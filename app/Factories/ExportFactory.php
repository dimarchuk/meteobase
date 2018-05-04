<?php

namespace App\Factories;

class ExportFactory
{
    public static function build($exportName)
    {
        $class = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Exports' . DIRECTORY_SEPARATOR;

        echo $class .= ucfirst($exportName) . 'Export.php';

        if (file_exists($class)) {
            dd(new $class());
        }
    }
}