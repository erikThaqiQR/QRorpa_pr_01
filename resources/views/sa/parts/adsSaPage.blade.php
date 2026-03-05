<?php
    use App\adsMod;
    use App\adsActiveToRes;
    use App\adsRepeatInterval;
    use App\adsRestaurantGroup;
    use App\Restorant;
?>
<style>
    .btnQrorpa{
        font-weight: bold;
        font-size: 24px;
        color:rgb(39,190,175);
        border:1px solid rgb(39,190,175);
        border-radius: 5px;
    }
    .btnQrorpa:hover{
        font-weight: bold;
        font-size: 24px;
        color:white;
        background-color: rgb(39,190,175);
        border:1px solid rgb(39,190,175);
        border-radius: 5px;
    }

    .adType{
        border:1px solid rgb(72,81,87);
        border-radius: 10px;
        color: rgb(72,81,87);
        font-weight: bold;
    }
    .adTypeSelected{
        border:1px solid rgb(72,81,87);
        border-radius: 10px;
        color: white;
        background-color: rgb(72,81,87);
        font-weight: bold;
    }
    .adType:hover{
        cursor: pointer;
    }

    .adResClickable:hover{
        cursor: pointer;
    }

    .hoverInfo:hover{
        cursor: help;
    }

    .hoverPointer:hover{
        cursor: pointer;
    }




    .selectResGr:hover{
        background-color: rgb(39,190,175);
        color: white;
        cursor: pointer;
    }

</style>
<section class="pl-3 pr-3 mb-5">
    <h3 class="pl-3" style="color:rgb(39,190,175);"><strong>Anzeigen</strong></h3>
    <hr>

    <div class="d-flex justify-content-between">
        <button style="width:33%; font-size: 19px;" class="btn btnQrorpa" data-toggle="modal" data-target="#addAd"><i class="fab fa-adversal"></i> Registrieren Sie eine neue Anzeige</button>
        <button style="width:33%; font-size: 19px;" class="btn btnQrorpa" data-toggle="modal" data-target="#adRepeatModal"><i class="fas fa-redo-alt"></i> Restaurants und Wiederholungszeitraum</button>
        <button style="width:33%; font-size: 19px;" class="btn btnQrorpa" data-toggle="modal" data-target="#adResGroupsModal"><i class="fas fa-users"></i> Restaurantgruppen</button>
    </div>
    
 
    <div class="mt-4" id="allADs">
        @foreach (adsMod::all() as $ads)
            <div class="shadow d-flex flex-wrap justify-content-between p-2 mb-3" style="border-radius:20px;" id="adLine{{$ads->id}}"> 

                <div style="width:25%">
                    <div id="adDeleteLoading{{$ads->id}}">
                        <i class="far fa-2x btn  fa-minus-square" onclick="deleteAd('{{$ads->id}}')"></i> 
                    </div>
                    <div class="d-flex">
                        <p style="font-size:24px; padding-top:10px; color:rgb(72,81,87); width:20%;" class="pl-2"><strong><i class="far fa-eye"></i> {{$ads->adRepNr}}</strong></p>
                        <p style="font-size:24px; padding-top:10px; color:rgb(72,81,87); width:80%;" class="text-center"><strong>
                            @if($ads->tipi == 1)
                                Product Ad <sup class="hoverInfo" ><i class="fas fa-sm fa-info-circle" data-toggle="tooltip" data-placement="top" title="Öffnen Sie das Produkt, das diesem Namen entspricht!"></i></sup>
                            @elseif ($ads->tipi == 2)
                                Link Ad
                            @elseif ($ads->tipi == 3)
                                Info Ad
                            @elseif ($ads->tipi == 4)
                                Kategorie Ad
                            @endif
                        </strong></p>
                    </div>

                    <div id="adRepearOnOffLoading{{$ads->id}}">
                        @if($ads->repeatableAd == 1)
                            <i style="color:rgb(39,190,175);" class="fas fa-2x fa-redo-alt btn" onclick="changeRepeatableStat('{{$ads->id}}')"></i>
                        @else
                            <i style="color:red;" class="fas fa-2x fa-redo-alt btn" onclick="changeRepeatableStat('{{$ads->id}}')"></i>
                        @endif
                    </div>
                  
                </div>
                <div style="width:11%" class="text-center"> 
                    <img src="storage/restaurantADS/{{$ads->foto}}"  style="width:auto; height:130px;"   alt="">
                </div>

                <div style="width:25%"> 
                    @if($ads->tipi == 1)
                        <p style="font-size:24px; padding-top:43px; color:rgb(72,81,87);" class="text-center">
                            <strong> {{$ads->emri}} </strong>
                        </p>
                    @elseif ($ads->tipi == 2)
                        <p style="font-size:24px; padding-top:43px; color:rgb(72,81,87);" class="text-center">
                            <strong> {{$ads->linku}} </strong>
                        </p>
                    @elseif ($ads->tipi == 3)
                        <p style="font-size:16px; padding-top:10px; color:rgb(72,81,87);" class="text-center">
                            <strong> {{$ads->informata}} </strong>
                        </p>
                    @elseif ($ads->tipi == 4)
                        <p style="font-size:24px; padding-top:43px; color:rgb(72,81,87);" class="text-center">
                            <strong> {{$ads->catEmri}} </strong>
                        </p>
                    @endif
                </div>

                <div style="width:25%" class="text-center"> 
                    @if($ads->grSelected != 0)
                    <p style="font-size:24px; padding-top:23px; color:rgb(72,81,87);" class="text-center btn" data-toggle="modal" data-target="#resToAdSelectionModal{{$ads->id}}">
                    @else
                    <p style="font-size:24px; padding-top:43px; color:rgb(72,81,87);" class="text-center btn" data-toggle="modal" data-target="#resToAdSelectionModal{{$ads->id}}">
                    @endif
                        <strong>Restaurant auswahlen <span style="color:rgb(39,190,175);">( {{adsActiveToRes::where('adID',$ads->id)->get()->count()}} <i class="fas fa-utensils"></i> )</span></strong>
                    </p>
                    @if($ads->grSelected != 0)
                    <p style="font-size:21px; margin-top:-15px; color:rgb(39,190,175);" class="text-center ">
                        <strong> Gruppe 
                            @if(adsRestaurantGroup::find($ads->grSelected) != NULL)
                                <span style="color:rgb(72,81,87);">{{adsRestaurantGroup::find($ads->grSelected)->groupName}}</span>
                                <i onclick="unsubAdNgaGrupi('{{$ads->grSelected}}','{{$ads->id}}')" style="color:red;" class="fas fa-lg fa-ban btn"></i>
                            @else
                                <span style="color:rgb(72,81,87);">Gruppe gelöscht</span>
                            @endif
                        </strong>
                    </p>
                    @endif
                </div>

                <div style="width:12%" class="text-center"> 
                    <button style="font-weight: bold;" class="btn btn-block btn-default" onclick="changeTheAdAvailability('res','{{$ads->id}}')">
                        @if ($ads->resActive == 1)
                            <i style="color:green;" class="fas fa-check-square"></i> 
                        @endif
                        Restaurant
                    </button>
                    <button style="font-weight: bold;" class="btn btn-block btn-default" onclick="changeTheAdAvailability('del','{{$ads->id}}')">
                        @if ($ads->delActive == 1)
                            <i style="color:green;" class="fas fa-check-square"></i> 
                        @endif
                        Delivery
                    </button>
                    <button style="font-weight: bold;" class="btn btn-block btn-default" onclick="changeTheAdAvailability('tak','{{$ads->id}}')">
                        @if ($ads->takActive == 1)
                            <i style="color:green;" class="fas fa-check-square"></i> 
                        @endif
                        Takeaway
                    </button>
                </div>

                <script>
                    function unsubAdNgaGrupi(grId,adId){
                        $.ajax({
                            url: '{{ route("adsModuleSa.unsubAdNgaGrupi") }}',
                            method: 'post',
                            data: {
                                grI: grId,
                                adI: adId,
                                _token: '{{csrf_token()}}'
                            },
                            success: () => {
                                $("#adLine"+adId).load(location.href+" #adLine"+adId+">*","");
                            },
                            error: (error) => {
                                console.log(error);
                                alert('bitte aktualisieren und erneut versuchen!');
                            }
                        });
                    }
                    function changeTheAdAvailability(chType, adId){
                        $.ajax({
                            url: '{{ route("adsModuleSa.changeTheAdAvailability") }}',
                            method: 'post',
                            data: {
                                chT: chType,
                                adI: adId,
                                _token: '{{csrf_token()}}'
                            },
                            success: () => {
                                $("#adLine"+adId).load(location.href+" #adLine"+adId+">*","");
                            },
                            error: (error) => {
                                console.log(error);
                                alert('bitte aktualisieren und erneut versuchen!');
                            }
                        });
                    }
                </script>

            </div>


                                            <!-- Select restaurant to ads Modal  -->
                                            <div class="modal resToAdSelectionModalAll" id="resToAdSelectionModal{{$ads->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                                                style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header" style="background-color:rgb(39,190,175);">
                                                            <h4 style="color:white;" class="modal-title"><strong>
                                                                @if($ads->tipi == 1)
                                                                    " Product Ad " {{$ads->emri}}
                                                                @elseif ($ads->tipi == 2)
                                                                    " Link Ad " {{$ads->linku}}
                                                                @elseif ($ads->tipi == 3)
                                                                    " Info Ad " {{$ads->informata}}
                                                                @endif
                                                                </strong></h4>
                                                            <button style="color:white;" type="button" class="close" data-dismiss="modal"><strong>X</strong></button>
                                                        </div>

                                                
                                                        <!-- Modal body -->
                                                        <div class="modal-body " id="resToAdSelectionModalBody{{$ads->id}}" >
                                                            <div class=" d-flex flex-wrap justify-content-start" id="adRestaurantGroups{{$ads->id}}" style="display:none !important;">
                                                                <button class="btn btn-block btn-outline-dark mt-2 mb-4" onclick="showRestaurantsSelect('{{$ads->id}}')"
                                                                    style="font-size:24px;"><strong>Restaurants zeigen</strong></button>
                                                                @foreach (adsRestaurantGroup::all() as $resGr)
                                                                    <div style="width:100%; border:1px solid rgb(72,81,87,0.4); border-radius:20px;" 
                                                                        class="shadow p-3 mb-2 selectResGr" onclick="selectResGrForTheAd('{{$ads->id}}','{{$resGr->id}}')">
                                                                        <p>Name der Restaurantgruppe: <strong style="font-size:21px;">{{$resGr->groupName}}</strong></p>
                                                                        <div class="d-flex flex-wrap justify-content-start">
                                                                            @foreach (explode('||',$resGr->restaurants) as $inGrRes)
                                                                                <div style="width:24.8%; margin-right:0.2%; border:1px solid rgb(72,81,87,0.4);
                                                                                 border-radius:10px; background-color:white; color:rgb(72,81,87);" class="p-2 mb-1 text-center">
                                                                                    {{Restorant::find($inGrRes)->emri}}
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>  
                                                                @endforeach
                                                            </div>
                            
                                                            <div class=" d-flex flex-wrap justify-content-start" id="adRestaurants{{$ads->id}}" style="display:block;">
                                                                <button class="btn btn-block btn-outline-dark mt-2 mb-4" onclick="showResGroupsSelect('{{$ads->id}}')"
                                                                    style="font-size:24px;"><strong>Restaurantgruppen zeigen</strong></button>

                                                                <div style="width:100%" class="d-flex justify-content-between">
                                                                    <button style="width:49%;" class="btn btnQrorpa" onclick="addAllResToAd('{{$ads->id}}')">Füge alle Hinzu</button>
                                                                    <button style="width:49%; color:red;  font-weight: bold; font-size: 24px;" onclick="removeAllResToAd('{{$ads->id}}')"
                                                                        class="btn btn-outline-danger">Alles entfernen</button>
                                                                </div>

                                                                <hr style="width:100%">
                                                                @foreach (Restorant::all()->sortByDesc('created_at') as $allRes )
                                                                    @if(adsActiveToRes::where([['adID',$ads->id],['resID',$allRes->id]])->first() != NULL)
                                                                        <?php $adResRecord = adsActiveToRes::where([['adID',$ads->id],['resID',$allRes->id]])->first(); ?>
                                                                        <div onclick="adToResRemove('{{$adResRecord->id}}','{{$allRes->id}}','{{$ads->id}}')" 
                                                                            style="width:33%; margin-right:0.333%; background-color:rgb(72,81,87); color:white; border:1px solid rgb(72,81,87); border-radius:5px;"
                                                                            class="mb-1 pt-2 pb-2 text-center adResClickable">
                                                                            <strong id="resToAd{{$allRes->id}}O{{$ads->id}}"> {{$allRes->id}}.{{$allRes->emri}} </strong>
                                                                        </div>
                                                                    @else
                                                                        <div onclick="adToResADD('{{$allRes->id}}','{{$ads->id}}')" style="width:33%; margin-right:0.333%; border:1px solid rgb(72,81,87); border-radius:5px;"
                                                                            class="mb-1 pt-2 pb-2 text-center adResClickable">
                                                                            <strong id="resToAd{{$allRes->id}}O{{$ads->id}}"> {{$allRes->id}}.{{$allRes->emri}} </strong>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                function showResGroupsSelect(adId){
                                                    $('#adRestaurants'+adId).attr('style','display:none !important;');
                                                    $('#adRestaurantGroups'+adId).attr('style','display:block;');
                                                }
                                                function showRestaurantsSelect(adId){
                                                    $('#adRestaurants'+adId).attr('style','display:block;');
                                                    $('#adRestaurantGroups'+adId).attr('style','display:none !important;');
                                                }

                                                function selectResGrForTheAd(adId, rgId){
                                                    $.ajax({
                                                        url: '{{ route("adsModuleSa.resGroupToAdSave") }}',
                                                        method: 'post',
                                                        data: {
                                                            adI: adId,
                                                            rgI: rgId,
                                                            _token: '{{csrf_token()}}'
                                                        },
                                                        success: () => {
                                                            $("#adLine"+adId).load(location.href+" #adLine"+adId+">*","");
                                                            $('#resToAdSelectionModal'+adId).modal("toggle");
                                                            $("#resToAdSelectionModal"+adId).load(location.href+" #resToAdSelectionModal"+adId+">*","");

                                                            
                                                        },
                                                        error: (error) => {
                                                            console.log(error);
                                                            alert('bitte aktualisieren und erneut versuchen!');
                                                        }
                                                    });
                                                }
                                            </script>


        @endforeach
    </div>
