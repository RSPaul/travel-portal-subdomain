@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
               <h2 class="textlog">Cruises</h2>
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
            <a href="/admin/cruise/add" class="btn btn-primary mr-auto" >Add Cruise</a>
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
                        @foreach($cruises as $cruise)
                           <tr>
                              <td>{{$cruise->sub_domain}}</td>
                              <td>{{$cruise->name}}</td>
                              <td>{{$cruise->price}}</td>
                              <td><a href="/admin/cruise/edit/{{$cruise['id']}}" class="btn btn-info btn-sm">Edit </a> <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="{{$cruise->id}}">Delete </a>
                                 <form action="/admin/cruise/delete/{{$cruise->id}}" method="POST" id="item_delete_{{$cruise->id}}">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{$cruise->id}}">
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