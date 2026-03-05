  <!-- Barbershop Smartphone Nav -->
  <nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-12 text-left">
                          <?php
                              if(isset($_SESSION['Bar'])){
                                  echo '<a class="navbar-brand" href="/?Bar='.$_SESSION["Bar"].'">';
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
                         <button  type="button" class="btn" data-toggle="modal" data-target="#optionsModalBar"><img src="/storage/icons/listDown.PNG"/></button> 
                      </div>
                  </div>
              </div>
          </nav>