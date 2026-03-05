<?php

use App\kategori;
use App\Takeaway;
use App\Produktet;
  use App\Restorant;
  use App\checkInOutReg;
  use App\accessControllForAdmins;
  use Illuminate\Support\Facades\Auth;
    use App\Orders;
    use Carbon\Carbon;

    $thisRestaurantId = Auth::user()->sFor;
    $thisRestaurant = Restorant::find(Auth::user()->sFor);

    echo '<input type="hidden" id="thisResId" value="'.Auth::user()->sFor.'">';

    $nowDate = date('Y-m-d');

    $ordersLi = Orders::where('Restaurant', '=', $thisRestaurantId)->where([['statusi', '=', 0],['nrTable','!=',500],['nrTable','!=',9000]])
    ->whereDate('created_at', Carbon::today())->whereIn('nrTable',$myTablesWaiter)->get()->count();

    $usrChkIn = checkInOutReg::where([['theStat',0],['userId',Auth::user()->id]])->first();
?>
<input type="hidden" id="theResAdminId" value="{{$thisRestaurantId}}">
<style>
  .openNotifications:hover{
    cursor:pointer;
  }


  @keyframes glowing {
  0% { box-shadow: 0 0 -10px red; }
  40% { box-shadow: 0 0 20px red; }
  60% { box-shadow: 0 0 20px red; }
  100% { box-shadow: 0 0 -10px red; }
}

.button-glow {
  animation: glowing 1000ms infinite;
}
.fingerPointer:hover{
  cursor: pointer;
}
</style>

<div id="addTypePlease" style="top: 12%; width: 100%; position: fixed; z-index: 100000; display: none;">
    <div class="text-center" style="background-color:rgb(39, 190, 175); color:white;  padding-top:15px; padding-bottom:15px; border-radius:9px;">
        Bitte Typ auswählen!
    </div>
</div>



<!-- Modal -->
<div class="modal" id="openCloseResModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding: 0px 0px 0px 0px;" aria-modal="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="openCloseResModalLabel"><strong>Restaurant, Takeaway, Lieferung (Geöffnet / Geschlossen)</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
      </div>
      <div class="modal-body" id="openCloseResModalBody">
        @php
          $isOp = explode('-||-',$thisRestaurant->isOpen);
        @endphp
        <div class="d-flex flex-wrap justify-content-between" id="openCloseResModalBodyDiv1">
          <!-- <button class="btn btn-success shadow-none" style="width:49%; margin:0px;"><strong>Der Service ist geöffnet</strong></button>
          <button class="btn btn-danger shadow-none" style="width:49%; margin:0px;"><strong>Der Service ist geschlossen</strong></button> -->

          <button style="width:100%;" class="btn btn-outline-info shadow-none mb-1" onclick="openProdONOFF()">
            <strong>RESTAURANT | Produkte für den Kunden ausblenden/anzeigen</strong>
          </button>
          <button style="width:100%;" class="btn btn-outline-info shadow-none" onclick="openProdONOFFTA()">
            <strong>TAKEAWAY | Produkte für den Kunden ausblenden/anzeigen</strong>
          </button>

          <hr style="width:100%" class="mt-2 mb-2">

          <p style="width:100%; font-size:1.4rem; color:rgb(39,190,175); margin:10px 0px 10px 0px;" class="text-center">
            <strong>Klicken Sie darauf, um den Status zu ändern</strong>
          </p>

          @if ( $isOp[0] == 1)
            <button class="btn btn-success shadow-none" style="width:30%;" onclick="chngResOpenStatus('Res','0')">Restaurant</button>
          @elseif ( $isOp[0] == 0)
            <button class="btn btn-danger shadow-none" style="width:30%;" onclick="chngResOpenStatus('Res','1')">Restaurant</button>
          @endif

          @if ( $isOp[1] == 1)
            <button class="btn btn-success shadow-none" style="width:30%;" onclick="chngResOpenStatus('TA','0')">Takeaway</button>
          @elseif ( $isOp[1] == 0)
            <button class="btn btn-danger shadow-none" style="width:30%;" onclick="chngResOpenStatus('TA','1')">Takeaway</button>
          @endif

          @if ( $isOp[2] == 1)
            <button class="btn btn-success shadow-none" style="width:30%;" onclick="chngResOpenStatus('DE','0')">Delivery</button>
          @elseif ( $isOp[2] == 0)
            <button class="btn btn-danger shadow-none" style="width:30%;" onclick="chngResOpenStatus('DE','1')">Delivery</button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Products on / off Modal -->
