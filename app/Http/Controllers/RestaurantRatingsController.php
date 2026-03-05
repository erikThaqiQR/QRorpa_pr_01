<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\RestaurantRating;
use App\User;
use App\Piket;
use App\PiketLog;
use DB;

use App\BarbershopRating;

class RestaurantRatingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRatings(){
        return view('index');
    }
    public function index()
    {
        //
       /* $ratings = RestaurantRating::all()->orderBy('created_at');

        return view('')*/

        $restaurantsRatings = RestaurantRating::all()->sortByDesc('created_at');
        return view('sa/superAdminIndex', ['restaurantsRatings'=> $restaurantsRatings]);


    }

    public function BarbershopRatingSA(){
        return view('sa/superAdminIndex');
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
        
      /*  $ratings = new RestaurantRating([
                    'restaurant_id' => $request->get('restaurant_id'),
                    'nickname' => $request->get('nickname'),
                    'stars' => $request->get('stars'),
                    'comment' => $request->get('comment'),
                    'verified' => $request->get('verified'),
                ]);
                $ratings->save();*/
    

        $ratings = new RestaurantRating;
        
        $ratings->nickname = $request->nickname;
        $ratings->title = $request->title;
        $ratings->email = $request->email;
        $ratings->stars = $request->stars;
        $ratings->comment = $request->comment;      
        $ratings->restaurant_id = $request->restaurant_id;
        $ratings->verified = $request->verified;
        $ratings->byId = $request->clientBy;

        if($request->clientBy != 0){
            $fTime = RestaurantRating::where('byId',$request->clientBy)->first();
        }
       
        if(isset($fTime) && $fTime != null ){
            $ratings->save();
            return response()->json(['success'=>'Wir haben Ihre Bewertung erhalten, vielen Dank!']);
        }elseif(!Auth::check()){
            $ratings->save();
            return response()->json(['success'=>'Wir haben Ihre Bewertung erhalten, vielen Dank!']);
        }else{
            $ratings->for100 = 1;
            $ratings->save();
            return response()->json(['success'=>'Wir haben Ihre Bewertung erhalten, vielen Dank!(+100 Punkte "nach Überprüfung")']);

        }


        /*return redirect()->back()->with('success','Wir haben Ihre Bewertung erhalten, vielen Dank!');*/
         /*$data = $request->all();*/
        

      
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
    public function confirmRating($id)
    {
        $restaurantRating = RestaurantRating::find($id);

        if($restaurantRating->verified == 0){

            $restaurantRating->verified = 1;
            $restaurantRating->save();

            if($restaurantRating->for100 == 1){

        

                $SearchUser = Piket::where('klienti_u',$restaurantRating->byId)->first();
                if($SearchUser == null){
                    $SearchUser = new Piket;
                    $SearchUser->klienti_u = $restaurantRating->byId;
                    $SearchUser->piket = 100;
                    $SearchUser->level = 1;
                    $SearchUser->save();
                }else{
                    $SearchUser->piket = $SearchUser->piket + 100;
                    $SearchUser->save();
                }

                $PLog = new PiketLog;

                $PLog->shumaPor = 0;
                $PLog->toRes = $restaurantRating->restaurant_id;
                $PLog->klienti_u = $restaurantRating->byId;
                $PLog->order_u = 0;
                $PLog->piket = 100;
                $PLog->payM = 'none';

                $PLog->save();


            }
        }
        
        return redirect()->route('restaurantsRatings.index');
    }








    public function storeBarbershopR(Request $req){
        $barRating = new BarbershopRating;

        $barRating->title = $req->title;
        $barRating->nickname = $req->nickname;
        $barRating->email = $req->email;
        $barRating->stars = $req->stars;
        $barRating->comment = $req->comment;
        $barRating->bar_id = $req->bar_id;
        $barRating->byID = $req->clientBy;
        $barRating->verified = 0;
        $barRating->save();

        return 'success';
    }

    public function verifyBarbershopR(Request $req){
        $barRating = BarbershopRating::find($req->id);
        $barRating->verified = 1;
        $barRating->save();
    }

    public function deleteBarbershopR(Request $req){
        BarbershopRating::find($req->id)->delete();
    }
    
}
