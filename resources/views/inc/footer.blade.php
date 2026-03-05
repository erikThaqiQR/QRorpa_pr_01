   <style>
       .text-center i{
            padding: 10px;
            margin-bottom: 20px;
       }
       #footer{
            position: absolute;
            width: 100%;
       }
      
   </style>

   <footer id="footer" class="text-center" style="background-color: rgb(72, 81, 87);">
        
        <div class="container text-center">
            <a href="https://www.facebook.com/qrorpa"><i class="fa fa-facebook-square fa-lg" aria-hidden="true"></i></a>
              <a href="https://www.instagram.com/qrorpa"> <i class="fa fa-instagram fa-lg" aria-hidden="true"></i></a>

            <p style="margin-top:-10px;"><strong>{{__('inc.copyright')}} &copy; {{Date('Y')}} </strong></p>
        </div>

        

        <div class="container text-center">
            @if (isset($_SESSION["Res"]) && ($_SESSION["Res"] == 22 || $_SESSION["Res"] == 23))
            <div class="row" style="padding-bottom: 70px;">
            @else
            <div class="row">
            @endif
            
                <div class="col-lg-1 col-sm-0 col-0"></div>

                <div class="col-lg-2 col-sm-3 col-4">
                    <p><a href="https://qrorpa.ch/storage/Impressum_AGB_Gast_Datenschutz.pdf" download><strong>{{__('inc.agbs')}}</strong></a></p>
                </div>

                <div class="col-lg-2 col-sm-3 col-4">
                    <p><a href="https://qrorpa.ch/#kontakt"><strong>{{__('inc.contact')}}</strong></a></p>
                </div>

                <div class="col-lg-2 col-sm-6 col-4">
                    <p>{{__('inc.support')}}</p>
                </div>

                <div class="col-lg-2 col-sm-3 col-6">
                    <p><a href="https://qrorpa.ch/storage/Impressum_AGB_Gast_Datenschutz.pdf" download><strong>{{__('inc.private')}}</strong></a></p>
                </div>

                <div class="col-lg-2 col-sm-6 col-6">
                    <p><a href="https://qrorpa.ch/storage/Impressum_AGB_Gast_Datenschutz.pdf" download><strong>{{__('inc.impressum')}}</strong></a></p>
                </div>

               

                <div class="col-lg-1 col-sm-0 col-0"></div>
             
            </div>
        </div>
    </footer>