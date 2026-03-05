<?php
    use App\Restorant;
?>
<div class="modal show" id="detPassChangeModal" aria-modal="true" role="dialog" style="display: block; background-color:rgb(39,190,175);" 
      data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border:2px solid rgb(39,190,175); border-radius:20px;">
                <div class="modal-body text-center">
                    <img src="storage/images/logo_QRorpa.png" style="width:180px; height:auto;"  alt="logo_qrorpa">
                    <p style="font-size: 27px;"><strong>{{__('adminP.WelcomeToQRorpa')}}</strong></p>

                    <p style="color:rgb(72,81,87);"><strong><i style="color:red;" class="fas fa-exclamation-triangle"></i> 
                        {{__('adminP.chngPassTxt01')}} <span style="color:rgb(39,190,175);">{{Restorant::findOrFail(Auth::User()->sFor)->emri}}</span> {{__('adminP.chngPassTxt02')}}</strong>
                    </p>
                    <hr>

                    <div class="input-group mb-2">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                        <input onkeyup="testPassChng(this.value)" id="theNewPass1" type="password" class="form-control" placeholder="Passwort" aria-describedby="basic-addon1" autocomplete="off">
                        <button onclick="seePassOne()" style="margin: 0px;" class="btn btn-outline-secondary" type="button" id="button_addon1"><i class="fas fa-eye"></i></button>
                    </div>
                    <div class="input-group ">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                        <input id="theNewPass2" type="password" class="form-control" placeholder="Passwort Bestätigung" aria-describedby="basic-addon1" autocomplete="off">
                        <button onclick="seePassTwo()" style="margin: 0px;" class="btn btn-outline-secondary" type="button" id="button_addon2"><i class="fas fa-eye"></i></button>
                    </div>

                    <div id="psChngComplexity" class="d-flex justify-content-start mt-2">
                    </div>

                    <button onclick="SaveNewPassForAdm('{{Auth::User()->id}}')" class="btn btn-block btn-success mt-4" style="margin: 0px;">Spare <i class="fas fa-save"></i></button>

                    <div id="passChng_error01" class="mt-1 text-center alert alert-danger" style="display:none;">
                        <strong>{{__('adminP.chngPassTxt03')}}</strong>
                    </div>
                    <div id="passChng_error02" class="mt-1 text-center alert alert-danger" style="display:none;">
                        <strong>{{__('adminP.chngPassTxt04')}}</strong>
                    </div>
                    <hr>
                    <p style="color:red;"><strong>{{__('adminP.chngPassTxt05')}}</strong></p>
                </div>
            </div>
        </div>
    </div>
    <script>
      function testPassChng(ps){
        $('#psChngComplexity').html('');
        if(ps.length >= 6){
          $('#psChngComplexity').append('<span style="width: 19.6%; margin-right:0.5%; color:white; background-color:#ED2938;" class="p-2 badge"><strong>Sehr niedrig</strong></span>');
          var strength = 0;
          var arr = [/.{5,}/, /[a-z]+/, /[0-9]+/, /[A-Z]+/];
          jQuery.map(arr, function(regexp) {
            if(ps.match(regexp))
              strength++;
          });
          if(strength >= 1){
            $('#psChngComplexity').append('<span style="width: 19.6%; margin-right:0.5%; color:white; background-color:#B25F4A;" class="p-2 badge"><strong>Niedrig</strong></span>');
          }
          if(strength >= 2 && ps.length >= 8){
            $('#psChngComplexity').append('<span style="width: 19.6%; margin-right:0.5%; color:white; background-color:#77945C;" class="p-2 badge"><strong>Normal</strong></span>');
          }
          if(strength >= 3 && ps.length >= 10){
            $('#psChngComplexity').append('<span style="width: 19.6%; margin-right:0.5%; color:white; background-color:#3BCA6D;" class="p-2 badge"><strong>Hoch</strong></span>');
          }
          if(strength >= 4 && ps.length >= 12){
            $('#psChngComplexity').append('<span style="width: 19.6%; color:white; background-color:#00FF7F;" class="p-2 badge"><strong>Sehr hoch</strong></span>');
          }
        }
      }

      function seePassOne(){
        $('#theNewPass1').attr('type','text');
        $('#button_addon1').attr('onclick','hidePassOne()');
        $('#button_addon1').html('<i class="fas fa-eye-slash"></i>');
      }
      function hidePassOne(){
        $('#theNewPass1').attr('type','password');
        $('#button_addon1').attr('onclick','seePassOne()');
        $('#button_addon1').html('<i class="fas fa-eye"></i>');
      }

      function seePassTwo(){
        $('#theNewPass2').attr('type','text');
        $('#button_addon2').attr('onclick','hidePassTwo()');
        $('#button_addon2').html('<i class="fas fa-eye-slash"></i>');
      }
      function hidePassTwo(){
        $('#theNewPass2').attr('type','password');
        $('#button_addon2').attr('onclick','seePassTwo()');
        $('#button_addon2').html('<i class="fas fa-eye"></i>');
      }




      function SaveNewPassForAdm(uId){
        if(!$('#theNewPass1').val() || !$('#theNewPass2').val()){
          if($('#passChng_error01').is(":hidden")){
            $('#passChng_error01').show(50).delay(4500).hide(50);
          }
        }else if($('#theNewPass1').val() !== $('#theNewPass2').val()){
          if($('#passChng_error02').is(":hidden")){
            $('#passChng_error02').show(50).delay(4500).hide(50);
          }
        }else{
            $.ajax({
		    	url: '{{ route("admPass.admPassChngDt") }}',
		    	method: 'post',
		    	data: {
		    		passw: $('#theNewPass1').val(),
		    		usr: uId,
		    		_token: '{{csrf_token()}}'
		    	},
		    	success: () => {
                    location.reload();
                },
		    	error: (error) => { console.log(error); }
		    });
        }
      }
    </script>