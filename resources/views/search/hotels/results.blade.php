@extends('layouts.app-header')
@section('content')   
<input type="hidden" id="domain" value="{{ env('APP_URL')}}">
<div ng-app="hotelApp" ng-controller="searchCtrl" id="hotelListPage" class="" ng-init="searchHotels(true)">
    <section class="listing_banner_forms" style="padding:5px 0px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="listing_inner_forms">
                        <form method="GET" name="searchForm" id="searchRoomsForm" >
                            @csrf
                            <div class="rounding_form_info mobileOnlyView" id="stickMobileMenu">
                                <span class="mobileOnlyView" style="font-size: 14px;float: left;width: 75%;margin-top: 5px;color:#1e4355;"><b>{{ $input['city_name']}}</b>, {{  date('d F', strtotime($input['departdate']))}}-{{  date('d F', strtotime($input['returndate']))}}, {{ $input['roomsGuests']}}</span>
                                <button style="padding:6px 10px !important;float: right;margin-left: -5px;font-size: 15px;margin-bottom: 8px;background-color: #1e4355;color:#fff;" type="button" class="btn btn-outline-dark btn-sm mobileOnlyView toggleFormHSearch"><i class="fa fa-search"></i> Edit <i class="fa fa-chevron-down"></i> </button>
                            </div>
                            <div class="rounding_form_info searchHotelMobileForm" style="border-right:5px solid #b3cedd;border-left:5px solid #b3cedd;width:100%">

                                <div class="row">
                                    <div class="col-lg-3" style="border-radius:4px;padding: 2px;background:#fff;border:5px solid #b3cedd;">
                                        <div class="form-group" style="margin:0px !important;">
                                            <input id="autocomplete" name="Location" value="{{ $input['Location']}}"  placeholder="{{ __('labels.search_placeholder') }}"  onFocus="geolocate()" type="text" style="color:#333;border:0px solid #ccc;border-radius: 5px;padding:11px 6px;font-size:14px;width:100%;" />


                                            <input type="hidden" name="Latitude" id="Latitude" value="{{ $input['Latitude']}}">
                                            <input type="hidden" name="Longitude" id="Longitude" value="{{ $input['Longitude']}}">
                                            <input type="hidden" name="Radius" id="Radius" value="{{ $input['Radius']}}">
                                            <input type="hidden" name="city_id" id="city_id" value="{{ $input['city_id']}}">
                                            <input type="hidden" name="countryCode" id="country_code" value="{{ isset($input['countryCode']) ? $input['countryCode'] : ''}}">
                                            <input type="hidden" name="city_name" id="city_name" value="{{ isset($input['city_name']) ? $input['city_name'] : ''}}">
                                            <input type="hidden" name="countryName" id="country_name" value="{{ $input['countryName']}}">
                                            <input type="hidden" name="country" id="country" value="{{ isset($input['countryCode']) ? $input['countryCode'] : ''}}">
                                            <input type="hidden" name="currency" id="currency" value="{{ $input['currency']}}">
                                            <input type="hidden" name="ishalal" id="ishalal" value="{{ $input['ishalal']}}">
                                            <input type="hidden" name="referral" class="referral" value="{{ $referral}}">
                                            
                                            <input type="hidden" id="nights_lbl" value="{{ __('labels.nights') }}">
                                            <input type="hidden" id="rooms_lbl" value="{{ __('labels.rooms') }}">
                                            <input type="hidden" id="night_lbl" value="{{ __('labels.night') }}">
                                            <input type="hidden" id="room_lbl" value="{{ __('labels.room') }}">
                                            <input type="hidden" id="local_sel" value="{{Session::get('locale')}}">
                                            <input type="hidden" id="adults_lbl" value="{{ __('labels.adults') }}">
                                            <input type="hidden" id="childrens_lbl" value="{{ __('labels.childrens') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-5" style="border-radius:4px;padding: 2px;background:#fff;border:5px solid #b3cedd;">
                                        <div class="row" style="margin:0px;">
                                            <div class="col-12" style="padding:0px 5px;">
                                                <div class="form-group" style="margin:0px;">
                                                    <div class="small_station_info departDay1 text-center total-nights" >
                                                        @if(Session::get('locale') == 'heb')
                                                            @if ($input['NoOfNights'] > 1)
                                                                {{ __('labels.night') }}&nbsp;<span style="position:absolute;">{{ $input['NoOfNights']}}</span>
                                                            @else
                                                                {{ __('labels.nights') }}&nbsp;<span style="position:absolute;">{{ $input['NoOfNights']}}</span>
                                                            @endif
                                                        @else
                                                            {{ $input['NoOfNights']}} 
                                                            @if ($input['NoOfNights'] > 1)
                                                                {{ __('labels.night') }}
                                                            @else
                                                                {{ __('labels.nights') }}
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="input-group" >
                                                        <input type="text" id="dateRange" value="{{ $input['departdate']}} - {{ $input['returndate']}}" class="form-control  text-center" />
                                                        <input id="departHotel" type="hidden" name="departdate" value="{{ $input['departdate']}}" />
                                                        <input id="returnHotel" type="hidden" name="returndate" value="{{ $input['returndate']}}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2" style="border-radius:4px;padding:2px;background:#fff;border:5px solid #b3cedd;">
                                        <div class="form-group" style="margin:0px !important;">
                                            <div class="small_station_info  text-center" id="guestRooms" >
                                                @if(Session::get('locale') == 'heb')
                                                    {{ __('labels.rooms') }}&nbsp;<span style="position:absolute;">1</span>
                                                @else
                                                    1 {{ __('labels.rooms') }}
                                                @endif
                                            </div>
                                            <div class="input-group" >
                                                <input type="text" name="roomsGuests" id="roomsGuests" style=" width:100%;text-align: center;font-size:14px;" readonly class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 {{ __('labels.rooms') }}" value="{{ $input['roomsGuests']}}">
                                                @include('_partials.hotel-guests-edit')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2" style="padding:0px;border:3px solid #b3cedd;">
                                        <div class="search_btns_listing" style="padding:2px;">
                                            <button type="submit" style="width:100%;border-radius: 4px !important;" class="btn btn-primary"  >{{ __('labels.search')}}</button>
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
    <section class="listing_title_map mobileOnlyView" >
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 searchHotelMobileFormMap">
                    <div class="map_vews">
                        <div class="search_location_data">
                            <input type="search" name="search" id="hotelNameMob"  ng-model='hotelNameMob'  placeholder="Enter hotel name to filter search results" autocomplete="off" ng-change="checkAndClearSearch()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="listing_section_data data-section kkkl" infinite-scroll="loadMore()"  infinite-scroll-distance="1" >
        <div class="mapviewlayout"  id="map" ></div>
        <div class="container" id="gridlistview">
            @if($isAgent)
            <div class="row" ng-if="loaded">
                <div ng-class="selectedHotel.length ? 'col-lg-9' : 'col-lg-10'">
                </div>
                <div ng-class="selectedHotel.length ? 'col-lg-3 email-hotels' : 'col-lg-2 email-hotels'">
                    <button href="javascript:void(0);" class="btn btn-primary email-hotels" ng-disabled="!selectedHotel.length"  data-target="#hotel-email-modal" data-toggle="modal">{{ __('labels.email_hotels') }} <span ng-if="selectedHotel.length">(<span ng-bind="selectedHotel.length"></span>)</span></button>
                </div>
            </div>
            @endif
            <div class="row">

                <div class="col-lg-3 listing_third_data"  ng-if="!loaded">
                    <div class="listing_sidebar">
                        <h3>{{ __('labels.select_filters')}}</h3>
                        <div class="popularity_filters_items">
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
                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.star_category')}}</h4>
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

                <!-- loader -->
                <div class="col-lg-9 listing_data_large" id="hotel-loader">
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
                <div id="hotelFilterModal" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="roomFilterModalLabel">{{ __('labels.select_filters')}} </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span> {{ __('labels.close_label')}}
                                </button>
                            </div>
                            <div class="modal-body">
                                <section class="room_filters mt-4">
                                    <div class="container">
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

                                        <h3 class="hotel-filters-heading">{{ __('labels.select_filters')}} <a href="javascript:void(0);" class="clear-search" ng-click="clearSearch()"> <i class="fa fa-filter" aria-hidden="true"></i> {{ __('labels.clear_filters')}} </a></h3>
                                        <div class="popularity_filters_items">
                                            <h4>{{ __('labels.price_range')}}</h4>
                                            <div class="check_filter_cont">
                                                <div class="slidecontainer" ng-if="highstPrice > 0">
                                                    <!-- <label class="filters-label">Price Range</label> -->
                                                    <input type="range" min="500" max="@{{highstPrice}}" ng-value="highstPrice" class="slider" id="pirceRange" ng-change="filterByPrice(priceRange)" ng-model="priceRange">
                                                    <br>
                                                    <span class="range-price max"> @{{priceRange| currency:searchData.currency}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="popularity_filters_items">
                                            <h4>{{ __('labels.distance')}}</h4>
                                            <div class="check_filter_cont">
                                                <div class="slidecontainer">
                                                    <input type="range" min="1" max="20" value="20" class="slider" id="distanceRange" ng-change="filterByDistance(distanceRange)" ng-model="distanceRange">
                                                    <br>
                                                    <span class="range-price max"> @{{distanceRange}} Km</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="popularity_filters_items">
                                            <h4>{{ __('labels.star_category')}}</h4>
                                            <div class="check_filter_cont">
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">{{ __('labels.unrated')}}</span>
                                                    </span>                    
                                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('0')" value="0">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ unrated}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">{{ __('labels.5_star')}}</span>
                                                    </span>                    
                                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('5')" value="5">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ fi_star}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">{{ __('labels.4_star')}}</span>
                                                    </span>                    
                                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('4')" value="4">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ f_star}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">{{ __('labels.3_star')}}</span>
                                                    </span>                    
                                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('3')" value="3">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ th_star}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">{{ __('labels.2_star')}}</span>
                                                    </span>                    
                                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('2')" value="2">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ t_star}})</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="popularity_filters_items" ng-if="locations.length">
                                            <h4>{{ __('labels.locations')}}</h4>
                                            <div class="check_filter_cont">
                                                <div class="h-amn-list">
                                                    <label class="container_airline" ng-repeat="loc in locations track by $index">  
                                                        <span class="airline_name">
                                                            <span class="air_name_set">@{{loc.name}}</span>
                                                        </span>                    
                                                        <input type="checkbox" class="hotel-loc" ng-click="filterByLocation(loc.name, loc.hotels)" value="@{{loc.name}}">
                                                        <span class="checkmark"></span>
                                                        <span class="value_numbers">(@{{ loc.hotels}})</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="popularity_filters_items">
                                            <h4>{{ __('labels.hotel_type')}}</h4>
                                            <div class="check_filter_cont">
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">{{ __('labels.halal')}}</span>
                                                    </span>                    
                                                    <input type="checkbox" class="hotel-types" ng-click="filterByTypes()" value="halal">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ isHalal}})</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="popularity_filters_items">
                                            <h4>{{ __('labels.tp_ratings')}}</h4>
                                            <div class="check_filter_cont">
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">
                                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                        </span>
                                                    </span>                    
                                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('0.0', unrated_t)" value="0.0">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ unrated_t}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">
                                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star "></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                        </span>
                                                    </span>                    
                                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('1.0', tp_one)" value="1.0">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ tp_one}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">
                                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star-half-o checked-half"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                        </span>
                                                    </span>                    
                                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('1.5', tp_one_h)" value="1.5">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ tp_one_h}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">
                                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                        </span>
                                                    </span>                    
                                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('2.0', tp_two)" value="2.0">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ tp_two}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">
                                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star-half-o checked-half"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                        </span>
                                                    </span>                    
                                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('2.5', tp_two_h)" value="2.5">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ tp_two_h}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">
                                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                        </span>
                                                    </span>                    
                                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('3.0', tp_three)" value="3.0">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ tp_three}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">
                                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star-half-o checked-half"></span>
                                                            <span class="fa fa-star"></span>
                                                        </span>
                                                    </span>                    
                                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('3.5', tp_three_h)" value="3.5">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ tp_three_h}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">
                                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star"></span>
                                                        </span>
                                                    </span>                    
                                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('4.0', tp_four)" value="4.0">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ tp_four}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">
                                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star-half-o checked-half"></span>
                                                        </span>
                                                    </span>                    
                                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('4.5', tp_four_h)" value="4.5">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ tp_four_h}})</span>
                                                </label>
                                                <label class="container_airline">  
                                                    <span class="airline_name">
                                                        <span class="air_name_set">
                                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                        </span>
                                                    </span>                    
                                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('5.0', tp_five)" value="5.0">
                                                    <span class="checkmark"></span>
                                                    <span class="value_numbers">(@{{ tp_five}})</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="popularity_filters_items" ng-if="h_amenities.length">
                                            <h4>{{ __('labels.hotel_amns')}}</h4>
                                            <div class="check_filter_cont">
                                                <div class="h-amn-list">
                                                    <label class="container_airline" ng-repeat="h_amn in h_amenities track by $index">  
                                                        <span class="airline_name">
                                                            <span class="air_name_set">@{{h_amn}}</span>
                                                        </span>                    
                                                        <input type="checkbox" class="hotel-amns" ng-click="filterByHAmenities(h_amn)" value="@{{h_amn}}">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="check_filter_cont text-right">
                                                <span class="airline_name">
                                                    <span class="air_name_set">
                                                        <a href="javascript:void(0);" ng-click="toggleHAmenities()">
                                                            <span ng-if="toggleHAmenitiesFlag">{{ __('labels.view_less')}}</span>
                                                            <span ng-if="!toggleHAmenitiesFlag">{{ __('labels.view_more')}}</span>
                                                        </a>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="popularity_filters_items" ng-if="r_amenities.length">
                                            <h4>{{ __('labels.room_amns')}}</h4>
                                            <div class="check_filter_cont">
                                                <div class="h-amn-list">
                                                    <label class="container_airline" ng-repeat="r_amn in r_amenities track by $index">  
                                                        <span class="airline_name">
                                                            <span class="air_name_set">@{{r_amn}}</span>
                                                        </span>                    
                                                        <input type="checkbox" class="hotel-amns" ng-click="filterByHAmenities(r_amn)" value="hamn-@{{r_amn}}">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="check_filter_cont text-right">
                                                <span class="airline_name">
                                                    <span class="air_name_set">
                                                        <a href="javascript:void(0);" ng-click="toggleRAmenities()">
                                                            <span ng-if="toggleRAmenitiesFlag">{{ __('labels.view_less')}}</span>
                                                            <span ng-if="!toggleRAmenitiesFlag">{{ __('labels.view_more')}}</span>
                                                        </a>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 desktopOnlyView ListHotelOnLOadFilter"  ng-if="loaded" style="display: none;">
                    <div class="listing_sidebar hotel" style="background:#fff;">
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
                        <div class="popularity_filters_items">
                            <div class="search_location_data">
                                <input type="search" style="background:#e3f6ff;"  ng-model='hotelName' name="search" id="hotelName" placeholder="{{ __('labels.search_hotel_lbl')}}" autocomplete="off" ng-change="checkAndClearSearch()">
                            </div>
                        </div>
                        <h3 class="hotel-filters-heading">{{ __('labels.select_filters')}} <a href="javascript:void(0);" class="clear-search" ng-click="clearSearch()"> <i class="fa fa-filter" aria-hidden="true"></i> {{ __('labels.clear_filters')}} </a></h3>
                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.price_range')}}</h4>
                            <div class="check_filter_cont">
                                <div class="slidecontainer">
                                    
                                    <input type="range" min="500" max="@{{highstPrice}}" value="@{{highstPrice}}" class="slider" id="pirceRange" ng-change="filterByPrice(priceRange)" ng-model="priceRange">
                                    <br>
                                    <span class="range-price max"> @{{(priceRange)| currency:hotels[0].TBO_data.Price.CurrencyCode}}</span>
                                </div>
                            </div>
                        </div>


                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.distance')}}</h4>
                            <div class="check_filter_cont">
                                <div class="slidecontainer">

                                    <input type="range" min="1" max="20" value="20" class="slider" id="distanceRange" ng-change="filterByDistance(distanceRange)" ng-model="distanceRange">
                                    <br>
                                    <span class="range-price max"> @{{distanceRange}} Km</span>
                                </div>
                            </div>
                        </div>
                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.star_category')}}</h4>
                            <div class="check_filter_cont">
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.unrated')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('0')" value="0">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ unrated}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.5_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('5')" value="5">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ fi_star}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.4_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('4')" value="4">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ f_star}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.3_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('3')" value="3">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ th_star}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.2_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('2')" value="2">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ t_star}})</span>
                                </label>
                            </div>
                        </div>
                        <div class="popularity_filters_items" ng-if="locations.length">
                            <h4>{{ __('labels.locations')}}</h4>
                            <div class="check_filter_cont">
                                <div class="h-amn-list">
                                    <label class="container_airline" ng-repeat="loc in locations track by $index">  
                                        <span class="airline_name">
                                            <span class="air_name_set">@{{loc.name}}</span>
                                        </span>                    
                                        <input type="checkbox" class="hotel-loc" ng-click="filterByLocation(loc.name, loc.hotels)" value="@{{loc.name}}">
                                        <span class="checkmark"></span>
                                        <span class="value_numbers">(@{{ loc.hotels}})</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.hotel_type')}}</h4>
                            <div class="check_filter_cont">
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.halal')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="hotel-types" ng-click="filterByTypes()" value="halal">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ isHalal}})</span>
                                </label>
                            </div>
                        </div>
                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.tp_ratings')}} </h4>
                            <div class="check_filter_cont">
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">
                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                        </span>
                                    </span>                    
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('0.0', unrated_t)" value="0.0">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ unrated_t}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">
                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star "></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                        </span>
                                    </span>                    
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('1.0', tp_one)" value="1.0">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ tp_one}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">
                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star-half-o checked-half"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                        </span>
                                    </span>                    
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('1.5', tp_one_h)" value="1.5">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ tp_one_h}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">
                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                        </span>
                                    </span>                    
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('2.0', tp_two)" value="2.0">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ tp_two}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">
                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star-half-o checked-half"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                        </span>
                                    </span>                    
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('2.5', tp_two_h)" value="2.5">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ tp_two_h}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">
                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                        </span>
                                    </span>                    
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('3.0', tp_three)" value="3.0">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ tp_three}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">
                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star-half-o checked-half"></span>
                                            <span class="fa fa-star"></span>
                                        </span>
                                    </span>                    
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('3.5', tp_three_h)" value="3.5">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ tp_three_h}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">
                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                        </span>
                                    </span>                    
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('4.0', tp_four)" value="4.0">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ tp_four}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">
                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star-half-o checked-half"></span>
                                        </span>
                                    </span>                    
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('4.5', tp_four_h)" value="4.5">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ tp_four_h}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">
                                            <i class="fa fa-tripadvisor" aria-hidden="true"></i>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                        </span>
                                    </span>                    
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('5.0', tp_five)" value="5.0">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ tp_five}})</span>
                                </label>
                            </div>
                        </div>

                        <div class="popularity_filters_items" ng-if="h_amenities.length">
                            <h4>{{ __('labels.hotel_amns')}}</h4>
                            <div class="check_filter_cont">
                                <div class="h-amn-list">
                                    <label class="container_airline" ng-repeat="h_amn in h_amenities track by $index">  
                                        <span class="airline_name">
                                            <span class="air_name_set">@{{h_amn}}</span>
                                        </span>                    
                                        <input type="checkbox" class="hotel-amns" ng-click="filterByHAmenities(h_amn)" value="@{{h_amn}}">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="check_filter_cont text-right">
                                <span class="airline_name">
                                    <span class="air_name_set">
                                        <a href="javascript:void(0);" ng-click="toggleHAmenities()">
                                            <span ng-if="toggleHAmenitiesFlag">{{ __('labels.view_less')}}</span>
                                            <span ng-if="!toggleHAmenitiesFlag">{{ __('labels.view_more')}}</span>
                                        </a>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="popularity_filters_items" ng-if="r_amenities.length">
                            <h4>{{ __('labels.room_amns')}}</h4>
                            <div class="check_filter_cont">
                                <div class="h-amn-list">
                                    <label class="container_airline" ng-repeat="r_amn in r_amenities track by $index">  
                                        <span class="airline_name">
                                            <span class="air_name_set">@{{r_amn}}</span>
                                        </span>                    
                                        <input type="checkbox" class="hotel-amns" ng-click="filterByHAmenities(r_amn)" value="hamn-@{{r_amn}}">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="check_filter_cont text-right">
                                <span class="airline_name">
                                    <span class="air_name_set">
                                        <a href="javascript:void(0);" ng-click="toggleRAmenities()">
                                            <span ng-if="toggleRAmenitiesFlag">{{ __('labels.view_less')}}</span>
                                            <span ng-if="!toggleRAmenitiesFlag">{{ __('labels.view_more')}}</span>
                                        </a>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9" id="hotelcardsview">
                    <div class="row ListHotelOnLOadFilter2" style="display: none;">
                        <div class="col-lg-7">
                            <div class="srt_by_data">
                                <ul>
                                    <!--                                    <li ng-if="loaded">{{ __('labels.sortby')}}:</li>-->
                                    <li class="nav-item dropdown" ng-if="loaded">
                                        <a class="nav-link dropdown-toggle" href="#" id="popularity_data" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-sort-amount-asc"></span> {{ __('labels.ratings')}}</a>
                                        <div class="dropdown-menu" aria-labelledby="popularity_data">
                                            <a class="dropdown-item" ng-class="{'active' : low_rating}" href="javascript:void(0);" ng-click="sortBy('ratings', 'low', this);"><span class="fa fa-sort-amount-desc"></span> {{ __('labels.ratings_high_low')}}</a>
                                            <a class="dropdown-item" ng-class="{'active' : high_rating}" href="javascript:void(0);" ng-click="sortBy('ratings', 'high', this);"><span class="fa fa-sort-amount-asc"></span> {{ __('labels.ratings_low_high')}}</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown" ng-if="loaded">
                                        <a class="nav-link dropdown-toggle" href="#" id="popularity_data" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-sort-amount-asc"></span> {{ __('labels.price')}}</a>
                                        <div class="dropdown-menu" aria-labelledby="popularity_data">
                                            <a class="dropdown-item" ng-class="{'active' : low_price}" href="javascript:void(0);" ng-click="sortBy('price', 'low', this);"><span class="fa fa-sort-amount-asc"></span> {{ __('labels.price_low_high')}}</a>
                                            <a class="dropdown-item" ng-class="{'active' : high_price}" href="javascript:void(0);" ng-click="sortBy('price', 'high', this);"><span class="fa fa-sort-amount-desc"></span> {{ __('labels.price_high_low')}}</a>
                                        </div>
                                    </li>
                                    <li ng-cloak ng-if="loaded">{{ __('labels.showing')}}  <span ng-bind="hotelCount"></span> {{ __('labels.hotels')}} {{ __('labels.in_label')}}  <span ng-bind="searchData['city_name']"></span></li>
                                    <li ng-if="!loaded">
                                        <div class="block block--long load-animate"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-5 text-right">
                            <nav class="nav justify-content-end" style="padding-bottom: 15px;">
                                <div class="nav nav-pills" id="nav-tab" role="tablist">
                                    <a class="btn-sm nav-link active" id="hotel_list_tab" ng-click="setLayout('list');" data-toggle="tab" href="#hotel_listing" role="tab" aria-controls="hotel_listing" aria-selected="true" href="javascript:void(0);" class="nav-link btn btn-sm btn-light"><i class="fa fa-list"></i> {{ __('labels.list_view')}}</a>   
                                    <a class="btn-sm nav-link desktopOnlyView" id="hotel_grid_tab"  ng-click="setLayout('grid');"  data-toggle="tab" href="#hotel_listing" role="tab" aria-controls="hotel_listing" aria-selected="true" href="javascript:void(0);" class="nav-link btn btn-sm btn-light"><i class="fa fa-th"></i> {{ __('labels.grid_view')}}</a>                                
                                    <a class="btn-sm nav-link" id="hotel_map_tab"  ng-click="setLayout('map');"  data-toggle="tab" href="#hotel_map_view" role="tab" aria-controls="hotel_map_view" aria-selected="true" href="javascript:void(0);" class="nav-link btn btn-sm btn-light"><i class="fa fa-map-marker"></i> {{ __('labels.map_view')}}</a>                                
                                </div>
                            </nav>
                        </div>
                    </div>
                    <div class="listing_items_data" id="db-hotels">
                        @foreach($hotels as $hotel)

                        <div class="row" id="ListLayoutViews">
                             <div class="col-12 hotelgrids">
                                <div class="row listing_item hotel-item">
                                @php
                                $h_images = json_decode($hotel['hotel_images'], true);
                                @endphp
                                <!-- Updated Hotel layout with single image -->
                                @if(isset($h_images) && isset($h_images[0]))
                                <div class="col-5  @{{ (hotelLayout == 'grid')?'col-lg-12':'col-lg-3'}} hotelview imageoverlay" style="@if(strpos($h_images[0], 'http') !== false || strpos($h_images[0], 'www') !== false ) background:url({{ $h_images[0]  }}); @else background:url({{env('AWS_BUCKET_URL')}}/{{ $h_images[0] }}); @endif" >
                                @else
                                <div class="col-5  @{{ (hotelLayout == 'grid')?'col-lg-12':'col-lg-3'}} hotelview imageoverlay" style="background:url(https://via.placeholder.com/250X150?text=Image%20Not%20Available);">
                                @endif

                                </div>

                                <div class="col-7  @{{ (hotelLayout == 'grid')?'col-lg-12':'col-lg-9'}} hotelview" style='margin:auto;'>
                                    <div class="listing_description_data hotel_desc" >
                                        <div class="row" >
                                            <div class="@{{ (hotelLayout == 'grid')?'col-lg-12':'col-lg-8'}} hotel_desview " >
                                                <div class="listing_main_desp">
                                                    <h2>
                                                        {{ $hotel['hotel_name']}}

                                                        @php
                                                        $address = json_decode($hotel['hotel_address'], true);
                                                        @endphp

                                                        @if(isset($address) && isset($address['AddressLine']) && isset($address['AddressLine'][0]))
                                                        <span class="country_name">
                                                            {{ $address['AddressLine'][0]}} 
                                                            @if(isset($address['CityName']))
                                                            ,{{$address['CityName']}} 

                                                            @if(isset($address['PostalCode']) && is_array($address['PostalCode']))
                                                            - <?php print_r($address['PostalCode']); ?>
                                                            @else 
                                                            - {{ $address['PostalCode']}}
                                                            @endif
                                                            @endif
                                                        </span>
                                                        @endif

                                                    </h2>

                                                    <span class="fa fa-star @if(intval($hotel['start_rating']) >= 1) checked @endif"></span>
                                                    <span class="fa fa-star @if(intval($hotel['start_rating']) >= 2) checked @endif"></span>
                                                    <span class="fa fa-star @if(intval($hotel['start_rating']) >= 3) checked @endif"></span>
                                                    <span class="fa fa-star @if(intval($hotel['start_rating']) >= 4) checked @endif"></span>
                                                    <span class="fa fa-star @if(intval($hotel['start_rating']) >= 5) checked @endif"></span>


                                                    @php
                                                    $facilities = json_decode($hotel['hotel_facilities'], true);
                                                    @endphp
                                                    <div class="listing_tags_data" >

                                                        @if(isset($facilities) && !empty($facilities))
                                                        @foreach($facilities as $f_key => $facility)
                                                        @if($f_key < 5)
                                                        <a href="javascript:void(0);" class="listing_kids_option" >
                                                            {{ $facility}}
                                                        </a>
                                                        @endif
                                                        @endforeach
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="@{{ (hotelLayout == 'grid')?'col-lg-12':'col-lg-4'}} text-right hotel_price_box" style="max-height:150px;">
                                                <div class="listing_rice_info">
                                                    <div class="listing_rice_info">

                                                        <div class="actual_price" style="text-align:center;">
                                                            <div class="content-section">
                                                                <div class="content-info side">
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
                        </div>

                        @endforeach
                    </div>
                    <!-- hotel without API -->

                    <div class="tab-content ListHotelOnLOad" id="nav-tabContent" style="display: none;">
                        <div class="tab-pane fade show active"   id="hotel_listing" role="tabpanel" aria-labelledby="hotel_list_tab">
                            <div  class="listing_items_data"   ng-if="hotels.length" >
                                <div class="row @{{ (hotelLayout == 'grid')?'HotelGridView':''}}" id="ListLayoutViews">
                                    <div  class="col-12 hotelgrids @{{ (hotelLayout == 'grid')?'col-lg-4':''}}" data-htype="@{{ (hotel.static_data.isHalal == 'yes')?'halal':''}}" data-rating="@{{ hotel.TBO_data.h_rating}}" data-price="@{{ hotel.TBO_data.Price.OfferedPriceRoundedOff + (iniscomm / 100 * hotel.TBO_data.Price.OfferedPriceRoundedOff)}}" data-code="@{{ hotel.TBO_data.HotelCode}}" ng-repeat="hotel in hotels track by $index" ng-init="h_key = $index" ng-click="viewHotel($event, hotel, referral)" data-tprating="@{{ hotel.static_data.tp_ratings}}" data-location="@{{ (hotel.static_data && hotel.static_data.hotel_address) ? hotel.static_data.hotel_address.CityName : searchData['Location']}}" data-distance="@{{ hotel.static_data.distance}}">
                                        <div class="row listing_item hotel-item" >
                                            <div class="col-12 cashbackflag" ng-if="hotel.TBO_data.FinalPrice > lottery_limit">
                                                <span ><img  src="/images/lottery-icon.gif" style="width:30px;" /> {{ __('labels.for_cashback')}}</span>                      
                                            </div>


                                            <div class="col-5  @{{ (hotelLayout == 'grid')?'col-lg-12':'col-lg-3'}} hotelview imageoverlay" style="background:url(@{{ hotel.static_data.hotel_images || 'https://via.placeholder.com/250X150?text=Image%20Not%20Available' }});" ng-if="(hotel.static_data.hotel_images.indexOf('http') !== -1 || hotel.static_data.hotel_images.indexOf('www') !== -1)">
                                                <div class="pt-30" ng-if="hotel.TBO_data.FinalPrice > {{ Session::get('lotteryLimit') }}"></div>
                                                <span class="distanceflag" ng-if="hotel.static_data && hotel.static_data.distance">
                                                    @{{ hotel.static_data.distance}} Km
                                                </span>
                                                <span class="tprating" ng-if='hotel.static_data.tp_ratings > 0' ><img style='width:22px;' src='images/tp-logo.png' /> @{{hotel.static_data.tp_ratings}} / 5</span>
                                            </div>

                                            <div class="col-5  @{{ (hotelLayout == 'grid')?'col-lg-12':'col-lg-3'}} hotelview imageoverlay" style="background:url({{env('AWS_BUCKET_URL')}}/@{{ hotel.static_data.hotel_images}});" ng-if="(hotel.static_data.hotel_images.indexOf('http') === -1 && hotel.static_data.hotel_images.indexOf('www') === -1)">

                                                <div class="pt-30" ng-if="hotel.TBO_data.FinalPrice > {{ Session::get('lotteryLimit') }}"></div>
                                                <span class="distanceflag" ng-if="hotel.static_data && hotel.static_data.distance">
                                                    @{{ hotel.static_data.distance}} Km
                                                </span>
                                                <span class="tprating" ng-if='hotel.static_data.tp_ratings > 0' ><img style='width:22px;' src='images/tp-logo.png' /> @{{hotel.static_data.tp_ratings}} / 5</span>

                                            </div>
                                            <div class="col-7  @{{ (hotelLayout == 'grid')?'col-lg-12':'col-lg-9'}} hotelview" style='margin:auto;'>
                                                <div class="pt-30" ng-if="hotel.TBO_data.FinalPrice > {{ Session::get('lotteryLimit') }}"></div>
                                                <div class="listing_description_data hotel_desc" >
                                                    <div class="row" >
                                                        <div class="@{{ (hotelLayout == 'grid')?'col-lg-12':'col-lg-8'}} hotel_desview " >
                                                            <div class="listing_main_desp" ng-cloak>
                                                                <h2 ng-if="hotel.static_data && hotel.static_data.id && hotel.static_data.hotel_name != ''">
                                                                    @{{ hotel.static_data.hotel_name}} 

                                                                    <span ng-if="hotel.static_data.hotel_address && hotel.static_data.hotel_address.AddressLine && hotel.static_data.hotel_address.AddressLine.length" class="country_name">@{{ hotel.static_data.hotel_address.AddressLine[0]}} @{{ (hotel.static_data.hotel_address.CityName) ? ', ' + hotel.static_data.hotel_address.CityName : ''}} </span>

                                                                </h2>

                                                                <span class="country_name" ng-if="!hotel.static_data.id">@{{ hotel.TBO_data.HotelAddress}}</span>

                                                                <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 1 ? 'checked' : ''"></span>
                                                                <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 2 ? 'checked' : ''"></span>
                                                                <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 3 ? 'checked' : ''"></span>
                                                                <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 4 ? 'checked' : ''"></span>
                                                                <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 5 ? 'checked' : ''"></span>


                                                                <p class="distance_data" ng-if="hotel.static_data && hotel.static_data.hotel_address && hotel.static_data.hotel_address.AddressLine && !hotel.static_data.hotel_address.AddressLine.length">
                                                                    @{{ hotel.static_data.hotel_address.CityName}} , @{{ hotel.static_data.hotel_address.PostalCode}}
                                                                </p>

                                                                <div class="listing_tags_data" ng-if="hotel.static_data && hotel.static_data.hotel_facilities && hotel.static_data.hotel_facilities.length">
                                                                    <a href="javascript:void(0);" class="listing_kids_option" ng-repeat="facility in hotel.static_data.hotel_facilities| limitTo:5 track by $index">
                                                                        @{{ facility}}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="@{{ (hotelLayout == 'grid')?'col-lg-12':'col-lg-4'}} text-right hotel_price_box">
                                                            <div class="actual_price pull-right text-right">
                                                                <h4 style="font-size:12px;">
                                                                    <span ng-if="hotel.TBO_data.discount > 0"  class="offer_discount" >@{{hotel.TBO_data.discount}}% OFF</span><br/>
                                                                    <span style='color:black;line-height:30px;text-decoration:line-through'>
                                                                        <span style='color:red;'>@{{ hotel.TBO_data.Price.CurrencyCode}} @{{ hotel.TBO_data.FinalPrice + ((hotel.TBO_data.FinalPrice / 100) * hotel.TBO_data.discount) | number : 2}}</span>
                                                                    </span>
                                                                    <br/>
                                                                    <span style="color:rgb(6, 170, 6);line-height:10px;font-size:18px;font-weight:bold;">@{{ hotel.TBO_data.Price.CurrencyCode}} @{{ hotel.TBO_data.FinalPrice | number : 2 }}</span>
                                                                </h4>
                                                            </div>
                                                            <!-- Social Meadia -->
                                                            <div class="share-post-icons" style="margin-top:60px;margin-left:0px;" id="post_share_icon_@{{ hotel.static_data.hotel_name}} ">
                                                                <div class="row">
                                                                    <div class="col-12 text-right">
                                                                        <i style="padding-top: 16px;" class="fa fa-share-alt" ng-click="showSocialMedia(hotel.TBO_data.HotelCode)" aria-hidden="true"></i> &nbsp;&nbsp; <i  style="padding-top: 16px;" class="fa fa-clipboard" id="copy-@{{hotel.TBO_data.HotelCode}}" ng-click="copyToClip($event, hotel, referral)" aria-hidden="true"></i></div>
                                                                    @if($isAgent)
                                                                    <div class="email-hotel-check form-check col-4">
                                                                        <input type="checkbox" class="form-check-input" name="email-hotels" value="@{{hotel.TBO_data.HotelCode}}" ng-click="toggleSelection(hotel.TBO_data.HotelCode)">
                                                                        <span class="airline_name">
                                                                            <span class="air_name_set">{{ __('labels.email_label')}}</span>
                                                                        </span>  
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                <div class="row mediaicons show-social-icons-@{{ hotel.TBO_data.HotelCode}}"  style="display: none;">
                                                                    <a class="share-to fb-clr" data-type="fb" ng-click="viewHotelFB($event, hotel, referral, 'fb')">
                                                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                                                    </a>
                                                                    <a class="share-to whatsp-clr" data-type="wp" ng-click="viewHotelFB($event, hotel, referral, 'wts')">
                                                                        <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                                                    </a>
                                                                    <a class="share-to tw-clr" data-type="tw" ng-click="viewHotelFB($event, hotel, referral, 'tw')">
                                                                        <i class="fa fa-twitter" aria-hidden="true"></i>
                                                                    </a>
                                                                    <a class="share-to pint-clr" data-type="pt" ng-click="viewHotelFB($event, hotel, referral, 'pt')">
                                                                        <i class="fa fa-pinterest" aria-hidden="true"></i>
                                                                    </a>
                                                                    <a class="share-to stb-clr" data-type="stb" ng-click="viewHotelFB($event, hotel, referral, 'stb')">
                                                                        <i class="fa fa-stumbleupon" aria-hidden="true"></i>
                                                                    </a>
                                                                    <a class="share-to lkn-clr" data-type="lkn" ng-click="viewHotelFB($event, hotel, referral, 'lkn')">
                                                                        <i class="fa fa-linkedin" aria-hidden="true"></i>
                                                                    </a>
                                                                    <a class="share-to inst-clr" data-type="inst" ng-click="viewHotelFB($event, hotel, referral, 'inst')">
                                                                        <i class="fa fa-instagram" aria-hidden="true"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <!-- Ends  -->

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- hotel without API ends -->

                            <div class="container loading" ng-if="busy && !loadingMore">
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

                            <div class="container loading" ng-if="busy && hotels.length <= hotelCount">
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

                            <div class="listing_items_data no-data" ng-if="noResults && !hotels.length" >
                                <h3> {{ __('labels.no_result_found')}} </h3>
                            </div>

                            <div class="listing_items_data no-data" ng-if="noMoreLoad" >
                                <h3> {{ __('labels.no_more_hotel')}} </h3>

                        </div>
                        <div class="tab-pane fade" id="hotel_map_view" role="tabpanel" aria-labelledby="hotel_map_tab">
                            <div id="hotel_map_view">
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
        </div>
    </section>

    <div id="hotel-email-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form ng-submit="sendSelectedHotels()" name="sendHotelEmail">
                <div class="modal-content">
                    <div class="modal-header refresh-header">
                        <h3 class="refresh-header text-center">{{ __('labels.send_itineraries')}}</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ __('labels.enter_email')}}</label>
                            <input type="email" name="hotelEmail" ng-model="hotelEmail" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="submit" value="@{{sendText}}" class="btn btn-primary" ng-disabled="sendHotelEmail.$invalid">
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<div id="sessionWarningModal" class="modal fade" role="dialog" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header refresh-header">
                <h3 class="refresh-header text-center">{{ __('labels.session_expired')}}</h3>
            </div>
            <div class="modal-body">
                <p>{{ __('labels.session_expired_msg')}}</p>        
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-primary refresh-btn">{{ __('labels.refresh_search')}}</a>
            </div>
        </div>
    </div>
</div>


<div class="mobileOnlyView" style="position: fixed;bottom: 0px;width: 100%;background:#1e4355;z-index: 5;">
    <div class="col text-center">
        <a href="#" class="mobileOnlyView" style="color:#fff;padding-top:0px;padding-bottom:12px;" data-toggle="modal" data-target="#hotelFilterModal">
            <i class="fa fa-filter my-float"></i> {{ __('labels.select_filters')}}
        </a>
    </div>
</div>

<style>
    .pac-container:after {
        /* Disclaimer: not needed to show 'powered by Google' if also a Google Map is shown */

        /*        background-image: none !important;
                height: 0px;*/
    }
    .pac-container .pac-item{
        word-wrap: break-word;
    }

    .markerBubbleBx{
        background:#000;
    }



    .main_footer{
        display:none;
    }
    .mapviewlayout{
        position:fixed !important;
        width:100% !important;
        height:100vh !important;
        display:none;
    }
    #hotelcardsview{
        padding-left:8px;
        padding-right:8px;
    }

    .hotelcardsview{
        height:50px;
        background:#fff;
        padding:6px;
    }

    section.listing_section_data{
        padding:0px !important;
    }

    #gridlistview{
        padding-top:15px;
    }
    .stickyHeader{
        position: sticky !important;
        z-index: 1;
        background: #fff;
        top:0px;
    }

    @media (max-width: 767px) {
        .hotelcardsview{
            height:auto;
        }
        #nav-tab{
            margin:0 auto;
        }

    }
    .total-nights{
        display:block;
    }
    .share-post-icons .mediaicons{
        margin-left:0px !important;
    }
    
