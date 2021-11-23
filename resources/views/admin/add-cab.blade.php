@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Add New Cab</h2>
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
         <form action="/admin/cab/add" name="activities_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Name</label>
                     <input type="text" name="name" class="form-control" value="" required>
                  </div>
                  <div class="form-group">
                     <label>Short Description</label>
                     <textarea id="editor10" name="short_description" class="form-control" value=""></textarea>
                  </div>
                  <div class="form-group">
                     <label>Description</label>
                     <textarea id="editor11" name="description" class="form-control" value=""></textarea>
                  </div>
                  <!-- <div class="form-group">
                     <label>Currency Code</label>
                     <input type="text" name="currency" class="form-control" value="" required>
                  </div> -->
                  <div class="form-group">
                     <label>Price</label>
                     <input type="text" name="price" class="form-control" value="" required>
                  </div>
                  <div class="form-group">
                     <label>Main Image</label>
                     <input type="file" name="main_image" class="form-control" value="" >
                  </div>
                  <div class="form-group">
                     <label>Slider Images <span class="info">(You can select multiple images)</span></label>
                     <input type="file" name="images[]" class="form-control" value="" multiple>
                  </div>
                  <input type="submit" name="submit" class="btn btn-primary" value="Submit">
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection