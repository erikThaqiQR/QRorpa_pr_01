<?php
    use Illuminate\Support\Facades\Auth;
    use App\admExtraAccessToRes;
    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);
    $exAcs = admExtraAccessToRes::where('admId',Auth::user()->id)->get();
?>
    @if (admExtraAccessToRes::where('admId',Auth::user()->id)->count() >= 1)
        <input type="hidden" id="accessToOtherResRepV1" value="1">
    @else
        <input type="hidden" id="accessToOtherResRepV1" value="0">
    @endif
    
    
    <div class="card" style="width: 100%;" id="VerkaufsstatistikenPH1">
        <div class="card-header">
            <strong>{{__('adminP.services')}}</strong>
        </div>
        <div class="d-flex justify-content-between p-1">
            @if ($exAcs->count() > 0 || 1 == 1)
                @if($theRes->resType == 1 || $theRes->resType == 5)
                    <button id="selectPH11" onclick="selectPH1('1')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}}</strong> </button>
                    <button id="selectPH14" onclick="selectPH1('4')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> PDF-Bericht </strong> </button>
                    <button id="selectPH15" onclick="selectPH1('5')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> Selektiv/Alle PDF-Bericht </strong> </button>
                @elseif($theRes->resType == 2 || $theRes->resType == 6)
                    <button id="selectPH11" onclick="selectPH1('1')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}} </strong> </button>
                    <button id="selectPH12" onclick="selectPH1('2')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.takeaway')}} </strong> </button>
                    <button id="selectPH14" onclick="selectPH1('4')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> PDF-Bericht </strong> </button>
                    <button id="selectPH15" onclick="selectPH1('5')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> Selektiv/Alle PDF-Bericht </strong> </button>
                @elseif($theRes->resType == 3 || $theRes->resType == 8)
                    <button id="selectPH11" onclick="selectPH1('1')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}} </strong> </button>
                    <button id="selectPH13" onclick="selectPH1('3')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.delivery')}} </strong> </button>
                    <button id="selectPH14" onclick="selectPH1('4')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> PDF-Bericht </strong> </button>
                    <button id="selectPH15" onclick="selectPH1('5')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> Selektiv/Alle PDF-Bericht </strong> </button>
                @elseif($theRes->resType == 7 || $theRes->resType == 9)
                    <button id="selectPH11" onclick="selectPH1('1')" style="width: 19.9%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}} </strong> </button>
                    <button id="selectPH12" onclick="selectPH1('2')" style="width: 19.9%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.takeaway')}} </strong> </button>
                    <button id="selectPH13" onclick="selectPH1('3')" style="width: 19.9%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.delivery')}} </strong> </button>
                    <button id="selectPH14" onclick="selectPH1('4')" style="width: 19.9%;" class="text-center btn btn-outline-dark"> <strong> PDF-Bericht </strong> </button>
                    <button id="selectPH15" onclick="selectPH1('5')" style="width: 19.9%;" class="text-center btn btn-outline-dark"> <strong> Selektiv/Alle PDF-Bericht </strong> </button>
                @endif
            @else
                @if($theRes->resType == 1 || $theRes->resType == 5)
                    <button id="selectPH11" onclick="selectPH1('1')" style="width: 49%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}}</strong> </button>
                    <button id="selectPH14" onclick="selectPH1('4')" style="width: 49%;" class="text-center btn btn-outline-dark"> <strong> PDF-Bericht </strong> </button>
                @elseif($theRes->resType == 2 || $theRes->resType == 6)
                    <button id="selectPH11" onclick="selectPH1('1')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}} </strong> </button>
                    <button id="selectPH12" onclick="selectPH1('2')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.takeaway')}} </strong> </button>
                    <button id="selectPH14" onclick="selectPH1('4')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> PDF-Bericht </strong> </button>
                @elseif($theRes->resType == 3 || $theRes->resType == 8)
                    <button id="selectPH11" onclick="selectPH1('1')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}} </strong> </button>
                    <button id="selectPH13" onclick="selectPH1('3')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.delivery')}} </strong> </button>
                    <button id="selectPH14" onclick="selectPH1('4')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> PDF-Bericht </strong> </button>
                @elseif($theRes->resType == 7 || $theRes->resType == 9)
                    <button id="selectPH11" onclick="selectPH1('1')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}} </strong> </button>
                    <button id="selectPH12" onclick="selectPH1('2')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.takeaway')}} </strong> </button>
                    <button id="selectPH13" onclick="selectPH1('3')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.delivery')}} </strong> </button>
                    <button id="selectPH14" onclick="selectPH1('4')" style="width: 24.9%;" class="text-center btn btn-outline-dark"> <strong> PDF-Bericht </strong> </button>
                @endif
            @endif
        </div>
    </div>



    <script>
        function selectPH1(ph1Var){
            if(ph1Var == 5){
                $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','25');
                $('#VerkaufsstatistikberichtLoading').attr('style','width: 25%');
                $('#VerkaufsstatistikberichtLoading').html('25%');
            }else{
                if($('#accessToOtherResRepV1').val() == 0){
                    $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','33');
                    $('#VerkaufsstatistikberichtLoading').attr('style','width: 33%');
                    $('#VerkaufsstatistikberichtLoading').html('33%');
                }else{
                    $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','25');
                    $('#VerkaufsstatistikberichtLoading').attr('style','width: 25%');
                    $('#VerkaufsstatistikberichtLoading').html('25%');
                }
            }

            $('#VerkaufsstatistikberichtLoading').addClass('bg-primary').removeClass('bg-danger');

            $('#VerkaufsstatistikenPH1Val').val(ph1Var);
            $('#selectPH1'+ph1Var).addClass('btn-dark').removeClass('btn-outline-dark');

            $('#selectPH11').attr('disabled','disabled'); //Disable Restaurant
            $('#selectPH12').attr('disabled','disabled'); //Disable Takeaway
            $('#selectPH13').attr('disabled','disabled'); //Disable Delivery
            $('#selectPH14').attr('disabled','disabled'); //Disable ALL-Report
            $('#selectPH15').attr('disabled','disabled'); //Disable SELECTIVE-Report

            if(ph1Var == 5){
                // V2 select restaurant show
                $('#VerkaufsstatistikenPH1_5').show(200);
            }else{
                if($('#accessToOtherResRepV1').val() == 0){
                    $('#VerkaufsstatistikenPH2').show(200);
                }else{
                    // V1 select restaurant show
                    $('#VerkaufsstatistikenPH1_6').show(200);
                }
            }

            if(ph1Var == '1' || ph1Var == '2' || ph1Var == '3'){
                $('#selectPH22').attr('disabled','disabled');
                $('#selectPH23').attr('disabled','disabled');
                $('#selectPH24').attr('disabled','disabled');
            }
        }
    </script>