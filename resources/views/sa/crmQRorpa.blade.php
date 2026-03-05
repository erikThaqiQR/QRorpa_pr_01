<?php
    if(Auth::check() && !(Auth::user()->email == 'Briefmarketing@qrorpa.ch' || Auth::user()->email == 'mg_ResData@qrorpa.ch' || Auth::user()->email == 'callagent@qrorpa.ch' || Auth::user()->email == 'callagent01@qrorpa.ch' || Auth::user()->email == 'callagent02@qrorpa.ch' 
        || Auth::user()->email == 'callagent03@qrorpa.ch' || Auth::user()->email == 'callagent04@qrorpa.ch' || Auth::user()->email == 'callagent05@qrorpa.ch' || Auth::user()->email == 'callagent06@qrorpa.ch'
        || Auth::user()->email == 'callagent07@qrorpa.ch' || Auth::user()->email == 'callagent08@qrorpa.ch' || Auth::user()->email == 'callagent09@qrorpa.ch' || Auth::user()->email == 'callagent10@qrorpa.ch')){
        header("Location: ".route('home'));
        exit();
    }

    use App\Events\ActiveAdminPanel;
    use App\Restorant;
    use App\resdemoalfa;
    use Illuminate\Support\Facades\Input;
    use Illuminate\Support\Facades\DB;
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width">
  <meta name="description" content="">
  <meta name="author" content="">

  <title> Dashboard</title>

 
  <script
  src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
  integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs="
  crossorigin="anonymous"></script>

  <!-- Custom fonts for this template-->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">



  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


  <script src="{{ asset('js/app.js') }}" defer></script>

   <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
   @include('fontawesome')

</head>

<body>




<style>
    #searchDemo{
        background-image: url('storage/icons/search.png'); /* Add a search icon to input */
        background-size: 30px 30px;
        background-position: 10px 10px; /* Position the search icon */
        background-repeat: no-repeat; /* Do not repeat the icon image */
        font-size: 16px; /* Increase font-size */
        border: 1px solid #000; /* Add a grey border */
        opacity:0.45;
        border-radius:20px;
    }


    .deleteResDemoBtn:hover{
        cursor:pointer;
        color:black;
    }
    .deleteResDemoBtn{
        color:rgb(39,190,175);
    }

    .selectiveResDemo:hover{
        cursor:pointer;
    }



    button:focus {outline:0 !important;}
    .button:focus {outline:0 !important;}
    .button{outline:0 !important;}

    textarea:focus, input:focus{
        outline: none;
    }
</style>

<!-- The Modal -->
<div class="modal" id="importModal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal body -->
      <div class="modal-body">
      {{Form::open(['action' => 'ResDemoController@store', 'method' => 'post']) }}

                                <div class="form-group">
                                    <label for="kat">Selektoni a model reastaurant</label>
                                    <select name="modelRes" class="form-control">
                                        <option value="0">Select...</option>
                                        <?php

                                            foreach(Restorant::all() as $res){
                                            
                                                echo '  <option value="'.$res->id.'">'.$res->emri.'</option>';
                                                
                                            }
                                        ?>  
                                            
                                    </select>
                                </div>

        <div class="form-group">
            {{ Form::submit('Start importing...', ['class' => 'form-control btn btn-primary']) }}
        </div>

        {{Form::close() }}
      </div>

    </div>
  </div>
</div>



<div class="text-center">
    <a style="font-size:25px;" class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                       <strong>{{ __('Logout') }}</strong> 
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>








