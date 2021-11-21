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
                    <form method="GET" name="searchForm" style="margin-bottom:0px;" id="searchRoomsForm" action="{{ route('search_rooms')}}"  >
                        @csrf
                        <div class="rounding_form_info" style="border-right:5px solid #b3cedd;border-left:5px solid #b3cedd;">
                            <div class="row">
                                <div class="col-lg-3" style="border-radius:4px;padding: 2px;background:#fff;border:5px solid #b3cedd;">
                                    <div class="form-group" style="margin:0px !important;">
                                        <input  id="autocomplete" name="Location" value="{{ $static_data['hotel_name']}}"  placeholder="{{ __('labels.search_placeholder') }}"  onFocus="geolocate()" type="text" style="color:#333;border:0px solid #ccc;padding:8px;font-size:14px;height:40px;width:100%;display: block !important;" />



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
                                        <input type="hidden" name="referral" class="referral" value="{{$referral}}">
                                        <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="{{ $static_data['hotel_code']}}">

                                    </div>
                                </div>
                                <div class="col-lg-5" style="border-radius:4px;padding: 2px;background:#fff;border:5px solid #b3cedd;">

                                    <div class="row" style="margin:0px;">
                                        <div class="col"  style="padding:0px 5px;">
                                            <div class="form-group" style="margin:0px !important;">
                                                <div class="small_station_info departDay1 text-center" >{{ __('labels.checkin') }}</div>
                                                <div class="input-group" >
                                                    <input style="text-align:center;" type="text" id="dateRange" value="{{ date('d-m-Y') }} - {{ date('d-m-Y', strtotime(date('d-m-Y') . ' +1 day')) }}" class="form-control  text-center" />
                                                    <input id="departHotel" type="hidden" name="departdate" value="{{ date('d-m-Y') }}" />
                                                    <input id="returnHotel" type="hidden" name="returndate" value="{{ date('d-m-Y', strtotime(date('d-m-Y') . ' +1 day')) }}" />                                                   
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="col-lg-2" style="border-radius:4px;padding:2px;background:#fff;border:5px solid #b3cedd;">
                                   <div class="form-group" style="margin:0px !important;">
                                        @if(Session::get('locale') == 'heb')
                                        <div class="small_station_info" id="guestRooms" style="text-align: center;">{{ __('labels.rooms') }} <span style="position:absolute;">1</span></div>
                                        @else
                                        <div class="small_station_info" id="guestRooms" style="text-align: center;">1 {{ __('labels.rooms') }}</div>
                                        @endif
                                        <div class="input-group text-center">
                                            @if(Session::get('locale') == 'heb')
                                            <input type="text" name="roomsGuests" id="roomsGuests" readonly class="form-control text-center" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="חדר אחד 2 אורח">
                                            @else
                                            <input type="text" name="roomsGuests" id="roomsGuests" readonly class="form-control text-center" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ __('labels.room_guests') }}">
                                            @endif
                                            @include('_partials.hotel-guests')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2" style="padding:0px;border:3px solid #b3cedd;">
                                    <div class="search_btns_listing" style="padding:0px;border:3px solid #b3cedd;">
                                        <button type="submit" style="width:100%;border-radius: 4px !important;" class="btn btn-primary">{{ __('labels.search')}}</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>  
