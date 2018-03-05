<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Region extends Model
{
    protected $table = 'CAT_OBL';

    public function getAllRegions()
    {
        return DB::table($this->table)->get();
    }
}
