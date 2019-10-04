<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $session = new SpotifyWebAPI\Session(
        env('SPOTIFY_CLIENT_ID'),
        env('SPOTIFY_CLIENT_SECRET'),
        url('/')
    );

    $api = new SpotifyWebAPI\SpotifyWebAPI();

    if (isset($_GET['code'])) {
        $session->requestAccessToken($_GET['code']);
        $api->setAccessToken($session->getAccessToken());

        ddd($api->getMyRecentTracks(['limit' => 50]));
    } else {
        $options = [
            'scope' => [
                'user-read-recently-played',
            ],
        ];

        header('Location: ' . $session->getAuthorizeUrl($options));
        die();
    }
});
