<?php

namespace App\Http\Controllers;

use App\resContRecommended;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Mail;


class resContRecommendedController extends Controller
{
    public function openFirstPage(){
        $agent = new Agent();
        if($agent->isMobile() && !$agent->isTablet()){
            return view('ResRecommendation/firsdtPageSmartphone');
        }else if ($agent->isTablet()){
            return view('ResRecommendation/firsdtPageTablet');
        }else{
            return view('ResRecommendation/firsdtPageDesktop');
        }
    }

    public function saveInstance(Request $req){

        if(resContRecommended::where('betEmail',$req->bet_Email)->first() != Null){
            return 'resDuplicate';
        }else{
            $newResRec = new resContRecommended();
            $newResRec->konPerVorname = $req->konPer_Vorname;
            $newResRec->konPerName = $req->konPer_Name;
            $newResRec->konPerAdresse = $req->konPer_Adresse;
            $newResRec->konPerPLZ = $req->konPer_PLZ;
            $newResRec->konPerOrt = $req->konPer_Ort;
            $newResRec->konPerTel = $req->konPer_Tel;
            $newResRec->konPerEmail = $req->konPer_Email;
            $newResRec->betBetrieb = $req->bet_Betrieb;
            $newResRec->betInhaber = $req->bet_Inhaber;
            $newResRec->betAdresse = $req->bet_Adresse;
            $newResRec->betPLZ = $req->bet_PLZ;
            $newResRec->betOrt = $req->bet_Ort;
            $newResRec->betTel = $req->bet_Tel;
            $newResRec->betEmail = $req->bet_Email;
            $newResRec->save();
            

            $to_name = 'QRorpa';
            $to_email = str_replace(' ', '', 'empfehlung@qrorpa.ch'); ;
            $data = array('resRecId'=>$newResRec->id, 'name'=>$to_name);
            Mail::send('emails.resRecoNewInsEm', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('QRorpa neues empfohlenes Restaurant');
                $message->from('noreply@qrorpa.ch','Qrorpa');
            });

        }
    
       
    }
}
