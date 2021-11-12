@extends('layouts.app-header')
@section('content')   
<section class="listing_banner_forms">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="listing_inner_forms">
              <form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_hotels') }}"  >
                @csrf
               <div class="rounding_form_info">
                  <div class="form-group">
                     <label>City Name / Area Name <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                     <select name="city_name" class="hotel-city" required>
                        <option value="Dubai">Dubai</option>
                     </select>
                     <input type="hidden" name="city_id" id="city_id" value="{{ $input['city_id'] }}">
                      <input type="hidden" name="countryCode" id="countryCode" value="{{ $input['countryCode'] }}">
                      <input type="hidden" name="countryName" id="countryName" value="{{ $input['countryName'] }}">
                      <input type="hidden" name="country" id="country" value="{{ $input['countryCode'] }}">
                      <input type="hidden" name="currency" id="currency" value="{{ $input['currency'] }}">
                  </div>
                  <div class="form-group">
                     <label>Check In <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                     <div class="input-group" >
                        <input class="form-control departdate" type="text" name="departdate" required readonly value="{{ $input['departdate'] }}" />
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Check Out <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                     <div class="input-group" >
                        <input class="form-control returndate" type="text" name="returndate" readonly required value="{{ $input['returndate'] }}" />
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Room &amp; Guests <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                      <input type="text" name="roomsGuests" id="roomsGuests" readonly class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ $input['roomsGuests'] }}">
                      <div class="roomsGuests" >
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
                                       <li data-cy="1" class="selected">1</li>
                                       <li data-cy="2" class="">2</li>
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
                                       <li data-cy="3" class="">3</li>
                                       <li data-cy="4" class="">4</li>
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
                                       <li class="childAgeSelector " id="childAgeSelector3Room1">
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
                                       <li class="childAgeSelector " id="childAgeSelector4Room1">
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
                                    <input type="hidden" name="adultCountRoom1" id="adultCountRoom1" value="1">
                                    <input type="hidden" name="childCountRoom1" id="childCountRoom1" value="0">
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
                  </div>
                  <div class="search_btns_listing"><button type="submit" class="btn btn-primary">Search</button></div>
               </div>
             </form>
            </div>
         </div>
      </div>
   </div>
