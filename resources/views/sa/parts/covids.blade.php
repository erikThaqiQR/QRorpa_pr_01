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
                <p class="color-qrorpa" style="font-size:25px; margin-bottom:-5px;"><strong>Covid-19 Bericht</strong></p>
            </div>
        </div>

    </div>

    <br>
<div class="container">
       
     
  <table class="table">
  
  <thead>
    <tr>
      <th scope="col">Datum/Zeit</th>
      <th scope="col">Restaurant</th>
      <th scope="col">Name</th>
      <th scope="col">Vorname</th>
      <th scope="col">Adresse</th>
      <th scope="col">Telefonnummer</th>
      <th scope="col">Anzahl Personen</th>
    </tr>
  </thead>
  <tbody>
  	 @foreach($covids as $covid)
    <tr>
    	
      <td>
        {{$covid->created_at}}
      </td>
      <td>{{$covid->name}}</td>
      <td>@if(Restorant::find($covid->restaurant_id) != null)    
                                        {{Restorant::find($covid->restaurant_id)->emri}}
                                    @endif</td>
      <td>{{$covid->vorname}}</td>
      <td>{{$covid->address}}</td>
      <td>{{$covid->tel}}</td>
      <td>{{$covid->persons}}</td>
     
    </tr>
   	@endforeach
  </tbody>
</table>
		
</div>

