<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersPassive extends Model
{
    protected $connection = 'second_mysql';
}
