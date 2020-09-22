<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::resources([
    'markers' => 'MarkersController',
    'maps' => 'MapsController',
    'profile' => 'ProfileController',
    'campaigns' => 'CampaignsController',
    'places' => 'PlacesController'
]);

Route::get('/', 'PagesController@index');

Route::get('/cypher_calculator', 'PagesController@cypher_calculator');

Route::put('campaigns/{id}/{type}', 'CampaignsController@update');

Route::put('maps/{id}/{type}', 'MapsController@update');

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/message/{id}', 'DashboardController@message')->name('message');
Route::post('/dashboard/{id}/{type}', 'DashboardController@update');
Route::post('/dashboard/player_search', 'DashboardController@player_search');
Route::post('/dashboard/get_pending_players', 'DashboardController@get_pending_players');
Route::post('/dashboard/send_player_invite', 'DashboardController@send_player_invite');
Route::post('/dashboard/accept_map_invite', 'DashboardController@accept_map_invite');
Route::post('/dashboard/deny_map_invite', 'DashboardController@deny_map_invite');

Auth::routes();