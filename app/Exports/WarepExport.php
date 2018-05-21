<?php

namespace App\Exports;

use DB;
use Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\{
    Category, UserCategory
};
use App\Helpers\{
    Helper, Decode
};

/**
 * Class WarepExport
 * @package App\Exports
 */
class WarepExport implements FromView
{
    public $group = null;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $helper = new Helper();
        $decode = new Decode();
        $categories = Category::all();

        $uId = Auth::getUser()->getAuthIdentifier();

        if (DB::table('user_categories')->where('user_id', $uId)->exists()) {

            $selectedFilters = UserCategory::all()->where('user_id', '=', $uId)->where('page', '=', 'warep')->first();

            parse_str($selectedFilters->categories_list, $selectedFilters);

            $appearances = DB::table('WEATHER2')->select('CODE_WAREP', 'CWCW')->where('CWCW', '!=', '')->get();
            $appear = [];
            foreach ($appearances as $appearance) {
                $appear[] = $appearance->CODE_WAREP;
            }

            $storm = ($selectedFilters['storm'] == 'All') ? [1, 2] : [(int)$selectedFilters['storm']];
            $appearance = ($selectedFilters['appearance'] == 'All') ? $appear : [(int)$selectedFilters['appearance']];


            /**
             * Data filtering
             */
            if (isset($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('CAT_STATION.OBL_ID', $selectedFilters['regionName'])
                    ->whereIn('warep.STORM_AVIA', $storm)
                    ->whereIn('warep.CODPHENOTYP', $appearance)
                    ->whereBetween('DATE_CH', [$selectedFilters['dateFrom'], $selectedFilters['dateTo']])
                    ->get();

            } else if (isset($data['regionName']) && isset($data['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('CAT_STATION.OBL_ID', $selectedFilters['regionName'])
                    ->whereIn('CAT_STATION.IND_ST', $selectedFilters['stationName'])
                    ->whereIn('warep.STORM_AVIA', $storm)
                    ->whereIn('warep.CODPHENOTYP', $appearance)
                    ->whereBetween('DATE_CH', [$selectedFilters['dateFrom'], $selectedFilters['dateTo']])
                    ->get();

            } else if (empty($data['regionName']) && isset($data['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('CAT_STATION.IND_ST', $selectedFilters['stationName'])
                    ->whereIn('warep.STORM_AVIA', $storm)
                    ->whereIn('warep.CODPHENOTYP', $appearance)
                    ->whereBetween('DATE_CH', [$selectedFilters['dateFrom'], $selectedFilters['dateTo']])
                    ->get();

            } else if (empty($data['regionName']) && empty($data['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('warep', 'CAT_STATION.IND_ST', '=', 'warep.INDSTATION')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('warep.STORM_AVIA', $storm)
                    ->whereIn('warep.CODPHENOTYP', $appearance)
                    ->whereBetween('DATE_CH', [$selectedFilters['dateFrom'], $selectedFilters['dateTo']])
                    ->get();
            }

            foreach ($dataForTable as $item) {
                if ($item->STORM_AVIA === 1) {
                    $item->STORM_AVIA = 'STORM';
                } else $item->STORM_AVIA = 'AVIA';
                foreach ($appearances as $appearance) {
                    if ($item->CODPHENOTYP === $appearance->CODE_WAREP)
                        $item->HENOTYP_DECODE = $appearance->CWCW;
                }
                if ($item->CODGROUP != $this->group) {
                    $item->PAR1 = null;
                    $item->PAR2 = null;
                    $item->PAR3 = null;
                }
            }


            $decode->decodeWarepParams($dataForTable);

            $grups = [
                0 => ['Діам./Товщина, мм', 'Т,°С', 'Показн. явища'],
                1 => ['Напрям вітру', 'Сер. шв вітру', 'Макс. шв. вітру'],
                2 => ['Напрямок явища', 'Вид опадів', ''],
                3 => ['Опади, мм', 'Період, год', ''],
                7 => ['МДВ, км', 'Явища', 'Трив. НЯ/СГЯ, год'],
                8 => ['К-сть хмар', 'Форма хмар', 'Вис. нижн. межі, м'],
                9 => ['Діаметр, мм', '', '']
            ];

            /**
             * array with all data for view
             */
            $this->data = [
                'dataForTable' => $dataForTable,
                'headers' => $grups[$this->group]
            ];

        }

        if (count($dataForTable) >= 50000) {
            $this->data = [
                'errors' => 'Data limit is limited'
            ];
        }

        return view('site.warep.WarepExport', $this->data);
    }
}