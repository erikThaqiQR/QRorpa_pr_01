@extends('firstPage.appM.kat01')

@section('content') 
<style>
    *{
        outline: none;
        box-shadow: none;
    }
    .btn:focus{
        outline: none;
        box-shadow: none;
    }

    .srchBtnTop{
        font-size:24px;
        color:rgb(39,120,100);
    }
    .typeBtnRes{
        border:none;
        border-radius:5px;
        font-size: 14px;
        color:rgb(72,81,87);
    }
    .typeBtnRes:hover{
        background-color:rgb(39,190,175);
        color:white;
    }
    .typeBtnResNot{
        color:red;
        border:none;
        border-radius:5px;
        font-size: 14px;
    }
    .typeBtnResNot:hover{
        background-color:red;
        color:white;
        cursor: not-allowed;
    }

    form.example input[type=text] {
      padding: 10px;
      font-size: 17px;
      border: 1px solid grey;
      float: left;
      width: 80%;
      background: #fff;
    }
    input[type=text]:focus {
        outline: -webkit-focus-ring-color auto 0px !important;
    }
    form.example button {
      float: left;
      width: 20%;
      padding: 10px;
      background: #02beaf;
      color: white;
      font-size: 17px;
      border: 1px solid grey;
      border-left: none;
      cursor: pointer;
    }
    form.example button:hover {
      background: #02beaf;
    }
    form.example::after {
      content: "";
      clear: both;
      display: table;
    } 





.swiper-container{
        background-color:#FFF;
        padding-top: 5px !important;
    }
    .swiper-slide{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .swiper-slide img{
        object-fit:cover;
    }
    .swiper-slide p{
        margin-top:5px;
        margin:0; 
        font-size: 23px;
        color: rgb(72,81,87);
    }

    .color-text{
        color: rgb(72,81,87);
    }



  
    .yjetMenuRat{
        font-size:16px;
    }
    .star-ratings-css {
        unicode-bidi: bidi-override;
        color: #c5c5c5;
        font-size: 17px;
        margin: 0 auto;
        position: relative;
        /*text-shadow: 0 1px 0 #a2a2a2;*/
        display: inline-table;
    } 
    .star-ratings-css::before { 
        content: '★★★★★';
        opacity: .3;
        display: flex;
    }

    .star-ratings-css::after {
        color: #ffb101;
        content: '★★★★★';
        /*text-shadow: 0 1px 0 #ab5414;*/
        position: absolute;
        z-index: 1;
        display: block;
        left: 0;
        top:0;
        width: attr(rating);
        overflow: hidden;
    }
                                                    

</style>





<?php
    use App\DeliveryPLZ;
    use App\RestaurantRating;
    use App\TableQrcode;

    use App\DeliverySchedule;
    use App\TakeawaySchedule;
    use App\RestaurantWH;

    use Jenssegers\Agent\Agent;
    $agent = new Agent();


    if(isset($_GET["emri"])){
        $searchWor = $_GET["emri"];
    }else if(isset($_GET["wor"])){
        $searchWor = $_GET["wor"];
    }else{
        $searchWor = '';
    }


    $weekDayNr = date('w');
?>



    @if($agent->isMobile())
        @include('firstPage.searchResTel')
    @else
        @include('firstPage.searchResDes')
    @endif

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script type="">

            function sendServiceRequest(typ,resId){
                if(typ == 'takeaway'){
                    var typ2 = "komentTakeawayServiceReq"+resId;
                    var rTy = 1;
                }else if(typ == 'delivery'){
                    var typ2 = "komentDeliveryServiceReq"+resId;
                    var rTy = 2;
                }else if(typ == 'tablerez'){
                    var typ2 = "komentTablerezServiceReq"+resId;
                    var rTy = 3;
                }
                var kom = $('#'+typ2).val();

                if(kom != ''){
                    $.ajax({
                        url: '{{ route("SerReqCli.store") }}',
                        method: 'post',
                        data: {
                            id: resId,
                            comm: $('#'+typ2).val(),
                            reqType: rTy,
                            _token: '{{csrf_token()}}'
                        },
                        success: () => {
                            // alert(resId);
                            $('.SerReqAlS'+resId).show(200).delay(3500).hide(200);
                            $('#'+typ2).val('');
                            setTimeout(function() {
                                location.reload();
                            }, 3500);	
                        },
                        error: (error) => {
                            console.log(error);
                            $('.SerReqAlE'+resId).show(200).delay(3500).hide(200);
                        }
                    });
                }
            
            }


        function showCarSer(cat){

            $('.resBoxAll').each(function(i, obj) {
                var resId = (this.id).split('O')[1];
                if(cat == "showall"){
                    location.reload();
                }else if(cat =="takeaway"){
                    if($('#btnShowallCatSer').is(":hidden")){
                        $('#btnShowallCatSer').show();
                    }

                    $('#btnTakeawayCatSer').css('border-bottom','4px solid rgb(39,190,175)');
                    if($('#hasTakeaway'+resId).val() == 0){
                        $('#resBoxO'+resId).remove();
                    }

                }else if(cat =="tablerez"){
                    if($('#btnShowallCatSer').is(":hidden")){
                        $('#btnShowallCatSer').show();
                    }

                    $('#btnTablerezCatSer').css('border-bottom','4px solid rgb(39,190,175)');
                    if($('#hasTableRez'+resId).val() == 0){
                        $('#resBoxO'+resId).remove();
                    }

                }
            });
        }

    
        function showCarSerTel(cat){

            $('.resBoxAllTel').each(function(i, obj) {
                var resId = (this.id).split('O')[1];
                if(cat == "showall"){
                    location.reload();
                }else if(cat =="takeaway"){
                    if($('#btnShowallCatSerTel').is(":hidden")){
                        $('#btnShowallCatSerTel').show();
                    }

                    $('#btnTakeawayCatSerTel').css('border-bottom','4px solid rgb(39,190,175)');
                    if($('#hasTakeawayTel'+resId).val() == 0){
                        $('#resBoxTelO'+resId).remove();
                    }

                }else if(cat =="tablerez"){
                    if($('#btnShowallCatSerTel').is(":hidden")){
                        $('#btnShowallCatSerTel').show();
                    }

                    $('#btnTablerezCatSerTel').css('border-bottom','4px solid rgb(39,190,175)');
                    if($('#hasTableRezTel'+resId).val() == 0){
                        $('#resBoxTelO'+resId).remove();
                    }

                }
            });
        }

    </script>



@endsection