<?php

namespace App\Http\Controllers;

use App\User;
use App\ekstra;
use App\kategori;
use App\TabOrder;
use App\Takeaway;
use App\cookColor;
use App\LlojetPro;
use App\Produktet;
use App\resPlates;
use App\DeliveryProd;
use App\notifyClient;
use App\taDeForCookOr;
use App\billsRecordRes;
use App\logCookActivity;
use App\newOrdersAdminAlert;
use App\orderServingDevices;
use Illuminate\Http\Request;
use App\cooksProductSelection;
use App\orderServingOrderShow;
use App\tablesAccessToWaiters;
use App\accessControllForAdmins;
use App\tabVerificationPNumbers;
use App\orderServingNotification;
use App\orderServingDevicesAccess;
use App\TableQrcode;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Helper\Table;

class adminMngWorkersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmMngPage(){
        return view('adminPanel/adminIndex');
    }

    public function indexAdmMngOpenWaiterS(){ return view('adminPanel/adminIndex'); }
    public function indexAdmMngOpenWaiterST(){ return view('adminPanel/adminIndex'); }
    public function indexAdmMngOpenWaiterSD(){ return view('adminPanel/adminIndex'); }

    public function indexAdmMngPageWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function ordersListAdmMngPageWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function ordersFreeTablesAdmMngPageWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function notificationsActPageWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function ordersStatisticsWaiter01(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function ordersStatisticsWaiter02(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function ordersStatisticsWaiter03(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function ordersStatisticsWaiter04(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function ordersCanceledOrdersWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function WaiterPanelStatBillsPage(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function categorizeReportWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    
    public function ordersTakeawayWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function ordersDeliveryWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminSaMsgWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoRecomendetProdWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function adminWoRechnungPageWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    
    public function adminWoWaiterCallsWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoContentMngWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function adminWoContentMngOrderWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoTableChngReqWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoTipsWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function adminWoTipsMonthWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoFreeProductsWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoRestrictProductsWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoCouponsWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoTakeawayWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function adminWoDeliveryWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoTakeawaySortingWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function adminWoDeliverySortingWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    
    public function adminWoTableCapacityWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoTableReservationIndexWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }
    public function adminWoTableReservationListWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoServiceRequestWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoStatusWorkerWaiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function adminWoCovid19Waiter(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function billTabletIndex(){return view('adminPanelWaiter/adminIndexWaiter');}

    public function orderServingDevicesPageWaiter(){return view('adminPanelWaiter/adminIndexWaiter');}

    public function cookPanelIndexCook(){ return view('adminPanelCook/adminIndexCook'); }
    public function cookPanelIndexCookNotConf(){ return view('adminPanelCook/adminIndexCook'); }
    public function cookPanelIndexCookT(){ return view('adminPanelCook/adminIndexCook'); }
    public function cookPanelIndexCookTNotConf(){ return view('adminPanelCook/adminIndexCook'); }
    public function cookPanelIndexCookD(){ return view('adminPanelCook/adminIndexCook'); }

    

    public function AccountPanelStatistika(){ return view('adminPanelAccountant/adminIndexAccountant'); }
    public function AccountPanelStatistikaDash1(){ return view('adminPanelAccountant/adminIndexAccountant'); }
    public function AccountPanelStatistikaDash2(){ return view('adminPanelAccountant/adminIndexAccountant'); }
    public function AccountPanelStatistikaSales(){ return view('adminPanelAccountant/adminIndexAccountant'); }
    public function AccountPanelStatistikaRepCat(){ return view('adminPanelAccountant/adminIndexAccountant'); }

    public function AccountPanelCanceledOrders(){ return view('adminPanelAccountant/adminIndexAccountant'); }

    public function AccountPanelStatBillsPage(){ return view('adminPanelAccountant/adminIndexAccountant'); }

    public function AccountPanelDeletedIns(){ return view('adminPanelAccountant/adminIndexAccountant'); }
    public function AccountPanelWaitersSales(){ return view('adminPanelAccountant/adminIndexAccountant'); }
    public function AccountPanelEmailBillsP(){ return view('adminPanelAccountant/adminIndexAccountant'); }
    public function AccountPanelReportCatsPage(){ return view('adminPanelAccountant/adminIndexAccountant'); }
    
    
    public function AccountPanelProduktet(){ return view('adminPanelAccountant/adminIndexAccountant'); }
    public function AccountPanelProduktetOrder(){ return view('adminPanelAccountant/adminIndexAccountant'); }

   
    
    public function cookPanelAddOneDoneOrderProd(Request $req){
        $to = TabOrder::find($req->tabOrderId);

        $to->OrderSasiaDone =  $to->OrderSasiaDone + 1;
        $to->save();

        if($to->OrderSasia == $to->OrderSasiaDone){
            // admins
            foreach(User::where([['sFor',$to->toRes],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $to->id,
                        'type' => 'productIsReadyRes',
                        'prodId' => $to->prodId,
                        'tableNr' => $to->tableNr
                    ];
                    $user->notify(new \App\Notifications\productIsReadyRes($details));

                    if(newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$to->tableNr],['tabOrderId',$to->id],['statActive','1']])->first() == NULL){
                        $newAdmAlert = new newOrdersAdminAlert();
                        $newAdmAlert->adminId = $user->id;
                        $newAdmAlert->tableNr = $to->tableNr;
                        $newAdmAlert->toRes = $to->toRes;
                        $newAdmAlert->tabOrderId = $to->id;
                        $newAdmAlert->statActive = 1;
                        $newAdmAlert->save();
                    }
                }
            }
            // waiters
            foreach(User::where([['sFor',$to->toRes],['role','55']])->get() as $oneWaiter){
                if($oneWaiter->id != auth()->user()->id){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $to->tableNr]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $to->id,
                            'type' => 'productIsReadyRes',
                            'prodId' => $to->prodId,
                            'tableNr' => $to->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\productIsReadyRes($details));

                        if(newOrdersAdminAlert::where([['adminId',$oneWaiter->id],['tableNr',$to->tableNr],['tabOrderId',$to->id],['statActive','1']])->first() == NULL){
                            $newAdmAlert = new newOrdersAdminAlert();
                            $newAdmAlert->adminId = $oneWaiter->id;
                            $newAdmAlert->tableNr = $to->tableNr;
                            $newAdmAlert->toRes = $to->toRes;
                            $newAdmAlert->tabOrderId = $to->id;
                            $newAdmAlert->statActive = 1;
                            $newAdmAlert->save();
                        }
                    }
                }
            }
        }

        $theP = Produktet::find($to->prodId);
        if($theP != NULL){
            foreach(User::where([['sFor',$to->toRes],['role','54']])->get() as $oneCook){
                if($oneCook->id != Auth::User()->id){
                    $prodAcs = 0;
                    $extAcs = 0;
                    $typAcs = 0;

                    $catAcs = $this->checkCategoryAcs($theP->kategoria,$oneCook->id);
                    if($catAcs == 0){
                        $prodAcs = $this->checkProductAcs($theP->id,$oneCook->id);
                        if($catAcs == 0){
                            if($to->OrderExtra != 'empty'){
                            $extAcs = $this->checkExtraAcs($to->OrderExtra,$theP->kategoria,$oneCook->id);
                            }
                            if($extAcs == 0){
                                if($to->OrderType != 'empty'){
                                    $typAcs = $this->checkTypeAcs($to->OrderType,$theP->kategoria,$oneCook->id);
                                }
                            }
                        }
                    }
                    if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                        $details = [
                            'id' => $to->id,
                            'type' => 'cookPanelUpdate',
                            'prodId' => $to->prodId
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
        }

        // Njoftohet klienti 
            $clToNotify=array();
            foreach(tabVerificationPNumbers::where('tabCode',$to->tabCode)->get() as $usrOnTab){
                if($usrOnTab && !in_array($usrOnTab->phoneNr,$clToNotify)){

                    $clNoti = new notifyClient();

                    $clNoti->for = 'prodStatChange';
                    $clNoti->toRes = $to->toRes;
                    $clNoti->tableNr = $to->tableNr;
                    $clNoti->clPhoneNr = $usrOnTab->phoneNr;
                    $clNoti->data = json_encode([
                        'phoneNrNotifyFor' => $usrOnTab->phoneNr,
                        'tabOrId' => $to->id,
                    ]);
                    $clNoti->readInd = 0;
                    $clNoti->save();

                    array_push($clToNotify,$usrOnTab->phoneNr);
                }
            }
        // -------------------------------------

    }

    public function cookPanelAddOneDoneOrderProdT(Request $req){
        $taInst = taDeForCookOr::find($req->taInstId);

        $taInst->prodSasiaDone =  $taInst->prodSasiaDone + 1;
        $taInst->save();

        if($taInst->prodSasia == $taInst->prodSasiaDone){
            // admins
            foreach(User::where([['sFor',$taInst->toRes],['role','5']])->get() as $user){
                $details = [
                    'id' => $taInst->orderId,
                    'type' => 'productIsReadyTa',
                    'prodId' => $taInst->prodId,
                    'tableNr' => '0'
                ];
                $user->notify(new \App\Notifications\productIsReadyRes($details));
            }

            // waiters
            foreach(User::where([['sFor',$taInst->toRes],['role','55']])->get() as $oneWaiter){
                $aToTakeaway = accessControllForAdmins::where([['userId',$oneWaiter->id],['accessDsc', 'Takeaway']])->first();
                if($aToTakeaway != NULL && $aToTakeaway->accessValid == 1){
                    // register the notification ...
                    $details = [
                        'id' => $taInst->orderId,
                        'type' => 'productIsReadyTa',
                        'prodId' => $taInst->prodId,
                        'tableNr' => '0'
                    ];
                    $oneWaiter->notify(new \App\Notifications\productIsReadyRes($details));
                }
            }
        }

        $theP = Produktet::find($taInst->prodId);
        if($theP != NULL){
            foreach(User::where([['sFor',$taInst->toRes],['role','54']])->get() as $oneCook){
                if($oneCook->id != Auth::User()->id){
                    $prodAcs = 0;
                    $extAcs = 0;
                    $typAcs = 0;

                    $catAcs = $this->checkCategoryAcs($theP->kategoria,$oneCook->id);
                    if($catAcs == 0){
                        $prodAcs = $this->checkProductAcs($theP->id,$oneCook->id);
                        if($catAcs == 0){
                            if($taInst->prodExtra != 'empty'){
                            $extAcs = $this->checkExtraAcs($taInst->prodExtra,$theP->kategoria,$oneCook->id);
                            }
                            if($extAcs == 0){
                                if($taInst->prodType != 'empty'){
                                    $typAcs = $this->checkTypeAcs($taInst->prodType,$theP->kategoria,$oneCook->id);
                                }
                            }
                        }
                    }
                    if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                        $details = [
                            'id' => $taInst->orderId,
                            'type' => 'cookPanelUpdateTaCookUpdate',
                            'prodId' => $taInst->prodId
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
        }
    }

    
    public function cookPanelAddOneDoneOrderProdD(Request $req){
        $deInst = taDeForCookOr::find($req->deInstId);

        $deInst->prodSasiaDone =  $deInst->prodSasiaDone + 1;
        $deInst->save();

        if($deInst->prodSasia == $deInst->prodSasiaDone){
            // admins
            foreach(User::where([['sFor',$deInst->toRes],['role','5']])->get() as $user){
                $details = [
                    'id' => $deInst->orderId,
                    'type' => 'productIsReadyDe',
                    'prodId' => $deInst->prodId,
                    'tableNr' => '0'
                ];
                $user->notify(new \App\Notifications\productIsReadyRes($details));
            }

            // waiters
            foreach(User::where([['sFor',$deInst->toRes],['role','55']])->get() as $oneWaiter){
                $aToDelivery = accessControllForAdmins::where([['userId',$oneWaiter->id],['accessDsc', 'Delivery']])->first();
                if($aToDelivery != NULL && $aToDelivery->accessValid == 1){
                    // register the notification ...
                    $details = [
                        'id' => $deInst->orderId,
                        'type' => 'productIsReadyDe',
                        'prodId' => $deInst->prodId,
                        'tableNr' => '0'
                    ];
                    $oneWaiter->notify(new \App\Notifications\productIsReadyRes($details));
                }
            }
        }

        $theP = Produktet::find($deInst->prodId);
        if($theP != NULL){
            foreach(User::where([['sFor',$deInst->toRes],['role','54']])->get() as $oneCook){
                if($oneCook->id != Auth::User()->id){
                    $prodAcs = 0;
                    $extAcs = 0;
                    $typAcs = 0;

                    $catAcs = $this->checkCategoryAcs($theP->kategoria,$oneCook->id);
                    if($catAcs == 0){
                        $prodAcs = $this->checkProductAcs($theP->id,$oneCook->id);
                        if($catAcs == 0){
                            if($deInst->prodExtra != 'empty'){
                            $extAcs = $this->checkExtraAcs($deInst->prodExtra,$theP->kategoria,$oneCook->id);
                            }
                            if($extAcs == 0){
                                if($deInst->prodType != 'empty'){
                                    $typAcs = $this->checkTypeAcs($deInst->prodType,$theP->kategoria,$oneCook->id);
                                }
                            }
                        }
                    }
                    if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                        $details = [
                            'id' => $deInst->id,
                            'type' => 'cookPanelUpdateDe',
                            'prodId' => $deInst->prodId
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
        }
    }






















    public function cookPanelRemoveOneDoneOrderProd(Request $req){
        $to = TabOrder::find($req->tabOrderId);

        $to->OrderSasiaDone =  $to->OrderSasiaDone - 1;
        $to->save();

        if((int)$to->OrderSasia == (int)((int)$to->OrderSasiaDone + (int)1)){
            // admins
            foreach(User::where([['sFor',$to->toRes],['role','5']])->get() as $user){
                $details = [
                    'id' => $to->id,
                    'type' => 'productIsReadyRes',
                    'prodId' => $to->prodId,
                    'tableNr' => $to->tableNr
                ];
                $user->notify(new \App\Notifications\productIsReadyRes($details));
            }
            // waiters
            foreach(User::where([['sFor',$to->toRes],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $to->tableNr]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $to->id,
                        'type' => 'productIsReadyRes',
                        'prodId' => $to->prodId,
                        'tableNr' => $to->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\productIsReadyRes($details));
                }
            }
        }

        $theP = Produktet::find($to->prodId);
        if($theP != NULL){
            foreach(User::where([['sFor',$to->toRes],['role','54']])->get() as $oneCook){
                if($oneCook->id != Auth::User()->id){
                    $prodAcs = 0;
                    $extAcs = 0;
                    $typAcs = 0;

                    $catAcs = $this->checkCategoryAcs($theP->kategoria,$oneCook->id);
                    if($catAcs == 0){
                        $prodAcs = $this->checkProductAcs($theP->id,$oneCook->id);
                        if($catAcs == 0){
                            if($to->OrderExtra != 'empty'){
                            $extAcs = $this->checkExtraAcs($to->OrderExtra,$theP->kategoria,$oneCook->id);
                            }
                            if($extAcs == 0){
                                if($to->OrderType != 'empty'){
                                    $typAcs = $this->checkTypeAcs($to->OrderType,$theP->kategoria,$oneCook->id);
                                }
                            }
                        }
                    }
                    if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                        $details = [
                            'id' => $to->id,
                            'type' => 'cookPanelUpdate',
                            'prodId' => $to->prodId
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
        }
        // Njoftohet klienti 
            $clToNotify=array();
            foreach(tabVerificationPNumbers::where('tabCode',$to->tabCode)->get() as $usrOnTab){
                if($usrOnTab && !in_array($usrOnTab->phoneNr,$clToNotify)){

                    $clNoti = new notifyClient();

                    $clNoti->for = 'prodStatChange';
                    $clNoti->toRes = $to->toRes;
                    $clNoti->tableNr = $to->tableNr;
                    $clNoti->clPhoneNr = $usrOnTab->phoneNr;
                    $clNoti->data = json_encode([
                        'phoneNrNotifyFor' => $usrOnTab->phoneNr,
                        'tabOrId' => $to->id,
                    ]);
                    $clNoti->readInd = 0;
                    $clNoti->save();

                    array_push($clToNotify,$usrOnTab->phoneNr);
                }
            }
        // -------------------------------------
    }


    public function cookPanelRemoveOneDoneOrderProdT(Request $req){
        $taInst = taDeForCookOr::find($req->taInstId);

        $taInst->prodSasiaDone =  $taInst->prodSasiaDone - 1;
        $taInst->save();

        if((int)$taInst->prodSasia == (int)((int)$taInst->prodSasiaDone + (int)1)){
            // admins
            foreach(User::where([['sFor',$taInst->toRes],['role','5']])->get() as $user){
                $details = [
                    'id' => $taInst->orderId,
                    'type' => 'productIsReadyTa',
                    'prodId' => $taInst->prodId,
                    'tableNr' => '0'
                ];
                $user->notify(new \App\Notifications\productIsReadyRes($details));
            }
            // waiters
            foreach(User::where([['sFor',$taInst->toRes],['role','55']])->get() as $oneWaiter){
                $aToTakeaway = accessControllForAdmins::where([['userId',$oneWaiter->id],['accessDsc', 'Takeaway']])->first();
                if($aToTakeaway != NULL && $aToTakeaway->accessValid == 1){
                    // register the notification ...
                    $details = [
                        'id' => $taInst->orderId,
                        'type' => 'productIsReadyTa',
                        'prodId' => $taInst->prodId,
                        'tableNr' => '0'
                    ];
                    $oneWaiter->notify(new \App\Notifications\productIsReadyRes($details));
                }
            }
        }
        $theP = Produktet::find($taInst->prodId);
        if($theP != NULL){
            foreach(User::where([['sFor',$taInst->toRes],['role','54']])->get() as $oneCook){
                if($oneCook->id != Auth::User()->id){
                    $prodAcs = 0;
                    $extAcs = 0;
                    $typAcs = 0;

                    $catAcs = $this->checkCategoryAcs($theP->kategoria,$oneCook->id);
                    if($catAcs == 0){
                        $prodAcs = $this->checkProductAcs($theP->id,$oneCook->id);
                        if($catAcs == 0){
                            if($taInst->prodExtra != 'empty'){
                            $extAcs = $this->checkExtraAcs($taInst->prodExtra,$theP->kategoria,$oneCook->id);
                            }
                            if($extAcs == 0){
                                if($taInst->prodType != 'empty'){
                                    $typAcs = $this->checkTypeAcs($taInst->prodType,$theP->kategoria,$oneCook->id);
                                }
                            }
                        }
                    }
                    if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                        $details = [
                            'id' => $taInst->orderId,
                            'type' => 'cookPanelUpdateTaCookUpdate',
                            'prodId' => $taInst->prodId
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
        }
    }


    public function cookPanelRemoveOneDoneOrderProdD(Request $req){
        $deInst = taDeForCookOr::find($req->deInstId);

        $deInst->prodSasiaDone =  $deInst->prodSasiaDone - 1;
        $deInst->save();

        if((int)$deInst->prodSasia == (int)((int)$deInst->prodSasiaDone + (int)1)){
            // admins
            foreach(User::where([['sFor',$deInst->toRes],['role','5']])->get() as $user){
                $details = [
                    'id' => $deInst->orderId,
                    'type' => 'productIsReadyDe',
                    'prodId' => $deInst->prodId,
                    'tableNr' => '0'
                ];
                $user->notify(new \App\Notifications\productIsReadyRes($details));
            }
            // waiters
            foreach(User::where([['sFor',$deInst->toRes],['role','55']])->get() as $oneWaiter){
                $aToDelivery = accessControllForAdmins::where([['userId',$oneWaiter->id],['accessDsc', 'Delivery']])->first();
                if($aToDelivery != NULL && $aToDelivery->accessValid == 1){
                    // register the notification ...
                    $details = [
                        'id' => $deInst->orderId,
                        'type' => 'productIsReadyDe',
                        'prodId' => $deInst->prodId,
                        'tableNr' => '0'
                    ];
                    $oneWaiter->notify(new \App\Notifications\productIsReadyRes($details));
                }
            }
        }
        $theP = Produktet::find($deInst->prodId);
        if($theP != NULL){
            foreach(User::where([['sFor',$deInst->toRes],['role','54']])->get() as $oneCook){
                if($oneCook->id != Auth::User()->id){
                    $prodAcs = 0;
                    $extAcs = 0;
                    $typAcs = 0;

                    $catAcs = $this->checkCategoryAcs($theP->kategoria,$oneCook->id);
                    if($catAcs == 0){
                        $prodAcs = $this->checkProductAcs($theP->id,$oneCook->id);
                        if($catAcs == 0){
                            if($deInst->prodExtra != 'empty'){
                            $extAcs = $this->checkExtraAcs($deInst->prodExtra,$theP->kategoria,$oneCook->id);
                            }
                            if($extAcs == 0){
                                if($deInst->prodType != 'empty'){
                                    $typAcs = $this->checkTypeAcs($deInst->prodType,$theP->kategoria,$oneCook->id);
                                }
                            }
                        }
                    }
                    if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                        $details = [
                            'id' => $deInst->id,
                            'type' => 'cookPanelUpdateDe',
                            'prodId' => $deInst->prodId
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
        }
    }















    public function cookPanelOrderProdFinished(Request $req){
        $to = TabOrder::find($req->tabOrderId);

        $addVal = (int)$to->OrderSasia - (int)$to->OrderSasiaDone;

        $to->OrderSasiaDone = $to->OrderSasia;
        $to->save();

        $cookLog = new logCookActivity();
        $cookLog->cookId = Auth::user()->id;
        $cookLog->actType = 'ResOrCooked';
        $cookLog->actId = $req->tabOrderId;
        $cookLog->permb = 'none';
        $cookLog->opDesc = 'Tab Order Cooked by Cook';
        $cookLog->save();

        // notification to Admins
        foreach(User::where([['sFor',$to->toRes],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                $details = ['id' => $to->id, 'type' => 'productIsReadyRes', 'prodId' => $to->prodId, 'tableNr' => $to->tableNr];
                $user->notify(new \App\Notifications\productIsReadyRes($details));
                if(newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$to->tableNr],['tabOrderId',$to->id],['statActive','1']])->first() == NULL){
                    $newAdmAlert = new newOrdersAdminAlert();
                    $newAdmAlert->adminId = $user->id;
                    $newAdmAlert->tableNr = $to->tableNr;
                    $newAdmAlert->toRes = $to->toRes;
                    $newAdmAlert->tabOrderId = $to->id;
                    $newAdmAlert->statActive = 1;
                    $newAdmAlert->save();
                }
            }
        }

        // notification to Waiters
        foreach(User::where([['sFor',$to->toRes],['role','55']])->get() as $oneWaiter){
            if($oneWaiter->id != auth()->user()->id){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $to->tableNr]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = ['id' => $to->id, 'type' => 'productIsReadyRes', 'prodId' => $to->prodId, 'tableNr' => $to->tableNr];
                    $oneWaiter->notify(new \App\Notifications\productIsReadyRes($details));
                    if(newOrdersAdminAlert::where([['adminId',$oneWaiter->id],['tableNr',$to->tableNr],['tabOrderId',$to->id],['statActive','1']])->first() == NULL){
                        $newAdmAlert = new newOrdersAdminAlert();
                        $newAdmAlert->adminId = $oneWaiter->id;
                        $newAdmAlert->tableNr = $to->tableNr;
                        $newAdmAlert->toRes = $to->toRes;
                        $newAdmAlert->tabOrderId = $to->id;
                        $newAdmAlert->statActive = 1;
                        $newAdmAlert->save();
                    }
                }
            }
        }

        // notifications to other cooks
        $theP = Produktet::find($to->prodId);
        if($theP != NULL){
            foreach(User::where([['sFor',$to->toRes],['role','54']])->get() as $oneCook){
                if($oneCook->id != Auth::User()->id){
                    $prodAcs = 0;
                    $extAcs = 0;
                    $typAcs = 0;
                    $catAcs = $this->checkCategoryAcs($theP->kategoria,$oneCook->id);
                    if($catAcs == 0){
                        $prodAcs = $this->checkProductAcs($theP->id,$oneCook->id);
                        if($catAcs == 0){
                            if($to->OrderExtra != 'empty'){ $extAcs = $this->checkExtraAcs($to->OrderExtra,$theP->kategoria,$oneCook->id); }
                            if($extAcs == 0){
                                if($to->OrderType != 'empty'){ $typAcs = $this->checkTypeAcs($to->OrderType,$theP->kategoria,$oneCook->id);}
                            }
                        }
                    }
                    if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                        $details = ['id' => $to->id, 'type' => 'cookPanelUpdate', 'prodId' => $to->prodId];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
        }

        // Njoftohet klienti 
            $clToNotify=array();
            foreach(tabVerificationPNumbers::where('tabCode',$to->tabCode)->get() as $usrOnTab){
                if($usrOnTab && !in_array($usrOnTab->phoneNr,$clToNotify)){
                    $clNoti = new notifyClient();
                    $clNoti->for = 'prodStatChange';
                    $clNoti->toRes = $to->toRes;
                    $clNoti->tableNr = $to->tableNr;
                    $clNoti->clPhoneNr = $usrOnTab->phoneNr;
                    $clNoti->data = json_encode([
                        'phoneNrNotifyFor' => $usrOnTab->phoneNr,
                        'tabOrId' => $to->id,
                    ]);
                    $clNoti->readInd = 0;
                    $clNoti->save();
                    array_push($clToNotify,$usrOnTab->phoneNr);
                }
            }
        // -----------------------------------------------------------------------

        // order Serving devices
            $thisProdukt = Produktet::find($to->prodId);
            $deviHasAccess = false;
            if($thisProdukt != Null){
                foreach(orderServingDevices::where('toRes',Auth::user()->sFor)->get() as $oneDevi){
                    if(orderServingDevicesAccess::where([['deviceId',$oneDevi->id],['accessType',1],['prodCatId',$thisProdukt->kategoria]])->first() != Null){
                        // access ne kategori
                        $newOrShowToDevice = new orderServingOrderShow(); 
                        $newOrShowToDevice->deviceId = $oneDevi->id;
                        $newOrShowToDevice->tabOrderId = $to->id;
                        $newOrShowToDevice->tableNr = $to->tableNr;
                        $newOrShowToDevice->theStat = 0;
                        $newOrShowToDevice->save(); 

                        $newOrNotificationDevi = new orderServingNotification();
                        $newOrNotificationDevi->deviceId = $oneDevi->id;
                        $newOrNotificationDevi->elementId = $to->id;
                        $newOrNotificationDevi->notiType = 'cookedByCookPanel';
                        $newOrNotificationDevi->save();

                        $to->orderServed = 0;
                        $to->save();

                    }else if(orderServingDevicesAccess::where([['deviceId',$oneDevi->id],['accessType',2],['prodCatId',$thisProdukt->id]])->first() != Null){
                        // access ne product
                        $newOrShowToDevice = new orderServingOrderShow(); 
                        $newOrShowToDevice->deviceId = $oneDevi->id;
                        $newOrShowToDevice->tabOrderId = $to->id;
                        $newOrShowToDevice->tableNr = $to->tableNr;
                        $newOrShowToDevice->theStat = 0;
                        $newOrShowToDevice->save(); 

                        $newOrNotificationDevi = new orderServingNotification();
                        $newOrNotificationDevi->deviceId = $oneDevi->id;
                        $newOrNotificationDevi->elementId = $to->id;
                        $newOrNotificationDevi->notiType = 'cookedByCookPanel';
                        $newOrNotificationDevi->save();

                        $to->orderServed = 0;
                        $to->save();
                    }
                }
            }
        // -----------------------------------------------------------------------

        $tabOrCalled = 0;
        foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',(int)$to->tableNr],['abrufenStat','1']])->get() as $toOneCnt){
            if($toOneCnt->OrderSasia != $toOneCnt->OrderSasiaDone){
                $tabOrCalled++;
                break;
            }
        }

        return $addVal.'|||'.$tabOrCalled;

    }

    public function cookPanelOrderProdFinishedAllTable(Request $req){
        
        if($req->tableNr == -1){
            $allTOrds = TabOrder::where([['tabCode','!=',0],['toRes',Auth::user()->sFor],['abrufenStat','1']])->get();
        }else{
            $allTOrds = TabOrder::where([['tabCode','!=',0],['toRes',Auth::user()->sFor],['tableNr',$req->tableNr]])->get();
        }
        
        foreach($allTOrds as $tOrOne){
            if($tOrOne->OrderSasiaDone != $tOrOne->OrderSasia){
                $theP = Produktet::find($tOrOne->prodId);
                $prodAcs = 0;
                $extAcs = 0;
                $typAcs = 0;
                $catAcs = $this->checkCategoryAcs($theP->kategoria,Auth::user()->id);
                if($catAcs == 0){
                    $prodAcs = $this->checkProductAcs($theP->id,Auth::user()->id);
                    if($prodAcs == 0){
                        if($tOrOne->OrderExtra != 'empty'){ $extAcs = $this->checkExtraAcs($tOrOne->OrderExtra,$theP->kategoria,Auth::user()->id); }
                        if($extAcs == 0){
                            if($tOrOne->OrderType != 'empty'){ $typAcs = $this->checkTypeAcs($tOrOne->OrderType,$theP->kategoria,Auth::user()->id);}
                        }
                    }
                }

                if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                    
                    $tOrOne->OrderSasiaDone = $tOrOne->OrderSasia;
                    $tOrOne->save();
            
                    $cookLog = new logCookActivity();
                    $cookLog->cookId = Auth::user()->id;
                    $cookLog->actType = 'ResOrCooked';
                    $cookLog->actId = $tOrOne->id;
                    $cookLog->permb = 'none';
                    $cookLog->opDesc = 'Tab Order Cooked by Cook';
                    $cookLog->save();

                    // notification to Admins
                    foreach(User::where([['sFor',$tOrOne->toRes],['role','5']])->get() as $admin){
                        if($admin->id != Auth::user()->id){
                            $details = ['id' => $tOrOne->id, 'type' => 'productIsReadyRes', 'prodId' => $tOrOne->prodId, 'tableNr' => $tOrOne->tableNr];
                            $admin->notify(new \App\Notifications\productIsReadyRes($details));
                            if(newOrdersAdminAlert::where([['adminId',$admin->id],['tableNr',$tOrOne->tableNr],['tabOrderId',$tOrOne->id],['statActive','1']])->first() == NULL){
                                $newAdmAlert = new newOrdersAdminAlert();
                                $newAdmAlert->adminId = $admin->id;
                                $newAdmAlert->tableNr = $tOrOne->tableNr;
                                $newAdmAlert->toRes = $tOrOne->toRes;
                                $newAdmAlert->tabOrderId = $tOrOne->id;
                                $newAdmAlert->statActive = 1;
                                $newAdmAlert->save();
                            }
                        }
                    }

                    // notification to Waiters
                    foreach(User::where([['sFor',$tOrOne->toRes],['role','55']])->get() as $oneWaiter){
                        if($oneWaiter->id != auth()->user()->id){
                            $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $tOrOne->tableNr]])->first();
                            if($aToTable != NULL && $aToTable->statusAct == 1){
                                // register the notification ...
                                $details = ['id' => $tOrOne->id, 'type' => 'productIsReadyRes', 'prodId' => $tOrOne->prodId, 'tableNr' => $tOrOne->tableNr];
                                $oneWaiter->notify(new \App\Notifications\productIsReadyRes($details));
                                if(newOrdersAdminAlert::where([['adminId',$oneWaiter->id],['tableNr',$tOrOne->tableNr],['tabOrderId',$tOrOne->id],['statActive','1']])->first() == NULL){
                                    $newAdmAlert = new newOrdersAdminAlert();
                                    $newAdmAlert->adminId = $oneWaiter->id;
                                    $newAdmAlert->tableNr = $tOrOne->tableNr;
                                    $newAdmAlert->toRes = $tOrOne->toRes;
                                    $newAdmAlert->tabOrderId = $tOrOne->id;
                                    $newAdmAlert->statActive = 1;
                                    $newAdmAlert->save();
                                }
                            }
                        }
                    }

                    // notifications to other cooks
                    $theP = Produktet::find($tOrOne->prodId);
                    if($theP != NULL){
                        foreach(User::where([['sFor',$tOrOne->toRes],['role','54']])->get() as $oneCook){
                            if($oneCook->id != Auth::User()->id){
                                $prodAcs = 0;
                                $extAcs = 0;
                                $typAcs = 0;
                                $catAcs = $this->checkCategoryAcs($theP->kategoria,$oneCook->id);
                                if($catAcs == 0){
                                    $prodAcs = $this->checkProductAcs($theP->id,$oneCook->id);
                                    if($prodAcs == 0){
                                        if($tOrOne->OrderExtra != 'empty'){ $extAcs = $this->checkExtraAcs($tOrOne->OrderExtra,$theP->kategoria,$oneCook->id); }
                                        if($extAcs == 0){
                                            if($tOrOne->OrderType != 'empty'){ $typAcs = $this->checkTypeAcs($tOrOne->OrderType,$theP->kategoria,$oneCook->id);}
                                        }
                                    }
                                }
                                if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                                    $details = ['id' => $tOrOne->id, 'type' => 'cookPanelUpdate', 'prodId' => $tOrOne->prodId];
                                    $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                                }
                            }
                        }
                    }

                    // Njoftohet klienti 
                        $clToNotify = array();
                        foreach(tabVerificationPNumbers::where('tabCode',$tOrOne->tabCode)->get() as $usrOnTab){
                            if($usrOnTab && !in_array($usrOnTab->phoneNr,$clToNotify)){
                                $clNoti = new notifyClient();
                                $clNoti->for = 'prodStatChange';
                                $clNoti->toRes = $tOrOne->toRes;
                                $clNoti->tableNr = $tOrOne->tableNr;
                                $clNoti->clPhoneNr = $usrOnTab->phoneNr;
                                $clNoti->data = json_encode([
                                    'phoneNrNotifyFor' => $usrOnTab->phoneNr,
                                    'tabOrId' => $tOrOne->id,
                                ]);
                                $clNoti->readInd = 0;
                                $clNoti->save();
                                array_push($clToNotify,$usrOnTab->phoneNr);
                            }
                        }
                    // -----------------------------------------------------------------------


                    // order Serving devices
                        $thisProdukt = Produktet::find($tOrOne->prodId);
                        $deviHasAccess = false;
                        if($thisProdukt != Null){
                            foreach(orderServingDevices::where('toRes',Auth::user()->sFor)->get() as $oneDevi){
                                if(orderServingDevicesAccess::where([['deviceId',$oneDevi->id],['accessType',1],['prodCatId',$thisProdukt->kategoria]])->first() != Null){
                                    // access ne kategori
                                    $newOrShowToDevice = new orderServingOrderShow(); 
                                    $newOrShowToDevice->deviceId = $oneDevi->id;
                                    $newOrShowToDevice->tabOrderId = $tOrOne->id;
                                    $newOrShowToDevice->tableNr = $tOrOne->tableNr;
                                    $newOrShowToDevice->theStat = 0;
                                    $newOrShowToDevice->save(); 

                                    $newOrNotificationDevi = new orderServingNotification();
                                    $newOrNotificationDevi->deviceId = $oneDevi->id;
                                    $newOrNotificationDevi->elementId = $tOrOne->id;
                                    $newOrNotificationDevi->notiType = 'cookedByCookPanel';
                                    $newOrNotificationDevi->save();

                                    $tOrOne->orderServed = 0;
                                    $tOrOne->save();

                                }else if(orderServingDevicesAccess::where([['deviceId',$oneDevi->id],['accessType',2],['prodCatId',$thisProdukt->id]])->first() != Null){
                                    // access ne product
                                    $newOrShowToDevice = new orderServingOrderShow(); 
                                    $newOrShowToDevice->deviceId = $oneDevi->id;
                                    $newOrShowToDevice->tabOrderId = $tOrOne->id;
                                    $newOrShowToDevice->tableNr = $tOrOne->tableNr;
                                    $newOrShowToDevice->theStat = 0;
                                    $newOrShowToDevice->save(); 

                                    $newOrNotificationDevi = new orderServingNotification();
                                    $newOrNotificationDevi->deviceId = $oneDevi->id;
                                    $newOrNotificationDevi->elementId = $tOrOne->id;
                                    $newOrNotificationDevi->notiType = 'cookedByCookPanel';
                                    $newOrNotificationDevi->save();

                                    $tOrOne->orderServed = 0;
                                    $tOrOne->save();
                                }
                            }
                        }
                    // -----------------------------------------------------------------------
                }
            }
        }
    }




















    public function cookPanelOrderProdFinishedT(Request $req){
        $taInst = taDeForCookOr::find($req->taInstId);

        $addVal = (int)$taInst->prodSasia - (int)$taInst->prodSasiaDone;

        $taInst->prodSasiaDone =  $taInst->prodSasia;
        $taInst->save();

        $cookLog = new logCookActivity();
        $cookLog->cookId = Auth::user()->id;
        $cookLog->actType = 'TaOrCooked';
        $cookLog->actId = $req->taInstId;
        $cookLog->permb = 'orId:'.$taInst->orderId.'--- prodId:'.$taInst->prodId.'--- prodType:'.$taInst->prodType.'--- prodExtra:'.$taInst->prodExtra.'--- sasia/done:'.$taInst->prodSasia.'/'.$taInst->prodSasiaDone;
        $cookLog->opDesc = 'Takeaway Order Cooked by Cook';
        $cookLog->save();

        // admins
        foreach(User::where([['sFor',$taInst->toRes],['role','5']])->get() as $user){
            $details = [
                'id' => $taInst->orderId,
                'type' => 'productIsReadyTa',
                'prodId' => $taInst->prodId,
                'tableNr' => '0'
            ];
            $user->notify(new \App\Notifications\productIsReadyRes($details));
        }

        // waiters
        foreach(User::where([['sFor',$taInst->toRes],['role','55']])->get() as $oneWaiter){
            $aToTakeaway = accessControllForAdmins::where([['userId',$oneWaiter->id],['accessDsc', 'Takeaway']])->first();
            if($aToTakeaway != NULL && $aToTakeaway->accessValid == 1){
                // register the notification ...
                $details = [
                    'id' => $taInst->orderId,
                    'type' => 'productIsReadyTa',
                    'prodId' => $taInst->prodId,
                    'tableNr' => '0'
                ];
                $oneWaiter->notify(new \App\Notifications\productIsReadyRes($details));
            }
        }

        $theP = Produktet::find($taInst->prodId);
        if($theP != NULL){
            foreach(User::where([['sFor',$taInst->toRes],['role','54']])->get() as $oneCook){
                if($oneCook->id != Auth::User()->id){
                    $prodAcs = 0;
                    $extAcs = 0;
                    $typAcs = 0;

                    $catAcs = $this->checkCategoryAcs($theP->kategoria,$oneCook->id);
                    if($catAcs == 0){
                        $prodAcs = $this->checkProductAcs($theP->id,$oneCook->id);
                        if($prodAcs == 0){
                            if($taInst->prodExtra != 'empty'){
                            $extAcs = $this->checkExtraAcs($taInst->prodExtra,$theP->kategoria,$oneCook->id);
                            }
                            if($extAcs == 0){
                                if($taInst->prodType != 'empty'){
                                    $typAcs = $this->checkTypeAcs($taInst->prodType,$theP->kategoria,$oneCook->id);
                                }
                            }
                        }
                    }
                    if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                        $details = [
                            'id' => $taInst->orderId,
                            'type' => 'cookPanelUpdateTaCookUpdate',
                            'prodId' => $taInst->prodId
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
        }

        return $addVal;
    }


    public function cookPanelOrderProdFinishedD(Request $req){
        $deInst = taDeForCookOr::find($req->deInstId);

        $addVal = (int)$deInst->prodSasia - (int)$deInst->prodSasiaDone;

        $deInst->prodSasiaDone =  $deInst->prodSasia;
        $deInst->save();

        $cookLog = new logCookActivity();
        $cookLog->cookId = Auth::user()->id;
        $cookLog->actType = 'DeOrCooked';
        $cookLog->actId = $req->taInstId;
        $cookLog->permb = 'orId:'.$deInst->orderId.'--- prodId:'.$deInst->prodId.'--- prodType:'.$deInst->prodType.'--- prodExtra:'.$deInst->prodExtra.'--- sasia/done:'.$deInst->prodSasia.'/'.$deInst->prodSasiaDone;
        $cookLog->opDesc = 'Delivery Order Cooked by Cook';
        $cookLog->save();

        // admins
        foreach(User::where([['sFor',$deInst->toRes],['role','5']])->get() as $user){
            $details = [
                'id' => $deInst->orderId,
                'type' => 'productIsReadyDe',
                'prodId' => $deInst->prodId,
                'tableNr' => '0'
            ];
            $user->notify(new \App\Notifications\productIsReadyRes($details));
        }

        // waiters
        foreach(User::where([['sFor',$deInst->toRes],['role','55']])->get() as $oneWaiter){
            $aToDelivery = accessControllForAdmins::where([['userId',$oneWaiter->id],['accessDsc', 'Delivery']])->first();
            if($aToDelivery != NULL && $aToDelivery->accessValid == 1){
                // register the notification ...
                $details = [
                    'id' => $deInst->orderId,
                    'type' => 'productIsReadyDe',
                    'prodId' => $deInst->prodId,
                    'tableNr' => '0'
                ];
                $oneWaiter->notify(new \App\Notifications\productIsReadyRes($details));
            }
        }

        $theP = Produktet::find($deInst->prodId);
        if($theP != NULL){
            foreach(User::where([['sFor',$deInst->toRes],['role','54']])->get() as $oneCook){
                if($oneCook->id != Auth::User()->id){
                    $prodAcs = 0;
                    $extAcs = 0;
                    $typAcs = 0;

                    $catAcs = $this->checkCategoryAcs($theP->kategoria,$oneCook->id);
                    if($catAcs == 0){
                        $prodAcs = $this->checkProductAcs($theP->id,$oneCook->id);
                        if($catAcs == 0){
                            if($deInst->prodExtra != 'empty'){
                            $extAcs = $this->checkExtraAcs($deInst->prodExtra,$theP->kategoria,$oneCook->id);
                            }
                            if($extAcs == 0){
                                if($deInst->prodType != 'empty'){
                                    $typAcs = $this->checkTypeAcs($deInst->prodType,$theP->kategoria,$oneCook->id);
                                }
                            }
                        }
                    }
                    if($catAcs == 1 || $prodAcs == 1 || $extAcs == 1 || $typAcs == 1){
                        $details = [
                            'id' => $deInst->id,
                            'type' => 'cookPanelUpdateDe',
                            'prodId' => $deInst->prodId
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
        }

        return $addVal;
    }








    













    public function checkCategoryAcs($catId, $cookId){
        if(cooksProductSelection::where([['workerId',$cookId],['contentType','Category'],['contentId',$catId]])->first() != NULL){
            return 1;
        }else{
            return 0;
        }
    }
    public function checkProductAcs($pId,$cookId){
        if(cooksProductSelection::where([['workerId',$cookId],['contentType','Product'],['contentId',$pId]])->first() != NULL){
            return 1;
        }else{
            return 0;
        }
    }
    public function checkExtraAcs($extras, $catId, $cookId){
        $exAcs = 0;
        foreach(explode('--0--',$extras) as $oneEx){
            $oE2D = explode('||',$oneEx);
            $oEID = ekstra::where([['toCat',$catId],['emri',$oE2D[0]],['qmimi',$oE2D[1]]])->first();
            if($oEID != NULL){
                if(cooksProductSelection::where([['workerId',$cookId],['contentType','Extra'],['contentId',$oEID->id]])->first() != NULL){
                    $exAcs = 1;
                    break;
                }
            }
        }
        return $exAcs;
    }
    public function checkTypeAcs($type, $catId, $cookId){
        $tyAcs = 0;
        $oT2D = explode('||',$type);
        if(isset($oT2D[1])){
            $oTID = LlojetPro::where([['kategoria',$catId],['emri',$oT2D[0]],['vlera',$oT2D[1]]])->first();
        }else{
            $oTID = LlojetPro::where([['kategoria',$catId],['emri',$oT2D[0]]])->first();
        }
        if($oTID != NULL){
            if(cooksProductSelection::where([['workerId',$cookId],['contentType','Type'],['contentId',$oTID->id]])->first() != NULL){
                $tyAcs = 1;
            }
        }
        return $tyAcs;
    }




















    

    public function saveNewWorker(Request $req){
        $emailDomein = explode('@',$req->woEmail)[1];
        $lowerCEmail = strtolower($req->woEmail);
        $notAllowedEm = array('checkout@qrorpa.ch','datatrans@qrorpa.ch','erik.thaqi@qrorpa.ch','info@qrorpa.ch','jobs@qrorpa.ch','marketing@qrorpa.ch','noreply@qrorpa.ch',
        'sales@qrorpa.ch','seo@qrorpa.ch','sms.api@qrorpa.ch','socialmedia@qrorpa.ch','spryng.sms@qrorpa.ch','verkauf@qrorpa.ch','vertrag@qrorpa.ch','vertrieb@qrorpa.ch');
        if($emailDomein != 'qrorpa.ch'){
            return 'emailOnlyQrorpa';
        }else if(User::where('email',$req->woEmail)->first() != NULL){
            return 'emailIsUsed';
        }else if(in_array($lowerCEmail,$notAllowedEm)){
            return 'emailNotAllowed';
        }else{

            $newWo = new User();
            $newWo->name = $req->woName;
            $newWo->email = $req->woEmail;
            $newWo->password = bcrypt($req->woPassword);
            $newWo->role = $req->woType;
            $newWo->sFor = $req->resId;
            $newWo->save();

            return 'success';
        }
    }





    public function registerTableForWaiter(Request $req){
		// workerId: woId,
		// tNr: tableNr,
        // resId: res,
        $newTaToWaiters = new tablesAccessToWaiters(); 
        $newTaToWaiters->toRes = $req->resId; 
        $newTaToWaiters->tableNr = $req->tNr; 
        $newTaToWaiters->waiterId = $req->workerId; 
        $newTaToWaiters->statusAct = 1; 
        $newTaToWaiters->save(); 
    }
    public function removeTableForWaiter(Request $req){
    	// tatwaiterId
        $TaToWaiters = tablesAccessToWaiters::find($req->tatwaiterId); 
        if($TaToWaiters != NULL){ $TaToWaiters->delete(); }
    }








    public function registerAccessForWaiter(Request $req){
        // admId
        // waId
        // accContId
        $adminsAccess = accessControllForAdmins::find($req->accContId);
        if($adminsAccess != NULL){
            $newWaiterAccess = new accessControllForAdmins();
            $newWaiterAccess->userId = $req->waId;
            $newWaiterAccess->forRes = $adminsAccess->forRes;
            $newWaiterAccess->accessDsc = $adminsAccess->accessDsc;
            $newWaiterAccess->accessValid = 1;
            $newWaiterAccess->save();

            return 'Success';
        }else{
            return 'Fail';
        }
    }

    public function removeAccessForWaiter(Request $req){
        // waiterACCId
        $waiterAccess = accessControllForAdmins::find($req->waiterACCId);
        if($waiterAccess != NULL){ $waiterAccess->delete(); }
    }




    public function registerCategoryForCook(Request $req){
        $cNewProd = new cooksProductSelection();
        $cNewProd->toRes = $req->toRes;
        $cNewProd->workerId = $req->workerId;
        $cNewProd->contentType = 'Category';
        $cNewProd->contentId = $req->categoryId;
        $cNewProd->save();
    }
    public function removeCategoryForCook(Request $req){
        // productRecordId
        $proRecord = cooksProductSelection::find($req->productRecordId);
        if($proRecord != NULL){ $proRecord->delete(); }
    }



    
    public function registerExtraForCook(Request $req){
        // extraId
        // workerId
        // toRes
        $cNewProd = new cooksProductSelection();
        $cNewProd->toRes = $req->toRes;
        $cNewProd->workerId = $req->workerId;
        $cNewProd->contentType = 'Extra';
        $cNewProd->contentId = $req->extraId;
        $cNewProd->save();
    }
    public function removeExtraForCook(Request $req){
        // productRecordId
        $proRecord = cooksProductSelection::find($req->productRecordId);
        if($proRecord != NULL){ $proRecord->delete(); }
    }








    public function registerTypeForCook(Request $req){
        // typeId
        // workerId
        // toRes
        $cNewProd = new cooksProductSelection();
        $cNewProd->toRes = $req->toRes;
        $cNewProd->workerId = $req->workerId;
        $cNewProd->contentType = 'Type';
        $cNewProd->contentId = $req->typeId;
        $cNewProd->save();
    }
    public function removeTypeForCook(Request $req){
        // productRecordId
        $proRecord = cooksProductSelection::find($req->productRecordId);
        if($proRecord != NULL){ $proRecord->delete(); }
    }







    public function registerProductForCook(Request $req){
        $cNewProd = new cooksProductSelection();
        $cNewProd->toRes = $req->toRes;
        $cNewProd->workerId = $req->workerId;
        $cNewProd->contentType = 'Product';
        $cNewProd->contentId = $req->prodId;
        $cNewProd->save();
    }
    public function removeProductForCook(Request $req){
        // productRecordId
        $proRecord = cooksProductSelection::find($req->productRecordId);
        if($proRecord != NULL){ $proRecord->delete(); }
    }







    public function takeawayAccessForCook(Request $req){
        $taAccess = cooksProductSelection::where([['workerId',$req->workerId],['contentType','Takeaway']])->first();
        if($taAccess == NULL){
            // register
            $cNewProd = new cooksProductSelection();
            $cNewProd->toRes = $req->resId;
            $cNewProd->workerId = $req->workerId;
            $cNewProd->contentType = 'Takeaway';
            $cNewProd->contentId = 0;
            $cNewProd->save();

        }else{
            // remove
            $taAccess->delete();
        }
    }

    public function deliveryAccessForCook(Request $req){
        $taAccess = cooksProductSelection::where([['workerId',$req->workerId],['contentType','Delivery']])->first();
        if($taAccess == NULL){
            // register
            $cNewProd = new cooksProductSelection();
            $cNewProd->toRes = $req->resId;
            $cNewProd->workerId = $req->workerId;
            $cNewProd->contentType = 'Delivery';
            $cNewProd->contentId = 0;
            $cNewProd->save();

        }else{
            // remove
            $taAccess->delete();
        }
    }



    public function chngCooksPVer(Request $req){
        // workerId
        // newVer
        $theW = User::find($req->workerId);
        if($theW != NULL){
            $theW->cookPanV = (int)$req->newVer;
            $theW->save();
        }
    }




    public function deleteWorker(Request $req){
        // workerId
        $theW = User::find($req->workerId);
        if($theW != NULL){
            if($theW->role == 55){
                foreach(tablesAccessToWaiters::where('waiterId',$theW->id)->get() as $od){
                    $od->delete();
                }
                foreach(accessControllForAdmins::where('userId',$theW->id)->get() as $od){
                    $od->delete();
                }
            }else if($theW->role == 54){
                foreach(cooksProductSelection::where('workerId',$theW->id)->get() as $od){
                    $od->delete();
                }
            }
            $theW->delete();
        }
    }




    // plates for restaurants mng

    public function setPlateForCat(request $req){
        // plateNr
        // catId
        $theCa = kategori::find($req->catId);
        if($theCa == NULL){
            return 'catNotFound';
        }else{
            $theCa->forPlate = $req->plateNr;
            $theCa->save();

            return 'success';
        }
    }

    public function saveNewPlate(Request $req){
        // newPlName
        // resId
        $chForIns = resPlates::where([['nameTitle',$req->newPlName],['toRes',$req->resId]])->first();
        if($chForIns != NULL){
            return 'duplicateIns';
        }else{
            $insNrPlateNext = resPlates::where('toRes',Auth::user()->sFor)->count() + 1;

            $newPl = new resPlates();
            $newPl->nameTitle = $req->newPlName;
            $newPl->desc2C = (int)$insNrPlateNext;
            $newPl->toRes = $req->resId;
            $newPl->isActive = 1;
            $newPl->save();

            return 'success';
        }
    }
    public function deleteThisPlate(Request $req){
        // pId
        $theResPl = resPlates::find($req->pId);
        $plNr = $theResPl->desc2C;
        $plId = $theResPl->id;
        foreach(kategori::where([['toRes',Auth::user()->sFor],['forPlate',$plNr]]) as $cpch1){
            $cpch1->forPlate = 0;
            $cpch1->save();
        }
        $theResPl->delete();

        foreach(resPlates::where([['toRes',Auth::user()->sFor],['desc2C','>', $plNr]])->get() as $plToCh){
            foreach(kategori::where([['toRes',Auth::user()->sFor],['forPlate',$plToCh->desc2C]]) as $cpch2){
                $cpch2->forPlate = (int)$cpch2->forPlate - (int)1;
                $cpch2->save();
            }
            $plToCh->desc2C = (int)$plToCh->desc2C - (int)1;
            $plToCh->save();
        }
       
        foreach( TabOrder::where('toPlate',$plId)->get() as $tfp ){
            $tfp->toPlate = 0;
            $tfp->save();
        }
        return 'success';
    }
    public function saveChangesFPlate(Request $req){
        // pId
        // pNewName
        $theRP = resPlates::find($req->pId);
        if( $theRP == NULL ){
            return 'rpNotFound';
        }else{
            $theRP->nameTitle = $req->pNewName;
            $theRP->save();

            return 'success';
        }
    }   
    // ---------------------------------------------------------------------------------




    public function catRepSetProdToCatWaiter(Request $req){
        // prId
        // repCat
        $theP = Produktet::findOrFail($req->prId);
        if($req->repCat == $theP->toReportCat){
            $theP->toReportCat = 0;
            $theP->save();

            $taP = Takeaway::where('prod_id',$theP->id)->first();
            if($taP != NULL){
                $taP->toReportCat = 0;
                $taP->save();
            }
            $deP = DeliveryProd::where('prod_id',$theP->id)->first();
            if($deP != NULL){
                $deP->toReportCat = 0;
                $deP->save();
            }
            return 'removed';
        }else{
            if($theP->toReportCat == 0){
                $theP->toReportCat = $req->repCat;
                $theP->save();

                $taP = Takeaway::where('prod_id',$theP->id)->first();
                if($taP != NULL){
                    $taP->toReportCat = $req->repCat;
                    $taP->save();
                }
                $deP = DeliveryProd::where('prod_id',$theP->id)->first();
                if($deP != NULL){
                    $deP->toReportCat = $req->repCat;
                    $deP->save();
                }

                return 'added';
            }else{
                $theP->toReportCat = $req->repCat;
                $theP->save();

                $taP = Takeaway::where('prod_id',$theP->id)->first();
                if($taP != NULL){
                    $taP->toReportCat = $req->repCat;
                    $taP->save();
                }
                $deP = DeliveryProd::where('prod_id',$theP->id)->first();
                if($deP != NULL){
                    $deP->toReportCat = $req->repCat;
                    $deP->save();
                }

                return 'addedPlus'; 
            }
        }
    }

    public function catRepSetAllCatToCatWaiter(Request $req){
        foreach(Produktet::where('kategoria',$req->catId)->get() as $onePr){
            $onePr->toReportCat = $req->repCat;
            $onePr->save();

            $taP = Takeaway::where('prod_id',$onePr->id)->first();
            if($taP != NULL){
                $taP->toReportCat = $req->repCat;
                $taP->save();
            }
            $deP = DeliveryProd::where('prod_id',$onePr->id)->first();
            if($deP != NULL){
                $deP->toReportCat = $req->repCat;
                $deP->save();
            }
        }
    }



    public function setAcceToStatPgFA(Request $req){
        $hasAcce = accessControllForAdmins::where([['userId',$req->usrId],['accessDsc','Statistiken']])->first();
        if($hasAcce != NULL){
            $hasAcce->delete();
        }else{
            $newAcce = new accessControllForAdmins();
            $newAcce->userId = $req->usrId;
            $newAcce->forRes = Auth::user()->sFor;
            $newAcce->accessDsc = 'Statistiken';
            $newAcce->accessValid = 1;
            $newAcce->save();
        }
    }

    public function setAcceToProdPgFA(Request $req){
        $hasAcce = accessControllForAdmins::where([['userId',$req->usrId],['accessDsc','Products']])->first();
        if($hasAcce != NULL){
            $hasAcce->delete();
        }else{
            $newAcce = new accessControllForAdmins();
            $newAcce->userId = $req->usrId;
            $newAcce->forRes = Auth::user()->sFor;
            $newAcce->accessDsc = 'Products';
            $newAcce->accessValid = 1;
            $newAcce->save();
        }
    }





    public function AccountPanelStatBillsSave(Request $req){
        if($req->hasfile('billsToSave')){
            foreach($req->file('billsToSave') as $file){
                $billReco = new billsRecordRes();
                $billReco->forRes = $req->saveBillRes ;
                $billReco->fromStaf = $req->saveBillStaf ;
                $billReco->docForDate = $req->saveBillDate ;
                $billReco->docStat = 1 ;

                $fNO_menu = $file->getClientOriginalName();
                $fN_menu = pathinfo($fNO_menu, PATHINFO_FILENAME);
                $ex_menu = $file->getClientOriginalExtension();
                $fNS_menu = $fN_menu.'_'.time().'.'.$ex_menu;
                $path_menu = $file->move('storage/billsAusgabenFiles', $fNS_menu);

                $billReco->docName = $fNS_menu;
                $billReco->save();
            }
        }
        return redirect()->back();
    }

    public function AccountPanelStatBillsGetDocs(Request $req){
        $bRecords = billsRecordRes::where([['forRes',$req->resId],['docForDate',$req->theDt]])->get();
        if($bRecords != NULL && $bRecords->count() > 0){
            $respo = '';
            foreach($bRecords as $oneBR){
                if ($respo == ''){
                    $theD2D = explode('-',explode(' ',$oneBR->created_at)[0]);
                    $respo = $oneBR->docName.'-3-3-'.User::find($oneBR->fromStaf)->name.'-3-3-'.$theD2D[2].'.'.$theD2D[1].'.'.$theD2D[0];
                }else{
                    $theD2D = explode('-',explode(' ',$oneBR->created_at)[0]);
                    $respo .= '-4-4-'.$oneBR->docName.'-3-3-'.User::find($oneBR->fromStaf)->name.'-3-3-'.$theD2D[2].'.'.$theD2D[1].'.'.$theD2D[0];
                }
            }
            return $respo;
        }else{
            return 'zero';
        }
    }








    public function WaiterPanelStatBillsSave(Request $req){
        if($req->hasfile('billsToSave')){
            foreach($req->file('billsToSave') as $file){
                $billReco = new billsRecordRes();
                $billReco->forRes = $req->saveBillRes ;
                $billReco->fromStaf = $req->saveBillStaf ;
                $billReco->docForDate = $req->saveBillDate ;
                $billReco->docStat = 1 ;

                $fNO_menu = $file->getClientOriginalName();
                $fN_menu = pathinfo($fNO_menu, PATHINFO_FILENAME);
                $ex_menu = $file->getClientOriginalExtension();
                $fNS_menu = $fN_menu.'_'.time().'.'.$ex_menu;
                $path_menu = $file->move('storage/billsAusgabenFiles', $fNS_menu);

                $billReco->docName = $fNS_menu;
                $billReco->save();
            }
        }
        return redirect()->back();
    }

    public function WaiterPanelStatBillsGetDocs(Request $req){
        $bRecords = billsRecordRes::where([['forRes',$req->resId],['docForDate',$req->theDt],['fromStaf',Auth::user()->id]])->get();
        if($bRecords != NULL && $bRecords->count() > 0){
            $respo = '';
            foreach($bRecords as $oneBR){
                if ($respo == ''){
                    $theD2D = explode('-',explode(' ',$oneBR->created_at)[0]);
                    $respo = $oneBR->docName.'-3-3-'.User::find($oneBR->fromStaf)->name.'-3-3-'.$theD2D[2].'.'.$theD2D[1].'.'.$theD2D[0];
                }else{
                    $theD2D = explode('-',explode(' ',$oneBR->created_at)[0]);
                    $respo .= '-4-4-'.$oneBR->docName.'-3-3-'.User::find($oneBR->fromStaf)->name.'-3-3-'.$theD2D[2].'.'.$theD2D[1].'.'.$theD2D[0];
                }
            }
            return $respo;
        }else{
            return 'zero';
        }
    }


    public function setNewPassToAWorker(Request $req){
        $theU = User::find($req->uId);
        if($theU != NULL){
            $theNewPass = strval($req->newPas) ;
            $theU->password = bcrypt($theNewPass);
            $theU->save();
        }else{
            return 'usrNotFound';
        }
    }


    public function chnGNrBlocksShownCPV232INCH(Request $req){
        $theU = User::find(Auth::user()->id);  
        $theU->cookPV2BlShow = $req->newNrShown;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
        $theU->save();
    }






    public function waiterstatsDeletedTAProdsPage(){
        return view('adminPanelWaiter/adminIndexWaiter'); 
    }




    public function changePlateColorCook(Request $req){
        // convert to rgb
        $hexColorCode = $req->plateColorHex;
        $rgbRed = hexdec($hexColorCode[1].''.$hexColorCode[2]);
        $rgbGreen = hexdec($hexColorCode[3].''.$hexColorCode[4]);
        $rgbBlue = hexdec($hexColorCode[5].''.$hexColorCode[6]);

        // -------------------------------
        $cookColoreIns = cookColor::where([['cookId',Auth::user()->id],['plateId',$req->plateId]])->first();
        if($cookColoreIns != Null){
            // change
            $cookColoreIns->colorRGB = $rgbRed.','.$rgbGreen.','.$rgbBlue;
            $cookColoreIns->colorHEX = $req->plateColorHex;
            $cookColoreIns->save();
        }else{
            // create
            $cookColoreInsNew = new cookColor();
            $cookColoreInsNew->cookId = Auth::user()->id;
            $cookColoreInsNew->plateId = $req->plateId;
            $cookColoreInsNew->colorRGB = $rgbRed.','.$rgbGreen.','.$rgbBlue;
            $cookColoreInsNew->colorHEX = $req->plateColorHex;
            $cookColoreInsNew->save();

        }
    }


    
}
