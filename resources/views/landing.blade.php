@extends('layouts.app-header')
@section('content')   
<section class="banner_data home_banner_section">
    <video playsinline="playsinline" id="homeVideo" autoplay="autoplay" muted="muted" loop="loop"  loop autoplay>
        <source id="mp4_src" src="" type="video/mp4">
        <source id="webm_src" src="" type="video/webm">
        <source id="ogg_src" src="" type="video/ogv">
    </video>
    <div class="overlaybg"></div>
    <div class="container">
        <div class="row">
            <input type="hidden" id="isHome" value="1">
            <input type="hidden" id="isHomeFH" value="1">
            <input type="hidden" id="showTab" value="{{ Session::get('active_tab') }}">
            <div class="col-md-12">
                <div class="form_tab_data" id="flights">
                    <div class="tab-content">
                        <div id="oneway" class="tab-pane active">

                            <div class="slider_test_heading" style="margin-top: 60px;">
                                <h1 style="font-size:50px;font-weight: 800;">TRIPHEIST</h1>
                                <h2 style="font-size:40px;font-weight: 600;">{{ __('labels.hotel_landing_heading') }}</h2>
                                <p style="font-size:25px;">{{ __('labels.hotel_landing_subheading') }}</p>
                            </div>

                            <input type="hidden" id="tripType" value="2">
                            <form action="{{ route('search_flights') }}" id="searchFlightsForm"  method="GET">
                                @csrf
                                <input type="hidden" name="JourneyType" id="JourneyType" value="1">
                                <div class="round_triping_data">
                                    <!--                                    <h4>{{ __('labels.flight_greeting') }}</h4>-->
                                    <div class="rounding_form_info">
                                        <div class="row" style="border: 3px solid #333;border-radius: 5px;">
                                            <div class="col-lg-2 col-6" style="padding:0px;border: 5px solid #1e4355;background: #fff;">
                                                <div class="form-group">
                                                    <!--                                                    <label>{{ __('labels.from') }}</label>-->
                                                    <select name="origin" class="depart-from" id="f-origin">
                                                        <option value=""></option>
                                                    </select>
                                                    <input type="hidden" name="from" id="from-city" value="">

                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-6" style="padding:0px;border: 5px solid #1e4355;background: #fff;">
                                                <div class="form-group">
                                                    <!--                                                    <label>{{ __('labels.to') }}</label>-->
                                                    <select name="destination" class="depart-to" required> 
                                                        <!-- <option value="BOM">Mumbai</option> -->
                                                    </select>

                                                    <input type="hidden" name="to" id="to-city" value="">
                                                    <!--<div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>-->
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-6"  style="padding:0px;border: 5px solid #1e4355;background: #fff;">
                                                <div class="form-group" style="padding-top:5px;">
                                                    <div class="small_station_info ">{{ __('labels.departure') }}</div>
                                                    <!--<label>{{ __('labels.departure') }} <i class="fa fa-angle-down" aria-hidden="true"></i></label>-->
                                                    <div class="input-group" >
                                                        <input class="form-control departdate" type="text" name="departDate" required readonly value="{{ date('d-m-Y') }}"/>
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-6" style="padding:0px;border: 5px solid #1e4355;background: #fff;">
                                                <div class="form-group not-allowed"  style="padding-top:5px;" id="not-allowed">
                                                    <div class="small_station_info ">{{ __('labels.arrival') }}</div>
                                                    <!--<label>{{ __('labels.return') }} <i class="fa fa-angle-down" aria-hidden="true"></i></label>-->
                                                    <div class="input-group" >
                                                        <input class="form-control returndate" type="text" name="returnDate" required readonly value="{{ date('d-m-Y') }}"/>
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2" style="padding:0px;border: 5px solid #1e4355;background: #fff;">
                                                <div class="form-group"  style="padding-top:5px;">
                                                    <!--<label>{{ __('labels.travel_class') }}</label>-->
                                                    @if(Session::get('locale') == 'heb')
                                                    <span style="position:absolute;margin-left: 20px;color:#000;">1</span>
                                                    <input type="text" name="travellersClass" id="travellersClassOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 {{ __('labels.traveller') }}" value="{{ __('labels.traveller') }}" style="margin-left: -6px;">
                                                    @else
                                                    <input type="text" name="travellersClass" id="travellersClassOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 {{ __('labels.traveller') }}" value="1 {{ __('labels.traveller') }}">
                                                    @endif
                                                    <div class="travellers gbTravellers travellersClassOne">
                                                        <div class="appendBottom20">
                                                            <p data-cy="adultRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.adults') }}</p>
                                                            <ul class="guestCounter font12 darkText gbCounter adCF">
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
                                                                <div class="makeFlex column childCounter">
                                                                    <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.children') }}</p>
                                                                    <ul class="guestCounter font12 darkText gbCounter clCF">
                                                                        <li data-cy="0" class="selected">0</li>
                                                                        <li data-cy="1" class="">1</li>
                                                                        <li data-cy="2" class="">2</li>
                                                                        <li data-cy="3" class="">3</li>
                                                                        <li data-cy="4" class="">4</li>
                                                                        <li data-cy="5" class="">5</li>
                                                                        <li data-cy="6" class="">6</li>
                                                                    </ul>
                                                                </div>
                                                                <div class="makeFlex column pushRight infantCounter">
                                                                    <p data-cy="infantRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.infants') }}</p>
                                                                    <ul class="guestCounter font12 darkText gbCounter inCF">
                                                                        <li data-cy="0" class="selected">0</li>
                                                                        <li data-cy="1" class="">1</li>
                                                                        <li data-cy="2" class="">2</li>
                                                                        <li data-cy="3" class="">3</li>
                                                                        <li data-cy="4" class="">4</li>
                                                                        <li data-cy="5" class="">5</li>
                                                                        <li data-cy="6" class="">6</li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <p data-cy="chooseTravelClass" class="latoBold font12 grayText appendBottom10">{{ __('labels.choose_travel_class') }}</p>
                                                            <ul class="guestCounter classSelect font12 darkText tcF">
                                                                <li data-cy="0" class="selected">{{ __('labels.all') }}</li>
                                                                <li data-cy="1" class="">{{ __('labels.economy') }}</li>
                                                                <li data-cy="2" class="">{{ __('labels.premium_economy') }}</li>
                                                                <li data-cy="3" class="">{{ __('labels.business') }}</li>
                                                                <li data-cy="4" class="">{{ __('labels.premium_business') }}</li>
                                                                <li data-cy="5" class="">{{ __('labels.first_class') }}</li>
                                                            </ul>
                                                            <div class="makeFlex appendBottom25">
                                                                <div class="makeFlex column childCounter">
                                                                    <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.direct_flight') }}</p>
                                                                    <ul class="guestCounter font12 darkText gbCounter df">
                                                                        <li data-cy="false" class="selected">{{ __('labels.no') }}</li>
                                                                        <li data-cy="true" class="">{{ __('labels.yes') }}</li>
                                                                    </ul>
                                                                </div>
                                                                <div class="makeFlex column pushRight infantCounter">
                                                                    <p data-cy="infantRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.one_stop_flight') }}</p>
                                                                    <ul class="guestCounter font12 darkText gbCounter osf">
                                                                        <li data-cy="false" class="selected">{{ __('labels.no') }}</li>
                                                                        <li data-cy="true" class="">{{ __('labels.yes') }}</li>
                                                                    </ul> 
                                                                </div>
                                                                <p></p>
                                                                <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">{{ __('labels.apply') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="small_station_info class_info blk-font">{{ __('labels.all_cabin_class') }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2" style="padding:0px;border: 5px solid #1e4355;background: #fff;">
                                                <div class="search_btns" style="width:100%;margin:0 auto !important;">
                                                    <button style="padding:10px !important;" type="submit" class="btn btn-primary">{{ __('labels.search') }} <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rounding_form_info">
                                        <div class="row">
                                            <div class="col text-center"  style="padding:0px;">
                                                <div class="trending_searches">
                                                    <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                                                    <input type="hidden" name="adultsF" class="adultsF" value="1">
                                                    <input type="hidden" name="childsF" class="childsF" value="0">
                                                    <input type="hidden" name="infants" class="infantsF" value="0">
                                                    <input type="hidden" name="FlightCabinClass" class="FlightCabinClass" value="1">
                                                    <input type="hidden" name="DirectFlight" class="DirectFlight" value="false">
                                                    <input type="hidden" name="OneStopFlight" class="OneStopFlight" value="false">
                                                    <input type="hidden" name="results" class="results" value="true">
                                                    <!--<label>{{ __('labels.trending_search') }}:</label>-->
                                                    <ul class="list-inline">
                                                        <li class="trendingOne"><a href="javascript:void(0);">{{ __('labels.delhi') }} <img src="{{ asset('images/black_right_arrow.png')}}" alt="icon">{{ __('labels.dubai') }}</a></li>
                                                        <li class="trendingTwo"><a href="javascript:void(0);">{{ __('labels.london') }} <img src="{{ asset('images/black_right_arrow.png')}}" alt="icon">{{ __('labels.paris') }}</a></li>
                                                        <li class="trendingThree"><a href="javascript:void(0);">{{ __('labels.tel_aviv') }} <img src="{{ asset('images/black_right_arrow.png')}}" alt="icon">{{ __('labels.delhi') }}</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Form First trending  -->

                <form method="GET" name="searchFormTr1" id="searchTrend1Form" action="{{ route('search_flights') }}"  >
                    @csrf
                    <input type="hidden" name="origin" value="DEL" />
                    <input type="hidden" name="from" value="Delhi" />
                    <input type="hidden" name="to" value="Dubai" />
                    <input type="hidden" name="destination" value="DXB" />
                    <input type="hidden" name="departDate"  value="{{ date('d-m-Y', strtotime('+1 days')) }}" />
                    <input type="hidden" name="returnDate"  value="{{ date('d-m-Y', strtotime('+2 days')) }}" />
                    <input type="hidden" name="travellersClass" value="1 Traveller" />
                    <input type="hidden" name="JourneyType" value="1" />
                    <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                    <input type="hidden" name="adultsF" class="adultsF" value="1">
                    <input type="hidden" name="childsF" class="childsF" value="0">
                    <input type="hidden" name="infants" class="infantsF" value="0">
                    <input type="hidden" name="FlightCabinClass" class="FlightCabinClass" value="1">
                    <input type="hidden" name="DirectFlight" class="DirectFlight" value="false">
                    <input type="hidden" name="OneStopFlight" class="OneStopFlight" value="false">
                    <input type="hidden" name="results" class="results" value="true">
                </form>
                <!-- Trending Ends -->

                <!-- Form First trending 2 -->

                <form method="GET" name="searchFormTr2" id="searchTrend2Form" action="{{ route('search_flights') }}"  >
                    @csrf
                    <input type="hidden" name="origin" value="LON" />
                    <input type="hidden" name="from" value="London" />
                    <input type="hidden" name="to" value="PAR" />
                    <input type="hidden" name="destination" value="Paris" />
                    <input type="hidden" name="departDate"  value="{{ date('d-m-Y', strtotime('+1 days')) }}" />
                    <input type="hidden" name="returnDate"  value="{{ date('d-m-Y', strtotime('+2 days')) }}" />
                    <input type="hidden" name="travellersClass" value="1 Traveller" />
                    <input type="hidden" name="JourneyType" value="1" />
                    <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                    <input type="hidden" name="adultsF" class="adultsF" value="1">
                    <input type="hidden" name="childsF" class="childsF" value="0">
                    <input type="hidden" name="infants" class="infantsF" value="0">
                    <input type="hidden" name="FlightCabinClass" class="FlightCabinClass" value="1">
                    <input type="hidden" name="DirectFlight" class="DirectFlight" value="false">
                    <input type="hidden" name="OneStopFlight" class="OneStopFlight" value="false">
                    <input type="hidden" name="results" class="results" value="true">
                </form>
                <!-- Trending Ends -->

                <!-- Form First trending 3 -->

                <form method="GET" name="searchFormTr3" id="searchTrend3Form" action="{{ route('search_flights') }}"  >
                    @csrf
                    <input type="hidden" name="origin" value="TLV" />
                    <input type="hidden" name="from" value="Tel Aviv" />
                    <input type="hidden" name="to" value="DEL" />
                    <input type="hidden" name="destination" value="Delhi" />
                    <input type="hidden" name="departDate"  value="{{ date('d-m-Y', strtotime('+1 days')) }}" />
                    <input type="hidden" name="returnDate"  value="{{ date('d-m-Y', strtotime('+2 days')) }}" />
                    <input type="hidden" name="travellersClass" value="1 Traveller" />
                    <input type="hidden" name="JourneyType" value="1" />
                    <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                    <input type="hidden" name="adultsF" class="adultsF" value="1">
                    <input type="hidden" name="childsF" class="childsF" value="0">
                    <input type="hidden" name="infants" class="infantsF" value="0">
                    <input type="hidden" name="FlightCabinClass" class="FlightCabinClass" value="1">
                    <input type="hidden" name="DirectFlight" class="DirectFlight" value="false">
                    <input type="hidden" name="OneStopFlight" class="OneStopFlight" value="false">
                    <input type="hidden" name="results" class="results" value="true">
                </form>
                <!-- Trending Ends -->

                <div class="row" id="roundButtonsTab">
                    <div class="col-3 nav-item menu-link @if(Session::get('active_tab') == 'hotels') active @endif" data-tab="hotels">
                        <a  class="nav-link text-dark"  href="javascript:void(0);" >
                            <img style="width:35px;" src="{{ asset('images/hotel_icon.png')}}" alt="Hotel">
                        </a>
                        <span >{{ __('labels.hotels') }}</span>
                    </div>

                    <div  class="col-3 nav-item menu-link @if(Session::get('active_tab') == 'flights-hotels') active @endif" data-tab="flights-hotels">
                        <a style="padding-top:15px;" class="nav-link text-dark"   href="javascript:void(0);" >
                            <img src="{{ asset('images/package-icon.png')}}" alt="Flight+Hotel">
                        </a>
                        <span>{{ __('labels.flights') }}<br>{{ __('labels.hotels') }}</span>
                    </div>

                    <div class="col-3 nav-item menu-link @if(Session::get('active_tab') == 'activities') active @endif" data-tab="activities">
                        <a class="nav-link text-dark"   href="javascript:void(0);" >
                            <img src="{{ asset('images/activity-icon.png')}}" alt="Activity">
                        </a>
                        <span>{{ __('labels.activities') }}</span>
                    </div>

                    <div class="col-3 nav-item menu-link @if(Session::get('active_tab') == 'cabs') active @endif" data-tab="cabs">
                        <a class="nav-link text-dark"  href="javascript:void(0);" >
                            <img src="{{ asset('images/cab-icon.png')}}" alt="Cab">
                        </a>
                        <span>{{ __('labels.cabs') }}</span>
                    </div>
                </div>

                <div class="form_tab_data " id="hotels" >
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="slider_test_heading" style="margin-top: 60px;">
                                <h1 style="font-size:50px;font-weight: 800;">TRIPHEIST</h1>
                                <h2 style="font-size:40px;font-weight: 600;">{{ __('labels.hotel_landing_heading') }}</h2>
                                <p style="font-size:25px;">{{ __('labels.hotel_landing_subheading') }}</p>
                            </div>
                            <div class="round_triping_data hotels">
                                <div class="rounding_form_info">
                                    <div class="tab-content" style="width:100%;">
                                        <div class="tab-pane active" id="international-hotels">
                                            <form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_rooms') }}"  >
                                                <div class="row" style="border: 3px solid #333;border-radius: 5px;">
                                                    <!-- <div class="col-lg-3" style="padding:0px;border: 5px solid #1e4355;background: #fff;">
                                                        <div class="form-group" style="margin:0px !important;padding:0px;width:auto;">
                                                            <input  id="autocomplete" name="Location"  placeholder="{{ __('labels.search_placeholder') }}"  onFocus="geolocate()" type="text" style="color:#333;border:0px solid #ccc;border-radius: 5px;padding:12px;font-size:15px;width:100%;" />
                                                            <select name="city_name_select" class="auto-complete hotel-city-us hide-me" id="hotel-city-us" style="color:#333;border:0px solid #ccc;border-radius: 5px;padding:12px;font-size:15px;width:100%;"></select>
                                                            
                                                        </div>
                                                    </div> -->
                                                    <div class="col-lg-5" style="background: #fff;border: 5px solid #1e4355;padding-top:3px;">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <div class="small_station_info departDay1 text-center" >
                                                                        <span class="total-nights" >
                                                                            @if(Session::get('locale') == 'heb')
                                                                                {{ __('labels.night') }}<span style="position:absolute;">1</span>
                                                                            @else
                                                                                1 {{ __('labels.night') }}
                                                                            @endif
                                                                        </span>
                                                                        <div class="daterangepicker"> <i class="fa fa-hotel" style="color:#1e4355;"></i></div>
                                                                    </div>
                                                                    <input type="hidden" name="Latitude" id="Latitude" value="">
                                                                    <input type="hidden" name="Longitude" id="Longitude" value="">
                                                                    <input type="hidden" name="Radius" id="Radius" value="20">
                                                                    <input type="hidden" name="city_id" id="city_id" value="{{$static_data['city_id']}}">
                                                                    <input type="hidden" name="city_name" id="city_name" value="{{$countryDetails['CityName']}}">
                                                                    <input type="hidden" name="countryCode" id="country_code" value="{{$countryDetails['CountryCode']}}">
                                                                    <input type="hidden" name="countryName" id="country_name" value="{{$countryDetails['Country']}}">
                                                                    <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="{{$static_data['hotel_code']}}">
                                                                    <input type="hidden" name="ishalal"  value="0">

                                                                    <input type="hidden" id="nights_lbl" value="{{ __('labels.nights') }}">
                                                                    <input type="hidden" id="rooms_lbl" value="{{ __('labels.rooms') }}">
                                                                    <input type="hidden" id="night_lbl" value="{{ __('labels.night') }}">
                                                                    <input type="hidden" id="room_lbl" value="{{ __('labels.room') }}">
                                                                    <input type="hidden" id="local_sel" value="{{Session::get('locale')}}">
                                                                    <input type="hidden" id="adults_lbl" value="{{ __('labels.adults') }}">
                                                                    <input type="hidden" id="childrens_lbl" value="{{ __('labels.childrens') }}">
                                                                    <div class="input-group text-center">
                                                                        <input type="text" id="dateRange" value="{{ date('d-m-Y') }} - {{ date('d-m-Y', strtotime('+1 days')) }}" class="form-control  text-center" />
                                                                        <input id="departHotel" type="hidden" name="departdate" value="{{ date('d-m-Y') }}" />
                                                                        <input id="returnHotel" type="hidden" name="returndate" value="{{ date('d-m-Y', strtotime('+1 days')) }}" />
                                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                                    </div>
                                                                </div>   
                                                            </div>
                                                        </div>
                                                    </div>   

                                                    <div class="col-lg-4 text-center" style="background: #fff;border: 5px solid #1e4355;padding-top:3px;">
                                                        <div class="form-group" style="margin:0px !important;">
                                                            @if(Session::get('locale') == 'heb')
                                                            <div class="small_station_info" id="guestRooms" >{{ __('labels.rooms') }} <span style="position:absolute;">1</span></div>
                                                            @else
                                                            <div class="small_station_info" id="guestRooms" >1 {{ __('labels.rooms') }}</div>
                                                            @endif
                                                            <div class="input-group text-center">
                                                                @if(Session::get('locale') == 'heb')
                                                                <input type="text" name="roomsGuests" id="roomsGuests" readonly class="form-control text-center" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="?????? ?????? 2 ????????">
                                                                @else
                                                                <input type="text" name="roomsGuests" id="roomsGuests" readonly class="form-control text-center" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ __('labels.room_guests') }}">
                                                                @endif
                                                                @include('_partials.hotel-guests')
                                                                <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3" style="padding:0px;background: #fff;border: 5px solid #1e4355;">
                                                        <div class="search_btns">
                                                            <button type="submit" style="padding:6px !important;" class="btn btn-primary">{{ __('labels.search') }} <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form_tab_data" id="flights-hotels">
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="slider_test_heading" style="margin-top: 60px;">
                                <h1 style="font-size:50px;font-weight: 800;">TRIPHEIST</h1>
                                <h2 style="font-size:40px;font-weight: 600;">{{ __('labels.hotel_flight_heading') }}</h2>
                                <p style="font-size:25px;">{{ __('labels.hotel_landing_subheading') }}</p>
                            </div>
                            <div class="round_triping_data hotels">
                                <form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_flight_rooms') }}"  >
                                    @csrf
                                    <div class="rounding_form_info">

                                        <div class="row" style="border-radius: 5px;background:#fff;border:6px solid #1e4355 !important;">
                                            <div class="col-lg-4" style="padding:0px;border:3px solid #1e4355;">
                                                <div class="row" style="margin:0px;">
                                                    <div class="col" style="padding:0px;">
                                                        <div class="form-group flightDepartCombox" style="padding:2px;margin:0px !important;">
                                                            <select name="origin" class="depart-from form-control" id="f-origin-fh">
                                                                <option value=""></option>
                                                            </select>
                                                            <input type="hidden" name="from" id="from-city-fh" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col" style="padding:0px;">
                                                        <div class="form-group flightArrivalCombox" style="padding:2px;margin:0px !important;">
                                                            <select name="destination" class="depart-to-FH  form-control" required> 
                                                            </select>
                                                            <input type="hidden" name="to" id="to-city-fh" value="">
                                                            <div class="small_station_info selected-hotel-city"></div>
                                                            <input type="hidden" name="ishalal"  value="0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3" style="padding:0px;border:3px solid #333;">
                                                <div class="form-group" style="padding:0px;margin:0px !important;">

                                                    <input id="autocompleteFH" name="Location"  placeholder="{{ __('labels.search_placeholder') }}"  onFocus="geolocateFH()" type="text" style="height:45px;color:#333;border:0px solid #ccc;border-radius: 0px;padding:8px 4px !important;font-size:13px;width:100%;"  required/>

                                                    <input type="hidden" name="Latitude" id="LatitudeFH" value="">
                                                    <input type="hidden" name="Longitude" id="LongitudeFH" value="">
                                                    <input type="hidden" name="Radius" id="RadiusFH" value="20">
                                                    <input type="hidden" name="city_id" id="city_id" value="130443">
                                                    <input type="hidden" name="city_name" id="city_nameFH" value="Delhi">
                                                    <input type="hidden" name="countryCode" id="country_codeFH" value="IN">
                                                    <input type="hidden" name="countryName" id="country_nameFH" value="India">
                                                    <input type="hidden" name="preffered_hotel" id="preffered_hotelFH" value="">
                                                    <div class="small_station_info selected-hotel-city"></div>
                                                    <input type="hidden" name="ishalal"  value="0">
                                                </div>


                                            </div>
                                            <div class="col-lg-3" style="padding:0px;border:3px solid #333;">
                                                <div class="row" style="margin:0px;padding-top:3px;">
                                                    <div class="col-12 text-center" style="padding:0px;">
                                                        <div class="small_station_info ">
                                                            <span class="total-nights" >
                                                            @if(Session::get('locale') == 'heb')
                                                                 {{ __('labels.night') }}<span style="position:absolute;">1</span>
                                                            @else
                                                                1 {{ __('labels.night') }}
                                                            @endif
                                                            </span>
                                                            <div class="daterangepicker"> <i class="fa fa-hotel" style="color:#1e4355;"></i></div>
                                                        </div>
                                                        <div class="form-group" style="padding:3px;margin:0px !important;">
                                                            <div class="input-group">
                                                                <input type="text" id="dateRangeFH" value="{{ date('d-m-Y') }} - {{ date('d-m-Y', strtotime('+1 days')) }}" class="form-control  text-center" />
                                                                <input id="departHotelFH" type="hidden" name="departdate"  value="{{ date('d-m-Y') }}" />
                                                                <input id="returnHotelFH" type="hidden" name="returndate"   value="{{ date('d-m-Y', strtotime('+1 days')) }}"/>
                                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 text-center" style="padding:0px;border:3px solid #333;padding-top:1px;">
                                                <div class="row">
                                                    <div class="col-12 text-center">
                                                        <div class="form-group" style="padding:3px;margin:0px !important;">
                                                            @if(Session::get('locale') == 'heb')
                                                            <div class="small_station_info" id="guestRoomsFH" >{{ __('labels.rooms') }} <span style="position:absolute;">1</span></div>
                                                            @else
                                                            <div class="small_station_info" id="guestRoomsFH" >1 {{ __('labels.rooms') }}</div>
                                                            @endif
                                                            @if(Session::get('locale') == 'heb')
                                                            <input type="text" name="roomsGuests" id="roomsGuestsFH" style="padding-top:0px;" readonly class="form-control  text-center" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="?????? ?????? 2 ????????">
                                                            @else
                                                            <input type="text" name="roomsGuests" id="roomsGuestsFH" style="padding-top:0px;" readonly class="form-control  text-center" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ __('labels.room_guests') }}">
                                                            @endif
                                                            @include('_partials.flight-hotel-guests')
                                                            <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:40px;">
                                        <div class="col-lg-4 offset-lg-4">
                                            <div class="search_btns" >
                                                <button type="submit" style="border:6px solid #1e4355 !important;padding:5px !important;color:#fff !important;" class="btn btn-primary flightHotelSearch">
                                                    {{ __('labels.search') }} <img src="{{ asset('images/search_arrow.png')}}" alt="search">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="trending_searches">
                                    </div>
                                    <span class="error_no_of_passenger" style="display:none;color:red;float: right;">{{ __('labels.passenger_validation') }}</span>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="form_tab_data" id="activities">
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="slider_test_heading" style="margin-top: 60px;">
                                <h1 style="font-size:50px;font-weight: 800;">TRIPHEIST</h1>
                                <h2 style="font-size:40px;font-weight: 600;">{{ __('labels.hotel_activity_heading') }}</h2>
                                <p style="font-size:25px;">{{ __('labels.hotel_landing_subheading') }}</p>
                            </div>
                            <div class="round_triping_data">
                                <form method="GET" name="searchForm" id="searchActivityForm" action="{{ route('search_activities') }}"  >
                                    @csrf
                                    <div class="rounding_form_info">
                                        <div class="row" style="border-radius: 5px;background:#1e4355 !important;border:3px solid #1e4355 !important;">
                                            <div class="col-lg-3 text-center" style="padding:0px;">
                                                <div class="form-group" style="padding:5px !important;  border:5px solid #1e4355 !important;">
                                                    <div class="small_station_info">{{ __('labels.start_date') }}</div>
                                                    <div class="input-group">
                                                        <input id="departHotel" class="form-control departdateAct" type="text" name="travelstartdate" required readonly value="{{ date('d-m-Y') }}"/>
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 text-center" style="padding:0px;">
                                                <div class="form-group" style="padding:3px !important;  border:5px solid #1e4355 !important;">
                                                    <select name="city_name" class="auto-complete act-city"  required>
                                                    </select>
                                                    <input type="hidden" name="city_act_id" id="city_act_id" value="126632">
                                                    <input type="hidden" name="currency_code_act" id="currency_code_act" value="GB">
                                                    <div class="small_station_info selected-hotel-city"></div>
                                                </div> 
                                            </div>
                                            <div class="col-lg-3 text-center" style="padding:0px;">
                                                <div class="form-group" style="padding:3px !important;  border:5px solid #1e4355 !important;">
                                                    <div class="small_station_info">{{ __('labels.traveller') }}</div>
                                                    @if(Session::get('locale') == 'heb')
                                                    <span style="position:absolute;margin-left: 20px;">1</span>
                                                    <input type="text" name="travellersClass" id="travellersClassactOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 {{ __('labels.traveller') }}" value="{{ __('labels.traveller') }}" style="margin-left: -6px;">
                                                    @else
                                                    <input type="text" name="travellersClass" id="travellersClassactOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 {{ __('labels.traveller') }}" value="1 {{ __('labels.traveller') }}">
                                                    @endif
                                                    <div class="travellers gbTravellers travellersClassactOne">
                                                        <div class="appendBottom20">
                                                            <p data-cy="adultRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.adults') }}</p>
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
                                                                    <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.children') }}</p>
                                                                    <ul id="childCount1" class="childCountCab guestCounter font12 darkText clCCA">
                                                                        <li data-cy="0" class="selected">0</li>
                                                                        <li data-cy="1" class="">1</li>
                                                                        <li data-cy="2" class="">2</li>
                                                                        <li data-cy="3" class="">3</li>
                                                                        <li data-cy="4" class="">4</li>
                                                                    </ul>
                                                                    <ul class="childAgeList appendBottom10">
                                                                        <li class="childAgeSelector " id="childAgeSelector1Cab1">
                                                                            <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child1age') }}</span>
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
                                                                            <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child2age') }}</span>
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
                                                                            <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child3age') }}</span>
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
                                                                            <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child4age') }}</span>
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
                                                                <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">{{ __('labels.apply') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3  text-center" style="padding:0px;">
                                                <div class="search_btns" style="padding:0px !important;  border:5px solid #1e4355 !important;">
                                                    <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                                                    <input type="hidden" name="adultsCCA" class="adultsCCA" value="1">
                                                    <input type="hidden" name="childsCCA" class="childsCCA" value="0">
                                                    <button type="submit"  class="btn btn-primary">{{ __('labels.search') }} <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                   
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form_tab_data" id="cabs">
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="slider_test_heading" style="margin-top: 60px;">
                                <h1 style="font-size:50px;font-weight: 800;">TRIPHEIST</h1>
                                <h2 style="font-size:40px;font-weight: 600;">{{ __('labels.hotel_cab_heading') }}</h2>
                                <p style="font-size:25px;">{{ __('labels.hotel_landing_subheading') }}</p>
                            </div>
                            <div class="round_triping_data">
                                <!--                                <h4>{{ __('labels.cab_greeting') }}</h4>-->
                                <form method="GET" name="searchForm" id="searchCabsForm" action="{{ route('search_cabs') }}"  >
                                    @csrf
                                    <div class="rounding_form_info">
                                        <div class="row" style="border-radius: 6px;border:3px solid #1e4355 !important;">
                                            <div class="col-lg-4 text-center" style="background:#fff;padding:0px;border:6px solid #1e4355 !important;">
                                                <div class="form-group comboBox" style="border-radius: 0px;padding:0px 5px;">
                                                    <!--                                                    <div class="small_station_info">{{ __('labels.cityarea') }}</div>-->
                                                    <select name="city_name" class="auto-complete cab-city" required>
                                                    </select>
                                                    <input type="hidden" name="city_cab_id" id="city_cab_id" value="115936">
                                                    <input type="hidden" name="currency_code" id="currency_code" value="GB">
                                                    <input type="hidden" name="country_code_value" id="country_code_value" value="IN">
                                                    <input type="hidden" name="pick_up_point_name" id="pick_up_point_name" value="">
                                                    <input type="hidden" name="drop_off_point_name" id="drop_off_point_name" value="">
                                                    <div class="small_station_info selected-hotel-city"></div>
<!--                                                    <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>-->
                                                </div>
                                            </div>
                                            <div class="col-lg-4" style="padding:0px;background:#fff;border:6px solid #1e4355 !important;">
                                                <div class="row" style="padding:0px;margin:0px;">
                                                    <div class="col text-center" style="padding:0px;">
                                                        <div class="form-group comboBox"  style="border-radius: 0px;padding:0px 5px;">
                                                            <!--                                                            <div class="small_station_info">{{ __('labels.pickup') }}</div>-->
                                                            <select name="pick_up" id="pick_up_type" class="select_pickup" required>
                                                                <option value="">{{ __('labels.select_one_org') }}</option>
                                                                <option value="0">{{ __('labels.accommodation') }}</option>
                                                                <option value="1">{{ __('labels.airport') }}</option>
                                                                <option value="2">{{ __('labels.train_station') }}</option>
                                                                <option value="3">{{ __('labels.sea_port') }}</option>
                                                                <!-- <option value="4">Other</option> -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col text-center" style="padding:0px;">
                                                        <div class="form-group comboBox"  style="border-radius: 0px;padding:0px 5px;">
                                                            <!--                                                            <div class="small_station_info">{{ __('labels.drop_off') }}</div>-->
                                                            <select name="drop_off" id="drop_off_type" class="select_dropoff" required>
                                                                <option value="">{{ __('labels.select_one_dest') }}</option>
                                                                <option value="0">{{ __('labels.accommodation') }}</option>
                                                                <option value="1">{{ __('labels.airport') }}</option>
                                                                <option value="2">{{ __('labels.train_station') }}</option>
                                                                <option value="3">{{ __('labels.sea_port') }}</option>
                                                                <!-- <option value="4">Other</option> -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4" style="padding:0px;background:#fff;border:6px solid #1e4355 !important;">
                                                <div class="row" style="padding:0px;margin:0px;">
                                                    <div class="col-6 text-center" style="padding:0px;">
                                                        <div class="form-group comboBox non_acc_city"  style="border-radius: 0px;padding:0px 5px;">
                                                            <!--                                                            <div class="small_station_info">{{ __('labels.pickup_point') }}</div>-->
                                                            <select name="pick_up_point" id="pick_up_point" class="pickup-city">
                                                                <option value=''>{{ __('labels.select_location') }}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group accom_city" style="border-radius: 0px;display:none;">
                                                            <!--                                                            <div class="small_station_info">{{ __('labels.drop_off') }}</div>-->
                                                            <select name="pick_up_point_acc" class="auto-complete pick_up_point_auto">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 text-center" style="padding:0px;">
                                                        <div class="form-group comboBox non_acc_city_drop" style="border-radius: 0px;padding:0px 5px;">
                                                            <!--                                                            <div class="small_station_info">{{ __('labels.drop_off_point') }}</div>-->
                                                            <select name="drop_off_point" id="drop_off_point" class="dropoff-city">
                                                                <option value=''>{{ __('labels.select_location') }}</option>
                                                            </select>
                                                        </div>
                                                        <!-- For Accom -->
                                                        <div class="form-group accom_city_drop comboBox" style="display:none;">
                                                            <!--                                                    <div class="small_station_info">{{ __('labels.drop_off_point') }}</div>-->
                                                            <select name="drop_off_point_acc" class="auto-complete drop_off_point_auto">
                                                            </select>
                                                        </div>
                                                        <!-- Ends Here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rounding_form_info">
                                        <div class="row" style="margin-top:6px;border-radius: 6px;background:#fff;border:4px solid #1e4355 !important;">
                                            <div class="col text-center" style="padding:0px;border:6px solid #1e4355 !important;">
                                                <div class="form-group" style="background:#fff;border-radius: 0px;padding:0px 5px;">
                                                    <!--                                                    <div class="small_station_info">{{ __('labels.travel_date') }}</div>-->
                                                    <div class="input-group">
                                                        <input id="departHotel" class="form-control departdateCab text-center " type="text" name="transferdate" required readonly value="{{ date('d-m-Y') }}"/>
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col text-center" style="background:#fff;padding:0px;border:6px solid #1e4355 !important;">
                                                <div class="form-group comboBox" style="border-radius: 0px;padding:0px 5px;border-radius: 0px;">
                                                    <!--                                                    <div class="small_station_info">{{ __('labels.travel_time') }}</div>-->
                                                    <select name="time">
                                                        <option value="0000">12.00 AM</option>
                                                        <option value="0030">12.30 AM</option>
                                                        <option value="0100">01.00 AM</option>
                                                        <option value="0130">01.30 AM</option>
                                                        <option value="0200">02.00 AM</option>
                                                        <option value="0230">02.30 AM</option>
                                                        <option value="0300">03.00 AM</option>
                                                        <option value="0330">03.30 AM</option>
                                                        <option value="0400">04.00 AM</option>
                                                        <option value="0430">04.30 AM</option>
                                                        <option value="0500">05.00 AM</option>
                                                        <option value="0530">05.30 AM</option>
                                                        <option value="0600">06.00 AM</option>
                                                        <option value="0630">06.30 AM</option>
                                                        <option value="0700">07.00 AM</option>
                                                        <option value="0730">07.30 AM</option>
                                                        <option value="0800">08.00 AM</option>
                                                        <option value="0830">08.30 AM</option>
                                                        <option value="0900">09.00 AM</option>
                                                        <option value="0930">09.30 AM</option>
                                                        <option value="1000">10.00 AM</option>
                                                        <option value="1030">10.30 AM</option>
                                                        <option value="1100">11.00 AM</option>
                                                        <option value="1130">11.30 AM</option>
                                                        <option value="1200">12.00 PM</option>
                                                        <option value="1230">12.30 PM</option>
                                                        <option value="1300">01.00 PM</option>
                                                        <option value="1330">01.30 PM</option>
                                                        <option value="1400">02.00 PM</option>
                                                        <option value="1430">02.30 PM</option>
                                                        <option value="1500">03.00 PM</option>
                                                        <option value="1530">03.30 PM</option>
                                                        <option value="1600">04.00 PM</option>
                                                        <option value="1630">04.30 PM</option>
                                                        <option value="1700">05.00 PM</option>
                                                        <option value="1730">05.30 PM</option>
                                                        <option value="1800">06.00 PM</option>
                                                        <option value="1830">06.30 PM</option>
                                                        <option value="1900">07.00 PM</option>
                                                        <option value="1930">07.30 PM</option>
                                                        <option value="2000">08.00 PM</option>
                                                        <option value="2030">08.30 PM</option>
                                                        <option value="2100">09.00 PM</option>
                                                        <option value="2130">09.30 PM</option>
                                                        <option value="2200">10.00 PM</option>
                                                        <option value="2230">10.30 PM</option>
                                                        <option value="2300">11.00 PM</option>
                                                        <option value="2330">11.30 PM</option>
                                                    </select>
                                                </div>                          
                                            </div>
                                            <div class="col text-center" style="background:#fff;padding:0px;border:6px solid #1e4355 !important;">
                                                <div class="form-group comboBox" style="border-radius: 0px;padding:0px 5px;">
                                                    <!--                                                    <div class="small_station_info">{{ __('labels.pref_lang') }}</div>-->
                                                    <select name="preffered_language" class="select_preffered_lang" required>
                                                        <option value="">{{ __('labels.not_specified') }}</option>
                                                        <option value="1">{{ __('labels.Arabic') }}</option>
                                                        <option value="2">{{ __('labels.Cantinese') }}</option>
                                                        <option value="3">{{ __('labels.Danish') }}</option>
                                                        <option value="4">{{ __('labels.English') }}</option>
                                                        <option value="5">{{ __('labels.French') }}</option>
                                                        <option value="6">{{ __('labels.German') }}</option>
                                                        <option value="7">{{ __('labels.Hebrew') }}</option>
                                                        <option value="8">{{ __('labels.Italian') }}</option>
                                                        <option value="9">{{ __('labels.Japanese') }}</option>
                                                        <option value="10">{{ __('labels.Korean') }}</option>
                                                        <option value="11">{{ __('labels.Mandrain') }}</option>
                                                        <option value="12">{{ __('labels.Portuguese') }}</option>
                                                        <option value="13">{{ __('labels.Russian') }}</option>
                                                        <option value="14">{{ __('labels.Spanish') }}</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col text-center" style="background:#fff;padding:0px;border:6px solid #1e4355 !important;">
                                                <div class="form-group" style="border-radius: 0px;padding:0px 5px;">
                                                    <!--                                                    <div class="small_station_info">Traveller</div>-->
                                                    @if(Session::get('locale') == 'heb')
                                                     <span class="cabGuestCss">1</span>
                                                     <input type="text" name="travellersClass" id="travellersClassCabOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 {{ __('labels.traveller') }}" value="{{ __('labels.traveller') }}" style="margin-left: -6px;">
                                                    @else
                                                     <input type="text" name="travellersClass" id="travellersClassCabOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 {{ __('labels.traveller') }}" value="1 {{ __('labels.traveller') }}">
                                                    @endif

                                                    <!-- <input type="text" name="travellersClass" id="travellersClassCabOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 {{ __('labels.traveller') }}" value="1 {{ __('labels.traveller') }}"> -->
                                                    <div class="travellers gbTravellers travellersClassCabOne">
                                                        <div class="appendBottom20">
                                                            <p data-cy="adultRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.adults') }}</p>
                                                            <ul class="guestCounter font12 darkText gbCounter adCC">
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
                                                                    <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.children') }}</p>
                                                                    <ul id="childCount1" class="childCountCabAct guestCounter font12 darkText clCC">
                                                                        <li data-cy="0" class="selected">0</li>
                                                                        <li data-cy="1" class="">1</li>
                                                                        <li data-cy="2" class="">2</li>
                                                                        <li data-cy="3" class="">3</li>
                                                                        <li data-cy="4" class="">4</li>
                                                                    </ul>
                                                                    <ul class="childAgeList appendBottom10">
                                                                        <li class="childAgeSelector " id="childAgeSelector1CabAct1">
                                                                            <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child1age') }}</span>
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
                                                                        <li class="childAgeSelector " id="childAgeSelector2CabAct1">
                                                                            <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child2age') }}</span>
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
                                                                        <li class="childAgeSelector " id="childAgeSelector3CabAct1">
                                                                            <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child3age') }}</span>
                                                                            <label class="lblAge" for="0">
                                                                                <select id="child3AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge3">
                                                                                    <option data-cy="childAgeValue-Select" value="">Select</option>
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
                                                                        <li class="childAgeSelector " id="childAgeSelector4CabAct1">
                                                                            <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child4age') }}</span>
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
                                                                <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">{{ __('labels.apply') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col text-center" style="background:#fff;padding:0px;border:6px solid #1e4355 !important;">
                                                <div class="form-group comboBox" style="border-radius: 0px;padding:0px 5px;">
                                                    <!--                                                    <div class="small_station_info">{{ __('labels.pref_currency') }}</div>-->
                                                    <select name="preffered_currency" class="select_preffered_currency" required>
                                                        <option value="USD" selected="selected">United States Dollars</option>
                                                        <option value="EUR">Euro</option>
                                                        <option value="GBP">United Kingdom Pounds</option>
                                                        <option value="DZD">Algeria Dinars</option>
                                                        <option value="ARP">Argentina Pesos</option>
                                                        <option value="AUD">Australia Dollars</option>
                                                        <option value="ATS">Austria Schillings</option>
                                                        <option value="BSD">Bahamas Dollars</option>
                                                        <option value="BBD">Barbados Dollars</option>
                                                        <option value="BEF">Belgium Francs</option>
                                                        <option value="BMD">Bermuda Dollars</option>
                                                        <option value="BRR">Brazil Real</option>
                                                        <option value="BGL">Bulgaria Lev</option>
                                                        <option value="CAD">Canada Dollars</option>
                                                        <option value="CLP">Chile Pesos</option>
                                                        <option value="CNY">China Yuan Renmimbi</option>
                                                        <option value="CYP">Cyprus Pounds</option>
                                                        <option value="CSK">Czech Republic Koruna</option>
                                                        <option value="DKK">Denmark Kroner</option>
                                                        <option value="NLG">Dutch Guilders</option>
                                                        <option value="XCD">Eastern Caribbean Dollars</option>
                                                        <option value="EGP">Egypt Pounds</option>
                                                        <option value="FJD">Fiji Dollars</option>
                                                        <option value="FIM">Finland Markka</option>
                                                        <option value="FRF">France Francs</option>
                                                        <option value="DEM">Germany Deutsche Marks</option>
                                                        <option value="XAU">Gold Ounces</option>
                                                        <option value="GRD">Greece Drachmas</option>
                                                        <option value="HKD">Hong Kong Dollars</option>
                                                        <option value="HUF">Hungary Forint</option>
                                                        <option value="ISK">Iceland Krona</option>
                                                        <option value="INR" >India Rupees</option>
                                                        <option value="IDR">Indonesia Rupiah</option>
                                                        <option value="IEP">Ireland Punt</option>
                                                        <option value="ILS">Israel New Shekels</option>
                                                        <option value="ITL">Italy Lira</option>
                                                        <option value="JMD">Jamaica Dollars</option>
                                                        <option value="JPY">Japan Yen</option>
                                                        <option value="JOD">Jordan Dinar</option>
                                                        <option value="KRW">Korea (South) Won</option>
                                                        <option value="LBP">Lebanon Pounds</option>
                                                        <option value="LUF">Luxembourg Francs</option>
                                                        <option value="MYR">Malaysia Ringgit</option>
                                                        <option value="MXP">Mexico Pesos</option>
                                                        <option value="NLG">Netherlands Guilders</option>
                                                        <option value="NZD">New Zealand Dollars</option>
                                                        <option value="NOK">Norway Kroner</option>
                                                        <option value="PKR">Pakistan Rupees</option>
                                                        <option value="XPD">Palladium Ounces</option>
                                                        <option value="PHP">Philippines Pesos</option>
                                                        <option value="XPT">Platinum Ounces</option>
                                                        <option value="PLZ">Poland Zloty</option>
                                                        <option value="PTE">Portugal Escudo</option>
                                                        <option value="ROL">Romania Leu</option>
                                                        <option value="RUR">Russia Rubles</option>
                                                        <option value="SAR">Saudi Arabia Riyal</option>
                                                        <option value="XAG">Silver Ounces</option>
                                                        <option value="SGD">Singapore Dollars</option>
                                                        <option value="SKK">Slovakia Koruna</option>
                                                        <option value="ZAR">South Africa Rand</option>
                                                        <option value="KRW">South Korea Won</option>
                                                        <option value="ESP">Spain Pesetas</option>
                                                        <option value="XDR">Special Drawing Right (IMF)</option>
                                                        <option value="SDD">Sudan Dinar</option>
                                                        <option value="SEK">Sweden Krona</option>
                                                        <option value="CHF">Switzerland Francs</option>
                                                        <option value="TWD">Taiwan Dollars</option>
                                                        <option value="THB">Thailand Baht</option>
                                                        <option value="TTD">Trinidad and Tobago Dollars</option>
                                                        <option value="TRL">Turkey Lira</option>
                                                        <option value="VEB">Venezuela Bolivar</option>
                                                        <option value="ZMK">Zambia Kwacha</option>
                                                        <option value="XCD">Eastern Caribbean Dollars</option>
                                                        <option value="XDR">Special Drawing Right (IMF)</option>
                                                        <option value="XAG">Silver Ounces</option>
                                                        <option value="XAU">Gold Ounces</option>
                                                        <option value="XPD">Palladium Ounces</option>
                                                        <option value="XPT">Platinum Ounces</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="offset-lg-4 col-lg-4 text-center" style="padding-top:50px;">
                                            <div class="search_btns">
                                                <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                                                <input type="hidden" name="adultsFC" class="adultsCC" value="1">
                                                <input type="hidden" name="childsFC" class="childsCC" value="0">
                                                <input type="hidden" name="alternate_language" class="" value="4">
                                                <input type="hidden" name="country" class="country_val" value="IN">
                                                <button type="submit" style="border:6px solid #1e4355 !important;" class="btn btn-primary">{{ __('labels.search') }} <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="trending_searches"> </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php //echo "<pre>"; print_r($cities);?>
