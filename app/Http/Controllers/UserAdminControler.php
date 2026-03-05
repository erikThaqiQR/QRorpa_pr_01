<?php

namespace App\Http\Controllers;

use App\User;
use App\Orders;
use Carbon\Carbon;
use App\userPlaceholder;
use App\admExtraAccessToRes;
use Illuminate\Http\Request;
use App\accessControllForAdmins;
use App\UserNotiDtlSet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserAdminControler extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('sa/superAdminIndex');
    }
    public function indexKuzhinier(){
        return view('sa/superAdminIndex');
    }
    public function indexKamarier(){
        return view('sa/superAdminIndex');
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

    public function storeKuzhinier(Request $req){
        $convK = User::find($req->user);
        $convK->role = 3;
        $convK->sFor = $req->restaurant;
        $convK->save();

        return redirect('/userKuzhinier');
    }
    public function storeKamarier(Request $req){
        $convK = User::find($req->user);
        $convK->role = 4;
        $convK->sFor = $req->restaurant;
        $convK->save();

        return redirect('/userKuzhinier');

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
    public function destroyKuzhinier(Request $req){
        User::find($req->id)->delete();
    }





    public function changeUR(Request $request){

        $user = User::find($request->userId);

        if($request->chType == '15'){
            $user->role = 5;
            $user->save();
        }else if($request->chType == '59'){
            $user->role = 9;
            $user->sFor = 0;
            $user->save();
            foreach(accessControllForAdmins::where('userId',$user->id)->get() as $delA){  $delA->delete();}
        }else if($request->chType == '91'){
            $user->role = 1;
            $user->sFor = 0;
            $user->save();
            foreach(accessControllForAdmins::where('userId',$user->id)->get() as $delA){  $delA->delete();}
        }
        
    }

    public function setRes(Request $request){
        $user = User::find($request->userId);
        $user->sFor = $request->sendValue;
        $user->save();

        $accR = new accessControllForAdmins;
        $accR->userId = $request->userId;
        $accR->forRes = $request->sendValue;
        $accR->accessDsc = 'Statistiken';
        $accR->accessValid = 1;
        $accR->save();

        $regAccess = array();
        foreach(accessControllForAdmins::where('forRes',$request->sendValue)->get() as $oneA){
            if(!in_array($oneA->accessDsc,$regAccess) && $oneA->accessDsc != 'Statistiken'){
                $accR = new accessControllForAdmins;
                $accR->userId = $request->userId;
                $accR->forRes = $request->sendValue;
                $accR->accessDsc = $oneA->accessDsc;
                $accR->accessValid = 1;
                $accR->save();
                array_push($regAccess,$oneA->accessDsc);
            }
        }
    }







    public function setToUser(Request $req){
        foreach(accessControllForAdmins::where('userId',$req->userId)->get() as $oneAccess){ $oneAccess->delete(); }
        foreach(admExtraAccessToRes::where('admId',$req->userId)->get() as $oExAc){ $oExAc->delete(); }

        $user = User::find($req->userId);
        $user->role = 1;
        $user->sFor = 0;
        $user->save();
    }
    
    public function setToAdmin(Request $req){
        foreach(accessControllForAdmins::where('userId',$req->userId)->get() as $oneAccess){ $oneAccess->delete(); }
        foreach(admExtraAccessToRes::where('admId',$req->userId)->get() as $oExAc){ $oExAc->delete(); }

        $user = User::find($req->userId);
        $user->role = 5;
        $user->sFor = 0;
        $user->save();
    }

    public function setToWaiter(Request $req){
        foreach(accessControllForAdmins::where('userId',$req->userId)->get() as $oneAccess){ $oneAccess->delete(); }
        foreach(admExtraAccessToRes::where('admId',$req->userId)->get() as $oExAc){ $oExAc->delete(); }

        $user = User::find($req->userId);
        $user->role = 55;
        $user->sFor = 0;
        $user->save();
    }

    public function setToCook(Request $req){
        foreach(accessControllForAdmins::where('userId',$req->userId)->get() as $oneAccess){ $oneAccess->delete(); }
        foreach(admExtraAccessToRes::where('admId',$req->userId)->get() as $oExAc){ $oExAc->delete(); }

        $user = User::find($req->userId);
        $user->role = 54;
        $user->sFor = 0;
        $user->save();
    }

    public function setToAccountant(Request $req){
        foreach(accessControllForAdmins::where('userId',$req->userId)->get() as $oneAccess){ $oneAccess->delete(); }
        foreach(admExtraAccessToRes::where('admId',$req->userId)->get() as $oExAc){ $oExAc->delete(); }

        $user = User::find($req->userId);
        $user->role = 53;
        $user->sFor = 0;
        $user->save();
    }

    public function setToSuperadmin(Request $req){
        foreach(accessControllForAdmins::where('userId',$req->userId)->get() as $oneAccess){ $oneAccess->delete(); }
        foreach(admExtraAccessToRes::where('admId',$req->userId)->get() as $oExAc){ $oExAc->delete(); }

        $user = User::find($req->userId);
        $user->role = 9;
        $user->sFor = 0;
        $user->save();
    }

    public function setToContractAgent(Request $req){
        foreach(accessControllForAdmins::where('userId',$req->userId)->get() as $oneAccess){ $oneAccess->delete(); }
        foreach(admExtraAccessToRes::where('admId',$req->userId)->get() as $oExAc){ $oExAc->delete(); }

        $user = User::find($req->userId);
        $user->role = 33;
        $user->sFor = 0;
        $user->save();
    }

    public function setToBarbershopAdminAgent(Request $req){
        foreach(accessControllForAdmins::where('userId',$req->userId)->get() as $oneAccess){ $oneAccess->delete(); }
        foreach(admExtraAccessToRes::where('admId',$req->userId)->get() as $oExAc){ $oExAc->delete(); }

        $user = User::find($req->userId);
        $user->role = 15;
        $user->sFor = 0;
        $user->save();
    }




    public function selectThisResForThisAdm(Request $req){
        foreach( accessControllForAdmins::where('userId',$req->userId)->get() as $oldAcCon){ $oldAcCon->delete(); }

        $user = User::find($req->userId);
        $pagesAdmin = ['Statistiken','Aufträge','Empfohlen','RechnungMngAcce','Kellner','Products','Tabellenwechsel','Trinkgeld','Frei','16+/18+','Gutscheincode','Takeaway','Delivery','Tischkapazität','Tischreservierungen','Dienstleistungen','Covid-19','talkToQrorpaSA','workersManagement'];
        
        foreach($pagesAdmin as $p1){
            $theAccess = accessControllForAdmins::where([['userId',$user->id],['accessDsc',$p1]])->first();
            if($theAccess == NULL){
                // is not registred , we'll register it
                $accR = new accessControllForAdmins;
                $accR->userId = $user->id;
                $accR->forRes = $req->rId;
                $accR->accessDsc = $p1;
                $accR->accessValid = 1;
                $accR->save();
            }
        }

        $user->sFor = $req->rId;
        $user->save();
    }

    public function selectThisBarForThisAdm(Request $req){
        $user = User::find($req->userId);
        $user->sFor = $req->bId;
        $user->save();
    }









    public function setResToAdmExtraAcs(Request $req){
        $newExAc = new admExtraAccessToRes();
        $newExAc->admId = $req->aId;
        $newExAc->toRes = $req->rId;
        $newExAc->accessType = 1;
        $newExAc->save();
    }

    public function removeResToAdmExtraAcs(Request $req){
        $exAc = admExtraAccessToRes::where([['admId',$req->aId],['toRes',$req->rId]])->first();
        if($exAc != NULL){
            $exAc->delete();
        }
    }



    public function checkIfAUserExistsWithEmail(Request $req){
        if(User::where('email',$req->theEm)->first() != Null){ 
            return 'usrEx'; 
        }else{
            if(userPlaceholder::where('usremail',$req->theEm)->first() != Null){
                return 'usrEx';
            }else{
                return 'negative'; 
            }
        }
    }
    private function genNewHash48B(){
        $word2 = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'));
        shuffle($word2);
        $hash = substr(implode($word2), 0, 48);

        if(userPlaceholder::where('confirmhash',$hash)->first() == Null){
            return $hash;
        }else{
            return $this->genNewHash48B();
        }
    }
    public function registerTemUser(Request $req){
        // theEm: email, 
        // pass: $('#createAccPass1').val(), 
        // orId: orId, 
        // trM: trMO, 
        
        $order = Orders::find((int)$req->orId);
        if($order == Null){
            return 'orderNull';
        }else{
            $hash =  $this->genNewHash48B();

            $usrPl = new userPlaceholder();
            $usrPl->usrname = $order->TAemri;
            $usrPl->usrlastname = $order->TAmbiemri;
            $usrPl->usrphonenr = $order->userPhoneNr;
            $usrPl->usremail = $req->theEm;
            $usrPl->usrpass = bcrypt($req->pass);
            
            $usrPl->confirmhash = $hash;
            $usrPl->save();

            $to_name = $order->TAemri.' '.$order->TAmbiemri;
            $to_email = str_replace(' ', '', $req->theEm); 

            $data = array('name'=>$to_name, "tmpId" => $usrPl->id, "tmpHash" => $hash);
            Mail::send('emails.userTempCreatedEm', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('Überprüfung des QRorpa-Kontos');
                $message->from('noreply@qrorpa.ch','Qrorpa');
            });

            return 'usrPlSuccess';
        }
    }
    public function registerTemUserRes(Request $req){
        $order = Orders::whereDate('created_at', Carbon::today())->where([['Restaurant',$req->res],['shifra',$req->trM]])->first(); 
        if($order == Null){
            return 'orderNull';
        }else{
            $hash =  $this->genNewHash48B();

            $usrPl = new userPlaceholder();
            $usrPl->usrname = $req->name;
            $usrPl->usrlastname = $req->lastname;
            $usrPl->usrphonenr = $order->userPhoneNr;
            $usrPl->usremail = $req->theEm;
            $usrPl->usrpass = bcrypt($req->pass);
            
            $usrPl->confirmhash = $hash;
            $usrPl->save();

            $to_name = $order->TAemri.' '.$order->TAmbiemri;
            $to_email = str_replace(' ', '', $req->theEm); 

            $data = array('name'=>$to_name, "tmpId" => $usrPl->id, "tmpHash" => $hash);
            Mail::send('emails.userTempCreatedEm', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('Überprüfung des QRorpa-Kontos');
                $message->from('noreply@qrorpa.ch','Qrorpa');
            });

            return 'usrPlSuccess';
        }
    }
    public function taAcRegesterConfFC(){
        $usrPlIns = userPlaceholder::find($_GET['tui']);
        if($usrPlIns != Null){
            if($usrPlIns->confirmhash == 'empty'){
                return redirect()->route("login", ["taRegErr"=>'regAlrUsed']);
            }else if($_GET['tuh'] == $usrPlIns->confirmhash){
                $newUser = new User();
                $newUser->name = $usrPlIns->usrname.' '.$usrPlIns->usrlastname;
                $newUser->email = $usrPlIns->usremail;
                $newUser->phoneNr = $usrPlIns->usrphonenr;
                $newUser->role = 1;
                $newUser->sFor = 0;
                $newUser->password = $usrPlIns->usrpass;
                $newUser->save();

                $usrPlIns->confirmhash = 'empty';
                $usrPlIns->save();

                return redirect()->route("login", ["taRegScc"=>$usrPlIns->usremail]);
            }else{
                return redirect()->route("login", ["taRegErr"=>'invalidHash']);
            }
        }else{
            return redirect()->route("login", ["taRegErr"=>'regReqNotFound']);
        }
    }







    public function notificationsActChng(Request $req){
        $theU = user::find(Auth::user()->id);

        $NoSet2D = explode('--||--',$theU->notifySet);
        if((int)$req->notifyNr == 0){
            $newNoSet = (int)$req->notifySetVal.'--||--'.$NoSet2D[1].'--||--'.$NoSet2D[2];
        }else  if((int)$req->notifyNr == 1){
            $newNoSet = $NoSet2D[0].'--||--'.(int)$req->notifySetVal.'--||--'.$NoSet2D[2];
        }else  if((int)$req->notifyNr == 2){
            $newNoSet = $NoSet2D[0].'--||--'.$NoSet2D[1].'--||--'.(int)$req->notifySetVal;
        }

        $theU->notifySet = $newNoSet;
        $theU->save();

    }

    public function notificationsActSetNewGlowTbColor(Request $req){
        $usrNotiReg =  UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',1]])->first();
        if($usrNotiReg != Null){
            $usrNotiReg->setValue = $req->newColor;
            $usrNotiReg->save();
        }else{
            $newRecord = new UserNotiDtlSet();
            $newRecord->UsrId = Auth::user()->id;
            $newRecord->setType = 1;
            $newRecord->setValue = $req->newColor;
            $newRecord->extInfo = "GlowColorinHex";
            $newRecord->save();
        }
    }

    public function notificationsActSetNewSound21(Request $req){
        $usrNotiReg =  UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',21]])->first();
        if($usrNotiReg != Null){
            $usrNotiReg->setValue = $req->newSndId;
            $usrNotiReg->save();
        }else{
            $newRecord = new UserNotiDtlSet();
            $newRecord->UsrId = Auth::user()->id;
            $newRecord->setType = 21;
            $newRecord->setValue = $req->newSndId;
            $newRecord->extInfo = "soundById_userSoundList";
            $newRecord->save();
        }
    }

    public function notificationsActSetNewSound31(Request $req){
        $usrNotiReg =  UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',31]])->first();
        if($usrNotiReg != Null){
            $usrNotiReg->setValue = $req->newSndId;
            $usrNotiReg->save();
        }else{
            $newRecord = new UserNotiDtlSet();
            $newRecord->UsrId = Auth::user()->id;
            $newRecord->setType = 31;
            $newRecord->setValue = $req->newSndId;
            $newRecord->extInfo = "soundById_userSoundList";
            $newRecord->save();
        }
    }
}
