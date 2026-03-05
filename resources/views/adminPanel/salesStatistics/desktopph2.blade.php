<?php

    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);
?>
    
    
    <div class="card mt-2" style="width: 100%; display:none;" id="VerkaufsstatistikenPH2">
        <div class="card-header">
            <strong>{{__('adminP.selectDatesBy')}}...</strong>
        </div>
        <div class="d-flex justify-content-between p-1">
            <button id="selectPH21" style="width: 24.9%;" onclick="selectPH2('1')" class="text-center btn btn-outline-dark"> <strong> <i class="fas fa-calendar-day"></i> {{__('adminP.day')}} </strong> </button>
            <button id="selectPH22" style="width: 24.9%;" onclick="selectPH2('2')" class="text-center btn btn-outline-dark"> <strong> <i class="fas fa-calendar-week"></i> {{__('adminP.week')}} </strong> </button>
            <button id="selectPH23" style="width: 24.9%;" onclick="selectPH2('3')" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.month')}} </strong> </button>
            <button id="selectPH24" style="width: 24.9%;" onclick="selectPH2('4')" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.year')}} </strong> </button>
        </div>
    </div>


    <script>
        function selectPH2(ph2Var){
            if(!$('#VerkaufsstatistikenPH1O5Val').val()){
                $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','75');
                $('#VerkaufsstatistikberichtLoading').attr('style','width: 75%');
                $('#VerkaufsstatistikberichtLoading').html('75%');
            }else{
            $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','66');
            $('#VerkaufsstatistikberichtLoading').attr('style','width: 66%');
            $('#VerkaufsstatistikberichtLoading').html('66%');
            }

            $('#VerkaufsstatistikenPH2Val').val(ph2Var);
            $('#selectPH2'+ph2Var).addClass('btn-dark').removeClass('btn-outline-dark');

            $('#selectPH21').attr('disabled','disabled'); //Disable day
            $('#selectPH22').attr('disabled','disabled'); //Disable week
            $('#selectPH23').attr('disabled','disabled'); //Disable month
            $('#selectPH24').attr('disabled','disabled'); //Disable year

            if(ph2Var == 1){
                $('#VerkaufsstatistikenPH21').show(200);
            }else if(ph2Var == 2){
                $('#VerkaufsstatistikenPH22').show(200);
            }else if(ph2Var == 3){
                $('#VerkaufsstatistikenPH23').show(200);
            }else if(ph2Var == 4){
                $('#VerkaufsstatistikenPH24').show(200);
            }
        }
    </script>