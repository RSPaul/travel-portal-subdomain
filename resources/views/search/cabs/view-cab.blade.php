@extends('layouts.app-header')
@section('content')
<?php $final_price_install = $cab['FinalPrice']; ?>

<style type="text/css">
  .raveller_informaion.mrgintop_20.cabInfo {
    width: 100%;
  }
</style>
<input type="hidden" id="search_id" value="{{ $search_id }}">
<input type="hidden" id="paymeUrl" value="{{ env('PAYME_URL') }}">
<input type="hidden" id="paymeKey" value="{{ env('PAYME_KEY') }}">
<section class="prdt_detail_sec" ng-app="cabsApp" ng-controller="searchCabCtrl"  id="cabExpireDiv">
   <div class="container">
      <div class="row">

        <form method="GET" name="searchForm" id="searchCabsForm" action="{{ route('search_cabs') }}"  style="display:none">
                           @csrf
                          <div class="rounding_form_info">
                            <div class="form-group">
                               <label>{{ __('labels.cityarea') }}</label>
                               <select name="city_name" class="auto-complete cab-city" required>
                                 <option value="{{ $input['city_name'] }}">{{ $input['city_name'] }}</option>
                               </select>
                               <input type="hidden" name="city_cab_id" id="city_cab_id" value="{{$input['city_cab_id']}}">
                               <input type="hidden" name="pick_up_point_name" id="pick_up_point_name" value="{{$input['pick_up_point_name']}}">
                               <input type="hidden" name="drop_off_point_name" id="drop_off_point_name" value="{{$input['drop_off_point_name']}}">
                               <div class="small_station_info selected-hotel-city"></div>
                               <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>
                            </div>
                            <div class="form-group">
                               <label>{{ __('labels.pickup') }}</label>
                               <select name="pick_up" id="pick_up_type" class="select_pickup" required>
                                  <option value="">Select One</option>
                                  <option value="0" <?php if($input['pick_up'] == '0'): ?> selected="selected"<?php endif; ?> >Accommodation</option>
                                  <option value="1" <?php if($input['pick_up'] == '1'): ?> selected="selected"<?php endif; ?> >Airport</option>
                                  <option value="2" <?php if($input['pick_up'] == '2'): ?> selected="selected"<?php endif; ?> >Train Station</option>
                                  <option value="3" <?php if($input['pick_up'] == '3'): ?> selected="selected"<?php endif; ?> >Sea Port</option>
                               </select>
                            </div>
                            <div class="form-group">
                               <label>{{ __('labels.drop_off') }}</label>
                               <select name="drop_off" id="drop_off_type" class="select_dropoff" required>
                                  <option value="">Select One</option>
                                  <option value="0" <?php if($input['drop_off'] == '0'): ?> selected="selected"<?php endif; ?> >Accommodation</option>
                                  <option value="1" <?php if($input['drop_off'] == '1'): ?> selected="selected"<?php endif; ?>>Airport</option>
                                  <option value="2" <?php if($input['drop_off'] == '2'): ?> selected="selected"<?php endif; ?>>Train Station</option>
                                  <option value="3" <?php if($input['drop_off'] == '3'): ?> selected="selected"<?php endif; ?>>Sea Port</option>
                                  <!-- <option value="4">Other</option> -->
                               </select>
                            </div>
                           
                            <?php if(isset($input['pick_up_point_acc'])){ ?>
                            <div class="form-group accom_city">
                                <label>{{ __('labels.pickup_point') }}</label>
                                <select name="pick_up_point_acc" class="auto-complete pick_up_point_auto">
                                  <option value="{{ $input['pick_up_point_acc'] }}">{{ $input['pick_up_point_name'] }}</option>
                                </select>
                            </div>
                            <div class="form-group non_acc_city" style="display: none;">
                                <label>{{ __('labels.pickup_point') }}</label>
                                <select name="pick_up_point" id="pick_up_point" class="pickup-city">
                                     <option value=''>Select Location</option>
                                </select>
                            </div>
                            <?php }else{  ?>
                            <div class="form-group non_acc_city">
                                <label>{{ __('labels.pickup_point') }}</label>
                                <select name="pick_up_point" id="pick_up_point" class="pickup-city">
                                    <option value="{{ $input['pick_up_point'] }}">{{ $input['pick_up_point_name'] }}</option>
                                </select>
                            </div>
                            <div class="form-group accom_city" style="display:none;">
                                <label>{{ __('labels.pickup_point') }}</label>
                                <select name="pick_up_point_acc" class="auto-complete pick_up_point_auto">
                                  
                                </select>
                            </div>
                            <?php } ?>
                           
                            <!-- For Accom -->
                            <?php if(isset($input['drop_off_point_acc'])){ ?>
                            <div class="form-group accom_city_drop">
                                <label>{{ __('labels.drop_off_point') }}</label>
                                <select name="drop_off_point_acc" class="auto-complete drop_off_point_auto">
                                  <option value="{{ $input['drop_off_point_acc'] }}">{{ $input['drop_off_point_name'] }}</option>
                                </select>
                            </div>
                            <div class="form-group non_acc_city_drop" style="display: none;">
                                <label>{{ __('labels.drop_off_point') }}</label>
                                <select name="drop_off_point" id="drop_off_point" class="dropoff-city">
                                     <option value=''>Select Location</option>
                                </select>
                            </div>
                            <?php }else{  ?>
                             <div class="form-group non_acc_city_drop">
                                <label>{{ __('labels.drop_off_point') }}</label>
                                <select name="drop_off_point" id="drop_off_point" class="dropoff-city">
                                    <option value="{{ $input['drop_off_point'] }}">{{ $input['drop_off_point_name'] }}</option>
                                </select>
                            </div>
                            <div class="form-group accom_city_drop" style="display:none;">
                                <label>{{ __('labels.drop_off_point') }}</label>
                                <select name="drop_off_point_acc" class="auto-complete drop_off_point_auto">
                                  
                                </select>
                            </div>
                            <?php } ?>

                          </div>
                          <div class="rounding_form_info second_routinf_forms">
                            
                            <div class="form-group">
                               <label>{{ __('labels.travel_date') }} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                               <div class="input-group">
                                  <input id="departHotel" class="form-control departdate" type="text" name="transferdate" required readonly value="{{ $input['transferdate'] }}"/>
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                               </div>
                            </div>
                            <div class="form-group">
                              <label>{{ __('labels.travel_time') }} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                              <select name="time">
                                <option value="0000" <?php if($input['time'] == '0000'): ?> selected="selected"<?php endif; ?> >12.00 AM</option>
                                <option value="0030" <?php if($input['time'] == '0030'): ?> selected="selected"<?php endif; ?> >12.30 AM</option>
                                <option value="0100" <?php if($input['time'] == '0100'): ?> selected="selected"<?php endif; ?> >01.00 AM</option>
                                <option value="0130" <?php if($input['time'] == '0130'): ?> selected="selected"<?php endif; ?> >01.30 AM</option>
                                <option value="0200" <?php if($input['time'] == '0200'): ?> selected="selected"<?php endif; ?> >02.00 AM</option>
                                <option value="0230" <?php if($input['time'] == '0230'): ?> selected="selected"<?php endif; ?> >02.30 AM</option>
                                <option value="0300" <?php if($input['time'] == '0300'): ?> selected="selected"<?php endif; ?> >03.00 AM</option>
                                <option value="0330" <?php if($input['time'] == '0330'): ?> selected="selected"<?php endif; ?> >03.30 AM</option>
                                <option value="0400" <?php if($input['time'] == '0400'): ?> selected="selected"<?php endif; ?> >04.00 AM</option>
                                <option value="0430" <?php if($input['time'] == '0430'): ?> selected="selected"<?php endif; ?> >04.30 AM</option>
                                <option value="0500" <?php if($input['time'] == '0500'): ?> selected="selected"<?php endif; ?> >05.00 AM</option>
                                <option value="0530" <?php if($input['time'] == '0530'): ?> selected="selected"<?php endif; ?> >05.30 AM</option>
                                <option value="0600" <?php if($input['time'] == '0600'): ?> selected="selected"<?php endif; ?> >06.00 AM</option>
                                <option value="0630" <?php if($input['time'] == '0630'): ?> selected="selected"<?php endif; ?> >06.30 AM</option>
                                <option value="0700" <?php if($input['time'] == '0700'): ?> selected="selected"<?php endif; ?> >07.00 AM</option>
                                <option value="0730" <?php if($input['time'] == '0730'): ?> selected="selected"<?php endif; ?> >07.30 AM</option>
                                <option value="0800" <?php if($input['time'] == '0800'): ?> selected="selected"<?php endif; ?> >08.00 AM</option>
                                <option value="0830" <?php if($input['time'] == '0830'): ?> selected="selected"<?php endif; ?> >08.30 AM</option>
                                <option value="0900" <?php if($input['time'] == '0900'): ?> selected="selected"<?php endif; ?> >09.00 AM</option>
                                <option value="0930" <?php if($input['time'] == '0930'): ?> selected="selected"<?php endif; ?> >09.30 AM</option>
                                <option value="1000" <?php if($input['time'] == '1000'): ?> selected="selected"<?php endif; ?> >10.00 AM</option>
                                <option value="1030" <?php if($input['time'] == '1030'): ?> selected="selected"<?php endif; ?> >10.30 AM</option>
                                <option value="1100" <?php if($input['time'] == '1100'): ?> selected="selected"<?php endif; ?> >11.00 AM</option>
                                <option value="1130" <?php if($input['time'] == '1130'): ?> selected="selected"<?php endif; ?> >11.30 AM</option>
                                <option value="1200" <?php if($input['time'] == '1200'): ?> selected="selected"<?php endif; ?> >12.00 PM</option>
                                <option value="1230" <?php if($input['time'] == '1230'): ?> selected="selected"<?php endif; ?> >12.30 PM</option>
                                <option value="1300" <?php if($input['time'] == '1300'): ?> selected="selected"<?php endif; ?> >01.00 PM</option>
                                <option value="1330" <?php if($input['time'] == '1330'): ?> selected="selected"<?php endif; ?> >01.30 PM</option>
                                <option value="1400" <?php if($input['time'] == '1400'): ?> selected="selected"<?php endif; ?> >02.00 PM</option>
                                <option value="1430" <?php if($input['time'] == '1430'): ?> selected="selected"<?php endif; ?> >02.30 PM</option>
                                <option value="1500" <?php if($input['time'] == '1500'): ?> selected="selected"<?php endif; ?> >03.00 PM</option>
                                <option value="1530" <?php if($input['time'] == '1530'): ?> selected="selected"<?php endif; ?> >03.30 PM</option>
                                <option value="1600" <?php if($input['time'] == '1600'): ?> selected="selected"<?php endif; ?> >04.00 PM</option>
                                <option value="1630" <?php if($input['time'] == '1630'): ?> selected="selected"<?php endif; ?> >04.30 PM</option>
                                <option value="1700" <?php if($input['time'] == '1700'): ?> selected="selected"<?php endif; ?> >05.00 PM</option>
                                <option value="1730" <?php if($input['time'] == '1730'): ?> selected="selected"<?php endif; ?> >05.30 PM</option>
                                <option value="1800" <?php if($input['time'] == '1800'): ?> selected="selected"<?php endif; ?> >06.00 PM</option>
                                <option value="1830" <?php if($input['time'] == '1830'): ?> selected="selected"<?php endif; ?> >06.30 PM</option>
                                <option value="1900" <?php if($input['time'] == '1900'): ?> selected="selected"<?php endif; ?> >07.00 PM</option>
                                <option value="1930" <?php if($input['time'] == '1930'): ?> selected="selected"<?php endif; ?> >07.30 PM</option>
                                <option value="2000" <?php if($input['time'] == '2000'): ?> selected="selected"<?php endif; ?> >08.00 PM</option>
                                <option value="2030" <?php if($input['time'] == '2030'): ?> selected="selected"<?php endif; ?> >08.30 PM</option>
                                <option value="2100" <?php if($input['time'] == '2100'): ?> selected="selected"<?php endif; ?> >09.00 PM</option>
                                <option value="2130" <?php if($input['time'] == '2130'): ?> selected="selected"<?php endif; ?> >09.30 PM</option>
                                <option value="2200" <?php if($input['time'] == '2200'): ?> selected="selected"<?php endif; ?> >10.00 PM</option>
                                <option value="2230" <?php if($input['time'] == '2230'): ?> selected="selected"<?php endif; ?> >10.30 PM</option>
                                <option value="2300" <?php if($input['time'] == '2300'): ?> selected="selected"<?php endif; ?> >11.00 PM</option>
                                <option value="2330" <?php if($input['time'] == '2330'): ?> selected="selected"<?php endif; ?> >11.30 PM</option>
                              </select>
                            </div>
                            <div class="form-group">
                               <label>{{ __('labels.pref_lang') }}</label>
                               <select name="preffered_language" class="select_preffered_lang" required>
                                  <option value="0" <?php if($input['preffered_language'] == '0'): ?> selected="selected"<?php endif; ?> >NotSpecified</option>
                                  <option value="1" <?php if($input['preffered_language'] == '1'): ?> selected="selected"<?php endif; ?> >Arabic</option>
                                  <option value="2" <?php if($input['preffered_language'] == '2'): ?> selected="selected"<?php endif; ?> >Cantinese</option>
                                  <option value="3" <?php if($input['preffered_language'] == '3'): ?> selected="selected"<?php endif; ?> >Danish</option>
                                  <option value="4" <?php if($input['preffered_language'] == '4'): ?> selected="selected"<?php endif; ?> >English</option>
                                  <option value="5" <?php if($input['preffered_language'] == '5'): ?> selected="selected"<?php endif; ?> >French</option>
                                  <option value="6" <?php if($input['preffered_language'] == '6'): ?> selected="selected"<?php endif; ?> >German</option>
                                  <option value="7" <?php if($input['preffered_language'] == '7'): ?> selected="selected"<?php endif; ?> >Hebrew</option>
                                  <option value="8" <?php if($input['preffered_language'] == '8'): ?> selected="selected"<?php endif; ?> >Italian</option>
                                  <option value="9" <?php if($input['preffered_language'] == '9'): ?> selected="selected"<?php endif; ?> >Japanese</option>
                                  <option value="10" <?php if($input['preffered_language'] == '10'): ?> selected="selected"<?php endif; ?> >Korean</option>
                                  <option value="11" <?php if($input['preffered_language'] == '11'): ?> selected="selected"<?php endif; ?> >Mandrain</option>
                                  <option value="12" <?php if($input['preffered_language'] == '12'): ?> selected="selected"<?php endif; ?> >Portuguese</option>
                                  <option value="13" <?php if($input['preffered_language'] == '13'): ?> selected="selected"<?php endif; ?> >Russian</option>
                                  <option value="14" <?php if($input['preffered_language'] == '14'): ?> selected="selected"<?php endif; ?> >Spanish</option>
                               </select>
                            </div>
                            <div class="form-group">
                             <label>{{ __('labels.traveller') }}</label>
                             <input type="text" name="travellersClass" id="travellersClassCabOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="1 Traveller">
                              <div class="travellers gbTravellers travellersClassCabOne">
                                 <div class="appendBottom20">
                                    <p data-cy="adultRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.adults') }}</p>
                                    <ul class="guestCounter font12 darkText gbCounter adCC">
                                       <li data-cy="1" class="">1</li>
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
                                             <li class="childAgeSelector " id="VchildAgeSelector1CabAct1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child1age') }}</span>
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
                                             <li class="childAgeSelector " id="VchildAgeSelector2CabAct1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child2age') }}</span>
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
                                             <li class="childAgeSelector " id="VchildAgeSelector3CabAct1">
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
                                             <li class="childAgeSelector " id="VchildAgeSelector4CabAct1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child42age') }}</span>
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
                                       <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">{{ __('labels.apply') }}</button>
                                    </div>
                                 </div>
                              </div>
                            </div>
                            <div class="form-group">
                               <label>{{ __('labels.pref_currency') }}</label>
                               <select name="preffered_currency" class="select_preffered_currency" required>
                                  <option value="USD" <?php if($input['preffered_currency'] == 'USD'): ?> selected="selected"<?php endif; ?> >United States Dollars</option>
                                  <option value="EUR" <?php if($input['preffered_currency'] == 'EUR'): ?> selected="selected"<?php endif; ?> >Euro</option>
                                  <option value="GBP" <?php if($input['preffered_currency'] == 'GBP'): ?> selected="selected"<?php endif; ?> >United Kingdom Pounds</option>
                                  <option value="DZD" <?php if($input['preffered_currency'] == 'DZD'): ?> selected="selected"<?php endif; ?> >Algeria Dinars</option>
                                  <option value="ARP" <?php if($input['preffered_currency'] == 'ARP'): ?> selected="selected"<?php endif; ?> >Argentina Pesos</option>
                                  <option value="AUD" <?php if($input['preffered_currency'] == 'AUD'): ?> selected="selected"<?php endif; ?> >Australia Dollars</option>
                                  <option value="ATS" <?php if($input['preffered_currency'] == 'ATS'): ?> selected="selected"<?php endif; ?> >Austria Schillings</option>
                                  <option value="BSD" <?php if($input['preffered_currency'] == 'BSD'): ?> selected="selected"<?php endif; ?> >Bahamas Dollars</option>
                                  <option value="BBD" <?php if($input['preffered_currency'] == 'BBD'): ?> selected="selected"<?php endif; ?> >Barbados Dollars</option>
                                  <option value="BEF" <?php if($input['preffered_currency'] == 'BEF'): ?> selected="selected"<?php endif; ?> >Belgium Francs</option>
                                  <option value="BMD" <?php if($input['preffered_currency'] == 'BMD'): ?> selected="selected"<?php endif; ?> >Bermuda Dollars</option>
                                  <option value="BRR" <?php if($input['preffered_currency'] == 'BRR'): ?> selected="selected"<?php endif; ?> >Brazil Real</option>
                                  <option value="BGL" <?php if($input['preffered_currency'] == 'BGL'): ?> selected="selected"<?php endif; ?> >Bulgaria Lev</option>
                                  <option value="CAD" <?php if($input['preffered_currency'] == 'CAD'): ?> selected="selected"<?php endif; ?> >Canada Dollars</option>
                                  <option value="CLP" <?php if($input['preffered_currency'] == 'CLP'): ?> selected="selected"<?php endif; ?> >Chile Pesos</option>
                                  <option value="CNY" <?php if($input['preffered_currency'] == 'CNY'): ?> selected="selected"<?php endif; ?> >China Yuan Renmimbi</option>
                                  <option value="CYP" <?php if($input['preffered_currency'] == 'CYP'): ?> selected="selected"<?php endif; ?> >Cyprus Pounds</option>
                                  <option value="CSK" <?php if($input['preffered_currency'] == 'CSK'): ?> selected="selected"<?php endif; ?> >Czech Republic Koruna</option>
                                  <option value="DKK" <?php if($input['preffered_currency'] == 'DKK'): ?> selected="selected"<?php endif; ?> >Denmark Kroner</option>
                                  <option value="NLG" <?php if($input['preffered_currency'] == 'NLG'): ?> selected="selected"<?php endif; ?> >Dutch Guilders</option>
                                  <option value="XCD" <?php if($input['preffered_currency'] == 'XCD'): ?> selected="selected"<?php endif; ?> >Eastern Caribbean Dollars</option>
                                  <option value="EGP" <?php if($input['preffered_currency'] == 'EGP'): ?> selected="selected"<?php endif; ?> >Egypt Pounds</option>
                                  <option value="FJD" <?php if($input['preffered_currency'] == 'FJD'): ?> selected="selected"<?php endif; ?> >Fiji Dollars</option>
                                  <option value="FIM" <?php if($input['preffered_currency'] == 'FIM'): ?> selected="selected"<?php endif; ?> >Finland Markka</option>
                                  <option value="FRF" <?php if($input['preffered_currency'] == 'FRF'): ?> selected="selected"<?php endif; ?> >France Francs</option>
                                  <option value="DEM" <?php if($input['preffered_currency'] == 'DEM'): ?> selected="selected"<?php endif; ?> >Germany Deutsche Marks</option>
                                  <option value="XAU" <?php if($input['preffered_currency'] == 'XAU'): ?> selected="selected"<?php endif; ?> >Gold Ounces</option>
                                  <option value="GRD" <?php if($input['preffered_currency'] == 'GRD'): ?> selected="selected"<?php endif; ?> >Greece Drachmas</option>
                                  <option value="HKD" <?php if($input['preffered_currency'] == 'HKD'): ?> selected="selected"<?php endif; ?> >Hong Kong Dollars</option>
                                  <option value="HUF" <?php if($input['preffered_currency'] == 'HUF'): ?> selected="selected"<?php endif; ?> >Hungary Forint</option>
                                  <option value="ISK" <?php if($input['preffered_currency'] == 'ISK'): ?> selected="selected"<?php endif; ?> >Iceland Krona</option>
                                  <option value="INR" <?php if($input['preffered_currency'] == 'INR'): ?> selected="selected"<?php endif; ?> >India Rupees</option>
                                  <option value="IDR" <?php if($input['preffered_currency'] == 'IDR'): ?> selected="selected"<?php endif; ?> >Indonesia Rupiah</option>
                                  <option value="IEP" <?php if($input['preffered_currency'] == 'IEP'): ?> selected="selected"<?php endif; ?> >Ireland Punt</option>
                                  <option value="ILS" <?php if($input['preffered_currency'] == 'ILS'): ?> selected="selected"<?php endif; ?> >Israel New Shekels</option>
                                  <option value="ITL" <?php if($input['preffered_currency'] == 'ITL'): ?> selected="selected"<?php endif; ?> >Italy Lira</option>
                                  <option value="JMD" <?php if($input['preffered_currency'] == 'JMD'): ?> selected="selected"<?php endif; ?> >Jamaica Dollars</option>
                                  <option value="JPY" <?php if($input['preffered_currency'] == 'JPY'): ?> selected="selected"<?php endif; ?> >Japan Yen</option>
                                  <option value="JOD" <?php if($input['preffered_currency'] == 'JOD'): ?> selected="selected"<?php endif; ?> >Jordan Dinar</option>
                                  <option value="KRW" <?php if($input['preffered_currency'] == 'KRW'): ?> selected="selected"<?php endif; ?> >Korea (South) Won</option>
                                  <option value="LBP" <?php if($input['preffered_currency'] == 'LBP'): ?> selected="selected"<?php endif; ?> >Lebanon Pounds</option>
                                  <option value="LUF" <?php if($input['preffered_currency'] == 'LUF'): ?> selected="selected"<?php endif; ?> >Luxembourg Francs</option>
                                  <option value="MYR" <?php if($input['preffered_currency'] == 'MYR'): ?> selected="selected"<?php endif; ?> >Malaysia Ringgit</option>
                                  <option value="MXP" <?php if($input['preffered_currency'] == 'MXP'): ?> selected="selected"<?php endif; ?> >Mexico Pesos</option>
                                  <option value="NLG" <?php if($input['preffered_currency'] == 'NLG'): ?> selected="selected"<?php endif; ?> >Netherlands Guilders</option>
                                  <option value="NZD" <?php if($input['preffered_currency'] == 'NZD'): ?> selected="selected"<?php endif; ?> >New Zealand Dollars</option>
                                  <option value="NOK" <?php if($input['preffered_currency'] == 'NOK'): ?> selected="selected"<?php endif; ?> >Norway Kroner</option>
                                  <option value="PKR" <?php if($input['preffered_currency'] == 'PKR'): ?> selected="selected"<?php endif; ?> >Pakistan Rupees</option>
                                  <option value="XPD" <?php if($input['preffered_currency'] == 'XPD'): ?> selected="selected"<?php endif; ?> >Palladium Ounces</option>
                                  <option value="PHP" <?php if($input['preffered_currency'] == 'PHP'): ?> selected="selected"<?php endif; ?> >Philippines Pesos</option>
                                  <option value="XPT" <?php if($input['preffered_currency'] == 'XPT'): ?> selected="selected"<?php endif; ?> >Platinum Ounces</option>
                                  <option value="PLZ" <?php if($input['preffered_currency'] == 'PLZ'): ?> selected="selected"<?php endif; ?> >Poland Zloty</option>
                                  <option value="PTE" <?php if($input['preffered_currency'] == 'PTE'): ?> selected="selected"<?php endif; ?> >Portugal Escudo</option>
                                  <option value="ROL" <?php if($input['preffered_currency'] == 'ROL'): ?> selected="selected"<?php endif; ?> >Romania Leu</option>
                                  <option value="RUR" <?php if($input['preffered_currency'] == 'RUR'): ?> selected="selected"<?php endif; ?> >Russia Rubles</option>
                                  <option value="SAR" <?php if($input['preffered_currency'] == 'SAR'): ?> selected="selected"<?php endif; ?> >Saudi Arabia Riyal</option>
                                  <option value="XAG" <?php if($input['preffered_currency'] == 'XAG'): ?> selected="selected"<?php endif; ?> >Silver Ounces</option>
                                  <option value="SGD" <?php if($input['preffered_currency'] == 'SGD'): ?> selected="selected"<?php endif; ?> >Singapore Dollars</option>
                                  <option value="SKK" <?php if($input['preffered_currency'] == 'SKK'): ?> selected="selected"<?php endif; ?> >Slovakia Koruna</option>
                                  <option value="ZAR" <?php if($input['preffered_currency'] == 'ZAR'): ?> selected="selected"<?php endif; ?> >South Africa Rand</option>
                                  <option value="KRW" <?php if($input['preffered_currency'] == 'KRW'): ?> selected="selected"<?php endif; ?> >South Korea Won</option>
                                  <option value="ESP" <?php if($input['preffered_currency'] == 'ESP'): ?> selected="selected"<?php endif; ?> >Spain Pesetas</option>
                                  <option value="XDR" <?php if($input['preffered_currency'] == 'XDR'): ?> selected="selected"<?php endif; ?> >Special Drawing Right (IMF)</option>
                                  <option value="SDD" <?php if($input['preffered_currency'] == 'SDD'): ?> selected="selected"<?php endif; ?> >Sudan Dinar</option>
                                  <option value="SEK" <?php if($input['preffered_currency'] == 'SEK'): ?> selected="selected"<?php endif; ?> >Sweden Krona</option>
                                  <option value="CHF" <?php if($input['preffered_currency'] == 'CHF'): ?> selected="selected"<?php endif; ?> >Switzerland Francs</option>
                                  <option value="TWD" <?php if($input['preffered_currency'] == 'TWD'): ?> selected="selected"<?php endif; ?> >Taiwan Dollars</option>
                                  <option value="THB" <?php if($input['preffered_currency'] == 'THB'): ?> selected="selected"<?php endif; ?> >Thailand Baht</option>
                                  <option value="TTD" <?php if($input['preffered_currency'] == 'TTD'): ?> selected="selected"<?php endif; ?> >Trinidad and Tobago Dollars</option>
                                  <option value="TRL" <?php if($input['preffered_currency'] == 'TRL'): ?> selected="selected"<?php endif; ?> >Turkey Lira</option>
                                  <option value="VEB" <?php if($input['preffered_currency'] == 'VEB'): ?> selected="selected"<?php endif; ?> >Venezuela Bolivar</option>
                                  <option value="ZMK" <?php if($input['preffered_currency'] == 'ZMK'): ?> selected="selected"<?php endif; ?> >Zambia Kwacha</option>
                                  <option value="EUR" <?php if($input['preffered_currency'] == 'EUR'): ?> selected="selected"<?php endif; ?> >Euro</option>
                                  <option value="XCD" <?php if($input['preffered_currency'] == 'XCD'): ?> selected="selected"<?php endif; ?> >Eastern Caribbean Dollars</option>
                                  <option value="XDR" <?php if($input['preffered_currency'] == 'XDR'): ?> selected="selected"<?php endif; ?> >Special Drawing Right (IMF)</option>
                                  <option value="XAG" <?php if($input['preffered_currency'] == 'XAG'): ?> selected="selected"<?php endif; ?> >Silver Ounces</option>
                                  <option value="XAU" <?php if($input['preffered_currency'] == 'XAU'): ?> selected="selected"<?php endif; ?> >Gold Ounces</option>
                                  <option value="XPD" <?php if($input['preffered_currency'] == 'XPD'): ?> selected="selected"<?php endif; ?> >Palladium Ounces</option>
                                  <option value="XPT" <?php if($input['preffered_currency'] == 'XPT'): ?> selected="selected"<?php endif; ?> >Platinum Ounces</option>
                               </select>
                            </div>
                          </div>
                          <div class="search_btns_listing">
                               <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                               <input type="hidden" name="adultsFC" id="adultsFC" class="adultsCC" value="{{ $input['adultsFC'] }}">
                               <input type="hidden" name="childsFC" id="childsFC" class="childsCC" value="{{ $input['childsFC'] }}">
                               <input type="hidden" name="alternate_language" class="alternate_language" value="4">
                               <input type="hidden" name="country" class="country_val" value="{{ $input['currency_code'] }}">
                               <input type="hidden" name="currency_code" class="country_val" value="{{ $input['currency_code'] }}">
                               <input type="hidden" name="city_cab_pickup_id" id="city_cab_pickup_id" value="">
                               <input type="hidden" name="city_cab_dropoff_id" id="city_cab_dropoff_id" value="">
                               <button type="submit" class="btn btn-primary">{{ __('labels.search') }} <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
                            </div>
                     </form>

         <p id="timerBook"></p>
         <div class="col-lg-8 col-md-7">
            <div class="inner_left_prdct_data">
               <div class="row cabInfo">
                  <div class="col-lg-3 col-md-12">
                     <div class="prdct_full_img">

                      @if (strpos($cab['TransferName'], 'Car') !== false)
                        <img src="/images/Car.png" alt="/images/Car.png" style="height:100px;width:135px;" >
                      @elseif(strpos($cab['TransferName'], 'Minibus') !== false)
                        <img src="/images/Minibus.png" alt="/images/{{$cab['TransferName']}}.png" style="height:100px;width:135px;" >
                      @elseif(strpos($cab['TransferName'], 'Sedan') !== false)
                        <img src="/images/Sedan.png" alt="/images/{{$cab['TransferName']}}.png" style="height:100px;width:135px;" >
                      @elseif(strpos($cab['TransferName'], 'Minivan') !== false)
                        <img src="/images/Minivan.png" alt="/images/{{$cab['TransferName']}}.png" style="height:100px;width:135px;" >
                      @elseif(strpos($cab['TransferName'], 'SUV') !== false)
                        <img src="/images/SUV.png" alt="/images/{{$cab['TransferName']}}.png" style="height:100px;width:135px;" >
                      @elseif(strpos($cab['TransferName'], 'Adapted') !== false)
                        <img src="/images/Adapted.png" alt="/images/{{$cab['TransferName']}}.png" style="height:100px;width:135px;" >
                      @elseif(strpos($cab['TransferName'], 'Bus') !== false)
                        <img src="/images/Bus.png" alt="/images/{{$cab['TransferName']}}.png" style="height:100px;width:135px;" >
                      @else
                        <img src="/images/{{$cab['TransferName']}}.png" alt="/images/{{$cab['TransferName']}}.png" style="height:100px;width:135px;" onerror="this.src = ''; this.src = '/images/Car.png'">
                      @endif
                     </div>
                  </div>
                  <!-- <div class="col-lg-1 col-md-12"></div> -->
                  <div class="col-lg-9 col-md-12">
                     <div class="prdct_detail_data">
                        <h3>{{$cab['TransferName']}}({{$cab['Vehicles'][0]['VehicleCode']}})</h3>
                        <div class="check_out">
                           <ul class="list-inline">
                              <li>
                                 <h5>{{ __('labels.pickup')}}:</h5>
                                 <p>{{$cab['PickUp']['PickUpDate']}}&nbsp;{{$cab['PickUp']['PickUpTime']}}</p>
                              </li>
                              <li>
                                 <h5>{{ __('labels.from')}}:</h5>
                                 <p>{{$cab['PickUp']['PickUpDetailName']}}</p>
                              </li>
                              <li>
                                 <h5>{{ __('labels.to')}}:</h5>
                                 <p>{{$cab['DropOff']['DropOffDetailName']}}</p>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12 alert-success cancellation_text_cab">
                    <?php if(isset($cancellation_data) && isset($cancellation_data[0])) { ?>
                    <span class="cancellation_policy"> {{ __('labels.cancel_policy')}}: You will be charged <?php if( $cancellation_data[0]['ChargeType'] == 1){ echo $cancellation_data[0]['Charge']." Amount"; }else{ echo $cancellation_data[0]['ChargeType']." % of booking amount."; } ?> from {{ $cancellation_data[0]['FromDate']  }} to {{ $cancellation_data[0]['ToDate']  }} </span>
                  <?php } ?>
                  </div>
               </div>
               <div class="row">
                    <div class="room_information mrgintop_20 cabInfo">
                     <div class="col-lg-12 col-md-12">
                        <div class="inner_cab_detail">
                          <h4>{{ __('labels.conditions')}}:</h4>
                          <ol class="cab-conditions">
                            @foreach($cab['Condition'] as $condition)
                             <li>{{$condition}}</li><br>
                            @endforeach
                          </ol>
                          <?php if(isset($cab['MeetingPoint']) && !empty($cab['MeetingPoint'])) { ?>
                            <h4>{{ __('labels.meet_point')}}</h4>
                            <li>{{$cab['MeetingPoint']}}</li>
                          <?php } ?>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- Booking Form -->
              <form method="POST" class="traveller_form" id="BookRoomForm" name="BookingForm" action="{{ route('bookCab') }}">
                @if($paidAmtILS > 0)

                <input type="hidden" name="file_name" id="file_name" value="cab">
                <input type="hidden" name="preffered_currency" value="{{$input['preffered_currency']}}">
                <input type="hidden" name="trace_id" value="{{ $traceId }}" />
                <input type="hidden" name="cancellation_policy" value='<?php echo json_encode($cancellation_data); ?>' />
                <input type="hidden" name="last_cancellation_date" value="{{ $last_cancel_date }}" />
                <input type="hidden" name="pickup_detail_code" class="form-control" value="{{ $cab['PickUp']['PickUpDetailCode'] }}" >
                <input type="hidden" name="pickup_name" class="form-control" value="{{ $cab['PickUp']['PickUpName'] }}" >
                <input type="hidden" name="dropoff_detail_code" class="form-control" value="{{ $cab['DropOff']['DropOffDetailCode'] }}" >
                <input type="hidden" name="dropoff_name" class="form-control" value="{{ $cab['DropOff']['DropOffName'] }}" >
                <input type="hidden" name="pickup_date" class="form-control" value="{{$cab['PickUp']['PickUpDate']}}" >
                <input type="hidden" name="result_index" value="{{$cab['ResultIndex']}}" />
                <input type="hidden" name="transfer_code" value="{{$cab['TransferCode']}}" />
                <input type="hidden" name="vehicle_index" value="{{ $cab['Vehicles'][0]['VehicleIndex'] }}" />
                <input type="hidden" name="noofpax" value="1" />
                <input type="hidden" name="adultsFC" class="adultsFC" value="1">
                <input type="hidden" name="childsFC" class="childsFC" value="0">
                <input type="hidden" name="traceIDval" id="traceIDval" value="{{ $traceId }}" />
                <input type="hidden" name="search_id" value="{{$search_id}}">
                @else
                <div class="row" id="bookingForm">
                 <div class="raveller_informaion mrgintop_20 cabInfo">
                    <h3>{{ __('labels.traveller_info')}}</h3>
                      @csrf
                      <input type="hidden" name="file_name" id="file_name" value="cab">
                      <input type="hidden" name="preffered_currency" value="{{$input['preffered_currency']}}">
                      <input type="hidden" name="trace_id" value="{{ $traceId }}" />
                      <input type="hidden" name="cancellation_policy" value='<?php echo json_encode($cancellation_data); ?>' />
                      <input type="hidden" name="last_cancellation_date" value="{{ $last_cancel_date }}" />
                      <input type="hidden" name="pickup_detail_code" class="form-control" value="{{ $cab['PickUp']['PickUpDetailCode'] }}" >
                      <input type="hidden" name="pickup_name" class="form-control" value="{{ $cab['PickUp']['PickUpName'] }}" >
                      <input type="hidden" name="dropoff_detail_code" class="form-control" value="{{ $cab['DropOff']['DropOffDetailCode'] }}" >
                      <input type="hidden" name="dropoff_name" class="form-control" value="{{ $cab['DropOff']['DropOffName'] }}" >
                      <input type="hidden" name="pickup_date" class="form-control" value="{{$cab['PickUp']['PickUpDate']}}" >
                      <input type="hidden" name="result_index" value="{{$cab['ResultIndex']}}" />
                      <input type="hidden" name="transfer_code" value="{{$cab['TransferCode']}}" />
                      <input type="hidden" name="vehicle_index" value="{{ $cab['Vehicles'][0]['VehicleIndex'] }}" />
                      <input type="hidden" name="noofpax" value="1" />
                      <input type="hidden" name="adultsFC" class="adultsFC" value="1">
                      <input type="hidden" name="childsFC" class="childsFC" value="0">
                      <input type="hidden" name="traceIDval" id="traceIDval" value="{{ $traceId }}" />
                      <hr>
                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>{{ __('labels.first_name')}}</label>
                              <input type="text"  data-parsley-type="alphanum" name="passenger_first_name" class="form-control fn_1_1" placeholder="{{ __('labels.first_name')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>{{ __('labels.last_name')}}</label>
                              <input type="text" data-parsley-type="alphanum" name="passenger_last_name" class="form-control ln_1_1" placeholder="{{ __('labels.last_name')}}" required>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>{{ __('labels.email_id')}}</label>
                              <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" name="passenger_email" class="form-control email_1_1" placeholder="{{ __('labels.email_id')}}" required>
                              <div class="small_text">({{ __('labels.booking_email_confirm')}})</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>{{ __('labels.phone_number')}}</label>
                              <input type="number" name="phone" class="form-control" placeholder="{{ __('labels.phone_number')}}" required>
                            </div>
                        </div>
                      </div>
                      <hr>
                       <div class="row">
                         <div class="col-md-9">
                            <div class="form-group">
                              <label>{{ __('labels.pickup_detail_lbl')}}</label>
                              <input type="text" name="pickup_detailname" class="form-control" value="{{ $cab['PickUp']['PickUpDetailName'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <label>{{ __('labels.pickup_time_lbl')}}</label>
                              <input type="text" name="pickup_time" class="form-control" value="{{ $cab['PickUp']['PickUpTime'] }}" readonly>
                            </div>
                        </div>
                       </div>
                       <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>{{ __('labels.description')}}</label>
                              <input type="text" name="pickup_description" class="form-control" placeholder="{{ __('labels.description')}}" required>
                            </div>
                        </div>
                      </div>
                      <hr>
                       <div class="row">
                         <div class="col-md-9">
                            <div class="form-group">
                              <label>{{ __('labels.drop_detail_lbl')}}</label>
                              <input type="text" name="dropoff_detailname" class="form-control" value="{{ $cab['DropOff']['DropOffDetailName'] }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <label>{{ __('labels.drop_time_lbl')}}</label>
                              <input type="text" name="dropoff_time" class="form-control" value="{{ $cab['PickUp']['PickUpTime'] }}" readonly>
                            </div>
                        </div>
                       </div>
                       <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>{{ __('labels.description')}}</label>
                              <input type="text" name="dropoff_description" class="form-control" placeholder="Description" required>
                            </div>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form_inner_data">
                            <div class="form-group term_conditions">
                              <label class="check_container">{{ __('labels.i_agree')}} <a href="#" data-toggle="modal" data-target="#policyModel" class="agreement-link">{{ __('labels.cab_book_policy_lbl')}}</a>, <a href="/refund-policy" target="_blank" class="agreement-link">{{ __('labels.cab_cancel_policy_lbl')}}</a>,<a href="/privacy-policy" target="_blank" class="agreement-link">{{ __('labels.privacy_policy_lbl')}}</a>,{{ __('labels.user_agrement_lbl')}} <a href="/terms-conditions" target="_blank" class="agreement-link">{{ __('labels.terms_lbl')}}.</a>
                                <input type="checkbox" checked="checked" name="t&c" disabled required>
                                <span class="checkmark"></span>
                              </label>
                            </div>

                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group">
                          <input type="hidden" name="referral" value="{{ $referral }}">
                          @if($input['preffered_currency'] == 'ILS')
                            <input type="button" class="btn more_details show-ils-pay" value="{{ __('labels.pay_now')}}"  id="submit-btn">
                          @else
                            <input type="Submit" class="btn more_details" value="{{ __('labels.pay_now')}}"  onClick="stripePayCab(event);" id="submit-btn">
                          @endif
                          <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                          <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
                          <input type="hidden" name="search_id" value="{{$search_id}}">
                        </div>

                        <div id="loader">
                            <img alt="loader" src="https://phppot.com/demo/stripe-payment-gateway-integration-using-php/LoaderIcon.gif">
                        </div>

                      </div> 
                      <div id="error-message"></div>
                  </div>
                </div>
                @endif
               <!-- Form Ends -->

               <!-- ILS payment details -->
               <div class="row" id="ils-pay">
                <div class="raveller_informaion mrgintop_20 cabInfo">
                  <h4 style="margin:25px auto;padding-bottom:15px;border-bottom: 1px solid #ccc;">{{ __('labels.pay_mode')}}</h4>
                  <?php 
                  if (!Auth::guest()) {

                      $walletBal =  \Auth::user()->balance;
                      if ($walletBal > 1) { ?>
                          <div class="col-12">
                              <div class="form-check" style="margin:20px;">
                                  <input class="form-check-input" name="walletAmount" value="{{ wallet_blance()['amount'] }}" type="checkbox" value="" id="walletAmount">
                                  <label class="form-check-label" for="defaultCheck1">
                                      {{ __('labels.pay_wallet')}} ( {{ wallet_blance()['currency'] }} {{ number_format(wallet_blance()['amount'],2) }} )
                                  </label>
                              </div>
                          </div>
                          <?php
                      }
                  }
                  
                  ?>
                  <nav class="nav nav-pills flex-column flex-sm-row" role="tablist">
                    @if($paidAmtILS > 0)
                    <a class="flex-sm-fill text-sm-center nav-link" id="multiCardTab" data-toggle="tab" href="#multiple-payment" onclick="$('#paymentMode').val('multiple');">{{ __('labels.multi_card')}}</a>
                    @else
                      <a class="flex-sm-fill text-sm-center nav-link active" id="singleCardTab" data-toggle="tab" href="#single-payment" onclick="$('#paymentMode').val('single');">{{ __('labels.single_card')}}</a>
                      <a class="flex-sm-fill text-sm-center nav-link" id="multiCardTab" data-toggle="tab" href="#multiple-payment" onclick="$('#paymentMode').val('multiple');">{{ __('labels.multi_card')}}</a>
                    @endif
                  </nav>

                  <input type="hidden" name="walletPay" id="walletPay" value="no" >
                  <input type="hidden" name="walletDebit" id="walletDebit" value="0" >
                  <input type="hidden" id="agentMarkup"  value="0" >
                  <input type="hidden" name="paymentMode" id="paymentMode" value="single" >

                  <div class="tab-content" id="pills-tabContent">
                      <div style="padding:20px;" class="tab-pane fade" id="single-payment" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="row">

                          <input type="hidden" id="fullAmount"  value="{{ round($cab['FinalPrice'],2)}}" >

                          <input type="hidden" id="fullAmount_Install"  value="{{ round($final_price_install,2)}}" >
                          <input type="hidden" name="BOOKING_PRICE" id="BOOKING_PRICE" value="{{ round($cab['FinalPrice'],2)  }}">
                          <input type="hidden" name="ORIGINAL_BOOKING_PRICE_PME" id="ORIGINAL_BOOKING_PRICE_PME" value="{{ round($cab['FinalPrice'],2)  }}">

                          <div class="row">
                            <div class="col-xs-6 col-md-6">
                              <div class="form-group" id="installments-group">
                                <label for="installments-container" class="control-label">{{ __('labels.installments')}}</label>
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
                                <label for="installments-container" class="control-label">{{ __('labels.periodical_payments')}}</label>
                                  <span class="period_payment" style="font-size: 16px;color: #000;">{{ $cab['Vehicles'][0]['TransferPrice']['CurrencyCode'] }} {{ round($cab['FinalPrice'],2)}}</span>
                              </div>
                            </div>
                           <div class="col-xs-4 col-md-6">
                              <div class="form-group" id="installments-group">
                                <label for="installments-container" class="control-label">{{ __('labels.interest_amount')}}</label>
                                  <span class="amount_interest" style="font-size: 16px;color: #000;">{{ $cab['Vehicles'][0]['TransferPrice']['CurrencyCode'] }} 0</span>
                              </div>
                            </div>
                          
                          </div>
                          <div class="row">
                            <div class="col-xs-4 col-md-6">
                              <div class="form-group" id="installments-group">
                                <label for="installments-container" class="control-label">{{ __('labels.total_payment')}}</label>
                                  <span class="total_payment" style="font-size: 16px;color: #000;">{{ $cab['Vehicles'][0]['TransferPrice']['CurrencyCode'] }} {{ round($cab['FinalPrice'],2)}}</span>
                              </div>
                            </div>
                           <div class="col-xs-4 col-md-6">
                              
                            </div>
                          
                          </div>

                          <button type="button" id="submit-payme-api" class="btn btn-primary btn-open-pay-form">
                              {{ __('labels.pay_lbl')}} {{ number_format ( $final_price_install, 2)  }} ILS
                          </button>
                        </div>
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
                                                  {{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}} 
                                                  {{ number_format ( $cab['FinalPrice'], 2)  }}
                                              </div>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td >
                                              {{ __('labels.total_paid')}}:
                                          </td>
                                          <td align="right">
                                              <div class="total_paid" style="color:green;">
                                                  {{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}} 
                                                  <span class="paidAmount">{{$paidAmtILS}}</span>
                                              </div>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td >
                                              {{ __('labels.total_due')}}:
                                          </td>
                                          <td align="right">
                                              <div class="due_amount" style="color:red;">
                                                  {{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}} 
                                                  <span class="dueAmount">{{ number_format ( $cab['FinalPrice']  - $paidAmtILS, 2)  }}</span>
                                              </div>
                                          </td>
                                      </tr>
                                  </table>
                              </div>
                              <div class=" offset-md-6 col-lg-6">
                                  <div class="form-group  text-right">
                                      <label for="partAmount" >{{ __('labels.pay_amount')}}</label>
                                      <input type="text"  id="partAmountILS" name="partAmountILS" style="font-weight: bold;text-align: right;font-size: 20px;" class="form-control" value="0" >
                                              <input type="hidden" name="paidAmtILS" id="paidAmtILS" value="{{ isset($paidAmtILS) ? $paidAmtILS : 0 }}">
                                  </div>
                              </div>

                              <div class="row">
                                  <div class="col-xs-6 col-md-6"></div>
                                  <div class="col-xs-6 col-md-6">
                                    <div class="form-group" id="installments-group">
                                      <label for="installments-container" class="control-label">{{ __('labels.installments')}}</label>
                                      <select id="installments_val_multiple" name="installments_multiple" class="form-control">
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
                                      <label for="installments-container" class="control-label">{{ __('labels.periodical_payments')}}</label>
                                        <span class="period_payment_multiple" style="font-size: 16px;color: #000;">{{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}}  0</span>
                                    </div>
                                  </div>
                                 <div class="col-xs-4 col-md-6">
                                    <div class="form-group" id="installments-group">
                                      <label for="installments-container" class="control-label">{{ __('labels.interest_amount')}}</label>
                                        <span class="amount_interest_multiple" style="font-size: 16px;color: #000;">{{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}}  0</span>
                                    </div>
                                  </div>
                                
                                </div>
                                <div class="row">
                                  <div class="col-xs-4 col-md-6">
                                    <div class="form-group" id="installments-group">
                                      <label for="installments-container" class="control-label">{{ __('labels.total_payment')}}</label>
                                        <span class="total_payment_multiple" style="font-size: 16px;color: #000;">{{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}}  0</span>
                                    </div>
                                  </div>
                                 <div class="col-xs-4 col-md-6">
                                    
                                  </div>
                                
                                </div>



                              <div class="col-lg-12 text-right">
                                  <button  class="btn btn-primary multiCardPayILS" type="button" id="multiCardPayILS">{{ __('labels.pay_now')}}</button>                                              
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
               </div>
             </form>
            </div>
         </div>
        <div class="col-lg-4 col-md-5">
           <div class="right_detail_data">
              <div class="basic_price_data" style="display:none;">
                 <ul class="list-inline">
                    <li>
                       <div class="base_text">Base Price <span>
                          {{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}} 

                         <?php $base_price = $cab['FinalPrice']; ?>

                        {{number_format($base_price,2)}}</span>
                       </div>
                    </li>
                    <li>
                       <div class="base_text">Promo Discount <span>
                        {{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}} {{number_format($cab['Vehicles'][0]['TransferPrice']['Discount'],2)}}
                          </span>
                       </div>
                    </li>
                 </ul>
              </div>
              <div class="basic_price_data" style="display:none;">
                 <ul class="list-inline">
                    <li>
                       <div class="base_text">Tax &amp; Service Fees <span>
                        {{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}} 
                        <?php $tax = $cab['Vehicles'][0]['TransferPrice']['ServiceCharge'] + ( $commission / 100 * $cab['Vehicles'][0]['TransferPrice']['ServiceCharge'] ); ?>
                        {{number_format($tax,2)}}
                          </span>
                       </div>
                    </li>
                 </ul>
              </div>
              <?php
               
                $conversion_payment = 0;
                if($cab['Vehicles'][0]['TransferPrice']['CurrencyCode'] == 'INR') {
                  $conversion = env('CONVERSION_VAL_CAB'); 
                } else {
                  $conversion = env('CONVERSION_VAL_CAB');                         
                }

               // $f_price = $base_price + $tax;
                $conversion_payment = ( $conversion / 100 * $base_price );
              ?>
              <div class="basic_price_data" style="display:none;">
                 <ul class="list-inline">
                    <li>
                       <div class="base_text">Currency Conversion <span>
                        {{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}} {{number_format($conversion_payment,2)}}
                          </span>
                       </div>
                    </li>
                 </ul>
              </div>
               <div class="row">
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
                                              <div>{{ __('labels.offer_expiry')}}</div>
                                              <strong id="sessionExpiryTimer"></strong>
                                           </div>
                                        </div>
                                     </div>
                                  </div>
                               </div>
                            </div>
                            <hr>
                         <div class="total_payouts">
                            <div class="text_payable">{{ __('labels.total_payble')}}</div>
                            <div class="final_price">                  
                             {{$cab['Vehicles'][0]['TransferPrice']['CurrencyCode']}} {{number_format($cab['FinalPrice'],2)}}
                            </div>
                            <input type="hidden" name="BOOKING_PRICE" id="BOOKING_PRICE" value="{{ round($cab['FinalPrice'],2) }}">
                         </div>
                    </div>
               </div>
              <div class="pay_btns" id="pay_btns"><a href="javascript:void(0);" class="btn btn_pay_now" onClick="goTo('submit-btn');">{{ __('labels.pay_now')}}</a></div>
              <input type="hidden" name="RAZOR_KEY_ID" id="RAZOR_KEY_ID" value="{{ env('RAZOR_KEY_ID') }}">
              <input type="hidden" name="userName" id="userName" value="">
              <input type="hidden" name="userEmail" id="userEmail" value="">
              <input type="hidden" name="useraddress" id="useraddress" value="">
              <input type="hidden" name="CURRENCY_VAL" id="CURRENCY_VAL" value="{{ $cab['Vehicles'][0]['TransferPrice']['CurrencyCode'] }}">
              <input type="hidden" name="BOOKING_NAME" id="BOOKING_NAME" value="{{$cab['TransferName']}} {{ $cab['Vehicles'][0]['VehicleCode'] }}">
              <input type="hidden" name="BOOKING_DESC" id="BOOKING_DESC" value="{{ $cab['PickUp']['PickUpDate']}} {{$cab['PickUp']['PickUpTime'] }}">
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
                <h2 style="color:#fff;"><strong>{{ __('labels.please_wait')}}</strong></h2>
                <h3 style="color:#fff;">{{ __('labels.booking_in_process')}}</h3>        
            </div>
        </div>
    </div>
</div>
<div id="sessionWarningModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header refresh-header">
                <h3 class="refresh-header text-center">{{ __('labels.session_expired')}}</h3>
            </div>
            <div class="modal-body">
                <p>{{ __('labels.session_expired_msg')}}</p>        
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-primary refresh-btn show-cab-search">{{ __('labels.refresh_search')}}</a>
            </div>
        </div>
    </div>
</div>
@endsection