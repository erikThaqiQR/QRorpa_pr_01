<nav class="navbar navbar-expand-sm b-white"  width="100%" id="navDesktop" style="display:none; background-color:white; z-index:2;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-left">
                <a class="navbar-brand" href="#"><img style="width:180px" src="/storage/images/logo_QRorpa.png" alt=""></a>
            </div>
        </div>
        <div class="row">
            <div class="col-12" >
               <button type="button" class="btn btn-default shadow-none" data-toggle="modal" data-target="#optionsModal"><img src="storage/icons/listDown.PNG"/></button> 
            </div>
        </div>
    </div>
</nav>
   


<nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone" style="display:none; background-color:white; z-index:2;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-left">
                <a class="navbar-brand" href="#"><img style="width:140px" src="/storage/images/logo_QRorpa.png" alt=""></a>
            </div>
        </div>

        <div class="row">
            <div class="col-12" >
                
                <button type="button" class="btn btn-default shadow-none" data-toggle="modal" data-target="#optionsModal"><img src="storage/icons/listDown.PNG"/></button> 
            </div>
        </div>
    </div>
</nav>



<script>
    if ((screen.width>580)) {   
        $('#navPhone').hide();
        $('#navDesktop').show();        
    }else if ((screen.width<=580))  {
        $('#navPhone').show();
        $('#navDesktop').hide();
    }
</script>