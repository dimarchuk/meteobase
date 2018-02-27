<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Helpers\{
    Helper
};
use App\{
    Category,
    Region,
    Station
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
        $currentDate = date('Y-m-d');

        $regions = new Region();
        $stations = new Station();
        $categories = Category::all();

        $selectedCategories = [];
        foreach ($categories as $category) {
            if ($category->selekted_col == true) {
                $selectedCategories[] = $category->code_col_name;
            }
        }

        $srok = DB::table('CAT_STATION')
            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
            ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->where('DATE_CH', '=', '2018-02-25')//var $currentDate
            ->paginate(17);

        return view('/site.kode_kn', array(
            'regions' => $regions->getRegions(),
            'stations' => $stations->getStation(),
            'categories' => $categories,
            'dataFromSrok' => $srok,
            'selectedCategories' => $selectedCategories
        ));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getDataKodeKN(Request $request)
    {
        $helper = new Helper();

        $currentDate = date('Y-m-d');
        parse_str($_POST['data'], $data);
        $ajaxIdentification = $data['requestName'];

        switch ($ajaxIdentification) {
            case "selectStation":
                {

                    if (empty($data['regionName'])) {

                        $stations = DB::table('CAT_STATION')
                            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                            ->select('CAT_STATION.IND_ST', 'CAT_STATION.NAME_ST')
                            ->orderBy('CAT_STATION.OBL_ID', 'asc')
                            ->orderBy('CAT_STATION.IND_ST')
                            ->get();

                    } else {

                        $stations = DB::table('CAT_STATION')
                            ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                            ->select('CAT_STATION.IND_ST', 'CAT_STATION.NAME_ST')
                            ->whereIn('CAT_OBL.OBL_ID', $data['regionName'])
                            ->orderBy('CAT_STATION.OBL_ID', 'asc')
                            ->orderBy('CAT_STATION.IND_ST')
                            ->get();
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

                    $dataFromTableSrok = DB::table('CAT_STATION')
                        ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                        ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                        ->where('DATE_CH', '=', $currentDate)
                        ->whereIn('CAT_OBL.OBL_ID', $data['regionName'])
//                    ->whereIn('CAT_STATION.IND_ST', $data['stationName'])
                        ->orderBy('CAT_STATION.OBL_ID', 'asc')
                        ->orderBy('CAT_STATION.IND_ST')
                        ->get();

                    if (isset($data['page'])) {
                        $currentPage = $data['page'];
                    } else {
                        $currentPage = 1;
                    }

                    $srok = $dataFromTableSrok->forPage($currentPage, PER_PAGE);

                    $countPages = ceil($dataFromTableSrok->count() / PER_PAGE);

                    $paginationLinks = $helper->generateLinksForPagination(url('/'), $countPages, $currentPage, true);

                    $data = [
                        'categories' => $categories,
                        'dataFromSrok' => $srok,
                        'countPages' => $countPages,
                        'currentPage' => $currentPage,
                        'paginationLinks' => $paginationLinks
                    ];

                    return view('site.table', $data);
                    break;
                }
        }
    }
}