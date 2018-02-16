<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class Kode_knController extends Controller
{
    /**
     * Kode_knController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $regions = DB::table('CAT_OBL')->get();
        $stations = DB::table('CAT_STATION')->select('IND_ST', 'NAME_ST')->get();
        $data['regions'] = $regions;
        $data['stations'] = $stations;
        return view('/site.kode_kn', array(
            'regions' => $regions,
            'stations' => $stations
        ));
    }
}