<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //指定表面
    protected $table = 'ecs_category';
    protected $primaryKey = 'cat_id';
    public $timestamps = false;
}
