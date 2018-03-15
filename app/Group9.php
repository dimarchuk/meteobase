<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Group9 extends Model
{
    protected $table = 'group9';

    public function selectGroup9Info($filter)
    {
        $dataToSelectedStation = DB::table($this->table)
            ->where('IND_ST', $filter['id'])
            ->where('DATE_CH', $filter['date'])
            ->where('SROK_CH', $filter['srok'])
            ->get();
        return $dataToSelectedStation;
    }
}
