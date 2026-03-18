<?php

namespace App\Http\Controllers;

use QRCode;
use App\User;
use App\Orders;
use App\TabOrder;
use App\Takeaway;
use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use App\TableQrcode;
use App\notifyClient;
use App\OrdersPassive;
use App\taDeForCookOr;
use App\Events\CartMsg;
use App\ordersTempForTA;
use App\onlinePayQRCStaf;
use App\OPSaferpayReference;
use Illuminate\Http\Request;
use App\cooksProductSelection;
use App\tablesAccessToWaiters;
use App\tabVerificationPNumbers;
use App\ordersTaOnlinePayStafTemp;
use Illuminate\Support\Facades\Auth;

class OnlinePayFromStafController313233 extends Controller
{
    


    private function genShifra($res){
        $orSh = rand(1111,9999);
        $orShFi = $res.'|'.$orSh;

        if(Orders::whereDate('created_at', Carbon::today())->where([['shifra', $orShFi],['statusi','<','2']])->first() != NULL){
            return $this->genShifra($res);
        }else{
            return $orShFi;
        }        
    }


    public function oPayFStafPayAllRes(Request $req){
        // onlPayRecId: opcfsId,
        // totalPay: totPay,

        $oPForCl = onlinePayQRCStaf::find($req->onlPayRecId);

        $spRef = new OPSaferpayReference();
        $spRef->toRes = $oPForCl->resId;
        $spRef->refPh = "empty";
        $spRef->orderId = 0;
        $spRef->theStat = 0;
        $spRef->save();

        if($req->skontoCHF > 0){
            $skontoCHF = number_format($req->skontoCHF,2,'.','');
            $toPay = number_format($req->totalPay - $oPForCl->giftCardDisc - $skontoCHF + $req->bakshishi,2,'.','');
        }else if($req->skontoPer > 0){
            $skontoCHF = number_format($req->totalPay*($req->skontoPer/100),2,'.','');
            $toPay = number_format($req->totalPay - $oPForCl->giftCardDisc - $skontoCHF + $req->bakshishi,2,'.','');
        }else{
            $skontoCHF = number_format(0,2,'.','');
            $toPay = number_format($req->totalPay - $oPForCl->giftCardDisc + $req->bakshishi,2,'.','');
        }

        $OnlinePayRegister = $req->totalPay.'|-|-|'.$req->onlPayRecId.'|-|-|'.$req->skontoCHF.'|-|-|'.$req->skontoPer.'|-|-|'.$req->bakshishi.'|-|-|'.$spRef->id.'|-|-|'.$skontoCHF.'|-|-|'.$oPForCl->giftCardDisc.'|-|-|'.$oPForCl->giftCardId.'|-|-|'.$oPForCl->resComment;
        $OnlinePayRegister2 = str_replace("&", "und",  $OnlinePayRegister);

        $payload = array(
            'RequestHeader' => array(
                'SpecVersion' => "1.7",
                'CustomerId' => "335189",
                'RequestId' => "aScdFewDSRFrfas2wsad3",
                'RetryIndicator' => 0,
                'ClientInfo' => array(
                    'ShopInfo' => "My Shop",
                    'OsInfo' => "Windows 10"
                    )
            ),
            'TerminalId' => "17750074",
            // 
            'Payment' => array(
                'Amount' => array(
                    'Value' => "1",
                    'CurrencyCode' => "CHF"
                ),
                'OrderId' => "SP_".$spRef->id,
                'PayerNote' => "A Note",
                'Description' => "Test_Order_123test"
            ),
            'Payer' => array(
                'IpAddress' => "192.168.178.1",
                'LanguageCode' => "en"
            ),
            'ReturnUrls' => array(
                'Success' => "https://qrorpa.ch/oPayFStafPayAllResRegOrd313233/?OPR=".$OnlinePayRegister2,
                'Fail' => "https://qrorpa.ch/?Res=".$oPForCl->resId."&t=".$oPForCl->tableNr
            ),
            'Notification' => array(
                'PayerEmail' => "clientOnlinePay@qrorpa.ch",
                'MerchantEmail' => "checkout@qrorpa.ch",
                'NotifyUrl' => "https://qrorpa.ch/?Res=".$oPForCl->resId."&t=".$oPForCl->tableNr
            ),
        );

        $payload['Payment']['Amount']['Value'] = strval( $toPay*100 ) ;
      
        //$username and $password for the http-Basic Authentication
        //$url is the SaferpayURL eg. https://www.saferpay.com/api/Payment/v1/PaymentPage/Initialize
        //$payload is a multidimensional array, that assembles the JSON structure. Example see above
        $username = "API_335189_86302817";
        $password = "t5$&8Hj6uI2&*&23sd";
        $url = "https://www.saferpay.com/api/Payment/v1/PaymentPage/Initialize";
            
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




    public function oPayFStafPayAllResRegOrd(Request $request){
       
        // $req->totalPay.'|-|-|'.$req->onlPayRecId.'|-|-|'.$req->skontoCHF.'|-|-|'.$req->skontoPer.'|-|-|'.$req->bakshishi;

        $requ = explode('|-|-|',$_GET['OPR']);
        $oPForCl = onlinePayQRCStaf::find($requ[1]);

        $theTabCode = TableQrcode::where([['Restaurant',$oPForCl->resId],['tableNr',$oPForCl->tableNr]])->first()->kaTab;

        $newOrder = new Orders;
        // $bakshishi =number_format((float)$req[3], 2, '.', '') ;
        $bakshishi =number_format((float)$requ[4], 2, '.', '') ;
        $allMyOr = array();

        foreach(TabOrder::where('tabCode',$theTabCode)->get() as $tOr){
            array_push($allMyOr,$tOr->id);
        }

        $totalPay = 0 ;
        $saveOrderAll = '';

        foreach(TabOrder::where('tabCode',$theTabCode)->get() as $tOr){
            $pnvRecord = tabVerificationPNumbers::where('tabOrderId',$tOr->id)->first();
            $pnvRecord->status = 0;
            $pnvRecord->specStat = 0;
            $pnvRecord->save();

            if($tOr->status != 9 ){
                if($tOr->OrderType != null){ $regType = explode('||',$tOr->OrderType)[0]; }else{  $regType = ''; }

                $theP = Produktet::find($tOr->prodId);
                if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
                
                if($saveOrderAll != ''){
                    $saveOrderAll .= "---8---".$tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$tOr->OrderSasia.'-8-'.$tOr->OrderQmimi
                    .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
                }else{
                    $saveOrderAll = $tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$tOr->OrderSasia.'-8-'.$tOr->OrderQmimi
                    .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
                }

                $totalPay += (float)$tOr->OrderQmimi;
            }
            $tOr->tabCode = 0;
            $tOr->status = 1;
            $tOr->specStat = 0;
            $tOr->save();
        }        

        $MinusVal = 0; 
        $ProdsVal = 'empty';

        if($oPForCl->tvsh == 2.60){
            $newOrder->nrTable = (int)500;
        }else{
            $newOrder->nrTable = (int)$oPForCl->tableNr;
        }
        $newOrder->statusi = 0;
        $newOrder->byId = 0;
        $newOrder->userEmri = 'none';
        $newOrder->userEmail = 'none';
        if($saveOrderAll == ''){
            $newOrder->porosia = 'empty';
        }else{
            $newOrder->porosia = $saveOrderAll; //$request->userPorosia;
        }
        $newOrder->resComment = $requ[9];
        $newOrder->payM = 'Online';
        $newOrder->shuma =number_format($totalPay, 2, '.', '') - $MinusVal - (0 * 0.01) + $bakshishi; //$request->Shuma
        $newOrder->Restaurant = (int)$oPForCl->resId;
        $newOrder->userPhoneNr = '0000000000';
        $newOrder->tipPer =  number_format((float)$requ[4], 2, '.', '') ;
        $newOrder->TAemri = 'none';
        $newOrder->TAmbiemri = 'none';
        $newOrder->TAtime = 'none';
        $newOrder->cuponOffVal = $MinusVal;
        $newOrder->cuponProduct = $ProdsVal;
       
        $refIfOrPa = OrdersPassive::where('Restaurant',(int)$oPForCl->resId)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',(int)$oPForCl->resId)->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->TAplz = 'empty';
        $newOrder->TAort = 'empty';
        $newOrder->TAaddress = 'empty';
        $newOrder->TAkoment = 'empty';

        $newOrder->discReason = $oPForCl->disReason;
        $newOrder->inCashDiscount = $oPForCl->cashDis;
        $newOrder->inPercentageDiscount = $oPForCl->percDis;

        if(TabOrder::where([['tableNr',(int)$oPForCl->tableNr],['toRes',(int)$oPForCl->resId],['tabCode','!=',0],['status','<',2]])->get()->count() <= 0){
            $tableGet = TableQrcode::where([['Restaurant',(int)$oPForCl->resId],['tableNr',(int)$oPForCl->tableNr]])->first();
            $tableGet->kaTab = 0;
            $tableGet->save();
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Gen the next indentifikation number (shifra - per Orders) 
            $orderSh = $this->genShifra((int)$oPForCl->resId);
            $_SESSION['trackMO'] = $orderSh;
            $newOrder->shifra = $orderSh;
            $newOrder->save();
        // ------------------------------------------------------------

        $theRefIns = OPSaferpayReference::find((int)$requ[5]);
        if($theRefIns != Null){
            $theRefIns->refPh = "SP_".$theRefIns->id;
            $theRefIns->orderId = $newOrder->id;
            $theRefIns->theStat = 1;
            $theRefIns->save();
        }

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

        // alert the admins
        foreach(User::where([['sFor',(int)$oPForCl->resId],['role','5']])->get() as $user){
            $details = [
                'id' => $newOrder->id,
                'type' => 'Order',
                'tableNr' => (int)$oPForCl->tableNr
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',(int)$oPForCl->resId],['role','55']])->get() as $oneWaiter){
            $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', (int)$oPForCl->tableNr]])->first();
            if($aToTable != NULL && $aToTable->statusAct == 1){
                // register the notification ...
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'Order',
                    'tableNr' => (int)$oPForCl->tableNr
                ];
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        foreach(User::where([['sFor',(int)$oPForCl->resId],['role','54']])->get() as $oneCook){
            $details = [
                'id' => $newOrder->id,
                'type' => 'Order',
                'tableNr' => (int)$oPForCl->tableNr
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }

        $toDoCart = 9;
        $sendPayAllOrMineSelected = 'none';
        $ToTable=$oPForCl->resId.'-0-'.$oPForCl->tableNr.'-0-'.$toDoCart;

        event(new CartMsg($ToTable));
        $phoneNrActive = array();
        $tVerNrForNotifications = tabVerificationPNumbers::where([['tabCode',$theTabCode],['status','1']])->get();

        foreach($tVerNrForNotifications as $nrVers){
            if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){
                array_push($phoneNrActive,$nrVers->phoneNr);
                // $payerPhNr
                $newNotifyClient = new notifyClient();
                $newNotifyClient->for = "CartMsg";
                $newNotifyClient->toRes = $oPForCl->resId;
                $newNotifyClient->tableNr = $oPForCl->resId;
                $newNotifyClient->clPhoneNr = $nrVers->tableNr;
                $newNotifyClient->data = json_encode([
                    'toDoCart' => $toDoCart,
                    'payAllOrMineSelected' => $sendPayAllOrMineSelected
                ]);
                $newNotifyClient->readInd = 0;
                $newNotifyClient->save();
            }
        }
        $oPForCl->orderId = $newOrder->id;
        $oPForCl->status = 1;
        $oPForCl->save();

        return redirect('/onlPayClPageFinish')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440))->withCookie(cookie('ghostCartReturn', 'not' , 5))->withCookie(cookie('retSessionCK', 'not', 20));
    }

   






































    public function oPayFStafPaySelectedRes(Request $req){
        // onlPayRecId: opcfsId,
        // totalPay: totPay,

        $oPForCl = onlinePayQRCStaf::find($req->onlPayRecId);

        $spRef = new OPSaferpayReference();
        $spRef->toRes = $oPForCl->resId;
        $spRef->refPh = "empty";
        $spRef->orderId = 0;
        $spRef->theStat = 0;
        $spRef->save();

        if($req->skontoCHF > 0){
            $skontoCHF = number_format($req->skontoCHF,2,'.','');
            $toPay = number_format($req->totalPay - $oPForCl->giftCardDisc - $skontoCHF + $req->bakshishi,2,'.','');
        }else if($req->skontoPer > 0){
            $skontoCHF = number_format($req->totalPay*($req->skontoPer/100),2,'.','');
            $toPay = number_format($req->totalPay - $oPForCl->giftCardDisc - $skontoCHF + $req->bakshishi,2,'.','');
        }else{
            $skontoCHF = number_format(0,2,'.','');
            $toPay = number_format($req->totalPay - $oPForCl->giftCardDisc + $req->bakshishi,2,'.','');
        }

        $OnlinePayRegister = $req->totalPay.'|-|-|'.$req->onlPayRecId.'|-|-|'.$req->skontoCHF.'|-|-|'.$req->skontoPer.'|-|-|'.$req->bakshishi.'|-|-|'.$spRef->id.'|-|-|'.$skontoCHF.'|-|-|'.$oPForCl->giftCardDisc.'|-|-|'.$oPForCl->giftCardId.'|-|-|'.$oPForCl->resComment;
        $OnlinePayRegister2 = str_replace("&", "und",  $OnlinePayRegister);

        $payload = array(
            'RequestHeader' => array(
                'SpecVersion' => "1.7",
                'CustomerId' => "335189",
                'RequestId' => "aScdFewDSRFrfas2wsad3",
                'RetryIndicator' => 0,
                'ClientInfo' => array(
                    'ShopInfo' => "My Shop",
                    'OsInfo' => "Windows 10"
                    )
            ),
            'TerminalId' => "17750074",
            // 
            'Payment' => array(
                'Amount' => array(
                    'Value' => "1",
                    'CurrencyCode' => "CHF"
                ),
                'OrderId' => "SP_".$spRef->id,
                'PayerNote' => "A Note",
                'Description' => "Test_Order_123test"
            ),
            'Payer' => array(
                'IpAddress' => "192.168.178.1",
                'LanguageCode' => "en"
            ),
            'ReturnUrls' => array(
                'Success' => "https://qrorpa.ch/oPayFStafPaySelectedResRegOrd313233/?OPR=".$OnlinePayRegister2,
                'Fail' => "https://qrorpa.ch/?Res=".$oPForCl->resId."&t=".$oPForCl->tableNr
            ),
            'Notification' => array(
                'PayerEmail' => "clientOnlinePay@qrorpa.ch",
                'MerchantEmail' => "checkout@qrorpa.ch",
                'NotifyUrl' => "https://qrorpa.ch/?Res=".$oPForCl->resId."&t=".$oPForCl->tableNr
            ),
        );

        $payload['Payment']['Amount']['Value'] = strval( $toPay*100 ) ;
      
        //$username and $password for the http-Basic Authentication
        //$url is the SaferpayURL eg. https://www.saferpay.com/api/Payment/v1/PaymentPage/Initialize
        //$payload is a multidimensional array, that assembles the JSON structure. Example see above
        $username = "API_335189_86302817";
        $password = "t5$&8Hj6uI2&*&23sd";
        $url = "https://www.saferpay.com/api/Payment/v1/PaymentPage/Initialize";
            
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

    public function oPayFStafPaySelectedResRegOrd(Request $request){
        $requ = explode('|-|-|',$_GET['OPR']);
        $oPForCl = onlinePayQRCStaf::find($requ[1]);

        $theTabCode = TableQrcode::where([['Restaurant',$oPForCl->resId],['tableNr',$oPForCl->tableNr]])->first()->kaTab;

        $newOrder = new Orders;
        // $bakshishi =number_format((float)$req[3], 2, '.', '') ;
        $bakshishi =number_format((float)$requ[4], 2, '.', '') ;
        $allMyOr = array();

        foreach(explode('||',$oPForCl->prodSelected) as $oneTabOrSel){ array_push($allMyOr,$oneTabOrSel); }
     
        $totalPay = 0 ;
        $saveOrderAll = '';

        foreach(TabOrder::whereIn('id',$allMyOr)->get() as $tOr){
            $pnvRecord = tabVerificationPNumbers::where('tabOrderId',$tOr->id)->first();
            $pnvRecord->status = 0;
            $pnvRecord->specStat = 0;
            $pnvRecord->save();

            if($tOr->status != 9 ){
                if($tOr->OrderType != null){ $regType = explode('||',$tOr->OrderType)[0]; }else{  $regType = ''; }

                $theP = Produktet::find($tOr->prodId);
                if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }

                if($saveOrderAll != ''){
                    $saveOrderAll .= "---8---".$tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$tOr->OrderSasia.'-8-'.$tOr->OrderQmimi
                    .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
                }else{
                    $saveOrderAll = $tOr->OrderEmri."-8-".$tOr->OrderPershkrimi."-8-".$tOr->OrderExtra.'-8-'.$tOr->OrderSasia.'-8-'.$tOr->OrderQmimi
                    .'-8-'.$regType.'-8-'.$tOr->OrderKomenti.'-8-'.$tOr->prodId.'-8-'.$grId;
                }
                $totalPay += (float)$tOr->OrderQmimi;
            }
            $tOr->tabCode = 0;
            $tOr->status = 1;
            $tOr->specStat = 0;
            $tOr->save();
        }        

        $MinusVal = 0; 
        $ProdsVal = 'empty';

        if($oPForCl->tvsh == 2.60){
            $newOrder->nrTable = (int)500;
        }else{
            $newOrder->nrTable = (int)$oPForCl->tableNr;
        }
        $newOrder->statusi = 0;
        $newOrder->byId = 0;
        $newOrder->userEmri = 'none';
        $newOrder->userEmail = 'none';
        if($saveOrderAll == ''){
            $newOrder->porosia = 'empty';
        }else{
            $newOrder->porosia = $saveOrderAll; //$request->userPorosia;
        }
        $newOrder->resComment = $requ[9];
        $newOrder->payM = 'Online';
        $newOrder->shuma =number_format($totalPay, 2, '.', '') - $MinusVal - (0 * 0.01) + $bakshishi; //$request->Shuma
        $newOrder->Restaurant = (int)$oPForCl->resId;
        $newOrder->userPhoneNr = '0000000000';
        $newOrder->tipPer =  number_format((float)$requ[4], 2, '.', '') ;
        $newOrder->TAemri = 'none';
        $newOrder->TAmbiemri = 'none';
        $newOrder->TAtime = 'none';
        $newOrder->cuponOffVal = $MinusVal;
        $newOrder->cuponProduct = $ProdsVal;
    
        $refIfOrPa = OrdersPassive::where('Restaurant',(int)$oPForCl->resId)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',(int)$oPForCl->resId)->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->TAplz = 'empty';
        $newOrder->TAort = 'empty';
        $newOrder->TAaddress = 'empty';
        $newOrder->TAkoment = 'empty';

        $newOrder->discReason = $oPForCl->disReason;
        $newOrder->inCashDiscount = $oPForCl->cashDis;
        $newOrder->inPercentageDiscount = $oPForCl->percDis;

        if(TabOrder::where([['tableNr',(int)$oPForCl->tableNr],['toRes',(int)$oPForCl->resId],['tabCode','!=',0],['status','<',2]])->get()->count() <= 0){
            $tableGet = TableQrcode::where([['Restaurant',(int)$oPForCl->resId],['tableNr',(int)$oPForCl->tableNr]])->first();
            $tableGet->kaTab = 0;
            $tableGet->save();
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Gen the next indentifikation number (shifra - per Orders) 
            $orderSh = $this->genShifra((int)$oPForCl->resId);
            $_SESSION['trackMO'] = $orderSh;
            $newOrder->shifra = $orderSh;
            $newOrder->save();
        // ------------------------------------------------------------

        $theRefIns = OPSaferpayReference::find((int)$requ[5]);
        if($theRefIns != Null){
            $theRefIns->refPh = "SP_".$theRefIns->id;
            $theRefIns->orderId = $newOrder->id;
            $theRefIns->theStat = 1;
            $theRefIns->save();
        }

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

        // alert the admins
        foreach(User::where([['sFor',(int)$oPForCl->resId],['role','5']])->get() as $user){
            $details = [
                'id' => $newOrder->id,
                'type' => 'Order',
                'tableNr' => (int)$oPForCl->tableNr
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',(int)$oPForCl->resId],['role','55']])->get() as $oneWaiter){
            $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', (int)$oPForCl->tableNr]])->first();
            if($aToTable != NULL && $aToTable->statusAct == 1){
                // register the notification ...
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'Order',
                    'tableNr' => (int)$oPForCl->tableNr
                ];
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        foreach(User::where([['sFor',(int)$oPForCl->resId],['role','54']])->get() as $oneCook){
            $details = [
                'id' => $newOrder->id,
                'type' => 'Order',
                'tableNr' => (int)$oPForCl->tableNr
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }
        
        $toDoCart = 7;
        $sendPayAllOrMineSelected = 'none';
        $ToTable=$oPForCl->resId.'-0-'.$oPForCl->tableNr.'-0-'.$toDoCart;
        
        event(new CartMsg($ToTable));
        $phoneNrActive = array();
        $tVerNrForNotifications = tabVerificationPNumbers::where([['tabCode',$theTabCode],['status','1']])->get();
        
        foreach($tVerNrForNotifications as $nrVers){
            if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){
                array_push($phoneNrActive,$nrVers->phoneNr);
                // $payerPhNr
                $newNotifyClient = new notifyClient();
                $newNotifyClient->for = "CartMsg";
                $newNotifyClient->toRes = $oPForCl->resId;
                $newNotifyClient->tableNr = $oPForCl->resId;
                $newNotifyClient->clPhoneNr = $nrVers->tableNr;
                $newNotifyClient->data = json_encode([
                    'toDoCart' => $toDoCart,
                    'payAllOrMineSelected' => $sendPayAllOrMineSelected
                ]);
                $newNotifyClient->readInd = 0;
                $newNotifyClient->save();
            }
        }
        $oPForCl->orderId = $newOrder->id;
        $oPForCl->status = 1;
        $oPForCl->save();
        
        return redirect('/onlPayClPageFinish')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440))->withCookie(cookie('ghostCartReturn', 'not' , 5))->withCookie(cookie('retSessionCK', 'not', 20));
    }































    public function OnlinePayInitiateTA(Request $req){
        $newOPStaf = new onlinePayQRCStaf();
        $newOPStaf->resId = $req->resId;
        $newOPStaf->tableNr = 500;
        $newOPStaf->qrCodeTP = "none";
        $newOPStaf->disReason = $req->disReason;
        $newOPStaf->cashDis = $req->cashDis;
        $newOPStaf->percDis = $req->percDis;
        $newOPStaf->giftCardDisc = $req->discGCAmnt;
        $newOPStaf->giftCardId = $req->discGCId;
        $newOPStaf->tvsh = 0;
        $newOPStaf->thePayM = "Online";
        $newOPStaf->payType = "payAll";
        $newOPStaf->save();



        $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
        shuffle($word);
        $name = substr(implode($word), 0, 16).'-onlinePayStaf-'.$newOPStaf->id;
        $file = "storage/OnlinePayStaf/".$name.".png";

        $qrCHashname = substr(implode($word), 0, 64);
        $newQrcode = QRCode::URL('https://qrorpa.ch/oPayFStafOpenOrder?ops='.$newOPStaf->id.'&h='.$qrCHashname)
                    ->setSize(64)
                    ->setMargin(0)
                    ->setOutfile($file)
                    ->png();


        $newOPStaf->qrCodeTP = $name.".png";
        $newOPStaf->qrCodeHash = $qrCHashname;
        $newOPStaf->save();

        $toPayTA = "";
        $totalPay = 0 ;
        $nextOrNr = ordersTaOnlinePayStafTemp::all()->max('orderNr')+1;
        foreach(ordersTempForTA::where([['toRes',Auth::user()->sFor],['fromWo',Auth::user()->id],['theStatus','0']])->get() as $tempTAOr){
            $tempTAOr->forOnlPayStaf = $newOPStaf->id;
            $tempTAOr->save();
            if( $toPayTA == ""){ $toPayTA = $tempTAOr->id;
            }else{ $toPayTA .= "|||".$tempTAOr->id; }

            $totPrice = number_format((float)$tempTAOr->proSasia * (float)$tempTAOr->proQmimi,2,'.','');
            $totalPay += (float)$totPrice;

            $regToOnlTemp = new ordersTaOnlinePayStafTemp();
            $regToOnlTemp->orderNr = $nextOrNr;
            $regToOnlTemp->taProdId = $tempTAOr->taProdId;
            $regToOnlTemp->taProdName = $tempTAOr->taProdName;
            $regToOnlTemp->taProdCatName = $tempTAOr->taProdCatName;
            $regToOnlTemp->taProdDsc = $tempTAOr->taProdDsc;
            $regToOnlTemp->taProdMwst = $tempTAOr->taProdMwst;
            $regToOnlTemp->toRes = $tempTAOr->toRes;
            $regToOnlTemp->fromWo = $tempTAOr->fromWo;
            $regToOnlTemp->proSasia = $tempTAOr->proSasia;
            $regToOnlTemp->proQmimi = $tempTAOr->proQmimi;
            $regToOnlTemp->proExtra = $tempTAOr->proExtra;
            $regToOnlTemp->proType = $tempTAOr->proType;
            $regToOnlTemp->procomm = $tempTAOr->procomm;
            $regToOnlTemp->theStatus = $tempTAOr->theStatus;
            $regToOnlTemp->onlinePayRef = $newOPStaf->id;
            $regToOnlTemp->save();

            $tempTAOr->delete();
        }

        $newOPStaf->totPay = $totalPay;
        $newOPStaf->prodSelected = $toPayTA;
        $newOPStaf->save();

        return $name.".png";
    }




    public function oPayFStafPayTakeaway(Request $req){
        // onlPayRecId: opcfsId,
        // totalPay: totPay,

        $oPForCl = onlinePayQRCStaf::find($req->onlPayRecId);

        $spRef = new OPSaferpayReference();
        $spRef->toRes = $oPForCl->resId;
        $spRef->refPh = "empty";
        $spRef->orderId = 0;
        $spRef->theStat = 0;
        $spRef->save();

        if($req->skontoCHF > 0){
            $skontoCHF = number_format($req->skontoCHF,2,'.','');
            $toPay = number_format($req->totalPay - $skontoCHF + $req->bakshishi,2,'.','');
        }else if($req->skontoPer > 0){
            $skontoCHF = number_format($req->totalPay*($req->skontoPer/100),2,'.','');
            $toPay = number_format($req->totalPay - $skontoCHF + $req->bakshishi,2,'.','');
        }else{
            $skontoCHF = number_format(0,2,'.','');
            $toPay = number_format($req->totalPay + $req->bakshishi,2,'.','');
        }

        $OnlinePayRegister = $req->totalPay.'|-|-|'.$req->onlPayRecId.'|-|-|'.$req->skontoCHF.'|-|-|'.$req->skontoPer.'|-|-|'.$req->bakshishi.'|-|-|'.$spRef->id.'|-|-|'.$skontoCHF;
        $OnlinePayRegister2 = str_replace("&", "und",  $OnlinePayRegister);

        $payload = array(
            'RequestHeader' => array(
                'SpecVersion' => "1.7",
                'CustomerId' => "335189",
                'RequestId' => "aScdFewDSRFrfas2wsad3",
                'RetryIndicator' => 0,
                'ClientInfo' => array(
                    'ShopInfo' => "My Shop",
                    'OsInfo' => "Windows 10"
                    )
            ),
            'TerminalId' => "17750074",
            // 
            'Payment' => array(
                'Amount' => array(
                    'Value' => "1",
                    'CurrencyCode' => "CHF"
                ),
                'OrderId' => "SP_".$spRef->id,
                'PayerNote' => "A Note",
                'Description' => "Test_Order_123test"
            ),
            'Payer' => array(
                'IpAddress' => "192.168.178.1",
                'LanguageCode' => "en"
            ),
            'ReturnUrls' => array(
                'Success' => "https://qrorpa.ch/oPayFStafPayTakeawayRegOrd313233/?OPR=".$OnlinePayRegister2,
                'Fail' => "https://qrorpa.ch/?Res=".$oPForCl->resId."&t=".$oPForCl->tableNr
            ),
            'Notification' => array(
                'PayerEmail' => "clientOnlinePay@qrorpa.ch",
                'MerchantEmail' => "checkout@qrorpa.ch",
                'NotifyUrl' => "https://qrorpa.ch/?Res=".$oPForCl->resId."&t=".$oPForCl->tableNr
            ),
        );

        $payload['Payment']['Amount']['Value'] = strval( $toPay*100 ) ;
      
        //$username and $password for the http-Basic Authentication
        //$url is the SaferpayURL eg. https://www.saferpay.com/api/Payment/v1/PaymentPage/Initialize
        //$payload is a multidimensional array, that assembles the JSON structure. Example see above
        $username = "API_335189_86302817";
        $password = "t5$&8Hj6uI2&*&23sd";
        $url = "https://www.saferpay.com/api/Payment/v1/PaymentPage/Initialize";
            
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

    public function oPayFStafPayTakeawayRegOrd(Request $request){
        $requ = explode('|-|-|',$_GET['OPR']);
        $oPForCl = onlinePayQRCStaf::find($requ[1]);

        $totalPay = 0 ;
        $saveOrderAll = '';

        foreach(ordersTaOnlinePayStafTemp::where('onlinePayRef',$oPForCl->id)->get() as $taOr){
            
            if($taOr->proType != null && $taOr->proType != "empty"){ $regType = explode('||',$taOr->proType)[0]; }else{  $regType = ''; }

            $theP = Takeaway::find($taOr->taProdId);
            if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }

            if($saveOrderAll != ''){
                $saveOrderAll .= "---8---".$taOr->taProdName."-8-".$taOr->taProdDsc."-8-".$taOr->proExtra.'-8-'.$taOr->proSasia.'-8-'.$taOr->proQmimi
                .'-8-'.$regType.'-8-'.$taOr->procomm.'-8-'.$taOr->taProdId.'-8-'.$grId;
            }else{
                $saveOrderAll = $taOr->taProdName."-8-".$taOr->taProdDsc."-8-".$taOr->proExtra.'-8-'.$taOr->proSasia.'-8-'.$taOr->proQmimi
                .'-8-'.$regType.'-8-'.$taOr->procomm.'-8-'.$taOr->taProdId.'-8-'.$grId;
            }
            $totalPay += number_format((float)$taOr->proQmimi * $taOr->proSasia,2,'.','');

            $taOr->delete();
        } 

        $MinusVal = 0; 
        $ProdsVal = 'empty';

        $newOrder = new Orders;
        $bakshishi =number_format((float)$requ[4], 2, '.', '');

        $newOrder->nrTable = (int)$oPForCl->tableNr;
        $newOrder->statusi = 0;
        if(Auth::check()){
        $newOrder->byId = Auth::user()->id;
        $newOrder->userEmri = Auth::user()->name;
        $newOrder->userEmail = Auth::user()->email;
        }else{
        $newOrder->byId = 0;
        $newOrder->userEmri = 'empty';
        $newOrder->userEmail = 'empty';
        }

        $newOrder->porosia = $saveOrderAll;
        $newOrder->payM = 'Online';
        $newOrder->shuma =number_format((float)$totalPay - $MinusVal + $bakshishi, 2, '.', '');
        $newOrder->Restaurant = (int)$oPForCl->resId;
        $newOrder->userPhoneNr = '0000000000';;
        $newOrder->tipPer =  $bakshishi;
        $newOrder->TAemri = 'D_Client';
        $newOrder->TAmbiemri = 'D_Client';
        $newOrder->TAtime = 'D_Client';
        $newOrder->cuponOffVal = $MinusVal;
        $newOrder->cuponProduct = $ProdsVal;
       
        $refIfOrPa = OrdersPassive::where('Restaurant',(int)$oPForCl->resId)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',(int)$oPForCl->resId)->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->TAplz = 'empty';
        $newOrder->TAort = 'empty';
        $newOrder->TAaddress = 'empty';
        $newOrder->TAkoment = 'empty';

        $newOrder->discReason = $oPForCl->disReason;
        $newOrder->inCashDiscount = $oPForCl->cashDis;
        $newOrder->inPercentageDiscount = $oPForCl->percDis;

        // Gen the next indentifikation number (shifra - per Orders) 
            $orderSh = $this->genShifra((int)$oPForCl->resId);
            $_SESSION['trackMO'] = $orderSh;
            $newOrder->shifra = $orderSh;
            $newOrder->save();
        // ------------------------------------------------------------

        // SaferPay reference , link it with the order 
        $theRefIns = OPSaferpayReference::find((int)$requ[5]);
        if($theRefIns != Null){
            $theRefIns->refPh = "SP_".$theRefIns->id;
            $theRefIns->orderId = $newOrder->id;
            $theRefIns->theStat = 1;
            $theRefIns->save();
        }
        // ------------------------------------------------------------

        // save orders for the COOK
            foreach(explode('---8---',$newOrder->porosia) as $orOne){
                $orOne2D = explode('-8-',$orOne);
                $taProd = Takeaway::find($orOne2D[7]);

                $newOrForCook = new taDeForCookOr();
                $newOrForCook->toRes = (int)$oPForCl->resId;
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
                if($orOne2D[5] != '' && $orOne2D[5] != ' '){$newOrForCook->prodType =  $orOne2D[5];
                }else{$newOrForCook->prodType =  'empty';}
                if($orOne2D[2] != '' && $orOne2D[2] != ' '){$newOrForCook->prodExtra =  $orOne2D[2];
                }else{$newOrForCook->prodExtra =  'empty';}

                $newOrForCook->prodQmimi = (float)$orOne2D[4];
                $newOrForCook->prodComm = $orOne2D[6];
                $newOrForCook->prodSasia = $orOne2D[3];
                $newOrForCook->prodSasiaDone = 0;
                $newOrForCook->save();
            }
        // -------------------------------------------------------------------

        // Send Notifications for the Admin
        foreach(User::where([['sFor',(int)$oPForCl->resId],['role','5']])->get() as $user){
            $details = [
                'id' => $newOrder->id,
                'type' => 'OrderTakeaway',
                'tableNr' => (int)$oPForCl->tableNr
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',(int)$oPForCl->resId],['role','55']])->get() as $oneWaiter){
            // register the notification ...
            $details = [
                'id' => $newOrder->id,
                'type' => 'OrderTakeaway',
                'tableNr' => (int)$oPForCl->tableNr
            ];
            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',(int)$oPForCl->resId],['role','54']])->get() as $oneCook){
            if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Takeaway']])->first() != NULL){
                // register the notification ...
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'cookPanelUpdateTaNewOr',
                    'prodId' => '0'
                ];
                $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
            }
        }
        // event(new newOrder($text));

        $oPForCl->orderId = $newOrder->id;
        $oPForCl->status = 1;
        $oPForCl->save();

        return redirect('/onlPayClPageFinish')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440))->withCookie(cookie('ghostCartReturn', 'not' , 5))->withCookie(cookie('retSessionCK', 'not', 20));

    }
}
