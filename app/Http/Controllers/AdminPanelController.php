<?php

namespace App\Http\Controllers;

use PDF;
use Cart;
use Excel;
use QRCode;
use App\User;
use DateTime;
use App\Cupon;
use App\Piket;
use App\adsMod;
use App\ekstra;
use App\Orders;
use App\TipLog;
use App\giftCard;
use App\kategori;
use App\TabOrder;
use App\Takeaway;
use App\LlojetPro;
use App\Produktet;
use App\resPlates;
use App\Restorant;
use Carbon\Carbon;
use App\payTecPair;
use App\resdemoalfa;
use App\TableQrcode;
use App\DeliveryProd;
use App\notifyClient;
use App\pdfReportIns;
use App\TableChngReq;
use App\checkInOutReg;
use App\exportToExcel;
use App\OrdersPassive;
use App\rouletteUsage;
use App\taDeForCookOr;
use App\adsActiveToRes;
use App\billsRecordRes;
use App\billTabletsReg;
use App\cntGroupAdmWai;
use App\ghostCartInUse;
use App\pdfResProdCats;
use App\rechnungClient;
use App\RecomendetProd;
use App\tabOrderDelete;
use App\ordersTempForTA;
use App\cntGroupL2AdmWai;
use App\logOrderPayMChng;
use App\logTabAutoRemove;
use App\onlinePayQRCStaf;
use App\TableReservation;
use App\tabOrdersPassive;
use App\testTableOnDbTwo;
use App\adsRepeatInterval;
use App\couponUsedPhoneNr;
use App\giftCardOnlinePay;
use App\waiterActivityLog;
use App\billTabletsCrrStat;
use App\tableChngReqsAdmin;
use App\admExtraAccessToRes;
use App\emailReceiptFromAdm;
use App\Exports\excelExport;
use App\giftCardRechnungPay;
use App\newOrdersAdminAlert;
use App\OPSaferpayReference;
use App\orderServingDevices;
use Illuminate\Http\Request;
use SpryngApiHttpPhp\Client;
use App\payTecTransactionLog;
use App\cooksProductSelection;
use App\Events\addToCartAdmin;
use App\orderServingOrderShow;
use App\rechnungClientToBills;
use App\tablesAccessToWaiters;
use App\billsExpensesRecordRes;
use App\rechnungClientForMonth;
use App\accessControllForAdmins;
use App\displayAddForUser;
use App\Events\ActiveAdminPanel;
use App\tabVerificationPNumbers;
use App\Events\removePaidProduct;
use App\orderServingNotification;
use App\waiterActivityLogPassive;
use App\orderServingDevicesAccess;
use Cartalyst\Stripe\Api\Products;
use App\giftCardOnlinePayReference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\tabVerificationPNumbersPassive;
use Intervention\Image\ImageManagerStatic as Image;


class AdminPanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){return view('adminPanel/adminIndex');}
    public function index2(){return view('adminPanel/adminIndex');}
    public function index3(){return view('adminPanel/adminIndex');}
    public function indexList(){return view('adminPanel/adminIndex');}
    public function indexTakeaway(){return view('adminPanel/adminIndex');}
    public function indexDelivery(){return view('adminPanel/adminIndex');}
    public function statistics(){return view('adminPanel/adminIndex');}
    public function statisticsCanceled(){return view('adminPanel/adminIndex');}
    public function statBillsPage(){return view('adminPanel/adminIndex');}
    public function recomendet(){return view('adminPanel/adminIndex');}
    public function RechnungsverwaltungPage(){return view('adminPanel/adminIndex');}
    public function statusWorkerIndex(){return view('adminPanel/adminIndex');}
    public function covidsTelIndex(){return view('adminPanel/adminIndex');}
    public function indexContentMng(){return view('adminPanel/adminIndex');}
    public function categorizeReport(){return view('adminPanel/adminIndex');}
    // public function barazimiDitor(){return view('adminPanel/adminIndex');}
    public function waDailySalesPage(){return view('adminPanel/adminIndex');}

    public function admstatsDeletedTAProdsPage(){return view('adminPanel/adminIndex');}

    public function chngPayMethodForOrdersPage(){return view('adminPanel/adminIndex');}
    public function chngPayMethodForOrdersPageWa(){return view('adminPanelWaiter/adminIndexWaiter');}
    
    public function infoPageTableRez(){return view('infoTableRez');}
    public function notAccessToPage(){return view('accessNotAllowedAWP');}

    public function notificationsActPage(){ return view('adminPanel/adminIndex'); }

    public function billTabletIndex(){return view('adminPanel/adminIndex');}
    public function BillTabletsActive(){return view('billTablet.btFirstPage');}

    public function orServingPageOpen(){return view('orderServingPage/osIndex');}

    public function orderServingDevicesPage(){return view('adminPanel/adminIndex');}

    

    

    public function filterDate(Request $req){

        $this->validate($req, [
            'dateF' => 'required',
            'dateE' => 'required|after:dateF',
        ]);

        $orders = Orders::all()->sortByDesc("created_at");
        return view('adminPanel/adminIndex' , ['orders' => $orders, 'date1' => $req->dateF, 'date2' => $req->dateE]);
    }

 

    public function waiterCheckForCheckin(){
        if(Auth::user()->role == 55 && checkInOutReg::where([['theStat',0],['userId',Auth::user()->id]])->first() == Null){
            return 'CheckInNotValide';
        }else{
            return 'CheckInValide';
        }
    }



    public function getMoOrdersForRes(Request $req){
        $filteredData = Orders::where([['Restaurant',$req->resId],['statusi','!=','2']])
        ->whereMonth('created_at', $req->mon)
        ->whereYear('created_at', $req->yea)->get()->sortByDesc('created_at');
        return json_encode( $filteredData );
    }
 


    private function genShifra($res){
        $orSh = rand(1111,9999);
        $orShFi = $res.'|'.$orSh;

        if(Orders::whereDate('created_at', Carbon::today())->where([['shifra', $orShFi],['statusi','<','2']])->first() != NULL){
            return $this->genShifra($res);
        }else{
            return $orShFi;
        }        
    }

    private function genTheNewTabCode(){
        $newTab = mt_rand(100000, 999999);
        if(TabOrder::where('tabCode',$newTab)->count() > 0){
            return $this->genTheNewTabCode();
        }else{
            return $newTab; 
        }
    }







    public function generatePDF($id){
        $id2D = explode('||',$id);
        $details = ['title' => 'RestaurantDemo'];
        $item = Orders::find($id2D[0]);
        if($item == Null){
            $item = OrdersPassive::find($id2D[0]);
        }

        if($item->payM =="Rechnung"){
            // Auf rechnung pagesat

            $theExI = emailReceiptFromAdm::where('forOrder',$item->id)->first();
            $theRes = Restorant::find($item->Restaurant);
            $adr2D = explode(',',$theRes->adresa);
            $ad2 = '---';
            if(isset($adr2D[1])){ $ad2 = $adr2D[1]; }
            if(isset($adr2D[2])){ $ad2 = $adr2D[1].','.$adr2D[2]; }

            if($item->inCashDiscount > 0){ $totPrice = number_format($item->shuma-$item->inCashDiscount - $item->dicsountGcAmnt, 2, '.', '');
            }else if($item->inPercentageDiscount > 0){ $totPrice = number_format($item->shuma-($item->shuma*($item->inPercentageDiscount*0.01)) - $item->dicsountGcAmnt, 2, '.', '');
            }else{ $totPrice = number_format($item->shuma - $item->dicsountGcAmnt, 2, '.', ''); }
            
            $billNr = str_pad($item->id, 10, '0', STR_PAD_LEFT);

            $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
            shuffle($word);
            $name = substr(implode($word), 0, 25).'OrId'.$item->orId;
            $file = "storage/ebankqrcode/".$name.".png";
        
            $newQrcode = QRCode::text('SPC
0200
1
'.$theRes->resBankId.'
K
'.$theRes->emri.'
'.$adr2D[0].'
'.$ad2.'


CH







'.number_format($totPrice, 2, '.', '').'
CHF
K
'.$theExI->exInfoFirma.'
'.$theExI->exInfoStreet.'
'.$theExI->exInfoPlzOrt.' '.$theExI->exInfoLand.'


CH
NON

'.$billNr.'
EPD
')
            ->setSize(64)
            ->setMargin(0)
            ->setOutfile($file)
            ->png();

            $img1 = Image::make('storage/ebankqrcode/'.$name.'.png');
            $img1->insert('storage/ebankqrcode/eBPIcon.png');
            $img1->save('storage/ebankqrcode/'.$name.'.png');

            $theOrder = Orders::find($theExI->forOrder);
            if($theOrder != Null){
                $theOrder->ebankqrcode= $name.".png";
                $theOrder->save();
            }
            $theOrderP = OrdersPassive::find($theExI->forOrder);
            if($theOrderP != Null){
                $theOrderP->ebankqrcode= $name.".png";
                $theOrderP->save();
            }

            view()->share('items', $item);
            $pdf1 = PDF::loadView('adminInvoiceRechnung')->setPaper('a4', 'portrait');

            $docName = 'rechnungBillFirst'.Restorant::find($item->Restaurant)->emri.'_'.$item->id.'.pdf';
            $pdf1->save('storage/rechnungBillsFirst/'.$docName);

            $pdf2 = PDF::loadView('adminInvoiceRechnungFinal')->setPaper('a4', 'portrait');
            return $pdf2->download('rechnung_'.Restorant::find($item->Restaurant)->emri.'_'.$item->id.'.pdf');

        }else{
            // Set bill tablet as "bill downloadet"
            if($item->nrTable == 500){
                foreach(billTabletsReg::where('toStaffId',$item->servedBy)->get() as $billTabletOne){
                    $billTabletOne->showBillQRCode = 0;
                    $billTabletOne->save();
                }
            }

            $nrOfOrders19 = 0;
            $nrOfOrders19P = 0;
            $totExtra = 0;
            foreach(explode('---8---', $item->porosia) as $onOr){ $or2D = explode('-8-',$onOr);
                if(strlen($onOr[5]) > 19){$nrOfOrders19P++;
                }else{$nrOfOrders19++;}
                if($or2D[2] != 'empty' && $or2D[2] != ''){
                    if(str_contains($or2D[2], '--0--')){ $nrOfExt = count(explode('--0--',$or2D[2])); $totExtra = $totExtra + $nrOfExt;
                    }else{ $totExtra++; }
                }
            }
            if($item->digitalReceiptQRKHash == $id2D[1]){
                $customPaper = array(0,0,340.16,990+($nrOfOrders19P*32)+($nrOfOrders19*21)+($totExtra*12));
                view()->share('items', $item);
                $pdf = PDF::loadView('adminInvoice')->setPaper($customPaper, 'potrait');

                return $pdf->download(Restorant::find($item->Restaurant)->emri.'_'.$item->id.'.pdf');
            }
        }
    }

    public function generatePDFReceipt(Request $req){
        $item = Orders::find($req->orId);
        if($item == Null){
            $item = OrdersPassive::find($req->orId);
        }

        if($item->payM =="Rechnung"){
            // Auf rechnung pagesat

            $theExI = emailReceiptFromAdm::where('forOrder',$item->id)->first();
            $theRes = Restorant::find($item->Restaurant);
            $adr2D = explode(',',$theRes->adresa);
            $ad2 = '---';
            if(isset($adr2D[1])){ $ad2 = $adr2D[1]; }
            if(isset($adr2D[2])){ $ad2 = $adr2D[1].','.$adr2D[2]; }

            if($item->inCashDiscount > 0){ $totPrice = number_format($item->shuma-$item->inCashDiscount - $item->dicsountGcAmnt, 2, '.', '');
            }else if($item->inPercentageDiscount > 0){ $totPrice = number_format($item->shuma-($item->shuma*($item->inPercentageDiscount*0.01)) - $item->dicsountGcAmnt, 2, '.', '');
            }else{ $totPrice = number_format($item->shuma - $item->dicsountGcAmnt, 2, '.', ''); }
            
            $billNr = str_pad($item->id, 10, '0', STR_PAD_LEFT);

            $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
            shuffle($word);
            $name = substr(implode($word), 0, 25).'OrId'.$item->orId;
            $file = "storage/ebankqrcode/".$name.".png";
        
            $newQrcode = QRCode::text('SPC
0200
1
'.$theRes->resBankId.'
K
'.$theRes->emri.'
'.$adr2D[0].'
'.$ad2.'


CH







'.number_format($totPrice, 2, '.', '').'
CHF
K
'.$theExI->exInfoFirma.'
'.$theExI->exInfoStreet.'
'.$theExI->exInfoPlzOrt.' '.$theExI->exInfoLand.'


CH
NON

'.$billNr.'
EPD
')
            ->setSize(64)
            ->setMargin(0)
            ->setOutfile($file)
            ->png();

            $img1 = Image::make('storage/ebankqrcode/'.$name.'.png');
            $img1->insert('storage/ebankqrcode/eBPIcon.png');
            $img1->save('storage/ebankqrcode/'.$name.'.png');

            $theOrder = Orders::find($theExI->forOrder);
            if($theOrder != Null){
                $theOrder->ebankqrcode= $name.".png";
                $theOrder->save();
            }
            $theOrderP = OrdersPassive::find($theExI->forOrder);
            if($theOrderP != Null){
                $theOrderP->ebankqrcode= $name.".png";
                $theOrderP->save();
            }

            view()->share('items', $item);
            $pdf1 = PDF::loadView('adminInvoiceRechnung')->setPaper('a4', 'portrait');

            $docName = 'rechnungBillFirst'.Restorant::find($item->Restaurant)->emri.'_'.$item->id.'.pdf';
            $pdf1->save('storage/rechnungBillsFirst/'.$docName);

            $pdf2 = PDF::loadView('adminInvoiceRechnungFinal')->setPaper('a4', 'portrait');
            return $pdf2->download('rechnung_'.Restorant::find($item->Restaurant)->emri.'_'.$item->id.'.pdf');
        }else{
            $nrOfOrders19 = 0;
            $nrOfOrders19P = 0;
            $totExtra = 0;
            foreach(explode('---8---', $item->porosia) as $onOr){ $or2D = explode('-8-',$onOr);
                if(strlen($onOr[5]) > 19){$nrOfOrders19P++;
                }else{$nrOfOrders19++;}
                if($or2D[2] != 'empty' && $or2D[2] != ''){
                    if(str_contains($or2D[2], '--0--')){ $nrOfExt = count(explode('--0--',$or2D[2])); $totExtra = $totExtra + $nrOfExt;
                    }else{ $totExtra++; }
                }
            }
            $customPaper = array(0,0,340.16,990+($nrOfOrders19P*32)+($nrOfOrders19*21)+($totExtra*12));
            view()->share('items', $item);
            $pdf = PDF::loadView('adminInvoice')->setPaper($customPaper, 'potrait');
            
            return $pdf->download(Restorant::find($item->Restaurant)->emri.'_'.$item->id.'.pdf');
        }
    }

    public function getTheReceiptQrCodePic(Request $req){
        $order = Orders::find($req->id);

        if($order->digitalReceiptQRKHash == 'empty' || $order->digitalReceiptQRK == 'empty'){
            $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
            shuffle($word);
            $name = substr(implode($word), 0, 25).'OrId'.$order->id;
            $file = "storage/digitalReceiptQRK/".$name.".png";

            $word2 = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'));
            shuffle($word2);
            $hash = substr(implode($word2), 0, 128);

            $newQrcode = QRCode::URL('qrorpa.ch/generatePDF/'.$order->id.'||'.$hash)
            ->setSize(64)
            ->setMargin(0)
            ->setOutfile($file)
            ->png();

            $order->digitalReceiptQRKHash = $hash;
            $order->digitalReceiptQRK = $name.".png";
            $order->save();
        }
        return $order->digitalReceiptQRK.'-8-8-'.$order->nrTable.'-8-8-'.$order->refId;
    }





    public function sendActiveMSG(Request $req){
        event(new ActiveAdminPanel($req->resId));
    }






























    public function addNewProductOrPageStore(Request $req){
        // prodId: pId,
        // phoneN: $('#newOrPhNrSelected').val(),
        // tableN: $('#t').val(),
        // resN: $('#res').val(),
        // name: $('#ProdAddEmri'+pId).val(),
        // persh: $('#ProdAddPershk'+pId).val(),
        // sasia: $('#sasiaProd'+pId).val(),
        // qmimi: $('#ProdAddQmimi'+pId).val(),
        // ekstra: $('#ProdAddExtra'+pId).val(),
        // types: $('#ProdAddLlojet'+pId).val(),
        // komm: $('#komentMenuAjax'+pId).val(),
        // plate: $('#plateFor'+pId).val(),
        // _token: '{{csrf_token()}}'

            // Add as a tab order 
            $tableOfRes = TableQrcode::where([['tableNr',$req->tableN],['Restaurant',$req->resN]])->first();

            if($tableOfRes->kaTab != 0 && $tableOfRes->kaTab != -1){
                $tabCodeN = $tableOfRes->kaTab;
            }else{
                $tabCodeN = $this->genTheNewTabCode();
                $tableOfRes->kaTab = $tabCodeN;
                $tableOfRes->save();
            }

            // check if a cook has this product registered (in control)
            $addThPro = Produktet::findOrFail($req->prodId);
            $sasiaDone = (int)$req->sasia;
            $abrufenStat = -1;
            if(cooksProductSelection::where([['toRes',$req->resN],['contentType','Category'],['contentId',$addThPro->kategoria]])->first() != Null){
                $sasiaDone = 0;
                $abrufenStat = 0;
            }else{
                if(cooksProductSelection::where([['toRes',$req->resN],['contentType','Product'],['contentId',$addThPro->id]])->first() != Null){
                    $sasiaDone = 0;
                    $abrufenStat = 0;
                }else{
                    if($req->types != 'empty' && $req->types != ''){
                        $tyDt2D = explode('||',$req->types);
                        $theTypeToAdd = LlojetPro::where([['emri',$tyDt2D[0]],['vlera',$tyDt2D[1]]])->first();
                        if($theTypeToAdd != Null){
                            if(cooksProductSelection::where([['toRes',$req->resN],['contentType','Type'],['contentId',$theTypeToAdd->id]])->first() != Null){
                                $sasiaDone = 0;
                                $abrufenStat = 0;
                            }
                        }
                    }
                    if($sasiaDone != 0 && $req->ekstra != 'empty' && $req->ekstra != ''){
                        foreach(explode('--0--',$req->ekstra) as $oneExt){
                            $exDt2D = explode('||',$oneExt);
                            $theExtraToAdd = ekstra::where([['emri',$exDt2D[0]],['qmimi',$exDt2D[1]]])->first();
                            if($theExtraToAdd != Null){
                                if(cooksProductSelection::where([['toRes',$req->resN],['contentType','Extra'],['contentId',$theExtraToAdd->id]])->first() != Null){
                                    $sasiaDone = 0;
                                    $abrufenStat = 0;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            // ---------------------------------------------

            $newTabOrder = new TabOrder;

            $newTabOrder->tabCode = $tabCodeN;
            $newTabOrder->prodId = $req->prodId;
            $newTabOrder->OrderEmri = $req->name;
            $newTabOrder->tableNr = $req->tableN;
            $newTabOrder->toRes = $req->resN;
            $newTabOrder->OrderPershkrimi= $req->persh;
            $newTabOrder->OrderSasia = (int)$req->sasia;
            $newTabOrder->OrderSasiaDone = (int)$sasiaDone;
            $newTabOrder->OrderQmimi = (Float)$req->qmimi*(Float)$req->sasia;
            
            $newExtraRegister = 'empty';
            foreach(explode('--0--',$req->ekstra) as $exOne){
                if(!str_contains($exOne, '||') || $exOne == '' || $exOne == ' ' || $exOne == 'empty' || $exOne == null){
                }else{
                    if($newExtraRegister == 'empty'){
                        $newExtraRegister = $exOne; 
                    }else{
                        $newExtraRegister .= '--0--'.$exOne; 
                    }
                }
            }
            $newTabOrder->OrderExtra = $newExtraRegister;
            $newTabOrder->OrderType = ($req->types == '' ? 'empty' : $req->types);
            $newTabOrder->OrderKomenti = $req->komm;
            $newTabOrder->status = Auth::user()->tableOrAutoConfirm;
            $newTabOrder->toPlate = $req->plate;
            $newTabOrder->abrufenStat = $abrufenStat;

            

            $newTabOrder->save();

            // waiter Log
            if(Auth::User()->role == 55 || Auth::User()->role == 5){
                $waLog = new waiterActivityLog();
                $waLog->waiterId = Auth::User()->id;
                $waLog->actType = 'newProdWa';
                $waLog->actId = $newTabOrder->id;
                $waLog->save();
            }

            if($req->phoneN == 0){
                $savePhoneN = "0770000000";
            }else{
                $savePhoneN = $req->phoneN;
                // $sendToEv = $req->resN.'||'.$req->tableN.'||'.$req->phoneN.'||'.$newTabOrder->id;
                // event(new addToCartAdmin($sendToEv));

                $phoneNrActive = array();
                foreach(tabVerificationPNumbers::where([['tabCode',$tabCodeN],['status','1']])->get() as $nrVers){
                    if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){
                        array_push($phoneNrActive,$nrVers->phoneNr);
        
                        // Register a new notifyClient
                        $newNotifyClient = new notifyClient();
                        $newNotifyClient->for = "addToCartAdmin";
                        $newNotifyClient->toRes = $req->resN;
                        $newNotifyClient->tableNr = $req->tableN;
                        $newNotifyClient->clPhoneNr = $nrVers->phoneNr;
                        $newNotifyClient->data = json_encode([
                            'phoneNrNotifyFor' => $nrVers->phoneNr,
                            'phoneNrOrderFor' => $req->phoneN,
                            'newOrId' => $newTabOrder->id,
                        ]);
                        $newNotifyClient->readInd = 0;
                        $newNotifyClient->save();
                    }
                }
            }

            $newTabOrder->usrPhNr = $savePhoneN;
            $newTabOrder->save();

            // Save the number ....
            $newNrVerification = new tabVerificationPNumbers;
            $newNrVerification->phoneNr = $savePhoneN;
            $newNrVerification->tabCode = $tabCodeN;
            $newNrVerification->tabOrderId = $newTabOrder->id;
            $newNrVerification->status = 1;
            $newNrVerification->save();

            // Save the notification for GHOST CART / if a ghostCart is active in this Restaurant/Table 
            if(ghostCartInUse::where([['toRes', $req->resN],['tableNr', $req->tableN],['status','0']])->get()->count() > 0){
                $newClNotify = new notifyClient;
                $newClNotify->for = 'ghostCartRefresh';
                $newClNotify->toRes = $req->resN;
                $newClNotify->tableNr = $req->tableN;
                $newClNotify->data = '';
                $newClNotify->save();
            }

        
        // Send Notifications for the Admin
        foreach(User::where([['sFor',$req->resN],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                $details = [
                    'id' => $newTabOrder->id,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $req->tableN
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));

                if(newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$req->tableN],['tabOrderId',$newTabOrder->id],['statActive','1']])->first() == NULL){
                    $newAdmAlert = new newOrdersAdminAlert();
                    $newAdmAlert->adminId = $user->id;
                    $newAdmAlert->tableNr = $req->tableN;
                    $newAdmAlert->toRes = $req->resN;
                    $newAdmAlert->tabOrderId = $newTabOrder->id;
                    $newAdmAlert->statActive = 1;
                    $newAdmAlert->save();
                }
            }
        }
        foreach(User::where([['sFor',$req->resN],['role','55']])->get() as $oneWaiter){
            if($oneWaiter->id != auth()->user()->id){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->tableN]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $newTabOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $req->tableN
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));

                    if(newOrdersAdminAlert::where([['adminId',$oneWaiter->id],['tableNr',$req->tableN],['tabOrderId',$newTabOrder->id],['statActive','1']])->first() == NULL){
                        $newAdmAlert = new newOrdersAdminAlert();
                        $newAdmAlert->adminId = $oneWaiter->id;
                        $newAdmAlert->tableNr = $req->tableN;
                        $newAdmAlert->toRes = $req->resN;
                        $newAdmAlert->tabOrderId = $newTabOrder->id;
                        $newAdmAlert->statActive = 1;
                        $newAdmAlert->save();
                    }
                }
            }
        }

        if(Auth::user()->tableOrAutoConfirm == 1){
            // Send Notifications for the Cooks
            foreach(User::where([['sFor',$newTabOrder->toRes],['role','54']])->get() as $oneCook){
                $details = [
                    'id' => $newTabOrder->id,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $newTabOrder->tableNr
                ];
                $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }

        if($newTabOrder->OrderKomenti == Null){$tabOrComm = 'empty';
        }else{ $tabOrComm = $newTabOrder->OrderKomenti; }

        $waLog = waiterActivityLog::where([['actType','newProdWa'],['actId',$newTabOrder->id]])->first();
        if($waLog != Null ){ $waiterDataName = User::find($waLog->waiterId)->name; }else{ $waiterDataName = 'Administrator'; }

        $thePlateOfTO = resPlates::find($newTabOrder->toPlate);
        if($thePlateOfTO != Null){ $thePlate = $thePlateOfTO->nameTitle;
        }else{ $thePlate = 'none'; }

        if($newTabOrder->OrderExtra != 'empty' && $newTabOrder->OrderExtra != Null){ $extraToShow = $newTabOrder->OrderExtra;
        }else{ $extraToShow = 'empty'; }
                                                                                                
        $showProdData = $req->tableN.'-||-'.$newTabOrder->id.'-||-'.$newTabOrder->status.'-||-'.$newTabOrder->OrderQmimi.'-||-'.
        $newTabOrder->created_at.'-||-'.$newTabOrder->OrderSasia.'-||-'.$newTabOrder->OrderEmri.'-||-'.$newTabOrder->OrderPershkrimi.'-||-'.
        $newTabOrder->OrderType.'-||-'.$tabOrComm.'-||-'.$waiterDataName.'-||-'.$thePlate.'-||-'.$newTabOrder->tabCode.'-||-'.$extraToShow.'-||-'.
        $newTabOrder->OrderSasiaDone.'-||-'.$newTabOrder->usrPhNr.'-||-'.$newTabOrder->toPlate.'-||-'.$newTabOrder->abrufenStat;

        return $showProdData;
    }



    public function addToCartAdminsNewOrderToMe(Request $req){
        // tabOId: d2d[4],
        $to = TabOrder::find($req->tabOId);

        if($to->OrderExtra == 'empty'){ $saveExtra = ''; }else{ $saveExtra = $to->OrderExtra;}
        if($to->OrderType == 'empty'){ $saveType = ''; }else{ $saveType = $to->OrderType;}
        
        Cart::add($to->prodId, $to->OrderEmri, (int)$to->OrderSasia, $to->OrderQmimi, ['ekstras' => $saveExtra, 'persh' => $to->OrderPershkrimi, 'type' => $saveType, 'koment' => $to->OrderKomenti, 'tabOId' => $to->id])->associate('App\Produktet');
    }


    public function removePaidProductCart(Request $req){
        // tabOId: d2d[1],
        foreach(Cart::content() as $item){
            if($item->options->tabOId == $req->tabOId){
                Cart::remove($item->rowId);
            }
        }
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }  
        if(count(Cart::content()) <= 0){    
            if(!Auth::check()){
                unset($_SESSION['phoneNrVerified']);
            }
        }
    }

    public function removePaidProductCart2(Request $req){
        // tabOId: d2d[1],
        foreach(Cart::content() as $item){
            if($item->options->tabOId == $req->tabOId){
                Cart::remove($item->rowId);
            }
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }  

        if(count(Cart::content()) <= 0){
            if(!Auth::check()){
                unset($_SESSION['phoneNrVerified']);
            }
        }

        if(TabOrder::find($req->tabOId) != NULL){
            TabOrder::find($req->tabOId)->delete();
        }
        if(tabVerificationPNumbers::where('tabOrderId',$req->tabOId)->first() != NULL){
            tabVerificationPNumbers::where('tabOrderId',$req->tabOId)->first()->delete();
        }
    }



    public function closeAllProductsTab(Request $req){
        // tableNr: tNr,
        // resId: resId,
        // _token: '{{csrf_token()}}'
        $newOrder = new Orders;
        $tabCode = TableQrcode::where([['Restaurant',$req->resId],['tableNr',$req->tableNr]])->first()->kaTab;

        if($tabCode != 0 && $tabCode != -1){
            $saveOrderAll = '';
            $totalPay = 0 ;
            $AllFromTab = TabOrder::where([['tableNr',$req->tableNr],['toRes',$req->resId],['tabCode',$tabCode],['status','!=','9']])->get();

            $clientsActive = array();
            $tabOrdersToR = array();

            foreach($AllFromTab as $tOr){
                // Deactivate phone Nr verification 
                $pnvRecord = tabVerificationPNumbers::where('tabOrderId',$tOr->id)->first();
                $pnvRecord->status = 0;
                $pnvRecord->save();

                if(!in_array($pnvRecord->phoneNr,$clientsActive)){
                    array_push($clientsActive,$pnvRecord->phoneNr);
                }
                array_push($tabOrdersToR,$pnvRecord->phoneNr.'||'.$tOr->id);
                //---------------------------------------------------------------------------------------
                // Pergaditja e porosive per regjistrim 
                if($tOr->OrderType != null){ $regType = explode('||',$tOr->OrderType)[0]; }else{  $regType = ''; }
                if($req->tableNr == 500){
                    $theP = Produktet::find($tOr->prodId);
                    if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
                }else{
                    $theP = Produktet::find($tOr->prodId);
                    if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
                }
                if($saveOrderAll != ''){
                    $saveOrderAll .= "---8---".$tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$tOr->OrderSasia.'-8-'.$tOr->OrderQmimi
                    .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
                }else{
                    $saveOrderAll = $tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$tOr->OrderSasia.'-8-'.$tOr->OrderQmimi
                    .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
                }
                //---------------------------------------------------------------------------------------
                if($tOr->status != 9 ){
                    $totalPay += (float)$tOr->OrderQmimi;
                }
                $tOr->tabCode = 0;
                $tOr->status = 1;
                $tOr->save();
            }

            if($req->tvsh == 2.60){
                $newOrder->nrTable = '500';
            }else if($req->tvsh == 8.10){
                $newOrder->nrTable = $req->tableNr;
            }else{
                $newOrder->nrTable = $req->tableNr;
            }
            $newOrder->statusi = 3;
            $newOrder->byId = 0;
            $newOrder->userEmri = "admin";
            $newOrder->userEmail = "admin";
            if($saveOrderAll == ''){$newOrder->porosia = 'empty'; }else{$newOrder->porosia = $saveOrderAll;} //$request->userPorosia;
            $newOrder->resComment = $req->resComment;
            if(isset($req->thePayM)){
                $newOrder->payM = $req->thePayM;
            }else{
                $newOrder->payM = 'Admin';
            }
            if(isset($req->tipp)){
                $newOrder->shuma =number_format($totalPay + number_format((float)$req->tipp, 2, '.', ''), 2, '.', ''); //$request->Shuma
            }else{
                $newOrder->shuma =number_format($totalPay, 2, '.', ''); //$request->Shuma
            }
            $newOrder->Restaurant = $req->resId;
            $newOrder->userPhoneNr = "0770000000";
            if(isset($req->tipp)){
                $bakshishi =number_format((float)$req->tipp, 2, '.', '') ;
                $newOrder->tipPer = $bakshishi;
            }else{
                $newOrder->tipPer = 0;
            }
            $newOrder->TAemri = 'empty';
            $newOrder->TAmbiemri = 'empty';
            $newOrder->TAtime = 'empty';
            $newOrder->cuponOffVal = 0;
            $newOrder->TAplz = 'empty';
            $newOrder->TAort = 'empty';
            $newOrder->TAaddress = 'empty';
            $newOrder->TAkoment = 'empty';
            $newOrder->discReason = $req->disReason;
            $newOrder->inCashDiscount = $req->cashDis;
            $newOrder->inPercentageDiscount = $req->percDis;
            $newOrder->dicsountGcAmnt = $req->discGCAmnt;
            $newOrder->mwstVal = $req->tvsh;
            $newOrder->servedBy = Auth::user()->id;
            
            $refIfOrPa = OrdersPassive::where('Restaurant',$req->resId)->max('refId') + 1;
            $refIfOr = Orders::where('Restaurant',$req->resId)->max('refId') + 1;
            $nextRefId = 0;
            if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
            $newOrder->refId = $nextRefId;
            $newOrder->save();

            // Register Gift Card use
            if($req->discGCId != 0){
                $TheGC = giftCard::find($req->discGCId);
                if($TheGC != Null){
                    $TheGC->gcSumInChfUsed = number_format($TheGC->gcSumInChfUsed + $req->discGCAmnt,2,'.','');
                    if($TheGC->usedInOrdersId != 'empty'){ $TheGC->usedInOrdersId = $TheGC->usedInOrdersId.'|||'.$newOrder->id;
                    }else{ $TheGC->usedInOrdersId = $newOrder->id; }
                    $TheGC->save();
                }
            }
            // ---------------------------------------------------------------


            // PayTec LOG register
            if($req->thePayM == 'Kartenzahlung' && $req->payTecTrx != 'none'){
                // $req->payTecTrx
                $payTecTrx = json_decode($req->payTecTrx);
                $payTecLog = new payTecTransactionLog();
                $payTecLog->orderId = $newOrder->id;
                $payTecLog->toRes = Auth::user()->sFor;
                if(isset($payTecTrx->TrmID)){ $payTecLog->TrmID = $payTecTrx->TrmID; }
                if(isset($payTecTrx->TrxResult)){ $payTecLog->TrxResult = $payTecTrx->TrxResult; }
                if(isset($payTecTrx->Brand)){ $payTecLog->Brand = $payTecTrx->Brand; }                    
                if(isset($payTecTrx->VoicePhone)){ $payTecLog->VoicePhone = $payTecTrx->VoicePhone; }
                if(isset($payTecTrx->TrxRefNum)){ $payTecLog->TrxRefNum = $payTecTrx->TrxRefNum; }
                if(isset($payTecTrx->AccountType)){ $payTecLog->AccountType = $payTecTrx->AccountType; }
                if(isset($payTecTrx->AcqID)){ $payTecLog->AcqID = $payTecTrx->AcqID; }
                if(isset($payTecTrx->AID)){ $payTecLog->AID = $payTecTrx->AID; }
                if(isset($payTecTrx->AIDICC)){ $payTecLog->AIDICC = $payTecTrx->AIDICC; }
                if(isset($payTecTrx->AmtAuth)){ $payTecLog->AmtAuth = $payTecTrx->AmtAuth; }
                if(isset($payTecTrx->AuthC)){ $payTecLog->AuthC = $payTecTrx->AuthC; }
                if(isset($payTecTrx->ARC)){ $payTecLog->ARC = $payTecTrx->ARC; }
                if(isset($payTecTrx->CVMResults)){ $payTecLog->CVMResults = $payTecTrx->CVMResults; }
                if(isset($payTecTrx->IssCntryC)){ $payTecLog->IssCntryC = $payTecTrx->IssCntryC; }
                if(isset($payTecTrx->POSEntryMode)){ $payTecLog->POSEntryMode = $payTecTrx->POSEntryMode; }
                if(isset($payTecTrx->TrxAmt)){ $payTecLog->TrxAmt = $payTecTrx->TrxAmt; }
                if(isset($payTecTrx->TrxCurrC)){ $payTecLog->TrxCurrC = $payTecTrx->TrxCurrC; }
                if(isset($payTecTrx->TrxType)){ $payTecLog->TrxType = $payTecTrx->TrxType; }
                if(isset($payTecTrx->TrxSeqCnt)){ $payTecLog->TrxSeqCnt = $payTecTrx->TrxSeqCnt; }
                if(isset($payTecTrx->TrxDate)){ $payTecLog->TrxDate = $payTecTrx->TrxDate; }
                if(isset($payTecTrx->TrxTime)){ $payTecLog->TrxTime = $payTecTrx->TrxTime; }
                if(isset($payTecTrx->AuthReslt)){ $payTecLog->AuthReslt = $payTecTrx->AuthReslt; }
                if(isset($payTecTrx->AppPANEnc)){ $payTecLog->AppPANEnc = $payTecTrx->AppPANEnc; }
                if(isset($payTecTrx->StatKeyPANRctInd)){ $payTecLog->StatKeyPANRctInd = $payTecTrx->StatKeyPANRctInd; }
                if(isset($payTecTrx->KeyPANRctDOLInd)){ $payTecLog->KeyPANRctDOLInd = $payTecTrx->KeyPANRctDOLInd; }
                if(isset($payTecTrx->DisplayName)){ $payTecLog->DisplayName = $payTecTrx->DisplayName; }
                if(isset($payTecTrx->TrxResultExtended)){ $payTecLog->TrxResultExtended = $payTecTrx->TrxResultExtended; }
                if(isset($payTecTrx->IIN)){ $payTecLog->IIN = $payTecTrx->IIN; }
                if(isset($payTecTrx->AppPANPrtCardholder)){ $payTecLog->AppPANPrtCardholder = $payTecTrx->AppPANPrtCardholder; }
                if(isset($payTecTrx->AppPANPrtAttendant)){ $payTecLog->AppPANPrtAttendant = $payTecTrx->AppPANPrtAttendant; }
                if(isset($payTecTrx->SurrogatePAN)){ $payTecLog->SurrogatePAN = $payTecTrx->SurrogatePAN; }
                if(isset($payTecTrx->CardholderText)){ $payTecLog->CardholderText = $payTecTrx->CardholderText; }
                if(isset($payTecTrx->AttendantText)){ $payTecLog->AttendantText = $payTecTrx->AttendantText; }
                if(isset($payTecTrx->TipAmt)){ $payTecLog->TipAmt = $payTecTrx->TipAmt; }
                if(isset($payTecTrx->AmtRemaining)){ $payTecLog->AmtRemaining = $payTecTrx->AmtRemaining; }
                $payTecLog->save();
            }

            //---------------------------------------------------------------

            // waiter LOG
            if((Auth::User()->role == 55 || Auth::User()->role == 5) && $newOrder->porosia != 'empty'){
                $waLog = new waiterActivityLog();
                $waLog->waiterId = Auth::User()->id;
                $waLog->actType = 'orderCloseWA';
                $waLog->actId = $newOrder->id;

                $ctOr = (int)0;
                foreach(explode('---8---',$newOrder->porosia) as $op){
                    $ctOr = $ctOr + (int)explode('-8-',$op)[3];
                }
                $waLog->sasia = $ctOr;
                $waLog->save();

                $newOrder->orForWaiter = Auth::User()->id;
            }
            // ---------------------------------

            // Code per tip
            if(isset($req->tipp)){
                $newTip = new TipLog;

                if($req->cashDis > 0){
                    $skontoCHF = number_format($req->cashDis,2,'.','');
                    $toPay = number_format($totalPay - $skontoCHF,2,'.','');
                  }else if($req->percDis > 0){
                      $skontoCHF = number_format($totalPay*($req->percDis/100),2,'.','');
                      $toPay = number_format($totalPay - $skontoCHF,2,'.','');
                  }else{
                      $toPay = number_format($totalPay,2,'.','');
                  } 

                $newTip->shumaPor = number_format($toPay + number_format((float)$req->tipp, 2, '.', ''), 2, '.', '');
                $newTip->tipPer = 'Empty';
                $newTip->tipTot = number_format((float)$req->tipp, 2, '.', '');
                $newTip->toRes = $req->resId;
                if(Auth::check()){
                    $newTip->klienti = Auth::user()->id;
                }else{
                    $newTip->klienti = 9999999;
                }
                $newTip->save();
            }

            // Gen the next indentifikation number (shifra - per Orders) 
            $orderSh = $this->genShifra($req->resId);
            $newOrder->shifra = $orderSh;
            $newOrder->save();
            // ------------------------------------------------------------



            $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
            shuffle($word);
            $name = substr(implode($word), 0, 25).'OrId'.$newOrder->id;
            $file = "storage/digitalReceiptQRK/".$name.".png";

            $word2 = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'));
            shuffle($word2);
            $hash = substr(implode($word2), 0, 128);

            $newQrcode = QRCode::URL('qrorpa.ch/generatePDF/'.$newOrder->id.'||'.$hash)
            ->setSize(64)
            ->setMargin(0)
            ->setOutfile($file)
            ->png();

            $newOrder->digitalReceiptQRKHash = $hash;
            $newOrder->digitalReceiptQRK = $name.".png";
            $newOrder->save();

            $tableGet = TableQrcode::where([['Restaurant',$req->resId],['tableNr',$req->tableNr]])->first();
            $tableGet->kaTab = 0;
            $tableGet->save();


            foreach($clientsActive as $clActiveOne){
                if(str_contains($clActiveOne,'|')){
                    // $request->t $request->Res
                    $clPhoneNr2D = explode('|',$clActiveOne);
                    $findGCActive = ghostCartInUse::where([['toRes',$req->resId],['tableNr',$req->tableNr],['indNr2',$clPhoneNr2D[1]],['status','0']])->first();
                    if($findGCActive != NULL){
                        $findGCActive->status = 1;
                        $findGCActive->save();
                    }
                }

                if($clActiveOne != '0770000000'){
                    $tabOrdersForUs = '';
                    foreach($tabOrdersToR as $oneTOTR){
                        $oneTOTR2D = explode('||',$oneTOTR);
                        if($oneTOTR2D[0] == $clActiveOne){
                            if($tabOrdersForUs == ''){
                                $tabOrdersForUs = $oneTOTR2D[1];
                            }else{
                                $tabOrdersForUs .= '--9--'.$oneTOTR2D[1];
                            }
                        }
                    }
                    // $sendRemoveProd = $pnvRecord->phoneNr.'||'.$tOr->id.'||a||'.$req->resId.'||'.$req->tableNr;
                    // event(new removePaidProduct($sendRemoveProd));

                    // Register a new notifyClient
                    $newNotifyClient = new notifyClient();
                    $newNotifyClient->for = "removePaidProduct";
                    $newNotifyClient->toRes = $req->resId;
                    $newNotifyClient->tableNr = $req->tableNr;
                    $newNotifyClient->clPhoneNr = $clActiveOne;
                    $newNotifyClient->data = json_encode([
                        'tabOrId' => $tabOrdersForUs,
                        'orderId' => $newOrder->id,
                        'type' => 'a'
                    ]);
                    $newNotifyClient->readInd = 0;
                    $newNotifyClient->save();
                }
            }


            // Send Notifications for the Admin
            foreach(User::where([['sFor',$req->resId],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $req->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            foreach(User::where([['sFor',$req->resId],['role','55']])->get() as $oneWaiter){
                if($oneWaiter->id != auth()->user()->id){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->tableNr]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $req->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
            }
            foreach(User::where([['sFor',$req->resId],['role','54']])->get() as $oneCook){
                $details = [
                    'id' => 0,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $req->tableNr
                ];
                $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
            }

            $newOrAlertToDel = newOrdersAdminAlert::where([['toRes',$req->resId],['tableNr',$req->tableNr],['statActive','1']])->get();
            if($newOrAlertToDel != NULL){
                foreach($newOrAlertToDel as $newOrAlertToDelOne){
                    $newOrAlertToDelOne->delete();
                }
            }

            return $newOrder->id;
        }
    }









    

    public function closeSelectedProductsTab(Request $req){
        // tableNr: tNr,
        // resId: resId,
        // selProds : $('#closeOrSelected'+tNr).val(),
        // _token: '{{csrf_token()}}'

        $newOrder = new Orders;

        $tabCode = TableQrcode::where([['Restaurant',$req->resId],['tableNr',$req->tableNr]])->first()->kaTab;

        if($tabCode != 0 && $tabCode != -1){
            $saveOrderAll = '';
            $totalPay = 0 ;
            $allSelOr = array();
            foreach(explode('||',$req->selProds) as $prodsToPay){
                $prodsToPay2D = explode('-8-',$prodsToPay);
                array_push($allSelOr,$prodsToPay2D[0]);
            }
            $AllFromTab = TabOrder::where([['tableNr',$req->tableNr],['toRes',$req->resId],['status','!=','9'],['tabCode',$tabCode]])->whereIn('id',$allSelOr)->get();

            $clientsActive = array();
            $tabOrdersToR = array();

            foreach($AllFromTab as $tOr){
                // Nxirret sasia e selektuar 
                $sasiaSelected = 0;
                $qmimiOfSelected = number_format(0, 2, '.', '');
                $tabOrIdSelected = 0;
                foreach(explode('||',$req->selProds) as $prodsToPay){
                    $prodsToPay2D = explode('-8-',$prodsToPay);
                    if($tOr->id == $prodsToPay2D[0]){
                        $sasiaSelected = $prodsToPay2D[1];
                    }
                }
                if($sasiaSelected == $tOr->OrderSasia){
                    // Selected ALL
                    $qmimiOfSelected = number_format($tOr->OrderQmimi, 2, '.', '');
                    $tabOrIdSelected = $tOr->id;
                }else{
                    // Selected SOME
                    $priceForOneSelTabProd = number_format($tOr->OrderQmimi/$tOr->OrderSasia, 2, '.', '');
                    $qmimiOfSelected = number_format($priceForOneSelTabProd * $sasiaSelected, 2, '.', '');

                    $tOr->OrderSasia = $tOr->OrderSasia - $sasiaSelected;
                    $tOr->OrderQmimi = number_format($tOr->OrderQmimi - $qmimiOfSelected, 2, '.', '');
                    if($tOr->OrderSasiaDone >= $sasiaSelected){
                        $tOr->OrderSasiaDone = $tOr->OrderSasiaDone - $sasiaSelected;
                    }
                    $tOr->save();

                    // Save extra TAB order ....
                    $newTabOrder = new TabOrder;
                    $newTabOrder->tabCode = $tOr->tabCode;
                    $newTabOrder->prodId = $tOr->prodId;
                    $newTabOrder->OrderEmri = $tOr->OrderEmri;
                    $newTabOrder->tableNr = $tOr->tableNr;
                    $newTabOrder->toRes = $tOr->toRes;
                    $newTabOrder->OrderPershkrimi= $tOr->OrderPershkrimi;
                    $newTabOrder->OrderSasia = $sasiaSelected;
                    $newTabOrder->OrderSasiaDone = $sasiaSelected;
                    $newTabOrder->OrderQmimi = number_format($qmimiOfSelected, 2, '.', '');
                    $newTabOrder->OrderExtra = $tOr->OrderExtra;
                    $newTabOrder->OrderType = $tOr->OrderType;
                    $newTabOrder->OrderKomenti = $tOr->OrderKomenti;
                    $newTabOrder->status = 1;
                    $newTabOrder->toPlate = $tOr->toPlate;
                    $newTabOrder->abrufenStat = $tOr->abrufenStat;
                    $newTabOrder->save();

                    $theNrVer = tabVerificationPNumbers::where('tabOrderId',$tOr->id)->first();
                    // Save the number ....
                    $newNrVerification = new tabVerificationPNumbers;
                    $newNrVerification->phoneNr = $theNrVer->phoneNr;
                    $newNrVerification->tabCode = $theNrVer->tabCode;
                    $newNrVerification->tabOrderId = $newTabOrder->id;
                    $newNrVerification->status = 1;
                    $newNrVerification->save();

                    $newTabOrder->usrPhNr = $theNrVer->phoneNr;
                    $newTabOrder->save();

                    $tabOrIdSelected = $newTabOrder->id;

                }
                //---------------------------------------------------------------------------------------

                
                // Deactivate phone Nr verification 
                $pnvRecord = tabVerificationPNumbers::where('tabOrderId',$tabOrIdSelected)->first();
                $pnvRecord->status = 0;
                $pnvRecord->save();
                
                if(!in_array($pnvRecord->phoneNr,$clientsActive)){
                    array_push($clientsActive,$pnvRecord->phoneNr);
                }
                array_push($tabOrdersToR,$pnvRecord->phoneNr.'||'.$tabOrIdSelected);
                //---------------------------------------------------------------------------------------

                

                // Pergaditja e porosive per regjistrim 
                if($tOr->OrderType != null){ $regType = explode('||',$tOr->OrderType)[0]; }else{  $regType = ''; }
                if($req->tableNr == 500){
                    $theP = Produktet::find($tOr->prodId);
                    if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
                }else{
                    $theP = Produktet::find($tOr->prodId);
                    if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
                }
                if($saveOrderAll != ''){
                    $saveOrderAll .= "---8---".$tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$sasiaSelected.'-8-'.$qmimiOfSelected
                    .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
                }else{
                    $saveOrderAll = $tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$sasiaSelected.'-8-'.$qmimiOfSelected
                    .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
                }
                //---------------------------------------------------------------------------------------
                $tabOrderSold = TabOrder::find($tabOrIdSelected);
                if($tabOrderSold->status != 9 ){
                    $totalPay += number_format($qmimiOfSelected, 2, '.', '');
                }
                $tabOrderSold->tabCode = 0;
                $tabOrderSold->status = 1;
                $tabOrderSold->save();
                
            }

    

            if($req->tvsh == 2.60){
                $newOrder->nrTable = '500';
            }else if($req->tvsh == 8.10){
                $newOrder->nrTable = $req->tableNr;
            }else{
                $newOrder->nrTable = $req->tableNr;
            }
            $newOrder->statusi = 3;
            $newOrder->byId = 0;
            $newOrder->userEmri = "admin";
            $newOrder->userEmail = "admin";
            if($saveOrderAll == ''){$newOrder->porosia = 'empty'; }else{$newOrder->porosia = $saveOrderAll;} //$request->userPorosia;
            $newOrder->resComment = $req->resComment;
            if(isset($req->thePayM)){
                $newOrder->payM = $req->thePayM;
            }else{
                $newOrder->payM = 'Admin';
            }
            if(isset($req->tipp)){
                $newOrder->shuma =number_format($totalPay + number_format((float)$req->tipp, 2, '.', ''), 2, '.', ''); //$request->Shuma
            }else{
                $newOrder->shuma =number_format($totalPay, 2, '.', ''); //$request->Shuma
            }
            $newOrder->Restaurant = $req->resId;
            $newOrder->userPhoneNr = "0770000000";
            if(isset($req->tipp)){
                $bakshishi =number_format((float)$req->tipp, 2, '.', '') ;
                $newOrder->tipPer = $bakshishi;
            }else{
                $newOrder->tipPer = 0;
            }
            $newOrder->TAemri = 'empty';
            $newOrder->TAmbiemri = 'empty';
            $newOrder->TAtime = 'empty';
            $newOrder->cuponOffVal = 0;
            $newOrder->TAplz = 'empty';
            $newOrder->TAort = 'empty';
            $newOrder->TAaddress = 'empty';
            $newOrder->TAkoment = 'empty';
            $newOrder->discReason = $req->disReason;
            $newOrder->inCashDiscount = $req->cashDis;
            $newOrder->inPercentageDiscount = $req->percDis;
            $newOrder->dicsountGcAmnt = $req->discGCAmnt;
            $newOrder->mwstVal = $req->tvsh;
            $newOrder->servedBy = Auth::user()->id;
            $refIfOrPa = OrdersPassive::where('Restaurant',$req->resId)->max('refId') + 1;
            $refIfOr = Orders::where('Restaurant',$req->resId)->max('refId') + 1;
            $nextRefId = 0;
            if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
            $newOrder->refId = $nextRefId;
            $newOrder->save();

            // Register Gift Card use
            if($req->discGCId != 0){
                $TheGC = giftCard::find($req->discGCId);
                if($TheGC != Null){
                    $TheGC->gcSumInChfUsed = number_format($TheGC->gcSumInChfUsed + $req->discGCAmnt,2,'.','');
                    if($TheGC->usedInOrdersId != 'empty'){ $TheGC->usedInOrdersId = $TheGC->usedInOrdersId.'|||'.$newOrder->id;
                    }else{ $TheGC->usedInOrdersId = $newOrder->id; }
                    $TheGC->save();
                }
            }
            //---------------------------------------------------------------


            // PayTec LOG register
            if($req->thePayM == 'Kartenzahlung' && $req->payTecTrx != 'none'){
                // $req->payTecTrx
                $payTecTrx = json_decode($req->payTecTrx);
                $payTecLog = new payTecTransactionLog();
                $payTecLog->orderId = $newOrder->id;
                $payTecLog->toRes = Auth::user()->sFor;
                if(isset($payTecTrx->TrmID)){ $payTecLog->TrmID = $payTecTrx->TrmID; }
                if(isset($payTecTrx->TrxResult)){ $payTecLog->TrxResult = $payTecTrx->TrxResult; }
                if(isset($payTecTrx->Brand)){ $payTecLog->Brand = $payTecTrx->Brand; }                    
                if(isset($payTecTrx->VoicePhone)){ $payTecLog->VoicePhone = $payTecTrx->VoicePhone; }
                if(isset($payTecTrx->TrxRefNum)){ $payTecLog->TrxRefNum = $payTecTrx->TrxRefNum; }
                if(isset($payTecTrx->AccountType)){ $payTecLog->AccountType = $payTecTrx->AccountType; }
                if(isset($payTecTrx->AcqID)){ $payTecLog->AcqID = $payTecTrx->AcqID; }
                if(isset($payTecTrx->AID)){ $payTecLog->AID = $payTecTrx->AID; }
                if(isset($payTecTrx->AIDICC)){ $payTecLog->AIDICC = $payTecTrx->AIDICC; }
                if(isset($payTecTrx->AmtAuth)){ $payTecLog->AmtAuth = $payTecTrx->AmtAuth; }
                if(isset($payTecTrx->AuthC)){ $payTecLog->AuthC = $payTecTrx->AuthC; }
                if(isset($payTecTrx->ARC)){ $payTecLog->ARC = $payTecTrx->ARC; }
                if(isset($payTecTrx->CVMResults)){ $payTecLog->CVMResults = $payTecTrx->CVMResults; }
                if(isset($payTecTrx->IssCntryC)){ $payTecLog->IssCntryC = $payTecTrx->IssCntryC; }
                if(isset($payTecTrx->POSEntryMode)){ $payTecLog->POSEntryMode = $payTecTrx->POSEntryMode; }
                if(isset($payTecTrx->TrxAmt)){ $payTecLog->TrxAmt = $payTecTrx->TrxAmt; }
                if(isset($payTecTrx->TrxCurrC)){ $payTecLog->TrxCurrC = $payTecTrx->TrxCurrC; }
                if(isset($payTecTrx->TrxType)){ $payTecLog->TrxType = $payTecTrx->TrxType; }
                if(isset($payTecTrx->TrxSeqCnt)){ $payTecLog->TrxSeqCnt = $payTecTrx->TrxSeqCnt; }
                if(isset($payTecTrx->TrxDate)){ $payTecLog->TrxDate = $payTecTrx->TrxDate; }
                if(isset($payTecTrx->TrxTime)){ $payTecLog->TrxTime = $payTecTrx->TrxTime; }
                if(isset($payTecTrx->AuthReslt)){ $payTecLog->AuthReslt = $payTecTrx->AuthReslt; }
                if(isset($payTecTrx->AppPANEnc)){ $payTecLog->AppPANEnc = $payTecTrx->AppPANEnc; }
                if(isset($payTecTrx->StatKeyPANRctInd)){ $payTecLog->StatKeyPANRctInd = $payTecTrx->StatKeyPANRctInd; }
                if(isset($payTecTrx->KeyPANRctDOLInd)){ $payTecLog->KeyPANRctDOLInd = $payTecTrx->KeyPANRctDOLInd; }
                if(isset($payTecTrx->DisplayName)){ $payTecLog->DisplayName = $payTecTrx->DisplayName; }
                if(isset($payTecTrx->TrxResultExtended)){ $payTecLog->TrxResultExtended = $payTecTrx->TrxResultExtended; }
                if(isset($payTecTrx->IIN)){ $payTecLog->IIN = $payTecTrx->IIN; }
                if(isset($payTecTrx->AppPANPrtCardholder)){ $payTecLog->AppPANPrtCardholder = $payTecTrx->AppPANPrtCardholder; }
                if(isset($payTecTrx->AppPANPrtAttendant)){ $payTecLog->AppPANPrtAttendant = $payTecTrx->AppPANPrtAttendant; }
                if(isset($payTecTrx->SurrogatePAN)){ $payTecLog->SurrogatePAN = $payTecTrx->SurrogatePAN; }
                if(isset($payTecTrx->CardholderText)){ $payTecLog->CardholderText = $payTecTrx->CardholderText; }
                if(isset($payTecTrx->AttendantText)){ $payTecLog->AttendantText = $payTecTrx->AttendantText; }
                if(isset($payTecTrx->TipAmt)){ $payTecLog->TipAmt = $payTecTrx->TipAmt; }
                if(isset($payTecTrx->AmtRemaining)){ $payTecLog->AmtRemaining = $payTecTrx->AmtRemaining; }
                //  if(isset($payTecTrx->xxxxxxxxxxxxxxx)){ $payTecLog->xxxxxxxxxxxxxxx = $payTecTrx->xxxxxxxxxxxxxxx; }
                $payTecLog->save();
            }

            //---------------------------------------------------------------

            // waiter LOG
            if(Auth::User()->role == 55 || Auth::User()->role == 5){
                $waLog = new waiterActivityLog();
                $waLog->waiterId = Auth::User()->id;
                $waLog->actType = 'orderCloseWA';
                $waLog->actId = $newOrder->id;

                $ctOr = (int)0;
                foreach(explode('---8---',$newOrder->porosia) as $op){
                    $ctOr = $ctOr + (int)explode('-8-',$op)[3];
                }
                $waLog->sasia = $ctOr;
                $waLog->save();

                $newOrder->orForWaiter = Auth::User()->id;
            }
            // ---------------------------------

            // Code per tip
            if(isset($req->tipp)){
                $newTip = new TipLog;

                if($req->cashDis > 0){
                    $skontoCHF = number_format($req->cashDis,2,'.','');
                    $toPay = number_format($totalPay - $skontoCHF,2,'.','');
                  }else if($req->percDis > 0){
                        $skontoCHF = number_format($totalPay*($req->percDis/100),2,'.','');
                        $toPay = number_format($totalPay - $skontoCHF,2,'.','');
                  }else{
                      $toPay = number_format($totalPay,2,'.','');
                  } 

                $newTip->shumaPor = number_format($toPay + number_format((float)$req->tipp, 2, '.', ''), 2, '.', '');
                $newTip->tipPer = 'Empty';
                $newTip->tipTot = number_format((float)$req->tipp, 2, '.', '');
                $newTip->toRes = $req->resId;
                if(Auth::check()){
                    $newTip->klienti = Auth::user()->id;
                }else{
                    $newTip->klienti = 9999999;
                }
                $newTip->save();
            }


            // Gen the next indentifikation number (shifra - per Orders) 
            $orderSh = $this->genShifra($req->resId);
            $newOrder->shifra = $orderSh;
            $newOrder->save();
            // ------------------------------------------------------------


            $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
            shuffle($word);
            $name = substr(implode($word), 0, 25).'OrId'.$newOrder->id;
            $file = "storage/digitalReceiptQRK/".$name.".png";

            $word2 = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'));
            shuffle($word2);
            $hash = substr(implode($word2), 0, 128);

            $newQrcode = QRCode::URL('qrorpa.ch/generatePDF/'.$newOrder->id.'||'.$hash)
            ->setSize(64)
            ->setMargin(0)
            ->setOutfile($file)
            ->png();

            $newOrder->digitalReceiptQRKHash = $hash;
            $newOrder->digitalReceiptQRK = $name.".png";
            $newOrder->save();

            // if(TabOrder::where([['tableNr',$req->tableNr],['toRes',$req->resId],['tabCode','!=',0],['status','<',2]])->get()->count() <= 0){
            //     $tableGet = TableQrcode::where([['Restaurant',$req->resId],['tableNr',$req->tableNr]])->first();
            //     $tableGet->kaTab = 0;
            //     $tableGet->save();
            // }


            foreach($clientsActive as $clActiveOne){
                // remove used GHOST code / set it to finished
                if(str_contains($clActiveOne,'|')){
                    // $request->t $request->Res
                    $clPhoneNr2D = explode('|',$clActiveOne);
                    $findGCActive = ghostCartInUse::where([['toRes',$req->resId],['tableNr',$req->tableNr],['indNr2',$clPhoneNr2D[1]],['status','0']])->first();
                    if($findGCActive != NULL){
                        $findGCActive->status = 1;
                        $findGCActive->save();
                    }
                }

                if($clActiveOne != '0770000000' && !str_contains($clActiveOne,'|')){
                    $tabOrdersForUs = '';
                    foreach($tabOrdersToR as $oneTOTR){
                        $oneTOTR2D = explode('||',$oneTOTR);
                        if($oneTOTR2D[0] == $clActiveOne){
                            if($tabOrdersForUs == ''){
                                $tabOrdersForUs = $oneTOTR2D[1];
                            }else{
                                $tabOrdersForUs .= '--9--'.$oneTOTR2D[1];
                            }
                        }
                    }

                    // Register a new notifyClient
                    $newNotifyClient = new notifyClient();
                    $newNotifyClient->for = "removePaidProduct";
                    $newNotifyClient->toRes = $req->resId;
                    $newNotifyClient->tableNr = $req->tableNr;
                    $newNotifyClient->clPhoneNr = $clActiveOne;
                    $newNotifyClient->data = json_encode([
                        'tabOrId' => $tabOrdersForUs,
                        'orderId' => $newOrder->id,
                        'type' => 'a'
                    ]);
                    $newNotifyClient->readInd = 0;
                    $newNotifyClient->save();
                }
            }

            
            if(TabOrder::where([['tabCode',$tabCode]])->count() == 0){
                $tableGet = TableQrcode::where([['Restaurant',$req->resId],['tableNr',$req->tableNr]])->first();
                $tableGet->kaTab = 0;
                $tableGet->save();

                return 'ref||'.$newOrder->id;

                $endOfTab = 'true';
            }else{
                $endOfTab = 'false';
            }

            // Send Notifications for the Admin
            foreach(User::where([['sFor',$req->resId],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $req->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));

                    if($endOfTab == 'true'){
                        $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$req->tableNr],['statActive','1']])->first();
                        if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();}
                    }else{
                        foreach($AllFromTab as $oneTabOSel){
                            $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$req->tableNr],['tabOrderId',$oneTabOSel->id],['statActive','1']])->first();
                            if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();} 
                        }
                    }
                }
            }
            foreach(User::where([['sFor',$req->resId],['role','55']])->get() as $oneWaiter){
                if($oneWaiter->id != auth()->user()->id){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->tableNr]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $req->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));

                        if($endOfTab == 'true'){
                            $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$oneWaiter->id],['tableNr',$req->tableNr],['statActive','1']])->first();
                            if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();}
                        }else{
                            foreach($AllFromTab as $oneTabOSel){
                                $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$oneWaiter->id],['tableNr',$req->tableNr],['tabOrderId',$oneTabOSel->id],['statActive','1']])->first();
                                if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();} 
                            }
                        }
                    }
                }
            }
            foreach(User::where([['sFor',$req->resId],['role','54']])->get() as $oneCook){
                $details = [
                    'id' => 0,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $req->tableNr
                ];
                $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
            }

            return 'noRef||'.$newOrder->id;
        }
    }














    public function proEditGetEkstras(Request $req){
        $filteredData = ekstra::where([['toRes',$req->resId],['toCat',$req->catId]])->select('id','emri','qmimi')->get();
        return json_encode( $filteredData );
    }
    public function proEditGetTypes(Request $req){
        $filteredData = LlojetPro::where([['toRes',$req->resId],['kategoria',$req->catId]])->select('id','emri','vlera')->get();
        return json_encode( $filteredData );
    }







    public function proUpdateTheOrder(Request $req){
        // order
        foreach($req->order as $newPosPro){
            $pro = Produktet::find($newPosPro['id']);
            if($pro != null){
                $pro->position = $newPosPro['position'];
                $pro->save();
            }
        }
        return response('Update Successfully.', 200);
    }

    public function catUpdateTheOrder(Request $req){
        foreach($req->order as $newPosPro){
            $kat = kategori::find($newPosPro['id']);
            if($kat != null){
                $kat->position = $newPosPro['position'];
                $kat->save();
            }
        }
        return response('Update Successfully.', 200);
    }



    public function catNewSorting(Request $req){
        // catId: catId,
        // newSort: so,
        $theCat = kategori::find($req->catId);
        $theCat->sortingType = $req->newSort;
        $theCat->save();
    }



    public function catUpdateTheOrderTel(Request $req){
        // id: catid,
	    // newPoz: $('#changePositionCat'+catid).val(),
        $thisCat = kategori::find($req->id);

        // case 1 (4->6)
        if($thisCat->position < $req->newPoz){
            foreach(kategori::where('toRes',$thisCat->toRes)->get() as $otherCat){
                if($otherCat->position > $thisCat->position && $otherCat->position <= $req->newPoz){
                    $otherCat->position = $otherCat->position - 1;
                    $otherCat->save();
                }
            }
            $thisCat->position = $req->newPoz;
            $thisCat->save();
       
        // case 2 (2->4)
        }else if($thisCat->position > $req->newPoz){
            foreach(kategori::where('toRes',$thisCat->toRes)->get() as $otherCat){
                if($otherCat->position < $thisCat->position && $otherCat->position >= $req->newPoz){
                    $otherCat->position = $otherCat->position + 1;
                    $otherCat->save();
                }
            }
            $thisCat->position = $req->newPoz;
            $thisCat->save();
     
        }else if($thisCat->position == $req->newPoz){
            return 'yes';
        }
    }

    public function resNewSorting(Request $req){
        // resId: resId,
        // newSort: newSor,
        $theRes = Restorant::find($req->resId);
        $theRes->sortingType = $req->newSort;
        $theRes->save();
    }

    public function prodUpdateTheOrderTel(Request $req){
        
        $thisProd = Produktet::find($req->id);
        if($thisProd->position < $req->newPoz){
            foreach(Produktet::where([['toRes',$thisProd->toRes],['kategoria',$req->catId]])->get() as $otherProd){

                if($otherProd->position > $thisProd->position && $otherProd->position <= $req->newPoz){
                    $otherProd->position = $otherProd->position - 1;
                    $otherProd->save();
                }
            }
            $thisProd->position = $req->newPoz;
            $thisProd->save();

        }else if($thisProd->position > $req->newPoz){
            foreach(Produktet::where([['toRes',$thisProd->toRes],['kategoria',$req->catId]])->get() as $otherProd){
                if($otherProd->position < $thisProd->position && $otherProd->position >= $req->newPoz){
                    $otherProd->position = $otherProd->position + 1;
                    $otherProd->save();
                }
            }
            $thisProd->position = $req->newPoz;
            $thisProd->save();

        }else if($thisProd->position == $req->newPoz){
            return 'yes';
        }
    }



   








    public function returnGhostCToUser(Request $req){
        $ghostNr = '999999|'.$req->ghostCartCode;

        $countActiveOrders = 0 ;
        foreach(tabVerificationPNumbers::where('phoneNr',$ghostNr)->get() as $tabVerNr){
            $to = TabOrder::find($tabVerNr->tabOrderId);
            if($to != NULL && $to->tabCode != 0){
                // if($to->OrderExtra == 'empty'){ $saveExtra = ''; }else{ $saveExtra = $to->OrderExtra;}
                // if($to->OrderType == 'empty'){ $saveType = ''; }else{ $saveType = $to->OrderType;}
                // Cart::add($to->prodId, $to->OrderEmri, (int)$to->OrderSasia, $to->OrderQmimi/(int)$to->OrderSasia, ['ekstras' => $saveExtra, 'persh' => $to->OrderPershkrimi, 'type' => $saveType, 'koment' => $to->OrderKomenti, 'tabOId' => $to->id])->associate('App\Produktet');
                $countActiveOrders++;
            }
        }

        if($countActiveOrders > 0){
            $tabVerNr = tabVerificationPNumbers::where('phoneNr',$ghostNr)->first();
            $to2 = TabOrder::find($tabVerNr->tabOrderId);

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['adminToClProdsRec'] = $to2->specStat;
            $_SESSION['phoneNrVerified'] =  $ghostNr;

            // check the cart content 
            foreach(Cart::content() as $Citem){
                $to3 = TabOrder::find($Citem->options->tabOId);
                if($to3 != NULL && $to3->tabCode == 0){
                    Cart::remove($Citem->rowId);
                }
            }
            return redirect()->back();
        }else{
            return redirect()->back()->withCookie(cookie('ghostCartReturn', 'not' , 360));
        }




        // return redirect('/?Res='.$to->toRes.'&t='.$to->tableNr);



      
    }

    public function returnGhostCToUserCancel(Request $req){
        $ghostNr = '999999|'.$req->ghostCartCodeCancel;
        foreach(tabVerificationPNumbers::where('phoneNr',$ghostNr)->get() as $tabVerNr){
            $to = TabOrder::find($tabVerNr->tabOrderId);
            if($to != NULL && $to->tabCode != 0){
                $tabVerNr->phoneNr = '0770000000';
                $tabVerNr->save();
            }
        }
        if(Cart::count() > 0){
            Cart::destroy();
        }
        return redirect()->back()->withCookie(cookie('ghostCartReturn', 'not' , 360));
    }
















































    public function statisticsGetMonths(Request $req){

    }




    public function statisticsProds1(Request $req){
        // serviceN: $('#VerkaufsstatistikenPH1Val').val(),
        // dateN: $('#VerkaufsstatistikenPH21Val').val(),
        // res: $('#theResIdTel').val(),
        $thisRestaurant = Restorant::find(Auth::user()->sFor);
        $resClock = explode('->',$thisRestaurant->reportTimeArc);
        $resClock1_2D = explode(':',$resClock[0]);
        $resClock2_2D = explode(':',$resClock[1]);

        $date2D = explode('-',explode(' ',$req->dateN)[0]);
        $dateStr = Carbon::create($date2D[0], $date2D[1], $date2D[2], $resClock1_2D[0], $resClock1_2D[1], 00);
        $dateEnd = Carbon::create($date2D[0], $date2D[1], $date2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
        if($thisRestaurant->reportTimeOtherDay == 1){
            // diff day
            $dateEnd->addDays(1); 
        }

        if($req->serviceN == 1){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','!=','9000'],['nrTable','!=','500']])->whereBetween('created_at', [$dateStr, $dateEnd])->get();
        }else if($req->serviceN == 2){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','500']])->whereBetween('created_at', [$dateStr, $dateEnd])->get();
        }else if($req->serviceN == 3){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','9000']])->whereBetween('created_at', [$dateStr, $dateEnd])->get();
        }else if($req->serviceN == 4){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2']])->whereBetween('created_at', [$dateStr, $dateEnd])->get();
        }
      
        return json_encode( $filteredData );
    }

    public function statisticsProds2(Request $req){
        $thisRestaurant = Restorant::find(Auth::user()->sFor);
        $resClock = explode('->',$thisRestaurant->reportTimeArc);
        $resClock1_2D = explode(':',$resClock[0]);
        $resClock2_2D = explode(':',$resClock[1]);

        $date2D_1 = explode('-',explode(' ',$req->weekS)[0]);
        $date2D_2 = explode('-',explode(' ',$req->weekE)[0]);
        $dateStr = Carbon::create($date2D_1[0], $date2D_1[1], $date2D_1[2], $resClock1_2D[0], $resClock1_2D[1], 00);
        $dateEnd = Carbon::create($date2D_2[0], $date2D_2[1], $date2D_2[2], $resClock2_2D[0], $resClock2_2D[1], 59);
        if($thisRestaurant->reportTimeOtherDay == 1){
            // diff day
            $dateEnd->addDays(1); 
        }
        if($req->serviceN == 1){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','!=','9000'],['nrTable','!=','500']])->whereBetween('created_at', [$dateStr, $dateEnd])->get();
        }else if($req->serviceN == 2){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','500']])->whereBetween('created_at', [$dateStr, $dateEnd])->get();
        }else if($req->serviceN == 3){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','9000']])->whereBetween('created_at', [$dateStr, $dateEnd])->get();
        }else if($req->serviceN == 4){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2']])->whereBetween('created_at', [$dateStr, $dateEnd])->get();
        }
      
        return json_encode( $filteredData );
    }

    public function statisticsProds3(Request $req){
        // serviceN: $('#VerkaufsstatistikenPH1Val').val(),
		// dateMonth: ph23Month,
		// dateYear: ph23Year,
        // res: $('#theResId').val(),

        $thisRestaurant = Restorant::find(Auth::user()->sFor);
        $resClock = explode('->',$thisRestaurant->reportTimeArc);
        $resClock1_2D = explode(':',$resClock[0]);
        $resClock2_2D = explode(':',$resClock[1]);

        $dateBasePerMoth = Carbon::create($req->dateYear, $req->dateMonth, 01, 01, 00, 00);
        $dateMonthEnd = $dateBasePerMoth->endOfMonth();
        $dateMonthEnd = explode(' ',$dateMonthEnd)[0];
        $dateMonthEnd2D = explode('-',$dateMonthEnd);
        $dateStrThisMonth = Carbon::create($req->dateYear, $req->dateMonth, 01, $resClock1_2D[0], $resClock1_2D[1], 00);
        $dateEndThisMonth = Carbon::create($dateMonthEnd2D[0], $dateMonthEnd2D[1], $dateMonthEnd2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
        if($thisRestaurant->reportTimeOtherDay == 1){
            // diff day
            $dateEndThisMonth->addDays(1); 
        }


        if($req->serviceN == 1){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','!=','9000'],['nrTable','!=','500']])->whereBetween('created_at', [$dateStrThisMonth, $dateEndThisMonth])->get();
        }else if($req->serviceN == 2){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','500']])->whereBetween('created_at', [$dateStrThisMonth, $dateEndThisMonth])->get();
        }else if($req->serviceN == 3){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','9000']])->whereBetween('created_at', [$dateStrThisMonth, $dateEndThisMonth])->get();
        }else if($req->serviceN == 4){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2']])->whereBetween('created_at', [$dateStrThisMonth, $dateEndThisMonth])->get();
        }else if($req->serviceN == 5){
            $resSelected = array();
            foreach(explode('-m-m-m-',$req->res) as $rO ){
                array_push($resSelected, $rO);
            }
            $filteredData = OrdersPassive::where('statusi','!=','2')->whereIn('Restaurant',$resSelected)->whereBetween('created_at', [$dateStrThisMonth, $dateEndThisMonth])->get();
        }
      
        return json_encode( $filteredData );
    }

    public function statisticsProds4(Request $req){
        $thisRestaurant = Restorant::find(Auth::user()->sFor);
        $resClock = explode('->',$thisRestaurant->reportTimeArc);
        $resClock1_2D = explode(':',$resClock[0]);
        $resClock2_2D = explode(':',$resClock[1]);

        $dateBasePerMoth = Carbon::create($req->dateYear, 12, 01, 01, 00, 00);
        $dateMonthEnd = $dateBasePerMoth->endOfMonth();
        $dateMonthEnd = explode(' ',$dateMonthEnd)[0];
        $dateMonthEnd2D = explode('-',$dateMonthEnd);
        $dateStrThisYear = Carbon::create($req->dateYear, 01, 01, $resClock1_2D[0], $resClock1_2D[1], 00);
        $dateEndThisYear = Carbon::create($dateMonthEnd2D[0], $dateMonthEnd2D[1], $dateMonthEnd2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
        if($thisRestaurant->reportTimeOtherDay == 1){
            // diff day
            $dateEndThisYear->addDays(1); 
        }

        if($req->serviceN == 1){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','!=','9000'],['nrTable','!=','500']])->whereBetween('created_at', [$dateStrThisYear, $dateEndThisYear])->get();
        }else if($req->serviceN == 2){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','500']])->whereBetween('created_at', [$dateStrThisYear, $dateEndThisYear])->get();
        }else if($req->serviceN == 3){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2'],['nrTable','9000']])->whereBetween('created_at', [$dateStrThisYear, $dateEndThisYear])->get();
        }else if($req->serviceN == 4){
            $filteredData = OrdersPassive::where([['Restaurant',$req->res],['statusi','!=','2']])->whereBetween('created_at', [$dateStrThisYear, $dateEndThisYear])->get();
        }
      
        return json_encode( $filteredData );
    }


    public function downloadExcel(Request $req)
    {
        // $type = 'xls';
        // $data = Piket::get()->toArray();
            
        // return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
        //     $excel->sheet('mySheet', function($sheet) use ($data)
        //     {
        //         $sheet->fromArray($data);
        //     });
        // })->download($type);

        exportToExcel::truncate();

        // titujt
        $newExToEx = new exportToExcel();
        $newExToEx->emriN = 'Produkt';
        $newExToEx->sasiaN = 'Menge';
        $newExToEx->qmimiN = 'Preis';
        $newExToEx->save();

        $totSasiaEx = number_format((float)0, 2, '.', '');
        $totQmimiEx = number_format((float)0, 2, '.', '');;

        foreach(explode('--77--',$req->productsToExport) as $prodList){
            $rowN = explode('-7-',$prodList);

            $newExToEx = new exportToExcel();
            $newExToEx->emriN = $rowN[0];
            $newExToEx->sasiaN = $rowN[1];
            $newExToEx->qmimiN = $rowN[2];
            $newExToEx->save();

            $totSasiaEx = $totSasiaEx + number_format((float)$rowN[1], 2, '.', '');
            $totQmimiEx = $totQmimiEx + number_format((float)$rowN[2], 2, '.', '');
        }

        // totalet
        $newExToEx = new exportToExcel();
        $newExToEx->emriN = 'GESAMT';
        $newExToEx->sasiaN = $totSasiaEx;
        $newExToEx->qmimiN = $totQmimiEx;
        $newExToEx->save();

        return Excel::download(new excelExport, 'data.xlsx');
    }

    public function downloadPDFDayR(Request $req){

        $theDt = explode(' ',$req->daySelectedPDFDay)[0];
        $pdfRepot = pdfReportIns::where([['forRes',$req->daySelectedPDFDayForRes],['reportType','1'],['forDate1',$theDt]])->first();
        if($pdfRepot != NULL){
            $repoNr = $pdfRepot->billNumber; 
            $repoID = $pdfRepot->billId; 
        }else{
            $repoNr = strval(hexdec(uniqid()));
            if(pdfReportIns::where('forRes',$req->daySelectedPDFDayForRes)->count() > 0){
                $repoID = pdfReportIns::where('forRes',$req->daySelectedPDFDayForRes)->max('billId') + 1;
            }else{
                $repoID = 1;
            }
            $newRep = new pdfReportIns();
            $newRep->forRes = $req->daySelectedPDFDayForRes;
            $newRep->forDate1 = $theDt;
            $newRep->reportType = 1;
            $newRep->billId = $repoID;
            $newRep->billNumber = $repoNr;
            $newRep->save();
        }
        $sendData = $theDt.'--77--'.$repoNr.'--77--'.$repoID.'--77--type1--77--'.$req->daySelectedPDFDayForRes;
        view()->share('teDh',$sendData);
        $pdf = PDF::loadView('dailyReportPDF')->setPaper('a4', 'portrait');
        $docName = Restorant::find($req->daySelectedPDFDayForRes)->emri.'_'.explode(' ',$req->daySelectedPDFDay)[0].'.pdf';
        $pdf->save('storage/pdfDayReport/'.$docName);
        $pdf2 = PDF::loadView('dailyReportPDF2')->setPaper('a4', 'portrait');
        return $pdf2->download($docName);
    }


    
    public function downloadPDFWeekR(Request $req){
        // weekSelectedPDFWeekS
        // weekSelectedPDFWeekE
        // weekSelectedPDFWeekNr
        // weekSelectedPDFWeekYr
        $dt = $req->weekSelectedPDFWeekS.'-5-'.$req->weekSelectedPDFWeekE.'-5-'.$req->weekSelectedPDFWeekNr.'-5-'.$req->weekSelectedPDFWeekYr;
        $pdfRepot = pdfReportIns::where([['forRes',$req->daySelectedPDFWeekForRes],['reportType','2'],['forDate1',$req->weekSelectedPDFWeekS],['forDate2',$req->weekSelectedPDFWeekE]])->first();
        if($pdfRepot != NULL){
            $repoNr = $pdfRepot->billNumber; 
            $repoID = $pdfRepot->billId; 
        }else{
            $repoNr = strval(hexdec(uniqid()));
            if(pdfReportIns::where('forRes',$req->daySelectedPDFWeekForRes)->count() > 0){
                $repoID = pdfReportIns::where('forRes',$req->daySelectedPDFWeekForRes)->max('billId') + 1;
            }else{
                $repoID = 1;
            }
            $newRep = new pdfReportIns();
            $newRep->forRes = $req->daySelectedPDFWeekForRes;
            $newRep->forDate1 = $req->weekSelectedPDFWeekS;
            $newRep->forDate2 = $req->weekSelectedPDFWeekE;
            $newRep->reportType = 2;
            $newRep->billId = $repoID;
            $newRep->billNumber = $repoNr;
            $newRep->save();
        }
        $sendData = $dt.'--77--'.$repoNr.'--77--'.$repoID.'--77--type2--77--'.$req->daySelectedPDFWeekForRes;
        view()->share('teDh',$sendData);
        $pdf = PDF::loadView('dailyReportPDF')->setPaper('a4', 'portrait');
        
        $docName = Restorant::find($req->daySelectedPDFWeekForRes)->emri.'_w'.$req->weekSelectedPDFWeekNr.'of'.$req->weekSelectedPDFWeekYr.'.pdf';
        $pdf->save('storage/pdfWeekReport/'.$docName);

        $pdf2 = PDF::loadView('dailyReportPDF2')->setPaper('a4', 'portrait');

        return $pdf2->download($docName); 
    }

    public function downloadPDFMonthR(Request $req){
        // monthSelectedPDFMonth
        // monthSelectedPDFYear
        $dt = $req->monthSelectedPDFMonth.'-'.$req->monthSelectedPDFYear;
        $pdfRepot = pdfReportIns::where([['forRes',$req->daySelectedPDFMonthForRes],['reportType','3'],['forDate1',$dt]])->first();
        if($pdfRepot != NULL){
            $repoNr = $pdfRepot->billNumber; 
            $repoID = $pdfRepot->billId; 
        }else{
            $repoNr = strval(hexdec(uniqid()));
            if(pdfReportIns::where('forRes',$req->daySelectedPDFMonthForRes)->count() > 0){
                $repoID = pdfReportIns::where('forRes',$req->daySelectedPDFMonthForRes)->max('billId') + 1;
            }else{
                $repoID = 1;
            }
            $newRep = new pdfReportIns();
            $newRep->forRes = $req->daySelectedPDFMonthForRes;
            $newRep->forDate1 = $dt;
            $newRep->reportType = 3;
            $newRep->billId = $repoID;
            $newRep->billNumber = $repoNr;
            $newRep->save();
        }
        $sendData = $dt.'--77--'.$repoNr.'--77--'.$repoID.'--77--type3--77--'.$req->daySelectedPDFMonthForRes;
        
        view()->share('teDh',$sendData);
        $pdf = PDF::loadView('dailyReportPDF')->setPaper('a4', 'portrait');
        
        $docName = Restorant::find($req->daySelectedPDFMonthForRes)->emri.'_'.$dt.'.pdf';
        $pdf->save('storage/pdfMonthReport/'.$docName);

        $pdf2 = PDF::loadView('dailyReportPDF2')->setPaper('a4', 'portrait');

        return $pdf2->download($docName); 
    }

    public function downloadPDFMonthSelectiveR(Request $req){
        $dt = $req->monthSelectedPDFMonthSelective.'-'.$req->monthSelectedPDFYearSelective;
        $sendData = $dt.'-a-s-0-a-s-0-a-s-type3-a-s-'.Auth::user()->sFor.'-a-s-'.$req->monthSelectedTheRes;
        $allRestorants = '';
        foreach(explode('-m-m-m-',$req->monthSelectedTheRes) as $reOn){
            if($allRestorants == ''){ $allRestorants = $reOn; }
            else { $allRestorants .= '_'.$reOn; }
        }
        
        view()->share('teDh',$sendData);
        $pdf = PDF::loadView('genReportMVer2FP')->setPaper('a4', 'portrait');
        
        $docName = 'MonatlicherDetaillierterBericht_'.$allRestorants.'_'.$dt.'.pdf';
        $pdf->save('storage/pdfMonthReportSelective/'.$docName);

        $pdf2 = PDF::loadView('genReportMVer2')->setPaper('a4', 'portrait');
        return $pdf2->download($docName); 
    }

    public function downloadPDFYearR(Request $req){
        // yearSelectedPDFYear
        $dt = $req->yearSelectedPDFYear;
        $pdfRepot = pdfReportIns::where([['forRes',$req->daySelectedPDFYearForRes],['reportType','4'],['forDate1',$dt]])->first();
        if($pdfRepot != NULL){
            $repoNr = $pdfRepot->billNumber; 
            $repoID = $pdfRepot->billId; 
        }else{
            $repoNr = strval(hexdec(uniqid()));
            if(pdfReportIns::where('forRes',$req->daySelectedPDFYearForRes)->count() > 0){
                $repoID = pdfReportIns::where('forRes',$req->daySelectedPDFYearForRes)->max('billId') + 1;
            }else{
                $repoID = 1;
            }
            $newRep = new pdfReportIns();
            $newRep->forRes = $req->daySelectedPDFYearForRes;
            $newRep->forDate1 = $dt;
            $newRep->reportType = 4;
            $newRep->billId = $repoID;
            $newRep->billNumber = $repoNr;
            $newRep->save();
        }
        $sendData = $dt.'--77--'.$repoNr.'--77--'.$repoID.'--77--type4--77--'.$req->daySelectedPDFYearForRes;
        
        view()->share('teDh',$sendData);
        $pdf = PDF::loadView('dailyReportPDF')->setPaper('a4', 'portrait');
        
        $docName = Restorant::find($req->daySelectedPDFYearForRes)->emri.'_'.$dt.'.pdf';
        $pdf->save('storage/pdfYearReport/'.$docName);

        $pdf2 = PDF::loadView('dailyReportPDF2')->setPaper('a4', 'portrait');

        return $pdf2->download($docName); 
    }
    
    public function changeResOpenStatusTH(Request $req){
        $theRes = Restorant::find(Auth::user()->sFor);
        $isO2D = explode('-||-',$theRes->isOpen);

        if($req->theS == 'Res'){
            $newIsOp = $req->newSt.'-||-'.$isO2D[1].'-||-'.$isO2D[2];
        }else if($req->theS == 'TA'){
            $newIsOp = $isO2D[0].'-||-'.$req->newSt.'-||-'.$isO2D[2];
        }else if($req->theS == 'DE'){
            $newIsOp = $isO2D[0].'-||-'.$isO2D[1].'-||-'.$req->newSt;
        }
        $theRes->isOpen = $newIsOp;
        $theRes->save();



        // Send Notifications for the Admin
        foreach(User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $user){
            if($user->id != Auth::user()->id){
                $details = [
                    'id' => '00',
                    'type' => 'OpenCloseRestaurant',
                    'tableNr' => '00'
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        foreach(User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $oneWaiter){
            if($oneWaiter->id != Auth::user()->id){
                // register the notification ...
                $details = [
                    'id' => '00',
                    'type' => 'OpenCloseRestaurant',
                    'tableNr' => '00'
                ];
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
    }






























    public function adminReqClTableChange(Request $req){
        // tableFromNr: tfrom,
        // tableToId: $('#adminChangeTableModal'+tfrom+'TableSelectedId').val(),
        // tableToActive
        // clPhoneNr: $('#adminChangeTableModal'+tfrom+'NumberSelected').val(),
        // res: res,
        $toTable = TableQrcode::find($req->tableToId);
        if($toTable != NULL){

            $adminSelected = False;
            foreach(explode('||',$req->clPhoneNr) as $oneClPhoneNr){
                if($oneClPhoneNr == '0770000000'){
                    $adminSelected = True;
                    break;
                }
            }

            foreach(explode('||',$req->clPhoneNr) as $oneClPhoneNr){
                $tableChReqAdmin = new tableChngReqsAdmin();
                $tableChReqAdmin->toRes = $req->res;
                $tableChReqAdmin->fromTable = $req->tableFromNr;
                $tableChReqAdmin->toTable = $toTable->tableNr;
                if($adminSelected && $oneClPhoneNr != '0770000000'){
                    $tableChReqAdmin->toTableActive = 1;
                }else{
                    $tableChReqAdmin->toTableActive = $req->tableToActive;
                }
                $tableChReqAdmin->clPhoneNr = $oneClPhoneNr;
                if($req->tabOrdersSelected == 0){
                    $tableChReqAdmin->tabOrSelected = 'none';
                }else{
                    $tableChReqAdmin->tabOrSelected = $req->tabOrdersSelected;
                }
             
                $tableChReqAdmin->status = 0;
                // o new / 1 accept / 2 cancel
                $tableChReqAdmin->save();

                if($oneClPhoneNr == '0770000000'){
                    $tableChReqAdminIdAdmin = $tableChReqAdmin->id;
                }
            }

            if($adminSelected){
                return  $tableChReqAdminIdAdmin;
            }else{ return 'reset';}
        }
    }



    public function adminReqClTableChangeCheck(Request $req){
        $hasRequest = tableChngReqsAdmin::where([['toRes',$req->myRes],['fromTable',$req->myTable],['clPhoneNr',$req->myNumber],['status',0]])->first();
        if($hasRequest != NULL ){
            return $hasRequest->id.'||'.$hasRequest->toTable;
        }else{return 'no';}
    }



    public function adminReqClTableChangeConfirm(Request $req){
        // tabChngReqId
        // $tableChReqAdmin->tabOrSelected
        $tabChngReq = tableChngReqsAdmin::findOrFail($req->tabChngReqId);

        $oldT = TableQrcode::where([['tableNr',$tabChngReq->fromTable],['Restaurant',$tabChngReq->toRes]])->first();
        $newT = TableQrcode::where([['tableNr',$tabChngReq->toTable],['Restaurant',$tabChngReq->toRes]])->first();

        $tabCodeN = $this->genTheNewTabCode();

        if($tabChngReq->toTableActive == 0){
            // New table is NOT active
            if($oldT->kaTab != 0){

                // $theTab = $oldT->kaTab;
                // $hasOtherOrders = False;
                // if(tabVerificationPNumbers::where([['phoneNr','!=',$tabChngReq->clPhoneNr],['tabCode',$theTab]])->count() > 0){
                //     $hasOtherOrders = True;
                //     $tabCodeNew = $this->genTheNewTabCode();
                // }

                if($tabChngReq->tabOrSelected == 'none'){
                    $tabOrdAll = TabOrder::where([['tabCode',$oldT->kaTab],['toRes',$tabChngReq->toRes]])->get();
                }else{
                    $tabOrSelectedId = array();
                    foreach(explode('||',$tabChngReq->tabOrSelected) as $tabOrdersSelectedOne){
                        $tabOrdersSelectedOne2D = explode('-8-',$tabOrdersSelectedOne);
                        
                        $checkThisTabOrd = TabOrder::find($tabOrdersSelectedOne2D[0]);
                        if($checkThisTabOrd->OrderSasia == $tabOrdersSelectedOne2D[1]){
                            array_push($tabOrSelectedId,$tabOrdersSelectedOne2D[0]);
                        }else{

                            $priceForOneSelTabProd = number_format($checkThisTabOrd->OrderQmimi/$checkThisTabOrd->OrderSasia, 2, '.', '');
                            $qmimiOfSelected = number_format($priceForOneSelTabProd * $tabOrdersSelectedOne2D[1], 2, '.', '');

                            $checkThisTabOrd->OrderSasia = $checkThisTabOrd->OrderSasia - $tabOrdersSelectedOne2D[1];
                            $checkThisTabOrd->OrderQmimi = number_format($checkThisTabOrd->OrderQmimi - $qmimiOfSelected, 2, '.', '');
                            if($checkThisTabOrd->OrderSasiaDone >= $tabOrdersSelectedOne2D[1]){
                                $checkThisTabOrd->OrderSasiaDone = $checkThisTabOrd->OrderSasiaDone - $tabOrdersSelectedOne2D[1];
                            }
                            $checkThisTabOrd->save();

                            // Save extra TAB order ....
                            $newTabOrder = new TabOrder;
                            $newTabOrder->tabCode = $checkThisTabOrd->tabCode;
                            $newTabOrder->prodId = $checkThisTabOrd->prodId;
                            $newTabOrder->OrderEmri = $checkThisTabOrd->OrderEmri;
                            $newTabOrder->tableNr = $checkThisTabOrd->tableNr;
                            $newTabOrder->toRes = $checkThisTabOrd->toRes;
                            $newTabOrder->OrderPershkrimi= $checkThisTabOrd->OrderPershkrimi;
                            $newTabOrder->OrderSasia = $tabOrdersSelectedOne2D[1];
                            $newTabOrder->OrderSasiaDone = 0;
                            $newTabOrder->OrderQmimi = number_format($qmimiOfSelected, 2, '.', '');
                            $newTabOrder->OrderExtra = $checkThisTabOrd->OrderExtra;
                            $newTabOrder->OrderType = $checkThisTabOrd->OrderType;
                            $newTabOrder->OrderKomenti = $checkThisTabOrd->OrderKomenti;
                            $newTabOrder->status = $checkThisTabOrd->status;
                            $newTabOrder->toPlate = $checkThisTabOrd->toPlate;
                            $newTabOrder->abrufenStat = $checkThisTabOrd->abrufenStat;
                            $newTabOrder->save();

                            $theNrVer = tabVerificationPNumbers::where('tabOrderId',$checkThisTabOrd->id)->first();
                            // Save the number ....
                            $newNrVerification = new tabVerificationPNumbers;
                            $newNrVerification->phoneNr = $theNrVer->phoneNr;
                            $newNrVerification->tabCode = $theNrVer->tabCode;
                            $newNrVerification->tabOrderId = $newTabOrder->id;
                            $newNrVerification->status = 1;
                            $newNrVerification->save();

                            $newTabOrder->usrPhNr = $theNrVer->phoneNr;
                            $newTabOrder->save();

                            array_push($tabOrSelectedId,$newTabOrder->id);
                        }
                    }
                    $tabOrdAll = TabOrder::where([['tabCode',$oldT->kaTab],['toRes',$tabChngReq->toRes]])->whereIn('id',$tabOrSelectedId)->get();
                }

                foreach($tabOrdAll as $tabOrders){
                    $tabVerify = tabVerificationPNumbers::where('tabOrderId',$tabOrders->id)->firstOrFail();
                    if($tabVerify->phoneNr == $tabChngReq->clPhoneNr){
                        $tabOrders->tableNr = $tabChngReq->toTable;
                        $tabOrders->tabCode = $tabCodeN;
                        $tabOrders->save();

                        $tabVerify->tabCode = $tabCodeN;
                        $tabVerify->save();
                    }
                }
              
                // Ka te tjera ne tavolinen e vjeter
                $newT->kaTab = $tabCodeN;
                $newT->save();

                // Nuk ka te tjera ne tavolinen e vjeter
                if(TabOrder::where([['tabCode',$oldT->kaTab],['toRes',$tabChngReq->toRes]])->count() == 0){
                    $oldT->kaTab = 0;
                    $oldT->save();
                }
            }
            // Change red glow alerts from old->new table
            foreach(newOrdersAdminAlert::where([['toRes',$tabChngReq->toRes],['tableNr',$oldT->tableNr]])->get() as $redGlowTable){
                $redGlowTable->tableNr = $newT->tableNr;
                $redGlowTable->save();
            }
    
        }else if($tabChngReq->toTableActive == 1){
            // New table is active
            $mergeToTab = $newT->kaTab;
            $theTab = $oldT->kaTab;

            $tabOrSelectedId = array();

            if($tabChngReq->tabOrSelected == 'none'){
                $tabOrdAll = TabOrder::where([['tabCode',$theTab],['toRes',$tabChngReq->toRes]])->get();
            }else{
                foreach(explode('||',$tabChngReq->tabOrSelected) as $tabOrdersSelectedOne){
                    $tabOrdersSelectedOne2D = explode('-8-',$tabOrdersSelectedOne);

                    $checkThisTabOrd = TabOrder::find($tabOrdersSelectedOne2D[0]);
                    if($checkThisTabOrd->OrderSasia == $tabOrdersSelectedOne2D[1]){
                        array_push($tabOrSelectedId,$tabOrdersSelectedOne2D[0]);
                    }else{

                        $priceForOneSelTabProd = number_format($checkThisTabOrd->OrderQmimi/$checkThisTabOrd->OrderSasia, 2, '.', '');
                        $qmimiOfSelected = number_format($priceForOneSelTabProd * $tabOrdersSelectedOne2D[1], 2, '.', '');

                        $checkThisTabOrd->OrderSasia = $checkThisTabOrd->OrderSasia - $tabOrdersSelectedOne2D[1];
                        $checkThisTabOrd->OrderQmimi = number_format($checkThisTabOrd->OrderQmimi - $qmimiOfSelected, 2, '.', '');
                        if($checkThisTabOrd->OrderSasiaDone >= $tabOrdersSelectedOne2D[1]){
                            $checkThisTabOrd->OrderSasiaDone = $checkThisTabOrd->OrderSasiaDone - $tabOrdersSelectedOne2D[1];
                        }
                        $checkThisTabOrd->save();

                        // Save extra TAB order ....
                        $newTabOrder = new TabOrder;
                        $newTabOrder->tabCode = $checkThisTabOrd->tabCode;
                        $newTabOrder->prodId = $checkThisTabOrd->prodId;
                        $newTabOrder->OrderEmri = $checkThisTabOrd->OrderEmri;
                        $newTabOrder->tableNr = $checkThisTabOrd->tableNr;
                        $newTabOrder->toRes = $checkThisTabOrd->toRes;
                        $newTabOrder->OrderPershkrimi= $checkThisTabOrd->OrderPershkrimi;
                        $newTabOrder->OrderSasia = $tabOrdersSelectedOne2D[1];
                        $newTabOrder->OrderSasiaDone = 0;
                        $newTabOrder->OrderQmimi = number_format($qmimiOfSelected, 2, '.', '');
                        $newTabOrder->OrderExtra = $checkThisTabOrd->OrderExtra;
                        $newTabOrder->OrderType = $checkThisTabOrd->OrderType;
                        $newTabOrder->OrderKomenti = $checkThisTabOrd->OrderKomenti;
                        $newTabOrder->status = $checkThisTabOrd->status;
                        $newTabOrder->toPlate = $checkThisTabOrd->toPlate;
                        $newTabOrder->abrufenStat = $checkThisTabOrd->abrufenStat;
                        $newTabOrder->save();

                        $theNrVer = tabVerificationPNumbers::where('tabOrderId',$checkThisTabOrd->id)->first();
                        // Save the number ....
                        $newNrVerification = new tabVerificationPNumbers;
                        $newNrVerification->phoneNr = $theNrVer->phoneNr;
                        $newNrVerification->tabCode = $theNrVer->tabCode;
                        $newNrVerification->tabOrderId = $newTabOrder->id;
                        $newNrVerification->status = 1;
                        $newNrVerification->save();

                        $newTabOrder->usrPhNr = $theNrVer->phoneNr;
                        $newTabOrder->save();

                        array_push($tabOrSelectedId,$newTabOrder->id);
                    }
                }
                $tabOrdAll = TabOrder::where([['tabCode',$theTab],['toRes',$tabChngReq->toRes]])->whereIn('id',$tabOrSelectedId)->get();
            }

            foreach($tabOrdAll as $tabOrders){
                $tabVerify = tabVerificationPNumbers::where('tabOrderId',$tabOrders->id)->firstOrFail();
                if($tabVerify->phoneNr == $tabChngReq->clPhoneNr){
                    $tabOrders->tableNr = $tabChngReq->toTable;
                    $tabOrders->tabCode = $mergeToTab;
                    $tabOrders->save();

                    $tabVerify->tabCode =$mergeToTab;
                    $tabVerify->save();
                }
            }

            if(TabOrder::where([['tabCode',$oldT->kaTab],['toRes',$tabChngReq->toRes]])->count() == 0){
                $oldT->kaTab = 0;
                $oldT->save();
            }

            // Change red glow alerts from old->new table
            foreach(newOrdersAdminAlert::where([['toRes',$tabChngReq->toRes],['tableNr',$oldT->tableNr]])->get() as $redGlowTable){
                
                if($tabChngReq->tabOrSelected == 'none' || in_array($redGlowTable->tabOrderId, $tabOrSelectedId)){

                    $newTNewADAlert = newOrdersAdminAlert::where([['toRes',$tabChngReq->toRes],['tableNr',$newT->tableNr],['adminId',$redGlowTable->adminId],['tabOrderId',$redGlowTable->tabOrderId]])->first();
                    if($newTNewADAlert == Null){
                        $redGlowTable->tableNr = $newT->tableNr;
                        $redGlowTable->save();
                    }else{
                        $redGlowTable->delete();
                    }
                }
            }
        }

        // mark as read ( Table change request )
        $tabChngReq->status = 1;
        $tabChngReq->save();

        // change tableNr on ghost In use records
        if (str_contains($tabChngReq->clPhoneNr, '|')) { 
            $ghostCartInUse = ghostCartInUse::where([['indNr',$tabChngReq->clPhoneNr],['tableNr',$tabChngReq->fromTable],['status',0]])->firstOrFail();
            if($ghostCartInUse != NULL){
                $ghostCartInUse->tableNr = $tabChngReq->toTable;
                $ghostCartInUse->save();
            }
        }

        // Send Notifications for the Admin "if not admins"
        foreach(User::where([['sFor',$tabChngReq->toRes],['role','5']])->get() as $user){
            if(Auth::user()->id != $user->id ){
                $details = [
                    'id' => $req->tabChngReqId,
                    'type' => 'tableChngReqAdminExecuted',
                    'tableNr' => $oldT->tableNr
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));

                $details = [
                    'id' => $req->tabChngReqId,
                    'type' => 'tableChngReqAdminExecuted',
                    'tableNr' => $newT->tableNr
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        foreach(User::where([['sFor',$tabChngReq->toRes],['role','55']])->get() as $oneWaiter){
            if(Auth::user()->id != $oneWaiter->id ){
                $aToTableOld = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $oldT->tableNr]])->first();
                if($aToTableOld != NULL && $aToTableOld->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $req->tabChngReqId,
                        'type' => 'tableChngReqAdminExecuted',
                        'tableNr' => $oldT->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
                $aToTableNew = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $newT->tableNr]])->first();
                if($aToTableNew != NULL && $aToTableNew->statusAct == 1){
                    $details = [
                        'id' => $req->tabChngReqId,
                        'type' => 'tableChngReqAdminExecuted',
                        'tableNr' => $newT->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
        }

        // Send Notifications for the Cooks
        foreach(User::where([['sFor',$tabChngReq->toRes],['role','54']])->get() as $oneCook){
            $details = [
                'id' => 0,
                'type' => 'AdminUpdateOrdersP',
                'tableNr' => $oldT->tableNr
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));

            $details2 = [
                'id' => 0,
                'type' => 'AdminUpdateOrdersP',
                'tableNr' => $newT->tableNr
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details2));
        }
        return $tabChngReq->toTable;
    }



    

    public function admPassChngDt(Request $req){
        $theU = User::findOrFail($req->usr);
        $theU->password = bcrypt($req->passw);
        $theU->passChngRequ = 0;
        $theU->save();
    }




    public function addToCartExpresWOT(Request $requ){
        // pid: pId,
        // tNr: $('#t').val(),
        // res: $('#res').val(),
        $addThPro = Produktet::findOrFail($requ->pid);
        $addThProSasia = (int)1;


        // Add as a tab order 
        $tableOfRes = TableQrcode::where([['tableNr',$requ->tNr],['Restaurant',$requ->res]])->first();
        if($tableOfRes->kaTab != 0 && $tableOfRes->kaTab != -1){
            $tabCodeN = $tableOfRes->kaTab;
        }else{
            $tabCodeN = $this->genTheNewTabCode();
            $tableOfRes->kaTab = $tabCodeN;
            $tableOfRes->save();
        }
        // ---------------------------------------------

        // check if a cook has this product registered (in control)
        $sasiaDone = $addThProSasia;
        $abrufenStat = -1;
        if(cooksProductSelection::where([['toRes',$requ->res],['contentType','Category'],['contentId',$addThPro->kategoria]])->first() != Null){
            $sasiaDone = 0;
            $abrufenStat = 0;
        }else{
            if(cooksProductSelection::where([['toRes',$requ->res],['contentType','Product'],['contentId',$addThPro->id]])->first() != Null){
                $sasiaDone = 0;
                $abrufenStat = 0;
            }
        }
        // ---------------------------------------------

        $newTabOrder = new TabOrder;

        $newTabOrder->tabCode = $tabCodeN;
        $newTabOrder->prodId = $requ->pid;
        $newTabOrder->OrderEmri = $addThPro->emri;
        $newTabOrder->tableNr = $requ->tNr;
        $newTabOrder->toRes = $requ->res;
        $newTabOrder->OrderPershkrimi= $addThPro->pershkrimi;
        $newTabOrder->OrderSasia= $addThProSasia;
        $newTabOrder->OrderSasiaDone= $sasiaDone;
        $newTabOrder->OrderQmimi= (Float)$addThPro->qmimi*$addThProSasia;
        $newTabOrder->OrderExtra= 'empty';
        $newTabOrder->OrderType= 'empty';
        $newTabOrder->OrderKomenti= NULL; 
        $newTabOrder->status = Auth::user()->tableOrAutoConfirm;

        $theProduIns = Produktet::find($requ->pid);
        $theKategIns = kategori::find($theProduIns->kategoria);
        $thePlateIns = resPlates::where([['toRes',Auth::user()->sFor],['desc2C',$theKategIns->forPlate]])->first();
        if($thePlateIns == Null){ $newTabOrder->toPlate = 0;
        }else{ $newTabOrder->toPlate = $thePlateIns->id;}

        $newTabOrder->abrufenStat = $abrufenStat;

        $newTabOrder->save();

        // waiter LOG
        $waLog = new waiterActivityLog();
        $waLog->waiterId = Auth::User()->id;
        $waLog->actType = 'newProdWa';
        $waLog->actId = $newTabOrder->id;
        $waLog->save();
        // ----------------------------------------------------

        if($requ->phoneN == 0){
            $savePhoneN = "0770000000";
        }else{
            $savePhoneN = $requ->phoneN;
            // $sendToEv = $req->resN.'||'.$req->tableN.'||'.$req->phoneN.'||'.$newTabOrder->id;
            // event(new addToCartAdmin($sendToEv));

            $phoneNrActive = array();
            foreach(tabVerificationPNumbers::where([['tabCode',$tabCodeN],['status','1']])->get() as $nrVers){
                if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){
                    array_push($phoneNrActive,$nrVers->phoneNr);
                    // Register a new notifyClient
                    $newNotifyClient = new notifyClient();
                    $newNotifyClient->for = "addToCartAdmin";
                    $newNotifyClient->toRes = $requ->res;
                    $newNotifyClient->tableNr = $requ->tNr;
                    $newNotifyClient->clPhoneNr = $nrVers->phoneNr;
                    $newNotifyClient->data = json_encode([
                        'phoneNrNotifyFor' => $nrVers->phoneNr,
                        'phoneNrOrderFor' => $requ->phoneN,
                        'newOrId' => $newTabOrder->id,
                    ]);
                    $newNotifyClient->readInd = 0;
                    $newNotifyClient->save();
                }
            }
        }

        $newTabOrder->usrPhNr = $savePhoneN;
        $newTabOrder->save();

        // Save the number ....
        $newNrVerification = new tabVerificationPNumbers;
        $newNrVerification->phoneNr = $savePhoneN;
        $newNrVerification->tabCode = $tabCodeN;
        $newNrVerification->tabOrderId = $newTabOrder->id;
        $newNrVerification->status = 1;
        $newNrVerification->save();
     
        // Save the notification for GHOST CART / if a ghostCart is active in this Restaurant/Table 
        if(ghostCartInUse::where([['toRes', $requ->res],['tableNr', $requ->tNr],['status','0']])->get()->count() > 0){
            $newClNotify = new notifyClient;
            $newClNotify->for = 'ghostCartRefresh';
            $newClNotify->toRes = $requ->res;
            $newClNotify->tableNr = $requ->tNr;
            $newClNotify->data = '';
            $newClNotify->save();
        }

        // Send Notifications for the Admin
        foreach(User::where([['sFor',$requ->res],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                $details = [
                    'id' => $newTabOrder->id,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $requ->tNr
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));

                if(newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$requ->tNr],['tabOrderId',$newTabOrder->id],['statActive','1']])->first() == NULL){
                    $newAdmAlert = new newOrdersAdminAlert();
                    $newAdmAlert->adminId = $user->id;
                    $newAdmAlert->tableNr = $requ->tNr;
                    $newAdmAlert->toRes = $requ->res;
                    $newAdmAlert->tabOrderId = $newTabOrder->id;
                    $newAdmAlert->statActive = 1;
                    $newAdmAlert->save();
                }
            }
        }
        foreach(User::where([['sFor',$requ->res],['role','55']])->get() as $oneWaiter){
            if($oneWaiter->id != auth()->user()->id){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $requ->tNr]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $newTabOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $requ->tNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));

                    if(newOrdersAdminAlert::where([['adminId',$oneWaiter->id],['tableNr',$requ->tNr],['tabOrderId',$newTabOrder->id],['statActive','1']])->first() == NULL){
                        $newAdmAlert = new newOrdersAdminAlert();
                        $newAdmAlert->adminId = $oneWaiter->id;
                        $newAdmAlert->tableNr = $requ->tNr;
                        $newAdmAlert->toRes = $requ->res;
                        $newAdmAlert->tabOrderId = $newTabOrder->id;
                        $newAdmAlert->statActive = 1;
                        $newAdmAlert->save();
                    }
                }
            }
        }
        if(Auth::user()->tableOrAutoConfirm == 1){
            // Send Notifications for the Cooks
            foreach(User::where([['sFor',$newTabOrder->toRes],['role','54']])->get() as $oneCook){
                $details = [
                    'id' => $newTabOrder->id,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $newTabOrder->tableNr
                ];
                $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
    
        // build the order showing array

        if($newTabOrder->OrderKomenti == Null){$tabOrComm = 'empty';
        }else{ $tabOrComm = $newTabOrder->OrderKomenti; }

        $waLog = waiterActivityLog::where([['actType','newProdWa'],['actId',$newTabOrder->id]])->first();
        if($waLog != Null ){ $waiterDataName = User::find($waLog->waiterId)->name; }else{ $waiterDataName = 'Administrator'; }

        $thePlateOfTO = resPlates::find($newTabOrder->toPlate);
        if($thePlateOfTO != Null){ $thePlate = $thePlateOfTO->nameTitle;
        }else{ $thePlate = 'none'; }

        if($newTabOrder->OrderExtra != 'empty' && $newTabOrder->OrderExtra != Null){ $extraToShow = $newTabOrder->OrderExtra;
        }else{ $extraToShow = 'empty'; }
                                                                                                           
        $showProdData = $requ->tNr.'-||-'.$newTabOrder->id.'-||-'.$newTabOrder->status.'-||-'.$newTabOrder->OrderQmimi.'-||-'.
        $newTabOrder->created_at.'-||-'.$newTabOrder->OrderSasia.'-||-'.$newTabOrder->OrderEmri.'-||-'.$newTabOrder->OrderPershkrimi.'-||-'.
        $newTabOrder->OrderType.'-||-'.$tabOrComm.'-||-'.$waiterDataName.'-||-'.$thePlate.'-||-'.$newTabOrder->tabCode.'-||-'.$extraToShow.'-||-'.
        $newTabOrder->OrderSasiaDone.'-||-'.$newTabOrder->usrPhNr.'-||-'.$newTabOrder->toPlate.'-||-'.$newTabOrder->abrufenStat;

        return $showProdData;
    }



    public function addToCartExpresWT(Request $requ){
        // pid
        // typeId
        // 
        // tNr
        // res
        // phoneN
        $addThPro = Produktet::findOrFail($requ->pid);
        $theTypeToAdd = LlojetPro::findOrFail($requ->typeId);
        $addThProSasia = (int)1;

        // Add as a tab order 
        $tableOfRes = TableQrcode::where([['tableNr',$requ->tNr],['Restaurant',$requ->res]])->first();
        if($tableOfRes->kaTab != 0 && $tableOfRes->kaTab != -1){
            $tabCodeN = $tableOfRes->kaTab;
        }else{
            $tabCodeN = $this->genTheNewTabCode();
            $tableOfRes->kaTab = $tabCodeN;
            $tableOfRes->save();
        }
        // ---------------------------------------------
        // check if a cook has this product registered (in control)
        $sasiaDone = $addThProSasia;
        $abrufenStat = -1;
        if(cooksProductSelection::where([['toRes',$requ->res],['contentType','Category'],['contentId',$addThPro->kategoria]])->first() != Null){
            $sasiaDone = 0;
             $abrufenStat = 0;
        }else{
            if(cooksProductSelection::where([['toRes',$requ->res],['contentType','Product'],['contentId',$addThPro->id]])->first() != Null){
                $sasiaDone = 0;
                 $abrufenStat = 0;
            }else if(cooksProductSelection::where([['toRes',$requ->res],['contentType','Type'],['contentId',$theTypeToAdd->id]])->first() != Null){
                $sasiaDone = 0;
                 $abrufenStat = 0;
            }
        }
        // Extra
        // ---------------------------------------------

        $newTabOrder = new TabOrder;

        $newTabOrder->tabCode = $tabCodeN;
        $newTabOrder->prodId = $requ->pid;
        $newTabOrder->OrderEmri = $addThPro->emri;
        $newTabOrder->tableNr = $requ->tNr;
        $newTabOrder->toRes = $requ->res;
        $newTabOrder->OrderPershkrimi= $addThPro->pershkrimi;
        $newTabOrder->OrderSasia= $addThProSasia;
        $newTabOrder->OrderSasiaDone= $sasiaDone;
        $newTabOrder->OrderQmimi= (Float)$requ->newPrice*$addThProSasia;
        $newTabOrder->OrderExtra= 'empty';
        $newTabOrder->OrderType= $theTypeToAdd->emri.'||'.$theTypeToAdd->vlera;
        $newTabOrder->OrderKomenti= NULL;
        $newTabOrder->status = Auth::user()->tableOrAutoConfirm;

        $theProduIns = Produktet::find($requ->pid);
        $theKategIns = kategori::find($theProduIns->kategoria);
        $thePlateIns = resPlates::where([['toRes',Auth::user()->sFor],['desc2C',$theKategIns->forPlate]])->first();
        if($thePlateIns == Null){ $newTabOrder->toPlate = 0;
        }else{ $newTabOrder->toPlate = $thePlateIns->id;}

        $newTabOrder->abrufenStat = $abrufenStat;

        $newTabOrder->save();

        // waiter LOG
        if(Auth::User()->role == 5 || Auth::User()->role == 55){
            $waLog = new waiterActivityLog();
            $waLog->waiterId = Auth::User()->id;
            $waLog->actType = 'newProdWa';
            $waLog->actId = $newTabOrder->id;
            $waLog->save();
        }
        // ---------------------------------------------

        if($requ->phoneN == 0){
            $savePhoneN = "0770000000";
        }else{
            $savePhoneN = $requ->phoneN;
            // $sendToEv = $req->resN.'||'.$req->tableN.'||'.$req->phoneN.'||'.$newTabOrder->id;
            // event(new addToCartAdmin($sendToEv));

            $phoneNrActive = array();
            foreach(tabVerificationPNumbers::where([['tabCode',$tabCodeN],['status','1']])->get() as $nrVers){
                if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){
                    array_push($phoneNrActive,$nrVers->phoneNr);
                    // Register a new notifyClient
                    $newNotifyClient = new notifyClient();
                    $newNotifyClient->for = "addToCartAdmin";
                    $newNotifyClient->toRes = $requ->res;
                    $newNotifyClient->tableNr = $requ->tNr;
                    $newNotifyClient->clPhoneNr = $nrVers->phoneNr;
                    $newNotifyClient->data = json_encode([
                        'phoneNrNotifyFor' => $nrVers->phoneNr,
                        'phoneNrOrderFor' => $requ->phoneN,
                        'newOrId' => $newTabOrder->id,
                    ]);
                    $newNotifyClient->readInd = 0;
                    $newNotifyClient->save();
                }
            }
        }

        $newTabOrder->usrPhNr = $savePhoneN;
        $newTabOrder->save();

        // Save the number ....
        $newNrVerification = new tabVerificationPNumbers;
        $newNrVerification->phoneNr = $savePhoneN;
        $newNrVerification->tabCode = $tabCodeN;
        $newNrVerification->tabOrderId = $newTabOrder->id;
        $newNrVerification->status = 1;
        $newNrVerification->save();
     
        // Save the notification for GHOST CART / if a ghostCart is active in this Restaurant/Table 
        if(ghostCartInUse::where([['toRes', $requ->res],['tableNr', $requ->tNr],['status','0']])->get()->count() > 0){
            $newClNotify = new notifyClient;
            $newClNotify->for = 'ghostCartRefresh';
            $newClNotify->toRes = $requ->res;
            $newClNotify->tableNr = $requ->tNr;
            $newClNotify->data = '';
            $newClNotify->save();
        }

        // Send Notifications for the Admin
        foreach(User::where([['sFor',$requ->res],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                $details = [
                    'id' => $newTabOrder->id,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $requ->tNr
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));

                if(newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$requ->tNr],['tabOrderId',$newTabOrder->id],['statActive','1']])->first() == NULL){
                    $newAdmAlert = new newOrdersAdminAlert();
                    $newAdmAlert->adminId = $user->id;
                    $newAdmAlert->tableNr = $requ->tNr;
                    $newAdmAlert->toRes = $requ->res;
                    $newAdmAlert->tabOrderId = $newTabOrder->id;
                    $newAdmAlert->statActive = 1;
                    $newAdmAlert->save();
                }
            }
        }
        foreach(User::where([['sFor',$requ->res],['role','55']])->get() as $oneWaiter){
            if($oneWaiter->id != auth()->user()->id){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $requ->tNr]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $newTabOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $requ->tNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));

                    if(newOrdersAdminAlert::where([['adminId',$oneWaiter->id],['tableNr',$requ->tNr],['tabOrderId',$newTabOrder->id],['statActive','1']])->first() == NULL){
                        $newAdmAlert = new newOrdersAdminAlert();
                        $newAdmAlert->adminId = $oneWaiter->id;
                        $newAdmAlert->tableNr = $requ->tNr;
                        $newAdmAlert->toRes = $requ->res;
                        $newAdmAlert->tabOrderId = $newTabOrder->id;
                        $newAdmAlert->statActive = 1;
                        $newAdmAlert->save();
                    }
                }
            }
        }
        if(Auth::user()->tableOrAutoConfirm == 1){
            // Send Notifications for the Cooks
            foreach(User::where([['sFor',$newTabOrder->toRes],['role','54']])->get() as $oneCook){
                $details = [
                    'id' => $newTabOrder->id,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $newTabOrder->tableNr
                ];
                $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }

        if($newTabOrder->OrderKomenti == Null){$tabOrComm = 'empty';
        }else{ $tabOrComm = $newTabOrder->OrderKomenti; }

        $waLog = waiterActivityLog::where([['actType','newProdWa'],['actId',$newTabOrder->id]])->first();
        if($waLog != Null ){ $waiterDataName = User::find($waLog->waiterId)->name; }else{ $waiterDataName = 'Administrator'; }

        $thePlateOfTO = resPlates::find($newTabOrder->toPlate);
        if($thePlateOfTO != Null){ $thePlate = $thePlateOfTO->nameTitle;
        }else{ $thePlate = 'none'; }

        if($newTabOrder->OrderExtra != 'empty' && $newTabOrder->OrderExtra != Null){ $extraToShow = $newTabOrder->OrderExtra;
        }else{ $extraToShow = 'empty'; }
                                                                                                           
        $showProdData = $requ->tNr.'-||-'.$newTabOrder->id.'-||-'.$newTabOrder->status.'-||-'.$newTabOrder->OrderQmimi.'-||-'.
        $newTabOrder->created_at.'-||-'.$newTabOrder->OrderSasia.'-||-'.$newTabOrder->OrderEmri.'-||-'.$newTabOrder->OrderPershkrimi.'-||-'.
        $newTabOrder->OrderType.'-||-'.$tabOrComm.'-||-'.$waiterDataName.'-||-'.$thePlate.'-||-'.$newTabOrder->tabCode.'-||-'.$extraToShow.'-||-'.
        $newTabOrder->OrderSasiaDone.'-||-'.$newTabOrder->usrPhNr.'-||-'.$newTabOrder->toPlate.'-||-'.$newTabOrder->abrufenStat;

        return $showProdData;
    }






    public function removeNewOrderAlertFA(Request $req){
        // aId
        // tableNr
        // tabOrderId
        $AdmAlert = newOrdersAdminAlert::where([['adminId',$req->aId],['tableNr',$req->tableNr],['tabOrderId',$req->tabOrderId],['statActive','1']])->first();
        if($AdmAlert != NULL){
            $AdmAlert->delete();
        }
        $AdmAlertOnTable = newOrdersAdminAlert::where([['adminId',$req->aId],['tableNr',$req->tableNr],['statActive','1']])->count();
        if($AdmAlertOnTable <= 0){ return 'tableEmpty'; }else{ return 'tableActive'; }
    }






    public function alertAdmAWaiterClPay(Request $req){
        // resId: $('#theRestaurant').val(),
        // tableNr: $('#theTable').val(),

        foreach(User::where([['sFor',$req->resId],['role','5']])->get() as $user){
            $details = [
                'id' => $req->resId,
                'type' => 'clPayUnconfirmedAlert',
                'tableNr' => $req->tableNr
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',$req->resId],['role','55']])->get() as $oneWaiter){
            $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->tableNr]])->first();
            if($aToTable != NULL && $aToTable->statusAct == 1){
                // register the notification ...
                $details = [
                    'id' => $req->resId,
                    'type' => 'clPayUnconfirmedAlert',
                    'tableNr' => $req->tableNr
                ];
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        
    }























    public function payAllFetchOrders(Request $req){
        $filteredData = TabOrder::where([['tabCode','!=','0'],['status','!=','9'],['toRes',$req->resId],['tableNr',$req->tNr]])->get();
        return json_encode( $filteredData );
    }
    public function paySelFetchOrders(Request $req){
        $allSelOr = array();
        foreach(explode('||',$req->selProds) as $prodsToPay){
            array_push($allSelOr,$prodsToPay);
        }
        $filteredData = TabOrder::where([['tableNr',$req->tNr],['toRes',$req->resId],['status','!=','9'],['tabCode','!=','0']])->whereIn('id',$allSelOr)->get();

        return json_encode( $filteredData );
    }
    public function payAllVerifyTelSendNr(Request $req){
        // phoneNr
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
        
            // disa numrba perdoren per DEMO
            if($phToTestForSMS != '763270293' && $phToTestForSMS != '763251809' && $phToTestForSMS != '763459941' && $phToTestForSMS != '763469963'){
                $spryng = new Client('QRorpasms', 'Spryng@qrorpa.ch', 'qrorpa.ch');
                if($spryng->sms->checkBalance() > 0){
                    try {
                        $spryng->sms->send($sendTo2,'Verwenden Sie diesen Code '.$randCode.', um Ihre Telefonnummer für die Quittung von QRorpa zu bestätigen.', array(
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
        }else{
            return "payAllVerifyTelSendNrFail";
        }
    }







    public function saveTheWHForReportGen(Request $req){
        $theRe = Restorant::find(Auth::user()->sFor);

        $otherDay = 0;

        $strTime1 = number_format($req->strTime1,0,'.',''); 
        $strTime2 = number_format($req->strTime2,0,'.',''); 
        $endTime1 = number_format($req->endTime1,0,'.',''); 
        $endTime2 = number_format($req->endTime2,0,'.',''); 
        if($strTime1 == $endTime1 && $strTime2 > $endTime2){
            $otherDay = 1;
        }else if($strTime1 > $endTime1){
            $otherDay = 1;
        }

        $ti = $req->strTime1.':'.$req->strTime2.'->'.$req->endTime1.':'.$req->endTime2;

        $theRe->reportTimeArc = $ti;
        $theRe->reportTimeOtherDay = $otherDay;
        $theRe->save();
    }

    public function saveNewGroup(Request $req){
        $newGr = new pdfResProdCats();
        $newGr->catTitle = $req->grName;
        $newGr->toRes = $req->resId;
        $newGr->save();
    }

    public function deleteReportGroup(Request $req){
        $theGr = pdfResProdCats::find($req->grId);
        if( $theGr != NULL ){
            foreach(Produktet::where('toReportCat',$req->grId)->get() as $chngPro){
                $chngPro->toReportCat = 0;
                $chngPro->save();
            }
            foreach(Takeaway::where('toReportCat',$req->grId)->get() as $chngProTA){
                $chngProTA->toReportCat = 0;
                $chngProTA->save();
            }
            foreach(DeliveryProd::where('toReportCat',$req->grId)->get() as $chngProDE){
                $chngProDE->toReportCat = 0;
                $chngProDE->save();
            }

            $theGr->isActive = 0;
            $theGr->save();
        }
       
    }

    public function catRepSetProdToCat(Request $req){
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

    public function catRepSetProdToCatTA(Request $req){
        $theTaP = Takeaway::findOrFail($req->taPrId);
        if($req->repCat == $theTaP->toReportCat){
            $theTaP->toReportCat = 0;
            $theTaP->save();

            return 'removed';
        }else{
            if($theTaP->toReportCat == 0){
                $theTaP->toReportCat = $req->repCat;
                $theTaP->save();

                return 'added';
            }else{
                $theTaP->toReportCat = $req->repCat;
                $theTaP->save();

                return 'addedPlus'; 
            }

        }
    }


    public function catRepSetAllCatToCat(Request $req){
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
        foreach(Takeaway::where('kategoria',$req->catId)->get() as $oneTaPr){
            $oneTaPr->toReportCat = $req->repCat;
            $oneTaPr->save();

        }
    }


    public function statBillsSave(Request $req){
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

    public function statBillsGetDocs(Request $req){
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
            $bExpRecords = billsExpensesRecordRes::where([['forRes',$req->resId],['forDate',$req->theDt]])->first();
            if($bExpRecords != Null){ 
                $bExpValue = number_format($bExpRecords->expValue,2,'.',''); 
                $bExpValueCash = number_format($bExpRecords->expCash,2,'.',''); 
                $stafFrom = User::find($bExpRecords->fromStaf);
                $stafFromSent = $stafFrom->name.' '.$stafFrom->lastname; 

                if($bExpRecords->expComment != 'empty'){$expCommSent = $bExpRecords->expComment;
                }else{$expCommSent = '';}
            }else{ 
                $bExpValue = number_format(0,2,'.',''); 
                $bExpValueCash = number_format(0,2,'.',''); 
                $stafFromSent = '--- ---'; 
                $expCommSent = '';
            }
            return $respo.'|||'.$bExpValue.'|||'.$stafFromSent.'|||'.$expCommSent.'|||'.$bExpValueCash;
        }else{
            $bExpRecords = billsExpensesRecordRes::where([['forRes',$req->resId],['forDate',$req->theDt]])->first();
            if($bExpRecords != Null){ 
                $bExpValue = number_format($bExpRecords->expValue,2,'.',''); 
                $bExpValueCash = number_format($bExpRecords->expCash,2,'.',''); 
                $stafFrom = User::find($bExpRecords->fromStaf);
                $stafFromSent = $stafFrom->name.' '.$stafFrom->lastname; 

                if($bExpRecords->expComment != 'empty'){$expCommSent = $bExpRecords->expComment;
                }else{$expCommSent = '';}
            }else{ 
                $bExpValue = number_format(0,2,'.',''); 
                $bExpValueCash = number_format(0,2,'.',''); 
                $stafFromSent = '--- ---'; 
                $expCommSent = '';
            }

            return 'zero'.'|||'.$bExpValue.'|||'.$stafFromSent.'|||'.$expCommSent.'|||'.$bExpValueCash;
        }
    }

    public function statBillsGetDocsW(Request $req){
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
            $bExpRecords = billsExpensesRecordRes::where([['forRes',$req->resId],['forDate',$req->theDt]])->first();
            if($bExpRecords != Null){ 
                $bExpValue = number_format($bExpRecords->expValue,2,'.',''); 
                $bExpValueCash = number_format($bExpRecords->expCash,2,'.','');
                $stafFrom = User::find($bExpRecords->fromStaf);
                $stafFromSent = $stafFrom->name.' '.$stafFrom->lastname; 

                if($bExpRecords->expComment != 'empty'){$expCommSent = $bExpRecords->expComment;
                }else{$expCommSent = '';}
            }else{ 
                $bExpValue = number_format(0,2,'.','');
                $bExpValueCash = number_format(0,2,'.',''); 
                $stafFromSent = '--- ---'; 
                $expCommSent = '';
            }
            return $respo.'|||'.$bExpValue.'|||'.$stafFromSent.'|||'.$expCommSent.'|||'.$bExpValueCash;
        }else{
            $bExpRecords = billsExpensesRecordRes::where([['forRes',$req->resId],['forDate',$req->theDt]])->first();
            if($bExpRecords != Null){ 
                $bExpValue = number_format($bExpRecords->expValue,2,'.',''); 
                $bExpValueCash = number_format($bExpRecords->expCash,2,'.','');
                $stafFrom = User::find($bExpRecords->fromStaf);
                $stafFromSent = $stafFrom->name.' '.$stafFrom->lastname; 

                if($bExpRecords->expComment != 'empty'){$expCommSent = $bExpRecords->expComment;
                }else{$expCommSent = '';}
            }else{ 
                $bExpValue = number_format(0,2,'.',''); 
                $bExpValueCash = number_format(0,2,'.','');
                $stafFromSent = '--- ---'; 
                $expCommSent = '';
            }

            return 'zero'.'|||'.$bExpValue.'|||'.$stafFromSent.'|||'.$expCommSent.'|||'.$bExpValueCash;
        }
    }

    public function setNewBillsExpenseValue(Request $req){
        $bExpRecords = billsExpensesRecordRes::where([['forRes',$req->resId],['forDate',$req->theDt]])->first();
        if($bExpRecords != Null){
            $bExpRecords->expValue = $req->newVa;
            $bExpRecords->expCash = $req->newCashVa;
            $bExpRecords->fromStaf = Auth::user()->id;
            $bExpRecords->expComment = $req->newVaComm;
            $bExpRecords->save();
        }else{
            $bExpRecords = new billsExpensesRecordRes();
            $bExpRecords->forRes = $req->resId;
            $bExpRecords->forDate = $req->theDt;
            $bExpRecords->expValue = $req->newVa;
            $bExpRecords->expCash = $req->newCashVa;
            $bExpRecords->fromStaf = Auth::user()->id;
            $bExpRecords->expComment = $req->newVaComm;
            $bExpRecords->save();
        }
        return Auth::user()->name.' '.Auth::user()->lastname;
    }








    public function updateProCatSortingNumbers(Request $req){
        // foreach(Restorant::all() as $res){
        //     $catCount = 1;
        //     foreach ( kategori::where('toRes',$res->id)->get()->sortBy('position') as $kat){
        //         $prodCount = 1;
        //         foreach(Produktet::where([['toRes',$res->id],['kategoria',$kat->id]])->get()->sortBy('position') as $pro){
        //             $pro->position = $prodCount++;
        //             $pro->save();
        //         }
        //         $kat->position = $catCount++;
        //         $kat->save();
        //     }
        // }
    }


    public function genEBankingQrCode(Request $req){
        $theOr = Orders::findOrFail($req->orId);
        $theRes = Restorant::findOrFail($theOr->Restaurant);
        $theOrExInfo = emailReceiptFromAdm::where('forOrder',$theOr->id)->first();

        if($theOrExInfo != NULL){

        $adr2D = explode(',',$theRes->adresa);
        $ad2 = '---';
        if(isset($adr2D[1])){
            $ad2 = $adr2D[1];
        }
        if(isset($adr2D[2])){
            $ad2 = $adr2D[1].','.$adr2D[2];
        }

        if($theOr->inCashDiscount > 0){
            $totPrice = number_format($theOr->shuma-$theOr->inCashDiscount - $theOr->dicsountGcAmnt, 2, '.', '');
        }else if($theOr->inPercentageDiscount > 0){
            $totPrice = number_format($theOr->shuma-($theOr->shuma*($theOr->inPercentageDiscount*0.01)) - $theOr->dicsountGcAmnt, 2, '.', '');
        }else{
            $totPrice = number_format($theOr->shuma - $theOr->dicsountGcAmnt, 2, '.', '');
        }

        $billNr = str_pad($theOr->id, 10, '0', STR_PAD_LEFT);
      
        $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
        shuffle($word);
        $name = substr(implode($word), 0, 25).'OrId'.$req->orId;
        $file = "storage/ebankqrcode/".$name.".png";

        $theOr->ebankqrcode = $name.".png";
        $theOr->save();

  // $theRes->resBankId

        $newQrcode = QRCode::text('SPC
0200
1
'.$theRes->resBankId.'
K
'.$theRes->emri.'
'.$adr2D[0].'
'.$ad2.'


CH







'.number_format($totPrice, 2, '.', '').'
CHF
K
'.$theOrExInfo->exInfoFirma.'
'.$theOrExInfo->exInfoStreet.'
'.$theOrExInfo->exInfoPlzOrt.' '.$theOrExInfo->exInfoLand.'


CH
NON

'.$billNr.'
EPD
')
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file)
        ->png();

        $img1 = Image::make('storage/ebankqrcode/'.$name.'.png');
        $img1->insert('storage/ebankqrcode/eBPIcon.png');
        $img1->save('storage/ebankqrcode/'.$name.'.png');

       

        return $name.".png";

        }
    }










    public function invTabOrSendCheck(Request $req){
        if(Auth::user()->sFor == 46){
            return 'none';
        }else{
            if(Auth::user()->sFor == 57){
                // Passagino-Gourmet GmbH
                $torToDel = TabOrder::where([['toRes',Auth::user()->sFor],['tabCode','!=',0]])->where('created_at', '<', Carbon::now()->subMinutes(900)->toDateTimeString())->get();
            }else if(Auth::user()->sFor == 54){
                // Restaurant Türmli
                $torToDel = TabOrder::where([['toRes',Auth::user()->sFor],['tabCode','!=',0]])->where('created_at', '<', Carbon::now()->subMinutes(720)->toDateTimeString())->get();
            }else{
                $torToDel = TabOrder::where([['toRes',Auth::user()->sFor],['tabCode','!=',0]])->where('created_at', '<', Carbon::now()->subMinutes(480)->toDateTimeString())->get();
            }
            $CntTorToDel = count($torToDel);
            if($CntTorToDel > 0){
                foreach($torToDel as $oneTOr){
                    $newLog = new logTabAutoRemove();
                    $newLog->toRes = $oneTOr->toRes ;
                    $newLog->tableNr = $oneTOr->tableNr;
                    $newLog->tabId = $oneTOr->id;
                    $newLog->permb = 'TabC:'.$oneTOr->tabCode.'--ProdId:'.$oneTOr->prodId.'--OrdEmri:'.$oneTOr->OrderSasia.'x '.$oneTOr->OrderEmri.'--Time'.$oneTOr->created_at;
                    $newLog->save();

                    $oneTOr->delete();
                }

                foreach(TableQrcode::where([['Restaurant',Auth::user()->sFor],['kaTab','!=','0']])->get() as $actTableOne){
                    if(TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$actTableOne->tableNr],['tabCode','!=',0]])->first() == Null){
                        $actTableOne->kaTab = 0;
                        $actTableOne->save();
                    }
                }

                return $CntTorToDel;
            }else{
                return 'none';
            }
        }
        
    }














    


    public function checkIfTableHasOrders(Request $req){
        $orCnt = TabOrder::where([['tabCode','!=','0'],['toRes',$req->resId],['tableNr',$req->tbNr]])->count();
        if($orCnt > 0){
            return 'hasActiveOr';
        }else{
            return 'doesNotHaveActiveOr';
        }
    }



    public function setGroupToCloseOrers(Request $req){
        if(isset($_GET['hash']) && $_GET['hash'] == 'qr10pe80'){

            foreach(Restorant::all() as $oneRes){
                foreach(Orders::where('Restaurant',$oneRes->id)->get() as $oneOrd){
                    $newPorosia = '';
                    $porosia2D = explode('---8---',$oneOrd->porosia);

                    if($oneOrd->nrTable == 9000){
                    }else if($oneOrd->nrTable == 500){
                        if($oneOrd->userPhoneNr == '0770000000'){
                            // Takeaway Order (From Staf)
                            foreach($porosia2D as $poroOne){
                                $poroOne2D = explode('-8-',$poroOne);
                                $theResPro = Produktet::find($poroOne2D[7]);
                                if($theResPro != Null){
                                    if($newPorosia == ''){ $newPorosia = $poroOne.'-8-'.$theResPro->toReportCat; }
                                    else{ $newPorosia .= '---8---'.$poroOne.'-8-'.$theResPro->toReportCat; }
                                }else{
                                    if($newPorosia == ''){ $newPorosia = $poroOne.'-8-0'; }
                                    else{ $newPorosia .= '---8---'.$poroOne.'-8-0'; }
                                }
                            }
                        }else{
                            // Takeaway Order (From Client)
                            foreach($porosia2D as $poroOne){
                                $poroOne2D = explode('-8-',$poroOne);
                                $theTAPro = Takeaway::find($poroOne2D[7]);
                                if($theTAPro != Null){
                                    if($newPorosia == ''){ $newPorosia = $poroOne.'-8-'.$theTAPro->toReportCat; }
                                    else{ $newPorosia .= '---8---'.$poroOne.'-8-'.$theTAPro->toReportCat; }
                                }else{
                                    if($newPorosia == ''){ $newPorosia = $poroOne.'-8-0'; }
                                    else{ $newPorosia .= '---8---'.$poroOne.'-8-0'; }
                                }
                            }
                        }

                    }else{
                        // Restaurant Order
                        foreach($porosia2D as $poroOne){
                            $poroOne2D = explode('-8-',$poroOne);
                            $theResPro = Produktet::find($poroOne2D[7]);
                            if($theResPro != Null){
                                if($newPorosia == ''){ $newPorosia = $poroOne.'-8-'.$theResPro->toReportCat; }
                                else{ $newPorosia .= '---8---'.$poroOne.'-8-'.$theResPro->toReportCat; }
                            }else{
                                if($newPorosia == ''){ $newPorosia = $poroOne.'-8-0'; }
                                else{ $newPorosia .= '---8---'.$poroOne.'-8-0'; }
                            }
                        }
                    }
                    $oneOrd->porosia = $newPorosia;
                    $oneOrd->save();
                }
            }
            
        }
    }


    public function copyARestoToAResto(Request $req){
        if(isset($_GET['hash']) && $_GET['hash'] == 'qr10pe81'){
            if(isset($_GET['resC']) && isset($_GET['resV'])){
                $resC = $_GET['resC'];
                $resV = $_GET['resV'];

                $cats = array();
                $extra = array();
                $types = array();

                $countCats = 0;
                $countExtr = 0;
                $countType = 0;
                $countProd = 0;
    
                foreach(kategori::where('toRes',$resV)->get() as $catDelete){ $catDelete->delete(); }
                foreach(ekstra::where('toRes',$resV)->get() as $extDelete){ $extDelete->delete(); }
                foreach(LlojetPro::where('toRes',$resV)->get() as $typDelete){ $typDelete->delete(); }
                foreach(Produktet::where('toRes',$resV)->get() as $prodDelete){ $prodDelete->delete(); }


                foreach(kategori::where('toRes',$resC)->get() as $catOne){

                    $regCat = new kategori();
                    $regCat->emri = $catOne->emri;
                    $regCat->foto = $catOne->foto;
                    $regCat->toRes = $resV;
                    $regCat->visits = 0;
                    $regCat->position = $catOne->position;
                    $regCat->positionTakeaway = $catOne->positionTakeaway;
                    $regCat->positionDelivery = $catOne->positionDelivery;
                    $regCat->sortingType = $catOne->sortingType;
                    $regCat->forPlate = 0;
                    $regCat->toGroup = 0;
                    $regCat->acsByClRes = $catOne->acsByClRes;
                    $regCat->acsByClTA = $catOne->acsByClTA;
                    $regCat->acsByCldDE = $catOne->acsByCldDE;
                    $regCat->save();

                    $cats[$catOne->id] = $regCat->id;
                    $countCats++;
                }

                foreach(ekstra::where('toRes',$resC)->get() as $extOne){
                    $regExt = new ekstra();
                    $regExt->emri = $extOne->emri;
                    $regExt->qmimi = $extOne->qmimi;
                    $regExt->toCat = $cats[$extOne->toCat];
                    $regExt->toRes = $resV;
                    $regExt->save();

                    $extra[$extOne->id] = $regExt->id;
                    $countExtr++;
                }

                foreach(LlojetPro::where('toRes',$resC)->get() as $typOne){
                    $regType = new LlojetPro();
                    $regType->emri = $typOne->emri;
                    $regType->vlera = $typOne->vlera;
                    $regType->kategoria = $cats[$typOne->kategoria];
                    $regType->toRes = $resV;
                    $regType->save();

                    $types[$typOne->id] = $regType->id;
                    $countType++;
                }

                foreach(Produktet::where('toRes',$resC)->get() as $prodOne){
                    $newType = NULL;
                    $newExtra = NULL;
                    if($prodOne->extPro != NULL){
                        foreach(explode('--0--',$prodOne->extPro) as $exOne){
                            $exOne2D = explode('||',$exOne);
                            if(isset($exOne2D[0]) && $exOne2D[0] != 0 && isset($exOne2D[1]) && isset($exOne2D[2])){
                                if($newExtra == NULL){
                                    $newExtra = $extra[$exOne2D[0]].'||'.$exOne2D[1].'||'.$exOne2D[2];
                                }else{
                                    $newExtra .= '--0--'.$extra[$exOne2D[0]].'||'.$exOne2D[1].'||'.$exOne2D[2];
                                }
                            }
                        }
                    }
                    if($prodOne->type != NULL){
                        foreach(explode('--0--',$prodOne->type) as $tyOne){
                            $tyOne2D = explode('||',$tyOne);
                            if(isset($tyOne2D[0]) && $tyOne2D[0] != 0 && isset($tyOne2D[1]) && isset($tyOne2D[2])){
                                if($newType == NULL){
                                    $newType = $types[$tyOne2D[0]].'||'.$tyOne2D[1].'||'.$tyOne2D[2];
                                }else{
                                    $newType .= '--0--'.$types[$tyOne2D[0]].'||'.$tyOne2D[1].'||'.$tyOne2D[2];
                                }
                            }
                        }
                    }

                    $reqProd = new Produktet();
                    $reqProd->emri = $prodOne->emri;
                    $reqProd->pershkrimi = $prodOne->pershkrimi;
                    $reqProd->kategoria = $cats[$prodOne->kategoria];
                    $reqProd->qmimi = $prodOne->qmimi;
                    $reqProd->qmimi2 = $prodOne->qmimi2;
                    $reqProd->extPro = $newExtra;
                    $reqProd->type = $newType;
                    $reqProd->toRes = $resV;
                    $reqProd->restrictPro = $prodOne->restrictPro;
                    $reqProd->doneIn = $prodOne->doneIn;
                    $reqProd->visits = 0;
                    $reqProd->position = $prodOne->position;
                    $reqProd->toReportCat = 0;
                    $reqProd->accessableByClients = $prodOne->accessableByClients;
                    $reqProd->save();

                    $countProd++;
                }

                dd('kategori:'.$countCats.' --- Extra:'.$countExtr.' --- Tipe:'.$countType.' --- Produkte:'.$countProd);


            }else{
                dd('Put two restaurants is resC and resV!');
            }
        }else{
            dd('You are not allowed to use this!');
        }
    }










    public function deleteResCont(Request $req){
        if(isset($_GET['hash']) && $_GET['hash'] == '3grfFjT53fHgh5434FgFEyJK4366gGVjhG856fTfyvbfhvfv3567774fvghrfFUHghgcf456778' ){
            if(isset($_GET['resId']) && isset($_GET['st'])){
                $theRes = Restorant::find($_GET['resId']);
                if($theRes == Null){ dd('Restaurant not found'); }
                if($_GET['st'] == 'clean' || $_GET['st'] == 'deleteAll'){
                    // ----------------------------------------------------------------------
                    $del001 = 0;
                    $del002 = 0;
                    $del003 = 0;
                    foreach(adsActiveToRes::where('resID',$theRes->id)->get() as $toDel001){ 
                        $adMod = adsMod::find($toDel001->adID);
                        if($adMod != Null){ $adMod->delete(); $del002++; }
                        $toDel001->delete(); 
                        $del001++;
                    }
                    foreach(adsRepeatInterval::where('toRes',$theRes->id)->get() as $toDel003){ 
                        $toDel003->delete(); 
                        $del003++;
                    }
                    echo '\r\n 1.Ads Active: '.$del001.' -- Ads Mods: '.$del002.'Ads Repeat interval: '.$del003;
                    // ----------------------------------------------------------------------
                    $del002 = 0;
                    foreach(billsExpensesRecordRes::where('forRes',$theRes->id)->get() as $toDel002){ 
                        $toDel002->delete(); 
                        $del002++;
                    }
                    echo '\r\n 2.bills expenses:'.$del002;
                    // ----------------------------------------------------------------------
                    $del003 = 0;
                    foreach(billsRecordRes::where('forRes',$theRes->id)->get() as $toDel003){ 
                        $toDel003->delete(); 
                        $del003++;
                    }
                    echo '\r\n 3.Bills record:'.$del003;
                    // ----------------------------------------------------------------------
                    $del004 = 0;
                    foreach(emailReceiptFromAdm::where('forRes',$theRes->id)->get() as $toDel004){ 
                        $toDel004->delete(); 
                        $del004++;
                    }
                    echo '\r\n 4.E-mail receipts:'.$del004;
                    // ----------------------------------------------------------------------
                    $del005 = 0;
                    foreach(newOrdersAdminAlert::where('toRes',$theRes->id)->get() as $toDel005){ 
                        $toDel005->delete(); 
                        $del005++;
                    }
                    echo '\r\n 5.New order admin alert:'.$del005;
                    // ----------------------------------------------------------------------
                    $del006 = 0;
                    foreach(notifyClient::where('toRes',$theRes->id)->get() as $toDel006){ 
                        $toDel006->delete(); 
                        $del006++;
                    }
                    echo '\r\n 6.Client notify:'.$del006;
                    // ----------------------------------------------------------------------
                    $del007 = 0;
                    foreach(onlinePayQRCStaf::where('resId',$theRes->id)->get() as $toDel007){ 
                        $toDel007->delete(); 
                        $del007++;
                    }
                    echo '\r\n 7.Online pay QRCode staf:'.$del007;
                    // ----------------------------------------------------------------------
                    $del0081 = 0;
                    $del0082 = 0;
                    foreach(Orders::where('Restaurant',$theRes->id)->get() as $toDel0081){ 
                        $toDel0081->delete(); 
                        $del0081++;
                    }
                    foreach(OrdersPassive::where('Restaurant',$theRes->id)->get() as $toDel0082){ 
                        $toDel0082->delete(); 
                        $del0082++;
                    }
                    echo '\r\n 8.Orders:'.$del0081.' -- OrdersPassive:'.$del0082;
                    // ----------------------------------------------------------------------
                    $del009 = 0;
                    foreach(OPSaferpayReference::where('toRes',$theRes->id)->get() as $toDel009){ 
                        $toDel009->delete(); 
                        $del009++;
                    }
                    echo '\r\n 9.Online Pay Saferpay reference:'.$del009;
                    // ----------------------------------------------------------------------
                    $del010 = 0;
                    foreach(rechnungClient::where('toRes',$theRes->id)->get() as $toDel010){ 
                        $toDel010->delete(); 
                        $del010++;
                    }
                    echo '\r\n 10.rechnung Clients:'.$del010;
                    // ----------------------------------------------------------------------
                    $del011 = 0;
                    foreach(rechnungClientForMonth::where('toRes',$theRes->id)->get() as $toDel011){ 
                        $toDel011->delete(); 
                        $del011++;
                    }
                    echo '\r\n 11.rechnung clients to months:'.$del011;
                    // ----------------------------------------------------------------------
                    $del012 = 0;
                    foreach(rechnungClientToBills::where('toRes',$theRes->id)->get() as $toDel012){ 
                        $toDel012->delete(); 
                        $del012++;
                    }
                    echo '\r\n 12.rechnung clients to bills:'.$del012;
                    // ----------------------------------------------------------------------
                    $del013 = 0;
                    foreach(rouletteUsage::where('toRes',$theRes->id)->get() as $toDel013){ 
                        $toDel013->delete(); 
                        $del013++;
                    }
                    echo '\r\n 13.roulette usage:'.$del013;
                    // ----------------------------------------------------------------------
                    $del014 = 0;
                    foreach(TableChngReq::where('toRes',$theRes->id)->get() as $toDel014){ 
                        $toDel014->delete(); 
                        $del014++;
                    }
                    echo '\r\n 14.table chenge requests:'.$del014;
                    // ----------------------------------------------------------------------
                    $del015 = 0;
                    foreach(tableChngReqsAdmin::where('toRes',$theRes->id)->get() as $toDel015){ 
                        $toDel015->delete(); 
                        $del015++;
                    }
                    echo '\r\n 15.table chenge requests (By staff):'.$del015;
                    // ----------------------------------------------------------------------
                    $del016 = 0;
                    foreach(TableReservation::where('toRes',$theRes->id)->get() as $toDel016){ 
                        $toDel016->delete(); 
                        $del016++;
                    }
                    echo '\r\n 16.Table reservation:'.$del016;
                    // ----------------------------------------------------------------------
                    $del017 = 0;
                    $del018 = 0;
                    foreach(TabOrder::where('toRes',$theRes->id)->get() as $toDel017){ 
                        $tverNr = tabVerificationPNumbers::where('tabOrderId',$toDel017->id)->first();
                        if($tverNr != Null){ $tverNr->delete();  $del018++; }
                        $toDel017->delete(); 
                        $del017++;
                    }
                    foreach(TableQrcode::where('Restaurant',$theRes->id)->get() as $tableOne){
                        $tableOne->kaTab = 0;
                        $tableOne->save();
                    }
                    echo '\r\n 17.Tab Orders:'.$del017;
                    echo '\r\n 18.Tab Orders Pnr. Verifitacion:'.$del018;
                    // ----------------------------------------------------------------------
                    $del019 = 0;
                    foreach(taDeForCookOr::where('toRes',$theRes->id)->get() as $toDel019){ 
                        $toDel019->delete(); 
                        $del019++;
                    }
                    echo '\r\n 19.Cook orders for TA & DE:'.$del019;
                    // ----------------------------------------------------------------------
                    $del020 = 0;
                    $del021 = 0;
                    foreach(User::where([['sFor',$theRes->id],['role','55']])->get() as $waiterOne){ 
                        foreach(waiterActivityLog::where('waiterId',$waiterOne->id)->get() as $toDel020){ 
                            $toDel020->delete(); 
                            $del020++;
                        }
                    }
                    foreach(User::where([['sFor',$theRes->id],['role','5']])->get() as $waiterOne){ 
                        foreach(waiterActivityLog::where('waiterId',$waiterOne->id)->get() as $toDel021){ 
                            $toDel021->delete(); 
                            $del021++;
                        }
                    }
                    echo '\r\n 20.Waiter activity:'.$del020;
                    echo '\r\n 21.Admin activity:'.$del021;
                }// END clean | deleteAll

                if($_GET['st'] == 'deleteAll'){
                    echo '\r\n --------------------DeleteAll Start--------------------';
                    $del022 = 0;
                    foreach(accessControllForAdmins::where('forRes',$theRes->id)->get() as $toDel022){ 
                        $toDel022->delete(); 
                        $del022++;
                    }
                    echo '\r\n 22.Access for staff: '.$del022;
                    // ----------------------------------------------------------------------
                    $del023 = 0;
                    foreach(admExtraAccessToRes::where('toRes',$theRes->id)->get() as $toDel023){ 
                        $toDel023->delete(); 
                        $del023++;
                    }
                    echo '\r\n 23.Staff exytra access to Res: '.$del023;
                    // ----------------------------------------------------------------------
                    $del024 = 0;
                    $del025 = 0;
                    foreach(cntGroupAdmWai::where('toRes',$theRes->id)->get() as $toDel024){ 
                        $toDel024->delete(); 
                        $del024++;
                    }
                    foreach(cntGroupL2AdmWai::where('toRes',$theRes->id)->get() as $toDel025){ 
                        $toDel025->delete(); 
                        $del025++;
                    }
                    echo '\r\n 24.CNT group: '.$del024;
                    echo '\r\n 25.CNT group L2: '.$del025;
                    // ----------------------------------------------------------------------
                    $del026 = 0;
                    foreach(cooksProductSelection::where('toRes',$theRes->id)->get() as $toDel026){ 
                        $toDel026->delete(); 
                        $del026++;
                    }
                    echo '\r\n 26.cooks prod selection: '.$del026;
                    // ----------------------------------------------------------------------
                    $del0xx = 0;
                    foreach(accessControllForAdmins::where('toRes',$theRes->id)->get() as $toDel0xx){ 
                        $toDel0xx->delete(); 
                        $del0xx++;
                    }
                    echo '\r\n xx.xxxxxxxxxxxxxxxxxxxxxxxxxx'.$del0xx;
                    // ----------------------------------------------------------------------
                    
                }// deleteAll (others)
                 


            }else{ dd('no restaurant ID(resId) and/or no format(st)'); }
        }else{ dd('Hash not valid'); }
    }




    public function copyOrdersToOrdersPassive(Request $req){
        if(isset($_GET['hash']) && $_GET['hash'] == '123555789222333' ){
            $orders = Orders::where('created_at', '>', Carbon::now()->subDays(2)->toDateTimeString())->get();
            foreach($orders as $orOne){
                if(OrdersPassive::find($orOne->id) == Null){
                    $Orpassive = new OrdersPassive();
                    $Orpassive->id = $orOne->id;
                    $Orpassive->refId = $orOne->refId;
                    $Orpassive->Restaurant = $orOne->Restaurant;
                    $Orpassive->nrTable = $orOne->nrTable;
                    $Orpassive->statusi = $orOne->statusi;
                    $Orpassive->byId = $orOne->byId;
                    $Orpassive->userEmri = $orOne->userEmri;
                    $Orpassive->userEmail = $orOne->userEmail;
                    $Orpassive->userPhoneNr = $orOne->userPhoneNr;
                    $Orpassive->porosia = $orOne->porosia;
                    $Orpassive->resComment = $orOne->resComment;
                    $Orpassive->freeProdId = $orOne->freeProdId;
                    $Orpassive->payM = $orOne->payM;
                    $Orpassive->shuma = $orOne->shuma;
                    $Orpassive->shifra = $orOne->shifra;
                    $Orpassive->tipPer = $orOne->tipPer;
                    $Orpassive->cuponOffVal = $orOne->cuponOffVal;
                    $Orpassive->cuponProduct = $orOne->cuponProduct;
                    $Orpassive->StatusBy = $orOne->StatusBy;
                    $Orpassive->cancelComm = $orOne->cancelComm;
                    $Orpassive->TAemri = $orOne->TAemri;
                    $Orpassive->TAmbiemri = $orOne->TAmbiemri;
                    $Orpassive->TAtime = $orOne->TAtime;
                    $Orpassive->TAplz = $orOne->TAplz;
                    $Orpassive->TAort = $orOne->TAort;
                    $Orpassive->TAaddress = $orOne->TAaddress;
                    $Orpassive->TAkoment = $orOne->TAkoment;
                    $Orpassive->TAbowlingLine = $orOne->TAbowlingLine;
                    $Orpassive->inCashDiscount = $orOne->inCashDiscount;
                    $Orpassive->discReason = $orOne->discReason;
                    $Orpassive->inPercentageDiscount = $orOne->inPercentageDiscount;
                    $Orpassive->dicsountGcAmnt = $orOne->dicsountGcAmnt;
                    $Orpassive->mwstVal = $orOne->mwstVal;
                    $Orpassive->digitalReceiptQRK = $orOne->digitalReceiptQRK;
                    $Orpassive->digitalReceiptQRKHash = $orOne->digitalReceiptQRKHash;
                    $Orpassive->ebankqrcode = $orOne->ebankqrcode;
                    $Orpassive->orForWaiter = $orOne->orForWaiter;
                    $Orpassive->servedBy = $orOne->servedBy;
                    $Orpassive->created_at = $orOne->created_at;
                    $Orpassive->updated_at = $orOne->updated_at;
                    $Orpassive->save();
                }
            }

            foreach(Orders::where('created_at', '>', Carbon::now()->subDays(2)->toDateTimeString())->get() as $orOneAll){
                $pasOr = OrdersPassive::find($orOneAll->id);
                if($pasOr != Null){
                    if($pasOr->statusi != $orOneAll->statusi || $pasOr->StatusBy != $orOneAll->StatusBy || $pasOr->cancelComm != $orOneAll->cancelComm){
                        $pasOr->statusi = $orOneAll->statusi;
                        $pasOr->cancelComm = $orOneAll->cancelComm;
                        $pasOr->StatusBy = $orOneAll->StatusBy;
                        $pasOr->save();
                    }
                    if($pasOr->servedBy != $orOneAll->servedBy || $pasOr->orForWaiter != $orOneAll->orForWaiter){
                        $pasOr->servedBy = $orOneAll->servedBy;
                        $pasOr->orForWaiter = $orOneAll->orForWaiter;
                        $pasOr->save();
                    }
                }
            }

            $ordersToDel = Orders::where('created_at', '<', Carbon::now()->subDays(2)->toDateTimeString())->get();
            foreach($ordersToDel as $delOr){
                if(OrdersPassive::find($delOr->id) != Null){
                    $delOr->delete();
                }
            }

            // foreach(TabOrder::where('created_at', '>', Carbon::now()->subMinutes(10)->toDateTimeString())->get() as $mvTOOne){
            //     if(tabOrdersPassive::find($mvTOOne->id) == Null){
            //         $TOPassive = new tabOrdersPassive();
            //         $TOPassive->id = $mvTOOne->id;
            //         $TOPassive->tabCode = $mvTOOne->tabCode;
            //         $TOPassive->tableNr = $mvTOOne->tableNr;
            //         $TOPassive->toRes = $mvTOOne->toRes;
            //         $TOPassive->prodId = $mvTOOne->prodId;
            //         $TOPassive->OrderEmri = $mvTOOne->OrderEmri;
            //         $TOPassive->OrderPershkrimi = $mvTOOne->OrderPershkrimi;
            //         $TOPassive->OrderSasia = $mvTOOne->OrderSasia;
            //         $TOPassive->OrderSasiaDone = $mvTOOne->OrderSasiaDone;
            //         $TOPassive->OrderQmimi = $mvTOOne->OrderQmimi;
            //         $TOPassive->OrderExtra = $mvTOOne->OrderExtra;
            //         $TOPassive->OrderType = $mvTOOne->OrderType;
            //         $TOPassive->OrderKomenti = $mvTOOne->OrderKomenti;
            //         $TOPassive->status = $mvTOOne->status;
            //         $TOPassive->specStat = $mvTOOne->specStat;
            //         $TOPassive->toPlate = $mvTOOne->toPlate;
            //         $TOPassive->abrufenStat = $mvTOOne->abrufenStat;
            //         $TOPassive->orderServed = $mvTOOne->orderServed;
            //         $TOPassive->created_at = $mvTOOne->created_at;
            //         $TOPassive->updated_at = $mvTOOne->updated_at;
            //         $TOPassive->save();
            //     }
            // }
            // foreach(TabOrder::where('created_at', '>', Carbon::now()->subMinutes(600)->toDateTimeString())->get() as $TOChange){
            //     if(tabOrdersPassive::find($TOChange->id) != Null){
            //         $TabOrderPassive = tabOrdersPassive::find($TOChange->id);
            //         $TabOrderPassive->tabCode = $TOChange->tabCode;
            //         $TabOrderPassive->OrderSasiaDone = $TOChange->OrderSasiaDone;
            //         $TabOrderPassive->status = $TOChange->status;
            //         $TabOrderPassive->specStat = $TOChange->specStat;
            //         $TabOrderPassive->updated_at = $TOChange->updated_at;
            //         $TabOrderPassive->save();
            //     }
            // }
            // foreach(TabOrder::all() as $TODelete){
            //     if(tabOrdersPassive::find($TODelete->id) != Null && $TODelete->tabCode == 0){
            //         $TODelete->delete();
            //     }
            // }


            // foreach(tabVerificationPNumbers::where('created_at', '>', Carbon::now()->subMinutes(10)->toDateTimeString())->get() as $mvTVerNrOne){
            //     if(tabVerificationPNumbersPassive::find($mvTVerNrOne->id) == Null){
            //         $tVerNrPassive = new tabVerificationPNumbersPassive();
            //         $tVerNrPassive->id = $mvTVerNrOne->id;
            //         $tVerNrPassive->phoneNr = $mvTVerNrOne->phoneNr;
            //         $tVerNrPassive->tabCode = $mvTVerNrOne->tabCode;
            //         $tVerNrPassive->tabOrderId = $mvTVerNrOne->tabOrderId;
            //         $tVerNrPassive->status = $mvTVerNrOne->status;
            //         $tVerNrPassive->specStat = $mvTVerNrOne->specStat;
            //         $tVerNrPassive->created_at = $mvTVerNrOne->created_at;
            //         $tVerNrPassive->updated_at = $mvTVerNrOne->updated_at;
            //         $tVerNrPassive->save();
            //     }
            // }
            // foreach(tabVerificationPNumbers::where('created_at', '<', Carbon::now()->subDays(1)->toDateTimeString())->get() as $TVerNrDelete){
            //     if(tabVerificationPNumbersPassive::find($TVerNrDelete->id) != Null){
            //         $TVerNrDelete->delete();
            //     }
            // }

            // foreach(waiterActivityLog::all() as $waLoOne){
            //     if(waiterActivityLogPassive::find($waLoOne->id) == Null){
            //         $waLoPass = new waiterActivityLogPassive();
            //         $waLoPass->id = $waLoOne->id;
            //         $waLoPass->waiterId = $waLoOne->waiterId;
            //         $waLoPass->actType = $waLoOne->actType;
            //         $waLoPass->actId = $waLoOne->actId;
            //         $waLoPass->sasia = $waLoOne->sasia;
            //         $waLoPass->save();
            //     }
            // }
            // foreach(waiterActivityLog::where('created_at', '<', Carbon::now()->subMinutes(600)->toDateTimeString())->get() as $waLoOne){
            //     if(waiterActivityLogPassive::find($waLoOne->id) != Null){
            //         $waLoOne->delete();
            //     }
            // }
        }
    }


    public function checkForCopyOrdersToOrdersPassive(Request $req){
        // $changed = 0;

        // $orders = Orders::where([['Restaurant',$req->resId],['created_at', '>', Carbon::now()->subMinutes(3)->toDateTimeString()]])->get();
        // foreach($orders as $orOne){
        //     if(OrdersPassive::find($orOne->id) == Null){
        //         $Orpassive = new OrdersPassive();
        //         $Orpassive->id = $orOne->id;
        //         $Orpassive->refId = $orOne->refId;
        //         $Orpassive->Restaurant = $orOne->Restaurant;
        //         $Orpassive->nrTable = $orOne->nrTable;
        //         $Orpassive->statusi = $orOne->statusi;
        //         $Orpassive->byId = $orOne->byId;
        //         $Orpassive->userEmri = $orOne->userEmri;
        //         $Orpassive->userEmail = $orOne->userEmail;
        //         $Orpassive->userPhoneNr = $orOne->userPhoneNr;
        //         $Orpassive->porosia = $orOne->porosia;
        //         $Orpassive->freeProdId = $orOne->freeProdId;
        //         $Orpassive->payM = $orOne->payM;
        //         $Orpassive->shuma = $orOne->shuma;
        //         $Orpassive->shifra = $orOne->shifra;
        //         $Orpassive->tipPer = $orOne->tipPer;
        //         $Orpassive->cuponOffVal = $orOne->cuponOffVal;
        //         $Orpassive->cuponProduct = $orOne->cuponProduct;
        //         $Orpassive->StatusBy = $orOne->StatusBy;
        //         $Orpassive->cancelComm = $orOne->cancelComm;
        //         $Orpassive->TAemri = $orOne->TAemri;
        //         $Orpassive->TAmbiemri = $orOne->TAmbiemri;
        //         $Orpassive->TAtime = $orOne->TAtime;
        //         $Orpassive->TAplz = $orOne->TAplz;
        //         $Orpassive->TAort = $orOne->TAort;
        //         $Orpassive->TAaddress = $orOne->TAaddress;
        //         $Orpassive->TAkoment = $orOne->TAkoment;
        //         $Orpassive->inCashDiscount = $orOne->inCashDiscount;
        //         $Orpassive->discReason = $orOne->discReason;
        //         $Orpassive->inPercentageDiscount = $orOne->inPercentageDiscount;
        //         $Orpassive->dicsountGcAmnt = $orOne->dicsountGcAmnt;
        //         $Orpassive->mwstVal = $orOne->mwstVal;
        //         $Orpassive->digitalReceiptQRK = $orOne->digitalReceiptQRK;
        //         $Orpassive->digitalReceiptQRKHash = $orOne->digitalReceiptQRKHash;
        //         $Orpassive->ebankqrcode = $orOne->ebankqrcode;
        //         $Orpassive->orForWaiter = $orOne->orForWaiter;
        //         $Orpassive->servedBy = $orOne->servedBy;
        //         $Orpassive->created_at = $orOne->created_at;
        //         $Orpassive->updated_at = $orOne->updated_at;
        //         $Orpassive->save();

        //         $changed++; 
        //     }
        // }

        // foreach(Orders::where('created_at', '>', Carbon::now()->subDays(2)->toDateTimeString())->get() as $orOneAll){
        //     $pasOr = OrdersPassive::find($orOneAll->id);
        //     if($pasOr != Null){
        //         if($pasOr->statusi != $orOneAll->statusi || $pasOr->cancelComm != $orOneAll->cancelComm){
        //             $pasOr->statusi = $orOneAll->statusi;
        //             $pasOr->cancelComm = $orOneAll->cancelComm;
        //             $pasOr->save();

        //             $changed++; 
        //         }
        //         if($pasOr->servedBy != $orOneAll->servedBy || $pasOr->orForWaiter != $orOneAll->orForWaiter){
        //             $pasOr->servedBy = $orOneAll->servedBy;
        //             $pasOr->orForWaiter = $orOneAll->orForWaiter;
        //             $pasOr->save();

        //             $changed++; 
        //         }
        //     }
        // }
        // if($changed > 0){
        //     return 'changesTrue';
        // }
    }









    public function checkInRegister(Request $req){
        $nowDt = Carbon::now();
        $newChInOutReg = new checkInOutReg();
        $newChInOutReg->toRes = Auth::user()->sFor;
        $newChInOutReg->userId = Auth::user()->id;
        $newChInOutReg->checkIn = $nowDt;
        $newChInOutReg->checkOut = 'empty';
        $newChInOutReg->theStat = 0;
        $newChInOutReg->save();

        $dt2D = explode('-',explode(' ',$nowDt)[0]);
        $hr2D = explode(':',explode(' ',$nowDt)[1]);
        $showDt = $dt2D[2].'.'.$dt2D[1].'.'.$dt2D[0].'<span style="margin-right:10px;"></span>'.$hr2D[0].':'.$hr2D[1].'||||'.$newChInOutReg->id;
        return $showDt;
    }
    public function checkOutRegister(Request $req){
        $nowDt = Carbon::now();
        $chIns = checkInOutReg::find($req->chInIns);
        $chIns->checkOut = $nowDt;
        $chIns->theStat = 1;
        $chIns->save();

        $dt2D = explode('-',explode(' ',$nowDt)[0]);
        $hr2D = explode(':',explode(' ',$nowDt)[1]);
        $showDt = $dt2D[2].'.'.$dt2D[1].'.'.$dt2D[0].'<span style="margin-right:10px;"></span>'.$hr2D[0].':'.$hr2D[1];
        return $showDt;
    }
    public function openCheckInOutReports(){ return view('adminPanel/adminIndex'); }
    public function openCheckInOutReportsWa(){ return view('adminPanelWaiter/adminIndexWaiter'); }

    public function getWaCheckInOutIns(Request $req){
        $filteredData = checkInOutReg::where([['userId',$req->waiterId],['theStat',1]])->orderByDesc('updated_at')->get();
        return json_encode( $filteredData );
    }

    public function getCheckInOutSalesRepo(Request $req){
        $checkInIns = checkInOutReg::find($req->chInId);

        $AllOrders = OrdersPassive::where([['servedBy',$req->workerId],['statusi',3],['created_at','>=',$checkInIns->checkIn],['created_at','<=',$checkInIns->checkOut]])->get();

        $orBarChf = number_format(0, 2, '.', '');
        $orCardChf = number_format(0, 2, '.', '');
        $orOnlineChf = number_format(0, 2, '.', '');
        $orRechnungChf = number_format(0, 2, '.', '');
        $orChf = number_format(0, 2, '.', '');
        $orTippChf = number_format(0, 2, '.', '');
        $orNr = 0;
        $discFromGC = number_format(0, 2, '.', '');
        foreach($AllOrders as $orOne){
            $orNr++;
            $totPayCHF = 0;

            if($orOne->inCashDiscount > 0){
                $totPayCHF = number_format((float)$orOne->shuma - (float)$orOne->inCashDiscount, 2, '.', '');
            }else if($orOne->inPercentageDiscount > 0){
                $totPayCHF = number_format((float)$orOne->shuma - (float)$orOne->tipPer, 2, '.', '');
                $valOff = number_format((float)$totPayCHF * ((float)$orOne->inPercentageDiscount/(float)100), 2, '.', '');
                $totPayCHF = number_format((float)$orOne->shuma - (float)$valOff, 2, '.', '');
            }else{
                $totPayCHF = number_format((float)$orOne->shuma, 2, '.', '');
            }

            $totPayCHF = number_format((float)$totPayCHF - (float)$orOne->dicsountGcAmnt, 2, '.', '');

            $discFromGC += number_format($orOne->dicsountGcAmnt, 2, '.', '');

            if($orOne->payM == 'Barzahlungen' || $orOne->payM == 'Cash'){
                $orBarChf += number_format((float)$totPayCHF, 2, '.', '');
            }else if($orOne->payM == 'Kartenzahlung'){
                $orCardChf += number_format((float)$totPayCHF, 2, '.', '');
            }else if($orOne->payM == 'Online' || $orOne->payM == 'Onlinezahlung'){
                $orOnlineChf += number_format((float)$totPayCHF, 2, '.', '');
            }else if($orOne->payM == 'Rechnung'){
                $orRechnungChf += number_format((float)$totPayCHF, 2, '.', '');
            }
            $orChf += number_format((float)$totPayCHF, 2, '.', '');
            $orTippChf += number_format((float)$orOne->tipPer, 2, '.', '');

            // Online
            // Onlinezahlung
            // Rechnung
        }

        $gcSalesTotalCHF = giftCard::where([['soldByStaff',$req->workerId],['created_at','>=',$checkInIns->checkIn],['created_at','<=',$checkInIns->checkOut]])->sum('gcSumInChf');
        $gcSalesCash = number_format(0, 2, '.', '');
        $gcSalesCard = number_format(0, 2, '.', '');
        $gcSalesOnline = number_format(0, 2, '.', '');
        $gcSalesRechnung = number_format(0, 2, '.', '');
        foreach(giftCard::where([['soldByStaff',$req->workerId],['created_at','>=',$checkInIns->checkIn],['created_at','<=',$checkInIns->checkOut]])->get() as $gcIns){
            if($gcIns->payM == 'Cash'){
                $gcSalesCash += number_format($gcIns->gcSumInChf, 2, '.', '');
            }else  if($gcIns->payM == 'Card'){
                $gcSalesCard += number_format($gcIns->gcSumInChf, 2, '.', '');
            }else  if($gcIns->payM == 'Online'){
                $gcSalesOnline += number_format($gcIns->gcSumInChf, 2, '.', '');
            }else  if($gcIns->payM == 'Rechnung'){
                $gcSalesRechnung += number_format($gcIns->gcSumInChf, 2, '.', '');
            }
        }
        return $orBarChf.'|||'.$orCardChf.'|||'.$orOnlineChf.'|||'.$orRechnungChf.'|||'.$orNr.'|||'.$orChf.'|||'.$orTippChf.'|||'.
        $gcSalesTotalCHF.'|||'.$discFromGC.'|||'.$gcSalesCash.'|||'.$gcSalesCard.'|||'.$gcSalesOnline.'|||'.$gcSalesRechnung;
    }






    public function admConfConfirmAll(Request $req){
        if($req->tabOrSelected != 0){
            $tabOrSelIds = array();
            $selectedTabOr = explode('||',$req->tabOrSelected); 
            foreach($selectedTabOr as $selectedTabOrOne){
                $selectedTabOrOne2D = explode('-8-',$selectedTabOrOne);
                $thisTabOr = TabOrder::find($selectedTabOrOne2D[0]);
                if((int)$thisTabOr->OrderSasia == (int)$selectedTabOrOne2D[1]){
                    // selected all
                    array_push($tabOrSelIds,$selectedTabOrOne2D[0]);
                }else{
                    // selected some

                    $priceForOneSelTabProd = number_format($thisTabOr->OrderQmimi/$thisTabOr->OrderSasia, 2, '.', '');
                    $qmimiOfSelected = number_format($priceForOneSelTabProd * (int)$selectedTabOrOne2D[1], 2, '.', '');

                    $thisTabOr->OrderSasia = $thisTabOr->OrderSasia - (int)$selectedTabOrOne2D[1];
                    $thisTabOr->OrderQmimi = number_format($thisTabOr->OrderQmimi - $qmimiOfSelected, 2, '.', '');
                    $thisTabOr->save();

                    // Save extra TAB order ....
                    $newTabOrder = new TabOrder;
                    $newTabOrder->tabCode = $thisTabOr->tabCode;
                    $newTabOrder->prodId = $thisTabOr->prodId;
                    $newTabOrder->OrderEmri = $thisTabOr->OrderEmri;
                    $newTabOrder->tableNr = $thisTabOr->tableNr;
                    $newTabOrder->toRes = $thisTabOr->toRes;
                    $newTabOrder->OrderPershkrimi= $thisTabOr->OrderPershkrimi;
                    $newTabOrder->OrderSasia = (int)$selectedTabOrOne2D[1];
                    $newTabOrder->OrderSasiaDone = 0;
                    $newTabOrder->OrderQmimi = number_format($qmimiOfSelected, 2, '.', '');
                    $newTabOrder->OrderExtra = $thisTabOr->OrderExtra;
                    $newTabOrder->OrderType = $thisTabOr->OrderType;
                    $newTabOrder->OrderKomenti = $thisTabOr->OrderKomenti;
                    $newTabOrder->status = 0;
                    $newTabOrder->toPlate = $thisTabOr->toPlate;
                    $newTabOrder->abrufenStat = $thisTabOr->abrufenStat;
                    $newTabOrder->created_at = $thisTabOr->created_at;
                    $newTabOrder->save();

                    $theNrVer = tabVerificationPNumbers::where('tabOrderId',$thisTabOr->id)->first();
                    // Save the number ....
                    $newNrVerification = new tabVerificationPNumbers;
                    $newNrVerification->phoneNr = $theNrVer->phoneNr;
                    $newNrVerification->tabCode = $theNrVer->tabCode;
                    $newNrVerification->tabOrderId = $newTabOrder->id;
                    $newNrVerification->status = 1;
                    $newNrVerification->save();

                    $newTabOrder->usrPhNr = $theNrVer->phoneNr;
                    $newTabOrder->save();

                    $waLogPrev = waiterActivityLog::where([['actType','newProdWa'],['actId',$thisTabOr->id]])->first();
                    $waLogPrev->sasia = $thisTabOr->OrderSasia;
                    $waLogPrev->save();

                    $waLog = new waiterActivityLog();
                    $waLog->waiterId = $waLogPrev->waiterId;
                    $waLog->actType = 'newProdWa';
                    $waLog->actId = $newTabOrder->id;
                    $waLog->sasia = (int)$selectedTabOrOne2D[1];
                    $waLog->save();

                    array_push($tabOrSelIds,$newTabOrder->id);
                }
            }
            $allTOrds = TabOrder::where([['tableNr',$req->tableNr],['tabCode',$req->tabCode],['status',0]])->whereIn('id', $tabOrSelIds)->get();
            // value="406-8-1||403-8-1"
        }else{
            $allTOrds = TabOrder::where([['tableNr',$req->tableNr],['tabCode',$req->tabCode],['status',0]])->get();
        }
       
        if($req->indication == '0'){
            foreach( $allTOrds as $oneTO){
                $oneTO->status = 1;
                $oneTO->save();

                // Send Notifications for the Clients
                $toDoCart = 1;
                $phoneNrActive = array();
                foreach(tabVerificationPNumbers::where([['tabCode',$req->tabCode],['status','1']])->get() as $nrVers){
                    if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){

                        $newNotifyClient = new notifyClient();
                        $newNotifyClient->for = "CartMsg";
                        $newNotifyClient->toRes = $oneTO->toRes;
                        $newNotifyClient->tableNr = $oneTO->tableNr;
                        $newNotifyClient->clPhoneNr = $nrVers->phoneNr;
                        $newNotifyClient->data = json_encode([
                            'toDoCart' => $toDoCart,
                            'payAllOrMineSelected' => 'none'
                        ]);
                        $newNotifyClient->readInd = 0;
                        $newNotifyClient->save();
        
                        array_push($phoneNrActive,$nrVers->phoneNr);
                    }
                }

                // Send Notifications for the Admins
                foreach(User::where([['sFor',$oneTO->toRes],['role','5']])->get() as $user){
                    if($user->id != auth()->user()->id){
                        $details = [
                            'id' => $oneTO->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $oneTO->tableNr
                        ];
                        $user->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
                // Send Notifications for the Waiters
                foreach(User::where([['sFor',$oneTO->toRes],['role','55']])->get() as $oneWaiter){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $oneTO->tableNr]])->first();
                    if($oneWaiter->id != Auth::user()->id && $aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $oneTO->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $oneTO->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
                // Send Notifications for the Cooks
                foreach(User::where([['sFor',$oneTO->toRes],['role','54']])->get() as $oneCook){
                    $details = [
                        'id' => $oneTO->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $oneTO->tableNr
                    ];
                    $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
        }else{
            foreach( $allTOrds as $oneTO){
                $tVerNr = tabVerificationPNumbers::where('tabOrderId',$oneTO->id)->first();
                if($tVerNr != Null && $tVerNr->phoneNr == $req->indication){
                    $oneTO->status = 1;
                    $oneTO->save();

                    // Send Notifications for the Clients
                    $toDoCart = 1;
                    $phoneNrActive = array();
                    foreach(tabVerificationPNumbers::where([['tabCode',$req->tabCode],['status','1']])->get() as $nrVers){
                        if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){

                            $newNotifyClient = new notifyClient();
                            $newNotifyClient->for = "CartMsg";
                            $newNotifyClient->toRes = $oneTO->toRes;
                            $newNotifyClient->tableNr = $oneTO->tableNr;
                            $newNotifyClient->clPhoneNr = $nrVers->phoneNr;
                            $newNotifyClient->data = json_encode([
                                'toDoCart' => $toDoCart,
                                'payAllOrMineSelected' => 'none'
                            ]);
                            $newNotifyClient->readInd = 0;
                            $newNotifyClient->save();
            
                            array_push($phoneNrActive,$nrVers->phoneNr);
                        }
                    }

                    // Send Notifications for the Admins
                    foreach(User::where([['sFor',$oneTO->toRes],['role','5']])->get() as $user){
                        if($user->id != auth()->user()->id){
                            $details = [
                                'id' => $oneTO->id,
                                'type' => 'AdminUpdateOrdersP',
                                'tableNr' => $oneTO->tableNr
                            ];
                            $user->notify(new \App\Notifications\NewOrderNotification($details));
                        }
                    }
                    // Send Notifications for the Waiters
                    foreach(User::where([['sFor',$oneTO->toRes],['role','55']])->get() as $oneWaiter){
                        $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $oneTO->tableNr]])->first();
                        if($oneWaiter->id != Auth::user()->id && $aToTable != NULL && $aToTable->statusAct == 1){
                            // register the notification ...
                            $details = [
                                'id' => $oneTO->id,
                                'type' => 'AdminUpdateOrdersP',
                                'tableNr' => $oneTO->tableNr
                            ];
                            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                        }
                    }
                    // Send Notifications for the Cooks
                    foreach(User::where([['sFor',$oneTO->toRes],['role','54']])->get() as $oneCook){
                        $details = [
                            'id' => $oneTO->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $oneTO->tableNr
                        ];
                        $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
            }
        }
    }




    public function AbrufenCallProd(Request $req){
        $theTOr = TabOrder::find($req->tabOrderId);
        if($theTOr != Null){
            $theTOr->abrufenStat = 1;
            $theTOr->save();

            // Send Notifications for the Admins
            foreach(User::where([['sFor',$theTOr->toRes],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $theTOr->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $theTOr->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            // Send Notifications for the Waiters
            foreach(User::where([['sFor',$theTOr->toRes],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $theTOr->tableNr]])->first();
                if($oneWaiter->id != Auth::user()->id && $aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $theTOr->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $theTOr->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            // Send Notifications for the Cooks
            foreach(User::where([['sFor',$theTOr->toRes],['role','54']])->get() as $oneCook){
                $hasAccessCook = 0;
                $tp = Produktet::find($theTOr->prodId);
                if($tp != NULL){ 
                    $prodCookAcs = cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Product'],['contentId',$theTOr->prodId]])->first();
                    if($prodCookAcs != NULL){
                        $hasAccessCook = 1;
                    }
                    if( $hasAccessCook == 0 ){
                        $tc = kategori::find($tp->kategoria);
                        if($tc != NULL){ 
                            $catCookAcs = cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Category'],['contentId',$tp->kategoria]])->first();
                            if($catCookAcs != NULL){
                                $hasAccessCook = 1;
                            }
                        }
                    }
                }
            
                if($hasAccessCook == 1){
                    $details = [
                        'id' => $theTOr->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $theTOr->tableNr
                    ];
                    $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
        }
    }

    public function AbrufenCallByPlate(Request $req){
        foreach(TabOrder::where([['tabCode','!=',0],['toRes',Auth::user()->sFor],['tableNr',$req->taNr],['toPlate',$req->plaId]])->get() as $tOrOne){
            $tOrOne->abrufenStat = 1;
            $tOrOne->save();

            // Send Notifications for the Admins
            foreach(User::where([['sFor',$tOrOne->toRes],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $tOrOne->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $tOrOne->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            // Send Notifications for the Waiters
            foreach(User::where([['sFor',$tOrOne->toRes],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $tOrOne->tableNr]])->first();
                if($oneWaiter->id != Auth::user()->id && $aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $tOrOne->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $tOrOne->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            // Send Notifications for the Cooks
            foreach(User::where([['sFor',$tOrOne->toRes],['role','54']])->get() as $oneCook){
                $hasAccessCook = 0;
                $tp = Produktet::find($tOrOne->prodId);
                if($tp != NULL){ 
                    $prodCookAcs = cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Product'],['contentId',$tOrOne->prodId]])->first();
                    if($prodCookAcs != NULL){
                        $hasAccessCook = 1;
                    }
                    if( $hasAccessCook == 0 ){
                        $tc = kategori::find($tp->kategoria);
                        if($tc != NULL){ 
                            $catCookAcs = cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Category'],['contentId',$tp->kategoria]])->first();
                            if($catCookAcs != NULL){
                                $hasAccessCook = 1;
                            }
                        }
                    }
                }
            
                if($hasAccessCook == 1){
                    $details = [
                        'id' => $tOrOne->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $tOrOne->tableNr
                    ];
                    $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            
        }
    }














    public function billTabletSaveTablet(Request $req){
        
        $newBillTabl = new billTabletsReg();
        $newBillTabl->tabletBillType = $req->bTType ;
        $newBillTabl->toRes = Auth::user()->sFor ;
        if($req->stafSelId != 0){
            $newBillTabl->toStaffId = $req->stafSelId ;
            $newBillTabl->toStaffName = User::find($req->stafSelId)->name ;
        }
        $newBillTabl->nameTitle = $req->bTName;
        $newBillTabl->currStat = 1;
        $newBillTabl->qrCodeImg = 'empty';
        $newBillTabl->scrHash = 'empty';

        $newBillTabl->save();

        $word2 = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'));
        shuffle($word2);
        $hash = substr(implode($word2), 0, 128);

        $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
        shuffle($word);
        $name = 'billTabletQrCode_'.$newBillTabl->id;
        $file = "storage/billTabletQrCode/".$name.".png";

        $newQrcode = QRCode::URL('qrorpa.ch/BillTabletsActive?hs='.$hash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file)
        ->png();

        $newBillTabl->qrCodeImg = $name.".png";
        $newBillTabl->scrHash = $hash;

        $newBillTabl->save();
    }

    public function billTabletEditTablet(Request $req){
        $billTabl = billTabletsReg::find($req->btId);
        if($billTabl != Null){
            $billTabl->nameTitle = $req->btNewName;
            $billTabl->save();
            return 'Success';
        }else{
            return 'Error';
        }
    }
    public function billTabletEditTabletTA(Request $req){
        $billTabl = billTabletsReg::find($req->btId);
        if($billTabl != Null){
            $billTabl->nameTitle = $req->btNewName;
            $billTabl->toStaffId = $req->btNewStaffId;
            $billTabl->toStaffName = User::find($req->btNewStaffId)->name;
            $billTabl->save();
            return 'Success';
        }else{
            return 'Error';
        }
    }

    public function billTabletDeleteTablet(Request $req){
        $billTabl = billTabletsReg::find($req->bTId);
        if($billTabl != Null){
            $billTabl->delete();
            return 'Success';
        }else{
            return 'Error';
        }
    }

    public function sendBillToTabletWaiting(Request $req){
        $btCrrSt = billTabletsCrrStat::find($req->btStatid);
        if($btCrrSt != Null){
            $btCrrSt->statOfTbl = 2;
            $btCrrSt->forTableNr = $req->tblNr;
            $btCrrSt->updated_at = Carbon::now();
            $btCrrSt->save();

            return 'Success';
        }else{
            return 'Error';
        }
    }


    public function sendNewTabletStatus(Request $req){
        $btCrrSt = billTabletsCrrStat::where('billTbltId',$req->btid)->first();
        $billTabl = billTabletsReg::find($req->btid);
        if($btCrrSt != Null){
            if( $btCrrSt->statOfTbl == 2){
                if(TabOrder::where([['toRes',$billTabl->toRes],['tableNr',$btCrrSt->forTableNr],['tabCode','>',0]])->count() > 0){
                    $btCrrSt->updated_at = Carbon::now();
                    $btCrrSt->save();

                    return $btCrrSt->forTableNr;
                }else{
                    $btCrrSt->statOfTbl = 1;
                    $btCrrSt->updated_at = Carbon::now();
                    $btCrrSt->save();
                    return 'returnToNull';
                }
            }else{
                $btCrrSt->statOfTbl = 1;
                $btCrrSt->updated_at = Carbon::now();
                $btCrrSt->save();
                return 'toOne';
            }
        }else{
            $newBTCrrStat = new billTabletsCrrStat();
            $newBTCrrStat->toRes = $billTabl->toRes;
            $newBTCrrStat->billTbltId = $req->btid;
            $newBTCrrStat->statOfTbl = 1; 
            $newBTCrrStat->save();
            return 'toOne';
        }
    }

    public function getAndDisplayOrdersInTablet(Request $req){
        $billTabl = billTabletsReg::find($req->btid);

        $filteredData = TabOrder::where([['toRes',$billTabl->toRes],['tableNr',$req->tableNr],['tabCode','>',0]])->get();

        return json_encode( $filteredData );
    }

    public function billTabletSetNewTipp(Request $req){
        foreach(billTabletsReg::where('toStaffId',$req->staffId)->get() as $billTabl){
            $billTabl->currTipp = number_format($req->tippVal, 2, '.', '');
            $billTabl->save();
        }
    }
    public function billTabletSetNewRabatt(Request $req){
        foreach(billTabletsReg::where('toStaffId',$req->staffId)->get() as $billTabl){
            $billTabl->currRabatt = number_format($req->discVal, 2, '.', '');
            $billTabl->save();
        }
    }
    public function billTabletSetNewGC(Request $req){
        foreach(billTabletsReg::where('toStaffId',$req->staffId)->get() as $billTabl){
            $billTabl->currGCValue = number_format($req->gcVal, 2, '.', '');
            $billTabl->currGCName = $req->gcId;
            $billTabl->save();
        }
    }











    public function orServingActThePage(Request $req){
        $theR = Restorant::find(Auth::user()->sFor);
        if($theR->orderServerHash == 'empty'){

            $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'), range('A', 'Z'));
            shuffle($word);
            $hash = substr(implode($word), 0, 128);
         
            $name = $hash;
            $file = "storage/orderServingQRCode/".$name.".png";

            $newQrcode = QRCode::URL('qrorpa.ch/orServingPage?hs='.$hash)
            ->setSize(64)
            ->setMargin(0)
            ->setOutfile($file)
            ->png();

            $theR->orderServerHash = $hash;
            $theR->save();

            return 'done';
        }else{
            return 'notDone';
        }
    }





    public function orderServingDevicesSave(Request $req){
        
        $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        $hash = substr(implode($word), 0, 128);
     
        $name = $hash;
        $file = "storage/orderServingQRCode/".$name.".png";

        $newQrcode = QRCode::URL('qrorpa.ch/orServingPage?hs='.$hash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file)
        ->png();

        $newDevice = new orderServingDevices();
        $newDevice->toRes = Auth::user()->sFor;
        $newDevice->deviceName = $req->devName;
        $newDevice->theHash = $hash;
        $newDevice->qrCodeName = $name.".png";
        $newDevice->save();

    }

    public function orderServingDevicesDelete(Request $req){
        $delDevice = orderServingDevices::find($req->devId);
        if( $delDevice != Null ){ 
            foreach(orderServingDevicesAccess::where('deviceId',$delDevice->id)->get() as $devAccOne){
                $devAccOne->delete();
            }
            $delDevice->delete(); 
        }
    }

    public function orderServingDevicesAddKatAccss(Request $req){
        $orAccss = new orderServingDevicesAccess();
        $orAccss->deviceId = $req->devId;
        $orAccss->accessType = 1;
        $orAccss->prodCatId = $req->kateId;
        $orAccss->save();

        return $orAccss->id;
    }
    public function orderServingDevicesRemoveKatAccss(Request $req){
        $orAccss = orderServingDevicesAccess::find($req->katAccId);
        if( $orAccss != Null ){ $orAccss->delete(); }
    }

    public function orderServingDevicesAddProdAccss(Request $req){
        $orAccss = new orderServingDevicesAccess();
        $orAccss->deviceId = $req->devId;
        $orAccss->accessType = 2;
        $orAccss->prodCatId = $req->productId;
        $orAccss->save();

        return $orAccss->id;
    }

    public function orderServingDevicesRemoveProdAccss(Request $req){
        $orAccss = orderServingDevicesAccess::find($req->prodAccId);
        if( $orAccss != Null ){ $orAccss->delete(); }
    }

    public function orderServingDevicesChngShowBlocks(Request $req){
        $device = orderServingDevices::find($req->deviceId);
        $device->showColPerDev = $req->newNrShown;
        $device->save();
    }

    public function orderServingDevicesConfServeProd(Request $req){
        $theOrShowIns = orderServingOrderShow::find($req->orShowDeviceId);
        if($theOrShowIns != Null){ 
            // $theOrShowIns->theStat = 1; 
            // $theOrShowIns->save();
            foreach(orderServingOrderShow::where('tabOrderId',$theOrShowIns->tabOrderId)->get() as $oneOrShowDel){
                $oneOrShowDel->delete();
            }
        }
        
        $to = TabOrder::find($req->tabOrderId);
        $to->orderServed = 1;
        $to->save();

        $thisProdukt = Produktet::find($to->prodId);
        foreach(orderServingDevices::where('toRes',$req->restoId)->get() as $oneDevi){
            if($oneDevi->id != $req->deviceId){
                if(orderServingDevicesAccess::where([['deviceId',$oneDevi->id],['accessType',1],['prodCatId',$thisProdukt->kategoria]])->first() != Null
                || orderServingDevicesAccess::where([['deviceId',$oneDevi->id],['accessType',2],['prodCatId',$thisProdukt->id]])->first() != Null){
                    $newOrNotificationDevi = new orderServingNotification();
                    $newOrNotificationDevi->deviceId = $oneDevi->id;
                    $newOrNotificationDevi->elementId = $req->tabOrderId;
                    $newOrNotificationDevi->notiType = 'changesFromOtherDevice';
                    $newOrNotificationDevi->save();
                }
            }
        }

        // Send Notifications for the Cooks
        foreach(User::where([['sFor',$req->restoId],['role','54']])->get() as $oneCook){
            $details = [
                'id' => $to->id,
                'type' => 'OrderServedToTable',
                'tableNr' => $to->tableNr
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }
    }

    public function orderServingDevicesCheckNotify(Request $req){
        $notyAnsw = '';
        $orShowDeleteCnt = 0;
        foreach(orderServingOrderShow::where('deviceId',$req->deviId)->get() as $ordShowOne){
            $theTO = TabOrder::find($ordShowOne->tabOrderId);
            if($theTO == Null){
                if( $notyAnsw == '' ){ $notyAnsw = 'tabOrderDeleted||0';
                }else{ $notyAnsw .= '--8--tabOrderDeleted||0'; }
                $ordShowOne->delete();
            }else if($theTO->tabCode == 0){
                if( $notyAnsw == '' ){ $notyAnsw = 'tabOrderPayed||0';
                }else{ $notyAnsw .= '--8--tabOrderPayed||0'; }
                $ordShowOne->delete();
            }
        }


        foreach(orderServingNotification::where([['deviceId',$req->deviId],['created_at', '>', Carbon::now()->subMinutes(3)]])->get() as $notyOne){
            if($notyOne->notiType == 'cookedByCookPanel'){
                if( $notyAnsw == '' ){ $notyAnsw = 'cookedByCookPanel||'.$notyOne->elementId;
                }else{ $notyAnsw .= '--8--cookedByCookPanel||'.$notyOne->elementId; }
            }else if($notyOne->notiType == 'changesFromOtherDevice'){
                if( $notyAnsw == '' ){ $notyAnsw = 'changesFromOtherDevice||'.$notyOne->elementId;
                }else{ $notyAnsw .= '--8--changesFromOtherDevice||'.$notyOne->elementId; }
            }
            $notyOne->delete();
        }
        return $notyAnsw;
    }






















   




    public function tablePageNewOrDetailedFetch(Request $req){
        $theProd = Produktet::find($req->pId);
        $theCat = kategori::find($theProd->kategoria);

        if(Carbon::now()->format('H:i') >= '20:00' || Carbon::now()->format('H:i') <= '03:00'){
            if($theProd->qmimi2 != 999999){
                $starterPrice = sprintf('%01.2f', $theProd->qmimi2); 
            }else{ $starterPrice = sprintf('%01.2f', $theProd->qmimi); }
        }else{ $starterPrice = sprintf('%01.2f', $theProd->qmimi); }

        $plateNr = $theCat->forPlate;
        $plateDefTitle = 'empty';
        $plateDefId = 0;
        if($plateNr != NULL){
            $plateData = resPlates::where([['toRes',$theProd->toRes],['desc2C',$plateNr]])->first();
            $plateDefTitle = $plateData->nameTitle;
            $plateDefId = $plateData->id;
        }else{
            $plateNr = 0;
        }

        if($theProd->type != NULL && count(explode('--0--', $theProd->type)) > 0){ $theTypes = $theProd->type;
        }else{ $theTypes = 'empty'; }

        if(count(explode('--0--', $theProd->extPro)) > 0){ $theExtras = $theProd->extPro;
        }else{ $theExtras = 'empty'; }
        
        return $theProd->id."--||--".$theProd->emri."--||--".$theProd->pershkrimi."--||--".$theCat->emri."--||--".$starterPrice."--||--".$plateNr."--||--".$plateDefTitle."--||--".$plateDefId."--||--".$theCat->id."--||--".$theTypes."--||--".$theExtras;
    }

    public function tablePageTableChangeFetchClients(Request $req){
        $activeClientsPNr = array();
        $clientsActiveSend = '';

        foreach(TabOrder::where([['tab_orders.tabCode',$req->tTab]])
        ->join('tab_verification_p_numbers', 'tab_orders.id', '=', 'tab_verification_p_numbers.tabOrderId')
        ->select('tab_verification_p_numbers.phoneNr as phoneNr')->get() as $oneTOrder){
            if ($oneTOrder!= NULL && !in_array($oneTOrder->phoneNr, $activeClientsPNr)){
                array_push($activeClientsPNr, $oneTOrder->phoneNr);
                if($clientsActiveSend == ''){
                    $clientsActiveSend = $oneTOrder->phoneNr;
                }else{
                    $clientsActiveSend .= '||'.$oneTOrder->phoneNr;
                }
            }
        }
       return $clientsActiveSend;
    }
    public function tablePageTableChangeFetchClientsWaiter(Request $req){
        $activeClientsPNr = array();
        $clientsActiveSend = '';
        $myTablesWaiter = array();
        foreach(tablesAccessToWaiters::where('waiterId',Auth::user()->id)->get() as $oneT){ array_push($myTablesWaiter,$oneT->tableNr); }
        array_push($myTablesWaiter,500);

        foreach(TabOrder::where([['tab_orders.tabCode',$req->tTab]])->whereIn('tab_orders.tableNr',$myTablesWaiter)
        ->join('tab_verification_p_numbers', 'tab_orders.id', '=', 'tab_verification_p_numbers.tabOrderId')
        ->select('tab_verification_p_numbers.phoneNr as phoneNr')->get() as $oneTOrder){
            if ($oneTOrder!= NULL && !in_array($oneTOrder->phoneNr, $activeClientsPNr)){
                array_push($activeClientsPNr, $oneTOrder->phoneNr);
                if($clientsActiveSend == ''){
                    $clientsActiveSend = $oneTOrder->phoneNr;
                }else{
                    $clientsActiveSend .= '||'.$oneTOrder->phoneNr;
                }
            }
        }
       return $clientsActiveSend;
    }

    public function tablePageTableChangeFetchTables(Request $req){
        $allTblSend = '';
        foreach(TableQrcode::where('Restaurant',Auth::user()->sFor)->orderBy('tableNr')->get() as $tblOne){
            if($allTblSend == ''){
                $allTblSend = $tblOne->id.'|||'.$tblOne->tableNr.'|||'.$tblOne->kaTab;
            }else{
                $allTblSend .= '--||--'.$tblOne->id.'|||'.$tblOne->tableNr.'|||'.$tblOne->kaTab;
            }
        }
        return $allTblSend;
    }

    public function tablePageTableChangeFetchTablesWaiter(Request $req){
        $allTblSend = '';
        $myTablesWaiter = array();
        foreach(tablesAccessToWaiters::where('waiterId',Auth::user()->id)->get() as $oneT){ array_push($myTablesWaiter,$oneT->tableNr); }
        array_push($myTablesWaiter,500);
        foreach(TableQrcode::where('Restaurant',Auth::user()->sFor)->whereIn('tableNr',$myTablesWaiter)->orderBy('tableNr')->get() as $tblOne){
            if($allTblSend == ''){
                $allTblSend = $tblOne->id.'|||'.$tblOne->tableNr.'|||'.$tblOne->kaTab;
            }else{
                $allTblSend .= '--||--'.$tblOne->id.'|||'.$tblOne->tableNr.'|||'.$tblOne->kaTab;
            }
        }
        return $allTblSend;
    }





    public function payMethodChangeByStaff(Request $req){
        $theOr = Orders::find($req->orderId);
        if($theOr != Null){
            $newLog = new logOrderPayMChng();
            $newLog->orderId = $req->orderId;
            $newLog->staffRole = Auth::user()->role;
            $newLog->staffId = Auth::user()->id;
            $newLog->toRes = $theOr->Restaurant;
            $newLog->payMPrevious = $theOr->payM;
            $newLog->payMCurrent = $req->newPayM;
            $newLog->save();

            $theOr->payM = $req->newPayM; 
            $theOr->save(); 

            $theOrPassive = OrdersPassive::find($req->orderId);
            if($theOrPassive != Null){
                $theOrPassive->payM = $req->newPayM; 
                $theOrPassive->save(); 
            }
        }


        $testTable = new testTableOnDbTwo();
        $testTable->toRes = 1;
        $testTable->textTest = 'test';
        $testTable->save();
    }


    public function repairOrdersRefId(Request $req){
        if(isset($_GET['hs']) && $_GET['hs'] == 'OeqWeSXcY789D6d67D878d765D677d6D65D6Dd56DdtgHGjhbVBMmM'){
            foreach(Restorant::all() as $resOne){
                
                // ordersPassive
                $orPasRefId = 1;
                foreach(OrdersPassive::where('Restaurant',$resOne->id)->orderBy('created_at')->get() as $orPasOne){
                    if($orPasRefId == $orPasOne->refId){
                        $orPasRefId++;
                    }else{
                        $orPasOne->refId = $orPasRefId;
                        $orPasOne->save();

                        // orders
                        $orOne = Orders::find($orPasOne->id);
                        if($orOne != Null){
                            $orOne->refId = $orPasRefId;
                            $orOne->save();
                        }

                        $orPasRefId++;
                    }
                }

            } // restorant loo p
        } // hash check
    }


    public function repairSomeTables(Request $req){

    }

    public function generateMostSalesPDF(Request $req){
        return view('mostSalesPDF');
    }

    public function repairPayMChngIns(){
        foreach(logOrderPayMChng::all() as $lpmcOne){
            $theOr = OrdersPassive::find($lpmcOne->orderId);
            if($theOr != Null){
                $lpmcOne->toRes = $theOr->Restaurant;
                $lpmcOne->save();
            }
        }
    }

    public function deleteResSales(){
        if(isset($_GET['res']) && isset($_GET['hs']) && $_GET['hs'] == 'Hgfdd5343545665$657gfFCBgfTTY556yhghFGf^$yvgty55rffy6yt55rffguhyUJuYd656767fcGhu7*7898'){

            $theResId = $_GET['res'];

            foreach(billsRecordRes::where('forRes',$theResId)->get() as $del01){
                $del01->delete();
            }
            foreach(checkInOutReg::where('toRes',$theResId)->get() as $del02){
                $del02->delete();
            }
            foreach(couponUsedPhoneNr::where('toRes',$theResId)->get() as $del03){
                $del03->delete();
            }
            foreach(Cupon::where('toRes',$theResId)->get() as $del04){
                $del04->delete();
            }
            foreach(emailReceiptFromAdm::where('forRes',$theResId)->get() as $del05){
                $del05->delete();
            }
            foreach(ghostCartInUse::where('toRes',$theResId)->get() as $del06){
                $del06->delete();
            }
            foreach(logOrderPayMChng::where('toRes',$theResId)->get() as $del07){
                $del07->delete();
            }
            foreach(logTabAutoRemove::where('toRes',$theResId)->get() as $del08){
                $del08->delete();
            }
            foreach(newOrdersAdminAlert::where('toRes',$theResId)->get() as $del09){
                $del09->delete();
            }
            foreach(onlinePayQRCStaf::where('resId',$theResId)->get() as $del10){
                $del10->delete();
            }
            foreach(Orders::where('Restaurant',$theResId)->get() as $del11){
                $del11->delete();
            }
            foreach(ordersTempForTA::where('toRes',$theResId)->get() as $del12){
                $del12->delete();
            }
            foreach(OPSaferpayReference::where('toRes',$theResId)->get() as $del13){
                $del13->delete();
            }
            foreach(pdfReportIns::where('forRes',$theResId)->get() as $del14){
                $del14->delete();
            }
            foreach(rechnungClientToBills::where('toRes',$theResId)->get() as $del15){
                $del15->delete();
            }
            foreach(TableChngReq::where('toRes',$theResId)->get() as $del16){
                $del16->delete();
            }
            foreach(tableChngReqsAdmin::where('toRes',$theResId)->get() as $del17){
                $del17->delete();
            }
            foreach(TableReservation::where('toRes',$theResId)->get() as $del18){
                $del18->delete();
            }
            foreach(TabOrder::where('toRes',$theResId)->get() as $del19){
                $tbver01 = tabVerificationPNumbers::where('tabOrderId',$del19->id)->first();
                $tbver02 = tabVerificationPNumbersPassive::where('tabOrderId',$del19->id)->first();
                if($tbver01 != Null){ $tbver01->delete(); }
                if($tbver02 != Null){ $tbver02->delete(); }
                $del19->delete();
            }

            foreach(OrdersPassive::where('Restaurant',$theResId)->get() as $del20){
                $del20->delete();
            }
            foreach(rouletteUsage::where('toRes',$theResId)->get() as $del21){
                $del21->delete();
            }
            foreach(tabOrderDelete::where('toRes',$theResId)->get() as $del01){
                $del01->delete();
            }
        }
    }


    public function callDataForPrintReceipt(Request $req){
        $theOr = Orders::find($req->oId);
        if($theOr != Null){
            $theRes = Restorant::find($theOr->Restaurant);
            $theTime = Carbon::now();
            $date2D = explode('-',explode(' ',$theTime)[0]);
            $time2D = explode(':',explode(' ',$theTime)[1]);
            $theTime = $date2D[2].'.'. $date2D[1].'.'. $date2D[0].' '.$time2D[0].':'.$time2D[1];

            $theOrder = '<p style="width:100%; text-align:left; font-size:0.9rem; display:flex; flex-wrap: wrap; justify-content: space-between;">';
            foreach(explode('---8---',$theOr->porosia) as $produkti){  
                $prod = explode('-8-', $produkti);
                $theOrder .= '<span style="width:80%;">'.$prod[3].'x '.$prod[0].' ';

                $theOrder .= ' </span>';
                $theOrder .= ' <span style="width:20%; text-align:right;">'.number_format($prod[4], 2, '.', '');
                $theOrder .= ' </span><br>';
            }
            $theOrder .= '</p>';

            $payMethod = $theOr->payM;

            $GC_Discount = number_format($theOr->dicsountGcAmnt, 2, '.', '');
            $total_shuma = number_format($theOr->shuma, 2, '.', '');
            $total_zbritjeStaff = number_format(0, 2, '.', '');

            if($theOr->inCashDiscount > 0){
                $total_zbritjeStaff += number_format($theOr->inCashDiscount, 2, '.', '');
            }else if($theOr->inPercentageDiscount > 0){
                $total_zbritjeStaff += number_format(($theOr->shuma - $theOr->tipPer - $theOr->dicsountGcAmnt)*($theOr->inPercentageDiscount*0.01), 2, '.', '');
            }

            $total_bakshish = number_format($theOr->tipPer, 2, '.', '');

            $total_shuma_toPay = number_format($total_shuma - $total_zbritjeStaff - $theOr->dicsountGcAmnt, 2, '.', '');

            $orderId = $theOr->id;
            $orderRefId = $theOr->refId;
            $orderQrCodeName = $theOr->digitalReceiptQRK;
            $waiterName = User::find($theOr->servedBy)->name;

            $sdr2d = explode(',',$theRes->adresa);

            $resAdr ='<p style="width:100%; font-size:0.7rem; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">';
            if (isset($sdr2d[0])){ $resAdr .= $sdr2d[0].'<br>';}
            if (isset($sdr2d[1])){ $resAdr .= $sdr2d[1];}
            if (isset($sdr2d[2])){ $resAdr .= ', '.$sdr2d[2];}
            $resAdr .= '<br>';
            if ($theRes != NULL && $theRes->resPhoneNr != 'empty'){
                $resAdr .= 'Tel. '.$theRes->resPhoneNr;
            }else{
                $resAdr .= 'Tel. +41 XX XXX XX XX';
            }
            $resAdr .= '<br>';
            if ($theRes != NULL && $theRes->chemwstForRes != 'empty'){
                if (str_contains($theRes->chemwstForRes, 'CHE')){
                     $resAdr .= $theRes->chemwstForRes.' MWST';
                }else{
                    $resAdr .= $theRes->chemwstForRes;
                }
            }else{
                $resAdr .= 'CHE-xxx.xxx.xxx MWST';
            }
            $resAdr .= '</p>';


            $date2D = explode('-',explode(' ',$theOr->created_at)[0]);
            if($theRes->resTvsh == 0){
                $hiTvsh = number_format( 0 , 9, '.', '');
                $loTvsh = number_format( 0 , 9, '.', '');
            }else{
                if($date2D[0] <= 2023){
                    $hiTvsh = number_format( 0.071494893 , 9, '.', '');
                    $loTvsh = number_format( 0.024390243 , 9, '.', '');
                }else{
                    $hiTvsh = number_format( 0.074930619 , 9, '.', '');
                    $loTvsh = number_format( 0.025341130 , 9, '.', '');
                }
            }
            $mwstFor2526 = number_format(0, 9, '.', '');
            $mwstFor7781 = number_format(0, 9, '.', '');
            $totMwst = number_format(0, 9, '.', '');
            $totZbritja = number_format($total_zbritjeStaff, 9, '.', '');
            $totFromProductePrice = number_format(0, 9, '.', '');

            foreach(explode('---8---',$theOr->porosia) as $produkti){
                $prod = explode('-8-', $produkti);
                $totFromProductePrice += number_format($prod[4], 2, '.', '');
            }
            foreach(explode('---8---',$theOr->porosia) as $produkti){
                $prod = explode('-8-', $produkti);

                if($theOr->userEmri == 'admin' || $theOr->userPhoneNr == '0000000000'){
                    $taProdIns = Takeaway::where('prod_id',$prod[7])->first();
                }else{
                    $taProdIns = Takeaway::find($prod[7]);
                }

                if($theOr->nrTable == 500){
                    if($taProdIns->mwstForPro == 2.50 || $taProdIns->mwstForPro == 2.60){
                        $cal1 = number_format(($prod[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                        $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                        $mwstFor2526 += number_format($cal2*$loTvsh, 9, '.', '');
                        $totMwst += number_format($cal2*$loTvsh, 9, '.', '');
                    }else if($taProdIns->mwstForPro == 7.70 || $taProdIns->mwstForPro == 8.10){
                        $cal1 = number_format(($prod[4]* $totZbritja) / $totFromProductePrice , 9, '.', '');
                        $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                        $mwstFor7781 += number_format($cal2*$hiTvsh, 9, '.', '');
                        $totMwst += number_format($cal2*$hiTvsh, 9, '.', '');
                    }
                }else{
                    $cal1 = number_format(($prod[4]* $totZbritja) / $totFromProductePrice , 9, '.', '');
                    $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                    $totMwst += number_format($cal2*$hiTvsh, 9, '.', ''); 
                    $mwstFor7781 += number_format($cal2*$hiTvsh, 9, '.', ''); 
                }
            }

            $showMwSt = '';
            if($mwstFor7781 > 0){
                $showMwSt .= '<p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>MwSt 8.1%: </strong></p>'.
                            '<p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">'.number_format($mwstFor7781, 2, '.', '').' CHF</p>';
            }
            if($mwstFor2526 > 0){
                $showMwSt .= '<p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>MwSt 2.6%: </strong></p>'.
                            '<p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">'.number_format($mwstFor2526, 2, '.', '').' CHF</p>';
            }

            $theOrPayTecData = payTecTransactionLog::where('orderId',$req->oId)->first();
            $hasPOSData = '';
            $DisplayName = '';
            $AppPANPrtCardholder = '';
            $TrxDate = '';
            $TrxTime = '';
            $TrmID = '';
            $AID = '';
            $TrxSeqCnt = '';
            $TrxRefNum = '';
            $AuthC = '';
            $AcqID = '';
            $AppPANEnc = '';
            $TrxAmt = '';
            if($theOrPayTecData != Null ){
                $hasPOSData = 'Yes';
                $DisplayName = $theOrPayTecData->DisplayName;
                $AppPANPrtCardholder = $theOrPayTecData->AppPANPrtCardholder;
                $TrxDate = $theOrPayTecData->TrxDate;
                $TrxTime = $theOrPayTecData->TrxTime;
                $TrmID = $theOrPayTecData->TrmID;
                $AID = base64_decode($theOrPayTecData->AID);
                $TrxSeqCnt = $theOrPayTecData->TrxSeqCnt;
                $TrxRefNum = $theOrPayTecData->TrxRefNum;
                $AuthC = $theOrPayTecData->AuthC;
                $AcqID = $theOrPayTecData->AcqID;
                $AppPANEnc = $theOrPayTecData->AppPANEnc;
                $TrxAmt = number_format($theOrPayTecData->TrxAmt / 100, 2, '.', '');
                

                // $xxxxxxxxxx = $theOrPayTecData->xxxxxxx;
            }else{
                $hasPOSData = 'No';
            }

          
        
            return $theRes->emri.'---88---'.$theOr->nrTable.'---88---'.$theTime.'---88---'. $theOrder.'---88---'.$payMethod.'---88---'.$total_shuma.'---88---'.$GC_Discount
            .'---88---'.$total_zbritjeStaff.'---88---'.$total_bakshish.'---88---'.$total_shuma_toPay.'---88---'.$orderId.'---88---'.$orderRefId.'---88---'.$orderQrCodeName
            .'---88---'.$waiterName.'---88---'.$resAdr.'---88---'.$mwstFor2526.'---88---'.$mwstFor7781.'---88---'.$showMwSt.'---88---'.$hasPOSData.'---88---'.$DisplayName
            .'---88---'.$AppPANPrtCardholder.'---88---'.$TrxDate.'---88---'.$TrxTime.'---88---'.$TrmID.'---88---'.$AID.'---88---'.$TrxSeqCnt.'---88---'.$TrxRefNum
            .'---88---'.$AuthC.'---88---'.$AcqID.'---88---'.$AppPANEnc.'---88---'.$TrxAmt.'---88---'.$theOr->resComment;
        }else{
            return 'orderNotFound';
        }
    }


    public function callDataForPrintReceiptActiveTab(Request $req){

        $theRes = Restorant::find(Auth::user()->sFor);
        $theTime = Carbon::now();
        $date2D = explode('-',explode(' ',$theTime)[0]);
        $time2D = explode(':',explode(' ',$theTime)[1]);
        $theTime = $date2D[2].'.'. $date2D[1].'.'. $date2D[0].' '.$time2D[0].':'.$time2D[1];

        $total_shuma = 0;

        // ─────────────────────────────
        // PRODUCTS — plain text, pipe-separated: "qty x name|price"
        // ─────────────────────────────
        $theProdsShow = '';
        $theTable = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$req->tableNrSend]])->first();
        if ($theTable != Null && $theTable->kaTab != 0) {
            foreach(TabOrder::where('tabCode',$theTable->kaTab)->get() as $produkti) {
                $name  = $produkti->OrderSasia.'x '.$produkti->OrderEmri;
                $price = number_format($produkti->OrderQmimi, 2, '.', '');
                $theProdsShow .= $name.'|'.$price."\n";
                $total_shuma += $produkti->OrderQmimi;
            }
        }

        // ─────────────────────────────
        // ADDRESS — plain text, newline-separated
        // ─────────────────────────────
        $sdr2d = explode(',', $theRes->adresa);
        $resAdr = '';
        if (isset($sdr2d[0])) { $resAdr .= trim($sdr2d[0])."\n"; }
        if (isset($sdr2d[1])) {
            $line = trim($sdr2d[1]);
            if (isset($sdr2d[2])) { $line .= ', '.trim($sdr2d[2]); }
            $resAdr .= $line."\n";
        }
        if ($theRes != NULL && $theRes->resPhoneNr != 'empty') {
            $resAdr .= 'Tel. '.$theRes->resPhoneNr."\n";
        } else {
            $resAdr .= 'Tel. +41 XX XXX XX XX'."\n";
        }
        if ($theRes != NULL && $theRes->chemwstForRes != 'empty') {
            if (str_contains($theRes->chemwstForRes, 'CHE')) {
                $resAdr .= $theRes->chemwstForRes.' MWST';
            } else {
                $resAdr .= $theRes->chemwstForRes;
            }
        } else {
            $resAdr .= 'CHE-xxx.xxx.xxx MWST';
        }

        $total_shuma = number_format($total_shuma, 2, '.', '');

        return $theRes->emri.'---88---'.$req->tableNrSend.'---88---'.$theTime.'---88---'.$theProdsShow.'---88---'.$total_shuma.'---88---'.$resAdr;
    }







    public function payTecPair(Request $req){
        $theRes = Restorant::find(Auth::user()->sFor);
        $data =  [
            "Code" => $req->pairCode,
            "PeerName" => $theRes->emri." POS"
        ];
        $jsonData = json_encode($data);

        $url = "https://kitrest.paytec.ch/api/v1/pair";
        $crl = curl_init($url);

        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($crl, CURLOPT_POST, true);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($crl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);


        $result = curl_exec($crl);

        curl_close($crl);

        $resultDecode = json_decode($result);
        $payTecIns = payTecPair::where('userId',Auth::user()->id)->first();
        if($payTecIns != Null){
            $payTecIns->userId = Auth::user()->id;
            $payTecIns->toRes = Auth::user()->sFor;
            $payTecIns->accessToken = $resultDecode->AccessToken;
            $payTecIns->save();
        }else{
            $payTecIns = new payTecPair();
            $payTecIns->userId = Auth::user()->id;
            $payTecIns->toRes = Auth::user()->sFor;
            $payTecIns->accessToken = $resultDecode->AccessToken;
            $payTecIns->save();
        }

        return $result;
    }

    public function payTecConnect(Request $req){
        $payTecIns = payTecPair::where([['userId',Auth::user()->id],['toRes',Auth::user()->sFor]])->first();
        if($payTecIns != Null){
            $data =  [
                    "AccessToken"=> "$payTecIns->accessToken",
                ];
            $jsonData = json_encode($data);
            $url = "https://kitrest.paytec.ch/api/v1/connect";
            $crl = curl_init($url);

            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($crl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            $result = curl_exec($crl);
            curl_close($crl);
            return $result;
        }
    }


    public function payTecDisconnect(Request $req){
        $payTecIns = payTecPair::where([['userId',Auth::user()->id],['toRes',Auth::user()->sFor]])->first();
        if($payTecIns != Null){
            $data =  [
                    "AccessToken"=> "$payTecIns->accessToken",
                ];
            $jsonData = json_encode($data);
            $url = "https://kitrest.paytec.ch/api/v1/disconnect";
            $crl = curl_init($url);

            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($crl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            $result = curl_exec($crl);
            curl_close($crl);

            $payTecIns->delete();

            return $result;
        }
    }

    public function payTecTransact(Request $req){
        $payTecIns = payTecPair::where([['userId',Auth::user()->id],['toRes',Auth::user()->sFor]])->first();

        
        $totalChfFirst = number_format((float)$req->totalChf * 100, 2, '.', '');
        $totalChf = (int)$totalChfFirst;

        if($payTecIns != Null){
            $data =  [
                "AccessToken" => $payTecIns->accessToken,
                "TrxFunction" => 32768,
                "TrxCurrC" => 756,
                "AmtAuth" => $totalChf,
                "AutoConfirm" => 1,
        
            ];
            $jsonData = json_encode($data);

            $url = "https://kitrest.paytec.ch/api/v1/transact";
            $crl = curl_init($url);

            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($crl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);

            $result = curl_exec($crl);

            curl_close($crl);

            return $result;

            // {
            //     "AccessToken": "CxOr9ssjChB/sjJ9JD0XVfrwUSjFOu/0l2FZCjSiTsCU6IX/I0Mc7RyF/qR/IPS4wpIjjTNYpCLh9wonMF3VUw==",
            //     "TrxFunction": 32768,
            //     "TrxCurrC": 756,
            //     "AmtAuth": 1500,
            //     "AutoConfirm": 1,
            //     "RecOrderRef": {
            //         "OrderID": "Order1234"
            //     }
            // }
        }
    }

    public function payTecAbortTransact(Request $req){
        $payTecIns = payTecPair::where([['userId',Auth::user()->id],['toRes',Auth::user()->sFor]])->first();
        if($payTecIns != Null){
            $data =  [
                "AccessToken" => $payTecIns->accessToken,
                "AbortTrxFlags" => 1,
            ];
            $jsonData = json_encode($data);

            $url = "https://kitrest.paytec.ch/api/v1/abort";
            $crl = curl_init($url);

            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($crl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);

            $result = curl_exec($crl);

            curl_close($crl);

            return $result;
        }
    }



    

    public function prodOnOffCallCatProds(Request $req){
        $allProdsThisCat = '';
        foreach(Produktet::where([['toRes',Auth::user()->sFor],['kategoria',$req->catId]])->get() as $prodOne){
            if($allProdsThisCat == ''){
                $allProdsThisCat = $prodOne->id.'--88--'.$prodOne->emri.'--88--'.$prodOne->qmimi.'--88--'.$prodOne->pershkrimi.'--88--'.$prodOne->accessableByClients;
            }else{
                $allProdsThisCat .= '--99--'.$prodOne->id.'--88--'.$prodOne->emri.'--88--'.$prodOne->qmimi.'--88--'.$prodOne->pershkrimi.'--88--'.$prodOne->accessableByClients;
            }
        }
        return $allProdsThisCat;
    }
    public function prodOnOffChangeProdStatus(Request $req){
        $theProduct = Produktet::find($req->prodId);
        if($theProduct != Null){
            if($theProduct->accessableByClients == 1){
                $theProduct->accessableByClients = 0;
                $theProduct->save();

                return $theProduct->id.'--99--0';
            }else if($theProduct->accessableByClients == 0){
                $theProduct->accessableByClients = 1;
                $theProduct->save();

                return $theProduct->id.'--99--1';
            }
        }else{
            return 'productNotFound';
        }

    }

    public function prodOnOffCallCatProdsTA(Request $req){
        $allProdsThisCat = '';
        foreach(Takeaway::where([['toRes',Auth::user()->sFor],['kategoria',$req->catId]])->get() as $prodOne){
            if($allProdsThisCat == ''){
                $allProdsThisCat = $prodOne->id.'--88--'.$prodOne->emri.'--88--'.$prodOne->qmimi.'--88--'.$prodOne->pershkrimi.'--88--'.$prodOne->accessableByClients;
            }else{
                $allProdsThisCat .= '--99--'.$prodOne->id.'--88--'.$prodOne->emri.'--88--'.$prodOne->qmimi.'--88--'.$prodOne->pershkrimi.'--88--'.$prodOne->accessableByClients;
            }
        }
        return $allProdsThisCat;
    }
    public function prodOnOffChangeProdStatusTA(Request $req){
        $theProduct = Takeaway::find($req->prodId);
        if($theProduct != Null){
            if($theProduct->accessableByClients == 1){
                $theProduct->accessableByClients = 0;
                $theProduct->save();

                return $theProduct->id.'--99--0';
            }else if($theProduct->accessableByClients == 0){
                $theProduct->accessableByClients = 1;
                $theProduct->save();

                return $theProduct->id.'--99--1';
            }
        }else{
            return 'productNotFound';
        }

    }




    public function payTakeawayPosConfirm(Request $req){

        $theOr = Orders::find($req->orderId);
        // PayTec LOG register
        if($req->payTecTrx != 'none'){
            // $req->payTecTrx
            $payTecTrx = json_decode($req->payTecTrx);
            $payTecLog = new payTecTransactionLog();
            $payTecLog->orderId = $theOr->id;
            $payTecLog->toRes = Auth::user()->sFor;
            if(isset($payTecTrx->TrmID)){ $payTecLog->TrmID = $payTecTrx->TrmID; }
            if(isset($payTecTrx->TrxResult)){ $payTecLog->TrxResult = $payTecTrx->TrxResult; }
            if(isset($payTecTrx->Brand)){ $payTecLog->Brand = $payTecTrx->Brand; }                    
            if(isset($payTecTrx->VoicePhone)){ $payTecLog->VoicePhone = $payTecTrx->VoicePhone; }
            if(isset($payTecTrx->TrxRefNum)){ $payTecLog->TrxRefNum = $payTecTrx->TrxRefNum; }
            if(isset($payTecTrx->AccountType)){ $payTecLog->AccountType = $payTecTrx->AccountType; }
            if(isset($payTecTrx->AcqID)){ $payTecLog->AcqID = $payTecTrx->AcqID; }
            if(isset($payTecTrx->AID)){ $payTecLog->AID = $payTecTrx->AID; }
            if(isset($payTecTrx->AIDICC)){ $payTecLog->AIDICC = $payTecTrx->AIDICC; }
            if(isset($payTecTrx->AmtAuth)){ $payTecLog->AmtAuth = $payTecTrx->AmtAuth; }
            if(isset($payTecTrx->AuthC)){ $payTecLog->AuthC = $payTecTrx->AuthC; }
            if(isset($payTecTrx->ARC)){ $payTecLog->ARC = $payTecTrx->ARC; }
            if(isset($payTecTrx->CVMResults)){ $payTecLog->CVMResults = $payTecTrx->CVMResults; }
            if(isset($payTecTrx->IssCntryC)){ $payTecLog->IssCntryC = $payTecTrx->IssCntryC; }
            if(isset($payTecTrx->POSEntryMode)){ $payTecLog->POSEntryMode = $payTecTrx->POSEntryMode; }
            if(isset($payTecTrx->TrxAmt)){ $payTecLog->TrxAmt = $payTecTrx->TrxAmt; }
            if(isset($payTecTrx->TrxCurrC)){ $payTecLog->TrxCurrC = $payTecTrx->TrxCurrC; }
            if(isset($payTecTrx->TrxType)){ $payTecLog->TrxType = $payTecTrx->TrxType; }
            if(isset($payTecTrx->TrxSeqCnt)){ $payTecLog->TrxSeqCnt = $payTecTrx->TrxSeqCnt; }
            if(isset($payTecTrx->TrxDate)){ $payTecLog->TrxDate = $payTecTrx->TrxDate; }
            if(isset($payTecTrx->TrxTime)){ $payTecLog->TrxTime = $payTecTrx->TrxTime; }
            if(isset($payTecTrx->AuthReslt)){ $payTecLog->AuthReslt = $payTecTrx->AuthReslt; }
            if(isset($payTecTrx->AppPANEnc)){ $payTecLog->AppPANEnc = $payTecTrx->AppPANEnc; }
            if(isset($payTecTrx->StatKeyPANRctInd)){ $payTecLog->StatKeyPANRctInd = $payTecTrx->StatKeyPANRctInd; }
            if(isset($payTecTrx->KeyPANRctDOLInd)){ $payTecLog->KeyPANRctDOLInd = $payTecTrx->KeyPANRctDOLInd; }
            if(isset($payTecTrx->DisplayName)){ $payTecLog->DisplayName = $payTecTrx->DisplayName; }
            if(isset($payTecTrx->TrxResultExtended)){ $payTecLog->TrxResultExtended = $payTecTrx->TrxResultExtended; }
            if(isset($payTecTrx->IIN)){ $payTecLog->IIN = $payTecTrx->IIN; }
            if(isset($payTecTrx->AppPANPrtCardholder)){ $payTecLog->AppPANPrtCardholder = $payTecTrx->AppPANPrtCardholder; }
            if(isset($payTecTrx->AppPANPrtAttendant)){ $payTecLog->AppPANPrtAttendant = $payTecTrx->AppPANPrtAttendant; }
            if(isset($payTecTrx->SurrogatePAN)){ $payTecLog->SurrogatePAN = $payTecTrx->SurrogatePAN; }
            if(isset($payTecTrx->CardholderText)){ $payTecLog->CardholderText = $payTecTrx->CardholderText; }
            if(isset($payTecTrx->AttendantText)){ $payTecLog->AttendantText = $payTecTrx->AttendantText; }
            if(isset($payTecTrx->TipAmt)){ $payTecLog->TipAmt = $payTecTrx->TipAmt; }
            if(isset($payTecTrx->AmtRemaining)){ $payTecLog->AmtRemaining = $payTecTrx->AmtRemaining; }
            //  if(isset($payTecTrx->xxxxxxxxxxxxxxx)){ $payTecLog->xxxxxxxxxxxxxxx = $payTecTrx->xxxxxxxxxxxxxxx; }
            $payTecLog->save();
        }

        //---------------------------------------------------------------
        $theOr->statusi = 3;
        $theOr->payM = 'Kartenzahlung'; 	
        $theOr->orForWaiter = Auth::user()->id; 	
        $theOr->servedBy = Auth::user()->id; 	
        $theOr->save();

        $thePassiveOr = OrdersPassive::find($req->orderId);
        if( $thePassiveOr != Null ){
            $thePassiveOr->statusi = 3;
            $thePassiveOr->payM = 'Kartenzahlung';  
            $thePassiveOr->orForWaiter = Auth::user()->id; 	
            $thePassiveOr->servedBy = Auth::user()->id; 
            $thePassiveOr->save();
        }
    }





    public function deleteSelectedTabOrders(Request $req){
        // value="474-8-1||472-8-1||473-8-1"   
        // tabOrSelected: tabOrSel,
        //                     delKom: commSend,
        //                     _token: '{{csrf_token()}}'
        $tabOrSelIds = array();
        foreach(explode('||',$req->tabOrSelected) as $tabOrSelOne){
            $tabOrSelOne2D = explode('-8-',$tabOrSelOne);
            $thisTabOr = TabOrder::find($tabOrSelOne2D[0]);
            if((int)$thisTabOr->OrderSasia == (int)$tabOrSelOne2D[1]){
                // selected all
                array_push($tabOrSelIds,$tabOrSelOne2D[0]);
            }else{
                // selected some

                $priceForOneSelTabProd = number_format($thisTabOr->OrderQmimi/$thisTabOr->OrderSasia, 2, '.', '');
                $qmimiOfSelected = number_format($priceForOneSelTabProd * (int)$tabOrSelOne2D[1], 2, '.', '');

                $thisTabOr->OrderSasia = $thisTabOr->OrderSasia - (int)$tabOrSelOne2D[1];
                $thisTabOr->OrderQmimi = number_format($thisTabOr->OrderQmimi - $qmimiOfSelected, 2, '.', '');
                $thisTabOr->save();

                // Save extra TAB order ....
                $newTabOrder = new TabOrder;
                $newTabOrder->tabCode = $thisTabOr->tabCode;
                $newTabOrder->prodId = $thisTabOr->prodId;
                $newTabOrder->OrderEmri = $thisTabOr->OrderEmri;
                $newTabOrder->tableNr = $thisTabOr->tableNr;
                $newTabOrder->toRes = $thisTabOr->toRes;
                $newTabOrder->OrderPershkrimi= $thisTabOr->OrderPershkrimi;
                $newTabOrder->OrderSasia = (int)$tabOrSelOne2D[1];
                $newTabOrder->OrderSasiaDone = 0;
                $newTabOrder->OrderQmimi = number_format($qmimiOfSelected, 2, '.', '');
                $newTabOrder->OrderExtra = $thisTabOr->OrderExtra;
                $newTabOrder->OrderType = $thisTabOr->OrderType;
                $newTabOrder->OrderKomenti = $thisTabOr->OrderKomenti;
                $newTabOrder->status = 1;
                $newTabOrder->toPlate = $thisTabOr->toPlate;
                $newTabOrder->abrufenStat = $thisTabOr->abrufenStat;
                $newTabOrder->created_at = $thisTabOr->created_at;
                $newTabOrder->save();

                $theNrVer = tabVerificationPNumbers::where('tabOrderId',$thisTabOr->id)->first();
                // Save the number ....
                $newNrVerification = new tabVerificationPNumbers;
                $newNrVerification->phoneNr = $theNrVer->phoneNr;
                $newNrVerification->tabCode = $theNrVer->tabCode;
                $newNrVerification->tabOrderId = $newTabOrder->id;
                $newNrVerification->status = 1;
                $newNrVerification->save();

                $newTabOrder->usrPhNr = $theNrVer->phoneNr;
                $newTabOrder->save();

                $waLogPrev = waiterActivityLog::where([['actType','newProdWa'],['actId',$thisTabOr->id]])->first();
                $waLogPrev->sasia = $thisTabOr->OrderSasia;
                $waLogPrev->save();

                $waLog = new waiterActivityLog();
                $waLog->waiterId = $waLogPrev->waiterId;
                $waLog->actType = 'newProdWa';
                $waLog->actId = $newTabOrder->id;
                $waLog->sasia = (int)$tabOrSelOne2D[1];
                $waLog->save();

                array_push($tabOrSelIds,$newTabOrder->id);
            }
        } 

        $resId = 0;
        $tableNr = 0;
        foreach($tabOrSelIds as $tabOrIdToDelete){
            $tOrder = TabOrder::find($tabOrIdToDelete);

            $delTAAudit = new tabOrderDelete();
            $delTAAudit->toRes = $tOrder->toRes;
            $delTAAudit->tableNr = $tOrder->tableNr;
            $delTAAudit->taOrId = $tOrder->id;
            $delTAAudit->byId = Auth::user()->id;
            $delTAAudit->prodId = $tOrder->prodId;
            $delTAAudit->prodName = $tOrder->OrderEmri;
            $delTAAudit->prodPershkrimi = $tOrder->OrderPershkrimi;
            $delTAAudit->prodTipi = $tOrder->OrderType;
            $delTAAudit->prodEkstra = $tOrder->OrderExtra;
            $delTAAudit->prodKomenti = $tOrder->OrderKomenti;
            $delTAAudit->prodSasia = $tOrder->OrderSasia;
            $delTAAudit->prodQmimi = $tOrder->OrderQmimi;
            $delTAAudit->deleteKomenti =$req->delKom;
            $delTAAudit->save();

            $resId = $tOrder->toRes;
            $tableNr = $tOrder->tableNr;

            // $toDoCart = 1;
            // $ToTable=$tOrder->toRes.'-0-'.$tOrder->tableNr.'-0-'.$toDoCart;
            // event(new CartMsg($ToTable));

            $nrVerRecord = tabVerificationPNumbers::where('tabOrderId',$tOrder->id)->first();
            $sendRemoveProd = $nrVerRecord->phoneNr.'||'.$tOrder->id.'||b||'.$tOrder->toRes.'||'.$tOrder->tableNr;
            event(new removePaidProduct($sendRemoveProd));

            foreach(newOrdersAdminAlert::where('tabOrderId',$req->id)->get() as $oneAdminAlert){
                $oneAdminAlert->delete();
            }

            // Send Notifications for the Admin
            foreach(User::where([['sFor',$tOrder->toRes],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $tOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $tOrder->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            foreach(User::where([['sFor',$tOrder->toRes],['role','55']])->get() as $oneWaiter){
                if($oneWaiter->id != auth()->user()->id){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $tOrder->tableNr]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $tOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $tOrder->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
            }
            foreach(User::where([['sFor',$tOrder->toRes],['role','54']])->get() as $oneCook){
                $details = [
                    'id' => $tOrder->id,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $tOrder->tableNr
                ];
                $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
            }

            $tOrder->delete();
        }

        $isEmptyNow = 'false';

        //Nese ska me porosi ne TAB  ai mbyllet  
        if(TabOrder::where([['toRes',$resId],['tableNr',$tableNr],['tabCode','!=','0']])->get()->count() == 0){
            $theTqr = TableQrcode::where([['tableNr',$tableNr],['Restaurant',$resId]])->first();
            $theTqr->kaTab = 0;
            $theTqr->save();

            $isEmptyNow = 'true';
        }
        return $isEmptyNow;
    }


    public function deleteAllTabOrders(Request $req){
        $tabOrAll = TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['tabCode','!=','0']])->get();
        foreach($tabOrAll as $tOrder){

            $delTAAudit = new tabOrderDelete();
            $delTAAudit->toRes = $tOrder->toRes;
            $delTAAudit->tableNr = $tOrder->tableNr;
            $delTAAudit->taOrId = $tOrder->id;
            $delTAAudit->byId = Auth::user()->id;
            $delTAAudit->prodId = $tOrder->prodId;
            $delTAAudit->prodName = $tOrder->OrderEmri;
            $delTAAudit->prodPershkrimi = $tOrder->OrderPershkrimi;
            $delTAAudit->prodTipi = $tOrder->OrderType;
            $delTAAudit->prodEkstra = $tOrder->OrderExtra;
            $delTAAudit->prodKomenti = $tOrder->OrderKomenti;
            $delTAAudit->prodSasia = $tOrder->OrderSasia;
            $delTAAudit->prodQmimi = $tOrder->OrderQmimi;
            $delTAAudit->deleteKomenti =$req->delKom;
            $delTAAudit->save();

            // $toDoCart = 1;
            // $ToTable=$tOrder->toRes.'-0-'.$tOrder->tableNr.'-0-'.$toDoCart;
            // event(new CartMsg($ToTable));

            $nrVerRecord = tabVerificationPNumbers::where('tabOrderId',$tOrder->id)->first();
            $sendRemoveProd = $nrVerRecord->phoneNr.'||'.$tOrder->id.'||b||'.$tOrder->toRes.'||'.$tOrder->tableNr;
            event(new removePaidProduct($sendRemoveProd));

            foreach(newOrdersAdminAlert::where('tabOrderId',$req->id)->get() as $oneAdminAlert){
                $oneAdminAlert->delete();
            }

            // Send Notifications for the Admin
            foreach(User::where([['sFor',$tOrder->toRes],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $tOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $tOrder->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            foreach(User::where([['sFor',$tOrder->toRes],['role','55']])->get() as $oneWaiter){
                if($oneWaiter->id != auth()->user()->id){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $tOrder->tableNr]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $tOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $tOrder->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
            }
            foreach(User::where([['sFor',$tOrder->toRes],['role','54']])->get() as $oneCook){
                $details = [
                    'id' => $tOrder->id,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $tOrder->tableNr
                ];
                $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
            }

            $tOrder->delete();
        }
        $theTqr = TableQrcode::where([['tableNr',$req->tableNr],['Restaurant',Auth::user()->sFor]])->first();
        $theTqr->kaTab = 0;
        $theTqr->save();
    }





// Keine teller
    public function plateForAbrufenFetchPlatesForAll(Request $req){
        $tabOrAll = TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['tabCode','!=','0']])->get();
        $platesActive = array();
        $platesActiveSend = '0';
        foreach($tabOrAll as $tOrder){

            if(!in_array($tOrder->toPlate,$platesActive)){
                array_push($platesActive,$tOrder->toPlate);
            }
        }
        sort($platesActive);
        foreach($platesActive as $plOneId){
            $thePlate = resPlates::find($plOneId);
            $hasNotAbrufenOrders = 0;
            foreach(TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['toPlate',$plOneId],['tabCode','!=','0']])->get() as $taOrOfThisPl){
                if($taOrOfThisPl->abrufenStat == 0 || $taOrOfThisPl->abrufenStat == -1){
                    $hasNotAbrufenOrders = 1;
                    break;
                }
            }

            if($plOneId == 0){
                $plateName = '0-8-Keine teller-8-'.$hasNotAbrufenOrders;
            }else{
                $thePlate = resPlates::find($plOneId);
              
                if( $thePlate == Null ){
                    $plateName = $plOneId.'-8-Nicht gefunden-8-'.$hasNotAbrufenOrders;
                }else{
                    $plateName = $plOneId.'-8-'.$thePlate->nameTitle.'-8-'.$hasNotAbrufenOrders;
                }
            }
            if($platesActiveSend == '0'){
                $platesActiveSend = $plateName;
            }else{
                $platesActiveSend .= '|||'.$plateName;
            }
        }
        
        return $platesActiveSend;
    }

    public function plateForAbrufenExecuteAbrufen(Request $req){
        $tabOrAll = TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['toPlate',$req->plateId],['tabCode','!=','0']])->get();
        foreach($tabOrAll as $tOrder){
            if($tOrder->abrufenStat != 1 && $tOrder->status == 1){
                $tOrder->abrufenStat = 1;
                $tOrder->save();

                // Send Notifications for the Admins
                foreach(User::where([['sFor',$tOrder->toRes],['role','5']])->get() as $user){
                    if($user->id != auth()->user()->id){
                        $details = [
                            'id' => $tOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $tOrder->tableNr
                        ];
                        $user->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
                // Send Notifications for the Waiters
                foreach(User::where([['sFor',$tOrder->toRes],['role','55']])->get() as $oneWaiter){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $tOrder->tableNr]])->first();
                    if($oneWaiter->id != Auth::user()->id && $aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $tOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $tOrder->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
                // Send Notifications for the Cooks
                foreach(User::where([['sFor',$tOrder->toRes],['role','54']])->get() as $oneCook){
                    $hasAccessCook = 0;
                    $tp = Produktet::find($tOrder->prodId);
                    if($tp != NULL){ 
                        $prodCookAcs = cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Product'],['contentId',$tOrder->prodId]])->first();
                        if($prodCookAcs != NULL){
                            $hasAccessCook = 1;
                        }
                        if( $hasAccessCook == 0 ){
                            $tc = kategori::find($tp->kategoria);
                            if($tc != NULL){ 
                                $catCookAcs = cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Category'],['contentId',$tp->kategoria]])->first();
                                if($catCookAcs != NULL){
                                    $hasAccessCook = 1;
                                }
                            }
                        }
                    }
                
                    if($hasAccessCook == 1){
                        $details = [
                            'id' => $tOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $tOrder->tableNr
                        ];
                        $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
            }
        }
    }


    public function executeAbrufenOnSelectedTabOr(Request $req){
        $tabOrToAbrufen = array();
        foreach(explode('||',$req->selectedTOr) as $selTabOne){
            $tabOrSelOne2D = explode('-8-',$selTabOne);
            $thisTabOr = TabOrder::find($tabOrSelOne2D[0]);
            if((int)$thisTabOr->OrderSasia == (int)$tabOrSelOne2D[1]){
                // selected all
                array_push($tabOrToAbrufen,$tabOrSelOne2D[0]);
            }else{
                // selected some

                $priceForOneSelTabProd = number_format($thisTabOr->OrderQmimi/$thisTabOr->OrderSasia, 2, '.', '');
                $qmimiOfSelected = number_format($priceForOneSelTabProd * (int)$tabOrSelOne2D[1], 2, '.', '');

                $thisTabOr->OrderSasia = $thisTabOr->OrderSasia - (int)$tabOrSelOne2D[1];
                $thisTabOr->OrderQmimi = number_format($thisTabOr->OrderQmimi - $qmimiOfSelected, 2, '.', '');
                $thisTabOr->save();

                // Save extra TAB order ....
                $newTabOrder = new TabOrder;
                $newTabOrder->tabCode = $thisTabOr->tabCode;
                $newTabOrder->prodId = $thisTabOr->prodId;
                $newTabOrder->OrderEmri = $thisTabOr->OrderEmri;
                $newTabOrder->tableNr = $thisTabOr->tableNr;
                $newTabOrder->toRes = $thisTabOr->toRes;
                $newTabOrder->OrderPershkrimi= $thisTabOr->OrderPershkrimi;
                $newTabOrder->OrderSasia = (int)$tabOrSelOne2D[1];
                $newTabOrder->OrderSasiaDone = 0;
                $newTabOrder->OrderQmimi = number_format($qmimiOfSelected, 2, '.', '');
                $newTabOrder->OrderExtra = $thisTabOr->OrderExtra;
                $newTabOrder->OrderType = $thisTabOr->OrderType;
                $newTabOrder->OrderKomenti = $thisTabOr->OrderKomenti;
                $newTabOrder->status = 1;
                $newTabOrder->toPlate = $thisTabOr->toPlate;
                $newTabOrder->abrufenStat = $thisTabOr->abrufenStat;
                $newTabOrder->created_at = $thisTabOr->created_at;
                $newTabOrder->save();

                $theNrVer = tabVerificationPNumbers::where('tabOrderId',$thisTabOr->id)->first();
                // Save the number ....
                $newNrVerification = new tabVerificationPNumbers;
                $newNrVerification->phoneNr = $theNrVer->phoneNr;
                $newNrVerification->tabCode = $theNrVer->tabCode;
                $newNrVerification->tabOrderId = $newTabOrder->id;
                $newNrVerification->status = 1;
                $newNrVerification->save();

                $newTabOrder->usrPhNr = $theNrVer->phoneNr;
                $newTabOrder->save();

                $waLogPrev = waiterActivityLog::where([['actType','newProdWa'],['actId',$thisTabOr->id]])->first();
                $waLogPrev->sasia = $thisTabOr->OrderSasia;
                $waLogPrev->save();

                $waLog = new waiterActivityLog();
                $waLog->waiterId = $waLogPrev->waiterId;
                $waLog->actType = 'newProdWa';
                $waLog->actId = $newTabOrder->id;
                $waLog->sasia = (int)$tabOrSelOne2D[1];
                $waLog->save();

                array_push($tabOrToAbrufen,$newTabOrder->id);
            }
        }

        $tabOrAll = TabOrder::whereIn('id',$tabOrToAbrufen)->get();
        foreach($tabOrAll as $tabOrToAbrufenOne){
            if($tabOrToAbrufenOne->abrufenStat != 1 && $tabOrToAbrufenOne->status == 1){
                $tabOrToAbrufenOne->abrufenStat = 1;
                $tabOrToAbrufenOne->save();

                // Send Notifications for the Admins
                foreach(User::where([['sFor',$tabOrToAbrufenOne->toRes],['role','5']])->get() as $user){
                    if($user->id != auth()->user()->id){
                        $details = [
                            'id' => $tabOrToAbrufenOne->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $tabOrToAbrufenOne->tableNr
                        ];
                        $user->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
                // Send Notifications for the Waiters
                foreach(User::where([['sFor',$tabOrToAbrufenOne->toRes],['role','55']])->get() as $oneWaiter){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $tabOrToAbrufenOne->tableNr]])->first();
                    if($oneWaiter->id != Auth::user()->id && $aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $tabOrToAbrufenOne->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $tabOrToAbrufenOne->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
                // Send Notifications for the Cooks
                foreach(User::where([['sFor',$tabOrToAbrufenOne->toRes],['role','54']])->get() as $oneCook){
                    $hasAccessCook = 0;
                    $tp = Produktet::find($tabOrToAbrufenOne->prodId);
                    if($tp != NULL){ 
                        $prodCookAcs = cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Product'],['contentId',$tabOrToAbrufenOne->prodId]])->first();
                        if($prodCookAcs != NULL){
                            $hasAccessCook = 1;
                        }
                        if( $hasAccessCook == 0 ){
                            $tc = kategori::find($tp->kategoria);
                            if($tc != NULL){ 
                                $catCookAcs = cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Category'],['contentId',$tp->kategoria]])->first();
                                if($catCookAcs != NULL){
                                    $hasAccessCook = 1;
                                }
                            }
                        }
                    }
                
                    if($hasAccessCook == 1){
                        $details = [
                            'id' => $tabOrToAbrufenOne->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $tabOrToAbrufenOne->tableNr
                        ];
                        $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
            }
        }
    }


    public function deleteTabOrderCheckForConfirmed(Request $req){
        // tableNr
        $hasSomeConfirmed = 0;
        if($req->tabOrSel == '0'){
            $tabOrAll = TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['tabCode','!=','0']])->get();
        }else{
            $selectedTabOr = array();
            foreach(explode('||',$req->tabOrSel) as $selTabOne){
                $tabOrSelOne2D = explode('-8-',$selTabOne);
                array_push($selectedTabOr,$tabOrSelOne2D[0]);
            }
            $tabOrAll = TabOrder::whereIn('id',$selectedTabOr)->get();
        }
        foreach($tabOrAll as $tabOrOne){
            if($tabOrOne->status == 1){
                $hasSomeConfirmed = 1;
                break;
            }
        }
        if($hasSomeConfirmed == 1){
            return 'hasSomeConfirmed';
        }else{
            return 'hasNotSomeConfirmed';
        }
    }

    public function tabOrderModalCheckTotalPriceShow(Request $req){
         $totalQmimiShow = number_format(0, 2, '.', '');
        if($req->tabOrSel == '0'){
            $tabOrAll = TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['tabCode','!=','0']])->get();
            
            foreach($tabOrAll as $tabOrOne){
                if($tabOrOne->status < 2){
                    $totalQmimiShow += number_format($tabOrOne->OrderQmimi, 2, '.', '');
                }
            }
        }else{
            foreach(explode('||',$req->tabOrSel) as $selTabOne){
                $tabOrSelOne2D = explode('-8-',$selTabOne);
                $tabOrOne = TabOrder::find($tabOrSelOne2D[0]);

                if($tabOrOne->status < 2){
                    $priceForOneSelTabProd = number_format($tabOrOne->OrderQmimi/$tabOrOne->OrderSasia, 2, '.', '');
                    $qmimiOfSelected = number_format($priceForOneSelTabProd * (int)$tabOrSelOne2D[1], 2, '.', '');

                    $totalQmimiShow += number_format($qmimiOfSelected, 2, '.', '');
                }
            }
        }
        return $totalQmimiShow;
    }

    public function tabOrderModalCheckActiveOrToReopen(Request $req){
        if(TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['tabCode','!=','0']])->count() > 0){
            return 'Yes';
        }else{
            return 'No';
        }
    }



    public function reRegisterProdsToTab(Request $req){
        // tableNr: tNr,
        // tabOrderSelected: $('#closeOrSelected'+tNr).val(),

        // Add as a tab order 
        $tableOfRes = TableQrcode::where([['tableNr',$req->tableNr],['Restaurant',Auth::user()->sFor]])->first();
        if($tableOfRes->kaTab != 0 && $tableOfRes->kaTab != -1){
            $tabCodeN = $tableOfRes->kaTab;
        }else{
            $tabCodeN = $this->genTheNewTabCode();
            $tableOfRes->kaTab = $tabCodeN;
            $tableOfRes->save();
        }
        // ---------------------------------------------

        $showProdDataAll = "none";

        // 1996-8-1||1997-8-1||1995-8-1||2000-8-3
        foreach(explode('||',$req->tabOrderSelected) as $selTabOne){
            // Process each selected tab order
            $tabOrSelOne2D = explode('-8-',$selTabOne);
            $thisTabOr = TabOrder::find($tabOrSelOne2D[0]);

            $newTabOrder = new TabOrder;
            $newTabOrder->tabCode = $thisTabOr->tabCode;
            $newTabOrder->prodId = $thisTabOr->prodId;
            $newTabOrder->OrderEmri = $thisTabOr->OrderEmri;
            $newTabOrder->tableNr = $thisTabOr->tableNr;
            $newTabOrder->toRes = $thisTabOr->toRes;
            $newTabOrder->OrderPershkrimi= $thisTabOr->OrderPershkrimi;
            $newTabOrder->OrderSasia = (int)$tabOrSelOne2D[1];
            $newTabOrder->OrderSasiaDone = 0;

            $ordeQmimiforOne = number_format($thisTabOr->OrderQmimi / $thisTabOr->OrderSasia, 2, '.', '');
            $newTabOrder->OrderQmimi = number_format($ordeQmimiforOne * (int)$tabOrSelOne2D[1], 2, '.', '');

            $newTabOrder->OrderExtra = $thisTabOr->OrderExtra;
            $newTabOrder->OrderType = $thisTabOr->OrderType;
            $newTabOrder->OrderKomenti = $thisTabOr->OrderKomenti;
            $newTabOrder->status = Auth::user()->tableOrAutoConfirm;
            $newTabOrder->toPlate = $thisTabOr->toPlate;
            $newTabOrder->abrufenStat = 0;
            $newTabOrder->usrPhNr = '0770000000';
            $newTabOrder->save();

            // waiter LOG
            $waLog = new waiterActivityLog();
            $waLog->waiterId = Auth::User()->id;
            $waLog->actType = 'newProdWa';
            $waLog->actId = $newTabOrder->id;
            $waLog->save();
            // ----------------------------------------------------

            // Save the number ....
            $newNrVerification = new tabVerificationPNumbers;
            $newNrVerification->phoneNr = '0770000000';
            $newNrVerification->tabCode = $tabCodeN;
            $newNrVerification->tabOrderId = $newTabOrder->id;
            $newNrVerification->status = 1;
            $newNrVerification->save();
            // ----------------------------------------------------

            // Send Notifications for the Admin
            foreach(User::where([['sFor',$thisTabOr->toRes],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $newTabOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $thisTabOr->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));

                    if(newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$thisTabOr->tableNr],['tabOrderId',$newTabOrder->id],['statActive','1']])->first() == NULL){
                        $newAdmAlert = new newOrdersAdminAlert();
                        $newAdmAlert->adminId = $user->id;
                        $newAdmAlert->tableNr = $thisTabOr->tableNr;
                        $newAdmAlert->toRes = $thisTabOr->toRes;
                        $newAdmAlert->tabOrderId = $newTabOrder->id;
                        $newAdmAlert->statActive = 1;
                        $newAdmAlert->save();
                    }
                }
            }

            foreach(User::where([['sFor',$thisTabOr->toRes],['role','55']])->get() as $oneWaiter){
                if($oneWaiter->id != auth()->user()->id){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $thisTabOr->tableNr]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $newTabOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $thisTabOr->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));

                        if(newOrdersAdminAlert::where([['adminId',$oneWaiter->id],['tableNr',$thisTabOr->tableNr],['tabOrderId',$newTabOrder->id],['statActive','1']])->first() == NULL){
                            $newAdmAlert = new newOrdersAdminAlert();
                            $newAdmAlert->adminId = $oneWaiter->id;
                            $newAdmAlert->tableNr = $thisTabOr->tableNr;
                            $newAdmAlert->toRes = $thisTabOr->toRes;
                            $newAdmAlert->tabOrderId = $newTabOrder->id;
                            $newAdmAlert->statActive = 1;
                            $newAdmAlert->save();
                        }
                    }
                }
            }

            if(Auth::user()->tableOrAutoConfirm == 1){
                // Send Notifications for the Cooks
                foreach(User::where([['sFor',$newTabOrder->toRes],['role','54']])->get() as $oneCook){
                    $details = [
                        'id' => $newTabOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $newTabOrder->tableNr
                    ];
                    $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
    

            // build the order showing array

            if($newTabOrder->OrderKomenti == Null){$tabOrComm = 'empty';
            }else{ $tabOrComm = $newTabOrder->OrderKomenti; }

            $waLog = waiterActivityLog::where([['actType','newProdWa'],['actId',$newTabOrder->id]])->first();
            if($waLog != Null ){ $waiterDataName = User::find($waLog->waiterId)->name; }else{ $waiterDataName = 'Administrator'; }

            $thePlateOfTO = resPlates::find($newTabOrder->toPlate);
            if($thePlateOfTO != Null){ $thePlate = $thePlateOfTO->nameTitle;
            }else{ $thePlate = 'none'; }

            if($newTabOrder->OrderExtra != 'empty' && $newTabOrder->OrderExtra != Null){ $extraToShow = $newTabOrder->OrderExtra;
            }else{ $extraToShow = 'empty'; }
                                                                                                            
            $showProdData = $thisTabOr->tableNr.'-||-'.$newTabOrder->id.'-||-'.$newTabOrder->status.'-||-'.$newTabOrder->OrderQmimi.'-||-'.
            $newTabOrder->created_at.'-||-'.$newTabOrder->OrderSasia.'-||-'.$newTabOrder->OrderEmri.'-||-'.$newTabOrder->OrderPershkrimi.'-||-'.
            $newTabOrder->OrderType.'-||-'.$tabOrComm.'-||-'.$waiterDataName.'-||-'.$thePlate.'-||-'.$newTabOrder->tabCode.'-||-'.$extraToShow.'-||-'.
            $newTabOrder->OrderSasiaDone.'-||-'.$newTabOrder->usrPhNr.'-||-'.$newTabOrder->toPlate.'-||-'.$newTabOrder->abrufenStat;

            if($showProdDataAll == 'none'){
                $showProdDataAll = $showProdData;
            }else{
                $showProdDataAll .= '--|||--'.$showProdData;
            }
        }

        return $showProdDataAll;
    }



    public function regNotShowVideoAd(Request $req){
        $newIns = new displayAddForUser();
        $newIns->toUser = Auth::user()->id;
        $newIns->status = 0;
        $newIns->forVideo = 'addReRegisterGifOptimize.gif';
        $newIns->save();
    }


    public function changeAutoConfOrdTableStatus(Request $req){
        $user = User::find(Auth::user()->id);
        if($user->tableOrAutoConfirm == 1){
            $user->tableOrAutoConfirm = 0;
        }else{
            $user->tableOrAutoConfirm = 1;
        }
        $user->save();
    }
}