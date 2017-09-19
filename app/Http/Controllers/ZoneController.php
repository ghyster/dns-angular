<?php

namespace App\Http\Controllers;

class ZoneController extends Controller
{
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

    public function getAllZones()
    {
        $zones = \Auth::user()->zones();
        $ret = [];
        foreach ($zones as $z) {
            $ret[] = [
            'id'          => $z->id,
            'name'        => $z->name,
            'server'      => $z->server,
            'reverse'     => $z->reverse,
            'hastsigname' => $z->tsigname != null && $z->tsigname != '',
          'hastsigkey'    => $z->tsigkey != null && $z->tsigkey != '',
            'hastsigalgo' => $z->tsigalgo != null && $z->tsigalgo != '',
            ];
        }

        return \Response::json($ret);
    }

    /**
     * list user zones.
     *
     * @return Response
     */
    /*public function index(){
            return $this->getAllZones();
    }*/

    public function get()
    {
        $zone = \Auth::user()->zone(\Input::get('id'));
        $ret = [
            'id'      => $zone->id,
            'name'    => $zone->name,
            'server'  => $zone->server,
            'reverse' => $zone->reverse,
            'records' => $zone->getRecords(),
        ];

        return \Response::json($ret);
    }

    public function postRecord()
    {
        $zone = \Auth::user()->zone(\Input::get('zid'));
        if ($zone != null) {
            //TODO validate input
            //name ttl type rdata
            $validator = \Validator::make(
                \Input::all(),
                [
                    /*'name' => 'required',*/
                    'ttl'       => 'required',
                    'type'      => 'required',
                    'rdata'     => 'required',
                ]
            );
            if ($validator->fails()) {
                // The given data did not pass validation
                abort(400);
            }
            $records = $zone->saveRecord(\Input::all());

            return \Response::json($records);
        } else {
            abort(401);
        }
    }

    public function postSave()
    {
        if (\Auth::user()->role != 'admin') {
            abort(401);
        }
        $data = \Input::all();
        $validator = \Validator::make(
                $data,
                [
                        'name' => 'required',
                ]
        );
        if ($validator->fails()) {
            // The given data did not pass validation
            abort(400);
        }
        if (isset($data['tsigname']) && $data['tsigname'] != '') {
            $data['tsigname'] = \Crypt::encrypt($data['tsigname']);
        }
        if (isset($data['tsigkey']) && $data['tsigkey'] != '') {
            $data['tsigkey'] = \Crypt::encrypt($data['tsigkey']);
        }
        if (isset($data['tsigalgo']) && $data['tsigalgo'] != '') {
            $data['tsigalgo'] = \Crypt::encrypt($data['tsigalgo']);
        }

        if (\Input::has('id')) {
            $zone = \App\Zone::find(\Input::get('id'));
            $zone->update($data);
        } else {
            $zone = \App\Zone::create($data);
        }

        return $this->getAllZones();
    }

    public function postRemove()
    {
        if (\Auth::user()->role != 'admin') {
            abort(401);
        }
        \App\Zone::destroy(\Input::get('id'));

        return $this->getAllZones();
    }

    public function postRemoverecord()
    {
        $zone = \Auth::user()->zone(\Input::get('zid'));
        if ($zone != null) {
            //TODO validate input
            $records = $zone->removeRecord(\Input::all());

            return \Response::json($records);
        } else {
            abort(401);
        }
    }
}
