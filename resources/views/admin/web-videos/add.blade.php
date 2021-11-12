@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Add Webinar/Video</h2>
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
         <form action="/admin/web-video/add" name="agent_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Title</label>
                     <input type="text" name="title" class="form-control" value="" required>
                  </div>
                  <div class="form-group">
                     <label>Description</label>
                     <textarea  name="description" class="form-control" value="" required></textarea>
                  </div>
                  <div class="form-group">
                     <label>Video Link</label>
                     <input type="url" name="media_link" id="media_link" class="form-control" value="" required>
                  </div>
                  <div class="form-group">
                     <iframe src="" width="250" height="250" class="media_link_preview" style="display: none;"></iframe>
                  </div>
                  <input type="hidden" name="status" value="Active" />
                  <input type="submit" name="submit" class="btn btn-primary" value="Submit">
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection