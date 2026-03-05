        <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">


<style>
.checked {
  color: orange !important;
}
span.fa.fa-star{
  color: #464545;
}
.table{
  margin-top:50px;
}
</style>




    <div class="container">
        <div class="row">
            <div class="col-12 text-left">
                <p class="color-qrorpa" style="font-size:25px; margin-bottom:-5px;"><strong>Bewertungen</strong></p>
            </div>
        </div>

    </div>

    <br>
<div class="container">
        <div class="d-flex justify-content-between">
            <div style="width:19.80%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" >
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    </h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;"> {{ $countRatings5 }}</p>

            </div>

            <div style="width:19.80%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" >
                   <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star"></span>
                </h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;"> {{ $countRatings4 }}</p>

            </div>

            <div style="width:19.80%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" >
                   <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star "></span>
                    <span class="fa fa-star "></span>
                </h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;"> {{ $countRatings3 }}</p>

            </div>

            <div style="width:19.80%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" >
                   <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star "></span>
                    <span class="fa fa-star "></span>
                    <span class="fa fa-star "></span>
                </h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;"> {{ $countRatings2 }}</p>
            

            </div>
            <div style="width:19.80%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" >
                   <span class="fa fa-star checked"></span>
                    <span class="fa fa-star "></span>
                    <span class="fa fa-star "></span>
                    <span class="fa fa-star "></span>
                    <span class="fa fa-star "></span>
                </h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;"> {{ $countRatings1 }}</p>
            

            </div>
        

        </div>
     
  <table class="table">
  
  <thead>
    <tr>
      <th scope="col">Bewertungen</th>
      <th scope="col">Kommentar</th>
      <th scope="col">Datum</th>
    </tr>
  </thead>
  <tbody>
  	 @foreach($ratings as $rating)
    <tr>
    	
      <td>
        @if($rating->stars == 5)
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
          @elseif($rating->stars == 4)
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star "></span>
          @elseif($rating->stars == 3)
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star "></span>
            <span class="fa fa-star "></span>
          @elseif($rating->stars == 2)
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star "></span>
            <span class="fa fa-star "></span>
            <span class="fa fa-star "></span>
          @elseif($rating->stars == 1)
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star "></span>
            <span class="fa fa-star "></span>
            <span class="fa fa-star "></span>
            <span class="fa fa-star "></span>
        @endif
      </td>
      <td>{{$rating->comment}}</td>
      <td>{{$rating->created_at}}</td>
     
    </tr>
   	@endforeach
  </tbody>
</table>
		
</div>

