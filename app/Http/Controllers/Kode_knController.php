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
            ->paginate(16);


        return view('/site.kode_kn', array(
            'regions' => $regions,
            'stations' => $stations,
            'categories' => $categories,
            'dataFromSrok' => $srok,
            'selectedCategories' => $selectedCategories
        ));
    }
}