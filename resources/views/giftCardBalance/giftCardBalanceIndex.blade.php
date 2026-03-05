<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Restaurant') }}</title>


    @yield('extra-css')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <style>
        *{
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif
        }
    </style>
    <?php
        use App\giftCard;
        use App\Orders;
        use App\OrdersPassive;
use App\Restorant;

        
    ?>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="">
                    <!-- {{ config('app.name', 'Laravel') }} -->
                    Geschenkkarten-Guthaben
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button> 
            </div>
        </nav>

        <main class="py-8">
            @if (isset($_GET['gcid']) && isset($_GET['hs']))
                <?php
                    $theGCIns = giftCard::find($_GET['gcid']);
                ?>
                @if ($theGCIns != Null && $theGCIns->gcHash == isset($_GET['hs']))
                    <div class="d-flex flex-wrap justify-content-between">
                        <p style="width: 100%; text-align:center; font-size:1.1rem;"><strong>{{Restorant::find($theGCIns->toRes)->emri}} ({{$theGCIns->toRes}})</strong></p>
                        <p style="width: 100%; text-align:center; font-size:1.3rem;"><strong>Geschenkkartendaten (ref ID:{{$theGCIns->refId}})</strong></p>
                        
                        @if ($theGCIns->clName != 'empty')
                            <p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Name: <br><strong>{{$theGCIns->clName}}</strong></p>
                        @else
                            <p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Name: <br><strong>---</strong></p>
                        @endif

                        @if ($theGCIns->clLastname != 'empty')
                            <p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Nachname: <br><strong>{{$theGCIns->clLastname}}</strong></p>
                        @else
                            <p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Nachname: <br><strong>---</strong></p>
                        @endif

                        @if ($theGCIns->clEmail != 'empty')
                            <p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">E-Mail: <br><strong>{{$theGCIns->clEmail}}</strong></p>
                        @else
                            <p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">E-Mail: <br><strong>---</strong></p>
                        @endif
                      
                        @if ($theGCIns->clPhNr != 'empty')
                            <p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Telefonnummer: <br><strong>{{$theGCIns->clPhNr}}</strong></p>
                        @else
                            <p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Telefonnummer: <br><strong>---</strong></p>
                        @endif

                        <hr style="width:100%;">

                        <p style="width:59%; text-align:right; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px; margin-bottom:5px;">Gesamtwert:</p>
                        <p style="width:39%; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px; margin-bottom:5px;"><strong>{{ number_format($theGCIns->gcSumInChf, 2, '.', '') }} CHF</strong></p>
                        
                        <p style="width:59%; text-align:right; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px; margin-bottom:5px;">Gesamtausgaben:</p>
                        <p style="width:39%; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px; color:red; margin-bottom:5px;"><strong>{{ number_format($theGCIns->gcSumInChfUsed, 2, '.', '') }} CHF</strong></p>
                        
                        <p style="width:59%; text-align:right; line-height:1.1; font-size:1.2rem; padding:5px 5px 5px 5px; margin-bottom:5px;">insgesamt verfügbar:</p>
                        <p style="width:39%; line-height:1.1; font-size:1.2rem; padding:5px 5px 5px 5px; margin-bottom:5px;"><strong>{{ number_format($theGCIns->gcSumInChf - $theGCIns->gcSumInChfUsed, 2, '.', '') }} CHF</strong></p>
                        
                        <hr style="width:100%;">

                        <p style="width: 100%; text-align:center; font-size:1.1rem;"><strong>Nutzungsverlauf der Geschenkkarte</strong></p>
                        
                        <p style="width:18%; margin-bottom:8px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;"><strong>#</strong></p>
                        <p style="width:40%; margin-bottom:8px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;"><strong>GK Rabat</strong></p>
                        <p style="width:40%; margin-bottom:8px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;"><strong>Datum/Uhrzeit</strong></p>
                        

                        @foreach (explode('|||',$theGCIns->usedInOrdersId) as $OneGCUse)
                            <?php
                                $theOrder = OrdersPassive::find($OneGCUse);
                                if( $theOrder == Null ){  $theOrder = Orders::find($OneGCUse); }
                            ?>
                            @if ( $theOrder != Null )
                                <?php
                                    $date2D = explode('-',explode(' ',$theOrder->created_at)[0]);
                                    $time2D = explode(':',explode(' ',$theOrder->created_at)[1]);
                                ?>
                                <p style="width:18%; margin-bottom:4px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;">{{$theOrder->id}}</p>
                                <p style="width:40%; margin-bottom:4px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;">{{number_format($theOrder->dicsountGcAmnt, 2, '.', '') }} CHF</p>
                                <p style="width:40%; margin-bottom:4px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;">{{$date2D[2]}}.{{$date2D[1]}}.{{$date2D[0]}} {{$time2D[0]}}:{{$time2D[1]}}</p>
                            @endif
                        @endforeach
                    </div>

                    @include('inc.footer')
                @else
                    <div class="d-flex flex-wrap justify-content-between">
                        <p style="width: 100%; color:red;"><strong>Die angegebenen Geschenkkartendaten sind nicht gültig, bitte scannen Sie den QR-Code um Ihr aktuelles Guthaben zu prüfen!</strong></p>
                    </div>
                @endif
                
            @else
                <div class="d-flex flex-wrap justify-content-between">
                    <p style="width: 100%; color:red;"><strong>Dieser Link ist nicht gültig. Um den Saldo Ihrer Geschenkkarte zu überprüfen, scannen Sie einfach den QR-Code</strong></p>
                </div>
            @endif
        </main>
    </div>


    <script src="js/app.js">
    </script>

    @yield('extra-js')
</body>
</html>
