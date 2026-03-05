<?php

namespace App\Http\Controllers;

use App\accessControllForAdmins;
use App\User;
use Illuminate\Http\Request;

class SuperadminAccessMngController extends Controller
{

    public function index(){
        return view('sa/superAdminIndex');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
    }

  
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }



    public function fetchAdmins(Request $req){
        $result = User::where([['role','5'],['sFor',$req->resId]])->get();
        return json_encode( $result );
    }

    public function fetchAccess(Request $req){
        $pagesAdmin = ['Aufträge','Empfohlen','Kellner','Products','Tabellenwechsel','Trinkgeld','Frei','16+/18+','Gutscheincode','Takeaway','Delivery','Tischkapazität',
        'Tischreservierungen','Dienstleistungen','Covid-19','talkToQrorpaSA','workersManagement','RechnungMngAcce','Tabellenstatus_Tische','Heute_Verkaufe'];

        $V1 = accessControllForAdmins::where([['forRes',$req->resId],['accessDsc','Statistiken']])->first();
        if($V1 != NULL && $V1->accessValid == 1){ $result = '1'; }else{ $result = '0'; }

        foreach($pagesAdmin as $p1){
            $V1 = accessControllForAdmins::where([['forRes',$req->resId],['accessDsc',$p1]])->first();
            if($V1 != NULL && $V1->accessValid == 1){ $result = $result.'||1'; }else{ $result = $result.'||0'; }
        }
        return $result ;
    }


    public function regDelAccess(Request $req){
        //   type: active,
        //   res: resId,
        //   pageN: pName,
        if($req->type == 0){
            // register
            foreach(User::where([['role','5'],['sFor',$req->res]])->get() as $admins){
                $accR = new accessControllForAdmins;
                $accR->userId = $admins->id;
                $accR->forRes = $req->res;
                $accR->accessDsc = $req->pageN;
                $accR->accessValid = 1;
                $accR->save();
            }
        }else{
            // delete
            foreach(User::where([['role','5'],['sFor',$req->res]])->get() as $admins){
                $accR = accessControllForAdmins::where([['userId',$admins->id],['accessDsc',$req->pageN]])->firstOrFail()->delete();
            }
        }
        return $req->res;
    }

    public function regAllAccess(Request $req){

        $pagesAdmin = ['Statistiken','Aufträge','Empfohlen','Kellner','Products','Tabellenwechsel','Trinkgeld','Frei','16+/18+','Gutscheincode','Takeaway','Delivery',
        'Tischkapazität','Tischreservierungen','Dienstleistungen','Covid-19','talkToQrorpaSA','workersManagement','RechnungMngAcce','Tabellenstatus_Tische','Heute_Verkaufe'];
        foreach(User::where([['role','5'],['sFor',$req->res]])->get() as $admins){
            foreach($pagesAdmin as $p1){
                $theAccess = accessControllForAdmins::where([['userId',$admins->id],['accessDsc',$p1]])->first();
                if($theAccess == NULL){
                    // is not registred , we'll register it
                    $accR = new accessControllForAdmins;
                    $accR->userId = $admins->id;
                    $accR->forRes = $req->res;
                    $accR->accessDsc = $p1;
                    $accR->accessValid = 1;
                    $accR->save();
                }
            }
        }
    }

    public function delAllAccess(Request $req){
        foreach(accessControllForAdmins::where('forRes',$req->res)->get() as $delAccOfRes){
            $delAccOfRes->delete();
        }
    }




    public function changeValidity(Request $req){
        // id: accessId, validity: newV,
        $accR = accessControllForAdmins::findOrFail($req->id);
        $accR->accessValid = $req->validity;
        $accR->save();
    }
}