</style>
<script>

    $(document).ready(function () {
        $("#hotel_grid_tab").click(function () {
            $(".hotelgrids").addClass('col-lg-3');
            $(".hotelview").addClass('col-lg-12');
            $("#ListLayoutViews").addClass("HotelGridView");
            $(".hotel_desview").removeClass('col-lg-8');
            $(".hotel_price_box").removeClass('col-lg-4');
            $(".hotel_desview,.hotel_price_box").addClass('col-12');
            $(".listing_banner_forms").show();
            $(".mapviewlayout").hide();
            $("#hotelcardsview").removeClass("hotelcardsview");
            $(".main_header").removeClass("stickyHeader");
            $('#hotel_listing').show();
        });
        $("#hotel_list_tab").click(function () {
            $(".hotelgrids").removeClass('col-lg-3');
            $(".hotelview").removeClass('col-lg-12');
            $("#ListLayoutViews").removeClass("HotelGridView");
            $(".hotel_desview,.hotel_price_box").removeClass('col-12');
            $(".hotel_desview").addClass('col-lg-8');
            $(".hotel_price_box").addClass('col-lg-4');
            $(".listing_banner_forms").show();
            $(".mapviewlayout").hide();
            $("#hotelcardsview").removeClass("hotelcardsview");
            $(".main_header").removeClass("stickyHeader");
            $('#hotel_listing').show();
        });
        $("#hotel_map_tab").click(function () {
            $(".listing_banner_forms").hide();
            $("#hotelcardsview").addClass("hotelcardsview");
            $(".mapviewlayout").show();
            $(".main_header").addClass("stickyHeader");
            $('#hotel_listing').css('display', 'none');
        });
    });

</script>
@endsection
