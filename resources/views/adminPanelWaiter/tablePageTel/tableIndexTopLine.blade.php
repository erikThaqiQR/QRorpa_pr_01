<?php
    use Carbon\Carbon;
    use App\payTecPair;
    use Illuminate\Support\Facades\Auth;
?>
<div style="width:100%;" class="d-flex flex-wrap justify-content-between pl-2 pr-2">
    <?php
        $dt = Carbon::now();
        $date = explode('-',$dt->toDateString());
    ?>
   
    <div style="width: 33%;">
        <p class="text-center mb-1 mt-1" style="font-size:20px; color:rgb(72,81,87); font-weight:bold;" id="oraTel">{{$dt->toTimeString()}}</p>
        <p class="text-center mb-1" style="font-size:20px; color:rgb(72,81,87); font-weight:bold;" id="dataTel">{{$date[2]}}.{{$date[1]}}</p>
    </div>
    
    <button style="width:33%;" data-toggle="modal" data-target="#PosPairModal" class="btn btn-outline-secondary shadow-none mt-2 mb-2" type="button">PayTec POS</button>

    <div style="width:33%; text-align:center;">
        <p style="margin:0;">Automatisch bestätigen</p>
        @if(Auth::user()->tableOrAutoConfirm == 1)
        <input checked type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="xs" data-on="Ja" data-off="Nein" onchange="changeAutoConfOrStat()" id="autoAcceptOrdersToggle">
        @else   
        <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="xs" data-on="Ja" data-off="Nein" onchange="changeAutoConfOrStat()" id="autoAcceptOrdersToggle">
        @endif
    </div>
</div>

<div style="width:100%;" class="d-flex flex-wrap justify-content-between pl-2 pr-2">
    
    <a class="btn shadow-none" href="{{ route('admWoMng.ordersListAdmMngPageWaiter') }}" style="width:49%; margin:0px; border:1px solid rgb(39,190,175); color:rgb(39,190,175);">
        <strong>Bestellliste</strong>
    </a>

    <div class="input-group " style="width:49%;">
        <div class="input-group-prepend" style="height:37px;" id="TischSearchIconDiv" onclick="findThisTable()">
            <span class="input-group-text pointTable"  id="TischSearch"><i id="TischSearchIcon" style="color:rgb(72,81,87);" class="fas fa-search"></i></span>
        </div>
        <input style="background-color: rgba(245,245,245,255);" min="1" onkeyup="findThisTableIcon()" max="999" type="number" class="form-control shadow-none" placeholder="{{__('adminP.tSearch')}}" aria-label="Tisch" aria-describedby="TischSearch" id="TischSearchInput">
    </div>

    <div style="width: 100%; display:none;" class="alert alert-danger text-center" id="findThisTableError01">
        <strong><i class="fas fa-exclamation"></i> {{__('adminP.writeAValidNrTSearch')}}</strong>
    </div>
</div>

<hr style="width: 100%; margin:5px 0 5px 0;">


<div class="modal" id="PosPairModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Verbindung zu einem PayTech-POS herstellen</strong></h5>
                <button type="button" class="close" aria-label="Close" onclick="closePosPairModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body p-1" id="PosPairModalBody">
                <p class="text-center mt-1 mb-1"><strong>Schreiben Sie den vom POS-Terminal bereitgestellten Pairing-Code</strong></p>
                <div style="width:100%;" class="input-group mb-2">
                    <input type="text" class="form-control shadow-none" placeholder="POS code" id="posCodePair">
                    <div class="input-group-append">
                        <button onclick="ConnectToPaytec()" id="ConnectToPaytecBtn" class="btn btn-outline-secondary shadow-none" type="button"><strong>Paar</strong></button>
                    </div>
                </div>
                <div style="width: 100%; display:none;" class="alert alert-success text-center mb-2" id="posPairSuccess01">
                    <strong>Die Verbindung zum POS-Terminal war erfolgreich</strong>
                </div>
                <div style="width: 100%; display:none;" class="alert alert-danger text-center mb-2" id="posPairError01">
                    <strong>Die Verbindung zum POS-Terminal war nicht erfolgreich</strong>
                </div>

                <hr>

                <div id="PosPairModalAtivePOS">
                <?php
                    $payTecPair = payTecPair::where([['userId',Auth::user()->id],['toRes',Auth::user()->sFor]])->first();
                ?>
                @if ($payTecPair != Null)
                    <p class="text-center mt-1 mb-1" style="width:100%;"><strong>Es besteht eine aktuelle aktive Verbindung zu einem POS-Terminal</strong></p>
                    <button class="btn btn-outline-dark" style="width:100%;" onclick="DisconnectToPaytec()" id="DisconnectToPaytecBtn">
                        <strong>Beenden Sie die POD-Verbindung</strong>
                    </button>
                @else
                    <p class="text-center mt-1 mb-1" style="width:100%; color:red;"><strong>Es besteht derzeit keine aktive Verbindung zu einem POS-Terminal</strong></p>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    function closePosPairModal(){
        $('#PosPairModal').modal('hide');
    }
    function ConnectToPaytec(){
        $('#ConnectToPaytecBtn').html('Warten');
        $('#ConnectToPaytecBtn').prop('disabled', true);
        $('#posCodePair').prop('disabled', true);
        $.ajax({
            url: '{{ route("payTec.Pair") }}',
            method: 'post',
            data: {
                pairCode: $('#posCodePair').val(),
                _token: '{{csrf_token()}}'
            },
            success: (res) => {
                var resJSON = $.parseJSON(res)
                
                $('#posCodePair').val('');
                console.log(res);
                console.log(resJSON.AccessToken);
                if($('#posPairSuccess01').is(':hidden')){
                    $('#posPairSuccess01').show(50).delay(6000).hide(50);
                }
                $('#ConnectToPaytecBtn').html('Paar');
                $('#ConnectToPaytecBtn').prop('disabled', false);
                $('#posCodePair').prop('disabled', false);

                $("#PosPairModalAtivePOS").load(location.href+" #PosPairModalAtivePOS>*","");
            },
            error: (error) => {
                console.log(error);
                $('#posCodePair').val('');
                $('#ConnectToPaytecBtn').html('Paar');
                $('#ConnectToPaytecBtn').prop('disabled', false);
                $('#posCodePair').prop('disabled', false);
                if($('#posPairError01').is(':hidden')){
                    $('#posPairError01').show(50).delay(6000).hide(50);
                }
            }
        });
    }

    function DisconnectToPaytec(){
        $('#DisconnectToPaytecBtn').html('Warten');
        $('#DisconnectToPaytecBtn').prop('disabled', true);
        $.ajax({
            url: '{{ route("payTec.Disconnect") }}',
            method: 'post',
            data: { _token: '{{csrf_token()}}' },
            success: (res) => {
                var resJSON = $.parseJSON(res)
                $("#PosPairModalBody").load(location.href+" #PosPairModalBody>*","");
            },
            error: (error) => {
                console.log(error);
                $('#DisconnectToPaytecBtn').html('Beenden Sie die POD-Verbindung');
                $('#DisconnectToPaytecBtn').prop('disabled', false);
            }
        });
    }

    function changeAutoConfOrStat(){
        $.ajax({
            url: '{{ route("dash.changeAutoConfOrdTableStatus") }}',
            method: 'post',
            data: { _token: '{{csrf_token()}}' },
            success: (res) => {},
            error: (error) => { console.log(error); }
        });
    }
</script>

