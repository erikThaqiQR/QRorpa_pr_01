<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PassResetController extends Controller
{
    public function confEmail(){
        return view('auth/passwords/confemail');
    }

    public function checkEmail(Request $req){
        if(User::where('email',$req->emailUser)->first() != NULL){
            
            $code = rand(11111,99999);

            $to_name = User::where('email',$req->emailUser)->first()->name;
            $to_email = str_replace(' ', '', $req->emailUser); ;
            $data = array("name" => $to_name , "body" => $code);

            Mail::send('emails.passresConf', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('E-Mail-Bestätigung QRorpa');
                $message->from('noreply@qrorpa.ch','Qrorpa');
            });

            return redirect()->route('pasreset.confEmail')->with('success',$code.'||'.$req->emailUser);
        }else{
            return redirect()->route('password.request')->with('error','Diese E-Mail existiert nicht, bitte versuchen Sie es erneut!');
        }
    }













    public function chEmailPass(Request $req){
        if($req->code == $req->codeUser){

            $hashedPass = Hash::make($req->ps);

            $theUser = User::where('email',$req->email)->first();
            $theUser->password = $hashedPass;
            $theUser->save();
          
            return "done";
        }else{
            return "no";
        }

    }
}
