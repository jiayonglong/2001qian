<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    //指定表面
    protected $table = 'ecs_cart';
    protected $primaryKey = 'cart_id';
    public $timestamps = false;
}
