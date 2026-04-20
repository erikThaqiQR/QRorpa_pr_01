<?php

namespace App\Http\Controllers;

use PDF;
use QRCode;
use App\Orders;
use App\giftCard;
use App\Restorant;
use Carbon\Carbon;
use App\OrdersPassive;
use App\giftCardToSell;
use App\rechnungClient;
use App\giftCardOnlinePay;
use App\giftCardDeleteLogs;
use App\giftCardRechnungPay;
use Illuminate\Http\Request;
use App\payTecTransactionTaLog;
use App\giftCardOnlinePayReference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;

class GiftCardController extends Controller{

    public function giftCardMngAdmin(){return view('adminPanel/adminIndex');}
    public function giftCardClientPayOnline(){ return view('gcOnlinePayClient'); }
    public function giftCardOnlinePayFinishPage(){ return view('gcOnlinePayClientFinish'); }
    public function giftCardMngAdminWa(){return view('adminPanelWaiter/adminIndexWaiter');}
    public function giftCardcheckBalance(){ return view('giftCardBalance/giftCardBalanceIndex'); }

    public function giftCardRegCashCardPay(Request $req){
        $gcToSell = 0;
        if($req->gcConToKartel == 0 ){
            $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'));
            shuffle($word);
            shuffle($word);
            $GChash = substr(implode($word), 0, 256);

            $wordForIdn = array_merge(range('0', '9'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('0', '9'), range('A', 'Z'));
            shuffle($wordForIdn);
            
        }else{
            $gcToSell = giftCardToSell::find($req->gcConToKartel);
            $GChash = $gcToSell->hashVal;

            $gcToSell->status = 'sold';
            $gcToSell->save();
        }
        do{
            if($req->gcConToKartel == 0 ){
                shuffle($wordForIdn);
                $randIndNr = substr(implode($wordForIdn), 0, 8);
            }else{
                $randIndNr = $gcToSell->idnShortCode;
            }
            if(giftCard::where([['idnShortCode', $randIndNr],['statActive','1']])->first() == Null){
                $newGC =new giftCard();
                if($req->gcConToKartel != 0){ $newGC->id = $gcToSell->toGC; }
                $gcRefId = giftCard::where('toRes',Auth::user()->sFor)->max('refId') + 1;
                $gcDeletedRefId = giftCardDeleteLogs::where('toRes',Auth::user()->sFor)->max('refId') + 1;
                if( $gcRefId > $gcDeletedRefId){$finalRefId = $gcRefId;
                }else{ $finalRefId = $gcDeletedRefId; }
                $newGC->refId = $finalRefId;
                $newGC->idnShortCode = $randIndNr;
                $newGC->toRes = Auth::user()->sFor;
                $newGC->gcType = $req->gcType;
                $newGC->gcProdId = 0;
                $newGC->gcProdSum = 0;
                $newGC->gcProdSumUsed = 0;
                $newGC->gcSumInChf = number_format($req->gcValueInChf,2,'.','');
                $newGC->gcSumInChfUsed = number_format(0,2,'.','');
                $newGC->clName = $req->gcClName;
                $newGC->clLastname = $req->gcCllastname;
                $newGC->clEmail = $req->gcClEmail;
                $newGC->clPhNr = $req->gcClPhoneNr;
                $newGC->usedInOrdersId = 'empty';
                $newGC->payM = $req->gcPayMeth;
                $newGC->gcHash = $GChash;
                $newGC->soldByStaff = Auth::user()->id;
                $exDt2D = explode('-',$req->gcExDate);
                $newGC->expirationDate = $exDt2D[0].'-'.$exDt2D[1].'-'.$exDt2D[2].' 23:59:59';
                $newGC->save();

                $reqpeatThis = false;
            }else{
                $reqpeatThis = true;
            }
        }while($reqpeatThis);

        $name = $newGC->id;
        $file = "storage/giftcardQRCode/GC".$name.".png";
        $newQrcode = QRCode::text($newGC->idnShortCode.'|||'.$newGC->gcHash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file)
        ->png();

        $file2 = "storage/giftCardBillQRCode/GCBillQrC".$name.".png";
        $newQrcode2 = QRCode::URL('qrorpa.ch/giftCardGetReceiptFQRC?gcid='.$newGC->id.'&hs='.$GChash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file2)
        ->png();

        $file3 = "storage/giftCardBalanceQRCode/GCBalanceQrC".$name.".png";
        $newQrcode3 = QRCode::URL('qrorpa.ch/giftCardcheckBalance?gcid='.$newGC->id.'&hs='.$GChash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file3)
        ->png();

        // PayTec LOG register
        if($req->gcPayMeth == 'Card' && $req->payTecTrx != 'none'){
            // $req->payTecTrx
            $payTecTrx = json_decode($req->payTecTrx);
            $payTecLog = new payTecTransactionTaLog();
            $payTecLog->gcId = $newGC->id;
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

        // send email to client
        if($req->gcClEmail != 'empty'){

            if($req->gcClName != 'empty'){ $to_name = $req->gcClName;
            }else{ $to_name = 'QRorpa-Kunde'; }
            $to_email = str_replace(' ', '', $req->gcClEmail); 
            $GCvalue = number_format($req->gcValueInChf,2,'.','');
            $theRes = Restorant::find(Auth::user()->sFor);

            $data = array('name'=>$to_name, 'resName' => $theRes->emri, "gcValue" => $GCvalue, "gcCode" => $newGC->idnShortCode, "gcQrCode" => "GC".$name.".png", "gcExpDt" => $req->gcExDate, "gcId" => $newGC->id, "gcHash" => $newGC->gcHash);
       
            $theRes = Restorant::find($newGC->toRes);
            $theResEmri = $theRes->emri;
            $newGCId = $newGC->id;
            view()->share('theGC', $newGC);
            $customPaper = array(0,0,340.16,750+(2*21));
            $pdfBill = PDF::loadView('GiftCardInvoice')->setPaper($customPaper, 'potrait');

            Mail::send('emails.giftCardToClients', $data, function($message) use ($to_name, $to_email, $pdfBill, $theResEmri, $newGCId) {
                $message->to($to_email, $to_name)->subject('Geschenkkarte');
                $message->from('noreply@qrorpa.ch','QRorpa');
                $message->attachData($pdfBill->output(), 'giftCardBill_'.$theResEmri.'_'.$newGCId.'.pdf', [
                    'mime' => 'application/pdf',
                ]);
            });
        }
    }


    public function giftCardRegOnlinePay(Request $req){
        $gcToSell = 0;
        if($req->gcConToKartel == 0 ){
            $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'));
            shuffle($word);
            shuffle($word);
            $GChash = substr(implode($word), 0, 256);

            $wordForIdn = array_merge(range('0', '9'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('0', '9'), range('A', 'Z'));
            shuffle($wordForIdn);
            
        }else{
            $gcToSell = giftCardToSell::find($req->gcConToKartel);
            $GChash = $gcToSell->hashVal;

            $gcToSell->status = 'sold';
            $gcToSell->save();
        }

        do{
            if($req->gcConToKartel == 0 ){
                shuffle($wordForIdn);
                $randIndNr = substr(implode($wordForIdn), 0, 8);
            }else{
                $randIndNr = $gcToSell->idnShortCode;
            }

            if(giftCard::where([['idnShortCode', $randIndNr],['statActive','1']])->first() == Null){
                $newGC =new giftCard();
                if($req->gcConToKartel != 0){ $newGC->id = $gcToSell->toGC; }
                $gcRefId = giftCard::where('toRes',Auth::user()->sFor)->max('refId') + 1;
                $gcDeletedRefId = giftCardDeleteLogs::where('toRes',Auth::user()->sFor)->max('refId') + 1;
                if( $gcRefId > $gcDeletedRefId){$finalRefId = $gcRefId;
                }else{ $finalRefId = $gcDeletedRefId; }
                $newGC->refId = $finalRefId;
                $newGC->idnShortCode = $randIndNr;
                $newGC->toRes = Auth::user()->sFor;
                $newGC->gcType = $req->gcType;
                $newGC->gcProdId = 0;
                $newGC->gcProdSum = 0;
                $newGC->gcProdSumUsed = 0;
                $newGC->gcSumInChf = number_format($req->gcValueInChf,2,'.','');
                $newGC->gcSumInChfUsed = number_format(0,2,'.','');
                $newGC->clName = $req->gcClName;
                $newGC->clLastname = $req->gcCllastname;
                $newGC->clEmail = $req->gcClEmail;
                $newGC->clPhNr = $req->gcClPhoneNr;
                $newGC->usedInOrdersId = 'empty';
                $newGC->payM = $req->gcPayMeth;
                $newGC->gcHash = $GChash;
                $newGC->onlinePayStat = 0;
                $newGC->soldByStaff = Auth::user()->id;
                $exDt2D = explode('-',$req->gcExDate);
                $newGC->expirationDate = $exDt2D[0].'-'.$exDt2D[1].'-'.$exDt2D[2].' 23:59:59';
                $newGC->save();

                $reqpeatThis = false;
            }else{
                $reqpeatThis = true;
            }
        }while($reqpeatThis);

        $name = $newGC->id;
        $file1 = "storage/giftcardQRCode/GC".$name.".png";
        $useGCQRCode = QRCode::text($newGC->idnShortCode.'|||'.$newGC->gcHash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file1)
        ->png();

        $file2 = "storage/giftcardOnlinePayQRCode/GCOnlinePay".$name.".png";
        $GCOnlinePayQRC = QRCode::URL('qrorpa.ch/giftCardOnlinePay?s='.$newGC->id.'&hs='.$GChash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file2)
        ->png();

        $file3 = "storage/giftCardBillQRCode/GCBillQrC".$name.".png";
        $newQrcode = QRCode::URL('qrorpa.ch/giftCardGetReceiptFQRC?gcid='.$newGC->id.'&hs='.$GChash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file3)
        ->png();

        $file4 = "storage/giftCardBalanceQRCode/GCBalanceQrC".$name.".png";
        $newQrcode = QRCode::URL('qrorpa.ch/giftCardcheckBalance?gcid='.$newGC->id.'&hs='.$GChash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file4)
        ->png();

        $newGCOnlinePayIns = new giftCardOnlinePay();
        $newGCOnlinePayIns->giftCardId = $newGC->id;
        $newGCOnlinePayIns->paySum = number_format($req->gcValueInChf,2,'.','');;
        $newGCOnlinePayIns->payOnlineQrCode = "GCOnlinePay".$name.".png";
        $newGCOnlinePayIns->save();

        // send email to client
        if($req->gcClEmail != 'empty'){

            if($req->gcClName != 'empty'){ $to_name = $req->gcClName;
            }else{ $to_name = 'QRorpa-Kunde'; }
            $to_email = str_replace(' ', '', $req->gcClEmail); 
            $GCvalue = number_format($req->gcValueInChf,2,'.','');
            $theRes = Restorant::find(Auth::user()->sFor);

            $data = array('name'=>$to_name, 'resName' => $theRes->emri, "gcValue" => $GCvalue, "gcCode" => $newGC->idnShortCode, "gcQrCode" => "GC".$name.".png", "gcExpDt" => $req->gcExDate, "gcId" => $newGC->id, "gcHash" => $newGC->gcHash);
                
            $theRes = Restorant::find($newGC->toRes);
            $theResEmri = $theRes->emri;
            $newGCId = $newGC->id;
            view()->share('theGC', $newGC);
            $customPaper = array(0,0,340.16,750+(2*21));
            $pdfBill = PDF::loadView('GiftCardInvoice')->setPaper($customPaper, 'potrait');

            Mail::send('emails.giftCardToClients', $data, function($message) use ($to_name, $to_email, $pdfBill, $theResEmri, $newGCId) {
                $message->to($to_email, $to_name)->subject('Geschenkkarte');
                $message->from('noreply@qrorpa.ch','QRorpa');
                $message->attachData($pdfBill->output(), 'giftCardBill_'.$theResEmri.'_'.$newGCId.'.pdf', [
                    'mime' => 'application/pdf',
                ]);
            });
        }

        return "GCOnlinePay".$name.".png";
    }







    public function giftCardRegAufRechnungPay(Request $req){
        $gcToSell = 0;
        if($req->payRechnungGCToSellCon == 0 ){
            $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'));
            shuffle($word);
            shuffle($word);
            $GChash = substr(implode($word), 0, 256);

            $wordForIdn = array_merge(range('0', '9'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('0', '9'), range('A', 'Z'));
            shuffle($wordForIdn);
            
        }else{
            $gcToSell = giftCardToSell::find($req->payRechnungGCToSellCon);
            $GChash = $gcToSell->hashVal;

            $gcToSell->status = 'sold';
            $gcToSell->save();
        }
        do{
            if($req->payRechnungGCToSellCon == 0 ){
                shuffle($wordForIdn);
                $randIndNr = substr(implode($wordForIdn), 0, 8);
            }else{
                $randIndNr = $gcToSell->idnShortCode;
            }

            if(giftCard::where([['idnShortCode', $randIndNr],['statActive','1']])->first() == Null){
                $newGC =new giftCard();
                if($req->payRechnungGCToSellCon != 0){ $newGC->id = $gcToSell->toGC; }
                $gcRefId = giftCard::where('toRes',Auth::user()->sFor)->max('refId') + 1;
                $gcDeletedRefId = giftCardDeleteLogs::where('toRes',Auth::user()->sFor)->max('refId') + 1;
                if( $gcRefId > $gcDeletedRefId){$finalRefId = $gcRefId;
                }else{ $finalRefId = $gcDeletedRefId; }
                $newGC->refId = $finalRefId;
                $newGC->idnShortCode = $randIndNr;
                $newGC->toRes = Auth::user()->sFor;
                $newGC->gcType = $req->payRechnungGCType;
                $newGC->gcProdId = 0;
                $newGC->gcProdSum = 0;
                $newGC->gcProdSumUsed = 0;
                $newGC->gcSumInChf = number_format($req->payRechnungValueInCHF,2,'.','');
                $newGC->gcSumInChfUsed = number_format(0,2,'.','');
                $newGC->clName = $req->payRechnungClName;
                $newGC->clLastname = $req->payRechnungClLastname;
                $newGC->clEmail = $req->payRechnungClEmail;
                $newGC->clPhNr = $req->payRechnungClPhoneNr;
                $newGC->usedInOrdersId = 'empty';
                $newGC->payM = "Rechnung";
                $newGC->gcHash = $GChash;
                $newGC->onlinePayStat = 1;
                $newGC->rechnungPayClId = $req->usedAndExistingClientAll;
                $newGC->soldByStaff = Auth::user()->id;
                $exDt2D = explode('-',$req->rechnungPayExpiringDate);
                $newGC->expirationDate = $exDt2D[0].'-'.$exDt2D[1].'-'.$exDt2D[2].' 23:59:59';
                $newGC->save();

                $reqpeatThis = false;
            }else{
                $reqpeatThis = true;
            }
        }while($reqpeatThis);

        $name = $newGC->id;
        $file1 = "storage/giftcardQRCode/GC".$name.".png";
        $useGCQRCode = QRCode::text($newGC->idnShortCode.'|||'.$newGC->gcHash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file1)
        ->png();

        $file2 = "storage/giftCardBillQRCode/GCBillQrC".$name.".png";
        $newQrcode = QRCode::URL('qrorpa.ch/giftCardGetReceiptFQRC?gcid='.$newGC->id.'&hs='.$GChash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file2)
        ->png();

        $file3 = "storage/giftCardBalanceQRCode/GCBalanceQrC".$name.".png";
        $newQrcode = QRCode::URL('qrorpa.ch/giftCardcheckBalance?gcid='.$newGC->id.'&hs='.$GChash)
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($file3)
        ->png();

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
            if($req->saveClForARechnungAllVal == 1){
                $dtNow = Carbon::now();
                if($dtNow->month == 12){ $setYear = $dtNow->year + 1; $setmonth = 1;
                }else{ $setYear = $dtNow->year; $setmonth = $dtNow->month + 1; }
                $dateToPay = new Carbon($setYear.'-'.$setmonth.'-10 23:59:59');
                $clDtRechnungDaysToPay = $dateToPay->diff($dtNow)->days;
            }else{
                $clDtRechnungDaysToPay = $req->RechnungDaysToPayInp;
            }
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
            $clDtRechnungSignature = $clData->signatureFile;;

            $dtNow = Carbon::now();
            if($dtNow->month == 12){
                $setYear = $dtNow->year + 1;
                $setmonth = 1;
            }else{
                $setYear = $dtNow->year;
                $setmonth = $dtNow->month + 1;
            }
            $dateToPay = new Carbon($setYear.'-'.$setmonth.'-'.$clData->daysToPay.' 23:59:59');
            $clDtRechnungDaysToPay = $dateToPay->diff($dtNow)->days;
        }

        $rechnungGCnew = new giftCardRechnungPay();
        $rechnungGCnew->toRes = Auth::user()->sFor;
        $rechnungGCnew->gcId = $newGC->id;
        $rechnungGCnew->clientId = $req->usedAndExistingClientIdAll;
        $rechnungGCnew->clFirma = $clDtRechnungFirmaInp;
        $rechnungGCnew->clPhoneNr = $clDtRechnungTelInp;
        $rechnungGCnew->clName = $clDtRechnungNameInp;
        $rechnungGCnew->clLastname = $clDtRechnungVornameInp;
        $rechnungGCnew->clStreetNr = $clDtRechnungStrNrInp;
        $rechnungGCnew->clPlzOrt = $clDtRechnungPlzOrtInp;
        $rechnungGCnew->clLand = $clDtRechnungLandInp;
        $rechnungGCnew->clEmail = $clDtRechnungEmailInp;
        $rechnungGCnew->clDaysToPay = $clDtRechnungDaysToPay;
        if($req->payAllRechnungComment != ''){ $rechnungGCnew->clComment = $req->payAllRechnungComment;
        }else{ $rechnungGCnew->clComment = "empty"; }
        if($clDtRechnungSignature == 'empty'){
            $folderPath = public_path('storage/giftcardRechnungSignature/');       
            $image_parts = explode(";base64,", $req->signed);             
            $image_type_aux = explode("image/", $image_parts[0]);           
            $image_type = $image_type_aux[1];           
            $image_base64 = base64_decode($image_parts[1]); 
            $signature = uniqid() . '.'.$image_type;           
            $file = $folderPath . $signature;
            file_put_contents($file, $image_base64);
            $rechnungGCnew->clSignature = $signature;
            $clDtRechnungSignature = $signature;
        }else{
            $rechnungGCnew->clSignature = $clDtRechnungSignature;
        }
        $rechnungGCnew->save();

        // Register client
        if($req->saveClForARechnungAllVal == 1 && $req->usedAndExistingClientAll == 0){
            $checkCl = rechnungClient::where([['toRes',Auth::user()->sFor],['firmaName',$req->RechnungFirmaInp],['phoneNr',$req->RechnungTelInp],['name',$req->RechnungNameInp],['lastname',$req->RechnungVornameInp]])->first();
            if($checkCl == Null){
                $newCl = new rechnungClient();
                $newCl->toRes = Auth::user()->sFor;
                $newCl->firmaName = $req->RechnungFirmaInp;
                $newCl->phoneNr = $req->RechnungTelInp;
                $newCl->name = $req->RechnungNameInp;
                $newCl->lastname = $req->RechnungVornameInp;
                $newCl->street = $req->RechnungStrNrInp;
                $newCl->plzort = $req->RechnungPlzOrtInp;
                $newCl->land = $req->RechnungLandInp;
                $newCl->email = $req->RechnungEmailInp;
                $newCl->signatureFile = $clDtRechnungSignature;
                $newCl->save();
            }
        }

        
        $theRes = Restorant::findOrFail(Auth::user()->sFor);
        $adr2D = explode(',',$theRes->adresa);
        $ad2 = '---';
        if(isset($adr2D[1])){
            $ad2 = $adr2D[1];
        }
        if(isset($adr2D[2])){
            $ad2 = $adr2D[1].','.$adr2D[2];
        }
        $billNr = str_pad($newGC->id, 10, '0', STR_PAD_LEFT);

        $GCRechnungData = giftCardRechnungPay::where('gcId',$newGC->id)->first();

        $fileEbankqrcode = "storage/giftCardEbankqrcode/".$name.".png";

        $newQrcode = QRCode::text('SPC
0200
1
'.$theRes->resBankId.'
K
'.$theRes->emri.'
'.$adr2D[0].'
'.$ad2.'


CH







'.number_format($req->payRechnungValueInCHF, 2, '.', '').'
CHF
K
'.$GCRechnungData->clFirma.'
'.$GCRechnungData->clStreetNr.'
'.$GCRechnungData->clPlzOrt.' '.$GCRechnungData->clLand.'


CH
NON

'.$billNr.'
EPD
')
        ->setSize(64)
        ->setMargin(0)
        ->setOutfile($fileEbankqrcode)
        ->png();

        $img1 = Image::make('storage/giftCardEbankqrcode/'.$name.'.png');
        $img1->insert('storage/ebankqrcode/eBPIcon.png');
        $img1->save('storage/giftCardEbankqrcode/'.$name.'.png');

        $newGC->ebankqrcode = $name.".png";
        $newGC->save();


        // send email to client
        if($req->gcClEmail != 'empty'){

            if($req->payRechnungClName != 'empty'){ $to_name = $req->payRechnungClName;
            }else{ $to_name = 'QRorpa-Kunde'; }
            $to_email = str_replace(' ', '', $clDtRechnungEmailInp); 
            $GCvalue = number_format($req->payRechnungValueInCHF,2,'.','');
            $theRes = Restorant::find(Auth::user()->sFor);

            $data = array('name'=>$to_name, 'resName' => $theRes->emri, "gcValue" => $GCvalue, "gcCode" => $newGC->idnShortCode, "gcQrCode" => "GC".$name.".png", "gcExpDt" => $req->rechnungPayExpiringDate, "gcId" => $newGC->id, "gcHash" => $newGC->gcHash);
                
            $theRes = Restorant::find($newGC->toRes);
            $theResEmri = $theRes->emri;
            $newGCId = $newGC->id;
            view()->share('theGC', $newGC);
            $customPaper = array(0,0,340.16,750+(2*21));
            $pdfBill = PDF::loadView('GiftCardInvoice')->setPaper($customPaper, 'potrait');


            // Auf rechnung bill
            $item = giftCard::find($newGC->id);
            view()->share('items', $item);
            $pdfARe1 = PDF::loadView('adminInvoiceRechnungGC')->setPaper('a4', 'portrait');
            $docName = 'rechnungBillFirst'.Restorant::find($item->toRes)->emri.'_'.$item->id.'.pdf';
            $pdfARe1->save('storage/giftCardRechnungBill/'.$docName);

            $pdfARe2 = PDF::loadView('adminInvoiceRechnungFinalGC')->setPaper('a4','portrait');

            $koha2D = explode('-',explode(' ',$newGC->created_at)[0]);
            //------------------------------------------------------------------ 

            Mail::send('emails.giftCardToClients', $data, function($message) use ($to_name, $to_email, $pdfBill, $pdfARe2, $theResEmri, $newGCId, $koha2D) {
                $message->to($to_email, $to_name)->subject('Geschenkkarte');
                $message->from('noreply@qrorpa.ch','QRorpa');
                $message->attachData($pdfBill->output(), 'giftCardBill_'.$theResEmri.'_'.$newGCId.'.pdf', [
                    'mime' => 'application/pdf',
                ]);
                $message->attachData($pdfARe2->output(), 'Rechnung_GC-'.$newGCId.'-QRorpa-'.$koha2D[2].'_'.$koha2D[1].'_'.$koha2D[0].'.pdf', [
                    'mime' => 'application/pdf',
                ]);
            });
        }

        if(Auth::user()->role == 55){
            return redirect()->route('giftCardWa.giftCardMngAdminWa');
        }else{
            return redirect()->route('giftCard.giftCardMngAdmin');
        }
    }












    public function giftCardValidateTheIdnCode(Request $req){
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

    public function giftCardValidateTheSumToApplyDisc(Request $req){
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

    public function giftCardValidateTheSumToApplyDiscMax(Request $req){
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








    public function giftCardOnlinePayInitiatePay(Request $req){
        $gcOnlinePay = giftCard::find($req->onlPayGiftCardId);

        $spRefGC = new giftCardOnlinePayReference();
        $spRefGC->toRes = $gcOnlinePay->toRes;
        $spRefGC->refPh = "empty";
        $spRefGC->giftCardId = $gcOnlinePay->id;
        $spRefGC->theStat = 0;
        $spRefGC->save();

        $toPay = number_format($gcOnlinePay->gcSumInChf,2,'.','');
        
        $OnlinePayRegister = $gcOnlinePay->id.'|||'.$toPay;
        $OnlinePayRegister2 = str_replace("&", "und",  $OnlinePayRegister);

        if($gcOnlinePay->toRes == 31 || $gcOnlinePay->toRes == 32 || $gcOnlinePay->toRes == 33 || $gcOnlinePay->toRes == 34 || $gcOnlinePay->toRes == 39){
            $CustomerId = "335189";
            $TerminalId = "17750074";
            $username = "API_335189_86302817";
            $password = "t5$&8Hj6uI2&*&23sd";
        }else{
            $CustomerId = "259813";
            $TerminalId = "17746921";
            $username = "API_259813_10756315";
            $password = "hybHBjb3vg45vh55";
        }

        $payload = array(
            'RequestHeader' => array(
                'SpecVersion' => "1.7",
                'CustomerId' => $CustomerId,
                'RequestId' => "aScdFewDSRFrfas2wsad3",
                'RetryIndicator' => 0,
                'ClientInfo' => array(
                    'ShopInfo' => "My Shop",
                    'OsInfo' => "Windows 10"
                    )
            ),
            'TerminalId' => $TerminalId,
            // 
            'Payment' => array(
                'Amount' => array(
                    'Value' => "1",
                    'CurrencyCode' => "CHF"
                ),
                'OrderId' => "SP_GC_".$spRefGC->id,
                'PayerNote' => "A Note",
                'Description' => "Test_Order_123test"
            ),
            'Payer' => array(
                'IpAddress' => "192.168.178.1",
                'LanguageCode' => "en"
            ),
            'ReturnUrls' => array(
                'Success' => "https://qrorpa.ch/giftCardOnlinePayInitiatePayRegister?OPR=".$OnlinePayRegister2,
                'Fail' => "https://qrorpa.ch/?Res=".$gcOnlinePay->toRes."&t=1"
            ),
            'Notification' => array(
                'PayerEmail' => "erikthaqi1@amail.com",
                'MerchantEmail' => "checkout@qrorpa.ch",
                'NotifyUrl' => "https://qrorpa.ch/?Res=".$gcOnlinePay->toRes."&t=1"
            ),
        );

        $payload['Payment']['Amount']['Value'] = strval( $toPay*100 ) ;
      
        //$username and $password for the http-Basic Authentication
        //$url is the SaferpayURL eg. https://www.saferpay.com/api/Payment/v1/PaymentPage/Initialize
        //$payload is a multidimensional array, that assembles the JSON structure. Example see above
        $url = "https://test.saferpay.com/api/Payment/v1/PaymentPage/Initialize";
            
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);

        //Return Response to Application
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //Set Content-Headers to JSON
        curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-type: application/json","Accept: application/json; charset=utf-8"));

        //Execute call via http-POST
        curl_setopt($curl, CURLOPT_POST, true);

        //Set POST-Body
        //convert DATA-Array into a JSON-Object
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));

        //WARNING!!!!!
        //This option should NOT be "false", otherwise the connection is not secured
        //You can turn it of if you're working on the test-system with no vital data
        //PLEASE NOTE:
        //Under Windows (using WAMP or XAMP) it is necessary to manually download and save the necessary SSL-Root certificates!
        //To do so, please visit: http://curl.haxx.se/docs/caextract.html and Download the .pem-file
        //Then save it to a folder where PHP has write privileges (e.g. the WAMP/XAMP-Folder itself)
        //and then put the following line into your php.ini:
        //curl.cainfo=c:\path\to\file\cacert.pem
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        //HTTP-Basic Authentication for the Saferpay JSON-API.
        //This will set the authentication header and encode the password & username in Base64 for you
        curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);

        //CURL-Execute & catch response
        $jsonResponse = curl_exec($curl);

        //Get HTTP-Status
        //Abort if Status != 200
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($status != 200) {
            //IF ERROR
            //Get http-Body (if aplicable) from CURL-response
            $body = json_decode(curl_multi_getcontent($curl), true);
            //Build array, containing the body (Response data, like Error-messages etc.) and the http-status-code
            $response = array(
                "status" => $status . " <|> " . curl_error($curl),
                "body" => $body
            );
        }else{
            //IF OK
            //Convert response into an Array
            $body = json_decode($jsonResponse, true);
            //Build array, containing the body (Response-data) and the http-status-code
            $response = array(
                "status" => $status,
                "body" => $body
            );
        }

        //IMPORTANT!!!
        //Close connection!
        curl_close($curl);
        //$response, again, is a multi-dimensional Array, containing the status-code ($response["status"]) and the API-response (if available) itself ($response["body"])
        return $response;
    }

    public function giftCardOnlinePayInitiatePayRegister(Request $req){
        if(isset($_GET['OPR'])){
            $requ = explode('|||',$_GET['OPR']);

            $gcOnlinePay = giftCard::find($requ[0]);
            $gcOnlinePay->onlinePayStat = 1;
            $gcOnlinePay->save();

            $spRefGC = giftCardOnlinePayReference::where('giftCardId',$gcOnlinePay->id)->first();
            $spRefGC->save();

            return redirect('/giftCardOnlinePayFinishPage?stat=payed&gcId='.$gcOnlinePay->id);
        }else{
            return redirect('/giftCardOnlinePayFinishPage?stat=noopr');
        }
    }


    public function giftCardShowExpAndUsed(Request $req){
        $nowDt = Carbon::now();
        $expAndUsedGC = giftCard::where([['toRes',Auth::user()->sFor],['statActive','1'],['expirationDate','<',$nowDt]])->orWhereColumn('gcSumInChf', '=', 'gcSumInChfUsed')->where('toRes',Auth::user()->sFor)
                                    ->orderByDesc('created_at')->get();
        return json_encode( $expAndUsedGC );
    }

    public function giftCardShowDeletedIns(Request $req){
        $deletedGC = giftCardDeleteLogs::where('toRes',Auth::user()->sFor)->orderByDesc('updated_at')->get();
        return json_encode( $deletedGC );
    }



    public function giftCardGetReceipt(Request $req){
        $theGC = giftCard::find($req->giftCardId);
        if($theGC != Null){
            $theRes = Restorant::find($theGC->toRes);
            view()->share('theGC', $theGC);
            $customPaper = array(0,0,340.16,800+(2*21));
            $pdf = PDF::loadView('GiftCardInvoice')->setPaper($customPaper, 'potrait');
            $docName = 'giftCardBill_'.$theRes->emri.'_'.$theGC->id.'.pdf';
            $pdf->save('storage/giftCardBill/'.$docName);
            return $pdf->download('giftCardBill_'.$theRes->emri.'_'.$theGC->id.'.pdf');
        }
    }

    public function giftCardGetReceiptFQRC(Request $req){
        if(isset($_GET['gcid']) && isset($_GET['hs'])){
            $theGC = giftCard::find($_GET['gcid']);
            if($theGC != Null && $theGC->gcHash == $_GET['hs']){
                $theRes = Restorant::find($theGC->toRes);
                view()->share('theGC', $theGC);
                $customPaper = array(0,0,340.16,800+(2*21));
                $pdf = PDF::loadView('GiftCardInvoice')->setPaper($customPaper, 'potrait');
                $docName = 'giftCardBill_'.$theRes->emri.'_'.$theGC->id.'.pdf';
                $pdf->save('storage/giftCardBill/'.$docName);
                return $pdf->download('giftCardBill_'.$theRes->emri.'_'.$theGC->id.'.pdf');
            }
        }

    }









    public function giftCardFetchcheckBalance(Request $req){
        $theGC = giftCard::find($req->gcId);
        if($theGC != Null){
            $showgcUse = '';
            if($theGC->usedInOrdersId == 'empty'){
                $showgcUse = 'empty';
            }else{
                foreach (explode('|||',$theGC->usedInOrdersId) as $OneGCUse){
                    $theOrder = OrdersPassive::find($OneGCUse);
                    if( $theOrder == Null ){  $theOrder = Orders::find($OneGCUse); }

                    $date2D = explode('-',explode(' ',$theOrder->created_at)[0]);
                    $time2D = explode(':',explode(' ',$theOrder->created_at)[1]);
                    $orderUseDate = $date2D[2].'.'.$date2D[1].'.'.$date2D[0].' '.$time2D[0].':'.$time2D[1];

                    if($showgcUse == ''){
                        $showgcUse = $theOrder->id.'-||-'.$theOrder->dicsountGcAmnt.'-||-'.$orderUseDate;
                    }else{
                        $showgcUse .= '---|---'.$theOrder->id.'-||-'.$theOrder->dicsountGcAmnt.'-||-'.$orderUseDate;
                    }
                }
            }
            return $theGC->id.'|||'.$theGC->clName.'|||'.$theGC->clLastname.'|||'.$theGC->clEmail.'|||'.
            $theGC->clPhNr.'|||'.$theGC->gcSumInChf.'|||'.$theGC->gcSumInChfUsed.'|||'.$showgcUse.'|||'.$theGC->refId;
        }else{
            return 'notFound';
        }
    }





    public function giftCardDeleteInstance(Request $req){
        $theGC = giftCard::find($req->gcId);
        if($theGC != Null){
            $theGCDeleteLog = new giftCardDeleteLogs();

            $theGCDeleteLog->id = $theGC->id;
            $theGCDeleteLog->refId = $theGC->refId;
            $theGCDeleteLog->idnShortCode = $theGC->idnShortCode;
            $theGCDeleteLog->toRes = $theGC->toRes;
            $theGCDeleteLog->gcType = $theGC->gcType;
            $theGCDeleteLog->gcProdId = $theGC->gcProdId;
            $theGCDeleteLog->gcProdSum = $theGC->gcProdSum;
            $theGCDeleteLog->gcProdSumUsed = $theGC->gcProdSumUsed;
            $theGCDeleteLog->gcSumInChf = $theGC->gcSumInChf;
            $theGCDeleteLog->gcSumInChfUsed = $theGC->gcSumInChfUsed;
            $theGCDeleteLog->clName = $theGC->clName;
            $theGCDeleteLog->clLastname = $theGC->clLastname;
            $theGCDeleteLog->clEmail = $theGC->clEmail;
            $theGCDeleteLog->clPhNr = $theGC->clPhNr;
            $theGCDeleteLog->usedInOrdersId = $theGC->usedInOrdersId;
            $theGCDeleteLog->payM = $theGC->payM;
            $theGCDeleteLog->gcHash = $theGC->gcHash;
            $theGCDeleteLog->statActive = $theGC->statActive;
            $theGCDeleteLog->onlinePayStat = $theGC->onlinePayStat;
            $theGCDeleteLog->rechnungPayClId = $theGC->rechnungPayClId;
            $theGCDeleteLog->soldByStaff = $theGC->soldByStaff;
            $theGCDeleteLog->expirationDate = $theGC->expirationDate;
            $theGCDeleteLog->deletedByStaff = Auth::user()->id;
            $theGCDeleteLog->created_at = $theGC->created_at;
            
            $theGCDeleteLog->save();


            $theGC->delete();

        }else{
            return 'notFound';
        }
    }





    function validateGiftCardToSellKartel(Request $req){
        $theGcToSell = giftCardToSell::where([['idnShortCode',$req->gcIndCode],['hashVal',$req->gcHash]])->first();
        if($theGcToSell != Null){
            if($theGcToSell->status == 'notSold'){
                return $theGcToSell->id;
            }else{
                return 'gcToSellAlreadySold';
            }
            
        }else{
            return 'gcToSellNotFound';
        }
    }

    function validateGiftCardOnScanToApply(Request $req){
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




    public function giftCardCreateGCToSell(Request $req){
        if(isset($_GET['hashVal']) && $_GET['hashVal'] == 'hPj65fggFG4234FGfBjQasLMk345jGfD24784d'){
            if(isset($_GET['nrOfGC'])){
                for($i = 1 ; $i <= $_GET['nrOfGC']; $i++){

                    $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'), range('a', 'z'), range('A', 'Z'), range('0', '9'));
                    shuffle($word);
                    shuffle($word);
                    $GChash = substr(implode($word), 0, 256);

                    $wordForIdn = array_merge(range('0', '9'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('0', '9'), range('A', 'Z'),range('0', '9'), range('A', 'Z'));
                    shuffle($wordForIdn);
                    do{
                        shuffle($wordForIdn);
                        $randIndNr = substr(implode($wordForIdn), 0, 8);
                        if(giftCard::where([['idnShortCode', $randIndNr],['statActive','1']])->first() == Null){
                            $newGC =new giftCard();
                         
                            $newGC->refId = 0;
                            $newGC->idnShortCode = $randIndNr;
                            $newGC->toRes = 0;
                            $newGC->gcType = 'chf';
                            $newGC->gcProdId = 0;
                            $newGC->gcProdSum = 0;
                            $newGC->gcProdSumUsed = 0;
                            $newGC->gcSumInChf = number_format(0,2,'.','');
                            $newGC->gcSumInChfUsed = number_format(0,2,'.','');
                            $newGC->clName = 'empty';
                            $newGC->clLastname = 'empty';
                            $newGC->clEmail = 'empty';
                            $newGC->clPhNr = 'empty';
                            $newGC->usedInOrdersId = 'empty';
                            $newGC->payM = 'empty';
                            $newGC->gcHash = 'empty';
                            $newGC->onlinePayStat = 0;
                            $newGC->soldByStaff = 0;
                            $newGC->expirationDate = 'empty';
                            $newGC->save();

                            $reqpeatThis = false;

                            $newGCSaleCard = new giftCardToSell();
                            $newGCSaleCard->hashVal = $GChash;
                            $newGCSaleCard->idnShortCode = $randIndNr;
                            $newGCSaleCard->toGC = $newGC->id;
                            $newGCSaleCard->status = 'notSold';
                            $newGCSaleCard->save();

                            $name = $newGC->id;
                            $file1 = "storage/giftCardToSell/GC".$name.".png";
                            $useGCQRCode = QRCode::text($randIndNr.'|||'.$GChash)
                            ->setSize(64)
                            ->setMargin(0)
                            ->setOutfile($file1)
                            ->png();

                            $newGC->delete();
                            
                        }else{
                            $reqpeatThis = true;
                        }
                    }while($reqpeatThis);

                }
            }
        }
    }





    public function searchGCByCode(Request $req){
        
        $theGC = giftCard::where('idnShortCode',$req->gcId)->first();
        if( $theGC == Null ){
            return 'giftCardNotFound';
        }else{
            return $theGC;
        }
    }


    public function rechnungReminderGC(Request $req) {
        $giftCardId = (int) $req->emReId;

        $giftCard = giftCard::find($giftCardId);
        $giftCardRechnung = giftCardRechnungPay::where('gcId', $giftCardId)->first();

        if(!$giftCard){ return 'giftCardNotFound'; }

        $to_email = $giftCardRechnung->clEmail;
        $to_name = $giftCardRechnung->clName;
        $GCvalue = number_format($giftCard->gcSumInChf,2,'.','');
        $theRes = Restorant::find(Auth::user()->sFor);

        $data = array(
            'name'       => $to_name,
            'resName'    => $theRes->emri,
            "gcValue"    => $GCvalue,
            "gcDueDate"  => Carbon::parse($giftCardRechnung->created_at)->addDays($giftCardRechnung->clDaysToPay)->format('d-m-Y'),
            "gcId"       => $giftCardId,
            "gcHash"     => $giftCard->gcHash
        );

        $theRes = Restorant::find($giftCard->toRes);
        $theResEmri = $theRes->emri;
        view()->share('theGC', $giftCard);
        $customPaper = array(0, 0, 340.16, 750 + (2 * 21));
        $pdfBill = PDF::loadView('GiftCardInvoice')->setPaper($customPaper, 'potrait');

        // Auf rechnung bill
        view()->share('items', $giftCard);
        $pdfARe1 = PDF::loadView('adminInvoiceRechnungGC')->setPaper('a4', 'portrait');
        $docName = 'rechnungBillFirst' . $theResEmri . '_' . $giftCardId . '.pdf';
        $pdfARe1->save('storage/giftCardRechnungBill/' . $docName);

        $pdfARe2 = PDF::loadView('adminInvoiceRechnungFinalGC')->setPaper('a4','portrait');

        $koha2D = explode('-', explode(' ', $giftCard->created_at)[0]);
        //------------------------------------------------------------------ 

        Mail::send('emails.giftCardRechnungReminder', $data, function($message) use ($to_name, $to_email, $pdfBill, $pdfARe2, $theResEmri, $giftCardId, $koha2D) {
            $message->to($to_email, $to_name)->subject("Zahlungserinnerung – Geschenkkarte bei $theResEmri");
            $message->from('noreply@qrorpa.ch','QRorpa');
            $message->attachData($pdfBill->output(), 'giftCardBill_' . $theResEmri . '_' . $giftCardId . '.pdf', [
                'mime' => 'application/pdf',
            ]);
            $message->attachData($pdfARe2->output(), 'Rechnung_GC-' . $giftCardId . '-QRorpa-' . $koha2D[2] . '_' . $koha2D[1] . '_' . $koha2D[0] . '.pdf', [
                'mime' => 'application/pdf',
            ]);
        });

        return 'Email sent!';
    }

    public function rechnungDownloadGC($giftCardId){
        $theGC = giftCard::find($giftCardId);

        if($theGC != null){
            $theRes = Restorant::find($theGC->toRes);
            $date = date_format($theGC->created_at, 'd_m_Y');
            view()->share('items', $theGC);
            view()->share('theGC', $theGC);
            $pdf = PDF::loadView('adminInvoiceRechnungFinalGC')->setPaper('a4', 'portrait');
            return $pdf->download('Rechnung_GC-' . $giftCardId . '-QRorpa-' . $date . '.pdf');
        }
    }
}
