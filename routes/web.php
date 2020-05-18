<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'PagesController@index');

Route::get('/cypher_calculator', 'PagesController@cypher_calculator');

/* Route::get('/cypher-roller/{id}', function ($id) {
    return view('pages.cypher-roller.'.$id);
}); */

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
