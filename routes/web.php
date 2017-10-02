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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/rules', 'HomeController@rules')->name('rules');
Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
Route::get('/callback/{provider}', 'SocialAuthController@callback');

Route::get('/movies', 'MoviesController@showCatalog')->name('catalog');
Route::get('/movies/{slug}/{id}', 'MoviesController@showCatalog')->name('catalog_filtered');
Route::get('/movie/{slug}', 'MoviesController@show')->name('show_movie');
Route::get('/movie/store_view/{id}', 'MoviesController@storeView');



Route::group(['middleware' => ['auth']], function () {

    Route::get('/profile', 'UserController@profile')->name('profile');
    Route::get('/seen', 'UserController@seen')->name('seen');
    Route::get('/watch_later', 'UserController@watchLater')->name('watch_later');

    Route::get('/profile_edit', 'UserController@showEditForm')->name('profile_form');
    Route::post('/update_user', 'UserController@updateProfile')->name('update_user');

    Route::get('/change_password', 'UserController@showPasswordForm')->name('password_form');
    Route::post('/update_password', 'UserController@updatePassword')->name('update_password');

    Route::post('/comment', 'CommentsController@store')->name('store_comment');
    Route::get('/movie/toggle_likes/{id}/{type}', 'MoviesController@toggleLikes')->name('toggle_likes');
    Route::get('/movie/toggle_seen/{id}', 'MoviesController@toggleSeen')->name('toggle_seen');
    Route::get('/movie/toggle_watch_later/{id}', 'MoviesController@toggleWatchLater')->name('toggle_watch_later');

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

        Route::get('movies', 'MoviesController@index');
        Route::get('movies/check', 'MoviesController@check');
        Route::get('movies/new', 'MoviesController@storeDraft');
        Route::get('movies/{id}', 'MoviesController@show')->where('id', '[0-9]+');
        Route::post('movies/{id}', 'MoviesController@update')->where('id', '[0-9]+');
        Route::get('movies/delete/{id}', 'MoviesController@destroy')->where('id', '[0-9]+');
        Route::get('movies/datatable', 'MoviesController@datatable');

        Route::get('comments/moderate', 'CommentsController@moderate');
        Route::get('comments/approve/{id}', 'CommentsController@approve')->where('id', '[0-9]+');
        Route::get('comments/delete/{id}', 'CommentsController@destroy')->where('id', '[0-9]+');
        Route::get('comments/block/{id}', 'CommentsController@block')->where('id', '[0-9]+');

    }
);