<section class="top_locations">
    <div class="container">
        <h1 class="text-center section_title">{{ $static_data['hotel_name'] }}</h1>
    <div class="row">
        <section class="explore_data">
          <div class="container">
            <div class="row justify-content-center attr">
              <div class="col-lg-6 col-md-12">
                <div class="about_dubai_info">
                  
                  @if(isset($static_data) && isset($static_data['hotel_name']))
                  <h2 class="hotel-name">{{$static_data['hotel_name']}}</h2>
                  <p class="hotel-ratings">
                    Ratings&nbsp;<span class="fa fa-star @if($static_data['start_rating'] >= 1) checked @endif"></span>
                      <span class="fa fa-star @if($static_data['start_rating'] >= 2) checked @endif"></span>
                      <span class="fa fa-star @if($static_data['start_rating'] >= 3) checked @endif"></span>
                      <span class="fa fa-star @if($static_data['start_rating'] >= 4) checked @endif"></span>
                      <span class="fa fa-star @if($static_data['start_rating'] >= 5) checked @endif"></span>
                  </p>
                  @if(isset($static_data) && isset($static_data['hotel_type']))
                  <div class="safety_features">
                     <ul class="list-inline" >
                      @foreach($static_data['hotel_type'] as $key => $hotel_type)
                        <li >
                         <i class="fa fa-check" aria-hidden="true"></i> {{ isset($hotel_type['@ThemeName']) ? $hotel_type['@ThemeName'] : '' }}
                        </li>
                      @endforeach
                     </ul>
                  </div>
                  @endif
                  @else
                  <h2>{{$hotelDetails['name']}}</h2>
                  <h4>{{$hotelDetails['tag_line']}}</h4>
                  <p>{!!html_entity_decode($hotelDetails['description'])!!}</p>
                  @endif
               
                </div>
              </div>
              <div class="col-lg-6 col-md-12">
                <div class="about_info_img home">
                    <div id="custCarouse1" class="carousel slide" data-ride="carousel" align="center">
                    @if(isset($static_data) && isset($static_data['hotel_images']))
                     <ol class="carousel-indicators">
                       @foreach($static_data['hotel_images'] as $key => $hotelImg)
                          <li data-target="#custCarouse1"  data-slide-to="{{ $key }}" class="@if($key == 0) active @endif"></li>
                       @endforeach
                     </ol>
                    <div class="carousel-inner" >
                       @foreach($static_data['hotel_images'] as $key => $hotelImg)
                       <div class="carousel-item @if($key == 0) active @endif"> 
                          <img src="{{env('AWS_BUCKET_URL')}}/{{ $hotelImg }}" class="view-hotel-image" alt="hotel">
                       </div>
                       @endforeach
                    </div>
                    @else 
                     <img class="home-page img-fluid" src="/uploads/hotel/{{$hotelDetails['main_image']}}" alt="info data">
                    @endif
                 </div>
                </div>
              </div>
            </div>
          </div>
        </section>  
    </div>

    <div class="row">
        <section class="explore_data">
          <div class="container">
            <div class="row justify-content-center attr">
              <div class="col-lg-12 col-md-12">
                <div class="about_dubai_info">
                  
                  @if(isset($static_data) && isset($static_data['hotel_description']) && isset($static_data['hotel_description'][0]))
                    <h4>About - {{$static_data['hotel_name']}}</h4>
                    <p>{!!html_entity_decode($static_data['hotel_description'][0])!!}</p>
                  @else
                    <h4>{{$hotelDetails['tag_line2']}}</h4>
                    <p>{!!html_entity_decode($hotelDetails['description2'])!!}</p>
                  @endif
               
                </div>
              </div>
            </div>
          </div>
        </section>  
    </div>

    <div class="row">
        <section class="explore_data">
          <div class="container">
            <div class="row justify-content-center attr">
              <div class="col-lg-12 col-md-12">
                <div class="about_dubai_info">
                  
                  @if(isset($static_data) && isset($static_data['attractions']) && isset($static_data['attractions'][0]))
                    <h4>Attraction - {{$static_data['hotel_name']}}</h4>
                    <p>{!!html_entity_decode($static_data['attractions'][0])!!}</p>
                  @else
                    <h4>{{$hotelDetails['tag_line3']}}</h4>
                    <p>{!!html_entity_decode($hotelDetails['description3'])!!}</p>
                  @endif
               
                </div>
              </div>
            </div>
          </div>
        </section> 
    </div>

    @if(isset($static_data) && isset($static_data['hotel_facilities']))
    <div class="row">
        <section class="explore_data">
          <div class="container">
            <div class="row justify-content-center attr">
              <div class="col-lg-12 col-md-12">
                <div class="about_dubai_info">
                  
                  <h2>Ameneties</h2>
                   <div class="row">
                       @foreach($static_data['hotel_facilities'] as $h_fac)
                      <div class="col-md-4">
                         <a href="javascript:void(0);" class="listing_kids_option" >
                            {{ $h_fac }}
                         </a>
                      </div>
                      @endforeach
                   </div>
               
                </div>
              </div>
            </div>
          </div>
        </section> 
    </div>
    @endif 
    </div>
