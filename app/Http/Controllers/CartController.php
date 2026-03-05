<?php

  

namespace App\Http\Controllers;

use Cart;
use App\User;

use App\ekstra;
use App\kategori;
use App\TabOrder;
use App\LlojetPro;
use App\Produktet;
use App\resPlates;
use App\Restorant;
use Carbon\Carbon;
use App\TableQrcode;
use App\notifyClient;
use App\Events\CartMsg;
use App\ghostCartInUse;
use App\tabOrderDelete;
use App\Events\newOrder;
use App\newOrdersAdminAlert;
use Illuminate\Http\Request;
use SpryngApiHttpPhp\Client;
use App\cooksProductSelection;
use App\tablesAccessToWaiters;
use App\tabVerificationPNumbers;
use App\Events\removePaidProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cart');
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



    public function storeP1(Request $req){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(!isset($_SESSION['phoneNrVerified'])){
            $tAns = 'first';
            $pn = '07';
        }else if(isset($_SESSION['phoneNrVerified']) && str_contains($_SESSION['phoneNrVerified'], '|')){
            if(Auth::check() &&  Auth::user()->phoneNr != 'empty'){
                $tAns = 'twoPlus';
                $pn = Auth::user()->phoneNr;
                $_SESSION['phoneNrVerified'] = Auth::user()->phoneNr;

                foreach(Cart::content() as $item){
                    $convThesePN = tabVerificationPNumbers::where([['status','1'],['tabOrderId',$item->options->tabOId]])->first();
                    if($convThesePN != NULL){
                        $convThesePN->phoneNr = Auth::user()->phoneNr;
                        $convThesePN->specStat = 0;
                        $convThesePN->save();

                        $convTheseTO = TabOrder::find($item->tabOId);
                        if($convTheseTO != NULL){
                            $convTheseTO->specStat = 0;
                            $convTheseTO->save();
                        }
                    }
                }
                
            }else{
                $tAns = 'first';
                $pn = '07';
            }
        }else{
            // if($_SESSION['phoneNrVerified'] == '0780000000'){
            //     $tAns = 'first';
            //     $pn = $_SESSION['phoneNrVerified'];
            // }else{
                $tAns = 'twoPlus';
                $pn = $_SESSION['phoneNrVerified'];
            // }
        }
        $storeP1Response =[
            'pId' => $req->id,
            'typeAns' => $tAns,
            'phoneNr' => $pn,
        ];
        return  $storeP1Response; 
    }








    public function storeP2(Request $request){
        // phoneNr
        $sendTo = 445566 ;
        if(substr($request->phoneNr, 0, 1) == 0){
            $pref =substr($request->phoneNr, 0, 3);
            if($pref == '075' || $pref == '076' || $pref == '077' || $pref == '078' || $pref == '079'){
                if(strlen($request->phoneNr) == 10){
                    $sendTo = '41'.substr($request->phoneNr, 1, 9);
                    $phToTestForSMS = substr($request->phoneNr, 1, 9);
                }
            }
            
        }else{
            $pref =substr($request->phoneNr, 0, 2);
            if($pref == '75' || $pref == '76' || $pref == '77' || $pref == '78' || $pref == '79'){
                $sendTo = '41'.$request->phoneNr;
                $phToTestForSMS = $request->phoneNr;
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

            if(substr($request->phoneNr, 0, 1) == 0){ $searchPhoneNr = $request->phoneNr; }else{ $searchPhoneNr = '0'+$request->phoneNr; }
            $unpaid = '';
            $unpaidShow = '';
            $unpaidSend = '';
            if(tabVerificationPNumbers::where([['phoneNr',$searchPhoneNr],['status','1']])->count() > 0){
                // $unpaidProductsNr = tabVerificationPNumbers::where([['phoneNr',$searchPhoneNr],['status','1']])->count();
                foreach(tabVerificationPNumbers::where([['phoneNr',$searchPhoneNr],['status','1']])->get() as $unp){
                    $to = TabOrder::find($unp->tabOrderId);
                  
                    if($unpaidShow == ''){ 
                        $unpaidShow = $to->OrderEmri.'||'.$to->OrderQmimi.'||'.$to->tableNr; 
                    }else{ 
                        $unpaidShow .= '-8-'.$to->OrderEmri.'||'.$to->OrderQmimi.'||'.$to->tableNr; 
                    }
                    if($unpaid == ''){ $unpaid = $unp->tabOrderId ; }else{ $unpaid .= '||'.$unp->tabOrderId ; }
                    
                }
                $unpaidSend = $unpaid.'--8--'.$unpaidShow;
            }else{
                $unpaidSend = 'empty';
            }

            $confirmData =[
                'code' => $randCode,
                'timeStart' => $nowTime,
                'pID' => $request->pID,
                'unpaid' => $unpaidSend,
                'status' => 'success',
            ];
            return $confirmData;
        }else{
            $confirmData =[
                'status' => 'fail',
            ];
            return $confirmData;
        }
    }


    public function store(Request $req){
        // code:  $('#cartStoreP1PhoneNrCode').val(),
        // codeUser: $('#cartStoreP1PhoneNrCodeUser').val(),
        // timeStart: $('#cartStoreP1TimeStarted').val(),
        // emri: $('#ProdAddEmri' + pi).val(),
        // qmimi: parseFloat($('#ProdAddQmimi' + pi).val()),
        // pershkrimi: $('#ProdAddPershk' + pi).val(),
        // extra: $('#ProdAddExtra' + pi).val(),
        // llojet: $('#ProdAddLlojet' + pi).val(),
        // koment: $('#komentMenuAjax' + pi).val(),
        // kategoria: $('#ProdAddKategoria' + pi).val(),
        // res: $('#thisRestaurant').val(),
        // t: $('#thisTable').val(),
        // sas: $('#sasiaProd'+pi).val(),
        // ghostPay: gv,
        // _token: '{{csrf_token()}}' },

        $clPhoneNr = preg_replace('/\s+/', '', $req->phoneNr);

        $nowTime = date('Y-m-d h:i:s');
        $currentDate = strtotime($req->timeStart);
        $futureDate = $currentDate+(60*5);
        $formatDate5m = date("Y-m-d H:i:s", $futureDate);

        if($req->code != $req->codeUser){
            // Fail Code
            $finishData =[
                'status' => 'failCode',
                'code' =>  $req->code,
                'timeStart' =>  $req->timeStart,
                'pID' =>  $req->id,
            ];
            return $finishData;
        }else if($nowTime > $formatDate5m){
            // Fail Time
            $finishData =[
                'status' => 'failTime',
                'pID' =>  $req->id,
            ];
            return $finishData;
        }else{
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // reg number to thusers account
            if(Auth::check() && $req->regNrToUsr == 'Yes'){
                $theU = User::find(Auth::user()->id);
                if($theU != Null){
                    $theU->	phoneNr = $clPhoneNr;
                    $theU->save();
                }
            }
       
            // Add as a tab order 
            $tableOfRes = TableQrcode::where([['tableNr',$req->t],['Restaurant',$req->res]])->first();

            if($tableOfRes->kaTab != 0 && $tableOfRes->kaTab != -1){
                $tabCodeN = $tableOfRes->kaTab;
            }else{
                $tabCodeN = mt_rand(100000, 999999);
                $tableOfRes->kaTab = $tabCodeN;
                $tableOfRes->save();
            }
            // ---------------------------------------------

            // check if a cook has this product registered (in control)
            $addThPro = Produktet::findOrFail($req->id);
            $sasiaDone = (int)$req->sas;
            if(cooksProductSelection::where([['toRes',$req->res],['contentType','Category'],['contentId',$addThPro->kategoria]])->first() != Null){
                $sasiaDone = 0;
            }else{
                if(cooksProductSelection::where([['toRes',$req->res],['contentType','Product'],['contentId',$addThPro->id]])->first() != Null){
                    $sasiaDone = 0;
                }else{
                    if($req->llojet != 'empty' && $req->llojet != ''){
                        $tyDt2D = explode('||',$req->llojet);
                        $theTypeToAdd = LlojetPro::where([['emri',$tyDt2D[0]],['vlera',$tyDt2D[1]]])->first();
                        if($theTypeToAdd != Null){
                            if(cooksProductSelection::where([['toRes',$req->res],['contentType','Type'],['contentId',$theTypeToAdd->id]])->first() != Null){
                                $sasiaDone = 0;
                            }
                        }
                    }
                    if($sasiaDone != 0 && $req->extra != 'empty' && $req->extra != ''){
                        foreach(explode('--0--',$req->extra) as $oneExt){
                            $exDt2D = explode('||',$oneExt);
                            $theExtraToAdd = ekstra::where([['emri',$exDt2D[0]],['qmimi',$exDt2D[1]]])->first();
                            if($theExtraToAdd != Null){
                                if(cooksProductSelection::where([['toRes',$req->res],['contentType','Extra'],['contentId',$theExtraToAdd->id]])->first() != Null){
                                    $sasiaDone = 0;
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
            
            $newTabOrder->prodId = $req->id;
            $newTabOrder->OrderEmri = $req->emri;
            $newTabOrder->tableNr = $req->t;
            $newTabOrder->toRes = $req->res;
            $newTabOrder->OrderPershkrimi= $req->pershkrimi;
            $newTabOrder->OrderSasia=  (int)$req->sas;
            $newTabOrder->OrderSasiaDone =  (int)$sasiaDone;
            $newTabOrder->OrderQmimi= (Float)$req->qmimi*(Float)$req->sas;
            $newTabOrder->OrderExtra= ($req->extra == '' ? 'empty' : $req->extra) ;
            $newTabOrder->OrderType= ($req->llojet == '' ? 'empty' : $req->llojet);
            $newTabOrder->OrderKomenti= $req->koment;

            $theProduIns = Produktet::find($req->id);
            $theKategIns = kategori::find($theProduIns->kategoria);
            $thePlateIns = resPlates::where([['toRes',$req->res],['desc2C',$theKategIns->forPlate]])->first();
            if($thePlateIns == Null){ $newTabOrder->toPlate = 0;
            }else{ $newTabOrder->toPlate = $thePlateIns->id;}
        
            $newTabOrder->status= 0;

            $newTabOrder->save();

            
            Cart::add($req->id, $req->emri, (int)$req->sas, $req->qmimi, ['ekstras' => $req->extra, 'persh' => $req->pershkrimi, 'type' => $req->llojet, 'koment' => $req->koment, 'tabOId' => $newTabOrder->id])->associate('App\Produktet');
            // Add other products too "UNPAID"
            if($req->unpaid != 'empty'){
                $tabCodesToMove = array();
                $tableAlreadyActive = 0;
                $tableQRNew = TableQrcode::where([['Restaurant',$req->res],['tableNr',$req->t]])->first();
                if($tableQRNew->kaTab != 0){
                    $tableAlreadyActive = $tableQRNew->kaTab;
                }

                foreach(explode('||',$req->unpaid) as $upTabOrder){

                    $isInCart = false;
                    $to = TabOrder::find($upTabOrder);
                    if( Cart::count() > 0){
                        foreach(Cart::content() as $item){
                            if($item->options->tabOId == $to->id){ $isInCart = true; }
                        }
                    }
                    if(!$isInCart){
                        if($to->OrderExtra == 'empty'){ $to->OrderExtra = '';}
                        if($to->OrderType == 'empty'){ $to->OrderType = '';}
                        Cart::add($to->prodId, $to->OrderEmri, (int)$to->OrderSasia, (double)($to->OrderQmimi/(int)$to->OrderSasia), ['ekstras' => $to->OrderExtra, 'persh' => $to->OrderPershkrimi, 'type' => $to->OrderType, 'koment' => $to->OrderKomenti, 'tabOId' => $to->id])->associate('App\Produktet');
                    }
                    // Move them to this table (if needed)

                    // Save Tabcodes to move later 
                    if(!in_array($to->tabCode, $tabCodesToMove)){
                        array_push($tabCodesToMove,$to->tabCode);
                    }
                    // merge with the new table 
                    if($tableAlreadyActive != 0){
                        $to->tabCode = $tableAlreadyActive;
    
                        $tabVerify = tabVerificationPNumbers::where('tabOrderId',$to->id)->firstOrFail();
                        $tabVerify->tabCode = $tableAlreadyActive;
                        $tabVerify->save();
                    }
                        // Change order on TAB "tableNr"
                        $to->tableNr = $req->t ;
                        $to->save();
                }
                // Move Tabcodes
                foreach($tabCodesToMove as $TCMove){
                    $tableQROld = TableQrcode::where([['Restaurant',$req->res],['kaTab',$TCMove]])->first();
                    $tableQRNew = TableQrcode::where([['Restaurant',$req->res],['tableNr',$req->t]])->first();
    
                    if($tableQRNew != $tableQROld){
                        if($tableAlreadyActive == 0){
                            // Move if not moved "TAB CODE"
                            $tableQRNew->kaTab = $tableQROld->kaTab;
                            $tableQRNew->save();
                        }
                        $tableQROld->kaTab = 0;
                        $tableQROld->save();
                        
                        $diferentTables = true;
                    }
                }
                if(isset($diferentTables)){
                    foreach(User::where([['sFor',$req->res],['role','5']])->get() as $user){
                        $details = [
                            'id' => $to->id,
                            'type' => 'Taborder',
                            'tableNr' => $tableQRNew->tableNr
                        ];
                        $user->notify(new \App\Notifications\NewOrderNotification($details));

                        $details = [
                            'id' => $to->id,
                            'type' => 'Taborder',
                            'tableNr' => $tableQROld->tableNr
                        ];
                        $user->notify(new \App\Notifications\NewOrderNotification($details));
                    }

                    foreach(User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
                        $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->t]])->first();
                        if($aToTable != NULL && $aToTable->statusAct == 1){
                            $details = [
                                'id' => $to->id,
                                'type' => 'Taborder',
                                'tableNr' => $tableQRNew->tableNr
                            ];
                            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                            $details = [
                                'id' => $to->id,
                                'type' => 'Taborder',
                                'tableNr' => $tableQROld->tableNr
                            ];
                            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                        }
                    }
                }
            }


            // Send Notifications for the Admin
            foreach(User::where([['sFor',$req->res],['role','5']])->get() as $user){
                $details = [
                    'id' => $newTabOrder->id,
                    'type' => 'Taborder',
                    'tableNr' => $req->t
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
            foreach(User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->t]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    $details = [
                        'id' => $newTabOrder->id,
                        'type' => 'Taborder',
                        'tableNr' => $req->t
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            // event(new newOrder($req->res));

            // Save the number ....
            $newNrVerification = new tabVerificationPNumbers;
            $newNrVerification->phoneNr = $clPhoneNr;
            $newNrVerification->tabCode = $tabCodeN;
            $newNrVerification->tabOrderId = $newTabOrder->id;
            $newNrVerification->status = 1;
            $newNrVerification->save();

            // ghostPay: gv,
            // Konverto produktet ghost
            if(isset($_SESSION["phoneNrVerified"]) && str_contains($_SESSION["phoneNrVerified"], '|')){
                // foreach(TabOrder::where([['toRes',$req->res],['tableNr',$req->t],['specStat',$req->ghostPay]])->get() as $orToConv){
                //     $theTabVer = tabVerificationPNumbers::where('tabOrderId',$orToConv->id)->first();

                //     $orToConv->specStat = 0;
                //     $orToConv->save();

                //     $theTabVer->phoneNr = $req->phoneNr;
                //     $theTabVer->specStat = 0;
                //     $theTabVer->save();
                // }
                $ghostPhoneNrNow = $_SESSION["phoneNrVerified"];
                foreach(tabVerificationPNumbers::where([['status',1],['phoneNr',$ghostPhoneNrNow]])->get() as $convThesePN){
                    $convThesePN->phoneNr = $clPhoneNr;
                    $convThesePN->specStat = 0;
                    $convThesePN->save();

                    $convTheseTO = TabOrder::find($convThesePN->tabOrderId);
                    if($convTheseTO != NULL){
                        $convTheseTO->specStat = 0;
                        $convTheseTO->save();
                    }
                }
                unset($_SESSION['adminToClProdsRec']);
            }
            if(!str_contains($clPhoneNr,'|')){ unset($_SESSION['adminToClProdsRec']); }
            $_SESSION["phoneNrVerified"] = $clPhoneNr;

            $phoneNrActive = array();
            foreach(tabVerificationPNumbers::where([['tabCode',$tabCodeN],['status','1']])->get() as $nrVers){
                if($clPhoneNr != $nrVers->phoneNr && !in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){
                    $newNotifyClient = new notifyClient();
                    $newNotifyClient->for = "CartMsg";
                    $newNotifyClient->toRes = $req->res;
                    $newNotifyClient->tableNr = $req->t;
                    $newNotifyClient->clPhoneNr = $nrVers->phoneNr;
                    $newNotifyClient->data = json_encode([
                        'toDoCart' => '1',
                        'payAllOrMineSelected' => 'none'
                    ]);
                    $newNotifyClient->readInd = 0;
                    $newNotifyClient->save();
                    array_push($phoneNrActive,$nrVers->phoneNr);
                }
            }

            Cookie::queue('retSessionCK', $req->res.'|'.$req->t.'|'.$nrVers->phoneNr , 180);
            
            if(isset($_SESSION["phoneNrVerified"]) && str_contains($_SESSION["phoneNrVerified"], '|')){
                return redirect()->back()->withCookie(cookie('ghostCartReturn', 'not' , 360));
            }else{
                $finishData =[
                    'status' => 'success',
                ];
                return $finishData;
            }
        }
    }

    public function store2Plus(Request $req){

        $clPhoneNr = preg_replace('/\s+/', '', $req->phoneNr);

        // Add as a tab order 
        $tableOfRes = TableQrcode::where([['tableNr',$req->t],['Restaurant',$req->res]])->first();

        if($tableOfRes->kaTab != 0 && $tableOfRes->kaTab != -1){
            $tabCodeN = $tableOfRes->kaTab;
        }else{
            $tabCodeN = mt_rand(100000, 999999);
            $tableOfRes->kaTab = $tabCodeN;
            $tableOfRes->save();
        }
        // ---------------------------------------------

        // check if a cook has this product registered (in control)
        $addThPro = Produktet::findOrFail($req->id);
        $sasiaDone = (int)$req->sas;
        if(cooksProductSelection::where([['toRes',$req->res],['contentType','Category'],['contentId',$addThPro->kategoria]])->first() != Null){
            $sasiaDone = 0;
        }else{
            if(cooksProductSelection::where([['toRes',$req->res],['contentType','Product'],['contentId',$addThPro->id]])->first() != Null){
                $sasiaDone = 0;
            }else{
                if($req->llojet != 'empty' && $req->llojet != ''){
                    $tyDt2D = explode('||',$req->llojet);
                    $theTypeToAdd = LlojetPro::where([['emri',$tyDt2D[0]],['vlera',$tyDt2D[1]]])->first();
                    if($theTypeToAdd != Null){
                        if(cooksProductSelection::where([['toRes',$req->res],['contentType','Type'],['contentId',$theTypeToAdd->id]])->first() != Null){
                            $sasiaDone = 0;
                        }
                    }
                }
                if($sasiaDone != 0 && $req->extra != 'empty' && $req->extra != ''){
                    foreach(explode('--0--',$req->extra) as $oneExt){
                        $exDt2D = explode('||',$oneExt);
                        $theExtraToAdd = ekstra::where([['emri',$exDt2D[0]],['qmimi',$exDt2D[1]]])->first();
                        if($theExtraToAdd != Null){
                            if(cooksProductSelection::where([['toRes',$req->res],['contentType','Extra'],['contentId',$theExtraToAdd->id]])->first() != Null){
                                $sasiaDone = 0;
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
        
        $newTabOrder->prodId            = $req->id;
        $newTabOrder->OrderEmri         = $req->emri;
        $newTabOrder->tableNr           = $req->t;
        $newTabOrder->toRes             = $req->res;
        $newTabOrder->OrderPershkrimi   = $req->pershkrimi;
        $newTabOrder->OrderSasia        = (int)$req->sas;
        $newTabOrder->OrderSasiaDone    = (int)$sasiaDone;
        $newTabOrder->OrderQmimi        = (Float)$req->qmimi*(Float)$req->sas;
        $newTabOrder->OrderExtra        = ($req->extra == '' ? 'empty' : $req->extra) ;
        $newTabOrder->OrderType         = ($req->llojet == '' ? 'empty' : $req->llojet);
        $newTabOrder->OrderKomenti      = $req->koment;

        $theProduIns = Produktet::find($req->id);
        $theKategIns = kategori::find($theProduIns->kategoria);
        $thePlateIns = resPlates::where([['toRes',$req->res],['desc2C',$theKategIns->forPlate]])->first();
        if($thePlateIns == Null){ $newTabOrder->toPlate = 0;
        }else{ $newTabOrder->toPlate = $thePlateIns->id;}

        $newTabOrder->status            = 0;

        $newTabOrder->save();

        Cart::add($req->id, $req->emri, (int)$req->sas, $req->qmimi, ['ekstras' => $req->extra, 'persh' => $req->pershkrimi, 'type' => $req->llojet, 'koment' => $req->koment, 'tabOId' => $newTabOrder->id])->associate('App\Produktet');

        // Send Notifications for the Admin
        foreach(User::where([['sFor',$req->res],['role','5']])->get() as $user){
            $details = [
                'id' => $newTabOrder->id,
                'type' => 'Taborder',
                'tableNr' => $req->t
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
            $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->t]])->first();
            if($aToTable != NULL && $aToTable->statusAct == 1){
                // register the notification ...
                $details = [
                    'id' => $newTabOrder->id,
                    'type' => 'Taborder',
                    'tableNr' => $req->t
                ];
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        // event(new newOrder($req->res));

        // Save the number ....
        $newNrVerification = new tabVerificationPNumbers;
        $newNrVerification->phoneNr = $clPhoneNr;
        $newNrVerification->tabCode = $tabCodeN;
        $newNrVerification->tabOrderId = $newTabOrder->id;
        $newNrVerification->status = 1;
        $newNrVerification->save();


        $phoneNrActive = array();
        foreach(tabVerificationPNumbers::where([['tabCode',$tabCodeN],['status','1']])->get() as $nrVers){
            if($clPhoneNr != $nrVers->phoneNr && !in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){
                $newNotifyClient = new notifyClient();
                $newNotifyClient->for = "CartMsg";
                $newNotifyClient->toRes = $req->res;
                $newNotifyClient->tableNr = $req->t;
                $newNotifyClient->clPhoneNr = $nrVers->phoneNr;
                $newNotifyClient->data = json_encode([
                    'toDoCart' => '1',
                    'payAllOrMineSelected' => 'none'
                ]);
                $newNotifyClient->readInd = 0;
                $newNotifyClient->save();
                array_push($phoneNrActive,$nrVers->phoneNr);
            }
        }

    }





    public function storeTakeaway(Request $request){
        Cart::add($request->id, $request->emri, $request->sas, $request->qmimi, ['ekstras' => $request->extra, 'persh' => $request->pershkrimi, 'type' => $request->llojet, 'koment' => $request->koment])->associate('App\Produktet');
    } 





    public function storeBarbershop(Request $req){
        Cart::add($req->id, $req->emri, 1, $req->qmimi, ['ekstras' => $req->extra, 'persh' => $req->pershkrimi, 'type' => $req->type, 'timeNeed' => $req->timeN, 'worker' => $req->worker, 'workerTer' => $req->workerTer, 'workerDate' => $req->workerDate])->associate('App\BarbershopService');
    }






































    public function returnUnpaid01(Request $req){
        // phoneNr: $('#phoneNrSend').val(),
        // myTableNr: $('#theTable').val(),
        // myResId: $('#theRestaurant').val(),
        $sendTo = 445566 ;
        if(substr($req->phoneNr, 0, 1) == 0){
            $pref =substr($req->phoneNr, 0, 3);
            if($pref == '075' || $pref == '076' || $pref == '077' || $pref == '078' || $pref == '079'){
                if(strlen($req->phoneNr) == 10){
                    $sendTo = '41'.substr($req->phoneNr, 1, 9);
                }
            }
            
        }else{
            $pref =substr($req->phoneNr, 0, 2);
            if($pref == '75' || $pref == '76' || $pref == '77' || $pref == '78' || $pref == '79'){
                $sendTo = '41'.$req->phoneNr;
            }
        }
        if($sendTo != 445566){
            $randCode = rand(111111,999999);

            $sendTo2 = (int)$sendTo;
            $nowTime = date('Y-m-d h:i:s');

            // $spryng = new Client('QRorpasms', 'Spryng@qrorpa.ch', 'qrorpa.ch');
            // if($spryng->sms->checkBalance() > 0){
            //     try {
            //         $spryng->sms->send($sendTo2,'Ihr Sicherheitscode ist: '.$randCode.' . Er lauft in 5 Minuten ab.', array(
            //             'route'     => 'business',
            //             'allowlong' => true,
            //             )
            //         );
            //     }catch (InvalidRequestException $e){
            //         dd ($e->getMessage());
            //     }
            // }

            if(substr($req->phoneNr, 0, 1) == 0){ $searchPhoneNr = $req->phoneNr; }else{ $searchPhoneNr = '0'+$req->phoneNr; }
            $unpaid = '';
            $unpaidShow = '';
            $unpaidSend = '';
            if(tabVerificationPNumbers::where([['phoneNr',$searchPhoneNr],['status','1']])->count() > 0){
                $unpaidProductsNr = tabVerificationPNumbers::where([['phoneNr',$searchPhoneNr],['status','1']])->count();
                foreach(tabVerificationPNumbers::where([['phoneNr',$searchPhoneNr],['status','1']])->get() as $unp){
                    // get tab Order 
                    $to = TabOrder::find($unp->tabOrderId);

                    if($unpaidShow == ''){ 
                        $unpaidShow = $to->OrderEmri.'||'.$to->OrderQmimi.'||'.$to->tableNr; 
                    }else{ 
                        $unpaidShow .= '-8-'.$to->OrderEmri.'||'.$to->OrderQmimi.'||'.$to->tableNr; 
                    }

                    if($unpaid == ''){ 
                        $unpaid = $unp->tabOrderId ; 
                    }else{ 
                        $unpaid .= '||'.$unp->tabOrderId ; 
                    }
                }
                $unpaidSend = $unpaid.'--8--'.$unpaidShow;
            }else{
                $unpaidSend = 'empty';
            }

            $confirmData =[
                'code' => $randCode,
                'timeStart' => $nowTime,
                'unpaid' => $unpaidSend,
                'status' => 'success',
            ];
            return $confirmData;

        }else{
            $confirmData =[
                'status' => 'fail',
            ];
            return $confirmData;
        }
    }





    
    public function returnUnpaid02(Request $req){
        $nowTime = date('Y-m-d h:i:s');
        $currentDate = strtotime($req->timeStart);
        $futureDate = $currentDate+(60*5);
        $formatDate5m = date("Y-m-d H:i:s", $futureDate);

        if($req->code != $req->codeUser){
            // Fail Code
            $finishData =[
                'status' => 'failCode',
                'code' =>  $req->code,
                'timeStart' =>  $req->timeStart,
            ];
            return $finishData;
        }else if($nowTime > $formatDate5m){
            // Fail Time
            $finishData =[
                'status' => 'failTime',
            ];
            return $finishData;
        }else{
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION["phoneNrVerified"] = $req->phoneNr;

            $tabCodesToMove = array();
            $tableAlreadyActive = 0;
            $tableQRNew = TableQrcode::where([['Restaurant',$req->res],['tableNr',$req->myTableNr]])->first();
            if($tableQRNew->kaTab != 0){
                $tableAlreadyActive = $tableQRNew->kaTab;
            }
            if($req->unpaid != 'empty'){
                foreach(explode('||',$req->unpaid) as $upTabOrder){

                    $isInCart = false;
                    $to = TabOrder::find($upTabOrder);
                    if( Cart::count() > 0){
                        foreach(Cart::content() as $item){
                            if($item->options->tabOId == $to->id){ $isInCart = true; }
                        }
                    }
                    if(!$isInCart){
                        if($to->OrderExtra == 'empty'){ $to->OrderExtra = '';}
                        if($to->OrderType == 'empty'){ $to->OrderType = '';}
                        Cart::add($to->prodId, $to->OrderEmri, (int)$to->OrderSasia, (double)($to->OrderQmimi/(int)$to->OrderSasia), ['ekstras' => $to->OrderExtra, 'persh' => $to->OrderPershkrimi, 'type' => $to->OrderType, 'koment' => $to->OrderKomenti, 'tabOId' => $to->id])->associate('App\Produktet');
                    }

                    // Save Tabcodes to move later
                    if(!in_array($to->tabCode, $tabCodesToMove)){
                        array_push($tabCodesToMove,$to->tabCode);
                    }

                    // merge with the new table 
                    if($tableAlreadyActive != 0){
                        $to->tabCode = $tableAlreadyActive;

                        $tabVerify = tabVerificationPNumbers::where('tabOrderId',$to->id)->firstOrFail();
                        $tabVerify->tabCode = $tableAlreadyActive;
                        $tabVerify->save();
                    }
                    // Change order on TAB "tableNr"
                    $to->tableNr = $req->myTableNr ;
                    $to->save();

                  
                }
            }
            
            foreach($tabCodesToMove as $TCMove){
                $tableQROld = TableQrcode::where([['Restaurant',$req->res],['kaTab',$TCMove]])->first();
                $tableQRNew = TableQrcode::where([['Restaurant',$req->res],['tableNr',$req->myTableNr]])->first();

                if($tableQRNew != $tableQROld){
                    if($tableAlreadyActive == 0){
                        // Move if not moved "TAB CODE"
                        $tableQRNew->kaTab = $tableQROld->kaTab;
                        $tableQRNew->save();
                    }
                    $tableQROld->kaTab = 0;
                    $tableQROld->save();
                    
                    $diferentTables = true;
                }
            }
            if(isset($diferentTables)){
                foreach(User::where([['sFor',$req->res],['role','5']])->get() as $user){
                    $details = [
                        'id' => $to->id,
                        'type' => 'Taborder',
                        'tableNr' => $tableQROld->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                    $details = [
                        'id' => $to->id,
                        'type' => 'Taborder',
                        'tableNr' => $tableQRNew->tableNr
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
                foreach(User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->myTableNr]])->first();
                    if($aToTable != NULL && $aToTable->statusAct == 1){
                        // register the notification ...
                        $details = [
                            'id' => $to->id,
                            'type' => 'Taborder',
                            'tableNr' => $tableQROld->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                        $details = [
                            'id' => $to->id,
                            'type' => 'Taborder',
                            'tableNr' => $tableQRNew->tableNr
                        ];
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
            }
            $finishData =[
                'status' => 'success',
            ];
            return $finishData;
        }
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
    public function update(Request $request)
    {
  

        Cart::update($request->id, $request->val);
    }
















    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
        // Cart::remove($request->rowId);
        $el = Cart::get($request->rowId);

        // if($el->options->ekstras == ''){ $theEx = 'empty'; }else{ $theEx = $el->options->ekstras; }
        // if($el->options->type == ''){ $theTy = 'empty'; }else{ $theTy = $el->options->type; }
        // $tabEl = TabOrder::where([['toRes',$request->res],['tableNr',$request->t],['OrderEmri',$el->name],['OrderExtra',$theEx],['OrderType',$theTy]
        // ,['OrderPershkrimi',$el->options->persh],['tabCode','!=',0]])->get();
        $tabEl = TabOrder::find($request->taborderId);
        
        // return $tabEl;

        if($tabEl->status == 0){
            Cart::remove($request->rowId);


            // Send Notifications for the Admin
            foreach(User::where([['sFor',$tabEl->toRes],['role','5']])->get() as $user){
                $details = [
                    'id' => $tabEl->id,
                    'type' => 'Taborder',
                    'tableNr' => $tabEl->tableNr
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
            foreach(User::where([['sFor',$tabEl->toRes],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $tabEl->tableNr]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $tabEl->id,
                        'type' => 'Taborder',
                        'tableNr' => $tabEl->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            // event(new newOrder($tabEl->toRes));

            $theR = $tabEl->toRes;
            $theT = $tabEl->tableNr;

            if($tabEl->OrderSasia == $el->qty){
                $tabEl->delete();
                $verPNr = tabVerificationPNumbers::where([['tabCode',$tabEl->tabCode],['tabOrderId',$tabEl->id]])->first();
                if($verPNr != NULL){
                    $verPNr->status=0;
                    $verPNr->save();
                }
            }else{
                $totDel = 0;
                foreach($tabEl as $oneTO){
                    if($oneTO->status == 0 && $oneTO->OrderSasia < ($totDel+$el->qty)){
                        $oneTO->delete(); 
                        $totDel += (int)$oneTO->OrderSasia;

                        $verPNr = tabVerificationPNumbers::where([['tabCode',$oneTO->tabCode],['tabOrderId',$oneTO->id]])->first();
                        if($verPNr != NULL){
                            $verPNr->status=0;
                            $verPNr->save();
                        }
                    }
                }
            }

            //Nese ska me porosi ne TAB  ai mbyllet  
            if(TabOrder::where([['toRes',$theR],['tableNr',$theT],['tabCode','!=','0']])->get()->count() == 0){
                $theTqr = TableQrcode::where([['tableNr',$theT],['Restaurant',$theR]])->first();
                $theTqr->kaTab = 0;
                $theTqr->save();
            }

        if(Cart::count() == 0){ $restart = "1"; }else{ $restart = "0"; }
            $sendData =[
                'ans' => 'yes',
                'reset' => $restart,
            ];
            return $sendData;
        }else{
            // Cart::remove($request->rowId);
            $sendData =[
                'ans' => 'no',
            ];
            return $sendData;
        }
        // return back()->with('success','item has been removed');
     
    }








    public function destroyTakeaway(Request $request){
        // Cart::remove($request->rowId);
        $el = Cart::get($request->rowId);
        $tabEl = TabOrder::find($request->taborderId);

            Cart::remove($request->rowId);

            if(Cart::count() == 0){ $restart = "1"; }else{ $restart = "0"; }
            $sendData =[
                'ans' => 'yes',
                'reset' => $restart,
            ];
            return $sendData;
     
    }





    public function destroyOnlyCart(Request $req){
        Cart::remove($req->rowId);
        
    }





































    public function destroyBarbershop(Request $req){
        Cart::remove($req->id);
    }



    /**
     * switxh to save for later.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switchToSaveForLater($id)
    {
        $item = Cart::get($id);
        Cart::remove($id);

        Cart::instance('saveForLater')->add($item->id, $item->emri, 1, $item->qmimi)->associate('App\Produktet');
        return redirect()->route ('cart')->with('success', 'Item added to Save for later list');
    }
















    public function chTabOrderStatus(Request $req){
        $tOrder = TabOrder::find($req->tabOrId);
        $tOrder->status = 1;
        $tOrder->save();

        $toDoCart = 1;
        $ToTable=$tOrder->toRes.'-0-'.$tOrder->tableNr.'-0-'.$toDoCart;
        event(new CartMsg($ToTable));

        $phoneNrActive = array();
        $tabCodeN = TableQrcode::where([['Restaurant',$tOrder->toRes],['tableNr',$tOrder->tableNr]])->firstOrFail()->kaTab;
        foreach(tabVerificationPNumbers::where([['tabCode',$tabCodeN],['status','1']])->get() as $nrVers){
            if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){

                $newNotifyClient = new notifyClient();
                $newNotifyClient->for = "CartMsg";
                $newNotifyClient->toRes = $tOrder->toRes;
                $newNotifyClient->tableNr = $tOrder->tableNr;
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
            $details = [
                'id' => $tOrder->id,
                'type' => 'AdminUpdateOrdersP',
                'tableNr' => $tOrder->tableNr
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }
    }


    public function chTabOrderStatusDeConfirm(Request $req){
        $tOrder = TabOrder::find($req->tabOrId);
        $tOrder->status = 0;
        $tOrder->save();

        $toDoCart = 1;
        $ToTable=$tOrder->toRes.'-0-'.$tOrder->tableNr.'-0-'.$toDoCart;
        event(new CartMsg($ToTable));

        $phoneNrActive = array();
        $tabCodeN = TableQrcode::where([['Restaurant',$tOrder->toRes],['tableNr',$tOrder->tableNr]])->firstOrFail()->kaTab;
        foreach(tabVerificationPNumbers::where([['tabCode',$tabCodeN],['status','1']])->get() as $nrVers){
            if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){

                $newNotifyClient = new notifyClient();
                $newNotifyClient->for = "CartMsg";
                $newNotifyClient->toRes = $tOrder->toRes;
                $newNotifyClient->tableNr = $tOrder->tableNr;
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
            $details = [
                'id' => $tOrder->id,
                'type' => 'AdminUpdateOrdersP',
                'tableNr' => $tOrder->tableNr
            ];
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }
    }







    public function chTabOrderStatusDelete(Request $req){
        $tOrder = TabOrder::find($req->id);

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

        $isEmptyNow = 'false';

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

        //Nese ska me porosi ne TAB  ai mbyllet  
        if(TabOrder::where([['toRes',$tOrder->toRes],['tableNr',$tOrder->tableNr],['tabCode','!=','0']])->get()->count() == 0){
            $theTqr = TableQrcode::where([['tableNr',$tOrder->tableNr],['Restaurant',$tOrder->toRes]])->first();
            $theTqr->kaTab = 0;
            $theTqr->save();

            $isEmptyNow = 'true';
        }

        return $isEmptyNow;
    }






























    public function checkProdsReadyFromCook(Request $request){
        $therr = Restorant::find($request->res);
        if($therr != Null && $therr->chCookOrderDoneToPay == 1){
            $allMyOr = array();

            foreach(Cart::content() as $item){
                foreach(tabVerificationPNumbers::where('phoneNr',$request->clPhNumber)->get() as $verRecord){
                    array_push($allMyOr,$verRecord->tabOrderId);
                }  
            }
            $TabC = TableQrcode::where([['Restaurant',(int)$request->res],['tableNr',(int)$request->t]])->first()->kaTab;

            // 1, pay mine  7, pay mine and these   9, pay all 
            if((int)$request->payAllOrMine == 1){
                if((int)$request->ghostPayId == 0){ $AllFromTab = TabOrder::where([['tableNr',(int)$request->t],['toRes',(int)$request->res],['tabCode','!=',0],['tabCode',$TabC]])->whereIn('id',$allMyOr)->get();
                }else{ $AllFromTab = TabOrder::where([['tableNr',(int)$request->t],['toRes',(int)$request->res],['tabCode','!=',0],['tabCode',$TabC],['specStat',(int)$request->ghostPayId]])->whereIn('id',$allMyOr)->get();}
            }else if((int)$request->payAllOrMine == 7){
                foreach(explode('||',$request->payAllOrMineSelected) as $nrsToPay){
                    if((int)$request->ghostPayId== 0){ foreach(tabVerificationPNumbers::where('phoneNr',$nrsToPay)->get() as $nrVerRecords){array_push($allMyOr,$nrVerRecords->tabOrderId);}
                    }else{ 
                        foreach(tabVerificationPNumbers::where([['phoneNr',$nrsToPay],['specStat',(int)$request->ghostPayId]])->get() as $nrVerRecords){ 
                            array_push($allMyOr,$nrVerRecords->tabOrderId);
                        }
                    }
                }
                if($request->payAllOrMineProSelected != ''){
                    foreach(explode('||',$request->payAllOrMineProSelected) as $prodsToPay){
                        array_push($allMyOr,$prodsToPay);
                        $verCodeRec = tabVerificationPNumbers::where('tabOrderId',$prodsToPay)->first(); 
                    }
                }
                $AllFromTab = TabOrder::where([['tableNr',(int)$request->t],['toRes',(int)$request->res],['tabCode','!=',0],['tabCode',$TabC]])->whereIn('id',$allMyOr)->get();
            }else if((int)$request->payAllOrMine == 9){
                if((int)$request->ghostPayId== 0){
                    $AllFromTab = TabOrder::where([['tableNr',(int)$request->t],['toRes',(int)$request->res],['tabCode','!=',0],['tabCode',$TabC]])->get();
                }else{
                    $AllFromTab = TabOrder::where([['tableNr',(int)$request->t],['toRes',(int)$request->res],['tabCode','!=',0],['tabCode',$TabC],['specStat',(int)$request->ghostPayId]])->get();
                }
            }
            //----------------------------------------------------------------------------

            
            if($AllFromTab == Null || count($AllFromTab) == 0 ){
                // Nuk eshte gjet asnje porosi nga TAB
                return 'zerroCookProds';
            }else{

                $tabOrdsWAcs = array();
                // check the tab orders for cook access and save their id's in the array above
                foreach(User::where([['sFor',$request->res],['role','54']])->get() as $oneCook){
                    $hasCateAccess = False;
                    $hasProdAccess = False;
                    $hasTypeAccess = False;
                    $hasExtrAccess = False;
                    if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Category']])->count() > 0){$hasCateAccess = True;}
                    if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Product']])->count() > 0){$hasProdAccess = True;}
                    if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Type']])->count() > 0){$hasTypeAccess = True;}
                    if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Extra']])->count() > 0){$hasExtrAccess = True;}

                    foreach($AllFromTab as $oTOr){
                        if(!in_array($oTOr->id,$tabOrdsWAcs)){
                            if($hasProdAccess && cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Product'],['contentId',$oTOr->prodId]])->first() != NULL){
                                array_push($tabOrdsWAcs,$oTOr->id);
                            }else{
                                $tp = Produktet::find($oTOr->prodId);
                                if($hasCateAccess){
                                    if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Category'],['contentId',$tp->kategoria]])->first() != NULL){
                                        array_push($tabOrdsWAcs,$oTOr->id);
                                    }
                                }else if($hasTypeAccess){
                                    if($oTOr->OrderType != 'empty'){
                                        $oTf012D = explode('||',$oTOr->OrderType);
                                        $oneTyperef01ID = LlojetPro::where([['kategoria',$tp->kategoria],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                                        if($oneTyperef01ID != NULL && cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                            array_push($tabOrdsWAcs,$oTOr->id);
                                        }
                                    }
                                }else if($hasExtrAccess){
                                    $hasAnExtra = False;
                                    foreach(explode('--0--',$oTOr->OrderExtra) as $oneExtraref01){
                                        $oEf012D = explode('||',$oneExtraref01);
                                        $oneExtraref01ID = ekstra::where([['toCat',$tp->kategoria],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                                        if($oneExtraref01ID != NULL && cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                            $hasAnExtra = True;
                                            break;
                                        }
                                    }
                                    if($hasAnExtra){
                                        array_push($tabOrdsWAcs,$oTOr->id);
                                    }
                                }
                            }
                        }
                    }
                }

                // check the cooks orders for done 
                if(count($tabOrdsWAcs) == 0){
                    return 'zerroCookProds';
                }else{
                    foreach($tabOrdsWAcs as $tabOrdsWAcsOne){
                        $theTaOr = TabOrder::find($tabOrdsWAcsOne);
                        if($theTaOr != NULL){
                            if($theTaOr->OrderSasia != $theTaOr->OrderSasiaDone ){
                                return 'notDone';
                            }
                        }
                    }
                }
                return 'done';
            }
        }
    }


































    // Register admin products to client

    public function registerAdminToClUn(Request $req){
        //  resId: res,
        //  tableNr: tNr,
        //  selPro: $('#payUnpaidSelectedPr').val(),

        $sesCode = $this->getUnikeSpecStatCode() ;
        $ghostNr = $this->createGhostCNumber($req->resId, $req->tableNr);

        foreach(explode('||',$req->selPro) as $oneSelPro){
            $to = TabOrder::find($oneSelPro);
            $tVer =tabVerificationPNumbers::where('tabOrderId',$to->id)->first();
           
            if($tVer->phoneNr == '0770000000'){
                if($to->OrderExtra == 'empty'){ $saveExtra = ''; }else{ $saveExtra = $to->OrderExtra;}
                if($to->OrderType == 'empty'){ $saveType = ''; }else{ $saveType = $to->OrderType;}
                Cart::add($to->prodId, $to->OrderEmri, (int)$to->OrderSasia, $to->OrderQmimi/(int)$to->OrderSasia, ['ekstras' => $saveExtra, 'persh' => $to->OrderPershkrimi, 'type' => $saveType, 'koment' => $to->OrderKomenti, 'tabOId' => $to->id])->associate('App\Produktet');
                
                $tVer->specStat = $sesCode;
                $tVer->phoneNr =  $ghostNr;
                $tVer->save();

                $to->specStat = $sesCode;
                $to->save();
            }

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['adminToClProdsRec'] = $sesCode;
            $_SESSION['phoneNrVerified'] =  $ghostNr;
        }
        
        $newClNotify = new notifyClient;
        $newClNotify->for = 'ghostCartRefresh';
        $newClNotify->toRes = $req->resId;
        $newClNotify->tableNr = $req->tableNr;
        $newClNotify->data = '';
        $newClNotify->save();

        // Send Notifications for the Admin
        foreach(User::where([['sFor',$req->resId],['role','5']])->get() as $user){
            $details = [
                'id' => $ghostNr,
                'type' => 'newGhostForAdm',
                'tableNr' => $req->tableNr
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',$req->resId],['role','55']])->get() as $oneWaiter){
            $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->tableNr]])->first();
            if($aToTable != NULL && $aToTable->statusAct == 1){
                // register the notification ...
                $details = [
                    'id' => $ghostNr,
                    'type' => 'newGhostForAdm',
                    'tableNr' => $req->tableNr
                ];
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }


        return redirect('/order')->withCookie(cookie('ghostCartReturn', explode('|',$ghostNr)[1] , 360));
    }


    public function registerAdminToClUnFCode(Request $req){
        //gCode: ghostCode,
        // res:  $('#theRestaurant').val(),
        // tableNr: $('#theTable').val(),
        $ghostNr = '999999|'.$req->gCode;
        if(tabVerificationPNumbers::where([['phoneNr',$ghostNr],['status','1']])->get()->count() > 0){
            $specStatSave = 0;
            foreach(tabVerificationPNumbers::where([['phoneNr',$ghostNr],['status','1']])->get() as $tabVerNr){
                $to = TabOrder::find($tabVerNr->tabOrderId);
                if($to->toRes == $req->res && $to->tableNr == $req->tableNr){
                    if($to != NULL && $to->tabCode != 0){
                        $isInCart = False;
                        foreach(Cart::content() as $Citem){
                            if($Citem->options->tabOId == $to->id){
                                $isInCart = True;
                            }
                        }
                        if(!$isInCart){
                            if($to->OrderExtra == 'empty'){ $saveExtra = ''; }else{ $saveExtra = $to->OrderExtra;}
                            if($to->OrderType == 'empty'){ $saveType = ''; }else{ $saveType = $to->OrderType;}
                            Cart::add($to->prodId, $to->OrderEmri, (int)$to->OrderSasia, $to->OrderQmimi/(int)$to->OrderSasia, ['ekstras' => $saveExtra, 'persh' => $to->OrderPershkrimi, 'type' => $saveType, 'koment' => $to->OrderKomenti, 'tabOId' => $to->id])->associate('App\Produktet');
                        }
                    }
                    $specStatSave =  $to->specStat;
                }
            }
            if($specStatSave != 0){

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['adminToClProdsRec'] = $specStatSave;
                $_SESSION['phoneNrVerified'] =  $ghostNr;

                return redirect('/order')->withCookie(cookie('ghostCartReturn', explode('|',$ghostNr)[1] , 360));
            }else{
                return "zerroGhostProds";
            }
        }else{
            return "zerroGhostProds";
        }

    }




    private function getUnikeSpecStatCode(){
        $code =  random_int(0000000,9999999);
        if(tabVerificationPNumbers::where('specStat',$code)->first() != NULL){
            $this->getUnikeSpecStatCode();
        }else{ return $code; }
    }

    private function createGhostCNumber($res, $t){
        $staticPart = '999999|';
        $unikPart = rand(1111,9999);
        $ind = $staticPart.$unikPart;

        $checkGC = ghostCartInUse::where([['indNr',$ind],['toRes',$res],['tableNr',$t]])->first();

        if($checkGC != NULL &&   $checkGC->status == 0){
            return $this->createGhostCNumber($res, $t);
        }else{
            $newNrIndInUse = new ghostCartInUse;
            $newNrIndInUse->toRes = $res;
            $newNrIndInUse->tableNr = $t;
            $newNrIndInUse->indNr = $ind;
            $newNrIndInUse->indNr2 = $unikPart;
            $newNrIndInUse->status = 0;
            $newNrIndInUse->save();
            
            return $ind;
        }
    }

    public function checkCartValidity(Request $req){
        // res:, tNr: 
        if( Cart::count() > 0){
            foreach(Cart::content() as $item){
                // if($item->options->tabOId == $to->id){ $isInCart = true; }
                $to = TabOrder::find($item->options->tabOId);
                if($to != NULL){
                    if($to->toRes != $req->res){
                        $this->emptyTheCart();
                        return 'invalideRes||'.$to->toRes;
                    }else if($to->toRes == $req->res && $to->tableNr != $req->tNr){
                        return 'invalideTable||'.$to->tableNr;
                    }
                }
            }
        }
    }
    public function emptyTheCart(){
        Cart::destroy();
    }


























    




    public function returnCartFromCookie(Request $req){
        // cDt
		// theR
		// theT

        $cd2D = explode('|',$req->cDt);
        // $req->res.'|'.$req->t.'|'.$nrVers->phoneNr

        if($req->theR == $cd2D[0] && $req->theT == $cd2D[1]){
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION["phoneNrVerified"] = $cd2D[2];

            $tabCodesToMove = array();
            $tableAlreadyActive = 0;
            $tableQRNew = TableQrcode::where([['Restaurant',$req->theR],['tableNr',$req->theT]])->first();
            if($tableQRNew->kaTab != 0){
                $tableAlreadyActive = $tableQRNew->kaTab;
            }

            $unpaidPr = tabVerificationPNumbers::where([['phoneNr',$cd2D[2]],['status','1']])->get();
            if( $unpaidPr != NULL ){
                foreach($unpaidPr as $upTabOrder){

                    $isInCart = false;
                    $to = TabOrder::find($upTabOrder->tabOrderId);
                    if( Cart::count() > 0){
                        foreach(Cart::content() as $item){
                            if($item->options->tabOId == $to->id){ $isInCart = true; }
                        }
                    }
                    if(!$isInCart){
                        if($to->OrderExtra == 'empty'){ $to->OrderExtra = '';}
                        if($to->OrderType == 'empty'){ $to->OrderType = '';}
                        Cart::add($to->prodId, $to->OrderEmri, (int)$to->OrderSasia, (double)($to->OrderQmimi/(int)$to->OrderSasia), ['ekstras' => $to->OrderExtra, 'persh' => $to->OrderPershkrimi, 'type' => $to->OrderType, 'koment' => $to->OrderKomenti, 'tabOId' => $to->id])->associate('App\Produktet');
                    }

                    // Save Tabcodes to move later
                    if(!in_array($to->tabCode, $tabCodesToMove)){
                        array_push($tabCodesToMove,$to->tabCode);
                    }
                    // merge with the new table 
                    if($tableAlreadyActive != 0){
                        $to->tabCode = $tableAlreadyActive;

                        $tabVerify = tabVerificationPNumbers::where('tabOrderId',$to->id)->firstOrFail();
                        $tabVerify->tabCode = $tableAlreadyActive;
                        $tabVerify->save();
                    }
                    // Change order on TAB "tableNr"
                    $to->tableNr = $req->theT ;
                    $to->save();
                }
            }

        }
        foreach($tabCodesToMove as $TCMove){
            $tableQROld = TableQrcode::where([['Restaurant',$req->theR],['kaTab',$TCMove]])->first();
            $tableQRNew = TableQrcode::where([['Restaurant',$req->theR],['tableNr',$req->theT]])->first();

            if($tableQRNew != $tableQROld){
                if($tableAlreadyActive == 0){
                    // Move if not moved "TAB CODE"
                    $tableQRNew->kaTab = $tableQROld->kaTab;
                    $tableQRNew->save();
                }
                $tableQROld->kaTab = 0;
                $tableQROld->save();
                
                $diferentTables = true;
            }
        }

        if(isset($diferentTables)){
            foreach(User::where([['sFor',$req->theR],['role','5']])->get() as $user){
                $details = [
                    'id' => $to->id,
                    'type' => 'Taborder',
                    'tableNr' => $tableQROld->tableNr
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
                $details = [
                    'id' => $to->id,
                    'type' => 'Taborder',
                    'tableNr' => $tableQRNew->tableNr
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
            foreach(User::where([['sFor',$req->theR],['role','55']])->get() as $oneWaiter){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $req->theT]])->first();
                if($aToTable != NULL && $aToTable->statusAct == 1){
                    // register the notification ...
                    $details = [
                        'id' => $to->id,
                        'type' => 'Taborder',
                        'tableNr' => $tableQROld->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    $details = [
                        'id' => $to->id,
                        'type' => 'Taborder',
                        'tableNr' => $tableQRNew->tableNr
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
        }
        $finishData =[
            'status' => 'success',
        ];
        return $finishData;





    } 





}
