@extends('layouts.appSAPanel')

@section('extra-css')
    <style>
        .direktiveBox{
            border:1px solid gray;
            color:black;
            border-radius:10px;
            margin-top:35px;
            padding-top:50px;
            padding-bottom:50px;
        }
    </style>
@endsection()

@section('content')
<!-- <div class="container">
    <div id="app">
        <produktshow></produktshow>
    </div>
</div> -->

        <div class="col-12 BRef">
             <a href="/" class="btn btn-outline-default qrorpaColor">Restaurant</a><span>/</span>
        </div>

 
        @include('inc.messages')

<div class="container">
    <div class="row ">
        <div class="col-1">
        </div>

        <div class="col-4 text-center direktiveBox KatBox">
            <a href="{{ route('kategorite.index') }}" style="color:black;" class="stretched-link">
                <h4 class="qrorpaColor">Kategoritë</h4>
            </a>
        </div>

        <div class="col-2">
        </div>

        <div class="col-4 text-center direktiveBox KatBox">
            <a href="{{ route('ekstras.index') }}" style="color:black;" class="stretched-link">
                <h4 class="qrorpaColor">Ekstra's</h4>
            </a>
        </div>

        <div class="col-1">
        </div>
        
        
    </div>

    <div class="row">
        <div class="col-1">
        </div>

        <div class="col-4 text-center direktiveBox KatBox">
            <a href="{{ route('llojetPro.index') }}" style="color:black;"  class="stretched-link">
                <h4 class="qrorpaColor">Llojet/madhsit</h4>
            </a>
        </div>

        <div class="col-2">
        </div>

        <div class="col-4 text-center direktiveBox KatBox">
            <a href="{{ route('produktet.index') }}" style="color:black;"  class="stretched-link">
                <h4 class="qrorpaColor">Produktet</h4>
            </a>
        </div>

        <div class="col-1">
        </div>
    </div>
</div>

<br><br>
@include('inc.footer')



@endsection