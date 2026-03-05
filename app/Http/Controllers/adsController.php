<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\adsMod;
use App\adsActiveToRes;
use App\adsRepeatInterval;
use App\adsRestaurantGroup;
use App\DeliveryProd;
use App\kategori;
use App\Produktet;
use App\Restorant;
use App\Takeaway;
use InfyOm\Generator\Commands\Publish\PublishBaseCommand;

class adsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
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
    public function storeProduct(Request $request){
      
        $newAD = new adsMod;

         //get name .etc
         $fileNameOriginal = $request->file('foto')->getClientOriginalName();
         //get just the name
         $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
         // get extension
         $extension = $request->file('foto')->getClientOriginalExtension();
         $fileNameStore = $fileName.'_'.time().'.'.$extension;
         // Upload
         $path = $request->file('foto')->move('storage/restaurantADS', $fileNameStore);

         $newAD->tipi = 1;
         $newAD->foto = $fileNameStore;
         $newAD->emri = $request->emri;
         $newAD->linku = 'none';
         $newAD->informata ='none';
         $newAD->catEmri = 'none';

         $newAD->save();

         return redirect('/adsModuleSaIndex');
    }
    public function storeLink(Request $request){
         $newAD = new adsMod;

         //get name .etc
         $fileNameOriginal = $request->file('foto')->getClientOriginalName();
         //get just the name
         $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
         // get extension
         $extension = $request->file('foto')->getClientOriginalExtension();
         $fileNameStore = $fileName.'_'.time().'.'.$extension;
         // Upload
         $path = $request->file('foto')->move('storage/restaurantADS', $fileNameStore);

         $newAD->tipi = 2;
         $newAD->foto = $fileNameStore;
         $newAD->emri = 'none';
         $newAD->linku = $request->linku;
         $newAD->informata ='none';
         $newAD->catEmri = 'none';

         $newAD->save();

         return redirect('/adsModuleSaIndex');
    }
    public function storeInfo(Request $request){
        // teksti foto
        $newAD = new adsMod;
        //get name .etc
        $fileNameOriginal = $request->file('foto')->getClientOriginalName();
        //get just the name
        $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
        // get extension
        $extension = $request->file('foto')->getClientOriginalExtension();
        $fileNameStore = $fileName.'_'.time().'.'.$extension;
        // Upload
        $path = $request->file('foto')->move('storage/restaurantADS', $fileNameStore);

        $newAD->tipi = 3;
        $newAD->foto = $fileNameStore;
        $newAD->emri = 'none';
        $newAD->linku = 'none';
        $newAD->informata = $request->teksti;
        $newAD->catEmri = 'none';

        $newAD->save();

        return redirect('/adsModuleSaIndex');
    }


    public function storeCategory(Request $request){
        // foto kategoriEmri
        $newAD = new adsMod;
        //get name .etc
        $fileNameOriginal = $request->file('foto')->getClientOriginalName();
        //get just the name
        $fileName = pathinfo($fileNameOriginal, PATHINFO_FILENAME);
        // get extension
        $extension = $request->file('foto')->getClientOriginalExtension();
        $fileNameStore = $fileName.'_'.time().'.'.$extension;
        // Upload
        $path = $request->file('foto')->move('storage/restaurantADS', $fileNameStore);

        $newAD->tipi = 4;
        $newAD->foto = $fileNameStore;
        $newAD->emri = 'none';
        $newAD->linku = 'none';
        $newAD->informata = 'none';
        $newAD->catEmri = $request->kategoriEmri;

        $newAD->save();

        return redirect('/adsModuleSaIndex');
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
    public function edit($id){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adDestroy(Request $req){
        // adI
        if(adsActiveToRes::where('adID',$req->adI)->get()->count() > 0){
            foreach(adsActiveToRes::where('adID',$req->adI)->get() as $adToResOne){
                $adToResOne->delete();
            }
        }
        adsMod::find($req->adI)->delete();
    }
























    public function getAdsForMenu(Request $req){
        $theRes = (int)$req->resI;
        // $allActiveAds = adsActiveToRes::where([['resID',$theRes],['resActive','1']])->get();
        $allActiveAdsCount = adsActiveToRes::where([['resID',$theRes],['resActive','1']])->get()->count();
        if($allActiveAdsCount > 0){
            if($allActiveAdsCount == 1){
                // Send the Ad
                $adRes = adsActiveToRes::where([['resID',$theRes],['resActive','1']])->first();
                $adToSend =adsMod::find($adRes->adID);
                $adToSave =adsMod::find($adRes->adID);

                if($adToSend->tipi == 1){
                    $theProduct = Produktet::where([['emri',$adToSend->emri],['toRes',$theRes]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$theRes]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }
            }else if($allActiveAdsCount > 1){
                // Send One random Ad
                $nr = rand(1,1);
                $count = 1;
                foreach(adsActiveToRes::where([['resID',$theRes],['resActive','1']])->get() as $adToC){
                    if($count == $nr){
                        $adChos = $adToC->adID;
                        break;
                    }else{
                        $count++;
                    }
                }
                $adToSend = adsMod::find($adChos);
                $adToSave = adsMod::find($adChos);
                if($adToSend->tipi == 1){
                    $theProduct = Produktet::where([['emri',$adToSend->emri],['toRes',$theRes]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$theRes]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                         // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }  
            }else{
                // No Ad
                return json_encode('none');
            }
        }else{
            // No Ad
            return json_encode('none');
        }
    }


    public function getAdsForMenuRepeatable(Request $req){
        $allActiveAds = adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['resActive','1']])->get();
        $allActiveAdsCount = adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['resActive','1']])->get()->count();
        if($allActiveAdsCount > 0){
            if($allActiveAdsCount == 1){
                // Send the Ad
                $adRes = adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['resActive','1']])->first();
                $adToSend =adsMod::find($adRes->adID);
                $adToSave =adsMod::find($adRes->adID);

                if($adToSend->tipi == 1){
                    $theProduct = Produktet::where([['emri',$adToSend->emri],['toRes',$req->resI]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$req->resI]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }
            }else if($allActiveAdsCount > 1){
                // Send One random Ad
                $nr = rand(1,$allActiveAdsCount);
                $count = 1;
                foreach(adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['resActive','1']])->get() as $adToC){
                    if($count == $nr){
                        $adChos = $adToC->adID;
                        break;
                    }else{
                        $count++;
                    }
                }
                $adToSend = adsMod::find($adChos);
                $adToSave = adsMod::find($adChos);
                if($adToSend->tipi == 1){
                    $theProduct = Produktet::where([['emri',$adToSend->emri],['toRes',$req->resI]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$req->resI]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }
            }else{
                // No Ad
                return json_encode('none');
            }
        }else{
            // No Ad
            return json_encode('none');
        }
    }
























    // Thirrja e reklamave per DELIVERY

    
    public function getAdsForMenuDelivery(Request $req){
        $allActiveAds = adsActiveToRes::where([['resID',$req->resI],['delActive','1']])->get();
        $allActiveAdsCount = adsActiveToRes::where([['resID',$req->resI],['delActive','1']])->get()->count();
        if($allActiveAdsCount > 0){
            if($allActiveAdsCount == 1){
                // Send the Ad
                $adRes = adsActiveToRes::where([['resID',$req->resI],['delActive','1']])->first();
                $adToSend =adsMod::find($adRes->adID);
                $adToSave =adsMod::find($adRes->adID);

                if($adToSend->tipi == 1){
                    $theProduct = DeliveryProd::where([['emri',$adToSend->emri],['toRes',$req->resI]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$req->resI]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }
            }else if($allActiveAdsCount > 1){
                // Send One random Ad
                $nr = rand(1,$allActiveAdsCount);
                $count = 1;
                foreach(adsActiveToRes::where([['resID',$req->resI],['delActive','1']])->get() as $adToC){
                    if($count == $nr){
                        $adChos = $adToC->adID;
                        break;
                    }else{
                        $count++;
                    }
                }
                $adToSend = adsMod::find($adChos);
                $adToSave = adsMod::find($adChos);
                if($adToSend->tipi == 1){
                    $theProduct = DeliveryProd::where([['emri',$adToSend->emri],['toRes',$req->resI]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                        // return $this->getAdsForMenuDelivery($req);
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$req->resI]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                         // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }  
            }else{
                // No Ad
                return json_encode('none');
            }
        }else{
            // No Ad
            return json_encode('none');
        }
    }
    public function getAdsForMenuDeliveryRepeatable(Request $req){
        $allActiveAds = adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['delActive','1']])->get();
        $allActiveAdsCount = adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['delActive','1']])->get()->count();
        if($allActiveAdsCount > 0){
            if($allActiveAdsCount == 1){
                // Send the Ad
                $adRes = adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['delActive','1']])->first();
                $adToSend =adsMod::find($adRes->adID);
                $adToSave =adsMod::find($adRes->adID);

                if($adToSend->tipi == 1){
                    $theProduct = DeliveryProd::where([['emri',$adToSend->emri],['toRes',$req->resI]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$req->resI]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                         // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }
            }else if($allActiveAdsCount > 1){
                // Send One random Ad
                $nr = rand(1,$allActiveAdsCount);
                $count = 1;
                foreach(adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['delActive','1']])->get() as $adToC){
                    if($count == $nr){
                        $adChos = $adToC->adID;
                        break;
                    }else{
                        $count++;
                    }
                }
                $adToSend = adsMod::find($adChos);
                $adToSave = adsMod::find($adChos);
                if($adToSend->tipi == 1){
                    $theProduct = DeliveryProd::where([['emri',$adToSend->emri],['toRes',$req->resI]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$req->resI]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                         // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }
            }else{
                // No Ad
                return json_encode('none');
            }
        }else{
            // No Ad
            return json_encode('none');
        }
    }



























    

    // Thirrja e reklamave per TAKEAWAY

    
    public function getAdsForMenuTakeaway(Request $req){
        $allActiveAds = adsActiveToRes::where([['resID',$req->resI],['takActive','1']])->get();
        $allActiveAdsCount = adsActiveToRes::where([['resID',$req->resI],['takActive','1']])->get()->count();
        if($allActiveAdsCount > 0){
            if($allActiveAdsCount == 1){
                // Send the Ad
                $adRes = adsActiveToRes::where([['resID',$req->resI],['takActive','1']])->first();
                $adToSend =adsMod::find($adRes->adID);
                $adToSave =adsMod::find($adRes->adID);

                if($adToSend->tipi == 1){
                    $theProduct = Takeaway::where([['emri',$adToSend->emri],['toRes',$req->resI]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$req->resI]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                         // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }
            }else if($allActiveAdsCount > 1){
                // Send One random Ad
                $nr = rand(1,$allActiveAdsCount);
                $count = 1;
                foreach(adsActiveToRes::where([['resID',$req->resI],['takActive','1']])->get() as $adToC){
                    if($count == $nr){
                        $adChos = $adToC->adID;
                        break;
                    }else{
                        $count++;
                    }
                }
                $adToSend = adsMod::find($adChos);
                $adToSave = adsMod::find($adChos);
                if($adToSend->tipi == 1){
                    $theProduct = Takeaway::where([['emri',$adToSend->emri],['toRes',$req->resI]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                        // return $this->getAdsForMenuDelivery($req);
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$req->resI]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                         // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }  
            }else{ 
                // No Ad
                return json_encode('none');
            }
        }else{
            // No Ad
            return json_encode('none');
        }
    }
    public function getAdsForMenuTakeawayRepeatable(Request $req){
        $allActiveAds = adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['takActive','1']])->get();
        $allActiveAdsCount = adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['takActive','1']])->get()->count();
        if($allActiveAdsCount > 0){
            if($allActiveAdsCount == 1){
                // Send the Ad
                $adRes = adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['takActive','1']])->first();
                $adToSend =adsMod::find($adRes->adID);
                $adToSave =adsMod::find($adRes->adID);

                if($adToSend->tipi == 1){
                    $theProduct = Takeaway::where([['emri',$adToSend->emri],['toRes',$req->resI]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$req->resI]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                         // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }
            }else if($allActiveAdsCount > 1){
                // Send One random Ad
                $nr = rand(1,$allActiveAdsCount);
                $count = 1;
                foreach(adsActiveToRes::where([['resID',$req->resI],['repeatableAd','1'],['takActive','1']])->get() as $adToC){
                    if($count == $nr){
                        $adChos = $adToC->adID;
                        break;
                    }else{
                        $count++;
                    }
                }
                $adToSend = adsMod::find($adChos);
                $adToSave = adsMod::find($adChos);
                if($adToSend->tipi == 1){
                    $theProduct = Takeaway::where([['emri',$adToSend->emri],['toRes',$req->resI]])->first();
                    if($theProduct != NULL){
                        $adToSend->prodId = $theProduct->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                        // No Ad "Product not found"
                        return json_encode('none');
                    }
                }else if($adToSend->tipi == 2 || $adToSend->tipi == 3){
                    $adToSave->adRepNr = $adToSave->adRepNr + 1;
                    $adToSave->save();
                    return json_encode($adToSend);
                }else if($adToSend->tipi == 4){
                    $theKat = kategori::where([['emri',$adToSend->catEmri],['toRes',$req->resI]])->first();
                    if($theKat != NULL){
                        $adToSend->catId = $theKat->id;
                        $adToSave->adRepNr = $adToSave->adRepNr + 1;
                        $adToSave->save();
                        return json_encode($adToSend);
                    }else{
                         // No Ad "Category not found"
                        return json_encode('none');
                    } 
                }
            }else{
                // No Ad
                return json_encode('none');
            }
        }else{
            // No Ad
            return json_encode('none');
        }
    }








































    public function checkIfResHasRepeat(Request $req){
        $repInterval = adsRepeatInterval::where('toRes',$req->resI)->first();

        if( $repInterval != NULL){
            return json_encode($repInterval);
        }else{
            return json_encode('empty');
        }
    }




    public function saveTheadRepeatRestaurants(Request $req){
        //        restaurants: $('#resToSaveRepeat').val(),
        // seconds: $('#secondsFor').val(),

        foreach(explode('||',$req->restaurants) as $resOne){
            $ari = new adsRepeatInterval;
            $ari->toRes = $resOne;
            $ari->forSec = $req->seconds;
            $ari->save();
        }
    }

    public function deleteTheadRepeatRestaurants(Request $req){
        adsRepeatInterval::find($req->adRepResI)->delete();
    }



























    public function adToAllResAdd(Request $req){
        $theAd = adsMod::find($req->adI);

        foreach (Restorant::all() as $resOne ){
            if(adsActiveToRes::where([['adID',$req->adI],['resID',$resOne->id]])->first() == NULL){
                $newATR = new adsActiveToRes;
                $newATR->adID = $req->adI;
                $newATR->resID = $resOne->id;
                $newATR->resActive = $theAd->resActive;
                $newATR->delActive = $theAd->delActive;
                $newATR->takActive = $theAd->takActive;
                $newATR->save();
            }
        }
    }

    public function adToAllResRemove(Request $req){
        foreach(adsActiveToRes::where('adID',$req->adI)->get() as $adResRec){
            $adResRec->delete();
        }
    }



    public function adToResAdd(Request $req){
        $theAd = adsMod::find($req->adI);

        $newATR = new adsActiveToRes;
        $newATR->adID = $req->adI;
        $newATR->resID = $req->resI;
        $newATR->resActive = $theAd->resActive;
        $newATR->delActive = $theAd->delActive;
        $newATR->takActive = $theAd->takActive;
        $newATR->save();
    }
    public function adToResRemove(Request $req){
        adsActiveToRes::find($req->adResI)->delete();
    }













    public function changeRepeatableStat(Request $req){
        $theAd = adsMod::find($req->adI);
        if($theAd->repeatableAd == 0){
            $theAd->repeatableAd = 1;
            foreach(adsActiveToRes::where('adID',$theAd->id)->get() as $activeAd){
                $activeAd->repeatableAd = 1;
                $activeAd->save();
            }
        }else{
            $theAd->repeatableAd = 0;
            foreach(adsActiveToRes::where('adID',$theAd->id)->get() as $activeAd){
                $activeAd->repeatableAd = 0;
                $activeAd->save();
            }
        }
        $theAd->save();
    }























    // Grupi

    public function saveResGroup(Request $req){
        // resToGroup: ('#resToSaveGroup').val(),
        // resGroupName: $('#resGroupName').val(),

        $newRG = new adsRestaurantGroup;
        $newRG->restaurants = $req->resToGroup;
        $newRG->groupName = $req->resGroupName;
        $newRG->save();
    }

    public function deleteResGroup(Request $req){
        adsRestaurantGroup::find($req->rgI)->delete();
    }

    public function resGroupToAdSave(Request $req){
        // adI: adId,
        // rgI: rgId,
        if(adsActiveToRes::where('adID',$req->adI)->get()->count() > 0){
            foreach(adsActiveToRes::where('adID',$req->adI)->get() as $activeATR){
                $activeATR->delete();
            }
        }
        $resGr = adsRestaurantGroup::find($req->rgI);
        foreach(explode('||',$resGr->restaurants) as $resInGrId){
            $newAdAcRes = new adsActiveToRes;
            $newAdAcRes->adID = $req->adI;
            $newAdAcRes->resID = $resInGrId;
            $newAdAcRes->save();
        }
        $theAd = adsMod::find($req->adI);
        $theAd->grSelected = $req->rgI;
        $theAd->save();
    }

    public function unsubAdNgaGrupi(Request $req){
        // grI: grId,
        // adI: adId,

        $resGr = adsRestaurantGroup::find($req->grI);
        foreach(explode('||',$resGr->restaurants) as $resInGrId){
            adsActiveToRes::where([['adID',$req->adI],['resID',$resInGrId]])->first()->delete();
        }

        $theAd = adsMod::find($req->adI);
        $theAd->grSelected = 0;
        $theAd->save();
    }






















    public function changeTheAdAvailability(Request $req){
        // chT: chType,
        // adI: adId,
        $theAdv = adsMod::find($req->adI);
        if($req->chT == 'res'){
            if($theAdv->resActive == 0){ 
                $theAdv->resActive = 1;
                foreach(adsActiveToRes::where('adID',$req->adI)->get() as $adAcRes){
                    $adAcRes->resActive = 1;
                    $adAcRes->save();
                }
            }else{ 
                $theAdv->resActive = 0; 
                foreach(adsActiveToRes::where('adID',$req->adI)->get() as $adAcRes){
                    $adAcRes->resActive = 0;
                    $adAcRes->save();
                }
            }
            $theAdv->save(); 

        }else if($req->chT == 'del'){
            if($theAdv->delActive == 0){ 
                $theAdv->delActive = 1;
                foreach(adsActiveToRes::where('adID',$req->adI)->get() as $adAcRes){
                    $adAcRes->delActive = 1;
                    $adAcRes->save();
                }
            }
            else{ 
                $theAdv->delActive = 0;
                foreach(adsActiveToRes::where('adID',$req->adI)->get() as $adAcRes){
                    $adAcRes->delActive = 0;
                    $adAcRes->save();
                }
            }
            $theAdv->save(); 

        }else if($req->chT == 'tak'){
            if($theAdv->takActive == 0){ 
                $theAdv->takActive = 1;
                foreach(adsActiveToRes::where('adID',$req->adI)->get() as $adAcRes){
                    $adAcRes->takActive = 1;
                    $adAcRes->save();
                }
            }
            else{ 
                $theAdv->takActive = 0; 
                foreach(adsActiveToRes::where('adID',$req->adI)->get() as $adAcRes){
                    $adAcRes->takActive = 0;
                    $adAcRes->save();
                }
            }
            $theAdv->save(); 

        }
    }
}
