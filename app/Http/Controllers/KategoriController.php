<?php

namespace App\Http\Controllers;

use App\DeliveryProd;
use App\ekstra;
use App\kategori;
use App\LlojetPro;
use App\Produktet;
use App\RecomendetProd;
use App\Takeaway;
use App\waAdCatGroups;
use Cartalyst\Stripe\Api\Products;
use Illuminate\Http\Request;
use File;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $kategorit = kategori::all();
        $thisKat = ekstra::all();
        $types = LlojetPro::all();
        return view('sa/kategorite' ,['kat'=>$kategorit , 'thisKat'=>$thisKat, 'types' => $types]);
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
    //     $this->validate($request, [
    //         'emri' => 'required',
    //         'foto' => 'required|image|max:19999',
    //         'restaurant' => 'required'
    //    ]);
        if(empty($request->input('emri')) || $request->file('foto') == null){
            if(isset($request->userSA)){
                return redirect('/SuperAdminContentCat?goK='.$request->input('page'))->with('error','Empty fields');
            }else{
                return redirect('/produktet5?goK='.$request->input('page'))->with('error','Empty fields');
            }
           
        }

        //get name .etc
        $fileNameOriginal = $request->file('foto')->getClientOriginalName();
        //get just the name
        $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
        // get extension
        $extension = $request->file('foto')->getClientOriginalExtension();

        $fileNameStore = $fileName.'_'.time().'.'.$extension;
        // Upload
         $path = $request->file('foto')->move('storage/kategoriaUpload', $fileNameStore);

        $kategoria = new kategori;
        $kategoria->emri = $request->input('emri');
        $kategoria->foto = $fileNameStore;
        $kategoria->toRes = $request->input('restaurant');

        $kategoria->save();

        if(isset($request->userSA)){
            return redirect('/SuperAdminContentCat?Res='.$request->input('restaurant'))->with('success','Category added successfully' );
        }else{
            return redirect('/produktet5Category?Res='.$request->input('restaurant'))->with('success','Category added successfully' );
        }

    }




    public function storeAdminP(Request $req){
       
        if(isset($req->isWaiter)){
            if(empty($req->input('emri')) || ($req->file('foto') == null && $req->photo == '')){
                return redirect('/adminWoContentMngWaiter')->with('error','Leerfelder');
            }
            if (str_contains($req->input('emri'), '&') || str_contains($req->input('emri'), '+') || str_contains($req->input('emri'), '|') || str_contains($req->input('emri'), '||')){
                return redirect('/adminWoContentMngWaiter')->with('error','Dieser Kategoriename enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else if(isset($req->isAccountant)){
            if(empty($req->input('emri')) || ($req->file('foto') == null && $req->photo == '')){
                return redirect('/AccountantProducts')->with('error','Leerfelder');
            }
            if (str_contains($req->input('emri'), '&') || str_contains($req->input('emri'), '+') || str_contains($req->input('emri'), '|') || str_contains($req->input('emri'), '||')){
                return redirect('/AccountantProducts')->with('error','Dieser Kategoriename enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else{
            if(empty($req->input('emri')) || ($req->file('foto') == null && $req->photo == '')){
                return redirect('/dashboardContentMng')->with('error','Leerfelder');
            }
            if (str_contains($req->input('emri'), '&') || str_contains($req->input('emri'), '+') || str_contains($req->input('emri'), '|') || str_contains($req->input('emri'), '||')){
                return redirect('/dashboardContentMng')->with('error','Dieser Kategoriename enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }
        if($req->photoFrom == 1){
            //get name .etc
            $fileNameOriginal = $req->file('foto')->getClientOriginalName();
            //get just the name
            $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
            // get extension
            $extension = $req->file('foto')->getClientOriginalExtension();

            $fileNameStore = $fileName.'_'.time().'.'.$extension;
            // Upload
            $path = $req->file('foto')->move('storage/kategoriaUpload', $fileNameStore);
        }else{
            $picLibName = $req->emri.'_'.$req->restaurant.'_'.time().$req->photo;
            File::copy(public_path('storage/PicLibrary/'.$req->photo), public_path('storage/kategoriaUpload/'.$picLibName));
        }

       


        $kategoria = new kategori;
        $kategoria->emri = $req->emri;
        if($req->photoFrom == 1){
            $kategoria->foto = $fileNameStore;
        }else{
            $kategoria->foto = $picLibName;
        }
        $kategoria->toRes = $req->restaurant;
        
        $maxPosition = kategori::where('toRes',$req->restaurant)->max('position');
        $kategoria->position = ++$maxPosition;
        $kategoria->acsByClRes = $req->catAccessByClientsValRes;
        $kategoria->acsByClTA = $req->catAccessByClientsValTA;
        $kategoria->acsByCldDE = $req->catAccessByClientsValDE;

        $kategoria->save();

        if(isset($req->isWaiter)){
            return redirect('/adminWoContentMngWaiter')->with('success','Kategorie erfolgreich hinzugefügt' );
        }else if(isset($req->isAccountant)){
            return redirect('/AccountantProducts')->with('success','Kategorie erfolgreich hinzugefügt' );
        }else{
            return redirect('/dashboardContentMng')->with('success','Kategorie erfolgreich hinzugefügt' );
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $this->validate($request, [
            'emri' => 'required',
        ]);
        $editKat = kategori::find($id);

        if($request->file('foto') != null){
            // reapir null exception 
            
            //get name .etc
            $fileNameOriginal = $request->file('foto')->getClientOriginalName();
            //get just the name
            $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
            // get extension
            $extension = $request->file('foto')->getClientOriginalExtension();
            $fileNameStore = $fileName.'_'.time().'.'.$extension;
            // Upload
            $path = $request->file('foto')->move('storage/kategoriaUpload', $fileNameStore);
            $editKat->foto = $fileNameStore;
        }

        $editKat->emri = $request->input('emri');
        $editKat->save();

        if(isset($request->userSA)){
            return redirect('/SuperAdminContentCat?Res='.$request->input('page'))->with('success','Die Kategorie wurde erfolgreich geändert' );
        }else{
            return redirect('/produktet5Category?Res='.$request->input('page'))->with('success','Die Kategorie wurde erfolgreich geändert' );
        }
    }



    public function updateAdminP(Request $request, $id){
        $this->validate($request, [
            'emri' => 'required',
        ]);
        if(isset($request->isWaiter)){
            if (str_contains($request->input('emri'), '&') || str_contains($request->input('emri'), '+') || str_contains($request->input('emri'), '|') || str_contains($request->input('emri'), '||')){
                return redirect('/adminWoContentMngWaiter')->with('error','Dieser Kategoriename enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else if(isset($request->isAccountant)){
            if (str_contains($request->input('emri'), '&') || str_contains($request->input('emri'), '+') || str_contains($request->input('emri'), '|') || str_contains($request->input('emri'), '||')){
                return redirect('/AccountantProducts')->with('error','Dieser Kategoriename enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else{
            if (str_contains($request->input('emri'), '&') || str_contains($request->input('emri'), '+') || str_contains($request->input('emri'), '|') || str_contains($request->input('emri'), '||')){
                return redirect('/dashboardContentMng')->with('error','Dieser Kategoriename enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }
        $editKat = kategori::find($id);
        if($request->photoFrom == 1){
            if($request->file('foto') != null){
                // reapir null exception 
                
                //get name .etc
                $fileNameOriginal = $request->file('foto')->getClientOriginalName();
                //get just the name
                $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
                // get extension
                $extension = $request->file('foto')->getClientOriginalExtension();
                $fileNameStore = $fileName.'_'.time().'.'.$extension;
                // Upload
                $path = $request->file('foto')->move('storage/kategoriaUpload', $fileNameStore);
                $editKat->foto = $fileNameStore;
            }
        }else{
            $picLibName = $request->emri.'_'.$request->restaurant.'_'.time().$request->photo;
            File::copy(public_path('storage/PicLibrary/'.$request->photo), public_path('storage/kategoriaUpload/'.$picLibName));
            $editKat->foto = $picLibName;
        }

        $editKat->emri = $request->input('emri');
        $editKat->acsByClRes = $request->catAccessByClientsValEditRes;
        $editKat->acsByClTA = $request->catAccessByClientsValEditTA;
        $editKat->acsByCldDE = $request->catAccessByClientsValEditDE;
        $editKat->save();
        
        if(isset($request->isWaiter)){
            return redirect('/adminWoContentMngWaiter')->with('success','Die Kategorie wurde erfolgreich geändert' );
        }else if(isset($request->isAccountant)){
            return redirect('/AccountantProducts')->with('success','Die Kategorie wurde erfolgreich geändert' );
        }else{
            return redirect('/dashboardContentMng')->with('success','Die Kategorie wurde erfolgreich geändert' );
        }
    }




















    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $req){
        $theCat = kategori::find($id);
      

        // delete the aasoc ekstra
        foreach(ekstra::where('toCat',$theCat->id)->get() as $oneEx){ $oneEx->delete(); }

        // delete the aasoc types
        foreach(LlojetPro::where('kategoria',$theCat->id)->get() as $oneTy){ $oneTy->delete(); }

        // delete the assoc products
        foreach(Produktet::where([['toRes',$theCat->toRes],['kategoria',$req->id]])->get() as $catProd){ 

            // Delete Takeaway product
            $prodInTakeaway = Takeaway::where('prod_id',$catProd->id)->first();
            if($prodInTakeaway != NULL){ 
                // Fix takeaway product position
                foreach(Takeaway::where([['toRes',$prodInTakeaway->toRes],['kategoria',$prodInTakeaway->kategoria],['position','>',$prodInTakeaway->position]])->get() as $upT){
                    $upT->position = $upT->position-1;
                    $upT->save();
                }
                // Fix takeaway kategory product position
                if(Takeaway::where([['toRes',$prodInTakeaway->toRes],['kategoria',$prodInTakeaway->kategoria]])->count() == 1){
                    $upThisKat = kategori::find($prodInTakeaway->kategoria);
                    foreach(kategori::where([['toRes',$prodInTakeaway->toRes],['positionTakeaway','>', $upThisKat->positionTakeaway]])->get() as $upCatt){
                        $upCatt->positionTakeaway = $upCatt->positionTakeaway-1;
                        $upCatt->save();
                    }
                    $upThisKat->positionTakeaway = 1;
                    $upThisKat->save();
                }
                $prodInTakeaway->delete(); 
            }

            // Delete delivery product 
            $prodInDelivery = DeliveryProd::where('prod_id',$catProd->id)->first();
            if($prodInDelivery != NULL){ 
                // Fix delivery product position
                foreach(DeliveryProd::where([['toRes',$prodInDelivery->toRes],['kategoria',$prodInDelivery->kategoria],['position','>',$prodInDelivery->position]])->get() as $upD){
                    $upD->position = $upD->position-1;
                    $upD->save();
                }
                // Fix delivery kategory product position
                if(DeliveryProd::where('kategoria',$prodInDelivery->kategoria)->count() == 1){
                    $upThisKat = kategori::find($prodInDelivery->kategoria);
                    foreach(kategori::where([['toRes',$prodInDelivery->toRes],['positionDelivery','>', $upThisKat->positionDelivery]])->get() as $upCatt){
                        $upCatt->positionDelivery = $upCatt->positionDelivery-1;
                        $upCatt->save();
                    }
                    $upThisKat->positionDelivery = 1;
                    $upThisKat->save();
                }
                $prodInDelivery->delete(); 
            }

            $catProd->delete();
        }

        $theCat->delete();

        if(isset($req->userSA)){
            return redirect('/SuperAdminContentCat?Res='.$req->input('res'))->with('success','Die Kategorie wurde erfolgreich geändert' );
        }else{
            return redirect('/produktet5Category?Res='.$req->input('res'))->with('success','Die Kategorie wurde erfolgreich gelöscht' );
        }
    }






    public function destroyAdminP(Request $req){
        $theCat = kategori::find($req->id);
        foreach(ekstra::where([['toRes',$theCat->toRes],['toCat',$req->id]])->get() as $catExtra){ 
            $catExtra->delete();
        }
        foreach(LlojetPro::where([['toRes',$theCat->toRes],['kategoria',$req->id]])->get() as $catType){ 
            $catType->delete();
        }
        foreach(Produktet::where([['toRes',$theCat->toRes],['kategoria',$req->id]])->get() as $catProd){ 
            // Delete Takeaway product
            $prodInTakeaway = Takeaway::where('prod_id',$catProd->id)->first();
            if($prodInTakeaway != NULL){ 
                // Fix takeaway product position
                foreach(Takeaway::where([['toRes',$prodInTakeaway->toRes],['kategoria',$prodInTakeaway->kategoria],['position','>',$prodInTakeaway->position]])->get() as $upT){
                    $upT->position = $upT->position-1;
                    $upT->save();
                }
                // Fix takeaway kategory product position
                if(Takeaway::where([['toRes',$prodInTakeaway->toRes],['kategoria',$prodInTakeaway->kategoria]])->count() == 1){
                    $upThisKat = kategori::find($prodInTakeaway->kategoria);
                    foreach(kategori::where([['toRes',$prodInTakeaway->toRes],['positionTakeaway','>', $upThisKat->positionTakeaway]])->get() as $upCatt){
                        $upCatt->positionTakeaway = $upCatt->positionTakeaway-1;
                        $upCatt->save();
                    }
                    $upThisKat->positionTakeaway = 1;
                    $upThisKat->save();
                }
                $prodInTakeaway->delete(); 
            }

            // Delete delivery product 
            $prodInDelivery = DeliveryProd::where('prod_id',$catProd->id)->first();
            if($prodInDelivery != NULL){ 
                // Fix delivery product position
                foreach(DeliveryProd::where([['toRes',$prodInDelivery->toRes],['kategoria',$prodInDelivery->kategoria],['position','>',$prodInDelivery->position]])->get() as $upD){
                    $upD->position = $upD->position-1;
                    $upD->save();
                }
                // Fix delivery kategory product position
                if(DeliveryProd::where('kategoria',$prodInDelivery->kategoria)->count() == 1){
                    $upThisKat = kategori::find($prodInDelivery->kategoria);
                    foreach(kategori::where([['toRes',$prodInDelivery->toRes],['positionDelivery','>', $upThisKat->positionDelivery]])->get() as $upCatt){
                        $upCatt->positionDelivery = $upCatt->positionDelivery-1;
                        $upCatt->save();
                    }
                    $upThisKat->positionDelivery = 1;
                    $upThisKat->save();
                }
                $prodInDelivery->delete(); 
            }

            $recProd = RecomendetProd::where([['toRes',$catProd->toRes],['produkti',$catProd->id]])->first();
            if($recProd != NULL){ $recProd->delete(); }

            $catProd->delete();
        }

        // fshihet instanca e kategoris ne grup per admin/kamarier
        foreach(waAdCatGroups::where('forCat',$theCat->id) as $grOfAK){
            $grOfAK->delete();
        } 

        $theCat->delete();
    }


    public function addVisit(Request $req){
        $kat = kategori::find($req->id);
        $kat->visits += 1;
        $kat->save();
    }
}
