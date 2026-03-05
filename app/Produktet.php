<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produktet extends Model
{
    protected $fillable = ['emri','pershkrimi','qmimi','kategoria','extPro'];

    public function presentPrice(){
       
        return sprintf('%01.2f', $this->qmimi);
    }
}
