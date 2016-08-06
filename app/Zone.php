<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'zone';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'tsigname', 'tsigkey', 'reverse'];

	public function getRecords(){
		$app=app();
		//var_dump($app['DnsService']);
		//return array();
		$rdata=$app['DnsService']->getRecords($this);

		$ret=array();
		foreach($rdata as $r){
			if($r->type!="SOA" && $r->type!="NS"){
				$ret[]=array(
						"name" => str_replace(".".$this->name,"",$r->name),
						"ttl" => $r->ttl,
						"class" => $r->class,
						"type" => $r->type,
						"rdata" => $r->type!="CNAME" ? $r->rrToString2() : rtrim($r->rrToString2(),".")
				);
			}
		}
		return $ret;

	}

	public function saveRecord($data){
		$app=app();
		$app['DnsService']->saveRecord($data,$this);
		return $this->getRecords();
	}

	public function removeRecord($data){
		$app=app();
		$app['DnsService']->removeRecord($data,$this);
		return $this->getRecords();
	}

}
