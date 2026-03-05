    <?php

        use App\Restorant;
        use App\TableQrcode;

    if(Auth::check() && (Auth::user()->email == 'Briefmarketing@qrorpa.ch' || Auth::user()->email == 'mg_ResData@qrorpa.ch' || Auth::user()->email == 'callagent@qrorpa.ch' || Auth::user()->email == 'callagent01@qrorpa.ch' || Auth::user()->email == 'callagent02@qrorpa.ch' 
    || Auth::user()->email == 'callagent03@qrorpa.ch' || Auth::user()->email == 'callagent04@qrorpa.ch' || Auth::user()->email == 'callagent05@qrorpa.ch' || Auth::user()->email == 'callagent06@qrorpa.ch'
    || Auth::user()->email == 'callagent07@qrorpa.ch' || Auth::user()->email == 'callagent08@qrorpa.ch' || Auth::user()->email == 'callagent09@qrorpa.ch' || Auth::user()->email == 'callagent10@qrorpa.ch')){
        header("Location: ".route('resDemo.indexCRM')."");
        exit(); 
    }


        if(isset($_GET['Reservierung']) && isset($_GET['Res'])){
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['ResRez'] = $_GET['Res'];

            header("Location: ".route('TableRez.index', ['Res' => $_GET['Res']]));
            exit();

        }else if(Auth::check() && Auth::user()->role == 5){
            header("Location: ".route('dash.index'));
            exit();

        }else if( Auth::check() && Auth::user()->role == 15){
            header("Location: ".route('barAdmin.indexStatistics'));
            exit();

        }else if( Auth::check() && Auth::user()->role == 9 ){
            header("Location: ".route('manageProduktet.index'));
            exit();

        }else if( Auth::check() && Auth::user()->role == 55 ){
            header("Location: ".route('admWoMng.indexAdmMngPageWaiter'));
            exit();

        }else if( Auth::check() && Auth::user()->role == 54 ){
            header("Location: ".route('cookPnl.cookPanelIndexCook'));
            exit();

        }else if( Auth::check() && Auth::user()->role == 53 ){
            header("Location: ".route('admWoMng.AccountantStatistics'));
            exit();

        }else if( Auth::check() && Auth::user()->role == 33 ){
            header("Location: ".route('saContracts.index'));
            exit();
        }

    
    
        if(isset($_GET['Res']) && isset($_GET['t'])){
            if($_GET['Res'] == 31){
                
                header("Location: https://qrorpa.ch/?Res=34&t=".$_GET['t']);
                exit();
            }
            $resTestEx = Restorant::find($_GET['Res']);
            if($resTestEx == NULL){
                header("Location: ".route('Res.resTNotFoundPage'));
                exit();
            }else if($_GET['t'] != 500){
                $tTestEx = TableQrcode::where([['Restaurant',$_GET['Res']],['tableNr',$_GET['t']]])->first();
                if($tTestEx == NULL){
                    header("Location: ".route('Res.resTNotFoundPage'));
                    exit();
                }
            }
        }else{
            header("Location: ".route('firstPage.index'));
            exit();
        }
    ?>

    

    @extends('layouts.appMaster')

    @section('content')
        <div id="allMenu">
            @if(isset($_GET['t']) && isset($_GET['Res']))
                @if($_GET['t'] != 500)
                    @include('inc.menuNormal')
                    @include('inc.footer')
                @elseif($_GET['t'] == 500)
                    @include('inc.menuTakeaway')
                    @include('inc.footer')
                @endif
            @elseif(isset($_GET['Bar']))
                @include('inc.menuBarbershop')
                @include('inc.footer')
            @endif
        </div>

        @if(!isset($_GET['Res']) && !isset($_GET['t']) && !isset($_GET['Bar']))
            <script>
                $(document).ready(function(){
                    $('#allMenu').hide();
                });
            </script>
        @endif

        <div id="extraSpace">
            <br><br><br>
        </div>

        <script>
            $(document).ready(function(){
                if ((screen.width>580)) {
                    $('#extraSpace').hide();
                }else{
                    $('#extraSpace').show();
                }
            });
        </script>
    @endsection
 


