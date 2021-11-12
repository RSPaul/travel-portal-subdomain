@extends('layouts.app-header')
@section('content')   
<section class="banner_data">
    <video playsinline="playsinline" id="homeVideo" autoplay="autoplay" muted="muted" loop="loop"  loop autoplay>
        <source id="mp4_src" src="" type="video/mp4">
        <source id="webm_src" src="" type="video/webm">
        <source id="ogg_src" src="" type="video/ogv">
    </video>
    <div class="container">
        <div class="row">
            <input type="hidden" id="isHome" value="1">
            <input type="hidden" id="isHomeFH" value="1">
            <input type="hidden" id="showTab" value="{{ Session::get('active_tab') }}">
            <div class="col-md-12">
                <div class="slider_test_heading">
                    <h1>{{ __('labels.hotel_landing_heading') }}</h1>
                    <p>{{ __('labels.hotel_landing_subheading') }}</p>
                </div>

                                 
                <div class="form_tab_data active" id="hotels">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" >{{ __('labels.hotel_search') }}</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="round_triping_data hotels">
                                <h4>{{ __('labels.domestic_international_hotel_booking') }}</h4>
                                <form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_rooms') }}"  >
                                    @csrf
                                    <div class="rounding_form_info">
                                        <div class="form-group">
                                            <label>{{ __('labels.cityarea') }}</label>
                                            <input id="autocomplete" name="Location"  placeholder="Enter City , Hotel , Address"  onFocus="geolocate()" type="text" style="color:#333;border:1px solid #ccc;border-radius: 5px;padding:8px;font-size:14px;width:95%;"  required/>

                                            <input type="hidden" name="Latitude" id="Latitude" value="">
                                            <input type="hidden" name="Longitude" id="Longitude" value="">
                                            <input type="hidden" name="Radius" id="Radius" value="20">
                                            <input type="hidden" name="city_id" id="city_id" value="130443">
                                            <input type="hidden" name="city_name" id="city_name" value="Delhi">
                                            <input type="hidden" name="countryCode" id="country_code" value="IN">
                                            <input type="hidden" name="countryName" id="country_name" value="India">
                                            <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="">
                                            <div class="small_station_info selected-hotel-city"></div>
                                            <input type="hidden" name="ishalal"  value="0">
                                            <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('labels.checkin') }} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                            <div class="input-group">
                                                <input id="departHotel" class="form-control departdate" type="text" name="departdate" required readonly value="{{ date('d-m-Y') }}"/>
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                <span class="total-nights">1 night</span>
                                            </div>
                                            <div class="small_station_info departDay">Sunday</div>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('labels.checkout') }} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                            <div class="input-group ">
                                                <input id="returnHotel" class="form-control returndate" type="text" name="returndate" readonly required value="{{ date('d-m-Y') }}"/>
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            </div>
                                            <div class="small_station_info returnDay">Monday</div>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('labels.roomguests') }}</label>
                                            <input type="text" name="roomsGuests" id="roomsGuests" readonly class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="1 Room 2 Guest">
                                            @include('_partials.hotel-guests')
                                            <input type="hidden" name="referral" class="referral" value="">
                                        </div>
                                    </div>
                            </div>
                            <div class="trending_searches">
                            </div>
                            <div class="search_btns">
                                <button type="submit" class="btn btn-primary">{{ __('labels.search') }} <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="top_locations">
    <div class="container">
        <div class="row">
            <div class="api-stats" style="display: none; padding: 20px;">
                <h3>API call started at <b id="startTime"></b> API call completed at <b id="timer"></b></h3>
                <br>
                <h3 style="display: none;" id="tStats"> Total hotel found <b id="totalHotels"></b> total time taken <b id="totalTime"></b></h3>
                <h3 id="error"></h3>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    var totalTime = 0;
    $(document).ready(function() {
        $('.btn-primary').click(function(e) {
            e.preventDefault();
            $('#tStats').hide();
            totalTime = 0;
           
            var currentTime = new Date ( );
            var currentHours = currentTime.getHours ( );
            var currentMinutes = currentTime.getMinutes ( );
            var currentSeconds = currentTime.getSeconds ( );
            var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";
            currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
            currentHours = ( currentHours == 0 ) ? 12 : currentHours;


            var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
            $('.api-stats').show();
            $("#startTime").html(currentTimeString);
            var clock = setInterval('updateClock()', 1000);
            $.ajax({
                url: '/hotels-raw',
                data: $('#searchRoomsForm').serialize(),
                type: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    console.log('reponse ', response)
                    clearInterval(clock);
                    if(response.success) {
                        $('#tStats').show();
                        $('#totalHotels').html(response.hotels.length);
                        if(totalTime > 60) {
                            var t = parseInt(totalTime / 60) + ' minutes and ' + (totalTime % 60) + ' seconds';
                        } else {
                            var t = totalTime + ' seconds';
                        }
                        $('#totalTime').html(t)
                    } else {
                        $('#error').html(response.hotels)
                    }
                },
                error: function(error) {
                    console.log('err ', error);
                }
            });
        });
    });
    function updateClock ( )
        {
        var currentTime = new Date ( );
        var currentHours = currentTime.getHours ( );
        var currentMinutes = currentTime.getMinutes ( );
        var currentSeconds = currentTime.getSeconds ( );

        // Pad the minutes and seconds with leading zeros, if required
        currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
        currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

        // Choose either "AM" or "PM" as appropriate
        var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

        // Convert the hours component to 12-hour format if needed
        currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

        // Convert an hours component of "0" to "12"
        currentHours = ( currentHours == 0 ) ? 12 : currentHours;

        // Compose the string for display
        var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
        totalTime++;
        
        $("#timer").html(currentTimeString);
            
     }

</script>
@endsection
