<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RecomendetProd;
use App\Produktet;
use App\Events\newOrder;
use App\Restorant;

class RecomendetProdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 123;
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
        $this->validate($request, [
            'pozita' => 'required',
       ]);

       if($request->photoFrom == 1){
            //get name .etc
            $fileNameOriginal = $request->file('foto')->getClientOriginalName();
            //get just the name
            $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
            // get extension
            $extension = $request->file('foto')->getClientOriginalExtension();

            $fileNameStore = $fileName.'_'.time().'.'.$extension;

                // Upload
            $path = $request->file('foto')->move('storage/RecUpload', $fileNameStore);
        }

        if(RecomendetProd::where('pozita', '=', $request->input('pozita'))->where('toRes',$request->input('toRes'))->count() > 0){
            $addOnes = RecomendetProd::where([['pozita', '>=', $request->input('pozita')],['toRes',$request->input('toRes')]])->get();
            if(!empty($addOnes)){
                foreach($addOnes as $addOne){
                    $addOne->pozita = $addOne->pozita + 1;
                    $addOne->save();
                }
            }
        }
       
        $recProd = new RecomendetProd;
        $Produkti = Produktet::find($request->input('produkti'));

        $recProd->produkti = $request->input('produkti');
        $recProd->pozita = $request->input('pozita');
        if($request->photoFrom == 1){
            $recProd->foto = $fileNameStore ;
        }else{
            $recProd->foto = $request->photo ;
        }
        $recProd->picFrom = $request->photoFrom ;
        $recProd->qmimi = $request->input('qmimiN');
        if($request->input('qmimiN2') != ""){
            $recProd->qmimi2 = $request->input('qmimiN2');
        }
        $recProd->toRes = $request->input('toRes');

        $recProd->save();



        event(new newOrder("recUpdate".$request->input('toRes')));
        

        if(isset($request->isWaiter)){
            return redirect()->route ('admWoMng.adminWoRecomendetProdWaiter')->with('success', 'Recomendet product added successfully');
        }else{
            return redirect()->route ('dash.recom')->with('success', 'Recomendet product added successfully');
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
            'qmimi' => 'required',
            'foto' => 'image|max:19999',
            'pozitaN' => 'required'
       ]);

       $recUpd = RecomendetProd::find($id);

       if(!empty($request->file('foto'))){
            //get name .etc
            $fileNameOriginal = $request->file('foto')->getClientOriginalName();
            //get just the name
            $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
            // get extension
            $extension = $request->file('foto')->getClientOriginalExtension();

            $fileNameStore = $fileName.'_'.time().'.'.$extension;

                // Upload
            $path = $request->file('foto')->move('storage/RecUpload', $fileNameStore);

            $recUpd->foto = $fileNameStore;
       }
        // set qmimin e ri 
        $recUpd->qmimi = $request->input('qmimi');
        if($request->input('qmimi2') != ""){
            $recUpd->qmimi2 = $request->input('qmimi2');
        }


        //set oziten e re 
       if($recUpd->pozita != $request->pozitaN){
            // me lart ne pozit -1-   
            if( $request->pozitaN < $recUpd->pozita){
                foreach(RecomendetProd::where([['toRes', '=', $recUpd->toRes],['pozita', '>=', $request->pozitaN],['pozita', '<', $recUpd->pozita]])->get() as $changeThese){
                    $changeThese->pozita = $changeThese->pozita+1;
                    $changeThese->save();
                }
                $recUpd->pozita = $request->pozitaN;
                $recUpd->save();
            //me posht ne pozit -2-
            }else{
                foreach(RecomendetProd::where([['toRes', $recUpd->toRes],['pozita', '<=', $request->pozitaN],['pozita', '>', $recUpd->pozita]])->get() as $changeThese){
                    $changeThese->pozita = $changeThese->pozita-1;
                    $changeThese->save();
                }
                $recUpd->pozita = $request->pozitaN;
                $recUpd->save();
            }
       }


        
        event(new newOrder("recUpdate".$recUpd->toRes));

        if(isset($request->isWaiter)){
            return redirect()->route ('admWoMng.adminWoRecomendetProdWaiter');
        }else{
            return redirect()->route ('dash.recom');
        }
    }
















    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $recDel = RecomendetProd::find($id);

        $bigerRecs =RecomendetProd::where([['pozita', '>', $recDel->pozita],['toRes',$recDel->toRes]])->get();
        foreach($bigerRecs as $bigerRec){
            $bigerRec->pozita = $bigerRec->pozita - 1;
            $bigerRec->save();
        }
        $recDel->delete();

        event(new newOrder("recUpdate".$recDel->toRes));

        if(isset($request->isWaiter)){
            return redirect()->route ('admWoMng.adminWoRecomendetProdWaiter');
        }else{
            return redirect()->route ('dash.recom');
        }
    }

    public function destroy2($id)
    {
        $recDel = RecomendetProd::find($id);

        $bigerRecs =RecomendetProd::where([['pozita', '>', $recDel->pozita],['toRes',$recDel->toRes]])->get();
        foreach($bigerRecs as $bigerRec){
            $bigerRec->pozita = $bigerRec->pozita - 1;
            $bigerRec->save();
        }
        $recDel->delete();

        event(new newOrder("recUpdate".$recDel->toRes));

     
        return redirect()->route ('admWoMng.adminWoRecomendetProdWaiter');
      
    }




    



    public function UpOne(Request $req){
        
        $recom = RecomendetProd::find($req->recom);

        $BackPoz = $recom->pozita;
        $recomFront = RecomendetProd::where([['pozita','=',--$BackPoz],['toRes','=',$recom->toRes]])->first();
        
        $recomFront->pozita = $recomFront->pozita + 1;
        $recomFront->save();
        $recom ->pozita = $recom ->pozita - 1;
        $recom ->save();

        event(new newOrder("recUpdate".$recom->toRes));

        // return redirect()->route ('dash.recom');
    }
     public function DownOne(Request $req){
        $recom = RecomendetProd::find($req->recom);

        $FrontPoz = $recom->pozita + 1;
        $recomFront = RecomendetProd::where([['pozita','=',$FrontPoz],['toRes','=',$recom->toRes]])->first();
        
        $recomFront->pozita = $recomFront->pozita - 1;
        $recomFront->save();

        $recom ->pozita = $recom ->pozita + 1;
        $recom ->save();

        event(new newOrder("recUpdate".$recom->toRes));

    
        // return redirect()->route ('dash.recom');
     }





    public function resetRecProdSorting(Request $req){
        // foreach(Restorant::all() as $restaurant){
        //     $newPozita = 1;
        //     foreach(RecomendetProd::where('toRes',$restaurant->id)->orderBy('pozita')->get() as $recProd){
        //         $recProd->pozita = $newPozita;
        //         $recProd->save();
        //         $newPozita++;
        //     }
        // }
    }


  
}
