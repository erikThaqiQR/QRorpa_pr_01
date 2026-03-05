<?php

namespace App\Http\Controllers;

use App\Orders;
use App\tablesAccessToWaiters;
use App\User;
use Carbon\Carbon;
use App\waiterDaySales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class waiterDaySalesController extends Controller
{
    public function waiterSalesTodayWP(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function waiterSalesTodayRegister(Request $req){
        if(Orders::where([['Restaurant',Auth::user()->sFor],['nrTable','500'],['statusi','<','2']])->count() > 0){
            return 'TAunClosedOrder';
        }else{
            $hasOpenOr = false;
            foreach(Orders::where([['Restaurant',Auth::user()->sFor],['statusi','<','2']])->get() as $oOne){
                $tAc = tablesAccessToWaiters::where([['toRes',Auth::user()->sFor],['waiterId',Auth::user()->id],['tableNr',$oOne->nrTable],['statusAct','1']])->first();
                if($tAc != Null){
                    $hasOpenOr = true;
                    break;
                }
            }
            if($hasOpenOr){
                return 'unClosedOrder';
            }else{
                $newSReg = new waiterDaySales();

                $newSReg->forWa = Auth::user()->id;
                $newSReg->forDay = explode(' ',Carbon::now())[0];
                $newSReg->forRes = Auth::user()->sFor;

                $newSReg->c5rp = $req->c5rp;
                $newSReg->c10rp = $req->c10rp;
                $newSReg->c20rp = $req->c20rp;
                $newSReg->c50rp = $req->c50rp;
                $newSReg->c1chf = $req->c1chf;
                $newSReg->c2chf = $req->c2chf;
                $newSReg->c5chf = $req->c5chf;
                $newSReg->c10chf = $req->c10chf;
                $newSReg->c20chf = $req->c20chf;
                $newSReg->c50chf = $req->c50chf;
                $newSReg->c100chf = $req->c100chf;
                $newSReg->c200chf = $req->c200chf;
                $newSReg->c1000chf = $req->c1000chf;

                $newSReg->countCoins = $req->coinCount;
                $newSReg->countBanknotes = $req->banknotesCount;
                $newSReg->totalInChf = $req->totalInChf;

                $newSReg->inCashSalesDirect = $req->cashDirect;
                $newSReg->inCardSalesDirect = $req->cardDirect;
                $newSReg->inOnlineSalesDirect = $req->onlineirect;
                $newSReg->inRechnungSalesDirect = $req->rechnungDirect;

                $newSReg->save();
            }
        }
    }


    public function waiterSalesTodayRegister2(Request $req){
        if(Orders::where([['Restaurant',Auth::user()->sFor],['nrTable','500'],['statusi','<','2']])->count() > 0){
            return 'TAunClosedOrder';
        }else{
            $hasOpenOr = false;
            foreach(Orders::where([['Restaurant',Auth::user()->sFor],['statusi','<','2']])->get() as $oOne){
                $tAc = tablesAccessToWaiters::where([['toRes',Auth::user()->sFor],['waiterId',Auth::user()->id],['tableNr',$oOne->nrTable],['statusAct','1']])->first();
                if($tAc != Null){
                    $hasOpenOr = true;
                    break;
                }
            }
            if($hasOpenOr){
                return 'unClosedOrder';
            }else{
                $newSReg = new waiterDaySales();

                $newSReg->forWa = Auth::user()->id;
                $newSReg->forDay = explode(' ',Carbon::now())[0];
                $newSReg->forRes = Auth::user()->sFor;
    
                $newSReg->c5rp = number_format(0, 2, '.','');
                $newSReg->c10rp = number_format(0, 2, '.','');
                $newSReg->c20rp = number_format(0, 2, '.','');
                $newSReg->c50rp = number_format(0, 2, '.','');
                $newSReg->c1chf = number_format(0, 2, '.','');
                $newSReg->c2chf = number_format(0, 2, '.','');
                $newSReg->c5chf = number_format(0, 2, '.','');
                $newSReg->c10chf = number_format(0, 2, '.','');
                $newSReg->c20chf = number_format(0, 2, '.','');
                $newSReg->c50chf = number_format(0, 2, '.','');
                $newSReg->c100chf = number_format(0, 2, '.','');
                $newSReg->c200chf = number_format(0, 2, '.','');
                $newSReg->c1000chf = number_format(0, 2, '.','');
    
                $newSReg->countCoins = number_format(0, 2, '.','');
                $newSReg->countBanknotes = number_format(0, 2, '.','');
                $newSReg->totalInChf = number_format(0, 2, '.','');
    
                $newSReg->inCashSalesDirect = $req->cashDirect;
                $newSReg->inCardSalesDirect = $req->cardDirect;
                $newSReg->inOnlineSalesDirect = $req->onlineirect;
                $newSReg->inRechnungSalesDirect = $req->rechnungDirect;
    
                $newSReg->save();
            }
            
        }
    }









    function waiterSalesTodayGetData(Request $req){
        $repData = waiterDaySales::where('forWa',Auth::user()->id)->whereDate('forDay',$req->theDt)->first();
        return json_encode( $repData );
    }

    public function waDailySalesPageGetData(Request $req){
        $repData = waiterDaySales::where('forWa',$req->waiterId)->whereDate('forDay',$req->theDt)->first();
        return json_encode( $repData );
    }

    public function waDailySalesPageGetDataOrders(Request $req){

        $orNr = Orders::where('orForWaiter',$req->waiterId2)->whereDate('created_at',$req->theDt2)->get()->count();
        $orChrPre = Orders::where('orForWaiter',$req->waiterId2)->whereDate('created_at',$req->theDt2)->get()->sum('shuma');
        $tipsReg = Orders::where('orForWaiter',$req->waiterId2)->whereDate('created_at',$req->theDt2)->get()->sum('tipPer');
        $orChf =  number_format($orChrPre - $tipsReg, 2, '.', '');

        $repData = waiterDaySales::where('forWa',$req->waiterId2)->whereDate('forDay',$req->theDt2)->first();                   

        $totalVer2 = number_format(0, 2, '.','');
        if($repData->inCashSalesDirect > 0){
            $totalVer2 += number_format($repData->inCashSalesDirect, 2, '.','');
            $totalVer2 += number_format($repData->inCardSalesDirect, 2, '.','');
            $totalVer2 += number_format($repData->inOnlineSalesDirect, 2, '.','');
            $totalVer2 += number_format($repData->inRechnungSalesDirect, 2, '.','');
        }

        return $orNr.'-8-8-'.$orChf.'-8-8-'.$tipsReg.'-8-8-'.$totalVer2;
    }





    public function waitersSalesPrintPDFRep(Request $req){
        // waiterSalesReport
        // 2023-10-06 00:00:00
        $repData = waiterDaySales::where('forWa',$req->waitersId)->whereDate('forDay',$req->dateSelected)->first();
        $theWaiter = User::findOrFail((int)$req->waitersId);
        $date1 = explode(' ',$req->dateSelected);
        $date2D = explode('-',$date1[0]);
        view()->share('repData', $repData);
        view()->share('resID', $theWaiter->sFor);
        view()->share('waiterName', $theWaiter->name);
        view()->share('dateRep', $date2D[2].'.'.$date2D[1].'.'.$date2D[0]);
        $pdf = PDF::loadView('waiterSalesReport')->setPaper('a4', 'portrait');
        return $pdf->download('salesReport_'.$theWaiter->name.'_'.$date2D[2].'.'.$date2D[1].'.'.$date2D[0].'.pdf');
    }
}