<div class="container-fluid mb-3" style="margin-top:20px; min-width:1200px;">
    <div class="row" style="width:100%;">
        @if(session('success'))
            <div class="col-12">
                <div class="alert alert-success">
                    <strong>{{session('success')}}</strong>.
                </div>
            </div>
        @endif

        <div class="col-12 mt-3 mb-4 d-flex justify-content-between">
            <input style="width:70%;" class="pl-5 p-2" type="text" id="searchDemoCRMInput" onkeyup="setNewSearchWord(this.value)" placeholder="Search a restaurant...">
            {{Form::open(['action' => 'ResDemoController@indexCRM2', 'method' => 'get', 'style'=>'width:25%;']) }}
                {{ Form::submit('Search', ['class' => 'btn btn-outline-primary', 'style' => 'width:100%;']) }}
                {{ Form::hidden('searchWord','', ['class' => 'form-control', 'id'=>'sendSearchCRM']) }}
            {{Form::close() }}
        </div>

        <script>
            function setNewSearchWord(swVal){
                $('#sendSearchCRM').val(swVal);
            }
        </script>

        @if(Auth::user()->email == "Briefmarketing@qrorpa.ch" || Auth::user()->email == 'mg_ResData@qrorpa.ch')
        <div class="col-10">
            <button class="btn btn-block btn-outline-dark" data-toggle="modal" data-target="#importModal" disabled>
                import the restaurants!
            </button>
        </div>
        <div class="col-2">
            {{Form::open(['action' => 'ResDemoController@destroyAll', 'method' => 'post']) }}
                {{Form::hidden('_method', 'DELETE')}}
                {{csrf_field()}}
                <div class="form-group">
                    {{ Form::submit('Delete all', ['class' => 'form-control btn btn-danger' , 'disabled']) }}
                </div>

            {{Form::close() }}

        </div>
        @endif



            @if(Auth::user()->email == 'Briefmarketing@qrorpa.ch' || Auth::user()->email == 'mg_ResData@qrorpa.ch')
                <button id="saveChanges" onclick="setForDownload()" style="position:fixed; bottom:25px; z-index:9999; left:10%; width:200px;" class="btn p-3 btn-primary">Save selected Restaurants</button>
            @endif
            {{Form::open(['action' => 'ResDemoController@generatePDF2All', 'method' => 'get', 'target' => '_blank']) }}
                {{ Form::submit('Print All', ['id'=> 'printAll', 'class' => 'btn p-3 btn-success', 'style' => 'position:fixed; bottom:25px; z-index:9999; left:10%;; width:200px; display:none;']) }}
                {{ Form::hidden('resDemos','', ['class' => 'form-control', 'id'=>'sendForPrit']) }}
            {{Form::close() }}
            

            <script>
                function setForDownload(){

                    var allRes = '';
                    $('#saveChanges').hide(500);
                    $('#printAll').show(500);

                    $('.allResDemoSel').each(function(i, obj) {
                        if($(this).val() != 0){
                            console.log($(this).val());
                            if(allRes == ''){
                                allRes = $(this).val();
                            }else{
                                allRes += '|-|'+$(this).val();
                            }
                           
                            
                            $('#doneDown'+$(this).val()).attr('class', ' form-control btn btn-block btn-success');
                            $('#doneDown'+$(this).val()).html('Erledigt');
                        }
                    });
                    $('#sendForPrit').val(allRes);
                }
            </script>


        @if(Auth::user()->email == "Briefmarketing@qrorpa.ch" || Auth::user()->email == 'mg_ResData@qrorpa.ch')
        <div class="col-12 d-flex justify-content-between flex-wrap" id="pageBtnCRM">
            <a href="IndexCRMQ?page=1"  style="width:3%;"><button class="text-center btn btn-block {{(isset($_GET['page']) && $_GET['page'] == 1) || !isset($_GET['page']) ? 'btn-primary' : 'btn-outline-primary'}}">1</button></a>
            <a href="IndexCRMQ?page=2"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 2 ? 'btn-primary' : 'btn-outline-primary'}}">2</button></a>
            <a href="IndexCRMQ?page=3"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 3 ? 'btn-primary' : 'btn-outline-primary'}}">3</button></a>
            <a href="IndexCRMQ?page=4"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 4 ? 'btn-primary' : 'btn-outline-primary'}}">4</button></a>
            <a href="IndexCRMQ?page=5"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 5 ? 'btn-primary' : 'btn-outline-primary'}}">5</button></a>
            <a href="IndexCRMQ?page=6"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 6 ? 'btn-primary' : 'btn-outline-primary'}}">6</button></a>
            <a href="IndexCRMQ?page=7"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 7 ? 'btn-primary' : 'btn-outline-primary'}}">7</button></a>
            <a href="IndexCRMQ?page=8"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 8 ? 'btn-primary' : 'btn-outline-primary'}}">8</button></a>
            <a href="IndexCRMQ?page=9"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 9 ? 'btn-primary' : 'btn-outline-primary'}}">9</button></a>
            <a href="IndexCRMQ?page=10"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 10 ? 'btn-primary' : 'btn-outline-primary'}}">10</button></a>
            <a href="IndexCRMQ?page=11"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 11 ? 'btn-primary' : 'btn-outline-primary'}}">11</button></a>
            <a href="IndexCRMQ?page=12"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 12 ? 'btn-primary' : 'btn-outline-primary'}}">12</button></a>
            <a href="IndexCRMQ?page=13"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 13 ? 'btn-primary' : 'btn-outline-primary'}}">13</button></a>
            <a href="IndexCRMQ?page=14"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 14 ? 'btn-primary' : 'btn-outline-primary'}}">14</button></a>
            <a href="IndexCRMQ?page=15"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 15 ? 'btn-primary' : 'btn-outline-primary'}}">15</button></a>
            <a href="IndexCRMQ?page=16"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 16 ? 'btn-primary' : 'btn-outline-primary'}}">16</button></a>
            <a href="IndexCRMQ?page=17"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 17 ? 'btn-primary' : 'btn-outline-primary'}}">17</button></a>
            <a href="IndexCRMQ?page=18"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 18 ? 'btn-primary' : 'btn-outline-primary'}}">18</button></a>
            <a href="IndexCRMQ?page=19"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 19 ? 'btn-primary' : 'btn-outline-primary'}}">19</button></a>
            <a href="IndexCRMQ?page=20"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 20 ? 'btn-primary' : 'btn-outline-primary'}}">20</button></a>
            <a href="IndexCRMQ?page=21"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 21 ? 'btn-primary' : 'btn-outline-primary'}}">21</button></a>
            <a href="IndexCRMQ?page=22"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 22 ? 'btn-primary' : 'btn-outline-primary'}}">22</button></a>
            <a href="IndexCRMQ?page=23"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 23 ? 'btn-primary' : 'btn-outline-primary'}}">23</button></a>
            <a href="IndexCRMQ?page=24"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 24 ? 'btn-primary' : 'btn-outline-primary'}}">24</button></a>
            <a href="IndexCRMQ?page=25"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 25 ? 'btn-primary' : 'btn-outline-primary'}}">25</button></a>
            <a href="IndexCRMQ?page=26"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 26 ? 'btn-primary' : 'btn-outline-primary'}}">26</button></a>
            <a href="IndexCRMQ?page=27"  style="width:3%;"><button class="text-center btn btn-block {{isset($_GET['page']) && $_GET['page'] == 27 ? 'btn-primary' : 'btn-outline-primary'}}">27</button></a>
        </div>
        @endif
          
        
        <div class="col-12 mt-5">

                    <div class="d-flex justify-content-between" style="margin-top:-50px;">
                            
                           
                            <div style="width:5%" id="OneGet" class="mt-3 pl-1">
                                <p><strong>Nr.</strong></p>
                            </div>
                            <div style="width:20%" class="mt-3">
                                <p><strong>Name / Address / PLZ + ORT</strong></p>
                            </div>
                            <div style="width:15%" class="mt-3">
                                <p><strong>Email</strong></p>
                            </div>
                            <div style="width:13.3%" class="mt-3 text-center">
                                <p><strong>Phone Nr</strong></p>
                            </div>  
                            <div style="width:13.3%" class="mt-3  text-center">
                                <p><strong>Phone Nr 2</strong></p>
                            </div> 
                            <div style="width:10%" class="mt-3  text-center">
                                <p><strong>Comment</strong></p>
                            </div> 
                            <div style="width:15.3%" class="mt-3  text-center">
                                <p><strong>Web Link</strong></p>
                            </div>  
                            <div style="width:8%" class=" text-center mt-3">
                                <p><strong>PDF</strong></p>
                            </div>
                        
                    </div>




                 


                    <script>
                           function deleteResDem(RDId){
                                $.ajax({
                                    url: '{{ route("addAll.destroyOne") }}',
                                    method: 'delete',
                                    data: {delId: RDId, _token: '{{csrf_token()}}'},
                                    success: (response) => {
                                        
                                        $('#ResDemoLine'+RDId).hide(500); 
                                        $('#selectedResDemo'+RDId).val(0); 
                                        
                                    },
                                    error: (error) => {
                                        console.log(error);
                                        alert('Oops! Something went wrong')
                                    }
                                });
                            }
                            function selectThisResDemo(RDId){
                                if($('#selectedResDemo'+RDId).val() == 0){
                                    $('#ResDemoLine'+RDId).attr('style','background-color:lightgray;');
                                    $('#selectedResDemo'+RDId).val(RDId);
                                }else{
                                    $('#ResDemoLine'+RDId).attr('style','background-color:white;');
                                    $('#selectedResDemo'+RDId).val('0')
                                }
                            }
                    </script>






            <?php
                if(isset($_GET['page'])){

                    if($_GET['page'] == 1){$allResD = resdemoalfa::where([['id', '>=', 0],['id', '<=', 500]])->get();
                    }else if($_GET['page'] == 2){ $allResD = resdemoalfa::where([['id', '>=', 501],['id', '<=', 1000]])->get();
                    }else if($_GET['page'] == 3){$allResD = resdemoalfa::where([['id', '>=', 1001],['id', '<=', 1500]])->get();
                    }else if($_GET['page'] == 4){$allResD = resdemoalfa::where([['id', '>=', 1501],['id', '<=', 2000]])->get();
                    }else if($_GET['page'] == 5){$allResD = resdemoalfa::where([['id', '>=', 2001],['id', '<=', 2500]])->get();
                    }else if($_GET['page'] == 6){$allResD = resdemoalfa::where([['id', '>=', 2501],['id', '<=', 3000]])->get();
                    }else if($_GET['page'] == 7){$allResD = resdemoalfa::where([['id', '>=', 3001],['id', '<=', 3500]])->get();
                    }else if($_GET['page'] == 8){$allResD = resdemoalfa::where([['id', '>=', 3501],['id', '<=', 4000]])->get();
                    }else if($_GET['page'] == 9){$allResD = resdemoalfa::where([['id', '>=', 4001],['id', '<=', 4500]])->get();
                    }else if($_GET['page'] == 10){$allResD = resdemoalfa::where([['id', '>=', 4501],['id', '<=', 5000]])->get();
                    }else if($_GET['page'] == 11){$allResD = resdemoalfa::where([['id', '>=', 5001],['id', '<=', 5500]])->get();
                    }else if($_GET['page'] == 12){$allResD = resdemoalfa::where([['id', '>=', 5501],['id', '<=', 6000]])->get();
                    }else if($_GET['page'] == 13){$allResD = resdemoalfa::where([['id', '>=', 6001],['id', '<=', 6500]])->get();
                    }else if($_GET['page'] == 14){$allResD = resdemoalfa::where([['id', '>=', 6501],['id', '<=', 7000]])->get();
                    }else if($_GET['page'] == 15){$allResD = resdemoalfa::where([['id', '>=', 7001],['id', '<=', 7500]])->get();
                    }else if($_GET['page'] == 16){$allResD = resdemoalfa::where([['id', '>=', 7501],['id', '<=', 8000]])->get();
                    }else if($_GET['page'] == 17){$allResD = resdemoalfa::where([['id', '>=', 8001],['id', '<=', 8500]])->get();
                    }else if($_GET['page'] == 18){$allResD = resdemoalfa::where([['id', '>=', 8501],['id', '<=', 9000]])->get();
                    }else if($_GET['page'] == 19){$allResD = resdemoalfa::where([['id', '>=', 9001],['id', '<=', 9500]])->get();
                    }else if($_GET['page'] == 20){$allResD = resdemoalfa::where([['id', '>=', 9501],['id', '<=', 10000]])->get();
                    }else if($_GET['page'] == 21){$allResD = resdemoalfa::where([['id', '>=', 10001],['id', '<=', 10500]])->get();
                    }else if($_GET['page'] == 22){$allResD = resdemoalfa::where([['id', '>=', 10501],['id', '<=', 11000]])->get();
                    }else if($_GET['page'] == 23){$allResD = resdemoalfa::where([['id', '>=', 11001],['id', '<=', 11500]])->get();
                    }else if($_GET['page'] == 24){$allResD = resdemoalfa::where([['id', '>=', 11501],['id', '<=', 12000]])->get();
                    }else if($_GET['page'] == 25){$allResD = resdemoalfa::where([['id', '>=', 12001],['id', '<=', 12500]])->get();
                    }else if($_GET['page'] == 26){$allResD = resdemoalfa::where([['id', '>=', 12501],['id', '<=', 13000]])->get();
                    }else if($_GET['page'] == 27){$allResD = resdemoalfa::where([['id', '>=', 13001],['id', '<=', 13500]])->get();
                    }else if($_GET['page'] == 28){$allResD = resdemoalfa::where([['id', '>=', 13501],['id', '<=', 14000]])->get();
                    }else if($_GET['page'] == 29){$allResD = resdemoalfa::where([['id', '>=', 14001],['id', '<=', 14500]])->get();
                    }else if($_GET['page'] == 30){$allResD = resdemoalfa::where([['id', '>=', 14501],['id', '<=', 15000]])->get();
                    }else{
                        $allResD = resdemoalfa::where([['id', '>=', 0],['id', '<=', 500]])->get();
                    }
                }else{
                    if(Auth::user()->email == "callagent@qrorpa.ch"){
                        $allResD = resdemoalfa::where('isForCA', 1)->get();
                    }else if(Auth::user()->email == "Briefmarketing@qrorpa.ch" || Auth::user()->email == 'mg_ResData@qrorpa.ch'){
                        $allResD = resdemoalfa::where([['id', '>=', 0],['id', '<=', 500]])->get();
                    }else{
                        $allResD = resdemoalfa::where('isForCA', Auth::user()->email)->get();
                    }
                  
                }
            ?>





















                <ul class="list-group" id="SearchDemoUL">
          
                    @foreach($allResD as $resDemoBeta)
                
                        <li class="list-group-item" id="ResDemoLine{{$resDemoBeta->id}}">
                            <input type="hidden" value="0" id="selectedResDemo{{$resDemoBeta->id}}" class="allResDemoSel">
                            <div class="d-flex justify-content-between">
                                

                                    <div style="width:25%" class="mt-3 selectiveResDemo d-flex flex-wrap justify-content-around" >
                                    
                                        @if(Auth::user()->email == 'callagent@qrorpa.ch')
                                        
                                        <p style="font-size:21px; width:100%;"><strong >{{$resDemoBeta->forThis}}. {{$resDemoBeta->emri}}</strong></p>
                                        <p style="margin-top:-17px; width:100%;">{{$resDemoBeta->adresa}}</p>
                                        <p style="margin-top:-17px; width:100%;">{{$resDemoBeta->plz}} {{$resDemoBeta->ort}}</p>
                                            @if($resDemoBeta->boosName != 'none')
                                            <input onkeyup="saveNameResDemo(this.value,'{{$resDemoBeta->id}}')" style="width:46%; border:none; border-bottom:1px solid black; height:22px;"
                                                     class="pl-3" type="text" value="{{$resDemoBeta->boosName}}">
                                            @else
                                                <input onkeyup="saveNameResDemo(this.value,'{{$resDemoBeta->id}}')" style="width:46%; border:none; border-bottom:1px solid black; height:22px;"
                                                     class="pl-3" type="text" placeholder="Name">
                                            @endif
                                            <p style="width:2%"> / </p>
                                            @if($resDemoBeta->boosSurname != 'none')
                                                <input onkeyup="saveSurnameResDemo(this.value,'{{$resDemoBeta->id}}')" style="width:46%; border:none; border-bottom:1px solid black;
                                                    height:22px;" class="pl-3" type="text" value="{{$resDemoBeta->boosSurname}}">
                                            @else
                                                <input onkeyup="saveSurnameResDemo(this.value,'{{$resDemoBeta->id}}')" style="width:46%; border:none; border-bottom:1px solid black;
                                                 height:22px;" class="pl-3" type="text" placeholder="Nachname">
                                            @endif
                                        @else
                                        
                                        <p onclick="selectThisResDemo('{{$resDemoBeta->id}}')" style="font-size:21px; width:100%;"><strong >{{$resDemoBeta->forThis}}. {{$resDemoBeta->emri}}</strong></p>
                                        <p onclick="selectThisResDemo('{{$resDemoBeta->id}}')" style="margin-top:-17px; width:100%;">{{$resDemoBeta->adresa}}</p>
                                        <p onclick="selectThisResDemo('{{$resDemoBeta->id}}')" style="margin-top:-17px; width:100%;">{{$resDemoBeta->plz}} {{$resDemoBeta->ort}}</p>
                                            @if($resDemoBeta->boosName != 'none')
                                                <p class="pl-3" style="width:46%; border:none; border-bottom:1px solid black; height:22px;">{{$resDemoBeta->boosName}}</p>
                                            @else
                                                <p style="width:46%; border:none; border-bottom:1px solid black; height:22px;"></p>
                                            @endif
                                            <p style="width:2%"> / </p>
                                            @if($resDemoBeta->boosSurname != 'none')
                                                <p class="pl-3" style="width:46%; border:none; border-bottom:1px solid black; height:22px;">{{$resDemoBeta->boosSurname}}</p>
                                            @else
                                                <p style="width:46%; border:none; border-bottom:1px solid black; height:22px;"></p>
                                            @endif

                                    
                                        @endif
                                    </div>
