@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
               <h2 class="textlog">Room Images</h2>
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
            <a href="/admin/hotel-room-images/add/{{ $hotel_code }}" target="_blank" class="btn btn-primary mr-auto" >Add Images</a>
         </div>
          <div class="card shadow mb-4">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-bordered dataTable addaddress" id="dataTable" width="100%" cellspacing="0">
                     <thead>
                        <tr>
                           <th width="30%">Hotel Name</th>
                           <th width="50%">Room Type</th>
                           <th width="20%">Actions</th>
                        </tr>
                     </thead>                     
                     <tbody>
                        @foreach($images as $image)
                           <tr>
                              <td>{{$image->hotel_name}}</td>
                              <td>{{$image->name}}</td>
                              <td><a href="/admin/hotel-room-images/edit/{{$image->id}}" class="btn btn-info btn-sm">Edit </a> <a href="javascript:void(0);" data-id="{{$image->id}}" class="btn btn-danger btn-sm delete-btn">Delete </a>
                                 <form action="/admin/hotel-room-images/edit/{{$image->id}}" method="POST" id="item_delete_{{$image->id}}">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{$image->id}}">
                                 </form>
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