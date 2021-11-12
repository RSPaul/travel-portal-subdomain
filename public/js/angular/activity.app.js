var app = angular.module("activitiesApp", ['infinite-scroll']);

app.controller("searchActivityCtrl", function($scope, $http, $timeout, $locale, $location, $rootScope, $window) {
  	$scope.activities = [];
  	$scope.activitiesCheck = [];
  	$scope.prefferedcabs = [];
  	$scope.priceRange = 50000;
  	$scope.searchData = [];
  	$scope.cabCount = 0;
  	$scope.loaded = false;
  	$scope.busy = false;
	$scope.after = '';
	$scope.traceId = '';
	$scope.currency_code = '';
	$scope.availText = false;
	$scope.errorMessage = '';
	$scope.selectedActs = [];
	$scope.sendText = 'Send';
	$scope.search_id = '';

	$scope.doTransLation = function() {
		var jObj = $('.goog-te-combo');
		var db = jObj.get(0);
		var lang = getCookie("googtrans");
		if(lang && lang !== '/en/en')  {
			jObj.val(lang);
			fireEvent(db, 'change');
		}
	}


        $window.onload = function() {
            $scope.searchActivities();
        };

  	$scope.searchActivities = function() {
	  	$('.data-section').show();
        $("#loadingInProgress").modal("show");
	  	var params = $location.absUrl().split('&');
	  	var queryParmas = [];
	  	if(params.length) {
	  		params.forEach(function(value) {
	  			if(value.indexOf('cabs') == -1) {  				
	  				var parm = value.split('=');
	  				var obj = {};
	  				obj[decodeURI(parm[0])] = decodeURI(parm[1]);
	  				queryParmas.push(obj);
	  			}
	  		});
	  		
		  	$http.post('/api/activities', queryParmas)
			.then(function (returnArray) {

			  	var response = returnArray.data;
			  	if(response.status) {
			  		$scope.activities = response.activities;
			  		$scope.iniscomm = response.commission_inis;
			  		$scope.conversion = response.conversion;
			  		$scope.traceId = response.traceId;
			  		$scope.currency_code = response.input_data.currency_code;
			  		$scope.search_id = response.search_id;
			  		$scope.loadScript();
			  		$scope.doTransLation();

			  		$scope.startTimer();
			  		
			  	} else {
			  		$scope.activities= [];
			  		$scope.error = response.message;
			  	}
			  	//console.log($scope.cabs);
			  	$scope.searchData = response.input_data;
			  	$scope.loaded = true;
                $("#loadingInProgress").modal("hide");

			},function(error){
				$scope.loaded = true;
                $("#loadingInProgress").modal("hide");
			});
		}
  	}

  	$scope.checkAvailability = function(actData, index){

  		$('.availb-btn-' + index).html('<i class="fa fa-spinner"></i> Please wait..');
  		var dataNeed = {};
  		dataNeed.ResultIndex = actData.ResultIndex;
  		dataNeed.TraceId = $scope.traceId;
  		$scope.availText = true;
      dataNeed.search_id = $scope.search_id;
      dataNeed.ResultIndex = actData.ResultIndex;

  		$('.err').hide();
  		$http.post('/api/checkAvailability', dataNeed)
			.then(function (returnArray) {
			//console.log("#view_activity_"+index);	
  			//$("#view_activity_"+index).toggle();
			$scope.availText = false;
			//console.log(returnArray);
			    var response = returnArray.data;
			  	if(response.status) {
			  		$scope.activitiesCheck = response.activities;
			  		$scope.activitiesPrice = response.price_arr;
			  	} else {
			  		$scope.errorMessage = response.message;
			  		$('.error-msg-' + index).show();
			  		$('.error-msg-' + index + ' p').html($scope.errorMessage);
			  	}
			  	$('.availb-btn-' + index).html('Check Availability');

		},function(error){

			$scope.loaded = true;
			$('.availb-btn-' + index).html('Check Availability');
	});

  	}


  	$scope.loadScript = function (){

  		document.getElementById("customScript").remove();

  		var script = document.createElement("script")
	    script.type = "text/javascript";

	    if (script.readyState){  //IE
	        script.onreadystatechange = function(){
	            if (script.readyState == "loaded" || script.readyState == "complete"){
		            script.onreadystatechange = null;
		            //callback();
	            }
	        };
	    } else {  //Others
	        script.onload = function(){
	            //callback();
	        };
	    }

	    script.src = '/js/custom.js';
	    document.getElementsByTagName("head")[0].appendChild(script);

	    //$scope.doTransLation();
  	}

  	$scope.loadMoreCabs = function () {
  		if ($scope.busy) return;
    	$scope.busy = true;
  		// console.log('load more ', $scope.cabs.length);
  		$http.get('/api/cabs/more/' + $scope.cabs.length)
		.then(function (returnArray) {

		  	var response = returnArray.data;
		  	if(response.status) {
		  		for(var i = 0; i < response.cabs.length; i++) {
		  			$scope.cabs.push(response.cabs[i]);
		  		}
		  		//$scope.loadMap();

		  		$scope.doTransLation();
		  	}

		},function(error){
 	
			console.log('not able to load more cabs ', error);
		});

  	}

  	$scope.filterByPrice = function(value)  {
  		
  		$scope.noMoreLoad = false;
  		$scope.priceRange = value;
  		$('.cab_list').each(function() {
  			if($(this).data('price') > value) {
		        $(this).hide();
	      	} else {
		        $(this).show();
	      	}
  		});
  	}

  	$scope.viewAct = function(e, act,referral) {
  		//console.log('==> ', cab);
  		if(!referral) {
  			referral = 0;
  		}
  		//console.log('==> ', $scope.currency_code);
  		$window.open('/act/' + act.CategoryId + '/' + act.ResultIndex + '/' + $scope.traceId +'/'+ (referral || 0) + '/' + $scope.currency_code + '/' + $scope.search_id, '_blank');
  	}

  	$scope.toggleSelection = function (activity) {
        var idx = $scope.selectedActs.indexOf(activity.ResultIndex);
        if (idx > -1) {
          $scope.selectedActs.splice(idx, 1);
        }

        else {
          $scope.selectedActs.push(activity.ResultIndex);
        }
  	};

  	$scope.sendSelectedHotels = function() {
        $scope.sendText = 'Please wait..';
        $http.post('/api/activity-send', {email: $scope.hotelEmail, data:$scope.selectedActs, search_id: $scope.search_id})
            .then(function (response) {
                $scope.sendText = 'Send';
                $scope.selectedActs = [];
                $scope.hotelEmail = '';
                $('.form-check-input').prop('checked', false);
                $('#act-email-modal').modal('hide');
            }, function (error) {
                $('.form-check-input').prop('checked', false);
                $('#act-email-modal').modal('hide');
                $scope.sendText = 'Send';

            });
    }

    $scope.startTimer = function () {
        setTimeout(function () {
            var intervalHotelList = setInterval(function () {
                var cookieVal = parseInt($scope.search_id);
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
        }, 10000);
    }


});

app.filter('time', function() {

    var conversions = {
      'ss': angular.identity,
      'mm': function(value) { return value * 60; },
      'hh': function(value) { return value * 3600; }
    };

    var padding = function(value, length) {
      var zeroes = length - ('' + (value)).length,
          pad = '';
      while(zeroes-- > 0) pad += '0';
      return pad + value;
    };

    return function(value, unit, format, isPadded) {
      var totalSeconds = conversions[unit || 'ss'](value),
          hh = Math.floor(totalSeconds / 3600),
          mm = Math.floor((totalSeconds % 3600) / 60),
          ss = totalSeconds % 60;

      format = format || 'hh:mm:ss';
      isPadded = angular.isDefined(isPadded)? isPadded: true;
      hh = isPadded? padding(hh, 2): hh;
      mm = isPadded? padding(mm, 2): mm;
      ss = isPadded? padding(ss, 2): ss;

      return format.replace(/hh/, hh).replace(/mm/, mm).replace(/ss/, ss);
    };
  });
