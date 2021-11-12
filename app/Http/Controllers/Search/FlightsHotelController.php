<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use DB;
use Session;
use Mail;
use App\Models\User;
use App\Models\Activities;
use App\Models\Packages;
use App\Models\Cruises;
use App\Models\Cabs;
use App\Models\Bookings;
use App\Models\FlightBookings;
use App\Models\FlightPayments;
use App\Models\Payments;
use App\Models\Currencies;
use App\Models\Cities;
use App\Models\Reviews;
use App\Models\StaticDataHotels;
use App\Models\RoomImages;
use App\Services\TBOHotelAPI;
use App\Mail\HotelBookingEmail;
use App\Mail\NoResultsEmail;
use App\Mail\FlightBookingEmail;
use App\Mail\FailedPaymentEmail;
use App\Mail\NewUserRegister;
use App\Mail\FlightsHotelsEmail;
use App\Models\AffiliateUsers;
use App\Models\NotificationAgents;
use App\Models\Posts;
use App\Services\TBOFlightAPI;
use Stripe\Stripe;
use PDF;
use App\Models\Lottery;
use App\Models\LotteryUsers;
use App\Mail\LotteryBookingEmail;
use App\Mail\LotteryWonEmail;
use Currency;
use File;
use Log;
use Config;
use App\Models\Token;

class FlightsHotelController extends Controller {

    public $hotels_list;
    public $temp;
    public $end_user_ip;

    //global $hotels_list;
    public function __construct(Request $request) {
        ini_set('max_execution_time', 1000);
        ini_set('memory_limit', '1024M');
        // $this->api = new TBOHotelAPI();
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? \Request::ip() ?? '3.64.135.96';
        if ($ip == '127.0.0.1') {
            $ip = '132.154.175.244'; //'93.173.228.94'; US = 52.186.25.21 IN = 132.154.175.244
        }
        if (count(explode(', ', $ip)) > 1) {
            $this->end_user_ip = explode(', ', $ip)[0];
        } else {
            $this->end_user_ip = $ip;
        }

    }

    public function lookupF(Request $request) {

        $hotels = array();
        $this->temp = 'set value ';
        //$input = $request->all();
        $roomGuests = array();
        Session::put('active_tab', 'flightshotels');
        $queryValues = $request->query();
        $input_data = $request->all();
        $queryVals = '';
        $input = array();


        Session::put('flightSearhData', $input_data);


        $input['ishalal'] = (isset($input_data['ishalal'])) ? $input_data['ishalal'] : 0;
        $input['departdate'] = $input_data['departdate'];
        $input['returndate'] = $input_data['returndate']; //date('d-m-Y', strtotime($input['departdate']. ' + '. $total_nights .' days'));

        $date = Carbon::createFromDate(str_replace("/", "-", $input['departdate']));
        $now = Carbon::createFromDate(str_replace("/", "-", $input['returndate']));

        $noOfNights = $date->diffInDays($now);
        $input['roomCount'] = (isset($input_data['roomCount'])) ? $input_data['roomCount'] : $queryValues['roomCount'];
        $input['city_id'] = $queryValues['city_id'];
        if (isset($queryValues['referral'])) {
            $input['referral'] = $queryValues['referral'];
        } else {
            $input['referral'] = 0;
        }

        if (isset($queryValues['preffered_hotel']) && $queryValues['preffered_hotel'] != '') {
            $input["preffered_hotel"] = $queryValues['preffered_hotel'];
        } else {
            $input["preffered_hotel"] = (isset($input_data['preffered_hotel'])) ? $input_data['preffered_hotel'] : '';
        }


        $startdate = str_replace("/", "-", $input['departdate']);
        $returndate = str_replace("/", "-", $input['returndate']);

        //get country from IP
        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {

            $location = \Location::get($this->end_user_ip);
        }

        $country = $location->countryCode;

        //get user currency
        $countryInfo = Currencies::where('code', $location->countryCode)->first();
        //$input['currency'] = $countryInfo['currency_code'];
        
         $ourCurrency = Config::get('ourcurrency');

        if (in_array($countryInfo['currency_code'], $ourCurrency)) {
            $input['currency']= $countryInfo['currency_code'];
        } else {
            $input['currency']= 'USD';
        }
        

        $searchCountry = Cities::where('CityId', $input['city_id'])->first();


        if (isset($input_data['countryCode']) && !empty($input_data['countryCode'])) {
            $input['countryCode'] = $input_data['countryCode'];
            $input['city_name'] = $input_data['city_name'];
        } else {
            $searchCountry = Cities::where('CityName', 'like', '%' . $input['city_name'] . '%')->first();
            $input['countryCode'] = $searchCountry['CountryCode'];
            $input['city_name'] = $searchCountry['CityName'];
        }

        $currencyCode = $location->countryCode;



        $currency = $input['currency'];
        $input['countryName'] = $input_data['countryName'];
        
        $input['origin'] = $input_data['origin'];
        $input['from'] = $input_data['from'];
        $input['destination'] = $input_data['destination'];
        $input['to'] = $input_data['to'];

        if (isset($input['Latitude']) && !empty($input['Latitude']) && isset($input['Longitude']) && !empty($input['Longitude'])) {

            $input['city_name_select'] = $input_data['city_name'];

        }else{

            //$input['city_name_select'] = $input_data['city_name_select'];
        }

        $input['user_country'] = (isset($location->countryName) && $location->countryName != '') ? $location->countryName : $countryInfo['name'];

        $date = Carbon::createFromDate($startdate);
        $now = Carbon::createFromDate($returndate);


        $noOfNights = $date->diffInDays($now);

        $roomguests = array();

        $input['NoOfNights'] = $noOfNights;

        $input['CheckInDate'] = str_replace("-", "/", $input['departdate']);

        $noOfRooms = $input['roomCount'];

        $roomGuests = array();
        $total_guests = 0;

        for ($i = 1; $i <= $noOfRooms; $i++) {
            $childAges = array();

            if (isset($queryValues['c' . $i]) && $queryValues['c' . $i] > 0) {

                if ($queryVals == '') {
                    $queryVals = 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                } else {
                    $queryVals = '&' . $queryVals . 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                }

                for ($ca = 1; $ca <= $queryValues['c' . $i]; $ca++) {
                    array_push($childAges, $queryValues['ca' . $ca . 'r' . $i]);
                    $input['ca' . $ca . 'r' . $i] = $queryValues['ca' . $ca . 'r' . $i];
                    $queryVals = $queryVals . '&' . 'ca' . $i . 'r' . $i . '=' . $queryValues['ca' . $ca . 'r' . $i];
                }

                if (isset($childAges) && sizeof($childAges) > 0) {

                    array_push($roomGuests, array(
                        'NoOfAdults' => $queryValues['a' . $i],
                        'NoOfChild' => $queryValues['c' . $i],
                        'ChildAge' => $childAges
                    ));

                    $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                    $input['childCountRoom' . $i] = $queryValues['c' . $i];

                    $total_guests = $total_guests + $queryValues['a' . $i] + $queryValues['c' . $i];
                } else {
                    array_push($roomGuests, array(
                        'NoOfAdults' => $queryValues['a' . $i],
                        'NoOfChild' => $queryValues['c' . $i],
                        'ChildAge' => null
                    ));

                    $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                    $input['childCountRoom' . $i] = $queryValues['c' . $i];

                    $total_guests = $total_guests + $queryValues['a' . $i] + $queryValues['c' . $i];

                    if ($queryVals == '') {
                        $queryVals = 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                    } else {

                        $queryVals = '&' . $queryVals . 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                    }
                }
            } else {
                array_push($roomGuests, array(
                    'NoOfAdults' => (isset($queryValues['a' . $i])) ? $queryValues['a' . $i] : 0,
                    'NoOfChild' => 0,
                    'ChildAge' => null
                ));

                $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                $input['childCountRoom' . $i] = $queryValues['c' . $i];

                $total_guests = $total_guests + $queryValues['a' . $i] + $queryValues['c' . $i];

                if ($queryVals == '') {
                    $queryVals = 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                } else {

                    $queryVals = '&' . $queryVals . 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                }
            }
        }

        if (isset($input_data['Location'])) {
            $input_data['Location'] = $input_data['Location'];
        } else {
            $input_data['Location'] = '';
        }

        if (isset($input_data['Latitude'])) {
            $input_data['Latitude'] = $input_data['Latitude'];
        } else {
            $input_data['Latitude'] = '';
        }

        if (isset($input_data['Longitude'])) {
            $input_data['Longitude'] = $input_data['Longitude'];
        } else {
            $input_data['Longitude'] = '';
        }

        $input['Location'] = (isset($queryValues['Location']) && !empty($queryValues['Location'])) ? $queryValues['Location'] : $input_data['Location'];
        $input['Latitude'] = (isset($queryValues['Latitude']) && !empty($queryValues['Latitude'])) ? $queryValues['Latitude'] : $input_data['Latitude'];
        $input['Longitude'] = (isset($queryValues['Longitude']) && !empty($queryValues['Longitude'])) ? $queryValues['Longitude'] : $input_data['Longitude'];

        $input['Radius'] = '20';

        if (empty($input['city_name'])) {
            $input['city_name'] = $queryValues['city_name'];
        }

        if (empty($input['Location'])) {
            $input['Location'] = $queryValues['city_name'];
        }

        if(Session::get('locale') == 'heb') {
            $input['roomsGuests'] = 'חדרים' .' ' . $input['roomCount'] . 'אורחים' .' '.$total_guests;
        } else {

            $input['roomsGuests'] = $input['roomCount'] . ' Rooms ' . $total_guests . ' Guests';
        }
        

        $isAgent = false;
        if (Auth::user()) {

            $agent = AffiliateUsers::select('user_id')->where('user_id', Auth::user()->id)->first();

            if (isset($agent) && !empty($agent)) {
                $isAgent = true;
            }
        }



        $city = Cities::where(['CityName' => $input['city_name'], 'CountryCode' => $input['countryCode']])
                        ->select('CityId')
                        ->first();
        if(empty($city)) {
            $city = Cities::where('CityName', 'like', '%' . $input['city_name'] . '%')
                    ->where('CountryCode' , $input['countryCode'])
                    ->select('CityId')
                    ->first();
        }
        if(empty($city)) {
            $city = Cities::where('CityName', 'like', '%' . $input['city_name'] . '%')
                    ->select('CityId')
                    ->first();
        }
        
        if(empty($city)) {
            $city = Cities::where('CountryCode' , $input['countryCode'])
                        ->select('CityId')
                        ->first();
        }

        if(empty($city)) {
            $city = Cities::select('CityId')
                        ->first();
        }
        
        $hotels = StaticDataHotels::where(['city_id' => $city['CityId'], 'start_rating' => '5'])->limit(15)->get();

         // $hotels = DB::select("SELECT *, ( 3959 * acos( cos( radians(".$input['Latitude'].") ) * cos( radians( lat ) ) 
         //                * cos( radians( lng ) - radians(".$input['Longitude'].") ) + sin( radians(".$input['Latitude'].") ) * sin(radians(lat)) ) ) AS distance 
         //            FROM static_data_hotels
         //            HAVING distance < 50
         //            ORDER BY distance LIMIT 15");

        // $hotels = array_map(function ($value) {
        //     return (array)$value;
        // }, $hotels);

        if(empty($hotels)){
            $hotels = StaticDataHotels::where(['city_id' => $city['CityId'], 'start_rating' => '5'])->limit(15)->get();
        }
        

        $input['city_id'] = (isset($city['CityId'])) ? $city['CityId'] : $input['city_id'];
        $input['departdate'] = date('d-m-Y', strtotime($input['departdate']));
        $input['returndate'] = date('d-m-Y', strtotime($input['returndate']));
        
        $title = ' Top Hotel In '. $input['city_name'] .' 2021';

        //echo "<pre>";print_r($input);echo "</pre>";

        $isILS = false;

        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {
            $location = \Location::get($this->end_user_ip);
        }



        if (isset($location) && isset($location->countryCode)) {

            if ($location->countryCode == 'IL') {
                $isILS = true;
            }
        }

        return view('search.flights-hotels.results')->with(['hotels' => $hotels, 'input' => $input, 'referral' => $_GET['referral'], 'title' => $title,'isAgent' => $isAgent, 'input_data' => $input,'isILS' => $isILS ]);
        //}
    }

    public function searchFlightHotels(Request $request) {

        $postJson = file_get_contents('php://input');
        $postArray = json_decode($postJson, true);
        $input = array();
        foreach ($postArray as $post) {
            foreach ($post as $key => $p) {
                $input[$key] = urldecode($p);
            }
        }


        if (isset($input['ishalal']) && $input['ishalal'] == 1) {
            Session::put('active_tab', 'halal');
        } else {
            Session::put('active_tab', 'flightshotels');
        }


        $startdate = $input['departdate'];
        $returndate = $input['returndate'];

        //get country from IP
        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {

            $location = \Location::get($this->end_user_ip);
        }

        $country = $location->countryCode;

        //get user currency
        $countryInfo = Currencies::where('code', $location->countryCode)->first();

        $currencyCode = $location->countryCode;
        //$currency = $countryInfo['currency_code'];
        $ourCurrency = Config::get('ourcurrency');

        if (in_array($countryInfo['currency_code'], $ourCurrency)) {
            $currency= $countryInfo['currency_code'];
        } else {
            $currency= 'USD';
        }
        

        $input['user_country'] = (isset($location->countryName) && $location->countryName != '') ? $location->countryName : $countryInfo['name'];

        $selectedGuests = $input['roomsGuests'];


        if (isset($input['countryCode']) && !empty($input['countryCode'])) {
            $input['countryCode'] = $input['countryCode'];
            $input['city_name'] = $input['city_name'];
        } else {

        }



        $date = Carbon::createFromDate($startdate);
        $now = Carbon::createFromDate($returndate);

        $noOfNights = $date->diffInDays($now);

        $roomguests = array();

        $input['NoOfNights'] = $noOfNights;

        $input['currency'] = $currency;

        $input['CheckInDate'] = str_replace("-", "/", $input['departdate']);

        $noOfRooms = $input['roomCount'];


        $roomGuests = array();
        $total_guests = 0;

        $adultCount = 0;
        $childCount = 0;


        for ($i = 1; $i <= $noOfRooms; $i++) {
            $childAges = array();

            if (isset($input['c' . $i]) && $input['c' . $i] > 0) {

                for ($ca = 1; $ca <= $input['c' . $i]; $ca++) {
                    array_push($childAges, $input['ca' . $ca . 'r' . $i]);
                    $input['ca' . $ca . 'r' . $i] = $input['ca' . $ca . 'r' . $i];
                }

                if (isset($childAges) && sizeof($childAges) > 0) {

                    array_push($roomGuests, array(
                        'NoOfAdults' => $input['a' . $i],
                        'NoOfChild' => $input['c' . $i],
                        'ChildAge' => $childAges
                    ));

                    $input['adultCountRoom' . $i] = $input['a' . $i];
                    $input['childCountRoom' . $i] = $input['c' . $i];
                    
                    if($input['a'. $i] > 0){
                         $adultCount =  $adultCount+ $input['a' . $i];
                    }

                    if($input['c'. $i] > 0){
                        $childCount =  $childCount+ $input['c' . $i];
                    }

                    $total_guests = $total_guests + $input['a' . $i] + $input['c' . $i];
                } else {
                    array_push($roomGuests, array(
                        'NoOfAdults' => $input['a' . $i],
                        'NoOfChild' => $input['c' . $i],
                        'ChildAge' => null
                    ));

                    $input['adultCountRoom' . $i] = $input['a' . $i];
                    $input['childCountRoom' . $i] = $input['c' . $i];
                    
                    if($input['a'. $i] > 0){
                       $adultCount =  $adultCount+ $input['a' . $i];
                    }

                    if($input['c'. $i] > 0){
                        $childCount =  $childCount+ $input['c' . $i];
                    }

                    $total_guests = $total_guests + $input['a' . $i] + $input['c' . $i];
                }
            } else {
                array_push($roomGuests, array(
                    'NoOfAdults' => (isset($input['a' . $i])) ? $input['a' . $i] : 0,
                    'NoOfChild' => 0,
                    'ChildAge' => null
                ));

                $input['adultCountRoom' . $i] = $input['a' . $i];
                $input['childCountRoom' . $i] = $input['c' . $i];

                if($input['a'. $i] > 0){
                    $adultCount =  $adultCount+ $input['a' . $i];
                }


                if($input['c'. $i] > 0){
                    $childCount =  $childCount+ $input['c' . $i];
                }

                $total_guests = $total_guests + $input['a' . $i] + $input['c' . $i];
            }
        }

        if(Session::get('locale') == 'heb') {
            $input['roomsGuests'] = 'חדרים' .' ' . $input['roomCount'] . 'אורחים' .' '.$total_guests;
        } else {

            $input['roomsGuests'] = $input['roomCount'] . ' Rooms ' . $total_guests . ' Guests';
        }
        //Session::put('hotelSearchInput', $input);
        $this->api = new TBOHotelAPI();
        $this->flightapi = new TBOFlightAPI();

        if (strpos(date('d/m/Y', strtotime($input['CheckInDate'])), '1970') === false) {
            //$input['CheckInDate'] = date('d/m/Y', strtotime($input['CheckInDate']));
        }

        
        //if (isset($input['Latitude']) && !empty($input['Latitude'])) {
        if (isset($input["preffered_hotel"]) && $input["preffered_hotel"] != '') {
           // echo $input['Latitude']. " ";die;
            
            $postData = [
                "CheckInDate" => $input['CheckInDate'],
                "NoOfNights" => $input['NoOfNights'],
                "CountryCode" => $input['countryCode'],
                "CityId" => $input['city_id'],
                "ResultCount" => null,
                "PreferredCurrency" => $currency,
                "GuestNationality" => $country,
                "NoOfRooms" => $noOfRooms,
                "RoomGuests" => $roomGuests,
                "MaxRating" => 5,
                "MinRating" => 2,
                "ReviewScore" => null,
                "IsTBOMapped" => true,
                "IsNearBySearchAllowed" => false,
                "EndUserIp" => $this->api->userIP,
                "TokenId" => $this->api->tokenId,
                "HotelCode" => $input["preffered_hotel"]
            ];

        } else {

            //echo $input['city_id']. " ";die;

            $postData = [
                "CheckInDate" => $input['CheckInDate'],
                "Latitude" => $input['Latitude'],
                "Longitude" => $input['Longitude'],
                "Radius" => $input['Radius'],
                "NoOfNights" => $input['NoOfNights'],
                "CountryCode" => $input['countryCode'],
                // "CityId" => $input['city_id'],
                "ResultCount" => null,
                "PreferredCurrency" => $currency,
                "GuestNationality" => $country,
                "NoOfRooms" => $noOfRooms,
                "RoomGuests" => $roomGuests,
                "MaxRating" => 5,
                "MinRating" => 2,
                "ReviewScore" => null,
                "IsTBOMapped" => true,
                "IsNearBySearchAllowed" => false,
                "EndUserIp" => $this->api->userIP,
                "TokenId" => $this->api->tokenId
            ];
        }

        $hotels_list = $this->api->hotelSearch($postData);


        $Segments = array();

        array_push($Segments, array(
            "Origin" => $input['origin'],
            "Destination" => $input['destination'],
            "FlightCabinClass" => 1,
            "PreferredDepartureTime" => date('Y-m-d', strtotime($input['departdate'])) . "T00:00:00",
            "PreferredArrivalTime" => date('Y-m-d', strtotime($input['departdate'] . ' +1 day')) . "T00:00:00"
        ));
        array_push($Segments, array(
            "Origin" => $input['destination'],
            "Destination" => $input['origin'],
            "FlightCabinClass" => 1,
            "PreferredDepartureTime" => date('Y-m-d', strtotime($input['returndate'])) . "T00:00:00",
            "PreferredArrivalTime" => date('Y-m-d', strtotime($input['returndate'] . ' +1 day')) . "T00:00:00"
        ));



        $postDataF = [
            "EndUserIp" => $this->api->userIP,
            "TokenId" => $this->api->tokenId,
            "AdultCount" => $adultCount,
            "ChildCount" => $childCount,
            "InfantCount" => "0",
            "DirectFlight" => "false",
            "OneStopFlight" => "false",
            "PreferredCurrency" => $input['currency'],
            "JourneyType" => "2",
            "PreferredAirlines" => null,
            "Segments" => $Segments,
            "Sources" => null
        ];


        $flights = $this->flightapi->search($postDataF);

        $commisioninisFlight = env('INIS_VAL_FLIGHT');

        $commisioninis_currency = env('INR_FEES');

        if (isset($flights['Response']) && isset($flights['Response']['ResponseStatus']) && $flights['Response']['ResponseStatus'] == 1) {

            $fprice = $flights['Response']['Results'][0][0]['Fare']['OfferedFare'];

            if(isset($flights['Response']['Results'][1][0]['Fare']['OfferedFare']) && $flights['Response']['Results'][1][0]['Fare']['OfferedFare'] != ''){
              $fprice =  $flights['Response']['Results'][0][0]['Fare']['OfferedFare'] +  $flights['Response']['Results'][1][0]['Fare']['OfferedFare'];
            }

            $inis_markup_flight = (($commisioninisFlight / 100) * $fprice);
            $price_with_markup_flight = $inis_markup_flight + $fprice;

            //$taxes_flight = ($commisioninis_currency / 100) * $price_with_markup_flight;
            //$flightPrice = $inis_markup_flight + $taxes_flight + $fprice;

            if($currency == 'ILS'){

                $inis_markup_flight = ((env('INIS_VAL_PAYME') / 100) * $fprice);
                $price_with_markup_flight = $inis_markup_flight + $fprice;

                $taxes_flight = (env('PAYME_FEES') / 100) * $price_with_markup_flight;
                $flightPrice = $inis_markup_flight + $taxes_flight + $fprice;
                //$taxes = $taxes + env('PAYME_FIX_FEES');

            }else{

                $taxes_flight = ($commisioninis_currency / 100) * $price_with_markup_flight;
                $flightPrice = $inis_markup_flight + $taxes_flight + $fprice;
            }

            if($currency == 'ILS'){

              $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes_flight + env('PAYME_FIX_FEES') );

              $flightPrice = $flightPrice + env('PAYME_FIX_FEES') + $vat;

            }



            if(isset($flights['Response']['Results'][1][0]) && $flights['Response']['Results'][1][0] != ''){


            }else{

            }


            $fileName = time() + (60 * 13);
            $postDataF['input'] = $input;
            $fileContents = json_encode(array('request' => $postDataF, 'response' => $flights['Response']));
            $this->saveSearchData($fileName . '.json', $fileContents);
            
        
        }else{

           $flightPrice = 0; 
           $flights['Response'] = array();
        
        }
             

        $input['countryName'] = str_replace('+', ' ', $input['countryName']);
        $input['roomsGuests'] = str_replace('+', ' ', $input['roomsGuests']);
        $traceId = '';


        if (isset($hotels_list['HotelSearchResult']) && isset($hotels_list['HotelSearchResult']['ResponseStatus']) && $hotels_list['HotelSearchResult']['ResponseStatus'] == 1) {

            //set session expirey cookie
            $this->setCookie('hotel_session', time() + (60 * 13), 20);
            $this->setCookie('hotel_city', $input['city_id'], 20);

            $hotels = $hotels_list['HotelSearchResult']['HotelResults'];
            $traceId = $hotels_list['HotelSearchResult']['TraceId'];
            $results_hotels = array();
            $ameneties_array = array();
            $room_ameneties_array = array();
            $locations = array();

            $unrated = 0;
            $t_star = 0;
            $th_star = 0;
            $f_star = 0;
            $fi_star = 0;

            $unrated_t = 0;
            $tp_one = 0;
            $tp_one_h = 0;
            $tp_two = 0;
            $tp_two_h = 0;
            $tp_three = 0;
            $tp_three_h = 0;
            $tp_four = 0;
            $tp_four_h = 0;
            $tp_five = 0;

            $isHalal = 0;

            $hotel_in_array = array();

            foreach ($hotels as $h_key => $hotel) {
                array_push($hotel_in_array, $hotel['HotelCode']);
            }

            

            $db_hotels = StaticDataHotels::select('hotel_location', 'hotel_code', 'hotel_images', 'start_rating', 'hotel_facilities', 'hotel_address', 'hotel_name', 'id', 'ishalal', 'tp_ratings', 'hotel_info', 'room_amenities','lat', 'lng')->whereIn('hotel_code', $hotel_in_array)->get();

            foreach ($db_hotels as $key => $d_hotel) {

                foreach ($hotels as $h_key => $hotel) {

                    if($d_hotel['hotel_code'] == $hotel['HotelCode']) {
                        if (isset($hotel['IsTBOMapped']) && $hotel['IsTBOMapped'] == true) {

                            if (isset($input['referral']) && $input['referral'] != '') {

                                $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request
                                        ->referral])
                                    ->first();
                                if (isset($checkrefferal)) {

                                    $commisioninis = env('INIS_VAL');
                                } else {
                                    $commisioninis = env('INIS_VAL');
                                }

                                $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $hotel['HotelCode'] . "'");

                                if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                                    $commisioninis = 10;
                                }
                            } else {

                                $commisioninis = env('INIS_VAL');
                            }

                            $tdsVal = ((env('INIS_TDS') / 100) * ( $hotel['Price']['OfferedPriceRoundedOff'] ));

                            $inis_markup = (($commisioninis / 100) * $hotel['Price']['OfferedPriceRoundedOff'] + $tdsVal);
                            $price_with_markup = $inis_markup + $hotel['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                            if($currency == 'ILS'){

                                $inis_markup = ((env('INIS_VAL_PAYME') / 100) * ( $hotel['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                                $price_with_markup = $inis_markup + $hotel['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                                $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;
                                //$taxes = $taxes + env('PAYME_FIX_FEES');

                            }else{

                                $taxes = ($commisioninis_currency / 100) * $price_with_markup;
                            }


                            $hotel['FinalPrice'] = round( $inis_markup + $taxes + $hotel['Price']['OfferedPriceRoundedOff'] + $tdsVal ,2);
                            $hotel['discount'] = rand(0,25);


                            if($currency == 'ILS'){

                              $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                              $hotel['FinalPrice'] = $hotel['FinalPrice'] + env('PAYME_FIX_FEES') + $vat;

                            }

                            //$hotel['hotelprice'] = $hotel['FinalPrice'];
                            //$hotel['flightprice'] = $flightPrice;
                            $hotel['FinalPrice'] = $hotel['FinalPrice'] + $flightPrice;


                            if (isset($input['Latitude']) && !empty($input['Latitude'])) {
                                $hfilter['hotel_code'] = $hotel['HotelCode'];
                            } else {
                                $hfilter['hotel_code'] = $hotel['HotelCode'];
                                //$hfilter['city_id'] = $input['city_id'];
                            }
                            $static_data = $d_hotel;
                            
                            if (isset($static_data)) {

                                if($d_hotel['hotel_location'] == '' || empty($d_hotel['hotel_location'])) {
                                    $loc = array();
                                    $loc['@Latitude'] = $d_hotel['lat'];
                                    $loc['@Longitude'] = $d_hotel['lng'];
                                }

                                if(isset($static_data['hotel_location'])) {
                                   
                                    $loc = json_decode($static_data['hotel_location'], true);

                                    if($loc == '' || empty($loc)) {
                                        $loc = array();
                                        $loc['@Latitude'] = $d_hotel['lat'];
                                        $loc['@Longitude'] = $d_hotel['lng'];
                                    }
                                    if (isset($input['Longitude']) && !empty($input['Longitude']) && isset($input['Latitude']) && !empty($input['Latitude']) && isset($loc['@Latitude']) && !empty($loc['@Latitude']) && isset($loc['@Longitude']) && !empty($loc['@Longitude'])) {

                                        $static_data['distance'] = $this->getDistance($input['Latitude'], $input['Longitude'], $loc['@Latitude'], $loc['@Longitude'], 'K');
                                    }
                                }

                                $hotel['h_rating'] = ($static_data['start_rating'] != null) ? (int) $static_data['start_rating'] : 0;

                                if (isset($d_hotel['hotel_images']) && !empty($d_hotel['hotel_images'])) {

                                    $d_hotel['hotel_images'] = json_decode($d_hotel['hotel_images']);
                                    if (isset($d_hotel['hotel_images']) && !empty($d_hotel['hotel_images'][0])) {

                                        // $d_hotel['hotel_images'] = $d_hotel['hotel_images'][0];
                                        $content = @file_get_contents($d_hotel['hotel_images'][0]);
                                        if($content !== FALSE) {
                                            $d_hotel['hotel_images'] = $d_hotel['hotel_images'][0];
                                        } else {
                                            $roomImages = RoomImages::select('images')->where(['sub_domain' => $hotel['HotelCode']])->whereNotNull('images')->first();
                                            if(isset($roomImages) && !empty($roomImages)) {
                                                $r_images = unserialize($roomImages['images']);
                                                $d_hotel['hotel_images'] = ($r_images && $r_images[0]) ? $r_images[0] : 'https://via.placeholder.com/250X150?text=Image%20Not%20Available';
                                            } else {
                                                $d_hotel['hotel_images'] = 'https://via.placeholder.com/250X150?text=Image%20Not%20Available';
                                            }
                                        }
                                    } else {

                                        $roomImages = RoomImages::select('images')->where(['sub_domain' => $hotel['HotelCode']])->whereNotNull('images')->first();
                                        if(isset($roomImages) && !empty($roomImages)) {
                                            $r_images = unserialize($roomImages['images']);
                                           
                                            $d_hotel['hotel_images'] = ($r_images && $r_images[0]) ? $r_images[0] : 'https://via.placeholder.com/250X150?text=Image%20Not%20Available';
                                        } else {
                                            $d_hotel['hotel_images'] = 'https://via.placeholder.com/250X150?text=Image%20Not%20Available';
                                        }
                                    }
                                }

                                if (isset($static_data['hotel_facilities']) && !empty($static_data['hotel_facilities'])) {
                                    $static_data['hotel_facilities'] = json_decode($static_data['hotel_facilities']);

                                    if (isset($static_data['hotel_facilities']) && !empty($static_data['hotel_facilities'])) {
                                        $tmp_arr = array();
                                        foreach ($static_data['hotel_facilities'] as $key => $fac) {
                                            if ($key <= 4) {
                                                array_push($tmp_arr, $fac);
                                                if(!in_array($fac, $ameneties_array)) {
                                                    array_push($ameneties_array, $fac);
                                                }
                                            }

                                        }

                                        $static_data['hotel_facilities'] = $tmp_arr;
                                    }
                                }

                                if (isset($static_data['room_amenities']) && !empty($static_data['room_amenities'])) {
                                    $static_data['room_amenities'] = json_decode($static_data['room_amenities']);

                                    if (isset($static_data['room_amenities']) && !empty($static_data['room_amenities'])) {
                                        $tmp_arr = array();
                                        foreach ($static_data['room_amenities'] as $key => $r_amn) {
                                            if ($key <= 4) {
                                             //   array_push($tmp_arr, $fac);
                                                if(!in_array($r_amn, $room_ameneties_array)) {
                                                    array_push($room_ameneties_array, $r_amn);
                                                }
                                            }

                                        }

                                        //$static_data['hotel_facilities'] = $tmp_arr;
                                    }
                                }


                                if (isset($static_data['hotel_address']) && !empty($static_data['hotel_address'])) {
                                    $static_data['hotel_address'] = json_decode($static_data['hotel_address'], true);

                                    // echo "<pre>";
                                    // print_r($static_data['hotel_address']);
                                    if(isset($static_data['hotel_address']['CityName']) && $static_data['hotel_address']['CityName'] != '') {
                                        //$static_data['hotel_address']['CityName'] = str_replace("-", " ", $static_data['hotel_address']['CityName']);
                                        if(sizeof($locations) > 0) {
                                            $check_loc = false;
                                            foreach ($locations as $key => $loc) {
                                                if(strtolower(str_replace("-", " ", $loc['name'])) == strtolower(str_replace("-", " ", $static_data['hotel_address']['CityName']))) {
                                                    $locations[$key]['hotels'] = $locations[$key]['hotels'] + 1;
                                                    $check_loc = true;
                                                }
                                            }

                                            if(!$check_loc) {
                                                array_push($locations, array('name' => $static_data['hotel_address']['CityName'], 'hotels' => 1));
                                            }
                                        } else {
                                            array_push($locations, array('name' => $static_data['hotel_address']['CityName'], 'hotels' => 1));
                                        }
                                    }
                                }

                                if (isset($static_data['hotel_location']) && !empty($static_data['hotel_location'])) {
                                    $static_data['hotel_location'] = json_decode($static_data['hotel_location']);
                                }


                                unset($hotel['room_amenities']);
                                unset($hotel['HotelName']);
                                unset($hotel['HotelCategory']);
                                unset($hotel['HotelDescription']);
                                unset($hotel['HotelPromotion']);
                                unset($hotel['HotelPolicy']);
                                unset($hotel['IsTBOMapped']);
                                unset($hotel['HotelAddress']);
                                unset($hotel['HotelContactNo']);
                                unset($hotel['HotelMap']);
                                unset($hotel['Latitude']);
                                unset($hotel['Longitude']);
                                unset($hotel['HotelLocation']);
                                unset($hotel['SupplierPrice']);
                                unset($hotel['RoomDetails']);
                                unset($hotel['Price']['GST']);
                                unset($hotel['Price']['RoomPrice']);
                                unset($hotel['Price']['Tax']);
                                unset($hotel['Price']['ExtraGuestCharge']);
                                unset($hotel['Price']['ChildCharge']);
                                unset($hotel['Price']['OtherCharges']);
                                unset($hotel['Price']['Discount']);
                                unset($hotel['Price']['AgentCommission']);
                                unset($hotel['Price']['AgentMarkUp']);
                                unset($hotel['Price']['ServiceTax']);
                                unset($hotel['Price']['TCS']);
                                unset($hotel['Price']['TDS']);
                                unset($hotel['Price']['ServiceCharge']);
                                unset($hotel['Price']['TotalGSTAmount']);

                                $add_hotel = true;
                                if(sizeof($results_hotels) == 0) {
                                    $add_hotel = true;
                                } else {

                                    foreach ($results_hotels as $key => $r_hotel) {
                                        
                                        if($r_hotel['TBO_data']['HotelCode'] == $hotel['HotelCode']) {
                                            $add_hotel = false;    
                                            continue;
                                        }

                                    }
                                }

                                if($add_hotel) {
                                    array_push($results_hotels, array(
                                        'TBO_data' => $hotel,
                                        'static_data' => $static_data
                                    ));

                                    if ($static_data['ishalal'] == 'yes') {
                                        $isHalal++;
                                    }

                                    if ($hotel['h_rating'] == 0) {

                                        $unrated++;
                                    }

                                    if ($hotel['h_rating'] == 2) {

                                        $t_star++;
                                    }



                                    if ($hotel['h_rating'] == 3) {

                                        $th_star++;
                                    }

                                    if ($hotel['h_rating'] == 4) {

                                        $f_star++;
                                    }

                                    if ($hotel['h_rating'] == 5) {

                                        $fi_star++;
                                    }

                                    if($static_data['tp_ratings'] == '0.0') {
                                        $unrated_t++;
                                    }
                                    if($static_data['tp_ratings'] == '1.0') {
                                        $tp_one++;
                                    }
                                    if($static_data['tp_ratings'] == '1.5') {
                                        $tp_one_h++;
                                    }
                                    if($static_data['tp_ratings'] == '2.0') {
                                        $tp_two++;
                                    }
                                    if($static_data['tp_ratings'] == '2.5') {
                                        $tp_two_h++;
                                    }
                                    if($static_data['tp_ratings'] == '3.0') {
                                        $tp_three++;
                                    }
                                    if($static_data['tp_ratings'] == '3.5') {
                                        $tp_three_h++;
                                    }
                                    if($static_data['tp_ratings'] == '4.0') {
                                        $tp_four++;
                                    }
                                    if($static_data['tp_ratings'] == '4.5') {
                                        $tp_four_h++;
                                    }
                                    if($static_data['tp_ratings'] == '5.0') {
                                        $tp_five++;
                                    }
                                }

                            } else {

                                $hotel['h_rating'] = isset($hotel['StarRating']) ? (int) $hotel['StarRating'] : 0;
                            }

                            continue;
                        }

                    }
                }
                
            }

            if (isset($input['Longitude']) && !empty($input['Longitude'])) {

                uasort($results_hotels, function ($a, $b) {
                    if (isset($a['static_data']['distance']) && isset($b['static_data']['distance'])) {
                        if ($a['static_data']['distance'] == $b['static_data']['distance']) {
                            return 0;
                        }
                        return ($a['static_data']['distance'] < $b['static_data']['distance']) ? -1 : 1;
                    } else {
                        return 0;
                    }
                });
            } else {
                uasort($results_hotels, function ($a, $b) {
                    if ($a['TBO_data']['h_rating'] == $b['TBO_data']['h_rating']) {
                        return 0;
                    }
                    return ($a['TBO_data']['h_rating'] > $b['TBO_data']['h_rating']) ? -1 : 1;
                });
            }



            $counter = 1;
            $array_send = array();

            $request->session()->forget('hotels_list');
            Session::put('hotels_list', serialize($results_hotels));

            foreach ($results_hotels as $h_key => $hotel) {
                array_push($array_send, $hotel);

                if ($counter >= 1000) {
                    break;
                }

                $counter++;
            }

            //Session::put('hotels_counter', $counter);

            if ($input['referral'] != '') {

                $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();
                if (isset($checkrefferal)) {

                    $commisioninis = env('INIS_VAL');
                } else {

                    $commisioninis = env('INIS_VAL');
                }
            } else {

                $commisioninis = env('INIS_VAL');
            }

            

            if (isset($flights['Response']) && isset($flights['Response']['ResponseStatus']) && $flights['Response']['ResponseStatus'] == 1) {

                $fileNameHotel = time() + (60 * 13);
                $fileContents = json_encode(array('request' => $input, 'response' => $results_hotels));
                $this->saveSearchDataHotel($fileNameHotel . '.json', $fileContents);


                return response()->json(array(
                        'hotels' => $array_send,
                        'flights' => $flights['Response'],
                        'input_data' => $input,
                        'status' => (sizeof($array_send) > 0) ? true : false,
                        'hotel_count' => sizeof($results_hotels),
                        'unrated' => $unrated,
                        't_star' => $t_star,
                        'th_star' => $th_star,
                        'f_star' => $f_star,
                        'isHalal' => $isHalal,
                        'fi_star' => $fi_star,
                        'unrated_t' => $unrated_t,
                        'tp_one' => $tp_one,
                        'tp_one_h' => $tp_one_h,
                        'tp_two' => $tp_two,
                        'tp_two_h' => $tp_two_h,
                        'tp_three' => $tp_three,
                        'tp_three_h' => $tp_three_h,
                        'tp_four' => $tp_four,
                        'tp_four_h' => $tp_four_h,
                        'tp_five' => $tp_five,
                        'ameneties_array' => $ameneties_array,
                        'room_ameneties_array' => $room_ameneties_array,
                        'locations' => $locations,
                        'traceId' => $traceId,
                        'commission_inis' => $commisioninis,
                        'referral' => $input['referral'],
                        'search_id' => $fileName,
                        'search_id_hotel' => $fileNameHotel,
                        'lottery_Limit' => Session::get('lotteryLimit')
                ));

            }

            
        } else {

            if(isset($hotels_list['HotelSearchResult']) && isset($hotels_list['HotelSearchResult']['Error']) && isset($hotels_list['HotelSearchResult']['Error']['ErrorMessage']) && $hotels_list['HotelSearchResult']['Error']['ErrorMessage'] == 'InValid Session') {

                $url = env('API_HOST') . env('API_AUTH_URL');
                  $postData  = [
                      'ClientId' => env('ClientId'),
                      'UserName' => env('API_UserName'),
                      'Password' => env('API_Password'),
                      'EndUserIp' => $this->api->userIP,
                  ];

                  $response = Http::post($url, $postData);
                  $tokenResponse =  $response->json();
                  
                  $tokenId = $tokenResponse['TokenId'];

                  Token::where(['mode' => env('API_MODE_HOTEL')])
                        ->update(['token' => $tokenId]);
            }

            $this->writeLogs($postData, $hotels_list['HotelSearchResult']);
            Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode($postData), json_encode($hotels_list['HotelSearchResult']) ));
            return response()->json(array(
                        'hotels' => $hotels_list,
                        'flights' => array(),
                        'input_data' => $input,
                        'status' => false,
                        'hotel_count' => 0,
                        'traceId' => $traceId
            ));
        }
    }

    function getDistance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return round(($miles * 1.609344), 2);
        } else if ($unit == "N") {
            return round(($miles * 0.8684), 2);
        } else {
            return round($miles, 2);
        }
    }

    public function loadMoreHotels(Request $request) {

        $search_id = $request->city_id;
        $search_contents = json_decode($this->readSearchDataHotel($search_id.'.json'), true);
        // echo "<pre>"; print_r($search_contents); die();
        $hotels = $search_contents['response'];//unserialize(Session::get('hotels_list_' . $s_city));

        //$hotels = unserialize(Session::get('hotels_list'));
        $hotels_counter = $request->size; //Session::get('hotels_counter');
        $size = $hotels_counter + 20;
        //$previous_hotels = $request->size;
        $results_hotels = array();
        $counter = $hotels_counter;
        $counter_temp = 1;
        $price_filter = $request->price;
        $distance_filter = $request->distance;
        $array_ratings = array();
        $array_tp_ratings = array();
        $array_h_amns = array();
        $array_h_loc = array();

        $z_star = 0;
        $on_star = 0;
        $t_star = 0;
        $th_star = 0;
        $f_star = 0;
        $fi_star = 0;

        if ($request->has('ratings')) {
            $ratings = $request->ratings;

            if ($ratings != '' && $ratings != null) {
                $all_ratings = explode(",", $ratings);

                if (isset($all_ratings)) {
                    foreach ($all_ratings as $key => $rate) {
                        $r = explode("-star", $rate);
                        if (isset($r) && isset($r[0])) {
                            array_push($array_ratings, $r[0]);
                        }
                    }
                } else {
                   array_push($array_ratings, $ratings); 
                }
            }
        }

        

        if ($request->has('tpratings')) {
            $tpratings = $request->tpratings;

            if ($tpratings != '' && $tpratings != null) {
                $tp_ratings = explode(",", $tpratings);

                if (isset($tp_ratings)) {
                    foreach ($tp_ratings as $key => $rate) {
                        $r = explode("-star", $rate);
                        if (isset($r) && isset($r[0])) {
                            array_push($array_tp_ratings, $r[0]);
                        }
                    }
                } else {
                    array_push($array_tp_ratings, $tpratings);
                }
            }
        }

        if ($request->has('hAmns')) {
            $hAmns = $request->hAmns;

            if ($hAmns != '' && $hAmns != null) {
                $h_amns = explode(",", $hAmns);
                      
                if (isset($h_amns)) {
                    foreach ($h_amns as $key => $rate) {
                        $r = explode("hamn-", $rate);
                        if (isset($r) && isset($r[1])) {
                            array_push($array_h_amns, $r[1]);
                        }
                    }
                } else {
                    array_push($array_h_amns, $hAmns);
                }
            }
        }

        if ($request->has('hloc')) {
            $hloc = $request->hloc;
            if ($hloc != '' && $hloc != null) {
                $h_loc = explode(",", $hloc);
                      
                if (isset($h_loc)) {
                    foreach ($h_loc as $key => $rate) {
                        $r = explode("loc-", $rate);
                        if (isset($r) && isset($r[1])) {
                            array_push($array_h_loc, strtolower(str_replace("-", " ", $r[1])));
                        }
                    }
                } else {
                    array_push($array_h_loc, strtolower(str_replace("-", " ", $hloc)));
                }
            }
        }

        //echo "<pre>"; print_r($array_h_loc); die();
        foreach ($hotels as $h_key => $hotel) {

            if ($request->has('htypes')) {
                $htltypes = (array) $request->htypes;

                if (in_array("halal", $htltypes) && $hotel['static_data']['ishalal'] != 'yes') {
                    continue;
                }
            }

            // echo 'temp ' . $counter_temp . ' ' . $hotels_counter . "\n";
            if ($counter_temp <= $hotels_counter) {
                $counter_temp++;
                continue;
            }


            if (intval($price_filter) < 500000) {

                if ($hotel['TBO_data']['Price']['OfferedPriceRoundedOff'] < $price_filter) {
                    
                    if (isset($array_ratings)) {

                        if (in_array($hotel['TBO_data']['h_rating'], $array_ratings)  && floatval($hotel['static_data']['distance']) < $distance_filter) {

                            $counter_temp++;
                            if ($hotels_counter == 0 || $hotels_counter > 10) {
                                array_push($results_hotels, $hotel);
                            }
                        }
                    } else if(isset($array_tp_ratings)) {
                        if (in_array($hotel['static_data']['tp_ratings'], $array_tp_ratings)  && floatval($hotel['static_data']['distance']) < $distance_filter) {

                            $counter_temp++;
                            if ($hotels_counter == 0 || $hotels_counter > 10) {
                                array_push($results_hotels, $hotel);
                            }
                        }

                    } else if(isset($array_h_amns)) {
                        
                        if(isset($hotel['static_data']['hotel_facilities']) && !empty($hotel['static_data']['hotel_facilities'])) {
                            
                            $hotel_matched = false;
                            foreach($hotel['static_data']['hotel_facilities'] as $h_fac) {
                                if (in_array($h_fac, $array_h_amns)) {
                                    $hotel_matched = true;
                                    continue;
                                }
                            }
                            
                            if($hotel_matched  && floatval($hotel['static_data']['distance']) < $distance_filter) {
                                 $counter_temp++;
                                if ($hotels_counter == 0 || $hotels_counter > 10) {
                                    array_push($results_hotels, $hotel);
                                }
                            }
                        }

                    } else if(isset($array_h_loc)) {
                        
                        if(isset($hotel['static_data']['hotel_address']) && !empty($hotel['static_data']['hotel_address'])) {
                            
                            if (in_array(strtolower(str_replace("-", " ", $hotel['static_data']['hotel_address']['CityName'])), $array_h_loc)  && floatval($hotel['static_data']['distance']) < $distance_filter) {

                                $counter_temp++;
                                if ($hotels_counter == 0 || $hotels_counter > 10) {
                                    array_push($results_hotels, $hotel);
                                }
                            }
                        }

                    } else if(floatval($hotel['static_data']['distance']) < $distance_filter){
                        $counter_temp++;
                        array_push($results_hotels, $hotel);
                    }
                }
            } else {
               
                if (isset($array_ratings) && sizeof($array_ratings) > 0) {
                    if (in_array($hotel['TBO_data']['h_rating'], $array_ratings) && floatval($hotel['static_data']['distance']) < $distance_filter) {

                        $counter_temp++;
                        if ($hotels_counter == 0 || $hotels_counter > 10) {
                            array_push($results_hotels, $hotel);
                        }
                    }
                } else if(isset($array_tp_ratings) && sizeof($array_tp_ratings) > 0) {
                    if (in_array($hotel['static_data']['tp_ratings'], $array_tp_ratings) && floatval($hotel['static_data']['distance']) < $distance_filter) {

                        $counter_temp++;
                        if ($hotels_counter == 0 || $hotels_counter > 10) {
                            array_push($results_hotels, $hotel);
                        }
                    }

                } else if(isset($array_h_amns) && sizeof($array_h_amns) > 0) {

                        if(isset($hotel['static_data']['hotel_facilities']) && !empty($hotel['static_data']['hotel_facilities'])) {
                            
                            $hotel_matched = false;
                            foreach($hotel['static_data']['hotel_facilities'] as $h_fac) {
                                if (in_array($h_fac, $array_h_amns)) {
                                    $hotel_matched = true;
                                    continue;
                                }
                            }
                            
                            if($hotel_matched && floatval($hotel['static_data']['distance']) < $distance_filter) {
                                 $counter_temp++;
                                if ($hotels_counter == 0 || $hotels_counter > 10) {
                                    array_push($results_hotels, $hotel);
                                }
                            }
                        }

                } else if(isset($array_h_loc) && sizeof($array_h_loc) > 0) {

                    if(isset($hotel['static_data']['hotel_address']) && isset($hotel['static_data']['hotel_address']['CityName'])) {
                        if (in_array(strtolower(str_replace("-", " ", $hotel['static_data']['hotel_address']['CityName'])), $array_h_loc) && floatval($hotel['static_data']['distance']) < $distance_filter) {

                            $counter_temp++;
                            if ($hotels_counter == 0 || $hotels_counter > 10) {
                                array_push($results_hotels, $hotel);
                            }
                        }
                    }

                } else if(floatval($hotel['static_data']['distance']) < $distance_filter){

                    $counter_temp++;
                    array_push($results_hotels, $hotel);
                }
            }
            $counter++;

            if ($counter_temp > $size) {
                break;
            }
        }

        if ($hotels_counter > $counter) {

            return response()->json(array(
                        'hotels' => []
            ));
        } else {

            return response()->json(array(
                        'hotels' => $results_hotels
            ));
        }
    }

    public function viewFlightHotel2(Request $request) {

        $hotel_code = $request->code;
        $checkin_date = $request->checkIn;
        $rooms_count = $request->rooms;
        $city_id = $request->city_id;
        $total_nights = $request->nights;
        $referral = $request->referral;
        $traceId = $request->traceId;
        $flightTraceId = $request->flightTraceId;
        $search_id = $request->searchId;
        $flightId = $request->flightID;
        $rflightId = $request->rflightID;

        //echo $request->searchId;die;

        $destinationPathFlight=public_path()."/logs/searches/flights/" . $search_id . '.json';


        if (file_exists($destinationPathFlight)){

            $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);


            $flightdata = $search_contents['response']['Results'][0][$flightId];

            $flightdataAll =  $search_contents['response']['Results'][0];
            unset($flightdataAll[0]);


            $flightReturndata = array();
            $flightReturndataAll = array();


            if(isset($search_contents['response']['Results'][1][$rflightId])){

                $flightReturndata = $search_contents['response']['Results'][1][$rflightId];
            }

            if(isset($search_contents['response']['Results'][1])){

                $flightReturndataAll = $search_contents['response']['Results'][1];
                unset($flightReturndataAll[0]);        
            }
        }


        $date = date('Y-m-d');
        $now = date('Y-m-d', strtotime($checkin_date));
        if ($date >= $now) {
            $new_date = date('d-m-Y', strtotime('+5 days'));
            $checkin_date = $new_date;
        }

        if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

            $lottery_Limit = env('LOTTERY_ELIGIBILITY');

            $location = json_decode($this->getCookie('th_country'));

            $countryInfo = Currencies::where('code', $location->countryCode)->first();

            $myCurrency = $countryInfo['currency_code'];


            if (!empty(trim($myCurrency))) {
                $lAmount = Currency::convert('USD', $myCurrency, $lottery_Limit);
                if (isset($lAmount) && isset($lAmount['convertedAmount'])) {

                    $lottery_Limit = round($lAmount['convertedAmount']);
                    Session::put('lotteryLimit', $lottery_Limit);
                } else {
                    Session::put('lotteryLimit', $lottery_Limit);
                }
            } else {
                Session::put('lotteryLimit', $lottery_Limit);
            }
        } else {

            $location = \Location::get($this->end_user_ip);

            if ($location) {

                $countryInfo = Currencies::where('code', $location->countryCode)->first();
                Session::put('CurrencyCode', $countryInfo['currency_code']);
                $this->setCookie('th_country', json_encode($location), time() + 60 * 60 * 24 * 10);
                $myCurrency = $countryInfo['currency_code']; //Session::get('CurrencyCode');
            } else {
                Session::put('CurrencyCode', 'USD');
                //$this->setCookie('th_country', 'US', time() + 60 * 60 * 24 * 10);
                $myCurrency = 'USD';
            }

            $lottery_Limit = env('LOTTERY_ELIGIBILITY');

            if (!empty(trim($myCurrency))) {
                $lAmount = Currency::convert('USD', $myCurrency, $lottery_Limit);
                if (isset($lAmount) && isset($lAmount['convertedAmount'])) {

                    $lottery_Limit = round($lAmount['convertedAmount']);
                    Session::put('lotteryLimit', $lottery_Limit);
                } else {
                    Session::put('lotteryLimit', $lottery_Limit);
                }
            } else {
                Session::put('lotteryLimit', $lottery_Limit);
            }
        }

        Session::put('CurrencyCode', $myCurrency);

        if (!empty(strtolower(Session::get('CountryCode'))) || strtolower(Session::get('CountryCode')) == '') {
            $city_info = StaticDataHotels::where('hotel_code', $hotel_code)->select('city_id')->first();

            if (!empty($city_info)) {
                $country_info = Cities::where('CityId', $city_info['city_id'])->select('CountryCode')->first();
                if (!empty($country_info)) {
                    Session::put('CountryCode', strtolower($country_info['CountryCode']));
                }
            }
        }


        $destinationPath=public_path()."/logs/searches/hotels/" . $city_id . '.json';


        if (file_exists($destinationPath)){

            $hotels = json_decode($this->readSearchDataHotel($city_id.'.json'), true);
            
            $selected_hotel = array();
            $supplierIds = '';

            
            foreach ($hotels['response'] as $key => $hotel) {

                if ($hotel['TBO_data']['HotelCode'] == $hotel_code) {
                    $selected_hotel = $hotel;

                    if (empty($hotel['static_data']['hotel_name'])) {
                        $hotel['static_data'] = StaticDataHotels::where(['hotel_code' => $selected_hotel['TBO_data']['HotelCode']])->first();
                    }
                    Session::put('hotelName', $hotel['static_data']['hotel_name']);
                    Session::put('hotelCode', $hotel['TBO_data']['HotelCode']);
                    Session::put('resultIndex', $hotel['TBO_data']['ResultIndex']);

                    if (isset($hotel['TBO_data']['SupplierHotelCodes'])) {
                        foreach ($hotel['TBO_data']['SupplierHotelCodes'] as $s_id) {
                            $supplierIds = $supplierIds . "-" . $s_id['CategoryIndex'];
                        }
                    }
                }
            }

            if ($supplierIds == '') {
                $supplierIds = "-0";
            }

            if (empty($selected_hotel)) {
                $this->emptySession();
                return view('500')->with(['error' => 'No rooms availblefor selected hotel, please try with different booking dates.']);
            }


            $static_data = StaticDataHotels::select('hotel_location', 'hotel_code', 'hotel_images', 'start_rating', 'hotel_facilities', 'hotel_address', 'hotel_name', 'id', 'ishalal', 'tp_ratings', 'hotel_info', 'hotel_time', 'lat', 'lng')->where(['hotel_code' => $selected_hotel['TBO_data']['HotelCode']])->first();
            
            if (isset($static_data)) {

                Session::put('hotelName', $static_data['hotel_name']);

                if (isset($static_data['hotel_images']) && !empty($static_data['hotel_images'])) {
                    $static_data['hotel_images'] = json_decode($static_data['hotel_images'], true);
                }

                if (isset($static_data['hotel_facilities']) && !empty($static_data['hotel_facilities'])) {
                    $static_data['hotel_facilities'] = json_decode($static_data['hotel_facilities'], true);
                }

                if (isset($static_data['attractions']) && !empty($static_data['attractions'])) {
                    $static_data['attractions'] = json_decode($static_data['attractions'], true);
                }

                if (isset($static_data['hotel_description']) && !empty($static_data['hotel_description'])) {
                    $static_data['hotel_description'] = json_decode($static_data['hotel_description'], true);
                }

                if (isset($static_data['hotel_location']) && !empty($static_data['hotel_location'])) {
                    $static_data['hotel_location'] = json_decode($static_data['hotel_location'], true);
                    if(isset($static_data['hotel_location']) && isset($static_data['hotel_location']['@Latitude'])) {
                        $static_data['lat'] = $static_data['hotel_location']['@Latitude'];
                        $static_data['lng'] = $static_data['hotel_location']['@Longitude'];
                    }
                }

                if (isset($static_data['hotel_address']) && !empty($static_data['hotel_address'])) {
                    $static_data['hotel_address'] = json_decode($static_data['hotel_address'], true);
                }

                if (isset($static_data['hotel_contact']) && !empty($static_data['hotel_contact'])) {
                    $static_data['hotel_contact'] = json_decode($static_data['hotel_contact'], true);
                }

                if (isset($static_data['hotel_time']) && !empty($static_data['hotel_time'])) {
                    $static_data['hotel_time'] = json_decode($static_data['hotel_time'], true);
                }

                if (isset($static_data['hotel_type']) && !empty($static_data['hotel_type'])) {
                    $static_data['hotel_type'] = json_decode($static_data['hotel_type'], true);
                }

                if(isset($static_data['hotel_info']) && !empty($static_data['hotel_info'])) {
                    $static_data['hotel_info'] = json_decode($static_data['hotel_info'], true);
                }

                $selected_hotel['static_data'] = $static_data;
            }

            if (empty($selected_hotel['static_data'])) {
                $selected_hotel['static_data'] = array('hotel_name' => '');
            }

            $roomImages = RoomImages::where('sub_domain', $selected_hotel['TBO_data']['HotelCode'])->get();

            if (!is_array($selected_hotel['static_data']['hotel_images']) || count($selected_hotel['static_data']['hotel_images']) < 6) {

                /* get room images */
                $r_images = array();
                $roomImagesCount = 0;
                foreach ($roomImages as $room_img) {
                    if ($room_img->images && $room_img->images != null && !empty($room_img->images)) {

                        array_push($r_images, unserialize($room_img->images));
                    }
                }

                $roomPhotos = [];
                foreach ($r_images as $r_key => $r_imgs) {
                    foreach ($r_imgs as $r_key => $r_img) {
                        if (strpos($r_img, "_z") !== false) {
                            $roomPhotos[] = $r_img;
                            $roomImagesCount++;
                        }
                    }
                }

                $selected_hotel['static_data']['hotel_images'] = $roomPhotos;
            }

            $queryValues = $request->query();

            //check if shared from social media
            if(!isset($queryValues['a1'])) {
                $queryValues = array();
                for($i = 1; $i <= $rooms_count; $i++) {
                    $queryValues['a' . $i] = '2';
                    $queryValues['c' . $i] = '0';
                }
            }

            $queryVals = '';
            foreach ($queryValues as $q_k => $q) {
                $queryVals = $queryVals . $q_k . '=' . $q . '&';
            }
            
            $request_input = $hotels['request'];
           
            $title = $selected_hotel['static_data']['hotel_name'].' - '. $request_input['city_name'] .' - ' . $request_input['countryName'];

            $commisioninis = env('INIS_VAL_FLIGHT');
            $conversion = env('INR_FEES');


            $s_location = Cities::where('CityId', $city_id)->first();
            $s_name = isset($s_location) ? $s_location['CityName'] : $request_input['city_name'];

            $isILS = false;

            if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
                
                $location = json_decode($this->getCookie('th_country'));

            } else {
                $location = \Location::get($this->end_user_ip);
            }



            if (isset($location) && isset($location->countryCode)) {

                if ($location->countryCode == 'IL') {
                    $isILS = true;
                }
            }

            
            return view('search.flights-hotels.view-hotel2')
                            ->with(['hotel' => $selected_hotel, 'total_nights' => $total_nights, 'roomImages' => $roomImages, 'referral' => $referral, 'supplierIds' => $supplierIds, 'hotel_code' => $hotel_code, 'image_count' => 0, 'traceId' => $traceId, 'static_data' => $selected_hotel['static_data'], 'queryVals' => $queryVals,'search_id_hotel' => $city_id, 'title' => $title, 'flightdata' => $flightdata,'flightdataAll' => $flightdataAll, 'flightReturndata' => $flightReturndata, 'flightReturndataAll' => $flightReturndataAll, 'flightTraceId' => $flightTraceId, 'flight_search_id' => $search_id, 'flightId' => $flightId, 'rflightId' => $rflightId, 'iniscomm' => $commisioninis, 's_city' => $city_id, 's_name' => $s_name, 'conversion' => $conversion, 'input_data' => $hotels['request'],'isILS' => $isILS ]);
        } else {
            //new search
            //get city id from hotel code
            $city_data = StaticDataHotels::where('hotel_code', $hotel_code)->select('city_id')->first();
            $city_id = $city_data['city_id'];
            $this->setCookie('hotel_city', $city_id, 20);
            $this->setCookie('thns', '0', 20);
            $queryValues = $request->query();

            //check if shared from social media
            if(!isset($queryValues['a1'])) {
                $queryValues = array();
                for($i = 1; $i <= $rooms_count; $i++) {
                    $queryValues['a' . $i] = '2';
                    $queryValues['c' . $i] = '0';
                }
            }

            
            $queryVals = '';
            $input = array();
            $input['departdate'] = $checkin_date;
            $input['returndate'] = date('d-m-Y', strtotime($checkin_date . ' + ' . $total_nights . ' days'));
            $input['roomCount'] = $rooms_count;
            $input['city_id'] = $city_id;
            $input["preffered_hotel"] = $hotel_code;

            foreach ($queryValues as $key => $q) {
                $queryVals = $queryVals . $key . '=' . $q . '&';
            }

            $startdate = $input['departdate'];
            $returndate = $input['returndate'];

            //get country from IP
            if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
                $location = json_decode($this->getCookie('th_country'));

            } else {

                $location = \Location::get($this->end_user_ip);
            }

            $country = $location->countryCode;

            //get user currency
            $countryInfo = Currencies::where('code', $location->countryCode)->first();
            //$input['currency'] = $countryInfo['currency_code'];
            $ourCurrency = Config::get('ourcurrency');

            if (in_array($countryInfo['currency_code'], $ourCurrency)) {
                $input['currency']= $countryInfo['currency_code'];
            } else {
                $input['currency']= 'USD';
            }
            

            $searchCountry = Cities::where('CityId', $city_id)->first();
            // echo "<pre>"; print_r($searchCountry); die();
            $input['countryCode'] = $searchCountry['CountryCode'];
            $input['city_name'] = $searchCountry['CityName'];
            $input['Location'] = $searchCountry['CityName'];
            $currencyCode = $location->countryCode;
            $currency = $input['currency'];
            $input['countryName'] = (isset($location->countryName) && $location->countryName != '') ? $location->countryName : $countryInfo['name'];
            $input['user_country'] = (isset($location->countryName) && $location->countryName != '') ? $location->countryName : $countryInfo['name'];

            $date = Carbon::createFromDate($startdate);
            $now = Carbon::createFromDate($returndate);

            $noOfNights = $date->diffInDays($now);

            $roomguests = array();

            $input['NoOfNights'] = $noOfNights;

            $input['CheckInDate'] = str_replace("-", "/", $input['departdate']);

            $noOfRooms = $input['roomCount'];

            $roomGuests = array();
            $total_guests = 0;
            // echo "<pre>"; print_r($queryValues); echo "</pre>";
            for ($i = 1; $i <= $noOfRooms; $i++) {
                $childAges = array();


                if (isset($queryValues['c' . $i]) && $queryValues['c' . $i] > 0) {

                    for ($ca = 1; $ca <= $queryValues['c' . $i]; $ca++) {
                        array_push($childAges, $queryValues['ca' . $ca . 'r' . $i]);
                        $input['ca' . $ca . 'r' . $i] = $queryValues['ca' . $ca . 'r' . $i];
                    }

                    if (empty($childAges) && sizeof($childAges) > 0) {

                        array_push($roomGuests, array(
                            'NoOfAdults' => $queryValues['a' . $i],
                            'NoOfChild' => 0,
                            'ChildAge' => [5]
                        ));
                        $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                        $input['childCountRoom' . $i] = $queryValues['c' . $i];

                        $total_guests = $total_guests + $queryValues['a' . $i] + $queryValues['c' . $i];
                    } else {
                        array_push($roomGuests, array(
                            'NoOfAdults' => $queryValues['a' . $i],
                            'NoOfChild' => $queryValues['c' . $i],
                            'ChildAge' => $childAges
                        ));
                        $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                        $input['childCountRoom' . $i] = $queryValues['c' . $i];

                        $total_guests = $total_guests + $queryValues['a' . $i] + $queryValues['c' . $i];
                    }
                } else {
                    array_push($roomGuests, array(
                        'NoOfAdults' => (isset($queryValues['c' . $i])) ? $queryValues['a' . $i] : 0,
                        'NoOfChild' => 0,
                        'ChildAge' => null
                    ));
                    $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                    $input['childCountRoom' . $i] = $queryValues['c' . $i];

                    $total_guests = $total_guests + $queryValues['a' . $i] + $queryValues['c' . $i];
                }
            }

            $input['roomsGuests'] = $input['roomCount'] . ' Rooms ' . $total_guests . ' Guests';
            // echo "<pre>"; print_r($input); die();
            //Session::put('hotelSearchInput', $input);

            //make new seach to API
            $this->api = new TBOHotelAPI();
            $postData = ["CheckInDate" => $input['CheckInDate'], "NoOfNights" => $noOfNights, "CountryCode" => $input['countryCode'], "CityId" => $input['city_id'], "ResultCount" => null, "PreferredCurrency" => $currency, "GuestNationality" => $country, "NoOfRooms" => $noOfRooms, "RoomGuests" => $roomGuests, "MaxRating" => 5, "MinRating" => 3, "ReviewScore" => null, "IsTBOMapped" => true, "IsNearBySearchAllowed" => false, "EndUserIp" => $this
                ->api->userIP, "TokenId" => $this
                ->api->tokenId, "HotelCode" => $input["preffered_hotel"]];

            try {
                $hotels = $this
                        ->api
                        ->hotelSearch($postData);

                if (isset($hotels['HotelSearchResult']) && isset($hotels['HotelSearchResult']['ResponseStatus']) && $hotels['HotelSearchResult']['ResponseStatus'] == 1) {

                    if (sizeof($hotels['HotelSearchResult']['HotelResults']) > 0) {

                        //set session expirey cookie
                        $this->setCookie('hotel_session', time() + (60 * 13), 20);

                        $results_hotels = array();
                        $hotel = $hotels['HotelSearchResult']['HotelResults'][0];
                        $traceId = $hotels['HotelSearchResult']['TraceId'];

                        if (isset($hotel['IsTBOMapped']) && $hotel['IsTBOMapped'] == true) {
                            $static_data = StaticDataHotels::where(['city_id' => $input['city_id'], 'hotel_code' => $hotel['HotelCode']])->first();

                            if (isset($static_data)) {

                                $hotel['h_rating'] = ($static_data['start_rating'] != null) ? (int) $static_data['start_rating'] : 0;

                                if (isset($static_data['hotel_images']) && !empty($static_data['hotel_images'])) {
                                    $static_data['hotel_images'] = json_decode($static_data['hotel_images']);
                                }

                                if (isset($static_data['hotel_facilities']) && !empty($static_data['hotel_facilities'])) {
                                    $static_data['hotel_facilities'] = json_decode($static_data['hotel_facilities']);
                                }

                                if (isset($static_data['attractions']) && !empty($static_data['attractions'])) {
                                    $static_data['attractions'] = json_decode($static_data['attractions']);
                                }

                                if (isset($static_data['hotel_description']) && !empty($static_data['hotel_description'])) {
                                    $static_data['hotel_description'] = json_decode($static_data['hotel_description']);
                                }

                                if (isset($static_data['hotel_location']) && !empty($static_data['hotel_location'])) {
                                    $static_data['hotel_location'] = json_decode($static_data['hotel_location']);
                                }

                                if (isset($static_data['hotel_address']) && !empty($static_data['hotel_address'])) {
                                    $static_data['hotel_address'] = json_decode($static_data['hotel_address']);
                                }

                                if (isset($static_data['hotel_contact']) && !empty($static_data['hotel_contact'])) {
                                    $static_data['hotel_contact'] = json_decode($static_data['hotel_contact']);
                                }

                                if (isset($static_data['hotel_time']) && !empty($static_data['hotel_time'])) {
                                    $static_data['hotel_time'] = json_decode($static_data['hotel_time']);
                                }

                                if (isset($static_data['hotel_type']) && !empty($static_data['hotel_type'])) {
                                    $static_data['hotel_type'] = json_decode($static_data['hotel_type']);
                                }
                                array_push($results_hotels, array(
                                    'TBO_data' => $hotel,
                                    'static_data' => $static_data
                                ));
                            } else {

                                $hotel['h_rating'] = isset($hotel['StarRating']) ? (int) $hotel['StarRating'] : 0;

                                array_push($results_hotels, array(
                                    'TBO_data' => $hotel,
                                    'static_data' => $static_data
                                ));
                            }
                        }

                        $fileName = time() + (60 * 13);
                        $fileContents = json_encode(array('request' => $input, 'response' => $results_hotels));
                        $this->saveSearchDataHotel($fileName . '.json', $fileContents);


                        return redirect('/findflighthotels?_token=dXNfgIPCrv9UyPSAm8nqGQmMsnUvHE5iXuSQsqlZ&origin='.$request->origin.'&from='.$request->from.'&destination='.$request->destination.'&to='.$request->to.'&ishalal=0&city_name_select='.$input['city_name'].' ('.$input['countryCode'].') &city_id='.$fileName.'&city_name='.$input['city_name'].'&countryCode='.$input['countryCode'].'&countryName='.$input['countryName'].'&preffered_hotel='.$input["preffered_hotel"].'&ishalal=0&departdate='.$input['departdate'].'&returndate='.$input['returndate'].'&roomsGuests='.$input['roomsGuests'].'&'.$queryVals.'roomCount='.$rooms_count.'&referral='.$referral.'');

                    } else {

                        $this->writeLogs($postData, $hotels['HotelSearchResult']);
                        $this->emptySession();
                        return view('500')->with(['error' => 'No results found for your search.']);
                    }
                } else {

                    $this->emptySession();
                    return view('500')->with(['error' => $hotels['HotelSearchResult']['Error']['ErrorMessage']]);
                }
            } catch (Exception $e) {

                $this->emptySession();
                return view('500')->with(['error' => $e->getMessage()]);
            }
        }
    }

    public function hotelRooms(Request $request) {

        //get hotel rooms from API

        $flightTraceId = $request->flightTraceId;
        $commisioninisFlight = env('INIS_VAL_FLIGHT');

        $commisioninis_currency = env('INR_FEES');

        $search_id = $request->searchId;
        $flightId = $request->flightID;
        $rflightId = $request->rflightID;
        $search_id_hotel = $request->city_id;

        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);


        $flightdata = $search_contents['response']['Results'][0][$flightId];



        $flightReturndata = array();


        if(isset($search_contents['response']['Results'][1][$rflightId])){

            $flightReturndata = $search_contents['response']['Results'][1][$rflightId];
        }


        $lottery_Limit = env('LOTTERY_ELIGIBILITY');
        
        if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

            $location = json_decode($this->getCookie('th_country'));

            $countryInfo = Currencies::where('code', $location->countryCode)->first();

            $myCurrency = $countryInfo['currency_code'];

            $location = $_COOKIE['th_country'];
            if (!empty(trim($myCurrency))) {
                if (!empty(trim($myCurrency)) && (empty(Session::get('lotteryLimit')) || Session::get('lotteryLimit') !='' || !Session::get('lotteryLimit'))) {
                   $lAmount = Currency::convert('USD', $myCurrency, $lottery_Limit);
                   if (isset($lAmount) && isset($lAmount['convertedAmount'])) {

                       $lottery_Limit = round($lAmount['convertedAmount']);
                       Session::put('lotteryLimit', $lottery_Limit);
                   } else {
                       Session::put('lotteryLimit', $lottery_Limit);
                   }
               }
           } else {
               Session::put('lotteryLimit', $lottery_Limit);
           }
        } else {

            $location = \Location::get($this->end_user_ip);

            if ($location) {

                $countryInfo = Currencies::where('code', $location->countryCode)->first();
                Session::put('CurrencyCode', $countryInfo['currency_code']);
                $this->setCookie('th_country', json_encode($location), time() + 60 * 60 * 24 * 10);
                $myCurrency = $countryInfo['currency_code']; //Session::get('CurrencyCode');
            } else {
                Session::put('CurrencyCode', 'USD');
                $this->setCookie('th_country', 'US', time() + 60 * 60 * 24 * 10);
                $myCurrency = 'USD';
            }


            if (!empty(trim($myCurrency))) {
                if (!empty(trim($myCurrency)) && (empty(Session::get('lotteryLimit')) || Session::get('lotteryLimit') !='' || !Session::get('lotteryLimit'))) {
                    $lAmount = Currency::convert('USD', $myCurrency, $lottery_Limit);
                    if (isset($lAmount) && isset($lAmount['convertedAmount'])) {

                        $lottery_Limit = round($lAmount['convertedAmount']);
                        Session::put('lotteryLimit', $lottery_Limit);
                    } else {
                        Session::put('lotteryLimit', $lottery_Limit);
                    }
                }
            } else {
                Session::put('lotteryLimit', $lottery_Limit);
            }
        }

        $fprice = $flightdata['Fare']['OfferedFare'];

        if(isset($flightReturndata['Fare']['OfferedFare']) && $flightReturndata['Fare']['OfferedFare'] != ''){
          $fprice =   $flightdata['Fare']['OfferedFare'] +  $flightReturndata['Fare']['OfferedFare'];
        }

        $inis_markup_flight = (($commisioninisFlight / 100) * $fprice);
        $price_with_markup_flight = $inis_markup_flight + $fprice;
        //$taxes_flight = ($commisioninis_currency / 100) * $price_with_markup_flight;
        //$flightPrice = $inis_markup_flight + $taxes_flight + $fprice;

        if(Session::get('CurrencyCode') == 'ILS'){

            $inis_markup_flight = ((env('INIS_VAL_PAYME') / 100) * $fprice);
            $price_with_markup_flight = $inis_markup_flight + $fprice;

            $taxes_flight = (env('PAYME_FEES') / 100) * $price_with_markup_flight;
            $flightPrice = $inis_markup_flight + $taxes_flight + $fprice;
            //$taxes = $taxes + env('PAYME_FIX_FEES');

        }else{

            $taxes_flight = ($commisioninis_currency / 100) * $price_with_markup_flight;
            $flightPrice = $inis_markup_flight + $taxes_flight + $fprice;
        }

        if(Session::get('CurrencyCode') == 'ILS'){

          $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes_flight + env('PAYME_FIX_FEES') );

          $flightPrice = $flightPrice + env('PAYME_FIX_FEES') + $vat;

        }

        
        $supplierCategories = array();

        $hotel_code = $request->hotelCode;

        $destinationPath=public_path()."/logs/searches/hotels/" . $search_id_hotel . '.json';

        if (file_exists($destinationPath)){
            
            $hotels = json_decode($this->readSearchDataHotel($search_id_hotel.'.json'), true);

        } else {

            return response()->json(array(
                        'rooms' => 'Your session has expired, please refresh the page to get rooms list.',
                        'status' => false
            ));
        }


        //$hotels = unserialize(Session::get('hotels_list'));
        $selected_hotel = array();
        $supplierIds = '';
        foreach ($hotels['response'] as $key => $hotel) {
            if ($hotel['TBO_data']['HotelCode'] == $hotel_code) {
                $selected_hotel = $hotel;
                if (isset($hotel['TBO_data']['SupplierHotelCodes'])) {
                    foreach ($hotel['TBO_data']['SupplierHotelCodes'] as $s_id) {
                        array_push($supplierCategories, $s_id['CategoryIndex']);
                    }
                }
            }
        }

        // echo "<pre>"; print_r($selected_hotel); die();
        $rooms_array = array();
        $roomArr = array();
        $this->api = new TBOHotelAPI();
        $rooms = $this
                ->api
                ->hotelRooms($request->hotelCode, $selected_hotel['TBO_data']['ResultIndex'], $request->traceId, $supplierCategories);

        $combination_type = "";

        //if(isset($rooms['GetHotelRoomResult']))
        // echo "<pre>"; print_r($rooms); die();
        if (!empty($rooms['GetHotelRoomResult']) && !empty($rooms['GetHotelRoomResult']['RoomCombinationsArray'])) {
            $this->hotelRoomsCombination = array();
            foreach ($rooms['GetHotelRoomResult']['RoomCombinationsArray'] as $key => $roomC) {

                array_push($this->hotelRoomsCombination, array(
                    'CategoryId' => $roomC['CategoryId'],
                    'InfoSource' => $roomC['InfoSource'],
                    'RoomCombination' => $roomC['RoomCombination']
                ));
            }
        }


        $imagesAll = RoomImages::where('sub_domain', $request->hotelCode)->get();
        foreach ($imagesAll as $key => $value) {
            $imagesAll[$key]['images'] = unserialize($value['images']);
        }

        $room_images_size = sizeof($imagesAll);
        $image_counter = 0;

        if ($request->referral != '') {

            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request
                        ->referral])
                    ->first();
            if (isset($checkrefferal)) {

                $commisioninis = env('INIS_VAL');
            } else {
                $commisioninis = env('INIS_VAL');
            }
        } else {

            $commisioninis = env('INIS_VAL');
        }

        if ($request->referral && $request->referral != '' && $request->referral != '0') {
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $request->hotelCode . "'");
        }

        if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
            $commisioninis = 10;
        }

        $commisioninis_currency = env('INR_FEES');

        if ($rooms['GetHotelRoomResult']['ResponseStatus'] == 1) {

            $inclusion_array = array();
            if ($hotels['request']['roomCount'] == 1) {

                foreach ($rooms['GetHotelRoomResult']['HotelRoomsDetails'] as $key => $room) {
                    $room['RoomTypeName'] = ucwords(strtolower(trim($room['RoomTypeName'])));
                    $room['CancellationPolicy'] = str_replace("#^#", " ", $room['CancellationPolicy']);
                    $room['CancellationPolicy'] = str_replace("#", " ", $room['CancellationPolicy']);
                    $room['CancellationPolicy'] = str_replace("|", " ", $room['CancellationPolicy']);
                    $room['CancellationPolicy'] = str_replace("#!#", " ", $room['CancellationPolicy']);
                    $room['CancellationPolicy'] = str_replace("!", " ", $room['CancellationPolicy']);

                    unset($room['ChildCount']);
                    unset($room['RequireAllPaxDetails']);
                    unset($room['RoomId']);
                    unset($room['RoomStatus']);
                    unset($room['RatePlan']);
                    unset($room['InfoSource']);
                    unset($room['DayRates']);
                    unset($room['IsPerStay']);
                    unset($room['SupplierPrice']);
                    unset($room['RoomPromotion']);
                    unset($room['HotelSupplements']);


                    $tdsVal = ((env('INIS_TDS') / 100) * ( $room['Price']['OfferedPriceRoundedOff'] ));
                    $inis_markup = (($commisioninis / 100) * $room['Price']['OfferedPriceRoundedOff'] + $tdsVal);
                    $price_with_markup = $inis_markup + $room['Price']['OfferedPriceRoundedOff'] + $tdsVal;
                    //$taxes = ($commisioninis_currency / 100) * $price_with_markup;
                    //$room['FinalPrice'] = round($inis_markup + $taxes + $room['Price']['OfferedPriceRoundedOff'] + $tdsVal + $flightPrice,2);

                    if(Session::get('CurrencyCode') == 'ILS'){

                        $inis_markup = ((env('INIS_VAL_PAYME') / 100) * ( $room['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                        $price_with_markup = $inis_markup + $room['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                        $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;
                        //$taxes = $taxes + env('PAYME_FIX_FEES');

                    }else{

                        $taxes = ($commisioninis_currency / 100) * $price_with_markup;
                    }


                    $room['FinalPrice'] = round( $inis_markup + $taxes + $room['Price']['OfferedPriceRoundedOff'] + $tdsVal ,2);
                    //$hotel['discount'] = rand(0,25);


                    if(Session::get('CurrencyCode') == 'ILS'){

                      $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                      $room['FinalPrice'] = $room['FinalPrice'] + env('PAYME_FIX_FEES') + $vat;

                    }

                    $room['FinalPrice'] = $room['FinalPrice'] + $flightPrice;


                    if ($room_images_size > 0 && isset($imagesAll) && isset($imagesAll[$image_counter])) {
                        $room['images'] = $imagesAll[$image_counter]['images'];
                        $image_counter++;
                    }


                    if ($image_counter == $room_images_size) {
                        $image_counter = 0;
                    }

                    if (sizeof($rooms_array) > 0) {

                        $id = $this->searchForId($room['RoomTypeName'], $rooms_array);

                        if ($id !== 'empty') {

                            // echo "<pre>";
                            if(isset($room['Inclusion']) && isset($room['Inclusion'][0])) {
                                
                                // print_r($room['Inclusion'][0]);
                                // echo "<br>";
                                $currentType = $room['Inclusion'][0];
                                $add_room = true;
                                foreach ($rooms_array[$id]['sub_rooms'] as $key => $s_room) {
                                    // echo "<br>";
                                    // print_r($s_room['Inclusion'][0]);

                                    if(isset($s_room['Inclusion']) && isset($s_room['Inclusion'][0]) && strtolower(str_replace(",", "",$room['Inclusion'][0])) == strtolower(str_replace(",", "",$s_room['Inclusion'][0]))) {

                                        $add_room = false;
                                    }
                                }

                                if($add_room) {
                                    array_push($rooms_array[$id]['sub_rooms'], $room);
                                }

                            } else {
                              //  $room['Inclusion'] = array('Room Only');
                               // array_push($rooms_array[$id]['sub_rooms'], $room);

                            }

                        } else {
                            // if(empty($room['Inclusion'])){
                            //     $room['Inclusion'] = array('Room Only');
                            // }
                            array_push($rooms_array, array(
                                'RoomTypeName' => $room['RoomTypeName'],
                                'Inclusion' => $room['Inclusion'],
                                'sub_rooms' => array(
                                    $room
                                )
                            ));
                        }
                    } else {

                        array_push($rooms_array, array(
                            'RoomTypeName' => $room['RoomTypeName'],
                            'Inclusion' => $room['Inclusion'],
                            'sub_rooms' => array(
                                $room
                            )
                        ));

                    }

                    if ($room['SmokingPreference'] == 'NoPreference') {
                        $room['SmokingPreference'] = 0;
                    } else if ($room['SmokingPreference'] == 'Smoking') {
                        $room['SmokingPreference'] = 1;
                    } else if ($room['SmokingPreference'] == 'NonSmoking') {
                        $room['SmokingPreference'] = 2;
                    } else {
                        $room['SmokingPreference'] = 3;
                    }
                }
            } else {
                $rooms_temp_array = array();
                for ($i = 0; $i < $hotels['request']['roomCount']; $i++) {

                    $rooms_temp_array['rooms_' . $i] = array();
                }

                foreach ($this->hotelRoomsCombination as $key => $room_combination) {

                    $room_category = $room_combination['CategoryId'];
                    if ($room_combination['InfoSource'] == 'OpenCombination') {

                        foreach ($room_combination['RoomCombination'] as $k => $combinations) {

                            for ($r = 0; $r < sizeof($combinations['RoomIndex']); $r++) {

                                foreach ($rooms['GetHotelRoomResult']['HotelRoomsDetails'] as $key => $roomC) {

                                    $roomC['CancellationPolicy'] = str_replace("#^#", " ", $roomC['CancellationPolicy']);
                                    $roomC['CancellationPolicy'] = str_replace("#", " ", $roomC['CancellationPolicy']);
                                    $roomC['CancellationPolicy'] = str_replace("|", " ", $roomC['CancellationPolicy']);
                                    $roomC['CancellationPolicy'] = str_replace("#!#", " ", $roomC['CancellationPolicy']);
                                    $roomC['CancellationPolicy'] = str_replace("!", " ", $roomC['CancellationPolicy']);

                                    unset($roomC['ChildCount']);
                                    unset($roomC['RequireAllPaxDetails']);
                                    unset($roomC['RoomId']);
                                    unset($roomC['RoomStatus']);
                                    unset($roomC['RatePlan']);
                                    //unset($roomC['InfoSource']);
                                    unset($roomC['DayRates']);
                                    unset($roomC['IsPerStay']);
                                    unset($roomC['SupplierPrice']);
                                    unset($roomC['RoomPromotion']);
                                    unset($roomC['HotelSupplements']);

                                    $tdsVal = ((env('INIS_TDS') / 100) * ( $roomC['Price']['OfferedPriceRoundedOff'] ));
                                    $inis_markup = (($commisioninis / 100) * $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal);
                                    $price_with_markup = $inis_markup + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal;
                                    //$taxes = ($commisioninis_currency / 100) * $price_with_markup;
                                    //$roomC['FinalPrice'] = round($inis_markup + $taxes + $roomC['Price']['OfferedPriceRoundedOff'] + $room['Price']['TDS'],2);

                                    if(Session::get('CurrencyCode') == 'ILS'){

                                        $inis_markup = ((env('INIS_VAL_PAYME') / 100) * ( $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                                        $price_with_markup = $inis_markup + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                                        $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;
                                        //$taxes = $taxes + env('PAYME_FIX_FEES');

                                    }else{

                                        $taxes = ($commisioninis_currency / 100) * $price_with_markup;
                                    }


                                    $roomC['FinalPrice'] = round( $inis_markup + $taxes + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal ,2);
                                    //$hotel['discount'] = rand(0,25);


                                    if(Session::get('CurrencyCode') == 'ILS'){

                                      $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                                      $roomC['FinalPrice'] = $roomC['FinalPrice'] + env('PAYME_FIX_FEES') + $vat;

                                    }

                                    if ($room_images_size > 0 && isset($imagesAll) && isset($imagesAll[$image_counter])) {
                                        $roomC['images'] = $imagesAll[$image_counter]['images'];
                                        $image_counter++;
                                    }

                                    if ($image_counter == $room_images_size) {
                                        $image_counter = 0;
                                    }
                                    if ($roomC['CategoryId'] == $room_category && $roomC['RoomIndex'] == $combinations['RoomIndex'][$r]) {

                                        //only push fixed rooms
                                        if (sizeof($rooms_temp_array['rooms_' . $k]) > 0) {
                                            if (isset($rooms_temp_array['rooms_' . $k][0]) && $rooms_temp_array['rooms_' . $k][0]['InfoSource'] != 'FixedCombination') {
                                                array_push($rooms_temp_array['rooms_' . $k], $roomC);
                                            }
                                        } else {

                                            array_push($rooms_temp_array['rooms_' . $k], $roomC);
                                        }

                                        if ($roomC['SmokingPreference'] == 'NoPreference') {
                                            $roomC['SmokingPreference'] = 0;
                                        } else if ($roomC['SmokingPreference'] == 'Smoking') {
                                            $roomC['SmokingPreference'] = 1;
                                        } else if ($roomC['SmokingPreference'] == 'NonSmoking') {
                                            $roomC['SmokingPreference'] = 2;
                                        } else {
                                            $roomC['SmokingPreference'] = 3;
                                        }
                                    }

                                }
                            }
                        }

                        $combination_type = "open";
                        //break;
                    } else {
                        foreach ($room_combination['RoomCombination'] as $k => $combinations) {
                            for ($r = 0; $r < sizeof($combinations['RoomIndex']); $r++) {
                                foreach ($rooms['GetHotelRoomResult']['HotelRoomsDetails'] as $key => $roomC) {

                                    unset($roomC['ChildCount']);
                                    unset($roomC['RequireAllPaxDetails']);
                                    unset($roomC['RoomId']);
                                    unset($roomC['RoomStatus']);
                                    unset($roomC['RatePlan']);
                                    //unset($roomC['InfoSource']);
                                    unset($roomC['DayRates']);
                                    unset($roomC['IsPerStay']);
                                    unset($roomC['SupplierPrice']);
                                    unset($roomC['RoomPromotion']);
                                    unset($roomC['HotelSupplements']);

                                    $tdsVal = ((env('INIS_TDS') / 100) * ( $roomC['Price']['OfferedPriceRoundedOff'] ));
                                    $inis_markup = (($commisioninis / 100) * $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal);
                                    $price_with_markup = $inis_markup + $roomC['Price']['OfferedPriceRoundedOff']  + $tdsVal;
                                    //$taxes = ($commisioninis_currency / 100) * $price_with_markup;
                                    //$roomC['FinalPrice'] = round($inis_markup + $taxes + $roomC['Price']['OfferedPriceRoundedOff'],2);

                                    if(Session::get('CurrencyCode')== 'ILS'){

                                        $inis_markup = ((env('INIS_VAL_PAYME') / 100) * ( $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                                        $price_with_markup = $inis_markup + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                                        $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;
                                        //$taxes = $taxes + env('PAYME_FIX_FEES');

                                    }else{

                                        $taxes = ($commisioninis_currency / 100) * $price_with_markup;
                                    }


                                    $roomC['FinalPrice'] = round( $inis_markup + $taxes + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal ,2);
                                    //$hotel['discount'] = rand(0,25);


                                    if(Session::get('CurrencyCode')== 'ILS'){

                                      $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                                      $roomC['FinalPrice'] = $roomC['FinalPrice'] + env('PAYME_FIX_FEES') + $vat;

                                    }


                                    if ($room_images_size > 0 && isset($imagesAll) && isset($imagesAll[$image_counter])) {
                                        $roomC['images'] = $imagesAll[$image_counter]['images'];
                                        $image_counter++;
                                    }

                                    if ($image_counter == $room_images_size) {
                                        $image_counter = 0;
                                    }

                                    if ($roomC['CategoryId'] == $room_category && $roomC['RoomIndex'] == $combinations['RoomIndex'][$r]) {
                                        //only push fixed rooms
                                        if (sizeof($rooms_temp_array['rooms_' . $r]) > 0) {
                                            if (isset($rooms_temp_array['rooms_' . $r][0]) && isset($rooms_temp_array['rooms_' . $r][0]['InfoSource']) && $rooms_temp_array['rooms_' . $r][0]['InfoSource'] != 'OpenCombination') {
                                                array_push($rooms_temp_array['rooms_' . $r], $roomC);
                                            }
                                        } else {

                                            if(!is_array($rooms_temp_array['rooms_' . $r])) {
                                                $rooms_temp_array['rooms_' . $r] = array();
                                            }
                                            array_push($rooms_temp_array['rooms_' . $r], $roomC);
                                            

                                            //array_push($rooms_temp_array['rooms_' . $r], $roomC);
                                        }

                                        if ($roomC['SmokingPreference'] == 'NoPreference') {
                                            $roomC['SmokingPreference'] = 0;
                                        } else if ($roomC['SmokingPreference'] == 'Smoking') {
                                            $roomC['SmokingPreference'] = 1;
                                        } else if ($roomC['SmokingPreference'] == 'NonSmoking') {
                                            $roomC['SmokingPreference'] = 2;
                                        } else {
                                            $roomC['SmokingPreference'] = 3;
                                        }
                                    }

                                }
                            }
                        }

                        $combination_type = "fixed";
                        //break;
                    }
                    // die();
                }
            }

            $request->session()->forget('RoomCombination');
            $request->session()->forget('RoomArr');
            $hotelSearchInput = $hotels['request'];

            if ($hotels['request']['roomCount'] == 1) {
                return response()
                                ->json(array(
                                    'rooms' => $rooms_array,
                                    'status' => true,
                                    'commission' => $commisioninis,
                                 //   'inclusion_array' => array_unique($incNewData),
                                    'hotelSearchInput' => $hotelSearchInput,
                                    'commisioninis_currency' => $commisioninis_currency,
                                    'search_id_hotel' => $search_id_hotel,
                                    'lottery_Limit' => $lottery_Limit
                                        // 'imagesAll' => $imagesAll
                ));
            } else {

                if(isset($rooms_temp_array) && isset($rooms_temp_array['rooms_0']) && isset($rooms_temp_array['rooms_0'][0]) && isset($rooms_temp_array['rooms_0'][0]['InfoSource'])) {

                    if($rooms_temp_array['rooms_0'][0]['InfoSource'] == 'OpenCombination') {
                        $combination_type = "open";
                    } else {
                        $combination_type = "fixed";
                    }
                }

                return response()->json(array(
                            'rooms' => $rooms_temp_array,
                            'status' => true,
                            'commission' => $commisioninis,
                            'combination_type' => $combination_type,
                            'room_combination' => $this->hotelRoomsCombination,
                            'flight_price' => $flightPrice,
                            //'inclusion_array' => array_unique($incNewData),
                            'hotelSearchInput' => $hotelSearchInput,
                            'commisioninis_currency' => $commisioninis_currency,
                            'search_id_hotel' => $search_id_hotel,
                            'lottery_Limit' => $lottery_Limit
                                //'imagesAll' => $imagesAll
                ));
            }
        } else {

            $this->writeLogs(array('HotelCode' => $request->hotelCode, 'HotelIndex' => $selected_hotel['TBO_data']['ResultIndex'], 'TraceId' => $request->traceId, 'CategoryId' => $supplierCategories), $rooms['GetHotelRoomResult']);

            Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode(array('HotelCode' => $request->hotelCode, 'supplierCategories' => $supplierCategories, 'traceId' => $request->traceId)), json_encode($rooms) ));

            return response()->json(array(
                        'rooms' => $rooms['GetHotelRoomResult']['Error']['ErrorMessage'],
                        'status' => false
            ));
        }
    }

    public function searchForId($id, $array) {
        foreach ($array as $key => $val) {
            if ($val['RoomTypeName'] === $id) {
                return $key;
            }
        }
        return 'empty';
    }

    public function searchHotel(Request $request) {
        if ($request->name) {
            $results_hotels = array();
            $hotels = unserialize(Session::get('hotels_list'));
            foreach ($hotels as $h_key => $hotel) {
                if (str_contains(strtolower($hotel['static_data']['hotel_name']), strtolower($request->name))) { 
                    array_push($results_hotels, $hotel);
                }
            }

            return response()->json(array(
                        'hotels' => $results_hotels
            ));
        }
    }

    public function allHotels(Request $request) {
        $hotels = unserialize(Session::get('hotels_list'));
        return response()->json($hotels);
    }

    public function viewFlightRoom(Request $request) {

        if (Session::has('paid')) {                         
            Session::forget('paid');
        }
        
        
        $input = $request->all();

        $roomCategory = $request->category; //str_replace('-', '#', $request->category);
        //echo "<pre>"; print_r($input); die();
        $this->hotelName = $input['hotelName'];
        $this->hotelCode = $input['hotelCode'];
        $this->hoteIndex = $input['hotelIndex']; //Session::get('resultIndex');
        $this->traceId = $request->traceId;


        $flightTraceId = $request->flightTraceId;


        $search_id = $request->searchId;
        $flightId = $request->flightID;
        $rflightId = $request->rflightID;

        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);


        $flightdata = $search_contents['response']['Results'][0][$flightId];



        $flightreturndata = array();
        

        if(isset($search_contents['response']['Results'][1][$rflightId])){

            $flightreturndata = $search_contents['response']['Results'][1][$rflightId];
        }


        $flightSearch = $search_contents['request']['input'];


        $this->flightapi = new TBOFlightAPI();

        $this->fareRuleOB = $this->flightapi->fareRule($flightTraceId, $flightdata['ResultIndex']);
        $this->fareQuoteOB = $this->flightapi->fareQuote($flightTraceId, $flightdata['ResultIndex']);

         /* Select Seat and Meal */    
        $this->SSR = $this->flightapi->SSR($flightTraceId, $flightdata['ResultIndex']);

        $mealarray = array();
        $seatarray = array();

        $baggagearray = array();
        $mealLCCarray = array();

        $baggagearrayreturn = array();
        $mealLCCarrayreturn = array();

        if (isset($this->SSR['Response']) && isset($this->SSR['Response']['Meal']) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
            
            $mealarray = $this->SSR['Response']['Meal'];
        
        }

        if (isset($this->SSR['Response']) && isset($this->SSR['Response']['SeatPreference']) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
            
            $seatarray = $this->SSR['Response']['SeatPreference'];
        
        }

        if (isset($this->SSR['Response']) && isset($this->SSR['Response']['Baggage']) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
            
            $baggagearray = $this->SSR['Response']['Baggage'][0];
        
        }

        if (isset($this->SSR['Response']) && isset($this->SSR['Response']['Baggage'][1]) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
            
            $baggagearrayreturn = $this->SSR['Response']['Baggage'][1];
        
        }

        if (isset($this->SSR['Response']) && isset($this->SSR['Response']['MealDynamic']) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
            
            $mealLCCarray = $this->SSR['Response']['MealDynamic'][0];
        }

        if (isset($this->SSR['Response']) && isset($this->SSR['Response']['MealDynamic'][1]) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
            
            $mealLCCarrayreturn = $this->SSR['Response']['MealDynamic'][1];
        
        }

        //echo "<pre>"; print_r($mealLCCarrayreturn);die;

        foreach ($baggagearray as $key => $value) {
            # code...
            if($value['Price'] == 0){
                unset($baggagearray[$key]);
            }
        }

        foreach ($mealLCCarray as $key => $value) {
            # code...
            if($value['Price'] == 0){
                unset($mealLCCarray[$key]);
            }
        }

        foreach ($baggagearrayreturn as $key => $value) {
            # code...
            if($value['Price'] == 0){
                unset($baggagearrayreturn[$key]);
            }
        }

        foreach ($mealLCCarrayreturn as $key => $value) {
            # code...
            if($value['Price'] == 0){
                unset($mealLCCarrayreturn[$key]);
            }
        }

        /* Ends Here */

        if(isset($flightreturndata['ResultIndex']) && $flightreturndata['ResultIndex'] != ''){

            $this->fareRuleIB = $this->flightapi->fareRuleIB($flightTraceId, $flightreturndata['ResultIndex']);
            $this->fareQuoteIB = $this->flightapi->fareQuoteIB($flightTraceId, $flightreturndata['ResultIndex']);
            $this->SSRIB = $this->flightapi->SSRIB($flightTraceId, $flightreturndata['ResultIndex']);

        }else{

            $this->fareRuleIB['Response'] = [];
            $this->fareQuoteIB['Response'] = [];
            $flightreturndata['ResultIndex'] = '';
        }

        $baggagearrayreturnib = array();
        $mealarrayreturnib = array();

        if (isset($this->SSRIB['Response']) && isset($this->SSRIB['Response']['Baggage']) && $this->fareQuoteIB['Response']['ResponseStatus'] == 1) {
            
            $baggagearrayreturnib = $this->SSRIB['Response']['Baggage'][0];
        
        }

        if (isset($this->SSRIB['Response']) && isset($this->SSRIB['Response']['MealDynamic']) && $this->fareQuoteIB['Response']['ResponseStatus'] == 1) {
            
            $mealarrayreturnib = $this->SSRIB['Response']['MealDynamic'][0];
        
        }

        /* Redirect to 500 when FareOB Quote doesnt work */
         if (isset($this->fareRuleOB['Response']) && isset($this->fareRuleOB['Response']['ResponseStatus']) && $this->fareRuleOB['Response']['ResponseStatus'] != 1) {

            $message = $this->fareRuleOB['Response']['Error']['ErrorMessage'];
            return view('500')->with(['error' => $message]);
        }

        if (isset($this->fareQuoteOB['Response']) && isset($this->fareQuoteOB['Response']['ResponseStatus']) && $this->fareQuoteOB['Response']['ResponseStatus'] != 1) {

            $message = $this->fareQuoteOB['Response']['Error']['ErrorMessage'];
            return view('500')->with(['error' => $message]);
        }


         /* Redirect to 500 when FareIB Quote doesnt work */
        if (isset($this->fareRuleIB['Response']) && isset($this->fareRuleIB['Response']['ResponseStatus']) && $this->fareRuleIB['Response']['ResponseStatus'] != 1) {

            $message = $this->fareRuleIB['Response']['Error']['ErrorMessage'];
            return view('500')->with(['error' => $message]);
        }

        if (isset($this->fareQuoteIB['Response']) && isset($this->fareQuoteIB['Response']['ResponseStatus']) && $this->fareQuoteIB['Response']['ResponseStatus'] != 1) {

            $message = $this->fareQuoteIB['Response']['Error']['ErrorMessage'];
            return view('500')->with(['error' => $message]);
        }
        /* Ends here */

        $search_id_hotel = $request->search_id;
        $destinationPath=public_path()."/logs/searches/hotels/" . $search_id_hotel . '.json';
        if (file_exists($destinationPath)){
            
            $search_contents_hotel = json_decode($this->readSearchDataHotel($search_id_hotel.'.json'), true);

        }

        $this->checkInDate = $search_contents_hotel['request']['departdate'];
        $this->checkOutDate = $search_contents_hotel['request']['returndate'];
        $this->noOfRooms = $search_contents_hotel['request']['roomCount'];
        $roomGuest = $search_contents_hotel['request']['roomsGuests'];

        $isVoucherBooking = true;
        $lastCancellationDate = date('Y-m-d');

        $selectedRooms = array();
        if ($this->noOfRooms == 1) {
            if ($input['room']['SmokingPreference'] == 'NoPreference') {
                $input['room']['SmokingPreference'] = 0;
            } else if ($input['room']['SmokingPreference'] == 'Smoking') {
                $input['room']['SmokingPreference'] = 1;
            } else if ($input['room']['SmokingPreference'] == 'NonSmoking') {
                $input['room']['SmokingPreference'] = 2;
            } else {
                $input['room']['SmokingPreference'] = 3;
            }

            if(date('Y-m-d', strtotime($input['room']['LastCancellationDate'])) > date('Y-m-d')) {
                $isVoucherBooking = false;
                $lastCancellationDate = date('Y-m-d', strtotime($input['room']['LastCancellationDate']));
            }
            unset($input['room']['LastCancellationDate']);

            array_push($selectedRooms, $input['room']);
        } else {
            // echo "<pre>";
            foreach ($input['room'] as $key => $r) {
                # code...
                // print_r($r);
                if ($r['SmokingPreference'] == 'NoPreference') {
                    $r['SmokingPreference'] = 0;
                } else if ($r['SmokingPreference'] == 'Smoking') {
                    $r['SmokingPreference'] = 1;
                } else if ($r['SmokingPreference'] == 'NonSmoking') {
                    $r['SmokingPreference'] = 2;
                } else {
                    $r['SmokingPreference'] = 3;
                }

                if(date('Y-m-d', strtotime($r['LastCancellationDate'])) > date('Y-m-d')) {
                    $isVoucherBooking = false;
                    $lastCancellationDate = date('Y-m-d', strtotime($r['LastCancellationDate']));
                } else {
                    $isVoucherBooking = true;
                }
                unset($input['room']['LastCancellationDate']);

                array_push($selectedRooms, $r);
            }
        }

        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $getlocation = json_decode($this->getCookie('th_country'));

        } else {

            $getlocation = \Location::get($this->end_user_ip);
        }

        $guestNationality = $getlocation->countryCode;

        $CategoryId = $roomCategory;


        if ($request->referral != '') {

            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request
                        ->referral])
                    ->first();
            if (isset($checkrefferal)) {

                $commisioninis = env('INIS_VAL');
            } else {
                $commisioninis = env('INIS_VAL');
            }
        } else {

            $commisioninis = env('INIS_VAL');
        }

        if ($request->referral && $request->referral != '' && $request->referral != '0') {
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $request->hotelCode . "'");
        }



        if ($request->rphoto && $request->rphoto != '' && !empty($request->rphoto)) {
            $roomPhoto = $request->rphoto;
        } else {
            $roomPhoto = 'https://b2b.tektravels.com/Images/HotelNA.jpg';
        }

        if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
            $commisioninis = 10;
        }

        $commisioninis_currency = env('INR_FEES');

        //echo $CategoryId;die;
        $this->api = new TBOHotelAPI();
        $roomDetails = $this
                ->api
                ->hotelBlockRoom($this->hotelCode, $this->hoteIndex, $this->traceId, $this->hotelName, $selectedRooms, $this->noOfRooms, $guestNationality, $CategoryId, $isVoucherBooking);

        // echo "<pre>"; print_r($roomDetails); die();
        if ($roomDetails['BlockRoomResult']['ResponseStatus'] != 1) {
            //$this->emptySession();
            $this->writeLogs(array('HotelCode' => $this->hotelCode, 'HotelIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'selectedRooms' => $selectedRooms), $roomDetails['BlockRoomResult']);

            Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode(array('HotelCode' => $this->hotelCode, 'HotelIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'selectedRooms' => $selectedRooms)), json_encode($roomDetails) ));

            return view('500')->with(['error' => 'This room is not available, Kindly select another room for your stay']);
        }

        $blockRequest = array('hotelCode' => $this->hotelCode,
                              'hoteIndex' => $this->hoteIndex,
                              'traceId' => $this->traceId,
                              'hotelName' => $this->hotelName,
                              'selectedRooms' => $selectedRooms,
                              'noOfRooms' => $this->noOfRooms,
                              'guestNationality' => $guestNationality,
                              'CategoryId' => $CategoryId,
                              'roomPhoto' => $roomPhoto);
        
        $fileName = $search_id_hotel . '_block_request.json';
        $blockRequest['isVoucherBooking'] = $isVoucherBooking;
        $blockRequest['lastCancellationDate'] = $lastCancellationDate;
        $this->saveBlockRoomData($fileName, json_encode($blockRequest));

        $blockRoom = $roomDetails['BlockRoomResult']['HotelRoomsDetails'];

        $fileNameBlock = $search_id_hotel . '_block.json';
        $this->saveBlockRoomData($fileNameBlock, json_encode($roomDetails));


        Session::put('BookRoomDetails', $blockRoom);
        //Session::put('HotelPolicyDetail', '');
        $isPackageFare = $roomDetails['BlockRoomResult']['IsPackageFare'];
        $isPackageDetailsMandatory = $roomDetails['BlockRoomResult']['IsPackageDetailsMandatory'];

        Session::put('IsPackageFare', $isPackageFare);
        Session::put('IsPackageDetailsMandatory', $isPackageDetailsMandatory);

        $hotel = StaticDataHotels::where('hotel_code', $this->hotelCode)
                ->first();
        StaticDataHotels::where('hotel_code', $this->hotelCode)->update(['hotel_policy' => $roomDetails['BlockRoomResult']['HotelPolicyDetail']]);

        if (isset($hotel['hotel_images']) && !empty($hotel['hotel_images'])) {
            $hotel['hotel_images'] = json_decode($hotel['hotel_images']);
        }


        if ($request->referral != '') {

            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request
                        ->referral])
                    ->first();
            if (isset($checkrefferal)) {

                $commisioninis = env('INIS_VAL');
            } else {
                $commisioninis = env('INIS_VAL');
            }
        } else {

            $commisioninis = env('INIS_VAL');
        }

        if ($request->referral != '') {
            $referral = $request->referral;
        } else {
            $referral = '0';
        }


        if ($request->referral && $request->referral != '' && $request->referral != '0') {
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

            if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                $commisioninis = 10;
            }
        }

        $type = preg_replace('/\s*/', '', $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['RoomTypeName']);
        $images = RoomImages::where(['sub_domain' => $this->hotelCode])->first();
        //$roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['RoomTypeName'];

        $conversion = env('INR_FEES');
        $commisioninisFlight = env('INIS_VAL_FLIGHT');
        $commisioninisagent = env('INIS_AGENT_VAL');


        $adultCount = $search_contents['request']['AdultCount'];
        $childCount = $search_contents['request']['ChildCount'];


        $show_markup = false;
        if(Auth::user()) {
            $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();

            if(isset($agent) && $agent['referal_code'] && $agent['referal_code'] == $referral) {
                $show_markup = true;
            }
        } 


        $location = \Location::get($this->end_user_ip);
        //$timeline[]=["after Location api called:"=>date("H:i:s")];
        if ($location) {
                
             //$timeline[]=["before currencies:"=>date("H:i:s")];
            $countryInfo = Currencies::where('code', $location->countryCode)->first();
                //$timeline[]=["after currencies:"=>date("H:i:s")];
                
            Session::put('CurrencyCode', $countryInfo['currency_code']);
            $this->setCookie('th_country',  json_encode($location), time()+60*60*24*10);
            $myCurrency = $countryInfo['currency_code']; //Session::get('CurrencyCode');
            

        } else {
            Session::put('CurrencyCode', 'USD');
            $this->setCookie('th_country',  'US', time()+60*60*24*10);
            $myCurrency = 'USD';
            
        }

        $paidAmtILS = 0;
        $agentMarkup = 0;

        $isILS = false;

        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {
            $location = \Location::get($this->end_user_ip);
        }



        if (isset($location) && isset($location->countryCode)) {

            if ($location->countryCode == 'IL') {
                $isILS = true;
            }
        }

        return view('search.flights-hotels.view-room')->with(['rphoto' => $roomPhoto, 'roomDetails' => $roomDetails, 'checkInDate' => $this->checkInDate, 'checkOutDate' => $this->checkOutDate, 'ResultIndex' => $this->hoteIndex, 'roomGuests' => $roomGuest, 'noOfRooms' => $this->noOfRooms, 'sub_domain' => $this->hotelCode, 'CategoryId' => $CategoryId, 'hotel_img' => (isset($hotel['hotel_images'][0])) ? $hotel['hotel_images'] : [], 'commission' => $commisioninis, 'referral' => $referral, 'room_images' => $images, 'guestNationality' => $guestNationality, 'hotel_code' => $this->hotelCode, 'traceId' => $this->traceId, 'fareRuleOB' => $this->fareRuleOB['Response'],'fareRuleIB' => $this->fareRuleIB['Response'], 'fareQuoteOB' => $this->fareQuoteOB['Response'],'fareQuoteIB' => $this->fareQuoteIB['Response'], 'adultCount' => $adultCount, 'childCount' => $childCount, 'infantCount' => 0, 'ftraceID' => $flightTraceId, 'resultOBIndex' => $flightdata['ResultIndex'] ,'resultIBIndex' => $flightreturndata['ResultIndex'], 'commissionFlight' => $commisioninisFlight, 'conversion' => $conversion, 'commisioninisagent' => $commisioninisagent, 'meal' => $mealarray, 'seat' => $seatarray, 'mealLCC' => $mealLCCarray, 'mealLCCreturn' => $mealLCCarrayreturn, 'baggage' => $baggagearray , 'baggagereturn' => $baggagearrayreturn, 'mealreturnib' => $mealarrayreturnib, 'baggagereturnib' => $baggagearrayreturnib, 'input' => $flightSearch, 'show_markup' => $show_markup,'search_id' => $search_id, 'search_id_hotel' => $search_id_hotel, 'input_data' => $search_contents_hotel['request'], 'myCurrency' => $myCurrency, 'paidAmtILS' => $paidAmtILS, 'agentMarkup' => $agentMarkup, 'isILS' => $isILS]);
    }

    public function viewFlightRoomGet(Request $request) {

        if (Session::has('paid')) {                         
            Session::forget('paid');
        }
        
        
        $input = $request->all();
        $queryValues = $request->query();
        $flightTraceId = $request->flightTraceId;
        $search_id = $request->searchId;
        $flightId = $request->flightID;
        $rflightId = $request->rflightID;

         //str_replace('-', '#', $request->category);

        $destinationPath=public_path()."/logs/searches/hotels/" . $request->search_id . '_block_request.json';


        if (file_exists($destinationPath)){
   
                $search_contents_block = json_decode($this->readSearchDataHotel($request->search_id . '_block_request.json'), true);
                $this->hotelName = $search_contents_block['hotelName'];
                $this->hotelCode = $search_contents_block['hotelCode'];
                $this->hoteIndex = $search_contents_block['hoteIndex']; //Session::get('resultIndex');
                $this->traceId = $request->traceId;


                

                $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);


                $flightdata = $search_contents['response']['Results'][0][$flightId];



                $flightreturndata = array();
                

                if(isset($search_contents['response']['Results'][1][$rflightId])){

                    $flightreturndata = $search_contents['response']['Results'][1][$rflightId];
                }


                $flightSearch = $search_contents['request']['input'];


                $this->flightapi = new TBOFlightAPI();

                $this->fareRuleOB = $this->flightapi->fareRule($flightTraceId, $flightdata['ResultIndex']);
                $this->fareQuoteOB = $this->flightapi->fareQuote($flightTraceId, $flightdata['ResultIndex']);

                 /* Select Seat and Meal */    
                $this->SSR = $this->flightapi->SSR($flightTraceId, $flightdata['ResultIndex']);

                $mealarray = array();
                $seatarray = array();

                $baggagearray = array();
                $mealLCCarray = array();

                $baggagearrayreturn = array();
                $mealLCCarrayreturn = array();

                if (isset($this->SSR['Response']) && isset($this->SSR['Response']['Meal']) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
                    
                    $mealarray = $this->SSR['Response']['Meal'];
                
                }

                if (isset($this->SSR['Response']) && isset($this->SSR['Response']['SeatPreference']) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
                    
                    $seatarray = $this->SSR['Response']['SeatPreference'];
                
                }

                if (isset($this->SSR['Response']) && isset($this->SSR['Response']['Baggage']) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
                    
                    $baggagearray = $this->SSR['Response']['Baggage'][0];
                
                }

                if (isset($this->SSR['Response']) && isset($this->SSR['Response']['Baggage'][1]) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
                    
                    $baggagearrayreturn = $this->SSR['Response']['Baggage'][1];
                
                }

                if (isset($this->SSR['Response']) && isset($this->SSR['Response']['MealDynamic']) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
                    
                    $mealLCCarray = $this->SSR['Response']['MealDynamic'][0];
                }

                if (isset($this->SSR['Response']) && isset($this->SSR['Response']['MealDynamic'][1]) && $this->fareRuleOB['Response']['ResponseStatus'] == 1) {
                    
                    $mealLCCarrayreturn = $this->SSR['Response']['MealDynamic'][1];
                
                }

                //echo "<pre>"; print_r($mealLCCarrayreturn);die;

                foreach ($baggagearray as $key => $value) {
                    # code...
                    if($value['Price'] == 0){
                        unset($baggagearray[$key]);
                    }
                }

                foreach ($mealLCCarray as $key => $value) {
                    # code...
                    if($value['Price'] == 0){
                        unset($mealLCCarray[$key]);
                    }
                }

                foreach ($baggagearrayreturn as $key => $value) {
                    # code...
                    if($value['Price'] == 0){
                        unset($baggagearrayreturn[$key]);
                    }
                }

                foreach ($mealLCCarrayreturn as $key => $value) {
                    # code...
                    if($value['Price'] == 0){
                        unset($mealLCCarrayreturn[$key]);
                    }
                }

                /* Ends Here */

                if(isset($flightreturndata['ResultIndex']) && $flightreturndata['ResultIndex'] != ''){

                    $this->fareRuleIB = $this->flightapi->fareRuleIB($flightTraceId, $flightreturndata['ResultIndex']);
                    $this->fareQuoteIB = $this->flightapi->fareQuoteIB($flightTraceId, $flightreturndata['ResultIndex']);
                    $this->SSRIB = $this->flightapi->SSRIB($flightTraceId, $flightreturndata['ResultIndex']);

                }else{

                    $this->fareRuleIB['Response'] = [];
                    $this->fareQuoteIB['Response'] = [];
                    $flightreturndata['ResultIndex'] = '';
                }

                $baggagearrayreturnib = array();
                $mealarrayreturnib = array();

                if (isset($this->SSRIB['Response']) && isset($this->SSRIB['Response']['Baggage']) && $this->fareQuoteIB['Response']['ResponseStatus'] == 1) {
                    
                    $baggagearrayreturnib = $this->SSRIB['Response']['Baggage'][0];
                
                }

                if (isset($this->SSRIB['Response']) && isset($this->SSRIB['Response']['MealDynamic']) && $this->fareQuoteIB['Response']['ResponseStatus'] == 1) {
                    
                    $mealarrayreturnib = $this->SSRIB['Response']['MealDynamic'][0];
                
                }

                /* Redirect to 500 when FareOB Quote doesnt work */
                 if (isset($this->fareRuleOB['Response']) && isset($this->fareRuleOB['Response']['ResponseStatus']) && $this->fareRuleOB['Response']['ResponseStatus'] != 1) {

                    $amt = Session::get('multiplePayments');

                    if(isset($amt) && $amt > 0 ){

                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');

                        $walletAmount = \Auth::user()->balance;

                        $amt = $walletAmount + $amt;

                        $pAmount = Currency::convert($ccrcy, 'USD', round($amt));

                        $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);

                        Session::forget('multiplePayments');
                    }

                    $message = $this->fareRuleOB['Response']['Error']['ErrorMessage'];
                    return view('500')->with(['error' => $message]);
                }

                if (isset($this->fareQuoteOB['Response']) && isset($this->fareQuoteOB['Response']['ResponseStatus']) && $this->fareQuoteOB['Response']['ResponseStatus'] != 1) {

                    $amt = Session::get('multiplePayments');

                    if(isset($amt) && $amt > 0 ){

                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');

                        $walletAmount = \Auth::user()->balance;

                        $amt = $walletAmount + $amt;

                        $pAmount = Currency::convert($ccrcy, 'USD', round($amt));

                        $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);

                        Session::forget('multiplePayments');
                    }

                    $message = $this->fareQuoteOB['Response']['Error']['ErrorMessage'];
                    return view('500')->with(['error' => $message]);
                }


                 /* Redirect to 500 when FareIB Quote doesnt work */
                if (isset($this->fareRuleIB['Response']) && isset($this->fareRuleIB['Response']['ResponseStatus']) && $this->fareRuleIB['Response']['ResponseStatus'] != 1) {

                    $amt = Session::get('multiplePayments');

                    if(isset($amt) && $amt > 0 ){

                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');

                        $walletAmount = \Auth::user()->balance;

                        $amt = $walletAmount + $amt;

                        $pAmount = Currency::convert($ccrcy, 'USD', round($amt));

                        $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);

                        Session::forget('multiplePayments');
                    }

                    $message = $this->fareRuleIB['Response']['Error']['ErrorMessage'];
                    return view('500')->with(['error' => $message]);
                }

                if (isset($this->fareQuoteIB['Response']) && isset($this->fareQuoteIB['Response']['ResponseStatus']) && $this->fareQuoteIB['Response']['ResponseStatus'] != 1) {

                    $amt = Session::get('multiplePayments');

                    if(isset($amt) && $amt > 0 ){

                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');

                        $walletAmount = \Auth::user()->balance;

                        $amt = $walletAmount + $amt;

                        $pAmount = Currency::convert($ccrcy, 'USD', round($amt));

                        $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);

                        Session::forget('multiplePayments');
                    }

                    $message = $this->fareQuoteIB['Response']['Error']['ErrorMessage'];
                    return view('500')->with(['error' => $message]);
                }
                /* Ends here */

                $search_id_hotel = $request->search_id;
                $destinationPath=public_path()."/logs/searches/hotels/" . $search_id_hotel . '.json';
                if (file_exists($destinationPath)){
                    
                    $search_contents_hotel = json_decode($this->readSearchDataHotel($search_id_hotel.'.json'), true);
                    $block_room_data = json_decode($this->readSearchDataHotel($search_id_hotel.'_block.json'), true);

                }
                // echo "<pre>"; print_r($block_room_data['BlockRoomResult']['HotelRoomsDetails']); die();

                $this->checkInDate = $search_contents_hotel['request']['departdate'];
                $this->checkOutDate = $search_contents_hotel['request']['returndate'];
                $this->noOfRooms = $search_contents_hotel['request']['roomCount'];
                $roomGuest = $search_contents_hotel['request']['roomsGuests'];

                $selectedRooms = array();
                
                if ($this->noOfRooms == 1) {
                    if ($block_room_data['BlockRoomResult']['HotelRoomsDetails'][0]['SmokingPreference'] == 'NoPreference') {
                        $block_room_data['BlockRoomResult']['HotelRoomsDetails'][0]['SmokingPreference'] = 0;
                    } else if ($block_room_data['BlockRoomResult']['HotelRoomsDetails']['SmokingPreference'] == 'Smoking') {
                        $block_room_data['BlockRoomResult']['HotelRoomsDetails'][0]['SmokingPreference'] = 1;
                    } else if ($block_room_data['BlockRoomResult']['HotelRoomsDetails'][0]['SmokingPreference'] == 'NonSmoking') {
                        $block_room_data['BlockRoomResult']['HotelRoomsDetails'][0]['SmokingPreference'] = 2;
                    } else {
                        $block_room_data['BlockRoomResult']['HotelRoomsDetails'][0]['SmokingPreference'] = 3;
                    }
                    array_push($selectedRooms, $block_room_data['BlockRoomResult']['HotelRoomsDetails'][0]);
                } else {
                    // echo "<pre>";
                    foreach ($block_room_data['BlockRoomResult']['HotelRoomsDetails'] as $key => $r) {
                        # code...
                        // print_r($r);
                        if ($r['SmokingPreference'] == 'NoPreference') {
                            $r['SmokingPreference'] = 0;
                        } else if ($r['SmokingPreference'] == 'Smoking') {
                            $r['SmokingPreference'] = 1;
                        } else if ($r['SmokingPreference'] == 'NonSmoking') {
                            $r['SmokingPreference'] = 2;
                        } else {
                            $r['SmokingPreference'] = 3;
                        }
                        array_push($selectedRooms, $r);
                    }
                }

                if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
                    
                    $getlocation = json_decode($this->getCookie('th_country'));

                } else {

                    $getlocation = \Location::get($this->end_user_ip);
                }

                $guestNationality = $getlocation->countryCode;

                // $CategoryId = $roomCategory;


                if ($request->referral != '') {

                    $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request
                                ->referral])
                            ->first();
                    if (isset($checkrefferal)) {

                        $commisioninis = env('INIS_VAL');
                    } else {
                        $commisioninis = env('INIS_VAL');
                    }
                } else {

                    $commisioninis = env('INIS_VAL');
                }

                if ($request->referral && $request->referral != '' && $request->referral != '0') {
                    $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $request->hotelCode . "'");
                }



                if ($request->rphoto && $request->rphoto != '' && !empty($request->rphoto)) {
                    $roomPhoto = $request->rphoto;
                } else {
                    $roomPhoto = 'https://b2b.tektravels.com/Images/HotelNA.jpg';
                }

                if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                    $commisioninis = 10;
                }

                $commisioninis_currency = env('INR_FEES');

                // echo "<pre>";
                // print_r($selectedRooms);
                // die();
                $CategoryId = $search_contents_block['CategoryId'];
                $this->api = new TBOHotelAPI();
                $roomDetails = $this
                        ->api
                        ->hotelBlockRoom($this->hotelCode, $this->hoteIndex, $this->traceId, $this->hotelName, $selectedRooms, $this->noOfRooms, $guestNationality, $CategoryId, $search_contents_block['isVoucherBooking']);

                // echo "<pre>"; print_r($roomDetails); die();
                if ($roomDetails['BlockRoomResult']['ResponseStatus'] != 1) {

                    $amt = Session::get('multiplePayments');

                    if(isset($amt) && $amt > 0 ){

                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');

                        $walletAmount = \Auth::user()->balance;

                        $amt = $walletAmount + $amt;

                        $pAmount = Currency::convert($ccrcy, 'USD', round($amt));

                        $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);

                        Session::forget('multiplePayments');
                    }

                    //$this->emptySession();
                    $this->writeLogs(array('HotelCode' => $this->hotelCode, 'HotelIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'selectedRooms' => $selectedRooms), $roomDetails['BlockRoomResult']);

                    Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode(array('HotelCode' => $this->hotelCode, 'HotelIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'selectedRooms' => $selectedRooms)), json_encode($roomDetails) ));

                    return view('500')->with(['error' => 'This room is not available, Kindly select another room for your stay']);
                }


                $blockRoom = $roomDetails['BlockRoomResult']['HotelRoomsDetails'];

                $fileNameBlock = $search_id_hotel . '_block.json';
                $this->saveBlockRoomData($fileNameBlock, json_encode($roomDetails));


                Session::put('BookRoomDetails', $blockRoom);
                //Session::put('HotelPolicyDetail', '');
                $isPackageFare = $roomDetails['BlockRoomResult']['IsPackageFare'];
                $isPackageDetailsMandatory = $roomDetails['BlockRoomResult']['IsPackageDetailsMandatory'];

                Session::put('IsPackageFare', $isPackageFare);
                Session::put('IsPackageDetailsMandatory', $isPackageDetailsMandatory);

                $hotel = StaticDataHotels::where('hotel_code', $this->hotelCode)
                        ->first();
                StaticDataHotels::where('hotel_code', $this->hotelCode)->update(['hotel_policy' => $roomDetails['BlockRoomResult']['HotelPolicyDetail']]);

                if (isset($hotel['hotel_images']) && !empty($hotel['hotel_images'])) {
                    $hotel['hotel_images'] = json_decode($hotel['hotel_images']);
                }


                if ($request->referral != '') {

                    $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request
                                ->referral])
                            ->first();
                    if (isset($checkrefferal)) {

                        $commisioninis = env('INIS_VAL');
                    } else {
                        $commisioninis = env('INIS_VAL');
                    }
                } else {

                    $commisioninis = env('INIS_VAL');
                }

                if ($request->referral != '') {
                    $referral = $request->referral;
                } else {
                    $referral = '0';
                }


                if ($request->referral && $request->referral != '' && $request->referral != '0') {
                    $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

                    if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                        $commisioninis = 10;
                    }
                }

                $type = preg_replace('/\s*/', '', $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['RoomTypeName']);
                $images = RoomImages::where(['sub_domain' => $this->hotelCode])->first();
                //$roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['RoomTypeName'];

                $conversion = env('INR_FEES');
                $commisioninisFlight = env('INIS_VAL_FLIGHT');
                $commisioninisagent = env('INIS_AGENT_VAL');


                $adultCount = $search_contents['request']['AdultCount'];
                $childCount = $search_contents['request']['ChildCount'];


                $show_markup = false;
                if(Auth::user()) {
                    $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();

                    if(isset($agent) && $agent['referal_code'] && $agent['referal_code'] == $referral) {
                        $show_markup = true;
                    }
                } 

                $agentMarkup = 0;
                $paidAmtILS = 0;
                $ilsPayDetails = array();
                if(isset($queryValues['payme_sale_id']) && $queryValues['payme_sale_id'] != ''){

                    $saleID = $queryValues['payme_sale_id'];

                    $paymentDetails = $this
                            ->api
                            ->checkPaymePayment(env('PAYME_KEY'), $saleID);
                    $payMEDetails = $paymentDetails['items'];

                    if(!empty($payMEDetails)  && $payMEDetails[0]['sale_status'] == 'completed'){

                        $destinationPath=public_path()."/logs/searches/hotels/" . $search_id . '_payme_form_f_h.json';
                        $ilsPay = json_decode($this->readSearchDataWithPath($destinationPath), true);
                        $ilsPayDetails = $ilsPay['request'];

                        
                        //add payment to wallet
                        

                        $paymentVal = true;
                     
                        ///Session::get('BookRoomDetails');
                        
                        
                        // $paidAmtILS = ($ilsPayDetails['paidAmtILS'] == 0) ? ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'];

                        if($ilsPayDetails['paymentMode'] == 'multiple'){

                            $paidAmtILS = ($ilsPayDetails['paidAmtILS'] == 0) ? ($ilsPayDetails['partAmountILS']) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($ilsPayDetails['partAmountILS']) + $ilsPayDetails['walletDebit'];

                            $paidAmtILSMultiple = ($ilsPayDetails['paidAmtILS'] == 0) ? ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'];

                            Session::put('multiplePayments', $paidAmtILSMultiple);

                        }else{
                            
                            $paidAmtILS = ($ilsPayDetails['paidAmtILS'] == 0) ? ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'];

                        }

                        $pendingAmount = $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME'] - $paidAmtILS;
                        
                        if(Auth::user() && $ilsPayDetails['paymentMode'] == 'single') {

                            $walletuser = \Auth::user();
                            $pAmount = Currency::convert('ILS', 'USD', round($paidAmtILS));

                            $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);
                        }

                        if(isset($ilsPayDetails['agent_makrup']) && $ilsPayDetails['agent_makrup'] != ''){

                            $agentMarkup = $ilsPayDetails['agent_makrup'];

                       }

                        if($ilsPayDetails['walletDebit'] > 0){

                            if(Auth::user()) {
                                $walletuser = \Auth::user();
                                $pAmount = Currency::convert('ILS', 'USD', $ilsPayDetails['walletDebit']);

                                $walletuser->withdraw(round($pAmount['convertedAmount']), ['description' => "Card payment " . $ilsPayDetails['walletDebit'] . "(ILS) received from card for Multiple Payment transaction_id :- " . $payMEDetails[0]['transaction_id']]);
                            }

                        }

                        Session::forget('walletAmount');
                        // echo $pendingAmount;
                        // echo "<br>";
                        // echo $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME'];
                        // echo "<br>";
                        // echo  $paidAmtILS;//die();

                        if($pendingAmount <= 0) {

                            $hotelId = $this->bookFlightILS($ilsPayDetails);

                            if(isset($hotelId['success']) && $hotelId['success']){

                                if ($ilsPayDetails['paymentMode'] == 'single') {

                                    if($ilsPayDetails['installments'] != '1'){

                                        $installmentsValue =  0.5 * $ilsPayDetails['installments'];

                                        $deductamount = $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME'] + ( ( $installmentsValue / 100 )  * $ilsPayDetails['ORIGINAL_BOOKING_PRICE']);

                                    }else{
                                         $deductamount = $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME'];
                                    }


                                    $myCurrency = Session::get('CurrencyCode');
                                    $usercurrency = Currency::convert($myCurrency, 'USD', ($deductamount));
                                    $debitAmnt = round($usercurrency['convertedAmount']);


                                    $walletuser = \Auth::user();
                                    $walletuser->withdraw($debitAmnt, ['BookingID' => $hotelId]);
                                    
                                    $walletAmount = \Auth::user()->balance;
                                    Session::forget('walletAmount');
                                }

                                // return redirect('/thankyou/hotel/' . $hotelId['booking_id'] . '/true');
                                return redirect('/thankyou/flight-hotel/' . $hotelId['hotelId'] . '/'. $hotelId['flightId']);

                            }else{

                                //add oney to wallet
                                $amt = Session::get('multiplePayments');

                                if(isset($amt) && $amt > 0){

                                    $walletuser = \Auth::user();
                                    $ccrcy = Session::get('CurrencyCode');

                                    $walletAmount = \Auth::user()->balance;

                                    $amt = $walletAmount + $amt;

                                    $pAmount = Currency::convert($ccrcy, 'USD', round($amt));

                                    $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);

                                    Session::forget('multiplePayments');
                                }
                                //print_r($hotelId); die();
                                // $walletuser = \Auth::user();
                                // $pAmount = Currency::convert('ILS', 'USD', $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME']);
                                // $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);
                                return view('500')->with(['error' => $hotelId['message']]);  
                            }
                        }

                    }else{
                        
                        $paymentVal = false;

                        $destinationPath=public_path()."/logs/searches/hotels/" . $search_id . '_payme_form_f_h.json';
                        $ilsPay = json_decode($this->readSearchDataWithPath($destinationPath), true);
                        $ilsPayDetails = $ilsPay['request'];

                        $this->writePaymeLogs($ilsPayDetails, $payMEDetails);

                        Mail::to(env('NO_RESULT_EMAIL'))->send(new FailedPaymentEmail(json_encode($ilsPayDetails), json_encode($payMEDetails) ));

                        //return view('500')->with(['error' => 'Payment Failed']);

                        if($ilsPayDetails['paymentMode'] == 'multiple'){

                            $amt = Session::get('multiplePayments');

                            if(isset($amt) && $amt > 0){

                                $paidAmtILS = $amt;

                                Session::forget('multiplePayments');
                            }

                        }else{

                            return view('500')->with(['error' => 'Payment Failed']);
                        }
                            
                    }
                
                }

                $isILS = false;

                if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
                    
                    $location = json_decode($this->getCookie('th_country'));

                } else {
                    $location = \Location::get($this->end_user_ip);
                }



                if (isset($location) && isset($location->countryCode)) {

                    if ($location->countryCode == 'IL') {
                        $isILS = true;
                    }
                }



                return view('search.flights-hotels.view-room')->with(['rphoto' => $roomPhoto, 'roomDetails' => $roomDetails, 'checkInDate' => $this->checkInDate, 'checkOutDate' => $this->checkOutDate, 'ResultIndex' => $this->hoteIndex, 'roomGuests' => $roomGuest, 'noOfRooms' => $this->noOfRooms, 'sub_domain' => $this->hotelCode, 'CategoryId' => $CategoryId, 'hotel_img' => (isset($hotel['hotel_images'][0])) ? $hotel['hotel_images'] : [], 'commission' => $commisioninis, 'referral' => $referral, 'room_images' => $images, 'guestNationality' => $guestNationality, 'hotel_code' => $this->hotelCode, 'traceId' => $this->traceId, 'fareRuleOB' => $this->fareRuleOB['Response'],'fareRuleIB' => $this->fareRuleIB['Response'], 'fareQuoteOB' => $this->fareQuoteOB['Response'],'fareQuoteIB' => $this->fareQuoteIB['Response'], 'adultCount' => $adultCount, 'childCount' => $childCount, 'infantCount' => 0, 'ftraceID' => $flightTraceId, 'resultOBIndex' => $flightdata['ResultIndex'] ,'resultIBIndex' => $flightreturndata['ResultIndex'], 'commissionFlight' => $commisioninisFlight, 'conversion' => $conversion, 'commisioninisagent' => $commisioninisagent, 'meal' => $mealarray, 'seat' => $seatarray, 'mealLCC' => $mealLCCarray, 'mealLCCreturn' => $mealLCCarrayreturn, 'baggage' => $baggagearray , 'baggagereturn' => $baggagearrayreturn, 'mealreturnib' => $mealarrayreturnib, 'baggagereturnib' => $baggagearrayreturnib, 'input' => $flightSearch, 'show_markup' => $show_markup,'search_id' => $search_id, 'search_id_hotel' => $search_id_hotel, 'input_data' => $search_contents_hotel['request'], 'paidAmtILS' => $paidAmtILS, 'agentMarkup' => $agentMarkup, 'isILS' => $isILS ]);

        }else{


            // if search file dont exist 

            $saleID = $queryValues['payme_sale_id'];

            $this->api = new TBOHotelAPI();

            $paymentDetails = $this
                    ->api
                    ->checkPaymePayment(env('PAYME_KEY'), $saleID);
            $payMEDetails = $paymentDetails['items'];

            if(!empty($payMEDetails) && $payMEDetails[0]['sale_status'] == 'completed'){


                $destinationPath=public_path()."/logs/searches/hotels/" . $search_id . '_payme_form_f_h.json';
                $ilsPay = json_decode($this->readSearchDataWithPath($destinationPath), true);
                $ilsPayDetails = $ilsPay['request'];


                if($ilsPayDetails['paymentMode'] == 'single'){
                    
                    $paidAmtILS = ($ilsPayDetails['paidAmtILS'] == 0) ? ($payMEDetails[0]['sale_price'] / 100) : $ilsPayDetails['paidAmtILS'] + ($payMEDetails[0]['sale_price'] / 100);

                }
                   
               // Deposit Paid Amount to User Account

                if(Auth::user() && $ilsPayDetails['paymentMode'] == 'single') {

                    $walletuser = \Auth::user();
                    $ccrcy = Session::get('CurrencyCode');
                    $pAmount = Currency::convert($ccrcy, 'USD', round($paidAmtILS));

                    $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'SIngle payment(ILS) -' . $pAmount['convertedAmount']]);
                }

            }

            // Ends Here

            $amt = Session::get('multiplePayments');

            if(isset($amt) && $amt > 0 ){

                $walletuser = \Auth::user();
                $ccrcy = Session::get('CurrencyCode');

                $walletAmount = \Auth::user()->balance;

                $amt = $amt;

                $pAmount = Currency::convert($ccrcy, 'USD', round($amt));

                $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);

                Session::forget('multiplePayments');
            }

            return redirect('/?show=flights-hotels');


        }

    }

    public function bookFlightILS($dataVal) {

        $input = $dataVal;

        $search_contents = json_decode($this->readSearchData($input['search_id'].'.json'), true);

        //$flightSearch = Session::get('flightSearhData');

        $flightSearch = $search_contents['request']['input'];

        $flightSearch['travellersClass'] = '';
        if ($input['referral'] != '') {

            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();
            if (isset($checkrefferal)) {

                //$commisioninis = $checkrefferal['commission'];
                $agent_id = $checkrefferal['user_id'];
                $agentemail = User::select('email')->where(['id' => $agent_id])->first();
                $agentemail = $agentemail['email'];
            } else {
                $agent_id = '';
                $agentemail = '';
                // $commisioninis = env('INIS_VAL_FLIGHT');
            }
        } else {

            $agent_id = '';
            $agentemail = '';
        }

        $fareQuoteOB = json_decode($input['farebreakDownOB'], true);

        $adultCount = $input['adultCountHidden'];
        $ChildCount = $input['childCountHidden'];
        $InfantCount =  "0";

        $roomGuest = $input['roomPassengers'];

        $departDate = $flightSearch['departdate'];

        $flightPassengerArr = array();
        $bookFlightArr = array();

        $travelArr = array();

        $travelArr['from_loc'] = $input['from_loc'];
        $travelArr['to_loc'] = $input['to_loc'];
        $travelArr['duration'] = $input['duration'];
        $travelArr['dep_time'] = $input['dep_time'];
        $travelArr['arr_time'] = $input['arr_time'];
        $travelArr['flight_name_ob'] = $input['flight_name_ob'];
        $travelArr['flight_jetname_ob'] = $input['flight_jetname_ob'];
        $travelArr['base_price'] = $input['base_price'];
        $travelArr['tax_price'] = $input['tax_price'];
        $travelArr['amount'] = $input['amount'];
        $travelArr['amount_without_conversion'] = $input['amount_without_conversion'];
        $travelArr['amount_tbo'] = $input['amount_tbo'];
        $travelArr['departure_date_arr'] = $input['departure_date_arr'];

        $travelArr['city_code_arrival'] = $flightSearch['origin'];
        $travelArr['city_code_departure'] = $flightSearch['destination'];


        $travelArr['main_start'] = $flightSearch['from'];
        $travelArr['to_start'] = $flightSearch['to'];
        $travelArr['extra_baggage_meal_price'] = $input['extra_baggage_meal_price'];

        if (isset($input['stop_from_loc']) && $input['stop_from_loc'] != '') {

            $travelArr['stop_from_loc'] = $input['stop_from_loc'];
            $travelArr['stop_to_loc'] = $input['stop_to_loc'];
            $travelArr['stop_duration'] = $input['stop_duration'];
            $travelArr['stop_dep_time'] = $input['stop_dep_time'];
            $travelArr['stop_arr_time'] = $input['stop_arr_time'];
            $travelArr['stop_flight_name_ob'] = $input['stop_flight_name_ob'];
            $travelArr['stop_flight_jetname_ob'] = $input['stop_flight_jetname_ob'];
        }

        if (isset($input['stop2_from_loc']) && $input['stop2_from_loc'] != '') {

            $travelArr['stop2_from_loc'] = $input['stop2_from_loc'];
            $travelArr['stop2_to_loc'] = $input['stop2_to_loc'];
            $travelArr['stop2_duration'] = $input['stop2_duration'];
            $travelArr['stop2_dep_time'] = $input['stop2_dep_time'];
            $travelArr['stop2_arr_time'] = $input['stop2_arr_time'];
            $travelArr['stop2_flight_name_ob'] = $input['stop2_flight_name_ob'];
            $travelArr['stop2_flight_jetname_ob'] = $input['stop2_flight_jetname_ob'];
        }

        if (isset($input['return_int_from_loc']) && $input['return_int_from_loc'] != '') {

            $travelArr['return_int_from_loc'] = $input['return_int_from_loc'];
            $travelArr['return_int_to_loc'] = $input['return_int_to_loc'];
            $travelArr['return_int_duration'] = $input['return_int_duration'];
            $travelArr['return_int_dep_time'] = $input['return_int_dep_time'];
            $travelArr['return_int_arr_time'] = $input['return_int_arr_time'];
            $travelArr['return_int_flight_name_ob'] = $input['return_int_flight_name_ob'];
            $travelArr['return_int_flight_jetname_ob'] = $input['return_int_flight_jetname_ob'];
        }

        if (isset($input['stop_return_int_from_loc']) && $input['stop_return_int_from_loc'] != '') {

            $travelArr['stop_return_int_from_loc'] = $input['stop_return_int_from_loc'];
            $travelArr['stop_return_int_to_loc'] = $input['stop_return_int_to_loc'];
            $travelArr['stop_return_int_duration'] = $input['stop_return_int_duration'];
            $travelArr['stop_return_int_dep_time'] = $input['stop_return_int_dep_time'];
            $travelArr['stop_return_int_arr_time'] = $input['stop_return_int_arr_time'];
            $travelArr['stop_return_int_flight_name_ob'] = $input['stop_return_int_flight_name_ob'];
            $travelArr['stop_return_int_flight_jetname_ob'] = $input['stop_return_int_flight_jetname_ob'];
        }

        if (isset($input['stop2_return_int_from_loc']) && $input['stop2_return_int_from_loc'] != '') {

            $travelArr['stop2_return_int_from_loc'] = $input['stop2_return_int_from_loc'];
            $travelArr['stop2_return_int_to_loc'] = $input['stop2_return_int_to_loc'];
            $travelArr['stop2_return_int_duration'] = $input['stop2_return_int_duration'];
            $travelArr['stop2_return_int_dep_time'] = $input['stop2_return_int_dep_time'];
            $travelArr['stop2_return_int_arr_time'] = $input['stop2_return_int_arr_time'];
            $travelArr['stop2_return_int_flight_name_ob'] = $input['stop2_return_int_flight_name_ob'];
            $travelArr['stop2_return_int_flight_jetname_ob'] = $input['stop2_return_int_flight_jetname_ob'];
        }

        if (isset($input['return_ib_from_loc']) && $input['return_ib_from_loc'] != '') {

            $travelArr['return_ib_from_loc'] = $input['return_ib_from_loc'];
            $travelArr['return_ib_to_loc'] = $input['return_ib_to_loc'];
            $travelArr['return_ib_duration'] = $input['return_ib_duration'];
            $travelArr['return_ib_dep_time'] = $input['return_ib_dep_time'];
            $travelArr['return_ib_arr_time'] = $input['return_ib_arr_time'];
            $travelArr['flight_name_ib'] = $input['flight_name_ib'];
            $travelArr['flight_jetname_ib'] = $input['flight_jetname_ib'];
            $travelArr['departure_date_dep'] = $input['departure_date_dep'];
        }

        if (isset($input['stop_return_ib_from_loc']) && $input['stop_return_ib_from_loc'] != '') {

            $travelArr['stop_return_ib_from_loc'] = $input['stop_return_ib_from_loc'];
            $travelArr['stop_return_ib_to_loc'] = $input['stop_return_ib_to_loc'];
            $travelArr['stop_return_ib_duration'] = $input['stop_return_ib_duration'];
            $travelArr['stop_return_ib_dep_time'] = $input['stop_return_ib_dep_time'];
            $travelArr['stop_return_ib_arr_time'] = $input['stop_return_ib_arr_time'];
            $travelArr['stop_flight_name_ib'] = $input['stop_flight_name_ib'];
            $travelArr['stop_flight_jetname_ib'] = $input['stop_flight_jetname_ib'];
        }

        if (isset($input['stop2_return_ib_from_loc']) && $input['stop2_return_ib_from_loc'] != '') {

            $travelArr['stop2_return_ib_from_loc'] = $input['stop2_return_ib_from_loc'];
            $travelArr['stop2_return_ib_to_loc'] = $input['stop2_return_ib_to_loc'];
            $travelArr['stop2_return_ib_duration'] = $input['stop2_return_ib_duration'];
            $travelArr['stop2_return_ib_dep_time'] = $input['stop2_return_ib_dep_time'];
            $travelArr['stop2_return_ib_arr_time'] = $input['stop2_return_ib_arr_time'];
            $travelArr['stop2_flight_name_ib'] = $input['stop2_flight_name_ib'];
            $travelArr['stop2_flight_jetname_ib'] = $input['stop2_flight_jetname_ib'];
        }

        $commission = $input['amount_without_conversion'] - $input['amount_tbo'];
        $commission_agent = $input['amount_without_conversion_agent'] - $input['amount_tbo'];

        if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {

            $agent_makrup = $input['agent_makrup'] / 2 ;
        }



        if ($input['referral'] && $input['referral'] != '' && $input['referral'] != '0') {

            $commission_agent = $commission_agent;

        } else {
            
            $commission_agent = $commission;
        }

        $markup_commission_agent = 0;
        if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
            //$commission_agent = $commission_agent + ($agent_makrup * 0.60);

            $agent_markup_commision = $agent_makrup * 0.60;
            $markUplAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $agent_markup_commision);
            $markup_commission_agent = $markUplAmount['convertedAmount'];

            $partners_markup = env('PARTNER_COMMISION_MARKUP');

            $partners_markup_rest = env('PARTNER_COMMISION_REST_MARKUP');

            $markupPartner = round($agent_makrup  * 0.40);

            $markupPartnerCommission = ( ( $partners_markup / 100 ) * $markupPartner);

            $markupPartnerRestCommission = ( ( $partners_markup_rest / 100 ) * $markupPartner);

            $convertPrtnr = Currency::convert(Session::get('CurrencyCode'), 'USD', $markupPartnerCommission );
            $convertPrtnrAmount = $convertPrtnr['convertedAmount'];

            $convertPrtnrest = Currency::convert(Session::get('CurrencyCode'), 'USD', $markupPartnerRestCommission );
            $convertPrtnrestAmount = $convertPrtnrest['convertedAmount'];

        }else{
            $convertPrtnrAmount = 0;
            $convertPrtnrestAmount = 0;
        }


        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {

            $location = \Location::get($this->end_user_ip);
        }
        // //get user currency
        $countryName = $location->countryName;

        $currencyCode = Currencies::where('code', $location->countryCode)->first();
        //Session::put('currency', $currencyCode['currency_code']);


        $lAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $commission_agent);
        $commission_agent = $lAmount['convertedAmount'];

        /* Adult Array */

        $counterchild = $adultCount;
        $counterinfant = $adultCount + $ChildCount;
        //echo "<pre>";print_r($roomGuest);die;


        foreach ($roomGuest as $room => $guest) {
            // echo $room;
            // print_r($guest);
            $NoOfAdults = sizeof($guest['adult']);
            if (isset($guest['child'])) {

                //$NoOfChild = $guest['child'];
                $NoOfChild = sizeof($guest['child']);
            }
            $hotelPassengers[$room] = array();

            for ($a = 1; $a <= $NoOfAdults; $a++) {
                // echo $a . '\n';
                if ($room == 1 && $a == 1) {
                    $userFirstName = $guest['adult'][$a]['first_name'];
                    $userLastName = $guest['adult'][$a]['last_name'];
                    $userEmail = $guest['adult'][$a]['email'];
                    $userPhone = $guest['adult'][$a]['phone'];
                }

                if(isset($guest['adult'][$a]['passportNo'])){

                    array_push($flightPassengerArr, array(
                        "Title" => $guest['adult'][$a]['title'],
                        "FirstName" => $guest['adult'][$a]['first_name'],
                        "LastName" => $guest['adult'][$a]['last_name'],
                        "PaxType" => 1,
                        "DateOfBirth" => isset($guest['adult'][$a]['adult_dob']) ? $guest['adult'][$a]['adult_dob'] : "1985-01-30T00:00:00",
                        "Gender" => ($guest['adult'][$a]['title'] == 'Mr') ? 1 : 2,
                        "AddressLine1" => $guest['adult'][$a]['address'],
                        "AddressLine2" => "",
                        "City" => $guest['adult'][$a]['address'],
                        "CountryCode" => $location->countryCode,
                        "CountryName" => $location->countryName,
                        "PassportNo" => isset($guest['adult'][$a]['passportNo']) ? $guest['adult'][$a]['passportNo'] : "",
                        "PassportExpiry" => isset($guest['adult'][$a]['passportExpire']) ? $guest['adult'][$a]['passportExpire'] : "",
                        "ContactNo" => $guest['adult'][$a]['phone'],
                        "Email" => $guest['adult'][$a]['email'],
                        "IsLeadPax" =>  ($a == 1) ? true : false,
                        "FFAirline" => "",
                        "FFNumber" => "",
                        "Nationality" => "",
                        "Fare" => array(
                            "BaseFare" => $fareQuoteOB[0]['BaseFare'] / $fareQuoteOB[0]['PassengerCount'],
                            "Tax" => $fareQuoteOB[0]['Tax'] / $fareQuoteOB[0]['PassengerCount'],
                            "YQTax" => $fareQuoteOB[0]['YQTax'] / $fareQuoteOB[0]['PassengerCount'],
                            "AdditionalTxnFeeOfrd" => $fareQuoteOB[0]['AdditionalTxnFeeOfrd'] / $fareQuoteOB[0]['PassengerCount'],
                            "AdditionalTxnFeePub" => $fareQuoteOB[0]['AdditionalTxnFeePub'] / $fareQuoteOB[0]['PassengerCount'],
                            "PGCharge" => $fareQuoteOB[0]['PGCharge'] / $fareQuoteOB[0]['PassengerCount']
                        )
                    ));
                }else{

                    array_push($flightPassengerArr, array(
                        "Title" => $guest['adult'][$a]['title'],
                        "FirstName" => $guest['adult'][$a]['first_name'],
                        "LastName" => $guest['adult'][$a]['last_name'],
                        "PaxType" => 1,
                        "DateOfBirth" => isset($guest['adult'][$a]['adult_dob']) ? $guest['adult'][$a]['adult_dob'] : "1985-01-30T00:00:00",
                        "Gender" => ($guest['adult'][$a]['title'] == 'Mr') ? 1 : 2,
                        "AddressLine1" => $guest['adult'][$a]['address'],
                        "AddressLine2" => "",
                        "City" => $guest['adult'][$a]['address'],
                        "CountryCode" => $location->countryCode,
                        "CountryName" => $location->countryName,
                        "ContactNo" => $guest['adult'][$a]['phone'],
                        "PassportNo" => "KJHHJKHKJH",
                        "PassportExpiry"=> "2025-08-30",
                        "Email" => $guest['adult'][$a]['email'],
                        "IsLeadPax" =>  ($a == 1) ? true : false,
                        "FFAirline" => "",
                        "FFNumber" => "",
                        "Nationality" => "",
                        "Fare" => array(
                            "BaseFare" => $fareQuoteOB[0]['BaseFare'] / $fareQuoteOB[0]['PassengerCount'],
                            "Tax" => $fareQuoteOB[0]['Tax'] / $fareQuoteOB[0]['PassengerCount'],
                            "YQTax" => $fareQuoteOB[0]['YQTax'] / $fareQuoteOB[0]['PassengerCount'],
                            "AdditionalTxnFeeOfrd" => $fareQuoteOB[0]['AdditionalTxnFeeOfrd'] / $fareQuoteOB[0]['PassengerCount'],
                            "AdditionalTxnFeePub" => $fareQuoteOB[0]['AdditionalTxnFeePub'] / $fareQuoteOB[0]['PassengerCount'],
                            "PGCharge" => $fareQuoteOB[0]['PGCharge'] / $fareQuoteOB[0]['PassengerCount']
                        )
                    ));
                }

                if(isset($input['meal_' . $a]) && $input['meal_' . $a] != ''){
                    $input['meal_' . $a] = json_decode($input['meal_' . $a], true);
                    $flightPassengerArr[$a-1]['Meal'] = $input['meal_' . $a];
                }

                if(isset($input['seat_' . $a]) && $input['seat_' . $a] != ''){
                    $input['seat_' . $a] = json_decode($input['seat_' . $a], true);
                    $flightPassengerArr[$a-1]['Seat'] = $input['seat_' . $a];
                }

                if(isset($input['baggage_' . $a]) && $input['baggage_' . $a] != ''){
                    $input['baggage_' . $a] = json_decode($input['baggage_' . $a], true);
                    $flightPassengerArr[$a-1]['Baggage'] = array();
                    array_push($flightPassengerArr[$a-1]['Baggage'], $input['baggage_' . $a]);
                }

                if(isset($input['meallcc_' . $a]) && $input['meallcc_' . $a] != ''){
                    $input['meallcc_' . $a] = json_decode($input['meallcc_' . $a], true);
                    $flightPassengerArr[$a-1]['MealDynamic'] = array();
                    array_push($flightPassengerArr[$a-1]['MealDynamic'], $input['meallcc_' . $a]);
                }

                if(isset($input['baggage_return' . $a]) && $input['baggage_return' . $a] != ''){
                    $input['baggage_return' . $a] = json_decode($input['baggage_return' . $a], true);
                    //$flightPassengerArr[$a-1]['Baggage'] = array();
                    if(!isset($input['baggage_' . $a]) && $input['baggage_' . $a] == ''){
                     $flightPassengerArr[$a-1]['Baggage'] = array();
                    }
                    array_push($flightPassengerArr[$a-1]['Baggage'], $input['baggage_return' . $a]);
                }

                if(isset($input['meallcc_return' . $a]) && $input['meallcc_return' . $a] != ''){
                    $input['meallcc_return' . $a] = json_decode($input['meallcc_return' . $a], true);
                    
                    if(!isset($input['meallcc_' . $a]) && $input['meallcc_' . $a] == ''){
                     $flightPassengerArr[$a-1]['MealDynamic'] = array();
                    }
                    //echo "<pre>";print_r($input['meallcc_return' . $a]);die;
                    array_push($flightPassengerArr[$a-1]['MealDynamic'], $input['meallcc_return' . $a]);
                }

                //echo "<pre>";print_r($flightPassengerArr);die;

            }
            if (isset($NoOfChild)) {

                for ($c = 1; $c <= $NoOfChild; $c++) {

                    if(isset($guest['child'][$c]['child_passport_no'])){
                        array_push($flightPassengerArr, array(
                            "Title" => isset($guest['child'][$c]['title']) ? 'Mr':'Miss',
                            "FirstName" => $guest['child'][$c]['first_name'],
                            "LastName" => $guest['child'][$c]['last_name'],
                            "PaxType" => 2,
                            "DateOfBirth" => date('Y-m-d', strtotime($guest['child'][$c]['child_dob'])) . "T00:00:00",
                            "Gender" => ($guest['child'][$c]['title']) ? 1 : 2,
                            "AddressLine1" => $guest['child'][$c]['address'],
                            "AddressLine2" => "",
                            "City" => $guest['child'][$c]['address'],
                            "CountryCode" => $location->countryCode,
                            "CountryName" => $location->countryName,
                            "PassportNo" => isset($guest['child'][$c]['child_passport_no']) ? $guest['child'][$c]['child_passport_no'] : "",
                            "PassportExpiry" => isset($guest['child'][$c]['child_pass_expiry_date']) ? $guest['child'][$c]['child_pass_expiry_date']: "",
                            "ContactNo" => $guest['child'][$c]['child_phone'],
                            "Email" => $guest['child'][$c]['child_email'],
                            "IsLeadPax" => false,
                            "FFAirline" => "",
                            "FFNumber" => "",
                            "Nationality" => "",
                            "Fare" => array(
                                "BaseFare" => $fareQuoteOB[1]['BaseFare'] / $fareQuoteOB[1]['PassengerCount'],
                                "Tax" => $fareQuoteOB[1]['Tax'] / $fareQuoteOB[1]['PassengerCount'],
                                "YQTax" => $fareQuoteOB[1]['YQTax'] / $fareQuoteOB[1]['PassengerCount'],
                                "AdditionalTxnFeeOfrd" => $fareQuoteOB[1]['AdditionalTxnFeeOfrd'] / $fareQuoteOB[1]['PassengerCount'],
                                "AdditionalTxnFeePub" => $fareQuoteOB[1]['AdditionalTxnFeePub'] / $fareQuoteOB[1]['PassengerCount'],
                                "PGCharge" => $fareQuoteOB[1]['PGCharge'] / $fareQuoteOB[1]['PassengerCount']
                            )
                        ));
                    }else{
                      array_push($flightPassengerArr, array(
                            "Title" => $guest['child'][$c]['title'],
                            "FirstName" => $guest['child'][$c]['first_name'],
                            "LastName" => $guest['child'][$c]['last_name'],
                            "PaxType" => 2,
                            "DateOfBirth" => date('Y-m-d', strtotime($guest['child'][$c]['child_dob'])) . "T00:00:00",
                            "Gender" => ($guest['child'][$c]['title']) ? 1 : 2,
                            "AddressLine1" => $guest['child'][$c]['address'],
                            "AddressLine2" => "",
                            "City" => $guest['child'][$c]['address'],
                            "CountryCode" => $location->countryCode,
                            "CountryName" => $location->countryName,
                            "ContactNo" => $guest['child'][$c]['child_phone'],
                            "Email" => $guest['child'][$c]['child_email'],
                            "PassportNo" => "KJHHJKHKJH",
                            "PassportExpiry"=> "2025-08-30",
                            "IsLeadPax" => false,
                            "FFAirline" => "",
                            "FFNumber" => "",
                            "Nationality" => "",
                            "Fare" => array(
                                "BaseFare" => $fareQuoteOB[1]['BaseFare'] / $fareQuoteOB[1]['PassengerCount'],
                                "Tax" => $fareQuoteOB[1]['Tax'] / $fareQuoteOB[1]['PassengerCount'],
                                "YQTax" => $fareQuoteOB[1]['YQTax'] / $fareQuoteOB[1]['PassengerCount'],
                                "AdditionalTxnFeeOfrd" => $fareQuoteOB[1]['AdditionalTxnFeeOfrd'] / $fareQuoteOB[1]['PassengerCount'],
                                "AdditionalTxnFeePub" => $fareQuoteOB[1]['AdditionalTxnFeePub'] / $fareQuoteOB[1]['PassengerCount'],
                                "PGCharge" => $fareQuoteOB[1]['PGCharge'] / $fareQuoteOB[1]['PassengerCount']
                            )
                        ));  
                    }

                    if(isset($input['child_meal_' . $c]) && $input['child_meal_' . $c] != ''){
                        $input['child_meal_' . $c] = json_decode($input['child_meal_' . $c], true);
                        $flightPassengerArr[$counterchild]['Meal'] = $input['child_meal_' . $c];
                    }

                    if(isset($input['child_seat_' . $c]) && $input['child_seat_' . $c] != ''){
                        $input['child_seat_' . $c] = json_decode($input['child_seat_' . $c], true);
                        $flightPassengerArr[$counterchild]['Seat'] = $input['child_seat_' . $c];
                    }

                    if(isset($input['child_baggage_' . $c]) && $input['child_baggage_' . $c] != ''){
                        $input['child_baggage_' . $c] = json_decode($input['child_baggage_' . $c], true);
                        $flightPassengerArr[$counterchild]['Baggage'] = array();
                        array_push($flightPassengerArr[$counterchild]['Baggage'], $input['child_baggage_' . $c]);
                    }

                    if(isset($input['child_meallcc_' . $c]) && $input['child_meallcc_' . $c] != ''){
                        $input['child_meallcc_' . $c] = json_decode($input['child_meallcc_' . $c], true);
                        $flightPassengerArr[$counterchild]['MealDynamic'] = array();
                        array_push($flightPassengerArr[$counterchild]['MealDynamic'], $input['child_meallcc_' . $c]);
                    }

                    if(isset($input['child_baggage_return' . $c]) && $input['child_baggage_return' . $c] != ''){
                        $input['child_baggage_return' . $c] = json_decode($input['child_baggage_return' . $c], true);
                        
                        if(!isset($input['child_baggage_' . $c]) && $input['child_baggage_' . $c] == ''){
                         $flightPassengerArr[$counterchild]['Baggage'] = array();
                        }

                        array_push($flightPassengerArr[$counterchild]['Baggage'], $input['child_baggage_return' . $c]);
                    }

                    if(isset($input['child_meallcc_return' . $c]) && $input['child_meallcc_return' . $c] != ''){
                        $input['child_meallcc_return' . $c] = json_decode($input['child_meallcc_return' . $c], true);

                        if(!isset($input['child_meallcc_' . $c]) && $input['child_meallcc_' . $c] == ''){
                         $flightPassengerArr[$counterchild]['MealDynamic'] = array();
                        }

                        array_push($flightPassengerArr[$counterchild]['MealDynamic'], $input['child_meallcc_return' . $c]);
                    }

                    $counterchild++;
                }
            }

            // $roomCount++;
        }


        /* End Object */

        $this->flightapi = new TBOFlightAPI();


        $currency = Session::get('currency');
        $bookFlightArr = array('PreferredCurrency' => $currency,
            "IsBaseCurrencyRequired" => "true",
            "EndUserIp" => $this->flightapi->userIP,
            "TokenId" => $this->flightapi->tokenId,
            "TraceId" => $input['trace_id'],
            "ResultIndex" => $input['obindex'],
            "Passengers" => $flightPassengerArr
        );

        //echo "<pre>";print_r($bookFlightArr);echo "</pre>"; die;

        /* Booking For OB */

        try {

            /*
             * First check if customer exists in the DB
             */
            //$user = User::where('email', $userEmail)->first();

            if(Auth::user()) {
                $user = Auth::user();
            } else {
                $user = User::where('email', $userEmail)->first();
            }


            if (isset($user) && $user->id) {
                Auth::login($user);
            } else {
                //create new user
                $password = $this->generateRandomString();

                $user = User::create([
                            'name' => $userFirstName . ' ' . $userLastName,
                            'email' => $userEmail,
                            'phone' => $userPhone,
                            'address' => 'Bhopal',
                            'role' => 'user',
                            'password' => Hash::make($password),
                            'password_changed' => 1
                ]);
                //send email for create user
                Mail::to($userEmail)->send(new NewUserRegister($user, $password));
                Auth::login($user);
            }


            /* Getting Ticket For OB */

            if ($input['is_ob_lcc'] && $input['is_ob_lcc'] != '') {

                $this->bookingResult = $this->flightapi->ticket($bookFlightArr);

                if (isset($this->bookingResult['Response']) && isset($this->bookingResult['Response']['ResponseStatus']) && $this->bookingResult['Response']['ResponseStatus'] == 1) {

                    $bookingDetails = $this->bookingResult['Response'];
                    if ($bookingDetails['Response']['FlightItinerary']['IsLCC']) {
                        $bookingDetails['Response']['FlightItinerary']['IsLCC'] = 1;
                    } else {
                        $bookingDetails['Response']['FlightItinerary']['IsLCC'] = 0;
                    }

                    $travelArr['ticket_id'] = array();

                    foreach ($bookingDetails['Response']['FlightItinerary']['Passenger'] as $key => $value) {
                        # code...
                        array_push($travelArr['ticket_id'], $value['Ticket']['TicketId']);
                    }

                    // if (isset($input['walletPay']) && $input['walletPay'] == 'yes' && $input['walletDebit'] > 0) {

                    //     $myCurrency = Session::get('CurrencyCode');
                    //     $usercurrency = Currency::convert($myCurrency, 'USD', $input['walletDebit']);
                    //     $debitAmnt = round($usercurrency['convertedAmount']);


                    //     $walletuser = \Auth::user();
                    //     $walletuser->withdraw($debitAmnt, ['BookingID' => $bookingDetails['Response']['BookingId']]);
                        
                    //     $walletAmount = \Auth::user()->balance;
                    //     Session::put('walletAmount', $walletAmount);
                    // }


                    $booking = FlightBookings::create(['booking_id' => $bookingDetails['Response']['BookingId'],
                                'trace_id' => $bookingDetails['TraceId'],
                                'user_id' => Auth::user()->id,
                                'token_id' => $this->flightapi->tokenId,
                                'is_lcc' => $bookingDetails['Response']['FlightItinerary']['IsLCC'],
                                'flight_booking_status' => $bookingDetails['Response']['TicketStatus'],
                                'invoice_number' => $bookingDetails['Response']['FlightItinerary']['InvoiceNo'],
                                'pnr' => $bookingDetails['Response']['PNR'],
                                'booking_ref' => '',
                                'price_changed' => $bookingDetails['Response']['IsPriceChanged'],
                                'cancellation_policy' => $bookingDetails['Response']['FlightItinerary']['CancellationCharges'],
                                'request_data' => json_encode(array('travelData' => $travelArr, 'bookingData' => $bookFlightArr, 'razorpay_payment_id' => $input['razorpay_payment_id'],
                                    'razorpay_signature' => $input['razorpay_signature']))
                    ]);

                    $booking->request_data = array('travelData' => $travelArr, 'bookingData' => $bookFlightArr); //json_decode($booking->request_data, true);

                    $input['amount'] = $input['amount'] + $input['extra_baggage_meal_price'];

                    if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
                         $input['amount'] = round( $input['amount'] + $agent_makrup, 2);
                    }

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $input['amount']);
                    $mAmountC = $mAmount['convertedAmount'];

                    //create entry to FlightPayments table
                    $payments = FlightPayments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'agent_id' => $agent_id,
                                'commission' => $commission_agent,
                                'price' => $input['amount'],
                                'price_convered' => $mAmountC,
                                'agent_markup' => $markup_commission_agent,
                                'partners_commision' => $convertPrtnrAmount,
                                'partners_commision_rest' => $convertPrtnrestAmount,
                                'customer_id' => '',
                                'sub_domain' => '']);



                    $segments = $bookingDetails['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetails['Response']['FlightItinerary']['FareRules'];

                    if(isset($agent_id) && $agent_id != ''){ 

                        if($NoOfAdults == 1){
                            $adultText = 'Adult';
                        }else{
                            $adultText = 'Adults';
                        }

                        if(isset($NoOfChild) && $NoOfChild > 0 ){


                            if($NoOfChild == 1){
                                $childText = 'Child';
                            }else{
                                $childText = 'Childs';
                            }

                            $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' and '.$NoOfChild.' '.$childText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        }else{

                             $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';    
                        }  
                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => $post_content,
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }

                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){
                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    }

                    if (isset($input['ibindex']) && $input['ibindex'] != '') {
                        
                    } else {

                        $this->emptyFlightSession();
                        //send to thank you page
                        $hotelId = $this->bookFlightRoom($input);

                        if(isset($hotelId['success']) && $hotelId['success']){

                            // return redirect('/thankyou/flight-hotel/' . $hotelId['booking_id'] . '/'.$booking->id);
                            return array('hotelId' => $hotelId['booking_id'], 'flightId' => $booking->id, 'success' =>true);

                        }else{
                            // return view('500')->with(['error' => $hotelId['message']]);  
                            return array('message' => $hotelId['message'], 'success' =>false);
                        }


                    }
                } else {
                    $message = $this->bookingResult['Response']['Error']['ErrorMessage'];
                    //echo "<pre>";print_r($message);echo"</pre>";
                    // return view('500')->with(['error' => $message]);
                    return array('message' => $message, 'success' =>false);
                }
            } else {

                $this->bookingResultNOLCC = $this->flightapi->book($bookFlightArr);

                $bookingDetailsNOLCC = $this->bookingResultNOLCC['Response'];

                //echo "<pre>";print_r($bookingDetailsNOLCC);echo "</pre>";

                if ($bookingDetailsNOLCC['ResponseStatus'] != 1) {
                    $this->emptyFlightSession();
                    // return view('error')->with(['error' => $bookingDetailsNOLCC['Error']['ErrorMessage']]);
                    // return view('500')->with(['error' => $bookingDetailsNOLCC['Error']['ErrorMessage']]);
                    return array('message' => $bookingDetailsNOLCC['Error']['ErrorMessage'], 'success' =>false);
                }

                $this->TicketResult = $this->flightapi->getNoLCCTicket($input['trace_id'], $bookingDetailsNOLCC['Response']['BookingId'], $bookingDetailsNOLCC['Response']['PNR']);

                //$ticketDetailsNOLCC = $this->TicketResult['Response'];

                if (isset($this->TicketResult['Response']) && isset($this->TicketResult['Response']['ResponseStatus']) && $this->TicketResult['Response']['ResponseStatus'] == 1) {

                    $ticketDetailsNOLCC = $this->TicketResult['Response'];

                    if ($ticketDetailsNOLCC['Response']['FlightItinerary']['IsLCC']) {
                        $ticketDetailsNOLCC['Response']['FlightItinerary']['IsLCC'] = 1;
                    } else {
                        $ticketDetailsNOLCC['Response']['FlightItinerary']['IsLCC'] = 0;
                    }

                    $travelArr['ticket_id'] = array();

                    foreach ($ticketDetailsNOLCC['Response']['FlightItinerary']['Passenger'] as $key => $value) {
                        # code...
                        array_push($travelArr['ticket_id'], $value['Ticket']['TicketId']);
                    }

                    if (isset($input['walletPay']) && $input['walletPay'] == 'yes' && $input['walletDebit'] > 0) {

                        $myCurrency = Session::get('CurrencyCode');
                        $usercurrency = Currency::convert($myCurrency, 'USD', $input['walletDebit']);
                        $debitAmnt = round($usercurrency['convertedAmount']);


                        $walletuser = \Auth::user();
                        $walletuser->withdraw($debitAmnt, ['BookingID' => $ticketDetailsNOLCC['Response']['BookingId']]);
                    }

                    $booking = FlightBookings::create(['booking_id' => $ticketDetailsNOLCC['Response']['BookingId'],
                                'trace_id' => $ticketDetailsNOLCC['TraceId'],
                                'user_id' => Auth::user()->id,
                                'token_id' => $this->flightapi->tokenId,
                                'is_lcc' => $ticketDetailsNOLCC['Response']['FlightItinerary']['IsLCC'],
                                'flight_booking_status' => $ticketDetailsNOLCC['Response']['TicketStatus'],
                                'invoice_number' => $ticketDetailsNOLCC['Response']['FlightItinerary']['InvoiceNo'],
                                'pnr' => $ticketDetailsNOLCC['Response']['PNR'],
                                'booking_ref' => '',
                                'price_changed' => $ticketDetailsNOLCC['Response']['IsPriceChanged'],
                                'cancellation_policy' => $ticketDetailsNOLCC['Response']['FlightItinerary']['CancellationCharges'],
                                'request_data' => json_encode(array('travelData' => $travelArr, 'bookingData' => $bookFlightArr, 'razorpay_payment_id' => $input['razorpay_payment_id'],
                                    'razorpay_signature' => $input['razorpay_signature']))
                    ]);

                    $booking->request_data = array('travelData' => $travelArr, 'bookingData' => $bookFlightArr);

                    //create entry to FlightPayments table
                    $input['amount'] = $input['amount'] + $input['extra_baggage_meal_price'];

                    if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
                         $input['amount'] = round( $input['amount'] + $agent_makrup, 2);
                    }
                    

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $input['amount']);
                    $mAmountC = $mAmount['convertedAmount'];

                    $payments = FlightPayments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'agent_id' => $agent_id,
                                'commission' => $commission_agent,
                                'price' => $input['amount'],
                                'price_convered' => $mAmountC,
                                'agent_markup' => $markup_commission_agent,
                                'partners_commision' => $convertPrtnrAmount,
                                'partners_commision_rest' => $convertPrtnrestAmount,
                                'customer_id' => '',
                                'sub_domain' => '']);


                    $segments = $bookingDetailsNOLCC['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetailsNOLCC['Response']['FlightItinerary']['FareRules'];

                    if(isset($agent_id) && $agent_id != ''){   

                        if($NoOfAdults == 1){
                            $adultText = 'Adult';
                        }else{
                            $adultText = 'Adults';
                        }

                        if(isset($NoOfChild) && $NoOfChild > 0 ){


                            if($NoOfChild == 1){
                                $childText = 'Child';
                            }else{
                                $childText = 'Childs';
                            }

                            $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' and '.$NoOfChild.' '.$childText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        }else{

                             $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';    
                        } 
                        
                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => $post_content,
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }

                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){
                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    }
 

                    if (isset($input['ibindex']) && $input['ibindex'] != '') {
                        
                    } else {

                        $this->emptyFlightSession();
                        //send to thank you page
                        $hotelId = $this->bookFlightRoom($input);

                       if(isset($hotelId['success']) && $hotelId['success']){

                            // return redirect('/thankyou/flight-hotel/' . $hotelId['booking_id'] . '/'.$booking->id);
                            return array('hotelId' => $hotelId['booking_id'], 'flightId' => $booking->id,'success' =>true);

                        }else{
                            return array('message' => $hotelId['message'], 'success' =>false);
                            // return view('500')->with(['error' => $hotelId['message']]);  
                        }
                    }
                } else {
                    $message = $this->TicketResult['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    // return view('500')->with(['error' => $message]);
                    return array('message' => $message, 'success' =>false);
                }
            }
        } catch (\Stripe\Error\RateLimit $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\InvalidRequest $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\Authentication $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\ApiConnection $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\Base $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        /* Booking Done */


        /* Getting Ticket For OB */
        //$this->bookingResult = $this->flightapi->ticket($bookFlightArr);

        /* Getting Ticket For IB */

        if (isset($input['ibindex']) && $input['ibindex'] != '') {

            $flightPassengerArrIB = array();
            $fareQuoteIB = json_decode($input['farebreakDownIB'], true);

            $counterchildIB = $adultCount;


            foreach ($roomGuest as $room => $guest) {
            // echo $room;
            // print_r($guest);
            $NoOfAdults = sizeof($guest['adult']);
            if (isset($guest['child'])) {

                $NoOfChild = sizeof($guest['child']);
            }
            $hotelPassengers[$room] = array();

            for ($a = 1; $a <= $NoOfAdults; $a++) {
                // echo $a . '\n';
                if ($room == 1 && $a == 1) {
                    $userFirstName = $guest['adult'][$a]['first_name'];
                    $userLastName = $guest['adult'][$a]['last_name'];
                    $userEmail = $guest['adult'][$a]['email'];
                    $userPhone = $guest['adult'][$a]['phone'];
                }
                if( isset($guest['adult'][$a]['passportNo']) ){

                    array_push($flightPassengerArrIB, array(
                        "Title" => $guest['adult'][$a]['title'],
                        "FirstName" => $guest['adult'][$a]['first_name'],
                        "LastName" => $guest['adult'][$a]['last_name'],
                        "PaxType" => 1,
                        "DateOfBirth" => isset($guest['adult'][$a]['adult_dob']) ? $guest['adult'][$a]['adult_dob'] : "1985-01-30T00:00:00",
                        "Gender" => ($guest['adult'][$a]['title'] == 'Mr') ? 1 : 2,
                        "AddressLine1" => $guest['adult'][$a]['address'],
                        "AddressLine2" => "",
                        "City" => $guest['adult'][$a]['address'],
                        "CountryCode" => $location->countryCode,
                        "CountryName" => $location->countryName,
                        "PassportNo" => isset($guest['adult'][$a]['passportNo']) ? $guest['adult'][$a]['passportNo'] : "",
                        "PassportExpiry" => isset($guest['adult'][$a]['passportExpire']) ? $guest['adult'][$a]['passportExpire'] : "",
                        "ContactNo" => $guest['adult'][$a]['phone'],
                        "Email" => $guest['adult'][$a]['email'],
                        "IsLeadPax" =>  ($a == 1) ? true : false,
                        "FFAirline" => "",
                        "FFNumber" => "",
                        "Nationality" => "",
                        "Fare" => array(
                            "BaseFare" => $fareQuoteIB[0]['BaseFare'] / $fareQuoteIB[0]['PassengerCount'],
                            "Tax" => $fareQuoteIB[0]['Tax'] / $fareQuoteIB[0]['PassengerCount'],
                            "YQTax" => $fareQuoteIB[0]['YQTax'] / $fareQuoteIB[0]['PassengerCount'],
                            "AdditionalTxnFeeOfrd" => $fareQuoteIB[0]['AdditionalTxnFeeOfrd'] / $fareQuoteIB[0]['PassengerCount'],
                            "AdditionalTxnFeePub" => $fareQuoteIB[0]['AdditionalTxnFeePub'] / $fareQuoteIB[0]['PassengerCount'],
                            "PGCharge" => $fareQuoteIB[0]['PGCharge'] / $fareQuoteIB[0]['PassengerCount']
                        )
                    ));
                }else{

                    array_push($flightPassengerArrIB, array(
                        "Title" => $guest['adult'][$a]['title'],
                        "FirstName" => $guest['adult'][$a]['first_name'],
                        "LastName" => $guest['adult'][$a]['last_name'],
                        "PaxType" => 1,
                        "DateOfBirth" => isset($guest['adult'][$a]['adult_dob']) ? $guest['adult'][$a]['adult_dob'] : "1985-01-30T00:00:00",
                        "Gender" => ($guest['adult'][$a]['title'] == 'Mr') ? 1 : 2,
                        "AddressLine1" => $guest['adult'][$a]['address'],
                        "AddressLine2" => "",
                        "City" => $guest['adult'][$a]['address'],
                        "CountryCode" => $location->countryCode,
                        "CountryName" => $location->countryName,
                        "ContactNo" => $guest['adult'][$a]['phone'],
                        "PassportNo" => "KJHHJKHKJH",
                        "PassportExpiry"=> "2025-08-30",
                        "Email" => $guest['adult'][$a]['email'],
                        "IsLeadPax" =>  ($a == 1) ? true : false,
                        "FFAirline" => "",
                        "FFNumber" => "",
                        "Nationality" => "",
                        "Fare" => array(
                            "BaseFare" => $fareQuoteIB[0]['BaseFare'] / $fareQuoteIB[0]['PassengerCount'],
                            "Tax" => $fareQuoteIB[0]['Tax'] / $fareQuoteIB[0]['PassengerCount'],
                            "YQTax" => $fareQuoteIB[0]['YQTax'] / $fareQuoteIB[0]['PassengerCount'],
                            "AdditionalTxnFeeOfrd" => $fareQuoteIB[0]['AdditionalTxnFeeOfrd'] / $fareQuoteIB[0]['PassengerCount'],
                            "AdditionalTxnFeePub" => $fareQuoteIB[0]['AdditionalTxnFeePub'] / $fareQuoteIB[0]['PassengerCount'],
                            "PGCharge" => $fareQuoteIB[0]['PGCharge'] / $fareQuoteIB[0]['PassengerCount']
                        )
                    ));

                }

                if(isset($input['baggage_return_ib' . $a]) && $input['baggage_return_ib' . $a] != ''){
                    $input['baggage_return_ib' . $a] = json_decode($input['baggage_return_ib' . $a], true);
                    $flightPassengerArrIB[$a-1]['Baggage'] = array();
                    array_push($flightPassengerArrIB[$a-1]['Baggage'], $input['baggage_return_ib' . $a]);
                }

                if(isset($input['meallcc_return_ib' . $a]) && $input['meallcc_return_ib' . $a] != ''){
                    $input['meallcc_return_ib' . $a] = json_decode($input['meallcc_return_ib' . $a], true);
                    $flightPassengerArrIB[$a-1]['MealDynamic'] = array();
                    array_push($flightPassengerArrIB[$a-1]['MealDynamic'], $input['meallcc_return_ib' . $a]);
                }


            }

            if (isset($NoOfChild)) {

                for ($c = 1; $c <= $NoOfChild; $c++) {

                    if(isset($guest['child'][$c]['child_passport_no'])){

                            array_push($flightPassengerArrIB, array(
                                "Title" => $guest['child'][$c]['title'],
                                "FirstName" => $guest['child'][$c]['first_name'],
                                "LastName" => $guest['child'][$c]['last_name'],
                                "PaxType" => 2,
                                "DateOfBirth" => date('Y-m-d', strtotime($guest['child'][$c]['child_dob'])) . "T00:00:00",
                                "Gender" => ($guest['child'][$c]['title']) ? 1 : 2,
                                "AddressLine1" => $guest['child'][$c]['address'],
                                "AddressLine2" => "",
                                "City" => $guest['child'][$c]['address'],
                                "CountryCode" => $location->countryCode,
                                "CountryName" => $location->countryName,
                                "PassportNo" => isset($guest['child'][$c]['child_passport_no']) ? $guest['child'][$c]['child_passport_no'] : "",
                                "PassportExpiry" => isset($guest['child'][$c]['child_pass_expiry_date']) ? $guest['child'][$c]['child_pass_expiry_date']: "",
                                "ContactNo" => $guest['child'][$c]['child_phone'],
                                "Email" => $guest['child'][$c]['child_email'],
                                "IsLeadPax" => false,
                                "FFAirline" => "",
                                "FFNumber" => "",
                                "Nationality" => "",
                                "Fare" => array(
                                    "BaseFare" => $fareQuoteIB[1]['BaseFare'] / $fareQuoteIB[1]['PassengerCount'],
                                    "Tax" => $fareQuoteIB[1]['Tax'] / $fareQuoteIB[1]['PassengerCount'],
                                    "YQTax" => $fareQuoteIB[1]['YQTax'] / $fareQuoteIB[1]['PassengerCount'],
                                    "AdditionalTxnFeeOfrd" => $fareQuoteIB[1]['AdditionalTxnFeeOfrd'] / $fareQuoteIB[1]['PassengerCount'],
                                    "AdditionalTxnFeePub" => $fareQuoteIB[1]['AdditionalTxnFeePub'] / $fareQuoteIB[1]['PassengerCount'],
                                    "PGCharge" => $fareQuoteIB[1]['PGCharge'] / $fareQuoteIB[1]['PassengerCount']
                                )
                             ));

                        }else{

                            array_push($flightPassengerArrIB, array(
                                "Title" => $guest['child'][$c]['title'],
                                "FirstName" => $guest['child'][$c]['first_name'],
                                "LastName" => $guest['child'][$c]['last_name'],
                                "PaxType" => 2,
                                "DateOfBirth" => date('Y-m-d', strtotime($guest['child'][$c]['child_dob'])) . "T00:00:00",
                                "Gender" => ($guest['child'][$c]['title']) ? 1 : 2,
                                "AddressLine1" => $guest['child'][$c]['address'],
                                "AddressLine2" => "",
                                "City" => $guest['child'][$c]['address'],
                                "CountryCode" => $location->countryCode,
                                "CountryName" => $location->countryName,
                                "ContactNo" => $guest['child'][$c]['child_phone'],
                                "Email" => $guest['child'][$c]['child_email'],
                                "PassportNo" => "KJHHJKHKJH",
                                "PassportExpiry"=> "2025-08-30",
                                "IsLeadPax" => false,
                                "FFAirline" => "",
                                "FFNumber" => "",
                                "Nationality" => "",
                                "Fare" => array(
                                    "BaseFare" => $fareQuoteIB[1]['BaseFare'] / $fareQuoteIB[1]['PassengerCount'],
                                    "Tax" => $fareQuoteIB[1]['Tax'] / $fareQuoteIB[1]['PassengerCount'],
                                    "YQTax" => $fareQuoteIB[1]['YQTax'] / $fareQuoteIB[1]['PassengerCount'],
                                    "AdditionalTxnFeeOfrd" => $fareQuoteIB[1]['AdditionalTxnFeeOfrd'] / $fareQuoteIB[1]['PassengerCount'],
                                    "AdditionalTxnFeePub" => $fareQuoteIB[1]['AdditionalTxnFeePub'] / $fareQuoteIB[1]['PassengerCount'],
                                    "PGCharge" => $fareQuoteIB[1]['PGCharge'] / $fareQuoteIB[1]['PassengerCount']
                                )
                             ));

                        }

                        if(isset($input['child_baggage_return_ib' . $c]) && $input['child_baggage_return_ib' . $c] != ''){

                            $input['child_baggage_return_ib' . $c] = json_decode($input['child_baggage_return_ib' . $c], true);
                            $flightPassengerArrIB[$counterchildIB]['Baggage'] = array();

                            array_push($flightPassengerArrIB[$counterchildIB]['Baggage'], $input['child_baggage_return_ib' . $c]);

                        }

                        if(isset($input['child_meallcc_return_ib' . $c]) && $input['child_meallcc_return_ib' . $c] != ''){

                            $input['child_meallcc_return_ib' . $c] = json_decode($input['child_meallcc_return_ib' . $c], true);
                            $flightPassengerArrIB[$counterchildIB]['MealDynamic'] = array();

                            array_push($flightPassengerArrIB[$counterchildIB]['MealDynamic'], $input['child_meallcc_return_ib' . $c]);

                        }

                        $counterchildIB++;

                }
            }

            // $roomCount++;
        }


            /* End Object */
            $currency = Session::get('currency');
            $bookFlightArrIB = array('PreferredCurrency' => $currency,
                "IsBaseCurrencyRequired" => "true",
                "EndUserIp" => $this->flightapi->userIP,
                "TokenId" => $this->flightapi->tokenId,
                "TraceId" => $input['trace_id'],
                "ResultIndex" => $input['ibindex'],
                "Passengers" => $flightPassengerArrIB
            );

            if ($input['is_ib_lcc'] && $input['is_ib_lcc'] != '') {

                $this->bookingResultIB = $this->flightapi->ticketIB($bookFlightArrIB);

                if (isset($this->bookingResultIB['Response']) && isset($this->bookingResultIB['Response']['ResponseStatus']) && $this->bookingResultIB['Response']['ResponseStatus'] == 1) {

                    $bookingDetails = $this->bookingResultIB['Response'];

                    $travelArr['ticket_id'] = array();

                    foreach ($bookingDetails['Response']['FlightItinerary']['Passenger'] as $key => $value) {
                        # code...
                        array_push($travelArr['ticket_id'], $value['Ticket']['TicketId']);
                    }

                    //create entry on FlightBookings table

                    $booking = FlightBookings::create(['booking_id' => $bookingDetails['Response']['BookingId'],
                                'trace_id' => $bookingDetails['TraceId'],
                                'user_id' => Auth::user()->id,
                                'token_id' => $this->flightapi->tokenId,
                                'is_lcc' => $bookingDetails['Response']['FlightItinerary']['IsLCC'],
                                'flight_booking_status' => $bookingDetails['Response']['TicketStatus'],
                                'invoice_number' => $bookingDetails['Response']['FlightItinerary']['InvoiceNo'],
                                'pnr' => $bookingDetails['Response']['PNR'],
                                'booking_ref' => $bookingDetails['Response']['FlightItinerary']['ParentBookingId'],
                                'price_changed' => $bookingDetails['Response']['IsPriceChanged'],
                                'cancellation_policy' => $bookingDetails['Response']['FlightItinerary']['CancellationCharges'],
                                'request_data' => json_encode(array('travelData' => $travelArr, 'bookingData' => $bookFlightArrIB, 'razorpay_payment_id' => $input['razorpay_payment_id'],
                                    'razorpay_signature' => $input['razorpay_signature']))
                    ]);

                    //create entry to FlightPayments table
                    // $payments = FlightPayments::create(['booking_id' => $booking->id,
                    //                   'user_id' => Auth::user()->id,
                    //                   'agent_id' => $agent_id,
                    //                   'commission' => $commission_agent,
                    //                   'price' => $input['amount'],
                    //                   'customer_id' => "",
                    //                   'sub_domain' => '']);

                    $payments = array();

                    $travelArr['city_code_arrival'] = $flightSearch['destination'];
                    $travelArr['city_code_departure'] = $flightSearch['origin'];


                    $travelArr['main_start'] = $flightSearch['to'];
                    $travelArr['to_start'] = $flightSearch['from'];
                    $travelArr['departure_date_arr'] = $input['departure_date_dep'];

                    $booking->request_data = array('travelData' => $travelArr, 'bookingData' => $bookFlightArr);

                    $segments = $bookingDetails['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetails['Response']['FlightItinerary']['FareRules'];

                    if(isset($agent_id) && $agent_id != ''){   

                        if($NoOfAdults == 1){
                            $adultText = 'Adult';
                        }else{
                            $adultText = 'Adults';
                        }

                        if(isset($NoOfChild) && $NoOfChild > 0 ){


                            if($NoOfChild == 1){
                                $childText = 'Child';
                            }else{
                                $childText = 'Childs';
                            }

                            $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' and '.$NoOfChild.' '.$childText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        }else{

                             $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';    
                        }

                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => $post_content,
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }


                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){
                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    }
                    //Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $bookingDetails['Response']['FlightItinerary']['Segments'], $bookingDetails['Response']['FlightItinerary']['FareRules']));

                    $this->emptyFlightSession();
                    //send to thank you page
                    $hotelId = $this->bookFlightRoom($input);

                    if(isset($hotelId['success']) && $hotelId['success']){

                        // return redirect('/thankyou/flight-hotel/' . $hotelId['booking_id'] . '/'.$booking->id);
                        return array('hotelId' => $hotelId['booking_id'], 'flightId' => $booking->id,'success' =>true);
                    }else{
                        // return view('500')->with(['error' => $hotelId['message']]);  
                        return array('message' => $hotelId['message'], 'success' =>false);
                    }
                    //send to thank you page
                   // return redirect('/thankyou/flight/' . $booking->id . '/true');
                } else {
                    $message = $this->bookingResultIB['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    // return view('500')->with(['error' => $message]);
                    return array('message' => $message, 'success' =>false);
                }
            } else {

                $this->bookingResultIBNOLCC = $this->flightapi->bookIB($bookFlightArrIB);

                $bookingDetailsIBNOLCC = $this->bookingResultIBNOLCC['Response'];

                //echo "<pre>";print_r($bookingDetailsNOLCC);echo "</pre>";
                if ($bookingDetailsIBNOLCC['ResponseStatus'] != 1) {
                    $this->emptyFlightSession();
                    // return view('error')->with(['error' => $bookingDetailsIBNOLCC['Error']['ErrorMessage']]);
                    // return view('500')->with(['error' => $bookingDetailsIBNOLCC['Error']['ErrorMessage']]);
                    return array('message' => $bookingDetailsIBNOLCC['Error']['ErrorMessage'], 'success' =>false);
                }
                $this->TicketResultIB = $this->flightapi->getNoLCCTicketIB($input['trace_id'], $bookingDetailsIBNOLCC['Response']['BookingId'], $bookingDetailsIBNOLCC['Response']['PNR']);

                //$ticketDetailsNOLCC = $this->TicketResult['Response'];

                if (isset($this->TicketResultIB['Response']) && isset($this->TicketResultIB['Response']['ResponseStatus']) && $this->TicketResultIB['Response']['ResponseStatus'] == 1) {

                    $ticketDetailsIBNOLCC = $this->TicketResultIB['Response'];

                    if ($ticketDetailsIBNOLCC['Response']['FlightItinerary']['IsLCC']) {
                        $ticketDetailsIBNOLCC['Response']['FlightItinerary']['IsLCC'] = 1;
                    } else {
                        $ticketDetailsIBNOLCC['Response']['FlightItinerary']['IsLCC'] = 0;
                    }

                    $travelArr['ticket_id'] = array();

                    foreach ($ticketDetailsIBNOLCC['Response']['FlightItinerary']['Passenger'] as $key => $value) {
                        # code...
                        array_push($travelArr['ticket_id'], $value['Ticket']['TicketId']);
                    }


                    $booking = FlightBookings::create(['booking_id' => $ticketDetailsIBNOLCC['Response']['BookingId'],
                                'trace_id' => $ticketDetailsIBNOLCC['TraceId'],
                                'user_id' => Auth::user()->id,
                                'token_id' => $this->flightapi->tokenId,
                                'is_lcc' => $ticketDetailsIBNOLCC['Response']['FlightItinerary']['IsLCC'],
                                'flight_booking_status' => $ticketDetailsIBNOLCC['Response']['TicketStatus'],
                                'invoice_number' => $ticketDetailsIBNOLCC['Response']['FlightItinerary']['InvoiceNo'],
                                'pnr' => $ticketDetailsIBNOLCC['Response']['PNR'],
                                'booking_ref' => $ticketDetailsIBNOLCC['Response']['FlightItinerary']['ParentBookingId'],
                                'price_changed' => $ticketDetailsIBNOLCC['Response']['IsPriceChanged'],
                                'cancellation_policy' => $ticketDetailsIBNOLCC['Response']['FlightItinerary']['CancellationCharges'],
                                'request_data' => json_encode(array('travelData' => $travelArr, 'bookingData' => $bookFlightArrIB, 'razorpay_payment_id' => $input['razorpay_payment_id'],
                                    'razorpay_signature' => $input['razorpay_signature']))
                    ]);

                    //create entry to FlightPayments table
                    // $payments = FlightPayments::create(['booking_id' => $booking->id,
                    //                   'user_id' => Auth::user()->id,
                    //                   'agent_id' => $agent_id,
                    //                   'commission' => $commission_agent,
                    //                   'price' => $input['amount'],
                    //                   'customer_id' => "",
                    //                   'sub_domain' => '']);

                    $payments = array();


                    $travelArr['city_code_arrival'] = $flightSearch['destination'];
                    $travelArr['city_code_departure'] = $flightSearch['origin'];


                    $travelArr['main_start'] = $flightSearch['to'];
                    $travelArr['to_start'] = $flightSearch['from'];
                    $travelArr['departure_date_arr'] = $input['departure_date_dep'];

                    $booking->request_data = array('travelData' => $travelArr, 'bookingData' => $bookFlightArr);


                    $segments = $bookingDetailsIBNOLCC['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetailsIBNOLCC['Response']['FlightItinerary']['FareRules'];

                    if(isset($agent_id) && $agent_id != ''){   

                        //$post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . Session::get('flightSearhData')['travellersClass'] . '</b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';


                        if($NoOfAdults == 1){
                            $adultText = 'Adult';
                        }else{
                            $adultText = 'Adults';
                        }

                        if(isset($NoOfChild) && $NoOfChild > 0 ){


                            if($NoOfChild == 1){
                                $childText = 'Child';
                            }else{
                                $childText = 'Childs';
                            }

                            $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' and '.$NoOfChild.' '.$childText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        }else{

                             $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';    
                        }

                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => $post_content,
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }
                    

                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){
                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    }
                    //Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $ticketDetailsIBNOLCC['Response']['FlightItinerary']['Segments'], $ticketDetailsIBNOLCC['Response']['FlightItinerary']['FareRules']));
                    //Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $ticketDetailsIBNOLCC['Response']['FlightItinerary']['Segments'], $ticketDetailsIBNOLCC['Response']['FlightItinerary']['FareRules']));

                    $this->emptyFlightSession();
                    //send to thank you page
                    $hotelId = $this->bookFlightRoom($input);

                    if(isset($hotelId['success']) && $hotelId['success']){

                        // return redirect('/thankyou/flight-hotel/' . $hotelId['booking_id'] . '/'.$booking->id);
                        return array('hotelId' => $hotelId['booking_id'], 'flightId' => $booking->id,'success' =>true);

                    }else{
                        
                        // return view('500')->with(['error' => $hotelId['message']]);  
                        return array('message' => $hotelId['message'], 'success' =>false);
                    }
                    
                    //send to thank you page
                   // return redirect('/thankyou/flight/' . $booking->id . '/true');
                } else {
                    $message = $this->TicketResultIB['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    // return view('500')->with(['error' => $message]);
                    return array('message' => $message, 'success' =>false);
                }
            }
        }

    }

    public function bookFlightRoom($data) {

        //$input = $request->all();
        $input = $data;

        $installments = $input['installments'];

        $roomPassengers = $input['roomPassengers'];

        $search_id_hotel = $input['search_id_hotel'];

        $destinationPath=$search_id_hotel . '_block.json';
        $roomDetails = json_decode($this->getBlockRoomData($destinationPath), true);///Session::get('BookRoomDetails');
        $BookRoomDetails = $roomDetails['BlockRoomResult']['HotelRoomsDetails'];

        // echo "<pre>";
        $hotelPassengers = array();
        $roomGuest = $input['roomPassengers']; //Session::get('roomGuests');

        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $getlocation = json_decode($this->getCookie('th_country'));

        } else {

            $getlocation = \Location::get($this->end_user_ip);
        }
        $guestNationality = $getlocation->countryCode;
        //   print_r($BookRoomDetails); die();
        $roomCount = 1;
        $userEmail = '';
        $userFirstName = '';
        $userLastName = '';
        $userPhone = '';

        if ($input['referral'] != '' && $input['referral'] != '0') {

            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();
            //$commisioninis = env('INIS_VAL');
            if (isset($checkrefferal)) {

                $agent_id = $checkrefferal['user_id'];
                $agentemail = User::select('email')->where(['id' => $agent_id])->first();
                $agentemail = $agentemail['email'];
                $commission = env('INIS_VAL');
            } else {
                $agentemail = '';
                $agent_id = '';
                $commission = env('INIS_VAL');
            }
        } else {

            $agentemail = '';
            $agent_id = '';
            $commission = env('INIS_VAL');
        }

        $this->hotelCode = Session::get('hotelCode');

        if ($input['referral'] != '' && $input['referral'] != '0') {
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

            if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                $commission = 10;
            }
        }

        // print_r($BookRoomDetails);

        foreach ($roomGuest as $room => $guest) {
            // echo $room;
            // print_r($guest);
            $NoOfAdults = sizeof($guest['adult']);

            if (isset($guest['child'])) {

                //$NoOfChild = $guest['child'];
                $NoOfChild = sizeof($guest['child']);
            }


            $hotelPassengers[$room] = array();

            for ($a = 1; $a <= $NoOfAdults; $a++) {
                // echo $a . '\n';
                if ($room == 1 && $a == 1) {
                    $userFirstName = $guest['adult'][$a]['first_name'];
                    $userLastName = $guest['adult'][$a]['last_name'];
                    $userEmail = $guest['adult'][$a]['email'];
                    $userPhone = $guest['adult'][$a]['phone'];
                }
                array_push($hotelPassengers[$room], array(
                    'Title' => $guest['adult'][$a]['title'],
                    'FirstName' => $guest['adult'][$a]['first_name'],
                    'Middlename' => null,
                    'LastName' => $guest['adult'][$a]['last_name'],
                    'Phoneno' => isset($guest['adult'][$a]['phone']) ? $guest['adult'][$a]['phone'] : null,
                    'Email' => isset($guest['adult'][$a]['email']) ? $guest['adult'][$a]['email'] : null,
                    'PaxType' => 1,
                    'PassportNo' => isset($guest['adult'][$a]['passportNo']) ? $guest['adult'][$a]['passportNo'] : null,
                    'PAN' => isset($guest['adult'][$a]['panNo']) ? $guest['adult'][$a]['panNo'] : null,
                    'LeadPassenger' => ($a == 1) ? true : false,
                    'Age' => 0
                ));
            }
            if (isset($NoOfChild)) {

                for ($c = 1; $c <= $NoOfChild; $c++) {

                    array_push($hotelPassengers[$room], array(
                        'Title' => 'Mr',
                        'FirstName' => $guest['child'][$c]['first_name'],
                        'Middlename' => null,
                        'LastName' => $guest['child'][$c]['last_name'],
                        'PAN' => isset($guest['child'][$c]['panNo']) ? $guest['child'][$c]['panNo'] : null,
                        'PaxType' => 2,
                        'Age' => "4"
                    ));
                }
            }

            // $roomCount++;
        }

        $HotelRoomsDetails = array();
        $roomCountDetails = 1;
        $amount = 0;
        $oldAmount = 0;
        $base_price = 0;
        $tax_price = 0;

        $amount_temp = 0;
        $base_price_temp = 0;
        $tax_price_temp = 0;

        if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {

            $agent_makrup = $input['agent_makrup'] / 2 ;
        }
        // echo "<pre>";
        foreach ($BookRoomDetails as $room) {

            $price = $room['Price'];
            unset($price['GST']);
            unset($price['ServiceCharge']);
            unset($price['TotalGSTAmount']);
            array_push($HotelRoomsDetails, array(
                'RoomIndex' => $room['RoomIndex'],
                'RoomTypeCode' => $room['RoomTypeCode'],
                'RoomTypeName' => $room['RoomTypeName'],
                'RatePlanCode' => $room['RatePlanCode'],
                'BedTypeCode' => null,
                'SmokingPreference' => 0,
                'Supplements' => null,
                'Price' => $price,
                'HotelPassenger' => $hotelPassengers[$roomCountDetails]
            ));


            $amount = $amount + $price['OfferedPriceRoundedOff'];

            $oldAmount = $oldAmount + $price['OfferedPriceRoundedOff'];

            $amount_temp = $amount_temp + $price['OfferedPriceRoundedOff'];


            $roomCountDetails++;
        }


        $commission_price = ($commission / 100 * $amount_temp);

        if (Session::get('CurrencyCode') == 'ILS') {

            $commission_price = (env('INIS_VAL_PAYME') / 100 * $amount_temp);
        }

        $amount = $amount + $commission_price;
        $commission_agent = $commission_price;
        $add_to_subdomain = false;

        $commission_sub_doamin = 0;
        //split comission if booking from sub somain hotel
        if ($input['referral'] && $input['referral'] != '' && $input['referral'] != '0') {
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

            if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                $commisioninis = 10;

                $commission_agent = (5 / 100 * $amount_temp);
                $commission_sub_doamin = $commission_agent;

                $add_to_subdomain = true;
            }
        } else {
            //check if only sub domain is there
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

            if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                $commisioninis = 5;

                $commission_agent = 0;
                $commission_sub_doamin = (5 / 100 * $amount_temp);

                $add_to_subdomain = true;
            }
        }

        $markup_commission_agent = 0;
        if ($commission_agent > 0) {

            if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
                //$commission_agent = $commission_agent + ($agent_makrup * 0.60);

                $agent_markup_commision = $agent_makrup * 0.60;
                $markUplAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $agent_markup_commision);
                $markup_commission_agent = $markUplAmount['convertedAmount'];


                $partners_markup = env('PARTNER_COMMISION_MARKUP');

                $partners_markup_rest = env('PARTNER_COMMISION_REST_MARKUP');

                $markupPartner = round($agent_makrup  * 0.40);

                $markupPartnerCommission = ( ( $partners_markup / 100 ) * $markupPartner);

                $markupPartnerRestCommission = ( ( $partners_markup_rest / 100 ) * $markupPartner);

                $convertPrtnr = Currency::convert(Session::get('CurrencyCode'), 'USD', $markupPartnerCommission );
                $convertPrtnrAmount = $convertPrtnr['convertedAmount'];

                $convertPrtnrest = Currency::convert(Session::get('CurrencyCode'), 'USD', $markupPartnerRestCommission );
                $convertPrtnrestAmount = $convertPrtnrest['convertedAmount'];


            }else{

                $convertPrtnrAmount = 0;
                $convertPrtnrestAmount = 0;
            }

            $lAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $commission_agent);
            $commission_agent = $lAmount['convertedAmount'];
        }

        if ($commission_sub_doamin > 0) {
            $lAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $commission_sub_doamin);
            $commission_sub_doamin = $lAmount['convertedAmount'];
        }

        $conversion_payment = 0;
        if (Session::get('CurrencyCode') == 'ILS') {

             $conversion = env('PAYME_FEES');
        }else if (Session::get('currency') == 'INR') {
            $conversion = env('INR_FEES');
        } else {
            $conversion = env('INT_FEES');
        }

        $ils_conversion = 0;

        $ils_install = 0;

        $vat = 0;


        if (Session::get('CurrencyCode') == 'ILS') {

            $conversion_payment = ( $conversion / 100 * $amount ) + env('PAYME_FIX_FEES');

            $ils_conversion = $conversion_payment;

            $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $conversion_payment );

            if($installments > 1){
                
                $installments = 0.5 * $installments;

                $ils_install = ( ( $installments / 100 ) * ( $amount + $conversion_payment ) );
            }


            $conversion_payment = $conversion_payment + $ils_install;

            //$conversion_payment = round($conversion_payment , 2);

        
        }else{

            $conversion_payment = ( $conversion / 100 * $amount );

        }
        //$conversion_payment = ( $conversion / 100 * $amount );

        $amount = $amount + $conversion_payment;

        $amount = round($amount * 100, 2);

        $this->hotelName = $input['hotelName'];
        $this->hotelCode = $input['hotelCode'];
        $this->hoteIndex = $input['hotelIndex']; //Session::get('resultIndex');
        $this->traceId = $input['traceId'];
        $this->checkInDate = $input['checkInDate'];
        $this->checkOutDate = $input['checkOutDate'];

        $search_contents_htl = json_decode($this->readSearchDataHotel($search_id_hotel.'.json'), true);
        $this->noOfRooms = $search_contents_htl['request']['roomCount'];

        $date = Carbon::createFromDate($this->checkInDate);
        $now = Carbon::createFromDate($this->checkOutDate);

        $noOfNights = $date->diffInDays($now);

        $this->IsPackageFare = $roomDetails['BlockRoomResult']['IsPackageFare'];//Session::get('IsPackageFare');
        $this->IsPackageDetailsMandatory = $roomDetails['BlockRoomResult']['IsPackageDetailsMandatory'];//

        unset($BookRoomDetails['Price']['GST']);
        unset($BookRoomDetails['Price']['ServiceCharge']);
        unset($BookRoomDetails['Price']['TotalGSTAmount']);

        $BookRoomDetails['HotelPassenger'] = $hotelPassengers;
        $hotel_details = StaticDataHotels::where('hotel_code', $this->hotelCode)->first();
        //echo "<pre>";print_r($BookRoomDetails['HotelPassenger']);echo "</pre>";die;
        try {

            /*
             * First check if customer exists in the DB
             */
            $user = User::where('email', $userEmail)->first();

            if (isset($user) && $user->id) {
                $customer_id = $user->customer_id;
                if ($user->customer_id == '') {
                    
                }
                Auth::login($user);
            } else {
                //create new user
                $password = $this->generateRandomString();
                $user = User::create(['name' => $userFirstName . ' ' . $userLastName, 'email' => $userEmail, 'phone' => $userPhone, 'address' => ' ', 'role' => 'user', 'password' => Hash::make($password), 'password_changed' => 1]);
                //send email for create user
                Mail::to($userEmail)->send(new NewUserRegister($user, $password));
                Auth::login($user);
            }

            $blockRequest = json_decode($this->readSearchDataHotel($search_id_hotel . '_block_request.json'), true);


            $this->api = new TBOHotelAPI();
            $bookRoomData = $this
                    ->api
                    ->hotelBookRoom($this->checkInDate, $this->hotelCode, $this->hoteIndex, $this->traceId, $this->hotelName, $HotelRoomsDetails, $this->noOfRooms, $this->IsPackageFare, $this->IsPackageDetailsMandatory, $guestNationality, $input['CategoryId'], $blockRequest['isVoucherBooking']);

            if (isset($bookRoomData['BookResult']) && isset($bookRoomData['BookResult']['ResponseStatus']) && $bookRoomData['BookResult']['ResponseStatus'] == 1) {
                $bookingDetails = $bookRoomData['BookResult'];

                /* check if lottery reach its entry limit */

                /* get active lottery */
                $lottery = Lottery::where(['lotteryStatus' => 'active'])->first();

                /* find all enrolled user count */
                $lotryNos = LotteryUsers::select(['id'])->where(['lotteryID' => $lottery->id])->get()->toArray();

                /* check if entry limit reach the quota to announce winner */
                if ($lottery->entryLimit <= count($lotryNos)) {

                    $tickets = [];
                    foreach ($lotryNos as $lno) {
                        $tickets[] = $lno['id'];
                    }

                    $noOfUsers = count($tickets) - 1;

                    /* if entry full the pick a random ticket */
                    $luckyDraw = rand(0, $noOfUsers);
                    $wonTicketID = $tickets[$luckyDraw];

                    $winner = LotteryUsers::where(['id' => $wonTicketID])->first();

                    /* get the winner details */
                    $lotteryuser = User::where(['id' => $winner->userID])->first();

                    /* credit won amount into winner user wallet */
                    $lotteryuser->deposit($lottery->winAmount, ["description" => 'Lottery winner for Ticket No-' . $wonTicketID]);

                    /* close existing lottery */
                    $closeLottery = Lottery::find($lottery->id);
                    $closeLottery->lotteryStatus = 'draw';
                    $closeLottery->winnerID = $winner->userID;
                    $closeLottery->save();

                    /* open new lottery */
                    $lName = time();

                    Mail::to($lotteryuser['email'])->send(new LotteryWonEmail($lotteryuser, $lottery, $wonTicketID));


                    $new_lottery = Lottery::create([
                                'lotteryName' => "#" . $lName,
                                'winAmount' => 500,
                                'entryLimit' => 100,
                                'entryFees' => 25,
                                'feeCurrency' => 'USD',
                                'lotteryStatus' => 'active'
                    ]);
                }


                $ltry_ID = null;

                if (isset($input['buyLottery']) && $input['buyLottery'] == 'yes') {

                    $lotteryuser = \Auth::user();
                    $lotteryuser->deposit(env('LOTTERY_FEE'), ["description" => 'Lottery Participation Fees on booking id-' . $bookingDetails['BookingId']]);
                    $lottery = Lottery::where(['lotteryStatus' => 'active'])->first();
                    $ltry_ID = LotteryUsers::create(['paidAmount' => env('LOTTERY_FEE'), 'paymentSignature' => $input['razorpay_signature'], 'paymentID' => $input['razorpay_payment_id'], 'paymentStatus' => 'success', 'userID' => Auth::user()->id, 'lotteryID' => $lottery->id])->id;
                }

               // if (isset($input['walletPay']) && $input['walletPay'] == 'yes' && $input['walletDebit'] > 0) {

                    // $myCurrency = Session::get('CurrencyCode');
                    // $usercurrency = Currency::convert($myCurrency, 'USD', $input['walletDebit']);
                    // $debitAmnt = round($usercurrency['convertedAmount']);


                    // $walletuser = \Auth::user();
                    // $walletuser->withdraw(round($debitAmnt), ['BookingID' => $bookingDetails['BookingId']]);
                    
                    // $walletAmount = \Auth::user()->balance;
                    // Session::put('walletAmount', $walletAmount);
                    
                    if (Session::has('paid') || (isset($input['walletPay']) && $input['walletPay'] == 'yes')) {

                        $paid = $input['walletDebit'];
                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');
                        $pAmount = Currency::convert($ccrcy, 'USD', $paid);
                        
                        $walletuser->withdraw(round($pAmount['convertedAmount']), ['description' => "Card payment " . $paid . "(" . $ccrcy . ") received from card for booking ID :- " . $bookingDetails['BookingId']]);
                        Session::forget('paid');
                        
                    }
               // }

                $amount = ($amount / 100);
                if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
                    $amount = round($amount + $agent_makrup, 2);

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $amount);
                    $mAmountC = $mAmount['convertedAmount'];

                } else {
                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $amount);
                    $mAmountC = $mAmount['convertedAmount'];
                }


                $booking = Bookings::create(['booking_id' => $bookingDetails['BookingId'], 'type' => 'hotel', 'trace_id' => $bookingDetails['TraceId'], 'user_id' => Auth::user()->id, 'token_id' => $this
                            ->api->tokenId, 'status' => $bookingDetails['Status'], 'hotel_booking_status' => $bookingDetails['HotelBookingStatus'], 'invoice_number' => (isset($bookingDetails['InvoiceNumber'])) ? $bookingDetails['InvoiceNumber'] : '', 'confirmation_number' => $bookingDetails['ConfirmationNo'], 'booking_ref' => $bookingDetails['BookingRefNo'], 'price_changed' => $bookingDetails['IsPriceChanged'], 'cancellation_policy' => $bookingDetails['IsCancellationPolicyChanged'],
                                'request_data' => json_encode(array(
                                'noOfNights' => $noOfNights,
                                'noofrooms' => $this->noOfRooms,
                                'checkInDate' => $this->checkInDate,
                                'checkOutDate' => $this->checkOutDate,
                                'country' => $guestNationality,
                                'hotelCode' => $this->hotelCode,
                                'hoteIndex' => $this->hoteIndex,
                                'hotelName' => $this->hotelName,
                                'bookingData' => $BookRoomDetails,
                                'base_price' => round($base_price,2),
                                'tax_price' => round($tax_price,2),
                                'amount_tbo' => round($oldAmount,2),
                                'installment_price' => round($ils_install, 2),
                                'ils_fees' => round($ils_conversion, 2),
                                'amount' => round($amount,2),
                                'vat' => round($vat, 2),
                                'isVoucherBooking' => $blockRequest['isVoucherBooking'],
                                'lastCancellationDate' => $blockRequest['lastCancellationDate'],
                                'razorpay_payment_id' => $input['razorpay_payment_id'],
                                'razorpay_signature' => $input['razorpay_signature'],
                ))]);

                //echo "<pre>"; print_r($booking->request_data); die();
                $booking->request_data = json_decode($booking->request_data, true);
                //create entry to payments table
                $payments = Payments::create(['booking_id' => $booking->id, 'user_id' => Auth::user()->id, 'agent_id' => $agent_id, 'commission' => $commission_agent, 'price' => $amount, 'price_convered' => $mAmountC,'agent_markup' => $markup_commission_agent,'partners_commision' => $convertPrtnrAmount,'partners_commision_rest' => $convertPrtnrestAmount, 'customer_id' => Auth::user()->id, 'sub_domain' => '']);

                if ($add_to_subdomain) {

                    $check_hotel_subdomain = DB::connection('mysql2')->select("INSERT INTO `main_bookings` (`booking_id`, `hotel_code`, `currency_code`, `user_id`, `agent_id`, `total_paid`, `comission_earned`, `booking_type`, `created_at`) VALUES ('" . $booking['id'] . "', '" . $this->hotelCode . "', '" . Session::get('currency') . "', '" . Auth::user()->id . "', '" . $agent_id . "', '" . ($mAmountC) . "', '" . $commission_sub_doamin . "', 'hotel', '" . date('Y-m-d h:i:s') . "')");
                }

                $currency = Session::get('currency');

                if (isset($hotel_details)) {
                    $hotel_images = json_decode($hotel_details['hotel_images'], true);
                    if (isset($hotel_images) && $hotel_images != '' && isset($hotel_images[0])) {
                        $hotel_image = $hotel_images[0];
                    } else {
                        $hotel_image = '';
                    }

                    $hotel_address = json_decode($hotel_details['hotel_address'], true);
                    if (isset($hotel_address) && $hotel_address != '') {
                        $hotel_address = (isset($hotel_address['AddressLine']) && isset($hotel_address['AddressLine'][0])) ? $hotel_address['AddressLine'][0] . ' ' . $hotel_address['CityName'] : $hotel_address['CityName'];
                    } else {
                        $hotel_address = '';
                    }

                    $hotel_contact = json_decode($hotel_details['hotel_contact'], true);
                    if (isset($hotel_contact) && $hotel_contact != '') {
                        $hotel_phone = (isset($hotel_contact['ContactNumber']) && isset($hotel_contact['ContactNumber'][0]) && isset($hotel_contact['ContactNumber'][0]['@PhoneNumber'])) ? $hotel_contact['ContactNumber'][0]['@PhoneNumber'] : '';
                        $hotel_fax = (isset($hotel_contact['ContactNumber']) && isset($hotel_contact['ContactNumber'][1]) && isset($hotel_contact['ContactNumber'][1]['@PhoneNumber'])) ? $hotel_contact['ContactNumber'][1]['@PhoneNumber'] : '';
                    } else {
                        $hotel_phone = '';
                        $hotel_fax = '';
                    }

                    $hotel_location = json_decode($hotel_details['hotel_location'], true);
                    if (isset($hotel_location) && $hotel_location != '') {
                        $hotel_location = $hotel_location['@Latitude'] . ',' . $hotel_location['@Longitude'];
                    } else {
                        $hotel_location = '';
                    }

                    $hotel_policy = $hotel_details['hotel_policy'];
                    $hotel_data = array('hotel_image' => $hotel_image, 'hotel_address' => $hotel_address, 'hotel_phone' => $hotel_phone, 'hotel_fax' => $hotel_fax, 'hotel_location' => $hotel_location, 'hotel_policy' => $hotel_policy);
                } else {
                    $hotel_data = array('hotel_image' => '', 'hotel_address' => '', 'hotel_phone' => '', 'hotel_fax' => '', 'hotel_location' => '', 'hotel_policy' => '');
                }

                if(isset($agent_id) && $agent_id != ''){
                   
                    $post_content = 'Booking for <b>' . $this->hotelName . '</b> city <b>'.  $search_contents_htl['request']['city_name'] .'</b> checkin <b>' . date('l, F d Y', strtotime($this->checkInDate)) . '</b> checkout <b>' . date('l, F d Y', strtotime($this->checkOutDate)) . '</b> for ' . $search_contents_htl['request']['roomsGuests'] . '<br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($amount,2) . '</b>';
                    //create story for profile page
                    Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => $hotel_data['hotel_image'],
                                  'user_id' => Auth::user()->id]);


                    $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                                  'type' => 'hotel',
                                  'description' => $post_content,
                                  'price' => 'USD ' . round($commission_agent,2),
                                  'status' => 0
                              ]);
                }

                //generate PDF ticket
                // $pdf = PDF::loadView('emails.pdf.hotel-booking', array('booking' => $booking, 'hotel' => $hotel_data));
                $pdf = PDF::loadView('emails.pdf.hotel-booking', compact('booking', 'hotel_data'));
                $pdf->save(public_path('e-tickets/hotel/e-Ticket-' . $booking->booking_id . '.pdf'));
                $path = public_path('e-tickets/hotel/e-Ticket-' . $booking->booking_id . '.pdf');
                $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                Mail::to($userEmail)->send(new HotelBookingEmail($booking, $hotel_data, $path, $file_name));
                if(isset($agent_id) && $agent_id != ''  && Auth::user()->email != $agentemail){
                    Mail::to($agentemail)->send(new HotelBookingEmail($booking, $hotel_data, $path, $file_name));
                }

                if (isset($input['buyLottery']) && $input['buyLottery'] == 'yes') {
                    Mail::to($userEmail)->send(new LotteryBookingEmail($booking, $hotel_data, $ltry_ID));
                }


                $this->emptySession();

                return array('success' => true, 'booking_id' => $booking->id);

                //return redirect('/thankyou/hotel/' . $booking->id . '/true');
            } else {

                $this->writeLogs(array('checkInDate' => $this->checkInDate, 'hotelCode' => $this->hotelCode, 'hoteIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'HotelRoomsDetails' => $HotelRoomsDetails), $bookRoomData['BookResult']);
                $message = $bookRoomData['BookResult']['Error']['ErrorMessage'];

                return array('success' => false, 'message' => $message);

            }
        } catch (\Stripe\Error\RateLimit $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\InvalidRequest $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\Authentication $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\ApiConnection $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\Base $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return array('success' => false, 'message' => $message);
    }

    /* Flight Booking */

    public function bookFlight(Request $request) {

        $input = $request->all();

        $search_contents = json_decode($this->readSearchData($input['search_id'].'.json'), true);

        //$flightSearch = Session::get('flightSearhData');

        $flightSearch = $search_contents['request']['input'];

        $flightSearch['travellersClass'] = '';
        if ($input['referral'] != '') {

            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();
            if (isset($checkrefferal)) {

                //$commisioninis = $checkrefferal['commission'];
                $agent_id = $checkrefferal['user_id'];
                $agentemail = User::select('email')->where(['id' => $agent_id])->first();
                $agentemail = $agentemail['email'];
            } else {
                $agent_id = '';
                $agentemail = '';
                // $commisioninis = env('INIS_VAL_FLIGHT');
            }
        } else {

            $agent_id = '';
            $agentemail = '';
        }

        $fareQuoteOB = json_decode($input['farebreakDownOB'], true);

        $adultCount = $input['adultCountHidden'];
        $ChildCount = $input['childCountHidden'];
        $InfantCount =  "0";

        $roomGuest = $input['roomPassengers'];

        $departDate = $flightSearch['departdate'];

        $flightPassengerArr = array();
        $bookFlightArr = array();

        $travelArr = array();

        $travelArr['from_loc'] = $input['from_loc'];
        $travelArr['to_loc'] = $input['to_loc'];
        $travelArr['duration'] = $input['duration'];
        $travelArr['dep_time'] = $input['dep_time'];
        $travelArr['arr_time'] = $input['arr_time'];
        $travelArr['flight_name_ob'] = $input['flight_name_ob'];
        $travelArr['flight_jetname_ob'] = $input['flight_jetname_ob'];
        $travelArr['base_price'] = $input['base_price'];
        $travelArr['tax_price'] = $input['tax_price'];
        $travelArr['amount'] = $input['amount'];
        $travelArr['amount_without_conversion'] = $input['amount_without_conversion'];
        $travelArr['amount_tbo'] = $input['amount_tbo'];
        $travelArr['departure_date_arr'] = $input['departure_date_arr'];

        $travelArr['city_code_arrival'] = $flightSearch['origin'];
        $travelArr['city_code_departure'] = $flightSearch['destination'];


        $travelArr['main_start'] = $flightSearch['from'];
        $travelArr['to_start'] = $flightSearch['to'];
        $travelArr['extra_baggage_meal_price'] = $input['extra_baggage_meal_price'];

        if (isset($input['stop_from_loc']) && $input['stop_from_loc'] != '') {

            $travelArr['stop_from_loc'] = $input['stop_from_loc'];
            $travelArr['stop_to_loc'] = $input['stop_to_loc'];
            $travelArr['stop_duration'] = $input['stop_duration'];
            $travelArr['stop_dep_time'] = $input['stop_dep_time'];
            $travelArr['stop_arr_time'] = $input['stop_arr_time'];
            $travelArr['stop_flight_name_ob'] = $input['stop_flight_name_ob'];
            $travelArr['stop_flight_jetname_ob'] = $input['stop_flight_jetname_ob'];
        }

        if (isset($input['stop2_from_loc']) && $input['stop2_from_loc'] != '') {

            $travelArr['stop2_from_loc'] = $input['stop2_from_loc'];
            $travelArr['stop2_to_loc'] = $input['stop2_to_loc'];
            $travelArr['stop2_duration'] = $input['stop2_duration'];
            $travelArr['stop2_dep_time'] = $input['stop2_dep_time'];
            $travelArr['stop2_arr_time'] = $input['stop2_arr_time'];
            $travelArr['stop2_flight_name_ob'] = $input['stop2_flight_name_ob'];
            $travelArr['stop2_flight_jetname_ob'] = $input['stop2_flight_jetname_ob'];
        }

        if (isset($input['return_int_from_loc']) && $input['return_int_from_loc'] != '') {

            $travelArr['return_int_from_loc'] = $input['return_int_from_loc'];
            $travelArr['return_int_to_loc'] = $input['return_int_to_loc'];
            $travelArr['return_int_duration'] = $input['return_int_duration'];
            $travelArr['return_int_dep_time'] = $input['return_int_dep_time'];
            $travelArr['return_int_arr_time'] = $input['return_int_arr_time'];
            $travelArr['return_int_flight_name_ob'] = $input['return_int_flight_name_ob'];
            $travelArr['return_int_flight_jetname_ob'] = $input['return_int_flight_jetname_ob'];
        }

        if (isset($input['stop_return_int_from_loc']) && $input['stop_return_int_from_loc'] != '') {

            $travelArr['stop_return_int_from_loc'] = $input['stop_return_int_from_loc'];
            $travelArr['stop_return_int_to_loc'] = $input['stop_return_int_to_loc'];
            $travelArr['stop_return_int_duration'] = $input['stop_return_int_duration'];
            $travelArr['stop_return_int_dep_time'] = $input['stop_return_int_dep_time'];
            $travelArr['stop_return_int_arr_time'] = $input['stop_return_int_arr_time'];
            $travelArr['stop_return_int_flight_name_ob'] = $input['stop_return_int_flight_name_ob'];
            $travelArr['stop_return_int_flight_jetname_ob'] = $input['stop_return_int_flight_jetname_ob'];
        }

        if (isset($input['stop2_return_int_from_loc']) && $input['stop2_return_int_from_loc'] != '') {

            $travelArr['stop2_return_int_from_loc'] = $input['stop2_return_int_from_loc'];
            $travelArr['stop2_return_int_to_loc'] = $input['stop2_return_int_to_loc'];
            $travelArr['stop2_return_int_duration'] = $input['stop2_return_int_duration'];
            $travelArr['stop2_return_int_dep_time'] = $input['stop2_return_int_dep_time'];
            $travelArr['stop2_return_int_arr_time'] = $input['stop2_return_int_arr_time'];
            $travelArr['stop2_return_int_flight_name_ob'] = $input['stop2_return_int_flight_name_ob'];
            $travelArr['stop2_return_int_flight_jetname_ob'] = $input['stop2_return_int_flight_jetname_ob'];
        }

        if (isset($input['return_ib_from_loc']) && $input['return_ib_from_loc'] != '') {

            $travelArr['return_ib_from_loc'] = $input['return_ib_from_loc'];
            $travelArr['return_ib_to_loc'] = $input['return_ib_to_loc'];
            $travelArr['return_ib_duration'] = $input['return_ib_duration'];
            $travelArr['return_ib_dep_time'] = $input['return_ib_dep_time'];
            $travelArr['return_ib_arr_time'] = $input['return_ib_arr_time'];
            $travelArr['flight_name_ib'] = $input['flight_name_ib'];
            $travelArr['flight_jetname_ib'] = $input['flight_jetname_ib'];
            $travelArr['departure_date_dep'] = $input['departure_date_dep'];
        }

        if (isset($input['stop_return_ib_from_loc']) && $input['stop_return_ib_from_loc'] != '') {

            $travelArr['stop_return_ib_from_loc'] = $input['stop_return_ib_from_loc'];
            $travelArr['stop_return_ib_to_loc'] = $input['stop_return_ib_to_loc'];
            $travelArr['stop_return_ib_duration'] = $input['stop_return_ib_duration'];
            $travelArr['stop_return_ib_dep_time'] = $input['stop_return_ib_dep_time'];
            $travelArr['stop_return_ib_arr_time'] = $input['stop_return_ib_arr_time'];
            $travelArr['stop_flight_name_ib'] = $input['stop_flight_name_ib'];
            $travelArr['stop_flight_jetname_ib'] = $input['stop_flight_jetname_ib'];
        }

        if (isset($input['stop2_return_ib_from_loc']) && $input['stop2_return_ib_from_loc'] != '') {

            $travelArr['stop2_return_ib_from_loc'] = $input['stop2_return_ib_from_loc'];
            $travelArr['stop2_return_ib_to_loc'] = $input['stop2_return_ib_to_loc'];
            $travelArr['stop2_return_ib_duration'] = $input['stop2_return_ib_duration'];
            $travelArr['stop2_return_ib_dep_time'] = $input['stop2_return_ib_dep_time'];
            $travelArr['stop2_return_ib_arr_time'] = $input['stop2_return_ib_arr_time'];
            $travelArr['stop2_flight_name_ib'] = $input['stop2_flight_name_ib'];
            $travelArr['stop2_flight_jetname_ib'] = $input['stop2_flight_jetname_ib'];
        }

        $commission = $input['amount_without_conversion'] - $input['amount_tbo'];
        $commission_agent = $input['amount_without_conversion_agent'] - $input['amount_tbo'];

        if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {

            $agent_makrup = $input['agent_makrup'] / 2 ;
        }



        if ($input['referral'] && $input['referral'] != '' && $input['referral'] != '0') {

            $commission_agent = $commission_agent;

        } else {
            
            $commission_agent = $commission;
        }

        $markup_commission_agent = 0;
        if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
            //$commission_agent = $commission_agent + ($agent_makrup * 0.60);

            $agent_markup_commision = $agent_makrup * 0.60;
            $markUplAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $agent_markup_commision);
            $markup_commission_agent = $markUplAmount['convertedAmount'];

            $partners_markup = env('PARTNER_COMMISION_MARKUP');

            $partners_markup_rest = env('PARTNER_COMMISION_REST_MARKUP');

            $markupPartner = round($agent_makrup  * 0.40);

            $markupPartnerCommission = ( ( $partners_markup / 100 ) * $markupPartner);

            $markupPartnerRestCommission = ( ( $partners_markup_rest / 100 ) * $markupPartner);

            $convertPrtnr = Currency::convert(Session::get('CurrencyCode'), 'USD', $markupPartnerCommission );
            $convertPrtnrAmount = $convertPrtnr['convertedAmount'];

            $convertPrtnrest = Currency::convert(Session::get('CurrencyCode'), 'USD', $markupPartnerRestCommission );
            $convertPrtnrestAmount = $convertPrtnrest['convertedAmount'];

        }else{
            $convertPrtnrAmount = 0;
            $convertPrtnrestAmount = 0;
        }


        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {

            $location = \Location::get($this->end_user_ip);
        }
        // //get user currency
        $countryName = $location->countryName;

        $currencyCode = Currencies::where('code', $location->countryCode)->first();
        //Session::put('currency', $currencyCode['currency_code']);


        $lAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $commission_agent);
        $commission_agent = $lAmount['convertedAmount'];

        /* Adult Array */

        $counterchild = $adultCount;
        $counterinfant = $adultCount + $ChildCount;
        //echo "<pre>";print_r($roomGuest);die;


        foreach ($roomGuest as $room => $guest) {
            // echo $room;
            // print_r($guest);
            $NoOfAdults = sizeof($guest['adult']);
            if (isset($guest['child'])) {

                //$NoOfChild = $guest['child'];
                $NoOfChild = sizeof($guest['child']);
            }
            $hotelPassengers[$room] = array();

            for ($a = 1; $a <= $NoOfAdults; $a++) {
                // echo $a . '\n';
                if ($room == 1 && $a == 1) {
                    $userFirstName = $guest['adult'][$a]['first_name'];
                    $userLastName = $guest['adult'][$a]['last_name'];
                    $userEmail = $guest['adult'][$a]['email'];
                    $userPhone = $guest['adult'][$a]['phone'];
                }

                if(isset($guest['adult'][$a]['passportNo'])){

                    array_push($flightPassengerArr, array(
                        "Title" => $guest['adult'][$a]['title'],
                        "FirstName" => $guest['adult'][$a]['first_name'],
                        "LastName" => $guest['adult'][$a]['last_name'],
                        "PaxType" => 1,
                        "DateOfBirth" => isset($guest['adult'][$a]['adult_dob']) ? $guest['adult'][$a]['adult_dob'] : "1985-01-30T00:00:00",
                        "Gender" => ($guest['adult'][$a]['title'] == 'Mr') ? 1 : 2,
                        "AddressLine1" => $guest['adult'][$a]['address'],
                        "AddressLine2" => "",
                        "City" => $guest['adult'][$a]['address'],
                        "CountryCode" => $location->countryCode,
                        "CountryName" => $location->countryName,
                        "PassportNo" => isset($guest['adult'][$a]['passportNo']) ? $guest['adult'][$a]['passportNo'] : "",
                        "PassportExpiry" => isset($guest['adult'][$a]['passportExpire']) ? $guest['adult'][$a]['passportExpire'] : "",
                        "ContactNo" => $guest['adult'][$a]['phone'],
                        "Email" => $guest['adult'][$a]['email'],
                        "IsLeadPax" =>  ($a == 1) ? true : false,
                        "FFAirline" => "",
                        "FFNumber" => "",
                        "Nationality" => "",
                        "Fare" => array(
                            "BaseFare" => $fareQuoteOB[0]['BaseFare'] / $fareQuoteOB[0]['PassengerCount'],
                            "Tax" => $fareQuoteOB[0]['Tax'] / $fareQuoteOB[0]['PassengerCount'],
                            "YQTax" => $fareQuoteOB[0]['YQTax'] / $fareQuoteOB[0]['PassengerCount'],
                            "AdditionalTxnFeeOfrd" => $fareQuoteOB[0]['AdditionalTxnFeeOfrd'] / $fareQuoteOB[0]['PassengerCount'],
                            "AdditionalTxnFeePub" => $fareQuoteOB[0]['AdditionalTxnFeePub'] / $fareQuoteOB[0]['PassengerCount'],
                            "PGCharge" => $fareQuoteOB[0]['PGCharge'] / $fareQuoteOB[0]['PassengerCount']
                        )
                    ));
                }else{

                    array_push($flightPassengerArr, array(
                        "Title" => $guest['adult'][$a]['title'],
                        "FirstName" => $guest['adult'][$a]['first_name'],
                        "LastName" => $guest['adult'][$a]['last_name'],
                        "PaxType" => 1,
                        "DateOfBirth" => isset($guest['adult'][$a]['adult_dob']) ? $guest['adult'][$a]['adult_dob'] : "1985-01-30T00:00:00",
                        "Gender" => ($guest['adult'][$a]['title'] == 'Mr') ? 1 : 2,
                        "AddressLine1" => $guest['adult'][$a]['address'],
                        "AddressLine2" => "",
                        "City" => $guest['adult'][$a]['address'],
                        "CountryCode" => $location->countryCode,
                        "CountryName" => $location->countryName,
                        "ContactNo" => $guest['adult'][$a]['phone'],
                        "PassportNo" => "KJHHJKHKJH",
                        "PassportExpiry"=> "2025-08-30",
                        "Email" => $guest['adult'][$a]['email'],
                        "IsLeadPax" =>  ($a == 1) ? true : false,
                        "FFAirline" => "",
                        "FFNumber" => "",
                        "Nationality" => "",
                        "Fare" => array(
                            "BaseFare" => $fareQuoteOB[0]['BaseFare'] / $fareQuoteOB[0]['PassengerCount'],
                            "Tax" => $fareQuoteOB[0]['Tax'] / $fareQuoteOB[0]['PassengerCount'],
                            "YQTax" => $fareQuoteOB[0]['YQTax'] / $fareQuoteOB[0]['PassengerCount'],
                            "AdditionalTxnFeeOfrd" => $fareQuoteOB[0]['AdditionalTxnFeeOfrd'] / $fareQuoteOB[0]['PassengerCount'],
                            "AdditionalTxnFeePub" => $fareQuoteOB[0]['AdditionalTxnFeePub'] / $fareQuoteOB[0]['PassengerCount'],
                            "PGCharge" => $fareQuoteOB[0]['PGCharge'] / $fareQuoteOB[0]['PassengerCount']
                        )
                    ));
                }

                if(isset($input['meal_' . $a]) && $input['meal_' . $a] != ''){
                    $input['meal_' . $a] = json_decode($input['meal_' . $a], true);
                    $flightPassengerArr[$a-1]['Meal'] = $input['meal_' . $a];
                }

                if(isset($input['seat_' . $a]) && $input['seat_' . $a] != ''){
                    $input['seat_' . $a] = json_decode($input['seat_' . $a], true);
                    $flightPassengerArr[$a-1]['Seat'] = $input['seat_' . $a];
                }

                if(isset($input['baggage_' . $a]) && $input['baggage_' . $a] != ''){
                    $input['baggage_' . $a] = json_decode($input['baggage_' . $a], true);
                    $flightPassengerArr[$a-1]['Baggage'] = array();
                    array_push($flightPassengerArr[$a-1]['Baggage'], $input['baggage_' . $a]);
                }

                if(isset($input['meallcc_' . $a]) && $input['meallcc_' . $a] != ''){
                    $input['meallcc_' . $a] = json_decode($input['meallcc_' . $a], true);
                    $flightPassengerArr[$a-1]['MealDynamic'] = array();
                    array_push($flightPassengerArr[$a-1]['MealDynamic'], $input['meallcc_' . $a]);
                }

                if(isset($input['baggage_return' . $a]) && $input['baggage_return' . $a] != ''){
                    $input['baggage_return' . $a] = json_decode($input['baggage_return' . $a], true);
                    //$flightPassengerArr[$a-1]['Baggage'] = array();
                    if(!isset($input['baggage_' . $a]) && $input['baggage_' . $a] == ''){
                     $flightPassengerArr[$a-1]['Baggage'] = array();
                    }
                    array_push($flightPassengerArr[$a-1]['Baggage'], $input['baggage_return' . $a]);
                }

                if(isset($input['meallcc_return' . $a]) && $input['meallcc_return' . $a] != ''){
                    $input['meallcc_return' . $a] = json_decode($input['meallcc_return' . $a], true);
                    
                    if(!isset($input['meallcc_' . $a]) && $input['meallcc_' . $a] == ''){
                     $flightPassengerArr[$a-1]['MealDynamic'] = array();
                    }
                    //echo "<pre>";print_r($input['meallcc_return' . $a]);die;
                    array_push($flightPassengerArr[$a-1]['MealDynamic'], $input['meallcc_return' . $a]);
                }

                //echo "<pre>";print_r($flightPassengerArr);die;

            }
            if (isset($NoOfChild)) {

                for ($c = 1; $c <= $NoOfChild; $c++) {

                    if(isset($guest['child'][$c]['child_passport_no'])){
                        array_push($flightPassengerArr, array(
                            "Title" => isset($guest['child'][$c]['title']) ? 'Mr':'Miss',
                            "FirstName" => $guest['child'][$c]['first_name'],
                            "LastName" => $guest['child'][$c]['last_name'],
                            "PaxType" => 2,
                            "DateOfBirth" => date('Y-m-d', strtotime($guest['child'][$c]['child_dob'])) . "T00:00:00",
                            "Gender" => ($guest['child'][$c]['title']) ? 1 : 2,
                            "AddressLine1" => $guest['child'][$c]['address'],
                            "AddressLine2" => "",
                            "City" => $guest['child'][$c]['address'],
                            "CountryCode" => $location->countryCode,
                            "CountryName" => $location->countryName,
                            "PassportNo" => isset($guest['child'][$c]['child_passport_no']) ? $guest['child'][$c]['child_passport_no'] : "",
                            "PassportExpiry" => isset($guest['child'][$c]['child_pass_expiry_date']) ? $guest['child'][$c]['child_pass_expiry_date']: "",
                            "ContactNo" => $guest['child'][$c]['child_phone'],
                            "Email" => $guest['child'][$c]['child_email'],
                            "IsLeadPax" => false,
                            "FFAirline" => "",
                            "FFNumber" => "",
                            "Nationality" => "",
                            "Fare" => array(
                                "BaseFare" => $fareQuoteOB[1]['BaseFare'] / $fareQuoteOB[1]['PassengerCount'],
                                "Tax" => $fareQuoteOB[1]['Tax'] / $fareQuoteOB[1]['PassengerCount'],
                                "YQTax" => $fareQuoteOB[1]['YQTax'] / $fareQuoteOB[1]['PassengerCount'],
                                "AdditionalTxnFeeOfrd" => $fareQuoteOB[1]['AdditionalTxnFeeOfrd'] / $fareQuoteOB[1]['PassengerCount'],
                                "AdditionalTxnFeePub" => $fareQuoteOB[1]['AdditionalTxnFeePub'] / $fareQuoteOB[1]['PassengerCount'],
                                "PGCharge" => $fareQuoteOB[1]['PGCharge'] / $fareQuoteOB[1]['PassengerCount']
                            )
                        ));
                    }else{
                      array_push($flightPassengerArr, array(
                            "Title" => $guest['child'][$c]['title'],
                            "FirstName" => $guest['child'][$c]['first_name'],
                            "LastName" => $guest['child'][$c]['last_name'],
                            "PaxType" => 2,
                            "DateOfBirth" => date('Y-m-d', strtotime($guest['child'][$c]['child_dob'])) . "T00:00:00",
                            "Gender" => ($guest['child'][$c]['title']) ? 1 : 2,
                            "AddressLine1" => $guest['child'][$c]['address'],
                            "AddressLine2" => "",
                            "City" => $guest['child'][$c]['address'],
                            "CountryCode" => $location->countryCode,
                            "CountryName" => $location->countryName,
                            "ContactNo" => $guest['child'][$c]['child_phone'],
                            "Email" => $guest['child'][$c]['child_email'],
                            "PassportNo" => "KJHHJKHKJH",
                            "PassportExpiry"=> "2025-08-30",
                            "IsLeadPax" => false,
                            "FFAirline" => "",
                            "FFNumber" => "",
                            "Nationality" => "",
                            "Fare" => array(
                                "BaseFare" => $fareQuoteOB[1]['BaseFare'] / $fareQuoteOB[1]['PassengerCount'],
                                "Tax" => $fareQuoteOB[1]['Tax'] / $fareQuoteOB[1]['PassengerCount'],
                                "YQTax" => $fareQuoteOB[1]['YQTax'] / $fareQuoteOB[1]['PassengerCount'],
                                "AdditionalTxnFeeOfrd" => $fareQuoteOB[1]['AdditionalTxnFeeOfrd'] / $fareQuoteOB[1]['PassengerCount'],
                                "AdditionalTxnFeePub" => $fareQuoteOB[1]['AdditionalTxnFeePub'] / $fareQuoteOB[1]['PassengerCount'],
                                "PGCharge" => $fareQuoteOB[1]['PGCharge'] / $fareQuoteOB[1]['PassengerCount']
                            )
                        ));  
                    }

                    if(isset($input['child_meal_' . $c]) && $input['child_meal_' . $c] != ''){
                        $input['child_meal_' . $c] = json_decode($input['child_meal_' . $c], true);
                        $flightPassengerArr[$counterchild]['Meal'] = $input['child_meal_' . $c];
                    }

                    if(isset($input['child_seat_' . $c]) && $input['child_seat_' . $c] != ''){
                        $input['child_seat_' . $c] = json_decode($input['child_seat_' . $c], true);
                        $flightPassengerArr[$counterchild]['Seat'] = $input['child_seat_' . $c];
                    }

                    if(isset($input['child_baggage_' . $c]) && $input['child_baggage_' . $c] != ''){
                        $input['child_baggage_' . $c] = json_decode($input['child_baggage_' . $c], true);
                        $flightPassengerArr[$counterchild]['Baggage'] = array();
                        array_push($flightPassengerArr[$counterchild]['Baggage'], $input['child_baggage_' . $c]);
                    }

                    if(isset($input['child_meallcc_' . $c]) && $input['child_meallcc_' . $c] != ''){
                        $input['child_meallcc_' . $c] = json_decode($input['child_meallcc_' . $c], true);
                        $flightPassengerArr[$counterchild]['MealDynamic'] = array();
                        array_push($flightPassengerArr[$counterchild]['MealDynamic'], $input['child_meallcc_' . $c]);
                    }

                    if(isset($input['child_baggage_return' . $c]) && $input['child_baggage_return' . $c] != ''){
                        $input['child_baggage_return' . $c] = json_decode($input['child_baggage_return' . $c], true);
                        
                        if(!isset($input['child_baggage_' . $c]) && $input['child_baggage_' . $c] == ''){
                         $flightPassengerArr[$counterchild]['Baggage'] = array();
                        }

                        array_push($flightPassengerArr[$counterchild]['Baggage'], $input['child_baggage_return' . $c]);
                    }

                    if(isset($input['child_meallcc_return' . $c]) && $input['child_meallcc_return' . $c] != ''){
                        $input['child_meallcc_return' . $c] = json_decode($input['child_meallcc_return' . $c], true);

                        if(!isset($input['child_meallcc_' . $c]) && $input['child_meallcc_' . $c] == ''){
                         $flightPassengerArr[$counterchild]['MealDynamic'] = array();
                        }

                        array_push($flightPassengerArr[$counterchild]['MealDynamic'], $input['child_meallcc_return' . $c]);
                    }

                    $counterchild++;
                }
            }

            // $roomCount++;
        }


        /* End Object */

        $this->flightapi = new TBOFlightAPI();


        $currency = Session::get('currency');
        $bookFlightArr = array('PreferredCurrency' => $currency,
            "IsBaseCurrencyRequired" => "true",
            "EndUserIp" => $this->flightapi->userIP,
            "TokenId" => $this->flightapi->tokenId,
            "TraceId" => $input['trace_id'],
            "ResultIndex" => $input['obindex'],
            "Passengers" => $flightPassengerArr
        );

        //echo "<pre>";print_r($bookFlightArr);echo "</pre>"; die;

        /* Booking For OB */

        try {

            /*
             * First check if customer exists in the DB
             */
            //$user = User::where('email', $userEmail)->first();

            if(Auth::user()) {
                $user = Auth::user();
            } else {
                $user = User::where('email', $userEmail)->first();
            }


            if (isset($user) && $user->id) {
                Auth::login($user);
            } else {
                //create new user
                $password = $this->generateRandomString();

                $user = User::create([
                            'name' => $userFirstName . ' ' . $userLastName,
                            'email' => $userEmail,
                            'phone' => $userPhone,
                            'address' => 'Bhopal',
                            'role' => 'user',
                            'password' => Hash::make($password),
                            'password_changed' => 1
                ]);
                //send email for create user
                Mail::to($userEmail)->send(new NewUserRegister($user, $password));
                Auth::login($user);
            }


            /* Getting Ticket For OB */

            if ($input['is_ob_lcc'] && $input['is_ob_lcc'] != '') {

                $this->bookingResult = $this->flightapi->ticket($bookFlightArr);

                if (isset($this->bookingResult['Response']) && isset($this->bookingResult['Response']['ResponseStatus']) && $this->bookingResult['Response']['ResponseStatus'] == 1) {

                    $bookingDetails = $this->bookingResult['Response'];
                    if ($bookingDetails['Response']['FlightItinerary']['IsLCC']) {
                        $bookingDetails['Response']['FlightItinerary']['IsLCC'] = 1;
                    } else {
                        $bookingDetails['Response']['FlightItinerary']['IsLCC'] = 0;
                    }

                    $travelArr['ticket_id'] = array();

                    foreach ($bookingDetails['Response']['FlightItinerary']['Passenger'] as $key => $value) {
                        # code...
                        array_push($travelArr['ticket_id'], $value['Ticket']['TicketId']);
                    }

                    if (isset($input['walletPay']) && $input['walletPay'] == 'yes' && $input['walletDebit'] > 0) {

                        $myCurrency = Session::get('CurrencyCode');
                        $usercurrency = Currency::convert($myCurrency, 'USD', $input['walletDebit']);
                        $debitAmnt = round($usercurrency['convertedAmount']);


                        $walletuser = \Auth::user();
                        $walletuser->withdraw($debitAmnt, ['BookingID' => $bookingDetails['Response']['BookingId']]);
                        
                        $walletAmount = \Auth::user()->balance;
                        Session::put('walletAmount', $walletAmount);
                    }


                    $booking = FlightBookings::create(['booking_id' => $bookingDetails['Response']['BookingId'],
                                'trace_id' => $bookingDetails['TraceId'],
                                'user_id' => Auth::user()->id,
                                'token_id' => $this->flightapi->tokenId,
                                'is_lcc' => $bookingDetails['Response']['FlightItinerary']['IsLCC'],
                                'flight_booking_status' => $bookingDetails['Response']['TicketStatus'],
                                'invoice_number' => $bookingDetails['Response']['FlightItinerary']['InvoiceNo'],
                                'pnr' => $bookingDetails['Response']['PNR'],
                                'booking_ref' => '',
                                'price_changed' => $bookingDetails['Response']['IsPriceChanged'],
                                'cancellation_policy' => $bookingDetails['Response']['FlightItinerary']['CancellationCharges'],
                                'request_data' => json_encode(array('travelData' => $travelArr, 'bookingData' => $bookFlightArr, 'razorpay_payment_id' => $input['razorpay_payment_id'],
                                    'razorpay_signature' => $input['razorpay_signature']))
                    ]);

                    $booking->request_data = array('travelData' => $travelArr, 'bookingData' => $bookFlightArr); //json_decode($booking->request_data, true);

                    $input['amount'] = $input['amount'] + $input['extra_baggage_meal_price'];

                    if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
                         $input['amount'] = round( $input['amount'] + $agent_makrup, 2);
                    }

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $input['amount']);
                    $mAmountC = $mAmount['convertedAmount'];

                    //create entry to FlightPayments table
                    $payments = FlightPayments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'agent_id' => $agent_id,
                                'commission' => $commission_agent,
                                'price' => $input['amount'],
                                'price_convered' => $mAmountC,
                                'agent_markup' => $markup_commission_agent,
                                'partners_commision' => $convertPrtnrAmount,
                                'partners_commision_rest' => $convertPrtnrestAmount,
                                'customer_id' => '',
                                'sub_domain' => '']);



                    $segments = $bookingDetails['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetails['Response']['FlightItinerary']['FareRules'];

                    if(isset($agent_id) && $agent_id != ''){ 

                        if($NoOfAdults == 1){
                            $adultText = 'Adult';
                        }else{
                            $adultText = 'Adults';
                        }

                        if(isset($NoOfChild) && $NoOfChild > 0 ){


                            if($NoOfChild == 1){
                                $childText = 'Child';
                            }else{
                                $childText = 'Childs';
                            }

                            $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' and '.$NoOfChild.' '.$childText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        }else{

                             $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';    
                        }  
                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => $post_content,
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }

                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){
                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    }

                    if (isset($input['ibindex']) && $input['ibindex'] != '') {
                        
                    } else {

                        $this->emptyFlightSession();
                        //send to thank you page
                        $hotelId = $this->bookFlightRoom($input);

                        if(isset($hotelId['success']) && $hotelId['success']){

                            return redirect('/thankyou/flight-hotel/' . $hotelId['booking_id'] . '/'.$booking->id);
                        }else{
                            return view('500')->with(['error' => $hotelId['message']]);  
                        }


                    }
                } else {
                    $message = $this->bookingResult['Response']['Error']['ErrorMessage'];
                    //echo "<pre>";print_r($message);echo"</pre>";
                    return view('500')->with(['error' => $message]);
                }
            } else {

                $this->bookingResultNOLCC = $this->flightapi->book($bookFlightArr);

                $bookingDetailsNOLCC = $this->bookingResultNOLCC['Response'];

                //echo "<pre>";print_r($bookingDetailsNOLCC);echo "</pre>";

                if ($bookingDetailsNOLCC['ResponseStatus'] != 1) {
                    $this->emptyFlightSession();
                    // return view('error')->with(['error' => $bookingDetailsNOLCC['Error']['ErrorMessage']]);
                    return view('500')->with(['error' => $bookingDetailsNOLCC['Error']['ErrorMessage']]);
                }

                $this->TicketResult = $this->flightapi->getNoLCCTicket($input['trace_id'], $bookingDetailsNOLCC['Response']['BookingId'], $bookingDetailsNOLCC['Response']['PNR']);

                //$ticketDetailsNOLCC = $this->TicketResult['Response'];

                if (isset($this->TicketResult['Response']) && isset($this->TicketResult['Response']['ResponseStatus']) && $this->TicketResult['Response']['ResponseStatus'] == 1) {

                    $ticketDetailsNOLCC = $this->TicketResult['Response'];

                    if ($ticketDetailsNOLCC['Response']['FlightItinerary']['IsLCC']) {
                        $ticketDetailsNOLCC['Response']['FlightItinerary']['IsLCC'] = 1;
                    } else {
                        $ticketDetailsNOLCC['Response']['FlightItinerary']['IsLCC'] = 0;
                    }

                    $travelArr['ticket_id'] = array();

                    foreach ($ticketDetailsNOLCC['Response']['FlightItinerary']['Passenger'] as $key => $value) {
                        # code...
                        array_push($travelArr['ticket_id'], $value['Ticket']['TicketId']);
                    }

                    if (isset($input['walletPay']) && $input['walletPay'] == 'yes' && $input['walletDebit'] > 0) {

                        $myCurrency = Session::get('CurrencyCode');
                        $usercurrency = Currency::convert($myCurrency, 'USD', $input['walletDebit']);
                        $debitAmnt = round($usercurrency['convertedAmount']);


                        $walletuser = \Auth::user();
                        $walletuser->withdraw($debitAmnt, ['BookingID' => $ticketDetailsNOLCC['Response']['BookingId']]);
                    }

                    $booking = FlightBookings::create(['booking_id' => $ticketDetailsNOLCC['Response']['BookingId'],
                                'trace_id' => $ticketDetailsNOLCC['TraceId'],
                                'user_id' => Auth::user()->id,
                                'token_id' => $this->flightapi->tokenId,
                                'is_lcc' => $ticketDetailsNOLCC['Response']['FlightItinerary']['IsLCC'],
                                'flight_booking_status' => $ticketDetailsNOLCC['Response']['TicketStatus'],
                                'invoice_number' => $ticketDetailsNOLCC['Response']['FlightItinerary']['InvoiceNo'],
                                'pnr' => $ticketDetailsNOLCC['Response']['PNR'],
                                'booking_ref' => '',
                                'price_changed' => $ticketDetailsNOLCC['Response']['IsPriceChanged'],
                                'cancellation_policy' => $ticketDetailsNOLCC['Response']['FlightItinerary']['CancellationCharges'],
                                'request_data' => json_encode(array('travelData' => $travelArr, 'bookingData' => $bookFlightArr, 'razorpay_payment_id' => $input['razorpay_payment_id'],
                                    'razorpay_signature' => $input['razorpay_signature']))
                    ]);

                    $booking->request_data = array('travelData' => $travelArr, 'bookingData' => $bookFlightArr);

                    //create entry to FlightPayments table
                    $input['amount'] = $input['amount'] + $input['extra_baggage_meal_price'];

                    if(isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
                         $input['amount'] = round( $input['amount'] + $agent_makrup, 2);
                    }
                    

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $input['amount']);
                    $mAmountC = $mAmount['convertedAmount'];

                    $payments = FlightPayments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'agent_id' => $agent_id,
                                'commission' => $commission_agent,
                                'price' => $input['amount'],
                                'price_convered' => $mAmountC,
                                'agent_markup' => $markup_commission_agent,
                                'partners_commision' => $convertPrtnrAmount,
                                'partners_commision_rest' => $convertPrtnrestAmount,
                                'customer_id' => '',
                                'sub_domain' => '']);


                    $segments = $bookingDetailsNOLCC['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetailsNOLCC['Response']['FlightItinerary']['FareRules'];

                    if(isset($agent_id) && $agent_id != ''){   

                        if($NoOfAdults == 1){
                            $adultText = 'Adult';
                        }else{
                            $adultText = 'Adults';
                        }

                        if(isset($NoOfChild) && $NoOfChild > 0 ){


                            if($NoOfChild == 1){
                                $childText = 'Child';
                            }else{
                                $childText = 'Childs';
                            }

                            $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' and '.$NoOfChild.' '.$childText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        }else{

                             $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';    
                        } 
                        
                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => $post_content,
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }

                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){
                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    }
 

                    if (isset($input['ibindex']) && $input['ibindex'] != '') {
                        
                    } else {

                        $this->emptyFlightSession();
                        //send to thank you page
                        $hotelId = $this->bookFlightRoom($input);

                       if(isset($hotelId['success']) && $hotelId['success']){

                            return redirect('/thankyou/flight-hotel/' . $hotelId['booking_id'] . '/'.$booking->id);
                        }else{
                            return view('500')->with(['error' => $hotelId['message']]);  
                        }
                    }
                } else {
                    $message = $this->TicketResult['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    return view('500')->with(['error' => $message]);
                }
            }
        } catch (\Stripe\Error\RateLimit $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\InvalidRequest $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\Authentication $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\ApiConnection $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (\Stripe\Error\Base $e) {
            $success = false;
            $message = $e->getMessage();
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        /* Booking Done */


        /* Getting Ticket For OB */
        //$this->bookingResult = $this->flightapi->ticket($bookFlightArr);

        /* Getting Ticket For IB */

        if (isset($input['ibindex']) && $input['ibindex'] != '') {

            $flightPassengerArrIB = array();
            $fareQuoteIB = json_decode($input['farebreakDownIB'], true);

            $counterchildIB = $adultCount;


            foreach ($roomGuest as $room => $guest) {
            // echo $room;
            // print_r($guest);
            $NoOfAdults = sizeof($guest['adult']);
            if (isset($guest['child'])) {

                $NoOfChild = sizeof($guest['child']);
            }
            $hotelPassengers[$room] = array();

            for ($a = 1; $a <= $NoOfAdults; $a++) {
                // echo $a . '\n';
                if ($room == 1 && $a == 1) {
                    $userFirstName = $guest['adult'][$a]['first_name'];
                    $userLastName = $guest['adult'][$a]['last_name'];
                    $userEmail = $guest['adult'][$a]['email'];
                    $userPhone = $guest['adult'][$a]['phone'];
                }
                if( isset($guest['adult'][$a]['passportNo']) ){

                    array_push($flightPassengerArrIB, array(
                        "Title" => $guest['adult'][$a]['title'],
                        "FirstName" => $guest['adult'][$a]['first_name'],
                        "LastName" => $guest['adult'][$a]['last_name'],
                        "PaxType" => 1,
                        "DateOfBirth" => isset($guest['adult'][$a]['adult_dob']) ? $guest['adult'][$a]['adult_dob'] : "1985-01-30T00:00:00",
                        "Gender" => ($guest['adult'][$a]['title'] == 'Mr') ? 1 : 2,
                        "AddressLine1" => $guest['adult'][$a]['address'],
                        "AddressLine2" => "",
                        "City" => $guest['adult'][$a]['address'],
                        "CountryCode" => $location->countryCode,
                        "CountryName" => $location->countryName,
                        "PassportNo" => isset($guest['adult'][$a]['passportNo']) ? $guest['adult'][$a]['passportNo'] : "",
                        "PassportExpiry" => isset($guest['adult'][$a]['passportExpire']) ? $guest['adult'][$a]['passportExpire'] : "",
                        "ContactNo" => $guest['adult'][$a]['phone'],
                        "Email" => $guest['adult'][$a]['email'],
                        "IsLeadPax" =>  ($a == 1) ? true : false,
                        "FFAirline" => "",
                        "FFNumber" => "",
                        "Nationality" => "",
                        "Fare" => array(
                            "BaseFare" => $fareQuoteIB[0]['BaseFare'] / $fareQuoteIB[0]['PassengerCount'],
                            "Tax" => $fareQuoteIB[0]['Tax'] / $fareQuoteIB[0]['PassengerCount'],
                            "YQTax" => $fareQuoteIB[0]['YQTax'] / $fareQuoteIB[0]['PassengerCount'],
                            "AdditionalTxnFeeOfrd" => $fareQuoteIB[0]['AdditionalTxnFeeOfrd'] / $fareQuoteIB[0]['PassengerCount'],
                            "AdditionalTxnFeePub" => $fareQuoteIB[0]['AdditionalTxnFeePub'] / $fareQuoteIB[0]['PassengerCount'],
                            "PGCharge" => $fareQuoteIB[0]['PGCharge'] / $fareQuoteIB[0]['PassengerCount']
                        )
                    ));
                }else{

                    array_push($flightPassengerArrIB, array(
                        "Title" => $guest['adult'][$a]['title'],
                        "FirstName" => $guest['adult'][$a]['first_name'],
                        "LastName" => $guest['adult'][$a]['last_name'],
                        "PaxType" => 1,
                        "DateOfBirth" => isset($guest['adult'][$a]['adult_dob']) ? $guest['adult'][$a]['adult_dob'] : "1985-01-30T00:00:00",
                        "Gender" => ($guest['adult'][$a]['title'] == 'Mr') ? 1 : 2,
                        "AddressLine1" => $guest['adult'][$a]['address'],
                        "AddressLine2" => "",
                        "City" => $guest['adult'][$a]['address'],
                        "CountryCode" => $location->countryCode,
                        "CountryName" => $location->countryName,
                        "ContactNo" => $guest['adult'][$a]['phone'],
                        "PassportNo" => "KJHHJKHKJH",
                        "PassportExpiry"=> "2025-08-30",
                        "Email" => $guest['adult'][$a]['email'],
                        "IsLeadPax" =>  ($a == 1) ? true : false,
                        "FFAirline" => "",
                        "FFNumber" => "",
                        "Nationality" => "",
                        "Fare" => array(
                            "BaseFare" => $fareQuoteIB[0]['BaseFare'] / $fareQuoteIB[0]['PassengerCount'],
                            "Tax" => $fareQuoteIB[0]['Tax'] / $fareQuoteIB[0]['PassengerCount'],
                            "YQTax" => $fareQuoteIB[0]['YQTax'] / $fareQuoteIB[0]['PassengerCount'],
                            "AdditionalTxnFeeOfrd" => $fareQuoteIB[0]['AdditionalTxnFeeOfrd'] / $fareQuoteIB[0]['PassengerCount'],
                            "AdditionalTxnFeePub" => $fareQuoteIB[0]['AdditionalTxnFeePub'] / $fareQuoteIB[0]['PassengerCount'],
                            "PGCharge" => $fareQuoteIB[0]['PGCharge'] / $fareQuoteIB[0]['PassengerCount']
                        )
                    ));

                }

                if(isset($input['baggage_return_ib' . $a]) && $input['baggage_return_ib' . $a] != ''){
                    $input['baggage_return_ib' . $a] = json_decode($input['baggage_return_ib' . $a], true);
                    $flightPassengerArrIB[$a-1]['Baggage'] = array();
                    array_push($flightPassengerArrIB[$a-1]['Baggage'], $input['baggage_return_ib' . $a]);
                }

                if(isset($input['meallcc_return_ib' . $a]) && $input['meallcc_return_ib' . $a] != ''){
                    $input['meallcc_return_ib' . $a] = json_decode($input['meallcc_return_ib' . $a], true);
                    $flightPassengerArrIB[$a-1]['MealDynamic'] = array();
                    array_push($flightPassengerArrIB[$a-1]['MealDynamic'], $input['meallcc_return_ib' . $a]);
                }


            }

            if (isset($NoOfChild)) {

                for ($c = 1; $c <= $NoOfChild; $c++) {

                    if(isset($guest['child'][$c]['child_passport_no'])){

                            array_push($flightPassengerArrIB, array(
                                "Title" => $guest['child'][$c]['title'],
                                "FirstName" => $guest['child'][$c]['first_name'],
                                "LastName" => $guest['child'][$c]['last_name'],
                                "PaxType" => 2,
                                "DateOfBirth" => date('Y-m-d', strtotime($guest['child'][$c]['child_dob'])) . "T00:00:00",
                                "Gender" => ($guest['child'][$c]['title']) ? 1 : 2,
                                "AddressLine1" => $guest['child'][$c]['address'],
                                "AddressLine2" => "",
                                "City" => $guest['child'][$c]['address'],
                                "CountryCode" => $location->countryCode,
                                "CountryName" => $location->countryName,
                                "PassportNo" => isset($guest['child'][$c]['child_passport_no']) ? $guest['child'][$c]['child_passport_no'] : "",
                                "PassportExpiry" => isset($guest['child'][$c]['child_pass_expiry_date']) ? $guest['child'][$c]['child_pass_expiry_date']: "",
                                "ContactNo" => $guest['child'][$c]['child_phone'],
                                "Email" => $guest['child'][$c]['child_email'],
                                "IsLeadPax" => false,
                                "FFAirline" => "",
                                "FFNumber" => "",
                                "Nationality" => "",
                                "Fare" => array(
                                    "BaseFare" => $fareQuoteIB[1]['BaseFare'] / $fareQuoteIB[1]['PassengerCount'],
                                    "Tax" => $fareQuoteIB[1]['Tax'] / $fareQuoteIB[1]['PassengerCount'],
                                    "YQTax" => $fareQuoteIB[1]['YQTax'] / $fareQuoteIB[1]['PassengerCount'],
                                    "AdditionalTxnFeeOfrd" => $fareQuoteIB[1]['AdditionalTxnFeeOfrd'] / $fareQuoteIB[1]['PassengerCount'],
                                    "AdditionalTxnFeePub" => $fareQuoteIB[1]['AdditionalTxnFeePub'] / $fareQuoteIB[1]['PassengerCount'],
                                    "PGCharge" => $fareQuoteIB[1]['PGCharge'] / $fareQuoteIB[1]['PassengerCount']
                                )
                             ));

                        }else{

                            array_push($flightPassengerArrIB, array(
                                "Title" => $guest['child'][$c]['title'],
                                "FirstName" => $guest['child'][$c]['first_name'],
                                "LastName" => $guest['child'][$c]['last_name'],
                                "PaxType" => 2,
                                "DateOfBirth" => date('Y-m-d', strtotime($guest['child'][$c]['child_dob'])) . "T00:00:00",
                                "Gender" => ($guest['child'][$c]['title']) ? 1 : 2,
                                "AddressLine1" => $guest['child'][$c]['address'],
                                "AddressLine2" => "",
                                "City" => $guest['child'][$c]['address'],
                                "CountryCode" => $location->countryCode,
                                "CountryName" => $location->countryName,
                                "ContactNo" => $guest['child'][$c]['child_phone'],
                                "Email" => $guest['child'][$c]['child_email'],
                                "PassportNo" => "KJHHJKHKJH",
                                "PassportExpiry"=> "2025-08-30",
                                "IsLeadPax" => false,
                                "FFAirline" => "",
                                "FFNumber" => "",
                                "Nationality" => "",
                                "Fare" => array(
                                    "BaseFare" => $fareQuoteIB[1]['BaseFare'] / $fareQuoteIB[1]['PassengerCount'],
                                    "Tax" => $fareQuoteIB[1]['Tax'] / $fareQuoteIB[1]['PassengerCount'],
                                    "YQTax" => $fareQuoteIB[1]['YQTax'] / $fareQuoteIB[1]['PassengerCount'],
                                    "AdditionalTxnFeeOfrd" => $fareQuoteIB[1]['AdditionalTxnFeeOfrd'] / $fareQuoteIB[1]['PassengerCount'],
                                    "AdditionalTxnFeePub" => $fareQuoteIB[1]['AdditionalTxnFeePub'] / $fareQuoteIB[1]['PassengerCount'],
                                    "PGCharge" => $fareQuoteIB[1]['PGCharge'] / $fareQuoteIB[1]['PassengerCount']
                                )
                             ));

                        }

                        if(isset($input['child_baggage_return_ib' . $c]) && $input['child_baggage_return_ib' . $c] != ''){

                            $input['child_baggage_return_ib' . $c] = json_decode($input['child_baggage_return_ib' . $c], true);
                            $flightPassengerArrIB[$counterchildIB]['Baggage'] = array();

                            array_push($flightPassengerArrIB[$counterchildIB]['Baggage'], $input['child_baggage_return_ib' . $c]);

                        }

                        if(isset($input['child_meallcc_return_ib' . $c]) && $input['child_meallcc_return_ib' . $c] != ''){

                            $input['child_meallcc_return_ib' . $c] = json_decode($input['child_meallcc_return_ib' . $c], true);
                            $flightPassengerArrIB[$counterchildIB]['MealDynamic'] = array();

                            array_push($flightPassengerArrIB[$counterchildIB]['MealDynamic'], $input['child_meallcc_return_ib' . $c]);

                        }

                        $counterchildIB++;

                }
            }

            // $roomCount++;
        }


            /* End Object */
            $currency = Session::get('currency');
            $bookFlightArrIB = array('PreferredCurrency' => $currency,
                "IsBaseCurrencyRequired" => "true",
                "EndUserIp" => $this->flightapi->userIP,
                "TokenId" => $this->flightapi->tokenId,
                "TraceId" => $input['trace_id'],
                "ResultIndex" => $input['ibindex'],
                "Passengers" => $flightPassengerArrIB
            );

            if ($input['is_ib_lcc'] && $input['is_ib_lcc'] != '') {

                $this->bookingResultIB = $this->flightapi->ticketIB($bookFlightArrIB);

                if (isset($this->bookingResultIB['Response']) && isset($this->bookingResultIB['Response']['ResponseStatus']) && $this->bookingResultIB['Response']['ResponseStatus'] == 1) {

                    $bookingDetails = $this->bookingResultIB['Response'];

                    $travelArr['ticket_id'] = array();

                    foreach ($bookingDetails['Response']['FlightItinerary']['Passenger'] as $key => $value) {
                        # code...
                        array_push($travelArr['ticket_id'], $value['Ticket']['TicketId']);
                    }

                    //create entry on FlightBookings table

                    $booking = FlightBookings::create(['booking_id' => $bookingDetails['Response']['BookingId'],
                                'trace_id' => $bookingDetails['TraceId'],
                                'user_id' => Auth::user()->id,
                                'token_id' => $this->flightapi->tokenId,
                                'is_lcc' => $bookingDetails['Response']['FlightItinerary']['IsLCC'],
                                'flight_booking_status' => $bookingDetails['Response']['TicketStatus'],
                                'invoice_number' => $bookingDetails['Response']['FlightItinerary']['InvoiceNo'],
                                'pnr' => $bookingDetails['Response']['PNR'],
                                'booking_ref' => $bookingDetails['Response']['FlightItinerary']['ParentBookingId'],
                                'price_changed' => $bookingDetails['Response']['IsPriceChanged'],
                                'cancellation_policy' => $bookingDetails['Response']['FlightItinerary']['CancellationCharges'],
                                'request_data' => json_encode(array('travelData' => $travelArr, 'bookingData' => $bookFlightArrIB, 'razorpay_payment_id' => $input['razorpay_payment_id'],
                                    'razorpay_signature' => $input['razorpay_signature']))
                    ]);

                    //create entry to FlightPayments table
                    // $payments = FlightPayments::create(['booking_id' => $booking->id,
                    //                   'user_id' => Auth::user()->id,
                    //                   'agent_id' => $agent_id,
                    //                   'commission' => $commission_agent,
                    //                   'price' => $input['amount'],
                    //                   'customer_id' => "",
                    //                   'sub_domain' => '']);

                    $payments = array();

                    $travelArr['city_code_arrival'] = $flightSearch['destination'];
                    $travelArr['city_code_departure'] = $flightSearch['origin'];


                    $travelArr['main_start'] = $flightSearch['to'];
                    $travelArr['to_start'] = $flightSearch['from'];
                    $travelArr['departure_date_arr'] = $input['departure_date_dep'];

                    $booking->request_data = array('travelData' => $travelArr, 'bookingData' => $bookFlightArr);

                    $segments = $bookingDetails['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetails['Response']['FlightItinerary']['FareRules'];

                    if(isset($agent_id) && $agent_id != ''){   

                        if($NoOfAdults == 1){
                            $adultText = 'Adult';
                        }else{
                            $adultText = 'Adults';
                        }

                        if(isset($NoOfChild) && $NoOfChild > 0 ){


                            if($NoOfChild == 1){
                                $childText = 'Child';
                            }else{
                                $childText = 'Childs';
                            }

                            $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' and '.$NoOfChild.' '.$childText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        }else{

                             $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';    
                        }

                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => $post_content,
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }


                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){
                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    }
                    //Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $bookingDetails['Response']['FlightItinerary']['Segments'], $bookingDetails['Response']['FlightItinerary']['FareRules']));

                    $this->emptyFlightSession();
                    //send to thank you page
                    $hotelId = $this->bookFlightRoom($input);

                    if(isset($hotelId['success']) && $hotelId['success']){

                        return redirect('/thankyou/flight-hotel/' . $hotelId['booking_id'] . '/'.$booking->id);
                    }else{
                        return view('500')->with(['error' => $hotelId['message']]);  
                    }
                    //send to thank you page
                   // return redirect('/thankyou/flight/' . $booking->id . '/true');
                } else {
                    $message = $this->bookingResultIB['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    return view('500')->with(['error' => $message]);
                }
            } else {

                $this->bookingResultIBNOLCC = $this->flightapi->bookIB($bookFlightArrIB);

                $bookingDetailsIBNOLCC = $this->bookingResultIBNOLCC['Response'];

                //echo "<pre>";print_r($bookingDetailsNOLCC);echo "</pre>";
                if ($bookingDetailsIBNOLCC['ResponseStatus'] != 1) {
                    $this->emptyFlightSession();
                    // return view('error')->with(['error' => $bookingDetailsIBNOLCC['Error']['ErrorMessage']]);
                    return view('500')->with(['error' => $bookingDetailsIBNOLCC['Error']['ErrorMessage']]);
                }
                $this->TicketResultIB = $this->flightapi->getNoLCCTicketIB($input['trace_id'], $bookingDetailsIBNOLCC['Response']['BookingId'], $bookingDetailsIBNOLCC['Response']['PNR']);

                //$ticketDetailsNOLCC = $this->TicketResult['Response'];

                if (isset($this->TicketResultIB['Response']) && isset($this->TicketResultIB['Response']['ResponseStatus']) && $this->TicketResultIB['Response']['ResponseStatus'] == 1) {

                    $ticketDetailsIBNOLCC = $this->TicketResultIB['Response'];

                    if ($ticketDetailsIBNOLCC['Response']['FlightItinerary']['IsLCC']) {
                        $ticketDetailsIBNOLCC['Response']['FlightItinerary']['IsLCC'] = 1;
                    } else {
                        $ticketDetailsIBNOLCC['Response']['FlightItinerary']['IsLCC'] = 0;
                    }

                    $travelArr['ticket_id'] = array();

                    foreach ($ticketDetailsIBNOLCC['Response']['FlightItinerary']['Passenger'] as $key => $value) {
                        # code...
                        array_push($travelArr['ticket_id'], $value['Ticket']['TicketId']);
                    }


                    $booking = FlightBookings::create(['booking_id' => $ticketDetailsIBNOLCC['Response']['BookingId'],
                                'trace_id' => $ticketDetailsIBNOLCC['TraceId'],
                                'user_id' => Auth::user()->id,
                                'token_id' => $this->flightapi->tokenId,
                                'is_lcc' => $ticketDetailsIBNOLCC['Response']['FlightItinerary']['IsLCC'],
                                'flight_booking_status' => $ticketDetailsIBNOLCC['Response']['TicketStatus'],
                                'invoice_number' => $ticketDetailsIBNOLCC['Response']['FlightItinerary']['InvoiceNo'],
                                'pnr' => $ticketDetailsIBNOLCC['Response']['PNR'],
                                'booking_ref' => $ticketDetailsIBNOLCC['Response']['FlightItinerary']['ParentBookingId'],
                                'price_changed' => $ticketDetailsIBNOLCC['Response']['IsPriceChanged'],
                                'cancellation_policy' => $ticketDetailsIBNOLCC['Response']['FlightItinerary']['CancellationCharges'],
                                'request_data' => json_encode(array('travelData' => $travelArr, 'bookingData' => $bookFlightArrIB, 'razorpay_payment_id' => $input['razorpay_payment_id'],
                                    'razorpay_signature' => $input['razorpay_signature']))
                    ]);

                    //create entry to FlightPayments table
                    // $payments = FlightPayments::create(['booking_id' => $booking->id,
                    //                   'user_id' => Auth::user()->id,
                    //                   'agent_id' => $agent_id,
                    //                   'commission' => $commission_agent,
                    //                   'price' => $input['amount'],
                    //                   'customer_id' => "",
                    //                   'sub_domain' => '']);

                    $payments = array();


                    $travelArr['city_code_arrival'] = $flightSearch['destination'];
                    $travelArr['city_code_departure'] = $flightSearch['origin'];


                    $travelArr['main_start'] = $flightSearch['to'];
                    $travelArr['to_start'] = $flightSearch['from'];
                    $travelArr['departure_date_arr'] = $input['departure_date_dep'];

                    $booking->request_data = array('travelData' => $travelArr, 'bookingData' => $bookFlightArr);


                    $segments = $bookingDetailsIBNOLCC['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetailsIBNOLCC['Response']['FlightItinerary']['FareRules'];

                    if(isset($agent_id) && $agent_id != ''){   

                        //$post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . Session::get('flightSearhData')['travellersClass'] . '</b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';


                        if($NoOfAdults == 1){
                            $adultText = 'Adult';
                        }else{
                            $adultText = 'Adults';
                        }

                        if(isset($NoOfChild) && $NoOfChild > 0 ){


                            if($NoOfChild == 1){
                                $childText = 'Child';
                            }else{
                                $childText = 'Childs';
                            }

                            $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' and '.$NoOfChild.' '.$childText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        }else{

                             $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $NoOfAdults . ' '.$adultText.' </b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';    
                        }

                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => $post_content,
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }
                    

                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){
                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                    }
                    //Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $ticketDetailsIBNOLCC['Response']['FlightItinerary']['Segments'], $ticketDetailsIBNOLCC['Response']['FlightItinerary']['FareRules']));
                    //Mail::to($userEmail)->send(new FlightBookingEmail($booking, $payments, $ticketDetailsIBNOLCC['Response']['FlightItinerary']['Segments'], $ticketDetailsIBNOLCC['Response']['FlightItinerary']['FareRules']));

                    $this->emptyFlightSession();
                    //send to thank you page
                    $hotelId = $this->bookFlightRoom($input);

                    if(isset($hotelId['success']) && $hotelId['success']){

                        return redirect('/thankyou/flight-hotel/' . $hotelId['booking_id'] . '/'.$booking->id);

                    }else{
                        
                        return view('500')->with(['error' => $hotelId['message']]);  
                    }
                    
                    //send to thank you page
                   // return redirect('/thankyou/flight/' . $booking->id . '/true');
                } else {
                    $message = $this->TicketResultIB['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    return view('500')->with(['error' => $message]);
                }
            }
        }

    }


    public function cancelBooking(Request $request) {
        if ($request->isMethod('post')) {
            $input = $request->all();
            $this->api = new TBOHotelAPI();
            $input['RequestType'] = 4;
            $input['EndUserIp'] = $this->api->userIP;
            // echo "<pre>";
            unset($input['_token']);
            unset($input['submit']);
            // print_r(json_encode($input));
            try {

                $sendChangeRequest = $this
                        ->api
                        ->sendChangeRequest($input);
                //print_r($sendChangeRequest);


                if (isset($sendChangeRequest['HotelChangeRequestResult']) && $sendChangeRequest['HotelChangeRequestResult']['ResponseStatus'] == 1) {
                    //call get change request
                    $postData = array('TokenId' => $input['TokenId'], 'EndUserIp' => $this->api->userIP, 'ChangeRequestId' => $sendChangeRequest['HotelChangeRequestResult']['ChangeRequestId']);
                    $getChangeRequest = $this
                            ->api
                            ->getChangeRequest($postData);

                    if (isset($getChangeRequest['HotelChangeRequestStatusResult']) && $getChangeRequest['HotelChangeRequestStatusResult']['ResponseStatus'] == 1) {
                        $message = '';
                        $bookingStatus = 'Confirmed';
                        if ($getChangeRequest['HotelChangeRequestStatusResult']['ChangeRequestStatus'] == 0) {
                            $message = 'Invalid request values, please try again.';
                            $bookingStatus = 'Confirmed';
                        } else if ($getChangeRequest['HotelChangeRequestStatusResult']['ChangeRequestStatus'] == 1) {
                            $message = 'Booking cancellation request is accepted but pending for processing.';
                            $bookingStatus = 'Processing';
                        } else if ($getChangeRequest['HotelChangeRequestStatusResult']['ChangeRequestStatus'] == 2) {
                            $message = 'Booking cancellation request is in progress.';
                            $bookingStatus = 'In Progress';
                        } else if ($getChangeRequest['HotelChangeRequestStatusResult']['ChangeRequestStatus'] == 3) {
                            $message = 'Booking cancellation request is completed.';
                            $bookingStatus = 'Cancelled';
                        } else if ($getChangeRequest['HotelChangeRequestStatusResult']['ChangeRequestStatus'] == 4) {
                            $message = 'Booking cancellation request is rejected.';
                            $bookingStatus = 'Rejected';
                        }

                        /*
                         * Update Booking Status
                         */
                        $refunded_amount = (isset($getChangeRequest['HotelChangeRequestStatusResult']['RefundedAmount'])) ? $getChangeRequest['HotelChangeRequestStatusResult']['RefundedAmount'] : 0;
                        if($refunded_amount > 0) {
                            //conver refund amount to USD
                            $lAmount = Currency::convert('INR', 'USD', $refunded_amount);
                            $refunded_amount = $lAmount['convertedAmount'];
                        }
                        Bookings::where(['booking_id' => $input['BookingId'], 'user_id' => Auth::user()->id])
                                ->update(['hotel_booking_status' => $bookingStatus, 'change_request_id' => $sendChangeRequest['HotelChangeRequestResult']['ChangeRequestId']]);
                        Session::flash('success', $message);
                        return redirect("/user/bookings")->with('success', $message);
                    } else {
                        $message = $getChangeRequest['HotelChangeRequestStatusResult']['Error']['ErrorMessage'];
                        Session::flash('error', $message);
                        return redirect("/user/bookings")->with('error', $message);
                    }
                } else {
                    $message = $getChangeRequest['HotelChangeRequestStatusResult']['Error']['ErrorMessage'];
                    Session::flash('error', $message);
                    return redirect("/user/bookings")->with('error', $message);
                }
            } catch (Exception $e) {

                return view('500')->with(['error' => $e->getMessage()]);
            }

            // die();
        }
    }

    public function cancelBookingStatus(Request $request) {
        if ($request->isMethod('post')) {
            $this->api = new TBOHotelAPI();
            $input = $request->all();
            $postData = array('TokenId' => $input['TokenId'], 'EndUserIp' => $this->api->userIP, 'ChangeRequestId' => $input['ChangeRequestId']);
           
            $bookingStatus = '';
            $refunded_amount = 0;
            
            //check if refund is already procssed
            $checkRefund = Bookings::where(['user_id' => Auth::user()->id, 'change_request_id' => $input['ChangeRequestId']])->first();

            if(isset($checkRefund) && $checkRefund['refunded_amount'] > 0) {

                return response()->json(array(
                            'message' => "Your refund request has been completed",
                            'bookingStatus' => "completed",
                            'refunded_amount' => $checkRefund['refunded_amount']
                ));

            } else {

                $getChangeRequest = $this
                    ->api
                    ->getChangeRequest($postData);

                if (isset($getChangeRequest['HotelChangeRequestStatusResult']) && $getChangeRequest['HotelChangeRequestStatusResult']['ResponseStatus'] == 1) {
                    $message = '';
                    $bookingStatus = 'Confirmed';
                    if ($getChangeRequest['HotelChangeRequestStatusResult']['ChangeRequestStatus'] == 0) {
                        $message = 'Invalid request values, please try again.';
                        $bookingStatus = 'Confirmed';
                    } else if ($getChangeRequest['HotelChangeRequestStatusResult']['ChangeRequestStatus'] == 1) {
                        $message = 'Booking cancellation request is accepted but pending for processing.';
                        $bookingStatus = 'Processing';
                    } else if ($getChangeRequest['HotelChangeRequestStatusResult']['ChangeRequestStatus'] == 2) {
                        $message = 'Booking cancellation request is in progress.';
                        $bookingStatus = 'In Progress';
                    } else if ($getChangeRequest['HotelChangeRequestStatusResult']['ChangeRequestStatus'] == 3) {
                        $message = 'Booking cancellation request is completed.';
                        $bookingStatus = 'Cancelled';
                    } else if ($getChangeRequest['HotelChangeRequestStatusResult']['ChangeRequestStatus'] == 4) {
                        $message = 'Booking cancellation request is rejected.';
                        $bookingStatus = 'Rejected';
                    }

                    /*
                     * Update Booking Status
                     */
                    $refunded_amount = (isset($getChangeRequest['HotelChangeRequestStatusResult']['RefundedAmount'])) ? $getChangeRequest['HotelChangeRequestStatusResult']['RefundedAmount'] : 0;

                    if($refunded_amount > 0) {
                        //conver refund amount to USD
                        $lAmount = Currency::convert('INR', 'USD', $refunded_amount);
                        $refunded_amount = $lAmount['convertedAmount'];
                    }

                    Bookings::where(['user_id' => Auth::user()->id, 'change_request_id' => $input['ChangeRequestId']])
                            ->update(['hotel_booking_status' => $bookingStatus, 'refunded_amount' => $refunded_amount]);
                } else {
                    $message = $getChangeRequest['HotelChangeRequestStatusResult']['Error']['ErrorMessage'];
                }

                return response()->json(array(
                            'message' => $message,
                            'bookingStatus' => $bookingStatus,
                            'refunded_amount' => $refunded_amount
                ));
            }
        }
    }

    public function changeFlight(Request $request){

        $this->flightId = $request->flightID;
        $this->flightTraceId = $request->flightTraceId;
        $search_id = $request->searchId;

        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);

        //$flightdataAll = Session::get('flightDataAll');

        $flightdataAll =  $search_contents['response']['Results'][0];
        unset($flightdataAll[0]);


        $changedFlight = array();
        $changedFlight = $flightdataAll[$this->flightId];

        Session::put('flightData', $changedFlight);

        return response()->json(array(
                'message' => 'Flight Changed Successfully',
                'flight_id' => $this->flightId,
                'status' => true
        ));
    
    }

    public function changeFlightReturn(Request $request){

        $this->flightId = $request->flightID;
        $this->flightTraceId = $request->flightTraceId;
        $flightdataretrunAll = Session::get('flightReturnDataAll');
        
        $changedFlight = array();
        $changedFlight[$this->flightTraceId] = $flightdataretrunAll[$this->flightTraceId][$this->flightId];

        Session::put('flightReturnData', $changedFlight);

        return response()->json(array(
                'message' => 'Flight Changed Successfully',
                'status' => true
        ));
    
    }

    public function allRooms(Request $request) {

        $this->hotelCode = $request->hotelCode;
        $this->hoteIndex = $request->hotelIndex;
        $this->traceId = $request->traceId;
        $this->checkInDate = $request->checkInDate;
        $this->checkOutDate = $request->checkOutDate;
        $this->hotelName = $request->hotelName;

        $this->api = new TBOHotelAPI();
        $this->hotelRooms = $this
                ->api
                ->hotelRooms($this->hotelCode, $this->hoteIndex, $this->traceId);
        $this->hotelInfo = $this
                ->api
                ->hotelInfo($this->hotelCode, $this->hoteIndex, $this->traceId);
        return view('rooms')
                        ->with(['hotelRooms' => $this->hotelRooms, 'hotelInfo' => $this->hotelInfo, 'hotelCode' => $this->hotelCode, 'hoteIndex' => $this->hoteIndex, 'traceId' => $this->traceId, 'checkInDate' => $this->checkInDate, 'checkOutDate' => $this->checkOutDate, 'hotelName' => $this->hotelName]);
    }

    public function addRating(Request $request) {

        $input = $request->all();

        try {

            Reviews::create($input);

            return redirect('/activity/' . $input['activity_id'])->with('success', 'Review added Successfully.');
        } catch (Exception $e) {

            return redirect('/activity/' . $input['activity_id'])->with('error', $e->getMessage());
        }
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function emptySession() {

        //Session::forget('RoomCombination');
        // Session::forget('RoomArr');
        Session::forget('hotelName');
        Session::forget('hotelCode');
        Session::forget('resultIndex');
        Session::forget('traceID');
        Session::forget('checkInDate');
        Session::forget('checkOutDate');
        Session::forget('noOfNights');
        Session::forget('noofrooms');
        Session::forget('currency');
        Session::forget('country');
        Session::forget('selectedGuests');
        Session::forget('countryName');
        Session::forget('BookRoomDetails');
        Session::forget('IsPackageFare');
        Session::forget('IsPackageDetailsMandatory');
        Session::forget('CategoryId');
        Session::forget('hotelSearchInput');
    }

    public function emptyFlightSession(){

        Session::forget('flightData');
        Session::forget('flightDataAll');
        Session::forget('flightAdCount');
        Session::forget('flightChCount');
        Session::forget('flightTraceId');
        Session::forget('checkInDate');
        Session::forget('flightSearhData');
        Session::forget('flightReturnData');
        Session::forget('flightReturnDataAll');

    }

    public function getImages($room_type) {
        $type = preg_replace('/\s*/', '', $room_type);
        $images = RoomImages::where(['r_type' => strtolower($type), 'sub_domain' => $this
                    ->sub_domain])
                ->first();
        return $images;
    }

    public function importRooms(Request $request) {

        if (Auth::check() && Auth::user()->role == 'admin') {
            if ($request->isMethod('post')) {
                $input = $request->all();

                $end_date = str_replace('/', '-', $input['checkout']);
                $end_date = date('Y-m-d', strtotime($end_date));

                $first_date = strtotime($input['checkin']);
                $end_date = strtotime($input['checkout']);
                $timeDiff = abs($end_date - $first_date);

                $numberDays = $timeDiff / 86400; // 86400 seconds in one day
                $input['noOfNights'] = intval($numberDays);
                //$input['NoOfNights'] =  $noOfNights;
                $input['CheckInDate'] = date('d/m/yy', strtotime($input['checkin']));
                $noOfRooms = $input['rooms'];

                $dbHotel = Cities::where(['HotelName' => $this
                            ->sub_domain])
                        ->first();
                $this->hotelCode = $dbHotel['HotelCode'];
                $roomsGuests = array();
                for ($r = 1; $r <= $input['rooms']; $r++) {
                    $ages = array();
                    for ($c = 1; $c <= $input['childs']; $c++) {
                        array_push($ages, "7");
                    }
                    array_push($roomsGuests, array(
                        "NoOfAdults" => $input['adults'],
                        "NoOfChild" => $input['childs'],
                        "ChildAge" => $ages
                    ));
                }

                $postData = ["CheckInDate" => str_replace('-', '/', $input['checkout']), "NoOfNights" => $input['noOfNights'], "CountryCode" => 'AE', "CityId" => '115936', "ResultCount" => null, "PreferredCurrency" => 'AED', "GuestNationality" => 'IN', "NoOfRooms" => $input['rooms'], "RoomGuests" => $roomsGuests, "HotelCode" => $this->hotelCode, "MaxRating" => 5, "MinRating" => 3, "ReviewScore" => null, "IsTBOMapped" => true, "IsNearBySearchAllowed" => false, "EndUserIp" => $this
                    ->api->userIP, "TokenId" => $this
                    ->api->tokenId,];

                $this->api = new TBOHotelAPI();
                $allHotels = $this
                        ->api
                        ->hotelSearch($postData);

                $this->traceId = $allHotels['HotelSearchResult']['TraceId'];
                $this->hotelCode = $allHotels['HotelSearchResult']['HotelResults'][0]['HotelCode'];
                $this->hoteIndex = $allHotels['HotelSearchResult']['HotelResults'][0]['ResultIndex'];
                $this->hotelName = $allHotels['HotelSearchResult']['HotelResults'][0]['HotelName'];

                $this->hotelDetails = $allHotels['HotelSearchResult']['HotelResults'][0];

                $this->traceId = $allHotels['HotelSearchResult']['TraceId'];

                $supplierCategories = array();
                foreach ($allHotels['HotelSearchResult']['HotelResults'][0]['SupplierHotelCodes'] as $key => $sC) {
                    array_push($supplierCategories, $sC['CategoryIndex']);
                }

                $this->hotelRooms = $this
                        ->api
                        ->hotelRooms($this->hotelCode, $this->hoteIndex, $this->traceId, $supplierCategories);

                foreach ($this->hotelRooms['GetHotelRoomResult']['HotelRoomsDetails'] as $key => $room) {

                    $type = preg_replace('/\s*/', '', $room['RoomTypeName']);
                    $check = RoomImages::where(['r_type' => strtolower($type), 'sub_domain' => $this
                                ->sub_domain])
                            ->first();
                    if (isset($check) && !empty($check)) {
                        
                    } else {
                        RoomImages::create(['r_type' => strtolower($type), 'sub_domain' => $this->sub_domain, 'name' => $room['RoomTypeName']]);
                    }
                }
                return redirect('/admin/import/rooms')->with('success', 'Rooms imported Successfully.');
                // die('rooms imported');
            }
            return view('admin.import-rooms');
        } else {
            return redirect('/login')
                            ->with('error', 'You are not allowed to access this page.');
        }
    }

    public function setCookie($name, $value, $time) {
        setcookie($name, $value, time() + (60 * $time), "/");
        return true;
    }
    
    public function getCookie($name) {
        return $_COOKIE[$name];
    }

    public function writeLogs($requestData, $responseData) {
        Log::info(['HotelError:' => date('Y-m-d h:i:s'), 'requestData' => $requestData, 'responseData' => $responseData]);
    }
    public function writePaymeLogs($requestData,$responseData) {
        Log::info(['HotelError:' => date('Y-m-d h:i:s'), 'requestData' => $requestData,'responseData' => $responseData]);
    }


    public function searchRaw(Request $request) {

        $hotels = array();
        $this->temp = 'set value ';
        //$input = $request->all();
        $roomGuests = array();

        $queryValues = $request->query();
        $input_data = $request->all();


        $queryVals = '';
        $input = array();
        $input['departdate'] = $input_data['departdate'];
        $input['returndate'] = $input_data['returndate']; //date('d-m-Y', strtotime($input['departdate']. ' + '. $total_nights .' days'));
        $input['ishalal'] = $input_data['ishalal'];
        $date = Carbon::createFromDate($input['departdate']);
        $now = Carbon::createFromDate($input['returndate']);

        $noOfNights = $date->diffInDays($now);
        $input['roomCount'] = $queryValues['roomCount'];
        $input['city_id'] = $queryValues['city_id'];
        if (isset($queryValues['referral'])) {
            $input['referral'] = $queryValues['referral'];
        } else {
            $input['referral'] = 0;
        }

        if (isset($queryValues['preffered_hotel']) && $queryValues['preffered_hotel'] != '') {
            $input["preffered_hotel"] = $queryValues['preffered_hotel'];
        } else {
            $input["preffered_hotel"] = (isset($input_data['preffered_hotel'])) ? $input_data['preffered_hotel'] : '';
        }


        $startdate = $input['departdate'];
        $returndate = $input['returndate'];

        //get country from IP
        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {

            $location = \Location::get($this->end_user_ip);
        }

        $country = $location->countryCode;

        //get user currency
        $countryInfo = Currencies::where('code', $location->countryCode)->first();
        //$input['currency'] = $countryInfo['currency_code'];

        $ourCurrency = Config::get('ourcurrency');

        if (in_array($countryInfo['currency_code'], $ourCurrency)) {
            $input['currency']= $countryInfo['currency_code'];
        } else {
            $input['currency']= 'USD';
        }
        
        $searchCountry = Cities::where('CityId', $input['city_id'])->first();

        $input['countryCode'] = $searchCountry['CountryCode'];
        $input['city_name'] = $searchCountry['CityName'];
        $currencyCode = $location->countryCode;
        $currency = $input['currency'];
        $input['countryName'] = $input_data['countryName'];

        //$input['countryName'] = (isset($location->countryName) && $location->countryName != '') ? $location->countryName : $countryInfo['name'];
        $input['user_country'] = (isset($location->countryName) && $location->countryName != '') ? $location->countryName : $countryInfo['name'];

        $date = Carbon::createFromDate($startdate);
        $now = Carbon::createFromDate($returndate);

        $noOfNights = $date->diffInDays($now);

        $roomguests = array();

        $input['NoOfNights'] = $noOfNights;

        $input['CheckInDate'] = str_replace("-", "/", $input['departdate']);

        $noOfRooms = $input['roomCount'];

        $roomGuests = array();
        $total_guests = 0;

        for ($i = 1; $i <= $noOfRooms; $i++) {
            $childAges = array();

            if (isset($queryValues['c' . $i]) && $queryValues['c' . $i] > 0) {

                if ($queryVals == '') {
                    $queryVals = 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                } else {
                    $queryVals = '&' . $queryVals . 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                }

                for ($ca = 1; $ca <= $queryValues['c' . $i]; $ca++) {
                    array_push($childAges, $queryValues['ca' . $ca . 'r' . $i]);
                    $input['ca' . $ca . 'r' . $i] = $queryValues['ca' . $ca . 'r' . $i];
                    $queryVals = $queryVals . '&' . 'ca' . $i . 'r' . $i . '=' . $queryValues['ca' . $ca . 'r' . $i];
                }

                if (isset($childAges) && sizeof($childAges) > 0) {

                    array_push($roomGuests, array(
                        'NoOfAdults' => $queryValues['a' . $i],
                        'NoOfChild' => $queryValues['c' . $i],
                        'ChildAge' => $childAges
                    ));

                    $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                    $input['childCountRoom' . $i] = $queryValues['c' . $i];

                    $total_guests = $total_guests + $queryValues['a' . $i] + $queryValues['c' . $i];
                } else {
                    array_push($roomGuests, array(
                        'NoOfAdults' => $queryValues['a' . $i],
                        'NoOfChild' => $queryValues['c' . $i],
                        'ChildAge' => null
                    ));

                    $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                    $input['childCountRoom' . $i] = $queryValues['c' . $i];

                    $total_guests = $total_guests + $queryValues['a' . $i] + $queryValues['c' . $i];

                    if ($queryVals == '') {
                        $queryVals = 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                    } else {

                        $queryVals = '&' . $queryVals . 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                    }
                }
            } else {
                array_push($roomGuests, array(
                    'NoOfAdults' => (isset($queryValues['a' . $i])) ? $queryValues['a' . $i] : 0,
                    'NoOfChild' => 0,
                    'ChildAge' => null
                ));

                $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                $input['childCountRoom' . $i] = $queryValues['c' . $i];

                $total_guests = $total_guests + $queryValues['a' . $i] + $queryValues['c' . $i];

                if ($queryVals == '') {
                    $queryVals = 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                } else {

                    $queryVals = '&' . $queryVals . 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
                }
            }
        }

        $input['roomsGuests'] = $input['roomCount'] . ' Rooms ' . $total_guests . ' Guests';
        $input['Location'] = $input_data['Location'];
        $input['Longitude'] = $input_data['Longitude'];
        $input['Latitude'] = $input_data['Latitude'];
        $input['Radius'] = $input_data['Radius'];

        Session::put('hotelSearchInput', $input);

        // echo  "<pre>"; print_r($input); print_r($input_data);  die();

        if (isset($input['preffered_hotel']) && $input['preffered_hotel'] != '') {

            //$input["preffered_hotel"] = $queryValues['preffered_hotel'];

            $this->api = new TBOHotelAPI();
            $postData = ["CheckInDate" => $input['CheckInDate'], "NoOfNights" => $noOfNights, "CountryCode" => $input['countryCode'], "CityId" => $input['city_id'], "ResultCount" => null, "PreferredCurrency" => $currency, "GuestNationality" => $country, "NoOfRooms" => $noOfRooms, "RoomGuests" => $roomGuests, "MaxRating" => 5, "MinRating" => 3, "ReviewScore" => null, "IsTBOMapped" => true, "IsNearBySearchAllowed" => false, "EndUserIp" => $this
                ->api->userIP, "TokenId" => $this
                ->api->tokenId, "HotelCode" => $input["preffered_hotel"]];


            // echo $queryVals; die();
            try {
                $hotels = $this
                        ->api
                        ->hotelSearch($postData);

                if (isset($hotels['HotelSearchResult']) && isset($hotels['HotelSearchResult']['ResponseStatus']) && $hotels['HotelSearchResult']['ResponseStatus'] == 1) {

                    if (sizeof($hotels['HotelSearchResult']['HotelResults']) > 0) {

                        //set session expirey cookie
                        $this->setCookie('hotel_session', time() + (60 * 13), 20);
                        $this->setCookie('hotel_city', $input['city_id'], 20);

                        $results_hotels = array();
                        $hotel = $hotels['HotelSearchResult']['HotelResults'][0];
                        $traceId = $hotels['HotelSearchResult']['TraceId'];

                        if (isset($hotel['IsTBOMapped']) && $hotel['IsTBOMapped'] == true) {
                            // $static_data = StaticDataHotels::where(['city_id' => $input['city_id'], 'hotel_code' => $hotel['HotelCode']])->first();
                            $static_data = StaticDataHotels::select('hotel_location', 'hotel_images', 'start_rating', 'hotel_facilities', 'hotel_address', 'hotel_name', 'id', 'ishalal')->where(['city_id' => $input['city_id'], 'hotel_code' => $hotel['HotelCode']])->first();

                            if (isset($static_data)) {

                                $hotel['h_rating'] = ($static_data['start_rating'] != null) ? (int) $static_data['start_rating'] : 0;

                                if (isset($static_data['hotel_images']) && !empty($static_data['hotel_images'])) {
                                    $static_data['hotel_images'] = json_decode($static_data['hotel_images']);
                                }

                                if (isset($static_data['hotel_facilities']) && !empty($static_data['hotel_facilities'])) {
                                    $static_data['hotel_facilities'] = json_decode($static_data['hotel_facilities']);
                                }

                                // if (isset($static_data['attractions']) && !empty($static_data['attractions'])) {
                                //     $static_data['attractions'] = json_decode($static_data['attractions']);
                                // }
                                // if (isset($static_data['hotel_description']) && !empty($static_data['hotel_description'])) {
                                //     $static_data['hotel_description'] = json_decode($static_data['hotel_description']);
                                // }
                                // if (isset($static_data['hotel_location']) && !empty($static_data['hotel_location'])) {
                                //     $static_data['hotel_location'] = json_decode($static_data['hotel_location']);
                                // }

                                if (isset($static_data['hotel_address']) && !empty($static_data['hotel_address'])) {
                                    $static_data['hotel_address'] = json_decode($static_data['hotel_address']);
                                }

                                if (isset($static_data['hotel_contact']) && !empty($static_data['hotel_contact'])) {
                                    $static_data['hotel_contact'] = json_decode($static_data['hotel_contact']);
                                }

                                // if (isset($static_data['hotel_time']) && !empty($static_data['hotel_time'])) {
                                //     $static_data['hotel_time'] = json_decode($static_data['hotel_time']);
                                // }
                                // if (isset($static_data['hotel_type']) && !empty($static_data['hotel_type'])) {
                                //     $static_data['hotel_type'] = json_decode($static_data['hotel_type']);
                                // }
                                unset($hotel['HotelName']);
                                unset($hotel['HotelCategory']);
                                unset($hotel['HotelDescription']);
                                unset($hotel['HotelPromotion']);
                                unset($hotel['HotelPolicy']);
                                unset($hotel['IsTBOMapped']);
                                unset($hotel['HotelAddress']);
                                unset($hotel['HotelContactNo']);
                                unset($hotel['HotelMap']);
                                unset($hotel['Latitude']);
                                unset($hotel['Longitude']);
                                unset($hotel['HotelLocation']);
                                unset($hotel['SupplierPrice']);
                                unset($hotel['RoomDetails']);
                                unset($hotel['Price']['GST']);
                                unset($hotel['Price']['RoomPrice']);
                                unset($hotel['Price']['Tax']);
                                unset($hotel['Price']['ExtraGuestCharge']);
                                unset($hotel['Price']['ChildCharge']);
                                unset($hotel['Price']['OtherCharges']);
                                unset($hotel['Price']['Discount']);
                                unset($hotel['Price']['AgentCommission']);
                                unset($hotel['Price']['AgentMarkUp']);
                                unset($hotel['Price']['ServiceTax']);
                                unset($hotel['Price']['TCS']);
                                unset($hotel['Price']['TDS']);
                                unset($hotel['Price']['ServiceCharge']);
                                unset($hotel['Price']['TotalGSTAmount']);
                                array_push($results_hotels, array(
                                    'TBO_data' => $hotel,
                                    'static_data' => $static_data
                                ));
                            } else {

                                $hotel['h_rating'] = isset($hotel['StarRating']) ? (int) $hotel['StarRating'] : 0;

                                unset($hotel['HotelName']);
                                unset($hotel['HotelCategory']);
                                unset($hotel['HotelDescription']);
                                unset($hotel['HotelPromotion']);
                                unset($hotel['HotelPolicy']);
                                unset($hotel['IsTBOMapped']);
                                unset($hotel['HotelAddress']);
                                unset($hotel['HotelContactNo']);
                                unset($hotel['HotelMap']);
                                unset($hotel['Latitude']);
                                unset($hotel['Longitude']);
                                unset($hotel['HotelLocation']);
                                unset($hotel['SupplierPrice']);
                                unset($hotel['RoomDetails']);
                                unset($hotel['Price']['GST']);
                                unset($hotel['Price']['RoomPrice']);
                                unset($hotel['Price']['Tax']);
                                unset($hotel['Price']['ExtraGuestCharge']);
                                unset($hotel['Price']['ChildCharge']);
                                unset($hotel['Price']['OtherCharges']);
                                unset($hotel['Price']['Discount']);
                                unset($hotel['Price']['AgentCommission']);
                                unset($hotel['Price']['AgentMarkUp']);
                                unset($hotel['Price']['ServiceTax']);
                                unset($hotel['Price']['TCS']);
                                unset($hotel['Price']['TDS']);
                                unset($hotel['Price']['ServiceCharge']);
                                unset($hotel['Price']['TotalGSTAmount']);
                                array_push($results_hotels, array(
                                    'TBO_data' => $hotel,
                                    'static_data' => $static_data
                                ));
                            }
                        }

                        $request->session()->forget('hotels_list');
                        Session::put('hotels_list', serialize($results_hotels));


                        return redirect('/hotel/' . $traceId . '/' . $hotel['HotelCode'] . '/' . $input['departdate'] . '/' . $noOfRooms . '/' . $input['city_id'] . '/' . $noOfNights . '/' . $input['referral'] . '?' . $queryVals);
                    } else {
                        $this->emptySession();
                        return view('500')->with(['error' => 'You results found for your search, please try again.']);
                    }
                } else {
                    $this->emptySession();
                    return view('500')->with(['error' => $hotels['HotelSearchResult']['Error']['ErrorMessage']]);
                }
            } catch (Exception $e) {
                $this->emptySession();
                return view('500')->with(['error' => $e->getMessage()]);
            }
        } else {

            return view('search.flights-hotels.hotels')->with(['hotels' => $hotels, 'input' => $input, 'referral' => $_GET['referral']]);
        }
    }


    public function searchHotelsRaw(Request $request) {

        $postJson = file_get_contents('php://input');
        $postArray = json_decode($postJson, true);
        $input = array();
        foreach ($postArray as $post) {
            foreach ($post as $key => $p) {
                $input[$key] = urldecode($p);
            }
        }

        // echo "<pre>"; print_r($input); die();
        //Session::put('active_tab', 'hotels');


        if (isset($input['ishalal']) && $input['ishalal'] == 1) {
            Session::put('active_tab', 'halal');
        } else {
            Session::put('active_tab', 'flightshotels');
        }


        $startdate = $input['departdate'];
        $returndate = $input['returndate'];

        //get country from IP
        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {

            $location = \Location::get($this->end_user_ip);
        }

        $country = $location->countryCode;

        //get user currency
        $countryInfo = Currencies::where('code', $location->countryCode)->first();

        $currencyCode = $location->countryCode;
        //$currency = $countryInfo['currency_code'];
        $ourCurrency = Config::get('ourcurrency');

        if (in_array($countryInfo['currency_code'], $ourCurrency)) {
            $currency= $countryInfo['currency_code'];
        } else {
            $currency= 'USD';
        }
        

        $input['countryName'] = (isset($location->countryName) && $location->countryName != '') ? $location->countryName : $countryInfo['name'];
        $input['user_country'] = (isset($location->countryName) && $location->countryName != '') ? $location->countryName : $countryInfo['name'];

        $selectedGuests = $input['roomsGuests'];


        if (isset($input['countryCode']) && !empty($input['countryCode'])) {
            $input['countryCode'] = $input['countryCode'];
            $input['city_name'] = $input['city_name'];
        } else {
            $searchCountry = Cities::where('CityName', 'like', '%' . $input['city_name'] . '%')->first();
            $input['countryCode'] = $searchCountry['CountryCode'];
            $input['city_name'] = $searchCountry['CityName'];
        }


        $date = Carbon::createFromDate($startdate);
        $now = Carbon::createFromDate($returndate);

        $noOfNights = $date->diffInDays($now);

        $roomguests = array();

        $input['NoOfNights'] = $noOfNights;

        $input['currency'] = $currency;

        $input['CheckInDate'] = str_replace("-", "/", $input['departdate']);

        $noOfRooms = $input['roomCount'];

        Session::put('hotelSearchInput', $input);

        $roomGuests = array();
        $total_guests = 0;


        for ($i = 1; $i <= $noOfRooms; $i++) {
            $childAges = array();

            if (isset($input['c' . $i]) && $input['c' . $i] > 0) {

                for ($ca = 1; $ca <= $input['c' . $i]; $ca++) {
                    array_push($childAges, $input['ca' . $ca . 'r' . $i]);
                    $input['ca' . $ca . 'r' . $i] = $input['ca' . $ca . 'r' . $i];
                }

                if (isset($childAges) && sizeof($childAges) > 0) {

                    array_push($roomGuests, array(
                        'NoOfAdults' => $input['a' . $i],
                        'NoOfChild' => $input['c' . $i],
                        'ChildAge' => $childAges
                    ));

                    $input['adultCountRoom' . $i] = $input['a' . $i];
                    $input['childCountRoom' . $i] = $input['c' . $i];

                    $total_guests = $total_guests + $input['a' . $i] + $input['c' . $i];
                } else {
                    array_push($roomGuests, array(
                        'NoOfAdults' => $input['a' . $i],
                        'NoOfChild' => $input['c' . $i],
                        'ChildAge' => null
                    ));

                    $input['adultCountRoom' . $i] = $input['a' . $i];
                    $input['childCountRoom' . $i] = $input['c' . $i];

                    $total_guests = $total_guests + $input['a' . $i] + $input['c' . $i];
                }
            } else {
                array_push($roomGuests, array(
                    'NoOfAdults' => (isset($input['a' . $i])) ? $input['a' . $i] : 0,
                    'NoOfChild' => 0,
                    'ChildAge' => null
                ));

                $input['adultCountRoom' . $i] = $input['a' . $i];
                $input['childCountRoom' . $i] = $input['c' . $i];

                $total_guests = $total_guests + $input['a' . $i] + $input['c' . $i];
            }
        }

        $input['roomsGuests'] = $input['roomCount'] . ' Rooms ' . $total_guests . ' Guests';
        Session::put('hotelSearchInput', $input);
        $this->api = new TBOHotelAPI();

        // echo  "<pre>"; print_r($input);  die();
        if (isset($input['Latitude']) && !empty($input['Latitude'])) {
            $postData = [
                "CheckInDate" => $input['CheckInDate'],
                "Latitude" => $input['Latitude'],
                "Longitude" => $input['Longitude'],
                "Radius" => $input['Radius'],
                "NoOfNights" => $input['NoOfNights'],
                "CountryCode" => $input['countryCode'],
                // "CityId" => $input['city_id'],
                "ResultCount" => null,
                "PreferredCurrency" => $currency,
                "GuestNationality" => $country,
                "NoOfRooms" => $noOfRooms,
                "RoomGuests" => $roomGuests,
                "MaxRating" => 5,
                "MinRating" => 2,
                "ReviewScore" => null,
                "IsTBOMapped" => true,
                "IsNearBySearchAllowed" => false,
                "EndUserIp" => $this->api->userIP,
                "TokenId" => $this->api->tokenId
            ];
        } else {
            $postData = [
                "CheckInDate" => $input['CheckInDate'],
                "NoOfNights" => $input['NoOfNights'],
                "CountryCode" => $input['countryCode'],
                "CityId" => $input['city_id'],
                "ResultCount" => null,
                "PreferredCurrency" => $currency,
                "GuestNationality" => $country,
                "NoOfRooms" => $noOfRooms,
                "RoomGuests" => $roomGuests,
                "MaxRating" => 5,
                "MinRating" => 2,
                "ReviewScore" => null,
                "IsTBOMapped" => true,
                "IsNearBySearchAllowed" => false,
                "EndUserIp" => $this->api->userIP,
                "TokenId" => $this->api->tokenId
            ];
        }

        $hotels_list = $this->api->hotelSearch($postData);


        $input['countryName'] = str_replace('+', ' ', $input['countryName']);
        $input['roomsGuests'] = str_replace('+', ' ', $input['roomsGuests']);
        $traceId = '';

        $commisioninis_currency = env('INR_FEES');
        // echo gettype($hotels_list['HotelSearchResult']['ResponseStatus']);
        // echo "<pre>"; print_r($hotels_list['HotelSearchResult']['ResponseStatus']); die();
        if (isset($hotels_list['HotelSearchResult']) && isset($hotels_list['HotelSearchResult']['ResponseStatus']) && $hotels_list['HotelSearchResult']['ResponseStatus'] == 1) {

            //  echo "<pre>"; print_r($hotels_list); //die();
            //set session expirey cookie
            $this->setCookie('hotel_session', time() + (60 * 13), 20);
            $this->setCookie('hotel_city', $input['city_id'], 20);

            $hotels = $hotels_list['HotelSearchResult']['HotelResults'];
            $traceId = $hotels_list['HotelSearchResult']['TraceId'];
            $results_hotels = array();
            $ameneties_array = array();
            $room_ameneties_array = array();
            $locations = array();

            $unrated = 0;
            $t_star = 0;
            $th_star = 0;
            $f_star = 0;
            $fi_star = 0;

            $unrated_t = 0;
            $tp_one = 0;
            $tp_one_h = 0;
            $tp_two = 0;
            $tp_two_h = 0;
            $tp_three = 0;
            $tp_three_h = 0;
            $tp_four = 0;
            $tp_four_h = 0;
            $tp_five = 0;

            $isHalal = 0;

            //get data for first 20 hotels
            foreach ($hotels as $h_key => $hotel) {


                if (isset($hotel['IsTBOMapped']) && $hotel['IsTBOMapped'] == true) {

                    if (isset($input['referral']) && $input['referral'] != '') {

                        $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request
                                    ->referral])
                                ->first();
                        if (isset($checkrefferal)) {

                            $commisioninis = env('INIS_VAL');
                        } else {
                            $commisioninis = env('INIS_VAL');
                        }

                        $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $hotel['HotelCode'] . "'");

                        if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                            $commisioninis = 10;
                        }
                    } else {

                        $commisioninis = env('INIS_VAL');
                    }

                    $inis_markup = (($commisioninis / 100) * $hotel['Price']['OfferedPriceRoundedOff']);
                    $price_with_markup = $inis_markup + $hotel['Price']['OfferedPriceRoundedOff'];
                    $taxes = ($commisioninis_currency / 100) * $price_with_markup;
                    $hotel['FinalPrice'] = round($inis_markup + $taxes + $hotel['Price']['OfferedPriceRoundedOff'],2);

                    if (isset($input['Latitude']) && !empty($input['Latitude'])) {
                        $hfilter['hotel_code'] = $hotel['HotelCode'];
                    } else {
                        $hfilter['hotel_code'] = $hotel['HotelCode'];
                        //$hfilter['city_id'] = $input['city_id'];
                    }

                        unset($hotel['room_amenities']);
                        unset($hotel['HotelName']);
                        unset($hotel['HotelCategory']);
                        unset($hotel['HotelDescription']);
                        unset($hotel['HotelPromotion']);
                        unset($hotel['HotelPolicy']);
                        unset($hotel['IsTBOMapped']);
                        unset($hotel['HotelAddress']);
                        unset($hotel['HotelContactNo']);
                        unset($hotel['HotelMap']);
                        unset($hotel['Latitude']);
                        unset($hotel['Longitude']);
                        unset($hotel['HotelLocation']);
                        unset($hotel['SupplierPrice']);
                        unset($hotel['RoomDetails']);
                        unset($hotel['Price']['GST']);
                        unset($hotel['Price']['RoomPrice']);
                        unset($hotel['Price']['Tax']);
                        unset($hotel['Price']['ExtraGuestCharge']);
                        unset($hotel['Price']['ChildCharge']);
                        unset($hotel['Price']['OtherCharges']);
                        unset($hotel['Price']['Discount']);
                        unset($hotel['Price']['AgentCommission']);
                        unset($hotel['Price']['AgentMarkUp']);
                        unset($hotel['Price']['ServiceTax']);
                        unset($hotel['Price']['TCS']);
                        unset($hotel['Price']['TDS']);
                        unset($hotel['Price']['ServiceCharge']);
                        unset($hotel['Price']['TotalGSTAmount']);

                        array_push($results_hotels, array(
                            'TBO_data' => $hotel,
                            'static_data' => array('hotel_location' => array(), 'hotel_images' => array(), 'start_rating' => 0, 'hotel_facilities' => array(), 'hotel_address' => array(), 'hotel_name' => '', 'id' => '', 'ishalal' => 'no', 'tp_ratings' => 0, 'hotel_info'  => array(), 'room_amenities'  => array())
                        ));

                        

                        $hotel['h_rating'] = isset($hotel['StarRating']) ? (int) $hotel['StarRating'] : 0;
                   // }
                }
            }

        
            $counter = 1;
            $array_send = array();

            $request->session()->forget('hotels_list');
            Session::put('hotels_list', serialize($results_hotels));

            foreach ($results_hotels as $h_key => $hotel) {
                array_push($array_send, $hotel);

                if ($counter >= 20) {
                    break;
                }

                $counter++;
            }

            //Session::put('hotels_counter', $counter);

            if ($input['referral'] != '') {

                $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();
                if (isset($checkrefferal)) {

                    $commisioninis = env('INIS_VAL');
                } else {

                    $commisioninis = env('INIS_VAL');
                }
            } else {

                $commisioninis = env('INIS_VAL');
            }


            return response()->json(array(
                        'hotels' => $array_send,
                        'input_data' => $input,
                        'status' => (sizeof($array_send) > 0) ? true : false,
                        'hotel_count' => sizeof($results_hotels),
                        'ameneties_array' => $ameneties_array,
                        'room_ameneties_array' => $room_ameneties_array,
                        'locations' => $locations,
                        'traceId' => $traceId,
                        'commission_inis' => $commisioninis,
                        'referral' => $input['referral']
            ));
        } else {

            $this->writeLogs($postData, $hotels_list['HotelSearchResult']);
            return response()->json(array(
                        'hotels' => $hotels_list,
                        'input_data' => $input,
                        'status' => false,
                        'hotel_count' => 0,
                        'traceId' => $traceId
            ));
        }
    }


    public function sendFlightsHotelsEmail(Request $request) {

        $search_id = $request->search_id;
        $search_contents = json_decode($this->readSearchDataHotel($search_id.'.json'), true);
        $searchData = $search_contents['request'];
        $hotels = array();
        $selected_hotels = $request->hotel;

        $flights1 = $request->flights1;
        $flights2 = $request->flights2;
        $url = $request->url;

        //echo "<pre>";print_r($flights1);die;

        $hotel_list = $search_contents['response'];
        $hotel_found = array();
        foreach ($selected_hotels as $s_hotel) {

            foreach ($hotel_list as $key => $hotel) {
                if ($hotel['TBO_data']['HotelCode'] == $s_hotel) {
                    $hotel_found = $hotel;
                }
            }

            array_push($hotels, $hotel_found);
        }

        $agent = AffiliateUsers::select('referal_code')->where('user_id', Auth::user()->id)->first();
        Mail::to($request->email)->send(new FlightsHotelsEmail($searchData, $hotels, $agent, $flights1, $flights2, $url));

        return response()->json(array(
                    'message' => "Email sent",
                    'success' => true
        ));
    }

    /* For Flights */
    public function saveSearchData($file, $content) {
        $destinationPath=public_path()."/logs/searches/flights/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function readSearchData($file) {
        $destinationPath=public_path()."/logs/searches/flights/";
        return $file = File::get($destinationPath.$file);
    }

    /* For Hotles */

    public function validateDate($date, $format = 'dd/mm/Y') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function saveSearchDataHotel($file, $content) {
        $destinationPath=public_path()."/logs/searches/hotels/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function readSearchDataHotel($file) {
        $destinationPath=public_path()."/logs/searches/hotels/";
        return $file = File::get($destinationPath.$file);
    }

    public function saveBlockRoomData($file, $content) {
        $destinationPath=public_path()."/logs/searches/hotels/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function getBlockRoomData($file) {
        $destinationPath=public_path()."/logs/searches/hotels/";
        return $file = File::get($destinationPath.$file);
    }

    public function readSearchDataWithPath($destinationPath) {
     return File::get($destinationPath);   
    }

}