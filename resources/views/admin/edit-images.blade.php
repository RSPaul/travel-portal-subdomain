@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
               <h2 class="textlog">Update Room Images - {{ $hotel_name['hotel_name'] }}</h2>
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
         <form action="/admin/hotel-room-images/edit/{{$image->id}}" name="activities_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group">
                     <label>Selected Room</label>
                     <input type="text" name="name" class="form-control" value="{{$image['name']}}" readonly required>
                  </div>
                  <div class="form-group">
                     <label>Select Parent Room</label>
                     <select class="form-control" name="parent_room_id">
                        <option value="">Select</option>
                        @foreach($rooms as $room)
                           @if($room->id != $image['id'])
                              <option value="{{ $room->id }}" @if($room->id == $image['parent_room_id']) selected="selected" @endif>{{ $room->name }}</option>
                           @endif   
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Room Images <span class="info">(You can select multiple images)</span></label>
                     <input type="file" name="room_images[]" class="form-control" value="" multiple>
                     <br>
                     @if(isset($image['images']) && !empty($image['images']))
                        @foreach($image['images'] as $key => $v)
                           <input type="hidden" name="room_image_count" value="{{$key + 1}}">
                           <input type="hidden" name="hidden_images[]" value="{{$v}}">
                           @if (strpos($v, 'http') !== false)
                              <img src="{{ $v }}" width="150px" height="100px">
                           @else
                              <img src="/uploads/rooms/{{ $image['sub_domain'] }}/{{$v}}" width="150px" height="100px">
                           @endif
                        @endforeach
                     @endif
                  </div>
                  <input type="submit" name="submit" class="btn btn-primary" value="Submit">
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection