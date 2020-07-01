<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //History Deleted it's display in culum db
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable=['name', 'code', 'price'];
}
