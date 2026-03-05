<?php

namespace App\Http\Controllers;

use App\Barbershop;
use Illuminate\Http\Request;

use Cart;

use App\BarbershopService;
use App\BarbershopServiceOrder;
use App\BarbershopServiceOrdersRecords;
use App\BarbershopWorker;
use App\BarbershopWorkerTerminet;
use App\BarbershopWorkerTerminBusy;
use App\BarbershoServiceRecomendet;
use App\Events\barbershopNewRez;
use Illuminate\Support\Facades\Mail;
use App\User;

use SpryngApiHttpPhp\Client;

class BarbershopServiceController extends Controller
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

    public function BarSerRecEmailInfoPage(){
        return view('infoBarbershopRezStat');
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
    public function store(Request $request){
        $newBS = new BarbershopService; 

        $newBS->emri = $request->emri ;
        $newBS->pershkrimi = $request->pershkirmi ;
        $newBS->kategoria = $request->toCat ;
        $newBS->timeNeed = $request->koha ;
        $newBS->qmimi = $request->qmimi ;
        $newBS->qmimiSt = $request->qmimi2 ;
        $newBS->toBar = $request->barbershop ;
        $newBS->extra = $request->extra ;
        $newBS->type = $request->type ;

        $newBS->save();

        return redirect('/barbershopServicesService?barbershop='.$request->barbershop);
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
    public function edit(Request $req){
        $neditBS = BarbershopService::find($req->barSerId); 

        $neditBS->emri = $req->emri ;
        $neditBS->pershkrimi = $req->pershkirmi ;
        $neditBS->timeNeed = $req->koha ;
        $neditBS->qmimi = $req->qmimi ;
        $neditBS->qmimiSt = $req->qmimi2 ;

        $neditBS->save();

        return redirect('/barbershopServicesService?barbershop='.$neditBS->toBar);
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







    public function searchBarServices(Request $req){

        $filteredData = BarbershopService::where([['toBar',$req->theBar],['emri', 'like', '%'.$req->searchW.'%']])
                                            ->orWhere([['toBar',$req->theBar],['pershkrimi', 'like', '%'.$req->searchW.'%']])
                                            ->orWhere([['toBar',$req->theBar],['type', 'like', '%'.$req->searchW.'%']])->get();

        return json_encode( $filteredData );
    }



















    public function declineBarSerRec(Request $req){
        $barSerRec = BarbershopServiceOrdersRecords::find($req->id);
        $barSerRec->status = 1;
        $barSerRec->save();


        if(BarbershopServiceOrdersRecords::where([['forSerOrder', $barSerRec->forSerOrder],['status', '0']])->get()->count() == 0){
            $barSer = BarbershopServiceOrder::find($barSerRec->forSerOrder);
            $barSer->status = 1;
            $barSer->save();
        }



        $barSer = BarbershopServiceOrder::find($barSerRec->forSerOrder);
        $bar = Barbershop::find($barSer->toBar);

        $startTime = BarbershopWorkerTerminet::find($barSerRec->forWorkerTermin)->startT;

        $worker = BarbershopWorker::find($barSerRec->forWorker)->emri;

        // Send the confirmation email ...
        $to_name = $barSer->clName." ".$barSer->clLastname;
        $to_email = str_replace(' ', '', $barSer->clEmail); ;
        $data = array('name'=>$to_name, 'body'=>$barSerRec, 'bar'=>$bar, 'time' => $startTime, 'worker' => $worker, 'address'=>$bar->adresa);

        Mail::send('emails.barDeclineMail', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('Bestätigungs-E-Mail für die Barbershop-Reservierung');
            $message->from('noreply@qrorpa.ch','Qrorpa');
        });
    }












    public function acceptBarSerRec(Request $req){
        // id: barSerRecID,
        // terForDate: forDate,
        // worTer01: worTerID01,
        // worTer02: worTerID02,
        // worTer03: worTerID03,
        // worTer04: worTerID04,
        // worTer05: worTerID05,
        // worTer06: worTerID06,
        // _token: '{{csrf_token()}}'

        switch(date('w', strtotime($req->terForDate))){
            case '1': $dayofweek='d1'; break;
            case '2': $dayofweek='d2'; break;
            case '3': $dayofweek='d3'; break;
            case '4': $dayofweek='d4'; break;
            case '5': $dayofweek='d5'; break;
            case '6': $dayofweek='d6'; break;
            case '0': $dayofweek='d0'; break;
        }

        $barSerRec = BarbershopServiceOrdersRecords::find($req->id);
        $barSerRec->status = 2;
        $barSerRec->save();

        if($req->worTer01 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer01]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer01Var = new BarbershopWorkerTerminBusy;
                $worTer01Var->workerTerminID = $req->worTer01;
                $worTer01Var->worker = $barSerRec->forWorker;
                $worTer01Var->date = $req->terForDate;
                $worTer01Var->serviceRecord = $req->id;
                $worTer01Var->status = 1;
                $worTer01Var->save();
            }
        }
        if($req->worTer02 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer02]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer02Var = new BarbershopWorkerTerminBusy;
                $worTer02Var->workerTerminID = $req->worTer02;
                $worTer02Var->worker =  $barSerRec->forWorker;
                $worTer02Var->date = $req->terForDate;
                $worTer02Var->serviceRecord = $req->id;
                $worTer02Var->status = 1;
                $worTer02Var->save();
            }
        }
        if($req->worTer03 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer03]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer03Var = new BarbershopWorkerTerminBusy;
                $worTer03Var->workerTerminID = $req->worTer03;
                $worTer03Var->worker =  $barSerRec->forWorker;
                $worTer03Var->date = $req->terForDate;
                $worTer03Var->serviceRecord = $req->id;
                $worTer03Var->status = 1;
                $worTer03Var->save();
            }
        }
        if($req->worTer04 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer04]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer04Var = new BarbershopWorkerTerminBusy;
                $worTer04Var->workerTerminID = $req->worTer04;
                $worTer04Var->worker =  $barSerRec->forWorker;
                $worTer04Var->date = $req->terForDate;
                $worTer04Var->serviceRecord = $req->id;
                $worTer04Var->status = 1;
                $worTer04Var->save();
            }
        }
        if($req->worTer05 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer05]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer05Var = new BarbershopWorkerTerminBusy;
                $worTer05Var->workerTerminID = $req->worTer05;
                $worTer05Var->worker =  $barSerRec->forWorker;
                $worTer05Var->date = $req->terForDate;
                $worTer05Var->serviceRecord = $req->id;
                $worTer05Var->status = 1;
                $worTer05Var->save();
            }
        }
        if($req->worTer06 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer06]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer06Var = new BarbershopWorkerTerminBusy;
                $worTer06Var->workerTerminID = $req->worTer06;
                $worTer06Var->worker =  $barSerRec->forWorker;
                $worTer06Var->date = $req->terForDate;
                $worTer06Var->serviceRecord = $req->id;
                $worTer06Var->status = 1;
                $worTer06Var->save();
            }
        }
        if($req->worTer07 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer07]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer07Var = new BarbershopWorkerTerminBusy;
                $worTer07Var->workerTerminID = $req->worTer07;
                $worTer07Var->worker =  $barSerRec->forWorker;
                $worTer07Var->date = $req->terForDate;
                $worTer07Var->serviceRecord = $req->id;
                $worTer07Var->status = 1;
                $worTer07Var->save();
            }
        }  
        if($req->worTer08 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer08]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer08Var = new BarbershopWorkerTerminBusy;
                $worTer08Var->workerTerminID = $req->worTer08;
                $worTer08Var->worker =  $barSerRec->forWorker;
                $worTer08Var->date = $req->terForDate;
                $worTer08Var->serviceRecord = $req->id;
                $worTer08Var->status = 1;
                $worTer08Var->save();
            }
        }
        if($req->worTer09 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer09]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer09Var = new BarbershopWorkerTerminBusy;
                $worTer09Var->workerTerminID = $req->worTer09;
                $worTer09Var->worker =  $barSerRec->forWorker;
                $worTer09Var->date = $req->terForDate;
                $worTer09Var->serviceRecord = $req->id;
                $worTer09Var->status = 1;
                $worTer09Var->save();
            }
        }
        if($req->worTer10 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer10]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer10Var = new BarbershopWorkerTerminBusy;
                $worTer10Var->workerTerminID = $req->worTer10;
                $worTer10Var->worker =  $barSerRec->forWorker;
                $worTer10Var->date = $req->terForDate;
                $worTer10Var->serviceRecord = $req->id;
                $worTer10Var->status = 1;
                $worTer10Var->save();
            }
        }
        if($req->worTer11 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer11]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer11Var = new BarbershopWorkerTerminBusy;
                $worTer11Var->workerTerminID = $req->worTer11;
                $worTer11Var->worker =  $barSerRec->forWorker;
                $worTer11Var->date = $req->terForDate;
                $worTer11Var->serviceRecord = $req->id;
                $worTer11Var->status = 1;
                $worTer11Var->save();
            }
        }
        if($req->worTer12 != 0){
            if(BarbershopWorkerTerminBusy::whereDate('date',$req->terForDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$req->worTer12]])->first() != NULL){
                $barSerRec->status = 0;
                $barSerRec->save();
                return 'error';
            }else{
                $worTer12Var = new BarbershopWorkerTerminBusy;
                $worTer12Var->workerTerminID = $req->worTer12;
                $worTer12Var->worker =  $barSerRec->forWorker;
                $worTer12Var->date = $req->terForDate;
                $worTer12Var->serviceRecord = $req->id;
                $worTer12Var->status = 1;
                $worTer12Var->save();
            }
        }


        if(BarbershopServiceOrdersRecords::where([['forSerOrder', $barSerRec->forSerOrder],['status', '0']])->get()->count() == 0){
            $barSer = BarbershopServiceOrder::find($barSerRec->forSerOrder);
            $barSer->status = 1;
            $barSer->save();
        }


        $barSer = BarbershopServiceOrder::find($barSerRec->forSerOrder);
        $bar = Barbershop::find($barSer->toBar);

        $startTime = '';
        foreach(BarbershopWorkerTerminBusy::where('serviceRecord',$req->id)->get()->sortBy('workerTerminID') as $ters){
            $ter = BarbershopWorkerTerminet::find($ters->workerTerminID);
            if($startTime == ''){
                $startTime = $ter->startT;
            }
            $endTime = $ter->endT;
        }
        $time = $startTime.' => '.$endTime;
        $worker = BarbershopWorker::find($barSerRec->forWorker)->emri;

        // Send the confirmation email ...
        $to_name = $barSer->clName." ".$barSer->clLastname;
        $to_email = str_replace(' ', '', $barSer->clEmail); ;
        $data = array('name'=>$to_name , 'body'=>$barSerRec, 'bar'=>$bar, 'time' => $time, 'worker' => $worker, 'address'=>$bar->adresa);

        Mail::send('emails.barConfirmMail', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('Bestätigungs-E-Mail für die Barbershop-Reservierung');
            $message->from('noreply@qrorpa.ch','Qrorpa');
        });

    }













    public function declineBarSerRecEmail(){
        if(isset($_GET['bsorId'])){
            $barSerRec = BarbershopServiceOrdersRecords::find($_GET['bsorId']);
            if( $barSerRec->status == 0){
                $barSerRec->status = 1;
                $barSerRec->save();

                if(BarbershopServiceOrdersRecords::where([['forSerOrder', $barSerRec->forSerOrder],['status', '0']])->get()->count() == 0){
                    $barSer = BarbershopServiceOrder::find($barSerRec->forSerOrder);
                    $barSer->status = 1;
                    $barSer->save();
                }

                $barSer = BarbershopServiceOrder::find($barSerRec->forSerOrder);
                $bar = Barbershop::find($barSer->toBar);

                $startTime = BarbershopWorkerTerminet::find($barSerRec->forWorkerTermin)->startT;

                $worker = BarbershopWorker::find($barSerRec->forWorker)->emri;

                // Send the confirmation email ...
                $to_name = $barSer->clName." ".$barSer->clLastname;
                $to_email = str_replace(' ', '', $barSer->clEmail); ;
                $data = array('name'=>$to_name, 'body'=>$barSerRec, 'bar'=>$bar, 'time' => $startTime, 'worker' => $worker, 'address'=>$bar->adresa);

                Mail::send('emails.barDeclineMail', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                            ->subject('Bestätigungs-E-Mail für die Barbershop-Reservierung');
                    $message->from('noreply@qrorpa.ch','Qrorpa');
                });

                return redirect('/BarServiceBarSerRecEmailInfoPage?bsorId={{$barSerRec->id}}');
            }else{
                // eshte ndryshuar statusi njeher / nuk lejohet
                return redirect('/BarServiceBarSerRecEmailInfoPage?bsorId={{$barSerRec->id}}&error=Fail');
            }
        }
    }


    public function acceptBarSerRecEmail(){
        if(isset($_GET['bsorId']) && isset($_GET['workerTermins'])){
            $barSerRec = BarbershopServiceOrdersRecords::find($_GET['bsorId']);
            if($barSerRec->status == 0){
                $barSerRec->status = 2;
                $barSerRec->save();

                switch(date('w', strtotime($barSerRec->forDate))){
                    case '1': $dayofweek='d1'; break;
                    case '2': $dayofweek='d2'; break;
                    case '3': $dayofweek='d3'; break;
                    case '4': $dayofweek='d4'; break;
                    case '5': $dayofweek='d5'; break;
                    case '6': $dayofweek='d6'; break;
                    case '0': $dayofweek='d0'; break;
                }

                foreach(explode('||',$_GET['workerTermins']) as $oneWorTer){
                    if($oneWorTer != 0){
                        $barSerRec02 = BarbershopServiceOrdersRecords::find($_GET['bsorId']);
                        if(BarbershopWorkerTerminBusy::whereDate('date',$barSerRec->forDate)->where([['worker',$barSerRec->forWorker],['workerTerminID',$oneWorTer]])->first() != NULL){
                            $barSerRec->status = 0;
                            $barSerRec->save();
                            return 'error';
                        }else{
                            $worTer01Var = new BarbershopWorkerTerminBusy;
                            $worTer01Var->workerTerminID = $oneWorTer;
                            $worTer01Var->worker = $barSerRec->forWorker;
                            $worTer01Var->date = $barSerRec->forDate;
                            $worTer01Var->serviceRecord = $_GET['bsorId'];
                            $worTer01Var->status = 1;
                            $worTer01Var->save();
                        }
                    }
                }

                if(BarbershopServiceOrdersRecords::where([['forSerOrder', $barSerRec->forSerOrder],['status', '0']])->get()->count() == 0){
                    $barSer = BarbershopServiceOrder::find($barSerRec->forSerOrder);
                    $barSer->status = 1;
                    $barSer->save();
                }
        
                $barSer = BarbershopServiceOrder::find($barSerRec->forSerOrder);
                $bar = Barbershop::find($barSer->toBar);
        
                $startTime = '';
                foreach(BarbershopWorkerTerminBusy::where('serviceRecord',$_GET['bsorId'])->get()->sortBy('workerTerminID') as $ters){
                    $ter = BarbershopWorkerTerminet::find($ters->workerTerminID);
                    if($startTime == ''){
                        $startTime = $ter->startT;
                    }
                    $endTime = $ter->endT;
                }
                $time = $startTime.' => '.$endTime;
                $worker = BarbershopWorker::find($barSerRec->forWorker)->emri;
        
                // Send the confirmation email ...
                $to_name = $barSer->clName." ".$barSer->clLastname;
                $to_email = str_replace(' ', '', $barSer->clEmail); ;
                $data = array('name'=>$to_name , 'body'=>$barSerRec, 'bar'=>$bar, 'time' => $time, 'worker' => $worker, 'address'=>$bar->adresa);
                Mail::send('emails.barConfirmMail', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                            ->subject('Bestätigungs-E-Mail für die Barbershop-Reservierung');
                    $message->from('noreply@qrorpa.ch','Qrorpa');
                });

                return redirect('/BarServiceBarSerRecEmailInfoPage');
            }else{
                // eshte ndryshuar statusi njeher / nuk lejohet
                return redirect('/BarServiceBarSerRecEmailInfoPage?bsorId={{$barSerRec->id}}&error=Fail');
            }
        }

  
    }




















// Recomendet services 

    public function recomenderSerStore(Request $req){


        if(BarbershoServiceRecomendet::where([['toBar',$req->barId],['position', '>=' , $req->pozita]])->get()->count() > 0){
            foreach(BarbershoServiceRecomendet::where([['toBar',$req->barId],['position', '>=' , $req->pozita]])->get() as $barSerRecToCng){
                $barSerRecToCng->position = $barSerRecToCng->position + 1 ;
                $barSerRecToCng->save();
            }
        }

        $newBarSerRec = new BarbershoServiceRecomendet;

        //get name .etc
        $fileNameOriginal = $req->file('foto')->getClientOriginalName();
        //get just the name
        $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
        // get extension
        $extension = $req->file('foto')->getClientOriginalExtension();
        $fileNameStore = $fileName.'_'.time().'.'.$extension;
        // Upload
        $path = $req->file('foto')->move('storage/recomendetServices', $fileNameStore);

        $newBarSerRec->toBar =  $req->barId;
        $newBarSerRec->serviceid =  $req->sherbimi;
        $newBarSerRec->servicePic =  $fileNameStore;
        $newBarSerRec->position =  $req->pozita;
        $newBarSerRec->newPrice =  $req->qmimi;
        if($req->qmimi2 == NULL || $req->qmimi2 < 0){
            $newBarSerRec->newPriceSt =  0;
        }else{
            $newBarSerRec->newPriceSt =  $req->qmimi2;
        }
        $newBarSerRec->save();

        return redirect('/barAdmRecomendetSer');
    }

    public function recomenderSerUpdate(Request $req){
        if(!empty($req->file('foto'))){
            //get name .etc
            $fileNameOriginal = $req->file('foto')->getClientOriginalName();
            //get just the name
            $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
            // get extension
            $extension = $req->file('foto')->getClientOriginalExtension();
            $fileNameStore = $fileName.'_'.time().'.'.$extension;
            // Upload
            $path = $req->file('foto')->move('storage/recomendetServices', $fileNameStore);
        }

        $BarSerRec = BarbershoServiceRecomendet::find($req->barSerRecId);

        if( $BarSerRec->position != $req->pozita){

            $BarSerRecToReplacePosition = BarbershoServiceRecomendet::where([['toBar',$req->barId],['position', $req->pozita]])->first();
            if($BarSerRecToReplacePosition != NULL){
                $BarSerRecToReplacePosition->position = $BarSerRec->position;
                $BarSerRecToReplacePosition->save();
            }

            $BarSerRec->position =  $req->pozita;
        }
        $BarSerRec->newPrice =  $req->qmimi;
        $BarSerRec->newPriceSt =  $req->qmimi2;
        $BarSerRec->save();

        return redirect('/barAdmRecomendetSer');
    }


    public function recomenderGetSerPrice(Request $req){
        return  BarbershopService::find($req->id);
        // return  number_format((float)BarbershopService::find($req->id)->qmimi, 2, '.', '');
    }
    public function recomenderGetSerPrice2(Request $req){
        $barSer = BarbershopService::find($req->id);
        if(BarbershoServiceRecomendet::where('serviceid',$barSer->id)->first() != NULL){
            if($req->std == 1){
                $barSer->qmimi = number_format((float)BarbershoServiceRecomendet::where('serviceid',$barSer->id)->first()->newPriceSt, 2, '.', '');
            }else{
                $barSer->qmimi = number_format((float)BarbershoServiceRecomendet::where('serviceid',$barSer->id)->first()->newPrice, 2, '.', '');
            }
        }
        return  $barSer;
    }

    public function recomendetDelete(Request $req){
        $rsToDel = BarbershoServiceRecomendet::find($req->id);

        if(BarbershoServiceRecomendet::where([['toBar',$rsToDel->toBar],['position', '>', $rsToDel->position]])->get()->count() > 0){
            foreach(BarbershoServiceRecomendet::where([['toBar',$rsToDel->toBar],['position', '>', $rsToDel->position]])->get() as $sc){
                $sc->position = $sc->position - 1;
                $sc->save();
            }
        }
        $rsToDel->delete();
    }

    public function recomenderUpOne(Request $req){
        $BarSerRec = BarbershoServiceRecomendet::find($req->id);

        $myPoz = $BarSerRec->position;
        $myPozNew = $BarSerRec->position - 1;

        $recoilSer =  BarbershoServiceRecomendet::where([['toBar',$req->barID],['position',  $myPozNew]])->first();
        $recoilSer->position = $myPoz;
        $recoilSer->save();

        $BarSerRec->position = $myPozNew;
        $BarSerRec->save();
    }

    public function recomenderDownOne(Request $req){
        $BarSerRec = BarbershoServiceRecomendet::find($req->id);

        $myPoz = $BarSerRec->position;
        $myPozNew = $BarSerRec->position + 1;

        $recoilSer =  BarbershoServiceRecomendet::where([['toBar',$req->barID],['position',  $myPozNew]])->first();
        $recoilSer->position = $myPoz;
        $recoilSer->save();

        $BarSerRec->position = $myPozNew;
        $BarSerRec->save();
    }

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------




























    public function confNrBar(Request $request){

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

        if(!empty($request->sendName) && !empty($request->sendLastname) && !empty($request->sendEmail)){
            if (!filter_var($request->sendEmail, FILTER_VALIDATE_EMAIL)) {
                return redirect('/order')->with('error',"Die E-Mail ist ungültig!");
            }
            if($sendTo != 445566){
                $sendTo2 = (int)$sendTo;
                $nowTime = date('Y-m-d h:i:s');

                // Numri i Besart Hazirit perdoret per Demo 
                if($phToTestForSMS != '763270293'){
                    $spryng = new Client('QRorpasms', 'Spryng@qrorpa.ch', 'qrorpa.ch');
                    if($spryng->sms->checkBalance() > 0){
                        try {
                            $spryng->sms->send($sendTo2,'Ihr Sicherheitscode ist: '.$request->sendCode.' . Er lauft in 5 Minuten ab.', array(
                                'route'     => 'business',
                                'allowlong' => true,
                                )
                            );
                        }catch (InvalidRequestException $e){
                            dd ($e->getMessage());
                        }
                    }
                }

                $confirmData =[
                    'code' => $request->sendCode,
                    'timeStart' => $nowTime,
                    'klientPhone' => $sendTo2,
                    'tipValue' => $request->bakshishVAR01,
                    'tipCHF' => number_format($request->bakshishVAR01, 2, '.', ''),
                    'userName' => $request->sendName,
                    'userLastname' => $request->sendLastname,
                    'userEmail' => $request->sendEmail,
                    'studentStatus' => $request->studentStat01,
                    'codeUsed' => $request->codeUsedValueID,
                    'codeUsedPerce' => $request->codeUsedPerceID,
                ];
                return view('cart')->with('confirmData',$confirmData);

            }else{
                return redirect('/order')->with('error',"Ihre Nummer existiert nicht. Bitte versuchen Sie es noch ein mal.");
            }
        }else{
            return redirect('/order')->with('error',"Schreiben Sie bitte Ihren Namen, Nachnamen und E-Mail!");
        }
    }










    public function confCodeBar(Request $req){
        // userCode
        // bakshishVAR02
        // timeCode
        // timeCodeEnd
        // codeReg

        $nowTime = date('Y-m-d h:i:s');
        $Ucode = (int)$req->userCode;
        $Code = (int) $req->codeReg;

        if($req->timeCodeEnd >= $nowTime){
            if($Code == $Ucode){
                // Register the reservation 
                $newRez = new BarbershopServiceOrder;

                $priceTot = 0;
                $minTot = 0;
                $servicesAll = '';

                foreach(Cart::content() as $item){
                    $priceTot += sprintf('%01.2f', $item->price);
                    $minTot += $item->options->timeNeed;
                    // if( $servicesAll == ''){
                    //     $servicesAll = $item->name.'-0-'.$item->price.'-0-'.$item->options->persh.'-0-'.$item->options->type.'-0-'.$item->options->ekstras.'-0-'.$item->options->timeNeed.'-0-'.$item->options->workerDate.'-0-'.$item->options->worker.'-0-'.$item->options->workerTer ;
                    // }else{
                    //     $servicesAll .= '<<>>'.$item->name.'-0-'.$item->price.'-0-'.$item->options->persh.'-0-'.$item->options->type.'-0-'.$item->options->ekstras.'-0-'.$item->options->timeNeed.'-0-'.$item->options->workerDate.'-0-'.$item->options->worker.'-0-'.$item->options->workerTer ;
                    // }
                }

                $newRez->toBar = $req->barI;
                $newRez->clPhoneNr = $req->phoneNrCl;
                $newRez->clName = $req->clName;
                $newRez->clLastname = $req->clLastname;
                $newRez->clEmail= $req->clEmail;
                // $newRez->services = $servicesAll;
                $newRez->totalMins = $minTot;
                $newRez->toUser = 0;
                $newRez->shumaTot = number_format(Cart::total() +  $req->bakshishVAR02 - $req->codeUsedValueID2, 2, '.', '');
                $newRez->bakshish = number_format($req->bakshishVAR02, 2, '.', '');
                $newRez->couponOff = $req->codeUsedValueID2;
                $newRez->status = 0;

                $newRez->save();

                $perOff = $req->codeUsedPerceID2 * 0.01;

                // store all the new BarbershopServiceOrdersRecords
                $ordersRecordArray = '';

                foreach(Cart::content() as $item){
                    $newSerOrRecord = new BarbershopServiceOrdersRecords;

                    $newSerOrRecord->forSerOrder = $newRez->id;
                    $newSerOrRecord->emri = $item->name;
                    $newSerOrRecord->qmimi = $item->price - ($item->price * $perOff);
                    $newSerOrRecord->pershkrimi = $item->options->persh;
                    $newSerOrRecord->type = $item->options->type;
                    $newSerOrRecord->extra = $item->options->ekstras;
                    $newSerOrRecord->timeNeed = $item->options->timeNeed;
                    $newSerOrRecord->forDate = $item->options->workerDate;
                    $newSerOrRecord->forWorker = $item->options->worker;
                    $newSerOrRecord->forWorkerTermin = $item->options->workerTer;
                    $newSerOrRecord->status = 0;
                    $newSerOrRecord->forStudent = $req->studentStat02;

                    $newSerOrRecord->save();

                    if($ordersRecordArray == ''){ $ordersRecordArray = $newSerOrRecord->id; }else{ $ordersRecordArray = $ordersRecordArray.'||'.$newSerOrRecord->id; }
                }

                event(new barbershopNewRez($req->barI));

                Cart::destroy();


                $barAdmin = User::where([['role','15'],['sFor',$req->barI]])->firstOrFail();
                $to_name = $barAdmin->name;
                $to_email = str_replace(' ', '', $barAdmin->email); ;
                $data = array('name'=>$to_name, "ordersRecordArray" => $ordersRecordArray);
                Mail::send('emails.barNotifyAdminForRez', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                            ->subject('neue Reservierungswarnung')
                            ->from('noreply@qrorpa.ch','Qrorpa');
                });

                return redirect('/order')->with('success','Vielen Dank, dass Sie qrorpa.ch/ verwenden. Wir werden Ihre Reservierung sehr bald prüfen.');
            }else{
                // Try again
                $confirmData =[
                    'code' => $Code,
                    'timeStart' => $req->timeCode,
                    'klientPhone' => $req->phoneNrCl,
                    'tipValue' => $req->bakshishVAR02,
                    'tipCHF' => number_format($req->bakshishVAR02, 2, '.', ''),
                    'userName' => $req->clName,
                    'userLastname' => $req->clLastname,
                    'userEmail' => $req->clEmail,
                    'studentStatus' => $req->studentStat02,
                    'codeUsed' => $req->codeUsedValueID2,
                    'codeUsedPerce' => $req->codeUsedPerceID2,
                ];
                return view('cart')->with('confirmData',$confirmData);
            }
        }else{
            // Expire
            return redirect('/order')->with('error',"Ihre 5-Minuten-Zeit ist abgelaufen. Schreiben Sie Ihre Nummer erneut");
        }

    }

}
