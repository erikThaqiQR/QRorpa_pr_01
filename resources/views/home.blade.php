@extends('layouts.appOrders')

@section('content')
<?php
    use App\Restorant;
    use App\PiketLog;

    if(isset($_GET['Reservierung']) && isset($_GET['Res'])){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['ResRez'] = $_GET['Res'];
        $ress = $_GET['Res'];

        header("Location: ".route('TableRez.index', ['Res' => $ress]));
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
        // KAMARIER
        header("Location: ".route('admWoMng.indexAdmMngPageWaiter'));
        exit();

    }else if( Auth::check() && Auth::user()->role == 54 ){
        // KUZHINIER
        header("Location: ".route('cookPnl.cookPanelIndexCook'));
        exit();

    }else if( Auth::check() && Auth::user()->role == 53 ){
        // KONTABILISTI
        header("Location: ".route('admWoMng.AccountantStatistics'));
        exit();

    }else if( Auth::check() && Auth::user()->role == 33 ){
        // MENAGJER TE KONTRATAVE 
        header("Location: ".route('saContracts.index'));
        exit();
    }

?>

                                @foreach($porosit as $por)
                              
                                        <div class="modal mt-5" id="moreD{{$por->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    @if($por->statusi == 0)
                                                        <button class="btn btn-block btn-warning">In der Warteschleife</button>
                                                    @elseif($por->statusi == 1)
                                                        <button class="btn btn-block btn-info">Bestätigt</button>
                                                    @elseif($por->statusi == 2)
                                                        <button class="btn btn-block btn-danger">Annulliert</button>
                                                    @elseif($por->statusi == 3)
                                                        <button class="btn btn-block btn-success">Abgeschlossen</button>
                                                    @endif

                                    
                                                               
                                                </div>

                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                             <?php
                                                                   $date = explode(' ',$por->created_at);
                                                                   $date2D = explode('-',$date[0]);
                                                                   $time2D = explode(':',$date[1]);
                                                                   echo '
                                                                    <div style="opacity:0.65; margin-top:-15px; width:100%;" class="d-flex justify-content-between">
                                                                            <span>'.$date2D[2].'/'.date("M",strtotime($date[0])).'/'.$date2D[0].'</span> 
                                                                            <span>'.$time2D[0].' : '.$time2D[1].'</span> 
                                                                    </div>
                                                                   ';
                                                                ?>

                                                                <div class="d-flex justify-content-between mt-2">
                                                                    <span> Tisch : {{$por->nrTable}} </span>
                                                                    <span><strong> {{ number_format($por->shuma, 2, '.', '')}}</strong> CHF</span>
                                                                </div>   
                                                                <?php
                                                                    if(PiketLog::where('order_u',$por->id)->first() != null && PiketLog::where('order_u',$por->id)->first()->piket < 0){
                                                                        $pointsUsed = PiketLog::where('order_u',$por->id)->first()->piket * (-1);
                                                                        $moneyOff = $pointsUsed*0.01;
                                                                        $pointsEr = 'none';
                                                                        if($por->tipPer != 0){
                                                                             $tipPer = $por->tipPer * 0.01;
                                                                             $nrTipPer = 1+$tipPer;
                                                                 
                                                                             $Price = $por->shuma + $moneyOff;
                                                                             $basePrice = $Price/$nrTipPer ;
                                                                 
                                                                             $tipValue = number_format($basePrice * ($por->tipPer*0.01), 2, '.', '');
                                                                        }
                                                                     }elseif(PiketLog::where('order_u',$por->id)->first() != null && PiketLog::where('order_u',$por->id)->first()->piket > 0){
                                                                         $pointsEr = PiketLog::where('order_u',$por->id)->first()->piket;
                                                                         $pointsUsed = 'none';
                                                                         if($por->tipPer != 0){
                                                                             $tipPer = $por->tipPer * 0.01;
                                                                             $nrTipPer = 1+$tipPer;
                                                                 
                                                                             $basePrice = $por->shuma/$nrTipPer ;
                                                                 
                                                                             $tipValue = number_format($por->shuma - $basePrice, 2, '.', '');
                                                                        }
                                                                     
                                                                     }else{
                                                                        $pointsUsed = 'none';
                                                                        $pointsEr = 'none';
                                                                         if($por->tipPer != 0){
                                                                             $tipPer = $por->tipPer * 0.01;
                                                                             $nrTipPer = 1+$tipPer;
                                                                 
                                                                             $basePrice = $por->shuma/$nrTipPer ;
                                                                 
                                                                             $tipValue = number_format($por->shuma - $basePrice, 2, '.', '');
                                                                        }
                                                                     }
                                                                ?>

                                                                <!-- Tip Value  -->
                                                                @if($por->tipPer != 0)
                                                                    <p>Kellner Trinkgeld: {{number_format($por->tipPer, 2, '.', '')}} CHF</p>
                                                                @endif
                                                                @if(isset($pointsEr) && $pointsEr != 'none')
                                                                    @if($por->tipPer != 0)
                                                                        <p style="margin-top:-12px;">
                                                                    @else
                                                                        <p>
                                                                    @endif
                                                                        Punkte: +{{$pointsEr}}</p>
                                                                @elseif(isset($pointsUsed) && $pointsUsed != 'none')
                                                                    @if($por->tipPer != 0)
                                                                        <p style="margin-top:-12px;">
                                                                    @else
                                                                        <p>
                                                                    @endif
                                                                        Punkte: -{{$pointsUsed}} <span class="ml-4">(-{{number_format($pointsUsed*0.01, 2, '.', '') }}) CHF</span></p>
                                                                @endif
                                                                
                                                                <!-- Prit bill if order id complete 'done' -->
                                                                @if($por->statusi == 3)
                                                                <a class="btn btn-block btn-outline-default" href="generatePDF/{{$por->id}}">
                                                                    Rechnung bekommen
                                                                </a>
                                                                @endif
                                                                
                                                                <hr style="margin-top:0px;">   



                                                                <div class="d-flex flex-wrap justify-content-between" >
                                                                @foreach(explode('---8---',$por->porosia) as $prodsPor)
                                                                    <?php
                                                                        $prodsPor2D = explode('-8-', $prodsPor);
                                                                    ?>
                                                                    

                                                                    <div style="width:55%; border-bottom:1px solid lightgray;">
                                                                          <p style="font-weight:bold;">> {{$prodsPor2D[3]}}x {{$prodsPor2D[0]}} </p>  
                                                                          @if(isset($prodsPor2D[5]) && $prodsPor2D[5] != '' )
                                                                          <p style="margin-top:-20px;">{{$prodsPor2D[5]}}</p>
                                                                          @endif
                                                                          @if(isset($prodsPor2D[6]) && $prodsPor2D[6] != '')
                                                                          <p style="margin-top:-20px;">{{$prodsPor2D[6]}}</p>
                                                                          @endif
                                                                    </div>
                                                                    <div style="width:25%; border-bottom:1px solid lightgray;">
                                                                    <?php $cn = 1;?>
                                                                    @if(isset($prodsPor2D[2]) && $prodsPor2D[2] != '' )
                                                                        @foreach(explode('--0--',$prodsPor2D[2]) as $exc)
                                                                            @if($exc != '')
                                                                                @if($cn >= 2)
                                                                                    <p style="margin-top:-20px;">{{explode('||', $exc)[0]}}</p>
                                                                                @else
                                                                                    <p>{{explode('||', $exc)[0]}}</p>
                                                                                @endif
                                                                            @endif
                                                                            <?php $cn++;?>
                                                                        @endforeach
                                                                    @endif
                                                                         
                                                                    </div>
                                                                    <div style="width:20%; border-bottom:1px solid lightgray;">
                                                                        <p><strong>{{$prodsPor2D[4]}}</strong> CHF</p>
                                                                        @if($prodsPor2D[3] >1)
                                                                            <p style="margin-top:-10px;"><strong>{{$prodsPor2D[3] * $prodsPor2D[4]}}</strong> CHF</p>
                                                                        @endif
                                                                    </div>
                                                                   
                                                 
                                                                    @endforeach
                                                                </div>
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Schliessen</button>
                                                </div>

                                                </div>
                                            </div>
                                        </div>

                                
                                @endforeach

                                <!-- The Modal -->





                         





                <div style="width:96%; margin-left:2%;" class="d-flex justify-content-between b-white flex-wrap p-2">
                
                     <h3 style="width:100%;" class="color-qrorpa text-center mb-5"> <strong>Bestellungen</strong> </h3>
                    <hr style="width:100%; margin-top:-15px;">
                    <p style="width:25%;"><strong>Restaurant</strong></p>
                    <p style="width:15%;"><strong>Tisch</strong></p>
                    <p style="width:20%;"><strong>gesamt</strong></p>
                    <p style="width:20%;"></p>


                    @foreach($porosit as $por)
                        @if($por->byId == Auth::user()->id)
                            @if(!empty(Restorant::find($por->Restaurant)))
                                <p style="width:25%;">{{Restorant::find($por->Restaurant)->emri}}</p>
                            @else
                                <p style="width:25%;"> --- </p>
                            @endif
                                        
                            <p style="width:15%;">{{$por->nrTable}}</p>

                            <p style="width:20%;">{{$por->shuma}} <span style="opacity:0.75;">CHF</span></p>

                            <button type="button" style="width:20%;" class="btn btn-block btn-outline-dark mb-3" data-toggle="modal" data-target="#moreD{{$por->id}}">Mehr...</button>
                        @endif
                    @endforeach        
                </div>
             
                        
      
@endsection
