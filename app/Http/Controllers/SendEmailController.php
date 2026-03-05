<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\User;

class SendEmailController extends Controller
{
    public function send(Request $request){


        $code = rand(1111,9999);

        $to_name = $request->emri;
        $to_email = str_replace(' ', '', $request->email); ;
        $data = array('name'=>$to_name, "body" => $code);
            
        if(User::where('email', $to_email)->first() == null){
            
            Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('Email confirmation Code ');
                $message->from('noreply@qrorpa.ch','Qrorpa');
            });

            return $code;
        }else{
            return 9999999;
        }
    }






    public function sendMailCRM(Request $request){


        $code = rand(1111,9999);

        $to_name = 'erik';
        $to_email = str_replace(' ', '', $request->email); ;
        $data = array('name'=>$to_name, "body" => $code);
            
            Mail::send('emails.mailCRM', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('Marketing email');
                $message->from('noreply@qrorpa.ch','Qrorpa');
            });

            return $code;
    }
}

?>
