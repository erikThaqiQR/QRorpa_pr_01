<?php

namespace App\Http\Controllers;

use App\Cupon;
use App\rouletteUsage;
use Illuminate\Http\Request;

class rouletteController extends Controller
{
    public function getCoupons(Request $req){
 

    $ip1 = 'empty';
    $ip2 = 'empty';
    $ip3 = 'empty';
    
    
        $newRUsage = new rouletteUsage();
        $newRUsage->clientIP1 = $ip1;
        $newRUsage->clientIP2 = $ip2;
        $newRUsage->clientIP3 = $ip3;
        $newRUsage->toRes = $req->res;
        $newRUsage->save();

        if(Cupon::where([['toRes',$req->res],['forRoulette','1']])->count() > 0){
            $ans = "";
            
            foreach(Cupon::where([['toRes',$req->res],['forRoulette','1']])->get() as $coupOne){
                if($ans == ""){
                    if($coupOne->valueOff > 0){
                        $ans = number_format($coupOne->valueOff,2,'.','').' %';
                    }else if($coupOne->valueOffMoney > 0){
                        $ans = number_format($coupOne->valueOffMoney,2,'.','').' CHF';
                    }else if($coupOne->prodName != 'empty'){
                        $ans = $coupOne->prodName;
                    }
                }else{
                    if($coupOne->valueOff > 0){
                        $ans .= "|||".number_format($coupOne->valueOff,2,'.','').' %';
                    }else if($coupOne->valueOffMoney > 0){
                        $ans .= "|||".number_format($coupOne->valueOffMoney,2,'.','').' CHF';
                    }else if($coupOne->prodName != 'empty'){
                        $ans .= "|||".$coupOne->prodName;
                    }
                }
            }
            return $ans; 
        }else{
            return 'noGame';
        }
    }

    public function getCouponCode(Request $req){
        if(Cupon::where([['toRes',$req->res],['prodName','=',$req->coupTxt]])->first() != Null){
            $theC = Cupon::where([['toRes',$req->res],['prodName','=',$req->coupTxt]])->first();
        }else if(str_contains($req->coupTxt, 'CHF')){
            $text2D = explode(' ',$req->coupTxt);
            $theC = Cupon::where([['toRes',$req->res],['valueOffMoney', $text2D[0]]])->first();
        }else if(str_contains($req->coupTxt, '%')){
            $text2D = explode(' ',$req->coupTxt);
            $theC = Cupon::where([['toRes',$req->res],['valueOff', $text2D[0]]])->first();
        }

        $newSas = $theC->timesToUse + 1;
        $newWon = $theC->timesWonByCl + 1;
        $theC->timesToUse = $newSas;
        $theC->timesWonByCl = $newWon;
        $theC->save();

        return $theC->codeName.'|||'.$req->coupTxt.'|||'.$theC->id;
    }


    public function wheelSetCookie(Request $req){
        // 30 days
        return redirect()->back()->withCookie(cookie('rouletteCuponId', $req->cId , 43200));
    }
}
