@extends('layouts.appMaster')

@section('content')
                <div id="allMenu">
                    @include('inc.services')
                </div>
                @include('inc.footer')

                @if(!isset($_GET['barber'])))
                    <script>
                        $(document).ready(function(){
                            $('#allMenu').hide();
                        });
                    </script>
                @endif

                
@endsection



