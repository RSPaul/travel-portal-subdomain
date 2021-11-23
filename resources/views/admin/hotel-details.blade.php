@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Hotel Details</h2>
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
         <form action="{{route('admin_hotel_details')}}" name="activities_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Hotel Name</label>
                     <input type="text" name="name" class="form-control" value="{{$details->name}}" required placeholder="Hotel Name">
                     <input type="hidden" name="id" value="{{$id}}">
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Tag Line</label>
                     <input type="text" name="tag_line" class="form-control" value="{{$details->tag_line}}" required placeholder="Tag Line">
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Tag Line 2 </label>
                     <input type="text" name="tag_line2" class="form-control" value="{{$details->tag_line2}}" required placeholder="Tag Line">
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Tag Line 3 </label>
                     <input type="text" name="tag_line3" class="form-control" value="{{$details->tag_line3}}" required placeholder="Tag Line">
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Main Image</label>
                     <input type="file" name="main_image" class="form-control" value="" >
                     <input type="hidden" name="hidden_main_image" value="{{$details['main_image']}}">
                     <img src="/uploads/hotel/{{$details['main_image']}}" width="150px">
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Slider Images <span class="info">(You can select multiple images)</span></label>
                     <input type="file" name="slider_images[]" class="form-control" value="" multiple>
                     @if(isset($details['slider_images']) && !empty($details['slider_images']))
                        @foreach($details['slider_images'] as $key => $image)
                           <input type="hidden" name="slider_image_count" value="{{$key + 1}}">
                           <input type="hidden" name="hidden_images[]" value="{{$image}}">
                           <img src="/uploads/hotel/{{$image}}" width="150px">
                        @endforeach
                     @endif
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Latitude </label>
                     <input type="text" name="lat" class="form-control" value="{{$details->lat}}">
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Longitude </label>
                     <input type="text" name="lng" class="form-control" value="{{$details->lng}}">
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Hotel Description</label>
                     <textarea name="description"  id="editor1" class="form-control editor">{{$details->description}}</textarea>
                  </div>     
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Hotel Description 2</label>
                     <textarea name="description2" id="editor2" class="form-control editor">{{$details->description2}}</textarea>
                  </div> 
                                         
               </div>
               <div class="col-md-12">                  
                  <div class="form-group">
                     <label>Hotel Description 3</label>
                     <textarea name="description3" id="editor3" class="form-control editor">{{$details->description3}}</textarea>
                  </div>  
               </div>
               <div class="form-group">
                  <input type="submit" name="submit" class="btn btn-primary" value="Submit"> 
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection