<?php

namespace App\Http\Controllers;

use App\cntGroupAdmWai;
use App\cntGroupL2AdmWai;
use App\waAdCatGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdmGroupController extends Controller
{
    public function waiSaveGrL1F(Request $req){
        $ngl1 = new cntGroupAdmWai();
        $ngl1->groupName = $req->groupN;
        $ngl1->forUser = Auth::user()->id;
        $ngl1->toRes = Auth::user()->sFor;
        $ngl1->save();
    }

    public function waiSaveGrL2F(Request $req){
        $ngl2 = new cntGroupL2AdmWai();
        $ngl2->groupL2Name = $req->groupL2N;
        $ngl2->forUser = Auth::user()->id;
        $ngl2->toRes = Auth::user()->sFor;
        $ngl2->toGroup = $req->groupId;
        $ngl2->save();
    }

    public function deleteGrL1(Request $req){
        $delGrL1 = cntGroupAdmWai::find($req->grL1Id);
        if($delGrL1 != NULL){
            foreach(cntGroupL2AdmWai::where('toGroup',$delGrL1->id)->get() as $delGrL2){
                if($delGrL2 != NULL){
                    $delGrL2->delete();
                }
            }
            foreach(waAdCatGroups::where('groupId', $delGrL1->id)->get() as $katToGr){
                if($katToGr != NULL){
                    $katToGr->delete();
                }
            }
            $delGrL1->delete();
        }
    }

    public function deleteGrL2(Request $req){
        $delGrL2 = cntGroupL2AdmWai::find($req->grL2Id);
        foreach(waAdCatGroups::where('groupL2Id', $delGrL2->id)->get() as $katToGr){
            if($katToGr != NULL){
                $katToGr->delete();
            }
        }
        $delGrL2->delete();
    }

    public function setCateToGroup(Request $req){
        $newCatToGr = new waAdCatGroups();
        $newCatToGr->forUser = Auth::user()->id;
        $newCatToGr->groupId = $req->grL1Id;
        $newCatToGr->groupL2Id = $req->grL2Id;
        $newCatToGr->forRes = Auth::user()->sFor;
        $newCatToGr->forCat = $req->katId;
        $newCatToGr->save();
    }

    public function changeCateToGroup(Request $req){
        $catToGr = waAdCatGroups::find($req->catToGRId);
        if($catToGr != NULL){
            $catToGr->groupId = $req->grL1Id;
            $catToGr->groupL2Id = $req->grL2Id;
            $catToGr->save();
        }
    }

    public function copyGrToWaiters(Request $req){
        foreach(waAdCatGroups::where('forUser',$req->waiId)->get() as $wg){ if($wg != NULL){ $wg->delete(); }}
        foreach(waAdCatGroups::where('forUser',Auth::user()->id)->get() as $ag){ 
            if($ag != NULL){ 
                $nwgr = new waAdCatGroups();
                $nwgr->forUser = $req->waiId;
                $nwgr->groupId = $ag->groupId;
                $nwgr->groupL2Id = $ag->groupL2Id;
                $nwgr->forRes = $ag->forRes;
                $nwgr->forCat = $ag->forCat;
                $nwgr->save();
            }
        }
    }


    
}
