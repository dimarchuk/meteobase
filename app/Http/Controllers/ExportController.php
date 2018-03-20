<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;

class ExportController extends Controller
{
    /**
     * @return $this|\Symfony\Component\HttpFoundation\BinaryFileResponse|mixed
     */
    public function export()
    {
        ini_set('max_execution_time', 900);
        libxml_use_internal_errors(true);

        $export = new InvoicesExport();
        $export->view();

        if (in_array('Data limit is limited', $export->getData())) {
            return Redirect::back()->withErrors(['Забагато данних, будь ласка, зменшіть Вашу вибірку']);
        }

        $fileName = date('Y-m-d H:m:s');

        return Excel::download(new InvoicesExport(), $fileName . '.xlsx');
    }

}
