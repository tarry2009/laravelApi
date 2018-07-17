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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
/*
Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => '2',
        'redirect_uri' => 'http://api.local/home',
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect('http://api.local/oauth/authorize?'.$query);
});

Route::get('/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://api.local/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '1',
            'client_secret' => 'mR7k7ITv4f7DJqkwtfEOythkUAsy4GJ622hPkxe6',
            'redirect_uri' => 'http://api.local/callback',
            'code' => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});
*/
