@extends('layouts.app')

@section('content')
<section class="booking_detail_page">
      <div class="container">
        <div class="row">
        
	        <div class="card">

		        <div class="card-body">
	        		<h3 class="card-title">Booking Details</h3>  <br>
		          <p>Confirmation Number: {{ $bookingDetails->confirmation_number }}
		          </p>
		          <p>BookingId:   {{ $bookingDetails->booking_id }}</p>

		          <p>Booking Status:   {{ $bookingDetails->hotel_booking_status }}</p>

		          You can check all your bookings on <a href="/user/bookings">My Bookings</a> page.
		      	</div>
	      	</div>
        </div>
      </div>
</section>
@endsection 