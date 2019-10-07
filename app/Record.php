<?php

namespace App;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'record';
    protected $primaryKey = 'WeekDay';
    public $timestamps = false;
}
