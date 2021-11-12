@extends('layouts.app-header')

@section('content')

@if(isset($agent))
<section class="booking_lists_data profile_data_info">
  <div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="profile_picture_data">
                <div class="row">
                    <div class="col-md-3 pr-0">
                        <img src="/uploads/profiles/{{ $user->picture }}" class="profile-pic" onerror="this.onerror=null;this.src='https://via.placeholder.com/150?text=No Image';">
                    </div>
                    <div class="col-md-9 pl-0">
                        <h4>{{ $user->name }} <!-- <img src="https://www.countryflags.io/{{ $country_name['code'] }}/flat/32.png" class="flag-pic"> --></h4>
                        @if(isset($country_name))
                        <p>
                            {{ $country_name['name'] }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="text-left code copy_code_data">
                <a class="font-bold" href="{{ env('REFFERAL_URL') }}/referral/{{$agent->referal_code}}" target="_blank">{{ env('REFFERAL_URL') }}/referral/{{$agent->referal_code}}</a>
                
                <input type="hidden" id="ref_link" value="{{ env('REFFERAL_URL') }}/referral/{{$agent->referal_code}}">
                
                <!-- <a href="javascript:void(0);" class="copy-link" id="ref_link-tag" onclick="copyToClip('ref_link')">Click here to copy the link.</a>
                <br> -->
                <br>
                <a href="javascript:void(0);" class="copy-link" id="widget-text-tag" onclick="copyToClip('widget-text')">Click here to copy the search widget code.</a>
                <!-- <br> -->
                <textarea class="widget-text" id="widget-text" readonly style="display: none;"><form method="GET" name="searchForm" action="{{url('/')}}/findhotels"><div> <img src="https://tripheist.com/images/logo.png" width="150"><div> <label>City/Area Name</label><div> <input id="autocomplete" name="Location" placeholder="Enter City , Hotel , Address" onFocus="geolocate()" type="text" style="color: #333; border: 1px solid #ccc; border-radius: 5px; padding: 8px; font-size: 14px;" required /></div> <input type="hidden" name="Latitude" id="Latitude" value="" /> <input type="hidden" name="Longitude" id="Longitude" value="" /> <input type="hidden" name="Radius" id="Radius" value="15" /> <input type="hidden" name="city_id" id="city_id" value="" /> <input type="hidden" name="city_name" id="city_name" value="" /> <input type="hidden" name="countryCode" id="country_code" value="" /> <input type="hidden" name="countryName" id="country_name" value="" /> <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="" /> <input type="hidden" name="ishalal" value="0" /> <input type="hidden" name="a1" value="2" /> <input type="hidden" name="c1" value="0" /> <input type="hidden" name="roomCount" value="1" /> <input type="hidden" name="referral" class="referral" value="{{$agent->referal_code}}" /> <input type="hidden" name="roomsGuests" value="1 Room 2 Guest" /></div><div> <label>Check in </label><div class="input-group"><input type="date" id="d" name="departdate" required value="" style="color: #333; border: 1px solid #ccc; border-radius: 5px; padding: 8px; font-size: 14px;" /></div></div><div> <label>Check out </label><div><input type="date" id="r" name="returndate" required value="" style="color: #333; border: 1px solid #ccc; border-radius: 5px; padding: 8px; font-size: 14px;" /></div></div><div><button type="submit" class="btn btn-primary" style="color: #333; border: 1px solid #ccc; border-radius: 5px; padding: 8px; font-size: 14px;">Search</button></div></div></form><script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlmbzdiJw-ZZbwwWimGpZk96wQg77emoY&callback=initAutocomplete&libraries=places&v=weeklysensor=false&language=en"defer ></script><script>let placeSearch,autocomplete;const componentForm={street_number:"short_name",route:"long_name",locality:"long_name",administrative_area_level_1:"short_name",country:"short_name",postal_code:"short_name"};function initAutocomplete(){(autocomplete=new google.maps.places.Autocomplete(document.getElementById("autocomplete"))).setFields(["address_component","geometry","name"]),autocomplete.addListener("place_changed",fillInAddress)}function fillInAddress(){const e=autocomplete.getPlace();if(e.geometry){var t=e.geometry.location.lat(),o=e.geometry.location.lng();document.getElementById("Latitude").value=t,document.getElementById("Longitude").value=o,displayLocation(t,o)}else alert("Invalid Location");document.getElementById("autocomplete").value=e.name,document.getElementById("city_name").value=e.name;for(const t of e.address_components){const o=t.types[0];if(componentForm[o]&&"country"==o){const n=t[componentForm[o]];document.getElementById("country_code").value=n;const a=t.long_name;document.getElementById("country_name").value=a,-1!==document.getElementById("city_name").value.indexOf(e.name)&&(document.getElementById("city_name").value=a)}}}function geolocate(){navigator.geolocation&&navigator.geolocation.getCurrentPosition(e=>{const t={lat:e.coords.latitude,lng:e.coords.longitude},o=new google.maps.Circle({center:t,radius:e.coords.accuracy});autocomplete.setBounds(o.getBounds())})}function displayLocation(e,t){var o=new XMLHttpRequest,n="https://maps.googleapis.com/maps/api/geocode/json?latlng="+e+","+t+"&sensor=true&key=AIzaSyDlmbzdiJw-ZZbwwWimGpZk96wQg77emoY";o.open("GET",n,!0),o.onreadystatechange=function(){if(4==o.readyState&&200==o.status){var e=JSON.parse(o.responseText);e.results[1];for(const t of e.results)if(t.types&&t.types[0]&&"locality"==t.types[0])for(const e of t.address_components){const t=e.types[0];if(componentForm[t]){if("locality"==t){const t=e.long_name;document.getElementById("city_name").value=t}if("country"==t){const t=e.long_name;document.getElementById("country_name").value=t}}}}},o.send()}var today=new Date,d=today.getDate(),m=today.getMonth()+1,y=today.getFullYear(),tom=new Date;tom.setDate(tom.getDate()+1),dt=tom.getDate(),mt=tom.getMonth()+1,yt=tom.getFullYear(),d<10&&(d="0"+d),m<10&&(m="0"+m),today=y+"-"+m+"-"+d,dt<10&&(dt="0"+dt),mt<10&&(mt="0"+mt),tom=yt+"-"+mt+"-"+dt,document.getElementById("d").setAttribute("min",today),document.getElementById("r").setAttribute("min",tom),document.getElementById("d").value=today,document.getElementById("r").value=tom;</script></textarea>
            </div>
        </div>
    </div>
    <div class="profile_tabs_data">
      <nav class="bookings_menu_tab ">
       <div class="nav nav-tabs nav-fill" role="tablist">
          <a class="nav-item nav-link active" id="nav-fare-tab" data-toggle="tab" href="#nav-dashboard" role="tab" aria-controls="nav-dashboard" aria-selected="true">Dashboard</a>
          <a class="nav-item nav-link" id="nav-fare-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</a>
       </div>
      </nav>
      <div class="tab_profile_info">
        <div class="tab-content">
            <div class="tab-pane fade show active table-responsive" id="nav-dashboard" role="tabpanel" aria-labelledby="nav-dashboard">
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>S No.</th>
                            <th>Date</th>
                            <th>Booking Type</th>
                            <th>Booking Status</th>
                            <th>Confimation Number</th>
                            <th>Total Paid</th>
                            <th>Commission</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1 @endphp
                        @php $earnings = 0 @endphp
                        @if(isset($bookings[0]) && isset($bookings[0]['hotels']))
                            @foreach($bookings[0]['hotels'] as $h_booking)
                                @php $r_data  = json_decode($h_booking['r_data'], true); @endphp
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ date('l, F jS, Y', strtotime($h_booking['created_at'])) }} </td>
                                    <td>Hotel</td>
                                    <td>{{ $h_booking['booking_status'] }}</td>
                                    <td>{{ $h_booking['c_number'] }}</td>
                                    <td>{{ $r_data['bookingData'][0]['Price']['CurrencyCode'] }}{{ number_format($h_booking['price'],2) }}</td>
                                    <td>USD {{ number_format($h_booking['commission'],2) }}
                                        @php $earnings = $earnings + $h_booking['commission'] @endphp
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        @if(isset($bookings[0]) && isset($bookings[0]['flights']))
                            @foreach($bookings[0]['flights'] as $h_booking)
                                @php $r_data  = json_decode($h_booking['r_data'], true); @endphp
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ date('l, F jS, Y', strtotime($h_booking['created_at'])) }}</td>
                                    <td>Flight</td>
                                    <td>{{ $h_booking['booking_status'] == '1' ? 'Confirmed' : 'Pending' }}</td>
                                    <td>{{ $h_booking['c_number'] }}</td>
                                    <td>{{ $r_data['bookingData']['PreferredCurrency'] }}{{ number_format($h_booking['price'],2) }}</td>
                                    <td>USD {{ number_format($h_booking['commission'],2) }}
                                        @php $earnings = $earnings + $h_booking['commission'] @endphp
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        @if(isset($bookings[0]) && isset($bookings[0]['cabs']))
                            @foreach($bookings[0]['cabs'] as $c_booking)
                                @php $r_data  = json_decode($c_booking['r_data'], true); @endphp
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ date('l, F jS, Y', strtotime($c_booking['created_at'])) }}</td>
                                    <td>Cab</td>
                                    <td>{{ $c_booking['booking_status'] == '1' ? 'Confirmed' : 'Pending' }}</td>
                                    <td>{{ $c_booking['c_number'] }}</td>
                                    <td>{{ $r_data['currency_code'] }}{{ number_format($c_booking['price'],2) }}</td>
                                    <td>USD {{ number_format($c_booking['commission'],2) }}
                                        @php $earnings = $earnings + $c_booking['commission'] @endphp
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        @if(isset($bookings[0]) && isset($bookings[0]['activities']))
                            @foreach($bookings[0]['activities'] as $act_booking)
                                @php $r_data  = json_decode($act_booking['r_data'], true); @endphp
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ date('l, F jS, Y', strtotime($act_booking['created_at'])) }}</td>
                                    <td>Cab</td>
                                    <td>{{ $act_booking['booking_status'] == '1' ? 'Confirmed' : 'Pending' }}</td>
                                    <td>{{ $act_booking['c_number'] }}</td>
                                    <td>{{ $r_data['currency'] }}{{ number_format($act_booking['price'],2) }}</td>
                                    <td>USD {{ number_format($act_booking['commission'],2) }}
                                        @php $earnings = $earnings + $act_booking['commission'] @endphp
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <h5 class="text-right">Total Earnings&nbsp; USD {{ number_format($earnings,2) }}</h5>
            </div>
            <div class="tab-pane fade show" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile">
               
                <form class="profile_edits_profiles" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        @if($user->social_id != '')
                            <input type="text" name="email" class="form-control" value="{{ $user->email }}" required>
                        @else
                            <input type="text" name="email" class="form-control" value="{{ $user->email }}" readonly>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <select class="form-control" name="country">
                            @foreach($cities as $city)
                                <option value="{{ $city->code }}" @if($city->code == $user->country) selected='selected' @endif>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(isset($agent))
                    <div class="form-group">
                        <label>Referral Link</label>
                        <input type="text" name="referral" class="form-control" value="{{ env('REFFERAL_URL') }}/referral/{{ $agent->referal_code }}" readonly>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="image">Profile Picture</label>
                        <input id="image" type="file" name="image">
                    </div>
                    <div class="profile_update_data">
                        <input type="submit" name="submit" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        </div>
        </div>
  </div>
</section>
@else
<section id="cover" class="min-vh-100">
    <div id="cover-caption">
        <div class="container">
            <div class="row">
            	<div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-center form ">
            		<br><br>
            		@if (session('success'))
				        <div class="alert alert-success" role="alert" >
				            {{ session('success') }}
				        </div>
				    @endif
                    @if($showEmailMessage) 
                        <div class="alert alert-success" role="alert" >
                            <p>Please update your email address.</p>
                        </div>
                    @endif
                    <h3 class="clr-blk">Update Profile</h3>
                    <div class="px-2">
                        <form action="" method="POST">
		        		@csrf
		        		<div class="form-group clr-blk">
		        			<label>Name</label>
		        			<input type="text" name="name" class="form-control" value="{{ $user->name }}">
		        		</div>
		        		<div class="form-group clr-blk">
		        			<label>Email</label>
                            @if($user->social_id != '')
                                @if(filter_var($user->email, FILTER_VALIDATE_EMAIL))
                                    <input type="text" name="email" class="form-control" value="{{ $user->email }}" required>
                                @else
                                    <input type="text" name="email" class="form-control" value="" required>
                                @endif
                            @else
		        			   <input type="text" name="email" class="form-control" value="{{ $user->email }}" readonly>
                            @endif
		        		</div>
                        <div class="form-group clr-blk">
                            <label>Country</label>
                            <select class="form-control" name="country">
                                @foreach($cities as $city)
                                    <option value="{{ $city->code }}" @if($city->code == $user->country) selected='selected' @endif>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group clr-blk">
                            <label for="image">Profile Picture</label>
                            <input id="image" type="file" name="image">
                        </div>
		        		<input type="submit" name="submit" value="Update" class="btn btn-primary">
		        	</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<style type="text/css">


/* only used for background overlay not needed for centering */
form:before {
    content: '';
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    /*background-color: rgba(0,0,0,0.3);*/
    z-index: -1;
    border-radius: 10px;
}
.clr-blk {
	color: #000;
}
</style>
@endsection 