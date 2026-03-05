<?php

namespace App\Http\Controllers;

use App\Restorant;
use Carbon\Carbon;
use App\RestaurantWH;
use App\TableReservation;
use App\tempPhoneNrConfCode;
use Illuminate\Http\Request;
use SpryngApiHttpPhp\Client;
use App\tablesAccessToWaiters;
use App\tabReservationReqEmailSent;
use Illuminate\Support\Facades\Mail;

class TableRezController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){ return view('tableRez/home'); }

    public function getWorkingHrsForRes(Request $req){
        $daySelectedOfWeekNr = date('w', strtotime((string)$req->dt[0]));
        $resWH = RestaurantWH::where('toRes',$req->resId)->first();
        if($daySelectedOfWeekNr == 1 ){ $WHStar1 = $resWH->D1Starts1; $WHEnd1 = $resWH->D1End1; $WHStar2 = $resWH->D1Starts2; $WHEnd2 = $resWH->D1End2;}
        else if($daySelectedOfWeekNr == 2){ $WHStar1 = $resWH->D2Starts1; $WHEnd1 = $resWH->D2End1; $WHStar2 = $resWH->D2Starts2; $WHEnd2 = $resWH->D2End2;}
        else if($daySelectedOfWeekNr == 3){ $WHStar1 = $resWH->D3Starts1; $WHEnd1 = $resWH->D3End1; $WHStar2 = $resWH->D3Starts2; $WHEnd2 = $resWH->D3End2;}
        else if($daySelectedOfWeekNr == 4){ $WHStar1 = $resWH->D4Starts1; $WHEnd1 = $resWH->D4End1; $WHStar2 = $resWH->D4Starts2; $WHEnd2 = $resWH->D4End2;}
        else if($daySelectedOfWeekNr == 5){ $WHStar1 = $resWH->D5Starts1; $WHEnd1 = $resWH->D5End1; $WHStar2 = $resWH->D5Starts2; $WHEnd2 = $resWH->D5End2;}
        else if($daySelectedOfWeekNr == 6){ $WHStar1 = $resWH->D6Starts1; $WHEnd1 = $resWH->D6End1; $WHStar2 = $resWH->D6Starts2; $WHEnd2 = $resWH->D6End2;}
        else if($daySelectedOfWeekNr == 0){ $WHStar1 = $resWH->D7Starts1; $WHEnd1 = $resWH->D7End1; $WHStar2 = $resWH->D7Starts2; $WHEnd2 = $resWH->D7End2;}
        
        
        if($WHStar1 != 'none'){
        $timeShow = Carbon::createFromFormat('H:i', $WHStar1);
        $timeE1 = Carbon::createFromFormat('H:i', $WHEnd1);
        }else{
            $timeShow = 'none';
            $timeE1 = 'none';
        }

        if($WHStar2 != 'none'){
            $timeS2 = Carbon::createFromFormat('H:i', $WHStar2);
            $timeE2 = Carbon::createFromFormat('H:i', $WHEnd2);
        }else{
            $timeS2 = 'none';
            $timeE2 = 'none';
        }

        return $timeShow.'|||'.$timeE1.'|||'.$timeS2.'|||'.$timeE2;
    }

    public function getPhoneNrSendVerificationCode(Request $req){
        $sendTo = 445566 ;
        if(substr($req->phoneNr, 0, 1) == 0){
            $pref =substr($req->phoneNr, 0, 3);
            if($pref == '075' || $pref == '076' || $pref == '077' || $pref == '078' || $pref == '079'){
                if(strlen($req->phoneNr) == 10){
                    $sendTo = '41'.substr($req->phoneNr, 1, 9);
                    $phToTestForSMS = substr($req->phoneNr, 1, 9);
                }
            }
            
        }else{
            $pref =substr($req->phoneNr, 0, 2);
            if($pref == '75' || $pref == '76' || $pref == '77' || $pref == '78' || $pref == '79'){
                $sendTo = '41'.$req->phoneNr;
                $phToTestForSMS = $req->phoneNr;
            }
        }
        if($sendTo != 445566){
            $randCode = rand(111111,999999);

            $sendTo2 = (int)$sendTo;
            $nowTime = date('Y-m-d h:i:s');

            if($phToTestForSMS != '763270293' && $phToTestForSMS != '763251809' && $phToTestForSMS != '763459941' && $phToTestForSMS != '763469963'){
                $spryng = new Client('QRorpasms', 'Spryng@qrorpa.ch', 'qrorpa.ch');
                if($spryng->sms->checkBalance() > 0){
                    try {
                        $spryng->sms->send($sendTo2,'Ihr Sicherheitscode ist: '.$randCode.' . Er lauft in 5 Minuten ab.', array(
                            'route'     => 'business',
                            'allowlong' => true,
                            )
                        );
                    }catch (InvalidRequestException $e){
                        dd ($e->getMessage());
                    }
                }
            }

            $length = 64;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            }

            $tempConfCode = new tempPhoneNrConfCode();
            $tempConfCode->hashValue = $randomString;
            $tempConfCode->confCode = $randCode;
            $tempConfCode->status = 1;
            $tempConfCode->save();

            $confirmData =[
                'code' => $randCode,
                'hash' => $randomString,
                'phoneNr' => $sendTo2,
                'timeStart' => $nowTime,
                'status' => 'success',
            ];
            return $confirmData;

        }else{
            $confirmData =[
                'status' => 'fail',
            ];
            return $confirmData;
        }
    }

    public function sendVerCodeForPhoneNr(Request $req){
        $tempConfCode = tempPhoneNrConfCode::where('hashValue',$req->codeConfHash)->first();
        if($tempConfCode != Null){
            if($tempConfCode->confCode == $req->codeByClinet){

                $confirmData =['status' => 'success',];
                return $confirmData;

            }else{
                $confirmData =['status' => 'failInvalidCode',];
                return $confirmData;
            }
        }else{
            $confirmData =['status' => 'failInvalidHash',];
            return $confirmData;
        }
    }

    public function saveRezRequest(Request $req){
        $newTabReser = new TableReservation();

        $newTabReser->toRes = $req->res;
        $newTabReser->tableNr = 999999;
        $newTabReser->persona = $req->persona;
        $newTabReser->dita = $req->dita;
        $newTabReser->koha01 = $req->koha1;
        $newTabReser->koha02 = $req->koha2;
        $newTabReser->status = 0;
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

        $tabRezEmaNoty = tabReservationReqEmailSent::where('toRes',$req->res)->get();

        $res = Restorant::findOrFail($req->res);
        // event(new newTabRez($req->res));
        foreach(\App\User::where([['sFor',$req->res],['role','5']])->get() as $user){
            $details = [
                'id' => $newTabReser->id,
                'type' => 'AdminUpdateTableRez',
                'tableNr' => '999999'
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        
            if(count($tabRezEmaNoty) == 0){
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
        }

        foreach(\App\User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
            $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->table]])->first();
            if($aToTable != NULL && $aToTable->statusAct == 1){
                // register the notification ...
                $details = [
                    'id' => $newTabReser->id,
                    'type' => 'AdminUpdateTableRez',
                    'tableNr' => '999999'
                ];
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }

        if(count($tabRezEmaNoty) > 0){
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