</section>

<script>
    function deleteAd(adsId){
        $('#adDeleteLoading'+adsId).html('<img style="width:40px;" class="pl-2" src="storage/gifs/loading2.gif" alt="">');
        $.ajax({
			url: '{{ route("adsModuleSa.adDestroy") }}',
			method: 'post',
			data: {
				adI: adsId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#allADs").load(location.href+" #allADs>*","");
			},
			error: (error) => {
				console.log(error);
				alert('bitte aktualisieren und erneut versuchen!');
			}
		});
    }


    function changeRepeatableStat(adId){

        $('#adRepearOnOffLoading'+adId).html('<img style="width:40px;" class="pl-2" src="storage/gifs/loading2.gif" alt="">');
        $.ajax({
			url: '{{ route("adsModuleSa.changeRepeatableStat") }}',
			method: 'post',
			data: {
				adI: adId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#adLine"+adId).load(location.href+" #adLine"+adId+">*","");
			},
			error: (error) => {console.log(error); alert('bitte aktualisieren und erneut versuchen!');}
		});
    }

    function adToResADD(resId, adId){
        $('#resToAd'+resId+'O'+adId).html('<img style="height:25px; width:auto;" src="storage/gifs/loading2.gif" alt="">');
        $.ajax({
			url: '{{ route("adsModuleSa.adToResAdd") }}',
			method: 'post',
			data: {
				resI: resId,
				adI: adId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#adRestaurants"+adId).load(location.href+" #adRestaurants"+adId+">*","");
                $("#adLine"+adId).load(location.href+" #adLine"+adId+">*","");
			},
			error: (error) => {console.log(error); alert('bitte aktualisieren und erneut versuchen!'); }
		});
    }

    function adToResRemove(adResId, resId, adId){
        $('#resToAd'+resId+'O'+adId).html('<img style="height:25px; width:auto;" src="storage/gifs/loading2.gif" alt="">');
        $.ajax({
			url: '{{ route("adsModuleSa.adToResRemove") }}',
			method: 'post',
			data: {
				adResI: adResId,
				_token: '{{csrf_token()}}'
			},
			success: () => { 
                $("#adRestaurants"+adId).load(location.href+" #adRestaurants"+adId+">*",""); 
                $("#adLine"+adId).load(location.href+" #adLine"+adId+">*","");
            },
			error: (error) => { console.log(error); alert('bitte aktualisieren und erneut versuchen!'); }
		});
    }





    function addAllResToAd(adId){
        $.ajax({
			url: '{{ route("adsModuleSa.adToAllResAdd") }}',
			method: 'post',
			data: {
				adI: adId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#adRestaurants"+adId).load(location.href+" #adRestaurants"+adId+">*","");
                $("#adLine"+adId).load(location.href+" #adLine"+adId+">*","");
            },
			error: (error) => {console.log(error); alert('bitte aktualisieren und erneut versuchen!'); }
		});
    }
    function removeAllResToAd(adId){
        $.ajax({
			url: '{{ route("adsModuleSa.adToAllResRemove") }}',
			method: 'post',
			data: {
				adI: adId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#adRestaurants"+adId).load(location.href+" #adRestaurants"+adId+">*","");
                $("#adLine"+adId).load(location.href+" #adLine"+adId+">*","");
            },
			error: (error) => {console.log(error); alert('bitte aktualisieren und erneut versuchen!'); }
		});
    }
</script>
































<!-- adResGroupsModal -->
<div class="modal" id="adResGroupsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

             <!-- Modal Header -->
             <div style="background-color: rgb(39,190,175);" class="modal-header">
                <h4 style="color:white;" class="modal-title">Restaurantgruppen</h4>
                <button style="color:white;" type="button" class="close" data-dismiss="modal">X</button>
            </div>

              <!-- Modal body -->
              <div class="modal-body">
                <div class="d-flex flex-wrap justify-content-start">
                    <div class="alert alert-danger textcenter p-2" id="restaurantGroupError01" style="display: none; width:100%;"></div>
                    <input style="width:49%; margin-right:2%; border:1px solid rgb(39,190,175);" class="mb-2 p-2" type="text" 
                        placeholder="Gruppenname" id="resGroupName">
                    <button style="width:49%;" class="btn btnQrorpa mb-2" onclick="saveTheRestaurantsGroup()">Sparen</button>
                    <input type="hidden" value="" id="resToSaveGroup">
                    @foreach (Restorant::all()->sortByDesc('created_at') as $allRes )
                        <button id="resForGroupAd{{$allRes->id}}" style="width:24.5%; margin-right:0.5%;" class="p-2 mb-2 btn btn-outline-dark" 
                            onclick="selectResForGroupAd('{{$allRes->id}}')">{{$allRes->emri}}</button>
                        <input type="hidden" id="restaurantGrSelected{{$allRes->id}}" value="0">
                    @endforeach
                    <hr style="width:100%">
                    @foreach (adsRestaurantGroup::all() as $resGr)
                        <div style="width:100%; border:1px solid rgb(72,81,87,0.4); border-radius:20px;" class="shadow p-3 mb-2">
                            <div class="d-flex justify-content-between">
                                <p style="width:75%;">Name der Restaurantgruppe: <strong style="font-size:21px;">{{$resGr->groupName}}</strong></p>
                                <p style="width:25%;" class="text-right hoverPointer" onclick="deleteThisResGroup('{{$resGr->id}}')">
                                    <i style="color:red;" class="fas fa-2x fa-trash-alt"></i>
                                </p>
                            </div>
                        
                            <div class="d-flex flex-wrap justify-content-start">
                                @foreach (explode('||',$resGr->restaurants) as $inGrRes)
                                    <div style="width:24.8%; margin-right:0.2%; border:1px solid rgb(72,81,87,0.4); border-radius:10px;" class="p-2 mb-1 text-center">
                                        {{Restorant::find($inGrRes)->emri}}
                                    </div>
                                @endforeach
                            </div>
                        </div>  
                    @endforeach
                </div>
              </div>

        </div>
    </div>
