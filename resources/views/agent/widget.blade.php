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
                                <li class="breadcrumb-item"><a href="/agent/widget">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">HTML Widget
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Basic Textarea start -->
            <section class="basic-textarea">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">HTML Widget</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="widget-text"></label>
                                            <textarea class="form-control" rows="10" placeholder="Textarea" id="widget-text" readonly><form method="GET" name="searchForm" action="{{url('/')}}/findhotels"><div> <img src="https://tripheist.com/images/logo.png" width="150"><div> <label>City/Area Name</label><div> <input id="autocomplete" name="Location" placeholder="Enter City , Hotel , Address" onFocus="geolocate()" type="text" style="color: #333; border: 1px solid #ccc; border-radius: 5px; padding: 8px; font-size: 14px;" required /></div> <input type="hidden" name="Latitude" id="Latitude" value="" /> <input type="hidden" name="Longitude" id="Longitude" value="" /> <input type="hidden" name="Radius" id="Radius" value="15" /> <input type="hidden" name="city_id" id="city_id" value="" /> <input type="hidden" name="city_name" id="city_name" value="" /> <input type="hidden" name="countryCode" id="country_code" value="" /> <input type="hidden" name="countryName" id="country_name" value="" /> <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="" /> <input type="hidden" name="ishalal" value="0" /> <input type="hidden" name="a1" value="2" /> <input type="hidden" name="c1" value="0" /> <input type="hidden" name="roomCount" value="1" /> <input type="hidden" name="referral" class="referral" value="{{$agent->referal_code}}" /> <input type="hidden" name="roomsGuests" value="1 Room 2 Guest" /></div><div> <label>Check in </label><div class="input-group"><input type="date" id="d" name="departdate" required value="" style="color: #333; border: 1px solid #ccc; border-radius: 5px; padding: 8px; font-size: 14px;" /></div></div><div> <label>Check out </label><div><input type="date" id="r" name="returndate" required value="" style="color: #333; border: 1px solid #ccc; border-radius: 5px; padding: 8px; font-size: 14px;" /></div></div><div><button type="submit" class="btn btn-primary" style="color: #333; border: 1px solid #ccc; border-radius: 5px; padding: 8px; font-size: 14px;">Search</button></div></div></form><script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlmbzdiJw-ZZbwwWimGpZk96wQg77emoY&callback=initAutocomplete&libraries=places&v=weeklysensor=false&language=en"defer ></script><script>let placeSearch,autocomplete;const componentForm={street_number:"short_name",route:"long_name",locality:"long_name",administrative_area_level_1:"short_name",country:"short_name",postal_code:"short_name"};function initAutocomplete(){(autocomplete=new google.maps.places.Autocomplete(document.getElementById("autocomplete"))).setFields(["address_component","geometry","name"]),autocomplete.addListener("place_changed",fillInAddress)}function fillInAddress(){const e=autocomplete.getPlace();if(e.geometry){var t=e.geometry.location.lat(),o=e.geometry.location.lng();document.getElementById("Latitude").value=t,document.getElementById("Longitude").value=o,displayLocation(t,o)}else alert("Invalid Location");document.getElementById("autocomplete").value=e.name,document.getElementById("city_name").value=e.name;for(const t of e.address_components){const o=t.types[0];if(componentForm[o]&&"country"==o){const n=t[componentForm[o]];document.getElementById("country_code").value=n;const a=t.long_name;document.getElementById("country_name").value=a,-1!==document.getElementById("city_name").value.indexOf(e.name)&&(document.getElementById("city_name").value=a)}}}function geolocate(){navigator.geolocation&&navigator.geolocation.getCurrentPosition(e=>{const t={lat:e.coords.latitude,lng:e.coords.longitude},o=new google.maps.Circle({center:t,radius:e.coords.accuracy});autocomplete.setBounds(o.getBounds())})}function displayLocation(e,t){var o=new XMLHttpRequest,n="https://maps.googleapis.com/maps/api/geocode/json?latlng="+e+","+t+"&sensor=true&key=AIzaSyDlmbzdiJw-ZZbwwWimGpZk96wQg77emoY";o.open("GET",n,!0),o.onreadystatechange=function(){if(4==o.readyState&&200==o.status){var e=JSON.parse(o.responseText);e.results[1];for(const t of e.results)if(t.types&&t.types[0]&&"locality"==t.types[0])for(const e of t.address_components){const t=e.types[0];if(componentForm[t]){if("locality"==t){const t=e.long_name;document.getElementById("city_name").value=t}if("country"==t){const t=e.long_name;document.getElementById("country_name").value=t}}}}},o.send()}var today=new Date,d=today.getDate(),m=today.getMonth()+1,y=today.getFullYear(),tom=new Date;tom.setDate(tom.getDate()+1),dt=tom.getDate(),mt=tom.getMonth()+1,yt=tom.getFullYear(),d<10&&(d="0"+d),m<10&&(m="0"+m),today=y+"-"+m+"-"+d,dt<10&&(dt="0"+dt),mt<10&&(mt="0"+mt),tom=yt+"-"+mt+"-"+dt,document.getElementById("d").setAttribute("min",today),document.getElementById("r").setAttribute("min",tom),document.getElementById("d").value=today,document.getElementById("r").value=tom;</script></textarea>
                                        </div>
                                        <a class="card-text copy-link" id="widget-text-tag" onclick="copyToClip('widget-text')">Click here to copy the search widget code.<i data-feather='copy'></i></a>
                                        <input type="hidden" id="affiliate-link" value="{{ env('REFFERAL_URL') }}/referral/{{$agent->referal_code}}">
                                        <br>
                                        <a class="card-text copy-link" id="affiliate-link-tag" onclick="copyToClip('affiliate-link')">Click here to copy the affiliate link.<i data-feather='copy'></i></a>
                                    </div>
                                    <div class="col-6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Basic Textarea end -->

        </div>
    </div>
</div>
@endsection