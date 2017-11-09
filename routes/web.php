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

Auth::routes();

Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
Route::get('/callback/{provider}', 'SocialAuthController@callback');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/lang/{slug}', 'HomeController@switchLang')->name('lang');
Route::get('/song/store_download/{id}', 'SongsController@storeDownload')->name('store_download');
Route::get('/copyrights', 'HomeController@copyrights')->name('copyrights');

Route::group(
    [
        'prefix' => 'en',
    ],
    function () {
        Route::get('/', 'HomeController@index')->name('home_en');
        Route::get('/copyrights', 'HomeController@copyrights')->name('copyrights_en');
        Route::get('/pre_search', 'SongsController@preSearch')->name('pre_search_en');
        Route::get('/search/{q}', 'SongsController@search')->name('search_en');
        Route::get('/popular', 'SongsController@popular')->name('popular_en');
        Route::get('/new', 'SongsController@newSongs')->name('new_en');
        Route::get('/rules', 'HomeController@rules')->name('rules_en');
        Route::get('/song/{slug}', 'SongsController@show')->name('show_song_en');
        Route::get('/tag/{slug}', 'SongsController@tag')->name('show_tag_en');
    });

Route::group(
    [
        'prefix' => 'ru',
    ],
    function () {
        Route::get('/', 'HomeController@index')->name('home_ru');
        Route::get('/copyrights', 'HomeController@copyrights')->name('copyrights_ru');
        Route::get('/pre_search', 'SongsController@preSearch')->name('pre_search_ru');
        Route::get('/search/{q}', 'SongsController@search')->name('search_ru');
        Route::get('/popular', 'SongsController@popular')->name('popular_ru');
        Route::get('/new', 'SongsController@newSongs')->name('new_ru');
        Route::get('/rules', 'HomeController@rules')->name('rules_ru');
        Route::get('/song/{slug}', 'SongsController@show')->name('show_song_ru');
        Route::get('/tag/{slug}', 'SongsController@tag')->name('show_tag_ru');
    });





Route::group(
    [
        'namespace' => 'Admin',
        'prefix' => 'admin',
        'middleware' => ['auth', 'admin','doNotCacheResponse']
    ],
    function () {

        Route::get('home', 'AdminController@index')->name('admin_panel');
        Route::get('users', 'UsersController@index');
        Route::get('users/{id}', 'UsersController@show')->where('id', '[0-9]+');
        Route::post('users/{id}', 'UsersController@update')->where('id', '[0-9]+');
        Route::get('users/delete/{id}', 'UsersController@destroy')->where('id', '[0-9]+');
        Route::get('users/datatable', 'UsersController@datatable');

        Route::get('songs', 'SongsController@index');
        Route::get('songs/approve', 'SongsController@approve');
        Route::get('songs/store_approve/{id}/{type}', 'SongsController@storeApprove');
        Route::get('songs/new', 'SongsController@storeDraft');
        Route::get('songs/{id}', 'SongsController@show')->where('id', '[0-9]+');
        Route::post('songs/{id}', 'SongsController@update')->where('id', '[0-9]+');
        Route::get('songs/delete/{id}', 'SongsController@destroy')->where('id', '[0-9]+');
        Route::get('songs/datatable', 'SongsController@datatable');
    }
);



