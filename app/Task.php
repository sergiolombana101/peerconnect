<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = "task";
    protected $primaryKey = 'Id';
    public $timestamps = false;
}
