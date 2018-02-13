<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class Kode_knController extends Controller
{
    public function show(Request $request)
    {
        $regions = DB::table('CAT_OBL')->get();
        $stations = DB::table('CAT_STATION')->select('IND_ST', 'NAME_ST')->get();
//        foreach ($regions as $key => $region) {
//            echo "{$key} => {$region->NAME_OBL} <br>";
//        }
        $data['regions'] = $regions;
        $data['stations'] = $stations;
//        echo gettype($data['regions']);
//        var_dump($data['regions']);
        //        echo $region_name[2]->NAME_OBL;

//select obl and station from cat_station and from cat_obl
//        $users = DB::table('CAT_OBL')
//            ->join('CAT_STATION', 'CAT_OBL.OBL_ID', '=', 'CAT_STATION.OBL_ID')
//            ->select('CAT_OBL.*', 'CAT_STATION.NAME_ST')
//            ->get();
//        var_dump($users);
        return view('/site.kode_kn', array(
            'regions' => $regions,
            'stations' => $stations
        ));
    }
}