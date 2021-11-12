@extends('layouts.app-header')
@section('content')   

    <section class="thankyou_message_sec">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            
            <div class="thenkyou_message_quote">
              <p><span>Booking Confirmed &nbsp;</span><i class="fa fa-check-circle" aria-hidden="true"></i>You booking has been completed, please find the booking details below.</p>
            </div>


	            <div class="thankyou_flight_info">
            	<div class="heading-top">
		            <h2>Hotel Booking - {{ date('l, F jS, Y', strtotime(str_replace('/' , '-', $h_booking['request_data']['checkInDate']) )) }} - {{ $h_booking['request_data']['hotelName'] }}</h2>
	          	</div>
	              <div class="row">
	                <div class="col-md-4">
	                  <div class="hotel_flight_img">
	                  	@if(isset($hotel_data) && isset($hotel_data['hotel_images']) && isset($hotel_data['hotel_images'][0]))
	                  		@if(strpos($hotel_data['hotel_images'][0], 'http') !== false || strpos($hotel_data['hotel_images'][0], 'www') !== false)
	                  			<img class="img-fluid" src="{{ $hotel_data['hotel_images'][0] }}" alt="room">
	                  		@else
	                  			<img class="img-fluid" src="{{env('AWS_BUCKET_URL')}}/{{ $hotel_data['hotel_images'][0] }}" alt="room">
                  			@endif
	                  	@else
	                    	<img class="img-fluid" src="/images/room_type_imga.webp" alt="room">
	                  	@endif
	                  </div>
	                </div>
	                <div class="col-md-8">
	                  <div class="hotel_address_description">
	                    <div class="h_address">
	                      <p>Hotel Name</p>
	                      <h3>{{ $h_booking['request_data']['hotelName'] }}</h3>
	                    </div>
	                    <div class="h_address">
	                  		<p>Hotel Address</p>
	                    	@if(isset($hotel_data) && isset($hotel_data['hotel_address']))
	                    		<h3>{{ $hotel_data['hotel_address']['AddressLine'][0] }}</h3>
	                  		@else
	                      		<h3>{{ $h_booking['request_data']['country'] }}</h3>
	                  		@endif
	                    </div>
	                    <div class="h_address">
	                      <p>Check In</p>
	                      <h3>{{ date('l, F jS, Y', strtotime($h_booking['request_data']['checkInDate'])) }}</h3>
	                    </div>
	                    <div class="h_address">
	                      <p>Check Out</p>
	                      <h3>{{ date('l, F jS, Y', strtotime($h_booking['request_data']['checkOutDate'])) }}</h3>
	                    </div>
	                    <div class="h_address">
	                      <p>Guests</p>
	                      <h3>
	                      	@foreach($h_booking['request_data']['bookingData']['HotelPassenger'] as $k => $p)
	                      		@if($k <= 1)
		                      		@foreach($p as $guest)
		                      			{{ $guest['FirstName'] }} {{ $guest['LastName'] }}
		                      		@endforeach
	                      		@endif
	                      	@endforeach
	                      		@if(sizeof($h_booking['request_data']['bookingData']['HotelPassenger']) > 1)
	                      			+ {{ sizeof($h_booking['request_data']['bookingData']['HotelPassenger']) - 1 }}
	                      		@endif
	                      </h3>
	                    </div>
	                    <div class="h_address">
	                      <p>Room Type</p>
	                      <h3> {{ $h_booking['request_data']['bookingData'][0]['RoomTypeName'] }}</h3>
	                    </div>
	                    <div class="h_address">
	                      <p>Total paid</p>
	                      <h3>{{ $h_booking['request_data']['bookingData'][0]['Price']['CurrencyCode'] }}{{round( $h_booking['request_data']['amount'],2) }}</h3>
	                    </div>
	                    <div class="h_address">
	                      <p>Room Facilities</p>
	                      <h3>
	                      	@foreach($h_booking['request_data']['bookingData'][0]['Amenities'] as $am)
	                      	 {{ $am }}&nbsp;
	                      	@endforeach
	                      </h3>
	                    </div>
	                  </div>
	                </div>
	              </div>
	            </div>

	            <div class="cancel_policies">
	              <h2>Cancellation Policy - {{ $h_booking['request_data']['hotelName'] }}</h2>
	              <div class="row">
	              	<div class="col-md-8">
		              <div class="policy_info_data">
	                  	<p>{{ str_replace(array('#^#', '#', '|', '#!#', '!'), array(' ', ' ', ' ', ' ', ' '),$h_booking['request_data']['bookingData'][0]['CancellationPolicy']) }}</p>
		              </div>
		          </div>
		          <div class="col-md-4">
		          	<div id="map" ng-show="loaded"></div>
		          	<script type="text/javascript">
		          		let map;
							let mapCenter = { lat: parseFloat("{{$hotel_data['hotel_location']['@Latitude']}}"), lng: parseFloat("{{$hotel_data['hotel_location']['@Longitude']}}") };
							// if(locations && locations.length) {
							// 	mapCenter = {lat: locations[0][1], lng: locations[0][2]};
							// }
							// console.log(mapCenter);
						    map = new google.maps.Map(document.getElementById("map"), {
						        center: mapCenter,
						        zoom: 12
						    });

						    var infowindow = new google.maps.InfoWindow();

						    var marker, i;

						   // for (i = 0; i < locations.length; i++) {  
						      	marker = new google.maps.Marker({
						        	position: new google.maps.LatLng(parseFloat("{{$hotel_data['hotel_location']['@Latitude']}}"), parseFloat("{{$hotel_data['hotel_location']['@Longitude']}}")),
						        	map: map
						      	});

						      	google.maps.event.addListener(marker, 'click', (function(marker, i) {
						        	return function() {
						        		let hotelDetails = 
						        			"<div class='hotel-details-map'>" +
						        			"<a target='_blank' href='javascript:void(0);'><p><img src='{{ $hotel_data['hotel_images'][0] }}'></p>" +
						        			"<h4 class='hotel-name-map text-center'>{{ $h_booking['request_data']['hotelName'] }}</h4></a>" +
						        			"</div>";

						          		infowindow.setContent(hotelDetails);
						          		infowindow.open(map, marker);
						        	}
						      	})(marker, i));
						   // }
		          	</script>
		          </div>
		          </div>
	            </div>

            
            	<div class="thankyou_flight_info">
            		<div class="heading-top">
			            <h2>Flight Booking - {{ date('l, F jS, Y', strtotime(str_replace('/' , '-', $f_booking['request_data']['travelData']['departure_date_arr']) )) }}</h2>
		          	</div>
	              <div class="row">
	                <div class="col-md-3">
	                  <div class="hotel_flight_img text-center">
                  		<img class="img-fluid" src="/images/{{ $f_booking['request_data']['travelData']['flight_jetname_ob'] }}.gif" width="150" alt="flight" onerror="this.onerror=null;this.src='/images/blog_01.png';">
	                  </div>
	                </div>
	                <div class="col-md-9">
	                  <div class="hotel_address_description">
	                    <div class="h_address col-md-4">
	                      <p>Airline</p>
	                      <h3>{{ $f_booking['request_data']['travelData']['flight_name_ob'] }}</h3>
	                    </div>
	                    <div class="h_address col-md-4">
	                      <p>Departure Date</p>
	                      <h3>{{ date('l, F jS, Y', strtotime(str_replace('/' , '-', $f_booking['request_data']['travelData']['departure_date_arr']) )) }}</h3>
	                    </div>
	                    
	                    <div class="h_address col-md-4">
	                      <p>Departure Time</p>
	                      <h3>{{ date('H:i', strtotime(str_replace('/' , '-', $f_booking['request_data']['travelData']['departure_date_arr']) )) }}</h3>
	                    </div>
	                    @if($f_booking['booking_ref'] != '')
	                    <div class="h_address col-md-4">
	                      <p>From Location</p>
	                      <?php if( isset($f_booking['request_data']['travelData']['stop2_to_loc']) && $f_booking['request_data']['travelData']['stop2_to_loc'] != '' ){ ?>
	                      	<h3>{{ $f_booking['request_data']['travelData']['stop2_to_loc'] }}</h3>
	                      <?php }else if( isset($f_booking['request_data']['travelData']['stop_to_loc']) && $f_booking['request_data']['travelData']['stop_to_loc'] != '' ){ ?>
	                        <h3>{{ $f_booking['request_data']['travelData']['stop_to_loc'] }}</h3>
	                     <?php  }else{?>
	                      	<h3>{{ $f_booking['request_data']['travelData']['to_loc'] }}</h3>
	                      <?php }?>
	                    </div>
	                    <div class="h_address col-md-4">
	                      <p>To Location</p>
	                      <h3>{{ $f_booking['request_data']['travelData']['from_loc'] }} </h3>
	                    </div>
	                	@else
	                    <div class="h_address col-md-4">
	                      <p>From Location</p>
	                      <h3>{{ $f_booking['request_data']['travelData']['from_loc'] }} </h3>
	                    </div>
	                    <div class="h_address col-md-4">
	                      <p>To Location</p>
	                      <?php if( isset($f_booking['request_data']['travelData']['stop2_to_loc']) && $f_booking['request_data']['travelData']['stop2_to_loc'] != '' ){ ?>
	                      	<h3>{{ $f_booking['request_data']['travelData']['stop2_to_loc'] }}</h3>
	                      <?php }else if( isset($f_booking['request_data']['travelData']['stop_to_loc']) && $f_booking['request_data']['travelData']['stop_to_loc'] != '' ){ ?>
	                        <h3>{{ $f_booking['request_data']['travelData']['stop_to_loc'] }}</h3>
	                     <?php  }else{?>
	                      	<h3>{{ $f_booking['request_data']['travelData']['to_loc'] }}</h3>
	                      <?php }?>
	                    </div>
	                    @endif
	                    <div class="h_address col-md-4">
	                      <p>Flight Duration</p>
	                      <h3>{{ $f_booking['request_data']['travelData']['duration'] }}</h3>
	                    </div>
	                    @foreach($f_booking['request_data']['bookingData']['Passengers'] as $keyps => $value)
	                    <div class="h_address col-md-4">
	                      <p>Passenger {{ $keyps + 1 }} </p>
	                      <h3>
	                      	{{ $value['FirstName'] }}&nbsp;{{ $value['LastName'] }}
	                      </h3>
	                    </div>

	                    <!-- For NOn Lcc Meal  -->

	                    @if(isset($f_booking['request_data']['bookingData']['Passengers'][$keyps]['Meal']))
	                    <div class="h_address col-md-4">
	                      <p>Meal</p>
	                      <h3>
	                      	{{ $f_booking['request_data']['bookingData']['Passengers'][$keyps]['Meal']['Description'] }}
	                      </h3>
	                    </div>
	                    @endif
	                    @if(isset($f_booking['request_data']['bookingData']['Passengers'][$keyps]['Seat']))
	                    <div class="h_address col-md-4">
	                      <p>Seat</p>
	                      <h3>
	                      	{{ $f_booking['request_data']['bookingData']['Passengers'][$keyps]['Seat']['Description'] }}
	                      </h3>
	                    </div>
	                    @endif

	                    <!-- Ends -->

	                    <!-- For Lcc Meal  -->

	                    @if(isset($f_booking['request_data']['bookingData']['Passengers'][$keyps]['MealDynamic']))
		                    @foreach($f_booking['request_data']['bookingData']['Passengers'][$keyps]['MealDynamic'] as $keypmD => $value)
		                    <div class="h_address col-md-4">
		                      <p>Passenger {{ $keyps + 1 }} Meal ({{ $value['Origin'] }} - {{ $value['Destination'] }}) </p>
		                      <h3>
		                      	{{ $value['AirlineDescription'] }} - {{ $value['Currency'] }} {{ $value['Price'] }}
		                      </h3>
		                    </div>
		                    @endforeach
	                    @endif
	                    @if(isset($f_booking['request_data']['bookingData']['Passengers'][$keyps]['Baggage']))
		                    @foreach($f_booking['request_data']['bookingData']['Passengers'][$keyps]['Baggage'] as $keypmD => $value)
		                    <div class="h_address col-md-4">
		                      <p>Passenger {{ $keyps + 1 }} Baggage ({{ $value['Origin'] }} - {{ $value['Destination'] }}) </p>
		                      <h3>
		                      	{{ $value['Weight'] }} Kg - {{ $value['Currency'] }} {{ $value['Price'] }}
		                      </h3>
		                    </div>
		                    @endforeach
	                    @endif

	                    <!-- Ends -->

	                    @endforeach
	                   
	                    <div class="h_address col-md-4">
	                      <p>Total Paid</p>
	                      <h3>
	                      	@if(isset($f_payment['price']))
	                      	 {{ $f_booking['request_data']['bookingData']['PreferredCurrency'] }} {{round($f_payment['price'],2) }}
	                      	@else
	                      	{{ $f_booking['request_data']['bookingData']['PreferredCurrency'] }} {{ round( $f_booking['request_data']['travelData']['amount'],2 )  +  round($f_booking['request_data']['travelData']['extra_baggage_meal_price'],2) }}
	                      	@endif
	                      </h3>
	                    </div>
	                  </div>
	                </div>
	              </div>
	            </div>
	            	@if(isset($f_booking['cancellation_policy']))
		            <div class="cancel_policies">
		              <h2>Cancellation Policy</h2>
	                  <div class="policy_info_data">
	                  	<?php $cancel = json_decode($f_booking['cancellation_policy'], true)[0]; //echo "<pre>"; print_r($cancel); echo "</pre>"; die(); ?>
	                  	@if(isset($cancel))
	                  	<p>You will be charged <?php if( $cancel['ChargeType'] == 1){ echo $f_booking['request_data']['selected_cab']['TransferPrice']['CurrencyCode'] . " " . round($cancel['Charge'],2)." "; }else{ echo $cancel['ChargeType']." % of booking amount."; } ?> if cancelled between {{ date('l, F jS, Y', strtotime($cancel['FromDate']))  }} to {{ date('l, F jS, Y', strtotime($cancel['ToDate']))  }}.</p>
	                  	@endif
		              </div>
		          	</div>
		          	@endif
	            </div>


          </div>
        </div>
      </div>     
    </section>
@endsection
   <style type="text/css">

    section.thankyou_message_sec {
        background-color: #4fbbff;
        background-image: linear-gradient(#4fbbff, #74c9ff);
        padding: 50px 0px;
        color: #fff;
        /*margin-bottom: 50px;*/
    }
    .thenkyou_message_quote {
        background: #fff;
        border-radius: 5px;
        max-width: 700px;
        margin: 0 auto;
        padding: 30px 20px;
        box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.2);
    }
    .thenkyou_message_quote p {
        margin: 0;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: 600;
        text-align: center;
    }
    .thenkyou_message_quote p i.fa {
        display: block;
        font-size: 50px;
        margin-bottom: 10px;
        color: #43aff2;
    }
    .hotel_flight_img {
        /*box-shadow: 5px 5px 0px 0px rgba(255,255,255,1);*/
        position: relative;
    }
    .hotel_flight_img img {
        position: relative;
        z-index: 1;
    }
    .hotel_flight_img::after {
        content: "";
        position: absolute;
        top: 12px;
        left: 12px;
        width: 100%;
        height: 100%;
        background: #f5f5f5;
    }
    .hotel_address_description {
        display: flex;
        flex-wrap: wrap;
        margin-top: 2px;
        padding-left: 10px;
    }
    .h_address h3 {
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .h_address p {
        margin: 0px;
        font-size: 14px;
    }
    .h_address {
        width: 33.33%;
        padding: 17px 15px;
        margin-bottom: -1px;
        border: 1px dashed #ddd;
        margin-left: -1px;
        color: #333;
    }
    .heading-top {
    	margin-top: 50px;
        background: #fff;
        border-radius: 5px;
        margin: 0px auto 0px;
        padding: 0 20px;
        text-align: center;
    }
    .heading-top h2 {
        text-transform: uppercase;
        font-weight: 600;
        font-size: 20px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 17px;
        margin-bottom: 22px;
        color: #333;
    }
    .cancel_policies {
        margin-top: 50px;
        background: #fff;
        border-radius: 5px;
        margin: 0px auto 0px;
        padding: 20px 20px;
        box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.2);
    }
    .cancel_policies h2 {
        text-transform: uppercase;
        font-weight: 600;
        font-size: 20px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 17px;
        margin-bottom: 22px;
        color: #333;
    }
    .policy_info_data ul {
        margin: 15px 0px 0px;
    }
    .policy_info_data li {
        font-size: 14px;
        margin-bottom: 7px;
        color: #333;
    }
    .policy_info_data li:last-child {
      margin-bottom: 0px;
    }
    .thankyou_flight_info {
        background: #fff;
        border-radius: 5px;
        margin: 20px 0px;
        padding: 20px 20px;
        box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.2);
    }

    @media (max-width: 767px) {

      .hotel_address_description {
          margin-top: 32px;
          padding-left: 0;
      }
      .h_address {
          width: 50%;
          padding: 15px;
      }
      .h_address h3 {
          font-size: 14px;
      }
      .h_address p {
        font-size: 14px;
        line-height: 20px;
    }


    }


     

   </style>
