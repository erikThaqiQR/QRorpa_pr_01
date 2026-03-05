<?php
    use Illuminate\Support\Facades\Auth;
    use App\admExtraAccessToRes;
    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);
    $accResEx = admExtraAccessToRes::where('admId',Auth::user()->id)->get();

    
?>
    <input type="hidden" value="" id="resSelExAc">
    <div class="card mt-2" style="width: 100%; display:none;" id="VerkaufsstatistikenPH1_5">
        <div class="card-header">
            <strong>Wählen Sie Restaurants aus, um den Bericht zu erstellen</strong>
        </div>
        <div class="d-flex flex-wrap justify-content-start p-1">
            <button id="selectPH1O5{{Auth::user()->sFor}}" style="width: 49%; margin-right:1%;" onclick="selectPH1O5('0','{{Auth::user()->sFor}}')" class="text-center btn btn-outline-dark shadow-none"> <strong>{{$theRes->emri}}</strong> </button>
            <input type="hidden" id="resSelExAcStat{{Auth::user()->sFor}}" value="0" id="">
            @foreach ($accResEx as $exResAcs)
                <button id="selectPH1O5{{$exResAcs->toRes}}" style="width: 49%; margin-right:1%;" onclick="selectPH1O5('{{$exResAcs->id}}','{{$exResAcs->toRes}}')" 
                class="text-center btn btn-outline-dark shadow-none selectPH1O5All"> <strong>{{Restorant::find($exResAcs->toRes)->emri}}</strong> </button>
                <input type="hidden" id="resSelExAcStat{{$exResAcs->toRes}}" value="0" id="">    
            @endforeach
            <button id="selectPH1O5Finish" style="width: 100%;" onclick="selectPH1O5Finish()" class="text-center btn btn-success shadow-none"> <strong>Auswahl bestätigen</strong> </button>
        </div>
        <div class="alert alert-danger text-center" id="resSelExAcEmptyErr01" style="display:none;">
            <strong>Wählen Sie mindestens ein Restaurant aus, um fortzufahren</strong>
        </div>
    </div>

<script>
    
    function selectPH1O5(exAcId, resId){
        var resSelExAc = $('#resSelExAc').val();

        if($('#resSelExAcStat'+resId).val() == '0'){
            $('#selectPH1O5'+resId).removeClass('btn-outline-dark');
            $('#selectPH1O5'+resId).addClass('btn-dark');
            if(resSelExAc == ''){ resSelExAc = resId;
            }else{ resSelExAc += '-m-m-m-'+resId; }

            $('#resSelExAc').val(resSelExAc);
            $('#resSelExAcStat'+resId).val('1');
        }else{
            $('#selectPH1O5'+resId).removeClass('btn-dark');
            $('#selectPH1O5'+resId).addClass('btn-outline-dark');
            var resSelExAc2D = resSelExAc.split('-m-m-m-');
            resSelExAc = '';
            $.each(resSelExAc2D, function( index, value ) {
                if(value != resId){
                    if(resSelExAc == ''){ resSelExAc = value;
                    }else{ resSelExAc += '-m-m-m-'+value; }
                }
            });
            $('#resSelExAc').val(resSelExAc);
            $('#resSelExAcStat'+resId).val('0');
        }
    }

    function selectPH1O5Finish(){
        if(!$('#resSelExAc').val()){
            if($('#resSelExAcEmptyErr01').is(':hidden')){ $('#resSelExAcEmptyErr01').show(100).delay('4500').hide(100); }
        }else{
            $('#VerkaufsstatistikenPH1O5Val').val($('#resSelExAc').val());
            $('#monthSelectedTheRes').val($('#resSelExAc').val());

            $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','50');
            $('#VerkaufsstatistikberichtLoading').attr('style','width: 50%');
            $('#VerkaufsstatistikberichtLoading').html('50%');

            $('#selectPH1O5{{Auth::user()->sFor}}').attr('disabled','disabled');
            $('#selectPH1O5Finish').attr('disabled','disabled');
            $('.selectPH1O5All').attr('disabled','disabled');
            
            $('#VerkaufsstatistikenPH2').show(200);
            $('#selectPH21').attr('disabled','disabled');
            $('#selectPH22').attr('disabled','disabled');
            $('#selectPH24').attr('disabled','disabled');
        }

    }
</script>