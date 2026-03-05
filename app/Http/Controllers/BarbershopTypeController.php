<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\BarbershopType;

class BarbershopTypeController extends Controller
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
        $newBT = new BarbershopType;
        $newBT->emri = $request->emri;
        $newBT->kategoria = $request->toCat;
        $newBT->vlera = $request->value;
        $newBT->toBar = $request->barbershop;
        $newBT->save();

        return redirect('/barbershopServicesType?barbershop='.$request->barbershop);
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
        $updateBT = BarbershopType::find($request->typeId);

        $updateBT->emri = $request->emri ;
        $updateBT->kategoria = $request->toCat;
        $updateBT->vlera = $request->value ;
        $updateBT->save();

        return redirect('/barbershopServicesType?barbershop='.$request->barbershop);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req){
        BarbershopType::find($req->id)->delete();
    }
}