<div class="modal" id="productsOnOffModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding: 0px 0px 0px 0px;" aria-modal="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="productsOnOffModalLabel"><strong>RESTAURANT | Produkte für den Kunden ausblenden/anzeigen</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
      </div>
      <div class="modal-body" id="productsOnOffModalBody">
        <?php $firstCatId = 0; ?>
        <div class="d-flex flex-wrap justify-content-between">
          <div style="width:30%; border-right:1px solid black;" class="d-flex flex-wrap justify-content-between">
            @foreach (kategori::where('toRes',Auth::user()->sFor)->get() as $katOne)
              <img class="mb-1 fingerPointer" style="width:25%; height:40px;" src="storage/kategoriaUpload/{{$katOne->foto}}" onclick="productsOnOffCatBtnSelectCat('{{$katOne->id}}')" alt="">
              @if($loop->first)
                <?php $firstCatId = $katOne->id; ?>
                <p class="mb-1 pl-2 pt-2 fingerPointer productsOnOffAllCatBtn" id="productsOnOffCatBtn{{$katOne->id}}" onclick="productsOnOffCatBtnSelectCat('{{$katOne->id}}')"
                style="font-size:1.1rem; width:75%; background-color:rgba(40,167,69,0.5); color:rgb(72,81,87);"><strong>{{$katOne->emri}}</strong></p>
              @else
                <p class="mb-1 pl-2 pt-2 fingerPointer productsOnOffAllCatBtn" id="productsOnOffCatBtn{{$katOne->id}}" onclick="productsOnOffCatBtnSelectCat('{{$katOne->id}}')"
                style="font-size:1.1rem; width:75%; color:rgb(72,81,87);"><strong>{{$katOne->emri}}</strong></p>
              @endif

            @endforeach
          </div>
          <div class="d-flex flex-wrap justify-content-between" style="width:68%; height:fit-content;" id="productsOnOffBodyShowProds">
            @foreach (Produktet::where([['toRes',Auth::user()->sFor],['kategoria',$firstCatId]])->get() as $prodOne)
              @if ($prodOne->accessableByClients == 1)
              <div class="d-flex flex-wrap justify-content-between mb-2 p-1 fingerPointer" id="productsOnOffProdDiv{{$prodOne->id}}" onclick="productsOnOffChangeProdsStatus('{{$prodOne->id}}')"
              style="width:49%; height:fit-content; background-color:#CCFFCC; border: 1px solid rgb(72,81,87); border-radius:3px;">
              @else
              <div class="d-flex flex-wrap justify-content-between mb-2 p-1 fingerPointer" id="productsOnOffProdDiv{{$prodOne->id}}" onclick="productsOnOffChangeProdsStatus('{{$prodOne->id}}')"
              style="width:49%; height:fit-content; background-color:#FFCCCC; border: 1px solid rgb(72,81,87); border-radius:3px;">
              @endif
                  <p style="width:75%; margin:0;"><strong>{{$prodOne->emri}}</strong></p>
                  <p style="width:25%; margin:0; text-align:center;"><strong>{{$prodOne->qmimi}} CHF</strong></p>
                  <p style="width:100%; margin:0; font-size:0.7rem; color:rgba(72,81,87,0.5);"><strong>{{$prodOne->pershkrimi}}</strong></p>
              </div>
            @endforeach
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="productsOnOffTAModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding: 0px 0px 0px 0px;" aria-modal="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="productsOnOffModalLabelTA"><strong>TAKEAWAY | Produkte für den Kunden ausblenden/anzeigen</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
      </div>
      <div class="modal-body" id="productsOnOffModalBodyTA">
        <?php $firstCatId = 0; ?>
        <div class="d-flex flex-wrap justify-content-between">
          <div style="width:30%; border-right:1px solid black;" class="d-flex flex-wrap justify-content-between">
            @foreach (kategori::where('toRes',Auth::user()->sFor)->get() as $katOne)
              <img class="mb-1 fingerPointer" style="width:25%; height:40px;" src="storage/kategoriaUpload/{{$katOne->foto}}" onclick="productsOnOffCatBtnSelectCatTA('{{$katOne->id}}')" alt="">
              @if($loop->first)
                <?php $firstCatId = $katOne->id; ?>
                <p class="mb-1 pl-2 pt-2 fingerPointer productsOnOffAllCatBtnTA" id="productsOnOffCatBtnTA{{$katOne->id}}" onclick="productsOnOffCatBtnSelectCatTA('{{$katOne->id}}')"
                style="font-size:1.1rem; width:75%; background-color:rgba(40,167,69,0.5); color:rgb(72,81,87);"><strong>{{$katOne->emri}}</strong></p>
              @else
                <p class="mb-1 pl-2 pt-2 fingerPointer productsOnOffAllCatBtnTA" id="productsOnOffCatBtnTA{{$katOne->id}}" onclick="productsOnOffCatBtnSelectCatTA('{{$katOne->id}}')"
                style="font-size:1.1rem; width:75%; color:rgb(72,81,87);"><strong>{{$katOne->emri}}</strong></p>
              @endif

            @endforeach
          </div>
          <div class="d-flex flex-wrap justify-content-between" style="width:68%; height:fit-content;" id="productsOnOffBodyShowProdsTA">
            @foreach (Takeaway::where([['toRes',Auth::user()->sFor],['kategoria',$firstCatId]])->get() as $prodOne)
              @if ($prodOne->accessableByClients == 1)
              <div class="d-flex flex-wrap justify-content-between mb-2 p-1 fingerPointer" id="productsOnOffProdDivTA{{$prodOne->id}}" onclick="productsOnOffChangeProdsStatusTA('{{$prodOne->id}}')"
              style="width:49%; height:fit-content; background-color:#CCFFCC; border: 1px solid rgb(72,81,87); border-radius:3px;">
              @else
              <div class="d-flex flex-wrap justify-content-between mb-2 p-1 fingerPointer" id="productsOnOffProdDivTA{{$prodOne->id}}" onclick="productsOnOffChangeProdsStatusTA('{{$prodOne->id}}')"
              style="width:49%; height:fit-content; background-color:#FFCCCC; border: 1px solid rgb(72,81,87); border-radius:3px;">
              @endif
                  <p style="width:75%; margin:0;"><strong>{{$prodOne->emri}}</strong></p>
                  <p style="width:25%; margin:0; text-align:center;"><strong>{{$prodOne->qmimi}} CHF</strong></p>
                  <p style="width:100%; margin:0; font-size:0.7rem; color:rgba(72,81,87,0.5);"><strong>{{$prodOne->pershkrimi}}</strong></p>
              </div>
            @endforeach
          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<script>
  function chngResOpenStatus(service,newStat){
    $('#openCloseResModalBodyDiv1').html('<img src="storage/gifs/loading2.gif" style="width: 30%; margin-left:35%;" alt="">');
    $.ajax({
      url: '{{ route("dash.changeResOpenStatusTH") }}',
      method: 'post',
      data: {
        theS: service,
        newSt: newStat,
        _token: '{{csrf_token()}}'
      },
      success: () => {
        $("#openCloseResModalBody").load(location.href+" #openCloseResModalBody>*","");
      },
      error: (error) => { console.log(error); }
    });
  }



  function productsOnOffCatBtnSelectCat(catId){
    // productsOnOffBodyShowProds
    $('.productsOnOffAllCatBtn').attr('style','font-size:1.1rem; width:75%; color:rgb(72,81,87);');
    $('#productsOnOffCatBtn'+catId).attr('style','font-size:1.1rem; width:75%; background-color:rgba(40,167,69,0.5); color:rgb(72,81,87);');
    $.ajax({
			url: '{{ route("prodOnOff.callCatProds") }}',
			method: 'post',
			data: {
				catId: catId,
				_token: '{{csrf_token()}}'
			},
			success: (allProdsThisCat) => {
        // productsOnOffBodyShowProds
        allProdsThisCat = $.trim(allProdsThisCat);
        $('#productsOnOffBodyShowProds').html('');
        var addShowProd = '';
        var allProdsThisCat2D = allProdsThisCat.split('--99--');
        $.each(allProdsThisCat2D, function( index, oneProd ) {
          var oneProd2D = oneProd.split('--88--');

            if (oneProd2D[4] == 1){
              addShowProd = '<div class="d-flex flex-wrap justify-content-between mb-2 p-1 fingerPointer" id="productsOnOffProdDiv'+oneProd2D[0]+'" onclick="productsOnOffChangeProdsStatus(\''+oneProd2D[0]+'\')" style="width:49%; height:fit-content; background-color:#CCFFCC; border: 1px solid rgb(72,81,87); border-radius:3px;">';
            }else{
              addShowProd = '<div class="d-flex flex-wrap justify-content-between mb-2 p-1 fingerPointer" id="productsOnOffProdDiv'+oneProd2D[0]+'" onclick="productsOnOffChangeProdsStatus(\''+oneProd2D[0]+'\')" style="width:49%; height:fit-content; background-color:#FFCCCC; border: 1px solid rgb(72,81,87); border-radius:3px;">';
            }
            addShowProd +=    '<p style="width:75%; margin:0;"><strong>'+oneProd2D[1]+'</strong></p>'+
                              '<p style="width:25%; margin:0; text-align:center;"><strong>'+parseFloat(oneProd2D[2]).toFixed(2)+' CHF</strong></p>'+
                              '<p style="width:100%; margin:0; font-size:0.7rem; color:rgba(72,81,87,0.5);"><strong>'+oneProd2D[3]+'</strong></p>'+
                            '</div>';
          $('#productsOnOffBodyShowProds').append(addShowProd);
        });

			},error: (error) => { console.log(error); }
		});
  }

  function productsOnOffCatBtnSelectCatTA(catId){
    // productsOnOffBodyShowProds
    $('.productsOnOffAllCatBtnTA').attr('style','font-size:1.1rem; width:75%; color:rgb(72,81,87);');
    $('#productsOnOffCatBtnTA'+catId).attr('style','font-size:1.1rem; width:75%; background-color:rgba(40,167,69,0.5); color:rgb(72,81,87);');
    $.ajax({
			url: '{{ route("prodOnOff.callCatProdsTA") }}',
			method: 'post',
			data: {
				catId: catId,
				_token: '{{csrf_token()}}'
			},
			success: (allProdsThisCat) => {
        // productsOnOffBodyShowProds
        allProdsThisCat = $.trim(allProdsThisCat);
        $('#productsOnOffBodyShowProdsTA').html('');
        var addShowProd = '';
        var allProdsThisCat2D = allProdsThisCat.split('--99--');
        $.each(allProdsThisCat2D, function( index, oneProd ) {
          var oneProd2D = oneProd.split('--88--');

            if (oneProd2D[4] == 1){
              addShowProd = '<div class="d-flex flex-wrap justify-content-between mb-2 p-1 fingerPointer" id="productsOnOffProdDivTA'+oneProd2D[0]+'" onclick="productsOnOffChangeProdsStatusTA(\''+oneProd2D[0]+'\')" style="width:49%; height:fit-content; background-color:#CCFFCC; border: 1px solid rgb(72,81,87); border-radius:3px;">';
            }else{
              addShowProd = '<div class="d-flex flex-wrap justify-content-between mb-2 p-1 fingerPointer" id="productsOnOffProdDivTA'+oneProd2D[0]+'" onclick="productsOnOffChangeProdsStatusTA(\''+oneProd2D[0]+'\')" style="width:49%; height:fit-content; background-color:#FFCCCC; border: 1px solid rgb(72,81,87); border-radius:3px;">';
            }
            addShowProd +=    '<p style="width:75%; margin:0;"><strong>'+oneProd2D[1]+'</strong></p>'+
                              '<p style="width:25%; margin:0; text-align:center;"><strong>'+parseFloat(oneProd2D[2]).toFixed(2)+' CHF</strong></p>'+
                              '<p style="width:100%; margin:0; font-size:0.7rem; color:rgba(72,81,87,0.5);"><strong>'+oneProd2D[3]+'</strong></p>'+
                            '</div>';
          $('#productsOnOffBodyShowProdsTA').append(addShowProd);
        });

			},error: (error) => { console.log(error); }
		});
  }



  function productsOnOffChangeProdsStatus(pId){
    $.ajax({
			url: '{{ route("prodOnOff.changeProdStatus") }}',
			method: 'post',
			data: {
				prodId: pId,
				_token: '{{csrf_token()}}'
			},
			success: (prodStatChng) => {
        prodStatChng = $.trim(prodStatChng);
        console.log(prodStatChng);
        prodStatChng2D = prodStatChng.split('--99--');
        if(prodStatChng2D[1] == 0){
          $('#productsOnOffProdDiv'+prodStatChng2D[0]).attr('style', 'width:49%; height:fit-content; background-color:#FFCCCC; border: 1px solid rgb(72,81,87); border-radius:3px;');
        }else if(prodStatChng2D[1] == 1){
          $('#productsOnOffProdDiv'+prodStatChng2D[0]).attr('style', 'width:49%; height:fit-content; background-color:#CCFFCC; border: 1px solid rgb(72,81,87); border-radius:3px;');
        }
      },error: (error) => { console.log(error); }
		});
  }

  function productsOnOffChangeProdsStatusTA(pId){
    $.ajax({
			url: '{{ route("prodOnOff.changeProdStatusTA") }}',
			method: 'post',
			data: {
				prodId: pId,
				_token: '{{csrf_token()}}'
			},
			success: (prodStatChng) => {
        prodStatChng = $.trim(prodStatChng);
        console.log(prodStatChng);
        prodStatChng2D = prodStatChng.split('--99--');
        if(prodStatChng2D[1] == 0){
          $('#productsOnOffProdDivTA'+prodStatChng2D[0]).attr('style', 'width:49%; height:fit-content; background-color:#FFCCCC; border: 1px solid rgb(72,81,87); border-radius:3px;');
        }else if(prodStatChng2D[1] == 1){
          $('#productsOnOffProdDivTA'+prodStatChng2D[0]).attr('style', 'width:49%; height:fit-content; background-color:#CCFFCC; border: 1px solid rgb(72,81,87); border-radius:3px;');
        }
      },error: (error) => { console.log(error); }
		});
  }

  function openProdONOFF(){
    $('#openCloseResModal').modal('hide');
    $('#productsOnOffModal').modal('show');
  }
  function openProdONOFFTA(){
    $('#openCloseResModal').modal('hide');
    $('#productsOnOffTAModal').modal('show');
  }


