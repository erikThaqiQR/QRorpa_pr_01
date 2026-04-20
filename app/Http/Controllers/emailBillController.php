<?php

namespace App\Http\Controllers;

use PDF;
use Cart;
use Excel;
use QRCode;
use App\User;
use DateTime;
use App\Piket;
use App\ekstra;
use App\Orders;
use App\TipLog;
use App\giftCard;
use App\kategori;
use App\TabOrder;
use App\LlojetPro;
use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use App\resdemoalfa;
use App\TableQrcode;
use App\notifyClient;
use App\exportToExcel;
use App\OrdersPassive;
use App\ghostCartInUse;
use App\rechnungClient;
use App\RecomendetProd;
use App\waiterActivityLog;
use App\tableChngReqsAdmin;
use App\emailReceiptFromAdm;
use App\Exports\excelExport;
use App\newOrdersAdminAlert;
use Illuminate\Http\Request;
use SpryngApiHttpPhp\Client;
use App\Events\addToCartAdmin;
use App\rechnungClientToBills;
use App\tablesAccessToWaiters;
use App\Events\ActiveAdminPanel;
use App\tabVerificationPNumbers;
use App\Events\removePaidProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;
use App\giftCardRechnungPay;

class emailBillController extends Controller{
  
    public function sendReminderEmailToCl(Request $req){
        // emReId

        $theExI = emailReceiptFromAdm::find($req->emReId);
        if($theExI != NULL){
            $item = OrdersPassive::find($theExI->forOrder);
            if( $item == Null){ $item = Orders::find($theExI->forOrder); }
            $nrOfOrders = count(explode('---8---', $item->porosia))-1;
            $totExtra = 0;
            foreach(explode('---8---', $item->porosia) as $onOr){
                $or2D = explode('-8-',$onOr);
                if($or2D[2] != 'empty' && $or2D[2] != ''){
                    if(str_contains($or2D[2], '--0--')){
                        $nrOfExt = count(explode('--0--',$or2D[2]));
                        $totExtra = $totExtra + $nrOfExt;
                    }else{
                        $totExtra++;
                    }
                }
            }

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

            $to_name = $theExI->exInfoName.' '.$theExI->exInfoLastname;
            $to_email = $theExI->exInfoEmail;
            $fromRes = Restorant::find($theExI->forRes);
            $reId = $theExI->forOrder;

            $stD2d = explode('-',explode(' ',$theExI->created_at)[0]);
            $endD = date('d-m-Y', mktime(0, 0, 0, $stD2d[1], $stD2d[2] + $theExI->exInfoDaysToPay, $stD2d[0]));

            $data = array('sendName'=>$to_name, "sendEmail" => $to_email, "fromResName" => $fromRes->emri, "daysToPay" => $req->RechnungDaysToPayInpSel, "ReId" => $reId, "endD" => $endD);

            Mail::send('emails.reminderForRechnung', $data , function($message)use ($to_email, $to_name ,$reId , $pdf2){
                $message->from('noreply@qrorpa.ch','Qrorpa');
                $message->to($to_email, $to_name);
                $message->subject('Mahnung zur Zahlung der Rechnungsnummer '.$reId.' QRorpa');
                $message->attachData($pdf2->output(), 'Rechnungsnummer:'.$reId.'.pdf', [
                    'mime' => 'application/pdf',
                ]);
            });
        }else{

        } 
    }

    public function rechnungGetBilMngPage(Request $req){
        $theExI = emailReceiptFromAdm::find($req->emBiId);
        $item = OrdersPassive::find($theExI->forOrder);
        if( $item == Null){ $item = Orders::find($theExI->forOrder); }

        $nrOfOrders = count(explode('---8---', $item->porosia))-1;
        $totExtra = 0;
        foreach(explode('---8---', $item->porosia) as $onOr){
            $or2D = explode('-8-',$onOr);
            if($or2D[2] != 'empty' && $or2D[2] != ''){
                if(str_contains($or2D[2], '--0--')){
                    $nrOfExt = count(explode('--0--',$or2D[2]));
                    $totExtra = $totExtra + $nrOfExt;
                }else{
                    $totExtra++;
                }
            }
        }

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
    }
















