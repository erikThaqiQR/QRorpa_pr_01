        <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
<?php
    use App\Orders;
    use App\Restorant;
                           

?>

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
       
     
  <table class="table">
  
  <thead>
    <tr>
      <th scope="col">Datum/Zeit</th>
      <th scope="col">Bewertung</th>
      <th scope="col">Spitzname</th>
      <th scope="col">Titel</th>
      <th scope="col">Email</th>
      <th scope="col">Bewertung</th>
      <th scope="col">Restaurant</th>
      <th scope="col">Aktion</th>
    </tr>
  </thead>
  <tbody>
  	 @foreach($restaurantsRatings as $resRating)
    <tr>
    	<td>
        {{explode(' ',$resRating->created_at)[0]}} <br>
        {{explode(' ',$resRating->created_at)[1]}} <br>
        @if($resRating->for100 != 0)
          <strong>Kandidat für 100 punkte</strong>
        @endif

      </td>
      <td>{{$resRating->stars}}</td>
      <td>{{$resRating->nickname}}</td>
      <td>{{$resRating->title}}</td>
      <td>{{$resRating->email}}</td>
      <td>{{$resRating->comment}}</td>
      <td>@if(Restorant::find($resRating->restaurant_id) != null)    
                                        {{Restorant::find($resRating->restaurant_id)->emri}}
                                    @endif</td>
      <td>

         {{Form::open(['action' => ['RestaurantRatingsController@confirmRating', $resRating->id], 'method' => 'get']) }}

                  <div class="form-group text-center">
                  @if($resRating->verified == 0)
                        {{ Form::submit('Bestätigen', ['class' => 'form-control btn btn-block btn-danger', 'style' => 'margin-top:5px; margin-bottom:-5px;','target'=>'_blank']) }}
                  @elseif($resRating->verified == 1)
                      {{ Form::submit('Bestätigt', ['class' => 'form-control btn btn-block btn-success', 'style' => 'margin-top:5px; margin-bottom:-5px;', 'id'=>'downloaded']) }}
                 
                   @endif
                  </div>

              {{Form::close() }}

      </td>
     
    </tr>
   	@endforeach
  </tbody>
</table>
		
</div>

