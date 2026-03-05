<?php
    use App\Produktet;
    use App\kategori;
    use App\ekstra;
    use App\LlojetPro;
    use App\RecomendetProd;
    use App\resdemoalfa;
    use Carbon\Carbon;
    use App\Restorant;
    use App\RestaurantWH;
    use App\RestaurantRating;



    if(isset($_GET['Res'])){
        $theRes = $_GET['Res'];
        $theRestaurant = Restorant::find($_GET["Res"]);
        $RWHT = RestaurantWH::where('toRes', $_GET['Res'])->first();
        $thisRestaurantRatings = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->orderBy('updated_at','DESC')->get();
        $thisRestaurantRatingsSum = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->sum('stars');
        $thisRestaurantRaringAverage = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->avg('stars');
    }
    
?>
<section>
    

@if($RWHT != null)
                 

                            
                                       @if(Restorant::find($_GET['Res'])->resDesc != 'none')
                                  <p class="restaurant-description"> <strong>{{__('inc.description')}}:</strong>{{Restorant::find($_GET['Res'])->resDesc}}</p> 
                                  @endif
                                <h5> <i class="fa fa-clock-o" aria-hidden="true"></i> <strong>{{__('inc.openTime')}}:</strong></h5>

                                   
                                    <table class="table info-table" style="width: 100%; background-color: #fafafa;">
                                        <tbody>
                                            <tr>
                                                <td valign="top">{{__('inc.monday')}} </td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D1Starts1 != "none" && $RWHT->D1End1 != "none" && $RWHT->D1Starts2 != "none" && $RWHT->D1End2 != "none")
                                                        <strong>{{$RWHT->D1Starts1}} - {{$RWHT->D1End1}}</strong> {{__('inc.and')}} <strong>{{$RWHT->D1Starts2}} - {{$RWHT->D1End2}}</strong>
                                                    @elseif($RWHT->D1Starts1 != "none" && $RWHT->D1End1 != "none")
                                                        <strong>{{$RWHT->D1Starts1}} - {{$RWHT->D1End1}}</strong>
                                                    @else
                                                        <strong>{{__('inc.openTime')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('inc.tuesday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D2Starts1 != "none" && $RWHT->D2End1 != "none" && $RWHT->D2Starts2 != "none" && $RWHT->D2End2 != "none")
                                                        <strong>{{$RWHT->D2Starts1}} - {{$RWHT->D2End1}}</strong> {{__('inc.and')}} <strong>{{$RWHT->D2Starts2}} - {{$RWHT->D2End2}}</strong>
                                                    @elseif($RWHT->D2Starts1 != "none" && $RWHT->D2End1 != "none")
                                                        <strong>{{$RWHT->D2Starts1}} - {{$RWHT->D2End1}}</strong>
                                                    @else
                                                        <strong>{{__('inc.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('inc.wednesday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D3Starts1 != "none" && $RWHT->D3End1 != "none" && $RWHT->D3Starts2 != "none" && $RWHT->D3End2 != "none")
                                                        <span> <strong>{{$RWHT->D3Starts1}} - {{$RWHT->D3End1}}</strong> {{__('inc.and')}} <strong>{{$RWHT->D3Starts2}} - {{$RWHT->D3End2}}</strong></span>
                                                    @elseif($RWHT->D3Starts1 != "none" && $RWHT->D3End1 != "none")
                                                        <strong>{{$RWHT->D3Starts1}} - {{$RWHT->D3End1}}</strong>
                                                    @else
                                                        <strong>{{__('inc.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('inc.thursday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D4Starts1 != "none" && $RWHT->D4End1 != "none" && $RWHT->D4Starts2 != "none" && $RWHT->D4End2 != "none")
                                                        <strong>{{$RWHT->D4Starts1}} - {{$RWHT->D4End1}}</strong> {{__('inc.and')}} <strong>{{$RWHT->D4Starts2}} - {{$RWHT->D4End2}}</strong>
                                                    @elseif($RWHT->D4Starts1 != "none" && $RWHT->D4End1 != "none")
                                                        <strong>{{$RWHT->D4Starts1}} - {{$RWHT->D4End1}}</strong>
                                                    @else
                                                        <strong>{{__('inc.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('inc.friday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D5Starts1 != "none" && $RWHT->D5End1 != "none" && $RWHT->D5Starts2 != "none" && $RWHT->D5End2 != "none")
                                                        <strong>{{$RWHT->D5Starts1}} - {{$RWHT->D5End1}}</strong> {{__('inc.and')}} <strong>{{$RWHT->D5Starts2}} - {{$RWHT->D5End2}}</strong>
                                                    @elseif($RWHT->D5Starts1 != "none" && $RWHT->D5End1 != "none")
                                                       <strong> {{$RWHT->D5Starts1}} - {{$RWHT->D5End1}}</strong>
                                                    @else
                                                        <strong>{{__('inc.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('inc.saturday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D6Starts1 != "none" && $RWHT->D6End1 != "none" && $RWHT->D6Starts2 != "none" && $RWHT->D6End2 != "none")
                                                        <strong>{{$RWHT->D6Starts1}} - {{$RWHT->D6End1}}</strong> {{__('inc.and')}} <strong>{{$RWHT->D6Starts2}} - {{$RWHT->D6End2}}</strong>
                                                    @elseif($RWHT->D6Starts1 != "none" && $RWHT->D6End1 != "none")
                                                        <strong>{{$RWHT->D6Starts1}} - {{$RWHT->D6End1}}</strong>
                                                    @else
                                                        <strong>{{__('inc.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('inc.sunday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D7Starts1 != "none" && $RWHT->D7End1 != "none" && $RWHT->D7Starts2 != "none" && $RWHT->D7End2 != "none")
                                                        <strong>{{$RWHT->D7Starts1}} - {{$RWHT->D7End1}}</strong> {{__('inc.and')}} <strong>{{$RWHT->D7Starts2}} - {{$RWHT->D7End2}}</strong>
                                                    @elseif($RWHT->D7Starts1 != "none" && $RWHT->D7End1 != "none")
                                                       <strong> {{$RWHT->D7Starts1}} - {{$RWHT->D7End1}}</strong>
                                                    @else
                                                        <strong>{{__('inc.restDay')}}</strong>
                                                    @endif</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                
                                <h5 style="margin-top:40px;"> <i class="fa fa-location-arrow" aria-hidden="true"></i> <strong>{{__('inc.address')}}:</strong></h5>
                                
                                <p style=" padding: 5px;">
                                    @if(Restorant::find($_GET['Res'])->map != 'none')
                                    <iframe src="{{Restorant::find($_GET['Res'])->map}}"
                                        width="100%" height="350" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                    @else
                                     <div id="map"> <iframe width='100%' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'
                                      src='https://maps.google.com/maps?&amp;q="+ {{Restorant::find($_GET['Res'])->adresa}} + "&amp;hl=de&amp;output=embed'></iframe></div>
                                    @endif

                                    {{Restorant::find($_GET['Res'])->emri}}<br>
                                    {{Restorant::find($_GET['Res'])->adresa}}</p>
                  
                         
                    @endif


</section>