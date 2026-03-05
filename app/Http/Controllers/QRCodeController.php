<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QRCode;
use App\TableQrcode;
use App\TableQrcodeTA;
// use Endroid\QrCode\QrCode;
use Intervention\Image\ImageManagerStatic as Image;

class QRCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('sa/superAdminIndex');
    }
    public function indexC(){
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




















    public function store(Request $request){

        $countAdd = 0;
        $countFail = 0;
        foreach(explode(',',$request->input('tableNr')) as $periodOne){
            if (strpos($periodOne, '-') !== false) {
                $step = explode('-',$periodOne)[0];
                while($step <= explode('-',$periodOne)[1]){
                        
                    if(TableQrcode::where('tableNr', '=', $step)->where('Restaurant', '=', $request->input('restaurant'))->first() != Null){
                        $countFail++;

                        ++$step;
                    }else{

                        $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
                        shuffle($word);
                        $name = substr(implode($word), 0, 45).'Table'.$step.'Res'.$request->input('restaurant');
                        $file = "storage/qrcode/".$name.".png";

                        $word2 = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'));
                        shuffle($word2);
                        $hash = substr(implode($word2), 0, 128);


                        $newQrcode = QRCode::URL('https://qrorpa.ch/?Res='.$request->input('restaurant').'&t='.$step)
                        ->setSize(32)
                        ->setMargin(0)
                        ->setOutfile($file)
                        ->png();

                        $tQrcode = new TableQrcode;

                        $img1 = Image::make('storage/qrcode/'.$name.'.png');
                        $img1->insert('storage/qrcodeNr/number'.$step.'.png');
                        $img1->save('storage/qrcode/'.$name.'.png');
                        $tQrcode->path = $name.'.png';
                        $tQrcode->tableNr = $step;
                        $tQrcode->Restaurant = $request->input('restaurant');
                        $tQrcode->hash = $hash;
                        $tQrcode->save();

                        $countAdd++;

                        ++$step;
                    }
                }

            }else{
    
                $checkIfExs = TableQrcode::where('tableNr', '=', $periodOne)->
                                        where('Restaurant', '=', $request->input('restaurant'))->get();
                if(count($checkIfExs) != 0){
                    // return redirect()->route('table.index')->with('error','This table is already registred!');
                    $countFail++;
                }else{

                    $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
                    shuffle($word);
                    $name = substr(implode($word), 0, 45).'Table'.$periodOne.'Res'.$request->input('restaurant');

                    $file = "storage/qrcode/".$name.".png";
                    
                    $word2 = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'));
                    shuffle($word2);
                    
                    $hash = substr(implode($word2), 0, 128);
                

                    $newQrcode = QRCode::URL('https://qrorpa.ch/?Res='.$request->input('restaurant').'&t='.$periodOne)
                        ->setSize(32)
                        ->setMargin(0)
                        ->setOutfile($file)
                        ->png();

                        $tQrcode = new TableQrcode;

                        $img1 = Image::make('storage/qrcode/'.$name.'.png');
                        $img1->insert('storage/qrcodeNr/number'.$periodOne.'.png');
                        $img1->save('storage/qrcode/'.$name.'.png');
                        $tQrcode->path = $name.'.png';
                        $tQrcode->tableNr = $periodOne;
                        $tQrcode->Restaurant = $request->input('restaurant');
                        $tQrcode->hash = $hash;
                        $tQrcode->save();

                        $countAdd++;
                }
            }
        }


        if($request->redirectTo=='none'){
            return redirect()->route('table.index')->with('success', $countAdd.' Tables Added / '.$countFail.' Failed');
        }else{
            if(isset($request->userSA)){
                return redirect()->route('homeConRegUserTable', ['Res' => $request->redirectTo])->with('success', $countAdd.' Tables Added / '.$countFail.' Failed');
            }else{
                return redirect()->route('table.index', ['Res' => $request->redirectTo])->with('success', $countAdd.' Tables Added / '.$countFail.' Failed');
            }
        } 
    }









    public function storeTA(Request $req){

        $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
        shuffle($word);
        $name = substr(implode($word), 0, 45).'Table500Res'.$req->redirectTo;
        $file = "storage/qrcode/".$name.".png";
        $word2 = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'));
        shuffle($word2);
        $hash = substr(implode($word2), 0, 128);
        $newQrcode = QRCode::URL('https://qrorpa.ch/?Res='.$req->redirectTo.'&t=500')
                        ->setSize(32)
                        ->setMargin(0)
                        ->setOutfile($file)
                        ->png();

        $checkIfExs = TableQrcodeTA::where('tableNr', '500')->where('Restaurant', $req->redirectTo)->first();

        if($checkIfExs != Null){
            $checkIfExs->path = $name.'.png';
            $checkIfExs->hash = $hash;
            $checkIfExs->save();

            $ansReturn = 'We have successfuly changed the Takeaways QR-code';
        }else{
            $tQrcode = new TableQrcodeTA;
            $tQrcode->path = $name.'.png';
            $tQrcode->tableNr = 500;
            $tQrcode->Restaurant = $req->redirectTo;
            $tQrcode->hash = $hash;
            $tQrcode->save();

            $ansReturn = 'We have successfuly created the Takeaways QR-code';
        }


        if($req->redirectTo=='none'){
            return redirect()->route('table.index')->with('success', $ansReturn);
        }else{
            if(isset($req->userSA)){
                return redirect()->route('homeConRegUserTable', ['Res' => $req->redirectTo])->with('success', $ansReturn);
            }else{
                return redirect()->route('table.index', ['Res' => $req->redirectTo])->with('success', $ansReturn);
            }
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
    public function destroy(Request $req){
        if($req->tableN == 500){
        TableQrcodeTA::find($req->id)->delete();
        }else{
        TableQrcode::find($req->id)->delete();
        }
    }




    public function tableCSave(Request $req){
        $theTable = TableQrcode::where([['Restaurant',$req->res],['tableNr',$req->table]])->first();
        $theTable->capacity = $req->newVal;
        $theTable->save();

        $ret = $req->table.'||'.$req->newVal;
        return $ret;
    }
    public function tableRezStatus(Request $req){
        $theTable = TableQrcode::where([['Restaurant',$req->res],['tableNr',$req->table]])->first();
        if($theTable->forRez == 1){
            $theTable->forRez = 0;
            $theTable->save();
        }else{
            $theTable->forRez = 1;
            $theTable->save();
        }
        $ret = $req->table.'||'.$theTable->forRez;
        return $ret;
    }














    public function tableStatusSet(Request $req){
        $theTable = TableQrcode::find($req->id);
        $theTable->kaTab = $req->val;
        $theTable->save();
    }
}
