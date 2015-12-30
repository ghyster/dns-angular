<?php namespace App\Http\Controllers;

class UserController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Zone Controller
	|--------------------------------------------------------------------------
	|
	|
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		if(\Auth::user()->role!="admin"){
			abort(401);
		}
	}

	private function getAllUsers(){
		$users=\App\User::all();

		$ret=array();
		//$zones=[];
		foreach($users as $z){
			$uzones=[];
			if($z->role!="admin"){
				$zones=$z->zones()->get();
				//var_dump($zones);
				
				foreach($zones as $zo){
					//echo $zo->id;
					$uzones[]=array(
					"id" => $zo->id,
					"name" => $zo->name
					);
				}
			}

			$ret[]=array(
				"id" => $z->id,
				"username" => $z->username,
				"lastname" => $z->lastname,
				"firstname" => $z->firstname,
				"role" => $z->role,
				"zones" => $uzones
			);
		}

		return \Response::json( $ret);

	}

	/**
	 * list user zones.
	 *
	 * @return Response
	 */
	public function getAll(){
		return $this->getAllUsers();
	}

	public function postSave(){

		$data = \Input::all();
		
		$validator = \Validator::make(
				$data,
				[
						'username' => 'required'
				]
		);
		if ($validator->fails()){
			// The given data did not pass validation
			abort(400);
		}
		if(\Input::has('id')){
			$user = \App\User::find(\Input::get("id"));
			$user->update($data);//$zone->name = \Input::get("name");
		}else{

			$user = \App\User::create($data);
		}
		
		$user->syncZones(\Input::get("zones"));
		
		
		return $this->getAllUsers();
	}
	public function postRemove(){

		\App\User::destroy(\Input::get("id"));

		return $this->getAllUsers();

	}

}
