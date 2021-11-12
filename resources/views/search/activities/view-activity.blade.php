@extends('layouts.app-header')
@section('content')
<input type="hidden" id="search_id" value="{{ $search_id }}">
<input type="hidden" id="paymeUrl" value="{{ env('PAYME_URL') }}">
<input type="hidden" id="paymeKey" value="{{ env('PAYME_KEY') }}">
<section class="prdt_detail_sec" id="activityExpireDiv">
    <div class="container">
        <div class="row">
             <form method="GET" name="searchForm" id="searchActivityForm" action="{{ route('search_activities') }}"  style="display:none;">
                           @csrf
                          <div class="rounding_form_info">
                            <div class="form-group">
                               <label>{{ __('labels.start_date')}} <i class="fa fa-angle-down" aria-hidden="true"></i></label>
                               <div class="input-group">
                                  <input id="departHotel" class="form-control departdateAct" type="text" name="travelstartdate" required readonly value="{{ $input['travelstartdate'] }}"/>
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                               </div>
                            </div>
                            <div class="form-group">
                               <label>{{ __('labels.cityarea')}}</label>
                               <select name="city_name" class="auto-complete act-city" required>
                                 <option value="{{ $input['city_name'] }}">{{ $input['city_name'] }}</option>
                               </select>
                               <input type="hidden" name="city_act_id" id="city_act_id" value="{{$input['city_act_id']}}">
                               <input type="hidden" name="currency_code_act" id="currency_code_act" value="{{$input['currency_code_act']}}">
                               <div class="small_station_info selected-hotel-city"></div>
                               <div class="switcher_data"><img src="{{ asset('images/switch_icon.png')}}" alt="switcher"></div>
                            </div>
                            <input type="hidden" name="childAge1" value="{{$input['childAge1']}}"  />
                            <input type="hidden" name="childAge2" value="{{$input['childAge2']}}"  />                  
                            <input type="hidden" name="childAge3" value="{{$input['childAge3']}}"  />
                            <input type="hidden" name="childAge4" value="{{$input['childAge4']}}"  />

                            <div class="form-group">
                             <label>{{ __('labels.traveller')}}</label>
                             <input type="text" name="travellersClass" id="travellersClassactOne" readonly class="form-control" required data-parsley-required-message="Please travllers & class." placeholder="1 Traveller" value="1 Traveller">
                              <div class="travellers gbTravellers travellersClassactOne">
                                 <div class="appendBottom20">
                                    <p data-cy="adultRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.adults')}}</p>
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
                                          <p data-cy="childrenRange" class="latoBold font12 grayText appendBottom10">{{ __('labels.children')}}</p>
                                          <ul id="childCount1" class="childCountCab guestCounter font12 darkText clCCA">
                                             <li data-cy="0" class="selected">0</li>
                                             <li data-cy="1" class="">1</li>
                                             <li data-cy="2" class="">2</li>
                                             <li data-cy="3" class="">3</li>
                                             <li data-cy="4" class="">4</li>
                                          </ul>
                                          <ul class="childAgeList appendBottom10">
                                             <li class="childAgeSelector " id="childAgeSelector1Cab1">
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child1age')}}</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child1AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge12">
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
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child2age')}}</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child2AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge22">
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
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child3age')}}</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child3AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge32">
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
                                                <span data-cy="childAgeLabel-1" class="latoBold font12 grayText appendBottom10">{{ __('labels.child4age')}}</span>
                                                <label class="lblAge" for="0">
                                                   <select id="child4AgeRoom1" data-cy="childAge-0" class="ageSelectBox" name="childAge42">
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
                                       <button type="button" data-cy="travellerApplyBtn" class="primaryBtn btnApply pushRight ">{{ __('labels.apply')}}</button>
                                    </div>
                                 </div>
                              </div>
                            </div>
                          </div>
                          <div class="search_btns_listing">
                               <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                               <input type="hidden" name="adultsCCA" class="adultsCCA" value="{{ $input['adultsCCA'] }}">
                               <input type="hidden" name="childsCCA" class="childsCCA" value="{{ $input['childsCCA'] }}">
                               <button type="submit" class="btn btn-primary">{{ __('labels.search')}} <img src="{{ asset('images/search_arrow.png')}}" alt="search"></button>
                            </div>
                     </form>

            <p id="timerBook"></p>
            <div class="col-lg-8 col-md-7">
                <div class="inner_left_prdct_data">
                    <div class="row cabInfo">
                        <div class="col-lg-6 col-md-12">
                            <div class="prdct_full_img">
                                <img src="{{ $tourImage }}" alt="{{ $tourImage }}" style="height: 250px;width: 350px;" >
                            </div>
                        </div>
                        <!-- <div class="col-lg-1 col-md-12"></div> -->
                        <div class="col-lg-6 col-md-12">
                            <div class="prdct_detail_data">
                                <h4>{{ $activities['SightseeingName'] }} - {{ $activities['SightseeingCode'] }}</h4>
                                <div class="check_out">
                                    <ul class="list-inline">
                                        <li>
                                            <h5>{{ __('labels.from')}}:</h5>
                                            <p>{{$activities['FromDate']}}</p>
                                        </li>
                                        <li>
                                            <h5>{{ __('labels.to')}}:</h5>
                                            <p>{{$activities['ToDate']}}</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 alert-success cancellation_text_cab">
                            <?php if (isset($activities['TourPlan']['CancellationPolicy'])) { ?>
                                <span class="cancellation_policy"> {{ __('labels.hotel_cancel_policy_lbl')}}y: {{ $activities['TourPlan']['CancellationPolicy'] }} </span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="room_information mrgintop_20 cabInfo">
                            <div class="col-lg-12 col-md-12">
                                <div class="inner_cab_detail">
                                    <h4>{{ __('labels.tour_desc_lbl')}}:</h4>
                                    {!!html_entity_decode($activities['TourDescription'])!!}

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Booking Form -->
                    <div class="row">
                      <form method="POST" class="traveller_form" id="BookRoomForm" name="BookingForm" action="{{ route('bookActivity') }}">
                        @if($paidAmtILS > 0)

                          <input type="hidden" name="file_name" id="file_name" value="activity">
                          <input type="hidden" name="preffered_currency" value="{{$activities['TourPlan']['Price']['CurrencyCode']}}">
                          <input type="hidden" name="trace_id" value="{{ $traceId }}" />
                          <input type="hidden" name="from_date" value="{{ $activities['FromDate'] }}" />
                          <input type="hidden" name="search_id" value="{{ $search_id }}" />
                          <input type="hidden" name="SightseeingName" value="{{ $activities['SightseeingName'] }}" />
                          <input type="hidden" name="SightseeingCode" value="{{ $activities['SightseeingCode'] }}" />
                          <input type="hidden" name="currency" value=" {{$activities['TourPlan']['Price']['CurrencyCode']}}" />
                          <input type="hidden" name="ResultIndex" value="{{ $ResultIndex }}" />
                          <input type="hidden" name="TourIndex" value="{{ $TourIndex }}" />
                          <input type="hidden" name="TourImage" value="{{ $tourImage }}" />
                          @if($price_changed)
                          <input type="hidden" name="price_block" value='<?php echo json_encode($activities['TourPlan']['Price']); ?>' />
                          <input type="hidden" name="price_block_null" value="yes" />
                          @else
                          <input type="hidden" name="price_block_null" value="no" />
                          @endif
                          <input type="hidden" name="amount" value="{{ round($FinalPrice) }}" />

                          <input type="hidden" name="amount_tbo" value="{{ $activities['TourPlan']['Price']['OfferedPriceRoundedOff'] }}" />

                          <input type="hidden" name="amount_commission_agent" value="{{ round($inis_markup_agent) }}" />

                          <input type="hidden" name="amount_commission" value="{{ round($inis_markup_view) }}" />


                          <input type="hidden" name="adultsCCA" class="adultsCCA" value="{{ $adultCount }}">
                          <input type="hidden" name="childsCCA" class="childsCCA" value="{{ $ChildCount }}">
                          <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                                
                        @else
                        <div class="raveller_informaion mrgintop_20 cabInfo" id="bookingForm">
                            <h3>{{ __('labels.traveller_info')}}</h3>                            
                                @csrf
                                <input type="hidden" name="file_name" id="file_name" value="activity">
                                <input type="hidden" name="preffered_currency" value="{{$activities['TourPlan']['Price']['CurrencyCode']}}">
                                <input type="hidden" name="trace_id" value="{{ $traceId }}" />
                                <input type="hidden" name="from_date" value="{{ $activities['FromDate'] }}" />
                                <input type="hidden" name="search_id" value="{{ $search_id }}" />
                                <input type="hidden" name="SightseeingName" value="{{ $activities['SightseeingName'] }}" />
                                <input type="hidden" name="SightseeingCode" value="{{ $activities['SightseeingCode'] }}" />
                                <input type="hidden" name="currency" value=" {{$activities['TourPlan']['Price']['CurrencyCode']}}" />
                                <input type="hidden" name="ResultIndex" value="{{ $ResultIndex }}" />
                                <input type="hidden" name="TourIndex" value="{{ $TourIndex }}" />
                                <input type="hidden" name="TourImage" value="{{ $tourImage }}" />
                                @if($price_changed)
                                <input type="hidden" name="price_block" value='<?php echo json_encode($activities['TourPlan']['Price']); ?>' />
                                <input type="hidden" name="price_block_null" value="yes" />
                                @else
                                <input type="hidden" name="price_block_null" value="no" />
                                @endif

                                <input type="hidden" name="paymentMode" id="paymentMode" value="single" >
                                <input type="hidden" name="traceIDval" id="traceIDval" value="{{ $traceId }}" />

                                <input type="hidden" name="amount" value="{{ round($FinalPrice) }}" />

                                <input type="hidden" name="amount_tbo" value="{{ $activities['TourPlan']['Price']['OfferedPriceRoundedOff'] }}" />

                                <input type="hidden" name="amount_commission_agent" value="{{ round($inis_markup_agent) }}" />

                                <input type="hidden" name="amount_commission" value="{{ round($inis_markup_view) }}" />


                                <input type="hidden" name="adultsCCA" class="adultsCCA" value="{{ $adultCount }}">
                                <input type="hidden" name="childsCCA" class="childsCCA" value="{{ $ChildCount }}">
                                <input type="hidden" name="referral" class="referral" value="{{ $referral }}">

                                @for($i=1; $i <= $adultCount; $i++ )
                                @if($adultCount > 1)
                                <hr>
                                @endif
                                <hr>
                                <span class="adultNo"><b>{{ __('labels.adult')}} {{ $i }} </b></span>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('labels.title')}}</label>
                                            <select  name="adult_title_{{$i}}"  class="form-control" required data-parsley-pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$" data-parsley-trigger="keyup" data-parsley-required-message="Title is required." autocomplete="off">
                                                <option value="Mr">{{ __('labels.Mr')}}</option>
                                                <option value="Mrs">{{ __('labels.Mrs')}}</option>
                                                <option value="Miss">{{ __('labels.Miss')}}</option>
                                                <option value="Ms">{{ __('labels.Ms')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.first_name')}}</label>
                                            <input type="text"  data-parsley-type="alphanum" name="adult_passenger_first_name_{{$i}}" class="form-control fn_1_{{$i}}" placeholder="{{ __('labels.first_name')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.last_name')}}</label>
                                            <input type="text" data-parsley-type="alphanum" name="adult_passenger_last_name_{{$i}}" class="form-control ln_1_{{$i}}" placeholder="{{ __('labels.last_name')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.email_id')}}</label>
                                             @if(!Auth::guest())
                                              <input type="email" value="{{ Auth::user()->email}}"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" name="adult_passenger_email_{{$i}}" class="form-control email_1_{{$i}}" placeholder="{{ __('labels.email_id')}}" required>
                                             @else
                                              <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" name="adult_passenger_email_{{$i}}" class="form-control email_1_{{$i}}" placeholder="{{ __('labels.email_id')}}" required>                   
                                             @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.phone_number')}}</label>
                                            <input type="number" name="adult_phone_{{$i}}" class="form-control" placeholder="{{ __('labels.phone_number')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.passport_no')}}</label>
                                            <input type="text" name="adult_passport_no_{{$i}}" class="form-control" placeholder="{{ __('labels.passport_no')}}" required>
                                        </div>
                                    </div>
                                    @if($ispan)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.pan_no')}}</label>
                                            <input type="text" name="adult_pan_no_{{$i}}" class="form-control" placeholder="{{ __('labels.pan_no')}}" required>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endfor

                                @for($i=1; $i <= $ChildCount; $i++ )
                                @if($ChildCount > 1)
                                <hr>
                                @endif
                                <hr>
                                <span class="adultNo"><b>Child {{ $i }} </b></span>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('labels.title')}}</label>
                                            <select  name="child_title_{{$i}}"  class="form-control" required data-parsley-pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$" data-parsley-trigger="keyup" data-parsley-required-message="Title is required." autocomplete="off">
                                                <option value="Mr">{{ __('labels.Mr')}}</option>
                                                <option value="Mrs">{{ __('labels.Mrs')}}</option>
                                                <option value="Miss">{{ __('labels.Miss')}}</option>
                                                <option value="Ms">{{ __('labels.Ms')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.first_name')}}</label>
                                            <input type="text"  data-parsley-type="alphanum" name="child_passenger_first_name_{{$i}}" class="form-control" placeholder="{{ __('labels.first_name')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.last_name')}}</label>
                                            <input type="text" data-parsley-type="alphanum" name="child_passenger_last_name_{{$i}}" class="form-control" placeholder="{{ __('labels.last_name')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.email_id')}}</label>
                                            <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" name="child_passenger_email_{{$i}}" class="form-control" placeholder="{{ __('labels.email_id')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.phone_number')}}</label>
                                            <input type="number" name="child_phone_{{$i}}" class="form-control" placeholder="{{ __('labels.phone_number')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('labels.dob')}}</label>
                                            <input type="text" name="child_dob_{{$i}}" class="form-control dob" required placeholder="{{ __('labels.dob')}}" data-parsley-trigger="keyup" data-parsley-required-message="DOB is required." autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                @endfor

                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form_inner_data">
                                            <div class="form-group term_conditions">
                                                <label class="check_container">{{ __('labels.i_agree')}} <a href="#" data-toggle="modal" data-target="#policyModel" class="agreement-link">{{ __('labels.act_book_policy_lbl')}}</a>, <a href="/refund-policy" target="_blank" class="agreement-link">{{ __('labels.act_cancel_policy_lbl')}}</a>,<a href="/privacy-policy" target="_blank" class="agreement-link">{{ __('labels.privacy_policy_lbl')}}</a>,{{ __('labels.user_agrement_lbl')}} <a href="/terms-conditions" target="_blank" class="agreement-link">{{ __('labels.terms_lbl')}}.</a>
                                                    <input type="checkbox" checked="checked" name="t&c" disabled required>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                       @if($activities['TourPlan']['Price']['CurrencyCode'] == 'ILS')
                                        <input type="button" class="btn more_details show-ils-pay" value="{{ __('labels.pay_now')}}"  id="submit-btn">
                                      @else
                                        <input type="Submit" class="btn more_details" value="{{ __('labels.pay_now')}}"  onClick="stripePayActivity(event);" id="submit-btn">
                                      @endif
                                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                                        <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
                                    </div>

                                    <div id="loader">
                                        <img alt="loader" src="https://phppot.com/demo/stripe-payment-gateway-integration-using-php/LoaderIcon.gif">
                                    </div>

                                </div> 
                                <div id="error-message"></div>

                        </div>
                        @endif
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
                          <input type="hidden" id="agentMarkup"  value="0" >
                          <input type="hidden" name="walletPay" id="walletPay" value="no" >
                          <input type="hidden" name="walletDebit" id="walletDebit" value="0" >
                          <div class="tab-content" id="pills-tabContent">
                              <div style="padding:20px;" class="tab-pane fade" id="single-payment" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="row">

                                  <input type="hidden" id="fullAmount"  value="{{ round($FinalPrice,2)}}" >

                                  <input type="hidden" id="fullAmount_Install" name="fullAmount_Install" value="{{ round($FinalPrice,2)}}" >
                                  <input type="hidden" name="BOOKING_PRICE" id="BOOKING_PRICE" value="{{ round($FinalPrice,2)  }}">
                                  <input type="hidden" name="ORIGINAL_BOOKING_PRICE_PME" id="ORIGINAL_BOOKING_PRICE_PME" value="{{ round($FinalPrice,2)  }}">
                                  <input type="hidden" name="installment_price" id="installment_price" value="0">

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
                                          <span class="period_payment" style="font-size: 16px;color: #000;">{{ $activities['TourPlan']['Price']['CurrencyCode'] }} {{ round($FinalPrice,2)}}</span>
                                      </div>
                                    </div>
                                   <div class="col-xs-4 col-md-6">
                                      <div class="form-group" id="installments-group">
                                        <label for="installments-container" class="control-label">{{ __('labels.interest_amount')}}</label>
                                          <span class="amount_interest" style="font-size: 16px;color: #000;">{{ $activities['TourPlan']['Price']['CurrencyCode'] }} 0</span>
                                      </div>
                                    </div>
                                  
                                  </div>
                                  <div class="row">
                                    <div class="col-xs-4 col-md-6">
                                      <div class="form-group" id="installments-group">
                                        <label for="installments-container" class="control-label">{{ __('labels.total_payment')}}</label>
                                          <span class="total_payment" style="font-size: 16px;color: #000;">{{ $activities['TourPlan']['Price']['CurrencyCode'] }} {{ round($FinalPrice,2)}}</span>
                                      </div>
                                    </div>
                                   <div class="col-xs-4 col-md-6">
                                      
                                    </div>
                                  
                                  </div>

                                  <button type="button" id="submit-payme-api" class="btn btn-primary btn-open-pay-form">
                                      {{ __('labels.pay_lbl')}} {{ number_format ( $FinalPrice, 2)  }} ILS
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
                                                          {{$activities['TourPlan']['Price']['CurrencyCode']}} 
                                                          {{ number_format ( $FinalPrice, 2)  }}
                                                      </div>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td >
                                                      {{ __('labels.total_paid')}}:
                                                  </td>
                                                  <td align="right">
                                                      <div class="total_paid" style="color:green;">
                                                          {{$activities['TourPlan']['Price']['CurrencyCode']}} 
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
                                                          {{$activities['TourPlan']['Price']['CurrencyCode']}} 
                                                          <span class="dueAmountILS">{{ number_format ( $FinalPrice - $paidAmtILS, 2)  }}</span>
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
                                                <span class="period_payment_multiple" style="font-size: 16px;color: #000;">{{$activities['TourPlan']['Price']['CurrencyCode']}}  0</span>
                                            </div>
                                          </div>
                                         <div class="col-xs-4 col-md-6">
                                            <div class="form-group" id="installments-group">
                                              <label for="installments-container" class="control-label">{{ __('labels.interest_amount')}}</label>
                                                <span class="amount_interest_multiple" style="font-size: 16px;color: #000;">{{$activities['TourPlan']['Price']['CurrencyCode']}}  0</span>
                                            </div>
                                          </div>
                                        
                                        </div>
                                        <div class="row">
                                          <div class="col-xs-4 col-md-6">
                                            <div class="form-group" id="installments-group">
                                              <label for="installments-container" class="control-label">{{ __('labels.total_payment')}}</label>
                                                <span class="total_payment_multiple" style="font-size: 16px;color: #000;">{{$activities['TourPlan']['Price']['CurrencyCode']}}  0</span>
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
                    <!-- Form Ends -->
                </div>
            </div>
            <div class="col-lg-4 col-md-5">
                <div class="right_detail_data">
                    
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
                                    {{$activities['TourPlan']['Price']['CurrencyCode']}} {{ number_format($FinalPrice , 2)  }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pay_btns" id="pay_btns"><a href="javascript:void(0);" class="btn btn_pay_now" onClick="goTo('submit-btn');">{{ __('labels.pay_now')}}</a></div>
                    <input type="hidden" name="BOOKING_PRICE" id="BOOKING_PRICE" value="{{ round($FinalPrice) }}">         
                    <input type="hidden" name="RAZOR_KEY_ID" id="RAZOR_KEY_ID" value="{{ env('RAZOR_KEY_ID') }}">
                    <input type="hidden" name="userName" id="userName" value="">
                    <input type="hidden" name="userEmail" id="userEmail" value="">
                    <input type="hidden" name="useraddress" id="useraddress" value="">
                    <input type="hidden" name="CURRENCY_VAL" id="CURRENCY_VAL" value="{{$activities['TourPlan']['Price']['CurrencyCode']}}">
                    <input type="hidden" name="BOOKING_NAME" id="BOOKING_NAME" value="{{ $activities['SightseeingName'] }} - {{ $activities['SightseeingCode'] }}">
                    <input type="hidden" name="BOOKING_DESC" id="BOOKING_DESC" value="{{$activities['FromDate']}}">
                </div>
            </div>
        </div>
    </div>
</section>
<div id="sessionWarningModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header refresh-header">
                <h3>{{ __('labels.session_expired')}}</h3>
            </div>
            <div class="modal-body">
                <p>{{ __('labels.session_expired_msg')}}</p>
            </div>
            <div class="modal-footer">
                <a href="/" class="btn btn-primary show-activity-search">{{ __('labels.refresh_search')}}</a>
            </div>
        </div>
    </div>
</div>
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

@endsection