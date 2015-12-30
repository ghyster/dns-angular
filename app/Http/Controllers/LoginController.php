<?php namespace App\Http\Controllers;

//use Google\Client;
//use Google\Service\Oauth2;
class LoginController extends Controller {

	protected $layout = 'layouts.login';

	public function __construct()
	{
		//$this->beforeFilter('csrf', array('on' => 'post'));
	}

	   public function getIndex()
    {
        $avatar = Cookie::get('avatar')=="" ? "0" : Cookie::get('avatar');

        $this->layout->with('avatar', $avatar);
    }

    public function getGoogle()
   {
     return \View::make('login.google');
   }

    public function postIndex()
    {
    	/*$hash=Hash::make(Input::get('password'));
    	echo $hash;
    	die();*/
    	if (trim(Input::get('password'))!="" && Auth::attempt(array('mail'=>Input::get('mail'), 'password'=>Input::get('password')),true)) {
    	   $cookie = Cookie::forever('avatar', Auth::user()->id);
    	   return Redirect::to('/')->withCookie($cookie);
		} else {
		   return Redirect::to('/login')
		      ->with('message', 'Your username/password combination was incorrect')
		      ->withInput();
		}
    }

    public function getGooglelogout(){
    	$this->layout = null;
    	return \View::make('login.googlelogout');
    }

    public function postGoogle(){

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
      $client->authenticate(\Input::get("access_token"));

    	$token = json_decode($client->getAccessToken());

    	$oauth2 = new \Google_Service_Oauth2($client);
    	//$ouser = $oauth2->tokeninfo(array( "access_token" => $token->access_token));
    	$ouser = $oauth2->userinfo->get();
    	$user=\App\User::where("username","=",$ouser["email"])->get()->first();
    	if($user!=null){
      		\Auth::login($user/*,true*/);	    	
	    	return \Redirect::to('/');
    	}else{
    		//if first user then create it and login
    		$count=\App\User::all()->count();
    		if($count==0){
    			$data=array();
    			$data['lastname']=$ouser["familyName"];
    			$data['firstname']=$ouser["givenName"];
    			$data['username']=$ouser["email"];
    			$data['role']="admin";
    			$user=\App\User::create($data);
    			\Auth::login($user);
    			return \Redirect::to('/');
    		}else{
        		abort(401);
    		}	

    		//we register the non existing user
    		//var_dump($ouser);
    		/*{ ["email"]=> string(21) "ghyster.guu@gmail.com"
    		 * ["familyName"]=> string(3) "Guu"
    		 * ["gender"]=> NULL
    		 * ["givenName"]=> string(7) "Ghyster"
    		 * ["hd"]=> NULL
    		 * ["id"]=> string(21) "117520916275417916022"
    		 * ["link"]=> string(45) "https://plus.google.com/117520916275417916022"
    		 * ["locale"]=> string(2) "fr"
    		 * ["name"]=> string(11) "Ghyster Guu"
    		 * ["picture"]=> string(92) "https://lh5.googleusercontent.com/-p2h7EhpOdvs/AAAAAAAAAAI/AAAAAAAAABQ/JlbQ9tHs9-w/photo.jpg"
    		 * ["verifiedEmail"]=> bool(true)
    		 * ["modelData":protected]=> array(3) {
    		 * 		["verified_email"]=> bool(true)
    		 * 		["given_name"]=> string(7) "Ghyster"
    		 * 		["family_name"]=> string(3) "Guu"
    		 * }
    		 * ["processed":protected]=> array(0) { }
    		 * }
    		*/
    		/*$data=array();
    		$data['lastname']=$ouser["familyName"];
    		$data['firstname']=$ouser["givenName"];
    		$data['mail']=$ouser["email"];
    		$data['password']="";
    		$user=User::create($data);
    		$user->uid=$user->id;
    		$user->save();
    		File::put(Config::get("app.avatar_path").DIRECTORY_SEPARATOR. $user->id.".jpg",file_get_contents($ouser["picture"]."?sz=96"));

    		Auth::login($user,true);
    		$cookie = Cookie::forever('avatar', Auth::user()->id);
    		return Redirect::to('/user/settings')->withCookie($cookie);
        */
    		//die();
    	}

    }

    public function getLogout(){
    	\Auth::logout();
    	return \Redirect::to('/');
    }

}
