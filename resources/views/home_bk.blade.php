@extends('layouts.app-header')
@section('content')   
<section class="banner_data">
   <div class="container">
      <div class="row">
        <input type="hidden" id="isHome" value="1">
         <div class="col-md-12">
            <div class="slider_test_heading">
               <h1>Find deals on hotels, homes, and much more...</h1>
               <p>From cozy country homes to funky city apartments</p>
            </div>
            <div class="form_tab_data active" id="flights">
               <ul class="nav nav-tabs">
                  <li class="nav-item trip-type" data-type="1">
                     <a class="nav-link active" data-toggle="tab" href="#oneway">One Way</a>
                  </li>
                  <li class="nav-item trip-type" data-type="2">
                     <a class="nav-link" data-toggle="tab" href="#round">Round Trip</a>
                  </li>
               </ul>
               <!-- Tab panes -->
               <div class="tab-content">
                  <div id="oneway" class="tab-pane active">
                     <input type="hidden" id="tripType" value="2">
                     <form action="{{ route('search_flights') }}" id="searchFlightsForm"  method="GET">
                        @csrf
                        <input type="hidden" name="JourneyType" id="JourneyType" value="1">
                        <div class="round_triping_data">
                           <h4>Book Domestic and International flights</h4>
                           <div class="rounding_form_info">
                              <div class="form-group">
                                 <label>From</label>
                                 <select name="origin" class="depart-from">
                                    <option value="DEL">Delhi</option>
                                 </select>
                                 <input type="hidden" name="from" id="from-city" value="Delhi">
                                 <div class="small_station_info from-city">Delhi (DEL) IN</div>
                                 <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>
                              </div>
                              <div class="form-group">
                                 <label>To</label>
                                 <select name="destination" class="depart-to">
                                    <option value="BOM">Mumbai</option>
                                 </select>
                                 <div class="small_station_info to-city">BOM, Chhatrapati Shivaji Maharaj International Airport</div>
                                 <input type="hidden" name="to" id="to-city" value="Mumbai">
                                 <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>
                              </div>
                              <div class="form-group">
                                 <label>Departure <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                 <div class="input-group" >
                                    <input class="form-control departdate" type="text" name="departDate" required readonly value="{{ date('d-m-Y') }}"/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                 </div>
                                 <div class="small_station_info departDay">Sunday</div>
                              </div>
                              <div class="form-group not-allowed" id="not-allowed">
                                 <label>Return <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                 <div class="input-group" >
                                    <input class="form-control returndate" type="text" name="returnDate" required readonly value="{{ date('d-m-Y') }}"/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                 </div>
                                 <div class="small_station_info returnDay">Monday</div>
                              </div>
                              <div class="form-group">
                                 <label>Traveller &amp; Class</label>
                                 <input type="text" name="travellersClass" id="travellersClassOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="1 Traveller">
                                  <div class="travellers gbTravellers travellersClassOne">
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
                                 <div class="small_station_info class_info blk-font">All Cabin Classes</div>
                              </div>
                           </div>
                           <!-- <div class="trending_searches">
                              <label>Trending Searches:</label>
                              <ul class="list-inline">
                                 <li><a href="javascript:void(0);">Chennai <img src="{{ asset('images/black_right_arrow.png')}}" alt="icon">Mumbai</a></li>
                                 <li><a href="javascript:void(0);">Chennai <img src="{{ asset('images/black_right_arrow.png')}}" alt="icon">Hyderabad</a></li>
                                 <li><a href="javascript:void(0);">Chennai <img src="{{ asset('images/black_right_arrow.png')}}" alt="icon">Delhi</a></li>
                              </ul>
                           </div> -->
                            <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                            <input type="hidden" name="adultsF" class="adultsF" value="1">
                            <input type="hidden" name="childsF" class="childsF" value="0">
                            <input type="hidden" name="infants" class="infantsF" value="0">
                            <input type="hidden" name="FlightCabinClass" class="FlightCabinClass" value="0">
                            <input type="hidden" name="DirectFlight" class="DirectFlight" value="false">
                            <input type="hidden" name="OneStopFlight" class="OneStopFlight" value="false">
                           <div class="search_btns">
                              <button type="submit" class="btn btn-primary">Search <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
            <div class="form_tab_data" id="hotels">
               <ul class="nav nav-tabs">
                  <li class="nav-item">
                     <a class="nav-link active" >Hotel Search</a>
                  </li>
               </ul>
               <!-- Tab panes -->
               <div class="tab-content">
                  <div class="tab-pane active">
                     <div class="round_triping_data hotels">
                        <h4>Book Domestic and International Hotels</h4>
                        <form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_hotels') }}"  >
                           @csrf
                           <div class="rounding_form_info">
                              <!-- <div class="form-group">
                                 <label>Nationality</label>
                                 <select name="country" class="auto-complete nationality" required></select>
                                 <input type="hidden" name="currency" id="currency" value="INR">
                                 <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>
                              </div> -->
                              <div class="form-group">
                                 <label>City Name / Hotel Name</label>
                                 <select name="city_name_select" class="auto-complete hotel-city" required>
                                 </select>
                                 <input type="hidden" name="city_id" id="city_id" value="115936">
                                 <input type="hidden" name="city_name" id="city_name" value="Dubai">
                                 <input type="hidden" name="countryCode" id="country_code" value="AE">
                                 <input type="hidden" name="countryName" id="country_name" value="AE">
                                 <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="">
                                 <div class="small_station_info selected-hotel-city"></div>
                                 <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>
                              </div>
                              <div class="form-group">
                                 <label>Checkin <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                 <div class="input-group">
                                    <input id="departHotel" class="form-control departdate" type="text" name="departdate" required readonly value="{{ date('d-m-Y') }}"/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    <span class="total-nights">1 night</span>
                                 </div>
                                 <div class="small_station_info departDay">Sunday</div>
                              </div>
                              <div class="form-group">
                                 <label>Checkout <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                 <div class="input-group ">
                                    <input id="returnHotel" class="form-control returndate" type="text" name="returndate" readonly required value="{{ date('d-m-Y') }}"/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                 </div>
                                 <div class="small_station_info returnDay">Monday</div>
                              </div>
                              <div class="form-group">
                                 <label>Rooms &amp; Guests</label>
                                 <input type="text" name="roomsGuests" id="roomsGuests" readonly class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="1 Room 2 Guest">
                                 <div class="roomsGuests" style="left:10px !important;">
                                    <div class="roomsGuestsTop">
                                       <div data-cy="roomRow1" id="room1" class="addRoomRow">
                                          <div class="addRoomLeft">
                                             <p data-cy="roomNum1" class="darkText font16 latoBlack capText" id="roomNo1">Room 1</p>
                                          </div>
                                          <div class="addRoomRight">
                                             <div class="addRooomDetails">
                                                <p class="appendBottom15 makeFlex spaceBetween"><span data-cy="adultRange" class="latoBold font12 grayText">ADULTS (12y +) </span>
                                                   <a data-cy="removeButton-1" id="removeRoom1" class="font12 appendLeft250 remove-room-btn" href="javascript:void(0);" data-room="1" style="display: none;">Remove </a>
                                                </p>
                                                <ul id="adultsCount1" class="adultsCount guestCounter font12 darkText">
                                                   <li data-cy="1" class="">1</li>
                                                   <li data-cy="2" class="selected">2</li>
                                                   <li data-cy="3" class="">3</li>
                                                   <li data-cy="4" class="">4</li>
                                                   <li data-cy="5" class="">5</li>
                                                   <li data-cy="6" class="">6</li>
                                                   <li data-cy="7" class="">7</li>
                                                   <li data-cy="8" class="">8</li>
                                                   <li data-cy="9" class="">9</li>
                                                   <li data-cy="10" class="">10</li>
                                                   <li data-cy="11" class="">11</li>
                                                   <li data-cy="12" class="">12</li>
                                                </ul>
                                                <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">CHILDREN (Age 12y and below)</p>
                                                <ul id="childCount1" class="childCount guestCounter font12 darkText">
                                                   <li data-cy="0" class="selected">0</li>
                                                   <li data-cy="1" class="">1</li>
                                                   <li data-cy="2" class="">2</li>
                                                </ul>
                                                <ul class="childAgeList appendBottom10">
                                                   <li class="childAgeSelector " id="childAgeSelector1Room1">
                                                      <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">CHILD 1 AGE</span>
                                                      <label class="lblAge" for="0">
                                                         <select id="child1AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge1[]">
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
                                                   <li class="childAgeSelector " id="childAgeSelector2Room1">
                                                      <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">CHILD 2 AGE</span>
                                                      <label class="lblAge" for="0">
                                                         <select id="child2AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge1[]">
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
                                                </ul>
                                                <input type="hidden" name="adultCountRoom1" id="adultCountRoom1" value="2">
                                                <input type="hidden" name="childCountRoom1" id="childCountRoom1" value="0">
                                                <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="roomsGuestsBottom">
                                       <button data-cy="addAnotherRoom" id="addAnotherRoom" type="button" class="btnAddRoom">+ ADD ANOTHER ROOM</button>
                                       <button data-cy="submitGuest" type="button" id="applyBtn" class="primaryBtn btnApply">APPLY</button>
                                    </div>
                                 </div>
                                 <input type="hidden" name="roomCount" id="roomCount" value="1">
                                 <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                              </div>
                           </div>
                     </div>
                     <div class="trending_searches">
                     </div>
                     <div class="search_btns">
                     <button type="submit" class="btn btn-primary">Search <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
                     </div>
                     </form>
                  </div>
               </div>
            </div>
            <div class="form_tab_data" id="activities">
              <ul class="nav nav-tabs">
                  <li class="nav-item">
                     <a class="nav-link active" >Activities Search</a>
                  </li>
               </ul>
               <div class="tab-content">
                  <div class="tab-pane active">
                     <div class="round_triping_data">
                        <h4>Book Activities</h4>
                        <form method="GET" name="searchForm" id="searchActivityForm" action="{{ route('search_activities') }}"  >
                           @csrf
                          <div class="rounding_form_info">
                            <div class="form-group">
                               <label>Start Date <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                               <div class="input-group">
                                  <input id="departHotel" class="form-control departdateAct" type="text" name="travelstartdate" required readonly value="{{ date('d-m-Y') }}"/>
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                               </div>
                            </div>
                            <div class="form-group">
                               <label>City Name / Area Name</label>
                               <select name="city_name" class="auto-complete act-city" required>
                               </select>
                               <input type="hidden" name="city_act_id" id="city_act_id" value="126632">
                               <input type="hidden" name="currency_code_act" id="currency_code_act" value="GB">
                               <div class="small_station_info selected-hotel-city"></div>
                               <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>
                            </div> 
                            <!-- <div class="form-group">
                               <label>Sightseeing Start Date <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                               <div class="input-group">
                                  <input id="departHotel" class="form-control departdateAct" type="text" name="SSstartDate" required readonly value="{{ date('d-m-Y') }}"/>
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                               </div>
                            </div> -->
                            <div class="form-group">
                             <label>Traveller</label>
                             <input type="text" name="travellersClass" id="travellersClassactOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="1 Traveller">
                              <div class="travellers gbTravellers travellersClassactOne">
                                 <div class="appendBottom20">
                                    <p data-cy="adultRange" class="latoBold font12 grayText appendBottom10">ADULTS (12y +)</p>
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
                                          <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">CHILDREN (Age 12y and below)</p>
                                          <ul id="childCount1" class="childCountCab guestCounter font12 darkText clCCA">
                                             <li data-cy="0" class="selected">0</li>
                                             <li data-cy="1" class="">1</li>
                                             <li data-cy="2" class="">2</li>
                                             <li data-cy="3" class="">3</li>
                                             <li data-cy="4" class="">4</li>
                                          </ul>
                                          <ul class="childAgeList appendBottom10">
                                             <li class="childAgeSelector " id="childAgeSelector1Cab1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">CHILD 1 AGE</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child1AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge1[]">
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
                                             <li class="childAgeSelector " id="childAgeSelector2Cab1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">CHILD 2 AGE</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child2AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge1[]">
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
                                             <li class="childAgeSelector " id="childAgeSelector3Cab1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">CHILD 3 AGE</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child3AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge1[]">
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
                                             <li class="childAgeSelector " id="childAgeSelector4Cab1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">CHILD 4 AGE</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child4AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge1[]">
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
                                          </ul>
                                       </div>
                                    </div>
                                    <div class="makeFlex appendBottom25">
                                       <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">APPLY</button>
                                    </div>
                                 </div>
                              </div>
                            </div>
                            <!-- Ends Here -->
                          </div>
                        <div class="trending_searches"> </div>
                        <div class="search_btns">
                          <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                          <input type="hidden" name="adultsCCA" class="adultsCCA" value="1">
                          <input type="hidden" name="childsCCA" class="childsCCA" value="0">
                          <button type="submit" class="btn btn-primary">Search <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
                       </div>
                     </form>
                  </div>
               </div>
            </div>
            </div>
            <div class="form_tab_data" id="cabs">
               <ul class="nav nav-tabs">
                  <li class="nav-item">
                     <a class="nav-link active" >Cabs Search</a>
                  </li>
               </ul>
              <div class="tab-content">
                  <div class="tab-pane active">
                     <div class="round_triping_data">
                        <h4>Book Domestic and International Cabs</h4>
                        <form method="GET" name="searchForm" id="searchCabsForm" action="{{ route('search_cabs') }}"  >
                           @csrf
                          <div class="rounding_form_info">
                            <div class="form-group">
                               <label>City Name / Area Name</label>
                               <select name="city_name" class="auto-complete cab-city" required>
                               </select>
                               <input type="hidden" name="city_cab_id" id="city_cab_id" value="115936">
                               <input type="hidden" name="currency_code" id="currency_code" value="GB">
                               <input type="hidden" name="country_code_value" id="country_code_value" value="IN">
                               <input type="hidden" name="pick_up_point_name" id="pick_up_point_name" value="">
                               <input type="hidden" name="drop_off_point_name" id="drop_off_point_name" value="">
                               <div class="small_station_info selected-hotel-city"></div>
                               <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>
                            </div>
                            <div class="form-group">
                               <label>Pick Up</label>
                               <select name="pick_up" id="pick_up_type" class="select_pickup" required>
                                  <option value="">Select One</option>
                                  <option value="0">Accommodation</option>
                                  <option value="1">Airport</option>
                                  <option value="2">Train Station</option>
                                  <option value="3">Sea Port</option>
                                  <!-- <option value="4">Other</option> -->
                               </select>
                            </div>
                            <div class="form-group">
                               <label>Drop Off</label>
                               <select name="drop_off" id="drop_off_type" class="select_dropoff" required>
                                  <option value="">Select One</option>
                                  <option value="0">Accommodation</option>
                                  <option value="1">Airport</option>
                                  <option value="2">Train Station</option>
                                  <option value="3">Sea Port</option>
                                  <!-- <option value="4">Other</option> -->
                               </select>
                            </div>
                            <div class="form-group non_acc_city">
                               <label>Pick Up Point</label>
                               <select name="pick_up_point" id="pick_up_point" class="pickup-city">
                                <option value=''>Select Location</option>
                               </select>
                            </div>
                            <div class="form-group accom_city">
                               <label>Pick Up Point</label>
                              <select name="pick_up_point_acc" class="auto-complete pick_up_point_auto">
                               </select>
                            </div>
                            <div class="form-group non_acc_city_drop">
                               <label>Drop Off Point</label>
                               <select name="drop_off_point" id="drop_off_point" class="dropoff-city">
                                <option value=''>Select Location</option>
                               </select>
                            </div>
                            <!-- For Accom -->
                            <div class="form-group accom_city_drop">
                               <label>Drop Off Point</label>
                               <select name="drop_off_point_acc" class="auto-complete drop_off_point_auto">
                               </select>
                            </div>
                            <!-- Ends Here -->
                          </div>
                          <div class="rounding_form_info">
                            
                            <div class="form-group">
                               <label>Travel Date <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                               <div class="input-group">
                                  <input id="departHotel" class="form-control departdateCab" type="text" name="transferdate" required readonly value="{{ date('d-m-Y') }}"/>
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                               </div>
                            </div>
                            <div class="form-group">
                              <label>Travel Time <i class="fa fa-angle-down" aria-hidden="true"></i></label>
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
                            <div class="form-group">
                               <label>Preffered Language</label>
                               <select name="preffered_language" class="select_preffered_lang" required>
                                  <option value="">NotSpecified</option>
                                  <option value="1">Arabic</option>
                                  <option value="2">Cantinese</option>
                                  <option value="3">Danish</option>
                                  <option value="4">English</option>
                                  <option value="5">French</option>
                                  <option value="6">German</option>
                                  <option value="7">Hebrew</option>
                                  <option value="8">Italian</option>
                                  <option value="9">Japanese</option>
                                  <option value="10">Korean</option>
                                  <option value="11">Mandrain</option>
                                  <option value="12">Portuguese</option>
                                  <option value="13">Russian</option>
                                  <option value="14">Spanish</option>
                               </select>
                            </div>
                            <div class="form-group">
                             <label>Traveller</label>
                             <input type="text" name="travellersClass" id="travellersClassCabOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="1 Traveller">
                              <div class="travellers gbTravellers travellersClassCabOne">
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
                                       <div class="makeFlex column childCounter col-md-12">
                                          <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">CHILDREN (Age 12y and below)</p>
                                          <ul id="childCount1" class="childCountCabOr guestCounter font12 darkText clCF">
                                             <li data-cy="0" class="selected">0</li>
                                             <li data-cy="1" class="">1</li>
                                             <li data-cy="2" class="">2</li>
                                             <li data-cy="3" class="">3</li>
                                             <li data-cy="4" class="">4</li>
                                          </ul>
                                          <ul class="childAgeList appendBottom10">
                                             <li class="childAgeSelector " id="childAgeSelector1CabOr1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">CHILD 1 AGE</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child1AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge1">
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
                                             <li class="childAgeSelector " id="childAgeSelector2CabOr1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">CHILD 2 AGE</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child2AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge2">
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
                                             <li class="childAgeSelector " id="childAgeSelector3CabOr1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">CHILD 3 AGE</span>
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
                                             <li class="childAgeSelector " id="childAgeSelector4CabOr1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">CHILD 4 AGE</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child4AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge4">
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
                                          </ul>
                                       </div>
                                    </div>
                                    <div class="makeFlex appendBottom25">
                                       <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">APPLY</button>
                                    </div>
                                 </div>
                              </div>
                            </div>
                            <div class="form-group">
                               <label>Preffered Currency</label>
                               <select name="preffered_currency" class="select_preffered_currency" required>
                                  <option value="USD" >United States Dollars</option>
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
                                  <option value="INR" selected="selected">India Rupees</option>
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
                          <div class="rounding_form_info">
                          </div>  
                        <div class="trending_searches"> </div>
                        <div class="search_btns">
                          <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                          <input type="hidden" name="adultsFC" id="adultsFC" value="1">
                          <input type="hidden" name="childsFC" id="childsFC" value="0">
                          <input type="hidden" name="alternate_language" class="" value="4">
                          <input type="hidden" name="country" class="country_val" value="IN">
                          <button type="submit" class="btn btn-primary">Search <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
                       </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>
