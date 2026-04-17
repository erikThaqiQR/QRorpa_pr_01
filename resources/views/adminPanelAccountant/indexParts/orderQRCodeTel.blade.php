<?php
  use App\Orders;
  use Carbon\Carbon;
  use App\accessControllForAdmins;
?>


      <!-- Modal -->
      <div class="modal" id="orderQRCodeTel">
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content ">

            <div class="modal-header">
              <h5 class="modal-title"><strong>{{__('adminP.orderQRCodeTxt01')}}</strong></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="far fa-2x fa-times-circle"></i>
              </button>
            </div>

            <div class="modal-body">
              @foreach(Orders::where('Restaurant', Auth::User()->sFor)->whereDate('created_at', '>=', Carbon::now()->subDays(1))->get()->sortByDesc('created_at') as $order)
                <div class="d-flex flex-wrap justify-content-between mb-1" style="border-bottom: 1px solid #c1f0f0;" onclick="showOrderQECodeTel('{{$order->id}}')"> 
                  <p style="width: 33%;">{{__('adminP.table')}}: <strong>{{ $order->nrTable }}</strong>
                    <br> <strong>{{ $order->shuma }} {{__('adminP.currencyShow')}}</strong>
                    @if($order->userPhoneNr == "0770000000")
                    <br> <strong>Admin</strong>
                    @else
                    <br> <strong>07* *** {{substr ($order->userPhoneNr, -4)}}</strong>
                    @endif
                  </p>  
                  <p style="width: 65%;">
                    @php
                      $orderedProducts = explode('---8---',$order->porosia);

                      $mappedOrderedProducts = [];
                      foreach($orderedProducts as $product){
                        $explodedProduct = explode('-8-', $product);

                        if(str_contains($explodedProduct[3], '/')){
                          $mappedOrderedProducts[] = [
                                "productName" => $explodedProduct[0],
                                "quantity"    => $explodedProduct[3],
                                "type"        => $explodedProduct[5] != 'empty' ? $explodedProduct[5] : null
                            ];
                        } else if(isset($mappedOrderedProducts[$explodedProduct[7]])){
                            $mappedOrderedProducts[$explodedProduct[7]] = [
                                "productName" => $explodedProduct[0],
                                "quantity"    => $explodedProduct[3] ? $mappedOrderedProducts[$explodedProduct[7]]['quantity'] + $explodedProduct[3] : ++$mappedOrderedProducts[$explodedProduct[7]]['quantity'],
                                "type"        => null
                            ];
                        } else {
                            $mappedOrderedProducts[$explodedProduct[7]] = [
                                "productName" => $explodedProduct[0],
                                "quantity"    => $explodedProduct[3] ?? 1,
                                "type"        => $explodedProduct[5] != 'empty' ? $explodedProduct[5] : null
                            ];
                        }
                      }
                    @endphp
                    @foreach($mappedOrderedProducts as $produkti)
                      <span >
                        ({{$produkti["quantity"]}} x) {{$produkti["productName"]}}  
                        @if($produkti["type"])
                          <!-- Tipi  -->
                          <span style="font-size:10px;"><strong>({{$produkti["type"]}})</strong></span>
                        @endif  
                      </span>
                      <br>
                    @endforeach
                  </p>
                </div>
              @endforeach
            </div>
          </div>
        </div>  
      </div>

      <!-- Modal -->
      <div class="modal" id="orderQRCodePicTel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.6); padding-top:5%; z-index:9999;">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

              <div class="modal-body text-center">
                <p class="pb-2 pt-1 text-center" style="color:rgb(39,190,175); font-size:24px;"><strong>{{__('adminP.orderQRCodeTxt02')}}</strong></p>
                <p id="orderQRCodePicIdTel" style="font-weight: bold;" class="text-center mb-1"></p>
                <img id="orderQRCodePicImgTel" style="width:100%; height:auto;" src="" alt="qrCodeNotFound">

                <div class="pt-2 pb-2 text-center" style="width: 100%;">
                  <form method="POST" action="{{ route('receipt.getReceipt') }}">
                      {{ csrf_field()}}
                      <input id="orderQRCodePicDownloadOI" type="hidden" value="" name="orId">
                      <button type="submit" class="btn"><strong><i class="fas fa-download mr-2"></i> {{__('adminP.orderQRCodeTxt03')}}</strong></button>
                  </form>
                </div>

                <button onclick="closeOrderQRCodePicTel()" type="button" class="close mt-3 text-center" style="width:100%; margin:0px;" aria-label="Close">
                  <i style="color:red;" class="far fa-2x fa-times-circle"></i>
                </button>
              </div>
          </div>
        </div>  
      </div>



      

      <script>
        function showOrderQECodeTel(oId){
          $.ajax({
			url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
			method: 'post',
			data: {
				id: oId,
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
        res = $.trim(res);
        res2D = res.split('-8-8-');
        $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
        $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res);
        $('#orderQRCodePicDownloadOI').val(oId);
        $('#orderQRCodePicTel').modal('show');
			},
				error: (error) => { console.log(error); }
			});
        }

        function closeOrderQRCodePicTel(){
            $('#orderQRCodePicTel').modal('hide');
            $('body').addClass('modal-open');
        }
      </script>