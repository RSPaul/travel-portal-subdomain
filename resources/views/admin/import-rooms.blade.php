@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Import Hotel Rooms</h2>
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
         <form action="/admin/import/rooms" name="activities_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Check In</label>
                     <input type="text" name="checkin" placeholder="Check In" class="form-control datepicker" value="" required>
                  </div>
                  <div class="form-group">
                     <label>Check Out</label>
                     <input type="text" name="checkout" placeholder="Check Out" class="form-control datepicker" value="" required>
                  </div>
                  <div class="form-group">
                     <label>Adults</label>
                     <select name="adults" class="form-control" required="">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Childs</label>
                     <select name="childs" class="form-control" >
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>No. of Rooms</label>
                     <select name="rooms" class="form-control" required="">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                     </select>
                  </div>
                  <input type="submit" name="submit" class="btn btn-primary" value="Submit">
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection