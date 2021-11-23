@extends('layouts.app-header')
@section('content') 
<input type="hidden" id="isViewHotel" value="1">
<style>
    /*.responsive__tabs ul.scrollable-tabs {
      background-color: #333 !important;
      overflow-x: auto !important;
      white-space: nowrap !important;
      display: flex !important;
      text-transform: uppercase !important;
      flex-direction: row !important;
      list-style: none !important;
    }
    .responsive__tabs ul.scrollable-tabs li {
      list-style-type: none !important;
      width:auto !important;
      margin-bottom: 0px !important;
      padding-left:0px !important;
    }
    .responsive__tabs ul.scrollable-tabs li::before{
        content:none !important;
    }
    .responsive__tabs ul.scrollable-tabs li a {
      display: inline-block !important;
      color: white !important;
      text-align: center !important;
      padding: 14px !important;
      text-decoration: none !important;
    }
    .responsive__tabs ul.scrollable-tabs li a:hover, .responsive__tabs ul.scrollable-tabs li a.active {
      background-color: #777 !important;
    }*/

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
        width:auto;
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
        font-size:18px;
    }
    .responsive__tabs ul.scrollable-tabs li a.active {
        background-color: #fff !important;
        border-bottom: 3px solid #fd7e14;
    }

    .responsive__tabs tr.active{
        border-left:14px solid #1e4355;
    }
    
/*    .roomsList input[type='radio']{
        visibility: hidden;
        height: 1px;
    }*/
    .rounding_form_info p {
        color: #000;
    }
