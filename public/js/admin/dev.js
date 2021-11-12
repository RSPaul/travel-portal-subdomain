$(function() {
    $('.lazy-load').Lazy();
});

$(document).ready(function(){

	$(".dob").datepicker({ 
         autoclose: true, 
         todayHighlight: true,
         endDate: new Date(),
         format: 'dd-mm-yyyy'
   });

	var owl = $('.rooms.owl-carousel');
    owl.owlCarousel({
        loop: false,
        items: 1,
        thumbs: true,
        thumbImage: true,
        thumbContainerClass: 'owl-thumbs',
        thumbItemClass: 'owl-thumb-item',
        navText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"]
    });

    var owl = $('.view-room.owl-carousel');
    owl.owlCarousel({
        loop: false,
        items: 1,
        thumbs: false,
        thumbImage: false
    });

    $('#searchRoomsForm').parsley();
    $('#searchRoomsFormFilters').parsley();
    $('#searchRooms').click(function(e) {
    	var formData = $('#searchRoomsForm').serializeArray();
    	$('#schkInDate').html(formData[4].value);
    	$('#schkOutDate').html(formData[5].value);
    	
    	var adultsCountLoader = 0; 
    	var childsCountLoader = 0; 
    	for(var i = 0; i < formData.length; i++) {
    		if(formData[i].name.indexOf('adultCountRoom') > -1) {
    			adultsCountLoader = parseInt(adultsCountLoader) + parseInt(formData[i].value);
    		}

    		if(formData[i].name.indexOf('childCountRoom') > -1) {
    			childsCountLoader = parseInt(childsCountLoader) + parseInt(formData[i].value);
    		}

    		if(formData[i].name.indexOf('roomCount') > -1) {
    			$('#sInfo').html(formData[i].value);
    		}
    	}
    	$('#sGuestInfo').html(adultsCountLoader);
    	$('#childCoundLoader').html(childsCountLoader);
    	if($('#searchRoomsForm').parsley().isValid()) {
    		$('#loadingModalRooms').modal('show');
    	}
    });

    $('#searchFlightsForm').parsley();
    $('#searchFlightsFormFilters').parsley();
	$('.searchFlights').click(function(e) {		
		
		var formData = $('#' + $(this).data('form-id')).serializeArray();
		
		$('.journy-type').hide();
		$('.journy-' + formData[1].value).show();
		$('.departDateLoader').html(formData[6].value);
		$('.arrivalDateLoader').html(formData[8].value);
		var childs = (formData[11].value != '') ? parseInt(formData[11].value) : 0;
		var infants = (formData[12].value != '') ? parseInt(formData[12].value) : 0;
		var passengers = parseInt(formData[10].value) + childs + infants;
		
		$('.passengerFlight').html(passengers);

		var icon = '<i style="font-size:18px" class="fa">&#xf061;</i>';
		if(formData[1].value == "2") {
			var icon = '<i style="font-size:24px" class="fa">&#xf07e;</i>';
		}
		var journyRoute = formData[2].value + '&nbsp;&nbsp;' + icon + '&nbsp;&nbsp;' + formData[4].value;
		$('#journyRoute').html(journyRoute)
		if($('#searchFlightsForm').parsley().isValid()) {
    		$('#loadingModalFlights').modal('show');
    	}
	});


	if($('#resultsElement').length) {		
		$('html, body').animate({
	        scrollTop: $('#resultsElement').offset().top
	    }, 'slow');
	}

	/* One Way and return showhide */
	$('.returnClass').hide();
	$('#oneWayTrip').click(function(){
		$('.returnClass').hide();
		$('#JourneyType').val('1');
	});

	$('#returnTrip').click(function(){
		$('.returnClass').show();
		$('#JourneyType').val('2');
	});

	var hiddenJourneyType = $('#hiddenJourneyType').val();
	if(hiddenJourneyType == '1') {
		$('.returnClass-2').hide();
	}
	$('.journey-type-radio').click(function() {
		if($(this).val() == '1') {
			$('.returnClass-2').hide();
		} else {
			$('.returnClass-2').show();
		}
	});

	$('.modify-search').click(function() {
		$('.modify-search-div').slideToggle();
	});
	/* Ends Here*/

	/* On Select FLights */

	setTimeout(function(){
		$('#book_id_0').trigger("click");
		$('#book_return_id_0').trigger("click");
	} , 1000);

	$( 'input[name="book"]:radio' ).change(function(){
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
        $('.airimg_arrive').attr("src", '/images/'+$(this).data('air-img')+'.gif');
        setTimeout(function(){
	        var totalPrice  = parseInt($('.returnPrice').html().replace(',','')) + parseInt($('.arrivePrice').html().replace(',',''));
	        $('.price_total').html(totalPrice);
        }, 500);
        setTimeout(function(){

        	$('.book_return_url').attr("href", '/flight/'+$('.traceIdval').val()+'/'+$('.obindexval').val()+'/'+$('.ibindexval').val());
        }, 1000);
    }); 

    $( 'input[name="book_return"]:radio' ).change(function(){
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
        $('.airimg_return').attr("src", '/images/'+$(this).data('air-img-return')+'.gif');
        setTimeout(function(){
	        var totalPrice  = parseInt($('.returnPrice').html().replace(',','')) + parseInt($('.arrivePrice').html().replace(',',''));
	        $('.price_total').html(totalPrice);
        }, 500);
        setTimeout(function(){

        	$('.book_return_url').attr("href", '/flight/'+$('.traceIdval').val()+'/'+$('.obindexval').val()+'/'+$('.ibindexval').val());
        }, 1000);
    });            


	/* Flight Details */
    $(".btn_flight_details").click(function(){
    	 var id = $(this).attr('data-id');
         $("#view_flight_"+id).toggle();
    });

     $(".btn_flight_details_return").click(function(){
    	 var id = $(this).attr('data-id');
         $("#view_flight_return_"+id).toggle();
    });


	$('.slide-toggler').click(function(){
		$(this).next('.slide-me').slideToggle();
		if($(this).find('.fa').hasClass('fa-plus')) {
			$(this).find('.fa').removeClass('fa-plus');
			$(this).find('.fa').addClass('fa-minus');
		} else {
			$(this).find('.fa').removeClass('fa-minus');
			$(this).find('.fa').addClass('fa-plus');
		}
	});

	var selectRoomCount = $('#selectRoomCount').val();

	$('#pirceRange').change(function(){
		var price = $(this).val();
		$('span.range-price.max').html(price);

		$('tr.rooms-tr').each(function() {
			if($(this).data('price') > price) {
				$(this).hide();
				$(this).removeClass('show');
			} else {
				$(this).addClass('show');
				$(this).show();
			}
		});

		if(parseInt(selectRoomCount) > 1) {

			$('tr.rooms-tr.show').each(function() {
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

	$('#priceRangeAir').change(function(){
		var price = $(this).val();
		$('span.range-price-air.max').html(price);
		//console.log(price);
		$('.air_list_data').each(function() {
			if($(this).data('price') > price) {
				$(this).hide();
			} else {
				$(this).show();
			}
		});
	});

	/* Time Filter */
	var checkedTime = [];
	$('.time_oneway').change(function () {
		checkedTime = []
	    $('.time_oneway').each(function(){

			if($(this).is(':checked')) {
				checkedTime.push($(this).val());
			}

		});

		$('.oneway .air_list_data').each(function() {
			if(checkedTime.length == 0){
				$(this).show();
			}else{
				if(checkedTime.includes($(this).data('depart'))) {
					$(this).show();
				} else {
					$(this).hide();
				}
			}
		});
	});


	/* Time Filter Return */
	var checkedTimeReturn = [];
	$('.time_return').change(function () {
		checkedTimeReturn = []
	    $('.time_return').each(function(){

			if($(this).is(':checked')) {
				checkedTimeReturn.push($(this).val());
			}

		});

		$('.return .air_list_data').each(function() {
			if(checkedTimeReturn.length == 0){
				$(this).show();
			}else{
				if(checkedTimeReturn.includes($(this).data('return'))) {
					$(this).show();
				} else {
					$(this).hide();
				}
			}
		});
	});


	/* Filter by Aircraft type*/
	var checkedAirType = [];
	$('.air-line-type').change(function () {
		checkedAirType = []
	    $('.air-line-type').each(function(){
	    	if($(this).is(':checked')) {
				checkedAirType.push($(this).val());
			}

		});

		$('.f-list').each(function() {
			if(checkedAirType.length == 0){
				$(this).show();
			}else{
				if(checkedAirType.includes($(this).data('air'))) {
					$(this).show();
				} else {
					$(this).hide();
				}
			}
		});
	});
	

	$('.show-room-description').click(function(){
		$(this).next('.room-description').slideToggle();
	});

	$('.cancel-policy').hover(function(){
		$('.cp-table').hide();
		$(this).next('.cp-table').show();
	});

	$(".cp-table").mouseleave(function(){
	    $(this).hide();
	});

	var checkedMeals = [];
	
	$('.switch .meal-checkbox').change(function() {
		checkedMeals = []
		$('.switch .meal-checkbox').each(function(){

			if($(this).is(':checked')) {
				checkedMeals.push($(this).val());
			}
		});

		$('tr.rooms-tr').each(function() {
			if(checkedMeals.includes($(this).data('meal'))) {
				$(this).show();
				$(this).addClass('show');
			} else {
				$(this).hide();
				$(this).removeClass('show');
			}
		});

		if(parseInt(selectRoomCount) > 1) {

			$('tr.rooms-tr.show').each(function() {
				var category = $(this).data('category');
				var price = $(this).data('price');
				var type = $(this).data('type');

				var className = ".price-" + price + ".type-" + type + ".category-" + category;
				$(className).hide();
				$(className).first().show();
				$(className).first().find('.book-btn').html('Select ' + selectRoomCount + ' Room(s)')
				$(className).first().find('.room-price').html(price);
			});
		}
	});	
	
	if(parseInt(selectRoomCount) > 1) {
		var tmpRoomCount = selectRoomCount;
		$('tr.rooms-tr.show').each(function() {

			var category = $(this).data('category');
			var price = $(this).data('price');
			var type = $(this).data('type');
			var index = $(this).data('index');
			var source = $(this).data('source');

			var className = ".price-" + price + ".type-" + type + ".category-" + category;
			//if(source == 'OpenCombination') {
			tmpRoomCount--;
			if(tmpRoomCount == 0) {
				var roomIndexes = '';
				$(className).each(function() {
					if(roomIndexes != '') {
						roomIndexes = roomIndexes + '-' + $(this).data('index');
					} else {
						roomIndexes = '-' + $(this).data('index');
					}
				});	
			}
			//} else {
			//	var roomIndexes = '0';
			//}
			

			$(className).hide();
			$(className).first().show();
			$(className).first().addClass('lazy-load');
			$(className).first().find('.book-btn').html('Select ' + selectRoomCount + ' Rooms');
			$(className).first().find('.room-price').html(parseFloat(price).toFixed(2));
			//tmpRoomCount--;
			if(tmpRoomCount == 0) {
				var newUrl = $(className).first().find('.book-btn').attr('href') + '/' + roomIndexes;
				$(className).first().find('.book-btn').attr('href', newUrl);
				tmpRoomCount = selectRoomCount;
			}
			//console.log($(className).first().find('.book-btn').attr('href'));
		});
	}


	var roomCount = 1;


	$("#countryInput").on('input', function () {
		var input = this;
	    var val = input.value;
	    if($('#country option').filter(function(e){
	    	if(this.value.toUpperCase() === val.toUpperCase()) {
	    		$('#selectedCurrency').val($(this)[0].dataset.currency);
	        	$('#selectedCountry').val($(this)[0].dataset.country);
	    		return this;        	
	    	}
	        
	    }).length) {
	       // $('.departDate').focus();
	    }
	});

	$("#countryInputFilters").on('input', function () {
		var input = this;
	    var val = input.value;
	    if($('#countryInputFilters option').filter(function(e){
	    	if(this.value.toUpperCase() === val.toUpperCase()) {
	    		$('#selectedCurrencyFilters').val($(this)[0].dataset.currency);
	        	$('#selectedCountryFilters').val($(this)[0].dataset.country);
	    		return this;        	
	    	}
	        
	    }).length) {
	       // $('.departDateFilters').focus();
	    }
	});

	$('.childAgeSelector').hide();

	$('#roomsGuests').click(function(){
	    $('#resultsElement').addClass('index-class');
		$('.roomsGuests').show();
	});

	$('#cancelBtn').click(function(){
		$('.roomsGuests').hide();
		$('#resultsElement').removeClass('index-class');
	});

	$(document).on('click', '.adultsCount li', function(){
		$(this).parent().find('li').removeClass('selected');
		$(this).addClass('selected');
		$('#adultCountRoom' + roomCount).val($(this).data('cy'));
	});

	$(document).on('click', '.childCount li' , function(){
		//$('.childCount li').removeClass('selected');
		$(this).parent().find('li').removeClass('selected');
		$(this).addClass('selected');

		//$('#childAgeList' + roomCount).show();
		if($(this).data('cy') == 1) {
			$('#room' + roomCount + ' .childAgeSelector').hide();
			$('#childAgeSelector1Room' + roomCount).show();
		} else if($(this).data('cy')  == 2) {
			$('#room' + roomCount + ' .childAgeSelector').hide();
			$('#childAgeSelector1Room' + roomCount).show();
			$('#childAgeSelector2Room' + roomCount).show();
		} else if($(this).data('cy')  == 3) {
			$('#room' + roomCount + ' .childAgeSelector').hide();
			$('#childAgeSelector1Room' + roomCount).show();
			$('#childAgeSelector2Room' + roomCount).show();
			$('#childAgeSelector3Room' + roomCount).show();
		} else if($(this).data('cy')  == 4) {
			$('#room' + roomCount + ' .childAgeSelector').hide();
			$('#childAgeSelector1Room' + roomCount).show();
			$('#childAgeSelector2Room' + roomCount).show();
			$('#childAgeSelector3Room' + roomCount).show();
			$('#childAgeSelector4Room' + roomCount).show();
		}

		
		//$('#childCount').val($(this).data('cy'));
		$('#childCountRoom' + roomCount).val($(this).data('cy'));
	});	

	$('#applyBtn').click(function(){
		$('.roomsGuests').hide();
		var guests = 0;
		for(var i = 1; i <= roomCount; i++) {
			guests = parseInt(guests + parseInt($('#adultCountRoom' + i).val()) + parseInt($('#childCountRoom' + i).val()));
		}
		$('#roomsGuests').val( roomCount + ' Rooms ' + guests + ' Guests');
	});

	$(document).click(function(e){
	    // Check if click was triggered on or within #menu_content
	    if( $(e.target).closest(".roomsGuests").length > 0 || $(e.target).closest("#roomsGuests").length > 0 || $(e.target).closest(".remove-room-btn").length > 0 ) {
	        //return false;
	    } else {
	    	$('.roomsGuests').hide();
	    	$('#resultsElement').removeClass('index-class');
	    }
	});

	$('#addAnotherRoom').click(function(){
		var el = $('#room' + roomCount).clone();

		el.attr('id', 'room' + (roomCount + 1));
		el.find('#roomNo' + roomCount).attr('id', 'roomNo' + (roomCount + 1)).html('Room ' + (roomCount + 1));
		el.find('#removeRoom' + roomCount).attr('id', 'removeRoom' + (roomCount + 1)).attr('data-room', (roomCount + 1)).attr('style', 'display: block;');

		el.find('#adultsCount' + roomCount).attr('id', 'adultsCount' + (roomCount + 1));
		el.find('#adultsCount' + roomCount).attr('id', 'adultsCount' + (roomCount + 1));
		
		el.find('#childCount' + roomCount).attr('id', 'childCount' + (roomCount + 1));
		
		el.find('#childAgeSelector1Room' + roomCount).attr('id', 'childAgeSelector1Room' + (roomCount + 1));
		el.find('#childAgeSelector2Room' + roomCount).attr('id', 'childAgeSelector2Room' + (roomCount + 1));
		el.find('#childAgeSelector3Room' + roomCount).attr('id', 'childAgeSelector3Room' + (roomCount + 1));
		el.find('#childAgeSelector4Room' + roomCount).attr('id', 'childAgeSelector4Room' + (roomCount + 1));

		el.find('#child1AgeRoom' + roomCount).attr('id', 'child1AgeRoom' + (roomCount + 1)).attr('name', 'childAge' + (roomCount + 1) + '[]');
		el.find('#child2AgeRoom' + roomCount).attr('id', 'child2AgeRoom' + (roomCount + 1)).attr('name', 'childAge' + (roomCount + 1) + '[]');
		el.find('#child3AgeRoom' + roomCount).attr('id', 'child3AgeRoom' + (roomCount + 1)).attr('name', 'childAge' + (roomCount + 1) + '[]');
		el.find('#child4AgeRoom' + roomCount).attr('id', 'child4AgeRoom' + (roomCount + 1)).attr('name', 'childAge' + (roomCount + 1) + '[]');

		el.find('#adultCountRoom' + roomCount).attr('id', 'adultCountRoom' + (roomCount + 1)).attr('name', 'adultCountRoom'  + (roomCount + 1));
		el.find('#childCountRoom' + roomCount).attr('id', 'childCountRoom' + (roomCount + 1)).attr('name', 'childCountRoom'  + (roomCount + 1));
		

		$('#room' + roomCount).after(el);
		roomCount++;
		$('#roomCount').val(roomCount);
		if(roomCount > 3 ) {
			$('#addAnotherRoom').hide();
		}
		//console.log('append ', roomCount);
	});

	$(document).on('click', '.remove-room-btn', function(){
		var room = $(this).data('room');
		$('#room' + room).remove();
		roomCount--;
	});

	var $star_rating = $('.star-rating .fa');

	var SetRatingStar = function() {
	  return $star_rating.each(function() {
	    if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
	      return $(this).removeClass('fa-star-o').addClass('fa-star');
	    } else {
	      return $(this).removeClass('fa-star').addClass('fa-star-o');
	    }
	  });
	};

	$star_rating.on('click', function() {
	  $star_rating.siblings('input.rating-value').val($(this).data('rating'));
	  return SetRatingStar();
	});

	SetRatingStar();

	$('#BookActivityForm').parsley();
	$('#BookActivityForm').on('submit', function(event){
          if(!$('#BookActivityForm').parsley().isValid()) {
              $( "#loader" ).css("display", "none");
              $("#submit-btn-activity").show();
              event.preventDefault();
          }
      });
});

function goTo(el) {
	$('html, body').animate({
        scrollTop: $('#' + el).offset().top - 500
    }, 'slow');
}
function cardValidationFlight () {
    var valid = true;
    var name = $('#name').val();
    if($('#email').length) {
    	var email = $('#email').val();
    } else {
    	var email = $('.ad_email').first().val();
    }
    var cardNumber = $('#card-number').val();
    var month = $('#month').val();
    var year = $('#year').val();
    var cvc = $('#cvc').val();

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

    if(valid == false) {
        $("#error-message").html("All Fields are required.").show();
    }

    let adultsCountF = $('#adultCountHidden').val();
    let childsCountF = $('#childCountHidden').val();
    let infantsCountF = $('#infantCountHidden').val();

    for (var a = 1; a <= adultsCountF; a++) {
    	if($('.afn_' + a).val() != '' && $('.afn_' + a).val() == $('.aln_' + a).val()) {
    		valid = false;
    		$("#error-message").html("Firstname and lastname should not be same.").show();
    	}
    }

    for (var c = 1; c <= childsCountF; c++) {
    	if($('.cfn_' + c).val() != '' &&  $('.cfn_' + c).val() == $('.cln_' + c).val()) {
    		valid = false;
    		$("#error-message").html("Firstname and lastname should not be same.").show();
    	}
    }

    for (var i = 1; i <= infantsCountF; i++) {
    	if($('.ifn_' + i).val() != '' &&  $('.ifn_' + i).val() == $('.iln_' + i).val()) {
    		valid = false;
    		$("#error-message").html("Firstname and lastname should not be same.").show();
    	}
    }
    return valid;
}
//set your publishable key
Stripe.setPublishableKey("<?php echo env('STRIPE_PUBLISH'); ?>");

//callback to handle the response from stripe
function stripeResponseHandlerActivity(status, response) {
    if (response.error) {
        //enable the submit button
        $("#submit-btn-activity").show();
        $( "#loader" ).css("display", "none");
        //display the errors on the form
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
function stripePayActivity(e) {
    e.preventDefault();
    valid = true;
    if(!$('#BookActivityForm').parsley().isValid()) {
     	$("#submit-btn-activity").show();
        $( "#loader" ).css("display", "none");
      	valid = false;
    }

    valid = cardValidationFlight();
    if(valid == true) {
        $("#submit-btn-activity").hide();
        $( "#loader" ).css("display", "inline-block");
        Stripe.createToken({
            number: $('#card-number').val(),
            cvc: $('#cvc').val(),
            exp_month: $('#month').val(),
            exp_year: $('#year').val()
        }, stripeResponseHandlerActivity);

        //submit from callback
        return false;
    }
}








const stackContainer = document.querySelector('.stack-container');
const cardNodes = document.querySelectorAll('.card-container');
const perspecNodes = document.querySelectorAll('.perspec');
const perspec = document.querySelector('.perspec');
const card = document.querySelector('.card');

if(stackContainer && stackContainer.length) {
	let counter = stackContainer.children.length;
}

//function to generate random number
function randomIntFromInterval(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}

if(card && card.card) {
	//after tilt animation, fire the explode animation
	card.addEventListener('animationend', function () {
	    perspecNodes.forEach(function (elem, index) {
	        elem.classList.add('explode');
	    });
	});
}

if(perspec && perspec.card) {
//after explode animation do a bunch of stuff
	perspec.addEventListener('animationend', function (e) {
	    if (e.animationName === 'explode') {
	        cardNodes.forEach(function (elem, index) {

	            //add hover animation class
	            elem.classList.add('pokeup');

	            //add event listner to throw card on click
	            elem.addEventListener('click', function () {
	                let updown = [800, -800]
	                let randomY = updown[Math.floor(Math.random() * updown.length)];
	                let randomX = Math.floor(Math.random() * 1000) - 1000;
	                elem.style.transform = `translate(${randomX}px, ${randomY}px) rotate(-540deg)`
	                elem.style.transition = "transform 1s ease, opacity 2s";
	                elem.style.opacity = "0";
	                counter--;
	                if (counter === 0) {
	                    stackContainer.style.width = "0";
	                    stackContainer.style.height = "0";
	                }
	            });

	            //generate random number of lines of code between 4 and 10 and add to each card
	            let numLines = randomIntFromInterval(5, 10);

	            //loop through the lines and add them to the DOM
	            for (let index = 0; index < numLines; index++) {
	                let lineLength = randomIntFromInterval(25, 97);
	                var node = document.createElement("li");
	                node.classList.add('node-' + index);
	                elem.querySelector('.code ul').appendChild(node).setAttribute('style', '--linelength: ' + lineLength + '%;');

	                //draw lines of code 1 by 1
	                if (index == 0) {
	                    elem.querySelector('.code ul .node-' + index).classList.add('writeLine');
	                } else {
	                    elem.querySelector('.code ul .node-' + (index - 1)).addEventListener('animationend', function (e) {
	                        elem.querySelector('.code ul .node-' + index).classList.add('writeLine');
	                    });
	                }
	            }
	        });
	    }
	});
}


if(document.getElementById("timerRooms")) {
	var endDate = new Date();
	var startdate = new Date(endDate);
	var durationInMinutes = 15;
	startdate.setMinutes(endDate.getMinutes() + durationInMinutes);
	var countDownDate = new Date(startdate).getTime();

	var x = setInterval(function() {
	  var now = new Date().getTime();
	  var distance = countDownDate - now;
	  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	    if(minutes < 5) {
	    	$('#sessionWarningModal').modal('show');
	    }
	  if (distance < 0) {
	    clearInterval(x);
	    $('#sessionWarningModal').modal('hide');
	    $('#sessionExpiredModal').modal('show');
	  }
	}, 1000);
}

if(document.getElementById("timerFlights")) {
	var endDate = new Date();
	var startdate = new Date(endDate);
	var durationInMinutes = 15;
	startdate.setMinutes(endDate.getMinutes() + durationInMinutes);
	var countDownDate = new Date(startdate).getTime();

	var x = setInterval(function() {
	  var now = new Date().getTime();
	  var distance = countDownDate - now;
	  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	    if(minutes < 5) {
	    	$('#sessionWarningModal').modal('show');
	    }
	  if (distance < 0) {
	    clearInterval(x);
	    $('#sessionWarningModal').modal('hide');
	    $('#sessionExpiredModal').modal('show');
	  }
	}, 1000);
}

if(document.getElementById("timerBook")) {
	var endDate = new Date();
	var startdate = new Date(endDate);
	var durationInMinutes = 15;
	startdate.setMinutes(endDate.getMinutes() + durationInMinutes);
	var countDownDate = new Date(startdate).getTime();

	var x = setInterval(function() {
	  var now = new Date().getTime();
	  var distance = countDownDate - now;
	  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	    if(minutes < 2) {
	    	$('#sessionExpiredModal').modal('show');
	    }
	  if (distance < 0) {
	    clearInterval(x);	    
	  }
	}, 1000);
}

if($('.fligthOrigin').length) {
	$('.fligthOrigin').autocomplete({
	    source: function (request, response) {
	    	$.ajax({
	            type: "GET",
	            url: "/api/cities/" + request.term,
	            dataType: "json",
	            success: function (data) {
	            	response( data );    
	            }                                             
	        });
	    },
	    minLength: 3,
	    select:  function (event, ui) { 
	        $('#fligthOriginCity').val(ui.item.id);
	        $('#fligthOriginCityFilters').val(ui.item.id);
	        $('.fligthDestination').val('');
	        $('.fligthDestination').focus();
	    },
	    create: function () {
	        $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
	           
	             return $('<li >')
	                .append('<div>')
	                .append('<span>')
	                .append(item.label)
	                .append('</span>')
	                .append('</div>')
	                .append('</li>')
	                .appendTo(ul); // customize your HTML
	        };
	    }
	});
}

if($('.fligthDestination').length) {
	$('.fligthDestination').autocomplete({
	    source: function (request, response) {
	    	$.ajax({
	            type: "GET",
	            url: "/api/cities/" + request.term,
	            dataType: "json",
	            success: function (data) {
	            	response( data );    
	            }                                             
	        });
	    },
	    minLength: 3,
	    select:  function (event, ui) { 
	        $('#fligthDestinationCity').val(ui.item.id);
	        $('#fligthDestinationCityFilters').val(ui.item.id);
	        $('.departDate').focus();
	    },
	    create: function () {
	        $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
	           
	             return $('<li >')
	                .append('<div>')
	                .append('<span>')
	                .append(item.label)
	                .append('</span>')
	                .append('</div>')
	                .append('</li>')
	                .appendTo(ul); // customize your HTML
	        };
	    }
});
}

if($('.fligthOriginFilters').length) {
	$('.fligthOriginFilters').autocomplete({
	    source: function (request, response) {
	    	$.ajax({
	            type: "GET",
	            url: "/api/cities/" + request.term,
	            dataType: "json",
	            success: function (data) {
	            	response( data );    
	            }                                             
	        });
	    },
	    minLength: 3,
	    select:  function (event, ui) { 
	        $('#fligthOriginCity').val(ui.item.id);
	        $('#fligthOriginCityFilters').val(ui.item.id);
	        $('.fligthDestinationFilters').val('');
	        $('.fligthDestinationFilters').focus();
	    },
	    create: function () {
	        $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
	           
	             return $('<li >')
	                .append('<div>')
	                .append('<span>')
	                .append(item.label)
	                .append('</span>')
	                .append('</div>')
	                .append('</li>')
	                .appendTo(ul); // customize your HTML
	        };
	    }
	});
}

if($('.fligthDestinationFilters').length) {
	$('.fligthDestinationFilters').autocomplete({
	    source: function (request, response) {
	    	$.ajax({
	            type: "GET",
	            url: "/api/cities/" + request.term,
	            dataType: "json",
	            success: function (data) {
	            	response( data );    
	            }                                             
	        });
	    },
	    minLength: 3,
	    select:  function (event, ui) { 
	        $('#fligthDestinationCity').val(ui.item.id);
	        $('#fligthDestinationCityFilters').val(ui.item.id);
	        $('.departDateFilters').focus();
	    },
	    create: function () {
	        $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
	           
	             return $('<li >')
	                .append('<div>')
	                .append('<span>')
	                .append(item.label)
	                .append('</span>')
	                .append('</div>')
	                .append('</li>')
	                .appendTo(ul); // customize your HTML
	        };
	    }
	});
}

document.onreadystatechange = function() { 
    if ($('.timerRooms').length && document.readyState !== "complete") { 
        var formData = $('#searchRoomsFormFilters').serializeArray();
		$('#schkInDate').html(formData[3].value);
    	$('#schkOutDate').html(formData[4].value);
    	
    	var adultsCountLoader = 0; 
    	var childsCountLoader = 0; 
    	var roomCount = 0;
    	for(var i = 0; i < formData.length; i++) {
    		if(formData[i].name.indexOf('adultCountRoom') > -1) {
    			adultsCountLoader = parseInt(adultsCountLoader) + parseInt(formData[i].value);
    			roomCount++;
    			$('#sInfo').html(roomCount);

    		}

    		if(formData[i].name.indexOf('childCountRoom') > -1) {
    			childsCountLoader = parseInt(childsCountLoader) + parseInt(formData[i].value);
    		}
    	}

    	$('#sGuestInfo').html(adultsCountLoader);
    	$('#childCoundLoader').html(childsCountLoader);
    	$('#loadingModalRooms').modal('show');

    } else { 
        $('#loadingModalRooms').modal('hide');
    }

    if ($('.timerFlights').length && document.readyState !== "complete") { 
        var formData = $('#searchFlightsFormFilters').serializeArray();
		$('.journy-type').hide();
		$('.journy-' + formData[1].value).show();
		$('.departDateLoader').html(formData[6].value);
		$('.arrivalDateLoader').html(formData[8].value);
		var childs = (formData[11].value != '') ? parseInt(formData[11].value) : 0;
		var infants = (formData[12].value != '') ? parseInt(formData[12].value) : 0;
		var passengers = parseInt(formData[10].value) + childs + infants;
		
		$('.passengerFlight').html(passengers);

		var icon = '<i style="font-size:18px" class="fa">&#xf061;</i>';
		if(formData[1].value == "2") {
			var icon = '<i style="font-size:24px" class="fa">&#xf07e;</i>';
		}
		var journyRoute = formData[2].value + '&nbsp;&nbsp;' + icon + '&nbsp;&nbsp;' + formData[4].value;
		$('#journyRoute').html(journyRoute)

		$('#loadingModalFlights').modal('show');

    } else { 
        $('#loadingModalFlights').modal('hide');
    } 
}; 


function stripePayFlight(e) {
   e.preventDefault();
   valid = true;
   if(!$('#BookFlightForm').parsley().isValid()) {
     $("#submit-btn").show();
     $( "#loader" ).css("display", "none");
     valid = false;
     $("#error-message").html("All Fields are required.").show();
   } else {
   	 valid = cardValidationFlight();
   }

  
 	//nsole.log('valid ', valid);
   if(valid == true) {
       $("#submit-btn").hide();
       $( "#loader" ).css("display", "inline-block");
       Stripe.createToken({
           number: $('#card-number').val(),
           cvc: $('#cvc').val(),
           exp_month: $('#month').val(),
           exp_year: $('#year').val()
       }, stripeResponseHandlerFlight);
 
       //submit from callback
       return false;
   }
 }

function stripeResponseHandlerFlight(status, response) {
   if (response.error) {
       //enable the submit button
       $("#submit-btn").show();
       $( "#loader" ).css("display", "none");
       //display the errors on the form
       $("#error-message").html(response.error.message).show();
   } else {
       //get token id
       var token = response['id'];
       //insert the token into the form
       $("#BookFlightForm").append("<input type='hidden' name='token' value='" + token + "' />");
       //submit form to the server
       $("#BookFlightForm").submit();
   }
 }