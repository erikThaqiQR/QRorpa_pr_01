<?php

use App\Restorant;
use App\admExtraAccessToRes;
use Illuminate\Support\Facades\Auth;

    $theResV1 = Restorant::find(Auth::user()->sFor);
    $accResExV1 = admExtraAccessToRes::where('admId',Auth::user()->id)->get();

    $wOfAccResF1 = number_format((100 / ($accResExV1->count() + 2) - 0.01) ,2,'.',',');
?>
<input type="hidden" value="" id="resSelExAcRaportV1">
<input type="hidden" value="" id="resSelNamesExAcRaportV1">

<div class="card mt-2" style="width: 100%; display:none;" id="VerkaufsstatistikenPH1_6">
    <div class="card-header">
        <strong>Wählen Sie Restaurants aus, um den Bericht zu erstellen</strong>
    </div>

    <div class="d-flex justify-content-between p-1">
        <button id="selectPH1O6{{Auth::user()->sFor}}" style="width: {{$wOfAccResF1}}%;" onclick="selectPH1O6('0','{{Auth::user()->sFor}}','{{$theResV1->emri}}')" class="text-center btn btn-outline-dark shadow-none"> <strong>{{$theResV1->emri}}</strong> </button>
        <input type="hidden" id="resSelExAcStatRapV1{{Auth::user()->sFor}}" value="0" id="">
        @foreach ($accResExV1 as $exResAcsV1)
            <button id="selectPH1O6{{$exResAcsV1->toRes}}" style="width: {{$wOfAccResF1}}%;" onclick="selectPH1O6('{{$exResAcsV1->id}}','{{$exResAcsV1->toRes}}','{{Restorant::find($exResAcsV1->toRes)->emri}}')" 
            class="text-center btn btn-outline-dark shadow-none selectPH1O6All"> <strong>{{Restorant::find($exResAcsV1->toRes)->emri}}</strong> </button>
            <input type="hidden" id="resSelExAcStatRapV1{{$exResAcsV1->toRes}}" value="0" id="">    
        @endforeach
        <button id="selectPH1O6Finish" style="width: {{$wOfAccResF1}}%;" onclick="selectPH1O6Finish()" class="text-center btn btn-success shadow-none"> <strong>Auswahl bestätigen</strong> </button>
    </div>

    <div class="alert alert-danger text-center" id="resSelExAcEmptyErr01RapV1" style="display:none;">
        <strong>Wählen Sie mindestens ein Restaurant aus, um fortzufahren</strong>
    </div>
  
</div>


<script>
    function selectPH1O6(exAcId, resId, resEmri){
        var resSelExAc = $('#resSelExAcRaportV1').val();
        var resSelNamesExAc = $('#resSelNamesExAcRaportV1').val();

        if($('#resSelExAcStatRapV1'+resId).val() == '0'){
            // SELECT
            $('#selectPH1O6'+resId).removeClass('btn-outline-dark');
            $('#selectPH1O6'+resId).addClass('btn-dark');
            if(resSelExAc == ''){ resSelExAc = resId;
            }else{ resSelExAc += '-m-m-m-'+resId; }

            if(resSelNamesExAc == ''){ resSelNamesExAc = resEmri;
            }else{ resSelNamesExAc += '-m-m-m-'+resEmri; }

            $('#resSelExAcRaportV1').val(resSelExAc);
            $('#resSelNamesExAcRaportV1').val(resSelNamesExAc);
            $('#resSelExAcStatRapV1'+resId).val('1');
        }else{
            // DESELECT
            $('#selectPH1O6'+resId).removeClass('btn-dark');
            $('#selectPH1O6'+resId).addClass('btn-outline-dark');
            var resSelExAc2D = resSelExAc.split('-m-m-m-');
            resSelExAc = '';
            $.each(resSelExAc2D, function( index, value ) {
                if(value != resId){
                    if(resSelExAc == ''){ resSelExAc = value;
                    }else{ resSelExAc += '-m-m-m-'+value; }
                }
            });
            var resSelNamesExAc2D = resSelNamesExAc.split('-m-m-m-');
            resSelNamesExAc = '';
            $.each(resSelNamesExAc2D, function( index, value ) {
                if(value != resEmri){
                    if(resSelNamesExAc == ''){ resSelNamesExAc = value;
                    }else{ resSelNamesExAc += '-m-m-m-'+value; }
                }
            });
            $('#resSelExAcRaportV1').val(resSelExAc);
            $('#resSelNamesExAcRaportV1').val(resSelNamesExAc);
            $('#resSelExAcStatRapV1'+resId).val('0');
        }
    }


    function selectPH1O6Finish(){
        if(!$('#resSelExAcRaportV1').val()){
            if($('#resSelExAcEmptyErr01RapV1').is(':hidden')){ $('#resSelExAcEmptyErr01RapV1').show(100).delay('4500').hide(100); }
        }else{
            $('#VerkaufsstatistikenPH1O6Val').val($('#resSelExAcRaportV1').val());
            // $('#monthSelectedTheRes').val($('#resSelExAcRaportV1').val());
            $('#resSelectedSendToRaportGenV1Day').val($('#resSelExAcRaportV1').val());
            $('#resSelectedSendToRaportGenV1Week').val($('#resSelExAcRaportV1').val());
            $('#resSelectedSendToRaportGenV1Month').val($('#resSelExAcRaportV1').val());
            $('#resSelectedSendToRaportGenV1Year').val($('#resSelExAcRaportV1').val());
            

            $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','50');
            $('#VerkaufsstatistikberichtLoading').attr('style','width: 50%');
            $('#VerkaufsstatistikberichtLoading').html('50%');

            $('#selectPH1O6{{Auth::user()->sFor}}').attr('disabled','disabled');
            $('#selectPH1O6Finish').attr('disabled','disabled');
            $('.selectPH1O6All').attr('disabled','disabled');
            
            $('#VerkaufsstatistikenPH2').show(200);
        }

    }
</script>
