<?php namespace App\Services;

class Dns {

	private $resolver;
	private $servers;
	
	public function __construct($servers){
		//echo $key;
		$this->resolver = new \Net_DNS2_Resolver(array('nameservers' => $servers, 'cache_type' => 'none'));
		$this->servers=$servers;
		
	}
	
	public function getRecords(\App\Zone $zone){
		$this->resolver->signTSIG(\Crypt::decrypt($zone->tsigname),\Crypt::decrypt($zone->tsigkey));
		$ret=array();
		try{
			$result =$this->resolver->query($zone->name, 'AXFR');
			foreach($result->answer as $rr){
				$ret[]=$rr;
			}
		} catch(\Net_DNS2_Exception $e) {
		
			echo "::query() failed: ", $e->getMessage(), "\n";
		}
		return $ret;
	}
	
	public function saveRecord($data,\App\Zone $zone){
		$updater = new \Net_DNS2_Updater($zone->name,array('nameservers' => $this->servers, 'cache_type' => 'none'));
		//TODO check if record type is authorized ?
		try {
			$record=\Net_DNS2_RR::fromString($data["name"].'.'.$zone->name.' '.$data["ttl"].' '.$data["type"].' '.$data["rdata"]);
			$updater->add($record);
			$updater->signTSIG(\Crypt::decrypt($zone->tsigname),\Crypt::decrypt($zone->tsigkey));
			$updater->update();
		} catch(\Net_DNS2_Exception $e) {
		
			echo "::update() failed: ", $e->getMessage(), "\n";
		}
	}
	
	public function removeRecord($data,\App\Zone $zone){
		$updater = new \Net_DNS2_Updater($zone->name,array('nameservers' => $this->servers, 'cache_type' => 'none'));
		try {
			$record=\Net_DNS2_RR::fromString($data["name"].'.'.$zone->name.' '.$data["ttl"].' '.$data["type"].' '.$data["rdata"]);
			$updater->delete($record);
			$updater->signTSIG(\Crypt::decrypt($zone->tsigname),\Crypt::decrypt($zone->tsigkey));
			$updater->update();
		} catch(\Net_DNS2_Exception $e) {
	
			echo "::update() failed: ", $e->getMessage(), "\n";
		}
	}

}
