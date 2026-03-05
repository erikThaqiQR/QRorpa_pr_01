<?php

namespace App\Http\Controllers;

use Cart;
use QRCode;
use App\User;
use App\Orders;
use App\TipLog;
use App\kategori;
use App\Takeaway;
use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use App\OrdersPassive;
use App\taDeForCookOr;
use App\TakeawaySchedule;
use App\couponUsedPhoneNr;
use Illuminate\Http\Request;
use SpryngApiHttpPhp\Client;
use App\cooksProductSelection;
use App\tablesAccessToWaiters;
use Illuminate\Support\Facades\Auth;

class TakeawayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('adminPanel/adminIndex');
    }
    public function openSortingTel(){
        return view('adminPanel/adminIndex');
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



    private function genShifra($res){
        $orSh = rand(1111,9999);
        $orShFi = $res.'|'.$orSh;

        if(Orders::whereDate('created_at', Carbon::today())->where([['shifra', $orShFi],['statusi','<','2']])->first() != NULL){
            return $this->genShifra($res);
        }else{
            return $orShFi;
        }        
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $newTakeaway = new Takeaway;
        $newTakeaway->emri = $request->emri ;
        $newTakeaway->pershkrimi = $request->pershkrimi ;
        $newTakeaway->kategoria = $request->kategoria ;
        $newTakeaway->qmimi = $request->qmimi ;
        if($request->qmimi2 != '' || $request->qmimi2 != NULL ){
            $newTakeaway->qmimi2 = $request->qmimi2 ;
        }else{
            $newTakeaway->qmimi2 = 999999 ;
        }
        $newTakeaway->extPro = $request->extPro ;
        $newTakeaway->type = $request->typePro ;
        $newTakeaway->toRes = $request->restaurant ;
        $newTakeaway->accessableByClients = $request->accessByClientsValInd ;

        if(Takeaway::where('kategoria',$request->kategoria)->count() > 0){
            $newTakeaway->position = Takeaway::where('kategoria',$request->kategoria)->max('position')+1 ;
        }else{
            $newTakeaway->position = 1;
            if(Takeaway::where('toRes',$request->toRes)->count() == 0){
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

        $newTakeaway->save();

        if(isset($request->isWaiter)){
            return redirect()->route ('admWoMng.adminWoTakeawayWaiter');
        }else{
            return redirect()->route ('takeaway.index');
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
    public function update(Request $request){
        $ta = Takeaway::find($request->id);

        $ta->emri = $request->emri;
        $ta->pershkrimi = $request->pershkrimi;
        $ta->qmimi = $request->qmimi;
        if($request->qmimi2 == null){
            $ta->qmimi2 = 999999;
        }else{
            $ta->qmimi2 = $request->qmimi2;
        }
        $ta->mwstForPro = number_format($request->mwst, 2, '.', ' ');
        $ta->accessableByClients = $request->acsCl;;

        $ta->save();
    }



    public function addToRec(Request $req){
        $this->validate($req, [
            'id' => 'required',
        ]);


        $ta = Takeaway::find($req->id);
        $ta->recomendet = 1;

                //get name .etc
                $fileNameOriginal = $req->file('foto')->getClientOriginalName();
                //get just the name
                $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
                // get extension
                $extension = $req->file('foto')->getClientOriginalExtension();
                $fileNameStore = $fileName.'_'.time().'.'.$extension;
  
                // Upload
                $path = $req->file('foto')->move('storage/TakeawayRecomendet', $fileNameStore);
                $ta->recomendetPic = $fileNameStore;
            $ta->recomendetNr = Takeaway::where([['toRes', $ta->toRes],['recomendet', 1]])->get()->max('recomendetNr') + 1; 
        $ta->save();

        if(isset($req->isWaiter)){
            return redirect()->route ('admWoMng.adminWoTakeawayWaiter');
        }else{
            return redirect()->route ('takeaway.index');
        }
    }


    public function removeRec(Request $req){
        $ta = Takeaway::find($req->id);

        foreach(Takeaway::where([['toRes', $ta->toRes],['recomendet', 1],['recomendetNr','>', $ta->recomendetNr]])->get() as $others){
            $others->recomendetNr -= 1;
            $others->save();
        }
        $ta->recomendet = 0;
        $ta->save();
    }








    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req){
        $theT = Takeaway::find($req->id);
        foreach(Takeaway::where([['kategoria',$theT->kategoria],['position','>',$theT->position]])->get() as $upT){
            $upT->position = $upT->position-1;
            $upT->save();
        }
        $hasMore = 'yes';
        
        if(Takeaway::where('kategoria',$theT->kategoria)->count() == 1){
            $upThisKat = kategori::find($theT->kategoria);
           
            foreach(kategori::where([['toRes',$theT->toRes],['positionTakeaway','>', $upThisKat->positionTakeaway]])->get() as $upCatt){
                $upCatt->positionTakeaway = $upCatt->positionTakeaway-1;
                $upCatt->save();
            }

            $upThisKat->positionTakeaway = 1;
            $upThisKat->save();
            $hasMore = 'no';
        }
        $theT->delete();

        return $hasMore;
    }

    public function destroyAll(Request $req){
        foreach(Takeaway::where('toRes', $req->id)->get() as $allTA){
            $theKaa = kategori::find($allTA->kategoria);
            $theKaa->positionTakeaway = 1;
            $theKaa->save();

            $allTA->delete();
        }
    }




    public function addOne(Request $req){
        $coPro = Produktet::find($req->id);
        $newTA = new Takeaway;

        $newTA->emri = $coPro->emri;
        $newTA->pershkrimi = $coPro->pershkrimi;
        $newTA->kategoria = $coPro->kategoria;
        $newTA->qmimi = $coPro->qmimi;
        $newTA->qmimi2 = $coPro->qmimi2;
        $newTA->extPro = $coPro->extPro;
        $newTA->type = $coPro->type;
        $newTA->toRes = $coPro->toRes;
        $newTA->restrictPro = $coPro->restrictPro;
        $newTA->doneIn = $coPro->doneIn;
        $newTA->accessableByClients = $coPro->accessableByClients;
        if(Takeaway::where('kategoria',$coPro->kategoria)->count() > 0){
            $newTA->position = Takeaway::where('kategoria',$coPro->kategoria)->max('position')+1 ;
            $isFirst = 'no';
        }else{
            $newTA->position = 1;
            if(Takeaway::where('toRes',$coPro->toRes)->count() == 0){
                $upThisKat = kategori::find($newTA->kategoria);
                $upThisKat->positionTakeaway = 1;
                $upThisKat->save();
            }else{
                $upThisKat = kategori::find($newTA->kategoria);
                $upThisKat->positionTakeaway = kategori::where('toRes',$upThisKat->toRes)->max('positionTakeaway')+1;
                $upThisKat->save();
            }
            $isFirst = 'yes';
        }
        $newTA->prod_id = $coPro->id;
        $newTA->toReportCat = $coPro->toReportCat;
        $newTA->save();

        return $isFirst;
    }


    public function addAll(Request $req){
        foreach(Produktet::where('toRes',$req->id)->get() as $allPro){
            if(Takeaway::where('prod_id', $allPro->id)->first() == null){
                $newTA = new Takeaway;

                $newTA->emri = $allPro->emri;
                $newTA->pershkrimi = $allPro->pershkrimi;
                $newTA->kategoria = $allPro->kategoria;
                $newTA->qmimi = $allPro->qmimi;
                $newTA->qmimi2 = $allPro->qmimi2;
                $newTA->extPro = $allPro->extPro;
                $newTA->type = $allPro->type;
                $newTA->toRes = $allPro->toRes;
                $newTA->restrictPro = $allPro->restrictPro;
                $newTA->doneIn = $allPro->doneIn;
                $newTA->accessableByClients = $allPro->accessableByClients;

                if(Takeaway::where('kategoria',$allPro->kategoria)->count() > 0){
                    $newTA->position = Takeaway::where('kategoria',$allPro->kategoria)->max('position')+1 ;
                }else{
                    $newTA->position = 1;       
                    if(Takeaway::where('toRes',$allPro->toRes)->count() == 0){
                        $upThisKat = kategori::find($newTA->kategoria);
                        $upThisKat->positionTakeaway = 1;
                        $upThisKat->save();
                    }else{
                        $upThisKat = kategori::find($newTA->kategoria);
                        $upThisKat->positionTakeaway = kategori::where('toRes',$upThisKat->toRes)->max('positionTakeaway')+1;
                        $upThisKat->save();
                    }
                }

                $newTA->prod_id = $allPro->id;
                $newTA->toReportCat = $allPro->toReportCat;
                $newTA->save();
            }
        }
    }





    public function minusOneRec(Request $req){
        $TARecPro = Takeaway::find($req->id);
        

        $backOne = $TARecPro->recomendetNr; 
        $minOne= $backOne - 1;
        $TARecProCNG = Takeaway::where([['toRes', $TARecPro->toRes],['recomendetNr',$minOne]])->first();
        $TARecProCNG->recomendetNr = $backOne;
        $TARecProCNG->save();

        $TARecPro->recomendetNr -= 1;
        $TARecPro->save();


    }
    public function plusOneRec(Request $req){
        $TARecPro = Takeaway::find($req->id);
        

        $frontOne = $TARecPro->recomendetNr; 
        $pOne = $frontOne + 1;
        $TARecProCNG = Takeaway::where([['toRes', $TARecPro->toRes],['recomendetNr',$pOne]])->first();
        $TARecProCNG->recomendetNr = $frontOne ;
        $TARecProCNG->save();

        $TARecPro->recomendetNr += 1;
        $TARecPro->save();
    }

















    public function scheduleSet(Request $req){

        if( TakeawaySchedule::where('toRes', $req->res)->first() == null){
            $newTS = new TakeawaySchedule;
        }else{
            $newTS = TakeawaySchedule::where('toRes', $req->res)->first();
        }
 
        $newTS->toRes = $req->res;

        $newTS->day11S = ($req->day11In == '' ? '00:00' : $req->day11In);
        $newTS->day11E = ($req->day11Out == '' ? '00:00' : $req->day11Out);
        $newTS->day21S = ($req->day21In == '' ? '00:00' : $req->day21In);
        $newTS->day21E = ($req->day21Out == '' ? '00:00' : $req->day21Out);
        $newTS->day12S = ($req->day12In == '' ? '00:00' : $req->day12In);
        $newTS->day12E = ($req->day12Out == '' ? '00:00' : $req->day12Out);
        $newTS->day22S = ($req->day22In == '' ? '00:00' : $req->day22In);
        $newTS->day22E = ($req->day22Out == '' ? '00:00' : $req->day22Out);
        $newTS->day13S = ($req->day13In == '' ? '00:00' : $req->day13In);
        $newTS->day13E = ($req->day13Out == '' ? '00:00' : $req->day13Out);
        $newTS->day23S = ($req->day23In == '' ? '00:00' : $req->day23In);
        $newTS->day23E = ($req->day23Out == '' ? '00:00' : $req->day23Out);
        $newTS->day14S = ($req->day14In == '' ? '00:00' : $req->day14In);
        $newTS->day14E = ($req->day14Out == '' ? '00:00' : $req->day14Out);
        $newTS->day24S = ($req->day24In == '' ? '00:00' : $req->day24In);
        $newTS->day24E = ($req->day24Out == '' ? '00:00' : $req->day24Out);
        $newTS->day15S = ($req->day15In == '' ? '00:00' : $req->day15In);
        $newTS->day15E = ($req->day15Out == '' ? '00:00' : $req->day15Out);
        $newTS->day25S = ($req->day25In == '' ? '00:00' : $req->day25In);
        $newTS->day25E = ($req->day25Out == '' ? '00:00' : $req->day25Out);
        $newTS->day16S = ($req->day16In == '' ? '00:00' : $req->day16In);
        $newTS->day16E = ($req->day16Out == '' ? '00:00' : $req->day16Out);
        $newTS->day26S = ($req->day26In == '' ? '00:00' : $req->day26In);
        $newTS->day26E = ($req->day26Out == '' ? '00:00' : $req->day26Out);
        $newTS->day10S = ($req->day10In == '' ? '00:00' : $req->day10In);
        $newTS->day10E = ($req->day10Out == '' ? '00:00' : $req->day10Out);
        $newTS->day20S = ($req->day20In == '' ? '00:00' : $req->day20In);
        $newTS->day20E = ($req->day20Out == '' ? '00:00' : $req->day20Out);

        $newTS->save();
    }





    public function changeCategoryOrder(Request $req){
        // catId:
        // newPoz:
        $theK = kategori::find($req->catId);
        $crrPoz = $theK->positionTakeaway;
        $theK->positionTakeaway = $req->newPoz;
        if($crrPoz < $req->newPoz){
            foreach(kategori::where([['toRes',$theK->toRes],['positionTakeaway','>',$crrPoz],['positionTakeaway','<=',$req->newPoz]])->get() as $chngCat){
                $chngCat->positionTakeaway = $chngCat->positionTakeaway-1;
                $chngCat->save();
            }
        }else if($crrPoz > $req->newPoz){
            foreach(kategori::where([['toRes',$theK->toRes],['positionTakeaway','<',$crrPoz],['positionTakeaway','>=',$req->newPoz]])->get() as $chngCat){
                $chngCat->positionTakeaway = $chngCat->positionTakeaway+1;
                $chngCat->save();
            }
        }
        $theK->save();
    }


    public function changeProductOrder(Request $req){
        // prodId
        // newPoz:

        $theProT = Takeaway::find($req->prodId);
        $crrPoz = $theProT->position;
        $theProT->position = $req->newPoz;

        if($crrPoz < $req->newPoz){
            foreach(Takeaway::where([['kategoria', $theProT->kategoria],['position','>',$crrPoz],['position','<=',$req->newPoz]])->get() as $chngProd){
                $chngProd->position = $chngProd->position-1;
                $chngProd->save();
            }
        }else if($crrPoz > $req->newPoz){
            foreach(Takeaway::where([['kategoria', $theProT->kategoria],['position','<',$crrPoz],['position','>=',$req->newPoz]])->get() as $chngProd){
                $chngProd->position = $chngProd->position+1;
                $chngProd->save();
            }
        }
        $theProT->save();
    }





    public function checkTakeawayOrderCodeValidation(Request $req){
        // res:
        // shif:
        $tOr = Orders::where([['Restaurant',$req->res],['shifra',$req->shif],['statusi','<',3]])->first();
        if($tOr != NULL){
            return 'yesShow';
        }else{
            return redirect('/?Res='.$req->res.'&t='.$req->t)->withCookie(cookie('trackMO', $req->shif, 30));
        }
    }
    public function checkTakeawayOrderCodeValidation2(Request $req){
        // res:
        // shif:
        $tOr = Orders::where([['Restaurant',$req->res],['shifra',$req->shif],['statusi','<',3]])->first();
        if($tOr != NULL){
            return 'yesShow||'.$tOr->statusi.'||'.$tOr->cancelComm;
        }else{
            return redirect('/?Res='.$req->res.'&t='.$req->t)->withCookie(cookie('trackMO', 'not', 60));
        }
    }












    public function sendPhoneNrTACashPay(Request $req){
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
            if($req->cUsed != 0){
                if(couponUsedPhoneNr::where([['toRes',$req->res],['phoneNr',$req->phNr],['couponId',$req->cUsed]])->first() != NULL){
                    return 'couponUsedOnce';
                }
            }

            $randCode = rand(111111,999999);

            $sendTo2 = (int)$sendTo;
            $nowTime = date('Y-m-d h:i:s');

            // Disa numra te telefonit perdoren per demo 
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
        }else{
            return 'falseNR';
        }
    }

    public function closeTheOrder(Request $req){
        if(Cart::count() > 0){
            if($req->codeByCl != $req->codeCreated){
                return 'falseCode';
            }else{
                $newOrder = new Orders;

                $bakshishi =number_format((float)$req->tipval, 2, '.', '') ;
        
                $newOrder->nrTable = $req->t;
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

                $newOrderOPay = '';
                foreach(explode('---8---',$req->userPorosiaOPay) as $orProOne){
                    $orProOne2D = explode('-8-',$orProOne);
                    if(!isset($orProOne2D[8])){
                        if($newOrder->nrTable == 500){
                            if($req->phNr == '0770000000'){
                                $theP = Produktet::find($orProOne2D[7]);
                                if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
                            }else{
                                $theP = Takeaway::find($orProOne2D[7]);
                                if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
                            }
                        }else{
                            $theP = Produktet::find($orProOne2D[7]);
                            if($theP != Null){ $grId = $theP->toReportCat; }else{ $grId = 0; }
                        }
                    }
                    if($newOrderOPay == ''){
                        $newOrderOPay = $orProOne.'-8-'.$grId;
                    }else{
                        $newOrderOPay .= '---8---'.$orProOne.'-8-'.$grId;
                    }
                }
            
                $newOrder->porosia = $newOrderOPay;
                if(isset($req->payMethod)){
                    $newOrder->payM = $req->payMethod;
                }else{
                    $newOrder->payM = 'Barzahlungen';
                }
                $newOrder->shuma =number_format((float)$req->ShumaOPay, 2, '.', '')-(float)$req->codeUsedValueID2 - (0 * 0.01) + $bakshishi;
                $newOrder->Restaurant = (int)$req->res;
                $newOrder->userPhoneNr = $req->phNr;
                $newOrder->tipPer =  $bakshishi;
                $newOrder->TAemri = $req->clName;
                $newOrder->TAmbiemri = $req->clLastname;
                $newOrder->TAtime = 'empty';
                $newOrder->cuponOffVal = (float)$req->codeUsedValueID2;
                
                $refIfOrPa = OrdersPassive::where('Restaurant',(int)$req->res)->max('refId') + 1;
                $refIfOr = Orders::where('Restaurant',(int)$req->res)->max('refId') + 1;
                $nextRefId = 0;
                if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
                $newOrder->refId = $nextRefId;
        
                $newOrder->TAplz = 'empty';
                $newOrder->TAort = 'empty';
                $newOrder->TAaddress = 'empty';
                $newOrder->TAkoment = 'empty';
                $newOrder->TAbowlingLine = $req->bowlingNr;
        
                $theR = Restorant::find((int)$req->res);
                $theR->cashPayOrders += 1;
                $theR->save();
        
                if(Cart::total() >= Restorant::find($req->res)->priceFree && (int)$req->freeShotPh2Id != 0){
                    $newOrder->freeProdId = (int)$req->freeShotPh2Id;
                }
        
                // Gen the next indentifikation number (shifra - per Orders) 
                    $orderSh = $this->genShifra($req->res);
                    $_SESSION['trackMO'] = $orderSh;
                    $newOrder->shifra = $orderSh;
                    $newOrder->save();
                // ------------------------------------------------------------

                // orders qrCode for the bill
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
                        $taProd = Takeaway::find($orOne2D[7]);

                        $newOrForCook = new taDeForCookOr();
                        $newOrForCook->toRes = $req->res;
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
                        $newOrForCook->prodSasiaDone = 0;
                        $newOrForCook->save();
                    }
                // -------------------------------------------------------------------
        
                // Code per tip
                if((float)$req->tipval != 0){
                    $newTip = new TipLog;
        
                    $newTip->shumaPor =number_format((float)((float)$req->ShumaOPay + $bakshishi) - (0 * 0.01), 2, '.', '') ;
                    $newTip->tipPer = $req->tiptitle;
                    $newTip->tipTot =  number_format((float)$req->tipval, 2, '.', '') ;
                    $newTip->toRes = $req->res;
                    if(Auth::check()){
                        $newTip->klienti = Auth::user()->id;
                    }else{
                        $newTip->klienti = 9999999;
                    }
                    $newTip->save();
                } 
        
                
                Cart::destroy();
        
                // Send Notifications for the Admin
                foreach(User::where([['sFor',(int)$req->res],['role','5']])->get() as $user){
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'OrderTakeaway',
                        'tableNr' => (int)$req->t
                    ];
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
                foreach(User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
                    // register the notification ...
                    $details = [
                        'id' => $newOrder->id,
                        'type' => 'OrderTakeaway',
                        'tableNr' => (int)$req->t
                    ];
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
                foreach(User::where([['sFor',$req->res],['role','54']])->get() as $oneCook){
                    if(cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Takeaway']])->first() != NULL){
                        // register the notification ...
                        $details = [
                            'id' => $newOrder->id,
                            'type' => 'cookPanelUpdateTaToPay',
                            'prodId' => '0'
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
                // event(new newOrder($text));
        
                return redirect('/order')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440))->withCookie(cookie('phNrConfirmed', $req->phNr, 1440));
            }
        }
    }












    public function cashPayTAUseUserPNumber(Request $req){
        $newOrder = new Orders;

        $bakshishi =number_format((float)$req->tipval, 2, '.', '') ;

        $newOrder->nrTable = $req->t;
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
      
        $newOrder->porosia =  $req->userPorosiaOPay;
        if(isset($req->payMethod)){
            $newOrder->payM = $req->payMethod;
        }else{
            $newOrder->payM = 'Barzahlungen';
        }
        $newOrder->shuma =number_format((float)$req->ShumaOPay, 2, '.', '')-(float)$req->codeUsedValueID2 - (0 * 0.01) + $bakshishi;
        $newOrder->Restaurant = (int)$req->res;
        $newOrder->userPhoneNr = $req->phNr;
        $newOrder->tipPer =  $bakshishi;
        $newOrder->TAemri = $req->clName;
        $newOrder->TAmbiemri = $req->clLastname;
        $newOrder->TAtime = $req->clTime;
        $newOrder->cuponOffVal = (float)$req->codeUsedValueID2;

        $refIfOrPa = OrdersPassive::where('Restaurant',(int)$req->res)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',(int)$req->res)->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->TAplz = 'empty';
        $newOrder->TAort = 'empty';
        $newOrder->TAaddress = 'empty';
        $newOrder->TAkoment = 'empty';
        $newOrder->TAbowlingLine = $req->bowlingNr;

        $theR = Restorant::find((int)$req->res);
        $theR->cashPayOrders += 1;
        $theR->save();

        if(Cart::total() >= Restorant::find($req->res)->priceFree && (int)$req->freeShotPh2Id != 0){
            $newOrder->freeProdId = (int)$req->freeShotPh2Id;
        }

        // Gen the next indentifikation number (shifra - per Orders) 
			$orderSh = $this->genShifra($req->res);
			$_SESSION['trackMO'] = $orderSh;
			$newOrder->shifra = $orderSh;
			$newOrder->save();
        // ------------------------------------------------------------


        // save orders for the COOK
        foreach(explode('---8---',$newOrder->porosia) as $orOne){
            $orOne2D = explode('-8-',$orOne);
            $taProd = Takeaway::find($orOne2D[7]);

            $newOrForCook = new taDeForCookOr();
            $newOrForCook->toRes = $req->res;
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
        if((float)$req->tipval != 0){
            $newTip = new TipLog;

            $newTip->shumaPor =number_format((float)((float)$req->ShumaOPay + $bakshishi) - (0 * 0.01), 2, '.', '') ;
            $newTip->tipPer = $req->tiptitle;
            $newTip->tipTot =  number_format((float)$req->tipval, 2, '.', '') ;
            $newTip->toRes = $req->res;
            if(Auth::check()){
                $newTip->klienti = Auth::user()->id;
            }else{
                $newTip->klienti = 9999999;
            }
            $newTip->save();
        } 

        Cart::destroy();

        // Send Notifications for the Admin
        foreach(User::where([['sFor',(int)$req->res],['role','5']])->get() as $user){
            $details = [
                'id' => $newOrder->id,
                'type' => 'OrderTakeaway',
                'tableNr' => (int)$req->t
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
            // register the notification ...
            $details = [
                'id' => $newOrder->id,
                'type' => 'OrderTakeaway',
                'tableNr' => (int)$req->t
            ];
            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
        }
        // event(new newOrder($text));
        if(Auth::check()){
            return redirect('/order')->with('success', 'Überprüfen Sie die Bestelldetails in Ihrem Profil')->withCookie(cookie('trackMO', $orderSh, 1440));
        }else{
            return redirect('/order')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440));
        }
    }

}
