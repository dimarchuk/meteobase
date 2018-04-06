<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helpers\{
    Helper, Decode
};
use App\{
    Region, Station, Srok, UserCategory
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
        $regions = new Region();
        $stations = new Station();

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
//                'categories' => $categories,
//                'dataFromSrok' => $dataForTable,
//                'selectedCategories' => $selectedFilters['collumns'],
//                'paginationLinks' => $paginationLinks
            ];

        } else {
            //if first auth
            $currentDate = Date('Y-m-d');

            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;


            /**
             * Create pagination links
             */
//            $countPages = ceil($srok->getCountStrBasic() / PER_PAGE);
//            $paginationLinks = $helper->generateLinksForPagination(url('/'), $countPages, $currentPage, true);
//

            /**
             * array with all data for view
             */
            $data = [
                'regions' => $regions->getAllRegions(),
                'stations' => $stations->getAllStation(),
//                'categories' => $categories,
//                'dataFromSrok' => $dataForTable,
//                'selectedCategories' => $selectedCategories,
//                'paginationLinks' => $paginationLinks
            ];
        }


        return view('site.warep.warep', $data);
    }
}
