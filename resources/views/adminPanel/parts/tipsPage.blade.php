<?php
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Trinkgeld']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\TipLog;
    use App\User;
    use Carbon\Carbon;

    $thisRestaurantId = Auth::user()->sFor;
?>
<style>
    .anchorHover:hover{
        color: whitesmoke;
        text-decoration: none;
    }
</style>
<section class="p-3 pb-5">
    <?php
        $thisMonthTips = 0;
        $thisTodayTips = 0;
        $countMonth = 0;

        if(isset($_GET['mo'])){
            foreach(TipLog::where('toRes','=', $thisRestaurantId)->whereMonth('created_at', $_GET['mo'])->get()->sortByDesc('created_at') as $allTips){
                $thisMonthTips += $allTips->tipTot;
                $countMonth++;
            }
            foreach(TipLog::where('toRes','=', $thisRestaurantId)->whereDate('created_at', Carbon::today())->get()->sortByDesc('created_at') as $allTips){
                $thisTodayTips += $allTips->tipTot;
            }
        }else{
            foreach(TipLog::where('toRes','=', $thisRestaurantId)->whereMonth('created_at', Carbon::now()->month)->get()->sortByDesc('created_at') as $allTips){
                $thisMonthTips += $allTips->tipTot;
                $countMonth++;
            }
            foreach(TipLog::where('toRes','=', $thisRestaurantId)->whereDate('created_at', Carbon::today())->get()->sortByDesc('created_at') as $allTips){
                $thisTodayTips += $allTips->tipTot;
            }
        }
    
    ?>


    <div class="d-flex justify-content-between mb-2">
        @if(isset($_GET['mo']))
        <div class="b-qrorpa color-white text-center p-2" style="border-radius:20px; width:49.5%; font-size:19px; font-weight:bold;">
            <?php
                switch($_GET['mo']){
                    case 1: echo __("adminP.currentMonth"). ':' .__("adminP.jan"); break;
                    case 2: echo __("adminP.currentMonth"). ':' .__("adminP.feb"); break;
                    case 3: echo __("adminP.currentMonth"). ':' .__("adminP.march"); break;
                    case 4: echo __("adminP.currentMonth"). ':' .__("adminP.apr"); break;
                    case 5: echo __("adminP.currentMonth"). ':' .__("adminP.May"); break;
                    case 6: echo __("adminP.currentMonth"). ':' .__("adminP.june"); break;
                    case 7: echo __("adminP.currentMonth"). ':' .__("adminP.july"); break;
                    case 8: echo __("adminP.currentMonth"). ':' .__("adminP.aug"); break;
                    case 9: echo __("adminP.currentMonth"). ':' .__("adminP.sept"); break;
                    case 10: echo __("adminP.currentMonth"). ':' .__("adminP.oct"); break;
                    case 11: echo __("adminP.currentMonth"). ':' .__("adminP.nov"); break;
                    case 12: echo __("adminP.currentMonth"). ':' .__("adminP.dec"); break;   
                }
            ?>
        </div>
        @else
        <div class="b-qrorpa color-white text-center p-2" style="border-radius:20px; width:49.5%; font-size:19px; font-weight:bold;">
            <?php
                switch(Carbon::now()->month){
                    case 1: echo __("adminP.currentMonth"). ':' .__("adminP.jan"); break;
                    case 2: echo __("adminP.currentMonth"). ':' .__("adminP.feb"); break;
                    case 3: echo __("adminP.currentMonth"). ':' .__("adminP.march"); break;
                    case 4: echo __("adminP.currentMonth"). ':' .__("adminP.apr"); break;
                    case 5: echo __("adminP.currentMonth"). ':' .__("adminP.May"); break;
                    case 6: echo __("adminP.currentMonth"). ':' .__("adminP.june"); break;
                    case 7: echo __("adminP.currentMonth"). ':' .__("adminP.july"); break;
                    case 8: echo __("adminP.currentMonth"). ':' .__("adminP.aug"); break;
                    case 9: echo __("adminP.currentMonth"). ':' .__("adminP.sept"); break;
                    case 10: echo __("adminP.currentMonth"). ':' .__("adminP.oct"); break;
                    case 11: echo __("adminP.currentMonth"). ':' .__("adminP.nov"); break;
                    case 12: echo __("adminP.currentMonth"). ':' .__("adminP.dec"); break;   
                }
            ?>
        </div>
        @endif
        <a href="{{route('tips.indexM')}}" class="b-qrorpa color-white text-center p-2 anchorHover" style="border-radius:20px; width:49.5%; font-size:19px; font-weight:bold;">
            {{__('adminP.pastMonths')}}  
        </a>
    </div>

    <div class="d-flex justify-content-between pb-4" >
        <div class="b-qrorpa color-white text-center" style="border-radius:20px; width:33%;">
            <p style="margin:25px; font-size:21px;"><strong>{{__('adminP.thisMonth')}} : {{$thisMonthTips}}</strong><sup>{{__('adminP.currencyShow')}}</sup></p>
        </div>
        <div class="b-qrorpa color-white text-center" style="border-radius:20px; width:33%;">
            <p style="margin:25px; font-size:21px;"><strong>{{__('adminP.today')}} : {{$thisTodayTips}}</strong><sup>{{__('adminP.currencyShow')}}</sup></p>
        </div>
        <div class="b-qrorpa color-white text-center" style="border-radius:20px; width:33%;">
            <p style="margin:25px; font-size:21px;"><strong>{{__('adminP.from')}}  {{$countMonth}} {{__('adminP.customersClientele')}}</strong></p>
        </div>
    </div>



    <div class="d-flex justify-content-between" style="border-bottom:1px solid lightgray;">
        <p style="width:33%;"><strong>{{__('adminP.time')}}</strong></p>
        <p style="width:33%;"><strong>{{__('adminP.orderPrice')}}</strong></p>
        <p style="width:33%;"><strong>{{__('adminP.tipsTotal')}}</strong></p>
        <!-- <p style="width:24%;"><strong>Monat</strong></p> -->
    </div>

    <div class="d-flex justify-content-between flex-wrap pt-2" style="border-bottom:1px solid lightgray;">
        @if(isset($_GET['mo']) && isset($_GET['ye']))
            <?php
                $getFromM = $_GET['mo'];
                $getFromY = $_GET['ye'];
            ?>
        @else
            <?php
                $getFromM = Carbon::today()->month;
                $getFromY = Carbon::today()->year;
            ?>
        @endif
        @foreach(TipLog::where('toRes','=', $thisRestaurantId)->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->get()->sortByDesc('created_at') as $allTips)
            <p style="width:33%;"><strong>
            <?php
                $time = explode(' ', $allTips->created_at);
                $time2D = explode(':', $time[1]);
                $time3D = explode('-', $time[0]);
            ?>
            {{$time2D[0]}}:{{$time2D[1]}} <br>
            {{$time3D[2]}}.{{$time3D[1]}}.{{$time3D[0]}}

            </strong></p>
            <p style="width:33%;"><strong>{{number_format($allTips->shumaPor,2,'.','')}}<sup>{{__('adminP.currencyShow')}}</sup></strong></p>
            <p style="width:33%;"><strong>{{number_format($allTips->tipTot,2,'.','')}} <sup>{{__('adminP.currencyShow')}}</sup></strong></p>
            
        @endforeach
    </div>
</section>