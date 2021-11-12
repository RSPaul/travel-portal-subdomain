@extends('layouts.app-header')
@section('content')   
<div ng-app="hotelStaticApp" ng-controller="searchStaticCtrl" id="hotelListPage" class="" ng-init="searchHotels()">
    <section class="listing_banner_forms" >
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="listing_inner_forms">
                        <input type="hidden" name="staticPage" id="staticPage" value="1">
                        <form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_rooms')}}"  >
                            @csrf
                            <div class="rounding_form_info">
                                <div class="form-group">
                                    <label>{{ __('labels.cityarea')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                    <input id="autocomplete" name="Location" value="{{ $city['CityName']}}"  placeholder="Enter City , Hotel , Address"  onFocus="geolocate()" type="text" style="color:#333;border:1px solid #ccc;border-radius: 5px;padding:8px;font-size:14px;width:95%;" />

                                    <input type="hidden" name="Latitude" id="Latitude" value="">
                                    <input type="hidden" name="Longitude" id="Longitude" value="">
                                    <input type="hidden" name="Radius" id="Radius" value="">
                                    <input type="hidden" name="city_id" id="city_id" value="{{ $city['CityId']}}">
                                    <input type="hidden" name="countryCode" id="country_code" value="{{ $city['CountryCode']}}">
                                    <input type="hidden" name="city_name" id="city_name" value="{{ $city['CityName']}}">
                                    <input type="hidden" name="countryName" id="country_name" value="{{ $city['Country']}}">
                                    <input type="hidden" name="country" id="country" value="{{ $city['CountryCode']}}">
                                    <input type="hidden" name="currency" id="currency" value="">
                                    <input type="hidden" name="ishalal" id="ishalal" value="">
                                    <input type="hidden" name="referral" class="referral" value="">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('labels.checkin')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                    <div class="input-group" >
                                        <input class="form-control departdate" type="text" name="departdate" id="departdate" required readonly value="" /> 
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('labels.checkout')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                    <div class="input-group" >
                                        <input class="form-control returndate" type="text" name="returndate" readonly required value="" />
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('labels.roomguests')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                    <input type="text" name="roomsGuests" id="roomsGuests" style="left:10px !important;" readonly class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="">
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
    <section class="listing_title_map data-section" >
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="listing_main_title">
                        <h1 ng-if="loaded"> {{ __('labels.resultmsg')}} <span >{{ $city['CityName']}}</span></h1>
                        <h1 ng-if="!loaded">
                            <div class="block block--short load-animate"></div>
                        </h1>
                        <div class="srt_by_data">
                            <ul>
                                <!-- <li ng-if="loaded">{{ __('labels.sortby')}}:</li>
                                <li class="nav-item dropdown" ng-if="loaded">
                                    <a class="nav-link dropdown-toggle" href="#" id="popularity_data" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('labels.ratings')}}</a>
                                    <div class="dropdown-menu" aria-labelledby="popularity_data">
                                        <a class="dropdown-item" ng-class="{'active' : low_rating}" href="javascript:void(0);" ng-click="sortBy('ratings', 'low', this);">{{ __('labels.ratings_high_low')}}</a>
                                        <a class="dropdown-item" ng-class="{'active' : high_rating}" href="javascript:void(0);" ng-click="sortBy('ratings', 'high', this);">{{ __('labels.ratings_low_high')}}</a>
                                    </div>
                                </li> -->
                                <!-- <li class="nav-item dropdown" ng-if="loaded">
                                    <a class="nav-link dropdown-toggle" href="#" id="popularity_data" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('labels.price')}}</a>
                                    <div class="dropdown-menu" aria-labelledby="popularity_data">
                                        <a class="dropdown-item" ng-class="{'active' : low_price}" href="javascript:void(0);" ng-click="sortBy('price', 'low', this);">{{ __('labels.price_low_high')}}</a>
                                        <a class="dropdown-item" ng-class="{'active' : high_price}" href="javascript:void(0);" ng-click="sortBy('price', 'high', this);">{{ __('labels.price_high_low')}}</a>
                                    </div>
                                </li> -->
                                <li ng-cloak ng-if="loaded">Showing  <span >{{ sizeof($hotels) }}</span> hotels in  <span >{{ $city['CityName']}}</span></li>
                                <li ng-if="!loaded">
                                    <div class="block block--long load-animate"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="map_vews">
                        <!-- <div id="map" ng-show="loaded"></div>
                        <div id="map-loading" class="load-animate" ng-show="!loaded"></div> -->
                        <div class="search_location_data">
                            <input type="search" name="search" ng-model='hotelName'  placeholder="Enter hotel name to filter search results" autocomplete="off" ng-keyup="checkAndClearSearch()">
                            <input type="submit" name="submit" class="btn btn-primary" value="{{ __('labels.search') }}" ng-disabled="!hotelName" ng-click="searchHotel()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="listing_section_data data-section kkkl" >
        <div class="container">
            <div class="row">

                <div class="col-lg-3 listing_third_data" ng-if="!loaded">
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
                <div class="col-lg-9 listing_data_large" ng-if="!loaded">
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

                <div class="col-lg-3" ng-if="loaded">
                    <div class="listing_sidebar hotel">
                        <h3 class="hotel-filters-heading">{{ __('labels.select_filters')}} <a href="javascript:void(0);" class="clear-search" ng-click="clearSearch()"> <i class="fa fa-filter" aria-hidden="true"></i> {{ __('labels.clear_filters')}} </a></h3>
                        

                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.star_category')}}</h4>
                            <div class="check_filter_cont">
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">Unrated</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings-static" value="0" ng-click="filterByRatings('0-star')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['unrated']}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.5_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings-static" value="5" ng-click="filterByRatings('5-star')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['fi_star']}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.4_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings-static" value="4" ng-click="filterByRatings('4-star')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['f_star']}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.3_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings-static" value="3" ng-click="filterByRatings('3-star')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['th_star']}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.2_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings-static" value="2" ng-click="filterByRatings('2-star')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['t_star']}})</span>
                                </label>
                            </div>
                        </div>
                        <div class="popularity_filters_items" >
                            <h4>Locations</h4>
                            <div class="check_filter_cont">
                                <div class="h-amn-list">
                                    @foreach($filters_data['locations'] as $location)
                                    <label class="container_airline" >  
                                        <span class="airline_name">
                                            <span class="air_name_set">{{$location['name']}}</span>
                                        </span>                    
                                        <input type="checkbox" class="hotel-loc-static" value="{{$location['name']}}" ng-click="filterByLocation('{{$location['name']}}', '{{$location['hotels']}}')">
                                        <span class="checkmark"></span>
                                        <span class="value_numbers">({{ $location['hotels'] }})</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="popularity_filters_items">
                            <h4>Hotel Type</h4>
                            <div class="check_filter_cont">
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">Halal</span>
                                    </span>                    
                                    <input type="checkbox" class="hotel-types-static" value="yes" ng-click="filterByTypes()">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['isHalal']}})</span>
                                </label>
                            </div>
                        </div>
                        <div class="popularity_filters_items">
                            <h4>TripAdvisor Rating </h4>
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
                                    <input type="checkbox" class="tp-ratings-static" value="0.0" ng-click="filterByTPRatings('0.0', '{{$filters_data['unrated_t']}}')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['unrated_t']}})</span>
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
                                    <input type="checkbox" class="tp-ratings-static" value="1.0" ng-click="filterByTPRatings('0.0', '{{$filters_data['tp_one']}}')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['tp_one']}})</span>
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
                                    <input type="checkbox" class="tp-ratings-static" value="1.5" ng-click="filterByTPRatings('0.0', '{{$filters_data['tp_one']}}')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['tp_one_h']}})</span>
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
                                    <input type="checkbox" class="tp-ratings-static" value="2.0" ng-click="filterByTPRatings('0.0', '{{$filters_data['tp_two']}}')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['tp_two']}})</span>
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
                                    <input type="checkbox" class="tp-ratings-static" value="2.5" ng-click="filterByTPRatings('0.0', '{{$filters_data['tp_two_h']}}')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['tp_two_h']}})</span>
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
                                    <input type="checkbox" class="tp-ratings-static" value="3.0" ng-click="filterByTPRatings('0.0', '{{$filters_data['tp_three']}}')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['tp_three']}})</span>
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
                                    <input type="checkbox" class="tp-ratings-static" value="3.5" ng-click="filterByTPRatings('0.0', '{{$filters_data['tp_three_h']}}')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['tp_three_h']}})</span>
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
                                    <input type="checkbox" class="tp-ratings-static" value="4.0" ng-click="filterByTPRatings('0.0', '{{$filters_data['tp_four']}}')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['tp_four']}})</span>
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
                                    <input type="checkbox" class="tp-ratings-static" value="4.5" ng-click="filterByTPRatings('0.0', '{{$filters_data['tp_four_h']}}')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['tp_four_h']}})</span>
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
                                    <input type="checkbox" class="tp-ratings-static" value="5.0" ng-click="filterByTPRatings('0.0', '{{$filters_data['tp_five']}}')">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">({{ $filters_data['tp_five']}})</span>
                                </label>
                            </div>
                        </div>
                        
                        @if(isset($filters_data['h_amenities']) && sizeof($filters_data['h_amenities']) > 0)
                        <div class="popularity_filters_items" >
                            <h4>Hotel Amenities</h4>
                            <div class="check_filter_cont">
                                <div class="h-amn-list">
                                    @foreach($filters_data['h_amenities'] as $h_amn)
                                    <label class="container_airline" >  
                                        <span class="airline_name">
                                            <span class="air_name_set">{{$h_amn}}</span>
                                        </span>                    
                                        <input type="checkbox" class="hotel-amns-static" value="{{$h_amn}}" ng-click="filterByHAmenities('{{$h_amn}}')">
                                        <span class="checkmark"></span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="check_filter_cont text-right">
                                <span class="airline_name">
                                    <span class="air_name_set">
                                        <a href="javascript:void(0);" ng-click="toggleHAmenities('h-amn-list')">@{{toggleHAmenitiesFlag ? 'View Less' : 'View More'}}</a>
                                    </span>
                                </span>
                            </div>
                        </div>
                        @endif

                        @if(isset($filters_data['r_amenities']) && sizeof($filters_data['r_amenities']) > 0)
                        <div class="popularity_filters_items" >
                            <h4>Room Amenities</h4>
                            <div class="check_filter_cont">
                                <div class="r-amn-list">
                                    @foreach($filters_data['r_amenities'] as $r_amn)
                                    <label class="container_airline" >  
                                        <span class="airline_name">
                                            <span class="air_name_set">{{$r_amn}}</span>
                                        </span>                    
                                        <input type="checkbox" class="hotel-amns-static"  value="{{$r_amn}}" ng-click="filterByHAmenities('{{$r_amn}}')">
                                        <span class="checkmark"></span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="check_filter_cont text-right">
                                <span class="airline_name">
                                    <span class="air_name_set">
                                        <a href="javascript:void(0);" ng-click="toggleRAmenities('r-amn-list')">@{{toggleRAmenitiesFlag ? 'View Less' : 'View More'}}</a>
                                    </span>
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-9">

                    <!-- hotel without API -->
                    <div class="listing_items_data" ng-if="loaded">
                        @foreach($hotels as $hotel)
                        <div class="listing_item hotel-item" data-rating="{{$hotel['start_rating']}}" data-tprating="{{$hotel['tp_ratings']}}" data-location="{{ isset($hotel['hotel_address']) ? $hotel['hotel_address']['CityName'] : $city['CityName'] }}" data-htype="{{$hotel['ishalal']}}" data-facility="@foreach($hotel['hotel_facilities'] as $f_key => $facility) {{ $facility }} @endforeach" ng-click="viewHotel($event, '{{$hotel['hotel_name']}}', '{{$hotel['hotel_code']}}')" data-name="{{ $hotel['hotel_name']}}">

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="listing_description_data">
                                        <div class="listing_slider_thumbnail">
                                            <!-- Updated Hotel layout with single image -->
                                            @if(isset($hotel['hotel_images']))
                                                <img class="hotel-result-img" src="{{ $hotel['hotel_images']}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/250X150?text=Image%20Not%20Available';" alt="slide">
                                            @else
                                                <img class="hotel-result-img" src="https://via.placeholder.com/250X150?text=Image%20Not%20Available" onerror="this.onerror=null;this.src='https://via.placeholder.com/250X150?text=Image%20Not%20Available';" alt="slide">
                                            @endif

                                        </div>
                                        <div class="listing_main_desp" >
                                            <h2 >
                                                {{ $hotel['hotel_name']}}

                                                @if(isset($hotel['hotel_address']) && isset($hotel['hotel_address']['AddressLine']) && isset($hotel['hotel_address']['AddressLine'][0]))
                                                    <span class="country_name">{{ $hotel['hotel_address']['AddressLine'][0] }} {{ ($hotel['hotel_address']['CityName']) ? ', '  . $hotel['hotel_address']['CityName'] : ''}} </span>
                                                @endif

                                            </h2>

                                            <span class="fa fa-star @if(intval($hotel['start_rating']) >= 1) checked @endif"></span>
                                            <span class="fa fa-star @if(intval($hotel['start_rating']) >= 2) checked @endif"></span>
                                            <span class="fa fa-star @if(intval($hotel['start_rating']) >= 3) checked @endif"></span>
                                            <span class="fa fa-star @if(intval($hotel['start_rating']) >= 4) checked @endif"></span>
                                            <span class="fa fa-star @if(intval($hotel['start_rating']) >= 5) checked @endif"></span>


                                            @if(isset($hotel['hotel_address']) && isset($hotel['hotel_address']['AddressLine']) )

                                            <p class="distance_data" >
                                                @if(isset($hotel['hotel_address']['CityName']) && isset($hotel['hotel_address']['PostalCode']))
                                                    {{ $hotel['hotel_address']['CityName'] }} 
                                                    @if(!is_array($hotel['hotel_address']['PostalCode']))
                                                    , {{ $hotel['hotel_address']['PostalCode']}}
                                                    @endif
                                                @endif
                                            </p>
                                            @endif                                            

                                            <div class="listing_tags_data" >
                                                @foreach($hotel['hotel_facilities'] as $f_key => $facility)
                                                    <a href="javascript:void(0);" class="listing_kids_option" >
                                                        {{ $facility}}
                                                    </a>
                                                @endforeach
                                            </div>

                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="listing_rice_info">
                                        <div class="listing_rice_info">

                                            <div class="actual_price" style="text-align:center;">
                                                <a href="javascript:void(0);" class="btn btn-primary " ng-click="showPrice()">Show Prices</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- hotel without API ends -->

                </div>            
            </div>
        </div>
    </section>
</div>
@endsection
