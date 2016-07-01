<?php namespace App\Http\Controllers;

class ZoneController extends Controller {

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
	}

	public function getTest(){
		$app=app();
		//var_dump($app['DnsService']);
		//return array();
		echo $app['DnsService']->test();
	}

	private function getAllZones(){
		$zones=\Auth::user()->zones();

		$ret=array();
		foreach($zones as $z){
			$ret[]=array(
			"id" => $z->id,
			"name" => $z->name,
			"reverse" => $z->reverse,
			"hastsigname" => $z->tsigname!=null && $z->tsigname!="",
		    "hastsigkey" => $z->tsigkey!=null && $z->tsigkey!=""
			);
		}

		return \Response::json( $ret);

	}

	/**
	 * list user zones.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
			return $this->getAllZones();
	}

	public function getGet()
	{
		$zone=\Auth::user()->zone(\Input::get("id"));
		$ret=array(
			'id' => $zone->id,
			'name' => $zone->name,
			'reverse' => $zone->reverse,
			'records' => $zone->getRecords()
		);

		return \Response::json( $ret);
	}

	public function postRecord(){
		$zone=\Auth::user()->zone(\Input::get("zid"));
		if($zone!=null){
			//TODO validate input
			//name ttl type rdata
			$validator = \Validator::make(
				\Input::all(),
				[
					'name' => 'required',
					'ttl' => 'required',
					'type' => 'required',
					'rdata'	 => 'required'
				]
			);
			if ($validator->fails()){
				// The given data did not pass validation
				abort(400);
			}
			$records = $zone->saveRecord(\Input::all());
			return \Response::json( $records);
		}else{
			abort(401);
		}

	}

	public function postSave(){
		if(\Auth::user()->role!="admin"){
			abort(401);
		}
		$data = \Input::all();
		$validator = \Validator::make(
				$data,
				[
						'name' => 'required'
				]
		);
		if ($validator->fails()){
			// The given data did not pass validation
			abort(400);
		}
		if(isset($data["tsigname"]) && $data["tsigname"]!=""){
			$data["tsigname"]=\Crypt::encrypt($data["tsigname"]);
		}
		if(isset($data["tsigkey"]) && $data["tsigkey"]!=""){
			$data["tsigkey"]=\Crypt::encrypt($data["tsigkey"]);
		}
		//var_dump($data);
		if(\Input::has('id')){
			$zone = \App\Zone::find(\Input::get("id"));
			$zone->update($data);//$zone->name = \Input::get("name");
		}else{

			$zone = \App\Zone::create($data);
		}

		return $this->getAllZones();
	}
	public function postRemove(){
		if(\Auth::user()->role!="admin"){
			abort(401);
		}
		\App\Zone::destroy(\Input::get("id"));

		return $this->getAllZones();
	}

	public function postRemoverecord(){
		$zone=\Auth::user()->zone(\Input::get("zid"));
		if($zone!=null){
			//TODO validate input

			$records = $zone->removeRecord(\Input::all());
			return \Response::json( $records);
		}else{
			abort(401);
		}

	}

}
