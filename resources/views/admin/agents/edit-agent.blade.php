@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Approve Agent</h2>
            </div>
         </div>
         <div class="row">
            @if(Session::has('success'))
           <p class="alert {{ Session::get('alert-class', 'alert-success text-center') }}">
              {{ Session::get('success') }}
           </p>
           @endif
           @if(Session::has('error'))
           <p class="alert {{ Session::get('alert-class', 'alert-danger text-center') }}">
              {{ Session::get('error') }}
           </p>
           @endif
         </div>
         <form action="/admin/agent/edit/{{$users->id}}" name="activities_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Name</label>
                     <input type="text" name="name" class="form-control" value="{{$users->name}}" readonly>
                  </div>
                  <div class="form-group">
                     <label>Email</label>
                     <input type="email" name="email" class="form-control" value="{{$users->email}}" readonly>
                  </div>
                   <div class="form-group">
                     <label>Refferal Code</label>
                     <input type="text" name="referal_code" class="form-control" value="{{$users->referal_code}}" readonly>
                  </div>
                  <div class="form-group">
                     <label>Commission</label>
                     <input type="text" name="commission" class="form-control" value="{{$users->commission}}" required>
                  </div>
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Id Document</label><br>
                     <img src="/uploads/id_documents/{{$users->id_proof}}" width="100" onerror="this.onerror=null;this.src='https://via.placeholder.com/100?text=Image%20Not%20Available';">
                  </div>
                  <div class="form-group">
                     <label>Profile Picture</label><br>
                     <img src="/uploads/profiles/{{$users->picture}}" width="100" onerror="this.onerror=null;this.src='https://via.placeholder.com/100?text=Image%20Not%20Available';">
                  </div>
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <!-- <input type="submit" name="submit" class="btn btn-primary" value="Approve User"> -->
                  @if($users->status == '1')
                     <input type="hidden" name="status" value="0">
                     <button type="submit" class="btn btn-danger">
                         <i class="fa fa-check fa-lg"></i> Deactivate Account
                     </button>
                  @else
                     <input type="hidden" name="status" value="1">
                     <button type="submit" class="btn btn-success">
                         <i class="fa fa-check fa-lg"></i> Activate Account
                     </button>
                  @endif
                  
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection