<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Barbershop;
use App\Barberservice;
use App\BarbershopWorker;
use App\BarbershopWorkerDays;
use App\BarbershopWorkerTerminBusy;
use App\BarbershopWorkerTerminet;
use App\barbershopWorkingH;
use App\WorkerCategoryDone;
use App\User;
use Auth;


class BarberShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

    }

    public function indexBarbershops(){ return view('sa/superAdminIndex');}
    public function indexBarbershopsOne(){ return view('sa/superAdminIndex'); }
    public function servicesIndex(){ return view('sa/superAdminIndex'); }
    public function servicesSelBar(){ return view('sa/superAdminIndex'); }
    public function servicesCategory(){ return view('sa/superAdminIndex'); }
    public function servicesType(){ return view('sa/superAdminIndex'); }
    public function servicesExtra(){ return view('sa/superAdminIndex'); }
    public function servicesService(){ return view('sa/superAdminIndex'); }

    public function indexUser(){ return view('sa/superAdminIndex'); }

    public function indexBannerSA(){
        return view('sa/superAdminIndex');
    }







    public function changeRoleUser(Request $req){
        $chRUser = User::find($req->id);

        if($chRUser->role == 1){
            $chRUser->role = 15;
            $chRUser->save();
        }else if($chRUser->role == 15){
            $chRUser->role = 9;
            $chRUser->save();
        }else if($chRUser->role == 9){
            $chRUser->role = 1;
            $chRUser->save();
        }
    }
    public function UserToRes(Request $req){
        $RUser = User::find($req->user);
        $RUser->sFor = $req->res;
        $RUser->save();
    }
    public function UserToBar(Request $req){
        $BUser = User::find($req->user);
        $BUser->sFor = $req->bar;
        $BUser->save();
    }




























    
    

    public function barbershopUser()
    {
            $barberUser = User::find(Auth::user()->id)->barbershop()->get();

            // Should be the same
            $barberUser = Auth::user()->barbershop;
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
        $barbershop = new Barbershop;
        $barbershop->emri = $request->name;
        $barbershop->adresa = $request->address;
        $barbershop->save();
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
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
        ]);
        $barbershop = Barbershop::find($id);


        $barbershop->name = $request->input('name');
        $barbershop->address = $request->input('address');
        $barbershop->save();

        return redirect('/barbershops')->with('success','Die Kategorie wurde erfolgreich geändert' );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Barbershop::find($id)->delete();
        return redirect('/barbershops')->with('success','Die Kategorie wurde erfolgreich gelöscht');
    }



























    public function setBarLogo(Request $req){

        $this->validate($req, [
            'foto' => 'image|max:199999'
        ]);
            $bar = Barbershop::find($req->id);

            //get name .etc
            $fileNameOriginal = $req->file('foto')->getClientOriginalName();
            //get just the name
            $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
            // get extension
            $extension = $req->file('foto')->getClientOriginalExtension();
            $fileNameStore = $fileName.'_'.time().'.'.$extension;
    
            // Upload
            $path = $req->file('foto')->move('storage/barbershopLogo', $fileNameStore);

            $bar->bPic = $fileNameStore;
            $bar->save();

            return redirect('/barbershopBarbershopsOne?barbershop='.$req->id);
    }


    public function setWorkingHAll(Request $req){

        $barWH = barbershopWorkingH::where('toBar',$req->bar)->first();
        if($barWH == null){
            $barWH = new barbershopWorkingH;
            $barWH->toBar = $req->bar;
        }
        $barWH->D1Starts1 = $req->D1in1;
        $barWH->D1End1 = $req->D1out1;
        $barWH->D1Starts2 = $req->D1in2;
        $barWH->D1End2 = $req->D1out2;
        $barWH->D2Starts1 = $req->D2in1;
        $barWH->D2End1 = $req->D2out1;
        $barWH->D2Starts2 = $req->D2in2;
        $barWH->D2End2 = $req->D2out2;
        $barWH->D3Starts1 = $req->D3in1;
        $barWH->D3End1 = $req->D3out1;
        $barWH->D3Starts2 = $req->D3in2;
        $barWH->D3End2 = $req->D3out2;
        $barWH->D4Starts1 = $req->D4in1;
        $barWH->D4End1 = $req->D4out1;
        $barWH->D4Starts2 = $req->D4in2;
        $barWH->D4End2 = $req->D4out2;
        $barWH->D5Starts1 = $req->D5in1;
        $barWH->D5End1 = $req->D5out1;
        $barWH->D5Starts2 = $req->D5in2;
        $barWH->D5End2 = $req->D5out2;
        $barWH->D6Starts1 = $req->D6in1;
        $barWH->D6End1 = $req->D6out1;
        $barWH->D6Starts2 = $req->D6in2;
        $barWH->D6End2 = $req->D6out2;
        $barWH->D7Starts1 = $req->D7in1;
        $barWH->D7End1 = $req->D7out1;
        $barWH->D7Starts2 = $req->D7in2;
        $barWH->D7End2 = $req->D7out2;

        $barWH->save();

    }


    public function setBarMap(Request $req){
        $bar = Barbershop::find($req->id);
        $bar->map = $req->map;
        $bar->save();
    }


    public function setBarDesc(Request $req){
        $bar = Barbershop::find($req->id);

        if( $req->desc == ''){
            $bar->barDesc = 'none';
        }else{
            $bar->barDesc = $req->desc;
        }
        $bar->save();
    }


    public function updateBarAddress(Request $req){
        $bar = Barbershop::find($req->barId);
        $bar->adresa = $req->adresaNew;
        $bar->save();
        
        return redirect('/barbershopBarbershopsOne?barbershop='.$req->barId);
    }

    public function BarRemoveGoogleMap(Request $req){
        $bar = Barbershop::find($req->barId);
        $bar->map = 'none';
        $bar->save();
    }






















    public function fetchtheWorkers(Request $req){
        $dayofweek = date('w', strtotime($req->dateSelected));

        $ourWorkers = array();
        foreach(BarbershopWorker::where('toBar',$req->barId)->get() as $barWorker){
            if(WorkerCategoryDone::where([['workerID', $barWorker->id],['categoryID',$req->bSerCat]])->first() != NULL){
                if($dayofweek == 1){
                    if(BarbershopWorkerDays::where([['workerId',$barWorker->id],['workerDay','d1']])->first() != NUll){ array_push( $ourWorkers, $barWorker); }
                }else if($dayofweek == 2){
                    if(BarbershopWorkerDays::where([['workerId',$barWorker->id],['workerDay','d2']])->first() != NUll){ array_push( $ourWorkers, $barWorker); }
                }else if($dayofweek == 3){
                    if(BarbershopWorkerDays::where([['workerId',$barWorker->id],['workerDay','d3']])->first() != NUll){ array_push( $ourWorkers, $barWorker); }
                }else if($dayofweek == 4){
                    if(BarbershopWorkerDays::where([['workerId',$barWorker->id],['workerDay','d4']])->first() != NUll){array_push( $ourWorkers, $barWorker); }
                }else if($dayofweek == 5){
                    if(BarbershopWorkerDays::where([['workerId',$barWorker->id],['workerDay','d5']])->first() != NUll){ array_push( $ourWorkers, $barWorker); }
                }else if($dayofweek == 6){
                    if(BarbershopWorkerDays::where([['workerId',$barWorker->id],['workerDay','d6']])->first() != NUll){ array_push( $ourWorkers, $barWorker); }
                }else if($dayofweek == 0){
                    if(BarbershopWorkerDays::where([['workerId',$barWorker->id],['workerDay','d0']])->first() != NUll){ array_push( $ourWorkers, $barWorker); }
                }
            }
        }
        return json_encode($ourWorkers);
    }






    public function fetchtheWorkerTermins(Request $req){
        // data: {dateSelected: dateSelected, barServiceId: barSerId, workerId: workerId, _token: '{{csrf_token()}}'},
        $dayofweek = date('w', strtotime($req->dateSelected));
        if($dayofweek == 1){
            $output = BarbershopWorkerTerminet::where([['worker',$req->workerId],['theDay','d1']])->get()->sortBy('renditja');
        }else if($dayofweek == 2){
            $output = BarbershopWorkerTerminet::where([['worker',$req->workerId],['theDay','d2']])->get()->sortBy('renditja');
        }else if($dayofweek == 3){
            $output = BarbershopWorkerTerminet::where([['worker',$req->workerId],['theDay','d3']])->get()->sortBy('renditja');
        }else if($dayofweek == 4){
            $output = BarbershopWorkerTerminet::where([['worker',$req->workerId],['theDay','d4']])->get()->sortBy('renditja');
        }else if($dayofweek == 5){
            $output = BarbershopWorkerTerminet::where([['worker',$req->workerId],['theDay','d5']])->get()->sortBy('renditja');
        }else if($dayofweek == 6){
            $output = BarbershopWorkerTerminet::where([['worker',$req->workerId],['theDay','d6']])->get()->sortBy('renditja');
        }else if($dayofweek == 0){
            $output = BarbershopWorkerTerminet::where([['worker',$req->workerId],['theDay','d0']])->get()->sortBy('renditja');
        }

        $sendData = array();

        foreach($output as $wTer){
            $worTerminDiff = 15;
            if($req->timeNeed > $worTerminDiff){
                if($req->timeNeed < (2 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id + 2);
                    if($endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }else if($req->timeNeed < (3 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id + 3);
                    if( $endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }else if($req->timeNeed < (4 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id + 4);
                    if( $endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }else if($req->timeNeed < (5 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id+ 5);
                    if( $endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }else if($req->timeNeed < (6 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id + 6);
                    if($endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }else if($req->timeNeed < (7 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id + 7);
                    if( $endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }else if($req->timeNeed < (8 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id + 8);
                    if($endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }else if($req->timeNeed < (9 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id + 9);
                    if( $endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }else if($req->timeNeed < (10 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id + 10);
                    if($endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }else if($req->timeNeed < (11 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id + 11);
                    if($endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }else if($req->timeNeed < (12 * $worTerminDiff)){
                    $endBarTermin = BarbershopWorkerTerminet::find($wTer->id + 12);
                    if($endBarTermin != NULL && $endBarTermin->worker == $wTer->worker && $endBarTermin->theDay == $wTer->theDay){$wTer->statT = 1;}else{$wTer->statT = 0;}
                }
            }else{
                $wTer->statT = 1;
            }
            array_push($sendData,$wTer);
        }

        // data: {dateSelected: dateSelected, barServiceId: barSerId, workerId: workerId, timeNeed: timeNeed, _token: '{{csrf_token()}}'},
        $sendData2 = array();
        foreach($sendData as $wTer2){
            if(BarbershopWorkerTerminBusy::where([['workerTerminID',$wTer2->id],['worker',$req->workerId],['date',$req->dateSelected]])->first() != NULL){
                $wTer2->statT = 2;
            }
            array_push($sendData2,$wTer2);
        }
        return json_encode($sendData2);
    }













    public function workerTerminsChValidity(Request $req){
        $thisBarTermin =  BarbershopWorkerTerminet::find($req->wTerId);

        $worTerminDiff = 15;
        if($req->timeNeed > $worTerminDiff){
            if($req->timeNeed < (2 * $worTerminDiff)){
                $endBarTermin = BarbershopWorkerTerminet::find($req->wTerId + 2);
                if($endBarTermin->worker == $thisBarTermin->worker && $endBarTermin->theDay == $thisBarTermin->theDay){$thisBarTermin->newValidity = 1;}else{$thisBarTermin->newValidity = 0;}
            }else if($req->timeNeed < (3 * $worTerminDiff)){
                $endBarTermin = BarbershopWorkerTerminet::find($req->wTerId + 3);
                if($endBarTermin->worker == $thisBarTermin->worker && $endBarTermin->theDay == $thisBarTermin->theDay){$thisBarTermin->newValidity = 1;}else{$thisBarTermin->newValidity = 0;}
            }else if($req->timeNeed < (4 * $worTerminDiff)){
                $endBarTermin = BarbershopWorkerTerminet::find($req->wTerId + 4);
                if($endBarTermin->worker == $thisBarTermin->worker && $endBarTermin->theDay == $thisBarTermin->theDay){$thisBarTermin->newValidity = 1;}else{$thisBarTermin->newValidity = 0;}
            }else if($req->timeNeed < (5 * $worTerminDiff)){
                $endBarTermin = BarbershopWorkerTerminet::find($req->wTerId + 5);
                if($endBarTermin->worker == $thisBarTermin->worker && $endBarTermin->theDay == $thisBarTermin->theDay){$thisBarTermin->newValidity = 1;}else{$thisBarTermin->newValidity = 0;}
            }else if($req->timeNeed < (6 * $worTerminDiff)){
                $endBarTermin = BarbershopWorkerTerminet::find($req->wTerId + 6);
                if($endBarTermin->worker == $thisBarTermin->worker && $endBarTermin->theDay == $thisBarTermin->theDay){$thisBarTermin->newValidity = 1;}else{$thisBarTermin->newValidity = 0;}
            }else if($req->timeNeed < (7 * $worTerminDiff)){
                $endBarTermin = BarbershopWorkerTerminet::find($req->wTerId + 7);
                if($endBarTermin->worker == $thisBarTermin->worker && $endBarTermin->theDay == $thisBarTermin->theDay){$thisBarTermin->newValidity = 1;}else{$thisBarTermin->newValidity = 0;}
            }else if($req->timeNeed < (8 * $worTerminDiff)){
                $endBarTermin = BarbershopWorkerTerminet::find($req->wTerId + 8);
                if($endBarTermin->worker == $thisBarTermin->worker && $endBarTermin->theDay == $thisBarTermin->theDay){$thisBarTermin->newValidity = 1;}else{$thisBarTermin->newValidity = 0;}
            }else if($req->timeNeed < (9 * $worTerminDiff)){
                $endBarTermin = BarbershopWorkerTerminet::find($req->wTerId + 9);
                if($endBarTermin->worker == $thisBarTermin->worker && $endBarTermin->theDay == $thisBarTermin->theDay){$thisBarTermin->newValidity = 1;}else{$thisBarTermin->newValidity = 0;}
            }
        }
        // timeNeed
        // dateSel

        return $thisBarTermin;
    }
}
