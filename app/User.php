<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract/*, CanResetPasswordContract*/ {

	use Authenticatable/*, CanResetPassword*/;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['username', 'lastname', 'firstname','role'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = ['password', 'remember_token'];
	public function zones(){
		//return all zones for role admin
		if($this->role=="admin"){
			return Zone::all();
		}
		return $this->belongsToMany('App\Zone', 'user_zone','user', 'zone');
	}

	public function zone($id){

		if($this->role=="admin"){
			return Zone::find($id)->firstOrFail();
		}

		return $this->belongsToMany('App\Zone', 'user_zone','user', 'zone')->getQuery()->whereRaw('id = '.$id)->firstOrFail();
	}
	
	public function uzones(){
		return $this->belongsToMany('App\Zone', 'user_zone','user', 'zone');
	}
	
	public function syncZones($zones){
		
		//manage zone access
		$newids=[];
		foreach($zones as $zone){
			//echo $zone["id"].",";
			$newids[]=$zone["id"];
		}
		//var_dump($newids);
		//die();
		$this->uzones()->sync($newids);
		
	}
	/*public function getAccountsAttribute()
	{
		$data = $this->accounts()->getQuery()->whereRaw('active = 1')->orderBy('name', 'asc')->get();
		return $data;
	}*/
}
