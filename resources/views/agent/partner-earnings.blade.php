@extends('layouts.app-agent-partner')
@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/agent/{{$partnerName}}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active">Earnings
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Earnings</h4>
                                </div>
                                <div class="card-body">
                                <ul class="nav nav-tabs" role="tablist">
                                	<li class="nav-item">
                                        <a href="#activities" id="activities-tab" data-toggle="tab" class="nav-link" aria-controls="profile" role="tab" aria-selected="false"><i data-feather="eye-off"></i> Activities</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="cabs-tab" data-toggle="tab" href="#cabs" aria-controls="about" role="tab" aria-selected="false"><i data-feather="user"></i> Cabs</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    @if($type == 'activity')
                                    <div class="tab-pane active" id="activities" aria-labelledby="activities-tab" role="tabpanel">
                                    @else
                                    <div class="tab-pane" id="activities" aria-labelledby="activities-tab" role="tabpanel">
                                    @endif
                                    	<!-- Earnings Card -->
                                    	<div class="row">
				                            <div class="col-lg-6 col-md-6 col-12">
				                                <div class="card earnings-card">
				                                    <div class="card-body">
				                                        <div class="row">
				                                            <div class="col-6">
				                                                <h4 class="card-title mb-1">Activity Bookings</h4>
				                                                
				                                                <p class="card-text text-muted font-small-2">
				                                                    These are activity bookings
				                                                </p>
				                                            </div>
				                                        </div>
				                                    </div>
				                                </div>
				                            </div>

				                            <div class="col-lg-6 col-md-6 col-12">
				                                <div class="card earnings-card">
				                                    <div class="card-body">
				                                        <div class="row">
				                                            <div class="col-6">
				                                                <h4 class="card-title mb-1">Earnings</h4>
				                                                <div class="font-small-2">Total</div>
				                                                <h5 class="mb-1">${{ round($activity_payments, 2) }}</h5>
				                                                <p class="card-text text-muted font-small-2">
				                                                    <!-- <span class="font-weight-bolder">68.2%</span><span> more earnings than last month.</span> -->
				                                                </p>
				                                            </div>
				                                        </div>
				                                    </div>
				                                </div>
				                            </div>
				                        </div>
				                        <div class="row filter">
				                        	<form class="form-group row col-lg-12" id="hotelFilter" method="POST" novalidate action="/api/agent/earnings/activity">
					                        	<div class="col-lg-2">
					                        		<select class="form-control" name="month_filter_activity">
					                        			<option value="">Search by month</option>
					                        			<option value="1">Last month</option>
					                        			<option value="3">Last 3 months</option>
					                        			<option value="6">Last 6 months</option>
					                        		</select>
					                        	</div>
					                        	<div class="col-lg-2">
					                        		<input class="form-control" name="from_date_activity" type="date" />
					                        	</div>
					                        	<div class="col-lg-2">
					                        		<input class="form-control" name="to_date_activity" type="date" />
					                        	</div>
					                        	<div class="col-lg-2">
					                        		<input type="submit" class="btn btn-success" />
					                        		<a href="/agent/earnings/" class="btn btn-success">Clear</a>
					                        	</div>
				                        	</form>
				                        </div>
				                        <br />
			                            <!--/ Earnings Card -->
                                        <div class="card card-company-table">
				                            <div class="card-body p-0">
				                                <div class="table-responsive">
				                                    <table class="table">
				                                        <thead>
				                                            <tr>
				                                                <th>S No.</th>
									                            <th>Booking ID</th>
									                            <th>Description</th>
									                            <th>Commision Price</th>
									                            <th>Status</th>
									                            <th>Created</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                        		@php $counter = 1 @endphp
									                            @foreach($act_bookings as $key => $abook)
									                                <tr>
									                                	<td> {{ $counter++ }} </td>
									                                    <td>{{ $abook['bookID'] }}</td>
									                                    <td>
									                                    	<b>
									                                    	Activity booking for {{ $abook['a_data']['SightseeingName'] }} from {{ date('l, F jS, Y', strtotime(str_replace('/' , '-', $abook['a_data']['from_date']) )) }} <br> Total paid {{ $abook['a_data']['currency'] }} {{ number_format($abook['price'],2) }}
											                          		</b>
											                            </td>
									                                    <td>USD {{ $abook['partners_commision'] }}</td>
									                                    <td>{{ $abook['withdraw_status'] }}</td>
									                                    <td>{{ date('l, F d Y', strtotime($abook['created_at'])) }}</td>
									                                    
									                                </tr>
									                            @endforeach 
				                                        </tbody>
				                                    </table>
				                                </div>
				                            </div>
				                        </div>
                                    </div>
                                    @if($type == 'cab')
                                    <div class="tab-pane active" id="cabs" aria-labelledby="cabs-tab" role="tabpanel">
                                    @else
                                    <div class="tab-pane" id="cabs" aria-labelledby="cabs-tab" role="tabpanel">
                                    @endif
                                    	<!-- Earnings Card -->
                                    	<div class="row">
				                            <div class="col-lg-6 col-md-6 col-12">
				                                <div class="card earnings-card">
				                                    <div class="card-body">
				                                        <div class="row">
				                                            <div class="col-6">
				                                                <h4 class="card-title mb-1">Cab Bookings</h4>
				                                                
				                                                <p class="card-text text-muted font-small-2">
				                                                    These are cab bookings
				                                                </p>
				                                            </div>
				                                        </div>
				                                    </div>
				                                </div>
				                            </div>

				                            <div class="col-lg-6 col-md-6 col-12">
				                                <div class="card earnings-card">
				                                    <div class="card-body">
				                                        <div class="row">
				                                            <div class="col-6">
				                                                <h4 class="card-title mb-1">Earnings</h4>
				                                                <div class="font-small-2">Total</div>
				                                                <h5 class="mb-1">${{ round($cab_payments, 2) }}</h5>
				                                                <p class="card-text text-muted font-small-2">
				                                                    <!-- <span class="font-weight-bolder">68.2%</span><span> more earnings than last month.</span> -->
				                                                </p>
				                                            </div>
				                                        </div>
				                                    </div>
				                                </div>
				                            </div>
				                        </div>
				                        <div class="row filter">
				                        	<form class="form-group row col-lg-12" id="hotelFilter" method="POST" novalidate action="/api/agent/earnings/cab">
					                        	<div class="col-lg-2">
					                        		<select class="form-control" name="month_filter_cab">
					                        			<option value="">Search by month</option>
					                        			<option value="1">Last month</option>
					                        			<option value="3">Last 3 months</option>
					                        			<option value="6">Last 6 months</option>
					                        		</select>
					                        	</div>
					                        	<div class="col-lg-2">
					                        		<input class="form-control" name="from_date_cab" type="date" />
					                        	</div>
					                        	<div class="col-lg-2">
					                        		<input class="form-control" name="to_date_cab" type="date" />
					                        	</div>
					                        	<div class="col-lg-2">
					                        		<input type="submit" class="btn btn-success" />
					                        		<a href="/agent/earnings/" class="btn btn-success">Clear</a>
					                        	</div>
				                        	</form>
				                        </div>
				                        <br />
			                            <!--/ Earnings Card -->
                                        <div class="card card-company-table">
				                            <div class="card-body p-0">
				                                <div class="table-responsive">
				                                    <table class="table">
				                                        <thead>
				                                            <tr>
				                                                <th>S No.</th>
									                            <th>Booking ID</th>
									                            <th>Description</th>
									                            <th>Commision Price</th>
									                            <th>Status</th>
									                            <th>Created</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                        		@php $counter = 1 @endphp
									                            @foreach($cab_bookings as $key => $cbook)
									                                <tr>
									                                	<td> {{ $counter++ }} </td>
									                                    <td>{{ $cbook['bookID'] }}</td>
									                                    <td>
									                                    	<b>
									                                    	Cab booking for {{ $cbook['c_data']['selected_cab']['Vehicle'] }} pickup on {{ date('l, F jS, Y', strtotime(str_replace('/' , '-', $cbook['c_data']['pickup_date']) )) }} {{ date('h:i:s', strtotime(str_replace('/' , '-', $cbook['c_data']['pickup_time']) )) }}.<br>Pickup from {{ $cbook['c_data']['pickup_detailname'] }} and drop off to {{ $cbook['c_data']['dropoff_detailname'] }}<br> Total paid  {{ $cbook['c_data']['selected_cab']['TransferPrice']['CurrencyCode'] }} {{ number_format($cbook['price'],2) }}
											                          		</b>
											                            </td>
									                                    <td>USD {{ $cbook['partners_commision'] }}</td>
									                                    <td>{{ $cbook['withdraw_status'] }}</td>
									                                    <td>{{ date('l, F d Y', strtotime($cbook['created_at'])) }}</td>
									                                    
									                                </tr>
									                            @endforeach 
				                                        </tbody>
				                                    </table>
				                                </div>
				                            </div>
				                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </section>                

            </div>
        </div>
    </div>
@endsection