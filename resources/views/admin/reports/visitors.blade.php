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
                     <!-- img src="{{asset('images/reports/ReportsHome.png')}}" class="visitors-reports-img" align="Visitors Reports Home" -->
                     From: <input type="date" name="from_date" max="" id="FromDate" />
                     To: <input type="date" name="to_date" max="" id="ToDate" />
                     <figure class="highcharts-figure">
                        <div id="container"></div>
                     </figure>
                  </div>
                  <div class="tab-pane fade" id="realtime" role="tabpanel" aria-labelledby="v-pills-realtime-tab">
                     <div class="row">
                        <ul class="nav nav-tabs custom-tabs">
                            <li class="active"><a data-toggle="tab" href="#rt-overview" >Overview</a></li>
                            <li><a data-toggle="tab" href="#rt-locations">Locations</a></li>
                            <li><a data-toggle="tab" href="#rt-traffic">Traffic&nbsp;Sources</a></li>
                            <li><a data-toggle="tab" href="#rt-content">Content</a></li>
                            <li><a data-toggle="tab" href="#rt-events">Events</a></li>
                            <li><a data-toggle="tab" href="#rt-conversions">Conversions</a></li>
                        </ul>
                    </div>
                    <div class="tab-content custom">
                      <div id="rt-overview" class="tab-view active">
                        <div class="row">
                        <div class="col-md-3">
                          <p><h3>Right Now</h3></p>
                          <p><h1 id="activeuser">0</h1></p>
                          <p>activer users on site</p>
                        </div>
                        <div class="col-md-9">
                            <h4>Page Views</h4>
                            <figure class="highcharts-figure">
                                <div id="rt-container"></div>
                            </figure>
                        </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                            <h4>Top Referrals:</h4>
                            <table class="table table-bordered " id="top-ref">
                                <thead>
                                  <tr id="">
                                    <th>Source</th>
                                    <th>Active Users</th>
                                  </tr>
                                </thead> 
                                <tbody class="active-referral-tbody-ov">
                                </tbody>
                            </table>
                            </div>
                            <div class="col-md-8">
                            <h4>Top Active Pages</h4>
                            <table id="pathList">
                              <tr id="pathData">
                                <th>Path</th>
                                <th>Title</th>
                                <th>Page View</th>
                              </tr>
                            </table>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Top Social Traffic:</h4>
                                <table class="table table-bordered ">
                                    <thead>
                                        <tr id="">
                                        <th>Source</th>
                                        <th>Active Users</th>
                                      </tr>
                                    </thead>
                                    <tbody class="active-traffic-tbody-ov">
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                 <h4>Top Keywords:</h4>
                                <table  class="table table-bordered " id="social-traffic">
                                    <thead>
                                      <tr id="pathDataKeyWord">
                                        <th>Keyword</th>
                                        <th>Active Users</th>
                                      </tr>
                                    </thead>
                                    <tbody class="active-keywords-tbody-ov">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-12">
                                <h4>Top Locations:</h4>
                                <div class="row"><div id="containerMap"></div></div>
                            </div>
                        </div>
                      </div>
                      <div id="rt-locations" class="tab-view">
                          <div class="row">
                              <div id="containerMap2"></div>
                          </div>
                          <div class="row">
                            <table class="table table-bordered active-users-table">
                                <thead>
                                  <tr >
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>Active Users</th>
                                  </tr>
                                </thead>
                                  <tbody class="active-users-tbody">
                                      
                                  </tbody>
                            </table>
                          <div>
                      </div>
                    </div>
                    
                  </div>
                      <div id="rt-traffic" class="tab-view">
                              <div class="row">
                                <table class="table table-bordered active-users-table">
                                    <thead>
                                      <tr >
                                        <th>Source</th>
                                        <th>Campaign</th>
                                        <th>Page Views</th>
                                        <th>Active Users</th>
                                      </tr>
                                    </thead>
                                      <tbody class="active-traffic-tbody">
                                          
                                      </tbody>
                                </table>
                              <div>
                          </div>
                        </div>
                        
                      </div>
                      <div id="rt-content" class="tab-view">
                            <div class="row">
                                <table class="table table-bordered active-users-table">
                                    <thead>
                                      <tr >
                                        <th>Page</th>
                                        <th>Page Title</th>
                                        <th>Page Views</th>
                                      </tr>
                                    </thead>
                                      <tbody class="active-content-tbody">
                                          
                                      </tbody>
                                </table>
                            <div>
                        </div>
                        </div>
                        
                      </div>
                      <div id="rt-events" class="tab-view">
                              <div class="row">
                                <table class="table table-bordered active-users-table">
                                    <thead>
                                      <tr >
                                        <th>Event Category</th>
                                        <th>Event Action</th>
                                        <th>Active Users</th>
                                      </tr>
                                    </thead>
                                      <tbody class="active-events-tbody">
                                          
                                      </tbody>
                                </table>
                              <div>
                          </div>
                        </div>
                        
                      </div>
                      <div id="rt-conversions" class="tab-view">
                              <div class="row">
                                <table class="table table-bordered active-users-table">
                                    <thead>
                                      <tr >
                                        <th>Goal</th>
                                        <th>Active Users</th>
                                      </tr>
                                    </thead>
                                      <tbody class="active-conversions-tbody">
                                          
                                      </tbody>
                                </table>
                              <div>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  <div class="tab-pane fade" id="audience" role="tabpanel" aria-labelledby="v-pills-traffic-audience-tab">
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