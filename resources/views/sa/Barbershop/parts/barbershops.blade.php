<?php
    use App\Restorant;
    use App\Orders;
    use App\Produktet;
    use App\TableQrcode;
    use App\kategori;

    use App\Barbershop;

    
    $barbershops = Barbershop::all()->sortByDesc('created_at');
    $orders = Orders::all();

    $dateNow = date('Y-m-d');

   
    $todaySales = 0;
    foreach($orders as $orderSot){
        if(explode(' ',$orderSot->created_at)[0] == $dateNow)
            $todaySales += $orderSot->shuma;
    }
?>
<style>
    .listRes:hover{
        cursor:pointer;
    }
</style>









<!-- add Barbershop Modal -->
<div class="modal" id="addBarbershop" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Barbershop</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group">
            <label for="usr">Name:</label>
            <input id="barbershopName" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="usr">Address:</label>
            <input id="barbershopAddress" type="text" class="form-control">
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer d-flex jstify-content">
        <button style="width:48%" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
        <button style="width:48%" type="button" class="btn btn-outline-success" data-dismiss="modal" onclick="addBarbershop()">Save</button>
      </div>

    </div>
  </div>
</div>







<section style="padding:10px;">
    <div class="container">
        <div class="d-flex justify-content-between">
            <div style="width:20%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Barbershops</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;">{{count($barbershops)}}</p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; opacity:0.65;"> Text test text test ... </p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; margin-bottom:-15px; opacity:0.65;"> Text test text test text test text ... </p>

            </div>

            <div style="width:20%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Heute Vertrieb</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;"><span style="opacity:0.7; font-size:20px;">CHF </span>xx</p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; opacity:0.65;"> Text test text test ... </p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; margin-bottom:-15px; opacity:0.65;"> Text test text test text test text ... </p>

            </div>

            <div style="width:20%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Babrbershops</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;">xx</p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; opacity:0.65;"> Text test text test ... </p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; margin-bottom:-15px; opacity:0.65;"> Text test text test text test text ... </p>

            </div>

            <div style="width:35%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Aktivitätsverkauf</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;">---</p>
            

            </div>
        

        </div>
    </div>
    <br><br>
    <div class="container">
        <div class="row">
            <div class="col-12 text-left">
                <p class="color-qrorpa" style="font-size:25px; margin-bottom:-5px;"><strong>Barbershops</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <button class="b-qrorpa color-white pr-3 pl-3 br-25" data-toggle="modal" data-target="#addBarbershop">Restaurant hinzufügen</button>
            </div>
            <div class="col-9">
            </div>
        </div>
    </div>



    <div class="d-flex flex-wrap justify-content-between mt-3 mb-3 p-4" id="barbershopList">
        @foreach(Barbershop::all()->sortByDesc('created_at') as $bShop)
            <a href="{{route('barbershops.indexBarbershopsOne', ['barbershop' => $bShop->id] )}}" style="width:19.5%;">
                <div class="container listRes" style=" background-color:rgb(247, 247, 240); border-radius:25px;" >
                    <div class="row p-2">
                        <div class="col-4">
                            <img width="100%" src="storage/icons/Logo.png" alt="">
                        </div>
                        <div class="col-8">
                            @if(strlen($bShop->emri) >= 13)
                            <p class="color-qrorpa mt-2" style="font-size:16px;"><strong>{{$bShop->emri}}</strong> </p>
                            @else
                            <p class="color-qrorpa mt-2" style="font-size:18px;"><strong>{{$bShop->emri}}</strong> </p>
                            @endif
                            <p class="color-qrorpa" style="opacity:0.8; margin-top:-15px; font-size:12px;">{{($bShop->adresas == 'empty' ? '---' : $bShop->adresa)}}</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>



<script>
    	  function addBarbershop(){
					$.ajax({
						url: '{{ route("barbershops.addBarbershop") }}',
						method: 'post',
						data: {
							name: $('#barbershopName').val(),
							address: $('#barbershopAddress').val(),
							_token: '{{csrf_token()}}'
						},
						success: () => {
							$("#barbershopList").load(location.href+" #barbershopList>*","");
						},
						error: (error) => {
							console.log(error);
							alert('Oops! Something went wrong')
						}
					});
				}
</script>