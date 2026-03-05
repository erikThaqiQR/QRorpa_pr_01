<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Restorant;
use App\RestaurantRating;
use Auth;
use DB;
use App\Covid;

class SearchRestaurantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
 /*    public function index()
    {
        return view('searchRestaurants');
    }*/
    public function fetch(Request $request){

         if($request->get('query')){
            $query = $request->get('query');
            $data = DB::table('restorants')
                ->where('emri', 'LIKE', "%{$query}%")
                ->orWhere('adresa', 'LIKE', "%{$query}%")
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($data as $row){
                $output .= '
                <li><a href="#"> <i class="fa fa-map-marker" aria-hidden="true" style="margin-right:5px;"></i> '.$row->emri.'</a></li>
                ';
            }
            $output .= '</ul>';
        
            echo $output;
          
         }
    }
    public function search(Request $request){
        $serWor = $request->input('emri');
        
        $korrORT = DB::table('plzort')->where('ort', 'LIKE', "%{$serWor}%")->first();
        if($korrORT != NULL){
            $search = $korrORT->plz;
        }else{
            $search = $serWor;
        }

    
        $restorants = Restorant::
            where('emri', 'LIKE', "%{$search}%")
            ->orWhere('adresa', 'LIKE', "%{$search}%")
            ->orWhere('deliveryFor', 'LIKE', "%{$search}%")
            ->orderBy('updated_at', 'desc')->paginate(30);

        return view('firstPage/searchRestaurants', ['restorants'=> $restorants, 'searchWord' => $search]);
    }


    public function searchFilter(Request $req){
        // filter: word,
        // ress: restorants,
        $all = 0;
        if($req->filter == 'takeaway'){
            $fi1 = '2'; $fi2 = '6'; $fi3 = '7'; $fi4 = '9'; 
        }else if($req->filter == 'delivery'){
            $fi1 = '3'; $fi2 = '7'; $fi3 = '8'; $fi4 = '9'; 
        }else if($req->filter == 'tableRez'){
            $fi1 = '5'; $fi2 = '6'; $fi3 = '8'; $fi4 = '9'; 
        }


        $serWor = $req->wor;
        
        $korrORT = DB::table('plzort')->where('ort', 'LIKE', "%{$serWor}%")->first();
        if($korrORT != NULL){
            $search = $korrORT->plz;
        }else{
            $search = $serWor;
        }

    
        $restorants = Restorant::
                where('emri', 'LIKE', "%{$search}%")
            ->orWhere('adresa', 'LIKE', "%{$search}%")
            ->orWhere('deliveryFor', 'LIKE', "%{$search}%")
            ->orderBy('updated_at', 'desc')->paginate(30);

            
        $filterIds = array();
      
        foreach($restorants as $resOne){
            if($resOne->resType == $fi1 || $resOne->resType == $fi2 || $resOne->resType == $fi3 || $resOne->resType == $fi4){
                array_push($filterIds, $resOne->id);
            }
        }

        $restorantsFiltered = Restorant::whereIn('id', $filterIds)->get();

        return view('firstPage/searchRestaurants', ['restorants'=> $restorantsFiltered, 'searchWord' => $serWor]);

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
    public function destroy($id)
    {
        //
    }
}
