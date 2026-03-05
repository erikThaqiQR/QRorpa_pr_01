<style>
/* The switch - the box around the slider */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    float: right;
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
</style>



<style>
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


[type=radio] { 
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

.rating { 
  border: none;
  float: left;
}

.rating > input { display: none; } 
.rating > label:before { 
  margin: 5px;
  font-size: 1.25em;
  font-family: FontAwesome;
  display: inline-block;
  content: "\f005";
}

.rating > .half:before { 
  content: "\f089";
  position: absolute;
}

.rating > label { 
  color: #ddd; 
 float: right; 
}

/***** CSS Magic to Highlight Stars on Hover *****/

.rating > input:checked ~ label, /* show gold star when clicked */
.rating:not(:checked) > label:hover, /* hover current star */
.rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

.rating > input:checked + label:hover, /* hover current star when changing rating */
.rating > input:checked ~ label:hover,
.rating > label:hover ~ input:checked ~ label, /* lighten current selection */
.rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 


label.control-label{
    width: 100%;
}

</style>

@if(isset($_GET['Res']))
   <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#ratingsM">{{__('inc.reviews')}}</button>
<!-- Modal -->
<div id="ratingsM" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
       {{Form::open(['action' => 'RatingController@store', 'method' => 'post']) }}

                       

                        <fieldset class="rating">
                            <input type="radio" id="star5" name="stars" value="5" /><label class = "full" for="star5" title="{{__('inc.awesome5Stars')}}"></label>   
                            <input type="radio" id="star4" name="stars" value="4" /><label class = "full" for="star4" title="{{__('inc.prettyGood4Stars')}}"></label>
                            <input type="radio" id="star3" name="stars" value="3" /><label class = "full" for="star3" title="{{__('inc.meh3Stars')}}"></label>
                            <input type="radio" id="star2" name="stars" value="2" /><label class = "full" for="star2" title="{{__('inc.kindaBad2Stars')}}"></label>
                            <input type="radio" id="star1" name="stars" value="1" /><label class = "full" for="star1" title="{{__('inc.sucksBigTime1Star')}}"></label>
                        </fieldset>
                <br>

                    <div class="form-group">

                        {{ Form::label(__('inc.comment'), null, ['class' => 'control-label']) }}
                        {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => 4, 'cols' => 54]) !!}
                      {{--   {{ Form::text('comment','', ['class' => 'form-control']) }} --}}
                    </div>

                    <div class="form-group">
                        {{ Form::submit(__('inc.send'), ['class' => 'form-control btn btn-outline-primary']) }}
                    </div>

                {{Form::close() }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
      </div>
    </div>

  </div>
</div>
@endif

