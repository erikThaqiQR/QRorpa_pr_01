<?php

namespace App\Http\Controllers;

use PDF;
use App\Orders;
use Carbon\Carbon;
use App\OrdersPassive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TrackMyOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('trackMyOrder');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        //
    }


    public function sendReceiptToEmail(Request $req){
        // email: sEmail,
        // orderId: oId,

        // Send the ThankYou email ...
        $theCon = OrdersPassive::findOrFail($req->orderId);
        $to_name = explode('@',$req->email)[0];
        $to_email = str_replace(' ', '', $req->email); 
        $data = array('name'=>$to_name);

        view()->share('items', $theCon);

        $nrOfOrders19 = 0;
        $nrOfOrders19P = 0;
        $totExtra = 0;
        foreach(explode('---8---', $theCon->porosia) as $onOr){ $or2D = explode('-8-',$onOr);
            if(strlen($onOr[5]) > 19){$nrOfOrders19P++;
            }else{$nrOfOrders19++;}
            if($or2D[2] != 'empty' && $or2D[2] != ''){
                if(str_contains($or2D[2], '--0--')){ $nrOfExt = count(explode('--0--',$or2D[2])); $totExtra = $totExtra + $nrOfExt;
                }else{ $totExtra++; }
            }
        }
        $customPaper = array(0,0,340.16,990+($nrOfOrders19P*32)+($nrOfOrders19*21)+($totExtra*12));
        $pdf = PDF::loadView('adminInvoice')->setPaper($customPaper, 'potrait');
        
        Mail::send('adminInvoice', $data, function($message) use ($to_name, $to_email, $pdf, $theCon) {
            $message->to($to_email, $to_name)
                         ->subject('Vertrag');
            $message->from('noreply@qrorpa.ch','Qrorpa');
            $message->attachData($pdf->output(), 'Vertrag-'.$theCon->id.'-QRorpa-'.$theCon->dateOnTheEnd.'.pdf', [
                'mime' => 'application/pdf',
            ]);
        });
    }



    public function getOrderByCode(Request $req){
        $filteredOrder = Orders::whereDate('created_at', '=', Carbon::today())->where('shifra',$req->orCode)
                                ->first();
        if($filteredOrder != NULL){
            return json_encode( $filteredOrder );
        }else{
            return json_encode( 'none' );
        }
    }
}
