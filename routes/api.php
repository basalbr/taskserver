<?php

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/', function () {
    return response()->json(['message' => 'Welcome to ' . env('app_name')]);
});

//Dashboard - Usuário
Route::group(['prefix' => 'register', 'namespace' => 'ApiAuth', 'middleware' => ['guest']], function () {
    Route::post('', ['as' => 'register', 'uses' => 'RegisterController@register']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return 'usuario';
});

Route::post('login', function (Request $request) {
    $http = new Client;

    $response = $http->post(env('APP_URL').'/oauth/token', [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => 2,
            'client_secret' => 'SWxa2Lcn6Iu85TcGoACZHCHLSiUpegWALDx1ht4t',
            'username' => $request->get('email'),
            'password' => $request->get('password'),
            'scope' => '',
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});