<!-- <section class="listing_banner_forms no-index bread-cums">
    <div class="container">
        <div class="row">
            <div class="">
                <p><a href="/">Home</a>&nbsp;<i class="fa fa-angle-double-right"></i> 
                    <a href="/discover/more-countries">Top Destinations</a>&nbsp;<i class="fa fa-angle-double-right"></i> 
                    <a href="/discover/country/{{$city['CountryCode']}}">{{$city['Country']}}</a>&nbsp;<i class="fa fa-angle-double-right"></i> 
                    <a href="/hotels/{{strtolower(str_replace(' ', '-', $city['Country']))}}/{{strtolower(str_replace(' ', '-', $city['CityName']))}}/{{$city['CityId']}}">{{$city['CityName']}}</a>&nbsp;<i class="fa fa-angle-double-right"></i> 
                    @if(isset($static_data) && isset($static_data['hotel_name']))
                        <span class=""> {{ $static_data['hotel_name']}}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</section> -->
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
                            {{ __('labels.from')}} <span class=""><i class="fa fa-calendar"></i>  {{ date('d-m-Y') }}</span>
                            {{ __('labels.to')}} <span class=""><i class="fa fa-calendar"></i>  {{ date('d-m-Y', strtotime(date('d-m-Y') . ' +1 day')) }}</span>
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
                                                        @foreach($rooms as $room_img)
                                                        @if($room_img->images && $room_img->images != null && !empty($room_img->images))
                                                        @php 
                                                        array_push($r_images , unserialize($room_img->images));
                                                        @endphp
                                                        @endif
                                                        @endforeach
                                                        @php $counter = 0; @endphp
                                                        @foreach($r_images as $r_key1 => $r_imgs)
                                                        @foreach($r_imgs as $r_key2 => $r_img)
                                                        <a href="{{ $r_img}}" title="{{ $static_data['hotel_name']}}" class="hotel_url @if($counter == 0) show-me @endif">
                                                            @if(strpos($r_img, 'http') !== false || strpos($r_img, 'www') !== false)
                                                                <a href="{{ $r_img}}" title="{{ $static_data['hotel_name']}}" class="hotel_url @if($counter == 0) show-me @endif">
                                                                    <img  class="hotel_photo img-responsive" src="{{ $r_img}}" alt="hotel" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                </a>
                                                            @else
                                                                <a href="{{env('AWS_BUCKET_URL')}}/{{ $r_img}}" title="{{ $static_data['hotel_name']}}" class="hotel_url @if($counter == 0) show-me @endif">
                                                                    <img  class="hotel_photo img-responsive" src="{{env('AWS_BUCKET_URL')}}/{{ $r_img}}" alt="hotel" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                </a>
                                                            @endif
                                                        </a>
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
                                <div class="col-lg-4">
                                    <div id="hotelmap" style="min-height:536px;">
                                    
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
                        <?php $KEY = 0; ?>
                        @foreach($rooms as $r_key => $room)
                        @if($room['name'] && $room['name'] != '')
                        @if($KEY < 5)
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
                                                                    @if(strpos($image, 'http') !== false || strpos($image, 'www') !== false)
                                                                        <img ng-src="{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                    @else
                                                                        <img ng-src="{{env('AWS_BUCKET_URL')}}/{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>

                                                    <!-- Thumbnails -->

                                                    <ol class="carousel-indicators list-inline" style="display: none;">
                                                        @foreach($r_images as $i_key => $image)
                                                            @if(strpos($image, "_t") === false)
                                                                <li class="list-inline-item @if($i_key ==0) active @endif" > 
                                                                    <a id="carousel-selector-{{ $i_key}}" class="selected" data-slide-to="{{ $i_key}}" data-target="#custCarouse{{ $r_key}}">
                                                                        @if(strpos($image, 'http') !== false || strpos($image, 'www') !== false)
                                                                            <img ng-src="{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                        @else
                                                                            <img ng-src="{{env('AWS_BUCKET_URL')}}/{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                        @endif
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-8" >
                                            <div class="">
                                                <div class="row" style="margin:8px;border-bottom:1px solid #ccc;">
                                                    <div class="col-lg-4 showOptionsDesktop">
                                                        <span class='room-label-title'> What’s Included</span>
                                                    </div>
                                                    <div class="col-lg-4 showOptionsDesktop">
                                                        <span class='room-label-title'> Room Details</span>
                                                    </div>
                                                    <div class="col-lg-4 total-stay">
                                                        <span class='room-label-title'>Total for stay</span>
                                                    </div>
                                                </div>

                                                <div style="min-height: 100px;margin:10px;padding-top:0px;padding-bottom: 0px;" class="row rooms-tr-meal price_dat_table sub_room-pr pr price-@{{ sub_room.FinalPrice}} index-@{{ room_key}} td-inclusion" data-meal="@{{ sub_room.RatePlanName || 'No Meals' }}"  data-price="@{{ sub_room.FinalPrice}}" data-index="@{{ room_key}}" data-currency="@{{ sub_room.Price.CurrencyCode}}" data-room="@{{ sub_room.RoomIndex}}" data-include="@{{ tdClass(sub_room.Inclusion)}}">
                                                    <div class="col-lg-4 showOptionsDesktop">
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
                                                    <div class="col-lg-4 showOptionsDesktop">
                                                        @if(isset($room['bed_type']) && !empty($room['bed_type']))
                                                            @php $bed_type = json_decode($room['bed_type'], true); @endphp
                                                            @if(isset($bed_type['beds']['BedName']))
                                                                <i class="fa fa-bed" aria-hidden="true"></i>&nbsp;{{ $bed_type['beds']['BedName'] }}
                                                            @endif

                                                            @if(isset($bed_type['room_size']['sm']) && !empty($bed_type['room_size']['sm']))
                                                                <br>
                                                                Room Size: <?php print_r($bed_type['room_size']['sm']); ?>sqm
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
                        <?php $KEY++; ?>
                        @endif
                        @endif
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

<script>
    
    $(document).ready(function(){

        const uluru = { lat: <?php if(isset($static_data['lat'])) { echo $static_data['lat']; } else { echo '10
        13498'; } ?>, lng: <?php if(isset($static_data['lng'])) { echo $static_data['lng']; } else { echo '10
        13498'; } ?> };
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
