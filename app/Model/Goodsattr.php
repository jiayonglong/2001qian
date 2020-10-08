<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goodsattr extends Model
{
     //指定表面
    protected $table = 'ecs_goods_attr';
    protected $primaryKey = 'goods_attr_id';
    public $timestamps = false;
}
