@extends('layouts.app-header')

@section('content')
<input type="hidden" id="paymeUrl" value="{{ env('PAYME_URL') }}">
<input type="hidden" id="paymeKey" value="{{ env('PAYME_KEY') }}">
<section class="listing_banner_forms" id="flightExpireDiv">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs flight-list">
                    <li class="nav-item trip-type" data-type="1">
                        <a class="nav-link @if($input['JourneyType'] == '1') active @endif" data-toggle="tab" href="javascript:void(0);">{{ __('labels.one_way') }}</a>
                    </li>
                    <li class="nav-item trip-type" data-type="2">
                        <a class="nav-link @if($input['JourneyType'] == '2') active @endif" data-toggle="tab" href="javascript:void(0);">{{ __('labels.round_trip') }}</a>
                    </li>
                </ul>
                <form action="{{ route('search_flights') }}" id="searchFlightsFormRound"  method="GET">
                    @csrf
                    <div class="listing_inner_forms">
                        <div class="rounding_form_info">
                            <input type="hidden" id="JourneyType" name="JourneyType" value="{{ $input['JourneyType'] }}">
                            <div class="form-group">
                                <label>{{ __('labels.from') }}</label>
                                <select name="origin" class="depart-from">
                                    <option value="{{ $input['origin'] }}">{{ $input['from'] }}</option>
                                </select>
                                <input type="hidden" name="from" id="from-city" value="{{ $input['from'] }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('labels.to') }}</label>
                                <select name="destination" class="depart-to">
                                    <option value="{{ $input['destination'] }}">{{ $input['to'] }}</option>
                                </select>
                                <input type="hidden" name="to" id="to-city" value="{{ $input['to'] }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('labels.departure') }} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                <div class="input-group" >
                                    <input class="form-control departdate" type="text" name="departDate" required readonly value="{{ $input['departDate'] }}"/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="form-group @if($input['JourneyType'] == '1') not-allowed @endif" id="not-allowed">
                                <label>{{ __('labels.return') }} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                <div class="input-group" >
                                    <input class="form-control returndate" type="text" name="returnDate" required readonly value="{{ ($input['JourneyType'] == '1') ? $input['departDate'] : $input['returnDate'] }}"/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('labels.travel_class') }}</label>
                                <input type="text" name="travellersClass" id="travellersClass" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="{{ $input['travellersClass'] }}">
                                <div class="travellers gbTravellers">
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
                                            <li data-cy="1" class="selected">{{ __('labels.all') }}</li>
                                            <li data-cy="2" class="">{{ __('labels.economy') }}</li>
                                            <li data-cy="3" class="">{{ __('labels.premium_economy') }}</li>
                                            <li data-cy="4" class="">{{ __('labels.business') }}</li>
                                            <li data-cy="5" class="">{{ __('labels.premium_business') }}</li>
                                            <li data-cy="6" class="">{{ __('labels.first_class') }}</li>
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
                            <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                            <input type="hidden" name="adultsF" class="adultsF" value="{{ $input['adultsF'] }}">
                            <input type="hidden" name="childsF" class="childsF" value="{{ $input['childsF'] }}">
                            <input type="hidden" name="infants" class="infantsF" value="{{ $input['infants'] }}">
                            <input type="hidden" name="FlightCabinClass" class="FlightCabinClass" value="{{ $input['FlightCabinClass'] }}">
                            <input type="hidden" name="DirectFlight" class="DirectFlight" value="{{ $input['DirectFlight'] }}">
                            <input type="hidden" name="OneStopFlight" class="OneStopFlight" value="{{ $input['OneStopFlight'] }}">
                            <input type="hidden" name="results" class="results" value="true">
                            <div class="search_btns_listing"><button type="submit" class="btn btn-primary">{{ __('labels.search') }}</button></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<section class="product_new_arrival search_detail_info room-section" id="resultsElement">
    <div class="container">
        <div class="row">
            <!-- Detail Section  -->
            <div class="col-lg-8 col-md-7">
                <div class="row air_list_data">
                    <div class="flex_width_air col-md-12">
                        <div class="vistara_Data">
                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $fareQuoteOB['Results']['Segments'][0][0]['Airline']['AirlineCode'] }}" alt="{{ $fareQuoteOB['Results']['Segments'][0][0]['Airline']['AirlineName'] }}">
                            <span>{{ $fareQuoteOB['Results']['Segments'][0][0]['Airline']['AirlineName'] }} 
                                {{ $fareQuoteOB['Results']['Segments'][0][0]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][0][0]['Airline']['FlightNumber'] }}
                            </span>
                        </div>
                        <div class="main_flts_time">
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][0][0]['Origin']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][0][0]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][0]['Origin']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][0]['Origin']['DepTime'])) }})</div>
                            </div>
                            <div class="time_hr">{{ intdiv($fareQuoteOB['Results']['Segments'][0][0]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][0][0]['Duration'] % 60) }}m</div>
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][0][0]['Destination']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][0][0]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][0]['Destination']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][0]['Destination']['ArrTime'])) }})</div>
                            </div>
                        </div>
                        <div class="inner_flight_tabs">
                            <table class="table">
                                <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                </tr>
                                <tr>
                                    <td>Adult</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][0][0]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][0][0]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @if(!empty($fareQuoteOB['Results']['Segments'][0][1]))
                    <div class="flex_width_air col-md-12 mt-4">
                        <div class="vistara_Data">
                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $fareQuoteOB['Results']['Segments'][0][1]['Airline']['AirlineCode'] }}" alt="{{ $fareQuoteOB['Results']['Segments'][0][1]['Airline']['AirlineName'] }}">
                            <span>{{ $fareQuoteOB['Results']['Segments'][0][1]['Airline']['AirlineName'] }} 
                                {{ $fareQuoteOB['Results']['Segments'][0][1]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][0][1]['Airline']['FlightNumber'] }}
                            </span>
                        </div>
                        <div class="main_flts_time">
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][0][1]['Origin']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][0][1]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][1]['Origin']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][1]['Origin']['DepTime'])) }})</div>
                            </div>
                            <div class="time_hr">{{ intdiv($fareQuoteOB['Results']['Segments'][0][1]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][0][1]['Duration'] % 60) }}m</div>
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][0][1]['Destination']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][0][1]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][1]['Destination']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][1]['Destination']['ArrTime'])) }})</div>
                            </div>
                        </div>
                        <div class="inner_flight_tabs">
                            <table class="table">
                                <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                </tr>
                                <tr>
                                    <td>Adult</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][0][1]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][0][1]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @endif
                    @if(!empty($fareQuoteOB['Results']['Segments'][0][2]))
                    <div class="flex_width_air col-md-12 mt-4">
                        <div class="vistara_Data">
                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $fareQuoteOB['Results']['Segments'][0][2]['Airline']['AirlineCode'] }}" alt="{{ $fareQuoteOB['Results']['Segments'][0][2]['Airline']['AirlineName'] }}">
                            <span>{{ $fareQuoteOB['Results']['Segments'][0][2]['Airline']['AirlineName'] }} 
                                {{ $fareQuoteOB['Results']['Segments'][0][2]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][0][2]['Airline']['FlightNumber'] }}
                            </span>
                        </div>
                        <div class="main_flts_time">
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][0][2]['Origin']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][0][2]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][2]['Origin']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][2]['Origin']['DepTime'])) }})</div>
                            </div>
                            <div class="time_hr">{{ intdiv($fareQuoteOB['Results']['Segments'][0][2]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][0][2]['Duration'] % 60) }}m</div>
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][0][2]['Destination']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][0][2]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][2]['Destination']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][2]['Destination']['ArrTime'])) }})</div>
                            </div>
                        </div>
                        <div class="inner_flight_tabs">
                            <table class="table">
                                <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                </tr>
                                <tr>
                                    <td>Adult</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][0][2]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][0][2]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @endif
                    @if(!empty($fareQuoteOB['Results']['Segments'][1][0]))
                    <div  class="refundable_data"></div>
                    <div class="flex_width_air col-md-12 mt-4">
                        <div class="vistara_Data">
                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $fareQuoteOB['Results']['Segments'][1][0]['Airline']['AirlineCode'] }}" alt="{{ $fareQuoteOB['Results']['Segments'][1][0]['Airline']['AirlineName'] }}">
                            <span>{{ $fareQuoteOB['Results']['Segments'][1][0]['Airline']['AirlineName'] }} 
                                {{ $fareQuoteOB['Results']['Segments'][1][0]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][1][0]['Airline']['FlightNumber'] }}
                            </span>
                        </div>
                        <div class="main_flts_time">
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][1][0]['Origin']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][1][0]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][0]['Origin']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][0]['Origin']['DepTime'])) }})</div>
                            </div>
                            <div class="time_hr">{{ intdiv($fareQuoteOB['Results']['Segments'][1][0]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][1][0]['Duration'] % 60) }}m</div>
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][1][0]['Destination']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][1][0]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][0]['Destination']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][0]['Destination']['ArrTime'])) }})</div>
                            </div>
                        </div>
                        <div class="inner_flight_tabs">
                            <table class="table">
                                <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                </tr>
                                <tr>
                                    <td>Adult</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][1][0]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][1][0]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @endif
                    @if(!empty($fareQuoteOB['Results']['Segments'][1][1]))
                    <div class="flex_width_air col-md-12 mt-4">
                        <div class="vistara_Data">
                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $fareQuoteOB['Results']['Segments'][1][1]['Airline']['AirlineCode'] }}" alt="{{ $fareQuoteOB['Results']['Segments'][1][1]['Airline']['AirlineName'] }}">
                            <span>{{ $fareQuoteOB['Results']['Segments'][1][1]['Airline']['AirlineName'] }} 
                                {{ $fareQuoteOB['Results']['Segments'][1][1]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][1][1]['Airline']['FlightNumber'] }}
                            </span>
                        </div>
                        <div class="main_flts_time">
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][1][1]['Origin']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][1][1]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][1]['Origin']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][1]['Origin']['DepTime'])) }})</div>
                            </div>
                            <div class="time_hr">{{ intdiv($fareQuoteOB['Results']['Segments'][1][1]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][1][1]['Duration'] % 60) }}m</div>
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][1][1]['Destination']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][1][1]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][1]['Destination']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][1]['Destination']['ArrTime'])) }})</div>
                            </div>
                        </div>
                        <div class="inner_flight_tabs">
                            <table class="table">
                                <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                </tr>
                                <tr>
                                    <td>Adult</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][1][1]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][1][1]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @endif
                    @if(!empty($fareQuoteOB['Results']['Segments'][1][2]))
                    <div class="flex_width_air col-md-12 mt-4">
                        <div class="vistara_Data">
                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $fareQuoteOB['Results']['Segments'][1][2]['Airline']['AirlineCode'] }}" alt="{{ $fareQuoteOB['Results']['Segments'][1][2]['Airline']['AirlineName'] }}">
                            <span>{{ $fareQuoteOB['Results']['Segments'][1][2]['Airline']['AirlineName'] }} 
                                {{ $fareQuoteOB['Results']['Segments'][1][2]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][1][2]['Airline']['FlightNumber'] }}
                            </span>
                        </div>
                        <div class="main_flts_time">
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][1][2]['Origin']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][1][2]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][2]['Origin']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][2]['Origin']['DepTime'])) }})</div>
                            </div>
                            <div class="time_hr">{{ intdiv($fareQuoteOB['Results']['Segments'][1][2]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][1][2]['Duration'] % 60) }}m</div>
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteOB['Results']['Segments'][1][2]['Destination']['Airport']['AirportCode'] }} <span>{{ $fareQuoteOB['Results']['Segments'][1][2]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][2]['Destination']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][2]['Destination']['ArrTime'])) }})</div>
                            </div>
                        </div>
                        <div class="inner_flight_tabs">
                            <table class="table">
                                <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                </tr>
                                <tr>
                                    <td>Adult</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][1][2]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][1][2]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @endif
                    @if(!empty($fareQuoteIB['Results']))
                    <div class="flex_width_air col-md-12 mt-4">
                        <div class="vistara_Data">
                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $fareQuoteIB['Results']['Segments'][0][0]['Airline']['AirlineCode'] }}" alt="{{ $fareQuoteIB['Results']['Segments'][0][0]['Airline']['AirlineName'] }}">
                            <span>{{ $fareQuoteIB['Results']['Segments'][0][0]['Airline']['AirlineName'] }} 
                                {{ $fareQuoteIB['Results']['Segments'][0][0]['Airline']['AirlineCode'] }} - {{ $fareQuoteIB['Results']['Segments'][0][0]['Airline']['FlightNumber'] }}
                            </span>
                        </div>
                        <div class="main_flts_time">
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteIB['Results']['Segments'][0][0]['Origin']['Airport']['AirportCode'] }} <span>{{ $fareQuoteIB['Results']['Segments'][0][0]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][0]['Origin']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][0]['Origin']['DepTime'])) }})</div>
                            </div>
                            <div class="time_hr">{{ intdiv($fareQuoteIB['Results']['Segments'][0][0]['Duration'], 60).'h '. ($fareQuoteIB['Results']['Segments'][0][0]['Duration'] % 60) }}m</div>
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteIB['Results']['Segments'][0][0]['Destination']['Airport']['AirportCode'] }} <span>{{ $fareQuoteIB['Results']['Segments'][0][0]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][0]['Destination']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][0]['Destination']['ArrTime'])) }})</div>
                            </div>
                        </div>
                        <div class="inner_flight_tabs">
                            <table class="table">
                                <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                </tr>
                                <tr>
                                    <td>Adult</td>
                                    <td>{{ $fareQuoteIB['Results']['Segments'][0][0]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteIB['Results']['Segments'][0][0]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @endif
                    @if(!empty($fareQuoteIB['Results']['Segments'][0][1]))
                    <div class="flex_width_air col-md-12 mt-4">
                        <div class="vistara_Data">
                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $fareQuoteIB['Results']['Segments'][0][1]['Airline']['AirlineCode'] }}" alt="{{ $fareQuoteIB['Results']['Segments'][0][1]['Airline']['AirlineName'] }}">
                            <span>{{ $fareQuoteIB['Results']['Segments'][0][1]['Airline']['AirlineName'] }} 
                                {{ $fareQuoteIB['Results']['Segments'][0][1]['Airline']['AirlineCode'] }} - {{ $fareQuoteIB['Results']['Segments'][0][1]['Airline']['FlightNumber'] }}
                            </span>
                        </div>
                        <div class="main_flts_time">
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteIB['Results']['Segments'][0][1]['Origin']['Airport']['AirportCode'] }} <span>{{ $fareQuoteIB['Results']['Segments'][0][1]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][1]['Origin']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][1]['Origin']['DepTime'])) }})</div>
                            </div>
                            <div class="time_hr">{{ intdiv($fareQuoteIB['Results']['Segments'][0][1]['Duration'], 60).'h '. ($fareQuoteIB['Results']['Segments'][0][1]['Duration'] % 60) }}m</div>
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteIB['Results']['Segments'][0][1]['Destination']['Airport']['AirportCode'] }} <span>{{ $fareQuoteIB['Results']['Segments'][0][1]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][1]['Destination']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][1]['Destination']['ArrTime'])) }})</div>
                            </div>
                        </div>
                        <div class="inner_flight_tabs">
                            <table class="table">
                                <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                </tr>
                                <tr>
                                    <td>Adult</td>
                                    <td>{{ $fareQuoteIB['Results']['Segments'][0][1]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteIB['Results']['Segments'][0][1]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @endif
                    @if(!empty($fareQuoteIB['Results']['Segments'][0][2]))
                    <div class="flex_width_air col-md-12 mt-4">
                        <div class="vistara_Data">
                            <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $fareQuoteIB['Results']['Segments'][0][2]['Airline']['AirlineCode'] }}" alt="{{ $fareQuoteIB['Results']['Segments'][0][2]['Airline']['AirlineName'] }}">
                            <span>{{ $fareQuoteIB['Results']['Segments'][0][2]['Airline']['AirlineName'] }} 
                                {{ $fareQuoteIB['Results']['Segments'][0][2]['Airline']['AirlineCode'] }} - {{ $fareQuoteIB['Results']['Segments'][0][2]['Airline']['FlightNumber'] }}
                            </span>
                        </div>
                        <div class="main_flts_time">
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteIB['Results']['Segments'][0][2]['Origin']['Airport']['AirportCode'] }} <span>{{ $fareQuoteIB['Results']['Segments'][0][2]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][2]['Origin']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][2]['Origin']['DepTime'])) }})</div>
                            </div>
                            <div class="time_hr">{{ intdiv($fareQuoteIB['Results']['Segments'][0][2]['Duration'], 60).'h '. ($fareQuoteIB['Results']['Segments'][0][2]['Duration'] % 60) }}m</div>
                            <div class="fllight_time_date">
                                <h5>{{ $fareQuoteIB['Results']['Segments'][0][2]['Destination']['Airport']['AirportCode'] }} <span>{{ $fareQuoteIB['Results']['Segments'][0][2]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][2]['Destination']['Airport']['CountryName'] }}</span></h5>
                                <div class="time_flts">({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][2]['Destination']['ArrTime'])) }})</div>
                            </div>
                        </div>
                        <div class="inner_flight_tabs">
                            <table class="table">
                                <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                </tr>
                                <tr>
                                    <td>Adult</td>
                                    <td>{{ $fareQuoteIB['Results']['Segments'][0][2]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteIB['Results']['Segments'][0][2]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @endif
                </div>
                <div class="row farerule air_list_data">
                    <div class="slide-me row">
                        <div class="col-md-11">
                            <h3>View Flight Details and Policy </h3>
                        </div>
                        <div class="col-md-1">
                            <span class="text-right"><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                    <div id="flight-details-policy">
                        <?php if(isset($fareRuleOB['FareRules']['0']['FareRuleDetail'])) { ?> 
                        {!!html_entity_decode($fareRuleOB['FareRules']['0']['FareRuleDetail'])!!}
                        <?php } ?>
                        @if(!empty($fareRuleIB['FareRules']))
                        {!!html_entity_decode($fareRuleIB['FareRules']['0']['FareRuleDetail'])!!}
                        @endif
                    </div>
                </div>
                <!-- Passenger Form -->
                <?php
                // $totalobFare = $fareQuoteOB['Results']['Fare']['Tax'] + $fareQuoteOB['Results']['Fare']['ServiceFee'] + $fareQuoteOB['Results']['Fare']['OtherCharges'] + $fareQuoteOB['Results']['Fare']['AdditionalTxnFeePub'];
                // if (!empty($fareQuoteIB['Results'])) {
                //     $totalibFare = $fareQuoteIB['Results']['Fare']['Tax'] + $fareQuoteIB['Results']['Fare']['ServiceFee'] + $fareQuoteIB['Results']['Fare']['OtherCharges'] + $fareQuoteIB['Results']['Fare']['AdditionalTxnFeePub'];
                // }
                ?>

                <?php
                // $totalconversionob = $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( $commission / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] );
                // if (!empty($fareQuoteIB['Results'])) {
                //     $totalconversionib = $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( $commission / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] );
                // }

                // if ($fareQuoteOB['Results']['Fare']['Currency'] == 'INR') {
                //     $conversion_price_inr = $conversion / 100 * $totalconversionob;
                //     $ob_price_convert = $conversion / 100 * $totalconversionob;
                // } else {
                //     $conversion_price_int = $int_conversion / 100 * $totalconversionob;
                //     $ob_price_convert = $int_conversion / 100 * $totalconversionob;
                // }
                // if (!empty($fareQuoteIB['Results'])) {
                //     if ($fareQuoteIB['Results']['Fare']['Currency'] == 'INR') {
                //         $conversion_price_ib_inr = ($conversion / 100 * $totalconversionib) + $conversion_price_inr;
                //         $ib_price_convert = ($conversion / 100 * $totalconversionib) + $conversion_price_inr;
                //     } else {
                //         $conversion_price_ib_int = ($int_conversion / 100 * $totalconversionib) + $conversion_price_int;
                //         $ib_price_convert = ($int_conversion / 100 * $totalconversionib) + $conversion_price_int;
                //     }
                // }

                if($fareQuoteOB['Results']['Fare']['Currency'] == 'ILS'){

                    $totalconversionob = $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( env('INIS_VAL_PAYME') / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] );


                    if (!empty($fareQuoteIB['Results'])) {
                        $totalconversionib = $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( env('INIS_VAL_PAYME') / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] );
                    }

                    $ob_price_convert = env('PAYME_FEES') / 100 * $totalconversionob;

                    if (!empty($fareQuoteIB['Results'])) {
                        //$conversion_price_ib_inr = ($conversion / 100 * $totalconversionib) + $conversion_price_inr;
                        $ib_price_convert = env('PAYME_FEES') / 100 * $totalconversionib + $ob_price_convert;
                        
                    }

                    $flightprice = $totalconversionob + $ob_price_convert;

                    if (!empty($fareQuoteIB['Results'])) {
                        $flightprice = $totalconversionob + $totalconversionib  + $ib_price_convert;
                    }


                    if($fareQuoteOB['Results']['Fare']['Currency'] == 'ILS'){

                      if (!empty($fareQuoteIB['Results'])) {

                        $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $ib_price_convert + env('PAYME_FIX_FEES') );
                     }else{
                        $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $ob_price_convert + env('PAYME_FIX_FEES') );

                     }

                      $flightprice = $flightprice + env('PAYME_FIX_FEES') + $vat;

                    }

                     $totalobFare = $fareQuoteOB['Results']['Fare']['Tax'] + $fareQuoteOB['Results']['Fare']['ServiceFee'] + $fareQuoteOB['Results']['Fare']['OtherCharges'] + $fareQuoteOB['Results']['Fare']['AdditionalTxnFeePub'];

                     if (!empty($fareQuoteIB['Results'])) {
                        $totalibFare = $fareQuoteIB['Results']['Fare']['Tax'] + $fareQuoteIB['Results']['Fare']['ServiceFee'] + $fareQuoteIB['Results']['Fare']['OtherCharges'] + $fareQuoteIB['Results']['Fare']['AdditionalTxnFeePub'];
                    }

                }else{

                    $totalconversionob = $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( $commission / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] );


                        if (!empty($fareQuoteIB['Results'])) {
                            $totalconversionib = $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( $commission / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] );
                        }

                        $ob_price_convert = $conversion / 100 * $totalconversionob;

                        if (!empty($fareQuoteIB['Results'])) {
                                //$conversion_price_ib_inr = ($conversion / 100 * $totalconversionib) + $conversion_price_inr;
                             $ib_price_convert = $conversion / 100 * $totalconversionib + $ob_price_convert;
        
                        }

                        $flightprice = $totalconversionob + $ob_price_convert;

                        if (!empty($fareQuoteIB['Results'])) {
                                $flightprice = $totalconversionob + $totalconversionib  + $ib_price_convert;
                        }




                        $totalobFare = $fareQuoteOB['Results']['Fare']['Tax'] + $fareQuoteOB['Results']['Fare']['ServiceFee'] + $fareQuoteOB['Results']['Fare']['OtherCharges'] + $fareQuoteOB['Results']['Fare']['AdditionalTxnFeePub'];

                            if (!empty($fareQuoteIB['Results'])) {
                                $totalibFare = $fareQuoteIB['Results']['Fare']['Tax'] + $fareQuoteIB['Results']['Fare']['ServiceFee'] + $fareQuoteIB['Results']['Fare']['OtherCharges'] + $fareQuoteIB['Results']['Fare']['AdditionalTxnFeePub'];
                            }
                    }
                ?>

                <div class="raveller_informaion mrgintop_20">
                    <h3>{{ __('labels.traveller_info') }}</h3>
                    <form method="POST" class="traveller_form" id="BookRoomForm" name="BookingForm" action="{{ route('bookFlight') }}">
                        @csrf
                        <input type="hidden" name="file_name" id="file_name" value="flight">
                        <input type="hidden" name="trace_id" value="{{ $traceID }}" />
                        <input type="hidden" name="referral" value="{{ $referral }}" />
                        <input type="hidden" name="obindex" value="{{ $resultOBIndex }}" />
                        <input type="hidden" name="search_id" value="{{ $search_id }}" />
                        <input type="hidden" id="extra_baggage_meal_price" name="extra_baggage_meal_price" value="0" />
                        <?php if(isset($fareRuleOB['FareRules']['0']['DepartureTime'])) { ?> 
                        <input type="hidden" name="departure_date_arr" value="{{ $fareRuleOB['FareRules']['0']['DepartureTime'] }}" />
                        <?php } ?>
                        @if(!empty($fareRuleIB['FareRules']))
                        <input type="hidden" name="departure_date_dep" value="{{ $fareRuleIB['FareRules']['0']['DepartureTime'] }}" />
                        @endif

                        <input type="hidden" name="is_ob_lcc" value="{{ $fareQuoteOB['Results']['IsLCC'] }}" />
                        <input type="hidden" name="flight_jetname_ob" value="{{ $fareQuoteOB['Results']['Segments'][0][0]['Airline']['AirlineName'] }}" />

                        <input type="hidden" name="flight_name_ob" value="{{ $fareQuoteOB['Results']['Segments'][0][0]['Airline']['AirlineName'] }} {{ $fareQuoteOB['Results']['Segments'][0][0]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][0][0]['Airline']['FlightNumber'] }}" />
                        <input type="hidden" name="farebreakDownOB" value='<?php echo json_encode($fareQuoteOB['Results']['FareBreakdown']); ?>' />
                         <?php if($fareQuoteOB['Results']['Fare']['Currency'] == 'ILS'){ ?>

                        <input type="hidden"  name="base_price" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['BaseFare'] + (env('INIS_VAL_PAYME') / 100 * $fareQuoteOB['Results']['Fare']['BaseFare'] ) }}@else{{ $fareQuoteOB['Results']['Fare']['BaseFare'] + (env('INIS_VAL_PAYME') / 100 * $fareQuoteOB['Results']['Fare']['BaseFare'] ) +  $fareQuoteIB['Results']['Fare']['BaseFare'] + (env('INIS_VAL_PAYME') / 100 * $fareQuoteIB['Results']['Fare']['BaseFare'] )}}@endif" />

                        <input type="hidden"  name="tax_price" value="@if(empty($fareQuoteIB['Results'])){{ $totalobFare + ( env('INIS_VAL_PAYME') / 100 * $totalobFare ) }}@else{{ $totalobFare + ( env('INIS_VAL_PAYME') / 100 * $totalobFare ) +  $totalibFare + ( env('INIS_VAL_PAYME') / 100 * $totalibFare ) }}@endif" />

                        <input type="hidden"  name="amount_tbo" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['OfferedFare'] }}@else{{ $fareQuoteOB['Results']['Fare']['OfferedFare'] +  $fareQuoteIB['Results']['Fare']['OfferedFare'] }}@endif" />

                        <input type="hidden" name="amount" value="<?php echo $flightprice; ?>" />

                        <input type="hidden" name="amount_without_conversion" value="@if(empty($fareQuoteIB['Results'])){{ $totalconversionob }}@else{{ $totalconversionib }}@endif" />

                        <input type="hidden" name="amount_without_conversion_agent" value="@if(empty($fareQuoteIB['Results'])){{ $totalconversionob }}@else{{ $totalconversionib }}@endif" />

                        <?php }else{ ?>

                        <input type="hidden"  name="base_price" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['BaseFare'] + ( $commission / 100 * $fareQuoteOB['Results']['Fare']['BaseFare'] ) }}@else{{ $fareQuoteOB['Results']['Fare']['BaseFare'] + ( $commission / 100 * $fareQuoteOB['Results']['Fare']['BaseFare'] ) +  $fareQuoteIB['Results']['Fare']['BaseFare'] + ( $commission / 100 * $fareQuoteIB['Results']['Fare']['BaseFare'] )}}@endif" />

                        <input type="hidden"  name="tax_price" value="@if(empty($fareQuoteIB['Results'])){{ $totalobFare + ( $commission / 100 * $totalobFare ) }}@else{{ $totalobFare + ( $commission / 100 * $totalobFare ) +  $totalibFare + ( $commission / 100 * $totalibFare ) }}@endif" />

                        <input type="hidden"  name="amount_tbo" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['OfferedFare'] }}@else{{ $fareQuoteOB['Results']['Fare']['OfferedFare'] +  $fareQuoteIB['Results']['Fare']['OfferedFare'] }}@endif" />

                        <input type="hidden" name="amount" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( $commission / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] ) + $ob_price_convert }}@else{{ $fareQuoteOB['Results']['Fare']['OfferedFare'] +  ( $commission / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] )  + $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( $commission / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] ) + $ib_price_convert }}@endif" />

                        <input type="hidden" name="amount_without_conversion" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( $commission / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] )  }}@else{{ $fareQuoteOB['Results']['Fare']['OfferedFare'] +  ( $commission / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] ) +  $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( $commission / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] ) }}@endif" />

                        <input type="hidden" name="amount_without_conversion_agent" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( $commisioninisagent / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] )  }}@else{{ $fareQuoteOB['Results']['Fare']['OfferedFare'] +  ( $commisioninisagent / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] ) +  $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( $commisioninisagent / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] ) }}@endif" />

                        <?php } ?>

                        <input type="hidden" name="currency" value="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}" />
                        <input type="hidden" id="adultCountHidden" value="{{ $adultCount }}">
                        <input type="hidden" id="childCountHidden" value="{{ $childCount }}">
                        <input type="hidden" id="infantCountHidden" value="{{ $infantCount }}">
                        <input type="hidden" name="from_loc" value="{{ $fareQuoteOB['Results']['Segments'][0][0]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][0]['Origin']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="to_loc" value="{{ $fareQuoteOB['Results']['Segments'][0][0]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][0]['Destination']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="duration" value="{{ intdiv($fareQuoteOB['Results']['Segments'][0][0]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][0][0]['Duration'] % 60) }}m" />
                        <input type="hidden" name="dep_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][0]['Origin']['DepTime'])) }})" />
                        <input type="hidden" name="arr_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][0]['Destination']['ArrTime'])) }})" />

                        @if(!empty($fareQuoteOB['Results']['Segments'][0][1]))
                        <input type="hidden" name="stop_flight_jetname_ob" value="{{ $fareQuoteOB['Results']['Segments'][0][1]['Airline']['AirlineName'] }}" />
                        <input type="hidden" name="stop_flight_name_ob" value="{{ $fareQuoteOB['Results']['Segments'][0][1]['Airline']['AirlineName'] }} {{ $fareQuoteOB['Results']['Segments'][0][1]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][0][1]['Airline']['FlightNumber'] }}" />
                        <input type="hidden" name="stop_from_loc" value="{{ $fareQuoteOB['Results']['Segments'][0][1]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][1]['Origin']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop_to_loc" value="{{ $fareQuoteOB['Results']['Segments'][0][1]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][1]['Destination']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop_duration" value="{{ intdiv($fareQuoteOB['Results']['Segments'][0][1]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][0][1]['Duration'] % 60) }}m" />
                        <input type="hidden" name="stop_dep_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][1]['Origin']['DepTime'])) }})" />
                        <input type="hidden" name="stop_arr_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][1]['Destination']['ArrTime'])) }})" />
                        @endif

                        @if(!empty($fareQuoteOB['Results']['Segments'][0][2]))
                        <input type="hidden" name="stop2_flight_jetname_ob" value="{{ $fareQuoteOB['Results']['Segments'][0][2]['Airline']['AirlineName'] }}" />
                        <input type="hidden" name="stop2_flight_name_ob" value="{{ $fareQuoteOB['Results']['Segments'][0][2]['Airline']['AirlineName'] }} {{ $fareQuoteOB['Results']['Segments'][0][2]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][0][2]['Airline']['FlightNumber'] }}" />
                        <input type="hidden" name="stop2_from_loc" value="{{ $fareQuoteOB['Results']['Segments'][0][2]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][2]['Origin']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop2_to_loc" value="{{ $fareQuoteOB['Results']['Segments'][0][2]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][0][2]['Destination']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop2_duration" value="{{ intdiv($fareQuoteOB['Results']['Segments'][0][2]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][0][2]['Duration'] % 60) }}m" />
                        <input type="hidden" name="stop2_dep_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][2]['Origin']['DepTime'])) }})" />
                        <input type="hidden" name="stop2_arr_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][0][2]['Destination']['ArrTime'])) }})" />
                        @endif

                        @if(!empty($fareQuoteOB['Results']['Segments'][1][0]))

                        <input type="hidden" name="return_int_flight_jetname_ob" value="{{ $fareQuoteOB['Results']['Segments'][1][0]['Airline']['AirlineName'] }}" />

                        <input type="hidden" name="return_int_flight_name_ob" value="{{ $fareQuoteOB['Results']['Segments'][1][0]['Airline']['AirlineName'] }} {{ $fareQuoteOB['Results']['Segments'][1][0]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][1][0]['Airline']['FlightNumber'] }}" />
                        <input type="hidden" name="return_int_from_loc" value="{{ $fareQuoteOB['Results']['Segments'][1][0]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][0]['Origin']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="return_int_to_loc" value="{{ $fareQuoteOB['Results']['Segments'][1][0]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][0]['Destination']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="return_int_duration" value="{{ intdiv($fareQuoteOB['Results']['Segments'][1][0]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][1][0]['Duration'] % 60) }}m" />
                        <input type="hidden" name="return_int_dep_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][0]['Origin']['DepTime'])) }})" />
                        <input type="hidden" name="return_int_arr_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][0]['Destination']['ArrTime'])) }})" />
                        @endif

                        @if(!empty($fareQuoteOB['Results']['Segments'][1][1]))
                        <input type="hidden" name="stop_return_int_flight_jetname_ob" value="{{ $fareQuoteOB['Results']['Segments'][1][1]['Airline']['AirlineName'] }}" />
                        <input type="hidden" name="stop_return_int_flight_name_ob" value="{{ $fareQuoteOB['Results']['Segments'][1][1]['Airline']['AirlineName'] }} {{ $fareQuoteOB['Results']['Segments'][1][1]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][1][1]['Airline']['FlightNumber'] }}" />
                        <input type="hidden" name="stop_return_int_from_loc" value="{{ $fareQuoteOB['Results']['Segments'][1][1]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][1]['Origin']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop_return_int_to_loc" value="{{ $fareQuoteOB['Results']['Segments'][1][1]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][1]['Destination']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop_return_int_duration" value="{{ intdiv($fareQuoteOB['Results']['Segments'][1][1]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][1][1]['Duration'] % 60) }}m" />
                        <input type="hidden" name="stop_return_int_dep_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][1]['Origin']['DepTime'])) }})" />
                        <input type="hidden" name="stop_return_int_arr_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][1]['Destination']['ArrTime'])) }})" />
                        @endif

                        @if(!empty($fareQuoteOB['Results']['Segments'][1][2]))
                        <input type="hidden" name="stop2_return_int_flight_jetname_ob" value="{{ $fareQuoteOB['Results']['Segments'][1][2]['Airline']['AirlineName'] }}" />
                        <input type="hidden" name="stop2_return_int_flight_name_ob" value="{{ $fareQuoteOB['Results']['Segments'][1][2]['Airline']['AirlineName'] }} {{ $fareQuoteOB['Results']['Segments'][1][2]['Airline']['AirlineCode'] }} - {{ $fareQuoteOB['Results']['Segments'][1][2]['Airline']['FlightNumber'] }}" />
                        <input type="hidden" name="stop2_return_int_from_loc" value="{{ $fareQuoteOB['Results']['Segments'][1][2]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][2]['Origin']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop2_return_int_to_loc" value="{{ $fareQuoteOB['Results']['Segments'][1][2]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteOB['Results']['Segments'][1][2]['Destination']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop2_return_int_duration" value="{{ intdiv($fareQuoteOB['Results']['Segments'][1][2]['Duration'], 60).'h '. ($fareQuoteOB['Results']['Segments'][1][2]['Duration'] % 60) }}m" />
                        <input type="hidden" name="stop2_return_int_dep_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][2]['Origin']['DepTime'])) }})" />
                        <input type="hidden" name="stop2_return_int_arr_time" value="({{ date('H:i' , strtotime($fareQuoteOB['Results']['Segments'][1][2]['Destination']['ArrTime'])) }})" />
                        @endif

                        @if(!empty($fareQuoteIB['Results']))
                        <input type="hidden" name="ibindex" value="{{ $resultIBIndex }}" />
                        <input type="hidden" name="farebreakDownIB" value='<?php echo json_encode($fareQuoteIB['Results']['FareBreakdown']); ?>' />
                        <input type="hidden" name="is_ib_lcc" value="{{ $fareQuoteIB['Results']['IsLCC'] }}" />
                        <input type="hidden" name="flight_jetname_ib" value="{{ $fareQuoteIB['Results']['Segments'][0][0]['Airline']['AirlineName'] }}" />
                        <input type="hidden" name="flight_name_ib" value="{{ $fareQuoteIB['Results']['Segments'][0][0]['Airline']['AirlineName'] }} {{ $fareQuoteIB['Results']['Segments'][0][0]['Airline']['AirlineCode'] }} - {{ $fareQuoteIB['Results']['Segments'][0][0]['Airline']['FlightNumber'] }}" />

                        <input type="hidden" name="return_ib_from_loc" value="{{ $fareQuoteIB['Results']['Segments'][0][0]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][0]['Origin']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="return_ib_to_loc" value="{{ $fareQuoteIB['Results']['Segments'][0][0]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][0]['Destination']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="return_ib_duration" value="{{ intdiv($fareQuoteIB['Results']['Segments'][0][0]['Duration'], 60).'h '. ($fareQuoteIB['Results']['Segments'][0][0]['Duration'] % 60) }}m" />
                        <input type="hidden" name="return_ib_dep_time" value="({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][0]['Origin']['DepTime'])) }})" />
                        <input type="hidden" name="return_ib_arr_time" value="({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][0]['Destination']['ArrTime'])) }})" />

                        @endif

                        @if(!empty($fareQuoteIB['Results']['Segments'][0][1]))
                        <input type="hidden" name="stop_flight_jetname_ib" value="{{ $fareQuoteIB['Results']['Segments'][0][1]['Airline']['AirlineName'] }}" />
                        <input type="hidden" name="stop_flight_name_ib" value="{{ $fareQuoteIB['Results']['Segments'][0][1]['Airline']['AirlineName'] }} {{ $fareQuoteIB['Results']['Segments'][0][1]['Airline']['AirlineCode'] }} - {{ $fareQuoteIB['Results']['Segments'][0][1]['Airline']['FlightNumber'] }}" />

                        <input type="hidden" name="stop_return_ib_from_loc" value="{{ $fareQuoteIB['Results']['Segments'][0][1]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][1]['Origin']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop_return_ib_to_loc" value="{{ $fareQuoteIB['Results']['Segments'][0][1]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][1]['Destination']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop_return_ib_duration" value="{{ intdiv($fareQuoteIB['Results']['Segments'][0][1]['Duration'], 60).'h '. ($fareQuoteIB['Results']['Segments'][0][1]['Duration'] % 60) }}m" />
                        <input type="hidden" name="stop_return_ib_dep_time" value="({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][1]['Origin']['DepTime'])) }})" />
                        <input type="hidden" name="stop_return_ib_arr_time" value="({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][1]['Destination']['ArrTime'])) }})" />

                        @endif

                        @if(!empty($fareQuoteIB['Results']['Segments'][0][2]))
                        <input type="hidden" name="stop2_flight_jetname_ib" value="{{ $fareQuoteIB['Results']['Segments'][0][2]['Airline']['AirlineName'] }}" />
                        <input type="hidden" name="stop2_flight_name_ib" value="{{ $fareQuoteIB['Results']['Segments'][0][2]['Airline']['AirlineName'] }} {{ $fareQuoteIB['Results']['Segments'][0][2]['Airline']['AirlineCode'] }} - {{ $fareQuoteIB['Results']['Segments'][0][2]['Airline']['FlightNumber'] }}" />

                        <input type="hidden" name="stop2_return_ib_from_loc" value="{{ $fareQuoteIB['Results']['Segments'][0][2]['Origin']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][2]['Origin']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop2_return_ib_to_loc" value="{{ $fareQuoteIB['Results']['Segments'][0][2]['Destination']['Airport']['CityName'] }}, {{ $fareQuoteIB['Results']['Segments'][0][2]['Destination']['Airport']['CountryName'] }}" />
                        <input type="hidden" name="stop2_return_ib_duration" value="{{ intdiv($fareQuoteIB['Results']['Segments'][0][2]['Duration'], 60).'h '. ($fareQuoteIB['Results']['Segments'][0][2]['Duration'] % 60) }}m" />
                        <input type="hidden" name="stop2_return_ib_dep_time" value="({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][2]['Origin']['DepTime'])) }})" />
                        <input type="hidden" name="stop2_return_ib_arr_time" value="({{ date('H:i' , strtotime($fareQuoteIB['Results']['Segments'][0][2]['Destination']['ArrTime'])) }})" />

                        @endif

                        <div class="row" id="bookingForm">
                        @for($i=1; $i <= $adultCount; $i++ )
                        @if($adultCount > 1)
                        <hr>
                        @endif
                        <span class="adultNo"><b>Adult {{ $i }} </b></span>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Title</label>
                                    <select  name="adult_title_{{$i}}"  class="form-control" required data-parsley-pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$" data-parsley-trigger="keyup" data-parsley-required-message="Title is required." autocomplete="off">
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Miss">Miss</option>
                                        <option value="Ms">Ms</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.first_name') }}</label>
                                    <input type="text" name="adult_first_name_{{$i}}" id="adult_first_name_{{$i}}" class="form-control afn_{{$i}} fn_1_{{$i}}" placeholder="First Name" required value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.last_name') }}</label>
                                    <input type="text" name="adult_last_name_{{$i}}" id="adult_last_name_{{$i}}" class="form-control aln_{{$i}} ln_1_{{$i}}" placeholder="Last Name" required value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.email_id') }}</label>
                                    @if($adultCount == 1)
                                        @if(!Auth::guest())
                                         <input  id="email" value="{{ Auth::user()->email}}"  type="text" name="adult_email_{{$i}}" id="adult_email_{{$i}}" class="form-control email_1_{{$i}}" placeholder="Email Address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
                                        @else
                                         <input  id="email"   type="text" name="adult_email_{{$i}}" id="adult_email_{{$i}}" class="form-control email_1_{{$i}}" placeholder="Email Address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required value="">                                        
                                        @endif
                                    @else
                                      @if(!Auth::guest())
                                       <input  value="{{ Auth::user()->email}}" id="adult_email_{{$i}}"  type="text" name="adult_email_{{$i}}" class="form-control ad_email email_1_{{$i}}" placeholder="Email Address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
                                      @else 
                                       <input  id="adult_email_{{$i}}"  type="text" name="adult_email_{{$i}}" class="form-control ad_email email_1_{{$i}}" placeholder="Email Address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
                                      @endif
                                    @endif
                                    <div class="small_text">(Booking confirmation will be sent to this email ID)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.phone_number') }}</label>
                                    <input type="number" name="adult_phone_{{$i}}" id="adult_phone_{{$i}}" class="form-control" placeholder="Contact Info" required value="">
                                </div>
                            </div>
                        </div>
                        @if($fareQuoteOB['Results']['IsPassportRequiredAtBook'] || $fareQuoteOB['Results']['IsPassportRequiredAtTicket'] )
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.passport_no') }}</label>
                                    <input type="text" name="adult_passport_no_{{$i}}" class="form-control" placeholder="Passport No." required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.passport_expire') }}</label>
                                    <input type="text" name="adult_pass_expiry_date_{{$i}}" class="form-control pass_expiry_date" placeholder="Passport Expiry Date" required autocomplete="false">
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            @if($fareQuoteOB['Results']['Segments'][0][0]['Airline']['AirlineName'] == 'AirAsia')
                            <div class="col-md-6">
                                <label>DOB</label>
                                <input type="text" name="adult_dob_{{$i}}" class="form-control dob" required placeholder="Date of Birth" data-parsley-trigger="keyup" data-parsley-required-message="DOB is required." autocomplete="off">
                            </div>
                            @endif
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>{{ __('labels.address') }}</label>
                                <input type="text" name="address_{{$i}}" class="form-control" placeholder="Address" required value="">
                              </div>
                            </div>
                        </div>
                        @if(!empty($meal) || !empty($seat))

                        <div class="ssrResult">
                            <div class="row">
                                @if(!empty($meal))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.meal') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} ) </label>
                                        <select name="meal_{{$i}}" class="form-control">
                                            <option value="">No Preference</option>
                                            @foreach($meal as $key => $value)
                                                <option value=" {{ json_encode($value) }} ">{{ $value['Description'] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>
                                @endif 
                                @if(!empty($seat))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.seat') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} )</label>
                                        <select name="seat_{{$i}}" class="form-control">
                                            <option value="">No Preference</option>
                                            @foreach($seat as $key => $value)
                                                <option value=" {{ json_encode($value) }}  ">{{ $value['Description'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>        
                         <span class="small_text">NOTE: Meals &amp; Seats are subject to availibility</span>
                        </div><br >
                        @endif

                        @if(!empty($mealLCC) && !empty($baggage))
                        <div class="ssrResult">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.baggage') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} )</label>
                                        <select name="baggage_{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0" value="">No Excess/Extra Baggage</option>
                                            @foreach($baggage as $key => $value)
                                                <option data-meal-price="0" data-currency="{{ $value['Currency'] }}" data-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }}  ">{{ $value['Weight'] }}-Kg {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.meal') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} )</label>
                                        <select name="meallcc_{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0"  value="">Add No Meal</option>
                                            @foreach($mealLCC as $key => $value)
                                                <option  data-price="0" data-currency="{{ $value['Currency'] }}" data-meal-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }} ">Add {{ $value['AirlineDescription'] }} {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div> 
                            </div>        
                        </div><br >
                        @endif

                        @if(!empty($mealLCCreturn) && !empty($baggagereturn))
                        <div class="ssrResult">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.baggage') }} - ( {{ $input['destination'] }} - {{ $input['origin'] }} )</label>
                                        <select name="baggage_return{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0" value="">No Excess/Extra Baggage</option>
                                            @foreach($baggagereturn as $key => $value)
                                                <option data-meal-price="0" data-currency="{{ $value['Currency'] }}" data-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }}  ">{{ $value['Weight'] }}-Kg {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.meal') }} - ( {{ $input['destination'] }} - {{ $input['origin'] }} )</label>
                                        <select name="meallcc_return{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0" value="">Add No Meal</option>
                                            @foreach($mealLCCreturn as $key => $value)
                                                <option data-price="0" data-currency="{{ $value['Currency'] }}" data-meal-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }} ">Add {{ $value['AirlineDescription'] }} {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div> 
                            </div>        
                        </div><br >
                        @endif

                        @if(!empty($mealreturnib) && !empty($baggagereturnib))
                        <div class="ssrResult">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.baggage') }} - ( {{ $input['destination'] }} - {{ $input['origin'] }} )</label>
                                        <select name="baggage_return_ib{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteIB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0" value="">No Excess/Extra Baggage</option>
                                            @foreach($baggagereturnib as $key => $value)
                                                <option data-meal-price="0" data-currency="{{ $value['Currency'] }}" data-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }}  ">{{ $value['Weight'] }}-Kg {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.meal') }} - ( {{ $input['destination'] }} - {{ $input['origin'] }} )</label>
                                        <select name="meallcc_return_ib{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteIB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0" value="">Add No Meal</option>
                                            @foreach($mealreturnib as $key => $value)
                                                <option data-price="0" data-currency="{{ $value['Currency'] }}" data-meal-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }} ">Add {{ $value['AirlineDescription'] }} {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div> 
                            </div>        
                        </div><br >
                        @endif

                        @endfor
                        @for($i=1; $i <= $childCount; $i++ )
                        <hr>
                        <span class="adultNo"><b>Child {{ $i }} </b></span>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.title') }}</label>
                                    <select  name="child_title_{{$i}}"  class="form-control" required data-parsley-pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$" data-parsley-trigger="keyup" data-parsley-required-message="Title is required." autocomplete="off">
                                        <option value="Mr">Mr</option>
                                        <option value="Miss">Miss</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.first_name') }}</label>
                                    <input type="text" name="child_first_name_{{$i}}" class="form-control cfn_{{$i}}" placeholder="First Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.last_name') }}</label>
                                    <input type="text" name="child_last_name_{{$i}}" class="form-control cln_{{$i}}" placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>DOB</label>
                                    <input type="text" name="child_dob_{{$i}}" class="form-control dob" required placeholder="Date of Birth" data-parsley-trigger="keyup" data-parsley-required-message="DOB is required." autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row"> -->
                        <!-- </div> -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.email_id') }}</label>
                                    <input type="text" name="child_email_{{$i}}" class="form-control" placeholder="Email Address" required>
                                    <div class="small_text">(Booking confirmation will be sent to this email ID)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.phone_number') }}</label>
                                    <input type="number" name="child_phone_{{$i}}" class="form-control" placeholder="Contact Info" required>
                                </div>
                            </div>
                        </div>
                        @if($fareQuoteOB['Results']['IsPassportRequiredAtBook'] || $fareQuoteOB['Results']['IsPassportRequiredAtTicket'] )
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.passport_no') }}</label>
                                    <input type="text" name="child_passport_no_{{$i}}" class="form-control" placeholder="Passport No." required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.passport_expire') }}</label>
                                    <input type="text" name="child_pass_expiry_date_{{$i}}" class="form-control pass_expiry_date" placeholder="Passport No." required>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(!empty($meal) || !empty($seat))
                        
                        <div class="ssrResult">
                            <div class="row">
                                @if(!empty($meal))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.meal') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} )</label>
                                        <select name="child_meal_{{$i}}" class="form-control">
                                            <option value="">No Preference</option>
                                            @foreach($meal as $key => $value)
                                                <option value=" {{ json_encode($value) }} ">{{ $value['Description'] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>
                                @endif
                                @if(!empty($seat)) 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.seat') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} )</label>
                                        <select name="child_seat_{{$i}}" class="form-control">
                                            <option value="">No Preference</option>
                                            @foreach($seat as $key => $value)
                                                <option value=" {{ json_encode($value) }}  ">{{ $value['Description'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <span class="small_text">NOTE: Meals &amp; Seats are subject to availibility</span>        
                        </div><br >

                        @endif

                         @if(!empty($mealLCC) && !empty($baggage))
                        <div class="ssrResult">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.baggage') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} )</label>
                                        <select name="child_baggage_{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0" value="">No Excess/Extra Baggage</option>
                                            @foreach($baggage as $key => $value)
                                                <option data-meal-price="0" data-currency="{{ $value['Currency'] }}" data-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }}  ">{{ $value['Weight'] }}-Kg {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.meal') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} )</label>
                                        <select name="child_meallcc_{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0"  value="">Add No Meal</option>
                                            @foreach($mealLCC as $key => $value)
                                                <option  data-price="0" data-currency="{{ $value['Currency'] }}" data-meal-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }} ">Add {{ $value['AirlineDescription'] }} {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div> 
                            </div>        
                        </div><br >
                        @endif
                        
                        @if(!empty($mealLCCreturn) && !empty($baggagereturn))
                        <div class="ssrResult">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.baggage') }} - ( {{ $input['destination'] }} - {{ $input['origin'] }} )</label>
                                        <select name="child_baggage_return{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0" value="">No Excess/Extra Baggage</option>
                                            @foreach($baggagereturn as $key => $value)
                                                <option data-meal-price="0" data-currency="{{ $value['Currency'] }}" data-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }}  ">{{ $value['Weight'] }}-Kg {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.meal') }} - ( {{ $input['destination'] }} - {{ $input['origin'] }} )</label>
                                        <select name="child_meallcc_return{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0"  value="">Add No Meal</option>
                                            @foreach($mealLCCreturn as $key => $value)
                                                <option  data-price="0" data-currency="{{ $value['Currency'] }}" data-meal-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }} ">Add {{ $value['AirlineDescription'] }} {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div> 
                            </div>        
                        </div><br >
                        @endif

                        @if(!empty($mealreturnib) && !empty($baggagereturnib))
                        <div class="ssrResult">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.baggage') }} - ( {{ $input['destination'] }} - {{ $input['origin'] }} )</label>
                                        <select name="child_baggage_return_ib{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteIB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0" value="">No Excess/Extra Baggage</option>
                                            @foreach($baggagereturnib as $key => $value)
                                                <option data-meal-price="0" data-currency="{{ $value['Currency'] }}" data-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }}  ">{{ $value['Weight'] }}-Kg {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.meal') }} - ( {{ $input['destination'] }} - {{ $input['origin'] }} )</label>
                                        <select name="child_meallcc_return_ib{{$i}}" class="form-control baggageDropDown">
                                            <option data-currency="{{ $fareQuoteIB['Results']['Fare']['Currency'] }}" data-meal-price="0" data-price="0" value="">Add No Meal</option>
                                            @foreach($mealreturnib as $key => $value)
                                                <option data-price="0" data-currency="{{ $value['Currency'] }}" data-meal-price="{{ $value['Price'] }}" value=" {{ json_encode($value) }} ">Add {{ $value['AirlineDescription'] }} {{ $value['Currency'] }} {{ $value['Price'] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div> 
                            </div>        
                        </div><br >
                        @endif

                        @endfor
                        @for($i=1; $i <= $infantCount; $i++ )
                        <hr>
                        <span class="adultNo"><b>Infant {{ $i }} </b></span>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.title') }}</label>
                                    <select  name="infant_title_{{$i}}"  class="form-control" required data-parsley-pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$" data-parsley-trigger="keyup" data-parsley-required-message="Title is required." autocomplete="off">
                                        <option value="Mr">Mr</option>
                                        <option value="Miss">Miss</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.first_name') }}</label>
                                    <input type="text" name="infant_first_name_{{$i}}" class="form-control ifn_{{$i}}" placeholder="First Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.last_name') }}</label>
                                    <input type="text" name="infant_last_name_{{$i}}" class="form-control iln_{{$i}}" placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>DOB</label>
                                    <input type="text" name="infant_dob_{{$i}}" class="form-control dob" required placeholder="Date of Birth" data-parsley-trigger="keyup" data-parsley-required-message="DOB is required." autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">                                     
                                    <label>{{ __('labels.email_id') }}</label>
                                    @if(!Auth::guest())
                                     <input value="{{ Auth::user()->email}}" type="text" name="infant_email_{{$i}}" class="form-control" placeholder="Email Address" required>
                                    @else
                                      <input type="text" name="infant_email_{{$i}}" class="form-control" placeholder="Email Address" required>                                  
                                    @endif
                                    <div class="small_text">(Booking confirmation will be sent to this email ID)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('labels.phone_number') }}</label>
                                    <input type="number" name="infant_phone_{{$i}}" class="form-control" placeholder="Contact Info" required>
                                </div>
                            </div>
                        </div>

                        @if(!empty($meal) && !empty($seat))
                        <div class="ssrResult">
                            <div class="row">
                                @if(!empty($meal))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.meal') }}</label>
                                        <select name="infant_meal_{{$i}}" class="form-control">
                                            <option value="">No Preference</option>
                                            @foreach($meal as $key => $value)
                                                <option value=" {{ json_encode($value) }} ">{{ $value['Description'] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div> 
                                @endif
                                @if(!empty($seat))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('labels.seat') }}</label>
                                        <select name="infant_seat_{{$i}}" class="form-control">
                                            <option value="">No Preference</option>
                                            @foreach($seat as $key => $value)
                                                <option value=" {{ json_encode($value) }}  ">{{ $value['Description'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>        
                        </div><br >
                        @endif
                        @endfor
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_inner_data">
                                    <div class="form-group term_conditions">
                                        <label class="check_container">I agree to the <a href="#" data-toggle="modal" data-target="#policyModel" class="agreement-link">Flight booking Policy</a>, <a href="/refund-policy" target="_blank" class="agreement-link">Flight Cancellation policy</a>,<a href="/privacy-policy" target="_blank" class="agreement-link">Privacy Policy</a>,User Agreement & <a href="/terms-conditions" target="_blank" class="agreement-link">Terms of Services.</a>
                                            <input type="checkbox" checked="checked" name="t&c" disabled required>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">

                                <input type="hidden" id="fullAmount"  value="{{ round($flightprice,2)}}" >

                                <input type="hidden" name="fullAmount_Install" id="fullAmount_Install"  value="{{ round($flightprice,2)}}" >

                                <input type="hidden" name="ORIGINAL_BOOKING_PRICE_PME" id="ORIGINAL_BOOKING_PRICE_PME" value="{{ round($flightprice,2)  }}">

                                <input type="hidden" name="installment_price" id="installment_price" value="0">
                                <input type="hidden" name="installments_number" id="installments_number" value="1">

                                @if($fareQuoteOB['Results']['Fare']['Currency'] == 'ILS')
                                <input type="button" class="btn more_details show-ils-pay" value="{{ __('labels.pay_now')}}"  id="submit-btn">

                                @else
                                <input type="Submit" class="btn more_details" value="Pay Now"  onClick="stripePayFlight(event);" id="submit-btn">
                                @endif
                                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                                <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
                                <input type="hidden" name="walletPay" id="walletPay" value="no" >
                                <input type="hidden" name="walletDebit" id="walletDebit" value="0" >
                            </div>

                            <div id="loader">
                                <img alt="loader" src="https://phppot.com/demo/stripe-payment-gateway-integration-using-php/LoaderIcon.gif">
                            </div>

                        </div> 
                        <div id="error-message"></div>
                    </div>
                    </form>
                </div><br />
                <!-- Form Ends  -->

                <!-- ILS payment details -->
               <div class="row" id="ils-pay">
                <div class="raveller_informaion mrgintop_20 cabInfo">
                  <h4 style="margin:25px auto;padding-bottom:15px;border-bottom: 1px solid #ccc;">{{ __('labels.pay_mode')}}</h4>
                  <nav class="nav nav-pills flex-column flex-sm-row" role="tablist">
                      <a class="flex-sm-fill text-sm-center nav-link active" id="singleCardTab" data-toggle="tab" href="#single-payment" onclick="$('#paymentMode').val('single');">{{ __('labels.single_card')}}</a>
                      <!-- <a class="flex-sm-fill text-sm-center nav-link" id="multiCardTab" data-toggle="tab" href="#multiple-payment" onclick="$('#paymentMode').val('multiple');">{{ __('labels.single_card')}}</a> -->
                  </nav>
                  <div class="tab-content" id="pills-tabContent">
                      <div class="row">

                        <div class="row">
                          <div class="col-xs-6 col-md-6">
                            <div class="form-group" id="installments-group">
                              <label for="installments-container" class="control-label">Installments</label>
                              <select id="installments_val" name="installments" class="form-control">
                                  <option value="1" selected="selected">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-xs-4 col-md-6">
                            <div class="form-group" id="installments-group">
                              <label for="installments-container" class="control-label">Periodical Payments</label>
                                <span class="period_payment" style="font-size: 16px;color: #000;">{{ $fareQuoteOB['Results']['Fare']['Currency'] }} {{ round($flightprice,2)}}</span>
                            </div>
                          </div>
                         <div class="col-xs-4 col-md-6">
                            <div class="form-group" id="installments-group">
                              <label for="installments-container" class="control-label">Amount of interest</label>
                                <span class="amount_interest" style="font-size: 16px;color: #000;">{{ $fareQuoteOB['Results']['Fare']['Currency'] }} 0</span>
                            </div>
                          </div>
                        
                        </div>
                        <div class="row">
                          <div class="col-xs-4 col-md-6">
                            <div class="form-group" id="installments-group">
                              <label for="installments-container" class="control-label">Total Payment</label>
                                <span class="total_payment" style="font-size: 16px;color: #000;">{{ $fareQuoteOB['Results']['Fare']['Currency'] }} {{ round($flightprice,2)}}</span>
                            </div>
                          </div>
                         <div class="col-xs-4 col-md-6">
                            
                          </div>
                        
                        </div>

                        <button type="button" id="submit-payme-api" class="btn btn-primary btn-open-pay-form">
                            Pay {{ number_format ( $flightprice, 2)  }} ILS
                        </button>
                    </div>
                      <div style="padding:20px;" class="tab-pane fade" id="multiple-payment" role="tabpanel" aria-labelledby="pills-profile-tab">
                          <div class="row">
                              <div class="col-lg-12">
                                  <table class="table">
                                      <tr>
                                          <td >
                                              {{ __('labels.total_payble')}}:
                                          </td>
                                          <td align="right">
                                              <div class="final_price">
                                                  {{$fareQuoteOB['Results']['Fare']['Currency']}} 
                                                  {{ number_format ( $flightprice, 2)  }}
                                              </div>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td >
                                              {{ __('labels.total_paid')}}:
                                          </td>
                                          <td align="right">
                                              <div class="total_paid" style="color:green;">
                                                  {{$fareQuoteOB['Results']['Fare']['Currency']}} 
                                                  <span class="paidAmount">0</span>
                                              </div>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td >
                                              {{ __('labels.total_due')}}:
                                          </td>
                                          <td align="right">
                                              <div class="due_amount" style="color:red;">
                                                  {{$fareQuoteOB['Results']['Fare']['Currency']}} 
                                                  <span class="dueAmount">{{ number_format ( $flightprice, 2)  }}</span>
                                              </div>
                                          </td>
                                      </tr>
                                  </table>
                              </div>
                              <div class=" offset-md-6 col-lg-6">
                                  <div class="form-group  text-right">
                                      <label for="partAmount" >{{ __('labels.pay_amount')}}</label>
                                      <input type="text"  id="partAmount" name="partAmount" style="font-weight: bold;text-align: right;font-size: 20px;" class="form-control" value="0" >
                                  </div>
                              </div>
                              <div class="col-lg-12 text-right">
                                  <button  class="btn btn-primary multiCardPay" type="button" id="multiCardPay">{{ __('labels.pay_now')}}</button>                                              
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
               </div>

            </div>

            <!-- Price Section  -->
            <div class="col-lg-4 col-md-5">
                <div class="right_detail_data">
                    
                    
                    <div class="row">
                        <?php
                        if (!Auth::guest()) {

                            $walletBal =  \Auth::user()->balance;
                            if ($walletBal > 1) { ?>
                                <div class="col-12">
                                    <div class="form-check" style="margin:20px;">
                                        <input class="form-check-input" name="walletAmount" value="{{ wallet_blance()['amount'] }}" type="checkbox"  id="walletAmount">
                                        <label class="form-check-label" for="defaultCheck1">
                                            Pay with Wallet ( {{ wallet_blance()['currency'] }} {{ number_format(wallet_blance()['amount'],2) }} )
                                        </label>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <div class="col-12">
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
                                              <div>This offer will expire in</div>
                                              <strong id="sessionExpiryTimer"></strong>
                                           </div>
                                        </div>
                                     </div>
                                  </div>
                               </div>
                            </div>
                            <hr> 
                            <div class="basic_price_data basic_meal_charges" style="display: none;">
                              <ul class="list-inline">
                                <li>
                                  <div class="base_text">Meal Charges <span class="mealPrices"></span> </div>
                                </li>
                              </ul>
                            </div> 
                            <div class="basic_price_data basic_baggage_charges" style="display: none;">
                              <ul class="list-inline">
                                <li>
                                  <div class="base_text">Baggage Charges <span class="baggagePrices"></span> </div>
                                </li>
                              </ul>
                            </div>     
                            <div class="total_payouts">
                                <div class="text_payable">{{ __('labels.total_payble') }}</div>
                                <div class="final_price">
                                    {{ $fareQuoteOB['Results']['Fare']['Currency'] }} 
                                    @if(empty($fareQuoteIB['Results'])) 
                                     {{ number_format( $flightprice , 2) }} 
                                    @else
                                     {{ number_format( $flightprice , 2) }} 
                                    @endif
                                </div>
                            </div>   
                        </div>
                    </div>
                    <div class="pay_btns" id="pay_btns" style="top: 0px;"><a href="javascript:void(0);" class="btn btn_pay_now" onClick="goTo('submit-btn');">Pay Now</a></div>
                    <input type="hidden" name="RAZOR_KEY_ID" id="RAZOR_KEY_ID" value="{{ env('RAZOR_KEY_ID') }}">
                    <input type="hidden" name="userName" id="userName" value="">
                    <input type="hidden" name="userEmail" id="userEmail" value="">
                    <input type="hidden" name="useraddress" id="useraddress" value="">
                    <input type="hidden" name="CURRENCY_VAL" id="CURRENCY_VAL" value="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}">
                    <input type="hidden" name="BOOKING_NAME" id="BOOKING_NAME" value="{{$input['from']}} - {{$input['to']}}">
                    <input type="hidden" name="BOOKING_DESC" id="BOOKING_DESC" value="{{$input['departDate']}} - {{$input['returnDate']}}">

                    <input type="hidden" name="BOOKING_PRICE" id="BOOKING_PRICE" value="{{ round( $flightprice,2)  }}">

                    <input type="hidden" name="BOOKING_PRICE_EXTRA" id="BOOKING_PRICE_EXTRA" value="{{ round( $flightprice ,2)  }}">

                </div>
            </div>

        </div>
    </div>
</section>
<div id="bookingInProgress" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: transparent;border:none;">
            <div class="modal-body text-center">
                <img src="{{ asset('/images/flight-loader.gif') }}"   class="img-responsive" />
                <h2 style="color:#fff;"><strong>Please wait</strong></h2>
                <h3 style="color:#fff;">While we process your booking.</h3>        
            </div>
        </div>
    </div>
</div>

<div id="sessionWarningModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header refresh-header">
                <h3 class="refresh-header text-center">Session Expired</h3>
            </div>
            <div class="modal-body">
                <p>Flight prices change frequently due to availability and demand. We want to make sure you always see the most up-to-date price. Please refresh your search to see the latest price.</p>        
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-primary refresh-btn show-flight-search">Refresh Search</a>
            </div>
        </div>
    </div>
</div>

@endsection 