<!-- ............................................................................................................................................................................................................ -->
                                    <div style="width:15%; border-right:1px solid gray;" class="mt-3 d-flex flex-wrap" >
                                        <?php 
                                            if($resDemoBeta->email != 'empty'){
                                                $thisRDEmail = $resDemoBeta->email;
                                            }else{$thisRDEmail = '';}
                                        ?>
                                         <button style="width:25%;  height:25px;" class="btn btn-outline-default" onclick="sendEmailCRMDate('{{$resDemoBeta->id}}')" >Send</button>
                                        <input  type="text" style="width:75%; height:25px; border:none; border-bottom:1px solid lightgray;" onkeyup="showemailSaveCRMC('{{$resDemoBeta->id}}')"
                                             id="emailInputCRM{{$resDemoBeta->id}}" value="{{$thisRDEmail}}">

                                        <button style="width:100%; display:none;" class="btn btn-outline-success mt-2 mb-2" id="emailSaveCRMCID{{$resDemoBeta->id}}"
                                             onclick="emailSaveCRMC('{{$resDemoBeta->id}}')">Save</button>
                                       
                                        @if($resDemoBeta->emailDate != 'empty')
                                            <p class="text-center" style="margin-top:-12px; margin-bottom:-15px; width:100%;">{{$resDemoBeta->emailDate}}</p>
                                        @endif
                                        @if(Auth::user()->email == 'Briefmarketing@qrorpa.ch' || Auth::user()->email == 'mg_ResData@qrorpa.ch')
                                            <select style="height:fit-content; width:90%;" onchange="chngisForCA(this.value,'{{$resDemoBeta->id}}')" class="browser-default custom-select">

                                                <option value="0" <?php echo ($resDemoBeta->isForCA == 0) ? 'selected' : '';?> >Nicht Aktiv</option>
                                                <option value="callagent01@qrorpa.ch" <?php echo ($resDemoBeta->isForCA == 'callagent01@qrorpa.ch') ? 'selected' : '';?> >Call agent 01</option>
                                                <option value="callagent02@qrorpa.ch" <?php echo ($resDemoBeta->isForCA == 'callagent02@qrorpa.ch') ? 'selected' : '';?> >Call agent 02</option>
                                                <option value="callagent03@qrorpa.ch" <?php echo ($resDemoBeta->isForCA == 'callagent03@qrorpa.ch') ? 'selected' : '';?> >Call agent 03</option>
                                                <option value="callagent04@qrorpa.ch" <?php echo ($resDemoBeta->isForCA == 'callagent04@qrorpa.ch') ? 'selected' : '';?> >Call agent 04</option>
                                                <option value="callagent05@qrorpa.ch" <?php echo ($resDemoBeta->isForCA == 'callagent05@qrorpa.ch') ? 'selected' : '';?> >Call agent 05</option>
                                                <option value="callagent06@qrorpa.ch" <?php echo ($resDemoBeta->isForCA == 'callagent06@qrorpa.ch') ? 'selected' : '';?> >Call agent 06</option>
                                                <option value="callagent07@qrorpa.ch" <?php echo ($resDemoBeta->isForCA == 'callagent07@qrorpa.ch') ? 'selected' : '';?> >Call agent 07</option>
                                                <option value="callagent08@qrorpa.ch" <?php echo ($resDemoBeta->isForCA == 'callagent08@qrorpa.ch') ? 'selected' : '';?> >Call agent 08</option>
                                                <option value="callagent09@qrorpa.ch" <?php echo ($resDemoBeta->isForCA == 'callagent09@qrorpa.ch') ? 'selected' : '';?> >Call agent 09</option>
                                                <option value="callagent10@qrorpa.ch" <?php echo ($resDemoBeta->isForCA == 'callagent10@qrorpa.ch') ? 'selected' : '';?> >Call agent 10</option>
                                            </select>
                                            <i style="width:10%; color:green; display:none;" id="doneSetCA{{$resDemoBeta->id}}" class="far fa-check-circle"></i>

                                        @endif
                                    </div>
