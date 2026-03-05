<?php

namespace App\Http\Controllers;

use App\TabOrder;
use App\TableQrcode;
use App\notifyClient;

use App\TableChngReq;
use App\ghostCartInUse;
use App\Events\clNewTab;
use Illuminate\Http\Request;
use App\tabVerificationPNumbers;

class TableChangeCLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function indexAP(){
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
    public function store(Request $req){
        $this->validate($req, [
            'oldTable' => 'required',
            'newTable' => 'required'
        ]);
        $newTC =new TableChngReq;

        $newTC->toRes = $req->res;
        $newTC->crrTable = $req->oldTable;
        $newTC->newTable = $req->newTable;

        $newTableRecord = TableQrcode::where([['tableNr',$req->newTable],['Restaurant',$req->res]])->first();
        if($newTableRecord->kaTab != 0){
            $newTC->toTableActive = 1;
        }else{
            $newTC->toTableActive = 0;
        }
        $newTC->clPhoneNr = $req->clPH;
        if($req->comm != ''){
            $newTC->komenti = $req->comm;
        }else{
            $newTC->komenti = 'empty';
        }
        $newTC->status = 0;

        $newTC->save();

        // $text = $req->res.'||admin';
        // event(new clNewTab($text));

        // Send Notifications for the Admin
        foreach(\App\User::where([['sFor',$req->res],['role','5']])->get() as $user){
            $details = [
                'id' => $newTC->id, 
                'type' => 'AdminUpdateTableCh',
                'tableNr' => $req->oldTable
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));

            $details = [
                'id' => $newTC->id, 
                'type' => 'AdminUpdateTableCh',
                'tableNr' => $req->newTable
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
            // register the notification ...
            $details = [
                'id' => $newTC->id, 
                'type' => 'AdminUpdateTableCh',
                'tableNr' => $req->oldTable
            ];
            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));

            $details = [
                'id' => $newTC->id, 
                'type' => 'AdminUpdateTableCh',
                'tableNr' => $req->newTable
            ];
            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
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











