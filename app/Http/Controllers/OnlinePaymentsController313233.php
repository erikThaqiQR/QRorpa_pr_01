<?php

namespace App\Http\Controllers;

use App\User;
use App\Orders;
use App\Restorant;
use App\couponUsedPhoneNr;
use App\OPSaferpayReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlinePaymentsController313233 extends Controller
{




    public function saferPayQrorpa(Request $req){

        $theRest = Restorant::find($req->res);
        $orNr = Orders::all()->max('id');

        // order reference instance
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
                'PayerNote' => 'Cl Telefonnr: '.$req->clPhNumber,
                'Description' => "Bestellen Sie bei ".$theRest->emri
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
                'PayerEmail' => "clientOnlinePay@qrorpa.ch",
                'MerchantEmail' => "checkout@qrorpa.ch",
                'NotifyUrl' => "http://qrorpa.ch/?Res=".$req->res."&t=".$req->t
            ),
            
        );

        $payload['Payment']['Amount']['Value'] = strval( $req->theTotOnCart*100 ) ;

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

        $theRest = Restorant::find($req->res);

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
                    'PayerNote' => $req->clName.' '.$req->clLastname,
                    'Description' => "Bestellen Sie bei ".$theRest->emri
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
                    'PayerEmail' => "clientOnlinePay@qrorpa.ch",
                    'MerchantEmail' => "checkout@qrorpa.ch",
                    'NotifyUrl' => "http://qrorpa.ch/?Res=".$req->res."&t=500"
                ),
                
            );

            $toPay = (float)$req->tipval+(float)$req->ShumaOPay-(float)$req->codeUsedValueID2;
    
            $payload['Payment']['Amount']['Value'] = strval( $toPay*100 ) ;
    
            $username = "API_335189_86302817";
            $password = "t5$&8Hj6uI2&*&23sd";
            $url = "https://www.saferpay.com/api/Payment/v1/PaymentPage/Initialize";
                
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











    public function onlineTakeawayUseUsrPNumber(Request $req){
        if ($req->codeUsedId != 0 && couponUsedPhoneNr::where([['phoneNr',$req->phNr],['couponId',$req->codeUsedId]])->first() != Null){
            return 'couponUsed';
        }else{
            $theRest = Restorant::find($req->res);
            $orNr = Orders::all()->max('id');

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
                    'PayerNote' => $req->clName.' '.$req->clLastname,
                    'Description' => "Bestellen Sie bei ".$theRest->emri
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
                    'PayerEmail' => "clientOnlinePay@qrorpa.ch",
                    'MerchantEmail' => "checkout@qrorpa.ch",
                    'NotifyUrl' => "http://qrorpa.ch/?Res=".$req->res."&t=500"
                ),
                
            );

            $toPay = (float)$req->tipval+(float)$req->ShumaOPay-(float)$req->codeUsedValueID2;
        
            $payload['Payment']['Amount']['Value'] = strval( $toPay*100 ) ;
        
            $username = "API_335189_86302817";
            $password = "t5$&8Hj6uI2&*&23sd";
            $url = "https://www.saferpay.com/api/Payment/v1/PaymentPage/Initialize";
                
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

}