<!-- ............................................................................................................................................................................................................ -->
                                    <div style="width:13.3%;  border-right:1px solid gray;" class="mt-3 pl-2 pr-2 d-flex flex-wrap">
                                        <?php 
                                            if($resDemoBeta->nrTel != 'empty'){
                                                $thisRDNrTel = $resDemoBeta->nrTel;
                                            }else{$thisRDNrTel = '';}
                                        ?>
                                         <button style="width:25%;  height:25px;" class="btn btn-outline-default" onclick="sendNrTelCRMDate('{{$resDemoBeta->id}}')">Call</button>
                                        <input  type="text" style="width:75%; height:25px; border:none; border-bottom:1px solid lightgray;" onkeyup="showNrTelSaveCRMC('{{$resDemoBeta->id}}')"
                                           id="nrTelInputCRM{{$resDemoBeta->id}}"  value="{{$thisRDNrTel}}">

                                        <button style="width:100%; display:none;" class="btn btn-outline-success mt-2 mb-2" id="nrTelSaveCRMCID{{$resDemoBeta->id}}"
                                             onclick="nrTelSaveCRMC('{{$resDemoBeta->id}}')">Save</button>

                                        @if($resDemoBeta->nrTelDate != 'empty')
                                            <p class="text-center" style="margin-top:-12px; margin-bottom:-15px; width:100%;">{{$resDemoBeta->nrTelDate}}</p>
                                        @endif
                                    </div>
                                    <div style="width:13.3%" class="mt-3 pl-2 pr-2 d-flex flex-wrap">
                                        <?php 
                                            if($resDemoBeta->nrTel2 != 'empty'){
                                                $thisRDNrTel2 = $resDemoBeta->nrTel2;
                                            }else{$thisRDNrTel2 = '';}
                                        ?>
                                         <button style="width:25%;  height:25px;" class="btn btn-outline-default" onclick="sendNrTelCRMDate2('{{$resDemoBeta->id}}')">Call</button>
                                        <input  type="text" style="width:75%; height:25px; border:none; border-bottom:1px solid lightgray;" onkeyup="showNrTelSaveCRMC2('{{$resDemoBeta->id}}')"
                                           id="nrTelInputCRM2{{$resDemoBeta->id}}"  value="{{$thisRDNrTel2}}">

                                        <button style="width:100%; display:none;" class="btn btn-outline-success mt-2 mb-2" id="nrTelSaveCRMCID2{{$resDemoBeta->id}}"
                                             onclick="nrTelSaveCRMC2('{{$resDemoBeta->id}}')">Save</button>

                                        @if($resDemoBeta->nrTel2Date != 'empty')
                                            <p class="text-center" style="margin-top:-12px; margin-bottom:-15px; width:100%;">{{$resDemoBeta->nrTel2Date}}</p>
                                        @endif
                                    </div>
