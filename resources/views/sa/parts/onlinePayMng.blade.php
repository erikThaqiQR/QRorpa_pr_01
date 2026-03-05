<?php
    use App\Orders;
    use App\Restorant;
?>

<style>
    .clickableRes:hover{
        cursor: pointer;
    }

    :root {
        --theadColor: rgb(39,190,175);
    }

    .button1{
        background-color: var(--theadColor);
        padding: 5px;
        text-align: center;
        color: white;
        font-weight: bold;
        border-radius: 20px;
    }
    .seceltedOrderRow{
        background-color: #AFE2DD !important;
    }
    .paidOrderRow{
        background-color: #CCFFE5 !important;
    }
    .boldText{
        font-weight: bold;
    }




    /* ........................................................................................ */
    table.dataTable {
        box-shadow: #bbbbbb 0px 0px 5px 0px;
    }
    thead {
        background-color: var(--theadColor);
    }
    thead > tr,
    thead > tr > th {
        background-color: transparent;
        color: #fff;
        font-weight: normal;
        text-align: start;
    }
    table.dataTable thead th,
    table.dataTable thead td {
        border-bottom: 0px solid #111 !important;
    }
    .dataTables_wrapper > div {
        margin: 5px;
    }
    table.dataTable.display tbody tr.even > .sorting_1,
    table.dataTable.order-column.stripe tbody tr.even> .sorting_1 table.dataTable.display tbody tr.even,
    table.dataTable.display tbody tr.odd > .sorting_1,
    table.dataTable.order-column.stripe tbody tr.odd > .sorting_1,
    table.dataTable.display tbody tr.odd {
        background-color: #ffffff;
    }
    table.dataTable thead th {
        position: relative;
        background-image: none !important;
    }
    table.dataTable thead th.sorting:after,
    table.dataTable thead th.sorting_asc:after,
    table.dataTable thead th.sorting_desc:after {
        position: absolute;
        top: 12px;
        right: 8px;
        display: block;
        font-family: "Font Awesome\ 5 Free";
    }
    table.dataTable thead th.sorting:after {
        content: "\f0dc";
        color: #ddd;
        font-size: 0.8em;
        padding-top: 0.12em;
    }
    table.dataTable thead th.sorting_asc:after {
        content: "\f0de";
    }
    table.dataTable thead th.sorting_desc:after {
        content: "\f0dd";
    }
    table.dataTable.display tbody tr:hover > .sorting_1,
    table.dataTable.order-column.hover tbody tr:hover > .sorting_1 {
        background-color: #f2f2f2 !important;
        color: #000;
    }
    tbody tr:hover {
        background-color: #f2f2f2 !important;
        color: #000;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: none !important;
        border-radius: 50px;
        background-color: var(--theadColor) !important;
        color:#fff !important
    }
    .paginate_button.current:hover{
        background: none !important;
        border-radius: 50px;
        background-color: var(--theadColor) !important;
        color:#fff !important
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover,
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border: 1px solid #979797;
        background: none !important;
        border-radius: 50px !important;
        background-color: #000 !important;
        color: #fff !important;
    }



</style>




























