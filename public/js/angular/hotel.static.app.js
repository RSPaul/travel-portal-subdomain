var app = angular.module("hotelStaticApp", ['infinite-scroll', 'ngSanitize']);

app.controller("searchStaticCtrl", function ($scope, $http, $timeout, $locale, $location, $rootScope, $window) {
    
    $scope.hotels = [];
    $scope.searchData = [];
    $scope.hotelCount = 0;
    $scope.loaded = false;
    $scope.busy = false;
    $scope.after = '';
    $scope.priceRange = 500000;
    $scope.loadMore = false;
    $scope.traceId = '';
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


    $scope.searchHotels = function () {
        $scope.loaded = true;
    }

    $scope.loadMap = function () {
        var locations = [];
        $scope.hotels.map(function (h) {
            if (h.TBO_data.Latitude && h.TBO_data.Latitude) {

                locations.push([h.TBO_data.HotelName, parseFloat(h.TBO_data.Latitude), parseFloat(h.TBO_data.Latitude), h.TBO_data.HotelPicture, h.TBO_data.HotelCode, h.TBO_data.ResultIndex, h.TBO_data.h_rating, h.TBO_data.HotelCode]);

            } else if (h.static_data && h.static_data.hotel_location) {

                locations.push([h.static_data.hotel_name, parseFloat(h.static_data.hotel_location['@Latitude']), parseFloat(h.static_data.hotel_location['@Longitude']), h.static_data.hotel_images.length ? h.static_data.hotel_images : h.TBO_data.HotelPicture, h.TBO_data.HotelCode, h.TBO_data.ResultIndex, h.TBO_data.h_rating, h.TBO_data.HotelCode]);
            }
        });

        let map;
        let mapCenter = {lat: 25.276987, lng: 55.296249};
        if (locations && locations.length) {
            mapCenter = {lat: locations[0][1], lng: locations[0][2]};
        }

        var referral = $('.referral').val() || '0';

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
            zoom: 12
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map
            });



            ///hotel/{traceId}/{code}/{checkIn}/{rooms}/{city_id}/{nights}/{referral}'
            var hotelLink = '/hotel/' + $scope.traceId + '/' + locations[i][7] + '/' + $('#departdate').val() + '/' + $('#roomCount').val() + '/' + $('#city_id').val() + '/' + $scope.searchData.NoOfNights + '/' + referral + '?' + guest;

            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    let hotelDetails =
                            "<div class='hotel-details-map'>" +
                            "<a target='_blank' href='"+ hotelLink +"'><p><img src='" + locations[i][3] + "'></p>" +
                            "<h4 class='hotel-name-map text-center'>" + locations[i][0] +
                            "</h4></a>" +
                            "</div>";

                    infowindow.setContent(hotelDetails);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    }


    $scope.viewHotel = function (e, hotel_name, hotel_code) {
        if ($(e.target).closest(".listing_rice_info").length) {
        } else {
            // /hotel/{country}/{city}/{hotel_name}/{hotel_code}
            hotel_name = hotel_name.replace(/\s+/g, '-').toLowerCase();
            var city_name = $('#city_name').val().replace(/\s+/g, '-').toLowerCase();
            var country_name = $('#country_name').val().replace(/\s+/g, '-').toLowerCase();
            $window.open('/hotel/' + country_name + '/' + city_name + '/' + hotel_name + '/' + hotel_code, '_blank');
        }
    }

    $scope.searchHotel = function () {
        if ($scope.hotelName && $scope.hotelName.length >= 3) {
            $('.hotel-item').hide();
            $('.hotel-item').each(function(){
                if($(this).data('name').toLowerCase().indexOf($scope.hotelName.toLowerCase()) !== -1) {
                    $(this).show();
                }
            });
        }
    }

    $scope.showPrice = function() {
        $('.departdate').focus();
    };

    $scope.clearSearch = function () {
        $scope.loadMore = true;
        $scope.busy = true;
        $scope.hotelName = '';
        //$scope.hotels = $scope.hotelsRaw;
        //$scope.loadMap();
        $scope.loadMore = false;
        $scope.busy = false;
        $scope.noResults = false;
        $(".user-ratings-static").prop('checked', false);
        $(".tp-ratings-static").prop('checked', false);
        $(".hotel-amns-static").prop('checked', false);
        $(".hotel-loc-static").prop('checked', false);
        $('.hotel-item').show();
    }

    $scope.checkAndClearSearch = function () {
        if ($scope.hotelName == '') {
            $('.hotel-item').show();
            $scope.loadMore = false;
            $scope.busy = false;
            $scope.noResults = false;
            $(".user-ratings-static").prop('checked', false);
            $(".tp-ratings-static").prop('checked', false);
            $(".hotel-amns-static").prop('checked', false);
            $(".hotel-loc-static").prop('checked', false);
        }
    }

    $scope.filterByTypes = function () {
        $scope.hotels = [];
        $scope.htypes = [];
        $scope.noMoreLoad = false;

        // $scope.filterHotel = true;
        $('.hotel-types-static').each(function () {

            if ($(this).is(':checked')) {
                $scope.htypes.push($(this).val());
            }
        });
        
        $('.hotel-item').each(function () {
            //console.log($(this).data('htype'));
            if ($scope.htypes.includes($(this).data('htype'))) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });


        setTimeout(function () {
            if (!$('.hotel-item').is(":visible") || $('.hotel-item').length == 0) {
                $('.hotel-item')
            }
        }, 1000);

        if ($scope.htypes.length === 0) {
            $scope.filterHotel = false;
            $scope.hotels = $scope.hotelsRaw;
           // $scope.loadMap();
            $scope.loadMore = false;
            $scope.busy = false;
            $scope.noMoreLoad = false;
            $('.hotel-item').show();
        }
    }

    $scope.filterByRatings = function (rating) {
        $scope.hotels = [];
        $scope.checkedRatings = [];
        $scope.noMoreLoad = false;

        // $scope.filterHotel = true;
        $('.user-ratings-static').each(function () {

            if ($(this).is(':checked')) {
                $scope.checkedRatings.push($(this).val());
            }
        });

        $('.hotel-item').each(function () {
            if ($scope.checkedRatings.includes($(this).data('rating'))) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });


        setTimeout(function () {
            if (!$('.hotel-item').is(":visible") || $('.hotel-item').length == 0) {
               $('.hotel-item')
            }
        }, 1000);

        if ($scope.checkedRatings.length == 0) {
            $scope.filterHotel = false;
            $scope.hotels = $scope.hotelsRaw;
            //$scope.loadMap();
            $scope.loadMore = false;
            $scope.busy = false;
            $scope.noMoreLoad = false;
            $('.hotel-item').show();
        }
    }

    $scope.filterByTPRatings = function (rating, count) {
        if(count == 0) {
            return false;
        }
        $scope.hotels = [];
        $scope.checkedTPRatings = [];
        $scope.noMoreLoad = false;

        // $scope.filterHotel = true;
        $('.tp-ratings-static').each(function () {

            if ($(this).is(':checked')) {
                $scope.checkedTPRatings.push($(this).val());
            }
        });

        $('.hotel-item').each(function () {
            if ($scope.checkedTPRatings.includes($(this).data('tprating'))) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });


        setTimeout(function () {
            if (!$('.hotel-item').is(":visible") || $('.hotel-item').length == 0) {
                $('.hotel-item')
            }
        }, 1000);

        if ($scope.checkedTPRatings.length == 0) {
            $scope.filterHotel = false;
            $scope.hotels = $scope.hotelsRaw;
            //$scope.loadMap();
            $scope.loadMore = false;
            $scope.busy = false;
            $scope.noMoreLoad = false;
            $('.hotel-item').show();
        }
    }

    $scope.filterByLocation = function (location, count) {

        if(count == 0) {
            return false;
        }
        $scope.hotels = [];
        $scope.checkedLocations = [];
        $scope.noMoreLoad = false;

        // $scope.filterHotel = true;
        $('.hotel-loc-static').each(function () {

            if ($(this).is(':checked')) {
                $scope.checkedLocations.push($(this).val());
            }
        });

        $('.hotel-item').each(function () {
            if ($scope.checkedLocations.includes($(this).data('location'))) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });


        setTimeout(function () {
            if (!$('.hotel-item').is(":visible") || $('.hotel-item').length == 0) {
               $('.hotel-item').show();
            }
        }, 1000);

        if ($scope.checkedLocations.length == 0) {
            $scope.filterHotel = false;
            $scope.hotels = $scope.hotelsRaw;
            //$scope.loadMap();
            $scope.loadMore = false;
            $scope.busy = false;
            $scope.noMoreLoad = false;
            $('.hotel-item').show();
        }
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

    $scope.toggleHAmenities = function(el) {
        $scope.toggleHAmenitiesFlag = !$scope.toggleHAmenitiesFlag;
        $('.' + el).toggleClass('view-more');
    }

    $scope.toggleRAmenities = function(el) {
        $scope.toggleRAmenitiesFlag = !$scope.toggleRAmenitiesFlag;
        $('.' + el).toggleClass('view-more');
    }

    $scope.filterByHAmenities = function() {
        $scope.hotels = [];
        $scope.checkedHAmns = [];
        $scope.noMoreLoad = false;

        // $scope.filterHotel = true;
        $('.hotel-amns-static').each(function () {

            if ($(this).is(':checked')) {
                $scope.checkedHAmns.push($(this).val().toLowerCase());
            }
        });

        $('.hotel-item').each(function () {
            var item = $(this);
            item.hide();
            $scope.checkedHAmns.map(function(value){
                if (item.data('facility').toLowerCase().indexOf(value.toLowerCase()) >= 0) {
                    item.show();
                }
            });
            // if ($scope.checkedHAmns.includes($(this).data('facility').toLowerCase())) {
            //     $(this).show();
            // } else {
            //     $(this).hide();
            // }
        });


        setTimeout(function () {
            if (!$('.hotel-item').is(":visible") || $('.hotel-item').length == 0) {
                $('.hotel-item').show();
            }
        }, 1000);

        if ($scope.checkedHAmns.length == 0) {
            $scope.filterHotel = false;
            $scope.hotels = $scope.hotelsRaw;
           // $scope.loadMap();
            $scope.loadMore = false;
            $scope.busy = false;
            $scope.noMoreLoad = false;
            $('.hotel-item').show();
        }
    }
});



app.controller("roomStaticCtrl", function ($scope, $http, $timeout, $locale, $location, $rootScope, $window, $filter) {
   
    $scope.selectedRoomType = [];
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

    $('.room-type-checkbox').change(function () {
        $scope.filterByRoomType($(this).prop('checked'), $(this).val());
        console.log('filter by ', $(this).prop('checked'), $(this).val());
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

            // $('.rooms-tr-meal-twice').each(function () {
            //     if ($(this).data('name').toLowerCase().indexOf(value) > -1) {
            //         $(this).show();
            //         $(this).addClass('no-filters');
            //     } else {
            //         if (!$(this).hasClass('no-filters')) {
            //             $(this).hide();
            //         }
            //     }
            // });
        });

        if ($scope.selectedRoomType.length == 0) {
            $('.rooms-tr').show();
            $('.rooms-tr').removeClass('no-filters');

            // $('.rooms-tr-meal-twice').show();
            // $('.rooms-tr-meal-twice').removeClass('no-filters');
        }

        $timeout(function () {
            $scope.loaded = true;
        }, 500);
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

});
