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
        if(Session::has('state'))
        {
            $state = Session::get('state');
        }
        else
        {
            $state = md5(rand());
            Session::put('state', $state);
        }

        return View::make('hello')->with('state', $state)->with('client_id', Config::get('app.tokens.google_client_id'));
    }
});

Route::controller('users', 'UserController');