<!-- ............................................................................................................................................................................................................ -->
                                    <div style="width:10%" class="mt-3">
                                        <?php 
                                            if($resDemoBeta->commentRD != 'empty'){
                                                $thisRDComm = $resDemoBeta->commentRD;
                                            }else{$thisRDComm = '';}
                                        ?>
                                        <textarea value="{{$thisRDComm}}" id="newCommTXTA{{$resDemoBeta->id}}" style="width:100%;" rows="2" onkeyup="showSaveCom('{{$resDemoBeta->id}}')">{{$thisRDComm}}</textarea>
                                        <button class="btn btn-block btn-outline-success" onclick="saveNewComCRM('{{$resDemoBeta->id}}')" id="saveCommentBtn{{$resDemoBeta->id}}" style="display:none;"> Save </button>
                                    </div>
<!-- ............................................................................................................................................................................................................ -->
                                    
                                    <div style="width:15.3%" class="mt-3 pl-2 pr-2 d-flex flex-wrap">
                                        <?php 
                                            if($resDemoBeta->webP != 'empty'){
                                                $thisRDWeb= $resDemoBeta->webP;
                                            }else{$thisRDWeb = '';}
                                        ?>
                                      
                                         <button style="width:25%;  height:25px;" class="btn btn-outline-default" onclick="sendWebCRMDate('{{$resDemoBeta->id}}')" >Visit</button>
                                 
                                        <input  type="text" style="width:75%; height:25px; border:none; border-bottom:1px solid lightgray;" onkeyup="showWebSaveCRMC('{{$resDemoBeta->id}}')"
                                             id="webInputCRM{{$resDemoBeta->id}}" value="{{$thisRDWeb}}">
                                             

                                        <button style="width:100%; display:none;" class="btn btn-outline-success mt-2 mb-2" id="webSaveCRMCID{{$resDemoBeta->id}}"
                                             onclick="webSaveCRMC('{{$resDemoBeta->id}}')">Save</button>
                                       
                                        @if($resDemoBeta->webPDate != 'empty')
                                            <p class="text-center" style="margin-top:-12px; margin-bottom:-15px; width:100%;">{{$resDemoBeta->webPDate}}</p>
                                        @endif
                                        
                                    </div>