</style>
<section class="listing_banner_forms" class="showProgressLoader" >
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="listing_inner_forms">
                    <input type="hidden" name="staticPage" id="staticPage" value="1">
                    @if($typeSearch == 'activities')
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
                    @elseif($typeSearch == 'cabs')
                    <form method="GET" name="searchForm" id="searchCabsForm" action="{{ route('search_cabs') }}"  >
                                    @csrf
                                    <div class="rounding_form_info">
                                        <div class="row" style="border-radius: 6px;border:3px solid #1e4355 !important;">
                                            <div class="col-lg-4 text-center" style="background:#fff;padding:0px;border:6px solid #1e4355 !important;">
                                                <div class="form-group comboBox" style="border-radius: 0px;padding:0px 5px;">
                                                    <select name="city_name" class="auto-complete cab-city" required>
                                                    </select>
                                                    <input type="hidden" name="city_cab_id" id="city_cab_id" value="115936">
                                                    <input type="hidden" name="currency_code" id="currency_code" value="GB">
                                                    <input type="hidden" name="country_code_value" id="country_code_value" value="IN">
                                                    <input type="hidden" name="pick_up_point_name" id="pick_up_point_name" value="">
                                                    <input type="hidden" name="drop_off_point_name" id="drop_off_point_name" value="">
                                                    <div class="small_station_info selected-hotel-city"></div>
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
                    @elseif($typeSearch == 'flights')
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
                                                        <input class="form-control departdate" type="text" name="departDate" required readonly value="{{ date('d-m-Y') }}" style="color:#000;" />
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-6" style="padding:0px;border: 5px solid #1e4355;background: #fff;">
                                                <div class="form-group not-allowed"  style="padding-top:5px;" id="not-allowed">
                                                    <div class="small_station_info ">{{ __('labels.arrival') }}</div>
                                                    <!--<label>{{ __('labels.return') }} <i class="fa fa-angle-down" aria-hidden="true"></i></label>-->
                                                    <div class="input-group" >
                                                        <input class="form-control returndate" type="text" name="returnDate" required readonly value="{{ date('d-m-Y') }}" style="color:#000;" />
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
                                                    <input type="text" name="travellersClass" id="travellersClassOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 {{ __('labels.traveller') }}" value="1 {{ __('labels.traveller') }}" style="color:#000;">
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
                    @else

                    <form method="GET" name="searchForm" style="margin-bottom:0px;" id="searchRoomsForm" action="{{ route('search_rooms')}}"  >
                        @csrf
                        <div class="rounding_form_info" style="border-right:5px solid #b3cedd;border-left:5px solid #b3cedd;">
                            <div class="row">
                                <div class="col-lg-3" style="border-radius:4px;padding: 2px;background:#fff;border:5px solid #b3cedd;">
                                    <div class="form-group" style="margin:0px !important;">
                                        <input  id="autocomplete" name="Location" value="{{ $static_data['hotel_name']}}"  placeholder="{{ __('labels.search_placeholder') }}"  onFocus="geolocate()" type="text" style="color:#333;border:0px solid #ccc;padding:8px;font-size:14px;height:40px;width:100%;display: block !important;" />



                                        <input type="hidden" name="Latitude" id="Latitude" value="{{ isset($static_data['hotel_location']['@Latitude']) ? $static_data['hotel_location']['@Latitude'] : ''}}">
                                        <input type="hidden" name="Longitude" id="Longitude" value="{{ isset($static_data['hotel_location']['@Longitude']) ? $static_data['hotel_location']['@Longitude'] : ''}}">
                                        <input type="hidden" name="Radius" id="Radius" value="15">
                                        <input type="hidden" name="city_id" id="city_id" value="{{ $city['CityId']}}">
                                        <input type="hidden" name="countryCode" id="country_code" value="{{ $city['CountryCode']}}">
                                        <input type="hidden" name="city_name" id="city_name" value="{{ $city['CityName']}}">
                                        <input type="hidden" name="countryName" id="country_name" value="{{ $city['Country']}}">
                                        <input type="hidden" name="country" id="country" value="{{ $city['CountryCode']}}">
                                        <input type="hidden" name="currency" id="currency" value="">
                                        <input type="hidden" name="ishalal" id="ishalal" value="">
                                        <input type="hidden" name="referral" class="referral" value="{{$referral}}">
                                        <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="{{ $static_data['hotel_code']}}">

                                    </div>
                                </div>
                                <div class="col-lg-5" style="border-radius:4px;padding: 2px;background:#fff;border:5px solid #b3cedd;">

                                    <div class="row" style="margin:0px;">
                                        <div class="col"  style="padding:0px 5px;">
                                            <div class="form-group" style="margin:0px !important;">
                                                <div class="small_station_info departDay1 text-center" >{{ __('labels.checkin') }}</div>
                                                <div class="input-group" >
                                                    <input style="text-align:center;" type="text" id="dateRange" value="{{ date('d-m-Y') }} - {{ date('d-m-Y', strtotime(date('d-m-Y') . ' +1 day')) }}" class="form-control  text-center" />
                                                    <input id="departHotel" type="hidden" name="departdate" value="{{ date('d-m-Y') }}" />
                                                    <input id="returnHotel" type="hidden" name="returndate" value="{{ date('d-m-Y', strtotime(date('d-m-Y') . ' +1 day')) }}" />                                                   
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="col-lg-2" style="border-radius:4px;padding:2px;background:#fff;border:5px solid #b3cedd;">
                                   <div class="form-group" style="margin:0px !important;">
                                        @if(Session::get('locale') == 'heb')
                                        <div class="small_station_info" id="guestRooms" style="text-align: center;">{{ __('labels.rooms') }} <span style="position:absolute;">1</span></div>
                                        @else
                                        <div class="small_station_info" id="guestRooms" style="text-align: center;">1 {{ __('labels.rooms') }}</div>
                                        @endif
                                        <div class="input-group text-center">
                                            @if(Session::get('locale') == 'heb')
                                            <input type="text" name="roomsGuests" id="roomsGuests" readonly class="form-control text-center" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="חדר אחד 2 אורח">
                                            @else
                                            <input type="text" name="roomsGuests" id="roomsGuests" readonly class="form-control text-center" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ __('labels.room_guests') }}">
                                            @endif
                                            @include('_partials.hotel-guests')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2" style="padding:0px;border:3px solid #b3cedd;">
                                    <div class="search_btns_listing" style="padding:0px;border:3px solid #b3cedd;">
                                        <button type="submit" style="width:100%;border-radius: 4px !important;" class="btn btn-primary">{{ __('labels.search')}}</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>  
<!-- <section class="listing_banner_forms no-index bread-cums">
    <div class="container">
        <div class="row">
            <div class="">
                <p><a href="/">Home</a>&nbsp;<i class="fa fa-angle-double-right"></i> 
                    <a href="/discover/more-countries">Top Destinations</a>&nbsp;<i class="fa fa-angle-double-right"></i> 
                    <a href="/discover/country/{{$city['CountryCode']}}">{{$city['Country']}}</a>&nbsp;<i class="fa fa-angle-double-right"></i> 
                    <a href="/hotels/{{strtolower(str_replace(' ', '-', $city['Country']))}}/{{strtolower(str_replace(' ', '-', $city['CityName']))}}/{{$city['CityId']}}">{{$city['CityName']}}</a>&nbsp;<i class="fa fa-angle-double-right"></i> 
                    @if(isset($static_data) && isset($static_data['hotel_name']))
                        <span class=""> {{ $static_data['hotel_name']}}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</section> -->
<section class="listing_banner_forms no-index">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="listing_inner_forms">
                    <div class="rounding_form_info">
                        <p class="text-center"> 
                            @if(isset($static_data) && isset($static_data['hotel_name']))
                            <span class=""><i class="fa fa-building"></i> {{ $static_data['hotel_name']}}</span>
                            @endif
                            <span class=""> - {{ $city['CityName'] }}</span> 
                            {{ __('labels.from')}} <span class=""><i class="fa fa-calendar"></i>  {{ date('d-m-Y') }}</span>
                            {{ __('labels.to')}} <span class=""><i class="fa fa-calendar"></i>  {{ date('d-m-Y', strtotime(date('d-m-Y') . ' +1 day')) }}</span>
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

<div ng-controller="roomStaticCtrl" ng-app="hotelStaticApp" id="hotelViewPage">


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
                                                @if(isset($static_data['hotel_images'][0]) && strpos($static_data['hotel_images'][0], 'http') !== false || ( isset($static_data['hotel_images'][0]) && strpos($static_data['hotel_images'][0], 'www') !== false) )
                                                <a href="{{ isset($static_data['hotel_images'][0])?$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me second-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][0])?$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][0])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me second-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][0])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][0]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif

                                                @if(isset($static_data['hotel_images'][1]) && strpos($static_data['hotel_images'][1], 'http') !== false || ( isset($static_data['hotel_images'][1]) && strpos($static_data['hotel_images'][1], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][1])?$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}" class="hotel_url  show-me third-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][1])?$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][1])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me third-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][1])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][1]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-sm-12  p-1">
                                            <div class="hotel_box room-vcol ht_imgs">
                                                @if(isset($static_data['hotel_images'][2]) && strpos($static_data['hotel_images'][2], 'http') !== false || ( isset($static_data['hotel_images'][2]) && strpos($static_data['hotel_images'][2], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][2])?$static_data['hotel_images'][2]:asset('/images/no-hotel-photo.png')}}" class="hotel_url  show-me primary-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][2])?$static_data['hotel_images'][2]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][2])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][2]:asset('/images/no-hotel-photo.png')}}" class="hotel_url  show-me primary-banner">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][2])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][2]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row room-thumbs" >
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][3]) && strpos($static_data['hotel_images'][3], 'http') !== false || ( isset($static_data['hotel_images'][3]) && strpos($static_data['hotel_images'][3], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][3])?$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][3])?$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}"  onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][3])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][3])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][3]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][4]) && strpos($static_data['hotel_images'][4], 'http') !== false || ( isset($static_data['hotel_images'][4]) && strpos($static_data['hotel_images'][4], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][4])?$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][4])?$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][4])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][4])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][4]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][5]) && strpos($static_data['hotel_images'][5], 'http') !== false || ( isset($static_data['hotel_images'][5]) && strpos($static_data['hotel_images'][5], 'www') !== false))
                                                <a href="{{ isset($static_data['hotel_images'][5])?$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][5])?$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][5])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][5])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][5]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb ht_imgs">
                                                @if(isset($static_data['hotel_images'][6]) && strpos($static_data['hotel_images'][6], 'http') !== false || ( isset($static_data['hotel_images'][6]) && strpos($static_data['hotel_images'][6], 'www') !== false ) )
                                                <a href="{{ isset($static_data['hotel_images'][6])?$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][6])?$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @else
                                                <a href="{{ isset($static_data['hotel_images'][6])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}" class="hotel_url show-me">
                                                    <img class="hotel_photo img-responsive" src="{{ isset($static_data['hotel_images'][6])?env('AWS_BUCKET_URL'). '/' .$static_data['hotel_images'][6]:asset('/images/no-hotel-photo.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';" >
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col  p-1">
                                            <div class="hotel_box room-thumb">
                                                <div class="ht_imgs">
                                                    <div class="hotel_box room-thumb">
                                                        @php $r_images = array(); $roomImagesCount = 0; @endphp
                                                        @foreach($rooms as $room_img)
                                                        @if($room_img->images && $room_img->images != null && !empty($room_img->images))
                                                        @php 
                                                        array_push($r_images , unserialize($room_img->images));
                                                        @endphp
                                                        @endif
                                                        @endforeach
                                                        @php $counter = 0; @endphp
                                                        @foreach($r_images as $r_key1 => $r_imgs)
                                                        @foreach($r_imgs as $r_key2 => $r_img)
                                                        <a href="{{ $r_img}}" title="{{ $static_data['hotel_name']}}" class="hotel_url @if($counter == 0) show-me @endif">
                                                            @if(strpos($r_img, 'http') !== false || strpos($r_img, 'www') !== false)
                                                                <a href="{{ $r_img}}" title="{{ $static_data['hotel_name']}}" class="hotel_url @if($counter == 0) show-me @endif">
                                                                    <img  class="hotel_photo img-responsive" src="{{ $r_img}}" alt="hotel" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                </a>
                                                            @else
                                                                <a href="{{env('AWS_BUCKET_URL')}}/{{ $r_img}}" title="{{ $static_data['hotel_name']}}" class="hotel_url @if($counter == 0) show-me @endif">
                                                                    <img  class="hotel_photo img-responsive" src="{{env('AWS_BUCKET_URL')}}/{{ $r_img}}" alt="hotel" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                </a>
                                                            @endif
                                                        </a>
                                                        @php 
                                                        $counter++; $roomImagesCount++; 
                                                        @endphp
                                                        @endforeach
                                                        @endforeach
                                                    </div>
                                                    @if($roomImagesCount > 0)
                                                        @if(strpos($r_img, 'http') !== false || strpos($r_img, 'www') !== false)
                                                            <div class="overlay_text_md" style="height: 100%;border-radius: 5px;" href="{{ $r_img}}" title="{{ $static_data['hotel_name']}}" >
                                                                <a style="padding: 30% 20% !important;color:#fff;display: block;" href="{{ $r_img}}">+ {{$roomImagesCount}} {{ __('labels.photos') }}</a>
                                                            </div>
                                                        @else
                                                            <div class="overlay_text_md" style="height: 100%;border-radius: 5px;" href="{{env('AWS_BUCKET_URL')}}/{{ $r_img}}" title="{{ $static_data['hotel_name']}}" >
                                                                <a style="padding: 30% 20% !important;color:#fff;display: block;" href="{{env('AWS_BUCKET_URL')}}/{{ $r_img}}">+ {{$roomImagesCount}} {{ __('labels.photos') }}</a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div id="hotelmap" style="min-height:536px;">
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="room_filters mt-4 desktopOnlyView" id="desktopRoomFilters">
        <div class="container">
            <div class="col-md-12 inner_about_hotels_data attr" >
                <div class="row">
                    <div class="col-md-12">
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
                                    <div class="col-md-12">
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
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <section class="room_type_data mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- New Html 1 Dec  -->
                    <div id="room-list">
                        <?php $KEY = 0; ?>
                        @foreach($rooms as $r_key => $room)
                        @if($room['name'] && $room['name'] != '')
                        @if($KEY < 5)
                        <div class="row rooms-tr" data-name="{{ $room['name']}}" >
                            <div class="col-lg-12">
                                <div class='rooms-options'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class='room-list-title section_title'> {{ $room['name']}}</div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 15px;">
                                        <div class="col-lg-4">
                                            <div class="room-photo no-price">
                                                @php $r_images = array(); $roomImagesCount = 0; @endphp
                                                @if($room_img['images'] && $room_img['images'] != null && !empty($room_img['images']))
                                                     @php $r_images = unserialize($room_img->images); @endphp
                                                @endif
                                           

                                                <div id="custCarouse{{$r_key}}" class="carousel slide" data-ride="carousel" align="center">
                                                    <div class="carousel-inner" >
                                                        @foreach($r_images as $i_key => $image)
                                                            @if(strpos($image, "_t") === false)
                                                                <div class="hotel-pic carousel-item @if($i_key ==0) active @endif" > 
                                                                    @if(strpos($image, 'http') !== false || strpos($image, 'www') !== false)
                                                                        <img ng-src="{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                    @else
                                                                        <img ng-src="{{env('AWS_BUCKET_URL')}}/{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>

                                                    <!-- Thumbnails -->

                                                    <ol class="carousel-indicators list-inline" style="display: none;">
                                                        @foreach($r_images as $i_key => $image)
                                                            @if(strpos($image, "_t") === false)
                                                                <li class="list-inline-item @if($i_key ==0) active @endif" > 
                                                                    <a id="carousel-selector-{{ $i_key}}" class="selected" data-slide-to="{{ $i_key}}" data-target="#custCarouse{{ $r_key}}">
                                                                        @if(strpos($image, 'http') !== false || strpos($image, 'www') !== false)
                                                                            <img ng-src="{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                        @else
                                                                            <img ng-src="{{env('AWS_BUCKET_URL')}}/{{ $image}}" class="img-fluid" onerror="this.onerror=null;this.src='https://via.placeholder.com/50X50?text=Image%20Not%20Available';">
                                                                        @endif
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-8" >
                                            <div class="">
                                                <div class="row" style="margin:8px;border-bottom:1px solid #ccc;">
                                                    <div class="col-lg-4 showOptionsDesktop">
                                                        <span class='room-label-title'> What’s Included</span>
                                                    </div>
                                                    <div class="col-lg-4 showOptionsDesktop">
                                                        <span class='room-label-title'> Room Details</span>
                                                    </div>
                                                    <div class="col-lg-4 total-stay">
                                                        <span class='room-label-title'>Total for stay</span>
                                                    </div>
                                                </div>

                                                <div style="min-height: 100px;margin:10px;padding-top:0px;padding-bottom: 0px;" class="row rooms-tr-meal price_dat_table sub_room-pr pr price-@{{ sub_room.FinalPrice}} index-@{{ room_key}} td-inclusion" data-meal="@{{ sub_room.RatePlanName || 'No Meals' }}"  data-price="@{{ sub_room.FinalPrice}}" data-index="@{{ room_key}}" data-currency="@{{ sub_room.Price.CurrencyCode}}" data-room="@{{ sub_room.RoomIndex}}" data-include="@{{ tdClass(sub_room.Inclusion)}}">
                                                    <div class="col-lg-4 showOptionsDesktop">
                                                        @if(isset($room['ameneties']) && !empty($room['ameneties']))
                                                            @php $room_ameneties = json_decode($room['ameneties'], true); @endphp
                                                            <ul >
                                                                @foreach($room_ameneties as $f_k => $r_fac)
                                                                    @if($f_k < 4)
                                                                        <li class="room-facility-text" ><i class="fa fa-check"></i>{{$r_fac}}</li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                            <a class="tw-text-sm" href="javascript:void(0);" data-toggle='modal' data-target='#roomInclusion_{{ $r_key}}'>More Inclusions</a> 
                                                        
                                                        <div id="roomInclusion_{{ $r_key}}" class="modal fade" role="dialog">
                                                            <div class="modal-dialog  modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h3 >{{ $room['name']}}</h3>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row" >
                                                                            <div class="col-md-12" >
                                                                                @foreach($room_ameneties as $f_k => $r_fac)
                                                                                    <div class="col-md-4">
                                                                                        <i class="fa fa-check"></i>{{$r_fac}}
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-4 showOptionsDesktop">
                                                        @if(isset($room['bed_type']) && !empty($room['bed_type']))
                                                            @php $bed_type = json_decode($room['bed_type'], true); @endphp
                                                            @if(isset($bed_type['beds']['BedName']))
                                                                <i class="fa fa-bed" aria-hidden="true"></i>&nbsp;{{ $bed_type['beds']['BedName'] }}
                                                            @endif

                                                            @if(isset($bed_type['room_size']['sm']) && !empty($bed_type['room_size']['sm']))
                                                                <br>
                                                                Room Size: <?php print_r($bed_type['room_size']['sm']); ?>sqm
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div style="min-height: 100px;padding:0px;" class="price_dat_table select_room_data td-inclusion">
                                                            <button type="button"  class="btn btn-primary show-price-room" > Show Prices</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $KEY++; ?>
                        @endif
                        @endif
                        @endforeach
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
                        <h2 class="about_hotel section_title text-center">{{ __('labels.about_hotel')}}</h2>
                        <ul class="list-inline" style="padding: 20px 0px;">
                            @if(isset($static_data) && isset($static_data['hotel_time']) && isset($static_data['hotel_time']['@CheckInTime']))
                            <li style="float:left;">CHECK IN {{ $static_data['hotel_time']['@CheckInTime']}}</li>
                            @endif
                            @if(isset($static_data) && isset($static_data['hotel_time']) && isset($static_data['hotel_time']['@CheckOutTime']))
                            <li style="float:left;">CHECK OUT {{ $static_data['hotel_time']['@CheckOutTime']}} </li>
                            @endif
                        </ul>
                        @if(isset($static_data) && isset($static_data['hotel_description']) && isset($static_data['hotel_description'][0]))
                        <p>{!!html_entity_decode($static_data['hotel_description'][0])!!}</p>
                        @endif
                    </div>
                    @if(isset($static_data) && isset($static_data['attractions']) && sizeof($static_data['attractions']) > 0)
                    <br>
                    <div class="inner_about_hotels_data attr">
                        <h2 class='section_title text-center'>Attractions</h2>
                        @foreach($static_data['attractions'] as $h_at)
                        <p>{!!html_entity_decode($h_at)!!}</p>
                        @endforeach
                    </div>
                    @endif
                    
                    @if(isset($static_data['hotel_facilities']) && sizeof($static_data['hotel_facilities']) > 0)
                    <br>
                    <div class="inner_about_hotels_data attr">
                        <h2 class='section_title text-center'>Ameneties</h2>
                        <div class="row">
                            @foreach($static_data['hotel_facilities'] as $h_fac)
                            <div class="col-md-3 pad-left">
                                <a href="javascript:void(0);" class="listing_kids_option" >
                                    {{ $h_fac}}
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if(isset($static_data['hotel_info']) && sizeof($static_data['hotel_info']) > 0)
                    <br>
                    <div class="inner_about_hotels_data attr">
                        <h2 class='section_title text-center'>Covid Facilities</h2>
                        <div class="row">
                            <ul class="covid-data">
                                @foreach($static_data['hotel_info'] as $c_info)
                                    <li>{{ $c_info }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <br>

                </div>
            </div>
        </div>
    </section>
</div>

<script>
    
    $(document).ready(function(){

        const uluru = { lat: <?php if(isset($static_data['lat'])) { echo $static_data['lat']; } else { echo '10
        13498'; } ?>, lng: <?php if(isset($static_data['lng'])) { echo $static_data['lng']; } else { echo '10
        13498'; } ?> };
        // The map, centered at Uluru
        const mapx = new google.maps.Map(document.getElementById("hotelmap"), {
                zoom: 14,
                center: uluru
        });
        // The marker, positioned at Uluru
        const marker = new google.maps.Marker({
                position: uluru,
                map: mapx
        });

        setTimeout(function(){

            $('#autocomplete').css('display','block');

        }, 2000);
        
    });

</script>
@endsection
