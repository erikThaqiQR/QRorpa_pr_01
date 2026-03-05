        <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

<?php
    use App\Orders;
    use App\Restorant;
                           

?>

<style>
.checked {
  color: orange !important;
}
span.fa.fa-star{
  color: #464545;
}
.table{
  margin-top:50px;
}
/*#signaturePad{
  width: 100%;
  height: 400px;
}*/
.contractData td{
  padding-bottom: 0px !important;
    padding-top: 0px !important;
    border-top: none !important;
}
.plzTable td{
  padding-bottom: 0px !important;
    padding-top: 0px !important;
    border-top: none !important;
}
table.table.plzTable {
    margin-top: -20px;
}
.monthlyCostTable td{
    padding-bottom: 0px !important;
    padding-top: 0px !important;
    border-top: 1px dashed #dee2e6;
}
.monthlyCostTable tbody{
  border: 1px solid #3a3838;
}
.right-td{
  border-right: 1px solid #3a3838;
  border-left: 1px dotted #c3c3c3;
}
.left-td{
  border-left: 1px dotted #c3c3c3;
}
table.table.monthlyCostTable {
    margin-top: -1px;
}

.contractTable td{
  padding-bottom: 0px !important;
    padding-top: 0px !important;
    border-top: 1px dashed #dee2e6;
}
.contractTable tbody{
  border: 1px solid #3a3838;
}
table.table.contractTable {
    margin-top: -1px;
}




.monthlyText{
    border: 1px solid #3a3838;
}
.monthlyTitle{
  font-size: 15px;
    font-weight: bold;
    background: #c1c1c1;
    padding: 5px;
    margin-bottom: 0px;
}
input#monthlyChargesTelType, input#monthlyChargesTel {
    width: auto;
    height: auto;
    display: inline;
}

td.right-td, td.left-td{
  padding-top:5px !important;
}
span.checkIcon {
    color: #8bf98f;
}
.col-md-12{
  padding: 15px; 
  float:  left;
}

