<?php

use App\resPlates;
use App\Restorant;
use App\cooksProductSelection;
use Illuminate\Support\Facades\Auth;
    use App\User;
    use App\accessControllForAdmins;
    use App\tablesAccessToWaiters;
    use App\TableQrcode;
    use App\kategori;
    use App\Produktet;
    use App\LlojetPro;
    use App\ekstra;
    use Carbon\Carbon;

    $resto = Restorant::find(Auth::User()->sFor);
?>
 <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
 @include('fontawesome')
<style>
    .centerImg{
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50px;
        height: 50px;
    }
    .gifImg{
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 23px;
        height: 23px;
    }
    .btn-outline-success:hover{
        background-color: rgb(72,81,87);
    }

    .teksti{
        justify-content:space-between;
        margin-top:-50px;
        color:#FFF;
        font-weight:bold;
        font-size:23px;
        margin-bottom:10px;
    }
 
    .prod-name{
        line-height: 2;
    }
    .add-plus-section{
        text-align: right;
        padding: 0px;
    }
    .product-section{
        border-bottom: 1px solid #dcd9d9;
        padding-bottom: 15px;
    }
    .recommended-title{
        margin-left: 0px !important;
    }
    .teksti strong{
        margin-left:20px;
    }
    .teksti i{
        margin-right:20px
    }
</style>

<div class="p-2 pb-5">
    <div class="d-flex flex-wrap flex-wrap justify-content-between" id="orderServingDiv">
        <h4 style="color:rgb(39,190,175); width:100%;"><strong>Kontrollieren Sie Ihre Arbeiter</strong></h4>
        <button style="width:100%;" class="btn btn-dark mt-1 shadow-none" data-toggle="modal" data-target="#addWorker"><strong>Fügen Sie einen Arbeiter hinzu</strong></button>
    </div>
    
    <hr>

    <div class="d-flex flex-wrap justify-content-start" id="allWorkersWaiter">
        <h4 style="color:rgb(39,190,175); width:100%;">Ihre Kellner</h4>
        @foreach(User::where([['sFor',Auth::User()->sFor],['role','55']])->get()->sortByDesc('created_at') as $user)
            <div class="card mb-1 p-1" style="width: 49.5%; margin-right:0.5%;" id="oneUsers{{$user->id}}">
                @if($user->profilePic != 'empty')
                <img class="card-img-top centerImg" src="storage/profilePics/{{$user->profilePic}}" alt="Card image cap">
                @else
                <img class="card-img-top centerImg" src="storage/icons/Asset 24800.png" alt="Card image cap">
                @endif
                <div class="card-body p-1">
                    <h5 class="card-title" style="margin-bottom:2px; font-size:1.2rem;">
                        <i data-toggle="modal" data-target="#deleteWorkerConf{{$user->id}}Modal" style="color:red;" class="fas fa-trash-alt mr-2"></i>
                        {{$user->name}}
                    </h5>
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;">{{$user->email}}</p>
                    @if($user->phoneNr != 'empty')
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;"><strong><i class="fas fa-phone"></i> {{$user->phoneNr}}</strong></p>
                    @else
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;"><strong><i class="fas fa-phone-slash"></i></strong></p>
                    @endif
                </div>
                <button class="btn btn-outline-dark btn-block shadow-none" data-toggle="modal" data-target="#setTablesForWo{{$user->id}}Modal">
                    <strong>Tische zuweisen</strong>
                </button>
                <button class="btn btn-outline-dark btn-block shadow-none" data-toggle="modal" data-target="#setAccessForWo{{$user->id}}Modal">
                    <strong>Zugangskontrolle</strong>
                </button>
                <a href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi' => $user->id])}}" class="btn btn-outline-dark btn-block shadow-none" >
                    <strong><i class="far fa-chart-bar"></i> Statistiken</strong>
                </a>
                <button class="btn btn-outline-dark btn-block shadow-none" onclick="changePassForWoPrep('{{$user->id}}','{{$user->name}}')" data-toggle="modal" data-target="#changePassForWoModal">
                    <strong><i class="fa-solid fa-lock"></i> Änder_Passwort</strong>
                </button>
            </div>

            <!-- delete worker Modal -->
            <div class="modal" id="deleteWorkerConf{{$user->id}}Modal" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Sind Sie sicher, dass Sie <strong>{{$user->name}}</strong> und alles über ihn löschen möchten</h5>
                        </div>
                        <div class="modal-body d-flex justify-content-between">
                            <button data-dismiss="modal" style="width:49%;" class="btn btn-outline-dark">Nein</button>
                            <button onclick="deleteWorker('{{$user->id}}')" style="width:49%;" class="btn btn-danger" data-dismiss="modal">Ja</button>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    </div>

    <hr>

    <div class="d-flex flex-wrap justify-content-start" id="allWorkersCook">
        <h4 style="color:rgb(39,190,175); width:100%;">Ihre Köche</h4>
        <button style="width:100%;" class="btn btn-dark shadow-none mb-1" data-toggle="modal" data-target="#catToPlate"><strong>Legen Sie Kategorien auf Platten fest</strong></button>
        @foreach(User::where([['sFor',Auth::User()->sFor],['role','54']])->get()->sortByDesc('created_at') as $user)
            <div class="card mb-1 p-1" style="width: 49.5%; margin-right:0.5%;" id="oneUsers{{$user->id}}">
                @if($user->profilePic != 'empty')
                <img class="card-img-top centerImg" src="storage/profilePics/{{$user->profilePic}}" alt="Card image cap">
                @else
                <img class="card-img-top centerImg" src="storage/icons/Asset 24800.png" alt="Card image cap">
                @endif
                <div class="card-body p-1">
                    <h5 class="card-title" style="margin-bottom:2px; font-size:1.2rem;">
                        <i data-toggle="modal" data-target="#deleteWorkerConf{{$user->id}}Modal" style="color:red;" class="fas fa-trash-alt mr-2"></i>
                        {{$user->name}}
                    </h5>
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;">{{$user->email}}</p>
                    @if($user->phoneNr != 'empty')
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;"><strong><i class="fas fa-phone"></i> {{$user->phoneNr}}</strong></p>
                    @else
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;"><strong><i class="fas fa-phone-slash"></i></strong></p>
                    @endif
                </div>
                <button class="btn btn-outline-dark btn-block shadow-none" data-toggle="modal" data-target="#setKPETForWo{{$user->id}}Modal"><strong>Produktauswahl</strong></button>
                <div class="mt-1 d-flex justify-content-between">
                    @if (cooksProductSelection::where([['workerId',$user->id],['contentType','Takeaway']])->first() != NULL)
                    <button style="width: 49%; font-size:11px;" class="btn btn-dark shadow-none" id="cookTakeawayAccessBtn{{$user->id}}" 
                        onclick="cookTakeawayAccess('{{$user->id}}','{{Auth::User()->sFor}}')">
                        <strong>Takeaway</strong>
                    </button>
                    @else
                    <button style="width: 49%; font-size:11px;" class="btn btn-outline-dark shadow-none" id="cookTakeawayAccessBtn{{$user->id}}" 
                        onclick="cookTakeawayAccess('{{$user->id}}','{{Auth::User()->sFor}}')">
                        <strong>Takeaway</strong>
                    </button>
                    @endif

                    @if (cooksProductSelection::where([['workerId',$user->id],['contentType','Delivery']])->first() != NULL)
                    <button style="width: 49%; font-size:11px;" class="btn btn-dark shadow-none" id="cookDeliveryAccessBtn{{$user->id}}" 
                        onclick="cookDeliveryAccess('{{$user->id}}','{{Auth::User()->sFor}}')">
                        <strong>Delivery</strong>
                    </button>
                    @else
                    <button style="width: 49%; font-size:11px;" class="btn btn-outline-dark shadow-none" id="cookDeliveryAccessBtn{{$user->id}}" 
                        onclick="cookDeliveryAccess('{{$user->id}}','{{Auth::User()->sFor}}')">
                        <strong>Delivery</strong>
                    </button>
                    @endif
                </div>
                <div class="mt-1 d-flex justify-content-between">
                    @if ($user->cookPanV == 1)
                        <button style="width: 24.5%; font-size:11px;" class="btn btn-dark shadow-none" id="cookPanV1Btn{{$user->id}}">
                            <strong>V.1</strong>
                        </button>
                        <button style="width: 24.5%; font-size:12px;" class="btn btn-info shadow-none" id="cookPanV1InfoBtn{{$user->id}}"
                        data-toggle="modal" data-target="#infoVer1Modal">
                            (i)
                        </button>
                        <button style="width: 24.5%; font-size:11px;" class="btn btn-outline-dark shadow-none" id="cookPanV2Btn{{$user->id}}" 
                            onclick="cookPanVerChng('{{$user->id}}','2')">
                            <strong>V.2</strong>
                        </button>
                        <button style="width: 24.5%; font-size:12px;" class="btn btn-info shadow-none" id="cookPanV2InfoBtn{{$user->id}}"
                        data-toggle="modal" data-target="#infoVer2Modal">
                            (i)
                        </button>
                    @elseif ($user->cookPanV == 2)
                        <button style="width: 24.5%; font-size:11px;" class="btn btn-outline-dark shadow-none" id="cookPanV1Btn{{$user->id}}"
                            onclick="cookPanVerChng('{{$user->id}}','1')">
                            <strong>V.1</strong>
                        </button>
                        <button style="width: 24.5%; font-size:12px;" class="btn btn-info shadow-none" id="cookPanV1InfoBtn{{$user->id}}"
                        data-toggle="modal" data-target="#infoVer1Modal">
                            (i)
                        </button>
                        <button style="width: 24.5%; font-size:11px;" class="btn btn-dark shadow-none" id="cookPanV2Btn{{$user->id}}">
                            <strong>V.2</strong>
                        </button>
                        <button style="width: 24.5%; font-size:12px;" class="btn btn-info shadow-none" id="cookPanV2InfoBtn{{$user->id}}"
                        data-toggle="modal" data-target="#infoVer2Modal">
                            (i)
                        </button>
                    @endif
                </div>

                <button class="btn btn-outline-dark btn-block shadow-none mt-1" onclick="changePassForWoPrep('{{$user->id}}','{{$user->name}}')" data-toggle="modal" data-target="#changePassForWoModal">
                    <strong><i class="fa-solid fa-lock"></i> Änder_Passwort</strong>
                </button>
            </div>

            <!-- delete worker Modal -->
            <div class="modal" id="deleteWorkerConf{{$user->id}}Modal" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Sind Sie sicher, dass Sie <strong>{{$user->name}}</strong> und alles über ihn löschen möchten</h5>
                        </div>
                        <div class="modal-body d-flex justify-content-between">
                            <button data-dismiss="modal" style="width:49%;" class="btn btn-outline-dark">Nein</button>
                            <button onclick="deleteWorker('{{$user->id}}')" style="width:49%;" class="btn btn-danger" data-dismiss="modal">Ja</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <hr>

    <div class="d-flex flex-wrap justify-content-start" id="allWorkersAccountant">
        <h4 style="color:rgb(39,190,175); width:100%;">Ihre Buchhalter </h4>
        @foreach(User::where([['sFor',Auth::User()->sFor],['role','53']])->get()->sortByDesc('created_at') as $user)
            <div class="card mb-1 p-1" style="width: 49.5%; margin-right:0.5%;" id="oneUsers{{$user->id}}">
                @if($user->profilePic != 'empty')
                <img class="card-img-top centerImg" src="storage/profilePics/{{$user->profilePic}}" alt="Card image cap">
                @else
                <img class="card-img-top centerImg" src="storage/icons/Asset 24800.png" alt="Card image cap">
                @endif
                <div class="card-body p-1">
                    <h5 class="card-title" style="margin-bottom:2px; font-size:1.2rem;">
                        <i data-toggle="modal" data-target="#deleteWorkerConf{{$user->id}}Modal" style="color:red;" class="fas fa-trash-alt mr-2"></i>
                        {{$user->name}}
                    </h5>
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;">{{$user->email}}</p>
                    @if($user->phoneNr != 'empty')
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;"><strong><i class="fas fa-phone"></i> {{$user->phoneNr}}</strong></p>
                    @else
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;"><strong><i class="fas fa-phone-slash"></i></strong></p>
                    @endif
                </div>
                <?php
                    $hasAcceStKonta = accessControllForAdmins::where([['userId',$user->id],['accessDsc','Statistiken']])->first();
                    $hasAccePrKonta = accessControllForAdmins::where([['userId',$user->id],['accessDsc','Products']])->first();
                ?>
                @if ($hasAcceStKonta != NULL)
                <button class="btn btn-dark btn-block shadow-none" onclick="setAcceStatTo53('{{$user->id}}')">
                @else
                <button class="btn btn-outline-dark btn-block shadow-none" onclick="setAcceStatTo53('{{$user->id}}')">
                @endif
                    <strong>Statistik / Bericht </strong>
                </button>

                @if ($hasAccePrKonta != NULL)
                <button class="btn btn-dark btn-block shadow-none" onclick="setAcceProdsTo53('{{$user->id}}')">
                @else
                <button class="btn btn-outline-dark btn-block shadow-none" onclick="setAcceProdsTo53('{{$user->id}}')"> 
                @endif
                    <strong>Inhaltsverwaltung</strong>
                </button>

                <button class="btn btn-outline-dark btn-block shadow-none" onclick="changePassForWoPrep('{{$user->id}}','{{$user->name}}')" data-toggle="modal" data-target="#changePassForWoModal">
                    <strong><i class="fa-solid fa-lock"></i> Änder_Passwort</strong>
                </button>
         
            </div>

            <!-- delete worker Modal -->
            <div class="modal" id="deleteWorkerConf{{$user->id}}Modal" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Sind Sie sicher, dass Sie <strong>{{$user->name}}</strong> und alles über ihn löschen möchten</h5>
                        </div>
                        <div class="modal-body d-flex justify-content-between">
                            <button data-dismiss="modal" style="width:49%;" class="btn btn-outline-dark">Nein</button>
                            <button onclick="deleteWorker('{{$user->id}}')" style="width:49%;" class="btn btn-danger" data-dismiss="modal">Ja</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>



















