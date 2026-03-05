<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use App\Orders;
use App\TipLog;
use App\giftCard;
use App\TabOrder;
use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use App\TableQrcode;
use App\OrdersPassive;
use App\giftCardToSell;
use App\rechnungClient;
use App\waiterActivityLog;
use App\emailReceiptFromAdm;
use App\newOrdersAdminAlert;
use Illuminate\Http\Request;
use App\payTecTransactionLog;
use App\splitBillLogInitiate;
use App\splitBillLogPayments;
use App\rechnungClientToBills;
use App\tablesAccessToWaiters;
use App\tabVerificationPNumbers;
use LaravelQRCode\Facades\QRCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;

class splitBillController extends Controller{

    private function splitBillgenShifra($res){
        $orSh = rand(1111,9999);
        $orShFi = $res.'|'.$orSh;

        if(Orders::whereDate('created_at', Carbon::today())->where([['shifra', $orShFi],['statusi','<','2']])->first() != NULL){
            return $this->splitBillgenShifra($res);
        }else{
            return $orShFi;
        }        
    }




    public function displayFirstSplit(Request $req){

        $theTable = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$req->tableNr]])->first();
        if($theTable != Null){
            // ceil($number / 0.05) * 0.05;
            $totToPayClient = number_format(0, 2, '.', '');
            $originalPrice = number_format(0, 2, '.', '');
            $toPayPrice = number_format(0, 2, '.', '');
            foreach(TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['tabCode',$theTable->kaTab]])->get() as $tabOrderOne){

                $tabOrPrice = number_format($tabOrderOne->OrderQmimi, 2, '.', '');
                $tabOrPricePerCl = number_format($tabOrPrice / $req->splitNr, 2, '.', '');
                $tabOrPricePerClFixed = ceil($tabOrPricePerCl / 0.05) * 0.05;

                $totToPayClient += number_format($tabOrPricePerClFixed, 2, '.', '');

                $originalPrice += number_format($tabOrPrice, 2, '.', '');
            }
            $toPayPrice = number_format($totToPayClient * $req->splitNr, 2, '.', '');

            $initiateLog = new splitBillLogInitiate();
            $initiateLog->toRes = Auth::user()->sFor;
            $initiateLog->tableNr = $req->tableNr;
            $initiateLog->workerId = Auth::user()->id;
            $initiateLog->nrOfClients = $req->splitNr;
            $initiateLog->originalPrice = $originalPrice;
            $initiateLog->finalPrice = $toPayPrice;
            $initiateLog->pricePerClient = $totToPayClient;
            $initiateLog->paymentComplete = 0;
            $initiateLog->save();

            return $totToPayClient.'|||'.$req->tableNr.'|||'.$req->splitNr.'|||'.$originalPrice.'|||'.$toPayPrice.'|||'.$initiateLog->id;
            
        }else{
            return 'invalideTable';
        }
    }


    public function displaysplitBillAfterRechnung(Request $req){

        $splitBillInitiate = splitBillLogInitiate::find($req->splitBillIn);

        $splitBillPayLog = splitBillLogPayments::find($req->splitBillPayLog);
        if($splitBillPayLog->aRechReShow == 0){
            $splitBillPayLog->aRechReShow = 1;
            $splitBillPayLog->save();

            $theTable = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$splitBillInitiate->tableNr]])->first();
            if($theTable != Null){
                // ceil($number / 0.05) * 0.05;
                $totToPayClient = number_format(0, 2, '.', '');
                $originalPrice = number_format(0, 2, '.', '');
                $toPayPrice = number_format(0, 2, '.', '');
                foreach(TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$splitBillInitiate->tableNr],['tabCode',$theTable->kaTab]])->get() as $tabOrderOne){

                    $tabOrPrice = number_format($tabOrderOne->OrderQmimi, 2, '.', '');
                    $tabOrPricePerCl = number_format($tabOrPrice / $splitBillInitiate->nrOfClients, 2, '.', '');
                    $tabOrPricePerClFixed = ceil($tabOrPricePerCl / 0.05) * 0.05;

                    $totToPayClient += number_format($tabOrPricePerClFixed, 2, '.', '');

                    $originalPrice += number_format($tabOrPrice, 2, '.', '');
                }
                $toPayPrice = number_format($totToPayClient * $splitBillInitiate->nrOfClients, 2, '.', '');

                $rechReturnPayM = '';
                $rechReturntips = '';
                $rechReturnGCId = '';
                $rechReturnGCAmt = '';
                foreach(splitBillLogPayments::where('initiateId',$req->splitBillIn)->get() as $splitBillPayOne){
                    
                    if($rechReturnPayM == ''){ $rechReturnPayM = $splitBillPayOne->payM;
                    }else{ $rechReturnPayM .= '--88--'.$splitBillPayOne->payM; }
            
                    if($splitBillPayOne->tipOrder == 0){
                        if($rechReturntips == ''){ $rechReturntips = '0.00';
                        }else{ $rechReturntips .= '--88--0.00'; }
                    }else{
                        if($rechReturntips == ''){ $rechReturntips = $splitBillPayOne->tipOrder;
                        }else{ $rechReturntips .= '--88--'.$splitBillPayOne->tipOrder; }
                    }  

                    if($splitBillPayOne->GCId == 0){
                        if($rechReturnGCId == ''){ $rechReturnGCId = '0.00';
                        }else{ $rechReturnGCId .= '--88--0.00'; }
                    }else{
                        if($rechReturnGCId == ''){ $rechReturnGCId = $splitBillPayOne->GCId;
                        }else{ $rechReturnGCId .= '--88--'.$splitBillPayOne->GCId; }
                    }
                    
                    if($splitBillPayOne->GCAmt == 0){
                        if($rechReturnGCAmt == ''){ $rechReturnGCAmt = '0.00';
                        }else{ $rechReturnGCAmt .= '--88--0.00'; }
                    }else{
                        if($rechReturnGCAmt == ''){ $rechReturnGCAmt = $splitBillPayOne->GCAmt;
                        }else{ $rechReturnGCAmt .= '--88--'.$splitBillPayOne->GCAmt; }
                    }
                }

                if($splitBillInitiate != Null && $splitBillInitiate->nrOfClients == $splitBillInitiate->paymentComplete){
                    $theFinalPay = 2;
                }else if($splitBillInitiate != Null && $splitBillInitiate->paymentComplete == 1){
                    $theFinalPay = 1;
                }else{
                    $theFinalPay = 0;
                }

                return $totToPayClient.'|||'.$splitBillInitiate->tableNr.'|||'.$splitBillInitiate->nrOfClients.'|||'.$originalPrice.'|||'.$toPayPrice.'|||'.$req->splitBillIn.'|||'.$rechReturnPayM.'|||'.$rechReturntips.'|||'.$splitBillInitiate->pricePerClient.'|||'.$theFinalPay.'|||'.$rechReturnGCId.'|||'.$rechReturnGCAmt;
            }else{
                return 'invalideTable';
            }
        }else{
            return 'modalAlreadyShown';
        }
    }



    public function splitBillCancelTheInitiate(Request $req){
        $sbIn = splitBillLogInitiate::find($req->sbInitiateId);
        $sbIn->cancelStatus = 1;
        $sbIn->save();
    }
























    public function splitBillPayCashCard(Request $req){
        $newOrder = new Orders;
        $tabCode = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$req->tableNr]])->first()->kaTab;

        if($tabCode != 0 && $tabCode != -1){
            $saveOrderAll = '';
            $totalPay = 0 ;
            $AllFromTab = TabOrder::where([['tableNr',$req->tableNr],['toRes',Auth::user()->sFor],['tabCode',$tabCode],['status','!=','9']])->get();

            $clientsActive = array();
            $tabOrdersToR = array();

            $tabOrdersIds = "";

            $initiateIns = splitBillLogInitiate::find($req->initiateId);
            $initiateIns->paymentComplete = $initiateIns->paymentComplete + 1;
            $initiateIns->save();

            foreach($AllFromTab as $tOr){
                // Deactivate phone Nr verification 
                $pnvRecord = tabVerificationPNumbers::where('tabOrderId',$tOr->id)->first();
                $pnvRecord->status = 0;
                $pnvRecord->save();

                if(!in_array($pnvRecord->phoneNr,$clientsActive)){
                    array_push($clientsActive,$pnvRecord->phoneNr);
                }
                array_push($tabOrdersToR,$pnvRecord->phoneNr.'||'.$tOr->id);

                $tabOrPrice = number_format($tOr->OrderQmimi, 2, '.', '');
                $tabOrPricePerCl = number_format($tabOrPrice / $req->clientsNr, 2, '.', '');
                $tabOrPricePerClFixed = ceil($tabOrPricePerCl / 0.05) * 0.05;
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

                $sasiaPerProd = $tOr->OrderSasia/$req->clientsNr;

                if($saveOrderAll != ''){
                    $saveOrderAll .= "---8---".$tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$tOr->OrderSasia.'/'.$req->clientsNr.'-8-'.$tabOrPricePerClFixed
                    .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
                }else{
                    $saveOrderAll = $tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$tOr->OrderSasia.'/'.$req->clientsNr.'-8-'.$tabOrPricePerClFixed
                    .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
                }
                //---------------------------------------------------------------------------------------

                if($tabOrdersIds == ""){ $tabOrdersIds = $tOr->id; 
                }else{ $tabOrdersIds .= '|||'.$tOr->id; }

                if($tOr->status != 9 ){
                    $totalPay += (float)$tabOrPricePerClFixed;
                }

                if($initiateIns != Null && $initiateIns->nrOfClients == $initiateIns->paymentComplete){
                    $tOr->tabCode = 0;
                    $tOr->status = 1;
                    $tOr->save();
                }
            }

            $newOrder->nrTable = $req->tableNr;
            $newOrder->statusi = 3;
            $newOrder->byId = 0;
            $newOrder->userEmri = "admin";
            $newOrder->userEmail = "admin";
            if($saveOrderAll == ''){$newOrder->porosia = 'empty'; }else{$newOrder->porosia = $saveOrderAll;} //$request->userPorosia;
            if(isset($req->thePayM)){ $newOrder->payM = $req->thePayM; }else{ $newOrder->payM = 'Admin'; }
            
            if(isset($req->bakshoshChf)){
                $newOrder->shuma =number_format($totalPay + number_format((float)$req->bakshoshChf, 2, '.', ''), 2, '.', ''); //$request->Shuma
            }else{
                $newOrder->shuma =number_format($totalPay, 2, '.', ''); //$request->Shuma
            }
            $newOrder->Restaurant = Auth::user()->sFor;
            $newOrder->userPhoneNr = "0770000000";
            if(isset($req->bakshoshChf)){
                $bakshishi =number_format((float)$req->bakshoshChf, 2, '.', '') ;
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
            $newOrder->discReason = Null;
            $newOrder->inCashDiscount = 0.00;
            $newOrder->inPercentageDiscount = 0.00;
            $newOrder->dicsountGcAmnt = $req->GCAmt;
            $newOrder->mwstVal = 0.00;
            $newOrder->servedBy = Auth::user()->id;

            $refIfOrPa = OrdersPassive::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
            $refIfOr = Orders::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
            $nextRefId = 0;
            if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
            $newOrder->refId = $nextRefId;
            $newOrder->save();

            // Register Gift Card use
            if($req->GCId != 0){
                $TheGC = giftCard::find($req->GCId);
                if($TheGC != Null){
                    $TheGC->gcSumInChfUsed = number_format($TheGC->gcSumInChfUsed + $req->GCAmt,2,'.','');
                    if($TheGC->usedInOrdersId != 'empty'){ $TheGC->usedInOrdersId = $TheGC->usedInOrdersId.'|||'.$newOrder->id;
                    }else{ $TheGC->usedInOrdersId = $newOrder->id; }
                    $TheGC->save();
                }
            }
            // ---------------------------------------------------------------

            // Register splitBill Payment Log
            $splitBillPaymentLog = new splitBillLogPayments(); 
            $splitBillPaymentLog->toRes = Auth::user()->sFor; 
            $splitBillPaymentLog->tableNr = $req->tableNr; 
            $splitBillPaymentLog->initiateId = $req->initiateId; 
            $splitBillPaymentLog->tabOrders = $tabOrdersIds; 
            $splitBillPaymentLog->orderId = $newOrder->id;
            $splitBillPaymentLog->tipOrder = number_format((float)$req->bakshoshChf, 2, '.', '');
            $splitBillPaymentLog->GCId = $req->GCId;
            $splitBillPaymentLog->GCAmt = number_format($req->GCAmt,2,'.','');
            $splitBillPaymentLog->payM = $req->thePayM;
            $splitBillPaymentLog->save();
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
                //  if(isset($payTecTrx->xxxxxxxxxxxxxxx)){ $payTecLog->xxxxxxxxxxxxxxx = $payTecTrx->xxxxxxxxxxxxxxx; }
                $payTecLog->save();
            }

            //---------------------------------------------------------------
            
            // waiter LOG
            if($newOrder->porosia != 'empty'){
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
            if(isset($req->bakshoshChf)){
                $newTip = new TipLog;

                $toPay = number_format($totalPay,2,'.','');

                $newTip->shumaPor = number_format($toPay + number_format((float)$req->bakshoshChf, 2, '.', ''), 2, '.', '');
                $newTip->tipPer = 'Empty';
                $newTip->tipTot = number_format((float)$req->bakshoshChf, 2, '.', '');
                $newTip->toRes = Auth::user()->sFor;
                if(Auth::check()){
                    $newTip->klienti = Auth::user()->id;
                }else{
                    $newTip->klienti = 9999999;
                }
                $newTip->save();
            }

            // Gen the next indentifikation number (shifra - per Orders) 
            $orderSh = $this->splitBillgenShifra(Auth::user()->sFor);
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

            if($initiateIns != Null && $initiateIns->nrOfClients == $initiateIns->paymentComplete){
                $tableGet = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$req->tableNr]])->first();
                $tableGet->kaTab = 0;
                $tableGet->save();
            }

            $newOrAlertToDel = newOrdersAdminAlert::where([['toRes',Auth::user()->sFor],['tableNr',$req->tableNr],['statActive','1']])->get();
            if($newOrAlertToDel != NULL){
                foreach($newOrAlertToDel as $newOrAlertToDelOne){
                    $newOrAlertToDelOne->delete();
                }
            }

            if($initiateIns != Null && $initiateIns->nrOfClients == $initiateIns->paymentComplete){
                $theFinalPay = 2;

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
                
            }else if($initiateIns != Null && $initiateIns->paymentComplete == 1){
                $theFinalPay = 1;
            }else{
                $theFinalPay = 0;
            }

            return $newOrder->id.'|||'.$req->crrClNr.'|||'.$theFinalPay.'|||'.$req->tableNr;
        }


    }








    public function splitBillGiftCardValidateTheIdnCode(Request $req){
        $theGC = giftCard::where([['idnShortCode',$req->gcIdnCode],['statActive','1']])->first(); 
        if($theGC == Null){
            return 'gcNotFound';
        }else if($theGC->toRes != Auth::user()->sFor){
            return 'gcNotOfThisRes';
        }else if($theGC->statActive == 0){
            return 'gcIsAllSpend';
        }else if($theGC->onlinePayStat == 0){
            return 'gcIsNotPaid';
        }else{
            $nowDt = Carbon::now();
            $nowDate = explode(' ',$nowDt)[0];
            if( $nowDate > $theGC->expirationDate){
                return 'gcExpired';
            }else{
                $leftAmnt = number_format($theGC->gcSumInChf-$theGC->gcSumInChfUsed,2,'.','');
                return $theGC->id.'|||'.$leftAmnt;
            }
        }
    }


    public function splitBillGiftCardApplyAmount(Request $req){
        $theGC = giftCard::findOrFail($req->gcId);
        if($theGC == Null){
            return 'gcNotFound';
        }else{
            $discReq = number_format($req->gcDiscAmnt,2,'.','');
            $discAvailable = number_format($theGC->gcSumInChf - $theGC->gcSumInChfUsed,2,'.','');
            if($discAvailable < $discReq ){
                return 'gcAmountNotAvailable';
            }else{
                return $discReq;
            }
        }
    }

    public function splitBillGiftCardApplyAmountMax(Request $req){
        $theGC = giftCard::findOrFail($req->gcId);
        if($theGC == Null){
            return 'gcNotFound';
        }else{
            $discReq = number_format($req->toPayAmnt,2,'.','');
            $maxInGC = number_format($theGC->gcSumInChf - $theGC->gcSumInChfUsed,2,'.','');
            if($maxInGC >= $discReq ){
                // discount all
                return $discReq;
            }else{
                // discount some - max 
                return $maxInGC;
            }
        }
    }

    function splitBillValidateGiftCardOnScanToApply(Request $req){
        if(str_contains($req->qrCodeD,'|||')){
            $qrCodeD2D = explode('|||',$req->qrCodeD);

            $theGC = giftCard::where([['idnShortCode',$qrCodeD2D[0]],['gcHash',$qrCodeD2D[1]]])->first();
            if($theGC != Null){
                return $theGC->idnShortCode;
            }else{
                $theGCToSell = giftCardToSell::where([['idnShortCode',$qrCodeD2D[0]],['hashVal',$qrCodeD2D[1]]])->first();
                if($theGCToSell != Null){
                    return 'gcNotSoldYet';
                }else{
                    return 'invalideQRCode';
                }
            }
        }else{
            return 'invalideQRCode';
        }
    }












    public function splitBillPayAufRechnung(Request $req){
        $newOrder = new Orders;

        $tabCode = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$req->splitBillTableNrRechnung]])->first()->kaTab;
        $saveOrderAll = '';
        $totalPay = 0 ;
        $AllFromTab = TabOrder::where([['tableNr',$req->splitBillTableNrRechnung],['toRes',Auth::user()->sFor],['tabCode',$tabCode],['status','!=','9']])->get();

        $clientsActive = array();
        $tabOrdersToR = array();

        $tabOrdersIds = "";

        $initiateIns = splitBillLogInitiate::find($req->splitBillInitiateIdRechnung);
        $initiateIns->paymentComplete = $initiateIns->paymentComplete + 1;
        $initiateIns->save();

        foreach($AllFromTab as $tOr){
            // Deactivate phone Nr verification 
            $pnvRecord = tabVerificationPNumbers::where('tabOrderId',$tOr->id)->first();
            $pnvRecord->status = 0;
            $pnvRecord->save();

            if(!in_array($pnvRecord->phoneNr,$clientsActive)){
                array_push($clientsActive,$pnvRecord->phoneNr);
            }
            array_push($tabOrdersToR,$pnvRecord->phoneNr.'||'.$tOr->id);

            $tabOrPrice = number_format($tOr->OrderQmimi, 2, '.', '');
            $tabOrPricePerCl = number_format($tabOrPrice / $req->splitBillClientsNrRechnung, 2, '.', '');
            $tabOrPricePerClFixed = ceil($tabOrPricePerCl / 0.05) * 0.05;
            //---------------------------------------------------------------------------------------
            // Pergaditja e porosive per regjistrim 
            if($tOr->OrderType != null){ $regType = explode('||',$tOr->OrderType)[0]; }else{  $regType = ''; }
            if($req->splitBillTableNrRechnung == 500){
                $theP = Produktet::find($tOr->prodId);
                if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
            }else{
                $theP = Produktet::find($tOr->prodId);
                if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
            }

            $sasiaPerProd = $tOr->OrderSasia/$req->splitBillClientsNrRechnung;

            if($saveOrderAll != ''){
                $saveOrderAll .= "---8---".$tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$tOr->OrderSasia.'/'.$req->splitBillClientsNrRechnung.'-8-'.$tabOrPricePerClFixed
                .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
            }else{
                $saveOrderAll = $tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$tOr->OrderSasia.'/'.$req->splitBillClientsNrRechnung.'-8-'.$tabOrPricePerClFixed
                .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
            }
            //---------------------------------------------------------------------------------------

            if($tabOrdersIds == ""){ $tabOrdersIds = $tOr->id; 
            }else{ $tabOrdersIds .= '|||'.$tOr->id; }

            if($tOr->status != 9 ){
                $totalPay += (float)$tabOrPricePerClFixed;
            }

            if($initiateIns != Null && $initiateIns->nrOfClients == $initiateIns->paymentComplete){
                $tOr->tabCode = 0;
                $tOr->status = 1;
                $tOr->save();
            }
        }
        $newOrder->nrTable = $req->splitBillTableNrRechnung;


        $newOrder->statusi = 3;
        $newOrder->byId = 0;
        $newOrder->userEmri = "admin";
        $newOrder->userEmail = "admin";
        if($saveOrderAll == ''){$newOrder->porosia = 'empty'; }else{$newOrder->porosia = $saveOrderAll;} //$request->userPorosia;
        $newOrder->payM = 'Rechnung';
        if(isset($req->splitBilltipForOrderCloseRechnung)){
            $newOrder->shuma =number_format($totalPay + number_format((float)$req->splitBilltipForOrderCloseRechnung, 2, '.', ''), 2, '.', ''); //$request->Shuma
        }else{
            $newOrder->shuma =number_format($totalPay, 2, '.', ''); //$request->Shuma
        }
        $newOrder->Restaurant = Auth::user()->sFor;
        $newOrder->userPhoneNr = "0770000000";
        if(isset($req->splitBilltipForOrderCloseRechnung)){
            $bakshishi =number_format((float)$req->splitBilltipForOrderCloseRechnung, 2, '.', '') ;
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
        $newOrder->discReason = Null;
        $newOrder->inCashDiscount = 0.00;
        $newOrder->inPercentageDiscount = 0.00;
        $newOrder->dicsountGcAmnt = $req->splitBillGCAmntARechnungAllVal;
        $newOrder->mwstVal = 0.00;
        $newOrder->servedBy = Auth::user()->id;
        
        $refIfOrPa = OrdersPassive::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->save();

        // Register Gift Card use
        if($req->splitBillGCIdARechnungAllVal != 0){
            $TheGC = giftCard::find($req->splitBillGCIdARechnungAllVal);
            if($TheGC != Null){
                $TheGC->gcSumInChfUsed = number_format($TheGC->gcSumInChfUsed + $req->splitBillGCAmntARechnungAllVal,2,'.','');
                if($TheGC->usedInOrdersId != 'empty'){ $TheGC->usedInOrdersId = $TheGC->usedInOrdersId.'|||'.$newOrder->id;
                }else{ $TheGC->usedInOrdersId = $newOrder->id; }
                $TheGC->save();
            }
        }

        // Register splitBill Payment Log
            $splitBillPaymentLog = new splitBillLogPayments(); 
            $splitBillPaymentLog->toRes = Auth::user()->sFor; 
            $splitBillPaymentLog->tableNr = $req->splitBillTableNrRechnung; 
            $splitBillPaymentLog->initiateId = $req->splitBillInitiateIdRechnung; 
            $splitBillPaymentLog->tabOrders = $tabOrdersIds; 
            $splitBillPaymentLog->orderId = $newOrder->id;
            $splitBillPaymentLog->tipOrder = number_format((float)$req->splitBilltipForOrderCloseRechnung, 2, '.', '');
            $splitBillPaymentLog->GCId = $req->splitBillGCIdARechnungAllVal;
            $splitBillPaymentLog->GCAmt = number_format($req->splitBillGCAmntARechnungAllVal,2,'.','');
            $splitBillPaymentLog->payM = 'Auf rechnung';
            $splitBillPaymentLog->save();
        // ---------------------------------------------------------------

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
        //  ------------------------------------------------------------

        // Code per tip
        if(isset($req->splitBilltipForOrderCloseRechnung)){
            $newTip = new TipLog;
            
            $toPay = number_format($totalPay,2,'.','');
            $newTip->shumaPor = number_format($toPay + number_format((float)$req->splitBilltipForOrderCloseRechnung, 2, '.', ''), 2, '.', '');
            $newTip->tipPer = 'Empty';
            $newTip->tipTot = number_format((float)$req->splitBilltipForOrderCloseRechnung, 2, '.', '');
            $newTip->toRes = Auth::user()->sFor;
            if(Auth::check()){
                $newTip->klienti = Auth::user()->id;
            }else{
                $newTip->klienti = 9999999;
            }
            $newTip->save();
        }
        //------------------------------------------------

        // Gen the next indentifikation number (shifra - per Orders) 
            $orderSh = $this->splitBillgenShifra(Auth::user()->sFor);
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

        if($initiateIns != Null && $initiateIns->nrOfClients == $initiateIns->paymentComplete){
            $tableGet = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$req->splitBillTableNrRechnung]])->first();
            $tableGet->kaTab = 0;
            $tableGet->save();
        }

        if($req->splitBillusedAndExistingClientAll == 0){
            $clDtRechnungFirmaInp = $req->splitBillRechnungFirmaInp;
            $clDtRechnungTelInp = $req->splitBillRechnungTelInp;
            $clDtRechnungNameInp = $req->splitBillRechnungNameInp;
            $clDtRechnungVornameInp = $req->splitBillRechnungVornameInp;
            $clDtRechnungStrNrInp = $req->splitBillRechnungStrNrInp;
            $clDtRechnungPlzOrtInp = $req->splitBillRechnungPlzOrtInp;
            $clDtRechnungLandInp = $req->splitBillRechnungLandInp;
            $clDtRechnungEmailInp = $req->splitBillRechnungEmailInp;
            $clDtRechnungSignature = 'empty';
            // if($req->saveClForARechnungAllVal == 1){
            //     $dtNow = Carbon::now();
            //     if($dtNow->month == 12){ $setYear = $dtNow->year + 1; $setmonth = 1;
            //     }else{ $setYear = $dtNow->year; $setmonth = $dtNow->month + 1; }
            //     $dateToPay = new Carbon($setYear.'-'.$setmonth.'-10 23:59:59');
            //     $clDtRechnungDaysToPay = $dateToPay->diff($dtNow)->days;
            // }else{
                $clDtRechnungDaysToPay = $req->splitBillRechnungDaysToPayInp;
            // }
        }else{
            $clData = rechnungClient::find($req->splitBillusedAndExistingClientIdAll);

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
            if($req->splitBillpayAllRechnungComment != ''){ $rechExInfo->theComm = $req->splitBillpayAllRechnungComment;
            }else if($req->splitBillpayAllRechnungCommentClient != ''){ $rechExInfo->theComm = $req->splitBillpayAllRechnungCommentClient; 
            }else{ $rechExInfo->theComm = "empty"; }
            $rechExInfo->statusConf = 0;

            if($clDtRechnungSignature == 'empty'){
                $folderPath = public_path('storage/rechnungPaySignatures/');       
                $image_parts = explode(";base64,", $req->splitBillsigned);             
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
         if($req->splitBillsaveClForARechnungAllVal == 1 && $req->splitBillusedAndExistingClientAll == 0){
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
        }else if($req->splitBillusedAndExistingClientAll != 0){
            $newBillToCl = new rechnungClientToBills();
            $newBillToCl->toRes = Auth::user()->sFor;
            $newBillToCl->billId = $rechExInfo->id;
            $newBillToCl->orderId = $newOrder->id;
            $newBillToCl->clientId = $req->splitBillusedAndExistingClientIdAll;
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
        $name = substr(implode($word), 0, 25).'OrId'.$theOr->id;
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
        // if($req->splitBillsaveClForARechnungAllVal == 1 || $req->splitBillusedAndExistingClientAll != 0){
        if(1 == 1){
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
                $message->to($to_email, $to_name)->subject('Vertrag');
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


        if($initiateIns != Null && $initiateIns->nrOfClients == $initiateIns->paymentComplete){
            $theFinalPay = 2;



            // Send Notifications and email for the ADMIN
            if($req->splitBillsaveClForARechnungAllVal == 1 || $req->splitBillusedAndExistingClientAll != 0){
                foreach(User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $user){
                    if($user->id != Auth::user()->id){
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $req->splitBillTableNrRechnung
                        ];
                        $user->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                    $to_name = $user->name;
                    $to_email = $user->email;
                    $fromResemri = Restorant::find($user->sFor)->emri;
                    $reId = $newOrder->id;
                    $data = array('sendName'=>$to_name, "sendEmail" => $to_email, "fromResName" => $fromResemri, "daysToPay" => $clDtRechnungDaysToPay, "ReId" => $newOrder->id);
                    
                    Mail::send('adminInvoice', $data , function($message)use ($to_email, $to_name ,$reId ,$fromResemri ,$pdf2s){
                        $message->from('noreply@qrorpa.ch','Qrorpa');
                        $message->to($to_email, $to_name);
                        $message->subject('Rechnung für einen Kunden von '.$fromResemri);
                        $message->attachData($pdf2s->output(), 'Rechnungsnummer:'.$reId.'.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                    });
                }
            }else{
                foreach(User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $user){
                    if($user->id != auth()->user()->id){
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $req->splitBillTableNrRechnung
                        ];
                        $user->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                    $to_name = $user->name;
                    $to_email = $user->email;
                    $fromResemri = Restorant::find($user->sFor)->emri;
                    $reId = $newOrder->id;
                    $data = array('sendName'=>$to_name, "sendEmail" => $to_email, "fromResName" => $fromResemri, "daysToPay" => $clDtRechnungDaysToPay, "ReId" => $newOrder->id);
                    Mail::send('emails.rechnungPaymentForAdm', $data , function($message)use ($to_email, $to_name ,$reId ,$fromResemri ,$pdf){
                        $message->from('noreply@qrorpa.ch','Qrorpa');
                        $message->to($to_email, $to_name);
                        $message->subject('Rechnung für einen Kunden von '.$fromResemri);
                        $message->attachData($pdf->output(), 'Rechnungsnummer:'.$reId.'.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                    });
                }
            }

            // Send Notifications and email for the WAITERS
            if($req->splitBillsaveClForARechnungAllVal == 1 || $req->splitBillusedAndExistingClientAll != 0){
                foreach(User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $oneWaiter){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->splitBillTableNrRechnung]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $req->splitBillTableNrRechnung
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                    $to_name = $oneWaiter->name;
                    $to_email = $oneWaiter->email;
                    $fromResemri = Restorant::find($oneWaiter->sFor)->emri;
                    $reId = $newOrder->id;
                    $data = array('sendName'=>$to_name, "sendEmail" => $to_email, "fromResName" => $fromResemri, "daysToPay" => $clDtRechnungDaysToPay, "ReId" => $newOrder->id);
                    Mail::send('adminInvoice', $data , function($message)use ($to_email, $to_name ,$reId ,$fromResemri ,$pdf2s){
                        $message->from('noreply@qrorpa.ch','Qrorpa');
                        $message->to($to_email, $to_name);
                        $message->subject('Rechnung für einen Kunden von '.$fromResemri);
                        $message->attachData($pdf2s->output(), 'Rechnungsnummer:'.$reId.'.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                    });
                }
            }else{
                foreach(User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $oneWaiter){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->splitBillTableNrRechnung]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $req->splitBillTableNrRechnung
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                    $to_name = $oneWaiter->name;
                    $to_email = $oneWaiter->email;
                    $fromResemri = Restorant::find($oneWaiter->sFor)->emri;
                    $reId = $newOrder->id;
                    $data = array('sendName'=>$to_name, "sendEmail" => $to_email, "fromResName" => $fromResemri, "daysToPay" => $clDtRechnungDaysToPay, "ReId" => $newOrder->id);
                    Mail::send('emails.rechnungPaymentForAdm', $data , function($message)use ($to_email, $to_name ,$reId ,$fromResemri ,$pdf){
                        $message->from('noreply@qrorpa.ch','Qrorpa');
                        $message->to($to_email, $to_name);
                        $message->subject('Rechnung für einen Kunden von '.$fromResemri);
                        $message->attachData($pdf->output(), 'Rechnungsnummer:'.$reId.'.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                    });
                }
            }




            foreach(User::where([['sFor',Auth::user()->sFor],['role','54']])->get() as $oneCook){
                $details = [
                    'id' => 0,
                    'type' => 'AdminUpdateOrdersP',
                    'tableNr' => $req->splitBillTableNrRechnung
                ];
                $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
            }

            $newOrAlertToDel = newOrdersAdminAlert::where([['toRes',Auth::user()->sFor],['tableNr',$req->splitBillTableNrRechnung],['statActive','1']])->get();
            if($newOrAlertToDel != NULL){
                foreach($newOrAlertToDel as $newOrAlertToDelOne){
                    $newOrAlertToDelOne->delete();
                }
            }

        }else if($initiateIns != Null && $initiateIns->paymentComplete == 1){
            $theFinalPay = 1;
        }else{
            $theFinalPay = 0;
        }


        

        if(Auth::user()->role == 55){
            return redirect()->route('admWoMng.indexAdmMngPageWaiter' , ['orId' => $newOrder->id, 'orExId' =>  $rechExInfo->id, 'splitBillIn' =>  $req->splitBillInitiateIdRechnung, 'splitBillPayLogId' => $splitBillPaymentLog->id, 'splitBillTheFinalPay' => $theFinalPay]);
        }else{
            return redirect()->route('dash.index' , ['orId' => $newOrder->id, 'orExId' =>  $rechExInfo->id, 'splitBillIn' =>  $req->splitBillInitiateIdRechnung, 'splitBillPayLogId' => $splitBillPaymentLog->id, 'splitBillTheFinalPay' => $theFinalPay]);
        }



    }









    public function splitBillCallUnfinishedBill(Request $req){
        $sbInitiate = splitBillLogInitiate::where('workerId',Auth::user()->id)->orderByDesc('id')->first();
        if($sbInitiate == Null){
            return 'InstanceNotFound';
        }else if($sbInitiate->cancelStatus == 1){
            return 'InstanceIsCanceled'; 
        }else{
            if($sbInitiate->nrOfClients == $sbInitiate->paymentComplete){
                return 'LastPaymentComplete';
            }else{

                $theTable = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$sbInitiate->tableNr]])->first();
                if($theTable != Null){
                    // ceil($number / 0.05) * 0.05;
                    $totToPayClient = number_format(0, 2, '.', '');
                    $originalPrice = number_format(0, 2, '.', '');
                    $toPayPrice = number_format(0, 2, '.', '');
                    foreach(TabOrder::where([['toRes',Auth::user()->sFor],['tableNr',$sbInitiate->tableNr],['tabCode',$theTable->kaTab]])->get() as $tabOrderOne){

                        $tabOrPrice = number_format($tabOrderOne->OrderQmimi, 2, '.', '');
                        $tabOrPricePerCl = number_format($tabOrPrice / $sbInitiate->nrOfClients, 2, '.', '');
                        $tabOrPricePerClFixed = ceil($tabOrPricePerCl / 0.05) * 0.05;

                        $totToPayClient += number_format($tabOrPricePerClFixed, 2, '.', '');

                        $originalPrice += number_format($tabOrPrice, 2, '.', '');
                    }
                    $toPayPrice = number_format($totToPayClient * $sbInitiate->nrOfClients, 2, '.', '');

                    $rechReturnPayM = '';
                    $rechReturntips = '';
                    $rechReturnGCId = '';
                    $rechReturnGCAmt = '';
                    foreach(splitBillLogPayments::where('initiateId',$sbInitiate->id)->get() as $splitBillPayOne){
                        if($rechReturnPayM == ''){ $rechReturnPayM = $splitBillPayOne->payM;
                        }else{ $rechReturnPayM .= '--88--'.$splitBillPayOne->payM; }
                
                        if($splitBillPayOne->tipOrder == 0){
                            if($rechReturntips == ''){ $rechReturntips = '0.00';
                            }else{ $rechReturntips .= '--88--0.00'; }
                        }else{
                            if($rechReturntips == ''){ $rechReturntips = $splitBillPayOne->tipOrder;
                            }else{ $rechReturntips .= '--88--'.$splitBillPayOne->tipOrder; }
                        }  

                        if($splitBillPayOne->GCId == 0){
                            if($rechReturnGCId == ''){ $rechReturnGCId = '0.00';
                            }else{ $rechReturnGCId .= '--88--0.00'; }
                        }else{
                            if($rechReturnGCId == ''){ $rechReturnGCId = $splitBillPayOne->GCId;
                            }else{ $rechReturnGCId .= '--88--'.$splitBillPayOne->GCId; }
                        }
                        
                        if($splitBillPayOne->GCAmt == 0){
                            if($rechReturnGCAmt == ''){ $rechReturnGCAmt = '0.00';
                            }else{ $rechReturnGCAmt .= '--88--0.00'; }
                        }else{
                            if($rechReturnGCAmt == ''){ $rechReturnGCAmt = $splitBillPayOne->GCAmt;
                            }else{ $rechReturnGCAmt .= '--88--'.$splitBillPayOne->GCAmt; }
                        }
                    }
                    if($sbInitiate != Null && $sbInitiate->nrOfClients == $sbInitiate->paymentComplete){
                        $theFinalPay = 2;
                    }else if($sbInitiate != Null && $sbInitiate->paymentComplete == 0){
                        $theFinalPay = 1;
                    }else{
                        $theFinalPay = 0;
                    }

                    return $totToPayClient.'|||'.$sbInitiate->tableNr.'|||'.$sbInitiate->nrOfClients.'|||'.$originalPrice.'|||'.$toPayPrice.'|||'.$sbInitiate->id.'|||'.$rechReturnPayM.'|||'.$rechReturntips.'|||'.$sbInitiate->pricePerClient.'|||'.$theFinalPay.'|||'.$rechReturnGCId.'|||'.$rechReturnGCAmt;
                }else{
                    return 'invalideTable';
                }
            }
        }

    }
    
}
