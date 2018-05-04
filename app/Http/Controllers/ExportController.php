<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
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

       ExportFactory::build('warep');

dd(parse_url($request->header('referer')), isset($group));
        $export = new InvoicesExport();
        $export->view();

        if (in_array('Data limit is limited', $export->getData())) {
            return Redirect::back()->withErrors(['Забагато данних, потрібно зменшити вибірку! ']);
        }

        $fileName = date('Y-m-d H:m:s');

        return Excel::download(new InvoicesExport(), $fileName . '.xlsx');
    }

}
