<?php

namespace App\Http\Controllers;

use App\BarbershopService;
use App\BarbershopServiceOrder;
use App\BarbershopServiceOrdersRecords;
use App\BarbershopType;
use Illuminate\Http\Request;

use PDF;

use App\BarbershopWorker;
use App\BarbershopWorkerDays;
use App\BarbershopWorkerTerminBusy;
use App\BarbershopWorkerTerminet;
use App\WorkerCategoryDone;

class BarbershopAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexStatistics(){ return view('adminPanel/adminIndex'); }
    public function indexReservierung(){ return view('adminPanel/adminIndex'); }
    public function indexWorker(){ return view('adminPanel/adminIndex'); }

    public function indexAllConfirmedRez(){ return view('adminPanel/adminIndex'); }
    public function indexRecomendetSer(){ return view('adminPanel/adminIndex'); }
    public function indexCuponMng(){ return view('adminPanel/adminIndex'); }                //Cupons
    public function addReservationAdminPage(){ return view('adminPanel/adminIndex'); }




    public function showReservationsByMonth(){ return view('adminPanel/adminIndex'); }

    public function generateBarSerOrderReceipt(Request $req){
            //   //   pdfRestorantOrders.blade
            //   $details = ['title' => 'Restaurant Orders'];
            //   $item = Orders::where('Restaurant',$req->id)->get()->sortBy('created_at');
      
            //   // $item->status = 1;
            //   // $item->datePrint = date("d-m-Y H:i");
            //   // $item->save();
              
            //   view()->share('items', $item);
            //   $pdf = PDF::loadView('pdfRestorantOrders');
            //   return $pdf->download('ResOrders_'.Restorant::find($req->id)->emri.'.pdf');

            $details = ['title' => 'Barbershop reservation receipt'];
            $item = BarbershopServiceOrdersRecords::find($req->id);

            view()->share('items', $item);
            $pdf = PDF::loadView('pdfBarServiceRecipe');
            return $pdf->download('Reservierungsbeleg_'.$req->id.'.pdf');
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function addWorker(Request $req){

        if(isset($req->emri)){
            $newBWorker = new BarbershopWorker;
            $newBWorker->emri = $req->emri;
            $newBWorker->toBar = $req->barbershop;
            $newBWorker->save();
        }

        $newWorkerDay = new BarbershopWorkerDays;
        
        if(isset($req->emri)){
            $newWorkerDay->workerId = $newBWorker->id;
        }else{
            $newWorkerDay->workerId = $req->thisWorkerID;
        }
        $newWorkerDay->workerDay = $req->dayOfWeek;
        if($req->startW == NULL){
            $newWorkerDay->workStart01 = $req->startW;
            $newWorkerDay->workEnd01= $req->stopW;
        }else{
            $newWorkerDay->workStart01 = '00:00';
            $newWorkerDay->workEnd01= '00:00';
        }
        if($req->startW2 == NULL){
            $newWorkerDay->workStart02 = '00:00';
            $newWorkerDay->workEnd02 = '00:00';
        }else{ 
            $newWorkerDay->workStart02 = $req->startW2;
            $newWorkerDay->workEnd02 = $req->stopW2;
        }
        $newWorkerDay->save();

        if($req->startW != NULL){
            $endWork = $req->stopW;

            $t1 =$req->startW;
            $t1 = date("H:i", strtotime($t1));
            $t2 = date("H:i", strtotime($t1 . "+15 minutes"));

            $or = 1;
            while(true){
                $newTermin = new BarbershopWorkerTerminet;
                if($t2 <= $endWork){
                    if(isset($req->emri)){
                        $newTermin->worker = $newBWorker->id;
                    }else{
                        $newTermin->worker = $req->thisWorkerID;
                    }
                    $newTermin->renditja = $or++;
                    $newTermin->theDay = $req->dayOfWeek;
                    $newTermin->startT = $t1;
                    $newTermin->endT = $t2;

                    $newTermin->save();

                    $t1 = $t2;
                    $t2 = date("H:i", strtotime($t2 . "+15 minutes"));
                }else{
                    break;
                }
            }
        }
        if($req->startW2 != NULL){
            $endWork = $req->stopW2;

            $t1 =$req->startW2;
            $t1 = date("H:i", strtotime($t1));
            $t2 = date("H:i", strtotime($t1 . "+15 minutes"));
            while(true){
                $newTermin = new BarbershopWorkerTerminet;
                if($t2 <= $endWork){
                    if(isset($req->emri)){
                        $newTermin->worker = $newBWorker->id;
                    }else{
                        $newTermin->worker = $req->thisWorkerID;
                    }
                    $newTermin->renditja = $or++;
                    $newTermin->theDay = $req->dayOfWeek;
                    $newTermin->startT = $t1;
                    $newTermin->endT = $t2;

                    $newTermin->save();

                    $t1 = $t2;
                    $t2 = date("H:i", strtotime($t2 . "+15 minutes"));
                }else{
                    break;
                }
            }
        }

        return redirect('/barAdminWorker');
    }




    public function getWorkerDayTermins(Request $req){
        // workerId: wID,
        // workerDay: wDay,

        $filteredData = BarbershopWorkerTerminet::where([['worker',$req->workerId],['theDay',$req->workerDay]])->get()->sortBy('id');

        return json_encode( $filteredData );
    }





    public function deleteWorker(Request $req){
        BarbershopWorker::find($req->id)->delete();
    }

    public function deleteWorkerTermin(Request $req){
        BarbershopWorkerTerminet::find($req->worderTID)->delete();
    }



    public function workerCategorySetDel(Request $req){
        if(WorkerCategoryDone::where([['workerID',$req->worderID],['categoryID',$req->categoryID]])->first() == Null){
            $newWC = new WorkerCategoryDone ;
            $newWC->workerID = $req->worderID ;
            $newWC->categoryID = $req->categoryID ;
            $newWC->barID = $req->barbershopID ;
            $newWC->save();
        }else{
            WorkerCategoryDone::where([['workerID',$req->worderID],['categoryID',$req->categoryID]])->first()->delete() ;
        }
    }



    public function workerDayChngStatus(Request $req){
        // worderDayID: wdID,
        // dayNumber: day,
        $workerDay = BarbershopWorkerDaySchedule::find($req->worderDayID);
        switch($req->dayNumber){
            case 'd1':
                if($workerDay->d1 == 0){
                    $workerDay->d1 = 1;
                }else{
                    $workerDay->d1 = 0;
                }
                $workerDay->save();
            break;
            case 'd2':
                if($workerDay->d2 == 0){
                    $workerDay->d2 = 1;
                }else{
                    $workerDay->d2 = 0;
                }
                $workerDay->save();
            break;
            case 'd3':
                if($workerDay->d3 == 0){
                    $workerDay->d3 = 1;
                }else{
                    $workerDay->d3 = 0;
                }
                $workerDay->save();
            break;
            case 'd4':
                if($workerDay->d4 == 0){
                    $workerDay->d4 = 1;
                }else{
                    $workerDay->d4 = 0;
                }
                $workerDay->save();
            break;
            case 'd5':
                if($workerDay->d5 == 0){
                    $workerDay->d5 = 1;
                }else{
                    $workerDay->d5 = 0;
                }
                $workerDay->save();
            break;
            case 'd6':
                if($workerDay->d6 == 0){
                    $workerDay->d6 = 1;
                }else{
                    $workerDay->d6 = 0;
                }
                $workerDay->save();
            break;
            case 'd0':
                if($workerDay->d0 == 0){
                    $workerDay->d0 = 1;
                }else{
                    $workerDay->d0 = 0;
                }
                $workerDay->save();
            break;
        }
    }













    // Admin registers a new reservation 

    public function setRezFetchWorkers(Request $req){
        // dateSelected: dateSel, barId: bId, catSel: $('#barCatSelectInput').val(),
        switch(date('w', strtotime($req->dateSelected))){
            case '1': $dayofweek='d1'; break;
            case '2': $dayofweek='d2'; break;
            case '3': $dayofweek='d3'; break;
            case '4': $dayofweek='d4'; break;
            case '5': $dayofweek='d5'; break;
            case '6': $dayofweek='d6'; break;
            case '0': $dayofweek='d0'; break;
        }
        $filteredData01 = array();
        foreach(BarbershopWorker::where('toBar',$req->barId)->get() as $barWorker){
            if(WorkerCategoryDone::where([['workerID',$barWorker->id],['categoryID',$req->catSel]])->first() != NULL){
                array_push($filteredData01,$barWorker);
            }
        }
        $filteredData = array();
        foreach($filteredData01 as $barWorker2){
            if(BarbershopWorkerDays::where([['workerId',$barWorker2->id],['workerDay',$dayofweek]])->first() != NULL){
                array_push($filteredData,$barWorker2);
            }
        }
        return json_encode( $filteredData );
    }



    public function setRezFetchWorkerTers(Request $req){
        switch(date('w', strtotime($req->dateSelected))){
            case '1': $dayofweek='d1'; break;
            case '2': $dayofweek='d2'; break;
            case '3': $dayofweek='d3'; break;
            case '4': $dayofweek='d4'; break;
            case '5': $dayofweek='d5'; break;
            case '6': $dayofweek='d6'; break;
            case '0': $dayofweek='d0'; break;
        }
    
        $timeNeedService = BarbershopService::find($req->serviceId)->timeNeed;
        $worTerminDiff = 15;
        $checkTimeWT = array();

        if($timeNeedService > $worTerminDiff){
            foreach(BarbershopWorkerTerminet::where([['worker',$req->workerId],['theDay',$dayofweek]])->get()->sortBy('id') as $workerTermin){
                if($timeNeedService <= (2 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 2);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }else if($timeNeedService <= (3 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 3);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }else if($timeNeedService <= (4 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 4);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }else if($timeNeedService <= (5 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 5);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }else if($timeNeedService <= (6 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 6);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }else if($timeNeedService <= (7 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 7);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }else if($timeNeedService <= (8 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 8);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }else if($timeNeedService <= (9 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 9);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }else if($timeNeedService <= (10 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 10);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }else if($timeNeedService <= (11 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 11);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }else if($timeNeedService <= (12 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($workerTermin->id + 12);
                    if($endBarTermin != NULL && $endBarTermin->worker == $workerTermin->worker && $endBarTermin->theDay == $workerTermin->theDay){$workerTermin->validity = 0;}else{$workerTermin->validity = 1;}
                }
                array_push($checkTimeWT,$workerTermin);
            }
        }else{
            foreach(BarbershopWorkerTerminet::where([['worker',$req->workerId],['theDay',$dayofweek]])->get()->sortBy('id') as $workerTermin){
                $workerTermin->validity = 0;
                array_push($checkTimeWT,$workerTermin);
            }
        }


        if(BarbershopWorkerTerminBusy::where([['date',$req->dateSelected],['worker',$req->workerId]])->get()->count() == 0){
            return json_encode( $checkTimeWT );
        }else{
            $finalFilterData = array();
            foreach($checkTimeWT as $wTer){
                if(BarbershopWorkerTerminBusy::where([['date',$req->dateSelected],['worker',$req->workerId],['workerTerminID',$wTer->id]])->get()->count() == 0){
                    array_push($finalFilterData,$wTer);
                }else{
                    $wTer->validity = 2;
                    array_push($finalFilterData,$wTer);
                }
            }
            return json_encode( $finalFilterData );
        }
        // dateSelected , workerId , serviceId:
    }









    public function setRezSaveTheRezervation(Request $req){


        $serviceGet = BarbershopService::find($req->service);
        if($req->type != 0){
            $typeGet = BarbershopType::find($req->type);
        }

        $newSerOrder = new BarbershopServiceOrder;
        $newSerOrder->toBar = $req->barId ;
        $newSerOrder->clPhoneNr = $req->nrTel;
        $newSerOrder->clName = $req->name ;
        $newSerOrder->clLastname = $req->lastname ;
        $newSerOrder->clEmail = $req->email ;
        $newSerOrder->totalMins = $req->timeNeed ;
        $newSerOrder->toUser = 0 ;
        $newSerOrder->shumaTot = $req->shumaFinal ;
        $newSerOrder->bakshish = 0;
        $newSerOrder->status = 1 ;
        $newSerOrder->save();

        $newSerOrRecord = new BarbershopServiceOrdersRecords;
        $newSerOrRecord->forSerOrder =$newSerOrder->id;
        $newSerOrRecord->emri = $serviceGet->emri;
        $newSerOrRecord->qmimi = $req->shumaFinal;
        $newSerOrRecord->pershkrimi = $serviceGet->pershkrimi;
        if($req->type != 0){
            $newSerOrRecord->type = $typeGet->id.'||'.$typeGet->emri.'||'.$typeGet->vlera;
        }else{
            $newSerOrRecord->type = NULL;
        }
        $newSerOrRecord->extra = NULL;
        $newSerOrRecord->timeNeed = $req->timeNeed;
        $newSerOrRecord->forDate = $req->date;
        $newSerOrRecord->forWorker = $req->worker;
        $newSerOrRecord->forWorkerTermin = $req->terminStart;
        $newSerOrRecord->status = 2;
        $newSerOrRecord->save();

        // barId: barID,
        // cat: Cat,
        // date: DateSel,
        // worker: Worker,
        // service: Service,
        // type: Type,
        // shumaFinal: ShumaFinal,
        // timeNeed: TimeNeed,
        // terminStart: TerminStart,
        // terminsNeed: TerminsNeed,
        // name: Name,
        // lastname: Lastname,
        // email: Email,
        // nrTel: NrTel,
        // _token: '{{csrf_token()}}'

        $worTerVar = new BarbershopWorkerTerminBusy;
            $worTerVar->workerTerminID = $req->terminStart;
            $worTerVar->worker = $req->worker;
            $worTerVar->date = $req->date;
            $worTerVar->serviceRecord = $newSerOrRecord->id;
            $worTerVar->status = 1;
            $worTerVar->save();

        $step = 0;
        while($step++ < ($req->terminsNeed - 1)){
            $nextID = $step + $req->terminStart;

            $worTerVar = new BarbershopWorkerTerminBusy;
            $worTerVar->workerTerminID = $nextID;
            $worTerVar->worker = $req->worker;
            $worTerVar->date = $req->date;
            $worTerVar->serviceRecord = $newSerOrRecord->id;
            $worTerVar->status = 1;
            $worTerVar->save();
        }


    }



}
