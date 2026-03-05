<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Barberservice;

class BarberserviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $barberservices = Barberservice::all()->sortByDesc('created_at');
        return view('sa/superAdminIndex', compact('barberservices'));
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
        $barberservice = new Barberservice;
        $barberservice->name = $request->input('name');
        $barberservice->description = $request->input('description');
        $barberservice->minutes = $request->input('minutes');
        $barberservice->price = $request->input('price');

        $barberservice->save();

        return redirect('/barberservices')->with('success','Das Restaurant wurde erfolgreich erstellt');
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
            'description' => 'required',
            'minutes' => 'required',
            'price' => 'required',
        ]);
        $barberservice = Barberservice::find($id);


        $barberservice->name = $request->input('name');
        $barberservice->description = $request->input('description');
        $barberservice->minutes = $request->input('minutes');
        $barberservice->price = $request->input('price');
        $barberservice->save();

        return redirect('/barberservices')->with('success','Die Kategorie wurde erfolgreich geändert' );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Barberservice::find($id)->delete();
        return redirect('/barberservices')->with('success','Die Kategorie wurde erfolgreich gelöscht');
    }
}
