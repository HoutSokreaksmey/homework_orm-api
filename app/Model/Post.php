<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    //History Deleted it's display in culum db
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    // Mass Assignment = assign via model There are 'fillable' and 'Guarded' 
    protected $fillable = ['title','content'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function photos(){
        return $this->morphMany('App\Photo','imageable');
    }

    public function tags(){
        return $this->morphToMany('App\Tag', 'taggable');
    }
}
