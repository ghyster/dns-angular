<?php

namespace App\Services;

class Dns
{
    private $resolver;
    private $servers;
    private $logging;
    private $autoreverse;

    public function __construct($servers, $logging, $autoreverse)
    {
        $this->resolver = new \Net_DNS2_Resolver(['nameservers' => $servers, 'cache_type' => 'none', 'recurse' => false]);
        $this->servers = $servers;
        $this->logging = $logging;
        $this->autoreverse = $autoreverse;
    }

    private function loggingenable()
    {
        return $this->logging == true;
    }

    private function managereverse()
    {
        return $this->autoreverse == true;
    }

    private function getReverseIP($ip)
    {
        $binary = inet_pton($ip);

        return ord($binary[3]).'.'.ord($binary[2]).'.'.ord($binary[1]).'.'.ord($binary[0]);
    }

    private function getPTR($zone, $ip)
    {
        $this->resolver->signTSIG(\Crypt::decrypt($zone->tsigname), \Crypt::decrypt($zone->tsigkey), \Crypt::decrypt($zone->tsigalgo));
        $result = $this->resolver->query($this->getReverseIP($ip).'.in-addr.arpa', 'PTR');

        $ret = [];
        foreach ($result->answer as $rr) {
            $ret[] = $rr;
        }
        //find authoritative zone
        $zoner = null;
        if ($result->header->aa == 1) {
            foreach ($result->authority as $a) {
                //try to find zone in db
                $zoner = \App\Zone::where('name', $a->name)->where('reverse', 1)->first();
                if ($zoner != null) {
                    break;
                }
            }
        }

        return ['zone' => $zoner, 'records' => $ret];
    }

    private function canAddPTR($zone, $ip)
    {
        $this->resolver->signTSIG(\Crypt::decrypt($zone->tsigname), \Crypt::decrypt($zone->tsigkey), \Crypt::decrypt($zone->tsigalgo));

        try {
            $result = $this->resolver->query($this->getReverseIP($ip).'.in-addr.arpa', 'PTR');
        } catch (\Net_DNS2_Exception $e) {
            $result = $e->getResponse();
        }
        $zoner = null;
        if ($result->header->aa == 1) {
            foreach ($result->authority as $a) {
                //try to find zone in db
                $zoner = \App\Zone::where('name', $a->name)->where('reverse', 1)->first();
                if ($zoner != null) {
                    break;
                }
            }
        }

        return $result->header->aa == 1 ? $zoner : null;
    }

    public function getRecords(\App\Zone $zone)
    {
        $ret = [];
        if ($zone->tsigname != '' && $zone->tsigkey != '') {
            $this->resolver->signTSIG(\Crypt::decrypt($zone->tsigname), \Crypt::decrypt($zone->tsigkey), \Crypt::decrypt($zone->tsigalgo));
            //try{
            $result = $this->resolver->query($zone->name, 'AXFR');
            foreach ($result->answer as $rr) {
                $ret[] = $rr;
            }
            /*} catch(\Net_DNS2_Exception $e) {
                abort(500, $e->getMessage());
                die();
            }*/
        }

        return $ret;
    }

    public function saveRecord($data, \App\Zone $zone)
    {
        $updater = new \Net_DNS2_Updater($zone->name, ['nameservers' => $this->servers, 'cache_type' => 'none', 'recurse' => false]);
        //TODO check if record type is authorized ?
        try {
            $rname = (isset($data['name']) && $data['name'] != '') ? $data['name'].'.'.$zone->name : $zone->name;
            $rvalue = $data['rdata'];
            if ($data['type'] == 'TXT') {
                if (substr($rvalue, 0, 1) != '"') {
                    $rvalue = '"'.$rvalue;
                }
                if (substr($rvalue, -1) != '"') {
                    $rvalue = $rvalue.'"';
                }
            }
            //dd($rvalue);
            //if record update, we delete the previous one
            if (array_key_exists('ori', $data)) {
                $ori = $data['ori'];
                $orirecord = \Net_DNS2_RR::fromString($rname.' '.$ori['ttl'].' '.$ori['type'].' '.$ori['rdata']);
                $updater->delete($orirecord);
                if ($this->loggingenable()) {
                    \Log::info(\Auth::user()->username.' DELETE '.$orirecord);
                }

                // if reverse record(s) exists delete it
                if ($ori['type'] == 'A' && $this->managereverse()) {
                    $revarr = $this->getPTR($zone, $ori['rdata']);
                    if ($revarr['zone'] != null) {
                        $zonerev = $revarr['zone'];
                        $updaterrev = new \Net_DNS2_Updater($zonerev->name, ['nameservers' => $this->servers, 'cache_type' => 'none', 'recurse' => false]);
                        foreach ($revarr['records'] as $rr) {
                            $updaterrev->delete($rr);
                            if ($this->loggingenable()) {
                                \Log::info(\Auth::user()->username.' DELETE '.$rr);
                            }
                        }
                        $updaterrev->signTSIG(\Crypt::decrypt($zonerev->tsigname), \Crypt::decrypt($zonerev->tsigkey), \Crypt::decrypt($zone->tsigalgo));
                        $updaterrev->update();
                    }
                }
            }

            $record = \Net_DNS2_RR::fromString($rname.' '.$data['ttl'].' '.$data['type'].' '.$rvalue);
            if (!$updater->add($record)) {
                echo 'error adding record to the updater';
            }
            $updater->signTSIG(\Crypt::decrypt($zone->tsigname), \Crypt::decrypt($zone->tsigkey), \Crypt::decrypt($zone->tsigalgo));
            $updater->update();

            if ($this->loggingenable()) {
                \Log::info(\Auth::user()->username.' CREATE '.$record);
            }
            // create reverse record if authoritative
            if ($data['type'] == 'A' && $this->managereverse()) {
                $zonerev = $this->canAddPTR($zone, $rvalue);
                if ($zonerev != null) {
                    $revrecord = \Net_DNS2_RR::fromString($this->getReverseIP($rvalue).'.in-addr.arpa '.$data['ttl'].' PTR '.$rname);
                    $updaterrev = new \Net_DNS2_Updater($zonerev->name, ['nameservers' => $this->servers, 'cache_type' => 'none', 'recurse' => false]);
                    $updaterrev->add($revrecord);
                    $updaterrev->signTSIG(\Crypt::decrypt($zonerev->tsigname), \Crypt::decrypt($zonerev->tsigkey), \Crypt::decrypt($zone->tsigalgo));
                    $updaterrev->update();
                    if ($this->loggingenable()) {
                        \Log::info(\Auth::user()->username.' CREATE '.$revrecord);
                    }
                }
            }
        } catch (\Net_DNS2_Exception $e) {
            echo '::update() failed: ', $e->getMessage(), "\n";
        }
        //die();
    }

    public function removeRecord($data, \App\Zone $zone)
    {
        $updater = new \Net_DNS2_Updater($zone->name, ['nameservers' => $this->servers, 'cache_type' => 'none']);

        try {
            $record = \Net_DNS2_RR::fromString($data['name'].'.'.$zone->name.' '.$data['ttl'].' '.$data['type'].' '.$data['rdata']);
            $updater->delete($record);
            $updater->signTSIG(\Crypt::decrypt($zone->tsigname), \Crypt::decrypt($zone->tsigkey), \Crypt::decrypt($zone->tsigalgo));
            $updater->update();
            if ($this->loggingenable()) {
                \Log::info(\Auth::user()->username.' DELETE '.$record);
            }
            //delete reverse record
            if ($data['type'] == 'A' && $this->managereverse()) {
                $revarr = $this->getPTR($zone, $data['rdata']);
                if ($revarr['zone'] != null) {
                    $zonerev = $revarr['zone'];
                    $updaterrev = new \Net_DNS2_Updater($zonerev->name, ['nameservers' => $this->servers, 'cache_type' => 'none', 'recurse' => false]);
                    foreach ($revarr['records'] as $rr) {
                        $updaterrev->delete($rr);
                        if ($this->loggingenable()) {
                            \Log::info(\Auth::user()->username.' DELETE '.$rr);
                        }
                    }
                    $updaterrev->signTSIG(\Crypt::decrypt($zonerev->tsigname), \Crypt::decrypt($zonerev->tsigkey), \Crypt::decrypt($zone->tsigalgo));
                    $updaterrev->update();
                }
            }
        } catch (\Net_DNS2_Exception $e) {
            echo '::update() failed: ', $e->getMessage(), "\n";
        }
    }

    public function getRecordValue($rr)
    {
        $full = $rr->__toString();
        $values = preg_split('/[\s]+/', $full);
        $f1 = array_shift($values);
        $f2 = array_shift($values);
        $f3 = array_shift($values);
        $f4 = array_shift($values);

        return implode(' ', $values);
    }
}
