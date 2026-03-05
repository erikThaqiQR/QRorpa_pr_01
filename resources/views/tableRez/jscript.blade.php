<script>
    $('.numeric').on('input', function (event) { 
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    function setPrsNr(prsNr){
        if($('#rezPersonNrInp').val() > 0 && $('#rezPersonNrInp').val() < 10){
            $('#setPrsNrBtn'+$('#rezPersonNrInp').val()).removeClass('btn-qrorpa-01-roundSelected');   
        }
        $('#setPrsNrInp').attr('style','width: 19%; padding:0; border-radius:20px; font-size:21px; background-color:lightgray;');
        $('#setPrsNrInp').val('');
        
        $('#setPrsNrBtn'+prsNr).addClass('btn-qrorpa-01-roundSelected');
        $('#rezPersonNrInp').val(prsNr);

        if($('#rezRegErrorMsg01').is(':visible')){ $('#rezRegErrorMsg01').hide(100); }
    }

    function setPrsNrInp(){
        if($('#rezPersonNrInp').val() > 0 && $('#rezPersonNrInp').val() < 10){
            $('#setPrsNrBtn'+$('#rezPersonNrInp').val()).removeClass('btn-qrorpa-01-roundSelected');
        }
        if($('#setPrsNrInp').val() != ''){
            $('#setPrsNrInp').attr('style','width: 19%; padding:0; border-radius:20px; font-size:21px; color:white; background-color:rgb(39,190,175);');
        
            $('#rezPersonNrInp').val($('#setPrsNrInp').val());

            if($('#rezRegErrorMsg01').is(':visible')){ $('#rezRegErrorMsg01').hide(100); }
        }else{
            $('#setPrsNrInp').attr('style','width: 19%; padding:0; border-radius:20px; font-size:21px; background-color:lightgray;');
            $('#rezPersonNrInp').val('0');
        }
    }







    function showOtherMonth(mnthNr){
        $('#month0'+$('#rezMonthShowSelected').val()).attr('style','width: 100%; display:none;');
        $('#monthSelBtn0'+$('#rezMonthShowSelected').val()).removeClass('shadow-none btn-qrorpa-02');

        $('#month0'+mnthNr).attr('style','width: 100%; display:flex;');
        $('#monthSelBtn0'+mnthNr).addClass('shadow-none btn-qrorpa-02');

        $('#rezMonthShowSelected').val(mnthNr);

        $('#dtSelectBtn'+$('#rezDateSelectDt').val()).removeClass('btn-qrorpa-01-roundSelected');
        $('#rezDateSelectDt').val('none');

        $('#timeSelectDiv').html('<p style="width: 100%; margin:0; font-size:21px;"> <i style="color: rgb(39,190,175);" class="fa-regular fa-clock mr-2 ml-2"></i>Uhrzeit auswählen (von - bis)</p>'+
        '<div class="alert alert-info text-center mt-1" style="width:100%;"><strong>Wählen Sie ein Datum aus, um die möglichen Reservierungszeiten anzuzeigen</strong></div>');

        $('#timeSet1Nr').val(0);
        $('#timeSet1Place').val(0);
        $('#timeSet1Val').val(0);
        $('#timeSet2Nr').val(0);
        $('#timeSet2Place').val(0);
        $('#timeSet2Val').val(0);
    }

    function selectRezDate(theDt,resId){
        var theDt = theDt.split(' ');
        if($('#rezDateSelectDt').val() != 'none'){
            $('#dtSelectBtn'+$('#rezDateSelectDt').val()).removeClass('btn-qrorpa-01-roundSelected');
        }

        $('#dtSelectBtn'+theDt[0]).addClass('btn-qrorpa-01-roundSelected');
        $('#rezDateSelectDt').val(theDt[0]);


        $('#timeSelectDiv').html('<p style="width: 100%; margin:0; font-size:21px;"> <i style="color: rgb(39,190,175);" class="fa-regular fa-clock mr-2 ml-2"></i>Uhrzeit auswählen (von - bis)</p>');
        
        $('#timeSet1Nr').val(0);
        $('#timeSet1Place').val(0);
        $('#timeSet1Val').val(0);
        $('#timeSet2Nr').val(0);
        $('#timeSet2Place').val(0);
        $('#timeSet2Val').val(0);
        
        $.ajax({
			url: '{{ route("tableRez.getWorkingHrsForRes") }}',
			method: 'post',
			data: {
				dt: theDt,
                resId: resId,
				_token: '{{csrf_token()}}'
			},
			success: (gwhRes) => {
                gwhRes = $.trim(gwhRes);
                gwhRes2D = gwhRes.split('|||');

                var timeSlotCount = 1;

                if(gwhRes2D[0] != 'none'){
                    var prep1 = gwhRes2D[0].split(' ')[1];
                    prep1 = prep1.split(':');

                    var prep2 = gwhRes2D[1].split(' ')[1];
                    prep2 = prep2.split(':');
                }

                if(gwhRes2D[2] != 'none'){
                    var prep3 = gwhRes2D[2].split(' ')[1];
                    prep3 = prep3.split(':');

                    var prep4 = gwhRes2D[3].split(' ')[1];
                    prep4 = prep4.split(':');
                }

                if(gwhRes2D[0] == 'none' && gwhRes2D[2] == 'none'){
                    $('#timeSelectDiv').append('<div class="alert alert-danger text-center mt-1" style="width:100%;"><strong>Für dieses Datum sind keine Arbeitszeiten verfügbar</strong></div>');
                }else{
                    
                    if($('#rezRegErrorMsg02').is(':visible')){ $('#rezRegErrorMsg02').hide(100); }

                    var showTimeHr;
                    var showTimeMn;
                    if(gwhRes2D[0] != 'none'){
                        showTimeHr = prep1[0];
                        showTimeMn = prep1[1];

                        do {
                            var cont = true;
                            $('#timeSelectDiv').append( '<div id="timeDiv1O'+timeSlotCount+'" style="width:16.66666%;">'+
                                                            '<button id="timeBtn1O'+timeSlotCount+'"  class="btn shadow-none pb-3 pt-3" onclick="selectRezTime(\'1\',\''+timeSlotCount+'\')" style="width:100%; height:100%; padding:0;">'+showTimeHr+':'+showTimeMn+'</button>'+
                                                        '</div>');
                            if(showTimeMn == '00'){
                                showTimeMn = '30';
                            }else{
                                showTimeHr++;
                                showTimeHr = pad(showTimeHr,2);
                                showTimeMn = '00';
                            }
                            timeSlotCount++;

                            if(showTimeHr > prep2[0] || (showTimeHr == prep2[0] && showTimeMn > prep2[1])){
                                var cont = false;
                            }
                        }while (cont);
                    }

                    if(gwhRes2D[2] != 'none'){
                        $('#timeSelectDiv').append('<button class="btn shadow-none pb-3 pt-3" style="width:16.66666%; padding:0; background-color:palevioletred">Aus</button>');
                    
                        showTimeHr = prep3[0];
                        showTimeMn = prep3[1];

                        do {
                            var cont = true;
                            $('#timeSelectDiv').append( '<div id="timeDiv2O'+timeSlotCount+'" style="width:16.66666%;">'+
                                                            '<button id="timeBtn2O'+timeSlotCount+'"  class="btn shadow-none pb-3 pt-3" onclick="selectRezTime(\'2\',\''+timeSlotCount+'\')" style="width:100%; height:100%; padding:0;">'+showTimeHr+':'+showTimeMn+'</button>'+
                                                        '</div>');
                            if(showTimeMn == '00'){
                                showTimeMn = '30';
                            }else{
                                showTimeHr++;
                                showTimeHr = pad(showTimeHr,2);
                                showTimeMn = '00';
                            }
                            timeSlotCount++;

                            if(showTimeHr > prep4[0] || (showTimeHr == prep4[0] && showTimeMn > prep4[1])){
                                var cont = false;
                            }
                        }while (cont);
                    }
                }
                
                
			},
			error: (error) => { console.log(error); }
		});
    }

    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    } 

    function selectRezTime(timePlace, timeNr){
      
        if($('#timeSet1Nr').val() != 0 && $('#timeSet2Nr').val() != 0){
         
            // ri-fillohet periudha 
            $('#timeDiv'+ $('#timeSet1Place').val()+'O'+ $('#timeSet1Nr').val()).removeClass('time-SelectDiv-start');
            $('#timeBtn'+ $('#timeSet1Place').val()+'O'+ $('#timeSet1Nr').val()).removeClass('time-SelectBtn-start-end');
            $('#timeDiv'+ $('#timeSet2Place').val()+'O'+ $('#timeSet2Nr').val()).removeClass('time-SelectDiv-end');
            $('#timeBtn'+ $('#timeSet2Place').val()+'O'+ $('#timeSet2Nr').val()).removeClass('time-SelectBtn-start-end');
            var start = parseInt(parseInt($('#timeSet1Nr').val()) + parseInt(1));
            do {
                $('#timeDiv'+ $('#timeSet1Place').val()+'O'+start).removeClass('time-SelectDiv-continue');
                start++;
            }while(start < $('#timeSet2Nr').val());
            $('#timeBtn'+timePlace+'O'+timeNr).addClass('time-SelectBtn-start-end');
            $('#timeSet1Nr').val(timeNr);
            $('#timeSet1Place').val(timePlace);
            $('#timeSet1Val').val($('#timeBtn'+timePlace+'O'+timeNr).html());
            $('#timeSet2Nr').val(0);
            $('#timeSet2Place').val(0);
            $('#timeSet2Val').val(0);
        }else{
          
            if($('#timeSet1Nr').val() == 0){

                // selektohet e para
                $('#timeBtn'+timePlace+'O'+timeNr).addClass('time-SelectBtn-start-end');
                $('#timeSet1Nr').val(timeNr);
                $('#timeSet1Place').val(timePlace);
                $('#timeSet1Val').val($('#timeBtn'+timePlace+'O'+timeNr).html());
            }else{
                if(parseInt(timePlace) != parseInt($('#timeSet1Place').val())){

                    // e dyta eshte ne zone tjeter
                    $('#timeBtn'+ $('#timeSet1Place').val()+'O'+ $('#timeSet1Nr').val()).removeClass('time-SelectBtn-start-end');
                    $('#timeBtn'+timePlace+'O'+timeNr).addClass('time-SelectBtn-start-end');
                    $('#timeSet1Nr').val(timeNr);
                    $('#timeSet1Place').val(timePlace);
                    $('#timeSet1Val').val($('#timeBtn'+timePlace+'O'+timeNr).html());
                }else{
                    if(parseInt(timeNr) > parseInt($('#timeSet1Nr').val())){

                        // vlen per periudh
                        $('#timeBtn'+timePlace+'O'+timeNr).addClass('time-SelectBtn-start-end');
                        $('#timeSet2Nr').val(timeNr);
                        $('#timeSet2Place').val(timePlace);
                        $('#timeSet2Val').val($('#timeBtn'+timePlace+'O'+timeNr).html());

                        // Dizajni i selektimit
                        $('#timeDiv'+ $('#timeSet1Place').val()+'O'+ $('#timeSet1Nr').val()).addClass('time-SelectDiv-start');
                        var start = parseInt(parseInt($('#timeSet1Nr').val()) + parseInt(1));
                        do {
                            $('#timeDiv'+ $('#timeSet1Place').val()+'O'+start).addClass('time-SelectDiv-continue');
                            start++;
                        }while(start < $('#timeSet2Nr').val());
                        $('#timeDiv'+ $('#timeSet2Place').val()+'O'+ $('#timeSet2Nr').val()).addClass('time-SelectDiv-end');
                    }else{

                        // selektimi i dyte ma i vogel
                        $('#timeBtn'+ $('#timeSet1Place').val()+'O'+ $('#timeSet1Nr').val()).removeClass('time-SelectBtn-start-end');
                        $('#timeBtn'+timePlace+'O'+timeNr).addClass('time-SelectBtn-start-end');
                        $('#timeSet1Nr').val(timeNr);
                        $('#timeSet1Place').val(timePlace);
                        $('#timeSet1Val').val($('#timeBtn'+timePlace+'O'+timeNr).html());
                    }
                }
            }
        }
    }


    function sendPhoneNrForVer(){
        var pNr = $('#rezUsrPhoneNr').val().replace(/ /g,'');
        
        if(pNr != ''){
            if(pNr[0] == '+' && pNr[1] == 4 && (pNr[2] == 1 || pNr[2] == 9) && pNr[3] == 7 && pNr.length == 12){
                pNr = '0'+pNr.toString().slice(3);
            }else if(pNr[0] == 4 && (pNr[1] == 1 || pNr[1] == 9) && pNr[2] == 7 && pNr.length == 11){
                pNr = '0'+pNr.toString().slice(2);
            }else if(pNr[0] == 0 && pNr[1] == 0 && pNr[2] == 4 && (pNr[3] == 1 || pNr[3] == 9) && pNr[4] == 7 && pNr.length == 13){
                pNr = '0'+pNr.toString().slice(4);
            }
        }
        if(pNr == ''){
            if($('#rezRegErrorMsg06').is(':hidden')){ $('#rezRegErrorMsg06').show(100).delay(4500).hide(100); }
        }else if(pNr.length < 9 || pNr.length > 10){
            if($('#rezRegErrorMsg06').is(':hidden')){ $('#rezRegErrorMsg06').show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
				url: '{{ route("tableRez.getPhoneNrSendVerificationCode") }}',
				method: 'post',
				data: {
					phoneNr: pNr,
					_token: '{{csrf_token()}}'
				},
				success: (res) => {
                    if(res['status'] == 'success'){
                        $('#sentNrForConfDiv').hide(100);
                        $('#sentCodeForConfDiv').show(100);
                        $("#confCodeHashValue").val(res['hash']);
                        $("#confCodePhoneNr").val(res['phoneNr']);
                        if(pNr == '763270293' || pNr == '0763270293' || pNr == '763251809' || pNr == '0763251809' || pNr == '763459941' || pNr == '0763459941' || pNr == '763469963' || pNr == '0763469963' || pNr == '760000000' || pNr == '0760000000'){
                            $('#rezUsrConfPhoneNrCode').val(res['code']);
                        }
                    }else{
                        if($('#rezRegErrorMsg06').is(':hidden')){ $('#rezRegErrorMsg06').show(100).delay(4500).hide(100); }
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
    }

    function sendConfConfCodeForVer(){
        if($('#confCodeHashValue').val() == 0){
            if($('#rezRegErrorMsg07').is(':hidden')){ $('#rezRegErrorMsg07').show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
				url: '{{ route("tableRez.sendVerCodeForPhoneNr") }}',
				method: 'post',
				data: {
					codeConfHash: $('#confCodeHashValue').val(),
                    codeByClinet: $('#rezUsrConfPhoneNrCode').val(),
					_token: '{{csrf_token()}}'
				},
				success: (res) => {
                    if(res['status'] == 'success'){
                        $('#sentCodeForConfDiv').hide(100);
                        $('#confPhoneNrParag01').hide(100);
                        $('#rezSuccPhoneNrConf').show(100);
                        $('#rezSuccPhoneNrConf').html('Sie haben diese Telefonnummer (+'+$("#confCodePhoneNr").val()+') erfolgreich bestätigt!');
                        $('#phoneNrConfirmed').val( $('#confCodePhoneNr').val() );
                    }else if(res['status'] == 'failInvalidHash'){
                        // Invalid Hash
                        if($('#rezRegErrorMsg08').is(':hidden')){ $('#rezRegErrorMsg08').show(100).delay(4500).hide(100); }
                    }else if(res['status'] == 'failInvalidCode'){
                        // Invalid Code
                        if($('#rezRegErrorMsg09').is(':hidden')){ $('#rezRegErrorMsg09').show(100).delay(4500).hide(100); }
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
    }







    function saveReservation(resId){
        $('#saveReservationBtn').prop("disabled", true);
        if($('#rezPersonNrInp').val() == 0){

            // no number of persons 
            if($('#rezRegErrorMsg01').is(':hidden')){ $('#rezRegErrorMsg01').show(100); }
            $('*').animate({ scrollTop: $("#rezInpDiv01").offset().top }, 500);
            $('#saveReservationBtn').prop('disabled', false);
        }else if($('#rezDateSelectDt').val() == 'none'){

            // no date selected
            if($('#rezRegErrorMsg02').is(':hidden')){ $('#rezRegErrorMsg02').show(100); }
            $('*').animate({ scrollTop: $("#rezInpDiv02").offset().top }, 500);
            $('#saveReservationBtn').prop('disabled', false);
        }else if($('#timeSet1Val').val() == 0 || $('#timeSet2Val').val() == 0 ){

            // no time selected
            if($('#rezRegErrorMsg03').is(':hidden')){ $('#rezRegErrorMsg03').show(100).delay(4500).hide(100); }
            $('*').animate({ scrollTop: $("#timeSelectDiv").offset().top }, 500);
            $('#saveReservationBtn').prop('disabled', false);
        }else{
            var userEmail = $('#rezUsrEmail').val();
            var patternEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

            if(!patternEmail.test(userEmail)){

                // invalid email
                $('*').animate({ scrollTop: $("#rezInpDiv05").offset().top }, 500);
                if($('#rezRegErrorMsg04').is(':hidden')){ $('#rezRegErrorMsg04').show(100).delay(4500).hide(100); }
                $('#saveReservationBtn').prop('disabled', false);
            }else if($("#rezUsrLastname").val() == '' || $("#rezUsrName").val() == '' ){

                // name and last name not given
                $('*').animate({ scrollTop: $("#rezInpDiv05").offset().top }, 500);
                if($('#rezRegErrorMsg05').is(':hidden')){ $('#rezRegErrorMsg05').show(100).delay(4500).hide(100); }
                $('#saveReservationBtn').prop('disabled', false);
            }else if($('#phoneNrConfirmed').val() == 0){

                // phone nr nor verified
                $('*').animate({ scrollTop: $("#rezInpDiv05").offset().top }, 500);
                if($('#rezRegErrorMsg10').is(':hidden')){ $('#rezRegErrorMsg10').show(100).delay(4500).hide(100); }
                $('#saveReservationBtn').prop('disabled', false);
            }else{
                if($('#defaultCheck1').is(":checked")){ var qrorpaMarketingUse = 1; }else{ var qrorpaMarketingUse = 0; }
                if($('#defaultCheck2').is(":checked")){ var resMarketingUse = 1; }else{ var resMarketingUse = 0; }
                // if($('#rezRegErrorMsg11').is(':hidden')){ $('#rezRegErrorMsg11').show(100).delay(4500).hide(100); }

                $.ajax({
					url: '{{ route("tableRez.saveRezRequest") }}',
					method: 'post',
					data: {
                        res: resId,
                        dita: $('#rezDateSelectDt').val(),
                        koha1: $('#timeSet1Val').val(),
                        koha2: $('#timeSet2Val').val(),
                        persona: $('#rezPersonNrInp').val(),
                        emri: $('#rezUsrName').val(),
                        mbiemri: $('#rezUsrLastname').val(),
                        tel: $('#phoneNrConfirmed').val(),
                        email: $('#rezUsrEmail').val(),
                        koment: $('#rezComment').val(),
                        qroMar: qrorpaMarketingUse,
                        resMar: resMarketingUse,
						_token: '{{csrf_token()}}'
					},
					success: () => {
                        if($('#rezSuccRegistered').is(':hidden')){ $('#rezSuccRegistered').show(100).delay(12000).hide(100); }
                        $('*').animate({ scrollTop: $("#rezSuccRegistered").offset().top }, 500);
                        $("#rezInp").load(location.href+" #rezInp>*","");
                        $('#saveReservationBtn').prop('disabled', false);
					},
					error: (error) => { console.log(error); }
				});
                
            }
                
        }
    }
</script>























<script src="js/date-time-picker.min.js"></script>
<script>

    function dataAcc(tId){
        if($("#dataGetAccept"+tId).is(':checked'))
            $("#sendTabRezBtn"+tId).prop('disabled', false);  // checked
        else
            $("#sendTabRezBtn"+tId).prop('disabled', true);  // unchecked
    }




    function setTabRez(tableNr,tableCap,resId){
        // $('#tabRez_tabNr').html(tableNr);
        // $('#tabRezD_tableNr').val(tableNr);
        // $('#tabRez_tabCapacity').html(tableCap);
        // $('#tabRez_tabMax').val(tableCap);
        // $('#addRezImg').attr('src' , 'storage/TablePositions/Res'+resId+'Tab'+tableNr+'.png')
    }

    function resetModal(){
        $("#regRezForTable").load(location.href+" #regRezForTable>*","");
    }


</script>
