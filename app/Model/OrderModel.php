<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
     //指定表面
    protected $table = 'ecs_region';
    protected $primaryKey = 'region_id';
    public $timestamps = false;
}
