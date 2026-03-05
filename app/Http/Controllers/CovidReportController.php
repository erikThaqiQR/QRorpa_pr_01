<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Covid;

class CovidReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 
    public function indexCovids(){
        return view('index');
    }
    public function index()
    {
        //
        $covids = Covid::all()->sortByDesc('created_at');
       
        return view('sa/superAdminIndex', ['covids'=> $covids]);

    }
    public function indexAdmin()
    {
        //
        $covidsAdmin = Covid::all()->sortByDesc('created_at');
       
        return view('adminPanel/adminIndex', ['covidsAdmin'=> $covidsAdmin]);

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
    public function store(Request $req){

    $phoneFUser = $req->input('tel');

        
        $sendTo = 445566 ;
        if(substr($phoneFUser, 0, 1) == 0){
            $pref =substr($phoneFUser, 0, 3);
            if($pref == '075' || $pref == '076' || $pref == '077' || $pref == '078' || $pref == '079'){
                if(strlen($phoneFUser) == 10){
                    $sendTo = '41'.substr($phoneFUser, 1, 9);
                }
            }
        }else{
            $pref =substr($phoneFUser, 0, 2);
            if($pref == '75' || $pref == '76' || $pref == '77' || $pref == '78' || $pref == '79'){
                $sendTo = '41'.$phoneFUser;
            }
        }
        if($sendTo != 445566){
            $sendTo2 = (int)$sendTo;

            $covid = new Covid;
            $covid->name = $req->input('name');
            $covid->vorname = $req->input('vorname');
            $covid->address = $req->input('address');
            $covid->plz = $req->input('plz');
            $covid->ort = $req->input('ort');
            $covid->tel = $req->input('tel');
            $covid->persons = $req->input('persons');
            $covid->restaurant_id = $req->input('restaurant_id');

            $covid->save();

            return redirect()->back()->with('success','Ihre Berichterstattung wurde erfolgreich hinzugefügt!');
        }else{
            return back()->with('errorMsg', 'Telefonnummer ist nicht korrekt');
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
}
