@extends('layouts.app-header')
@section('content')   
<div ng-app="hotelApp" ng-controller="searchCtrl" id="hotelListPage" class="showProgressLoader" ng-init="searchHotelsRaw()">
    <section class="listing_banner_forms" >
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="listing_inner_forms">
                        <form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_hotels_raw')}}"  >
                            @csrf
                            <div class="rounding_form_info">
                                <div class="form-group">
                                    <label>{{ __('labels.cityarea')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                    <input id="autocomplete" name="Location" value="{{ $input['Location']}}"  placeholder="Enter City , Hotel , Address"  onFocus="geolocate()" type="text" style="color:#333;border:1px solid #ccc;border-radius: 5px;padding:8px;font-size:14px;width:95%;" />

                                    <input type="hidden" name="Latitude" id="Latitude" value="{{ $input['Latitude']}}">
                                    <input type="hidden" name="Longitude" id="Longitude" value="{{ $input['Longitude']}}">
                                    <input type="hidden" name="Radius" id="Radius" value="{{ $input['Radius']}}">
                                    <input type="hidden" name="city_id" id="city_id" value="{{ $input['city_id']}}">
                                    <input type="hidden" name="countryCode" id="country_code" value="{{ $input['countryCode']}}">
                                    <input type="hidden" name="city_name" id="city_name" value="{{ $input['city_name']}}">
                                    <input type="hidden" name="countryName" id="country_name" value="{{ $input['countryName']}}">
                                    <input type="hidden" name="country" id="country" value="{{ $input['countryCode']}}">
                                    <input type="hidden" name="currency" id="currency" value="{{ $input['currency']}}">
                                    <input type="hidden" name="ishalal" id="ishalal" value="{{ $input['ishalal']}}">
                                    <input type="hidden" name="referral" class="referral" value="{{ $referral}}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('labels.checkin')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                    <div class="input-group" >
                                        <input class="form-control departdate" type="text" name="departdate" id="departdate" required readonly value="{{ $input['departdate']}}" /> {{ $input['NoOfNights'] }} <?php if($input['NoOfNights'] > 1 ) { echo  'Nights'; }else { echo 'Night'; } ?>
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('labels.checkout')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                    <div class="input-group" >
                                        <input class="form-control returndate" type="text" name="returndate" readonly required value="{{ $input['returndate']}}" />
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('labels.roomguests')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                    <input type="text" name="roomsGuests" id="roomsGuests" style="left:10px !important;" readonly class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ $input['roomsGuests']}}">
                                    @include('_partials.hotel-guests-edit')
                                </div>
                                <div class="search_btns_listing"><button type="submit" class="btn btn-primary">{{ __('labels.search')}}</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="listing_title_map data-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="listing_main_title">
                        <h1 ng-if="loaded"> {{ __('labels.resultmsg')}} <span ng-bind="searchData['Location']"></span></h1>
                        <h1 ng-if="!loaded">
                            <div class="block block--short load-animate"></div>
                        </h1>
                        <div class="srt_by_data">
                            <ul>
                                <li ng-if="loaded">{{ __('labels.sortby')}}:</li>
                                <li class="nav-item dropdown" ng-if="loaded">
                                    <a class="nav-link dropdown-toggle" href="#" id="popularity_data" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('labels.ratings')}}</a>
                                    <div class="dropdown-menu" aria-labelledby="popularity_data">
                                        <a class="dropdown-item" ng-class="{'active' : low_rating}" href="javascript:void(0);" ng-click="sortBy('ratings', 'low', this);">{{ __('labels.ratings_high_low')}}</a>
                                        <a class="dropdown-item" ng-class="{'active' : high_rating}" href="javascript:void(0);" ng-click="sortBy('ratings', 'high', this);">{{ __('labels.ratings_low_high')}}</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown" ng-if="loaded">
                                    <a class="nav-link dropdown-toggle" href="#" id="popularity_data" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('labels.price')}}</a>
                                    <div class="dropdown-menu" aria-labelledby="popularity_data">
                                        <a class="dropdown-item" ng-class="{'active' : low_price}" href="javascript:void(0);" ng-click="sortBy('price', 'low', this);">{{ __('labels.price_low_high')}}</a>
                                        <a class="dropdown-item" ng-class="{'active' : high_price}" href="javascript:void(0);" ng-click="sortBy('price', 'high', this);">{{ __('labels.price_high_low')}}</a>
                                    </div>
                                </li>
                                <li ng-cloak ng-if="loaded">Showing  <span ng-bind="hotelCount"></span> hotels in  <span ng-bind="searchData['Location']"></span></li>
                                <li ng-if="!loaded">
                                    <div class="block block--long load-animate"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="map_vews">
                        <div id="map" ng-show="loaded"></div>
                        <div id="map-loading" class="load-animate" ng-show="!loaded"></div>
                        <div class="search_location_data">
                            <input type="search" name="search" ng-model='hotelName'  placeholder="Enter hotel name to filter search results" autocomplete="off" ng-keyup="checkAndClearSearch()">
                            <input type="submit" name="submit" class="btn btn-primary" value="{{ __('labels.search') }}" ng-disabled="!hotelName" ng-click="searchHotel()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="listing_section_data data-section kkkl">
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

                        <!-- <div class="popularity_filters_items">
                           <h4>Locality</h4>
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
                        </div> -->

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

                        <!-- <div class="popularity_filters_items">
                           <h4>User Rating</h4>
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
                        </div> -->
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

                    <!-- <div class="container loading">
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
                    </div> -->

                </div>

                <div class="col-lg-3" ng-if="loaded">
                    <div class="listing_sidebar hotel">
                        <div class="tw-w-full lg:tw-w-1/3 tw-px-3" data-v-f5a8416e="" id="sessionExpiryTimerDiv">
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
                                          <div class="expire-text">This offer will expire in</div>
                                          <strong id="sessionExpiryTimer"></strong>
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
                                <div class="slidecontainer">
                                    <!-- <label class="filters-label">Price Range</label> -->
                                    <input type="range" min="500" max="500000" value="500000" class="slider" id="pirceRange" ng-change="filterByPrice(priceRange)" ng-model="priceRange">
                                    <br>
                                    <span class="range-price max"> @{{priceRange| currency:hotels[0].TBO_data.Price.CurrencyCode}}</span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="popularity_filters_items">
                           <h4>Locality</h4>
                           <div class="check_filter_cont">
                              <label class="container_airline">  
                              <span class="airline_name">
                              <span class="air_name_set">Bur Dubai</span>
                              </span>                    
                              <input type="checkbox">
                              <span class="checkmark"></span>
                              </label>
                              <label class="container_airline">  
                              <span class="airline_name">
                              <span class="air_name_set">Deira</span>
                              </span>                    
                              <input type="checkbox">
                              <span class="checkmark"></span>
                              </label>
                              <label class="container_airline">  
                              <span class="airline_name">
                              <span class="air_name_set">Dubai Marina</span>
                              </span>                    
                              <input type="checkbox">
                              <span class="checkmark"></span>
                              </label>
                              <label class="container_airline">  
                              <span class="airline_name">
                              <span class="air_name_set">Media City</span>
                              </span>                    
                              <input type="checkbox">
                              <span class="checkmark"></span>
                              </label>
                           </div>
                        </div> -->
                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.star_category')}}</h4>
                            <div class="check_filter_cont">
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">Unrated</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('0-star')" value="0-star">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ unrated}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.5_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('5-star')" value="5-star">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ fi_star}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.4_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('4-star')" value="4-star">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ f_star}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.3_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('3-star')" value="3-star">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ th_star}})</span>
                                </label>
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.2_star')}}</span>
                                    </span>                    
                                    <input type="checkbox" class="user-ratings" ng-click="filterByRatings('2-star')" value="2-star">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ t_star}})</span>
                                </label>
                            </div>
                        </div>
                        <div class="popularity_filters_items" ng-if="locations.length">
                            <h4>Locations</h4>
                            <div class="check_filter_cont">
                                <div class="h-amn-list">
                                    <label class="container_airline" ng-repeat="loc in locations track by $index">  
                                        <span class="airline_name">
                                            <span class="air_name_set">@{{loc.name}}</span>
                                        </span>                    
                                        <input type="checkbox" class="hotel-loc" ng-click="filterByLocation(loc.name, loc.hotels)" value="loc-@{{loc.name}}">
                                        <span class="checkmark"></span>
                                        <span class="value_numbers">(@{{ loc.hotels }})</span>
                                    </label>
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
                                    <input type="checkbox" class="hotel-types" ng-click="filterByTypes()" value="halal">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ isHalal}})</span>
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
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('0.0', unrated_t)" value="0.0-star">
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
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('1.0', tp_one)" value="1.0-star">
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
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('1.5', tp_one_h)" value="1.5-star">
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
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('2.0', tp_two)" value="2.0-star">
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
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('2.5', tp_two_h)" value="2.5-star">
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
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('3.0', tp_three)" value="3.0-star">
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
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('3.5', tp_three_h)" value="3.5-star">
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
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('4.0', tp_four)" value="4.0-star">
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
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('4.5', tp_four_h)" value="4.5-star">
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
                                    <input type="checkbox" class="tp-ratings" ng-click="filterByTPRatings('5.0', tp_five)" value="5.0-star">
                                    <span class="checkmark"></span>
                                    <span class="value_numbers">(@{{ tp_five}})</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="popularity_filters_items" ng-if="h_amenities.length">
                            <h4>Hotel Amenities</h4>
                            <div class="check_filter_cont">
                                <div class="h-amn-list">
                                    <label class="container_airline" ng-repeat="h_amn in h_amenities track by $index">  
                                        <span class="airline_name">
                                            <span class="air_name_set">@{{h_amn}}</span>
                                        </span>                    
                                        <input type="checkbox" class="hotel-amns" ng-click="filterByHAmenities(h_amn)" value="hamn-@{{h_amn}}">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="check_filter_cont text-right">
                                <span class="airline_name">
                                    <span class="air_name_set">
                                        <a href="javascript:void(0);" ng-click="toggleHAmenities()">@{{toggleHAmenitiesFlag ? 'View Less' : 'View More'}}</a>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="popularity_filters_items" ng-if="r_amenities.length">
                            <h4>Room Amenities</h4>
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
                                        <a href="javascript:void(0);" ng-click="toggleRAmenities()">@{{toggleRAmenitiesFlag ? 'View Less' : 'View More'}}</a>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <!-- <div class="popularity_filters_items">
                           <h4>User Rating</h4>
                           <div class="check_filter_cont">
                              <label class="container_airline">  
                              <span class="airline_name">
                              <span class="air_name_set">4.5 & above (Excellent)</span>
                              </span>                    
                              <input type="checkbox">
                              <span class="checkmark"></span>
                              <span class="value_numbers">(26)</span>
                              </label>
                              <label class="container_airline">  
                              <span class="airline_name">
                              <span class="air_name_set">4 & above (Very Good)</span>
                              </span>                    
                              <input type="checkbox">
                              <span class="checkmark"></span>
                              <span class="value_numbers">(102)</span>
                              </label>
                              <label class="container_airline">  
                              <span class="airline_name">
                              <span class="air_name_set">3 & above (Good)</span>
                              </span>                    
                              <input type="checkbox">
                              <span class="checkmark"></span>
                              <span class="value_numbers">(355)</span>
                              </label>
                           </div>
                        </div> -->
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="listing_items_data" ng-if="hotels.length" infinite-scroll='loadMoreHotels()' infinite-scroll-disabled='busy' infinite-scroll-distance='1'>
                        <div class="listing_item hotel-item" data-htype="@{{ (hotel.static_data.isHalal=='yes')?'halal':''}}" data-rating="@{{ hotel.TBO_data.h_rating}}-star" data-price="@{{ hotel.TBO_data.Price.OfferedPriceRoundedOff + (iniscomm / 100 * hotel.TBO_data.Price.OfferedPriceRoundedOff)}}" data-code="@{{ hotel.TBO_data.HotelCode}}" ng-repeat="hotel in hotels" ng-init="h_key = $index" ng-click="viewHotel($event, hotel, referral)" data-tprating="@{{ hotel.static_data.tp_ratings}}" data-location="@{{ (hotel.static_data && hotel.static_data.hotel_address) ? hotel.static_data.hotel_address.CityName : searchData['Location'] }}">

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="listing_description_data">
                                        <div class="listing_slider_thumbnail">
                                            <div id="custCarouse@{{h_key}}" class="carousel slide" data-ride="carousel" align="center">
                                                <div class="carousel-inner" ng-if="hotel.static_data && hotel.static_data.hotel_images.length">

                                                    <div ng-repeat="image in hotel.static_data.hotel_images| limitTo:5" ng-init="hm_key = $index" class="hotel-pic carousel-item" ng-class="hm_key == 0 ? 'active' : ''"> 
                                                        <img ng-src="@{{ image || 'https://via.placeholder.com/250X150?text=Image%20Not%20Available' }}" onerror="this.onerror=null;this.src='https://via.placeholder.com/250X150?text=Image%20Not%20Available';" alt="slide"> 
                                                    </div>
                                                </div>

                                                <div class="carousel-inner" ng-if="!hotel.static_data.hotel_images.length">
                                                    <div class="carousel-item active "> 
                                                        <img ng-src="@{{ hotel.TBO_data.HotelPicture || 'https://via.placeholder.com/250X150?text=Image%20Not%20Available' }}" alt="slide" onerror="this.onerror=null;this.src='https://via.placeholder.com/250X150?text=Image%20Not%20Available';"> 
                                                    </div>
                                                </div>
                                                <!-- Thumbnails -->

                                                <ol class="carousel-indicators list-inline" ng-if="hotel.static_data && hotel.static_data.hotel_images.length">
                                                    <li class="list-inline-item "  ng-repeat="image in hotel.static_data.hotel_images| limitTo:5" ng-init="ht_key = $index" ng-class="ht_key == 0 ? 'active' : ''"> 
                                                        <a id="carousel-selector-@{{ ht_key}}" class="selected" data-slide-to="@{{ ht_key}}" data-target="#custCarouse@{{ h_key}}">
                                                            <img ng-src="@{{ image || 'https://via.placeholder.com/50X50?text=Image%20Not%20Available' }}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                        </a>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                        <div class="listing_main_desp" ng-cloak>
                                            <h2 ng-if="hotel.static_data && hotel.static_data.id && hotel.static_data.hotel_name != ''">
                                                @{{ hotel.static_data.hotel_name}}

                                                <span ng-if="hotel.static_data.hotel_address && hotel.static_data.hotel_address.AddressLine && hotel.static_data.hotel_address.AddressLine.length" class="country_name">@{{ hotel.static_data.hotel_address.AddressLine[0]}} @{{ (hotel.static_data.hotel_address.CityName) ? ', ' + hotel.static_data.hotel_address.CityName : ''}} </span>

                                         <!-- <span ng-if="hotel.static_data.hotel_address && hotel.static_data.hotel_address.AddressLine && !hotel.static_data.hotel_address.AddressLine.length" class="country_name">@{{ hotel.static_data.hotel_address.CountryName.$ }}, @{{ hotel.static_data.hotel_address.PostalCode }}</span> -->

                                                <!-- <div class="star-rating" ng-if="hotel.static_data.h_rating == null">
                                                   <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 1 ? 'checked' : ''"></span>
                                                   <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 2 ? 'checked' : ''"></span>
                                                   <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 3 ? 'checked' : ''"></span>
                                                   <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 4 ? 'checked' : ''"></span>
                                                   <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 5 ? 'checked' : ''"></span>
                                                </div> -->

                                            </h2>

                                            <!-- <h2 ng-if="!hotel.static_data.id"> @{{ hotel.TBO_data.HotelName }} -->
                                            <span class="country_name" ng-if="!hotel.static_data.id">@{{ hotel.TBO_data.HotelAddress}}</span>

                                            <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 1 ? 'checked' : ''"></span>
                                            <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 2 ? 'checked' : ''"></span>
                                            <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 3 ? 'checked' : ''"></span>
                                            <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 4 ? 'checked' : ''"></span>
                                            <span class="fa fa-star" ng-class="hotel.TBO_data.h_rating >= 5 ? 'checked' : ''"></span>

                                            <!-- </h2> -->


                                            <!-- <p class="distance_data" ng-if="hotel.static_data && hotel.static_data.hotel_address && hotel.static_data.hotel_address.AddressLine && hotel.static_data.hotel_address.AddressLine.length"> 
                                                @{{ hotel.static_data.hotel_address.AddressLine[0]}}
                                            </p> -->

                                            <p class="distance_data" ng-if="hotel.static_data && hotel.static_data.hotel_address && hotel.static_data.hotel_address.AddressLine && !hotel.static_data.hotel_address.AddressLine.length">
                                                @{{ hotel.static_data.hotel_address.CityName}} , @{{ hotel.static_data.hotel_address.PostalCode}}
                                            </p>

                                            <div class="listing_tags_data" ng-if="hotel.static_data && hotel.static_data.hotel_facilities && hotel.static_data.hotel_facilities.length">
                                                <a href="javascript:void(0);" class="listing_kids_option" ng-repeat="facility in hotel.static_data.hotel_facilities| limitTo:5">
                                                    @{{ facility}}
                                                </a>
                                            </div>


                                            <!-- <div class="assured_list" ng-if="hotel.static_data && hotel.static_data.hotel_contact && hotel.static_data.hotel_contact.ContactNumber && hotel.static_data.hotel_contact.ContactNumber.length">
                                                <a href="tel:@{{ hotel.static_data.hotel_contact.ContactNumber[0]['@PhoneNumber']}}" class="country_assured"> <span class="fa fa-phone"></span> @{{ hotel.static_data.hotel_contact.ContactNumber[0]['@PhoneNumber']}}</a>
                                            </div> -->

                                            <!-- <div class="crtified_options"><a href="javascript:void();">Hotel Themes</a></div>
                                            <div class="safety_features">
                                                <ul class="list-inline" ng-if="hotel.static_data && hotel.static_data.hotel_type && hotel.static_data && hotel.static_data.hotel_type.length">
                                                    <li ng-repeat="type in hotel.static_data.hotel_type" ng-if="$index < 4">
                                                        <i class="fa fa-check" aria-hidden="true"></i> @{{ type['@ThemeName']}}
                                                    </li>
                                                </ul>
                                            </div> -->
                                            <!-- <div class="more_options" ng-if="hotel.static_data && hotel.static_data.hotel_time">
                                                <p>More Options: 
                                                    <a href="javascript:void(0);" ng-if="hotel.static_data && hotel.static_data.hotel_time && hotel.static_data.hotel_time['@CheckInTime']">
                                                        Checkin Time @{{ hotel.static_data.hotel_time['@CheckInTime']}}
                                                    </a>
                                                    |
                                                    <a href="javascript:void(0);" ng-if="hotel.static_data && hotel.static_data.hotel_time && hotel.static_data.hotel_time['@CheckOutTime']">
                                                        Checkout Time @{{ hotel.static_data.hotel_time['@CheckOutTime']}}
                                                    </a>
                                                </p>
                                            </div> -->
                                            <div class="more_options" ng-if="hotel.static_data && hotel.static_data.distance">
                                                <p>Distance: 
                                                    <a href="javascript:void(0);" ng-if="hotel.static_data && hotel.static_data.distance">
                                                        @{{ hotel.static_data.distance}} Km
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="listing_rice_info">
                                        <div class="listing_rice_info">

