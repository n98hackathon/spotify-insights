<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use SpotifyWebAPI;
use SpotifyWebAPI\Session as SpotifySession;

class SpotifyController extends Controller
{
    /** @var SpotifySession */
    protected $session;

    /** @var SpotifyWebAPI\SpotifyWebAPI */
    protected $api;

    public function __construct()
    {
        $this->session = new SpotifySession(
            env('SPOTIFY_CLIENT_ID'),
            env('SPOTIFY_CLIENT_SECRET'),
            url('/callback')
        );

        $this->api = new SpotifyWebAPI\SpotifyWebAPI();
    }

    /**
     * @return ViewFactory|View
     */
    public function index()
    {
        $options = [
            'scope' => [
                'user-read-recently-played',
            ],
        ];

        return view('welcome', ['url' => $this->session->getAuthorizeUrl($options)]);
    }

    /**
     * @param Request $request
     *
     * @return ViewFactory|View
     */
    public function callback()
    {
        if (!Session::has('access-token') || $this->session->getTokenExpiration() === 0) {
            $this->session->requestAccessToken($_GET['code']);
            $accessToken = $this->session->getAccessToken();
            Session::put('access-token', $accessToken);
        } else {
            $accessToken = Session::get('access-token');
        }

        $this->api->setAccessToken($accessToken);

        return redirect('/statistics');
    }

    public function statistics()
    {
        $this->addApiAccessToken();
        $recentTracks = $this->api->getMyRecentTracks(['limit' => 50]);

        //Get Artist Array of all tracks
        $tracksWithArtists = array_map(function ($ar) {
            return [
                'artists' => array_map(function ($ar) {
                    return $ar->id;
                }, $ar->track->artists),
                'playedAt' => date('H', strtotime($ar->played_at))
            ];
        }, $recentTracks->items);

        foreach ($tracksWithArtists as &$trackArtists) {
            $artists = $this->api->getArtists($trackArtists['artists']);
            $trackArtists['genres'] = array_map(function ($ar) {
                return $ar->genres;
            }, $artists->artists);
            $trackArtists['genres'] = array_unique(array_merge(...$trackArtists['genres']));
        }

        ddd($tracksWithArtists);

        return view('charts', ['recentGenres' => $tracksWithArtists]);
    }

    protected function addApiAccessToken()
    {
        $accessToken = Session::get('access-token');
        $this->api->setAccessToken($accessToken);
    }
}
