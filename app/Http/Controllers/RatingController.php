<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Rating;

class RatingController extends Controller
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
        $ratings = Rating::all()->sortByDesc('created_at');
        $countRatings5 = Rating::select('stars')->where('stars' , 5)->count();
        $countRatings4 = Rating::select('stars')->where('stars' , 4)->count();
        $countRatings3 = Rating::select('stars')->where('stars' , 3)->count();
        $countRatings2 = Rating::select('stars')->where('stars' , 2)->count();
        $countRatings1 = Rating::select('stars')->where('stars' , 1)->count();
        return view('sa/superAdminIndex', ['ratings'=> $ratings, 'countRatings5' => $countRatings5, 'countRatings4' => $countRatings4, 'countRatings3' => $countRatings3, 'countRatings2' => $countRatings2, 'countRatings1' => $countRatings1]);

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
        // theKom: $('#theSendBtnStarComment').val(),
        // stars: $('input[name="stars"]:checked').val(),
        // fromAjax: 'yes';
        if($request->fromAjax == 'yes'){
            $ratings = new Rating;
            $ratings->stars = $request->stars;
            $ratings->comment = $request->theKom;

            $ratings->save();

            return 'done';
        }else{
            $ratings = new Rating;
            $ratings->stars = $request->input('stars');
            $ratings->comment = $request->input('comment');

            $ratings->save();

            return redirect()->back()->with('success','Vielen Dank, dass Sie QRorpa Systeme bewertet haben. So können wir uns ständig verbessern!');
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
    public function destroy($id)
    {
        //
    }
}
