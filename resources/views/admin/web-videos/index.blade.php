@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
               <h2 class="textlog">Webinars Videos</h2>
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
         <div class="form-group d-flex">  
            <a href="/admin/web-video/add" class="btn btn-primary mr-auto" >Add Video</a>
         </div>
          <div class="card shadow mb-4">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-bordered dataTable addaddress" id="dataTable" width="100%" cellspacing="0">
                     <thead>
                        <tr>
                           <th width="10%">Title</th>
                           <th width="20%">Description</th>
                           <th width="10%">Media Link</th>
                           <th width="10%">Status</th>
                           <th width="10%">Actions</th>
                        </tr>
                     </thead>                     
                     <tbody>
                        @foreach($videos as $video)
                           <tr>
                              <td>{{ $video->title }}</td>
                              <td>{{ $video->description }}</td>
                              <td><a href="{{ $video->media_link }}" target="_blank">{{ $video->media_link }}</a></td>
                              <td>{{ $video->status }}</td>
                              <td>
                                 <a href="/admin/web-video/edit/{{$video->id}}" class="btn btn-primary">Edit</a>
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection