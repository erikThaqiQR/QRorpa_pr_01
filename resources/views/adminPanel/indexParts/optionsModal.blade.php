<?php
    use App\accessControllForAdmins;
    use Carbon\Carbon;
    use App\Orders;
?>
              <!-- The Modal -->
              <div class="modal" id="optionsModalBar">
                <div class="modal-dialog">
                  <div class="modal-content" style="border-radius:30px;">

                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color:rgb(39, 190, 175);">
                      <h4 style="color:white;" class="modal-title"><strong>{{__('adminP.administrationMenu')}}</strong></h4>
                      <button style="color:white;" type="button" class="close" data-dismiss="modal"><strong>X</strong></button>
                    </div>

                    <!-- Modal body -->
                    <div style="background-color:whitesmoke; width:100%;">
                      <div style="background-color:whitesmoke; width:100%;" class="mt-2">
                        <div>

                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexStatistics')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-chart-line pr-2"></i>  {{__('adminP.statistics')}}      
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexReservierung')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-hand-holding-medical pr-2"></i> {{__('adminP.assignments')}}      
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.addReservationAdminPage')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-plus pr-2"></i> {{__('adminP.registerReservation')}}      
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexAllConfirmedRez')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-hand-holding pr-2"></i> {{__('adminP.reservation')}}      
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexRecomendetSer')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-hand-holding-heart pr-2"></i> {{__('adminP.recommended')}}      
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexCuponMng')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-barcode pr-2"></i> {{__('adminP.coupons')}}     
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexWorker')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-users-cog pr-2"></i> {{__('adminP.worker')}}      
                          </a>

                        </div>
                      </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-block btn-outline-dark" data-dismiss="modal"><strong>{{__('adminP.conclude')}}</strong></button>
                    </div>

                  </div>
                </div>
              </div>