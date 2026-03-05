<?php
    use App\User;
    use App\Barbershop;
    use App\Restorant;
?>

<style>
    .c-pointer:hover{
        cursor:pointer;
    }

    .userDataLine{
        border-bottom:1px solid lightgray;
        padding-bottom: 10px;
        padding-top: 5px;
    }
    .resAdminData{
        font-size:16px;
        font-weight:bold;
    }
    .noBorder:active {
        outline: none;
    }
    .noBorder:focus {
        outline: none;
        box-shadow: none;
    }
    a:hover{
        text-decoration:none;
    }
</style>








<div class="text-center">
    <h3 class="color-qrorpa"><strong>Users</strong></h3>
</div>
    <div id="userAdmin" class="table table-striped table-bordered pl-5 pr-4 pt-3 " style="width:100%">
        <div class="d-flex justify-content-between">
            <h3 style="width:5%"><strong>ID</strong></h3>
            <h3 style="width:30%"><strong>Name</strong></h3>
            <h3 style="width:35%"><strong>Email</strong></h3>
            <h3 style="width:15%" class="text-center"><strong>Role</strong></h3>
            <h3 style="width:15%" class="text-center"><strong>Admin For...</strong></h3>
        </div>
        <hr style="width:100%">
        <div class="d-flex justify-content-between flex-wrap">
            @foreach(User::all()->sortByDesc('created_at') as $user)
                <p style="width:5%;" class="resAdminData userDataLine">{{$user->id}}</p>
                <p style="width:30%;" class="resAdminData userDataLine">{{$user->name}}</p>
                <p style="width:35%;" class="resAdminData userDataLine">{{$user->email}}</p>
             
                    @if($user->role == 1)
                        <p style="width:15%;" onclick="changeRole('{{$user->id}}')" class="text-center c-pointer userDataLine">
                            <strong style="font-size:19px;"> User </strong>
                        </p>
                    @elseif($user->role == 5)
                        <p style="width:15%; " id="baseAdmin{{$user->id}}" onclick="changeRole('{{$user->id}}')" class="text-center c-pointer userDataLine">
                            <strong style="font-size:19px;">Restorant Admin </strong>
                        </p>
                    @elseif($user->role == 15)
                        <p style="width:15%; " id="baseAdmin{{$user->id}}" onclick="changeRole('{{$user->id}}')" class="text-center c-pointer userDataLine">
                            <strong style="font-size:19px;">Barbershop Admin </strong>
                        </p>
                    @elseif($user->role == 9)
                        <p style="width:15%" id="baseSuperadmin{{$user->id}}" onclick="changeRole('{{$user->id}}')" class="text-center c-pointer userDataLine">
                            <strong style="font-size:19px;"> Superadmin </strong>
                        </p>
                    @else
                        <p style="width:15%;" class="userDataLine"></p>
                    @endif
            

                    @if($user->role == 5)
                        @if($user->sFor != 0)
                            <div style="width:15%;" class="form-group userDataLine">
                                <select name="toCat" class="form-control" onchange="setResForUser(this.value, '{{$user->id}}')">
                                    @foreach(Restorant::all()->sortByDesc('created_at') as $res)
                                        @if($user->sFor == $res->id)
                                            <option value="{{$res->id}}" selected>{{$res->emri}}</option>
                                        @else
                                            <option value="{{$res->id}}">{{$res->emri}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div style="width:15%;" class="form-group userDataLine">
                                <select name="toCat" class="form-control" onchange="setResForUser(this.value, '{{$user->id}}')">
                                    <option value="0">Select...</option>
                                    @foreach(Restorant::all()->sortByDesc('created_at') as $res)
                                        <option value="{{$res->id}}">{{$res->emri}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @elseif($user->role == 15)
                        @if($user->sFor != 0)
                            <div style="width:15%;" class="form-group userDataLine">
                                <select name="toCat" class="form-control" onchange="setBarForUser(this.value, '{{$user->id}}')">
                                    @foreach(Barbershop::all()->sortByDesc('created_at') as $bar)
                                        @if($user->sFor == $bar->id)
                                            <option value="{{$bar->id}}" selected>{{$bar->emri}}</option>
                                        @else
                                            <option value="{{$bar->id}}">{{$bar->emri}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div style="width:15%;" class="form-group userDataLine">
                                <select name="toCat" class="form-control" onchange="setBarForUser(this.value, '{{$user->id}}')">
                                    <option value="0">Select...</option>
                                    @foreach(Barbershop::all()->sortByDesc('created_at') as $bar)
                                        <option value="{{$bar->id}}">{{$bar->emri}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @else
                        <p class="text-center userDataLine" style="color:red; width:15%"><strong>Not allowed</strong></p>
                    @endif
                       
                
            
            @endforeach    
        </div>
    </div>

    <br><br>

    <script>
        $(document).ready(function() {
            // $('#userAdmin').DataTable();
            $("#searchAdmin").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#userAdmin tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });




        function changeRole(uId){
            $.ajax({
                url: '{{route("barbershopsUser.chRole")}}',
                method:'post',
                data: {id: uId,  _token: '{{csrf_token()}}'},
                success: (response) => {
                    $("#userAdmin").load(location.href+" #userAdmin>*","");
                },
                error: (error) => {
                    alert(error.status);
                }
            });
        }


        function setResForUser(resId, userId){
            $.ajax({
                url: '{{route("barbershopsUser.toRes")}}',
                method:'post',
                data: {res: resId, user:userId, _token: '{{csrf_token()}}'},
                success: (response) => {
                    $("#userAdmin").load(location.href+" #userAdmin>*","");
                },
                error: (error) => {
                    alert(error.status);
                }
            });
        }
        function setBarForUser(barId, userId){
            $.ajax({
                url: '{{route("barbershopsUser.toBar")}}',
                method:'post',
                data: {bar: barId, user:userId, _token: '{{csrf_token()}}'},
                success: (response) => {
                    $("#userAdmin").load(location.href+" #userAdmin>*","");
                },
                error: (error) => {
                    alert(error.status);
                }
            });
        }
    
    </script>