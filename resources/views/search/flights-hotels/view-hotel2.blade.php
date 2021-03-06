@extends('layouts.app-header')
@section('content') 
<style>
    /*.responsive__tabs ul.scrollable-tabs {
      background-color: #333 !important;
      overflow-x: auto !important;
      white-space: nowrap !important;
      display: flex !important;
      text-transform: uppercase !important;
      flex-direction: row !important;
      list-style: none !important;
    }
    .responsive__tabs ul.scrollable-tabs li {
      list-style-type: none !important;
      width:auto !important;
      margin-bottom: 0px !important;
      padding-left:0px !important;
    }
    .responsive__tabs ul.scrollable-tabs li::before{
        content:none !important;
    }
    .responsive__tabs ul.scrollable-tabs li a {
      display: inline-block !important;
      color: white !important;
      text-align: center !important;
      padding: 14px !important;
      text-decoration: none !important;
    }
    .responsive__tabs ul.scrollable-tabs li a:hover, .responsive__tabs ul.scrollable-tabs li a.active {
      background-color: #777 !important;
    }*/

    .responsive__tabs ul.scrollable-tabs {
        overflow-x: auto !important;
        white-space: nowrap !important;
        display: flex !important;
        /*              text-transform: uppercase !important;*/
        flex-direction: row !important;
        list-style: none !important;
    }
    .responsive__tabs ul.scrollable-tabs li {
        list-style-type: none !important;
        margin-bottom: 0px !important;
        padding-left:0px !important;
        width:auto;
    }
    .responsive__tabs ul.scrollable-tabs li::before{
        content:none !important;
    }
    .responsive__tabs ul.scrollable-tabs li a {
        display: inline-block !important;
        color: #333 !important;
        text-align: center !important;
        padding: 14px !important;
        text-decoration: none !important;
        width:100%;
        border-bottom: 3px solid #fff;
        background-color: #fff !important;
        font-size:18px;
    }
    .responsive__tabs ul.scrollable-tabs li a.active {
        background-color: #fff !important;
        border-bottom: 3px solid #fd7e14;
    }

    .responsive__tabs tr.active{
        border-left:14px solid #1e4355;
    }

    /*    .roomsList input[type='radio']{
            visibility: hidden;
            height: 1px;
        }*/
    .rounding_form_info p {
        color: #000;
    }
    

    
</style>
<section class="listing_banner_forms">
    <div class="container">
        <div class="row" id="FilterToggleButton">
             <div class="col-8">
                 <p style="color:#1e4355;">
                     <strong>{{ $input_data['city_name']}} , {{ $input_data['roomsGuests']}} </strong> <br/>
                     {{ $input_data['departdate']}} TO {{ $input_data['returndate']}} 
                 </p>
             </div>
             <div class="col-4 text-right">
                 <button onclick="$('#searchFormHFToggle').slideToggle();" style="padding:6px 10px;" type="button" class="btn btn-outline-dark btn-sm"><i class="fa fa-search"></i> Edit <i class="fa fa-chevron-down"></i> </button>
             </div>
        </div>
        <div class="row"  id="searchFormHFToggle">
            <div class="col-md-12">
                <div class="listing_inner_forms">
                    <form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_flight_rooms')}}"  >
                        @csrf

                        <div class="rounding_form_info" style="border-right:5px solid #b3cedd;border-left:5px solid #b3cedd;">
                            <div class="row">
                                <div class="col-lg-2" style="padding:0px;background:#fff;border:2px solid #b3cedd;">
                                    <div class="row" style="margin:0px;padding:0px;">
                                        <div class="col-12" style="padding:0px;">

                                            <div class="form-group" style="margin:0px !important;;">
                                                <select name="origin" class="depart-from" required="">
                                                    <option value="{{ $input_data['origin'] }}">{{  $input_data['from'] }}</option>
                                                </select>
                                                <input type="hidden" name="from" id="from-city-fh" value="{{ $input_data['from'] }}">
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-2" style="padding:0px;background:#fff;border:2px solid #b3cedd;">
                                    <div class="row" style="margin:0px;padding:0px;">
                                        
                                        <div class="col-12" style="padding:0px;background:#fff;">

                                            <div class="form-group" style="margin:0px !important;;">
                                                <select name="destination" class="depart-to-FH" required="">
                                                    <option value="{{ $input_data['destination'] }}">{{  $input_data['to'] }}</option>
                                                </select>
                                                <input type="hidden" name="to" id="to-city-fh" value="{{ $input_data['to'] }}">
                                                <input type="hidden" name="countryCode" id="country_codefh" value="{{ $input_data['countryCode']}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2" style="padding:0px;background:#fff;border:2px solid #b3cedd;">
                                    <div class="form-group" style="margin:0px !important;;">
                                        <input id="autocompleteFH" name="Location" value="{{  isset($input_data['Location']) ? $input_data['Location'] : $input_data['to']}}"  placeholder="{{ __('labels.search_placeholder') }}"  onFocus="geolocateFH()" type="text" style="color:#333;border:none;border-radius: 0px;padding:12px 5px;font-size:14px;width:100%;" />

                                        <input type="hidden" name="Latitude" id="LatitudeFH" value="{{  isset($input_data['Latitude']) ? $input_data['Latitude'] : ''}}">
                                        <input type="hidden" name="Longitude" id="LongitudeFH" value="{{  isset($input_data['Longitude']) ? $input_data['Longitude'] : ''}}">
                                        <input type="hidden" name="Radius" id="RadiusFH" value="{{  isset($input_data['Radius']) ? $input_data['Radius'] : '20'}}">
                                        <input type="hidden" name="city_id" id="city_id" value="{{ $s_city }}">
                                        <input type="hidden" name="city_name" id="city_nameFH" value="{{  $s_name}}">
                                        <input type="hidden" name="countryCode" id="country_codeFH" value="{{ $input_data['countryCode']}}">
                                        <input type="hidden" name="countryName" id="country_nameFH" value="{{ $input_data['countryName']}}">
                                        <input type="hidden" name="country" id="country" value="{{ $input_data['user_country']}}">
                                        <input type="hidden" name="currency" id="currency" value="{{ $input_data['currency']}}">
                                        <input type="hidden" name="referral" id="referral" value="{{ $referral}}">

                                        <input type="hidden" name="preffered_hotel" id="preffered_hotelFH" value="{{ $hotel_code}}">
                                        <input type="hidden" name="ishalal" id="ishalal" value="{{ (Session::get('active_tab') == 'halal')?1:0}}">

                                        <input type="hidden" id="nights_lbl" value="{{ __('labels.nights') }}">
                                        <input type="hidden" id="rooms_lbl" value="{{ __('labels.rooms') }}">
                                        <input type="hidden" id="night_lbl" value="{{ __('labels.night') }}">
                                        <input type="hidden" id="room_lbl" value="{{ __('labels.room') }}">
                                        <input type="hidden" id="local_sel" value="{{Session::get('locale')}}">
                                        <input type="hidden" id="adults_lbl" value="{{ __('labels.adults') }}">
                                        <input type="hidden" id="childrens_lbl" value="{{ __('labels.childrens') }}">


                                    </div>

                                </div>
                                <div class="col-lg-3" style="padding:0px;background:#fff;border:2px solid #b3cedd;">
                                    <div class="row"  style="margin:0px;padding:0px;">
                                        <div class="col text-center" style="padding:2px 0px 0px 0px;">
                                            <span class="hfnight small_station_info  text-center total-nights">
                                                @if(Session::get('locale') == 'heb')
                                                    @if ($total_nights > 1)
                                                        {{ __('labels.night') }}&nbsp;<span style="position:absolute;">{{ $total_nights}}</span>
                                                    @else
                                                        {{ __('labels.nights') }}&nbsp;&nbsp;<span style="position:absolute;">{{ $total_nights}}</span>
                                                    @endif
                                                     
                                                @else
                                                    {{ $total_nights}} 
                                                    @if ($total_nights > 1)
                                                        {{ __('labels.night') }}
                                                    @else
                                                        {{ __('labels.nights') }}
                                                    @endif
                                                @endif
                                            </span>


                                        </div>
                                    </div>
                                    <div class="row"  style="margin:0px;padding:0px;">
                                        <div class="col-12 text-center" style="margin:0px;padding:0px;">
                                            <div class="form-group" style="margin:0px !important;">
                                                <div class="input-group" >
                                                    <input type="text" id="dateRangeFH" value="{{ $input_data['departdate']}} - {{ $input_data['returndate']}}" class="form-control  text-center" />
                                                    <input id="departHotelFH" type="hidden" name="departdate" value="{{ $input_data['departdate']}}" />
                                                    <input id="returnHotelFH" type="hidden" name="returndate" value="{{ $input_data['returndate']}}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 text-center" style="padding:2px 0px 0px 0px;background:#fff;border:2px solid #b3cedd;">
                                    <div class="form-group" style="margin:0px !important;;">
                                        <div class="small_station_info  text-center" id="guestRoomsFH" >
                                            @if(Session::get('locale') == 'heb')
                                                {{ __('labels.rooms') }}&nbsp;<span style="position:absolute;">1</span>
                                            @else
                                                1 {{ __('labels.rooms') }}
                                            @endif
                                        </div>
                                        <input type="text" style="text-align: center;padding-top:0px;" name="roomsGuests" id="roomsGuestsFH" class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ $input_data['roomsGuests']}}">
                                        @include('_partials.flight-hotel-guests-edit')
                                    </div>
                                </div>
                                <div class="col-lg-1" style="padding:0px;border:2px solid #b3cedd;">
                                    <div class="search_btns_listing" style="padding:0px;border-radius: 0px !important;width:100%;"><button style="padding:0px;border-radius: 0px !important;width:100%;" type="submit" class="btn btn-primary flightHotelSearch">{{ __('labels.search')}}</button></div>

                                </div>
                                <div class="col-12">
                                    <span class="error_no_of_passenger" style="display:none;color:red;float: right;">{{ __('labels.passenger_validation')}}</span>

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>  
<section class="listing_banner_forms no-index">
    <div class="container">
    @if(Session::get('locale') == 'heb')
    <p style="color:#fff;margin-top:-20px;">.???????????? ?????????? ???????????? ?????????? 17% ????"?? ???????????? ???????????? ??????????, ???????? ?????????? ???????? *</p> 
    @endif
        <div class="row">
            <div class="col-md-12">
                <div class="listing_inner_forms">
                    <div class="rounding_form_info">
                        <p class="text-center"> 
                            @if(isset($hotel) && isset($hotel['static_data']['hotel_name']))
                            <span class=""><i class="fa fa-building"></i> {{ $hotel['static_data']['hotel_name']}}</span>
                            @endif
                            <span class=""> - {{ $input_data['city_name'] }}</span> 
                            {{ __('labels.from')}} <span class=""><i class="fa fa-calendar"></i>  {{  $input_data['departdate']}}</span>
                            {{ __('labels.to')}} <span class=""><i class="fa fa-calendar"></i>  {{  $input_data['returndate']}}</span>
                            {{ __('labels.travellers')}} <span class=""><i class="fa fa-user"></i>  {{  $input_data['roomsGuests']}}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<a href="#" class="float mobileOnlyView" data-toggle="modal" data-target="#roomFilterModal">
    <i class="fa fa-filter my-float"></i>
</a>