<!-- ............................................................................................................................................................................................................ -->
                                    @if(Auth::user()->email != 'callagent@qrorpa.ch')
                                        <div style="width:8%" class=" text-center" id="PDFprintFor{{$resDemoBeta->id}}" >
                                            {{Form::open(['action' => ['ResDemoController@generatePDF2', $resDemoBeta->id], 'method' => 'get', 'target'=>'_blank', 'id' => 'submitCrPDFCRM'.$resDemoBeta->id]) }}
                                                <div class="form-group text-center">
                                                @if($resDemoBeta->status == 0)
                                                <button type="button" class="form-control btn btn-block btn-danger" style="margin-top:5px; margin-bottom:-5px;" target="_blank" id="doneDown{{$resDemoBeta->id}}"
                                                    onclick="refreshLine('{{$resDemoBeta->id}}')">PDF</button>
                                                    <div id="doneDown2{{$resDemoBeta->id}}" style="display:none;">
                                                        {{ Form::submit('Erledigt', ['class' => 'form-control btn btn-block btn-success', 'style' => 'margin-top:5px; margin-bottom:-5px;']) }}
                                                            <br> <p id="doneDown2Date{{$resDemoBeta->id}}" style="margin-top:-12px; margin-bottom:-15px;">{{$resDemoBeta->datePrint}}</p> 
                                                        </div>
                                                @elseif($resDemoBeta->status == 1)
                                                    @if($resDemoBeta->datePrint != 'empty')
                                                        {{ Form::submit('Erledigt', ['class' => 'form-control btn btn-block btn-success', 'style' => 'margin-top:5px; margin-bottom:-5px;']) }}
                                                        <br> <p style="margin-top:-12px; margin-bottom:-15px;">{{$resDemoBeta->datePrint}}</p> 
                                                    @else
                                                        {{ Form::submit('Erledigt', ['class' => 'form-control btn btn-block btn-success', 'style' => 'margin-top:5px; margin-bottom:-5px;']) }}
                                                    @endif
                                                @endif

                                                </div>
                                            {{Form::close()}}

                                          
                                                @if($resDemoBeta->clAccept == 1)
                                                <button class="btn btn-default" style="color:green; border-bottom:3px solid green;"><strong>CL akzeptiert</strong></button>
                                                @else
                                                    <button class="btn btn-default" style="color:red; border-bottom:3px solid red; font-size:12px;"><strong>CL nicht akzeptiert</strong></button>
                                                @endif
                                     
                                        </div>
                                    @else
                                        @if($resDemoBeta->clAccept == 1)
                                            <button style="width:8%" onclick="chngStatClAc('{{$resDemoBeta->id}}')" class="btn btn-success"><strong>CL akzeptiert</strong></button>
                                        @else
                                            <button style="width:8%" onclick="chngStatClAc('{{$resDemoBeta->id}}')" class="btn btn-danger"><strong>CL nicht akzeptiert</strong></button>
                                        @endif
                                    @endif
