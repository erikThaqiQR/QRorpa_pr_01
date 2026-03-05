<?php

namespace App\Http\Controllers;

use App\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Restorant;
use App\Produktet;
use App\resdemoalfa;
use QRCode;
use PDF;
use DataTables;
use Auth;


class ResDemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sa/superAdminIndex');
    }

    public function indexCRM(){return view('sa/crmQRorpa');
    }



    public function indexCRM2(Request $req){
        if($req->searchWord == ''){
            $filteredData = resdemoalfa::all()->sortBy('forThis');
        }else  if($req->searchWord == 'filterNr'){
            $filteredData = resdemoalfa::all()->sortBy('forThis');
        }else  if($req->searchWord == 'filterName'){
            $filteredData = resdemoalfa::all()->sortBy('emri');
        }else  if($req->searchWord == 'filterAddress'){
            $filteredData = resdemoalfa::all()->sortBy('adresa');
        }else  if($req->searchWord == 'filterPLZ'){
            $filteredData = resdemoalfa::all()->sortBy('plz');
        }else  if($req->searchWord == 'filterORT'){
            $filteredData = resdemoalfa::all()->sortBy('ort');
            
        }else{
            if(Auth::user()->email == 'callagent@qrorpa.ch' || Auth::user()->email == 'callagent01@qrorpa.ch' || Auth::user()->email == 'callagent02@qrorpa.ch' || Auth::user()->email == 'callagent03@qrorpa.ch'
            || Auth::user()->email == 'callagent04@qrorpa.ch' || Auth::user()->email == 'callagent05@qrorpa.ch' || Auth::user()->email == 'callagent06@qrorpa.ch' || Auth::user()->email == 'callagent07@qrorpa.ch'
            || Auth::user()->email == 'callagent08@qrorpa.ch' || Auth::user()->email == 'callagent09@qrorpa.ch' || Auth::user()->email == 'callagent10@qrorpa.ch'){
                $filteredData = resdemoalfa::where([['emri', 'like', '%'.$req->searchWord.'%'],['isForCa',Auth::user()->email]])
                ->orWhere([['adresa', 'like', '%'.$req->searchWord.'%'],['isForCa',Auth::user()->email]])
                ->orWhere([['plz', 'like', '%'.$req->searchWord.'%'],['isForCa',Auth::user()->email]])
                ->orWhere([['ort', 'like', '%'.$req->searchWord.'%'],['isForCa',Auth::user()->email]])->get()->sortByDesc('forThis');
            }else{
                $filteredData = resdemoalfa::where('emri', 'like', '%'.$req->searchWord.'%')->orWhere('adresa', 'like', '%'.$req->searchWord.'%')
                ->orWhere('plz', 'like', '%'.$req->searchWord.'%')
                ->orWhere('ort', 'like', '%'.$req->searchWord.'%')->get()->sortByDesc('forThis');
            }
        }

        return view('sa/crmQRorpa2')->with('resDemoSearchResult', $filteredData);
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
        $saveProduktet = '';
        $extr = '';
        $types = '';

        foreach(Produktet::where('toRes', '=', $request->modelRes)->get() as $prods){

            foreach(explode('--0--',$prods->extPro) as $prodsExt){
                if(!empty($prodsExt)){
                    if($extr == ''){
                        $extr.= explode('||', $prodsExt)[0];
                    }else{
                        $extr.= '||'.explode('||', $prodsExt)[0];
                    }
                }
            }
            foreach(explode('--0--',$prods->type) as $prodsType){
                if(!empty($prodsType)){
                    if($types == ''){
                        $types.= explode('||', $prodsType)[0];
                    }else{
                        $types.= '||'.explode('||', $prodsType)[0];
                    }
                }
            }

            if($saveProduktet == ''){
                $saveProduktet .= $prods->emri.'-0-'.$prods->pershkrimi.'-0-'.$prods->kategoria.'-0-'.$prods->qmimi.'-0-'
                .$extr.'-0-'.$types;
            }else{
                $saveProduktet .= '--00--'.$prods->emri.'-0-'.$prods->pershkrimi.'-0-'.$prods->kategoria.'-0-'.$prods->qmimi.'-0-'
                .$extr.'-0-'.$types;
            }
            $extr = '';
            $types = '';
        }

        $saved = 0;

        foreach(DB::table('resdemobeta')->get() as $resDemoBeta){
            if(!empty($resDemoBeta->addres_d)){

                if($resDemoBeta->name_d != 'Name'){
                    $saved++;

                    if($resDemoBeta->email_d == ''){
                        $saveThisEm = 'empty';
                    }else{
                        $saveThisEm = $resDemoBeta->email_d;
                    }
                    if($resDemoBeta->nrTel2_d == ''){
                        $saveThisNrTel2 = 'empty';
                    }else{
                        $saveThisNrTel2 = $resDemoBeta->nrTel2_d;
                    }
                    if($resDemoBeta->nrTel_d == ''){
                        $saveThisNrTel = 'empty';
                    }else{
                        $saveThisNrTel = $resDemoBeta->nrTel_d;
                    }
                    if($resDemoBeta->webP_d == ''){
                        $saveThisWeb = 'empty';
                    }else{
                        $saveThisWeb = $resDemoBeta->webP_d;
                    }

                    $saveOne = new resdemoalfa;
                    $saveOne->toRes = $request->modelRes;
                    $saveOne->forThis = $saved;
                    $saveOne->emri = $resDemoBeta->name_d;
                    $saveOne->adresa = $resDemoBeta->addres_d;
                    $saveOne->plz = $resDemoBeta->plz_d;
                    $saveOne->ort = $resDemoBeta->ort_d;
                    $saveOne->produktet = $saveProduktet;
                    $saveOne->email = $saveThisEm;
                    $saveOne->nrTel =  $saveThisNrTel;
                    $saveOne->nrTel2 = $saveThisNrTel2;
                    $saveOne->webP = $saveThisWeb;
                    
                    $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
                    shuffle($word);
                    $name = time().'_'.substr(implode($word), 0, 20)
                    .'Table'.$request->input('tableNr').'Res'.$request->input('restaurant');

                    $file = "storage/qrcodeDemo/".$name.".png";

                    $newQrcode = QRCode::URL('https://www.qrorpa.ch/?t=1&Res='.$saved.'&demo=1')
                        ->setSize(32)
                        ->setMargin(0)
                        ->setOutfile($file)
                        ->png();

                    $saveOne->qrcodeT = $name.'.png';

                    $saveOne->save(); 
                }//end if emri != Name
            }//end if adresa !empty 
        }

        $modelRes = $request->modelRes;
        return redirect()->route ('resDemo.indexCRM')->with('success',$saved.' Demo restaurants are imported successfully');
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
    public function destroyAll()
    {
        $delC = count(resdemoalfa::all());
        foreach(resdemoalfa::all() as $resDemo){
            $resDemo->delete();
        }
        return redirect()->route ('resDemo.indexCRM')->with('success',$delC.' demo restaurants are deleted successfully');
    }

    public function generatePDF2($id){
        $details = ['title' => 'RestaurantDemo'];
        $item = resdemoalfa::find($id);

        $item->status = 1;
        $item->datePrint = date("d-m-Y H:i");
        $item->save();
        
        view()->share('items', $item);
        $pdf = PDF::loadView('pdfviewD');
        return $pdf->download('ResDemo_'.$item->emri.'.pdf');



    }
   
    public function generatePDF2All(Request $req){
        $count = count(explode('|-|', $req->resDemos));

        foreach(explode('|-|', $req->resDemos) as $chThis){
            $DownDone = resdemoalfa::find($chThis);
            $DownDone->status = 1;
            $DownDone->datePrint = date("d-m-Y H:i");
            $DownDone->save();
        }
         
        view()->share('items', $req->resDemos);
        $pdf = PDF::loadView('pdfviewD2');
        return $pdf->download('ResDemo_'.$count.'.pdf');
        
    }

    public function generatePDFRestorantOrders(Request $req){
        // pdfRestorantOrders.blade
        $details = ['title' => 'Restaurant Orders'];
        $item = Orders::where('Restaurant',$req->id)->get()->sortBy('created_at');

        // $item->status = 1;
        // $item->datePrint = date("d-m-Y H:i");
        // $item->save();
        
        view()->share('items', $item);
        $pdf = PDF::loadView('pdfRestorantOrders');
        return $pdf->download('ResOrders_'.Restorant::find($req->id)->emri.'.pdf');
    }














    public function destroyOne(Request $request){
        resdemoalfa::find($request->delId)->delete();
    }



    public function saveNewComCRM(Request $req){
        $newCOMRD = resdemoalfa::find($req->id);
        $newCOMRD->commentRD = $req->newC;
        $newCOMRD->save();

    }


    public function emailDateSet(Request $req){
        $newCOMRD = resdemoalfa::find($req->id);
        $newCOMRD->emailDate = date("d.m.Y H:i");
        $newCOMRD->save();
    }
    public function nrTelDateSet(Request $req){
        $newCOMRD = resdemoalfa::find($req->id);
        $newCOMRD->nrTelDate = date("d.m.Y H:i");
        $newCOMRD->save();
    }
    public function nrTelDateSet2(Request $req){
        $newCOMRD = resdemoalfa::find($req->id);
        $newCOMRD->nrTel2Date = date("d.m.Y H:i");
        $newCOMRD->save();
    }
    public function sendWebDate(Request $req){
        $newCOMRD = resdemoalfa::find($req->id);
        $newCOMRD->webPDate = date("d.m.Y H:i");
        $newCOMRD->save();
    }



    public function emailSaveNewCRM(Request $req){
        $newCOMRD = resdemoalfa::find($req->id);
        $newCOMRD->email = $req->newEmail;
        $newCOMRD->save();
    }

    public function nrTelSaveNewCRM(Request $req){
        $newCOMRD = resdemoalfa::find($req->id);
        $newCOMRD->nrTel = $req->newNrTel;
        $newCOMRD->save();
    }
    public function nrTelSaveNewCRM2(Request $req){
        $newCOMRD = resdemoalfa::find($req->id);
        $newCOMRD->nrTel2 = $req->newNrTel;
        $newCOMRD->save();
    }
    public function webSaveNewCRM2(Request $req){
        $newCOMRD = resdemoalfa::find($req->id);
        $newCOMRD->webP = $req->newWeb;
        $newCOMRD->save();
    }



















    public function changeIsForCA(Request $req){
        $resDisCA = resdemoalfa::find($req->id);
        $resDisCA->isForCA = $req->val;
        $resDisCA->save();
    }

    public function changeClAc(Request $req){
        $resDisCA = resdemoalfa::find($req->id);
        if($resDisCA->clAccept == 0){
            $resDisCA->clAccept = 1;
        }else{
            $resDisCA->clAccept = 0;
        }
        $resDisCA->save();
    }


    public function saveNameBoos(Request $req){
        $resDName = resdemoalfa::find($req->id);
        $resDName->boosName = $req->val;
        $resDName->save();
    }

    public function saveSurnameBoos(Request $req){
        $resDSurname = resdemoalfa::find($req->id);
        $resDSurname->boosSurname = $req->val;
        $resDSurname->save();
    }
}
