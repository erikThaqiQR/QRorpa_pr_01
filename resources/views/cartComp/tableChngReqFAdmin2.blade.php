<div id="checkTabChngReqsAdminModal" class="modal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:4%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="text-center" style="font-weight: bold;" id="checkTabChngReqsAdminModalTitle"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-dark" onclick="checkTabChngReqsAdminConfirm()" data-dismiss="modal">Ok weiter</button>
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
                    myRes: $('#theRestaurant').val(),
                    myTable: $('#theTable').val(),
                    myNumber: $('#verifiedNr007').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    res = $.trim(res);
                    if(res != 'no'){
                        var res3D = res.split('||');
                        res_1 = String(res3D[1]);
                        res_0 = String(res3D[0]);
                        res_1 = res_1.replace(/\s/g, '');
                        res_0 = res_0.replace(/\s/g, '');

                        $('#checkTabChngReqsAdminModalTitle').html('Der Administrator möchte Sie zu Tisch '+res_1+' schicken');
                        $('#checkTabChngReqsAdminModal').modal('toggle');

                        $('#checkTabChngReqsAdminModalReqId').val(res_0);
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
                window.location = "/?Res="+$('#theRestaurant').val()+"&t="+res;
            },
            error: (error) => {console.log(error);}
        });
    }
</script>
