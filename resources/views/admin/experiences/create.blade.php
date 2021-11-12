@extends('layouts.app-admin')
@section('content')

<div id="page-content-wrapper">
    <section class="maindiv">
        <div class="container">
            <div class="row headingtop">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <h4 class="textlog">Add New</h4>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                    <a class="btn btn-primary" href="{{ route('destinations.index') }}"> Back</a>
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
        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
                Session::forget('success');
            @endphp
        </div>
        @endif

            <form action="{{ route('experiences.store')}}" name="location_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" value=""  required >
                            @if ($errors->has('city'))
                                <span class="text-danger">{{ $errors->first('city') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Country</label>
                            <select class="form-control" name="country">
                                <option>Select Country</option>
                                @foreach ($countries as $key => $value)
                                  <option value="{{ $value['CountryCode'] }}" {{ ( $value['CountryCode'] == 'IN') ? 'selected' : '' }}> 
                                      {{ $value['Country'] }} 
                                  </option>
                                @endforeach    
                          </select>
                            @if ($errors->has('country'))
                                <span class="text-danger">{{ $errors->first('country') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control" value="" required >
                            @if ($errors->has('price'))
                                <span class="text-danger">{{ $errors->first('price') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" name="photo" onchange="loadPreview(this)"  class="form-control" value="" required>
                            @if ($errors->has('photo'))
                                <span class="text-danger">{{ $errors->first('photo') }}</span>
                            @endif
                            <img id="prevImg" style="width:200px;" src="/uploads/photos/no-image.jpg" class="img-responsive img-thumbnail mt-1" />
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check fa-lg"></i> Save
                        </button>
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