<div class="modal" id="changePassForWoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog modal-m" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Legen Sie ein neues Passwort für <strong><span id="changePassForWoModalName"></span></strong> fest</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fas fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="changePassForWoId" value="0">

                        <div class="form-group">
                            <label for="exampleInputPassword1">Neues Kennwort</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control shadow-none" id="newPassInp1" value="" autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-dark shadow-none" type="button" id="newPassBtn1" onclick="showPassInp('1')">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="exampleInputPassword1">Bestätige neues Passwort</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control shadow-none" id="newPassInp2" value="" autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-dark shadow-none" type="button" id="newPassBtn2" onclick="showPassInp('2')">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                       
                        <button class="btn btn-success shadow-none btn-block" onclick="changePassForWo()"><strong>Weitermachen</strong></button>

                        <div class="alert alert-danger mt-1 shadow-none text-center" id="changePassForWoModalErr01" style="display:none;">
                            Schreiben Sie zuerst das neue Passwort!
                        </div>
                        <div class="alert alert-danger mt-1 shadow-none text-center" id="changePassForWoModalErr02" style="display:none;">
                            Das neue Passwort sollte mindestens 8 Zeichen enthalten
                        </div>
                        <div class="alert alert-danger mt-1 shadow-none text-center" id="changePassForWoModalErr03" style="display:none;">
                            Schreiben Sie auch die Passwortbestätigung!
                        </div>
                        <div class="alert alert-danger mt-1 shadow-none text-center" id="changePassForWoModalErr04" style="display:none;">
                            Das Passwort und die Passwortbestätigung stimmen nicht überein !
                        </div>

                        <div class="alert alert-success mt-1 shadow-none text-center" id="changePassForWoModalScc01" style="display:none; font-size:1.2rem;">
                            Sie haben das Passwort für diesen Worker erfolgreich geändert
                        </div>
                        <div class="alert alert-danger mt-1 shadow-none text-center" id="changePassForWoModalErr05" style="display:none;">
                            Wir konnten Ihre Anfrage zu diesem Zeitpunkt nicht abschließen, bitte kontaktieren Sie QRorpa!
                        </div>
                    </div>
                </div>
            </div>
        </div>























