@extends('layouts.app-admin')
@section('content')

<div id="page-content-wrapper">
    <section class="maindiv">
        <div class="container">
            <div class="row headingtop">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <h4 class="textlog">Edit City</h4>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                    <a class="btn btn-primary" href="{{ route('experiences.index') }}"> Back</a>
                </div>
            </div>
            @if ($errors->any())
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('getaways.update',$getaway->id) }}" class="addcontainerpart" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" value="{{ $getaway->title }}" class="form-control" placeholder="Title" required>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category"  class="form-control" value="{{ $getaway->category }}" required >
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Country</label>
                        <select class="form-control" name="country">
                            <option>Select Country</option>
                            @foreach ($countries as $key => $value)
                            <option value="{{ $value['CountryCode'] }}" {{ ( $value['CountryCode'] == $getaway->country) ? 'selected' : '' }}> 
                                {{ $value['Country'] }} 
                            </option>
                            @endforeach    
                        </select>
                        @if ($errors->has('country'))
                        <span class="text-danger">{{ $errors->first('country') }}</span>
                        @endif
                    </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" name="photo"   onchange="loadPreview(this)" class="form-control"  >
                            @if ($errors->has('photo'))
                            <span class="text-danger">{{ $errors->first('photo') }}</span>
                            @endif
                            <img id="prevImg" style="width:200px;" src="{{ $getaway->photo }}" class="img-responsive img-thumbnail mt-1" />
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </section>
</div>
<script>
    function loadPreview(input) {

        var data = input.files; //this file data
        console.log(data);
        $.each(data, function (index, file) {

            if (/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)) {
                var fRead = new FileReader();
                fRead.onload = (function (file) {
                    return function (e) {
                        $('#prevImg').attr('src', e.target.result); //create image thumb element
                    };
                })(file);
                fRead.readAsDataURL(file);
            }
        });

    }
</script>
@endsection