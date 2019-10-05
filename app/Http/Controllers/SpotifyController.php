<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SpotifyWebAPI;
use SpotifyWebAPI\Session;

class SpotifyController extends Controller
{
    /** @var Session */
    protected $session;
    /** @var SpotifyWebAPI\SpotifyWebAPI */
    protected $api;

    public function __construct()
    {
        $this->session = new Session(
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
     * @return ViewFactory|View
     */
    public function callback(Request $request)
    {
        if (!$request->session()->has('access-token')) {
            $this->session->requestAccessToken($_GET['code']);
            $accessToken = $this->session->getAccessToken();
            $request->session()->put('access-token', $accessToken);
        } else {
            $accessToken = $request->session()->get('access-token');
        }

        $this->api->setAccessToken($accessToken);
        $recentTracks = $this->api->getMyRecentTracks(['limit' => 10]);

        return view('charts', ['recentTracks' => $recentTracks->items]);
    }
}
