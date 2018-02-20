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
            ->where('DATE_CH', '=', $currentDate)
            ->orderBy('CAT_STATION.OBL_ID', 'asc')
            ->orderBy('CAT_STATION.IND_ST')
            ->paginate(17);

        return view('/site.kode_kn', array(
            'regions' => $regions,
            'stations' => $stations,
            'categories' => $categories,
            'dataFromSrok' => $srok,
            'selectedCategories' => $selectedCategories
        ));
    }

    public function ajaxShow(Request $request)
    {

        if (isset($_POST['form_data'])) {
            $req = false; // изначально переменная для "ответа" - false
            parse_str($_POST['form_data'], $form_data); // разбираем строку запроса
            // Приведём полученную информацию в удобочитаемый вид

            ob_start();
            echo 'До обработки: ' . $_POST['form_data'];
            echo 'После обработки:';
            echo '<pre>';
            print_r($form_data);
            echo '</pre>';
            $req = ob_get_contents();
            ob_end_clean();
            return response(json_encode($req), 200); // вернем полученное в ответе

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
            $response_data = json_encode($stations);

            return response($response_data, 200);

        } else {
            $stations = DB::table('CAT_STATION')
                ->join('CAT_OBL', 'CAT_STATION.OBL_ID', '=', 'CAT_OBL.OBL_ID')
                ->select('CAT_STATION.IND_ST', 'CAT_STATION.NAME_ST')
                ->get();

            $c = json_encode($stations);

            return response($c, 200);
        }
    }
}