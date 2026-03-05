<?php

namespace App\Http\Controllers;

use App\PicLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PicLibController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSA(){
        return view('sa/superAdminIndex');
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
            'title' => 'required',
            'foto' => 'image|max:19999'
       ]);

        //get name .etc
        $fileNameOriginal = $request->file('foto')->getClientOriginalName();
        //get just the name
        $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
        // get extension
        $extension = $request->file('foto')->getClientOriginalExtension();

        $fileNameStore = $fileName.'_'.time().'.'.$extension;

            // Upload
        $path = $request->file('foto')->move('storage/PicLibrary', $fileNameStore);

        $newPL = new PicLibrary;
        $newPL->picLPhoto = $fileNameStore;
        $newPL->picLTitle = $request->title;
        $newPL->picLExt =  $extension;
        $newPL->save();

        return redirect()->route ('PicLibrary.indexSA')->with('success', 'Bild erfolgreich hinzugefügt');
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
        $PL = PicLibrary::find($req->id);

        $image_path = "storage/PicLibrary/".$PL->picLPhoto;  // Value is not URL but directory file path
        if(File::exists($image_path)) {
            File::delete($image_path);
        }

        $PL->delete();
    }

    public function searchPIcsLib(Request $req){
        $filteredData = PicLibrary::where('picLTitle', 'like', '%'.$req->searchWord.'%')->get();
        return json_encode( $filteredData );
    }
}
