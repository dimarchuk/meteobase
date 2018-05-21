<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


/**
 * Class WarepExport
 * @package App\Exports
 */
class WarepExport implements FromView
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @return array
     */
    public function getData(): array
    {
       return $this->data;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        // TODO: Implement view() method.
    }
}