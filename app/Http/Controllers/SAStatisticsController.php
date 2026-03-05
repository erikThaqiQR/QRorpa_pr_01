<?php

namespace App\Http\Controllers;

use App\RestaurantCover;
use App\Restorant;
use App\SAStatistics;
use Illuminate\Http\Request;

class SAStatisticsController extends Controller
{
    public function index(){
        return view('sa/superAdminIndex');
    }
    public function indexRes(){
        return view('sa/superAdminIndex');
    }

  

    public function einloggenClicksOne(){
        $theSAS = SAStatistics::find(1);
        $theSAS->EinloggenClicks += 1;
        $theSAS->save();
    }
    public function registerClicksOne(){
        $theSAS = SAStatistics::find(1);
        $theSAS->RegisterClicks += 1;
        $theSAS->save();
    }


// Menu options clicks
    public function SAPageOpenOne(){
        $theSAS = SAStatistics::find(1);
        $theSAS->SAPageOpen += 1;
        $theSAS->save();
    }
    public function APageOpenOne(){
        $theSAS = SAStatistics::find(1);
        $theSAS->APageOpen += 1;
        $theSAS->save();
    }
    public function WaiterCallsOpenOne(){
        $theSAS = SAStatistics::find(1);
        $theSAS->WaiterCallsOpen += 1;
        $theSAS->save();
    }
    public function CartOpenOne(){
        $theSAS = SAStatistics::find(1);
        $theSAS->CartOpen += 1;
        $theSAS->save();
    }
    public function MyOrdersOpenOne(){
        $theSAS = SAStatistics::find(1);
        $theSAS->MyOrdersOpen += 1;
        $theSAS->save();
    }
    public function TrackOrderOpenOne(){
        $theSAS = SAStatistics::find(1);
        $theSAS->TrackOrderOpen += 1;
        $theSAS->save();
    }
    public function Covid19OpenOne(){
        $theSAS = SAStatistics::find(1);
        $theSAS->Covid19Open += 1;
        $theSAS->save();
    }
    public function ProfileOpenOne(){
        $theSAS = SAStatistics::find(1);
        $theSAS->ProfileOpen += 1;
        $theSAS->save();
    }

    public function BannerClickOne(Request $req){
        $theSAS = SAStatistics::find(1);
        $theSAS->BannerClick += 1;
        $theSAS->save();

        $theRes = Restorant::find($req->id);
        $theRes->BannerClick += 1;
        $theRes->save();

    }

    public function BannerClickLinkOne(Request $req){
        $theSAS = SAStatistics::find(1);
        $theSAS->BannerLinkClick += 1;
        $theSAS->save();

        $theRes = Restorant::find($req->resId);
        $theRes->BannerLinkClick += 1;
        $theRes->save();

        $theResCover = RestaurantCover::find($req->resCoId);
        $theResCover->linkClick += 1;
        $theResCover->save();
    }
// ----------------------------------------------------------
}
