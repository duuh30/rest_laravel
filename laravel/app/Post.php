<?php


namespace App;

use Prettus\Repository\Eloquent;


class Post extends Eloquent
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user','title','description','date'
    ];


}//end class