<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RestaurantOffer;
use App\Restorant;
use Auth;
use App\User;
use DB;
use Validator;
use Mail;
use PDF;
use DataTables;

class RestaurantOffersController extends Controller
{
    public function index(Request $request)
    {


   
        if(Auth::check()){
        $restaurantOffers = DB::table('restaurant_offers')->join('users', 'restaurant_offers.user_id', '=', 'users.id')->where('user_id','=', Auth::user()->id)->select('restaurant_offers.*', 'users.name as userName', 'users.sFor as sfor')->orderBy('restaurant_offers.id', 'DESC')->get();

      
        return view('adminPanel/adminIndex', ['restaurantOffers'=> $restaurantOffers]);
            }
            else{
                return redirect('/login');
            }

       
    }


    public function SAindex()
    {

        return view('sa/superAdminIndex');
       
    }
    public function getData(){ 
        $restaurantOffers  = RestaurantOffer::with('user')->orderBy('created_at', 'DESC');
          

            return Datatables::of($restaurantOffers)
            // New "hidden" column
->addColumn('status_color', ' ')
->addColumn('user_status_color', ' ')
             ->addColumn('action', function($offer){
                return ' <table border="0" cellspacing="5" cellpadding="5" id="actionButtons">
                        <tr>
                        <td>
                         <a href="" id="'.$offer->id.'" target="_blank" class="btn btn-secondary btn-sm tooltip2 edit" style="background-color: #0095ff;" data-toggle="modal" data-target="#editContract"><i class="fa fa-pencil" aria-hidden="true"></i><span class="tooltiptext">Edit</span></a>
                <a href="'.route("emails.contractPdf", $offer->id).'" target="_blank" class="btn btn-secondary btn-sm tooltip2"><i class="fa fa-print" aria-hidden="true"></i> <span class="tooltiptext">Print</span></a>
                <a href="'.route("sendEmail", $offer->id).'" target="_blank" class="btn btn-secondary btn-sm tooltip2" style="background-color: #58984c;"><i class="fa fa-envelope-o" aria-hidden="true"></i><span class="tooltiptext">E-Mail senden</span></a>
                      
                          </td>                          
                        </tr>
                        </table>';
            })
             ->editColumn('status_color', function ($row) {
                    return $row->contractStatus && RestaurantOffer::STATUS_COLOR[$row->contractStatus] ? RestaurantOffer::STATUS_COLOR[$row->contractStatus] : 'none';
                })
->editColumn('user_status_color', function ($row) {
    return $row->userPayment && RestaurantOffer::USER_STATUS_COLOR[$row->userPayment] ? RestaurantOffer::USER_STATUS_COLOR[$row->userPayment] : 'none';
})                
            ->make(true);
    }
    function fetchdata(Request $request)
    {
        $id = $request->input('id');
        $data = RestaurantOffer::find($id);
        $output = array(
            'monthlySum'    =>  $data->monthlySum,
            'userProfitPercentage'    =>  $data->userProfitPercentage,
            'userProfit'    =>  $data->userProfit,
            'discount'    =>  $data->discount,
            'discountSum'    =>  $data->discountSum,
            'total'    =>  $data->total,
            'contractStatus'    =>  $data->contractStatus,
            'userPayment'    =>  $data->userPayment,
        );
        echo json_encode($output);
    }

