<?php

namespace App\Http\Controllers;

use Cart;
use App\User;
use App\Orders;
use App\TipLog;
use App\kategori;
use App\Takeaway;
use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use App\DeliveryPLZ;
use App\DeliveryProd;
use App\OrdersPassive;
use App\taDeForCookOr;
use App\DeliverySchedule;
use App\deliveryPlzCharge;
use Illuminate\Http\Request;
use SpryngApiHttpPhp\Client;
use App\cooksProductSelection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('adminPanel/adminIndex');
    }

    public function indexClient(){
        return view('inc/menuDelivery');
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
        $newDelivery = new DeliveryProd();
        $newDelivery->emri = $request->emri ;
        $newDelivery->pershkrimi = $request->pershkrimi ;
        $newDelivery->kategoria = $request->kategoria ;
        $newDelivery->qmimi = $request->qmimi ;
        if($request->qmimi2 != '' || $request->qmimi2 != NULL ){
            $newDelivery->qmimi2 = $request->qmimi2 ;
        }else{
            $newDelivery->qmimi2 = 999999 ;
        }
        $newDelivery->extPro = $request->extPro ;
        $newDelivery->type = $request->typePro ;
        $newDelivery->toRes = $request->restaurant ;
        $newDelivery->doneIn = '8' ;
        $newDelivery->accessableByClients = $request->accessByClientsValInd ;

        if(DeliveryProd::where('kategoria',$request->kategoria)->count() > 0){
            $newDelivery->position = DeliveryProd::where('kategoria',$request->kategoria)->max('position')+1 ;
        }else{
            $newDelivery->position = 1;
            if(DeliveryProd::where('toRes',$request->toRes)->count() == 0){
                $upThisKat = kategori::find($newDelivery->kategoria);
                $upThisKat->positionDelivery = 1;
                $upThisKat->save();
            }else{
                $upThisKat = kategori::find($newDelivery->kategoria);
                $upThisKat->positionDelivery = kategori::where('toRes',$upThisKat->toRes)->max('positionDelivery')+1;
                $upThisKat->save();
            }
            $isFirst = 'yes';
        }


        $newDelivery->save();

        if(isset($request->isWaiter)){
            return redirect()->route ('admWoMng.adminWoDeliveryWaiter');
        }else{
            return redirect()->route ('delivery.index');
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
        $de = DeliveryProd::find($request->id);
        $de->emri = $request->emri;
        $de->pershkrimi = $request->pershkrimi;
        $de->qmimi = $request->qmimi;
        if($request->qmimi2 == null){
            $de->qmimi2 = 999999;
        }else{
            $de->qmimi2 = $request->qmimi2;
        }
        $de->mwstForPro = number_format($request->mwst, 2, '.', ' ');
        $de->accessableByClients = $request->acsCl;

        $de->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req){
        $theD = DeliveryProd::find($req->id);
        foreach(DeliveryProd::where([['kategoria',$theD->kategoria],['position','>',$theD->position]])->get() as $upD){
            $upD->position = $upD->position-1;
            $upD->save();
        }
        $hasMore = 'yes';

        if(DeliveryProd::where('kategoria',$theD->kategoria)->count() == 1){
            $upThisKat = kategori::find($theD->kategoria);
           
            foreach(kategori::where([['toRes',$theD->toRes],['positionDelivery','>', $upThisKat->positionDelivery]])->get() as $upCatt){
                $upCatt->positionDelivery = $upCatt->positionDelivery-1;
                $upCatt->save();
            }

            $upThisKat->positionDelivery = 1;
            $upThisKat->save();
            $hasMore = 'no';
        }
        $theD->delete();

        return $hasMore;
    }
    public function destroyAll(Request $req){
        foreach(DeliveryProd::where('toRes', $req->id)->get() as $allTA){

            $theKaa = kategori::find($allTA->kategoria);
            $theKaa->positionDelivery = 1;
            $theKaa->save();

            $allTA->delete();
        }
    }








    public function addToRec(Request $req){
        $this->validate($req, [
            'id' => 'required',
        ]);


        $ta = DeliveryProd::find($req->id);
        $ta->recomendet = 1;

                //get name .etc
                $fileNameOriginal = $req->file('foto')->getClientOriginalName();
                //get just the name
                $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
                // get extension
                $extension = $req->file('foto')->getClientOriginalExtension();
                $fileNameStore = $fileName.'_'.time().'.'.$extension;
  
                // Upload
                $path = $req->file('foto')->move('storage/DeliveryRecomendet', $fileNameStore);
                $ta->recomendetPic = $fileNameStore;
        
      
            $ta->recomendetNr = DeliveryProd::where([['toRes', $ta->toRes],['recomendet', 1]])->get()->max('recomendetNr') + 1; 
        

        $ta->save();

        if(isset($req->isWaiter)){
            return redirect()->route ('admWoMng.adminWoDeliveryWaiter');
        }else{
            return redirect()->route ('delivery.index');
        }
    }











    public function addOne(Request $req){
        $coPro = Produktet::find($req->id);
        $newDe = new DeliveryProd;

        $newDe->emri = $coPro->emri;
        $newDe->pershkrimi = $coPro->pershkrimi;
        $newDe->kategoria = $coPro->kategoria;
        $newDe->qmimi = $coPro->qmimi;
        $newDe->qmimi2 = $coPro->qmimi2;
        $newDe->extPro = $coPro->extPro;
        $newDe->type = $coPro->type;
        $newDe->toRes = $coPro->toRes;
        $newDe->restrictPro = $coPro->restrictPro;
        $newDe->doneIn = $coPro->doneIn;
        $newDe->accessableByClients = $coPro->accessableByClients;

        if(DeliveryProd::where('kategoria',$coPro->kategoria)->count() > 0){
            $newDe->position = DeliveryProd::where('kategoria',$coPro->kategoria)->max('position')+1 ;
            $isFirst = 'no';
        }else{
            $newDe->position = 1;
            if(DeliveryProd::where('toRes',$coPro->toRes)->count() == 0){
                $upThisKat = kategori::find($newDe->kategoria);
                $upThisKat->positionDelivery= 1;
                $upThisKat->save();
            }else{
                $upThisKat = kategori::find($newDe->kategoria);
                $upThisKat->positionDelivery = kategori::where('toRes',$upThisKat->toRes)->max('positionDelivery')+1;
                $upThisKat->save();
            }
            $isFirst = 'yes';
        }

        $newDe->prod_id = $coPro->id;
        $newDe->save();
        
        return $isFirst;
    }

    public function addAll(Request $req){
        foreach(Produktet::where('toRes',$req->id)->get() as $allPro){
            if(DeliveryProd::where('prod_id', $allPro->id)->first() == null){
                $newTA = new DeliveryProd;

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
                if(DeliveryProd::where('kategoria',$allPro->kategoria)->count() > 0){
                    $newTA->position = DeliveryProd::where('kategoria',$allPro->kategoria)->max('position')+1 ;
                }else{
                    $newTA->position = 1;       
                    if(DeliveryProd::where('toRes',$allPro->toRes)->count() == 0){
                        $upThisKat = kategori::find($newTA->kategoria);
                        $upThisKat->positionDelivery = 1;
                        $upThisKat->save();
                    }else{
                        $upThisKat = kategori::find($newTA->kategoria);
                        $upThisKat->positionDelivery = kategori::where('toRes',$upThisKat->toRes)->max('positionDelivery')+1;
                        $upThisKat->save();
                    }
                }
                $newTA->prod_id = $allPro->id;
        
                $newTA->save();
            }
        }
    }








    
    public function minusOneRec(Request $req){
        $TARecPro = DeliveryProd::find($req->id);
        

        $backOne = $TARecPro->recomendetNr; 
        $TARecProCNG = DeliveryProd::where([['toRes', $TARecPro->toRes],['recomendetNr',($backOne - 1)]])->first();
        $TARecProCNG->recomendetNr += 1;
        $TARecProCNG->save();

        $TARecPro->recomendetNr -= 1;
        $TARecPro->save();


    }
    public function plusOneRec(Request $req){
        $TARecPro = DeliveryProd::find($req->id);
        

        $frontOne = $TARecPro->recomendetNr; 
        $TARecProCNG = DeliveryProd::where([['toRes', $TARecPro->toRes],['recomendetNr',($frontOne + 1)]])->first();
        $TARecProCNG->recomendetNr -= 1;
        $TARecProCNG->save();

        $TARecPro->recomendetNr += 1;
        $TARecPro->save();
    }

    public function removeRec(Request $req){
        $ta = DeliveryProd::find($req->id);

        foreach(DeliveryProd::where([['toRes', $ta->toRes],['recomendet', 1],['recomendetNr','>', $ta->recomendetNr]])->get() as $others){
            $others->recomendetNr -= 1;
            $others->save();
        }
        $ta->recomendet = 0;
        $ta->save();
    }







    public function scheduleSet(Request $req){

        if( DeliverySchedule::where('toRes', $req->res)->first() == null){
            $newTS = new DeliverySchedule;
        }else{
            $newTS = DeliverySchedule::where('toRes', $req->res)->first();
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






    public function addPlzDel(Request $req){
        // res,plz,time
        $newDelPlz = new DeliveryPLZ;
        $newDelPlz->isAct = 1;
        $newDelPlz->toRes = $req->res ;
        $newDelPlz->plz = $req->plz ;
        $newDelPlz->takesTime = $req->time;
        $newDelPlz->takesTimeEnd = $req->time2;
        $newDelPlz->minimumOrder = $req->minOrder;
        $newDelPlz->save();

        // For the search - info at restaurant istance
            $thisResDelAll = '';
            $count = 1;
            foreach(DeliveryPLZ::where([['toRes',$req->res],['isAct','1']])->get() as $resDel){
                if($count++ == 1){
                    $thisResDelAll .= $resDel->plz;
                }else{
                    $thisResDelAll .= '-O-'.$resDel->plz;
                }
            }
            $theRes = Restorant::find($req->res);
            $theRes->deliveryFor = $thisResDelAll;
            $theRes->save();
        // ---------------------------------------------------------------------------------------

    }
    public function deletePlzDel(Request $req){
        DeliveryPLZ::find($req->id)->delete();

        // For the search 
              $thisResDelAll = '';
              $count = 1;
              foreach(DeliveryPLZ::where([['toRes',$req->id],['isAct','1']])->get() as $resDel){
                  if($count++ == 1){
                      $thisResDelAll .= $resDel->plz;
                  }else{
                      $thisResDelAll .= '-O-'.$resDel->plz;
                  }
              }
              $theRes = Restorant::find($req->id);
              $theRes->deliveryFor = $thisResDelAll;
              $theRes->save();
        // ---------------------------------------------------------------------------------------
    }
    public function chngAcPlzDel(Request $req){
       $plzDel = DeliveryPLZ::find($req->id);
       $plzDel->isAct =$req->val;
       $plzDel->save();

        // For the search 
            $thisResDelAll = '';
            $count = 1;
            foreach(DeliveryPLZ::where([['toRes',$plzDel->toRes],['isAct','1']])->get() as $resDel){
                if($count++ == 1){
                    $thisResDelAll .= $resDel->plz;
                }else{
                    $thisResDelAll .= '-O-'.$resDel->plz;
                }
            }
            $theRes = Restorant::find($plzDel->toRes);
            $theRes->deliveryFor = $thisResDelAll;
            $theRes->save();
        // ---------------------------------------------------------------------------------------
    }

    public function allTimePlzDel(Request $req){
        $plzDel = DeliveryPLZ::where([['toRes',$req->id],['plz','-99']])->first();
        if($plzDel == NULL){
            $plzDel = new DeliveryPLZ;
            $plzDel->takesTime =$req->val;
            $plzDel->plz = -99;
            $plzDel->toRes = $req->id;
            $plzDel->isAct = 1;
        }else{
            $plzDel->takesTime =$req->val;
        }
        $plzDel->save();
    }




















    public function changeCategoryOrder(Request $req){
        // catId:
        // newPoz:
        $theK = kategori::find($req->catId);
        $crrPoz = $theK->positionDelivery;
        $theK->positionDelivery = $req->newPoz;
        if($crrPoz < $req->newPoz){
            foreach(kategori::where([['toRes',$theK->toRes],['positionDelivery','>',$crrPoz],['positionDelivery','<=',$req->newPoz]])->get() as $chngCat){
                $chngCat->positionDelivery = $chngCat->positionDelivery-1;
                $chngCat->save();
            }
        }else if($crrPoz > $req->newPoz){
            foreach(kategori::where([['toRes',$theK->toRes],['positionDelivery','<',$crrPoz],['positionDelivery','>=',$req->newPoz]])->get() as $chngCat){
                $chngCat->positionDelivery = $chngCat->positionDelivery+1;
                $chngCat->save();
            }
        }
        $theK->save();
    }

    public function changeProductOrder(Request $req){
        // prodId
        // newPoz:

        $theProT = DeliveryProd::find($req->prodId);
        $crrPoz = $theProT->position;
        $theProT->position = $req->newPoz;

        if($crrPoz < $req->newPoz){
            foreach(DeliveryProd::where([['kategoria', $theProT->kategoria],['position','>',$crrPoz],['position','<=',$req->newPoz]])->get() as $chngProd){
                $chngProd->position = $chngProd->position-1;
                $chngProd->save();
            }
        }else if($crrPoz > $req->newPoz){
            foreach(DeliveryProd::where([['kategoria', $theProT->kategoria],['position','<',$crrPoz],['position','>=',$req->newPoz]])->get() as $chngProd){
                $chngProd->position = $chngProd->position+1;
                $chngProd->save();
            }
        }
        $theProT->save();
    }












    public function sendPhoneNrTACashPay(Request $req){
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
    
    public function closeTheOrderCash(Request $req){
        if($req->codeByCl != $req->codeCreated){
            return 'falseCode';
        }else{
            
            $newOrder = new Orders;

            $bakshishi =number_format((float)$req->tipVal, 2, '.', '') ;

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
    
            $newOrder->nrTable = 9000;
            $newOrder->statusi = 0;
            $newOrder->byId = (int)$req->userId;
            $newOrder->userEmri = $req->username;
            $newOrder->userEmail = $req->userEmail;
            $newOrder->porosia =  $req->theOrd;
            $newOrder->payM = 'Cash';
            $newOrder->shuma =number_format((float)$req->shuma + $plzExtChrg, 2, '.', '')-(float)$req->codeVal - (0 * 0.01) + $bakshishi;
            $newOrder->Restaurant = (int)$req->res;
            $newOrder->userPhoneNr = $req->phNr;
            $newOrder->tipPer =  number_format((float)$req->tipVal, 2, '.', '') ;
            $newOrder->TAemri = $req->theName;
            $newOrder->TAmbiemri = $req->theLastname;
            $newOrder->TAtime = $req->theTime;
            $newOrder->cuponOffVal = (float)$req->codeVal;

            $refIfOrPa = OrdersPassive::where('Restaurant',(int)$req->res)->max('refId') + 1;
            $refIfOr = Orders::where('Restaurant',(int)$req->res)->max('refId') + 1;
            $nextRefId = 0;
            if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
            $newOrder->refId = $nextRefId;
    
            $newOrder->TAplz = $req->plz.'|||'.number_format($plzExtChrg, 2, '.', '');
            $newOrder->TAort = $req->ort;
            $newOrder->TAaddress = $req->adresa;
            $newOrder->TAkoment = $req->theCom;
    
            $theR = Restorant::find((int)$req->res);
            $theR->cashPayOrders += 1;
            $theR->save();
    
            if(Cart::total() >= Restorant::find((int)$req->res)->priceFree && $req->freeShot != 0){
                $newOrder->freeProdId =  $req->freeShot;
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
                    $deProd = DeliveryProd::find($orOne2D[7]);

                    $newOrForCook = new taDeForCookOr();
                    $newOrForCook->toRes = (int)$req->res;
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
            if($req->tipVal != 0){
                $newTip = new TipLog;
                $newTip->shumaPor =number_format((float)((float)$req->shuma + $bakshishi) - (0 * 0.01), 2, '.', '') ;
                $newTip->tipPer = $req->tipTitle;
                $newTip->tipTot = (float)$req->tipVal;
                $newTip->toRes = (int)$req->res;
                if(Auth::check()){
                    $newTip->klienti = Auth::user()->id;
                }else{
                    $newTip->klienti = 9999999;
                }
                $newTip->save();
            } 
    
            Cart::destroy();
            foreach(User::where([['sFor',(int)$req->res],['role','5']])->get() as $user){
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'OrderDelivery',
                    'tableNr' => '9000'
                ];
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
            foreach(User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
                // register the notification ...
                $details = [
                    'id' => $newOrder->id,
                    'type' => 'OrderDelivery',
                    'tableNr' => '9000'
                ];
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
            foreach(User::where([['sFor',$req->res],['role','54']])->get() as $oneCook){
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

            return redirect('/order')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440));
            
        }
    }







    public function cashPayTAUseUserPNumber(Request $req){
        $newOrder = new Orders;

        $bakshishi =number_format((float)$req->tipVal, 2, '.', '') ;

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

        $newOrder->nrTable = 9000;
        $newOrder->statusi = 0;
        $newOrder->byId = (int)$req->userId;
        $newOrder->userEmri = $req->username;
        $newOrder->userEmail = $req->userEmail;
        $newOrder->porosia =  $req->theOrd;
        $newOrder->payM = 'Cash';
        $newOrder->shuma =number_format((float)$req->shuma + (float)$plzExtChrg, 2, '.', '')-(float)$req->codeVal - (0 * 0.01) + $bakshishi;
        $newOrder->Restaurant = (int)$req->res;
        $newOrder->userPhoneNr = $req->phNr;
        $newOrder->tipPer =  number_format((float)$req->tipVal, 2, '.', '') ;
        $newOrder->TAemri = $req->theName;
        $newOrder->TAmbiemri = $req->theLastname;
        $newOrder->TAtime = $req->theTime;
        $newOrder->cuponOffVal = (float)$req->codeVal;

        $refIfOrPa = OrdersPassive::where('Restaurant',(int)$req->res)->max('refId') + 1;
        $refIfOr = Orders::where('Restaurant',(int)$req->res)->max('refId') + 1;
        $nextRefId = 0;
        if($refIfOrPa > $refIfOr){ $nextRefId = $refIfOrPa;}else{ $nextRefId = $refIfOr; }
        $newOrder->refId = $nextRefId;

        $newOrder->TAplz = $req->plz.'|||'.number_format($plzExtChrg, 2, '.', '');
        $newOrder->TAort = $req->ort;
        $newOrder->TAaddress = $req->adresa;
        $newOrder->TAkoment = $req->theCom;

        $theR = Restorant::find((int)$req->res);
        $theR->cashPayOrders += 1;
        $theR->save();

        if(Cart::total() >= Restorant::find((int)$req->res)->priceFree && $req->freeShot != 0){
            $newOrder->freeProdId =  $req->freeShot;
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
                $newOrForCook->serviceType = 2;
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

        // Code per tip
        if($req->tipVal != 0){
            $newTip = new TipLog;
            $newTip->shumaPor =number_format((float)((float)$req->shuma + $bakshishi) - (0 * 0.01), 2, '.', '') ;
            $newTip->tipPer = $req->tipTitle;
            $newTip->tipTot = (float)$req->tipVal;
            $newTip->toRes = (int)$req->res;
            if(Auth::check()){
                $newTip->klienti = Auth::user()->id;
            }else{
                $newTip->klienti = 9999999;
            }
            $newTip->save();
        } 

        Cart::destroy();
        foreach(User::where([['sFor',(int)$req->res],['role','5']])->get() as $user){
            $details = [
                'id' => $newOrder->id,
                'type' => 'OrderDelivery',
                'tableNr' => '9000'
            ];
            $user->notify(new \App\Notifications\NewOrderNotification($details));
        }
        foreach(User::where([['sFor',$req->res],['role','55']])->get() as $oneWaiter){
            // register the notification ...
            $details = [
                'id' => $newOrder->id,
                'type' => 'OrderDelivery',
                'tableNr' => '9000'
            ];
            $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
        }
        // event(new newOrder($text));

        return redirect('/order')->with('success', $orderSh)->withCookie(cookie('trackMO', $orderSh, 1440));
    }






    public function deliveryCheckPLZOnCartFromUser(Request $req){
        $thePLZIns = DeliveryPLZ::where([['toRes',$req->theResId],['plz',$req->plz]])->first();
        if($thePLZIns == Null){
            return 'plzNotFound';
        }else{
            $cTot = number_format($req->cTot, 2, '.', ' ');
            if($cTot < number_format($thePLZIns->minimumOrder, 2, '.', ' ')){
                return 'notEnoughtCTot|||'.number_format($thePLZIns->minimumOrder, 2, '.', ' ');
            }else{
                $thePLZChrg = number_format(0, 2, '.', ' ');
             
                foreach(deliveryPlzCharge::where('plzId',$thePLZIns->id)->get() as $plzChrgOne){
                    if($cTot >= $plzChrgOne->priceFrom && $cTot <= $plzChrgOne->priceTo){
                        $thePLZChrg = number_format($plzChrgOne->extraCharge, 2, '.', ' ');
                    }
                }
                $count=1;
                $showOrt = "";
                foreach(DB::table('plzort')->where('plz', $req->plz)->get() as $allOr){
                    if($count++ == 1){
                        $showOrt .= $allOr->ort;
                    }else{
                        $showOrt .=', '.$allOr->ort;
                    }
                }
                return $thePLZChrg.'|||'.$thePLZIns->takesTime.'|||'.$thePLZIns->takesTimeEnd.'|||'.$showOrt;
            }
        }
    }



    public function addPlzChargePerPriceRange(Request $req){
        $thePLZIns = DeliveryPLZ::find($req->plzId);
        if($thePLZIns != Null){
            $newDelPlzChrg = new deliveryPlzCharge();
            $newDelPlzChrg->plzId = $req->plzId;
            $newDelPlzChrg->priceFrom = $req->chfVal1;
            $newDelPlzChrg->priceTo = $req->chfVal2 ;
            $newDelPlzChrg->extraCharge = $req->chfCharge;
            $newDelPlzChrg->save();

            return $req->chfVal2;
        }else{
            return 'plzNotFound';
        }
        
    }
}
