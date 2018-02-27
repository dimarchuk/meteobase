<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Srok extends Model
{
    protected $table = 'srok';
    protected $currentDate;

    public function __construct()
    {
        $this->currentDate = date('Y-m-d');
    }

    public function getBasicDataFromSrok()
    {
        $srok = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->where('DATE_CH', '=', $this->currentDate)->get();//var $currentDate
//            ->paginate(17);

        return $srok;
    }

    public function __destruct()
    {
        $this->currentDate;
        $this->table;
    }
}
