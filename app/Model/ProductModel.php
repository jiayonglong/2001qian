<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
     //指定表面
    protected $table = 'ecs_products';
    protected $primaryKey = 'product_id';
    public $timestamps = false;
}
