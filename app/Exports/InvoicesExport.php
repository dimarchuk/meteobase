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


class InvoicesExport implements FromView
{

    /**
     * @var array
     */
    private $data = [];

    /**
     * @return array
     */
    function getData()
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

            $selectedFilters = UserCategory::all()->where('user_id', '=', $uId)->where('page', '=', 'kodeKN')->first();

            parse_str($selectedFilters->categories_list, $selectedFilters);

            //add default categiry
            $defaultColl = ['NAME_OBL', 'NAME_ST', 'IND_ST', 'DATE_CH', 'SROK_CH'];
            $helper->addItemsinArr($defaultColl, $selectedFilters['collumns']);

            $currentDate = Date('Y-m-d');
            $strok = ($selectedFilters['srok'] == 'All') ? [0, 3, 6, 9, 12, 15, 18, 21] : [(int)$selectedFilters['srok']];

            /**
             * Data filtering
             */
            if (isset($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('CAT_STATION.OBL_ID', $selectedFilters['regionName'])
                    ->whereIn('srok.SROK_CH', $strok)
                    ->whereBetween('DATE_CH', [$selectedFilters['dateFrom'], $selectedFilters['dateTo']])
                    ->get();

            } else if (isset($selectedFilters['regionName']) && isset($selectedFilters['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('CAT_STATION.OBL_ID', $selectedFilters['regionName'])
                    ->whereIn('CAT_STATION.IND_ST', $selectedFilters['stationName'])
                    ->whereIn('srok.SROK_CH', $strok)
                    ->whereBetween('DATE_CH', [$selectedFilters['dateFrom'], $selectedFilters['dateTo']])
                    ->get();

            } else if (empty($selectedFilters['regionName']) && isset($selectedFilters['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('CAT_STATION.IND_ST', $selectedFilters['stationName'])
                    ->whereIn('srok.SROK_CH', $strok)
                    ->whereBetween('DATE_CH', [$selectedFilters['dateFrom'], $selectedFilters['dateTo']])
                    ->get();

            } else if (empty($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {
                $dataForTable = DB::table('CAT_STATION')
                    ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                    ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->whereIn('srok.SROK_CH', $strok)
                    ->whereBetween('DATE_CH', [$selectedFilters['dateFrom'], $selectedFilters['dateTo']])
                    ->get();
            }

            $decode->decodeDirectionWind($dataForTable);
            $decode->decodeBaricTendency($dataForTable);
            $decode->decodeWeatherSrok($dataForTable);
            $decode->decodeWeatherSrok12($dataForTable);
            $decode->decodeClouds($dataForTable);
            $decode->decodeCloudsC($dataForTable);
            $decode->decodeSoilCondition($dataForTable);

            /**
             * array with all data for view
             */
            $this->data = [
                'categories' => $categories,
                'dataFromSrok' => $dataForTable,
                'selectedCategories' => $selectedFilters['collumns']
            ];

        }

        if (count($dataForTable) >= 50000) {
            $this->data = [
                'errors' => 'Data limit is limited'
            ];
        }

        return view('site.KNExport', $this->data);
    }
}