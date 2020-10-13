<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrdergoodsModel extends Model
{
    //指定表面
    protected $table = 'order_goods';
    protected $primaryKey = 'order_shop_id';
    public $timestamps = false;

}
