<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use SpryngApiHttpPhp\Client;

class ProfileController extends Controller
{
    public function index(){
        return view('profile/profilePage');
    }

    public function setProfilePic(Request $request){

        $this->validate($request, [
            'foto' => 'image|max:199999'
       ]);
            $user = User::find($request->client);
          //get name .etc
          $fileNameOriginal = $request->file('foto')->getClientOriginalName();
          //get just the name
          $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
          // get extension
          $extension = $request->file('foto')->getClientOriginalExtension();
  
          $fileNameStore = $fileName.'_'.time().'.'.$extension;
  
              // Upload
          $path = $request->file('foto')->move('storage/profilePics', $fileNameStore);

          $user->profilePic = $fileNameStore;
          $user->save();
          
        return view('profile/profilePage');
    }








    public function sendConfCodeEmail(Request $req){
        $theCode = random_int(111111,999999);
        // clN: clName,
        // clI: clId,
        // newEm: email,
        $thisU = User::find($req->clI);

        if(User::where('email',$req->newEm)->first() == NULL){ $emailInUse = 'false'; }else{ $emailInUse = 'true'; }

        if($thisU == NULL || $thisU->email == $req->newEm){
            return 'sameEmail';
        }else if($emailInUse == 'true'){
            return 'emailInUse';
        }else{
            // Send the confirmation email ...
            $to_name = $req->clN;
            $to_email = str_replace(' ', '', $req->newEm);
            $data = array('name'=>$to_name, 'code'=>$theCode);

            Mail::send('emails.profileEmailConf', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('E-Mail-Änderungsbestätigungscode QRorpa');
                $message->from('noreply@qrorpa.ch','Qrorpa');
            });

            return $theCode;
        }
    }

    public function saveNewEmail(Request $req){
        // orgCode: originalCode,
        // clCode: clientCode,
        // theEm: originalEmail,
        // uId: userId,

        $thisU = User::find($req->uId);

        if($req->orgCode != $req->clCode){
            return 'codeFail';
        }else{
            $thisU->email = $req->theEm;
            $thisU->save();

            return 'success';
        }
    }











    public function sendConfCodePhoneNr(Request $req){
        $sendTo = 445566 ;
        if(substr($req->phNr, 0, 1) == 0){
            $pref =substr($req->phNr, 0, 3);
            if($pref == '075' || $pref == '076' || $pref == '077' || $pref == '078' || $pref == '079'){
                if(strlen($req->phNr) == 10){
                    $sendTo = '41'.substr($req->phNr, 1, 9);
                    $phToTestForSMS = substr($req->phNr, 1, 9);
                }
            }else{
                return 'falseNR';
            }
        }else{
            $pref =substr($req->phNr, 0, 2);
            if($pref == '75' || $pref == '76' || $pref == '77' || $pref == '78' || $pref == '79'){
                $sendTo = '41'.$req->phNr;
                $phToTestForSMS = $req->phNr;
            }else{
                return 'falseNR';
            }
        }

        if($sendTo != 445566){
            $randCode = rand(111111,999999);
            $sendTo2 = (int)$sendTo;

            if($phToTestForSMS != '763270293' && $phToTestForSMS != '763251809' && $phToTestForSMS != '763459941' && $phToTestForSMS != '763469963'){
                $spryng = new Client('QRorpasms', 'Spryng@qrorpa.ch', 'qrorpa.ch');
                if($spryng->sms->checkBalance() > 0){
                    try {
                        $spryng->sms->send($sendTo2,'Ihr Sicherheitscode ist: '.$randCode.' . Er lauft in 3 Minuten ab.', array(
                            'route'     => 'business',
                            'allowlong' => true,
                            )
                        );
                    }catch (InvalidRequestException $e){
                        dd ($e->getMessage());
                    }
                }
            }

            return $randCode;
        }
    }

    public function saveNewPhoneNr(Request $req){
        // userId: usId,
        // orgCode: originalCode,
        // clCode: clientCode,
        // pnr: phonenr,
        if($req->orgCode != $req->clCode){
            return 'falseCode';
        }else{
            $thisU = User::find($req->userId);

            $thisU->phoneNr = $req->pnr;
            $thisU->save();

            return 'success';
        }
    }






    public function changePassword(Request $req){
        // userId
        // currPass
        // newPass
        // confPass
        $thisU = User::find($req->userId);

        if(!Hash::check($req->currPass, $thisU->password)) {
            return 'currentPassFail';
        }else if(strlen($req->newPass) < 7){
            return 'smallPass';
        }else if($req->newPass != $req->confPass){
            return 'confirmPassFail';
        }else{
            $thisU->password = bcrypt($req->newPass);
            $thisU->save();
            return 'success';
        }
    }

}
