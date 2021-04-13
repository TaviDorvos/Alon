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

Route::get('/', function () {
    return view('home');
});

// AUTHENTICATION ROUTES
// ----LOGIN ROUTES
//creating the login view
Route::get('/login', 'App\Http\Controllers\AuthController@loginCreate');

//Checking the credentials for the login
Route::post('login', 'App\Http\Controllers\AuthController@loginStore');

//Log out function
Route::get('/logout', 'App\Http\Controllers\AuthController@destroy');
// -----------------------------------------------------------------------

// ----REGISTER ROUTES
//creating the register view
Route::get('/register', 'App\Http\Controllers\AuthController@registerCreate');

//storing the data from the register
Route::post('register', 'App\Http\Controllers\AuthController@registerStore');
//-------------------------------------------------------------------------------

// ----RESET PASSWORD ROUTES
//view for the page with the insert of an email account
Route::get('/forgot_password', 'App\Http\Controllers\AuthController@forgot');

//sending the email to the inserted email
Route::post('/send_email', 'App\Http\Controllers\AuthController@password');

//view for the page slug where I'm confirming the new password
Route::get('/password/reset/{token}', 'App\Http\Controllers\AuthController@getPassword');

//storing and reseting the new password
Route::post('/reset-password', 'App\Http\Controllers\AuthController@resetPassword');
//-------------------------------------------------------------------------------

// ARTICLES ROUTES
//Creating the view for the add a new article
Route::get('/add-article', 'App\Http\Controllers\ArticleController@create');

//Storing the data from adding a new article
Route::post('add-article', 'App\Http\Controllers\ArticleController@store');

//confirm message for a new article added
Route::get('/confirm', function () {
    return view('articles.confirm-added-article');
});

Route::get('search-results', 'App\Http\Controllers\ArticleController@search');
//-------------------------------------------------------------------------------------