    public function rechnungConfirmPayment(Request $req){
        if($req->type == 'order'){
            $theExI = emailReceiptFromAdm::find($req->emBiId);

            if($theExI != NULL){
                if($theExI->statusConf == 0){
                    $theExI->statusConf = 9;
                    $theExI->statusConfBy = Auth::user()->id;
                    $theExI->save();
                }else{
                    "alreadyConf";
                }
            }else{
                "notFound";
            }
        } else {
            $theGC = giftCardRechnungPay::find($req->emBiId);

            if($theGC != NULL){
                if($theGC->payment_status === 1){
                    return "alreadyConf";
                } else {
                    $theGC->status_confirmed = 1;
                    $theGC->status_confirmed_by = Auth::user()->id;
                    $theGC->save();
                }
            } else {
                return "notFound";
            }
        }
      
    }

    public function getDataClDayToPay(Request $req){
        $theCl = rechnungClient::find($req->clientId);
        if($theCl != Null){
            return $theCl->daysToPay.'||'.$theCl->name.'||'.$theCl->lastname;
        }else{
            return 'clNotFound';
        }
    }
    public function setDaysClDayToPay(Request $req){
        $theCl = rechnungClient::find($req->clientId);
        $theCl->daysToPay = $req->newDa;
        $theCl->save();
    }





























