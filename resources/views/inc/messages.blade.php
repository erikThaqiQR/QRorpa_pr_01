@if(count($errors) > 0)
    @foreach($errors ->all() as $error)
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <div class="alert alert-danger">
                        {{$error}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

@if(session('success'))
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <div class="alert alert-success">
                        {{session('success')}}
                    </div>
                </div>
            </div>
        </div>
@endif


@if(session('error'))
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <div class="alert alert-danger">
                        {{session('error')}}
                    </div>
                </div>
            </div>
        </div>
@endif