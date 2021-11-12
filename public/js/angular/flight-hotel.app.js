var app = angular.module("flighthotelApp", ['infinite-scroll', 'ngSanitize']);

app.controller("searchCtrl", function ($scope, $http, $timeout, $locale, $location, $rootScope, $window) {

    $scope.hotels = [];

    $scope.filteredHotels = [];

    $scope.allData = [];
    $scope.flights1 = [];
    $scope.flights2 = [];
    $scope.searchData = [];
    $scope.hotelCount = 0;
    $scope.loaded = false;
    $scope.busy = false;
    $scope.after = '';
    $scope.priceRange = 500000;
    $scope.loadMore = false;
    $scope.traceId = '';
    $scope.searchId = '';
    $scope.search_id_hotel = '';
    $scope.flighttraceId = '';
    $scope.hotelsRaw = [];
    $scope.noResults = false;
    $scope.checkedRatings = [];
    $scope.checkedTPRatings = [];
    $scope.htypes = [];
    $scope.filterHotel = false;
    $scope.loadingMore = true;
    $scope.noMoreLoad = false;

    $scope.unrated = 0;
    $scope.t_star = 0;
    $scope.th_star = 0;
    $scope.f_star = 0;
    $scope.fi_star = 0;
    $scope.isHalal = 0;

    $scope.unrated_t = 0;
    $scope.tp_one = 0;
    $scope.tp_one_h = 0;
    $scope.tp_two = 0;
    $scope.tp_two_h = 0;
    $scope.tp_three = 0;
    $scope.tp_three_h = 0;
    $scope.tp_four = 0;
    $scope.tp_four_h = 0;
    $scope.tp_five = 0;

    $scope.h_amenities = [];
    $scope.h_amenities_raw = [];
    $scope.toggleHAmenitiesFlag = false;
    $scope.checkedHAmns = [];

    $scope.r_amenities = [];
    $scope.r_amenities_raw = [];
    $scope.toggleRAmenitiesFlag = false;
    $scope.locations = [];
    $scope.checkedLocations = [];
    $scope.distanceRange = 80;
    $scope.selectedHotel = [];
    $scope.sendText = 'Send';
    $scope.s_city = '';
    $scope.search_id = '';
    $scope.hotelLayout = 'list';
    $scope.hotelName = '';
    $scope.hotelNameMob = '';

    $scope.hMarkers = [];
    $scope.map = null;
    $scope.markerCluster = null;

    $scope.pageNo = 0;
    $scope.pageSize = 10;
    $scope.hasMoreData = false;
    $scope.lottery_limit = 1000;


    $scope.setLayout = function (lyt) {
        $scope.hotelLayout = lyt;
    }

    $scope.doTransLation = function () {
        var jObj = $('.goog-te-combo');
        var db = jObj.get(0);
        var lang = getCookie("googtrans");
        if (lang !== '/en/en') {
            jObj.val(lang);
            fireEvent(db, 'change');
        }
    }

    $window.onload = function () {
        //$scope.searchHotels();
        //$scope.loadScript();
    };


    $scope.searchHotels = function (load) {
        $scope.loaded = false;

        $('#hotel-loader').hide();
        $('#hotel-loader').hide('');
        $('#db-hotels').show();

        var queryParmas = [];
        if (load) {

            var params = $location.absUrl().split('&');
            if (params.length) {
                params.forEach(function (value) {
                    if (value.indexOf('hotels') == -1) {
                        var parm = value.split('=');
                        var obj = {};

                        if (decodeURI(parm[0]) === 'city_id') {
                            obj[decodeURI(parm[0])] = $('#city_id').val();

                        } else {
                            obj[decodeURI(parm[0])] = decodeURI(parm[1]);
                        }

                        queryParmas.push(obj);
                    }
                });

                $('.child-ages').each(function () {

                    var obj = {};
                    obj['Room' + $(this).data('room') + 'Childs'] = $(this).val();
                    queryParmas.push(obj);
                });

                var obj = {};
                obj['currency'] = $('#currency').val();

                setTimeout(function () {
                    $("#loadingInProgress").modal("hide");
                    $('.data-section').show();
                }, 1500);

            }
        } else {
            $scope.hotels = [];
            $('#autocomplete').val();
            queryParmas.push({'Location': $('#autocompleteFH').val()},
                    {'Latitude': $('#LatitudeFH').val()},
                    {'Longitude': $('#LongitudeFH').val()},
                    {'Radius': $('#RadiusFH').val()},
                    {'city_id': $('#city_id').val()},
                    {'countryCode': $('#country_codeFH').val()},
                    {'city_name': $('#city_nameFH').val()},
                    {'countryName': $('#country_nameFH').val()},
                    {'country': $('#country').val()},
                    {'currency': $('#currency').val()},
                    {'ishalal': $('#ishalal').val()},
                    {'referral': $('#referral').val() || '0'},
                    {'returndate': $('.returndate').val()},
                    {'departdate': $('#departHotelFH').val()},
                    {'roomsGuests': $('#roomsGuests').val()},
                    {'roomCount': $('#roomCount').val()});

            $('.adultCountHidden').each(function (i, v) {
                var obj = {};
                obj['a' + parseInt(1 + i)] = $(this).val();
                queryParmas.push(obj);
            });

            $('.childCountHidden').each(function (i, v) {
                var obj = {};
                obj['c' + parseInt(1 + i)] = $(this).val();
                queryParmas.push(obj);
            });

            for (var r = 1; r <= $('#roomCount').val(); r++) {

                for (c = 0; c < $('#childCountRoom' + r).val(); c++) {
                    var obj = {};
                    obj['ca' + parseInt(c + 1) + 'r' + parseInt(r)] = $('#child' + parseInt(c + 1) + 'AgeRoom' + parseInt(r) + ' option:selected').val();
                    queryParmas.push(obj);
                }
            }
        }

        $http.post('/api/flight-hotels', queryParmas)
                .then(function (returnArray) {

                    var response = returnArray.data;
                    if (response.status) {

                        $scope.allData = response.hotels;

                        $scope.flights1 = response.flights.Results[0][0];
                        if (response.flights.Results[1] && response.flights.Results[1].length > 0) {
                            $scope.flights2 = response.flights.Results[1][0];
                        }
                        $scope.searchId = response.search_id;
                        $scope.search_id_hotel = response.search_id_hotel;
                        $scope.flighttraceId = response.flights.TraceId;

                        $scope.hotelCount = response.hotel_count;
                        $scope.iniscomm = response.commission_inis;
                        $scope.referral = response.referral;

                        $scope.unrated = response.unrated;
                        $scope.t_star = response.t_star;
                        $scope.th_star = response.th_star;
                        $scope.f_star = response.f_star;
                        $scope.fi_star = response.fi_star;
                        $scope.isHalal = response.isHalal;

                        $scope.unrated_t = response.unrated_t;
                        $scope.tp_one = response.tp_one;
                        $scope.tp_one_h = response.tp_one_h;
                        $scope.tp_two = response.tp_two;
                        $scope.tp_two_h = response.tp_two_h;
                        $scope.tp_three = response.tp_three;
                        $scope.tp_three_h = response.tp_three_h;
                        $scope.tp_four = response.tp_four;
                        $scope.tp_four_h = response.tp_four_h;
                        $scope.tp_five = response.tp_five;

                        $scope.search_id = response.search_id;
                        $scope.traceId = response.traceId;
                        // $scope.hotels.sort(function(a, b) { return b.TBO_data.h_rating - a.TBO_data.h_rating; });
                        $scope.hotelsRaw = $scope.hotels;
                        $scope.h_amenities_raw = response.ameneties_array;

                        response.ameneties_array.map(function (v, i) {
                            if (i < 10) {
                                $scope.h_amenities.push(v);
                            }
                        });

                        $scope.s_city = response.s_city;
                        $scope.r_amenities_raw = response.room_ameneties_array;

                        response.room_ameneties_array.map(function (v, i) {
                            if (i < 10) {
                                $scope.r_amenities.push(v);
                            }
                        });

                        $scope.locations = response.locations;
                        $scope.searchData = response.input_data;
                        $scope.lottery_limit = response.lottery_Limit;

                        //$scope.h_amenities = response.ameneties_array;
                        $scope.startTimer();
                        $scope.doTransLation();
                        //setTimeout(function(){

                        $scope.filterData();
                        $scope.loadMarkers($scope.allData);
                        //}, 1000);
                        //$scope.loadMap();


                    } else {
                        $scope.hotels = [];
                        $scope.loadMap();
                        $scope.noResults = true;
                    }

                    if ($scope.allData.length > 0) {
                        $scope.hasMoreData = true;
                    }


                    $scope.loaded = true;
                    $("#loadingInProgress").modal("hide");
                    $('#db-hotels').hide();
                    $('#db-hotels').html('');
                    // $('#db-hotels').html('');

                }, function (error) {

                    $scope.noResults = true;
                    $scope.loaded = true;
                    $scope.loadMap();
                    $("#loadingInProgress").modal("hide");

                });

    }

    $scope.filterData = function () {

        $scope.hotels = [];

        if ($scope.allData.length) {


            $scope.unrated = 0;
            $scope.fi_star = 0;
            $scope.f_star = 0;
            $scope.th_star = 0;
            $scope.t_star = 0;

            $scope.unrated_t = 0;
            $scope.tp_one = 0;
            $scope.tp_one_h = 0;
            $scope.tp_two = 0;
            $scope.tp_two_h = 0;
            $scope.tp_three = 0;
            $scope.tp_three_h = 0;
            $scope.tp_four = 0;
            $scope.tp_four_h = 0;
            $scope.tp_five = 0;
            $scope.pageNo = 0;

            $scope.isHalal = 0;

            $scope.locations = [];


            $scope.filteredHotels = $scope.allData.filter((hotel) => {
                var hprice = hotel.TBO_data.Price.OfferedPriceRoundedOff + ($scope.iniscomm / 100 * hotel.TBO_data.Price.OfferedPriceRoundedOff);
                return (hprice <= $scope.priceRange);
            });

            $scope.filteredHotels = $scope.filteredHotels.filter((hotel) => {
                return (hotel.static_data.distance <= $scope.distanceRange);
            });

            if ($scope.checkedRatings.length) {
                $scope.filteredHotels = $scope.filteredHotels.filter((hotel) => {
                    return $scope.checkedRatings.includes(hotel.TBO_data.h_rating);
                });
            }

            if ($scope.checkedLocations.length) {
                $scope.filteredHotels = $scope.filteredHotels.filter((hotel) => {
                    var loc = (hotel.static_data && hotel.static_data.hotel_address) ? hotel.static_data.hotel_address.CityName : $scope.searchData['Location'];
                    return $scope.checkedLocations.includes(loc);
                });
            }

            if ($scope.htypes.length) {
                $scope.filteredHotels = $scope.filteredHotels.filter((hotel) => {
                    var hyp = (hotel.static_data.ishalal == 'yes') ? 'halal' : 'NA';
                    return $scope.htypes.includes(hyp);
                });
            }


            if ($scope.checkedTPRatings.length) {
                $scope.filteredHotels = $scope.filteredHotels.filter((hotel) => {
                    return $scope.checkedTPRatings.includes(hotel.static_data.tp_ratings);
                });
            }

            if ($scope.hotelName != '') {
                var hname = ($scope.hotelName).toLowerCase();
                $scope.filteredHotels = $scope.filteredHotels.filter((hotel) => {
                    return hotel.static_data.hotel_name.toLowerCase().indexOf(hname) !== -1;
                });

            }

            if ($scope.hotelNameMob != '') {
                var hname = ($scope.hotelNameMob).toLowerCase();
                $scope.filteredHotels = $scope.filteredHotels.filter((hotel) => {
                    return hotel.static_data.hotel_name.toLowerCase().indexOf(hname) !== -1;
                });

            }

            if ($scope.checkedHAmns.length) {

                $scope.filteredHotels = $scope.filteredHotels.filter((hotel) => {
                    var hotel_amets = [];
                    if (hotel.static_data.hotel_facilities.length > 0) {
                        hotel_amets = $scope.checkedHAmns.filter(x => hotel.static_data.hotel_facilities.includes(x));
                    }
                    return hotel_amets.length == $scope.checkedHAmns.length;
                });

            }

            $scope.filteredHotels.map(h => {
                if (parseInt(h.static_data.start_rating) == 0) {
                    $scope.unrated++;
                } else if (parseInt(h.static_data.start_rating) == 2) {
                    $scope.t_star++;
                } else if (parseInt(h.static_data.start_rating) == 3) {
                    $scope.th_star++;
                } else if (parseInt(h.static_data.start_rating) == 4) {
                    $scope.f_star++;
                } else if (parseInt(h.static_data.start_rating) == 5) {
                    $scope.fi_star++;
                }

                if (h.static_data.tp_ratings === '0.0') {
                    $scope.unrated_t++;
                } else if (h.static_data.tp_ratings === '1.0') {
                    $scope.tp_one++;
                } else if (h.static_data.tp_ratings === '1.5') {
                    $scope.tp_one_h++;
                } else if (h.static_data.tp_ratings === '2.0') {
                    $scope.tp_two++;
                } else if (h.static_data.tp_ratings === '2.5') {
                    $scope.tp_two_h++;
                } else if (h.static_data.tp_ratings === '3.0') {
                    $scope.tp_three++;
                } else if (h.static_data.tp_ratings === '3.5') {
                    $scope.tp_three_h++;
                } else if (h.static_data.tp_ratings === '4.0') {
                    $scope.tp_four++;
                } else if (h.static_data.tp_ratings === '4.5') {
                    $scope.tp_four_h++;
                } else if (h.static_data.tp_ratings === '5.0') {
                    $scope.tp_five++;
                }

                if (h.static_data.ishalal == 'yes') {
                    $scope.isHalal++;
                }

                if (h.static_data.hotel_address && h.static_data.hotel_address !== '') {
                    // h.static_data.hotel_address = json_decode(h.static_data.hotel_address, true);

                    if (h.static_data.hotel_address.CityName && h.static_data.hotel_address.CityName != '') {
                        // console.log('h.static_data.hotel_address ', h.static_data.hotel_address.CityName);

                        if ($scope.locations.length > 0) {
                            var check_loc = false;
                            $scope.locations.map((l, i) => {

                                if (l.name.toLowerCase().replace(/-/g, ' ') === h.static_data.hotel_address.CityName.toLowerCase().replace(/-/g, ' ')) {
                                    $scope.locations[i]['hotels'] = $scope.locations[i]['hotels'] + 1;
                                    check_loc = true;
                                }
                            });
                            if (!check_loc) {
                                $scope.locations.push({name: h.static_data.hotel_address.CityName, hotels: 1});
                            }
                        } else {
                            $scope.locations.push({name: h.static_data.hotel_address.CityName, hotels: 1});
                        }
                    }
                }
            });

            if (!$scope.filteredHotels.length) {
                if ($scope.hotelName == '' && $scope.hotelNameMob == '') {
                    $scope.filteredHotels = $scope.allData;
                }
            }

            $scope.loadMarkers($scope.filteredHotels);

            //$scope.clearMarkers();
            $scope.loadMore();

        }

    }



    $scope.searchHotelsRaw = function () {

        $('.data-section').show();
        $("#loadingInProgress").modal("show");
        var params = $location.absUrl().split('&');
        var queryParmas = [];
        if (params.length) {
            params.forEach(function (value) {
                if (value.indexOf('hotels') == -1) {
                    var parm = value.split('=');
                    var obj = {};

                    obj[decodeURI(parm[0])] = decodeURI(parm[1]);

                    queryParmas.push(obj);
                }
            });

            $('.child-ages').each(function () {

                var obj = {};
                obj['Room' + $(this).data('room') + 'Childs'] = $(this).val();
                queryParmas.push(obj);
            });

            // var obj = {};
            // obj['countryCode'] = $('#country').val();
            // queryParmas.push(obj);
            var obj = {};
            obj['currency'] = $('#currency').val();
            // queryParmas.push(obj);
            // queryParmas['country'] = $('#country').val();
            // queryParmas['currency'] = $('#currency').val();
            //console.log('queryParmas ', queryParmas);
            $http.post('/api/hotels-raw', queryParmas)
                    .then(function (returnArray) {

                        var response = returnArray.data;
                        if (response.status) {
                            $scope.hotels = response.hotels;
                            $scope.hotelCount = response.hotel_count;
                            $scope.iniscomm = response.commission_inis;
                            $scope.referral = response.referral;

                            $scope.unrated = response.unrated;
                            $scope.t_star = response.t_star;
                            $scope.th_star = response.th_star;
                            $scope.f_star = response.f_star;
                            $scope.fi_star = response.fi_star;
                            $scope.isHalal = response.isHalal;

                            $scope.unrated_t = response.unrated_t;
                            $scope.tp_one = response.tp_one;
                            $scope.tp_one_h = response.tp_one_h;
                            $scope.tp_two = response.tp_two;
                            $scope.tp_two_h = response.tp_two_h;
                            $scope.tp_three = response.tp_three;
                            $scope.tp_three_h = response.tp_three_h;
                            $scope.tp_four = response.tp_four;
                            $scope.tp_four_h = response.tp_four_h;
                            $scope.tp_five = response.tp_five;

                            $scope.loadMap();
                            $scope.traceId = response.traceId;
                            // $scope.hotels.sort(function(a, b) { return b.TBO_data.h_rating - a.TBO_data.h_rating; });
                            $scope.hotelsRaw = $scope.hotels;
                            $scope.h_amenities_raw = response.ameneties_array;

                            response.ameneties_array.map(function (v, i) {
                                if (i < 10) {
                                    $scope.h_amenities.push(v);
                                }
                            });

                            $scope.r_amenities_raw = response.room_ameneties_array;

                            response.room_ameneties_array.map(function (v, i) {
                                if (i < 10) {
                                    $scope.r_amenities.push(v);
                                }
                            });

                            $scope.locations = response.locations;
                            //$scope.h_amenities = response.ameneties_array;

                            $scope.doTransLation();

                        } else {
                            $scope.hotels = [];
                            $scope.loadMap();
                            $scope.noResults = true;
                        }

                        $scope.searchData = response.input_data;
                        $scope.loaded = true;
                        $("#loadingInProgress").modal("hide");

                    }, function (error) {

                        $scope.noResults = true;
                        $scope.loaded = true;
                        $scope.loadMap();
                        $("#loadingInProgress").modal("hide");

                    });
        }

    }

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

    $scope.loadMore = () => {

        var pRecord = $scope.pageNo * $scope.pageSize;
        var pSize = $scope.pageSize + pRecord;

        var hData = $scope.filteredHotels.slice(pRecord, pSize);

        if (hData.length) {
            $scope.pushHotels(hData);
        }

        if (hData.length) {
            $scope.pageNo = $scope.pageNo + 1;
            $scope.hasMoreData = true;
        } else {
            $scope.hasMoreData = false;
        }

    }

    $scope.pushHotels = (data) => {

        angular.forEach(data, function (value, index) {
            $scope.hotels.push(value);
        });


    };

    $scope.loadMoreHotels = function () {
        if ($scope.busy || ($scope.hotelName != '' && $scope.hotelName) || $scope.noMoreLoad)
            return;
        $scope.busy = true;

        if (!$('.hotel-item').is(":visible")) {
            $scope.hotels = [];
        }
        var queryparm = '';
        var ratings = $scope.checkedRatings;
        if (ratings.length) {
            queryparm = 'ratings=' + ratings;
        }

        var tpRatings = $scope.checkedTPRatings;
        if (tpRatings.length) {
            if (queryparm == '') {
                queryparm = queryparm + 'tpratings=' + tpRatings;
            } else {
                queryparm = queryparm + '&tpratings=' + tpRatings;
            }
        }

        var htypes = $scope.htypes;
        if (htypes.length) {
            if (queryparm == '') {
                queryparm += 'htypes=' + htypes;
            } else {
                queryparm += '&htypes=' + htypes;
            }
        }

        var hAmns = $scope.checkedHAmns;
        if (hAmns.length) {
            if (queryparm == '') {
                queryparm = queryparm + 'hAmns=' + hAmns;
            } else {
                queryparm = queryparm + '&hAmns=' + hAmns;
            }
        }

        var hLocations = $scope.checkedLocations;
        if (hLocations.length) {
            if (queryparm == '') {
                queryparm = queryparm + 'hloc=' + hLocations;
            } else {
                queryparm = queryparm + '&hloc=' + hLocations;
            }
        }

        $http.get('/api/hotels/more/' + $scope.hotels.length + '/' + $scope.search_id_hotel + '/' + $scope.priceRange + '/' + $scope.distanceRange + '?' + queryparm)
                .then(function (returnArray) {

                    var response = returnArray.data;
                    if (response.hotels && response.hotels.length) {
                        for (var i = 0; i < response.hotels.length; i++) {
                            $scope.hotels.push(response.hotels[i]);
                        }
                        // $scope.hotels.sort(function(a, b) { return b.TBO_data.h_rating - a.TBO_data.h_rating; });
                        $scope.loadMap();
                        $scope.busy = false;
                        $scope.loadingMore = true;
                        $scope.doTransLation();
                    } else {
                        $scope.busy = false;
                        $scope.loadingMore = true;
                        $scope.noMoreLoad = true;
                    }

                }, function (error) {

                    console.log('not able to load more hotels ', error);
                });

    }

    $scope.loadMap = function () {

        $scope.map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            gestureHandling: "greedy",
            mapTypeControl: false,
            fullscreenControl: false,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_TOP,
            },
        });

    }

    $scope.loadMarkers = function (hotelList) {




        if ($scope.markerCluster != null) {
            $scope.markerCluster.removeMarkers($scope.hMarkers);
        }

        $scope.hMarkers = [];

        //console.log("before ",$scope.hMarkers);

        if ($scope.map == null) {
            $scope.loadMap();
        }

        var locations = [];
        hotelList.map(function (h) {
            if (h.TBO_data.Latitude && h.TBO_data.Latitude) {

                locations.push({name: h.TBO_data.HotelName, lat: parseFloat(h.TBO_data.Latitude), lng: parseFloat(h.TBO_data.Latitude), image: h.TBO_data.HotelPicture, code: h.TBO_data.HotelCode, index: h.TBO_data.ResultIndex, ratings: h.TBO_data.h_rating, price: h.TBO_data.FinalPrice, currency: h.TBO_data.Price.CurrencyCode, discount: h.TBO_data.discount, distance: h.static_data.distance, tp_ratings: h.static_data.tp_ratings, address: h.static_data.hotel_address});

            } else if (h.static_data && h.static_data.hotel_location) {

                var hotelImage = (h.static_data.hotel_images && h.static_data.hotel_images.length) ? h.static_data.hotel_images : h.TBO_data.HotelPicture;
                if(hotelImage.indexOf('www') === -1 && hotelImage.indexOf('http') === -1) {
                    hotelImage = 'https://tripheist.s3.ap-south-1.amazonaws.com/' + hotelImage;
                }

                locations.push({name: h.static_data.hotel_name, lat: parseFloat(h.static_data.hotel_location['@Latitude']), lng: parseFloat(h.static_data.hotel_location['@Longitude']), image: hotelImage, code: h.TBO_data.HotelCode, index: h.TBO_data.ResultIndex, ratings: h.TBO_data.h_rating, price: h.TBO_data.FinalPrice, currency: h.TBO_data.Price.CurrencyCode, discount: h.TBO_data.discount, distance: h.static_data.distance, tp_ratings: h.static_data.tp_ratings, address: h.static_data.hotel_address});
            }
        });


        let mapCenter = {lat: 25.276987, lng: 55.296249};

        if (locations && locations.length) {
            mapCenter = {lat: parseFloat($scope.searchData.Latitude), lng: parseFloat($scope.searchData.Longitude)};
        }

        $scope.map.setCenter(mapCenter);

        var referral = referral || '0';

        var guest = '';
        $('.adultCountHidden').each(function (index, val) {
            guest = guest + '&a' + (index + 1);
            guest = guest + '=' + $(this).val();
        });

        $('.childCountHidden').each(function (index, val) {
            guest = guest + '&c' + (index + 1);
            guest = guest + '=' + $(this).val();
        });

        $('.cages').each(function (index, val) {
            guest = guest + '&ca' + $(this).data('age') + 'r' + $(this).data('room');
            guest = guest + '=' + $(this).val();
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        var image = {
            url: '/images/price-badge.png',
            // This marker is 35 pixels wide by 35 pixels high.
            size: new google.maps.Size(75, 20),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 75)
        };


        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
                //map: $scope.map,
                icon: image,
                label: {
                    text: locations[i].currency + " " + Math.round(locations[i].price),
                    color: 'white',
                    fontSize: '13px',
                    fontWeight: 'bold'
                }
            });




            google.maps.event.addListener(marker, 'click', (function (marker, i) {


                return function () {

                    var hotel_name = locations[i].name.replace(/\s+/g, '-').toLowerCase();
                    var city_name = $('#city_nameFH').val().replace(/\s+/g, '-').toLowerCase();
                    var country_name = $('#country_nameFH').val().replace(/\s+/g, '-').toLowerCase();

                    //'/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + $scope.searchData.from + '/' + $scope.searchData.destination + '/' + $scope.searchData.to + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest
                    // var hotelLink = '/hotel/' + country_name + '/' + city_name + '/' + hotel_name + '/' + $scope.traceId + '/' + locations[i].code + '/' + $('#departHotelFH').val() + '/' + $('#roomCount').val() + '/' + $scope.search_id + '/' + $scope.searchData.NoOfNights + '/' + referral + '?' + guest;
                    var hotelLink = '/flight-hotel/' + $scope.traceId + '/' + locations[i].code + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + $scope.searchData.from + '/' + $scope.searchData.destination + '/' + $scope.searchData.to + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest;

                    let hotelDetails = '<div class="row listing_item1 hotel-item" style="margin:0px;padding: 0px 0px;overflow-x: hidden;">'
                            + '<div class="col-lg-5 hotelview imageoverlay" style="min-height:120px;min-height:140px;margin:0px;padding:0px;background:url(' + locations[i].image + ');">'
                            + '<span class="distanceflag ng-binding ng-scope" >'
                            + locations[i].distance + ' Km'
                            + '</span>'
                            + '<span class="tprating ng-binding ng-scope">'
                            + '<img style="width:22px;" src="images/tp-logo.png"> ' + locations[i].tp_ratings + ' / 5'
                            + '</span>'
                            + '</div>'
                            + '<div class="col-lg-7 hotelview" style="margin:0px;padding:0px;">'
                            + '<div class="listing_description_data hotel_desc" style="padding:0px 20px;">'
                            + '<div class="row">'
                            + '<div class="col-12 hotel_desview " style="padding:0px;">'
                            + '<div class="listing_main_desp" style="width:100%;">'
                            + ' <h2  class="ng-binding ng-scope" style="font-size:15px;">'
                            + '<a href="' + hotelLink + '" target="_blank">'
                            + locations[i].name;

                    if (locations[i].address.AddressLine && locations[i].address.AddressLine[0]) {
                        hotelDetails = hotelDetails + '<span  class="country_name ng-binding ng-scope">' + locations[i].address.AddressLine[0] + ', ' + locations[i].address.CityName + ' </span>';
                    } else {
                        hotelDetails = hotelDetails + '<span  class="country_name ng-binding ng-scope">' + locations[i].address.CityName + ' </span>';
                    }

                    hotelDetails = hotelDetails + '</a>'
                            + '</h2>';

                    if (locations[i].ratings >= 1) {
                        hotelDetails = hotelDetails + '<span class="fa fa-star checked" ></span>'
                    } else {
                        hotelDetails = hotelDetails + '<span class="fa fa-star" ></span>'
                    }
                    if (locations[i].ratings >= 2) {
                        hotelDetails = hotelDetails + '<span class="fa fa-star checked" ></span>'
                    } else {
                        hotelDetails = hotelDetails + '<span class="fa fa-star" ></span>'
                    }
                    if (locations[i].ratings >= 3) {
                        hotelDetails = hotelDetails + '<span class="fa fa-star checked" ></span>'
                    } else {
                        hotelDetails = hotelDetails + '<span class="fa fa-star" ></span>'
                    }
                    if (locations[i].ratings >= 4) {
                        hotelDetails = hotelDetails + '<span class="fa fa-star checked" ></span>'
                    } else {
                        hotelDetails = hotelDetails + '<span class="fa fa-star" ></span>'
                    }
                    if (locations[i].ratings >= 5) {
                        hotelDetails = hotelDetails + '<span class="fa fa-star checked" ></span>'
                    } else {
                        hotelDetails = hotelDetails + '<span class="fa fa-star" ></span>'
                    }
                    hotelDetails = hotelDetails + '</div>'
                            + '</div>'
                            + '<div class="col-12 text-right hotel_price_box">'
                            + '<div class="actual_price pull-right text-right">'
                            + '<h4 style="font-size:12px;">'
                            + '<span  class="offer_discount ng-binding ng-scope">' + locations[i].discount + '% OFF</span><br>'
                            + '<span style="color:black;line-height:30px;text-decoration:line-through">'
                            + '<span style="color:red;" class="ng-binding">' + locations[i].currency + ' ' + Math.round(locations[i].price + ((locations[i].price / 100) * locations[i].discount)) + '</span>'
                            + '</span>'
                            + '<br>'
                            + '<span style="color:rgb(6, 170, 6);line-height:10px;font-size:18px;font-weight:bold;" class="ng-binding">' + locations[i].currency + ' ' + Math.round(locations[i].price) + '</span>'
                            + '</h4>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>';

                    // let hotelDetails1x =
                    //         "<div class='hotel-details-map'>" +
                    //         "<a target='_blank' href='" + hotelLink + "'><p><img src='" + locations[i][3] + "'></p>" +
                    //         "<h4 class='hotel-name-map text-center'>" + locations[i][0] +
                    //         "</h4></a>" +
                    //         "</div>";

                    infowindow.setContent(hotelDetails);
                    infowindow.open($scope.map, marker);
                }
            })(marker, i));
            $scope.hMarkers.push(marker);
        }

        if ($scope.markerCluster == null) {
            $scope.markerCluster = new MarkerClusterer($scope.map, $scope.hMarkers);
        } else {
            $scope.markerCluster.setMap(null);
            //$scope.markerCluster.removeMarkers($scope.hMarkers);
            $scope.markerCluster = new MarkerClusterer($scope.map, $scope.hMarkers);
        }
        //console.log("after added",$scope.hMarkers);
    }


    $scope.clearMarkers = () => {
        for (var i = 0; i < $scope.hMarkers.length; i++) {
            $scope.hMarkers[i].setMap(null);
        }
        $scope.hMarkers.length = 0;
    }

    $scope.loadMapxx = function () {
        var locations = [];
        $scope.hotels.map(function (h) {
            if (h.TBO_data.Latitude && h.TBO_data.Latitude) {

                locations.push({name: h.TBO_data.HotelName, lat: parseFloat(h.TBO_data.Latitude), lng: parseFloat(h.TBO_data.Latitude), image: h.TBO_data.HotelPicture, code: h.TBO_data.HotelCode, index: h.TBO_data.ResultIndex, ratings: h.TBO_data.h_rating, price: h.TBO_data.FinalPrice, currency: h.TBO_data.Price.CurrencyCode, discount: h.TBO_data.discount, distance: h.static_data.distance, tp_ratings: h.static_data.tp_ratings, address: h.static_data.hotel_address});

            } else if (h.static_data && h.static_data.hotel_location) {

                locations.push({name: h.static_data.hotel_name, lat: parseFloat(h.static_data.hotel_location['@Latitude']), lng: parseFloat(h.static_data.hotel_location['@Longitude']), image: h.static_data.hotel_images.length ? h.static_data.hotel_images : h.TBO_data.HotelPicture, code: h.TBO_data.HotelCode, index: h.TBO_data.ResultIndex, ratings: h.TBO_data.h_rating, price: h.TBO_data.FinalPrice, currency: h.TBO_data.Price.CurrencyCode, discount: h.TBO_data.discount, distance: h.static_data.distance, tp_ratings: h.static_data.tp_ratings, address: h.static_data.hotel_address});
            }
        });

        let map;
        let mapCenter = {lat: 25.276987, lng: 55.296249};
        if (locations && locations.length) {
            mapCenter = {lat: parseFloat($scope.searchData.Latitude), lng: parseFloat($scope.searchData.Longitude)};
        }

        var referral = referral || '0';

        var guest = '';
        $('.adultCountHidden').each(function (index, val) {
            guest = guest + '&a' + (index + 1);
            guest = guest + '=' + $(this).val();
        });

        $('.childCountHidden').each(function (index, val) {
            guest = guest + '&c' + (index + 1);
            guest = guest + '=' + $(this).val();
        });

        $('.cages').each(function (index, val) {
            guest = guest + '&ca' + $(this).data('age') + 'r' + $(this).data('room');
            guest = guest + '=' + $(this).val();
        });

        map = new google.maps.Map(document.getElementById("map"), {
            center: mapCenter,
            zoom: 15,
            gestureHandling: "greedy"
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        var image = {
            url: '/images/price-badge.png',
            // This marker is 35 pixels wide by 35 pixels high.
            size: new google.maps.Size(75, 20),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 75)
        };


        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
                map: map,
                icon: image,
                label: {
                    text: locations[i].currency + " " + Math.round(locations[i].price),
                    color: 'white',
                    fontSize: '13px',
                    fontWeight: 'bold'
                }
            });




            google.maps.event.addListener(marker, 'click', (function (marker, i) {


                return function () {

                    var hotel_name = locations[i].name.replace(/\s+/g, '-').toLowerCase();
                    var city_name = $('#city_nameFH').val().replace(/\s+/g, '-').toLowerCase();
                    var country_name = $('#country_nameFH').val().replace(/\s+/g, '-').toLowerCase();

                    //'/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + $scope.searchData.from + '/' + $scope.searchData.destination + '/' + $scope.searchData.to + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest;

                    var hotelLink = '/flight-hotel/' + $scope.traceId + '/' + locations[i].code + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + $scope.searchData.from + '/' + $scope.searchData.destination + '/' + $scope.searchData.to + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest;

                    let hotelDetails = '<div class="row listing_item1 hotel-item" style="margin:0px;padding:10px 14px;min-width:330px;">'
                            + '<div class="col-5 hotelview imageoverlay" style="min-height:auto;margin:0px;padding:0px;background:url(' + locations[i].image + ');">'
                            + '<span class="distanceflag ng-binding ng-scope" >'
                            + locations[i].distance + ' Km'
                            + '</span>'
                            + '<span class="tprating ng-binding ng-scope">'
                            + '<img style="width:22px;" src="images/tp-logo.png"> ' + locations[i].tp_ratings + ' / 5'
                            + '</span>'
                            + '</div>'
                            + '<div class="col-7 hotelview" style="margin:0px;padding:0px;">'
                            + '<div class="listing_description_data hotel_desc" style="padding:2px;">'
                            + '<div class="row">'
                            + '<div class="col-12 hotel_desview " style="padding:0px;">'
                            + '<div class="listing_main_desp" style="width:100%;">'
                            + ' <h2  class="ng-binding ng-scope" style="font-size:15px;">'
                            + '<a href="' + hotelLink + '" target="_blank">'
                            + locations[i].name;

                    if (locations[i].address.AddressLine && locations[i].address.AddressLine[0]) {
                        hotelDetails = hotelDetails + '<span  class="country_name ng-binding ng-scope">' + locations[i].address.AddressLine[0] + ', ' + locations[i].address.CityName + ' </span>';
                    } else {
                        hotelDetails = hotelDetails + '<span  class="country_name ng-binding ng-scope">' + locations[i].address.CityName + ' </span>';
                    }

                    hotelDetails = hotelDetails + '</a>'
                            + '</h2>';

                    if (locations[i].ratings >= 1) {
                        hotelDetails = hotelDetails + '<span class="fa fa-star checked" ></span>'
                    } else {
                        hotelDetails = hotelDetails + '<span class="fa fa-star" ></span>'
                    }
                    if (locations[i].ratings >= 2) {
                        hotelDetails = hotelDetails + '<span class="fa fa-star checked" ></span>'
                    } else {
                        hotelDetails = hotelDetails + '<span class="fa fa-star" ></span>'
                    }
                    if (locations[i].ratings >= 3) {
                        hotelDetails = hotelDetails + '<span class="fa fa-star checked" ></span>'
                    } else {
                        hotelDetails = hotelDetails + '<span class="fa fa-star" ></span>'
                    }
                    if (locations[i].ratings >= 4) {
                        hotelDetails = hotelDetails + '<span class="fa fa-star checked" ></span>'
                    } else {
                        hotelDetails = hotelDetails + '<span class="fa fa-star" ></span>'
                    }
                    if (locations[i].ratings >= 5) {
                        hotelDetails = hotelDetails + '<span class="fa fa-star checked" ></span>'
                    } else {
                        hotelDetails = hotelDetails + '<span class="fa fa-star" ></span>'
                    }
                    hotelDetails = hotelDetails + '</div>'
                            + '</div>'
                            + '<div class="col-12 text-right hotel_price_box">'
                            + '<div class="actual_price pull-right text-right">'
                            + '<h4 style="font-size:12px;">'
                            + '<span  class="offer_discount ng-binding ng-scope">' + locations[i].discount + '% OFF</span><br>'
                            + '<span style="color:black;line-height:30px;text-decoration:line-through">'
                            + '<span style="color:red;" class="ng-binding">' + locations[i].currency + ' ' + Math.round(locations[i].price + ((locations[i].price / 100) * locations[i].discount)) + '</span>'
                            + '</span>'
                            + '<br>'
                            + '<span style="color:rgb(6, 170, 6);line-height:10px;font-size:18px;font-weight:bold;" class="ng-binding">' + locations[i].currency + ' ' + Math.round(locations[i].price) + '</span>'
                            + '</h4>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>';

                    // let hotelDetails1x =
                    //         "<div class='hotel-details-map'>" +
                    //         "<a target='_blank' href='" + hotelLink + "'><p><img src='" + locations[i][3] + "'></p>" +
                    //         "<h4 class='hotel-name-map text-center'>" + locations[i][0] +
                    //         "</h4></a>" +
                    //         "</div>";

                    infowindow.setContent(hotelDetails);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    }

    $scope.filterByPrice = function (value) {

//        $scope.noMoreLoad = false;
        $scope.priceRange = value;
        $scope.filterData();

    }

    $scope.filterByDistance = function (value) {
//        $scope.noMoreLoad = false;
        $scope.distanceRange = value;
        $scope.filterData();
//        $('.hotel-item').each(function () {
//            if ($(this).data('distance') > value) {
//                $(this).hide();
//            } else {
//                $(this).show();
//            }
//        });
    }

    $scope.viewHotel = function (e, hotel, referral) {
        if ($(e.target).closest(".carousel-indicators").length || $(e.target).closest(".list-inline-item").length || $(e.target).closest(".share-post-icons").length || $(e.target).closest(".email-hotel-check").length) {
        } else {
            referral = referral || '0';

            var guest = '';
            $('.adultCountHidden').each(function (index, val) {
                guest = guest + '&a' + (index + 1);
                guest = guest + '=' + $(this).val();
            });

            $('.childCountHidden').each(function (index, val) {
                guest = guest + '&c' + (index + 1);
                guest = guest + '=' + $(this).val();
            });

            $('.cages').each(function (index, val) {
                guest = guest + '&ca' + $(this).data('age') + 'r' + $(this).data('room');
                guest = guest + '=' + $(this).val();
            });


            ///hotel/{traceId}/{code}/{checkIn}/{rooms}/{city_id}/{nights}/{referral}'
            $window.open('/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + $scope.searchData.from + '/' + $scope.searchData.destination + '/' + $scope.searchData.to + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest, '_blank');
        }
    }


    $scope.showSocialMedia = function (hotelCode) {
        $('.mediaicons').hide();
        $('.show-social-icons-' + hotelCode).slideToggle();
    }

    $scope.copyToClip = function (e, hotel, referral) {


        referral = referral || '0';

        var guest = '';
        $('.adultCountHidden').each(function (index, val) {
            guest = guest + '&a' + (index + 1);
            guest = guest + '=' + $(this).val();
        });

        $('.childCountHidden').each(function (index, val) {
            guest = guest + '&c' + (index + 1);
            guest = guest + '=' + $(this).val();
        });

        $('.cages').each(function (index, val) {
            guest = guest + '&ca' + $(this).data('age') + 'r' + $(this).data('room');
            guest = guest + '=' + $(this).val();
        });


        ///hotel/{traceId}/{code}/{checkIn}/{rooms}/{city_id}/{nights}/{referral}'
        var domain = $('#domain').val();
        let link = domain + '/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + $scope.searchData.from + '/' + $scope.searchData.destination + '/' + $scope.searchData.to + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest;

        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(link).select();
        document.execCommand("copy");
        $temp.remove();

        $('#copy-' + hotel.TBO_data.HotelCode).attr('class', 'fa fa-check');
        $('#copy-' + hotel.TBO_data.HotelCode).addClass('copied');
    }


    $scope.viewHotelFB = function (e, hotel, referral, type) {
        referral = referral || '0';

        var guest = '';
        $('.adultCountHidden').each(function (index, val) {
            guest = guest + '&a' + (index + 1);
            guest = guest + '=' + $(this).val();
        });

        $('.childCountHidden').each(function (index, val) {
            guest = guest + '&c' + (index + 1);
            guest = guest + '=' + $(this).val();
        });

        $('.cages').each(function (index, val) {
            guest = guest + '&ca' + $(this).data('age') + 'r' + $(this).data('room');
            guest = guest + '=' + $(this).val();
        });

        var hotel_name = hotel.static_data.hotel_name.replace(/\s+/g, '-').toLowerCase();

        var domain = $('#domain').val();
        if (type == 'fb') {

            $window.open('https://www.facebook.com/sharer/sharer.php?u=' + domain + '/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + encodeURI($scope.searchData.from) + '/' + $scope.searchData.destination + '/' + encodeURI($scope.searchData.to) + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest + '&quote=' + hotel.static_data.hotel_name);
        } else if (type == 'wts') {

            $window.open('whatsapp://send?text=' + domain + '/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + encodeURI($scope.searchData.from) + '/' + $scope.searchData.destination + '/' + encodeURI($scope.searchData.to) + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest);

        } else if (type == 'tw') {

            $window.open('http://twitter.com/share?text=' + hotel.static_data.hotel_name + '&url=' + domain + '/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + encodeURI($scope.searchData.from) + '/' + $scope.searchData.destination + '/' + encodeURI($scope.searchData.to) + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest);

        } else if (type == 'pt') {

            $window.open('https://pinterest.com/pin/create/bookmarklet/?media=' + hotel.static_data.hotel_images + '&url=' + domain + '/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + encodeURI($scope.searchData.from) + '/' + $scope.searchData.destination + '/' + encodeURI($scope.searchData.to) + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest + '+&description=' + hotel.static_data.hotel_name + '');

        } else if (type == 'stb') {

            $window.open('https://www.stumbleupon.com/submit?url=' + domain + '/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + encodeURI($scope.searchData.from) + '/' + $scope.searchData.destination + '/' + encodeURI($scope.searchData.to) + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest + '&title=' + hotel.static_data.hotel_name + '');

        } else if (type == 'lkn') {

            $window.open('https://www.linkedin.com/shareArticle?url=' + domain + '/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + encodeURI($scope.searchData.from) + '/' + $scope.searchData.destination + '/' + encodeURI($scope.searchData.to) + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest + '&title=' + hotel.static_data.hotel_name + '');

        } else if (type == 'inst') {

            $window.open('https://www.instagram.com/?url=' + domain + '/flight-hotel/' + $scope.traceId + '/' + hotel.TBO_data.HotelCode + '/' + $('#departHotelFH').val() + '/' + $('#roomCountFH').val() + '/' + $scope.search_id_hotel + '/' + $scope.searchData.NoOfNights + '/' + referral + '/' + $scope.searchData.origin + '/' + encodeURI($scope.searchData.from) + '/' + $scope.searchData.destination + '/' + encodeURI($scope.searchData.to) + '/' + $scope.flighttraceId + '/' + $scope.searchId + '/0/0' + '?' + guest);

        } else {

        }
    }

    $scope.searchHotel = function () {
        if ($scope.hotelName && $scope.hotelName.length >= 3) {
            $scope.loadMore = true;
            $scope.busy = true;
            $scope.hotels = [];
            $scope.hotelsRaw.map(function (hotel) {
                if (hotel.static_data.hotel_name.toLowerCase().indexOf($scope.hotelName.toLowerCase()) >= 0) {
                    $scope.hotels.push(hotel);
                }
            });
            $scope.loadMap();
            $scope.busy = false;
            // $http.get('/api/hotel/search/' + $scope.hotelName + '/' + $scope.searchData.city_id)
            //         .then(function (returnArray) {

            //             var response = returnArray.data;
            //             if (response.hotels && response.hotels.length) {
            //                 $scope.hotels = [];
            //                 for (var i = 0; i < response.hotels.length; i++) {
            //                     $scope.hotels.push(response.hotels[i]);
            //                 }
            //                 $scope.loadMap();
            //                 $scope.busy = false;
            //                 $scope.doTransLation();
            //             } else {
            //                 $scope.noResults = true;
            //                 $scope.busy = false;
            //             }

            //         }, function (error) {

            //             console.log('not able to load more hotels ', error);
            //         });
        }
    }

    $scope.clearSearch = function () {
//        $scope.loadMore = true;
//        $scope.busy = true;
//        $scope.hotelName = '';
//        $scope.hotels = $scope.hotelsRaw;
//        $scope.loadMap();
//        $scope.loadMore = false;
//        $scope.busy = false;
//        $scope.noResults = false;
        $(".user-ratings").prop('checked', false);
        $(".tp-ratings").prop('checked', false);
        $(".hotel-amns").prop('checked', false);
        $(".hotel-loc").prop('checked', false);
        $(".hotel-types").prop('checked', false);
        $(".hotel-amns").prop('checked', false);

        $scope.checkedRatings = [];
        $scope.checkedTPRatings = [];
        $scope.htypes = [];
        $scope.checkedLocations = [];
        $scope.checkedHAmns = [];
        $scope.priceRange = 500000;
        $scope.distanceRange = 80;
        $("#hotelName").val('');
        $scope.hotelName = '';

        $("#hotelNameMob").val('');
        $scope.hotelNameMob = '';

        $scope.filterData();
    }

    $scope.checkAndClearSearch = function () {
        $scope.hotelName = $("#hotelName").val();
        $scope.hotelNameMob = $("#hotelNameMob").val();
        $scope.filterData();
    }

    $scope.filterByTypes = function () {
        $scope.hotels = [];
        $scope.htypes = [];
        $scope.noMoreLoad = false;

        // $scope.filterHotel = true;
        $('.hotel-types').each(function () {

            if ($(this).is(':checked')) {
                $scope.htypes.push($(this).val());
            }
        });

        $scope.filterData();

//        $('.hotel-item').each(function () {
//            //console.log($(this).data('htype'));
//            if ($scope.htypes.includes($(this).data('htype'))) {
//                $(this).show();
//            } else {
//                $(this).hide();
//            }
//        });
//
//
//        setTimeout(function () {
//            if (!$('.hotel-item').is(":visible") || $('.hotel-item').length == 0) {
//                console.log('load more');
//                $scope.loadMoreHotels();
//            }
//        }, 1000);
//
//        if ($scope.htypes.length === 0) {
//            $scope.filterHotel = false;
//            $scope.hotels = $scope.hotelsRaw;
//            $scope.loadMap();
//            $scope.loadMore = false;
//            $scope.busy = false;
//            $scope.noMoreLoad = false;
//            $('.hotel-item').show();
//        }
    }

    $scope.filterByRatings = function (rating) {
        //$scope.hotels = [];
        $scope.checkedRatings = [];
        $scope.noMoreLoad = false;

        // $scope.filterHotel = true;
        $('.user-ratings').each(function () {
            if ($(this).is(':checked')) {
                $scope.checkedRatings.push(parseInt($(this).val()));
            }
        });
        $scope.filterData();
//        $('.hotel-item').each(function () {
//            if ($scope.checkedRatings.includes($(this).data('rating'))) {
//                $(this).show();
//            } else {
//                $(this).hide();
//            }
//        });


//        setTimeout(function () {
//            if (!$('.hotel-item').is(":visible") || $('.hotel-item').length == 0) {
//                console.log('load more');
//                $scope.loadMoreHotels();
//            }
//        }, 1000);
//
//        if ($scope.checkedRatings.length == 0) {
//            $scope.filterHotel = false;
//            $scope.hotels = $scope.hotelsRaw;
//            $scope.loadMap();
//            $scope.loadMore = false;
//            $scope.busy = false;
//            $scope.noMoreLoad = false;
//            $('.hotel-item').show();
//        }
    }

    $scope.filterByTPRatings = function (rating, count) {
//        if (count == 0) {
//            return false;
//        }
//        $scope.hotels = [];
        $scope.checkedTPRatings = [];
//        $scope.noMoreLoad = false;

        // $scope.filterHotel = true;
        $('.tp-ratings').each(function () {

            if ($(this).is(':checked')) {
                $scope.checkedTPRatings.push($(this).val());
            }
        });
        $scope.filterData();
//        $('.hotel-item').each(function () {
//            if ($scope.checkedTPRatings.includes($(this).data('tprating'))) {
//                $(this).show();
//            } else {
//                $(this).hide();
//            }
//        });


//        setTimeout(function () {
//            if (!$('.hotel-item').is(":visible") || $('.hotel-item').length == 0) {
//                $scope.loadMoreHotels();
//            }
//        }, 1000);
//
//        if ($scope.checkedTPRatings.length == 0) {
//            $scope.filterHotel = false;
//            $scope.hotels = $scope.hotelsRaw;
//            $scope.loadMap();
//            $scope.loadMore = false;
//            $scope.busy = false;
//            $scope.noMoreLoad = false;
//            $('.hotel-item').show();
//        }
    }

    $scope.filterByLocation = function (location, count) {
        if (count == 0) {
            return false;
        }
        $scope.hotels = [];
        $scope.checkedLocations = [];
        $scope.noMoreLoad = false;

        // $scope.filterHotel = true;
        $('.hotel-loc').each(function () {

            if ($(this).is(':checked')) {
                $scope.checkedLocations.push($(this).val());
            }
        });

        $scope.filterData();

//        $('.hotel-item').each(function () {
//            if ($scope.checkedLocations.includes($(this).data('location'))) {
//                $(this).show();
//            } else {
//                $(this).hide();
//            }
//        });
//
//
//        setTimeout(function () {
//            if (!$('.hotel-item').is(":visible") || $('.hotel-item').length == 0) {
//                $scope.loadMoreHotels();
//            }
//        }, 1000);

//        if ($scope.checkedLocations.length == 0) {
//            $scope.filterHotel = false;
//            $scope.hotels = $scope.hotelsRaw;
//            $scope.loadMap();
//            $scope.loadMore = false;
//            $scope.busy = false;
//            $scope.noMoreLoad = false;
//            $('.hotel-item').show();
//        }
    }

    $scope.low_price = true;
    $scope.high_price = false;
    $scope.low_rating = false;
    $scope.high_rating = false;
    $scope.sortBy = function (key, order, el) {


        if (key == 'price') {
            if (order == 'low') {
                $scope.hotels.sort(function (a, b) {
                    return a.TBO_data.Price.OfferedPriceRoundedOff - b.TBO_data.Price.OfferedPriceRoundedOff;
                });
                $scope.low_price = true;
                $scope.high_price = false;
                $scope.low_rating = false;
                $scope.high_rating = false;
            } else {
                $scope.hotels.sort(function (a, b) {
                    return b.TBO_data.Price.OfferedPriceRoundedOff - a.TBO_data.Price.OfferedPriceRoundedOff;
                });
                $scope.low_price = false;
                $scope.high_price = true;
                $scope.low_rating = false;
                $scope.high_rating = false;
            }
        } else {
            if (order == 'low') {
                $scope.hotels.sort(function (a, b) {
                    return b.TBO_data.h_rating - a.TBO_data.h_rating;
                });
                $scope.low_price = false;
                $scope.high_price = false;
                $scope.low_rating = true;
                $scope.high_rating = false;
            } else {
                $scope.hotels.sort(function (a, b) {
                    return a.TBO_data.h_rating - b.TBO_data.h_rating;
                });
                $scope.low_price = false;
                $scope.high_price = false;
                $scope.low_rating = false;
                $scope.high_rating = true;
            }

            console.log('here ', $scope.hotels, $(el));
        }

        $(el).addClass('active');
    }

    $scope.toggleHAmenities = function () {
        $scope.toggleHAmenitiesFlag = !$scope.toggleHAmenitiesFlag;
        if ($scope.toggleHAmenitiesFlag) {

            $scope.h_amenities = $scope.h_amenities_raw;

        } else {

            $scope.h_amenities = [];
            $scope.h_amenities_raw.map(function (v, i) {
                if (i < 10) {
                    $scope.h_amenities.push(v);
                }
            });
        }
    }

    $scope.toggleRAmenities = function () {
        $scope.toggleRAmenitiesFlag = !$scope.toggleRAmenitiesFlag;
        if ($scope.toggleRAmenitiesFlag) {

            $scope.r_amenities = $scope.r_amenities_raw;

        } else {

            $scope.r_amenities = [];
            $scope.r_amenities_raw.map(function (v, i) {
                if (i < 10) {
                    $scope.r_amenities.push(v);
                }
            });
        }
    }

    $scope.filterByHAmenities = function () {
        $scope.hotels = [];
        $scope.checkedHAmns = [];
        $scope.noMoreLoad = false;

        // $scope.filterHotel = true;
        $('.hotel-amns').each(function () {

            if ($(this).is(':checked')) {
                $scope.checkedHAmns.push($(this).val());
            }
        });

        $scope.filterData();

//        $('.hotel-item').each(function () {
//            if ($scope.checkedHAmns.includes($(this).data('rating'))) {
//                $(this).show();
//            } else {
//                $(this).hide();
//            }
//        });
//
//
//        setTimeout(function () {
//            if (!$('.hotel-item').is(":visible") || $('.hotel-item').length == 0) {
//                $scope.loadMoreHotels();
//            }
//        }, 1000);
//
//        if ($scope.checkedHAmns.length == 0) {
//            $scope.filterHotel = false;
//            $scope.hotels = $scope.hotelsRaw;
//            $scope.loadMap();
//            $scope.loadMore = false;
//            $scope.busy = false;
//            $scope.noMoreLoad = false;
//            $('.hotel-item').show();
//        }
    }

    $scope.sendSelectedHotels = function () {
        $scope.sendText = 'Please wait..';
        $http.post('/api/flights-hotels-send', {email: $scope.hotelEmail, hotel: $scope.selectedHotel, search_id: $scope.search_id_hotel, flights1: $scope.flights1, flights2: $scope.flights2, url: window.location.href})
                .then(function (response) {
                    $scope.sendText = 'Send';
                    $scope.selectedHotel = [];
                    $scope.hotelEmail = '';
                    $('.form-check-input').prop('checked', false);
                    $('#hotel-email-modal').modal('hide');
                }, function (error) {
                    $('.form-check-input').prop('checked', false);
                    $('#hotel-email-modal').modal('hide');
                    $scope.sendText = 'Send';

                });
    }

    $scope.toggleSelection = function (hotelId) {
        var idx = $scope.selectedHotel.indexOf(hotelId);
        if (idx > -1) {
            $scope.selectedHotel.splice(idx, 1);
        } else {
            $scope.selectedHotel.push(hotelId);
        }
    };


    $scope.startTimer = function () {
        setTimeout(function () {
            var intervalHotelList = setInterval(function () {
                var cookieVal = parseInt($scope.search_id_hotel);
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



app.controller("roomCtrl", function ($scope, $http, $timeout, $locale, $location, $rootScope, $window, $filter) {
    $scope.rooms = [];
    $scope.loaded = false;
    $scope.error = '';
    $scope.error = '';
    $scope.error = '';
    $scope.combination = 'open';
    $scope.room_combination = [];
    $scope.selectedRoomType = [];
    $scope.inclusion_array = [];
    $scope.hotelSearchInput = {};
    $scope.commisioninis_currency = 0;
    $scope.search_id_hotel = '';

    var params = $location.absUrl().split('/flight-hotel/');
    var queryParmas = [];
    params = params[1].split('/');
    $scope.priceRange = 500000;
    $scope.lottery_limit = 1000;

    //$scope.roomCount = $('#roomCount').val();
    $scope.roomCount = [];
    for (var i = 0; i < $('#roomCountFH').val(); i++) {
        $scope.roomCount.push(i);
    }
    //console.log( $scope.roomCount);
    if (params.length) {
        var obj = {};
        obj['traceId'] = decodeURI(params[0]);
        obj['hotelCode'] = decodeURI(params[1]);
        var tId = params[14].split('?');
        obj['flightTraceId'] = decodeURI(tId[0]);
        //obj['hotelIndex'] = decodeURI(params[2]);
        //obj['roomCount'] = $('#roomCount').val();
        obj['supplierIds'] = $('#supplierIds').val();
        obj['referral'] = $('#referral').val();
        obj['searchId'] = decodeURI(params[12]);
        obj['flightID'] = decodeURI(params[13]);
        obj['rflightID'] = tId[0];

    }

    $scope.doTransLation = function () {
        var jObj = $('.goog-te-combo');
        var db = jObj.get(0);
        var lang = getCookie("googtrans");
        if (lang !== '' && lang !== '/en/en') {
            jObj.val(lang);
            fireEvent(db, 'change');
        }
    }

    //console.log(tId);

    $scope.selectedFlight = function (flightID, flightTraceId, searchID) {

        //console.log('dadsdsadsa', flightID);

        window.location.href = '/flight-hotel/' + decodeURI(params[0]) + '/' + decodeURI(params[1]) + '/' + decodeURI(params[2]) + '/' + decodeURI(params[3]) + '/' + decodeURI(params[4]) + '/' + decodeURI(params[5]) + '/' + decodeURI(params[6]) + '/' + decodeURI(params[7]) + '/' + decodeURI(params[8]) + '/' + decodeURI(params[9]) + '/' + decodeURI(params[10]) + '/' + decodeURI(params[11]) + '/' + decodeURI(params[12]) + '/' + flightID + '/' + tId[0] + '?' + tId[1];

        // $http.post('/api/change-flight-hotel/'+flightID+'/'+flightTraceId+'/'+searchID)
        // .then(function (returnArray) {

        //     //window.location.reload();
        //     //console.log('/flight-hotel/'+decodeURI(params[0])+'/'+decodeURI(params[1])+'/'+decodeURI(params[2])+'/'+decodeURI(params[3])+'/'+decodeURI(params[4])+'/'+decodeURI(params[5])+'/'+decodeURI(params[6])+'/'+decodeURI(params[7])+'/'+decodeURI(params[8])+'/'+decodeURI(params[9])+'/'+decodeURI(params[10])+'/'+decodeURI(params[11])+'/'+decodeURI(params[12])+'/'+flightID + '/0?'+tId[1]);

        // }, function (error) {

        //     $scope.loaded = true;

        // });

    }

    $scope.selectedFlightReturn = function (flightID, flightTraceId, searchID) {

        //console.log(flightID);

        window.location.href = '/flight-hotel/' + decodeURI(params[0]) + '/' + decodeURI(params[1]) + '/' + decodeURI(params[2]) + '/' + decodeURI(params[3]) + '/' + decodeURI(params[4]) + '/' + decodeURI(params[5]) + '/' + decodeURI(params[6]) + '/' + decodeURI(params[7]) + '/' + decodeURI(params[8]) + '/' + decodeURI(params[9]) + '/' + decodeURI(params[10]) + '/' + decodeURI(params[11]) + '/' + decodeURI(params[12]) + '/' + decodeURI(params[13]) + '/' + flightID + '?' + tId[1];

        // $http.post('/api/change-flight-hotel-return/'+flightID+'/'+flightTraceId+'/'+searchID)
        // .then(function (returnArray) {

        //     window.location.reload();

        // }, function (error) {

        //     $scope.loaded = true;

        // });

    }



    $scope.getRooms = function () {
        //$("#loadingInProgress").modal("show");
        obj['city_id'] = $('#city_id').val();
        $http.post('/api/flight-hotel/rooms', obj)
                .then(function (returnArray) {
                    var response = returnArray.data;
                    if (response.status) {
                        $scope.error = '';
                        $scope.rooms = response.rooms;
                        $scope.commission = response.commission;
                        $scope.combination = response.combination_type;
                        if (response.flight_price) {
                            $scope.flightPrice = response.flight_price;
                        }
                        for (var r = 0; r < parseInt($('#roomCountFH').val()); r++) {
                            $scope.hotelSearchInput[r] = {adults: response.hotelSearchInput[('adultCountRoom' + (r + 1)).toString()], childs: response.hotelSearchInput[('childCountRoom' + (r + 1)).toString()]};
                        }

                        $('.room_upper_data').css('display', 'flex');
                        var roomCount = $('#roomCountFH').val();
                        $scope.inclusion_array = response.inclusion_array;
                        $scope.room_combination = response.room_combination;
                        $scope.commisioninis_currency = response.commisioninis_currency;
                        $scope.search_id_hotel = response.search_id_hotel;
                        $scope.lottery_limit = response.lottery_Limit;

                        if (parseInt(roomCount) > 1) {


                            if (!$scope.rooms['rooms_0'].length) {
                                $scope.error = 'No rooms found.';
                                $scope.loaded = true;
                            }
                            var tmpRoomCount = roomCount;
                            $timeout(function () {
                                var index = '';
                                var tPrice = 0;
                                for (var c = 0; c < roomCount; c++) {
                                    $('.room-radio-' + c).first().prop('checked', true);
                                    tPrice = tPrice + $('.room-radio-' + c).first().data('price');

                                    if (index != '') {
                                        index = index + '-' + $('.room-radio-' + c).first().data('room');
                                    } else {
                                        index = '-' + $('.room-radio-' + c).first().data('room');
                                    }

                                    $('.room-radio-' + c).first().removeAttr("disabled");
                                    $('.room-radio-' + c).first().parent().parent().removeClass('not-first-column');
                                    $('.room-radio-' + c).first().parent().parent().addClass('first-column');

                                    $('#room_' + c + '_category').val($scope.rooms['rooms_' + c][0]['CategoryId']);
                                    $('#room_' + c + '_RoomIndex').val($scope.rooms['rooms_' + c][0]['RoomIndex']);
                                    $('#room_' + c + '_RoomTypeCode').val($scope.rooms['rooms_' + c][0]['RoomTypeCode']);
                                    $('#room_' + c + '_RoomTypeName').val($scope.rooms['rooms_' + c][0]['RoomTypeName']);
                                    $('#room_' + c + '_RatePlanCode').val($scope.rooms['rooms_' + c][0]['RatePlanCode']);
                                    $('#room_' + c + '_BedTypeCode').val($scope.rooms['rooms_' + c][0]['BedTypeCode']);
                                    $('#room_' + c + '_SmokingPreference').val($scope.rooms['rooms_' + c][0]['SmokingPreference']);
                                    $('#room_' + c + '_CurrencyCode').val($scope.rooms['rooms_' + c][0]['Price']['CurrencyCode']);
                                    $('#room_' + c + '_RoomPrice').val($scope.rooms['rooms_' + c][0]['Price']['RoomPrice']);
                                    $('#room_' + c + '_Tax').val($scope.rooms['rooms_' + c][0]['Price']['Tax']);
                                    $('#room_' + c + '_ExtraGuestCharge').val($scope.rooms['rooms_' + c][0]['Price']['ExtraGuestCharge']);
                                    $('#room_' + c + '_ChildCharge').val($scope.rooms['rooms_' + c][0]['Price']['ChildCharge']);
                                    $('#room_' + c + '_OtherCharges').val($scope.rooms['rooms_' + c][0]['Price']['OtherCharges']);
                                    $('#room_' + c + '_Discount').val($scope.rooms['rooms_' + c][0]['Price']['Discount']);
                                    $('#room_' + c + '_PublishedPrice').val($scope.rooms['rooms_' + c][0]['Price']['PublishedPrice']);
                                    $('#room_' + c + '_PublishedPriceRoundedOff').val($scope.rooms['rooms_' + c][0]['Price']['PublishedPriceRoundedOff']);
                                    $('#room_' + c + '_OfferedPrice').val($scope.rooms['rooms_' + c][0]['Price']['OfferedPrice']);
                                    $('#room_' + c + '_OfferedPriceRoundedOff').val($scope.rooms['rooms_' + c][0]['Price']['OfferedPriceRoundedOff']);
                                    $('#room_' + c + '_AgentCommission').val($scope.rooms['rooms_' + c][0]['Price']['AgentCommission']);
                                    $('#room_' + c + '_AgentMarkUp').val($scope.rooms['rooms_' + c][0]['Price']['AgentMarkUp']);
                                    $('#room_' + c + '_ServiceTax').val($scope.rooms['rooms_' + c][0]['Price']['ServiceTax']);
                                    $('#room_' + c + '_TCS').val($scope.rooms['rooms_' + c][0]['Price']['TCS']);
                                    $('#room_' + c + '_TDS').val($scope.rooms['rooms_' + c][0]['Price']['TDS']);

                                }



                                // var traceId = $('#traceId').val();
                                // var checkInDate = $('#checkInDate').val();
                                // var checkOutDate = $('#checkOutDate').val();
                                // var referral = $('#referral').val();
                                // var category = $('.room-radio-0').first().data('category');
                                // var combination  = $('.room-radio-0').first().data('combination');

                                // var link = '/room/0/' + traceId + '/' + checkInDate + '/' + checkOutDate + '/' + category.replace(/#/g, '-').replace('/', '@') + '/' + combination + '/' + referral + '/' + index;
                                // $('#book-button').attr('href', link);

                                var price = $filter("currency")(tPrice, $scope.rooms['rooms_0'][0].Price.CurrencyCode);
                                price = $scope.flightPrice + price;
                                $('#total-price').html(price);



                                $scope.loaded = true;

                            }, 500);

                        } else {
                            $scope.loaded = true;
                        }
                        $("#loadingInProgress").modal("hide");
                        $scope.doTransLation();

                        setTimeout(function () {
                            $(document).find('.carousel').each(function () {
                                $(this).find('.carousel-item').each(function (index, value) {
                                    if (index == 0) {
                                        $(this).addClass('active');
                                    }
                                });

                                $(this).find('.carousel-indicators li').each(function (index, value) {
                                    if (index == 0) {
                                        $(this).addClass('active');
                                    }
                                });
                            });
                        }, 5000);

                        $scope.startTimer();

                    } else {
                        $scope.rooms = [];
                        $scope.error = response.rooms;
                        $scope.loaded = true;
                        $("#loadingInProgress").modal("hide");
                        if ($scope.error === 'Your session (TraceId) is expired.') {
                            $('#sessionWarningModal').modal('show');
                        }
                    }


                }, function (error) {

                    $scope.loaded = true;

                });
    }

    $scope.showRoomDetails = function (index) {

        var defaultOptions = {};
        var element = $('#roomModal_' + index);
        // var customOptions = scope.$eval($(element).attr('data-options'));
        // combine the two options objects
        // for(var key in customOptions) {
        // 	defaultOptions[key] = customOptions[key];
        // }
        // init carousel
        var curOwl = $(element).data('owlCarousel');
        if (!angular.isDefined(curOwl)) {
            //$(element).owlCarousel({defaultOptions});
            setTimeout(function () {
                // $(element).owlCarousel({

                //     navigation : false, // Show next and prev buttons
                //     slideSpeed : 300,
                //     paginationSpeed : 400,
                //     singleItem:true

                // });
            }, 2000);
        }
        // scope.cnt++;
        $('#roomModal_' + index).modal('show');
    }


    $scope.changeCancelOne = function(index){
        $('#cancellationPopup_'+index).show();
    }

    $scope.changeCancelOneOut = function(index){
        $('#cancellationPopup_'+index).hide();
    }


    $scope.changeCancelTwo = function(index){
        $('#cancellationPopup_'+index).show();
    }

    $scope.changeCancelTwoOut = function(index){
        $('#cancellationPopup_'+index).hide();
    }


    $scope.changeCancelOneMulti = function(index){
        $('#cancellationPopupMulti_'+index).show();
    }

    $scope.changeCancelOneOutMulti = function(index){
        $('#cancellationPopupMulti_'+index).hide();
    }


    $scope.changeCancelTwoMulti = function(index){
        $('#cancellationPopupMulti_'+index).show();
    }

    $scope.changeCancelTwoOutMulti = function(index){
        $('#cancellationPopupMulti_'+index).hide();
    }
    
    
    $scope.showcancelMtlRoom = function(index){
        $('#roomCancelMultiple_' + index).modal('show');
    }

    $('.room-type-checkbox').change(function () {
        $scope.filterByRoomType($(this).prop('checked'), $(this).val());
    });

    $(document).on('click', '.room-filter-li', function () {

        $(this).toggleClass('active');
        $scope.filterByMealType();

    });

    $scope.filterByRoomType = function (checked, value) {
        $scope.loaded = false;
        if (checked) {
            $scope.selectedRoomType.push(value);
        } else {

            $scope.selectedRoomType.remove(value);
            $('.rooms-tr').each(function () {
                if ($(this).data('name').toLowerCase().indexOf(value) > -1) {
                    $(this).removeClass('no-filters');
                }
            });

            $('.rooms-tr-meal-twice').each(function () {
                if ($(this).data('name').toLowerCase().indexOf(value) > -1) {
                    $(this).removeClass('no-filters');
                }
            });
        }

        angular.forEach($scope.selectedRoomType, function (value) {
            $('.rooms-tr').each(function () {
                if ($(this).data('name').toLowerCase().indexOf(value) > -1) {
                    $(this).show();
                    $(this).addClass('no-filters');
                } else {
                    if (!$(this).hasClass('no-filters')) {
                        $(this).hide();
                    }
                }
            });

            $('.rooms-tr-meal-twice').each(function () {
                if ($(this).data('name').toLowerCase().indexOf(value) > -1) {
                    $(this).show();
                    $(this).addClass('no-filters');
                } else {
                    if (!$(this).hasClass('no-filters')) {
                        $(this).hide();
                    }
                }
            });
        });

        if ($scope.selectedRoomType.length == 0) {
            $('.rooms-tr').show();
            $('.rooms-tr').removeClass('no-filters');

            $('.rooms-tr-meal-twice').show();
            $('.rooms-tr-meal-twice').removeClass('no-filters');
        }

        $timeout(function () {
            $scope.loaded = true;
        }, 500);
    }

    $scope.checkMealIncluded = (meals, incMeal) => {
        var hasMeal = false;
        if (meals.length) {
            var matches = 0;
            for (i = 0; i <= meals.length; i++) {
                if (incMeal.includes(meals[i])) {
                    //hasMeal = true;
                    matches++;
                }
            }
        }
        return (matches === meals.length);
    };

    $scope.filterByMealType = function () {
        var checkedMeals = [];
        $scope.loaded = false;

        if ($('.mobileOnlyView').css('display') === 'block') {
            $('#desktopRoomFilters').remove();
            $(".showOptionsDesktop").remove();
        } else {
            $("#roomFilterModal").remove();
            $(".showOptionsMobile").remove();
        }


        $(document).find('.room-filter-li').each(function () {

            if ($(this).hasClass('active')) {
                checkedMeals.push($(this).data('include').toLowerCase());
            } else {
                checkedMeals.remove($(this).data('include').toLowerCase());
            }
        });
        // console.log("meals are:", checkedMeals);
        $('.rooms-tr-meal').each(function () {
            // console.log("meal:", $(this).data('meal'));
            // console.log("include:", $(this).data('include'));

            if ($scope.checkMealIncluded(checkedMeals, $(this).data('meal').toLowerCase()) || $scope.checkMealIncluded(checkedMeals, $(this).data('include').toLowerCase())) {

                $(this).show();
                $(this).addClass('show');

            } else {
                $(this).hide();
                $(this).removeClass('show');
            }

        });

        if (checkedMeals.length == 0) {
            $('.rooms-tr-meal').show();
            $('.rooms-tr-meal').addClass('show');
        }

        // check if not room is show
        $('.rooms-tr').each(function () {
            if (!$(this).find('.rooms-tr-meal.show').length) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });

        //for more than 1 room
        $('.rooms-tr-meal-twice').each(function () {

            console.log($scope.checkMealIncluded(checkedMeals, $(this).data('meal').toLowerCase()), ' == ', $scope.checkMealIncluded(checkedMeals, $(this).data('include').toLowerCase()));

            if ($scope.checkMealIncluded(checkedMeals, $(this).data('meal').toLowerCase()) || $scope.checkMealIncluded(checkedMeals, $(this).data('include').toLowerCase())) {
                // if (checkedMeals.indexOf($(this).data('meal').toLowerCase()) != -1 || checkedMeals.indexOf($(this).data('include').toLowerCase()) != -1) {
                $(this).show();
                $(this).addClass('show');

            } else {
                $(this).hide();
                $(this).removeClass('show');
            }

        });

        if (checkedMeals.length == 0) {
            $('.rooms-tr-meal-twice').show();
            $('.rooms-tr-meal-twice').addClass('show');
        }

        // check if not room is show
        // if(!$('.rooms-tr-meal-twice.show').length) {
        //     $('.rooms-tr-meal-twice').show();
        // }
        // $('.rooms-tr-meal-twice').each(function () {
        //     if (!$(this).find('.rooms-tr-meal-twice.show').length) {
        //         $(this).hide();
        //     } else {
        //         $(this).show();
        //     }
        // });

        $timeout(function () {
            $scope.loaded = true;
        }, 1500);
    }

    $scope.filterByPrice = function (value) {
        $scope.loaded = false;
        var selectRoomCount = $('#roomCountFH').val();

        $('.rooms-tr-meal').each(function () {
            if (parseInt($(this).data('price')) < parseInt(value)) {
                $(this).show();
                $(this).addClass('show');
            } else {
                $(this).hide();
                $(this).removeClass('show');
            }
        });

        //check if not room is show
        $('.rooms-tr').each(function () {
            if (!$(this).find('.rooms-tr-meal.show').length) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });

        $timeout(function () {
            $scope.loaded = true;
        }, 1500);
    }

    $scope.loadMap = function () {
        var locations = [];

        var lat = $('h_lat').val();
        var lng = $('h_lng').val();

        let map;
        let mapCenter = {lat: 25.276987, lng: 55.296249};
        if (lat && lng) {
            mapCenter = {lat: lat, lng: lng};
        }

        map = new google.maps.Map(document.getElementById("map-hotel"), {
            center: mapCenter,
            zoom: 12
        });

        var marker = new google.maps.Marker({
            position: mapCenter
        });

        // To add the marker to the map, call setMap();
        marker.setMap(map);
    }

    $scope.scrollTo = function (e) {
        $('html, body').animate({
            scrollTop: $("#" + e).offset().top - 100
        }, 1000);
    }

    $scope.selectRoom = function (room, count, room_key_index) {


        var roomCount = $('#roomCountFH').val();

        if ($scope.combination == 'fixed') {

            for (var c = 0; c < $scope.room_combination.length; c++) {

                if ($scope.room_combination[c]['CategoryId'] == room['CategoryId']) {


                    for (var k = 0; k < $scope.room_combination[c]['RoomCombination'].length; k++) {

                        if ($.inArray(room.RoomIndex, $scope.room_combination[c]['RoomCombination'][k]['RoomIndex']) !== -1) {


                            for (var f = 0; f < roomCount; f++) {

                                if (f > 0) {

                                    $('.trow').removeClass('first-column');
                                    $('.trow').addClass('not-first-column');

                                    $('#tr-' + f + '-' + $scope.room_combination[c]['RoomCombination'][k]['RoomIndex'][f] + '.not-first-column').removeClass('not-first-column');
                                    $('#tr-' + f + '-' + $scope.room_combination[c]['RoomCombination'][k]['RoomIndex'][f] + '.not-first-column').addClass('first-column');

                                    $('.trow').find('.room-radio-' + f).prop('checked', false);
                                    $('.trow').find('.room-radio-' + f).attr("disabled", true);

                                    $('.trow').find('#room-radio-' + f + '-' + $scope.room_combination[c]['RoomCombination'][k]['RoomIndex'][f] + '-' + room_key_index).prop('checked', true);

                                    $('.trow').find('#room-radio-' + f + '-' + $scope.room_combination[c]['RoomCombination'][k]['RoomIndex'][f] + '-' + room_key_index).removeAttr("disabled");

                                    var category = $('.trow').find('#room-radio-' + f + '-' + $scope.room_combination[c]['RoomCombination'][k]['RoomIndex'][f] + '-' + room_key_index).data('category');
                                    var combination = $('.trow').find('#room-radio-' + f + '-' + $scope.room_combination[c]['RoomCombination'][k]['RoomIndex'][f] + '-' + room_key_index).data('combination');
                                    var roomIndex = $('.trow').find('#room-radio-' + f + '-' + $scope.room_combination[c]['RoomCombination'][k]['RoomIndex'][f] + '-' + room_key_index).data('room');
                                    //console.log(category , combination, roomIndex, $('.trow').find('#room-radio-' + f + '-' + $scope.room_combination[c]['RoomCombination'][k]['RoomIndex'][f] + '-' + room_key_index));

                                    //now find the room
                                    for (var r = 0; r < $scope.rooms['rooms_' + f].length; r++) {

                                        if ($scope.rooms['rooms_' + f][r]['CategoryId'] == category && $scope.rooms['rooms_' + f][r]['RoomIndex'] == roomIndex && $scope.rooms['rooms_' + f][r]['InfoSource'] == combination) {
                                            // console.log('my rooms is ', $scope.rooms['rooms_' + f][r]);	
                                            var temp_room = $scope.rooms['rooms_' + f][r];

                                            $('#room_' + f + '_category').val(temp_room['CategoryId']);
                                            $('#room_' + f + '_RoomIndex').val(temp_room['RoomIndex']);
                                            $('#room_' + f + '_RoomTypeCode').val(temp_room['RoomTypeCode']);
                                            $('#room_' + f + '_RoomTypeName').val(temp_room['RoomTypeName']);
                                            $('#room_' + f + '_RatePlanCode').val(temp_room['RatePlanCode']);
                                            $('#room_' + f + '_BedTypeCode').val(temp_room['BedTypeCode']);
                                            $('#room_' + f + '_SmokingPreference').val(temp_room['SmokingPreference']);
                                            $('#room_' + f + '_CurrencyCode').val(temp_room['Price']['CurrencyCode']);
                                            $('#room_' + f + '_RoomPrice').val(temp_room['Price']['RoomPrice']);
                                            $('#room_' + f + '_Tax').val(temp_room['Price']['Tax']);
                                            $('#room_' + f + '_ExtraGuestCharge').val(temp_room['Price']['ExtraGuestCharge']);
                                            $('#room_' + f + '_ChildCharge').val(temp_room['Price']['ChildCharge']);
                                            $('#room_' + f + '_OtherCharges').val(temp_room['Price']['OtherCharges']);
                                            $('#room_' + f + '_Discount').val(temp_room['Price']['Discount']);
                                            $('#room_' + f + '_PublishedPrice').val(temp_room['Price']['PublishedPrice']);
                                            $('#room_' + f + '_PublishedPriceRoundedOff').val(temp_room['Price']['PublishedPriceRoundedOff']);
                                            $('#room_' + f + '_OfferedPrice').val(temp_room['Price']['OfferedPrice']);
                                            $('#room_' + f + '_OfferedPriceRoundedOff').val(temp_room['Price']['OfferedPriceRoundedOff']);
                                            $('#room_' + f + '_AgentCommission').val(temp_room['Price']['AgentCommission']);
                                            $('#room_' + f + '_AgentMarkUp').val(temp_room['Price']['AgentMarkUp']);
                                            $('#room_' + f + '_ServiceTax').val(temp_room['Price']['ServiceTax']);
                                            $('#room_' + f + '_TCS').val(temp_room['Price']['TCS']);
                                            $('#room_' + f + '_TDS').val(temp_room['Price']['TDS']);
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
            }
        }

        $('#room-radio-' + count + '-' + room.RoomIndex).prop('checked', true);
        $('#room-' + count).html(room.RoomTypeName);

        $('#room_' + count + '_category').val(room['CategoryId']);
        $('#room_' + count + '_RoomIndex').val(room['RoomIndex']);
        $('#room_' + count + '_RoomTypeCode').val(room['RoomTypeCode']);
        $('#room_' + count + '_RoomTypeName').val(room['RoomTypeName']);
        $('#room_' + count + '_RatePlanCode').val(room['RatePlanCode']);
        $('#room_' + count + '_BedTypeCode').val(room['BedTypeCode']);
        $('#room_' + count + '_SmokingPreference').val(room['SmokingPreference']);
        $('#room_' + count + '_CurrencyCode').val(room['Price']['CurrencyCode']);
        $('#room_' + count + '_RoomPrice').val(room['Price']['RoomPrice']);
        $('#room_' + count + '_Tax').val(room['Price']['Tax']);
        $('#room_' + count + '_ExtraGuestCharge').val(room['Price']['ExtraGuestCharge']);
        $('#room_' + count + '_ChildCharge').val(room['Price']['ChildCharge']);
        $('#room_' + count + '_OtherCharges').val(room['Price']['OtherCharges']);
        $('#room_' + count + '_Discount').val(room['Price']['Discount']);
        $('#room_' + count + '_PublishedPrice').val(room['Price']['PublishedPrice']);
        $('#room_' + count + '_PublishedPriceRoundedOff').val(room['Price']['PublishedPriceRoundedOff']);
        $('#room_' + count + '_OfferedPrice').val(room['Price']['OfferedPrice']);
        $('#room_' + count + '_OfferedPriceRoundedOff').val(room['Price']['OfferedPriceRoundedOff']);
        $('#room_' + count + '_AgentCommission').val(room['Price']['AgentCommission']);
        $('#room_' + count + '_AgentMarkUp').val(room['Price']['AgentMarkUp']);
        $('#room_' + count + '_ServiceTax').val(room['Price']['ServiceTax']);
        $('#room_' + count + '_TCS').val(room['Price']['TCS']);
        $('#room_' + count + '_TDS').val(room['Price']['TDS']);




        var tPrice = 0;
        var index = '';
        for (var c = 0; c < roomCount; c++) {
            tPrice = tPrice + $('.room-radio-' + c + ':checked').data('price');

            if (index != '') {
                index = index + '-' + $('.room-radio-' + c + ':checked').data('room');
            } else {
                index = '-' + $('.room-radio-' + c + ':checked').data('room');
            }

            if ($scope.combination == 'fixed') {
                $('#room-' + c).html($('.room-radio-' + c + ':checked').data('rtype'));
            }
        }

        var traceId = $('#traceId').val();
        var checkInDate = $('#checkInDate').val();
        var checkOutDate = $('#checkOutDate').val();
        var referral = $('#referral').val();
        var category = $('.room-radio-0:checked').data('category');
        var combination = $('.room-radio-0:checked').data('combination');



        var price = $filter("currency")(tPrice, $scope.rooms['rooms_0'][0].Price.CurrencyCode);
        if (roomCount > 1) {

            price = tPrice + $scope.flightPrice;
            price = $filter("currency")(price, $scope.rooms['rooms_0'][0].Price.CurrencyCode);
            $('.main_room_price').html(price);
        }
        $('#total-price').html(price);
        $('.flight_hotel_price_text').show();
        //console.log(' Flight Price ', $scope.flightPrice);

    }

    $scope.tdClass = function (inclusion) {
        var className = '';
        if (inclusion && inclusion !== '' && inclusion.length && Array.isArray(inclusion)) {
            inclusion.map(function (v) {
                if (className == '') {
                    className = $.trim(v);
                } else {
                    className = className + " " + $.trim(v);
                }
            });
        } else {
            className = inclusion;
        }
        return className;
    }

    $scope.startTimer = function () {
        setTimeout(function () {
            var intervalHotelList = setInterval(function () {
                var cookieVal = parseInt($scope.search_id_hotel);
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

app.directive("owlCarousel", function () {
    return {
        restrict: 'E',
        transclude: false,
        link: function (scope) {
            scope.initCarousel = function (element) {
                // provide any default options you want
                var defaultOptions = {
                    items: 1
                };
                var customOptions = scope.$eval($(element).attr('data-options'));
                // combine the two options objects
                for (var key in customOptions) {
                    defaultOptions[key] = customOptions[key];
                }
                // init carousel
                var curOwl = $(element).data('owlCarousel');
                if (!angular.isDefined(curOwl)) {
                    //$(element).owlCarousel({defaultOptions});
                    setTimeout(function () {
                        //console.log('here');
                        $(element).owlCarousel({

                            navigation: false, // Show next and prev buttons
                            slideSpeed: 300,
                            paginationSpeed: 400,
                            singleItem: true

                        });
                    }, 2000);
                }
                scope.cnt++;
            };
        }
    };
})
        .directive('owlCarouselItem', [function () {
                return {
                    restrict: 'A',
                    transclude: false,
                    link: function (scope, element) {
                        // wait for the last item in the ng-repeat then call init
                        if (scope.$last) {
                            scope.initCarousel(element.parent());
                        }
                    }
                };
            }]);

Array.prototype.remove = function () {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

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

function fireEvent(element, event) {
    // console.log("in Fire Event");
//    if (document.createEventObject) {
//        // dispatch for IE
//        //  console.log("in IE FireEvent");
//        var evt = document.createEventObject();
//        return element.fireEvent('on' + event, evt)
//    } else {
//        // dispatch for firefox + others
//        // console.log("In HTML5 dispatchEvent");
//        var evt = document.createEvent("HTMLEvents");
//        evt.initEvent(event, true, true); // event type,bubbling,cancelable
//        return !element.dispatchEvent(evt);
//    }
}

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
