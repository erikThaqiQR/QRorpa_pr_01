<?php
    use App\Restorant;
    use App\TableReservation;
?>
@extends('layouts.appInfoTableRez')

@section('content')
    <div id="infoTableRezDesktop" style="display:none;">
        @if(isset($_GET['Res']) && isset($_GET['RezId']) && isset($_GET['hash']))

            <?php
                $theResInfo = Restorant::find($_GET['Res']);
                $theReseservationInfo = TableReservation::find($_GET['RezId']);
            ?>

            @if($theReseservationInfo == NULL)
                <!-- Table reservation request does not exist -->
                <!-- THe status of this reservation cannot be changed  -->
                <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                    <h2 class="text-center" style="color: red;"><strong> {{__('others.tableRezervationDoesNotExist')}} </strong></h2>

                    <div class="d-flex justify-content-between">
                        <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                    </div>
                </div>
            @elseif($theResInfo == NULL)
                <!-- Table reservation request does not exist -->
                <!-- THe status of this reservation cannot be changed  -->
                <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                    <h2 class="text-center" style="color: red;"><strong> {{__('others.tableRezervationRestaurantNotFound')}} </strong></h2>

                    <div class="d-flex justify-content-between">
                        <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                    </div>
                </div>
            @else
                <?php
                    $dita2D = explode('-',$theReseservationInfo->dita);
                ?>
                @if($_GET['hash'] == $theReseservationInfo->idnHash)

                    <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                        @if($theReseservationInfo->status == 1)
                            <h2 style="color: rgb(39,190,175);"><strong> {{__('others.confirmTableRezervation')}} 👍 </strong></h2>
                        @elseif($theReseservationInfo->status == 2)
                            <h2 style="color: red"><strong> {{__('others.declineTableRezervation')}} </strong></h2>
                        @endif

                        <h4>{{__('others.detailForTableReservation')}}</h4>
                        <table style="width:100%; font-size:18px;" class="table-hover table-bordered text-center"> 
                            <tr>
                                <th>{{__('others.ClienNameLastname')}}</th>
                                <th>{{__('others.Restaurant')}}</th>
                                <th>{{__('others.Table')}}</th>
                                <th>{{__('others.nrOfPersons')}} (max)</th>
                                <th>{{__('others.Date')}}</th>
                                <th>{{__('others.Time')}}</th>
                            </tr>
                            <tr>
                                <td>{{$theReseservationInfo->emri}} {{$theReseservationInfo->mbiemri}}</td>
                                <td>{{Restorant::find($theReseservationInfo->toRes)->emri}}</td>
                                <td>{{$theReseservationInfo->tableNr}}</td>
                                <td>{{$theReseservationInfo->persona}} ( X )</td>
                                <td>{{$dita2D[2]}} / {{$dita2D[1]}} / {{$dita2D[0]}}</td>
                                <td>{{$theReseservationInfo->koha01}} => {{$theReseservationInfo->koha02}}</td>
                            </tr>
                            <tr>
                                <td colspan="3"><strong>{{__('others.phoneNr')}}:</strong> {{$theReseservationInfo->nrTel}}</td>
                                <td colspan="3"><strong>{{__('others.email')}}:</strong> {{$theReseservationInfo->email}}</td>
                            </tr>
                            <tr>
                                <td colspan="6"><strong>{{__('others.comment')}}:</strong> {{$theReseservationInfo->koment}}</td>
                            </tr>
                        </table>

                        <div class="d-flex justify-content-between">
                            <!-- <a class="p-3 mt-2 btn btn-info" style="width: 49%; font-weight:bold; font-size:19px;" href="/?Res={{$_GET['Res']}}&t=1"> {{$theResInfo->emri}} Buchseite</a> -->
                            <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                        </div>
                    </div>
                @else
                    <!-- HASH variable not valid -->
                    <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                        <h2 class="text-center" style="color: red;"><strong> {{__('others.tableRezervationAccessNotAllowed')}} </strong></h2>

                        <div class="d-flex justify-content-between">
                            <!-- <a class="p-3 mt-2 btn btn-info" style="width: 49%; font-weight:bold; font-size:19px;" href="/?Res={{$_GET['Res']}}&t=1"> {{$theResInfo->emri}} Buchseite</a> -->
                            <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                        </div>
                    </div>
                @endif
            @endif
        @elseif(isset($_GET['Res']) && isset($_GET['RezId']) && $_GET['RezId'] == 0)
            <!-- THe status of this reservation cannot be changed  -->
            <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                <h2 class="text-center" style="color: red;"><strong> {{__('others.tableRezervationStatusCanNotBeChanged')}} </strong></h2>

                <div class="d-flex justify-content-between">
                    <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                </div>
            </div>

        @else
            <!-- Missing variables  -->
            <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                <h2 class="text-center" style="color: red;"><strong> {{__('others.tableRezervationAccessNotAllowed2')}} </strong></h2>

                <div class="d-flex justify-content-between">
                
                    <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                </div>
            </div>
        @endif

    </div>
    <script>
        if ((screen.width > 580)) {
            $('#infoTableRezDesktop').show();
        } 
    </script>











    <div id="infoTableRezSmartphone" style="display:none;">
        @if(isset($_GET['Res']) && isset($_GET['RezId']) && isset($_GET['hash']))
            <?php
                $theResInfo = Restorant::find($_GET['Res']);
                $theReseservationInfo = TableReservation::find($_GET['RezId']);
            ?>
            @if($theReseservationInfo == NULL)
                <!-- Table reservation request does not exist -->
                <!-- THe status of this reservation cannot be changed  -->
                <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                    <h2 class="text-center" style="color: red;"><strong> {{__('others.tableRezervationDoesNotExist')}} </strong></h2>

                    <div class="d-flex justify-content-between">
                        <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                    </div>
                </div>
            @elseif($theResInfo == NULL)
                <!-- Table reservation request does not exist -->
                <!-- THe status of this reservation cannot be changed  -->
                <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                    <h2 class="text-center" style="color: red;"><strong> {{__('others.tableRezervationRestaurantNotFound')}} </strong></h2>

                    <div class="d-flex justify-content-between">
                        <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                    </div>
                </div>
            @else
     
                <?php
                    $dita2D = explode('-',$theReseservationInfo->dita);
                ?>
                @if($_GET['hash'] == $theReseservationInfo->idnHash)

                    <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                        @if($theReseservationInfo->status == 1)
                            <h2 style="color: rgb(39,190,175);"><strong> {{__('others.confirmTableRezervation')}} 👍 </strong></h2>
                        @elseif($theReseservationInfo->status == 2)
                            <h2 style="color: red"><strong> {{__('others.declineTableRezervation')}} </strong></h2>
                        @endif

                        <h4>{{__('others.detailForTableReservation')}}</h4>
                        <table style="width:100%; font-size:15px;" class="table-hover table-bordered"> 
                            <tr>
                                <th>{{__('others.ClienNameLastname')}}</th>
                                <td>{{$theReseservationInfo->emri}} {{$theReseservationInfo->mbiemri}}</td>
                            </tr>
                            <tr>
                                <th>{{__('others.Restaurant')}}</th>
                                <td>{{Restorant::find($theReseservationInfo->toRes)->emri}}</td>
                            </tr>
                            <tr>
                                <th>{{__('others.Table')}}</th>
                                <td>{{$theReseservationInfo->tableNr}}</td>
                            </tr>
                            <tr>
                                <th>{{__('others.nrOfPersons')}} (max)</th>
                                <td>{{$theReseservationInfo->persona}} ( X )</td>
                            </tr>
                            <tr>
                                <th>{{__('others.Date')}}</th>
                                <td>{{$dita2D[2]}} / {{$dita2D[1]}} / {{$dita2D[0]}}</td>
                            </tr>
                            <tr>
                                <th>{{__('others.Time')}}</th>
                                <td>{{$theReseservationInfo->koha01}} => {{$theReseservationInfo->koha02}}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>{{__('others.phoneNr')}}:</strong> {{$theReseservationInfo->nrTel}}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>{{__('others.email')}}:</strong> {{$theReseservationInfo->email}}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>{{__('others.comment')}}:</strong> {{$theReseservationInfo->koment}}</td>
                            </tr>
                        </table>

                        <div class="d-flex justify-content-between">
                            <!-- <a class="p-3 mt-2 btn btn-info" style="width: 49%; font-weight:bold; font-size:19px;" href="/?Res={{$_GET['Res']}}&t=1"> {{$theResInfo->emri}} Buchseite</a> -->
                            <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                        </div>
                    </div>
                @else
                    <!-- HASH variable not valid -->
                    <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                        <h2 class="text-center" style="color: red;"><strong> {{__('others.tableRezervationAccessNotAllowed')}} </strong></h2>

                        <div class="d-flex justify-content-between">
                            <!-- <a class="p-3 mt-2 btn btn-info" style="width: 49%; font-weight:bold; font-size:19px;" href="/?Res={{$_GET['Res']}}&t=1"> {{$theResInfo->emri}} Buchseite</a> -->
                            <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                        </div>
                    </div>
                @endif
            @endif
            @elseif(isset($_GET['Res']) && isset($_GET['RezId']) && $_GET['RezId'] == 0)
            <!-- THe status of this reservation cannot be changed  -->
            <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                <h2 class="text-center" style="color: red;"><strong> {{__('others.tableRezervationStatusCanNotBeChanged')}} </strong></h2>

                <div class="d-flex justify-content-between">
                    <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                </div>
            </div>

            @else
            <!-- Missing variables  -->
            <div style="width: 100%; background-color:white; border-radius:30px;" class="p-4 mt-3">
                <h2 class="text-center" style="color: red;"><strong> {{__('others.tableRezervationAccessNotAllowed2')}} </strong></h2>

                <div class="d-flex justify-content-between">
                
                    <a class="p-3 mt-2 btn btn-info" style="width: 100%; font-weight:bold; font-size:19px;" href="/login"> {{__('others.LogIn')}} </a>
                </div>
            </div>
            @endif

    </div>
    <script>
        if ((screen.width <= 580)) {
            $('#infoTableRezSmartphone').show();
        } 
    </script>
@endsection