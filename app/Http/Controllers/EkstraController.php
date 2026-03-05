<?php

namespace App\Http\Controllers;

use App\ekstra;
use App\kategori;
use App\Produktet;
use UsingRefs\Product;
use Illuminate\Http\Request;
use Cartalyst\Stripe\Api\Products;
use Illuminate\Support\Facades\Auth;

class EkstraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ekstra = ekstra::all();
        return view('sa/ekstra')->with('ekstra', $ekstra);
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
        // extraEmri: $('#extraEmri').val(),
		// extraQmimi: $('#extraQmimi').val(),
		// extraKategoriSel: $('#selectedCatForExtra').val(),

        $catSel = explode('|||',$req->extraKategoriSel);
        $countExtrReg = 0;
        foreach($catSel as $catSelOne){
            $extra = new ekstra;

            $extra->emri = $req->extraEmri;
            $extra->qmimi = $req->extraQmimi;
            $extra->toCat = $catSelOne;
            $extra->toRes = Auth::user()->sFor;

            $extra->save();

            $countExtrReg++;
        }
       return $countExtrReg;
    }



    
    public function storeAdminP(Request $request){
        //     $this->validate($request, [
        //         'emri' => 'required',
        //         'qmimi' => 'required',
        //    ]);
        if(isset($request->isWaiter)){
            if (empty($request->input('emri')) || $request->input('qmimi') < 0) {
                return redirect('/adminWoContentMngWaiter')->with('error', 'Leerfelder');
            }
            if (str_contains($request->input('emri'), '&') || str_contains($request->input('emri'), '+') || str_contains($request->input('emri'), '|') || str_contains($request->input('emri'), '||')){
                return redirect('/adminWoContentMngWaiter')->with('error','Dieser zusätzliche Produktname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else if($request->isAccountant){
            if (empty($request->input('emri')) || $request->input('qmimi') < 0) {
                return redirect('/AccountantProducts')->with('error', 'Leerfelder');
            }
            if (str_contains($request->input('emri'), '&') || str_contains($request->input('emri'), '+') || str_contains($request->input('emri'), '|') || str_contains($request->input('emri'), '||')){
                return redirect('/AccountantProducts')->with('error','Dieser zusätzliche Produktname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else{
            if (empty($request->input('emri')) || $request->input('qmimi') < 0) {
                return redirect('/dashboardContentMng')->with('error', 'Leerfelder');
            }
            if (str_contains($request->input('emri'), '&') || str_contains($request->input('emri'), '+') || str_contains($request->input('emri'), '|') || str_contains($request->input('emri'), '||')){
                return redirect('/dashboardContentMng')->with('error','Dieser zusätzliche Produktname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }
        $extra = new ekstra;

        $extra->emri = $request->input('emri');
        $extra->qmimi = $request->input('qmimi');
        $extra->toCat = $request->input('toCat');
        $extra->toRes = $request->input('restaurant');

        $extra->save();

        if(isset($request->isWaiter)){
            return redirect('/adminWoContentMngWaiter')->with('success', 'Extra erfolgreich hinzugefügt');
        }if(isset($request->isAccountant)){
            return redirect('/AccountantProducts')->with('success', 'Extra erfolgreich hinzugefügt');
        }else{
            return redirect('/dashboardContentMng')->with('success', 'Extra erfolgreich hinzugefügt');
        }
    }

    public function storeAdminPPro(Request $req){
        // emri: emriExtProAdd,
		// qmimi: qmimiExtProAdd,
		// cate: kategoriaExtProAdd,
		// res: resExtProAdd,
        if($req->emri == "" || $req->qmimi == ""){
            return 'Bitte unbedingt die notwendigen Daten schreiben!';
        }

        $extra = new ekstra;

        $extra->emri = $req->emri;
        $extra->qmimi = $req->qmimi;
        $extra->toCat = $req->cate;
        $extra->toRes = $req->res;

        $extra->save();

        return 'success';
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
        $editExt = ekstra::find($id);


        $editExt->emri = $request->input('emri');
        $editExt->qmimi = $request->input('qmimi');
        $editExt->toCat = $request->input('toCat');
        $editExt->save();

        // edit them on products too 
        foreach (Produktet::where([['toRes', $editExt->toRes], ['extPro', '!=', NULL]])->get() as $theProd) {
            $newProExtras = "";
            foreach (explode('--0--', $theProd->extPro) as $oneEx) {
                if ($oneEx != '') {
                    $oneEx2D = explode('||', $oneEx);
                    if( $oneEx2D[0] == $editExt->id){
                        if ($newProExtras == "") {
                            $newProExtras = $editExt->id . '||' . $editExt->emri . '||' . $editExt->qmimi ;
                        }else{
                            $newProExtras .= '--0--' . $editExt->id . '||' . $editExt->emri . '||' . $editExt->qmimi ;
                        }
                    }else{
                        if ($newProExtras == "") {
                            $newProExtras = $oneEx2D[0] . '||' . $oneEx2D[1] . '||' . $oneEx2D[2];
                        }else{
                            $newProExtras .= '--0--' . $oneEx2D[0] . '||' . $oneEx2D[1] . '||' . $oneEx2D[2];
                        }
                    }
                }
            }
        }

        if (isset($request->userSA)) {
            return redirect('/SuperAdminContentExtra?Res=' . $editExt->toRes)->with('success', 'Das zusätzliche Produkt wurde erfolgreich modifiziert');
        } else {
            return redirect('/produktet5Extra?Res=' . $editExt->toRes)->with('success', 'Das zusätzliche Produkt wurde erfolgreich modifiziert');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $req)
    {

        $theEx = ekstra::find($id);

        // Delete it from the products too 
        foreach (Produktet::where([['toRes', $theEx->toRes], ['extPro', '!=', NULL]])->get() as $theProd) {
            $newProExtras = "";
            $hasThisEx = false;
            foreach (explode('--0--', $theProd->extPro) as $oneEx) {
                $oneEx2D = explode('||', $oneEx);
                if ($oneEx2D[0] != $theEx->id && $oneEx != "") {
                    if ($newProExtras == "") {
                        $newProExtras = $oneEx2D[0] . '||' . $oneEx2D[1] . '||' . $oneEx2D[2];
                    } else {
                        $newProExtras .= '--0--' . $oneEx2D[0] . '||' . $oneEx2D[1] . '||' . $oneEx2D[2];
                    }
                    $hasThisEx = true;
                }
            }
            if ($hasThisEx) {
                $theProd->extPro = $newProExtras;
                $theProd->save();
            }
        }

        // Delete this ekstra 
        $theEx->delete();

        if (isset($req->userSA)) {
            return redirect('/SuperAdminContentExtra?Res=' . $req->input('page'))->with('success', 'Das zusätzliche Produkt wurde erfolgreich gelöscht');
        } else {
            return redirect('/produktet5Extra?Res=' . $req->input('page'))->with('success', 'Das zusätzliche Produkt wurde erfolgreich gelöscht');
        }
    }



    public function setAllExtToAllProdsOnCat(Request $req){
        // catInd
        $extrToregister = "0";
        foreach(ekstra::where([['toRes',Auth::user()->sFor],['toCat',$req->catInd]])->get() as $extraOne){
            if($extrToregister == "0"){
                $extrToregister = $extraOne->id.'||'.$extraOne->emri.'||'.number_format($extraOne->qmimi, 2, '.', '');
            }else{
                $extrToregister .= '--0--'.$extraOne->id.'||'.$extraOne->emri.'||'.number_format($extraOne->qmimi, 2, '.', '');
            }
        }
        if($extrToregister != "0"){
            foreach(Produktet::where([['toRes',Auth::user()->sFor],['kategoria',$req->catInd]])->get() as $prodOne){
                $prodOne->extPro = $extrToregister;
                $prodOne->save();
            }
        }
    }

    public function setAllExtToThisProdsOnCat(Request $req){
        // prodInd: prodId,
        // catInd: catId,

        $extrToregister = "0";
        foreach(ekstra::where([['toRes',Auth::user()->sFor],['toCat',$req->catInd]])->get() as $extraOne){
            if($extrToregister == "0"){
                $extrToregister = $extraOne->id.'||'.$extraOne->emri.'||'.number_format($extraOne->qmimi, 2, '.', '');
            }else{
                $extrToregister .= '--0--'.$extraOne->id.'||'.$extraOne->emri.'||'.number_format($extraOne->qmimi, 2, '.', '');
            }
        }
        if($extrToregister != "0"){
            $theProd = Produktet::find($req->prodInd);
            $theProd->extPro = $extrToregister;
            $theProd->save();
        }
    }
}
