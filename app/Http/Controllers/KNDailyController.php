<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KNDailyController extends Controller
{
    /**
     * Kode_knController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        define("PER_PAGE", 18);
    }

    public function show()
    {
        return view('/site.kndaily.kode_kn_daily');
    }

    public function getDataKodeKN()
    {
        var_dump(__METHOD__);
    }
}
