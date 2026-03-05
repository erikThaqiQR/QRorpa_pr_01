@extends('layouts.appSAPanel')


@section('extra-css')
   
@endsection

@section('content') 

        <div class="col-12 BRef">
             <a href="/" class="btn btn-outline-default qrorpaColor">Restaurant</a><span>/</span>
             <a href="/produktet1" class="btn btn-outline-default qrorpaColor">Menaxhimi i produkteve</a><span>/</span>
        </div>

    @include('inc.messages')

@endsection