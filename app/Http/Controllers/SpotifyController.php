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
        $recentTracks = $this->api->getMyRecentTracks(['limit' => 10]);

        return view('charts', ['recentTracks' => $recentTracks->items]);
    }

    protected function addApiAccessToken()
    {
        $accessToken = Session::get('access-token');
        $this->api->setAccessToken($accessToken);
    }
}
