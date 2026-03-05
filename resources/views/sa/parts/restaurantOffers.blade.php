        <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">



<?php
    use App\Orders;
    use App\Restorant;
    use App\RestaurantOffer;
                           

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
#signaturePad canvas{
width: 100% !important;
height: auto;
}
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
input#monthlyChargesType, input#monthlyCharges {
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
.b-qrorpa.col-md-4 {
    float: left;
    width: 32%;
    margin: 10px;
}
.top5table td{
  color:#fff;
  font-size: 17px;
}
.red {
  color: red !important;
}
</style>

    <div class="col-md-12"> 
  <div class="b-qrorpa col-md-4" style="border-radius:40px; color:#fff">
                <div class="row pl-4 pr-4 pt-5" style=" padding-right: 7.5rem !important;">
                    <div class="col-8 text-left">
                        <h3> Alle Verträge</h3>
                    </div>
                    <div class="col-4 text-right">
                        <h3>Gesamtsumme</h3>
                    </div>
                </div>
                <div class="row pl-4 pr-4 pb-5" style="margin-top:-20px;">
                  @php

              $restaurantOffers = DB::table('restaurant_offers')->join('users', 'restaurant_offers.user_id', '=', 'users.id')->select('restaurant_offers.*', 'users.name as userName')->orderBy('restaurant_offers.id', 'DESC')->get();
                      @endphp 
                    <div class="col-8 text-left" style="margin-top:20px">
                        <h2> {{$restaurantOffers->count()}}</h2>
                    </div>
                    <div class="col-4 text-right" style="margin-top:20px;">
                       <h2 style="   font-size: 1.5rem;">CHF {{$restaurantOffers->sum('monthlySum')}}</h2>
                    </div>

                </div>
            </div>
            <div class="b-qrorpa col-md-4" style="border-radius:40px; color:#fff">
                <div class="row pl-4 pr-4 pt-5" style=" padding-right: 7.5rem !important;">
                    <div class="col-8 text-left">
                        <h3> Top 5 Verkäufer </h3>
                    </div>
                    <div class="col-4 text-right">
                      @php

              $top5sellers = DB::table('restaurant_offers')
              ->selectRaw('restaurant_offers.user_id,count(users.id) as contracts')
              ->selectRaw('users.name as userName')
              ->join('users', 'restaurant_offers.user_id', '=', 'users.id')
              ->groupBy('restaurant_offers.user_id','userName')
              ->orderByDesc('contracts')
              ->take(5)
              ->get();
                      @endphp
                    </div>
                </div>
                <div class="row pl-4 pr-4 pb-5" style="margin-top:-20px;">
                    <div class="col-12 text-left">
                        <table class="table top5table">
                            @foreach($top5sellers as $top5)
                            <tr>
                              <td> {{$top5->userName}}</td>
                              <td> {{$top5->contracts}}</td>
                            </tr>

                          
                          @endforeach
                          </table>
                    </div>
                </div>
            </div>
            <div class="b-qrorpa col-md-4" style="border-radius:40px; color:#fff">
                <div class="row pl-4 pr-4 pt-5" style=" padding-right: 7.5rem !important;">
                    <div class="col-8 text-left">
                        <h3> //</h3>
                    </div>
                    <div class="col-4 text-right">
                        <h3>//</h3>
                    </div>
                </div>
                <div class="row pl-4 pr-4 pb-5" style="margin-top:-20px;">
                    <div class="col-8 text-left" style="margin-top:20px">
                        <h2> //</h2>
                    </div>
                    <div class="col-4 text-right" style="margin-top:20px;">
                       <h2 style="   font-size: 1.5rem;">//</h2>
                    </div>
                </div>
            </div>
</div>
       
     
    <div class="col-md-12">
  <table class="table table-striped table-bordered dt-responsive nowrap" width="100%" id="datatable">
  
  <thead>
    <tr>
      
      <th>ID</th>
      <th>Name</th>
      <th>Vorname</th>
      <th>Ort</th>
      <th>Firma</th>
      <th>Email</th>
      <th>Gesamtsumme</th>
      <th>Datum</th>
      <th>Agent</th>
      <th>Status</th>
      <th>Agentenzahlung</th>
      <th>Aktion</th>
    </tr>
 
  </thead>