</section>
<!-- <section class="ads_upper_data">
   <div class="container">
      <img class="img-fluid" src="{{ asset('images/ad_banner.png')}}" alt="ad">
   </div>
</section> -->
<!-- <section class="special_offer_data">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="popular_heading_data">
               <h2>Special Offers</h2>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="owl-carousel owl-theme special_flights owl-btn-same">
               <div class="item">
                  <div class="inner_special_data">
                     <div class="special_img_data">
                        <img src="{{ asset('images/special_offer_01.png')}}" alt="special">
                     </div>
                     <div class="special_inner_cont">
                        <h3>Yay! BOOKINGS OPEN for Flights to the USA & London...</h3>
                        <p>On Air India, for Indian citizens with valid Visas.</p>
                        <a href="javascript:void(0);" class="btn btn-primary">Book Now</a>
                     </div>
                  </div>
               </div>
               <div class="item">
                  <div class="inner_special_data">
                     <div class="special_img_data">
                        <img src="{{ asset('images/special_offer_02.png')}}" alt="special">
                     </div>
                     <div class="special_inner_cont">
                        <h3>Yay! BOOKINGS OPEN for Flights to the USA & London...</h3>
                        <p>On Air India, for Indian citizens with valid Visas.</p>
                        <a href="javascript:void(0);" class="btn btn-primary">Book Now</a>
                     </div>
                  </div>
               </div>
               <div class="item">
                  <div class="inner_special_data">
                     <div class="special_img_data">
                        <img src="{{ asset('images/special_offer_03.png')}}" alt="special">
                     </div>
                     <div class="special_inner_cont">
                        <h3>Yay! BOOKINGS OPEN for Flights to the USA & London...</h3>
                        <p>On Air India, for Indian citizens with valid Visas.</p>
                        <a href="javascript:void(0);" class="btn btn-primary">Book Now</a>
                     </div>
                  </div>
               </div>
               <div class="item">
                  <div class="inner_special_data">
                     <div class="special_img_data">
                        <img src="{{ asset('images/special_offer_01.png')}}" alt="special">
                     </div>
                     <div class="special_inner_cont">
                        <h3>Yay! BOOKINGS OPEN for Flights to the USA & London...</h3>
                        <p>On Air India, for Indian citizens with valid Visas.</p>
                        <a href="javascript:void(0);" class="btn btn-primary">Book Now</a>
                     </div>
                  </div>
               </div>
               <div class="item">
                  <div class="inner_special_data">
                     <div class="special_img_data">
                        <img src="{{ asset('images/special_offer_02.png')}}" alt="special">
                     </div>
                     <div class="special_inner_cont">
                        <h3>Yay! BOOKINGS OPEN for Flights to the USA & London...</h3>
                        <p>On Air India, for Indian citizens with valid Visas.</p>
                        <a href="javascript:void(0);" class="btn btn-primary">Book Now</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="view_all_btns text-center"><a href="javascript:void(0);" class="btn btn-primary all_news_btn">Explore all offers</a></div>
         </div>
      </div>
   </div>
