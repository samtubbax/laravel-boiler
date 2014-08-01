<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
    if (Auth::check())
    {
        // redirect to base action
    }
    else
    {
        return View::make('hello');
    }
});
Route::filter('copy', function()
{
    if (Config::get('app.debug'))
    {
        return Redirect::to('/');
    }
});
Route::controller('users', 'UserController');
Route::controller('copy', 'CopyController');