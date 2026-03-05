

<div id="soundsCartDiv">
   
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    var ResId = $('#theRes').val();
    var table = $('#theTable').val();


    // clNewTab appOrder PUSHER

    var intervalId = window.setInterval(function(){
        
        $.ajax({
            url: '{{ route("notify.checkUnrespondetClient") }}',
            method: 'post',
            data: {
                resId: ResId,
                tableNr: table,
                phoneNrVerify: $('#verifiedNr007').val(),
                _token: '{{csrf_token()}}'
            },
            success: (res) => {
                res = $.trim(res);
                if(res != 'none'){
                    var numberVerufy = $('#verifiedNr007').val();
                    if(res == 'reloadGhost' && (numberVerufy.indexOf("|") >= 0)){
                        location.reload();
                    }else{
                        var res2D = res.split('||');
                        if(res2D[0] == 'clNewTab'){
                            if(res2D[1] == 'userSuccess'){
                                if(res2D[3] == 'invalideCartToResTable@invalideCartToResTable@invalideCartToResTable'){
                                    $('#responseTCReqSuccess').html('Der Administrator des Restaurants hat Ihre Übertragung genehmigt. Sie können fortfahren.');
                                    $('#responseTCReqSuccess').show(250).delay(3000).hide(250); 
                                    $('#invalideCartToResTable2').modal('hide');
                                }else{
                                    $('#responseTCReqSuccess').html(res2D[3]);
                                    $('#responseTCReqSuccess').show(250).delay(3000).hide(250); 
                                    setTimeout(function(){ 
                                        window.location = "/?Res="+ResId+"&t="+res2D[2];
                                    }, 3000);
                                }
                            }else if(res2D[1] == 'userError'){
                                if(res2D[3] == 'invalideCartToResTable@invalideCartToResTable@invalideCartToResTable'){
                                    $('#adminToClientMsg').show(250); 
                                    $('#adminToClientMsgText').html('Der Administrator des Restaurants hat Ihre Übertragung nicht genehmigt!');
                                    setTimeout(function(){ 
                                        $.ajax({
                                            url: '{{ route("cart.emptyTheCart") }}',
                                            method: 'post',
                                            data: {_token: '{{csrf_token()}}'},
                                            success: () => {location.reload();},
                                            error: (error) => {console.log(error);}
                                        });
                                    }, 3000);
                                }else{
                                    // navigator.vibrate(1000);
                                    const canVibrate = window.navigator.vibrate
                                    if (canVibrate) window.navigator.vibrate(1000)
                                    $('#adminToClientMsg').show(250); 
                                    $('#adminToClientMsgText').html(res2D[3]);
                                }
                            }else if(res2D[1] == 'userMsg'){
                                if($('#verifiedNr007').length && $('#verifiedNr007').val() == res2D[4]){
                                    // navigator.vibrate(1000);
                                    const canVibrate = window.navigator.vibrate
                                    if (canVibrate) window.navigator.vibrate(1000)
                                    $('#adminToClientMsg').show(250); 
                                    $('#adminToClientMsgText').html(res2D[2]);
                                    $('#adminToClientMsgAdmin').val(res2D[3]);
                                }
                            }
                        }else if(res2D[0] == 'addToCartAdmin'){
                            if($('#verifiedNr007').val() == res2D[3]){
                                this.addToCartAdminProcess(res);
                            }else{
                                setTimeout( function(){ location.reload(); } , 1000 );
                            }
                        }else if(res2D[0] == 'CartMsg'){
                            if(!$('#succTrack').length){
                                this.CartMsgProcess(res);
                            }
                        }else if(res2D[0] == 'removePaidProduct'){
                            // location.reload();
                            $("#cartAllElements").load(location.href+" #cartAllElements>*","");
                            $('#adminPayGetReceipt').modal('show');
                            $('#adminPayGetReceiptOrIdInput').val(res2D[2]);

                        }else if(res2D[0] == 'prodStatChange'){
                            $("#showMyOrdersDiv").load(location.href+" #showMyOrdersDiv>*","");
                            $("#otherOrdersOnTab").load(location.href+" #otherOrdersOnTab>*","");
                            
                            $("#soundsCartDiv").html('<audio id="soundsCartAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   '); 
                                 
                        }else if(res2D[0] == 'taOrdStatusChange'){
                            console.log('yes');
                            $("#trackOrStatusShowDiv").load(location.href+" #trackOrStatusShowDiv>*","");
                        }
                    }
                }
            },
            error: (error) => { console.log(error); }
        });
    }, 2500);







                    
    //  12dqwd2bdu1hn1wid2d
    function addToCartAdminProcess(response){
        var d2d = response.split('||')

        if(ResId == d2d[1] && table == d2d[2]){
            if($('#verifiedNr007').val() == d2d[3]){
                // add to cart request  +TabOrderId
                $.ajax({
                    url: '{{ route("dash.addToCartAdminsNewOrderToMe") }}',
                    method: 'post',
                    data: {
                        tabOId: d2d[4],
                        _token: '{{csrf_token()}}'
                    },
                    success: () => { location.reload();},
                    error: (error) => {
                        console.log(error);
                    }
                });
            }else{
                location.reload();
            }
        }
    }




    function CartMsgProcess(response){
        var FromCart = response.split("||");
        if(ResId == FromCart[1] && table == FromCart[2]){
            if(FromCart[3] == 1){
                location.reload();
            }else if(FromCart[3] == 7){
                if($('#verifiedNr007').val() == FromCart[4]){
                    $.ajax({
                        url: '{{ route("Res.DeleteTheCart") }}',
                        method: 'post',
                        data: { _token: '{{csrf_token()}}' },
                        success: () => { location.reload(); },
                        error: (error) => { console.log(error); }
                    });
                }else{
                    location.reload();
                }
            }else if(FromCart[3] == 9){
                // alert('we did it');
                $.ajax({
                    url: '{{ route("Res.DeleteTheCart") }}',
                    method: 'post',
                    data: { _token: '{{csrf_token()}}' },
                    success: () => { location.reload(); },
                    error: (error) => { console.log(error); alert('Oops! Something went wrong') }
                });
            }
        }
    }











                    function closeMSGAC(){
                        $('#adminToClientMsg').hide(250); 
                    }
                    function MSGACsendAnswer01(){
                        $('#sendAntwortenToAdmin').show(200);
                    }
                    function MSGACsendAnswer02(){
                        if($('#MSGACsendAnswerText').val() != '' && $('#MSGACsendAnswerText').val() != ' '){
                                $.ajax({
                                    url: '{{ route("TabChngCli.MsgUserToAdmin") }}',
                                    method: 'post',
                                    data: {
                                        res: $('#theRes').val(),
                                        table: $('#theTable').val(),
                                        msg: $('#MSGACsendAnswerText').val(),
                                        msgAdmin: $('#adminToClientMsgText').html(),
                                        adminId: $('#adminToClientMsgAdmin').val(),
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: () => {
                                        $("#adminToClientMsg").load(location.href+" #adminToClientMsg>*","");
                                        $('#adminToClientMsg').hide(250); 
                                    },
                                    error: (error) => {
                                        console.log(error);
                                        alert('bitte aktualisieren und erneut versuchen!');
                                    }
                                });
                        }else{
                            $('#MSGACsendAnswerTextError01').show(200).delay(3000).hide(200);
                        }
                    }









                    function callWaiterF(){
                        $.ajax({
                            url: '{{ route("waiter.call") }}',
                            method: 'post',
                            data: {
                                res: $('#restaurantCW').val(),
                                table: $('#tableCW').val(),
                                comment: $('#commentCW').val(),
                                _token: '{{csrf_token()}}'
                            },
                            success: (response) => {
                                $('#waiterIsComming').show(500).delay(3500).hide(500);
                            },
                            error: (error) => {
                                console.log(error);
                                // alert('Oops! Something went wrong');
                                $('#waiterIsNotComming').show(500).delay(3500).hide(500);
                            }
                        })
                    }



























                    var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {cluster: 'eu'});







                    var channel = pusher.subscribe('removeProdsCart');
                    channel.bind('App\\Events\\removePaidProduct', function(data) {
                        var dataJ = JSON.stringify(data);
                        var dataJ2 = JSON.parse(dataJ);
                        var d2d = dataJ2.text.split('||');
                        if(d2d[2] == 'a'){
                            if($('#verifiedNr007').val() == d2d[0]){
                                // remove from cart "Paid" +TabOrderId
                                $.ajax({
                                    url: '{{ route("dash.removePaidProductCart") }}',
                                    method: 'post',
                                    data: {
                                        tabOId: d2d[1],
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: () => { location.reload(); },
                                    error: (error) => {
                                        console.log(error);
                                        alert('bitte aktualisieren und erneut versuchen!');
                                    }
                                });
                            }else if($('#RestoIdId').val() == d2d[3] && $('#TableIdId').val() == d2d[4]){
                                location.reload();
                            }
                        }else if(d2d[2] == 'b'){
                            if('0770000000' == d2d[0] && $('#RestoIdId').val() == d2d[3] && $('#TableIdId').val() == d2d[4]){  
                                $.ajax({
                                    url: '{{ route("dash.removePaidProductCart2") }}',
                                    method: 'post',
                                    data: {
                                        tabOId: d2d[1],
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: () => { location.reload(); },
                                    error: (error) => {
                                        console.log(error);
                                        alert('bitte aktualisieren und erneut versuchen!');
                                    }
                                });
                            }else if($('#verifiedNr007').val() == d2d[0]){
                                // remove from cart "Paid" +TabOrderId
                                $.ajax({
                                    url: '{{ route("dash.removePaidProductCart2") }}',
                                    method: 'post',
                                    data: {
                                        tabOId: d2d[1],
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: () => { location.reload(); },
                                    error: (error) => {
                                        console.log(error);
                                        alert('bitte aktualisieren und erneut versuchen!');
                                    }
                                });
                            }else if($('#RestoIdId').val() == d2d[3] && $('#TableIdId').val() == d2d[4]){
                                location.reload();
                            }
                        }
                    });

</script>