<!-- <p class="save_price_discount" ng-if="(hotel.TBO_data['Price']['PublishedPriceRoundedOff'] - hotel.TBO_data['Price']['OfferedPriceRoundedOff']) > 0">Save 
   @{{ hotel.TBO_data.Price.CurrencyCode }} 
   @{{ ( hotel.TBO_data.Price.PublishedPriceRoundedOff + ( iniscomm / 100 * hotel.TBO_data.Price.PublishedPriceRoundedOff ) ) - (hotel.TBO_data.Price.OfferedPriceRoundedOff + ( iniscomm / 100 * hotel.TBO_data.Price.OfferedPriceRoundedOff ) ) | number : 2 }}
   <span class="badge">
      @{{ ( ( ( ( (hotel.TBO_data.Price.PublishedPriceRoundedOff + ( iniscomm / 100 * hotel.TBO_data.Price.PublishedPriceRoundedOff ) )  ) - ( (hotel.TBO_data.Price.OfferedPriceRoundedOff + ( iniscomm / 100 * hotel.TBO_data.Price.OfferedPriceRoundedOff ) ) ) ) / (hotel.TBO_data.Price.PublishedPriceRoundedOff + ( iniscomm / 100 * hotel.TBO_data.Price.PublishedPriceRoundedOff ) ) ) * 100) | number : 2}}%</span>