</section> -->
<?php //echo "<pre>"; print_r($cities);?>
<section class="state_flight_info">
   <div class="container">
    <h1 class="text-center">Popular Searches</h1>
      <div class="row">
        <?php foreach ($cities as $key => $value) {?>
         <div class="col-md-4">
          <form method="GET" name="searchForm" id="searchRoomsFormMain<?php echo $key; ?>" action="{{ route('search_hotels') }}"  >
            @csrf
            <input type="hidden" name="city_id"  value="<?php echo $value['CityId']; ?>">
            <input type="hidden" name="city_name"  value="<?php echo $value['CityName']; ?>">
            <input type="hidden" name="countryCode"  value="<?php echo $value['CountryCode']; ?>">
            <input type="hidden" name="countryName"  value="<?php echo $value['Country']; ?>">
            <input type="hidden" name="departdate"  value="{{ date('d-m-Y') }}" />
            <input type="hidden" name="returndate"  value="{{ date('d-m-Y', strtotime('+1 days')) }}" />
            <input type="hidden" name="adultCountRoom1"  value="2">
            <input type="hidden" name="childCountRoom1"  value="0">
            <input type="hidden" name="roomsGuests"  value="1 Room 2 Guest">
            <input type="hidden" name="roomCount"  value="1">
            <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
            <div class="state_flight_inner_info">
              @if($key == 0)
               <img src="{{ asset('images/amersdame.jfif')}}" class="home-page-search-img" alt="<?php echo $value['CityName']; ?> Hotels" width="200px" height="130px">
              @elseif($key == 1)
                <img src="{{ asset('images/dubai.jfif')}}" class="home-page-search-img" alt="<?php echo $value['CityName']; ?> Hotels" width="200px" height="130px">
              @elseif($key == 2)
                <img src="{{ asset('images/LA.jfif')}}" class="home-page-search-img" alt="<?php echo $value['CityName']; ?> Hotels" width="200px" height="130px">
              @elseif($key == 3)
                <img src="{{ asset('images/paris.jfif')}}" class="home-page-search-img" alt="<?php echo $value['CityName']; ?> Hotels" width="200px" height="130px">
              @elseif($key == 4)
                <img src="{{ asset('images/prauge.jfif')}}" class="home-page-search-img" alt="<?php echo $value['CityName']; ?> Hotels" width="200px" height="130px">
              @elseif($key == 5)
                <img src="{{ asset('images/tel-aviv.jfif')}}" class="home-page-search-img" alt="<?php echo $value['CityName']; ?> Hotels" width="200px" height="130px">
              @endif

               <div class="inner_chenn_data">
                  <h4><span id="bookHotelRandom<?php echo $key; ?>"  style="color:blue;text-decoration: underline;cursor: pointer;"><?php echo $value['CityName']; ?> Hotels</span></h4>
                  <p><?php echo $value['CityName'];?>, <?php echo $value['Country']." (".$value['CountryCode'].")";;?></p>
               </div>
            </div>
          </form>
         </div>
         <?php } ?>
      </div>
   </div>
