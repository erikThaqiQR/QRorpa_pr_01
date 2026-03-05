<?php
    use App\User;
    use App\Restorant;
?>
<style>
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












<!-- The Modal -->
<div class="modal" id="addKuzhinier"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Shto nje kamarier</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        
        {{Form::open(['action' => 'UserAdminControler@storeKamarier', 'method' => 'post']) }}

            <div class="form-group">
                <label for="kat">User</label><br>
                <select name="user" class="form-control ">
                    <option value="0">Slect a user...</option>
                    <?php
                        if(!empty(User::all())){
                            foreach(User::where('role', '!=', 4)->get()->sortByDesc('created_at') as $usr){
                                echo '  <option style="color:black;" value="'.$usr->id.'">'.$usr->name.'</option> ';
                            }
                        }
                    ?>        
                </select>
            </div>
            <div class="form-group">
                <label for="kat">Restaurant</label><br>
                <select name="restaurant" class="form-control ">
                    <option value="0">Slect a restaurant...</option>
                    <?php
                        if(!empty(Restorant::all())){
                            foreach(Restorant::all()->sortByDesc('created_at') as $res){
                                echo '  <option style="color:black;" value="'.$res->id.'">'.$res->emri.'</option> ';
                            }
                        }
                    ?>        
                </select>
            </div>

            <div class="form-group">
                {{ Form::submit('Save', ['class' => 'form-control btn btn-outline-primary']) }}
            </div>

        {{Form::close() }}
      </div>

    </div>
  </div>
</div>

    <script>
        $('.select2').select2();
    </script>
       
   <style>
        .select2-container .select2-selection--single{
            height:34px !important;
            z-index:9999;
        }
        .select2-container--default .select2-selection--single{
                border: 1px solid #ccc !important; 
            border-radius: 0px !important; 
            z-index:9999;
        }
    </style>




















<section class="p-3">
<div class="text-center">
    <h3 class="color-qrorpa"><strong>Kamarier</strong></h3>
    <hr>
</div>
<div class="container">
    <div class="row">
        <div class="col-1">
            <a href="{{route('userAd.index')}}"> 
                <button class="btn btn-block btn-default noBorder"> Back </button>
            </a>
        </div>
        <div class="col-6">
            <button class="btn btn-block btn-outline-dark noBorder" data-toggle="modal" data-target="#addKuzhinier">
                <strong>+ Shto nje kamarier</strong>
            </button>
        </div>
        <div class="col-5">
            <!-- Actual search box -->
            <div class="form-group has-search">
                <span class="fa fa-search form-control-feedback"></span>
                <input type="text" id="searchKamarier" class="form-control noBorder" placeholder="Search Users">
            </div>
        </div>
    </div>
</div>

    <table id="userKamarier" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>email</th>
                <th>Role</th>
                <th>For</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach(User::where('role', '=', 4)->get()->sortByDesc('created_at') as $kuzh)
                <tr>
                    <td>{{$kuzh->id}}</td>
                    <td>{{$kuzh->name}}</td>
                    <td>{{$kuzh->email}}</td>
                    <td>{{$kuzh->role}}</td>
                    <td>{{Restorant::find($kuzh->sFor)->emri}}</td>
                    <td class="text-center">
                        <button onclick="deleteKam('{{$kuzh->id}}')" class="btn btn-block btn-outline-danger">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>email</th>
                <th>Role</th>
                <th>For</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
<script>
      $(document).ready(function() {
            // $('#userAdmin').DataTable();

            $("#searchKamarier").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#userKamarier tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });


        function deleteKam(tid){
            // alert(id);

            $.ajax({
                url: '{{route("userAd.destroyKuzhinier")}}',
                method: 'delete',
                data: {
                    id: tid,
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    $('#userKamarier').load('/userKuzhinier #userKamarier', function() {});
                },
                error: (error) => {
                    console.log(error);
                    alert('Oops! Something went wrong')
                }
            })
        }
</script>
</section>