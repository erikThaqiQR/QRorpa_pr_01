<nav class="navbar navbar-expand navbar-light bg-white topbar static-top " id="DashNavbar">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

         

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

         	<div class="dropdown mr-4">
  			
    				<a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
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
 	
</script>

        