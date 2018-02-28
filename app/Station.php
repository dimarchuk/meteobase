<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Station extends Model
{

    protected $table = 'CAT_STATION';
    public $regionName = [];

    public function __construct()
    {

    }

    public function getAllStation()
    {
        return DB::table('CAT_STATION')
            ->select('IND_ST', 'NAME_ST')
            ->orderBy('OBL_ID')
            ->orderBy('IND_ST')
            ->get();
    }

    public function filterStation()
    {
        $stations = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->select('CAT_STATION.IND_ST', 'CAT_STATION.NAME_ST')
            ->whereIn('CAT_OBL.OBL_ID', $this->regionName)
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->get();

        return $stations;
    }
}
