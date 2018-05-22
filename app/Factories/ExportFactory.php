<?php

namespace App\Factories;

/**
 * Class ExportFactory
 * @package App\Factories
 */
class ExportFactory
{
    /**
     * @param $exportName
     * @return mixed
     */
    public static function build($exportName)
    {
        if ($exportName == null) $exportName = 'invoices';

        $class = 'App\Exports\\' . ucfirst($exportName) . 'Export';

        if (class_exists($class)) {
            return new $class();
        }
    }
}