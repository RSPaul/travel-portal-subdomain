@extends('layouts.app-header')
@section('content') 
<input type="hidden" id="isViewHotel" value="1">
<input type="hidden" id="search_id" value="{{ $search_id }}">
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
        <div class="row">
            <div class="col-md-12">
                <div class="listing_inner_forms">
                    <form method="GET" name="searchForm" style="margin-bottom:0px;" id="searchRoomsForm" action="{{ route('search_rooms')}}"  >
                        @csrf
                        <div class="rounding_form_info" style="border-right:5px solid #b3cedd;border-left:5px solid #b3cedd;">
                            <div class="row">
                                <!-- <div class="col-lg-3" style="border-radius:4px;padding: 2px;background:#fff;border:5px solid #b3cedd;">
                                    <div class="form-group" style="margin:0px !important;">
                                        <input  id="autocomplete" name="Location" value="{{  isset($input_data['Location']) ? $input_data['Location'] : $s_name}}"  placeholder="{{ __('labels.search_placeholder') }}"  onFocus="geolocate()" type="text" style="color:#333;border:0px solid #ccc;padding:8px;font-size:14px;height:40px;width:100%;" />



                                        

                                    </div>
                                </div> -->
                                <div class="col-lg-6" style="border-radius:4px;padding: 2px;background:#fff;border:5px solid #b3cedd;">
                                        <input type="hidden" name="Latitude" id="Latitude" value="{{  isset($input_data['Latitude']) ? $input_data['Latitude'] : ''}}">
                                        <input type="hidden" name="Longitude" id="Longitude" value="{{  isset($input_data['Longitude']) ? $input_data['Longitude'] : ''}}">
                                        <input type="hidden" name="Radius" id="Radius" value="15">


                                        <input type="hidden" name="city_id" id="city_id" value="{{ $s_city}}">
                                        <input type="hidden" name="city_name" id="city_name" value="{{  $s_name}}">
                                        <input type="hidden" name="countryCode" id="country_code" value="{{ $input_data['countryCode']}}">
                                        <input type="hidden" name="countryName" id="country_name" value="{{ $input_data['countryName']}}">
                                        <input type="hidden" name="country" id="country" value="{{ $input_data['user_country']}}">
                                        <input type="hidden" name="currency" id="currency" value="{{ $input_data['currency']}}">
                                        <input type="hidden" name="referral" id="referral" value="{{ $referral}}">

                                        <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="{{ $hotel_code}}">
                                        <input type="hidden" name="ishalal" id="ishalal" value="{{ (Session::get('active_tab') == 'halal')?1:0}}">

                                        <input type="hidden" id="nights_lbl" value="{{ __('labels.nights') }}">
                                        <input type="hidden" id="rooms_lbl" value="{{ __('labels.rooms') }}">
                                        <input type="hidden" id="night_lbl" value="{{ __('labels.night') }}">
                                        <input type="hidden" id="room_lbl" value="{{ __('labels.room') }}">
                                        <input type="hidden" id="local_sel" value="{{Session::get('locale')}}">
                                        <input type="hidden" id="adults_lbl" value="{{ __('labels.adults') }}">
                                        <input type="hidden" id="childrens_lbl" value="{{ __('labels.childrens') }}">
                                    <div class="row" style="margin:0px;">
                                        <div class="col"  style="padding:0px 5px;">
                                            <div class="form-group" style="margin:0px !important;">
                                                <div class="small_station_info departDay1 text-center" >{{ __('labels.checkin') }}</div>
                                                <div class="input-group" >
                                                    <input style="text-align:center;" type="text" id="dateRange" value="{{ $input_data['departdate']}} - {{ $input_data['returndate']}}" class="form-control  text-center" />
                                                    <input id="departHotel" type="hidden" name="departdate" value="{{ $input_data['departdate']}}" />
                                                    <input id="returnHotel" type="hidden" name="returndate" value="{{ $input_data['returndate']}}" />                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col text-center">
                                            <span class="total-nights" style="font-size:10px;">
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
                                            <i class="fa fa-calendar" style="color:#1e4355;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3" style="border-radius:4px;padding:2px;background:#fff;border:5px solid #b3cedd;">
                                    <div class="form-group" style="margin:0px !important;">
                                        <div class="small_station_info  text-center" id="guestRooms" >
                                            @if(Session::get('locale') == 'heb')
                                                {{ __('labels.rooms') }}&nbsp;<span style="position:absolute;">1</span>
                                            @else
                                                1 {{ __('labels.rooms') }}
                                            @endif
                                        </div>
                                        <input style="text-align: center;" type="text" name="roomsGuests" id="roomsGuests" class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ $input_data['roomsGuests']}}">
                                        @include('_partials.hotel-guests-edit')
                                    </div>
                                </div>
                                <div class="col-lg-3" style="padding:0px;border:3px solid #b3cedd;">
                                    <div class="search_btns_listing" style="padding:0px;border:3px solid #b3cedd;">
                                        <button type="submit" style="width:100%;border-radius: 4px !important;" class="btn btn-primary">{{ __('labels.search')}}</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @if(Session::get('locale') == 'heb')
                        <p style="color:#fff;">.לתושבי ישראל יתווסף למחיר 17% מע"מ שישולם ישירות למלון, למעט באזור אילת *</p> 
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>  
<section class="listing_banner_forms no-index">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="listing_inner_forms">
                    <div class="rounding_form_info">
                        <p class="text-center"> 
                            @if(isset($hotel) && isset($hotel['static_data']['hotel_name']))
                            <span class=""><i class="fa fa-building"></i> {{ $hotel['static_data']['hotel_name']}}</span>
                            @endif
                            <span class=""> - {{ $s_name}}</span> 
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