    public function payAlladdRechnungPay(Request $req){
        // payAllTableNrRechnung
        // resId: resId,
        // _token: '{{csrf_token()}}'

        $newOrder = new Orders;

        $tabCode = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$req->payAllTableNrRechnung]])->first()->kaTab;
        $saveOrderAll = '';
        $totalPay = 0 ;
        $AllFromTab = TabOrder::where([['tableNr',$req->payAllTableNrRechnung],['toRes',Auth::user()->sFor],['tabCode',$tabCode],['status','!=','9']])->get();

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

        if($req->tvshAmProdsPerventageRechnung == 2.60){
            $newOrder->nrTable = '500';
        }else if($req->tvshAmProdsPerventageRechnung == 8.10){
            $newOrder->nrTable = $req->payAllTableNrRechnung;
        }
        $newOrder->statusi = 3;
        $newOrder->byId = 0;
        $newOrder->userEmri = "admin";
        $newOrder->userEmail = "admin";
        if($saveOrderAll == ''){$newOrder->porosia = 'empty'; }else{$newOrder->porosia = $saveOrderAll;} //$request->userPorosia;
        $newOrder->resComment = $req->payAllRechnungResComment;
        $newOrder->payM = 'Rechnung';
        if(isset($req->tipForOrderCloseRechnung)){
            $newOrder->shuma =number_format($totalPay + number_format((float)$req->tipForOrderCloseRechnung, 2, '.', ''), 2, '.', ''); //$request->Shuma
        }else{
            $newOrder->shuma =number_format($totalPay, 2, '.', ''); //$request->Shuma
        }
        $newOrder->Restaurant = Auth::user()->sFor;
        $newOrder->userPhoneNr = "0770000000";
        if(isset($req->tipForOrderCloseRechnung)){
            $bakshishi =number_format((float)$req->tipForOrderCloseRechnung, 2, '.', '') ;
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
        $newOrder->mwstVal = $req->tvshAmProdsPerventageRechnung;
        $newOrder->servedBy = Auth::user()->id;
        
        $refIfOrPa = OrdersPassive::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->save();

        // Register Gift Card use
        if($req->payAllRechnungGiftCardId != 0){
            $TheGC = giftCard::find($req->payAllRechnungGiftCardId);
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
        //  ------------------------------------------------------------

        // Code per tip
        if(isset($req->tipForOrderCloseRechnung)){
            $newTip = new TipLog;

            if($req->cashDiscountInpRechnungSel > 0){
                $skontoCHF = number_format($req->cashDiscountInpRechnungSel,2,'.','');
                $toPay = number_format($totalPay - $skontoCHF,2,'.','');
              }else if($req->tipForOrderCloseRechnung > 0){
                $skontoCHF = number_format($totalPay*($req->tipForOrderCloseRechnung/100),2,'.','');
                $toPay = number_format($totalPay - $skontoCHF,2,'.','');
              }else{
                  $toPay = number_format($totalPay,2,'.','');
              } 

            $newTip->shumaPor = number_format($toPay + number_format((float)$req->tipForOrderCloseRechnung, 2, '.', ''), 2, '.', '');
            $newTip->tipPer = 'Empty';
            $newTip->tipTot = number_format((float)$req->tipForOrderCloseRechnung, 2, '.', '');
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

        $tableGet = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$req->payAllTableNrRechnung]])->first();
        $tableGet->kaTab = 0;
        $tableGet->save();


        foreach($clientsActive as $clActiveOne){
            if(str_contains($clActiveOne,'|')){
                // $request->t $request->Res
                $clPhoneNr2D = explode('|',$clActiveOne);
                $findGCActive = ghostCartInUse::where([['toRes',Auth::user()->sFor],['tableNr',$req->payAllTableNrRechnung],['indNr2',$clPhoneNr2D[1]],['status','0']])->first();
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
                $newNotifyClient->toRes = Auth::user()->sFor;
                $newNotifyClient->tableNr = $req->payAllTableNrRechnung;
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
        // if($req->saveClForARechnungAllVal == 1 || $req->usedAndExistingClientAll != 0){
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
            
            $email2D = explode('.',$clDtRechnungEmailInp);
            $emailLast = end($email2D);
            if($emailLast != 'swiss'){
                Mail::send('adminInvoice', $data, function($message) use ($to_name, $to_email, $pdf2s, $newOrder, $koha2D) {
                    $message->to($to_email, $to_name)->subject('Vertrag');
                    $message->from('noreply@qrorpa.ch','Qrorpa');
                    $message->attachData($pdf2s->output(), 'Vertrag-'.$newOrder->id.'-QRorpa-'.$koha2D[2].'_'.$koha2D[1].'_'.$koha2D[0].'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
                });
            }

        }else{
            $email2D = explode('.',$clDtRechnungEmailInp);
            $emailLast = end($email2D);
            if($emailLast != 'swiss'){
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
        }

        // ---------------------------------



        
        // Send Notifications and email for the ADMIN
        if($req->saveClForARechnungAllVal == 1 || $req->usedAndExistingClientAll != 0){
            foreach(User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $user){
                if($user->id != Auth::user()->id){
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $req->payAllTableNrRechnung
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }

                $email2D = explode('.',$clDtRechnungEmailInp);
                $emailLast = end($email2D);
                if($emailLast != 'swiss'){
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
            }
        }else{
            foreach(User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $req->payAllTableNrRechnung
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }

                $email2D = explode('.',$clDtRechnungEmailInp);
                $emailLast = end($email2D);
                if($emailLast != 'swiss'){
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
        }

        // Send Notifications and email for the WAITERS
        if($req->saveClForARechnungAllVal == 1 || $req->usedAndExistingClientAll != 0){
            foreach(User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->payAllTableNrRechnung]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $req->payAllTableNrRechnung
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
                $email2D = explode('.',$clDtRechnungEmailInp);
                $emailLast = end($email2D);
                if($emailLast != 'swiss'){
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
            }
        }else{
            foreach(User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->payAllTableNrRechnung]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $req->payAllTableNrRechnung
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }

                $email2D = explode('.',$clDtRechnungEmailInp);
                $emailLast = end($email2D);
                if($emailLast != 'swiss'){
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
        }




        foreach(User::where([['sFor',Auth::user()->sFor],['role','54']])->get() as $oneCook){
            $details = [
                'id' => 0,
                'type' => 'AdminUpdateOrdersP',
                'tableNr' => $req->payAllTableNrRechnung
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }

        $newOrAlertToDel = newOrdersAdminAlert::where([['toRes',Auth::user()->sFor],['tableNr',$req->payAllTableNrRechnung],['statActive','1']])->get();
        if($newOrAlertToDel != NULL){
            foreach($newOrAlertToDel as $newOrAlertToDelOne){
                $newOrAlertToDelOne->delete();
            }
        }

        if(Auth::user()->role == 55){
            return redirect()->route('admWoMng.indexAdmMngPageWaiter' , ['orId' => $newOrder->id, 'orExId' =>  $rechExInfo->id]);
        }else{
            return redirect()->route('dash.index' , ['orId' => $newOrder->id, 'orExId' =>  $rechExInfo->id]);
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
















































    

    public function paySeladdRechnungPay(Request $req){
        // tableNr: tNr,
        // resId: resId,
        // selProds : $('#closeOrSelected'+tNr).val(),
        // _token: '{{csrf_token()}}'

        $returnPrevTableNr = $req->paySelTableNrRechnung;

        $newOrder = new Orders;

        $tabCode = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$req->paySelTableNrRechnung]])->first()->kaTab;
        $saveOrderAll = '';
        $totalPay = 0 ;
        $allSelOr = array();
        foreach(explode('||',$req->paySelSelectedProdsRechnung) as $prodsToPay){
            $prodsToPay2D = explode('-8-',$prodsToPay);
            array_push($allSelOr,$prodsToPay2D[0]);
        }
        $AllFromTab = TabOrder::where([['tableNr',$req->paySelTableNrRechnung],['toRes',Auth::user()->sFor],['status','!=','9'],['tabCode',$tabCode]])->whereIn('id',$allSelOr)->get();

        $clientsActive = array();
        $tabOrdersToR = array();

        foreach($AllFromTab as $tOr){
            $sasiaSelected = 0;
            $qmimiOfSelected = number_format(0, 2, '.', '');
            $tabOrIdSelected = 0;
            foreach(explode('||',$req->paySelSelectedProdsRechnung) as $prodsToPay){
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
            if($tOr->status != 9 ){
                $totalPay += number_format($qmimiOfSelected, 2, '.', '');;
            }
            $tabOrderSold->tabCode = 0;
            $tabOrderSold->status = 1;
            $tabOrderSold->save();
        }

   

        if($req->tvshAmProdsPerventageRechnungSel == 2.60){
            $newOrder->nrTable = '500';
        }else if($req->tvshAmProdsPerventageRechnungSel == 8.10){
            $newOrder->nrTable = $req->paySelTableNrRechnung;
        }
        $newOrder->statusi = 3;
        $newOrder->byId = 0;
        $newOrder->userEmri = "admin";
        $newOrder->userEmail = "admin";
        if($saveOrderAll == ''){$newOrder->porosia = 'empty'; }else{$newOrder->porosia = $saveOrderAll;} //$request->userPorosia;
        $newOrder->resComment = $req->payAllRechnungResCommentSel;
        $newOrder->payM = 'Rechnung';
        if(isset($req->tipForOrderCloseRechnungSel)){
            $newOrder->shuma =number_format($totalPay + number_format((float)$req->tipForOrderCloseRechnungSel, 2, '.', ''), 2, '.', ''); //$request->Shuma
        }else{
            $newOrder->shuma =number_format($totalPay, 2, '.', ''); //$request->Shuma
        }
        $newOrder->Restaurant = Auth::user()->sFor;
        $newOrder->userPhoneNr = "0770000000";
        if(isset($req->tipForOrderCloseRechnungSel)){
            $bakshishi =number_format((float)$req->tipForOrderCloseRechnungSel, 2, '.', '') ;
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
        $newOrder->discReason = $req->discReasonRechnungSel;
        $newOrder->inCashDiscount = $req->cashDiscountInpRechnungSel;
        $newOrder->inPercentageDiscount = $req->percentageDiscountInpRechnungSel;
        $newOrder->dicsountGcAmnt = $req->payAllRechnungGiftCardAmountSel;
        $newOrder->mwstVal = $req->tvshAmProdsPerventageRechnungSel;
        $newOrder->servedBy = Auth::user()->id;
        
        $refIfOrPa = OrdersPassive::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',Auth::user()->sFor)->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->save();

        // Register Gift Card use
        if($req->payAllRechnungGiftCardIdSel != 0){
            $TheGC = giftCard::find($req->payAllRechnungGiftCardIdSel);
            if($TheGC != Null){
                $TheGC->gcSumInChfUsed = number_format($TheGC->gcSumInChfUsed + $req->payAllRechnungGiftCardAmountSel,2,'.','');
                if($TheGC->usedInOrdersId != 'empty'){ $TheGC->usedInOrdersId = $TheGC->usedInOrdersId.'|||'.$newOrder->id;
                }else{ $TheGC->usedInOrdersId = $newOrder->id; }
                $TheGC->save();
            }
        }

        // waiter LOG
        if(Auth::User()->role == 55){
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
        if(isset($req->tipForOrderCloseRechnungSel)){
            $newTip = new TipLog;

            if($req->cashDiscountInpRechnungSel > 0){
                $skontoCHF = number_format($req->cashDiscountInpRechnungSel,2,'.','');
                $toPay = number_format($totalPay - $skontoCHF,2,'.','');
              }else if($req->percentageDiscountInpRechnungSel > 0){
                  $skontoCHF = number_format($totalPay*($req->percentageDiscountInpRechnungSel/100),2,'.','');
                  $toPay = number_format($totalPay - $skontoCHF,2,'.','');
              }else{
                  $toPay = number_format($totalPay,2,'.','');
              } 

            $newTip->shumaPor = number_format($toPay + number_format((float)$req->tipForOrderCloseRechnungSel, 2, '.', ''), 2, '.', '');
            $newTip->tipPer = 'Empty';
            $newTip->tipTot = number_format((float)$req->tipForOrderCloseRechnungSel, 2, '.', '');
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
                $findGCActive = ghostCartInUse::where([['toRes',Auth::user()->sFor],['tableNr',$req->paySelTableNrRechnung],['indNr2',$clPhoneNr2D[1]],['status','0']])->first();
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
                $newNotifyClient->toRes = Auth::user()->sFor;
                $newNotifyClient->tableNr = $req->paySelTableNrRechnung;
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
            $tableGet = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$req->paySelTableNrRechnung]])->first();
            $tableGet->kaTab = 0;
            $tableGet->save();

            return 'ref||'.$newOrder->id;

            $endOfTab = 'true';
        }else{
            $endOfTab = 'false';
        }

        if($req->usedAndExistingClientSel == 0){
            $clDtRechnungFirmaInp = $req->RechnungFirmaInpSel;
            $clDtRechnungTelInp = $req->RechnungTelInpSel;
            $clDtRechnungNameInp = $req->RechnungNameInpSel;
            $clDtRechnungVornameInp = $req->RechnungVornameInpSel;
            $clDtRechnungStrNrInp = $req->RechnungStrNrInpSel;
            $clDtRechnungPlzOrtInp = $req->RechnungPlzOrtInpSel;
            $clDtRechnungLandInp = $req->RechnungLandInpSel;
            $clDtRechnungEmailInp = $req->RechnungEmailInpSel;
            $clDtRechnungSignature = 'empty';
            // if($req->saveClForARechnungSelVal == 1){
            //     $dtNow = Carbon::now();
            //     if($dtNow->month == 12){
            //         $setYear = $dtNow->year + 1;
            //         $setmonth = 1;
            //     }else{
            //         $setYear = $dtNow->year;
            //         $setmonth = $dtNow->month + 1;
            //     }
            //     $dateToPay = new Carbon($setYear.'-'.$setmonth.'-10 23:59:59');
            //     $clDtRechnungDaysToPay = $dateToPay->diff($dtNow)->days;
            // }else{
                $clDtRechnungDaysToPay = $req->RechnungDaysToPayInpSel;
            // }
            
        }else{
            $clData = rechnungClient::find($req->usedAndExistingClientIdSel);

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
            if($req->paySelRechnungComment != ''){ $rechExInfo->theComm = $req->paySelRechnungComment;
            }else if($req->paySelRechnungCommentClient != ''){ $rechExInfo->theComm = $req->paySelRechnungCommentClient; 
            }else{ $rechExInfo->theComm = "empty"; }
            $rechExInfo->statusConf = 0;

            if($clDtRechnungSignature == 'empty'){
                $folderPath = public_path('storage/rechnungPaySignatures/');       
                $image_parts = explode(";base64,", $req->signedSel);             
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
        if($req->saveClForARechnungSelVal == 1 && $req->usedAndExistingClientSel == 0){
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
        }else if($req->usedAndExistingClientSel != 0){
            $newBillToCl = new rechnungClientToBills();
            $newBillToCl->toRes = Auth::user()->sFor;
            $newBillToCl->billId = $rechExInfo->id;
            $newBillToCl->orderId = $newOrder->id;
            $newBillToCl->clientId = $req->usedAndExistingClientIdSel;
            $newBillToCl->save();
        }
        // ------------------------------------------------------------


        // save the EBANKING qrcode
        $theOr = Orders::findOrFail($newOrder->id);
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

        $theOr->ebankqrcode = $name.".png";
        $theOr->save();

        }
        // -------------------------------------------------------------------------------------------------------

        // send emails for payments through Rechnung
        // if($req->saveClForARechnungSelVal == 1 || $req->usedAndExistingClientSel != 0){
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
            // $customPaper = array(0,0,340.16,990+($nrOfOrders19P*32)+($nrOfOrders19*21)+($totExtra*12));
            // $pdf1 = PDF::loadView('adminInvoiceRechnung')->setPaper('a4','portrait');

            // $docName = 'rechnungBillFirst'.Restorant::fin($newOrder->Restaurant)->emri.'_'.$newOrder->id.'.pdf';
            // $pdf1->save('storage/rechnungBillsFirst/'.$docName);

            $item = Orders::find($newOrder->id);
            view()->share('items', $item);
            $pdf1 = PDF::loadView('adminInvoiceRechnung')->setPaper('a4', 'portrait');
            $docName = 'rechnungBillFirst'.Restorant::find($item->Restaurant)->emri.'_'.$item->id.'.pdf';
            $pdf1->save('storage/rechnungBillsFirst/'.$docName);

            $pdf2s = PDF::loadView('adminInvoiceRechnungFinal')->setPaper('a4','portrait');
            
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







        // Send Notifications for the ADMIN
        if($req->saveClForARechnungSelVal == 1 || $req->usedAndExistingClientSel != 0){
            foreach(User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $req->paySelTableNrRechnung
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));

                    if($endOfTab == 'true'){
                        $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$req->paySelTableNrRechnung],['statActive','1']])->first();
                        if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();}
                    }else{
                        foreach($AllFromTab as $oneTabOSel){
                            $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$req->paySelTableNrRechnung],['tabOrderId',$oneTabOSel->id],['statActive','1']])->first();
                            if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();} 
                        }
                    }
                }
                $to_name = $user->name;
                $to_email = $user->email;
                $fromResemri = Restorant::find($user->sFor)->emri;
                $reId = $newOrder->id;
                $data = array('sendName'=>$to_name, "sendEmail" => $to_email, "fromResName" => $fromResemri, "daysToPay" => $clDtRechnungDaysToPay, "ReId" => $newOrder->id);
                Mail::send('emails.rechnungPaymentForAdm', $data , function($message)use ($to_email, $to_name ,$reId ,$fromResemri ,$pdf2s){
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
                        'tableNr' => $req->paySelTableNrRechnung
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));

                    if($endOfTab == 'true'){
                        $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$req->paySelTableNrRechnung],['statActive','1']])->first();
                        if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();}
                    }else{
                        foreach($AllFromTab as $oneTabOSel){
                            $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$req->paySelTableNrRechnung],['tabOrderId',$oneTabOSel->id],['statActive','1']])->first();
                            if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();} 
                        }
                    }
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



        // Send Notifications for the WAITER
        if($req->saveClForARechnungSelVal == 1 || $req->usedAndExistingClientSel != 0){
            foreach(User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->paySelTableNrRechnung]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $req->paySelTableNrRechnung
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
                $to_name = $oneWaiter->name;
                $to_email = $oneWaiter->email;
                $fromResemri = Restorant::find($oneWaiter->sFor)->emri;
                $reId = $newOrder->id;
                $data = array('sendName'=>$to_name, "sendEmail" => $to_email, "fromResName" => $fromResemri, "daysToPay" => $clDtRechnungDaysToPay, "ReId" => $newOrder->id);
                Mail::send('emails.rechnungPaymentForAdm', $data , function($message)use ($to_email, $to_name ,$reId ,$fromResemri ,$pdf2s){
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
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->paySelTableNrRechnung]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $req->paySelTableNrRechnung
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
                'tableNr' => $req->paySelTableNrRechnung
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }

        if(Auth::user()->role == 55){
            return redirect()->route('admWoMng.indexAdmMngPageWaiter' , ['orId' => $newOrder->id, 'orExId' =>  $rechExInfo->id, 'ptn' => $returnPrevTableNr]);
        }else{
            return redirect()->route('dash.index' , ['orId' => $newOrder->id, 'orExId' =>  $rechExInfo->id, 'ptn' => $returnPrevTableNr]);
        }
    }
}
