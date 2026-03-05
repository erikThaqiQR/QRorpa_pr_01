<?php

namespace App\Http\Controllers;

use App\payTecErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class payTecController extends Controller{
   
    public function index()
    {
        //
    }

    public function collectErrorLog(Request $req){
        $errLog = new payTecErrorLog();
        $errLog->toRes = Auth::user()->sFor;
        $errLog->fromStaff = Auth::user()->id;
        $errLog->errorMsg = $req->payTecTrx;
        $errLog->save();
    }

    
}
