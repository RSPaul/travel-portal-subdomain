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
                                    <li class="breadcrumb-item"><a href="/agent/notifications">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active">Notifications
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
                                    <h4 class="card-title">My Notifications</h4>
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
                                    <li class="nav-item">
                                        <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" aria-controls="about" role="tab" aria-selected="false"><i data-feather="user"></i> Bank</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments" aria-controls="about" role="tab" aria-selected="false"><i data-feather="user"></i> Payments</a>
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
									                            <th>Booking Type</th>
									                            <th>Description</th>
									                            <th>Commision Price</th>
									                            <th>Status</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
									                            @php $counter = 1 @endphp
									                            @foreach($all_notifications as $key => $not)
									                                @if($not['type'] == 'hotel')
									                                <tr>
									                                	<td> {{ $counter++ }} </td>
									                                    <td>{{ $not['type'] }}</td>
									                                    <td>{!!html_entity_decode($not['description'])!!}</td>
									                                    <td>{{ $not['price'] }}</td>
									                                    <td>{{ $not['status'] }}</td>
									                                    
									                                </tr>
									                                @endif
									                            @endforeach
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
									                            <th>Booking Type</th>
									                            <th>Description</th>
									                            <th>Commision Price</th>
									                            <th>Status</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                        		@php $counter = 1 @endphp
									                            @foreach($all_notifications as $key => $not)
									                                @if($not['type'] == 'flight')
									                                <tr>
									                                	<td> {{ $counter++ }} </td>
									                                    <td>{{ $not['type'] }}</td>
									                                    <td>{!!html_entity_decode($not['description'])!!}</td>
									                                    <td>{{ $not['price'] }}</td>
									                                    <td>{{ $not['status'] }}</td>
									                                    
									                                </tr>
									                                @endif
									                            @endforeach
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
									                            <th>Booking Type</th>
									                            <th>Description</th>
									                            <th>Commision Price</th>
									                            <th>Status</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                        		@php $counter = 1 @endphp
									                            @foreach($all_notifications as $key => $not)
									                                @if($not['type'] == 'activity')
									                                <tr>
									                                	<td> {{ $counter++ }} </td>
									                                    <td>{{ $not['type'] }}</td>
									                                    <td>{!!html_entity_decode($not['description'])!!}</td>
									                                    <td>{{ $not['price'] }}</td>
									                                    <td>{{ $not['status'] }}</td>
									                                @endif    
									                                </tr>
									                            @endforeach
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
									                            <th>Booking Type</th>
									                            <th>Description</th>
									                            <th>Commision Price</th>
									                            <th>Status</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                        		@php $counter = 1 @endphp
									                            @foreach($all_notifications as $key => $not)
									                                @if($not['type'] == 'cab')
									                                <tr>
									                                	<td> {{ $counter++ }} </td>
									                                    <td>{{ $not['type'] }}</td>
									                                    <td>{!!html_entity_decode($not['description'])!!}</td>
									                                    <td>{{ $not['price'] }}</td>
									                                    <td>{{ $not['status'] }}</td>
									                                    
									                                </tr>
									                                @endif
									                            @endforeach
				                                        </tbody>
				                                    </table>
				                                </div>
				                            </div>
				                        </div>
                                    </div>
                                    <div class="tab-pane" id="bank" aria-labelledby="bank-tab" role="tabpanel">
                                        <div class="card card-company-table">
				                            <div class="card-body p-0">
				                                <div class="table-responsive">
				                                    <table class="table">
				                                        <thead>
				                                            <tr>
				                                                <th>S No.</th>
									                            <th>Booking Type</th>
									                            <th>Description</th>
									                            <th>Status</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                        		@php $counter = 1 @endphp
									                            @foreach($all_notifications as $key => $not)
									                                @if($not['type'] == 'bank')
									                                <tr>
									                                	<td> {{ $counter++ }} </td>
									                                    <td>{{ $not['type'] }}</td>
									                                    <td>{!!html_entity_decode($not['description'])!!}</td>
									                                    <td>{{ $not['status'] }}</td>
									                                </tr>
									                                @endif
									                            @endforeach
				                                        </tbody>
				                                    </table>
				                                </div>
				                            </div>
				                        </div>
                                    </div>
                                    <div class="tab-pane" id="payments" aria-labelledby="payments-tab" role="tabpanel">
                                        <div class="card card-company-table">
				                            <div class="card-body p-0">
				                                <div class="table-responsive">
				                                    <table class="table">
				                                        <thead>
				                                            <tr>
				                                                <th>S No.</th>
									                            <th>Booking Type</th>
									                            <th>Description</th>
									                            <th>Amount</th>
									                            <th>Status</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                        		@php $counter = 1 @endphp
									                            @foreach($all_notifications as $key => $not)
									                                @if($not['type'] == 'payment')
									                                <tr>
									                                	<td> {{ $counter++ }} </td>
									                                    <td>{{ $not['type'] }}</td>
									                                    <td>{!!html_entity_decode($not['description'])!!}</td>
									                                    <td>USD {{ $not['price'] }}</td>
									                                    <td>{{ $not['status'] }}</td>
									                                </tr>
									                                @endif
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