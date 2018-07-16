<?php

namespace App\Http\Controllers;

use App\Factories\ExportFactory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;

class ExportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return $this|\Symfony\Component\HttpFoundation\BinaryFileResponse|mixed
     */
    public function export(Request $request, $group = null)
    {
        ini_set('max_execution_time', 900);
        libxml_use_internal_errors(true);

        $urlArr = parse_url($request->header('referer'));
        $className = trim($urlArr['path'], " \t\n\r\0\x0B/");

        $export = ExportFactory::build($className);

        if ($group) {
            $export->group = $group;
        }
        $export->view();

        if (in_array('Data limit is limited', $export->getData())) {
            return Redirect::back()->withErrors(['Забагато данних, потрібно зменшити вибірку! ']);
        }

        $fileName = date('Y-m-d H:m:s');

        return Excel::download($export, $fileName . '.xlsx');
    }

}
