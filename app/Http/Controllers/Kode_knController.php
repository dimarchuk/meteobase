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
        $regions = DB::table('CAT_OBL')->get();
        $stations = DB::table('CAT_STATION')->select('IND_ST', 'NAME_ST')->get();
        $categories = Category::all();

        //Select user categories
        //переробити збереження налаштувань користувача (зберігати в строку серіалізованого вигляду)
//        $test1 = DB::table('user_categories')
//            ->join('users', 'user_id', '=', 'users.id')
//            ->join('categories', 'categories_id', '=', 'categories.id')
//            ->select('categories.id', 'categories.col_name', 'categories.code_col_name', 'categories.selekted_col')
//            ->where('user_categories.user_id', '2')
//            ->get();
//        var_dump($category);

        return view('/site.kode_kn', array(
            'regions' => $regions,
            'stations' => $stations,
            'categories' => $categories
        ));
    }
}