@include('adminPanel.mngWorkersTel.infoVerModals')


<div id="AccessAndTableModalsForWaiters">
    @foreach(User::where([['sFor',Auth::User()->sFor],['role','55']])->get()->sortByDesc('created_at') as $worker)
        <!-- Access controll Modal -->
        <div class="modal fade" id="setAccessForWo{{$worker->id}}Modal" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$worker->name}}'s Zugangskontrolle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-wrap justify-content-start" id="setAccessForWo{{$worker->id}}AControlls">
                            @foreach(accessControllForAdmins::where([['userId',Auth::User()->id],['accessDsc','!=','workersManagement'],['accessDsc','!=','Frei']])->get() as $oneAcControl)
                                <?php $hasAccess = accessControllForAdmins::where([['userId',$worker->id],['accessDsc',$oneAcControl->accessDsc]])->first(); ?>
                                @if($hasAccess != NULL)
                                <button onclick="removeThisAccess('{{$hasAccess->id}}','{{$worker->id}}','{{$oneAcControl->id}}')" style="width:49.5%; margin-right:0.5%;" id="waiterAccess{{$worker->id}}O{{$oneAcControl->id}}"
                                class="btn btn-success mb-1 shadow-none"><strong>{{$oneAcControl->accessDsc}}</strong></button>
                                @else
                                <button onclick="addThisAccess('{{Auth::User()->id}}','{{$worker->id}}','{{$oneAcControl->id}}')"  style="width:49.5%; margin-right:0.5%;" id="waiterAccess{{$worker->id}}O{{$oneAcControl->id}}"
                                class="btn btn-outline-success mb-1 shadow-none"><strong>{{$oneAcControl->accessDsc}}</strong></button>
                                @endif
                            @endforeach 
                        </div>
                    </div>
            
                </div>
            </div>
        </div>

        <!-- Table selection Modal -->
        <div class="modal fade" id="setTablesForWo{{$worker->id}}Modal" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$worker->name}}'s Tische zuweisen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-wrap justify-content-start" id="setTablesForWo{{$worker->id}}AControlls">
                            @foreach(TableQrcode::where('Restaurant',Auth::User()->sFor)->get() as $oneTable)
                                <?php $tableAccess = tablesAccessToWaiters::where([['waiterId',$worker->id],['tableNr',$oneTable->tableNr],['statusAct','1']])->first(); ?>
                                @if($tableAccess != NULL)
                                <button onclick="removeThisTable('{{$tableAccess->id}}','{{$worker->id}}','{{$oneTable->tableNr}}')" style="width:16%; margin-right:0.66%;" id="waiterTable{{$worker->id}}O{{$oneTable->tableNr}}"
                                    class="btn btn-success mb-1 shadow-none"><strong>{{$oneTable->tableNr}}</strong></button>
                                @else
                                <button onclick="addThisTable('{{$worker->id}}','{{$oneTable->tableNr}}','{{Auth::User()->sFor}}')" id="waiterTable{{$worker->id}}O{{$oneTable->tableNr}}"
                                style="width:16%; margin-right:0.66%;" class="btn btn-outline-success mb-1 shadow-none">{{$oneTable->tableNr}}</button>
                                @endif
                            @endforeach 
                        </div>
                    </div>
            
                </div>
            </div>
        </div>
    @endforeach
