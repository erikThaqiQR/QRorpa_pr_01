<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
@if(isset($_SESSION['Res']))
    @if(isset($_SESSION['t']) && $_SESSION['t'] == 500)
        @include('CartRestaurantTakeaway')
    @elseif(isset($_SESSION['t']))
        @include('CartRestaurant')
    @else
        @include('CartRestaurantDelivery')
    @endif

@elseif(isset($_SESSION['Bar']))
    @include('CartBarbershop')
@endif