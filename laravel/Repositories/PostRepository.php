<?php

namespace App;

use Prettus\Repository\Eloquent\BaseRepository;


class PostRepository extends BaseRepository
{


    function model()
    {
        return "App\\Post";
    }//end model function

}//end class