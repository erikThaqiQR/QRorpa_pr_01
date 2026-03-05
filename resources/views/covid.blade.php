@extends('layouts.appOrders')


<?php
    use App\Restorant;
?>


<style>
    .noBorderBtn:focus{
      
        outline:none;
        box-shadow:none !important;
    }

select {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
border:none;
}

body { font-size: 16px; }
input, select, { font-size: 100%; }



</style>

@section('content')

<?php
    session_start();
?>
<style>
    #succTrack:hover{
        color:black;
        text-decoration:none;
    }
    #succTrack{
        color:black;
        text-decoration:none;
        font-size:18px;
    }
    .rating-area{
        margin-top:10px;
        text-align: left;
    }
    label.control-label{
        font-size: 1rem;
    }
    input.form-control.btn.btn-primary{
        width: 30%;
        background: #27beae;
        color: #fff;
        border: none;
    }
    
</style>
<div class="container mt-3">

    <div class="row">
        <div class="col-lg-4 col-sm-0">
        </div>
        <div class="col-lg-4 col-sm-12 bg-light text-center p-3" style="border-radius:40px;">
           
            <h3 class="color-text text-center"><strong>Covid-19 Kontaktformular</strong></h3>

            @if(session('errorMsg'))
                <div class="alert alert-danger text-center mt-1 mb-1">
                    {{session('errorMsg')}}
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success text-center mt-1 mb-1">
                    {{session('success')}}
                </div>
            @endif

		    <div class="form-group">
		    	{{Form::open(['action' => 'CovidReportController@store', 'method' => 'post']) }}

                    {{ Form::label('Name', null, ['class' => 'control-label']) }}
                    {!! Form::text('name','', ['class' => 'form-control', 'required']) !!}

                    {{ Form::label('Vorname', null, ['class' => 'control-label']) }}
                    {!! Form::text('vorname','', ['class' => 'form-control', 'required']) !!}

                    {{ Form::label('Adresse', null, ['class' => 'control-label']) }}
                    {!! Form::text('address','', ['class' => 'form-control', 'required']) !!}

                    <div class="d-flex flex-wrap justify-content-between">
                        {{ Form::label('PLZ', null, ['class' => 'control-label', 'style'=>'width:30%;']) }}
                        {{ Form::label('ORT', null, ['class' => 'control-label', 'style'=>'width:69%;']) }}

                        {!! Form::number('plz','', ['class' => 'form-control', 'minlength' => '4', 'maxlength' => '4', 'style'=>'width:30%;', 'required']) !!}
                        {!! Form::text('ort','', ['class' => 'form-control', 'style'=>'width:69%;', 'required']) !!}
                    </div>

                    {{ Form::label('Telefonnummer', null, ['class' => 'control-label']) }}
                    {!! Form::text('tel','', ['class' => 'form-control', 'minlength' => '9', 'maxlength' => '10', 'required']) !!}

                    {{ Form::label('Anzahl Personen', null, ['class' => 'control-label']) }}
                    {!! Form::number('persons','', ['class' => 'form-control', 'min'=>'1', 'step'=>'1', 'required']) !!}
                   
                    {!! Form::hidden('restaurant_id',$value = Restorant::find($_SESSION["Res"])->id, ['class' => 'form-control']) !!}
                    {{--   {{ Form::text('comment','', ['class' => 'form-control', 'required']) }} --}}
           

                        <div class="form-check mt-1 mb-1" onclick="dataAccCovid19()">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" value="" id="dataGetAcceptCovid19">Ich habe die 
                                    <a href="{{route('firstPage.datenschutz')}}">Datenschutzbestimmungen</a> zur Kenntnis genommen*
                            </label>
                        </div>

                        <div class="form-group">
                            {{ Form::submit('Senden', ['class' => 'form-control btn btn-primary btn-block', 'style' => 'width:100%;', 'id' => 'sendTabRezReqCovidBtn' ,'disabled']) }}
                        </div>

                        <!-- Return tto the menu -->
                        @if(isset($_SESSION['Res']) && isset($_SESSION['t']))
                            <a href="/?Res={{$_SESSION['Res']}}&t={{$_SESSION['t']}}" class="btn btn-outline-info btn-block"
                            style="padding:10px; margin-top:20px;"> <strong>Bestellung fortsetzen</strong>  </a>
                        @else
                            <a href="{{url('/')}}" class="btn btn-outline-info btn-block" style="padding:10px; margin-top:20px;"> <strong>Bestellung fortsetzen</strong>  </a>
                        @endif

                {{Form::close()}}
            </div>
        </div>
        <div class="col-lg-4 col-sm-0">
        </div>
    </div>
</div>

 







@endsection


<script>
    function dataAccCovid19(){
        if($("#dataGetAcceptCovid19").is(':checked'))
            $("#sendTabRezReqCovidBtn").prop('disabled', false);  // checked
        else
            $("#sendTabRezReqCovidBtn").prop('disabled', true);  // unchecked
    }
</script>













@section('extra-js')
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>


@endsection