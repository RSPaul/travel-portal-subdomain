@extends('layouts.app-admin')
@section('content')   
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Download Hotels Static Data</h2>
            </div>
         </div>
         <div class="row">
            @if(Session::has('error'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger text-center') }}">
               <b>{{ Session::get('error') }}</b>
            </p>
            @endif
            @if(Session::has('success'))
            <p class="alert {{ Session::get('alert-class', 'alert-success text-center') }}">
               <b>{{ Session::get('success') }}</b>
            </p>
            @endif

            @if($updated_hotels != 0)
               <p class="alert alert-success text-center">
               <b>{{ $updated_hotels }} hotels are updated, {{ $pending_hotels }} are still pending.</b>
               </p>
            @endif   
         </div>
         <form action="/admin/import-static-data" name="activities_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               		<div class="form-group">
               		<input id="someinput" class="form-control" placeholder="Type city name here" autocomplete="off">
                  </div>
                    <div class="form-group">
                     <label>Choose City To Get Static Data</label>
                     <select class="form-control" name="CityId" id="CityId" required>
                     	<option value="">Select</option>
                     	@foreach($cities as $city)
                     		<option value="{{ $city->CityId }}">{{ $city->CityName }} ( {{ $city->Country }} )</option>
                     	@endforeach
                     </select>
                  	</div>
                     <div class="form-group">
                        <label>Enter Hotel Name</label>
                        <input type="text" name="hotel_name" id="hotel_name_static" placeholder="Enter Hotel Name To Search" class="form-control">
                        <input id="prefredHotel" type="hidden" name="prefredHotel" value="">
                     </div>
                  	<input type="submit" name="submit" class="btn btn-primary" value="Submit">
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection