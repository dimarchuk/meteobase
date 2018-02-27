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
        $srok = new Srok();

        $selectedCategories = [];
        foreach ($categories as $category) {
            if ($category->selekted_col == true) {
                $selectedCategories[] = $category->code_col_name;
            }
        }

        if (isset($_GET['page'])) {
            $currentPage = $_GET['page'];
        } else {
            $currentPage = 1;
        }
        /**
         * Get data for table on one page
         */
        $dataFromTableSrok = $srok->getBasicDataFromSrok();
        $srokOnePage = $dataFromTableSrok->forPage($currentPage, PER_PAGE);

        /**
         * Create pagination links
         */
        $countPages = ceil($dataFromTableSrok->count() / PER_PAGE);
        $paginationLinks = $helper->generateLinksForPagination(url('/'), $countPages, $currentPage, true);

        /**
         * array with all data for view
         */
        $data = [
            'regions' => $regions->getRegions(),
            'stations' => $stations->getAllStation(),
            'categories' => $categories,
            'dataFromSrok' => $srokOnePage,
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
        $srok = new Srok();

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
                    $categories = Category::all()->whereIn('code_col_name', $data['collumns']);

                    $dataFromTableSrok = $srok->getBasicDataFromSrok();
                    $dataFromTableSrokk = $dataFromTableSrok->whereIn('OBL_ID', $data['regionName']);

                    if (isset($data['page'])) {
                        $currentPage = $data['page'];
                    } else {
                        $currentPage = 1;
                    }

                    $srokOnePage = $dataFromTableSrokk->forPage($currentPage, PER_PAGE);
                    $countPages = ceil($dataFromTableSrokk->count() / PER_PAGE);
                    $paginationLinks = $helper->generateLinksForPagination(url('/'), $countPages, $currentPage, true);

                    $data = [
                        'categories' => $categories,
                        'dataFromSrok' => $srokOnePage,
                        'countPages' => $countPages,
                        'currentPage' => $currentPage,
                        'paginationLinks' => $paginationLinks,
                    ];

                    return view('site.table', $data);
                    break;
                }
        }
    }
}