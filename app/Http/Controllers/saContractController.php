<?php

namespace App\Http\Controllers;


use PDF;
use QRCode;
use App\User;
use App\Restorant;
use App\contractRes;
use App\TableQrcode;
use Illuminate\Http\Request;
use App\accessControllForAdmins;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;

class saContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('sa.saContract.saConHome');
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
    public function store(Request $req){
        $nContract = new contractRes();
        $nContract->gender = $req->addNCon_gender;
        $nContract->name = $req->addNCon_name;
        $nContract->lastname = $req->addNCon_lastname;
        $nContract->street = $req->addNCon_street;
        $nContract->plz = $req->addNCon_plz;
        $nContract->ort = $req->addNCon_ort;
        $nContract->company = $req->addNCon_company;
        $nContract->phoneNr = $req->addNCon_phoneNr;
        $nContract->email = $req->addNCon_email;

        if($req->addNCon_bankNr == 0){
            $bankNr = "CH111111111111111111111111";
            $nContract->bankNumber = $bankNr;
        }else{
            $bankNr = "CH";
            foreach(explode('-',$req->addNCon_bankNr) as $bnPart){
                $bankNr .= $bnPart;
            }
            $nContract->bankNumber = $bankNr;
        }

        $nContract->tavolinatFormat = $req->addNCon_tables;
        $nContract->tablesCope = $req->addNCon_tablesCope;
        $nContract->tablesPerMonth = $req->addNCon_tablesPerMonth;
        $nContract->tablesProvision = $req->addNCon_tablesProvision;
        $nContract->tablesFixedPerMonth = $req->addNCon_tablesFixedPerMonth;
        $nContract->tablesPercentage = $req->addNCon_tablesPercentage;

        $nContract->flyerCost = $req->flyerCost;

        $nContract->TakeawayPerMonth = $req->addNCon_TakeawayPerMonth;
        $nContract->TakeawayProvision = $req->addNCon_TakeawayProvision;
        $nContract->TakeawayFixedPerMonth = $req->addNCon_TakeawayFixedPerMonth;
        $nContract->TakeawayPercentage = $req->addNCon_TakeawayPercentage;

        $nContract->DeliveryPerMonth = $req->addNCon_DeliveryPerMonth;
        $nContract->DeliveryProvision = $req->addNCon_DeliveryProvision;
        $nContract->DeliveryFixedPerMonth = $req->addNCon_DeliveryFixedPerMonth;
        $nContract->DeliveryPercentage = $req->addNCon_DeliveryPercentage;

        $nContract->TischreservierungPerMonth = $req->addNCon_TischreservierungPerMonth;
        $nContract->WarenwirtschaftPerMonth = $req->addNCon_WarenwirtschaftPerMonth;
        $nContract->PersonalvertretungPerMonth = $req->addNCon_PersonalvertretungPerMonth;

        $nContract->VertragsaufzeitPercentage = $req->addNCon_VertragsaufzeitPercentage;
        $nContract->VertragsaufzeitYear = $req->addNCon_VertragsaufzeitYear;

        $nContract->totalPerMonth = $req->totalPerMonth;
        $nContract->total = $req->total;
        $nContract->tablesPercentageTOT = $req->tablesPercentageTOT;
        $nContract->takeawayPercentageTOT = $req->takeawayPercentageTOT;
        $nContract->DeliveryPercentageTOT = $req->DeliveryPercentageTOT;

        if($req->hasFile('addNCon_menuFile')){
            $fNO_menu = $req->file('addNCon_menuFile')->getClientOriginalName();
            $fN_menu = pathinfo($fNO_menu, PATHINFO_FILENAME);
            $ex_menu = $req->file('addNCon_menuFile')->getClientOriginalExtension();
            $fNS_menu = $fN_menu.'_'.time().'.'.$ex_menu;
            $path_menu = $req->file('addNCon_menuFile')->move('storage/contractFiles', $fNS_menu);
            $nContract->menuFile = $fNS_menu;
        }else{ $nContract->menuFile = 'none'; }

        if($req->hasFile('addNCon_UIDFile')){
            $fNO_uid = $req->file('addNCon_UIDFile')->getClientOriginalName();
            $fN_uid = pathinfo($fNO_uid, PATHINFO_FILENAME);
            $ex_uid = $req->file('addNCon_UIDFile')->getClientOriginalExtension();
            $fNS_uid = $fN_uid.'_'.time().'.'.$ex_uid;
            $path_uid = $req->file('addNCon_UIDFile')->move('storage/contractFiles', $fNS_uid);
            $nContract->UIDFile = $fNS_uid;
        }else{ $nContract->UIDFile = 'none'; }

        if($req->hasFile('addNCon_PassIDFile')){
            $fNO_passId = $req->file('addNCon_PassIDFile')->getClientOriginalName();
            $fN_passId = pathinfo($fNO_passId, PATHINFO_FILENAME);
            $ex_passId = $req->file('addNCon_PassIDFile')->getClientOriginalExtension();
            $fNS_passId = $fN_passId.'_'.time().'.'.$ex_passId;
            $path_passId = $req->file('addNCon_PassIDFile')->move('storage/contractFiles', $fNS_passId);
            $nContract->PassIDFile = $fNS_passId;
        }else{ $nContract->PassIDFile = 'none'; }

        $nContract->theComment = $req->addNCon_theComment;
        $nContract->ortOnTheEnd = $req->addNCon_ortOnTheEnd;
        $nContract->dateOnTheEnd = $req->addNCon_dateOnTheEnd;

        $folderPath = public_path('storage/contractFiles/');       
        $image_parts = explode(";base64,", $req->signed);             
        $image_type_aux = explode("image/", $image_parts[0]);           
        $image_type = $image_type_aux[1];           
        $image_base64 = base64_decode($image_parts[1]); 
        $signature = uniqid() . '.'.$image_type;           
        $file = $folderPath . $signature;
        file_put_contents($file, $image_base64);

        $nContract->clSignature = $signature;
        $nContract->fromConMng = $req->addNCon_fromConMng;

        $nContract->save();

        // Register the restorant 
        $theNewRest = new Restorant();
        $theNewRest->emri = $req->addNCon_company;
        $theNewRest->adresa = $req->addNCon_street.' , '.$req->addNCon_plz.' '.$req->addNCon_ort.', Switzerland';
        $theNewRest->resBankId = $bankNr;
        $theNewRest->save();

        if($req->addNCon_TakeawayPerMonth == 0 && $req->addNCon_TakeawayProvision == 0 && $req->addNCon_TakeawayFixedPerMonth == 0 && $req->addNCon_TakeawayPercentage == 0){
            $hasTakeawayAccess = 0;
        }else{ $hasTakeawayAccess = 1; }

        if($req->addNCon_DeliveryPerMonth == 0 && $req->addNCon_DeliveryProvision == 0 && $req->addNCon_DeliveryFixedPerMonth == 0 && $req->addNCon_DeliveryPercentage == 0){
            $hasDeliveryAccess = 0;
        }else{ $hasDeliveryAccess = 1; }

        if($req->addNCon_TischreservierungPerMonth == 0){
            $hasTableRezAccess = 0;
        }else{ $hasTableRezAccess = 1; }

        if($req->addNCon_WarenwirtschaftPerMonth == 0){
            $hasInvMngAccess = 0;
        }else{ $hasInvMngAccess = 1; }

        if($req->addNCon_PersonalvertretungPerMonth == 0){
            $hasStafRepresentAccess = 0;
        }else{ $hasStafRepresentAccess = 1; }


        // give basic access to te restaurant and admins 
        //  + register the admins 
        if($req->addNCon_newRegAdmins != ''){
            foreach(explode('--9--',$req->addNCon_newRegAdmins) as $oneNewAdm){
                $oneNewAdm2D = explode('||',$oneNewAdm);
                $newAdm = new User();
                $newAdm->name = $oneNewAdm2D[0];
                $newAdm->email = $oneNewAdm2D[1];
                $newAdm->role = 5;
                $newAdm->sFor = $theNewRest->id;
                $newAdm->password = bcrypt($oneNewAdm2D[2]);
                $newAdm->passChngRequ = 1;
                $newAdm->save();

                $accessCon01 = new accessControllForAdmins();   $accessCon01->userId = $newAdm->id; $accessCon01->forRes = $theNewRest->id; 
                $accessCon01->accessDsc = 'Statistiken';        $accessCon01->accessValid = 1;      $accessCon01->save();

                $accessCon02 = new accessControllForAdmins();   $accessCon02->userId = $newAdm->id; $accessCon02->forRes = $theNewRest->id; 
                $accessCon02->accessDsc = 'Aufträge';        $accessCon02->accessValid = 1;      $accessCon02->save();
 
                $accessCon03 = new accessControllForAdmins();   $accessCon03->userId = $newAdm->id; $accessCon03->forRes = $theNewRest->id; 
                $accessCon03->accessDsc = 'Empfohlen';        $accessCon03->accessValid = 1;      $accessCon03->save();
                 
                $accessCon03 = new accessControllForAdmins();   $accessCon03->userId = $newAdm->id; $accessCon03->forRes = $theNewRest->id; 
                $accessCon03->accessDsc = 'RechnungMngAcce';        $accessCon03->accessValid = 1;      $accessCon03->save();
                
                $accessCon04 = new accessControllForAdmins();   $accessCon04->userId = $newAdm->id; $accessCon04->forRes = $theNewRest->id; 
                $accessCon04->accessDsc = 'Kellner';        $accessCon04->accessValid = 1;      $accessCon04->save();

                $accessCon05 = new accessControllForAdmins();   $accessCon05->userId = $newAdm->id; $accessCon05->forRes = $theNewRest->id; 
                $accessCon05->accessDsc = 'Products';        $accessCon05->accessValid = 1;      $accessCon05->save();

                $accessCon06 = new accessControllForAdmins();   $accessCon06->userId = $newAdm->id; $accessCon06->forRes = $theNewRest->id; 
                $accessCon06->accessDsc = 'Tabellenwechsel';        $accessCon06->accessValid = 1;      $accessCon06->save();

                $accessCon07 = new accessControllForAdmins();   $accessCon07->userId = $newAdm->id; $accessCon07->forRes = $theNewRest->id; 
                $accessCon07->accessDsc = 'Trinkgeld';        $accessCon07->accessValid = 1;      $accessCon07->save();

                $accessCon08 = new accessControllForAdmins();   $accessCon08->userId = $newAdm->id; $accessCon08->forRes = $theNewRest->id; 
                $accessCon08->accessDsc = 'Frei';        $accessCon08->accessValid = 1;      $accessCon08->save();

                $accessCon09 = new accessControllForAdmins();   $accessCon09->userId = $newAdm->id; $accessCon09->forRes = $theNewRest->id; 
                $accessCon09->accessDsc = '16+/18+';        $accessCon09->accessValid = 1;      $accessCon09->save();

                $accessCon10 = new accessControllForAdmins();   $accessCon10->userId = $newAdm->id; $accessCon10->forRes = $theNewRest->id; 
                $accessCon10->accessDsc = 'Gutscheincode';        $accessCon10->accessValid = 1;      $accessCon10->save();

                if($hasTakeawayAccess == 1){
                    $accessCon11 = new accessControllForAdmins();   $accessCon11->userId = $newAdm->id; $accessCon11->forRes = $theNewRest->id; 
                    $accessCon11->accessDsc = 'Takeaway';        $accessCon11->accessValid = 1;      $accessCon11->save();
                }

                if($hasDeliveryAccess == 1){
                    $accessCon12 = new accessControllForAdmins();   $accessCon12->userId = $newAdm->id; $accessCon12->forRes = $theNewRest->id; 
                    $accessCon12->accessDsc = 'Delivery';        $accessCon12->accessValid = 1;      $accessCon12->save();
                }

                $accessCon13 = new accessControllForAdmins();   $accessCon13->userId = $newAdm->id; $accessCon13->forRes = $theNewRest->id; 
                $accessCon13->accessDsc = 'Tischkapazität';        $accessCon13->accessValid = 1;      $accessCon13->save();

                if($hasTableRezAccess == 1){
                    $accessCon14 = new accessControllForAdmins();   $accessCon14->userId = $newAdm->id; $accessCon14->forRes = $theNewRest->id; 
                    $accessCon14->accessDsc = 'Tischreservierungen';        $accessCon14->accessValid = 1;      $accessCon14->save();
                }

                $accessCon15 = new accessControllForAdmins();   $accessCon15->userId = $newAdm->id; $accessCon15->forRes = $theNewRest->id; 
                $accessCon15->accessDsc = 'Dienstleistungen';        $accessCon15->accessValid = 1;      $accessCon15->save();

                $accessCon16 = new accessControllForAdmins();   $accessCon16->userId = $newAdm->id; $accessCon16->forRes = $theNewRest->id; 
                $accessCon16->accessDsc = 'Statusarbeiter';        $accessCon16->accessValid = 1;      $accessCon16->save();

                $accessCon17 = new accessControllForAdmins();   $accessCon17->userId = $newAdm->id; $accessCon17->forRes = $theNewRest->id; 
                $accessCon17->accessDsc = 'Covid-19';        $accessCon17->accessValid = 1;      $accessCon17->save();
            }
        }

        // Qr code and table "regThiTable()"
        foreach(explode(',',$req->addNCon_tables) as $oneTF){
            if (strpos($oneTF, '-') == false) {
                $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
                shuffle($word);
                $name = substr(implode($word), 0, 45).'Table'.(int)$oneTF.'Res'.$theNewRest->id;
                $file = "storage/qrcode/".$name.".png";
        
                $word2 = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'));
                shuffle($word2);
                $hash = substr(implode($word2), 0, 128);
        
                $newQrcode = QRCode::URL('qrorpa.ch/?t='.(int)$oneTF.'&Res='.$theNewRest->id)
                ->setSize(32)
                ->setMargin(0)
                ->setOutfile($file)
                ->png();
        
                $tQrcode = new TableQrcode;
                $img1 = Image::make('storage/qrcode/'.$name.'.png');
                $img1->insert('storage/qrcodeNr/number'.(int)$oneTF.'.png');
                $img1->save('storage/qrcode/'.$name.'.png');
                $tQrcode->path = $name.'.png';
                $tQrcode->tableNr = (int)$oneTF;
                $tQrcode->Restaurant = $theNewRest->id;
                $tQrcode->hash = $hash;
                $tQrcode->save();
                
            }else{
                $oneTF2D = explode('-', $oneTF);
                for($i = $oneTF2D[0]; $i<=$oneTF2D[1]; $i++){
                    $word = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
                    shuffle($word);
                    $name = substr(implode($word), 0, 45).'Table'.(int)$i.'Res'.$theNewRest->id;
                    $file = "storage/qrcode/".$name.".png";
            
                    $word2 = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'), range('A', 'Z'), range('a', 'z'), range('0', '9'), range('a', 'z'));
                    shuffle($word2);
                    $hash = substr(implode($word2), 0, 128);
            
                    $newQrcode = QRCode::URL('qrorpa.ch/?t='.(int)$i.'&Res='.$theNewRest->id)
                    ->setSize(32)
                    ->setMargin(0)
                    ->setOutfile($file)
                    ->png();
            
                    $tQrcode = new TableQrcode;
                    $img1 = Image::make('storage/qrcode/'.$name.'.png');
                    $img1->insert('storage/qrcodeNr/number'.(int)$i.'.png');
                    $img1->save('storage/qrcode/'.$name.'.png');
                    $tQrcode->path = $name.'.png';
                    $tQrcode->tableNr = (int)$i;
                    $tQrcode->Restaurant = $theNewRest->id;
                    $tQrcode->hash = $hash;
                    $tQrcode->save();
                }
            }
        }

        // Send the ThankYou email ...
        $to_name = $nContract->name." ".$nContract->lastname;
        $to_email = str_replace(' ', '', $nContract->email); 
        $data = array('name'=>$to_name, 'gender'=>$nContract->gender, 'name'=>$to_name);

        $pdf = PDF::loadView('emails.contractPDF', ['data' => $nContract]);

        Mail::send('emails.contractPDFMail', $data, function($message) use ($to_name, $to_email, $pdf, $nContract) {
            $message->to($to_email, $to_name)
                         ->subject('Vertrag');
            $message->from('noreply@qrorpa.ch','Qrorpa');
            $message->attachData($pdf->output(), 'Vertrag-'.$nContract->id.'-QRorpa-'.$nContract->dateOnTheEnd.'.pdf', ['mime' => 'application/pdf',]);
        });

        return redirect()->back();
    }


    public function sendConToEmail(Request $req){
        // Send the ThankYou email ...
        $theCon = contractRes::findOrFail($req->id);
        $to_name = $theCon->name." ".$theCon->lastname;
        $to_email = str_replace(' ', '', $theCon->email); 
        $data = array('name'=>$to_name, 'gender'=>$theCon->gender, 'name'=>$to_name);
          
        $pdf = PDF::loadView('emails.contractPDF', ['data' => $theCon]);

        Mail::send('emails.contractPDFMail', $data, function($message) use ($to_name, $to_email, $pdf, $theCon) {
            $message->to($to_email, $to_name)
                         ->subject('Vertrag');
            $message->from('noreply@qrorpa.ch','Qrorpa');
            $message->attachData($pdf->output(), 'Vertrag-'.$theCon->id.'-QRorpa-'.$theCon->dateOnTheEnd.'.pdf', [
                'mime' => 'application/pdf',
            ]);
        });
    }

    public function getPDFcontract(Request $req){
        $data = contractRes::find($req->conId);
     
        $pdf = PDF::loadView('emails.contractPDF', compact('data'))->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('Vertrag-#'.$data->id .'.pdf');
    }


    public function checkEmailUse(Request $req){
        if(User::where('email',$req->em)->first() != NULL){
            return 'yes';
        }else{  
            return 'no';
        }
    }
}
