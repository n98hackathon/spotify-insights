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
        $this->session->requestAccessToken($_GET['code']);
        $accessToken = $this->session->getAccessToken();
        Session::put('access-token', $accessToken);

        return redirect('/statistics');
    }

    public function statistics()
    {
        $this->addApiAccessToken();
        $recentTracks = $this->api->getMyRecentTracks(['limit' => 20]);

        //Get Artist Array of all tracks
        $trackArtists = array_map(function ($ar) {
            return [
                'artists' => array_map(function ($ar) {
                    return $ar->id;
                }, $ar->track->artists),
                'playedAt' => $ar->played_at
            ];
        }, $recentTracks->items);

        ddd($trackArtists);
        //Merge to single artist array
        $trackArtists = array_merge(...$trackArtists);

        ddd($trackArtists);
        //Map to Artist Ids
        $artistIds = array_map(function ($ar) {
            return id;
        }, $trackArtists);

        $artists = $this->api->getArtists($artistIds);
        $artists = $artists->artists;

        $genres = array_map(function ($ar) {
            return $ar->genres;
        }, $artists);
        $genres = array_merge(...$genres);
        ddd($genres);

        return view('charts', ['recentTracks' => $recentTracks->items]);
    }

    protected function addApiAccessToken()
    {
        $accessToken = Session::get('access-token');
        $this->api->setAccessToken($accessToken);
    }
}