</section>
<!-- <section class="popular_international_flights">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="popular_heading_data">
               <h2>Popular International Flight Routes</h2>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="owl-carousel owl-theme popular_flights owl-btn-same">
               <div class="item">
                  <div class="inner_popular_data">
                     <div class="pick_point">
                        <h4>New Delhi <span>Thu, 17 Sep</span></h4>
                        <h4>Singapore</h4>
                     </div>
                     <div class="border_dot"></div>
                     <div class="price_data">
                        <p>Starting from</p>
                        <h2>₹ 4,835</h2>
                     </div>
                  </div>
               </div>
               <div class="item">
                  <div class="inner_popular_data">
                     <div class="pick_point">
                        <h4>New Delhi <span>Thu, 17 Sep</span></h4>
                        <h4>Singapore</h4>
                     </div>
                     <div class="border_dot"></div>
                     <div class="price_data">
                        <p>Starting from</p>
                        <h2>₹ 4,835</h2>
                     </div>
                  </div>
               </div>
               <div class="item">
                  <div class="inner_popular_data">
                     <div class="pick_point">
                        <h4>New Delhi <span>Thu, 17 Sep</span></h4>
                        <h4>Singapore</h4>
                     </div>
                     <div class="border_dot"></div>
                     <div class="price_data">
                        <p>Starting from</p>
                        <h2>₹ 4,835</h2>
                     </div>
                  </div>
               </div>
               <div class="item">
                  <div class="inner_popular_data">
                     <div class="pick_point">
                        <h4>New Delhi <span>Thu, 17 Sep</span></h4>
                        <h4>Singapore</h4>
                     </div>
                     <div class="border_dot"></div>
                     <div class="price_data">
                        <p>Starting from</p>
                        <h2>₹ 4,835</h2>
                     </div>
                  </div>
               </div>
               <div class="item">
                  <div class="inner_popular_data">
                     <div class="pick_point">
                        <h4>New Delhi <span>Thu, 17 Sep</span></h4>
                        <h4>Singapore</h4>
                     </div>
                     <div class="border_dot"></div>
                     <div class="price_data">
                        <p>Starting from</p>
                        <h2>₹ 4,835</h2>
                     </div>
                  </div>
               </div>
               <div class="item">
                  <div class="inner_popular_data">
                     <div class="pick_point">
                        <h4>New Delhi <span>Thu, 17 Sep</span></h4>
                        <h4>Singapore</h4>
                     </div>
                     <div class="border_dot"></div>
                     <div class="price_data">
                        <p>Starting from</p>
                        <h2>₹ 4,835</h2>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section> -->
