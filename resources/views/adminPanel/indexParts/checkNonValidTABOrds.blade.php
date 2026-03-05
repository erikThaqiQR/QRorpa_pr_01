<script>
        $.ajax({
			url: '{{ route("invTabOr.sendCheck") }}',
			method: 'post',
			data: {
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = $.trim(res);
                if(res == 'none'){
                    console.log('0 Tab orders removed');
                }else{
                    console.log(res+' Tab orders removed');
                    $("#tableStatDiv").load(location.href+" #tableStatDiv>*","");
                    $("#allTabOrderTNon").load(location.href+" #allTabOrderTNon>*","");

					$("#tableCapRezClTel").load(location.href+" #tableCapRezClTel>*","");
					$("#allTabOrderTNonTel").load(location.href+" #allTabOrderTNonTel>*","");
                }
			},
			error: (error) => {
				console.log(error);
			}
		});
    var intervalId = window.setInterval(function(){
        $.ajax({
			url: '{{ route("invTabOr.sendCheck") }}',
			method: 'post',
			data: {
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = $.trim(res);
                if(res == 'none'){
                    console.log('0 Tab orders removed');
                }else{
                    console.log(res+' Tab orders removed');
                    $("#tableStatDiv").load(location.href+" #tableStatDiv>*","");
                    $("#allTabOrderTNon").load(location.href+" #allTabOrderTNon>*","");

					$("#tableCapRezClTel").load(location.href+" #tableCapRezClTel>*","");
					$("#allTabOrderTNonTel").load(location.href+" #allTabOrderTNonTel>*","");
                }
			},
			error: (error) => {
				console.log(error);
			}
		});
    }, 1800000);
</script>