</section>
<section class="listing_title_map">
   <div class="container">
      <div class="row align-items-center">
         <div class="col-md-8">
            <div class="listing_main_title">
               <h1>Hotels, Flights, Cars and more in {{ $input['countryName'] }}</h1>
               <div class="srt_by_data">
                @if(isset($hotels['HotelSearchResult']) && isset($hotels['HotelSearchResult']['ResponseStatus']) && $hotels['HotelSearchResult']['ResponseStatus'] == 1)
                  <ul>
                     <li>Sort by:</li>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="popularity_data" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Popularity</a>
                        <div class="dropdown-menu" aria-labelledby="popularity_data">
                           <a class="dropdown-item" href="#">Popularity</a>
                           <a class="dropdown-item" href="#">Price - Low to High</a>
                           <a class="dropdown-item" href="#">Price - High to Low</a>
                           <a class="dropdown-item" href="#">User Rating - High to Low</a>
                        </div>
                     </li>
                      <li>Showing {{ sizeof($hotels['HotelSearchResult']['HotelResults']) }} hotels in {{ $input['countryName'] }}</li>
                  </ul>
                @endif
               </div>
            </div>
         </div>
         <div class="col-md-4">
            <div class="map_vews">
                <div id="map"></div>
                @if(isset($hotels['HotelSearchResult']) && isset($hotels['HotelSearchResult']['ResponseStatus']) && $hotels['HotelSearchResult']['ResponseStatus'] == 1)
                <script type="text/javascript">
                let map;
                  map = new google.maps.Map(document.getElementById("map"), {
                    center: {
                      lat: 25.276987,
                      lng: 55.296249
                    },
                    zoom: 7
                  });
                  var markerLoc = {lat: 25.276987, lng: 55.296249};
                  var marker = new google.maps.Marker({
                    position: markerLoc,
                    map: map,
                    title: "<?php echo $hotels['HotelSearchResult']['HotelResults'][0]['HotelName']; ?>"
                  });
                        
                </script>
                @endif
               <div class="search_location_data">
                  <input type="search" name="search" placeholder="Enter location or hotel name">
                  <input type="submit" name="submit" class="btn btn-primary">
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<section class="listing_section_data">
   <div class="container">
      <div class="row">
         <div class="col-md-3">
            <div class="listing_sidebar">
               <h3>Select Filters</h3>
               <div class="popularity_filters_items">
                  <h4>Price Range</h4>
                  <div class="check_filter_cont">
                     <div class="slidecontainer">
                        <label class="filters-label">Price</label>
                        <input type="range" min="500" max="50000" value="50000" class="slider" id="pirceRange">
                        <br>
                        <span class="range-price max">50000</span>
                      </div>
                  </div>
               </div>
               <div class="popularity_filters_items">
                  <h4>Locality</h4>
                  <div class="check_filter_cont">
                     <label class="container_airline">  
                     <span class="airline_name">
                     <span class="air_name_set">Bur Dubai</span>
                     </span>                    
                     <input type="checkbox">
                     <span class="checkmark"></span>
                     <span class="value_numbers">(36)</span>
                     </label>
                     <label class="container_airline">  
                     <span class="airline_name">
                     <span class="air_name_set">Deira</span>
                     </span>                    
                     <input type="checkbox">
                     <span class="checkmark"></span>
                     <span class="value_numbers">(173)</span>
                     </label>
                     <label class="container_airline">  
                     <span class="airline_name">
                     <span class="air_name_set">Dubai Marina</span>
                     </span>                    
                     <input type="checkbox">
                     <span class="checkmark"></span>
                     <span class="value_numbers">(46)</span>
                     </label>
                     <label class="container_airline">  
                     <span class="airline_name">
                     <span class="air_name_set">Media City</span>
                     </span>                    
                     <input type="checkbox">
                     <span class="checkmark"></span>
                     <span class="value_numbers">(30)</span>
                     </label>
                  </div>
               </div>
               <div class="popularity_filters_items">
                  <h4>Star Category</h4>
                  <div class="check_filter_cont">
                     <label class="container_airline">  
                     <span class="airline_name">
                     <span class="air_name_set">Unrated</span>
                     </span>                    
                     <input type="checkbox">
                     <span class="checkmark"></span>
                     <span class="value_numbers">(26)</span>
                     </label>
                     <label class="container_airline">  
                     <span class="airline_name">
                     <span class="air_name_set">5 Stars</span>
                     </span>                    
                     <input type="checkbox">
                     <span class="checkmark"></span>
                     <span class="value_numbers">(102)</span>
                     </label>
                     <label class="container_airline">  
                     <span class="airline_name">
                     <span class="air_name_set">3 Stars</span>
                     </span>                    
                     <input type="checkbox">
                     <span class="checkmark"></span>
                     <span class="value_numbers">(161)</span>
                     </label>
                  </div>
               </div>
               <div class="popularity_filters_items">
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
               </div>
            </div>
         </div>
         <div class="col-md-9">
            <div class="listing_items_data">
              <?php $counter = 0; ?>
              @if(isset($hotels['HotelSearchResult']) && isset($hotels['HotelSearchResult']['ResponseStatus']) && $hotels['HotelSearchResult']['ResponseStatus'] == 1)
                @foreach($hotels['HotelSearchResult']['HotelResults'] as $key => $hotel)
                 <?php $hotel_static_info = \App\Models\StaticDataHotels::where(['hotel_code' => $hotel['HotelCode']])->first(); 

                 ?>
                 <div class="listing_item" data-rating="{{ $hotel['StarRating'] }}" data-price="{{ $hotel['Price']['OfferedPriceRoundedOff'] }}" data-code="{{ $hotel['HotelCode'] }}">
                    <div class="row">
                       <div class="col-md-9">
                          <div class="listing_description_data">
                             <div class="listing_slider_thumbnail">
                                <div id="custCarouse{{$key}}" class="carousel slide" data-ride="carousel" align="center">
                                   <!-- slides -->
                                   <div class="carousel-inner">
                                    @if(isset($hotel_static_info['hotel_images']))
                                      <?php $hotel_images = json_decode($hotel_static_info['hotel_images'], true) ?>
                                      @if(!empty($hotel_images))
                                        @foreach($hotel_images as $hm_key => $hm)
                                          <div class="hotel-pic carousel-item @if($hm_key == 0) active @endif"> 
                                            <img src="{{ $hm }}" alt="slide"> 
                                          </div>
                                        @endforeach
                                          <a class="carousel-control-prev" href="#custCarouse{{$key}}" data-slide="prev">
                                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                                          </a>
                                          <a class="carousel-control-next" href="#custCarouse{{$key}}" data-slide="next">
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                          </a>
                                      @else
                                        <div class="carousel-item active "> 
                                          <img src="{{ $hotel['HotelPicture'] }}" alt="slide"> 
                                        </div>
                                      @endif
                                    @else   
                                        <div class="carousel-item active "> 
                                          <img src="{{ $hotel['HotelPicture'] }}" alt="slide"> 
                                        </div>
                                    @endif                                   
                                   </div>
                                   <!-- Thumbnails -->
                                   @if(isset($hotel_static_info['hotel_images']))
                                      <?php $hotel_images = json_decode($hotel_static_info['hotel_images'], true) ?>
                                      @if(!empty($hotel_images))
                                        <ol class="carousel-indicators list-inline">
                                          @foreach($hotel_images as $hmt_key => $hmt)
                                            <li class="list-inline-item @if($hmt_key == 0) active @endif"> 
                                              <a id="carousel-selector-{{ $hmt_key }}" class="selected" data-slide-to="{{ $hmt_key }}" data-target="#custCarouse{{ $hmt_key }}"> 
                                                <img src="{{ $hmt }}" class="img-fluid">
                                              </a>
                                            </li>
                                          @endforeach
                                        </ol>
                                      @endif
                                    @endif
                                </div>
                             </div>
                             <div class="listing_main_desp">
                                @if(isset($hotel_static_info))
                                  <h2>{{ $hotel_static_info['hotel_name'] }} 
                                    <?php $hotel_address = json_decode($hotel_static_info['hotel_address'], true); ?>
                                    @if(isset($hotel_address['CountryName']))
                                      <span class="country_name">{{$hotel_address['CountryName']['$']}}</span>
                                    @endif
                                  </h2>
                                @else
                                  <h2>{{ $hotel['HotelName'] }} 
                                    <span class="country_name">{{$hotel['HotelAddress']}}</span>
                                  </h2>
                                @endif

                                @if(isset($hotel_static_info))
                                  <?php $ad = json_decode($hotel_static_info['hotel_address'], true) ?>
                                  @if(isset($ad['AddressLine']))
                                    @if(isset($ad['AddressLine'][0]))
                                      <p class="distance_data"> {{ $ad['AddressLine'][0] }}</p>
                                    @else
                                      <p class="distance_data"> {{ $ad['CityName'] }}, {{ $ad['PostalCode'] }}</p>
                                    @endif
                                  @endif
                                @endif
                                <div class="listing_tags_data">
                                  @if(isset($hotel_static_info))
                                  <?php $f = json_decode($hotel_static_info['hotel_facilities'], true) ?>
                                    @foreach($f as $f_key => $facility)
                                      @if($f_key < 5)
                                        <a href="javascript:void(0);" class="listing_kids_option">{{ $facility }}</a>
                                      @endif
                                    @endforeach
                                  @endif
                                </div>
                                @if(isset($hotel_static_info))
                                  <?php $hc = json_decode($hotel_static_info['hotel_contact'], true) ?>
                                  @if(isset($hc) && isset($hc['ContactNumber']) && isset($hc['ContactNumber'][0]) && isset($hc['ContactNumber'][0]['@PhoneNumber']))
                                    <div class="assured_list">
                                       <a href="tel:{{ $hc['ContactNumber'][0]['@PhoneNumber'] }}" class="country_assured"> <span class="fa fa-phone"></span> {{ $hc['ContactNumber'][0]['@PhoneNumber'] }}</a>
                                    </div>
                                  @endif
                                @endif
                                <div class="crtified_options"><a href="javascript:void();">Hotel Themes</a></div>
                                <div class="safety_features">
                                   <ul class="list-inline">
                                    @if(isset($hotel_static_info))
                                      <?php $ht = json_decode($hotel_static_info['hotel_type'], true) ?>
                                      @if(isset($ht))
                                        @foreach($ht as $hotel_theme)
                                          @if(isset($hotel_theme) && isset($hotel_theme['@ThemeName']))
                                            <li>
                                              <i class="fa fa-check" aria-hidden="true"></i> {{ $hotel_theme['@ThemeName'] }}
                                            </li>
                                          @endif
                                        @endforeach
                                      @endif
                                    @endif
                                   </ul>
                                </div>
                                <div class="more_options">
                                   <p>More Options: 
                                    @if(isset($hotel_static_info))
                                      <?php $h_time = json_decode($hotel_static_info['hotel_time'], true) ?>
                                      @if(isset($h_time) && !empty($h_time) && isset($h_time['@CheckInTime']))
                                        <a href="javascript:void(0);">
                                          Checkin Time {{ $h_time['@CheckInTime'] }}
                                        </a>
                                        |
                                        <a href="javascript:void(0);">
                                          Checkout Time {{ $h_time['@CheckOutTime'] }}
                                        </a>
                                      </p>
                                      @endif
                                    @endif
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="col-md-3">
                          <div class="listing_rice_info">
                            @if(($hotel['Price']['PublishedPriceRoundedOff'] - $hotel['Price']['OfferedPriceRoundedOff']) > 0 )
                             <p class="save_price_discount">Save {{$hotel['Price']['CurrencyCode']}} {{ number_format(($hotel['Price']['PublishedPriceRoundedOff'] - $hotel['Price']['OfferedPriceRoundedOff']),2) }} <span class="badge">{{ ($hotel['Price']['PublishedPriceRoundedOff'] - $hotel['Price']['OfferedPriceRoundedOff']) / 100 }}%</span></p>
                            @endif
                             <p class="cutof_price">{{$hotel['Price']['CurrencyCode']}} {{ number_format($hotel['Price']['PublishedPriceRoundedOff'],2) }}</p>
                             <div class="actual_price">
                                <h2>{{$hotel['Price']['CurrencyCode']}} {{ number_format($hotel['Price']['OfferedPriceRoundedOff'],2) }}</h2>
                                <p>Per night<span>Total {{$hotel['Price']['CurrencyCode']}} {{ number_format(($hotel['Price']['RoomPrice'] * $input['NoOfNights']),2) }} + Taxes</span></p>
                             </div>
                             <div class="no_cost_emi">
                                <p>No Cost <span class="emi_data">EMI</span></p>
                                <p>Starts at <span class="emi_price">{{$hotel['Price']['CurrencyCode']}}  {{ number_format(($hotel['Price']['RoomPrice'] * $input['NoOfNights']),2) }}</span></p>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
                @endforeach
              @else
                <h1> No hotels found for {{ $input['countryName'] }} from {{ $input['departdate'] }} to {{ $input['returndate'] }} </h1>
              @endif
            </div>
         </div>
      </div>
   </div>
</section>
@endsection