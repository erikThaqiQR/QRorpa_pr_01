<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RestaurantCover;
use App\Restorant;
use Auth;
use App\User;
use DB;
use Validator;

use App\BarbershopBanner;

class RestaurantCoversController extends Controller
{
    //
     public function index()
    {

        $restaurantCovers = RestaurantCover::all()->sortByDesc('created_at');
        $restaurantsList = Restorant::all()->sortByDesc('created_at');
      /*  $restaurantsCoversList = DB::table('restaurant_covers')
            ->join('restorants', 'restaurant_covers.res_id', '=', 'restorants.id')
            ->select('restaurant_covers.*','restorants.emri')
            ->get();*/
       
        return view('sa/superAdminIndex', ['restaurantCovers'=> $restaurantCovers,'restaurantsList'=> $restaurantsList]);
    }
    public function store(Request $request)
    {
       
        $fileNameOriginal = $request->file('image')->getClientOriginalName();
  
        $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
      
        $extension = $request->file('image')->getClientOriginalExtension();

        $fileNameStore = $fileName.'_'.time().'.'.$extension;
      
         $path = $request->file('image')->move('storage/ResBackgroundPic', $fileNameStore);

        $restaurantCovers = new RestaurantCover;
        $restaurantCovers->res_id = $request->input('res_id');
        $restaurantCovers->image = $fileNameStore;
        $restaurantCovers->text = $request->input('text');
        $restaurantCovers->link = $request->input('link');
        $restaurantCovers->status = 0;
        $restaurantCovers->position = $request->input('position');

        $restaurantCovers->save();

        return redirect('/restaurantCovers')->with('success','Cover added successfully');
      
    }
     public function update(Request $request, $id)
    {
        $this->validate($request, [
            'text' => 'nullable',
            'link' => 'nullable',
            'position' => 'nullable',
        ]);
        $editCover = RestaurantCover::find($id);

        if($request->file('image') != null){
            //get name .etc
            $fileNameOriginal = $request->file('image')->getClientOriginalName();
            //get just the name
            $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
            // get extension
            $extension = $request->file('image')->getClientOriginalExtension();

            $fileNameStore = $fileName.'_'.time().'.'.$extension;
            // Upload
            $path = $request->file('image')->move('storage/ResBackgroundPic', $fileNameStore);

            $editCover->image = $fileNameStore;
        }

        $editCover->text = $request->input('text');
        $editCover->link = $request->input('link');
        $editCover->position = $request->input('position');
        $editCover->save();

        return redirect('/restaurantCovers')->with('success','Cover edited successfully');

    }
    public function destroy($id, Request $req)
    {
        RestaurantCover::find($id)->delete();
       return redirect('/restaurantCovers')->with('success','Cover deleted successfully');
    }
    public function activateCover($id){
        $activatecover = RestaurantCover::find($id);
        if ($activatecover->status == 0) {
            $activatecover->status = 1;
        }
        else{
            $activatecover->status = 0;
        }
      
        $activatecover->save();
         return redirect('/restaurantCovers')->with('success','Cover deleted successfully');
    }




















    public function storeBarbershop(Request $request){

        $fileNameOriginal = $request->file('image')->getClientOriginalName();
  
        $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
      
        $extension = $request->file('image')->getClientOriginalExtension();

        $fileNameStore = $fileName.'_'.time().'.'.$extension;
      
         $path = $request->file('image')->move('storage/BarBackgroundPic', $fileNameStore);

        $barBanner = new BarbershopBanner;
        $barBanner->Bar_id = $request->input('bar_id');
        $barBanner->B_pic = $fileNameStore;
        $barBanner->B_text = $request->input('text');
        $barBanner->B_link = $request->input('link');
        $barBanner->B_status = 0;
        $barBanner->B_position = $request->input('position');

        $barBanner->save();

        return redirect('/barbershopbannerSAPage?barbershop')->with('success','Cover added successfully');
    }


    public function deleteBarbershop(Request $req){
        BarbershopBanner::find($req->id)->delete();
    }
   


    public function editBarbershop(Request $request){
        $barBanner = BarbershopBanner::find($request->input('id'));
        $barBanner->Bar_id = $request->input('bar_id');
        $barBanner->B_text = $request->input('text');
        $barBanner->B_link = $request->input('link');
        $barBanner->B_position = $request->input('position');

        $barBanner->save();

        return redirect('/barbershopbannerSAPage?barbershop');
    }

}