</table>
</div>

 

    <!-- The Modal  Edit Contract -->
    <div class="modal fade bd-example-modal-lg pt-2" id="editContract"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style=" margin-top:20px;">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content" style="border-radius:30px;">
               <form method="post" id="editContract_form" name="editcontract" enctype="multipart/form-data">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title color-qrorpa"><strong>Vertrag bearbeitung</strong></h4>
                <button type="button" class="close noOutline" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
            </div>

                <!-- Modal body -->
                <div class="modal-body">
                  
                        {{csrf_field()}}
        <span id="form_output"></span>
                       
                        <table class="table contractData">
                           
                        
                            <tr> 
                            <td>
                              
                                  <input id="getMonthlySum" type="text" name="monthlySum" class="form-control" readonly="" hidden="">
                                 
                              
                                 <div class="form-group">
                                  {{ Form::label('Jahreswertrag', null, ['class' => 'control-label color-black']) }}
                                  <input id="duration" type="text" name="discount" class="form-control" readonly=""> 
                                 
                                </div>
                            
                               
                                <div class="form-group">
                                  {{ Form::label('Prozentsatz für Agenten', null, ['class' => 'control-label color-black']) }}
                                  <input id="userProfitPercentage" type="text" name="userProfitPercentage" class="form-control">
                                 
                                </div>
                                
                              </td>
                              <td>
                                <div class="form-group">
                                  {{ Form::label('Gesamtsumme', null, ['class' => 'control-label color-black']) }}
                                  <input id="getTotal" type="text" name="total" class="form-control" readonly="">
                              </div>
                                 <div class="form-group">
                                  {{ Form::label('Agentengewinn', null, ['class' => 'control-label color-black']) }}
                                  <input id="userProfit" type="text" name="userProfit" class="form-control" readonly="">
                              </div>
                              
                                 
                               
                              </td>
                              <td>
                                <div class="form-group">
                                  {{ Form::label('Status', null, ['class' => 'control-label color-black']) }}
                                  <select class="form-control" id="contractStatus" name="contractStatus">
                                      <option>Bezahlt</option>
                                      <option>Unbezahlt</option>
                                    </select>
                              </div>
                                 <div class="form-group">
                                  {{ Form::label('Agentenzahlung', null, ['class' => 'control-label color-black']) }}
                                    <select class="form-control" id="userPayment" name="userPayment">
                                      <option>Bezahlt</option>
                                      <option>Unbezahlt</option>
                                    </select>
                              </div>
                              
                                 
                               
                              </td>
                           
                            </tr>
                            <td>
                                <input type="hidden" name="contract_id" id="contract_id" value="" />
                           <input type="submit" name="submit" id="action" value="Speichern" class="btn btn-info" />
                            </td>
                            <tr>
                            </tr>
                          
                         
                          </table>
                           

                </div>
                 </form>
            </div>
        </div>
    </div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset('js/jquery.signature.js') }}"></script>
<link href="{{ asset('css/jquery.signature.css') }}" rel="stylesheet">

