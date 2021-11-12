@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Discover Cities - {{$country['Country']}}</h2>
            </div>
         </div>
         <div class="row">
            @if(Session::has('error'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger text-center') }}">
               {{ Session::get('error') }}
            </p>
            @endif
            @if(Session::has('success'))
            <p class="alert {{ Session::get('alert-class', 'alert-success text-center') }}">
               {{ Session::get('success') }}
            </p>
            @endif
         </div>
          <div class="card shadow mb-4">
            <div class="card-body">
               <form method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                     @foreach($cities as $city)
                     <div class="form-group col-md-6 city-details">
                        <input type="checkbox" name="city[{{$city['id']}}]" @if($city['isFeatured'] == '1') checked="checked" @endif>
                        <label>{{$city['CityName']}}</label>
                        <br>
                        <input type="file" name="image[{{$city['id']}}]" >
                        <br>
                        <img src="/uploads/featured_cities/{{$city['image']}}" onerror="this.src= ''; this.src='https://via.placeholder.com/150?text=Image%20Not%20Uploaded'">
                     </div>
                     @endforeach
                     <input type="hidden" name="country_code" value="{{$country['CountryCode']}}">
                  </div>
                     <input type="submit" name="submit" value="Submit" class="btn btn-primary">
               </form>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection