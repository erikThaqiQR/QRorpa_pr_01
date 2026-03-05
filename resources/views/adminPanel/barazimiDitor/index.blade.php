

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


  <style>
  * {
  margin: 0;
  padding: 0;
  }
  .form-control{
      background-color:rgb(39,190,175,0.05);
      box-shadow:none;
      border-color:rgb(39,190,175,0.1);
  }
  .form-control:focus{
      background-color:rgb(39,190,175,0.1);
      box-shadow:none;
      border-color:rgb(39,190,175,0.9);
      
  }
  
  .modal-header{
      display:flex;
  }

   /* @media (max-width: 414px) {
   .header{
    margin-right:10px;
  }
}  */

  @media (max-width: 414px) {
   .row{
    margin-left:0px;
  }
}

/* @media (max-width: 414px) {
   .total-class{
    margin-right:15px;
  }
} */
 

@media (max-width: 414px) {
   .container{
    width:100%
  }
}
  

    </style>

    
  
  
    
        <script>
            const coinGr = [0.05,0.10,0.20,0.50,1,2,5,10,20,50,100,200,1000];
        </script>


    <div class="header panel panel-default;" style="margin-left:10px;" > 
      <div class="modal-header" style="background-color:rgb(30,190,175,0.5); margin-top:15px;  width:100%">
        <h2 class="modal-title" id="mngJobModalLabel" style="margin-left:15px" >Kassenzalprotokoll</h2> 
        <button type="button" class="btn-close"  aria-label="Close" style="margin-right:15px; margin-top:25px; margin-bottom:25px; box-shadow:none"></button> 
      </div>
    </div>




<hr style="margin-left:10px">
    <div class="container" style=" margin-top:20px; width:100%; display:flex">
      <div class="row" style="margin-bottom:15px; margin-top:15px">
        <div class=" col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6  col-6" style="margin:0px;">
          <div class="form-group mx-auto">
            <div class="Munzen">
              <h3 style="margin-bottom:15px">Munzen</h3>
            </div>
          </div>
        </div>
        <div class=" col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6  col-6" style="margin:0px;">
          <div class="form-group mx-auto">
            <div class="Munzen">
              <h3 style="margin-bottom:15px">Scheine</h3>
            </div>
          </div>
        </div>
    <?php
    $value = array(0.05,0.10,0.20,0.50,1.00,2.00,5.00,10.00,20.00,50.00,100.00,200.00,1000.00);
    ?>

    @for ($i=0; $i < count($value); $i++)
      <div class=" col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6  col-6" style="margin:0px;">
        <div class="input-label form-group" style="margin:0px;"> 
          <label  style="margin-bottom:10px; " >Wert: <span id="label{{$i}}">{{$value[$i]}} </span> CHF</label>
          <div class="ls-inputicon-box">
              <input style="width:100%; margin-bottom:20px; height:50px" onkeyup="updateTot()" class="form-control amountInp" id="coinCnt{{$i}}" type="number" placeholder="Schreib Menge">
          </div>
        </div>
      </div>             
    @endfor

</div>
</div>
</div>
            
<div class="mb-3 row total-class" id="printableArea" style="background-color:rgb(30,190,175,0.2); padding-top:15px; padding-bottom:15px">
            <label for="value" class="col-sm-2 col-form-label mx-auto" style="font-size:20px; text-align:center">Total value:</label>
            <div class="col-lg-8 col-sm-8 col-xs-8 ">
                <label class="form-control mx-auto" id ="totalCoinCount" style="width:70%; height:50px; background-color:rgb(30,190,175,0.5); text-align:center; font-size:25px;"></label>
            </div>
        </div>

        <div class="buttons d-grid gap-2 col-6 mx-auto">
            <button type="button" class="firstButton btn btn-primary" id="print" style=" background-color:rgb(30,190,145); border-color:white">
                ausdrucken oder als pdf generieren</button>
            <button type="button" class="secondButton btn btn-primary" id="secondButton"  style=" background-color:rgb(30,190,175);border-color:white" >Schlieben</button>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script>
       
        
        // multiply and sum of inputs and labels
        function updateTot(){
        var tot = 0
        $.each( coinGr, function( i, value ) {
          if($('#coinCnt'+i).val()){
            if(i < 4){
              let coinCnt = parseInt($('#coinCnt'+i).val());
              switch(i){
                case 0: tot += parseFloat(coinCnt/20); 
                break;
                case 1: tot += parseFloat(coinCnt/10); 
                break;
                case 2: tot += parseFloat(coinCnt/5); 
                break;
                case 3: tot += parseFloat(coinCnt/2); 
                break;
              }
            }else{
              let coinCnt = parseInt($('#coinCnt'+i).val());
              tot += coinCnt * value; 
            }   
          }   
        });
        $('#totalCoinCount').val(tot);
        $('#totalCoinCount').text(tot);
        console.log('totali: '+parseFloat(tot).toFixed(2));
      }

        
        
        //   Printimi
          // $('.firstButton').click(function(){
          //     window.print();
          //     return false;
          // });

         


          

          

          // validation for dot (.) 

          $(".amountInp").on("keypress", function(evt) {
        var keycode = evt.charCode || evt.keyCode;
        if (keycode == 46) {
          return false;
        }
      });



      </script>



