var app = angular.module("cabsApp", ['infinite-scroll']);

app.controller("searchCabCtrl", function($scope, $http, $timeout, $locale, $location, $rootScope, $window) {
  	$scope.cabs = [];
  	$scope.prefferedcabs = [];
  	$scope.priceRange = 50000;
  	$scope.searchData = [];
  	$scope.cabCount = 0;
  	$scope.loaded = false;
  	$scope.busy = false;
	$scope.after = '';
	$scope.traceId = '';
	$scope.currency_code = '';
	$scope.referral = '0';
	$scope.conversion = 0;
	$scope.selectedCabs = [];
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
            // $scope.searchCabs();
        };


  	$scope.searchCabs = function() {
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
	  		
		  	$http.post('/api/cabs', queryParmas)
			.then(function (returnArray) {

			  	var response = returnArray.data;
			  	if(response.status) {
			  		$scope.cabs = response.cabs;
			  		$scope.iniscomm = parseInt(response.commission_inis);		
			  		$scope.iniscomm = parseInt(response.commission_inis);		
			  		$scope.conversion = response.conversion;
			  		$scope.traceId = response.traceId;
			  		$scope.currency_code = response.input_data.currency_code;
			  		$scope.referral = response.input_data.referral;
			  		$scope.search_id = response.search_id;

			  		angular.forEach($scope.cabs,function(value,index){
				  		if($scope.prefferedcabs.indexOf(value.TransferName) == -1) {
					  		$scope.prefferedcabs.push(value.TransferName); 
					  	}
					})
					//console.log($scope.prefferedcabs);
			  		$scope.loadScript();
			  		$scope.doTransLation();
			  		$scope.startTimer();
			  	} else {
			  		$scope.cabs= [];
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

  	$scope.viewCab = function(e, cab,referral) {
  		if(!referral) {
  			referral = 0;
  		}
  		
  		if ($(e.target).closest(".email-hotel-check").length) {
  		} else {
  			$window.open('/cab/' + cab.CategoryId + '/' + cab.ResultIndex + '/' + $scope.traceId +'/'+ (referral || 0) + '/' + $scope.currency_code + '/' + $scope.search_id, '_blank');
  		}
  	}

  	$scope.toggleSelection = function (cab) {
        var idx = $scope.selectedCabs.indexOf(cab.ResultIndex);
        if (idx > -1) {
          $scope.selectedCabs.splice(idx, 1);
        }

        else {
          $scope.selectedCabs.push(cab.ResultIndex);
        }
  	};

  	$scope.sendSelectedHotels = function() {
        $scope.sendText = 'Please wait..';
        $http.post('/api/cab-send', {email: $scope.hotelEmail, data:$scope.selectedCabs, search_id: $scope.search_id})
            .then(function (response) {
                $scope.sendText = 'Send';
                $scope.selectedCabs = [];
                $scope.hotelEmail = '';
                $('.form-check-input').prop('checked', false);
                $('#cab-email-modal').modal('hide');
            }, function (error) {
                $('.form-check-input').prop('checked', false);
                $('#cab-email-modal').modal('hide');
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
