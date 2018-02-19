<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'col_name',
        'code_col_name',
        'selekted_col'
    ];
}
