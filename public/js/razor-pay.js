// Checkout details as a json
var options = {
    amount: document.getElementById('BOOKING_PRICE') ? parseFloat(document.getElementById('BOOKING_PRICE').value * 100).toFixed(2) : 0,
    currency: document.getElementById('CURRENCY_VAL') ? document.getElementById('CURRENCY_VAL').value  : "USD",
    name: document.getElementById('BOOKING_NAME') ? document.getElementById('BOOKING_NAME').value : '',
    description: document.getElementById('BOOKING_DESC') ? document.getElementById('BOOKING_DESC').value : '',
    key: document.getElementById('RAZOR_KEY_ID') ? document.getElementById('RAZOR_KEY_ID').value : '',
    image: "https://tripheist.com/images/logo.png",
    order_id: '',
    theme: {
        color: "#427ed1"
    },
    prefill: {
        name: document.getElementById('userName') ? document.getElementById('userName').value : '',
        email: document.getElementById('userEmail') ? document.getElementById('userEmail').value : '',
        contact:  "",
    },
    external: {
        wallets: ['amazonpay']
    },
    notes: {
    address:document.getElementById('useraddress') ? document.getElementById('useraddress').value : '',
    merchant_order_id: "",
    },
};


 //console.log(options);
/**
 * The entire list of Checkout fields is available at
 * https://docs.razorpay.com/docs/checkout-form#checkout-fields
 */
options.handler = function (response){
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    document.getElementById('razorpay_signature').value = response.razorpay_signature;
    $('#bookingInProgress').modal('show');
    document.BookingForm.submit();
};

// Boolean whether to show image inside a white frame. (default: true)
options.theme.image_padding = false;

options.modal = {
    ondismiss: function() {
        console.log("This code runs when the popup is closed");
    },
    // Boolean indicating whether pressing escape key 
    // should close the checkout form. (default: true)
    escape: true,
    // Boolean indicating whether clicking translucent blank
    // space outside checkout form should close the form. (default: false)
    backdropclose: false
};

