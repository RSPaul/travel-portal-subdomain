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
use App\Models\User;
use App\Models\Currencies;
use App\Models\Cities;
use App\Models\StaticDataHotels;
use App\Models\RoomImages;
use Currency;
use Log;
use Config;

class HotelStaticController extends Controller {

    
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

        $location = \Location::get($this->end_user_ip);
        Session::put('active_tab', 'hotels');

    }

    public function discoverCountry(Request $request) {

        $referral = $request->referral;

        if (isset($request->show)) {
            $active_tab = $request->show;
            Session::put('active_tab', $active_tab);
        } else {
            Session::put('active_tab', 'flights');
        }

        $countries = DB::select("SELECT DISTINCT `CityName`, `CountryCode`, `Country`, `CityId` , `image` FROM `cities` WHERE isFeatured = '1' AND `CountryCode` = '" . $request->country . "' ");
        $country_list = array();
        foreach ($countries as $key => $country) {

            $hotel = StaticDataHotels::select('hotel_images', 'hotel_name', 'hotel_code', 'id')
                    ->where(['city_id' => $country->CityId, 'start_rating' => '5'])
                    ->first();

            $count = StaticDataHotels::where(['city_id' => $country->CityId])->count();

            if (isset($hotel) && isset($hotel['hotel_images'])) {
                $hotel['hotel_images'] = json_decode($hotel['hotel_images'], true);
            }
            array_push($country_list, array('CityName' => $country->CityName,
                'CityId' => $country->CityId,
                'CountryCode' => $country->CountryCode,
                'Country' => $country->Country,
                'image' => $country->image,
                'hotel' => $hotel,
                'count' => $count));
        }

        $other_cities = Cities::select('CityName', 'CityId', 'CountryCode', 'Country')
                        ->where('CountryCode', $request->country)->get();
        $other_cities_array = array();
        foreach ($other_cities as $key => $city) {

            $count = StaticDataHotels::where(['city_id' => $city['CityId']])->count();
            if ($count > 0) {
                array_push($other_cities_array, array('CityName' => $city['CityName'],
                    'CityId' => $city->CityId,
                    'CountryCode' => $city->CountryCode,
                    'Country' => $city->Country,
                    'hotels' => $count));
            }
        }

        $selected_country = Cities::select('Country', 'CityName')
                        ->where('CountryCode', $request->country)->first();

        $title = $selected_country['Country'].' - Top Destinations';
        return view('discover.top-hotels')->with(['referral' => $referral, 'countries' => $country_list, 'other_cities' => $other_cities_array, 'selected_country' => $selected_country, 'title' => $title, 'selected_country' => $selected_country]);
    }

    public function discoverMoreCountry(Request $request) {

        $referral = $request->referral;

        if (isset($request->show)) {
            $active_tab = $request->show;
            Session::put('active_tab', $active_tab);
        } else {
            Session::put('active_tab', 'flights');
        }

        $countries = Cities::select('CityId', 'CityName', 'Country', 'CountryCode', 'image')
                ->where('isFeatured', '1')
                ->where('image', '<>', null)
                ->orderBy('CityName', 'ASC')
                ->get();

        $country_list = array();
        $citiesid = array();
        foreach ($countries as $key => $country) {
            $found = false;
            foreach ($country_list as $c_key => $c_value) {
                if ($c_value['CountryCode'] == $country->CountryCode) {
                    $found = true;
                }
            }

            $top_cities = array();
            $top_hotels = array();
            if (!$found) {

                //get all cities
                $all_cities = Cities::select('CityId')
                        ->where('CountryCode', $country->CountryCode)
                        ->get();

                $city_ids = array();
                foreach ($all_cities as $key => $city_id) {
                    array_push($city_ids, $city_id['CityId']);
                }

                $country_hotels = StaticDataHotels::whereIn('city_id', $city_ids)->count();
                // echo "SELECT DISTINCT `CityName`, `CityId` , `CountryCode`, `Country` FROM `cities` WHERE isFeatured = '1' AND `CountryCode` = '" . $country->CountryCode . "' LIMIT 2 "; die();
                $city = DB::select("SELECT DISTINCT `CityName`, `CityId` , `CountryCode`, `Country` FROM `cities` WHERE isFeatured = '1' AND `CountryCode` = '" . $country->CountryCode . "' LIMIT 2 ");

                foreach ($city as $key => $c) {
                    //get total hotels
                    $city_hotels = StaticDataHotels::where(['city_id' => $c->CityId])->count();
                    $c->hotels = $city_hotels;
                    array_push($top_cities, $c);
                    $hotel = StaticDataHotels::select('hotel_name', 'hotel_code', 'city_id')
                            ->where(['start_rating' => '5', 'city_id' => $c->CityId])
                            ->first();

                   
                    if(isset($hotel) && !empty($hotel)) {
                        array_push($top_hotels, array('city_name' => $c->CityName,
                            'hotel_name' => $hotel['hotel_name'],
                            'city_id' => $hotel['city_id'],
                            'hotel_code' => $hotel['hotel_code'],
                            'country_code' => $c->CountryCode,
                            'country_name' => $c->Country));
                    }
                }


                
                array_push($country_list, array('Country' => $country->Country,
                    'CountryCode' => $country->CountryCode, 'image' => $country->image, 'top_cities' => $top_cities, 'top_hotels' => $top_hotels, 'country_hotels' => $country_hotels));
            }
        }


        return view('discover.top-cities')->with(['referral' => $referral, 'countries' => $country_list, 'title' => 'Top Destinations']);
    }

    public function searchHotels(Request $request) {
        
        $city_id = $request->city_id;
        $country = $request->country;
        $city = $request->city;
        $hotels = StaticDataHotels::where(['city_id' => $city_id])->limit(100)->orderBy('start_rating', 'DESC')->get();
        $city = Cities::where('CityId', $city_id)->first();
        $results = array();
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

        foreach($hotels as $hotel) {

            if (isset($hotel['hotel_images']) && !empty($hotel['hotel_images'])) {

                $hotel['hotel_images'] = json_decode($hotel['hotel_images'], true);
                if (isset($hotel['hotel_images']) && !empty($hotel['hotel_images'][0])) {

                    $hotel['hotel_images'] = $hotel['hotel_images'][0];
                }else{
                    $hotel['hotel_images'] = 'https://via.placeholder.com/250X150?text=Image%20Not%20Available';
                }
            }

            if (isset($hotel['hotel_facilities']) && !empty($hotel['hotel_facilities'])) {
                $hotel['hotel_facilities'] = json_decode($hotel['hotel_facilities'], true);

                if (isset($hotel['hotel_facilities']) && !empty($hotel['hotel_facilities'])) {
                    $tmp_arr = array();
                    foreach ($hotel['hotel_facilities'] as $key => $fac) {
                        if ($key <= 4) {
                            array_push($tmp_arr, $fac);
                            if(!in_array($fac, $ameneties_array)) {
                                array_push($ameneties_array, $fac);
                            }
                        }

                    }

                    $hotel['hotel_facilities'] = $tmp_arr;
                }
            }

            if (isset($hotel['room_amenities']) && !empty($hotel['room_amenities'])) {
                $hotel['room_amenities'] = json_decode($hotel['room_amenities']);

                if (isset($hotel['room_amenities']) && !empty($hotel['room_amenities'])) {
                    $tmp_arr = array();
                    foreach ($hotel['room_amenities'] as $key => $r_amn) {
                        if ($key <= 4) {
                            if(!in_array($r_amn, $room_ameneties_array)) {
                                array_push($room_ameneties_array, $r_amn);
                            }
                        }

                    }
                }
            }


            if (isset($hotel['hotel_address']) && !empty($hotel['hotel_address'])) {
                $hotel['hotel_address'] = json_decode($hotel['hotel_address'], true);
                if(isset($hotel['hotel_address']['CityName']) && $hotel['hotel_address']['CityName'] != '') {
                    if(sizeof($locations) > 0) {
                        $check_loc = false;
                        foreach ($locations as $key => $loc) {
                            if(strtolower(str_replace("-", " ", $loc['name'])) == strtolower(str_replace("-", " ", $hotel['hotel_address']['CityName']))) {
                                $locations[$key]['hotels'] = $locations[$key]['hotels'] + 1;
                                $check_loc = true;
                            }
                        }

                        if(!$check_loc) {
                            array_push($locations, array('name' => $hotel['hotel_address']['CityName'], 'hotels' => 1));
                        }
                    } else {
                        array_push($locations, array('name' => $hotel['hotel_address']['CityName'], 'hotels' => 1));
                    }
                }
            }

            if (isset($hotel['hotel_location']) && !empty($hotel['hotel_location'])) {
                $hotel['hotel_location'] = json_decode($hotel['hotel_location'], true);
            }

            if ($hotel['ishalal'] == 'yes') {
                $isHalal++;
            }

            if ($hotel['start_rating'] == 0) {

                $unrated++;
            }

            if ($hotel['start_rating'] == 2) {

                $t_star++;
            }

            if ($hotel['start_rating'] == 3) {

                $th_star++;
            }

            if ($hotel['start_rating'] == 4) {

                $f_star++;
            }

            if ($hotel['start_rating'] == 5) {

                $fi_star++;
            }

            if($hotel['tp_ratings'] == '0.0') {
                $unrated_t++;
            }
            if($hotel['tp_ratings'] == '1.0') {
                $tp_one++;
            }
            if($hotel['tp_ratings'] == '1.5') {
                $tp_one_h++;
            }
            if($hotel['tp_ratings'] == '2.0') {
                $tp_two++;
            }
            if($hotel['tp_ratings'] == '2.5') {
                $tp_two_h++;
            }
            if($hotel['tp_ratings'] == '3.0') {
                $tp_three++;
            }
            if($hotel['tp_ratings'] == '3.5') {
                $tp_three_h++;
            }
            if($hotel['tp_ratings'] == '4.0') {
                $tp_four++;
            }
            if($hotel['tp_ratings'] == '4.5') {
                $tp_four_h++;
            }
            if($hotel['tp_ratings'] == '5.0') {
                $tp_five++;
            }

            array_push($results, $hotel);
        }

        $filters_data = array('isHalal' => $isHalal, 'unrated' => $unrated, 't_star' => $t_star, 'th_star' => $th_star, 'f_star' => $f_star, 'fi_star' =>$fi_star, 'unrated_t' => $unrated_t, 'tp_one' => $tp_one, 'tp_one_h' => $tp_one_h, 'tp_two' => $tp_two, 'tp_two_h' => $tp_two_h, 'tp_three' => $tp_three, 'tp_three_h' => $tp_three_h, 'tp_four' => $tp_four, 'tp_four_h' => $tp_four_h, 'tp_five' => $tp_five, 'r_amenities' => $room_ameneties_array, 'locations' => $locations, 'h_amenities' => $ameneties_array);

        // echo "<pre>"; print_r($filters_data); die();
        $title = ' Top Hotel In '. $city['CityName'] .' 2021';
        return view('search.hotels.no-price')->with(['hotels' => $results, 'city' => $city, 'title' => $title, 'filters_data' => $filters_data]);
    }

    public function viewHotel(Request $request) {
        $hotel_name = $request->hotel_name;
        $country = $request->country;
        $city = $request->city;
        $hotel_code = $request->hotel_code;
        $referral = $request->referral;
        $hotel = StaticDataHotels::where(['hotel_code' => $hotel_code])->first();
        $rooms = RoomImages::where('sub_domain', $hotel_code)->get();
        $city = Cities::where('CityId', $hotel['city_id'])->first();
        $title = $hotel['hotel_name'].' - '. $city['CityName'] .' - ' . $city['Country'];

        if (isset($hotel['hotel_images']) && !empty($hotel['hotel_images'])) {
            $hotel['hotel_images'] = json_decode($hotel['hotel_images'],true);
        }

        if (isset($hotel['hotel_facilities']) && !empty($hotel['hotel_facilities'])) {
            $hotel['hotel_facilities'] = json_decode($hotel['hotel_facilities'],true);
        }

        if (isset($hotel['attractions']) && !empty($hotel['attractions'])) {
            $hotel['attractions'] = json_decode($hotel['attractions'],true);
        }

        if (isset($hotel['hotel_description']) && !empty($hotel['hotel_description'])) {
            $hotel['hotel_description'] = json_decode($hotel['hotel_description'],true);
        }

        if (isset($hotel['hotel_location']) && !empty($hotel['hotel_location'])) {
            $hotel['hotel_location'] = json_decode($hotel['hotel_location'],true);
        }

        if (isset($hotel['hotel_address']) && !empty($hotel['hotel_address'])) {
            $hotel['hotel_address'] = json_decode($hotel['hotel_address'],true);
        }

        if (isset($hotel['hotel_contact']) && !empty($hotel['hotel_contact'])) {
            $hotel['hotel_contact'] = json_decode($hotel['hotel_contact'],true);
        }

        if (isset($hotel['hotel_time']) && !empty($hotel['hotel_time'])) {
            $hotel['hotel_time'] = json_decode($hotel['hotel_time'],true);
        }

        if (isset($hotel['hotel_type']) && !empty($hotel['hotel_type'])) {
            $hotel['hotel_type'] = json_decode($hotel['hotel_type'],true);
        }

        if (isset($hotel['hotel_info']) && !empty($hotel['hotel_info'])) {
            $hotel['hotel_info'] = json_decode($hotel['hotel_info'],true);
        }

        $meta_image  = (isset($hotel['hotel_images']) && !empty($hotel['hotel_images'])) ? $hotel['hotel_images'][0] : 'https://tripheist.com/images/logo.png';
        return view('search.hotels.view-no-price')->with(['static_data' => $hotel, 'city' => $city, 'title' => $title, 'rooms' => $rooms, 'meta_image' => $meta_image, 'referral' => $referral]);
    }
}