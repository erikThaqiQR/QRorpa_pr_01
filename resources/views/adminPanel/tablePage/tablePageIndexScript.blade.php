<script>
	var tablesRemovedSearch = [];

    function pad(d) {
        return (d < 10) ? '0' + d.toString() : d.toString();
    }
    function showTime() {
        var today = new Date()
        $("#PageTime1").html(pad(today.getHours())+':'+pad(today.getMinutes())+':'+pad(today.getSeconds()));
    }
    setInterval(showTime, 1000);

    function setTabStat(tNr,tId,newVal){
        $.ajax({
			url: '{{ route("table.tableStatusSet") }}',
			method: 'post',
			data: {
				id: tId,
				val: newVal,
				_token: '{{csrf_token()}}'
			},
			success: () => { $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");
			},error: (error) => { console.log(error);}
		});
    }

	function openNewTabOrderModal(tNr){
		$('#newTabOrderModalActiveTableNr').val(tNr);
		$('#newTabOrderModalTableNrShow').html(tNr);
	}

	function checkForRebuildTable(tNr){
		if($('#tableNeedsToReset'+tNr).val() == 1){
			$("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
			$("#tabOrderTotPriceDiv"+tNr).load(location.href+" #tabOrderTotPriceDiv"+tNr+">*","");
			$("#extraServicesOnTable"+tNr).load(location.href+" #extraServicesOnTable"+tNr+">*","");
			$('#tableNeedsToReset'+tNr).val(0);
		}
	}

	function reloadOrderQRCodeTel(){
		$("#orderQRCodeTelBody").html('<img src="storage/gifs/loading.gif" style="width:30%; margin-left:35%;" alt="">');
		$("#orderQRCodeTel").load(location.href+" #orderQRCodeTel>*","");
	}

	function findThisTableIcon(){
        var val = $('#TischSearchInput').val();
        if((val > 0 && val < 999)){
            $('#TischSearchIcon').css('color','green');
        }else{
            $('#TischSearchIcon').css('color','white');
        }
    }
	function findThisTable(){
        // console.log(val);
        var val = $('#TischSearchInput').val();
        if(val > 0 && val < 999 && val != ''){
            $('#TischSearchIcon').removeClass('fa-search');
            $('#TischSearchIcon').addClass('fa-times');
            $('#TischSearchIcon').css('color','red');
            $('#TischSearchIconDiv').attr('onclick','findThisTableReset()')
            $('.allTablesDes').each(function(i, obj) {
                var tabId = $(this).attr('id');
                var tabNr = tabId.split('Div')[1];
                if(tabNr != val){
                    tablesRemovedSearch.push($('#tableIconDiv'+tabNr));
                    $('#tableIconDiv'+tabNr).remove();
                    // $('#TableCapTel'+tabNr).remove();
                }
            });
        }else{
            $('#findThisTableError01').show(100).delay(2500).hide(100);
        }
    }
	function findThisTableReset(){
        $('#TischSearchIcon').removeClass('fa-times');
        $('#TischSearchIcon').addClass('fa-search');
        $('#TischSearchIcon').css('color','white');
        $('#TischSearchIconDiv').attr('onclick','findThisTable()');
        $('#TischSearchInput').val('');
		$("#allTablePageDiv").load(location.href+" #allTablePageDiv>*","");
        tablesRemovedSearch=[];
    }
</script>