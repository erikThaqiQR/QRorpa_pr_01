<?php
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','RechnungMngAcce']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }

    use App\emailReceiptFromAdm;
    use App\Orders;
    use App\OrdersPassive;
    use App\User;
    use App\rechnungClient;
    use App\rechnungClientToBills;
    use App\giftCardRechnungPay;

?>

 <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
 @include('fontawesome')

<div class="p-3 pb-5">
    <div class="d-flex justify-content-between mt-2 mb-2">
        <h3 style="color:rgb(39,190,175); width:100%;"><strong>Rechnungsverwaltung</strong></h3>
    </div>
    <hr>
       
    <div id="clientsRechnungList" class="d-flex flex-wrap justify-content-start" style="max-height: 200px; width:100%; overflow-y: scroll;">
        @if(isset($_GET['clS']) && $_GET['clS'] == 0)
        <a class="btn btn-dark shadow-none mb-1" href="{{route('dash.rechnungPage',['clS' => '0'])}}"
        style="text-align:left; width:33%; min-height:40px; margin-left:0.15%; margin-right:0.15%;" id="clientOneRechnung0">
        @else
        <a class="btn btn-outline-dark shadow-none mb-1" href="{{route('dash.rechnungPage',['clS' => '0'])}}"
        style="text-align:left; width:33%; min-height:40px; margin-left:0.15%; margin-right:0.15%;" id="clientOneRechnung0">
        @endif
            <strong>Andere - keinem Kunden zugeordnet</strong>
        </a>
             
        @foreach (rechnungClient::where('toRes',Auth::user()->sFor)->get() as $clOne)
            @if(isset($_GET['clS']))
                @if($_GET['clS'] == $clOne->id)
                <a class="btn btn-dark shadow-none mb-1" style="text-align:left; width:25%; min-height:40px; margin-left:0.15%;" id="clientOneRechnung{{$clOne->id}}">
                @else
                <a class="btn btn-outline-dark shadow-none mb-1" href="{{route('dash.rechnungPage',['clS' => $clOne->id])}}"
                style="text-align:left; width:25%; min-height:40px; margin-left:0.15%;" id="clientOneRechnung{{$clOne->id}}">
                @endif
                    <strong>{{$clOne->name}} {{$clOne->lastname}} - "{{$clOne->firmaName}}" - {{$clOne->street}} {{$clOne->plzort}}</strong>
                </a>

                @if($_GET['clS'] == $clOne->id)
                <button class="btn btn-dark shadow-none mb-1" style="text-align:center; width:4%; min-height:40px;" 
                id="clientOneRechnungModal{{$clOne->id}}" data-toggle="modal" data-target="#clDaysToPayModal" onclick="prepClDaysToPay('{{$clOne->id}}')">
                @else
                <button class="btn btn-outline-dark shadow-none mb-1" style="text-align:center; width:4%; min-height:40px;" 
                id="clientOneRechnungModal{{$clOne->id}}" data-toggle="modal" data-target="#clDaysToPayModal" onclick="prepClDaysToPay('{{$clOne->id}}','{{$clOne->name}}','{{$clOne->lastname}}')">
                @endif
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>

                @if($_GET['clS'] == $clOne->id)
                <button class="btn btn-dark shadow-none mb-1" style="text-align:center; width:4%; min-height:40px; margin-right:0.15%;" 
                id="clientsPDFMnthReBtn{{$clOne->id}}" data-toggle="modal" data-target="#clPdfMnthRechModal" onclick="prepClPdfMnthRech('{{$clOne->id}}')">
                @else
                <button class="btn btn-outline-dark shadow-none mb-1" style="text-align:center; width:4%; min-height:40px; margin-right:0.15%;" 
                id="clientsPDFMnthReBtn{{$clOne->id}}" data-toggle="modal" data-target="#clPdfMnthRechModal" onclick="prepClPdfMnthRech('{{$clOne->id}}','{{$clOne->name}}','{{$clOne->lastname}}')">
                @endif
                    <i class="fa-regular fa-file-lines"></i>
                </button>
            @else
                <a class="btn btn-outline-dark shadow-none mb-1" href="{{route('dash.rechnungPage',['clS' => $clOne->id])}}"
                style="text-align:left; width:25%; min-height:40px; margin-left:0.15%;" id="clientOneRechnung{{$clOne->id}}">
                    <strong>{{$clOne->name}} {{$clOne->lastname}} - "{{$clOne->firmaName}}" - {{$clOne->street}} {{$clOne->plzort}}</strong>
                </a>
                <button class="btn btn-outline-dark shadow-none mb-1" style="text-align:center; width:4%; min-height:40px;" 
                id="clientOneRechnungModal{{$clOne->id}}" data-toggle="modal" data-target="#clDaysToPayModal" onclick="prepClDaysToPay('{{$clOne->id}}')">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button class="btn btn-outline-dark shadow-none mb-1" style="text-align:center; width:4%; min-height:40px; margin-right:0.15%;" 
                id="clientsPDFMnthReBtn{{$clOne->id}}" data-toggle="modal" data-target="#clPdfMnthRechModal" onclick="prepClPdfMnthRech('{{$clOne->id}}','{{$clOne->name}}','{{$clOne->lastname}}')">
                    <i class="fa-regular fa-file-lines"></i>
                </button>
            @endif
        @endforeach
    </div>













    <!-- Set Days to Pay for Client - Modal -->
    <div class="modal" id="clDaysToPayModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clDaysToPayModalHead1">
                        Die Anzahl der Tage für diesen Kunden, um seine angesammelte Rechnung am Ende des Monats zu bezahlen!
                        <br>
                        <strong style="font-size:1.2rem;" id="clDaysToPayModalHead2"></strong>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="0" id="clDaysToPayClId">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control shadow-none" id="clDaysToPayDaysNr" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text" id="clDaysToPayDaysNrSpan">Tage zu zahlen</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" id="clDaysToPayModalBtn1" style="width:48%;" class="btn btn-dark shadow-none" data-dismiss="modal">Stornieren</button>
                    <button type="button" id="clDaysToPayModalBtn2" style="width:48%;" class="btn btn-success shadow-none" onclick="clDaysToPay()">Sparen</button>
                </div>
                <div class="alert alert-success text-center" id="clDaysToPaySucc01" style="display:none;">
                    <strong>Die neue Zahlungsperiode wurde erfolgreich gespeichert</strong>
                </div>
                <div class="alert alert-danger text-center" id="clDaysToPayErr01" style="display:none;">
                    <strong>Bitte geben Sie einen gültigen Wert ein!</strong>
                </div>
                <div class="alert alert-danger text-center" id="clDaysToPayErr02" style="display:none;">
                    <strong>Die Tage der Zahlungsfrist für unseren Kunden können nur zwischen 1 und 28 liegen!</strong>
                </div>
            </div>
        </div>
    </div>

    <script>
        function prepClDaysToPay(clId){
            $.ajax({
				url: '{{ route("emailBill.getDataClDayToPay") }}',
				method: 'post',
				data: {
					clientId: clId,
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = $.trim(respo);
                    respo2D = respo.split('||');
					$('#clDaysToPayClId').val(clId);
                    $('#clDaysToPayDaysNr').val(respo2D[0]);
                    $('#clDaysToPayModalHead2').html(respo2D[1]+' '+respo2D[2]);
				},
				error: (error) => {
					console.log(error);
				}
			});
        }
        function clDaysToPay(){
            $('#clDaysToPayModalBtn1').prop('disabled', true);
            $('#clDaysToPayModalBtn2').prop('disabled', true);
            if(!$('#clDaysToPayDaysNr').val()){
                if($('#clDaysToPayErr01').is(':hidden')){ $('#clDaysToPayErr01').show(50).delay(4500).hide(50); }
                $('#clDaysToPayModalBtn1').prop('disabled', false);
                $('#clDaysToPayModalBtn2').prop('disabled', false);
            }else if($('#clDaysToPayDaysNr').val() < 1 || $('#clDaysToPayDaysNr').val() > 28){
                if($('#clDaysToPayErr02').is(':hidden')){ $('#clDaysToPayErr02').show(50).delay(4500).hide(50); }
                $('#clDaysToPayModalBtn1').prop('disabled', false);
                $('#clDaysToPayModalBtn2').prop('disabled', false);
            }else{
                $.ajax({
                    url: '{{ route("emailBill.setDaysClDayToPay") }}',
                    method: 'post',
                    data: {
                        clientId: $('#clDaysToPayClId').val(),
                        newDa: $('#clDaysToPayDaysNr').val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        if($('#clDaysToPaySucc01').is(':hidden')){ $('#clDaysToPaySucc01').show(50).delay(4500).hide(50); }
                        $('#clDaysToPayModalBtn1').prop('disabled', false);
                        $('#clDaysToPayModalBtn2').prop('disabled', false);
                    },
                    error: (error) => {
                        console.log(error);
                    }
                });
            }
        }
    </script>














    <!-- Download clients monthly bills - Modal -->
    <div class="modal" id="clPdfMnthRechModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clPdfMnthRechModalHead1">
                        Diese Rechnungen wurden automatisch an diesen Kunden gesendet!
                        <br>
                        <strong style="font-size:1.2rem;" id="clPdfMnthRechModalHead2"></strong>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-start" id="clPdfMnthRechModalBody">
                   <p style="color:rgb(72,81,87);"><strong></strong></p>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                 
                </div>
              
            </div>
        </div>
    </div>
    <script>
        function zerroFront (str, max) {
            str = str.toString();
            return str.length < max ? zerroFront("0" + str, max) : str;
        }

        function prepClPdfMnthRech(clId, clName, clLastname){
            $('#clPdfMnthRechModalBody').html('');
            $('#clPdfMnthRechModalHead2').html(clName+' '+clLastname);
            $.ajax({
				url: '{{ route("mRechFCl.getClPdfBills") }}',
				method: 'post',
                dataType: 'json',
				data: {
					clientId: clId,
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    if(Object.keys(respo).length > 0){
                        $.each(respo, function(index, value){
                            listing = ' <div class="card text-center" style="width: 24.5%; margin:5px 0.25% 5px 0.25%;">'+
                                            '<i style="color:rgb(39,190,175);" class="mt-2 card-img-top fa-3x fa-solid fa-file-pdf"></i>'+
                                            '<div class="card-body" style="padding:2px !important;">'+
                                                '<h5 class="card-title mt-2 mb-2"><strong>'+zerroFront(value.forMonth, 2)+'.'+value.forYear+'</strong></h5>'+
                                                '<a href="storage/rechnungMonthlyFinal/'+value.pdfBill+'" style="margin:0px;" class="btn btn-outline-success btn-block shadow-none" download>'+
                                                    '<i style="color:rgb(39,190,175);" class="fa-solid fa-download"></i>'+
                                                '</a>'+
                                            '</div>'+
                                        '</div>';
                            $('#clPdfMnthRechModalBody').append(listing);
                        });
                    }else{
                        $('#clPdfMnthRechModalBody').append('');
                    }
					
				},
				error: (error) => { console.log(error); }
			});
        }
    </script>












    <hr>

    @if (isset($_GET['clS']) && $_GET['clS'] != 0)
            
        <div class="d-flex justify-content-between">
            <p class="text-center" style="width:16.6%"><strong>Auftragsnummer</strong></p>
            <p class="text-center" style="width:16.6%"><strong>Firma / vollständiger Name</strong></p>
            <p class="text-center" style="width:16.6%"><strong>Strasse-Nr / PLZ-ORT</strong></p>
            <p class="text-center" style="width:16.6%"><strong>Telefonnummer / E-mail</strong></p>
            <p class="text-center" style="width:16.6%"><strong>Termin / Endtermin</strong></p>
            <p class="text-center" style="width:16.6%"><strong>Erinnerung / Rechnung</strong></p>
        </div>
        <hr>
    
        <div id="notConfirmedBillsDiv">
            @php
                $clS = $_GET['clS'];
                $emailReceipts = emailReceiptFromAdm::where('forRes',Auth::user()->sFor)
                    ->whereIn('statusConf', ['0', '9'])
                    ->where('rechnung_client_to_bills.clientId', $clS)
                    ->join('rechnung_client_to_bills', 'email_receipt_from_adms.id', '=', 'rechnung_client_to_bills.billId')
                    ->select([
                        DB::raw("'order' as type"),
                        'email_receipt_from_adms.id as id',
                        'rechnung_client_to_bills.clientId as clId',
                        'email_receipt_from_adms.exInfoName as clientName',
                        'email_receipt_from_adms.exInfoLastname as clientLastName',
                        'email_receipt_from_adms.exInfoFirma as clientFirma',
                        'email_receipt_from_adms.exInfoClPhoneNr as clientPhoneNr',
                        'email_receipt_from_adms.exInfoStreet as clientStreet',
                        'email_receipt_from_adms.exInfoEmail as clientEmail',
                        'email_receipt_from_adms.exInfoPlzOrt as clientPlzOrt',
                        'email_receipt_from_adms.exInfoDaysToPay as clientDaysToPay',
                        'email_receipt_from_adms.forOrder as forOrder',
                        'email_receipt_from_adms.theComm as theComm',
                        'email_receipt_from_adms.created_at as created_at',
                        'email_receipt_from_adms.statusConf as paymentStatus',
                        DB::raw('NULL as price'),
                        'email_receipt_from_adms.statusConfBy as confirmedBy',
                        DB::raw('NULL as giftCardId')
                        ])
                    ->orderByDesc('created_at')
                    ->get();

                $giftCards = giftCardRechnungPay::where('gift_cards.toRes', Auth::user()->sFor)
                    ->where('gift_card_rechnung_pays.clientId', $clS)
                    ->join('gift_cards', 'gift_cards.id', '=', 'gift_card_rechnung_pays.gcId')
                    ->select(
                        DB::raw("'gift_card' as type"),
                        'gift_card_rechnung_pays.id as id',
                        'gift_card_rechnung_pays.clientId as clId',
                        'gift_card_rechnung_pays.clName as clientName',
                        'gift_card_rechnung_pays.clLastName as clientLastName',
                        'gift_card_rechnung_pays.clFirma as clientFirma',
                        'gift_card_rechnung_pays.clPhoneNr as clientPhoneNr',
                        'gift_card_rechnung_pays.clStreetNr as clientStreet',
                        'gift_card_rechnung_pays.clEmail as clientEmail',
                        'gift_card_rechnung_pays.clPlzOrt as clientPlzOrt',
                        'gift_card_rechnung_pays.clDaysToPay as clientDaysToPay',
                        DB::raw('NULL as forOrder'),
                        DB::raw('NULL as theComm'),
                        'gift_card_rechnung_pays.created_at as created_at',
                        'gift_card_rechnung_pays.status_confirmed as paymentStatus',
                        'gift_cards.gcSumInChf as price',
                        'gift_card_rechnung_pays.status_confirmed_by as confirmedBy',
                        'gift_cards.id as giftCardId'
                    )
                    ->orderByDesc('created_at')
                    ->get();

                $merged = $emailReceipts->merge($giftCards)->sortBy('created_at');

                $notConfirmed = $merged->filter(function($item) {
                    return $item->paymentStatus === 0;
                });

                $confirmed = $merged->filter(function($item) {
                    return $item->paymentStatus === 9 || $item->paymentStatus === 1;
                });
            @endphp

            @foreach ($notConfirmed as $oneRecNot)
                <?php
                    $stD2d = explode('-',explode(' ',$oneRecNot->created_at)[0]);
                    $endD = date('d-m-Y', mktime(0, 0, 0, $stD2d[1], $stD2d[2] + $oneRecNot->clientDaysToPay, $stD2d[0]));
                    $endD2d = explode('-',$endD);

                    $dt1 = time();
                    $dt2 = strtotime($endD2d[2].'-'.$endD2d[1].'-'.$endD2d[0]);
                    $datediff = $dt2 - $dt1;
                    $daysLeft = round($datediff / (60 * 60 * 24));
                ?>
                @if($daysLeft > 5 )
                    <div class="d-flex flex-wrap justify-content-between pt-1 pb-1 mb-2" style="background-color:rgba(39,190,175,0.4); border-radius:4px;">
                @elseif($daysLeft <= 5 && $daysLeft > 3)
                    <div class="d-flex flex-wrap justify-content-between pt-1 pb-1 mb-2" style="background-color:rgba(23,162,184,0.4); border-radius:4px;">
                @elseif($daysLeft == 3)
                    <div class="d-flex flex-wrap justify-content-between pt-1 pb-1 mb-2" style="background-color:rgba(247, 249, 137,0.4); border-radius:4px;">
                @elseif($daysLeft <= 2)
                    <div class="d-flex flex-wrap justify-content-between pt-1 pb-1 mb-2" style="background-color:rgba(220,53,69,0.4); border-radius:4px;">
                @endif
                    <?php
                        if($oneRecNot->type === 'order'){
                            $thisOrderAR = OrdersPassive::find($oneRecNot->forOrder);

                            if($thisOrderAR->inCashDiscount > 0){
                                $totShuma = number_format($thisOrderAR->shuma-$thisOrderAR->inCashDiscount, 2, '.', '');
                                $totZbritja = number_format($thisOrderAR->inCashDiscount, 2, '.', '');
                            }else if($thisOrderAR->inPercentageDiscount > 0){
                                $totShuma = number_format($thisOrderAR->shuma-(($thisOrderAR->shuma - $thisOrderAR->tipPer)*($thisOrderAR->inPercentageDiscount*0.01)), 2, '.', '');
                                $totZbritja = number_format(($thisOrderAR->shuma - $thisOrderAR->tipPer)*($thisOrderAR->inPercentageDiscount*0.01), 2, '.', '');
                            }else{
                                $totShuma = number_format($thisOrderAR->shuma, 2, '.', '');
                            }

                            $refId = $thisOrderAR->refId;
                        } else if($oneRecNot->type == 'gift_card'){
                            $totShuma = number_format($oneRecNot->price, 2, '.', '');
                            $refId = $oneRecNot->id;
                        }
                    ?>
                    <p class="text-center" style="width:8.3%"># {{$refId}}</p>
                    <p class="text-center" style="width:8.3%">{{$totShuma}} CHF</p>
                    <p class="text-center" style="width:16.6%">{{$oneRecNot->clientFirma}}</p>
                    <p class="text-center" style="width:16.6%">{{$oneRecNot->clientStreet}}</p>
                    <p class="text-center" style="width:16.6%">{{$oneRecNot->clientPhoneNr}}</p>
                    <p class="text-center" style="width:16.6%">{{$stD2d[2]}}-{{$stD2d[1]}}-{{$stD2d[0]}}</p>
                    <button id="sndRemEmBtn{{ $oneRecNot->type == 'order' ? $oneRecNot->id : $oneRecNot->giftCardId }}" class="btn btn-info shadow-none" style="width:16.6%" onclick="sendReminderEmail('{{ $oneRecNot->type == 'order' ? $oneRecNot->id : $oneRecNot->giftCardId }}', '{{ $oneRecNot->type }}')">
                        <i class="fas fa-at"></i> <strong>Erinnerung</strong>
                    </button>
                    <button style="width:16.6%;" class="btn btn-success mt-1 shadow-none" onclick="confPaymentOpen('{{$oneRecNot->id}}','{{ $oneRecNot->type == 'order' ? $oneRecNot->forOrder : $oneRecNot->id }}', '{{ $oneRecNot->type }}')" data-toggle="modal" data-target="#billPayConfModal">
                        <strong>Zahlung bestätigen</strong>
                    </button>

                    <p class="text-center mt-1" style="width:16.6%">{{$oneRecNot->clientName}} {{$oneRecNot->clientLastName}}</p>
                    <p class="text-center mt-1" style="width:16.6%">{{$oneRecNot->clientPlzOrt}}</p>
                    <p class="text-center mt-1" style="width:16.6%">{{$oneRecNot->clientEmail}}</p>
                    <p class="text-center mt-1" style="width:16.6%">({{$oneRecNot->clientDaysToPay}} Tage) {{$endD}}</p>
                    @if ($oneRecNot->type == 'order')
                        <form style="width:16.6%" method="post" action="{{ route('emailBill.rechnungGetBilMngPage') }}">
                            {{csrf_field()}}
                            <input type="hidden" value="{{$oneRecNot->id}}" name="emBiId">
                            <button type="submit" class="btn btn-info mt-1 shadow-none" style="width:100%"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> <strong>Rechnung</strong></button>
                        </form>
                    @else
                        <div style="width:16.6%" class="d-flex justify-content-between align-items-center gap-2">
                            <form style="width: 50%;" method="post" action="{{ route('giftCard.giftCardGetReceipt') }}">
                                {{csrf_field()}}         
                                <input  id="giftCardId" type="hidden" value="{{$oneRecNot->giftCardId}}" name="giftCardId">
                                <button type="submit" class="btn btn-info mt-1 shadow-none" style="width:100%"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> <strong>Rechnung</strong></button>
                            </form>
                            <a style="width: 50%;" class="btn btn-secondary mt-1 ml-1 shadow-none" href="{{ route('giftCard.rechnungDownloadGC', $oneRecNot->giftCardId) }}">
                                <i class="fa-solid fa-receipt"></i> <strong>Invoice</strong>
                            </a>
                        </div>
                    @endif

                    @if($oneRecNot->type == 'order' && $oneRecNot->theComm != 'empty')
                        <p class="text-center mt-1 mb-1" style="width:100%">
                            <strong>Kommentar: {{$oneRecNot->theComm }}</strong>
                        </p>
                    @endif

                    <div style="width:100%; display:none;" class="alert alert-success text-center mt-2" id="emailSendSuccess{{ $oneRecNot->type == 'order' ? $oneRecNot->id : $oneRecNot->giftCardId }}">
                        Eine E-Mail, um den Kunden an die verbleibenden Tage zur Zahlung der Rechnung zu erinnern, wurde erfolgreich gesendet
                    </div>
                    
                </div>
            @endforeach
        </div>
        
        <hr>
        <button id="showHideConfirmedBillsBtn" class="btn btn-info shadow-none" style="width:100%;" onclick="showHideCBD()">
            <strong><i class="fas fa-arrow-down"></i> Bestätigte / bezahlte Rechnungen anzeigen</strong>
        </button>
        <div id="confirmedBillsDiv" style="display:none;">
            <div class="d-flex justify-content-between mt-1">
                <p class="text-center" style="width:16.6%"><strong>Auftragsnummer</strong></p>
                <p class="text-center" style="width:16.6%"><strong>Firma / vollständiger Name</strong></p>
                <p class="text-center" style="width:16.6%"><strong>Strasse-Nr / PLZ-ORT</strong></p>
                <p class="text-center" style="width:16.6%"><strong>Telefonnummer / E-mail</strong></p>
                <p class="text-center" style="width:16.6%"><strong>Termin / Endtermin</strong></p>
                <p class="text-center" style="width:16.6%"><strong>Erinnerung / Rechnung</strong></p>
            </div>
            <div id="confirmedBillsList">
                @foreach ($confirmed as $oneRec)
                    <?php
                        $stD2d = explode('-',explode(' ',$oneRec->created_at)[0]);
                        $endD = date('d-m-Y', mktime(0, 0, 0, $stD2d[1], $stD2d[2] + $oneRec->clientDaysToPay, $stD2d[0]));
                        $tM = User::find($oneRec->confirmedBy);
                    ?>
            
                    <div class="d-flex justify-content-between pt-1 pb-1 mb-2" style="background-color:rgba(40,167,69,0.2); border-radius:4px;">
                        <div style="width:87.33%;" class="d-flex flex-wrap justify-content-between">
                            <?php
                                if($oneRec->type === 'order'){
                                    $thisOrderAR = OrdersPassive::find($oneRec->forOrder);
                                    if($thisOrderAR->inCashDiscount > 0){
                                        $totShuma = number_format($thisOrderAR->shuma-$thisOrderAR->inCashDiscount, 2, '.', '');
                                        $totZbritja = number_format($thisOrderAR->inCashDiscount, 2, '.', '');
                                    }else if($thisOrderAR->inPercentageDiscount > 0){
                                        $totShuma = number_format($thisOrderAR->shuma-(($thisOrderAR->shuma - $thisOrderAR->tipPer)*($thisOrderAR->inPercentageDiscount*0.01)), 2, '.', '');
                                        $totZbritja = number_format(($thisOrderAR->shuma - $thisOrderAR->tipPer)*($thisOrderAR->inPercentageDiscount*0.01), 2, '.', '');
                                    }else{
                                        $totShuma = number_format($thisOrderAR->shuma, 2, '.', '');
                                    }
                                    $refId = $thisOrderAR->refId;
                                } else if($oneRec->type == 'gift_card'){
                                    $totShuma = number_format($oneRec->price, 2, '.', '');
                                    $refId = $oneRec->id;
                                }
                            ?>
                            <p class="text-center" style="width:9.9%"># {{$refId}}</p>
                            <p class="text-center" style="width:9.9%">{{number_format($totShuma, 2, '.', '')}} CHF</p>
                            <p class="text-center" style="width:19.8%">{{$oneRec->clientFirma}}</p>
                            <p class="text-center" style="width:19.8%">{{$oneRec->clientStreet}}</p>
                            <p class="text-center" style="width:19.8%">{{$oneRec->clientPhoneNr}}</p>
                            <p class="text-center" style="width:19.8%">{{$stD2d[2]}}-{{$stD2d[1]}}-{{$stD2d[0]}}</p>
                    
                            @if($tM != NULL)
                                <p class="text-center mt-1 mb-1" style="width:19.8%">Von {{$tM->name}}</p>
                            @else
                                <p class="text-center mt-1 mb-1" style="width:19.8%">Von ---</p>
                            @endif
                            <p class="text-center mt-1 mb-1" style="width:19.8%">{{$oneRec->clientName}} {{$oneRec->clientLastName}}</p>
                            <p class="text-center mt-1 mb-1" style="width:19.8%">{{$oneRec->clientPlzOrt}}</p>
                            <p class="text-center mt-1 mb-1" style="width:19.8%">{{$oneRec->clientEmail}}</p>
                            <p class="text-center mt-1 mb-1" style="width:19.8%">{{$endD}}</p>

                            @if($oneRec->type == 'order' && $oneRec->theComm != 'empty')
                                <p class="text-center mt-1 mb-1" style="width:100%">
                                    <strong>Kommentar: {{$oneRec->theComm }}</strong>
                                </p>
                            @endif
                        </div>

                        @if ($oneRec->type == 'order')
                            <form style="width:12.66%" method="post" action="{{ route('emailBill.rechnungGetBilMngPage') }}">
                                {{csrf_field()}}         
                                <input type="hidden" value="{{$oneRec->id}}" name="emBiId">
                                <button type="submit" class="btn btn-info shadow-none" style="width:100%; height:100%;"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> <strong>Rechnung</strong></button>
                            </form>
                        @else
                            <div style="width:12.66%" class="d-flex flex-column">
                                <form style="width:100%;" method="post" action="{{ route('giftCard.giftCardGetReceipt') }}">
                                    {{csrf_field()}}         
                                    <input  id="giftCardId" type="hidden" value="{{$oneRec->giftCardId}}" name="giftCardId">
                                    <button type="submit" class="btn btn-info shadow-none" style="width:100%; height:100%;"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> <strong>Rechnung</strong></button>
                                </form>
                                <a style="width:100%;" class="btn btn-secondary mt-1 shadow-none" href="{{ route('giftCard.rechnungDownloadGC', $oneRec->giftCardId) }}">
                                    <i class="fa-solid fa-receipt"></i> <strong>Invoice</strong>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>



    @elseif (isset($_GET['clS']) && $_GET['clS'] == 0)

            <div class="d-flex justify-content-between">
                <p class="text-center" style="width:16.6%"><strong>Auftragsnummer</strong></p>
                <p class="text-center" style="width:16.6%"><strong>Firma / vollständiger Name</strong></p>
                <p class="text-center" style="width:16.6%"><strong>Strasse-Nr / PLZ-ORT</strong></p>
                <p class="text-center" style="width:16.6%"><strong>Telefonnummer / E-mail</strong></p>
                <p class="text-center" style="width:16.6%"><strong>Termin / Endtermin</strong></p>
                <p class="text-center" style="width:16.6%"><strong>Erinnerung / Rechnung</strong></p>
            </div>
            <hr>
        
            <div id="notConfirmedBillsDiv">
                @foreach (emailReceiptFromAdm::where([['forRes',Auth::user()->sFor],['statusConf','0']])->orderByDesc('created_at')->get() as $oneRecNot)
                    @if(rechnungClientToBills::where('billId',$oneRecNot->id)->first() == Null)
                        <?php
                            $stD2d = explode('-',explode(' ',$oneRecNot->created_at)[0]);
                            $endD = date('d-m-Y', mktime(0, 0, 0, $stD2d[1], $stD2d[2] + $oneRecNot->exInfoDaysToPay, $stD2d[0]));
                            $endD2d = explode('-',$endD);

                            $dt1 = time();
                            $dt2 = strtotime($endD2d[2].'-'.$endD2d[1].'-'.$endD2d[0]);
                            $datediff = $dt2 - $dt1;
                            $daysLeft = round($datediff / (60 * 60 * 24));
                        ?>
                        @if($daysLeft > 5 )
                            <div class="d-flex flex-wrap justify-content-between pt-1 pb-1 mb-2" style="background-color:rgba(39,190,175,0.4); border-radius:4px;">
                        @elseif($daysLeft <= 5 && $daysLeft > 3)
                            <div class="d-flex flex-wrap justify-content-between pt-1 pb-1 mb-2" style="background-color:rgba(23,162,184,0.4); border-radius:4px;">
                        @elseif($daysLeft == 3)
                            <div class="d-flex flex-wrap justify-content-between pt-1 pb-1 mb-2" style="background-color:rgba(247, 249, 137,0.4); border-radius:4px;">
                        @elseif($daysLeft <= 2)
                            <div class="d-flex flex-wrap justify-content-between pt-1 pb-1 mb-2" style="background-color:rgba(220,53,69,0.4); border-radius:4px;">
                        @endif
                            <?php
                                $thisOrderAR = OrdersPassive::find($oneRecNot->forOrder);
                                $totShuma = number_format(0, 2, '.', '');
                                if($thisOrderAR->inCashDiscount > 0){
                                    $totShuma = number_format($thisOrderAR->shuma-$thisOrderAR->inCashDiscount, 2, '.', '');
                                    $totZbritja = number_format($thisOrderAR->inCashDiscount, 2, '.', '');
                                }else if($thisOrderAR->inPercentageDiscount > 0){
                                    $totShuma = number_format($thisOrderAR->shuma-(($thisOrderAR->shuma - $thisOrderAR->tipPer)*($thisOrderAR->inPercentageDiscount*0.01)), 2, '.', '');
                                    $totZbritja = number_format(($thisOrderAR->shuma - $thisOrderAR->tipPer)*($thisOrderAR->inPercentageDiscount*0.01), 2, '.', '');
                                }else{
                                    $totShuma = $thisOrderAR->shuma;
                                }
                            ?>
                            <p class="text-center" style="width:8.3%"># {{$thisOrderAR->refId}}</p>
                            <p class="text-center" style="width:8.3%">{{number_format($totShuma, 2, '.', '')}} CHF</p>
                            <p class="text-center" style="width:16.6%">{{$oneRecNot->exInfoFirma}}</p>
                            <p class="text-center" style="width:16.6%">{{$oneRecNot->exInfoStreet}}</p>
                            <p class="text-center" style="width:16.6%">{{$oneRecNot->exInfoClPhoneNr}}</p>
                            <p class="text-center" style="width:16.6%">{{$stD2d[2]}}-{{$stD2d[1]}}-{{$stD2d[0]}}</p>
                            <button id="sndRemEmBtn{{$oneRecNot->id}}" class="btn btn-info shadow-none" style="width:16.6%" onclick="sendReminderEmail('{{ $oneRecNot->type == 'order' ? $oneRecNot->id : $oneRecNot->giftCardId }}', '{{ $oneRecNot->type }}')">
                                <i class="fas fa-at"></i> <strong>Erinnerung</strong>
                            </button>

                            <button style="width:16.6%;" class="btn btn-success mt-1 shadow-none" onclick="confPaymentOpen('{{$oneRecNot->id}}','{{$oneRecNot->forOrder}}')" data-toggle="modal" data-target="#billPayConfModal">
                                <strong>Zahlung bestätigen</strong>
                            </button>
                            <p class="text-center mt-1" style="width:16.6%">{{$oneRecNot->exInfoName}} {{$oneRecNot->exInfoLastname}}</p>
                            <p class="text-center mt-1" style="width:16.6%">{{$oneRecNot->exInfoPlzOrt}}</p>
                            <p class="text-center mt-1" style="width:16.6%">{{$oneRecNot->exInfoEmail}}</p>
                            <p class="text-center mt-1" style="width:16.6%">({{$oneRecNot->exInfoDaysToPay}} Tage) {{$endD}}</p>
                            <form style="width:16.6%" method="post" action="{{ route('emailBill.rechnungGetBilMngPage') }}">
                                {{csrf_field()}}         
                                <input type="hidden" value="{{$oneRecNot->id}}" name="emBiId">
                                <button type="submit" class="btn btn-info mt-1 shadow-none" style="width:100%"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> <strong>Rechnung</strong></button>
                            </form>

                            @if($oneRecNot->theComm != 'empty')
                                <p class="text-center mt-1 mb-1" style="width:100%">
                                    <strong>Kommentar: {{$oneRecNot->theComm }}</strong>
                                </p>
                            @endif
                        
                            
                            <div style="width:100%; display:none;" class="alert alert-success text-center mt-2" id="emailSendSuccess{{$oneRecNot->id}}">
                                Eine E-Mail, um den Kunden an die verbleibenden Tage zur Zahlung der Rechnung zu erinnern, wurde erfolgreich gesendet
                            </div>
                            
                        </div>
                    @endif
                @endforeach
            </div>
            
            <hr>
            <button id="showHideConfirmedBillsBtn" class="btn btn-info shadow-none" style="width:100%;" onclick="showHideCBD()">
                <strong><i class="fas fa-arrow-down"></i> Bestätigte / bezahlte Rechnungen anzeigen</strong>
            </button>
            <div id="confirmedBillsDiv" style="display:none;">
                <div class="d-flex justify-content-between mt-1">
                    <p class="text-center" style="width:16.6%"><strong>Auftragsnummer</strong></p>
                    <p class="text-center" style="width:16.6%"><strong>Firma / vollständiger Name</strong></p>
                    <p class="text-center" style="width:16.6%"><strong>Strasse-Nr / PLZ-ORT</strong></p>
                    <p class="text-center" style="width:16.6%"><strong>Telefonnummer / E-mail</strong></p>
                    <p class="text-center" style="width:16.6%"><strong>Termin / Endtermin</strong></p>
                    <p class="text-center" style="width:16.6%"><strong>Erinnerung / Rechnung</strong></p>
                </div>
                <div id="confirmedBillsList">
                    @foreach (emailReceiptFromAdm::where([['forRes',Auth::user()->sFor],['statusConf','9']])->orderByDesc('created_at')->get() as $oneRec)
                        @if(rechnungClientToBills::where('billId',$oneRec->id)->first() == Null)
                            <?php
                                $stD2d = explode('-',explode(' ',$oneRec->created_at)[0]);
                                $endD = date('d-m-Y', mktime(0, 0, 0, $stD2d[1], $stD2d[2] + $oneRec->exInfoDaysToPay, $stD2d[0]));
                                $tM = User::find($oneRec->statusConfBy);
                            ?>
                        
                            <div class="d-flex justify-content-between pt-1 pb-1 mb-2" style="background-color:rgba(40,167,69,0.2); border-radius:4px;">
                                <div style="width:87.33%;" class="d-flex flex-wrap justify-content-between">
                                    <?php
                                        $thisOrderAR = OrdersPassive::find($oneRec->forOrder);
                                        $totShuma = number_format(0, 2, '.', '');
                                        if($thisOrderAR->inCashDiscount > 0){
                                            $totShuma = number_format($thisOrderAR->shuma-$thisOrderAR->inCashDiscount, 2, '.', '');
                                            $totZbritja = number_format($thisOrderAR->inCashDiscount, 2, '.', '');
                                        }else if($thisOrderAR->inPercentageDiscount > 0){
                                            $totShuma = number_format($thisOrderAR->shuma-(($thisOrderAR->shuma - $thisOrderAR->tipPer)*($thisOrderAR->inPercentageDiscount*0.01)), 2, '.', '');
                                            $totZbritja = number_format(($thisOrderAR->shuma - $thisOrderAR->tipPer)*($thisOrderAR->inPercentageDiscount*0.01), 2, '.', '');
                                        }else{
                                            $totShuma = $thisOrderAR->shuma;
                                        }
                                    ?>
                                    <p class="text-center" style="width:9.9%"># {{$thisOrderAR->refId}}</p>
                                    <p class="text-center" style="width:9.9%">{{number_format($totShuma, 2, '.', '')}} CHF</p>
                                    <p class="text-center" style="width:19.8%">{{$oneRec->exInfoFirma}}</p>
                                    <p class="text-center" style="width:19.8%">{{$oneRec->exInfoStreet}}</p>
                                    <p class="text-center" style="width:19.8%">{{$oneRec->exInfoClPhoneNr}}</p>
                                    <p class="text-center" style="width:19.8%">{{$stD2d[2]}}-{{$stD2d[1]}}-{{$stD2d[0]}}</p>
                            
                                    @if($tM != NULL)
                                    <p class="text-center mt-1 mb-1" style="width:19.8%">Von {{$tM->name}}</p>
                                    @else
                                    <p class="text-center mt-1 mb-1" style="width:19.8%">Von ---</p>
                                    @endif
                                    <p class="text-center mt-1 mb-1" style="width:19.8%">{{$oneRec->exInfoName}} {{$oneRec->exInfoLastname}}</p>
                                    <p class="text-center mt-1 mb-1" style="width:19.8%">{{$oneRec->exInfoPlzOrt}}</p>
                                    <p class="text-center mt-1 mb-1" style="width:19.8%">{{$oneRec->exInfoEmail}}</p>
                                    <p class="text-center mt-1 mb-1" style="width:19.8%">{{$endD}}</p>

                                    @if($oneRec->theComm != 'empty')
                                        <p class="text-center mt-1 mb-1" style="width:100%">
                                            <strong>Kommentar: {{$oneRec->theComm }}</strong>
                                        </p>
                                    @endif

                                </div>
                                <form style="width:12.66%" method="post" action="{{ route('emailBill.rechnungGetBilMngPage') }}">
                                    {{csrf_field()}}         
                                    <input type="hidden" value="{{$oneRec->id}}" name="emBiId">
                                    <button type="submit" class="btn btn-info shadow-none" style="width:100%; height:100%;"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> <strong>Rechnung</strong></button>
                                </form>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>


        </div>

    @else
            <p style="color:rgb(39,190,175); font-size:1.7rem;" class="text-center"><strong>Wählen Sie zuerst einen Kunden aus!</strong></p>
        </div>
    @endif





















    
        <!-- Confirm Bill payment Modal  -->
        <div class="modal" id="billPayConfModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="billPayConfModalTitle"><strong>Bestätigung der Rechnungszahlung</strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cancelConfPayment()">
                            <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                        </button>
                    </div>
                    <div class="modal-body d-flex flex-wrap justify-content-between">
                        <p style="width: 100%; font-size:1.4rem;"><strong>Rechnungszahlungsbestätigung für Bestellnummer : <span id="billPayConfOrId"></span></strong></p>
                        <input type="hidden" id="billPayConfEmBiId" value="0">
                        <input type="hidden" id="type" value="">
                        <button style="width: 49%;" class="btn btn-danger" data-dismiss="modal" onclick="cancelConfPayment()">Absagen</button>
                        <button style="width: 49%;" class="btn btn-success" onclick="confPayment()">Fortsetzen</button>
                        <div style="width: 100%;" class="text-center alert alert-danger mt-1">
                            <strong>Diese Bestätigung ist endgültig. Wenn Sie sich entscheiden, fortzufahren, wird Ihr Name als Bestätiger der Rechnungszahlung angezeigt</strong>
                        </div>

                        <div style="width:100%; display:none;" class="alert alert-danger text-center mt-2" id="billPayConfModalErr1">
                            Der Status dieser Rechnung wurde kürzlich von Ihren Mitarbeitern geändert
                        </div>
                        <div style="width:100%; display:none;" class="alert alert-danger text-center mt-2" id="billPayConfModalErr2">
                            Es ist eine Fehlfunktion im System aufgetreten, bitte aktualisieren Sie die Seite
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function sendReminderEmail(emRId, type){
                $('#sndRemEmBtn'+emRId).prop('disabled', true);
                $('#sndRemEmBtn'+emRId).html('<img src="storage/gifs/loading2.gif" style="width:24px;" alt="">');
                
                let routeUrl = type == 'order' ? '{{ route("emailBill.sendReminderEmailToCl") }}' : '{{ route("giftCard.rechnungReminder") }}';
                
                $.ajax({
                    url:  routeUrl,
                    method: 'post',
                    data: {
                        emReId: emRId,
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        $('#sndRemEmBtn'+emRId).prop('disabled', false);
                        $('#sndRemEmBtn'+emRId).html('<i class="fas fa-at"></i> <strong>Erinnerung</strong>');
                        if($('#emailSendSuccess'+emRId).is(":hidden")){ $('#emailSendSuccess'+emRId).show(100).delay(4000).hide(100); }
                    },
                    error: (error) => { console.log(error); }
                });
            }

            function confPaymentOpen(emBId, orId, type){
                $('#billPayConfOrId').html(orId);
                $('#billPayConfEmBiId').val(emBId);
                $('#type').val(type);
            }
            function cancelConfPayment(){

            }
            function confPayment(){
                $.ajax({
                    url: '{{ route("emailBill.rechnungConfirmPayment") }}',
                    method: 'post',
                    data: {
                        emBiId: $('#billPayConfEmBiId').val(),
                        type: $('#type').val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: (respo) => {
                        if(respo == "alreadyConf"){
                            if($('#billPayConfModalErr1').is(":hidden")){ $('#billPayConfModalErr1').show(100).delay(4000).hide(100); }
                        }else if (respo == "notFound"){
                            if($('#billPayConfModalErr2').is(":hidden")){ $('#billPayConfModalErr2').show(100).delay(4000).hide(100); }
                        }else{
                            $("#notConfirmedBillsDiv").load(location.href+" #notConfirmedBillsDiv>*","");
                            $("#confirmedBillsList").load(location.href+" #confirmedBillsList>*","");
                            $("#billPayConfModal").modal("toggle");
                        }
                    },
                    error: (error) => { console.log(error); }
                });
            }

            function showHideCBD(){
                if($('#confirmedBillsDiv').is(":hidden")){
                    // show
                    $('#showHideConfirmedBillsBtn').html('<strong><i class="fas fa-arrow-up"></i> Bestätigte / bezahlte Rechnungen anzeigen</strong>')
                    $('#confirmedBillsDiv').show(100);
                }else{
                    // hide
                    $('#showHideConfirmedBillsBtn').html('<strong><i class="fas fa-arrow-down"></i> Bestätigte / bezahlte Rechnungen anzeigen</strong>')
                    $('#confirmedBillsDiv').hide(100);
                }
            }

        </script>