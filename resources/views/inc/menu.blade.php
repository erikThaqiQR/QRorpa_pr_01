@if(isset($_GET['t']) && isset($_GET['Res']))
    @if($_GET['t'] != 500)
        @include('inc.menuNormal')
    @elseif($_GET['t'] == 500)
         @include('inc.menuTakeaway')
    @endif
@elseif(isset($_GET['Bar']))
    @include('inc.menuBarbershop')
@endif

