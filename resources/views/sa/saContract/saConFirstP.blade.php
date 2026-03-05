<?php
    use App\contractRes;
 
?>
<style>
    input:focus {
        outline:none;
    }

    .serviceBtn{
        color:rgb(72,81,87);
        border: 1px solid rgb(72,81,87);
    }
  
    .serviceBtnSelected{
        color:white;
        background-color:rgb(39,190,175);
        border: 1px solid rgb(39,190,175);
    }

    .serviceBtn02{
        background-color: rgb(72,81,87);  
        border: 1px solid rgb(72,81,87);
        color: white;
        font-weight: bold;
    }
    .serviceBtn02Selected{
        background-color: rgb(39,190,175);  
        border: 1px solid rgb(39,190,175);
        color: white;
        font-weight: bold;
    }


    .contractListingTop{
        background-color:rgb(72,81,87);
        color:white;
        border-radius: 4px;
    }

    .clickable:hover{
        cursor: pointer;
    }
</style>

<div class="p-3">
    <div class="d-flex justify-content-between">
        <p style="color: rgb(39,190,175); font-size:26px; width:50%; margin:0px;" class="pl-2"><strong>Vertrag</strong></p>
        
        <button style="width: 50%;" class="btn btn-outline-dark text-center shadow-none" data-toggle="modal" data-target="#addANewContractModal">
            <strong> <i class="fas fa-folder-plus"></i> Neu hinzufügen </strong>
        </button>
    </div>
    <hr>

    <div class="d-flex flex-wrap justify-content-between">
        <p style="width:10%; " class="contractListingTop p-2 text-center">
            Date
        </p>
        <p style="width:15%; " class="contractListingTop p-2 text-center">
            Name lastname
        </p>
        <p style="width:21%; " class="contractListingTop p-2 text-center">
            Street / PLZ / ORT
        </p>
        <p style="width:10%; " class="contractListingTop p-2 text-center">
            Company
        </p>
        <p style="width:20%; " class="contractListingTop p-2 text-center">
            Email / Tel 
        </p>
        <p style="width:15%; " class="contractListingTop p-2 text-center">
            PerMonth / Total 
        </p>
        <p style="width:4%; " class="contractListingTop p-2 text-center">
            <i class="fas fa-money-bill-wave"></i>
        </p>
        <p style="width:4%; " class="contractListingTop p-2 text-center">
        </p>

        @foreach(contractRes::where('fromConMng',Auth::User()->id)->get()->sortByDesc('created_at') as $oneCon)
            @php
                $Date2D = explode('-',explode(' ',$oneCon->created_at)[0])
            @endphp
            <div id="conSow{{$oneCon->id}}" style="width: 100%;" class="d-flex flex-wrap justify-content-between clickable">
                <p style="width:10%; " class="p-1 text-center">
                    {{$Date2D[2]}}/{{$Date2D[1]}}/{{$Date2D[0]}} 
                </p>
                <p style="width:15%; " class="p-1 text-center">
                    {{$oneCon->gender}} <br> {{$oneCon->name}} {{$oneCon->lastname}}
                </p>
                <p style="width:21%; " class="p-1 text-center">
                    {{$oneCon->street}} <br> {{$oneCon->plz}} / {{$oneCon->ort}}
                </p>
                <p style="width:10%; " class="p-1 text-center">
                    {{$oneCon->company}}
                </p>
                <p style="width:20%; " class="p-1 text-center">
                    {{$oneCon->email}} <br> {{$oneCon->phoneNr}}
                </p>
                <p style="width:15%; " class="p-1 text-center">
                    {{$oneCon->totalPerMonth}} CHF/ month <br> ({{$oneCon->VertragsaufzeitYear}} y) {{$oneCon->total}} CHF
                </p>
                @if($oneCon->conStatus == 0)
                    <button id="chConPayStatBtn" style="width:4%" onclick="changeContratPaySt('{{$oneCon->id}}','1')" class="btn btn-danger"><i class="fas fa-times"></i></button>
                @else
                    <button id="chConPayStatBtn" style="width:4%" onclick="changeContratPaySt('{{$oneCon->id}}','0')" class="btn btn-success"><i class="fas fa-check"></i></button>
                @endif
                <div style="width: 4%;">
                <form method="POST" action="{{ route('saContracts.getPDFcontract') }}" target="_blank">
                    {{ csrf_field()}}
                    <input type="hidden" value="{{$oneCon->id}}" name="conId">
                    <button style="width:100%" class="btn"><i style="color:rgb(39,190,175);" class="fas fa-print"></i></button>
                </form>

                <button id="sendTheContractEmailBtn{{$oneCon->id}}" onclick="sendTheContractEmail('{{$oneCon->id}}')" style="width:100%" class="btn">
                    <i style="color:rgb(39,190,175);" class="fas fa-envelope"></i>
                </button>

                </div>
                
            </div>
            <hr style="width:100%; padding:0px; margin:1px 0px 1px 0px;">
        @endforeach

        
    </div>


</div>

@include('sa.saContract.saConAddContractModal')

@include('sa.saContract.saConJSFunctions')