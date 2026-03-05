<?php
    use App\Barbershop;
    use App\BarbershopRating;
?>

<style>
    .color-yellow{
        color:yellow;
    }
</style>
<section class="pr-3 pl-3 pt-4 pb-5"> 
    <h3 class="color-qrorpa"><strong>die Wertung</strong></h3>
    <hr>


    <div class="d-flex flex-wrap justify-content-between  " id="allBarRatings">
        <p style="width:10%; font-size:19px;" class="color-qrorpa"><strong>Friseur</strong></p>
        <p style="width:15%; font-size:19px;" class="color-qrorpa"><strong>Bewertung</strong></p>
        <p style="width:10%; font-size:19px;" class="color-qrorpa"><strong>Titel</strong></p>
        <p style="width:10%; font-size:19px;" class="color-qrorpa"><strong>Spitzname</strong></p>
        <p style="width:15%; font-size:19px;" class="color-qrorpa"><strong>e-mail</strong></p>
        <p style="width:15%; font-size:19px;" class="color-qrorpa"><strong>Kommentar</strong></p>
        <p style="width:10%; font-size:19px;" class="color-qrorpa"><strong>Nutzer</strong></p>
        <p style="width:15%; font-size:19px;" class="color-qrorpa"><strong>überprüfen / löschen</strong></p>

        @foreach(BarbershopRating::all()->sortByDesc('created_at') as $oseR)
            @if($oseR->verified == 0)
                <div class="d-flex justify-content-between" style="width:100%; background-color:rgb(255,0,0,0.12);">
            @else
                <div class="d-flex justify-content-between" style="width:100%; background-color:rgb(39,190,175,0.12);">
            @endif
       
                <p style="width:10%" class="color-text mb-1 pt-1"><strong>
                    @if(Barbershop::find($oseR->bar_id) != null)
                        {{Barbershop::find($oseR->bar_id)->emri}}
                    @else
                        Nicht gefunden
                    @endif
                </strong></p>
                <p style="width:15%" class="color-text mb-1  pt-1">
                    @if($oseR->stars == 5)
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star color-yellow" ></span>
                    @elseif($oseR->stars == 4)
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star " ></span>
                    @elseif($oseR->stars == 3)
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star" ></span>
                        <span class="fa fa-star" ></span>
                    @elseif($oseR->stars == 2)
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star" ></span>
                        <span class="fa fa-star" ></span>
                        <span class="fa fa-star" ></span>
                    @elseif($oseR->stars == 2)
                        <span class="fa fa-star color-yellow" ></span>
                        <span class="fa fa-star" ></span>
                        <span class="fa fa-star" ></span>
                        <span class="fa fa-star" ></span>
                        <span class="fa fa-star" ></span>
                    @endif
                </p>
                <p style="width:10%" class="color-text mb-1 pt-1"><strong>{{$oseR->title}}</strong></p>
                <p style="width:10%" class="color-text mb-1 pt-1"><strong>{{$oseR->nickname}}</strong></p>
                <p style="width:15%" class="color-text mb-1 pt-1"><strong>{{$oseR->email}}</strong></p>
                <p style="width:15%" class="color-text mb-1 pt-1"><strong>{{$oseR->comment}}</strong></p>
                <p style="width:10%" class="color-text mb-1 pt-1"><strong>{{$oseR->byId}}</strong></p>

                <div style="width:15%" class="color-qrorpa d-flex justify-content-between mb-1">
                    <button style="width:49%;" class="btn btn-outline-info" onclick="verifyBarRating('{{$oseR->id}}')">überprüfen</button>
                    <button style="width:49%;" class="btn btn-outline-danger" onclick="deleteBarRating('{{$oseR->id}}')">löschen</button>
                </div>
            </div>
        @endforeach
    </div>
</section>


<script>
    function verifyBarRating(BRid){
        $.ajax({
			url: '{{ route("restaurantRatings.verifyBarbershopR") }}',
			method: 'post',
			data: {
				id: BRid,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#allBarRatings").load(location.href+" #allBarRatings>*","");
			},
			error: (error) => {
				console.log(error);
				alert('bitte aktualisieren und erneut versuchen!');
			}
		});
    }


    function deleteBarRating(BRid){
        $.ajax({
			url: '{{ route("restaurantRatings.deleteBarbershopR") }}',
			method: 'post',
			data: {
				id: BRid,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#allBarRatings").load(location.href+" #allBarRatings>*","");
			},
			error: (error) => {
				console.log(error);
				alert('bitte aktualisieren und erneut versuchen!');
			}
		});
    }
</script>