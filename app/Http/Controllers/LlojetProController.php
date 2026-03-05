<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LlojetPro;
use App\Produktet;
use phpDocumentor\Reflection\Types\Null_;

class LlojetProController extends Controller
{
    public function index()
    {
        $llProduktet = LlojetPro::all();
        return view('sa/llojetPro')->with('llPro', $llProduktet);
    }

    public function store(Request $request)
    {

        //     $this->validate($request, [
        //         'emri' => 'required',
        //         'vlera' => 'required',
        //    ]);
        if (empty($request->input('emri')) || empty($request->input('vlera'))) {
            return redirect('/produktet5Type?Res=' . $request->input('page'))->with('error', 'Empty fields');
        }

        $llPro = new LlojetPro;

        $llPro->emri = $request->input('emri');
        $llPro->kategoria = $request->input('toCat');
        $llPro->vlera = $request->input('vlera');
        $llPro->toRes = $request->input('restaurant');
        $llPro->save();

        // $llProduktet = LlojetPro::all();

        if (isset($request->userSA)) {
            return redirect('/SuperAdminContentType?Res=' . $request->input('page'));
        } else {
            return redirect('/produktet5Type?Res=' . $request->input('page'));
        }
    }
    public function storeAdminP(Request $request){
        if(isset($request->isWaiter)){
            if (empty($request->input('emri')) || empty($request->input('vlera'))) {
                return redirect('/adminWoContentMngWaiter')->with('error', 'Leerfelder');
            }
            if (str_contains($request->input('emri'), '&') || str_contains($request->input('emri'), '+') || str_contains($request->input('emri'), '|') || str_contains($request->input('emri'), '||')){
                return redirect('/adminWoContentMngWaiter')->with('error','Dieser Produkttypname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else if(isset($request->isAccountant)){
            if (empty($request->input('emri')) || empty($request->input('vlera'))) {
                return redirect('/AccountantProducts')->with('error', 'Leerfelder');
            }
            if (str_contains($request->input('emri'), '&') || str_contains($request->input('emri'), '+') || str_contains($request->input('emri'), '|') || str_contains($request->input('emri'), '||')){
                return redirect('/AccountantProducts')->with('error','Dieser Produkttypname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }else{
            if (empty($request->input('emri')) || empty($request->input('vlera'))) {
                return redirect('/dashboardContentMng')->with('error', 'Leerfelder');
            }
            if (str_contains($request->input('emri'), '&') || str_contains($request->input('emri'), '+') || str_contains($request->input('emri'), '|') || str_contains($request->input('emri'), '||')){
                return redirect('/dashboardContentMng')->with('error','Dieser Produkttypname enthält eingeschränkte Zeichen (& , + , |  , ||)');
            }
        }
        $llPro = new LlojetPro;

        $llPro->emri = $request->input('emri');
        $llPro->kategoria = $request->input('toCat');
        $llPro->vlera = $request->input('vlera');
        $llPro->toRes = $request->input('restaurant');
        $llPro->save();

        if(isset($request->isWaiter)){
            return redirect('/adminWoContentMngWaiter')->with('success', 'Typ erfolgreich hinzugefügt');
        }else if(isset($request->isAccountant)){
            return redirect('/AccountantProducts')->with('success', 'Typ erfolgreich hinzugefügt');
        }else{
            return redirect('/dashboardContentMng')->with('success', 'Typ erfolgreich hinzugefügt');
        }
    }

    public function storeAdminPPro(Request $req){
        // emri: 
        // cate: 
        // vlera: 
        // res: 

        if($req->emri == "" || $req->vlera == ""){
            return 'Bitte unbedingt die notwendigen Daten schreiben!';
        }

        $llPro = new LlojetPro;

        $llPro->emri = $req->emri;
        $llPro->kategoria = $req->cate;
        $llPro->vlera = $req->vlera;
        $llPro->toRes = $req->res;
        $llPro->save();

        return 'success';

    }











    public function update(Request $req, $id)
    {
        //     $this->validate($req, [
        //         'emri' => 'required',
        //         'vlera' => 'required',
        //    ]);

        $llProE = LlojetPro::find($id);

        if (empty($req->input('emri')) || empty($req->input('vlera'))) {
            return redirect('/produktet5?goT=' . $llProE->toRes)->with('error', 'Empty fields');
        }

        $llProE->emri = $req->emri;
        $llProE->kategoria = $req->toCat;
        $llProE->vlera = $req->vlera;
        $llProE->save();

        foreach (Produktet::where([['toRes', $llProE->toRes], ['type', '!=', NULL]])->get() as $theP) {
            $newTypes = '';
            foreach (explode('--0--', $theP->type) as $oneTy) {
                if ($oneTy != '') {
                    $oneTy2d = explode('||', $oneTy);

                    if ($oneTy2d[0] == $llProE->id) {
                        if ($newTypes == '') {
                            $newTypes = $oneTy2d[0] . '||' . $req->emri . '||' . $req->vlera;
                        } else {
                            $newTypes .= '--0--' . $oneTy2d[0] . '||' . $req->emri . '||' . $req->vlera;
                        }
                    } else {
                        if ($newTypes == '') {
                            $newTypes = $oneTy2d[0] . '||' . $oneTy2d[1] . '||' . $oneTy2d[2];
                        } else {
                            $newTypes .= '--0--' . $oneTy2d[0] . '||' . $oneTy2d[1] . '||' . $oneTy2d[2];
                        }
                    }
                }
            }

            $theP->type = $newTypes;
            $theP->save();
        }

        if (isset($req->userSA)) {
            return redirect('/SuperAdminContentType?Res=' . $llProE->toRes);
        } else {
            return redirect('/produktet5Type?Res=' . $llProE->toRes);
        }
    }





    public function destroy(Request $req, $id)
    {
        $llProDel =  LlojetPro::find($id);

        // Delete it from the products too 
        foreach (Produktet::where([['toRes', $llProDel->toRes], ['type', '!=', NULL]])->get() as $theProd) {
            $newProType = "";
            $hasThisType = false;
            foreach (explode('--0--', $theProd->type) as $oneTy) {
                $oneTy2D = explode('||', $oneTy);
                if ($oneTy2D[0] != $llProDel->id && $oneTy != "") {
                    if ($newProType == "") {
                        $newProType = $oneTy2D[0] . '||' . $oneTy2D[1] . '||' . $oneTy2D[2];
                    } else {
                        $newProType .= '--0--' . $oneTy2D[0] . '||' . $oneTy2D[1] . '||' . $oneTy2D[2];
                    }
                    $hasThisType = true;
                }
            }
            if ($hasThisType) {
                if ($newProType == "") {
                    $newProType = NULL;
                }
                $theProd->type = $newProType;
                $theProd->save();
            }
        }

        // Delete the type 
        $llProDel->delete();

        if (isset($req->userSA)) {
            return redirect('/SuperAdminContentType?Res=' . $llProDel->toRes);
        } else {
            return redirect('/produktet5Type?Res=' . $llProDel->toRes);
        }
    }
}
