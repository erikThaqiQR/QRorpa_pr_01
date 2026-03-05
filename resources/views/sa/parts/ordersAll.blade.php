<?php
    use App\Orders;
    use App\Restorant;
                           

    $orders = Orders::all()->sortByDesc('created_at');
?>

<div style="padding:10px;">

<br><br>
    <div class="container">
        <div class="row">
            <div class="col-12 text-left">
                <p class="color-qrorpa" style="font-size:25px; margin-bottom:-5px;"><strong>Orders</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <button class="b-qrorpa color-white pr-3 pl-3 br-25">All Restaurants</button>
            </div>
            <div class="col-9">
            </div>
        </div>
    </div>

    <br>
    <div class="container ml-2">
        <div class="row" style="margin-bottom:-20px;">
            <div class="col-1">
                <p class="color-qrorpa" style="opacity:0.5">Date|Time</p>
            </div>
            <div class="col-1">
                <p class="color-qrorpa" style="opacity:0.5">Restaurant</p>
            </div>
            <div class="col-1">
                <p class="color-qrorpa" style="opacity:0.5">Table nr</p>
            </div>
            <div class="col-2">
                <p class="color-qrorpa" style="opacity:0.5">Product(s)</p>
            </div>
            <div class="col-1">
                <p class="color-qrorpa" style="opacity:0.5">Quantity</p>
            </div>
            <div class="col-3">
                <p class="color-qrorpa" style="opacity:0.5">Ektra(s)</p>
            </div>
            <div class="col-1">
                <p class="color-qrorpa" style="opacity:0.5">Total</p>
            </div>
            <div class="col-1">
                <p class="color-qrorpa" style="opacity:0.5">Pay</p>
            </div>
            <div class="col-1">
                <p class="color-qrorpa" style="opacity:0.5">Status</p>
            </div>
        </div>
    </div>
    <hr style="margin-bottom:0px;">
    <div class="pb-4 ">
        <ul class="list-group list-group-flush margin-top:-20px;">
            @foreach($orders as $order)
                <li class="list-group-item margin-top:-3px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-1">
                                <?php
                                    $orderTime = explode(' ',$order->created_at)[1];
                                    $orderDate = explode(' ',$order->created_at)[0];
                                    $orderDate2D = explode('-',$orderDate);
                                    $orderTime2D = explode(':',$orderTime);

                                    echo '<p class="color-qrorpa">'.$orderTime2D[0].':'.$orderTime2D[1].'</p>
                                    <p class="color-qrorpa" style="margin-top:-13px; font-size:10px;">'.$orderDate2D[2].'/'.$orderDate2D[1].'/'.$orderDate2D[0].'</p>
                                    <p class="color-qrorpa" style="margin-top:-13px; margin-bottom:-10px; font-size:10px;">'. date("l", strtotime($orderDate)).'</p>
                                    ';
                                ?>
                            </div>
                            <div class="col-1">
                                <br>
                               
                                @if($order->Restaurant == 0)
                                    <p class="color-qrorpa"> --- </p>
                                @else
                                    <p class="color-qrorpa">
                                    @if(Restorant::find($order->Restaurant) != null)    
                                        {{Restorant::find($order->Restaurant)->emri}}
                                    @endif
                                    </p>
                                @endif
                                
                            </div>
                            <div class="col-1">
                                <br>
                               <p class="color-qrorpa">{{$order->nrTable}}</p> 
                            </div>
                            
                            <?php
                                $row = 1;
                                echo '<div class="col-2">';
                                foreach(explode('---8---', $order->porosia) as $poro){
                                    
                                    if($row++ == 1){
                                        echo '<p class="color-qrorpa" style="font-size:13px;">'.explode('-8-',$poro)[0].'</p> ';
                                    }else{
                                        echo '<p class="color-qrorpa" style="margin-top:-15px; font-size:13px;">'.explode('-8-',$poro)[0].'</p> ';
                                    }
                                }
                                echo ' </div>';
                                $row = 1;
                                echo '<div class="col-1 ml-3">';
                                foreach(explode('---8---', $order->porosia) as $poro){
                                    if(isset(explode('-8-',$poro)[3])){
                                        if($row++ == 1){
                                            echo '<p class="color-qrorpa" style="font-size:13px;">'.explode('-8-',$poro)[3].' X</p> ';
                                        }else{
                                            echo '<p class="color-qrorpa" style="margin-top:-15px; font-size:13px;">'.explode('-8-',$poro)[3].' X</p> ';
                                        }
                                    }
                                }
                                echo ' </div>';

                                $row = 1;
                                echo '<div class="col-3">';
                                foreach(explode('---8---', $order->porosia) as $poro){
                                    if(isset(explode('-8-',$poro)[2])){
                                        foreach(explode('--0--',explode('-8-',$poro)[2]) as $extra){
                                            if(empty(explode('||',$extra)[0])){

                                            }else{
                                                if($row++ == 1){
                                                    echo '<span class="color-qrorpa" style="font-size:13px;"> + '.explode('||',$extra)[0].'</span> ';
                                                }else{
                                                    echo '<span class="color-qrorpa" style="margin-top:-15px; font-size:13px;">
                                                        + '.explode('||',$extra)[0].'</span> ';
                                                }
                                            }
                                        }
                                    }
                                    echo '<br>';
                                }
                                echo ' </div>';
                            ?>
                            <div class="col-1">
                                <p class="color-qrorpa"><span style="font-size:8px;">CHF </span>{{$order->shuma}}</p> 
                            </div>
                            <div class="col-1">
                                <p class="color-qrorpa">{{$order->payM}}</p> 
                            </div>

                            @if($order->statusi == 0)
                                <p class="color-qrorpa">Waiting</p> 
                            @elseif($order->statusi == 1)
                                <p class="color-qrorpa">Cooking...</p> 
                            @elseif($order->statusi == 2)
                                <p class="color-qrorpa">Declined</p> 
                            @elseif($order->statusi == 3)
                                <p class="color-qrorpa">Done</p> 
                            @endif
                           
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
    