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
<style type="text/css">
    /* The switch - the box around the slider */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    /* float: right; */
}

/* Hide default HTML checkbox */
.switch input {
    display: none;
}

/* The slider */
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input.default:checked+.slider {
    background-color: #444;
}

input.primary:checked+.slider {
    background-color: #2196F3;
}

input.success:checked+.slider {
    background-color: #8bc34a;
}

input.info:checked+.slider {
    background-color: #3de0f5;
}

input.warning:checked+.slider {
    background-color: #FFC107;
}

input.danger:checked+.slider {
    background-color: #f44336;
}

input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.scrolling {
    /* Note #2 */
    position: fixed;
    top: 0px;
}



.noBorder:active {
    outline: none;
}

.noBorder:focus {
    outline: none;
    box-shadow: none;
}





input[type="number"]:disabled {
    background-color: white;
}

.btn:focus,
.btn:active {
    outline: none !important;
    box-shadow: none;
}


.MultiCarousel {
    float: left;
    overflow: hidden;
    padding: 5px;
    width: 100%;
    position: relative;
}

.MultiCarousel .MultiCarousel-inner {
    transition: 1s ease all;
    float: left;
}

.MultiCarousel .MultiCarousel-inner .item {
    float: left;
}

.MultiCarousel .MultiCarousel-inner .item>div {
    text-align: center;
    padding-left: 50px;
    padding-right: 50px;
    padding: 3px;
    margin: 5px;
    background: #f1f1f1;
    color: #666;
}

.MultiCarousel .leftLst,
.MultiCarousel .rightLst {
    position: absolute;
    border-radius: 50%;
    top: calc(50% - 20px);
}

.MultiCarousel .leftLst {
    left: 0;
}

.MultiCarousel .rightLst {
    right: 0;
}

.MultiCarousel .leftLst.over,
.MultiCarousel .rightLst.over {
    pointer-events: none;
    background: #ccc;
}


.hover-pointer:hover {
    cursor: pointer;
}





#searchBar {
    background-image: url('storage/icons/search.png');
    /* Add a search icon to input */
    background-size: 25px 25px;
    background-position: 10px 12px;
    /* Position the search icon */
    background-repeat: no-repeat;
    /* Do not repeat the icon image */
    width: 100%;
    /* Full-width */
    font-size: 16px;
    /* Increase font-size */
    padding: 12px 20px 12px 40px;
    /* Add some padding */
    border: 1px solid #000;
    /* Add a grey border */
    margin-bottom: 12px;
    /* Add some space below the input */
    opacity: 0.45;
    border-radius: 20px;
}




select {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
    width: 30px !important;
    font-size: 15px !important;
    padding: 8px !important;
    text-align: -webkit-center;
}

.swiper-container{
    background-color:#FFF;
padding-top: 5px !important;
}
.swiper-slide{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}
.swiper-slide img{
    object-fit:cover;

}
.swiper-slide p{
    margin-top:5px;
    margin:0;
}
.rec .teksti{
    margin:0 auto;
    margin-bottom:10px;
}
.rec{
    margin-bottom:10px;
}
.select-stars{
    width: 30px !important;
    font-size: 15px !important;
    padding: 8px !important;
    text-align: -webkit-center;
}
.checked {
  color: orange;
}


