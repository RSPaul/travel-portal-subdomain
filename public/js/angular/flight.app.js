var app = angular.module("flightApp", ['infinite-scroll']);

app.controller("searchFlightCtrl", function ($scope, $http, $timeout, $locale, $location, $rootScope, $window) {
    $scope.flights = [];
    $scope.prefferedflights = [];
    $scope.airlineCodes = [];
    $scope.searchData = [];
    $scope.hotelCount = 0;
    $scope.loaded = false;
    $scope.busy = false;
    $scope.after = '';
    $scope.pageSize = 10;
    $scope.oneWayFlights = [];
    $scope.returnFlights = [];
    $scope.flights1 = [];
    $scope.flights2 = [];
    $scope.pageNo = 0;
    $scope.hasMoreData = true;
    $scope.departTime = [];
    $scope.returnTime = [];
    $scope.Stops0 = [];
    $scope.Stops1 = [];
    $scope.Airlines = [];
    $scope.errorMessage = '';
    $scope.selectedFlights = [];
    $scope.sendText = 'Send';
    $scope.search_id = '';

    $scope.doTransLation = function () {
        var jObj = $('.goog-te-combo');
        var db = jObj.get(0);
        var lang = getCookie("googtrans");
        if (lang && lang !== '/en/en') {
            jObj.val(lang);
            fireEvent(db, 'change');
        }
    }

    $window.onload = function () {
        $scope.searchFlights();
    };

    $scope.searchFlights = function () {
        $('.data-section').show();
        $("#loadingInProgress").modal("show");
        var params = $location.absUrl().split('&');
        var queryParmas = [];
        if (params.length) {
            params.forEach(function (value) {
                if (value.indexOf('flights') == -1) {
                    var parm = value.split('=');
                    var obj = {};
                    obj[decodeURI(parm[0])] = decodeURI(parm[1]);
                    queryParmas.push(obj);
                }
            });

            $scope.searchData.departDate = queryParmas[5]['departDate'];
            $scope.searchData.returnDate = queryParmas[6]['returnDate'];
            $scope.searchData.travellersClass = decodeURI(queryParmas[7]['travellersClass']).replace('%2B', ' ');

            $http.post('/api/flights', queryParmas)
                    .then(function (returnArray) {

                        var response = returnArray.data;
                        if (response.status) {
                            $scope.flights = response.flights;
                            $scope.iniscomm = response.commission_inis;
                            $scope.conversion = response.conversion;
                            $scope.search_id = response.search_id;
                            //console.log("oneway:",$scope.flights.Results[0]);
                            //console.log("return:",$scope.flights.Results[1]);
                            if ($scope.flights && $scope.flights.Results && $scope.flights.Results[0].length > 0) {

                                $scope.parseOneWay();

                                if ($scope.flights.Results[1] && $scope.flights.Results[1].length > 0) {
                                    $scope.parseReturn();
                                }

                            }
                            $scope.loadMore();
                            //$scope.loadScript();
                        } else {
                            $scope.flights = [];
                            $scope.errorMessage = response.flights;
                        }

                        $scope.loadMore();
                        $scope.searchData = response.input_data;
                        if($scope.searchData.returnDate == ''){
                            $scope.searchData.returnDateShr = 0;
                        }else{
                            $scope.searchData.returnDateShr = $scope.searchData.returnDate;
                        }
                        $scope.searchData.travellersClass = $scope.searchData.travellersClass.replace('+' , ' ');
                        $scope.loaded = true;
                        $("#loadingInProgress").modal("hide");
                        $scope.doTransLation();

                    }, function (error) {

                        $scope.loaded = true;
                        $("#loadingInProgress").modal("hide");
                        //$scope.loadMap();

                    });
        }
    }


    $scope.departFilter = (filtr) => {

        if ($scope.flights.Results[0] && $scope.flights.Results[0].length > 0) {

            $scope.flights1 = [];

            if (!$scope.departTime.includes(filtr)) {
                $scope.departTime.push(filtr);
            } else {
                const index = $scope.departTime.indexOf(filtr);
                $scope.departTime.splice(index, 1);
            }

            $scope.applyFilters();

        }

    }

    $scope.airlineFilter = (filtr) => {

        $scope.flights2 = [];
        $scope.flights1 = [];

        if (!$scope.Airlines.includes(filtr)) {
            $scope.Airlines.push(filtr);
        } else {
            const index = $scope.Airlines.indexOf(filtr);
            $scope.Airlines.splice(index, 1);
        }

        $scope.applyFilters();

    }

    $scope.returnFilter = (filtr) => {

        if ($scope.flights.Results[1] && $scope.flights.Results[1].length > 0) {

            $scope.flights2 = [];

            if (!$scope.returnTime.includes(filtr)) {
                $scope.returnTime.push(filtr);
            } else {
                const index = $scope.returnTime.indexOf(filtr);
                $scope.returnTime.splice(index, 1);
            }

            $scope.applyFilters();

        }

    }

    $scope.returnStopFilter = (filtr) => {

        if ($scope.flights.Results[1] && $scope.flights.Results[1].length > 0) {

            $scope.flights2 = [];

            if (!$scope.Stops1.includes(filtr)) {
                $scope.Stops1.push(filtr);
            } else {
                const index = $scope.Stops1.indexOf(filtr);
                $scope.Stops1.splice(index, 1);
            }

            $scope.applyFilters();
            $scope.loadScript();

        }

    }


    $scope.stopFilter = (filtr) => {

        if ($scope.flights.Results[0] && $scope.flights.Results[0].length > 0) {

            $scope.flights1 = [];

            if (!$scope.Stops0.includes(filtr)) {
                $scope.Stops0.push(filtr);
            } else {
                const index = $scope.Stops0.indexOf(filtr);
                $scope.Stops0.splice(index, 1);
            }

            $scope.applyFilters();

            $scope.loadScript();

        }

    }



    $scope.resetPaging = () => {
        $scope.pageNo = 0;
        $scope.loadMore();
    }

    $scope.resetFilters=()=>{

        $(".time_oneway").prop('checked', false);
        $(".time_return").prop('checked', false);
        $(".stop_flight_val").prop('checked', false);
        $(".stop_flight_val_return").prop('checked', false);
        $('.air-line-type').prop('checked', false);
        
        $scope.flights1 = [];
        $scope.flights2 = [];
        $scope.pageNo = 0;

        $scope.returnTime=[];
        $scope.departTime=[];
        $scope.Stops0=[];
        $scope.Stops1=[];
        $scope.Airlines=[];
        $scope.applyFilters();
    }

    $scope.applyFilters = () => {

        $scope.oneWayFlights = [];
        $scope.returnFlights = [];
        
        $scope.parseOneWay();
        $scope.parseReturn();
        
        console.log("departTime:",$scope.departTime);
        
        if ($scope.departTime.length) {
            
            var data = $scope.oneWayFlights.filter((n) => {
                return $scope.departTime.includes(n.time);
            });

            $scope.oneWayFlights = data;
        }

        console.log("returnTime:",$scope.returnTime);

        if ($scope.returnTime.length) {
            
            var rdata = $scope.returnFlights.filter((n) => {
                return $scope.returnTime.includes(n.time);
            });

            $scope.returnFlights = rdata;

        }

        console.log("Stops0:",$scope.Stops0);

        if ($scope.Stops0.length) {

            var data = $scope.oneWayFlights.filter((n) => {
                return $scope.Stops0.includes(n.stops);
            });

            $scope.oneWayFlights = data;

        }

        console.log("Stops1:",$scope.Stops1);

        if ($scope.Stops1.length) {

            var rdata = $scope.returnFlights.filter((n) => {
                return $scope.Stops1.includes(n.stops);
            });

            $scope.returnFlights = rdata;

        }

        console.log("alirlines:",$scope.Airlines);
        
        if ($scope.Airlines.length) {

             $scope.oneWayFlights = $scope.oneWayFlights.filter((n) => {
                return $scope.Airlines.includes(n.air);
            });

             $scope.returnFlights = $scope.returnFlights.filter((n) => {
                return $scope.Airlines.includes(n.air);
            });

        }
        
        console.log("flights:",$scope.oneWayFlights,$scope.returnFlights);
        
        $scope.resetPaging();
    }


//    $(document).on('change', '.time_oneway', function () {
//        $scope.loadMore();
//        var depTime = $(this).val();
//        //console.log("hello1");
//        if ($scope.flights && $scope.flights.Results && $scope.flights.Results[0].length > 0) {
//            //console.log("hello2");
//            if ($(this).is(':checked')) {
//                departTime.push(depTime);
//            } else {
//                const index = departTime.indexOf(depTime);
//                if (index > -1) {
//                    departTime.splice(index, 1);
//                }
//            }
//            //console.log("hello3");
//            var Flights = $scope.flights.Results[0];
//            //console.log("hello6", Flights);
//            $scope.oneWayFlights = $(Flights).filter(function (i, n) {
//                return departTime.includes(n.time);
//            });
//            //console.log("hello4", $scope.oneWayFlights);
//            
//            //$scope.flights1.remove();
//
//            //console.log($scope.flights1);
//            //$scope.pageNo=0;
//            $scope.loadMore();
//            //$scope.$apply();
//            
//        }
//
//
//    });



    $scope.loadMore = () => {

        //if ($scope.hasMoreData) {

            var pRecord = $scope.pageNo * $scope.pageSize;
            var pSize = $scope.pageSize + pRecord;

            var Data = $scope.oneWayFlights.slice(pRecord, pSize);

            var rData = $scope.returnFlights.slice(pRecord, pSize);

            if (Data.length) {
                $scope.loadOneWay(Data);
            }
            if (rData.length) {
                $scope.loadReturn(rData);
            }
            if (Data.length || rData.length) {
                $scope.pageNo = $scope.pageNo + 1;
                $scope.loadScript();
                $scope.hasMoreData = true;
            } else {
                $scope.hasMoreData = false;
            }

       // }
    }

    $scope.parseReturn = () => {

        angular.forEach($scope.flights.Results[1], function (value, index) {

            var dateHR = new Date(value.Segments[0][0].Origin.DepTime);

            if (dateHR.getHours() >= 4 && dateHR.getHours() < 11) {
                value['time'] = 'morning';
            } else if (dateHR.getHours() >= 11 && dateHR.getHours() < 16) {
                value['time'] = 'afternoon';
            } else if (dateHR.getHours() >= 16 && dateHR.getHours() < 21) {
                value['time'] = 'evening';
            } else {
                value['time'] = 'night';
            }

            value['air'] = value.Segments[0][0].Airline.AirlineName;
            value['stops'] = value.Segments[0].length > 1 ? 'Indirect' : 'Direct';

            $scope.returnFlights.push(value);
        });

    }

    $scope.showSocialMediaIB = function(flightCode){
        $('.show-social-icons-'+flightCode).toggle();
    }

    $scope.showSocialMedia = function(flightCode){
        $('.mediaicons').hide();
        $('.show-social-icons-'+flightCode).slideToggle();
    }



    $scope.parseOneWay = () => {

        angular.forEach($scope.flights.Results[0], function (value, index) {

            if ($scope.airlineCodes.indexOf(value.Segments[0][0].Airline.AirlineCode) == -1) {
                $scope.prefferedflights.push({name: value.Segments[0][0].Airline.AirlineName, fcode: value.Segments[0][0].Airline.AirlineCode});
                $scope.airlineCodes.push(value.Segments[0][0].Airline.AirlineCode);
            }
            var dateH = new Date(value.Segments[0][0].Origin.DepTime);
            //console.log(dateH.getHours());
            if (dateH.getHours() >= 4 && dateH.getHours() < 11) {
                value['time'] = 'morning';
            } else if (dateH.getHours() >= 11 && dateH.getHours() < 16) {
                value['time'] = 'afternoon';
            } else if (dateH.getHours() >= 16 && dateH.getHours() < 21) {
                value['time'] = 'evening';
            } else {
                value['time'] = 'night';
            }

            value['air'] = value.Segments[0][0].Airline.AirlineName;
            value['stops'] = value.Segments[0].length > 1 ? 'Indirect' : 'Direct';

            $scope.oneWayFlights.push(value);

        });

    }

    $scope.loadReturn = (data) => {

        angular.forEach(data, function (value, index) {
            $scope.flights2.push(value);
        });
    }

//    $scope.setReturnTime=(ths)=> {
//
//        console.log($(ths).is(":checked"));
//        if ($(ths).isChecked) {
//            console.log($(ths));
//        }
//    }


    $scope.loadOneWay = (data) => {

        angular.forEach(data, function (value, index) {
            $scope.flights1.push(value);
        });


    };


    $scope.loadScript = function () {

        if (document.getElementById("customScript").length) {
            document.getElementById("customScript").remove();
        }

        var script = document.createElement("script")
        script.type = "text/javascript";

        if (script.readyState) {  //IE
            script.onreadystatechange = function () {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    //callback();
                }
            };
        } else {  //Others
            script.onload = function () {
                //callback();
            };
        }

        script.src = '/js/custom.js';
        document.getElementsByTagName("head")[0].appendChild(script);

        //$scope.doTransLation();
    }

    $scope.sendSelectedHotels = function() {
        $scope.sendText = 'Please wait..';
        $http.post('/api/flight-send', {email: $scope.hotelEmail, data:$scope.selectedFlights, searchId:$scope.search_id})
            .then(function (response) {
                $scope.sendText = 'Send';
                $scope.selectedFlights = [];
                $scope.hotelEmail = '';
                $('.form-check-input').prop('checked', false);
                $('#flight-email-modal').modal('hide');
            }, function (error) {
                $('.form-check-input').prop('checked', false);
                $('#flight-email-modal').modal('hide');
                $scope.sendText = 'Send';
                $scope.selectedFlights = [];
                $scope.hotelEmail = '';
            });
    }

    $scope.toggleSelection = function (flight, type) {
        console.log('selected flight ', flight.ResultIndex, type);
        var selected = {};
        var foundFligh = true;

        if($scope.selectedFlights.length == 0) {
            found = false;
        } else {
            found = false;
            $scope.selectedFlights.map(function(f, i){
                if(flight.ResultIndex === f.index) {
                    found = true;
                    $scope.selectedFlights.splice(i, 1);
                }
            });
        }

        if(!found) {
            if(flight.Segments.length == 2) {
                flight.Segments.map(function(f, i) {
                    selected = {};

                    if(i === 0) {
                        selected['type'] = 'Outbound Flight';
                    } else {
                        selected['type'] = 'Inbound Flight';
                    }

                    selected['depart'] = f[0].Origin.Airport.AirportName + '(' + f[0].Origin.Airport.CityName +')';
                    selected['departT'] = f[0].Origin.DepTime;
                    selected['land'] = f[f.length - 1].Destination.Airport.AirportName + '(' + f[f.length - 1].Destination.Airport.CityName +')';
                    selected['landT'] = f[f.length - 1].Destination.ArrTime;
                    selected['price'] = flight.Fare.OfferedFare;
                    selected['duration'] = 0;
                    selected['index'] = flight.ResultIndex;
                    selected['flightN'] = f[f.length - 1].Airline.AirlineName;
                    selected['flightNo'] = f[f.length - 1].Airline.FlightNumber;
                    selected['flightC'] = f[f.length - 1].Airline.AirlineCode;

                    f.map(function(d) {
                        selected['duration'] = selected['duration'] + d.Duration;
                    });

                    $scope.selectedFlights.push(selected);
                });
            } else {

                selected = {};
                selected['type'] = type;
                selected['depart'] = flight.Segments[0][0].Origin.Airport.AirportName + '(' + flight.Segments[0][0].Origin.Airport.CityName +')';
                selected['departT'] = flight.Segments[0][0].Origin.DepTime;
                selected['land'] = flight.Segments[0][0].Destination.Airport.AirportName + '(' + flight.Segments[0][0].Destination.Airport.CityName +')';
                selected['landT'] = flight.Segments[0][0].Destination.ArrTime;
                selected['price'] = flight.Fare.OfferedFare;
                selected['duration'] = flight.Segments[0][0].Duration;
                selected['index'] = flight.ResultIndex;
                selected['flightN'] = flight.Segments[0][0].Airline.AirlineName;
                selected['flightNo'] = flight.Segments[0][0].Airline.FlightNumber;
                selected['flightC'] = flight.Segments[0][0].Airline.AirlineCode;                

                $scope.selectedFlights.push(selected);

            }
        }
    };


});

app.filter('time', function () {

    var conversions = {
        'ss': angular.identity,
        'mm': function (value) {
            return value * 60;
        },
        'hh': function (value) {
            return value * 3600;
        }
    };

    var padding = function (value, length) {
        var zeroes = length - ('' + (value)).length,
                pad = '';
        while (zeroes-- > 0)
            pad += '0';
        return pad + value;
    };

    return function (value, unit, format, isPadded) {
        var totalSeconds = conversions[unit || 'ss'](value),
                hh = Math.floor(totalSeconds / 3600),
                mm = Math.floor((totalSeconds % 3600) / 60),
                ss = totalSeconds % 60;

        format = format || 'hh:mm:ss';
        isPadded = angular.isDefined(isPadded) ? isPadded : true;
        hh = isPadded ? padding(hh, 2) : hh;
        mm = isPadded ? padding(mm, 2) : mm;
        ss = isPadded ? padding(ss, 2) : ss;

        return format.replace(/hh/, hh).replace(/mm/, mm).replace(/ss/, ss);
    };
});