<script  type="text/javascript">
    $(document).ready(function(){

     

     /*  $(document).on('click', '.edit', function(){
        var id = $(this).attr("id");
        $('#form_output').html('');
        $.ajax({
            url: "",
            method: 'get',
            data: {id:id},
            dataType: 'json',
            success:function(data)
            {
                 $('#id').val(data.id);
                $('#userProfitPercentage').val(data.userProfitPercentage);
                $('#userProfit').val(data.userProfit);
               
               
            }
        })
    });*/
      //Datatable
     /* var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'vorname', name: 'name'},
            {data: 'strasse', name: 'email'},
            {data: 'plz', name: 'email'},
            {data: 'ort', name: 'email'},
            {data: 'company', name: 'email'},
            {data: 'tel', name: 'email'},
            {data: 'email', name: 'email'},
            {data: 'signature', name: 'action', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},



 
        ]
    });*/
    /*$("#datatable").dataTable({
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
         });*/
         $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "paging":   false,
                "ajax": "{{ route('restaurantOffers.getData') }}",
                "order": [[1,'desc']],         
                "columns": [    
                {"data": "id"},     
                {"data": "name"},
                {"data": "surname"},   
                {"data": "ort"},
                {"data": "company"},
                {"data": "email"},
                {"data": "monthlySum"},
                {"data": "dateSignature"},
              
                {
                  data: function (row) {
                   let userName= [];
                     $(row.user).each(function (i, e) {
                       userName.push(e.name);
                       });
                     return userName.join(", ")
                   }, name: 'user.name'
                },
                {"data": "contractStatus"},               
                {"data": "userPayment"},              
               
                {data: 'action',orderable:false, searchable: false},
                 

                ],
                createdRow: (row, data, dataIndex, cells) => {
                  $(cells[9]).css('background-color', data.status_color);
                  $(cells[9]).css('color', '#ffffff');
                  $(cells[10]).css('background-color', data.user_status_color)
                  $(cells[10]).css('color', '#ffffff');
                  
              }

              
         });
         $(document).on('click', '.edit', function(){
                var id = $(this).attr("id");
                $('#form_output').html('');
                $.ajax({
                    url: "{{route('restaurantOffers.fetchdata')}}",
                    method: 'get',
                    data: {id:id},
                    dataType: 'json',
                    success:function(data)
                    {
                        $('#getMonthlySum').val(data.monthlySum);
                        $('#userProfitPercentage').val(data.userProfitPercentage);
                        $('#userProfit').val(data.userProfit);  
                        $('#discountpercentage').val(data.discountSum);   
                        $('#duration').val(data.discount);  
                        $('#getTotal').val(data.total);   
                        $('#contractStatus').val(data.contractStatus);        
                        $('#userPayment').val(data.userPayment);  
                        $('#contract_id').val(id);                
                        $('#editContract').modal('show');
                        $('#action').val('Speichern');
                        $('.modal-title').text('Daten aktualisieren');
                        $('#button_action').val('update');
                    }
                })
            });

   
         $('#editContract_form').on('submit', function(event){
        event.preventDefault();
        /*var form_data = $(this).serialize();*/
        $.ajax({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{route('restaurantOffers.updateContract')}}",
            method:"POST",
            data:new FormData(this),
            dataType:"json",
            contentType: false,
            cache: false,
            processData: false,
            success:function(data)
            {
                if (data.error.length > 0) 
                {
                    var error_html = '';
                    for(var count = 0; count < data.error.length; count++)
                    {
                        error_html += '<div class="alert alert-danger">'+data.error[count]+'</div>';
                    }
                    $('#form_output').html(error_html);
                }
                else
                {
                    $('#form_output').html(data.success);
                    $('#action').val('Speichern');
                    $('.modal-title').text('Hinzufügen');
                    $('#button_action').val('insert');
                    $('#datatable').DataTable().ajax.reload();

                }
            }
        })
        
    });

          

        $('.marketingArea>input#monthlyCharges').prop('disabled',true); 
        $('.noneMarketingArea>input#monthlyCharges').prop('disabled',true);

        $('input:radio[name="monthlyCharges"],input:radio[name="discountSum"],input[type=checkbox], input:text[name="monthlyCharges2"], input:radio[name="monthlyChargesType"]').on("keydown keyup change", function() {

            calculate();

        });
        $('input:text[name="userProfitPercentage"]').on("keydown keyup change", function() {

            sum();

        });




});
/*
        function userProfit(){
          var totalPrice   = 0;
           totalPrice += parseInt($('input:text[name="monthlySum"]').val()) - 20;

           document.getElementById("userProfit").setAttribute('value',(totalPrice));
        }*/

        function sum() {
            var monthlysum = document.getElementById('getMonthlySum').value;
            var userProfitPercentage = document.getElementById('userProfitPercentage').value;
            var duration = document.getElementById('duration').value;
            var res1 = monthlysum * (duration * 12);
            var subresult = res1 - (userProfitPercentage / 100 * res1);
            var result = res1 - subresult;
            if (!isNaN(result)) {
                document.getElementById('userProfit').value = parseInt(result.toFixed(2));
            }
        }

    function calculate(){
      var totalPrice   = 0,
      
      
              values       = [];
              if($('input:radio[name="monthlyChargesType"]:checked').val() == 'Ohne Marketing'){
                $('.marketingArea>input#monthlyCharges').prop('disabled',true);
                $('.noneMarketingArea>input#monthlyCharges').prop('disabled',false);
              }
              else if($('input:radio[name="monthlyChargesType"]:checked').val() == 'Mit Marketing'){
                $('.marketingArea>input#monthlyCharges').prop('disabled',false);
                $('.noneMarketingArea>input#monthlyCharges').prop('disabled',true);
              }

              if($('input:radio[name="monthlyCharges"]:checked').val() == '0'){

       
                $("#monthlyCharges2").prop("readonly", false);
                $("#customMonthlyChargesQty").prop("readonly", false);
                
                var customMonthlyCharges = + parseInt($('input:text[name="monthlyCharges2"]').val()) ;
                var checkBoxText =  $('input[type=checkbox]:checked').next('label').text();
                var discountText =  $('input:radio[name="discountSum"]:checked').next('label').text();
                    var monthlyCostText =  $('input:radio[name="monthlyCharges"]:checked').next('label').text();
                $('input[type=checkbox]').each(function() {

                  if( $(this).is(':checked') ) {
                    
                    var subtotal = parseInt($(this).val());
                    values.push($(this).val());
                   
                     
                    totalPrice += subtotal - (parseInt($('input:radio[name="discountSum"]:checked').val()) /100 * subtotal);

                    }
                     document.getElementById("checkBoxTextInput").setAttribute('value',checkBoxText.replace(/(\n)/g,",\n"));
                     document.getElementById("monthlyTextInput").setAttribute('value',monthlyCostText.replace(/(\n)/g,",\n"));
                     document.getElementById("discount").setAttribute('value',discountText.replace(/(\n)/g,",\n"));

                    
                  });
                
               document.getElementById("monthlySum").setAttribute('value',((totalPrice + customMonthlyCharges) - (parseInt($('input:radio[name="discountSum"]:checked').val())/100 * customMonthlyCharges)).toFixed(2) );
               var total = 0;
               total += ((totalPrice + customMonthlyCharges) - (parseInt($('input:radio[name="discountSum"]:checked').val())/100 * customMonthlyCharges))  * ($('input:hidden[name="discount"]').val() * 12);
                document.getElementById("total").setAttribute('value',total.toFixed(2));
               
                } 
         
            
              else {

                     $('input:radio[name="monthlyCharges"], input[type=checkbox]').each(function() {
                       var checkBoxText =  $('input[type=checkbox]:checked').next('label').text();
                       var monthlyCostText =  $('input:radio[name="monthlyCharges"]:checked').next('label').text();
                       var discountText =  $('input:radio[name="discountSum"]:checked').next('label').text();
                       

                      if( $(this).is(':checked') ) {
                        $("#monthlyCharges2").prop("readonly", true);
                        $("#customMonthlyChargesQty").prop("readonly", true);

                        values.push($(this).val());
                        totalPrice += parseInt($(this).val()) - (parseInt($('input:radio[name="discountSum"]:checked').val()) /100 * parseInt($(this).val()));

                        } 
                        document.getElementById("checkBoxTextInput").setAttribute('value',checkBoxText.replace(/(\n)/g,",\n"));
                        document.getElementById("monthlyTextInput").setAttribute('value',monthlyCostText.replace(/(\n)/g,",\n"));
                         document.getElementById("discount").setAttribute('value',discountText.replace(/(\n)/g,",\n"));
                       
                      });                             
            
                document.getElementById("monthlySum").setAttribute('value',totalPrice.toFixed(2));
                var total = 0;
                total += totalPrice *  parseInt($('input:hidden[name="discount"]').val() * 12);
                document.getElementById("total").setAttribute('value',total.toFixed(2));
              }
                 
        
    }
  </script>

<script type="text/javascript">

var signaturePad = $('#signaturePad').signature({syncField: '#signature64', syncFormat: 'PNG'});
$('#clear').click(function(e) {
e.preventDefault();
signaturePad.signature('clear');
$("#signature64").val('');
});

/*var signaturePad2 = $('#signaturePad2').signature({syncField: '#signature642', syncFormat: 'PNG'});
$('#clear2').click(function(e) {
e.preventDefault();
signaturePad2.signature('clear2');
$("#signature642").val('');
});
*/

</script>








