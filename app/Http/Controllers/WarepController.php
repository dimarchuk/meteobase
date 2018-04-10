<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helpers\{
    Helper, Decode
};
use App\{
    Region, Station, Srok, UserCategory, Warep
};


class WarepController extends Controller
{
    /**
     * WarepController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        define("PER_PAGE", 18);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $helper = new Helper();
        $regions = new Region();
        $stations = new Station();
        $appearances = DB::table('WEATHER2')->select('CODE_WAREP', 'CWCW')->where('CWCW', '!=', '')->get();

        $uId = Auth::getUser()->getAuthIdentifier();

        if (DB::table('user_categories')->where('user_id', $uId)->where('page', 'warep')->exists()) {

            $selectedFilters = UserCategory::all()->where('user_id', '=', $uId)->where('page', 'warep')->first();

            parse_str($selectedFilters->categories_list, $selectedFilters);

            $selectedRegions = isset($selectedFilters['regionName']) ? $selectedFilters['regionName'] : null;
            $selectesStations = isset($selectedFilters['stationName']) ? $selectedFilters['stationName'] : null;

            $currentDate = Date('Y-m-d');

            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

            /**
             * Data filtering
             */
            if (isset($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {


            } else if (isset($selectedFilters['regionName']) && isset($selectedFilters['stationName'])) {


            } else if (empty($selectedFilters['regionName']) && isset($selectedFilters['stationName'])) {


            } else if (empty($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {


            }

//            $countPages = ceil($countStr / PER_PAGE);

//            $paginationLinks = $countPages > 1 ? $helper->generateLinksForPagination(url('/'),
//                $countPages, $currentPage, true) : "";


            /**
             * array with all data for view
             */
            $data = [
                'regions' => $regions->getAllRegions(),
                'selectedRegions' => $selectedRegions,
                'stations' => $stations->getAllStation(),
                'selectedStations' => $selectesStations,
                'appearances' => $appearances
//                'categories' => $categories,
//                'dataFromSrok' => $dataForTable,
//                'selectedCategories' => $selectedFilters['collumns'],
//                'paginationLinks' => $paginationLinks
            ];

        } else {
            //if first auth
            $currentDate = Date('Y-m-d');
            $warep = new Warep([$currentDate, $currentDate]);


            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $dataForTable = $warep->getBasicData($currentPage);

            foreach ($dataForTable as $item) {
                if ($item->STORM_AVIA === 1) {
                    $item->STORM_AVIA = 'STORM';
                } else $item->STORM_AVIA = 'AVIA';
                foreach ($appearances as $appearance) {
                    if ($item->CODPHENOTYP === $appearance->CODE_WAREP)
                    $item->HENOTYP_DECODE = $appearance->CWCW;
                    }
            }
            var_dump($dataForTable);

            /**
             * Create pagination links
             */
            $countPages = ceil($warep->getCountStrBasic() / PER_PAGE);
            $paginationLinks = $helper->generateLinksForPagination(url('/'), $countPages, $currentPage, true);

            /**
             * array with all data for view
             */
            $data = [
                'regions' => $regions->getAllRegions(),
                'stations' => $stations->getAllStation(),
                'appearances' => $appearances,
                'dataForTable' => $dataForTable,
//                'selectedCategories' => $selectedCategories,
//                'paginationLinks' => $paginationLinks
            ];
        }


        return view('site.warep.warep', $data);
    }
}
