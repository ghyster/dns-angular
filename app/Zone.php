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
	protected $fillable = ['name', 'server', 'tsigname', 'tsigkey', 'tsigalgo', 'reverse'];

	public function getRecords(){
		$app=app();
		//var_dump($app['DnsService']);
		//return array();
		$rdata=$app['DnsService']->getRecords($this);

		$ret=array();
		foreach($rdata as $r){
			if($r->type!="SOA" && $r->type!="NS"){

				$rdata=$r->type!="CNAME" ? $app['DnsService']->getRecordValue($r) : rtrim($app['DnsService']->getRecordValue($r),".");

				$record=array(
						"name" => ($r->name!=$this->name) ? str_replace(".".$this->name,"",$r->name) : "",
						"ttl" => $r->ttl,
						"class" => $r->class,
						"type" => $r->type,
						//"rdata" => $r->type!="CNAME" ? $r->rrToString() : rtrim($r->rrToString(),".")
						"rdata" => $rdata
				);

				if($r->type=="SRV"){
					$values=explode(" ",$app['DnsService']->getRecordValue($r));
					$record['rdata']=$values[count($values)-1];
					$record['priority']=intval($values[0]);
					$record['weight']=intval($values[1]);
					$record['port']=intval($values[2]);
				}

				if($r->type=="MX"){
					$values=explode(" ",$app['DnsService']->getRecordValue($r));
					$record['rdata']=$values[count($values)-1];
					$record['priority']=intval($values[0]);
				}

				if($r->type=="CAA"){
					$values=explode(" ",$app['DnsService']->getRecordValue($r));
					$record['rdata']=str_replace(array('"','mailto:'),"",$values[count($values)-1]);
					$record['flag']=intval($values[0]);
					$record['tag']=$values[1];
				}
				$ret[]=$record;
			}
		}
		return $ret;

	}

	public function saveRecord($data){
		$app=app();
		if($data["type"]=="SRV"){
			if(array_key_exists("service", $data)){
				$name=$data["service"].".".$data["protocol"].(isset($data["name"]) && $data["name"]!="" ? '.'.$data["name"] : '');
				$data["name"]=$name;
			}
			$rdata=$data["priority"]." ".$data["weight"]." ".$data["port"]." ".$data["rdata"];
			$data["rdata"]=$rdata;

			if(array_key_exists("ori", $data)){

				$rdata=$data["ori"]["priority"]." ".$data["ori"]["weight"]." ".$data["ori"]["port"]." ".$data["ori"]["rdata"];
				$data["ori"]["rdata"]=$rdata;

			}
		}

		if($data["type"]=="MX"){
			$rdata=$data["priority"]." ".$data["rdata"];
			$data["rdata"]=$rdata;
			if(array_key_exists("ori", $data)){
				$rdata=$data["ori"]["priority"]." ".$data["ori"]["rdata"];
				$data["ori"]["rdata"]=$rdata;
			}
		}

		if($data["type"]=="CAA"){
			$rvalue=$data["rdata"];
			if(substr($rvalue,0,1)!='"'){
				if($data["tag"]=="iodef" && substr($rvalue,0,7)!="mailto:"){
					$rvalue='mailto:'.$rvalue;
				}
				$rvalue='"'.$rvalue;
			}
			if(substr($rvalue,-1)!='"'){
				$rvalue=$rvalue.'"';
			}

			$rdata=$data["flag"]." ".$data["tag"]." ".$rvalue;
			$data["rdata"]=$rdata;



			if(array_key_exists("ori", $data)){
				$rvalue=$data["ori"]["rdata"];
				if(substr($rvalue,0,1)!='"'){
					if($data["ori"]["tag"]=="iodef" && substr($rvalue,0,7)!="mailto:"){
						$rvalue='mailto:'.$rvalue;
					}
					$rvalue='"'.$rvalue;
				}
				if(substr($rvalue,-1)!='"'){
					$rvalue=$rvalue.'"';
				}

				$rdata=$data["ori"]["flag"]." ".$data["ori"]["tag"]." ".$rvalue;
				$data["ori"]["rdata"]=$rdata;
			}
		}

		$app['DnsService']->saveRecord($data,$this);
		return $this->getRecords();
	}

	public function removeRecord($data){
		$app=app();

		if($data["type"]=="SRV"){
			$rdata=$data["priority"]." ".$data["weight"]." ".$data["port"]." ".$data["rdata"];
			$data["rdata"]=$rdata;
		}

		if($data["type"]=="MX"){
			$rdata=$data["priority"]." ".$data["rdata"];
			$data["rdata"]=$rdata;
		}
		if($data["type"]=="CAA"){
			$rvalue=$data["rdata"];
			if(substr($rvalue,0,1)!='"'){
				if($data["tag"]=="iodef" && substr($rvalue,0,7)!="mailto:"){
					$rvalue='mailto:'.$rvalue;
				}
				$rvalue='"'.$rvalue;
			}
			if(substr($rvalue,-1)!='"'){
				$rvalue=$rvalue.'"';
			}

			$rdata=$data["flag"]." ".$data["tag"]." ".$rvalue;
			$data["rdata"]=$rdata;
		}

		$app['DnsService']->removeRecord($data,$this);
		return $this->getRecords();
	}

}
