<?php

namespace App\Http\Controllers;

use Cart;

use App\User;
use App\TabOrder;
use App\Restorant;

use App\RestaurantWH;
use Illuminate\Http\Request;
use App\tabVerificationPNumbers;

class RestorantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
      public function indexPP(){
        return view('index');
    }
    public function index(){
        return view('sa/superAdminIndex');
    }
    public function indexWH(){
        return view('sa/superAdminIndex');
    }
    public function openResTNotFound(){
        return view('restnotfound');
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
        $this->validate($request, [
            'emri' => 'required',
            'adresa' => 'required',
            'resPNr' => 'required',
            'che1' => 'required',
            'che2' => 'required',
            'che3' => 'required',
       ]);

        $newRes = new Restorant;
        $newRes->emri = $request->input('emri');
        $newRes->adresa = $request->input('adresa');
        $newRes->resPhoneNr = $request->input('resPNr');

        $cheMwst = $request->input('che1').'.'.$request->input('che2').'.'.$request->input('che3');
        $newRes->chemwstForRes = $cheMwst;

        if($request->input('userID') != NULL){
            $newRes->accessID = $request->userID;
        }else{
            $newRes->accessID = 0;
        }
        $newRes->save();

        if($request->input('userID') != NULL){
            return redirect('/SuperAdminContent')->with('success','Das Restaurant wurde erfolgreich erstellt');
        }else{
            return redirect('/Restorantet')->with('success','Das Restaurant wurde erfolgreich erstellt');
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


    public function setOneDayWH(Request $req){
        if(RestaurantWH::where('toRes', $req->res)->first() == null){
            $newRWH = new RestaurantWH;

            $newRWH->toRes = $req->res;
            switch($req->day){
                case 'D1':
                    $newRWH->D1Starts1 = $req->in1;
                    $newRWH->D1End1 = $req->out1;
                    $newRWH->D1Starts2 = $req->in2;
                    $newRWH->D1End2 = $req->out2;
                break;
                case 'D2':
                    $newRWH->D2Starts1 = $req->in1;
                    $newRWH->D2End1 = $req->out1;
                    $newRWH->D2Starts2 = $req->in2;
                    $newRWH->D2End2 = $req->out2;
                break;
                case 'D3':
                    $newRWH->D3Starts1 = $req->in1;
                    $newRWH->D3End1 = $req->out1;
                    $newRWH->D3Starts2 = $req->in2;
                    $newRWH->D3End2 = $req->out2;
                break;
                case 'D4':
                    $newRWH->D4Starts1 = $req->in1;
                    $newRWH->D4End1 = $req->out1;
                    $newRWH->D4Starts2 = $req->in2;
                    $newRWH->D4End2 = $req->out2;
                break;
                case 'D5':
                    $newRWH->D5Starts1 = $req->in1;
                    $newRWH->D5End1 = $req->out1;
                    $newRWH->D5Starts2 = $req->in2;
                    $newRWH->D5End2 = $req->out2;
                break;
                case 'D6':
                    $newRWH->D6Starts1 = $req->in1;
                    $newRWH->D6End1 = $req->out1;
                    $newRWH->D6Starts2 = $req->in2;
                    $newRWH->D6End2 = $req->out2;
                break;
                case 'D7':
                    $newRWH->D7Starts1 = $req->in1;
                    $newRWH->D7End1 = $req->out1;
                    $newRWH->D7Starts2 = $req->in2;
                    $newRWH->D7End2 = $req->out2;
                break;
            }

            $newRWH->save();
        }else{
            $newRWH =RestaurantWH::where('toRes', $req->res)->first();

            switch($req->day){
                case 'D1':
                    $newRWH->D1Starts1 = $req->in1;
                    $newRWH->D1End1 = $req->out1;
                    $newRWH->D1Starts2 = $req->in2;
                    $newRWH->D1End2 = $req->out2;
                break;
                case 'D2':
                    $newRWH->D2Starts1 = $req->in1;
                    $newRWH->D2End1 = $req->out1;
                    $newRWH->D2Starts2 = $req->in2;
                    $newRWH->D2End2 = $req->out2;
                break;
                case 'D3':
                    $newRWH->D3Starts1 = $req->in1;
                    $newRWH->D3End1 = $req->out1;
                    $newRWH->D3Starts2 = $req->in2;
                    $newRWH->D3End2 = $req->out2;
                break;
                case 'D4':
                    $newRWH->D4Starts1 = $req->in1;
                    $newRWH->D4End1 = $req->out1;
                    $newRWH->D4Starts2 = $req->in2;
                    $newRWH->D4End2 = $req->out2;
                break;
                case 'D5':
                    $newRWH->D5Starts1 = $req->in1;
                    $newRWH->D5End1 = $req->out1;
                    $newRWH->D5Starts2 = $req->in2;
                    $newRWH->D5End2 = $req->out2;
                break;
                case 'D6':
                    $newRWH->D6Starts1 = $req->in1;
                    $newRWH->D6End1 = $req->out1;
                    $newRWH->D6Starts2 = $req->in2;
                    $newRWH->D6End2 = $req->out2;
                break;
                case 'D7':
                    $newRWH->D7Starts1 = $req->in1;
                    $newRWH->D7End1 = $req->out1;
                    $newRWH->D7Starts2 = $req->in2;
                    $newRWH->D7End2 = $req->out2;
                break;
            }

            $newRWH->save();
        }
        
    }












    public function setAllWH(Request $req){
        if(RestaurantWH::where('toRes', $req->res)->first() == null){
            $newRWH = new RestaurantWH;
            $newRWH->toRes = $req->res;
        }else{
            $newRWH =RestaurantWH::where('toRes', $req->res)->first();
        }
      
        $newRWH->D1Starts1 =$req->D1in1;
        $newRWH->D1End1 =   $req->D1out1;
        $newRWH->D1Starts2 =$req->D1in2;
        $newRWH->D1End2 =   $req->D1out2;
        $newRWH->D2Starts1 =$req->D2in1;
        $newRWH->D2End1 =   $req->D2out1;
        $newRWH->D2Starts2 =$req->D2in2;
        $newRWH->D2End2 =   $req->D2out2;
        $newRWH->D3Starts1 =$req->D3in1;
        $newRWH->D3End1 =   $req->D3out1;
        $newRWH->D3Starts2 =$req->D3in2;
        $newRWH->D3End2 =   $req->D3out2;
        $newRWH->D4Starts1 =$req->D4in1;
        $newRWH->D4End1 =   $req->D4out1;
        $newRWH->D4Starts2 =$req->D4in2;
        $newRWH->D4End2 =   $req->D4out2;
        $newRWH->D5Starts1 =$req->D5in1;
        $newRWH->D5End1 =   $req->D5out1;
        $newRWH->D5Starts2 =$req->D5in2;
        $newRWH->D5End2 =   $req->D5out2;
        $newRWH->D6Starts1 =$req->D6in1;
        $newRWH->D6End1 =   $req->D6out1;
        $newRWH->D6Starts2 =$req->D6in2;
        $newRWH->D6End2 =   $req->D6out2;
        $newRWH->D7Starts1 =$req->D7in1;
        $newRWH->D7End1 =   $req->D7out1;
        $newRWH->D7Starts2 =$req->D7in2;
        $newRWH->D7End2 =   $req->D7out2;
        $newRWH->save();

    }



    public function setResMap(Request $req){

        $thisRes = Restorant::find($req->res);

        $thisRes->map = $req->src;
        $thisRes->save();

        
        if(isset($req->userSA)){
            return redirect()->route('homeConRegUserBox',['Res'=>$req->res]);
        }else{
            return redirect()->route('restorantet.workingHours');
        }

    }

    public function setDesc(Request $req){
        
        $thisRes = Restorant::find($req->res);

        $thisRes->resDesc = $req->desc;
        $thisRes->save();

        if(isset($req->userSA)){
            return redirect()->route('homeConRegUserBox',['Res'=>$req->res]);
        }else{
            return redirect()->route('restorantet.workingHours');
        }
    }

    public function setProfilePic(Request $request){

        $theRes = Restorant::find($request->toRes);

        $this->validate($request, [
            'foto' => 'image'
        ]);
         //get name .etc
         $fileNameOriginal = $request->file('foto')->getClientOriginalName();
         //get just the name
         $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
         // get extension
         $extension = $request->file('foto')->getClientOriginalExtension();
 
         $fileNameStore = $fileName.'_'.time().'.'.$extension;
 
             // Upload
         $path = $request->file('foto')->move('storage/ResProfilePic', $fileNameStore);

         $theRes->profilePic = $fileNameStore ;
         $theRes->save();

         return redirect()->route('restorantet.workingHours');
    }





    public function setBackgroundPic(Request $request){

        $theRes = Restorant::find($request->toRes);

        $this->validate($request, [
            'foto' => 'image|max:199999'
        ]);
         //get name .etc
         $fileNameOriginal = $request->file('foto')->getClientOriginalName();
         //get just the name
         $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
         // get extension
         $extension = $request->file('foto')->getClientOriginalExtension();
 
         $fileNameStore = $fileName.'_'.time().'.'.$extension;
 
             // Upload
         $path = $request->file('foto')->move('storage/ResBackgroundPic', $fileNameStore);

         $theRes->bPic = $fileNameStore ;
         $theRes->save();

         return redirect()->route('restorantet.workingHours');
    }

    
    public function removeBackgroundPic(Request $request){

        $theRes = Restorant::find($request->toRes);

 
         $theRes->bPic = 'none' ;
         $theRes->save();

         return redirect()->route('restorantet.workingHours');
    }





    public function cashPayClick(Request $req){
        $theR = Restorant::find($req->id);
        $theR->cashPayClicks += 1;
        $theR->save();
    }

    public function ResOpenCount(Request $req){
        $theR = Restorant::find($req->id);
        $theR->openResTimes += 1;
        $theR->save();
    }










    public function DeleteCartForMe(Request $req){
        Cart::destroy();
        Cart::destroy();
        Cart::destroy();
        Cart::destroy();
    }




    public function setResType(Request $req){
        $theR = Restorant::find($req->id);
        $theR->resType = $req->newVal;
        $theR->save();
    }
    public function setSerPayAm(Request $req){
        $theR = Restorant::find($req->id);
        $theR->payAmSer =  $req->newVal;
        $theR->save();
    }















    public function ResContentMng(Request $req){
        $theR = Restorant::find($req->resID);
        $theR->accessID = $req->userID;
        $theR->save();
    }
    public function CMUsrRoleSet(Request $req){
        $theUsr = User::find($req->userID);
        $theUsr->role = $req->role;
        $theUsr->save();
    }






    public function setNewTimeForTheRes(Request $req){
		// resId: resId,
		// newTime: $('#newTimeRes').val(),

        $theR = Restorant::find($req->resId);
        $theR->secondPriceTime = $req->newTime;
        $theR->save();
    }   






    public function checkGhostForTable(Request $req){
        // resId
        // tableNr
        $hasOnePlusAdPr = False;
        $hasOnePlusGhostCode = False;
        foreach (TabOrder::where([['toRes',$req->resId],['tableNr',$req->tableNr],['tabCode','!=','0']])->get() as $tabO){
            if ( tabVerificationPNumbers::where('tabOrderId',$tabO->id)->first()->phoneNr == '0770000000'){
                $hasOnePlusAdPr = True;
                break;
            }
        }
        if($hasOnePlusAdPr){
            return 'hasGhostTb';
        }else{
  
            if(tabVerificationPNumbers::where('status','1')->get()->count() > 0){
                foreach(tabVerificationPNumbers::where('status','1')->get() as $tabVerNr){
                    $gton = TabOrder::find($tabVerNr->tabOrderId);
                    if($gton != NULL && $gton->toRes == $req->resId && $gton->tableNr == $req->tableNr && str_contains($tabVerNr->phoneNr, '999999|')){
                        $hasOnePlusGhostCode = True;
                        break;
                    }
                }
            }
            if($hasOnePlusGhostCode){
                return 'hasGhostTb';
            }else{
                return 'ghostNoneTb';
            }
        }
    }
}
