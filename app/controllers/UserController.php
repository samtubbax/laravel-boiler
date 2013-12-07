<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

    /**
     * Display a login form
     *
     * @return Response
     */
    public function getLogin()
    {
        if (Auth::check())
        {
            return Redirect::to('/');
        }

        $invalidLogin = Session::get('message');
        return View::make('users.login')->with('invalidLogin', $invalidLogin);
    }

    /**
     * Do the login dance
     *
     * @return Response
     */
    public function postLogin()
    {
        if (Auth::check())
        {
            return Redirect::to('/');
        }

        $rules = array(
            'email'    => 'required|email',
            'password' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('users/login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {

            $userdata = array(
                'email' 	=> Input::get('email'),
                'password' 	=> Input::get('password')
            );

            if (Auth::attempt($userdata)) {
                return Redirect::to('/');
            } else {

                // validation not successful, send back to form
                return Redirect::to('users/login')->with('message', Lang::get('users.loginFailed'));;

            }

        }
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
        if (Auth::check())
        {
            return Redirect::to('/');
        }
        return View::make('users.signup');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postCreate()
	{
        $rules = array(
            'email'    => 'required|email|unique:users',
            'name'    => 'required',
            'password' => 'required|min:8|confirmed'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('users/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {

            $userdata = array(
                'email' 	=> Input::get('email'),
                'name'      => Input::get('name'),
                'password' 	=> Input::get('password')
            );

            $user = User::create($userdata);
            $user->password = Hash::make($userdata['password']);
            $user->save();

            Auth::loginUsingId($user->id);
            return Redirect::to('/');
        }
	}

    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('/');
    }

}