<div class="p-3">
    <h2 style="color: rgb(39,190,175);"><strong>Seite zur Verwaltung von Online-Zahlungen</strong></h2>
    <hr>

    <div class="mt-3 d-flex justify-content-between">
        @if(isset($_GET['Res']))
            <p style="width: 49%; font-size:21px;" class="p-2 btn btn-info" data-toggle="modal" data-target="#oPayMngSelRes"><strong>{{$_GET['Res']}}. {{Restorant::find($_GET['Res'])->emri}}</strong></p>
        @else
            <p style="width: 49%; font-size:21px;" class="p-2 btn btn-info" data-toggle="modal" data-target="#oPayMngSelRes"><strong>Wählen Sie ein Restaurant</strong></p>
        @endif
        @if(isset($_GET['myD']))

        @else
            <p style="width: 49%; font-size:21px;" class="p-2 btn btn-info"><strong>Wählen Sie ein Datum</strong></p>
        @endif
    </div>
    @if(isset($_GET['Res']))
        <div class="mt-2 d-flex justify-content-between flex-wrap" id="oneOrderId">
            <h3 style="width: 100%; color:rgb(39,190,175);" class="text-center"><strong>Ausgewählte Auftragsstatistiken</strong></h3>

            <h5 style="width: 15%; color:rgb(72,81,87)" class="text-right">Aufträge #: </h5>
            <h5 style="width: 10%; color:rgb(72,81,87); text-decoration: underline;" class="text-left pl-2 pr-4 boldText" id="orderSelDetailSum">0</h5>

            <h5 style="width: 15%; color:rgb(72,81,87)" class="text-right">Total: CHF</h5>
            <h5 style="width: 10%; color:rgb(72,81,87); text-decoration: underline;" class="text-left pl-2 pr-4 boldText" id="orderSelDetailTotal">0.00</h5>

            <h5 style="width: 15%; color:rgb(72,81,87)" class="text-right">Qrorpa 1%: CHF</h5>
            <h5 style="width: 10%; color:rgb(72,81,87); text-decoration: underline;" class="text-left pl-2 pr-4 boldText" id="orderSelDetailOurOnePercent">0.00</h5>

            <h5 style="width: 15%; color:rgb(72,81,87)" class="text-right">Verarbeitete Summe</h5>
            <h5 style="width: 10%; color:rgb(72,81,87); text-decoration: underline;" class="text-left pl-2 pr-4 boldText" id="orderSelDetailNewTotal">0.00</h5>

            <h5 style="width: 15%; color:rgb(72,81,87)" class="text-right">PSP-Gebühr (1.7%): CHF</h5>
            <h5 style="width: 10%; color:rgb(72,81,87); text-decoration: underline;" class="text-left pl-2 pr-4 boldText" id="orderSelDetailTotalOneSeven">0.00</h5>

            <h5 style="width: 15%; color:rgb(72,81,87)" class="text-right">PSP 0.3 CHF: </h5>
            <h5 style="width: 10%; color:rgb(72,81,87); text-decoration: underline;" class="text-left pl-2 pr-4 boldText" id="orderSelDetailTotalPointThree">0.00</h5>

            <h5 style="width: 15%; color:rgb(72,81,87)" class="text-right">Überweisungsbetrag</h5>
            <h5 style="width: 10%; color:rgb(72,81,87); text-decoration: underline;" class="text-left pl-2 pr-4 boldText" id="orderSelDetailTotalTransfer">0.00</h5>

            <button class="btn btn-info" style="width: 25%;" onclick="transferOnlinePayAllSel()">Als übertragen markieren</button>
        </div>
        
        <input type="hidden" id="orderSelDetailOrdersSelected">
        <hr>
    @endif

    @if(!isset($_GET['Res']) && !isset($_GET['myD']))
        <p style="color: rgb(72,81,87);"><strong>Wählen Sie zuerst ein Restaurant aus (Monat/Jahr optional)</strong></p>
    @else
        @if(isset($_GET['Res']))
            <?php $theOrders = Orders::where([['Restaurant',$_GET['Res']],['payM','Online']])->get()->sortByDesc('created_at');?>
        @endif
        <table id="onlinePayMngTable" class="display">
            <thead>
                <tr>
                    <th class="text-center">Überweisung</th>
                    <th class="text-center">Datum</th>
                    <th>Tisch</th>
                    <th>Befehl</th>
                    <th class="text-center">Gesamt <strong>CHF</strong></th>
                    <th class="text-center">Trinkgeld</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($theOrders as $order)
                    <tr id="orderTableRow{{$order->id}}" <?php if($order->paidOnlineTr != 0){echo'class="paidTr paidOrderRow"';}?>>
                        <td class="d-flex" style="background-color: inherit !important;">
                            @if($order->paidOnlineTr == 1)
                                <p class="text-center pt-3" style="color:Green; width:150px;"><strong>Bezahlt</strong></p> <!-- Paid -->
                            @else
                                <p class="button1" style="width: 150px;" onclick="transferOnlinePay('{{$order->id}}')">Bezahlt</p> <!-- Pay -->
                            @endif
                        </td>
                        @php
                            $crAt2D = explode('-',explode(' ',$order->created_at)[0])
                        @endphp
                            <td onclick="selectThisOrderRow('{{$order->id}}','{{$order->shuma}}')" class="text-center clickableRes">{{$crAt2D[2]}}-{{$crAt2D[1]}}-{{$crAt2D[0]}}</td>
                        @if($order->nrTable == 500)
                            <td onclick="selectThisOrderRow('{{$order->id}}','{{$order->shuma}}')" class="clickableRes">Takeaway</td>
                        @else
                            <td onclick="selectThisOrderRow('{{$order->id}}','{{$order->shuma}}')" class="clickableRes">{{$order->nrTable}}</td>
                        @endif
                        <td onclick="selectThisOrderRow('{{$order->id}}','{{$order->shuma}}')" class="clickableRes">
                            @foreach (explode('---8---',$order->porosia) as $oneProd)
                                @php
                                    $prod2D = explode('-8-',$oneProd)
                                @endphp
                                {{$prod2D[3]}}X <strong>{{$prod2D[0]}} <!-- SasiaX Emri -->
                                @if ($prod2D[5] != '')
                                        / {{$prod2D[5]}} <!-- Tipi -->
                                @endif 
                                </strong> 
                                ({{$prod2D[1]}}) <!-- Pershkrimi -->
                                @if ($prod2D[2] != '')
                                    <br> <span style="color:rgb(39,190,175);">Extras: </span>
                                    @foreach (explode('--0--',$prod2D[2]) as $oneExtra) <!-- Extra -->
                                        @php
                                            $oneExtra2D = explode('||',$oneExtra);
                                        @endphp
                                        {{$oneExtra2D[0]}} ( {{$oneExtra2D[1]}} CHF)
                                        @if(!$loop->last) 
                                            <strong class="ml-2 mr-2">/</strong>
                                        @endif
                                    @endforeach
                                @endif

                                @if(!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        </td>
                        <td class="text-center clickableRes" onclick="selectThisOrderRow('{{$order->id}}','{{$order->shuma}}')"><strong>{{$order->shuma}}</strong></td>
                        @if ($order->tipPer == 0)
                            <td class="text-center clickableRes" onclick="selectThisOrderRow('{{$order->id}}','{{$order->shuma}}')">---</td>
                        @else
                            <td class="text-center clickableRes" onclick="selectThisOrderRow('{{$order->id}}','{{$order->shuma}}')">{{$order->tipPer}} CHF</td>
                        @endif  
                      
                    </tr>
                @endforeach
               
            </tbody>
        </table>
    @endif


    <script>
        $(document).ready( function () {
            $('#onlinePayMngTable').DataTable({
                "language": {
                    "sProcessing":      "wird bearbeitet...",
                    "sSearch":          "Suche:",
                    "sLoadingRecords":  "Aufladen...",
                    "sInfoEmpty":       "Zeigt Datensätze von 0 bis 0 von insgesamt 0 Datensätzen an",
                    "sInfo":            "Es werden Datensätze von <strong>_START_</strong> bis <strong>_END_</strong> von insgesamt <strong>_TOTAL_</strong> Datensätzen angezeigt",
                    "sLengthMenu":      "_MENU_ Datensätze anzeigen",
                    "sZeroRecords":     "keine Ergebnisse gefunden",
                    "oPaginate": {
                        "sFirst":       "Zuerst",
                        "sLast":        "Neueste",
                        "sNext":        "Nächste",
                        "sPrevious":    "Vorherige"
                    },
                },
            });
        } );


        function transferOnlinePay(orId){
            $.ajax({
				url: '{{ route("oPayMng.TransferDone") }}',
				method: 'post',
				data: {
					orId: orId,
					_token: '{{csrf_token()}}'
				},
				success: () => {$("#onlinePayMngTable").load(location.href+" #onlinePayMngTable>*","");},
				error: (error) => {
					console.log(error);
					alert('bitte aktualisieren und erneut versuchen!');
				}
			});
        }

        function transferOnlinePayAllSel(){
            $.ajax({
				url: '{{ route("oPayMng.TransferDoneAllSel") }}',
				method: 'post',
				data: {
					ordersSelected: $('#orderSelDetailOrdersSelected').val(),
					_token: '{{csrf_token()}}'
				},
				success: () => {
                    $("#onlinePayMngTable").load(location.href+" #onlinePayMngTable>*","");
                    $("#oneOrderId").load(location.href+" #oneOrderId>*","");
                    
                },
				error: (error) => {
					console.log(error);
					alert('bitte aktualisieren und erneut versuchen!');
				}
			});
        }

        function selectThisOrderRow(orId,tot){
            if(!$("#orderTableRow"+orId).hasClass('seceltedOrderRow') && !$("#orderTableRow"+orId).hasClass('paidTr')){
                if($('#orderSelDetailOrdersSelected').val() == ''){
                    $('#orderSelDetailOrdersSelected').val(orId);
                }else{
                    $('#orderSelDetailOrdersSelected').val( $('#orderSelDetailOrdersSelected').val()+'---|---'+orId);
                }
                
                $("#orderTableRow"+orId).addClass('seceltedOrderRow');

                var tot= parseFloat(tot);

                var totDis= parseFloat($('#orderSelDetailTotal').html());
                var sumDis= parseInt($('#orderSelDetailSum').html());

                var Sum = ++sumDis;
                var Total = parseFloat(totDis+tot).toFixed(2);
                var OurOnePercent = parseFloat(Total * 0.01).toFixed(2);
                var Total2 = parseFloat(Total - OurOnePercent);
                var PSP1point3p = parseFloat(Total2*0.017).toFixed(2);
                var PSPpoint3chf =parseFloat(Sum*0.3).toFixed(2);
                var TotTransfer =parseFloat(Total2-PSP1point3p-PSPpoint3chf).toFixed(2);

                // Sum
                $('#orderSelDetailSum').html(Sum);
                // total 
                $('#orderSelDetailTotal').html(Total);
                // Our 1%
                $('#orderSelDetailOurOnePercent').html(OurOnePercent);
                //new total 
                $('#orderSelDetailNewTotal').html(Total2);
                // PSP 1.3%
                $('#orderSelDetailTotalOneSeven').html(PSP1point3p);
                // 0.3 CHF for each transaction
                $('#orderSelDetailTotalPointThree').html(PSPpoint3chf);
                // Total to transfer
                $('#orderSelDetailTotalTransfer').html(TotTransfer);                
            }
        }
    </script>











        <!-- Modal -->
        <div class="modal fade" id="oPayMngSelRes" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color:rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><strong>Wählen Sie ein Restaurant</strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><strong>X</strong></span></button>
                    </div>
                    <div class="modal-body d-flex justify-content-between flex-wrap text-center">
                        @foreach (Restorant::all() as $res)
                            <a href="{{route('oPayMng.onlinePayIndex',['Res'=>$res->id])}}" style="width:24.8%; border:1px solid rgb(72,81,87); border-radius:10px;" class="mb-2 p-2 clickableRes">
                                @if($res->profilePic == 'none')
                                    <img style="width:150px; height:150px;; border-radius:50%"  src="storage/images/ProfileIcon.png" alt="">
                                @else
                                    <img style="width:150px; height:150px;; border-radius:50%"  src="storage/ResProfilePic/{{$res->profilePic}}" alt="">
                                @endif
                                <h5 style="color:black;"><strong>{{$res->emri}}</strong></h5>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
















</div>