.tooltip2 {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip2 .tooltiptext {
  visibility: hidden;
  width: 108px;
background-color: black;
color: #fff;
text-align: center;
border-radius: 6px;
padding: 5px 0;
position: absolute;
z-index: 1;
left: -20px;
top: -30px;
}

.tooltip2:hover .tooltiptext {
  visibility: visible;
}
.label-default{
  display: inline;
    padding: .2em .6em .3em;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25em;
    background: #27beaf;
}


.b-qrorpa.col-md-4 {
    float: left;
    width: 32%;
    margin: 10px;
}
.top5table td{
  color:#fff;
  font-size: 17px;
}

.modal{
    overflow: scroll;
   }
.col-md-6 {
    float: left;
    background: #27beaf;
    margin: 5px;
    width: 47%;
    color: #fff;
    padding: 15px;
}
.col-md-4, .col-md-8 {
    float: left;
    margin: 5px;
    width: 47%;
    padding: 15px;
}
.resultValues{
    border-radius: 50%;
    background: #ffffff;
    display: inline;
    color: #27beaf;
    padding: 0px 10px 8px 10px;
    font-weight: bold;
  }
  .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter{
    text-align: left;
  }
  .signatureAreaTd {
    display: block;
  }


</style>


    <div class="col-md-12">
      @php

              $restaurantOffers = DB::table('restaurant_offers')->join('users', 'restaurant_offers.user_id', '=', 'users.id')->where('restaurant_offers.user_id', '=', Auth::user()->id)->select('restaurant_offers.*', 'users.name as userName')->orderBy('restaurant_offers.id', 'DESC')->get();
                      @endphp
        
      <div class="col-md-6">
      <h5>{{__('adminP.all')}}</h5>         
        <p class="resultValues">{{$restaurantOffers->count()}}</p>
      </div>
      <div class="col-md-6">
        <h5>{{__('adminP.total')}}</h5>
        <p class="resultValues">{{__('adminP.currencyShow')}}{{$restaurantOffers->sum('userProfit')}}</p>

      </div>
    </div>
    <div class="col-md-12">
      @php

             $paid = DB::table('restaurant_offers')->join('users', 'restaurant_offers.user_id', '=', 'users.id')->where('restaurant_offers.user_id', '=', Auth::user()->id)->where('userPayment','=','Bezahlt')->select('restaurant_offers.*', 'users.name as userName')->orderBy('restaurant_offers.id', 'DESC')->get();
               $unpaid = DB::table('restaurant_offers')->join('users', 'restaurant_offers.user_id', '=', 'users.id')->where('restaurant_offers.user_id', '=', Auth::user()->id)->where('userPayment','=','Unbezahlt')->select('restaurant_offers.*', 'users.name as userName')->orderBy('restaurant_offers.id', 'DESC')->get();
                      @endphp 
      <div class="col-md-6">
         <h5>{{__('adminP.paid')}}</h5>
        <p class="resultValues">{{$paid->count()}}</p>
      </div>
      <div class="col-md-6">
        <h5>{{__('adminP.unpaid')}}</h5>
        <p class="resultValues">{{$unpaid->count()}}</p>

      </div>
      
    </div>

    
    <div class="pt-4 pl-4 pr-4 pb-5">
      <div class="col-md-12">
                   <span style="font-size:17px; cursor: pointer" class="b-qrorpa color-white pr-3 p-2 pl-3 br-25" data-toggle="modal" data-target="#addOfferTel">{{__('adminP.addContract')}}</span>
            </div>

       
     <div class="col-md-12">
<table class="table table-striped table-bordered table-responsive" width="100%" id="datatableTel">
  
  <thead>
    <tr>
      <th>{{__('adminP.id')}}</th>
      <th>{{__('adminP.name')}}</th>
      <th>{{__('adminP.firstName')}}</th>
      <th>{{__('adminP.streetNr')}}</th>
      <th>{{__('adminP.postCode')}}</th>
      <th>{{__('adminP.location')}}</th>
      <th>{{__('adminP.company')}}</th>
      <th>{{__('adminP.phoneNumber')}}</th>
      <th>{{__('adminP.email')}}</th>
      <th>{{__('adminP.profit')}}</th>
      <th>{{__('adminP.status')}}</th>
      <th>{{__('adminP.date')}}</th>
      <th>{{__('adminP.date')}}</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($restaurantOffers as $resOffers)
      <tr>
        <td>

                {{$resOffers->id}}
        

        </td>
         <td>

                {{$resOffers->name}}
        

        </td>
        <td>

                {{$resOffers->surname}}
        

        </td>
        <td>

                {{$resOffers->street}}
        

        </td>
        <td>

                {{$resOffers->plz}}
        

        </td>
        <td>

                {{$resOffers->ort}}
        

        </td>
        <td>

                {{$resOffers->company}}
        

        </td>
        <td>

                {{$resOffers->tel}}
        

        </td>
        <td>

                {{$resOffers->email}}
        

        </td>
       <td>
         {{$resOffers->userProfit}}
        </td>
       

          @if($resOffers->userPayment == "Unbezahlt")
              <td style="background: red; color:#fff;">{{$resOffers->userPayment}}  </td>
                                          @else
                                            <td style="background: #58984c; color:#fff;">{{$resOffers->userPayment}}  </td>
                                            @endif

     
      
        <td>
        	
        	{{ date('d-m-Y', strtotime($resOffers->dateSignature))}}
        	
        </td>
        <td>




          <a href="{{ route('emails.contractPdf', $resOffers->id) }}" target="_blank" class="btn btn-secondary btn-sm tooltip2"><i class="fa fa-print" aria-hidden="true"></i> <span class="tooltiptext">{{__('adminP.print')}}</span></a>
          <a href="{{ route('sendEmail', $resOffers->id) }}" target="_blank" class="btn btn-secondary btn-sm tooltip2" style="background-color: #58984c;"><i class="fa fa-envelope-o" aria-hidden="true"></i><span class="tooltiptext">{{__('adminP.sendEmail')}}</span></a>
        </td>
      
      </tr>
      @endforeach
      

  </tbody>
</table>
</div>
</div>

 <!-- The Modal  Add Vertrag-->
    <div class="modal  fade" id="addOfferTel"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
        <div class="modal-dialog modal-lg" style="max-width: 100%">
            <div class="modal-content" style="border-radius:30px;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title color-qrorpa"><strong>{{__('adminP.addContract')}}</strong></h4>
                <button type="button" class="close noOutline" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
            </div>

                <!-- Modal body -->
                <div class="modal-body">  
                    {{Form::open(['action' => 'RestaurantOffersController@storeTel', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}
                    {{-- <form action="{{ action('RestaurantOffersController@store') }}" method="post"  enctype="multipart/form-data" > --}}
                        {{csrf_field()}}


                        <table class="table contractData">
                            <tr>
                              <td> 
                                <div class="form-group">
                                  {{ Form::label(__('adminP.name'), null, ['class' => 'control-label color-black']) }}
                                 {{--  <input type="text" name="name" id="name" class="form-control" required="" value="" data-error="{{__(''adminP.fillOutNameField)}}"  oninvalid="this.setCustomValidity({{__('adminP.fillOutNameField')}})"> --}}
                                  <input type="text" name="nameTel" id="nameTel" class="form-control" oninvalid="setCustomValidity({{__('adminP.fillOutNameField')}})"
  oninput="setCustomValidity('')" 
  required >
           
                                
                                 {{--  {{ Form::text('name','', ['class' => 'form-control ','required' => 'required'] ) }} --}}
                                </div>
                              </td>
                              <td>
                                 <div class="form-group">
                                  {{ Form::label(__('adminP.location'), null, ['class' => 'control-label color-black']) }}
                                  {{-- <input type="text" name="ort" id="ort" class="form-control"  required="" value="" oninvalid="this.setCustomValidity({{__('adminP.plsFillOutCityField')}})"> --}}
                                  <input type="text" name="ortTel" id="ortTel" class="form-control" oninvalid="setCustomValidity({{__('adminP.plsFillOutCityField')}})"
  oninput="setCustomValidity('')" 
  required >
                                
                                 
                                  {{-- {{ Form::text('ort','', ['class' => 'form-control ','required' => 'required']) }} --}}
                              </div>
                               
                              </td>
                            </tr>
                             <tr>
                              <td>
                                 <div class="form-group">
                                  {{ Form::label(__('adminP.firstName'), null, ['class' => 'control-label color-black']) }}
                                   <input type="text" name="surnameTel" id="surnameTel" class="form-control" oninvalid="setCustomValidity({{__('adminP.plsFillOutFirstNameField')}})"
  oninput="setCustomValidity('')" required>
                                  {{-- {{ Form::text('surname','', ['class' => 'form-control ']) }} --}}
                                </div>
                               
                              </td>
                              <td>
                                <div class="form-group">
                                  {{ Form::label(__('adminP.company'), null, ['class' => 'control-label color-black']) }}
                                   <input type="text" name="companyTel" id="companyTel" class="form-control" oninvalid="setCustomValidity({{__('adminP.plsFillOutCompanyField')}})"
  oninput="setCustomValidity('')" required>
                                 {{--  {{ Form::text('company','', ['class' => 'form-control ']) }} --}}
                                </div>
                                 
                              </td>
                            </tr>
                             <tr>
                              <td>
                                <div class="form-group">
                                  {{ Form::label(__('adminP.streetNr'), null, ['class' => 'control-label color-black']) }}
                                   <input type="text" name="streetTel" id="streetTel" class="form-control" oninvalid="setCustomValidity({{__('adminP.plsFillInStreetNrField')}})"
  oninput="setCustomValidity('')" required>
                                  {{-- {{ Form::text('street','', ['class' => 'form-control ']) }} --}}
                              </div>

                               
                              </td>
                              <td>
                                 <div class="form-group">
                                  {{ Form::label(__('adminP.phoneNumber'), null, ['class' => 'control-label color-black']) }}
                                   <input type="number" name="telTel" id="telTel" pattern="[0-9]+" class="form-control" oninvalid="setCustomValidity({{__('adminP.plsFillInTelField')}})"
  oninput="setCustomValidity('')" required>
                                {{--   {{ Form::text('tel','', ['class' => 'form-control ']) }} --}}
                              </div>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <div class="form-group">
                                  {{ Form::label(__('adminP.postCode'), null, ['class' => 'control-label color-black']) }}
                                   <input type="text" name="plzTel" id="plzTel" class="form-control" oninvalid="setCustomValidity({{__('adminP.plsFillOutZIPCodeField')}})"
  oninput="setCustomValidity('')" required>
                                 {{--  {{ Form::text('plz','', ['class' => 'form-control ']) }} --}}
                                </div>
                              </td>
                              <td>
                                <div class="form-group">
                                  {{ Form::label(__('adminP.email'), null, ['class' => 'control-label color-black']) }}
                                   <input type="email" name="emailTel" id="emailTel" class="form-control" oninvalid="setCustomValidity({{__('adminP.plsFillOutEmailField')}})"
  oninput="setCustomValidity('')" required>
                                  {{-- {{ Form::email('email','', ['class' => 'form-control ']) }} --}}
                              </div>
                              </td>
                            </tr>

                          </table>
                
                            
                           <p class="monthlyTitle">{{__('adminP.monthlyCost')}}</p>
                           <div class="col-md-12 monthlyText">
                               <span class="checkIcon"> ✓</span> {{__('adminP.orderAndPayContactless')}}  <span class="checkIcon"> ✓</span> {{__('adminP.callServiceViaSystem')}}
        <span class="checkIcon"> ✓</span> Covid-19  {{__('adminP.contactForm')}} <span class="checkIcon"> ✓</span> {{__('adminP.suggestedProducts')}} <span class="checkIcon"> ✓</span> {{__('adminP.productManagement')}}
        <span class="checkIcon"> ✓</span> {{__('adminP.tableChange')}}  <span class="checkIcon"> ✓</span> {{__('adminP.tip')}} <span class="checkIcon"> ✓</span> {{__('adminP.offerFreeProducts')}} <span class="checkIcon"> ✓</span> {{__('adminP.couponCode')}}  <span class="checkIcon"> ✓</span> {{__('adminP.customerRetention')}}
                           </div>
                           <table class="table monthlyCostTable">    
                           <tr style="background: #d6d6d6;">
                              <td class="left-td">
                                <input type="hidden" id="monthlyTextInputTel" name="monthlyTextInputTel" value="">
                                <div class="form-group">                                  
                                  {{ Form::radio('monthlyChargesTelType',__('adminP.withoutMarketing'), false,['class' => 'form-control ','id' => 'monthlyChargesTelType','style'=>'font-weight:bold;']) }}
                                  {{ Form::label('monthlyChargesTelType',__('adminP.withoutMarketing'), ['class' => 'control-label color-black']) }}
                                </div> 
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                 {{__('adminP.creativeIdea')}} <b>{{__('adminP.none')}}</b><br>
                                 {{__('adminP.advertisingSponsors')}}
                                </div>
                              </td>                            
                              
                            </tr>                        
                            <tr class="noneMarketing">
                              <td class="left-td">
                                <div class="form-group noneMarketingArea">                                  
                                  {{ Form::radio('monthlyChargesTel',89, false,['class' => 'form-control ','id' => 'monthlyChargesTel']) }}
                                  {{ Form::label('monthlyChargesTel',__('adminP.upTo10Tables'), __('adminP.upTo10Tables'), ['class' => 'control-label color-black']) }}
                                </div> 
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  {{__('adminP.currencyShow')}} 89.- {{__('adminP.perMonth')}}<br>
                                  + 1% {{__('adminP.commission')}}
                                </div>
                              </td>                            
                             
                            </tr>
                             <tr class="noneMarketing">
                              <td class="left-td">
                                <div class="form-group noneMarketingArea">
                                  {{ Form::radio('monthlyChargesTel',119,false, ['class' => 'form-control ','id' => 'monthlyChargesTel']) }}
                                  {{ Form::label('monthlyChargesTel','11-30 '.__("adminP.tables"), ['class' => 'control-label color-black']) }}
                                </div>
                              </td class="left-td">
                              <td class="right-td">
                               <div class="form-group">
                               {{__('adminP.currencyShow')}} 119.- {{__('adminP.perMonth')}}<br>
                                  + 1% {{__('adminP.commission')}}
                                </div>
                              </td>                      
                             
                            </tr>
                            <tr class="noneMarketing">
                              <td class="left-td">
                                <div class="form-group noneMarketingArea">
                                  {{ Form::radio('monthlyChargesTel',149, false,['class' => 'form-control ','id' => 'monthlyChargesTel']) }}
                                  {{ Form::label('monthlyChargesTel','31-50 '.__("adminP.tables"), ['class' => 'control-label color-black']) }}
                              </div>
                              </td>
                              <td class="right-td">
                               <div class="form-group">
                               {{__('adminP.currencyShow')}} 149.- {{__('adminP.perMonth')}}<br>
                                  + 1% {{__('adminP.commission')}}
                                </div>
                              </td>                          
                             
                            </tr>
                            <tr class="noneMarketing">
                              <td class="left-td">
                                <div class="form-group noneMarketingArea">
                                  {{ Form::radio('monthlyChargesTel',199, false,['class' => 'form-control ','id' => 'monthlyChargesTel']) }}
                                  {{ Form::label('monthlyChargesTel','51-99 '.__("adminP.tables"), ['class' => 'control-label color-black']) }}
                              </div>
                              </td>
                              <td class="right-td">
                               <div class="form-group">
                               {{__('adminP.currencyShow')}} 199.- {{__('adminP.perMonth')}}<br>
                                  + 1% {{__('adminP.commission')}}
                                </div>
                              </td>                         
                             
                            </tr>



                           <tr style="background: #d6d6d6;">
                              <td class="left-td">
                                <div class="form-group">
                                  {{ Form::radio('monthlyChargesTelType',__('adminP.withMarketing'), false,['class' => 'form-control ','id' => 'monthlyChargesTelType']) }}
                                  {{ Form::label('monthlyChargesTelType',__('adminP.withMarketing'), ['class' => 'control-label color-black']) }}
                              </div>
                              </td>
                              <td class="left-td">
                               <div class="form-group">
                                  {{__('adminP.creativeIdea')}}<br>
                                    {{__('adminP.advertisingSponsors')}}
                                </div>
                              </td>                           
                              
                            </tr>                        
                            <tr class="noneMarketing">
                             <td class="left-td">
                                <div class="form-group marketingArea">
                                  {{ Form::radio('monthlyChargesTel',199, false,['class' => 'form-control ','id' => 'monthlyChargesTel']) }}
                                  {{ Form::label('monthlyChargesTel',__('adminP.upTo10Tables'), ['class' => 'control-label color-black']) }}
                              </div>
                              </td>
                              <td class="left-td">
                               <div class="form-group">
                                  {{__('adminP.currencyShow')}} 199.- {{__('adminP.perMonth')}}<br>
                                  + 1% {{__('adminP.commission')}}
                                </div>
                              </td>                 
                             
                            </tr>
                             <tr class="noneMarketing">
                              <td>
                                <div class="form-group marketingArea">
                                  {{ Form::radio('monthlyChargesTel',249, false,['class' => 'form-control ','id' => 'monthlyChargesTel']) }}
                                  {{ Form::label('monthlyChargesTel','11-30 '.__("adminP.tables"), ['class' => 'control-label color-black']) }}
                              </div>
                              </td>
                              <td class="left-td">
                               <div class="form-group">
                               {{__('adminP.currencyShow')}} 249.- {{__('adminP.perMonth')}}<br>
                                  + 1% {{__('adminP.commission')}}
                                </div>
                              </td>              
                             
                            </tr>
                            <tr class="noneMarketing">
                             <td class="left-td">
                                <div class="form-group marketingArea">
                                  {{ Form::radio('monthlyChargesTel',349, false,['class' => 'form-control ','id' => 'monthlyChargesTel']) }}
                                  {{ Form::label('monthlyChargesTel','31-50 '.__("adminP.tables"), ['class' => 'control-label color-black']) }}
                              </div>
                              </td>
                              <td class="left-td">
                               <div class="form-group">
                               {{__('adminP.currencyShow')}} 349.- {{__('adminP.perMonth')}}<br>
                                  + 1% {{__('adminP.commission')}}
                                </div>
                              </td>            
                             
                            </tr>
                            <tr class="noneMarketing">
                              <td class="left-td">
                                <div class="form-group marketingArea">
                                  {{ Form::radio('monthlyChargesTel',499,false, ['class' => 'form-control ','id' => 'monthlyChargesTel']) }}
                                  {{ Form::label('monthlyChargesTel','51-99 '.__("adminP.tables"), ['class' => 'control-label color-black']) }}
                              </div>
                              </td>
                              <td class="left-td">
                               <div class="form-group">
                               {{__('adminP.currencyShow')}} 499.- {{__('adminP.perMonth')}}<br>
                                  + 1% {{__('adminP.commission')}}
                                </div>
                              </td>                 
                             
                            </tr>
                            <tr class="noneMarketing">
                              <td class="left-td">
                                <div class="form-group ">
                                  {{ Form::radio('monthlyChargesTel',0, false,['class' => 'form-control ','id' => 'monthlyChargesTel']) }}
                                  {{ Form::label('monthlyChargesTel',__('adminP.from100Tables'), ['class' => 'control-label color-black']) }}
                                  <input type="text" name="custommonthlyChargesTelQty" id="custommonthlyChargesTelQty" class="form-control" value="" placeholder="{{__('adminP.tables')}}">
                                </div>
                                <div class="form-group">
                              
                               
                                <input type="text" name="monthlyChargesTel2" readonly="readonly" id="monthlyChargesTel2" class="form-control" value="" placeholder="{{__('adminP.price')}}">
                                </div>
                              </td>
                              <td class="left-td">
                               <div class="form-group">
                                <div class="form-group">                            
                               
                                </div>
                                </div>
                              </td>
                            </tr>
                          </table>

                          <p class="monthlyTitle">{{__('adminP.additionalOptions')}}</p>
                           
                           <table class="table contractTable">                            
                            <tr>
                              <td class="left-td">
                                  <input type="hidden" id="checkBoxTextInputTel" name="checkBoxTextInputTel" value="">
                               <div class="form-check">
                                
                                    
                                  
                                  
                                    <input class="form-check-input calc" id="additionalOptionsSumTel" name="additionalOptionsSumTel[]" type="checkbox" value="19"> <label class="form-check-label" for="additionalOptionsSumTel">{{__('adminP.takeAwaySystem')}} 
                                  </label>
                          
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  {{__('adminP.currencyShow')}} 19.- {{__('adminP.perMonth')}} + 1% {{__('adminP.commission')}}
                                </div>
                              </td>                            
                              
                            </tr>
                            <tr>
                              <td class="left-td">
                               <div class="form-check">
                                  
                                 
                                   <input class="form-check-input calc" type="checkbox" id="additionalOptionsSumTel" name="additionalOptionsSumTel[]" value="19">  <label class="form-check-label" for="additionalOptionsSumTel" >Delivery System 
                                  </label>
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                {{__('adminP.currencyShow')}} 19.- {{__('adminP.perMonth')}} + 1% {{__('adminP.commission')}}
                                </div>
                              </td>                            
                              
                            </tr>
                           <tr>
                              <td class="left-td">
                               <div class="form-check">
                                  
                                 
                                   <input class="form-check-input calc" type="checkbox" id="additionalOptionsSumTel" name="additionalOptionsSumTel[]" value="29">   <label class="form-check-label" for="additionalOptionsSumTel">Tischreservierung System 
                                  </label>
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                {{__('adminP.currencyShow')}} 29.- {{__('adminP.perMonth')}}
                                </div>
                              </td>                            
                              
                            </tr>
                            <tr>
                              <td class="left-td">
                               <div class="form-check">
                                 
                                
                                   <input class="form-check-input calc" type="checkbox" id="additionalOptionsSumTel" name="additionalOptionsSumTel[]" value="49">    <label class="form-check-label" for="additionalOptionsSumTel">{{__('adminP.inventoryManagement')}} 
                                  </label>
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                {{__('adminP.currencyShow')}} 49.- {{__('adminP.perMonth')}}
                                </div>
                              </td>                            
                              
                            </tr>         

                          </table>
                          <p class="monthlyTitle">{{__('adminP.contractTerm')}}</p>
                           
                           <table class="table contractTable">                            
                            <tr>
                              <td class="left-td">
                                 <input type="hidden" id="discountTel" name="discountTel" value="">
                               <div class="form-check">
                                  {{ Form::radio('discountSumTel',0, true,['class' => 'calc2','id'=>'discountSumTel']) }}
                                  {{ Form::label('1', null, ['class' => 'control-label color-black']) }} {{__('adminP.annualContract')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  {{__('adminP.noDiscount')}}
                                </div>
                              </td>                            
                              
                            </tr>
                            <tr>
                              <td class="left-td">
                               <div class="form-check">
                                  {{ Form::radio('discountSumTel',10, false,['class' => 'calc2','id'=>'discountSumTel']) }}
                                  {{ Form::label('2', null, ['class' => 'control-label color-black']) }} {{__('adminP.annualContract')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  10% {{__('adminP.cheaper')}}
                                </div>
                              </td>                          
                              
                            </tr>
                           <tr>
                              <td class="left-td">
                               <div class="form-check">
                                  {{ Form::radio('discountSumTel',15, false,['class' => 'calc2','id'=>'discountSumTel']) }}
                                  {{ Form::label('3', null, ['class' => 'control-label color-black']) }} {{__('adminP.annualContract')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  15% cheaper
                                </div>
                              </td>               
                              
                            </tr>
                            <tr>
                             <td class="left-td">
                               <div class="form-check">
                                  {{ Form::radio('discountSumTel',25, false,['class' => 'calc2','id'=>'discountSumTel']) }}
                                  {{ Form::label('5', null, ['class' => 'control-label color-black']) }} {{__('adminP.annualContract')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  25% cheaper
                                </div>
                              </td>                    
                              
                            </tr>                            
                          </table>
                          <table class="table contractTable">                            
                            <tr>
                              <td class="left-td">
                               <div class="form-group">
                                  {{__('adminP.totalPricePerMonth')}}
                                </div>
                              </td>
                              <td class="right-td" style="text-align: right;">
                                <div class="form-group">
                                  <b>{{__('adminP.currencyShow')}}</b>  <input type="text" id="monthlySumTel" name="monthlySumTel" value="" readonly="true">
                                  <input type="hidden" id="totalTel" name="totalTel" value="" readonly="true">
                                </div>
                              </td>                            
                              
                            </tr>
                                        
                          </table>
                          <p class="monthlyTitle">{{__('adminP.costPerTransaction')}}</p>
                           
                           <table class="table contractTable">                            
                            <tr>
                              <td class="left-td">
                               <div class="form-group">
                                  {{__('adminP.verifySMSGateWayOrders')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  {{__('adminP.currencyShow')}} 0.10.- {{__('adminP.perSMS')}}
                                </div>
                              </td>                            
                              
                            </tr>
                            <tr>
                              <td class="left-td">
                               <div class="form-group">
                                  {{__('adminP.onlinePayemetsTwint')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  1.4% + {{__('adminP.currencyShow')}} 0.28.- {{__('adminP.perTransaction')}}
                                </div>
                              </td>                 
                              
                            </tr>
                           <tr>
                             <td class="left-td">
                               <div class="form-group">
                                  {{__('adminP.onlinePaymentsPostFinanceCard')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  1.9% + {{__('adminP.currencyShow')}}  0.28.- {{__('adminP.perTransaction')}}
                                </div>
                              </td>            
                              
                            </tr>
                            <tr>
                             <td class="left-td">
                               <div class="form-group">
                                  {{__('adminP.onlinePaymentsPostFinanceEService')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  1.9% + {{__('adminP.currencyShow')}} 0.28.- {{__('adminP.perTransaction')}}
                                </div>
                              </td>                     
                              
                            </tr>   
                            <tr>
                             <td class="left-td">
                               <div class="form-group">
                                  {{__('adminP.onlinePaymentsMasterCard')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  1.9% + {{__('adminP.currencyShow')}} 0.28.- {{__('adminP.perTransaction')}}
                                </div>
                              </td>                     
                              
                            </tr>  
                            <tr>
                             <td class="left-td">
                               <div class="form-group">
                                  {{__('adminP.onlinePaymentsVisa')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  1.9% + {{__('adminP.currencyShow')}} 0.28.- {{__('adminP.perTransaction')}}
                                </div>
                              </td>                     
                              
                            </tr>                           
                          </table>
                          <p class="monthlyTitle">{{__('adminP.oneTimeCosts')}}</p>
                           
                           <table class="table contractTable">                            
                            <tr>
                              <td class="left-td">
                               <div class="form-group">
                                  {{__('adminP.furFlyPlaCardHolders')}}
                                </div>
                              </td>
                              <td class="right-td">
                                <div class="form-group">
                                  <b>{{__('adminP.currencyShow')}} 190.-</b>
                                </div>
                              </td>                            
                              
                            </tr>                          
                          </table>
                          <div class="col-md-12">
                            <p>{{__('adminP.generalTermsConditions')}}</p>
                          </div>

                          <table class="table">                            
                            <tr>
                              <td class="">
                                <div class="form-group">
                                  {{ Form::label(__('adminP.remarks'), null, ['class' => 'control-label color-black']) }}
                                 {!! Form::textarea('commentTel', null, ['class'=>'form-control']) !!}
                                </div>
                              </td>                            
                              
                            </tr>                          
                          </table>
                          <table class="table signatureArea">                            
                            <tr>
                              <td class="signatureAreaTd">
                               <div class="form-group">
                                  {{ Form::label(__('adminP.location'), null, ['class' => 'control-label color-black']) }}
                                  {{ Form::text('ortSignatureTel','', ['class' => 'form-control ']) }}
                                </div>
                                <div class="form-group">
                                  {{ Form::label(__('adminP.date'), null, ['class' => 'control-label color-black']) }}
                                  {{Form::date('dateSignatureTel', \Carbon\Carbon::now(), ['class'=>'form-control','style' => 'max-width: 100%'])}}
                                </div>
                              </td>
                              <td class="signatureAreaTd">
                               <div class="col-4" style="padding-left: 0px;">
                                        <div class="form-group">
                                            <a data-toggle="modal" href="#editSignatureTel" class="btn btn-primary">
                                                <strong>{{__('adminP.signature')}}</strong>
                                            </a>
                                        </div>

                                    </div>

                                      
                                
                              </td>                            
                              
                            </tr>
                        <!--  <tr>
                              <td class="">
                               <div class="form-group">
                                  {{ Form::label('Ort/Datum', null, ['class' => 'control-label color-black']) }}
                                  {{ Form::text('ortDate2','', ['class' => 'form-control ']) }}
                                </div>
                              </td>
                              <td class="">
                                <div class="form-group">
                                                    <br/>
                                                    <div id="signaturePadTel" ></div>
                                                    <br/>
                                                     <div class="form-group">
                                                      <button id="clearTel" class="btn btn-danger btn-sm" style=" margin-top:20px;">Unterschrift löschen</button>
                                                    </div>
                                                    
                                                    <textarea id="signature64Tel" name="signedTel" style="display: none;"></textarea>
                                                </div>   
                              </td>                            
                              
                            </tr>  -->
                                                 
                          </table>
                          
                           
                            <button class="btn btn-block btn-success">{{__('adminP.saveOnComputer')}}</button>
                           {{-- <input type="submit" class="form-control btn btn-success" value="Speichern"> --}}
                           
                            
                   
                    

                </div>

            </div>
        </div>
 

        
    </div>
    <div class="modal fade" id="editSignatureTel" data-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="editSignatureTel" aria-hidden="true" style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%; opacity: 1; z-index: 9999;">
                    <div class="modal-dialog modal-lg"  style="max-width: 100%">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">{{__('adminP.addSignature')}}</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div><div class="container"></div>
                        <div class="modal-body">
                            <div class="form-group">
                                                    <br/>
                                                    <div id="signaturePadTel" ></div>
                                                    <br/>
                                                     <div class="form-group">
                                                      <button id="clearTel" class="btn btn-danger btn-sm" style=" margin-top:20px;">{{__('adminP.deleteSignature')}}</button>
                                                    </div>
                                                    
                                                    <textarea id="signature64Tel" name="signedTel" style="display: none;"></textarea>
                                                </div>                 
                        </div>
                        <div class="modal-footer">
                          <a href="#" data-dismiss="modal" class="btn btn-success" id="closeModal">{{__('adminP.saveOnComputer')}}</a>
                        </div>
                        
                      </div>
                    </div>
                </div>
                {{Form::close() }}
         
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset('js/jquery.signature.js') }}"></script>
<script src="{{ asset('js/jquery.ui.touch-punch.js') }}"></script>
<script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
<link href="{{ asset('css/jquery.signature.css') }}" rel="stylesheet">


<script  type="text/javascript">
    $(document).ready(function(){
     
    $("#datatableTel").dataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
                },
             "aoColumnDefs": [{ 'bSortable': true, 'aTargets': [] }],
             "bSort": true,
             dom: 'Blfrtip',
              responsive: true,
             order: [[ 0, 'desc' ]],
             lengthMenu: [
                 [10, 25, 50, -1],
                 ['10 reihen', '25 reihen', '50 reihen', 'Zeige alles']
             ],
             buttons: [
                 'excelHtml5'
             ]
         });


        $('.marketingArea>input#monthlyChargesTel').prop('disabled',true);
        $('.noneMarketingArea>input#monthlyChargesTel').prop('disabled',true);

        $('input:radio[name="monthlyChargesTel"],input:radio[name="discountSumTel"],input[type=checkbox], input:text[name="monthlyChargesTel2"], input:radio[name="monthlyChargesTelType"]').on("keydown keyup change", function() {

            calculateTel();

        });

});

    function calculateTel(){
      var totalPrice   = 0,
      
              values       = [];
              if($('input:radio[name="monthlyChargesTelType"]:checked').val() == 'Ohne Marketing'){
                $('.marketingArea>input#monthlyChargesTel').prop('disabled',true);
                $('.noneMarketingArea>input#monthlyChargesTel').prop('disabled',false);
              }
              else if($('input:radio[name="monthlyChargesTelType"]:checked').val() == 'Mit Marketing'){
                $('.marketingArea>input#monthlyChargesTel').prop('disabled',false);
                $('.noneMarketingArea>input#monthlyChargesTel').prop('disabled',true);
              }

              if($('input:radio[name="monthlyChargesTel"]:checked').val() == '0'){
       
                $("#monthlyChargesTel2").prop("readonly", false);
                $("#customMonthlyChargesTelQty").prop("readonly", false);
                
                var customMonthlyCharges = + parseInt($('input:text[name="monthlyChargesTel2"]').val()) ;
                var checkBoxText =  $('input[type=checkbox]:checked').next('label').text();
                var discountText =  $('input:radio[name="discountSumTel"]:checked').next('label').text();
                    var monthlyCostText =  $('input:radio[name="monthlyChargesTel"]:checked').next('label').text();
                $('input[type=checkbox]').each(function() {

                  if( $(this).is(':checked') ) {
                    
                    var subtotal = parseInt($(this).val());
                    values.push($(this).val());
                   
                     
                    totalPrice += subtotal - (parseInt($('input:radio[name="discountSumTel"]:checked').val()) /100 * subtotal);

                    }
                     document.getElementById("checkBoxTextInputTel").setAttribute('value',checkBoxText.replace(/(\n)/g,",\n"));
                     document.getElementById("monthlyTextInputTel").setAttribute('value',monthlyCostText.replace(/(\n)/g,",\n"));
                     document.getElementById("discountTel").setAttribute('value',discountText.replace(/(\n)/g,",\n"));

                    
                  });
                
               document.getElementById("monthlySumTel").setAttribute('value',((totalPrice + customMonthlyCharges) - (parseInt($('input:radio[name="discountSumTel"]:checked').val())/100 * customMonthlyCharges)).toFixed(2) );

               document.getElementById("totalTel").setAttribute('value',((totalPrice + customMonthlyCharges) - (parseInt($('input:radio[name="discountSumTel"]:checked').val())/100 * customMonthlyCharges).toFixed(2))  * ($('input:hidden[name="discountTel"]').val() * 12));
                } 
         
            
              else {

                     $('input:radio[name="monthlyChargesTel"], input[type=checkbox]').each(function() {
                       var checkBoxText =  $('input[type=checkbox]:checked').next('label').text();
                       var monthlyCostText =  $('input:radio[name="monthlyChargesTel"]:checked').next('label').text();
                       var discountText =  $('input:radio[name="discountSumTel"]:checked').next('label').text();
                       

                      if( $(this).is(':checked') ) {
                        $("#monthlyChargesTel2").prop("readonly", true);
                        $("#customMonthlyChargesTelQty").prop("readonly", true);

                        values.push($(this).val());
                        totalPrice += parseInt($(this).val()) - (parseInt($('input:radio[name="discountSumTel"]:checked').val()) /100 * parseInt($(this).val()));

                        } 
                        document.getElementById("checkBoxTextInputTel").setAttribute('value',checkBoxText.replace(/(\n)/g,",\n"));
                        document.getElementById("monthlyTextInputTel").setAttribute('value',monthlyCostText.replace(/(\n)/g,",\n"));
                         document.getElementById("discountTel").setAttribute('value',discountText.replace(/(\n)/g,",\n"));
                       
                      });                             
            
                document.getElementById("monthlySumTel").setAttribute('value',totalPrice.toFixed(2));
                 document.getElementById("totalTel").setAttribute('value',totalPrice *  ($('input:hidden[name="discountTel"]').val() * 12).toFixed(2));
              }
                 
        
    }
  </script>

<script type="text/javascript">

var signaturePadTel = $('#signaturePadTel').signature({syncField: '#signature64Tel', syncFormat: 'PNG'});
$('#clearTel').click(function(e) {
e.preventDefault();
signaturePadTel.signature('clear');
$("#signature64TelTel").val('');
});

/*var signaturePad2 = $('#signaturePad2').signature({syncField: '#signature64Tel2', syncFormat: 'PNG'});
$('#clear2').click(function(e) {
e.preventDefault();
signaturePad2.signature('clear2');
$("#signature64Tel2").val('');
});
*/

</script>








