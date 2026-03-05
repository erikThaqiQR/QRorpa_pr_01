<?php

namespace App\Http\Controllers;

use PDF;
use QRCode;
use App\Orders;
use App\OrdersPassive;
use App\Restorant;
use Carbon\Carbon;
use App\rechnungClient;
use Illuminate\Http\Request;
use App\rechnungClientToBills;
use App\rechnungClientForMonth;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MonthlyRechnungForClController extends Controller{


    public function checkBilSendM(Request $req){
        if(isset($_GET['hs']) && $_GET['hs'] == 'H54r5fHvGFf4347HjHfd456GFvVCdRF56778544FfcCvGjNbVcSDHjOeqWeSXcY789D6d67D878d765D677d6D65D6Dd56DdtgHGjhbVBMmM'){
            foreach(Restorant::all() as $resOne){
                $dtNow = Carbon::now();
                if($dtNow->day == 1 || $dtNow->day == 2){
                    $theCls = rechnungClient::where('toRes',$resOne->id)->get();
                    if($theCls->count() > 0){
                        if($dtNow->month == 1){ $billYear = $dtNow->year - 1; $billmonth = 12; }
                        else{ $billYear = $dtNow->year; $billmonth = $dtNow->month - 1; }

                        $countClScc = 0;
                        $clientsActive = array();
                        $clBills = array();
                        $clBillsOrders = array();

                        foreach($theCls as $clOne){
                            $billsAcum = '';
                            $newMoBiForCl = new rechnungClientForMonth();
                            $newMoBiForCl->forClient = $clOne->id;
                            $newMoBiForCl->toRes = $clOne->toRes;
                            $ordsId = array();
                            if(rechnungClientForMonth::where([['forClient',$clOne->id],['forYear',$billYear],['forMonth',$billmonth]])->first() == Null){
                                $clBills = rechnungClientToBills::where('clientId',$clOne->id)->whereMonth('created_at', sprintf("%02d", $billmonth))->get();
                                if($clBills->count() > 0){

                                    $totPrice = number_format(0, 4, '.', '');
                                    // iterate through bills
                                    foreach($clBills as $billOne){
                                        $theOr = OrdersPassive::findOrFail($billOne->orderId);
                                        if($theOr->inCashDiscount > 0){
                                            $totPrice += number_format($theOr->shuma-$theOr->inCashDiscount - $theOr->dicsountGcAmnt, 4, '.', '');
                                        }else if($theOr->inPercentageDiscount > 0){
                                            $totPrice += number_format($theOr->shuma-($theOr->shuma*($theOr->inPercentageDiscount*0.01)) - $theOr->dicsountGcAmnt, 4, '.', '');
                                        }else{
                                            $totPrice += number_format($theOr->shuma - $theOr->dicsountGcAmnt, 4, '.', '');
                                        }
                                        array_push($ordsId,$theOr->id);
                                    
                                        $billsAcum .= $billOne->billId.'|||';
                                    }
                                    $countClScc++;

                                    // create and save the eBanking QRcode
                                    $moBillRecords = rechnungClientForMonth::where('forClient',$clOne->id)->get()->count() + 1;
                                    $billNr = str_pad($moBillRecords, 10, '0', STR_PAD_LEFT);
                                    
                                    $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
                                    shuffle($word);
                                    $name = substr(implode($word), 0, 25).'moRechToCl'.$moBillRecords;
                                    $file = "storage/ebankqrcodeMnth/".$name.".png";

                                    $adr2D = explode(',',$resOne->adresa);
                                    $ad2 = '---';
                                    if(isset($adr2D[1])){
                                        $ad2 = $adr2D[1];
                                    }
                                    if(isset($adr2D[2])){
                                        $ad2 = $adr2D[1].','.$adr2D[2];
                                    }
                                    
                                    $newQrcode = QRCode::text('SPC
0200
1
'.$resOne->resBankId.'
K
'.$resOne->emri.'
'.$adr2D[0].'
'.$ad2.'


CH







'.number_format($totPrice, 2, '.', '').'
CHF
K
'.$clOne->firmaName.'
'.$clOne->street.'
'.$clOne->plzort.' '.$clOne->land.'


CH
NON

'.$billNr.'
EPD
')
                                    ->setSize(64)
                                    ->setMargin(0)
                                    ->setOutfile($file)
                                    ->png();

                                    $img1 = Image::make('storage/ebankqrcodeMnth/'.$name.'.png');
                                    $img1->insert('storage/ebankqrcode/eBPIcon.png');
                                    $img1->save('storage/ebankqrcodeMnth/'.$name.'.png');
                                    //--------------------------------------------------------------------------------------------------------

                                    // Send the emails & save the bills in PDF fromat

                                    $newMoBiForCl->	forMonth = $billmonth;
                                    $newMoBiForCl->	forYear = $billYear;
                                    $newMoBiForCl->	eBankQrCode = $name.'.png';
                                    $newMoBiForCl->	billsAccumulated = $billsAcum;
                                    $newMoBiForCl->	pdfBill = 'empty';
                                    $newMoBiForCl->save();

                                    view()->share('ordersId', $ordsId);
                                    view()->share('theClient' , $clOne);
                                    view()->share('eBankQRCode' , $name.'.png');
                                    view()->share('billMonthSnd' , $billmonth);
                                    view()->share('billYearSnd' , $billYear);
                                    view()->share('rechMnthId' , $newMoBiForCl->id);
                                    view()->share('resID' , $resOne->id);
                                    $pdf = PDF::loadView('monthlyRechnungPDFCL.monthlyRechnungClFirst')->setPaper('a4', 'potrait');
                                    $docName = $resOne->emri.'_'.$clOne->id.'_PreRechnung_'.$billmonth.'_'.$billYear.'.pdf';
                                    $pdf->save('storage/rechnungMonthlyFirst/'.$docName);

                                    $pdf2 = PDF::loadView('monthlyRechnungPDFCL.monthlyRechnungClFinal')->setPaper('a4', 'potrait');
                                    $docName2 = $resOne->emri.'_'.$clOne->id.'_Rechnung_'.$billmonth.'_'.$billYear.'.pdf';
                                    $pdf2->save('storage/rechnungMonthlyFinal/'.$docName2);

                                    $newMoBiForCl->	pdfBill = $docName2;
                                    $newMoBiForCl->	status = 1;
                                    $newMoBiForCl->save();
                                    
                                    // C:\Users\erikt\Desktop\QRORPA _ INFOMANIAK\fakturatMujoreAufRechnung.txt (1)

                                    $to_name = $clOne->name.' '.$clOne->lastname;
                                    $to_email = $clOne->email;
                                    $fromRes = Restorant::find(Auth::user()->sFor);
                                    $fromResName = $fromRes->emri;
                                    $moRechId = $newMoBiForCl->id;
                                    $data = array('sendName'=>$to_name, "sendEmail" => $to_email, "fromResName" => $fromRes->emri, "daysToPay" => $clOne->daysToPay, "moReId" => $moRechId);
                                    Mail::send('emails.rechnungMnthForCl', $data , function($message)use ($to_email, $to_name ,$moRechId, $billmonth, $billYear, $fromResName, $pdf2){
                                        $message->from('noreply@qrorpa.ch','Qrorpa');
                                        $message->to($to_email, $to_name);
                                        $message->subject('Monatliche Rechnung "'.$billmonth.'.'.$billYear.'" ab '.$fromResName.' von QRorpa');
                                        $message->attachData($pdf2->output(), 'monatlicheRechnung_QRorpa_'.$billmonth.'_'.$billYear.'_#'.$moRechId.'.pdf', [
                                            'mime' => 'application/pdf',
                                        ]);
                                    });
                                    //--------------------------------------------------------------------------------------------------------
                                }
                            }
                        } // clients loop

                        // if($countClScc > 0){
                            // return 'successSend';
                        // }else{
                            // return 'sendFor: '.$countClScc.' clients';
                        // }
                    }else{ /* return 'noClients'; */ } // check number of clients
                }else{ /* return 'notThe01Date'; */ } // check date 1 & 2
            } // end Foreach
        } // hash check
    } 













    public function getClPdfBills(Request $req){
        $filteredData = rechnungClientForMonth::where([['forClient',$req->clientId],['status','1']])->get();
        return json_encode( $filteredData );
    }

}