</div>


<div id="KPETModalsForCooks">
    @foreach(User::where([['sFor',Auth::User()->sFor],['role','54']])->get()->sortByDesc('created_at') as $worker)
        <!-- Access controll Modal -->
        <div class="modal fade" id="setKPETForWo{{$worker->id}}Modal" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$worker->name}}'s Management für Kochen/Zubereiten von Produkten</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-wrap justify-content-start" id="setKPETForWo{{$worker->id}}AControlls">
                            @foreach (kategori::where('toRes',Auth::User()->sFor)->get() as $kat)

                                <div style="width:100%;" class="mb-2">

                                    <div class="allKatFoto" id="KategoriFoto{{$kat->id}}O{{$worker->id}}">
                                        <div style="cursor: pointer; position:relative; object-fit: cover;" >
                                            <img style="border-radius:30px; width:100%; height:120px;" onclick="showProKat('{{$kat->id}}','{{$worker->id}}')" src="storage/kategoriaUpload/{{$kat->foto}}"alt="">

                                            <?php $prodKate = cooksProductSelection::where([['workerId',$worker->id],['contentType','Category'],['contentId',$kat->id]])->first();?>
                                            @if(strlen($kat->emri) > 20)
                                                <div class="teksti d-flex justify-content-between" style="font-size:20px;  margin-bottom:13px;">          
                                                  
                                                    @if($prodKate != NULL)
                                                    <div style="width: 7%;" class="text-center" id="KategoriPlus{{$kat->id}}O{{$worker->id}}">
                                                    <i style="color:rgb(39,190,175);" class="fas fa-plus-circle ml-2" onclick="removeCategoryForCook('{{$worker->id}}','{{$kat->id}}','{{$prodKate->id}}')"></i> 
                                                    </div>
                                                    @else
                                                    <div style="width: 7%;" class="text-center" id="KategoriPlus{{$kat->id}}O{{$worker->id}}">
                                                    <i style="color:white;" class="fas fa-plus-circle ml-2" onclick="addCategoryForCook('{{$worker->id}}','{{$kat->id}}')"></i> 
                                                    </div>
                                                    @endif

                                                    <span style="width: 65%;" class="text-left" onclick="showProKat('{{$kat->id}}','{{$worker->id}}')"><strong>{{$kat->emri}}</strong></span>
                                                    <button style="width: 25%;" class="btn btn-dark mr-3" data-toggle="modal" data-target="#categoryTAndE{{$kat->id}}O{{$worker->id}}">T / E</button>
                                                </div>
                                            @else
                                                <div class="teksti d-flex justify-content-between" >    

                                                    @if($prodKate != NULL)
                                                    <div style="width: 7%;" class="text-center" id="KategoriPlus{{$kat->id}}O{{$worker->id}}">
                                                    <i style="color:rgb(39,190,175);" class="fas fa-plus-circle ml-2" onclick="removeCategoryForCook('{{$worker->id}}','{{$kat->id}}','{{$prodKate->id}}')"></i> 
                                                    </div>
                                                    @else
                                                    <div style="width: 7%;" class="text-center" id="KategoriPlus{{$kat->id}}O{{$worker->id}}">
                                                    <i style="color:white;" class="fas fa-plus-circle ml-2" onclick="addCategoryForCook('{{$worker->id}}','{{$kat->id}}')"></i> 
                                                    </div>
                                                    @endif

                                                    <span style="width: 65%;" class="text-left" onclick="showProKat('{{$kat->id}}','{{$worker->id}}')"><strong>{{$kat->emri}}</strong></span> 
                                                    <button style="width: 25%;" class="btn btn-dark mr-3" data-toggle="modal" data-target="#categoryTAndE{{$kat->id}}O{{$worker->id}}">T / E</button>
                                                </div>
                                            @endif
                                            <input type="hidden" value="0" id="state{{$kat->id}}O{{$worker->id}}">
                                        </div>
                                    </div>
                                    <div class="container prodsFoto" id="prodsKatFoto{{$kat->id}}O{{$worker->id}}" style="display:none;">
                                        @foreach(Produktet::where('toRes', '=', Auth::user()->sFor)->where('kategoria','=',$kat->id)->get()->sortByDesc('visits') as $ketoProd)
                                            <div class="row p-1" id="catProds{{$ketoProd->id}}O{{$worker->id}}">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                            
                                                        <div class="col-12 product-section">
                                                            <div class="row">
                                                                    
                                                                <diV class="col-9">
                                                                    <h4 class="pull-right prod-name prodsFont color-text" style="font-weight:bold; font-size: 1rem ">
                                                                        {{$ketoProd->emri}} 
                                                                        @if($ketoProd->restrictPro != 0)
                                                                            @if($ketoProd->restrictPro == 16)
                                                                            <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px;" src="/storage/icons/R16.jpeg" alt="noImg"></span>
                                                                            @elseif($ketoProd->restrictPro == 18)
                                                                            <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px;" src="/storage/icons/R18.jpeg" alt="noImg"></span>
                                                                            @endif
                                                                        @endif
                                                                    </h4>
                                                                        <p style=" margin-top:-10px; font-size:13px;">{{substr($ketoProd->pershkrimi,0,35)}} 
                                                                            @if(strlen($ketoProd->pershkrimi)>35)
                                                                                <span onclick="showTypeMenu('{{$ketoProd->id}}')" class="hover-pointer" style="font-size:16px;"> . . .</span> 
                                                                            @endif 
                                                                        </p>
                                                                    <h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;">
                                                                        CHF
                                                                        </span> 
                                                                            @if(Carbon::now()->format('H:i') >= '20:00' || Carbon::now()->format('H:i') <= '03:00')
                                                                                @if($ketoProd->qmimi2 != 999999)
                                                                                    {{sprintf('%01.2f', $ketoProd->qmimi2)}}
                                                                                @else
                                                                                    {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                                                @endif
                                                                            @else
                                                                                @if($ketoProd->qmimi2 != 999999)
                                                                                    {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                                                    @if(Carbon::now()->format('H:i') > '19:40' && Carbon::now()->format('H:i') < '20:00')
                                                                                        <span class="ml-4" style="font-size:14px;">{{__('adminP.from8Pm')}} <span style="color:gray;">{{__('adminP.currencyShow')}}</span>
                                                                                        {{sprintf('%01.2f', $ketoProd->qmimi2)}} </span>
                                                                                    @endif
                                                                                @else
                                                                                    {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                                                @endif
                                                                            @endif
                                                                    </h5>
                                                                </div>
                                                                <div class="col-3 add-plus-section">
                                                                    <!-- <button class="btn mt-2 noBorder" type="button" >
                                                                        <i class="fa fa-plus fa-2x" aria-hidden="true" style="color:#27beaf"></i>
                                                                    </button> -->
                                                                    <?php $prodProd = cooksProductSelection::where([['workerId',$worker->id],['contentType','Product'],['contentId',$ketoProd->id]])->first();?>
                                                                    @if($prodProd != NULL)
                                                                    <button onclick="removeProductForCook('{{$prodProd->id}}','{{$worker->id}}','{{$ketoProd->id}}','{{$kat->id}}')"
                                                                    class="btn-block btn btn-success mt-2" style="font-size:0.6rem;" id="addProductForCookBtn{{$worker->id}}O{{$ketoProd->id}}">Registrieren</button>
                                                                    @else
                                                                    <button onclick="addProductForCook('{{$worker->id}}','{{$ketoProd->id}}','{{$kat->id}}')" 
                                                                    class="btn-block btn btn-outline-success mt-2" style="font-size:0.6rem;" id="addProductForCookBtn{{$worker->id}}O{{$ketoProd->id}}">Registrieren</button>
                                                                    @endif
                                                                </div>
                                                                 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="modal" id="categoryTAndE{{$kat->id}}O{{$worker->id}}" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                                style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">( {{$kat->emri}} ) Typen und Extras für {{$worker->name}}</h5>
                                                <button type="button" class="close" onclick="closeCategoryTAndE('{{$kat->id}}','{{$worker->id}}')" aria-label="Close">
                                                <span aria-hidden="true"><i class="fas fa-times"></i></span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="d-flex flex-wrap justify-content-start" id="categoryTAndE{{$kat->id}}O{{$worker->id}}Body">
                                                    <h4 style="color:rgb(39,190,175); width:100%;">Extras</h4>
                                                    @foreach (ekstra::where('toCat',$kat->id)->get() as $oneEx)
                                                        <?php $prodEx = cooksProductSelection::where([['workerId',$worker->id],['contentType','Extra'],['contentId',$oneEx->id]])->first();?>
                                                        @if($prodEx != NULL)
                                                        <button class="btn btn-dark mb-1" onclick="removeExtraForCook('{{$prodEx->id}}','{{$oneEx->id}}','{{$worker->id}}','{{$kat->id}}')" 
                                                        style="width:49%; margin-right:1%;" id="addExtraForCookBtn{{$oneEx->id}}O{{$worker->id}}">{{$oneEx->emri}}</button>
                                                        @else
                                                        <button class="btn btn-outline-dark mb-1" onclick="addExtraForCook('{{$oneEx->id}}','{{$worker->id}}','{{$kat->id}}')" 
                                                        style="width:49%; margin-right:1%;" id="addExtraForCookBtn{{$oneEx->id}}O{{$worker->id}}">{{$oneEx->emri}}</button>
                                                        @endif
                                                    @endforeach

                                                    <hr style="width: 100%;">

                                                    <h4 style="color:rgb(39,190,175); width:100%;">Typen</h4>
                                                    @foreach (LlojetPro::where('kategoria',$kat->id)->get() as $oneTy)
                                                        <?php $prodTy = cooksProductSelection::where([['workerId',$worker->id],['contentType','Type'],['contentId',$oneTy->id]])->first();?>
                                                        @if($prodTy != NULL)
                                                        <button class="btn btn-dark mb-1" onclick="removeTypeForCook('{{$prodTy->id}}','{{$oneTy->id}}','{{$worker->id}}','{{$kat->id}}')"
                                                        style="width:49%; margin-right:1%;" id="addTypeForCookBtn{{$oneTy->id}}O{{$worker->id}}">{{$oneTy->emri}}</button>
                                                        @else
                                                        <button class="btn btn-outline-dark mb-1" onclick="addTypeForCook('{{$oneTy->id}}','{{$worker->id}}','{{$kat->id}}')" 
                                                        style="width:49%; margin-right:1%;" id="addTypeForCookBtn{{$oneTy->id}}O{{$worker->id}}">{{$oneTy->emri}}</button>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    </div>
            
                </div>
            </div>
        </div>
    @endforeach
</div>





    <!-- add worker Modal -->
    <div class="modal" id="addWorker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWorkerLabel">Kellner oder Koch hinzufügen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <button class="btn btn-outline-dark" id="woType55" onclick="selectWorkerType('55')" style="width:100%;"><strong>Kellner</strong></button>
                        <button class="btn btn-outline-dark mt-1" id="woType54" onclick="selectWorkerType('54')"  style="width:100%;"><strong>Koch</strong></button>
                        <button class="btn btn-outline-dark mt-1" id="woType53" onclick="selectWorkerType('53')"  style="width:100%;"><strong>Buchhalter</strong></button>
                    </div>
                    <input type="hidden" id="addWorkerWorkerType" value="0">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Name</span>
                        </div>
                        <input id="addWorkerName" type="text" class="form-control shadow-none" aria-label="Name">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Email</span>
                        </div>
                        <input id="addWorkerEmail" type="text" class="form-control shadow-none" value="" autocomplete="false">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Passwort</span>
                        </div>
                        <input id="addWorkerPassword" type="text" class="form-control shadow-none" value="" autocomplete="false">
                    </div>

                    <button onclick="saveNewWorker('{{Auth::User()->sFor}}')" class="btn btn-block btn-success">Speichern</button>

                    <div class="alert alert-danger text-center mt-2" style="display:none;" id="addWorkerError01">Bitte schreiben Sie ihren Namen!</div>
                    <div class="alert alert-danger text-center mt-2" style="display:none;" id="addWorkerError02">Bitte schreiben Sie ihre E-Mail!</div>
                    <div class="alert alert-danger text-center mt-2" style="display:none;" id="addWorkerError03">Bitte schreiben Sie ihr Passwort!</div>
                    <div class="alert alert-danger text-center mt-2" style="display:none;" id="addWorkerError04">Bitte wählen Sie einen Arbeitertyp aus!</div>
                    <div class="alert alert-danger text-center mt-2" style="display:none;" id="addWorkerError05">Diese E-Mail-Adresse ist ungültig!</div>
                    <div class="alert alert-danger text-center mt-2" style="display:none;" id="addWorkerError06">Sie können nur die Domain qrorpa.ch/ verwenden ( example@qrorpa.ch/)!</div>
                    <div class="alert alert-danger text-center mt-2" style="display:none;" id="addWorkerError07">Es existiert bereits ein Benutzer mit dieser E-Mail-Adresse. Versuchen Sie es mit einer anderen!</div>
                    <div class="alert alert-danger text-center mt-2" style="display:none;" id="addWorkerError08">Diese E-Mail ist für Sie nicht erlaubt!</div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal" id="catToPlate" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="color:rgb(72,81,87);"><strong>Auf welchem Teller wird welche Kategorie serviert</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                        $insNrPlateNext = resPlates::where('toRes',Auth::user()->sFor)->count() + 1; 
                    ?>
                    <!-- NEW PART -->
                    <div style="width: 100%;" class="input-group mb-1" id="newPlateDiv">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" disabled>P.<span id="catToPlateNextPlSp">{{$insNrPlateNext}}</span></button>
                        </div>
                        <input type="text" id="newPlateName" class="form-control shadow-none" placeholder="Plattenname" aria-label="Plattenname" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-success shadow-none" type="button" onclick="saveNewPlate()">Sparen</button>
                        </div>
                    </div>
                    <!-- ............ -->
                    <!-- EDIT PART -->
                    <div style="width: 100%; display:none" class="input-group mb-1" id="editPlateDiv">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" disabled>P.<span id="editPlateNr"></span></button>
                        </div>
                        <input type="text" id="editPlateName" class="form-control shadow-none" value="" aria-label="Plattenname" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-info shadow-none" type="button" onclick="saveChangesPlate()">Sparen</button>
                            <button class="btn btn-danger shadow-none" type="button" onclick="cancelChangesPlate()">stornieren</button>
                        </div>
                        <input type="hidden" id="editPlateId" value="">
                    </div>
                    <!-- ............ -->
                    <div class="alert alert-danger text-center mb-1" style="display: none;" id="savePlateError01">
                        Schreiben Sie zuerst den Namen/die Beschreibung der Platten
                    </div>
                    <div class="alert alert-danger text-center mb-1" style="display: none;" id="savePlateError02">
                        Ein Schild mit diesem Namen/Beschreibung ist bereits registriert
                    </div>
                    <div class="alert alert-success text-center mb-1" style="display: none;" id="savePlateSuccess01">
                        Neue Platte erfolgreich gespeichert
                    </div>

                    <div class="d-flex flex-wrap justify-content-between mb-2" style="max-height:145px; overflow-y:scroll;" id="catToPlateShowPlates">
                        @if (resPlates::where('toRes',Auth::user()->sFor)->count() == 0)
                            <p style="width: 100%; color:red;" class="text-center"><strong>Es sind noch keine Platten registriert</strong></p>
                        @else
                            @foreach (resPlates::where('toRes',Auth::user()->sFor)->get() as $onePlate)
                                <div class="p-2 mb-1 text-center d-flex" style="width: 100%; border:1px solid rgb(72,81,87); border-radius:4px;">
                                    <span style="width: 72%;"><strong>(P.{{$onePlate->desc2C}}) "{{$onePlate->nameTitle}}"</strong></span>
                                    <button style="width: 14%; padding:6px 0px 6px 0px;" onclick="editResPlate('{{$onePlate->id}}','{{$onePlate->nameTitle}}','{{$onePlate->desc2C}}')" class="btn btn-info shadow-none"><i class="far fa-edit"></i></button>
                                    <button style="width: 14%; padding:6px 0px 6px 0px;" onclick="deleteResPlate('{{$onePlate->id}}')" class="btn btn-danger shadow-none"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="alert alert-success text-center mb-1" style="display: none;" id="delEditScs01">
                        Das Kennzeichen wurde erfolgreich gelöscht, Kennzeichennummern haben sich nun geändert
                    </div>
                    <div class="alert alert-success text-center mb-1" style="display: none;" id="delEditScs02">
                        Die Platte wurde erfolgreich bearbeitet
                    </div>
                    <div class="alert alert-danger text-center mb-1" style="display: none;" id="editPlateError01">
                        Die Platte wurde nicht gefunden, aktualisieren und erneut versuchen!
                    </div>

            
                    <div class="d-flex flex-wrap justify-content-start" id="catToPlateShowCats">
                        @foreach (kategori::where('toRes',Auth::User()->sFor)->get() as $kat)
                            <div style="width:100%;" class="mb-2">
                                <div class="allKatFotoPlate" id="catDivPlate{{$kat->id}}">
                                    <div style="cursor: pointer; position:relative; object-fit: cover;" >
                                        <img style="border-radius:30px; width:100%; height:120px;" src="storage/kategoriaUpload/{{$kat->foto}}"alt="">
                                        @if(strlen($kat->emri) > 20)
                                            <div class="teksti d-flex flex-wrap justify-content-around" style="font-size:20px; margin-bottom:13px;">
                                                <div class="d-flex justify-content-start pl-2 pr-2" style="width: 100%; margin-top:-50px; max-width:100%; overflow-x:scroll;">
                                                    @foreach (resPlates::where('toRes',Auth::user()->sFor)->get() as $onePlate)
                                                        <button id="p{{$onePlate->desc2C}}Btn{{$kat->id}}" onclick="selCatPlate('{{$onePlate->desc2C}}','{{$kat->id}}')" style="width: 15%; margin-right:0.33%;" 
                                                        class="shadow-none mb-1 btn {{$kat->forPlate === $onePlate->desc2C ? 'btn-success' : 'btn-dark'}} pAllBtn{{$kat->id}}" >P.{{$onePlate->desc2C}}</button>
                                                    @endforeach
                                                </div>          
                                                <span style="width: 100%;" class="text-left"><strong>{{$kat->emri}}</strong></span> 
                                           
                                            </div>
                                        @else
                                            <div class="teksti d-flex flex-wrap justify-content-between" >                 
                                                <div class="d-flex justify-content-start pl-2 pr-2" style="width: 100%; margin-top:-50px; max-width:100%; overflow-y:scroll;">
                                                    @foreach (resPlates::where('toRes',Auth::user()->sFor)->get() as $onePlate)
                                                        <button id="p{{$onePlate->desc2C}}Btn{{$kat->id}}" onclick="selCatPlate('{{$onePlate->desc2C}}','{{$kat->id}}')" style="width: 15%; margin-right:0.33%;" 
                                                        class="shadow-none mb-1 btn {{$kat->forPlate === $onePlate->desc2C ? 'btn-success' : 'btn-dark'}} pAllBtn{{$kat->id}}" >P.{{$onePlate->desc2C}}</button>
                                                    @endforeach
                                                </div>          
                                                <span style="width: 100%;" class="text-left"><strong>{{$kat->emri}}</strong></span> 
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>












@include('adminPanel.mngWorkersTel.mngWorkersTelJS')