</div>
<script>
    function saveTheRestaurantsGroup(){
        if($('#resToSaveGroup').val() == ""){
            $('#restaurantGroupError01').html('Bitte wählen Sie mindestens ein Restaurant aus!');
            $('#restaurantGroupError01').show('1').delay(3500).hide(1);
        }else if($('#resGroupName').val() == ""){
            $('#restaurantGroupError01').html('Schreiben Sie den Namen der Restaurantgruppe!');
            $('#restaurantGroupError01').show('1').delay(3500).hide(1);
        }else{
            $.ajax({
                url: '{{ route("adsModuleSa.saveResGroup") }}',
                method: 'post',
                data: {
                    resToGroup: $('#resToSaveGroup').val(),
                    resGroupName: $('#resGroupName').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => { 
                    $("#adResGroupsModal").load(location.href+" #adResGroupsModal>*","");
                    $("#allADs").load(location.href+" #allADs>*","");
                },
                error: (error) => { console.log(error); alert('bitte aktualisieren und erneut versuchen!');}
            });
        }
    }

    function selectResForGroupAd(resId){
        if($('#restaurantGrSelected'+resId).val() == '0'){
            $('#resForGroupAd'+resId).attr('class','p-2 mb-2 btn btn-dark');
            if($('#resToSaveGroup').val() == ''){
                $('#resToSaveGroup').val(resId);
            }else{
                $('#resToSaveGroup').val($('#resToSaveGroup').val()+'||'+resId);
            }
            $('#restaurantGrSelected'+resId).val('1');
        }else if($('#restaurantGrSelected'+resId).val() == '1'){
            $('#resForGroupAd'+resId).attr('class','p-2 mb-2 btn btn-outline-dark');
            if($('#resToSaveGroup').val() == resId){
                $('#resToSaveGroup').val('');
            }else{
                var rSel = $('#resToSaveGroup').val();
                var rSel2D = rSel.split('||');
                $('#resToSaveGroup').val('');
                $.each(rSel2D, function (key, val) {
                    // alert(val);
                    if(val != resId){
                        if($('#resToSaveGroup').val() == ''){
                            $('#resToSaveGroup').val(val);
                        }else{
                            $('#resToSaveGroup').val($('#resToSaveGroup').val()+'||'+val);
                        }
                    }
                });
                // re render 
            }
            $('#restaurantGrSelected'+resId).val('0');
        }
    }

    function deleteThisResGroup(adResGrId){
        $.ajax({
            url: '{{ route("adsModuleSa.deleteResGroup") }}',
            method: 'post',
            data: {
                rgI: adResGrId,
                _token: '{{csrf_token()}}'
            },
            success: () => { $("#adResGroupsModal").load(location.href+" #adResGroupsModal>*",""); },
            error: (error) => { console.log(error); alert('bitte aktualisieren und erneut versuchen!');}
        });
    }
</script>
































<!-- The adRepeatModal Modal -->
<div class="modal" id="adRepeatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div style="background-color: rgb(39,190,175);" class="modal-header">
                <h4 style="color:white;" class="modal-title">Restaurants und Wiederholungszeitraum</h4>
                <button style="color:white;" type="button" class="close" data-dismiss="modal">X</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="d-flex flex-wrap justify-content-start">
                    <div class="alert alert-danger textcenter p-2" id="adRepeatModalError01" style="display: none; width:100%;"></div>
                    <input style="width:49%; margin-right:2%; border:1px solid rgb(39,190,175);" class="mb-2" type="number" placeholder="Wiederholen Sie nach X Sekunden" id="secondsFor">
                    <button style="width:49%;" class="btn btnQrorpa mb-2" onclick="saveTheadRepeatRestaurants()">Sparen</button>
                    <input type="hidden" value="" id="resToSaveRepeat">
                    @foreach (Restorant::all()->sortByDesc('created_at') as $allRes )
                        @if (adsRepeatInterval::where('toRes',$allRes->id)->first() == NULL)
                            <button id="resForRepeatAd{{$allRes->id}}" style="width:24.5%; margin-right:0.5%;" class="p-2 mb-2 btn btn-outline-dark" 
                                onclick="selectResForRepeatAd('{{$allRes->id}}')">{{$allRes->emri}}</button>
                            <input type="hidden" id="resAdRepeatSelected{{$allRes->id}}" value="0">
                        @endif
                    @endforeach
                </div>
                <hr>
                @foreach (adsRepeatInterval::all() as $adRepeat)
                    <div class="d-flex flex-wrap justify-content-between">
                        <i style="width:10%; border-bottom:1px solid rgb(72,81,87,0.4); color:red;" class="far fa-2x fa-trash-alt mb-2 btn" onclick="deleteadRepRes('{{$adRepeat->id}}')"></i>
                        <p style="width:45%; border-bottom:1px solid rgb(72,81,87,0.4); font-size:20px;" class="text-center mb-2"><strong>{{Restorant::find($adRepeat->toRes)->emri}}</strong></p> 
                        <p style="width:45%; border-bottom:1px solid rgb(72,81,87,0.4); font-size:20px;" class="text-center mb-2"><strong>{{$adRepeat->forSec}} sekunden</strong></p> 
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

<script>
    function deleteadRepRes(adRepRes){
        $.ajax({
			url: '{{ route("adsModuleSa.deleteTheadRepeatRestaurants") }}',
			method: 'post',
			data: {
				adRepResI: adRepRes,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#adRepeatModal").load(location.href+" #adRepeatModal>*","");
			},
			error: (error) => { console.log(error); alert('bitte aktualisieren und erneut versuchen!'); }
		});
    }

    function selectResForRepeatAd(resId){
        if($('#resAdRepeatSelected'+resId).val() == '0'){
            $('#resForRepeatAd'+resId).attr('class','p-2 mb-2 btn btn-dark');
            if($('#resToSaveRepeat').val() == ''){
                $('#resToSaveRepeat').val(resId);
            }else{
                $('#resToSaveRepeat').val($('#resToSaveRepeat').val()+'||'+resId);
            }
            $('#resAdRepeatSelected'+resId).val('1');
        }else if($('#resAdRepeatSelected'+resId).val() == '1'){
            $('#resForRepeatAd'+resId).attr('class','p-2 mb-2 btn btn-outline-dark');
            if($('#resToSaveRepeat').val() == resId){
                $('#resToSaveRepeat').val('');
            }else{
                var rSel = $('#resToSaveRepeat').val();
                var rSel2D = rSel.split('||');
                $('#resToSaveRepeat').val('');
                $.each(rSel2D, function (key, val) {
                    // alert(val);
                    if(val != resId){
                        if($('#resToSaveRepeat').val() == ''){
                            $('#resToSaveRepeat').val(val);
                        }else{
                            $('#resToSaveRepeat').val($('#resToSaveRepeat').val()+'||'+val);
                        }
                    }
                });
                // re render 
            }
            $('#resAdRepeatSelected'+resId).val('0');
        }
    }

    function saveTheadRepeatRestaurants(){
        if($('#resToSaveRepeat').val() == ''){
            $('#adRepeatModalError01').html('Wählen Sie mindestens ein Restaurant aus');
            $('#adRepeatModalError01').show(1).delay(3500).hide(1);
        }else if($('#secondsFor').val() == ''){
            $('#adRepeatModalError01').html('Schreiben Sie die Sekunden für die Wiederholung der Anzeige');
            $('#adRepeatModalError01').show(1).delay(3500).hide(1);
        }else{
            $.ajax({
                url: '{{ route("adsModuleSa.saveTheadRepeatRestaurants") }}',
                method: 'post',
                data: {
                    restaurants: $('#resToSaveRepeat').val(),
                    seconds: $('#secondsFor').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#adRepeatModal").load(location.href+" #adRepeatModal>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert('bitte aktualisieren und erneut versuchen!');
                }
            });
        }
    }
</script>






































<!-- The addAd Modal -->
<div class="modal" id="addAd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div style="background-color:rgb(39,190,175);" class="modal-header">
        <h4 style="color:white;" class="modal-title"> <strong>neue Anzeige</strong> </h4>
        <button style="color:white; opacity:1;" type="button" class="close" data-dismiss="modal"><strong>X</strong></button>
      </div>

      <!-- Modal body -->
        <div class="modal-body">
            <div class="d-flex justify-content-between">
                <div id="adTypeBtn1" style="width:24.9%;" class="shadow p-2 text-center adType" onclick="showAdRegForm('1')">
                    Product AD
                </div>
                <div id="adTypeBtn4" style="width:24.9%;" class="shadow p-2 text-center adType" onclick="showAdRegForm('4')">
                    Kategorie AD
                </div>
                <div id="adTypeBtn2" style="width:24.9%;" class="shadow p-2 text-center adType" onclick="showAdRegForm('2')">
                    Link AD
                </div>
                <div id="adTypeBtn3" style="width:24.9%;" class="shadow p-2 text-center adType" onclick="showAdRegForm('3')">
                    Info AD
                </div>
            </div>
            <hr>

            <div id="addAdProduct" style="display:none;" class="shadow p-1">
                {{Form::open(['action' => 'adsController@storeProduct', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}
                    <div class="d-flex flex-wrap justify-content-between">
                        <p style="width:100%; font-size:24px; color:rgb(72,81,87);" class="text-center p-2"><strong>Product AD</strong></p>

                        <div style="width:47%;" class="form-group">
                            {{ Form::text('emri','', ['class' => 'form-control ', 'placeholder' => 'Product name', 'required']) }}
                        </div>

                        <div style="width:47%;" class="custom-file">
                            {{ Form::label('Produktbild (png)', null , ['class' => 'custom-file-label']) }}
                            {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile', 'required']) }}
                        </div>
                        
                        <div style="width:100%;">
                            {{ Form::submit('Save', ['class' => 'form-control btn btn-block btnQrorpa']) }}
                        </div>
                    </div>
                {{Form::close()}}
            </div>

            
            <div id="addAdLink" style="display:none;" class="shadow p-1">
                {{Form::open(['action' => 'adsController@storeLink', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}
                    <div class="d-flex flex-wrap justify-content-between">
                        <p style="width:100%; font-size:24px; color:rgb(72,81,87);" class="text-center p-2"><strong> Link AD </strong></p>

                        <div style="width:47%;" class="form-group">
                            {{ Form::text('linku','', ['class' => 'form-control ', 'placeholder' => 'Link']) }}
                        </div>

                        <div style="width:47%;" class="custom-file">
                            {{ Form::label('Bild (png)', null , ['class' => 'custom-file-label']) }}
                            {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                        </div>
                        
                        <div style="width:100%;">
                            {{ Form::submit('Save', ['class' => 'form-control btn btn-block btnQrorpa']) }}
                        </div>
                    </div>
                {{Form::close()}}
            </div>

            <div id="addAdInfo" style="display:none;" class="shadow p-1">
                {{Form::open(['action' => 'adsController@storeInfo', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}
                    <div class="d-flex flex-wrap justify-content-between">
                        <p style="width:100%; font-size:24px; color:rgb(72,81,87);" class="text-center p-2"><strong> Info AD </strong></p>

                        <div style="width:47%;" class="form-group">
                            {{ Form::text('teksti','', ['class' => 'form-control ', 'style'=>'width:100%', 'row' => '2', 'placeholder' => 'Beschreibungstext']) }}
                        </div>

                        <div style="width:47%;" class="custom-file">
                            {{ Form::label('Bild (png)', null , ['class' => 'custom-file-label']) }}
                            {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                        </div>
                        
                        <div style="width:100%;">
                            {{ Form::submit('Save', ['class' => 'form-control btn btn-block btnQrorpa']) }}
                        </div>
                    </div>
                {{Form::close()}}
            </div>

            <div id="addAdCategory" style="display:none;" class="shadow p-1">
                {{Form::open(['action' => 'adsController@storeCategory', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}
                        <div class="d-flex flex-wrap justify-content-between">
                            <p style="width:100%; font-size:24px; color:rgb(72,81,87);" class="text-center p-2"><strong> Kategorie AD </strong></p>

                            <div style="width:47%;" class="form-group">
                                {{ Form::text('kategoriEmri','', ['class' => 'form-control ', 'style'=>'width:100%', 'row' => '2', 'placeholder' => 'Kategoriename']) }}
                            </div>

                            <div style="width:47%;" class="custom-file">
                                {{ Form::label('Bild (png)', null , ['class' => 'custom-file-label']) }}
                                {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                            </div>
                            
                            <div style="width:100%;">
                                {{ Form::submit('Save', ['class' => 'form-control btn btn-block btnQrorpa']) }}
                            </div>
                        </div>
                    {{Form::close()}}
            </div>
            
        </div>
    </div>
  </div>
</div>
                        <script>
                            // Add the following code if you want the name of the file appear on select
                            $(".custom-file-input").on("change", function() {
                            var fileName = $(this).val().split("\\").pop();
                            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                            });
                        </script>



<script>
    function showAdRegForm(typeNr){
        $('#addAdProduct').hide(200);
        $('#addAdLink').hide(200);
        $('#addAdInfo').hide(200);
        $('#addAdCategory').hide(200);
        $('.adTypeSelected').attr('class','shadow p-2 text-center adType');
        if(typeNr == '1'){
            $('#addAdProduct').show(200);
            $('#adTypeBtn1').attr('class','shadow p-2 text-center adTypeSelected');
        }else if(typeNr == '2'){
            $('#addAdLink').show(200);
            $('#adTypeBtn2').attr('class','shadow p-2 text-center adTypeSelected');
        }else if(typeNr == '3'){
            $('#addAdInfo').show(200);
            $('#adTypeBtn3').attr('class','shadow p-2 text-center adTypeSelected');
        }else if(typeNr == '4'){
            $('#addAdCategory').show(200);
            $('#adTypeBtn4').attr('class','shadow p-2 text-center adTypeSelected');
        }

    }
</script>