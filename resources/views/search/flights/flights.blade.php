@extends('layouts.app-header')
@section('content')   
<input type="hidden" id="domain" value="{{ env('APP_URL') }}">
<div ng-app="flightApp" ng-controller="searchFlightCtrl" >
    <section class="listing_banner_forms">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!--                    <ul class="nav nav-tabs flight-list">
                                            <li class="nav-item trip-type" data-type="1">
                                                <a class="nav-link @if($input['JourneyType'] == '1') active @endif" data-toggle="tab" href="javascript:void(0);">{{ __('labels.one_way')}}</a>
                                            </li>
                                            <li class="nav-item trip-type" data-type="2">
                                                <a class="nav-link @if($input['JourneyType'] == '2') active @endif" data-toggle="tab" href="javascript:void(0);">{{ __('labels.round_trip')}}</a>
                                            </li>
                                        </ul>-->
                    <form action="{{ route('search_flights')}}" id="searchFlightsFormRound"  method="GET">
                        @csrf
                        <div class="listing_inner_forms">
                            
                            <div class="rounding_form_info list_rounding_fts">
                                <div class="row">
                                    <div class="col-lg-2 col-6" style="padding:0px;border: 5px solid  #b3cedd;background: #fff;">
                                        <input type="hidden" id="JourneyType" ng-model="searchData.JourneyType" name="JourneyType" value="{{ $input['JourneyType']}}">
                                        <div class="form-group" style="margin:0px;">
                                            <!--                                            <label>{{ __('labels.from')}}</label>-->
                                            <select name="origin" class="depart-from" required="">
                                                <option value="{{ $input['origin']}}">{{ $input['from']}}</option>
                                            </select>
                                            <input type="hidden" ng-model="searchData.from" name="from" id="from-city" value="{{ $input['from']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-2  col-6" style="padding:0px;border: 5px solid  #b3cedd;background: #fff;">
                                        <div class="form-group" style="margin:0px;">
                                            <!--                                            <label>{{ __('labels.to')}}</label>-->
                                            <select name="destination" class="depart-to" required="">
                                                <option value="{{ $input['destination']}}">{{ $input['to']}}</option>
                                            </select>
                                            <input type="hidden" ng-model="searchData.from" name="to" id="to-city" value="{{ $input['to']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-2  col-6" style="padding:0px;border: 5px solid  #b3cedd;background: #fff;">
                                        <div class="form-group" style="margin:0px;">
                                            <!--<label>{{ __('labels.departure')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>-->
                                            <div class="input-group" style="margin:0px;">
                                                <input class="form-control departdate" ng-model="searchData.departDate" type="text" name="departDate" required readonly value="{{ $input['departDate']}}"/>
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            </div>
                                        </div>      
                                    </div>
                                    <div class="col-lg-2  col-6" style="padding:0px;border: 5px solid  #b3cedd;background: #fff;">
                                        <div class="form-group @if($input['JourneyType'] == '1') not-allowed @endif" id="not-allowed" style="margin:0px;">
                                            <!--<label>{{ __('labels.return')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>-->
                                            <div class="input-group" style="margin:0px;">
                                                <input class="form-control returndate" ng-model="searchData.returnDate" type="text" name="returnDate" required readonly value="{{ ($input['JourneyType'] == '1') ? $input['departDate'] : $input['returnDate'] }}"/>
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 " style="padding:0px;border: 5px solid  #b3cedd;background: #fff;">
                                        <div class="form-group" style="margin:0px;">
                                            <!--<label>{{ __('labels.travel_class')}}</label>-->
                                            <input type="text" name="travellersClass" id="travellersClass" ng-model="searchData.travellersClass" returnDateid="travellersClass" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="{{ $input['travellersClass']}}">
                                            <div class="travellers gbTravellers">
                                                <div class="appendBottom20">
                                                    <p data-cy="adultRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.adults')}}</p>
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
                                                            <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.children')}}</p>
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
                                                            <p data-cy="infantRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.infants')}}</p>
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
                                                    <p data-cy="chooseTravelClass" class="latoBold font12 grayText appendBottom10">{{ __('labels.choose_travel_class')}}</p>
                                                    <ul class="guestCounter classSelect font12 darkText tcF">
                                                        <li data-cy="1" class="selected">{{ __('labels.all')}}</li>
                                                        <li data-cy="2" class="">{{ __('labels.economy')}}</li>
                                                        <li data-cy="3" class="">{{ __('labels.premium_economy')}}</li>
                                                        <li data-cy="4" class="">{{ __('labels.business')}}</li>
                                                        <li data-cy="5" class="">{{ __('labels.premium_business')}}</li>
                                                        <li data-cy="6" class="">{{ __('labels.first_class')}}</li>
                                                    </ul>
                                                    <div class="makeFlex appendBottom25">
                                                        <div class="makeFlex column childCounter">
                                                            <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.direct_flight')}}</p>
                                                            <ul class="guestCounter font12 darkText gbCounter df">
                                                                <li data-cy="false" class="selected">{{ __('labels.no')}}</li>
                                                                <li data-cy="true" class="">{{ __('labels.yes')}}</li>
                                                            </ul>
                                                        </div>
                                                        <div class="makeFlex column pushRight infantCounter">
                                                            <p data-cy="infantRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.one_stop_flight')}}</p>
                                                            <ul class="guestCounter font12 darkText gbCounter osf">
                                                                <li data-cy="false" class="selected">{{ __('labels.no')}}</li>
                                                                <li data-cy="true" class="">{{ __('labels.yes')}}</li>
                                                            </ul> 
                                                        </div>
                                                        <p></p>
                                                        <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">{{ __('labels.apply')}}</button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="small_station_info class_info">
                                                @if($input['FlightCabinClass'] == '1') 
                                                All Cabin Classes
                                                @elseif($input['FlightCabinClass'] == '2')
                                                Economy Class
                                                @elseif($input['FlightCabinClass'] == '3')
                                                PremiumEconomy Class
                                                @elseif($input['FlightCabinClass'] == '4')
                                                Business Class
                                                @elseif($input['FlightCabinClass'] == '5')
                                                PremiumBusiness Class
                                                @elseif($input['FlightCabinClass'] == '6')
                                                First Class
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 ">
                                        <input type="hidden" name="referral" class="referral" value="{{ $referral}}">
                                        <input type="hidden" name="adultsF" class="adultsF"  ng-model="searchData.adultsF" value="{{ $input['adultsF']}}">
                                        <input type="hidden" name="childsF" class="childsF"  ng-model="searchData.childsF" value="{{ $input['childsF']}}">
                                        <input type="hidden" name="infants" class="infantsF"  ng-model="searchData.infantsF" value="{{ $input['infants']}}">
                                        <input type="hidden" name="FlightCabinClass" ng-model="searchData.FlightCabinClass" class="FlightCabinClass" value="{{ $input['FlightCabinClass']}}">
                                        <input type="hidden" name="DirectFlight" ng-model="searchData.DirectFlight" class="DirectFlight" value="{{ $input['DirectFlight']}}">
                                        <input type="hidden" name="OneStopFlight" ng-model="searchData.OneStopFlight" class="OneStopFlight" value="{{ $input['OneStopFlight']}}">
                                        <input type="hidden" name="search_id" id="search_id" value="@{{ search_id }}">
                                        <input type="hidden" name="results" class="results" value="true">
                                        <div class="search_btns_listing" style="padding-top:5px;width:100%;padding-left:0px;">
                                            <button type="submit" style="width:100%;border-radius:0px !important;" class="btn btn-primary">{{ __('labels.search')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="flight_listing_section data-section bootom_flght_data_secs">
        <div class="container">
            @if($isAgent)
            <div class="row" ng-if="loaded">
                <div ng-class="selectedFlights.length ? 'col-lg-9' : 'col-lg-10'">
                </div>
                <div ng-class="selectedFlights.length ? 'col-lg-3 email-hotels' : 'col-lg-2 email-hotels'">
                    <button href="javascript:void(0);" class="btn btn-primary email-hotels" ng-disabled="!selectedFlights.length"  data-target="#flight-email-modal" data-toggle="modal">Email Selected Flights <span ng-if="selectedFlights.length">(<span ng-bind="selectedFlights.length"></span>)</span></button>
                </div>
            </div>
            @endif
            <div class="row">
                <!-- <div class="col-md-4"></div> -->

                <!-- Not Loaded Section -->
                <div class="col-md-3" ng-if="!loaded">
                    <div class="listing_sidebar">
                        <h3>{{ __('labels.select_filters')}}</h3>
                        <div class="popularity_filters_items">
                            <h4>{{ __('labels.departure')}}</h4>
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
                            <h4>{{ __('labels.stops')}}</h4>
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
                            <h4>{{ __('labels.class')}}</h4>
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
                            <h4>{{ __('labels.pref_airlines')}}</h4>
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
                <!-- Section Ends -->

                <div class="col-md-12" ng-if="flights.ResponseStatus && flights.Results[1] && loaded">
                    <div class="row air_list_data flight_air_data_searched" id="priceBoxTop">
                        <div class="col-md-5 twoway d-none d-sm-block">
                            <div class="f-list-new">
                                <div class="row align-items-end">
                                    <div class="col-md-12">
                                        <div class="flex_width_air">
                                            <div class="vistara_Data">
                                                <img src="/images/SpiceJet.gif"  class="airimg_arrive" alt="SpiceJet">
                                                <span class="flightCode_arrive">SpiceJet 
                                                    SG - 8153
                                                </span>
                                            </div>
                                            <div class="main_flts_time">
                                                <div class="fllight_time_date">
                                                    <h5 class="air_code_arrive"> {{$input['origin']}}</h5>
                                                    <div class="time_flts_arrive">(06:35)</div>
                                                </div>
                                                <div class="time_hr_arrive">2h 15m</div>
                                                <div class="fllight_time_date">
                                                    <h5 class="air_code_arriveto"> {{$input['destination']}} </h5>
                                                    <div class="time_flts_arriveto">(08:50)</div>
                                                </div>
                                                <div class="price_flight_datas">
                                                    <div class="price_sef_data">
                                                        <h3 class="currency">INR</h3>
                                                        <h3 class="arrivePrice"> 3,972.77 </h3>
                                                    </div>
                                                    <span class="arriveSeats"> 1 </span> <span>seat(s) left</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 twoway d-none d-sm-block">
                            <div class="f-list-new">
                                <div class="row align-items-end">
                                    <div class="col-md-12">
                                        <div class="flex_width_air">
                                            <div class="vistara_Data">
                                                <img src="/images/SpiceJet.gif"  class="airimg_return" alt="SpiceJet">
                                                <span class="flightCode_return">SpiceJet 
                                                    SG - 8153
                                                </span>
                                            </div>
                                            <div class="main_flts_time">
                                                <div class="fllight_time_date">
                                                    <h5 class="air_code_return"> {{$input['destination']}} </h5>
                                                    <div class="time_flts_return">(06:35)</div>
                                                </div>
                                                <div class="time_hr_return">2h 15m</div>
                                                <div class="fllight_time_date">
                                                    <h5 class="air_code_returnto"> {{$input['origin']}} </h5>
                                                    <div class="time_flts_returnto">(08:50)</div>
                                                </div>
                                                <div class="price_flight_datas">
                                                    <div class="price_sef_data">
                                                        <h3 class="returnCurrency">INR</h3>
                                                        <h3 class="returnPrice"> 3,972.77 </h3>
                                                    </div>
                                                    <span class="returnSeats"> 1 </span> <span>seat(s) left </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 twoway d-none d-sm-block">
                            <div class="price_flight_datas total_price">
                                <h3 class="currency_val"></h3>
                                <h3 class="price_total etc_price">INR 3,972.77 </h3>
                            </div>
                            <input type="hidden" class="obindexval" value="" />
                            <input type="hidden" class="ibindexval" value="" />
                            <input type="hidden" class="traceIdval" value="" />
                            <a href="#" target="_blank"  class="btn btn_book book_return_url  ">{{ __('labels.book')}}</a>
                            <!-- Social Meadia -->
                            <div class="share-post-icons-flight flight-social-obib" id="post_share_icon_@{{ flight.ResultIndex }}">
                                <div class="row"><i class="fa fa-share-alt" ng-click="showSocialMediaIB('ibflighticon')" aria-hidden="true"></i></div>
                                <div class="row mediaicons show-social-icons-ibflighticon" style="display: none;">
                                    <a style="font-size:20px;" href="https://www.facebook.com/sharer/sharer.php?u={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}" class="share-to fb-clr" data-type="fb" target="_blank">
                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                    </a>
                                    <a style="font-size:20px;" href="whatsapp://send?text={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}" class="share-to whatsp-clr" data-type="wp" target="_blank">
                                        <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                    </a>
                                    <a style="font-size:20px;" href="http://twitter.com/share?text={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}" class="share-to tw-clr" data-type="tw" target="_blank">
                                        <i class="fa fa-twitter" aria-hidden="true"></i>
                                    </a>
                                    <a style="font-size:20px;" class="share-to pint-clr" data-type="pt" href="https://pinterest.com/pin/create/bookmarklet/?media=https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][0].Airline.AirlineCode}}&url={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}" target="_blank">
                                        <i class="fa fa-pinterest" aria-hidden="true"></i>
                                    </a>
                                    <a style="font-size:20px;" class="share-to stb-clr" data-type="stb" href="https://www.stumbleupon.com/submit?url={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}&title=@{{ flight.Segments[0][0].Airline.AirlineName}} @{{ flight.Segments[0][0].Airline.AirlineCode}} - @{{ flight.Segments[0][0].Airline.FlightNumber}}" target="_blank">
                                        <i class="fa fa-stumbleupon" aria-hidden="true"></i>
                                    </a>
                                    <a style="font-size:20px;" class="share-to lkn-clr" data-type="lkn" href="https://www.linkedin.com/shareArticle?url={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}&title=@{{ flight.Segments[0][0].Airline.AirlineName}} @{{ flight.Segments[0][0].Airline.AirlineCode}} - @{{ flight.Segments[0][0].Airline.FlightNumber}}" target="_blank">
                                        <i class="fa fa-linkedin" aria-hidden="true"></i>
                                    </a>
                                    <a style="font-size:20px;" class="share-to inst-clr" data-type="inst" href="https://www.instagram.com/?url={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}" target="_blank">
                                        <i class="fa fa-instagram" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- Ends  --> 
                            <!-- <button class="btn btn_search">Book</button> -->
                        </div>
                        <div class="col-md-2 twoway d-block d-sm-none"  style="position:fixed;padding:5px;text-align: center;left:0px;bottom:5px;width:100%;z-index: 5;">
                            <a href="#" target="_blank" style="width:100%;padding:15px 20px !important;background:#FF7235 !important;font-weight:bold;;" class="btn btn-warning btn_book book_return_url ">Book For <span class="currency_val" ></span> <span class="price_total etc_price"></span></a> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" ng-if="loaded">
                <div class="col-md-3 d-none d-sm-block">
                    <div class="filter_data flts_sidebar_data">
                        <div class="filter_heading">
                            <h3>{{ __('labels.filter')}}</h3>
                            <a href="javascript:void(0)" ng-click="resetFilters();" >Reset All</a>
                        </div>
                        <div class="departure_data">
                            <h4>{{ __('labels.departure')}}</h4>
                            <div class="departure_time_info">
                                <label class="current_time_det">
                                    <input type="checkbox" value="morning" ng-model="morning" ng-change="departFilter('morning');" name="morning" id="morning_dept" class="form-control time_oneway">
                                    <span>4am - 11am</span>                    
                                </label>
                                <label class="current_time_det">
                                    <input type="checkbox" value="afternoon" ng-model="afternoon" ng-change="departFilter('afternoon');"   name="afternoon" id="afternoon_dept" class="form-control time_oneway">
                                    <span>11am - 4pm</span>                    
                                </label>
                                <label class="current_time_det">
                                    <input type="checkbox" value="evening" ng-model="evening" ng-change="departFilter('evening');"  name="evening" id="evening_dept" class="form-control time_oneway">
                                    <span>4pm - 9pm</span>                    
                                </label>
                                <label class="current_time_det">
                                    <input type="checkbox" value="night" ng-model="night" ng-change="departFilter('night');"  name="night" id="night_dept" class="form-control time_oneway">
                                    <span>9pm - 4am</span>                    
                                </label>
                            </div>
                        </div>
                        <div class="departure_data" ng-if="flights.ResponseStatus && flights.Results[1]">
                            <h4>{{ __('labels.return')}}</h4>
                            <div class="departure_time_info">
                                <label class="current_time_det">
                                    <input type="checkbox" value="morning" ng-model="morning_return" ng-change="returnFilter('morning');"  name="morning_return" class="form-control time_return">
                                    <span>4am - 11am</span>                    
                                </label>
                                <label class="current_time_det">
                                    <input type="checkbox" value="afternoon" ng-model="afternoon_return" ng-change="returnFilter('afternoon');"  name="afternoon_return" class="form-control time_return">
                                    <span>11am - 4pm</span>                    
                                </label>
                                <label class="current_time_det">
                                    <input type="checkbox" value="evening" ng-model="evening_return" ng-change="returnFilter('evening');"  name="evening_return" class="form-control time_return">
                                    <span>4pm - 9pm</span>                    
                                </label>
                                <label class="current_time_det">
                                    <input type="checkbox" value="night" ng-model="night_return" ng-change="returnFilter('night');"  name="night_return" class="form-control time_return">
                                    <span>9pm - 4am</span>                    
                                </label>
                            </div>
                        </div>
                        <div class="departure_data">
                            <h4>{{ __('labels.stops')}}</h4>
                            <div class="departure_time_info">
                                <label class="current_time_det">
                                    <input type="checkbox" ng-model="Stop0" ng-change="stopFilter('Direct');" value="Direct" class="form-control stop_flight_val">
                                    <span><i class="stop_data">0</i>Stop</span>                    
                                </label>
                                <label class="current_time_det">
                                    <input type="checkbox" ng-model="Stop1" ng-change="stopFilter('Indirect');" value="Indirect" class="form-control stop_flight_val">
                                    <span><i class="stop_data">1</i>Stop</span>                    
                                </label>
                            </div>
                        </div>

                        <div class="departure_data" ng-if="flights.ResponseStatus && flights.Results[1]">
                            <h4>{{ __('labels.stops')}} {{ __('labels.return')}}</h4>
                            <div class="departure_time_info">
                                <label class="current_time_det">
                                    <input type="checkbox" ng-model="Stop0_return" ng-change="returnStopFilter('Direct');" value="Direct" class="form-control stop_flight_val_return">
                                    <span><i class="stop_data">0</i>Stop</span>                    
                                </label>
                                <label class="current_time_det">
                                    <input type="checkbox" ng-model="Stop1_return" ng-change="returnStopFilter('Indirect');" value="Indirect" class="form-control stop_flight_val_return">
                                    <span><i class="stop_data">1</i>Stop</span>                    
                                </label>
                            </div>
                        </div>

                        <div class="departure_data">
                            <div class="filter_heading">
                                <h4>{{ __('labels.pref_airlines')}}</h4>
                            </div>
                            <div class="airline_filters">
                                <div class="inner_check_air_data" ng-repeat="prefered in prefferedflights">
                                    <label class="container_airline">  
                                        <span class="airline_name">
                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ prefered.fcode}}" alt="@{{ prefered.name}}" >
                                            <span class="air_name_set">@{{ prefered.name}}</span>
                                        </span>                    
                                        <input type="checkbox" ng-model="airlines" ng-change="airlineFilter(prefered.name);" class="air-line-type" value="@{{ prefered.name}}" >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $dary = explode("-", $input['departDate']);
                $dndt = implode("-", array_reverse($dary));
                $departDay = date("d M", strtotime($dndt));


                $Aary = explode("-", $input['returnDate']);
                $ardt = implode("-", array_reverse($Aary));
                $returnDay = date("d M", strtotime($ardt));
                ?>
                <div class="col-lg-9">
                    <div class="responsive__tabs">
                        <ul class="scrollable-tabs" >
                            <li ng-if="flights.Results[1]" ng-class="flights.Results[1] ? 'halfcol  nav-item' : 'fullcol  nav-item'"  >
                                <a class="nav-link  active" data-toggle="tab" href="#OneWayFlightTab">
                                    <p class="fltimg"><img src="/images/SpiceJet.gif"  class="airimg_arrive" alt="SpiceJet"> {{$input['origin']}} - {{$input['destination']}} {{$departDay}}</p>
                                    <p class="fdtl"><span class="time_flts_arrive">(06:35)</span>-<span class="time_flts_arriveto">(08:50)</span>-<span class="currency">INR</span><span class="arrivePrice"> 3,972.77 </span></p>
                                </a>

                            </li>
                            <li  class="nav-item halfcol" ng-if="flights.Results[1] && flights.Results[1].length > 0">
                                <a class="nav-link" data-toggle="tab" href="#returnFlightTab">
                                    <p class="fltimg"><img src="/images/SpiceJet.gif"  class="airimg_return" alt="SpiceJet"> {{$input['destination']}} - {{$input['origin']}} {{$returnDay}}</p>
                                    <p class="fdtl"><span class="time_flts_return">(06:35)</span>-<span class="time_flts_returnto">(08:50)</span><span class="returnCurrency">INR</span><span class="returnPrice"> 3,972.77 </span></p>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="OneWayFlightTab">
                                <div class="air_list_data f-list" ng-repeat="flight in flights1" data-price="@{{flight.Fare.FinalPrice}}" data-depart="@{{flight.time}}" data-cabinclass="Cabin@{{ flight.Segments[0][0].CabinClass}}" data-air="@{{flight.Segments[0][0].Airline.AirlineName}}" data-stops="@{{ flight.Segments[0][1].Airline ? 'Stop1' : 'Stop0' }}">
                                    <div class="row">
                                        <div class="col-8 col-lg-10" style="border-right: 1px dashed #ccc;">
                                            <div class="row align-items-end">

                                                <div ng-class="flights.Results[1].length > 0  ? 'col-md-12' : 'col-md-12'">
                                                    <div class="flex_width_air">
                                                        <div class="vistara_Data">
                                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][0].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][0].Airline.AirlineName}}" />
                                                            <span>@{{flight.Segments[0][0].Airline.AirlineName}} 
                                                                @{{flight.Segments[0][0].Airline.AirlineCode}} @{{flight.Segments[0][0].Airline.FlightNumber}}
                                                            </span> <span class="multistop" ng-if="flight.Segments[0][2]">(2 stops)</span><span class="onestop" ng-if="flight.Segments[0][1] && !flight.Segments[0][2]">(1 stop)</span><span class="direct" ng-if="!flight.Segments[0][1] && !flight.Segments[0][2]">(Direct)</span>
                                                        </div>
                                                        <div class="main_flts_time">
                                                            <div class="fllight_time_date">
                                                                <h5>@{{flight.Segments[0][0].Origin.Airport.AirportCode}} <span>@{{flight.Segments[0][0].Origin.Airport.CityName}}, @{{flight.Segments[0][0].Origin.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[0][0].Origin.DepTime|  date:'HH:mm' }})</div>
                                                            </div>
                                                            <!-- <div class="time_hr">@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}</div> -->
                                                            <div class="flight_duration" ng-if="flight.Segments[0][2]">
                                                                <span>@{{ flight.Segments[0][2].AccumulatedDuration | time:'mm':'hhh mmm':false}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>
                                                            <div class="flight_duration" ng-if="flight.Segments[0][1] && !flight.Segments[0][2]">
                                                                <span>@{{ flight.Segments[0][1].AccumulatedDuration | time:'mm':'hhh mmm':false}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>
                                                            <div class="flight_duration" ng-if="!flight.Segments[0][1] && !flight.Segments[0][2]">
                                                                <span>@{{flight.Segments[0][0].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>
                                                            <div class="fllight_time_date" ng-if="flight.Segments[0][2]">
                                                                <h5>@{{flight.Segments[0][2].Destination.Airport.AirportCode}} <span>@{{flight.Segments[0][2].Destination.Airport.CityName}}, @{{flight.Segments[0][2].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{ flight.Segments[0][2].Destination.ArrTime |  date:'HH:mm' }})</div>
                                                            </div>
                                                            <div class="fllight_time_date" ng-if="flight.Segments[0][1] && !flight.Segments[0][2]">
                                                                <h5>@{{flight.Segments[0][1].Destination.Airport.AirportCode}} <span>@{{flight.Segments[0][1].Destination.Airport.CityName}}, @{{flight.Segments[0][1].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{ flight.Segments[0][1].Destination.ArrTime |  date:'HH:mm' }})</div>
                                                            </div>
                                                            <div class="fllight_time_date" ng-if="!flight.Segments[0][1] && !flight.Segments[0][2]">
                                                                <h5>@{{flight.Segments[0][0].Destination.Airport.AirportCode}} <span>@{{flight.Segments[0][0].Destination.Airport.CityName}}, @{{flight.Segments[0][0].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{  flight.Segments[0][0].Destination.ArrTime  |  date:'HH:mm' }}) </div>
                                                            </div>
                                                            <!--                                                            <div class="price_flight_datas" ng-if="flights.Results[1].length">
                                                                                                                            <h3>@{{ flight.Fare.Currency}} @{{flight.Fare.OfferedFare + (iniscomm / 100 * flight.Fare.OfferedFare) + (conversion / 100 * (flight.Fare.OfferedFare + (iniscomm / 100 * flight.Fare.OfferedFare))) | number : 2}} 
                                                                                                                                <span>@{{flight.Segments[0][0].NoOfSeatAvailable}} seat(s) left </span>
                                                                                                                            </h3>
                                                                                                                        </div>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Domestic One Way With Stop Div  -->
                                            <div class="row mt-4" ng-if="flight.Segments[0][1]" style="display:none;"> 
                                                <div class="col-md-2">
                                                    <div class="plane_data">
                                                        <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][1].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][1].Airline.AirlineName}}">
                                                    </div>
                                                    <span class="flight_name">@{{ flight.Segments[0][1].Airline.AirlineName}}</span>
                                                    <span class="flight_serial_number">@{{ flight.Segments[0][1].Airline.AirlineCode}}  @{{ flight.Segments[0][1].Airline.FlightNumber}}</span>
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    <div class="city_time">@{{flight.Segments[0][1].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[0][1].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                    <div class="airpot_name_data">@{{flight.Segments[0][1].Origin.Airport.AirportName}}, @{{flight.Segments[0][1].Origin.Airport.CountryName}}</div>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <div class="flight_duration">
                                                        <span>@{{flight.Segments[0][1].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                    </div>
                                                    <!-- <div class="flg_tech">Flight Duration</div> -->
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="city_time">@{{flight.Segments[0][1].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[0][1].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                    <div class="airpot_name_data">@{{flight.Segments[0][1].Destination.Airport.AirportName}}, @{{flight.Segments[0][1].Destination.Airport.CountryName}}</div>
                                                </div>
                                            </div>
                                            <!-- One Way Stop Div Ends  -->

                                            <!-- Domestic One Way With Second Stop Div  -->
                                            <div class="row mt-4" ng-if="flight.Segments[0][2]" style="display:none;"> 
                                                <div class="col-md-2">
                                                    <div class="plane_data">
                                                        <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][2].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][2].Airline.AirlineName}}">
                                                    </div>
                                                    <span class="flight_name">@{{ flight.Segments[0][2].Airline.AirlineName}}</span>
                                                    <span class="flight_serial_number">@{{ flight.Segments[0][2].Airline.AirlineCode}}  @{{ flight.Segments[0][2].Airline.FlightNumber}}</span>
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    <div class="city_time">@{{flight.Segments[0][2].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[0][2].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                    <div class="airpot_name_data">@{{flight.Segments[0][2].Origin.Airport.AirportName}}, @{{flight.Segments[0][2].Origin.Airport.CountryName}}</div>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <div class="flight_duration">
                                                        <span>@{{flight.Segments[0][2].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                    </div>
                                                    <!-- <div class="flg_tech">Flight Duration</div> -->
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="city_time">@{{flight.Segments[0][2].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[0][2].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                    <div class="airpot_name_data">@{{flight.Segments[0][2].Destination.Airport.AirportName}}, @{{flight.Segments[0][2].Destination.Airport.CountryName}}</div>
                                                </div>
                                            </div>
                                            <!-- One Way Stop Div Ends  -->

                                            <!-- INternational Return Div -->
                                            <div  class="refundable_data" ng-if="flight.Segments[1][0]"></div>
                                            <div class="row mt-2" ng-if="flight.Segments[1][0]">
                                                <div ng-class="flights.Results[1].length > 0  ? 'col-md-12' : 'col-md-12'">
                                                    <div class="flex_width_air">
                                                        <div class="vistara_Data">
                                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[1][0].Airline.AirlineCode}}" alt="@{{ flight.Segments[1][0].Airline.AirlineName}}">
                                                            <span>@{{ flight.Segments[1][0].Airline.AirlineName}}
                                                                @{{ flight.Segments[1][0].Airline.AirlineCode}}  @{{ flight.Segments[1][0].Airline.FlightNumber}}
                                                            </span> <span class="multistop" ng-if="flight.Segments[1][2]">(2 stops)</span><span class="onestop" ng-if="flight.Segments[1][1] && !flight.Segments[1][2]">(1 stop)</span><span class="direct" ng-if="!flight.Segments[1][1] && !flight.Segments[1][2]">(Direct)</span>
                                                        </div>
                                                        <div class="main_flts_time">
                                                            <div class="fllight_time_date">
                                                                <h5>@{{flight.Segments[1][0].Origin.Airport.AirportCode}} <span>@{{flight.Segments[1][0].Origin.Airport.CityName}}, @{{flight.Segments[1][0].Origin.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[1][0].Origin.DepTime|  date:'HH:mm' }})</div>
                                                            </div>
                                                            <!-- <div class="time_hr">@{{flight.Segments[1][0].Duration | time:'mm':'hhh mmm':false }}</div> -->
                                                            <div class="flight_duration"  ng-if="flight.Segments[1][2]">
                                                                <span>@{{ flight.Segments[1][2].AccumulatedDuration  | time:'mm':'hhh mmm':false}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>
                                                            <div class="flight_duration"  ng-if="flight.Segments[1][1] && !flight.Segments[1][2]">
                                                                <span>@{{ flight.Segments[1][1].AccumulatedDuration  | time:'mm':'hhh mmm':false}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>
                                                            <div class="flight_duration"  ng-if="!flight.Segments[1][1] && !flight.Segments[1][2]">
                                                                <span>@{{ flight.Segments[1][0].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>

                                                            <div class="fllight_time_date" ng-if="flight.Segments[1][2]">
                                                                <h5>@{{flight.Segments[1][2].Destination.Airport.AirportCode}} <span>@{{flight.Segments[1][2].Destination.Airport.CityName}}, @{{flight.Segments[1][2].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[1][2].Destination.ArrTime|  date:'HH:mm' }})</div>
                                                            </div>

                                                            <div class="fllight_time_date" ng-if="flight.Segments[1][1] && !flight.Segments[1][2]">
                                                                <h5>@{{flight.Segments[1][1].Destination.Airport.AirportCode}} <span>@{{flight.Segments[1][1].Destination.Airport.CityName}}, @{{flight.Segments[1][1].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[1][1].Destination.ArrTime|  date:'HH:mm' }})</div>
                                                            </div>

                                                            <div class="fllight_time_date" ng-if="!flight.Segments[1][1] && !flight.Segments[1][2]">
                                                                <h5>@{{flight.Segments[1][0].Destination.Airport.AirportCode}} <span>@{{flight.Segments[1][0].Destination.Airport.CityName}}, @{{flight.Segments[1][0].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[1][0].Destination.ArrTime|  date:'HH:mm' }})</div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Div Ends -->

                                            <!-- INternational Return With One Stop  Div -->

                                            <div class="row mt-2" ng-if="flight.Segments[1][1]" style="display:none;">
                                                <div class="col-md-9">
                                                    <div class="flex_width_air">
                                                        <div class="vistara_Data">
                                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[1][1].Airline.AirlineCode}}" alt="@{{ flight.Segments[1][1].Airline.AirlineName}}">
                                                            <span>@{{ flight.Segments[1][1].Airline.AirlineName}}
                                                                @{{ flight.Segments[1][1].Airline.AirlineCode}}  @{{ flight.Segments[1][1].Airline.FlightNumber}}
                                                            </span>
                                                        </div>
                                                        <div class="main_flts_time">
                                                            <div class="fllight_time_date">
                                                                <h5>@{{flight.Segments[1][1].Origin.Airport.AirportCode}} <span>@{{flight.Segments[1][1].Origin.Airport.CityName}}, @{{flight.Segments[1][1].Origin.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[1][1].Origin.DepTime|  date:'HH:mm' }})</div>
                                                            </div>
                                                            <!-- <div class="time_hr">@{{flight.Segments[1][1].Duration | time:'mm':'hhh mmm':false }}</div> -->
                                                            <div class="flight_duration">
                                                                <span>@{{flight.Segments[1][1].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>
                                                            <div class="fllight_time_date">
                                                                <h5>@{{flight.Segments[1][1].Destination.Airport.AirportCode}} <span>@{{flight.Segments[1][1].Destination.Airport.CityName}}, @{{flight.Segments[1][1].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[1][1].Destination.ArrTime|  date:'HH:mm' }})</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Div Ends -->

                                            <!-- INternational Return With Second Stop  Div -->

                                            <div class="row mt-2" ng-if="flight.Segments[1][2]" style="display:none;">
                                                <div class="col-md-9">
                                                    <div class="flex_width_air">
                                                        <div class="vistara_Data">
                                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[1][2].Airline.AirlineCode}}" alt="@{{ flight.Segments[1][2].Airline.AirlineName}}">
                                                            <span>@{{ flight.Segments[1][2].Airline.AirlineName}}
                                                                @{{ flight.Segments[1][2].Airline.AirlineCode}}  @{{ flight.Segments[1][2].Airline.FlightNumber}}
                                                            </span>
                                                        </div>
                                                        <div class="main_flts_time">
                                                            <div class="fllight_time_date">
                                                                <h5>@{{flight.Segments[1][2].Origin.Airport.AirportCode}} <span>@{{flight.Segments[1][2].Origin.Airport.CityName}}, @{{flight.Segments[1][2].Origin.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[1][2].Origin.DepTime|  date:'HH:mm' }})</div>
                                                            </div>
                                                            <!-- <div class="time_hr">@{{flight.Segments[1][2].Duration | time:'mm':'hhh mmm':false }}</div> -->
                                                            <div class="flight_duration">
                                                                <span>@{{flight.Segments[1][2].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>
                                                            <div class="fllight_time_date">
                                                                <h5>@{{flight.Segments[1][2].Destination.Airport.AirportCode}} <span>@{{flight.Segments[1][2].Destination.Airport.CityName}}, @{{flight.Segments[1][2].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[1][2].Destination.ArrTime|  date:'HH:mm' }})</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Div Ends -->

                                        </div>
                                        <div class="col-lg-2  col-4" style="margin:auto;">
                                            <div class="book_data_btn text-center">
                                                <div class="price_flight_datas" >
                                                    <h3>@{{ flight.Fare.Currency}} @{{ flight.Fare.FinalPrice | number : 2}} 
                                                        <span>@{{flight.Segments[0][0].NoOfSeatAvailable}} seat(s) left </span>
                                                    </h3>
                                                </div>
                                                <a style="margin-top:5px;" href="/flight/@{{ flights.TraceId}}/@{{ flight.ResultIndex}}/0/@{{flight.IsLCC}}/{{$referral}}/@{{ search_id }}" target="_blank"  class="btn btn_book" ng-if="!flights.Results[1].length" >Book</a> 
                                                <input ng-if="flights.Results[1].length > 0" type="radio" name="book" data-fcode="@{{ flight.Segments[0][0].Airline.AirlineCode}}" data-flightcode="@{{ flight.Segments[0][0].Airline.AirlineName}} @{{ flight.Segments[0][0].Airline.AirlineCode}} - @{{ flight.Segments[0][0].Airline.FlightNumber}}" data-from-time="(@{{flight.Segments[0][0].Origin.DepTime|  date:'HH:mm' }})" data-to-time="(@{{flight.Segments[0][0].Destination.ArrTime|  date:'HH:mm' }})"  data-duration="@{{flight.Segments[0][0].Duration| time:'mm':'hhh mmm':false }}" data-price="@{{ flight.Fare.FinalPrice | number:2}}" data-currency="@{{flight.Fare.Currency}}"  data-seats-left="@{{flight.Segments[0][0].NoOfSeatAvailable}}" data-air-img="@{{ flight.Segments[0][0].Airline.AirlineName}}" data-trace-Id="@{{ flights.TraceId}}" data-result-index="@{{ flight.ResultIndex}}" data-referral="{{$referral}}"  data-lcc="@{{flight.IsLCC}}" class="form-control" id="book_id_@{{$index}}" value="">
                                            </div>

                                            

                                            @if($isAgent)
                                            <div class="email-hotel-check form-check">
                                                <input type="checkbox" class="form-check-input" name="email-hotels" value="@{{ flight.ResultIndex}}" ng-click="toggleSelection(flight, 'Outbound Flight')">
                                                <span class="airline_name">
                                                    <span class="air_name_set">Email</span>
                                                </span>  
                                            </div>
                                            @endif

                                             <!-- Social Meadia -->
                                            <div ng-if="!flights.Results[1]" class="share-post-icons-flight" id="post_share_icon_@{{ flight.ResultIndex }}">
                                                <div class="row"><i class="fa fa-share-alt" ng-click="showSocialMedia(flight.ResultIndex)" aria-hidden="true"></i></div>
                                                <div class="row mediaicons show-social-icons-@{{ flight.ResultIndex }}" style="display: none;">
                                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}" class="share-to fb-clr" data-type="fb" target="_blank">
                                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="whatsapp://send?text={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}" class="share-to whatsp-clr" data-type="wp" target="_blank">
                                                        <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="http://twitter.com/share?text={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}" class="share-to tw-clr" data-type="tw" target="_blank">
                                                        <i class="fa fa-twitter" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="share-to pint-clr" data-type="pt" href="https://pinterest.com/pin/create/bookmarklet/?media=https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][0].Airline.AirlineCode}}&url={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}" target="_blank">
                                                        <i class="fa fa-pinterest" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="share-to stb-clr" data-type="stb" href="https://www.stumbleupon.com/submit?url={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}&title=@{{ flight.Segments[0][0].Airline.AirlineName}} @{{ flight.Segments[0][0].Airline.AirlineCode}} - @{{ flight.Segments[0][0].Airline.FlightNumber}}" target="_blank">
                                                        <i class="fa fa-stumbleupon" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="share-to lkn-clr" data-type="lkn" href="https://www.linkedin.com/shareArticle?url={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}&title=@{{ flight.Segments[0][0].Airline.AirlineName}} @{{ flight.Segments[0][0].Airline.AirlineCode}} - @{{ flight.Segments[0][0].Airline.FlightNumber}}" target="_blank">
                                                        <i class="fa fa-linkedin" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="share-to inst-clr" data-type="inst" href="https://www.instagram.com/?url={{ env('APP_URL') }}/share-flight/@{{searchData.JourneyType}}/@{{searchData.origin}}/@{{searchData.from}}/@{{searchData.destination}}/@{{searchData.to}}/@{{searchData.departDate}}/@{{searchData.returnDateShr}}/@{{searchData.travellersClass}}/@{{searchData.referral}}/@{{searchData.adultsF}}/@{{searchData.childsF}}&quote=Flight From {{ $input['from']}} to {{ $input['to']}}" target="_blank">
                                                        <i class="fa fa-instagram" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- Ends  -->

                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="refundable_data">
                                                <span class="non_refundable">Non-Refundable</span>
                                                <a href="javascript:void(0);" class="btn btn_flight_details" data-id="@{{$index}}">{{ __('labels.flight_details')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flight_information_data" id="view_flight_@{{$index}}" style="display: none;">
                                        <nav>
                                            <div class="nav nav-tabs nav-fill" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-flightInformation-tab" data-toggle="tab" href="#nav-flightInformation_@{{$index}}" role="tab" aria-controls="nav-flightInformation" aria-selected="true">{{ __('labels.flight_info')}}</a>
                                                <a class="nav-item nav-link" id="nav-fare-tab" data-toggle="tab" href="#nav-fare_@{{$index}}" role="tab" aria-controls="nav-fare" aria-selected="false">Fare Details</a>
                                                <a class="nav-item nav-link" id="nav-baggage-tab" data-toggle="tab" href="#nav-baggage_@{{$index}}" role="tab" aria-controls="nav-baggage" aria-selected="false">Baggage Rules</a>
                                            </div>
                                        </nav>
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="nav-flightInformation_@{{$index}}" role="tabpanel" aria-labelledby="nav-flightInformation-tab">
                                                <div class="inner_flight_tabs">
                                                    <div class="flght_data_info">
                                                        <!-- Domestic Oneway Div -->
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="plane_data">
                                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][0].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][0].Airline.AirlineName}}">
                                                                </div>
                                                                <span class="flight_name">@{{ flight.Segments[0][0].Airline.AirlineName}}</span>
                                                                <span class="flight_serial_number">@{{ flight.Segments[0][0].Airline.AirlineCode}}  @{{ flight.Segments[0][0].Airline.FlightNumber}}</span>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <div class="city_time">@{{flight.Segments[0][0].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[0][0].Origin.DepTime|  date:'HH:mm'}})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][0].Origin.Airport.AirportName}}, @{{flight.Segments[0][0].Origin.Airport.CountryName}}</div>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="flight_duration">
                                                                    <span>@{{flight.Segments[0][0].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                                </div>
                                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="city_time">@{{flight.Segments[0][0].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[0][0].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][0].Destination.Airport.AirportName}}, @{{flight.Segments[0][0].Destination.Airport.CountryName}}</div>
                                                            </div>
                                                        </div>

                                                        <!-- Domestic One Way With One Stop Div -->
                                                        <div class="row mt-4" ng-if="flight.Segments[0][1]">
                                                            <div class="col-md-2">
                                                                <div class="plane_data">
                                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][1].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][1].Airline.AirlineName}}">
                                                                </div>
                                                                <span class="flight_name">@{{ flight.Segments[0][1].Airline.AirlineName}}</span>
                                                                <span class="flight_serial_number">@{{ flight.Segments[0][1].Airline.AirlineCode}}  @{{ flight.Segments[0][1].Airline.FlightNumber}}</span>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <div class="city_time">@{{flight.Segments[0][1].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[0][1].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][1].Origin.Airport.AirportName}}, @{{flight.Segments[0][1].Origin.Airport.CountryName}}</div>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="flight_duration">
                                                                    <span>@{{flight.Segments[0][1].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                                </div>
                                                                <!--  <div class="flg_tech">Flight Duration</div> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="city_time">@{{flight.Segments[0][1].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[0][1].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][1].Destination.Airport.AirportName}}, @{{flight.Segments[0][1].Destination.Airport.CountryName}}</div>
                                                            </div>
                                                        </div>
                                                        <!-- Ends here -->

                                                        <!-- Domestic One Way With Second Stop Div -->
                                                        <div class="row mt-4" ng-if="flight.Segments[0][2]">
                                                            <div class="col-md-2">
                                                                <div class="plane_data">
                                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][2].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][2].Airline.AirlineName}}">
                                                                </div>
                                                                <span class="flight_name">@{{ flight.Segments[0][2].Airline.AirlineName}}</span>
                                                                <span class="flight_serial_number">@{{ flight.Segments[0][2].Airline.AirlineCode}}  @{{ flight.Segments[0][2].Airline.FlightNumber}}</span>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <div class="city_time">@{{flight.Segments[0][2].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[0][2].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][2].Origin.Airport.AirportName}}, @{{flight.Segments[0][2].Origin.Airport.CountryName}}</div>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="flight_duration">
                                                                    <span>@{{flight.Segments[0][2].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                                </div>
                                                                <!--  <div class="flg_tech">Flight Duration</div> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="city_time">@{{flight.Segments[0][2].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[0][2].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][2].Destination.Airport.AirportName}}, @{{flight.Segments[0][2].Destination.Airport.CountryName}}</div>
                                                            </div>
                                                        </div>
                                                        <!-- Ends here -->

                                                        <!-- International Return Div  -->
                                                        <div  class="refundable_data" ng-if="flight.Segments[1][0]"></div>
                                                        <div class="row mt-2" ng-if="flight.Segments[1][0]">
                                                            <div class="col-md-2">
                                                                <div class="plane_data">
                                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[1][0].Airline.AirlineCode}}" alt="@{{ flight.Segments[1][0].Airline.AirlineName}}">
                                                                </div>
                                                                <span class="flight_name">@{{ flight.Segments[1][0].Airline.AirlineName}}</span>
                                                                <span class="flight_serial_number">@{{ flight.Segments[1][0].Airline.AirlineCode}}  @{{ flight.Segments[1][0].Airline.FlightNumber}}</span>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <div class="city_time">@{{flight.Segments[1][0].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[1][0].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[1][0].Origin.Airport.AirportName}}, @{{flight.Segments[1][0].Origin.Airport.CountryName}}</div>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="flight_duration">
                                                                    <span>@{{flight.Segments[1][0].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                                </div>
                                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="city_time">@{{flight.Segments[1][0].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[1][0].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[1][0].Destination.Airport.AirportName}}, @{{flight.Segments[1][0].Destination.Airport.CountryName}}</div>
                                                            </div>
                                                        </div>
                                                        <!-- Ends Here -->

                                                        <!-- International Return With one stop Div -->
                                                        <div class="row mt-2" ng-if="flight.Segments[1][1]">
                                                            <div class="col-md-2">
                                                                <div class="plane_data">
                                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[1][1].Airline.AirlineCode}}" alt="@{{ flight.Segments[1][1].Airline.AirlineName}}">
                                                                </div>
                                                                <span class="flight_name">@{{ flight.Segments[1][1].Airline.AirlineName}}</span>
                                                                <span class="flight_serial_number">@{{ flight.Segments[1][1].Airline.AirlineCode}}  @{{ flight.Segments[1][1].Airline.FlightNumber}}</span>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <div class="city_time">@{{flight.Segments[1][1].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[1][1].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[1][1].Origin.Airport.AirportName}}, @{{flight.Segments[1][1].Origin.Airport.CountryName}}</div>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="flight_duration">
                                                                    <span>@{{flight.Segments[1][1].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                                </div>
                                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="city_time">@{{flight.Segments[1][1].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[1][1].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[1][1].Destination.Airport.AirportName}}, @{{flight.Segments[1][1].Destination.Airport.CountryName}}</div>
                                                            </div>
                                                        </div>
                                                        <!-- Ends Here -->

                                                        <!-- International Return With second stop Div -->
                                                        <div class="row mt-2" ng-if="flight.Segments[1][2]">
                                                            <div class="col-md-2">
                                                                <div class="plane_data">
                                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[1][2].Airline.AirlineCode}}" alt="@{{ flight.Segments[1][2].Airline.AirlineName}}">
                                                                </div>
                                                                <span class="flight_name">@{{ flight.Segments[1][2].Airline.AirlineName}}</span>
                                                                <span class="flight_serial_number">@{{ flight.Segments[1][2].Airline.AirlineCode}}  @{{ flight.Segments[1][2].Airline.FlightNumber}}</span>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <div class="city_time">@{{flight.Segments[1][2].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[1][2].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[1][2].Origin.Airport.AirportName}}, @{{flight.Segments[1][2].Origin.Airport.CountryName}}</div>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="flight_duration">
                                                                    <span>@{{flight.Segments[1][2].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                                </div>
                                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="city_time">@{{flight.Segments[1][2].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[1][2].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[1][2].Destination.Airport.AirportName}}, @{{flight.Segments[1][2].Destination.Airport.CountryName}}</div>
                                                            </div>
                                                        </div>
                                                        <!-- Ends Here -->

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-fare_@{{$index}}" role="tabpanel" aria-labelledby="nav-fare-tab">
                                                <div class="inner_flight_tabs">
                                                    <table class="table">
                                                        <tr>
                                                            <td>Base Fare ( {{ $input['adultsF']}} Adult @if($input['childsF']) &  {{ $input['childsF']}} Child @endif )</td>
                                                            <td>@{{flight.Fare.Currency}} @{{flight.Fare.BaseFare + (iniscomm / 100 * flight.Fare.BaseFare) | number:2}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Taxes and Fees ( {{ $input['adultsF']}} Adult @if($input['childsF']) &  {{ $input['childsF']}} Child @endif )</td>
                                                            <td>@{{flight.Fare.Currency}} @{{flight.Fare.Tax + (iniscomm / 100 * flight.Fare.Tax) | number:2}} </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total Fare ( {{ $input['adultsF']}} Adult @if($input['childsF']) &  {{ $input['childsF']}} Child @endif )</th>
                                                            <th>@{{ flight.Fare.Currency}} @{{ flight.Fare.FinalPrice | number: 2}} </th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-baggage_@{{$index}}" role="tabpanel" aria-labelledby="nav-baggage-tab">
                                                <div class="inner_flight_tabs">
                                                    <table class="table">
                                                        <tr>
                                                            <td>Baggage Type</td>
                                                            <td>Check-In</td>
                                                            <td>Cabin</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Adult</td>
                                                            <td>@{{ flight.Segments[0][0].Baggage}}</td>
                                                            <td>@{{ flight.Segments[0][0].CabinBaggage}}</td>
                                                        </tr>
                                                    </table>
                                                    <!-- <p class="baggage_data">* Only 1 check-in baggage allowed per passenger. You can buy excess baggage as allowed by the airline, however you might have to pay additional charges at airport.</p> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div style="text-align: center;width:100%;padding-top:25px;" ng-if="flights1.length == 0">No Matching Flight found</div>
                                <div class="col-md-12 no-flight" ng-if="loaded && flights.length < 1">
                                    <div class="cab_list cab_large_sec_data container loading" >
                                        <h2 class="error_data" ng-bind="errorMessage"></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="returnFlightTab"  ng-if="flights.Results[1].length > 0">
                                <div class="air_list_data f-list" ng-repeat="flight in flights2" data-price="@{{flight.Fare.FinalPrice}}" data-cabinclass="Cabin@{{ flight.Segments[0][0].CabinClass}}" data-return="@{{flight.time}}" data-air="@{{flight.Segments[0][0].Airline.AirlineName}}" data-stops="@{{ flight.Segments[0][0].Airline ? 'Stop1' : 'Stop0' }}">

                                    <div class="row">
                                        <div class="col-8  col-lg-10" style="border-right: 1px dashed #ccc;">
                                            <div class="row align-items-end">
                                                <div class="col-md-12" >
                                                    <div class="flex_width_air">
                                                        <div class="vistara_Data">
                                                            @{{flight.Segments[0][1].Airline.AirlineName}} 
                                                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][0].Airline.AirlineCode}}" alt="@{{flight.Segments[0][0].Airline.AirlineName}}" />
                                                            <span>@{{flight.Segments[0][0].Airline.AirlineName}} 
                                                                @{{flight.Segments[0][0].Airline.AirlineCode}} @{{flight.Segments[0][0].Airline.FlightNumber}}
                                                            </span> <span class="multistop" ng-if="flight.Segments[0][2]">(2 stops)</span><span class="onestop" ng-if="flight.Segments[0][1] && !flight.Segments[0][2]">(1 stop)</span><span class="direct" ng-if="!flight.Segments[0][1] && !flight.Segments[0][2]">(Direct)</span>
                                                        </div>
                                                        <div class="main_flts_time">
                                                            <div class="fllight_time_date">
                                                                <h5>@{{flight.Segments[0][0].Origin.Airport.AirportCode}} <span>@{{flight.Segments[0][0].Origin.Airport.CityName}}, @{{flight.Segments[0][0].Origin.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[0][0].Origin.DepTime|  date:'HH:mm' }})</div>
                                                            </div>
                                                            <!--  <div class="time_hr">@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}</div> -->
                                                            <div class="flight_duration" ng-if="flight.Segments[0][2]">
                                                                <span>@{{ flight.Segments[0][2].AccumulatedDuration  | time:'mm':'hhh mmm':false}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>

                                                            <div class="flight_duration" ng-if="flight.Segments[0][1] && !flight.Segments[0][2]">
                                                                <span>@{{ flight.Segments[0][1].AccumulatedDuration | time:'mm':'hhh mmm':false}}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>

                                                            <div class="flight_duration" ng-if="!flight.Segments[0][1] && !flight.Segments[0][2]">
                                                                <span>@{{ flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                            </div>

                                                            <div class="fllight_time_date" ng-if="flight.Segments[0][2]">
                                                                <h5>@{{flight.Segments[0][2].Destination.Airport.AirportCode}} <span>@{{flight.Segments[0][2].Destination.Airport.CityName}}, @{{flight.Segments[0][2].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[0][2].Destination.ArrTime|  date:'HH:mm' }})</div>
                                                            </div>

                                                            <div class="fllight_time_date" ng-if="flight.Segments[0][1] && !flight.Segments[0][2]">
                                                                <h5>@{{flight.Segments[0][1].Destination.Airport.AirportCode}} <span>@{{flight.Segments[0][1].Destination.Airport.CityName}}, @{{flight.Segments[0][1].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[0][1].Destination.ArrTime|  date:'HH:mm' }})</div>
                                                            </div>

                                                            <div class="fllight_time_date" ng-if="!flight.Segments[0][1] && !flight.Segments[0][2]">
                                                                <h5>@{{flight.Segments[0][0].Destination.Airport.AirportCode}} <span>@{{flight.Segments[0][0].Destination.Airport.CityName}}, @{{flight.Segments[0][0].Destination.Airport.CountryName}}</span></h5>
                                                                <div class="time_flts">(@{{flight.Segments[0][0].Destination.ArrTime|  date:'HH:mm' }})</div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Domestic Return With Stop Div  -->
                                            <div class="row mt-4" ng-if="flight.Segments[0][1]" style="display:none;"> 
                                                <div class="col-md-2">
                                                    <div class="plane_data">
                                                        <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][1].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][1].Airline.AirlineName}}">
                                                    </div>
                                                    <span class="flight_name">@{{ flight.Segments[0][1].Airline.AirlineName}}</span>
                                                    <span class="flight_serial_number">@{{ flight.Segments[0][1].Airline.AirlineCode}}  @{{ flight.Segments[0][1].Airline.FlightNumber}}</span>
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    <div class="city_time">@{{flight.Segments[0][1].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[0][1].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                    <div class="airpot_name_data">@{{flight.Segments[0][1].Origin.Airport.AirportName}}, @{{flight.Segments[0][1].Origin.Airport.CountryName}}</div>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <div class="flight_duration">
                                                        <span>@{{flight.Segments[0][1].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                    </div>
                                                    <!-- <div class="flg_tech">Flight Duration</div> -->
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="city_time">@{{flight.Segments[0][1].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[0][1].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                    <div class="airpot_name_data">@{{flight.Segments[0][1].Destination.Airport.AirportName}}, @{{flight.Segments[0][1].Destination.Airport.CountryName}}</div>
                                                </div>
                                            </div>
                                            <!-- One Way Stop Div Ends  -->

                                            <!-- Domestic Return With Second Stop Div  -->
                                            <div class="row mt-4" ng-if="flight.Segments[0][2]" style="display:none;"> 
                                                <div class="col-md-2">
                                                    <div class="plane_data">
                                                        <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][2].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][2].Airline.AirlineName}}">
                                                    </div>
                                                    <span class="flight_name">@{{ flight.Segments[0][2].Airline.AirlineName}}</span>
                                                    <span class="flight_serial_number">@{{ flight.Segments[0][2].Airline.AirlineCode}}  @{{ flight.Segments[0][2].Airline.FlightNumber}}</span>
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    <div class="city_time">@{{flight.Segments[0][2].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[0][2].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                    <div class="airpot_name_data">@{{flight.Segments[0][2].Origin.Airport.AirportName}}, @{{flight.Segments[0][2].Origin.Airport.CountryName}}</div>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <div class="flight_duration">
                                                        <span>@{{flight.Segments[0][2].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                    </div>
                                                    <!-- <div class="flg_tech">Flight Duration</div> -->
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="city_time">@{{flight.Segments[0][2].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[0][2].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                    <div class="airpot_name_data">@{{flight.Segments[0][2].Destination.Airport.AirportName}}, @{{flight.Segments[0][2].Destination.Airport.CountryName}}</div>
                                                </div>
                                            </div>
                                            <!-- One Way Stop Div Ends  -->

                                        </div>
                                        <div class="col-4  col-lg-2 text-center" style="margin:auto;">
                                            <div class="price_flight_datas">
                                                <h3>@{{ flight.Fare.Currency}} @{{ flight.Fare.FinalPrice | number:2}} 
                                                    <span>@{{flight.Segments[0][0].NoOfSeatAvailable}} seat(s) left </span>
                                                </h3>
                                            </div>
                                            <div class="book_data_btn">

                                                <a href="/flight/@{{ flights.TraceId}}/@{{ flight.ResultIndex}}/0/@{{flight.IsLCC}}/{{$referral}}/@{{ search_id }}" target="_blank"  class="btn btn_book" ng-if="!flights.Results[1].length" >Book</a> 

                                                <input ng-if="flights.Results[1].length > 0" type="radio" name="book_return" data-fcode="@{{ flight.Segments[0][0].Airline.AirlineCode}}" data-flightcode-return="@{{ flight.Segments[0][0].Airline.AirlineName}} @{{ flight.Segments[0][0].Airline.AirlineCode}} - @{{ flight.Segments[0][0].Airline.FlightNumber}}" data-from-time-return="(@{{flight.Segments[0][0].Origin.DepTime|  date:'HH:mm' }})" data-to-time-return="(@{{flight.Segments[0][0].Destination.ArrTime|  date:'HH:mm' }})"  data-duration-return="@{{flight.Segments[0][0].Duration| time:'mm':'hhh mmm':false }}" data-price-return="@{{ flight.Fare.FinalPrice | number:2}}" data-currency-return="@{{flight.Fare.Currency}}"  data-seats-left-return="@{{flight.Segments[0][0].NoOfSeatAvailable}}" data-air-img-return="@{{ flight.Segments[0][0].Airline.AirlineName}}" data-trace-Id="@{{ flights.TraceId}}" data-result-index="@{{ flight.ResultIndex}}" data-lcc-return="@{{flight.IsLCC}}" class="form-control" id="book_return_id_@{{$index}}" data-referral-return="{{$referral}}" value="">

                                            </div>
                                            <div class="email-hotel-check form-check">
                                                <input type="checkbox" class="form-check-input" name="email-hotels" value="@{{ flight.ResultIndex}}" ng-click="toggleSelection(flight, 'Inbound Flight')">
                                                <span class="airline_name">
                                                    <span class="air_name_set">Email</span>
                                                </span>  
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="refundable_data">
                                                <span class="non_refundable">Non-Refundable</span>
                                                <a href="javascript:void(0);" class="btn btn_flight_details_return" data-id="@{{$index}}">Flight Details</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flight_information_data" id="view_flight_return_@{{$index}}" style="display: none;">
                                        <nav>
                                            <div class="nav nav-tabs nav-fill" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-flightInformation-tab" data-toggle="tab" href="#nav-flightInformation_return_@{{$index}}" role="tab" aria-controls="nav-flightInformation" aria-selected="true">Flight Information</a>
                                                <a class="nav-item nav-link" id="nav-fare-tab" data-toggle="tab" href="#nav-fare_return_@{{$index}}" role="tab" aria-controls="nav-fare" aria-selected="false">Fare Details</a>
                                                <a class="nav-item nav-link" id="nav-baggage-tab" data-toggle="tab" href="#nav-baggage_return_@{{$index}}" role="tab" aria-controls="nav-baggage" aria-selected="false">Baggage Rules</a>
                                            </div>
                                        </nav>
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="nav-flightInformation_return_@{{$index}}" role="tabpanel" aria-labelledby="nav-flightInformation-tab">
                                                <div class="inner_flight_tabs">
                                                    <div class="flght_data_info">
                                                        <!-- Domestic Oneway Div -->
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="plane_data">
                                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][0].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][0].Airline.AirlineName}}">
                                                                </div>
                                                                <span class="flight_name">@{{ flight.Segments[0][0].Airline.AirlineName}}</span>
                                                                <span class="flight_serial_number">@{{ flight.Segments[0][0].Airline.AirlineCode}}  @{{ flight.Segments[0][0].Airline.FlightNumber}}</span>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <div class="city_time">@{{flight.Segments[0][0].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[0][0].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][0].Origin.Airport.AirportName}}, @{{flight.Segments[0][0].Origin.Airport.CountryName}}</div>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="flight_duration">
                                                                    <span>@{{flight.Segments[0][0].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                                </div>
                                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="city_time">@{{flight.Segments[0][0].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[0][0].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][0].Destination.Airport.AirportName}}, @{{flight.Segments[0][0].Destination.Airport.CountryName}}</div>
                                                            </div>
                                                        </div>

                                                        <!-- Domestic One Way With One Stop Div -->
                                                        <div class="row mt-4" ng-if="flight.Segments[0][1]">
                                                            <div class="col-md-2">
                                                                <div class="plane_data">
                                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][1].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][1].Airline.AirlineName}}">
                                                                </div>
                                                                <span class="flight_name">@{{ flight.Segments[0][1].Airline.AirlineName}}</span>
                                                                <span class="flight_serial_number">@{{ flight.Segments[0][1].Airline.AirlineCode}}  @{{ flight.Segments[0][1].Airline.FlightNumber}}</span>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <div class="city_time">@{{flight.Segments[0][1].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[0][1].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][1].Origin.Airport.AirportName}}, @{{flight.Segments[0][1].Origin.Airport.CountryName}}</div>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="flight_duration">
                                                                    <span>@{{flight.Segments[0][1].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                                </div>
                                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="city_time">@{{flight.Segments[0][1].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[0][1].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][1].Destination.Airport.AirportName}}, @{{flight.Segments[0][1].Destination.Airport.CountryName}}</div>
                                                            </div>
                                                        </div>
                                                        <!-- Ends here -->

                                                        <!-- Domestic One Way With Second Stop Div -->
                                                        <div class="row mt-4" ng-if="flight.Segments[0][2]">
                                                            <div class="col-md-2">
                                                                <div class="plane_data">
                                                                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ flight.Segments[0][2].Airline.AirlineCode}}" alt="@{{ flight.Segments[0][2].Airline.AirlineName}}">
                                                                </div>
                                                                <span class="flight_name">@{{ flight.Segments[0][2].Airline.AirlineName}}</span>
                                                                <span class="flight_serial_number">@{{ flight.Segments[0][2].Airline.AirlineCode}}  @{{ flight.Segments[0][2].Airline.FlightNumber}}</span>
                                                            </div>
                                                            <div class="col-md-3 text-right">
                                                                <div class="city_time">@{{flight.Segments[0][2].Origin.Airport.AirportCode}} <span>(@{{flight.Segments[0][2].Origin.DepTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][2].Origin.Airport.AirportName}}, @{{flight.Segments[0][2].Origin.Airport.CountryName}}</div>
                                                            </div>
                                                            <div class="col-md-4 text-center">
                                                                <div class="flight_duration">
                                                                    <span>@{{flight.Segments[0][2].Duration| time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                                                </div>
                                                                <!-- <div class="flg_tech">Flight Duration</div> -->
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="city_time">@{{flight.Segments[0][2].Destination.Airport.AirportCode}} <span>(@{{flight.Segments[0][2].Destination.ArrTime|  date:'HH:mm' }})</span></div>
                                                                <div class="airpot_name_data">@{{flight.Segments[0][2].Destination.Airport.AirportName}}, @{{flight.Segments[0][2].Destination.Airport.CountryName}}</div>
                                                            </div>
                                                        </div>
                                                        <!-- Ends here -->

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-fare_return_@{{$index}}" role="tabpanel" aria-labelledby="nav-fare-tab">
                                                <div class="inner_flight_tabs">
                                                    <table class="table">
                                                        <tr>
                                                            <td>Base Fare ( {{ $input['adultsF']}} Adult @if($input['childsF']) &  {{ $input['childsF']}} Child @endif )</td>
                                                            <td>@{{flight.Fare.Currency}} @{{flight.Fare.BaseFare + (iniscomm / 100 * flight.Fare.BaseFare) | number:2}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Taxes and Fees ( {{ $input['adultsF']}} Adult @if($input['childsF']) &  {{ $input['childsF']}} Child @endif )</td>
                                                            <td>@{{flight.Fare.Currency}} @{{flight.Fare.Tax + (iniscomm / 100 * flight.Fare.Tax) | number:2}} </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total Fare ( {{ $input['adultsF']}} Adult @if($input['childsF']) &  {{ $input['childsF']}} Child @endif )</th>
                                                            <th>@{{ flight.Fare.Currency}} @{{ flight.Fare.FinalPrice | number:2}} </th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-baggage_return_@{{$index}}" role="tabpanel" aria-labelledby="nav-baggage-tab">
                                                <div class="inner_flight_tabs">
                                                    <table class="table">
                                                        <tr>
                                                            <td>Baggage Type</td>
                                                            <td>Check-In</td>
                                                            <td>Cabin</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Adult</td>
                                                            <td>@{{ flight.Segments[0][0].Baggage}}</td>
                                                            <td>@{{ flight.Segments[0][0].CabinBaggage}}</td>
                                                        </tr>
                                                    </table>
                                                    <!-- <p class="baggage_data">* Only 1 check-in baggage allowed per passenger. You can buy excess baggage as allowed by the airline, however you might have to pay additional charges at airport.</p> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div style="text-align: center;width:100%;padding-top:25px;" ng-if="flights2.length == 0">No Matching Flight found</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- LIsting Html -->

                <div  ng-if="hasMoreData"  class="col-lg-8 text-center">
                    <a class="btn btn-primary"  href="javascript:void(0);" ng-click="loadMore();">Load More</a>
                </div>

            </div>
            <!-- LIsting Ends -->
        </div>
    </section>
    <style>
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
        }

        .halfcol{
            width:50% !important;
        }
        .fullcol{
            width:100% !important;
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
        }
        .responsive__tabs ul.scrollable-tabs li a.active {
            background-color: #fff !important;
            border-bottom: 3px solid #fd7e14;
        }

        .f-list input[type='radio']{
            visibility: hidden;
            height: 1px;
        }

        .f-list.active{
            border-left: 15px solid #1e4355;
        }
        .fltimg{
            font-size: 14px;
        }
        .fltimg img{
            max-height: 40px;
            max-width: 40px;
        }
        .fdtl{
            font-size: 14px;
        }

        @media only screen and (max-width: 600px) {
            #priceBoxTop{
                box-shadow: none;
                padding:0px;
                margin:0px;
            }   
        }
        .f-list{

        }
    </style>
    <div class="modal" tabindex="-1" role="dialog" id="mainFilterBox">
        <div class="modal-dialog" role="document">
            <form action="{{ route('search_flights')}}" id="searchFlightsFormRound"  method="GET">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Search Flights</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="listing_inner_forms">
                            <div class="rounding_form_info list_rounding_fts">
                                <input type="hidden" id="JourneyType" ng-model="searchData.JourneyType" name="JourneyType" value="{{ $input['JourneyType']}}">
                                <div class="form-group">
                                    <label>{{ __('labels.from')}}</label>
                                    <select name="origin" class="depart-from" required="">
                                        <option value="{{ $input['origin']}}">{{ $input['from']}}</option>
                                    </select>
                                    <input type="hidden" ng-model="searchData.from" name="from" id="from-city" value="{{ $input['from']}}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('labels.to')}}</label>
                                    <select name="destination" class="depart-to" required="">
                                        <option value="{{ $input['destination']}}">{{ $input['to']}}</option>
                                    </select>
                                    <input type="hidden" ng-model="searchData.from" name="to" id="to-city" value="{{ $input['to']}}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('labels.departure')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                    <div class="input-group" >
                                        <input class="form-control departdate" ng-model="searchData.departDate" type="text" name="departDate" required readonly value="{{ $input['departDate']}}"/>
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="form-group @if($input['JourneyType'] == '1') not-allowed @endif" id="not-allowed">

                                    <label>{{ __('labels.return')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                    <div class="input-group" >
                                        <input class="form-control returndate" ng-model="searchData.returnDate" type="text" name="returnDate" required readonly value="{{ $input['returnDate']}}"/>
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('labels.travel_class')}}</label>
                                    <input type="text" name="travellersClass" id="travellersClass" ng-model="searchData.travellersClass" returnDateid="travellersClass" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="{{ $input['travellersClass']}}">
                                    <div class="travellers gbTravellers">
                                        <div class="appendBottom20">
                                            <p data-cy="adultRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.adults')}}</p>
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
                                                    <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.children')}}</p>
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
                                                    <p data-cy="infantRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.infants')}}</p>
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
                                            <p data-cy="chooseTravelClass" class="latoBold font12 grayText appendBottom10">{{ __('labels.choose_travel_class')}}</p>
                                            <ul class="guestCounter classSelect font12 darkText tcF">
                                                <li data-cy="1" class="selected">{{ __('labels.all')}}</li>
                                                <li data-cy="2" class="">{{ __('labels.economy')}}</li>
                                                <li data-cy="3" class="">{{ __('labels.premium_economy')}}</li>
                                                <li data-cy="4" class="">{{ __('labels.business')}}</li>
                                                <li data-cy="5" class="">{{ __('labels.premium_business')}}</li>
                                                <li data-cy="6" class="">{{ __('labels.first_class')}}</li>
                                            </ul>
                                            <div class="makeFlex appendBottom25">
                                                <div class="makeFlex column childCounter">
                                                    <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.direct_flight')}}</p>
                                                    <ul class="guestCounter font12 darkText gbCounter df">
                                                        <li data-cy="false" class="selected">{{ __('labels.no')}}</li>
                                                        <li data-cy="true" class="">{{ __('labels.yes')}}</li>
                                                    </ul>
                                                </div>
                                                <div class="makeFlex column pushRight infantCounter">
                                                    <p data-cy="infantRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.one_stop_flight')}}</p>
                                                    <ul class="guestCounter font12 darkText gbCounter osf">
                                                        <li data-cy="false" class="selected">{{ __('labels.no')}}</li>
                                                        <li data-cy="true" class="">{{ __('labels.yes')}}</li>
                                                    </ul> 
                                                </div>
                                                <p></p>
                                                <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">{{ __('labels.apply')}}</button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="small_station_info class_info">
                                        @if($input['FlightCabinClass'] == '1') 
                                        All Cabin Classes
                                        @elseif($input['FlightCabinClass'] == '2')
                                        Economy Class
                                        @elseif($input['FlightCabinClass'] == '3')
                                        PremiumEconomy Class
                                        @elseif($input['FlightCabinClass'] == '4')
                                        Business Class
                                        @elseif($input['FlightCabinClass'] == '5')
                                        PremiumBusiness Class
                                        @elseif($input['FlightCabinClass'] == '6')
                                        First Class
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="referral" class="referral" value="{{ $referral}}">
                                <input type="hidden" name="adultsF" class="adultsF"  ng-model="searchData.adultsF" value="{{ $input['adultsF']}}">
                                <input type="hidden" name="childsF" class="childsF"  ng-model="searchData.childsF" value="{{ $input['childsF']}}">
                                <input type="hidden" name="infants" class="infantsF"  ng-model="searchData.infantsF" value="{{ $input['infants']}}">
                                <input type="hidden" name="FlightCabinClass" ng-model="searchData.FlightCabinClass" class="FlightCabinClass" value="{{ $input['FlightCabinClass']}}">
                                <input type="hidden" name="DirectFlight" ng-model="searchData.DirectFlight" class="DirectFlight" value="{{ $input['DirectFlight']}}">
                                <input type="hidden" name="OneStopFlight" ng-model="searchData.OneStopFlight" class="OneStopFlight" value="{{ $input['OneStopFlight']}}">
                                <input type="hidden" name="results" class="results" value="true">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('labels.search')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="FilterBox">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="filter_data flts_sidebar_data modal-body">
                    <div class="filter_heading">
                        <h3>{{ __('labels.filter')}}</h3>
                        <a href="javascript:void(0)" ng-click="resetFilters();">Reset All</a>
                    </div>
                    <div class="departure_data">
                        <h4>{{ __('labels.departure')}}</h4>
                        <div class="departure_time_info">
                            <label class="current_time_det">
                                <input type="checkbox" value="morning" ng-model="morningx" ng-change="departFilter('morning');" name="morning" id="morning_dept" class="form-control time_oneway">
                                <span>4am - 11am</span>                    
                            </label>
                            <label class="current_time_det">
                                <input type="checkbox" value="afternoon" ng-model="afternoonx" ng-change="departFilter('afternoon');"   name="afternoon" id="afternoon_dept" class="form-control time_oneway">
                                <span>11am - 4pm</span>                    
                            </label>
                            <label class="current_time_det">
                                <input type="checkbox" value="evening" ng-model="eveningx" ng-change="departFilter('evening');"  name="evening" id="evening_dept" class="form-control time_oneway">
                                <span>4pm - 9pm</span>                    
                            </label>
                            <label class="current_time_det">
                                <input type="checkbox" value="night" ng-model="nightx" ng-change="departFilter('night');"  name="night" id="night_dept" class="form-control time_oneway">
                                <span>9pm - 4am</span>                    
                            </label>
                        </div>
                    </div>
                    <div class="departure_data" ng-if="flights.ResponseStatus && flights.Results[1]">
                        <h4>{{ __('labels.return')}}</h4>
                        <div class="departure_time_info">
                            <label class="current_time_det">
                                <input type="checkbox" value="morning" ng-model="morning_returnx" ng-change="returnFilter('morning');"  name="morning_return" class="form-control time_return">
                                <span>4am - 11am</span>                    
                            </label>
                            <label class="current_time_det">
                                <input type="checkbox" value="afternoon" ng-model="afternoon_returnx" ng-change="returnFilter('afternoon');"  name="afternoon_return" class="form-control time_return">
                                <span>11am - 4pm</span>                    
                            </label>
                            <label class="current_time_det">
                                <input type="checkbox" value="evening" ng-model="evening_returnx" ng-change="returnFilter('evening');"  name="evening_return" class="form-control time_return">
                                <span>4pm - 9pm</span>                    
                            </label>
                            <label class="current_time_det">
                                <input type="checkbox" value="night" ng-model="night_return" ng-change="returnFilter('night');"  name="night_return" class="form-control time_return">
                                <span>9pm - 4am</span>                    
                            </label>
                        </div>
                    </div>
                    <div class="departure_data">
                        <h4>{{ __('labels.stops')}}</h4>
                        <div class="departure_time_info">
                            <label class="current_time_det">
                                <input type="checkbox" ng-model="Stop0" ng-change="stopFilter('Direct');" value="Direct" class="form-control stop_flight_val">
                                <span><i class="stop_data">0</i>Stop</span>                    
                            </label>
                            <label class="current_time_det">
                                <input type="checkbox" ng-model="Stop1" ng-change="stopFilter('Indirect');" value="Indirect" class="form-control stop_flight_val">
                                <span><i class="stop_data">1</i>Stop</span>                    
                            </label>
                        </div>
                    </div>

                    <div class="departure_data" ng-if="flights.ResponseStatus && flights.Results[1]">
                        <h4>{{ __('labels.stops')}} {{ __('labels.return')}}</h4>
                        <div class="departure_time_info">
                            <label class="current_time_det">
                                <input type="checkbox" ng-model="Stop0_return" ng-change="returnStopFilter('Direct');" value="Direct" class="form-control stop_flight_val_return">
                                <span><i class="stop_data">0</i>Stop</span>                    
                            </label>
                            <label class="current_time_det">
                                <input type="checkbox" ng-model="Stop1_return" ng-change="returnStopFilter('Indirect');" value="Indirect" class="form-control stop_flight_val_return">
                                <span><i class="stop_data">1</i>Stop</span>                    
                            </label>
                        </div>
                    </div>

                    <div class="departure_data">
                        <div class="filter_heading">
                            <h4>{{ __('labels.pref_airlines')}}</h4>
                        </div>
                        <div class="airline_filters">
                            <div class="inner_check_air_data" ng-repeat="prefered in prefferedflights">
                                <label class="container_airline">  
                                    <span class="airline_name">
                                        <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{ prefered.fcode}}" alt="@{{ prefered.name}}" >
                                        <span class="air_name_set">@{{ prefered.name}}</span>
                                    </span>                    
                                    <input type="checkbox" ng-model="airlines" ng-change="airlineFilter(prefered.name);" class="air-line-type" value="@{{ prefered.name}}" >
                                    <span class="checkmark"></span>
                                </label>
                            </div>   
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <button type="button" style="position:fixed;right:10px;bottom:80px;z-index: 5;padding:15px 20px !important;" class="btn btn-success d-block d-sm-none" data-toggle="modal" data-target="#FilterBox">
        <span class="fa fa-filter fa-lg"></span>
    </button>

    <div id="flight-email-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form ng-submit="sendSelectedHotels()" name="sendHotelEmail">
                <div class="modal-content">
                    <div class="modal-header refresh-header">
                        <h3 class="refresh-header text-center">Send Itineraries by E-mail</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Enter email address</label>
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

    <!--    <button type="button" style="position:fixed;left:10px;bottom:80px;z-index: 5;padding:15px 20px !important;" class="btn btn-info d-block d-sm-none" data-toggle="modal" data-target="#mainFilterBox">
            <span class="fa fa-pencil fa-lg"></span>
        </button>-->
</div>
@endsection