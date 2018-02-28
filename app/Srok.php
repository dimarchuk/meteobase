<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Srok extends Model
{
    protected $table = 'srok';
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }
     function getCountStr() {
         $count = DB::table('CAT_STATION')
             ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
             ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
             ->orderBy('CAT_STATION.OBL_ID', 'asc')
             ->orderBy('CAT_STATION.IND_ST')
             ->whereBetween('DATE_CH', $this->date)
             ->count();

         return $count;
     }

    public function getBasicDataFromSrok($page)
    {
        $srok = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereBetween('DATE_CH', $this->date)->forPage($page,17)
            ->get();

        return $srok;
    }

    public function __destruct()
    {
        $this->currentDate;
        $this->table;
    }
}
