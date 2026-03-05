<?php

namespace App\Http\Controllers;

use Cart;
use App\User;
use App\ekstra;
use App\TabOrder;
use App\LlojetPro;
use App\Produktet;
use Carbon\Carbon;
use App\admMsgSaav;
use App\notifyClient;
use App\admMsgSaavchats;
use App\tableChngReqsAdmin;
use Illuminate\Http\Request;
use App\cooksProductSelection;
use App\Orders;
use App\taDeForCookOr;
use App\Takeaway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class notificationController extends Controller
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


    public function checkUnrespondet(Request $req){
        if(count(auth()->user()->unreadNotifications) > 0){
            $sendNotify = 'empty';
            $tablesChk = array(); 
            foreach(auth()->user()->unreadNotifications as $oneNoti){
                // Jane te ndara per shkak te fleksibilitetit per zhvillimet e ardhshme 
                if($oneNoti->data['type'] == 'Taborder'){
                    if($sendNotify == 'empty'){ $sendNotify = 'Taborder||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-Taborder||'.$oneNoti->data['tableNr']; }
                    
                }else if($oneNoti->data['type'] == 'Order'){
                    if($sendNotify == 'empty'){ $sendNotify = 'Order||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-Order||'.$oneNoti->data['tableNr']; }

                }else if($oneNoti->data['type'] == 'OrderTakeaway' || $oneNoti->data['type'] == 'OrderDelivery'){
                    if($sendNotify == 'empty'){ $sendNotify = $oneNoti->data['type'];
                    }else{ $sendNotify .= '-8-'.$oneNoti->data['type']; }

                }else if($oneNoti->data['type'] == 'AdminUpdateOrdersP'){
                    if(!in_array($oneNoti->data['tableNr'],$tablesChk)){
                        if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateOrdersP||'.$oneNoti->data['tableNr'];
                        }else{ $sendNotify .= '-8-AdminUpdateOrdersP||'.$oneNoti->data['tableNr'];}
                        array_push($tablesChk,$oneNoti->data['tableNr']);
                    }

                }else if($oneNoti->data['type'] == 'AdminUpdateOrdersPT'){
                    if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateOrdersPT'; 
                    }else{ $sendNotify .= '-8-AdminUpdateOrdersPT'; }

                }else if($oneNoti->data['type'] == 'AdminUpdateWaiterCall'){
                    if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateWaiterCall';  
                    }else{ $sendNotify .= '-8-AdminUpdateWaiterCall'; }

                }else if($oneNoti->data['type'] == 'AdminUpdateTableCh'){
                    if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateTableCh';  
                    }else{ $sendNotify .= '-8-AdminUpdateTableCh'; }

                }else if($oneNoti->data['type'] == 'AdminUpdateTableRez'){
                    if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateTableRez';
                    }else{ $sendNotify .= '-8-AdminUpdateTableRez'; }

                }else if($oneNoti->data['type'] == 'ClientToAdminMessage'){
                    if($sendNotify == 'empty'){ $sendNotify = 'ClientToAdminMessage||'.$oneNoti->data["table"].'||'.$oneNoti->data["msgAdmin"].'||'.$oneNoti->data["msg"].'||'.$oneNoti->data["clPhone"];
                    }else{ $sendNotify .= '-8-ClientToAdminMessage||'.$oneNoti->data["table"].'||'.$oneNoti->data["msgAdmin"].'||'.$oneNoti->data["msg"].'||'.$oneNoti->data["clPhone"]; }
                
                }else if($oneNoti->data['type'] == 'tableChngReqAdminExecuted'){
                    // table chng req From admin executed
                    $tCRAdmin = tableChngReqsAdmin::findOrFail($oneNoti->data['id']);
                    if($sendNotify == 'empty'){ $sendNotify = 'tableChngReqAdminExecuted||'.$tCRAdmin->fromTable.'||'.$tCRAdmin->toTable.'||'.$tCRAdmin->clPhoneNr.'||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-tableChngReqAdminExecuted||'.$tCRAdmin->fromTable.'||'.$tCRAdmin->toTable.'||'.$tCRAdmin->clPhoneNr.'||'.$oneNoti->data['tableNr']; }
                
                }else if($oneNoti->data['type'] == 'newGhostForAdm'){
                    if($sendNotify == 'empty'){ $sendNotify = 'newGhostForAdm||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-newGhostForAdm||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr']; }


                }else if($oneNoti->data['type'] == 'newMsgFromQRorpa'){
                    $msgCrt = admMsgSaav::find($oneNoti->data['avid']);
                    $msgCrtChat = admMsgSaavchats::find($oneNoti->data['avChatid']);
                    
                    if($msgCrt != NULL && $msgCrtChat != NULL){
                        if($sendNotify == 'empty'){ $sendNotify = 'newMsgFromQRorpa||'.$oneNoti->data['avid'].'||'.$oneNoti->data['theMsg'].'||'.$msgCrtChat->created_at.'||'.$oneNoti->data['avChatid'];
                        }else{ $sendNotify .= '-8-newMsgFromQRorpa||'.$oneNoti->data['avid'].'||'.$oneNoti->data['theMsg'].'||'.$msgCrtChat->created_at.'||'.$oneNoti->data['avChatid']; }
                    }

                }else if($oneNoti->data['type'] == 'newMsgFromAdminsAddAV'){
                    if($sendNotify == 'empty'){ $sendNotify = 'newMsgFromAdminsAddAV';
                    }else{ $sendNotify .= '-8-newMsgFromAdminsAddAV'; }

                }else if($oneNoti->data['type'] == 'newMsgFromAdminsDelAV'){
                    if($sendNotify == 'empty'){ $sendNotify = 'newMsgFromAdminsDelAV';
                    }else{ $sendNotify .= '-8-newMsgFromAdminsDelAV'; }
                }else if($oneNoti->data['type'] == 'SaMsgReadForAdmin'){
                    if($sendNotify == 'empty'){ $sendNotify = 'SaMsgReadForAdmin||'.$oneNoti->data['avid'].'||'.$oneNoti->data['theMsg'];
                    }else{ $sendNotify .= '-8-SaMsgReadForAdmin||'.$oneNoti->data['avid'].'||'.$oneNoti->data['theMsg']; }

                }else if($oneNoti->data['type'] == 'productIsReadyRes'){
                    if($sendNotify == 'empty'){ $sendNotify = 'productIsReadyRes||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId'].'||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-productIsReadyRes||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId'].'||'.$oneNoti->data['tableNr']; }

                }else if($oneNoti->data['type'] == 'productIsReadyTa'){
                    if($sendNotify == 'empty'){ $sendNotify = 'productIsReadyTa||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId'];
                    }else{ $sendNotify .= '-8-productIsReadyTa||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId']; }

                }else if($oneNoti->data['type'] == 'productIsReadyDe'){
                    if($sendNotify == 'empty'){ $sendNotify = 'productIsReadyDe||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId'];
                    }else{ $sendNotify .= '-8-productIsReadyDe||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId']; }

                }else if($oneNoti->data['type'] == 'clPayUnconfirmedAlert'){
                    if($sendNotify == 'empty'){ $sendNotify = 'clPayUnconfirmedAlert||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-clPayUnconfirmedAlert||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr']; }

                }else if($oneNoti->data['type'] == 'OpenCloseRestaurant'){
                    if($sendNotify == 'empty'){ $sendNotify = 'OpenCloseRestaurant||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-OpenCloseRestaurant||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr']; }
                }
            }
            auth()->user()->unreadNotifications->markAsRead();
            return $sendNotify;
        }else{
            return 'empty';
        }
    }
    public function markRespondRead(Request $req){
        auth()->user()->unreadNotifications->markAsRead();
        return 'success';
    }











    public function checkUnrespondetWaiter(Request $req){
        if(count(auth()->user()->unreadNotifications) > 0){
            $sendNotify = 'empty';
            $tablesChk = array(); 
            foreach(auth()->user()->unreadNotifications as $oneNoti){
                // Jane te ndara per shkak te fleksibilitetit per zhvillimet e ardhshme 
                if($oneNoti->data['type'] == 'Taborder'){
                    if($sendNotify == 'empty'){ $sendNotify = 'Taborder||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-Taborder||'.$oneNoti->data['tableNr']; }
                    
                }else if($oneNoti->data['type'] == 'Order'){
                    if($sendNotify == 'empty'){ $sendNotify = 'Order||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-Order||'.$oneNoti->data['tableNr']; }

                }else if($oneNoti->data['type'] == 'OrderTakeaway' || $oneNoti->data['type'] == 'OrderDelivery'){
                    if($sendNotify == 'empty'){ $sendNotify = $oneNoti->data['type'];
                    }else{ $sendNotify .= '-8-'.$oneNoti->data['type']; }

                }else if($oneNoti->data['type'] == 'AdminUpdateOrdersP'){
                    if(!in_array($oneNoti->data['tableNr'],$tablesChk)){
                        if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateOrdersP||'.$oneNoti->data['tableNr'];
                        }else{ $sendNotify .= '-8-AdminUpdateOrdersP||'.$oneNoti->data['tableNr'];}
                        array_push($tablesChk,$oneNoti->data['tableNr']);
                    }

                }else if($oneNoti->data['type'] == 'AdminUpdateOrdersPT'){
                    if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateOrdersPT'; 
                    }else{ $sendNotify .= '-8-AdminUpdateOrdersPT'; }

                }else if($oneNoti->data['type'] == 'AdminUpdateWaiterCall'){
                    if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateWaiterCall';  
                    }else{ $sendNotify .= '-8-AdminUpdateWaiterCall'; }

                }else if($oneNoti->data['type'] == 'AdminUpdateTableCh'){
                    if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateTableCh';  
                    }else{ $sendNotify .= '-8-AdminUpdateTableCh'; }

                }else if($oneNoti->data['type'] == 'AdminUpdateTableRez'){
                    if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateTableRez';
                    }else{ $sendNotify .= '-8-AdminUpdateTableRez'; }

                }else if($oneNoti->data['type'] == 'ClientToAdminMessage'){
                    if($sendNotify == 'empty'){ $sendNotify = 'ClientToAdminMessage||'.$oneNoti->data["table"].'||'.$oneNoti->data["msgAdmin"].'||'.$oneNoti->data["msg"].'||'.$oneNoti->data["clPhone"];
                    }else{ $sendNotify .= '-8-ClientToAdminMessage||'.$oneNoti->data["table"].'||'.$oneNoti->data["msgAdmin"].'||'.$oneNoti->data["msg"].'||'.$oneNoti->data["clPhone"]; }
                
                }else if($oneNoti->data['type'] == 'tableChngReqAdminExecuted'){
                    // table chng req From admin executed
                    $tCRAdmin = tableChngReqsAdmin::findOrFail($oneNoti->data['id']);
                    if($sendNotify == 'empty'){ $sendNotify = 'tableChngReqAdminExecuted||'.$tCRAdmin->fromTable.'||'.$tCRAdmin->toTable.'||'.$tCRAdmin->clPhoneNr.'||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-tableChngReqAdminExecuted||'.$tCRAdmin->fromTable.'||'.$tCRAdmin->toTable.'||'.$tCRAdmin->clPhoneNr.'||'.$oneNoti->data['tableNr']; }
                
                }else if($oneNoti->data['type'] == 'newGhostForAdm'){
                    if($sendNotify == 'empty'){ $sendNotify = 'newGhostForAdm||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-newGhostForAdm||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr']; }


                }else if($oneNoti->data['type'] == 'newMsgFromQRorpa'){
                    $msgCrt = admMsgSaav::find($oneNoti->data['avid']);
                    $msgCrtChat = admMsgSaavchats::find($oneNoti->data['avChatid']);
                    
                    if($msgCrt != NULL && $msgCrtChat != NULL){
                        if($sendNotify == 'empty'){ $sendNotify = 'newMsgFromQRorpa||'.$oneNoti->data['avid'].'||'.$oneNoti->data['theMsg'].'||'.$msgCrtChat->created_at.'||'.$oneNoti->data['avChatid'];
                        }else{ $sendNotify .= '-8-newMsgFromQRorpa||'.$oneNoti->data['avid'].'||'.$oneNoti->data['theMsg'].'||'.$msgCrtChat->created_at.'||'.$oneNoti->data['avChatid']; }
                    }

                }else if($oneNoti->data['type'] == 'newMsgFromAdminsAddAV'){
                    if($sendNotify == 'empty'){ $sendNotify = 'newMsgFromAdminsAddAV';
                    }else{ $sendNotify .= '-8-newMsgFromAdminsAddAV'; }

                }else if($oneNoti->data['type'] == 'newMsgFromAdminsDelAV'){
                    if($sendNotify == 'empty'){ $sendNotify = 'newMsgFromAdminsDelAV';
                    }else{ $sendNotify .= '-8-newMsgFromAdminsDelAV'; }
                }else if($oneNoti->data['type'] == 'SaMsgReadForAdmin'){
                    if($sendNotify == 'empty'){ $sendNotify = 'SaMsgReadForAdmin||'.$oneNoti->data['avid'].'||'.$oneNoti->data['theMsg'];
                    }else{ $sendNotify .= '-8-SaMsgReadForAdmin||'.$oneNoti->data['avid'].'||'.$oneNoti->data['theMsg']; }
                    
                }else if($oneNoti->data['type'] == 'productIsReadyRes'){
                    if($sendNotify == 'empty'){ $sendNotify = 'productIsReadyRes||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId'].'||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-productIsReadyRes||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId'].'||'.$oneNoti->data['tableNr']; }

                }else if($oneNoti->data['type'] == 'productIsReadyTa'){
                    if($sendNotify == 'empty'){ $sendNotify = 'productIsReadyTa||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId'];
                    }else{ $sendNotify .= '-8-productIsReadyTa||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId']; }

                }else if($oneNoti->data['type'] == 'productIsReadyDe'){
                    if($sendNotify == 'empty'){ $sendNotify = 'productIsReadyDe||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId'];
                    }else{ $sendNotify .= '-8-productIsReadyDe||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId']; }

                }else if($oneNoti->data['type'] == 'clPayUnconfirmedAlert'){
                    if($sendNotify == 'empty'){ $sendNotify = 'clPayUnconfirmedAlert||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-clPayUnconfirmedAlert||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr']; }

                }else if($oneNoti->data['type'] == 'OpenCloseRestaurant'){
                    if($sendNotify == 'empty'){ $sendNotify = 'OpenCloseRestaurant||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];
                    }else{ $sendNotify .= '-8-OpenCloseRestaurant||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr']; }
                }
            }
            auth()->user()->unreadNotifications->markAsRead();
            return $sendNotify;
        }else{
            return 'empty';
        }
    }
    public function markRespondReadWaiter(Request $req){
        auth()->user()->unreadNotifications->markAsRead();
        return 'success';
    }






    public function checkUnrespondetSA(Request $req){
        if(count(auth()->user()->unreadNotifications) > 0){
            $sendNotify = 'empty';
            foreach(auth()->user()->unreadNotifications as $oneNoti){
                if($oneNoti->data['type'] == 'newMsgFromAdmins'){

                    $msgCrt = admMsgSaav::find($oneNoti->data['avid']);
                    $msgCrtChat = admMsgSaavchats::find($oneNoti->data['avChatid']);
         
                    if($msgCrt != NULL && $msgCrtChat != NULL){
                        if($sendNotify == 'empty'){ $sendNotify = 'newMsgFromAdmins||'.$oneNoti->data['avid'].'||'.$oneNoti->data['theMsg'].'||'.$msgCrtChat->created_at.'||'.$oneNoti->data['avChatid'];
                        }else{ $sendNotify .= '-8-newMsgFromAdmins||'.$oneNoti->data['avid'].'||'.$oneNoti->data['theMsg'].'||'.$msgCrtChat->created_at.'||'.$oneNoti->data['avChatid']; }
                    }

                }else if($oneNoti->data['type'] == 'newMsgFromAdminsAddAV'){
                    if($sendNotify == 'empty'){ $sendNotify = 'newMsgFromAdminsAddAV';
                    }else{ $sendNotify .= '-8-newMsgFromAdminsAddAV'; }

                }else if($oneNoti->data['type'] == 'newMsgFromAdminsDelAV'){
                    if($sendNotify == 'empty'){ $sendNotify = 'newMsgFromAdminsDelAV';
                    }else{ $sendNotify .= '-8-newMsgFromAdminsDelAV'; }

                }else if($oneNoti->data['type'] == 'adminMsgReadForSA'){
                    if($sendNotify == 'empty'){ $sendNotify = 'adminMsgReadForSA||'.$oneNoti->data['avid'].'||'.$oneNoti->data['avChatid'];
                    }else{ $sendNotify .= '-8-adminMsgReadForSA||'.$oneNoti->data['avid'].'||'.$oneNoti->data['avChatid']; }
                }
            }
            auth()->user()->unreadNotifications->markAsRead();
            return $sendNotify;
        }else{
            return 'empty';
        }
    }








    public function checkUnrespondetCook(Request $req){
        if(count(auth()->user()->unreadNotifications) > 0){
            $sendNotify = 'empty';
            foreach(auth()->user()->unreadNotifications as $oneNoti){

                $tabOrder = TabOrder::find($oneNoti->data['id']);

                if($oneNoti->data['type'] == 'AdminUpdateOrdersP'){

                    $nrOfTOLeft = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',$oneNoti->data['tableNr']]])->count();
                    if($nrOfTOLeft == 0){$nrOfTOLeftRespo = 'removeIt';
                    }else{ $nrOfTOLeftRespo = 'activeTable'; }

                    $nrOfTOAbgerufenLeft = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['abrufenStat','1']])->count();
                    if($nrOfTOAbgerufenLeft == 0){$nrOfTOAbgerufenLeftRespo = 'removeIt';
                    }else{ $nrOfTOAbgerufenLeftRespo = 'activeAbgerufen'; }

                    if($oneNoti->data['id'] == 0){
                        if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateOrdersPForPayed'.'||'.$oneNoti->data['tableNr'].'||0||'.$nrOfTOLeftRespo.'||'.$nrOfTOAbgerufenLeftRespo; 
                        }else{ $sendNotify .= '-8-AdminUpdateOrdersPForPayed'.'||'.$oneNoti->data['tableNr'].'||0||'.$nrOfTOLeftRespo.'||'.$nrOfTOAbgerufenLeftRespo; }
                    }else{
                        $tOrder = TabOrder::find($oneNoti->data['id']);
                        if($tOrder != NULL){
                            $tOrProd = Produktet::find($tOrder->prodId);
                            if($tOrProd != NULL){
                                if( $this->checkCookAccess01($tOrder,$tOrProd) ){
                                    if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateOrdersP||'.$tOrProd->id.'||'.$tOrProd->kategoria.'||'.$oneNoti->data['tableNr'].'||'.$nrOfTOLeftRespo.'||'.$nrOfTOAbgerufenLeftRespo; 
                                    }else{ $sendNotify .= '-8-AdminUpdateOrdersP||'.$tOrProd->id.'||'.$tOrProd->kategoria.'||'.$oneNoti->data['tableNr'].'||'.$nrOfTOLeftRespo.'||'.$nrOfTOAbgerufenLeftRespo; }
                                }
                            }
                        }else{
                            if($sendNotify == 'empty'){ $sendNotify = 'AdminUpdateOrdersPForPayed'.'||'.$oneNoti->data['tableNr'].'||'.$oneNoti->data['id'].'||'.$nrOfTOLeftRespo.'||'.$nrOfTOAbgerufenLeftRespo; 
                            }else{ $sendNotify .= '-8-AdminUpdateOrdersPForPayed'.'||'.$oneNoti->data['tableNr'].'||'.$oneNoti->data['id'].'||'.$nrOfTOLeftRespo.'||'.$nrOfTOAbgerufenLeftRespo; }
                        }
                    }
                }else if($oneNoti->data['type'] == 'Order'){
                    if($sendNotify == 'empty'){ $sendNotify = 'Order||'.$oneNoti->data['id']; 
                    }else{ $sendNotify .= '-8-Order||'.$oneNoti->data['id']; }

                }else if($oneNoti->data['type'] == 'cookPanelUpdate'){
                    $tOrProd = Produktet::find($oneNoti->data['prodId']);
                    $tOrder = TabOrder::find($oneNoti->data['id']);
                    if( $this->checkCookAccess01($tOrder,$tOrProd) ){
                        if($sendNotify == 'empty'){ $sendNotify = 'cookPanelUpdate||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId'].'||'.$tOrProd->kategoria.'||'.$tOrder->tableNr; 
                        }else{ $sendNotify .= '-8-cookPanelUpdate||'.$oneNoti->data['id'].'||'.$oneNoti->data['prodId'].'||'.$tOrProd->kategoria.'||'.$tOrder->tableNr; }
                    }

                }else if($oneNoti->data['type'] == 'cookPanelUpdateTaNewOr'){
                    if( $this->checkCookAccess02($oneNoti->data['id']) ){
                        if($sendNotify == 'empty'){ $sendNotify = 'cookPanelUpdateTaNewOr||'.$oneNoti->data['id']; 
                        }else{ $sendNotify .= '-8-cookPanelUpdateTaNewOr||'.$oneNoti->data['id']; }
                    }
                    foreach(taDeForCookOr::where([['toRes',Auth::user()->sFor],['serviceType','1']])->get() as $oneCookOr){
                        $thiOrder = Orders::find($oneCookOr->orderId);
                        if($thiOrder != NULL && ($thiOrder->statusi == 2 || $thiOrder->statusi == 3)){
                            $oneCookOr->delete();
                        }
                    }
                }else if($oneNoti->data['type'] == 'cookPanelUpdateTaCookUpdate'){
                    if( $this->checkCookAccess02($oneNoti->data['id']) ){
                        if($sendNotify == 'empty'){ $sendNotify = 'cookPanelUpdateTaCookUpdate||'.$oneNoti->data['id']; 
                        }else{ $sendNotify .= '-8-cookPanelUpdateTaCookUpdate||'.$oneNoti->data['id']; }
                    }
                    foreach(taDeForCookOr::where([['toRes',Auth::user()->sFor],['serviceType','1']])->get() as $oneCookOr){
                        $thiOrder = Orders::find($oneCookOr->orderId);
                        if($thiOrder != NULL && ($thiOrder->statusi == 2 || $thiOrder->statusi == 3)){
                            $oneCookOr->delete();
                        }
                    }
                }else if($oneNoti->data['type'] == 'cookPanelUpdateTaToPay'){
                    if( $this->checkCookAccess02($oneNoti->data['id']) ){
                        if($sendNotify == 'empty'){ $sendNotify = 'cookPanelUpdateTaToPay||'.$oneNoti->data['id']; 
                        }else{ $sendNotify .= '-8-cookPanelUpdateTaToPay||'.$oneNoti->data['id']; }
                    }
                    foreach(taDeForCookOr::where([['toRes',Auth::user()->sFor],['serviceType','1']])->get() as $oneCookOr){
                        $thiOrder = Orders::find($oneCookOr->orderId);
                        if($thiOrder != NULL && ($thiOrder->statusi == 2 || $thiOrder->statusi == 3)){
                            $oneCookOr->delete();
                        }
                    }

                }else if($oneNoti->data['type'] == 'cookPanelUpdateDe'){
                    if($sendNotify == 'empty'){ $sendNotify = 'cookPanelUpdateDe||'.$oneNoti->data['id']; 
                    }else{ $sendNotify .= '-8-cookPanelUpdateDe||'.$oneNoti->data['id']; }

                }else if($oneNoti->data['type'] == 'OrderServedToTable'){
                    if($sendNotify == 'empty'){ $sendNotify = 'OrderServedToTable||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr']; 
                    }else{ $sendNotify .= '-8-OrderServedToTable||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];  }

                }
            }

            auth()->user()->unreadNotifications->markAsRead();
            return $sendNotify;
        }else{
            return 'empty';
        }
    }

    private function checkCookAccess01($tabO, $prod){
        $catCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Category'],['contentId',$prod->kategoria]])->first();
        if($catCookAcs != NULL){
            return True;
        }else{
            $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Product'],['contentId',$prod->id]])->first();
            if($prodCookAcs != NULL){
                return True;
            }
            if($tabO->OrderExtra != 'empty'){
                foreach(explode('--0--',$tabO->OrderExtra) as $orExtraOne){
                    $orExtraOne2D = explode('||',$orExtraOne);
                    $oneExtraref01ID = ekstra::where([['toCat',$prod->kategoria],['emri',$orExtraOne2D[0]],['qmimi',$orExtraOne2D[1]]])->first();
                    if($oneExtraref01ID != NULL){
                        $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first();
                        if($prodCookAcs != NULL){ return True; }
                    }
                }
            }
            if($tabO->OrderType != 'empty'){
                $orType2D = explode('||',$tabO->OrderType);
                $oneTyperef01ID = LlojetPro::where([['kategoria',$prod->kategoria],['emri',$orType2D[0]],['vlera',$orType2D[1]]])->first();
                $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first();
                if($prodCookAcs != NULL){ return True; }
            }
            return False; 
        }
    }

    private function checkCookAccess02($orId){
        $theOr = Orders::find($orId);
        if($theOr != Null){
            foreach(explode('---8---',$theOr->porosia) as $orOne){
                $orOne2D = explode('-8-',$orOne);
                if($theOr->userEmri == 'admin'){ $thePro = Produktet::find($orOne2D[7]); $prodId = $thePro->id;
                }else{ $thePro = Takeaway::find($orOne2D[7]); $prodId = $thePro->prod_id; }
                if($thePro != NULL){
                    if(cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Category'],['contentId',$thePro->kategoria]])->first() != Null){
                        return True;
                    }else if(cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Product'],['contentId',$prodId]])->first() != Null){
                        return True;
                    }else{
                        if($orOne2D[2] != 'empty' && $orOne2D[2] != ''){
                            foreach(explode('--0--',$orOne2D[2]) as $orExtraOne){
                                $orExtraOne2D = explode('||',$orExtraOne);
                                $oneExtraref01ID = ekstra::where([['toCat',$thePro->kategoria],['emri',$orExtraOne2D[0]],['qmimi',$orExtraOne2D[1]]])->first();
                                if($oneExtraref01ID != NULL){
                                    if(cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){ return True; }
                                }
                            }
                        }
                        if($orOne2D[5] != 'empty' && $orOne2D[5] != ''){
                            $orType2D = explode('||',$orOne2D[5]);
                            $oneTyperef01ID = LlojetPro::where([['kategoria',$thePro->kategoria],['emri',$orType2D[0]],['vlera',$orType2D[1]]])->first();
                            if(cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){ return True; }
                        }
                    }  
                }
            }
            return False;
        }else{
            return False;
        }
    }


















    



    public function checkUnrespondetClient(Request $req){
        // resId: thisRestaurant,
        // tableNr: thisTable,
        // phoneNrVerify:
            // varNrCl: verNrCl,
        // _token: '{{csrf_token()}}'
        if(str_contains(strval($req->phoneNrVerify), '|')){
            $unResNotifyClGhost = notifyClient::where([['toRes',$req->resId],['tableNr',$req->tableNr],['for','ghostCartRefresh'],['created_at', '>', Carbon::now()->subSeconds(5)->toDateTimeString()]])->whereNull('read')->get();
            foreach(notifyClient::where([['toRes',$req->resId],['tableNr',$req->tableNr],['for','ghostCartRefresh'],['created_at', '<', Carbon::now()->subSeconds(5)->toDateTimeString()]])->whereNull('read')->get() as $outDatedNoti){
                $outDatedNoti->read = date('Y-m-d H:i:s');
                $outDatedNoti->readInd = 1;
                $outDatedNoti->save();
            }
            if(count($unResNotifyClGhost) > 0){
                return 'reloadGhost';
                // return $_SERVER['REMOTE_ADDR'];  
            }
        }
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------------ 
        //----addToCartAdmin-------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
        $unResNotifyClAddToCartAdmin = notifyClient::where([['toRes',$req->resId],['tableNr',$req->tableNr],['clPhoneNr',$req->phoneNrVerify],['for','addToCartAdmin']])->whereNull('read')->first();
        if($unResNotifyClAddToCartAdmin != NULL){

            $notiData = json_decode($unResNotifyClAddToCartAdmin->data, true);
            $ret = 'addToCartAdmin||'.$req->resId.'||'.$req->tableNr.'||'.$notiData["phoneNrOrderFor"].'||'.$notiData["newOrId"].'||'.$notiData["phoneNrNotifyFor"];

            $unResNotifyClAddToCartAdmin->read = date('Y-m-d H:i:s');
            $unResNotifyClAddToCartAdmin->readInd = 1;
            $unResNotifyClAddToCartAdmin->save();
            
            return $ret;
        }
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------------ 
        //-----CartMsg------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
        $unResNotifyClCartMsg= notifyClient::where([['toRes',$req->resId],['tableNr',$req->tableNr],['clPhoneNr',$req->phoneNrVerify],['for','CartMsg']])->whereNull('read')->first();
        if($unResNotifyClCartMsg != NULL){

            $notiData = json_decode($unResNotifyClCartMsg->data, true);

            $ret = 'CartMsg||'.$req->resId.'||'.$req->tableNr.'||'.$notiData["toDoCart"].'||'.$notiData["payAllOrMineSelected"];

            $unResNotifyClCartMsg->read = date('Y-m-d H:i:s');
            $unResNotifyClCartMsg->readInd = 1;
            $unResNotifyClCartMsg->save();

            return $ret;
        }
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------------         
        //-----prodStatChange----------------------------------------------------------------------------------------------------------------------------------------------------- 
        $unResNotifyClCartMsg= notifyClient::where([['toRes',$req->resId],['tableNr',$req->tableNr],['clPhoneNr',$req->phoneNrVerify],['for','prodStatChange']])->whereNull('read')->first();
        if($unResNotifyClCartMsg != NULL){
            $notiData = json_decode($unResNotifyClCartMsg->data, true);

            $ret = 'prodStatChange||'.$req->resId.'||'.$req->tableNr.'||'.$notiData["phoneNrNotifyFor"].'||'.$notiData["tabOrId"];

            $unResNotifyClCartMsg->read = date('Y-m-d H:i:s');
            $unResNotifyClCartMsg->readInd = 1;
            $unResNotifyClCartMsg->save();

            return $ret;
        }
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------------ 
        //-----taOrdStatusChange----------------------------------------------------------------------------------------------------------------------------------------------------- 
        if(Cookie::has('trackMO')){
            $trackMO = Cookie::get('trackMO');
        }else{ $trackMO = 'NONE'; }
        if(Cookie::has('trackMO')){
            $unResNotifyClCartMsg= notifyClient::where([['toRes',$req->resId],['tableNr','500'],['clPhoneNr',$trackMO],['for','taOrdStatusChange']])->whereNull('read')->first();
            if($unResNotifyClCartMsg != NULL){
                $notiData = json_decode($unResNotifyClCartMsg->data, true);
                $ret = 'taOrdStatusChange||'.$req->resId.'||500||'.$notiData["trackMO"].'||'.$notiData["newStat"];

                $unResNotifyClCartMsg->read = date('Y-m-d H:i:s');
                $unResNotifyClCartMsg->readInd = 1;
                $unResNotifyClCartMsg->save();

                return $ret;
            }
        }
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------------ 
        //-----removePaidProduct-------------------------------------------------------------------------------------------------------------------------------------------------- 
        $unResNotifyClCartMsg= notifyClient::where([['toRes',$req->resId],['tableNr',$req->tableNr],['clPhoneNr',$req->phoneNrVerify],['for','removePaidProduct']])->whereNull('read')->first();
        if($unResNotifyClCartMsg != NULL){
        
            $notiData = json_decode($unResNotifyClCartMsg->data, true);
        
            // $ret = 'removePaidProduct||'.$req->resId.'||'.$req->tableNr.'||'.$notiData["tabOrId"].'||'.$notiData["type"];

            if($notiData["type"] == 'a'){
                foreach(explode('--9--',$notiData["tabOrId"]) as $oneTOId){
                    // tabOId: d2d[1],
                    foreach(Cart::content() as $item){
                        if($item->options->tabOId == $oneTOId){
                            Cart::remove($item->rowId);
                        }
                    }
                }
                $ret = 'removePaidProduct||payedAll||'.$notiData["orderId"];
            }
        
            $unResNotifyClCartMsg->read = date('Y-m-d H:i:s');
            $unResNotifyClCartMsg->readInd = 1;
            $unResNotifyClCartMsg->save();

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if(!Auth::check()){
                unset($_SESSION['phoneNrVerified']);
            }

            Cookie::queue('retSessionCK', 'not', 180);
        
            return $ret;
        }
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------------         
        //-----CartMsg------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
        $unResNotifyCl = notifyClient::where([['toRes',$req->resId],['tableNr',$req->tableNr]])->whereNull('read')->count();
        if($unResNotifyCl > 0){
            $ret = '';
            
            foreach(notifyClient::where([['toRes',$req->resId],['tableNr',$req->tableNr]])->whereNull('read')->get() as $noti){
                if($noti->for == 'clientRequestChangeTable'){ 
                    $notiData = json_decode($noti->data, true);
                    
                    if($ret == ''){
                        if($notiData['answare'] == 'userSuccess' || $notiData['answare'] == 'userError'){
                            $ret = 'clNewTab||'.$notiData['answare'].'||'.$notiData['newTable'].'||'.$notiData['comment'];
                             // Mark as Read
                            $noti->read = date('Y-m-d H:i:s');
                            $noti->readInd = 1;
                            $noti->save();
                        }else  if($notiData['answare'] == 'userMsg'){
                            if($req->phoneNrVerify == $notiData['clSelected']){
                                $ret = 'clNewTab||'.$notiData['answare'].'||'.$notiData['comment'].'||'.$notiData['adminId'].'||'.$notiData['clSelected'];
                                // Mark as Read
                                $noti->read = date('Y-m-d H:i:s');
                                $noti->readInd = 1;
                                $noti->save();
                            }
                        }
                    }else{
                        if($notiData['answare'] == 'userSuccess' || $notiData['answare'] == 'userError'){
                            $ret .= '--8--clNewTab||'.$notiData['answare'].'||'.$notiData['newTable'].'||'.$notiData['comment'];
                            // Mark as Read
                            $noti->read = date('Y-m-d H:i:s');
                            $noti->readInd = 1;
                            $noti->save();
                        }else  if($notiData['answare'] == 'userMsg'){
                            if($req->phoneNrVerify == $notiData['clSelected']){
                                $ret .= '--8--clNewTab||'.$notiData['answare'].'||'.$notiData['comment'].'||'.$notiData['adminId'].'||'.$notiData['clSelected'];
                                // Mark as Read
                                $noti->read = date('Y-m-d H:i:s');
                                $noti->readInd = 1;
                                $noti->save();
                            }
                        }
                    }
                   

                    return $ret;
                }else if($noti->for == 'takeawayOrderFinished'){
                    $notiData = json_decode($noti->data, true);
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    if(Cookie::has('trackMO') && Cookie::get('trackMO') == $notiData['shifra']){
                        $ret = 'taOrderFinish||'.$notiData['tableNr'].'||'.$notiData['shifra'].'||'.$notiData['newStat'];
                        // unset($_SESSION['trackMO']);
                        $cookieDel = Cookie::forget('trackMO');

                        // Mark as Read
                        $noti->read = date('Y-m-d H:i:s');
                        $noti->readInd = 1;
                        $noti->save();

                        return redirect('/?Res='.$noti->toRes.'&t='.$noti->tableNr)->withCookie(cookie('trackMO', $notiData['shifra'], 30));
                    }
                }
            }
            return 'none';
        }else{
            return 'none';
        }
     
    }


    public function checkUnrespondetClientCart(Request $req){
        $unResNotifyCl = notifyClient::where([['toRes',$req->resId],['tableNr',$req->tableNr],['for','ghostCartRefresh'],['created_at', '>', Carbon::now()->subSeconds(5)->toDateTimeString()]])->whereNull('read')->get();
        
        foreach(notifyClient::where([['toRes',$req->resId],['tableNr',$req->tableNr],['for','ghostCartRefresh'],['created_at', '<', Carbon::now()->subSeconds(5)->toDateTimeString()]])->whereNull('read')->get() as $outDatedNoti){
            $outDatedNoti->read = date('Y-m-d H:i:s');
            $outDatedNoti->readInd = 1;
            $outDatedNoti->save();
        }
        if(count($unResNotifyCl) > 0){
            return 'reloadGhost';
            // return $_SERVER['REMOTE_ADDR'];  
        }else{
            return 'none'; 
        }
    }





    public function clAcceptsCookie(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }    
        $_SESSION['cookiesAcpt'] = 'Yes';

        return redirect()->back()->withCookie(cookie('cookiesAcpt', 'Yes' , 10080));
    }

}
