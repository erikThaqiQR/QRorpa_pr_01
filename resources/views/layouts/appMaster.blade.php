@if(isset($_GET['Res']))
    @include('layouts.appMasterRes')
@elseif(isset($_GET['Bar']))
    @include('layouts.appMasterBar')
@else
    @include('layouts.appMasterRes')
@endif
