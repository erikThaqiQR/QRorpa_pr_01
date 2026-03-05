<?php

namespace App\Http\Controllers;

use App\Restorant;
use App\Events\newTabRez;
use App\TableReservation;
use Illuminate\Http\Request;
use SpryngApiHttpPhp\Client;

use App\tablesAccessToWaiters;
use App\tabReservationReqEmailSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TableReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('adminPanel/adminIndex');
    }
    public function indexRezList(){
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

        if($req->tel != '' && (strlen($req->tel) == 9 || strlen($req->tel) == 10 )){
            $sendTo = 445566 ;
            
            if(substr($req->tel, 0, 1) == 0){
                $pref =substr($req->tel, 0, 3);
                if($pref == '075' || $pref == '076' || $pref == '077' || $pref == '078' || $pref == '079'){
                    if(strlen($req->tel) == 10){
                        $sendTo = '41'.substr($req->tel, 1, 9);
                    }
                }
            }else{
                $pref =substr($req->tel, 0, 2);
                if($pref == '75' || $pref == '76' || $pref == '77' || $pref == '78' || $pref == '79'){
                    $sendTo = '41'.$req->tel;
                }
                
            }
            if($sendTo != 445566){
                $sendTo2 = (int)$sendTo;

                $newTabReser = new TableReservation;

                $newTabReser->toRes = $req->res;
                $newTabReser->tableNr = $req->table;
                $newTabReser->persona = $req->persona;
                $newTabReser->dita = $req->dita;
                $newTabReser->koha01 = $req->koha1;
                $newTabReser->koha02 = $req->koha2;
                $newTabReser->status = 0;
                $newTabReser->emri = $req->emri;
                $newTabReser->mbiemri = $req->mbiemri;
                $newTabReser->nrTel = $sendTo2;
                $newTabReser->email = $req->email;
                if($req->koment == ''){
                    $newTabReser->koment = 'empty';
                }else{
                    $newTabReser->koment = $req->koment;
                }
                $hashLength = 32;
                $randHash = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($hashLength/strlen($x)) )),1,$hashLength);
                $newTabReser->idnHash = $randHash;
        
                $newTabReser->save();
        
                // event(new newTabRez($req->res));
                foreach(\App\User::where([['sFor',$req->res],['role','5']])->get() as $user){
                    $details = [
                        'id' => $newTabReser->id,
                        'type' => 'AdminUpdateTableRez',
                        'tableNr' => $req->table
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                

                    // send Email to admin 
                    // $res = Restorant::findOrFail($TabRezRecord->toRes);
                    $res = Restorant::findOrFail($req->res);
                    $to_name = $user->name;
                    $to_email = str_replace(' ', '', $user->email);
                    $data = array("tabRezId" => $newTabReser->id, "tabRezHash" => $randHash, "res"=>$res->emri, "tableNr" => $req->table, "xPersona" => $req->persona, "theDay" => $req->dita, "time1" => $req->koha1, "time2" => $req->koha2, "emri" => $req->emri, "mbiemri" => $req->mbiemri, "phoneNr" => $sendTo2, "email" => $req->email, "komenti" => $req->koment);
                    Mail::send('emails.toAdminTableRezRes', $data, function($message) use ($to_name, $to_email) {
                        $message->to($to_email, $to_name)
                                ->subject('Tischreservierungsanfrage!');
                        $message->from('noreply@qrorpa.ch','Qrorpa');
                    });
                }

                foreach(\App\User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->table]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $newTabReser->id,
                            'type' => 'AdminUpdateTableRez',
                            'tableNr' => $req->table
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
        



                return 'Yes';
            }else{
                return 'No';
            }
        }else{
            return 'No';
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

















    public function chgStatus(Request $req){
        $TabRezRecord = TableReservation::find($req->id); 
        if( $TabRezRecord->status == 0 ){
            $TabRezRecord->status = $req->newVal;
       
            $TabRezRecord->save();

            // $spryng = new Client('QRorpasms', 'Spryng@qrorpa.ch', 'qrorpa.ch');
            if($req->newVal == 1){
                // Pranohet Rezervimi 

                // if($spryng->sms->checkBalance() > 0){
                //     try {
                //         $spryng->sms->send($TabRezRecord->nrTel, 'Ihre Tischreservierungsanfrage wurde genehmigt' , array(
                //             'route'     => 'business',
                //             'allowlong' => true,
                //             )
                //         );
                //     }catch (InvalidRequestException $e){
                //         dd ($e->getMessage());
                //     }
                // }

                // $resName = Restorant::findOrFail($TabRezRecord->toRes)->emri;
                $to_name = $TabRezRecord->emri." ".$TabRezRecord->mbiemri;
                $to_email = str_replace(' ', '', $TabRezRecord->email); ;
                $data = array('resId'=>$TabRezRecord->toRes, "tableNr" => $TabRezRecord->tableNr, "theDay" => $TabRezRecord->dita, "time1" => $TabRezRecord->koha01, "time2" => $TabRezRecord->koha02, "komenti" => $TabRezRecord->koment, "clName" => $to_name, "persona" => $TabRezRecord->persona);
                Mail::send('emails.confTableRezRes', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                            ->subject('Tischreservierungsbestätigung');
                    $message->from('noreply@qrorpa.ch','Qrorpa');
                });

            }else if($req->newVal == 2){
                // Refuzohet Rezervimi 

                // if($spryng->sms->checkBalance() > 0){
                //     try {
                //         $spryng->sms->send($TabRezRecord->nrTel, 'Ihre Tischreservierungsanfrage wurde nicht genehmigt' , array(
                //             'route'     => 'business',
                //             'allowlong' => true,
                //             )
                //         );
                //     }catch (InvalidRequestException $e){
                //         dd ($e->getMessage());
                //     }
                // }

                // $resName = Restorant::findOrFail($TabRezRecord->toRes)->emri;
                $to_name = $TabRezRecord->emri." ".$TabRezRecord->mbiemri;
                $to_email = str_replace(' ', '', $TabRezRecord->email); ;
                $data = array('resId'=>$TabRezRecord->toRes, "tableNr" => $TabRezRecord->tableNr, "theDay" => $TabRezRecord->dita, "time1" => $TabRezRecord->koha01, "time2" => $TabRezRecord->koha02, "komenti" => $TabRezRecord->koment, "clName" => $to_name, "persona" => $TabRezRecord->persona);
                Mail::send('emails.declTableRezRes', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                            ->subject('Tischreservierungsbestätigung');
                    $message->from('noreply@qrorpa.ch','Qrorpa');
                });
            }

            // Send Notifications for the Admin
            foreach(\App\User::where([['sFor',$TabRezRecord->toRes],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $TabRezRecord->id,
                        'type' => 'AdminUpdateTableRez',
                        'tableNr' => $TabRezRecord->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            foreach(\App\User::where([['sFor',$TabRezRecord->toRes],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $TabRezRecord->tableNr]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $TabRezRecord->id,
                        'type' => 'AdminUpdateTableRez',
                        'tableNr' => $TabRezRecord->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
    
        }
    }







    public function processAReservationFromEmailAdmin(){
        // var1 id
        // var2 hash
        // var3 new status

        $theReservation = TableReservation::find($_GET['var1']);
        if($theReservation != NULL &&  $theReservation->status == 0 && $theReservation->idnHash == $_GET['var2']){
            $theReservation->status = $_GET['var3'];
            $theReservation->save();

            // Send email to the client.
            // $resName = Restorant::findOrFail($theReservation->toRes)->emri;
            $to_name = $theReservation->emri." ".$theReservation->mbiemri;
            $to_email = str_replace(' ', '', $theReservation->email); ;
            $data = array('resId'=>$theReservation->toRes, "tableNr" => $theReservation->tableNr, "theDay" => $theReservation->dita, "time1" => $theReservation->koha01, "time2" => $theReservation->koha02, "komenti" => $theReservation->koment, "clName" => $to_name, "persona" => $theReservation->persona);
            if($_GET['var3'] == 1){ 
                Mail::send('emails.confTableRezRes', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                            ->subject('Tischreservierungsbestätigung');
                    $message->from('noreply@qrorpa.ch','Qrorpa');
                });
            }else if($_GET['var3'] == 2){
                Mail::send('emails.declTableRezRes', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                            ->subject('Tischreservierungsbestätigung');
                    $message->from('noreply@qrorpa.ch','Qrorpa');
                });
            }

            // Send Notifications for the Admin
            foreach(\App\User::where([['sFor',$theReservation->toRes],['role','5']])->get() as $user){
                // if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $theReservation->id,
                        'type' => 'AdminUpdateTableRez',
                        'tableNr' => $theReservation->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                // }
            }
            foreach(\App\User::where([['sFor',$theReservation->toRes],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $theReservation->tableNr]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $theReservation->id,
                        'type' => 'AdminUpdateTableRez',
                        'tableNr' => $theReservation->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
    
            return redirect('/infoPageTableReservationAdminEmail?Res='.$theReservation->toRes.'&RezId='.$theReservation->id.'&hash='.$theReservation->idnHash);
        }else{
            return redirect('/infoPageTableReservationAdminEmail?Res='.$theReservation->toRes.'&RezId=0.');
        }
  
    }























    public function RezReqCancel(Request $req){
        $TabRezRecord = TableReservation::find($req->id); 
        $TabRezRecord->status = 2;
        $TabRezRecord->save();

            // $message = $client->message()->send([
            //  		'to' => $TabRezRecord->nrTel,
            //  		'from' => 'qrorpa.ch',
            //  		'text' => 'Ihre Tischreservierungsanfrage wurde nicht genehmigt'
            // ]);

        $to_name = $TabRezRecord->emri." ".$TabRezRecord->mbiemri;
        $to_email = str_replace(' ', '', $TabRezRecord->email); ;
        $data = array('resId'=>$TabRezRecord->toRes, "tableNr" => $TabRezRecord->tableNr, "theDay" => $TabRezRecord->dita, "time1" => $TabRezRecord->koha01, "time2" => $TabRezRecord->koha02, "komenti" => $TabRezRecord->koment, "clName" => $to_name, "persona" => $TabRezRecord->persona);
        Mail::send('emails.declTableRezRes', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('Tischreservierungsbestätigung');
            $message->from('noreply@qrorpa.ch','Qrorpa');
        });
    }
    public function RezReqConfirm(Request $req){

        $tablesReserved = "";
        $TabRezRecord = TableReservation::find($req->id); 

        foreach($req->tableS as $seT){

            $newTabReser = new TableReservation;

            $newTabReser->toRes = $TabRezRecord->toRes;
            $newTabReser->tableNr = $seT;
            $newTabReser->persona = $TabRezRecord->persona;
            $newTabReser->dita = $TabRezRecord->dita;
            $newTabReser->koha01 = $TabRezRecord->koha01;
            $newTabReser->koha02 = $TabRezRecord->koha02;
            $newTabReser->status = 1;
            $newTabReser->emri = $TabRezRecord->emri;
            $newTabReser->mbiemri = $TabRezRecord->mbiemri;
            $newTabReser->nrTel = $TabRezRecord->nrTel;
            $newTabReser->email = $TabRezRecord->email;
            $newTabReser->koment = $TabRezRecord->koment;
    
            $newTabReser->save();

            if($tablesReserved == ""){
                $tablesReserved =$seT;
            }else{
                $tablesReserved .=",".$seT;
            }
        }

        // $TabRezRecord->status = 1;
        // $TabRezRecord->save();
     
        // $message = $client->message()->send([
        //  	'to' => $TabRezRecord->nrTel,
        //  	'from' => 'qrorpa.ch',
        //  	'text' => 'Ihre Tischreservierungsanfrage wurde genehmigt'
        // ]);

        // Send email to the client.
        // $resName = Restorant::findOrFail($TabRezRecord->toRes)->emri;
        $to_name = $TabRezRecord->emri." ".$TabRezRecord->mbiemri;
        $to_email = str_replace(' ', '', $TabRezRecord->email); ;
        $data = array('resId'=>$TabRezRecord->toRes, "tableNr" => $TabRezRecord->tableNr, "theDay" => $TabRezRecord->dita, "time1" => $TabRezRecord->koha01, "time2" => $TabRezRecord->koha02, "komenti" => $TabRezRecord->koment, "clName" => $to_name, "persona" => $TabRezRecord->persona);
        Mail::send('emails.confTableRezRes', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('Tischreservierungsbestätigung');
            $message->from('noreply@qrorpa.ch','Qrorpa');
        });


        $TabRezRecord->delete();
             
        
    }








    public function regEmForTabRezNotify(Request $req){
        $tabRezEmNoty = tabReservationReqEmailSent::where([['toRes',Auth::user()->sFor],['email',$req->theEm]])->first();
        if($tabRezEmNoty != Null){
            return 'emailExists';
        }else{
            $newEmReg = new tabReservationReqEmailSent();
            $newEmReg->toRes = Auth::user()->sFor;
            $newEmReg->email = $req->theEm;
            $newEmReg->save();

            return 'success';
        }
    }

    public function delEmForTabRezNotify(Request $req){
        $tabRezEmNoty = tabReservationReqEmailSent::find($req->theIns);
        if($tabRezEmNoty != Null){ $tabRezEmNoty->delete(); }
    }






    public function saveReservationFromStaf(Request $req){
        $tabRezEmaNoty = tabReservationReqEmailSent::where('toRes',$req->res)->get();

        foreach(explode('|||',$req->tablesAll) as $tabOne){
            $newTabReser = new TableReservation();

            $newTabReser->toRes = $req->res;
            $newTabReser->tableNr = $tabOne;
            $newTabReser->persona = $req->persona;
            $newTabReser->dita = $req->dita;
            $newTabReser->koha01 = $req->koha1;
            $newTabReser->koha02 = $req->koha2;
            $newTabReser->status = 1;
            $newTabReser->emri = $req->emri;
            $newTabReser->mbiemri = $req->mbiemri;
            $newTabReser->nrTel = $req->tel;
            $newTabReser->email = $req->email;
            if($req->koment == ''){
                $newTabReser->koment = 'empty';
            }else{
                $newTabReser->koment = $req->koment;
            }
            $newTabReser->qroMarketing = $req->qroMar;
            $newTabReser->resMarketing = $req->resMar;
            $hashLength = 32;
            $randHash = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($hashLength/strlen($x)) )),1,$hashLength);
            $newTabReser->idnHash = $randHash;
            
            $newTabReser->save();

            $res = Restorant::findOrFail($req->res);
            // event(new newTabRez($req->res));
            foreach(\App\User::where([['sFor',$req->res],['role','5']])->get() as $user){
                if($user->id != Auth::user()->id){
                    $details = [
                        'id' => $newTabReser->id,
                        'type' => 'AdminUpdateTableRez',
                        'tableNr' => $tabOne
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }

            foreach(\App\User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
                if($oneWaiter->id != Auth::user()->id){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->table]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $newTabReser->id,
                            'type' => 'AdminUpdateTableRez',
                            'tableNr' => $tabOne
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
            }
        }

        if( $newTabReser->email != 'empty'){
            $to_name = $newTabReser->emri." ".$newTabReser->mbiemri;
            $to_email = str_replace(' ', '', $newTabReser->email); ;
            $data = array('resId'=>$newTabReser->toRes, "tableNr" => $newTabReser->tableNr, "theDay" => $newTabReser->dita, "time1" => $newTabReser->koha01, "time2" => $newTabReser->koha02, "komenti" => $newTabReser->koment, "clName" => $to_name, "persona" => $newTabReser->persona);
            Mail::send('emails.confTableRezRes', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('Tischreservierungsbestätigung');
                $message->from('noreply@qrorpa.ch','Qrorpa');
            });
        }


        if(count($tabRezEmaNoty) == 0){
            foreach(\App\User::where([['sFor',$req->res],['role','5']])->get() as $user){
                // send Email to admin 
                // $res = Restorant::findOrFail($TabRezRecord->toRes);
                $to_name = $user->name;
                $to_email = str_replace(' ', '', $user->email);
                $data = array("tabRezId" => $newTabReser->id, "tabRezHash" => $randHash, "res"=>$res->emri, "tableNr" => '999999', "xPersona" => $req->persona, "theDay" => $req->dita, "time1" => $req->koha1, "time2" => $req->koha2, "emri" => $req->emri, "mbiemri" => $req->mbiemri, "phoneNr" => $req->tel, "email" => $req->email, "komenti" => $req->koment);
                Mail::send('emails.toAdminTableRezRes', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                            ->subject('Tischreservierungsanfrage!');
                    $message->from('noreply@qrorpa.ch','Qrorpa');
                });
            }
        }else{
            $res = Restorant::findOrFail($req->res);
            foreach($tabRezEmaNoty as $oneEmNoty){
                // send Email to admin 
                // $res = Restorant::findOrFail($TabRezRecord->toRes);
                $to_name = explode('@',$oneEmNoty->email)[0];
                $to_email = str_replace(' ', '', $oneEmNoty->email);
                $data = array("tabRezId" => $newTabReser->id, "tabRezHash" => $randHash, "res"=>$res->emri, "tableNr" => '999999', "xPersona" => $req->persona, "theDay" => $req->dita, "time1" => $req->koha1, "time2" => $req->koha2, "emri" => $req->emri, "mbiemri" => $req->mbiemri, "phoneNr" => $req->tel, "email" => $req->email, "komenti" => $req->koment);
                Mail::send('emails.toAdminTableRezRes', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                            ->subject('Tischreservierungsanfrage!');
                    $message->from('noreply@qrorpa.ch','Qrorpa');
                });
            }
        }
    }








}
