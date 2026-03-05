<?php

namespace App\Http\Controllers;

use App\ekstra;
use App\LlojetPro;
use App\stockmng;
use App\Produktet;
use App\Restorant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockMngController extends Controller{

    public function StockMngPage(){ return view('adminPanel/adminIndex'); }

    public function StockMngRegAllCategory(Request $req){

        // foreach(Produktet::where([['toRes',Auth::user()->sFor],['kategoria',$req->kId]])->get() as $proOne){
        //     foreach(stockmng::where([['elementType',2],['elementId',$proOne->id]])->get() as $proODel){
        //         if($proODel != Null){ $proODel->delete(); }
        //     }          
        // }
        // foreach(ekstra::where([['toRes',Auth::user()->sFor],['toCat',$req->kId]])->get() as $exOne){
        //     $stockReg = stockmng::where([['elementType',3],['elementId',$exOne->id]])->first();
        //     if($stockReg != Null){ $stockReg->delete(); }
        // }

        $newstoReg = new stockmng();
        $newstoReg->toRes = Auth::user()->sFor;
        $newstoReg->elementType = 1;
        $newstoReg->elementId = $req->kId;
        $newstoReg->katId = 0;
        $newstoReg->sasia = 0;
        $newstoReg->theType = 0;
        $newstoReg->status = 1;

        $newstoReg->save();

        foreach(Produktet::where([['toRes',Auth::user()->sFor],['kategoria',$req->kId]])->get() as $proOne){
            $stockReg = stockmng::where([['elementType',2],['elementId',$proOne->id]])->first();
            if($stockReg == Null){ 
                if($proOne->type == NULL){
                    $newstoReg = new stockmng();
                    $newstoReg->toRes = Auth::user()->sFor;
                    $newstoReg->elementType = 2;
                    $newstoReg->elementId = $proOne->id;
                    $newstoReg->katId = $req->kId;
                    $newstoReg->sasia = 0;
                    $newstoReg->theType = 0;
                    $newstoReg->status = 1;

                    $newstoReg->save();
                }else{
                    foreach(explode('--0--', $proOne->type) as $proTypeOne){
                        $proTypeOne2D=explode('||',$proTypeOne);
        
                        $newstoReg = new stockmng();
                        $newstoReg->toRes = Auth::user()->sFor;
                        $newstoReg->elementType = 2;
                        $newstoReg->elementId = $proOne->id;
                        $newstoReg->katId = $req->kId;
                        $newstoReg->sasia = 0;
                        $newstoReg->theType = $proTypeOne2D[0];
                        $newstoReg->status = 1;
        
                        $newstoReg->save();
                    } 
                }  
            }   
        }

        foreach(ekstra::where([['toRes',Auth::user()->sFor],['toCat',$req->kId]])->get() as $exOne){
            $stockReg = stockmng::where([['elementType',3],['elementId',$exOne->id]])->first();
            if($stockReg == Null){ 
                $newstoReg = new stockmng();
                $newstoReg->toRes = Auth::user()->sFor;
                $newstoReg->elementType = 3;
                $newstoReg->elementId = $exOne->id;
                $newstoReg->katId = $req->kId;
                $newstoReg->sasia = 0;
                $newstoReg->theType = 0;
                $newstoReg->status = 1;
    
                $newstoReg->save();
             }
        }
    }

    public function StockMngRegProduct(Request $req){
        $thePro = Produktet::find($req->pId);
        if($thePro->type == NULL){
            $newstoReg = new stockmng();
            $newstoReg->toRes = Auth::user()->sFor;
            $newstoReg->elementType = 2;
            $newstoReg->elementId = $thePro->id;
            $newstoReg->katId = $thePro->kategoria;
            $newstoReg->sasia = 0;
            $newstoReg->theType = 0;
            $newstoReg->status = 1;

            $newstoReg->save();
        }else{
            foreach(explode('--0--', $thePro->type) as $proTypeOne){
                $proTypeOne2D=explode('||',$proTypeOne);

                $newstoReg = new stockmng();
                $newstoReg->toRes = Auth::user()->sFor;
                $newstoReg->elementType = 2;
                $newstoReg->elementId = $req->pId;
                $newstoReg->katId = $thePro->kategoria;
                $newstoReg->sasia = 0;
                $newstoReg->theType = $proTypeOne2D[0];
                $newstoReg->status = 1;

                $newstoReg->save();
            }
        }
    }

    public function StockMngRegExtra(Request $req){
        $newstoReg = new stockmng();
        $newstoReg->toRes = Auth::user()->sFor;
        $newstoReg->elementType = 3;
        $newstoReg->elementId = $req->eId;
        $newstoReg->katId = 0;
        $newstoReg->sasia = 0;
        $newstoReg->theType = 0;
        $newstoReg->status = 1;

        $newstoReg->save();
    }

    public function StockMngAddSasiTo(Request $req){
        $addSas = (int)$req->sasiaAdd;
        foreach(explode('||',$req->stockInsIds) as $oneStockIns){
            $stIns = stockmng::find($oneStockIns);
            if($stIns != Null){
                $stIns->sasia = $stIns->sasia + $addSas;
                $stIns->save();
            }
        }
    }

    public function StockMngAddSasiByNrTo(Request $req){
        $chngSas = (int)$req->sasiaChng;
        foreach(explode('||',$req->stockInsIds) as $oneStockIns){
            $stIns = stockmng::find($oneStockIns);
            if($stIns != Null){
                if($chngSas > 0){
                    $stIns->sasia = $stIns->sasia + $chngSas;
                    $stIns->save();
                }else  if($chngSas < 0){
                    if($stIns->sasia == 0 || $stIns->sasia < ($chngSas*(-1))){
                        $stIns->sasia = 0;
                        $stIns->save();
                    }else{
                        $stIns->sasia = $stIns->sasia + $chngSas;
                        $stIns->save();
                    }
                }
            }
        }
    }


    public function StockMngSavePeriodGrChngs(Request $req){
        $theR = Restorant::find(Auth::user()->sFor);
        if($theR != Null){
            $theR->stockP1 = $req->firstVal; 
            $theR->stockP2 = $req->secondVal; 
            $theR->save(); 
        }
    }


    public function StockMngGetStockInsFromIds(Request $req){
        $stInsID = array();
        foreach(explode('||',$req->StInsIds) as $stInsOne){
            array_push($stInsID,$stInsOne);
        }
        $filteredData = stockmng::whereIn('id',$stInsID)->get();
        return json_encode( $filteredData );
    }

    public function StockMngGetProdNameAndType(Request $req){
        $stIns = stockmng::find($req->StId);
        $response = '';
        if($stIns != Null){
            $thePro = Produktet::find($stIns->elementId);
            $response = $thePro->emri;
            if($stIns->theType != 0){
                $theTy = LlojetPro::find($stIns->theType);
                $response .= ' / '.$theTy->emri;
            }
            return $response.'||'.$stIns->peshaFO;
        }else{
            return 'NoName||0';
        }
    }
    public function StockMngSetKgLtrToStIns(Request $req){
        $stIns = stockmng::find($req->stId);

        $stIns->KgLtr = $req->kgLtr;
        $stIns->save(); 
    }
    public function StockMngRemoveKgLtrToStIns(Request $req){
        $stIns = stockmng::find($req->stId);

        $stIns->KgLtr = 'none';
        $stIns->peshaFO = 0;
        $stIns->save(); 

        $response = '';
        if($stIns != Null){
            $thePro = Produktet::find($stIns->elementId);
            $response = $thePro->emri;
            if($stIns->theType != 0){
                $theTy = LlojetPro::find($stIns->theType);
                $response .= ' / '.$theTy->emri;
            }
            return $response.'||'.$stIns->peshaFO;
        }else{
            return 'NoName||0';
        }
    }
    public function StockMngSaveStockFromKgLtr(Request $req){
        $stockChnaged = '';
        foreach(explode('--||--',$req->stSaveCh) as $oneStSv){
            $oneStSv2D = explode('||',$oneStSv);
            $stIns = stockmng::find($oneStSv2D[0]);
            
            $totalQty = (float)$oneStSv2D[1]*(float)1000;
            $totalQty = (int)$totalQty;
            $oneProdQty = (int)$oneStSv2D[2];

            $toAdd = $totalQty / $oneProdQty;
            $toAdd = (int) $toAdd;

            if($stockChnaged == ''){
                $stockChnaged = $stIns->id.'|'.$stIns->elementId.'|'.$stIns->katId.'|'.$toAdd;
            }else{
                $stockChnaged .= '-|-'.$stIns->id.'|'.$stIns->elementId.'|'.$stIns->katId.'|'.$toAdd;
            }

            $stIns->sasia = $stIns->sasia + $toAdd;
            $stIns->peshaFO = $oneProdQty;
            $stIns->save();

        }
        return 'Success|||'.$stockChnaged;
    }






    public function StockMngDeleteSockInsProduct(Request $req){
        $stIns = stockmng::find($req->stId);
        $prodId =  $stIns->elementId;
        $catId =  $stIns->katId;

        $stInsKategori = stockmng::where([['toRes',Auth::user()->sFor],['elementType','1'],['elementId',$catId]])->first();
        if($stInsKategori != Null){ $stInsKategori->delete(); }

        foreach(stockmng::where([['toRes',Auth::user()->sFor],['elementId',$stIns->elementId]])->get() as $prodStInsOne){
            if($prodStInsOne != Null){
                $prodStInsOne->delete();
            }
        }
        return $prodId.'||'.$catId;
    }

    public function StockMngDeleteSockInsEkstra(Request $req){
        $stIns = stockmng::find($req->stId);
        $exId =  $stIns->elementId;
        $catId =  $stIns->katId;

        $stInsKategori = stockmng::where([['toRes',Auth::user()->sFor],['elementType','1'],['elementId',$catId]])->first();
        if($stInsKategori != Null){ $stInsKategori->delete(); }

        if($stIns != Null){
            $stIns->delete();
        }
        return $exId.'||'.$catId;
    }

}
