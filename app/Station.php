<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Station extends Model
{
    protected $table = 'CAT_STATION';

    public function getStation()
    {
        return DB::table('CAT_STATION')->select('IND_ST', 'NAME_ST')->get();
    }
}
