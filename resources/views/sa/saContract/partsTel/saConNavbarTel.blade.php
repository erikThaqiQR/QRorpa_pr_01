<style>
    .logoutBtnsaCon{
        color: red;
        font-weight: bold;
        font-size: large;

    }

</style>

<nav class="navbar justify-content-between pr-4 pl-4" style="background-color: rgb(39,190,175);">
    <img src="storage/images/logo_QRorpa_wh.png" style="width:125px;" alt="">

    <!-- Button trigger modal -->
    <button type="button" class="btn form-inline" data-toggle="modal" data-target="#conServicesModal">
        <i style="color:white;" class="fas fa-2x fa-grip-lines"></i>
    </button>
</nav>




<!-- Modal -->
<div class="modal fade" id="conServicesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><strong>Qrorpa Contract</strong></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
            </button>
        </div>
        <div class="modal-body">

            <a class="btn btn-block btn-light pl-3 text-left" style="font-size: large;" href="{{ route('saContracts.index')}}">
                <strong>Contracts</strong>
            </a>

            <hr>

            <a class="logoutBtnsaCon btn btn-light btn-block pr-3 text-right" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"> {{ __('Logout') }} <i class=" ml-3 fas fa-lg fa-sign-out-alt"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        
        </div>
    </div>
</div>