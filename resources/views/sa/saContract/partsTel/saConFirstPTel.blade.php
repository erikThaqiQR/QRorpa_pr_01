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

    input[type="color"],
    input[type="date"],
    input[type="datetime"],
    input[type="datetime-local"],
    input[type="email"],
    input[type="month"],
    input[type="number"],
    input[type="password"],
    input[type="search"],
    input[type="tel"],
    input[type="text"],
    input[type="time"],
    input[type="url"],
    input[type="week"],
    select:focus,
    textarea {
        font-size: 16px;
    }
</style>

<div style="background-color: rgb(39,190,175);" class="p-3">
    <div class="d-flex justify-content-between">
        <p style="color: white; font-size:22px; width:50%; margin:0px;" class="pl-2"><strong>Vertrag</strong></p>
        
        <button style="width: 50%; color:white" class="btn btn-outline-light text-center shadow-none" data-toggle="modal" data-target="#addANewContractModal">
            <strong> <i class="fas fa-folder-plus"></i> Neu hinzufügen </strong>
        </button>
    </div>
    <hr>

    <div class="d-flex flex-wrap justify-content-between">
        @foreach(contractRes::where('fromConMng',Auth::User()->id)->get()->sortByDesc('created_at') as $oneCon)
            @php
                $Date2D = explode('-',explode(' ',$oneCon->created_at)[0])
            @endphp
            <div id="conSow{{$oneCon->id}}" style="width: 100%;" class="card d-flex flex-wrap justify-content-between clickable mb-1 mt-1">
                <div style="width: 100%"; class="card-header d-flex flex-wrap justify-content-between">
                    <span class="text-center" style="width: 100%;">{{$Date2D[2]}}/{{$Date2D[1]}}/{{$Date2D[0]}}</span>
                    <span class="text-center" style="width: 49.5%;">
                    @if($oneCon->gender == 'Male')
                        <i class="fas fa-male"></i>
                    @elseif ($oneCon->gender == 'Female')
                        <i class="fas fa-female"></i>
                    @endif
                    <strong>{{$oneCon->name}} {{$oneCon->lastname}} </span> <span class="text-center" style="width: 49.5%;">{{$oneCon->company}}</span></strong>
                </div>
                <ul style="width: 100%;" class="list-group list-group-flush">
                    <li class="list-group-item"><i class="fas fa-phone"></i> {{$oneCon->phoneNr}}</li>
                    <li class="list-group-item"><i class="fas fa-at"></i> {{$oneCon->email}} </li>
                    <li class="list-group-item"><span class="mr-2">{{$oneCon->street}}</span> {{$oneCon->plz}} / {{$oneCon->ort}}</li>
                    <li class="list-group-item d-flex justify-content-between"> 
                        @if($oneCon->conStatus == 0)
                            <button id="chConPayStatBtn" style="width:33%" onclick="changeContratPaySt('{{$oneCon->id}}','1')" class="btn btn-danger"><i class="fas fa-times"></i></button>
                        @else
                            <button id="chConPayStatBtn" style="width:33%" onclick="changeContratPaySt('{{$oneCon->id}}','0')" class="btn btn-success"><i class="fas fa-check"></i></button>
                        @endif
                        <div style="width: 33%;">
                            <form method="POST" action="{{ route('saContracts.getPDFcontract') }}" target="_blank">
                                {{ csrf_field()}}
                                <input type="hidden" value="{{$oneCon->id}}" name="conId">
                                <button style="width:100%" class="btn"><i style="color:rgb(39,190,175);" class="fas fa-2x fa-print"></i></button>
                            </form>
                        </div>
                        <button id="sendTheContractEmailBtn{{$oneCon->id}}" onclick="sendTheContractEmailTel('{{$oneCon->id}}')" style="width:33%" class="btn">
                            <i style="color:rgb(39,190,175);" class="fas fa-2x fa-envelope"></i>
                        </button>
                    </li>
                </ul>
                <div class="card-footer text-center">
                    <strong>{{$oneCon->totalPerMonth}} CHF/ month <span class="ml-3">({{$oneCon->VertragsaufzeitYear}} y) {{$oneCon->total}} CHF</span></strong>
                </div>
                

                
            </div>
            <hr style="width:100%; padding:0px; margin:5px 0px 5px 0px;">
        @endforeach

        
    </div>


</div>

@include('sa.saContract.partsTel.saConAddContractModalTel')

@include('sa.saContract.partsTel.saConJSFunctionsTel')