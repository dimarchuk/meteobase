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
    function getCountStrBasic(array $storm = [1, 2], array $appearance = [])
    {
        $count = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('warep.STORM_AVIA', $storm)
            ->whereIn('warep.CODPHENOTYP', $appearance)
            ->whereBetween('DATE_CH', $this->date)
            ->count();

        return $count;
    }

    /**
     * @param int $page
     * @return \Illuminate\Support\Collection
     */
    public function getBasicData(int $page, array $storm = [1, 2], array $appearance = [])
    {
        $warep = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('warep.STORM_AVIA', $storm)
            ->whereIn('warep.CODPHENOTYP', $appearance)
            ->whereBetween('DATE_CH', $this->date)
            ->forPage($page, PER_PAGE)
            ->get();

        return $warep;
    }

    /**
     * @param array $regionName
     * @param array $storm
     * @param array $appearance
     * @return int
     */
    function getCountStrRegion(array $regionName, array $storm, array $appearance)
    {
        $count = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.OBL_ID', $regionName)
            ->whereIn('warep.STORM_AVIA', $storm)
            ->whereIn('warep.CODPHENOTYP', $appearance)
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
    public function getRegionData(array $regionName, array $storm, array $appearance, int $page)
    {

        $warep = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.OBL_ID', $regionName)
            ->whereIn('warep.STORM_AVIA', $storm)
            ->whereIn('warep.CODPHENOTYP', $appearance)
            ->whereBetween('DATE_CH', $this->date)->forPage($page, PER_PAGE)
            ->get();

        return $warep;
    }

    /**
     * @param array $stationName
     * @param array $storm
     * @param array $appearance
     * @return int
     */
    function getCountStrStation(array $stationName, array $storm, array $appearance)
    {
        $count = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.IND_ST', $stationName)
            ->whereIn('warep.STORM_AVIA', $storm)
            ->whereIn('warep.CODPHENOTYP', $appearance)
            ->whereBetween('DATE_CH', $this->date)
            ->count();

        return $count;
    }

    /**
     * @param array $stationName
     * @param array $storm
     * @param array $appearance
     * @param int $page
     * @return \Illuminate\Support\Collection
     */
    public function getStationData(array $stationName, array $storm, array $appearance, int $page)
    {

        $warep = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.IND_ST', $stationName)
            ->whereIn('warep.STORM_AVIA', $storm)
            ->whereIn('warep.CODPHENOTYP', $appearance)
            ->whereBetween('DATE_CH', $this->date)->forPage($page, PER_PAGE)
            ->get();

        return $warep;
    }

    /**
     * @param array $regionName
     * @param array $stationName
     * @param array $storm
     * @param array $appearance
     * @return int
     */
    function getCountStrRegionStation(array $regionName, array $stationName, array $storm, array $appearance)
    {
        $count = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.OBL_ID', $regionName)
            ->whereIn('CAT_STATION.IND_ST', $stationName)
            ->whereIn('warep.STORM_AVIA', $storm)
            ->whereIn('warep.CODPHENOTYP', $appearance)
            ->whereBetween('DATE_CH', $this->date)
            ->count();

        return $count;
    }

    /**
     * @param array $regionName
     * @param array $stationName
     * @param array $storm
     * @param array $appearance
     * @param int $page
     * @return \Illuminate\Support\Collection
     */
    public function getRegionStationData(array $regionName, array $stationName, array $storm, array $appearance, int $page)
    {

        $warep = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->whereIn('CAT_STATION.OBL_ID', $regionName)
            ->whereIn('CAT_STATION.IND_ST', $stationName)
            ->whereIn('warep.STORM_AVIA', $storm)
            ->whereIn('warep.CODPHENOTYP', $appearance)
            ->whereBetween('DATE_CH', $this->date)->forPage($page, PER_PAGE)
            ->get();

        return $warep;
    }

    public function __destruct()
    {
        $this->date;
    }

}
