<style>
    .logoutBtnsaCon{
        color: white;
        font-weight: bold;
        font-size: large;

    }
    .logoutBtnsaCon:hover{
        color: red;
    }

</style>

<nav class="navbar justify-content-between pr-4 pl-4" style="background-color: rgb(39,190,175);">
    <img src="storage/images/logo_QRorpa_wh.png" style="width:175px;" alt="">

    <a class="form-inline logoutBtnsaCon" href="{{ route('logout') }}" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"> {{ __('Logout') }} <i class=" ml-3 fas fa-lg fa-sign-out-alt"></i>
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</nav>