<?php

use App\Restorant;
use App\OPSaferpayReference;
use Illuminate\Support\Facades\Auth;
  use App\Orders;
  use Carbon\Carbon;
  use App\accessControllForAdmins;

  $theRes = Restorant::find(Auth::user()->sFor);

  $nowDate = date('Y-m-d');
  $nowDate2D = explode('-',$nowDate);

  $resClock = explode('->',$theRes->reportTimeArc);
  $resClock1_2D = explode(':',$resClock[0]);
  $resClock2_2D = explode(':',$resClock[1]);

  $today_str = Carbon::create($nowDate2D[0], $nowDate2D[1], $nowDate2D[2], $resClock1_2D[0], $resClock1_2D[1], 00);
  $today_end = Carbon::create($nowDate2D[0], $nowDate2D[1], $nowDate2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
  if($theRes->reportTimeOtherDay == 1){
      // diff day
      $today_end->addDays(1); 
  }
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

            <div class="modal-body" id="orderQRCodeTelBody">
              @foreach(Orders::where('Restaurant', Auth::User()->sFor)
              ->whereBetween('created_at', [$today_str, $today_end])
              ->whereIn('nrTable',$myTablesWaiter)
              ->select('id','nrTable','shuma','userPhoneNr','porosia','created_at','refId','inCashDiscount','inPercentageDiscount','tipPer')
              ->orderByDesc('created_at')->get() as $order)

                <?php
                    $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();

                    if($order->inCashDiscount > 0){
                      $skontoCHF = number_format($order->inCashDiscount,2,'.','');
                      $toPay = number_format($order->shuma - $skontoCHF,2,'.','');
                    }else if($order->inPercentageDiscount > 0){
                        $skontoCHF = number_format(($order->shuma - $order->tipPer)*($order->inPercentageDiscount/100),2,'.','');
                        $toPay = number_format($order->shuma - $skontoCHF,2,'.','');
                    }else{
                        $toPay = number_format($order->shuma,2,'.','');
                    } 
                ?>
                <div class="d-flex flex-wrap justify-content-between mb-1" style="border-bottom: 1px solid #c1f0f0;" onclick="showOrderQECodeTel('{{$order->id}}')"> 
                  <p style="width: 33%;">
                    @if ($order->nrTable == 500)
                      <strong>Takeaway</strong>
                    @else
                      {{__('adminP.table')}}: <strong>{{ $order->nrTable }}</strong>
                    @endif

                    <br> <strong>{{ $toPay }} {{__('adminP.currencyShow')}}</strong>

                    @if($order->userPhoneNr == "0770000000")
                    <br> <strong>Admin</strong>
                    @elseif (str_contains($order->userPhoneNr,'|'))
                    <br> <strong>Ghost: {{substr ($order->userPhoneNr, -4)}}</strong>
                    @elseif ($order->userPhoneNr == "0000000000")
                    <br> <strong>Online (Direct)</strong>
                    @else
                    <br> <strong>07* *** {{substr ($order->userPhoneNr, -4)}}</strong>
                    @endif

                    <br><strong># {{$order->refId}} 
                    @if ($theRefIns != Null)
                      <span class="ml-2">({{$theRefIns->refPh}})</span>
                    @endif
                    </strong>
                    
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
                        } else if(isset($mappedOrderedProducts[$explodedProduct[0]])){
                            $mappedOrderedProducts[$explodedProduct[0]] = [
                                "productName" => $explodedProduct[0],
                                "quantity"    => $explodedProduct[3] ? $mappedOrderedProducts[$explodedProduct[0]]['quantity'] + $explodedProduct[3] : ++$mappedOrderedProducts[$explodedProduct[0]]['quantity'],
                                "type"        => null
                            ];
                        } else {
                            $mappedOrderedProducts[$explodedProduct[0]] = [
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
        style="background-color: rgba(0, 0, 0, 0.6); padding-top:1%; z-index:9999;">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

              <div class="modal-body text-center">
                <p class="pb-2 pt-1 text-center" style="color:rgb(39,190,175); font-size:24px;"><strong>{{__('adminP.orderQRCodeTxt02')}}</strong></p>
                <p id="orderQRCodePicIdTel" style="font-weight: bold;" class="text-center mb-1"></p>
                <img id="orderQRCodePicImgTel"  style="width:50%; height:auto; margin-left:25%; margin-right:25%;" src="" alt="qrCodeNotFound">

                <div class="pt-2 pb-2 text-center" style="width: 100%;">
                  <form method="POST" action="{{ route('receipt.getReceipt') }}">
                      {{ csrf_field()}}
                      <input id="orderQRCodePicDownloadOI" type="hidden" value="" name="orId">
                      <button type="submit" class="btn"><strong><i class="fas fa-download mr-2"></i> {{__('adminP.orderQRCodeTxt03')}}</strong></button>
                  </form>
                </div>

                <button style="width:100%" class="btn btn-outline-dark shadow-none" id="BtnPrintOrderDtl" type="button" onclick="printOrderDtl()">
                  <strong>Drucken Sie die Rechnung</strong>
                </button> 

                <input type="hidden" value="0" id="OrQRCodePayIsSelective">
                <input type="hidden" value="0" id="OrQRCodePayIsSelectiveTNr">

                <button onclick="closeOrderQRCodePicTel()" type="button" class="close mt-3 text-center" style="width:100%; margin:0px;" aria-label="Close">
                  <i style="color:red;" class="far fa-2x fa-times-circle"></i>
                </button>
              </div>
          </div>
        </div>  
      </div>



      

      <script>


        function printOrderDtl() {
          const orId = $('#orderQRCodePicDownloadOI').val();
          let resName = '';
          let tableNr = '';
          let tableNrShow = '';
          let timePrint = '';
          let theOrderShowProd = '';
          let ThePaymentMethod = '';
          let totalPre = '';
          let gcDiscount = '';
          let staffDiscount = '';
          let bakshishi = '';
          let totalToPay = '';
          let orderId = '';
          let orderRefId = '';
          let waitersName = '';
          let resAdrs = '';
          let tvshLow = '';
          let tvshHigh = '';
          let tvshShow = '';
          $.ajax({
						url: '{{ route("print.callDataForPrintReceipt") }}',
						method: 'post',
						data: {
							oId: orId,
							_token: '{{csrf_token()}}'
						},
						success: (printData) => {
              printData = $.trim(printData);
              printData2D = printData.split('---88---');
					
              resName = printData2D[0];
              tableNr = printData2D[1];
              if( tableNr == 500){ tableNrShow = 'Takeaway';      
              }else{ tableNrShow = 'Tisch: '+printData2D[1]; }
              timePrint = printData2D[2];
              theOrderShowProd = printData2D[3];
              ThePaymentMethod = printData2D[4];
              totalPre = parseFloat(printData2D[5]).toFixed(2);
              gcDiscount = parseFloat(printData2D[6]).toFixed(2);
              staffDiscount = parseFloat(printData2D[7]).toFixed(2);
              bakshishi = parseFloat(printData2D[8]).toFixed(2);
              totalToPay = parseFloat(printData2D[9]).toFixed(2);
              orderId = printData2D[10];
              orderRefId = printData2D[11];
              orderQrCodeName = printData2D[12];
              waitersName = printData2D[13];
              resAdrs = printData2D[14];
              tvshLow = parseFloat(printData2D[15]).toFixed(2);
              tvshHigh = parseFloat(printData2D[16]).toFixed(2);
              tvshShow = printData2D[17];

              hasPOSData = printData2D[18];
              DisplayName = printData2D[19];
              AppPANPrtCardholder = printData2D[20];
              TrxDate = printData2D[21];
              TrxTime = printData2D[22];
              TrmID = printData2D[23];

              aid = printData2D[24];
              TrxSeqCnt = printData2D[25];
              TrxRefNum = printData2D[26];
              AuthC = printData2D[27];
              AcqID = printData2D[28];
              AppPANEnc = printData2D[29];
              TrxAmt = printData2D[30];

              if(typeof printData2D[31] !== 'undefined'){ resComment = printData2D[31];
              }else{ resComment = '';}

              let printWindow = window.open('', '', 'height=500, width=1000');
              if(hasPOSData == 'Yes'){
                printWindow.document.write(`
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            h2 { color: #333;}
                        </style>
                    </head>
                    <body>
                        <h2 style="width:100%; text-align:center; margin-bottom:0px; margin-top:0;">`+resName+`<br>Rechnung</h2>

                        `+resAdrs+`

                        <div style="width:100%; display:flex; flex-wrap: wrap; justify-content: space-between;">
                          <p style="width:40%; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+tableNrShow+`</p>
                          <p style="width:60%; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+timePrint+`</p>
                        </div>

                        <div style="width:100%; display:flex; flex-wrap: wrap; justify-content: space-between;">
                          <p style="width:40%; font-size:0.6rem; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">Rechnung #: `+orderId+`</p>
                          <p style="width:60%; font-size:0.6rem; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">Verkaufs-ID: `+orderRefId+`</p>
                        </div>

                        <p style="width:100%; font-size:0.8rem; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">Kellner: `+waitersName+`</p>

                        <pre style="font-size:0.8rem; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height:1.01; text-align: center !important; margin:0px; padding:0px 15px 0px 15px; white-space: pre-wrap; word-wrap: break-word;">`+resComment+`</pre>

                        <hr style="width:100%; margin:4px 0 4px 0;">

                        `+theOrderShowProd+`

                        <hr style="width:100%; margin:4px 0 4px 0;">

                        <div style="width:100%; display:flex; flex-wrap: wrap; justify-content: space-between;">
                          
                          <p style="width:100%; text-align:center; margin-bottom:0px; line-height:1;"><strong>`+ThePaymentMethod+`</strong></p>

                          <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>Gesamtsumme: </strong></p>
                          <p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">`+totalToPay+` CHF</p>

                          
                          <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>Geschenkkarte: </strong></p>
                          <p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">`+gcDiscount+` CHF</p>

                          <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>Rabatt: </strong></p>
                          <p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">`+staffDiscount+` CHF</p>

                          <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>Kellner Trinkgeld: </strong></p>
                          <p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">`+bakshishi+` CHF</p>

                          `+tvshShow+`
                        </div>

                        <hr style="width:100%; margin:4px 0 4px 0;">
                          <p style="width:100%; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+DisplayName+` contactless</p>
                          <p style="width:100%; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+AppPANPrtCardholder+`</p>
                          <div style="width:100%; display:flex; flex-wrap: wrap; justify-content: space-between;">
                            <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+TrxDate.slice(-2)+`.`+TrxDate.slice(2,4)+`.`+TrxDate.slice(0,2)+`</p>
                            <p style="width:50%; text-align:right; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+TrxTime.slice(0,2)+`.`+TrxTime.slice(2,4)+`.`+TrxTime.slice(-2)+`</p>
                            <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:6px; line-height:1.1;">Trm-Id:</p>
                            <p style="width:50%; text-align:right; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+TrmID+`</p>

                            <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:6px; line-height:1.1;">Akt-Id:</p>
                            <p style="width:50%; text-align:right; margin-bottom:0px; margin-top:6px; line-height:1.1;">x</p>

                            <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:6px; line-height:1.1;">AID:</p>
                            <p style="width:50%; text-align:right; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+aid+`</p>

                            <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:6px; line-height:1.1;">Trx. Seq-Cnt:</p>
                            <p style="width:50%; text-align:right; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+TrxSeqCnt+`</p>

                            <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:6px; line-height:1.1;">Trx. Ref-No:</p>
                            <p style="width:50%; text-align:right; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+TrxRefNum+`</p>

                            <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:6px; line-height:1.1;">Auth. Code:</p>
                            <p style="width:50%; text-align:right; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+AuthC+`</p>

                            <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:6px; line-height:1.1;">Acq-Id:</p>
                            <p style="width:50%; text-align:right; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+AcqID+`</p>

                            <p style="width:100%; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+AppPANEnc+`</p>

                            <p style="width:50%; text-align:left; font-size:1.2rem; margin-bottom:0px; margin-top:6px; line-height:1.1;">Total-EFT CHF:</p>
                            <p style="width:50%; text-align:right; font-size:1.2rem; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+parseFloat(TrxAmt).toFixed(2)+`</p>
                          </div>

                        <hr style="width:100%; margin:4px 0 4px 0;">
                      
                        <div style="text-align:center;">
                          <img style="width:150px; height:150px; margin:0.2cm 0 0 0; padding:0px;" src="storage/digitalReceiptQRK/`+orderQrCodeName+`" alt="">
                        </div>

                        <p style="width:100%; text-align:center; margin-bottom:0px; line-height:1;">Wir danken Ihnen für die Nutzung von QRorpa.ch, `+resName+` erwartet Sie wieder</p>


                        <p style="color:white; width:100%;">-</p>
                        <p style="color:white; width:100%;">-</p>
                    </body>
                    </html>
                `);
              }else{
                printWindow.document.write(`
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            h2 { color: #333;}
                        </style>
                    </head>
                    <body>
                        <h2 style="width:100%; text-align:center; margin-bottom:0px; margin-top:0;">`+resName+`<br>Rechnung</h2>

                        `+resAdrs+`

                        <div style="width:100%; display:flex; flex-wrap: wrap; justify-content: space-between;">
                          <p style="width:40%; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+tableNrShow+`</p>
                          <p style="width:60%; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+timePrint+`</p>
                        </div>

                        <div style="width:100%; display:flex; flex-wrap: wrap; justify-content: space-between;">
                          <p style="width:40%; font-size:0.6rem; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">Rechnung #: `+orderId+`</p>
                          <p style="width:60%; font-size:0.6rem; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">Verkaufs-ID: `+orderRefId+`</p>
                        </div>

                        <p style="width:100%; font-size:0.8rem; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">Kellner: `+waitersName+`</p>

                        <pre style="font-size:0.8rem; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height:1.01; text-align: center !important; margin:0px; padding:0px 15px 0px 15px; white-space: pre-wrap; word-wrap: break-word;">`+resComment+`</pre>

                        <hr style="width:100%; margin:4px 0 4px 0;">

                        `+theOrderShowProd+`

                        <hr style="width:100%; margin:4px 0 4px 0;">

                        <div style="width:100%; display:flex; flex-wrap: wrap; justify-content: space-between;">
                          
                          <p style="width:100%; text-align:center; margin-bottom:0px; line-height:1;"><strong>`+ThePaymentMethod+`</strong></p>

                          <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>Gesamtsumme: </strong></p>
                          <p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">`+totalToPay+` CHF</p>

                          
                          <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>Geschenkkarte: </strong></p>
                          <p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">`+gcDiscount+` CHF</p>

                          <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>Rabatt: </strong></p>
                          <p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">`+staffDiscount+` CHF</p>

                          <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>Kellner Trinkgeld: </strong></p>
                          <p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">`+bakshishi+` CHF</p>

                          `+tvshShow+`
                        </div>

                        <hr style="width:100%; margin:4px 0 4px 0;">
                      
                      
                        <div style="text-align:center;">
                          <img style="width:150px; height:150px; margin:0.2cm 0 0 0; padding:0px;" src="storage/digitalReceiptQRK/`+orderQrCodeName+`" alt="">
                        </div>

                        <p style="width:100%; text-align:center; margin-bottom:0px; line-height:1;">Wir danken Ihnen für die Nutzung von QRorpa.ch, `+resName+` erwartet Sie wieder</p>


                        <p style="color:white; width:100%;">-</p>
                        <p style="color:white; width:100%;">-</p>
                    </body>
                    </html>
                `);
              }
              printWindow.document.close();
              printWindow.print();
						},
						error: (error) => { console.log(error); }
					});
          // printWindow.window.close();
        }



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
              $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
              $('#orderQRCodePicDownloadOI').val(oId);
              $('#orderQRCodePicTel').modal('show');
            },
            error: (error) => { console.log(error); }
          });
        }

        function closeOrderQRCodePicTel(){
          $('#orderQRCodePicTel').modal('hide');
          
          if($('#OrQRCodePayIsSelective').val() == 1){
            $('body').addClass('modal-open');
            $.ajax({
              url: '{{ route("admin.checkIfTableHasOrders") }}',
              method: 'post',
              data: {
                  resId: '{{Auth::user()->sFor}}',
                  tbNr: $('#OrQRCodePayIsSelectiveTNr').val(),
                  _token: '{{csrf_token()}}'
              },
              success: (res) => {
                  res = $.trim(res);
                  if(res == 'hasActiveOr'){
                    $('#tabOrderTel'+$('#OrQRCodePayIsSelectiveTNr').val()).modal('show');
                    $('#tabOrder'+$('#OrQRCodePayIsSelectiveTNr').val()).modal('show');
                  }
              },
              error: (error) => { console.log(error); }
            });
          }else if($('#splitTheBillInitiateModal').hasClass('show') && $('#splitTheBillInitiateModal').hasClass('modal')){
            // splitTheBillInitiateModal is open
            $('body').addClass('modal-open');
          }
        }
      </script>