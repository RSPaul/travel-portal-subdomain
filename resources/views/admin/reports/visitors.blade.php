@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
               <h2 class="textlog">Visitors Reports</h2>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-12">
               
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-12 text-right">
               <a href="/admin" class="btn btn-primary mr-auto" >Return to dashboard</a>
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
              <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                  <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
                  <a class="nav-link" id="v-pills-realtime-tab" data-toggle="pill" href="#realtime" role="tab" aria-controls="v-pills-realtime" aria-selected="false">Realtime</a>
                  <a class="nav-link" id="v-pills-audience-tab" data-toggle="pill" href="#audience" role="tab" aria-controls="v-pills-traffic-behaviour" aria-selected="false">Audience</a>
                  <a class="nav-link" id="v-pills-traffic-acquisition-tab" data-toggle="pill" href="#traffic-acquisition" role="tab" aria-controls="v-pills-traffic-behaviour" aria-selected="false">Traffic Acquisition</a>
                  <a class="nav-link" id="v-pills-traffic-behaviour-tab" data-toggle="pill" href="#traffic-behaviour" role="tab" aria-controls="v-pills-traffic-behaviour" aria-selected="false">Traffic Behaviour</a>
                </div>
              </div>
              <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                     <img src="{{asset('images/reports/ReportsHome.png')}}" class="visitors-reports-img" align="Visitors Reports Home">
                  </div>
                  <div class="tab-pane fade" id="realtime" role="tabpanel" aria-labelledby="v-pills-realtime-tab">
                     <img src="{{asset('images/reports/RealTimeTab.png')}}" class="visitors-reports-img" align="Real Time Reports">
                  </div>
                  <div class="tab-pane fade" id="audience" role="tabpanel" aria-labelledby="v-pills-traffic-acquisition-tab">
                     <img src="{{asset('images/reports/Audience.png')}}" class="visitors-reports-img" align="Audience Reports">
                  </div>
                  <div class="tab-pane fade" id="traffic-acquisition" role="tabpanel" aria-labelledby="v-pills-traffic-acquisition-tab">
                     <img src="{{asset('images/reports/TrafficAcquisition.png')}}" class="visitors-reports-img" align="Traffic Acquisition Reports">
                  </div>
                  <div class="tab-pane fade" id="traffic-behaviour" role="tabpanel" aria-labelledby="v-pills-traffic-behaviour-tab">
                     <img src="{{asset('images/reports/TrafficBehaviour.png')}}" class="visitors-reports-img" align="Traffic Behaviour Reports">
                  </div>
                </div>
              </div>
            </div>
      </div>
   </section>
</div>
@endsection