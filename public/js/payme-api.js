// HELPERS ---------------------------------------------------------------------------------
$(document).ready(function(){


var paidAmtILS = $('#paidAmtILS').val();

$('.dueAmountILS').html($('#fullAmount_Install').val() - parseFloat($('#paidAmtILS').val()));

//console.log($('#fullAmount_Install').val());

if(paidAmtILS  > 0 ){

    $('#bookingForm').hide();
    $('#ils-pay').show();
    $('#singleCardTab').hide();
    $('#singleCardTab').removeClass('active');
    $('#multiCardTab').addClass('active');
    $('#single-payment').removeClass('active');
    $('#multiple-payment').addClass('active');
    $('#multiple-payment').addClass('show');
    $('#paymentMode').val('multiple');

    var updateamount = parseFloat($('#fullAmount_Install').val()) + parseFloat($('#agentMarkup').val()); 

    updateamount = updateamount.toFixed(2);
    var dueamount = updateamount - parseFloat($('#paidAmtILS').val()); 
    dueamount      = dueamount.toFixed(2);


    $(".final_price").html($('#CURRENCY_VAL').val() + " " + updateamount + "<br><span class='tax-included'>Includes taxes and charges</span>");

    $('.dueAmountILS').html(dueamount);
}

  $(".multiCardPayILS").click(function () {

      $('#paymentMode').val('multiple');
      //var payType = $('#paymentMode').val();
      var payType = 'multiple';
      var formData = $('#BookRoomForm').serialize();
      //CHECK IF PAYING WITH WALLET
      if($('#walletAmount').is(":checked")) {

          var bookprice = $("#BOOKING_PRICE").val();
          var walletprice = $('#walletAmount').val();

          var walletamount = parseFloat(walletprice);
          var bookingPrice = parseFloat(bookprice).toFixed(2);

          if(bookingPrice <= 0){

              payble = 0;

          }else{

              payble = bookingPrice;

          }

          $("#BOOKING_PRICE").val(payble);
          //$("#ORIGINAL_BOOKING_PRICE").val(payble);
          $('#submit-payme-api').html('Pay '+ payble+' '+$('#CURRENCY_VAL').val());

          if(payble == 0) {
              $("#bookingInProgress").modal("show");
              document.BookingForm.submit();
          }else{
              //makePartPayment(payble);

              $.ajax({
                    type: "POST",
                    url: "/api/saveHotelFormData",
                    //contentType: "application/json",
                    data: formData,
                    success: function (data)
                    {
                     
                    }
                });

                var currentUrl = window.location.href;

                var payAmt = parseFloat($("#partAmountILS").val());

                var installmentVal = parseFloat(0.5 * $('#installments_val_multiple').val() );
                
                if(installmentVal == 1){

                  var updateamount = payAmt;

                }else{

                    var updateamount = payAmt + (payAmt * ( installmentVal / 100 ));

                    updateamount = parseFloat(updateamount.toFixed(2));
                }

                 var dataObj = {
                          "seller_payme_id": $('#paymeKey').val(),
                          //"seller_payme_id": "MPL15712-12169YSD-KDWKRBHQ-S86DLSXT",
                          "sale_price": (updateamount * 100),
                          "currency": "ILS",
                          "product_name": $('#BOOKING_NAME').val(),
                          "transaction_id": $('#traceIDval').val(),
                          "installments": $('#installments_val_multiple').val(),
                          "sale_callback_url": currentUrl,
                          "sale_return_url": currentUrl,
                          "capture_buyer": 0
                        };


              delete $.ajaxSettings.headers["X-CSRF-TOKEN"];

              $.ajax({
                    type: "POST",
                    //url: "https://ng.paymeservice.com/api/generate-sale",
                    url: $('#paymeUrl').val()+"/api/generate-sale",
                    contentType: "application/json",
                    crossDomain: true,
                    data: JSON.stringify(dataObj),
                    success: function (data)
                    { 
                      window.location.href = data.sale_url;
                    }
                });

          }

      } else {
          if(payType === 'single') {
              $('#partAmountILS').attr('data-parsley-required', 'false');
              payAmt = $('#partAmountILS').val();
              if (!isNaN(payAmt)) {
                $.ajax({
                    type: "POST",
                    url: "/api/saveHotelFormData",
                    //contentType: "application/json",
                    data: formData,
                    success: function (data)
                    {
                     
                    }
                });

                var currentUrl = window.location.href;


                 var dataObj = {
                          "seller_payme_id": $('#paymeKey').val(),
                          //"seller_payme_id": "MPL15712-12169YSD-KDWKRBHQ-S86DLSXT",
                          "sale_price": (payAmt * 100),
                          "currency": "ILS",
                          "product_name": $('#BOOKING_NAME').val(),
                          "transaction_id": $('#traceIDval').val(),
                          "installments": $('#installments_val').val(),
                          "sale_callback_url": currentUrl,
                          "sale_return_url": currentUrl,
                          "capture_buyer": 0
                        };


              delete $.ajaxSettings.headers["X-CSRF-TOKEN"];

              $.ajax({
                    type: "POST",
                    //url: "https://ng.paymeservice.com/api/generate-sale",
                    url: $('#paymeUrl').val()+"/api/generate-sale",
                    contentType: "application/json",
                    crossDomain: true,
                    data: JSON.stringify(dataObj),
                    success: function (data)
                    { 
                      window.location.href = data.sale_url;
                    }
                });
              }
          } else {

              $('#partAmountILS').attr('data-parsley-required', 'true');
              $("#partAmountILS").attr('data-parsley-pattern', '^[0-9]*\.[0-9]*$');
              $('#BookRoomForm').parsley();
              $('#paymentMode').val('multiple');

              $('#BookRoomForm').parsley().validate();
              //console.log("$('#BookRoomForm').parsley().isValid() ", $('#BookRoomForm').parsley().isValid());
              if ($('#BookRoomForm').parsley().isValid() || parseFloat($('#paidAmtILS').val()) > 0) {

                  var payAmt = parseFloat($("#partAmountILS").val());

                  var installmentoption = $('#installments_val_multiple').val();
                  var installmentVal = parseFloat( 0.5 * $('#installments_val_multiple').val() );
                  
                  if(installmentoption == 1){

                    var updateamount = payAmt;

                  }else{

                      var updateamount = payAmt + (payAmt * ( installmentVal / 100 ));

                      updateamount = parseFloat(updateamount.toFixed(2));
                  }


                  if (!isNaN(payAmt)) {
                       $.ajax({
                            type: "POST",
                            url: "/api/saveHotelFormData",
                            //contentType: "application/json",
                            data: formData,
                            success: function (data)
                            {
                             
                            }
                      });

                      var currentUrl = window.location.href;


                       var dataObj = {
                                "seller_payme_id": $('#paymeKey').val(),
                                //"seller_payme_id": "MPL15712-12169YSD-KDWKRBHQ-S86DLSXT",
                                "sale_price": (updateamount * 100),
                                "currency": "ILS",
                                "product_name": $('#BOOKING_NAME').val(),
                                "transaction_id": $('#traceIDval').val(),
                                "installments": $('#installments_val_multiple').val(),
                                "sale_callback_url": currentUrl,
                                "sale_return_url": currentUrl,
                                "capture_buyer": 0
                              };


                    delete $.ajaxSettings.headers["X-CSRF-TOKEN"];

                    $.ajax({
                          type: "POST",
                          //url: "https://ng.paymeservice.com/api/generate-sale",
                          url: $('#paymeUrl').val()+"/api/generate-sale",
                          contentType: "application/json",
                          crossDomain: true,
                          data: JSON.stringify(dataObj),
                          success: function (data)
                          { 
                            window.location.href = data.sale_url;
                          }
                      });
                  }
              }
          }
      }
  });

$('#submit-payme-api').click(function(){

  var formData = $('#BookRoomForm').serialize();

  if($('#walletAmount').is(":checked")) {

    var bookprice = $("#BOOKING_PRICE").val();
    var walletprice = $('#walletAmount').val();

    var walletamount = parseFloat(walletprice);
    var bookingPrice = parseFloat(bookprice).toFixed(2);

    if(bookingPrice <= 0){

        payble = 0;

    }else{

        payble = bookingPrice;

    }

    $("#BOOKING_PRICE").val(payble);
    //$("#ORIGINAL_BOOKING_PRICE").val(payble);
    $('#submit-payme-api').html('Pay '+ payble+' '+$('#CURRENCY_VAL').val());

    if(payble == 0) {

        $("#loadingInProgress").modal("show");
        document.BookingForm.submit();

    }else{

        $.ajax({
              type: "POST",
              url: "/api/saveHotelFormData",
              //contentType: "application/json",
              data: formData,
              success: function (data)
              {
               
              }
          });

        var currentUrl = window.location.href;


         var dataObj = {
                  "seller_payme_id": $('#paymeKey').val(),
                  //"seller_payme_id": "MPL15712-12169YSD-KDWKRBHQ-S86DLSXT",
                  "sale_price": (payble * 100),
                  "currency": "ILS",
                  "product_name": $('#BOOKING_NAME').val(),
                  "transaction_id": $('#traceIDval').val(),
                  "installments": $('#installments_val').val(),
                  "sale_callback_url": currentUrl,
                  "sale_return_url": currentUrl,
                  "capture_buyer": 0
                };


      delete $.ajaxSettings.headers["X-CSRF-TOKEN"];

      $.ajax({
            type: "POST",
            //url: "https://ng.paymeservice.com/api/generate-sale",
            url: $('#paymeUrl').val()+"/api/generate-sale",
            contentType: "application/json",
            crossDomain: true,
            data: JSON.stringify(dataObj),
            success: function (data)
            { 
              window.location.href = data.sale_url;
            }
        });

    }

  }else{

      $.ajax({
            type: "POST",
            url: "/api/saveHotelFormData",
            //contentType: "application/json",
            data: formData,
            success: function (data)
            {
             
            }
        });

      var currentUrl = window.location.href;


       var dataObj = {
                "seller_payme_id": $('#paymeKey').val(),
                //"seller_payme_id": "MPL15712-12169YSD-KDWKRBHQ-S86DLSXT",
                "sale_price": ($('#fullAmount_Install').val() * 100),
                "currency": "ILS",
                "product_name": $('#BOOKING_NAME').val(),
                "transaction_id": $('#traceIDval').val(),
                "installments": $('#installments_val').val(),
                "sale_callback_url": currentUrl,
                "sale_return_url": currentUrl,
                "capture_buyer": 0
              };


    delete $.ajaxSettings.headers["X-CSRF-TOKEN"];

    $.ajax({
          type: "POST",
          //url: "https://ng.paymeservice.com/api/generate-sale",
          url: $('#paymeUrl').val()+"/api/generate-sale",
          contentType: "application/json",
          crossDomain: true,
          data: JSON.stringify(dataObj),
          success: function (data)
          { 
            window.location.href = data.sale_url;
          }
      });
  }

});

$('.show-ils-pay').click(function() {
  if ($('#BookRoomForm').parsley().isValid()) {
    $('#bookingForm').hide();
    $('#ils-pay').show();
    $('#single-payment').addClass('active');
    $('#single-payment').addClass('show');
  }
});

$('#partAmountILS').keyup(function(){

  var value = parseFloat($(this).val());

  var fullAmount = parseFloat( $('#fullAmount_Install').val()) + parseFloat($('#agentMarkup').val());
  var walletprice = 0;
  if($('#walletAmount').is(":checked")) {
    var walletprice = $('#walletAmount').val();
  }

  fullAmount = fullAmount - walletprice;

  var remainingAmount = (fullAmount - (value + parseFloat($('#paidAmtILS').val()) ) ).toFixed(2);

  //$('.dueAmountILS').html(remainingAmount);

  $('#multiCardPayILS').html('Pay '+ value + ' '+ $('#CURRENCY_VAL').val());

  $(".period_payment_multiple").html($('#CURRENCY_VAL').val()+' '+value);
    $(".total_payment_multiple").html($('#CURRENCY_VAL').val()+' '+value);
    $(".amount_interest_multiple").html($('#CURRENCY_VAL').val()+' '+0);
    
});

$('#installments_val').change(function(){

  if($(this).val() == 1){

    var fullAmount = parseFloat( $('#ORIGINAL_BOOKING_PRICE_PME').val());

    if ($('#regForLottery').is(":checked")) {
         var lotteryPrice = parseFloat($('#regForLottery').val());
         fullAmount = fullAmount + lotteryPrice;
    }

    if($('#walletAmount').is(":checked")) {
      var walletprice = $('#walletAmount').val();
      fullAmount = fullAmount - walletprice;
    }


    $('#fullAmount_Install').val(fullAmount);

    $('#BOOKING_PRICE').val(fullAmount);
    //$('#ORIGINAL_BOOKING_PRICE').val(updateamount);

    $('#submit-payme-api').html('Pay '+ fullAmount+' '+$('#CURRENCY_VAL').val());

    $(".final_price").html($('#CURRENCY_VAL').val() + " " + fullAmount + "<br><span class='tax-included'>Includes taxes and charges</span>");

    $(".period_payment").html($('#CURRENCY_VAL').val()+' '+fullAmount);
    $(".total_payment").html($('#CURRENCY_VAL').val()+' '+fullAmount);
    $(".amount_interest").html($('#CURRENCY_VAL').val()+' '+0);
    $("#installment_price").val(0);
    $("#installments_number").val($(this).val());

  }else{

    var commval = parseFloat(0.5 * $(this).val());

    var fullAmount = parseFloat( $('#ORIGINAL_BOOKING_PRICE_PME').val());

    if($('#walletAmount').is(":checked")) {
      var walletprice = $('#walletAmount').val();
      fullAmount = fullAmount - walletprice;
    }
    var updateamount = fullAmount + (fullAmount * ( commval / 100 ));

    updateamount = parseFloat(updateamount.toFixed(2));

    if ($('#regForLottery').is(":checked")) {
         var lotteryPrice = parseFloat($('#regForLottery').val());
         updateamount = updateamount + lotteryPrice;
    }


    $('#fullAmount_Install').val(updateamount);

    $('#BOOKING_PRICE').val(updateamount);
    //$('#ORIGINAL_BOOKING_PRICE').val(updateamount);

    $('#submit-payme-api').html('Pay '+ updateamount+' '+$('#CURRENCY_VAL').val());

    $(".final_price").html($('#CURRENCY_VAL').val() + " " + updateamount + "<br><span class='tax-included'>Includes taxes and charges</span>");


    var period_payment = (parseFloat (updateamount / $(this).val())).toFixed(2);

    var amount_interest = (updateamount - fullAmount).toFixed(2);


    $(".period_payment").html($('#CURRENCY_VAL').val()+' '+period_payment);
    $(".total_payment").html($('#CURRENCY_VAL').val()+' '+updateamount);
    $(".amount_interest").html($('#CURRENCY_VAL').val()+' '+amount_interest);
    $("#installment_price").val(amount_interest);
    $("#installments_number").val($(this).val());

  }

});



$('#installments_val_multiple').change(function(){

  if($(this).val() == 1){

    var fullAmount = parseFloat( $('#partAmountILS').val());

    if ($('#regForLottery').is(":checked")) {
         var lotteryPrice = parseFloat($('#regForLottery').val());
         fullAmount = fullAmount + lotteryPrice;
    }

    // if($('#walletAmount').is(":checked")) {
    //   var walletprice = $('#walletAmount').val();
    //   fullAmount = fullAmount - walletprice;
    // }


    //$('#fullAmount_Install').val(fullAmount);

    //$('#BOOKING_PRICE').val(fullAmount);
    //$('#ORIGINAL_BOOKING_PRICE').val(updateamount);

    $('#multiCardPayILS').html('Pay '+ fullAmount+' '+$('#CURRENCY_VAL').val());

    //$(".final_price").html($('#CURRENCY_VAL').val() + " " + fullAmount + "<br><span class='tax-included'>Includes taxes and charges</span>");

    $(".period_payment_multiple").html($('#CURRENCY_VAL').val()+' '+fullAmount);
    $(".total_payment_multiple").html($('#CURRENCY_VAL').val()+' '+fullAmount);
    $(".amount_interest_multiple").html($('#CURRENCY_VAL').val()+' '+0);
    //$("#installment_price").val(0);
    //$("#installments_number").val($(this).val());

  }else{

    var commval = parseFloat(0.5 * $(this).val());

    var fullAmount = parseFloat( $('#partAmountILS').val());

    // if($('#walletAmount').is(":checked")) {
    //   var walletprice = $('#walletAmount').val();
    //   fullAmount = fullAmount - walletprice;
    // }
    var updateamount = fullAmount + (fullAmount * ( commval / 100 ));

    updateamount = parseFloat(updateamount.toFixed(2));

    if ($('#regForLottery').is(":checked")) {
         var lotteryPrice = parseFloat($('#regForLottery').val());
         updateamount = updateamount + lotteryPrice;
    }


    //$('#fullAmount_Install').val(updateamount);

    //$('#BOOKING_PRICE').val(updateamount);
    //$('#ORIGINAL_BOOKING_PRICE').val(updateamount);

    //$('#submit-payme-api').html('Pay '+ updateamount+' '+$('#CURRENCY_VAL').val());

    $('#multiCardPayILS').html('Pay '+ updateamount+' '+$('#CURRENCY_VAL').val());

    //$(".final_price").html($('#CURRENCY_VAL').val() + " " + updateamount + "<br><span class='tax-included'>Includes taxes and charges</span>");


    var period_payment = (parseFloat (updateamount / $(this).val())).toFixed(2);

    var amount_interest = (updateamount - fullAmount).toFixed(2);


    $(".period_payment_multiple").html($('#CURRENCY_VAL').val()+' '+period_payment);
    $(".total_payment_multiple").html($('#CURRENCY_VAL').val()+' '+updateamount);
    $(".amount_interest_multiple").html($('#CURRENCY_VAL').val()+' '+amount_interest);
    //$("#installment_price").val(amount_interest);
    //$("#installments_number").val($(this).val());

  }

});



});
