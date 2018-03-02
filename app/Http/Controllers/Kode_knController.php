<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\{
    Category,
    Region,
    Station,
    Srok
};
use Symfony\Component\VarDumper\Caster\DateCaster;

use DB;

class Kode_knController extends Controller
{
    /**
     * Kode_knController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        define("PER_PAGE", 17);
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


        /**
         * array with all data for view
         */
        $data = [
            'regions' => $regions->getRegions(),
            'stations' => $stations->getAllStation(),
            'categories' => $categories,
            'dataFromSrok' => $dataFromTableSrok,
            'selectedCategories' => $selectedCategories,
            'paginationLinks' => $paginationLinks
        ];

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

                    if (isset($data['regionName']) && empty($data['stationName'])) {

                        $countStr = $srok->getCountStrRegion($data['regionName']);
                        $dataForTable = $srok->getRegionData($data['regionName'], $currentPage);

                    } else if (isset($data['regionName']) && isset($data['stationName'])) {

                        $countStr = $srok->getCountStrRegionStation($data['regionName'], $data['stationName']);
                        $dataForTable = $srok->getRegionStationData($data['regionName'], $data['stationName'], $currentPage);

                    } else if (empty($data['regionName']) && isset($data['stationName'])) {

                        $countStr = $srok->getCountStrStation($data['stationName']);
                        $dataForTable = $srok->getStationData($data['stationName'], $currentPage);

                    } else if (empty($data['regionName']) && empty($data['stationName'])) {

                        $countStr = $srok->getCountStrBasic();
                        $dataForTable = $srok->getBasicData($currentPage);
                    }

                    $countPages = ceil($countStr / PER_PAGE);

                    $paginationLinks = $countPages > 1 ? $helper->generateLinksForPagination(url('/'), $countPages, $currentPage, true) : "";

                    $dataOut = [
                        'categories' => $categories,
                        'dataFromSrok' => $dataForTable,
                        'countPages' => $countPages,
                        'currentPage' => $currentPage,
                        'paginationLinks' => $paginationLinks,
                    ];

                    return view('site.table', $dataOut);
                    break;
                }
        }
    }
}