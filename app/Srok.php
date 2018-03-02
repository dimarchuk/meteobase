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


    /**
     * Select count pages when request method get
     * Current date filtering
     * @return int
     */
    function getCountStrBasic()
    {
        $count = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereBetween('DATE_CH', $this->date)
            ->count();

        return $count;
    }

    /**
     * Get data for one page
     * Current date filtering
     * @param $page
     * @return \Illuminate\Support\Collection
     */
    public function getBasicData(int $page)
    {
        $srok = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereBetween('DATE_CH', $this->date)->forPage($page, 17)
            ->get();

        return $srok;
    }

    /**
     * Select count pages when request method post
     * Date & Region id filtering
     * @param array $regionName
     * @return int
     */
    function getCountStrRegion(array $regionName)
    {
        $count = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.OBL_ID', $regionName)
            ->whereBetween('DATE_CH', $this->date)
            ->count();

        return $count;
    }

    /**
     * Get data for one page
     * Date & Region id filtering
     * @param array $regionName
     * @param int $page
     * @return \Illuminate\Support\Collection
     */
    public function getRegionData(array $regionName, int $page)
    {

        $srok = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.OBL_ID', $regionName)
            ->whereBetween('DATE_CH', $this->date)->forPage($page, 17)
            ->get();

        return $srok;
    }

    /**
     * @param array $regionName
     * @return int
     */
    function getCountStrStation(array $stationName)
    {
        $count = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.IND_ST', $stationName)
            ->whereBetween('DATE_CH', $this->date)
            ->count();

        return $count;
    }

    /**
     * @param array $regionName
     * @param int $page
     * @return \Illuminate\Support\Collection
     */
    public function getStationData(array $stationName, int $page)
    {

        $srok = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.IND_ST', $stationName)
            ->whereBetween('DATE_CH', $this->date)->forPage($page, 17)
            ->get();

        return $srok;
    }


    function getCountStrRegionStation(array $regionName, array $stationName)
    {
        $count = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.OBL_ID', $regionName)
            ->whereIn('CAT_STATION.IND_ST', $stationName)
            ->whereBetween('DATE_CH', $this->date)
            ->count();

        return $count;
    }

    public function getRegionStationData(array $regionName, array $stationName, int $page)
    {

        $srok = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.OBL_ID', $regionName)
            ->whereIn('CAT_STATION.IND_ST', $stationName)
            ->whereBetween('DATE_CH', $this->date)->forPage($page, 17)
            ->get();

        return $srok;
    }

    public function __destruct()
    {
        $this->date;
        $this->table;
    }
}
