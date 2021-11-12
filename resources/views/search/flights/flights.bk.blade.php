@extends('layouts.app-header')
@section('content')   
<section class="listing_banner_forms">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
          <form action="{{ route('search_flights') }}" id="searchFlightsFormRound"  method="GET">
            @csrf
            <div class="listing_inner_forms">
               <div class="rounding_form_info">
                  <input type="hidden" id="JourneyType" name="JourneyType" value="{{ $input['JourneyType'] }}">
                  <div class="form-group">
                     <label>From</label>
                     <select name="origin" class="depart-from">
                        <option value="{{ $input['origin'] }}">{{ $input['from'] }}</option>
                     </select>
                     <input type="hidden" name="from" id="from-city" value="{{ $input['from'] }}">
                  </div>
                  <div class="form-group">
                     <label>To</label>
                     <select name="destination" class="depart-to">
                        <option value="{{ $input['destination'] }}">{{ $input['to'] }}</option>
                     </select>
                     <input type="hidden" name="to" id="to-city" value="{{ $input['to'] }}">
                  </div>
                  <div class="form-group">
                     <label>Departure <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                     <div class="input-group" >
                        <input class="form-control departdate" type="text" name="departDate" required readonly value="{{ $input['departDate'] }}"/>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                     </div>
                  </div>
                  <div class="form-group @if($input['JourneyType'] == '1') not-allowed @endif">
                     <label>Return <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                     <div class="input-group" >
                        <input class="form-control returndate" type="text" name="returnDate" required readonly value="{{ $input['returnDate'] }}"/>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Traveller &amp; Class</label>
                     <input type="text" name="travellersClass" id="travellersClass" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="{{ $input['travellersClass'] }}">
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
                  <input type="hidden" name="adultsF" class="adultsF" value="{{ $input['adultsF'] }}">
                  <input type="hidden" name="childsF" class="childsF" value="{{ $input['childsF'] }}">
                  <input type="hidden" name="infants" class="infantsF" value="{{ $input['infants'] }}">
                  <input type="hidden" name="FlightCabinClass" class="FlightCabinClass" value="{{ $input['FlightCabinClass'] }}">
                  <input type="hidden" name="DirectFlight" class="DirectFlight" value="{{ $input['DirectFlight'] }}">
                  <input type="hidden" name="OneStopFlight" class="OneStopFlight" value="{{ $input['OneStopFlight'] }}">
                  <div class="search_btns_listing"><button type="submit" class="btn btn-primary">Search</button></div>
               </div>
            </div>
          </form>
         </div>
      </div>
   </div>
