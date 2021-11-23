@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Meta Tags</h2>
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
         <form action="/admin/meta" name="activities_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Title</label>
                     <input type="text" name="title" class="form-control" value="{{$tags['title']}}" required>
                  </div>
                  <div class="form-group">
                     <label>Description</label>
                     <textarea name="description" class="form-control">{{$tags['description']}}</textarea>
                  </div>
                  <div class="form-group">
                     <label>Keywords</label>
                     <textarea name="keywords" class="form-control">{{$tags['keywords']}}</textarea>
                  </div>
                  
                  <div class="form-group">
                     <label>Author</label>
                     <input type="text" name="author" class="form-control" value="{{$tags['author']}}" required>
                  </div>

                  <!-- <div class="form-group">
                     <label>Viewport</label>
                     <input type="text" name="viewport" class="form-control" value="{{$tags['viewport']}}" required>
                  </div> -->

                  <div class="form-group">
                     <label>Robots</label>
                     <input type="text" name="robots" class="form-control" value="{{$tags['robots']}}" required>
                  </div>

                  <div class="form-group">
                     <label>View Id</label>
                     <input type="text" name="view_id" class="form-control" value="{{$tags['view_id']}}" required>
                  </div>
                  <div class="form-group">
                     <label>Google Site Verification</label>
                     <input type="text" name="google_site_verification" class="form-control" value="{{$tags['google_site_verification']}}" required>
                  </div>

                  <div class="form-group">
                     <label>Google Analytics Code</label>
                     <textarea name="google_analytics_code" class="form-control">{{$tags['google_analytics_code']}}</textarea>
                  </div>

                  <input type="submit" name="submit" class="btn btn-primary" value="Submit">
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection