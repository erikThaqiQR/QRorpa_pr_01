<?php
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Covid-19']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Restorant;
    use App\Covid;

    $thisRestaurantId = Auth::user()->sFor;
  ?>
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
                <p class="color-qrorpa" style="font-size:25px; margin-bottom:-5px;"><strong>Covid-19 {{__('adminP.report')}}</strong></p>
            </div>
        </div>

    </div>

    <br>
<div class="container">
       
     
  <table class="table">
  
  <thead>
    <tr>
      <th scope="col">{{__('adminP.dateTime')}}</th>
      <th scope="col">{{__('adminP.name')}}</th>
      <th scope="col">{{__('adminP.firstName')}}</th>
      <th scope="col">{{__('adminP.address')}}</th>
      <th scope="col">{{__('adminP.phoneNumber')}}</th>
      <th scope="col">{{__('adminP.numberOfPeople')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach(Covid::where('restaurant_id', '=', $thisRestaurantId)->get() as $covid)
  
    <tr>
    	
      <td>
        {{$covid->created_at}}
      </td>
      <td>{{$covid->name}}</td>
      <td>{{$covid->vorname}}</td>
      <td>{{$covid->address}}</td>
      <td>{{$covid->tel}}</td>
      <td>{{$covid->persons}}</td>
     
    </tr>
   	@endforeach
  </tbody>
</table>
		
</div>

