<?php

namespace App\Http\Controllers;

use App\FreeProducts;
use App\Restorant;
use Illuminate\Http\Request;
use Auth;

class FreeProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newFree = new FreeProducts;

        $newFree->toRes = Auth::user()->sFor;
        $newFree->prod_id = $request->id;
        $newFree->active = 1;

        $newFree->save();
    }

    public function storeExtra(Request $req){
        $newFree = new FreeProducts;

        $newFree->toRes = Auth::user()->sFor;
        $newFree->prod_id = 0;
        $newFree->active = 1;
        $newFree->nameExt = $req->name;

        $newFree->save();
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
    public function destroy(Request $req){
        FreeProducts::find($req->id)->delete();
    }

    public function changePriceFree(Request $req){
       $theRes = Restorant::find($req->toRest);
       $theRes->priceFree = $req->newVal;
       $theRes->save();

       return $req->newVal;
    }

    public function activeFree(Request $req){
        $theRes = Restorant::find($req->resId);
        $theRes->allowFree = $req->newVal;
        $theRes->save();
    }

    public function changeTextFree(Request $req){
        $theRes = Restorant::find($req->toRest);
        $theRes->textFree = $req->newVal;
        $theRes->save();

        return $req->newVal;
    }




}
