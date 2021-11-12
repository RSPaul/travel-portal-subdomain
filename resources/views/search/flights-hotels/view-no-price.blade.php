@extends('layouts.app-header')
@section('content') 
<input type="hidden" id="isViewHotel" value="1">
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
<section class="listing_banner_forms" class="showProgressLoader" >
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="listing_inner_forms">
                    <input type="hidden" name="staticPage" id="staticPage" value="1">
                    <form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_rooms')}}"  >
                        @csrf

                        <div class="rounding_form_info">
                            <div class="form-group">
                                <label><label>{{ __('labels.cityarea')}}</label> <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                <input id="autocomplete" name="Location" value="{{ $static_data['hotel_name']}}"  placeholder="Enter City , Hotel , Address"  onFocus="geolocate()" type="text" style="color:#333;border:1px solid #ccc;border-radius: 5px;padding:8px;font-size:14px;width:95%;" />

                                    <input type="hidden" name="Latitude" id="Latitude" value="{{ isset($static_data['hotel_location']['@Latitude']) ? $static_data['hotel_location']['@Latitude'] : ''}}">
                                    <input type="hidden" name="Longitude" id="Longitude" value="{{ isset($static_data['hotel_location']['@Longitude']) ? $static_data['hotel_location']['@Longitude'] : ''}}">
                                    <input type="hidden" name="Radius" id="Radius" value="15">
                                    <input type="hidden" name="city_id" id="city_id" value="{{ $city['CityId']}}">
                                    <input type="hidden" name="countryCode" id="country_code" value="{{ $city['CountryCode']}}">
                                    <input type="hidden" name="city_name" id="city_name" value="{{ $city['CityName']}}">
                                    <input type="hidden" name="countryName" id="country_name" value="{{ $city['Country']}}">
                                    <input type="hidden" name="country" id="country" value="{{ $city['CountryCode']}}">
                                    <input type="hidden" name="currency" id="currency" value="">
                                    <input type="hidden" name="ishalal" id="ishalal" value="">
                                    <input type="hidden" name="referral" class="referral" value="">
                                    <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="{{ $static_data['hotel_code']}}">

                            </div>
                            <div class="form-group">
                                <label>{{ __('labels.checkin')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                <div class="input-group" >
                                    <input class="form-control departdate" type="text" name="departdate" required readonly value=""/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('labels.checkout')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                <div class="input-group" >
                                    <input class="form-control returndate" type="text" name="returndate" readonly required value=""/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('labels.roomguests')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                <input type="text" name="roomsGuests" id="roomsGuests" class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="Please select the rooms & guests." value="" readonly>
                                @include('_partials.hotel-guests')
                            </div>
                            <div class="search_btns_listing"><button type="submit" class="btn btn-primary">{{ __('labels.search')}}</button></div>
                        </div>
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
                            @if(isset($static_data) && isset($static_data['hotel_name']))
                            <span class=""><i class="fa fa-building"></i> {{ $static_data['hotel_name']}}</span>
                            @endif
                            <span class=""> - {{ $city['CityName'] }}</span> 
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

<div ng-controller="roomStaticCtrl" ng-app="hotelStaticApp" id="hotelViewPage">


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
                                                <a href="{{ isset($static_data['hotel_images'][0])?$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me second-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][0])?$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}"  >
                                                </a>
                                                <a href="{{ isset($static_data['hotel_images'][1])?$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}" class="hotel_url  show-me third-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][1])?$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}"  >
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-sm-12  p-1">
                                            <div class="hotel_box room-vcol ht_imgs">
                                                <a href="{{ isset($static_data['hotel_images'][2])?$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}" class="hotel_url  show-me primary-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][2])?$static_data['hotel_images'][2]:asset('/images/no-hotel-photo.png')}}"  >
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row room-thumbs" >
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                <a href="{{ isset($static_data['hotel_images'][3])?$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][3])?$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}"  >
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                <a href="{{ isset($static_data['hotel_images'][4])?$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][4])?$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}"  >
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                <a href="{{ isset($static_data['hotel_images'][5])?$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][5])?$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}"  >
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                <a href="{{ isset($static_data['hotel_images'][6])?$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][6])?$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}"  >
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb">
                                                <div class="ht_imgs">
                                                    <div class="hotel_box room-thumb">
                                                        @php $r_images = array(); $roomImagesCount = 0; @endphp
                                                        @foreach($rooms as $room_img)
                                                        @if($room_img->images && $room_img->images != null && !empty($room_img->images))
                                                        @php 
                                                        array_push($r_images , unserialize($room_img->images));
                                                        @endphp
                                                        @endif
                                                        @endforeach
                                                        @php $counter = 0; @endphp
                                                        @foreach($r_images as $r_key => $r_imgs)
                                                        @foreach($r_imgs as $r_key => $r_img)
                                                        @if(strpos($r_img, "_z") !== false)
                                                        <a href="{{ $r_img}}" title="{{ $static_data['hotel_name']}}" class="hotel_url @if($counter == 0) show-me @endif">
                                                            <img  class="hotel_photo img-responsive" src="{{ $r_img}}" alt="hotel">
                                                        </a>
                                                        @php 
                                                        $counter++; $roomImagesCount++; 
                                                        @endphp
                                                        @endif
                                                        @endforeach
                                                        @endforeach
                                                    </div>
                                                    @if($roomImagesCount > 0)
                                                    <div class="overlay_text_md" style="height: 100%;border-radius: 5px;" href="{{ $r_img}}" title="{{ $static_data['hotel_name']}}" >
                                                        <a style="padding: 30% 20% !important;color:#fff;display: block;" href="{{ $r_img}}">+ {{$roomImagesCount}} Photos</a>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="right_hotel_specification" >
                                        <div class="hotel_room_prcs" style="width:100%;">
                                            <div class="room_review_ratings">
                                                <h3>{{ __('labels.rating_review')}}  </h3>
                                                <p>
                                                    <span class="fa fa-star @if($static_data['start_rating'] >= 1) checked @endif"></span>
                                                    <span class="fa fa-star @if($static_data['start_rating'] >= 2) checked @endif"></span>
                                                    <span class="fa fa-star @if($static_data['start_rating'] >= 3) checked @endif"></span>
                                                    <span class="fa fa-star @if($static_data['start_rating'] >= 4) checked @endif"></span>
                                                    <span class="fa fa-star @if($static_data['start_rating'] >= 5) checked @endif"></span>
                                                </p>

                                                <!--Review Rating start-->
                                                @if(isset($static_data['hotel_type']))
                                                <br>
                                                <h3>Hotel Themes </h3>
                                                <div class="safety_features">
                                                    <ul class="list-inline" >
                                                        @foreach($static_data['hotel_type'] as $t_key =>  $type)
                                                        @if($t_key <= 3)
                                                        @if(isset($type) && isset($type['@ThemeName']))
                                                        <li >
                                                            <i class="fa fa-check" aria-hidden="true"></i> 
                                                            {{ $type['@ThemeName']}}
                                                        </li>
                                                        @endif
                                                        @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif

                                                @if(isset($static_data['hotel_time']))
                                                <br>
                                                <h3>Hotel Timings </h3>
                                                <div class="safety_features">
                                                    <ul class="list-inline" >
                                                        <li >
                                                            <i class="fa fa-clock" aria-hidden="true"></i> 
                                                            @if(isset($static_data['hotel_time']) && isset($static_data['hotel_time']['@CheckInTime']))
                                                            Checkin Time {{ $static_data['hotel_time']['@CheckInTime']}} &nbsp; Checkout Time {{ $static_data['hotel_time']['@CheckOutTime']}}
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                @endif


                                            </div>
                                            <!--Review Rating close-->
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

    <section class="room_filters mt-4 desktopOnlyView" id="desktopRoomFilters">
        <div class="container">
            <div class="col-md-12 inner_about_hotels_data attr" >
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group filter-checkbox col-md-4">
                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="room" class="room-type-checkbox" data-on="Rooms" data-off="Rooms" data-height="25" data-width="150">
                            </div>
                            <div class="form-group filter-checkbox col-md-4">
                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="suite" class="room-type-checkbox" data-on="Suite" data-off="Suite" data-height="25" data-width="150">
                                </label>
                            </div>
                            <div class="form-group filter-checkbox col-md-4">
                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="apartment" class="room-type-checkbox" data-on="Apartments" data-off="Apartments" data-height="25" data-width="150">
                                </label>
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
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="form-group filter-checkbox col-md-4">
                                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="room" class="room-type-checkbox" data-on="Rooms" data-off="Rooms" data-height="25" data-width="150">
                                            </div>
                                            <div class="form-group filter-checkbox col-md-4">
                                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="suite" class="room-type-checkbox" data-on="Suite" data-off="Suite" data-height="25" data-width="150">
                                                </label>
                                            </div>
                                            <div class="form-group filter-checkbox col-md-4">
                                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="apartment" class="room-type-checkbox" data-on="Apartments" data-off="Apartments" data-height="25" data-width="150">
                                                </label>
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

    <section class="room_type_data mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- New Html 1 Dec  -->
                    <div id="room-list">
                        @foreach($rooms as $r_key => $room)
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
                                                            @if(strpos($image, "_t") === false)
                                                                <div class="hotel-pic carousel-item @if($i_key ==0) active @endif" > 
                                                                    <img src="{{ $image}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/250X150?text=Image%20Not%20Available';" alt="slide"> 
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>

                                                    <!-- Thumbnails -->

                                                    <ol class="carousel-indicators list-inline" >
                                                        @foreach($r_images as $i_key => $image)
                                                            @if(strpos($image, "_t") === false)
                                                                <li class="list-inline-item @if($i_key ==0) active @endif" > 
                                                                    <a id="carousel-selector-{{ $i_key}}" class="selected" data-slide-to="{{ $i_key}}" data-target="#custCarouse{{ $r_key}}">
                                                                        <img ng-src="{{ $image }}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-8" >
                                            <div class="showOptionsDesktop">
                                                <div class="row" style="margin:8px;border-bottom:1px solid #ccc;">
                                                    <div class="col-lg-4">
                                                        <span class='room-label-title'> What’s Included</span>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <span class='room-label-title'> Room Details</span>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <span class='room-label-title'>Total for stay</span>
                                                    </div>
                                                </div>

                                                <div style="min-height: 100px;margin:10px;padding-top:0px;padding-bottom: 0px;border-bottom:1px solid #ccc;" class="row rooms-tr-meal price_dat_table sub_room-pr pr price-@{{ sub_room.FinalPrice}} index-@{{ room_key}} td-inclusion" data-meal="@{{ sub_room.RatePlanName || 'No Meals' }}"  data-price="@{{ sub_room.FinalPrice}}" data-index="@{{ room_key}}" data-currency="@{{ sub_room.Price.CurrencyCode}}" data-room="@{{ sub_room.RoomIndex}}" data-include="@{{ tdClass(sub_room.Inclusion)}}">
                                                    <div class="col-lg-4">
                                                        @if(isset($room['ameneties']) && !empty($room['ameneties']))
                                                            @php $room_ameneties = json_decode($room['ameneties'], true); @endphp
                                                            <ul >
                                                                @foreach($room_ameneties as $f_k => $r_fac)
                                                                    @if($f_k < 4)
                                                                        <li class="room-facility-text" ><i class="fa fa-check"></i>{{$r_fac}}</li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                            <a class="tw-text-sm" href="javascript:void(0);" data-toggle='modal' data-target='#roomInclusion_{{ $r_key}}'>More Inclusions</a> 
                                                        
                                                        <div id="roomInclusion_{{ $r_key}}" class="modal fade" role="dialog">
                                                            <div class="modal-dialog  modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h3 >{{ $room['name']}}</h3>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row" >
                                                                            <div class="col-md-12" >
                                                                                @foreach($room_ameneties as $f_k => $r_fac)
                                                                                    <div class="col-md-4">
                                                                                        <i class="fa fa-check"></i>{{$r_fac}}
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-4">
                                                        @if(isset($room['bed_type']) && !empty($room['bed_type']))
                                                            @php $bed_type = json_decode($room['bed_type'], true); @endphp
                                                            @if(isset($bed_type['beds']['BedName']))
                                                                <i class="fa fa-bed" aria-hidden="true"></i>&nbsp;{{ $bed_type['beds']['BedName'] }}
                                                            @endif

                                                            @if(isset($bed_type['room_size']['s_m']))
                                                                <br>
                                                                Room Size: <?php print_r($bed_type['room_size']['s_m']); ?>m
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div style="min-height: 100px;padding:0px;" class="price_dat_table select_room_data td-inclusion">
                                                            <button type="button"  class="btn btn-primary show-price-room" > Show Prices</button>
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

    <section class="about_detail_hotels">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="inner_about_hotels_data attr">
                        <h2 class="about_hotel section_title text-center">{{ __('labels.about_hotel')}}</h2>
                        <ul class="list-inline" style="padding: 20px 0px;">
                            @if(isset($static_data) && isset($static_data['hotel_time']) && isset($static_data['hotel_time']['@CheckInTime']))
                            <li style="float:left;">CHECK IN {{ $static_data['hotel_time']['@CheckInTime']}}</li>
                            @endif
                            @if(isset($static_data) && isset($static_data['hotel_time']) && isset($static_data['hotel_time']['@CheckOutTime']))
                            <li style="float:left;">CHECK OUT {{ $static_data['hotel_time']['@CheckOutTime']}} </li>
                            @endif
                        </ul>
                        @if(isset($static_data) && isset($static_data['hotel_description']) && isset($static_data['hotel_description'][0]))
                        <p>{!!html_entity_decode($static_data['hotel_description'][0])!!}</p>
                        @endif
                    </div>
                    @if(isset($static_data) && isset($static_data['attractions']) && sizeof($static_data['attractions']) > 0)
                    <br>
                    <div class="inner_about_hotels_data attr">
                        <h2 class='section_title text-center'>Attractions</h2>
                        @foreach($static_data['attractions'] as $h_at)
                        <p>{!!html_entity_decode($h_at)!!}</p>
                        @endforeach
                    </div>
                    @endif
                    
                    @if(isset($static_data['hotel_facilities']) && sizeof($static_data['hotel_facilities']) > 0)
                    <br>
                    <div class="inner_about_hotels_data attr">
                        <h2 class='section_title text-center'>Ameneties</h2>
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
                        <h2 class='section_title text-center'>Covid Facilities</h2>
                        <div class="row">
                            <ul class="covid-data">
                                @foreach($static_data['hotel_info'] as $c_info)
                                    <li>{{ $c_info }}</li>
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
</div>
@endsection