</section>
<section class="flight_listing_section">
   <div class="container">
   <div class="row">
      <!-- <div class="col-md-4"></div> -->
      @if($flights['ResponseStatus'] == 1)
        @if(!empty($flights['Results']['1']))
        <div class="col-md-12">
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
                                   <h5 class="air_code_arrive"> {{$input['origin']}} </span></h5>
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
        @endif
      @endif  
   </div>
   <div class="row">
      <div class="col-md-3">
         <div class="filter_data">
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
            @if($flights['ResponseStatus'] == 1)
              @if(!empty($flights['Results']['1']))
              <div class="departure_data">
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
              @endif
            @endif
            <div class="departure_data">
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
            </div>
            <?php $flightsarray = array(); ?>
            @if($flights['ResponseStatus'] == 1)
              @if(sizeof($flights) > 0)
              @foreach($flights['Results']['0'] as $key => $flight)
              <?php  array_push($flightsarray, $flight['Segments'][0][0]['Airline']['AirlineName']); ?>
              @endforeach
              @endif
            @endif
            <?php $flightsarray = array_unique($flightsarray);?>
            <div class="departure_data">
               <div class="filter_heading">
                  <h4>Preferred Airlines</h4>
                  <a href="javascript:void(0);" class="reste_all_btn">Reset</a>
               </div>
               <div class="airline_filters">
                  @foreach($flightsarray as $key => $airline)
                  <div class="inner_check_air_data">
                     <label class="container_airline">  
                     <span class="airline_name">
                     <img src="/images/{{ $airline }}.gif" alt="{{ $airline }}" onerror="this.onerror=null;this.src='/images/DefaultAir.gif';">
                     <span class="air_name_set">{{ $airline }}</span>
                     </span>                    
                     <input type="checkbox" class="air-line-type" value="{{ $airline }}" checked='checked'>
                     <span class="checkmark"></span>
                     </label>
                  </div>
                  @endforeach       
               </div>
            </div>
         </div>
      </div>
      <!-- LIsting Html -->
      @if($flights['ResponseStatus'] == 1)
      @if(empty($flights['Results']['1']))
      <div class="col-md-9  oneway">
         @else
         <div class="col-md-4  oneway">
            @endif
            @if(sizeof($flights) > 0)
            @foreach($flights['Results']['0'] as $key => $flight)
            <?php 
               $depart = date('H' , strtotime($flight['Segments'][0][0]['Origin']['DepTime']));
               if($depart >=4 and $depart < 11){
                 $timenow = 'morning';
               }else if($depart >= 11 and $depart < 16){
                 $timenow = 'afternoon';
               }else if($depart >= 16 and $depart < 21){
                 $timenow = 'evening';
               }else{  
                 $timenow = 'night';
               }
               ?>
            <div class="air_list_data f-list" data-price="{{ $flight['Fare']['OfferedFare'] }}" data-depart="<?php echo $timenow; ?>" data-air="{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}">
               <div class="row align-items-end">
                  @if(empty($flights['Results']['1']))
                  <div class="col-md-9">
                     @else
                     <div class="col-md-11">
                        @endif
                        <div class="flex_width_air">
                           <div class="vistara_Data">
                              <img src="/images/{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}" onerror="this.onerror=null;this.src='/images/DefaultAir.gif';">
                              <span>{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }} 
                              {{ $flight['Segments'][0][0]['Airline']['AirlineCode'] }} - {{ $flight['Segments'][0][0]['Airline']['FlightNumber'] }}
                              </span>
                           </div>
                           <div class="main_flts_time">
                              <div class="fllight_time_date">
                                 <h5>{{ $flight['Segments'][0][0]['Origin']['Airport']['AirportCode'] }} <span>{{ $flight['Segments'][0][0]['Origin']['Airport']['CityName'] }}, {{ $flight['Segments'][0][0]['Origin']['Airport']['CountryName'] }}</span></h5>
                                 <div class="time_flts">({{ date('H:i' , strtotime($flight['Segments'][0][0]['Origin']['DepTime'])) }})</div>
                              </div>
                              <div class="time_hr">{{ intdiv($flight['Segments'][0][0]['Duration'], 60).'h '. ($flight['Segments'][0][0]['Duration'] % 60) }}m</div>
                              <div class="fllight_time_date">
                                 <h5>{{ $flight['Segments'][0][0]['Destination']['Airport']['AirportCode'] }} <span>{{ $flight['Segments'][0][0]['Destination']['Airport']['CityName'] }}, {{ $flight['Segments'][0][0]['Destination']['Airport']['CountryName'] }}</span></h5>
                                 <div class="time_flts">({{ date('H:i' , strtotime($flight['Segments'][0][0]['Destination']['ArrTime'])) }})</div>
                              </div>
                              <div class="price_flight_datas">
                                 <h3>{{ $flight['Fare']['Currency'] }} {{ number_format($flight['Fare']['OfferedFare'],2) }} 
                                    @if(!empty($flight['Segments'][0][0]) && isset($flight['Segments'][0][0]['NoOfSeatAvailable']))
                                    <span> {{ $flight['Segments'][0][0]['NoOfSeatAvailable'] }} seat(s) left </span>
                                    @endif
                                 </h3>
                              </div>
                           </div>
                        </div>
                     </div>
                     @if(empty($flights['Results']['1']))
                     <div class="col-md-3">
                        @else
                        <div class="col-md-1">
                           @endif 
                           <div class="book_data_btn">
                              @if(empty($flights['Results']['1']))
                              <a href="/flight/{{ $flights['TraceId'] }}/{{ $flight['ResultIndex'] }}/0" target="_blank"  class="btn btn_book">Book</a> 
                              @else
                              <input type="radio" name="book" data-flightcode="{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }} {{ $flight['Segments'][0][0]['Airline']['AirlineCode'] }} - {{ $flight['Segments'][0][0]['Airline']['FlightNumber'] }}" data-from-time="({{ date('H:i' , strtotime($flight['Segments'][0][0]['Origin']['DepTime'])) }})" data-to-time="({{ date('H:i' , strtotime($flight['Segments'][0][0]['Destination']['ArrTime'])) }})"  data-duration="{{ intdiv($flight['Segments'][0][0]['Duration'], 60).'h '. ($flight['Segments'][0][0]['Duration'] % 60) }}m" data-price="{{ number_format($flight['Fare']['OfferedFare'],2) }}" data-currency="{{ $flight['Fare']['Currency'] }}"  data-seats-left="{{ isset($flight['Segments'][0][0]['NoOfSeatAvailable']) ? $flight['Segments'][0][0]['NoOfSeatAvailable'] : ''}}" data-air-img="{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}" data-trace-Id="{{ $flights['TraceId'] }}" data-result-index="{{ $flight['ResultIndex'] }}" class="form-control" id="book_id_{{$key}}" value="">
                              @endif
                           </div>
                        </div>
                     </div>
                     @if(!empty($flight['Segments'][0][1]))
                     <br />
                     <div class="row">
                        <div class="col-md-2">
                           <div class="plane_data">
                              <img src="/images/{{ $flight['Segments'][0][1]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][0][1]['Airline']['AirlineName'] }}">
                           </div>
                           <span class="flight_name">{{ $flight['Segments'][0][1]['Airline']['AirlineName'] }}</span>
                           <span class="flight_serial_number">{{ $flight['Segments'][0][1]['Airline']['AirlineCode'] }}  {{ $flight['Segments'][0][1]['Airline']['FlightNumber'] }}</span>
                        </div>
                        <div class="col-md-3 text-right">
                           <div class="city_time">{{ $flight['Segments'][0][1]['Origin']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][0][1]['Origin']['DepTime'])) }})</span></div>
                           <div class="airpot_name_data">{{ $flight['Segments'][0][1]['Origin']['Airport']['AirportName'] }},  {{ $flight['Segments'][0][1]['Origin']['Airport']['CountryName'] }}</div>
                        </div>
                        <div class="col-md-4 text-center">
                           <div class="flight_duration">
                              <span>{{ intdiv($flight['Segments'][0][1]['Duration'], 60).'h '. ($flight['Segments'][0][1]['Duration'] % 60) }}m</span> <i class="fa fa-plane" aria-hidden="true"></i>
                           </div>
                           <div class="flg_tech">Flight Duration</div>
                        </div>
                        <div class="col-md-3">
                           <div class="city_time">{{ $flight['Segments'][0][1]['Destination']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][0][1]['Destination']['ArrTime'])) }})</span></div>
                           <div class="airpot_name_data">{{ $flight['Segments'][0][1]['Destination']['Airport']['AirportName'] }},  {{ $flight['Segments'][0][1]['Destination']['Airport']['CountryName'] }}</div>
                        </div>
                     </div>
                     @endif
                     @if(!empty($flight['Segments'][1][0]))
                     <br />
                     <div class="row">
                        <div class="col-md-9">
                           <div class="flex_width_air">
                              <div class="vistara_Data">
                                 <img src="/images/{{ $flight['Segments'][1][0]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][1][0]['Airline']['AirlineName'] }}">
                                 <span>{{ $flight['Segments'][1][0]['Airline']['AirlineName'] }} 
                                 {{ $flight['Segments'][1][0]['Airline']['AirlineCode'] }} - {{ $flight['Segments'][1][0]['Airline']['FlightNumber'] }}
                                 </span>
                              </div>
                              <div class="main_flts_time">
                                 <div class="fllight_time_date">
                                    <h5>{{ $flight['Segments'][1][0]['Origin']['Airport']['AirportCode'] }} <span>{{ $flight['Segments'][1][0]['Origin']['Airport']['CityName'] }}, {{ $flight['Segments'][1][0]['Origin']['Airport']['CountryName'] }}</span></h5>
                                    <div class="time_flts">({{ date('H:i' , strtotime($flight['Segments'][1][0]['Origin']['DepTime'])) }})</div>
                                 </div>
                                 <div class="time_hr">{{ intdiv($flight['Segments'][1][0]['Duration'], 60).'h '. ($flight['Segments'][1][0]['Duration'] % 60) }}m</div>
                                 <div class="fllight_time_date">
                                    <h5>{{ $flight['Segments'][1][0]['Destination']['Airport']['AirportCode'] }} <span>{{ $flight['Segments'][1][0]['Destination']['Airport']['CityName'] }}, {{ $flight['Segments'][1][0]['Destination']['Airport']['CountryName'] }}</span></h5>
                                    <div class="time_flts">({{ date('H:i' , strtotime($flight['Segments'][1][0]['Destination']['ArrTime'])) }})</div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     @endif
                     @if(!empty($flight['Segments'][1][1]))
                     <br />
                     <div class="row">
                        <div class="col-md-9">
                           <div class="flex_width_air">
                              <div class="vistara_Data">
                                 <img src="/images/{{ $flight['Segments'][1][1]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][1][1]['Airline']['AirlineName'] }}">
                                 <span>{{ $flight['Segments'][1][1]['Airline']['AirlineName'] }} 
                                 {{ $flight['Segments'][1][1]['Airline']['AirlineCode'] }} - {{ $flight['Segments'][1][1]['Airline']['FlightNumber'] }}
                                 </span>
                              </div>
                              <div class="main_flts_time">
                                 <div class="fllight_time_date">
                                    <h5>{{ $flight['Segments'][1][1]['Origin']['Airport']['AirportCode'] }} <span>{{ $flight['Segments'][1][1]['Origin']['Airport']['CityName'] }}, {{ $flight['Segments'][1][1]['Origin']['Airport']['CountryName'] }}</span></h5>
                                    <div class="time_flts">({{ date('H:i' , strtotime($flight['Segments'][1][1]['Origin']['DepTime'])) }})</div>
                                 </div>
                                 <div class="time_hr">{{ intdiv($flight['Segments'][1][1]['Duration'], 60).'h '. ($flight['Segments'][1][1]['Duration'] % 60) }}m</div>
                                 <div class="fllight_time_date">
                                    <h5>{{ $flight['Segments'][1][1]['Destination']['Airport']['AirportCode'] }} <span>{{ $flight['Segments'][1][1]['Destination']['Airport']['CityName'] }}, {{ $flight['Segments'][1][1]['Destination']['Airport']['CountryName'] }}</span></h5>
                                    <div class="time_flts">({{ date('H:i' , strtotime($flight['Segments'][1][1]['Destination']['ArrTime'])) }})</div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     @endif
                     <div class="row">
                        <div class="col-md-12">
                           <div class="refundable_data">
                              <span class="non_refundable">Non-Refundable</span>
                              <a href="javascript:void(0);" class="btn btn_flight_details" data-id="{{$key}}">Flight Details</a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="flight_information_data" id="view_flight_{{$key}}" style="display: none;">
                     <nav>
                        <div class="nav nav-tabs nav-fill" role="tablist">
                           <a class="nav-item nav-link active" id="nav-flightInformation-tab" data-toggle="tab" href="#nav-flightInformation_{{$key}}" role="tab" aria-controls="nav-flightInformation" aria-selected="true">Flight Information</a>
                           <a class="nav-item nav-link" id="nav-fare-tab" data-toggle="tab" href="#nav-fare_{{$key}}" role="tab" aria-controls="nav-fare" aria-selected="false">Fare Details</a>
                           <a class="nav-item nav-link" id="nav-baggage-tab" data-toggle="tab" href="#nav-baggage_{{$key}}" role="tab" aria-controls="nav-baggage" aria-selected="false">Baggage Rules</a>
                           <a class="nav-item nav-link" id="nav-cancellation-tab" data-toggle="tab" href="#nav-cancellation_{{$key}}" role="tab" aria-controls="nav-cancellation" aria-selected="false">Cancellation Rules</a>
                        </div>
                     </nav>
                     <div class="tab-content">
                        <div class="tab-pane fade show active" id="nav-flightInformation_{{$key}}" role="tabpanel" aria-labelledby="nav-flightInformation-tab">
                           <div class="inner_flight_tabs">
                              <div class="flght_data_info">
                                 <div class="row">
                                    <div class="col-md-2">
                                       <div class="plane_data">
                                          <img src="/images/{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}">
                                       </div>
                                       <span class="flight_name">{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}</span>
                                       <span class="flight_serial_number">{{ $flight['Segments'][0][0]['Airline']['AirlineCode'] }}  {{ $flight['Segments'][0][0]['Airline']['FlightNumber'] }}</span>
                                    </div>
                                    <div class="col-md-3 text-right">
                                       <div class="city_time">{{ $flight['Segments'][0][0]['Origin']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][0][0]['Origin']['DepTime'])) }})</span></div>
                                       <div class="airpot_name_data">{{ $flight['Segments'][0][0]['Origin']['Airport']['AirportName'] }},  {{ $flight['Segments'][0][0]['Origin']['Airport']['CountryName'] }}</div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                       <div class="flight_duration">
                                          <span>{{ intdiv($flight['Segments'][0][0]['Duration'], 60).'h '. ($flight['Segments'][0][0]['Duration'] % 60) }}m</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                       </div>
                                       <div class="flg_tech">Flight Duration</div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="city_time">{{ $flight['Segments'][0][0]['Destination']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][0][0]['Destination']['ArrTime'])) }})</span></div>
                                       <div class="airpot_name_data">{{ $flight['Segments'][0][0]['Destination']['Airport']['AirportName'] }},  {{ $flight['Segments'][0][0]['Destination']['Airport']['CountryName'] }}</div>
                                    </div>
                                 </div>
                                 @if(!empty($flight['Segments'][0][1]))
                                 <br />
                                 <div class="row">
                                    <div class="col-md-2">
                                       <div class="plane_data">
                                          <img src="/images/{{ $flight['Segments'][0][1]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][0][1]['Airline']['AirlineName'] }}">
                                       </div>
                                       <span class="flight_name">{{ $flight['Segments'][0][1]['Airline']['AirlineName'] }}</span>
                                       <span class="flight_serial_number">{{ $flight['Segments'][0][1]['Airline']['AirlineCode'] }}  {{ $flight['Segments'][0][1]['Airline']['FlightNumber'] }}</span>
                                    </div>
                                    <div class="col-md-3 text-right">
                                       <div class="city_time">{{ $flight['Segments'][0][1]['Origin']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][0][1]['Origin']['DepTime'])) }})</span></div>
                                       <div class="airpot_name_data">{{ $flight['Segments'][0][1]['Origin']['Airport']['AirportName'] }},  {{ $flight['Segments'][0][1]['Origin']['Airport']['CountryName'] }}</div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                       <div class="flight_duration">
                                          <span>{{ intdiv($flight['Segments'][0][1]['Duration'], 60).'h '. ($flight['Segments'][0][1]['Duration'] % 60) }}m</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                       </div>
                                       <div class="flg_tech">Flight Duration</div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="city_time">{{ $flight['Segments'][0][1]['Destination']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][0][1]['Destination']['ArrTime'])) }})</span></div>
                                       <div class="airpot_name_data">{{ $flight['Segments'][0][1]['Destination']['Airport']['AirportName'] }},  {{ $flight['Segments'][0][1]['Destination']['Airport']['CountryName'] }}</div>
                                    </div>
                                 </div>
                                 @endif
                                 @if(!empty($flight['Segments'][1][0]))
                                 <br />
                                 <div class="row">
                                    <div class="col-md-2">
                                       <div class="plane_data">
                                          <img src="/images/{{ $flight['Segments'][1][0]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][1][0]['Airline']['AirlineName'] }}">
                                       </div>
                                       <span class="flight_name">{{ $flight['Segments'][1][0]['Airline']['AirlineName'] }}</span>
                                       <span class="flight_serial_number">{{ $flight['Segments'][1][0]['Airline']['AirlineCode'] }}  {{ $flight['Segments'][1][0]['Airline']['FlightNumber'] }}</span>
                                    </div>
                                    <div class="col-md-3 text-right">
                                       <div class="city_time">{{ $flight['Segments'][1][0]['Origin']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][1][0]['Origin']['DepTime'])) }})</span></div>
                                       <div class="airpot_name_data">{{ $flight['Segments'][1][0]['Origin']['Airport']['AirportName'] }},  {{ $flight['Segments'][1][0]['Origin']['Airport']['CountryName'] }}</div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                       <div class="flight_duration">
                                          <span>{{ intdiv($flight['Segments'][1][0]['Duration'], 60).'h '. ($flight['Segments'][1][0]['Duration'] % 60) }}m</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                       </div>
                                       <div class="flg_tech">Flight Duration</div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="city_time">{{ $flight['Segments'][1][0]['Destination']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][1][0]['Destination']['ArrTime'])) }})</span></div>
                                       <div class="airpot_name_data">{{ $flight['Segments'][1][0]['Destination']['Airport']['AirportName'] }},  {{ $flight['Segments'][1][0]['Destination']['Airport']['CountryName'] }}</div>
                                    </div>
                                 </div>
                                 @endif
                                 @if(!empty($flight['Segments'][1][1]))
                                 <br />
                                 <div class="row">
                                    <div class="col-md-2">
                                       <div class="plane_data">
                                          <img src="/images/{{ $flight['Segments'][1][1]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][1][1]['Airline']['AirlineName'] }}">
                                       </div>
                                       <span class="flight_name">{{ $flight['Segments'][1][1]['Airline']['AirlineName'] }}</span>
                                       <span class="flight_serial_number">{{ $flight['Segments'][1][1]['Airline']['AirlineCode'] }}  {{ $flight['Segments'][1][1]['Airline']['FlightNumber'] }}</span>
                                    </div>
                                    <div class="col-md-3 text-right">
                                       <div class="city_time">{{ $flight['Segments'][1][1]['Origin']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][1][1]['Origin']['DepTime'])) }})</span></div>
                                       <div class="airpot_name_data">{{ $flight['Segments'][1][1]['Origin']['Airport']['AirportName'] }},  {{ $flight['Segments'][1][1]['Origin']['Airport']['CountryName'] }}</div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                       <div class="flight_duration">
                                          <span>{{ intdiv($flight['Segments'][1][1]['Duration'], 60).'h '. ($flight['Segments'][1][1]['Duration'] % 60) }}m</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                       </div>
                                       <div class="flg_tech">Flight Duration</div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="city_time">{{ $flight['Segments'][1][1]['Destination']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][1][1]['Destination']['ArrTime'])) }})</span></div>
                                       <div class="airpot_name_data">{{ $flight['Segments'][1][1]['Destination']['Airport']['AirportName'] }},  {{ $flight['Segments'][1][1]['Destination']['Airport']['CountryName'] }}</div>
                                    </div>
                                 </div>
                                 @endif
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane fade" id="nav-fare_{{$key}}" role="tabpanel" aria-labelledby="nav-fare-tab">
                           <div class="inner_flight_tabs">
                              <table class="table">
                                 <tr>
                                    <td>Base Fare (1 Adult)</td>
                                    <td>{{ $flight['Fare']['Currency'] }} {{ number_format($flight['Fare']['BaseFare'],2) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Taxes and Fees (1 Adult)</td>
                                    <td>{{ $flight['Fare']['Currency'] }} {{ number_format($flight['Fare']['Tax'],2) }}</td>
                                 </tr>
                                 <tr>
                                    <th>Total Fare (1 Adult)</th>
                                    <th>{{ $flight['Fare']['Currency'] }} {{ number_format($flight['Fare']['OfferedFare'],2) }}</th>
                                 </tr>
                              </table>
                           </div>
                        </div>
                        <div class="tab-pane fade" id="nav-baggage_{{$key}}" role="tabpanel" aria-labelledby="nav-baggage-tab">
                           <div class="inner_flight_tabs">
                              <table class="table">
                                 <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                 </tr>
                                 <tr>
                                    <td>Adult</td>
                                    <td>{{ $flight['Segments'][0][0]['Baggage'] }}</td>
                                    <td>{{ $flight['Segments'][0][0]['CabinBaggage'] }}</td>
                                 </tr>
                              </table>
                              <!-- <p class="baggage_data">* Only 1 check-in baggage allowed per passenger. You can buy excess baggage as allowed by the airline, however you might have to pay additional charges at airport.</p> -->
                           </div>
                        </div>
                        <div class="tab-pane fade" id="nav-cancellation_{{$key}}" role="tabpanel" aria-labelledby="nav-cancellation-tab">
                           <div class="inner_flight_tabs">
                              <div class="row">
                                 <div class="col-md-6">
                                    <table class="table">
                                       <tr>
                                          <td>Goibibo Fee</td>
                                          <td>₹300</td>
                                       </tr>
                                       <tr>
                                          <td>0-2 hours</td>
                                          <td>Non Refundable</td>
                                       </tr>
                                       <tr>
                                          <td>>2 hours</td>
                                          <td>Non Refundable</td>
                                       </tr>
                                    </table>
                                 </div>
                                 <div class="col-md-6">
                                    <table class="table">
                                       <tr>
                                          <td>Goibibo Fee</td>
                                          <td>₹300</td>
                                       </tr>
                                       <tr>
                                          <td>0-2 hours</td>
                                          <td>Non Refundable</td>
                                       </tr>
                                       <tr>
                                          <td>>2 hours</td>
                                          <td>Non Refundable</td>
                                       </tr>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  @endforeach
                  @endif
               </div>
               <!-- For Return  -->
               @if(!empty($flights['Results']['1']))
               <div class="col-md-4 return">
                  @endif    
                  @if(sizeof($flights) > 0 && !empty($flights['Results']['1']))
                  @foreach($flights['Results']['1'] as $key => $flight)
                  <?php 
                     $depart = date('H' , strtotime($flight['Segments'][0][0]['Origin']['DepTime']));
                     if($depart >=4 and $depart < 11){
                       $timenow = 'morning';
                     }else if($depart >= 11 and $depart < 16){
                       $timenow = 'afternoon';
                     }else if($depart >= 16 and $depart < 21){
                       $timenow = 'evening';
                     }else{  
                       $timenow = 'night';
                     }
                     ?>
                  <div class="air_list_data f-list" data-price="{{ $flight['Fare']['OfferedFare'] }}" data-return="<?php echo $timenow; ?>" data-air="{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}">
                     <div class="row align-items-end">
                        <div class="col-md-11">
                           <div class="flex_width_air">
                              <div class="vistara_Data">
                                 <img src="/images/{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}">
                                 <span>{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }} 
                                 {{ $flight['Segments'][0][0]['Airline']['AirlineCode'] }} - {{ $flight['Segments'][0][0]['Airline']['FlightNumber'] }}
                                 </span>
                              </div>
                              <div class="main_flts_time">
                                 <div class="fllight_time_date">
                                    <h5>{{ $flight['Segments'][0][0]['Origin']['Airport']['AirportCode'] }} <span>{{ $flight['Segments'][0][0]['Origin']['Airport']['CityName'] }}, {{ $flight['Segments'][0][0]['Origin']['Airport']['CountryName'] }}</span></h5>
                                    <div class="time_flts">({{ date('H:i' , strtotime($flight['Segments'][0][0]['Origin']['DepTime'])) }})</div>
                                 </div>
                                 <div class="time_hr">{{ intdiv($flight['Segments'][0][0]['Duration'], 60).'h '. ($flight['Segments'][0][0]['Duration'] % 60) }}m</div>
                                 <div class="fllight_time_date">
                                    <h5>{{ $flight['Segments'][0][0]['Destination']['Airport']['AirportCode'] }} <span>{{ $flight['Segments'][0][0]['Destination']['Airport']['CityName'] }}, {{ $flight['Segments'][0][0]['Destination']['Airport']['CountryName'] }}</span></h5>
                                    <div class="time_flts">({{ date('H:i' , strtotime($flight['Segments'][0][0]['Destination']['ArrTime'])) }})</div>
                                 </div>
                                 <div class="price_flight_datas">
                                    <h3>{{ $flight['Fare']['Currency'] }} {{ number_format($flight['Fare']['OfferedFare'],2) }} 
                                       @if(isset($flight['Segments'][0][0]['NoOfSeatAvailable']))
                                       <span> 
                                       {{ $flight['Segments'][0][0]['NoOfSeatAvailable'] }} seat(s) left </span>
                                       @endif
                                    </h3>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-1">
                           <div class="book_data_btn">
                              @if(empty($flights['Results']['1']))
                              <a href="javascript:void(0);" class="btn btn_book">Book</a> 
                              @else
                              <input type="radio" name="book_return" data-flightcode-return="{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }} {{ $flight['Segments'][0][0]['Airline']['AirlineCode'] }} - {{ $flight['Segments'][0][0]['Airline']['FlightNumber'] }}" data-from-time-return="({{ date('H:i' , strtotime($flight['Segments'][0][0]['Origin']['DepTime'])) }})" data-to-time-return="({{ date('H:i' , strtotime($flight['Segments'][0][0]['Destination']['ArrTime'])) }})"  data-duration-return="{{ intdiv($flight['Segments'][0][0]['Duration'], 60).'h '. ($flight['Segments'][0][0]['Duration'] % 60) }}m" data-price-return="{{ number_format($flight['Fare']['OfferedFare'],2) }}" data-currency-return="{{ $flight['Fare']['Currency'] }}" data-seats-left-return="{{ isset($flight['Segments'][0][0]['NoOfSeatAvailable']) ? $flight['Segments'][0][0]['NoOfSeatAvailable'] : '' }}" data-air-img-return="{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}" data-trace-Id="{{ $flights['TraceId'] }}" data-result-index="{{ $flight['ResultIndex'] }}" id="book_return_id_{{$key}}" class="form-control" value="">
                              @endif
                           </div>
                        </div>
                     </div>
                     @if(!empty($flight['Segments'][0][1]))
                     <br />
                     <div class="row">
                        <div class="col-md-2">
                           <div class="plane_data">
                              <img src="/images/{{ $flight['Segments'][0][1]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][0][1]['Airline']['AirlineName'] }}">
                           </div>
                           <span class="flight_name">{{ $flight['Segments'][0][1]['Airline']['AirlineName'] }}</span>
                           <span class="flight_serial_number">{{ $flight['Segments'][0][1]['Airline']['AirlineCode'] }}  {{ $flight['Segments'][0][1]['Airline']['FlightNumber'] }}</span>
                        </div>
                        <div class="col-md-3 text-right">
                           <div class="city_time">{{ $flight['Segments'][0][1]['Origin']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][0][1]['Origin']['DepTime'])) }})</span></div>
                           <div class="airpot_name_data">{{ $flight['Segments'][0][1]['Origin']['Airport']['AirportName'] }},  {{ $flight['Segments'][0][1]['Origin']['Airport']['CountryName'] }}</div>
                        </div>
                        <div class="col-md-4 text-center">
                           <div class="flight_duration">
                              <span>{{ intdiv($flight['Segments'][0][1]['Duration'], 60).'h '. ($flight['Segments'][0][1]['Duration'] % 60) }}m</span> <i class="fa fa-plane" aria-hidden="true"></i>
                           </div>
                           <div class="flg_tech">Flight Duration</div>
                        </div>
                        <div class="col-md-3">
                           <div class="city_time">{{ $flight['Segments'][0][1]['Destination']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][0][1]['Destination']['ArrTime'])) }})</span></div>
                           <div class="airpot_name_data">{{ $flight['Segments'][0][1]['Destination']['Airport']['AirportName'] }},  {{ $flight['Segments'][0][1]['Destination']['Airport']['CountryName'] }}</div>
                        </div>
                     </div>
                     @endif
                     <div class="row">
                        <div class="col-md-12">
                           <div class="refundable_data">
                              <span class="non_refundable">Non-Refundable</span>
                              <a href="javascript:void(0);" class="btn btn_flight_details_return" data-id="{{$key}}">Flight Details</a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="flight_information_data" id="view_flight_return_{{$key}}" style="display: none;">
                     <nav>
                        <div class="nav nav-tabs nav-fill" role="tablist">
                           <a class="nav-item nav-link active" id="nav-flightInformation-tab" data-toggle="tab" href="#nav-flightInformation_return_{{$key}}" role="tab" aria-controls="nav-flightInformation" aria-selected="true">Flight Information</a>
                           <a class="nav-item nav-link" id="nav-fare-tab" data-toggle="tab" href="#nav-fare_return_{{$key}}" role="tab" aria-controls="nav-fare" aria-selected="false">Fare Details</a>
                           <a class="nav-item nav-link" id="nav-baggage-tab" data-toggle="tab" href="#nav-baggage_return_{{$key}}" role="tab" aria-controls="nav-baggage" aria-selected="false">Baggage Rules</a>
                           <a class="nav-item nav-link" id="nav-cancellation-tab" data-toggle="tab" href="#nav-cancellation_return_{{$key}}" role="tab" aria-controls="nav-cancellation" aria-selected="false">Cancellation Rules</a>
                        </div>
                     </nav>
                     <div class="tab-content">
                        <div class="tab-pane fade show active" id="nav-flightInformation_return_{{$key}}" role="tabpanel" aria-labelledby="nav-flightInformation-tab">
                           <div class="inner_flight_tabs">
                              <div class="flght_data_info">
                                 <div class="row">
                                    <div class="col-md-2">
                                       <div class="plane_data">
                                          <img src="/images/{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}.gif" alt="{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}">
                                       </div>
                                       <span class="flight_name">{{ $flight['Segments'][0][0]['Airline']['AirlineName'] }}</span>
                                       <span class="flight_serial_number">{{ $flight['Segments'][0][0]['Airline']['AirlineCode'] }}  {{ $flight['Segments'][0][0]['Airline']['FlightNumber'] }}</span>
                                    </div>
                                    <div class="col-md-3 text-right">
                                       <div class="city_time">{{ $flight['Segments'][0][0]['Origin']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][0][0]['Origin']['DepTime'])) }})</span></div>
                                       <div class="airpot_name_data">{{ $flight['Segments'][0][0]['Origin']['Airport']['AirportName'] }},  {{ $flight['Segments'][0][0]['Origin']['Airport']['CountryName'] }}</div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                       <div class="flight_duration">
                                          <span>{{ intdiv($flight['Segments'][0][0]['Duration'], 60).'h '. ($flight['Segments'][0][0]['Duration'] % 60) }}m</span> <i class="fa fa-plane" aria-hidden="true"></i>
                                       </div>
                                       <div class="flg_tech">Flight Duration</div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="city_time">{{ $flight['Segments'][0][0]['Destination']['Airport']['AirportCode'] }} <span>({{ date('H:i' , strtotime($flight['Segments'][0][0]['Destination']['ArrTime'])) }})</span></div>
                                       <div class="airpot_name_data">{{ $flight['Segments'][0][0]['Destination']['Airport']['AirportName'] }},  {{ $flight['Segments'][0][0]['Destination']['Airport']['CountryName'] }}</div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane fade" id="nav-fare_return_{{$key}}" role="tabpanel" aria-labelledby="nav-fare-tab">
                           <div class="inner_flight_tabs">
                              <table class="table">
                                 <tr>
                                    <td>Base Fare (1 Adult)</td>
                                    <td>{{ $flight['Fare']['Currency'] }} {{ number_format($flight['Fare']['BaseFare'],2) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Taxes and Fees (1 Adult)</td>
                                    <td>{{ $flight['Fare']['Currency'] }} {{ number_format($flight['Fare']['Tax'],2) }}</td>
                                 </tr>
                                 <tr>
                                    <th>Total Fare (1 Adult)</th>
                                    <th>{{ $flight['Fare']['Currency'] }} {{ number_format($flight['Fare']['OfferedFare'],2) }}</th>
                                 </tr>
                              </table>
                           </div>
                        </div>
                        <div class="tab-pane fade" id="nav-baggage_return_{{$key}}" role="tabpanel" aria-labelledby="nav-baggage-tab">
                           <div class="inner_flight_tabs">
                              <table class="table">
                                 <tr>
                                    <td>Baggage Type</td>
                                    <td>Check-In</td>
                                    <td>Cabin</td>
                                 </tr>
                                 <tr>
                                    <td>Adult</td>
                                    <td>{{ $flight['Segments'][0][0]['Baggage'] }}</td>
                                    <td>{{ $flight['Segments'][0][0]['CabinBaggage'] }}</td>
                                 </tr>
                              </table>
                              <!-- <p class="baggage_data">* Only 1 check-in baggage allowed per passenger. You can buy excess baggage as allowed by the airline, however you might have to pay additional charges at airport.</p> -->
                           </div>
                        </div>
                        <div class="tab-pane fade" id="nav-cancellation_return_{{$key}}" role="tabpanel" aria-labelledby="nav-cancellation-tab">
                           <div class="inner_flight_tabs">
                              <div class="row">
                                 <div class="col-md-6">
                                    <table class="table">
                                       <tr>
                                          <td>Goibibo Fee</td>
                                          <td>₹300</td>
                                       </tr>
                                       <tr>
                                          <td>0-2 hours</td>
                                          <td>Non Refundable</td>
                                       </tr>
                                       <tr>
                                          <td>>2 hours</td>
                                          <td>Non Refundable</td>
                                       </tr>
                                    </table>
                                 </div>
                                 <div class="col-md-6">
                                    <table class="table">
                                       <tr>
                                          <td>Goibibo Fee</td>
                                          <td>₹300</td>
                                       </tr>
                                       <tr>
                                          <td>0-2 hours</td>
                                          <td>Non Refundable</td>
                                       </tr>
                                       <tr>
                                          <td>>2 hours</td>
                                          <td>Non Refundable</td>
                                       </tr>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  @endforeach
                  @endif
               </div>
               <!-- Code Ends -->
            </div>
         </div>
         <!-- LIsting Ends -->
      </div>
      @else
        <div class="col-md-9  oneway">
          <h1> No flights found for {{ $input['from'] }} to {{ $input['to'] }} for {{ $input['departDate'] }}. <br>
            <h3>{{ $flights['Error']['ErrorMessage'] }}.</h3>
          </h1>
        </div>
      @endif
   </div>
</section>
@endsection