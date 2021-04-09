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

//creating the register view
Route::get('/register', 'App\Http\Controllers\RegisterController@create');

//storing the data from the register
Route::post('register', 'App\Http\Controllers\RegisterController@store');

//creating the login view
Route::get('/login', 'App\Http\Controllers\LoginController@create');

//Checking the credentials for the login
Route::post('login', 'App\Http\Controllers\LoginController@store');

//Log out function
Route::get('/logout', 'App\Http\Controllers\LoginController@destroy');

//Creating the view for the add a new article
Route::get('/add-article', 'App\Http\Controllers\ArticleController@create');

//Storing the data from adding a new article
Route::post('add-article', 'App\Http\Controllers\ArticleController@store');

//confirm message for a new article added
Route::get('/confirm', function () {
    return view('articles.confirm-added-article');
});

Route::get('search-results', 'App\Http\Controllers\ArticleController@search');

//view for the page with the insert of an email account
Route::get('/forgot_password', 'App\Http\Controllers\ForgotPasswordController@forgot');

//sending the email to the inserted email
Route::post('/send_email', 'App\Http\Controllers\ForgotPasswordController@password');

//view for the page slug where I'm confirming the new password
Route::get('/password/reset/{token}', 'App\Http\Controllers\ForgotPasswordController@getPassword');

//storing and reseting the new password
Route::post('/reset-password', 'App\Http\Controllers\ForgotPasswordController@resetPassword');