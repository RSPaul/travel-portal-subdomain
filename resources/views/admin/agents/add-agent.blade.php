@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Add New Agent</h2>
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
         <form action="/admin/agent/add" name="agent_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Name</label>
                     <input type="text" name="name" class="form-control" value="" required>
                  </div>
                  <div class="form-group">
                     <label>Email</label>
                     <input type="email" name="email" class="form-control" value="" required>
                  </div>
                  <div class="form-group">
                     <label>Commission</label>
                     <input type="text" name="commission" class="form-control" value="" required>
                  </div>
                  <input type="hidden" name="status" value="1" />
                  <input type="hidden" name="role" value="user" />
                  <input type="hidden" name="password" value="Admin123#" />
                  <input type="hidden" name="referal_code" value="<?php echo $code;?>" />
                  <input type="submit" name="submit" class="btn btn-primary" value="Submit">
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection