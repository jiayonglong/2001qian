<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SeckillModel extends Model
{
      //指定表面
    protected $table = 'seckill';
    protected $primaryKey = 'seckill_id';
    public $timestamps = false;
}
