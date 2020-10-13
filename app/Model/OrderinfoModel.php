<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderinfoModel extends Model
{
    //指定表面
    protected $table = 'order_info';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

}