</script>



<!-- The Modal -->
<div class="modal" id="notifications" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('adminP.orderOnHold')}}...</h4>
                <button type="button" class="close" data-dismiss="modal">X</button>
            </div>
 
      <!-- Modal body -->
      <div class="modal-body">
        @foreach(Orders::where('Restaurant', '=', $thisRestaurantId)->where('statusi', '=', 0)->whereIn('nrTable',$myTablesWaiter)->get()->sortByDesc('created_at') as $order)
          @if($nowDate == explode(' ', $order->created_at)[0])
            <p class="p-3" style="border-bottom:1px solid lightgray; font-size:21px;">-> {{count(explode('---8---',$order->porosia))}} {{__('adminP.productsTable')}}: <strong>{{$order->nrTable}} </strong></p>
          @endif
        @endforeach
      </div>


    </div>
  </div>
</div> 

<nav class="navbar navbar-expand navbar-light bg-white topbar d-flex flex-wrap justify-content-between" id="DashNavbar" style="margin-left:3%; width:97%;">
  <p style="font-size:21px; width:30%;" class="color-qrorpa ml-2 pt-1"><strong>{{Restorant::find($thisRestaurantId)->emri}}</strong></p>

  <div style="width:25%;" class="d-flex justify-content-between">
    <button class="btn btn-outline-dark shadow-none" style="border-radius:30px; margin:12px;" data-toggle="modal" data-target="#openCloseResModal">
      <img src="storage/icons/navIconCloseResTaDe.png" style="width: 1cm; height:1cm;" alt=""> 
    </button>
    @if(Auth::user()->ehcchurworker == 0 && accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Tabellenstatus_Tische'],['accessValid','1']])->first() != NULL)
      <a class="btn btn-outline-dark shadow-none" href="{{ route('admWoMng.ordersFreeTablesAdmMngPageWaiter') }}" style="border-radius:30px; margin:12px;">
        <img src="storage/icons/navIconTablesDeAct.png" style="width: 1cm; height:1cm;" alt="">  
      </a>
    @endif

    @if(Request::is('admWoMngIndexWaiter') || Request::is('dashboard2') || Request::is('dashboard3') || Request::is('admWoMngTakeawayWaiter') )
      @if($ordersLi > 0)
        <a class="btn btn-outline-dark shadow-none button-glow" href="{{ route('admWoMng.ordersListAdmMngPageWaiter') }}" style="border-radius:30px; margin:12px;">
          <img src="storage/icons/navIconOrdersList.png" style="width: 1cm; height:1cm;" alt=""> 
        </a>
      @else
        <a class="btn btn-outline-dark shadow-none" href="{{ route('admWoMng.ordersListAdmMngPageWaiter') }}" style="border-radius:30px; margin:12px;">
          <img src="storage/icons/navIconOrdersList.png" style="width: 1cm; height:1cm;" alt=""> 
        </a>
      @endif
    @else
      <a class="btn btn-outline-dark shadow-none" href="{{ route('admWoMng.indexAdmMngPageWaiter') }}" style="border-radius:30px; margin:12px;">
        <img src="storage/icons/navIconTablesOpen.png" style="width: 1cm; height:1cm;" alt=""> 
      </a>
    @endif   
  </div>

  
  <div style="width:30%;" class="d-flex justify-content-between">
    @if($usrChkIn != Null)
      <?php
         $dt2D = explode('-',explode(' ',$usrChkIn->checkIn)[0]);
         $hr2D = explode(':',explode(' ',$usrChkIn->checkIn)[1]);
      ?>
      <button class="btn btn-outline-dark shadow-none" id="setCheckInBtn" style="width: 49%; background-color:rgba(39,190,175,0.4); border-radius:10px; margin-right:4px; margin-left:4px; font-weight:bold;">
        {{$dt2D[2].'.'.$dt2D[1].'.'.$dt2D[0]}} <span style="margin-right:10px;"></span> {{$hr2D[0].':'.$hr2D[1]}}
      </button>
      <button class="btn btn-outline-dark shadow-none" id="setCheckOutBtn" onclick="setCheckOut('{{$usrChkIn->id}}')" style="width: 49%; border-radius:10px; margin-right:4px; margin-left:4px;"><strong>Checkout</strong></button>

    @else
      <button class="btn btn-outline-dark shadow-none" id="setCheckInBtn" onclick="setCheckIn()" style="width: 49%; border-radius:10px; margin-right:4px; margin-left:4px; font-weight:bold;">
        Einchecken
      </button>
      <button class="btn btn-outline-dark shadow-none"  id="setCheckOutBtn" style="width: 49%; border-radius:10px; margin-right:4px; margin-left:4px;"><strong>Checkout</strong></button>
    @endif
  </div>

  <div style="width:10%;" class="d-flex justify-content-end">
    <a class="btn shadow-none" href="{{ route('logout') }}" style="border-radius:30px; margin:12px;"  
    onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
      <img src="storage/icons/navIconLogout.png" style="width: 1cm; height:1cm;" alt=""> 
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
  </div>

  <div class="alert alert-success text-center mt-1" id="checkInOutScc01" style="width:100%; display:none; font-weight:bold;"></div>
</nav>

<script>
  function setCheckIn(){
    $.ajax({
			url: '{{ route("chkInOut.checkInRegister") }}',
			method: 'post',
			data: {
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
        respo = $.trim(respo);
        respo2D = respo.split('||||');
				$("#setCheckInBtn").html(respo2D[0]);
				$("#setCheckInBtn").attr('style','width: 49%; background-color:rgba(39,190,175,0.4); border-radius:10px; margin-right:4px; margin-left:4px; font-weight:bold;');

        $('#setCheckOutBtn').attr('onclick','setCheckOut('+respo2D[1]+')');
			},
			error: (error) => { console.log(error); }
		});
  }

  function setCheckOut(chInReg){
    $.ajax({
			url: '{{ route("chkInOut.checkOutRegister") }}',
			method: 'post',
			data: {
        chInIns: chInReg,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
        respo = $.trim(respo);
        $('#checkInOutScc01').html('Sie haben erfolgreich eine Check-In/Out-Sitzung registriert, '+$("#setCheckInBtn").html()+' bis '+respo);
        $('#checkInOutScc01').show(100).delay(8000).hide(100);

        $('#setCheckInBtn').html('Einchecken');
        $("#setCheckInBtn").attr('style','width: 49%; border-radius:10px; margin-right:4px; margin-left:4px; font-weight:bold;');
        $('#setCheckInBtn').attr('onclick','setCheckIn()');
        $('#setCheckOutBtn').removeAttr('onclick');

        $("#checkInOutRepDiv").load(location.href+" #checkInOutRepDiv>*","");
			},
			error: (error) => { console.log(error); }
		});
  }
</script>


                <!-- <i class="fas fa-bell fa-fw"></i> -->
                <!-- Counter - Alerts -->
                
        




                <div id="soundsAllNBKP">
                
                </div>

                @if(Auth::check())
                  @if(Auth::user()->role == 55)
                   
                    <!-- newOrder Pusher -->
                    <script>
                      
                      function newMessageFromQRorpa(avID, theMsg, msgCreated){

                        var respoTime = msgCreated.split(' ')[1];
                        var respoTime2D = respoTime.split(':');

                        var respoDate = msgCreated.split(' ')[0];
                        var respoDate2D = respoDate.split('-');

                        var newMsg =  '<div class="p-1 mb-2 leftMsg" style="width:70%; height:fit-content; margin-right:30%; color:white; background-color:rgba(32,44,51,255);">'+
                                        '<p class="mb-1">'+theMsg+'</p>'+
                                        '<div class="d-flex">'+
                                          '<p class="mb-1" style="color:rgba(255, 255, 255, 0.45); width:45%;">'+respoTime2D[0]+':'+respoTime2D[1]+':'+respoTime2D[2]+'</p>'+
                                          '<p class="mb-1 text-center" style="color:rgba(255, 255, 255, 0.45); width:40%;">'+respoDate2D[2]+'/'+respoDate2D[1]+'</p>'+
                                          '<i style="width:15%; color:rgb(39,190,175);" class="fas text-center pt-1 fa-check-double"></i> <!-- read -->'+
                                        '</div>'+
                                      '</div>';

                        $("#messagesDiv"+avID).append(newMsg);
                        $('#messageInputFor'+avID).val('');
                        objDiv = document.getElementById("messagesDiv"+avID);
                        objDiv.scrollTop = objDiv.scrollHeight;
                      }
                    </script>
                  @endif
                @endif

                <!-- <div class="modal show" id="tabOrder18" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style="background-color: rgba(0, 0, 0, 0.5); padding-top: 5%; padding-right: 17px; display: block; padding-left: 17px;" aria-modal="true"> -->

              

            

            
            <style>
             .optionsAnchorPh{
                    color:black;
                    text-decoration:none;
                    opacity:0.65;
                    font-weight: bold;
                    font-size:20px;
                }
                .optionsAnchorPh:hover{
                    opacity:0.95;
                    text-decoration:none;
                    color:black;
                    
                }
            </style>
           

            <div class="topbar-divider d-none d-sm-block"></div>


        <!-- PUSHER / call waiter -->
        <!-- <script>
          var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {cluster: 'eu'});
          var channel = pusher.subscribe('WaiterChanel');
          channel.bind('App\\Events\\CallWaiter', function(data) {
              window.location = window.location
          });
        </script> -->







              <script>
                //  clNewTab topNavBar PUSHER

                function closeMSGCA(){
                  $("#clientToAdminMsgANS").load(location.href+" #clientToAdminMsgANS>*","");
                  $("#clientToAdminMsgANS").hide(300);
                }

                function sendMsgToClFresh(res){
                  if($('#clientToAdminMsgANSMsgAgain').val().length != 0){
                    $.ajax({
                      url: '{{ route("TabChngCli.MsgToUser") }}',
                      method: 'post',
                      data: {
                          tableNr: $('#clientToAdminMsgANSTableNr').val(),
                          res: res,
                          msg: $('#clientToAdminMsgANSMsgAgain').val(),
                          clSelected : $('#clientToAdminMsgANSClientPhNr').val(),
                          _token: '{{csrf_token()}}'
                      },
                      success: () => {
                          $('#clientToAdminMsgANSAlertE').show(1).delay(3000).hide(1);

                          $("#clientToAdminMsgANS").load(location.href+" #clientToAdminMsgANS>*","");
                          $("#clientToAdminMsgANS").hide(300);
                      },
                      error: (error) => {
                          console.log(error);
                          alert($('#pleaseUpdateAndTryAgain').val());
                      }
                    });
                  }else{
                    $('#clientToAdminMsgANSAlertE').show(1).delay(3000).hide(1);
                  }
                }

          
              </script>


                  <div id="clientToAdminMsgANS" class="alert alert-info p-4" style="position:fixed; top:70px; width:75%; margin-left:12.5%; border-radius:15px; font-weight:bold; z-index:8; font-size:large; border:2px solid rgba(72,81,87,0.6); display:none !important; " >
                    <p style="font-weight:bold; color:rgb(39, 190, 175);" class="text-center">{{__('adminP.responseFromCustomers')}}</p>

                    <p style="font-weight:bold; color:rgb(72,81,87);" class="text-center"> {{__('adminP.table')}} : <span id="adminToClientMsgTableNr"></span> </p>
                    <p style="font-weight:bold; color:rgb(72,81,87);" class="text-center"> {{__('adminP.table')}} : <span id="adminToClientMsgClPhNr"></span> </p>
                    
                    <p style="font-weight:bold; color:rgb(39, 190, 175);" class="text-center"> Frage : 
                      <span style="color:rgb(72,81,87);" id="adminToClientMsgQuestion"></span> 
                    </p>
                    <p style="font-weight:bold; color:rgb(39, 190, 175);" class="text-center"> {{__('adminP.customersReply')}} : 
                      <span style="color:rgb(72,81,87);" id="adminToClientMsganswer"></span> 
                    </p>
                    <button onclick="closeMSGCA()" style="width:100%;" class="btn btn-danger">{{__('adminP.conclude')}}</button>


                      <p class="mt-3" style="color:rgb(72,81,87)"><strong>{{__('adminP.sendAnotherMessage')}}:</strong></p>
                      <textarea id="clientToAdminMsgANSMsgAgain" name="clientToAdminMsgANSMsgAgain" style="width: 100%;" placeholder="{{__('adminP.newMessage')}}" class="form-control p-3" rows="3"></textarea>
                      <p id="clientToAdminMsgANSAlertS" class="mt-2 alert alert-success textcenter" style="width:100%;; display:none;">{{__('adminP.messageSent')}}</p>
                      <p id="clientToAdminMsgANSAlertE" class="mt-2 alert alert-danger textcenter" style=" width:100%; display:none;">{{__('adminP.writeValidMessage')}}</p>
                      <button style="width:100%;" class="btn btn-success" onclick="sendMsgToClFresh('{{auth()->user()->sFor}}')">{{__('adminP.send')}}</button>
                    
                      <input type="hidden" id="clientToAdminMsgANSTableNr">
                      <input type="hidden" id="clientToAdminMsgANSClientPhNr">
                  </div>
                

   