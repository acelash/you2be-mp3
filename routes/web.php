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

Route::group(
    [
        'prefix' => 'en',
    ],
    function () {
        Route::get('/', 'HomeController@index')->name('home_en');
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
        'middleware' => ['auth', 'admin']
    ],
    function () {

        Route::get('home', 'AdminController@index')->name('admin_panel');
        Route::get('users', 'UsersController@index');
        Route::get('users/{id}', 'UsersController@show')->where('id', '[0-9]+');
        Route::post('users/{id}', 'UsersController@update')->where('id', '[0-9]+');
        Route::get('users/delete/{id}', 'UsersController@destroy')->where('id', '[0-9]+');
        Route::get('users/datatable', 'UsersController@datatable');

        Route::get('pages', 'PagesController@index');
        Route::post('pages', 'PagesController@store');
        Route::get('pages/new', 'PagesController@showAddForm');
        Route::get('pages/{id}', 'PagesController@show')->where('id', '[0-9]+');
        Route::post('pages/{id}', 'PagesController@update')->where('id', '[0-9]+');
        Route::get('pages/delete/{id}', 'PagesController@destroy')->where('id', '[0-9]+');
        Route::get('pages/datatable', 'PagesController@datatable');

        Route::get('genres', 'GenresController@index');
        Route::post('genres', 'GenresController@store');
        Route::get('genres/new', 'GenresController@showAddForm');
        Route::get('genres/{id}', 'GenresController@show')->where('id', '[0-9]+');
        Route::post('genres/{id}', 'GenresController@update')->where('id', '[0-9]+');
        Route::get('genres/delete/{id}', 'GenresController@destroy')->where('id', '[0-9]+');
        Route::get('genres/datatable', 'GenresController@datatable');

        Route::get('countries', 'CountriesController@index');
        Route::post('countries', 'CountriesController@store');
        Route::get('countries/new', 'CountriesController@showAddForm');
        Route::get('countries/{id}', 'CountriesController@show')->where('id', '[0-9]+');
        Route::post('countries/{id}', 'CountriesController@update')->where('id', '[0-9]+');
        Route::get('countries/delete/{id}', 'CountriesController@destroy')->where('id', '[0-9]+');
        Route::get('countries/datatable', 'CountriesController@datatable');

        Route::get('banners', 'BannersController@index');
        Route::post('banners/{slug}', 'BannersController@update');

        Route::get('prices', 'PricesController@index');
        Route::post('prices/{slug}', 'PricesController@update');

        Route::get('songs', 'SongsController@index');
        Route::get('songs/check', 'SongsController@check');
        Route::get('songs/new', 'SongsController@storeDraft');
        Route::get('songs/{id}', 'SongsController@show')->where('id', '[0-9]+');
        Route::post('songs/{id}', 'SongsController@update')->where('id', '[0-9]+');
        Route::get('songs/delete/{id}', 'SongsController@destroy')->where('id', '[0-9]+');
        Route::get('songs/datatable', 'SongsController@datatable');

        Route::get('comments/moderate', 'CommentsController@moderate');
        Route::get('comments/approve/{id}', 'CommentsController@approve')->where('id', '[0-9]+');
        Route::get('comments/delete/{id}', 'CommentsController@destroy')->where('id', '[0-9]+');
        Route::get('comments/block/{id}', 'CommentsController@block')->where('id', '[0-9]+');

    }
);