<div ng-app="hotelApp" ng-controller="roomCtrl" ng-init="getRooms()" id="hotelViewPage">


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
                                                @if(isset($static_data['hotel_images'][0]) && strpos($static_data['hotel_images'][0], 'http') !== false || ( isset($static_data['hotel_images'][0]) && strpos($static_data['hotel_images'][0], 'www') !== false) )
                                                <a href="{{ isset($static_data['hotel_images'][0])?$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me second-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][0])?$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][0])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me second-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][0])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif

                                                @if(isset($static_data['hotel_images'][1]) && strpos($static_data['hotel_images'][1], 'http') !== false || ( isset($static_data['hotel_images'][1]) && strpos($static_data['hotel_images'][1], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][1])?$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}" class="hotel_url  show-me third-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][1])?$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
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
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][2])?$static_data['hotel_images'][2]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
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
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][3])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][4]) && strpos($static_data['hotel_images'][4], 'http') !== false || ( isset($static_data['hotel_images'][4]) && strpos($static_data['hotel_images'][4], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][4])?$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][4])?$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][4])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][4])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][5]) && strpos($static_data['hotel_images'][5], 'http') !== false || ( isset($static_data['hotel_images'][5]) && strpos($static_data['hotel_images'][5], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][5])?$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][5])?$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][5])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][5])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][6]) && strpos($static_data['hotel_images'][6], 'http') !== false || ( isset($static_data['hotel_images'][6]) && strpos($static_data['hotel_images'][6], 'www') !== false ) )
                                                <a href="{{ isset($static_data['hotel_images'][6])?$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][6])?$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
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
                                            <span class="filter-text">{{ __('labels.filter_rooms_by')}}&nbsp;&nbsp;</span>
                                            <ul class="inclusion-filters">
                                                <li class="room-filter-li" data-include="Half Board">{{ __('labels.half_b')}}</li>
                                                <li class="room-filter-li" data-include="Full Board">{{ __('labels.full_b')}}</li>
                                                <li class="room-filter-li" data-include="Breakfast">{{ __('labels.breakfast')}}</li>
                                            </ul>
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
                                                        <div class="hotel-pic carousel-item @if($i_key ==0) active @endif" > 
                                                            @if(strpos($image, 'http') !== false || strpos($image, 'www') !== false)
                                                                <img ng-src="{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                            @else
                                                                <img ng-src="{{env('AWS_BUCKET_URL')}}/{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                            @endif
                                                        </div>
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
                                                            @foreach($room_ameneties as $k =>  $r_fac)
                                                            @if($k < 10)
                                                                <li class="room-facility-text" ><i class="fa fa-check"></i>{{$r_fac}}</li>
                                                            @endif
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
                                                    <img style="width:100%;" ng-click="showRoomDetails($index)" src="@{{ roomList.sub_rooms[0].images[0]}}" alt="@{{ roomList.RoomTypeName}}" class="img-responsive"   onerror="setTimeout(() => { this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available'},15000);" />
                                                </span>

                                                <span ng-if="roomList.sub_rooms[0].images.length && (roomList.sub_rooms[0].images[0].indexOf('http') == - 1 && roomList.sub_rooms[0].images[0].indexOf('www') == - 1)">
                                                    <img style="width:100%;" ng-click="showRoomDetails($index)" src="{{env('AWS_BUCKET_URL')}}/@{{ roomList.sub_rooms[0].images[0]}}" alt="@{{ roomList.RoomTypeName}}" class="img-responsive"  onerror="setTimeout(() => { this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available'},15000);" />
                                                </span>

                                                <img  style="width:100%;" ng-click="showRoomDetails($index)" src="https://b2b.tektravels.com/Images/HotelNA.jpg" alt="@{{ roomList.RoomTypeName}}" class="img-responsive" ng-if="!roomList.sub_rooms[0].images.length" />
                                            </div>
                                            <div style="padding:10px 20px 20px 20px;">
                                                <div class="tw-text-sm">
                                                    <ul class="list-inline room_influences">
                                                    </ul>
                                                        <ul ng-if="roomList.sub_rooms[0].Amenity.length">
                                                            <li class="room-facility-text" ng-repeat="am in roomList.sub_rooms[0].Amenity track by $index" ng-if="$index < 4"><i class="fa fa-check"></i>@{{ am}}</li>
                                                        </ul>
                                                        <ul ng-if="!roomList.sub_rooms[0].Amenity.length">
                                                            <li class="room-facility-text" ng-repeat="am in roomList.sub_rooms[0].Amenities track by $index" ng-if="$index < 4"><i class="fa fa-check"></i>@{{ am}}</li>
                                                        </ul>

                                                        <ul ng-if="!roomList.sub_rooms[0].Amenity.length && !roomList.sub_rooms[0].Amenities.length">
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
                                                                        <li  ng-repeat="imgR in ::roomList.sub_rooms[0].images track by $index" ng-init="image_key = $index" data-target="#custCarouse_@{{ room_key}}"  data-slide-to="@{{ image_key}}" ng-class="image_key < 1 ? 'active' : ''"></li>
                                                                    </ol>
                                                                    <div class="carousel-inner" >
                                                                        <div ng-repeat="imgR in ::roomList.sub_rooms[0].images track by $index"  ng-init="image_key_s = $index" ng-class="image_key_s < 1 ? 'carousel-item active' : 'carousel-item'" > 
                                                                            <img class="room-image-slider" src="@{{ ::imgR}}"  alt="hotel" ng-if="imgR.indexOf('http') != - 1 || imgR.indexOf('http') != - 1" onerror="setTimeout(() => { this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available'},15000);"> 

                                                                            <img class="room-image-slider" src="{{env('AWS_BUCKET_URL')}}/@{{ ::imgR}}"  alt="hotel" ng-if="imgR.indexOf('http') == - 1 && imgR.indexOf('http') == - 1" onerror="setTimeout(() => { this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available'},15000);">                                                                      
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
                                                    <div class="col-lg-3">
                                                        <span class='room-label-title'>{{ __('labels.what_included')}}</span>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class='room-label-title'>{{ __('labels.room_details')}}</span>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class='room-label-title'>{{ __('labels.total_cost')}}</span>
                                                    </div>
                                                    <div class="col-lg-3"></div>
                                                </div>

                                                <div style="min-height: 100px;margin:10px;padding-top:0px;padding-bottom: 0px;border-bottom:1px solid #ccc;" class="row rooms-tr-meal price_dat_table sub_room-pr pr price-@{{ sub_room.FinalPrice}} index-@{{ room_key}} td-inclusion" data-meal="@{{ sub_room.RatePlanName || 'No Meals' }}"  ng-repeat="sub_room in roomList.sub_rooms" data-price="@{{ sub_room.FinalPrice}}" data-index="@{{ room_key}}" data-currency="@{{ sub_room.Price.CurrencyCode}}" data-room="@{{ sub_room.RoomIndex}}" data-include="@{{ tdClass(sub_room.Inclusion)}}">

                                                    <div class="col-lg-3 pl-0">
                                                        
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
                                                                        <div class="col-md-4" ng-repeat="am in sub_room.Amenity track by $index" ngif="$index < 10">
                                                                            <li class="room-facility-text"  ><i class="fa fa-check"></i>@{{ am}}</li>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" ng-if="!sub_room.Amenity.length">
                                                                        <div class="col-md-4" ng-repeat="am in sub_room.Amenities track by $index" ngif="$index < 10">
                                                                            <li class="room-facility-text"  ><i class="fa fa-check"></i>@{{ am}}</li>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="" ng-if="sub_room.staticData && sub_room.staticData.bed_type && sub_room.staticData.bed_type.beds && sub_room.staticData.bed_type.beds.BedName">
                                                            <i class="fa fa-bed" aria-hidden="true"></i>&nbsp;
                                                            @{{ sub_room.staticData.bed_type.beds.BedName}}
                                                        </span>
                                                        <span class="" ng-if="sub_room.staticData && sub_room.staticData.bed_type && sub_room.staticData.bed_type.room_size && sub_room.staticData.bed_type.room_size.eb">
                                                            {{ __('labels.extra_bed')}}: @{{sub_room.staticData.bed_type.room_size.eb}}
                                                        </span>
                                                        <span class="" ng-if="sub_room.staticData && sub_room.staticData.room_size && sub_room.staticData.room_size.sf">
                                                            {{ __('labels.room_size')}}: @{{sub_room.staticData.room_size.sf[0]}}SF
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="text-success room-price">@{{ sub_room.Price.CurrencyCode}} @{{ sub_room.FinalPrice | number: 2 }}</span> 
                                                        <div class="room-stay-text">
                                                            {{ __('labels.total_cost')}} {{ $input_data['NoOfNights']}} {{ __('labels.nights')}}
                                                            <br>
                                                            {{ $input_data['roomsGuests']}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <!----> 
                                                        <div style="min-height: 100px;padding:0px;" class="price_dat_table select_room_data td-inclusion">
                                                            <div ng-if="sub_room.FinalPrice > lottery_limit" >
                                                                <img  src="/images/lottery-icon.gif" style="width:40px;" />
                                                                <span style="font-size:12px;">{{ __('labels.for_cashback')}}</span>
                                                            </div>
                                                            <form method="POST" target="_blank" action="/room/{{$traceId}}/{{$referral}}/{{$search_id}}/{{$hotel['TBO_data']['HotelCode']}}/{{ $input_data['departdate']}}/{{ $input_data['roomCount']}}/{{ $input_data['NoOfNights']}}?{{$queryVals}}">
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
                                                                <input type="hidden" name="rphoto" value="@{{ roomList.sub_rooms[0].images[0]}}" >
                                                                <input type="hidden" ng-if="roomList.sub_rooms[0].images.length" name="rphoto" value="@{{ roomList.sub_rooms[0].images[0]}}" >
                                                                <input type="hidden" name="queryVals" value="{{$queryVals}}">
                                                                <button type="submit"  class="btn btn-primary" >{{ __('labels.book_now')}}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion showOptionsMobile" id="accordionExample@{{room_key}}">
                                                <div class="row">

                                                    <div class="col-12 mobile-options-list" >
                                                        <button class="btn btn-primary pull-right" type="button" data-toggle="collapse" data-target="#collapseOne@{{room_key}}" aria-expanded="true" aria-controls="collapseOne">
                                                            {{ __('labels.show_op')}} <span class='fa fa-chevron-down'></span>
                                                        </button>
                                                    </div>
                                                    <div class="col-12">
                                                        <div id="collapseOne@{{room_key}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample@{{room_key}}">
                                                            <div style="border-bottom:0px solid #ccc;min-height: 100px;padding:10px;" class="row rooms-tr-meal sub_room-pr pr price-@{{ sub_room.FinalPrice}} index-@{{ room_key}} td-inclusion" data-meal="@{{ sub_room.RatePlanName || 'No Meals' }}"  ng-repeat="sub_room in roomList.sub_rooms" data-price="@{{ sub_room.FinalPrice}}" data-index="@{{ room_key}}" data-currency="@{{ sub_room.Price.CurrencyCode}}" data-room="@{{ sub_room.RoomIndex}}" data-include="@{{ tdClass(sub_room.Inclusion)}}">
                                                                <div class="col-12 padd-right">
                                                                    <ul>
                                                                        <li class="room-facility-text" ng-repeat="am in sub_room.Inclusion track by $index" ngif="$index < 10"><i class="fa fa-check"></i>@{{ am}}</li>
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
                                                                        <form method="POST" target="_blank" action="/room/{{$traceId}}/{{$referral}}/{{$search_id}}/{{$hotel['TBO_data']['HotelCode']}}/{{ $input_data['departdate']}}/{{ $input_data['roomCount']}}/{{ $input_data['NoOfNights']}}?{{$queryVals}}">
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
                                                                            <input type="hidden" ng-if="roomList.sub_rooms[0].images.length" name="rphoto" value="@{{ roomList.sub_rooms[0].images[0]}}" >
                                                                            <input type="hidden" name="queryVals" value="{{$queryVals}}">
                                                                            <button type="submit"  class="btn btn-primary" >{{ __('labels.book_now')}}</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="room-stay-text">
                                                                        {{ __('labels.total_cost')}} {{ $input_data['NoOfNights']}} {{ __('labels.nights')}}
                                                                        <br>
                                                                        {{ $input_data['roomsGuests']}}
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
                                                <span style="font-size:25px;font-weight: 700;" id="total-price"></span>
                                            </div>
                                            <div class="col-lg-4 col-6" style="padding:15px;">
                                                <form target="_blank" method="POST" action="/room/{{$traceId}}/{{$referral}}/{{$search_id}}/{{$hotel['TBO_data']['HotelCode']}}/{{ $input_data['departdate']}}/{{ $input_data['roomCount']}}/{{ $input_data['NoOfNights']}}?{{$queryVals}}">
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
                                                        <input type="hidden" name="queryVals" value="{{$queryVals}}">
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
                                                            {{ __('labels.room')}} <span ng-bind="rCount + 1"></span>: 
                                                            <span id="room-@{{rCount}}">
                                                                @{{rooms['rooms_' + rCount][0].RoomTypeName}}
                                                            </span>
                                                            <br>
                                                            <span>(Adults @{{ hotelSearchInput[rCount]['adults']}}, {{ __('labels.childrens')}} @{{ hotelSearchInput[rCount]['childs']}})</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 40%;">{{ __('labels.room_type')}}</th>
                                                                <th style="width: 15%;" class="whatisIncluded">{{ __('labels.what_included')}}</th>
                                                                <th style="width: 15%;">{{ __('labels.room_details')}}</th>
                                                                <th style="width: 20%;">{{ __('labels.total_cost')}}</th>
                                                                <th style="width: 10%;">{{ __('labels.book')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="room in rooms['rooms_' + rCount]" ng-class="rCount == 0 ? 'first-column' : (combination == 'fixed') ? 'trow not-first-column' : ''" id="tr-@{{rCount}}-@{{ room.RoomIndex}}" class="rooms-tr-meal-twice" data-price="@{{ room.FinalPrice}}" data-meal="@{{ roomList.RatePlanName || 'No Meals' }}" data-include="@{{ tdClass(room.Inclusion)}}" data-name="@{{ room.RoomTypeName}}" ng-init="room_key_index = $index">
                                                                <td >
                                                                    <span ng-bind="room.RoomTypeName"></span>
                                                                    <br>
                                                                    <div class="room-photo">
                                                                        <span ng-if="room.images.length && (room.images[0].indexOf('http') != - 1 || room.images[0].indexOf('www') != - 1)">
                                                                            <img ng-click="showRoomDetails($index)" src="@{{ room.images[0]}}" alt="@{{ roomList.RoomTypeName}}" class="img-responsive mul-rooms" onerror="setTimeout(() => { this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available'},15000);" />
                                                                        </span>
                                                                        <span ng-if="room.images.length && (room.images[0].indexOf('http') == - 1 && room.images[0].indexOf('www') == - 1)">
                                                                            <img ng-click="showRoomDetails($index)" src="{{env('AWS_BUCKET_URL')}}/@{{ room.images[0]}}" alt="@{{ roomList.RoomTypeName}}" class="img-responsive mul-rooms" onerror="setTimeout(() => { this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available'},15000);" />
                                                                        </span>

                                                                        <img  ng-click="showRoomDetails($index)" src="https://b2b.tektravels.com/Images/HotelNA.jpg" alt="@{{ roomList.RoomTypeName}}" class="img-responsive mul-rooms" ng-if="!room.images.length" />
                                                                    </div>
                                                                    <br>
                                                                    <a href="javascript:void(0);" class="tw-text-sm" ng-click="showRoomDetails($index)">{{ __('labels.more_about_room')}}</a>
                                                                </td>
                                                                <td class="whatisIncluded">
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
                                                                <td class="whatisIncluded">
                                                                    <span class="" ng-if="room.staticData && room.staticData.bed_type && room.staticData.bed_type.beds && room.staticData.bed_type.beds.BedName">
                                                                        <i class="fa fa-bed" aria-hidden="true"></i>&nbsp;
                                                                        @{{ room.staticData.bed_type.beds.BedName}}
                                                                    </span>
                                                                    <span class="" ng-if="room.staticData && room.staticData.bed_type && room.staticData.bed_type.room_size && room.staticData.bed_type.room_size.eb">
                                                                        {{ __('labels.extra_bed')}}: @{{room.staticData.bed_type.room_size.eb}}
                                                                    </span>
                                                                    <span class="" ng-if="room.staticData && room.staticData.room_size && room.staticData.room_size.sf">
                                                                        {{ __('labels.room_size')}}: @{{room.staticData.room_size.sf[0]}}SF
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @{{ room.Price.CurrencyCode}} @{{ room.FinalPrice | number: 2 }}
                                                                    <br>
                                                                    <span class="tax-included">{{ __('labels.include_tax')}}</span>
                                                                    <br>
                                                                    <div class="room-stay-text">
                                                                        {{ __('labels.total_cost')}} {{ $input_data['NoOfNights']}} {{ __('labels.nights')}}
                                                                        <br>
                                                                        {{ $input_data['roomsGuests']}}
                                                                    </div>
                                                                    <br>

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
                                                                                                    <li  ng-repeat="imgR in ::room.images track by $index" ng-init="image_key = $index" data-target="#custCarouse_@{{ room_key}}"  data-slide-to="@{{ image_key}}" ng-class="image_key < 1 ? 'active' : ''" ></li>
                                                                                                </ol>
                                                                                                <div class="carousel-inner" >
                                                                                                    <div ng-repeat="imgR in ::room.images track by $index" ng-init="image_key_s = $index" ng-class="image_key_s < 1 ? 'carousel-item active' : 'carousel-item'" > 
                                                                                                        <img class="room-image-slider" src="@{{ ::imgR}}"  alt="hotel" ng-if="imgR.indexOf('http') != - 1 || imgR.indexOf('http') != - 1" onerror="setTimeout(() => { this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available'},15000);"> 
                                                                             
                                                                                                        <img class="room-image-slider" src="{{env('AWS_BUCKET_URL')}}/@{{ ::imgR}}"  alt="hotel" ng-if="imgR.indexOf('http') == - 1 && imgR.indexOf('http') == - 1" onerror="setTimeout(() => { this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available'},15000);">
                                                                                                    </div>
                                                                                                    <div ng-if="!room.images.length" class="carousel-item active"> 
                                                                                                        <img class="room-image-slider" src="https://b2b.tektravels.com/Images/HotelNA.jpg"  alt="hotel">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 amenity" ng-repeat="am in room.Amenity track by $index">
                                                                                                    <span><i class="fa fa-check" aria-hidden="true"></i>&nbsp;@{{ am}}</span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
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
                                                                                        <div class="col-md-4" ng-repeat="am in room.Amenity track by $index" >
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
                            <li style="float:left;">{{ __('labels.checkin')}} {{ $static_data['hotel_time']['@CheckInTime']}}</li>
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
                </div>
            </div>
        </div>
    </section>
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

        setTimeout(function(){

            $('#autocomplete').css('display','block');

        }, 2000);
        

        
    });

</script>

@endsection


<div class="mobileOnlyView" style="position: fixed;bottom: 0px;width: 100%;background:#1e4355;z-index: 5;">
    <div class="col text-center">
        <a href="#" class="mobileOnlyView" style="color:#fff;padding-top:0px;padding-bottom:12px;" data-toggle="modal" data-target="#roomFilterModal">
            <i class="fa fa-filter my-float"></i> {{ __('labels.select_filters')}}
        </a>
    </div>
</div>