<!-- ............................................................................................................................................................................................................ -->
                                
                            </div>
                        </li>
                    @endforeach
                </ul>

                <script>

                    function refreshLine(rDemoId){
                        $('#doneDown'+rDemoId).hide(200);
                        $('#doneDown2'+rDemoId).show(200);
                        var currentdate = new Date(); 
                        var datetime = currentdate.getDate() + "."
                                    + (currentdate.getMonth()+1)  + "." 
                                    + currentdate.getFullYear() + "  "  
                                    + currentdate.getHours() + ":"  
                                    + currentdate.getMinutes();
                        $('#doneDown2Date'+rDemoId).html(datetime);
                        $('#submitCrPDFCRM'+rDemoId).submit();
                    }
                    function showSaveCom(rDemoId){
                        // document.getElementById('saveCommentBtn'+rDemoId).style.display = "block";
                        $('#saveCommentBtn'+rDemoId).show();
                        console.log(rDemoId);
                    }
                    function saveNewComCRM(rDemoId){
                        
                        $.ajax({
                            url: '{{ route("resDemo.saveNewComCRM") }}',
                            method: 'post',
                            data: {
                                id: rDemoId,
                                newC: $('#newCommTXTA'+rDemoId).val(),
                                _token: '{{csrf_token()}}'
                            },
                            success: () => {
                                $("#ResDemoLine"+rDemoId).load(location.href+" #ResDemoLine"+rDemoId+">*","");
                            },
                            error: (error) => {
                                console.log(error);
                                alert('Oops! Something went wrong')
                            }
                        });
                    }

                    function sendEmailCRMDate(rDemoId){
                        $.ajax({
                            url: '{{ route("resDemo.emailDateSet") }}',
                            method: 'post',
                            data: {id: rDemoId, _token: '{{csrf_token()}}'},
                            success: () => {$("#ResDemoLine"+rDemoId).load(location.href+" #ResDemoLine"+rDemoId+">*","");},
                            error: (error) => {console.log(error); alert('Oops! Something went wrong')}
                        });
                    }
                    function sendNrTelCRMDate(rDemoId){
                        $.ajax({
                            url: '{{ route("resDemo.nrTelDateSet") }}',
                            method: 'post',
                            data: {id: rDemoId, _token: '{{csrf_token()}}'},
                            success: () => {$("#ResDemoLine"+rDemoId).load(location.href+" #ResDemoLine"+rDemoId+">*","");},
                            error: (error) => {console.log(error); alert('Oops! Something went wrong')}
                        });
                    }
                    function sendNrTelCRMDate2(rDemoId){
                        $.ajax({
                            url: '{{ route("resDemo.nrTelDateSet2") }}',
                            method: 'post',
                            data: {id: rDemoId, _token: '{{csrf_token()}}'},
                            success: () => {$("#ResDemoLine"+rDemoId).load(location.href+" #ResDemoLine"+rDemoId+">*","");},
                            error: (error) => {console.log(error); alert('Oops! Something went wrong')}
                        });
                    }
                    function sendWebCRMDate(rDemoId){
                        $.ajax({
                            url: '{{ route("resDemo.sendWebDate") }}',
                            method: 'post',
                            data: {id: rDemoId, _token: '{{csrf_token()}}'},
                            success: () => {$("#ResDemoLine"+rDemoId).load(location.href+" #ResDemoLine"+rDemoId+">*","");},
                            error: (error) => {console.log(error); alert('Oops! Something went wrong')}
                        });
                    }

                    function showemailSaveCRMC(rDemoId){
                        $('#emailSaveCRMCID'+rDemoId).show(200);
                    }
                    function showNrTelSaveCRMC(rDemoId){
                        $('#nrTelSaveCRMCID'+rDemoId).show(200);
                    }
                    function showNrTelSaveCRMC2(rDemoId){
                        $('#nrTelSaveCRMCID2'+rDemoId).show(200);
                    }
                    function showWebSaveCRMC(rDemoId){
                        $('#webSaveCRMCID'+rDemoId).show(200);
                    }

                    function emailSaveCRMC(rDemoId){
                        $.ajax({
                            url: '{{ route("resDemo.emailSaveNewCRM") }}',
                            method: 'post',
                            data: {id: rDemoId, newEmail: $('#emailInputCRM'+rDemoId).val(), _token: '{{csrf_token()}}'},
                            success: () => {$("#ResDemoLine"+rDemoId).load(location.href+" #ResDemoLine"+rDemoId+">*","");},
                            error: (error) => {console.log(error); alert('Oops! Something went wrong')}
                        });
                    }
                    function nrTelSaveCRMC(rDemoId){
                        $.ajax({
                            url: '{{ route("resDemo.nrTelSaveNewCRM") }}',
                            method: 'post',
                            data: {id: rDemoId, newNrTel: $('#nrTelInputCRM'+rDemoId).val(), _token: '{{csrf_token()}}'},
                            success: () => {$("#ResDemoLine"+rDemoId).load(location.href+" #ResDemoLine"+rDemoId+">*","");},
                            error: (error) => {console.log(error); alert('Oops! Something went wrong')}
                        });
                    }
                    function nrTelSaveCRMC2(rDemoId){
                        $.ajax({
                            url: '{{ route("resDemo.nrTelSaveNewCRM2") }}',
                            method: 'post',
                            data: {id: rDemoId, newNrTel: $('#nrTelInputCRM2'+rDemoId).val(), _token: '{{csrf_token()}}'},
                            success: () => {$("#ResDemoLine"+rDemoId).load(location.href+" #ResDemoLine"+rDemoId+">*","");},
                            error: (error) => {console.log(error); alert('Oops! Something went wrong')}
                        });
                    }
                    function webSaveCRMC(rDemoId){
                        $.ajax({
                            url: '{{ route("resDemo.webSaveNewCRM2") }}',
                            method: 'post',
                            data: {id: rDemoId, newWeb: $('#webInputCRM'+rDemoId).val(), _token: '{{csrf_token()}}'},
                            success: () => {$("#ResDemoLine"+rDemoId).load(location.href+" #ResDemoLine"+rDemoId+">*","");},
                            error: (error) => {console.log(error); alert('Oops! Something went wrong')}
                        });
                    }
                </script>














        </div>
    </div>
</div>















<script>

        $("#searchDemoCRMInput2").keyup(function(e) {
            var searchWord = $(this).val();

            if(searchWord != ''){
                var listings = "";
                $.ajax({
                    method: 'POST',
                    url: '{{route("search.fromCRM")}}',
                    dataType: 'json',
                    data: {
                        '_token': '{{csrf_token()}}',
                        searchWord: searchWord
                    },
                    success: function(res){
                 
                        $('#pageBtnCRM').hide(1000);
                        $('#searchDemoUL').hide(1000);
                        $('#searchDemo2').html('');

                        $.each(res, function(index, value){
                            listings= '<li class="list-group-item" id="ResDemoLine'+value.id+'">'+
                                            '<input type="hidden" value="0" id="selectedResDemo'+value.id+'" class="allResDemoSel">'+
                                            '<div class="d-flex justify-content-between">'+
                                            '<p>'+value.emri+'</p>'+
                                            '</div>'+
                                        '</li>';

                            $('#searchDemo2').append(listings);
                            console.log(listings);
                        });// end foreach
                        console.log(searchWord);
                       
                    },
                    error: (error) => {
                        console.log(error);
                        // alert('Oops! Something went wrong')
                    }
                });

               
             
            }else{
           
            }
        });
  




        function chngisForCA(val,ResDId){
            $.ajax({
				url: '{{ route("resDemo.chngIsForca") }}',
				method: 'post',
				data: {
					id: ResDId,
					val: val,
					_token: '{{csrf_token()}}'
				},
				success: () => {
                    // $("#ResDemoLine"+ResDId).load(location.href+" #ResDemoLine"+ResDId+">*","");
                    $('#doneSetCA'+ResDId).show(100).delay(2000).hide(100);
				},
				error: (error) => {
					console.log(error);
					alert('Bitte aktualisieren, etwas ist schief gelaufen')
				}
			});
        }


        function chngStatClAc(ResDId){
            $.ajax({
				url: '{{ route("resDemo.chngClAc") }}',
				method: 'post',
				data: {
					id: ResDId,
					_token: '{{csrf_token()}}'
				},
				success: () => {
					$("#ResDemoLine"+ResDId).load(location.href+" #ResDemoLine"+ResDId+">*","");
				},
				error: (error) => {
					console.log(error);
					alert('Bitte aktualisieren, etwas ist schief gelaufen')
				}
			});
        }


        
        function saveNameResDemo(newVal, ResDId){
            // console.log(newVal);
            $.ajax({
				url: '{{ route("resDemo.saveNameBoos") }}',
				method: 'post',
				data: {
					id: ResDId,
                    val: newVal,
					_token: '{{csrf_token()}}'
				},
				success: () => {
					// $("#ResDemoLine"+ResDId).load(location.href+" #ResDemoLine"+ResDId+">*","");
				},
				error: (error) => {
					console.log(error);
					alert('Bitte aktualisieren, etwas ist schief gelaufen')
				}
			});
        }



        function saveSurnameResDemo(newVal, ResDId){
            // console.log(newVal);
            $.ajax({
				url: '{{ route("resDemo.saveSurnameBoos") }}',
				method: 'post',
				data: {
					id: ResDId,
                    val: newVal,
					_token: '{{csrf_token()}}'
				},
				success: () => {
					// $("#ResDemoLine"+ResDId).load(location.href+" #ResDemoLine"+ResDId+">*","");
				},
				error: (error) => {
					console.log(error);
					alert('Bitte aktualisieren, etwas ist schief gelaufen')
				}
			});
        }

</script>
<script src="{{ asset('js/app.js') }}" defer></script>

 </body>

 </html>