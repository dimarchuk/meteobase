<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\{
    Category, Region, Station, Srok, UserCategory, User
};
use Symfony\Component\VarDumper\Caster\DateCaster;
use DB;
use Auth;

class Kode_knController extends Controller
{
    /**
     * Kode_knController constructor.
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
        $categories = Category::all();

        $uId = Auth::getUser()->getAuthIdentifier();

        if (DB::table('user_categories')->where('user_id', $uId)->exists()) {

            $selectedFilters = UserCategory::all()->where('user_id', '=', $uId)->first();

            parse_str($selectedFilters->categories_list, $selectedFilters);

            $selectedRegions = isset($selectedFilters['regionName']) ? $selectedFilters['regionName'] : null;
            $selectesStations = isset($selectedFilters['stationName']) ? $selectedFilters['stationName'] : null;

            $currentDate = Date('Y-m-d');
            $srok = new Srok([$currentDate, $currentDate]);
            $strok = ($selectedFilters['srok'] == 'All') ? [0, 3, 6, 9, 12, 15, 18, 21] : [(int)$selectedFilters['srok']];

            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

            /**
             * Data filtering
             */
            if (isset($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {

                $countStr = $srok->getCountStrRegion($selectedFilters['regionName'], $strok);
                $dataForTable = $srok->getRegionData($selectedFilters['regionName'], $strok, $currentPage);

            } else if (isset($selectedFilters['regionName']) && isset($selectedFilters['stationName'])) {

                $countStr = $srok->getCountStrRegionStation($selectedFilters['regionName'], $selectedFilters['stationName'], $strok);
                $dataForTable = $srok->getRegionStationData($selectedFilters['regionName'], $selectedFilters['stationName'], $strok, $currentPage);

            } else if (empty($selectedFilters['regionName']) && isset($selectedFilters['stationName'])) {

                $countStr = $srok->getCountStrStation($selectedFilters['stationName'], $strok);
                $dataForTable = $srok->getStationData($selectedFilters['stationName'], $strok, $currentPage);

            } else if (empty($selectedFilters['regionName']) && empty($selectedFilters['stationName'])) {

                $countStr = $srok->getCountStrBasic($strok);
                $dataForTable = $srok->getBasicData($currentPage, $strok);
            }

            $countPages = ceil($countStr / PER_PAGE);

            $paginationLinks = $countPages > 1 ? $helper->generateLinksForPagination(url('/'),
                $countPages, $currentPage, true) : "";

            $helper->decodeDirectionWind($dataForTable);
            $helper->decodeBaricTendency($dataForTable);
            $helper->decodeWeatherSrok($dataForTable);
            $helper->decodeWeatherSrok12($dataForTable);
            /**
             * array with all data for view
             */
            $data = [
                'regions' => $regions->getAllRegions(),
                'selectedRegions' => $selectedRegions,
                'stations' => $stations->getAllStation(),
                'selectedStations' => $selectesStations,
                'categories' => $categories,
                'dataFromSrok' => $dataForTable,
                'selectedCategories' => $selectedFilters['collumns'],
                'paginationLinks' => $paginationLinks
            ];

        } else {
            //if first auth

            $currentDate = Date('Y-m-d');
            $srok = new Srok([$currentDate, $currentDate]);

            $selectedCategories = [];
            foreach ($categories as $category) {
                if ($category->selekted_col == true) {
                    $selectedCategories[] = $category->code_col_name;
                }
            }

            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

            /**
             * Get data for table on one page
             */
            $dataFromTableSrok = $srok->getBasicData($currentPage);

            /**
             * Create pagination links
             */
            $countPages = ceil($srok->getCountStrBasic() / PER_PAGE);
            $paginationLinks = $helper->generateLinksForPagination(url('/'), $countPages, $currentPage, true);

            $helper->decodeDirectionWind($dataFromTableSrok);
            $helper->decodeBaricTendency($dataFromTableSrok);
            $helper->decodeWeatherSrok($dataFromTableSrok);
            $helper->decodeWeatherSrok12($dataFromTableSrok);

            /**
             * array with all data for view
             */
            $data = [
                'regions' => $regions->getAllRegions(),
                'stations' => $stations->getAllStation(),
                'categories' => $categories,
                'dataFromSrok' => $dataFromTableSrok,
                'selectedCategories' => $selectedCategories,
                'paginationLinks' => $paginationLinks
            ];
        }

        return view('/site.kode_kn', $data);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getDataKodeKN(Request $request)
    {
        $helper = new Helper();
        $stations = new Station();

        parse_str($_POST['data'], $data);

        $ajaxIdentification = $data['requestName'];


        switch ($ajaxIdentification) {
            case "selectStation":
                {
                    if (empty($data['regionName'])) {
                        $stations = $stations->getAllStation();

                    } else {
                        $stations->regionName = $data['regionName'];
                        $stations = $stations->filterStation();
                    }

                    $data = [
                        'station' => $stations
                    ];

                    $response_data = json_encode($data);
                    return response($response_data, 200);
                    break;
                }

            case "selectInfoForTable":
                {
                    $currentPage = isset($data['page']) ? $data['page'] : 1;
                    $srok = new Srok([$data['dateFrom'], $data['dateTo']]);
                    $categories = Category::all()->whereIn('code_col_name', $data['collumns']);

                    $strok = ($data['srok'] == 'All') ? [0, 3, 6, 9, 12, 15, 18, 21] : [(int)$data['srok']];

                    /**
                     * Save selected filters
                     */
                    $uId = Auth::getUser()->getAuthIdentifier();
                    if (DB::table('user_categories')->where('user_id', $uId)->exists()) {
                        DB::table('user_categories')->where('user_id', $uId)->update(
                            ['categories_list' => $_POST['data']]
                        );
                    } else {
                        DB::table('user_categories')->where('user_id', $uId)->insert(
                            ['user_id' => $uId, 'categories_list' => $_POST['data']]
                        );
                    }

                    /**
                     * Data filtering
                     */
                    if (isset($data['regionName']) && empty($data['stationName'])) {
                        $countStr = $srok->getCountStrRegion($data['regionName'], $strok);
                        $dataForTable = $srok->getRegionData($data['regionName'], $strok, $currentPage);

                    } else if (isset($data['regionName']) && isset($data['stationName'])) {
                        $countStr = $srok->getCountStrRegionStation($data['regionName'], $data['stationName'], $strok);
                        $dataForTable = $srok->getRegionStationData($data['regionName'], $data['stationName'], $strok, $currentPage);

                    } else if (empty($data['regionName']) && isset($data['stationName'])) {
                        $countStr = $srok->getCountStrStation($data['stationName'], $strok);
                        $dataForTable = $srok->getStationData($data['stationName'], $strok, $currentPage);

                    } else if (empty($data['regionName']) && empty($data['stationName'])) {
                        $countStr = $srok->getCountStrBasic($strok);
                        $dataForTable = $srok->getBasicData($currentPage, $strok);
                    }

                    $countPages = ceil($countStr / PER_PAGE);

                    $paginationLinks = $countPages > 1 ? $helper->generateLinksForPagination(url('/'), $countPages, $currentPage, true) : "";

                    $helper->decodeDirectionWind($dataForTable);
                    $helper->decodeBaricTendency($dataForTable);
                    $helper->decodeWeatherSrok($dataForTable);
                    $helper->decodeWeatherSrok12($dataForTable);


                    $dataOut = [
                        'categories' => $categories,
                        'dataFromSrok' => $dataForTable,
                        'countPages' => $countPages,
                        'currentPage' => $currentPage,
                        'paginationLinks' => $paginationLinks,
                        'strok' => $strok
                    ];

                    return view('site.table', $dataOut);
                    break;
                }
        }
    }
}