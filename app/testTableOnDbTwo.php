<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class testTableOnDbTwo extends Model
{
    protected $connection = 'second_db';
}
