<?php

namespace App\Http\Controllers;

use Cart;
use QRCode;
use App\User;
use App\Cupon;
use App\Piket;
use App\ekstra;
use App\Orders;
use App\TipLog;
use App\kategori;
use App\PiketLog;
use App\TabOrder;
use App\Takeaway;
use App\LlojetPro;
use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use App\DeliveryPLZ;
use App\marketingNr;
use App\TableQrcode;
use App\DeliveryProd;
use App\notifyClient;
use App\OrdersPassive;

use App\taDeForCookOr;
use App\Events\CartMsg;
use App\ghostCartInUse;
use App\RecomendetProd;
use App\Events\newOrder;
use App\DeliverySchedule;
use App\couponUsedPhoneNr;
use App\newOrdersAdminAlert;
use Illuminate\Http\Request;
use App\tablesAccessToWaiters;
use App\tabVerificationPNumbers;
use App\Events\removePaidProduct;
use Cartalyst\Stripe\Api\Coupons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;


// if(!isset($_COOKIE["PHPSESSID"])){
    session_start();
// }

class ProduktController extends Controller
{
    
    public function manageIndex(){
        // $produktet = Produktet::all();
        return view('sa/superAdminIndex');
        // return view('sa/manageProduktet');
    }

    public function SAIndex(){
        return view('sa/superAdminIndex');
    }

    public function SAIndexBoxes(){
        return view('sa/superAdminIndex');
    }
    public function SAIndexCategory(){
        return view('sa/superAdminIndex');
    }
    public function SAIndexExtra(){
        return view('sa/superAdminIndex');
    }
    public function SAIndexType(){
        return view('sa/superAdminIndex');
    }
    public function SAIndexProduct(){
        return view('sa/superAdminIndex');
    }

    


