<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;


use Illuminate\Http\Request;

class FirstPageController extends Controller
{
    public function index(){
        // return view('sa/ekstra')->with('ekstra', $ekstra);
        return view('firstPage/index');
    }

    public function iQRcodeScanner(){
        return view('firstPage/qrCodeScanner');
    }

    public function iWieBenutztMan(){ return view('firstPage/wieBenutztMan');}

    public function itischeReservieren(){ return view('firstPage/tischeReservieren');}

    public function idelivery(){ return view('firstPage/delivery');}
    public function itakeaway(){ return view('firstPage/takeaway');}

    public function ikartenzahlung(){ return view('firstPage/kartenzahlung');}


    public function idatenschutz(){ return view('firstPage/datenschutz');}

    public function agbAndPrivtcy(){ return view('agbDatenschutz');}
    public function impressum(){ return view('Impressum');}
    

    public function agbFuerKunden(){ return view('agbFuerKunden');}



    public function SendConFor(Request $req){
        // $code = rand(1111,9999);

        $to_name = 'QRorpa';
        $email = 'info@qrorpa.ch';
        $to_email = str_replace(' ', '', $email); 

        $cfName = $req->name;
        $cfEmail = $req->email;
        $cfSubject = $req->subject;
        $cfMess = $req->message;

        $data = array('name'=>$cfName, 'email' => $cfEmail, 'subject' => $cfSubject, 'msg' => $cfMess);
                        
            Mail::send('emails.mailCF', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('KontaktForm');
                $message->from('noreply@qrorpa.ch','Qrorpa');
            });

    }
    

}