    public function stat01(Request $req){
        $TCR =TableChngReq::findOrFail($req->id);
        $TCR->status = $req->val;
        $TCR->save();

        // decline
        if($req->val == 1){
            // $text = $TCR->toRes.'||'.$TCR->crrTable.'||userError||Entschuldigung, wir können Sie nicht zur Tabelle Nummer '.$TCR->newTable.' verschieben';
            // event(new clNewTab($text));
            // Register a new notifyClient
            $newNotifyClient = new notifyClient();
            $newNotifyClient->for = "clientRequestChangeTable";
            $newNotifyClient->toRes = $TCR->toRes;
            if($TCR->komenti == 'invalideCartToResTable@invalideCartToResTable@invalideCartToResTable'){
                $newNotifyClient->tableNr = $TCR->newTable;
                $newNotifyClient->data = json_encode([
                    'type' => 'clNewTab',
                    'newTable' => $TCR->newTable,
                    'answare' => 'userError',
                    'comment' => 'invalideCartToResTable@invalideCartToResTable@invalideCartToResTable'
                ]);
            }else{
                $newNotifyClient->tableNr = $TCR->crrTable;
                $newNotifyClient->data = json_encode([
                    'type' => 'clNewTab',
                    'newTable' => $TCR->newTable,
                    'answare' => 'userError',
                    'comment' => 'Entschuldigung, wir können Sie nicht zur Tabelle Nummer '.$TCR->newTable.' verschieben'
                ]);
            }
        
            $newNotifyClient->readInd = 0;
            $newNotifyClient->save();

        // approved 
        }else if($req->val == 5){
            $theRes = $TCR->toRes;

            $oldT = TableQrcode::where([['tableNr',$TCR->crrTable],['Restaurant',$TCR->toRes]])->first();
            $newT = TableQrcode::where([['tableNr',$TCR->newTable],['Restaurant',$TCR->toRes]])->first();

            if($TCR->toTableActive == 0){
                // New table is NOT active
                if($oldT->kaTab != 0){
                    $theTab = $oldT->kaTab;

                    $hasOtherOrders = False;
                    if(tabVerificationPNumbers::where([['phoneNr','!=',$TCR->clPhoneNr],['tabCode',$theTab]])->count() > 0){
                        $hasOtherOrders = True;
                        $tabCodeNew = mt_rand(100000, 999999);
                    }

                    foreach(TabOrder::where([['tabCode',$theTab],['toRes',$TCR->toRes]])->get() as $tabOrders){
                        $tabVerify = tabVerificationPNumbers::where('tabOrderId',$tabOrders->id)->firstOrFail();
                        if($tabVerify->phoneNr == $TCR->clPhoneNr){
                            $tabOrders->tableNr = $TCR->newTable;
                            if($hasOtherOrders){
                                $tabOrders->tabCode = $tabCodeNew;
    
                                $tabVerify->tabCode = $tabCodeNew;
                                $tabVerify->save();
                            }
                            $tabOrders->save();
                        }
                    }
                    if($hasOtherOrders){
                        // Ka te tjera ne tavolinen e vjeter
                        $newT->kaTab = $tabCodeNew;
                        $newT->save();
                    }else{
                        // Nuk ka te tjera ne tavolinen e vjeter
                        $oldT->kaTab = 0;
                        $oldT->save();
                        $newT->kaTab = $theTab;
                        $newT->save();
                    }
                }
            }else if($TCR->toTableActive == 1){
                // New table is active
                $mergeToTab = $newT->kaTab;
                $theTab = $oldT->kaTab;

                foreach(TabOrder::where([['tabCode',$theTab],['toRes',$TCR->toRes]])->get() as $tabOrders){
                    $tabVerify = tabVerificationPNumbers::where('tabOrderId',$tabOrders->id)->firstOrFail();
                    if($tabVerify->phoneNr == $TCR->clPhoneNr){
                        $tabOrders->tableNr = $TCR->newTable;
                        $tabOrders->tabCode = $mergeToTab;
                        $tabOrders->save();
    
                        $tabVerify->tabCode =$mergeToTab;
                        $tabVerify->save();
                    }
                }
                if(tabVerificationPNumbers::where([['phoneNr','!=',$TCR->clPhoneNr],['tabCode',$theTab]])->count() <= 0){
                    // Nuk ka te tjera ne tavolinen e vjeter 
                    $oldT->kaTab = 0;
                    $oldT->save();
                }else if(TabOrder::where('tabCode',$oldT->kaTab)->count() <= 0){
                    $oldT->kaTab = 0;
                    $oldT->save();
                }
            }

            // change tableNr on ghost In use records
            if (str_contains($TCR->clPhoneNr, '|')) { 
                $ghostCartInUse = ghostCartInUse::where([['indNr',$TCR->clPhoneNr],['tableNr',$TCR->crrTable],['status',0]])->firstOrFail();
                $ghostCartInUse->tableNr = $TCR->newTable;
                $ghostCartInUse->save();
            }

            $newNotifyClient = new notifyClient();
            $newNotifyClient->for = "clientRequestChangeTable";
            $newNotifyClient->toRes = $TCR->toRes;
            if($TCR->komenti == 'invalideCartToResTable@invalideCartToResTable@invalideCartToResTable'){
                $newNotifyClient->tableNr = $TCR->newTable;
                $newNotifyClient->data = json_encode([
                    'type' => 'clNewTab',
                    'newTable' => $TCR->newTable,
                    'answare' => 'userSuccess',
                    'comment' => 'invalideCartToResTable@invalideCartToResTable@invalideCartToResTable'
                ]);
            }else{
                $newNotifyClient->tableNr = $TCR->crrTable;
                $newNotifyClient->data = json_encode([
                    'type' => 'clNewTab',
                    'newTable' => $TCR->newTable,
                    'answare' => 'userSuccess',
                    'comment' => 'Ihre Anfrage, zur Tabelle Nummer '.$TCR->newTable.' zu wechseln, wird genehmigt. Jemand wird Ihnen bald helfen.'
                ]);
            }

            // mark as read ( Table change request )
            $newNotifyClient->readInd = 0;
            $newNotifyClient->save();
            
        }






        // Send Notifications for the Admin
        foreach(\App\User::where([['sFor',$TCR->toRes],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                $details = [
                    'id' => $TCR->id, 
                    'type' => 'AdminUpdateTableCh',
                    'tableNr' => $TCR->crrTable
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));

                $details = [
                    'id' => $TCR->id, 
                    'type' => 'AdminUpdateTableCh',
                    'tableNr' => $TCR->newTable
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        foreach(\App\User::where([['sFor',$TCR->toRes],['role','55']])->get() as $oneWaiter){
            // register the notification ...
            $details = [
                'id' => $TCR->id, 
                'type' => 'AdminUpdateTableCh',
                'tableNr' => $TCR->crrTable
            ];
            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));

            $details = [
                'id' => $TCR->id, 
                'type' => 'AdminUpdateTableCh',
                'tableNr' => $TCR->newTable
            ];
            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
        }

      
    }












    public function MsgToUser(Request $req){
        // $text = $req->res.'||'.$req->tableNr.'||userMsg||'.$req->msg;
        // event(new clNewTab($text));

        // tableNr: tNr,
        // res: theRes,
        // msg: $('#tableMessageIn'+tId).val(),
        // clSelected: $('#mesageDivAdTClSelectedClient'+tNr).val(),
        foreach(explode('||',$req->clSelected) as $clSelectedOne){
            $newNotifyClient = new notifyClient();
            $newNotifyClient->for = "clientRequestChangeTable";
            $newNotifyClient->toRes = $req->res;
            $newNotifyClient->tableNr = $req->tableNr;
            $newNotifyClient->clPhoneNr = $clSelectedOne;
            $newNotifyClient->data = json_encode([
                'type' => 'clNewTab',
                'answare' => 'userMsg',
                'comment' => $req->msg,
                'adminId' => auth()->user()->id,
                'clSelected' => $clSelectedOne
            ]);
            $newNotifyClient->readInd = 0;
            $newNotifyClient->save();
        }
    }



    public function MsgUserToAdmin(Request $req){
  

        // Send Notifications for the Admin
        $user = \App\User::find($req->adminId);
        $details = [
            'res' => $req->res,
            'table' => $req->table,
            'type' => 'ClientToAdminMessage',
            'msg' => $req->msg,
            'msgAdmin' => $req->msgAdmin,
            'clPhone' => $req->clPhNr,
        ];
        $user->notify(new \App\Notifications\userToAdminNOtification($details));
    }
}
