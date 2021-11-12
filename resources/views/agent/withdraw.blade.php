@extends('layouts.app-agent-header')
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
                                    <li class="breadcrumb-item"><a href="/agent/withdraw">Dashbaord</a>
                                    </li>
                                    <li class="breadcrumb-item active">Withdraw
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
                                    <h4 class="card-title">My Earnings</h4>
                                </div>
                                <div class="card-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="hotel-tab" data-toggle="tab" href="#hotel" aria-controls="home" role="tab" aria-selected="true"><i data-feather="home"></i> Hotel</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="flight-tab" data-toggle="tab" href="#flight" aria-controls="profile" role="tab" aria-selected="false"><i data-feather="tool"></i> Flight</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#activities" id="activities-tab" data-toggle="tab" class="nav-link" aria-controls="profile" role="tab" aria-selected="false"><i data-feather="eye-off"></i> Activities</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="cabs-tab" data-toggle="tab" href="#cabs" aria-controls="about" role="tab" aria-selected="false"><i data-feather="user"></i> Cabs</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="hotel" aria-labelledby="hotel-tab" role="tabpanel">
                                       <div class="card card-company-table">
				                            <div class="card-body p-0">
				                                <div class="table-responsive">
				                                    <table class="datatables-basic table">
				                                        <thead>
				                                            <tr>
				                                                <th width="5%">
				                                                	<input type="checkbox" name="h_booking[]" id="hb_all">
				                                            	</th>
				                                                <th>S No.</th>
				                                                <th>Date</th>
									                            <th>Booking Status</th>
									                            <th>Confimation Number</th>
									                            <th>Total Paid</th>
									                            <th>Commission</th>
									                            <th>Extra Markup</th>
									                            <th>Withdraw Status</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                        	@if(isset($bookings_data['hotel_bookings']) && isset($bookings_data['hotel_bookings']))
				                                        		@php $counter = 1 @endphp
									                            @foreach($bookings_data['hotel_bookings'] as $h_booking)
									                                @php $r_data  = json_decode($h_booking['r_data'], true); @endphp
									                                <tr>
									                                	<td>
									                                		<input type="checkbox" name="h_booking[]" id="{{$h_booking->id}}" class="hb-select" data-amount="{{round($h_booking['commission'],2)}}" data-markup="{{round($h_booking['agent_markup'],2)}}">
									                                	</td>
									                                    <td>{{ $counter++ }}</td>
									                                    <td>{{ date('l, F jS, Y', strtotime($h_booking['created_at'])) }} </td>
									                                    <td>{{ $h_booking['booking_status'] }}</td>
									                                    <td>{{ $h_booking['c_number'] }}</td>
									                                    <td>{{ $r_data['bookingData'][0]['Price']['CurrencyCode'] }}{{ number_format($h_booking['price'],2) }}</td>
									                                    <td>USD {{ number_format($h_booking['commission'],2) }}
									                                    </td>
									                                    <td>USD {{ number_format($h_booking['agent_markup'],2) }}
									                                    </td>
									                                    <td>
									                                    	@if($h_booking->withdraw_status == 'pending')
									                                    		Not Requested
									                                    	@elseif($h_booking->withdraw_status == 'requested')
									                                    		Request Sent
									                                    	@else
									                                    		Paid
									                                    	@endif
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
				                                            	<th width="5%">
				                                                	<input type="checkbox" name="h_booking[]" id="fb_all">
				                                            	</th>
				                                                <th>S No.</th>
									                            <th>Date</th>
									                            <th>Booking Status</th>
									                            <th>Confimation Number</th>
									                            <th>Total Paid</th>
									                            <th>Commission</th>
									                            <th>Extra Markup</th>
									                            <th>Withdraw Status</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                             @if(isset($bookings_data['flight_bookings']) && isset($bookings_data['flight_bookings']))
				                                             	@php $counter = 1 @endphp
									                            @foreach($bookings_data['flight_bookings'] as $h_booking)
									                                @php $r_data  = json_decode($h_booking['r_data'], true); @endphp
									                                <tr>
									                                	<td>
									                                		<input type="checkbox" name="f_booking[]" id="{{$h_booking->id}}" class="fb-select" data-amount="{{round($h_booking['commission'],2)}}" data-markup="{{round($h_booking['agent_markup'],2)}}">
									                                	</td>
									                                    <td>{{ $counter++ }}</td>
									                                    <td>{{ date('l, F jS, Y', strtotime($h_booking['created_at'])) }}</td>
									                                    <td>{{ $h_booking['booking_status'] == '1' ? 'Confirmed' : 'Pending' }}</td>
									                                    <td>{{ $h_booking['c_number'] }}</td>
									                                    <td>{{ $r_data['bookingData']['PreferredCurrency'] }}{{ number_format($h_booking['price'],2) }}</td>
									                                    <td>USD {{ number_format($h_booking['commission'],2) }}
									                                    </td>
									                                    <td>USD {{ number_format($h_booking['agent_markup'],2) }}
									                                    </td>
									                                    <td>
									                                    	@if($h_booking->withdraw_status == 'pending')
									                                    		Not Requested
									                                    	@elseif($h_booking->withdraw_status == 'requested')
									                                    		Request Sent
									                                    	@else
									                                    		Paid
									                                    	@endif
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
				                                            	<th width="5%">
				                                                	<input type="checkbox" name="h_booking[]" id="ab_all">
				                                            	</th>
				                                                <th>S No.</th>
									                            <th>Date</th>
									                            <th>Booking Status</th>
									                            <th>Confimation Number</th>
									                            <th>Total Paid</th>
									                            <th>Commission</th>
									                            <th>Withdraw Status</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                            @if(isset($bookings_data['act_bookings']) && isset($bookings_data['act_bookings']))
				                                            	@php $counter = 1 @endphp
									                            @foreach($bookings_data['act_bookings'] as $act_booking)
									                                @php $r_data  = json_decode($act_booking['r_data'], true); @endphp
									                                <tr>
									                                	<td>
									                                		<input type="checkbox" name="a_booking[]" id="{{$act_booking->id}}" class="ab-select" data-amount="{{round($act_booking['commission'],2)}}">
									                                	</td>
									                                    <td>{{ $counter++ }}</td>
									                                    <td>{{ date('l, F jS, Y', strtotime($act_booking['created_at'])) }}</td>
									                                    <td>{{ $act_booking['booking_status'] == '1' ? 'Confirmed' : 'Pending' }}</td>
									                                    <td>{{ $act_booking['c_number'] }}</td>
									                                    <td>{{ $r_data['currency'] }}{{ number_format($act_booking['price'],2) }}</td>
									                                    <td>USD {{ number_format($act_booking['commission'],2) }}
									                                    </td>
									                                    <td>
									                                    	@if($act_booking->withdraw_status == 'pending')
									                                    		Not Requested
									                                    	@elseif($act_booking->withdraw_status == 'requested')
									                                    		Request Sent
									                                    	@else
									                                    		Paid
									                                    	@endif
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
				                                            	<th width="5%">
				                                                	<input type="checkbox" name="h_booking[]" id="cb_all">
				                                            	</th>
				                                                <th>S No.</th>
									                            <th>Date</th>
									                            <th>Booking Status</th>
									                            <th>Confimation Number</th>
									                            <th>Total Paid</th>
									                            <th>Commission</th>
									                            <th>Withdraw Status</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                            @if(isset($bookings_data) && isset($bookings_data['cab_bookings']))
				                                            	@php $counter = 1 @endphp
									                            @foreach($bookings_data['cab_bookings'] as $c_booking)
									                                @php $r_data  = json_decode($c_booking['r_data'], true); @endphp
									                                <tr>
									                                	<td>
									                                		<input type="checkbox" name="c_booking[]" id="{{$c_booking->id}}" class="cb-select" data-amount="{{round($c_booking['commission'],2)}}">
									                                	</td>
									                                    <td>{{ $counter++ }}</td>
									                                    <td>{{ date('l, F jS, Y', strtotime($c_booking['created_at'])) }}</td>
									                                    <td>{{ $c_booking['booking_status'] == '1' ? 'Confirmed' : 'Pending' }}</td>
									                                    <td>{{ $c_booking['c_number'] }}</td>
									                                    <td>{{ $r_data['currency_code'] }}{{ number_format($c_booking['price'],2) }}</td>
									                                    <td>USD {{ number_format($c_booking['commission'],2) }}
									                                    </td>
									                                    <td>
									                                    	@if($c_booking->withdraw_status == 'pending')
									                                    		Not Requested
									                                    	@elseif($c_booking->withdraw_status == 'requested')
									                                    		Request Sent
									                                    	@else
									                                    		Paid
									                                    	@endif
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
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                            	<div class="card-header">
                                    <h4 class="card-title">Total Earnings ($<span id="totalEarnings">{{ ($earnings_data['hotel_earning'] + $earnings_data['flight_earning'] + $earnings_data['cab_earning'] + $earnings_data['activity_earning']) }}</span>)</h4>
                                </div>
                                <div class="card-body">
                                	<div class="row">
                                		<div class="col-12">
                                			@if($bank_details->verified == 'yes')
	                                			@if((($earnings_data['hotel_earning'] + $earnings_data['flight_earning'] + $earnings_data['cab_earning'] + $earnings_data['activity_earning']) - 500) > 0)
	                                				<button class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Keeping $500 in your account is necessary." id="withdrawPayment" disabled>Withdraw $<i id="totalWithdraw">0</i>&nbsp;<i class="fa fa-question-circle"></i></button>
	                                			@else
	                                				<button class="btn btn-primary disabled" data-toggle="tooltip" data-placement="top" title="Withdrawals are disabled if amount is less than $500."> Withdraw&nbsp;<i class="fa fa-question-circle"></i></button>
	                                			@endif
	                                		@else
                                				<button class="btn btn-primary disabled" data-toggle="tooltip" data-placement="top" title="You will be able to withdraw the money once admin verify your bank account."> Withdraw&nbsp;<i class="fa fa-question-circle"></i></button>
	                                		@endif
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