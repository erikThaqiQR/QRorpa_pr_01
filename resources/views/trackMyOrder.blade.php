@extends('layouts.appOrders')

@section('content')
    <?php
        use App\Orders;
        use App\Restorant;
        use Carbon\Carbon;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    ?>

    <div class="container" id="startTrack">
        <div class="row" style="margin-top:15%;">
            <div class="col-lg-2 col-sm-0">
            </div>
            <div class="col-lg-8 col-sm-12 text-center">
                <h1 style="color:white;" class="mt-3"><strong>Verfolgen Sie Ihre Bestellung</strong> </h1>

            </div>
            <div class="col-lg-2 col-sm-0">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-2 col-sm-0">
            </div>
            <div class="col-lg-8 col-sm-12">
                <div class="input-group mb-3">
                    @if(Cookie::has('trackMO') && Cookie::get('trackMO') != 'not')
                        <input style="font-size:16px;" type="text" value="{{explode('|',Cookie::get('trackMO'))[1]}}" id="shifraTrack" class="form-control shadow-none" >
                        <?php $sndCode = Cookie::get('trackMO');?>
                    @elseif(isset($_SESSION['trackMO']))
                        <input style="font-size:16px;" type="text" value="{{explode('|',$_SESSION['trackMO'])[1]}}" id="shifraTrack" class="form-control shadow-none" >
                        <?php $sndCode = $_SESSION['trackMO'];?>
                    @else
                        <input style="font-size:16px;" type="text" id="shifraTrack" class="form-control shadow-none" placeholder="Bestellnummer...">
                        <?php $sndCode = 'none';?>
                    @endif
                    <div class="input-group-append">
                        <button style="color:white;" class="btn btn-outline-dark" type="submit" onclick="showOrderTrackFn('{{$sndCode}}')"><strong>Prüfen</strong></button>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-0">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-2 col-sm-0"></div>
            <div class="col-lg-8 col-sm-12 alert alert-danger text-center" style="display: none;" id="startTrackError01">
                <strong>Ihr Code wird nicht akzeptiert, bitte versuchen Sie es erneut!</strong>
            </div>
            <div class="col-lg-2 col-sm-0"></div>
        </div>
    </div>


    <div class="container text-center mt-5"  id="loadingTrack" style="display:none;">
        <img src="storage/gifs/loading2.gif" style="width:150px; height:auto" alt="">
    </div>

    <div class="container text-center mt-4" id="noneRespoTrack" style="display:none;">
        <p style="color:white; font-size:1.6rem;"><strong>Leider konnten wir Ihre Bestellung nicht finden, überprüfen Sie den Code erneut oder vergewissern Sie sich,
             dass die Bestellung nicht älter als 24 Stunden ist !</strong>
        </p>
    </div>


    <div class="container text-center mt-5"  id="showOrderTrack" style="display:none;">
    
    </div>

    <br><br><br><br>

    <?php
        
    ?>

    @foreach (Restorant::all() as $oneRes)
        <input type="hidden" id="res{{$oneRes->id}}" value="{{$oneRes->emri}}">
    @endforeach







    <script>
        function backTrackOrder(){
            $('#startTrack').show(800);
            $('.allOrdersTrack').hide(800);
            $('#backBtnTrack').hide(800);
        }

        function showOrderTrackFn(co){
     
            if(co == 'none'){ co = $('#shifraTrack').val(); }

            if(co.length != 7){
                if($('#startTrackError01').is(":hidden")){
                    $('#startTrackError01').show(50).delay(4500).hide();
                }
            }else{
                $('#startTrack').hide(50);
                $('#loadingTrack').show(50);
                $.ajax({
                    url: '{{ route("trackOrder.getOrderByCode") }}',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        orCode: co,
                        _token: '{{csrf_token()}}'
                    },
                    success: (respo) => {
                        if (respo == 'none'){
                            $('#loadingTrack').hide(50);
                            $('#noneRespoTrack').show(50);
                            $('#startTrack').show(50);
                        }else{
                            var orInfo = "";
                            $('#showOrderTrack').html('');
                            $('#startTrack').hide(50);
                            $('#loadingTrack').hide(50);
                            $('#noneRespoTrack').hide(50);
                            $('#showOrderTrack').show(50);

                            var theDateTime= respo.created_at;
                            var theDate = theDateTime.split(' ')[0];
                            var theDate2D = theDate.split('-');
                            var theTime = theDateTime.split(' ')[1];
                            var theTime2D = theTime.split(':');

                            var shf = respo.shifra;

                            rInfo = '<div class="container allOrdersTrack p-1 mb-1 pb-4" style="background-color:white; border-radius:30px; " id="orderTrack'+respo.shifra+'">'+
                                        '<div class="row">'+
                                            '<div class="col-lg-4 col-sm-6 col-6 text-center">'+
                                                '<p>'+theDate2D[2]+'/'+theDate2D[1]+'/'+theDate2D[0]+'</p>'+
                                            '</div>'+
                                            '<div class="col-lg-4 col-sm-6 col-6 text-center">'+
                                                '<p><strong>'+theTime2D[0]+':'+theTime2D[1]+'</strong></p>'+
                                            '</div>'+
                                            '<div class="col-lg-4 col-sm-12 col-12 text-center" style="margin-top: -10px;">'+
                                                '<p>Zahlungsmethode : '+respo.payM+'</p>'+
                                            '</div>'+
                                            '<div class="col-lg-4 col-sm-12 col-12 text-center" style="margin-top: -10px;">'+
                                                '<p style="font-size:23px; color:rgb(39,190,175)"><strong>'+respo.shuma+' <sup>CHF</sup></strong></p>'+
                                            '</div>'+
                                            '<div class="col-lg-4 col-sm-12 col-12 text-center" style="margin-top: -10px;">'+
                                                '<p style="font-size:23px; color:rgb(39,190,175)"><strong>'+$('#res'+respo.Restaurant).val()+'</strong></p>'+
                                            '</div>'+
                                            '<div class="col-lg-4 col-sm-12 col-12 text-center" style="margin-top: -10px;">'+
                                                '<p>Verfolgungsnummer: <strong style="color:rgb(39,190,175);">'+shf.split('|')[1]+'</strong></p>'+
                                            '</div>';
                        
                                            if(respo.tipPer != 0){
                            rInfo +=        '<div class="col-12 text-center p-1" style="margin-top: -10px;">'+
                                                '<p>Kellner Trinkgeld: <strong>'+respo.tipPer+' CHF</strong></p>'+
                                            '</div>'
                                            }
                            rInfo +=        '<div class="col-12 text-center">';
                            
                                                if(respo.statusi == 0){
                            rInfo +=                '<h3 style="color:#C9C90A"><strong>In der Warteschleife</strong></h3>';
                                                }else if(respo.statusi == 1){
                            rInfo +=                '<h3 style="color:#449ADB"><strong>Bestätigt</strong></h3>';
                                                }else if(respo.statusi == 2){
                            rInfo +=                '<h3 style="color:#FD0707"><strong>Annulliert</strong></h3>';
                                                }else if(respo.statusi == 3){
                            rInfo +=                '<h3 style="color:#05D10B"><strong>Abgeschlossen</strong></h3>';
                                                }

                                                if (respo.statusi != 3){
                            rInfo +=                 '<p class="text-center alert alert-info p-1 mt-2 mb-2"><i style="margin:0px;" class="fas fa-exclamation"></i> Sobald der Admin die Bestellung abgeschlossen hat, erscheint die Rechnung</p>';
                                                }
                            rInfo +=        '</div>'+
                                        '</div>';
                                        if(respo.statusi == 3){
                            rInfo +=    '<div class="row p-1">'+
                                            '<div class="col-lg-12 col-sm-12 col-12 text-center">'+
                                                '<a style="padding-top:0px ; padding-bottom:0px;" class="btn btn-block btn-outline-dark" href="generatePDF/'+respo.id+'||'+respo.digitalReceiptQRKHash+'">'+
                                                    '<strong><i style="margin:0px;" class="fas fa-file-pdf"></i> Rechnung herunterladen</strong>'+
                                                '</a>'+ 
                                                '<hr style="margin-top:4px ; margin-bottom:4px ;">'+
                                                '<input style="width:100%" type="email" class="form-control shadow-none mb-1" id="sendReceiptToEmailInput" placeholder="name@qrorpa.ch">'+
                                                '<button class="btn btn-block btn-outline-dark" onclick="sendReceiptToEmail(\''+respo.id+'\')"><strong>'+
                                                    '<i style="margin:0px; padding:0px;" class="fas fa-envelope"></i> Rechnung an E-Mail senden</strong>'+
                                                '</button>'+
                                                '<div id="sendReceiptToEmailError01" style="display:none;" class="alert alert-danger text-center p-1 mt-1">'+
                                                    'Bitte schreiben Sie zuerst die E-Mail'+
                                                '</div>'+
                                                '<div id="sendReceiptToEmailError02" style="display:none;" class="alert alert-danger text-center p-1 mt-1">'+
                                                    'Diese E-Mail ist ungültig, bitte überprüfen Sie sie erneut'+
                                                '</div>'+
                                                '<div id="sendReceiptToEmailSuccess01" style="display:none;" class="alert alert-success text-center p-1 mt-1">'+
                                                    'Die Quittung wurde erfolgreich an die E-Mail gesendet.'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>';
                                        }

                            rInfo +=    '<div class="mt-4 d-flex flex-wrap justify-content-between" style="width:100%;" >';
                                            var resPorosia = respo.porosia;
                                            var resPorosia2D = resPorosia.split('---8---');
                                            $.each(resPorosia2D, function(key,pors) {   
                                                var porsOne = pors.split('-8-');
                            rInfo +=        '<div style="width:8%; border-bottom:1px solid lightgray;">'+
                                                '<p>'+porsOne[3]+' X</p>'+
                                            '</div>'+
                                            '<div style="width:47%; border-bottom:1px solid lightgray;">'+
                                                '<p><strong>'+porsOne[0]+' ';

                                                if(porsOne[5] != '' && porsOne[5] != 'empty' && porsOne[5] != 'none'){
                            rInfo +=            '<br>'+
                                                '<span style="opacity:0.8;">( '+porsOne[5]+' )</span>';
                                                }

                            rInfo +=            '</strong></p>'+
                                                '<p style="margin-top:-15px;">'+porsOne[1]+'</p>';
                                                if(porsOne[6] != ''){
                            rInfo +=            '<p><strong>Comment :</strong> '+porsOne[6]+' </p>';
                                                }
                            rInfo +=        '</div>'+
                                            '<div class="pl-1" style="width:30%; border-bottom:1px solid lightgray;">';
                                            var stepEx = 1;
                                            if(porsOne[2] != 'empty'){
                                                var resPorExtra2D = porsOne[2].split('--0--');
                                                $.each(resPorExtra2D, function(key,extOne) { 
                                                    if(stepEx++ == 1){
                            rInfo +=                    '<p>'+extOne.split('||')[0]+'</p>';
                                                    }else{
                            rInfo +=                    '<p style="margin-top:-15px;">'+extOne.split('||')[0]+'</p>';
                                                    }
                                                });
                                            }

                            rInfo +=        '</div>'+
                                            '<div style="width:14%; border-bottom:1px solid lightgray;">'+
                                                '<p><strong>'+porsOne[4]+'</strong><sup style="font-size:10px;">CHF</sup></p>'+
                                            '</div>';
                                            });  
                            rInfo +=    '</div>';

                            $('#showOrderTrack').append(rInfo);
                        }
                    },
                    error: (error) => {
                        console.log(error);
                    }
                });
            }
        }







        function sendReceiptToEmail(oId){
            var sEmail = $('#sendReceiptToEmailInput').val();
            var filterEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if ($.trim(sEmail).length == 0) {
                if($("#sendReceiptToEmailError01").is(":hidden")){ $('#sendReceiptToEmailError01').show(50).delay(4500).hide(50); }
            }else if(!filterEmail.test(sEmail)){
                if($("#sendReceiptToEmailError02").is(":hidden")){ $('#sendReceiptToEmailError02').show(50).delay(4500).hide(50); }
            }else{
                $.ajax({
					url: '{{ route("trackOrder.sendReceiptToEmail") }}',
					method: 'post',
					data: {
						email: sEmail,
                        orderId: oId,
						_token: '{{csrf_token()}}'
					},
					success: () => {
						$('#sendReceiptToEmailInput').val('');
                        if($("#sendReceiptToEmailSuccess01").is(":hidden")){ $('#sendReceiptToEmailSuccess01').show(50).delay(4500).hide(50); }
					},
					error: (error) => { console.log(error); }
				});
            }
        }
    </script>
@endsection