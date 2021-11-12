@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
               <h2 class="textlog">Activities</h2>
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
            <a href="/admin/activity/add" class="btn btn-primary mr-auto" >Add Activities</a>
         </div>
          <div class="card shadow mb-4">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-bordered dataTable addaddress" id="dataTable" width="100%" cellspacing="0">
                     <thead>
                        <tr>
                           <th width="25%">Subdomain</th>
                           <th width="25%">Name</th>
                           <th width="25%">Price</th>
                           <th width="25%">Actions</th>
                        </tr>
                     </thead>                     
                     <tbody>
                        @foreach($activities as $activity)
                           <tr>
                              <td>{{$activity->sub_domain}}</td>
                              <td>{{$activity->name}}</td>
                              <td>{{$activity->price}}</td>
                              <td><a href="/admin/activity/edit/{{$activity->id}}" class="btn btn-info btn-sm">Edit </a> <a href="javascript:void(0);" data-id="{{$activity->id}}" class="btn btn-danger btn-sm delete-btn">Delete </a>
                                 <form action="/admin/activity/delete/{{$activity->id}}" method="POST" id="item_delete_{{$activity->id}}">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{$activity->id}}">
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