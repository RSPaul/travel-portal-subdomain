@extends('layouts.app-header')
@section('content')
<?php

use App\Models\Lottery;
use App\Models\LotteryUsers;
?>
<style>
    .nav-pills .nav-link{
        background:#e6e6e6;
        margin:5px;
    }


    #grad1 {
        background-color: #9C27B0;
        background-image: linear-gradient(120deg, #FF4081, #81D4FA)
    }

    #BookRoomForm {
        text-align: center;
        position: relative;
        margin-top: 0px
    }

    #BookRoomForm fieldset .form-card {
        background: white;
        border: 0 none;
        border-radius: 0px;
        padding: 20px;
        box-sizing: border-box;
        width: 100%;
        margin: 0px;
        position: relative
    }

    #BookRoomForm fieldset {
        background: white;
        border: 0 none;
        border-radius: 0.5rem;
        box-sizing: border-box;
        width: 100%;
        margin: 0;
        padding-bottom: 20px;
        position: relative
    }

    #BookRoomForm fieldset:not(:first-of-type) {
        display: none
    }

    #BookRoomForm fieldset .form-card {
        text-align: left;
        color: #9E9E9E
    }



    #BookRoomForm .action-button {
        width: 100px;
        background: skyblue;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px
    }

    #BookRoomForm .action-button:hover,
    #BookRoomForm .action-button:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
    }

    #BookRoomForm .action-button-previous {
        width: 100px;
        background: #616161;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px
    }

    #BookRoomForm .action-button-previous:hover,
    #BookRoomForm .action-button-previous:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
    }

    select.list-dt {
        border: none;
        outline: 0;
        border-bottom: 1px solid #ccc;
        padding: 2px 5px 3px 5px;
        margin: 2px
    }

    select.list-dt:focus {
        border-bottom: 2px solid skyblue
    }

    .card {
        z-index: 0;
        border: none;
        border-radius: 0.5rem;
        position: relative
    }

    .fs-title {
        font-size: 25px;
        color: #2C3E50;
        margin-bottom: 10px;
        font-weight: bold;
        text-align: left
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey
    }

    #progressbar .active {
        color: #000000
    }

    #progressbar li {
        list-style-type: none;
        font-size: 12px;
        width: 25%;
        float: left;
        position: relative
    }

    #progressbar #traveller:before {
        font-family: FontAwesome;
        content: "\f0c0"
    }

    #progressbar #payment:before {
        font-family: FontAwesome;
        content: "\f09d"
    }

    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 18px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto;
        padding: 2px
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: #1e4355;
    }

    #accordion .card-header{
        border:1px solid #ccc;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    #accordion .collapse{
        border:1px solid #ccc;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }

    .room_data_image img{
        border-radius:0px;
    }

</style>
<script>

    $(document).ready(function () {

        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;

        $(".next").click(function () {

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            $('#BookRoomForm').parsley().validate();

            if ($('#fn_1_1').val() != '' && $('#fn_1_1').val() == $('#ln_1_1').val()) {

                $(".error-message-name").show();
                $(".error-message-name").html("First Name and Last Name can not be same.").show();

            }else{

                $(".error-message-name").hide();
                //$(".error-message-name").html("");

                if ($('#BookRoomForm').parsley().isValid()) {

    //Add Class Active
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

    //show the next fieldset
                    next_fs.show();
    //hide the current fieldset with style
                    current_fs.animate({opacity: 0}, {
                        step: function (now) {
    // for making fielset appear animation
                            opacity = 1 - now;

                            current_fs.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            next_fs.css({'opacity': opacity});
                        },
                        duration: 600
                    });
                }
            }

        });

        $(".previous").click(function () {

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

//Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
            previous_fs.show();

//hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function (now) {
// for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({'opacity': opacity});
                },
                duration: 600
            });
        });

    //get date for auto form
    $.ajax({
        type: "GET",
        url: "/api/getFormData",
        success: function (response)
        {

         if(response.success) {

            for(var key in response.data.roomPassengers) {
                var adults = response.data.roomPassengers[key]['adult'];
                var childs = response.data.roomPassengers[key]['child'];

                for(var a in adults) {

                    $('#roomPassengers_'+ key +'_adult_' + a +'_title').val(adults[a]['title']);
                    $('#roomPassengers_'+ key +'_adult_' + a +'_first_name').val(adults[a]['first_name']);
                    $('#ln_'+ key +'_' + a).val(adults[a]['last_name']);
                    $('#email_'+ key +'_' + a).val(adults[a]['email']);
                    $('#ph_'+ key +'_' + a).val(adults[a]['phone']);
                    $('#roomPassengers_'+ key + '_adult_' + a + '_panNo').val(adults[a]['panNo']);
                    $('#roomPassengers_'+ key + '_adult_' + a + '_passportExpire').val(adults[a]['passportExpire']);
                    $('#roomPassengers_'+ key + '_adult_' + a + '_passportNo').val(adults[a]['passportNo']);
                    $('#roomPassengers_'+ key + '_adult_' + a +'_address').val(adults[a]['address']);

                    $('#baggage_' + a).val(response.data['baggage_' + a]);
                    $('#meallcc_' + a).val(response.data['meallcc_' + a]);
                    $('#baggage_return' + a).val(response.data['baggage_return' + a]);
                    $('#meallcc_return' + a).val(response.data['meallcc_return' + a]);
                }

                for(var c in childs) {
                    
                    $('#roomPassengers_'+ key +'_child_'+ c + '_title').val(childs[c]['title']);
                    $('#roomPassengers_'+ key +'_child_'+ c + '_first_name').val(childs[c]['first_name']);
                    $('#roomPassengers_'+ key +'_child_'+ c + '_last_name').val(childs[c]['last_name']);
                    $('#roomPassengers_'+ key +'_child_'+ c + '_child_email').val(childs[c]['child_email']);
                    $('#roomPassengers_'+ key +'_child_'+ c + '_child_phone').val(childs[c]['child_phone']);
                    $('#roomPassengers_'+ key +'_child_'+ c + '_panNo').val(childs[c]['panNo']);
                    $('#roomPassengers_'+ key +'_child_'+ c + '_child_pass_expiry_date').val(childs[c]['child_pass_expiry_date']);
                    $('#roomPassengers_'+ key +'_child_'+ c + '_child_passport_no').val(childs[c]['child_passport_no']);
                    $('#roomPassengers_'+ key +'_child_'+ c + '_address').val(childs[c]['address']);
                    $('#roomPassengers_'+ key +'_child_'+ c + '_child_dob').val(childs[c]['child_dob']);

                    $('#child_baggage_' + c).val(response.data['child_baggage_' + c]);
                    $('#child_meallcc_' + c).val(response.data['child_meallcc_' + c]);
                    $('#child_baggage_return' + c).val(response.data['child_baggage_return' + c]);
                    $('#child_meallcc_return' + c).val(response.data['child_meallcc_return' + c]);
                }
            }

         }
        }
    });

    });

    function onlyNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
                && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    $(document).ready(function () {
        var payType = $('#paymentMode').val();

        $('input').blur(function() {
            var formData = $('#BookRoomForm').serialize();
            $.ajax({
                type: "POST",
                url: "/api/saveHotelFormData",
                //contentType: "application/json",
                data: formData,
                success: function (data)
                {
                 
                }
            });
        });
        
        $('select').change(function() {
            var formData = $('#BookRoomForm').serialize();
            $.ajax({
                type: "POST",
                url: "/api/saveHotelFormData",
                //contentType: "application/json",
                data: formData,
                success: function (data)
                {
                 
                }
            });
        });

        $(".multiCardPay").click(function () {

            

            //CHECK IF PAYING WITH WALLET
            if($('#walletAmount').is(":checked")) {
                var bookprice = $("#BOOKING_PRICE").val();
                var walletprice = $('#walletAmount').val();

                var walletamount = parseFloat(walletprice);
                var bookingPrice = parseFloat(bookprice).toFixed(2);

                if (walletamount > bookingPrice) {
                    walletamount = bookingPrice;
                    payble = 0;
                } else {
                    var payble = bookingPrice - walletamount;
                }

                $("#BOOKING_PRICE").val(payble);
                $("#ORIGINAL_BOOKING_PRICE").val(payble);

                if(payble == 0) {
                    document.BookingForm.submit();
                }

            } else {
                if(payType === 'single') {
                    $('#partAmount').attr('data-parsley-required', 'false');
                    payAmt = $('#fullAmount').val();
                    if (!isNaN(payAmt)) {
                        makePartPayment(payAmt);
                    }
                } else {

                    $('#partAmount').attr('data-parsley-required', 'true');
                    $("#partAmount").attr('data-parsley-pattern', '^[0-9]*\.[0-9]*$');
                    $('#BookRoomForm').parsley();

                    $('#BookRoomForm').parsley().validate();

                    if ($('#BookRoomForm').parsley().isValid()) {

                        var payAmt = $("#partAmount").val();
                        if (!isNaN(payAmt)) {
                            makePartPayment(payAmt);
                        }
                    }
                }
            }
        });

        function savePartPayment(payresp, paidAmt) {

            var uemail = $('#email_1_1').val();
            var totprice = $("#BOOKING_PRICE").val();
            var name = $('#fn_1_1').val() + ' ' + $('#ln_1_1').val();
            var contact = $('#ph_1_1').val();
            var curcy = $("#CURRENCY_VAL").val();

            $("#bookingInProgress").modal("show");
            $.ajax({
                type: "POST",
                url: "/recordpayment",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "paidAmt": paidAmt,
                    "email": uemail,
                    "bookprice": totprice,
                    "payres": payresp,
                    "name": name,
                    "contact": contact,
                    "currency": curcy
                },
                success: function (data)
                {
                    $("#bookingInProgress").modal("hide");
                    $("#razorpay_payment_id").val(data.payment.txn_id);
                    $("#razorpay_signature").val(payresp.razorpay_signature);
                    $(".paidAmount").html(data.paid);
                    $("#totalPaid").val(data.paid);
                    $(".dueAmount").html(data.due);
                    $('#email_1_1').attr("readonly", true);
                    $("#partAmount").val(0);
                    $("#singleCardTab").addClass("disabled");
                    $("#BookRoomForm .previous").hide();
                    $("#walletPaySwitch").hide();
                    if (data.due <= 0) {
                        $("#bookingInProgress").modal("show");
                        document.BookingForm.submit();
                    }
                }
            });
        }

        function  makePartPayment(payAmt) {
            console.log(payAmt);
            var curcy = $("#CURRENCY_VAL").val();

            var partPayOptn = {
                "key": $("#RAZOR_KEY_ID").val(), // Enter the Key ID generated from the Dashboard
                "amount": payAmt * 100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                "currency": curcy,
                "name": "Trip Heist",
                "description": "Partial Payment",
                "image": "https://tripheist.com/images/logo.png",
                "order_id": "", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                "handler": function (response) {
                    //$("#razorpay_payment_id").val(response.razorpay_payment_id);
                    //$("#razorpay_signature").val(response.razorpay_signature);
                    savePartPayment(response, payAmt);
                },
                "prefill": {
                    "name": $('#fn_1_1').val() + ' ' + $('#ln_1_1').val(),
                    "email": $('#email_1_1').val(),
                    "contact": $('#ph_1_1').val()
                },
                "notes": {
                    "address": ''
                },
                "theme": {
                    "color": "#3399cc"
                }
            };

            var rzp1 = new Razorpay(partPayOptn);

            rzp1.on('payment.failed', function (response) {
                alert(response.error.code);
                alert(response.error.description);
                alert(response.error.source);
                alert(response.error.step);
                alert(response.error.reason);
                alert(response.error.metadata.order_id);
                alert(response.error.metadata.payment_id);
            });

            if(payType === 'single') {
                rzp1.open();
            } else {
                if ($('#BookRoomForm').parsley().isValid()) {
                    rzp1.open();
                }
            }

        }

    });

</script>
<input type="hidden" id="isViewHotel" value="1">
<input type="hidden" id="search_id" value="{{ $search_id }}">
<input type="hidden" id="paymeUrl" value="{{ env('PAYME_URL') }}">
<input type="hidden" id="paymeKey" value="{{ env('PAYME_KEY') }}">
<form method="GET" name="searchForm" id="searchRoomsForm" action="{{ route('search_flight_rooms')}}"  >
    @csrf            
    <!-- <input id="autocomplete" name="Location" value="{{  isset($input_data['Location']) ? $input_data['Location'] : ''}}"  placeholder="Enter City , Hotel , Address"  onFocus="geolocate()" type="hidden" style="color:#333;border:1px solid #ccc;border-radius: 5px;padding:8px;font-size:14px;width:95%;" />
    <input type="hidden" name="Latitude" id="Latitude" value="{{  isset($input_data['Latitude']) ? $input_data['Latitude'] : ''}}">
    <input type="hidden" name="Longitude" id="Longitude" value="{{  isset($input_data['Longitude']) ? $input_data['Longitude'] : ''}}">
    <input type="hidden" name="Radius" id="Radius" value="80">
    <input type="hidden" name="city_id" id="city_id" value="{{  $input_data['city_id']}}">
    <input type="hidden" name="city_name" id="city_name" value="{{  $input_data['city_name']}}">
    <input type="hidden" name="countryCode" id="country_code" value="{{ $input_data['countryCode']}}">
    <input type="hidden" name="countryName" id="country_name" value="{{ $input_data['countryName']}}">
    <input type="hidden" name="country" id="country" value="{{ $input_data['user_country']}}">
    <input type="hidden" name="currency" id="currency" value="{{ $input_data['currency']}}">
    <input type="hidden" name="referral" id="referral" value="{{ $referral}}">
    <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="{{ $hotel_code}}">
    <input type="hidden" name="ishalal" id="ishalal" value="{{ (Session::get('active_tab')=='halal')?1:0 }}">
    <input class="form-control departdate" type="hidden" name="departdate" required readonly value="{{ $input_data['departdate']}}"/>
    <input class="form-control returndate" type="hidden" name="returndate" readonly required value="{{ $input_data['returndate']}}"/>
    <input type="hidden" name="roomsGuests" id="roomsGuests" class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ $input_data['roomsGuests']}}"> -->
    <input id="autocomplete" name="Location" value="{{  isset($input_data['Location']) ? $input_data['Location'] : ''}}"  placeholder="Enter City , Hotel , Address"  onFocus="geolocate()" type="hidden" style="color:#333;border:1px solid #ccc;border-radius: 5px;padding:8px;font-size:14px;width:95%;" />
    <input type="hidden" name="Latitude" id="Latitude" value="{{  isset($input_data['Latitude']) ? $input_data['Latitude'] : ''}}">
    <input type="hidden" name="Longitude" id="Longitude" value="{{  isset($input_data['Longitude']) ? $input_data['Longitude'] : ''}}">
    <input type="hidden" name="Radius" id="Radius" value="80">
    <input type="hidden" name="city_id" id="city_id" value="{{  $input_data['city_id']}}">
    <input type="hidden" name="city_name" id="city_name" value="{{  $input_data['city_name']}}">
    <input type="hidden" name="countryCode" id="country_code" value="{{ $input_data['countryCode']}}">
    <input type="hidden" name="countryName" id="country_name" value="{{ $input_data['countryName']}}">
    <input type="hidden" name="country" id="country" value="{{ $input_data['user_country']}}">
    <input type="hidden" name="currency" id="currency" value="{{ $input_data['currency']}}">
    <input type="hidden" name="referral" id="referral" value="{{ $referral}}">
    <input type="hidden" name="preffered_hotel" id="preffered_hotel" value="{{ $hotel_code}}">
    <input type="hidden" name="ishalal" id="ishalal" value="{{ (Session::get('active_tab')=='halal')?1:0 }}">
    <input class="form-control departdate" type="hidden" name="departdate" required readonly value="{{ $input_data['departdate']}}"/>
    <input class="form-control returndate" type="hidden" name="returndate" readonly required value="{{ $input_data['returndate']}}"/>
    <input type="hidden" name="roomsGuests" id="roomsGuests" class="form-control" required data-parsley-required-message="Please select the rooms & guests." placeholder="1 Room" value="{{ $input_data['roomsGuests']}}">
    <input type="hidden" name="to" id="to-city-fh" value="{{ $input_data['to'] }}">
    <input type="hidden" name="from" id="from-city-fh" value="{{ $input_data['from'] }}">
    <input type="hidden" name="countryCode" id="country_codefh" value="{{ $input_data['countryCode']}}">
    <input type="hidden" name="origin"  value="{{ $input_data['origin'] }}">
    <input type="hidden" name="destination" value="{{ $input_data['destination']}}">
    @include('_partials.flight-hotel-guests-edit')
</form>
<section class="prdt_detail_sec" id="hotelViewPage">
    <div class="container">
        <div class="row">
            <p id="timerBook"></p>
            <?php
            $priceBase = 0;
            $final_price = 0;
            $final_price_install = 0;
            $currency = $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode'];
            ?>
            
            @foreach($roomDetails['BlockRoomResult']['HotelRoomsDetails'] as $roomD)           
            <?php
            //echo "<pre>"; print_r($roomD['Price']);  echo "</pre>";
            $tdsVal = ((env('INIS_TDS') / 100) * ( $roomD['Price']['OfferedPriceRoundedOff'] ));

            $inis_markup = (($commission / 100) * $roomD['Price']['OfferedPriceRoundedOff'] + $tdsVal);
            $price_with_markup = $inis_markup + $roomD['Price']['OfferedPriceRoundedOff'] + $tdsVal;
            //$taxes = (env('INR_FEES') / 100) * $price_with_markup;

            //$final_price = $final_price + $price_with_markup + $taxes;

            if($currency == 'ILS'){

                $inis_markup = ((env('INIS_VAL_PAYME') / 100) * ( $roomD['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                $price_with_markup = $inis_markup + $roomD['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;
                //$taxes = $taxes + env('PAYME_FIX_FEES');

            }else{

                $taxes = (env('INR_FEES') / 100) * $price_with_markup;
            }

             $final_price = $final_price + $price_with_markup + $taxes;

            if($currency == 'ILS'){

              $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

              $final_price = $final_price + env('PAYME_FIX_FEES') + $vat;

              $final_price_install = $final_price;

            }

            //echo "Hotel ".$final_price;
            
            ?>

            @endforeach
            
            <?php 

            if($currency == 'ILS'){

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

                if($currency == 'ILS'){

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

                $totalconversionob = $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( $commissionFlight / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] );


                if (!empty($fareQuoteIB['Results'])) {
                    $totalconversionib = $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( $commissionFlight / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] );
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

            $final_price = $final_price + $flightprice;
            $final_price_install = $final_price;
            //echo  $fareQuoteOB['Results']['Fare']['OfferedFare'] . '  '.( $commissionFlight / 100  $fareQuoteOB['Results']['Fare']['OfferedFare'] ).'  '. $conversion / 100  $totalconversionob. "<br />";

            //echo  $fareQuoteIB['Results']['Fare']['OfferedFare'] . '  '.( $commissionFlight / 100  $fareQuoteIB['Results']['Fare']['OfferedFare'] ).'  '. $conversion / 100  $totalconversionib. "<br />";

            //$priceBase = $priceBase + round($roomD['Price']['OfferedPriceRoundedOff'],2); 
            //$priceBase = (($priceBase + $roomD['Price']['OfferedPriceRoundedOff']) + ( $commission / 100 * $roomD['Price']['OfferedPriceRoundedOff'] ));

            ?>
            <div class="col-lg-4 col-md-5">

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
                                    <td>{{ __('labels.bag_type')}}</td>
                                    <td>{{ __('labels.checkin')}}</td>
                                    <td>{{ __('labels.cabin')}}</td>
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
                                    <td>{{ __('labels.bag_type')}}</td>
                                    <td>{{ __('labels.checkin')}}</td>
                                    <td>{{ __('labels.cabin')}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('labels.adult')}}</td>
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
                                    <td>{{ __('labels.bag_type')}}</td>
                                    <td>{{ __('labels.checkin')}}</td>
                                    <td>{{ __('labels.cabin')}}</td>
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
                                    <td>{{ __('labels.bag_type')}}</td>
                                    <td>{{ __('labels.checkin')}}</td>
                                    <td>{{ __('labels.cabin')}}</td>
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
                                    <td>{{ __('labels.bag_type')}}</td>
                                    <td>{{ __('labels.checkin')}}</td>
                                    <td>{{ __('labels.cabin')}}</td>
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
                                    <td>{{ __('labels.bag_type')}}</td>
                                    <td>{{ __('labels.checkin')}}</td>
                                    <td>{{ __('labels.cabin')}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('labels.adult')}}</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][1][2]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteOB['Results']['Segments'][1][2]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @endif

                    <!-- Domestic return Div  -->
                    @if(!empty($fareQuoteIB['Results']['Segments'][0][0]))
                    <hr />
                    <div class="flex_width_air col-md-12">
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
                                    <td>{{ __('labels.bag_type')}}</td>
                                    <td>{{ __('labels.checkin')}}</td>
                                    <td>{{ __('labels.cabin')}}</td>
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
                                    <td>{{ __('labels.bag_type')}}</td>
                                    <td>{{ __('labels.checkin')}}</td>
                                    <td>{{ __('labels.cabin')}}</td>
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
                                    <td>{{ __('labels.bag_type')}}</td>
                                    <td>{{ __('labels.checkin')}}</td>
                                    <td>{{ __('labels.cabin')}}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('labels.adult')}}</td>
                                    <td>{{ $fareQuoteIB['Results']['Segments'][0][2]['Baggage'] }}</td>
                                    <td>{{ $fareQuoteIB['Results']['Segments'][0][2]['CabinBaggage'] }}</td>
                                </tr>
                            </table>
                        </div>    
                    </div>
                    @endif
                    <!-- Div Ends Here  -->
                    
                </div>
                
                <div class="right_detail_data">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="padding:20px;">
                                <h3>{{$roomDetails['BlockRoomResult']['HotelName']}}</h3>
                                <p>{{$roomDetails['BlockRoomResult']['AddressLine1']}}</p>
                                <div class="test_stars">
                                    @for($i=0; $i < $roomDetails['BlockRoomResult']['StarRating']; $i++ )
                                    <a href="#"><i class="fa fa-star" aria-hidden="true"></i></a>
                                    @endfor
                                </div>
                            </div>
                            <div class="prdct_full_img">
                                <div class="room_data_image">
                                    @if(isset($hotel_img) && count($hotel_img)>0) 
                                    <div id="hotelCarouse_0" class="carousel slide" data-ride="carousel" align="center">
                                        <ol class="carousel-indicators" >
                                            @foreach ($hotel_img as $keyli => $li)
                                            <li  data-target="#hotelCarouse_0"  data-slide-to="{{ $keyli }}" class="@if($keyli == 0) active @endif"></li>
                                            @endforeach
                                        </ol>
                                        <div class="carousel-inner" >
                                            @foreach ($hotel_img as $keysi => $si)
                                            <div class="carousel-item @if($keysi == 0) active @endif"> 

                                                @if(strpos($si, 'http') !== false || strpos($si, 'www') !== false)
                                                    <img class="room-image-slider" src="{{ $si }}"  alt="hotel" >
                                                @else
                                                    <img class="room-image-slider" src="{{env('AWS_BUCKET_URL')}}/{{ $si }}"  alt="hotel" >
                                                @endif

                                            </div>
                                            @endforeach
                                        </div>
                                        @if(sizeof($hotel_img) == 0)
                                        <div  class="carousel-item active"> 
                                            <img class="room-image-slider" src="https://b2b.tektravels.com/Images/HotelNA.jpg"  alt="hotel">
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" >
                            <div class="prdct_detail_data" style="padding:15px;">

                                <div class="check_out">
                                    <ul class="list-inline">
                                        <li>
                                            <h5>{{ __('labels.checkin')}}:</h5>
                                            @if($checkInDate)
                                            <p>{{$checkInDate}}</p>
                                            @else
                                            <p>23 Aug 2020</p>
                                            @endif
                                        </li>
                                        <li>
                                            <h5>{{ __('labels.checkout')}}:</h5>
                                            @if($checkOutDate)
                                            <p>{{$checkOutDate}}</p>
                                            @else
                                            <p>25 Aug 2020</p>
                                            @endif
                                        </li>
                                        <li>
                                            <h5>{{ __('labels.no_of_rooms')}}:</h5>
                                            @if($noOfRooms)
                                            <p>{{$noOfRooms}}</p>
                                            @else
                                            <p>1</p>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="policy_guranteed">
                                {!!html_entity_decode(str_replace(array('#^#', '#', '|', '#!#', '!'), array(' ', ' ', ' ', ' ', ' '), $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['CancellationPolicy']))!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="room_data_image">

                                @if(isset($room_images) && $room_images['images'] != null) 
                                <?php $slider_images = unserialize($room_images['images']); ?>

                                    @if(strpos($rphoto, 'http') !== false || strpos($rphoto, 'www') !== false)
                                        <img style="width:100%;" src="{{ $rphoto }}"  alt="hotel">
                                    @else
                                        <img style="width:100%;" src="{{env('AWS_BUCKET_URL')}}/{{ $rphoto }}"  alt="hotel">
                                    @endif

                                @endif
                            </div>
                        </div>
                    </div>
                    <?php $priceBaseRoom = 0; ?>
                    @foreach($roomDetails['BlockRoomResult']['HotelRoomsDetails'] as $key => $roomD)
                    @if($key == 0)
                    <div class="room_information mrgintop_20" style="border:none;">
                        <div class="inner_room_detail">
                            <h3>
                                <a href="#">{{$roomD['RoomTypeName']}}  - {{$noOfRooms}} {{( $noOfRooms > 1) ? " Rooms" : " Room"}}</a> <span class="room-count"></span>
                                <h5>{{ __('labels.guests')}}</h5>
                                <ul class="list-inline">
                                    <?php
                                    $adults = 0;
                                    $childs = 0;
                                    for ($r = 1; $r <= $noOfRooms; $r++) {
                                        //foreach($roomGuests as $guest) {
                                        $adults = $adults + $input_data['adultCountRoom' . $r];
                                        $childs = $childs + $input_data['childCountRoom' . $r];
                                        //}
                                    }
                                    ?>
                                    <li>{{ $adults }} {{ __('labels.adults') }}</li>

                                    @if ($childs > 0) 
                                        <li>{{ $childs }} {{ __('labels.children') }}</li>
                                    @endif
                                </ul>
                            </h3>
                            <div class="teriff_type">
                                <h5>{{ __('labels.availability')}}</h5>
                                <ul class="list-inline">
                                    <li>{{$roomD['AvailabilityType']}}</li>
                                </ul>
                            </div>
                            <div class="tariff_inclusion">
                                <h5>{{ __('labels.amenities')}}</h5>
                                <ul class="list-inline">
                                    @for($i=0; $i < sizeof($roomD['Amenities']); $i++ )
                                    <li>{{$roomD['Amenities'][$i]}}</li>
                                    @endfor
                                </ul>
                            </div><br />

                        </div>
                    </div>
                    @endif
                    @endforeach
                    <div class="basic_price_data">
                        <ul class="list-inline">
                        </ul>
                    </div>



                    <input type="hidden" name="RAZOR_KEY_ID" id="RAZOR_KEY_ID" value="{{ env('RAZOR_KEY_ID') }}">
                    <input type="hidden" name="userName" id="userName" value="">
                    <input type="hidden" name="userEmail" id="userEmail" value="">
                    <input type="hidden" name="useraddress" id="useraddress" value="">
                    <input type="hidden" name="CURRENCY_VAL" id="CURRENCY_VAL" value="{{$roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode']}}">
                    <input type="hidden" name="BOOKING_NAME" id="BOOKING_NAME" value="{{$roomDetails['BlockRoomResult']['HotelName']}}">
                    <input type="hidden" name="BOOKING_DESC" id="BOOKING_DESC" value="{{$checkInDate}} - {{$checkOutDate}}">
                    <input type="hidden" name="BOOKING_PRICE" id="BOOKING_PRICE" value="{{ round($final_price,2)  }}">
                    
                    <input type="hidden" name="BOOKING_PRICE_EXTRA" id="BOOKING_PRICE_EXTRA" value="{{ round($final_price,2)  }}">

                    <input type="hidden" name="ORIGINAL_BOOKING_PRICE" id="ORIGINAL_BOOKING_PRICE" value="{{ round($final_price,2)  }}">
                
                    <!-- <div class="pay_btns" ><a href="javascript:void(0);" id="pay_btns_razor" class="btn btn_pay_now" >Razor Pay</a></div> -->
                </div>
                
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="inner_left_prdct_data" style="background: #fff;border-radius: 5px;box-shadow: 3px 6px 20px #ccc;"> 

                    <div class="row">
                        <?php
                        $check = false;
                        $lottery = Lottery::where(['lotteryStatus' => 'active'])->first();
                        if (!Auth::guest() && isset($lottery)) {
                            $check = LotteryUsers::where(['lotteryID' => $lottery->id, 'userID' => Auth::user()->id])->first();
                        }

                        if (!$check) {

                            if(isset($myCurrency)) {

                            } else {

                                $myCurrency = (Session::get('CurrencyCode') && Session::get('CurrencyCode') != '') ? Session::get('CurrencyCode') : 'INR';
                            }
                            $usercurrency = Currency::convert('USD', $myCurrency, env('LOTTERY_FEE'));
                            
                            $lotteryFees = round($usercurrency['convertedAmount']);


                            if (($final_price) > Session::get('lotteryLimit')) {
                                ?>
                                <div class="col-12"  id="lotterybox">
                                    <div class="form-check" style="padding:20px;background:#6DB5CA !important;">
                                        <img  src="/images/lottery-icon.gif" style="width:60px;" /><div style="padding-left:10px;display:inline;font-size:25px;font-weight: 700;color:#fff;"><?php echo $lotteryFees . " " . $myCurrency; ?></div>
                                        <p style="color:#fff;">
                                            {{ __('labels.cashback_message_1st')}}  <?php echo $lotteryFees . " " . $myCurrency; ?></p>
                                        <p style="color:#fff;">
                                            <label>
                                                <input  name="regForLottery" value="<?php echo $lotteryFees; ?>" type="checkbox"  id="regForLottery">
                                                {{ __('labels.cashback_message_2nd')}}
                                            </label>
                                        </p>
                                    </div>
                                </div>
                                <?php
                            }
                        }

                        if (!Auth::guest()) {

                            $walletBal = wallet_blance()['amount'];//\Auth::user()->balance;
                            if ($walletBal > 0) { ?>
                                <div class="col-lg-12" id="walletPaySwitch">
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
                        <div class="col-lg-12">
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
                            <div class="total_payouts" style="padding:10px 25px;background:#ff8700;color:#fff;font-weight: bold;">
                                <div class="text_payable" style="font-size:25px;">{{ __('labels.total_payable')}} <span class="flight_hotel_price_text">({{ __('labels.bundle_price')}})</span></div>
                                <div class="final_price" style="font-size:25px;">{{$roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode']}} 
                                    {{ number_format ( $final_price, 2)  }}
                                <br>
                                <span class="tax-included">{{ __('labels.include_tax')}}</span>
                                </div> 
                            </div>
                        </div>
                    </div> 


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="raveller_informaion mrgintop_20" style="padding:5px;border:none;border-radius: 0px;">
                                @if(Session::get('locale') == 'heb')
                                <p style="color: #000;margin-left: 20px;font-weight: 600;">.לתושבי ישראל יתווסף למחיר 17% מע"מ שישולם ישירות למלון, למעט באזור אילת *</p> 
                                @endif
                                <form method="POST" class="traveller_form" id="BookRoomForm" name="BookingForm" action="{{ route('bookFlightRoom') }}">

                                    <!--                            <ul id="progressbar">
                                                                    <li class="active" id="traveller"><strong>Traveller Infomation</strong></li>
                                                                    <li id="payment"><strong>Payment Information</strong></li>
                                                                </ul>-->
                                    @if($paidAmtILS > 0)
                                        @csrf
                                        <input type="hidden" name="file_name" id="file_name" value="f_h">
                                        <input type="hidden" name="CategoryId" class="form-control" value="{{$CategoryId}}" >
                                        <input type="hidden" name="CancellationPolicy" class="form-control" value="{{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['CancellationPolicy'] }}" >
                                        <input type="hidden" name="LastCancellationDate" class="form-control" value="{{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['LastCancellationDate'] }}" >
                                    @else
                                    <fieldset>
                                        <div class="form-card">
                                            <div id="error-message" class="error-message-name"></div>
                                            @csrf
                                            <input type="hidden" name="file_name" id="file_name" value="f_h">
                                            <input type="hidden" name="CategoryId" class="form-control" value="{{$CategoryId}}" >
                                            <input type="hidden" name="CancellationPolicy" class="form-control" value="{{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['CancellationPolicy'] }}" >
                                            <input type="hidden" name="LastCancellationDate" class="form-control" value="{{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['LastCancellationDate'] }}" >

                                            <h4 style="margin:0px auto 25px auto;padding-bottom:15px;border-bottom: 1px solid #ccc;">{{ __('labels.traveller_info')}}</h4>

                                            @for($r = 1; $r <= $noOfRooms; $r++)
                                            <div class="passengerInfo">
                                                @for($i=1; $i <= $input_data['adultCountRoom' . $r]; $i++)
                                                <span><b> {{ __('labels.room')}} {{$r}} {{ __('labels.adult')}} {{$i}}</b></span>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.title')}}</label>
                                                            <select  name="roomPassengers[{{$r}}][adult][{{$i}}][title]"  id="roomPassengers_{{$r}}_adult_{{$i}}_title" class="form-control" required data-parsley-pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$" data-parsley-trigger="keyup" data-parsley-required-message="Title is required." autocomplete="off">
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
                                                            <input type="text" name="roomPassengers[{{$r}}][adult][{{$i}}][first_name]" id="roomPassengers_{{$r}}_adult_{{$i}}_first_name"  class="form-control fn_{{$r}}_{{$i}}" id="fn_{{$r}}_{{$i}}" placeholder="{{ __('labels.first_name')}}" required data-parsley-pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$" data-parsley-trigger="keyup" data-parsley-required-message="First name is required." autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.last_name')}}</label>
                                                            <input type="text" name="roomPassengers[{{$r}}][adult][{{$i}}][last_name]" class="form-control  ln_{{$r}}_{{$i}}" id="ln_{{$r}}_{{$i}}" placeholder="{{ __('labels.last_name')}}" required data-parsley-pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$" data-parsley-trigger="keyup" data-parsley-required-message="Last name is required." autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.email_id')}}</label>
                                                            @if(!Auth::guest())
                                                            <input type="email" value="{{ Auth::user()->email}}" name="roomPassengers[{{$r}}][adult][{{$i}}][email]" id="email_{{$r}}_{{$i}}" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control email_{{$r}}_{{$i}}" placeholder="{{ __('labels.email_id')}}" required>
                                                            @else
                                                            <input type="email" value="" name="roomPassengers[{{$r}}][adult][{{$i}}][email]" id="email_{{$r}}_{{$i}}" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control useremail email_{{$r}}_{{$i}}" placeholder="{{ __('labels.email_id')}}" required>                                             
                                                            @endif
                                                            <div class="small_text">({{ __('labels.booking_email_confirm')}})</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.phone_number')}}</label>
                                                            <input type="number" name="roomPassengers[{{$r}}][adult][{{$i}}][phone]" class="form-control" placeholder="{{ __('labels.phone_number')}}" required id="ph_{{$r}}_{{$i}}">
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="row">
                                                    @if($roomDetails['BlockRoomResult']['HotelRoomsDetails'][$r - 1]['IsPassportMandatory'] == 1 || $fareQuoteOB['Results']['IsPassportRequiredAtBook'] || $fareQuoteOB['Results']['IsPassportRequiredAtTicket'] )
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.passport_no')}}</label>
                                                            <input type="text" name="roomPassengers[{{$r}}][adult][{{$i}}][passportNo]" id="roomPassengers_{{$r}}_adult_{{$i}}_passportNo" class="form-control" placeholder="{{ __('labels.passport_no')}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.passport_expire')}}</label>
                                                            <input type="text" name="roomPassengers[{{$r}}][adult][{{$i}}][passportExpire]" id="roomPassengers_{{$r}}_adult_{{$i}}_passportExpire" class="form-control pass_expiry_date" placeholder="{{ __('labels.passport_expiry_date')}}" required>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if($roomDetails['BlockRoomResult']['HotelRoomsDetails'][$r - 1]['IsPANMandatory'] == 1)                          
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.pan_no')}}</label>
                                                            <input type="text" name="roomPassengers[{{$r}}][adult][{{$i}}][panNo]" id="roomPassengers_{{$r}}_adult_{{$i}}_panNo" class="form-control" placeholder="{{ __('labels.pan_no')}}" required>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    @if($fareQuoteOB['Results']['Segments'][0][0]['Airline']['AirlineName'] == 'AirAsia')
                                                    <div class="col-md-6">
                                                        <label>{{ __('labels.pan_no')}}</label>
                                                        <input type="text" name="roomPassengers[{{$r}}][adult][{{$i}}][adult_dob]" id="roomPassengers_{{$r}}_adult_{{$i}}_adult_dob" class="form-control dob" required placeholder="{{ __('labels.pan_no')}}" data-parsley-trigger="keyup" data-parsley-required-message="DOB is required." autocomplete="off">
                                                    </div>
                                                    @endif
                                                    <div class="col-md-6">
                                                      <div class="form-group">
                                                        <label>{{ __('labels.address') }}</label>
                                                        <input type="text" name="roomPassengers[{{$r}}][adult][{{$i}}][address]" id="roomPassengers_{{$r}}_adult_{{$i}}_address" class="form-control" placeholder="{{ __('labels.address') }}" required value="">
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
                                                                    <select name="meal_{{$i}}" id="meal_{{$i}}" class="form-control">
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
                                                                    <select name="seat_{{$i}}" id="seat_{{$i}}" class="form-control">
                                                                        <option value="">No Preference</option>
                                                                        @foreach($seat as $key => $value)
                                                                            <option value=" {{ json_encode($value) }}  ">{{ $value['Description'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>        
                                                     <span class="small_text">{{ __('labels.meal_note')}}</span>
                                                    </div><br >
                                                    @endif

                                                    @if(!empty($mealLCC) && !empty($baggage))
                                                    <div class="ssrResult">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>{{ __('labels.baggage') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} )</label>
                                                                    <select name="baggage_{{$i}}" id="baggage_{{$i}}" class="form-control baggageDropDown">
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
                                                                    <select name="meallcc_{{$i}}" id="meallcc_{{$i}}" class="form-control baggageDropDown">
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
                                                                    <select name="baggage_return{{$i}}" id="baggage_return{{$i}}" class="form-control baggageDropDown">
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
                                                                    <select name="meallcc_return{{$i}}" id="meallcc_return{{$i}}" class="form-control baggageDropDown">
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
                                                                    <select name="baggage_return_ib{{$i}}" id="baggage_return_ib{{$i}}" class="form-control baggageDropDown">
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
                                                                    <select name="meallcc_return_ib{{$i}}" id="meallcc_return_ib{{$i}}" class="form-control baggageDropDown">
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
                                                @if(isset($input_data['childCountRoom' . $r]) && $input_data['childCountRoom' . $r] > 0)
                                                @for($c=0; $c < $input_data['childCountRoom' . $r]; $c++)
                                                <span><b> Room {{$r}} Child {{$c + 1}}</b></span>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.title')}}</label>
                                                            <select  name="roomPassengers[{{$r}}][child][{{$c+1}}][title]" id="roomPassengers_{{$r}}_child_{{$c+1}}_title" class="form-control" required data-parsley-pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$" data-parsley-trigger="keyup" data-parsley-required-message="Title is required." autocomplete="off">
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
                                                            <input type="text" name="roomPassengers[{{$r}}][child][{{$c+1}}][first_name]" id="roomPassengers_{{$r}}_child_{{$c+1}}_first_name" class="form-control" placeholder="{{ __('labels.first_name')}}" required pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.last_name')}}</label>
                                                            <input type="text" name="roomPassengers[{{$r}}][child][{{$c+1}}][last_name]" id="roomPassengers_{{$r}}_child_{{$c+1}}_last_name" class="form-control" placeholder="{{ __('labels.last_name')}}" required pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$">
                                                            <input type="hidden" name="roomPassengers[{{$r}}][child][{{$c+1}}][age]" id="roomPassengers_{{$r}}_child_{{$c+1}}_age" class="form-control" value="" >


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ __('labels.email_id') }}</label>
                                                                <input type="text" name="roomPassengers[{{$r}}][child][{{$c+1}}][child_email]" id="roomPassengers_{{$r}}_child_{{$c+1}}_child_email" class="form-control" placeholder="{{ __('labels.email_id') }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ __('labels.phone_number') }}</label>
                                                                <input type="number" name="roomPassengers[{{$r}}][child][{{$c+1}}][child_phone]" id="roomPassengers_{{$r}}_child_{{$c+1}}_child_phone" class="form-control" placeholder="{{ __('labels.phone_number') }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($roomDetails['BlockRoomResult']['HotelRoomsDetails'][$r - 1]['IsPANMandatory'] == 1)                          
                                                 <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.pan_no') }}</label>
                                                            <input type="text" name="roomPassengers[{{$r}}][child][{{$c+1}}][panNo]" id="roomPassengers_{{$r}}_child_{{$c+1}}_panNo" class="form-control" placeholder="{{ __('labels.pan_no') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                    @endif
                                                 @if($fareQuoteOB['Results']['IsPassportRequiredAtBook'] || $fareQuoteOB['Results']['IsPassportRequiredAtTicket'] )
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.passport_no') }}</label>
                                                            <input type="text" name="roomPassengers[{{$r}}][child][{{$c+1}}][child_passport_no]" id="roomPassengers_{{$r}}_child_{{$c+1}}_child_passport_no" class="form-control" placeholder="{{ __('labels.passport_no') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.passport_expire') }}</label>
                                                            <input type="text" name="roomPassengers[{{$r}}][child][{{$c+1}}][child_pass_expiry_date]" id="roomPassengers_{{$r}}_child_{{$c+1}}_child_pass_expiry_date" class="form-control pass_expiry_date" placeholder="{{ __('labels.passport_expire') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-6">
                                                      <div class="form-group">
                                                        <label>{{ __('labels.address') }}</label>
                                                        <input type="text" name="roomPassengers[{{$r}}][child][{{$c+1}}][address]" id="roomPassengers_{{$r}}_child_{{$c+1}}_address" class="form-control" placeholder="{{ __('labels.address') }}" required value="">
                                                      </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.dob') }}</label>
                                                            <input type="text" name="roomPassengers[{{$r}}][child][{{$c+1}}][child_dob]" id="roomPassengers_{{$r}}_child_{{$c+1}}_child_dob" class="form-control dob" required placeholder="{{ __('labels.dob') }}" data-parsley-trigger="keyup" data-parsley-required-message="DOB is required." autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                 @if(!empty($meal) || !empty($seat))
                        
                                                <div class="ssrResult">
                                                    <div class="row">
                                                        @if(!empty($meal))
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ __('labels.meal') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} )</label>
                                                                <select name="child_meal_{{$c+1}}" id="child_meal_{{$c+1}}" class="form-control">
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
                                                                <select name="child_seat_{{$c+1}}" id="child_seat_{{$c+1}}" class="form-control">
                                                                    <option value="">No Preference</option>
                                                                    @foreach($seat as $key => $value)
                                                                        <option value=" {{ json_encode($value) }}  ">{{ $value['Description'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <span class="small_text">{{ __('labels.meal_note') }}</span>        
                                                </div><br >

                                                @endif

                                                 @if(!empty($mealLCC) && !empty($baggage))
                                                <div class="ssrResult">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ __('labels.baggage') }} - ( {{ $input['origin'] }} - {{ $input['destination'] }} )</label>
                                                                <select name="child_baggage_{{$c+1}}" id="child_baggage_{{$c+1}}" class="form-control baggageDropDown">
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
                                                                <select name="child_meallcc_{{$c+1}}" id="child_meallcc_{{$c+1}}" class="form-control baggageDropDown">
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
                                                                <select name="child_baggage_return{{$c+1}}" id="child_baggage_return{{$c+1}}" class="form-control baggageDropDown">
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
                                                                <select name="child_meallcc_return{{$c+1}}" id="child_meallcc_return{{$c+1}}" class="form-control baggageDropDown">
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
                                                                <select name="child_baggage_return_ib{{$c+1}}" id="child_baggage_return_ib{{$c+1}}" class="form-control baggageDropDown">
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
                                                                <select name="child_meallcc_return_ib{{$c+1}}" id="child_meallcc_return_ib{{$c+1}}" class="form-control baggageDropDown">
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
                                                @endif
                                            </div>
                                            @endfor
                                            @if($show_markup)
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>{{ __('labels.add_markup') }}</label>
                                                            <input type="number" name="agent_makrup" id="agent_makrup" value="0" class="form-control" placeholder="Add Markup Amount" min="1" >
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group term_conditions">
                                                <label class="check_container">
                                                    {{ __('labels.booking_terms') }}
                                                    <input type="checkbox" checked="checked" required disabled>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="form-group term_conditions">
                                                <label class="check_container">{{ __('labels.i_agree')}} <a href="#" data-toggle="modal" data-target="#policyModel" class="agreement-link">{{ __('labels.hotel_book_policy_lbl')}}</a>, <a href="/refund-policy" target="_blank" class="agreement-link">{{ __('labels.hotel_cancel_policy_lbl')}}</a>,<a href="/privacy-policy" target="_blank" class="agreement-link">{{ __('labels.privacy_policy_lbl')}}</a>,{{ __('labels.user_agrement_lbl')}} <a href="/terms-conditions" target="_blank" class="agreement-link">{{ __('labels.terms_lbl')}}</a>
                                                    <input type="checkbox" checked="checked" required disabled>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            <?php
                                                $priceBase = 0;
                                                $final_price = 0;
                                                $final_price_install = 0;
                                                $currency = $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode'];
                                                ?>
                                                
                                                @foreach($roomDetails['BlockRoomResult']['HotelRoomsDetails'] as $roomD)           
                                                <?php
                                                //echo "<pre>"; print_r($roomD['Price']);  echo "</pre>";

                                                $tdsVal = ((env('INIS_TDS') / 100) * ( $roomD['Price']['OfferedPriceRoundedOff'] ));
                                                $inis_markup = (($commission / 100) * $roomD['Price']['OfferedPriceRoundedOff'] + $tdsVal);
                                                $price_with_markup = $inis_markup + $roomD['Price']['OfferedPriceRoundedOff'] + $tdsVal;
                                                //$taxes = (env('INR_FEES') / 100) * $price_with_markup;

                                                //$final_price = $final_price + $price_with_markup + $taxes;

                                                if($currency == 'ILS'){

                                                    $inis_markup = ((env('INIS_VAL_PAYME') / 100) * ( $roomD['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                                                    $price_with_markup = $inis_markup + $roomD['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                                                    $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;
                                                    //$taxes = $taxes + env('PAYME_FIX_FEES');

                                                }else{

                                                    $taxes = (env('INR_FEES') / 100) * $price_with_markup;
                                                }

                                                 $final_price = $final_price + $price_with_markup + $taxes;

                                                if($currency == 'ILS'){

                                                  $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                                                  $final_price = $final_price + env('PAYME_FIX_FEES') + $vat;

                                                  $final_price_install = $final_price;

                                                }

                                                
                                                ?>

                                                @endforeach
                                                
                                                <?php 

                                                if($currency == 'ILS'){

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


                                                    if($currency == 'ILS'){

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

                                                    $totalconversionob = $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( $commissionFlight / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] );


                                                        if (!empty($fareQuoteIB['Results'])) {
                                                            $totalconversionib = $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( $commissionFlight / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] );
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

                                                                                
                                                
                                                $final_price = $final_price + $flightprice;
                                                $final_price_install = $final_price;
                                                //echo  $fareQuoteOB['Results']['Fare']['OfferedFare'] . '  '.( $commissionFlight / 100  $fareQuoteOB['Results']['Fare']['OfferedFare'] ).'  '. $conversion / 100  $totalconversionob. "<br />";

                                                //echo  $fareQuoteIB['Results']['Fare']['OfferedFare'] . '  '.( $commissionFlight / 100  $fareQuoteIB['Results']['Fare']['OfferedFare'] ).'  '. $conversion / 100  $totalconversionib. "<br />";

                                                //$priceBase = $priceBase + round($roomD['Price']['OfferedPriceRoundedOff'],2); 
                                                //$priceBase = (($priceBase + $roomD['Price']['OfferedPriceRoundedOff']) + ( $commission / 100 * $roomD['Price']['OfferedPriceRoundedOff'] ));

                                                ?>
                                        </div>
                                        <input type="button" name="next" class="next btn btn-primary" value="{{ __('labels.continue_payment') }}" />
                                    </fieldset>
                                    @endif
                                    <fieldset>
                                        <div class="form-card">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <h4 style="margin:25px auto;padding-bottom:15px;border-bottom: 1px solid #ccc;">{{ __('labels.pay_mode')}}</h4>
                                                </div>
                                                @if($roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode'] == 'ILS')
                                                <div class="col-lg-12">
                                                    <nav class="nav nav-pills flex-column flex-sm-row" role="tablist">
                                                        @if($paidAmtILS > 0)
                                                        <a class="flex-sm-fill text-sm-center nav-link active" id="multiCardTab" data-toggle="tab" href="#multiple-payment" onclick="$('#paymentMode').val('multiple');">{{ __('labels.multi_card')}}</a>
                                                        @else
                                                        <a class="flex-sm-fill text-sm-center nav-link active" id="singleCardTab" data-toggle="tab" href="#single-payment" onclick="$('#paymentMode').val('single');">{{ __('labels.single_card')}}</a>
                                                        <a class="flex-sm-fill text-sm-center nav-link" id="multiCardTab" data-toggle="tab" href="#multiple-payment" onclick="$('#paymentMode').val('multiple');$('#installments_val').val('1');$('#installments_val').val('1');$('#installments_val').trigger('change');">{{ __('labels.multi_card')}}</a>
                                                        @endif
                                                    </nav>
                                                    <div class="tab-content" id="pills-tabContent">
                                                        <div style="padding:20px;" class="tab-pane fade show active" id="single-payment" role="tabpanel" aria-labelledby="pills-home-tab">
                                                            <input type="hidden" id="fullAmount"  value="{{ round($final_price,2)}}" >

                                                            <input type="hidden" id="fullAmount_Install"  value="{{ round($final_price_install,2)}}" >
                                                            <input type="hidden" name="ORIGINAL_BOOKING_PRICE_PME" id="ORIGINAL_BOOKING_PRICE_PME" value="{{ round($final_price,2)  }}">

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
                                                                    <span class="period_payment" style="font-size: 16px;color: #000;">{{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode'] }} {{ round($final_price,2)}}</span>
                                                                </div>
                                                              </div>
                                                             <div class="col-xs-4 col-md-6">
                                                                <div class="form-group" id="installments-group">
                                                                  <label for="installments-container" class="control-label">{{ __('labels.interest_amount')}}</label>
                                                                    <span class="amount_interest" style="font-size: 16px;color: #000;">{{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode'] }} 0</span>
                                                                </div>
                                                              </div>
                                                            
                                                            </div>
                                                            <div class="row">
                                                              <div class="col-xs-4 col-md-6">
                                                                <div class="form-group" id="installments-group">
                                                                  <label for="installments-container" class="control-label">{{ __('labels.total_payment')}}</label>
                                                                    <span class="total_payment" style="font-size: 16px;color: #000;">{{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode'] }} {{ round($final_price,2)}}</span>
                                                                </div>
                                                              </div>
                                                             <div class="col-xs-4 col-md-6">
                                                                
                                                              </div>
                                                            
                                                            </div>

                                                            <button type="button" id="submit-payme-api" class="btn btn-primary btn-open-pay-form">
                                                                {{ __('labels.pay_lbl')}} {{ number_format ( $final_price_install, 2)  }} ILS
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
                                                                                    {{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode'] }} {{ round($final_price,2)}}
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >
                                                                                {{ __('labels.total_paid')}}:
                                                                            </td>
                                                                            <td align="right">
                                                                                <div class="total_paid" style="color:green;">
                                                                                    {{$roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode']}} 
                                                                                    <span class="paidAmountILS">{{ $paidAmtILS }}</span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >
                                                                                {{ __('labels.total_due')}}:
                                                                            </td>
                                                                            <td align="right">
                                                                                <div class="due_amount" style="color:red;">
                                                                                    {{$roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode']}} 
                                                                                    <span class="dueAmountILS">{{ number_format ( $final_price_install - $paidAmtILS, 2)  }}</span>
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
                                                                        <input type="hidden" id="fullAmount_Install"  value="{{ round($final_price_install,2)}}" >
                                                                        <input type="hidden" name="ORIGINAL_BOOKING_PRICE_PME" id="ORIGINAL_BOOKING_PRICE_PME" value="{{ round($final_price,2)  }}">
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
                                                                        <span class="period_payment_multiple" style="font-size: 16px;color: #000;">{{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode'] }} 0</span>
                                                                    </div>
                                                                  </div>
                                                                 <div class="col-xs-4 col-md-6">
                                                                    <div class="form-group" id="installments-group">
                                                                      <label for="installments-container" class="control-label">{{ __('labels.interest_amount')}}</label>
                                                                        <span class="amount_interest_multiple" style="font-size: 16px;color: #000;">{{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode'] }} 0</span>
                                                                    </div>
                                                                  </div>
                                                                
                                                                </div>
                                                                <div class="row">
                                                                  <div class="col-xs-4 col-md-6">
                                                                    <div class="form-group" id="installments-group">
                                                                      <label for="installments-container" class="control-label">{{ __('labels.total_payment')}}</label>
                                                                        <span class="total_payment_multiple" style="font-size: 16px;color: #000;">{{ $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode'] }} 0</span>
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
                                                        <input type="hidden" id="agentMarkup"  value="{{ $agentMarkup }}" >
                                                    </div>
                                                </div>
                                                @else
                                                <div class="col-lg-12">
                                                    <nav class="nav nav-pills flex-column flex-sm-row" role="tablist">
                                                        <a class="flex-sm-fill text-sm-center nav-link active" id="singleCardTab" data-toggle="tab" href="#single-payment" onclick="$('#paymentMode').val('single');">{{ __('labels.single_card')}}</a>
                                                        <a class="flex-sm-fill text-sm-center nav-link" id="multiCardTab" data-toggle="tab" href="#multiple-payment" onclick="$('#paymentMode').val('multiple');">{{ __('labels.multi_card')}}</a>
                                                    </nav>
                                                    <div class="tab-content" id="pills-tabContent">
                                                        <div style="padding:20px;" class="tab-pane fade show active" id="single-payment" role="tabpanel" aria-labelledby="pills-home-tab">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <table class="table">
                                                                        <tr>
                                                                            <td valign="middle">{{ __('labels.total_payble')}}:</td>
                                                                            <td valign="middle">
                                                                                <div class="final_price">
                                                                                    {{$roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode']}} 
                                                                                    {{ number_format ( $final_price, 2)  }}
                                                                                </div>
                                                                                <span class="flight_hotel_price_text">({{ __('labels.bundle_price')}})</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2" align="right">
                                                                                <div class="form-group">
                                                                                    <input type="hidden" id="fullAmount"  value="{{ round($final_price,2)}}" >
                                                                                    <button type="button" class="btn btn-primary more_details multiCardPay"  >{{ __('labels.pay_now')}}</button>
                                                                                    <!-- <input type="Submit" class="btn more_details" value="Pay Now" onClick="stripePayFlightRoom(event);"  id="submit-btn"> -->
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                    <div id="loader">
                                                                        <img alt="loader" src="https://phppot.com/demo/stripe-payment-gateway-integration-using-php/LoaderIcon.gif">
                                                                    </div>
                                                                </div>
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
                                                                                    {{$roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode']}} 
                                                                                    {{ number_format ( $final_price, 2)  }}
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td >
                                                                                {{ __('labels.total_paid')}}:
                                                                            </td>
                                                                            <td align="right">
                                                                                <div class="total_paid" style="color:green;">
                                                                                    {{$roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode']}} 
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
                                                                                    {{$roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['Price']['CurrencyCode']}} 
                                                                                    <span class="dueAmount">{{ number_format ( $final_price, 2)  }}</span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <div class=" offset-md-6 col-lg-6">
                                                                    <div class="form-group  text-right">
                                                                        <label for="partAmount" >{{ __('labels.pay_amount')}}</label>
                                                                        <input type="text"  id="partAmount" name="partAmount" style="font-weight: bold;text-align: right;font-size: 20px;" class="form-control" placeholder="0" >
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 text-right">
                                                                    <button  class="btn btn-primary multiCardPay" type="button" id="multiCardPay">{{ __('labels.pay_now')}}</button>                                              
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div id="error-message"></div>
                                            <input type="hidden" name="referral" class="referral" value="{{ $referral }}">
                                            <input type="hidden" name="IsPackageFare"  value="{{ $roomDetails['BlockRoomResult']['IsPackageFare'] }}">
                                            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                                            <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >

                                            <input type="hidden" name="paymentMode" id="paymentMode" value="single" >
                                            <input type="hidden" name="totalPaid" id="totalPaid" value="0" >
                                            <input type="hidden" name="traceId"  value="{{$traceId}}" >
                                            <input type="hidden" name="checkInDate"  value="{{$checkInDate}}" >
                                            <input type="hidden" name="checkOutDate"  value="{{$checkOutDate}}" >
                                            <input type="hidden" name="hotelIndex"  value="{{$ResultIndex}}" >
                                            <input type="hidden" name="hotelCode"  value="{{$sub_domain}}" >
                                            <input type="hidden" name="buyLottery" id="buyLottery" value="no" >
                                            <input type="hidden" name="walletPay" id="walletPay" value="no" >
                                            <input type="hidden" name="walletDebit" id="walletDebit" value="0" >
                                            <input type="hidden" name="hotelName"  value="{{$roomDetails['BlockRoomResult']['HotelName']}}" >

                                            <!-- Flight Hidden Fields  -->

                                            <input type="hidden" name="trace_id" id="trace_id" value="{{ $ftraceID }}" />
                                            <input type="hidden" name="search_id" id="search_id" value="{{ $search_id }}" />
                                            <input type="hidden" name="search_id_hotel" id="search_id_hotel" value="{{ $search_id_hotel }}" />
                                            <input type="hidden" id="extra_baggage_meal_price" name="extra_baggage_meal_price" value="0" />

                                             <?php if(isset($fareRuleOB['FareRules']['0']['DepartureTime'])) { ?> 
                                            <input type="hidden" name="departure_date_arr" value="{{ $fareRuleOB['FareRules']['0']['DepartureTime'] }}" />
                                            <?php } ?>
                                            @if(!empty($fareRuleIB['FareRules']))
                                            <input type="hidden" name="departure_date_dep" value="{{ $fareRuleIB['FareRules']['0']['DepartureTime'] }}" />
                                            @endif
                        

                                            <input type="hidden" name="obindex" value="{{ $resultOBIndex }}" />
                                            <input type="hidden" name="departure_date_arr" value="{{ $fareRuleOB['FareRules']['0']['DepartureTime'] }}" />

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

                                            <input type="hidden"  name="base_price" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['BaseFare'] + ( $commissionFlight / 100 * $fareQuoteOB['Results']['Fare']['BaseFare'] ) }}@else{{ $fareQuoteOB['Results']['Fare']['BaseFare'] + ( $commissionFlight / 100 * $fareQuoteOB['Results']['Fare']['BaseFare'] ) +  $fareQuoteIB['Results']['Fare']['BaseFare'] + ( $commissionFlight / 100 * $fareQuoteIB['Results']['Fare']['BaseFare'] )}}@endif" />

                                            <input type="hidden"  name="tax_price" value="@if(empty($fareQuoteIB['Results'])){{ $totalobFare + ( $commissionFlight / 100 * $totalobFare ) }}@else{{ $totalobFare + ( $commissionFlight / 100 * $totalobFare ) +  $totalibFare + ( $commissionFlight / 100 * $totalibFare ) }}@endif" />

                                            <input type="hidden"  name="amount_tbo" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['OfferedFare'] }}@else{{ $fareQuoteOB['Results']['Fare']['OfferedFare'] +  $fareQuoteIB['Results']['Fare']['OfferedFare'] }}@endif" />

                                            <input type="hidden" name="amount" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( $commissionFlight / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] ) + $ob_price_convert }}@else{{ $fareQuoteOB['Results']['Fare']['OfferedFare'] +  ( $commissionFlight / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] )  + $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( $commissionFlight / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] ) + $ib_price_convert }}@endif" />

                                            <input type="hidden" name="amount_without_conversion" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( $commissionFlight / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] )  }}@else{{ $fareQuoteOB['Results']['Fare']['OfferedFare'] +  ( $commissionFlight / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] ) +  $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( $commissionFlight / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] ) }}@endif" />

                                            <input type="hidden" name="amount_without_conversion_agent" value="@if(empty($fareQuoteIB['Results'])){{ $fareQuoteOB['Results']['Fare']['OfferedFare'] + ( $commisioninisagent / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] )  }}@else{{ $fareQuoteOB['Results']['Fare']['OfferedFare'] +  ( $commisioninisagent / 100 * $fareQuoteOB['Results']['Fare']['OfferedFare'] ) +  $fareQuoteIB['Results']['Fare']['OfferedFare'] + ( $commisioninisagent / 100 * $fareQuoteIB['Results']['Fare']['OfferedFare'] ) }}@endif" />
                                            <?php } ?>
                                            <input type="hidden" name="currency" value="{{ $fareQuoteOB['Results']['Fare']['Currency'] }}" />
                                            <input type="hidden" id="adultCountHidden" name="adultCountHidden"  value="{{ $adultCount }}">
                                            <input type="hidden" id="childCountHidden" name="childCountHidden"  value="{{ $childCount }}">
                                            <input type="hidden" id="infantCountHidden" name="infantCountHidden"  value="{{ $infantCount }}">
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


                                            <!-- Flight Ends -->
                                        </div>
                                        <input type="button" name="previous" class="previous btn btn-secondary" value="Previous" /> 
                                    </fieldset>
                                </form>
                                <hr/>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div style="padding:25px;">
                                        <a href="javascript:void(0);" onclick="$('#hotelNorms').slideToggle();" > <h4  style="text-align: center;padding:15px auto;text-transform: uppercase;">{{ __('labels.hotel_policy_lbl')}} <i class="fa fa-chevron-down" aria-hidden="true"></i></h4></a>
                                        <p class="hotel-norms" id='hotelNorms' style="display:none;padding:18px;line-height: 20px;font-size: 13px;color: #000;text-align: justify;">
                                            {{ strip_tags($roomDetails['BlockRoomResult']['HotelPolicyDetail']) }}
                                        </p>
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

<div id="policyModel" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">{{$roomDetails['BlockRoomResult']['HotelName']}} - {{ __('labels.hotel_policy_lbl')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>{!!html_entity_decode(strip_tags($roomDetails['BlockRoomResult']['HotelPolicyDetail']))!!}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('labels.close_label')}}</button>
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
                <a href="javascript:void(0);" class="btn btn-primary refresh-btn show-hotel-Wall">{{ __('labels.refresh_search')}}</a>
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