input[type='radio'][name='stars2']{ 
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

/* IMAGE STYLES */
/*[type=radio] + img {
  cursor: pointer;
}

 CHECKED STYLES 
[type=radio]:checked + img {
  outline: 2px solid #f00;
}*/

pre { color: crimson; }
.hide { display: none; }
.fa-exclamation-triangle { color: goldenrod; }

input[type="checkbox"] + .fa,
input[type="checkbox"] + label > .fa,
input[type="radio"] + .fa,
input[type="radio"] + label > .fa { cursor: pointer; }

/* Unchecked */
input[type="checkbox"] + .fa-check-circle:before,
input[type="checkbox"] + label > .fa-check-circle:before,
input[type="radio"] + .fa-check-circle:before,
input[type="radio"] + label > .fa-check-circle:before { content: "\f111"; } /* .fa-circle */
input[type="checkbox"] + .fa-dot-circle-o:before,
input[type="checkbox"] + .fa-check-circle-o:before,
input[type="checkbox"] + label > .fa-dot-circle-o:before,
input[type="checkbox"] + label > .fa-check-circle-o:before,
input[type="radio"] + .fa-dot-circle-o:before,
input[type="radio"] + label > .fa-dot-circle-o:before,
input[type="radio"] + .fa-check-circle-o:before,
input[type="radio"] + label > .fa-check-circle-o:before { content: "\f10c"; } /* .fa-circle-o */
input[type="radio"] + .fa-circle:before,
input[type="radio"] + label > .fa-circle:before { content: "\f1db"; } /* .fa-circle-thin */
input[type="checkbox"] + .fa-check:before,
input[type="checkbox"] + .fa-check-square-o:before,
input[type="checkbox"] + label > .fa-check:before,
input[type="checkbox"] + label > .fa-check-square-o:before { content: "\f096"; } /* .fa-square-o */
input[type="checkbox"] + .fa-check-square:before,
input[type="checkbox"] + label > .fa-check-square:before { content: "\f0c8"; } /* .fa-square */

/* Checked */
input[type="checkbox"]:checked + .fa-check:before,
input[type="checkbox"]:checked + label > .fa-check:before { content: "\f00c"; }
input[type="checkbox"]:checked + .fa-check-circle:before,
input[type="checkbox"]:checked + label > .fa-check-circle:before,
input[type="radio"]:checked + .fa-check-circle:before,
input[type="radio"]:checked + label > .fa-check-circle:before { content: "\f058"; }
input[type="checkbox"]:checked + .fa-check-circle-o:before,
input[type="checkbox"]:checked + label > .fa-check-circle-o:before,
input[type="radio"]:checked + .fa-check-circle-o:before,
input[type="radio"]:checked + label > .fa-check-circle-o:before { content: "\f05d"; }
input[type="checkbox"]:checked + .fa-check-square:before,
input[type="checkbox"]:checked + label > .fa-check-square:before { content: "\f14a"; }
input[type="checkbox"]:checked + .fa-check-square-o:before,
input[type="checkbox"]:checked + label > .fa-check-square-o:before { content: "\f046"; }
input[type="radio"]:checked + .fa-circle:before,
input[type="radio"]:checked + label > .fa-circle:before { content: "\f111"; }
input[type="checkbox"]:checked + .fa-dot-circle-o:before,
input[type="checkbox"]:checked + label > .fa-dot-circle-o:before,
input[type="radio"]:checked + .fa-dot-circle-o:before,
input[type="radio"]:checked + label > .fa-dot-circle-o:before { content: "\f192"; }



/****** Style Star Rating Widget *****/

.rating2 { 
  border: none;
  float: left;
}

.rating2 > input[type='radio'][name='stars2'] { display: none; } 
.rating2 > .stars2:before { 
  margin: 5px;
  font-size: 1.25em;
  font-family: FontAwesome;
  display: inline-block;
  content: "\f005";
}

.rating2 > .half:before { 
  content: "\f089";
  position: absolute;
}

.rating2 > .stars2 { 
  color: #ddd; 
 float: right; 
}

/***** CSS Magic to Highlight Stars on Hover *****/

.rating2 > input[type='radio'][name='stars2']:checked ~ .stars2, /* show gold star when clicked */
.rating2:not(:checked) > .stars2:hover, /* hover current star */
.rating2:not(:checked) > .stars2:hover ~ .stars2 { color: #FFD700;  } /* hover previous stars in list */

.rating2 > input[type='radio'][name='stars2']:checked + .stars2:hover, /* hover current star when changing rating */
.rating2 > input[type='radio'][name='stars2']:checked ~ .stars2:hover,
.rating2 > .stars2:hover ~ input[type='radio'][name='stars2']:checked ~ .stars2, /* lighten current selection */
.rating2 > input[type='radio'][name='stars2']:checked ~ .stars2:hover ~ .stars2 { color: #FFED85;  } 


.stars2.control-label{
    width: 100%;
}

</style>

@if($RWHT != null)
        <!-- Ratings Area -->
                       
                                
                                        <h4><i class="fa fa-star" aria-hidden="true"></i> <strong>{{__('inc.reviews')}}</strong></h4>
                                        <div id="bewertungen">
                                            {{-- Ratings Area --}}
                                                <div class="ratings-area">
                                        
                                                <form id="ajaxform2">
                                                    {{csrf_field()}}
                                                    <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
                                                    <div class="form-group text-center" style="display: table-row;">
                                                        <div class="rating-stars text-center">
                                                        
                                                            <fieldset class="rating2 text-center" >
                                                                <strong>{{__('inc.reviews')}}*</strong><br>
                                                                <input type="radio" id="star10" name="stars2" value="5" required /><label class = "full stars2" for="star10" title="{{__('inc.awesome5Stars')}}"></label>   
                                                                <input type="radio" id="star9" name="stars2" value="4" /><label class = "full stars2" for="star9" title="{{__('inc.prettyGood4Stars')}}"></label>
                                                                <input type="radio" id="star8" name="stars2" value="3" /><label class = "full stars2" for="star8" title="{{__('inc.meh3Stars')}}"></label>
                                                                <input type="radio" id="star7" name="stars2" value="2" /><label class = "full stars2" for="star7" title="{{__('inc.kindaBad2Stars')}}"></label>
                                                                <input type="radio" id="star6" name="stars2" value="1" /><label class = "full stars2" for="star6" title="{{__('inc.sucksBigTime1Star')}}"></label>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" placeholder="{{__('inc.nickname')}}*" name="nickname2" class="form-control" placeholder="" required id="nickname2">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" placeholder="{{__('inc.title')}}" name="title2" class="form-control" placeholder="" id="titel2">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="email" placeholder="{{__('inc.email')}}" name="email2" class="form-control" placeholder="" id="email2">
                                                    </div>

                                                    @if(Auth::check())
                                                        <input type="hidden" name="klientiRat2" value="{{Auth::user()->id}}">
                                                    @else
                                                        <input type="hidden" name="klientiRat2" value="0">
                                                    @endif

                                                

                                                    <div class="form-group">
                                                        <label>{{__('inc.review')}}</label>
                                                        <textarea class="form-control"  name="comment2" rows="4" cols="50"></textarea>
                                                        <input type="hidden" name="restaurant_id2" class="form-control" value="{{$theRestaurant->id}}" id="restaurant_i2d">
                                                    </div>
                                                    <input type="hidden" name="verified2" class="form-control" placeholder="" value="0" id="verified2">
                                                
                                                    <div class="form-group d-flex justify-content-between">
                                                        <button class="btn btn-success send-button" id="submit" style="margin-top: 20px;margin-bottom: 20px; width:100%;">{{__('inc.send')}}</button>
                                                    </div>
                                                </form>
                                                    
                                                    <table class="table ratings-table">
                                                    
                                                    
                                                    
                                                            <tbody>
                                                                @foreach($thisRestaurantRatings as $thisres)
                                                                        <tr>
                                                                            <td style="font-weight:bold;">{{$thisres->nickname}} &nbsp&nbsp&nbsp @if($thisres->stars == 5)
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                            @elseif($thisres->stars == 4)
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star "></span>
                                                                            @elseif($thisres->stars == 3)
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                            @elseif($thisres->stars == 2)
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star "></span> 
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                            @elseif($thisres->stars == 1)
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                            @endif
                                                                            <br>
                                                                            <p style="padding:3px; font-size:13px; font-weight:normal;">{{$thisres->comment}}</p>
                                                                        </td>
                                                                        
                                                                        </tr>
                                                                        @endforeach
                                                            
                                                            </tbody>
                                                            
                                                            
                                                    </table>
                                                    

                                                </div>
                                        </div>    
          @endif


</section>

<script>
    //Restaurant Rating Post function
            $('#ajaxform2').on('submit',function(event){
                event.preventDefault();

                restaurant_id = $("input[name=restaurant_id2]").val();
                    nickname = $("input[name=nickname2]").val();
                    stars = $("input[name=stars2]:checked").val();
                   title = $("input[name=title2]").val();
                   email = $("input[name=email2]").val();
                    comment = $("textarea[name=comment2]").val();
                    verified = $("input[name=verified2]").val();
               

                $.ajax({
                  url:"{{route('restaurantRatings.store')}}",
                  type : 'post',
                
                  data:{
                    "_token": "{{ csrf_token() }}",
                    restaurant_id:restaurant_id,
                    nickname:nickname,
                    title:title,
                    email:email,
                    stars:stars,
                    comment:comment,
                    verified:verified,
                    clientBy:$("input[name=klientiRat2]").val()
                  },
                    success:function(data){
                          if(data) {
                            $('.success').text(data.success);
                            $("#ajaxform2")[0].reset();
                          }
                    },
                    error: (error) => {
                        console.log(error);
                    }
                 });
            });
</script>