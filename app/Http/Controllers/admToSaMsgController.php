<?php

namespace App\Http\Controllers;

use App\User;
use App\admMsgSaav;
use App\admMsgSaavchats;
use App\Restorant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class admToSaMsgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdminPanel(){
        return view('adminPanel/adminIndex');
    }

    public function indexSuperadminPanel(){
        return view('sa.superAdminIndex');
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







    public function ASASaveNewCard(Request $req){
        // adminId
        // reasonOfCard

        $oneSA = User::where('role',9)->first();

        $newConvCard = new admMsgSaav();
        $newConvCard->avTittle = $req->reasonOfCard;
        $newConvCard->avById = $req->adminId;
        $newConvCard->avForId = $oneSA->id;
        $newConvCard->activeState = 1;
        $newConvCard->save();

        $details = [
            'type' => 'newMsgFromAdminsAddAV',
            'avid' => 0,
            'avChatid' => 0,
            'byId' => 0,
            'forId' => 0,
            'theMsg' => 0
        ];
        $oneSA->notify(new \App\Notifications\newMsgFromQRorpa($details));

        $theAdminName = User::find($req->adminId);
        $theRes = Restorant::find($theAdminName->sFor);
        $to_name = $oneSA->name;
        $to_email = str_replace(' ', '', $oneSA->email); ;
        $data = array('name'=>$to_name, "avReason" => $req->reasonOfCard, "theAdmin" => $theAdminName->name, "admEmail"=>$theAdminName->email, "resName"=>$theRes->emri, "resAdr"=>$theRes->adresa);
        Mail::send('emails.superadminChatNewAv', $data, function($message) use ($to_name, $to_email, $theAdminName) {
            $message->to($to_email, $to_name)
                    ->subject($theAdminName->name.' möchte mit Ihnen chatten');
            $message->from('noreply@qrorpa.ch','Qrorpa');
        });
    }

    public function ASADeleteCard(Request $req){
        // avId
        $convCard = admMsgSaav::find($req->avId);
        if($convCard != NULL){
            foreach(admMsgSaavchats::where('avId',$req->avId)->get() as $oChat){
                $oChat->delete();
            }
            $convCard->delete();
        }

        $oneSA = User::find($convCard->avForId);
        if($oneSA != NULL){
            $details = [
                'type' => 'newMsgFromAdminsDelAV',
                'avid' => 0,
                'avChatid' => 0,
                'byId' => 0,
                'forId' => 0,
                'theMsg' => 0
            ];
            $oneSA->notify(new \App\Notifications\newMsgFromQRorpa($details));
        }
    }

    public function ASASaveNewMessageOnCard(Request $req){
        // adminId: 
        // avId: 
        // theMsg: 
        $oneSA = User::where('role',9)->first();

        $newMsg = new admMsgSaavchats();
        $newMsg->avId = $req->avId;
        $newMsg->msgById = $req->adminId;
        $newMsg->msgForId = $oneSA->id;
        $newMsg->msgContent = $req->theMsg;
        $newMsg->readStatus = 0;
        $newMsg->save();

        $details = [
            'type' => 'newMsgFromAdmins',
            'avid' => $req->avId,
            'avChatid' => $newMsg->id,
            'byId' => $req->adminId,
            'forId' => $oneSA->id,
            'theMsg' => $req->theMsg
        ];
        $oneSA->notify(new \App\Notifications\newMsgFromQRorpa($details));

        $convCard = admMsgSaav::find($req->avId);
        $theAdminName = User::find($req->adminId);
        $theRes = Restorant::find($req->theRes);
        $to_name = $oneSA->name;
        $to_email = str_replace(' ', '', $oneSA->email); ;
        $data = array('name'=>$to_name, "avReason" => $convCard->avTittle, "theAdmin" => $theAdminName->name, "admEmail"=>$theAdminName->email, "resName"=>$theRes->emri, "resAdr"=>$theRes->adresa);
        Mail::send('emails.superadminChatNewMsg', $data, function($message) use ($to_name, $to_email, $theAdminName) {
            $message->to($to_email, $to_name)
                    ->subject($theAdminName->name.' hat Ihnen geschrieben');
            $message->from('noreply@qrorpa.ch','Qrorpa');
        });

        return $req->theMsg.'||'.$newMsg->created_at;
    }

    public function ASASaveNewMessageOnCard2(Request $req){
        $convCard = admMsgSaav::find($req->avId);

        $newMsg = new admMsgSaavchats();
        $newMsg->avId = $req->avId;
        $newMsg->msgById = $req->adminId;
        $newMsg->msgForId = $convCard->avById;
        $newMsg->msgContent = $req->theMsg;
        $newMsg->readStatus = 0;
        $newMsg->save();


        $notifyUsr = User::where('role',9)->first();
        if($notifyUsr != NULL){
            $details = [
                'type' => 'newMsgFromAdmins',
                'avid' => $req->avId,
                'avChatid' => $newMsg->id,
                'byId' => $req->adminId,
                'forId' => $convCard->avById,
                'theMsg' => $req->theMsg
            ];
            $notifyUsr->notify(new \App\Notifications\newMsgFromQRorpa($details));
        }
        $convCard = admMsgSaav::find($req->avId);
        $theAdminName = User::find($convCard->avById);
        $theRes = Restorant::find($req->theRes);
        $to_name = $notifyUsr->name;
        $to_email = str_replace(' ', '', $notifyUsr->email); ;
        $data = array('name'=>$to_name, "avReason" => $convCard->avTittle, "theAdmin" => $theAdminName->name, "admEmail"=>$theAdminName->email, "resName"=>$theRes->emri, "resAdr"=>$theRes->adresa);
        Mail::send('emails.superadminChatNewMsg', $data, function($message) use ($to_name, $to_email, $theAdminName) {
            $message->to($to_email, $to_name)
                    ->subject($theAdminName->name.' hat Ihnen geschrieben');
            $message->from('noreply@qrorpa.ch','Qrorpa');
        });

        return $req->theMsg.'||'.$newMsg->created_at;
    }

    public function AdmIReadTheMsg(Request $req){

        $msgCrtChat = admMsgSaavchats::find($req->msgId);
        // mark read
        $msgCrtChat->readStatus = 1;
        $msgCrtChat->save();

        $msgSender = User::find($msgCrtChat->msgById);
        if($msgSender != NULL){
            $details = [
                'type' => 'adminMsgReadForSA',
                'avid' => $msgCrtChat->avId,
                'avChatid' => $req->msgId,
                'byId' => 0,
                'forId' => 0,
                'theMsg' => 0
            ];
            $msgSender->notify(new \App\Notifications\newMsgFromQRorpa($details));
        }
    }


















    public function SAASaveNewCard(Request $req){
        // superadminId
        // adminSel
        // reasonOfCard
        $newConvCard = new admMsgSaav();
        $newConvCard->avTittle = $req->reasonOfCard;
        $newConvCard->avById = $req->superadminId;
        $newConvCard->avForId = $req->adminSel;
        $newConvCard->activeState = 1;
        $newConvCard->save();

        $oneSA = User::find($req->adminSel);
        $details = [
            'type' => 'newMsgFromAdminsAddAV',
            'avid' => 0,
            'avChatid' => 0,
            'byId' => 0,
            'forId' => 0,
            'theMsg' => 0
        ];
        $oneSA->notify(new \App\Notifications\newMsgFromQRorpa($details));

        $to_name = $oneSA->name;
        $to_email = str_replace(' ', '', $oneSA->email); ;
        $data = array('name'=>$to_name, "avReason" => $req->reasonOfCard);
        Mail::send('emails.adminChatNewAv', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('QRorpa möchte mit Ihnen chatten');
            $message->from('noreply@qrorpa.ch','Qrorpa');
        });
    }
  



    public function SAASaveNewMessageOnCard(Request $req){
        // superadminId
        // avId
        // theMsg
        $convCard = admMsgSaav::find($req->avId);
        if($convCard != NULL){
            $newMsg = new admMsgSaavchats();
            $newMsg->avId = $req->avId;
            $newMsg->msgById = $req->superadminId;
            $newMsg->msgForId = $convCard->avForId;
            $newMsg->msgContent = $req->theMsg;
            $newMsg->readStatus = 0;
            $newMsg->save();
        }else{
            return 'CardNotFound';
        }

        $UserMsgFor = User::find($convCard->avForId);
        $details = [
            'type' => 'newMsgFromQRorpa',
            'avid' => $req->avId,
            'avChatid' => $newMsg->id,
            'byId' => $req->superadminId,
            'forId' => $convCard->avForId,
            'theMsg' => $req->theMsg
        ];
        $UserMsgFor->notify(new \App\Notifications\newMsgFromQRorpa($details));

        $to_name = $UserMsgFor->name;
        $to_email = str_replace(' ', '', $UserMsgFor->email); ;
        $data = array('name'=>$to_name, "avReason" => $convCard->avTittle);
        Mail::send('emails.adminChatNewMsg', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('QRorpa hat Ihnen geschrieben');
            $message->from('noreply@qrorpa.ch','Qrorpa');
        });

        return $req->theMsg.'||'.$newMsg->created_at;
    }

    public function SAASaveNewMessageOnCard2(Request $req){
        $convCard = admMsgSaav::find($req->avId);

        if($convCard != NULL){
            $newMsg = new admMsgSaavchats();
            $newMsg->avId = $req->avId;
            $newMsg->msgById = $req->superadminId;
            $newMsg->msgForId = $convCard->avById;
            $newMsg->msgContent = $req->theMsg;
            $newMsg->readStatus = 0;
            $newMsg->save();
        }else{
            return 'CardNotFound';
        }

        $UserMsgFor = User::find($convCard->avById);
        $details = [
            'type' => 'newMsgFromQRorpa',
            'avid' => $req->avId,
            'avChatid' => $newMsg->id,
            'byId' => $req->superadminId,
            'forId' => $convCard->avById,
            'theMsg' => $req->theMsg
        ];
        $UserMsgFor->notify(new \App\Notifications\newMsgFromQRorpa($details));

        $to_name = $UserMsgFor->name;
        $to_email = str_replace(' ', '', $UserMsgFor->email); ;
        $data = array('name'=>$to_name, "avReason" => $convCard->avTittle);
        Mail::send('emails.adminChatNewMsg', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('QRorpa hat Ihnen geschrieben');
            $message->from('noreply@qrorpa.ch','Qrorpa');
        });

        return $req->theMsg.'||'.$newMsg->created_at;
    }

    public function SAIReadTheMsg(Request $req){
        
        $msgCrtChat = admMsgSaavchats::find($req->msgId);
        // mark read
        $msgCrtChat->readStatus = 1;
        $msgCrtChat->save();

        $msgSender = User::find($msgCrtChat->msgById);
        if($msgSender != NULL){
            $details = [
                'type' => 'SaMsgReadForAdmin',
                'avid' => $msgCrtChat->avId,
                'avChatid' => $req->msgId,
                'byId' => 0,
                'forId' => 0,
                'theMsg' => 0
            ];
            $msgSender->notify(new \App\Notifications\newMsgFromQRorpa($details));
        }
    }
}
