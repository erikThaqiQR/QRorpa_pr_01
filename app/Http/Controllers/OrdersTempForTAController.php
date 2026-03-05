<?php

namespace App\Http\Controllers;

use PDF;
use QRCode;
use App\User;
use App\ekstra;
use App\Orders;
use App\TipLog;
use App\giftCard;
use App\kategori;
use App\Takeaway;
use App\LlojetPro;
use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use App\OrdersPassive;
use App\taDeForCookOr;
use App\billTabletsReg;
use App\rechnungClient;
use App\ordersTempForTA;
use App\waiterActivityLog;
use App\emailReceiptFromAdm;
use App\newOrdersAdminAlert;
use Illuminate\Http\Request;
use App\payTecTransactionLog;
use App\cooksProductSelection;
use App\rechnungClientToBills;
use App\tablesAccessToWaiters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;

class OrdersTempForTAController extends Controller
{

    public function searchProdsForTaTemp(Request $req){
        // resId
        // phraseS
        $kategoritToS = array();
        foreach(kategori::where('emri', 'like', '%'.$req->phraseS.'%')->where('toRes','=', Auth::user()->sFor)->get() as $addThisCatToSearch){
            array_push($kategoritToS,  $addThisCatToSearch->id);
        }

        $filteredData = Takeaway::where('emri', 'like', '%'.$req->phraseS.'%')->where('toRes','=', Auth::user()->sFor)
        ->orWhere('pershkrimi', 'like', '%'.$req->phraseS.'%')->where('toRes','=', Auth::user()->sFor)
        ->orWhere('type', 'like', '%'.$req->phraseS.'%')->where('toRes','=', Auth::user()->sFor)
        ->orWhere('extPro', 'like', '%'.$req->phraseS.'%')->where('toRes','=', Auth::user()->sFor)
        ->orWhereIn('kategoria',$kategoritToS)->get();

        return json_encode( $filteredData );
    }


    public function storeExpresWOT(Request $req){
        // tpId
        $tap = Takeaway::findOrFail($req->tpId);

        $newTATemp = new ordersTempForTA();
        $newTATemp->taProdId = $tap->id;
        $newTATemp->taProdName = $tap->emri;
        $newTATemp->taProdCatName = kategori::findOrFail($tap->kategoria)->emri;
        $newTATemp->taProdDsc = $tap->pershkrimi;
        $newTATemp->taProdMwst = $tap->mwstForPro;
        $newTATemp->toRes = Auth::user()->sFor;
        $newTATemp->fromWo = Auth::user()->id;
        $newTATemp->proSasia = 1;
        $newTATemp->proQmimi = $tap->qmimi;
        $newTATemp->proExtra = 'empty';
        $newTATemp->proType = 'empty';
        $newTATemp->procomm = 'empty';
        $newTATemp->theStatus = 0;
        $newTATemp->save();
    }

    public function storeExpresWT(Request $req){
        // pid: taProId,
        // typeId: tyId,
        // newPrice: priceByType,

        $tap = Takeaway::findOrFail($req->pid);
        $ty = LlojetPro::findOrFail($req->typeId);

        $newTATemp = new ordersTempForTA();
        $newTATemp->taProdId = $tap->id;
        $newTATemp->taProdName = $tap->emri;
        $newTATemp->taProdCatName = kategori::findOrFail($tap->kategoria)->emri;
        $newTATemp->taProdDsc = $tap->pershkrimi;
        $newTATemp->taProdMwst = $tap->mwstForPro;
        $newTATemp->toRes = Auth::user()->sFor;
        $newTATemp->fromWo = Auth::user()->id;
        $newTATemp->proSasia = 1;
        $newTATemp->proQmimi = number_format($req->newPrice, 2, '.', '');
        $newTATemp->proExtra = 'empty';
        $newTATemp->proType = $ty->emri.'||'.$ty->vlera;
        $newTATemp->procomm = 'empty';
        $newTATemp->theStatus = 0;
        $newTATemp->save();
    }


    public function deleteTempOrder(Request $req){
        $taTempToDel = ordersTempForTA::find($req->insId);
        if($taTempToDel != NULL){ $taTempToDel->delete(); }
    }

    public function deleteTempOrderAll(Request $req){
        foreach(ordersTempForTA::where([['toRes',Auth::user()->sFor],['fromWo',Auth::user()->id]])->get() as $tempTAOrOne){
            $tempTAOrOne->delete();
        }
    }


    public function storeTempOrder(Request $req){
        // prodId: pId,
        // resN: '{{Auth::user()->sFor}}',
        // name: $('#ProdAddEmri'+pId).val(),
        // persh: $('#ProdAddPershk'+pId).val(),
        // sasia: $('#sasiaProd'+pId).val(),
        // qmimi: $('#ProdAddQmimi'+pId).val(),
        // ekstra: $('#ProdAddExtra'+pId).val(),
        // types: $('#ProdAddLlojet'+pId).val(),
        // komm: $('#komentMenuAjax'+pId).val(),
        // plate: $('#plateFor'+pId).val(),

        $tap = Takeaway::findOrFail($req->prodId);

        $newTATemp = new ordersTempForTA();
        $newTATemp->taProdId = $req->prodId;
        $newTATemp->taProdName = $req->name;
        $newTATemp->taProdCatName = kategori::findOrFail($tap->kategoria)->emri;
        $newTATemp->taProdDsc = $req->persh;
        $newTATemp->taProdMwst = $tap->mwstForPro;
        $newTATemp->toRes = Auth::user()->sFor;
        $newTATemp->fromWo = Auth::user()->id;
        $newTATemp->proSasia = $req->sasia;
        $newTATemp->proQmimi = $req->qmimi;

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
        $newTATemp->proExtra = $newExtraRegister;

        if($req->types != ''){ $newTATemp->proType = $req->types;
        }else{ $newTATemp->proType = 'empty'; }

        if($req->komm != ''){ $newTATemp->procomm = $req->komm;
        }else{ $newTATemp->procomm = 'empty'; }
    
        $newTATemp->theStatus = 0;
        $newTATemp->save();

    }



