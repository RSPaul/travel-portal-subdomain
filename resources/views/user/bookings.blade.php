@extends('layouts.app-header')

@section('content')
<section class="booking_lists_data test_data_aftr_login">
  <div class="container">
      <nav class="bookings_menu_tab">
       <div class="nav nav-tabs nav-fill" role="tablist">
          <a class="nav-item nav-link active" id="nav-fare-tab" data-toggle="tab" href="#nav-hotelinfo" role="tab" aria-controls="nav-hotelinfo" aria-selected="true">Hotels</a>
          <a class="nav-item nav-link" id="nav-fare-tab" data-toggle="tab" href="#nav-flightinfo" role="tab" aria-controls="nav-flightinfo" aria-selected="false">Flights</a>
          <a class="nav-item nav-link" id="nav-fare-tab" data-toggle="tab" href="#nav-cabinfo" role="tab" aria-controls="nav-cabinfo" aria-selected="false">Cabs</a>
          <a class="nav-item nav-link" id="nav-fare-tab" data-toggle="tab" href="#nav-activitiesinfo" role="tab" aria-controls="nav-activitiesinfo" aria-selected="false">Activities</a>
       </div>
      </nav>
    <div class="row">
      @if(session('success'))
        <p class="alert aler-success">{{ Session::get('success') }}</p>
      @endif
      @if(Session::has('error'))
        <p class="alert aler-danger">{{ Session::get('error') }}</p>
      @endif
      <div class="tab-content col-md-12 bookings_class tab_list_upp_secs">
        <div class="tab-pane fade show active" id="nav-hotelinfo" role="tabpanel" aria-labelledby="nav-hotelinfo">
        <div class="row">
          <div class="col-md-12">
            <ul class="list-inline">
            @foreach($hotel_bookings as $key => $booking)
              @if($booking->booking_id == 'activity' || $booking->booking_id == 'cruise' || $booking->booking_id == 'package')
                <li class="list_inner_dddd">
                  <div class="inner_book_lists">                  
                    <div class="upper_booking_detail">
                      <div class="row align-items-center justify-content-center">
                        <div class="col-md-8">
                          <div class="booking_detail_data left_booki_data_parts">
                            <h3>{{$booking->activity_data['name']}}</h3>
                            <div class="test_stars">
                              <a href="#"><i class="fa fa-star" aria-hidden="true"></i></a>
                              <a href="#"><i class="fa fa-star" aria-hidden="true"></i></a>
                              <a href="#"><i class="fa fa-star" aria-hidden="true"></i></a>
                              <a href="#"><i class="fa fa-star-half-o" aria-hidden="true"></i></a>
                              <a href="#"><i class="fa fa-star-half-o" aria-hidden="true"></i></a>
                            </div>
                            <div class="booking_id_detals">
                              <ul class="list-inline">
                                <li>{{$booking->status}}</li>
                                <li><strong>Country {{isset($booking->request_data['country']) ? $booking->request_data['country'] : 'AE'}}</strong></li>
                                <li>Booking ID - <strong>{{$booking->id}}</strong></li>
                              </ul>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="view_booking_data pull-right">
                            <a href="javascript:void(0);" class="btn btn_view_booking">View Booking</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="bottom_booking_data_detail nearly_bottom_data">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="check_in_out_det">
                            <h5>Check-In</h5>
                            <h4>{{isset($booking->request_data['checkInDate']) ? $booking->request_data['checkInDate'] : '20 Oct 19 Sun'}}</h4>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="check_in_out_det">
                            <h5>Check-Out</h5>
                            <h4>{{isset($booking->request_data['checkInDate']) ? $booking->request_data['checkOutDate'] : '20 Oct 19 Sun'}}</h4>
                          </div>
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                          <div class="download_invoice pull-right">
                            <a href="/invoice/activity/{{ $booking->id }}" class="download_invoice_btn"><i class="fa fa-arrow-down" aria-hidden="true"></i> Download Invoice</a>
                          </div>
                        </div>
                      </div>
                    </div>               
                  </div>
                </li>
              @endif
              @if($booking->type == 'hotel')
              @if(sizeof($hotel_bookings) > 0)
              <li class="list_inner_dddd">
                <div class="inner_book_lists">                  
                  <div class="upper_booking_detail">
                    <div class="row align-items-center justify-content-center">
                      <div class="col-md-8">
                        <div class="booking_detail_data left_booki_data_parts">
                          <h3>{{$booking->request_data['hotelName']}}</h3>
                          <div class="test_stars">
                            <a href="#"><i class="fa fa-star" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-star" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-star" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-star-half-o" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-star-half-o" aria-hidden="true"></i></a>
                          </div>
                          <div class="booking_id_detals">
                            <ul class="list-inline data_lists_da">
                              @if($booking->hotel_booking_status == 'Confirmed')
                                @if(isset($booking->request_data['isVoucherBooking']) && $booking->request_data['isVoucherBooking'] == false)
                                  <li>RESERVED</li>
                                @else
                                  <li>{{$booking->hotel_booking_status}}</li>
                                @endif
                              @else
                                <li>Cancellation - {{$booking->hotel_booking_status}} <a href="javascript:void(0);" class="view-refund" data-requestid="{{ $booking->change_request_id }}" data-tokenid="{{ $booking->token_id }}">(Click to view Refund Status)</a></li>
                              @endif
                              <!-- <li><strong>Hotel in {{isset($booking->request_data['country']) ? $booking->request_data['country'] : 'AE'}}</strong></li> -->
                              <li>Booking ID - <strong>{{$booking->booking_id}}</strong></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <?php $policyCount = sizeof($booking->request_data['bookingData'][0]['CancellationPolicies']); 
                      $lastCancelDate = date('Y-m-d h:i:s', strtotime($booking->request_data['bookingData'][0]['CancellationPolicies'][$policyCount - 1]['ToDate']));                      
                      $todayDate = date('Y-m-d h:i:s');
                      // echo "<pre>";
                      // print_r($booking->request_data);
                      // echo "</pre>";
                      ?>
                      <div class="col-md-4">
                        @if(isset($booking->request_data['isVoucherBooking']) && $booking->request_data['isVoucherBooking'] == false)
                          <div class="view_booking_data pull-right">
                            <a href="javascript:void(0);" class="btn btn-danger btn_view_booking generate-vucher" data-toggle="modal" data-target="#generateVoucherModal_{{ $key }}">{{$booking->request_data['lastCancellationDate']}}</a>
                          </div>
                          <div class="view_booking_data pull-right">
                            <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal" data-target="#generateVoucherModal_{{ $key }}">Generate Voucher</a>
                          </div>
                          <br><br>
                        @endif
                        @if($todayDate < $lastCancelDate && $booking->hotel_booking_status == 'Confirmed')
                          <div class="view_booking_data pull-right">
                            <a href="javascript:void(0);" class="btn btn-danger btn_view_booking cancel-book-btn" data-toggle="modal" data-target="#hotelCancelBookingModal_{{ $key }}" >Cancel Booking</a>
                            <br><br>
                            <a href="/user/booking/hotel/{{ $booking->id }}/false" class="btn btn-primary btn_view_booking" >View Details</a>
                          </div>
                        @else
                          <div class="view_booking_data pull-right">
                            <a href="/user/booking/hotel/{{ $booking->id }}/false" class="btn btn-primary btn_view_booking" >View Details</a>
                          </div>
                        @endif
                        <div id="generateVoucherModal_{{ $key }}" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Generate Voucher - <strong>{{$booking->booking_id}}</strong></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <form action="{{ route('voucher_booking_hotel', 'hotel') }}" method="POST">
                                <div class="modal-body">
                                  <p>This will confirm your booking, please click the button below to generate booking voucher.</p>
                                  @csrf
                                  <input type="hidden" name="TokenId" value="{{ $booking->token_id }}">
                                  <input type="hidden" name="BookingId" value="{{ $booking->booking_id }}">
                                    
                                </div>
                                <div class="modal-footer">
                                  <input type="submit" name="submit" class="btn btn-primary" value="Generate Voucher">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                              </form>
                            </div>

                          </div>
                        </div>

                        <div id="hotelCancelBookingModal_{{ $key }}" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Cancel Booking - <strong>{{$booking->booking_id}}</strong></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <form action="{{ route('cancel_booking_hotel', 'hotel') }}" method="POST">
                                <div class="modal-body">
                                  <p><strong>Cancellation Policy:&nbsp;</strong>
                                    {!!html_entity_decode(str_replace(array('#^#', '#', '|', '#!#', '!'), array(' ', ' ', ' ', ' ', ' '),$booking->request_data['bookingData'][0]['CancellationPolicy'])) !!}</p>
                                    @csrf
                                    <div class="form-group">
                                      <textarea name="Remarks" class="form-control" placeholder="Enter reason to cancel this booking" required></textarea>
                                    </div>
                                    <input type="hidden" name="TokenId" value="{{ $booking->token_id }}">
                                    <input type="hidden" name="BookingId" value="{{ $booking->booking_id }}">
                                    
                                </div>
                                <div class="modal-footer">
                                  <input type="submit" name="submit" class="btn btn-primary" value="Cancel Booking">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                              </form>
                            </div>

                          </div>
                        </div> 
                      </div>
                    </div>
                  </div>
                  <div class="bottom_booking_data_detail nearly_bottom_data">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="check_in_out_det">
                          <h5>Check-In</h5>
                          <h4>{{isset($booking->request_data['checkInDate']) ? date('l, F jS, Y', strtotime($booking->request_data['checkInDate'])) : '20 Oct 19 Sun'}}</h4>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="check_in_out_det">
                          <h5>Check-Out</h5>
                          <h4>{{isset($booking->request_data['checkOutDate']) ? date('l, F jS, Y', strtotime($booking->request_data['checkOutDate'])) : '20 Oct 19 Sun'}}</h4>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="check_in_out_det rooms_data_date">
                          <h4><i class="fa fa-building-o" aria-hidden="true"></i>{{$booking->request_data['noofrooms']}} Room(s), {{$booking->request_data['noOfNights']}} Night(s)</h4>
                          <p><i class="fa fa-user-secret" aria-hidden="true"></i> {{$booking->request_data['bookingData']['HotelPassenger'][1][0]['FirstName']}} 

                          	@if(sizeof($booking->request_data['bookingData']['HotelPassenger']) > 0 )
                          		+{{sizeof($booking->request_data['bookingData']['HotelPassenger']) - 1}}
                          	@endif
                          </p>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="download_invoice pull-right">
                          <a href="/invoice/hotel/{{ $booking->id }}" class="download_invoice_btn"><i class="fa fa-arrow-down" aria-hidden="true"></i> Download Invoice</a>
                        </div>
                      </div>
                    </div>
                  </div>               
                </div>
              </li>
              @endif
              @endif
            @endforeach
            </ul>    
            @if(sizeof($hotel_bookings) < 1)
            <h3>You don't have any bookings yet, <a href="/">Click here</a> to book room now.</h3>
            @endif
          </div>
          </div>
        </div>
        <div class="tab-pane fade show" id="nav-flightinfo" role="tabpanel" aria-labelledby="nav-flightinfo">
           @foreach($flight_bookings as $f_key => $booking)
            @if($booking->request_data['bookingData']['ResultIndex'] && strpos($booking->request_data['bookingData']['ResultIndex'], 'OB') !== false )
              <div class="row air_list_data air_par_sec_data">
              <div class="flex_width_air flight_wid_air col-md-12">
                 <div class="vistara_Data">
                    <img src="/images/{{ $booking->request_data['travelData']['flight_jetname_ob'] }}.gif" alt="{{ $booking->request_data['travelData']['flight_jetname_ob'] }}">
                    <span>{{ $booking->request_data['travelData']['flight_name_ob'] }} 
                    </span>
                 </div>
                 <div class="main_flts_time">
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['from_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['dep_time'] }}</div>
                    </div>
                    <div class="flight_duration">
                      <span>{{ $booking->request_data['travelData']['duration'] }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                    </div>
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['to_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['arr_time'] }}</div>
                    </div>
                 </div> 
              </div>
              @if(isset($booking->request_data['travelData']['stop_from_loc']) && $booking->request_data['travelData']['stop_from_loc'] != '' )
              <div class="flex_width_air flight_wid_air col-md-12 mt-4">
                 <div class="vistara_Data">
                    <img src="/images/{{ $booking->request_data['travelData']['stop_flight_jetname_ob'] }}.gif" alt="{{ $booking->request_data['travelData']['stop_flight_jetname_ob'] }}">
                    <span>{{ $booking->request_data['travelData']['stop_flight_name_ob'] }} 
                    </span>
                 </div>
                 <div class="main_flts_time">
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['stop_from_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['stop_dep_time'] }}</div>
                    </div>
                    <div class="flight_duration">
                      <span>{{ $booking->request_data['travelData']['stop_duration'] }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                    </div>
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['stop_to_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['stop_arr_time'] }}</div>
                    </div>
                 </div> 
              </div>
              @endif
              @if(isset($booking->request_data['travelData']['return_int_from_loc']) && $booking->request_data['travelData']['return_int_from_loc'] != '' )
              <div class="flex_width_air flight_wid_air col-md-12 mt-4">
                 <div class="vistara_Data">
                    <img src="/images/{{ $booking->request_data['travelData']['return_int_flight_jetname_ob'] }}.gif" alt="{{ $booking->request_data['travelData']['return_int_flight_jetname_ob'] }}">
                    <span>{{ $booking->request_data['travelData']['return_int_flight_name_ob'] }} 
                    </span>
                 </div>
                 <div class="main_flts_time">
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['return_int_from_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['return_int_dep_time'] }}</div>
                    </div>
                    <div class="flight_duration">
                      <span>{{ $booking->request_data['travelData']['return_int_duration'] }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                    </div>
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['return_int_to_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['return_int_arr_time'] }}</div>
                    </div>
                 </div> 
              </div>
              @endif
              @if(isset($booking->request_data['travelData']['stop_return_int_from_loc']) && $booking->request_data['travelData']['stop_return_int_from_loc'] != '' )
              <div class="flex_width_air flight_wid_air col-md-12 mt-4">
                 <div class="vistara_Data">
                    <img src="/images/{{ $booking->request_data['travelData']['stop_return_int_flight_jetname_ob'] }}.gif" alt="{{ $booking->request_data['travelData']['stop_return_int_flight_jetname_ob'] }}">
                    <span>{{ $booking->request_data['travelData']['stop_return_int_flight_name_ob'] }} 
                    </span>
                 </div>
                 <div class="main_flts_time">
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['stop_return_int_from_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['stop_return_int_dep_time'] }}</div>
                    </div>
                    <div class="flight_duration">
                      <span>{{ $booking->request_data['travelData']['stop_return_int_duration'] }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                    </div>
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['stop_return_int_to_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['stop_return_int_arr_time'] }}</div>
                    </div>
                 </div> 
              </div>
              @endif
              <div class="view_booking_data pull-right" style="margin-top:10px;">
                @if(empty($booking->change_request_id))
                <a href="javascript:void(0);" class="btn btn-danger btn_view_booking cancel-book-btn" data-toggle="modal" data-target="#flightobCancelBookingModal_{{ $f_key }}">Cancel Booking</a>
                @else
                <a href="javascript:void(0);" class="view-refund-flight" data-requestid="{{ $booking->change_request_id }}" data-tokenid="{{ $booking->token_id }}">(Click to view Refund Status)</a>
                @endif
                <a href="/user/booking/flight/{{$booking->id}}/false" class="btn btn-primary btn_view_booking">View Details</a>
                <a href="/invoice/flights/{{ $booking->id }}" class="download_invoice_btn"><i class="fa fa-arrow-down" aria-hidden="true"></i> Download Invoice</a>
                        
              </div>

              <div id="flightobCancelBookingModal_{{ $f_key }}" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                  <!-- Modal content-->
                  <form action="{{ route('cancelFlightBooking') }}" method="POST">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Cancel Booking - <strong>{{$booking->booking_id}}</strong></h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                          <!-- <label>Reason</label> -->
                          <textarea name="remarks" class="form-control" placeholder="Enter reason to cancel this booking" required></textarea>
                        </div>
                        <input type="hidden" name="Origin" value="{{ $booking->request_data['travelData']['city_code_arrival'] }}">
                        <input type="hidden" name="Destination" value="{{ $booking->request_data['travelData']['city_code_departure'] }}">
                        <input type="hidden" name="TokenId" value="{{ $booking->token_id }}">
                        <input type="hidden" name="BookingId" value="{{ $booking->booking_id }}">
                        <input type="hidden" name="TicketId" value="<?php echo serialize($booking->request_data['travelData']['ticket_id']); ?>">
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="submit" class="btn btn-primary" value="Cancel Booking">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                  </div>

                </div>
              </div> 

              <div id="flightobBookingDetailsModal_{{ $f_key }}" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Flight Booking - <strong>{{$booking->booking_id}}</strong></h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                      <ul class="list-inline">
                        <li>Booking ID - <strong>{{$booking->booking_id}}</strong></li>
                        
                        <li>Total Paid - <strong>{{$booking->request_data['bookingData']['PreferredCurrency']}} 
                        @if(isset($booking->request_data['travelData']['extra_baggage_meal_price']))

                        {{ round( $booking->request_data['travelData']['amount'] , 2 ) + round( $booking->request_data['travelData']['extra_baggage_meal_price'] , 2 ) }}
                        @else
                        {{ round( $booking->request_data['travelData']['amount'] , 2 )  }}
                        @endif
                        
                      </strong></li>
                      </ul>
                      <br>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
              </div> 
            </div>
            @endif
            @if($booking->request_data['bookingData']['ResultIndex'] && strpos($booking->request_data['bookingData']['ResultIndex'], 'IB') !== false )
             <div class="row air_list_data air_par_sec_data">
              <div class="flex_width_air flight_wid_air col-md-12">
                 <div class="vistara_Data">
                    <img src="/images/{{ $booking->request_data['travelData']['flight_jetname_ib'] }}.gif" alt="{{ $booking->request_data['travelData']['flight_jetname_ib'] }}">
                    <span>{{ $booking->request_data['travelData']['flight_name_ib'] }} 
                    </span>
                 </div>
                 <div class="main_flts_time">
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['return_ib_from_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['return_ib_dep_time'] }}</div>
                    </div>
                    <div class="flight_duration">
                      <span>{{ $booking->request_data['travelData']['return_ib_duration'] }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                    </div>
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['return_ib_to_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['return_ib_arr_time'] }}</div>
                    </div>
                 </div> 
              </div>
              @if(isset($booking->request_data['travelData']['stop_return_ib_from_loc']) && $booking->request_data['travelData']['stop_return_ib_from_loc'] != '' )
              <div class="flex_width_air flight_wid_air col-md-12 mt-4">
                 <div class="vistara_Data">
                    <img src="/images/{{ $booking->request_data['travelData']['stop_flight_jetname_ib'] }}.gif" alt="{{ $booking->request_data['travelData']['stop_flight_jetname_ib'] }}">
                    <span>{{ $booking->request_data['travelData']['stop_flight_name_ib'] }} 
                    </span>
                 </div>
                 <div class="main_flts_time">
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['stop_return_ib_from_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['stop_return_ib_dep_time'] }}</div>
                    </div>
                    <div class="flight_duration">
                      <span>{{ $booking->request_data['travelData']['stop_return_ib_duration'] }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                    </div>
                    <div class="fllight_time_date">
                       <h5>{{ $booking->request_data['travelData']['stop_return_ib_to_loc'] }}</h5>
                       <div class="time_flts">{{ $booking->request_data['travelData']['stop_return_ib_arr_time'] }}</div>
                    </div>
                 </div> 
              </div>  
              @endif
              <div class="view_booking_data pull-right">
                @if(empty($booking->change_request_id))
                <a href="javascript:void(0);" class="btn btn-danger btn_view_booking cancel-book-btn" data-toggle="modal" data-target="#flightibCancelBookingModal_{{ $f_key }}">Cancel Booking</a>
                @else
                <a href="javascript:void(0);" class="view-refund-flight" data-requestid="{{ $booking->change_request_id }}" data-tokenid="{{ $booking->token_id }}">(Click to view Refund Status)</a>
                @endif
                <a href="/user/booking/flight/{{$booking->id}}/false" class="btn btn-primary btn_view_booking" >View Details</a>
                <a href="/invoice/flights/{{ $booking->id }}" class="download_invoice_btn"><i class="fa fa-arrow-down" aria-hidden="true"></i> Download Invoice</a>
              </div>

              <div id="flightibCancelBookingModal_{{ $f_key }}" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                  <!-- Modal content-->
                  <form action="{{ route('cancelFlightBooking') }}" method="POST">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Cancel Booking - <strong>{{$booking->booking_id}}</strong></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>

                      <div class="modal-body">
                          @csrf
                          <div class="form-group">
                            <!-- <label>Reason</label> -->
                            <textarea name="remarks" class="form-control" placeholder="Enter reason to cancel this booking" required></textarea>
                          </div>
                          <input type="hidden" name="Destination" value="{{ $booking->request_data['travelData']['city_code_arrival'] }}">
                          <input type="hidden" name="Origin" value="{{ $booking->request_data['travelData']['city_code_departure'] }}">
                          <input type="hidden" name="TokenId" value="{{ $booking->token_id }}">
                          <input type="hidden" name="BookingId" value="{{ $booking->booking_id }}">
                          <input type="hidden" name="TicketId" value="<?php echo serialize($booking->request_data['travelData']['ticket_id']); ?>">
                      </div>
                      <div class="modal-footer">
                        <input type="submit" name="submit" class="btn btn-primary" value="Cancel Booking">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </form>

                </div>
              </div> 

              <div id="flightibBookingDetailsModal_{{ $f_key }}" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Flight Booking - <strong>{{$booking->booking_id}}</strong></h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                      <ul class="list-inline">
                        <li>Booking ID - <strong>{{$booking->booking_id}}</strong></li>
                        
                        <li>Total Paid - <strong>{{$booking->request_data['bookingData']['PreferredCurrency']}}
                        
                        @if(isset($booking->request_data['travelData']['extra_baggage_meal_price']))

                        {{ round( $booking->request_data['travelData']['amount'] , 2 ) + round( $booking->request_data['travelData']['extra_baggage_meal_price'] , 2 ) }}
                        @else
                        {{ round( $booking->request_data['travelData']['amount'] , 2 )  }}
                        @endif
                        
                      </strong></li>
                      </ul>
                      <br>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
              </div> 
            </div>
            @endif
            @if(sizeof($flight_bookings) < 1)
            <h3>You don't have any bookings yet, <a href="/">Click here</a> to book flight now.</h3>
            @endif
           @endforeach
        </div>
        <!-- Cab Bookings -->
        <div class="tab-pane fade show" id="nav-cabinfo" role="tabpanel" aria-labelledby="nav-cabinfo">
           @foreach($cab_bookings as $c_key => $cbooking)
             @if($cbooking->type == 'cab')
              @if(sizeof($cab_bookings) > 0)
                <div class="row air_list_data air_par_sec_data">
                    <div class="col-md-6">
                     <div class="listing_description_data">
                        <div class="row" >
                           <div class="col-md-4">
                              <p >
                                 <!-- <i class="fa fa-car"></i> -->
                                 <img src="/images/default_cab.jpg" alt="/images/default_cab.jpg" style="height:60px;">
                                 <span class="cab-name" ng-bind="cab.TransferName"></span>
                              
                              &nbsp;
                              {{ $cbooking->request_data['pickup_date'] }} {{ $cbooking->request_data['pickup_time'] }}
                              </p>
                           </div>
                           <div class="col-md-8">
                              <p>
                              <span>{{ $cbooking->request_data['pickup_detailname'] }} </span>
                              @if($cbooking->request_data['pickup_name'] == 'Airport')
                              (<i class="fa fa-plane"></i>)
                              @endif
                              @if($cbooking->request_data['pickup_name'] == 'Port')
                              <i class="fa fa-ship"></i>)
                              @endif
                              @if($cbooking->request_data['pickup_name'] == 'Station')
                              <i class="fa fa-train"></i>)
                              @endif
                              &nbsp;
                              <i class="fa fa-long-arrow-right"></i>&nbsp;
                              <span>{{ $cbooking->request_data['dropoff_detailname'] }}</span>
                              @if($cbooking->request_data['dropoff_name'] == 'Airport')
                              (<i class="fa fa-plane"></i>)
                              @endif
                              @if($cbooking->request_data['dropoff_name'] == 'Port')
                              <i class="fa fa-ship"></i>)
                              @endif
                              @if($cbooking->request_data['dropoff_name'] == 'Station')
                              <i class="fa fa-train"></i>)
                              @endif
                              </p>
                           </div>
                        </div>                        
                     </div>
                    </div>
                    <div class="col-md-2">
                        <div class="cabs_listing_rice_info">
                              {{ $cbooking->request_data['currency_code'] }} {{ round( $cbooking->total_price , 2) }}
                        </div>
                    </div>
                     <?php $policyCount = sizeof($cbooking->cancellation_policy); 
                      $lastCancelDate = date('Y-m-d h:i:s', strtotime($cbooking->cancellation_policy[$policyCount - 1]['ToDate']));                      
                      $todayDate = date('Y-m-d h:i:s');
                      ?>
                      <div class="col-md-4">
                        @if($todayDate < $lastCancelDate && $cbooking->status  == 'Booked')
                          <div class="view_booking_data pull-right">
                            <a href="javascript:void(0);" class="btn btn-danger btn_view_booking cancel-book-btn" data-toggle="modal" data-target="#cabCancelBookingModal_{{ $c_key }}">Cancel Booking</a>
                            <br><br>
                            <a href="/user/booking/cab/{{ $cbooking->id }}/false" class="btn btn-primary btn_view_booking" >View Details</a>
                          </div>
                        @else
                          <div class="view_booking_data pull-right">
                            <a href="/user/booking/cab/{{ $cbooking->id }}/false" class="btn btn-primary btn_view_booking" >View Details</a>
                          </div>
                        @endif

                        <div id="cabCancelBookingModal_{{ $c_key }}" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <form action="{{ route('cancelCabBooking') }}" method="POST"> 
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Cancel Booking - <strong>{{$cbooking->booking_id}}</strong></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <div class="modal-body">
                                <p><strong>Cancellation Policy:&nbsp;</strong>You will be charged <?php if( $cbooking->cancellation_policy[0]['ChargeType'] == 1){ echo $cbooking->cancellation_policy[0]['Charge']." Amount"; }else{ echo $cbooking->cancellation_policy[0]['ChargeType']." % of booking amount."; } ?> from {{ $cbooking->cancellation_policy[0]['FromDate']  }} to {{ $cbooking->cancellation_policy[0]['ToDate']  }}</p>
                                <br>
                                  @csrf
                                  <div class="form-group">
                                    <!-- <label>Reason</label> -->
                                    <textarea name="remarks" class="form-control" placeholder="Enter reason to cancel this booking" required></textarea>
                                  </div>
                                  <input type="hidden" name="token_id" value="{{ $cbooking->token_id }}">
                                  <input type="hidden" name="booking_id" value="{{ $cbooking->booking_id }}">
                              </div>
                              <div class="modal-footer">
                                <input type="submit" name="submit" class="btn btn-primary" value="Cancel Booking">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>

                            </form>
                          </div>
                        </div>

                      </div>
                      @if($cbooking->change_request_id)
                      <a href="javascript:void(0);" class="view-refund-cab" data-requestid="{{ $cbooking->change_request_id }}" data-tokenid="{{ $cbooking->token_id }}">(Click to view Refund Status)</a>
                      @endif
                      <a href="/invoice/cabs/{{ $cbooking->id }}" class="download_invoice_btn"><i class="fa fa-arrow-down" aria-hidden="true"></i> Download Invoice</a>
                  </div>
              @endif  
            @endif
           @endforeach
        </div>      
        <!-- Ends Here -->

        <!-- Activities Bookings -->
        <div class="tab-pane fade show" id="nav-activitiesinfo" role="tabpanel" aria-labelledby="nav-activitiesinfo">
           @foreach($activity_bookings as $a_key => $abooking)
             @if($abooking->type == 'activity')
              @if(sizeof($activity_bookings) > 0)

              <?php //echo "<pre>";print_r($abooking);echo "</pre>"; ?>
                <div class="row air_list_data air_par_sec_data">
                    <div class="col-md-6">
                     <div class="listing_description_data">
                        <div class="row" >
                           <div class="col-md-6">
                              <p >
                                 <!-- <i class="fa fa-car"></i> -->
                                 <img src="{{ $abooking->request_data['TourImage'] }}" alt="{{ $abooking->request_data['TourImage'] }}" style="height:60px;">
                                 
                              &nbsp;
                              {{ date('l, F jS, Y', strtotime(str_replace('/' , '-', $abooking->request_data['from_date']) )) }}
                              </p>
                           </div>
                           <div class="col-md-6">
                              <p>SightSeeing Name:</p>
                              <h3>{{ $abooking->request_data['SightseeingName'] }}</h3>
                              <p>SightSeeing Code:</p>
                              <h3>{{ $abooking->request_data['SightseeingCode'] }}</h3>
                           </div>
                        </div>                        
                     </div>
                    </div>
                    <div class="col-md-2">
                        <div class="cabs_listing_rice_info">
                              {{ $abooking->request_data['currency'] }} {{ $abooking->request_data['amount'] }}
                        </div>
                    </div>
                     <?php //$policyCount = sizeof($cbooking->cancellation_policy); 
                      $lastCancelDate = date('Y-m-d h:i:s', strtotime($abooking['last_cancellation_date']));                      
                      $todayDate = date('Y-m-d h:i:s');
                      ?>
                      <div class="col-md-4">
                        @if($todayDate < $lastCancelDate && $abooking->status  == 'Booked' && $abooking->change_request_id == '')
                          <div class="view_booking_data pull-right">
                            <a href="javascript:void(0);" class="btn btn-danger btn_view_booking cancel-book-btn" data-toggle="modal" data-target="#actCancelBookingModal_{{ $a_key }}">Cancel Booking</a>
                            <br><br>
                            <a href="/user/booking/activity/{{ $abooking->id }}/false" class="btn btn-primary btn_view_booking" >View Details</a>
                          </div>
                        @else
                          <div class="view_booking_data pull-right">
                            <a href="/user/booking/activity/{{ $abooking->id }}/false" class="btn btn-primary btn_view_booking" >View Details</a>
                          </div>
                        @endif

                        <div id="actCancelBookingModal_{{ $a_key }}" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <form action="{{ route('cancelActBooking') }}" method="POST">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Cancel Booking - <strong>{{$abooking->booking_id}}</strong></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <div class="modal-body">
                                <p><strong>Cancellation Policy:&nbsp;</strong> {{ $abooking['cancellation_policy'] }} </p>
                                <br>
                                  @csrf
                                  <div class="form-group">
                                    <!-- <label>Reason</label> -->
                                    <textarea name="remarks" class="form-control" placeholder="Enter reason to cancel this booking" required></textarea>
                                  </div>
                                  <input type="hidden" name="token_id" value="{{ $abooking->token_id }}">
                                  <input type="hidden" name="trace_id" value="{{ $abooking->trace_id }}">
                                  <input type="hidden" name="booking_id" value="{{ $abooking->booking_id }}">
                              </div>
                              <div class="modal-footer">
                                  <input type="submit" name="submit" class="btn btn-primary" value="Cancel Booking">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                            </form>

                          </div>
                        </div>

                      </div>
                      <a href="/invoice/activities/{{ $abooking->id }}" class="download_invoice_btn"><i class="fa fa-arrow-down" aria-hidden="true"></i> Download Invoice</a>
                      @if($abooking->change_request_id)
                        <a href="javascript:void(0);" class="view-refund-act" data-requestid="{{ $abooking->change_request_id }}" data-tokenid="{{ $abooking->token_id }}">(Click to view Refund Status)</a>
                      @endif
                  </div>
              @endif  
            @endif
           @endforeach
        </div>      
        <!-- Ends Here -->

      </div>
    </div>
  </div>
</section>
   
@endsection 