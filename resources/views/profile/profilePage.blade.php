@extends('layouts.appOrders')

@section('content')

    @if(!Auth::check())
    <?php
        header("Location: ".route('login'));
        exit();
    ?>
    @endif
    <?php
          use Jenssegers\Agent\Agent;
          $agent = new Agent();
    ?>

    @if($agent->isMobile())
        @include('profile.profileTel.profileIndex')
    @else 
        @include('profile.profileDesktop.profileIndex')
    @endif




<script>
    if(screen.width <= 580){
        $('#profileTel').show();
    }else{
      $('#profileDesktop').show();
      
    }
  </script>
@endsection