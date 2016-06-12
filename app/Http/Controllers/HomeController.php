<?php namespace App\Http\Controllers;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		if(!file_exists(storage_path('installed'))){
			//echo "application not installed";
			//die();
			return redirect('/install');
			//die();
		}
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(!file_exists(storage_path('installed'))){
			//echo "application not installed";
			//die();
			return redirect('/install');
			//die();
		}
		return view('home');
	}

}
