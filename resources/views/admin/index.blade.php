@extends('layouts.app-admin')

@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
               <h2 class="textlog text-center">Welcome To Your Reports Panel</h2>
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

            <!-- calendar -->
            
         </div>
         <div class="row">
              <div class="col-2">
              </div>
              <div class="col-6">
                <img src="{{asset('images/WelcomeCharacter.png')}}" class="welcome-character-img" align="Welcome Character">
              </div>
              <div class="col-4">
                <a class="btn btn-primary btn-lg reports-btn" href="/admin/reports/visitors">Visitors Reports</a>
                <a class="btn btn-success btn-lg reports-btn" href="/admin/reports/sales">Sales Reports</a>
                <a class="btn btn-warning btn-lg reports-btn" href="/admin/reports/earnings">Earnings</a>
              </div>
          </div>
         </div>
      </div>
   </section>
</div>
@endsection