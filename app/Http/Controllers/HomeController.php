<?php

namespace App\Http\Controllers;

use App\DeliveryProd;
use Illuminate\Http\Request;
use App\Orders;
use App\Restorant;
use App\Takeaway;
use App\TableQrcode;
use App\TabOrder;
use App\logTabAutoRemove;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $porosit = Orders::where('byId', Auth::user()->id)->get()->sortByDesc('created_at');
        return view('home')->with('porosit', $porosit);
    }

    public function indexConRegUser(){ return view('userSA/userSAIndex'); }
    public function indexConRegUserBox(){ return view('userSA/userSABox'); }
    public function indexConRegUserCat(){ return view('userSA/userSACat'); }
    public function indexConRegUserType(){ return view('userSA/userSAType'); }
    public function indexConRegUserExtra(){ return view('userSA/userSAExtra'); }
    public function indexConRegUserProduct(){ return view('userSA/userSAProduct'); }

    public function indexConRegUserTable(){ return view('userSA/userSATable'); }

    public function saRestorantOne(){ return view('sa/superAdminIndex'); }

    public function rex31323334FirstPage(){ 
        
        return view('res343536FirstPage/pageOne'); 
    }

    public function openRes45BillTabletPage(){ return view('res45BillTabletPage'); }
    
    public function browserCheckIncognito(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['browserChecked'] = 'isIncognito' ; 
    }
    public function browserCheckIncognitoIsNot(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['browserChecked'] = 'isNotIncognito' ; 
    }

    public function scanQRCodePageOpen(){ return view('qrCodeScan/firstPage'); }

    public function refIdFillInScr266(){
        foreach(Restorant::all() as $ores){
            $refId = 1;
            foreach(Orders::where('Restaurant',$ores->id)->orderBy('id')->get() as $oorder){
                $oorder->refId = $refId++;
                $oorder->save();
            }
        }
    }

    public function setNewTvshValues(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if(isset($_GET['hash']) && $_GET['hash'] == 'cHTtdm3412^!@3$dsA)56(HjYpO' &&  $ip == '37.35.71.58'){
            foreach(Takeaway::all() as $oneTA){
                if($oneTA->mwstForPro == 7.70){
                    $oneTA->mwstForPro = 8.10;
                    echo $oneTA->id.'T# => 7.70 - 8.10 .<br>';
                    $oneTA->save();
                }else if($oneTA->mwstForPro == 2.50){
                    $oneTA->mwstForPro = 2.60;
                    echo $oneTA->id.'T# => 2.50 - 2.60 <br>';
                    $oneTA->save();
                }
            }
            foreach(DeliveryProd::all() as $oneDE){
                if($oneDE->mwstForPro == 7.70){
                    $oneDE->mwstForPro = 8.10;
                    echo $oneDE->id.'D# => 7.70 - 8.10 <br>';
                    $oneDE->save();
                }else if($oneDE->mwstForPro == 2.50){
                    $oneDE->mwstForPro = 2.60;
                    echo $oneDE->id.'D# => 2.50 - 2.60 <br>';
                    $oneDE->save();
                }
            }
        }else{
            echo 'You do not have access to use this API (your ip: '.$ip.')';
        }
    }

    public function cleanInvalideTabOnTable(Request $req){
        if(isset($_GET['sHash']) && $_GET['sHash'] == '4f9c2a7d8e1b6c3a9d5e7f0b2c4a8e6d1c3f9b7a2d5e8c1f6a4b9d2e7c5f0a1'){
            foreach(TableQrcode::where('kaTab','!=','0')->get() as $actTableOne){
                $otherOrdersOnThisTable = TabOrder::where([['toRes',$actTableOne->Restaurant],['tableNr',$actTableOne->tableNr],['tabCode','!=',$actTableOne->kaTab]])->get();
                foreach($otherOrdersOnThisTable as $otherOrdersOnThisTableOne){
                    if($otherOrdersOnThisTableOne->tabCode != 0){

                        $newLog = new logTabAutoRemove();
                        $newLog->toRes = $otherOrdersOnThisTableOne->toRes ;
                        $newLog->tableNr = $otherOrdersOnThisTableOne->tableNr;
                        $newLog->tabId = $otherOrdersOnThisTableOne->id;
                        $newLog->permb = 'InvalideTAB--TabC:'.$otherOrdersOnThisTableOne->tabCode.'--ProdId:'.$otherOrdersOnThisTableOne->prodId.'--OrdEmri:'.$otherOrdersOnThisTableOne->OrderSasia.'x '.$otherOrdersOnThisTableOne->OrderEmri.'--Time'.$otherOrdersOnThisTableOne->created_at;
                        $newLog->save();

                        $otherOrdersOnThisTableOne->delete();
                    }
                }
            }
        }
    }
}
