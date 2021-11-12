@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12 no-padding-left">
               <h2 class="textlog">Earning Reports</h2>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-12">
               
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-12 text-right no-padding-right">
               <a href="/admin" class="btn btn-primary mr-auto">Return to dashboard</a>
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
         <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12 no-padding-left">
                  <div class="form-group">
                     <label>Start Date</label>
                     <input type="text" name="start" class="form-control start-date"  required placeholder="Start Date">
                  </div>
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-12 no-padding-right">
                  <div class="form-group">
                     <label>End Date</label>
                     <input type="text" name="end" class="form-control end-date"  required placeholder="End Date">
                  </div>
               </div>
         </div>
         <div class="row">
               <div id="earningsCalendar"></div>
         </div>
         <div class="row">
              <div class="col-12">
                  <h3>EARNINGS: $0.00</h3>
              </div>
         </div>
      </div>
   </section>
</div>
@endsection