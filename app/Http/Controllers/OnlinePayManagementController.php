<?php

namespace App\Http\Controllers;

use App\Orders;
use Illuminate\Http\Request;

class OnlinePayManagementController extends Controller
{
    public function OnlinePayIndex(){
        return view('sa/superAdminIndex');
    }

    public function TransferDone(Request $req){
        // orId
        $or = Orders::find($req->orId);
        $or->paidOnlineTr = 1;
        $or->save();
    }

    public function TransferDoneAllSel(Request $req){
        // ordersSelected
        foreach(explode('---|---',$req->ordersSelected) as $oneOrderId){
            $or = Orders::find($oneOrderId);
            $or->paidOnlineTr = 1;
            $or->save();
        }
    }
}
