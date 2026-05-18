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
                    @foreach(explode('---8---',$order->porosia) as $produkti)
                      @php
                        $prod = explode('-8-', $produkti);
                      @endphp 
                      <span >
                        ({{$prod[3]}} x) {{$prod[0]}}  
                        @if($prod[5] != "empty")
                          <!-- Tipi  -->
                          <span style="font-size:10px;"><strong>({{$prod[5]}})</strong></span>
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

                <button style="width:100%" class="btn btn-outline-dark shadow-none" id="BtnPrintOrderDtl" type="button" onclick="printOrderDtl('{{ auth()->user()->epsonPrinterIp }}')">
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


        function printOrderDtl(printerIp = null, orId = null) {
          if(!orId){
            orId = $('#orderQRCodePicDownloadOI').val();
          }
          $.ajax({
              url: '{{ route("print.callDataForPrintReceipt") }}',
              method: 'post',
              data: { oId: orId, _token: '{{csrf_token()}}' },
              success: (printData) => {
                  const parser = new DOMParser();

                  printData = $.trim(printData);
                  const d = printData.split('---88---');

                  const resName             = d[0];
                  const tableNr             = d[1];
                  const tableNrShow         = (tableNr == 500) ? 'Takeaway' : 'Tisch: ' + tableNr;
                  const timePrint           = d[2];
                  const theProdsHtml        = d[3];
                  const ThePaymentMethod    = d[4];
                  const totalPre            = parseFloat(d[5]).toFixed(2);
                  const gcDiscount          = parseFloat(d[6]).toFixed(2);
                  const staffDiscount       = parseFloat(d[7]).toFixed(2);
                  const bakshishi           = parseFloat(d[8]).toFixed(2);
                  const totalToPay          = parseFloat(d[9]).toFixed(2);
                  const orderId             = d[10];
                  const orderRefId          = d[11];
                  const orderQrCodeName     = d[12];
                  const waitersName         = d[13];
                  const resAdrs             = d[14];
                  const tvshShow            = d[17];
                  const hasPOSData          = d[18];
                  const DisplayName         = d[19];
                  const AppPANPrtCardholder = d[20];
                  const TrxDate             = d[21];
                  const TrxTime             = d[22];
                  const TrmID               = d[23];
                  const aid                 = d[24];
                  const TrxSeqCnt           = d[25];
                  const TrxRefNum           = d[26];
                  const AuthC               = d[27];
                  const AcqID               = d[28];
                  const AppPANEnc           = d[29];
                  const TrxAmt              = d[30];
                  const resComment          = (typeof d[31] !== 'undefined') ? d[31] : '';

                  // ── FALLBACK: no printer IP → original browser print ─────────
                  if (!printerIp) {
                      let printWindow = window.open('', '', 'height=500, width=1000');
                      if (hasPOSData == 'Yes') {
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

                                  `+theProdsHtml+`

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
                      } else {
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

                                  `+theProdsHtml+`

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
                      return;
                  }

                  // ── EPSON ePOS PRINT ─────────────────────────────────────────
                  const resAdrsDoc  = parser.parseFromString(d[14].replace(/<br\s*\/?>/gi, '\n'), 'text/html');
                  const resAdrsText = resAdrsDoc.body.textContent.trim();

                  const tvshDoc  = parser.parseFromString(d[17] || '', 'text/html');
                  const tvshPs   = tvshDoc.querySelectorAll('p');
                  const tvshItems = [];
                  for (let i = 0; i < tvshPs.length; i += 2) {
                      const label = tvshPs[i]?.textContent?.trim() || '';
                      const value = tvshPs[i + 1]?.textContent?.trim() || '';
                      if (label) tvshItems.push({ label, value });
                  }

                  const doc   = parser.parseFromString(theProdsHtml, 'text/html');
                  const spans = doc.querySelectorAll('span');

                  const items = [];
                  for (let i = 0; i < spans.length; i += 2) {
                      const nameSpan  = spans[i]?.textContent?.trim() || '';
                      const priceSpan = spans[i + 1]?.textContent?.trim() || '';
                      if (nameSpan) items.push({ label: nameSpan, price: priceSpan });
                  }

                  const W   = 48;
                  const SEP = '='.repeat(W);
                  const sep = '-'.repeat(W);

                  function row(left, right) {
                      return left + ' '.repeat(Math.max(1, W - left.length - right.length)) + right;
                  }

                  const address = 'http://' + printerIp + '/cgi-bin/epos/service.cgi?devid=local_printer&timeout=60000';
                  const epos    = new epson.ePOSPrint(address);
                  const builder = new epson.ePOSBuilder();

                  epos.onreceive = function (res) {
                      if (!res.success) console.error('Print failed:', res.code);
                  };

                  // HEADER
                  builder.addTextAlign(builder.ALIGN_CENTER);
                  builder.addTextSize(2, 2);
                  const nameLines = wrapWords(resName, Math.floor(W / 2));
                  nameLines.forEach(line => builder.addText(line + '\n'));
                  builder.addTextSize(1, 1);
                  builder.addTextStyle(false, false, true, builder.COLOR_1);
                  builder.addText('Rechnung\n');
                  builder.addTextStyle(false, false, false, builder.COLOR_1);
                  if (resAdrsText) {
                      resAdrsText.split('\n').filter(l => l.trim()).forEach(line => {
                          builder.addText(line.trim() + '\n');
                      });
                  }
                  builder.addText(SEP + '\n');

                  // TABLE + TIME + IDs + WAITER
                  builder.addTextAlign(builder.ALIGN_LEFT);
                  builder.addText(row(tableNrShow, timePrint) + '\n');
                  builder.addText(row('Rechnung #: ' + orderId, 'Verkaufs-ID: ' + orderRefId) + '\n');
                  builder.addText('Kellner: ' + waitersName + '\n');

                  if (resComment && resComment != 'none') {
                      builder.addText(SEP + '\n');
                      builder.addText(resComment + '\n');
                  }

                  builder.addText(SEP + '\n');

                  // PRODUCTS
                  builder.addTextStyle(false, false, true, builder.COLOR_1);
                  builder.addText('Produkt'.padEnd(38) + 'Preis\n');
                  builder.addTextStyle(false, false, false, builder.COLOR_1);
                  builder.addText(sep + '\n');

                  items.forEach(function (item) {
                      const maxLen = 36;
                      let   label  = item.label;
                      const price  = item.price.padStart(10);
                      const lines  = [];

                      while (label.length > maxLen) {
                          lines.push(label.substring(0, maxLen));
                          label = label.substring(maxLen);
                      }
                      lines.push(label);

                      builder.addText(lines[0].padEnd(38) + price + '\n');
                      for (let n = 1; n < lines.length; n++) {
                          builder.addText('  ' + lines[n] + '\n');
                      }
                  });

                  builder.addText(SEP + '\n');

                  // PAYMENT SUMMARY
                  builder.addTextAlign(builder.ALIGN_CENTER);
                  builder.addTextStyle(false, false, true, builder.COLOR_1);
                  builder.addText(ThePaymentMethod + '\n');
                  builder.addTextStyle(false, false, false, builder.COLOR_1);
                  builder.addTextAlign(builder.ALIGN_LEFT);

                  builder.addText(row('Gesamtsumme:', totalToPay + ' CHF') + '\n');
                  builder.addText(row('Geschenkkarte:', gcDiscount + ' CHF') + '\n');
                  builder.addText(row('Rabatt:', staffDiscount + ' CHF') + '\n');
                  builder.addText(row('Kellner Trinkgeld:', bakshishi + ' CHF') + '\n');

                  tvshItems.forEach(item => {
                      builder.addText(row(item.label, item.value) + '\n');
                  });

                  builder.addText(SEP + '\n');

                  // POS CARD DATA
                  if (hasPOSData === 'Yes') {
                      const trxDateFmt = TrxDate.slice(-2) + '.' + TrxDate.slice(2, 4) + '.' + TrxDate.slice(0, 2);
                      const trxTimeFmt = TrxTime.slice(0, 2) + ':' + TrxTime.slice(2, 4) + ':' + TrxTime.slice(-2);

                      builder.addTextAlign(builder.ALIGN_CENTER);
                      builder.addText(DisplayName + ' contactless\n');
                      builder.addText(AppPANPrtCardholder + '\n');
                      builder.addTextAlign(builder.ALIGN_LEFT);

                      builder.addText(row(trxDateFmt, trxTimeFmt) + '\n');
                      builder.addText(row('Trm-Id:', TrmID) + '\n');
                      builder.addText(row('Akt-Id:', 'x') + '\n');
                      builder.addText(row('AID:', aid) + '\n');
                      builder.addText(row('Trx. Seq-Cnt:', TrxSeqCnt) + '\n');
                      builder.addText(row('Trx. Ref-No:', TrxRefNum) + '\n');
                      builder.addText(row('Auth. Code:', AuthC) + '\n');
                      builder.addText(row('Acq-Id:', AcqID) + '\n');

                      builder.addTextAlign(builder.ALIGN_CENTER);
                      builder.addText(AppPANEnc + '\n');
                      builder.addTextAlign(builder.ALIGN_LEFT);

                      builder.addTextSize(2, 1);
                      builder.addText(row('Total-EFT CHF:', parseFloat(TrxAmt).toFixed(2)) + '\n');
                      builder.addTextSize(1, 1);

                      builder.addText(SEP + '\n');
                  }

                  // QR + FOOTER
                  const qrUrl = 'storage/digitalReceiptQRK/' + orderQrCodeName;
                  builder.addTextAlign(builder.ALIGN_CENTER);
                  addImageToPrint(builder, qrUrl, function () {
                      builder.addTextAlign(builder.ALIGN_CENTER);
                      builder.addText('\nWir danken Ihnen fur die Nutzung\n');
                      builder.addText('von QRorpa.ch\n');
                      builder.addText(resName + ' erwartet Sie wieder\n');
                      builder.addFeedLine(4);
                      builder.addCut(builder.CUT_FEED);
                      epos.send(builder.toString());
                  });
              },
              error: (error) => console.log(error)
          });
      }


      function wrapWords(text, maxChars) {
          const words = text.split(' ');
          const lines = [];
          let current = '';
          words.forEach(word => {
              if ((current + (current ? ' ' : '') + word).length > maxChars) {
                  if (current) lines.push(current);
                  current = word;
              } else {
                  current = current ? current + ' ' + word : word;
              }
          });
          if (current) lines.push(current);
          return lines;
      }
      
      function addImageToPrint(builder, imageUrl, callback) {
          const img = new Image();
          img.crossOrigin = 'Anonymous';
          img.onload = function () {
              const size    = 150; // px — adjust for desired print size
              const canvas  = document.createElement('canvas');
              canvas.width  = size;
              canvas.height = size;
              const ctx = canvas.getContext('2d');
              ctx.fillStyle = '#ffffff';
              ctx.fillRect(0, 0, size, size);
              ctx.drawImage(img, 0, 0, size, size);
              builder.addImage(ctx, 0, 0, size, size);
              callback();
          };
          img.src = imageUrl;
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