</section>

<style>
    .row.justify-content-center.attr {
        box-shadow: 0px 0px 10px 0px rgb(0 0 0 / 10%);
        padding: 15px;
        border-radius: 5px;
    }
    section.explore_data {
        padding: 20px 0px;
        width: 100%;
    }
    .row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }
    .justify-content-center {
        -ms-flex-pack: center!important;
        justify-content: center!important;
    }
    .pac-container:after {
        /* Disclaimer: not needed to show 'powered by Google' if also a Google Map is shown */

        /*        background-image: none !important;*/
        /*        height: 0px;*/
    }
    .pac-container .pac-item{
        word-wrap: break-word;
    }

    .listing-countries{
        text-align: center;
    }

    .discover-countries-section{
        background: #ddd;
        padding: 50px;
    }
    .discover-countries-section .owl-dots{
        display:block;
        margin-top:40px;
    }

    .discover-countries-section .owl-nav{
        display:none;
    }

    .overlaybg{
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0px;
        left: 50%;
        -moz-transform: translate(-50%, 0%);
        -ms-transform: translate(-50%, 0%);
        -webkit-transform: translate(-50%, 0%);
        transform: translate(-50%, 0%);
        object-fit: cover;
        background:#00000085;
    }

    .hotelCityCombox .select2-selection--single::before{
        content:"\f1ad";
        font:normal normal normal 14px/1 FontAwesome;
        padding-left:5px;
    }

    .hotelCityCombox .select2-container .select2-selection--single .select2-selection__rendered{
        display: inline;
    }


    #returnHotel{
        font-size:15px;
    }

    .form_tab_data .tab-content{
        margin:0 auto;
    }
</style>
<script>
    $(document).ready(function () {
        $(".comboBox select").select2();
        $("#us_city_id").select2();
    });
</script>

@endsection