    public function fetchOrderPay(Request $req){
        $filteredData = ordersTempForTA::where([['toRes',Auth::user()->sFor],['fromWo',Auth::user()->id],['theStatus','0']])->get();
        return json_encode( $filteredData );
    }


    private function checkCooksAcsToOrder($allOrders){
        foreach(explode('---8---',$allOrders) as $oneOrder){
            $oneOrder2D =explode('-8-',$oneOrder);
            $ResP = Produktet::find($oneOrder2D[7]);
            if($ResP != NULL){

                foreach(User::where([['sFor',Auth::user()->sFor],['role','54']])->get() as $oneCook){   
                    if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Product'],['contentId',$ResP->id]])->first() != NULL){
                        return 0;
                        break;
                    }else{
                        if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Category'],['contentId',$ResP->kategoria]])->first() != NULL){
                            return 0;
                            break;
                        }else{
                            if($oneOrder2D[5] != 'empty' && $oneOrder2D[5] != ''){
                                $oTf012D = explode('||',$oneOrder2D[5]);
                                $oneTyperef01ID = LlojetPro::where([['kategoria',$ResP->kategoria],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                                if($oneTyperef01ID != NULL && cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                    return 0;
                                    break;
                                }
                            }else{
                                if($oneOrder2D[2] != 'empty' && $oneOrder2D[2] != ''){
                                    foreach(explode('--0--',$oneOrder2D[2]) as $oneExtraref01){
                                        if(str_contains($oneExtraref01, '||')){
                                            $oEf012D = explode('||',$oneExtraref01);
                                            $oneExtraref01ID = ekstra::where([['toCat',$ResP->kategoria],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                                            if($oneExtraref01ID != NULL && cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                                return 0;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
        return 3;
    }
    private function checkCooksAcsToOrderProd($orderProdukt){
        $oneOrder2D =explode('-8-',$orderProdukt);
        $ResP = Produktet::find($oneOrder2D[7]);
        if($ResP != NULL){

            foreach(User::where([['sFor',Auth::user()->sFor],['role','54']])->get() as $oneCook){   
                if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Product'],['contentId',$ResP->id]])->first() != NULL){
                    return 'Yes';
                    break;
                }else{
                    if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Category'],['contentId',$ResP->kategoria]])->first() != NULL){
                        return 'Yes';
                        break;
                    }else{
                        if($oneOrder2D[5] != 'empty' && $oneOrder2D[5] != ''){
                            $oTf012D = explode('||',$oneOrder2D[5]);
                            $oneTyperef01ID = LlojetPro::where([['kategoria',$ResP->kategoria],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                            if($oneTyperef01ID != NULL && cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                return 'Yes';
                                break;
                            }
                        }else{
                            if($oneOrder2D[2] != 'empty' && $oneOrder2D[2] != ''){
                                foreach(explode('--0--',$oneOrder2D[2]) as $oneExtraref01){
                                    if(str_contains($oneExtraref01, '||')){
                                        $oEf012D = explode('||',$oneExtraref01);
                                        $oneExtraref01ID = ekstra::where([['toCat',$ResP->kategoria],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                                        if($oneExtraref01ID != NULL && cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                            return 'Yes';
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return 'No';
    }


//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function payTempTaWithCash(Request $req){
        // resId: '{{Auth::user()->sFor}}',
        // disReason: discR,
        // cashDis: $('#cashDiscountInpCash').val(),
        // percDis: $('#percentageDiscountInpCash').val(),
        // tvsh: $('#tvshAmProdsPerventageCash').val(),
        // thePayM: 'Barzahlungen',

        $saveOrderAll = '';
        $totalPay = 0 ;

        foreach(ordersTempForTA::where([['toRes',Auth::user()->sFor],['fromWo',Auth::user()->id],['theStatus','0']])->get() as $orOne){
            $taP = Takeaway::findOrFail($orOne->taProdId);

            if($orOne->proExtra != 'empty'){ $regExtra = $orOne->proExtra; }else{  $regExtra = ''; }
            if($orOne->proType != 'empty'){ $regType = $orOne->proType; }else{  $regType = ''; }

            $totPrice = number_format((float)$orOne->proSasia * (float)$orOne->proQmimi,2,'.','');

            // $theP = Produktet::find($taP->prodId);
            if($taP != Null){ $grId = $taP->toReportCat; }else{ $grId = 0; }

            if($taP->prod_id == 0){
                $theProdIdOnOrder = $taP->id; 
            }else{
                $theProdIdOnOrder = $taP->prod_id;
            }

            if($saveOrderAll != ''){
                $saveOrderAll .= "---8---".$orOne->taProdName."-8-".$orOne->taProdDsc."-8-".$regExtra.'-8-'.$orOne->proSasia.'-8-'.$totPrice
                .'-8-'.$regType.'-8-'.$orOne->procomm.'-8-'.$theProdIdOnOrder.'-8-'.$grId;
            }else{
                $saveOrderAll = $orOne->taProdName."-8-".$orOne->taProdDsc."-8-".$regExtra.'-8-'.$orOne->proSasia.'-8-'.$totPrice
                .'-8-'.$regType.'-8-'.$orOne->procomm.'-8-'.$theProdIdOnOrder.'-8-'.$grId;
            }
            $totalPay += (float)$totPrice;

            // ------------------------------------------------------------
            // Fshihen nga regjistri produktet TEMP TA
            $orOne->delete();
            // ------------------------------------------------------------
        }

        $newOrder = new Orders;
        $newOrder->nrTable = '500';
        $newOrder->statusi = $this->checkCooksAcsToOrder($saveOrderAll);
        $newOrder->byId = 0;
        $newOrder->userEmri = "admin";
        $newOrder->userEmail = "admin";
        if($saveOrderAll == ''){$newOrder->porosia = 'empty'; }else{$newOrder->porosia = $saveOrderAll;} //$request->userPorosia;
        $newOrder->payM = 'Barzahlungen';

        if(isset($req->tipp)){
            $newOrder->shuma =number_format($totalPay + number_format((float)$req->tipp, 2, '.', ''), 2, '.', ''); //$request->Shuma
        }else{
            $newOrder->shuma =number_format($totalPay, 2, '.', ''); //$request->Shuma
        }
        
        $newOrder->Restaurant = Auth::user()->sFor;
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
        $newOrder->inCashDiscount = number_format($req->cashDis, 2, '.', '');
        $newOrder->inPercentageDiscount = $req->percDis;
        $newOrder->dicsountGcAmnt = $req->discGCAmnt;
        $newOrder->mwstVal = number_format(0, 2, '.', '');
        $newOrder->servedBy = Auth::user()->id;
    
        $refIfOrPa = OrdersPassive::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
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

         // waiter LOG
         if(Auth::User()->role == 55 && $newOrder->porosia != 'empty'){
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
            $newTip->toRes = Auth::user()->sFor;
            if(Auth::check()){
                $newTip->klienti = Auth::user()->id;
            }else{
                $newTip->klienti = 9999999;
            }
            $newTip->save();
        }
        // ---------------------------------

        // Gen the next indentifikation number (shifra - per Orders) 
        $orderSh = $this->genShifra(Auth::user()->sFor);
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
        // ------------------------------------------------------------

        // save orders for the COOK
            foreach(explode('---8---',$newOrder->porosia) as $orOne){
                $orOne2D = explode('-8-',$orOne);
                $taProd = Takeaway::where('prod_id',$orOne2D[7])->first();

                $newOrForCook = new taDeForCookOr();
                $newOrForCook->toRes = Auth::user()->sFor;
                $newOrForCook->serviceType = 1;
                $newOrForCook->orderId = $newOrder->id ;
                if($taProd != NULL){
                    $newOrForCook->prodCat = $taProd->kategoria;
                    $newOrForCook->prodId =  $taProd->prod_id;
                }else{
                    $newOrForCook->prodCat = 0;
                    $newOrForCook->prodId =  0;
                }
                $newOrForCook->prodName = $orOne2D[0];
                if($orOne2D[5] != '' && $orOne2D[5] != ' '){$newOrForCook->prodType = $orOne2D[5];
                }else{$newOrForCook->prodType =  'empty';}
                if($orOne2D[2] != '' && $orOne2D[2] != ' '){$newOrForCook->prodExtra = $orOne2D[2];
                }else{$newOrForCook->prodExtra =  'empty';}
                $newOrForCook->prodQmimi = (float)$orOne2D[4];
                $newOrForCook->prodComm = $orOne2D[6];
                $newOrForCook->prodSasia = $orOne2D[3];
                if($this->checkCooksAcsToOrderProd($orOne) == 'No'){
                    $newOrForCook->prodSasiaDone = $orOne2D[3];
                }else{
                    $newOrForCook->prodSasiaDone = 0;
                }
                $newOrForCook->save();
            }
        // -------------------------------------------------------------------
        

         // Send Notifications for the Admin
         foreach(User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'AdminUpdateOrdersPT',
                    'tableNr' => $req->tableNr
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        if(Auth::user()->role != 55){
            foreach(User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $oneWaiter){
                if($oneWaiter->id != auth()->user()->id){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersPT',
                        'tableNr' => $req->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
        }
        foreach(User::where([['sFor',Auth::user()->sFor],['role','54']])->get() as $oneCook){
            $details = [
                'id' => 0,
                'type' => 'AdminUpdateOrdersP',
                'tableNr' => $req->tableNr
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }

        $newOrAlertToDel = newOrdersAdminAlert::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['statActive','1']])->get();
        if($newOrAlertToDel != NULL){
            foreach($newOrAlertToDel as $newOrAlertToDelOne){
                $newOrAlertToDelOne->delete();
            }
        }

        // Clean the bill tablet 
            foreach(billTabletsReg::where('toStaffId',Auth::user()->id)->get() as $billTabl){
                $billTabl->currTipp = number_format(0, 2, '.', '');
                $billTabl->currRabatt = number_format(0, 2, '.', '');
                $billTabl->currGCValue = number_format(0, 2, '.', '');
                $billTabl->currGCName = 'none';
                $billTabl->showBillQRCode = 1;
                $billTabl->save();
            }
        // --------------------------------------------------------------

        if($newOrder->statusi == 3){
            return 'autoConfPayed||'.$newOrder->id;
        }else{
            return 'notAutoConfPayed||'.$newOrder->id;
        }
    }







    //------------------------------------------------------------------------------------------------------------------------------------------------------------------------------







    public function payTempTaWithCard(Request $req){
        // resId: '{{Auth::user()->sFor}}',
        // disReason: discR,
        // cashDis: $('#cashDiscountInp').val(),
        // percDis: $('#percentageDiscountInp').val(),
        // tvsh: $('#tvshAmProdsPerventage').html(),
        // thePayM: 'Kartenzahlung',

        $saveOrderAll = '';
        $totalPay = 0 ;

        foreach(ordersTempForTA::where([['toRes',Auth::user()->sFor],['fromWo',Auth::user()->id],['theStatus','0']])->get() as $orOne){
            $taP = Takeaway::findOrFail($orOne->taProdId);

            if($orOne->proExtra != 'empty'){ $regExtra = $orOne->proExtra; }else{  $regExtra = ''; }
            if($orOne->proType != 'empty'){ $regType = $orOne->proType; }else{  $regType = ''; }

            $totPrice = number_format((float)$orOne->proSasia * (float)$orOne->proQmimi,2,'.','');

            if($taP != Null){ $grId = $taP->toReportCat; }else{ $grId = 0; }
            if($taP->prod_id == 0){
                $theProdIdOnOrder = $taP->id; 
            }else{
                $theProdIdOnOrder = $taP->prod_id;
            }

            if($saveOrderAll != ''){
               
                $saveOrderAll .= "---8---".$orOne->taProdName."-8-".$orOne->taProdDsc."-8-".$regExtra.'-8-'.$orOne->proSasia.'-8-'.$totPrice
                .'-8-'.$regType.'-8-'.$orOne->procomm.'-8-'.$theProdIdOnOrder.'-8-'.$grId;
            }else{
                $saveOrderAll = $orOne->taProdName."-8-".$orOne->taProdDsc."-8-".$regExtra.'-8-'.$orOne->proSasia.'-8-'.$totPrice
                .'-8-'.$regType.'-8-'.$orOne->procomm.'-8-'.$theProdIdOnOrder.'-8-'.$grId;
            }
            $totalPay += (float)$totPrice;

            // ------------------------------------------------------------
            // Fshihen nga regjistri produktet TEMP TA
            $orOne->delete();
            // ------------------------------------------------------------
        }

        $newOrder = new Orders;
        $newOrder->nrTable = '500';
        $newOrder->statusi = $this->checkCooksAcsToOrder($saveOrderAll);
        $newOrder->byId = 0;
        $newOrder->userEmri = "admin";
        $newOrder->userEmail = "admin";
        if($saveOrderAll == ''){$newOrder->porosia = 'empty'; }else{$newOrder->porosia = $saveOrderAll;} //$request->userPorosia;
        $newOrder->payM = 'Kartenzahlung';
        if(isset($req->tipp)){
            $newOrder->shuma =number_format($totalPay + number_format((float)$req->tipp, 2, '.', ''), 2, '.', ''); //$request->Shuma
        }else{
            $newOrder->shuma =number_format($totalPay, 2, '.', ''); //$request->Shuma
        }
        $newOrder->Restaurant = Auth::user()->sFor;
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
        $newOrder->inCashDiscount = number_format($req->cashDis, 2, '.', '');
        $newOrder->inPercentageDiscount = $req->percDis;
        $newOrder->dicsountGcAmnt = $req->discGCAmnt;        
        $newOrder->mwstVal = number_format(0, 2, '.', '');
        $newOrder->servedBy = Auth::user()->id;
      
        $refIfOrPa = OrdersPassive::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
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
         if(Auth::User()->role == 55 && $newOrder->porosia != 'empty'){
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
            $newTip->toRes = Auth::user()->sFor;
            if(Auth::check()){
                $newTip->klienti = Auth::user()->id;
            }else{
                $newTip->klienti = 9999999;
            }
            $newTip->save();
        }
        // ---------------------------------

        // Gen the next indentifikation number (shifra - per Orders) 
        $orderSh = $this->genShifra(Auth::user()->sFor);
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
        // ------------------------------------------------------------

        // save orders for the COOK
            foreach(explode('---8---',$newOrder->porosia) as $orOne){
                $orOne2D = explode('-8-',$orOne);
                $taProd = Takeaway::where('prod_id',$orOne2D[7])->first();

                $newOrForCook = new taDeForCookOr();
                $newOrForCook->toRes = Auth::user()->sFor;
                $newOrForCook->serviceType = 1;
                $newOrForCook->orderId = $newOrder->id ;
                if($taProd != NULL){
                    $newOrForCook->prodCat = $taProd->kategoria;
                    $newOrForCook->prodId =  $taProd->prod_id;
                }else{
                    $newOrForCook->prodCat = 0;
                    $newOrForCook->prodId =  0;
                }
                $newOrForCook->prodName = $orOne2D[0];
                if($orOne2D[5] != '' && $orOne2D[5] != ' '){$newOrForCook->prodType = $orOne2D[5];
                }else{$newOrForCook->prodType =  'empty';}
                if($orOne2D[2] != '' && $orOne2D[2] != ' '){$newOrForCook->prodExtra = $orOne2D[2];
                }else{$newOrForCook->prodExtra =  'empty';}
                $newOrForCook->prodQmimi = (float)$orOne2D[4];
                $newOrForCook->prodComm = $orOne2D[6];
                $newOrForCook->prodSasia = $orOne2D[3];
                if($this->checkCooksAcsToOrderProd($orOne) == 'No'){
                    $newOrForCook->prodSasiaDone = $orOne2D[3];
                }else{
                    $newOrForCook->prodSasiaDone = 0;
                }
                $newOrForCook->save();
            }
        // -------------------------------------------------------------------
        

         // Send Notifications for the Admin
         foreach(User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'AdminUpdateOrdersPT',
                    'tableNr' => $req->tableNr
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        if(Auth::user()->role != 55){
            foreach(User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $oneWaiter){
                if($oneWaiter->id != auth()->user()->id){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersPT',
                        'tableNr' => $req->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
        }
        foreach(User::where([['sFor',Auth::user()->sFor],['role','54']])->get() as $oneCook){
            $details = [
                'id' => 0,
                'type' => 'AdminUpdateOrdersP',
                'tableNr' => $req->tableNr
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }

        $newOrAlertToDel = newOrdersAdminAlert::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['statActive','1']])->get();
        if($newOrAlertToDel != NULL){
            foreach($newOrAlertToDel as $newOrAlertToDelOne){
                $newOrAlertToDelOne->delete();
            }
        }

        // Clean the bill tablet 
            foreach(billTabletsReg::where('toStaffId',Auth::user()->id)->get() as $billTabl){
                $billTabl->currTipp = number_format(0, 2, '.', '');
                $billTabl->currRabatt = number_format(0, 2, '.', '');
                $billTabl->currGCValue = number_format(0, 2, '.', '');
                $billTabl->currGCName = 'none';
                $billTabl->showBillQRCode = 1;
                $billTabl->save();
            }
        // --------------------------------------------------------------

        if($newOrder->statusi == 3){
            return 'autoConfPayed||'.$newOrder->id;
        }else{
            return 'notAutoConfPayed||'.$newOrder->id;
        }
    }








    //------------------------------------------------------------------------------------------------------------------------------------------------------------------------------







    public function payTempTaByBillWithData(Request $req){
        $saveOrderAll = '';
        $totalPay = 0 ;

        foreach(ordersTempForTA::where([['toRes',Auth::user()->sFor],['fromWo',Auth::user()->id],['theStatus','0']])->get() as $orOne){
            $taP = Takeaway::findOrFail($orOne->taProdId);

            if($orOne->proExtra != 'empty'){ $regExtra = $orOne->proExtra; }else{  $regExtra = ''; }
            if($orOne->proType != 'empty'){ $regType = $orOne->proType; }else{  $regType = ''; }

            $totPrice = number_format((float)$orOne->proSasia * (float)$orOne->proQmimi,2,'.','');

            if($taP != Null){ $grId = $taP->toReportCat; }else{ $grId = 0; }

            if($taP->prod_id == 0){
                $theProdIdOnOrder = $taP->id; 
            }else{
                $theProdIdOnOrder = $taP->prod_id;
            }

            if($saveOrderAll != ''){
                $saveOrderAll .= "---8---".$orOne->taProdName."-8-".$orOne->taProdDsc."-8-".$regExtra.'-8-'.$orOne->proSasia.'-8-'.$totPrice
                .'-8-'.$regType.'-8-'.$orOne->procomm.'-8-'.$theProdIdOnOrder.'-8-'.$grId;
            }else{
                $saveOrderAll = $orOne->taProdName."-8-".$orOne->taProdDsc."-8-".$regExtra.'-8-'.$orOne->proSasia.'-8-'.$totPrice
                .'-8-'.$regType.'-8-'.$orOne->procomm.'-8-'.$theProdIdOnOrder.'-8-'.$grId;
            }
            $totalPay += (float)$totPrice;

            // ------------------------------------------------------------
            // Fshihen nga regjistri produktet TEMP TA
            $orOne->delete();
            // ------------------------------------------------------------
        }

        $newOrder = new Orders;
        $newOrder->nrTable = '500';
        $newOrder->statusi = $this->checkCooksAcsToOrder($saveOrderAll);
        $newOrder->byId = 0;
        $newOrder->userEmri = "admin";
        $newOrder->userEmail = "admin";
        if($saveOrderAll == ''){$newOrder->porosia = 'empty'; }else{$newOrder->porosia = $saveOrderAll;} //$request->userPorosia;
        $newOrder->payM = 'Rechnung';
        if(isset($req->tipForOrderRechnung)){
            $newOrder->shuma =number_format($totalPay + number_format((float)$req->tipForOrderRechnung, 2, '.', ''), 2, '.', ''); //$request->Shuma
        }else{
            $newOrder->shuma =number_format($totalPay, 2, '.', ''); //$request->Shuma
        }
        $newOrder->Restaurant = Auth::user()->sFor;
        $newOrder->userPhoneNr = "0770000000";
        $newOrder->tipPer =  0;
        if(isset($req->tipForOrderRechnung)){
            $bakshishi =number_format((float)$req->tipForOrderRechnung, 2, '.', '') ;
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
        $newOrder->discReason = $req->discReasonRechnung;
        $newOrder->inCashDiscount = $req->cashDiscountInpRechnung;
        $newOrder->inPercentageDiscount = $req->percentageDiscountInpRechnung;
        $newOrder->dicsountGcAmnt = $req->payAllRechnungGiftCardAmount;
        $newOrder->mwstVal = number_format(0, 2, '.', '');
        $newOrder->servedBy = Auth::user()->id;
        
        $refIfOrPa = OrdersPassive::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->save();

        // Register Gift Card use
        if($req->discGCId != 0){
            $TheGC = giftCard::find($req->discGCId);
            if($TheGC != Null){
                $TheGC->gcSumInChfUsed = number_format($TheGC->gcSumInChfUsed + $req->payAllRechnungGiftCardAmount,2,'.','');
                if($TheGC->usedInOrdersId != 'empty'){ $TheGC->usedInOrdersId = $TheGC->usedInOrdersId.'|||'.$newOrder->id;
                }else{ $TheGC->usedInOrdersId = $newOrder->id; }
                $TheGC->save();
            }
        }

        // waiter LOG
        if(Auth::User()->role == 55 && $newOrder->porosia != 'empty'){
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
        if(isset($req->tipForOrderRechnung)){
            $newTip = new TipLog;

            if($req->cashDiscountInpRechnung > 0){
                $skontoCHF = number_format($req->cashDiscountInpRechnung,2,'.','');
                $toPay = number_format($totalPay - $skontoCHF,2,'.','');
              }else if($req->percentageDiscountInpRechnung > 0){
                  $skontoCHF = number_format($totalPay*($req->percentageDiscountInpRechnung/100),2,'.','');
                  $toPay = number_format($totalPay - $skontoCHF,2,'.','');
              }else{
                  $toPay = number_format($totalPay,2,'.','');
              } 

            $newTip->shumaPor = number_format($toPay + number_format((float)$req->tipForOrderRechnung, 2, '.', ''), 2, '.', '');
            $newTip->tipPer = 'Empty';
            $newTip->tipTot = number_format((float)$req->tipForOrderRechnung, 2, '.', '');
            $newTip->toRes = Auth::user()->sFor;
            if(Auth::check()){
                $newTip->klienti = Auth::user()->id;
            }else{
                $newTip->klienti = 9999999;
            }
            $newTip->save();
        }
        // ---------------------------------

        // Gen the next indentifikation number (shifra - per Orders) 
        $orderSh = $this->genShifra(Auth::user()->sFor);
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
        // ------------------------------------------------------------

        // save orders for the COOK
            foreach(explode('---8---',$newOrder->porosia) as $orOne){
                $orOne2D = explode('-8-',$orOne);
                $taProd = Takeaway::where('prod_id',$orOne2D[7])->first();

                $newOrForCook = new taDeForCookOr();
                $newOrForCook->toRes = Auth::user()->sFor;
                $newOrForCook->serviceType = 1;
                $newOrForCook->orderId = $newOrder->id ;
                if($taProd != NULL){
                    $newOrForCook->prodCat = $taProd->kategoria;
                    $newOrForCook->prodId =  $taProd->prod_id;
                }else{
                    $newOrForCook->prodCat = 0;
                    $newOrForCook->prodId =  0;
                }
                $newOrForCook->prodName = $orOne2D[0];
                if($orOne2D[5] != '' && $orOne2D[5] != ' '){$newOrForCook->prodType = $orOne2D[5];
                }else{$newOrForCook->prodType =  'empty';}
                if($orOne2D[2] != '' && $orOne2D[2] != ' '){$newOrForCook->prodExtra = $orOne2D[2];
                }else{$newOrForCook->prodExtra =  'empty';}
                $newOrForCook->prodQmimi = (float)$orOne2D[4];
                $newOrForCook->prodComm = $orOne2D[6];
                $newOrForCook->prodSasia = $orOne2D[3];
                if($this->checkCooksAcsToOrderProd($orOne) == 'No'){
                    $newOrForCook->prodSasiaDone = $orOne2D[3];
                }else{
                    $newOrForCook->prodSasiaDone = 0;
                }
                $newOrForCook->save();
            }
        // -------------------------------------------------------------------

        if($req->usedAndExistingClientAll == 0){
            $clDtRechnungFirmaInp = $req->RechnungFirmaInp;
            $clDtRechnungTelInp = $req->RechnungTelInp;
            $clDtRechnungNameInp = $req->RechnungNameInp;
            $clDtRechnungVornameInp = $req->RechnungVornameInp;
            $clDtRechnungStrNrInp = $req->RechnungStrNrInp;
            $clDtRechnungPlzOrtInp = $req->RechnungPlzOrtInp;
            $clDtRechnungLandInp = $req->RechnungLandInp;
            $clDtRechnungEmailInp = $req->RechnungEmailInp;
            $clDtRechnungSignature = 'empty';
            // if($req->saveClForARechnungAllVal == 1){
            //     $dtNow = Carbon::now();
            //     if($dtNow->month == 12){ $setYear = $dtNow->year + 1; $setmonth = 1;
            //     }else{ $setYear = $dtNow->year; $setmonth = $dtNow->month + 1; }
            //     $dateToPay = new Carbon($setYear.'-'.$setmonth.'-10 23:59:59');
            //     $clDtRechnungDaysToPay = $dateToPay->diff($dtNow)->days;
            // }else{
                $clDtRechnungDaysToPay = $req->RechnungDaysToPayInp;
            // }
        }else{
            $clData = rechnungClient::find($req->usedAndExistingClientIdAll);

            $clDtRechnungFirmaInp = $clData->firmaName;
            $clDtRechnungTelInp = $clData->phoneNr;
            $clDtRechnungNameInp = $clData->name;
            $clDtRechnungVornameInp = $clData->lastname;
            $clDtRechnungStrNrInp = $clData->street;
            $clDtRechnungPlzOrtInp = $clData->plzort;
            $clDtRechnungLandInp = $clData->land;
            $clDtRechnungEmailInp = $clData->email;
            $clDtRechnungSignature = $clData->signatureFile;

            // $dtNow = Carbon::now();
            // if($dtNow->month == 12){
            //     $setYear = $dtNow->year + 1;
            //     $setmonth = 1;
            // }else{
            //     $setYear = $dtNow->year;
            //     $setmonth = $dtNow->month + 1;
            // }
            // $dateToPay = new Carbon($setYear.'-'.$setmonth.'-'.$clData->daysToPay.' 23:59:59');
            // $clDtRechnungDaysToPay = $dateToPay->diff($dtNow)->days;
            $clDtRechnungDaysToPay = $clData->daysToPay;
        }

        // Rechnung - email payment extra info
            $rechExInfo = new emailReceiptFromAdm();
            $rechExInfo->forOrder = $newOrder->id;
            $rechExInfo->forRes = Auth::user()->sFor;
            $rechExInfo->exInfoFirma = $clDtRechnungFirmaInp;
            $rechExInfo->exInfoClPhoneNr = $clDtRechnungTelInp;
            $rechExInfo->exInfoName = $clDtRechnungNameInp;
            $rechExInfo->exInfoLastname = $clDtRechnungVornameInp;
            $rechExInfo->exInfoStreet = $clDtRechnungStrNrInp;
            $rechExInfo->exInfoPlzOrt = $clDtRechnungPlzOrtInp;
            $rechExInfo->exInfoLand = $clDtRechnungLandInp;
            $rechExInfo->exInfoEmail = $clDtRechnungEmailInp;
            $rechExInfo->exInfoDaysToPay = $clDtRechnungDaysToPay;
            if($req->payAllRechnungComment != ''){ $rechExInfo->theComm = $req->payAllRechnungComment;
            }else if($req->payAllRechnungCommentClient != ''){ $rechExInfo->theComm = $req->payAllRechnungCommentClient; 
            }else{ $rechExInfo->theComm = "empty"; }
            $rechExInfo->statusConf = 0;

            if($clDtRechnungSignature == 'empty'){
                $folderPath = public_path('storage/rechnungPaySignatures/');       
                $image_parts = explode(";base64,", $req->signed);             
                $image_type_aux = explode("image/", $image_parts[0]);           
                $image_type = $image_type_aux[1];           
                $image_base64 = base64_decode($image_parts[1]); 
                $signature = uniqid() . '.'.$image_type;           
                $file = $folderPath . $signature;
                file_put_contents($file, $image_base64);

                $rechExInfo->exInfoClSignature = $signature;
                $clDtRechnungSignature = $signature;
            }else{
                $rechExInfo->exInfoClSignature = $clDtRechnungSignature;
            }
            $rechExInfo->save(); 
        // ---------------------------------

         // Save or pass the client + save the connection to the bill and order
         if($req->saveClForARechnungAllVal == 1 && $req->usedAndExistingClientAll == 0){
            $checkCl = rechnungClient::where([['toRes',Auth::user()->sFor],['firmaName',$clDtRechnungFirmaInp],['phoneNr',$clDtRechnungTelInp],['name',$clDtRechnungNameInp],['lastname',$clDtRechnungVornameInp]])->first();
            if($checkCl != Null){
                $newBillToCl = new rechnungClientToBills();
                $newBillToCl->toRes = Auth::user()->sFor;
                $newBillToCl->billId = $rechExInfo->id;
                $newBillToCl->orderId = $newOrder->id;
                $newBillToCl->clientId = $checkCl->id;
                $newBillToCl->save();
            }else{
                $newCl = new rechnungClient();
                $newCl->toRes = Auth::user()->sFor;
                $newCl->firmaName = $clDtRechnungFirmaInp;
                $newCl->phoneNr = $clDtRechnungTelInp;
                $newCl->name = $clDtRechnungNameInp;
                $newCl->lastname = $clDtRechnungVornameInp;
                $newCl->street = $clDtRechnungStrNrInp;
                $newCl->plzort = $clDtRechnungPlzOrtInp;
                $newCl->land = $clDtRechnungLandInp;
                $newCl->email = $clDtRechnungEmailInp;
                $newCl->signatureFile = $clDtRechnungSignature;
                $newCl->save();

                $newBillToCl = new rechnungClientToBills();
                $newBillToCl->toRes = Auth::user()->sFor;
                $newBillToCl->billId = $rechExInfo->id;
                $newBillToCl->orderId = $newOrder->id;
                $newBillToCl->clientId = $newCl->id;
                $newBillToCl->save();
            }
        }else if($req->usedAndExistingClientAll != 0){
            $newBillToCl = new rechnungClientToBills();
            $newBillToCl->toRes = Auth::user()->sFor;
            $newBillToCl->billId = $rechExInfo->id;
            $newBillToCl->orderId = $newOrder->id;
            $newBillToCl->clientId = $req->usedAndExistingClientIdAll;
            $newBillToCl->save();
        }
        // ------------------------------------------------------------

         // save the EBANKING qrcode
         $theOr = $newOrder;
         $theRes = Restorant::findOrFail($theOr->Restaurant);
         $theOrExInfo = emailReceiptFromAdm::where('forOrder',$newOrder->id)->first();
 
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
 
         $newOrder->ebankqrcode= $name.".png";
         $newOrder->save();
         }
         // -------------------------------------------------------------------------------------------------------

         // send emails for payments through Rechnung
        if($req->saveClForARechnungAllVal == 1 || $req->usedAndExistingClientAll != 0){
            // Send the ThankYou email ...
            $newOrder->cancelComm = 'empty';
            $newOrder->save();

            $to_name = $clDtRechnungNameInp.' '.$clDtRechnungVornameInp;
            $to_email = str_replace(' ', '', $clDtRechnungEmailInp); 
            $data = array('name'=>$to_name);

            $koha2D = explode('-',explode(' ',$newOrder->created_at)[0]);

            view()->share('items', $newOrder);
            $pdf = PDF::loadView('adminInvoice');

            $nrOfOrders19 = 0;
            $nrOfOrders19P = 0;
            $totExtra = 0;
            foreach(explode('---8---', $newOrder->porosia) as $onOr){ $or2D = explode('-8-',$onOr);
                if(strlen($onOr[5]) > 19){$nrOfOrders19P++;
                }else{$nrOfOrders19++;}
                if($or2D[2] != 'empty' && $or2D[2] != ''){
                    if(str_contains($or2D[2], '--0--')){ $nrOfExt = count(explode('--0--',$or2D[2])); $totExtra = $totExtra + $nrOfExt;
                    }else{ $totExtra++; }
                }
            }
            $customPaper = array(0,0,340.16,990+($nrOfOrders19P*32)+($nrOfOrders19*21)+($totExtra*12));
            $pdf1 = PDF::loadView('adminInvoiceRechnung')->setPaper('a4', 'portrait');

            $docName = 'rechnungBillFirst'.Restorant::find($newOrder->Restaurant)->emri.'_'.$newOrder->id.'.pdf';
            $pdf1->save('storage/rechnungBillsFirst/'.$docName);

            $pdf2s = PDF::loadView('adminInvoiceRechnungFinal')->setPaper('a4', 'portrait');
            
            Mail::send('adminInvoice', $data, function($message) use ($to_name, $to_email, $pdf2s, $newOrder, $koha2D) {
                $message->to($to_email, $to_name)
                            ->subject('Vertrag');
                $message->from('noreply@qrorpa.ch','Qrorpa');
                $message->attachData($pdf2s->output(), 'Vertrag-'.$newOrder->id.'-QRorpa-'.$koha2D[2].'_'.$koha2D[1].'_'.$koha2D[0].'.pdf', [
                    'mime' => 'application/pdf',
                ]);
            });

        }else{
            view()->share('items', $newOrder);
            $pdf = PDF::loadView('adminInvoice')->setPaper('a4', 'portrait');
            
            $to_name = $clDtRechnungNameInp.' '.$clDtRechnungVornameInp;
            $to_email = $clDtRechnungEmailInp;
            $fromRes = Restorant::find(Auth::user()->sFor);
            $reId = $newOrder->id;
            $data = array('sendName'=>$to_name, "sendEmail" => $to_email, "fromResName" => $fromRes->emri, "daysToPay" => $clDtRechnungDaysToPay, "ReId" => $newOrder->id);
            Mail::send('emails.rechnungPaymentForCl', $data , function($message)use ($to_email, $to_name ,$reId , $pdf){
                $message->from('noreply@qrorpa.ch','Qrorpa');
                $message->to($to_email, $to_name);
                $message->subject('Rechnung zur Zahlung an QRorpa');
                $message->attachData($pdf->output(), 'Rechnungsnummer:'.$reId.'.pdf', [
                    'mime' => 'application/pdf',
                ]);
            });
        }

        // ---------------------------------

         // Send Notifications for the Admin
         foreach(User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'AdminUpdateOrdersPT',
                    'tableNr' => $req->tableNr
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        if(Auth::user()->role != 55){
            foreach(User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $oneWaiter){
                if($oneWaiter->id != auth()->user()->id){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersPT',
                        'tableNr' => $req->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
        }
        foreach(User::where([['sFor',Auth::user()->sFor],['role','54']])->get() as $oneCook){
            $details = [
                'id' => 0,
                'type' => 'AdminUpdateOrdersP',
                'tableNr' => $req->tableNr
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }

        $newOrAlertToDel = newOrdersAdminAlert::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['statActive','1']])->get();
        if($newOrAlertToDel != NULL){
            foreach($newOrAlertToDel as $newOrAlertToDelOne){
                $newOrAlertToDelOne->delete();
            }
        }

        // Clean the bill tablet 
            foreach(billTabletsReg::where('toStaffId',Auth::user()->id)->get() as $billTabl){
                $billTabl->currTipp = number_format(0, 2, '.', '');
                $billTabl->currRabatt = number_format(0, 2, '.', '');
                $billTabl->currGCValue = number_format(0, 2, '.', '');
                $billTabl->currGCName = 'none';
                $billTabl->showBillQRCode = 1;
                $billTabl->save();
            }
        // --------------------------------------------------------------

        if($newOrder->statusi == 3){
            return back()->withInput(array('showBillQR' => 'Yes', 'orderId' => $newOrder->id));
        }else{
            return back();
        }

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

}
