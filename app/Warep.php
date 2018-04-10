<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Warep extends Model
{
    protected $table = 'warep';
    protected $date;

    /**
     * Srok constructor.
     * @param $date
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    function getCountStrBasic()
    {
        $count = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereBetween('DATE_CH', $this->date)
            ->count();

        return $count;
    }

    /**
     * @param int $page
     * @return \Illuminate\Support\Collection
     */
    public function getBasicData(int $page)
    {
        $warep = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereBetween('DATE_CH', $this->date)
            ->forPage($page, PER_PAGE)
            ->get();

        return $warep;
    }

}
