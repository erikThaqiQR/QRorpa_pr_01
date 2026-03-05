@extends('layouts.appPro')

@section('content')
<!-- <div class="container">
    <div id="app">
        <produkt></produkt>
    </div>
</div> -->

<div class="container">

    @include('inc.messages')
    <div class="row">
        <div class="col-12">
            <h3>Register a new product</h3>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
        {{Form::open(['action' => 'ProduktController@store', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
                        
        <div class="form-group">
            {{ Form::label('Emri', null, ['class' => 'control-label']) }}
            {{ Form::text('emri','', ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Pershkrimi', null , ['class' => 'control-label']) }}
            {{ Form::textarea('pershkrimi','', ['class' => 'form-control', 'rows'=>'3']) }}
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    {{ Form::label('Qmimi', null , ['class' => 'control-label']) }}
                    {{ Form::number('qmimi','', ['class' => 'form-control', 'step'=>'0.01']) }}
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    {{ Form::label('Foto', null , ['class' => 'control-label']) }}
                    {{ Form::file('foto', ['class' => 'form-control']) }}
                </div>
            </div>
        </div>

        <div class="form-group">
    
            {{ Form::submit('Save', ['class' => 'form-control btn btn-primary']) }}
        </div>
    
        {{Form::close() }}
        </div>
    </div>
</div>





@endsection