<?php

namespace App\Http\Controllers;

use Cart;
use QRCode;
use App\User;

use App\Cupon;
use App\ekstra;
use App\Orders;
use App\TipLog;
use App\TabOrder;
use App\Takeaway;
use App\LlojetPro;
use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use App\DeliveryPLZ;

use App\TableQrcode;
use App\DeliveryProd;
use App\notifyClient;
use App\OrdersPassive;
use App\taDeForCookOr;
use App\Events\CartMsg;
use App\ghostCartInUse;
use App\DeliverySchedule;
use App\couponUsedPhoneNr;
use App\deliveryPlzCharge;
use App\OPSaferpayReference;
use Illuminate\Http\Request;
use SpryngApiHttpPhp\Client;
use App\cooksProductSelection;
use App\tablesAccessToWaiters;
use Payrexx\Payrexx as Payrexx;
use App\tabVerificationPNumbers;
use App\Events\removePaidProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use PostFinanceCheckout\Sdk\ApiClient;
use Payrexx\Models\Request\Invoice as Invoice;
use PostFinanceCheckout\Sdk\Model\LineItemType;
use Payrexx\PayrexxException as PayrexxException;
use PostFinanceCheckout\Sdk\Model\LineItemCreate;
use PostFinanceCheckout\Sdk\Model\TransactionCreate;
use Payrexx\Models\Request\Gateway as PayrexxGateway;
use Payrexx\Models\Request\SignatureCheck as SignatureCheck;

// use vendor\postfinancecheckout\sdk\lib\Model\LineItemCreate;
// use vendor\postfinancecheckout\sdk\lib\Model\LineItemType;
// use vendor\postfinancecheckout\sdk\lib\Model\TransactionCreate;