    function updateContract(Request $request){
         $validation = Validator::make($request->all(), [
            'userProfitPercentage' => 'nullable',
            'userProfit' => 'nullable',
            'contractStatus' => 'nullable',
            'userPayment' => 'nullable',
            ]);

        $error_array = array();
        $success_output = '';

        $messages = [
          'address.required' => 'We need to know your e-mail address!',
      ];
      if ($validation->fails())
        {
            foreach ($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages; 
            }

        }
    else
        {
        $contract = RestaurantOffer::find($request->get('contract_id'));
                $contract->userProfitPercentage = $request->get('userProfitPercentage');
                $contract->userProfit = $request->get('userProfit');              
                $contract->contractStatus = $request->get('contractStatus'); 
                $contract->userPayment = $request->get('userPayment'); 
           
                $contract->save();
                $success_output = '<div class="alert alert-success">Die Daten wurden geändert</div>';
            }

                $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
      //return redirect('/restaurantOffers')->with('success','Contract editted successfully');
    }
    public function store(Request $request)
    {
       
        $folderPath = public_path('storage/offerPic/');       
        $image_parts = explode(";base64,", $request->signed);             
        $image_type_aux = explode("image/", $image_parts[0]);           
        $image_type = $image_type_aux[1];           
        $image_base64 = base64_decode($image_parts[1]); 
        $signature = uniqid() . '.'.$image_type;           
        $file = $folderPath . $signature;
 
        file_put_contents($file, $image_base64);       

        $data = new RestaurantOffer;

         $data["name"] = $request->get("name");
         $data["surname"] = $request->get("surname");
         $data["street"] = $request->get("street");
         $data["plz"] = $request->get("plz");
         $data["ort"] = $request->get("ort");
         $data["company"] = $request->get("company");
         $data["tel"] = $request->get("tel");
         $data["email"] = str_replace(' ', '', $request->get("email"));
         $data["monthlyCharges"] = $request->get("monthlyCharges");
         $data["customMonthlyCharges"] = $request->get("monthlyCharges2");
         $data["monthlyChargesType"] = $request->get("monthlyChargesType");
         $data["monthlyChargesQty"] = $request->get("monthlyTextInput");
         $data["customMonthlyChargesQty"] = $request->get("customMonthlyChargesQty");        
        
         
         $data["discount"] = $request->get("discount");
         $data["discountSum"] = $request->get("discountSum");
         $data["monthlySum"] = $request->get("monthlySum");
         $data["total"] = $request->get("total");
         $data["comment"] = $request->get("comment");
         $data["ortSignature"] = $request->get("ortSignature");
         $data["dateSignature"] = $request->get("dateSignature");
         $data["signatureClient"] = $signature;
         $data["user_Id"] = Auth::user()->id;



          if (!empty($request->input('checkBoxTextInput'))) {
              $data["additionalOptionsSum"] = implode(",", $request->get("additionalOptionsSum"));
              $data["additionalOptions"] = $request->get("checkBoxTextInput");
         }
         else{
            $data["additionalOptions"] = 'Keine';
              $data["additionalOptionsSum"] = '';
         }


        

        $data->save();
        $pdf = PDF::loadView('emails.contractPdf', ['data' => $data]);
        Mail::send('emails.contractBodyMail', ['data' => $data], function($message)use ($data, $pdf){
            $message->from('info@kreativeidee.ch');
            $message->to($data["email"], $data["email"]);
            $message->subject('Anfrage');
            $message->attachData($pdf->output(), 'Vertrag-#'.$data["id"] .'.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });

        return redirect('/contracts')->with('success','Contract added successfully');
      
    }

    //Store function - Tel
    public function storeTel(Request $request)
    {
        $folderPath = public_path('storage/offerPic/');       
        $image_parts = explode(";base64,", $request->signedTel);             
        $image_type_aux = explode("image/", $image_parts[0]);           
        $image_type = $image_type_aux[1];           
        $image_base64 = base64_decode($image_parts[1]); 
        $signature = uniqid() . '.'.$image_type;           
        $file = $folderPath . $signature; 
        file_put_contents($file, $image_base64);       

        $data = new RestaurantOffer;

         $data["name"] = $request->get("nameTel");
         $data["surname"] = $request->get("surnameTel");
         $data["street"] = $request->get("streetTel");
         $data["plz"] = $request->get("plzTel");
         $data["ort"] = $request->get("ortTel");
         $data["company"] = $request->get("companyTel");
         $data["tel"] = $request->get("telTel");
         $data["email"] = str_replace(' ', '', $request->get("emailTel"));
         $data["monthlyCharges"] = $request->get("monthlyChargesTel");
         $data["customMonthlyCharges"] = $request->get("customMonthlyChargesTel");
         $data["monthlyChargesType"] = $request->get("monthlyChargesTelType");
         $data["monthlyChargesQty"] = $request->get("monthlyChargesQtyTel");
         $data["customMonthlyChargesQty"] = $request->get("customMonthlyChargesQtyTel");        
        
         
         $data["discount"] = $request->get("discountTel");
         $data["discountSum"] = $request->get("discountSumTel");
         $data["monthlySum"] = $request->get("monthlySumTel");
         $data["total"] = $request->get("totalTel");
         $data["comment"] = $request->get("commentTel");
         $data["ortSignature"] = $request->get("ortSignatureTel");
         $data["dateSignature"] = $request->get("dateSignatureTel");
         $data["signatureClient"] = $signature;
         $data["user_Id"] = Auth::user()->id;

          if (!empty($request->input('checkBoxTextInputTel'))) {
              $data["additionalOptionsSum"] = implode(",", $request->get("additionalOptionsSumTel"));
              $data["additionalOptions"] = $request->get("checkBoxTextInputTel");
         }
         else{
            $data["additionalOptions"] = 'Keine';
              $data["additionalOptionsSum"] = '';
         }

        

        $data->save();
        $pdf = PDF::loadView('emails.contractPdf', ['data' => $data]);
        Mail::send('emails.contractBodyMail', ['data' => $data], function($message)use ($data, $pdf){
            $message->from('info@kreativeidee.ch');
            $message->to($data["email"], $data["email"]);
            $message->subject('Vertrag');
            $message->attachData($pdf->output(), 'Vertrag-#'.$data->id .'.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });

        return redirect('/contracts')->with('success','Contract added successfully');
      
    }



    public function generateInvoice($id)
    {
        $data = RestaurantOffer::find($id);
     
        $pdf = PDF::loadView('emails.contractPdf', compact('data'))->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream('Vertrag-#'.$data->id .'.pdf');
    }
    public function sendEmail($id)
    {
        $data = RestaurantOffer::find($id);


        $pdf = PDF::loadView('emails.contractPdf', ['data' => $data]);
        Mail::send('emails.contractBodyMail', ['data' => $data], function($message)use ($data, $pdf){
        $message->from('info@kreativeidee.ch');
        $message->to($data["email"], $data["email"]);
        $message->subject('Anfrage');
        $message->attachData($pdf->output(), 'Vertrag-#'.$data->id .'.pdf', [
                'mime' => 'application/pdf',
            ]);
        });
         return redirect('/contracts')->with('success','Contract added successfully');
      

        
    }
    
        
}
