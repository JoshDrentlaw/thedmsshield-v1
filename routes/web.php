<?php

use App\Http\Controllers\MapsController;
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
    'creatures' => 'CreaturesController',
    'places' => 'PlacesController',
    'mapChatMessages' => 'MapChatMessagesController'
]);

Route::get('/', 'PagesController@index');

Route::get('/cypher_calculator', 'PagesController@cypher_calculator');

Route::put('/campaigns/{id}/{type}', 'CampaignsController@update');

Route::get('/campaigns/{campaign_id}/compendium', 'CampaignsController@compendium');
Route::get('/campaigns/{campaign_id}/compendium/creatures', 'CreaturesController@index');
Route::get('/campaigns/{campaign_id}/compendium/creatures/{creature_id}', 'CreaturesController@show');
Route::post('/creatures/show_component', 'CreaturesController@show_component');
Route::post('/creatures/show_to_players/{id}', 'CreaturesController@show_to_players');
Route::get('/campaigns/{campaign_id}/compendium/places', 'PlacesController@index');
Route::get('/campaigns/{campaign_id}/compendium/places/{place_id}', 'PlacesController@show');
Route::post('/places/show_component', 'PlacesController@show_component');
Route::post('/places/show_to_players/{id}', 'PlacesController@show_to_players');
Route::get('/campaigns/{campaign_id}/maps/{map_id}', 'MapsController@show');

Route::put('/maps/{id}/{type}', 'MapsController@update');
Route::post('/maps/user_map_color', 'MapsController@user_map_color');
Route::post('/maps/map_ping', 'MapsController@map_ping');

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::post('/dashboard/{id}/{type}', 'DashboardController@update');
Route::post('/dashboard/player_search', 'DashboardController@player_search');
Route::post('/dashboard/get_pending_players', 'DashboardController@get_pending_players');
Route::post('/dashboard/send_player_invite', 'DashboardController@send_player_invite');
Route::post('/dashboard/accept_map_invite', 'DashboardController@accept_map_invite');
Route::post('/dashboard/deny_map_invite', 'DashboardController@deny_map_invite');
Route::get('/message/{id}', 'DashboardController@message')->name('message');
Route::post('/messages/mark_message_read', 'MessagesController@mark_message_read');
Route::post('/messages/mark_message_unread', 'MessagesController@mark_message_unread');

Auth::routes();