<?php

namespace App\Http\Controllers;

use App\DeliveryProd;
use App\kategori;
use Illuminate\Http\Request;
use App\Produktet;
use App\resdemoalfa;
use App\Takeaway;

class SearchController extends Controller
{
    public function searchFrom(Request $req){
        // $req->searchWord
        $filteredData = Produktet::where([['emri', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])
                ->orWhere([['pershkrimi', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])
                ->orWhere([['type', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])
                ->orWhere([['extPro', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])->get();
        return json_encode( $filteredData );
    }

    public function searchFromTA(Request $req){
        // $req->searchWord
        $filteredData = Takeaway::where([['emri', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])
            ->orWhere([['pershkrimi', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])
            ->orWhere([['type', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])
            ->orWhere([['extPro', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])->get();
        return json_encode( $filteredData );
    }

    public function searchFromDE(Request $req){
        // $req->searchWord
        $filteredData = DeliveryProd::where([['emri', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])
            ->orWhere([['pershkrimi', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])
            ->orWhere([['type', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])
            ->orWhere([['extPro', 'like', '%'.$req->searchWord.'%'],['toRes','=', $req->theRestaurant],['accessableByClients','1']])->get();
        return json_encode( $filteredData );
    }



    public function searchFromCRM(Request $req){
        // $req->searchWord
        $filteredData = resdemoalfa::where('emri', 'like', '%'.$req->searchWord.'%')->orWhere('adresa', 'like', '%'.$req->searchWord.'%')
                                                                                    ->orWhere('plz', 'like', '%'.$req->searchWord.'%')
                                                                                    ->orWhere('ort', 'like', '%'.$req->searchWord.'%')->get();
        return json_encode( $filteredData );
    }


    public function searchProductsAdminAddOrder(Request $req){
        // resId
        // phraseS
        $kategoritToS = array();
        foreach(kategori::where('emri', 'like', '%'.$req->phraseS.'%')->where('toRes','=', $req->resId)->get() as $addThisCatToSearch){
            array_push($kategoritToS,  $addThisCatToSearch->id);
        }

        $filteredData = Produktet::where('emri', 'like', '%'.$req->phraseS.'%')->where('toRes','=', $req->resId)
        ->orWhere('pershkrimi', 'like', '%'.$req->phraseS.'%')->where('toRes','=', $req->resId)
        ->orWhere('type', 'like', '%'.$req->phraseS.'%')->where('toRes','=', $req->resId)
        ->orWhere('extPro', 'like', '%'.$req->phraseS.'%')->where('toRes','=', $req->resId)
        ->orWhereIn('kategoria',$kategoritToS)->get();

        return json_encode( $filteredData );
    }
}
