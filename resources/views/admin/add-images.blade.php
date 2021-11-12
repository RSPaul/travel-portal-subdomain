@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Update Room Images</h2>
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
         <form action="/admin/hotel-room-images/add" name="activities_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group">
                     <label>Selected Hotel</label>
                     <select class="form-control" required name="sub_domain">
                        @foreach($hotels as $hotel)
                           <option value="{{$hotel->hotel_code}}">{{$hotel->hotel_name}}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Enter Room Name</label>
                     <input type="text" name="name" class="form-control" value="" required placeholder="Enter Room Name">
                  </div>
                  <div class="form-group">
                     <label>Room Images <span class="info">(You can select multiple images)</span></label>
                     <input type="file" name="room_images[]" class="form-control" value="" multiple>
                  </div>
                  <input type="submit" name="submit" class="btn btn-primary" value="Submit">
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection