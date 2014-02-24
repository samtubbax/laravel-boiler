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

        if(Session::has('state'))
        {
            $state = Session::get('state');
        }
        else
        {
            $state = md5(rand());
            Session::put('state', $state);
        }

        $invalidLogin = Session::get('message');
        return View::make('users.login')->with('invalidLogin', $invalidLogin)->with('state', $state)->with('client_id', Config::get('app.tokens.google_client_id'));
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

            $redirectURl = (Input::get('redirect') == '')? '/' : urldecode(Input::get('redirect'));

            if (Auth::attempt($userdata)) {
                return Redirect::to($redirectURl);
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

    public function getFacebook()
    {
        $facebook = new Facebook(array(
            'appId' => Config::get('app.tokens.facebook_key'),
            'secret' => Config::get('app.tokens.facebook_secret'),
        ));


        $fbId = $facebook->getUser();
        if($fbId == 0)
        {
            return Redirect::to($facebook->getLoginUrl(array(
                'scope' => 'email,user_friends'
            )));
        }
        else
        {
            // user exists
            if(User::where('facebook_id', $fbId)->count() > 0)
            {
                $userId = User::where('facebook_id', $fbId)->first()->id;
                Auth::loginUsingId($userId);
            }
            else
            {
                if(Auth::check())
                {
                    $user = Auth::user();
                    $user->facebook_id = $fbId;
                }
                else
                {
                    $user = new User();
                    $user->facebook_id = $fbId;

                    $info = $facebook->api('/me');
                    $user->name = $info['name'];
                    $user->email = $info['email'];
                    $user->save();

                    // Get image
                    $browser = new Browser();
                    $avatar = $browser->get('http://graph.facebook.com/' . $fbId . '/picture?width=200');
                    $filename =  $user->id . '_' . time() . '.jpg';
                    file_put_contents(public_path() . '/files/users/source/' . $filename, $avatar->getContent());

                    $user->image = $filename;
                    $user->createThubmnails();
                    Auth::loginUsingId($user->id);
                }

                $user->save();
            }
        }

        return Redirect::to(Input::get('redirect', '/'));
    }

    public function getTwitter()
    {
        $twitter = new Twitter(Config::get('app.tokens.twitter_key'), Config::get('app.tokens.twitter_secret'));

        if(Request::get('oauth_token') == null)
        {
            if(Auth::check() && Auth::user()->twitter_id != null)
            {
                return Redirect::to('/');
            }

            $remember = Request::get('remember');

            $requestToken = $twitter->oAuthRequestToken(action('UserController@getTwitter', array('remember' => $remember)));
            $twitter->oAuthAuthenticate($requestToken['oauth_token']);
            exit;
        }
        else
        {
            $access = $twitter->oAuthAccessToken(Request::get('oauth_token'), Request::get('oauth_verifier'));
            $remember = Request::get('remember') != null;

            // user exists
            if(User::where('twitter_id', $access['user_id'])->count() > 0)
            {
                $userId = User::where('twitter_id', $access['user_id'])->first()->id;
                Auth::loginUsingId($userId, $remember);
            }
            else
            {
                if(Auth::check())
                {
                    $user = Auth::user();
                    $user->twitter_id = $access['user_id'];
                }
                else
                {
                    $user = new User();
                    $user->twitter_id = $access['user_id'];

                    $info = $twitter->usersShow($access['user_id']);

                    $user->name = $info['name'];
                    $user->email_verified = 0;
                    $user->save();

                    // Get image
                    $browser = new Browser();
                    $avatar = $browser->get($info['profile_image_url']);
                    $filename = $user->id . '_' . time() . '.' . File::extension($info['profile_image_url']);
                    file_put_contents(public_path() . '/files/users/source/' . $filename, $avatar->getContent());

                    $user->image = $filename;
                    $user->createThubmnails();

                    Auth::loginUsingId($user->id, $remember);
                    $user->save();

                    return Redirect::to('users/email');
                }

                $user->save();

            }

            return Redirect::to(Input::get('redirect', '/'));
        }
    }

    public function getGoogle()
    {
        $google = new Google_Client();
        $google->setApplicationName('Mobile Vikings Game');
        $google->setClientId(Config::get('app.tokens.google_client_id'));
        $google->setClientSecret(Config::get('app.tokens.google_client_secret'));
        $google->setRedirectUri('postmessage');

        $plus = new Google_PlusService($google);

        if (!Auth::check()) {
            if (Input::get('state') != Session::get('state')) {
                App::abort(401);
            }

            $google->authenticate(Input::get('code'));
            $token = json_decode($google->getAccessToken());

            $attributes = $google->verifyIdToken($token->id_token)->getAttributes();

            $gPlusId = $attributes["payload"]["sub"];
            Session::put('gtoken', $token);

            $user = new User();
            $user->google_id = $gPlusId;

            $info = $plus->people->get($gPlusId);

            $user->name = $info['displayName'];
            $user->email = $info['emails'][0]['value'];
            $user->save();

            // Get image
            $browser = new Browser();
            $imageUrl = $info['image']['url'];
            $imageUrl = str_replace('sz=50', 'sz=150', $imageUrl);
            $avatar = $browser->get($imageUrl);
            $filename = $user->id . '_' . time() . '.jpg';
            file_put_contents(public_path() . '/files/users/source/' . $filename, $avatar->getContent());

            $user->image = $filename;
            $user->createThubmnails();

            Auth::loginUsingId($user->id);
            $user->save();
        }

        return Redirect::to(Input::get('redirect', '/'));
    }

}