<?php

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    $response = $http->post(env('APP_URL') . '/oauth/token', [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => 2,
            'client_secret' => 'SWxa2Lcn6Iu85TcGoACZHCHLSiUpegWALDx1ht4t',
            'username' => $request->get('email'),
            'password' => $request->get('password'),
            'scope' => '',
        ],
    ]);

    return json_decode((string)$response->getBody(), true);
});

//pega todos os usuarios
Route::middleware('auth:api')->get('users', function () {
    return response()->json(\App\User::all());
});

//atualiza um funcionario
Route::middleware('auth:api')->put('users', function (Request $request) {
    /* @var $user User */
    $user = User::findOrFail($request->get('id'));

    $user->update($request->all());
    return response()->json($user);
});

//pega um usuario atraves de um id
Route::middleware('auth:api')->get('users/{id}', function ($id) {
    $user = User::findOrFail($id);
    return response()->json($user);
});

//cria novo usuario
Route::middleware('auth:api')->post('users', function (Request $request) {
    return response()->json(User::create($request->all()));
});

//deleta usuario
Route::middleware('auth:api')->delete('users/{id}', function ($id) {
    if (Auth::user()->id === $id) {
        return response()->json(['error' => "can't delete yourself"])->setStatusCode(404);
    }
    User::destroy($id);
    return response()->json(['message'=>'ok']);
});
