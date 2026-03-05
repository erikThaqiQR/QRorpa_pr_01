<div class="modal fade" id="optionsModal">
                      <div class="modal-dialog modal-sm" >
                          <div class="modal-content" style="border-radius:30px;">

                              <!-- Modal body -->
                              <div class="modal-body ">
                                  <div class="container">
                                      <div class="row">
                                          <div class="col-12 text-center">
                                              <?php

                                                  if(isset($_SESSION['Res']) && isset($_SESSION['t'])){
                                                      echo '<a class="navbar-brand" href="/?Res='.$_SESSION["Res"].'&t='.$_SESSION["t"].'">';
                                                  }else{
                                                      echo '<a class="navbar-brand" href="/">';
                                                  }
                                              ?>
                                                  <img style="width:130px" src="/storage/images/logo_QRorpa.png" alt="">
                                              </a>
                                              <hr>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-12 text-center">
                                            <a class="optionsAnchorPh pb-5" href="{{route('dash.statistics')}}">
                                                {{__('adminP.dashboard')}}      
                                            </a>
                                            <br><br>
                                            <a class="optionsAnchorPh pb-5" href="{{route('dash.index')}}">
                                                {{__('adminP.orders'')}}
                                            </a>
                                            <br><br>
                                            <a class="optionsAnchorPh pb-5" href="{{route('dash.recom')}}">
                                                {{__('adminP.suggestedProducts')}}
                                            </a>
                                          </div>
                                      </div>

                                      <div class="row">
                                          <div class="col-12 text-center">
                                              <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

<nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-left">
                <?php
                    if(isset($_SESSION['Res']) && isset($_SESSION['t'])){
                        echo '<a class="navbar-brand" href="/?Res='.$_SESSION["Res"].'&t='.$_SESSION["t"].'">';
                    }else{
                        echo '<a class="navbar-brand" href="/">';
                    }
                ?>
                <img style="width:130px" src="/storage/images/logo_QRorpa.png" alt="">
                          </a>
            </div>
        </div>
          
        <div class="row">
            <div class="col-12" >
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img class="mr-4" src="storage/icons/listDown.PNG"/></button> 
            </div>
      </div>
    </div>
</nav>