<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $table = 'login';
    protected $primaryKey = 'Id';
    public $timestamps = false;
}
