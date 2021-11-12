@extends('layouts.app-header')
@section('content')   
<div ng-app="flightApp" ng-controller="searchFlightCtrl" ng-init="searchFlights()">
<section class="listing_banner_forms">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
          <form action="{{ route('search_flights') }}" id="searchFlightsFormRound"  method="GET">
            @csrf
            <div class="listing_inner_forms">
               <div class="rounding_form_info">
                  <input type="hidden" id="JourneyType" ng-model="searchData.JourneyType" name="JourneyType" value="{{ $input['JourneyType'] }}">
                  <div class="form-group">
                     <label>From</label>
                     <select name="origin" class="depart-from">
                        <option value="{{ $input['origin'] }}">{{ $input['from'] }}</option>
                     </select>
                     <input type="hidden" ng-model="searchData.from" name="from" id="from-city" value="{{ $input['from'] }}">
                  </div>
                  <div class="form-group">
                     <label>To</label>
                     <select name="destination" class="depart-to">
                        <option value="{{ $input['destination'] }}">{{ $input['to'] }}</option>
                     </select>
                     <input type="hidden" ng-model="searchData.from" name="to" id="to-city" value="{{ $input['to'] }}">
                  </div>
                  <div class="form-group">
                     <label>Departure <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                     <div class="input-group" >
                        <input class="form-control departdate" ng-model="searchData.departDate" type="text" name="departDate" required readonly value="{{ $input['departDate'] }}"/>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                     </div>
                  </div>
                  <div class="form-group @if($input['JourneyType'] == '1') not-allowed @endif">
                     <label>Return <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                     <div class="input-group" >
                        <input class="form-control returndate" ng-model="searchData.returnDate" type="text" name="returnDate" required readonly value="{{ $input['returnDate'] }}"/>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Traveller &amp; Class</label>
                     <input type="text" name="travellersClass" id="travellersClass" ng-model="searchData.travellersClass" returnDateid="travellersClass" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="{{ $input['travellersClass'] }}">
                      <div class="travellers gbTravellers">
                         <div class="appendBottom20">
                            <p data-cy="adultRange" class="latoBold font12 grayText appendBottom10">ADULTS (12y +)</p>
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
                                  <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">CHILDREN (2y - 12y )</p>
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
                                  <p data-cy="infantRange" class="latoBold font12 grayText appendBottom10">INFANTS (below 2y)</p>
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
                            <p data-cy="chooseTravelClass" class="latoBold font12 grayText appendBottom10">CHOOSE TRAVEL CLASS</p>
                            <ul class="guestCounter classSelect font12 darkText tcF">
                               <li data-cy="1" class="selected">All</li>
                               <li data-cy="2" class="">Economy</li>
                               <li data-cy="3" class="">Premium Economy</li>
                               <li data-cy="4" class="">Business</li>
                               <li data-cy="5" class="">Premium Business</li>
                               <li data-cy="6" class="">First Class</li>
                            </ul>
                            <div class="makeFlex appendBottom25">
                               <div class="makeFlex column childCounter">
                                  <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">DIRECT FLIGHT</p>
                                  <ul class="guestCounter font12 darkText gbCounter df">
                                     <li data-cy="false" class="selected">No</li>
                                     <li data-cy="true" class="">Yes</li>
                                  </ul>
                               </div>
                               <div class="makeFlex column pushRight infantCounter">
                                  <p data-cy="infantRange" class="latoBold font12 grayText appendBottom10">ONE STOP FLIGHT</p>
                                  <ul class="guestCounter font12 darkText gbCounter osf">
                                     <li data-cy="false" class="selected">No</li>
                                     <li data-cy="true" class="">Yes</li>
                                  </ul> 
                               </div>
                               <p></p>
                               <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">APPLY</button>
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
                  <input type="hidden" name="adultsF" class="adultsF"  ng-model="searchData.adultsF" value="{{ $input['adultsF'] }}">
                  <input type="hidden" name="childsF" class="childsF"  ng-model="searchData.childsF" value="{{ $input['childsF'] }}">
                  <input type="hidden" name="infants" class="infantsF"  ng-model="searchData.infantsF" value="{{ $input['infants'] }}">
                  <input type="hidden" name="FlightCabinClass" ng-model="searchData.FlightCabinClass" class="FlightCabinClass" value="{{ $input['FlightCabinClass'] }}">
                  <input type="hidden" name="DirectFlight" ng-model="searchData.DirectFlight" class="DirectFlight" value="{{ $input['DirectFlight'] }}">
                  <input type="hidden" name="OneStopFlight" ng-model="searchData.OneStopFlight" class="OneStopFlight" value="{{ $input['OneStopFlight'] }}">
                  <div class="search_btns_listing"><button type="submit" class="btn btn-primary">Search</button></div>
               </div>
            </div>
          </form>
         </div>
      </div>
   </div>
