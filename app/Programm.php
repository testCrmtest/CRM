<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Programm extends Model
{
    protected $fillable = [
    	'name',
    	'branch_id',
    ];
}