<!-- <section class="travel_news">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="heading_text text-center">
               <h2>Travel News</h2>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-4">
            <div class="travel_blog_data">
               <div class="blog_img"><img class="img-fluid" src="{{ asset('images/blog_01.png')}}" alt="blog"></div>
               <div class="blog_content">
                  <h3>Your One-Stop Resource For Country-Wise Travel Guidelin...</h3>
                  <a href="javascript:void(0);" class="read_more_btn">Read More...</a>
               </div>
            </div>
         </div>
         <div class="col-md-4">
            <div class="travel_blog_data">
               <div class="blog_img"><img class="img-fluid" src="{{ asset('images/blog_02.png')}}" alt="blog"></div>
               <div class="blog_content">
                  <h3>Your One-Stop Resource For Country-Wise Travel Guidelin...</h3>
                  <a href="javascript:void(0);" class="read_more_btn">Read More...</a>
               </div>
            </div>
         </div>
         <div class="col-md-4">
            <div class="travel_blog_data">
               <div class="blog_img"><img class="img-fluid" src="{{ asset('images/blog_03.png')}}" alt="blog"></div>
               <div class="blog_content">
                  <h3>Your One-Stop Resource For Country-Wise Travel Guidelin...</h3>
                  <a href="javascript:void(0);" class="read_more_btn">Read More...</a>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="view_all_btns text-center"><a href="javascript:void(0);" class="btn btn-primary all_news_btn">View all news</a></div>
         </div>
      </div>
   </div>
</section> -->
<!-- <section class="about_trip_payment">
   <div class="container">
      <div class="row">
         <div class="col-md-8">
            <div class="about_trip_data">
               <h2>Why My Trip?</h2>
               <p>7 Brilliant reasons Trip should be your one-stop-shop!</p>
               <h5>Book Flights, Hotels, Trains, Buses, Cruise and Holiday Packages</h5>
               <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</p>
               <a href="javascript:void(0);" class="read_more_btn">Read More...</a>
            </div>
         </div>
         <div class="col-md-4">
            <div class="payment_method">
               <h2>Security &amp; Payments</h2>
               <img class="img-fluid" src="{{ asset('images/payment_method.png')}}" alt="payment">
            </div>
         </div>
      </div>
   </div>
</section> -->
@endsection