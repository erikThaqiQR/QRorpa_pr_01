<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\BarbershopCategory;
class BarbershopCategoryController extends Controller
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
        $this->validate($request, [
            'emri' => 'required',
            'foto' => 'image|max:19999'
       ]);

       $newBC = new BarbershopCategory;

        //get name .etc
        $fileNameOriginal = $request->file('foto')->getClientOriginalName();
        //get just the name
        $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
        // get extension
        $extension = $request->file('foto')->getClientOriginalExtension();

        $fileNameStore = $fileName.'_'.time().'.'.$extension;

        // Upload
        $path = $request->file('foto')->move('storage/barbershop/CategoryUpload', $fileNameStore);

        $newBC->toBar = $request->barbershop;
        $newBC->emri = $request->emri;
        $newBC->foto = $fileNameStore;
        $newBC->save();

        return redirect('/barbershopServicesCategory?barbershop='.$request->barbershop);
        
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
        $updateBC = BarbershopCategory::find($request->barbershopCID);

        $updateBC->emri = $request->emri;

        if($request->foto != ''){
              //get name .etc
            $fileNameOriginal = $request->file('foto')->getClientOriginalName();
            //get just the name
            $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
            // get extension
            $extension = $request->file('foto')->getClientOriginalExtension();

            $fileNameStore = $fileName.'_'.time().'.'.$extension;

            // Upload
            $path = $request->file('foto')->move('storage/barbershop/CategoryUpload', $fileNameStore);

            $updateBC->foto =  $fileNameStore;
        }
        $updateBC->save();

        return redirect('/barbershopServicesCategory?barbershop='.$request->barbershop);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req){
        BarbershopCategory::find($req->id)->delete();
    }
}
