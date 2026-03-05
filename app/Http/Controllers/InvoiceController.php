<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\giftCard;
use App\Takeaway;
use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use App\pdfReportIns;
use App\OrdersPassive;
use App\pdfResProdCats;
use App\RestaurantOffer;
use Illuminate\Http\Request;
use App\billsExpensesRecordRes;
use App\ordersInNotCatInReport2;
use Illuminate\Support\Facades\Auth;

use App\Exports\PhpXlsxGenerator as phpxlsx;

class InvoiceController extends Controller
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
    public function SAindex()
    {

        return view('sa/superAdminIndex');
       
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function getData()
    {
        $restaurantOffers  = RestaurantOffer::with('user')->orderBy('created_at', 'DESC');
          

            return Datatables::of($restaurantOffers)
            // New "hidden" column
->addColumn('status_color', ' ')
->addColumn('user_status_color', ' ')
             ->addColumn('action', function($offer){
                return ' <table border="0" cellspacing="5" cellpadding="5" id="actionButtons">
                        <tr>
                        <td>
                         <a href="" id="'.$offer->id.'" target="_blank" class="btn btn-secondary btn-sm tooltip2 edit" style="background-color: #0095ff;" data-toggle="modal" data-target="#editContract"><i class="fa fa-pencil" aria-hidden="true"></i><span class="tooltiptext">Edit</span></a>
                <a href="'.route("emails.contractPdf", $offer->id).'" target="_blank" class="btn btn-secondary btn-sm tooltip2"><i class="fa fa-print" aria-hidden="true"></i> <span class="tooltiptext">Print</span></a>
                <a href="'.route("sendEmail", $offer->id).'" target="_blank" class="btn btn-secondary btn-sm tooltip2" style="background-color: #58984c;"><i class="fa fa-envelope-o" aria-hidden="true"></i><span class="tooltiptext">E-Mail senden</span></a>
                      
                          </td>                          
                        </tr>
                        </table>';
            })
             ->editColumn('status_color', function ($row) {
                    return $row->contractStatus && RestaurantOffer::STATUS_COLOR[$row->contractStatus] ? RestaurantOffer::STATUS_COLOR[$row->contractStatus] : 'none';
                })
                ->editColumn('user_status_color', function ($row) {
                    return $row->userPayment && RestaurantOffer::USER_STATUS_COLOR[$row->userPayment] ? RestaurantOffer::USER_STATUS_COLOR[$row->userPayment] : 'none';
                })                
            ->make(true);
    }
    

























    public function downloadEXCMonthSelectiveR(Request $req){

        $dt = $req->monthSelectedEXCMonthSelective.'-'.$req->monthSelectedEXCYearSelective;
        $dt2D = explode('-',$dt);
        $theRes = Restorant::findOrFail(Auth::user()->sFor);
        $adr2D =  explode(',',$theRes->adresa);
        $adr1 =  $adr2D[0];
        $adr2 = '---' ;
        if(isset($adr2D[1])){ $adr2 = $adr2D[1] ; }
        if(isset($adr2D[2])){ $adr2 = $adr2D[1].','.$adr2D[2]; }

        $month = new Carbon($dt2D[1].'-'.$dt2D[0].'-01');

        $dateOfCr = date('d').'.'.date('m').'.'.date('Y').' / '.date('H').':'.date('i');
        
        if($theRes->resTvsh == 0){
            $hiTvsh = number_format( 0 , 9, '.', '');
            $loTvsh = number_format( 0 , 9, '.', '');
        }else{
            $hiTvsh = number_format( 0.074930619 , 9, '.', '');
            $loTvsh = number_format( 0.025341130 , 9, '.', '');
        }

        // time calculate
        $dateBase = Carbon::create($req->monthSelectedEXCYearSelective, $req->monthSelectedEXCMonthSelective, 01, 01, 00, 00);
        $dateMonthEnd = $dateBase->endOfMonth();
        $dateMonthEnd = explode(' ',$dateMonthEnd)[0];
        $dateMonthEnd2D = explode('-',$dateMonthEnd);

        $resClock = explode('->',$theRes->reportTimeArc);
        $resClock1_2D = explode(':',$resClock[0]);
        $resClock2_2D = explode(':',$resClock[1]);
        $dateStart = Carbon::create($req->monthSelectedEXCYearSelective, $req->monthSelectedEXCMonthSelective, 01, $resClock1_2D[0], $resClock1_2D[1], 00);
        $dateEnd = Carbon::create($dateMonthEnd2D[0], $dateMonthEnd2D[1], $dateMonthEnd2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
        if($theRes->reportTimeOtherDay == 1){
            $dateEnd->addDays(1); 
        }
        // ------------------------------------------------------------------------------------------
        
        // Prod groups
        $proGrsIDS = array();
        $proGrs = array();
        foreach(OrdersPassive::where('Restaurant',$theRes->id)->whereBetween('created_at', [$dateStart, $dateEnd])->get() as $orderOnePG){
            foreach(explode('---8---',$orderOnePG->porosia) as $prodOnePG){
                $prodOnePG2D = explode('-8-',$prodOnePG);
                if(isset($prodOnePG2D[8]) && $prodOnePG2D[8] != 0){
                    
                        if(!in_array($prodOnePG2D[8],$proGrsIDS)){
                            array_push($proGrsIDS,$prodOnePG2D[8]);
                            $theProGr = pdfResProdCats::find($prodOnePG2D[8]);
                            array_push($proGrs,$theProGr);
                        }
                    
                }else{
                    if($orderOnePG->nrTable == 500){
                        if($orderOnePG->userPhoneNr != '0770000000'){
                            if($orderOnePG->userPhoneNr == '0000000000'){
                                $theP = Takeaway::where('prod_id',$prodOnePG2D[7])->first();
                            }else{
                                $theP = Takeaway::find($prodOnePG2D[7]);  
                            }                             
                        }else{
                            $theP = Takeaway::where('prod_id',$prodOnePG2D[7])->first();
                        }
                        if($theP != NULL && $theP->toReportCat != 0){
                            $repGr = $theP->toReportCat;
                            if(!in_array($repGr,$proGrsIDS)){
                                array_push($proGrsIDS,$repGr);
                                $theProGr = pdfResProdCats::findOrFail($repGr);
                                array_push($proGrs,$theProGr);
                            }
                        }
                    }else{
                        $theP = Produktet::find($prodOnePG2D[7]);
                        if($theP != NULL && $theP->toReportCat != 0){
                            $repGr = $theP->toReportCat;
                            if(!in_array($repGr,$proGrsIDS)){
                                array_push($proGrsIDS,$repGr);
                                $theProGr = pdfResProdCats::findOrFail($repGr);
                                array_push($proGrs,$theProGr);
                            }
                        }
                    }
                }
            }
        }
        // ------------------------------------------------------------------------------------------

        $excelData23[] = array($theRes->emri); 
        array_push($excelData23,array($adr1));
        array_push($excelData23,array($adr2));
        array_push($excelData23,array("Tel ".$theRes->resPhoneNr));

        if (str_contains($theRes->chemwstForRes, 'CHE')){
            array_push($excelData23,array("MwST-Nr ".$theRes->chemwstForRes));
        }else{
            array_push($excelData23,array($theRes->chemwstForRes));
        }

        $pdfRepot = pdfReportIns::where([['forRes',$theRes->id],['reportType','3'],['forDate1',$dt2D[0]]])->first();
        if($pdfRepot != NULL){
            $repoNr = $pdfRepot->billNumber; 
            $repoID = $pdfRepot->billId; 
        }else{
            $repoNr = strval(hexdec(uniqid()));
            if(pdfReportIns::where('forRes',$theRes->id)->count() > 0){ $repoID = pdfReportIns::where('forRes',$theRes->id)->max('billId') + 1; }
            else{ $repoID = 1; }
            $newRep = new pdfReportIns();
            $newRep->forRes = $theRes->id;
            $newRep->forDate1 = $dt2D[0];
            $newRep->reportType = 3;
            $newRep->billId = $repoID;
            $newRep->billNumber = $repoNr;
            $newRep->save();
        }

        array_push($excelData23,array("Bericht ID#: ".str_pad($repoID, 8, '0', STR_PAD_LEFT)));
        array_push($excelData23,array("Bericht#: ".$repoNr));
        array_push($excelData23,array("Datum/Zeit: ".$dateOfCr));
        $dateStart2D = explode(' ',$dateStart);
        $dateStart3D = explode('-',$dateStart2D[0]);
        $dateEnd2D = explode(' ',$dateEnd);
        $dateEnd3D = explode('-',$dateEnd2D[0]);
        array_push($excelData23,array('Berichtsstart: '.$dateStart3D[2].'.'.$dateStart3D[1].'.'.$dateStart3D[0].' '.$dateStart2D[1]));
        array_push($excelData23,array('Berichtsende; '.$dateEnd3D[2].'.'.$dateEnd3D[1].'.'.$dateEnd3D[0].' '.$dateEnd2D[1]));
        array_push($excelData23,array(''));

        $monthByLTR = "";
        switch($dt2D[0]){
            case 1:  $monthByLTR="Januar"; break;
            case 2:  $monthByLTR="Februar"; break;
            case 3:  $monthByLTR="März"; break;
            case 4:  $monthByLTR="April"; break;
            case 5:  $monthByLTR="Mai"; break;
            case 6:  $monthByLTR="Juni"; break;
            case 7:  $monthByLTR="Juli"; break;
            case 8:  $monthByLTR="August"; break;
            case 9:  $monthByLTR="September"; break;
            case 10:  $monthByLTR="Oktober"; break;
            case 11:  $monthByLTR="November"; break;
            case 12:  $monthByLTR="Dezember"; break;
        }
        array_push($excelData23,array("Monatlicher Bericht: (".$monthByLTR.") ".str_pad($dt2D[0], 2, '0', STR_PAD_LEFT).".".$dt2D[1]));
        // ------------------------------------------------------------------------------------------


        $fields1 = array('','Datum','Tag'); 
        foreach ($proGrs as $onePrGr){
            array_push($fields1, $onePrGr->catTitle);

            $salesPerProGr[$onePrGr->id] = number_format(0,9,'.','');

            $totBruto[$onePrGr->id] = number_format(0,9,'.','');
            $totmwst77p[$onePrGr->id] = number_format(0,9,'.','');
            $totmwst77pSales[$onePrGr->id] = number_format(0,9,'.','');
            $totmwst25p[$onePrGr->id] = number_format(0,9,'.','');
            $totmwst25pSales[$onePrGr->id] = number_format(0,9,'.','');
        }
        array_push($fields1, "Nicht kategorisiert");
        array_push($fields1, "Verkaufte Guscheine");
        array_push($fields1, "Gesamt");
        array_push($excelData23,$fields1);
    


        $salesPerProGr[0] = number_format(0,9,'.','');
        $totBruto[0] = number_format(0,9,'.','');
        $totmwst77p[0] = number_format(0,9,'.','');
        $totmwst77pSales[0] = number_format(0,9,'.','');
        $totmwst25p[0] = number_format(0,9,'.','');
        $totmwst25pSales[0] = number_format(0,9,'.','');
        $totalRebat = number_format(0,9,'.','');
        $gcSalesAll = number_format(0,9,'.','');
        $daysBrutoTot = array();

         
        for($i=1;$i<=$month->daysInMonth;$i++){
            $fields1_data = array('');
            array_push($fields1_data, $i);

            $dateCheckCreate = new Carbon($dt2D[1].'-'.sprintf('%02d', $dt2D[0]).'-'.sprintf('%02d', $i));
            $dayOfWeekNr = date('w', strtotime($dateCheckCreate));

            $resClock = explode('->',$theRes->reportTimeArc);
            $resClock1_2D = explode(':',$resClock[0]);
            $resClock2_2D = explode(':',$resClock[1]);
            $dateStartThisDate = Carbon::create($dt2D[1], $dt2D[0], $i, $resClock1_2D[0], $resClock1_2D[1], 00);
            $dateEndThisDate = Carbon::create($dt2D[1], $dt2D[0], $i, $resClock2_2D[0], $resClock2_2D[1], 59);
            if($theRes->reportTimeOtherDay == 1){
                // diff day
                $dateEndThisDate->addDays(1); 
            }

            $daysTotal = number_format(0,9,'.','');
            $daysRabat = number_format(0,9,'.','');
            $thisDaysOrds = OrdersPassive::where([['Restaurant',$theRes->id],['statusi','!=','2']])->whereBetween('created_at', [$dateStartThisDate, $dateEndThisDate])->get();

            $gcSalesThisDay = giftCard::where([['toRes',$theRes->id],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStartThisDate, $dateEndThisDate])->sum('gcSumInChf');
            $gcSalesAll += number_format($gcSalesThisDay ,9,'.','');

            // iterim neper qdo porosi per kete date
            foreach($thisDaysOrds as $thOrOne){

                $totFromProductePrice = number_format(0, 2, '.', '');
                foreach(explode('---8---',$thOrOne) as $produkti){
                    $prod = explode('-8-', $produkti);
                    $totFromProductePrice += number_format($prod[4], 2, '.', '');
                }

                // porosit RESTAURANT
                if($thOrOne->nrTable != 500 && $thOrOne->nrTable != 9000){
                    $poroAll = explode('---8---',$thOrOne->porosia);
                    foreach( $poroAll as $poroOne ){
                        $poroOne2D = explode('-8-',$poroOne);
                        
                        $theP = Produktet::find($poroOne2D[7]);
                        if(isset($poroOne2D[8]) && $poroOne2D[8] != 0){
                            $repGr = (int)$poroOne2D[8];
                        }else{
                            if($theP == NULL){
                                $repGr = (int)0;

                                $notCatLog = new ordersInNotCatInReport2();
                                $notCatLog->prodId = 0;
                                $notCatLog->prodIdTA = 0;
                                $notCatLog->orderId = $thOrOne->id;
                                $notCatLog->dayNr = $i;
                                $notCatLog->save();
                            }else{ $repGr = (int)$theP->toReportCat; }
                        }

                        

                        if($thOrOne->inCashDiscount > 0){
                            $totZbritja = number_format($thOrOne->inCashDiscount, 2, '.', '');
                            $cal1 = number_format(($poroOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                            $cal2 = number_format($poroOne2D[4] - $cal1, 9, '.', '');
                            $totmwst77p[$repGr] += number_format((float)$cal2 * $hiTvsh,9,'.','');

                            $totBruto[$repGr] += number_format((float)$cal2,9,'.','');
                            $totmwst77pSales[$repGr] += number_format((float)$cal2,9,'.','');
                            $salesPerProGr[$repGr] += number_format((float)$cal2,9,'.','');
                        }else if($thOrOne->inPercentageDiscount > 0){
                            $totZbritjaPrc = number_format($thOrOne->inPercentageDiscount / 100, 2, '.', '');
                            $cal1 = number_format($poroOne2D[4] * $totZbritjaPrc, 9, '.', '');
                            $cal2 = number_format($poroOne2D[4] - $cal1, 9, '.', '');
                            $totmwst77p[$repGr] += number_format((float)$cal2 * $hiTvsh,9,'.','');

                            $totBruto[$repGr] += number_format((float)$cal2,9,'.','');
                            $totmwst77pSales[$repGr] += number_format((float)$cal2,9,'.','');
                            $salesPerProGr[$repGr] += number_format((float)$cal2,9,'.','');
                        }else{
                            $totmwst77p[$repGr] += number_format((float)$poroOne2D[4] * $hiTvsh,9,'.','');
                            $totBruto[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                            $totmwst77pSales[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                            $salesPerProGr[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                        }
                        
                    }

                // porosit TAKEAWAY 
                }else if($thOrOne->nrTable == 500){
                    $poroAll = explode('---8---',$thOrOne->porosia);
                    foreach( $poroAll as $poroOne ){
                        $poroOne2D = explode('-8-',$poroOne);

                        if($thOrOne->userPhoneNr != '0770000000'){ 
                            if($thOrOne->userPhoneNr == '0000000000'){ 
                                $theP = Takeaway::where('prod_id',$poroOne2D[7])->first();
                            }else{
                                $theP = Takeaway::find($poroOne2D[7]); 
                            }
                        }else{ 
                            $theP = Takeaway::find($poroOne2D[7]);
                            if($theP == NULL){ 
                                $theP = Takeaway::where('prod_id',$poroOne2D[7])->first(); 
                            }else{
                                if($theP->emri != $poroOne2D[0] || $theP->qmimi != $poroOne2D[4]){
                                    $theP = Takeaway::where('prod_id',$poroOne2D[7])->first(); 
                                }
                            }
                        }

                        if(isset($poroOne2D[8]) && $poroOne2D[8] != 0){
                            $repGr = (int)$poroOne2D[8];
                        }else{
                            if($theP == NULL){
                                $repGr = (int)0;
                                $notCatLog = new ordersInNotCatInReport2();
                                $notCatLog->prodId = 0;
                                $notCatLog->prodIdTA = 500;
                                $notCatLog->orderId = $thOrOne->id;
                                $notCatLog->dayNr = $i;
                                $notCatLog->save();                                        
                            }else{ $repGr = (int)$theP->toReportCat; }
                        }
                    
                        

                        if($thOrOne->inCashDiscount > 0){
                            $totZbritja = number_format($thOrOne->inCashDiscount, 2, '.', '');
                            $cal1 = number_format(($poroOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                            $cal2 = number_format($poroOne2D[4] - $cal1, 9, '.', '');
                            if($theP == NULL){ 
                                $totmwst25p[$repGr] += number_format((float)$cal2 * $loTvsh,9,'.','');
                                $totmwst25pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                            }else if($theP->mwstForPro == 7.70 || $theP->mwstForPro == 8.10){
                                $totmwst77p[$repGr] += number_format((float)$cal2 * $hiTvsh,9,'.','');
                                $totmwst77pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                            }else if($theP->mwstForPro == 2.50 || $theP->mwstForPro == 2.60){
                                $totmwst25p[$repGr] += number_format((float)$cal2 * $loTvsh,9,'.','');
                                $totmwst25pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                            }
                            $totBruto[$repGr] += number_format((float)$cal2,9,'.','');
                            $salesPerProGr[$repGr] += number_format((float)$cal2,9,'.','');
                        }else if($thOrOne->inPercentageDiscount > 0){
                            $totZbritjaPrc = number_format($thOrOne->inPercentageDiscount / 100, 2, '.', '');
                            $cal1 = number_format($poroOne2D[4] * $totZbritjaPrc, 9, '.', '');
                            $cal2 = number_format($poroOne2D[4] - $cal1, 9, '.', '');
                            if($theP == NULL){ 
                                $totmwst25p[$repGr] += number_format((float)$cal2 * $loTvsh,9,'.','');
                                $totmwst25pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                            }else if($theP->mwstForPro == 7.70 || $theP->mwstForPro == 8.10){
                                $totmwst77p[$repGr] += number_format((float)$cal2 * $hiTvsh,9,'.','');
                                $totmwst77pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                            }else if($theP->mwstForPro == 2.50 || $theP->mwstForPro == 2.60){
                                $totmwst25p[$repGr] += number_format((float)$cal2 * $loTvsh,9,'.','');
                                $totmwst25pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                            }
                            $totBruto[$repGr] += number_format((float)$cal2,9,'.','');
                            $salesPerProGr[$repGr] += number_format((float)$cal2,9,'.','');
                        }else{
                            if($theP == NULL){ 
                                $totmwst25p[$repGr] += number_format((float)$poroOne2D[4] * $loTvsh,9,'.','');
                                $totmwst25pSales[$repGr] += number_format((float)$poroOne2D[4] ,9,'.','');
                            }else if($theP->mwstForPro == 7.70 || $theP->mwstForPro == 8.10){
                                $totmwst77p[$repGr] += number_format((float)$poroOne2D[4] * $hiTvsh,9,'.','');
                                $totmwst77pSales[$repGr] += number_format((float)$poroOne2D[4] ,9,'.','');
                            }else if($theP->mwstForPro == 2.50 || $theP->mwstForPro == 2.60){
                                $totmwst25p[$repGr] += number_format((float)$poroOne2D[4] * $loTvsh,9,'.','');
                                $totmwst25pSales[$repGr] += number_format((float)$poroOne2D[4] ,9,'.','');
                            }
                            $totBruto[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                            $salesPerProGr[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                        }
                    }

                // porosit DELIVERY
                }else if($thOrOne->nrTable == 9000){
                    $poroAll = explode('---8---',$thOrOne->porosia);
                    foreach( $poroAll as $poroOne ){
                        $poroOne2D = explode('-8-',$poroOne);
                        $theP = Produktet::find($poroOne2D[7]);

                        if($theP == NULL){
                            $repGr = (int)0;
                            $notCatLog = new ordersInNotCatInReport2();
                            $notCatLog->prodId = 0;
                            $notCatLog->prodIdTA = 9000;
                            $notCatLog->orderId = $thOrOne->id;
                            $notCatLog->dayNr = $i;
                            $notCatLog->save(); 
                        }else{ $repGr = (int)$theP->toReportCat; }

                        $salesPerProGr[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                        $totBruto[$repGr] += number_format((float)$poroOne2D[4],9,'.','');

                        if($theP == Null || $theP->mwstForPro == 7.70 || $theP->mwstForPro == 8.10){
                            $totmwst77p[$repGr] += number_format((float)$poroOne2D[4] * $hiTvsh,9,'.','');
                            $totmwst77pSales[$repGr] += number_format((float)$poroOne2D[4] ,9,'.','');
                        }else if($theP->mwstForPro == 2.50 || $theP->mwstForPro == 2.60){
                            $totmwst25p[$repGr] += number_format((float)$poroOne2D[4] * $loTvsh,9,'.','');
                            $totmwst25pSales[$repGr] += number_format((float)$poroOne2D[4] ,9,'.','');
                        }
                    }
                }

                if((float)$thOrOne->inCashDiscount > 0){
                    $daysRabat += number_format((float)$thOrOne->inCashDiscount,9,'.','');
                }else if((float)$thOrOne->inPercentageDiscount > 0){
                    $rebatVal = number_format((float)$thOrOne->inPercentageDiscount / 100 ,9,'.','');
                    $daysRabat += number_format((float)$rebatVal * ((float)$thOrOne->shuma - (float)$thOrOne->tipPer),9,'.','');
                }
                // $daysRabat += number_format((float)$thOrOne->dicsountGcAmnt,9,'.','');
            }

            $dayByLtr = '0';
            switch($dayOfWeekNr){
                case 1:  $dayByLtr = "Mo"; break;
                case 2:  $dayByLtr = "Di"; break;
                case 3:  $dayByLtr = "Mi"; break;
                case 4:  $dayByLtr = "Do"; break;
                case 5:  $dayByLtr = "Fr"; break;
                case 6:  $dayByLtr = "Sa"; break;
                case 0:  $dayByLtr = "So"; break;
            }
            array_push($fields1_data, $dayByLtr);

            foreach ($proGrs as $onePrGr){
                array_push($fields1_data, number_format($salesPerProGr[$onePrGr->id],2,'.',''));
                       
                $daysTotal += number_format($salesPerProGr[$onePrGr->id],9,'.','');
                $salesPerProGr[$onePrGr->id] = number_format(0,9,'.',''); 
            }
            $daysTotal += number_format($salesPerProGr[0],9,'.','');

            array_push($fields1_data, number_format($salesPerProGr[0],2,'.',''));
                    // number_format($daysRabat ,2,'.','')
            array_push($fields1_data, number_format($gcSalesThisDay,2,'.',''));          
            array_push($fields1_data, number_format($daysTotal + $gcSalesThisDay,2,'.',''));          

            $totalRebat += number_format($daysRabat,9,'.','');
            $salesPerProGr[0] = number_format(0,9,'.',''); 
            $daysBrutoTot[$i] = number_format($daysTotal,9,'.','');
            $daysTotal = number_format(0,9,'.','');
            $daysRabat = number_format(0,9,'.','');

            array_push($excelData23,$fields1_data);
        }

        // TOTALET 
        $totBrutoAll = number_format(0,9,'.','');
        $totmwst77All = number_format(0,9,'.','');
        $totmwst77AllSales = number_format(0,9,'.','');
        $totmwst25All = number_format(0,9,'.','');
        $totmwst25AllSales = number_format(0,9,'.','');

        $fields1_totals = array('');
        array_push($fields1_totals, 'Total Brutto');
        array_push($fields1_totals, '');
        foreach ($proGrs as $onePrGr){
            array_push($fields1_totals, number_format($totBruto[$onePrGr->id],2,'.',''));
            $totBrutoAll += number_format($totBruto[$onePrGr->id],2,'.','');
        }
        array_push($fields1_totals, number_format($totBruto[0],2,'.',''));               
        $totBrutoAll += number_format($totBruto[0],2,'.','');
            // number_format($totalRebat,2,'.','')
        array_push($fields1_totals, number_format($gcSalesAll,9,'.',''));
        array_push($fields1_totals, number_format($totBrutoAll + $gcSalesAll,9,'.',''));
                       
        array_push($excelData23,$fields1_totals);
        // -------------------------------------------------------------------------------------------------------------------
        
        // MWST _ 1
        $fields1_mwst_Pre1 = array('');
        if($theRes->resTvsh == 0){
            array_push($fields1_mwst_Pre1, '0.00% Verkäufe');       
        }else{
            if($dt2D[1] <= 2023){
                array_push($fields1_mwst_Pre1, '7.70% Verkäufe');
            }else{
                array_push($fields1_mwst_Pre1, '8.10% Verkäufe');
            }
        }
        array_push($fields1_mwst_Pre1, '');

        foreach ($proGrs as $onePrGr){
            array_push($fields1_mwst_Pre1, number_format($totmwst77pSales[$onePrGr->id],2,'.','')); 
            $totmwst77AllSales += number_format($totmwst77pSales[$onePrGr->id],2,'.',''); 
        }
        array_push($fields1_mwst_Pre1, number_format($totmwst77pSales[0],2,'.',''));
        $totmwst77AllSales += number_format($totmwst77pSales[0],2,'.',''); 

        array_push($fields1_mwst_Pre1, number_format(0,2,'.',''));
        array_push($fields1_mwst_Pre1, number_format($totmwst77AllSales,2,'.',''));

        array_push($excelData23,$fields1_mwst_Pre1);
        // --- 

        $fields1_mwst_1 = array('');
          
        if($theRes->resTvsh == 0){
            array_push($fields1_mwst_1, 'MwSt. 0.00%');       
        }else{
            if($dt2D[1] <= 2023){
                array_push($fields1_mwst_1, 'MwSt. 7.70%');
            }else{
                array_push($fields1_mwst_1, 'MwSt. 8.10%');
            }
        }
        array_push($fields1_mwst_1, '');
                    
        foreach ($proGrs as $onePrGr){
            array_push($fields1_mwst_1, number_format($totmwst77p[$onePrGr->id],2,'.',''));
            $totmwst77All += number_format($totmwst77p[$onePrGr->id],2,'.','');
        }
        array_push($fields1_mwst_1, number_format($totmwst77p[0],2,'.',''));
        $totmwst77All += number_format($totmwst77p[0],2,'.','');
            // empty one
        array_push($fields1_mwst_1, number_format(0,2,'.',''));
        array_push($fields1_mwst_1, number_format($totmwst77All,2,'.',''));

        array_push($excelData23,$fields1_mwst_1);
        // -------------------------------------------------------------------------------------------------------------------


        // MWST _ 2
        $fields1_mwst_Pre2 = array('');
        if($theRes->resTvsh == 0){
            array_push($fields1_mwst_Pre2, '0.00% Verkäufe');       
        }else{
            if($dt2D[1] <= 2023){
                array_push($fields1_mwst_Pre2, '2.50% Verkäufe');
            }else{
                array_push($fields1_mwst_Pre2, '2.60% Verkäufe');
            }
        }
        array_push($fields1_mwst_Pre2, '');

        foreach ($proGrs as $onePrGr){
            array_push($fields1_mwst_Pre2, number_format($totmwst25pSales[$onePrGr->id],2,'.','')); 
            $totmwst25AllSales += number_format($totmwst25pSales[$onePrGr->id],2,'.',''); 
        }
        array_push($fields1_mwst_Pre2, number_format($totmwst25pSales[0],2,'.',''));
        $totmwst25AllSales += number_format($totmwst25pSales[0],2,'.',''); 

        array_push($fields1_mwst_Pre2, number_format(0,2,'.',''));
        array_push($fields1_mwst_Pre2, number_format($totmwst25AllSales,2,'.',''));

        array_push($excelData23,$fields1_mwst_Pre2);
        // --- 

        $fields1_mwst_2 = array('');
        if($theRes->resTvsh == 0){
            array_push($fields1_mwst_2, 'MwSt. 0.00%');       
        }else{
            if($dt2D[1] <= 2023){
                array_push($fields1_mwst_2, 'MwSt. 2.50%');
            }else{
                array_push($fields1_mwst_2, 'MwSt. 2.60%');
            }
        }     
        array_push($fields1_mwst_2, '');

        foreach ($proGrs as $onePrGr){
            array_push($fields1_mwst_2, number_format($totmwst25p[$onePrGr->id],2,'.',''));
            $totmwst25All += number_format($totmwst25p[$onePrGr->id],2,'.','');
        }
        array_push($fields1_mwst_2, number_format($totmwst25p[0],2,'.',''));
        $totmwst25All += number_format($totmwst25p[0],2,'.','');
            // empty one
        array_push($fields1_mwst_2, number_format(0,2,'.',''));
        array_push($fields1_mwst_2, number_format($totmwst25All,2,'.',''));

        array_push($excelData23,$fields1_mwst_2);
        // -------------------------------------------------------------------------------------------------------------------


        // MWST _ TOTAL
        $fields1_mwst_total = array('');
        array_push($fields1_mwst_total, 'Total MwSt.'); 
        array_push($fields1_mwst_total, '');
        foreach ($proGrs as $onePrGr){
            array_push($fields1_mwst_total, number_format($totmwst77p[$onePrGr->id] + $totmwst25p[$onePrGr->id],2,'.','')); 
        }
        array_push($fields1_mwst_total, number_format($totmwst77p[0] + $totmwst25p[0],2,'.','')); 
            // empty one  
        array_push($fields1_mwst_total, number_format(0,2,'.',''));      
        array_push($fields1_mwst_total, number_format($totmwst77All + $totmwst25All,2,'.','')); 

        array_push($excelData23,$fields1_mwst_total);
        // -------------------------------------------------------------------------------------------------------------------

        // NETO _ TOTAL
        $fields1_totals_neto = array('');
        array_push($fields1_totals_neto, 'Total Netto.');
        array_push($fields1_totals_neto, '');
                 
        foreach ($proGrs as $onePrGr){
            array_push($fields1_totals_neto, number_format($totBruto[$onePrGr->id] - $totmwst77p[$onePrGr->id] - $totmwst25p[$onePrGr->id],2,'.','')); 
        }

        array_push($fields1_totals_neto, number_format($totBruto[0] - $totmwst77p[0] - $totmwst25p[0],2,'.','') ); 
            // empty one  
        array_push($fields1_totals_neto, number_format($gcSalesAll,2,'.',''));      
        array_push($fields1_totals_neto, number_format($totBrutoAll - $totmwst77All - $totmwst25All + $gcSalesAll,2,'.','')); 

        array_push($excelData23,$fields1_totals_neto);
        // -------------------------------------------------------------------------------------------------------------------










        

        array_push($excelData23,array(''));
        array_push($excelData23,array(''));
        array_push($excelData23,array(''));
















        $fields2 = array('','');
        array_push($fields2, 'Brutto Umsatz');
        array_push($fields2, 'Bargeld');
        array_push($fields2, 'Kreditkarte');
        array_push($fields2, 'Online');
        array_push($fields2, 'Rechnung');
        array_push($fields2, 'Geschenkkarten');
        array_push($fields2, 'Trinkgeld');
        array_push($fields2, 'Rabatte / Gutscheine');
        array_push($fields2, 'Ausgaben');
        array_push($fields2, 'Ausgaben Bar');
        array_push($fields2, 'Bar Endbestand');
            // Kellner Tagesumsatz
        array_push($excelData23,$fields2);


        $acuBrutto = number_format(0,9,'.','');
        $couponSalesTotal = number_format(0,9,'.','');
        $rechnungPagesaTotal = number_format(0,9,'.','');
        $cardPagesaTotal = number_format(0,9,'.','');
        $onlinePagesaTotal = number_format(0,9,'.','');
        $cashPagesaTotal = number_format(0,9,'.','');
        $zbritjaTotal = number_format(0,9,'.','');
        $thisDaysWaSalesTotal = number_format(0,9,'.','');
        $thisDaysExpensesTotal = number_format(0,9,'.','');
        $cashPagesaGCTotal = number_format(0,9,'.','');
        $cardPagesaGCTotal = number_format(0,9,'.','');
        $onlinePagesaGCTotal = number_format(0,9,'.','');
        $rechnungPagesaGCTotal = number_format(0,9,'.','');
        $tipTotal = number_format(0,9,'.','');
        $billRecordsExpCashTotal = number_format(0,9,'.','');

        for($j=1;$j<=$month->daysInMonth;$j++){

            $acuBrutto += number_format($daysBrutoTot[$j],9,'.','');
            $couponSales = number_format(0,9,'.','');
            $rechnungPagesa = number_format(0,9,'.','');
            $cardPagesa = number_format(0,9,'.','');
            $onlinePagesa = number_format(0,9,'.','');
            $cashPagesa = number_format(0,9,'.','');
            $zbritja = number_format(0,9,'.','');
            $netoTot = number_format(0,9,'.','');
            $barazimiKamarier = number_format(0,9,'.','');
            $cashPagesaGC = number_format(0,9,'.','');
            $cardPagesaGC = number_format(0,9,'.','');
            $onlinePagesaGC = number_format(0,9,'.','');
            $rechnungPagesaGC = number_format(0,9,'.','');
            $tipTodayTotal = number_format(0,9,'.','');
            $billRecordsExpCash = number_format(0,9,'.','');
            
            $dateCheckCreate = new Carbon($dt2D[1].'-'.sprintf('%02d', $dt2D[0]).'-'.sprintf('%02d', $j));
            $dayOfWeekNr = date('w', strtotime($dateCheckCreate));

            $billRecordsExp = billsExpensesRecordRes::where([['forRes',Auth::user()->sFor],['forDate',$dateCheckCreate]])->first();
            if($billRecordsExp != Null){
                $billRecordsExpCash = number_format($billRecordsExp->expCash,9,'.','');
            }else{
                $billRecordsExpCash = number_format(0,9,'.','');
            }
            $billRecordsExpCashTotal += $billRecordsExpCash;

            $resClock = explode('->',$theRes->reportTimeArc);
            $resClock1_2D = explode(':',$resClock[0]);
            $resClock2_2D = explode(':',$resClock[1]);
            $dateStart = Carbon::create($dt2D[1], $dt2D[0], $j, $resClock1_2D[0], $resClock1_2D[1], 00);
            $dateEnd = Carbon::create($dt2D[1], $dt2D[0], $j, $resClock2_2D[0], $resClock2_2D[1], 59);
            if($theRes->reportTimeOtherDay == 1){
                // diff day
                $dateEnd->addDays(1); 
            }

            $thisDaysOrds = OrdersPassive::where([['Restaurant',$theRes->id],['statusi','!=','2']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
            $thisDaysGCSells = giftCard::where([['toRes',$theRes->id],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

            // iterim neper qdo porosi per kete date
            foreach($thisDaysOrds as $thOrOne){
                // + $thOrOne->cuponOffVal
                $zbritjaThisOrd = number_format(0,9,'.','');

                if($thOrOne->inPercentageDiscount > 0){
                    $prcOff = number_format($thOrOne->inPercentageDiscount / 100,9,'.','');
                    $prcOffCHF = number_format(($thOrOne->shuma + $thOrOne->cuponOffVal)* $prcOff,9,'.','');
                }else{
                    $prcOffCHF = number_format(0,9,'.','');
                }
                
                $zbritjaThisOrd += number_format($thOrOne->cuponOffVal,9,'.','');
                $zbritjaThisOrd += number_format($thOrOne->inCashDiscount,9,'.','');
                $zbritjaThisOrd += number_format($prcOffCHF,9,'.','');

                if($thOrOne->payM == 'Rechnung'){
                    $rechnungPagesa += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');
                    $rechnungPagesaTotal += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');

                }else if($thOrOne->payM == 'Kartenzahlung'){
                    $cardPagesa += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');
                    $cardPagesaTotal += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');

                }else if($thOrOne->payM == 'Onlinezahlung' || $thOrOne->payM == 'Online'){
                    $onlinePagesa += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');
                    $onlinePagesaTotal += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');

                }else if($thOrOne->payM == 'Barzahlungen' || $thOrOne->payM == 'Cash'){
                    $cashPagesa += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');
                    $cashPagesaTotal += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');
                }

                $tipTodayTotal += number_format($thOrOne->tipPer,9,'.','');

                $zbritja += number_format($thOrOne->cuponOffVal,9,'.','');
                $zbritja += number_format($thOrOne->inCashDiscount,9,'.','');
                $zbritja += number_format($prcOffCHF,9,'.','');
                
                $zbritjaTotal += number_format($thOrOne->cuponOffVal,9,'.','');
                $zbritjaTotal += number_format($thOrOne->inCashDiscount,9,'.','');
                $zbritjaTotal += number_format($prcOffCHF,9,'.',''); 

                $couponSales += number_format($thOrOne->dicsountGcAmnt,9,'.','');
                $couponSalesTotal += number_format($thOrOne->dicsountGcAmnt,9,'.','');
            }

            $tipTotal += number_format($tipTodayTotal,9,'.','');

            // $thisDaysWaSales = waiterDaySales::where([['forRes',$oneRes],['forDay',$dt2d[1].'-'.sprintf('%02d', $dt2d[0]).'-'.sprintf('%02d', $j)]])->sum('totalInChf');
            // $thisDaysWaSalesTotal += number_format($thisDaysWaSales,9,'.','');

            $thisDaysExpenses = billsExpensesRecordRes::where([['forRes',$theRes->id],['forDate',$dt2D[1].'-'.sprintf('%02d', $dt2D[0]).'-'.sprintf('%02d', $j).' 00:00:00']])->first();
            if($thisDaysExpenses != Null){
                $thisDaysExpensesVal = number_format($thisDaysExpenses->expValue,9,'.','');
                $thisDaysExpensesTotal += number_format($thisDaysExpenses->expValue,9,'.','');
            }else{
                $thisDaysExpensesVal = number_format(0,9,'.','');
            }

            foreach($thisDaysGCSells as $thGCSellOne){
                if($thGCSellOne->payM == 'Cash'){
                    $cashPagesaGC += number_format($thGCSellOne->gcSumInChf,9,'.','');
                    $cashPagesaGCTotal += number_format($thGCSellOne->gcSumInChf,9,'.','');
                }else if($thGCSellOne->payM == 'Card'){
                    $cardPagesaGC += number_format($thGCSellOne->gcSumInChf,9,'.','');
                    $cardPagesaGCTotal += number_format($thGCSellOne->gcSumInChf,9,'.','');
                }else if($thGCSellOne->payM == 'Online'){
                    $onlinePagesaGC += number_format($thGCSellOne->gcSumInChf,9,'.','');
                    $onlinePagesaGCTotal += number_format($thGCSellOne->gcSumInChf,9,'.','');
                }else if($thGCSellOne->payM == 'Rechnung'){
                    $rechnungPagesaGC += number_format($thGCSellOne->gcSumInChf,9,'.','');
                    $rechnungPagesaGCTotal += number_format($thGCSellOne->gcSumInChf,9,'.','');
                }
            }


            $fields2_data = array('');
            array_push($fields2_data, $j);
            array_push($fields2_data, number_format($cashPagesa + $cardPagesa + $onlinePagesa + $rechnungPagesa + $couponSales + $cashPagesaGC + $cardPagesaGC + $onlinePagesaGC + $rechnungPagesaGC - $tipTodayTotal,2,'.',''));
            array_push($fields2_data, number_format($cashPagesa + $cashPagesaGC - $tipTodayTotal ,2,'.',''));
            array_push($fields2_data, number_format($cardPagesa + $cardPagesaGC,2,'.',''));
            array_push($fields2_data, number_format($onlinePagesa + $onlinePagesaGC,2,'.',''));
            array_push($fields2_data, number_format($rechnungPagesa + $rechnungPagesaGC,2,'.',''));
            array_push($fields2_data, number_format($couponSales,2,'.',''));
            array_push($fields2_data, number_format($tipTodayTotal ,2,'.',''));
            array_push($fields2_data, number_format($zbritja,2,'.',''));
            array_push($fields2_data, number_format($thisDaysExpensesVal,2,'.',''));
            array_push($fields2_data, number_format($billRecordsExpCash,2,'.',''));
            array_push($fields2_data, number_format(($cashPagesa + $cashPagesaGC - $tipTodayTotal) - $billRecordsExpCash,2,'.',''));

            array_push($excelData23,$fields2_data);
        }

        $fields2_total = array('');
        array_push($fields2_total, 'T:');
        array_push($fields2_total, number_format($cashPagesaTotal + $cardPagesaTotal + $onlinePagesaTotal + $rechnungPagesaTotal + $couponSalesTotal + $cashPagesaGCTotal + $cardPagesaGCTotal + $onlinePagesaGCTotal + $rechnungPagesaGCTotal - $tipTotal,2,'.',''));
        array_push($fields2_total, number_format($cashPagesaTotal + $cashPagesaGCTotal - $tipTotal,2,'.',''));
        array_push($fields2_total, number_format($cardPagesaTotal + $cardPagesaGCTotal,2,'.',''));
        array_push($fields2_total, number_format($onlinePagesaTotal + $onlinePagesaGCTotal,2,'.',''));
        array_push($fields2_total, number_format($rechnungPagesaTotal + $rechnungPagesaGCTotal,2,'.',''));
        array_push($fields2_total, number_format($couponSalesTotal,2,'.',''));
        array_push($fields2_total, number_format($tipTotal,2,'.',''));
        array_push($fields2_total, number_format($zbritjaTotal,2,'.',''));
        array_push($fields2_total, number_format($thisDaysExpensesTotal,2,'.',''));
        array_push($fields2_total, number_format($billRecordsExpCashTotal,2,'.',''));
        array_push($fields2_total, number_format(($cashPagesaTotal + $cashPagesaGCTotal - $tipTotal) - $billRecordsExpCashTotal,2,'.',''));
     
        array_push($excelData23,$fields2_total);
      






        // Excel file name and restaurant data
        $fileName = "report_v2_".date('Y-m-d').".xlsx"; 
        
        // Headers for download 
        // header("Content-Disposition: attachment; filename=\"$fileName\""); 
        // header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"); 

        // Export data to excel and download as xlsx file 
        $xlsx = phpxlsx::fromArray( $excelData23 ); 
        $xlsx->downloadAs($fileName); 

        // header("Content-Type: application/vnd.ms-excel");
        
        // Render excel data 
        // echo $excelData; 
    }
}
