<?php

namespace App\Http\Controllers;

use App\barbershopCupons;
use App\couponUsedPhoneNr;
use App\Cupon;
use App\Events\barbershopNewRez;
use Illuminate\Http\Request;

class CuponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('adminPanel/adminIndex');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $newCup = new Cupon;
        if($request->tipi == '1'){$valPerc = $request->value; }else{ $valPerc = 0; }
        if($request->tipi == '2'){$valMoney = $request->valueMoney; }else{ $valMoney = 0; }
        if($request->tipi == '3'){$valProd = $request->valueProduct; }else{ $valProd = 'empty'; }
        
        $newCup->typeCo =  $request->tipi;
        $newCup->codeName =  $request->code;
        $newCup->valueOff = $valPerc;
        $newCup->valueOffMoney = $valMoney;
        $newCup->prodName = $valProd;
        $newCup->toRes = $request->res;
        $newCup->cartAllow = $request->cartMin;
        $newCup->isActive = 1;
        $newCup->timesToUse = $request->tiToUse;
        $newCup->save();
        return 'done';
    }

    public function storeBar(Request $request){
        if($request->value < 0 || $request->value > 100 ){
            return 'Der Wert ist falsch';
        }else{
            $newCup = new barbershopCupons;
            
            $newCup->codeName =  $request->code;
            $newCup->valueOff =  $request->value;
            $newCup->toBar = $request->bar;
            $newCup->isActive = 1;

            $newCup->save();

            return 'done';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        if($request->cuType == '1'){$valPerc = $request->value; }else{ $valPerc = 0; }
        if($request->cuType == '2'){$valMoney = $request->value; }else{ $valMoney = 0; }
        if($request->cuType == '3'){$valProd = $request->value; }else{ $valProd = 'empty'; }

        $newCup = Cupon::find($request->cuponId);
        $newCup->codeName = $request->code;
        $newCup->valueOff = $valPerc;
        $newCup->valueOffMoney = $valMoney;
        $newCup->prodName = $valProd;
        $newCup->cartAllow = $request->cartMin;
        $newCup->timesToUse = $request->timToUse;
        $newCup->save();
        return 'done';

    }

    public function editCuponBar(Request $request){

        if($request->value < 0 || $request->value > 100 ){
            return 'Der Wert ist falsch';
        }else{

            $newCup = barbershopCupons::find($request->cuponId);
            
            $newCup->codeName =  $request->code;
            $newCup->valueOff =  $request->value;

            $newCup->save();

            return 'done';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req){
        Cupon::find($req->id)->delete();
    }
    public function deleteCuponBar(Request $req){
        barbershopCupons::find($req->id)->delete();
    }

    // Change status of the coupon   Restaurant
    public function chCuponStatus(Request $req){
        $chC = Cupon::find($req->id);
        if($chC->isActive == 1){
            $chC->isActive = 0;
        }else{
            $chC->isActive = 1;
        }
        $chC->save();
    }

    // Change status of the coupon   BARBERSHOP
    public function chCuponStatusBar(Request $req){
        $chC = barbershopCupons::find($req->id);
        if($chC->isActive == 1){
            $chC->isActive = 0;
        }else{
            $chC->isActive = 1;
        }
        $chC->save();
    }

    






    public function checkCupon(Request $req){
        $chC = Cupon::where([['toRes', $req->res],['codeName', '=' ,$req->code]])->first();
        if($chC != null){
            if($chC->isActive == 1){
                if($req->clPN != 'isDelivery' && $req->clPN != '0'){ $cUsed = couponUsedPhoneNr::where([['toRes',$req->res],['phoneNr',$req->clPN],['couponId',$chC->id]])->first();
                }else{ $cUsed = NULL;}
                
                if($cUsed != NULL){
                    // eshte perdor njeher
                    return 'noUsed';
                }else{
                    if($req->cartTot <  $chC->cartAllow){
                        // Nuk e plotson kushtin e totalit
                        return 'noETot';
                    }else{
                        if($chC->timesToUse == 0){
                            return 'noUsesLeft';
                        }else{
                            // nuk eshte perdor  ,  E plotson kushtin e totalit
                            
                            $chC->timesToUse -= 1;
                            $chC->save();
                            return $chC->valueOff.'||'.$chC->valueOffMoney.'||'.$chC->prodName.'||'.$chC->typeCo.'||'.$chC->codeName.'||'.$chC->id;
                        }
                    }
                }
            }else{
                return 'no';
            }
        }else{
            return 'no';
        }
    }














    public function activateCouponForWheel(Request $req){
        $theC = Cupon::find($req->cId);
        if( $theC != Null){
            $theC->forRoulette = 1;
            $theC->save();

            return 'success';
        }
    }

    public function deActivateCouponForWheel(Request $req){
        $theC = Cupon::find($req->cId);
        if( $theC != Null){
            $theC->forRoulette = 0;
            $theC->save();

            return 'success';
        }
    }
    
    
  






    public function checkCuponBar(Request $req){
        $chC = barbershopCupons::where([['toBar', $req->bar],['codeName', '=' ,$req->code]])->first();
        if($chC != null){
            if($chC->isActive == 1){
                return $chC->valueOff;
            }else{
                return 'no';
            }
        }else{
            return 'no';
        }
    }
}
