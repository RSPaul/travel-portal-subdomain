@extends('layouts.app-header')
@section('content')   
<div ng-app="activitiesApp" ng-controller="searchActivityCtrl" >
    <section class="listing_banner_forms cab_listing_banner_data_forms">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="cab_inner_portion_data">
                        <form method="GET" name="searchForm" id="searchActivityForm" action="{{ route('search_activities')}}"  >
                            @csrf
                            <div class="rounding_form_info" style="border-right:5px solid #b3cedd;border-left:5px solid #b3cedd;">
                                <div class="row">
                                    <div class="col-lg-3" style="padding:0px;background:#fff;border:2px solid #b3cedd;">
                                        <div class="form-group" style="margin:0px !important;padding:10px;">
<!--                                            <label>{{ __('labels.start_date')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>-->
                                            <div class="input-group">
                                                <input id="departHotel" class="form-control departdateAct" type="text" name="travelstartdate" required readonly value="{{ $input['travelstartdate']}}"/>
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4" style="padding:0px;background:#fff;border:2px solid #b3cedd;">
                                        <div class="form-group" style="margin:0px !important;">
<!--                                            <label>{{ __('labels.cityarea')}}</label>-->
                                            <select name="city_name" class="auto-complete act-city" required>
                                                <option value="{{ $input['city_name']}}">{{ $input['city_name']}}</option>
                                            </select>
                                            <input type="hidden" name="city_act_id" id="city_act_id" value="{{$input['city_act_id']}}">
                                            <input type="hidden" name="currency_code_act" id="currency_code_act" value="{{$input['currency_code_act']}}">
                                            <div class="small_station_info selected-hotel-city"></div>
<!--                                            <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>-->
                                        </div>
                                    </div>
                                    <div class="col-lg-3" style="padding:0px;background:#fff;border:2px solid #b3cedd;">
                                        <div class="form-group" style="margin:0px !important;">
<!--                                            <label>{{ __('labels.traveller')}}</label>-->
                                            <input type="text" style="font-size:15px;padding:10px;" name="travellersClass" id="travellersClassactOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="1 Traveller">
                                            <div class="travellers gbTravellers travellersClassactOne">
                                                <div class="appendBottom20">
                                                    <p data-cy="adultRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.adults')}}</p>
                                                    <ul class="guestCounter font12 darkText gbCounter adCCA">
                                                        <li data-cy="1" class="selected">1</li>
                                                        <li data-cy="2" class="">2</li>
                                                        <li data-cy="3" class="">3</li>
                                                        <li data-cy="4" class="">4</li>
                                                        <li data-cy="5" class="">5</li>
                                                        <li data-cy="6" class="">6</li>
                                                        <li data-cy="7" class="">7</li>
                                                        <li data-cy="8" class="">8</li>
                                                        <li data-cy="9" class="">9</li>
                                                    </ul>
                                                    <div class="makeFlex appendBottom25">
                                                        <div class="makeFlex column childCounter col-md-12">
                                                            <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.children')}}</p>
                                                            <ul id="childCount1" class="childCountCab guestCounter font12 darkText clCCA">
                                                                <li data-cy="0" class="selected">0</li>
                                                                <li data-cy="1" class="">1</li>
                                                                <li data-cy="2" class="">2</li>
                                                                <li data-cy="3" class="">3</li>
                                                                <li data-cy="4" class="">4</li>
                                                            </ul>
                                                            <ul class="childAgeList appendBottom10">
                                                                <li class="childAgeSelector " id="childAgeSelector1Cab1">
                                                                    <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child1age')}}</span>
                                                                    <label class="lblAge" for="0">
                                                                        <select id="child1AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge1">
                                                                            <option data-cy="childAgeValue-Select" value="">{{ __('labels.select') }}</option>
                                                                            <option data-cy="childAgeValue-1" value="1">1</option>
                                                                            <option data-cy="childAgeValue-2" value="2">2</option>
                                                                            <option data-cy="childAgeValue-3" value="3">3</option>
                                                                            <option data-cy="childAgeValue-4" value="4">4</option>
                                                                            <option data-cy="childAgeValue-5" value="5">5</option>
                                                                            <option data-cy="childAgeValue-6" value="6">6</option>
                                                                            <option data-cy="childAgeValue-7" value="7">7</option>
                                                                            <option data-cy="childAgeValue-8" value="8">8</option>
                                                                            <option data-cy="childAgeValue-9" value="9">9</option>
                                                                            <option data-cy="childAgeValue-10" value="10">10</option>
                                                                            <option data-cy="childAgeValue-11" value="11">11</option>
                                                                            <option data-cy="childAgeValue-12" value="12">12</option>
                                                                        </select>
                                                                    </label>
                                                                </li>
                                                                <li class="childAgeSelector " id="childAgeSelector2Cab1">
                                                                    <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child2age')}}</span>
                                                                    <label class="lblAge" for="0">
                                                                        <select id="child2AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge2">
                                                                            <option data-cy="childAgeValue-Select" value="">{{ __('labels.select') }}</option>
                                                                            <option data-cy="childAgeValue-1" value="1">1</option>
                                                                            <option data-cy="childAgeValue-2" value="2">2</option>
                                                                            <option data-cy="childAgeValue-3" value="3">3</option>
                                                                            <option data-cy="childAgeValue-4" value="4">4</option>
                                                                            <option data-cy="childAgeValue-5" value="5">5</option>
                                                                            <option data-cy="childAgeValue-6" value="6">6</option>
                                                                            <option data-cy="childAgeValue-7" value="7">7</option>
                                                                            <option data-cy="childAgeValue-8" value="8">8</option>
                                                                            <option data-cy="childAgeValue-9" value="9">9</option>
                                                                            <option data-cy="childAgeValue-10" value="10">10</option>
                                                                            <option data-cy="childAgeValue-11" value="11">11</option>
                                                                            <option data-cy="childAgeValue-12" value="12">12</option>
                                                                        </select>
                                                                    </label>
                                                                </li>
                                                                <li class="childAgeSelector " id="childAgeSelector3Cab1">
                                                                    <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child3age')}}</span>
                                                                    <label class="lblAge" for="0">
                                                                        <select id="child3AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge3">
                                                                            <option data-cy="childAgeValue-Select" value="">{{ __('labels.select') }}</option>
                                                                            <option data-cy="childAgeValue-1" value="1">1</option>
                                                                            <option data-cy="childAgeValue-2" value="2">2</option>
                                                                            <option data-cy="childAgeValue-3" value="3">3</option>
                                                                            <option data-cy="childAgeValue-4" value="4">4</option>
                                                                            <option data-cy="childAgeValue-5" value="5">5</option>
                                                                            <option data-cy="childAgeValue-6" value="6">6</option>
                                                                            <option data-cy="childAgeValue-7" value="7">7</option>
                                                                            <option data-cy="childAgeValue-8" value="8">8</option>
                                                                            <option data-cy="childAgeValue-9" value="9">9</option>
                                                                            <option data-cy="childAgeValue-10" value="10">10</option>
                                                                            <option data-cy="childAgeValue-11" value="11">11</option>
                                                                            <option data-cy="childAgeValue-12" value="12">12</option>
                                                                        </select>
                                                                    </label>
                                                                </li>
                                                                <li class="childAgeSelector " id="childAgeSelector4Cab1">
                                                                    <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child4age')}}</span>
                                                                    <label class="lblAge" for="0">
                                                                        <select id="child4AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge4">
                                                                            <option data-cy="childAgeValue-Select" value="">{{ __('labels.select') }}</option>
                                                                            <option data-cy="childAgeValue-1" value="1">1</option>
                                                                            <option data-cy="childAgeValue-2" value="2">2</option>
                                                                            <option data-cy="childAgeValue-3" value="3">3</option>
                                                                            <option data-cy="childAgeValue-4" value="4">4</option>
                                                                            <option data-cy="childAgeValue-5" value="5">5</option>
                                                                            <option data-cy="childAgeValue-6" value="6">6</option>
                                                                            <option data-cy="childAgeValue-7" value="7">7</option>
                                                                            <option data-cy="childAgeValue-8" value="8">8</option>
                                                                            <option data-cy="childAgeValue-9" value="9">9</option>
                                                                            <option data-cy="childAgeValue-10" value="10">10</option>
                                                                            <option data-cy="childAgeValue-11" value="11">11</option>
                                                                            <option data-cy="childAgeValue-12" value="12">12</option>
                                                                        </select>
                                                                    </label>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="makeFlex appendBottom25">
                                                        <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">{{ __('labels.apply')}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2" style="padding:0px;border:2px solid #b3cedd;">
                                        <div class="search_btns_listingx" style="border-radius: 0px;">
                                            <input type="hidden" name="referral" class="referral" value="{{ $referral}}">
                                            <input type="hidden" name="adultsCCA" class="adultsCCA" value="{{ $input['adultsCCA']}}">
                                            <input type="hidden" name="childsCCA" class="childsCCA" value="{{ $input['childsCCA']}}">
                                            <button type="submit" style="width:100%;border-radius: 0px !important;" class="btn btn-primary">{{ __('labels.search')}} <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
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
    <section class="flight_listing_section data-section">
        <div class="container">
            @if($isAgent)
            <div class="row" ng-if="loaded">
                <div ng-class="selectedActs.length ? 'col-lg-9' : 'col-lg-10'">
                </div>
                <div ng-class="selectedActs.length ? 'col-lg-3 email-hotels' : 'col-lg-2 email-hotels'">
                    <button href="javascript:void(0);" class="btn btn-primary email-hotels" ng-disabled="!selectedActs.length"  data-target="#act-email-modal" data-toggle="modal">{{ __('labels.email_hotels') }} <span ng-if="selectedActs.length">(<span ng-bind="selectedActs.length"></span>)</span></button>
                </div>
            </div>
            @endif
            <div class="row">
                <!-- Not Loaded Section -->
                <div class="col-md-3" ng-if="!loaded">
                    <div class="listing_sidebar cab_sidebar_listings">
                        <h3>{{ __('labels.select_filters')}}</h3>
                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.price')}}</h4>
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
                        <!--  <div class="popularity_filters_items">
                            <h4>Cab Type</h4>
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

                <div class="col-md-3" ng-if="loaded">
                    <div class="listing_sidebar cab_sidebar_listings">
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
                        <h3>{{ __('labels.select_filters')}}</h3>
                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.price')}}</h4>
                            <div class="check_filter_cont">
                                <div class="slidecontainer">
                                    <!-- <label class="filters-label">Price Range</label> -->
                                    <input type="range" min="500" max="50000" value="25000" class="slider" id="pirceRange" ng-change="filterByPrice(priceRange)" ng-model="priceRange">
                                    <br>
                                    <span class="range-price max"> @{{priceRange| currency:activities[0].Price.CurrencyCode }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="popularity_filters_items">
                           <h4>Cab Type</h4>
                           <div class="inner_check_air_data" ng-repeat="prefered in prefferedcabs">
                               <label class="container_airline">  
                               <span class="airline_name">
                               <img src="/images/default_cab.jpg" alt="/images/default_cab.jpg" >
                               <span class="air_name_set">@{{ prefered }}</span>
                               </span>                    
                               <input type="checkbox" class="cab-line-type" value="@{{ prefered }}" checked='checked'>
                               <span class="checkmark"></span>
                               </label>
                            </div> 
                        </div> -->
                    </div>
                </div>

                <!-- loader -->
                <div class="col-md-9" ng-if="!loaded">
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

                <div class="col-md-9" ng-if="loaded">
                    <div class="cab_list cab_large_sec_data container loading" data-price="@{{ act.FinalPrice}}" data-actname="@{{ act.SightseeingName}}" ng-repeat="act in activities">
                        <div class="row">
                            <div class="col-md-10 no-gutter">
                                <div class="listing_description_data">
                                    <div class="row" >
                                        <div class="col-md-5">
                                            <p >
                                               <!-- <i class="fa fa-car"></i> -->
                                                <b><h5 class="activity-name" ng-bind="act.SightseeingName"></h5></b>
                                                <img src="@{{ act.ImageList[0]}}" alt="@{{ act.ImageList[0]}}">

                                                &nbsp;
                                            </p>
                                        </div>
                                        <div class="col-md-7">
                                            <p>
                                                @{{ act.TourSummary | limitTo: 200 }}
                                            </p>
                                        </div>
                                    </div>                        
                                </div>
                            </div>
                            <div class="col-md-2 no-gutter">
                                <div class="cabs_listing_rice_info">
                                    @{{ act.FinalPrice | currency:act.Price.CurrencyCode}}
                                </div>
                                <br><br><br>
                                <div class="refundable_data">
                                    <a href="javascript:void(0);" ng-click="checkAvailability(act, $index)" class="btn btn_activities_details btn-secondary availb-btn-@{{$index}}" data-id="@{{$index}}">{{ __('labels.check_availability')}}</a>
                                </div>
                                @if($isAgent)
                                <div class="email-hotel-check form-check">
                                    <input type="checkbox" class="form-check-input" name="email-hotels" value="@{{act.SightseeingName}}" ng-click="toggleSelection(act)">
                                    <span class="airline_name">
                                        <span class="air_name_set">{{ __('labels.email_label')}}</span>
                                    </span>  
                                </div>
                                @endif
                            </div>
                            <div class="err error-msg-@{{$index}}" style="display: none;">
                                <p ></p>
                            </div>
                        </div>
                        <!-- <div class="row">
                          <div class="col-md-12">
                             
                          </div>
                        </div> -->
                        <div class="flight_information_data" id="view_activity_@{{$index}}" style="display: none;">
                         <!-- <span ng-if="availText">Loading. Please wait......</span> -->
                            <!--  <nav> -->
                            <div class="row">
                                <div class="col-md-4"  ng-repeat="actC in activitiesCheck.TourPlan">
                                     <a class="tourSpecialName">@{{ actC.SpecialItem[0]}}</a>
                                    <a href="/view-activity/@{{search_id}}/@{{ actC.TourIndex}}/@{{ act.ResultIndex}}/@{{ searchData.referral || '0'}}/@{{traceId}}" class="btn btn-primary" target="_blank">{{ __('labels.book')}}</a>
                                </div>
                            </div>
                            <!--  </nav> -->
                        </div>
                    </div>

                    <div class="cab_list cab_large_sec_data container loading" ng-if="!activities.length">
                        <h2 class="error_data" ng-bind="error"></h2>
                    </div>

                </div>
                <!-- Section Ends -->
            </div>
        </div>
        <div id="act-email-modal" class="modal fade" role="dialog"  data-keyboard="false">
            <div class="modal-dialog">
                <form ng-submit="sendSelectedHotels()" name="sendHotelEmail">
                    <div class="modal-content">
                        <div class="modal-header refresh-header">
                            <h3 class="refresh-header text-center">{{ __('labels.send_itineraries')}}</h3>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>{{ __('labels.enter_email')}}<</label>
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
    </section>
    @endsection
    <style type="text/css">
    #sessionExpiryTimer {
        font-size: 14px;
        font-weight: 700;
    }
    .tw-flex.tw-items-center.padd-10 {
        padding: 0 !important;
    }
</style>