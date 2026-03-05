<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantOffer extends Model
{
    //
    protected $guarded = [];

     public function user()
    {
        //Invoice reference ke table customers
        return $this->belongsTo(User::class);
    }
    const STATUS_COLOR = [
        'Unbezahlt'  => 'red',
        'Bezahlt'   => '#58984c',
    ];
    const USER_STATUS_COLOR = [
        'Unbezahlt'  => 'red',
        'Bezahlt'   => '#58984c',
    ];
}