<div ng-app="flighthotelApp"  ng-controller="roomCtrl"  ng-init="getRooms()" id="hotelViewPage">


    <section class="hotel_detail_sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="inner_hotel_yep">
                        <div class="room-photo-gallery">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-4  col-sm-6 p-1">
                                            <div class="hotel_box room-hcol ht_imgs">
                                                @if(isset($static_data['hotel_images'][0]) && strpos($static_data['hotel_images'][0], 'http') !== false || ( isset($static_data['hotel_images'][0]) && strpos($static_data['hotel_images'][0], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][0])?$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me second-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][0])?$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][0])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me second-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][0])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @endif

                                                @if(isset($static_data['hotel_images'][1]) && strpos($static_data['hotel_images'][1], 'http') !== false || ( isset($static_data['hotel_images'][1]) && strpos($static_data['hotel_images'][1], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][1])?$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}" class="hotel_url  show-me third-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][1])?$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][1])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me third-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][1])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-sm-12  p-1">
                                            <div class="hotel_box room-vcol ht_imgs">
                                                @if(isset($static_data['hotel_images'][2]) && strpos($static_data['hotel_images'][2], 'http') !== false || ( isset($static_data['hotel_images'][2]) && strpos($static_data['hotel_images'][2], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][2])?$static_data['hotel_images'][2]:asset('/images/no-hotel-photo.png')}}" class="hotel_url  show-me primary-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][2])?$static_data['hotel_images'][2]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][2])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][2]:asset('/images/no-hotel-photo.png')}}" class="hotel_url  show-me primary-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][2])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][2]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row room-thumbs" >
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][3]) && strpos($static_data['hotel_images'][3], 'http') !== false || ( isset($static_data['hotel_images'][3]) && strpos($static_data['hotel_images'][3], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][3])?$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][3])?$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][3])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][3])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][4]) && strpos($static_data['hotel_images'][4], 'http') !== false || ( isset($static_data['hotel_images'][4]) && strpos($static_data['hotel_images'][4], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][4])?$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][4])?$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}"  >
                                                </a>onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';"
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][4])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][4])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][5]) && strpos($static_data['hotel_images'][5], 'http') !== false || ( isset($static_data['hotel_images'][5]) && strpos($static_data['hotel_images'][5], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][5])?$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][5])?$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][5])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][5])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][6]) && strpos($static_data['hotel_images'][6], 'http') !== false || ( isset($static_data['hotel_images'][6]) && strpos($static_data['hotel_images'][6], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][6])?$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][6])?$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][6])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][6])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb">
                                                <div class="ht_imgs">
                                                    <div class="hotel_box room-thumb">
                                                        @php $r_images = array(); $roomImagesCount = 0; @endphp
                                                        @foreach($roomImages as $room_img)
                                                        @if($room_img->images && $room_img->images != null && !empty($room_img->images))
                                                        @php 
                                                        array_push($r_images , unserialize($room_img->images));
                                                        @endphp
                                                        @endif
                                                        @endforeach
                                                        @php $counter = 0; @endphp
                                                        @foreach($r_images as $r_key => $r_imgs)
                                                        @foreach($r_imgs as $r_key => $r_img)
                                                            @if(strpos($r_img, 'http') !== false || strpos($r_img, 'www') !== false)
                                                                <a href="{{ $r_img}}" title="{{ $static_data['hotel_name']}}" class="hotel_url @if($counter == 0) show-me @endif">
                                                                    <img  class="hotel_photo img-responsive" src="{{ $r_img}}" alt="hotel" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                </a>
                                                            @else
                                                                <a href="{{env('AWS_BUCKET_URL')}}/{{ $r_img}}" title="{{ $static_data['hotel_name']}}" class="hotel_url @if($counter == 0) show-me @endif">
                                                                    <img  class="hotel_photo img-responsive" src="{{env('AWS_BUCKET_URL')}}/{{ $r_img}}" alt="hotel" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                </a>
                                                            @endif
                                                        @php 
                                                        $counter++; $roomImagesCount++; 
                                                        @endphp
                                                        @endforeach
                                                        @endforeach
                                                    </div>
                                                    @if($roomImagesCount > 0)
                                                         @if(strpos($r_img, 'http') !== false || strpos($r_img, 'www') !== false)
                                                            <div class="overlay_text_md" style="height: 100%;border-radius: 5px;" href="{{ $r_img}}" title="{{ $static_data['hotel_name']}}" >
                                                                <a style="padding: 30% 20% !important;color:#fff;display: block;" href="{{ $r_img}}">+ {{$roomImagesCount}} {{ __('labels.photos') }}</a>
                                                            </div>
                                                        @else
                                                            <div class="overlay_text_md" style="height: 100%;border-radius: 5px;" href="{{env('AWS_BUCKET_URL')}}/{{ $r_img}}" title="{{ $static_data['hotel_name']}}" >
                                                                <a style="padding: 30% 20% !important;color:#fff;display: block;" href="{{env('AWS_BUCKET_URL')}}/{{ $r_img}}">+ {{$roomImagesCount}} {{ __('labels.photos') }}</a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4" id="hotelmap" style="min-height:300px;">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Flight Details -->
                <div class="flex_width_air col-md-12 inner_about_hotels_data attr mt-4">
                    <div class="vistara_Data">
                        <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightdata['Segments'][0][0]['Airline']['AirlineCode']}}" alt="{{ $flightdata['Segments'][0][0]['Airline']['AirlineName']}}">
                        <span>{{ $flightdata['Segments'][0][0]['Airline']['AirlineName']}} 
                            {{ $flightdata['Segments'][0][0]['Airline']['AirlineCode']}} - {{ $flightdata['Segments'][0][0]['Airline']['FlightNumber']}}
                        </span>
                        @if(!empty($flightdata['Segments'][0][2]))
                        <span class="multistop">(2 stops)</span>
                        @endif
                        @if(!empty($flightdata['Segments'][0][1]) && empty($flightdata['Segments'][0][2]))
                        <span class="onestop">(1 stop)</span>
                        @endif
                        @if(empty($flightdata['Segments'][0][1]) && empty($flightdata['Segments'][0][2]))
                        <span class="direct">(Direct)</span>
                        @endif
                    </div>
                    <div class="main_flts_time">
                        <div class="fllight_time_date">
                            <h5>{{ $flightdata['Segments'][0][0]['Origin']['Airport']['AirportCode']}} <span>{{ $flightdata['Segments'][0][0]['Origin']['Airport']['CityName']}}, {{ $flightdata['Segments'][0][0]['Origin']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightdata['Segments'][0][0]['Origin']['DepTime']))}})</div>
                        </div>
                        @if(!empty($flightdata['Segments'][0][2]))
                        <div class="time_hr">{{ intdiv($flightdata['Segments'][0][2]['Duration'], 60).'h '.($flightdata['Segments'][0][2]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                        @endif
                        @if(!empty($flightdata['Segments'][0][1]) && empty($flightdata['Segments'][0][2]))
                        <div class="time_hr">{{ intdiv($flightdata['Segments'][0][1]['Duration'], 60).'h '.($flightdata['Segments'][0][1]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                        @endif
                        @if(empty($flightdata['Segments'][0][1]) && empty($flightdata['Segments'][0][2]))
                        <div class="time_hr">{{ intdiv($flightdata['Segments'][0][0]['Duration'], 60).'h '.($flightdata['Segments'][0][0]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                        @endif

                        @if(!empty($flightdata['Segments'][0][2]))
                        <div class="fllight_time_date">
                            <h5>{{ $flightdata['Segments'][0][2]['Destination']['Airport']['AirportCode']}} <span>{{ $flightdata['Segments'][0][2]['Destination']['Airport']['CityName']}}, {{ $flightdata['Segments'][0][2]['Destination']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightdata['Segments'][0][2]['Destination']['ArrTime']))}})</div>
                        </div>
                        @endif
                        @if(!empty($flightdata['Segments'][0][1]) && empty($flightdata['Segments'][0][2]))
                        <div class="fllight_time_date">
                            <h5>{{ $flightdata['Segments'][0][1]['Destination']['Airport']['AirportCode']}} <span>{{ $flightdata['Segments'][0][1]['Destination']['Airport']['CityName']}}, {{ $flightdata['Segments'][0][1]['Destination']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightdata['Segments'][0][1]['Destination']['ArrTime']))}})</div>
                        </div>
                        @endif
                        @if(empty($flightdata['Segments'][0][1]) && empty($flightdata['Segments'][0][2]))
                        <div class="fllight_time_date">
                            <h5>{{ $flightdata['Segments'][0][0]['Destination']['Airport']['AirportCode']}} <span>{{ $flightdata['Segments'][0][0]['Destination']['Airport']['CityName']}}, {{ $flightdata['Segments'][0][0]['Destination']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightdata['Segments'][0][0]['Destination']['ArrTime']))}})</div>
                        </div>
                        @endif

                    </div>

                    <!-- International Return  -->
                    @if(!empty($flightdata['Segments'][1][0]))
                    <div class="vistara_Data">
                        <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightdata['Segments'][1][0]['Airline']['AirlineCode']}}" alt="{{ $flightdata['Segments'][1][0]['Airline']['AirlineName']}}">
                        <span>{{ $flightdata['Segments'][1][0]['Airline']['AirlineName']}} 
                            {{ $flightdata['Segments'][1][0]['Airline']['AirlineCode']}} - {{ $flightdata['Segments'][1][0]['Airline']['FlightNumber']}}
                        </span>
                        @if(!empty($flightdata['Segments'][1][2]))
                        <span class="multistop">(2 stops)</span>
                        @endif
                        @if(!empty($flightdata['Segments'][1][1]) && empty($flightdata['Segments'][1][2]))
                        <span class="onestop">(1 stop)</span>
                        @endif
                        @if(empty($flightdata['Segments'][1][1]) && empty($flightdata['Segments'][1][2]))
                        <span class="direct">(Direct)</span>
                        @endif
                    </div>
                    <div class="main_flts_time">
                        <div class="fllight_time_date">
                            <h5>{{ $flightdata['Segments'][1][0]['Origin']['Airport']['AirportCode']}} <span>{{ $flightdata['Segments'][1][0]['Origin']['Airport']['CityName']}}, {{ $flightdata['Segments'][1][0]['Origin']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightdata['Segments'][1][0]['Origin']['DepTime']))}})</div>
                        </div>
                        @if(!empty($flightdata['Segments'][1][2]))
                        <div class="time_hr">{{ intdiv($flightdata['Segments'][1][2]['Duration'], 60).'h '.($flightdata['Segments'][1][2]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                        @endif
                        @if(!empty($flightdata['Segments'][1][1]) && empty($flightdata['Segments'][1][2]))
                        <div class="time_hr">{{ intdiv($flightdata['Segments'][1][1]['Duration'], 60).'h '.($flightdata['Segments'][1][1]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                        @endif
                        @if(empty($flightdata['Segments'][1][1]) && empty($flightdata['Segments'][1][2]))
                        <div class="time_hr">{{ intdiv($flightdata['Segments'][1][0]['Duration'], 60).'h '.($flightdata['Segments'][1][0]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                        @endif

                        @if(!empty($flightdata['Segments'][1][2]))
                        <div class="fllight_time_date">
                            <h5>{{ $flightdata['Segments'][1][2]['Destination']['Airport']['AirportCode']}} <span>{{ $flightdata['Segments'][1][2]['Destination']['Airport']['CityName']}}, {{ $flightdata['Segments'][1][2]['Destination']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightdata['Segments'][1][2]['Destination']['ArrTime']))}})</div>
                        </div>
                        @endif
                        @if(!empty($flightdata['Segments'][1][1]) && empty($flightdata['Segments'][1][2]))
                        <div class="fllight_time_date">
                            <h5>{{ $flightdata['Segments'][1][1]['Destination']['Airport']['AirportCode']}} <span>{{ $flightdata['Segments'][1][1]['Destination']['Airport']['CityName']}}, {{ $flightdata['Segments'][1][1]['Destination']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightdata['Segments'][1][1]['Destination']['ArrTime']))}})</div>
                        </div>
                        @endif
                        @if(empty($flightdata['Segments'][1][1]) && empty($flightdata['Segments'][1][2]))
                        <div class="fllight_time_date">
                            <h5>{{ $flightdata['Segments'][1][0]['Destination']['Airport']['AirportCode']}} <span>{{ $flightdata['Segments'][1][0]['Destination']['Airport']['CityName']}}, {{ $flightdata['Segments'][1][0]['Destination']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightdata['Segments'][1][0]['Destination']['ArrTime']))}})</div>
                        </div>
                        @endif

                    </div>
                    @endif
                    <!-- Ends -->


                    <div class="mt-4"></div>
                    <a href="javascript:void(0);" class="btn btn_flight_details" data-id="1">{{ __('labels.flight_details')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="javascript:void(0);" data-toggle='modal' data-target='#moreflights' class="btn btn btn-primary">More Flights</a>
                    <!-- Return  -->
                    @if(!empty($flightReturndata['Segments'][0][0]))
                    <br />
                    <br />
                    <div class="vistara_Data">
                        <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightReturndata['Segments'][0][0]['Airline']['AirlineCode']}}" alt="{{ $flightReturndata['Segments'][0][0]['Airline']['AirlineName']}}">
                        <span>{{ $flightReturndata['Segments'][0][0]['Airline']['AirlineName']}} 
                            {{ $flightReturndata['Segments'][0][0]['Airline']['AirlineCode']}} - {{ $flightReturndata['Segments'][0][0]['Airline']['FlightNumber']}}
                        </span>
                        @if(!empty($flightReturndata['Segments'][0][2]))
                        <span class="multistop">(2 stops)</span>
                        @endif
                        @if(!empty($flightReturndata['Segments'][0][1]) && empty($flightReturndata['Segments'][0][2]))
                        <span class="onestop">(1 stop)</span>
                        @endif
                        @if(empty($flightReturndata['Segments'][0][1]) && empty($flightReturndata['Segments'][0][2]))
                        <span class="direct">(Direct)</span>
                        @endif
                    </div>
                    <div class="main_flts_time">
                        <div class="fllight_time_date">
                            <h5>{{ $flightReturndata['Segments'][0][0]['Origin']['Airport']['AirportCode']}} <span>{{ $flightReturndata['Segments'][0][0]['Origin']['Airport']['CityName']}}, {{ $flightReturndata['Segments'][0][0]['Origin']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightReturndata['Segments'][0][0]['Origin']['DepTime']))}})</div>
                        </div>
                        @if(!empty($flightReturndata['Segments'][0][2]))
                        <div class="time_hr">{{ intdiv($flightReturndata['Segments'][0][2]['Duration'], 60).'h '.($flightReturndata['Segments'][0][2]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                        @endif
                        @if(!empty($flightReturndata['Segments'][0][1]) && empty($flightReturndata['Segments'][0][2]))
                        <div class="time_hr">{{ intdiv($flightReturndata['Segments'][0][1]['Duration'], 60).'h '.($flightReturndata['Segments'][0][1]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                        @endif
                        @if(empty($flightReturndata['Segments'][0][1]) && empty($flightReturndata['Segments'][0][2]))
                        <div class="time_hr">{{ intdiv($flightReturndata['Segments'][0][0]['Duration'], 60).'h '.($flightReturndata['Segments'][0][0]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                        @endif

                        @if(!empty($flightReturndata['Segments'][0][2]))
                        <div class="fllight_time_date">
                            <h5>{{ $flightReturndata['Segments'][0][2]['Destination']['Airport']['AirportCode']}} <span>{{ $flightReturndata['Segments'][0][2]['Destination']['Airport']['CityName']}}, {{ $flightReturndata['Segments'][0][2]['Destination']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightReturndata['Segments'][0][2]['Destination']['ArrTime']))}})</div>
                        </div>
                        @endif
                        @if(!empty($flightReturndata['Segments'][0][1]) && empty($flightReturndata['Segments'][0][2]))
                        <div class="fllight_time_date">
                            <h5>{{ $flightReturndata['Segments'][0][1]['Destination']['Airport']['AirportCode']}} <span>{{ $flightReturndata['Segments'][0][1]['Destination']['Airport']['CityName']}}, {{ $flightReturndata['Segments'][0][1]['Destination']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightReturndata['Segments'][0][1]['Destination']['ArrTime']))}})</div>
                        </div>
                        @endif
                        @if(empty($flightReturndata['Segments'][0][1]) && empty($flightReturndata['Segments'][0][2]))
                        <div class="fllight_time_date">
                            <h5>{{ $flightReturndata['Segments'][0][0]['Destination']['Airport']['AirportCode']}} <span>{{ $flightReturndata['Segments'][0][0]['Destination']['Airport']['CityName']}}, {{ $flightReturndata['Segments'][0][0]['Destination']['Airport']['CountryName']}}</span></h5>
                            <div class="time_flts">({{ date('H:i', strtotime($flightReturndata['Segments'][0][0]['Destination']['ArrTime']))}})</div>
                        </div>
                        @endif

                    </div>
                    <a href="javascript:void(0);" class="btn btn_flight_details" data-id="2">{{ __('labels.flight_details')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="javascript:void(0);" data-toggle='modal' data-target='#moreflightsReturn' class="btn btn btn-primary">{{ __('labels.more_flights')}}</a>
                    @endif


                    <div class="flight_information_data" id="view_flight_1" style="display: none;">
                        <nav>
                            <div class="nav nav-tabs nav-fill" role="tablist">
                                <a class="nav-item nav-link active" id="nav-flightInformation-tab" data-toggle="tab" href="#nav-flightInformation_return_1" role="tab" aria-controls="nav-flightInformation" aria-selected="true">{{ __('labels.flight_info')}}</a>
                                <a class="nav-item nav-link" id="nav-fare-tab" data-toggle="tab" href="#nav-fare_return_1" role="tab" aria-controls="nav-fare" aria-selected="false">{{ __('labels.fair_details')}}</a>
                                <a class="nav-item nav-link" id="nav-baggage-tab" data-toggle="tab" href="#nav-baggage_return_1" role="tab" aria-controls="nav-baggage" aria-selected="false">{{ __('labels.bag_rules')}}</a>
                            </div>
                        </nav>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="nav-flightInformation_return_1" role="tabpanel" aria-labelledby="nav-flightInformation-tab">
                                <div class="inner_flight_tabs">
                                    <div class="flght_data_info">
                                        <!-- Domestic Oneway Div -->
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="plane_data">
                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightdata['Segments'][0][0]['Airline']['AirlineCode']}}" alt="{{ $flightdata['Segments'][0][0]['Airline']['AirlineName']}}">
                                                </div>
                                                <span class="flight_name">{{ $flightdata['Segments'][0][0]['Airline']['AirlineName']}}</span>
                                                <span class="flight_serial_number">{{ $flightdata['Segments'][0][0]['Airline']['AirlineCode']}}  {{ $flightdata['Segments'][0][0]['Airline']['FlightNumber']}}</span>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <div class="city_time">{{$flightdata['Segments'][0][0]['Origin']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][0][0]['Origin']['DepTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{$flightdata['Segments'][0][0]['Origin']['Airport']['AirportName']}}, {{$flightdata['Segments'][0][0]['Origin']['Airport']['CountryName']}}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="flight_duration">
                                                    <span>{{ intdiv($flightdata['Segments'][0][0]['Duration'], 60).'h '.($flightdata['Segments'][0][0]['Duration'] % 60)}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                </div>
                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                            </div>
                                            <div class="col-md-3">
                                                <div class="city_time">{{$flightdata['Segments'][0][0]['Destination']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][0][0]['Destination']['ArrTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{ $flightdata['Segments'][0][0]['Destination']['Airport']['CountryName']}}</div>
                                            </div>
                                        </div>

                                        <!-- Domestic One Way With One Stop Div -->
                                        @if(!empty($flightdata['Segments'][0][1]))
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="plane_data">
                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightdata['Segments'][0][1]['Airline']['AirlineCode']}}" alt="{{ $flightdata['Segments'][0][1]['Airline']['AirlineName']}}">
                                                </div>
                                                <span class="flight_name">{{ $flightdata['Segments'][0][1]['Airline']['AirlineName']}}</span>
                                                <span class="flight_serial_number">{{ $flightdata['Segments'][0][1]['Airline']['AirlineCode']}}  {{ $flightdata['Segments'][0][1]['Airline']['FlightNumber']}}</span>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <div class="city_time">{{$flightdata['Segments'][0][1]['Origin']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][0][1]['Origin']['DepTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{$flightdata['Segments'][0][1]['Origin']['Airport']['AirportName']}}, {{$flightdata['Segments'][0][1]['Origin']['Airport']['CountryName']}}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="flight_duration">
                                                    <span>{{ intdiv($flightdata['Segments'][0][1]['Duration'], 60).'h '.($flightdata['Segments'][0][1]['Duration'] % 60)}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                </div>
                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                            </div>
                                            <div class="col-md-3">
                                                <div class="city_time">{{$flightdata['Segments'][0][1]['Destination']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][0][1]['Destination']['ArrTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{ $flightdata['Segments'][0][1]['Destination']['Airport']['CountryName']}}</div>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Ends here -->

                                        <!-- Domestic One Way With Second Stop Div -->
                                        @if(!empty($flightdata['Segments'][0][2]))
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="plane_data">
                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightdata['Segments'][0][2]['Airline']['AirlineCode']}}" alt="{{ $flightdata['Segments'][0][2]['Airline']['AirlineName']}}">
                                                </div>
                                                <span class="flight_name">{{ $flightdata['Segments'][0][2]['Airline']['AirlineName']}}</span>
                                                <span class="flight_serial_number">{{ $flightdata['Segments'][0][2]['Airline']['AirlineCode']}}  {{ $flightdata['Segments'][0][2]['Airline']['FlightNumber']}}</span>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <div class="city_time">{{$flightdata['Segments'][0][2]['Origin']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][0][2]['Origin']['DepTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{$flightdata['Segments'][0][2]['Origin']['Airport']['AirportName']}}, {{$flightdata['Segments'][0][2]['Origin']['Airport']['CountryName']}}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="flight_duration">
                                                    <span>{{ intdiv($flightdata['Segments'][0][2]['Duration'], 60).'h '.($flightdata['Segments'][0][2]['Duration'] % 60)}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                </div>
                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                            </div>
                                            <div class="col-md-3">
                                                <div class="city_time">{{$flightdata['Segments'][0][2]['Destination']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][0][2]['Destination']['ArrTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{ $flightdata['Segments'][0][2]['Destination']['Airport']['CountryName']}}</div>
                                            </div>
                                        </div>
                                        @endif

                                        @if(!empty($flightdata['Segments'][1][0]))
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="plane_data">
                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightdata['Segments'][1][0]['Airline']['AirlineCode']}}" alt="{{ $flightdata['Segments'][1][0]['Airline']['AirlineName']}}">
                                                </div>
                                                <span class="flight_name">{{ $flightdata['Segments'][1][0]['Airline']['AirlineName']}}</span>
                                                <span class="flight_serial_number">{{ $flightdata['Segments'][1][0]['Airline']['AirlineCode']}}  {{ $flightdata['Segments'][1][0]['Airline']['FlightNumber']}}</span>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <div class="city_time">{{$flightdata['Segments'][1][0]['Origin']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][1][0]['Origin']['DepTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{$flightdata['Segments'][1][0]['Origin']['Airport']['AirportName']}}, {{$flightdata['Segments'][1][0]['Origin']['Airport']['CountryName']}}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="flight_duration">
                                                    <span>{{ intdiv($flightdata['Segments'][1][0]['Duration'], 60).'h '.($flightdata['Segments'][1][0]['Duration'] % 60)}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="city_time">{{$flightdata['Segments'][1][0]['Destination']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][1][0]['Destination']['ArrTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{ $flightdata['Segments'][1][0]['Destination']['Airport']['CountryName']}}</div>
                                            </div>
                                        </div>
                                        @endif

                                        @if(!empty($flightdata['Segments'][1][1]))
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="plane_data">
                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightdata['Segments'][1][1]['Airline']['AirlineCode']}}" alt="{{ $flightdata['Segments'][1][1]['Airline']['AirlineName']}}">
                                                </div>
                                                <span class="flight_name">{{ $flightdata['Segments'][1][1]['Airline']['AirlineName']}}</span>
                                                <span class="flight_serial_number">{{ $flightdata['Segments'][1][1]['Airline']['AirlineCode']}}  {{ $flightdata['Segments'][1][1]['Airline']['FlightNumber']}}</span>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <div class="city_time">{{$flightdata['Segments'][1][1]['Origin']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][1][1]['Origin']['DepTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{$flightdata['Segments'][1][1]['Origin']['Airport']['AirportName']}}, {{$flightdata['Segments'][1][1]['Origin']['Airport']['CountryName']}}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="flight_duration">
                                                    <span>{{ intdiv($flightdata['Segments'][1][1]['Duration'], 60).'h '.($flightdata['Segments'][1][1]['Duration'] % 60)}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="city_time">{{$flightdata['Segments'][1][1]['Destination']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][1][1]['Destination']['ArrTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{ $flightdata['Segments'][1][1]['Destination']['Airport']['CountryName']}}</div>
                                            </div>
                                        </div>
                                        @endif

                                        @if(!empty($flightdata['Segments'][1][2]))
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="plane_data">
                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightdata['Segments'][1][2]['Airline']['AirlineCode']}}" alt="{{ $flightdata['Segments'][1][2]['Airline']['AirlineName']}}">
                                                </div>
                                                <span class="flight_name">{{ $flightdata['Segments'][1][2]['Airline']['AirlineName']}}</span>
                                                <span class="flight_serial_number">{{ $flightdata['Segments'][1][2]['Airline']['AirlineCode']}}  {{ $flightdata['Segments'][1][2]['Airline']['FlightNumber']}}</span>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <div class="city_time">{{$flightdata['Segments'][1][2]['Origin']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][1][2]['Origin']['DepTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{$flightdata['Segments'][1][2]['Origin']['Airport']['AirportName']}}, {{$flightdata['Segments'][1][2]['Origin']['Airport']['CountryName']}}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="flight_duration">
                                                    <span>{{ intdiv($flightdata['Segments'][1][2]['Duration'], 60).'h '.($flightdata['Segments'][1][2]['Duration'] % 60)}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                </div>
                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                            </div>
                                            <div class="col-md-3">
                                                <div class="city_time">{{$flightdata['Segments'][1][2]['Destination']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightdata['Segments'][1][2]['Destination']['ArrTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{ $flightdata['Segments'][1][2]['Destination']['Airport']['CountryName']}}</div>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Ends -->

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-fare_return_1" role="tabpanel" aria-labelledby="nav-fare-tab">
                                <div class="inner_flight_tabs">
                                    <table class="table">
                                        <tr>
                                            <td>{{ __('labels.base_fare')}} </td>
                                            <td>{{ $flightdata['Fare']['Currency']}} {{ number_format( $flightdata['Fare']['BaseFare'] + ($iniscomm / 100 * $flightdata['Fare']['BaseFare']) , 2)}} </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('labels.tax_fee')}} </td>
                                            <td>{{ $flightdata['Fare']['Currency']}} {{ number_format($flightdata['Fare']['Tax'] + ($iniscomm / 100 * $flightdata['Fare']['Tax']) ,2) }} </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('labels.total_fare')}} </th>
                                            <th>{{ $flightdata['Fare']['Currency']}} {{ number_format( $flightdata['Fare']['OfferedFare'] + ($iniscomm / 100 * $flightdata['Fare']['OfferedFare']) + ($conversion / 100 * ($flightdata['Fare']['OfferedFare'] + ($iniscomm / 100 * $flightdata['Fare']['OfferedFare']))),2) }} </th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-baggage_return_1" role="tabpanel" aria-labelledby="nav-baggage-tab">
                                <div class="inner_flight_tabs">
                                    <table class="table">
                                        <tr>
                                            <td>{{ __('labels.bag_type')}}</td>
                                            <td>{{ __('labels.checkin')}}</td>
                                            <td>{{ __('labels.cabin')}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('labels.adult')}}</td>
                                            <td>{{ $flightdata['Segments'][0][0]['Baggage']}}</td>
                                            <td>{{ $flightdata['Segments'][0][0]['CabinBaggage']}}</td>
                                        </tr>
                                    </table>
                                    <!-- <p class="baggage_data">* Only 1 check-in baggage allowed per passenger. You can buy excess baggage as allowed by the airline, however you might have to pay additional charges at airport.</p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flight_information_data" id="view_flight_2" style="display: none;">
                        <nav>
                            <div class="nav nav-tabs nav-fill" role="tablist">
                                <a class="nav-item nav-link active" id="nav-flightInformation-tab" data-toggle="tab" href="#nav-flightInformation_return_2" role="tab" aria-controls="nav-flightInformation" aria-selected="true">{{ __('labels.flight_info')}}</a>
                                <a class="nav-item nav-link" id="nav-fare-tab" data-toggle="tab" href="#nav-fare_return_2" role="tab" aria-controls="nav-fare" aria-selected="false">{{ __('labels.fair_details')}}</a>
                                <a class="nav-item nav-link" id="nav-baggage-tab" data-toggle="tab" href="#nav-baggage_return_2" role="tab" aria-controls="nav-baggage" aria-selected="false">{{ __('labels.bag_rules')}}</a>
                            </div>
                        </nav>
                        @if(!empty($flightReturndata['Segments'][0][0]))
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="nav-flightInformation_return_2" role="tabpanel" aria-labelledby="nav-flightInformation-tab">
                                <div class="inner_flight_tabs">
                                    <div class="flght_data_info">
                                        <!-- Domestic Oneway Div -->
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="plane_data">
                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightReturndata['Segments'][0][0]['Airline']['AirlineCode']}}" alt="{{ $flightReturndata['Segments'][0][0]['Airline']['AirlineName']}}">
                                                </div>
                                                <span class="flight_name">{{ $flightReturndata['Segments'][0][0]['Airline']['AirlineName']}}</span>
                                                <span class="flight_serial_number">{{ $flightReturndata['Segments'][0][0]['Airline']['AirlineCode']}}  {{ $flightReturndata['Segments'][0][0]['Airline']['FlightNumber']}}</span>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <div class="city_time">{{$flightReturndata['Segments'][0][0]['Origin']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightReturndata['Segments'][0][0]['Origin']['DepTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{$flightReturndata['Segments'][0][0]['Origin']['Airport']['AirportName']}}, {{$flightReturndata['Segments'][0][0]['Origin']['Airport']['CountryName']}}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="flight_duration">
                                                    <span>{{ intdiv($flightReturndata['Segments'][0][0]['Duration'], 60).'h '.($flightReturndata['Segments'][0][0]['Duration'] % 60)}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                </div>
                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                            </div>
                                            <div class="col-md-3">
                                                <div class="city_time">{{$flightReturndata['Segments'][0][0]['Destination']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightReturndata['Segments'][0][0]['Destination']['ArrTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{ $flightReturndata['Segments'][0][0]['Destination']['Airport']['CountryName']}}</div>
                                            </div>
                                        </div>

                                        <!-- Domestic One Way With One Stop Div -->
                                        @if(!empty($flightReturndata['Segments'][0][1]))
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="plane_data">
                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightReturndata['Segments'][0][1]['Airline']['AirlineCode']}}" alt="{{ $flightReturndata['Segments'][0][1]['Airline']['AirlineName']}}">
                                                </div>
                                                <span class="flight_name">{{ $flightReturndata['Segments'][0][1]['Airline']['AirlineName']}}</span>
                                                <span class="flight_serial_number">{{ $flightReturndata['Segments'][0][1]['Airline']['AirlineCode']}}  {{ $flightReturndata['Segments'][0][1]['Airline']['FlightNumber']}}</span>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <div class="city_time">{{$flightReturndata['Segments'][0][1]['Origin']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightReturndata['Segments'][0][1]['Origin']['DepTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{$flightReturndata['Segments'][0][1]['Origin']['Airport']['AirportName']}}, {{$flightReturndata['Segments'][0][1]['Origin']['Airport']['CountryName']}}</div>
                                            </div> 
                                            <div class="col-md-4 text-center">
                                                <div class="flight_duration">
                                                    <span>{{ intdiv($flightReturndata['Segments'][0][1]['Duration'], 60).'h '.($flightReturndata['Segments'][0][1]['Duration'] % 60)}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                </div>
                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                            </div>
                                            <div class="col-md-3">
                                                <div class="city_time">{{$flightReturndata['Segments'][0][1]['Destination']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightReturndata['Segments'][0][1]['Destination']['ArrTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{ $flightReturndata['Segments'][0][1]['Destination']['Airport']['CountryName']}}</div>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Ends here -->

                                        <!-- Domestic One Way With Second Stop Div -->
                                        @if(!empty($flightReturndata['Segments'][0][2]))
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="plane_data">
                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $flightReturndata['Segments'][0][2]['Airline']['AirlineCode']}}" alt="{{ $flightReturndata['Segments'][0][2]['Airline']['AirlineName']}}">
                                                </div>
                                                <span class="flight_name">{{ $flightReturndata['Segments'][0][2]['Airline']['AirlineName']}}</span>
                                                <span class="flight_serial_number">{{ $flightReturndata['Segments'][0][2]['Airline']['AirlineCode']}}  {{ $flightReturndata['Segments'][0][2]['Airline']['FlightNumber']}}</span>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <div class="city_time">{{$flightReturndata['Segments'][0][2]['Origin']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightReturndata['Segments'][0][2]['Origin']['DepTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{$flightReturndata['Segments'][0][2]['Origin']['Airport']['AirportName']}}, {{$flightReturndata['Segments'][0][2]['Origin']['Airport']['CountryName']}}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="flight_duration">
                                                    <span>{{ intdiv($flightReturndata['Segments'][0][2]['Duration'], 60).'h '.($flightReturndata['Segments'][0][2]['Duration'] % 60)}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                </div>
                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                            </div>
                                            <div class="col-md-3">
                                                <div class="city_time">{{$flightReturndata['Segments'][0][2]['Destination']['Airport']['AirportCode']}} <span> ({{ date('H:i', strtotime($flightReturndata['Segments'][0][2]['Destination']['ArrTime']))}}) </span></div>
                                                <div class="airpot_name_data">{{ $flightReturndata['Segments'][0][2]['Destination']['Airport']['CountryName']}}</div>
                                            </div>
                                        </div>
                                        @endif
                                        <!-- Ends here -->

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-fare_return_2" role="tabpanel" aria-labelledby="nav-fare-tab">
                                <div class="inner_flight_tabs">
                                    <table class="table">
                                        <tr>
                                            <td>{{ __('labels.base_fare')}} </td>
                                            <td>{{ $flightReturndata['Fare']['Currency']}} {{ number_format( $flightReturndata['Fare']['BaseFare'] + ($iniscomm / 100 * $flightReturndata['Fare']['BaseFare']) , 2) }} </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('labels.tax_fee')}} </td>
                                            <td>{{ $flightReturndata['Fare']['Currency']}} {{ number_format( $flightReturndata['Fare']['Tax'] + ($iniscomm / 100 * $flightReturndata['Fare']['Tax']) , 2)}} </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('labels.total_fare')}} </th>
                                            <th>{{ $flightReturndata['Fare']['Currency']}} {{ number_format( $flightReturndata['Fare']['OfferedFare'] + ($iniscomm / 100 * $flightReturndata['Fare']['OfferedFare']) + ($conversion / 100 * ($flightReturndata['Fare']['OfferedFare'] + ($iniscomm / 100 * $flightReturndata['Fare']['OfferedFare']))) , 2) }} </th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-baggage_return_2" role="tabpanel" aria-labelledby="nav-baggage-tab">
                                <div class="inner_flight_tabs">
                                    <table class="table">
                                        <tr>
                                            <td>{{ __('labels.bag_type')}}</td>
                                            <td>{{ __('labels.checkin')}}</td>
                                            <td>{{ __('labels.cabin')}}</td>
                                        </tr>
                                        <tr>
                                            <td>Adult</td>
                                            <td>{{ $flightReturndata['Segments'][0][0]['Baggage']}}</td>
                                            <td>{{ $flightReturndata['Segments'][0][0]['CabinBaggage']}}</td>
                                        </tr>
                                    </table>
                                    <!-- <p class="baggage_data">* Only 1 check-in baggage allowed per passenger. You can buy excess baggage as allowed by the airline, however you might have to pay additional charges at airport.</p> -->
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <!-- Return Ends -->
                </div>
                <!-- Ends -->
            </div>
        </div>
    </section>

    <section class="room_filters mt-4 desktopOnlyView" id="desktopRoomFilters">
        <div class="container">
            <div class="col-md-12 inner_about_hotels_data attr" >
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="slidecontainer" >
                                <label class="filters-label">{{ __('labels.price')}}</label>
                                <input type="range" min="500" max="500000" value="50000" class="slider" id="pirceRangeRooms" ng-change="filterByPrice(priceRange)" ng-model="priceRange">
                                <br>
                                <span class="range-price max"> @{{ priceRange | number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="form-group filter-checkbox col-md-4">
                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="room" class="room-type-checkbox" data-on="{{ __('labels.rooms')}}" data-off="{{ __('labels.rooms')}}" data-height="25" data-width="150">
                            </div>
                            <div class="form-group filter-checkbox col-md-4">
                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="suite" class="room-type-checkbox" data-on="{{ __('labels.suites')}}" data-off="{{ __('labels.suites')}}" data-height="25" data-width="150">
                                </label>
                            </div>
                            <div class="form-group filter-checkbox col-md-4">
                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="apartment" class="room-type-checkbox" data-on="{{ __('labels.apartments')}}" data-off="{{ __('labels.apartments')}}" data-height="25" data-width="150">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" ng-show="!loaded">
                        <div class="profile-info">
                            <div class="block block--short load-animate"></div>
                            <div class="block block--short load-animate"></div>
                            <div class="block block--short load-animate"></div>
                        </div>
                    </div>
                    <div class="col-md-12" ng-show="loaded">
                        <div class="row">
                            <div class="col-md-8 col-lg-8 col-sm-12">
                                <span class="filter-text">{{ __('labels.filter_rooms_by')}}&nbsp;&nbsp;</span>
                                <ul class="inclusion-filters">
                                    <li class="room-filter-li" data-include="Half Board">{{ __('labels.half_b') }}</li>
                                    <li class="room-filter-li" data-include="Full Board">{{ __('labels.full_b') }}</li>
                                    <li class="room-filter-li" data-include="Breakfast">{{ __('labels.breakfast') }}</li>
                                </ul>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-12">
                                <div class="tw-w-full lg:tw-w-1/3 tw-px-3 sessionExpiryTimerDiv" data-v-f5a8416e="" id="sessionExpiryTimerDiv">
                                    <div data-v-f5a8416e="" class="display-block tw-hidden lg:tw-block">
                                        <div data-v-f5a8416e="">
                                            <div class="tw-flex tw-items-center padd-10">
                                                <div class="tw-px-3 tw-text-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="tw-fill-current tw-h-12 tw-w-12">
                                                    <path d="m425.349 138.864 14.499-14.499 7.073 7.073c2.929 2.929 6.768 4.394 10.606 4.394s7.678-1.465 10.606-4.394c5.858-5.857 5.858-15.355 0-21.213l-35.359-35.36c-5.856-5.857-15.354-5.858-21.213 0-5.858 5.857-5.858 15.355 0 21.213l7.073 7.073-14.499 14.499c-29.631-25.994-65.012-43.47-103.135-51.168v-36.482h5c8.284 0 15-6.716 15-15s-6.716-15-15-15h-100c-8.284 0-15 6.716-15 15s6.716 15 15 15h5v36.482c-38.123 7.698-73.504 25.174-103.136 51.169l-14.499-14.499 7.073-7.073c5.858-5.858 5.858-15.355 0-21.213-5.857-5.858-15.355-5.858-21.213 0l-35.36 35.36c-5.858 5.858-5.858 15.355 0 21.213 2.929 2.929 6.768 4.394 10.606 4.394s7.678-1.464 10.606-4.394l7.073-7.073 14.499 14.499c-35.985 41.021-55.649 93.063-55.649 148.135 0 60.1 23.404 116.602 65.901 159.099s98.999 65.901 159.099 65.901 116.602-23.404 159.099-65.901 65.901-98.999 65.901-159.099c0-55.071-19.664-107.114-55.651-148.136zm-184.349-108.864h30v32.504c-4.972-.326-9.972-.504-15-.504s-10.028.178-15 .504zm15 452c-107.523 0-195-87.477-195-195s87.477-195 195-195 195 87.477 195 195-87.477 195-195 195z"></path>
                                                    <circle cx="256" cy="137" r="15"></circle>
                                                    <circle cx="256" cy="437" r="15"></circle>
                                                    <ellipse cx="181" cy="157.096" rx="15" ry="15" transform="matrix(.259 -.966 .966 .259 -17.589 291.269)"></ellipse>
                                                    <path d="m323.5 403.913c-7.174 4.142-9.633 13.316-5.49 20.49 4.142 7.174 13.316 9.633 20.49 5.49 7.174-4.142 9.633-13.316 5.49-20.49-4.142-7.174-13.316-9.632-20.49-5.49z"></path>
                                                    <ellipse cx="126.096" cy="212" rx="15" ry="15" transform="matrix(.966 -.259 .259 .966 -50.573 39.86)"></ellipse>
                                                    <path d="m393.404 349.01c-7.174-4.142-16.348-1.684-20.49 5.49s-1.684 16.348 5.49 20.49 16.348 1.684 20.49-5.49 1.684-16.348-5.49-20.49z"></path>
                                                    <circle cx="106" cy="287" r="15"></circle>
                                                    <circle cx="406" cy="287" r="15"></circle>
                                                    <ellipse cx="126.096" cy="362" rx="15" ry="15" transform="matrix(.259 -.966 .966 .259 -256.205 390.107)"></ellipse>
                                                    <path d="m393.404 224.99c7.174-4.142 9.633-13.316 5.49-20.49-4.142-7.174-13.316-9.633-20.49-5.49-7.174 4.142-9.633 13.316-5.49 20.49 4.142 7.174 13.315 9.633 20.49 5.49z"></path>
                                                    <ellipse cx="181" cy="416.904" rx="15" ry="15" transform="matrix(.966 -.259 .259 .966 -101.735 61.052)"></ellipse>
                                                    <path d="m338.5 144.106c-7.174-4.142-16.348-1.684-20.49 5.49s-1.684 16.348 5.49 20.49 16.348 1.684 20.49-5.49c4.143-7.174 1.684-16.348-5.49-20.49z"></path>
                                                    <path d="m256 242c-11.143 0-21.345 4.08-29.213 10.813l-41.229-23.803c-7.176-4.144-16.35-1.685-20.49 5.49-4.142 7.174-1.684 16.348 5.49 20.49l41.208 23.791c-.495 2.667-.766 5.411-.766 8.219 0 24.813 20.187 45 45 45s45-20.187 45-45-20.187-45-45-45zm0 60c-8.271 0-15-6.729-15-15 0-2.291.532-4.455 1.454-6.4.232-.335.456-.679.663-1.038.206-.357.384-.723.558-1.089 2.71-3.906 7.221-6.473 12.325-6.473 8.271 0 15 6.729 15 15s-6.729 15-15 15z"></path>
                                                    </svg>
                                                </div>
                                                <div class="tw-text-gray-800">
                                                    <div>
                                                        <div class="expire-text">{{ __('labels.offer_expiry')}}</div>
                                                        <strong class="sessionExpiryTimer" id="sessionExpiryTimer"></strong>
                                                    </div>
                                                </div>
                                            </div>
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
    <div id="roomFilterModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roomFilterModalLabel">{{ __('labels.room_filters')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <section class="room_filters mt-4">
                        <div class="container">
                            <div class="col-md-12  attr" >
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="slidecontainer" >
                                                <label class="filters-label">{{ __('labels.price')}}</label>
                                                <input type="range" min="500" max="500000" value="50000" class="slider" id="pirceRangeRooms" ng-change="filterByPrice(priceRange)" ng-model="priceRange">
                                                <br>
                                                <span class="range-price max"> @{{ priceRange | number }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="form-group filter-checkbox col-md-4">
                                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="room" class="room-type-checkbox" data-on="{{ __('labels.rooms')}}" data-off="{{ __('labels.rooms')}}" data-height="25" data-width="150">
                                            </div>
                                            <div class="form-group filter-checkbox col-md-4">
                                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="suite" class="room-type-checkbox" data-on="{{ __('labels.suites')}}" data-off="{{ __('labels.suites')}}" data-height="25" data-width="150">
                                                </label>
                                            </div>
                                            <div class="form-group filter-checkbox col-md-4">
                                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="apartment" class="room-type-checkbox" data-on="{{ __('labels.apartments')}}" data-off="{{ __('labels.apartments')}}" data-height="25" data-width="150">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" ng-show="!loaded">
                                        <div class="profile-info">
                                            <div class="block block--short load-animate"></div>
                                            <div class="block block--short load-animate"></div>
                                            <div class="block block--short load-animate"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" ng-show="loaded">
                                        <div class="row">
                                            <div class="col-md-8 col-lg-8 col-sm-12">
                                                <span class="filter-text">{{ __('labels.filter_rooms_by')}}&nbsp;&nbsp;</span>
                                                <ul class="inclusion-filters">
                                                    <li class="room-filter-li" data-include="Half Board">{{ __('labels.half_b') }}</li>
                                                    <li class="room-filter-li" data-include="Full Board">{{ __('labels.full_b') }}</li>
                                                    <li class="room-filter-li" data-include="Breakfast">{{ __('labels.breakfast') }}</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-4 col-lg-4 col-sm-12">
                                                <div class="tw-w-full lg:tw-w-1/3 tw-px-3 sessionExpiryTimerDiv" data-v-f5a8416e="" id="sessionExpiryTimerDiv">
                                                    <div data-v-f5a8416e="" class="display-block tw-hidden lg:tw-block">
                                                        <div data-v-f5a8416e="">
                                                            <div class="tw-flex tw-items-center padd-10">
                                                                <div class="tw-px-3 tw-text-gray-700">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="tw-fill-current tw-h-12 tw-w-12">
                                                                    <path d="m425.349 138.864 14.499-14.499 7.073 7.073c2.929 2.929 6.768 4.394 10.606 4.394s7.678-1.465 10.606-4.394c5.858-5.857 5.858-15.355 0-21.213l-35.359-35.36c-5.856-5.857-15.354-5.858-21.213 0-5.858 5.857-5.858 15.355 0 21.213l7.073 7.073-14.499 14.499c-29.631-25.994-65.012-43.47-103.135-51.168v-36.482h5c8.284 0 15-6.716 15-15s-6.716-15-15-15h-100c-8.284 0-15 6.716-15 15s6.716 15 15 15h5v36.482c-38.123 7.698-73.504 25.174-103.136 51.169l-14.499-14.499 7.073-7.073c5.858-5.858 5.858-15.355 0-21.213-5.857-5.858-15.355-5.858-21.213 0l-35.36 35.36c-5.858 5.858-5.858 15.355 0 21.213 2.929 2.929 6.768 4.394 10.606 4.394s7.678-1.464 10.606-4.394l7.073-7.073 14.499 14.499c-35.985 41.021-55.649 93.063-55.649 148.135 0 60.1 23.404 116.602 65.901 159.099s98.999 65.901 159.099 65.901 116.602-23.404 159.099-65.901 65.901-98.999 65.901-159.099c0-55.071-19.664-107.114-55.651-148.136zm-184.349-108.864h30v32.504c-4.972-.326-9.972-.504-15-.504s-10.028.178-15 .504zm15 452c-107.523 0-195-87.477-195-195s87.477-195 195-195 195 87.477 195 195-87.477 195-195 195z"></path>
                                                                    <circle cx="256" cy="137" r="15"></circle>
                                                                    <circle cx="256" cy="437" r="15"></circle>
                                                                    <ellipse cx="181" cy="157.096" rx="15" ry="15" transform="matrix(.259 -.966 .966 .259 -17.589 291.269)"></ellipse>
                                                                    <path d="m323.5 403.913c-7.174 4.142-9.633 13.316-5.49 20.49 4.142 7.174 13.316 9.633 20.49 5.49 7.174-4.142 9.633-13.316 5.49-20.49-4.142-7.174-13.316-9.632-20.49-5.49z"></path>
                                                                    <ellipse cx="126.096" cy="212" rx="15" ry="15" transform="matrix(.966 -.259 .259 .966 -50.573 39.86)"></ellipse>
                                                                    <path d="m393.404 349.01c-7.174-4.142-16.348-1.684-20.49 5.49s-1.684 16.348 5.49 20.49 16.348 1.684 20.49-5.49 1.684-16.348-5.49-20.49z"></path>
                                                                    <circle cx="106" cy="287" r="15"></circle>
                                                                    <circle cx="406" cy="287" r="15"></circle>
                                                                    <ellipse cx="126.096" cy="362" rx="15" ry="15" transform="matrix(.259 -.966 .966 .259 -256.205 390.107)"></ellipse>
                                                                    <path d="m393.404 224.99c7.174-4.142 9.633-13.316 5.49-20.49-4.142-7.174-13.316-9.633-20.49-5.49-7.174 4.142-9.633 13.316-5.49 20.49 4.142 7.174 13.315 9.633 20.49 5.49z"></path>
                                                                    <ellipse cx="181" cy="416.904" rx="15" ry="15" transform="matrix(.966 -.259 .259 .966 -101.735 61.052)"></ellipse>
                                                                    <path d="m338.5 144.106c-7.174-4.142-16.348-1.684-20.49 5.49s-1.684 16.348 5.49 20.49 16.348 1.684 20.49-5.49c4.143-7.174 1.684-16.348-5.49-20.49z"></path>
                                                                    <path d="m256 242c-11.143 0-21.345 4.08-29.213 10.813l-41.229-23.803c-7.176-4.144-16.35-1.685-20.49 5.49-4.142 7.174-1.684 16.348 5.49 20.49l41.208 23.791c-.495 2.667-.766 5.411-.766 8.219 0 24.813 20.187 45 45 45s45-20.187 45-45-20.187-45-45-45zm0 60c-8.271 0-15-6.729-15-15 0-2.291.532-4.455 1.454-6.4.232-.335.456-.679.663-1.038.206-.357.384-.723.558-1.089 2.71-3.906 7.221-6.473 12.325-6.473 8.271 0 15 6.729 15 15s-6.729 15-15 15z"></path>
                                                                    </svg>
                                                                </div>
                                                                <div class="tw-text-gray-800">
                                                                    <div>
                                                                        <div class="expire-text">{{ __('labels.offer_expiry')}}</div>
                                                                        <strong class="sessionExpiryTimer" id="sessionExpiryTimer"></strong>
                                                                    </div>
                                                                </div>
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
    </div>

    <!-- Static Hotel Rooms ON Page Load -->



    <section class="room_type_data mt-4" ng-if="!loaded">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- New Html 1 Dec  -->
                    <div id="room-list">
                        @foreach($roomImages as $r_key => $room)
                        <div class="row rooms-tr" data-name="{{ $room['name']}}" >
                            <div class="col-lg-12">
                                <div class='rooms-options'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class='room-list-title section_title'> {{ $room['name']}}</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 15px;">
                                        <div class="col-lg-4">
                                            <div class="room-photo no-price">
                                                @php $r_images = array(); $roomImagesCount = 0; @endphp
                                                @if($room_img['images'] && $room_img['images'] != null && !empty($room_img['images']))
                                                @php $r_images = unserialize($room_img->images); @endphp
                                                @endif


                                                <div id="custCarouse{{$r_key}}" class="carousel slide" data-ride="carousel" align="center">
                                                    <div class="carousel-inner" >
                                                        @foreach($r_images as $i_key => $image)
                                                            @if(strpos($image, 'http') !== false || strpos($image, 'www') !== false)
                                                                <img ng-src="{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                            @else
                                                                <img ng-src="{{env('AWS_BUCKET_URL')}}/{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                            @endif
                                                        @endforeach
                                                    </div>

                                                    <!-- Thumbnails -->

                                                    <ol class="carousel-indicators list-inline" >
                                                        @foreach($r_images as $i_key => $image)
                                                        <li class="list-inline-item @if($i_key ==0) active @endif" > 
                                                            <a id="carousel-selector-{{ $i_key}}" class="selected" data-slide-to="{{ $i_key}}" data-target="#custCarouse{{ $r_key}}">
                                                                @if(strpos($image, 'http') !== false || strpos($image, 'www') !== false)
                                                                    <img ng-src="{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                @else
                                                                    <img ng-src="{{env('AWS_BUCKET_URL')}}/{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                @endif
                                                            </a>
                                                        </li>
                                                        @endforeach
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-8" >
                                            <div class="showOptionsDesktop">
                                                <div class="row" style="margin:8px;border-bottom:1px solid #ccc;">
                                                    <div class="col-lg-4">
                                                        <span class='room-label-title'>{{ __('labels.what_included')}}</span>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <span class='room-label-title'>{{ __('labels.room_details')}}</span>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <span class='room-label-title'>{{ __('labels.total_cost')}}</span>
                                                    </div>
                                                </div>

                                                <div style="min-height: 100px;margin:10px;padding-top:0px;padding-bottom: 0px;border-bottom:1px solid #ccc;" class="row rooms-tr-meal price_dat_table sub_room-pr pr price-@{{ sub_room.FinalPrice}} index-@{{ room_key}} td-inclusion" data-meal="@{{ sub_room.RatePlanName || 'No Meals' }}"  data-price="@{{ sub_room.FinalPrice}}" data-index="@{{ room_key}}" data-currency="@{{ sub_room.Price.CurrencyCode}}" data-room="@{{ sub_room.RoomIndex}}" data-include="@{{ tdClass(sub_room.Inclusion)}}">
                                                    <div class="col-lg-4">
                                                        @if(isset($room['ameneties']) && !empty($room['ameneties']))
                                                        @php $room_ameneties = json_decode($room['ameneties'], true); @endphp
                                                        <ul >
                                                            @foreach($room_ameneties as $r_fac)
                                                            <li class="room-facility-text" ><i class="fa fa-check"></i>{{$r_fac}}</li>
                                                            @endforeach
                                                        </ul>
                                                        @endif

                                                    </div>
                                                    <div class="col-lg-4">
                                                        @if(isset($room['bed_type']) && !empty($room['bed_type']))
                                                        @php $bed_type = json_decode($room['bed_type'], true); @endphp
                                                        @if(isset($bed_type['beds']['BedName']))
                                                        <i class="fa fa-bed" aria-hidden="true"></i>&nbsp;{{ $bed_type['beds']['BedName']}}
                                                        @endif

                                                        @if(isset($bed_type['room_size']['eb']))
                                                        <br>
                                                        {{ __('labels.extra_bed')}}: {{ $bed_type['room_size']['eb']}}
                                                        @endif
                                                        @endif

                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div style="min-height: 100px;padding:0px;" class="price_dat_table select_room_data td-inclusion">
                                                            <div class="content-section">
                                                                <div class="content-info side">
                                                                    <div class="block block--long load-animate"></div>
                                                                    <div class="block block--long load-animate"></div>
                                                                    <div class="block block--long load-animate"></div>
                                                                    <div class="block block--long load-animate"></div>
                                                                </div>
                                                            </div>
                                                            <!-- <button type="button"  class="btn btn-primary show-price-room" > Show Prices</button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <!-- Code Ends  -->
    </section>

    <!-- Static Data End -->

    <!-- Rooms From API -->

    <section class="room_type_data mt-4" ng-if="loaded">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" id="roomCount" value="{{ $input_data['roomCount']}}">
                    <input type="hidden" id="supplierIds" value="{{ $supplierIds}}">
                    @if($input_data['roomCount'] == '1')
                    <!-- New Html 1 Dec  -->
                    <div ng-show="loaded" id="room-list">
                        <div class="row rooms-tr" data-price="@{{ roomList.FinalPrice}}" data-meal="@{{ roomList.RatePlanName || 'No Meals' }}" ng-repeat="roomList in rooms" data-name="@{{ roomList.RoomTypeName}}" ng-init="room_key = $index">
                            <div class="col-lg-12">
                                <div class='rooms-options'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class='room-list-title section_title'> @{{ roomList.RoomTypeName}}</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 15px;">
                                        <div class="col-lg-3">
                                            <div class="room-photo">
                                                <span ng-if="roomList.sub_rooms[0].images.length && (roomList.sub_rooms[0].images[0].indexOf('http') != - 1 || roomList.sub_rooms[0].images[0].indexOf('www') != - 1)">
                                                    <img style="width:100%;" ng-click="showRoomDetails($index)" src="@{{ roomList.sub_rooms[0].images[0]}}" alt="@{{ roomList.RoomTypeName}}" class="img-responsive" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" />
                                                </span>

                                                <span ng-if="roomList.sub_rooms[0].images.length && (roomList.sub_rooms[0].images[0].indexOf('http') == - 1 && roomList.sub_rooms[0].images[0].indexOf('www') == - 1)">
                                                    <img style="width:100%;" ng-click="showRoomDetails($index)" src="{{env('AWS_BUCKET_URL')}}/@{{ roomList.sub_rooms[0].images[0]}}" alt="@{{ roomList.RoomTypeName}}" class="img-responsive" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" />
                                                </span>

                                                <img  style="width:100%;" ng-click="showRoomDetails($index)" src="https://b2b.tektravels.com/Images/HotelNA.jpg" alt="@{{ roomList.RoomTypeName}}" class="img-responsive" ng-if="!roomList.sub_rooms[0].images.length" />
                                            </div>
                                            <div style="padding:10px 20px 20px 20px;">
                                                <div class="tw-text-sm">
                                                    <!-- <ul class="list-inline room_influences">
                                                        <li ng-repeat="roomBd in roomList.sub_rooms[0].BedTypes">@{{ roomBd.BedTypeDescription}}</li>
                                                        <li ng-repeat="am in roomList.Inclusion">@{{ am}}</li>
                                                    </ul> -->
                                                    <ul ng-if="sub_room.Amenity.length">
                                                            <li class="room-facility-text" ng-repeat="am in sub_room.Amenity track by $index" ng-if="$index < 4"><i class="fa fa-check"></i>@{{ am}}</li>
                                                        </ul>

                                                        <ul ng-if="!sub_room.Amenity.length">
                                                            <li class="room-facility-text" ng-repeat="am in sub_room.Amenities track by $index" ng-if="$index < 4"><i class="fa fa-check"></i>@{{ am}}</li>
                                                        </ul>

                                                        <ul ng-if="!sub_room.Amenity.length && !sub_room.Amenities.length">
                                                            <li class="room-facility-text" ><i class="fa fa-check"></i>Room Only</li>
                                                        </ul>

                                                    <div class="more_room_detail">
                                                        <a href='javascript:void(0);' ng-click="showRoomDetails($index)">{{ __('labels.more_about_room')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Room Details Modal -->
                                        <div id="roomModal_@{{ $index}}" class="modal fade" role="dialog">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <p class="room-heading modal-room-name"> <i class="fa fa-bed" aria-hidden="true"></i> @{{ roomList.RoomTypeName}} <button type="button" class="close" data-dismiss="modal">&times;</button></p>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div id="custCarouse_@{{ $index}}" class="carousel slide" data-ride="carousel" align="center">
                                                                    <ol class="carousel-indicators" >
                                                                        <li  ng-repeat="imgR in ::roomList.sub_rooms[0].images track by $index" ng-init="image_key = $index" data-target="#custCarouse_@{{ room_key}}"  data-slide-to="@{{ image_key}}" ng-class="image_key < 1 ? 'active' : ''" ng-if="imgR.indexOf('_t') === - 1"></li>
                                                                    </ol>
                                                                    <div class="carousel-inner" >
                                                                        <div ng-repeat="imgR in ::roomList.sub_rooms[0].images track by $index"  ng-init="image_key_s = $index" ng-class="image_key_s < 1 ? 'carousel-item active' : 'carousel-item'" ng-if="imgR.indexOf('_t') === - 1"> 
                                                                            <img class="room-image-slider" src="@{{ ::imgR}}"  alt="hotel" ng-if="imgR.indexOf('http') != - 1 || imgR.indexOf('http') != - 1" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';"> 

                                                                            <img class="room-image-slider" src="{{env('AWS_BUCKET_URL')}}/@{{ ::imgR}}"  alt="hotel" ng-if="imgR.indexOf('http') == - 1 && imgR.indexOf('http') == - 1" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">                                                                     
                                                                        </div>
                                                                        <div ng-if="!roomList.sub_rooms[0].images.length" class="carousel-item active"> 
                                                                            <img class="room-image-slider" src="https://b2b.tektravels.com/Images/HotelNA.jpg"  alt="hotel">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="col-md-6 amenity" ng-repeat="am in roomList.sub_rooms[0].Amenity track by $index">
                                                                        <span><i class="fa fa-check" aria-hidden="true"></i>&nbsp;@{{ am}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p class="modal-cancel-policy alert alert-info">{{ __('labels.cancel_policy')}}: @{{ roomList.sub_rooms[0].CancellationPolicy}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-9" >
                                            <div class="showOptionsDesktop">
                                                <div class="row" style="margin:8px;border-bottom:1px solid #ccc;">
                                                    <div class="col-lg-6">
                                                        <span class='room-label-title'>{{ __('labels.what_included')}}</span>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class='room-label-title'>{{ __('labels.total_cost')}}</span>
                                                    </div>
                                                    <div class="col-lg-3"></div>
                                                </div>

                                                <div style="min-height: 100px;margin:10px;padding-top:0px;padding-bottom: 0px;border-bottom:1px solid #ccc;" class="row rooms-tr-meal price_dat_table sub_room-pr pr price-@{{ sub_room.FinalPrice}} index-@{{ room_key}} td-inclusion" data-meal="@{{ sub_room.RatePlanName || 'No Meals' }}"  ng-repeat="sub_room in roomList.sub_rooms" data-price="@{{ sub_room.FinalPrice}}" data-index="@{{ room_key}}" data-currency="@{{ sub_room.Price.CurrencyCode}}" data-room="@{{ sub_room.RoomIndex}}" data-include="@{{ tdClass(sub_room.Inclusion)}}">
                                                    <div class="col-lg-6 pl-0">
                                                        

                                                        <span class="room-facility-text" ng-repeat="in in sub_room.Inclusion track by $index" ng-if="$index < 1"><i class="fa fa-check"></i>@{{ in}}</span>

                                                        <ul ng-if="sub_room.CancellationPolicies.length" ng-repeat="cancelationT in sub_room.CancellationPolicies|limitTo:1">
                                                            <li style="cursor: pointer;" ng-mouseover="changeCancelOne(sub_room.RoomIndex)" ng-mouseleave="changeCancelOneOut(sub_room.RoomIndex)" data-roomI="@{{sub_room.RoomIndex}}" class="room-facility-text cancellation-text" ng-if="cancelationT.Charge == 0"> <a data-toggle='modal' data-target='#roomCancel_@{{ sub_room.RoomIndex}}'><i class="fa fa-info-circle" aria-hidden="true"></i></a> {{ __('labels.refund_before')}} @{{ cancelationT.ToDate | date:'medium'}}</li>
                                                            <li style="cursor: pointer;" ng-mouseover="changeCancelTwo(sub_room.RoomIndex)" ng-mouseleave="changeCancelTwoOut(sub_room.RoomIndex)" data-roomIR="@{{sub_room.RoomIndex}}" class="room-facility-text cancellation-text-all" ng-if="cancelationT.Charge != 0"> <a data-toggle='modal' data-target='#roomCancel_@{{ sub_room.RoomIndex}}'><i class="fa fa-info-circle" aria-hidden="true"></i></a> {{ __('labels.cancel_policies')}}</li>
                                                        </ul>

                                                        <!-- Cancellation Popup -->

                                                            <table  class="cancel_room_table" id="cancellationPopup_@{{ sub_room.RoomIndex }}" style="display: none;">
                                                                <tbody>
                                                                    
                                                                    <tr>
                                                                        <th align="left">{{ __('labels.cancel_after')}}</th>
                                                                        <th align="left">{{ __('labels.cancel_before')}}</th>
                                                                        <th align="left">{{ __('labels.cancel_charges')}}</th>
                                                                    </tr>
                                                                    
                                                                    <tr ng-repeat="cancelationTR in sub_room.CancellationPolicies">
                                                                        
                                                                        <td align="left">@{{ cancelationTR.FromDate | date:'medium' }}</td>
                                                                        <td align="left"> @{{ cancelationTR.ToDate | date:'medium'}}</td>
                                                                        <td align="left" ng-if="cancelationTR.ChargeType == 1">
                                                                            @{{ cancelationTR.Currency }} @{{ cancelationTR.Charge }}
                                                                        </td>
                                                                        <td align="left" ng-if="cancelationTR.ChargeType == 2">
                                                                            @{{ cancelationTR.Charge }}%
                                                                        </td>
                                                                        <td align="left" ng-if="cancelationTR.ChargeType == 3">
                                                                            @{{ cancelationTR.Charge }} {{ __('labels.night_charge')}}
                                                                        </td>
                                                                    </tr> 
                                                                    <tr>
                                                                        <td colspan="3" class="txtcnclleft">{{ __('labels.no_show')}}</td>
                                                                    </tr>  
                                                                    <tr>
                                                                        <td colspan="3" class="txtcnclleft">{{ __('labels.early_check')}}</td>
                                                                    </tr>                              
                                                                    
                                                                </tbody>

                                                            </table>
                                                        </br></br>
                                                    </div>
                                                    <div id="roomCancel_@{{ sub_room.RoomIndex}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog  modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h3 ng-bind="roomList.RoomTypeName"></h3>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p ng-bind="sub_room.CancellationPolicy"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="roomInclusion_@{{ sub_room.RoomIndex}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog  modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h3 ng-bind="roomList.RoomTypeName"></h3>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row" ng-if="sub_room.Amenity.length">
                                                                        <div class="col-md-4" ng-repeat="am in sub_room.Amenity track by $index">
                                                                            <li class="room-facility-text"  ><i class="fa fa-check"></i>@{{ am}}</li>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" ng-if="!sub_room.Amenity.length">
                                                                        <div class="col-md-4" ng-repeat="am in sub_room.Amenities track by $index">
                                                                            <li class="room-facility-text"  ><i class="fa fa-check"></i>@{{ am}}</li>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="text-success room-price">@{{ sub_room.Price.CurrencyCode}} @{{ sub_room.FinalPrice | number: 2 }}</span> 
                                                        <div class="room-stay-text">
                                                            {{ __('labels.total_cost')}} {{ $input_data['NoOfNights'] }} {{ __('labels.nights')}}
                                                            <br>
                                                            {{ $input_data['roomsGuests']}} <span class="flight_hotel_price_text">({{ __('labels.bundle_price')}})</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <!----> 
                                                        <div style="min-height: 100px;padding:0px;" class="price_dat_table select_room_data td-inclusion">
                                                            <div ng-if="sub_room.FinalPrice > lottery_limit" >
                                                                <img  src="/images/lottery-icon.gif" style="width:40px;" />
                                                                <span style="font-size:12px;">{{ __('labels.for_cashback')}}</span>
                                                            </div>
                                                            <form method="POST" target="_blank" action="/flight-room/{{$traceId}}/{{$referral}}/{{$search_id_hotel}}/{{$flightTraceId}}/{{$flight_search_id}}/{{$flightId}}/{{$rflightId}}">
                                                                @csrf
                                                                <input type="hidden" name="hotelName" value="{{ $hotel['static_data']['hotel_name']}}">
                                                                <input type="hidden" name="hotelCode" value="{{$hotel['TBO_data']['HotelCode']}}">
                                                                <input type="hidden" name="hotelIndex" value="{{$hotel['TBO_data']['ResultIndex']}}">
                                                                <input type="hidden" name="category" value="@{{ sub_room.CategoryId}}">
                                                                <input type="hidden" name="room[RoomIndex]" value="@{{ sub_room.RoomIndex}}">
                                                                <input type="hidden" name="room[RoomTypeCode]" value="@{{ sub_room.RoomTypeCode}}">
                                                                <input type="hidden" name="room[RoomTypeName]" value="@{{ sub_room.RoomTypeName}}">
                                                                <input type="hidden" name="room[RatePlanCode]" value="@{{ sub_room.RatePlanCode}}">
                                                                <input type="hidden" name="room[BedTypeCode]" value="@{{ sub_room.BedTypeCode}}">
                                                                <input type="hidden" name="room[SmokingPreference]" value="@{{ sub_room.SmokingPreference}}">
                                                                <input type="hidden" name="room[Supplements]" value="@{{ sub_room.Supplements}}">
                                                                <input type="hidden" name="room[Price][CurrencyCode]" value="@{{ sub_room.Price.CurrencyCode}}">
                                                                <input type="hidden" name="room[Price][RoomPrice]" value="@{{ sub_room.Price.RoomPrice}}">
                                                                <input type="hidden" name="room[Price][Tax]" value="@{{ sub_room.Price.Tax}}">
                                                                <input type="hidden" name="room[Price][ExtraGuestCharge]" value="@{{ sub_room.Price.ExtraGuestCharge}}">
                                                                <input type="hidden" name="room[Price][ChildCharge]" value="@{{ sub_room.Price.ChildCharge}}">
                                                                <input type="hidden" name="room[Price][OtherCharges]" value="@{{ sub_room.Price.OtherCharges}}">
                                                                <input type="hidden" name="room[Price][Discount]" value="@{{ sub_room.Price.Discount}}">
                                                                <input type="hidden" name="room[Price][PublishedPrice]" value="@{{ sub_room.Price.PublishedPrice}}">
                                                                <input type="hidden" name="room[Price][PublishedPriceRoundedOff]" value="@{{ sub_room.Price.PublishedPriceRoundedOff}}">
                                                                <input type="hidden" name="room[Price][OfferedPrice]" value="@{{ sub_room.Price.OfferedPrice}}">
                                                                <input type="hidden" name="room[Price][OfferedPriceRoundedOff]" value="@{{ sub_room.Price.OfferedPriceRoundedOff}}">
                                                                <input type="hidden" name="room[Price][AgentCommission]" value="@{{ sub_room.Price.AgentCommission}}">
                                                                <input type="hidden" name="room[Price][AgentMarkUp]" value="@{{ sub_room.Price.AgentMarkUp}}">
                                                                <input type="hidden" name="room[Price][ServiceTax]" value="@{{ sub_room.Price.ServiceTax}}">
                                                                <input type="hidden" name="room[Price][TCS]" value="@{{ sub_room.Price.TCS}}">
                                                                <input type="hidden" name="room[Price][TDS]" value="@{{ sub_room.Price.TDS}}">
                                                                <input type="hidden" name="room[LastCancellationDate]" value="@{{ sub_room.LastCancellationDate}}">
                                                                <button type="submit"  class="btn btn-primary" >{{ __('labels.book_now')}}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion showOptionsMobile" id="accordionExample@{{room_key}}">
                                                <!--                                            <div class="card">-->
                                                <div class="row">

                                                    <div class="col-12 mobile-options-list" >
                                                        <button class="btn btn-primary pull-right" type="button" data-toggle="collapse" data-target="#collapseOne@{{room_key}}" aria-expanded="true" aria-controls="collapseOne">
                                                            {{ __('labels.show_op')}} <span class='fa fa-chevron-down'></span>
                                                        </button>
                                                    </div>
                                                    <div class="col-12">
                                                        <div id="collapseOne@{{room_key}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample@{{room_key}}">
                                                            <!--                                                    <div class="card-body">-->
                                                            <div style="border-bottom:0px solid #ccc;min-height: 100px;padding:10px;" class="row rooms-tr-meal sub_room-pr pr price-@{{ sub_room.FinalPrice}} index-@{{ room_key}} td-inclusion" data-meal="@{{ sub_room.RatePlanName || 'No Meals' }}"  ng-repeat="sub_room in roomList.sub_rooms" data-price="@{{ sub_room.FinalPrice}}" data-index="@{{ room_key}}" data-currency="@{{ sub_room.Price.CurrencyCode}}" data-room="@{{ sub_room.RoomIndex}}" data-include="@{{ tdClass(sub_room.Inclusion)}}">
                                                                <div class="col-12 padd-right">
                                                                    <!-- <span class="" ng-repeat="am in sub_room.Inclusion track by $index">@{{ am}}</span><br /> -->
                                                                    <ul>
                                                                        <li class="room-facility-text" ng-repeat="am in sub_room.Inclusion track by $index"><i class="fa fa-check"></i>@{{ am}}</li>
                                                                    </ul>
                                                                    <span ng-if="!sub_room.Inclusion.length">Room Only</span>
                                                                    <a class="tw-text-sm" href="javascript:void(0);" data-toggle='modal' data-target='#roomCancel1_@{{ sub_room.RoomIndex}}'>{{ __('labels.cancel_policy')}}</a> 
                                                                </div>
                                                                <div id="roomCancel1_@{{ sub_room.RoomIndex}}" class="modal fade" role="dialog">
                                                                    <div class="modal-dialog   modal-dialog-centered modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h3 ng-bind="sub_room.RoomTypeName"></h3>
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p ng-bind="sub_room.CancellationPolicy"></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <span class="text-success room-price">@{{ sub_room.Price.CurrencyCode}} @{{ sub_room.FinalPrice | number: 2 }}</span> 
                                                                </div>
                                                                <div class="col-6 text-right">
                                                                    <!----> 
                                                                    <div  class="select_room_data td-inclusion">
                                                                        <div ng-if="sub_room.FinalPrice > lottery_limit" >
                                                                            <img  src="/images/lottery-icon.gif" style="width:40px;" />
                                                                            <span style="font-size:12px;">{{ __('labels.for_cashback')}}</span>
                                                                        </div>
                                                                        <form method="POST" target="_blank" action="/flight-room/{{$traceId}}/{{$referral}}/{{$search_id_hotel}}/{{$flightTraceId}}/{{$flight_search_id}}/{{$flightId}}/{{$rflightId}}">
                                                                            @csrf
                                                                            <input type="hidden" name="category" value="@{{ sub_room.CategoryId}}">
                                                                            <input type="hidden" name="hotelName" value="{{ $hotel['static_data']['hotel_name']}}">
                                                                            <input type="hidden" name="hotelCode" value="{{$hotel['TBO_data']['HotelCode']}}">
                                                                            <input type="hidden" name="hotelIndex" value="{{$hotel['TBO_data']['ResultIndex']}}">
                                                                            <input type="hidden" name="room[RoomIndex]" value="@{{ sub_room.RoomIndex}}">
                                                                            <input type="hidden" name="room[RoomTypeCode]" value="@{{ sub_room.RoomTypeCode}}">
                                                                            <input type="hidden" name="room[RoomTypeName]" value="@{{ sub_room.RoomTypeName}}">
                                                                            <input type="hidden" name="room[RatePlanCode]" value="@{{ sub_room.RatePlanCode}}">
                                                                            <input type="hidden" name="room[BedTypeCode]" value="@{{ sub_room.BedTypeCode}}">
                                                                            <input type="hidden" name="room[SmokingPreference]" value="@{{ sub_room.SmokingPreference}}">
                                                                            <input type="hidden" name="room[Supplements]" value="@{{ sub_room.Supplements}}">
                                                                            <input type="hidden" name="room[Price][CurrencyCode]" value="@{{ sub_room.Price.CurrencyCode}}">
                                                                            <input type="hidden" name="room[Price][RoomPrice]" value="@{{ sub_room.Price.RoomPrice}}">
                                                                            <input type="hidden" name="room[Price][Tax]" value="@{{ sub_room.Price.Tax}}">
                                                                            <input type="hidden" name="room[Price][ExtraGuestCharge]" value="@{{ sub_room.Price.ExtraGuestCharge}}">
                                                                            <input type="hidden" name="room[Price][ChildCharge]" value="@{{ sub_room.Price.ChildCharge}}">
                                                                            <input type="hidden" name="room[Price][OtherCharges]" value="@{{ sub_room.Price.OtherCharges}}">
                                                                            <input type="hidden" name="room[Price][Discount]" value="@{{ sub_room.Price.Discount}}">
                                                                            <input type="hidden" name="room[Price][PublishedPrice]" value="@{{ sub_room.Price.PublishedPrice}}">
                                                                            <input type="hidden" name="room[Price][PublishedPriceRoundedOff]" value="@{{ sub_room.Price.PublishedPriceRoundedOff}}">
                                                                            <input type="hidden" name="room[Price][OfferedPrice]" value="@{{ sub_room.Price.OfferedPrice}}">
                                                                            <input type="hidden" name="room[Price][OfferedPriceRoundedOff]" value="@{{ sub_room.Price.OfferedPriceRoundedOff}}">
                                                                            <input type="hidden" name="room[Price][AgentCommission]" value="@{{ sub_room.Price.AgentCommission}}">
                                                                            <input type="hidden" name="room[Price][AgentMarkUp]" value="@{{ sub_room.Price.AgentMarkUp}}">
                                                                            <input type="hidden" name="room[Price][ServiceTax]" value="@{{ sub_room.Price.ServiceTax}}">
                                                                            <input type="hidden" name="room[Price][TCS]" value="@{{ sub_room.Price.TCS}}">
                                                                            <input type="hidden" name="room[Price][TDS]" value="@{{ sub_room.Price.TDS}}">
                                                                            <input type="hidden" name="room[LastCancellationDate]" value="@{{ sub_room.LastCancellationDate}}">
                                                                            <button type="submit"  class="btn btn-primary" >{{ __('labels.book_now')}}</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="room-stay-text">
                                                                        {{ __('labels.total_cost')}} {{ $input_data['NoOfNights'] }} {{ $input_data['NoOfNights']}}
                                                                        <br>
                                                                        {{ $input_data['roomsGuests']}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--                                                    </div>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="inner_room_type_data" ng-show="loaded" id="room-list">
                        <div class="inner_about_hotels_data attr">
                            <div class="selected-rooms" ng-show="!error || error == ''">

                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="row" >
                                            <div class="col-lg-4 col-sm-12" style="padding:15px;">
                                                <h3 style="font-size:25px;font-weight: 700;">{{ __('labels.total_cost')}} {{ $input_data['roomCount']}} {{ __('labels.rooms')}}:- </h3>
                                            </div>
                                            <div class="col-lg-4 col-6 text-center" style="padding:15px;">
                                                <span style="font-size:25px;font-weight: 700;" id="total-price"></span>&nbsp;<span class="flight_hotel_price_text" style="display: none;">({{ __('labels.bundle_price')}})</span>
                                            </div>
                                            <div class="col-lg-4 col-6" style="padding:15px;">
                                                <form target="_blank" method="POST" action="/flight-room/{{$traceId}}/{{$referral}}/{{$search_id_hotel}}/{{$flightTraceId}}/{{$flight_search_id}}/{{$flightId}}/{{$rflightId}}">
                                                    <button type="submit" class="btn btn-primary book-btn pull-right" >{{ __('labels.book_now')}}</button>
                                                    <div class="" ng-repeat="rCount in roomCount">
                                                        @csrf
                                                        <input type="hidden" id="room_@{{rCount}}_category" name="category" value="">
                                                        <input type="hidden" name="hotelName" value="{{ $hotel['static_data']['hotel_name']}}">
                                                        <input type="hidden" name="hotelCode" value="{{$hotel['TBO_data']['HotelCode']}}">
                                                        <input type="hidden" name="hotelIndex" value="{{$hotel['TBO_data']['ResultIndex']}}">
                                                        <input type="hidden" id="room_@{{rCount}}_RoomIndex" name="room[@{{rCount}}][RoomIndex]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_RoomTypeCode" name="room[@{{rCount}}][RoomTypeCode]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_RoomTypeName" name="room[@{{rCount}}][RoomTypeName]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_RatePlanCode" name="room[@{{rCount}}][RatePlanCode]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_BedTypeCode" name="room[@{{rCount}}][BedTypeCode]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_SmokingPreference" name="room[@{{rCount}}][SmokingPreference]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_Supplements" name="room[@{{rCount}}][Supplements]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_CurrencyCode" name="room[@{{rCount}}][Price][CurrencyCode]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_RoomPrice" name="room[@{{rCount}}][Price][RoomPrice]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_Tax" name="room[@{{rCount}}][Price][Tax]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_ExtraGuestCharge" name="room[@{{rCount}}][Price][ExtraGuestCharge]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_ChildCharge" name="room[@{{rCount}}][Price][ChildCharge]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_OtherCharges" name="room[@{{rCount}}][Price][OtherCharges]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_Discount" name="room[@{{rCount}}][Price][Discount]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_PublishedPrice" name="room[@{{rCount}}][Price][PublishedPrice]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_PublishedPriceRoundedOff" name="room[@{{rCount}}][Price][PublishedPriceRoundedOff]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_OfferedPrice" name="room[@{{rCount}}][Price][OfferedPrice]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_OfferedPriceRoundedOff" name="room[@{{rCount}}][Price][OfferedPriceRoundedOff]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_AgentCommission" name="room[@{{rCount}}][Price][AgentCommission]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_AgentMarkUp" name="room[@{{rCount}}][Price][AgentMarkUp]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_ServiceTax" name="room[@{{rCount}}][Price][ServiceTax]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_TCS" name="room[@{{rCount}}][Price][TCS]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_TDS" name="room[@{{rCount}}][Price][TDS]" value="">
                                                        <input type="hidden" name="room[@{{rCount}}][LastCancellationDate]" value="">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <hr/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" id="traceId" value="{{ $traceId}}">
                                <input type="hidden" id="checkInDate" value="{{ $input_data['departdate']}}">
                                <input type="hidden" id="checkOutDate" value="{{ $input_data['returndate']}}">
                                <input type="hidden" id="referral" value="{{ $referral}}">
                                <div class="col-lg-12">
                                    <div class="responsive__tabs" id="hotelRoomsList">
                                        <ul class="scrollable-tabs" >
                                            <li   ng-repeat="rmCount in roomCount" class="nav-item" >
                                                <a class="nav-link  @{{ (rmCount == 0)?'active':''}}" data-toggle="tab" href="#RoomContent@{{rmCount}}">{{ __('labels.room')}} <span ng-bind="rmCount + 1"></span></a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div ng-repeat="rCount in roomCount" id="RoomContent@{{rCount}}" class="tab-pane  @{{ (rCount == 0)?'active':''}}">
                                                <div class="row roomsList"> 
                                                    <div class="col-lg-12" style="font-weight: 600;">
                                                        <div style="background:#ededed;padding:10px;">
                                                            Room <span ng-bind="rCount + 1"></span>: 
                                                            <span id="room-@{{rCount}}">
                                                                @{{rooms['rooms_' + rCount][0].RoomTypeName}}
                                                            </span>
                                                            <br>
                                                            <span>(Adults @{{ hotelSearchInput[rCount]['adults']}}, Childrens @{{ hotelSearchInput[rCount]['childs']}})</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 50%;">{{ __('labels.room_type')}}</th>
                                                                <th style="width: 20%;">{{ __('labels.what_included')}}</th>
                                                                <th style="width: 20%;">{{ __('labels.total_cost')}}</th>
                                                                <th style="width: 10%;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="room in rooms['rooms_' + rCount]" ng-class="rCount == 0 ? 'first-column' : (combination == 'fixed') ? 'trow not-first-column' : ''" id="tr-@{{rCount}}-@{{ room.RoomIndex}}" class="rooms-tr-meal-twice" data-price="@{{ room.FinalPrice}}" data-meal="@{{ roomList.RatePlanName || 'No Meals' }}" data-include="@{{ tdClass(room.Inclusion)}}" data-name="@{{ room.RoomTypeName}}" ng-init="room_key_index = $index">
                                                                <td >
                                                                    <span ng-bind="room.RoomTypeName"></span>
                                                                    <br>
                                                                    <div class="room-photo">
                                                                        <span ng-if="room.images.length && (room.images[0].indexOf('http') != - 1 || room.images[0].indexOf('www') != - 1)">
                                                                            <img ng-click="showRoomDetails($index)" src="@{{ room.images[0]}}" alt="@{{ roomList.RoomTypeName}}" class="img-responsive mul-rooms" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" />
                                                                        </span>
                                                                        <span ng-if="room.images.length && (room.images[0].indexOf('http') == - 1 && room.images[0].indexOf('www') == - 1)">
                                                                            <img ng-click="showRoomDetails($index)" src="{{env('AWS_BUCKET_URL')}}/@{{ room.images[0]}}" alt="@{{ roomList.RoomTypeName}}" class="img-responsive mul-rooms" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" />
                                                                        </span>

                                                                        <img  ng-click="showRoomDetails($index)" src="https://b2b.tektravels.com/Images/HotelNA.jpg" alt="@{{ roomList.RoomTypeName}}" class="img-responsive mul-rooms" ng-if="!room.images.length" />
                                                                    </div>
                                                                    <br>
                                                                    <a href="javascript:void(0);" class="tw-text-sm" ng-click="showRoomDetails($index)">{{ __('labels.more_about_room')}}</a>
                                                                </td>
                                                                <td>
                                                                    <ul ng-if="!room.Amenity.length && !room.Amenities.length">
                                                                        <li class="room-facility-text"><i class="fa fa-check"></i>Room Only</li>
                                                                    </ul>
                                                                    <br>
                                                                    <ul>
                                                                        <li class="room-facility-text" ng-repeat="am in room.Amenity track by $index" ng-if="$index < 4"><i class="fa fa-check"></i>@{{ am}}</li>
                                                                    </ul>

                                                                    <ul ng-if="!room.Amenity.length">
                                                                        <li class="room-facility-text" ng-repeat="am in room.Amenities track by $index" ng-if="$index < 4"><i class="fa fa-check"></i>@{{ am}}</li>
                                                                    </ul>

                                                                    <br>
                                                                    <span class="room-facility-text" ng-repeat="in in room.Inclusion track by $index" ng-if="$index < 1"><i class="fa fa-check"></i>@{{ in}}</span>
                                                                    <ul ng-if="room.CancellationPolicies.length" ng-repeat="cancelationT in room.CancellationPolicies|limitTo:1">
                                                                        <li style="cursor: pointer;" ng-mouseover="changeCancelOneMulti(room_key_index)" ng-mouseleave="changeCancelOneOutMulti(room_key_index)" class="room-facility-text cancellation-text" ng-if="cancelationT.Charge == 0"> <a ng-click="showcancelMtlRoom(room.RoomIndex)"><i class="fa fa-info-circle" aria-hidden="true"></i></a> {{ __('labels.refund_before')}} @{{ cancelationT.ToDate | date:'medium'}}</li>
                                                                        <li style="cursor: pointer;" ng-mouseover="changeCancelTwoMulti(room_key_index)" ng-mouseleave="changeCancelTwoOutMulti(room_key_index)" class="room-facility-text cancellation-text-all" ng-if="cancelationT.Charge != 0"> <a ng-click="showcancelMtlRoom(room.RoomIndex)"><i class="fa fa-info-circle" aria-hidden="true"></i></a> {{ __('labels.cancel_policy')}}</li>
                                                                    </ul>

                                                                    <!-- Cancellation Popup -->

                                                                    <table  class="cancel_room_table" id="cancellationPopupMulti_@{{ room_key_index }}" style="display: none;">
                                                                        <tbody>
                                                                            
                                                                            <tr>
                                                                                <th align="left">{{ __('labels.cancel_after')}}</th>
                                                                                <th align="left">{{ __('labels.cancel_before')}}</th>
                                                                                <th align="left">{{ __('labels.cancel_charges')}}</th>
                                                                            </tr>
                                                                            
                                                                            <tr ng-repeat="cancelationTR in room.CancellationPolicies">
                                                                                
                                                                                <td align="left">@{{ cancelationTR.FromDate | date:'medium' }}</td>
                                                                                <td align="left"> @{{ cancelationTR.ToDate | date:'medium'}}</td>
                                                                                <td align="left" ng-if="cancelationTR.ChargeType == 1">
                                                                                    @{{ cancelationTR.Currency }} @{{ cancelationTR.Charge }}
                                                                                </td>
                                                                                <td align="left" ng-if="cancelationTR.ChargeType == 2">
                                                                                    @{{ cancelationTR.Charge }}%
                                                                                </td>
                                                                                <td align="left" ng-if="cancelationTR.ChargeType == 3">
                                                                                    @{{ cancelationTR.Charge }} {{ __('labels.night_charge')}}
                                                                                </td>
                                                                            </tr>    
                                                                            <tr>
                                                                                <td colspan="3" class="txtcnclleft">{{ __('labels.no_show')}}</td>
                                                                            </tr>  
                                                                            <tr>
                                                                                <td colspan="3" class="txtcnclleft">{{ __('labels.early_check')}}</td>
                                                                            </tr>                           
                                                                            
                                                                        </tbody>

                                                                    </table>
                                                                    </br></br>
                                                                    <div id="roomCancelMultiple_@{{ room.RoomIndex}}" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog  modal-dialog-centered modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 ng-bind="room.RoomTypeName"></h3>
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <p ng-bind="room.CancellationPolicy"></p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @{{ room.Price.CurrencyCode}} @{{ room.FinalPrice | number: 2 }}
                                                                    <br>
                                                                    <span class="tax-included">{{ __('labels.include_tax')}}</span>
                                                                    <br>
                                                                    <div class="room-stay-text">
                                                                        {{ __('labels.total_cost')}} {{ $input_data['NoOfNights'] }} {{ __('labels.nights')}}
                                                                        <br>
                                                                        {{ $input_data['roomsGuests']}}
                                                                    </div>
                                                                    <br>
                                                                    <!-- <span class="room-facility-text" ng-repeat="in in room.Inclusion track by $index" ng-if="$index < 1"><i class="fa fa-check"></i>@{{ in}}</span> -->

                                                                    <span class="room-facility-text" ng-if="!room.Inclusion.length"><i class="fa fa-check"></i>Room Only</span>
                                                                </td>
                                                                <td>
                                                                    <input type="radio" data-price="@{{room.FinalPrice}}" ng-click="selectRoom(room, rCount, room_key_index)" class="room-radio-@{{rCount}}" id="room-radio-@{{rCount}}-@{{ room.RoomIndex}}-@{{ room_key_index}}" data-room="@{{ room.RoomIndex}}" data-category="@{{ room.CategoryId}}" data-combination="@{{ room.InfoSource}}" data-rtype="@{{ room.RoomTypeName}}" name="selected_rooms[@{{rCount}}][]" value="@{{ room.RoomIndex}}" ng-disabled="combination == 'fixed' && rCount > 0">
                                                                    <!-- Room Details Modal -->
                                                                    <div id="roomModal_@{{ $index}}" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog modal-xl">
                                                                            <div class="modal-content">
                                                                                <div class="modal-body">
                                                                                    <p class="room-heading modal-room-name"> <i class="fa fa-bed" aria-hidden="true"></i> @{{ room.RoomTypeName}} <button type="button" class="close" data-dismiss="modal">&times;</button></p>
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div id="custCarouse_@{{ $index}}" class="carousel slide" data-ride="carousel" align="center">
                                                                                                <ol class="carousel-indicators" >
                                                                                                    <li  ng-repeat="imgR in ::room.images track by $index" ng-init="image_key = $index" data-target="#custCarouse_@{{ room_key}}"  data-slide-to="@{{ image_key}}" ng-class="image_key < 1 ? 'active' : ''" ng-if="imgR.indexOf('_t') === - 1"></li>
                                                                                                </ol>
                                                                                                <div class="carousel-inner" >
                                                                                                    <div ng-repeat="imgR in ::room.images track by $index" ng-init="image_key_s = $index" ng-class="image_key_s < 1 ? 'carousel-item active' : 'carousel-item'" > 
                                                                                                        <img class="room-image-slider" src="@{{ ::imgR}}"  alt="hotel" ng-if="imgR.indexOf('http') != - 1 || imgR.indexOf('http') != - 1" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';"> 
                                                                             
                                                                                                        <img class="room-image-slider" src="{{env('AWS_BUCKET_URL')}}/@{{ ::imgR}}"  alt="hotel" ng-if="imgR.indexOf('http') == - 1 && imgR.indexOf('http') == - 1" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                                                    </div>
                                                                                                    <div ng-if="!room.images.length" class="carousel-item active"> 
                                                                                                        <img class="room-image-slider" src="https://b2b.tektravels.com/Images/HotelNA.jpg"  alt="hotel">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <!-- <p class="room-details" ng-if="roomList.sub_rooms[0].RatePlanName">
                                                                                                @{{ roomList.sub_rooms[0].RatePlanName}}
                                                                                            </p> -->
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 amenity" ng-repeat="am in room.Amenity track by $index">
                                                                                                    <span><i class="fa fa-check" aria-hidden="true"></i>&nbsp;@{{ am}}</span>
                                                                                                </div>
                                                                                            </div>
                                                                                            <!-- <p>
                                                                                                Smooking: <span class="strong">@{{ roomList.sub_rooms[0].SmokingPreference}}</span>
                                                                                            </p> -->
                                                                                            <!-- <p ng-repeat="ams in roomList.sub_rooms[0].Amenities track by $index">
                                                                                                Amenities: <span class="strong">@{{ ams.sub_rooms[0].Amenities[0]}}</span>
                                                                                            </p> -->
                                                                                            <!-- <p ng-if="roomList.sub_rooms[0].BedTypes && roomList.sub_rooms[0].BedTypes.length">
                                                                                                Bed Type: <span class="strong">@{{ roomList.sub_rooms[0].BedTypes[0].BedTypeDescription}}</span>
                                                                                            </p> -->
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--  <div class="row">
                                                                                         <div class="col-md-12">
                                                                                             <p class="modal-room-name" >
                                                                                                 <i class="fa fa-bed" aria-hidden="true"></i>
                                                                                                 <span ng-bind-html="room.RoomDescription"></span>
                                                                                             </p>
                                                                                         </div>
                                                                                     </div> -->
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <p class="modal-cancel-policy alert alert-info">{{ __('labels.cancel_policy')}}: @{{ room.CancellationPolicy}}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div id="roomInclusion_@{{rCount}}_@{{room_key_index}}_@{{ room.RoomIndex}}" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog  modal-dialog-centered modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 ng-bind="room.RoomTypeName"></h3>
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="row" ng-if="room.Amenity.length">
                                                                                        <div class="col-md-4" ng-repeat="am in room.Amenity track by $index">
                                                                                            <li class="room-facility-text"  ><i class="fa fa-check"></i>@{{ am}}</li>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row" ng-if="!room.Amenity.length">
                                                                                        <div class="col-md-4" ng-repeat="am in room.Amenities track by $index">
                                                                                            <li class="room-facility-text"  ><i class="fa fa-check"></i>@{{ am}}</li>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
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
                    @endif
                    <div class="inner_room_type_data" ng-show="!loaded">
                        <div class="container loading">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="top-section">
                                            <div class="profile-pic load-animate">
                                            </div>
                                            <div class="profile-info">
                                                <div class="block block--short load-animate"></div>
                                                <div class="block block--short load-animate"></div>
                                                <div class="block block--long load-animate"></div>
                                                <div class="block block--long load-animate"></div>
                                                <div class="block block--short load-animate"></div>
                                                <div class="block block--short load-animate"></div>
                                                <div class="block block--long load-animate"></div>
                                            </div>
                                        </div>
                                        <div class="bottom-section">
                                            <div class="content-section">
                                                <div class="content-info">
                                                    <div class="block block--short load-animate"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="content-section">
                                        <div class="content-info side">
                                            <div class="block block--long load-animate"></div>
                                            <div class="block block--long load-animate"></div>
                                            <div class="block block--long load-animate"></div>
                                            <div class="block block--long load-animate"></div>
                                            <div class="block block--long load-animate"></div>
                                            <div class="block block--long load-animate"></div>
                                            <div class="block block--long load-animate"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="inner_room_type_data" ng-if="error">
                        <div class="container loading">
                            <h3 ng-bind="error" class="text-center"></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Code Ends  -->
    </section>

    <section class="about_detail_hotels">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="inner_about_hotels_data attr">
                        <h2 class="about_hotel section_title text-center">{{ __('labels.about_hotel')}}</h2>
                        <ul class="list-inline" style="padding: 20px 0px;">
                            @if(isset($static_data) && isset($static_data['hotel_time']) && isset($static_data['hotel_time']['@CheckInTime']))
                            <li style="float:left;">{{ __('labels.checkin')}}{{ $static_data['hotel_time']['@CheckInTime']}}</li>
                            @endif
                            @if(isset($static_data) && isset($static_data['hotel_time']) && isset($static_data['hotel_time']['@CheckOutTime']))
                            <li style="float:left;">{{ __('labels.checkout')}} {{ $static_data['hotel_time']['@CheckOutTime']}} </li>
                            @endif
                        </ul>
                        @if(isset($static_data) && isset($static_data['hotel_description']) && isset($static_data['hotel_description'][0]))
                        <p>{!!html_entity_decode($static_data['hotel_description'][0])!!}</p>
                        @endif
                    </div>
                    @if(isset($static_data) && isset($static_data['attractions']) && sizeof($static_data['attractions']) > 0)
                    <br>
                    <div class="inner_about_hotels_data attr">
                        <h2 class='section_title text-center'>{{ __('labels.attractions')}}</h2>
                        @foreach($static_data['attractions'] as $h_at)
                        <p>{!!html_entity_decode($h_at)!!}</p>
                        @endforeach
                    </div>
                    @endif

                    @if(isset($static_data['hotel_facilities']) && sizeof($static_data['hotel_facilities']) > 0)
                    <br>
                    <div class="inner_about_hotels_data attr">
                        <h2 class='section_title text-center'>{{ __('labels.ameneties')}}</h2>
                        <div class="row">
                            @foreach($static_data['hotel_facilities'] as $h_fac)
                            <div class="col-md-3 pad-left">
                                <a href="javascript:void(0);" class="listing_kids_option" >
                                    {{ $h_fac}}
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(isset($static_data['hotel_info']) && sizeof($static_data['hotel_info']) > 0)
                    <br>
                    <div class="inner_about_hotels_data attr">
                        <h2 class='section_title text-center'>{{ __('labels.covid_fac')}}</h2>
                        <div class="row">
                            <ul class="covid-data">
                                @foreach($static_data['hotel_info'] as $c_info)
                                <li>{{ $c_info}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <br>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal for more flights -->

    <div id="moreflights" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roomFilterModalLabel">{{ __('labels.more_flights')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <section class="room_filters mt-4">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3 d-sm-block">
                                    <div class="filter_data flts_sidebar_data">
                                        <div class="filter_heading">
                                            <h3>{{ __('labels.filter')}}</h3>
                                            <a href="javascript:void(0)" class="reste_all_btn">{{ __('labels.reset_all')}}</a>
                                        </div>
                                        <div class="departure_data">
                                            <h4>{{ __('labels.departure')}}</h4>
                                            <div class="departure_time_info">
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="morning" name="morning" id="morning_dept" class="form-control time_oneway">
                                                    <span>{{ __('labels.4_11')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="afternoon"   name="afternoon" id="afternoon_dept" class="form-control time_oneway">
                                                    <span>{{ __('labels.11_4')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="evening"  name="evening" id="evening_dept" class="form-control time_oneway">
                                                    <span>{{ __('labels.4_9')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="night"  name="night" id="night_dept" class="form-control time_oneway">
                                                    <span>{{ __('labels.9_4')}}</span>                    
                                                </label>
                                            </div>
                                        </div>
                                        @if(!empty($flightdata['Segments'][1][0]))
                                        <div class="departure_data">
                                            <h4>{{ __('labels.return')}}</h4>
                                            <div class="departure_time_info">
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="morning" name="morning" id="morning_dept" class="form-control time_onewayR">
                                                    <span>{{ __('labels.4_11')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="afternoon"   name="afternoon" id="afternoon_dept" class="form-control time_onewayR">
                                                    <span>{{ __('labels.11_4')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="evening"  name="evening" id="evening_dept" class="form-control time_onewayR">
                                                    <span>{{ __('labels.4_9')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="night"  name="night" id="night_dept" class="form-control time_onewayR">
                                                    <span>{{ __('labels.9_4')}}</span>                    
                                                </label>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="departure_data">
                                            <h4>{{ __('labels.stops')}}</h4>
                                            <div class="departure_time_info">
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="Stop0"  class="form-control stop_flight_val">
                                                    <span><i class="stop_data">0</i>{{ __('labels.stop')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="Stop1"  class="form-control stop_flight_val">
                                                    <span><i class="stop_data">1</i>{{ __('labels.stop')}}</span>                    
                                                </label>
                                            </div>
                                        </div>

                                        <?php $flightsarray = array(); ?>
                                        @if(sizeof($flightdataAll) > 0)
                                        @foreach($flightdataAll as $key => $flight)
                                        <?php array_push($flightsarray, $flight['Segments'][0][0]['Airline']['AirlineCode']); ?>
                                        @endforeach
                                        @endif
                                        <?php $flightsarray = array_unique($flightsarray); ?>
                                        <div class="departure_data">
                                            <div class="filter_heading">
                                                <h4>{{ __('labels.pref_airs')}}</h4>
                                                <!-- <a href="javascript:void(0);" class="reste_all_btn">Reset</a> -->
                                            </div>
                                            <div class="airline_filters">
                                                @foreach($flightsarray as $key => $airline)

                                                <?php //echo "<pre>";print_r($airline);die;   ?>
                                                <div class="inner_check_air_data">
                                                    <label class="container_airline">  
                                                        <span class="airline_name">
                                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $airline}}" alt="{{ $airline}}" onerror="this.onerror=null;this.src='/images/DefaultAir.gif';">
                                                            <!-- <span class="air_name_set">{{ $airline }}</span> -->
                                                        </span>                    
                                                        <input type="checkbox" class="air-line-type" value="{{ $airline}}">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                @endforeach       
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="col-md-9  attr" >
                                    @foreach($flightdataAll as $key => $valueFH)
                                    <?php
                                    $timenow = 'night';
                                    $depart = date('H', strtotime($valueFH['Segments'][0][0]['Origin']['DepTime']));
                                    if ($depart >= 4 and $depart < 11) {
                                        $timenow = 'morning';
                                    } else if ($depart >= 11 and $depart < 16) {
                                        $timenow = 'afternoon';
                                    } else if ($depart >= 16 and $depart < 21) {
                                        $timenow = 'evening';
                                    } else {
                                        $timenow = 'night';
                                    }

                                    $timenowR = 'night';
                                    if (!empty($valueFH['Segments'][1][0])) {
                                        $departR = date('H', strtotime($valueFH['Segments'][1][0]['Origin']['DepTime']));
                                        if ($departR >= 4 and $departR < 11) {
                                            $timenowR = 'morning';
                                        } else if ($departR >= 11 and $departR < 16) {
                                            $timenowR = 'afternoon';
                                        } else if ($departR >= 16 and $departR < 21) {
                                            $timenowR = 'evening';
                                        } else {
                                            $timenowR = 'night';
                                        }
                                    }



                                    $stop = 'Stop0';

                                    if (!empty($valueFH['Segments'][0][2])) {
                                        $stop = 'Stop2';
                                    } else if (!empty($valueFH['Segments'][0][1]) && empty($valueFH['Segments'][0][2])) {
                                        $stop = 'Stop1';
                                    } else {
                                        $stop = 'Stop0';
                                    }
                                    ?>
                                    <div class="flex_width_air col-md-12 inner_about_hotels_data attr mt-4 outboundFlights"  data-depart="<?php echo $timenow; ?>" data-return="<?php echo $timenowR; ?>" data-cabinclass="Cabin{{ $valueFH['Segments'][0][0]['CabinClass']}}" data-air="{{$valueFH['Segments'][0][0]['Airline']['AirlineCode']}}" data-stops="<?php echo $stop; ?>">
                                        <div class="vistara_Data">
                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $valueFH['Segments'][0][0]['Airline']['AirlineCode']}}" alt="{{ $valueFH['Segments'][0][0]['Airline']['AirlineName']}}">
                                            <span>{{ $valueFH['Segments'][0][0]['Airline']['AirlineName']}} 
                                                {{ $valueFH['Segments'][0][0]['Airline']['AirlineCode']}} - {{ $valueFH['Segments'][0][0]['Airline']['FlightNumber']}}
                                            </span>
                                            @if(!empty($valueFH['Segments'][0][2]))
                                            <span class="multistop">(2 stops)</span>
                                            @endif
                                            @if(!empty($valueFH['Segments'][0][1]) && empty($valueFH['Segments'][0][2]))
                                            <span class="onestop">(1 stop)</span>
                                            @endif
                                            @if(empty($valueFH['Segments'][0][1]) && empty($valueFH['Segments'][0][2]))
                                            <span class="direct">(Direct)</span>
                                            @endif
                                        </div>
                                        <div class="main_flts_time">
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFH['Segments'][0][0]['Origin']['Airport']['AirportCode']}} <span>{{ $valueFH['Segments'][0][0]['Origin']['Airport']['CityName']}}, {{ $valueFH['Segments'][0][0]['Origin']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFH['Segments'][0][0]['Origin']['DepTime']))}})</div>
                                            </div>
                                            @if(!empty($valueFH['Segments'][0][2]))
                                            <div class="time_hr">{{ intdiv($valueFH['Segments'][0][2]['Duration'], 60).'h '.($valueFH['Segments'][0][2]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                                            @endif
                                            @if(!empty($valueFH['Segments'][0][1]) && empty($valueFH['Segments'][0][2]))
                                            <div class="time_hr">{{ intdiv($valueFH['Segments'][0][1]['Duration'], 60).'h '.($valueFH['Segments'][0][1]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                                            @endif
                                            @if(empty($valueFH['Segments'][0][1]) && empty($valueFH['Segments'][0][2]))
                                            <div class="time_hr">{{ intdiv($valueFH['Segments'][0][0]['Duration'], 60).'h '.($valueFH['Segments'][0][0]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                                            @endif

                                            @if(!empty($valueFH['Segments'][0][2]))
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFH['Segments'][0][2]['Destination']['Airport']['AirportCode']}} <span>{{ $valueFH['Segments'][0][2]['Destination']['Airport']['CityName']}}, {{ $valueFH['Segments'][0][2]['Destination']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFH['Segments'][0][2]['Destination']['ArrTime']))}})</div>
                                            </div>
                                            @endif
                                            @if(!empty($valueFH['Segments'][0][1]) && empty($valueFH['Segments'][0][2]))
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFH['Segments'][0][1]['Destination']['Airport']['AirportCode']}} <span>{{ $valueFH['Segments'][0][1]['Destination']['Airport']['CityName']}}, {{ $valueFH['Segments'][0][1]['Destination']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFH['Segments'][0][1]['Destination']['ArrTime']))}})</div>
                                            </div>
                                            @endif
                                            @if(empty($valueFH['Segments'][0][1]) && empty($valueFH['Segments'][0][2]))
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFH['Segments'][0][0]['Destination']['Airport']['AirportCode']}} <span>{{ $valueFH['Segments'][0][0]['Destination']['Airport']['CityName']}}, {{ $valueFH['Segments'][0][0]['Destination']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFH['Segments'][0][0]['Destination']['ArrTime']))}})</div>
                                            </div>
                                            @endif

                                        </div>

                                        <!-- International Return  -->
                                        @if(!empty($valueFH['Segments'][1][0]))
                                        <div class="vistara_Data">
                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $valueFH['Segments'][1][0]['Airline']['AirlineCode']}}" alt="{{ $valueFH['Segments'][1][0]['Airline']['AirlineName']}}">
                                            <span>{{ $valueFH['Segments'][1][0]['Airline']['AirlineName']}} 
                                                {{ $valueFH['Segments'][1][0]['Airline']['AirlineCode']}} - {{ $valueFH['Segments'][1][0]['Airline']['FlightNumber']}}
                                            </span>
                                            @if(!empty($valueFH['Segments'][1][2]))
                                            <span class="multistop">(2 {{ __('labels.stops')}})</span>
                                            @endif
                                            @if(!empty($valueFH['Segments'][1][1]) && empty($valueFH['Segments'][1][2]))
                                            <span class="onestop">(1 {{ __('labels.stop')}})</span>
                                            @endif
                                            @if(empty($valueFH['Segments'][1][1]) && empty($valueFH['Segments'][1][2]))
                                            <span class="direct">({{ __('labels.direct')}})</span>
                                            @endif
                                        </div>
                                        <div class="main_flts_time">
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFH['Segments'][1][0]['Origin']['Airport']['AirportCode']}} <span>{{ $valueFH['Segments'][1][0]['Origin']['Airport']['CityName']}}, {{ $valueFH['Segments'][1][0]['Origin']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFH['Segments'][1][0]['Origin']['DepTime']))}})</div>
                                            </div>
                                            @if(!empty($valueFH['Segments'][1][2]))
                                            <div class="time_hr">{{ intdiv($valueFH['Segments'][1][2]['Duration'], 60).'h '.($valueFH['Segments'][1][2]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                                            @endif
                                            @if(!empty($valueFH['Segments'][1][1]) && empty($valueFH['Segments'][1][2]))
                                            <div class="time_hr">{{ intdiv($valueFH['Segments'][1][1]['Duration'], 60).'h '.($valueFH['Segments'][1][1]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                                            @endif
                                            @if(empty($valueFH['Segments'][1][1]) && empty($valueFH['Segments'][1][2]))
                                            <div class="time_hr">{{ intdiv($valueFH['Segments'][1][0]['Duration'], 60).'h '.($valueFH['Segments'][1][0]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                                            @endif

                                            @if(!empty($valueFH['Segments'][1][2]))
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFH['Segments'][1][2]['Destination']['Airport']['AirportCode']}} <span>{{ $valueFH['Segments'][1][2]['Destination']['Airport']['CityName']}}, {{ $valueFH['Segments'][1][2]['Destination']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFH['Segments'][1][2]['Destination']['ArrTime']))}})</div>
                                            </div>
                                            @endif
                                            @if(!empty($valueFH['Segments'][1][1]) && empty($valueFH['Segments'][1][2]))
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFH['Segments'][1][1]['Destination']['Airport']['AirportCode']}} <span>{{ $valueFH['Segments'][1][1]['Destination']['Airport']['CityName']}}, {{ $valueFH['Segments'][1][1]['Destination']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFH['Segments'][1][1]['Destination']['ArrTime']))}})</div>
                                            </div>
                                            @endif
                                            @if(empty($valueFH['Segments'][1][1]) && empty($valueFH['Segments'][1][2]))
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFH['Segments'][1][0]['Destination']['Airport']['AirportCode']}} <span>{{ $valueFH['Segments'][1][0]['Destination']['Airport']['CityName']}}, {{ $valueFH['Segments'][1][0]['Destination']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFH['Segments'][1][0]['Destination']['ArrTime']))}})</div>
                                            </div>
                                            @endif

                                        </div>
                                        @endif
                                        <button type="submit" class="btn btn-primary" ng-click="selectedFlight('{{ $key}}','{{$flightTraceId}}','{{$flight_search_id}}')">{{ __('labels.select')}}</button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <!-- Ends here -->

    <!-- Domestic Flight Return  -->

    <!-- Modal for more flights -->

    <div id="moreflightsReturn" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roomFilterModalLabel">{{ __('labels.more_flights')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <section class="room_filters mt-4">
                        <div class="container">
                            <div class="row">

                                <div class="col-md-3 d-none d-sm-block">
                                    <div class="filter_data flts_sidebar_data">
                                        <div class="filter_heading">
                                            <h3>{{ __('labels.filter')}}</h3>
                                            <a href="javascript:void(0)" class="reste_all_btn">{{ __('labels.reset_all')}}</a>
                                        </div>
                                        <div class="departure_data">
                                            <h4>{{ __('labels.departure')}}</h4>
                                            <div class="departure_time_info">
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="morning" name="morning" id="morning_dept" class="form-control time_oneway_return">
                                                    <span>{{ __('labels.4_11')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="afternoon"   name="afternoon" id="afternoon_dept" class="form-control time_oneway_return">
                                                    <span>{{ __('labels.11_4')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="evening"  name="evening" id="evening_dept" class="form-control time_oneway_return">
                                                    <span>{{ __('labels.4_9')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="night"  name="night" id="night_dept" class="form-control time_oneway_return">
                                                    <span>{{ __('labels.9_4')}}</span>                    
                                                </label>
                                            </div>
                                        </div>

                                        <div class="departure_data">
                                            <h4>{{ __('labels.stops')}}</h4>
                                            <div class="departure_time_info">
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="Stop0"  class="form-control stop_flight_val_return">
                                                    <span><i class="stop_data">0</i>{{ __('labels.stop')}}</span>                    
                                                </label>
                                                <label class="current_time_det">
                                                    <input type="checkbox" value="Stop1"  class="form-control stop_flight_val_return">
                                                    <span><i class="stop_data">1</i>{{ __('labels.stop')}}</span>                    
                                                </label>
                                            </div>
                                        </div>

                                        <?php $flightsarrayR = array(); ?>
                                        @if($flightReturndataAll && !empty($flightReturndataAll))
                                        @if(sizeof($flightReturndataAll) > 0)
                                        @foreach($flightReturndataAll as $key => $flightR)
                                        <?php array_push($flightsarrayR, $flightR['Segments'][0][0]['Airline']['AirlineCode']); ?>
                                        @endforeach
                                        @endif
                                        @endif
                                        <?php $flightsarrayR = array_unique($flightsarrayR); ?>
                                        <div class="departure_data">

                                            <div class="filter_heading">
                                                <h4>{{ __('labels.pref_airs')}}</h4>
                                                <!-- <a href="javascript:void(0);" class="reste_all_btn">Reset</a> -->
                                            </div>
                                            <div class="airline_filters">
                                                @foreach($flightsarrayR as $key => $airline)

                                                <?php //echo "<pre>";print_r($airline);die;   ?>
                                                <div class="inner_check_air_data">
                                                    <label class="container_airline">  
                                                        <span class="airline_name">
                                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $airline}}" alt="{{ $airline}}" onerror="this.onerror=null;this.src='/images/DefaultAir.gif';">
                                                            <!-- <span class="air_name_set">{{ $airline }}</span> -->
                                                        </span>                    
                                                        <input type="checkbox" class="air-line-type-return" value="{{ $airline}}" >
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                @endforeach       
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="col-md-9  attr" >
                                    @if($flightReturndataAll && !empty($flightReturndataAll))
                                    @foreach($flightReturndataAll as $key => $valueFHReturn)
                                    <?php //echo "<pre>";print_r($valueFHReturn); die;?> 
                                    <?php
                                    $timenow = 'night';
                                    $depart = date('H', strtotime($valueFHReturn['Segments'][0][0]['Origin']['DepTime']));
                                    if ($depart >= 4 and $depart < 11) {
                                        $timenow = 'morning';
                                    } else if ($depart >= 11 and $depart < 16) {
                                        $timenow = 'afternoon';
                                    } else if ($depart >= 16 and $depart < 21) {
                                        $timenow = 'evening';
                                    } else {
                                        $timenow = 'night';
                                    }

                                    $stop = 'Stop0';

                                    if (!empty($valueFHReturn['Segments'][0][2])) {
                                        $stop = 'Stop2';
                                    } else if (!empty($valueFHReturn['Segments'][0][1]) && empty($valueFHReturn['Segments'][0][2])) {
                                        $stop = 'Stop1';
                                    } else {
                                        $stop = 'Stop0';
                                    }
                                    ?>
                                    <div class="flex_width_air col-md-12 inner_about_hotels_data attr mt-4 inboundFlights" data-depart="<?php echo $timenow; ?>" data-cabinclass="Cabin{{ $valueFHReturn['Segments'][0][0]['CabinClass']}}" data-air="{{$valueFHReturn['Segments'][0][0]['Airline']['AirlineCode']}}" data-stops="<?php echo $stop; ?>">
                                        <div class="vistara_Data">
                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $valueFHReturn['Segments'][0][0]['Airline']['AirlineCode']}}" alt="{{ $valueFHReturn['Segments'][0][0]['Airline']['AirlineName']}}">
                                            <span>{{ $valueFHReturn['Segments'][0][0]['Airline']['AirlineName']}} 
                                                {{ $valueFHReturn['Segments'][0][0]['Airline']['AirlineCode']}} - {{ $valueFHReturn['Segments'][0][0]['Airline']['FlightNumber']}}
                                            </span>
                                            @if(!empty($valueFHReturn['Segments'][0][2]))
                                            <span class="multistop">(2 {{ __('labels.stops')}})</span>
                                            @endif
                                            @if(!empty($valueFHReturn['Segments'][0][1]) && empty($valueFHReturn['Segments'][0][2]))
                                            <span class="onestop">(1 {{ __('labels.stop')}})</span>
                                            @endif
                                            @if(empty($valueFHReturn['Segments'][0][1]) && empty($valueFHReturn['Segments'][0][2]))
                                            <span class="direct">({{ __('labels.direct')}})</span>
                                            @endif
                                        </div>
                                        <div class="main_flts_time">
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFHReturn['Segments'][0][0]['Origin']['Airport']['AirportCode']}} <span>{{ $valueFHReturn['Segments'][0][0]['Origin']['Airport']['CityName']}}, {{ $valueFHReturn['Segments'][0][0]['Origin']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFHReturn['Segments'][0][0]['Origin']['DepTime']))}})</div>
                                            </div>
                                            @if(!empty($valueFHReturn['Segments'][0][2]))
                                            <div class="time_hr">{{ intdiv($valueFHReturn['Segments'][0][2]['Duration'], 60).'h '.($valueFHReturn['Segments'][0][2]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                                            @endif
                                            @if(!empty($valueFHReturn['Segments'][0][1]) && empty($valueFHReturn['Segments'][0][2]))
                                            <div class="time_hr">{{ intdiv($valueFHReturn['Segments'][0][1]['Duration'], 60).'h '.($valueFHReturn['Segments'][0][1]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                                            @endif
                                            @if(empty($valueFHReturn['Segments'][0][1]) && empty($valueFHReturn['Segments'][0][2]))
                                            <div class="time_hr">{{ intdiv($valueFHReturn['Segments'][0][0]['Duration'], 60).'h '.($valueFHReturn['Segments'][0][0]['Duration'] % 60)}}m</div> <i class="fa fa-plane" aria-hidden="true"></i>
                                            @endif

                                            @if(!empty($valueFHReturn['Segments'][0][2]))
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFHReturn['Segments'][0][2]['Destination']['Airport']['AirportCode']}} <span>{{ $valueFHReturn['Segments'][0][2]['Destination']['Airport']['CityName']}}, {{ $valueFHReturn['Segments'][0][2]['Destination']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFHReturn['Segments'][0][2]['Destination']['ArrTime']))}})</div>
                                            </div>
                                            @endif
                                            @if(!empty($valueFHReturn['Segments'][0][1]) && empty($valueFHReturn['Segments'][0][2]))
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFHReturn['Segments'][0][1]['Destination']['Airport']['AirportCode']}} <span>{{ $valueFHReturn['Segments'][0][1]['Destination']['Airport']['CityName']}}, {{ $valueFHReturn['Segments'][0][1]['Destination']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFHReturn['Segments'][0][1]['Destination']['ArrTime']))}})</div>
                                            </div>
                                            @endif
                                            @if(empty($valueFHReturn['Segments'][0][1]) && empty($valueFHReturn['Segments'][0][2]))
                                            <div class="fllight_time_date">
                                                <h5>{{ $valueFHReturn['Segments'][0][0]['Destination']['Airport']['AirportCode']}} <span>{{ $valueFHReturn['Segments'][0][0]['Destination']['Airport']['CityName']}}, {{ $valueFHReturn['Segments'][0][0]['Destination']['Airport']['CountryName']}}</span></h5>
                                                <div class="time_flts">({{ date('H:i', strtotime($valueFHReturn['Segments'][0][0]['Destination']['ArrTime']))}})</div>
                                            </div>
                                            @endif

                                        </div>
                                        <button type="submit" class="btn btn-primary" ng-click="selectedFlightReturn('{{ $key}}','{{$flightTraceId}}','{{ $flight_search_id }}')">SELECT</button>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Ends here -->

</div>
<br><br>
<div id="sessionWarningModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header refresh-header">
                <h3 class="refresh-header text-center">{{ __('labels.session_expired')}}</h3>
            </div>
            <div class="modal-body">
                <p>{{ __('labels.session_expired_msg')}}</p>        
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-primary refresh-btn show-hotel">{{ __('labels.refresh_search')}}</a>
            </div>
        </div>
    </div>
</div>


<script>

    /* Time Filter */
    var checkedTime = [];
    $('.time_oneway').change(function () {
    checkedTime = [];
    $('.time_oneway').each(function () {

    if ($(this).is(':checked')) {
    checkedTime.push($(this).val());
    }

    });
    // $('.outboundFlights').each(function () {

    //     if (checkedTime.length == 0) {
    //         $(this).show();
    //     } else {
    //         if (checkedTime.includes($(this).data('depart'))) {
    //             $(this).show();
    //         } else {
    //             $(this).hide();
    //         }
    //     }

    // });
    getFilter();
    });
    var checkedTimeR = [];
    $('.time_onewayR').change(function () {
    checkedTimeR = [];
    $('.time_onewayR').each(function () {

    if ($(this).is(':checked')) {
    checkedTimeR.push($(this).val());
    }

    });
    // $('.outboundFlights').each(function () {
    //     if (checkedTimeR.length == 0) {
    //         $(this).show();
    //     } else {
    //         if (checkedTimeR.includes($(this).data('return'))) {
    //             $(this).show();
    //         } else {
    //             $(this).hide();
    //         }
    //     }
    // });
    getFilter();
    });
    /* Stop Filter  */
    var StopClassReturn = [];
    $('.stop_flight_val').change(function () {
    StopClassReturn = [];
    $('.stop_flight_val').each(function () {

    if ($(this).is(':checked')) {
    StopClassReturn.push($(this).val());
    }

    });
    // $('.outboundFlights').each(function () {
    //     if (StopClassReturn.length == 0) {
    //         $(this).show();
    //     } else {
    //         if (StopClassReturn.includes($(this).data('stops'))) {
    //             $(this).show();
    //         } else {
    //             $(this).hide();
    //         }
    //     }
    // });
    getFilter();
    });
    /* Filter by Aircraft type*/
    var checkedAirType = [];
    $('.air-line-type').change(function () {
    checkedAirType = [];
    $('.air-line-type').each(function () {
    if ($(this).is(':checked')) {
    checkedAirType.push($(this).val());
    }

    });
    // $('.outboundFlights').each(function () {
    //     if (checkedAirType.length == 0) {
    //         $(this).show();
    //     } else {
    //         if (checkedAirType.includes($(this).data('air'))) {
    //             $(this).show();
    //         } else {
    //             $(this).hide();
    //         }
    //     }
    // });
    getFilter();
    });
    function getFilter(){

    checkedTime = [];
    $('.time_oneway').each(function () {

    if ($(this).is(':checked')) {
    checkedTime.push($(this).val());
    }

    });
    checkedTimeR = [];
    $('.time_onewayR').each(function () {

    if ($(this).is(':checked')) {
    checkedTimeR.push($(this).val());
    }

    });
    StopClassReturn = [];
    $('.stop_flight_val').each(function () {

    if ($(this).is(':checked')) {
    StopClassReturn.push($(this).val());
    }

    });
    checkedAirType = [];
    $('.air-line-type').each(function () {
    if ($(this).is(':checked')) {
    checkedAirType.push($(this).val());
    }

    });
    $('.outboundFlights').each(function () {

    if (checkedTime.length == 0) {
    // $(this).show();
    } else {
    if (checkedTime.includes($(this).data('depart'))) {
    $(this).show();
    } else {
    $(this).hide();
    }
    }

    if (checkedTimeR.length == 0) {
    //$(this).show();
    } else {
    if (checkedTimeR.includes($(this).data('return'))) {
    $(this).show();
    } else {
    $(this).hide();
    }
    }

    if (StopClassReturn.length == 0) {
    //$(this).show();
    } else {
    if (StopClassReturn.includes($(this).data('stops'))) {
    $(this).show();
    } else {
    $(this).hide();
    }
    }

    if (checkedAirType.length == 0) {
    //$(this).show();
    } else {
    if (checkedAirType.includes($(this).data('air'))) {
    $(this).show();
    } else {
    $(this).hide();
    }
    }

    if (checkedTime.length == 0 && checkedTimeR.length == 0 && StopClassReturn.length == 0 && checkedAirType.length == 0) {
    $(this).show();
    } else if (checkedAirType.length != 0 && StopClassReturn.length != 0 && checkedTimeR.length != 0 && checkedTime.length != 0) {


    if (checkedAirType.includes($(this).data('air')) && StopClassReturn.includes($(this).data('stops')) && checkedTimeR.includes($(this).data('return')) && checkedTime.includes($(this).data('depart'))) {
    $(this).show();
    } else {
    $(this).hide();
    }

    } else if (checkedAirType.length != 0 && StopClassReturn.length != 0 && checkedTime.length != 0) {


    if (checkedAirType.includes($(this).data('air')) && StopClassReturn.includes($(this).data('stops')) && checkedTime.includes($(this).data('depart'))) {
    $(this).show();
    } else {
    $(this).hide();
    }


    } else if (checkedAirType.length != 0 && StopClassReturn.length != 0 && checkedTimeR.length != 0) {

    if (checkedAirType.includes($(this).data('air')) && StopClassReturn.includes($(this).data('stops')) && checkedTimeR.includes($(this).data('return'))) {
    $(this).show();
    } else {
    $(this).hide();
    }


    } else if (checkedTime.length != 0 && StopClassReturn.length != 0 && checkedTimeR.length != 0) {

    if (checkedTime.includes($(this).data('depart')) && StopClassReturn.includes($(this).data('stops')) && checkedTimeR.includes($(this).data('return'))) {
    $(this).show();
    } else {
    $(this).hide();
    }


    } else if (checkedAirType.length != 0 && StopClassReturn.length != 0) {

    if (checkedAirType.includes($(this).data('air')) && StopClassReturn.includes($(this).data('stops'))) {
    $(this).show();
    } else {
    $(this).hide();
    }


    } else{

    }

    });
    }


    /* Return Flights */



    /* Time Filter */
    var checkedTimeReturn = [];
    $('.time_oneway_return').change(function () {
    checkedTimeReturn = [];
    $('.time_oneway_return').each(function () {

    if ($(this).is(':checked')) {
    checkedTimeReturn.push($(this).val());
    }

    });
    // $('.inboundFlights').each(function () {
    //     if (checkedTimeReturn.length == 0) {
    //         $(this).show();
    //     } else {
    //         if (checkedTimeReturn.includes($(this).data('depart'))) {
    //             $(this).show();
    //         } else {
    //             $(this).hide();
    //         }
    //     }
    // });
    getFilterReturn();
    });
    /* Stop Filter  */
    var StopClassReturnR = [];
    $('.stop_flight_val_return').change(function () {
    StopClassReturnR = [];
    $('.stop_flight_val_return').each(function () {

    if ($(this).is(':checked')) {
    StopClassReturnR.push($(this).val());
    }

    });
    // $('.inboundFlights').each(function () {
    //     if (StopClassReturnR.length == 0) {
    //         $(this).show();
    //     } else {
    //         if (StopClassReturnR.includes($(this).data('stops'))) {
    //             $(this).show();
    //         } else {
    //             $(this).hide();
    //         }
    //     }
    // });
    getFilterReturn();
    });
    /* Filter by Aircraft type*/
    var checkedAirTypeReturn = [];
    $('.air-line-type-return').change(function () {
    checkedAirTypeReturn = [];
    $('.air-line-type-return').each(function () {
    if ($(this).is(':checked')) {
    checkedAirTypeReturn.push($(this).val());
    }

    });
    // $('.inboundFlights').each(function () {
    //     if (checkedAirTypeReturn.length == 0) {
    //         $(this).show();
    //     } else {
    //         if (checkedAirTypeReturn.includes($(this).data('air'))) {
    //             $(this).show();
    //         } else {
    //             $(this).hide();
    //         }
    //     }
    // });
    getFilterReturn();
    });
    function getFilterReturn(){

    checkedTimeReturn = [];
    $('.time_oneway_return').each(function () {

    if ($(this).is(':checked')) {
    checkedTimeReturn.push($(this).val());
    }

    });
    StopClassReturnR = [];
    $('.stop_flight_val_return').each(function () {

    if ($(this).is(':checked')) {
    StopClassReturnR.push($(this).val());
    }

    });
    checkedAirTypeReturn = [];
    $('.air-line-type-return').each(function () {
    if ($(this).is(':checked')) {
    checkedAirTypeReturn.push($(this).val());
    }

    });
    $('.inboundFlights').each(function () {



    if (checkedTimeReturn.length == 0) {
    //$(this).show();
    } else {
    if (checkedTimeReturn.includes($(this).data('depart'))) {
    $(this).show();
    } else {
    $(this).hide();
    }
    }


    if (StopClassReturnR.length == 0) {
    //$(this).show();
    } else {
    if (StopClassReturnR.includes($(this).data('stops'))) {
    $(this).show();
    } else {
    $(this).hide();
    }
    }

    if (checkedAirTypeReturn.length == 0) {
    //$(this).show();
    } else {
    if (checkedAirTypeReturn.includes($(this).data('air'))) {
    $(this).show();
    } else {
    $(this).hide();
    }
    }

    if (checkedTimeReturn.length == 0 && StopClassReturnR.length == 0 && checkedAirTypeReturn.length == 0) {
    $(this).show();
    } else if (checkedAirTypeReturn.length != 0 && StopClassReturnR.length != 0 && checkedTimeReturn.length != 0) {


    if (checkedAirTypeReturn.includes($(this).data('air')) && StopClassReturnR.includes($(this).data('stops')) && checkedTimeReturn.includes($(this).data('depart'))) {
    $(this).show();
    } else {
    $(this).hide();
    }


    } else if (checkedTimeReturn.length != 0 && StopClassReturnR.length != 0) {

    if (checkedTimeReturn.includes($(this).data('depart')) && StopClassReturnR.includes($(this).data('stops'))) {
    $(this).show();
    } else {
    $(this).hide();
    }


    } else if (checkedAirTypeReturn.length != 0 && StopClassReturnR.length != 0) {

    if (checkedAirTypeReturn.includes($(this).data('air')) && StopClassReturnR.includes($(this).data('stops'))) {
    $(this).show();
    } else {
    $(this).hide();
    }


    } else{

    }

    });
    }

</script>
<script>
    
    $(document).ready(function(){

        const uluru = { lat: <?php echo $static_data['lat']; ?>, lng: <?php echo $static_data['lng']; ?> };
        // The map, centered at Uluru
        const mapx = new google.maps.Map(document.getElementById("hotelmap"), {
                zoom: 14,
                center: uluru
        });
        // The marker, positioned at Uluru
        const marker = new google.maps.Marker({
                position: uluru,
                map: mapx
        });
        
    });

</script>


@endsection