    public function index(){
        $produktet = Produktet::all();
        return view('sa/produkt')->with('prods', $produktet);
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

    
   



    public function confNr(Request $request){
        
        $this->validate($request, [
            'phoneNr' => 'required|max:10|min:9',
        ]);

        $sendTo = 445566 ;
        
        if(substr($request->phoneNr, 0, 1) == 0){
            $pref =substr($request->phoneNr, 0, 3);
            if($pref == '075' || $pref == '076' || $pref == '077' || $pref == '078' || $pref == '079'){
                if(strlen($request->phoneNr) == 10){
                    $sendTo = '41'.substr($request->phoneNr, 1, 9);
                }
            }
            
        }else{
            $pref =substr($request->phoneNr, 0, 2);
            if($pref == '75' || $pref == '76' || $pref == '77' || $pref == '78' || $pref == '79'){
                $sendTo = '41'.$request->phoneNr;
            }
            
        }
        if($sendTo != 445566){
            $sendTo2 = (int)$sendTo;

                $nowTime = date('Y-m-d h:i:s');
                
                // $spryng = new \Spryng\Client('2b7be9205d17e3ff71265385046260b40a8f2395598b194ed9');
                // $message = new \Spryng\Objects\Message();
                // $message->originator = 'qrorpa.ch';
                // $message->recipients = [$sendTo2];
                // $message->body = 'Ihr Sicherheitscode ist: '.$request->code.' . Er lauft in 2 Minuten ab.';
                // $Spryng->messages->create($message);

                $confirmData =[
                    'code' => $request->code,
                    'timeStart' => $nowTime,
                    'klientPhone' => $sendTo2,
                    'tipValue' => $request->tipValueSend,
                    'tipCHF' => number_format($request->tipValueCHFSend, 2, '.', ''),
                    'pUsed' => $request->pointsUsed,
                    'freeProd' => $request->freeShotPh1,
                    'nameTA' => $request->nameTA01,
                    'lastnameTA' => $request->lastnameTA01,
                    'timeTA' => $request->timeTA01,
                    'codeUsed' => $request->codeUsedValue,
                    'payWhat' => $request->payAllOrMine
                ];
                return view('cart')->with('confirmData',$confirmData);
        }else{
            return redirect('/order')->with('error',"Ihre Nummer existiert nicht. Bitte versuchen Sie es noch ein mal.");
        }
    }









    public function confCode(Request $request){
        
        $clPhoneNr = preg_replace('/\s+/', '', $request->klientPhoneNr);
        $nowTime = date('Y-m-d h:i:s');
            // Kontrolli i dyte per piket 
  
                // Deaktivizo numrin GHOSTCart nese egziston
                if($request->ghostCNr != 0){
                    // $request->t $request->Res
                    $findGCActive = ghostCartInUse::where([['toRes',$request->Res],['tableNr',$request->t],['indNr2',$request->ghostCNr],['status','0']])->first();
                    if($findGCActive != NULL){
                        $findGCActive->status = 1;
                        $findGCActive->save();
                    }
                }
                //---------------------------------------
                
                $newOrder = new Orders;

                $bakshishi =number_format($request->tipValueConfirmValue, 2, '.', '') ;

                $allMyOr = array();
                          
                // foreach(Cart::content() as $item){
                    foreach(tabVerificationPNumbers::where([['phoneNr',$clPhoneNr],['status','1']])->get() as $verRecord){
                        array_push($allMyOr,$verRecord->tabOrderId);
                    }  
                // }
            
                $totalPay = 0 ;
                $TabC = TableQrcode::where([['Restaurant',$request->Res],['tableNr',$request->t]])->first()->kaTab;
                $saveOrderAll = '';

                // 1, pay mine
                // 7, pay mine and these
                // 9, pay all 

                // ghostPay Set or not

                if($request->payAllOrMine == 1){
                    if($request->ghostPay == 0){
                        $AllFromTab = TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['tabCode',$TabC]])->whereIn('id',$allMyOr)->get();
                    }else{
                        $AllFromTab = TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['tabCode',$TabC],['specStat',$request->ghostPay]])->whereIn('id',$allMyOr)->get();
                    }
                }else if($request->payAllOrMine == 7){
                    foreach(explode('||',$request->payAllOrMineSelected) as $nrsToPay){
                        if($request->ghostPay == 0){
                            foreach(tabVerificationPNumbers::where('phoneNr',$nrsToPay)->get() as $nrVerRecords){
                                array_push($allMyOr,$nrVerRecords->tabOrderId);
                                $sendRemoveProd = $nrVerRecords->phoneNr.'||'.$nrVerRecords->tabOrderId.'||a';
                                event(new removePaidProduct($sendRemoveProd));
                            }
                        }else{
                            foreach(tabVerificationPNumbers::where([['phoneNr',$nrsToPay],['specStat',$request->ghostPay]])->get() as $nrVerRecords){
                                array_push($allMyOr,$nrVerRecords->tabOrderId);
                                $sendRemoveProd = $nrVerRecords->phoneNr.'||'.$nrVerRecords->tabOrderId.'||a';
                                event(new removePaidProduct($sendRemoveProd));
                            }
                        }
                    }
                    if($request->payAllOrMineProSelected != ''){
                        foreach(explode('||',$request->payAllOrMineProSelected) as $prodsToPay){
                            array_push($allMyOr,$prodsToPay);
                            $verCodeRec = tabVerificationPNumbers::where('tabOrderId',$prodsToPay)->first(); 
                            $sendRemoveProd = $verCodeRec->phoneNr.'||'.$prodsToPay.'||a';
                            // event(new removePaidProduct($sendRemoveProd));
                        }
                    }
                    $AllFromTab = TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['tabCode',$TabC]])->whereIn('id',$allMyOr)->get();
                }else if($request->payAllOrMine == 9){
                    if($request->ghostPay == 0){
                        $AllFromTab = TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['tabCode',$TabC]])->get();
                    }else{
                        $AllFromTab = TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['tabCode',$TabC],['specStat',$request->ghostPay]])->get();
                    }
                    foreach($AllFromTab as $prodsToPay22){
                        $verCodeRec22 = tabVerificationPNumbers::where('tabOrderId',$prodsToPay22->id)->first(); 
                        $sendRemoveProd = $verCodeRec22->phoneNr.'||'.$verCodeRec22.'||a';
                        // event(new removePaidProduct($sendRemoveProd));
                    } 
                }

                $tVerNrForNotifications = tabVerificationPNumbers::where([['tabCode',$TabC],['status','1']])->get();

                foreach($AllFromTab as $tOr){
                    // Deactivate phone Nr verification 
                    $pnvRecord = tabVerificationPNumbers::where('tabOrderId',$tOr->id)->first();
                    $pnvRecord->status = 0;
                    $pnvRecord->save();

                    if($tOr->status != 9 ){
                        if($tOr->OrderType != null){ $regType = explode('||',$tOr->OrderType)[0]; }else{  $regType = ''; }

                        if($request->t == 500){
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
                        $findGCActive = ghostCartInUse::where([['toRes',$request->Res],['tableNr',$request->t],['indNr2',$clPhoneNr2D[1]],['status','0']])->first();
                        if($findGCActive != NULL){
                            $findGCActive->status = 1;
                            $findGCActive->save();
                        }
                    }
                    //---------------------------------------
                }

                if($saveOrderAll != ''){
                    if($request->couponUsed != 0){
                        $cou = Cupon::find($request->couponUsed);
                        if($cou != NULL){
                            if($cou->typeCo == 1){
                                $beforeTotal =number_format($totalPay-$request->tipValueConfirmValue, 2, '.', '');
                                // $MinusVal = number_format(($cou['valueOff']*0.01) * $beforeTotal, 2, '.', '');
                                $MinusVal = number_format($request->codeUsedValue, 2, '.', '');
                                $ProdsVal = 'empty';
                            }else if($cou->typeCo == 2){
                                $MinusVal = $cou['valueOffMoney'];
                                $ProdsVal = 'empty';
                            }else if($cou->typeCo == 3){
                                $MinusVal = 0;
                                $ProdsVal = $cou['prodName'];
                            }

                            $cUsing  = new couponUsedPhoneNr;
                            $cUsing->toRes =$request->Res;
                            $cUsing->phoneNr =$clPhoneNr;
                            $cUsing->couponId =$cou->id;
                            $cUsing->save();
                        }else{
                            $MinusVal = 0;
                            $ProdsVal = 'empty';
                        }
                    }else{
                        $MinusVal = 0;
                        $ProdsVal = 'empty';
                    }

                    $newOrder->nrTable = $request->t;
                    $newOrder->statusi = 0;
                    $newOrder->byId = $request->userIdCash;
                    $newOrder->userEmri = $request->userNameCash;
                    $newOrder->userEmail = $request->userEmailCash;
                    if($saveOrderAll == ''){
                        $newOrder->porosia = 'empty';
                    }else{
                        $newOrder->porosia = $saveOrderAll; //$request->userPorosia;
                    }
                    $newOrder->payM = $request->userPayM;
                    $newOrder->shuma =number_format($totalPay, 2, '.', '')-$MinusVal - ($request->pointsUsing  * 0.01) + $bakshishi; //$request->Shuma
                    $newOrder->Restaurant = $request->Res;
                    $newOrder->userPhoneNr = $clPhoneNr;
                    $newOrder->tipPer =  number_format($request->tipValueConfirmValue, 2, '.', '') ;
                    $newOrder->TAemri = $request->nameTA02;
                    $newOrder->TAmbiemri = $request->lastnameTA02;
                    $newOrder->TAtime = $request->timeTA02;
                    $newOrder->cuponOffVal = $MinusVal;
                    $newOrder->cuponProduct = $ProdsVal;

                    $refIfOrPa = OrdersPassive::where('Restaurant',$request->Res)->max('refId') + 1;
                    $refIfOr = Orders::where('Restaurant',$request->Res)->max('refId') + 1;
                    $nextRefId = 0;
                    if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
                    $newOrder->refId = $nextRefId;
                
                    $newOrder->TAplz = 'empty';
                    $newOrder->TAort = 'empty';
                    $newOrder->TAaddress = 'empty';
                    $newOrder->TAkoment = 'empty';

                    $theR = Restorant::find($request->Res);
                    $theR->cashPayOrders += 1;
                    $theR->save();
                    

                    if(TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['status','<',2]])->get()->count() <= 0){
                        $tableGet = TableQrcode::where([['Restaurant',$request->Res],['tableNr',$request->t]])->first();
                        $tableGet->kaTab = 0;
                        $tableGet->save();

                        $endOfTAB = 'true';
                    }else{
                        $endOfTAB = 'false';
                    }


                    if($totalPay >= Restorant::find($request->Res)->priceFree && $request->freeShotPh2 != 0){
                        $newOrder->freeProdId =  $request->freeShotPh2;
                    }
                    
                  
                    // Gen the next indentifikation number (shifra - per Orders) 
                        $orderSh = $this->genShifra($request->Res);
                        $_SESSION['trackMO'] = $orderSh;
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


                    if($request->has('marketingSMS')){
                        $markNew = new marketingNr;
                        $markNew->nr = $clPhoneNr;
                        $markNew->save();
                    }
 
                    $text = $request->Res;



                    // Code per tip
                    if($request->tipValueConfirmValue != 0){
                        $newTip = new TipLog;

                        $newTip->shumaPor =number_format((float)($request->Shuma + $bakshishi) - ($request->pointsUsing  * 0.01), 2, '.', '') ;
                        $newTip->tipPer = $request->tipValueConfirmTitle;
                        $newTip->tipTot = $request->tipValueConfirmValue;
                        $newTip->toRes = $request->Res;
                        if(Auth::check()){
                            $newTip->klienti = Auth::user()->id;
                        }else{
                            $newTip->klienti = 9999999;
                        }

                        $newTip->save();
                    } 

                    $payerPhNr = $_SESSION['phoneNrVerified'];
                    if(!Auth::check()){
                        unset($_SESSION['phoneNrVerified']);
                    }

                    if($request->ghostPay != 0){
                        unset($_SESSION['adminToClProdsRec']);
                    }

             
                    // event(new newOrder($text));

                    if((int)$request->payAllOrMine == 9){
                        $toDoCart = '9';
                        $sendPayAllOrMineSelected = 'none';
                        $ToTable=$request->Res.'-0-'.$request->t.'-0-'.$toDoCart;
                    }else if((int)$request->payAllOrMine == 7){
                        $toDoCart = '7';
                        $sendPayAllOrMineSelected = $request->payAllOrMineSelected;
                        $ToTable=$request->Res.'-0-'.$request->t.'-0-'.$toDoCart.'-0-'.$request->payAllOrMineSelected;
                    }else if((int)$request->payAllOrMine == 1){
                        $toDoCart = '1';
                        $sendPayAllOrMineSelected = 'none';
                        $ToTable=$request->Res.'-0-'.$request->t.'-0-'.$toDoCart;
                    }
                    event(new CartMsg($ToTable));

                    $phoneNrActive = array();
                    
                    foreach($tVerNrForNotifications as $nrVers){
                        if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){
                            // $payerPhNr
                            $newNotifyClient = new notifyClient();
                            $newNotifyClient->data = json_encode([
                                'toDoCart' =>(int)$request->payAllOrMine,
                                'payAllOrMineSelected' => $sendPayAllOrMineSelected
                            ]);
                            $newNotifyClient->for = "CartMsg";
                            $newNotifyClient->toRes = $request->Res;
                            $newNotifyClient->tableNr = $request->t;
                            $newNotifyClient->readInd = 0;
                            $newNotifyClient->clPhoneNr = $nrVers->phoneNr;
                            $newNotifyClient->save();
                            array_push($phoneNrActive,$nrVers->phoneNr);
                        }
                    }
                    foreach(User::where([['sFor',$request->Res],['role','5']])->get() as $user){
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'Order',
                            'tableNr' => $request->t
                        ];
                        $user->notify(new \App\Notifications\NewOrderNotification($details));

                        if($endOfTAB == 'true'){
                            $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$request->t],['statActive','1']])->first();
                            if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();}
                        }else{
                            foreach($AllFromTab as $oneTabOSel){
                                $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$request->tableNr],['tabOrderId',$oneTabOSel->id],['statActive','1']])->first();
                                if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();} 
                            }
                        }
                    }

                    foreach(User::where([['sFor',$request->Res],['role','55']])->get() as $oneWaiter){
                        $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $request->t]])->first();
                        if($aToTable != NULL && $aToTable->statusAct == 1){
                            // register the notification ...
                            $details = [
                                'id' => $newOrder->id,
                                'type' => 'Order',
                                'tableNr' => $request->t
                            ];
                            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                        }
                    }

                    
                    foreach(User::where([['sFor',$request->Res],['role','54']])->get() as $oneCook){
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'Order',
                            'tableNr' => $request->t
                        ];
                        $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
                    }

                    Cart::destroy();
                    if(Cart::content()->count() > 0){
                        $this->emptyTheCarFunRek();
                    }
                    
                    return redirect('/order')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440))->withCookie(cookie('ghostCartReturn', 'not' , 5))->withCookie(cookie('retSessionCK', 'not', 20));
                  
                }else{
                    Cart::destroy();
                    if(Cart::content()->count() > 0){
                        $this->emptyTheCarFunRek();
                    }
                    return redirect('/order')->with('success', 'Es wurde erfolgreich bestätigt!');
                }
    }


    private function emptyTheCarFunRek(){
        Cart::destroy();
        if(Cart::content()->count() > 0){
            $this->emptyTheCarFunRek();
        }
    }





    public function removeExtFromCart(Request $request){
        // $request->extPro;
        // $request->allExtra;
        $newEl = Cart::get($request->elementId);

        if($newEl->options->ekstras == ''){ $theEx = 'empty'; }else{ $theEx = $newEl->options->ekstras; }
        if($newEl->options->type == ''){ $theTy = 'empty'; }else{ $theTy = $newEl->options->type; }

        $extraProPrice = explode('||',$request->extPro)[1];

        $newPrice = $newEl->price - $extraProPrice;
        $newExtElements = str_replace($request->extPro,'', $request->allExtra);

        $fin = false;
       
        $tabEl = TabOrder::where([['OrderEmri',$newEl->name],['OrderExtra',$theEx],['OrderType',$theTy]
        ,['OrderPershkrimi',$newEl->options->persh],['tabCode','!=',0]])->get();
        foreach( $tabEl as $oTO){
            if($oTO->status == 1){
                $fin = true;
            }else{
                $fin = false;
            }
        }

        if($tabEl != NULL && !$fin){
            foreach($tabEl as $tabElOne){
                $tabElOne->OrderExtra = $newExtElements;
                $tabElOne->OrderQmimi = $tabElOne->OrderQmimi - (float)((float)$extraProPrice*(float)$tabElOne->OrderSasia);
                $tabElOne->save();
            }
           
            // if(count($tabEl) > 1){
            //     event(new newOrder($tabEl[0]->toRes));
            // }else{
            //     event(new newOrder($tabEl->toRes));
            // }

            event(new newOrder($tabElOne->toRes));
        

            $newnew = Cart::update($request->elementId, ['price' => $newPrice,'options'=>['ekstras' => $newExtElements, 'persh' => $newEl->options->persh, 'type' => $newEl->options->type, 'koment' => $newEl->options->koment]]);
        
            return json_encode($newnew);
        }else{
            return 'no';
        }
    }




    public function add(){
        return view('sa/produktAdd');
    }


    public function store(Request $request){
    //     $this->validate($request, [
    //         'emri' => 'required',
    //         'pershkrimi' => 'required',
    //         'qmimi' => 'required',
    //         'kategoria' => 'required|not_in:0',
    //    ]);

        if(empty($request->input('emri')) || empty($request->input('qmimi'))){
            return redirect('/produktet5?goP='.$request->input('restaurant'))->with('error','empty Fields!');
        }
        $prod = new Produktet;

        $prod->emri = $request->input('emri');
        $prod->pershkrimi = $request->input('pershkrimi');
        $prod->kategoria = $request->input('kategoria');
        $prod->qmimi = $request->input('qmimi');
        $prod->extPro = $request->input('extPro');
        $prod->type = $request->input('typePro');
        $prod->toRes = $request->input('restaurant');

        $prod->doneIn = $request->input('tipiK');

        if($request->input('qmimi2') != ''){
            $prod->qmimi2 = $request->input('qmimi2');
        }
        
        $prod->save();

        if(isset($request->userSA)){
            return redirect('/SuperAdminContentProduct?Res='.$request->input('restaurant'));
        }else{
            return redirect('/produktet5Product?Res='.$request->input('restaurant'));
        }
    }


    public function storeAdminP(Request $req){
        if(isset($req->isWaiter)){
            if(empty($req->input('emri')) || empty($req->input('qmimi'))){
                return redirect('/adminWoContentMngWaiter')->with('error','Leerfelder');
            }
            if (str_contains($req->input('emri'), '&') || str_contains($req->input('emri'), '+') || str_contains($req->input('emri'), '|') || str_contains($req->input('emri'), '||')){
                return redirect('/adminWoContentMngWaiter')->with('error','Dieser Produktname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
            if (str_contains($req->input('pershkrimi'), '&') || str_contains($req->input('pershkrimi'), '+') || str_contains($req->input('pershkrimi'), '|') || str_contains($req->input('pershkrimi'), '||')){
                return redirect('/adminWoContentMngWaiter')->with('error','Diese Produktbeschreibung enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else if(isset($req->isAccountant)){
            if(empty($req->input('emri')) || empty($req->input('qmimi'))){
                return redirect('/AccountantProducts')->with('error','Leerfelder');
            }
            if (str_contains($req->input('emri'), '&') || str_contains($req->input('emri'), '+') || str_contains($req->input('emri'), '|') || str_contains($req->input('emri'), '||')){
                return redirect('/AccountantProducts')->with('error','Dieser Produktname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
            if (str_contains($req->input('pershkrimi'), '&') || str_contains($req->input('pershkrimi'), '+') || str_contains($req->input('pershkrimi'), '|') || str_contains($req->input('pershkrimi'), '||')){
                return redirect('/AccountantProducts')->with('error','Diese Produktbeschreibung enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else{
            if(empty($req->input('emri')) || empty($req->input('qmimi'))){
                return redirect('/dashboardContentMng')->with('error','Leerfelder');
            }
            if (str_contains($req->input('emri'), '&') || str_contains($req->input('emri'), '+') || str_contains($req->input('emri'), '|') || str_contains($req->input('emri'), '||')){
                return redirect('/dashboardContentMng')->with('error','Dieser Produktname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
            if (str_contains($req->input('pershkrimi'), '&') || str_contains($req->input('pershkrimi'), '+') || str_contains($req->input('pershkrimi'), '|') || str_contains($req->input('pershkrimi'), '||')){
                return redirect('/dashboardContentMng')->with('error','Diese Produktbeschreibung enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }
        $prod = new Produktet;

        $prod->emri = $req->input('emri');
        $prod->pershkrimi = $req->input('pershkrimi');
        $prod->kategoria = $req->input('kategoria');
        $prod->qmimi = $req->input('qmimi');
        $prod->extPro = $req->input('extPro');
        $prod->type = $req->input('typePro');
        $prod->toRes = $req->input('restaurant');
        $maxPosition = Produktet::where('kategoria',$req->input('kategoria'))->max('position');
        $prod->position = ++$maxPosition;
        $prod->doneIn = 8;
        if($req->input('qmimi2') != ''){ $prod->qmimi2 = $req->input('qmimi2'); }
        $prod->accessableByClients = $req->input('accessByClientsVal');
        $prod->save();

        if($req->forTakeawayToo == 1){
            $newTakeaway = new Takeaway;
            $newTakeaway->emri = $req->input('emri');
            $newTakeaway->pershkrimi = $req->input('pershkrimi');
            $newTakeaway->kategoria = $req->input('kategoria');
            $newTakeaway->qmimi = $req->input('qmimi');
            if($req->input('qmimi2') != ''){
                $newTakeaway->qmimi2 = $req->input('qmimi2');
            }else{
                $newTakeaway->qmimi2 = 999999 ;
            }
            $newTakeaway->extPro = $req->input('extPro');
            $newTakeaway->type = $req->input('typePro');
            $newTakeaway->toRes = $req->input('restaurant');
            if(Takeaway::where('kategoria',$req->input('kategoria'))->count() > 0){
                $newTakeaway->position = Takeaway::where('kategoria',$req->input('kategoria'))->max('position')+1 ;
                $isFirst = 'no';
            }else{
                $newTakeaway->position = 1;
                if(Takeaway::where('toRes',$req->input('restaurant'))->count() == 0){
                    $upThisKat = kategori::find($newTakeaway->kategoria);
                    $upThisKat->positionTakeaway = 1;
                    $upThisKat->save();
                }else{
                    $upThisKat = kategori::find($newTakeaway->kategoria);
                    $upThisKat->positionTakeaway = kategori::where('toRes',$upThisKat->toRes)->max('positionTakeaway')+1;
                    $upThisKat->save();
                }
                $isFirst = 'yes';
            }
            $newTakeaway->prod_id = $prod->id;
            $newTakeaway->accessableByClients = $req->input('accessByClientsVal');
            $newTakeaway->save();
        }
        if($req->forDeliveryToo == 1){
            $newDelivery = new DeliveryProd;
            $newDelivery->emri = $req->input('emri');
            $newDelivery->pershkrimi = $req->input('pershkrimi');
            $newDelivery->kategoria = $req->input('kategoria');
            $newDelivery->qmimi = $req->input('qmimi');
            if($req->input('qmimi2') != ''){
                $newDelivery->qmimi2 = $req->input('qmimi2');
            }else{
                $newDelivery->qmimi2 = 999999 ;
            }
            $newDelivery->extPro = $req->input('extPro');
            $newDelivery->type = $req->input('typePro');
            $newDelivery->toRes = $req->input('restaurant');
            // LLogaritet dhe vendoset pozicioni 
            if(DeliveryProd::where('kategoria',$req->input('kategoria'))->count() > 0){
                $newDelivery->position = DeliveryProd::where('kategoria',$req->input('kategoria'))->max('position')+1 ;
            }else{
                $newDelivery->position = 1;
                if(DeliveryProd::where('toRes',$req->input('restaurant'))->count() == 0){
                    $upThisKat = kategori::find($newDelivery->kategoria);
                    $upThisKat->positionDelivery = 1;
                    $upThisKat->save();
                }else{
                    $upThisKat = kategori::find($newDelivery->kategoria);
                    $upThisKat->positionDelivery = kategori::where('toRes',$upThisKat->toRes)->max('positionDelivery')+1;
                    $upThisKat->save();
                }
            }
            $newDelivery->prod_id = $prod->id;
            $newDelivery->accessableByClients = $req->input('accessByClientsVal');
            $newDelivery->save();
        }

        if(isset($req->isWaiter)){
            return redirect('/adminWoContentMngWaiter')->with('success','Produkt erfolgreich hinzugefügt' );
        }else if(isset($req->isAccountant)){
            return redirect('/AccountantProducts')->with('success','Produkt erfolgreich hinzugefügt' );
        }else{
            return redirect('/dashboardContentMng')->with('success','Produkt erfolgreich hinzugefügt' );
        }
    }











    public function edit($id){
        $prod = Produktet::find($id);
        return view('sa/editProduktet')->with('prod', $prod);
    }



    public function update(Request $request, $id){
        $this->validate($request, [
            'emri' => 'required',
            'pershkrimi' => 'required',
            'qmimi' => 'required'
       ]);

        //Save it
        $produkt = Produktet::find($id);

        $produkt->emri = $request->input('emri');
        $produkt->pershkrimi = $request->input('pershkrimi');
        $produkt->qmimi = $request->input('qmimi');
        $produkt->extPro = $request->input('extProEdit');
        $produkt->type = $request->input('typeProEdit');
 
        if($request->input('qmimi2') != ''){
            $produkt->qmimi2 = $request->input('qmimi2');
        }
        
        $produkt->save();

        return redirect('/produktet5?goP='.$produkt->toRes);
    }


    public function destroy(Request $req, $id){
        $delPro =Produktet::find($id)->toRes;
    
        if(Takeaway::where('prod_id',$id)->first() != NULL){
            $deleteThisTA = Takeaway::where('prod_id',$id)->first();    
            $deleteThisTA->delete();    
        }
        if(DeliveryProd::where('prod_id',$id)->first() != NULL){
            $deleteThisDE = DeliveryProd::where('prod_id',$id)->first();    
            $deleteThisDE->delete();    
        }

        Produktet::find($id)->delete();

        if(isset($req->userSA)){
            return redirect('/SuperAdminContentProduct?Res='.$delPro)->with('success','Das Produkt wurde erfolgreich gelöscht!');
        }else{
            return redirect('/produktet5Product?Res='.$delPro)->with('success','Das Produkt wurde erfolgreich gelöscht!');
        }   
    }


    public function destroyAdminP(Request $req){

        $deleteThisTA = Takeaway::where('prod_id',$req->id)->first(); 
        if($deleteThisTA != NULL){
            
            foreach(Takeaway::where([['kategoria',$deleteThisTA->kategoria],['position','>',$deleteThisTA->position]])->get() as $upT){
                $upT->position = $upT->position-1;
                $upT->save();
            }
            if(Takeaway::where('kategoria',$deleteThisTA->kategoria)->count() == 1){
                $upThisKat = kategori::find($deleteThisTA->kategoria);
               
                foreach(kategori::where([['toRes',$deleteThisTA->toRes],['positionTakeaway','>', $upThisKat->positionTakeaway]])->get() as $upCatt){
                    $upCatt->positionTakeaway = $upCatt->positionTakeaway-1;
                    $upCatt->save();
                }
                $upThisKat->positionTakeaway = 1;
                $upThisKat->save();
            }
            $deleteThisTA->delete();    
        }

        $deleteThisDE = DeliveryProd::where('prod_id',$req->id)->first(); 
        if($deleteThisDE != NULL){
            foreach(DeliveryProd::where([['kategoria',$deleteThisDE->kategoria],['position','>',$deleteThisDE->position]])->get() as $upD){
                $upD->position = $upD->position-1;
                $upD->save();
            }
            if(DeliveryProd::where('kategoria',$deleteThisDE->kategoria)->count() == 1){
                $upThisKat = kategori::find($deleteThisDE->kategoria);
               
                foreach(kategori::where([['toRes',$deleteThisDE->toRes],['positionDelivery','>', $upThisKat->positionDelivery]])->get() as $upCatt){
                    $upCatt->positionDelivery = $upCatt->positionDelivery-1;
                    $upCatt->save();
                }
                $upThisKat->positionDelivery = 1;
                $upThisKat->save();
            }
               
            $deleteThisDE->delete();    
        }

        $recomToDel = RecomendetProd::where('produkti',$req->id)->first();
        if($recomToDel != NULL){
            $recomToDel->delete();
        }

        Produktet::find($req->id)->delete();
    }


    public function newClick(Request $req){
        $pr = Produktet::find($req->id);
        $pr->visits += 1;
        $pr->save();
    }










    public function editAdminP(Request $req){
        if(isset($req->isWaiter)){
            if (str_contains($req->emri, '&') || str_contains($req->emri, '+') || str_contains($req->emri, '|') || str_contains($req->emri, '||')){
                return redirect('/adminWoContentMngWaiter')->with('error','Dieser Produktname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
            if (str_contains($req->pershkrimi, '&') || str_contains($req->pershkrimi, '+') || str_contains($req->pershkrimi, '|') || str_contains($req->pershkrimi, '||')){
                return redirect('/adminWoContentMngWaiter')->with('error','Diese Produktbeschreibung enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else if(isset($req->isAccountant)){
            if (str_contains($req->emri, '&') || str_contains($req->emri, '+') || str_contains($req->emri, '|') || str_contains($req->emri, '||')){
                return redirect('/AccountantProducts')->with('error','Dieser Produktname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
            if (str_contains($req->pershkrimi, '&') || str_contains($req->pershkrimi, '+') || str_contains($req->pershkrimi, '|') || str_contains($req->pershkrimi, '||')){
                return redirect('/AccountantProducts')->with('error','Diese Produktbeschreibung enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else{
            if (str_contains($req->emri, '&') || str_contains($req->emri, '+') || str_contains($req->emri, '|') || str_contains($req->emri, '||')){
                return redirect('/dashboardContentMng')->with('error','Dieser Produktname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
            if (str_contains($req->pershkrimi, '&') || str_contains($req->pershkrimi, '+') || str_contains($req->pershkrimi, '|') || str_contains($req->pershkrimi, '||')){
                return redirect('/dashboardContentMng')->with('error','Diese Produktbeschreibung enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }
        $saveNewExPro = '';
        $saveNewTyPro = '';
        
        if($req->thisProdsExtrasIn != null){
            foreach(explode('||',$req->thisProdsExtrasIn) as $newEx){
                $thisE = ekstra::find($newEx);
                if( $saveNewExPro == ''){ $saveNewExPro = $thisE->id.'||'.$thisE->emri.'||'.$thisE->qmimi ;}
                else{ $saveNewExPro .= '--0--'.$thisE->id.'||'.$thisE->emri.'||'.$thisE->qmimi; }
            }
        }else{

        }
        if($req->thisProdsTypesIn != null){
            foreach(explode('||',$req->thisProdsTypesIn) as $newTy){
                $thisT = LlojetPro::find($newTy);
                if( $saveNewTyPro == ''){ $saveNewTyPro = $thisT->id.'||'.$thisT->emri.'||'.$thisT->vlera ;}
                else{ $saveNewTyPro .= '--0--'.$thisT->id.'||'.$thisT->emri.'||'.$thisT->vlera; }
            }
        }

        $proEdit = Produktet::find($req->editProId);
        $proEdit->emri = $req->emri;
        $proEdit->pershkrimi = $req->pershkrimi;
        $proEdit->kategoria = $req->kategoriaProdEdit;
        $proEdit->qmimi = $req->qmimi;
        if($req->qmimi2 == ""){
            $proEdit->qmimi2 = 999999;
        }else{
            $proEdit->qmimi2 = $req->qmimi2;
        }

        if($req->thisProdsExtrasIn != null && $saveNewExPro != ''){
            $proEdit->extPro = $saveNewExPro;
        }else{
            $proEdit->extPro = NULL;
        }
        if($req->thisProdsTypesIn != null && $saveNewTyPro != ''){
            $proEdit->type = $saveNewTyPro;
        }else{
            $proEdit->type = NULL;
        }
        $proEdit->doneIn = 8;
        $proEdit->accessableByClients = $req->input('accessByClientsVal');
        $proEdit->save();

        // dd($saveNewExPro);
        // dd($saveNewTyPro);

        if(isset($req->isWaiter)){
            return redirect('/adminWoContentMngWaiter');
        }else if(isset($req->isAccountant)){
            return redirect('/AccountantProducts');
        }else{
            return redirect('/dashboardContentMng');
        }
    
    }



    public function confCodeTakeaway(Request $request){
       
        $nowTime = date('Y-m-d h:i:s');
        $Ucode = (int)$request->codeUser;
        $Code = (int) $request->codeOrigjinal;

        if($Ucode == $Code){
            if(Auth::check()){
                if(Piket::where('klienti_u',Auth::user()->id)->first() != null && Piket::where('klienti_u',Auth::user()->id)->first()->piket < $request->pointsUsing){
                    $confirmData =[
                        'code' => $Code,
                        'timeStart' => $request->dateEnd ,
                        'klientPhone' => $request->klientPhoneNr,
                        'tipValue' => $request->tipValueConfirmTitle,
                        'tipCHF' => number_format($request->tipValueConfirmValue, 2, '.', ''),
                        'pUsed' => $request->pointsUsed,
                        'freeProd' => $request->freeShotPh2,
                        'nameTA' => $request->nameTA02,
                        'lastnameTA' => $request->lastnameTA02,
                        'timeTA' => $request->timeTA02,
                        'codeUsed' => $request->codeUsedValue2
                    ];
                    return view('cart')->with('confirmData',$confirmData);
                }
            }
            if($request->dateEnd > $nowTime){
                
                $newOrder = new Orders;

                $bakshishi =number_format($request->tipValueConfirmValue, 2, '.', '') ;

                $newOrder->nrTable = $request->t;
                $newOrder->statusi = 0;
                $newOrder->byId = $request->userIdCash;
                $newOrder->userEmri = $request->userNameCash;
                $newOrder->userEmail = $request->userEmailCash;
                $newOrder->porosia =  $request->userPorosia;
                $newOrder->payM = $request->userPayM;
                $newOrder->shuma =number_format($request->Shuma, 2, '.', '')-$request->codeUsedValue2 - ($request->pointsUsing  * 0.01) + $bakshishi;
                $newOrder->Restaurant = $request->Res;
		        $newOrder->userPhoneNr = $request->klientPhoneNr;
                $newOrder->tipPer =  $bakshishi;
                $newOrder->TAemri = $request->nameTA02;
                $newOrder->TAmbiemri = $request->lastnameTA02;
                $newOrder->TAtime = $request->timeTA02;
                $newOrder->cuponOffVal = $request->codeUsedValue2;

                $refIfOrPa = OrdersPassive::where('Restaurant',$request->Res)->max('refId') + 1;
                $refIfOr = Orders::where('Restaurant',$request->Res)->max('refId') + 1;
                $nextRefId = 0;
                if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
                $newOrder->refId = $nextRefId;

                $newOrder->TAplz = 'empty';
                $newOrder->TAort = 'empty';
                $newOrder->TAaddress = 'empty';
                $newOrder->TAkoment = 'empty';

                $theR = Restorant::find($request->Res);
                $theR->cashPayOrders += 1;
                $theR->save();

                if(Cart::total() >= Restorant::find($request->Res)->priceFree && $request->freeShotPh2 != 0){
                    $newOrder->freeProdId =  $request->freeShotPh2;
                }
                
  		        // Gen the next indentifikation number (shifra - per Orders) 
                  $orderSh = $this->genShifra($request->Res);
                  $_SESSION['trackMO'] = $orderSh;
                  $newOrder->shifra = $orderSh;
                  $newOrder->save();
              // ------------------------------------------------------------

                // save orders for the COOK
                    foreach(explode('---8---',$newOrder->porosia) as $orOne){
                        $orOne2D = explode('-8-',$orOne);
                        $taProd = Takeaway::find($orOne2D[7]);

                        $newOrForCook = new taDeForCookOr();
                        $newOrForCook->toRes = $request->Res;
                        $newOrForCook->serviceType = 1;
                        $newOrForCook->orderId = $newOrder->id ;
                        if($taProd != NULL){
                            $newOrForCook->prodCat = $taProd->kategoria;
                        }else{
                            $newOrForCook->prodCat = 0;
                        }
                        $newOrForCook->prodName = $orOne2D[0];
                        $newOrForCook->prodType =  $orOne2D[5];
                        $newOrForCook->prodExtra = $orOne2D[2];
                        $newOrForCook->prodQmimi = (float)$orOne2D[4];
                        $newOrForCook->prodComm = $orOne2D[6];
                        $newOrForCook->prodSasia = $orOne2D[3];
                        $newOrForCook->prodSasiaDone = 0;
                        $newOrForCook->save();
                    }
                // -------------------------------------------------------------------

                // register a coupon usage
                    $cUsing  = new couponUsedPhoneNr;
                    $cUsing->toRes = $request->Res;
                    $cUsing->phoneNr = $request->klientPhoneNr;
                    $cUsing->couponId = $request->couponUsedId;
                    $cUsing->save();
                // -------------------------------------------------------------------
               

                if($request->has('marketingSMS')){
                    $markNew = new marketingNr;
                    $markNew->nr = $request->klientPhoneNr;
                    $markNew->save();
                }

                $text = $request->Res;

                // Code per tip
                if($request->tipValueConfirmValue != 0){
                    $newTip = new TipLog;

                    $newTip->shumaPor =number_format((float)($request->Shuma + $bakshishi) - ($request->pointsUsing  * 0.01), 2, '.', '') ;
                    $newTip->tipPer = $request->tipValueConfirmTitle;
                    $newTip->tipTot =  number_format($request->tipValueConfirmValue, 2, '.', '') ;
                    $newTip->toRes = $request->Res;
                    if(Auth::check()){
                        $newTip->klienti = Auth::user()->id;
                    }else{
                        $newTip->klienti = 9999999;
                    }
                    $newTip->save();
                } 

                Cart::destroy();

                // Send Notifications for the Admin
                foreach(User::where([['sFor',$request->Res],['role','5']])->get() as $user){
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'OrderTakeaway',
                        'tableNr' => $request->t
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }

                foreach(User::where([['sFor',$request->Res],['role','55']])->get() as $oneWaiter){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'OrderTakeaway',
                        'tableNr' => $request->t
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
                // event(new newOrder($text));
                
                if($request->userNameCash == "empty"){
                    return redirect('/order')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440));
                }else{
                    return redirect('/order')->with('success', 'Es wurde erfolgreich bestätigt!');
                }
            }else{
                return redirect('/order')->with('success', 'Der Code ist in Ordnung, aber die zulässige Zeit ist abgelaufen!');
            }
        }else{
            $confirmData =[
                'code' => $Code,
                'timeStart' => $request->dateEnd ,
                'klientPhone' => $request->klientPhoneNr,
                'tipValue' => $request->tipValueConfirmTitle,
                'tipCHF' =>  number_format($request->tipValueConfirmValue, 2, '.', ''),
                'pUsed' => $request->pointsUsed,
                'freeProd' => $request->freeShotPh2,
                'nameTA' => $request->nameTA02,
                'lastnameTA' => $request->lastnameTA02,
                'timeTA' => $request->timeTA02,
                'codeUsed' => $request->codeUsedValue
            ];
            return view('cart')->with('confirmData',$confirmData);
        }

    }

  














    public function confNrDelivery(Request $request){

        $this->validate($request, [
                'phoneNr' => 'required|max:10|min:9',
        ]);



        if($request->nameTA01 == '' || $request->lastnameTA01 == '' || $request->addressTA01 == '' || $request->timeTA01 == '' || $request->plzTA01 == '' || $request->ortTA01 == ''){
            $confirmDataError02['errorMsg'] = 'Bitte schreiben Sie zuerst die notwendigen Informationen!';
            return view('cart')->with('confirmDataError02',$confirmDataError02);
        }




        $sendTo = 445566 ;
        if(substr($request->phoneNr, 0, 1) == 0){
            $pref =substr($request->phoneNr, 0, 3);
            if($pref == '075' || $pref == '076' || $pref == '077' || $pref == '078' || $pref == '079'){
                if(strlen($request->phoneNr) == 10){
                    $sendTo = '41'.substr($request->phoneNr, 1, 9);
                }
            }
            
        }else{
            $pref =substr($request->phoneNr, 0, 2);
            if($pref == '75' || $pref == '76' || $pref == '77' || $pref == '78' || $pref == '79'){
                $sendTo = '41'.$request->phoneNr;
            }
            
        }
        if($sendTo != 445566){
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

                $confirmData =[
                    'code' => $request->code,
                    'timeStart' => $nowTime,
                    'klientPhone' => $sendTo2,
                    'tipValue' => $request->tipValueSend,
                    'tipCHF' => number_format($request->tipValueCHFSend, 2, '.', ''),
                    'pUsed' => $request->pointsUsed,
                    'freeProd' => $request->freeShotPh1,
                    'nameTA' => $request->nameTA01,
                    'lastnameTA' => $request->lastnameTA01,
                    'addressTA' => $request->addressTA01,
                    'plzTA' => $request->plzTA01,
                    'ortTA' => $request->ortTA01,
                    'emailTA' => $request->emailTA01,
                    'timeTA' => $request->timeTA01,
                    'komentTA' => $request->komentTA01,
                    'codeUsed' => $request->codeUsedValue,
                    'payWhat' => $request->payAllOrMine
                ];





                $confirmDataError =[
                    'code' => $request->code,
                    'timeStart' => $nowTime,
                    'klientPhone' => $sendTo2,
                    'tipValue' => $request->tipValueSend,
                    'tipCHF' => number_format($request->tipValueCHFSend, 2, '.', ''),
                    'pUsed' => $request->pointsUsed,
                    'freeProd' => $request->freeShotPh1,
                    'nameTA' => $request->nameTA01,
                    'lastnameTA' => $request->lastnameTA01,
                    'addressTA' => $request->addressTA01,
                    'plzTA' => $request->plzTA01,
                    'ortTA' => $request->ortTA01,
                    'emailTA' => $request->emailTA01,
                    'timeTA' => $request->timeTA01,
                    'komentTA' => $request->komentTA01,
                    'codeUsed' => $request->codeUsedValue,
                    'payWhat' => $request->payAllOrMine,
                    'errorMsg' => ''
                ];

                $todayWD = date('w'); //1-mon 2-tue 3 4 5 6 0
                $dSch = DeliverySchedule::where('toRes',$request->res)->first();

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

                $userTime2D = explode(':',$request->timeTA01);
                if($userTime2D[0]<10){ $utP1 = '0'.$userTime2D[0]; }else{ $utP1 = $userTime2D[0]; }
                if($userTime2D[1]<10){ $utP2 = '0'.$userTime2D[1]; }else{ $utP2 = $userTime2D[1]; }
                $userTime = $utP1.':'.$utP2;
                

                if(DeliveryPLZ::where('toRes',$request->res)->get()->count() > 0){
                    if(DeliveryPLZ::where([['toRes',$request->res],['plz',$request->plzTA01]])->get()->count() > 0){

                        if($dSch != NULL && $start1 != '00:00'){
                            if($userTime >= $start1 && $userTime <= $end1 ){
                                return view('cart')->with('confirmData',$confirmData);
                            }else{
                                if($dSch != NULL && $start2 != '00:00'){
                                    if($userTime >= $start2 && $userTime <= $end2 ){
                                        return view('cart')->with('confirmData',$confirmData);
                                    }else{
                                        $confirmDataError['errorMsg'] = 'Wir liefern nicht zu diesem Zeitpunkt, sorry!';
                                        return view('cart')->with('confirmDataError',$confirmDataError);
                                    }
                                }
                            }
                        }else if($dSch != NULL && $start2 != '00:00'){
                            if($userTime >= $start2 && $userTime <= $end2 ){
                                return view('cart')->with('confirmData',$confirmData);
                            }else{
                                $confirmDataError['errorMsg'] = 'Wir liefern nicht zu diesem Zeitpunkt, sorry!';
                                return view('cart')->with('confirmDataError',$confirmDataError);
                            }
                        }else{
                            return view('cart')->with('confirmData',$confirmData);
                        }
                    }else{
                        $confirmDataError['errorMsg'] = 'Wir liefern nicht an diese Adresse, sorry!';
                        return view('cart')->with('confirmDataError',$confirmDataError);
                    }
                }else{
                    if($dSch != NULL && $start1 != '00:00'){
                        if($userTime >= $start1 && $userTime <= $end1 ){
                            return view('cart')->with('confirmData',$confirmData);
                        }else{
                            $confirmDataError['errorMsg'] = 'Wir liefern nicht zu diesem Zeitpunkt, sorry!';
                            return view('cart')->with('confirmDataError',$confirmDataError);
                        }
                    }else if($dSch != NULL && $start2 != '00:00'){
                        if($userTime >= $start2 && $userTime <= $end2 ){
                            return view('cart')->with('confirmData',$confirmData);
                        }else{
                            $confirmDataError['errorMsg'] = 'Wir liefern nicht zu diesem Zeitpunkt, sorry!';
                            return view('cart')->with('confirmDataError',$confirmDataError);
                        }
                    }else{
                        return view('cart')->with('confirmData',$confirmData);
                    }
                }
                




                
        }else{
            return redirect('/order')->with('error',"Ihre Nummer existiert nicht. Bitte versuchen Sie es noch ein mal.");
        }
    }












    public function confCodeDelivery(Request $request){
        
        $nowTime = date('Y-m-d h:i:s');
        $Ucode = (int)$request->codeUser;
        $Code = (int) $request->codeOrigjinal;


        if($Ucode == $Code){
            if(Auth::check()){
                if(Piket::where('klienti_u',Auth::user()->id)->first() != null && Piket::where('klienti_u',Auth::user()->id)->first()->piket < $request->pointsUsing){
                    $confirmData =[
                        'code' => $Code,
                        'timeStart' => $request->dateEnd ,
                        'klientPhone' => $request->klientPhoneNr,
                        'tipValue' => $request->tipValueConfirmTitle,
                        'tipCHF' => number_format($request->tipValueConfirmValue, 2, '.', ''),
                        'pUsed' => $request->pointsUsed,
                        'freeProd' => $request->freeShotPh2,
                        'nameTA' => $request->nameTA02,
                        'lastnameTA' => $request->lastnameTA02,
                        'addressTA' => $request->addressTA02,
                        'plzTA' => $request->plzTA02,
                        'ortTA' => $request->ortTA02,
                        'emailTA' => $request->emailTA02,
                        'timeTA' => $request->timeTA02,
                        'komentTA' => $request->komentTA02,
                        'timeTA' => $request->timeTA02,
                        'codeUsed' => $request->codeUsedValue2
                    ];
                    return view('cart')->with('confirmData',$confirmData);
                }
            }
            if($request->dateEnd > $nowTime){
                
                
                $newOrder = new Orders;

                $bakshishi =number_format($request->tipValueConfirmValue, 2, '.', '') ;

                $newOrder->nrTable = $request->t;
                $newOrder->statusi = 0;
                $newOrder->byId = $request->userIdCash;
                $newOrder->userEmri = $request->userNameCash;
                $newOrder->userEmail = $request->userEmailCash;
                $newOrder->porosia =  $request->userPorosia;
                $newOrder->payM = $request->userPayM;
                $newOrder->shuma =number_format($request->Shuma, 2, '.', '')-$request->codeUsedValue2 - ($request->pointsUsing  * 0.01) + $bakshishi;
                $newOrder->Restaurant = $request->Res;
                $newOrder->userPhoneNr = $request->klientPhoneNr;
                $newOrder->tipPer =  number_format($request->tipValueConfirmValue, 2, '.', '') ;
                $newOrder->TAemri = $request->nameTA02;
                $newOrder->TAmbiemri = $request->lastnameTA02;
                $newOrder->TAtime = $request->timeTA02;
                $newOrder->cuponOffVal = $request->codeUsedValue2;

                $refIfOrPa = OrdersPassive::where('Restaurant',$request->Res)->max('refId') + 1;
                $refIfOr = Orders::where('Restaurant',$request->Res)->max('refId') + 1;
                $nextRefId = 0;
                if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
                $newOrder->refId = $nextRefId;

                $newOrder->TAplz = $request->plzTA02;
                $newOrder->TAort = $request->ortTA02;
                $newOrder->TAaddress = $request->addressTA02;
                $newOrder->TAkoment = $request->komentTA02;


                $theR = Restorant::find($request->Res);
                $theR->cashPayOrders += 1;
                $theR->save();

                if(Cart::total() >= Restorant::find($request->Res)->priceFree && $request->freeShotPh2 != 0){
                    $newOrder->freeProdId =  $request->freeShotPh2;
                }
                
            
            

                // Gen the next indentifikation number (shifra - per Orders) 
                    $orderSh = $this->genShifra($request->Res);
                    $_SESSION['trackMO'] = $orderSh;
                    $newOrder->shifra = $orderSh;
                    $newOrder->save();
                // ------------------------------------------------------------

            

                if($request->has('marketingSMS')){
                    $markNew = new marketingNr;
                    $markNew->nr = $request->klientPhoneNr;
                    $markNew->save();
                }

            
            
                $text = $request->Res;



                // Code per tip
                if($request->tipValueConfirmValue != 0){
                    $newTip = new TipLog;

                    $newTip->shumaPor =number_format((float)($request->Shuma + $bakshishi) - ($request->pointsUsing  * 0.01), 2, '.', '') ;
                    $newTip->tipPer = $request->tipValueConfirmTitle;
                    $newTip->tipTot = $request->tipValueConfirmValue;
                    $newTip->toRes = $request->Res;
                    if(Auth::check()){
                        $newTip->klienti = Auth::user()->id;
                    }else{
                        $newTip->klienti = 9999999;
                    }

                    $newTip->save();
                } 

                // Save the Points
                // if($request->points != 0){
                //     $piketLogNew = new PiketLog;

                //     $piketLogNew->shumaPor = number_format((float)($request->Shuma + $bakshishi) - ($request->pointsUsing  * 0.01), 2, '.', '') ;
                //     $piketLogNew->toRes = $request->Res;
                //     $piketLogNew->klienti_u = Auth::user()->id;
                //     $piketLogNew->order_u =  $newOrder->id;
                //     if($request->pointsUsing != 0){
                //         $piketLogNew->piket = 0-$request->pointsUsing;
                //     }else{
                //         $piketLogNew->piket = $request->points;
                //     }
                //     $piketLogNew->payM = $request->userPayM;
                //     $piketLogNew->save();

                //     $SearchUser = Piket::where('klienti_u',Auth::user()->id)->first();
                //     if($SearchUser == null){
                //         $SearchUser = new Piket;
                //         $SearchUser->klienti_u = Auth::user()->id;
                //         $SearchUser->piket = $request->points;
                //         $SearchUser->level = 1;
                //         $SearchUser->save();
                //     }else{
                //         if($request->pointsUsing != 0){
                //             $SearchUser->piket = $SearchUser->piket - $request->pointsUsing;
                //         }else{
                //             $SearchUser->piket = $SearchUser->piket + $request->points;
                //         }
                //         $SearchUser->save(); 
                //     }

                
                // }
                Cart::destroy();
                foreach(User::where([['sFor',$request->Res],['role','5']])->get() as $user){
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'OrderDelivery',
                        'tableNr' => $request->t
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
                foreach(User::where([['sFor',$request->Res],['role','55']])->get() as $oneWaiter){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'OrderDelivery',
                        'tableNr' => $request->t
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details)); 
                }
                // event(new newOrder($text));
                

                if($request->userNameCash == "empty"){
                    return redirect('/order')->with('success', $orderSh);
                }else{
                    return redirect('/order')->with('success', 'Es wurde erfolgreich bestätigt!');
                }
            }else{
                return redirect('/order')->with('success', 'Der Code ist in Ordnung, aber die zulässige Zeit ist abgelaufen!');
            }
        }else{
            $confirmData =[
                'code' => $Code,
                'timeStart' => $request->dateEnd ,
                'klientPhone' => $request->klientPhoneNr,
                'tipValue' => $request->tipValueConfirmTitle,
                'tipCHF' => number_format($request->tipValueConfirmValue, 2, '.', ''),
                'pUsed' => $request->pointsUsed,
                'freeProd' => $request->freeShotPh2,
                'nameTA' => $request->nameTA02,
                'lastnameTA' => $request->lastnameTA02,
                'addressTA' => $request->addressTA02,
                'plzTA' => $request->plzTA02,
                'ortTA' => $request->ortTA02,
                'emailTA' => $request->emailTA02,
                'timeTA' => $request->timeTA02,
                'komentTA' => $request->komentTA02,
                'timeTA' => $request->timeTA02,
                'codeUsed' => $request->codeUsedValue2
            ];
            return view('cart')->with('confirmData',$confirmData);
        }

    }






    public function removeExtFromCartDel(Request $request){
        // $request->extPro;
        // $request->allExtra;
        $newEl = Cart::get($request->elementId);

        if($newEl->options->ekstras == ''){ $theEx = 'empty'; }else{ $theEx = $newEl->options->ekstras; }
        if($newEl->options->type == ''){ $theTy = 'empty'; }else{ $theTy = $newEl->options->type; }

        $extraProPrice = explode('||',$request->extPro)[1];

        $newPrice = $newEl->price - $extraProPrice;
        $newExtElements = str_replace($request->extPro,'', $request->allExtra);

        $fin = false;
    

            $newnew = Cart::update($request->elementId, ['price' => $newPrice,'options'=>['ekstras' => $newExtElements, 'persh' => $newEl->options->persh, 'type' => $newEl->options->type, 'koment' => $newEl->options->koment]]);
        
        
            return json_encode($newnew);
        
    }



    public function usrPNrStartSession(Request $req){
        $theU = User::find(Auth::User()->id);
        if($theU != NULL){
            if($theU->phoneNr != 'empty'){
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                
                $_SESSION['phoneNrVerified'] = $theU->phoneNr;

                return 'pNrSeCreateSuccess';
                
            }else{
                return 'noPNrRegistred';
            }
        }else{
            return 'errorInvalideData';
        }
    }

    public function logoutPNrSessionRemove(Request $req){
        if( count(Cart::content()) > 0){
            // ka shport aktive 
        }else{
            unset($_SESSION['phoneNrVerified']);
        }
    }























    public function closeOrderByCardResClient(Request $request){
        
        $clPhoneNr = preg_replace('/\s+/', '', $request->klientPhoneNr);
        $nowTime = date('Y-m-d h:i:s');
            // Kontrolli i dyte per piket 
  
                // Deaktivizo numrin GHOSTCart nese egziston
                if($request->ghostCNr != 0){
                    // $request->t $request->Res
                    $findGCActive = ghostCartInUse::where([['toRes',$request->Res],['tableNr',$request->t],['indNr2',$request->ghostCNr],['status','0']])->first();
                    if($findGCActive != NULL){
                        $findGCActive->status = 1;
                        $findGCActive->save();
                    }
                }
                //---------------------------------------
                
                $newOrder = new Orders;

                $bakshishi =number_format($request->tipValueConfirmValue, 2, '.', '') ;

                $allMyOr = array();
                          
                // foreach(Cart::content() as $item){
                    foreach(tabVerificationPNumbers::where([['phoneNr',$clPhoneNr],['status','1']])->get() as $verRecord){
                        array_push($allMyOr,$verRecord->tabOrderId);
                    }  
                // }
            
                $totalPay = 0 ;
                $TabC = TableQrcode::where([['Restaurant',$request->Res],['tableNr',$request->t]])->first()->kaTab;
                $saveOrderAll = '';

                // 1, pay mine
                // 7, pay mine and these
                // 9, pay all 

                // ghostPay Set or not

                if($request->payAllOrMine == 1){
                    if($request->ghostPay == 0){
                        $AllFromTab = TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['tabCode',$TabC]])->whereIn('id',$allMyOr)->get();
                    }else{
                        $AllFromTab = TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['tabCode',$TabC],['specStat',$request->ghostPay]])->whereIn('id',$allMyOr)->get();
                    }
                }else if($request->payAllOrMine == 7){
                    foreach(explode('||',$request->payAllOrMineSelected) as $nrsToPay){
                        if($request->ghostPay == 0){
                            foreach(tabVerificationPNumbers::where('phoneNr',$nrsToPay)->get() as $nrVerRecords){
                                array_push($allMyOr,$nrVerRecords->tabOrderId);
                                $sendRemoveProd = $nrVerRecords->phoneNr.'||'.$nrVerRecords->tabOrderId.'||a';
                                event(new removePaidProduct($sendRemoveProd));
                            }
                        }else{
                            foreach(tabVerificationPNumbers::where([['phoneNr',$nrsToPay],['specStat',$request->ghostPay]])->get() as $nrVerRecords){
                                array_push($allMyOr,$nrVerRecords->tabOrderId);
                                $sendRemoveProd = $nrVerRecords->phoneNr.'||'.$nrVerRecords->tabOrderId.'||a';
                                event(new removePaidProduct($sendRemoveProd));
                            }
                        }
                    }
                    if($request->payAllOrMineProSelected != ''){
                        foreach(explode('||',$request->payAllOrMineProSelected) as $prodsToPay){
                            array_push($allMyOr,$prodsToPay);
                            $verCodeRec = tabVerificationPNumbers::where('tabOrderId',$prodsToPay)->first(); 
                            $sendRemoveProd = $verCodeRec->phoneNr.'||'.$prodsToPay.'||a';
                            // event(new removePaidProduct($sendRemoveProd));
                        }
                    }
                    $AllFromTab = TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['tabCode',$TabC]])->whereIn('id',$allMyOr)->get();
                }else if($request->payAllOrMine == 9){
                    if($request->ghostPay == 0){
                        $AllFromTab = TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['tabCode',$TabC]])->get();
                    }else{
                        $AllFromTab = TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['tabCode',$TabC],['specStat',$request->ghostPay]])->get();
                    }
                    foreach($AllFromTab as $prodsToPay22){
                        $verCodeRec22 = tabVerificationPNumbers::where('tabOrderId',$prodsToPay22->id)->first(); 
                        $sendRemoveProd = $verCodeRec22->phoneNr.'||'.$verCodeRec22.'||a';
                        // event(new removePaidProduct($sendRemoveProd));
                    } 
                }

                $tVerNrForNotifications = tabVerificationPNumbers::where([['tabCode',$TabC],['status','1']])->get();

                foreach($AllFromTab as $tOr){
                    // Deactivate phone Nr verification 
                    $pnvRecord = tabVerificationPNumbers::where('tabOrderId',$tOr->id)->first();
                    $pnvRecord->status = 0;
                    $pnvRecord->save();

                    if($tOr->status != 9 ){
                        if($tOr->OrderType != null){ $regType = explode('||',$tOr->OrderType)[0]; }else{  $regType = ''; }

                        if($request->t == 500){
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
                        $findGCActive = ghostCartInUse::where([['toRes',$request->Res],['tableNr',$request->t],['indNr2',$clPhoneNr2D[1]],['status','0']])->first();
                        if($findGCActive != NULL){
                            $findGCActive->status = 1;
                            $findGCActive->save();
                        }
                    }
                    //---------------------------------------
                }

                if($saveOrderAll != ''){
                    if($request->couponUsed != 0){
                        $cou = Cupon::find($request->couponUsed);
                        if($cou != NULL){
                            if($cou->typeCo == 1){
                                $beforeTotal =number_format($totalPay-$request->tipValueConfirmValue, 2, '.', '');
                                // $MinusVal = number_format(($cou['valueOff']*0.01) * $beforeTotal, 2, '.', '');
                                $MinusVal = number_format($request->codeUsedValue, 2, '.', '');
                                $ProdsVal = 'empty';
                            }else if($cou->typeCo == 2){
                                $MinusVal = $cou['valueOffMoney'];
                                $ProdsVal = 'empty';
                            }else if($cou->typeCo == 3){
                                $MinusVal = 0;
                                $ProdsVal = $cou['prodName'];
                            }

                            $cUsing  = new couponUsedPhoneNr;
                            $cUsing->toRes =$request->Res;
                            $cUsing->phoneNr =$clPhoneNr;
                            $cUsing->couponId =$cou->id;
                            $cUsing->save();
                        }else{
                            $MinusVal = 0;
                            $ProdsVal = 'empty';
                        }
                    }else{
                        $MinusVal = 0;
                        $ProdsVal = 'empty';
                    }

                    $newOrder->nrTable = $request->t;
                    $newOrder->statusi = 0;
                    $newOrder->byId = $request->userIdCash;
                    $newOrder->userEmri = $request->userNameCash;
                    $newOrder->userEmail = $request->userEmailCash;
                    if($saveOrderAll == ''){
                        $newOrder->porosia = 'empty';
                    }else{
                        $newOrder->porosia = $saveOrderAll; //$request->userPorosia;
                    }
                    $newOrder->payM = $request->userPayM;
                    $newOrder->shuma =number_format($totalPay, 2, '.', '')-$MinusVal - ($request->pointsUsing  * 0.01) + $bakshishi; //$request->Shuma
                    $newOrder->Restaurant = $request->Res;
                    $newOrder->userPhoneNr = $clPhoneNr;
                    $newOrder->tipPer =  number_format($request->tipValueConfirmValue, 2, '.', '') ;
                    $newOrder->TAemri = $request->nameTA02;
                    $newOrder->TAmbiemri = $request->lastnameTA02;
                    $newOrder->TAtime = $request->timeTA02;
                    $newOrder->cuponOffVal = $MinusVal;
                    $newOrder->cuponProduct = $ProdsVal;

                    $refIfOrPa = OrdersPassive::where('Restaurant',$request->Res)->max('refId') + 1;
                    $refIfOr = Orders::where('Restaurant',$request->Res)->max('refId') + 1;
                    $nextRefId = 0;
                    if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
                    $newOrder->refId = $nextRefId;
                
                    $newOrder->TAplz = 'empty';
                    $newOrder->TAort = 'empty';
                    $newOrder->TAaddress = 'empty';
                    $newOrder->TAkoment = 'empty';

                    $theR = Restorant::find($request->Res);
                    $theR->cashPayOrders += 1;
                    $theR->save();
                    

                    if(TabOrder::where([['tableNr',$request->t],['toRes',$request->Res],['tabCode','!=',0],['status','<',2]])->get()->count() <= 0){
                        $tableGet = TableQrcode::where([['Restaurant',$request->Res],['tableNr',$request->t]])->first();
                        $tableGet->kaTab = 0;
                        $tableGet->save();

                        $endOfTAB = 'true';
                    }else{
                        $endOfTAB = 'false';
                    }


                    if($totalPay >= Restorant::find($request->Res)->priceFree && $request->freeShotPh2 != 0){
                        $newOrder->freeProdId =  $request->freeShotPh2;
                    }
                    
                  
                    // Gen the next indentifikation number (shifra - per Orders) 
                        $orderSh = $this->genShifra($request->Res);
                        $_SESSION['trackMO'] = $orderSh;
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


                    if($request->has('marketingSMS')){
                        $markNew = new marketingNr;
                        $markNew->nr = $clPhoneNr;
                        $markNew->save();
                    }
 
                    $text = $request->Res;



                    // Code per tip
                    if($request->tipValueConfirmValue != 0){
                        $newTip = new TipLog;

                        $newTip->shumaPor =number_format((float)($request->Shuma + $bakshishi) - ($request->pointsUsing  * 0.01), 2, '.', '') ;
                        $newTip->tipPer = $request->tipValueConfirmTitle;
                        $newTip->tipTot = $request->tipValueConfirmValue;
                        $newTip->toRes = $request->Res;
                        if(Auth::check()){
                            $newTip->klienti = Auth::user()->id;
                        }else{
                            $newTip->klienti = 9999999;
                        }

                        $newTip->save();
                    } 

                    $payerPhNr = $_SESSION['phoneNrVerified'];
                    if(!Auth::check()){
                        unset($_SESSION['phoneNrVerified']);
                    }

                    if($request->ghostPay != 0){
                        unset($_SESSION['adminToClProdsRec']);
                    }

             
                    // event(new newOrder($text));

                    if((int)$request->payAllOrMine == 9){
                        $toDoCart = '9';
                        $sendPayAllOrMineSelected = 'none';
                        $ToTable=$request->Res.'-0-'.$request->t.'-0-'.$toDoCart;
                    }else if((int)$request->payAllOrMine == 7){
                        $toDoCart = '7';
                        $sendPayAllOrMineSelected = $request->payAllOrMineSelected;
                        $ToTable=$request->Res.'-0-'.$request->t.'-0-'.$toDoCart.'-0-'.$request->payAllOrMineSelected;
                    }else if((int)$request->payAllOrMine == 1){
                        $toDoCart = '1';
                        $sendPayAllOrMineSelected = 'none';
                        $ToTable=$request->Res.'-0-'.$request->t.'-0-'.$toDoCart;
                    }
                    event(new CartMsg($ToTable));

                    $phoneNrActive = array();
                    
                    foreach($tVerNrForNotifications as $nrVers){
                        if(!in_array($nrVers->phoneNr,$phoneNrActive) && !str_contains($nrVers->phoneNr, '|') && $nrVers->phoneNr != "0770000000"){
                            // $payerPhNr
                            $newNotifyClient = new notifyClient();
                            $newNotifyClient->data = json_encode([
                                'toDoCart' =>(int)$request->payAllOrMine,
                                'payAllOrMineSelected' => $sendPayAllOrMineSelected
                            ]);
                            $newNotifyClient->for = "CartMsg";
                            $newNotifyClient->toRes = $request->Res;
                            $newNotifyClient->tableNr = $request->t;
                            $newNotifyClient->readInd = 0;
                            $newNotifyClient->clPhoneNr = $nrVers->phoneNr;
                            $newNotifyClient->save();
                            array_push($phoneNrActive,$nrVers->phoneNr);
                        }
                    }
                    foreach(User::where([['sFor',$request->Res],['role','5']])->get() as $user){
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'Order',
                            'tableNr' => $request->t
                        ];
                        $user->notify(new \App\Notifications\NewOrderNotification($details));

                        if($endOfTAB == 'true'){
                            $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$request->t],['statActive','1']])->first();
                            if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();}
                        }else{
                            foreach($AllFromTab as $oneTabOSel){
                                $newOrAlertToDel = newOrdersAdminAlert::where([['adminId',$user->id],['tableNr',$request->tableNr],['tabOrderId',$oneTabOSel->id],['statActive','1']])->first();
                                if($newOrAlertToDel != NULL){$newOrAlertToDel->delete();} 
                            }
                        }
                    }

                    foreach(User::where([['sFor',$request->Res],['role','55']])->get() as $oneWaiter){
                        $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $request->t]])->first();
                        if($aToTable != NULL && $aToTable->statusAct == 1){
                            // register the notification ...
                            $details = [
                                'id' => $newOrder->id,
                                'type' => 'Order',
                                'tableNr' => $request->t
                            ];
                            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                        }
                    }

                    
                    foreach(User::where([['sFor',$request->Res],['role','54']])->get() as $oneCook){
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'Order',
                            'tableNr' => $request->t
                        ];
                        $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
                    }

                    Cart::destroy();
                    if(Cart::content()->count() > 0){
                        $this->emptyTheCarFunRek();
                    }
                    
                    return redirect('/order')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440))->withCookie(cookie('ghostCartReturn', 'not' , 5))->withCookie(cookie('retSessionCK', 'not', 20));
                  
                }else{
                    Cart::destroy();
                    if(Cart::content()->count() > 0){
                        $this->emptyTheCarFunRek();
                    }
                    return redirect('/order')->with('success', 'Es wurde erfolgreich bestätigt!');
                }
    }

}