


<div class="modal" id="couponRouletteModal" style="background-color: rgba(0,0,0,0.65);" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-md">
        <div style="background-color: transparent; margin-top:60px;" class="modal-content">
            <div class="modal-body text-center d-flex flex-wrap justify-content-between" 
            style="border:none; width:100%; color:white; font-weight:bold;" id="hasForGhostBodyInfo">
                <!-- <img src="storage/images/logo_QRorpa.png" style="width: 50%; height:auto; margin-left:25%; margin-right:25%; margin-bottom:20px;" alt=""> -->

                <p style="color:white; font-size:1rem; width:100%;">
                    <strong>Drehe das Rad, um einen tollen Preis zu gewinnen. Verwende den Gutscheincode während der Bestellung im Warenkorb.</strong>
                </p>
                <canvas id="couponRoulette" width="340" height="340" style="margin: 0 auto;"></canvas>

                <button type="button" class="btn btn-info" value="spin" style="width:100%; margin:10px 0 0 0; padding:5px; font-size:1.7rem;" id='spin' disabled>
                    <strong>Drehe das Rad</strong>
                </button>

                <!-- <input type="text" id="couponCode" class="text-center btn-success" style="width:100%; font-weight:bold; font-size:2.8rem; background-color: transparent; color:white; border:none;"> -->
            
                <button type="button" id="exitWheelBtn" class="btn btn-danger" style="width:100%; margin:30px 0 0 0; padding:5px; font-size:1.7rem; display:none;"
                class="close" data-dismiss="modal" aria-label="Close">
                    <strong>Schliessen</strong>
                </button>
                <button type="button" class="btn " id="exitWheelBtnBefore" style="width:100%; margin:25px 0 0 0; padding:5px; font-size:2.2rem; color:white;"
                class="close" data-dismiss="modal" aria-label="Close">
                    <strong>X</strong>
                </button>

                <div style="position: fixed; top:30%; width:80%; left:10%; " id="couponCodeDiv">
                    <p id="couponCode01" style="display:none; color: rgba(38,245,215,255); font-weight:bold; font-size:1.7rem; background-color:rgba(20,91,86,255); border-radius:8px;"> xxxxxxx xxxx</p>
                    <p id="couponCode02" style="display:none; color: black; font-size:1.4rem; background-color:white; border-radius:8px;">xxx xxxx xxx xxxxxx xxxxx xxxxx xxxx xxxx xxxx </p>
                </div>

                <img id="fireworkGif01" style="position: fixed; top:-50px; left:-10px; width:100%; display:none;" src="storage/gifs/firework_02.gif" alt="">
                <img id="fireworkGif02" style="position: fixed; top:180px; left:-10px; width:100%; display:none;" src="storage/gifs/firework_02.gif" alt="">
                <img id="fireworkGif03" style="position: fixed; top:360px; left:-10px; width:100%; display:none;" src="storage/gifs/firework_02.gif" alt="">
                <img id="fireworkGif04" style="position: fixed; top:540px; left:-10px; width:100%; display:none;" src="storage/gifs/firework_02.gif" alt="">
                <img id="fireworkGif05" style="position: fixed; top:720px; left:-10px; width:100%; display:none;" src="storage/gifs/firework_02.gif" alt="">
            
            </div>
        </div>
    </div>
</div>

