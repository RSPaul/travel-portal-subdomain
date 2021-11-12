@extends('layouts.app-agent-header')
@section('content') 

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Ecommerce Starts -->
            <section id="dashboard-ecommerce">
                <div class="row match-height">
                    <!-- Medal Card -->
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="card card-congratulation-medal">
                            <div class="card-body">
                                <h5>Congratulations 🎉 {{ $user->name }}!</h5>
                                @if($data['total_sales']  < 100000)
                                    <p class="card-text font-small-3">You have won brownz medal.</p>
                                @elseif($data['total_sales']  > 100000 && $data['total_sales']  < 500000)
                                    <p class="card-text font-small-3">You have won silver medal.</p>
                                @else
                                    <p class="card-text font-small-3">You have won gold medal.</p>
                                @endif
                                <h3 class="mb-75 mt-2 pt-50">
                                    <a href="javascript:void(0);">${{ number_format($data['total_sales']) }}</a>
                                </h3>
                                <a href="/agent/achievements"><button type="button" class="btn btn-primary">View Details</button></a>
                                @if($data['total_sales']  < 100000)
                                   <!--  <img src="{{asset('/images/agent/illustration/bros-madal.png')}}" class="congratulation-medal" width="190" height="120" alt="Medal Pic" /> -->
                                @elseif($data['total_sales']  > 100000 && $data['total_sales']  < 500000)
                                    <img src="{{asset('/images/agent/illustration/silver-madal.png')}}" class="congratulation-medal" width="190" height="120" alt="Medal Pic" />
                                @else
                                    <img src="{{asset('/images/agent/illustration/gold-madal.png')}}" class="congratulation-medal" width="190" height="120" alt="Medal Pic" />
                                @endif


                            </div>
                        </div>
                    </div>
                    <!--/ Medal Card -->

                    <!-- Statistics Card -->
                    <div class="col-xl-8 col-md-6 col-12">
                        <div class="card card-statistics">
                            <div class="card-header">
                                <h4 class="card-title">Statistics</h4>
                                <!-- <div class="d-flex align-items-center">
                                    <p class="card-text font-small-2 mr-25 mb-0">Updated 1 month ago</p>
                                </div> -->
                            </div>
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-primary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="trending-up" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">${{ number_format($data['total_sales']) }}</h4>
                                                <p class="card-text font-small-3 mb-0">Sales</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-info mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="user" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{ number_format($data['total_customers']) }}</h4>
                                                <p class="card-text font-small-3 mb-0">Customers</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                        <div class="media">
                                            <div class="avatar bg-light-danger mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="box" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{ number_format($data['total_bookings']) }}</h4>
                                                <p class="card-text font-small-3 mb-0">Bookings</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12">
                                        <div class="media">
                                            <div class="avatar bg-light-success mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="dollar-sign" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">${{ number_format($data['total_earnings']) }}</h4>
                                                <p class="card-text font-small-3 mb-0">Revenue</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Statistics Card -->
                </div>

                <div class="row match-height">
                    <div class="col-lg-4 col-12">
                        <div class="row match-height">
                            <!-- Bar Chart - Orders -->
                            <div class="col-lg-6 col-md-3 col-6">
                                <div class="card">
                                    <div class="card-body pb-50">
                                        <h6>Orders</h6>
                                        <h2 class="font-weight-bolder mb-1">{{ number_format($data['total_bookings']) }}</h2>
                                        <div id="statistics-order-chart"></div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Bar Chart - Orders -->

                            <!-- Line Chart - Profit -->
                            <div class="col-lg-6 col-md-3 col-6">
                                <div class="card card-tiny-line-stats">
                                    <div class="card-body pb-50">
                                        <h6>Profit</h6>
                                        <h2 class="font-weight-bolder mb-1">{{ number_format($data['total_earnings']) }}</h2>
                                        <div id="statistics-profit-chart"></div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Line Chart - Profit -->

                            <!-- Earnings Card -->
                            <div class="col-lg-12 col-md-6 col-12">
                                <div class="card earnings-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4 class="card-title mb-1">Earnings</h4>
                                                <div class="font-small-2">This Month</div>
                                                <h5 class="mb-1">${{ number_format($data['total_earnings_month']) }}</h5>
                                                <p class="card-text text-muted font-small-2">
                                                    <!-- <span class="font-weight-bolder">68.2%</span><span> more earnings than last month.</span> -->
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <div id="earnings-chart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Earnings Card -->
                        </div>
                    </div>

                    <!-- Revenue Report Card -->
                    <div class="col-lg-8 col-12">
                        <div class="card card-revenue-budget">
                            <div class="row mx-0">
                                <div class="col-md-12 col-12 revenue-report-wrapper">
                                    <div class="d-sm-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title mb-50 mb-sm-0">Revenue Report</h4>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center mr-2">
                                                <span class="bullet bullet-primary font-small-3 mr-50 cursor-pointer"></span>
                                                <span>Sales</span>
                                            </div>
                                            <div class="d-flex align-items-center ml-75">
                                                <span class="bullet bullet-warning font-small-3 mr-50 cursor-pointer"></span>
                                                <span>Earnings</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="revenue-report-chart"></div>
                                </div>
                               <!--  <div class="col-md-4 col-12 budget-wrapper">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle budget-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            2020
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                            <a class="dropdown-item" href="javascript:void(0);">2019</a>
                                            <a class="dropdown-item" href="javascript:void(0);">2018</a>
                                        </div>
                                    </div>
                                    <h2 class="mb-25">$25,852</h2>
                                    <div class="d-flex justify-content-center">
                                        <span class="font-weight-bolder mr-25">Budget:</span>
                                        <span>56,800</span>
                                    </div>
                                    <div id="budget-chart"></div>
                                    <button type="button" class="btn btn-primary">Increase Budget</button>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <!--/ Revenue Report Card -->
                </div>

                <div class="row match-height">
                    <!-- Company Table Card -->
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Bookings</h4>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="hotel-tab" data-toggle="tab" href="#hotel" aria-controls="home" role="tab" aria-selected="true"><i data-feather="home"></i> Hotel( 5% )</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="flight-tab" data-toggle="tab" href="#flight" aria-controls="profile" role="tab" aria-selected="false"><i data-feather="tool"></i> Flight( 2% )</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#activities" id="activities-tab" data-toggle="tab" class="nav-link" aria-controls="profile" role="tab" aria-selected="false"><i data-feather="eye-off"></i> Activities( 5% )</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="cabs-tab" data-toggle="tab" href="#cabs" aria-controls="about" role="tab" aria-selected="false"><i data-feather="user"></i> Cabs( 5% )</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="cabs-tab" data-toggle="tab" href="#packages" aria-controls="about" role="tab" aria-selected="false"><i data-feather="user"></i> Packages( 3% )</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="cabs-tab" data-toggle="tab" href="#cruses" aria-controls="about" role="tab" aria-selected="false"><i data-feather="user"></i> Cruses( 5% )</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="cabs-tab" data-toggle="tab" href="#cellphone_pkg" aria-controls="about" role="tab" aria-selected="false"><i data-feather="user"></i> Cellphone Packages( 5% )</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="cabs-tab" data-toggle="tab" href="#web_analytics" aria-controls="about" role="tab" aria-selected="false"><i data-feather="user"></i> Markups( 60% )</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="hotel" aria-labelledby="hotel-tab" role="tabpanel">
                                       <div class="card card-company-table">
				                            <div class="card-body p-0">
				                                <div class="table-responsive">
				                                    <table class="table">
				                                        <thead>
				                                            <tr>
				                                                <th>S No.</th>
				                                                <th>Date</th>
									                            <th>Booking Status</th>
									                            <th>Confimation Number</th>
									                            <th>Total Paid</th>
									                            <th>Commission</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                        	@if(isset($data['hotel_bookings']) && isset($data['hotel_bookings']))
				                                        		@php $counter = 1 @endphp
									                            @foreach($data['hotel_bookings'] as $h_booking)
									                                @php $r_data  = json_decode($h_booking['r_data'], true); @endphp
									                                <tr>
									                                    <td>{{ $counter++ }}</td>
									                                    <td>{{ date('l, F jS, Y', strtotime($h_booking['created_at'])) }} </td>
									                                    <td>{{ $h_booking['booking_status'] }}</td>
									                                    <td>{{ $h_booking['c_number'] }}</td>
									                                    <td>{{ $r_data['bookingData'][0]['Price']['CurrencyCode'] }}{{ number_format($h_booking['price'],2) }}</td>
									                                    <td>USD {{ number_format($h_booking['commission'],2) }}
									                                    </td>
									                                </tr>
									                            @endforeach
									                        @endif
				                                        </tbody>
				                                    </table>
				                                </div>
				                            </div>
				                       </div>
                                    </div>
                                    <div class="tab-pane" id="flight" aria-labelledby="flight-tab" role="tabpanel">
                                        <div class="card card-company-table">
				                            <div class="card-body p-0">
				                                <div class="table-responsive">
				                                    <table class="table">
				                                        <thead>
				                                            <tr>
				                                                <th>S No.</th>
									                            <th>Date</th>
									                            <th>Booking Status</th>
									                            <th>Confimation Number</th>
									                            <th>Total Paid</th>
									                            <th>Commission</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                             @if(isset($data['flight_bookings']) && isset($data['flight_bookings']))
				                                             	@php $counter = 1 @endphp
									                            @foreach($data['flight_bookings'] as $h_booking)
									                                @php $r_data  = json_decode($h_booking['r_data'], true); @endphp
									                                <tr>
									                                    <td>{{ $counter++ }}</td>
									                                    <td>{{ date('l, F jS, Y', strtotime($h_booking['created_at'])) }}</td>
									                                    <td>{{ $h_booking['booking_status'] == '1' ? 'Confirmed' : 'Pending' }}</td>
									                                    <td>{{ $h_booking['c_number'] }}</td>
									                                    <td>{{ $r_data['bookingData']['PreferredCurrency'] }}{{ number_format($h_booking['price'],2) }}</td>
									                                    <td>USD {{ number_format($h_booking['commission'],2) }}
									                                    </td>
									                                </tr>
									                            @endforeach
									                        @endif
				                                        </tbody>
				                                    </table>
				                                </div>
				                            </div>
				                        </div>
                                    </div>
                                    <div class="tab-pane" id="activities" aria-labelledby="activities-tab" role="tabpanel">
                                        <div class="card card-company-table">
				                            <div class="card-body p-0">
				                                <div class="table-responsive">
				                                    <table class="table">
				                                        <thead>
				                                            <tr>
				                                                <th>S No.</th>
									                            <th>Date</th>
									                            <th>Booking Status</th>
									                            <th>Confimation Number</th>
									                            <th>Total Paid</th>
									                            <th>Commission</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                            @if(isset($data['act_bookings']) && isset($data['act_bookings']))
				                                            	@php $counter = 1 @endphp
									                            @foreach($data['act_bookings'] as $act_booking)
									                                @php $r_data  = json_decode($act_booking['r_data'], true); @endphp
									                                <tr>
									                                    <td>{{ $counter++ }}</td>
									                                    <td>{{ date('l, F jS, Y', strtotime($act_booking['created_at'])) }}</td>
									                                    <td>{{ $act_booking['booking_status'] == '1' ? 'Confirmed' : 'Pending' }}</td>
									                                    <td>{{ $act_booking['c_number'] }}</td>
									                                    <td>{{ $r_data['currency'] }}{{ number_format($act_booking['price'],2) }}</td>
									                                    <td>USD {{ number_format($act_booking['commission'],2) }}
									                                    </td>
									                                </tr>
									                            @endforeach
									                        @endif
				                                        </tbody>
				                                    </table>
				                                </div>
				                            </div>
				                        </div>
                                    </div>
                                    <div class="tab-pane" id="cabs" aria-labelledby="cabs-tab" role="tabpanel">
                                        <div class="card card-company-table">
				                            <div class="card-body p-0">
				                                <div class="table-responsive">
				                                    <table class="table">
				                                        <thead>
				                                            <tr>
				                                                <th>S No.</th>
									                            <th>Date</th>
									                            <th>Booking Status</th>
									                            <th>Confimation Number</th>
									                            <th>Total Paid</th>
									                            <th>Commission</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                            @if(isset($data) && isset($data['cab_bookings']))
				                                            	@php $counter = 1 @endphp
									                            @foreach($data['cab_bookings'] as $c_booking)
									                                @php $r_data  = json_decode($c_booking['r_data'], true); @endphp
									                                <tr>
									                                    <td>{{ $counter++ }}</td>
									                                    <td>{{ date('l, F jS, Y', strtotime($c_booking['created_at'])) }}</td>
									                                    <td>{{ $c_booking['booking_status'] == '1' ? 'Confirmed' : 'Pending' }}</td>
									                                    <td>{{ $c_booking['c_number'] }}</td>
									                                    <td>{{ $r_data['currency_code'] }}{{ number_format($c_booking['price'],2) }}</td>
									                                    <td>USD {{ number_format($c_booking['commission'],2) }}
									                                    </td>
									                                </tr>
									                            @endforeach
									                        @endif
				                                        </tbody>
				                                    </table>
				                                </div>
				                            </div>
				                        </div>
                                    </div>
                                    <div class="tab-pane" id="packages" aria-labelledby="packages-tab" role="tabpanel">Coming Soon</div>
                                    <div class="tab-pane" id="cacrusesbs" aria-labelledby="cruses-tab" role="tabpanel">Coming Soon</div>
                                    <div class="tab-pane" id="cellphone_pkg" aria-labelledby="cellphone_pkg-tab" role="tabpanel">Coming Soon</div>
                                    <div class="tab-pane" id="web_analytics" aria-labelledby="web_analytics-tab" role="tabpanel">
                                        <!-- For Hotel  -->
                                        <div class="card card-company-table">
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>S No.</th>
                                                                <th>Date</th>
                                                                <th>Type</th>
                                                                <th>Booking Status</th>
                                                                <th>Confimation Number</th>
                                                                <th>Markup Price</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(isset($data['hotel_bookings']) && isset($data['hotel_bookings']))
                                                                @php $counter = 1 @endphp
                                                                @foreach($data['hotel_bookings'] as $h_booking)
                                                                @if($h_booking['agent_markup'] > 0)
                                                                    @php $r_data  = json_decode($h_booking['r_data'], true); @endphp
                                                                    <tr>
                                                                        <td>{{ $counter++ }}</td>
                                                                        <td>{{ date('l, F jS, Y', strtotime($h_booking['created_at'])) }} </td>
                                                                        <td>Hotel</td>
                                                                        <td>{{ $h_booking['booking_status'] }}</td>
                                                                        <td>{{ $h_booking['c_number'] }}</td>
                                                                        <td>USD {{ number_format($h_booking['agent_markup'],2) }}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Ends -->

                                        <!-- For Flights -->

                                        <div class="card card-company-table">
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>S No.</th>
                                                                <th>Date</th>
                                                                <th>Type</th>
                                                                <th>Booking Status</th>
                                                                <th>Confimation Number</th>
                                                                <th>Markup Price</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                             @if(isset($data['flight_bookings']) && isset($data['flight_bookings']))
                                                                @php $counter = 1 @endphp
                                                                @foreach($data['flight_bookings'] as $h_booking)
                                                                @if($h_booking['agent_markup'] > 0)
                                                                    @php $r_data  = json_decode($h_booking['r_data'], true); @endphp
                                                                    <tr>
                                                                        <td>{{ $counter++ }}</td>
                                                                        <td>{{ date('l, F jS, Y', strtotime($h_booking['created_at'])) }}</td>
                                                                        <td>Flight</td>
                                                                        <td>{{ $h_booking['booking_status'] == '1' ? 'Confirmed' : 'Pending' }}</td>
                                                                        <td>{{ $h_booking['c_number'] }}</td>
                                                                        <td>USD {{ number_format($h_booking['agent_markup'],2) }}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </section>
            <!-- Dashboard Ecommerce ends -->

        </div>
    </div>
</div>
@endsection