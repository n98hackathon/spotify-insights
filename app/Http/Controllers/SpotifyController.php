<?php

namespace App\Http\Controllers;

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

    public function index()
    {
        $options = [
            'scope' => [
                'user-read-recently-played',
            ],
        ];

        return view('welcome', ['url' => $this->session->getAuthorizeUrl($options)]);
    }

    public function callback()
    {
        $this->session->requestAccessToken($_GET['code']);
        $this->api->setAccessToken($this->session->getAccessToken());

        $recentTracks = $this->api->getMyRecentTracks(['limit' => 10]);
        ddd($recentTracks->items);

        return view('charts', ['recentTracks' => $recentTracks->items]);
    }
}