<script>
    
    var options = [];

    function getRandomInt(max) {
        return Math.floor(Math.random() * max);
    }

    var startAngle = 0;
    var arc = 0;
    var spinTimeout = null;

    var spinArcStart = getRandomInt(20);
    var spinTime = 0;
    var spinTimeTotal = 0;

    var ctx;
   
    function startRouleteGame(){
        $.ajax({
            url: '{{ route("wheel.getCoupons") }}',
            method: 'post',
            data: {
                res: "{{$_GET['Res']}}",
                _token: '{{csrf_token()}}'
            },
            success: (respo) => {
                respo = $.trim(respo);
                console.log(respo);
                if(respo != 'noGame'){

                    $('#couponRouletteModal').modal('show');
                    respo2D = respo.split('|||');
                
                    $.each(respo2D, function( index, value ) {
                        options.push(String(value));
                    });
                    startAngle = getRandomInt(380);
                    arc = Math.PI / (options.length / 2);
                
                    $('#spin').prop('disabled',false);
                    startWheel();
                }
            },
            error: (error) => { console.log(error);}
        });
    }
       
    // var options = ["100 CHF", "10 CHF", "2 %", "5 CHF", "10 %", "COLA", "7 %", "15 CHF", 'SPRITE'];

    document.getElementById("spin").addEventListener("click", spin);

    function byte2Hex(n) {
        var nybHexString = "0123456789ABCDEF";
        return String(nybHexString.substr((n >> 4) & 0x0F,1)) + nybHexString.substr(n & 0x0F,1);
    }

    function RGB2Color(r,g,b) {
        // return '#27beaf';
        return '#' + byte2Hex(r) + byte2Hex(g) + byte2Hex(b);
    }

    function getColor(item, maxitem) {
        var phase = 0;
        var center = 128;
        var width = 127;
        var frequency = Math.PI*2/maxitem;
        
        red   = Math.sin(frequency*item+2+phase) * width + center;
        green = Math.sin(frequency*item+0+phase) * width + center;
        blue  = Math.sin(frequency*item+4+phase) * width + center;
        
        return RGB2Color(red,green,blue);
    }

    function drawRouletteWheel() {
        var canvas = document.getElementById("couponRoulette");
        if (canvas.getContext) {
            var outsideRadius = 170;
            var textRadius = 100;
            var insideRadius = 50;

            ctx = canvas.getContext("2d");
            ctx.clearRect(0,0,340,340);

            ctx.strokeStyle = "green";
            ctx.lineWidth = 1;

            ctx.font = 'bold 14px Helvetica, Arial';

            for(var i = 0; i < options.length; i++) {
                var angle = startAngle + i * arc;
                //ctx.fillStyle = colors[i];
                ctx.fillStyle = getColor(i, options.length);

                ctx.beginPath();
                ctx.arc(170, 170, outsideRadius, angle, angle + arc, false);
                ctx.arc(170, 170, insideRadius, angle + arc, angle, true);
                ctx.stroke();
                ctx.fill();

                ctx.save();
                ctx.shadowOffsetX = -1;
                ctx.shadowOffsetY = -1;
                ctx.shadowBlur    = 0;
                // ctx.shadowColor   = "rgb(220,220,220)";
                ctx.fillStyle = "white";
                ctx.translate(170 + Math.cos(angle + arc / 2) * textRadius, 
                                170 + Math.sin(angle + arc / 2) * textRadius);
                ctx.rotate(angle + arc / 2 + Math.PI / 2);
                var text = options[i];
                ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
                ctx.restore();
            } 

            //Arrow
            ctx.fillStyle = "black";
            ctx.beginPath();
            ctx.moveTo(170 - 6, 170 - (outsideRadius + 7));
            ctx.lineTo(170 + 6, 170 - (outsideRadius + 7));
            ctx.lineTo(170 + 6, 170 - (outsideRadius - 7));
            ctx.lineTo(170 + 14, 170 - (outsideRadius - 7));
            ctx.lineTo(170 + 0, 170 - (outsideRadius - 19));
            ctx.lineTo(170 - 14, 170 - (outsideRadius - 7));
            ctx.lineTo(170 - 6, 170 - (outsideRadius - 7));
            ctx.lineTo(170 - 6, 170 - (outsideRadius + 7));
            ctx.fill();
        }
    }

    function spin() {
        $('#exitWheelBtnBefore').hide(50);
        spinAngleStart = getRandomInt(10) * 1 + 10;
        spinTime = 0;
        spinTimeTotal = 6000 ;
        $('#spin').prop('disabled', true);
        rotateWheel();
    }

    function rotateWheel() {
        spinTime += 12;
        if(spinTime >= spinTimeTotal) {
            stopRotateWheel();
            return;
        }
        var spinAngle = spinAngleStart - easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
        startAngle += (spinAngle * Math.PI / 180);
        drawRouletteWheel();
        spinTimeout = setTimeout('rotateWheel()', 35);
    }

            function stopRotateWheel() {
                clearTimeout(spinTimeout);
                var degrees = startAngle * 180 / Math.PI + 90;
                var arcd = arc * 180 / Math.PI;
                var index = Math.floor((360 - degrees % 360) / arcd);
                ctx.save();
                ctx.fillStyle = "black";
                ctx.font = 'bold 20px Helvetica, Arial';
                var text = options[index]
                // ctx.fillText(text, 170 - ctx.measureText(text).width / 2, 170 + 10);

                // $('#couponCode').html(text);
                // $('#couponCode').val(text);

                $.ajax({
                    url: '{{ route("wheel.getCouponCode") }}',
                    method: 'post',
                    data: {
                        res: "{{$_GET['Res']}}",
                        coupTxt: text,
                        _token: '{{csrf_token()}}'
                    },
                    success: (respo) => {
                        respo = $.trim(respo);
                        respo2D = respo.split('|||');

                        $('#couponCode01').show(50);
                        $('#couponCode01').html('GEWONNEN! '+respo2D[1]);
                        $('#couponCode02').show(50); 
                        $('#couponCode02').html('Dein Gewinncode lautet: <br> <strong>"'+respo2D[0]+'"</strong> <br> und kann im Warenkorb aktiviert werden.');

                        $('#fireworkGif01').show(1).delay(3200).hide(1);
                        $('#fireworkGif02').show(1).delay(3200).hide(1);
                        $('#fireworkGif03').show(1).delay(3200).hide(1);
                        $('#fireworkGif04').show(1).delay(3200).hide(1);
                        $('#fireworkGif05').show(1).delay(3200).hide(1);

                        setTimeout( function(){
                            $('#exitWheelBtn').show(50);
                        }  , 3202 );

                        // set cookie
                        $.ajax({
                            url: '{{ route("wheel.wheelSetCookie") }}',
                            method: 'post',
                            data: {
                                cId: respo2D[2],
                                _token: '{{csrf_token()}}'
                            },
                            success: (respo) => {},
                            error: (error) => { console.log(error);}
                        });
                
                    
                    },
                    error: (error) => { console.log(error);}
                });
                
                ctx.restore();

                $('#spin').prop('disabled', true);
            }

    function easeOut(t, b, c, d) {
        var ts = (t/=d)*t;
        var tc = ts*t;
        return b+c*(tc + -3*ts + 3*t);
    }

    function startWheel(){
        drawRouletteWheel();
    }
</script>