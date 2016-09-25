<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract {

	use Authenticatable;

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
		//else return user zones
		return $this->belongsToMany('App\Zone', 'user_zone','user', 'zone')->get();
	}

	public function zone($id){

		if($this->role=="admin"){
			return Zone::find($id);
		}
		return $this->belongsToMany('App\Zone', 'user_zone','user', 'zone')->getQuery()->whereRaw('id = '.$id)->first();
	}

	public function uzones(){
		return $this->belongsToMany('App\Zone', 'user_zone','user', 'zone')->get();
	}

	public function syncZones($zones){

		//manage zone access
		if( is_array( $zones ) || $zones instanceof \Traversable ){
			$newids=[];
			foreach($zones as $zone){
				$newids[]=$zone["id"];
			}
			$this->belongsToMany('App\Zone', 'user_zone','user', 'zone')->sync($newids);
		}
	}

	public function getRememberToken(){
		return null;
	}

	public function setRememberToken($value){

	}
	public function getRememberTokenName(){
		return null;
	}
}
