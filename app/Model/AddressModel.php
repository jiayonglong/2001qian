<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AddressModel extends Model
{
     //指定表面
    protected $table = 'ecs_user_address';
    protected $primaryKey = 'address_id';
    public $timestamps = false;
}
