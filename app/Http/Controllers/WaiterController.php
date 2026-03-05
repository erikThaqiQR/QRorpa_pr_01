<?php

namespace App\Http\Controllers;

use App\User;
use App\Waiter;
use App\Events\CallWaiter;
use Illuminate\Http\Request;
use App\tablesAccessToWaiters;

class WaiterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function callw(Request $req){
       
        // event(new CallWaiter($req->res));

        if($req->waSel == 0){
            foreach(User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->table]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    
                    $nWaiter = new Waiter;

                    $nWaiter->toRes = $req->res;
                    $nWaiter->tableNr = $req->table;
                    $nWaiter->toWaiter = $oneWaiter->id;
                    $nWaiter->comment = $req->comment;
                    $nWaiter->status = 0;
                    $nWaiter->userC = 0;
                    $nWaiter->save();

                    // register the notification ...
                    $details = [
                        'id' => $nWaiter->id,
                        'type' => 'AdminUpdateWaiterCall',
                        'tableNr' => $req->table,
                        'waId' => $oneWaiter->id
                    ];
                    $oneWaiter->notify(new \App\Notifications\waiterCallNotify($details));
                }
            }
        }else{
            $theWaiter = User::find($req->waSel);

            $nWaiter = new Waiter;

            $nWaiter->toRes = $req->res;
            $nWaiter->tableNr = $req->table;
            $nWaiter->toWaiter = $theWaiter->id;
            $nWaiter->comment = $req->comment;
            $nWaiter->status = 0;
            $nWaiter->userC = 0;
            $nWaiter->save();

            $details = [
                'id' => $nWaiter->id,
                'type' => 'AdminUpdateWaiterCall',
                'tableNr' => $req->table,
                'waId' => $req->waSel
            ];
            $theWaiter->notify(new \App\Notifications\waiterCallNotify($details));
        }
    }







    public function chStatus(Request $req){

        $thisCall = Waiter::find($req->id);
        if($thisCall->status == 0){
            $thisCall->status = 1;
            $thisCall->save();
        }else{
            $thisCall->status = 0;
            $thisCall->save();
        }

        foreach(\App\User::where([['sFor',$thisCall->toRes],['role','55']])->get() as $oneWaiter){
            $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $thisCall->tableNr]])->first();
            if($aToTable != NULL && $aToTable->statusAct == 1){

                // register the notification ...
                $details = [
                    'id' => $thisCall->id,
                    'type' => 'AdminUpdateWaiterCall',
                    'tableNr' => $thisCall->tableNr
                ];
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                
            }
        }





        

        public function waiterstatsDeletedTAProdsPage(){
            deletedTAIns.blade.php
        }

    
    }
}
