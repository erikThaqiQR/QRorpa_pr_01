<?php

use App\Cupon;
    use App\Restorant;

    $cpForRoul = Cupon::where([['toRes',$_GET["Res"]],['forRoulette',1]])->count();
?>
<!-- rouletteCuponId -->
@if(Cookie::has('rouletteCuponId') || $cpForRoul == 0)
    <!-- tab aktiv / pop-up reklama ... -->
    @include('layouts.layoutParts.firstCheckScript01')
    <script>
        // reset the cookie to 30 days
        $.ajax({
            url: '{{ route("wheel.wheelSetCookie") }}',
            method: 'post',
            data: {
                cId: '{{Cookie::get("rouletteCuponId")}}',
                _token: '{{csrf_token()}}'
            },
            success: (respo) => {},
            error: (error) => { console.log(error);}
        });
      
    </script>
@else
    <!-- Ruleta per kupona -->
 
    <?php
        $theRestaurant = Restorant::find($_GET["Res"]);
        $openStat2D = explode('-||-',$theRestaurant->isOpen);
    ?>
    @if (($_GET["t"] != 500 && $openStat2D[0] == '1') || ($_GET["t"] == 500 && $openStat2D[1] == '1'))
        <script>
            console.log('no');
        </script>
        @include('layouts.layoutParts.firstCheckScript021')
    <!-- layouts.layoutParts.firstCheckScript02-->
    @endif
@endif