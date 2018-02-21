<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Category;

class Kode_knController extends Controller
{
    /**
     * Kode_knController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request)
    {

        $currentDate = date('Y-m-d');


        $regions = DB::table('CAT_OBL')->get();
        $stations = DB::table('CAT_STATION')->select('IND_ST', 'NAME_ST')->get();
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
            ->where('DATE_CH', '=', $currentDate)->get();
//            ->paginate(17);

        return view('/site.kode_kn', array(
            'regions' => $regions,
            'stations' => $stations,
            'categories' => $categories,
            'dataFromSrok' => $srok,
            'selectedCategories' => $selectedCategories
        ));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function ajaxShow(Request $request)
    {
        $currentDate = date('Y-m-d');
        if (isset($_POST['form_data'])) {

            parse_str($_POST['form_data'], $form_data);
            $categories = Category::all()->whereIn('code_col_name', $form_data['collumns']);


            $srok = DB::table('CAT_STATION')
                ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                ->join('srok', 'CAT_STATION.IND_ST', '=', 'srok.IND_ST')
                ->where('DATE_CH', '=', $currentDate)
                ->whereIn('CAT_OBL.OBL_ID', $form_data['regionName'])
                ->whereIn('CAT_STATION.IND_ST', $form_data['stationName'])
                ->orderBy('CAT_STATION.OBL_ID', 'asc')
                ->orderBy('CAT_STATION.IND_ST')->get();
//                ->paginate(17);


            return view('site.table', array(
                'categories' => $categories,
                'dataFromSrok' => $srok,
            ));

        } else if (isset($_POST['regions_id'])) {

            $request_data = json_decode($_POST['regions_id']);

            if (empty($request_data)) {
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
                    ->whereIn('CAT_OBL.OBL_ID', $request_data)
                    ->orderBy('CAT_STATION.OBL_ID', 'asc')
                    ->orderBy('CAT_STATION.IND_ST')
                    ->get();
            }

            $data = [
                'station' => $stations,
            ];

            $response_data = json_encode($data);

            return response($response_data, 200);
        }
    }
}