</p> -->

                                   <!-- <p class="cutof_price">
                                    @{{ hotel.TBO_data.Price.PublishedPriceRoundedOff + ( iniscomm / 100 * hotel.TBO_data.Price.PublishedPriceRoundedOff ) | currency:hotel.TBO_data.Price.CurrencyCode }}
                                    </p> -->
                                            <div class="actual_price" style="text-align:center;">
                                                <div ng-if="hotel.TBO_data.FinalPrice > {{ Session::get('lotteryLimit') }}" style="position:absolute;left:20px;top:10px;">
                                                  <img  src="/images/lottery-icon.gif" style="width:30px;" />
                                                  <span style="font-size:12px;">Eligible for Lottery</span>
                                                </div>
                                                <h2>
                                                    @{{ hotel.TBO_data.Price.CurrencyCode }} @{{ hotel.TBO_data.FinalPrice | number : 2 }}
                                                </h2>
                                               <!-- <p>Per night<span>Total @{{ (hotel.TBO_data.Price.RoomPrice * searchData['NoOfNights']) | currency:hotel.TBO_data.Price.CurrencyCode  }} + Taxes</span></p> -->
                                            </div>
                                            <!-- <div class="no_cost_emi">
                                               <p>No Cost <span class="emi_data">EMI</span></p>
                                               <p>Starts at <span class="emi_price">
                                                @{{ (hotel.TBO_data.Price.RoomPrice * searchData['NoOfNights']) | currency:hotel.TBO_data.Price.CurrencyCode }}</span>
                                                </p>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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

                    <!-- <div class="listing_items_data no-data" ng-if="!hotels.length && !busy && noResults" >
                       <h3> No hotels found for {{ $input['countryName'] }} from {{ $input['departdate'] }} to {{ $input['returndate'] }} </h3>
                    </div> -->

                    <div class="listing_items_data no-data" ng-if="noResults && !hotels.length" >
                        <h3> {{ __('labels.no_result_found')}} </h3>
                    </div>

                    <div class="listing_items_data no-data" ng-if="noMoreLoad" >
                        <h3> {{ __('labels.no_more_hotel')}} </h3>
                    </div>



                </div>            
            </div>
        </div>
    </section>
</div>
<div id="sessionWarningModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header refresh-header">
                <h3 class="refresh-header text-center">Session Expired</h3>
            </div>
            <div class="modal-body">
                <p>Hotel prices change frequently due to availability and demand. We want to make sure you always see the most up-to-date price. Please refresh your search to see the latest price.</p>        
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-primary refresh-btn">Refresh Search</a>
            </div>
        </div>
    </div>
</div>
<style>
    .pac-container:after {
        /* Disclaimer: not needed to show 'powered by Google' if also a Google Map is shown */

        background-image: none !important;
        height: 0px;
    }
    .pac-container .pac-item{
        word-wrap: break-word;
    }
</style>
@endsection