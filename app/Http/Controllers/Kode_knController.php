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
        $dataFromTableSrok = $srok->getBasicDataFromSrok($currentPage);
//        $srokOnePage = $dataFromTableSrok->forPage($currentPage, PER_PAGE);
        /**
         * Create pagination links
         */
//        $countPages = $srok->getCountStr();
        $countPages = ceil($srok->getCountStr() / PER_PAGE);
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
                    $srok = new Srok([$data['dateFrom'], $data['dateTo']]);
                    $categories = Category::all()->whereIn('code_col_name', $data['collumns']);
                    $dataFromTableSrok = $srok->getBasicDataFromSrok();

                    if (isset($data['regionName']) && empty($data['stationName'])) {

                        $dataFromTableSrokk = $dataFromTableSrok
                            ->whereIn('OBL_ID', $data['regionName']);

                    } else if (isset($data['regionName']) && isset($data['stationName'])) {

                        $dataFromTableSrokk = $dataFromTableSrok
                            ->whereIn('OBL_ID', $data['regionName'])
                            ->whereIn('IND_ST', $data['stationName']);

                    } else if (empty($data['regionName']) && isset($data['stationName'])) {

                        $dataFromTableSrokk = $dataFromTableSrok
                            ->whereIn('IND_ST', $data['stationName']);

                    } else if (empty($data['regionName']) && empty($data['stationName'])) {

                        $dataFromTableSrokk = $dataFromTableSrok;

                    }

                    $currentPage = isset($data['page']) ? $data['page'] : 1;

                    $srokOnePage = $dataFromTableSrokk->forPage($currentPage, PER_PAGE);
                    $countPages = ceil($dataFromTableSrokk->count() / PER_PAGE);

                    $paginationLinks = $countPages >= 1 ? $helper->generateLinksForPagination(url('/'), $countPages, $currentPage, true) : "";

                    $dataOut = [
                        'categories' => $categories,
                        'dataFromSrok' => $srokOnePage,
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