class OnlinePaymentsController extends Controller
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

    private function genShifra($res){
        $orSh = rand(1111,9999);
        $orShFi = $res.'|'.$orSh;

        if(Orders::whereDate('created_at', Carbon::today())->where([['shifra', $orShFi],['statusi','<','2']])->first() != NULL){
            return $this->genShifra($res);
        }else{
            return $orShFi;
        }        
    }



    public function payResTakeawayOnline(Request $req){
        if(Cart::count() > 0){

        // Test With online payment 
        // Configuration
        $spaceId = 19938;
        $userId = 42967;
        $secret = 'U9d/HhTRVoWWAaMosdYtTIDu0ei0IGZYeoVbPPRMCD8=';

          // Setup API client
          $client = new ApiClient($userId, $secret);

            // Loop throw products
            foreach(Cart::content() as $item){
                // Create transaction
                $lineItem = new LineItemCreate();
                $lineItem->setName($item->name);
                $lineItem->setUniqueId(time());
                $lineItem->setSku($item->options->persh);
                $lineItem->setQuantity(1);
                    // Qmimi statik per testime -> Cart::total();  
                $lineItem->setAmountIncludingTax(00.01);
                $lineItem->setType(LineItemType::PRODUCT);

                $transactionPayload = new TransactionCreate();
                $transactionPayload->setCurrency('CHF');
                $transactionPayload->setLineItems(array($lineItem));
                $transactionPayload->setAutoConfirmationEnabled(true);

                $transaction = $client->getTransactionService()->create($spaceId, $transactionPayload);

                // Create Payment Page URL:
                $redirectionUrl = $client->getTransactionPaymentPageService()->paymentPageUrl($spaceId, $transaction->getId());

                // header('Location: ' . $redirectionUrl);
            }



            $newOrder = new Orders;

            $bakshishi =number_format($req->tipValueConfirmValue, 2, '.', '') ;

            $newOrder->nrTable = $req->t;
            $newOrder->statusi = 0;
            $newOrder->byId = $req->userIdCash;
            $newOrder->userEmri = 'empty';
            $newOrder->userEmail = 'empty';
            $newOrder->porosia =  $req->userPorosia;
            $newOrder->payM = $req->userPayM;
            $newOrder->shuma =number_format($req->Shuma, 2, '.', '')-$req->codeUsedValue2 - ($req->pointsUsing  * 0.01) + $bakshishi;
            $newOrder->Restaurant = $req->Res;
            $newOrder->userPhoneNr = 'empty';
            $newOrder->tipPer =  $bakshishi;
            $newOrder->TAemri ='empty';
            $newOrder->TAmbiemri = 'empty';
            $newOrder->TAtime = 'empty';
            $newOrder->cuponOffVal = $req->codeUsedValue2;
       
            $refIfOrPa = OrdersPassive::where('Restaurant',(int)$req->Res)->max('refId') + 1;
            $refIfOr = Orders::where('Restaurant',(int)$req->Res)->max('refId') + 1;
            $nextRefId = 0;
            if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
            $newOrder->refId = $nextRefId;

            $newOrder->TAplz = 'empty';
            $newOrder->TAort = 'empty';
            $newOrder->TAaddress = 'empty';
            $newOrder->TAkoment = 'empty';


            $theR = Restorant::find($req->Res);
            $theR->cashPayOrders += 1;
            $theR->save();

            if(Cart::total() >= Restorant::find($req->Res)->priceFree && $req->freeShotPh2 != 0){
                $newOrder->freeProdId =  $req->freeShotPh2;
            }
           

            // Gen the next indentifikation number (shifra - per Orders) 
				$orderSh = $this->genShifra($req->Res);
				$_SESSION['trackMO'] = $orderSh;
				$newOrder->shifra = $orderSh;
				$newOrder->save();
            // ------------------------------------------------------------


            $this->emptyTheCarFunRek();
            return redirect('/order')->with('success','');
        }else{
            // $this->emptyTheCarFunRek();
            return redirect('/order')->with('error','');
        }
        return redirect('/order');
    }

    private function emptyTheCarFunRek(){
        Cart::destroy();
        if(Cart::count() > 0){
            $this->emptyTheCarFunRek();
        }
    }







    
    // Restaurant payment 

    public function saferPayQrorpa(Request $req){

        $spRef = new OPSaferpayReference();
        $spRef->toRes = $req->res;
        $spRef->refPh = "empty";
        $spRef->orderId = 0;
        $spRef->theStat = 0;
        $spRef->save();

        $OnlinePayRegister = $req->theTotOnCart.'|-|-|'.$req->res."|-|-|".$req->t."|-|-|".$req->tipVal."|-|-|".$req->tipTit."|-|-|".$req->payAllOrMine."|-|-|".$req->payAllOrMineSelected."|-|-|".$req->payAllOrMineProSelected."|-|-|".$req->codeUsed."|-|-|".$req->freeShotId."|-|-|".$req->ghostCode."|-|-|".$req->clPhNumber.'|-|-|'.$req->ghostPayId.'|-|-|'.$req->codeUsedVal.'|-|-|'.$spRef->id;
        $OnlinePayRegister2 = str_replace("&", "und",  $OnlinePayRegister);

        $payload = array(
            'RequestHeader' => array(
                'SpecVersion' => "1.7",
                'CustomerId' => "259813",
                'RequestId' => "aScdFewDSRFrfas2wsad3",
                'RetryIndicator' => 0,
                'ClientInfo' => array(
                    'ShopInfo' => "My Shop",
                    'OsInfo' => "Windows 10"
                    )
            ),
            'TerminalId' => "17746921",
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
                'Success' => "http://qrorpa.ch/onlinePaySaferPayQrorpaRegister/?OPR=".$OnlinePayRegister2,
                'Fail' => "http://qrorpa.ch/?Res=".$req->res."&t=".$req->t
            ),
            'Notification' => array(
                'PayerEmail' => "erikthaqi1@amail.com",
                'MerchantEmail' => "checkout@qrorpa.ch",
                'NotifyUrl' => "http://qrorpa.ch/?Res=".$req->res."&t=".$req->t
            ),
        );

        $payload['Payment']['Amount']['Value'] = strval( $req->theTotOnCart*100 ) ;
        if($req->res == 22 || $req->res == 23){
            $payload['PaymentMethods'] = array("TWINT");
        }

        //$username and $password for the http-Basic Authentication
        //$url is the SaferpayURL eg. https://www.saferpay.com/api/Payment/v1/PaymentPage/Initialize
        //$payload is a multidimensional array, that assembles the JSON structure. Example see above
        $username = "API_259813_10756315";
        $password = "hybHBjb3vg45vh55";
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

    public function saferPayQrorpaRegister(){
        //0  console.log('theTotOnCart               :'$('.totalOnCart').html());
        //1  console.log('res                        :'+res);
        //2  console.log('t                          :'+t);
        //3  console.log('tipVal                     :'+$('.tipValueConfirmValueCLA').val());
        //4  console.log('tipTit                     :'+$('.tipValueConfirmTitleCLA').val());
        //5  console.log('payAllOrMine               :'+$('#payAllOrMineF1').val());
        //6  console.log('payAllOrMineSelected       :'+$('#payThisTooSelected').val());
        //7  console.log('payAllOrMineProSelected    :'+$('#payThisTooProSelected').val());
        //8  console.log('codeUsed                   :'+$('#codeUsedValueID').val());
        //9  console.log('freeShotId                 :'+$('#freeShotPh1Id').val());
        //10 console.log('ghostCode                  :'+$('#hasGhostCodeF1').val());
        //11 console.log('clPhNumber                 :'+$('#thisClPhoneNr').val());
        //12 ghostPayId
        // "13||500||0||0||Chicken Nuggets-8-8 Stk. mit Pommes-8--8-1-8-14-8--8--8-354||Cash||14.00||0||0||"

        $req = explode('|-|-|',$_GET['OPR']);
  
        // Deaktivizo numrin GHOSTCart nese egziston--------------------------------------------------------------------------------------------
            if($req[10] != 0){
                $findGCActive = ghostCartInUse::where([['toRes',(int)$req[1]],['tableNr',(int)$req[2]],['indNr2',(int)$req[10]],['status','0']])->first();
                if($findGCActive != NULL){ 
                    $findGCActive->status = 1; 
                    $findGCActive->save();
                }
            }
        //--------------------------------------------------------------------------------------------------------------------------------------

        $newOrder = new Orders;
        $bakshishi =number_format((float)$req[3], 2, '.', '') ;
        $allMyOr = array();

        foreach(Cart::content() as $item){
            foreach(tabVerificationPNumbers::where('phoneNr',$req[11])->get() as $verRecord){
                array_push($allMyOr,$verRecord->tabOrderId);
            }  
        }
   
        $totalPay = 0 ;
        $TabC = TableQrcode::where([['Restaurant',(int)$req[1]],['tableNr',(int)$req[2]]])->first()->kaTab;
        $saveOrderAll = '';

        // 1, pay mine  7, pay mine and these   9, pay all 
        if((int)$req[5] == 1){
            if((int)$req[12] == 0){ $AllFromTab = TabOrder::where([['tableNr',(int)$req[2]],['toRes',(int)$req[1]],['tabCode','!=',0],['tabCode',$TabC]])->whereIn('id',$allMyOr)->get();
            }else{ $AllFromTab = TabOrder::where([['tableNr',(int)$req[2]],['toRes',(int)$req[1]],['tabCode','!=',0],['tabCode',$TabC],['specStat',(int)$req[12]]])->whereIn('id',$allMyOr)->get();}
        }else if((int)$req[5] == 7){
            foreach(explode('||',$req[6]) as $nrsToPay){
                if((int)$req[12]== 0){ foreach(tabVerificationPNumbers::where('phoneNr',$nrsToPay)->get() as $nrVerRecords){array_push($allMyOr,$nrVerRecords->tabOrderId);}
                }else{ 
                    foreach(tabVerificationPNumbers::where([['phoneNr',$nrsToPay],['specStat',(int)$req[12]]])->get() as $nrVerRecords){ 
                        array_push($allMyOr,$nrVerRecords->tabOrderId);
                        $sendRemoveProd = $nrVerRecords->phoneNr.'||'.$nrVerRecords->tabOrderId.'||a';
                        event(new removePaidProduct($sendRemoveProd));
                    }
                }
            }
            if($req[7] != ''){
                foreach(explode('||',$req[7]) as $prodsToPay){
                    array_push($allMyOr,$prodsToPay);
                    $verCodeRec = tabVerificationPNumbers::where('tabOrderId',$prodsToPay)->first(); 
                    $sendRemoveProd = $verCodeRec->phoneNr.'||'.$prodsToPay.'||a';
                    event(new removePaidProduct($sendRemoveProd));
                }
            }
            $AllFromTab = TabOrder::where([['tableNr',(int)$req[2]],['toRes',(int)$req[1]],['tabCode','!=',0],['tabCode',$TabC]])->whereIn('id',$allMyOr)->get();
        }else if((int)$req[5] == 9){
            if((int)$req[12]== 0){
                $AllFromTab = TabOrder::where([['tableNr',(int)$req[2]],['toRes',(int)$req[1]],['tabCode','!=',0],['tabCode',$TabC]])->get();
            }else{
                $AllFromTab = TabOrder::where([['tableNr',(int)$req[2]],['toRes',(int)$req[1]],['tabCode','!=',0],['tabCode',$TabC],['specStat',(int)$req[12]]])->get();
            }
        }
        //--------------------------------------------------------------------------------------------------------------------------------------

        $tVerNrForNotifications = tabVerificationPNumbers::where([['tabCode',$TabC],['status','1']])->get();

        foreach($AllFromTab as $tOr){
            // Deactivate phone Nr verification 
            $pnvRecord = tabVerificationPNumbers::where('tabOrderId',$tOr->id)->first();
            $pnvRecord->status = 0;
            $pnvRecord->save();

            if($tOr->status != 9 ){
                if($tOr->OrderType != null){ $regType = explode('||',$tOr->OrderType)[0]; }else{  $regType = ''; }
                if((int)$req[2] == 500){
                    $theP = Takeaway::find($tOr->prodId);
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
            }
            if($tOr->status != 9 ){
                $totalPay += (float)$tOr->OrderQmimi;
            }

            $tOr->tabCode = 0;
            $tOr->status = 1;
            $tOr->specStat = 0;
            $tOr->save();

            $pnvRecord->specStat = 0;
            $pnvRecord->save();

            // Deaktivizo numrin GHOSTCart nese egziston per klientit tjeter per te cilin behet pagesa

             if(str_contains($pnvRecord->phoneNr,'|')){
                // $request->t $request->Res
                $clPhoneNr2D = explode('|',$pnvRecord->phoneNr);
                $findGCActive = ghostCartInUse::where([['toRes',(int)$req[1]],['tableNr',(int)$req[2]],['indNr2',$clPhoneNr2D[1]],['status','0']])->first();
                if($findGCActive != NULL){
                    $findGCActive->status = 1;
                    $findGCActive->save();
                }
            }
            //---------------------------------------
        }


        if($saveOrderAll != ''){
            if($req[8] != ''){
                $cou = Cupon::find($req[8]);
                if($cou != Null){
                    if($cou->typeCo == 1){
                        $beforeTotal =number_format($totalPay-(float)$req[3], 2, '.', '');
                        // $MinusVal = number_format(($cou['valueOff']*0.01) * $beforeTotal, 2, '.', '');
                        $MinusVal = number_format($req[13], 2, '.', '');
                        $ProdsVal = 'empty';
                    }else if($cou->typeCo == 2){
                        $MinusVal = $cou['valueOffMoney']; $ProdsVal = 'empty';
                    }else if($cou->typeCo == 3){
                        $MinusVal = 0; $ProdsVal = $cou['prodName'];
                    }
                    $cUsing  = new couponUsedPhoneNr;
                    $cUsing->toRes = (int)$req[1];
                    $cUsing->phoneNr =  $req[11];
                    $cUsing->couponId =$cou->id;
                    $cUsing->save();
                }else{
                    $MinusVal = 0; $ProdsVal = 'empty';
                }
            }else{
                $MinusVal = 0; $ProdsVal = 'empty';
            }

            $newOrder->nrTable = (int)$req[2];
            $newOrder->statusi = 0;
            $newOrder->byId = 0;
            $newOrder->userEmri = 'none';
            $newOrder->userEmail = 'none';
            if($saveOrderAll == ''){
                $newOrder->porosia = 'empty';
            }else{
                $newOrder->porosia = $saveOrderAll; //$request->userPorosia;
            }
            $newOrder->payM = 'Online';
            $newOrder->shuma =number_format($totalPay, 2, '.', '')-$MinusVal - (0 * 0.01) + $bakshishi; //$request->Shuma
            $newOrder->Restaurant = (int)$req[1];
            $newOrder->userPhoneNr = $req[11];
            $newOrder->tipPer =  number_format((float)$req[3], 2, '.', '') ;
            $newOrder->TAemri = 'none';
            $newOrder->TAmbiemri = 'none';
            $newOrder->TAtime = 'none';
            $newOrder->cuponOffVal = $MinusVal;
            $newOrder->cuponProduct = $ProdsVal;
  
            $refIfOrPa = OrdersPassive::where('Restaurant',(int)$req[1])->max('refId') + 1;
            $refIfOr = Orders::where('Restaurant',(int)$req[1])->max('refId') + 1;
            $nextRefId = 0;
            if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
            $newOrder->refId = $nextRefId;

            $newOrder->TAplz = 'empty';
            $newOrder->TAort = 'empty';
            $newOrder->TAaddress = 'empty';
            $newOrder->TAkoment = 'empty';

            $theR = Restorant::find((int)$req[1]);
            $theR->cashPayOrders += 1;
            $theR->save();

            if(TabOrder::where([['tableNr',(int)$req[2]],['toRes',(int)$req[1]],['tabCode','!=',0],['status','<',2]])->get()->count() <= 0){
                $tableGet = TableQrcode::where([['Restaurant',(int)$req[1]],['tableNr',(int)$req[2]]])->first();
                $tableGet->kaTab = 0;
                $tableGet->save();
            }
            if($totalPay >= Restorant::find((int)$req[1])->priceFree && $req[9] != 0){
                $newOrder->freeProdId =  $req[9];
            }
        
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            // Gen the next indentifikation number (shifra - per Orders) 
				$orderSh = $this->genShifra((int)$req[1]);
				$_SESSION['trackMO'] = $orderSh;
				$newOrder->shifra = $orderSh;
				$newOrder->save();
            // ------------------------------------------------------------
            $newOrder->save();

            $theRefIns = OPSaferpayReference::find((int)$req[14]);
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


            // tip / Bakshishi 
            if($req[3] != 0){
               $newTip = new TipLog;

               $newTip->shumaPor =number_format((float)($req[0] + $bakshishi) - (0  * 0.01), 2, '.', '') ;
               $newTip->tipPer = $req[4];
               $newTip->tipTot = (float)$req[3];
               $newTip->toRes = $req[1];
               if(Auth::check()){
                   $newTip->klienti = Auth::user()->id;
               }else{
                   $newTip->klienti = 9999999;
               }

               $newTip->save();
            }
            // -------------------------------------------------------------------------------------------------------------------

            if(!Auth::check()){
                unset($_SESSION['phoneNrVerified']);
            }
            
            if((int)$req[12] != 0){
                unset($_SESSION['adminToClProdsRec']);
            }

            // alert the admins
            foreach(User::where([['sFor',(int)$req[1]],['role','5']])->get() as $user){
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'Order',
                    'tableNr' => (int)$req[2]
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
            foreach(User::where([['sFor',(int)$req[1]],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', (int)$req[2]]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'Order',
                        'tableNr' => (int)$req[2]
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            foreach(User::where([['sFor',(int)$req[1]],['role','54']])->get() as $oneCook){
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'Order',
                    'tableNr' => (int)$req[2]
                ];
                $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
            }
            // -------------------------------------------------------------------------------------------------------------------

            if((int)$req[5] == 9){
                $toDoCart = 9;
                $sendPayAllOrMineSelected = 'none';
                $ToTable=$req[1].'-0-'.$req[2].'-0-'.$toDoCart;
            }else if((int)$req[5] == 7){
                $toDoCart = 7;
                $sendPayAllOrMineSelected = $req[6];
                $ToTable=$req[1].'-0-'.$req[2].'-0-'.$toDoCart.'-0-'.$req[6];
            }else if($req[5] == 1){
                $toDoCart = 1;
                $sendPayAllOrMineSelected = 'none';
                $ToTable=$req[1].'-0-'.$req[2].'-0-'.$toDoCart;
            }
            event(new CartMsg($ToTable));
            $phoneNrActive = array();

            foreach($tVerNrForNotifications as $nrVers){
                if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){
                    array_push($phoneNrActive,$nrVers->phoneNr);
                    // $payerPhNr
                    $newNotifyClient = new notifyClient();
                    $newNotifyClient->for = "CartMsg";
                    $newNotifyClient->toRes = $req[1];
                    $newNotifyClient->tableNr = $req[2];
                    $newNotifyClient->clPhoneNr = $nrVers->phoneNr;
                    $newNotifyClient->data = json_encode([
                        'toDoCart' => $toDoCart,
                        'payAllOrMineSelected' => $sendPayAllOrMineSelected
                    ]);
                    $newNotifyClient->readInd = 0;
                    $newNotifyClient->save();
                }
            }

            Cart::destroy();
            if(Cart::content()->count() > 0){
                // $this->emptyTheCarFunRek();
            }

            return redirect('/order')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440))->withCookie(cookie('ghostCartReturn', 'not' , 5))->withCookie(cookie('retSessionCK', 'not', 20));

        }else{
            Cart::destroy();
            if(Cart::content()->count() > 0){
                // $this->emptyTheCarFunRek();
            }
            return redirect('/order')->with('success', 'Es wurde erfolgreich bestätigt!');
            
        }
    }


































    public function onlineTakeawayReceivePhNr(Request $req){
        $sendTo = 445566 ;
        if(substr($req->phNr, 0, 1) == 0){
            $pref =substr($req->phNr, 0, 3);
            if($pref == '075' || $pref == '076' || $pref == '077' || $pref == '078' || $pref == '079'){
                if(strlen($req->phNr) == 10){
                    $sendTo = '41'.substr($req->phNr, 1, 9);
                    $phToTestForSMS = substr($req->phNr, 1, 9);
                }
            }
            
        }else{
            $pref =substr($req->phNr, 0, 2);
            if($pref == '75' || $pref == '76' || $pref == '77' || $pref == '78' || $pref == '79'){
                $sendTo = '41'.$req->phNr;
                $phToTestForSMS = $req->phNr;
            }
        }
        if($sendTo != 445566){
            $randCode = rand(111111,999999);

            $sendTo2 = (int)$sendTo;
            $nowTime = date('Y-m-d h:i:s');

            if($req->codeUsedId != 0 && couponUsedPhoneNr::where([['phoneNr','0'.$phToTestForSMS],['couponId',$req->codeUsedId]])->first() != Null){
                return 'couponUsed';
            }else if ($req->codeUsedId != 0 && couponUsedPhoneNr::where([['phoneNr',$phToTestForSMS],['couponId',$req->codeUsedId]])->first() != Null){
                return 'couponUsed';
            }else{
                // disa numra perdoren per Demo 
                if($phToTestForSMS != '763270293' && $phToTestForSMS != '763251809' && $phToTestForSMS != '763459941' && $phToTestForSMS != '763469963'){
                    $spryng = new Client('QRorpasms', 'Spryng@qrorpa.ch', 'qrorpa.ch');
                    if($spryng->sms->checkBalance() > 0){
                        try {
                            $spryng->sms->send($sendTo2,'Ihr Sicherheitscode ist: '.$randCode.' . Er lauft in 5 Minuten ab.', array(
                                'route'     => 'business',
                                'allowlong' => true,
                                )
                            );
                        }catch (InvalidRequestException $e){
                            dd ($e->getMessage());
                        }
                    }
                }

                return $sendTo.'||'.$randCode;
            }
        }else{
            return 'falseNR';
        }

    }
    public function onlineTakeawayReceiveCode(Request $req){
        //9 phNr: phoneNrFCl2,
        // codeByCl: codeByCl,
        // codeCreated: $('#TAOnlinePayClCodeCreated').val(),
        //1 res: resId,
        //2 t: tId,
        //3 tipval: $('#tipValueConfirmValueOPay').val(),
        //4 tiptitle: $('#tipValueConfirmTitleOPay').val(),
        //5 userPorosiaOPay: $('#userPorosiaOPay').val(),
        //6 userPayMOPay: $('#userPayMOPay').val(),
        //0 ShumaOPay: $('#ShumaOPay').val(),
        //7 codeUsedValueID2: $('#codeUsedValueID2').val(),
        //8 freeShotPh2Id: $('#freeShotPh2Id').val(),
        //10 clName: $('#nameTA01').val(),
        //11 clLastname: $('#lastnameTA01').val(),
        //12 clTime: $('#timeTA01').val(),
        if($req->codeByCl != $req->codeCreated){
            return 'falseCode';
        }else{
            // order reference instance
            $spRef = new OPSaferpayReference();
            $spRef->toRes = $req->res;
            $spRef->refPh = "empty";
            $spRef->orderId = 0;
            $spRef->theStat = 0;
            $spRef->save();

            $OnlinePayTARegister = $req->ShumaOPay.'|-|-|'.$req->res.'|-|-|'.$req->t.'|-|-|'.$req->tipval.'|-|-|'.$req->tiptitle.'|-|-|'.$req->userPorosiaOPay.'|-|-|'.$req->userPayMOPay.'|-|-|'.$req->codeUsedValueID2.'|-|-|'.$req->freeShotPh2Id.'|-|-|'.$req->phNr.'|-|-|'.$req->clName.'|-|-|'.$req->clLastname.'|-|-|'.$req->clTime.'|-|-|'.$req->codeUsedId.'|-|-|'.$spRef->id.'|-|-|'.$req->bowlingNr;
            $OnlinePayTARegister2 = str_replace("&", "und",  $OnlinePayTARegister);

            // reg number to thusers account
            if(Auth::check() && $req->regNrToUsr == 'Yes'){
                $theU = User::find(Auth::user()->id);
                if($theU != Null){
                    $theU->	phoneNr = $req->phNr;
                    $theU->save();
                }
            }

            $payload = array(
                'RequestHeader' => array(
                    'SpecVersion' => "1.7",
                    'CustomerId' => "259813",
                    'RequestId' => "aScdFewDSRFrfas2wsad3",
                    'RetryIndicator' => 0,
                    'ClientInfo' => array(
                        'ShopInfo' => "My Shop",
                        'OsInfo' => "Windows 10"
                        )
                ),
                'TerminalId' => "17746921",
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
                    'Success' => "http://qrorpa.ch/onlinePaySaferPayQrorpaTakeawayRegister/?OPR=".$OnlinePayTARegister2,
                    'Fail' => "http://qrorpa.ch/?Res=".$req->res."&t=500"
                ),
                'Notification' => array(
                    'PayerEmail' => "erikthaqi1@amail.com",
                    'MerchantEmail' => "checkout@qrorpa.ch",
                    'NotifyUrl' => "http://qrorpa.ch/?Res=".$req->res."&t=500"
                ),
                
            );

            $toPay = (float)$req->tipval+(float)$req->ShumaOPay-(float)$req->codeUsedValueID2;
    
            $payload['Payment']['Amount']['Value'] = strval( $toPay*100 ) ;
            if($req->res == 22 || $req->res == 23){
                $payload['PaymentMethods'] = array("TWINT");
            }
    
            $username = "API_259813_10756315";
            $password = "hybHBjb3vg45vh55";
            $url = "https://test.saferpay.com/api/Payment/v1/PaymentPage/Initialize";
                
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-type: application/json","Accept: application/json; charset=utf-8"));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
            $jsonResponse = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($status != 200) {
                $body = json_decode(curl_multi_getcontent($curl), true);
                $response = array(
                    "status" => $status . " <|> " . curl_error($curl),
                    "body" => $body
                );
            }else{
                $body = json_decode($jsonResponse, true);
                $response = array(
                    "status" => $status,
                    "body" => $body
                );
            }
            curl_close($curl);
            return $response;
        }

    }


    public function saferPayQrorpaTakeawayRegister(){

        //9 phNr: phoneNrFCl2,
        // codeByCl: codeByCl,
        // codeCreated: $('#TAOnlinePayClCodeCreated').val(),
        //1 res: resId,
        //2 t: tId,
        //3 tipval: $('#tipValueConfirmValueOPay').val(),
        //4 tiptitle: $('#tipValueConfirmTitleOPay').val(),
        //5 userPorosiaOPay: $('#userPorosiaOPay').val(),
        //6 userPayMOPay: $('#userPayMOPay').val(),
        //0 ShumaOPay: $('#ShumaOPay').val(),
        //7 codeUsedValueID2: $('#codeUsedValueID2').val(),
        //8 freeShotPh2Id: $('#freeShotPh2Id').val(),
        //10 clName: $('#nameTA01').val(),
        //11 clLastname: $('#lastnameTA01').val(),
        //12 clTime: $('#timeTA01').val(),

        if(Cart::count() > 0){
            $req = explode('|-|-|',$_GET['OPR']);

            if($req[13] != '' && $req[13] > 0){
                $cou = Cupon::find($req[13]);
                if($cou->typeCo == 1){
                    $beforeTotal =number_format((float)$req[0]-(float)$req[3], 2, '.', '');
                    // $MinusVal = number_format(($cou['valueOff']*0.01) * $beforeTotal, 2, '.', '');
                    $MinusVal = number_format($req[7],2, '.', '');
                    $ProdsVal = 'empty';
                }else if($cou->typeCo == 2){
                    $MinusVal = $cou['valueOffMoney']; $ProdsVal = 'empty';
                }else if($cou->typeCo == 3){
                    $MinusVal = 0; $ProdsVal = $cou['prodName'];
                }
                $cUsing  = new couponUsedPhoneNr;
                $cUsing->toRes = (int)$req[1];
                $cUsing->phoneNr =  $req[9];
                $cUsing->couponId =$cou->id;
                $cUsing->save();
            }else{
                $MinusVal = 0; $ProdsVal = 'empty';
            }

            $newPorosia = '';
            foreach(explode('---8---',$req[5]) as $orOne){
                $orOnr2D = explode('-8-',$orOne);
                $theP = Takeaway::find($orOnr2D[7]);
                if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
                if($newPorosia == ''){
                    $newPorosia = $orOne.'-8-'.$grId;
                }else{
                    $newPorosia .= '---8---'.$orOne.'-8-'.$grId;
                }
            }
                        
            $newOrder = new Orders;

            $bakshishi =number_format((float)$req[3], 2, '.', '') ;

            $newOrder->nrTable = (int)$req[2];
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
        
            $newOrder->porosia = $newPorosia;
            $newOrder->payM = 'Online';
            $newOrder->shuma =number_format((float)$req[0], 2, '.', '')-(float)$req[7] - (0 * 0.01) + $bakshishi;
            $newOrder->Restaurant = (int)$req[1];
            $newOrder->userPhoneNr = $req[9];
            $newOrder->tipPer =  $bakshishi;
            $newOrder->TAemri = $req[10];
            $newOrder->TAmbiemri = $req[11];
            $newOrder->TAtime = $req[12];
            $newOrder->cuponOffVal = $MinusVal;
            $newOrder->cuponProduct = $ProdsVal;
            
            $refIfOrPa = OrdersPassive::where('Restaurant',(int)$req[1])->max('refId') + 1;
            $refIfOr = Orders::where('Restaurant',(int)$req[1])->max('refId') + 1;
            $nextRefId = 0;
            if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
            $newOrder->refId = $nextRefId;

            $newOrder->TAplz = 'empty';
            $newOrder->TAort = 'empty';
            $newOrder->TAaddress = 'empty';
            $newOrder->TAkoment = 'empty';
            $newOrder->TAbowlingLine = (int)$req[15];

            $theR = Restorant::find((int)$req[1]);
            $theR->cashPayOrders += 1;
            $theR->save();

            if(Cart::total() >= Restorant::find($req[1])->priceFree && (int)$req[8] != 0){
                $newOrder->freeProdId = (int)$req[8];
            }

            Cart::destroy();

            // Gen the next indentifikation number (shifra - per Orders) 
                $orderSh = $this->genShifra((int)$req[1]);
                $_SESSION['trackMO'] = $orderSh;
                $newOrder->shifra = $orderSh;
                $newOrder->save();
            // ------------------------------------------------------------

            $theRefIns = OPSaferpayReference::find((int)$req[14]);
            if($theRefIns != Null){
                $theRefIns->refPh = "SP_".$theRefIns->id;
                $theRefIns->orderId = $newOrder->id;
                $theRefIns->theStat = 1;
                $theRefIns->save();
            }

            // save orders for the COOK
                foreach(explode('---8---',$newOrder->porosia) as $orOne){
                    $orOne2D = explode('-8-',$orOne);
                    $taProd = Takeaway::find($orOne2D[7]);

                    $newOrForCook = new taDeForCookOr();
                    $newOrForCook->toRes = (int)$req[1];
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

            // Code per tip
            if((float)$req[3] != 0){
                $newTip = new TipLog;

                $newTip->shumaPor =number_format((float)((float)$req[0] + $bakshishi) - (0 * 0.01), 2, '.', '') ;
                $newTip->tipPer = $req[4];
                $newTip->tipTot =  number_format((float)$req[3], 2, '.', '') ;
                $newTip->toRes = $req[1];
                if(Auth::check()){
                    $newTip->klienti = Auth::user()->id;
                }else{
                    $newTip->klienti = 9999999;
                }
                $newTip->save();
            } 

            
            Cart::destroy();

            // Send Notifications for the Admin
            foreach(User::where([['sFor',(int)$req[1]],['role','5']])->get() as $user){
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'OrderTakeaway',
                    'tableNr' => (int)$req[2]
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
            foreach(User::where([['sFor',(int)$req[1]],['role','55']])->get() as $oneWaiter){
                // register the notification ...
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'OrderTakeaway',
                    'tableNr' => (int)$req[2]
                ];
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
            foreach(User::where([['sFor',(int)$req[1]],['role','54']])->get() as $oneCook){
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

            return redirect('/order')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440));
        }

    }






    public function onlineTakeawayUseUsrPNumber(Request $req){
        if ($req->codeUsedId != 0 && couponUsedPhoneNr::where([['phoneNr',$req->phNr],['couponId',$req->codeUsedId]])->first() != Null){
            return 'couponUsed';
        }else{
            // order reference instance
            $spRef = new OPSaferpayReference();
            $spRef->toRes = $req->res;
            $spRef->refPh = "empty";
            $spRef->orderId = 0;
            $spRef->theStat = 0;
            $spRef->save();

            $OnlinePayTARegister = $req->ShumaOPay.'|-|-|'.$req->res.'|-|-|'.$req->t.'|-|-|'.$req->tipval.'|-|-|'.$req->tiptitle.'|-|-|'.$req->userPorosiaOPay.'|-|-|'.$req->userPayMOPay.'|-|-|'.$req->codeUsedValueID2.'|-|-|'.$req->freeShotPh2Id.'|-|-|'.$req->phNr.'|-|-|'.$req->clName.'|-|-|'.$req->clLastname.'|-|-|'.$req->clTime.'|-|-|'.$req->codeUsedId.'|-|-|'.$spRef->id.'|-|-|'.$req->bowlingNr;
            $OnlinePayTARegister2 = str_replace("&", "und",  $OnlinePayTARegister);
        
            $payload = array(
                'RequestHeader' => array(
                    'SpecVersion' => "1.7",
                    'CustomerId' => "259813",
                    'RequestId' => "aScdFewDSRFrfas2wsad3",
                    'RetryIndicator' => 0,
                    'ClientInfo' => array(
                        'ShopInfo' => "My Shop",
                        'OsInfo' => "Windows 10"
                        )
                ),
                'TerminalId' => "17746921",
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
                    'Success' => "http://qrorpa.ch/onlinePaySaferPayQrorpaTakeawayRegister/?OPR=".$OnlinePayTARegister2,
                    'Fail' => "http://qrorpa.ch/?Res=".$req->res."&t=500"
                ),
                'Notification' => array(
                    'PayerEmail' => "erikthaqi1@amail.com",
                    'MerchantEmail' => "checkout@qrorpa.ch",
                    'NotifyUrl' => "http://qrorpa.ch/?Res=".$req->res."&t=500"
                ),
                
            );

            $toPay = (float)$req->tipval+(float)$req->ShumaOPay-(float)$req->codeUsedValueID2;
        
            $payload['Payment']['Amount']['Value'] = strval( $toPay*100 ) ;
            if($req->res == 22 || $req->res == 23){
                $payload['PaymentMethods'] = array("TWINT");
            }
        
            $username = "API_259813_10756315";
            $password = "hybHBjb3vg45vh55";
            $url = "https://test.saferpay.com/api/Payment/v1/PaymentPage/Initialize";
                
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-type: application/json","Accept: application/json; charset=utf-8"));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
            $jsonResponse = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($status != 200) {
                $body = json_decode(curl_multi_getcontent($curl), true);
                $response = array(
                    "status" => $status . " <|> " . curl_error($curl),
                    "body" => $body
                );
            }else{
                $body = json_decode($jsonResponse, true);
                $response = array(
                    "status" => $status,
                    "body" => $body
                );
            }
            curl_close($curl);
            return $response;
        }
    }

































    public function onlineDeliveryReceivePhNr(Request $req){
        // phNr: phoneNrFCl,
        $sendTo = 445566 ;
        if(substr($req->phNr, 0, 1) == 0){
            $pref =substr($req->phNr, 0, 3);
            if($pref == '075' || $pref == '076' || $pref == '077' || $pref == '078' || $pref == '079'){
                if(strlen($req->phNr) == 10){
                    $sendTo = '41'.substr($req->phNr, 1, 9);
                    $phToTestForSMS = substr($req->phNr, 1, 9);
                }
            }else{
                return 'falseNR';
            }
            
        }else{
            $pref =substr($req->phNr, 0, 2);
            if($pref == '75' || $pref == '76' || $pref == '77' || $pref == '78' || $pref == '79'){
                $sendTo = '41'.$req->phNr;
                $phToTestForSMS = $req->phNr;
            }else{
                return 'falseNR';
            }
        }

        if($sendTo != 445566){
            $randCode = rand(111111,999999);

            $sendTo2 = (int)$sendTo;
            $nowTime = date('Y-m-d h:i:s');

            // Numri i Besart Hazirit perdoret per Demo 
            if($phToTestForSMS != '763270293' && $phToTestForSMS != '763251809' && $phToTestForSMS != '763459941' && $phToTestForSMS != '763469963'){
                $spryng = new Client('QRorpasms', 'Spryng@qrorpa.ch', 'qrorpa.ch');
                if($spryng->sms->checkBalance() > 0){
                    try {
                        $spryng->sms->send($sendTo2,'Ihr Sicherheitscode ist: '.$randCode.' . Er lauft in 5 Minuten ab.', array(
                            'route'     => 'business',
                            'allowlong' => true,
                            )
                        );
                    }catch (InvalidRequestException $e){
                        dd ($e->getMessage());
                    }
                }
            }

            $todayWD = date('w'); //1-mon 2-tue 3 4 5 6 0
            $dSch = DeliverySchedule::where('toRes',$req->res)->first();
            if($dSch != NULL){
                if($todayWD == 1){
                    $start1 = $dSch->day11S; $end1 = $dSch->day11E; $start2 = $dSch->day21S; $end2 = $dSch->day21E;
                }else if($todayWD == 2){
                    $start1 = $dSch->day12S; $end1 = $dSch->day12E; $start2 = $dSch->day22S; $end2 = $dSch->day22E;
                }else if($todayWD == 3){
                    $start1 = $dSch->day13S; $end1 = $dSch->day13E; $start2 = $dSch->day23S; $end2 = $dSch->day23E;
                }else if($todayWD == 4){
                    $start1 = $dSch->day14S; $end1 = $dSch->day14E; $start2 = $dSch->day24S; $end2 = $dSch->day24E;
                }else if($todayWD == 5){
                    $start1 = $dSch->day15S; $end1 = $dSch->day15E; $start2 = $dSch->day25S; $end2 = $dSch->day25E;
                }else if($todayWD == 6){
                    $start1 = $dSch->day16S; $end1 = $dSch->day16E; $start2 = $dSch->day26S; $end2 = $dSch->day26E;
                }else if($todayWD == 0){
                    $start1 = $dSch->day10S; $end1 = $dSch->day10E; $start2 = $dSch->day20S; $end2 = $dSch->day20E;
                }
            }

            $userTime2D = explode(':',$req->clTime);
            if($userTime2D[0]<10){ $utP1 = '0'.$userTime2D[0]; }else{ $utP1 = $userTime2D[0]; }
            if($userTime2D[1]<10){ $utP2 = '0'.$userTime2D[1]; }else{ $utP2 = $userTime2D[1]; }
            $userTime = $utP1.':'.$utP2;
            
            
            if(DeliveryPLZ::where('toRes',$req->res)->get()->count() > 0){
                if(DeliveryPLZ::where([['toRes',$req->res],['plz',$req->plz]])->get()->count() > 0){

                    if($dSch != NULL && $start1 != '00:00'){
                        if($userTime >= $start1 && $userTime <= $end1 ){
                            return $sendTo2.'||'.$randCode;
                        }else{
                            if($dSch != NULL && $start2 != '00:00'){
                                if($userTime >= $start2 && $userTime <= $end2 ){
                                    return $sendTo2.'||'.$randCode;
                                }else{
                                    return 'falseDataTime';
                                }
                            }else{
                                // --------
                                return $sendTo2.'||'.$randCode;
                            }
                        }
                    }else if($dSch != NULL && $start2 != '00:00'){
                        if($userTime >= $start2 && $userTime <= $end2 ){
                            return $sendTo2.'||'.$randCode;
                        }else{
                            return 'falseDataTime';
                        }
                    }else{
                        return $sendTo2.'||'.$randCode;
                    }
                }else{
                    return 'falseDataAddr';
                }
            }else{
                if($dSch != NULL && $start1 != '00:00'){
                    if($userTime >= $start1 && $userTime <= $end1 ){
                        return $sendTo2.'||'.$randCode;
                    }else{
                        return 'falseDataTime';
                    }
                }else if($dSch != NULL && $start2 != '00:00'){
                    if($userTime >= $start2 && $userTime <= $end2 ){
                        return $sendTo2.'||'.$randCode;
                    }else{
                        return 'falseDataTime';
                    }
                }else{
                    return $sendTo2.'||'.$randCode;
                }
            }

            return $sendTo2.'||'.$randCode;

        }else{
            return 'falseNR';
        }
    }

    public function onlineDeliveryReceiveCode(Request $req){
            //0 phNr: 
            // codeByCl: 
            // codeCreated: 
            //1 res: 
            //2 tipVal: 
            //3 tipTitle: 
            //4 pointsUsed: 
            //5 theName: 
            //6 theLastname: 
            //7 adresa: 
            //8 email: 
            //9 theTime: 
            //10 plz: 
            //11 ort: 
            //12 theCom: 
            //13 codeVal: 
            //14 freeShot:  
            //15 userId:   
            //16 username: 
            //17 userEmail: 
            //18 shuma: 
            //19 theOrd: 

        if($req->codeByCl != $req->codeCreated){
            return 'falseCode';
        }else{
            $OnlinePayDERegister = $req->phNr.'|-|-|'.$req->res.'|-|-|'.$req->tipVal.'|-|-|'.$req->tipTitle.'|-|-|'.$req->pointsUsed.'|-|-|'.$req->theName.'|-|-|'.$req->theLastname.'|-|-|'.$req->adresa.'|-|-|'.$req->email.'|-|-|'.$req->theTime.'|-|-|'.$req->plz.'|-|-|'.$req->ort.'|-|-|'.$req->theCom.'|-|-|'.$req->codeVal.'|-|-|'.$req->freeShot.'|-|-|'.$req->userId.'|-|-|'.$req->username.'|-|-|'.$req->userEmail.'|-|-|'.$req->shuma.'|-|-|'.$req->theOrd;
            $OnlinePayDERegister2 = str_replace("&", "und",  $OnlinePayDERegister);
    
            $thePLZIns = DeliveryPLZ::where([['toRes',(int)$req->res],['plz',$req->plz]])->first();
            if($thePLZIns != Null){
                $plzExtChrg = number_format(0, 2, '.', ' ');
                foreach(deliveryPlzCharge::where('plzId',$thePLZIns->id)->get() as $plzChrgOne){
                    if((float)$req->shuma >= $plzChrgOne->priceFrom && (float)$req->shuma <= $plzChrgOne->priceTo){
                        $plzExtChrg = number_format($plzChrgOne->extraCharge, 2, '.', ' ');
                    }
                }
            }else{
                $plzExtChrg = 0;
            }

            $payload = array(
                'RequestHeader' => array(
                    'SpecVersion' => "1.7",
                    'CustomerId' => "259813",
                    'RequestId' => "aScdFewDSRFrfas2wsad3",
                    'RetryIndicator' => 0,
                    'ClientInfo' => array(
                        'ShopInfo' => "My Shop",
                        'OsInfo' => "Windows 10"
                        )
                ),
                'TerminalId' => "17746921",
                // 
                'Payment' => array(
                    'Amount' => array(
                        'Value' => "1",
                        'CurrencyCode' => "CHF"
                    ),
                    'OrderId' => "123456",
                    'PayerNote' => "A Note",
                    'Description' => "Test_Order_123test"
                ),
                'Payer' => array(
                    'IpAddress' => "192.168.178.1",
                    'LanguageCode' => "en"
                ),
                'ReturnUrls' => array(
                    'Success' => "http://qrorpa.ch/onlinePaySaferPayQrorpaDeliveryRegister/?OPR=".$OnlinePayDERegister2,
                    'Fail' => "http://qrorpa.ch/?Res=".$req->res."&t=9000"
                ),
                'Notification' => array(
                    'PayerEmail' => "erikthaqi1@amail.com",
                    'MerchantEmail' => "checkout@qrorpa.ch",
                    'NotifyUrl' => "http://qrorpa.ch/?Res=".$req->res."&t=9000"
                ),
                
            );

            $toPay = (float)$req->shuma+(float)$req->tipVal-(float)$req->codeVal+(float)$plzExtChrg;
    
            $payload['Payment']['Amount']['Value'] = strval( $toPay*100 ) ;
    
            $username = "API_259813_10756315";
            $password = "hybHBjb3vg45vh55";
            $url = "https://test.saferpay.com/api/Payment/v1/PaymentPage/Initialize";
                
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-type: application/json","Accept: application/json; charset=utf-8"));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
            $jsonResponse = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($status != 200) {
                $body = json_decode(curl_multi_getcontent($curl), true);
                $response = array(
                    "status" => $status . " <|> " . curl_error($curl),
                    "body" => $body
                );
            }else{
                $body = json_decode($jsonResponse, true);
                $response = array(
                    "status" => $status,
                    "body" => $body
                );
            }
            curl_close($curl);
            return $response;
        }
    }

    public function saferPayQrorpaDeliveryRegister(){

        $req = explode('|-|-|',$_GET['OPR']);

        $newOrder = new Orders;

        $bakshishi =number_format((float)$req[2], 2, '.', '') ;

        $thePLZIns = DeliveryPLZ::where([['toRes',(int)$req[1]],['plz',$req[10]]])->first();
        if($thePLZIns != Null){
            $plzExtChrg = number_format(0, 2, '.', ' ');
            foreach(deliveryPlzCharge::where('plzId',$thePLZIns->id)->get() as $plzChrgOne){
                if((float)$req[18] >= $plzChrgOne->priceFrom && (float)$req[18] <= $plzChrgOne->priceTo){
                    $plzExtChrg = number_format($plzChrgOne->extraCharge, 2, '.', ' ');
                }
            }
        }else{
            $plzExtChrg = 0;
        }

        $newOrder->nrTable = 9000;
        $newOrder->statusi = 0;
        $newOrder->byId = (int)$req[15];
        $newOrder->userEmri = $req[16];
        $newOrder->userEmail = $req[17];
        $newOrder->porosia =  $req[19];
        $newOrder->payM = 'Online';
        $newOrder->shuma =number_format((float)$req[18] + (float)$plzExtChrg, 2, '.', '')-(float)$req[13] - (0 * 0.01) + $bakshishi;
        $newOrder->Restaurant = (int)$req[1];
        $newOrder->userPhoneNr = $req[0];
        $newOrder->tipPer =  number_format((float)$req[2], 2, '.', '') ;
        $newOrder->TAemri = $req[5];
        $newOrder->TAmbiemri = $req[6];
        $newOrder->TAtime = $req[9];
        $newOrder->cuponOffVal = (float)$req[13];

        $refIfOrPa = OrdersPassive::where('Restaurant',(int)$req[1])->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',(int)$req[1])->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->TAplz = $req[10].'|||'.number_format($plzExtChrg, 2, '.', '');
        $newOrder->TAort = $req[11];
        $newOrder->TAaddress = $req[7];
        $newOrder->TAkoment = $req[12];

        $theR = Restorant::find((int)$req[1]);
        $theR->cashPayOrders += 1;
        $theR->save();

        if(Cart::total() >= Restorant::find((int)$req[1])->priceFree && $req[14] != 0){
            $newOrder->freeProdId =  $req[14];
        }

        // Gen the next indentifikation number (shifra - per Orders) 
        $orderSh = $this->genShifra((int)$req[1]);
        $_SESSION['trackMO'] = $orderSh;
        $newOrder->shifra = $orderSh;
        $newOrder->save();
        // ------------------------------------------------------------


        // save orders for the COOK
            foreach(explode('---8---',$newOrder->porosia) as $orOne){
                $orOne2D = explode('-8-',$orOne);
                $deProd = DeliveryProd::find($orOne2D[7]);

                $newOrForCook = new taDeForCookOr();
                $newOrForCook->toRes = (int)$req[1];
                $newOrForCook->serviceType = 2;
                $newOrForCook->orderId = $newOrder->id ;
                if($deProd != NULL){
                    $newOrForCook->prodCat = $deProd->kategoria;
                    $newOrForCook->prodId =  $deProd->prod_id;
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

        // Code per tip
        if($req[2] != 0){
            $newTip = new TipLog;
            $newTip->shumaPor =number_format((float)((float)$req[18] + $bakshishi) - (0 * 0.01), 2, '.', '') ;
            $newTip->tipPer = $req[3];
            $newTip->tipTot = (float)$req[2];
            $newTip->toRes = (int)$req[1];
            if(Auth::check()){
                $newTip->klienti = Auth::user()->id;
            }else{
                $newTip->klienti = 9999999;
            }
            $newTip->save();
        } 

        Cart::destroy();
        foreach(User::where([['sFor',(int)$req[1]],['role','5']])->get() as $user){
            $details = [
                'id' => $newOrder->id,
                'type' => 'OrderDelivery',
                'tableNr' => '9000'
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',(int)$req[1]],['role','55']])->get() as $oneWaiter){
            // register the notification ...
            $details = [
                'id' => $newOrder->id,
                'type' => 'OrderDelivery',
                'tableNr' => '9000'
            ];
            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',(int)$req[1]],['role','54']])->get() as $oneCook){
            if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Delivery']])->first() != NULL){
                // register the notification ...
                $details = [
                    'id' => '0',
                    'type' => 'cookPanelUpdateDe',
                    'prodId' => '0'
                ];
                $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
            }
        }
        // event(new newOrder($text));
        
        return redirect('/order')->with('success', $orderSh);
    }

    public function onlineDeliveryUseUsrPNumber(Request $req){
        $OnlinePayDERegister = $req->phNr.'|-|-|'.$req->res.'|-|-|'.$req->tipVal.'|-|-|'.$req->tipTitle.'|-|-|'.$req->pointsUsed.'|-|-|'.$req->theName.'|-|-|'.$req->theLastname.'|-|-|'.$req->adresa.'|-|-|'.$req->email.'|-|-|'.$req->theTime.'|-|-|'.$req->plz.'|-|-|'.$req->ort.'|-|-|'.$req->theCom.'|-|-|'.$req->codeVal.'|-|-|'.$req->freeShot.'|-|-|'.$req->userId.'|-|-|'.$req->username.'|-|-|'.$req->userEmail.'|-|-|'.$req->shuma.'|-|-|'.$req->theOrd;
        $OnlinePayDERegister2 = str_replace("&", "und",  $OnlinePayDERegister);

        $payload = array(
            'RequestHeader' => array(
                'SpecVersion' => "1.7",
                'CustomerId' => "259813",
                'RequestId' => "aScdFewDSRFrfas2wsad3",
                'RetryIndicator' => 0,
                'ClientInfo' => array(
                    'ShopInfo' => "My Shop",
                    'OsInfo' => "Windows 10"
                    )
            ),
            'TerminalId' => "17746921",
            // 
            'Payment' => array(
                'Amount' => array(
                    'Value' => "1",
                    'CurrencyCode' => "CHF"
                ),
                'OrderId' => "123456",
                'PayerNote' => "A Note",
                'Description' => "Test_Order_123test"
            ),
            'Payer' => array(
                'IpAddress' => "192.168.178.1",
                'LanguageCode' => "en"
            ),
            'ReturnUrls' => array(
                'Success' => "http://qrorpa.ch/onlinePaySaferPayQrorpaDeliveryRegister/?OPR=".$OnlinePayDERegister2,
                'Fail' => "http://qrorpa.ch/?Res=".$req->res."&t=9000"
            ),
            'Notification' => array(
                'PayerEmail' => "erikthaqi1@amail.com",
                'MerchantEmail' => "checkout@qrorpa.ch",
                'NotifyUrl' => "http://qrorpa.ch/?Res=".$req->res."&t=9000"
            ),
            
        );

        $toPay = (float)$req->tipVal+(float)$req->shuma-(float)$req->codeVal;

        $payload['Payment']['Amount']['Value'] = strval( $toPay*100 ) ;

        $username = "API_259813_10756315";
        $password = "hybHBjb3vg45vh55";
        $url = "https://test.saferpay.com/api/Payment/v1/PaymentPage/Initialize";
            
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-type: application/json","Accept: application/json; charset=utf-8"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
        $jsonResponse = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status != 200) {
            $body = json_decode(curl_multi_getcontent($curl), true);
            $response = array(
                "status" => $status . " <|> " . curl_error($curl),
                "body" => $body
            );
        }else{
            $body = json_decode($jsonResponse, true);
            $response = array(
                "status" => $status,
                "body" => $body
            );
        }
        curl_close($curl);
        return $response;
    }

    




































    public function payrexxQrorpa(Request $req){
        // totalOnCart: $('.totalOnCart').html(),
        // 0 resId: res,
        // 1 tabId: t,
        // 2 tipValueConfirmValueOPay: $('#tipValueConfirmValueOPay').val(),
        // 3 tipValueConfirmTitleOPay: $('#tipValueConfirmTitleOPay').val(),
        // 4 userPorosiaOPay: $('#userPorosiaOPay').val(),
        // 5 userPayMOPay: $('#userPayMOPay').val(),
        // 6 ShumaOPay: $('#ShumaOPay').val(),
        // 7 codeUsedValueID2: $('#codeUsedValueID2').val(),
        // 8 freeShotPh2Id: $('#freeShotPh2Id').val(),

        $OnlinePayRegister = $req->resId."-|-".$req->tabId."-|-".$req->tipValueConfirmValueOPay."-|-".$req->tipValueConfirmTitleOPay."-|-".$req->userPorosiaOPay."-|-".$req->userPayMOPay."-|-".$req->ShumaOPay."-|-".$req->codeUsedValueID2."-|-".$req->freeShotPh2Id;
        $OnlinePayRegister2 = str_replace("&", "und",  $OnlinePayRegister);
        
        spl_autoload_register(function($class) {
            $root = dirname(__DIR__);
            $classFile = $root . '/lib/' . str_replace('\\', '/', $class) . '.php';
            if (file_exists($classFile)) {
                require_once $classFile;
            }
        });
        
        // $instanceName is a part of the url where you access your payrexx installation.
        // https://{$instanceName}.payrexx.com
        $instanceName = 'ehc-chur';
        // $instanceName = 'qrorpa';
       
        
        // $secret is the payrexx secret for the communication between the applications
        // if you think someone got your secret, just regenerate it in the payrexx administration
        $secret = '9eMV0lqbiyf6BB1Sc7x9dOtaWLC0Me';
        // $secret = 'hV9gboKDTK9jOpGjDD9zh35x8ad3q6';
        
        $payrexx = new Payrexx($instanceName, $secret);
        
        $gateway = new PayrexxGateway();
        
        // amount multiplied by 100
        $gateway->setAmount($req->totalOnCart * 100);
        // $gateway->setAmount(0.01 * 100);
        
        // VAT rate percentage (nullable)
        $gateway->setVatRate(0);
        
        //Product SKU
        $gateway->setSku('P01122000');
        
        // currency ISO code
        $gateway->setCurrency('CHF');
        
        //success and failed url in case that merchant redirects to payment site instead of using the modal view
        $gateway->setSuccessRedirectUrl('http://qrorpa.ch/oPayEhcChurTakeawayRegister/?OPR='.$OnlinePayRegister2);
        $gateway->setFailedRedirectUrl('http://qrorpa.ch/?Res='.$req->resId.'&t='.$req->tabId);
        $gateway->setCancelRedirectUrl('http://qrorpa.ch/order');
        
        // empty array = all available psps
        $gateway->setPsp([]);
        //$gateway->setPsp(array(4));
        $gateway->setPm(['twint']);
        
        // optional: whether charge payment manually at a later date (type authorization)
        $gateway->setPreAuthorization(false);
        // optional: if you want to do a pre authorization which should be charged on first time
        //$gateway->setChargeOnAuthorization(true);
        
        // optional: whether charge payment manually at a later date (type reservation)
        $gateway->setReservation(false);
        
        // subscription information if you want the customer to authorize a recurring payment.
        // this does not work in combination with pre-authorization payments.
        //$gateway->setSubscriptionState(true);
        //$gateway->setSubscriptionInterval('P1M');
        //$gateway->setSubscriptionPeriod('P1Y');
        //$gateway->setSubscriptionCancellationInterval('P3M');
        
        // optional: reference id of merchant (e. g. order number)
        $gateway->setReferenceId(975382);
        //$gateway->setValidity(5);
        //$gateway->setLookAndFeelProfile('144be481');
        
        // optional: parse multiple products
        //$gateway->setBasket([
        //    [
        //        'name' => [
        //            1 => 'Dies ist der Produktbeispielname 1 (DE)',
        //            2 => 'This is product sample name 1 (EN)',
        //            3 => 'Ceci est le nom de l\'échantillon de produit 1 (FR)',
        //            4 => 'Questo è il nome del campione del prodotto 1 (IT)'
        //        ],
        //        'description' => [
        //            1 => 'Dies ist die Produktmusterbeschreibung 1 (DE)',
        //            2 => 'This is product sample description 1 (EN)',
        //            3 => 'Ceci est la description de l\'échantillon de produit 1 (FR)',
        //            4 => 'Questa è la descrizione del campione del prodotto 1 (IT)'
        //        ],
        //        'quantity' => 1,
        //        'amount' => 100
        //    ],
        //    [
        //        'name' => [
        //            1 => 'Dies ist der Produktbeispielname 2 (DE)',
        //            2 => 'This is product sample name 2 (EN)',
        //            3 => 'Ceci est le nom de l\'échantillon de produit 2 (FR)',
        //            4 => 'Questo è il nome del campione del prodotto 2 (IT)'
        //        ],
        //        'description' => [
        //            1 => 'Dies ist die Produktmusterbeschreibung 2 (DE)',
        //            2 => 'This is product sample description 2 (EN)',
        //            3 => 'Ceci est la description de l\'échantillon de produit 2 (FR)',
        //            4 => 'Questa è la descrizione del campione del prodotto 2 (IT)'
        //        ],
        //        'quantity' => 2,
        //        'amount' => 200
        //    ]
        //]);
        
        // optional: add contact information which should be stored along with payment
        // $gateway->addField($type = 'title', $value = 'mister');
        // $gateway->addField($type = 'forename', $value = 'Max');
        // $gateway->addField($type = 'surname', $value = 'Mustermann');
        // $gateway->addField($type = 'company', $value = 'Max Musterfirma');
        // $gateway->addField($type = 'street', $value = 'Musterweg 1');
        // $gateway->addField($type = 'postcode', $value = '1234');
        // $gateway->addField($type = 'place', $value = 'Musterort');
        // $gateway->addField($type = 'country', $value = 'AT');
        // $gateway->addField($type = 'phone', $value = '+43123456789');
        // $gateway->addField($type = 'email', $value = 'max.muster@payrexx.com');
        // $gateway->addField($type = 'date_of_birth', $value = '03.06.1985');
        // $gateway->addField($type = 'terms', '');
        // $gateway->addField($type = 'privacy_policy', '');
        $gateway->addField($type = 'custom_field_1', $value = '123456789', $name = array(
            1 => 'Benutzerdefiniertes Feld (DE)',
            2 => 'Benutzerdefiniertes Feld (EN)',
            3 => 'Benutzerdefiniertes Feld (FR)',
            4 => 'Benutzerdefiniertes Feld (IT)',
        ));
        
        try {
            $response = $payrexx->create($gateway);
            var_dump($response->getLink());
        } catch (\Payrexx\PayrexxException $e) {
            print $e->getMessage();
        }
    }




    public function ehcChurTakeawayRegister(){
        // 0 resId: res,
        // 1 tabId: t,
        // 2 tipValueConfirmValueOPay: $('#tipValueConfirmValueOPay').val(),
        // 3 tipValueConfirmTitleOPay: $('#tipValueConfirmTitleOPay').val(),
        // 4 userPorosiaOPay: $('#userPorosiaOPay').val(),
        // 5 userPayMOPay: $('#userPayMOPay').val(),
        // 6 ShumaOPay: $('#ShumaOPay').val(),
        // 7 codeUsedValueID2: $('#codeUsedValueID2').val(),
        // 8 freeShotPh2Id: $('#freeShotPh2Id').val(),

        $req = explode('-|-',$_GET['OPR']);
                     
        $newOrder = new Orders;

        $bakshishi =number_format((float)$req[2], 2, '.', '');

        $newOrder->nrTable = (int)$req[1];
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

        $newOrder->porosia =  $req[4];
        $newOrder->payM = 'Online';
        $newOrder->shuma =number_format((float)$req[6], 2, '.', '')-(float)$req[7] - (0 * 0.01) + $bakshishi;
        $newOrder->Restaurant = (int)$req[0];
        $newOrder->userPhoneNr = 'fromEhcChur';
        $newOrder->tipPer =  $bakshishi;
        $newOrder->TAemri = 'fromEhcChur';
        $newOrder->TAmbiemri = 'fromEhcChur';
        $newOrder->TAtime ='fromEhcChur';
        $newOrder->cuponOffVal = (float)$req[7];

        $refIfOrPa = OrdersPassive::where('Restaurant',(int)$req[0])->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',(int)$req[0])->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->TAplz = 'empty';
        $newOrder->TAort = 'empty';
        $newOrder->TAaddress = 'empty';
        $newOrder->TAkoment = 'empty';

        if(Cart::total() >= Restorant::find($req[0])->priceFree && (int)$req[8] != 0){
            $newOrder->freeProdId = (int)$req[8];
        }

        // Gen the next indentifikation number (shifra - per Orders) 
			$orderSh = $this->genShifra((int)$req[0]);
			$_SESSION['trackMO'] = $orderSh;
			$newOrder->shifra = $orderSh;
			$newOrder->save();
        // ------------------------------------------------------------

        // save orders for the COOK
            foreach(explode('---8---',$newOrder->porosia) as $orOne){
                $orOne2D = explode('-8-',$orOne);
                $taProd = Takeaway::find($orOne2D[7]);
    
                $newOrForCook = new taDeForCookOr();
                $newOrForCook->toRes = (int)$req[0];
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
        // Code per tip
          if((float)$req[2] != 0){
            $newTip = new TipLog;

            $newTip->shumaPor =number_format((float)((float)$req[6] + $bakshishi) - (0 * 0.01), 2, '.', '') ;
            $newTip->tipPer = $req[3];
            $newTip->tipTot =  number_format((float)$req[2], 2, '.', '') ;
            $newTip->toRes = $req[0];
            if(Auth::check()){
                $newTip->klienti = Auth::user()->id;
            }else{
                $newTip->klienti = 9999999;
            }
            $newTip->save();
        } 

        Cart::destroy();

        // Send Notifications for the Admin
        foreach(User::where([['sFor',(int)$req[0]],['role','5']])->get() as $user){
            $details = [
                'id' => $newOrder->id,
                'type' => 'OrderTakeaway',
                'tableNr' => (int)$req[1]
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',(int)$req[0]],['role','55']])->get() as $oneWaiter){
            // register the notification ...
            $details = [
                'id' => $newOrder->id,
                'type' => 'OrderTakeaway',
                'tableNr' => (int)$req[1]
            ];
            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',(int)$req[0]],['role','54']])->get() as $oneCook){
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

        return redirect('/order')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440));
    }



}
