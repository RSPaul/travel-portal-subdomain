@extends('layouts.app-header')
@section('content') 
<section class="listing_banner_forms" >
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="listing_inner_forms">
                    <form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_hotels')}}"  >
                        @csrf
                        <div class="rounding_form_info">
                            <div class="form-group">
                                <label><label>{{ __('labels.cityarea')}}</label> <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                <select name="city_name_select" class="hotel-city" required disabled>
                                    <option value="{{ str_replace('+', ' ', $input_data['city_name'])}}">{{ str_replace("+", " ", $input_data['city_name'])}}</option>
                                </select>

                                <input type="hidden" name="city_id" id="city_id" value="{{ $queryValues['city_id']}}">
                                <input type="hidden" name="city_name" id="city_name" value="{{ $queryValues['city_name']}}">
                                <input type="hidden" name="countryCode" id="country_code" value="{{ $queryValues['countryCode']}}">
                                <input type="hidden" name="countryName" id="country_name" value="{{ $queryValues['countryName']}}">
                                <input type="hidden" name="country" id="country" value="{{ $queryValues['country']}}">
                                <input type="hidden" name="currency" id="currency" value="{{ $queryValues['currency']}}">
                                <input type="hidden" name="referral" id="referral" value="{{ $queryValues['referral']}}">
                                <input type="hidden" name="preffered_hotel" value="{{ $hotel_code}}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('labels.checkin')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                <div class="input-group" >
                                    <input class="form-control departdate" type="text" name="departdate" required readonly value="{{ $input_data['checkIn']}}" />
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('labels.checkout')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                <div class="input-group" >
                                    <input class="form-control returndate" type="text" name="returndate" readonly required value="{{ $input_data['checkOut']}}" />
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('labels.roomguests')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                                <input type="text" name="roomsGuests" id="roomsGuests" readonly class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ $input_data['guests']}}">
                                <div class="roomsGuests" >
                                    <div class="roomsGuestsTop">
                                        <div data-cy="roomRow1" id="room1" class="addRoomRow">
                                            <div class="addRoomLeft">
                                                <p data-cy="roomNum1" class="darkText font16 latoBlack capText" id="roomNo1">{{ __('labels.room')}} 1</p>
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
                                                    <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.childrenage')}} (Age 12y and below)</p>
                                                    <ul id="childCount1" class="childCount guestCounter font12 darkText">
                                                        <li data-cy="0" class="selected">0</li>
                                                        <li data-cy="1" class="">1</li>
                                                        <li data-cy="2" class="">2</li>

                                                    </ul>
                                                    <ul class="childAgeList appendBottom10">
                                                        <li class="childAgeSelector " id="childAgeSelector1Room1">
                                                            <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child1age')}}</span>
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
                                                            <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child2age')}}</span>
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
                                                    @for($i = 1; $i <= Session::get('noofrooms'); $i++)
                                                    <input type="hidden" name="adultCountRoom{{$i}}" id="adultCountRoom{{$i}}" value="{{Session::get('hotelSearchInput')['adultCountRoom' . $i] }}">
                                                    <input type="hidden" name="childCountRoom{{$i}}" id="childCountRoom{{$i}}" value="{{Session::get('hotelSearchInput')['childCountRoom' . $i] }}">
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="roomsGuestsBottom">
                                        <button data-cy="addAnotherRoom" id="addAnotherRoom" type="button" class="btnAddRoom">+ {{ __('labels.add_another_room')}}</button>
                                        <button data-cy="submitGuest" type="button" id="applyBtn" class="primaryBtn btnApply">{{ __('labels.apply')}}</button>
                                    </div>
                                </div>
                                <input type="hidden" name="roomCount" id="roomCount" value="{{ $input_data['roomCount']}}">
                            </div>
                            <div class="search_btns_listing"><button type="submit" class="btn btn-primary">{{ __('labels.search')}}</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>  
<section class="listing_banner_forms no-index">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="listing_inner_forms">
                    <div class="rounding_form_info">
                        <p class="text-center"> 
                            @if(isset($hotel) && isset($hotel['hotel_name']))
                            <span class=""><i class="fa fa-building"></i> {{ $hotel['hotel_name']}}</span>
                            @endif
                            <span class="">{{ $input_data['city_name']}}</span> 
                            From <span class=""><i class="fa fa-calendar"></i>  {{ $input_data['checkIn']}}</span>
                            To <span class=""><i class="fa fa-calendar"></i>  {{ $input_data['checkOut']}}</span>
                            Travllers <span class=""><i class="fa fa-user"></i>  {{ $input_data['guests']}}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<a href="#" class="float mobileOnlyView" data-toggle="modal" data-target="#roomFilterModal">
    <i class="fa fa-filter my-float"></i>
</a>

<div ng-app="hotelApp" ng-controller="roomCtrl" ng-init="getRooms()" id="hotelViewPage">

    <section class="room_filters mt-4 desktopOnlyView" id="desktopRoomFilters">
        <div class="container">
            <div class="col-md-12 inner_about_hotels_data attr" >
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="slidecontainer" >
                                <label class="filters-label">{{ __('labels.price')}}</label>
                                <input type="range" min="500" max="500000" value="50000" class="slider" id="pirceRangeRooms" ng-change="filterByPrice(priceRange)" ng-model="priceRange">
                                <br>
                                <span class="range-price max"> @{{ priceRange | number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="form-group filter-checkbox col-md-4">
                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="room" class="room-type-checkbox" data-on="Rooms" data-off="Rooms" data-height="25" data-width="150">
                            </div>
                            <div class="form-group filter-checkbox col-md-4">
                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="suite" class="room-type-checkbox" data-on="Suite" data-off="Suite" data-height="25" data-width="150">
                                </label>
                            </div>
                            <div class="form-group filter-checkbox col-md-4">
                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="apartment" class="room-type-checkbox" data-on="Apartments" data-off="Apartments" data-height="25" data-width="150">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" ng-show="!loaded">
                        <div class="profile-info">
                            <div class="block block--short load-animate"></div>
                            <div class="block block--short load-animate"></div>
                            <div class="block block--short load-animate"></div>
                        </div>
                    </div>
                    <div class="col-md-12" ng-show="loaded">
                        <div class="row">
                            <span class="filter-text">{{ __('labels.filter_rooms_by')}}&nbsp;&nbsp;</span>
                            <ul class="inclusion-filters">
                                <li ng-repeat="inclusion in inclusion_array" data-include="@{{ inclusion}}" ng-bind="inclusion" class="room-filter-li" ng-if="inclusion">
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="roomFilterModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roomFilterModalLabel">{{ __('labels.room_filters')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <section class="room_filters mt-4">
                        <div class="container">
                            <div class="col-md-12  attr" >
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="slidecontainer" >
                                                <label class="filters-label">{{ __('labels.price')}}</label>
                                                <input type="range" min="500" max="500000" value="50000" class="slider" id="pirceRangeRooms" ng-change="filterByPrice(priceRange)" ng-model="priceRange">
                                                <br>
                                                <span class="range-price max"> @{{ priceRange | number }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="form-group filter-checkbox col-md-4">
                                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="room" class="room-type-checkbox" data-on="Rooms" data-off="Rooms" data-height="25" data-width="150">
                                            </div>
                                            <div class="form-group filter-checkbox col-md-4">
                                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="suite" class="room-type-checkbox" data-on="Suite" data-off="Suite" data-height="25" data-width="150">
                                                </label>
                                            </div>
                                            <div class="form-group filter-checkbox col-md-4">
                                                <input type="checkbox" name="room_type" data-toggle="toggle" data-style="android" value="apartment" class="room-type-checkbox" data-on="Apartments" data-off="Apartments" data-height="25" data-width="150">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" ng-show="!loaded">
                                        <div class="profile-info">
                                            <div class="block block--short load-animate"></div>
                                            <div class="block block--short load-animate"></div>
                                            <div class="block block--short load-animate"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" ng-show="loaded">
                                        <div class="row">
                                            <span class="filter-text">{{ __('labels.filter_rooms_by')}}&nbsp;&nbsp;</span>
                                            <ul class="inclusion-filters">
                                                <li ng-repeat="inclusion in inclusion_array" data-include="@{{ inclusion}}" ng-bind="inclusion" class="room-filter-li" ng-if="inclusion">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <section class="hotel_detail_sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="inner_hotel_yep">
                        <div class="room-photo-gallery">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-4  col-sm-6 p-1">
                                            <div class="hotel_box room-hcol ht_imgs">
                                                <a href="{{ $hotel['hotel_images'][0]}}" class="hotel_url show-me second-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ $hotel['hotel_images'][0]}}"  >
                                                </a>
                                                <a href="{{ $hotel['hotel_images'][1]}}" class="hotel_url  show-me third-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ $hotel['hotel_images'][1]}}"  >
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-sm-12  p-1">
                                            <div class="hotel_box room-vcol ht_imgs">
                                                <a href="{{ $hotel['hotel_images'][2]}}" class="hotel_url  show-me primary-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ $hotel['hotel_images'][2]}}"  >
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row room-thumbs" >
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                <a href="{{ $hotel['hotel_images'][3]}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ $hotel['hotel_images'][3]}}"  >
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                <a href="{{ $hotel['hotel_images'][4]}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ $hotel['hotel_images'][4]}}"  >
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                <a href="{{ $hotel['hotel_images'][5]}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ $hotel['hotel_images'][5]}}"  >
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                <a href="{{ $hotel['hotel_images'][6]}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ $hotel['hotel_images'][6]}}"  >
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb">
                                                <div class="ht_imgs">
                                                    <div class="hotel_box room-thumb">
                                                        @php $r_images = array(); $roomImagesCount = 0; @endphp
                                                        @foreach($roomImages as $room_img)
                                                        @if($room_img->images && $room_img->images != null && !empty($room_img->images))
                                                        @php 
                                                        array_push($r_images , unserialize($room_img->images));
                                                        @endphp
                                                        @endif
                                                        @endforeach
                                                        @php $counter = 0; @endphp
                                                        @foreach($r_images as $r_key => $r_imgs)
                                                        @foreach($r_imgs as $r_key => $r_img)
                                                        @if(strpos($r_img, "_z") !== false)
                                                        <a href="{{ $r_img}}" title="{{ $hotel['hotel_name']}}" class="hotel_url @if($counter == 0) show-me @endif">
                                                            <img  class="hotel_photo img-responsive" src="{{ $r_img}}" alt="hotel">
                                                        </a>
                                                        @php 
                                                        $counter++; $roomImagesCount++; 
                                                        @endphp
                                                        @endif
                                                        @endforeach
                                                        @endforeach
                                                    </div>
                                                    @if($roomImagesCount > 0)
                                                    <div class="overlay_text_md" style="height: 100%;border-radius: 5px;" href="{{ $r_img}}" title="{{ $hotel['hotel_name']}}" >
                                                        <a style="padding: 30% 20% !important;color:#fff;display: block;" href="{{ $r_img}}">+ {{$roomImagesCount}} Photos</a>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="right_hotel_specification" >
                                        <div class="hotel_room_prcs" style="width:100%;">
                                            <div class="room_ht_dets">
                                                @if($input_data['roomCount'] == '1')
                                                <div class="room_upper_data hide" ng-show="rooms.length">
                                                    <div class="room_lft_data">
                                                        <h3 ng-bind="rooms[0].RoomTypeName"></h3>
                                                        <ul class="list-inline room_features">
                                                            <li class="non_refundable"><i class="fa fa-minus-circle" aria-hidden="true"></i> Non Refundable</li>
                                                            <li class="room_only_text"><i class="fa fa-check-circle" aria-hidden="true"></i> <span ng-bind="rooms[0].sub_rooms[0].RatePlanName"></span></li>
                                                        </ul>
                                                    </div>
                                                    <div class="room_rt_data">
                                                       <!-- <p class="cut_price" ng-bind="rooms[0].sub_rooms[0].FinalPrice | currency:rooms[0].sub_rooms[0].Price.CurrencyCode"></p> -->
                                                        <h4 class="main_room_price" ng-bind="rooms[0].sub_rooms[0].FinalPrice | currency:rooms[0].sub_rooms[0].Price.CurrencyCode"></h4>
                                                        <!-- <p ng-if="(rooms[0].sub_rooms[0].FinalPrice - rooms[0].sub_rooms[0].FinalPrice) > 1" class="deal_applies">Deal Applied<br> Saving <span ng-bind=" ( rooms[0].sub_rooms[0].FinalPrice | currency:rooms[0].sub_rooms[0].Price.CurrencyCode"></span></p> -->
                                                    </div>
                                                </div>
                                                <div class="room_upper_data" ng-show="!rooms.length">
                                                    <div class="container">
                                                        <div class="content-section">
                                                            <div class="content-info side">
                                                                <div class="block block--long load-animate"></div>
                                                                <div class="block block--long load-animate"></div>
                                                                <div class="block block--long load-animate"></div>
                                                                <div class="block block--long load-animate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="room_bottom_features" ng-show="rooms.length">
                                                    <h5 ng-show="rooms.length" >Other Rooms</h5>
                                                    <a  ng-show="rooms.length" href="javascript:void();" class="btn btn_book_now" ng-click="scrollTo('room-list')">Book this now</a>
                                                </div>
                                                <div class="room_bottom_features" ng-show="!rooms.length">
                                                    <div class="container">
                                                        <div class="content-section">
                                                            <div class="content-info side" >
                                                                <div class="block block--long load-animate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="room_upper_data hide" ng-show="rooms && rooms['rooms_0'].length">
                                                    <div class="room_lft_data">
                                                        <h3 ng-bind="rooms['rooms_0'][0].RoomTypeName"></h3>
                                                        <ul class="list-inline room_features">
                                                            <li class="non_refundable"><i class="fa fa-minus-circle" aria-hidden="true"></i> Non Refundable</li>
                                                            <li class="room_only_text"><i class="fa fa-check-circle" aria-hidden="true"></i> <span ng-bind="rooms['rooms_0'][0].RatePlanName"></span></li>
                                                        </ul>
                                                    </div>
                                                    <div class="room_rt_data">
                                                       <!-- <p class="cut_price" ng-bind="rooms['rooms_0'][0].Price.PublishedPrice + ( commission / 100 * rooms['rooms_0'][0].Price.PublishedPrice ) | currency:rooms['rooms_0'][0].Price.CurrencyCode"></p> -->
                                                        <h4 class="main_room_price" ng-bind="rooms['rooms_0'][0].FinalPrice | currency:rooms['rooms_0'][0].Price.CurrencyCode"></h4>
                                                        <!-- <p ng-if="(rooms['rooms_0'][0].Price.PublishedPrice - rooms['rooms_0'][0].Price.OfferedPriceRoundedOff) > 1" class="deal_applies">Deal Applied<br> Saving <span ng-bind=" ( rooms['rooms_0'][0].Price.PublishedPrice + ( commission / 100 * rooms['rooms_0'][0].Price.PublishedPrice ) ) - ( rooms['rooms_0'][0].Price.OfferedPriceRoundedOff  + ( commission / 100 * rooms['rooms_0'][0].Price.OfferedPriceRoundedOff ) ) | currency:rooms['rooms_0'][0].Price.CurrencyCode"></span></p> -->
                                                    </div>
                                                </div>
                                                <div class="room_upper_data" ng-show="!rooms">
                                                    <div class="container">
                                                        <div class="content-section">
                                                            <div class="content-info side">
                                                                <div class="block block--long load-animate"></div>
                                                                <div class="block block--long load-animate"></div>
                                                                <div class="block block--long load-animate"></div>
                                                                <div class="block block--long load-animate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="room_bottom_features" ng-show="rooms">
                                                    <h5 ng-show="rooms" >Other Rooms</h5>
                                                    <a  ng-show="rooms" href="javascript:void();" class="btn btn_book_now" ng-click="scrollTo('room-list')">Book this now</a>
                                                </div>
                                                <div class="room_bottom_features" ng-show="!rooms">
                                                    <div class="container">
                                                        <div class="content-section">
                                                            <div class="content-info side" >
                                                                <div class="block block--long load-animate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <!--room details close-->
                                            <div class="room_review_ratings">
                                                <!--Review Rating start-->
                                                @if(isset($hotel['hotel_type']))
                                                <h3>Hotel Themes </h3>
                                                <div class="safety_features">
                                                    <ul class="list-inline" >
                                                        @foreach($hotel['hotel_type'] as $type)
                                                        <li ng-repeat="type in hotel.static_data.hotel_type">
                                                            <i class="fa fa-check" aria-hidden="true"></i> 
                                                            @if(isset($type) && isset($type['@ThemeName']))
                                                            {{ $type['@ThemeName']}}
                                                        </li>
                                                        @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                                <h3>User Rating &amp; Reviews </h3>
                                                <p>
                                                    <span class="fa fa-star @if($input_data['ratings'] >= 1) checked @endif"></span>
                                                    <span class="fa fa-star @if($input_data['ratings'] >= 2) checked @endif"></span>
                                                    <span class="fa fa-star @if($input_data['ratings'] >= 3) checked @endif"></span>
                                                    <span class="fa fa-star @if($input_data['ratings'] >= 4) checked @endif"></span>
                                                    <span class="fa fa-star @if($input_data['ratings'] >= 5) checked @endif"></span>
                                                </p>
                                            </div>
                                            <!--Review Rating close-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="room_type_data mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" id="roomCount" value="{{ $input_data['roomCount']}}">
                    <input type="hidden" id="supplierIds" value="{{ $supplierIds}}">
                    @if($input_data['roomCount'] == '1')
                    <!-- New Html 1 Dec  -->
                    <div ng-show="loaded" id="room-list">
                        <div class="row rooms-tr" data-price="@{{ roomList.FinalPrice}}" data-meal="@{{ roomList.RatePlanName || 'No Meals' }}" ng-repeat="roomList in rooms" data-name="@{{ roomList.RoomTypeName}}" ng-init="room_key = $index">
                            <div class="col-lg-12">
                                <div class='rooms-options'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class='room-list-title'> @{{ roomList.RoomTypeName}}</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 15px;">
                                        <div class="col-lg-3">
                                            <div class="room-photo">
                                                <span ng-if="roomList.sub_rooms[0].images.length && roomList.sub_rooms[0].images[0].indexOf('http') != -1">
                                                    <img style="width:100%;" ng-click="showRoomDetails($index)" src="@{{ roomList.sub_rooms[0].images[0]}}" alt="@{{ roomList.RoomTypeName}}" class="img-responsive"  />
                                                </span>

                                                <span ng-if="roomList.sub_rooms[0].images.length && roomList.sub_rooms[0].images[0].indexOf('http') == -1">
                                                    <img  style="width:100%;" ng-click="showRoomDetails($index)" src="/uploads/rooms/{{ $hotel_code}}/@{{ roomList.sub_rooms[0].images[0]}}" alt="@{{ roomList.RoomTypeName}}" class="img-responsive" />
                                                </span>

                                                <img  style="width:100%;" ng-click="showRoomDetails($index)" src="https://b2b.tektravels.com/Images/HotelNA.jpg" alt="@{{ roomList.RoomTypeName}}" class="img-responsive" ng-if="!roomList.sub_rooms[0].images.length" />
                                            </div>
                                            <div style="padding:10px 20px 20px 20px;">
                                                <div class="tw-text-sm">
                                                    <ul class="list-inline room_influences">
                                                        <li ng-repeat="roomBd in roomList.sub_rooms[0].BedTypes">@{{ roomBd.BedTypeDescription}}</li>
                                                        <li ng-repeat="am in roomList.Inclusion">@{{ am}}</li>
                                                    </ul>
                                                    <div class="more_room_detail">
                                                        <a href='javascript:void(0);' ng-click="showRoomDetails($index)">{{ __('labels.more_about_room')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Room Details Modal -->
                                        <div id="roomModal_@{{ $index}}" class="modal fade" role="dialog">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <p class="room-heading">@{{ roomList.RoomTypeName}} <button type="button" class="close" data-dismiss="modal">&times;</button></p>
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <div id="custCarouse_@{{ $index}}" class="carousel slide" data-ride="carousel" align="center">
                                                                    <ol class="carousel-indicators" >
                                                                        <li  ng-repeat="imgR in ::roomList.sub_rooms[0].images" ng-init="image_key = $index" data-target="#custCarouse_@{{ room_key}}"  data-slide-to="@{{ image_key}}" ng-class="image_key < 1 ? 'active' : ''"></li>
                                                                    </ol>
                                                                    <div class="carousel-inner" >
                                                                        <div ng-repeat="imgR in ::roomList.sub_rooms[0].images"  ng-init="image_key_s = $index" ng-class="image_key_s < 1 ? 'carousel-item active' : 'carousel-item'"> 
                                                                            <img class="room-image-slider" src="@{{ ::imgR}}"  alt="hotel" ng-if="roomList.sub_rooms[0].images[0].indexOf('http') != -1">
                                                                            <img class="room-image-slider" src="/uploads/rooms/{{ $hotel_code}}/@{{ ::imgR}}"  alt="hotel" ng-if="roomList.sub_rooms[0].images[0].indexOf('http') == -1">
                                                                        </div>
                                                                        <div ng-if="!roomList.sub_rooms[0].images.length" class="carousel-item active"> 
                                                                            <img class="room-image-slider" src="https://b2b.tektravels.com/Images/HotelNA.jpg"  alt="hotel">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <p class="room-details" ng-if="roomList.sub_rooms[0].RatePlanName">
                                                                    @{{ roomList.sub_rooms[0].RatePlanName}}
                                                                </p>
                                                                <div class="row">
                                                                    <div class="col-md-6 amenity" ng-repeat="am in roomList.sub_rooms[0].Amenity">
                                                                        <span><i class="fa fa-check" aria-hidden="true"></i>&nbsp;@{{ am}}</span>
                                                                    </div>
                                                                </div>
                                                                <p>
                                                                    Smooking: <span class="strong">@{{ roomList.sub_rooms[0].SmokingPreference}}</span>
                                                                </p>
                                                                <p ng-repeat="ams in roomList.sub_rooms[0].Amenities">
                                                                    Amenities: <span class="strong">@{{ ams.sub_rooms[0].Amenities[0]}}</span>
                                                                </p>
                                                                <p ng-if="roomList.sub_rooms[0].BedTypes && roomList.sub_rooms[0].BedTypes.length">
                                                                    Bed Type: <span class="strong">@{{ roomList.sub_rooms[0].BedTypes[0].BedTypeDescription}}</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p ng-bind-html="roomList.sub_rooms[0].RoomDescription"></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p>Cancellation Policy: @{{ roomList.sub_rooms[0].CancellationPolicy}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-9" >
                                            <div class="showOptionsDesktop">
                                                <div class="row" style="margin:8px;border-bottom:1px solid #ccc;">
                                                    <div class="col-lg-6">
                                                        <span class='room-label-title'> What’s Included</span>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class='room-label-title'> Total for stay</span>
                                                    </div>
                                                    <div class="col-lg-3"></div>
                                                </div>

                                                <div style="min-height: 100px;margin:10px;padding:0px;margin-bottom: 0px;border-bottom:1px solid #ccc;" class="row rooms-tr-meal price_dat_table sub_room-pr pr price-@{{ sub_room.FinalPrice}} index-@{{ room_key}} td-inclusion" data-meal="@{{ sub_room.RatePlanName || 'No Meals' }}"  ng-repeat="sub_room in roomList.sub_rooms" data-price="@{{ sub_room.FinalPrice}}" data-index="@{{ room_key}}" data-currency="@{{ sub_room.Price.CurrencyCode}}" data-room="@{{ sub_room.RoomIndex}}" data-include="@{{ tdClass(sub_room.Inclusion)}}">
                                                    <div class="col-lg-6 padd-right">
                                                        <span class="room-facility-text" ng-repeat="am in sub_room.Inclusion">@{{ am}}</span><br />
                                                        <a class="tw-text-sm" href="javascript:void(0);" data-toggle='modal' data-target='#roomCancel_@{{ sub_room.RoomIndex}}'>Cancellation Policy</a> 
                                                    </div>
                                                    <div id="roomCancel_@{{ sub_room.RoomIndex}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog  modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h3 ng-bind="sub_room.RoomTypeName"></h3>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p ng-bind="sub_room.CancellationPolicy"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="text-success room-price">@{{ sub_room.FinalPrice | currency: sub_room.Price.CurrencyCode }}</span> 
                                                        <div class="room-stay-text">
                                                            Total for {{ Session::get('noOfNights') }} nights
                                                            <br>
                                                            {{ $input_data['guests']}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <!----> 
                                                        <div style="min-height: 100px;" class="price_dat_table select_room_data td-inclusion">
                                                            <form method="POST" target="_blank" action="/room/index/{{$input_data['traceId']}}/{{ date('d-m-yy', strtotime($checkInDate))}}/{{ date('d-m-yy', strtotime($checkOutDate))}}/category/infor-source/{{$referral}}/room-indexes">
                                                                @csrf
                                                                <input type="hidden" name="category" value="@{{ sub_room.CategoryId}}">
                                                                <input type="hidden" name="room[RoomIndex]" value="@{{ sub_room.RoomIndex}}">
                                                                <input type="hidden" name="room[RoomTypeCode]" value="@{{ sub_room.RoomTypeCode}}">
                                                                <input type="hidden" name="room[RoomTypeName]" value="@{{ sub_room.RoomTypeName}}">
                                                                <input type="hidden" name="room[RatePlanCode]" value="@{{ sub_room.RatePlanCode}}">
                                                                <input type="hidden" name="room[BedTypeCode]" value="@{{ sub_room.BedTypeCode}}">
                                                                <input type="hidden" name="room[SmokingPreference]" value="@{{ sub_room.SmokingPreference}}">
                                                                <input type="hidden" name="room[Supplements]" value="@{{ sub_room.Supplements}}">
                                                                <input type="hidden" name="room[Price][CurrencyCode]" value="@{{ sub_room.Price.CurrencyCode}}">
                                                                <input type="hidden" name="room[Price][RoomPrice]" value="@{{ sub_room.Price.RoomPrice}}">
                                                                <input type="hidden" name="room[Price][Tax]" value="@{{ sub_room.Price.Tax}}">
                                                                <input type="hidden" name="room[Price][ExtraGuestCharge]" value="@{{ sub_room.Price.ExtraGuestCharge}}">
                                                                <input type="hidden" name="room[Price][ChildCharge]" value="@{{ sub_room.Price.ChildCharge}}">
                                                                <input type="hidden" name="room[Price][OtherCharges]" value="@{{ sub_room.Price.OtherCharges}}">
                                                                <input type="hidden" name="room[Price][Discount]" value="@{{ sub_room.Price.Discount}}">
                                                                <input type="hidden" name="room[Price][PublishedPrice]" value="@{{ sub_room.Price.PublishedPrice}}">
                                                                <input type="hidden" name="room[Price][PublishedPriceRoundedOff]" value="@{{ sub_room.Price.PublishedPriceRoundedOff}}">
                                                                <input type="hidden" name="room[Price][OfferedPrice]" value="@{{ sub_room.Price.OfferedPrice}}">
                                                                <input type="hidden" name="room[Price][OfferedPriceRoundedOff]" value="@{{ sub_room.Price.OfferedPriceRoundedOff}}">
                                                                <input type="hidden" name="room[Price][AgentCommission]" value="@{{ sub_room.Price.AgentCommission}}">
                                                                <input type="hidden" name="room[Price][AgentMarkUp]" value="@{{ sub_room.Price.AgentMarkUp}}">
                                                                <input type="hidden" name="room[Price][ServiceTax]" value="@{{ sub_room.Price.ServiceTax}}">
                                                                <input type="hidden" name="room[Price][TCS]" value="@{{ sub_room.Price.TCS}}">
                                                                <input type="hidden" name="room[Price][TDS]" value="@{{ sub_room.Price.TDS}}">
                                                                <button type="submit"  class="btn btn-primary" > Book Now</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion showOptionsMobile" id="accordionExample@{{room_key}}">
                                                <!--                                            <div class="card">-->
                                                <div class="row">

                                                    <div class="col-12" style="border-top:1px solid #ccc;border-bottom:1px solid #ccc;padding:10px;">
                                                        <button class="btn btn-success pull-right" type="button" data-toggle="collapse" data-target="#collapseOne@{{room_key}}" aria-expanded="true" aria-controls="collapseOne">
                                                            Show Options
                                                        </button>
                                                    </div>
                                                    <div class="col-12">
                                                        <div id="collapseOne@{{room_key}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample@{{room_key}}">
                                                            <!--                                                    <div class="card-body">-->
                                                            <div style="border-bottom:1px solid #ccc;min-height: 100px;padding-top:10px;padding-bottom: 10px;" class="row rooms-tr-meal sub_room-pr pr price-@{{ sub_room.FinalPrice}} index-@{{ room_key}} td-inclusion" data-meal="@{{ sub_room.RatePlanName || 'No Meals' }}"  ng-repeat="sub_room in roomList.sub_rooms" data-price="@{{ sub_room.FinalPrice}}" data-index="@{{ room_key}}" data-currency="@{{ sub_room.Price.CurrencyCode}}" data-room="@{{ sub_room.RoomIndex}}" data-include="@{{ tdClass(sub_room.Inclusion)}}">
                                                                <div class="col-12 padd-right">
                                                                    <span class="" ng-repeat="am in sub_room.Inclusion">@{{ am}}</span><br />
                                                                    <a class="tw-text-sm" href="javascript:void(0);" data-toggle='modal' data-target='#roomCancel1_@{{ sub_room.RoomIndex}}'>Cancellation Policy</a> 
                                                                </div>
                                                                <div id="roomCancel1_@{{ sub_room.RoomIndex}}" class="modal fade" role="dialog">
                                                                    <div class="modal-dialog   modal-dialog-centered modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h3 ng-bind="sub_room.RoomTypeName"></h3>
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p ng-bind="sub_room.CancellationPolicy"></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <span class="text-success room-price">@{{ sub_room.FinalPrice | currency: sub_room.Price.CurrencyCode }}</span> 
                                                                </div>
                                                                <div class="col-6 text-right">
                                                                    <!----> 
                                                                    <div  class="select_room_data td-inclusion">
                                                                        <form method="POST" target="_blank" action="/room/index/{{$input_data['traceId']}}/{{ date('d-m-yy', strtotime($checkInDate))}}/{{ date('d-m-yy', strtotime($checkOutDate))}}/category/infor-source/{{$referral}}/room-indexes">
                                                                            @csrf
                                                                            <input type="hidden" name="category" value="@{{ sub_room.CategoryId}}">
                                                                            <input type="hidden" name="room[RoomIndex]" value="@{{ sub_room.RoomIndex}}">
                                                                            <input type="hidden" name="room[RoomTypeCode]" value="@{{ sub_room.RoomTypeCode}}">
                                                                            <input type="hidden" name="room[RoomTypeName]" value="@{{ sub_room.RoomTypeName}}">
                                                                            <input type="hidden" name="room[RatePlanCode]" value="@{{ sub_room.RatePlanCode}}">
                                                                            <input type="hidden" name="room[BedTypeCode]" value="@{{ sub_room.BedTypeCode}}">
                                                                            <input type="hidden" name="room[SmokingPreference]" value="@{{ sub_room.SmokingPreference}}">
                                                                            <input type="hidden" name="room[Supplements]" value="@{{ sub_room.Supplements}}">
                                                                            <input type="hidden" name="room[Price][CurrencyCode]" value="@{{ sub_room.Price.CurrencyCode}}">
                                                                            <input type="hidden" name="room[Price][RoomPrice]" value="@{{ sub_room.Price.RoomPrice}}">
                                                                            <input type="hidden" name="room[Price][Tax]" value="@{{ sub_room.Price.Tax}}">
                                                                            <input type="hidden" name="room[Price][ExtraGuestCharge]" value="@{{ sub_room.Price.ExtraGuestCharge}}">
                                                                            <input type="hidden" name="room[Price][ChildCharge]" value="@{{ sub_room.Price.ChildCharge}}">
                                                                            <input type="hidden" name="room[Price][OtherCharges]" value="@{{ sub_room.Price.OtherCharges}}">
                                                                            <input type="hidden" name="room[Price][Discount]" value="@{{ sub_room.Price.Discount}}">
                                                                            <input type="hidden" name="room[Price][PublishedPrice]" value="@{{ sub_room.Price.PublishedPrice}}">
                                                                            <input type="hidden" name="room[Price][PublishedPriceRoundedOff]" value="@{{ sub_room.Price.PublishedPriceRoundedOff}}">
                                                                            <input type="hidden" name="room[Price][OfferedPrice]" value="@{{ sub_room.Price.OfferedPrice}}">
                                                                            <input type="hidden" name="room[Price][OfferedPriceRoundedOff]" value="@{{ sub_room.Price.OfferedPriceRoundedOff}}">
                                                                            <input type="hidden" name="room[Price][AgentCommission]" value="@{{ sub_room.Price.AgentCommission}}">
                                                                            <input type="hidden" name="room[Price][AgentMarkUp]" value="@{{ sub_room.Price.AgentMarkUp}}">
                                                                            <input type="hidden" name="room[Price][ServiceTax]" value="@{{ sub_room.Price.ServiceTax}}">
                                                                            <input type="hidden" name="room[Price][TCS]" value="@{{ sub_room.Price.TCS}}">
                                                                            <input type="hidden" name="room[Price][TDS]" value="@{{ sub_room.Price.TDS}}">
                                                                            <button type="submit"  class="btn btn-primary" > Book Now</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="room-stay-text">
                                                                        Total for {{ Session::get('noOfNights') }} nights
                                                                        <br>
                                                                        {{ $input_data['guests']}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--                                                    </div>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="inner_room_type_data" ng-show="loaded" id="room-list">
                        <div class="inner_about_hotels_data attr">
                            <div class="selected-rooms" ng-show="!error || error == ''">

                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="row" >
                                            <div class="col-lg-4 col-sm-12" style="padding:15px;">
                                                <h3 style="font-size:25px;font-weight: 700;">Total Cost For {{ $input_data['roomCount']}} Rooms:- </h3>
                                            </div>
                                            <div class="col-lg-4 col-6 text-center" style="padding:15px;">
                                                <span style="font-size:25px;font-weight: 700;" id="total-price"></span>
                                            </div>
                                            <div class="col-lg-4 col-6" style="padding:15px;">
                                                <form target="_blank" method="POST" action="/room/index/{{$input_data['traceId']}}/{{ date('d-m-yy', strtotime($checkInDate))}}/{{ date('d-m-yy', strtotime($checkOutDate))}}/category/infor-source/{{$referral}}/room-indexes">
                                                    <button type="submit" class="btn btn-primary book-btn pull-right" >Book Now</button>
                                                    <div class="" ng-repeat="rCount in roomCount">
                                                        @csrf
                                                        <input type="hidden" id="room_@{{rCount}}_category" name="category" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_RoomIndex" name="room[@{{rCount}}][RoomIndex]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_RoomTypeCode" name="room[@{{rCount}}][RoomTypeCode]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_RoomTypeName" name="room[@{{rCount}}][RoomTypeName]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_RatePlanCode" name="room[@{{rCount}}][RatePlanCode]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_BedTypeCode" name="room[@{{rCount}}][BedTypeCode]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_SmokingPreference" name="room[@{{rCount}}][SmokingPreference]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_Supplements" name="room[@{{rCount}}][Supplements]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_CurrencyCode" name="room[@{{rCount}}][Price][CurrencyCode]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_RoomPrice" name="room[@{{rCount}}][Price][RoomPrice]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_Tax" name="room[@{{rCount}}][Price][Tax]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_ExtraGuestCharge" name="room[@{{rCount}}][Price][ExtraGuestCharge]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_ChildCharge" name="room[@{{rCount}}][Price][ChildCharge]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_OtherCharges" name="room[@{{rCount}}][Price][OtherCharges]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_Discount" name="room[@{{rCount}}][Price][Discount]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_PublishedPrice" name="room[@{{rCount}}][Price][PublishedPrice]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_PublishedPriceRoundedOff" name="room[@{{rCount}}][Price][PublishedPriceRoundedOff]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_OfferedPrice" name="room[@{{rCount}}][Price][OfferedPrice]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_OfferedPriceRoundedOff" name="room[@{{rCount}}][Price][OfferedPriceRoundedOff]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_AgentCommission" name="room[@{{rCount}}][Price][AgentCommission]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_AgentMarkUp" name="room[@{{rCount}}][Price][AgentMarkUp]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_ServiceTax" name="room[@{{rCount}}][Price][ServiceTax]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_TCS" name="room[@{{rCount}}][Price][TCS]" value="">
                                                        <input type="hidden" id="room_@{{rCount}}_TDS" name="room[@{{rCount}}][Price][TDS]" value="">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <hr/>
                                    </div>
                                </div>

                                <!--                                <div class="row" style="margin-top:20px;">
                                                                    <div class="col-md-12">
                                                                        <div class="row">
                                                                            <div class="selected-room @if($input_data['roomCount'] == 2) col-md-6 @elseif($input_data['roomCount'] == 3) col-md-4 @elseif($input_data['roomCount'] == 4) col-md-3 @endif" ng-repeat="rCount in roomCount">
                                                                                Room <span ng-bind="rCount + 1"></span>: 
                                                                                <span id="room-@{{rCount}}">
                                                                                    @{{rooms['rooms_' + rCount][0].RoomTypeName}}
                                                                                </span>
                                                                                <br>
                                                                                <span>(Adults @{{ hotelSearchInput[rCount]['adults']}}, Childrens @{{ hotelSearchInput[rCount]['childs']}})</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                            </div>
                            <div class="row">
                                <input type="hidden" id="traceId" value="{{ $input_data['traceId']}}">
                                <input type="hidden" id="checkInDate" value="{{ date('d-m-yy', strtotime($checkInDate))}}">
                                <input type="hidden" id="checkOutDate" value="{{ date('d-m-yy', strtotime($checkOutDate))}}">
                                <input type="hidden" id="referral" value="{{ $referral}}">
                                <div ng-repeat="rCount in roomCount" class="@if($input_data['roomCount'] == 2) col-md-6 @elseif($input_data['roomCount'] == 3) col-md-4 @elseif($input_data['roomCount'] == 4) col-md-3 @endif">
                                    <div class="row"> 
                                        <div class="col-lg-12" style="font-weight: 600;">
                                            <div style="background:#ededed;padding:10px;">
                                                Room <span ng-bind="rCount + 1"></span>: 
                                                <span id="room-@{{rCount}}">
                                                    @{{rooms['rooms_' + rCount][0].RoomTypeName}}
                                                </span>
                                                <br>
                                                <span>(Adults @{{ hotelSearchInput[rCount]['adults']}}, Childrens @{{ hotelSearchInput[rCount]['childs']}})</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 60%;">Room Type</th>
                                                        <th style="width: 30%;">Price</th>
                                                        <th style="width: 10%;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="room in rooms['rooms_' + rCount]" ng-class="rCount == 0 ? 'first-column' : (combination == 'fixed') ? 'trow not-first-column' : ''" id="tr-@{{rCount}}-@{{ room.RoomIndex}}" class="rooms-tr-meal-twice" data-price="@{{ room.FinalPrice}}" data-meal="@{{ roomList.RatePlanName || 'No Meals' }}" data-include="@{{ tdClass(room.Inclusion)}}" data-name="@{{ room.RoomTypeName}}" ng-init="room_key_index = $index">
                                                        <td >
                                                            <span ng-bind="room.RoomTypeName"></span>
                                                            <br>
                                                            <a href="javascript:void(0);" class="tw-text-sm" ng-click="showRoomDetails($index)">Room Details</a>
                                                        </td>
                                                        <td>@{{ room.FinalPrice | currency: room.Price.CurrencyCode }}</td>
                                                        <td>
                                                            <input type="radio" data-price="@{{room.FinalPrice}}" ng-click="selectRoom(room, rCount, room_key_index)" class="room-radio-@{{rCount}}" id="room-radio-@{{rCount}}-@{{ room.RoomIndex}}-@{{ room_key_index}}" data-room="@{{ room.RoomIndex}}" data-category="@{{ room.CategoryId}}" data-combination="@{{ room.InfoSource}}" data-rtype="@{{ room.RoomTypeName}}" name="selected_rooms[@{{rCount}}][]" value="@{{ room.RoomIndex}}" ng-disabled="combination == 'fixed' && rCount > 0">
                                                            <!-- Room Details Modal -->
                                                            <div id="roomModal_@{{ $index}}" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-body">
                                                                            <p class="room-heading">@{{ room.RoomTypeName}} <button type="button" class="close" data-dismiss="modal">&times;</button></p>
                                                                            <div class="row">
                                                                                <div class="col-md-7">
                                                                                    <div id="custCarouse_@{{ $index}}" class="carousel slide" data-ride="carousel" align="center">
                                                                                        <ol class="carousel-indicators" >
                                                                                            <li  ng-repeat="imgR in ::room.images" ng-init="image_key = $index" data-target="#custCarouse_@{{ room_key}}"  data-slide-to="@{{ image_key}}" ng-class="image_key < 1 ? 'active' : ''"></li>
                                                                                        </ol>
                                                                                        <div class="carousel-inner" >
                                                                                            <div ng-repeat="imgR in ::room.images" ng-init="image_key_s = $index" ng-class="image_key_s < 1 ? 'carousel-item active' : 'carousel-item'"> 
                                                                                                <img class="room-image-slider" src="@{{ ::imgR}}"  alt="hotel" ng-if="roomList.sub_rooms[0].images[0].indexOf('http') != -1">
                                                                                                <img class="room-image-slider" src="/uploads/rooms/{{ $hotel_code}}/@{{ ::imgR}}"  alt="hotel" ng-if="roomList.sub_rooms[0].images[0].indexOf('http') == -1">
                                                                                            </div>
                                                                                            <div ng-if="!room.images.length" class="carousel-item active"> 
                                                                                                <img class="room-image-slider" src="https://b2b.tektravels.com/Images/HotelNA.jpg"  alt="hotel">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-5">
                                                                                    <p class="room-details" ng-if="room.RatePlanName">
                                                                                        @{{ room.RatePlanName}}
                                                                                    </p>
                                                                                    <div class="row">
                                                                                        <div class="col-md-6 amenity" ng-repeat="am in room.Amenity">
                                                                                            <span><i class="fa fa-check" aria-hidden="true"></i>&nbsp;@{{ am}}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <p>
                                                                                        Smooking: <span class="strong">@{{ room.SmokingPreference}}</span>
                                                                                    </p>
                                                                                    <p ng-repeat="ams in room.Amenities">
                                                                                        Amenities: <span class="strong">@{{ ams.Amenities[0]}}</span>
                                                                                    </p>
                                                                                    <p ng-if="room.BedTypes && room.BedTypes.length">
                                                                                        Bed Type: <span class="strong">@{{ room.BedTypes[0].BedTypeDescription}}</span>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <p ng-bind-html="room.RoomDescription"></p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <p>Cancellation Policy: @{{ room.CancellationPolicy}}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="inner_room_type_data" ng-show="!loaded">
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
                    <div class="inner_room_type_data" ng-if="error">
                        <div class="container loading">
                            <h3 ng-bind="error" class="text-center"></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Code Ends  -->
    </section>

    <section class="about_detail_hotels">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="inner_about_hotels_data attr">
                        <h2 class="about_hotel">About The Hotel</h2>
                        <ul class="list-inline" style="padding: 20px 0px;">
                            @if(isset($hotel) && isset($hotel['hotel_time']) && isset($hotel['hotel_time']['@CheckInTime']))
                            <li style="float:left;">CHECK IN {{ $hotel['hotel_time']['@CheckInTime']}}</li>
                            @endif
                            @if(isset($hotel) && isset($hotel['hotel_time']) && isset($hotel['hotel_time']['@CheckOutTime']))
                            <li style="float:left;">CHECK OUT {{ $hotel['hotel_time']['@CheckOutTime']}} </li>
                            @endif
                        </ul>
                        @if(isset($hotel) && isset($hotel['hotel_description']) && isset($hotel['hotel_description'][0]))
                        <p>{!!html_entity_decode($hotel['hotel_description'][0])!!}</p>
                        @endif
                    </div>
                    @if(isset($hotel) && isset($hotel['attractions']) && sizeof($hotel['attractions']) > 0)
                    <br>
                    <div class="inner_about_hotels_data attr">
                        <h2>Attractions</h2>
                        @foreach($hotel['attractions'] as $h_at)
                        <p>{!!html_entity_decode($h_at)!!}</p>
                        @endforeach
                    </div>
                    @endif
                    @if(isset($hotel['hotel_facilities']) && sizeof($hotel['hotel_facilities']) > 0)
                    <br>
                    <div class="inner_about_hotels_data attr">
                        <h2>Ameneties</h2>
                        <div class="row">
                            @foreach($hotel['hotel_facilities'] as $h_fac)
                            <div class="col-md-3 pad-left">
                                <a href="javascript:void(0);" class="listing_kids_option" >
                                    {{ $h_fac}}
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
<br><br>
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
                <a href="javascript:void(0);" class="btn btn-primary refresh-btn show-hotel">Refresh Search</a>
            </div>
        </div>
    </div>
</div>
@endsection