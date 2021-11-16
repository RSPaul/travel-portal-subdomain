var roomCount = 1;
var roomCountFH = 1;
new WOW().init();

$(function() {
  $('#dateRange').daterangepicker({
    opens: 'center',
    locale: {
        format: 'DD-MM-YYYY',        
    },
    minDate:new Date(),
    autoApply: true,
  }, function(start, end, label) {
    
    $('#departHotel').val(start.format('DD-MM-YYYY'));
    $('#returnHotel').val(end.format('DD-MM-YYYY'));
    var Difference_In_Days = end.diff(start, 'days');

    if($('#local_sel').val() == 'heb') {
        if (Difference_In_Days > 0 && !isNaN(Difference_In_Days)) {
            if (Difference_In_Days == 1) {
                $('.total-nights').html($('#night_lbl').val() + '&nbsp;<span style="position:absolute;">' + Math.round(Difference_In_Days) + '</span>');
            } else {
                $('.total-nights').html($('#nights_lbl').val() + '&nbsp;<span style="position:absolute;">' + Math.round(Difference_In_Days) + '</span>');
            }
        } else {
            $('.total-nights').html($('#night_lbl').val() + '&nbsp;<span style="position:absolute;">1</span>');
        }
     } else {
        if (Difference_In_Days > 0 && !isNaN(Difference_In_Days)) {
            if (Difference_In_Days == 1) {
                $('.total-nights').html(Math.round(Difference_In_Days) + ' ' + $('#night_lbl').val());
            } else {
                $('.total-nights').html(Math.round(Difference_In_Days) + ' ' + $('#nights_lbl').val());
            }
        } else {
            $('.total-nights').html('1 ' + $('#night_lbl').val());
        }
     }

  });

  setTimeout(function() {
    $('#dateRange').val($('#departHotel').val() + ' - ' + $('#returnHotel').val());    
  },4000);
});

$(function() {
  $('#dateRangeFH').daterangepicker({
    opens: 'center',
    locale: {
        format: 'DD-MM-YYYY',        
    },
    minDate:new Date(),
    autoApply: true,
  }, function(start, end, label) {
    
    $('#departHotelFH').val(start.format('DD-MM-YYYY'));
    $('#returnHotelFH').val(end.format('DD-MM-YYYY'));
    var Difference_In_Days = end.diff(start, 'days');

    if($('#local_sel').val() == 'heb') {
        if (Difference_In_Days > 0 && !isNaN(Difference_In_Days)) {
            if (Difference_In_Days == 1) {
                $('.total-nights').html($('#night_lbl').val() + '&nbsp;<span style="position:absolute;">' + Math.round(Difference_In_Days) + '</span>');
            } else {
                $('.total-nights').html($('#nights_lbl').val() + '&nbsp;<span style="position:absolute;">' + Math.round(Difference_In_Days) + '</span>');
            }
        } else {
            $('.total-nights').html($('#night_lbl').val() + '&nbsp;<span style="position:absolute;">1</span>');
        }
    } else {
        if (Difference_In_Days > 0 && !isNaN(Difference_In_Days)) {
            if (Difference_In_Days == 1) {
                $('.total-nights').html(Math.round(Difference_In_Days) + ' ' + $('#night_lbl').val());
            } else {
                $('.total-nights').html(Math.round(Difference_In_Days) + ' ' + $('#nights_lbl').val());
            }
        } else {
            $('.total-nights').html('1 '+ $('#night_lbl').val());
        }
    }

  });

  setTimeout(function() {
    $('#dateRangeFH').val($('#departHotelFH').val() + ' - ' + $('#returnHotelFH').val());
  },4000);
});

$(document).ready(function () {

    /* Hotel Search on mobile */



    if(window.matchMedia("(max-width: 767px)").matches){

        if ($("#stickMobileMenu").length > 0){ 
        
            window.onscroll = function() {myFunction()};

            var navbar = document.getElementById("stickMobileMenu");
            var sticky = navbar.offsetTop;

            function myFunction() {
              if (window.pageYOffset > sticky) {
                navbar.classList.add("stickMobileHeader")
              } else {
                navbar.classList.remove("stickMobileHeader");
              }
            }

            $('.searchHotelMobileForm').hide();
        }

        $('.hidemobileForm').hide();

        setTimeout(function(){

            //$('.searchHotelMobileFormMap').hide();

        }, 2000);
        

        $('.toggleFormHSearch').click(function(){

            $('.searchHotelMobileForm').slideToggle();

            //$('.searchHotelMobileFormMap').slideToggle();

            $(window).scrollTop(0);

        });



    }


    /* Ends here */


    $('.show-price-room').click(function(){
        $("html, body").animate({ scrollTop: 0 }, "slow");
        setTimeout(function(){
            $('#dateRange').focus();
        }, 2000);
    });

    var isHome = $('#isHome').val();
    if(isHome && isHome == '1') {
        var selectedTab = $('#showTab').val();
        document.getElementById("mp4_src").src = selectedTab +"-bg.mp4";
        document.getElementById("webm_src").src = selectedTab +"-bg.webm";
        document.getElementById("ogg_src").src = selectedTab +"-b";
        document.getElementById("homeVideo").load();
    }


    $('a[data-toggle="tab"]').on('click', function (event) {
      event.preventDefault()
      $(this).tab('show')
    })


    $('.cancel-book-btn').click(function() {
        $($(this).data('target')).modal('show');
    });

    if ($('.showProgressLoader').length) {
        $("#loadingInProgress").modal("show");
        $('.data-section').hide();
    }

    $('.menu-link').click(function (e) {
        var tab = $(this).data('tab');
        //console.log('tab uis ', tab);
        //e.preventDefault();
        if (tab && tab != null) {
            if (isHome && isHome == '1') {
               
                document.getElementById("mp4_src").src = tab +"-bg.mp4";
                document.getElementById("webm_src").src = tab +"-bg.webm";
                document.getElementById("ogg_src").src = tab +"-bg.ogv";
                document.getElementById("homeVideo").load();
               
                $('.tab-pane').removeClass('active');
                $('.form_tab_data').removeClass('active');
                $('.menu-link').removeClass('active');

                $('#' + tab).find('.tab-pane').addClass('active');
                $('#' + tab).addClass('active');
                $(this).addClass('active');

                if (history.pushState) {
                    var url = window.location.href;       
                    var urlSplit = url.split( "?" );   
                    var obj  = { Title : "New title", Url: urlSplit[0] + "?show=" + tab};       
                    history.pushState(obj, obj.Title, obj.Url);
                }

                var referral = $('#referral').val();
                if (referral && referral != '') {
                   // window.location.href = '/referral/' + referral + '?show=' + tab;
                } else {

                    //window.location.href = '/?show=' + tab;
                }

            } else {
                window.location.href = '/?show=' + tab;
            }

        } else {
            window.location.href = tab;
        }
    });


    /* Add Bagage and Meal Cost for Flights */

    $('.baggageDropDown').change(function() {
        

        var priceTotal = parseFloat($('#BOOKING_PRICE_EXTRA').val());

        var priceBaggage = 0;
        var priceMeal = 0;

        $('.baggageDropDown').each(function(){

            var baggagePrice = $(this).find(':selected').attr('data-price');
            var mealPrice = $(this).find(':selected').attr('data-meal-price');
            if(isNaN(mealPrice)){ mealPrice = 0; }
            var baggageCurrency = $(this).find(':selected').attr('data-currency');

            priceBaggage = priceBaggage + parseFloat(baggagePrice);
            priceMeal    = priceMeal + parseFloat(mealPrice);
            //console.log('First', priceBaggage, priceMeal);

            priceTotal = parseFloat(baggagePrice) + parseFloat(mealPrice) + parseFloat(priceTotal);
            pricemealbaggage = parseFloat(priceBaggage) + parseFloat(priceMeal);

             //console.log('Second', priceTotal, pricemealbaggage);

        });

        $('#BOOKING_PRICE').val(priceTotal);
        $('#extra_baggage_meal_price').val(pricemealbaggage);

        $('#ORIGINAL_BOOKING_PRICE_PME').val(priceTotal);
        $('#fullAmount').val(priceTotal);
        $('#fullAmount_Install').val(priceTotal);

        //console.log('Third' , pricemealbaggage, priceTotal);

        options.amount = (priceTotal* 100).toFixed(2);
        
        priceBaggage = number_format(priceBaggage, true, '.', ',');
        priceMeal = number_format(priceMeal, true, '.', ',');
        priceTotal = number_format(priceTotal, true, '.', ',');

        //console.log('Fourth' , priceBaggage, priceMeal, priceTotal);


        //console.log(priceTotal , priceBaggage);
        
        $('.basic_baggage_charges').show();
        $('.basic_meal_charges').show();

        $('.baggagePrices').html($(this).find(':selected').attr('data-currency') + ' '+ priceBaggage );
        $('.mealPrices').html($(this).find(':selected').attr('data-currency') + ' '+ priceMeal );
        $('.final_price').html($(this).find(':selected').attr('data-currency') + ' '+  priceTotal );

        $('.btn-open-pay-form').html('Pay '+priceTotal+' '+$("#CURRENCY_VAL").val());

        $(".period_payment").html($('#CURRENCY_VAL').val()+' '+priceTotal);
        $(".total_payment").html($('#CURRENCY_VAL').val()+' '+priceTotal);
        $(".amount_interest").html($('#CURRENCY_VAL').val()+' '+0);

        $('#installments_val').val('1');


        if($("#agent_makrup").val() && $("#agent_makrup").val() != '0'){

            //var bookprice = $("#ORIGINAL_BOOKING_PRICE").val();
            var totalPrice = parseFloat($('#BOOKING_PRICE').val()) + parseFloat($("#agent_makrup").val());
            $("#BOOKING_PRICE").val(totalPrice);
            
            options.amount = totalPrice * 100;
            var formated_number = number_format(totalPrice, true, '.', ',');

            var curncy = $("#CURRENCY_VAL").val();
            $(".dueAmount").html(curncy + " " + formated_number);
            $(".final_price").html(curncy + " " + formated_number + "<br><span class='tax-included'>Includes taxes and charges</span>");
        }



    });

    /* Ends here */

    $('.trendingOne').click(function () {
        $('#searchTrend1Form').submit();
    });

    $('.trendingTwo').click(function () {
        $('#searchTrend2Form').submit();
    });

    $('.trendingThree').click(function () {
        $('#searchTrend3Form').submit();
    });


    let travellersCACTLoad = parseInt($('.adultsCC').first().val()) + parseInt($('.childsCC').first().val());
    let sCACTLoad = (travellersCACTLoad > 1) ? 's' : '';

    if(!$('#isHome').length) {
        $('#travellersClassCabOne').val(travellersCACTLoad + ' Traveller' + sCACTLoad);
    }

    $('.adCC li').each(function () {

        let adultCL = $('.adultsCC').first().val();

        if ($(this).data('cy') == parseInt(adultCL)) {
            $('.clCC li').removeClass('selected');
            $(this).addClass('selected');
        }

    });

    $('.clCC li').each(function () {

        let chidsCL = $('.childsCC').first().val();

        if ($(this).data('cy') == parseInt(chidsCL)) {
            $('.clCC li').removeClass('selected');
            $(this).addClass('selected');
        }

    });


    let travellersCAL = parseInt($('.adultsCCA').first().val()) + parseInt($('.childsCCA').first().val());
    let sCAL = (travellersCAL > 1) ? 's' : '';
    if(!$('#isHome').length) {
        $('#travellersClassactOne').val(travellersCAL + ' Traveller' + sCAL);
    }

    $('.adCCA li').each(function () {

        let adultCL = $('.adultsCCA').first().val();

        if ($(this).data('cy') == parseInt(adultCL)) {
            $('.adCCA li').removeClass('selected');
            $(this).addClass('selected');
        }

    });

    $('.clCCA li').each(function () {

        let chidsCL = $('.childsCCA').first().val();

        if ($(this).data('cy') == parseInt(chidsCL)) {
            $('.clCCA li').removeClass('selected');
            $(this).addClass('selected');
        }

    });

    $('#sessionExpiryTimerDiv').hide();
    $('.sessionExpiryTimerDiv').hide();
    setTimeout(function () {
        //show session expiry on hotel list page
        if (($('#isViewHotel') && $('#isViewHotel').length)) {
            var intervalHotelList = setInterval(function () {
                var cookieVal = $('#search_id').val();
                var currentTime = new Date().getTime();
                var countDownDate = new Date(cookieVal * 1000).getTime();
                if (Math.floor(currentTime / 1000) > cookieVal || (isNaN(cookieVal))) {
                    $('#sessionWarningModal').modal('show');
                    clearInterval(intervalHotelList);
                    $('#sessionExpiryTimer').html("Offer Expired");
                    $('.sessionExpiryTimer').html("Offer Expired");
                } else {
                    var distance = countDownDate - currentTime;
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    if (minutes <= 1) {
                        $('#sessionExpiryTimer').addClass('warning');
                         $('.sessionExpiryTimer').addClass('warning');
                    }
                    var expiryTime = minutes + " minutes " + seconds + " seconds";
                    if (minutes != -1 && seconds != -1) {
                        $('#sessionExpiryTimer').html(expiryTime);
                        $('.sessionExpiryTimer').html(expiryTime);
                    }
                }
            }, 1000);
            
            $('#sessionExpiryTimerDiv').show();
            $('.sessionExpiryTimerDiv').show();
        }
    }, 90000);

    $('.show-hotel').click(function () {
        IsplaceChange=true;
        $('#searchRoomsForm').submit();
    });

    $('.show-hotel-Wall').click(function () {
        IsplaceChange=true;

        $.ajax({
            url: '/api/send-wallet-single',
            method: "POST",
            data: {wallet: true},
            dataType: "json",
            success: function (data)
            {
                console.log('check email ', data);
                // return true;
            },
            error: function(error) {
                console.log('error in check email.');
            }
        });
        
        $('#searchRoomsForm').submit();
    });


    /* Flight Popup for time for Expiry trace Id */

    setTimeout(function () {

        //show session expiry on hotel list page
        if ($('#flightExpireDiv') && $('#flightExpireDiv').length) {
            $('#sessionExpiryTimer').show();
            var intervalHotelList = setInterval(function () {
                var cookieVal = parseInt(getCookie('flight_session'));
                var currentTime = new Date().getTime();
                var countDownDate = new Date(cookieVal * 1000).getTime();

                if (Math.floor(currentTime / 1000) > cookieVal) {
                    $('#sessionWarningModal').modal('show');
                    clearInterval(intervalHotelList);
                    $('#sessionExpiryTimer').html("Offer Expired");
                } else {
                    var distance = countDownDate - currentTime;
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    if (minutes <= 1) {
                        $('#sessionExpiryTimer').addClass('warning');
                    }
                    var expiryTime = minutes + " minutes " + seconds + " seconds";
                    if (minutes != -1 && seconds != -1) {
                        $('#sessionExpiryTimer').html(expiryTime);
                    }
                }
            }, 1000);
        }
        $('#sessionExpiryTimerDiv').show();
    }, 90000);


    setTimeout(function () {

        //show session expiry on hotel list page
        if ($('#cabExpireDiv') && $('#cabExpireDiv').length) {
            $('#sessionExpiryTimer').show();
            var intervalHotelList = setInterval(function () {
                var cookieVal = parseInt(getCookie('cab_session'));
                var currentTime = new Date().getTime();
                var countDownDate = new Date(cookieVal * 1000).getTime();

                if (Math.floor(currentTime / 1000) > cookieVal) {
                    $('#sessionWarningModal').modal('show');
                    clearInterval(intervalHotelList);
                    $('#sessionExpiryTimer').html("Offer Expired");
                } else {
                    var distance = countDownDate - currentTime;
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    if (minutes <= 1) {
                        $('#sessionExpiryTimer').addClass('warning');
                    }
                    var expiryTime = minutes + " minutes " + seconds + " seconds";
                    if (minutes != -1 && seconds != -1) {
                        $('#sessionExpiryTimer').html(expiryTime);
                    }
                }
            }, 1000);
        }
        $('#sessionExpiryTimerDiv').show();
    }, 90000);


    $('.show-flight-search').click(function () {
        $('#searchFlightsFormRound').submit();
    });
    
//    $(document).on("change","#hotelRoomList input[type='radio']",function(){
//        $(this).parent().parent().parent().find("tr").removeClass("active");
//        $(this).parent().parent().addClass("active");
//    });
    
//    $(document).on("click","#hotelRoomsList tr",function(){
//        alert($(this).data('isdisabled'));
//        if($(this).data('isdisabled')==false){
//            $(this).parent().find("tr").removeClass("active");
//            $(this).addClass("active");
//            $(this).find("input[type='radio']").prop('checked', true).trigger("change");
//        }
//    });
    
    $(document).on("click","#returnFlightTab .f-list",function(){
        $("#returnFlightTab .f-list").removeClass("active");
        $(this).addClass("active");
        $(this).find("input[type='radio']").prop('checked', true).trigger("change");
    });
    
    $(document).on("click","#OneWayFlightTab .f-list",function(){
        $("#OneWayFlightTab .f-list").removeClass("active");
        $(this).addClass("active");
        $(this).find("input[type='radio']").prop('checked', true).trigger("change");
    });

    /* Flight Popup Ends Here */


    /* Cab Popup for time for Expiry trace Id */

    setTimeout(function () {

        //show session expiry on hotel list page
        if ($('#cabExpireDiv') && $('#cabExpireDiv').length) {
            $('#sessionExpiryTimer').show();
            var intervalHotelList = setInterval(function () {
                var cookieVal = parseInt(getCookie('cab_session'));
                var currentTime = new Date().getTime();
                var countDownDate = new Date(cookieVal * 1000).getTime();

                if (Math.floor(currentTime / 1000) > cookieVal) {
                    $('#sessionWarningModal').modal('show');
                    clearInterval(intervalHotelList);
                    $('#sessionExpiryTimer').html("Offer Expired");
                } else {
                    var distance = countDownDate - currentTime;
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    if (minutes <= 1) {
                        $('#sessionExpiryTimer').addClass('warning');
                    }
                    var expiryTime = minutes + " minutes " + seconds + " seconds";
                    if (minutes != -1 && seconds != -1) {
                        $('#sessionExpiryTimer').html(expiryTime);
                    }
                }
            }, 1000);
        }
        $('#sessionExpiryTimerDiv').show();
    }, 90000);

    setTimeout(function () {

        //show session expiry on hotel list page
        if ($('#activityExpireDiv') && $('#activityExpireDiv').length) {
            $('#sessionExpiryTimer').show();
            var intervalHotelList = setInterval(function () {
                var cookieVal = parseInt(getCookie('activity_session'));
                var currentTime = new Date().getTime();
                var countDownDate = new Date(cookieVal * 1000).getTime();

                if (Math.floor(currentTime / 1000) > cookieVal) {
                    $('#sessionWarningModal').modal('show');
                    clearInterval(intervalHotelList);
                    $('#sessionExpiryTimer').html("Offer Expired");
                } else {
                    var distance = countDownDate - currentTime;
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    if (minutes <= 1) {
                        $('#sessionExpiryTimer').addClass('warning');
                    }
                    var expiryTime = minutes + " minutes " + seconds + " seconds";
                    if (minutes != -1 && seconds != -1) {
                        $('#sessionExpiryTimer').html(expiryTime);
                    }
                }
            }, 1000);
        }
        $('#sessionExpiryTimerDiv').show();
    }, 90000);

    $('.show-cab-search').click(function () {
        $('#searchCabsForm').submit();
    });

    /* Cab Popup Ends Here */

    /* Activity Popup for time for Expiry trace Id */

    setTimeout(function () {

        //show session expiry on hotel list page
        if ($('#activityExpireDiv') && $('#activityExpireDiv').length) {
            $('#sessionExpiryTimer').show();
            var intervalHotelList = setInterval(function () {
                var cookieVal = parseInt(getCookie('activity_session'));
                var currentTime = new Date().getTime();
                var countDownDate = new Date(cookieVal * 1000).getTime();

                if (Math.floor(currentTime / 1000) > cookieVal) {
                    $('#sessionWarningModal').modal('show');
                    clearInterval(intervalHotelList);
                    $('#sessionExpiryTimer').html("Offer Expired");
                } else {
                    var distance = countDownDate - currentTime;
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    if (minutes <= 1) {
                        $('#sessionExpiryTimer').addClass('warning');
                    }
                    var expiryTime = minutes + " minutes " + seconds + " seconds";
                    if (minutes != -1 && seconds != -1) {
                        $('#sessionExpiryTimer').html(expiryTime);
                    }
                }
            }, 1000);
        }
        $('#sessionExpiryTimerDiv').show();
    }, 90000);


    $('.show-activity-search').click(function () {
        $('#searchActivityForm').submit();
    });

    /* Activity Popup Ends Here */


    $('#registerForm').parsley();
    // $('#registerBtn').click(function(e) {
    //     if (!$('#registerForm').parsley().isValid()) {
    //         //e.preventDefault();
    //         return false;
    //     } else {
    //         console.log('check email');
    //         $.ajax({
    //             url: '/api/check-email',
    //             method: "POST",
    //             data: {email: $('#email').val()},
    //             dataType: "json",
    //             success: function (data)
    //             {
    //                 console.log('check email ', data);
    //                 // return true;
    //             },
    //             error: function(error) {
    //                 console.log('error in check email.');
    //             }
    //         });
    //     }
    // });
    window.ParsleyValidator.addValidator('checkemail', {
        validateString: function (value)
        {   
            $('.action-button').prop('disabled', true);
           // $('.action-button').val('Pleasw wait..');
            $('.action-button').addClass('disabled');
            return $.ajax({
                url: '/api/check-email',
                method: "POST",
                data: {email: value},
                dataType: "json",
                success: function (data)
                {
                    $('.action-button').removeAttr('disabled');
                    $('.action-button').removeClass('disabled');
                    //$('.action-button').val('Next Step');
                    return true;
                }
            });
        }
    });

    window.ParsleyValidator.addValidator('checkpassword', {
        validateString: function (value)
        {   
            var pvalue = $('#password').val();
            var cvalue = $('#confirm_password').val();

            if(pvalue != '' && cvalue != ''){
                if(pvalue != cvalue){
                  $('.regsiterAff').prop("disabled", true);
                 // alert('Not Same');   
                }else{
                    $('.regsiterAff').removeAttr("disabled");
                }
            }
        }
    });

    // var form0 = document.getElementById("searchRoomsFormMain0");

    // if(form0) {
    //   document.getElementById("bookHotelRandom0").addEventListener("click", function () {
    //     form0.submit();
    //   });
    // }

    // var form1 = document.getElementById("searchRoomsFormMain1");

    // if(form1) {
    //   document.getElementById("bookHotelRandom1").addEventListener("click", function () {
    //     form1.submit();
    //   });
    // }

    // var form2 = document.getElementById("searchRoomsFormMain2");

    // if(form2) {
    //   document.getElementById("bookHotelRandom2").addEventListener("click", function () {
    //     form2.submit();
    //   });
    // }

    // var form3 = document.getElementById("searchRoomsFormMain3");

    // if(form3) {
    //   document.getElementById("bookHotelRandom3").addEventListener("click", function () {
    //     form3.submit();
    //   });
    // }

    // var form4 = document.getElementById("searchRoomsFormMain4");

    // if(form4) {
    //   document.getElementById("bookHotelRandom4").addEventListener("click", function () {
    //     form4.submit();
    //   });
    // }

    // var form5 = document.getElementById("searchRoomsFormMain5");

    // if(form5) {
    //   document.getElementById("bookHotelRandom5").addEventListener("click", function () {
    //     form5.submit();
    //   });
    // }

    $('.view-refund').click(function () {
        $(this).html('Please wait&nbsp;<i class="fa fa-spinner" aria-hidden="true"></i>');
        var that = $(this);
        $.ajax({
            url: '/user/view-refund-status/hotel',
            type: 'POST',
            data: {ChangeRequestId: $(this).data('requestid'), TokenId: $(this).data('tokenid')},
            dataType: 'JSON',
            success: function (response) {
                that.html('(Click to view Refund Status)');
                // console.log('refund status ', response);
                var msg = '';
                if (response.refunded_amount != 0) {
                    msg = '<div class="cal alert alert-success">' + response.message + ' and your refund amount is USD ' + response.refunded_amount + '</div>'
                } else {
                    msg = '<div class="cal alert alert-success">' + response.message + '</div>'
                }
                that.after(msg);
                setTimeout(function () {
                    $(document).find('div.cal').remove();
                }, 10000);
            },
            error: function (error) {
                that.html('(Click to view Refund Status)');
                console.log('Error while showing refund status ', error);
            }
        })
    });

    $('.view-refund-cab').click(function () {
        $(this).html('Please wait&nbsp;<i class="fa fa-spinner" aria-hidden="true"></i>');
        var that = $(this);
        $.ajax({
            url: '/user/view-refund-status/cab',
            type: 'POST',
            data: {ChangeRequestId: $(this).data('requestid'), TokenId: $(this).data('tokenid')},
            dataType: 'JSON',
            success: function (response) {
                that.html('(Click to view Refund Status)');
                var msg = '';
                if (response.refunded_amount != 0) {
                    msg = '<div class="cal alert alert-success">' + response.message + ' and your refund amount is USD ' + response.refunded_amount + '</div>'
                } else {
                    msg = '<div class="cal alert alert-success">' + response.message + '</div>'
                }
                that.after(msg);
                setTimeout(function () {
                    $(document).find('div.cal').remove();
                }, 10000);
            },
            error: function (error) {
                that.html('(Click to view Refund Status)');
                console.log('Error while showing refund status ', error);
            }
        })
    });

    /* CHeck Refund Status FLight */
    $('.view-refund-flight').click(function () {
        $(this).html('Please wait&nbsp;<i class="fa fa-spinner" aria-hidden="true"></i>');
        var that = $(this);
        $.ajax({
            url: '/user/view-refund-status/flight',
            type: 'POST',
            data: {ChangeRequestId: $(this).data('requestid'), TokenId: $(this).data('tokenid')},
            dataType: 'JSON',
            success: function (response) {
                that.html('(Click to view Refund Status)');
                var msg = '';
                if (response.refunded_amount != 0) {
                    msg = '<div class="cal alert alert-success">' + response.message + ' and your refund amount is USD ' + response.refunded_amount + '</div>'
                } else {
                    msg = '<div class="cal alert alert-success">' + response.message + '</div>'
                }
                that.after(msg);
                setTimeout(function () {
                    $(document).find('div.cal').remove();
                }, 10000);
            },
            error: function (error) {
                that.html('(Click to view Refund Status)');
                console.log('Error while showing refund status ', error);
            }
        })
    });
    /* Code Ends*/

    $('.view-refund-act').click(function () {
        $(this).html('Please wait&nbsp;<i class="fa fa-spinner" aria-hidden="true"></i>');
        var that = $(this);
        $.ajax({
            url: '/user/view-refund-status/activity',
            type: 'POST',
            data: {ChangeRequestId: $(this).data('requestid'), TokenId: $(this).data('tokenid')},
            dataType: 'JSON',
            success: function (response) {
                that.html('(Click to view Refund Status)');
                var msg = '';
                if (response.refunded_amount != 0) {
                    msg = '<div class="cal alert alert-success">' + response.message + ' and your refund amount is USD ' + response.refunded_amount + '</div>'
                } else {
                    msg = '<div class="cal alert alert-success">' + response.message + '</div>'
                }
                that.after(msg);
                setTimeout(function () {
                    $(document).find('div.cal').remove();
                }, 10000);
            },
            error: function (error) {
                that.html('(Click to view Refund Status)');
                console.log('Error while showing refund status ', error);
            }
        })
    });

});

$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // if($('#promotionModal').length) {
    //   $('#promotionModal').modal('show');
    // }
    $(".dob").datepicker({
        autoclose: true,
        todayHighlight: true,
        endDate: new Date(),
        format: 'dd-mm-yyyy'
    });

    $('.ht_imgs a').simpleLightbox();

    $('.popular_flights').owlCarousel({
        loop: true,
        margin: 10,
        autoplay: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1.3,
                nav: true
            },
            600: {
                items: 2,
                nav: false
            },
            1000: {
                items: 4,
                nav: true,
                loop: false,
                margin: 10
            }
        }
    });
    $('.special_flights').owlCarousel({
        loop: true,
        margin: 10,
        autoplay: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1.3,
                nav: true
            },
            600: {
                items: 2,
                nav: false
            },
            1000: {
                items: 3,
                nav: true,
                loop: false,
                margin: 10
            }
        }
    });

    $(".pass_expiry_date").datepicker({
        autoclose: true,
        todayHighlight: true,
        //minDate: new Date(),
        //startDate: "dateToday",
        format: 'yyyy-mm-dd'
    });

    $(".datepicker").datepicker({
        autoclose: true,
        todayHighlight: true
    }).datepicker('update', new Date());

    function addIconList (state) {
        if (!state.id) { return state.text; }
        return '<span class="fa fa-lg fa-plane"></span> '+state.text;
    } 


    $('.depart-from').select2({
        placeholder: "Select a city",
        templateResult: addIconList,
        templateSelection: addIconList,
        escapeMarkup: function(m) { return m; },
        minimumInputLength: 1,
        ajax: {
            url: function (params) {
                return '/api/cities-air/' + params.term;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    })
            .on('select2:select', function (e) {
                $('.from-city').html(e.params.data.value);
                $('.from-city-fh').html(e.params.data.value);
                $('#from-city').val(e.params.data.label);
                $('#from-city-fh').val(e.params.data.label);
                $('.depart-from').html('');
                $('.depart-from').html('<option value="' + e.params.data.id + '">' + e.params.data.label + '</option>');
                $('.depart-to').focus();
            });

    $('.depart-to').select2({
        placeholder: "Destination",
        templateResult: addIconList,
        templateSelection: addIconList,
        escapeMarkup: function(m) { return m; },
        minimumInputLength: 1,
        ajax: {
            url: function (params) {
                return '/api/cities-air/' + params.term;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    })
            .on('select2:select', function (e) {
                $('.to-city').html(e.params.data.value);
                $('.to-city-fh').html(e.params.data.value);
                $('#to-city').val(e.params.data.label);
                $('#country_code_fh').val(e.params.data.country);
                $('#to-city-fh').val(e.params.data.label);
                $('.cityNameFH').val(e.params.data.label);
                $('.depart-to').html('');
                $('.depart-to').html('<option value="' + e.params.data.id + '">' + e.params.data.label + '</option>');
                $('.departdate').focus();
                

            });

     $('.depart-to-FH').select2({
        placeholder: "Destination",
        templateResult: addIconList,
        templateSelection: addIconList,
        escapeMarkup: function(m) { return m; },
        minimumInputLength: 1,
        ajax: {
            url: function (params) {
                return '/api/cities-air/' + params.term;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    })
            .on('select2:select', function (e) {
                $('.to-city').html(e.params.data.value);
                $('.to-city-fh').html(e.params.data.value);
                $('#to-city').val(e.params.data.label);
                $('#country_code_fh').val(e.params.data.country);
                $('#to-city-fh').val(e.params.data.label);
                $('.cityNameFH').val(e.params.data.label);
                $('.depart-to').html('');
                $('.depart-to').html('<option value="' + e.params.data.id + '">' + e.params.data.label + '</option>');
                $('.departdate').focus();
                var cityName =  e.params.data.label;
                var countryCOde = e.params.data.country;
                var url = '';
                if ($('#halal-city_name').val() == 1) {
                        url = '/api/hcities-FH/' + cityName+'/'+countryCOde;
                } else {
                        url = '/api/cities-FH/' + cityName+'/'+countryCOde;
                }
                $.ajax({
                method: 'GET',
                url: url,
                success: function (response) {

                        $('.hotel-cityFH').html('');
                        $('.hotel-cityFH').html('<option value="' + response[0].label + '">' + response[0].label + '</option>');
                        $('.selected-hotel-cityFH').html(response[0].value);
                        $('#city_idFH').val(response[0].id);
                        $('#city_id').val(response[0].id);
                        $('#country_codeFH').val(response[0].countryCode);
                        $('#country_nameFH').val(response[0].value);
                        $('#city_nameFH').val(response[0].cityName);
                        $('.departdate').focus();

                        if (response[0].type === 'hotel') {
                            $('#preffered_hotelFH').val(response[0].hotelCode);
                        } else {
                            $('#preffered_hotelFH').val('');
                        }


                    },
                    error: function (error) {
                       
                    },
                });

                /* Get Lat long From City */

                var request = new XMLHttpRequest();

                var method = 'GET';

                var urlMap = 'https://maps.googleapis.com/maps/api/geocode/json?address='+cityName+', '+countryCOde+'&key=AIzaSyDlmbzdiJw-ZZbwwWimGpZk96wQg77emoY';

                var async = true;

                request.open(method, urlMap, async);
                request.onreadystatechange = function(){
                  if(request.readyState == 4 && request.status == 200){
                    var data = JSON.parse(request.responseText);
                    //var address = data.results[1];

                    for (const address of data.results) {
                         if(address.types && address.types[0] &&  address.types[0] == 'locality') {


                            //for (const component of address.geometry) {

                                $("#LatitudeFH").val(address.geometry.location.lat);
                                $("#LongitudeFH").val(address.geometry.location.lng);
                                $("#autocompleteFH").val(cityName);

                                //console.log(address.geometry.location.lat);
                           // }

                         }
                    }
                    

                  }
                };
                request.send();

            });       

    $('.halal-city').select2({
        placeholder: "Type city or hotel name",
        minimumInputLength: 3,
        ajax: {
            url: function (params) {
                return '/api/hcities/' + params.term;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    }).on('select2:select', function (e) {
        $('.hotel-city').html('');
        $('.hotel-city').html('<option value="' + e.params.data.label + '">' + e.params.data.label + '</option>');
        $('.selected-hotel-city').html(e.params.data.value);
        $('#halal-city_id').val(e.params.data.id);
        $('#halal-country_code').val(e.params.data.countryCode);
        $('#halal-country_name').val(e.params.data.value);
        $('#halal-city_name').val(e.params.data.cityName);
        $('.departdate').focus();

        if (e.params.data.type === 'hotel') {
            $('#halal-preffered_hotel').val(e.params.data.hotelCode);
        } else {
            $('#halal-preffered_hotel').val('');
        }
    });

    $('.hotel-city').select2({
        placeholder: "Type city name",
        minimumInputLength: 3,
        ajax: {
            url: function (params) {
                if ($('#halal-city_name').val() == 1) {
                    return '/api/hcities/' + params.term;
                } else {
                    return '/api/cities/' + params.term;
                }
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    }).on('select2:select', function (e) {

        $('.hotel-city').html('');
        $('.hotel-city').html('<option value="' + e.params.data.label + '">' + e.params.data.label + '</option>');
        $('.selected-hotel-city').html(e.params.data.value);
        $('#city_id').val(e.params.data.id);
        $('#country_code').val(e.params.data.countryCode);
        $('#country_name').val(e.params.data.value);
        $('#city_name').val(e.params.data.cityName);
        $('.departdate').focus();

        if (e.params.data.type === 'hotel') {
            $('#preffered_hotel').val(e.params.data.hotelCode);
        } else {
            $('#preffered_hotel').val('');
        }

    });


    $('.hotel-cityFH').select2({
        placeholder: "Type city name",
        minimumInputLength: 3,
        ajax: {
            url: function (params) {
                if ($('#halal-city_name').val() == 1) {
                    return '/api/hcities/' + params.term;
                } else {
                    return '/api/cities/' + params.term;
                }
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    }).on('select2:select', function (e) {

        $('.hotel-cityFH').html('');
        $('.hotel-cityFH').html('<option value="' + e.params.data.label + '">' + e.params.data.label + '</option>');
        $('.selected-hotel-cityFH').html(e.params.data.value);
        $('#city_idFH').val(e.params.data.id);
        $('#country_codeFH').val(e.params.data.countryCode);
        $('#country_nameFH').val(e.params.data.value);
        $('#city_nameFH').val(e.params.data.cityName);
        $('.departdate').focus();

        if (e.params.data.type === 'hotel') {
            $('#preffered_hotelFH').val(e.params.data.hotelCode);
        } else {
            $('#preffered_hotelFH').val('');
        }

    });

    $('.hotel-city-us').select2({
        placeholder: "Type city name",
        minimumInputLength: 3,
        ajax: {
            url: function (params) {
                return '/api/us-cities/' + params.term;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    }).on('select2:select', function (e) {
        $('.hotel-city-us').html('');
        $('.hotel-city-us').html('<option value="' + e.params.data.label + '">' + e.params.data.label + '</option>');
        $('.selected-hotel-city').html(e.params.data.value);
        $('#city_id').val(e.params.data.id);
        $('#city_name').val(e.params.data.cityName);
        $('#country_code').val(e.params.data.countryCode);
        $('#country_name').val(e.params.data.value);
        $('.departdate').focus();

        // $('#Latitude').val('');
        // $('#Longitude').val('');
        // $('#autocomplete').val('');
        if (e.params.data.type === 'hotel') {
            $('#preffered_hotel').val(e.params.data.hotelCode);
        } else {
            $('#preffered_hotel').val('');
        }

    });

    // $(document).on('keyup', '.select2-search__field', function() {
    //     if($('.hotel-city-us').length) {
    //         $('#Latitude').val('');
    //         $('#Longitude').val('');
    //     }
    // });
    //select2-results__option select2-results__option--selectable select2-results__option--selected select2-results__option--highlighted

    $('.pick_up_point_auto').select2({
        placeholder: "Select Hotel Name",
        minimumInputLength: 3,
        ajax: {
            url: function (params) {
                return '/api/cabs-cities-pickup/' + $('#city_cab_id').val() + '/' + $('#pick_up_type').val() + '/' + params.term;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    })
            .on('select2:select', function (e) {
                $('.pick_up_point').html('');
                $('.pick_up_point').html('<option value="' + e.params.data.id + '">' + e.params.data.value + '</option>');
                $('#pick_up_point_name').val(e.params.data.value);
            });


    $('.cab-city').select2({
        placeholder: "Select a city",
        minimumInputLength: 3,
        ajax: {
            url: function (params) {
                return '/api/cabs-cities/' + params.term;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    })
            .on('select2:select', function (e) {
                $('.cab-city').html('');
                $('.cab-city').html('<option value="' + e.params.data.label + '">' + e.params.data.value + '</option>');
                $('.selected-cab-city').html(e.params.data.value);
                $('#city_cab_id').val(e.params.data.id);
                $('.country_val').val(e.params.data.currency_code);
                $('#country_cab_name').val(e.params.data.value);
                $('#currency_code').val(e.params.data.currency_code);
                // $('.departdate').focus();

                //reset all select boxes
                $('#pick_up_type').prop('selectedIndex', 0);
                $('#drop_off_type').prop('selectedIndex', 0);
                $('#pick_up_point').html("<option value=''>Select Location</option>");
                $('#drop_off_point').html("<option value=''>Select Location</option>");
            });

    /* Accomodation Type */



    $('.drop_off_point_auto').select2({
        placeholder: "Select Hotel Name",
        minimumInputLength: 3,
        ajax: {
            url: function (params) {
                return '/api/cabs-cities-pickup/' + $('#city_cab_id').val() + '/' + $('#drop_off_type').val() + '/' + params.term;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    })
            .on('select2:select', function (e) {
                $('.drop_off_type').html('');
                $('.drop_off_type').html('<option value="' + e.params.data.id + '">' + e.params.data.value + '</option>');
                $('#drop_off_point_name').val(e.params.data.value);
            });
    /* Ends Here */



    $('#pick_up_type').change(function () {
        if ($('#pick_up_type').val() == '0') {
            $('.non_acc_city').hide();
            $('.accom_city').show();
        } else {
            $('.non_acc_city').show();
            $('.accom_city').hide();
            $.ajax('/api/cabs-cities-pickup/' + $('#city_cab_id').val() + '/' + $('#pick_up_type').val(), // request url
                    {
                        success: function (data, status, xhr) {// success callback function
                            $('.pickup-city').html('');
                            pckupHtml = '';
                            for (i = 0; i < data.length; i++) {
                                pckupHtml += '<option value="' + data[i].id + '">' + data[i].value + '</option>';
                                $('.pickup-city').html(pckupHtml);
                                $('#pick_up_point_name').val(data[0].value);
                            }
                            if (!data.length) {
                                $('.pickup-city').html("<option value=''>Not Available</option>");
                            }

                        }
                    });
        }

    });

    $('#drop_off_type').change(function () {
        if ($('#drop_off_type').val() == '0') {
            $('.non_acc_city_drop').hide();
            $('.accom_city_drop').show();
        } else {
            $('.non_acc_city_drop').show();
            $('.accom_city_drop').hide();
            $.ajax('/api/cabs-cities-pickup/' + $('#city_cab_id').val() + '/' + $('#drop_off_type').val(), // request url
                    {
                        success: function (data, status, xhr) {// success callback function
                            $('.dropoff-city').html('');
                            dropHtml = '';
                            for (i = 0; i < data.length; i++) {
                                dropHtml += '<option value="' + data[i].id + '">' + data[i].value + '</option>';
                                $('.dropoff-city').html(dropHtml);
                                $('#drop_off_point_name').val(data[0].value);
                            }
                            if (!data.length) {
                                $('.dropoff-city').html("<option value=''>Not Available</option>");
                            }
                        }
                    });
        }


    });

    $('#pick_up_point').change(function () {
        var pickUp = $(this).val();
        $('#pick_up_point_name').val($('#pick_up_point option:selected').text());

        $('#drop_off_point option').each(function () {
            if ($(this).val() == pickUp) {
                $(this).attr('disabled', true)
            }
        });
    });

    $('#drop_off_point').change(function () {
        var dropUp = $(this).val();
        $('#drop_off_point_name').val($('#drop_off_point option:selected').text());
        $('#pick_up_point option').each(function () {
            if ($(this).val() == dropUp) {
                $(this).attr('disabled', true)
            }
        });
    });

    /* Get Country Code from currency code */

    $('.select_preffered_currency').change(function () {
        $.ajax('/api/get-country-code/' + $('.select_preffered_currency').val(), // request url
                {
                    success: function (data, status, xhr) {// success callback function
                        $('#country_code_value').val(data[0].code);
                    }

                });
    });


    $('.nationality').select2({
        placeholder: "Select a country",
        minimumInputLength: 3,
        ajax: {
            url: function (params) {
                return '/api/countries/' + params.term;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    })
            .on('select2:select', function (e) {
                $('.nationality').html('');
                $('.nationality').html('<option value="' + e.params.data.id + '">' + e.params.data.label + '</option>');
                $('.nationality-selected').html(e.params.data.value);
                $('#currency').val(e.params.data.currency_code);
                $('.hotel-city').focus();
            });

    // $('.mr-auto li').click(function() {
    //   let tabId = $(this).data('tab');
    //   $('.mr-auto li').removeClass('active');
    //   $(this).addClass('active');
    //   $('.form_tab_data').removeClass('active');
    //   $('#' + tabId).addClass('active');
    //   let isHome = $('#isHome').val();
    //   if(isHome && isHome == '1') {
    //     //do nothing
    //   } else {
    //     window.location.href = '/?show=' + tabId;
    //   }
    // });

    $('.trip-type').click(function () {
        $('#tripType').val($(this).data('type'));
        $('#JourneyType').val($(this).data('type'));
        $('.nav-item.trip-type').find('a').removeClass('active')
        $(this).find('a').removeClass('active')
        if ($(this).data('type') == '1') {
            $('#not-allowed').addClass('not-allowed');
        } else {
            $('#not-allowed').removeClass('not-allowed');
        }
    });

    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    var d = new Date();
    var dayName = days[d.getDay()];
    $('.departDay').html(dayName);
    $('.returnDay').html(dayName);
    var toDate = new Date();
    var frdate = new Date();
    var toDateOrg = new Date();
    // add a day
    var fromDate = frdate.setDate(frdate.getDate() + 1);

    $(".departdate").datepicker({
        autoclose: true,
        todayHighlight: true,
        minDate: new Date(),
        startDate: "dateToday",
        format: 'dd-mm-yyyy'
    }).on('changeDate', function (selected) {
        let startDate = new Date(selected.date.valueOf());
        let startDateOrg = new Date(selected.date.valueOf());

        startDateOrg.setDate(startDateOrg.getDate(new Date(selected.date.valueOf())));
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())) + 1);

        toDate = startDate;
        toDateOrg = startDateOrg;

        var tripType = $('#JourneyType').val();

        if($(document).find('.nav-item.menu-link.active').data('tab') !== 'flights' || ($(document).find('.nav-item.menu-link.active').data('tab') === 'flights' && tripType == '2')) {

            $(".returndate").val(selected.target.value);
            $('.returndate').datepicker('setDate', startDate);
          //  console.log('set date ', startDate , selected.target.value)
        }
        
        //console.log('set date 2', startDate , toDateOrg)
        $(".returndate").datepicker('setStartDate', startDate);


        var dayName = days[startDate.getDay()];
        $('.departDay').html(dayName);

        var date1 = toDate;
        var date2 = fromDate;

        var Difference_In_Time = date2.getTime() - toDateOrg.getTime();
        var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

        if (Difference_In_Days > 0 && !isNaN(Difference_In_Days)) {
            if (Difference_In_Days == 1) {
                $('.total-nights').html(Math.round(Difference_In_Days) + ' night');
            } else {
                $('.total-nights').html(Math.round(Difference_In_Days) + ' nights');
            }
        } else {
            $('.total-nights').html('1 night');
        }

        
        if($(document).find('.nav-item.menu-link.active').data('tab') !== 'flights' || ($(document).find('.nav-item.menu-link.active').data('tab') === 'flights' && tripType == '2')) {
            $('.nav-item.trip-type').each(function(){
                if($(this).data('type') == 2 && $(this).find('a').hasClass('active')) {
                    $('.returndate').focus();
                }
            });
        }

        if($(document).find('.nav-item.menu-link.active').data('tab') !== 'flights') {
            $('.returndate').focus();
        }

        
        var hotelActive = false;
        if ($('.nav-item.active').data('tab') == 'hotels') {
            hotelActive = true;
        }
    });

    $(".returndate").datepicker({
        autoclose: true,
        // todayHighlight: true,
        minDate: new Date(),
        startDate: "dateToday",
        format: 'dd-mm-yyyy',
    }).on('changeDate', function (selected) {

        var tripType = $('#JourneyType').val();
        if($(document).find('.nav-item.menu-link.active').data('tab') !== 'flights' || ($(document).find('.nav-item.menu-link.active').data('tab') === 'flights' && tripType == '2')) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                fromDate = startDate;
                
                var dayName = days[startDate.getDay()];
                $('.returnDay').html(dayName);
        }
        


        if($(document).find('.nav-item.menu-link.active').data('tab') === 'flights' || $('.nav.nav-tabs.flight-list').length) {
        
            $('.nav-item.trip-type').each(function(){
                if($(this).data('type') == 2) {
                    $('.nav-item.trip-type').find('a').removeClass('active')
                    $(this).find('a').addClass('active')
                }
            });
            $('#JourneyType').val('2');
            $('#not-allowed').removeClass('not-allowed');


       //     $('#travellersClassOne').trigger('click');
        } //else {
           // $('#roomsGuests').trigger('click');
        //}

        var date1 = toDate;
        var date2 = fromDate;
        //console.log('calcuklat ==> ',  date1, date2)
        var Difference_In_Time = date2.getTime() - toDateOrg.getTime();
        var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
        //console.log('Difference_In_Days ', Difference_In_Days)
        if (Difference_In_Days > 0 && !isNaN(Difference_In_Days)) {
            if (Difference_In_Days == 1) {
                $('.total-nights').html(Math.round(Difference_In_Days) + ' night');
            } else {
                $('.total-nights').html(Math.round(Difference_In_Days) + ' nights');
            }

            $('.total-nights').show();
        } else {
            $('.total-nights').html('1 night');
        }
    });

    $('#searchRoomsForm').parsley();
    /* Activities */

    $(".departdateAct").datepicker({
        autoclose: true,
        todayHighlight: true,
        minDate: new Date(),
        startDate: "dateToday",
        format: 'dd-mm-yyyy'
    });

    $('.act-city').select2({
        placeholder: "Select a city",
        minimumInputLength: 3,
        ajax: {
            url: function (params) {
                return '/api/act-cities/' + params.term;
            },
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    })
            .on('select2:select', function (e) {
                $('.act-city').html('');
                $('.act-city').html('<option value="' + e.params.data.label + '">' + e.params.data.value + '</option>');
                //$('.selected-cab-city').html(e.params.data.value);
                $('#city_act_id').val(e.params.data.id);
                //$('.country_val').val(e.params.data.currency_code);
                //$('#country_cab_name').val(e.params.data.value);
                $('#currency_code_act').val(e.params.data.currency_code);
            });


    /* Date Picker Cab*/
    $(".departdateCab").datepicker({
        autoclose: true,
        todayHighlight: true,
        minDate: new Date(),
        startDate: "dateToday",
        format: 'dd-mm-yyyy'
    });
    /* One Way and return showhide */
    $('.returnClass').hide();
    $('#oneWayTrip').click(function () {
        $('.returnClass').hide();
        $('#JourneyType').val('1');
    });

    $('#returnTrip').click(function () {
        $('.returnClass').show();
        $('#JourneyType').val('2');
    });

    var hiddenJourneyType = $('#hiddenJourneyType').val();
    if (hiddenJourneyType == '1') {
        $('.returnClass-2').hide();
    }
    $('.journey-type-radio').click(function () {
        if ($(this).val() == '1') {
            $('.returnClass-2').hide();
        } else {
            $('.returnClass-2').show();
        }
    });

    $('.modify-search').click(function () {
        $('.modify-search-div').slideToggle();
    });
    /* Ends Here*/

    /* On Select FLights */

    setTimeout(function () {
        $('#book_id_0').trigger("click");
        $('#book_return_id_0').trigger("click");
    }, 1000);

    $('input[name="book"]:radio').change(function () {
        //alert('changed');  
        $('.traceIdval').val($(this).data('trace-id'));
        $('.obindexval').val($(this).data('result-index'));
        $('.time_flts_arrive').html($(this).data('from-time'));
        $('.time_flts_arriveto').html($(this).data('to-time'));
        $('.time_hr_arrive').html($(this).data('duration'));
        $('.flightCode_arrive').html($(this).data('flightcode'));
        $('.currency').html($(this).data('currency'));
        $('.currency_val').html($(this).data('currency'));
        $('.arrivePrice').html($(this).data('price'));
        $('.arriveSeats').html($(this).data('seats-left'));
        $('.airimg_arrive').attr("src", 'https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=' + $(this).data('fcode'));
        var referral = 0;
        var lcc = $(this).data('lcc');
        setTimeout(function () {
            var totalPrice = parseFloat($('.returnPrice').html().replace(',', '')) + parseFloat($('.arrivePrice').html().replace(',', ''));
            $('.price_total').html(totalPrice.toFixed(2));
        }, 500);
        setTimeout(function () {

            $('.book_return_url').attr("href", '/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lcc + '/' + referral+'/'+$('#search_id').val());

            $('#shareFbLink').attr("href",'https://www.facebook.com/sharer/sharer.php?u='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lcc + '/' + referral+'/'+$('#search_id').val());

            $('#sharewspLink').attr("href",'whatsapp://send?text='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lcc + '/' + referral+'/'+$('#search_id').val());

            $('#shareTwitterLink').attr("href",'http://twitter.com/share?text='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lcc + '/' + referral+'/'+$('#search_id').val());

            $('#sharepintLink').attr("href",'"https://pinterest.com/pin/create/bookmarklet/?url='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lcc + '/' + referral+'/'+$('#search_id').val());

            $('#sharestbLink').attr("href",'https://www.stumbleupon.com/submit?url='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lcc + '/' + referral+'/'+$('#search_id').val());

            $('#sharelknLink').attr("href",'https://www.linkedin.com/shareArticle?url='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lcc + '/' + referral+'/'+$('#search_id').val());

            $('#shareinstLink').attr("href",'https://www.instagram.com/?url='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lcc + '/' + referral+'/'+$('#search_id').val());


        }, 1000);
    });

    $('input[name="book_return"]:radio').change(function () {
        //alert('changed');  
        $('.ibindexval').val($(this).data('result-index'));
        $('.time_flts_return').html($(this).data('from-time-return'));
        $('.time_flts_returnto').html($(this).data('to-time-return'));
        $('.time_hr_return').html($(this).data('duration-return'));
        $('.flightCode_return').html($(this).data('flightcode-return'));
        $('.returnCurrency').html($(this).data('currency-return'));
        $('.currency_val').html($(this).data('currency-return'));
        $('.returnPrice').html($(this).data('price-return'));
        $('.returnSeats').html($(this).data('seats-left-return'));
        $('.airimg_return').attr("src", 'https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=' + $(this).data('fcode'));
        var referralR = 0;
        var lccR = $(this).data('lcc-return');
        setTimeout(function () {
            var totalPrice = parseFloat($('.returnPrice').html().replace(',', '')) + parseFloat($('.arrivePrice').html().replace(',', ''));
            $('.price_total').html(totalPrice.toFixed(2));
        }, 500);
        setTimeout(function () {

            $('.book_return_url').attr("href", '/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lccR + '/' + referralR+'/'+$('#search_id').val());

            $('#shareFbLink').attr("href",'https://www.facebook.com/sharer/sharer.php?u='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lccR + '/' + referralR+'/'+$('#search_id').val());

            $('#sharewspLink').attr("href",'whatsapp://send?text='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lccR + '/' + referralR+'/'+$('#search_id').val());
            
            $('#shareTwitterLink').attr("href",'http://twitter.com/share?text='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lccR + '/' + referralR+'/'+$('#search_id').val());

            $('#sharepintLink').attr("href",'"https://pinterest.com/pin/create/bookmarklet/?url='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lccR + '/' + referralR+'/'+$('#search_id').val());

            $('#sharestbLink').attr("href",'https://www.stumbleupon.com/submit?url='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lccR + '/' + referralR+'/'+$('#search_id').val());

            $('#sharelknLink').attr("href",'https://www.linkedin.com/shareArticle?url='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lccR + '/' + referralR+'/'+$('#search_id').val());

            $('#shareinstLink').attr("href",'https://www.instagram.com/?url='+$('#domain').val()+'/flight/' + $('.traceIdval').val() + '/' + $('.obindexval').val() + '/' + $('.ibindexval').val() + '/' + lccR + '/' + referralR+'/'+$('#search_id').val());


        }, 1000);
    });


    /* Flight Details */

    $(".btn_activities_details").click(function () {
        $(".flight_information_data").hide();
        var id = $(this).attr('data-id');
        setTimeout(function () {
            $("#view_activity_" + id).toggle();
        }, 2000);
    });

    //$(document).off('click');
    $(document).on("click", "a.btn_flight_details", function () {

        var id = $(this).data('id');

        var ele=$(document).find("#view_flight_" + id);
        var isVisible=$(ele).css("display");

        if(isVisible=="block"){ 
           $(ele).hide(); 
        } else { 
           $(ele).show(); 
        }

    });



    $(document).on("click", "a.btn_flight_details_return", function () {
        var id = $(this).data('id');
        var ele=$(document).find("#view_flight_return_" + id);
        var isVisible=$(ele).css("display");
        
        if(isVisible=="block"){ 
          $(ele).hide(); 
        } else { 
           $(ele).show(); 
        }
    });


    $('.slide-toggler').click(function () {
        $(this).next('.slide-me').slideToggle();
        if ($(this).find('.fa').hasClass('fa-plus')) {
            $(this).find('.fa').removeClass('fa-plus');
            $(this).find('.fa').addClass('fa-minus');
        } else {
            $(this).find('.fa').removeClass('fa-minus');
            $(this).find('.fa').addClass('fa-plus');
        }
    });

    var selectRoomCount = $('#selectRoomCount').val();

    $('#pirceRange').change(function () {
        var price = $(this).val();
        $('span.range-price.max').html(price);

        $('tr.rooms-tr').each(function () {
            if ($(this).data('price') > price) {
                $(this).hide();
                $(this).removeClass('show');
            } else {
                $(this).addClass('show');
                $(this).show();
            }
        });

        if (parseInt(selectRoomCount) > 1) {

            $('tr.rooms-tr.show').each(function () {
                var category = $(this).data('category');
                var price = $(this).data('price');
                var type = $(this).data('type');

                var className = ".price-" + price + ".type-" + type + ".category-" + category;
                $(className).hide();
                $(className).first().show();
                $(className).first().find('.book-btn').html('Select ' + selectRoomCount + ' Room(s)');
                $(className).first().find('.room-price').html(parseFloat(price).toFixed(2));
            });
        }
    });

    $('#priceRangeAir').change(function () {
        var price = $(this).val();
        $('span.range-price-air.max').html(price);
        //console.log(price);
        $('.air_list_data').each(function () {
            if ($(this).data('price') > price) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    /* Time Filter */
//    var checkedTime = [];
//    $('.time_oneway').change(function () {
//        checkedTime = [];
//        $('.time_oneway').each(function () {
//
//            if ($(this).is(':checked')) {
//                checkedTime.push($(this).val());
//            }
//
//        });
//
//        $('.oneway .air_list_data').each(function () {
//            if (checkedTime.length == 0) {
//                $(this).show();
//            } else {
//                if (checkedTime.includes($(this).data('depart'))) {
//                    $(this).show();
//                } else {
//                    $(this).hide();
//                }
//            }
//        });
//    });

    /* Reset Filter Flight */

    $('.reste_all_btn').click(function () {
        $(".time_oneway").prop('checked', false);
        $(".time_return").prop('checked', false);
        $(".stop_flight_val").prop('checked', false);
        $(".stop_flight_val_return").prop('checked', false);
        $(".cabin_class_val").prop('checked', false);
        $('.air-line-type').prop('checked', true);
        $('.time_oneway').trigger("change");
        $('.time_return').trigger("change");
        $('.stop_flight_val').trigger("change");
        $('.stop_flight_val_return').trigger("change");
        $('.cabin_class_val').trigger("change");
        //$(".stop_flight_val_return").prop('checked', false); 
    });

    /* Stop Filter  */
//    var StopClassReturn = [];
//    $('.stop_flight_val').change(function () {
//        StopClassReturn = [];
//        $('.stop_flight_val').each(function () {
//
//            if ($(this).is(':checked')) {
//                StopClassReturn.push($(this).val());
//            }
//
//        });
//
//        $('.oneway .air_list_data').each(function () {
//            if (StopClassReturn.length == 0) {
//                $(this).show();
//            } else {
//                if (StopClassReturn.includes($(this).data('stops'))) {
//                    $(this).show();
//                } else {
//                    $(this).hide();
//                }
//            }
//        });
//    });

    /* Cabin Class Filter  */
    var CabinClassReturn = [];
    $('.cabin_class_val').change(function () {
        CabinClassReturn = [];
        $('.cabin_class_val').each(function () {

            if ($(this).is(':checked')) {
                CabinClassReturn.push($(this).val());
            }

        });

        if (CabinClassReturn.includes("Cabin0")) {
            CabinClassReturn = [];
        }

        $('.oneway .air_list_data').each(function () {
            if (CabinClassReturn.length == 0) {
                $(this).show();
            } else {
                if (CabinClassReturn.includes($(this).data('cabinclass'))) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });

    /* Stop Return Filter  */
//    var StopClassReturn = [];
//    $('.stop_flight_val_return').change(function () {
//        StopClassReturn = [];
//        $('.stop_flight_val_return').each(function () {
//
//            if ($(this).is(':checked')) {
//                StopClassReturn.push($(this).val());
//            }
//
//        });
//
//        $('.return .air_list_data').each(function () {
//            if (StopClassReturn.length == 0) {
//                $(this).show();
//            } else {
//                if (StopClassReturn.includes($(this).data('stops'))) {
//                    $(this).show();
//                } else {
//                    $(this).hide();
//                }
//            }
//        });
//    })


    /* Cabin Class Filter Return  */
    var CabinClassReturn = [];
    $('.cabin_class_val_return').change(function () {
        CabinClassReturn = [];
        $('.cabin_class_val_return').each(function () {

            if ($(this).is(':checked')) {
                CabinClassReturn.push($(this).val());
            }

        });

        if (CabinClassReturn.includes("Cabin0")) {
            CabinClassReturn = [];
        }

        $('.return .air_list_data').each(function () {
            if (CabinClassReturn.length == 0) {
                $(this).show();
            } else {
                if (CabinClassReturn.includes($(this).data('cabinclass'))) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });


    /* Time Filter Return */
//    var checkedTimeReturn = [];
//    $('.time_return').change(function () {
//        checkedTimeReturn = [];
//        $('.time_return').each(function () {
//
//            if ($(this).is(':checked')) {
//                checkedTimeReturn.push($(this).val());
//            }
//
//        });
//
//        $('.return .air_list_data').each(function () {
//            if (checkedTimeReturn.length == 0) {
//                $(this).show();
//            } else {
//                if (checkedTimeReturn.includes($(this).data('return'))) {
//                    $(this).show();
//                } else {
//                    $(this).hide();
//                }
//            }
//        });
//    });


    /* Filter by Aircraft type*/
//    var checkedAirType = [];
//    $('.air-line-type').change(function () {
//        checkedAirType = [];
//        $('.air-line-type').each(function () {
//            if ($(this).is(':checked')) {
//                checkedAirType.push($(this).val());
//            }
//
//        });
//
//        $('.f-list').each(function () {
//            if (checkedAirType.length == 0) {
//                $(this).show();
//            } else {
//                if (checkedAirType.includes($(this).data('air'))) {
//                    $(this).show();
//                } else {
//                    $(this).hide();
//                }
//            }
//        });
//    });

    /* Filter by Cab Type*/
    var checkedCabType = [];
    $('.cab-line-type').change(function () {
        checkedAirType = [];
        $('.cab-line-type').each(function () {
            if ($(this).is(':checked')) {
                checkedAirType.push($(this).val());
            }

        });

        $('.cab_list').each(function () {
            if (checkedAirType.length == 0) {
                $(this).show();
            } else {
                if (checkedAirType.includes($(this).data('cabtype'))) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });


    $('.show-room-description').click(function () {
        $(this).next('.room-description').slideToggle();
    });

    $('.cancel-policy').hover(function () {
        $('.cp-table').hide();
        $(this).next('.cp-table').show();
    });

    $(".cp-table").mouseleave(function () {
        $(this).hide();
    });





    var isHome = $('#isHome').val();
    if (isHome == '1') {
        $('.addRoomRow').hide();

            $('#room1').show();
        $('.childAgeSelector').hide();
    } else {

        roomCount = parseInt($('#roomCount').val());
        $('.addRoomRow').hide();
        $('.childAgeSelector').hide()
        for (var r = 1; r <= roomCount; r++) {

            $('#room' + r).show();

            //add active class
            $('#adultsCount' + r + ' li').each(function () {
                $(this).removeClass('selected');
                if ($(this).data('cy') == $('#adultCountRoom' + r).val()) {
                    $(this).addClass('selected');
                }
            });

            $('#childCount' + r + ' li').each(function () {
                $(this).removeClass('selected');
                if ($(this).data('cy') == $('#childCountRoom' + r).val()) {
                    $(this).addClass('selected');

                    if (parseInt($('#childCountRoom' + r).val()) > 1) {

                        for (var c = 1; c <= parseInt($('#childCountRoom' + r).val()); c++) {
                            $('#childAgeSelector' + c + 'Room' + r).show();
                            $('#child' + c + 'AgeRoom' + r).val($('#ca_hidden_' + c + '_' + r).val());
                        }

                    } else if (parseInt($('#childCountRoom' + r).val()) != 0) {

                        $('#childAgeSelector' + $(this).data('cy') + 'Room' + r).show();
                        $('#child' + $(this).data('cy') + 'AgeRoom' + r).val($('#ca_hidden_' + $(this).data('cy') + '_' + r).val());
                    }
                }
            });
        }



    }


    var isHomeFH = $('#isHomeFH').val();
    if (isHomeFH == '1') {
        $('#fHotelGuest .addRoomRow').hide();
        $('#roomFH1').show();
        $('#fHotelGuest .childAgeSelector').hide();
    } else {

        roomCountFH = parseInt($('#roomCountFH').val());
        $('#fHotelGuest .addRoomRow').hide();
        $('#fHotelGuest .childAgeSelector').hide()
        for (var r = 1; r <= roomCountFH; r++) {
            $('#roomFH' + r).show();

            //add active class
            $('#adultsCountFH' + r + ' li').each(function () {
                $(this).removeClass('selected');
                if ($(this).data('cy') == $('#adultCountRoomFH' + r).val()) {
                    $(this).addClass('selected');
                }
            });

            $('#childCountFH' + r + ' li').each(function () {
                $(this).removeClass('selected');
                if ($(this).data('cy') == $('#childCountRoomFH' + r).val()) {
                    $(this).addClass('selected');

                    if (parseInt($('#childCountRoomFH' + r).val()) > 1) {

                        for (var c = 1; c <= parseInt($('#childCountRoomFH' + r).val()); c++) {
                            $('#childAgeSelector' + c + 'RoomFH' + r).show();
                            $('#child' + c + 'AgeRoomFH' + r).val($('#ca_hidden_' + c + '_' + r).val());
                        }

                    } else if (parseInt($('#childCountRoomFH' + r).val()) != 0) {

                        $('#childAgeSelector' + $(this).data('cy') + 'RoomFH' + r).show();
                        $('#child' + $(this).data('cy') + 'AgeRoomFH' + r).val($('#ca_hidden_' + $(this).data('cy') + '_' + r).val());
                    }
                }
            });
        }

    }

    $('#addAnotherRoom').click(function () {
        roomCount++;
        $('#room' + roomCount).show();
        $('#roomCount').val(roomCount);
        $('#roomCount2').val(roomCount);
        // $('#adultCountRoom' + roomCount).val(1);
        if (roomCount > 5) {
            $('#addAnotherRoom').hide();
        }
    });


    $("#addAnotherRoomFH").unbind('click');

    $('#addAnotherRoomFH').click(function () {

        roomCountFH++;
        $('#roomFH' + roomCountFH).show();
        $('#roomCountFH').val(roomCountFH);
        $('#roomCountFH2').val(roomCountFH);
        if (roomCountFH > 5) {
            $('#addAnotherRoomFH').hide();
        }
    });



    $('#roomsGuests').click(function () {
        $('#resultsElement').addClass('index-class');
        $('.roomsGuests').show();
    });

    $('#roomsGuestsFH').click(function () {
        $('#resultsElement').addClass('index-class');
        $('.roomsGuests').show();
    });

    $('#cancelBtn').click(function () {
        $('.roomsGuests').hide();
        $('#resultsElement').removeClass('index-class');
    });

    $(document).on('click', '.adultsCount li', function () {
        $(this).parent().find('li').removeClass('selected');
        $(this).addClass('selected');
        var tm_room = $(this).parent().data('room');
        $('#adultCountRoom' + tm_room).val($(this).data('cy'));
        $('#adultCountRoomFH' + tm_room).val($(this).data('cy'));

    });

    // $('#addAnotherRoom').click(function () {
    //     roomCount++;
    //     $('#room' + roomCount).show();
    //     $('#roomCount').val(roomCount);
    //     $('#adultCountRoom' + roomCount).val(1);
    // });

    // if (roomCount > 5) {
    //     $('#addAnotherRoom').hide();
    // }


    // $('#roomsGuests').click(function () {
    //     $('#resultsElement').addClass('index-class');
    //     $('.roomsGuests').show();
    // });

    // $('#cancelBtn').click(function () {
    //     $('.roomsGuests').hide();
    //     $('#resultsElement').removeClass('index-class');
    // });

    // $(document).on('click', '.adultsCount li', function () {
    //     $(this).parent().find('li').removeClass('selected');
    //     $(this).addClass('selected');
    //     $('#adultCountRoom' + roomCount).val($(this).data('cy'));
    // });

    $(document).on('click', '.childCount li', function () {

        $(this).parent().find('li').removeClass('selected');
        $(this).addClass('selected');
        var tm_room = $(this).parent().data('room');
        if ($(this).data('cy') == '1') {

            $('#childAgeSelector1Room' + tm_room).show();
            $('#childAgeSelector1RoomFH' + tm_room).show();
            

            $('#childAgeSelector2Room' + tm_room).hide();
            $('#childAgeSelector2RoomFH' + tm_room).hide();
            $('#childAgeSelector3Room' + tm_room).hide();
            $('#childAgeSelector3RoomFH' + tm_room).hide();
            $('#childAgeSelector4Room' + tm_room).hide();
            $('#childAgeSelector4RoomFH' + tm_room).hide();
        }

        if ($(this).data('cy') == '2') {
            
            $('#childAgeSelector1Room' + tm_room).show();
            
            $('#childAgeSelector1RoomFH' + tm_room).show();
            
            $('#childAgeSelector2Room' + tm_room).show();
            
            $('#childAgeSelector2RoomFH' + tm_room).show();

            $('#childAgeSelector3Room' + tm_room).hide();
            $('#childAgeSelector3RoomFH' + tm_room).hide();
            $('#childAgeSelector4Room' + tm_room).hide();
            $('#childAgeSelector4RoomFH' + tm_room).hide();

        }

        if ($(this).data('cy') == '3') {

            $('#childAgeSelector1Room' + tm_room).show();            
            $('#childAgeSelector1RoomFH' + tm_room).show();

            $('#childAgeSelector2Room' + tm_room).show();            
            $('#childAgeSelector2RoomFH' + tm_room).show();
            
            $('#childAgeSelector3Room' + tm_room).show();            
            $('#childAgeSelector3RoomFH' + tm_room).show();

            $('#childAgeSelector4Room' + tm_room).hide();            
            $('#childAgeSelector4RoomFH' + tm_room).hide();

        }

        if ($(this).data('cy') == '4') {

            $('#childAgeSelector1Room' + tm_room).show();            
            $('#childAgeSelector2RoomFH' + tm_room).show();

            $('#childAgeSelector2Room' + tm_room).show();            
            $('#childAgeSelector2RoomFH' + tm_room).show();

            $('#childAgeSelector3Room' + tm_room).show();            
            $('#childAgeSelector3RoomFH' + tm_room).show();
            
            $('#childAgeSelector4Room' + tm_room).show();            
            $('#childAgeSelector4RoomFH' + tm_room).show();

        }

        if ($(this).data('cy') == '0') {
            
            $('#childAgeSelector1Room' + tm_room).hide();
            $('#childAgeSelector1RoomFH' + tm_room).hide();
            

            $('#childAgeSelector2Room' + tm_room).hide();
            $('#childAgeSelector2RoomFH' + tm_room).hide();
            $('#childAgeSelector3Room' + tm_room).hide();
            $('#childAgeSelector3RoomFH' + tm_room).hide();
            $('#childAgeSelector4Room' + tm_room).hide();
            $('#childAgeSelector4RoomFH' + tm_room).hide();
        }
        //console.log($(this).data('cy'));
        $('#childCountRoom' + tm_room).val($(this).data('cy'));
        $('#childCountRoomFH' + tm_room).val($(this).data('cy'));
    });

    // $(document).on('click', '.remove-room-btn', function () {
    //     var room = $(this).data('room');
    //     $('#room' + room).hide();
    //     roomCount--;
    //     $('#roomCount').val(roomCount);
    // });

    // $('#applyBtn').click(function () {
    //     $('.roomsGuests').hide();
    //     var guests = 0;
    //     for (var i = 1; i <= roomCount; i++) {
    //         guests = parseInt(guests + parseInt($('#adultCountRoom' + i).val()) + parseInt($('#childCountRoom' + i).val()));
    //     }
    //     $('#roomsGuests').val(roomCount + ' Rooms ' + guests + ' Guests');
    // });

    // $(document).click(function (e) {
    //     // Check if click was triggered on or within #menu_content
    //     if ($(e.target).closest(".roomsGuests").length > 0 || $(e.target).closest("#roomsGuests").length > 0 || $(e.target).closest(".remove-room-btn").length > 0) {
    //         //return false;
    //     } else {
    //         $('.roomsGuests').hide();
    //         $('#resultsElement').removeClass('index-class');
    //     }
    // });

    // $(document).on('click', '.childCount li' , function(){

    //   $(this).parent().find('li').removeClass('selected');
    //   $(this).addClass('selected');

    //   //$('#childAgeList' + roomCount).show();
    //   if($(this).data('cy') == 1) {
    //     $('#room' + roomCount + ' .childAgeSelector').hide();
    //     $('#childAgeSelector1Room' + roomCount).show();
    //   } else if($(this).data('cy')  == 2) {
    //     $('#room' + roomCount + ' .childAgeSelector').hide();
    //     $('#childAgeSelector1Room' + roomCount).show();
    //     $('#childAgeSelector2Room' + roomCount).show();
    //   } else if($(this).data('cy')  == 3) {
    //     $('#room' + roomCount + ' .childAgeSelector').hide();
    //     $('#childAgeSelector1Room' + roomCount).show();
    //     $('#childAgeSelector2Room' + roomCount).show();
    //     $('#childAgeSelector3Room' + roomCount).show();
    //   } else if($(this).data('cy')  == 4) {
    //     $('#room' + roomCount + ' .childAgeSelector').hide();
    //     $('#childAgeSelector1Room' + roomCount).show();
    //     $('#childAgeSelector2Room' + roomCount).show();
    //     $('#childAgeSelector3Room' + roomCount).show();
    //     $('#childAgeSelector4Room' + roomCount).show();
    //   } else {
    //     $('#room' + roomCount + ' .childAgeSelector').hide();
    //   }

    //   //$('#childCount').val($(this).data('cy'));
    //   $('#childCountRoom' + roomCount).val($(this).data('cy'));
    // }); 

    $(document).on('click', '.childCountCab li', function () {

        $(this).parent().find('li').removeClass('selected');
        $(this).addClass('selected');

        if ($(this).data('cy') == 1) {
            $('#room' + roomCount + ' .childAgeSelector').hide();
            $('#childAgeSelector1Cab' + roomCount).show();
        } else if ($(this).data('cy') == 2) {
            $('#room' + roomCount + ' .childAgeSelector').hide();
            $('#childAgeSelector1Cab' + roomCount).show();
            $('#childAgeSelector2Cab' + roomCount).show();
        } else if ($(this).data('cy') == 3) {
            $('#room' + roomCount + ' .childAgeSelector').hide();
            $('#childAgeSelector1Cab' + roomCount).show();
            $('#childAgeSelector2Cab' + roomCount).show();
            $('#childAgeSelector3Cab' + roomCount).show();
        } else if ($(this).data('cy') == 4) {
            $('#room' + roomCount + ' .childAgeSelector').hide();
            $('#childAgeSelector1Cab' + roomCount).show();
            $('#childAgeSelector2Cab' + roomCount).show();
            $('#childAgeSelector3Cab' + roomCount).show();
            $('#childAgeSelector4Cab' + roomCount).show();
        } else {
            $('#room' + roomCount + ' .childAgeSelector').hide();
        }

    });

    $(document).on('click', '.childCountCabAct li', function () {

        $(this).parent().find('li').removeClass('selected');
        $(this).addClass('selected');

        if ($(this).data('cy') == 1) {
            // $('#room' + roomCount + ' .childAgeSelector').hide();
            $('#childAgeSelector1CabAct' + roomCount).show();
            $('#VchildAgeSelector1CabAct1').show();
        } else if ($(this).data('cy') == 2) {
            // $('#room' + roomCount + ' .childAgeSelector').hide();
            $('#childAgeSelector1CabAct' + roomCount).show();
            $('#childAgeSelector2CabAct' + roomCount).show();
            $('#VchildAgeSelector1CabAct1').show();
            $('#VchildAgeSelector2CabAct1').show();
        } else if ($(this).data('cy') == 3) {
            // $('#room' + roomCount + ' .childAgeSelector').hide();
            $('#childAgeSelector1CabAct' + roomCount).show();
            $('#childAgeSelector2CabAct' + roomCount).show();
            $('#childAgeSelector3CabAct' + roomCount).show();
            $('#VchildAgeSelector1CabAct1').show();
            $('#VchildAgeSelector2CabAct1').show();
            $('#VchildAgeSelector3CabAct1').show();
        } else if ($(this).data('cy') == 4) {
            // $('#room' + roomCount + ' .childAgeSelector').hide();
            $('#childAgeSelector1CabAct' + roomCount).show();
            $('#childAgeSelector2CabAct' + roomCount).show();
            $('#childAgeSelector3CabAct' + roomCount).show();
            $('#childAgeSelector4CabAct' + roomCount).show();
            $('#VchildAgeSelector1CabAct1').show();
            $('#VchildAgeSelector2CabAct1').show();
            $('#VchildAgeSelector3CabAct1').show();
            $('#VchildAgeSelector4CabAct1').show();
        } else {
            //$('#room' + roomCount + ' .childAgeSelector').hide();
        }

    });



    // $('#addAnotherRoom').click(function(){
    //   var el = $('#room' + roomCount).clone();

    //   el.attr('id', 'room' + (roomCount + 1));
    //   el.find('#roomNo' + roomCount).attr('id', 'roomNo' + (roomCount + 1)).html('Room ' + (roomCount + 1));
    //   el.find('#removeRoom' + roomCount).attr('id', 'removeRoom' + (roomCount + 1)).attr('data-room', (roomCount + 1)).attr('style', 'display: block;');

    //   el.find('#adultsCount' + roomCount).attr('id', 'adultsCount' + (roomCount + 1));
    //   el.find('#adultsCount' + roomCount).attr('id', 'adultsCount' + (roomCount + 1));

    //   el.find('#childCount' + roomCount).attr('id', 'childCount' + (roomCount + 1));

    //   el.find('#childAgeSelector1Room' + roomCount).attr('id', 'childAgeSelector1Room' + (roomCount + 1));
    //   el.find('#childAgeSelector2Room' + roomCount).attr('id', 'childAgeSelector2Room' + (roomCount + 1));
    //   el.find('#childAgeSelector3Room' + roomCount).attr('id', 'childAgeSelector3Room' + (roomCount + 1));
    //   el.find('#childAgeSelector4Room' + roomCount).attr('id', 'childAgeSelector4Room' + (roomCount + 1));

    //   el.find('#child1AgeRoom' + roomCount).attr('id', 'child1AgeRoom' + (roomCount + 1)).attr('name', 'childAge' + (roomCount + 1) + '[]');
    //   el.find('#child2AgeRoom' + roomCount).attr('id', 'child2AgeRoom' + (roomCount + 1)).attr('name', 'childAge' + (roomCount + 1) + '[]');
    //   el.find('#child3AgeRoom' + roomCount).attr('id', 'child3AgeRoom' + (roomCount + 1)).attr('name', 'childAge' + (roomCount + 1) + '[]');
    //   el.find('#child4AgeRoom' + roomCount).attr('id', 'child4AgeRoom' + (roomCount + 1)).attr('name', 'childAge' + (roomCount + 1) + '[]');

    //   el.find('#adultCountRoom' + roomCount).attr('id', 'adultCountRoom' + (roomCount + 1)).attr('name', 'adultCountRoom'  + (roomCount + 1));
    //   el.find('#childCountRoom' + roomCount).attr('id', 'childCountRoom' + (roomCount + 1)).attr('name', 'childCountRoom'  + (roomCount + 1));


    //   $('#room' + roomCount).after(el);
    //   roomCount++;
    //   $('#roomCount').val(roomCount);
    //   if(roomCount > 3 ) {
    //     $('#addAnotherRoom').hide();
    //   }
    //   //console.log('append ', roomCount);
    // });



    $('.mr-auto li').each(function () {
        if ($(this).hasClass('active')) {
            let tabId = $(this).data('tab');
            $('.form_tab_data').removeClass('active');
            $('#' + tabId).addClass('active');
        }
    });


    //flight traverls
    $('#travellersClass').click(function () {
        $('.gbTravellers').show();
    });

    $('#travellersClassOne').click(function () {
        $('.travellersClassOne').show();
    });

    $('#travellersClassCabOne').click(function () {
        $('.travellersClassCabOne').show();
    });

    $('#travellersClassactOne').click(function () {
        $('.travellersClassactOne').show();
    });

    $(document).click(function (e) {
        // Check if click was triggered on or within #menu_content
        if ($(e.target).closest(".gbTravellers").length > 0 || $(e.target).closest("#travellersClass").length > 0 || $(e.target).closest(".remove-room-btn").length > 0 || $(e.target).closest(".remove-room-btn-fh").length > 0 || $(e.target).closest("#travellersClassOne").length > 0 || $(e.target).closest("#travellersClassactOne").length > 0 || $(e.target).closest("#travellersClassCabOne").length > 0 || $(e.target).closest(".travellersClassOne").length > 0) {
            //return false;
        } else {
            $('.gbTravellers').hide();
            $('.travellersClassOne').hide();
            $('.travellersClassCabOne').hide();
            $('.travellersClassactOne').hide();
        }
    });

    $('.adCF li').click(function () {
        $('.adCF li').removeClass("selected");
        $(this).addClass("selected");
        $('.adultsF').val($(this).data('cy'));
        $('.adultsFC').val($(this).data('cy'));

        $('#adultsFC').val($(this).data('cy'));

    });

    $('.adCC li').click(function () {
        $('.adCC li').removeClass("selected");
        $(this).addClass("selected");
        //$('.adultsF').val($(this).data('cy'));
        $('.adultsCC').val($(this).data('cy'));
    });

    $('.clCC li').click(function () {
        $('.clCC li').removeClass("selected");
        $(this).addClass("selected");
        //$('.adultsF').val($(this).data('cy'));
        $('.childsCC').val($(this).data('cy'));
    });

    $('.clCF li').click(function () {
        $('.clCF li').removeClass("selected");
        $(this).addClass("selected");
        //$('.adultsF').val($(this).data('cy'));
        $('.childsF').val($(this).data('cy'));
    });







});

// $(document).on('click', '.childCount li', function () {

//     $(this).parent().find('li').removeClass('selected');
//     $(this).addClass('selected');
//     var tm_room = $(this).parent().data('room');
//     if ($(this).data('cy') == '1') {
//         $('#childAgeSelector1Room' + tm_room).show();
//         $('#childAgeSelector2Room' + tm_room).hide();
//     }


//     $('#childCountRoom' + roomCount).val($(this).data('cy'));
// });

$(document).on('click', '.remove-room-btn', function () {

    var room = $(this).data('room');
    $('#room' + room).hide();
    roomCount--;
    $('#roomCount').val(roomCount);
    $('#roomCount2').val(roomCount);

    if(roomCount < 6) {
        $('#addAnotherRoom').show();
    }
});


$(".remove-room-btn-fh").unbind('click');
$(".remove-room-btn-fh").click(function() {
    var room = $(this).data('room');
    $('#roomFH' + room).hide();
    roomCountFH--;
    $('#roomCountFH').val(roomCountFH);
    $('#roomCountFH2').val(roomCountFH);
    if(roomCountFH < 6) {
        $('#addAnotherRoomFH').show();
    }

});

$('#applyBtn').click(function () {
    $('.roomsGuests').hide();
    var adults = 0;
    var childs = 0;
    var roomCount = parseInt($('#roomCount').val());
    for (var i = 1; i <= roomCount; i++) {
        adults=parseInt(adults + parseInt($('#adultCountRoom' + i).val()));
        childs=parseInt(childs + parseInt($('#childCountRoom' + i).val())); 
    }
    
    if($('#local_sel').val() == 'heb') {
        $('#roomsGuests').val($('#adults_lbl').val() + ' ' + adults + ' ,' + $('#childrens_lbl').val() + ' ' + childs);
        $('#guestRooms').html($('#rooms_lbl').val() + '&nbsp;<span style="position:absolute;">' + roomCount + '</span>');
    } else {
        $('#roomsGuests').val(adults + ' Adults ,' + childs + ' Children');
        $('#guestRooms').html(roomCount + ' ' + $('#rooms_lbl').val());
    }
});

$('#applyBtnFH').click(function () {
    $('.roomsGuests').hide();
    var guests = 0;
    var roomCountFH = parseInt($('#roomCountFH').val());
    
    var adults = 0;
    var childs = 0;
    
    for (var i = 1; i <= roomCountFH; i++) {
        guests = parseInt(guests + parseInt($('#adultCountRoomFH' + i).val()) + parseInt($('#childCountRoomFH' + i).val()));   
        
        adults=parseInt(adults + parseInt($('#adultCountRoomFH' + i).val()));
        childs=parseInt(childs + parseInt($('#childCountRoomFH' + i).val())); 
        
    }
    //$('#roomsGuestsFH').val(roomCountFH + ' Rooms ' + guests + ' Guests');
    
    if($('#local_sel').val() == 'heb') {
        $('#roomsGuestsFH').val($('#adults_lbl').val() + ' ' + adults + ' ,' + $('#childrens_lbl').val() + ' ' + childs);
        $('#guestRoomsFH').html($('#rooms_lbl').val() + '&nbsp;<span style="position:absolute;">' + roomCount + '</span>');
    } else {
        $('#roomsGuestsFH').val(adults + ' Adults ,' + childs + ' Children');
        $('#guestRoomsFH').html(roomCount + ' ' + $('#rooms_lbl').val());
    }

    
    if(guests > 9){
        $('.error_no_of_passenger').show();
        $('.flightHotelSearch').attr("disabled", true);
    }else{
        $('.error_no_of_passenger').hide();
        $('.flightHotelSearch').attr("disabled", false);
    }
});

$(document).click(function (e) {
    // Check if click was triggered on or within #menu_content
    if ($(e.target).closest(".roomsGuests").length > 0 || $(e.target).closest("#roomsGuests").length > 0 || $(e.target).closest("#roomsGuestsFH").length > 0 || $(e.target).closest(".remove-room-btn").length > 0 || $(e.target).closest(".remove-room-btn-fh").length > 0) {
        //return false;
    } else {
        $('.roomsGuests').hide();
        $('#resultsElement').removeClass('index-class');
    }
});

// $(document).on('click', '.childCount li' , function(){

//   $(this).parent().find('li').removeClass('selected');
//   $(this).addClass('selected');

//   //$('#childAgeList' + roomCount).show();
//   if($(this).data('cy') == 1) {
//     $('#room' + roomCount + ' .childAgeSelector').hide();
//     $('#childAgeSelector1Room' + roomCount).show();
//   } else if($(this).data('cy')  == 2) {
//     $('#room' + roomCount + ' .childAgeSelector').hide();
//     $('#childAgeSelector1Room' + roomCount).show();
//     $('#childAgeSelector2Room' + roomCount).show();
//   } else if($(this).data('cy')  == 3) {
//     $('#room' + roomCount + ' .childAgeSelector').hide();
//     $('#childAgeSelector1Room' + roomCount).show();
//     $('#childAgeSelector2Room' + roomCount).show();
//     $('#childAgeSelector3Room' + roomCount).show();
//   } else if($(this).data('cy')  == 4) {
//     $('#room' + roomCount + ' .childAgeSelector').hide();
//     $('#childAgeSelector1Room' + roomCount).show();
//     $('#childAgeSelector2Room' + roomCount).show();
//     $('#childAgeSelector3Room' + roomCount).show();
//     $('#childAgeSelector4Room' + roomCount).show();
//   } else {
//     $('#room' + roomCount + ' .childAgeSelector').hide();
//   }

//   //$('#childCount').val($(this).data('cy'));
//   $('#childCountRoom' + roomCount).val($(this).data('cy'));
// }); 

$(document).on('click', '.childCountCab li', function () {

    $(this).parent().find('li').removeClass('selected');
    $(this).addClass('selected');

    if ($(this).data('cy') == 1) {
        //$('#room' + roomCount + ' .childAgeSelector').hide();
        $('#childAgeSelector1Cab1').show();
        $('#childAgeSelector2Cab1').hide();
        $('#childAgeSelector3Cab1').hide();
        $('#childAgeSelector4Cab1').hide();
    } else if ($(this).data('cy') == 2) {
        //$('#room1' + ' .childAgeSelector').hide();
        $('#childAgeSelector1Cab1').show();
        $('#childAgeSelector2Cab1').show();
        $('#childAgeSelector3Cab1').hide();
        $('#childAgeSelector4Cab1').hide();
    } else if ($(this).data('cy') == 3) {
        // $('#room1' + ' .childAgeSelector').hide();
        $('#childAgeSelector1Cab1').show();
        $('#childAgeSelector2Cab1').show();
        $('#childAgeSelector3Cab1').show();
        $('#childAgeSelector4Cab1').hide();
    } else if ($(this).data('cy') == 4) {
        //$('#room1' + ' .childAgeSelector').hide();
        $('#childAgeSelector1Cab1').show();
        $('#childAgeSelector2Cab1').show();
        $('#childAgeSelector3Cab1').show();
        $('#childAgeSelector4Cab1').show();
    } else {
        //$('#room' + roomCount + ' .childAgeSelector').hide();
    }


    $('#childsFC').val($(this).data('cy'));

});

$('.clCCA li').click(function () {
    $('.clCCA li').removeClass("selected");
    $(this).addClass("selected");
    $('.childsCCA').val($(this).data('cy'));
});


$('.adCCA li').click(function () {
    $('.adCCA li').removeClass("selected");
    $(this).addClass("selected");
    $('.adultsCCA').val($(this).data('cy'));
});

$('.inCF li').click(function () {
    $('.inCF li').removeClass("selected");
    $(this).addClass("selected");
    $('.infantsF').val($(this).data('cy'));
});

$('.tcF li').click(function () {
    $('.tcF li').removeClass("selected");
    $(this).addClass("selected");
    $('.FlightCabinClass').val($(this).data('cy'));
    let classInfo = $(this).data('cy');
    if (classInfo == '1') {
        $('.class_info').html('All Cabin Classes');
    } else if (classInfo == '2') {
        $('.class_info').html('Economy Class');
    } else if (classInfo == '3') {
        $('.class_info').html('PremiumEconomy Class');
    } else if (classInfo == '4') {
        $('.class_info').html('Business Class');
    } else if (classInfo == '5') {
        $('.class_info').html('PremiumBusiness Class');
    } else if (classInfo == '6') {
        $('.class_info').html('First Class');
    }
});

$('.df li').click(function () {
    $('.df li').removeClass("selected");
    $(this).addClass("selected");
    $('.DirectFlight').val($(this).data('cy'));
});

$('.osf li').click(function () {
    $('.osf li').removeClass("selected");
    $(this).addClass("selected");
    $('.OneStopFlight').val($(this).data('cy'));
});

$('.btnApply').click(function () {
    $('.travellersClassCabOne').hide();
    $('.travellersClassactOne').hide();
    $('.travellersClassOne').hide();
    $('.travellers').hide();

    let travellers = parseInt($('.adultsF').first().val()) + parseInt($('.childsF').first().val()) + parseInt($('.infantsF').first().val());
    let travellersC = parseInt($('.adultsFC').first().val()) + parseInt($('.childsFC').first().val());
    let travellersCAB = parseInt($('#adultsFC').first().val()) + parseInt($('#childsFC').first().val());
    let travellersCA = parseInt($('.adultsCCA').first().val()) + parseInt($('.childsCCA').first().val());
    let travellersCACT = parseInt($('.adultsCC').first().val()) + parseInt($('.childsCC').first().val());
    let s = (travellers > 1) ? 's' : '';
    let sC = (travellersC > 1) ? 's' : '';
    let sCA = (travellersCA > 1) ? 's' : '';
    let sCACT = (travellersCACT > 1) ? 's' : '';
    $('#travellersClass').val(travellers + ' Traveller' + s);
    $('#travellersClassOne').val(travellers + ' Traveller' + s);
    $('#travellersClassCabOne').val(travellersCACT + ' Traveller' + sCACT);
    $('#travellersClassactOne').val(travellersCA + ' Traveller' + sCA);
});

$('.adCF li').each(function () {
    let adults = $('.adultsF').first().val();
    let adultsC = $('.adultsFC').first().val();
    if ($(this).data('cy') == parseInt(adults)) {
        $('.adCF li').removeClass('selected');
        $(this).addClass('selected');
    }

    if ($(this).data('cy') == parseInt(adultsC)) {
        $('.adCF li').removeClass('selected');
        $(this).addClass('selected');
    }

});


$(document).on('click', '.childCountCabOr li', function () {

    $(this).parent().find('li').removeClass('selected');
    $(this).addClass('selected');

    if ($(this).data('cy') == 1) {
        //$('#room' + roomCount + ' .childAgeSelector').hide();
        $('#childAgeSelector1CabOr1').show();
        $('#childAgeSelector2CabOr1').hide();
        $('#childAgeSelector3CabOr1').hide();
        $('#childAgeSelector4CabOr1').hide();
    } else if ($(this).data('cy') == 2) {
        // $('#room' + roomCount + ' .childAgeSelector').hide();
        $('#childAgeSelector1CabOr1').show();
        $('#childAgeSelector2CabOr1').show();
        $('#childAgeSelector3CabOr1').hide();
        $('#childAgeSelector4CabOr1').hide();
    } else if ($(this).data('cy') == 3) {
        //$('#room' + roomCount + ' .childAgeSelector').hide();
        $('#childAgeSelector1CabOr1').show();
        $('#childAgeSelector2CabOr1').show();
        $('#childAgeSelector3CabOr1').show();
        $('#childAgeSelector4CabOr1').hide();
    } else if ($(this).data('cy') == 4) {
        // $('#room1' + ' .childAgeSelector').hide();
        $('#childAgeSelector1CabOr1').show();
        $('#childAgeSelector2CabOr1').show();
        $('#childAgeSelector3CabOr1').show();
        $('#childAgeSelector4CabOr1').show();
    } else {
        // $('#room' + roomCount + ' .childAgeSelector').hide();
    }

});



// $('.adCC li').each(function() {
//   let adults = $('.adultsC').first().val();
//   let adultsC = $('.adultsCC').first().val();
//   if($(this).data('cy') == parseInt(adults)) {
//     $('.adCC li').removeClass('selected');
//     $(this).addClass('selected');
//   }

//   if($(this).data('cy') == parseInt(adultsC)) {
//     $('.adCC li').removeClass('selected');
//     $(this).addClass('selected');
//   }

// });

$('.slide-me.row').click(function () {
    $('#flight-details-policy').slideToggle('slow');
    if ($(this).find('i.fa').hasClass('fa-plus')) {
        $(this).find('i.fa').removeClass('fa-plus');
        $(this).find('i.fa').addClass('fa-minus');
    } else {
        $(this).find('i.fa').removeClass('fa-minus');
        $(this).find('i.fa').addClass('fa-plus');
    }
});

$('.adCCA li').each(function () {
    let adults = $('.adultsC').first().val();
    let adultsC = $('.adultsCCA').first().val();
    // if($(this).data('cy') == parseInt(adults)) {
    //   $('.adCC li').removeClass('selected');
    //   $(this).addClass('selected');
    // }

    if ($(this).data('cy') == parseInt(adultsC)) {
        $('.adCC li').removeClass('selected');
        $(this).addClass('selected');
    }

});

$('.clCCA li').each(function () {
    //let chids = $('.childsF').first().val();
    let chidsC = $('.childsCCA').first().val();

    // if($(this).data('cy') == parseInt(chids)) {
    //   $('.clCF li').removeClass('selected');
    //   $(this).addClass('selected');
    // }

    if ($(this).data('cy') == parseInt(chidsC)) {
        $('.clCF li').removeClass('selected');
        $(this).addClass('selected');
    }

});

$('.clCF li').each(function () {
    let chids = $('.childsF').first().val();
    let chidsC = $('.childsFC').first().val();

    if ($(this).data('cy') == parseInt(chids)) {
        $('.clCF li').removeClass('selected');
        $(this).addClass('selected');
    }

    if ($(this).data('cy') == parseInt(chidsC)) {
        $('.clCF li').removeClass('selected');
        $(this).addClass('selected');
    }

});

$('.clCC li').each(function () {
    let chids = $('.childsC').first().val();
    let chidsC = $('.childsFC').first().val();

    if ($(this).data('cy') == parseInt(chids)) {
        $('.clCC li').removeClass('selected');
        $(this).addClass('selected');
    }

    if ($(this).data('cy') == parseInt(chidsC)) {
        $('.clCC li').removeClass('selected');
        $(this).addClass('selected');
    }

});

$('.inCF li').each(function () {
    let infants = $('.infantsF').first().val();
    if ($(this).data('cy') == parseInt(infants)) {
        $('.inCF li').removeClass('selected');
        $(this).addClass('selected');
    }
});

$('.tcF li').each(function () {
    let cabinClass = $('.FlightCabinClass').first().val();
    if ($(this).data('cy') == parseInt(cabinClass)) {
        $('.tcF li').removeClass('selected');
        $(this).addClass('selected');
    }
});

function cardValidationFlight() {
    var valid = true;
    var name = $('#name').val();
    var email = $('.ad_email').first().val();
    if ($('#email').length) {
        email = $('#email').val();
    }

    var cardNumber = $('#card-number').val();
    var month = $('#month').val();
    var year = $('#year').val();
    var cvc = $('#cvc').val();

    $("#error-message").hide();
    $("#error-message").html("").hide();

    if (name.trim() == "") {
        valid = false;
    }
    if (email.trim() == "") {
        valid = false;
    }
    if (cardNumber.trim() == "") {
        valid = false;
    }

    if (month.trim() == "") {
        valid = false;
    }
    if (year.trim() == "") {
        valid = false;
    }
    if (cvc.trim() == "") {
        valid = false;
    }

    if (valid == false) {
        $("#error-message").show();
        $("#error-message").html("All Fields are required.").show();
    }

    let adultsCountF = $('#adultCountHidden').val();
    let childsCountF = $('#childCountHidden').val();
    let infantsCountF = $('#infantCountHidden').val();

    for (var a = 1; a <= adultsCountF; a++) {
        if ($('.afn_' + a).val() != '' && $('.afn_' + a).val() == $('.aln_' + a).val()) {
            valid = false;
            $("#error-message").show();
            $("#error-message").html("Firstname and lastname should not be same.").show();
        }
    }

    for (var c = 1; c <= childsCountF; c++) {
        if ($('.cfn_' + c).val() != '' && $('.cfn_' + c).val() == $('.cln_' + c).val()) {
            valid = false;
            $("#error-message").show();
            $("#error-message").html("Firstname and lastname should not be same.").show();
        }
    }

    for (var i = 1; i <= infantsCountF; i++) {
        if ($('.ifn_' + i).val() != '' && $('.ifn_' + i).val() == $('.iln_' + i).val()) {
            valid = false;
            $("#error-message").show();
            $("#error-message").html("Firstname and lastname should not be same.").show();
        }
    }
    return valid;
}
/* Stripe Activity */

function stripePayActivity(e) {

    if (options.amount === 0) {
        var walletAmount = $("#walletDebit").val();
        document.getElementById('razorpay_payment_id').value = "Wallet Payment:" + walletAmount;
        document.getElementById('razorpay_signature').value = "Wallet Paid";
        document.BookingForm.submit();
        return;
    }



    e.preventDefault();
    let valid = true;
    if (!$('#BookRoomForm').parsley().isValid()) {
        valid = false;
    } else {
        //$("#submit-btn").show();
        // $( "#loader" ).css("display", "block");
        valid = true;
    }

    options.prefill.name = $('#adult_passenger_first_name_').val() + ' ' + $('#adult_passenger_last_name_').val();
    options.prefill.email = $('#adult_passenger_email_').val();
    options.prefill.contact = $('#adult_phone_').val();
    var rzp = new Razorpay(options);

    //on payment failed
    rzp.on('payment.failed', function (response) {
        console.log(response.error.code);
        console.log(response.error.description);
        console.log(response.error.source);
        console.log(response.error.step);
        console.log(response.error.reason);
        console.log(response.error.metadata.order_id);
        console.log(response.error.metadata.payment_id);
        $("#error-message").show();
        $("#error-message").html(response.error.description).show();
    });


    if (valid == true) {
        rzp.open();

    }

    // else {
    //    valid = cardValidationActivity();
    // }

    //nsole.log('valid ', valid);
    // if(valid == true) {
    //     $("#submit-btn").hide();
    //     $( "#loader" ).css("display", "inline-block");
    //     Stripe.createToken({
    //         number: $('#card-number').val(),
    //         cvc: $('#cvc').val(),
    //         exp_month: $('#month').val(),
    //         exp_year: $('#year').val()
    //     }, stripeResponseHandlerActivity);

    //     //submit from callback
    //     return false;
    // }
}

function cardValidationActivity() {
    var valid = true;

    var cardNumber = $('#card-number').val();
    var month = $('#month').val();
    var year = $('#year').val();
    var cvc = $('#cvc').val();

    $("#error-message").hide();
    $("#error-message").html("").hide();

    if (cardNumber.trim() == "") {
        valid = false;
    }

    if (month.trim() == "") {
        valid = false;
    }
    if (year.trim() == "") {
        valid = false;
    }
    if (cvc.trim() == "") {
        valid = false;
    }

    if (valid == false) {
        $("#error-message").show();
        $("#error-message").html("All Fields are required.").show();
    }

    return valid;
}

//set your publishable key
function stripeResponseHandlerActivity(status, response) {
    if (response.error) {
        //enable the submit button
        $("#submit-btn").show();
        $("#loader").css("display", "none");
        //display the errors on the form
        $("#error-message").show();
        $("#error-message").html(response.error.message).show();
    } else {
        //get token id
        var token = response['id'];
        //insert the token into the form
        $("#BookActivityForm").append("<input type='hidden' name='token' value='" + token + "' />");
        //submit form to the server
        $("#BookActivityForm").submit();
    }
}



/* Stripe Ends for Activity */

/* Stripe Cab */

function stripePayCab(e) {

    if (options.amount === 0) {
        var walletAmount = $("#walletDebit").val();
        document.getElementById('razorpay_payment_id').value = "Wallet Payment:" + walletAmount;
        document.getElementById('razorpay_signature').value = "Wallet Paid";
        document.BookingForm.submit();
        return;
    }




    e.preventDefault();
    let valid = true;
    if (!$('#BookRoomForm').parsley().isValid()) {
        valid = false;
    } else {
        //$("#submit-btn").show();
        // $( "#loader" ).css("display", "block");
        valid = true;
    }

    options.prefill.name = $('#passenger_first_name').val() + ' ' + $('#passenger_last_name').val();
    options.prefill.email = $('#passenger_email').val();
    options.prefill.contact = $('#phone').val();
    var rzp = new Razorpay(options);

    //on payment failed
    rzp.on('payment.failed', function (response) {
        console.log(response.error.code);
        console.log(response.error.description);
        console.log(response.error.source);
        console.log(response.error.step);
        console.log(response.error.reason);
        console.log(response.error.metadata.order_id);
        console.log(response.error.metadata.payment_id);
        $("#error-message").show();
        $("#error-message").html(response.error.description).show();
    });
    //   $("#submit-btn").show();
    //   $( "#loader" ).css("display", "none");
    //   valid = false;
    //   $("#error-message").show();
    //   $("#error-message").html("All Fields are required. Please enter valid data in form. Make Sure You do not enter special character.").show();
    // } else {
    //    valid = cardValidationCab();
    // }

    if (valid == true) {
        rzp.open();

    }

    //nsole.log('valid ', valid);
    // if(valid == true) {
    //     $("#submit-btn").hide();
    //     $( "#loader" ).css("display", "inline-block");
    //     Stripe.createToken({
    //         number: $('#card-number').val(),
    //         cvc: $('#cvc').val(),
    //         exp_month: $('#month').val(),
    //         exp_year: $('#year').val()
    //     }, stripeResponseHandlerCab);

    //     //submit from callback
    //     return false;
    // }
}

function cardValidationCab() {
    var valid = true;

    var cardNumber = $('#card-number').val();
    var month = $('#month').val();
    var year = $('#year').val();
    var cvc = $('#cvc').val();

    $("#error-message").hide();
    $("#error-message").html("").hide();

    if (cardNumber.trim() == "") {
        valid = false;
    }

    if (month.trim() == "") {
        valid = false;
    }
    if (year.trim() == "") {
        valid = false;
    }
    if (cvc.trim() == "") {
        valid = false;
    }

    if (valid == false) {
        $("#error-message").show();
        $("#error-message").html("All Fields are required.").show();
    }

    return valid;
}

//set your publishable key
//Stripe.setPublishableKey("<?php echo env('STRIPE_PUBLISH'); ?>");

function stripeResponseHandlerCab(status, response) {
    if (response.error) {
        //enable the submit button
        $("#submit-btn").show();
        $("#loader").css("display", "none");
        //display the errors on the form
        $("#error-message").show();
        $("#error-message").html(response.error.message).show();
    } else {
        //get token id
        var token = response['id'];
        //insert the token into the form
        $("#BookCabForm").append("<input type='hidden' name='token' value='" + token + "' />");
        //submit form to the server
        $("#BookCabForm").submit();
    }
}

/* Cab Ends */

/* Stripe Flight */
function stripePayFlight(e) {

    if (options.amount === 0) {
        var walletAmount = $("#walletDebit").val();
        document.getElementById('razorpay_payment_id').value = "Wallet Payment:" + walletAmount;
        document.getElementById('razorpay_signature').value = "Wallet Paid";
        document.BookingForm.submit();
        return;
    }


    e.preventDefault();
    valid = true;
    if (!$('#BookRoomForm').parsley().isValid()) {
        //$("#submit-btn").hide();
        //$( "#loader" ).css("display", "block");
        valid = false;
    } else {
        //$("#submit-btn").show();
        // $( "#loader" ).css("display", "block");
        valid = true;
    }

    if (valid == false) {
        $("#error-message").show();
        $("#error-message").html("All Fields are required.").show();
    }

    options.prefill.name = $('#adult_first_name_1').val() + ' ' + $('#adult_last_name_1').val();
    options.prefill.email = $('#adult_email_1').val();
    options.prefill.contact = $('#adult_phone_1').val();
    var rzp = new Razorpay(options);

    //on payment failed
    rzp.on('payment.failed', function (response) {
        console.log(response.error.code);
        console.log(response.error.description);
        console.log(response.error.source);
        console.log(response.error.step);
        console.log(response.error.reason);
        console.log(response.error.metadata.order_id);
        console.log(response.error.metadata.payment_id);
        $("#error-message").show();
        $("#error-message").html(response.error.description).show();
    });

    //nsole.log('valid ', valid);
    if (valid == true) {
        rzp.open();
        // $("#submit-btn").hide();
        // $( "#loader" ).css("display", "inline-block");
        // Stripe.createToken({
        //     number: $('#card-number').val(),
        //     cvc: $('#cvc').val(),
        //     exp_month: $('#month').val(),
        //     exp_year: $('#year').val()
        // }, stripeResponseHandlerFlight);

        //submit from callback
        //return false;
    }
}

//set your publishable key
//Stripe.setPublishableKey("<?php echo env('STRIPE_PUBLISH'); ?>");

function stripeResponseHandlerFlight(status, response) {
    if (response.error) {
        //enable the submit button
        $("#submit-btn").show();
        $("#loader").css("display", "none");
        //display the errors on the form
        $("#error-message").show();
        $("#error-message").html(response.error.message).show();
    } else {
        //get token id
        var token = response['id'];
        //insert the token into the form
        $("#BookRoomForm").append("<input type='hidden' name='token' value='" + token + "' />");
        //submit form to the server
        $("#BookRoomForm").submit();
    }
}


/*
 * Hotel Room Booking Scripts
 */
$('#BookRoomForm').parsley();
$('#BookRoomForm').on('submit', function (event) {
    if (!$('#BookRoomForm').parsley().isValid()) {
        $("#loader").css("display", "none");
        $("#submit-btn").show();
        event.preventDefault();
    }
});

$('#BookRoomFlightForm').parsley();
$('#BookRoomFlightForm').on('submit', function (event) {
    if (!$('#BookRoomFlightForm').parsley().isValid()) {
        $("#loader").css("display", "none");
        $("#submit-btn").show();
        event.preventDefault();
    }
});


$('#BookRoomForm').on('submit', function (event) {
    if (!$('#BookRoomForm').parsley().isValid()) {
        $("#loader").css("display", "none");
        $("#submit-btn").show();
        event.preventDefault();
    }
});

function cardValidation() {
    var valid = true;
    var name = $('#name').val();
    var email = $('#email').val();
    var cardNumber = $('#card-number').val();
    var month = $('#month').val();
    var year = $('#year').val();
    var cvc = $('#cvc').val();

    $("#error-message").hide();
    $("#error-message").html("").hide();

    if (name.trim() == "") {
        valid = false;
    }
    if (email.trim() == "") {
        valid = false;
    }
    if (cardNumber.trim() == "") {
        valid = false;
    }

    if (month.trim() == "") {
        valid = false;
    }
    if (year.trim() == "") {
        valid = false;
    }
    if (cvc.trim() == "") {
        valid = false;
    }

    if (valid == false) {
        $("#error-message").show();
        $("#error-message").html("All Fields are required.").show();
    }

    return valid;
}
//set your publishable key
//Stripe.setPublishableKey("<?php echo env('STRIPE_PUBLISH'); ?>");

//callback to handle the response from stripe
function stripeResponseHandler(status, response) {
    if (response.error) {
        //enable the submit button
        $("#submit-btn").show();
        $("#loader").css("display", "none");
        //display the errors on the form
        $("#error-message").show();
        $("#error-message").html(response.error.message).show();
    } else {
        //get token id
        var token = response['id'];
        //insert the token into the form
        $("#BookRoomForm").append("<input type='hidden' name='token' value='" + token + "' />");
        //submit form to the server
        $("#BookRoomForm").submit();
    }
}



function stripePay(e) {

    if (options.amount === 0) {
        var walletAmount = $("#walletDebit").val();
        document.getElementById('razorpay_payment_id').value = "Wallet Payment:" + walletAmount;
        document.getElementById('razorpay_signature').value = "Wallet Paid";
        document.BookingForm.submit();
        return;
    }


    e.preventDefault();
    valid = true;
    if (!$('#BookRoomForm').parsley().isValid()) {
        //$("#submit-btn").hide();
        //$( "#loader" ).css("display", "block");
        valid = false;
    } else {
        //$("#submit-btn").show();
        // $( "#loader" ).css("display", "block");
        valid = true;
    }

    if (valid == false) {
        $("#error-message").show();
        $("#error-message").html("All Fields are required.").show();
    }

    options.prefill.name = $('#fn_0_1').val() + ' ' + $('#ln_0_1').val();
    options.prefill.email = $('#email_0_1').val();
    options.prefill.contact = $('#ph_0_1').val();
    var rzp = new Razorpay(options);

    //on payment failed
    rzp.on('payment.failed', function (response) {
        console.log(response.error.code);
        console.log(response.error.description);
        console.log(response.error.source);
        console.log(response.error.step);
        console.log(response.error.reason);
        console.log(response.error.metadata.order_id);
        console.log(response.error.metadata.payment_id);
        $("#error-message").show();
        $("#error-message").html(response.error.description).show();
    });
    // valid = cardValidation();
    if (valid == true) {
        rzp.open();
        //     $("#submit-btn").hide();
        //     $( "#loader" ).css("display", "inline-block");
        //     Stripe.createToken({
        //         number: $('#card-number').val(),
        //         cvc: $('#cvc').val(),
        //         exp_month: $('#month').val(),
        //         exp_year: $('#year').val()
        //     }, stripeResponseHandler);

        //     //submit from callback
        //     return false;
    }
}



function stripePayFlightRoom(e) {

    if (options.amount === 0) {
        var walletAmount = $("#walletDebit").val();
        document.getElementById('razorpay_payment_id').value = "Wallet Payment:" + walletAmount;
        document.getElementById('razorpay_signature').value = "Wallet Paid";
        document.BookingForm.submit();
        return;
    }


    e.preventDefault();
    valid = true;
    if (!$('#BookRoomFlightForm').parsley().isValid()) {
        //$("#submit-btn").hide();
        //$( "#loader" ).css("display", "block");
        valid = false;
    } else {
        //$("#submit-btn").show();
        // $( "#loader" ).css("display", "block");
        valid = true;
    }

    if (valid == false) {
        $("#error-message").show();
        $("#error-message").html("All Fields are required.").show();
    }

    if ($('#fn_1_1').val() == $('#ln_1_1').val()) {
        $("#error-message").show();
        $("#error-message").html("First Name and Last Name can not be same.").show();
    }

    options.prefill.name = $('#fn_1_1').val() + ' ' + $('#ln_1_1').val();
    options.prefill.email = $('#email_1_1').val();
    options.prefill.contact = $('#ph_1_1').val();
    var rzp = new Razorpay(options);

    //on payment failed
    rzp.on('payment.failed', function (response) {
        console.log(response.error.code);
        console.log(response.error.description);
        console.log(response.error.source);
        console.log(response.error.step);
        console.log(response.error.reason);
        console.log(response.error.metadata.order_id);
        console.log(response.error.metadata.payment_id);
        $("#error-message").show();
        $("#error-message").html(response.error.description).show();
    });
    // valid = cardValidation();
    if (valid == true) {
        rzp.open();

    }
}

function copyToClip(el) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($('#' + el).val()).select();
    document.execCommand("copy");
    $temp.remove();

    $('#' + el + '-tag').html("Use ctrl + v to paste the widget code.<i class='fa fa-check'></i>");
    $('#' + el + '-tag').addClass('copied');
}

function CopyToClipboard(containerid) {
  if (document.selection) {
    var range = document.body.createTextRange();
    range.moveToElementText(document.getElementById(containerid));
    range.select().createTextRange();
    document.execCommand("copy");
  } else if (window.getSelection) {
    var range = document.createRange();
    range.selectNode(document.getElementById(containerid));
    window.getSelection().addRange(range);
    document.execCommand("copy");
    alert("Text has been copied, now paste in the text-area")
  }
}

function goTo(el) {
    $('html, body').animate({
        scrollTop: $('#' + el).offset().top - 500
    }, 'slow');
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


function joinLottery(e) {

    e.preventDefault();

    valid = true;

    if (!$('#lotteryForm').parsley().isValid()) {
        valid = false;
    } else {
        valid = true;
    }

    if (valid == false) {
        $("#error-message").show();
        $("#error-message").html("All Fields are required in valid format.").show();
    }


    var lfee = $("#lottery_fee").val();
    var lcurrency = $("#lottery_currency").val();

    var options1 = {
        "key": $("#RAZOR_KEY_ID").val(), // Enter the Key ID generated from the Dashboard
        "amount": lfee * 100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
        "currency": lcurrency,
        "name": "Trip Heist",
        "description": "Lottery Participation Fee",
        "image": "https://tripheist.com/images/logo.png",
        "order_id": "", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
        "handler": function (response) {
            $("#razorpay_payment_id").val(response.razorpay_payment_id);
            $("#razorpay_signature").val(response.razorpay_signature);
            $("#lotteryForm").submit();
        },
        "prefill": {
            "name": $("#lname").val(),
            "email": $("#lemail").val(),
            "contact": $("#lphone").val()
        },
        "notes": {
            "address": $("#laddress").val()
        },
        "theme": {
            "color": "#3399cc"
        }
    };

    var rzp1 = new Razorpay(options1);

    rzp1.on('payment.failed', function (response) {
        alert(response.error.code);
        alert(response.error.description);
        alert(response.error.source);
        alert(response.error.step);
        alert(response.error.reason);
        alert(response.error.metadata.order_id);
        alert(response.error.metadata.payment_id);
    });

    if (valid == true) {
        rzp1.open();
    }
}



function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkLotteryPopup() {
    var seenPopup = getCookie("seenLotteryPopup");

    if (seenPopup != 1) {
        $("#lotaryModal").modal("show");
        setCookie("seenLotteryPopup", 1, 365);
    }
}


$(document).ready(function () {

    $("#regForLottery").change(function () {

        var lotteryFees = $(this).val();

        if ($(this).is(":checked")) {
            $("#buyLottery").val("yes");
            var bookprice = $("#BOOKING_PRICE").val();
            var totalPrice = parseFloat(bookprice) + parseInt(lotteryFees);
            $("#BOOKING_PRICE").val(totalPrice);
            $("#ORIGINAL_BOOKING_PRICE").val(totalPrice);
            
            var dueAmt=$("#BOOKING_PRICE").val() - $("#totalPaid").val();
            $(".dueAmount").html(dueAmt);
            
            options.amount = totalPrice * 100;
            var formated_number = number_format(totalPrice, true, '.', ',');

            var curncy = $("#CURRENCY_VAL").val();
            $(".final_price").html(curncy + " " + formated_number + "<br><span class='tax-included'>Includes taxes and charges</span>");
            if ($('#submit-payme-api').length){
                 $('#submit-payme-api').html('Pay '+ formated_number+' '+$('#CURRENCY_VAL').val());
            }
        } else {
            $("#buyLottery").val("no");
            var bookprice = $("#BOOKING_PRICE").val();
            var totalPrice = parseFloat(bookprice) - parseInt(lotteryFees);
            $("#BOOKING_PRICE").val(totalPrice);
            $("#ORIGINAL_BOOKING_PRICE").val(totalPrice);
            
            var dueAmt=$("#BOOKING_PRICE").val() - $("#totalPaid").val();
            $(".dueAmount").html(dueAmt);
            
            options.amount = totalPrice * 100;
            var formated_number = number_format(totalPrice, true, '.', ',');

            var curncy = $("#CURRENCY_VAL").val();
            $(".final_price").html(curncy + " " + formated_number + "<br><span class='tax-included'>Includes taxes and charges</span>");
            if ($('#submit-payme-api').length){
                 $('#submit-payme-api').html('Pay '+ formated_number+' '+$('#CURRENCY_VAL').val());
            }
        }

    });


    $("#walletAmount").change(function () {

        if ($(this).is(":checked")) {

            $("#walletPay").val("yes");
            var bookprice = $("#BOOKING_PRICE").val();
            var walletprice = $(this).val();

            var walletamount = parseFloat(walletprice);
            var bookingPrice = parseFloat(bookprice).toFixed(2);

            if (walletamount > bookingPrice) {
                walletamount = bookingPrice;
                payble = 0;
            } else {
                var payble = bookingPrice - walletamount;
            }

            payble = payble.toFixed(2);
            //console.log(walletamount);
            $("#walletDebit").val(walletamount);

            $("#BOOKING_PRICE").val(payble);
            $("#ORIGINAL_BOOKING_PRICE").val(payble);
            $('#submit-payme-api').html('Pay '+ payble+' '+$('#CURRENCY_VAL').val());
            
            var dueAmt= parseFloat(payble) - parseFloat($('#paidAmtILS').val()) || 0;
            dueAmt = dueAmt.toFixed(2);
            $(".dueAmount").html(dueAmt);
            $(".dueAmountILS").html(dueAmt);
            $('#partAmountILS').val(0);

            options.amount = payble * 100;
            var formated_number = number_format(payble, true, '.', ',');

            var curncy = $("#CURRENCY_VAL").val();
            $(".final_price").html(curncy + " " + formated_number + "<br><span class='tax-included'>Includes taxes and charges</span>");

            $(".period_payment").html($('#CURRENCY_VAL').val()+' '+formated_number);
            $(".total_payment").html($('#CURRENCY_VAL').val()+' '+formated_number);
            
        } else {

            $("#walletPay").val("no");
            var bookprice = $("#BOOKING_PRICE").val();
            var walletprice = $(this).val();
            if (bookprice == 0) {
                var amt = $("#walletDebit").val();
                var payble = parseFloat(amt);
            } else {
                var payble = parseFloat(bookprice) + parseFloat(walletprice);
            }

            payble = payble.toFixed(2);

            $("#walletDebit").val(0);

            $("#BOOKING_PRICE").val(payble);
            $("#ORIGINAL_BOOKING_PRICE").val(payble);
            

            var dueAmt=$("#BOOKING_PRICE").val() - ($("#totalPaid").val() || 0);
            dueAmt = dueAmt.toFixed(2);
            $(".dueAmount").html(dueAmt - (parseFloat($('#paidAmtILS').val()) || 0));
            $(".dueAmountILS").html(dueAmt - (parseFloat($('#paidAmtILS').val()) || 0));


            $('#submit-payme-api').html('Pay '+ dueAmt+' '+$('#CURRENCY_VAL').val());
            $('#partAmountILS').val(0);
             
            options.amount = payble * 100;
            var formated_number = number_format(payble, true, '.', ',');

            var curncy = $("#CURRENCY_VAL").val();
            $(".final_price").html(curncy + " " + formated_number + "<br><span class='tax-included'>Includes taxes and charges</span>");

            $(".period_payment").html($('#CURRENCY_VAL').val()+' '+formated_number);
            $(".total_payment").html($('#CURRENCY_VAL').val()+' '+formated_number);

        }
    });

    $(".useremail").blur(function () {
        var uemail = $(this).val();
        //if ($("#regForLottery").is(":checked")) {
        $.ajax({
            url: '/api/check-email',
            method: "POST",
            data: {email: uemail},
            dataType: "json",
            success: function (data)
            {
                if (data.success == false) {
                    if (data.hasTicket == true) {
                        $("#lotterybox").hide();
                        $("#regForLottery").prop("checked", false);
                        $("#buyLottery").val("no");
                        var bookprice = $("#BOOKING_PRICE").val();
                        var walletprice = $("#walletAmount").val();
                        var totalPrice = parseFloat(bookprice) - parseFloat(walletprice);
                        $("#BOOKING_PRICE").val(totalPrice);
                        $("#ORIGINAL_BOOKING_PRICE").val(totalPrice);
                        
                        var dueAmt=$("#BOOKING_PRICE").val() - $("#totalPaid").val();
                        $(".dueAmount").html(dueAmt);
            
                        options.amount = totalPrice * 100;
                        var formated_number = number_format(totalPrice, true, '.', ',');

                        var curncy = $("#CURRENCY_VAL").val();
                        $(".final_price").html(curncy + " " + formated_number);
                    } else {
                        $("#lotterybox").show();
                    }
                } else {
                    $("#lotterybox").show();
                }
            }
        });
        // }
    });

    if ($("#regForLottery").length) {
        $("#regForLottery").prop("checked", true);
        var lotteryFees = $("#regForLottery").val();
        $("#buyLottery").val("yes");
        var bookprice = $("#BOOKING_PRICE").val();
        var totalPrice = parseFloat(bookprice) + parseInt(lotteryFees);
        $("#BOOKING_PRICE").val(totalPrice);
        $("#ORIGINAL_BOOKING_PRICE").val(totalPrice);
        
        var dueAmt=$("#BOOKING_PRICE").val() - $("#totalPaid").val();
        $(".dueAmount").html(dueAmt);
            
        options.amount = totalPrice * 100;
        var formated_number = number_format(totalPrice, true, '.', ',');

        var curncy = $("#CURRENCY_VAL").val();
        $(".final_price").html(curncy + " " + formated_number + "<br><span class='tax-included'>Includes taxes and charges</span>");

        if ($('#submit-payme-api').length){
             $('#submit-payme-api').html('Pay '+ formated_number+' '+$('#CURRENCY_VAL').val());
        }
            
    }

});


function number_format(number, decimals, dec_point, thousands_point) {

    if (number == null || !isFinite(number)) {
        throw new TypeError("number is not valid");
    }

    if (!decimals) {
        var len = number.toString().split('.').length;
        decimals = len > 1 ? len : 0;
    }

    if (!dec_point) {
        dec_point = '.';
    }

    if (!thousands_point) {
        thousands_point = ',';
    }

    number = parseFloat(number).toFixed(decimals);

    number = number.replace(".", dec_point);

    var splitNum = number.split(dec_point);
    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
    number = splitNum.join(dec_point);

    return number;
}


 $('.lottery-info').click(function (e) {
     e.preventDefault();
     $("#lotaryModal").modal("show");
 });

 $('.search-top-city').click(function() {
    $('#searchRoomsFormMain_' + $(this).data('city')).submit();
 });

 $('.search-other-city').click(function() {
    $('#searchRoomsFormDiscover_' + $(this).data('city')).submit();
 });

  $('.search-top-hotel').click(function() {
    $('#searchRoomsFormHotel_' + $(this).data('city')).submit();
 });

$('body').on('click', '.scrollable-tabs li', function() {
    $('.scrollable-tabs li a.active').removeClass('active');
    $(this).addClass('active');
});

//$('#agent_makrup').change(function(){
$("#agent_makrup").bind('keyup mouseup', function () {

    if(!$(this).val() || $(this).val() == '0') {
        
        var bookprice = $("#ORIGINAL_BOOKING_PRICE").val();
        var totalPrice = parseFloat(bookprice) + 0;
        $("#BOOKING_PRICE").val(totalPrice);
        
        options.amount = totalPrice * 100;
        var formated_number = number_format(totalPrice, true, '.', ',');

        var curncy = $("#CURRENCY_VAL").val();
        $(".dueAmount").html(curncy + " " + formated_number);
        $(".final_price").html(curncy + " " + formated_number + "<br><span class='tax-included'>Includes taxes and charges</span>");


        if($("#extra_baggage_meal_price").val() && $("#extra_baggage_meal_price").val() != '0'){

            var priceTotal = totalPrice;

            var priceBaggage = 0;
            var priceMeal = 0;


            $('.baggageDropDown').each(function(){

                var baggagePrice = $(this).find(':selected').attr('data-price');
                var mealPrice = $(this).find(':selected').attr('data-meal-price');
                if(isNaN(mealPrice)){ mealPrice = 0; }
                var baggageCurrency = $(this).find(':selected').attr('data-currency');

                priceBaggage = priceBaggage + parseFloat(baggagePrice);
                priceMeal    = priceMeal + parseFloat(mealPrice);

                priceTotal = parseFloat(baggagePrice) + parseFloat(mealPrice) + parseFloat(priceTotal);
                pricemealbaggage = parseFloat(priceBaggage) + parseFloat(priceMeal);

            });

            $('#BOOKING_PRICE').val(priceTotal);
            $('#extra_baggage_meal_price').val(pricemealbaggage);

            $('#ORIGINAL_BOOKING_PRICE_PME').val(priceTotal);
            $('#fullAmount').val(priceTotal);
            $('#fullAmount_Install').val(priceTotal);


            options.amount = (priceTotal* 100).toFixed(2);
            
            priceBaggage = number_format(priceBaggage, true, '.', ',');
            priceMeal = number_format(priceMeal, true, '.', ',');
            priceTotal = number_format(priceTotal, true, '.', ',');
            
            $('.basic_baggage_charges').show();
            $('.basic_meal_charges').show();

            $('.baggagePrices').html(curncy + ' '+ priceBaggage );
            $('.mealPrices').html(curncy + ' '+ priceMeal );
            $(".dueAmount").html(curncy + ' '+  priceTotal);
            $('.final_price').html(curncy + ' '+  priceTotal );


            $('.btn-open-pay-form').html('Pay '+priceTotal+' '+$("#CURRENCY_VAL").val());

            $(".period_payment").html($('#CURRENCY_VAL').val()+' '+priceTotal);
            $(".total_payment").html($('#CURRENCY_VAL').val()+' '+priceTotal);
            $(".amount_interest").html($('#CURRENCY_VAL').val()+' '+0);

            $('#installments_val').val('1');
        }

        return false;
    }
    
    var bookprice = $("#ORIGINAL_BOOKING_PRICE").val();
    var totalPrice = parseFloat(bookprice) + parseFloat($(this).val());
    $("#BOOKING_PRICE").val(totalPrice);

    /* Changing Values for ILS Hotel Payments */

    $('#ORIGINAL_BOOKING_PRICE_PME').val(totalPrice);
    $('#fullAmount').val(totalPrice);
    $('#fullAmount_Install').val(totalPrice);
    $('.btn-open-pay-form').html('Pay '+totalPrice+' '+$("#CURRENCY_VAL").val());

    $(".period_payment").html($('#CURRENCY_VAL').val()+' '+totalPrice);
    $(".total_payment").html($('#CURRENCY_VAL').val()+' '+totalPrice);
    $(".amount_interest").html($('#CURRENCY_VAL').val()+' '+0);
    $(".dueAmountILS").html(totalPrice);

    $('#installments_val').val('1');

    /* Ends Here */
    
    options.amount = totalPrice * 100;
    var formated_number = number_format(totalPrice, true, '.', ',');

    var curncy = $("#CURRENCY_VAL").val();
    $(".dueAmount").html(curncy + " " + formated_number);
    $(".final_price").html(curncy + " " + formated_number + "<br><span class='tax-included'>Includes taxes and charges</span>");

    if($("#extra_baggage_meal_price").val() && $("#extra_baggage_meal_price").val() != '0'){

        var priceTotal = totalPrice;

        var priceBaggage = 0;
        var priceMeal = 0;

        $('.baggageDropDown').each(function(){

            var baggagePrice = $(this).find(':selected').attr('data-price');
            var mealPrice = $(this).find(':selected').attr('data-meal-price');
            if(isNaN(mealPrice)){ mealPrice = 0; }
            var baggageCurrency = $(this).find(':selected').attr('data-currency');

            priceBaggage = priceBaggage + parseFloat(baggagePrice);
            priceMeal    = priceMeal + parseFloat(mealPrice);
            //console.log('First', priceBaggage, priceMeal);

            priceTotal = parseFloat(baggagePrice) + parseFloat(mealPrice) + parseFloat(priceTotal);
            pricemealbaggage = parseFloat(priceBaggage) + parseFloat(priceMeal);

             //console.log('Second', priceTotal, pricemealbaggage);

        });

        $('#BOOKING_PRICE').val(priceTotal);
        $('#extra_baggage_meal_price').val(pricemealbaggage);


        $('#ORIGINAL_BOOKING_PRICE_PME').val(priceTotal);
        $('#fullAmount').val(priceTotal);
        $('#fullAmount_Install').val(priceTotal);

        //console.log('Third' , pricemealbaggage, priceTotal);

        options.amount = (priceTotal* 100).toFixed(2);
        
        priceBaggage = number_format(priceBaggage, true, '.', ',');
        priceMeal = number_format(priceMeal, true, '.', ',');
        priceTotal = number_format(priceTotal, true, '.', ',');

        //console.log('Fourth' , priceBaggage, priceMeal, priceTotal);


        //console.log(priceTotal , priceBaggage);
        
        $('.basic_baggage_charges').show();
        $('.basic_meal_charges').show();

        $('.btn-open-pay-form').html('Pay '+priceTotal+' '+$("#CURRENCY_VAL").val());

        $(".period_payment").html($('#CURRENCY_VAL').val()+' '+priceTotal);
        $(".total_payment").html($('#CURRENCY_VAL').val()+' '+priceTotal);
        $(".amount_interest").html($('#CURRENCY_VAL').val()+' '+0);

        $('#installments_val').val('1');


        $('.baggagePrices').html(curncy + ' '+ priceBaggage );
        $('.mealPrices').html(curncy + ' '+ priceMeal );
        $(".dueAmount").html(curncy + ' '+  priceTotal);
        $('.final_price').html(curncy + ' '+  priceTotal );
        $(".dueAmountILS").html(priceTotal);
    }

   
   
});

$('.toggle-password').click(function (){

    let input = $('#password');
    if (input.attr('type') == 'password') {
        input.attr('type', 'text');
        $(this).removeClass('fa-eye');
        $(this).addClass('fa-eye-slash');
    }
    else {
        input.attr('type', 'password');
        $(this).addClass('fa-eye');
        $(this).removeClass('fa-eye-slash');
    }
});

$('.toggle-cpassword').click(function (){

    let input = $('#confirm_password');
    if (input.attr('type') == 'password') {
        input.attr('type', 'text');
        $(this).removeClass('fa-eye');
        $(this).addClass('fa-eye-slash');
    }
    else {
        input.attr('type', 'password');
        $(this).addClass('fa-eye');
        $(this).removeClass('fa-eye-slash');
    }
});

setTimeout(function(){

    var owl = $('#discover-countries');
    owl.owlCarousel({
        items:4,
        loop:true,
        margin:25,
        autoplay:true,
        autoplayTimeout:4000,
        autoplayHoverPause:true,
        nav:true
    });
    $('.play').on('click',function(){
        owl.trigger('play.owl.autoplay',[1000])
    })
    $('.stop').on('click',function(){
        owl.trigger('stop.owl.autoplay')
    })


    var owl = $('#similar-hotels');
    owl.owlCarousel({
        items:4,
        loop:true,
        margin:15,
        autoplay:true,
        autoplayTimeout:4000,
        autoplayHoverPause:true,
        nav:false
    });
    // $('.play').on('click',function(){
    //     owl.trigger('play.owl.autoplay',[1000])
    // })
    // $('.stop').on('click',function(){
    //     owl.trigger('stop.owl.autoplay')
    // })
}, 500);
$(document).ready(function(){
    setTimeout(function() {
        $('.hotel-city-us').next().hide();
        $('.hotel-city-us').next().removeAttr('required');
        if($('#Latitude').length && $('#Latitude').val() !='') {
            $('#autocomplete').show();
            $('.hotel-city-us').next().hide();
        } else {
           /// $('#autocomplete').hide();
            $('.hotel-city-us').removeAttr('id');
            $('.hotel-city-us').next().show();        
        }
        if($('#isHome').length) {
            $('#autocomplete').show();
            $('.hotel-city-us').next().hide();
        }
    },1000);

    $('.change-locale').click(function() {
        setCookie("locale_changed", $(this).data('lang'), 1);
    });


    $('#submit-payme-api').click(function() {
        var fdata = {fname: $('.fn_1_1').val(), lname: $('.ln_1_1').val(), email: $(".email_1_1").val()};
        $.ajax({
            url: '/api/create-user',
            data: fdata,
            type: 'POST',
            success: function(response) {
                console.log('user is logged in', response)
            },
            error: function(error) {
                console.log('not able to login', error);
            }
        });

    });

    $('.multiCardPayILS').click(function() {
        var fdata = {fname: $('.fn_1_1').val(), lname: $('.ln_1_1').val(), email: $(".email_1_1").val()};
        $.ajax({
            url: '/api/create-user',
            data: fdata,
            type: 'POST',
            success: function(response) {
                console.log('user is logged in', response)
            },
            error: function(error) {
                console.log('not able to login', error);
            }
        });

    });
});

$('.hotel-tab').click(function() {
    $('.hotel-tab').removeClass('active');
    $(this).addClass('active');
    if($(this).data('tab') === 'us') {
        $('#autocomplete').hide();
        $('.hotel-city-us').removeAttr('id');
        $('.hotel-city-us').next().show();  
    } else {
        $('#autocomplete').show();
        $('.hotel-city-us').next().hide();     
    }
});