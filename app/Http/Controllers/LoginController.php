<?php

namespace App\Http\Controllers;

//use Google\Client;
//use Google\Service\Oauth2;
class LoginController extends Controller
{
    protected $layout = 'layouts.login';

    public function __construct()
    {
        //$this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function getIndex()
    {
        $avatar = Cookie::get('avatar') == '' ? '0' : Cookie::get('avatar');

        $this->layout->with('avatar', $avatar);
    }

    public function google()
    {
        return \View::make('login.google');
    }

    public function postIndex()
    {
        /*$hash=Hash::make(Input::get('password'));
    	echo $hash;
    	die();*/
        if (trim(Input::get('password')) != '' && Auth::attempt(['mail'=>Input::get('mail'), 'password'=>Input::get('password')], true)) {
            $cookie = Cookie::forever('avatar', Auth::user()->id);

            return Redirect::to('/')->withCookie($cookie);
        } else {
            return Redirect::to('/login')
              ->with('message', 'Your username/password combination was incorrect')
              ->withInput();
        }
    }

    public function getGooglelogout()
    {
        $this->layout = null;

        return \View::make('login.googlelogout');
    }

    public function postGoogle()
    {
        $client = new \Google_Client();
        //$client->setAccessToken(Input::get("access_token"));
        $client->setApplicationName(\Config::get('app.google_application_name'));
        $client->setClientId(\Config::get('app.google_client_id'));
        $client->setClientSecret(\Config::get('app.google_client_secret'));
        //$client->setRedirectUri(\Config::get('app.google_redirect_uri'));
        $client->setRedirectUri('postmessage');
        //$client->setScopes('email');
        //$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
        //var_dump($client->getHttpClient());
        //  die();
        $client->authenticate(\Input::get('access_token'));

        $token = json_decode($client->getAccessToken());

        $oauth2 = new \Google_Service_Oauth2($client);
        //$ouser = $oauth2->tokeninfo(array( "access_token" => $token->access_token));
        $ouser = $oauth2->userinfo->get();
        $user = \App\User::where('username', '=', $ouser['email'])->get()->first();
        if ($user != null) {
            \Auth::login($user/*,true*/);

            return \Redirect::to('/');
        } else {
            //if first user then create it and login
            $count = \App\User::all()->count();
            if ($count == 0) {
                $data = [];
                $data['lastname'] = $ouser['familyName'];
                $data['firstname'] = $ouser['givenName'];
                $data['username'] = $ouser['email'];
                $data['role'] = 'admin';
                $user = \App\User::create($data);
                \Auth::login($user);

                return \Redirect::to('/');
            } else {
                abort(401);
            }
        }
    }

    public function logout()
    {
        \Auth::logout();

        return \View::make('login.logout');
        //return \Redirect::to('/');
    }
}
