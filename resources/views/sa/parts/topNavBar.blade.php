<nav class="navbar navbar-expand navbar-light bg-white topbar static-top " id="DashNavbar">

  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>
      
  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">
    <div class="dropdown mr-4">		
    	<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        {{ __('Logout') }}
      </a>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>
  			
		</div>
  </ul>
</nav>

<script>
 	$('.dropdown').click(function(){
    $('.dropdown-menu').toggleClass('show');
  });



  var intervalId = window.setInterval(function(){
    $.ajax({
      url: '{{ route("notify.checkUnrespondetSA") }}',
      method: 'post',
      data: {
        resId: $('#theResAdminId').val(),
        _token: '{{csrf_token()}}'
      },
      success: (res) => {

        if(res != 'empty'){
          var pageURL = $(location).attr("href");
          var pageURLEnd = pageURL.split('/')[3];
          var arrNotifyAdm = res.split('-8-');
          $.each( arrNotifyAdm, function( index, value ) {
            var res2D = value.split('||');
            if(res2D[0] == 'newMsgFromAdmins'){

              if(pageURLEnd == 'SaAdminMSG'){
                newMessageFromQRorpa(res2D[1], res2D[2], res2D[3]);
                $.ajax({
                  url: '{{ route("atsMsg.SAIReadTheMsg") }}',
                  method: 'post',
                  data: {
                    msgId: res2D[4],
                    _token: '{{csrf_token()}}'
                  },
                  success: () => {},
                  error: (error) => {console.log(error);}
                });
              }else{
                $("#atsMsgOpenPageSuperadminPanel").load(location.href+" #atsMsgOpenPageSuperadminPanel>*","");
                // $("#resExtraServices").load(location.href+" #resExtraServices>*","");
              }

            }else if(value == 'newMsgFromAdminsAddAV' || value == 'newMsgFromAdminsDelAV'){
              if(pageURLEnd == 'SaAdminMSG'){
                location.reload();
              }
            }else if(res2D[0] == 'adminMsgReadForSA'){
              if(pageURLEnd == 'SaAdminMSG'){
                $("#messagesDiv"+res2D[1]).load(location.href+" #messagesDiv"+res2D[1]+">*","");
              }
            }
          });
        }

      },error: (error) => { console.log(error);}
    }); //End AJAX form 
  }, 2000);






  function newMessageFromQRorpa(avID, theMsg, msgCreated){

    var respoTime = msgCreated.split(' ')[1];
    var respoTime2D = respoTime.split(':');

    var respoDate = msgCreated.split(' ')[0];
    var respoDate2D = respoDate.split('-');

    var newMsg =  '<div class="p-1 mb-2 leftMsg" style="width:70%; height:fit-content; margin-right:30%; color:white; background-color:rgba(32,44,51,255);">'+
                    '<p class="mb-1">'+theMsg+'</p>'+
                    '<div class="d-flex">'+
                      '<p class="mb-1" style="color:rgba(255, 255, 255, 0.45); width:45%;">'+respoTime2D[0]+':'+respoTime2D[1]+':'+respoTime2D[2]+'</p>'+
                      '<p class="mb-1 text-center" style="color:rgba(255, 255, 255, 0.45); width:40%;">'+respoDate2D[2]+'/'+respoDate2D[1]+'</p>'+
                      '<i style="width:15%; color:rgb(39,190,175);" class="fas text-center pt-1 fa-check-double"></i> <!-- read -->'+
                    '</div>'+
                  '</div>';

    $("#messagesDiv"+avID).append(newMsg);
    $('#messageInputFor'+avID).val('');
    objDiv = document.getElementById("messagesDiv"+avID);
    objDiv.scrollTop = objDiv.scrollHeight;
  }
</script>

        