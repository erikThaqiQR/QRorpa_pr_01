<div id="checkTabChngReqsAdminModal" class="modal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:4%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="text-center" style="font-weight: bold;" id="checkTabChngReqsAdminModalTitle"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-dark" onclick="checkTabChngReqsAdminConfirm()" data-dismiss="modal">{{__('inc.okNext')}}</button>
                <input type="hidden" id="checkTabChngReqsAdminModalReqId" value="0">
            </div>
        </div>
    </div>
</div>







<script>
    var checkTabChngReqs=setInterval(checkTabChngReqsAdmin,5000);
    function checkTabChngReqsAdmin(){
        if(!$('#checkTabChngReqsAdminModal').hasClass('show') && $('#verifiedNr007').val()){
            $.ajax({
                url: '{{ route("dash.adminReqClTableChangeCheck") }}',
                method: 'post',
                data: {
                    myRes: '{{$_GET["Res"]}}',
                    myTable: '{{$_GET["t"]}}',
                    myNumber: $('#verifiedNr007').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    res = res.replace(/\s/g, '');
                    if(res != 'no'){
                        var res3D = res.split('||');

                        $('#checkTabChngReqsAdminModalTitle').html($('#administratorWantsYouToTable').val()+  '' +res3D[1]+ '' +$('#send').val());
                        $('#checkTabChngReqsAdminModal').modal('toggle');

                        $('#checkTabChngReqsAdminModalReqId').val(res3D[0]);
                    }
                    // location.reload();
                },
                error: (error) => {console.log(error);}
            });
        }
    }

    function checkTabChngReqsAdminConfirm(){
        $.ajax({
            url: '{{ route("dash.adminReqClTableChangeConfirm") }}',
            method: 'post',
            data: {
                tabChngReqId: $('#checkTabChngReqsAdminModalReqId').val(),
                _token: '{{csrf_token()}}'
            },
            success: (res) => {
                res = res.replace(/\s/g, '');
                window.location = "/?Res="+$('#thisRestaurant').val()+"&t="+res;
            },
            error: (error) => {console.log(error);}
        });
    }
</script>
