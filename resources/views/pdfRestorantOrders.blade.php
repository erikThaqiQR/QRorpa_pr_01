<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>PDF</title>
        
    </head>

    <body>
        @foreach($items as $order)
            <?php 
                $date = explode(' ',$order->created_at);
                $day2D = explode('-',$date[0]);
                $time2D = explode(':',$date[1]);
                
            ?>
            <div style="margin-bottom: 5px; ">
                <p style=" color:rgb(72,81,87); font-weight:bold; display:inline;">{{$day2D[2]}} / {{$day2D[1]}} / {{$day2D[0]}} _ </p>
                <p style=" color:rgb(72,81,87); font-weight:bold; display:inline;">T:{{$order->nrTable}} _ </p>
                <p style=" color:rgb(72,81,87); font-weight:bold; display:inline;">{{$order->payM}} _ </p>
                <p style=" color:rgb(72,81,87); font-weight:bold; display:inline;">{{$order->shuma}} <sup style="font-size:9px;">CHF</sup> _ </p>
                @if($order->tipPer > 0)
                    <p style=" color:rgb(72,81,87); font-weight:bold; display:inline;">+ {{$order->tipPer}} <sup style="font-size:9px;">CHF</sup> _ </p>
                @endif
            </div>
            @foreach(explode('---8---',$order->porosia) as $prs)
                <?php $prs2D = explode('-8-',$prs);?>
                <div  style="margin-bottom: 10px; ">
                    <p style=" color:rgb(72,81,87); font-weight:bold; display:inline;">
                            @if($prs2D[3] > 1)
                                {{$prs2D[3]}} X 
                            @endif
                            {{$prs2D[0]}} 
                            @if($prs2D[5] != '' && $prs2D[5] != 'empty')
                                " {{$prs2D[5]}} "
                            @endif
                            ( {{$prs2D[4] * $prs2D[3]}} <sup style="font-size:9px;">CHF</sup> )
                        <br>
                            {{$prs2D[1]}}  
                            @if($prs2D[2] != '' && $prs2D[2] != 'empty')
                                <sup style="margin-left:20px;">Zutaten: 
                                    @foreach(explode('--0--',$prs2D[2]) as $ext)
                                        <?php $ext2D = explode('||',$ext);
                                            if(!isset($ext2D[1])){$ext2D[1] = '';}
                                        ?>
                                        <sup style="margin-left:10px;">"{{$ext2D[0]}} ({{$ext2D[1]}}<sup style="font-size:9px;">CHF</sup>) "</sup>
                                    @endforeach
                                </sup>
                            @endif
                        @if($prs2D[6] != '')
                            <br>
                            <sup style="font-size: 10px;">Kommentar: {{$prs2D[6]}}</sup>
                        @endif
                    </p>
                  
                </div>
                
            @endforeach

            <hr style="margin-bottom: 15px; color:rgb(39,190,175);">
        @endforeach
       
    </body>

</html>