</section>
<section class="flight_listing_section data-section">
   <div class="container">
   <div class="row">
      <!-- <div class="col-md-4"></div> -->
      
      <!-- Not Loaded Section -->
            <div class="col-md-3" ng-if="!loaded">
               <div class="listing_sidebar">
                  <h3>Select Filters</h3>
                  <div class="popularity_filters_items">
                     <h4>Departure</h4>
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
                     <h4>Stops</h4>
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
                     <h4>PREFERRED AIRLINES</h4>
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
           <div class="row air_list_data">
              <div class="col-md-5 twoway">
                 <div class="f-list-new">
                    <div class="row align-items-end">
                       <div class="col-md-11">
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
                                   <h3 class="currency">INR</h3>
                                   <h3 class="arrivePrice"> 3,972.77 </h3>
                                   <span class="arriveSeats"> 1 </span> <span>seat(s) left</span>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
              <div class="col-md-5 twoway">
                 <div class="f-list-new">
                    <div class="row align-items-end">
                       <div class="col-md-11">
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
                                   <h3 class="returnCurrency">INR</h3>
                                   <h3 class="returnPrice"> 3,972.77 </h3>
                                   <span class="returnSeats"> 1 </span> <span>seat(s) left </span>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
              <div class="col-md-2 twoway">
                 <div class="price_flight_datas total_price">
                    <h3 class="currency_val"></h3>
                    <h3 class="price_total">INR 3,972.77 </h3>
                 </div>
                 <input type="hidden" class="obindexval" value="" />
                 <input type="hidden" class="ibindexval" value="" />
                 <input type="hidden" class="traceIdval" value="" />
                 <a href="#" target="_blank"  class="btn btn_book book_return_url">Book</a> 
                 <!-- <button class="btn btn_search">Book</button> -->
              </div>
           </div>
        </div>
   </div>
   <div class="row" ng-if="loaded">
      <div class="col-md-3">
         <div class="filter_data" >
            <div class="filter_heading">
               <h3>Filter</h3>
               <a href="javascript:void(0)" class="reste_all_btn">Reset All</a>
            </div>
            <div class="departure_data">
               <h4>Departure</h4>
               <div class="departure_time_info">
                  <label class="current_time_det">
                  <input type="checkbox" value="morning" name="morning" id="morning_dept" class="form-control time_oneway">
                  <span>4am - 11am</span>                    
                  </label>
                  <label class="current_time_det">
                  <input type="checkbox" value="afternoon" name="afternoon" id="afternoon_dept" class="form-control time_oneway">
                  <span>11am - 4pm</span>                    
                  </label>
                  <label class="current_time_det">
                  <input type="checkbox" value="evening" name="evening" id="evening_dept" class="form-control time_oneway">
                  <span>4pm - 9pm</span>                    
                  </label>
                  <label class="current_time_det">
                  <input type="checkbox" value="night" name="night" id="night_dept" class="form-control time_oneway">
                  <span>9pm - 4am</span>                    
                  </label>
               </div>
            </div>
              <div class="departure_data" ng-if="flights.ResponseStatus && flights.Results[1]">
                 <h4>Return</h4>
                 <div class="departure_time_info">
                    <label class="current_time_det">
                    <input type="checkbox" value="morning" name="morning_return" class="form-control time_return">
                    <span>4am - 11am</span>                    
                    </label>
                    <label class="current_time_det">
                    <input type="checkbox" value="afternoon" name="afternoon_return" class="form-control time_return">
                    <span>11am - 4pm</span>                    
                    </label>
                    <label class="current_time_det">
                    <input type="checkbox" value="evening" name="evening_return" class="form-control time_return">
                    <span>4pm - 9pm</span>                    
                    </label>
                    <label class="current_time_det">
                    <input type="checkbox" value="night" name="night_return" class="form-control time_return">
                    <span>9pm - 4am</span>                    
                    </label>
                 </div>
              </div>
            <!-- <div class="departure_data">
               <h4>Stops</h4>
               <div class="departure_time_info">
                  <label class="current_time_det">
                  <input type="checkbox" value="on_dep5" class="form-control">
                  <span><i class="stop_data">0</i>Stop</span>                    
                  </label>
                  <label class="current_time_det">
                  <input type="checkbox" value="on_dep6" class="form-control">
                  <span><i class="stop_data">1</i>Stop</span>                    
                  </label>
               </div>
            </div> -->
            <div class="departure_data">
               <div class="filter_heading">
                  <h4>Preferred Airlines</h4>
                  <a href="javascript:void(0);" class="reste_all_btn">Reset</a>
               </div>
               <div class="airline_filters">
                  <div class="inner_check_air_data" ng-repeat="prefered in prefferedflights">
                     <label class="container_airline">  
                     <span class="airline_name">
                     <img src="/images/@{{ prefered }}.gif" alt="@{{ prefered }}" >
                     <span class="air_name_set">@{{ prefered }}</span>
                     </span>                    
                     <input type="checkbox" class="air-line-type" value="@{{ prefered }}" checked='checked'>
                     <span class="checkmark"></span>
                     </label>
                  </div>   
               </div>
            </div>
         </div>
      </div>
      <!-- LIsting Html -->
       <div ng-class="flights.Results[1].length > 0  ? 'col-md-4  oneway' : 'col-md-9  oneway'">
              <div class="air_list_data f-list" ng-repeat="flight in flights.Results[0]" data-price="@{{flight.Fare.OfferedFare}}" data-depart="@{{flight.time}}" data-air="@{{flight.Segments[0][0].Airline.AirlineName}}">
                  <div class="row align-items-end">

                      <div ng-class="flights.Results[1].length > 0  ? 'col-md-11' : 'col-md-9'">

                        <div class="flex_width_air">
                           <div class="vistara_Data">
                              <img src="/images/@{{ flight.Segments[0][0].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[0][0].Airline.AirlineName }}" />
                              <span>@{{flight.Segments[0][0].Airline.AirlineName}} 
                              @{{flight.Segments[0][0].Airline.AirlineCode}} @{{flight.Segments[0][0].Airline.FlightNumber}}
                              </span>
                           </div>
                           <div class="main_flts_time">
                              <div class="fllight_time_date">
                                 <h5>@{{flight.Segments[0][0].Origin.Airport.AirportCode }} <span>@{{flight.Segments[0][0].Origin.Airport.CityName }}, @{{flight.Segments[0][0].Origin.Airport.CountryName }}</span></h5>
                                 <div class="time_flts">(@{{flight.Segments[0][0].Origin.DepTime |  date:'HH:mm' }})</div>
                              </div>
                              <!-- <div class="time_hr">@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}</div> -->
                              <div class="flight_duration">
                                    <span>@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                              </div>
                              <div class="fllight_time_date">
                                 <h5>@{{flight.Segments[0][0].Destination.Airport.AirportCode }} <span>@{{flight.Segments[0][0].Destination.Airport.CityName }}, @{{flight.Segments[0][0].Destination.Airport.CountryName }}</span></h5>
                                 <div class="time_flts">(@{{flight.Segments[0][0].Destination.ArrTime |  date:'HH:mm' }})</div>
                              </div>
                              <div class="price_flight_datas">
                                 <h3>@{{flight.Fare.Currency}} @{{flight.Fare.OfferedFare}} 
                                    <span>@{{flight.Segments[0][0].NoOfSeatAvailable }} seat(s) left </span>
                                 </h3>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div ng-class="flights.Results[1].length > 0  ? 'col-md-1' : 'col-md-3'">
                           <div class="book_data_btn">

                              <a href="/flight/@{{ flights.TraceId }}/@{{ flight.ResultIndex }}/0" target="_blank"  class="btn btn_book" ng-if="!flights.Results[1].length" >Book</a> 
  
                              <input ng-if="flights.Results[1].length > 0" type="radio" name="book" data-flightcode="@{{ flight.Segments[0][0].Airline.AirlineName }} @{{ flight.Segments[0][0].Airline.AirlineCode }} - @{{ flight.Segments[0][0].Airline.FlightNumber }}" data-from-time="(@{{flight.Segments[0][0].Origin.DepTime |  date:'HH:mm' }})" data-to-time="(@{{flight.Segments[0][0].Destination.ArrTime |  date:'HH:mm' }})"  data-duration="@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}" data-price="@{{flight.Fare.OfferedFare}}" data-currency="@{{flight.Fare.Currency}}"  data-seats-left="@{{flight.Segments[0][0].NoOfSeatAvailable }}" data-air-img="@{{ flight.Segments[0][0].Airline.AirlineName }}" data-trace-Id="@{{ flights.TraceId }}" data-result-index="@{{ flight.ResultIndex }}" class="form-control" id="book_id_@{{$index}}" value="">

                           </div>
                      </div>
                  </div>

                    <!-- Domestic One Way With Stop Div  -->
                     <div class="row mt-4" ng-if="flight.Segments[0][1]"> 
                        <div class="col-md-2">
                           <div class="plane_data">
                              <img src="/images/@{{ flight.Segments[0][1].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[0][1].Airline.AirlineName }}">
                           </div>
                           <span class="flight_name">@{{ flight.Segments[0][1].Airline.AirlineName }}</span>
                           <span class="flight_serial_number">@{{ flight.Segments[0][1].Airline.AirlineCode }}  @{{ flight.Segments[0][1].Airline.FlightNumber }}</span>
                        </div>
                        <div class="col-md-3 text-right">
                           <div class="city_time">@{{flight.Segments[0][1].Origin.Airport.AirportCode }} <span>(@{{flight.Segments[0][1].Origin.DepTime |  date:'HH:mm' }})</span></div>
                           <div class="airpot_name_data">@{{flight.Segments[0][1].Origin.Airport.AirportName }}, @{{flight.Segments[0][1].Origin.Airport.CountryName }}</div>
                        </div>
                        <div class="col-md-4 text-center">
                           <div class="flight_duration">
                              <span>@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                           </div>
                           <!-- <div class="flg_tech">Flight Duration</div> -->
                        </div>
                        <div class="col-md-3">
                           <div class="city_time">@{{flight.Segments[0][1].Destination.Airport.AirportCode }} <span>(@{{flight.Segments[0][1].Destination.ArrTime |  date:'HH:mm' }})</span></div>
                           <div class="airpot_name_data">@{{flight.Segments[0][1].Destination.Airport.AirportName }}, @{{flight.Segments[0][1].Destination.Airport.CountryName }}</div>
                        </div>
                     </div>
                     <!-- One Way Stop Div Ends  -->

                     <!-- INternational Return Div -->

                     <div class="row mt-4" ng-if="flight.Segments[1][0]">
                        <div class="col-md-9">
                           <div class="flex_width_air">
                              <div class="vistara_Data">
                                 <img src="/images/@{{ flight.Segments[1][0].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[1][0].Airline.AirlineName }}">
                                 <span>@{{ flight.Segments[1][0].Airline.AirlineName }}
                                 @{{ flight.Segments[1][0].Airline.AirlineCode }}  @{{ flight.Segments[1][0].Airline.FlightNumber }}
                                 </span>
                              </div>
                              <div class="main_flts_time">
                                <div class="fllight_time_date">
                                   <h5>@{{flight.Segments[1][0].Origin.Airport.AirportCode }} <span>@{{flight.Segments[1][0].Origin.Airport.CityName }}, @{{flight.Segments[1][0].Origin.Airport.CountryName }}</span></h5>
                                   <div class="time_flts">(@{{flight.Segments[1][0].Origin.DepTime |  date:'HH:mm' }})</div>
                                </div>
                                <!-- <div class="time_hr">@{{flight.Segments[1][0].Duration | time:'mm':'hhh mmm':false }}</div> -->
                                <div class="flight_duration">
                                    <span>@{{flight.Segments[1][0].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                </div>
                                <div class="fllight_time_date">
                                   <h5>@{{flight.Segments[1][0].Destination.Airport.AirportCode }} <span>@{{flight.Segments[1][0].Destination.Airport.CityName }}, @{{flight.Segments[1][0].Destination.Airport.CountryName }}</span></h5>
                                   <div class="time_flts">(@{{flight.Segments[1][0].Destination.ArrTime |  date:'HH:mm' }})</div>
                                </div>
                              </div>
                           </div>
                        </div>
                      </div>
                     <!-- Div Ends -->

                     <!-- INternational Return With One Stop  Div -->

                     <div class="row mt-4" ng-if="flight.Segments[1][1]">
                        <div class="col-md-9">
                           <div class="flex_width_air">
                              <div class="vistara_Data">
                                 <img src="/images/@{{ flight.Segments[1][1].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[1][1].Airline.AirlineName }}">
                                 <span>@{{ flight.Segments[1][1].Airline.AirlineName }}
                                 @{{ flight.Segments[1][1].Airline.AirlineCode }}  @{{ flight.Segments[1][1].Airline.FlightNumber }}
                                 </span>
                              </div>
                              <div class="main_flts_time">
                                <div class="fllight_time_date">
                                   <h5>@{{flight.Segments[1][1].Origin.Airport.AirportCode }} <span>@{{flight.Segments[1][1].Origin.Airport.CityName }}, @{{flight.Segments[1][1].Origin.Airport.CountryName }}</span></h5>
                                   <div class="time_flts">(@{{flight.Segments[1][1].Origin.DepTime |  date:'HH:mm' }})</div>
                                </div>
                                <!-- <div class="time_hr">@{{flight.Segments[1][1].Duration | time:'mm':'hhh mmm':false }}</div> -->
                                <div class="flight_duration">
                                    <span>@{{flight.Segments[1][1].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                </div>
                                <div class="fllight_time_date">
                                   <h5>@{{flight.Segments[1][1].Destination.Airport.AirportCode }} <span>@{{flight.Segments[1][1].Destination.Airport.CityName }}, @{{flight.Segments[1][1].Destination.Airport.CountryName }}</span></h5>
                                   <div class="time_flts">(@{{flight.Segments[1][1].Destination.ArrTime |  date:'HH:mm' }})</div>
                                </div>
                              </div>
                           </div>
                        </div>
                      </div>
                     <!-- Div Ends -->

                     <div class="row">
                        <div class="col-md-12">
                           <div class="refundable_data">
                              <span class="non_refundable">Non-Refundable</span>
                              <a href="javascript:void(0);" class="btn btn_flight_details" data-id="@{{$index}}">Flight Details</a>
                           </div>
                        </div>
                     </div>
                  

                  <div class="flight_information_data" id="view_flight_@{{$index}}" style="display: none;">
                     <nav>
                        <div class="nav nav-tabs nav-fill" role="tablist">
                           <a class="nav-item nav-link active" id="nav-flightInformation-tab" data-toggle="tab" href="#nav-flightInformation_@{{$index}}" role="tab" aria-controls="nav-flightInformation" aria-selected="true">Flight Information</a>
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
                                        <img src="/images/@{{ flight.Segments[0][0].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[0][0].Airline.AirlineName }}">
                                     </div>
                                     <span class="flight_name">@{{ flight.Segments[0][0].Airline.AirlineName }}</span>
                                     <span class="flight_serial_number">@{{ flight.Segments[0][0].Airline.AirlineCode }}  @{{ flight.Segments[0][0].Airline.FlightNumber }}</span>
                                  </div>
                                  <div class="col-md-3 text-right">
                                     <div class="city_time">@{{flight.Segments[0][0].Origin.Airport.AirportCode }} <span>(@{{flight.Segments[0][0].Origin.DepTime |  date:'HH:mm'}})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[0][0].Origin.Airport.AirportName }}, @{{flight.Segments[0][0].Origin.Airport.CountryName }}</div>
                                  </div>
                                  <div class="col-md-4 text-center">
                                     <div class="flight_duration">
                                        <span>@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                     </div>
                                     <!-- <div class="flg_tech">Flight Duration</div> -->
                                  </div>
                                  <div class="col-md-3">
                                     <div class="city_time">@{{flight.Segments[0][0].Destination.Airport.AirportCode }} <span>(@{{flight.Segments[0][0].Destination.ArrTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[0][0].Destination.Airport.AirportName }}, @{{flight.Segments[0][0].Destination.Airport.CountryName }}</div>
                                  </div>
                                 </div>

                                 <!-- Domestic One Way With One Stop Div -->
                                 <div class="row mt-4" ng-if="flight.Segments[0][1]">
                                    <div class="col-md-2">
                                     <div class="plane_data">
                                        <img src="/images/@{{ flight.Segments[0][1].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[0][1].Airline.AirlineName }}">
                                     </div>
                                     <span class="flight_name">@{{ flight.Segments[0][1].Airline.AirlineName }}</span>
                                     <span class="flight_serial_number">@{{ flight.Segments[0][1].Airline.AirlineCode }}  @{{ flight.Segments[0][1].Airline.FlightNumber }}</span>
                                  </div>
                                  <div class="col-md-3 text-right">
                                     <div class="city_time">@{{flight.Segments[0][1].Origin.Airport.AirportCode }} <span>(@{{flight.Segments[0][1].Origin.DepTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[0][1].Origin.Airport.AirportName }}, @{{flight.Segments[0][1].Origin.Airport.CountryName }}</div>
                                  </div>
                                  <div class="col-md-4 text-center">
                                     <div class="flight_duration">
                                        <span>@{{flight.Segments[0][1].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                     </div>
                                    <!--  <div class="flg_tech">Flight Duration</div> -->
                                  </div>
                                  <div class="col-md-3">
                                     <div class="city_time">@{{flight.Segments[0][1].Destination.Airport.AirportCode }} <span>(@{{flight.Segments[0][1].Destination.ArrTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[0][1].Destination.Airport.AirportName }}, @{{flight.Segments[0][1].Destination.Airport.CountryName }}</div>
                                  </div>
                                 </div>
                                 <!-- Ends here -->

                                 <!-- International Return Div  -->
                                 <div class="row mt-4" ng-if="flight.Segments[1][0]">
                                    <div class="col-md-2">
                                     <div class="plane_data">
                                        <img src="/images/@{{ flight.Segments[1][0].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[1][0].Airline.AirlineName }}">
                                     </div>
                                     <span class="flight_name">@{{ flight.Segments[1][0].Airline.AirlineName }}</span>
                                     <span class="flight_serial_number">@{{ flight.Segments[1][0].Airline.AirlineCode }}  @{{ flight.Segments[1][0].Airline.FlightNumber }}</span>
                                  </div>
                                  <div class="col-md-3 text-right">
                                     <div class="city_time">@{{flight.Segments[1][0].Origin.Airport.AirportCode }} <span>(@{{flight.Segments[1][0].Origin.DepTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[1][0].Origin.Airport.AirportName }}, @{{flight.Segments[1][0].Origin.Airport.CountryName }}</div>
                                  </div>
                                  <div class="col-md-4 text-center">
                                     <div class="flight_duration">
                                        <span>@{{flight.Segments[1][0].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                     </div>
                                     <!-- <div class="flg_tech">Flight Duration</div> -->
                                  </div>
                                  <div class="col-md-3">
                                     <div class="city_time">@{{flight.Segments[1][0].Destination.Airport.AirportCode }} <span>(@{{flight.Segments[1][0].Destination.ArrTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[1][0].Destination.Airport.AirportName }}, @{{flight.Segments[1][0].Destination.Airport.CountryName }}</div>
                                  </div>
                                 </div>
                                 <!-- Ends Here -->

                                 <!-- International Return With one stop Div -->
                                 <div class="row mt-4" ng-if="flight.Segments[1][1]">
                                    <div class="col-md-2">
                                     <div class="plane_data">
                                        <img src="/images/@{{ flight.Segments[1][1].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[1][1].Airline.AirlineName }}">
                                     </div>
                                     <span class="flight_name">@{{ flight.Segments[1][1].Airline.AirlineName }}</span>
                                     <span class="flight_serial_number">@{{ flight.Segments[1][1].Airline.AirlineCode }}  @{{ flight.Segments[1][1].Airline.FlightNumber }}</span>
                                  </div>
                                  <div class="col-md-3 text-right">
                                     <div class="city_time">@{{flight.Segments[1][1].Origin.Airport.AirportCode }} <span>(@{{flight.Segments[1][1].Origin.DepTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[1][1].Origin.Airport.AirportName }}, @{{flight.Segments[1][1].Origin.Airport.CountryName }}</div>
                                  </div>
                                  <div class="col-md-4 text-center">
                                     <div class="flight_duration">
                                        <span>@{{flight.Segments[1][1].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                     </div>
                                     <!-- <div class="flg_tech">Flight Duration</div> -->
                                  </div>
                                  <div class="col-md-3">
                                     <div class="city_time">@{{flight.Segments[1][1].Destination.Airport.AirportCode }} <span>(|  date:'HH:mm' @{{flight.Segments[1][1].Destination.ArrTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[1][1].Destination.Airport.AirportName }}, @{{flight.Segments[1][1].Destination.Airport.CountryName }}</div>
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
                                    <td>Base Fare (1 Adult)</td>
                                    <td>@{{flight.Fare.Currency}} @{{flight.Fare.BaseFare}} </td>
                                 </tr>
                                 <tr>
                                    <td>Taxes and Fees (1 Adult)</td>
                                    <td>@{{flight.Fare.Currency}} @{{flight.Fare.Tax}} </td>
                                 </tr>
                                 <tr>
                                    <th>Total Fare (1 Adult)</th>
                                    <th>@{{flight.Fare.Currency}} @{{flight.Fare.OfferedFare}} </th>
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
                                    <td>@{{ flight.Segments[0][0].Baggage }}</td>
                                    <td>@{{ flight.Segments[0][0].CabinBaggage }}</td>
                                 </tr>
                              </table>
                              <!-- <p class="baggage_data">* Only 1 check-in baggage allowed per passenger. You can buy excess baggage as allowed by the airline, however you might have to pay additional charges at airport.</p> -->
                           </div>
                        </div>
                     </div>
                  </div>

                </div>

                <div class="col-md-12 no-flight" ng-if="loaded && flights.Error.ErrorMessage == 'No Result Found' ">
                  <h1> No flights found for {{ $input['from'] }} to {{ $input['to'] }} for @{{ searchData.departDate | date:"MMMM d, y" }}. <br>
                    @if(!empty($flights['Error']))
                    <h3>{{ $flights['Error']['ErrorMessage'] }}.</h3>
                    @endif
                  </h1>
                </div>
               </div>
               <!-- For Return  -->

               <div class="col-md-4 return" ng-if="flights.Results[1].length > 0">
                  <div class="air_list_data f-list" ng-repeat="flight in flights.Results[1]" data-price="@{{flight.Fare.OfferedFare}}" data-return="@{{flight.time}}" data-air="@{{flight.Segments[0][0].Airline.AirlineName}}">

                     <div class="row align-items-end">
                        <div class="col-md-11">
                           <div class="flex_width_air">
                             <div class="vistara_Data">
                              @{{flight.Segments[0][1].Airline.AirlineName}} 
                                <img src="/images/@{{flight.Segments[0][0].Airline.AirlineName}}.gif" alt="@{{flight.Segments[0][0].Airline.AirlineName}}" />
                                <span>@{{flight.Segments[0][0].Airline.AirlineName}} 
                                @{{flight.Segments[0][0].Airline.AirlineCode}} @{{flight.Segments[0][0].Airline.FlightNumber}}
                                </span>
                             </div>
                             <div class="main_flts_time">
                                <div class="fllight_time_date">
                                   <h5>@{{flight.Segments[0][0].Origin.Airport.AirportCode }} <span>@{{flight.Segments[0][0].Origin.Airport.CityName }}, @{{flight.Segments[0][0].Origin.Airport.CountryName }}</span></h5>
                                   <div class="time_flts">(@{{flight.Segments[0][0].Origin.DepTime |  date:'HH:mm' }})</div>
                                </div>
                               <!--  <div class="time_hr">@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}</div> -->
                               <div class="flight_duration">
                                    <span>@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                </div>
                                <div class="fllight_time_date">
                                   <h5>@{{flight.Segments[0][0].Destination.Airport.AirportCode }} <span>@{{flight.Segments[0][0].Destination.Airport.CityName }}, @{{flight.Segments[0][0].Destination.Airport.CountryName }}</span></h5>
                                   <div class="time_flts">(@{{flight.Segments[0][0].Destination.ArrTime |  date:'HH:mm' }})</div>
                                </div>
                                <div class="price_flight_datas">
                                   <h3>@{{flight.Fare.Currency}} @{{flight.Fare.OfferedFare}} 
                                      <span>@{{flight.Segments[0][0].NoOfSeatAvailable }} seat(s) left </span>
                                   </h3>
                                </div>
                             </div>
                          </div>
                        </div>
                        <div class="col-md-1">
                           <div class="book_data_btn">

                              <a href="/flight/@{{ flights.TraceId }}/@{{ flight.ResultIndex }}/0" target="_blank"  class="btn btn_book" ng-if="!flights.Results[1].length" >Book</a> 
  
                              <input ng-if="flights.Results[1].length > 0" type="radio" name="book_return" data-flightcode-return="@{{ flight.Segments[0][0].Airline.AirlineName }} @{{ flight.Segments[0][0].Airline.AirlineCode }} - @{{ flight.Segments[0][0].Airline.FlightNumber }}" data-from-time-return="(@{{flight.Segments[0][0].Origin.DepTime |  date:'HH:mm' }})" data-to-time-return="(@{{flight.Segments[0][0].Destination.ArrTime |  date:'HH:mm' }})"  data-duration-return="@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}" data-price-return="@{{flight.Fare.OfferedFare}}" data-currency-return="@{{flight.Fare.Currency}}"  data-seats-left-return="@{{flight.Segments[0][0].NoOfSeatAvailable }}" data-air-img-return="@{{ flight.Segments[0][0].Airline.AirlineName }}" data-trace-Id="@{{ flights.TraceId }}" data-result-index="@{{ flight.ResultIndex }}" class="form-control" id="book_return_id_@{{$index}}" value="">

                           </div>
                        </div>
                     </div>
                     
                     <!-- Domestic Return With Stop Div  -->
                     <div class="row mt-4" ng-if="flight.Segments[0][1]"> 
                        <div class="col-md-2">
                           <div class="plane_data">
                              <img src="/images/@{{ flight.Segments[0][1].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[0][1].Airline.AirlineName }}">
                           </div>
                           <span class="flight_name">@{{ flight.Segments[0][1].Airline.AirlineName }}</span>
                           <span class="flight_serial_number">@{{ flight.Segments[0][1].Airline.AirlineCode }}  @{{ flight.Segments[0][1].Airline.FlightNumber }}</span>
                        </div>
                        <div class="col-md-3 text-right">
                           <div class="city_time">@{{flight.Segments[0][1].Origin.Airport.AirportCode }} <span>(@{{flight.Segments[0][1].Origin.DepTime |  date:'HH:mm' }})</span></div>
                           <div class="airpot_name_data">@{{flight.Segments[0][1].Origin.Airport.AirportName }}, @{{flight.Segments[0][1].Origin.Airport.CountryName }}</div>
                        </div>
                        <div class="col-md-4 text-center">
                           <div class="flight_duration">
                              <span>@{{flight.Segments[0][1].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                           </div>
                           <!-- <div class="flg_tech">Flight Duration</div> -->
                        </div>
                        <div class="col-md-3">
                           <div class="city_time">@{{flight.Segments[0][1].Destination.Airport.AirportCode }} <span>(@{{flight.Segments[0][1].Destination.ArrTime |  date:'HH:mm' }})</span></div>
                           <div class="airpot_name_data">@{{flight.Segments[0][1].Destination.Airport.AirportName }}, @{{flight.Segments[0][1].Destination.Airport.CountryName }}</div>
                        </div>
                     </div>
                     <!-- One Way Stop Div Ends  -->


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
                                        <img src="/images/@{{ flight.Segments[0][0].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[0][0].Airline.AirlineName }}">
                                     </div>
                                     <span class="flight_name">@{{ flight.Segments[0][0].Airline.AirlineName }}</span>
                                     <span class="flight_serial_number">@{{ flight.Segments[0][0].Airline.AirlineCode }}  @{{ flight.Segments[0][0].Airline.FlightNumber }}</span>
                                  </div>
                                  <div class="col-md-3 text-right">
                                     <div class="city_time">@{{flight.Segments[0][0].Origin.Airport.AirportCode }} <span>(@{{flight.Segments[0][0].Origin.DepTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[0][0].Origin.Airport.AirportName }}, @{{flight.Segments[0][0].Origin.Airport.CountryName }}</div>
                                  </div>
                                  <div class="col-md-4 text-center">
                                     <div class="flight_duration">
                                        <span>@{{flight.Segments[0][0].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                     </div>
                                     <!-- <div class="flg_tech">Flight Duration</div> -->
                                  </div>
                                  <div class="col-md-3">
                                     <div class="city_time">@{{flight.Segments[0][0].Destination.Airport.AirportCode }} <span>(@{{flight.Segments[0][0].Destination.ArrTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[0][0].Destination.Airport.AirportName }}, @{{flight.Segments[0][0].Destination.Airport.CountryName }}</div>
                                  </div>
                                 </div>

                                 <!-- Domestic One Way With One Stop Div -->
                                 <div class="row mt-4" ng-if="flight.Segments[0][1]">
                                    <div class="col-md-2">
                                     <div class="plane_data">
                                        <img src="/images/@{{ flight.Segments[0][1].Airline.AirlineName }}.gif" alt="@{{ flight.Segments[0][1].Airline.AirlineName }}">
                                     </div>
                                     <span class="flight_name">@{{ flight.Segments[0][1].Airline.AirlineName }}</span>
                                     <span class="flight_serial_number">@{{ flight.Segments[0][1].Airline.AirlineCode }}  @{{ flight.Segments[0][1].Airline.FlightNumber }}</span>
                                  </div>
                                  <div class="col-md-3 text-right">
                                     <div class="city_time">@{{flight.Segments[0][1].Origin.Airport.AirportCode }} <span>(@{{flight.Segments[0][1].Origin.DepTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[0][1].Origin.Airport.AirportName }}, @{{flight.Segments[0][1].Origin.Airport.CountryName }}</div>
                                  </div>
                                  <div class="col-md-4 text-center">
                                     <div class="flight_duration">
                                        <span>@{{flight.Segments[0][1].Duration | time:'mm':'hhh mmm':false }}</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                     </div>
                                     <!-- <div class="flg_tech">Flight Duration</div> -->
                                  </div>
                                  <div class="col-md-3">
                                     <div class="city_time">@{{flight.Segments[0][1].Destination.Airport.AirportCode }} <span>(@{{flight.Segments[0][1].Destination.ArrTime |  date:'HH:mm' }})</span></div>
                                     <div class="airpot_name_data">@{{flight.Segments[0][1].Destination.Airport.AirportName }}, @{{flight.Segments[0][1].Destination.Airport.CountryName }}</div>
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
                                    <td>Base Fare (1 Adult)</td>
                                    <td>@{{flight.Fare.Currency}} @{{flight.Fare.BaseFare}} </td>
                                 </tr>
                                 <tr>
                                    <td>Taxes and Fees (1 Adult)</td>
                                    <td>@{{flight.Fare.Currency}} @{{flight.Fare.Tax}} </td>
                                 </tr>
                                 <tr>
                                    <th>Total Fare (1 Adult)</th>
                                    <th>@{{flight.Fare.Currency}} @{{flight.Fare.OfferedFare}} </th>
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
                                    <td>@{{ flight.Segments[0][0].Baggage }}</td>
                                    <td>@{{ flight.Segments[0][0].CabinBaggage }}</td>
                                 </tr>
                              </table>
                              <!-- <p class="baggage_data">* Only 1 check-in baggage allowed per passenger. You can buy excess baggage as allowed by the airline, however you might have to pay additional charges at airport.</p> -->
                           </div>
                        </div>
                     </div>
                  </div>
                  </div>
               </div>
               <!-- Code Ends -->
            </div>
         <!-- LIsting Ends -->
   </div>
</section>
</div>
@endsection