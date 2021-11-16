<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\AirCities;
use App\Models\Currencies;
use App\Models\Cities;
use App\Models\RoomImages;
use App\Models\StaticDataHotels;
use App\Models\TransferCities;
use DB;
use Session;
use App\Services\TBOHotelAPI;
use App\Mail\TestEmail;
use DateTime;
use File;
use App\Models\User;
use App\Models\Lottery;
use App\Models\LotteryUsers;
use App\Models\AffiliateUsers;
use App\Models\HotelInfos;
use App\Models\MetaTags;
use Illuminate\Support\Facades\Auth;
use App\Mail\LotteryWonEmail;
use Currency;
use GuzzleHttp;
use GuzzleHttp\Client;

class HomeController extends Controller {

    //
    public $end_user_ip;
    private $sub_domain;
    private $domainData;

    public function __construct(Request $request) {
        // ini_set('max_execution_time', 1000);
        // $this->api = new TBOHotelAPI();
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? \Request::ip() ?? '3.64.135.96';
        if ($ip == '127.0.0.1') {

            $ip = '132.154.175.244'; //'93.173.228.94'; US = 52.186.25.21 IN = 132.154.175.244 Venuzula = 190.94.234.7 IL = 188.120.129.143
        }

        list($subdomain) = explode('.', $request->getHost(), 2);     
        if($subdomain == 'www') {
            $subdomain = explode('.', $request->getHost())[1];
        }
        $this->sub_domain = $subdomain;
        $this->domainData = User::where('domain', $this->sub_domain)->first();
        Session::put('domainData', $this->domainData);     

        if (count(explode(', ', $ip)) > 1) {
            $this->end_user_ip = explode(', ', $ip)[0];
        } else {
            $this->end_user_ip = $ip;
        }


        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {
            $location = \Location::get($this->end_user_ip);
        }

        if ($location->countryCode == 'IL') {

            if(isset($_COOKIE['locale_changed'])) {

                if($_COOKIE['locale_changed'] == 'heb') {

                    Session::put('locale', 'heb');
                }

            } else {
                
                if(Session::get('locale') != 'heb'){

                    Session::put('locale', 'heb');
                }
            }

        }else{
            
            // if(Session::get('locale') != 'en'){
            //     Session::put('locale', 'en');
            // }

        }
        
    }

    
    public function setUserCurrency(Request $request) {
        
        
        
        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {

            $lottery_Limit = env('LOTTERY_ELIGIBILITY');
            
            $location = json_decode($this->getCookie('th_country'));

            
            $countryInfo = Currencies::where('code', $location->countryCode)->first();
            
            $myCurrency = $countryInfo['currency_code']; 
            
            Session::put('CurrencyCode', $myCurrency);

            if (!empty(trim($myCurrency)) && (empty(Session::get('lotteryLimit')) || Session::get('lotteryLimit') !='' || !Session::get('lotteryLimit'))) {
            
                $lAmount = Currency::convert('USD', $myCurrency, $lottery_Limit);

                if (isset($lAmount) && isset($lAmount['convertedAmount'])) {


                    $LotteryLimit = round($lAmount['convertedAmount']);
                    Session::put('lotteryLimit', $LotteryLimit);
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
                $this->setCookie2('th_country',  json_encode($location), time()+60*60*24*10);
                $myCurrency = $countryInfo['currency_code']; //Session::get('CurrencyCode');
                

            } else {
                Session::put('CurrencyCode', 'USD');
                $this->setCookie2('th_country',  'US', time()+60*60*24*10);
                $myCurrency = 'USD';
                
            }

            $lottery_Limit = env('LOTTERY_ELIGIBILITY');

            if (!empty(trim($myCurrency))) {
                    
                $lAmount = Currency::convert('USD', $myCurrency, $lottery_Limit);
                if (isset($lAmount) && isset($lAmount['convertedAmount'])) {

                    $LotteryLimit = round($lAmount['convertedAmount']);
                    Session::put('lotteryLimit', $LotteryLimit);
                } else {
                    Session::put('lotteryLimit', $lottery_Limit);
                }
            } else {
                Session::put('lotteryLimit', $lottery_Limit);
            }
        }

        Session::put('CurrencyCode', $myCurrency);

        $myCity = TransferCities::where('country_code', $location->countryCode)->where('city_name', 'like', '%' . $location->cityName . '%')->first();
        if (!$myCity) {
            $myCity = TransferCities::where('country_code', $location->countryCode)->first();
        }

        
        if (isset($myCity) && !empty($myCity)) {

            $user_city = ['countryName' => $location->countryName, 'cityName' => $myCity['city_name'], 'cityCode' => $myCity['city_code'], 'countryCode' => $myCity['country_code']];
        } else {
            $user_city = ['countryName' => $location->countryName, 'cityName' => '', 'cityCode' => '', 'countryCode' => $location->countryCode];
        }
        
        return response()->json($user_city);
    }

    function getAirport() {

        $location = \Location::get($this->end_user_ip);

        if (isset($location->countryCode)) {
            $myCity = TransferCities::where('country_code', $location->countryCode)->where('city_name', 'like', '%' . $location->cityName . '%')->first();
            if (!$myCity) {
                $myCity = TransferCities::where('country_code', $location->countryCode)->first();
            }
        }

        if (isset($myCity) && !empty($myCity)) {

            $user_city = ['countryName' => $location->countryName, 'cityName' => $myCity['city_name'], 'cityCode' => $myCity['city_code'], 'countryCode' => $myCity['country_code']];
        } else {
            $user_city = ['countryName' => $location->countryName, 'cityName' => '', 'cityCode' => '', 'countryCode' => $location->countryCode];
        }


        Session::put('user_city', $user_city);
    }

    public function index(Request $request) {

        return view('home');
    }

    public function comingSoon() {

        $search_id = 'weekend_images';

        $destinationPath=public_path()."/logs/searches/weekend_image/" . $search_id . '.json';
        if (file_exists($destinationPath)){
            $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);

            if(isset($search_contents['web_image'])){
                    
                $saveImage['web_image'] = $search_contents['web_image'];

            }else{

                $saveImage['web_image'] = '';
            }

            if(isset($search_contents['mobile_image'])){
                
                $saveImage['mobile_image'] = $search_contents['mobile_image'];

            }else{

                $saveImage['mobile_image'] = '';
            }
        }else{

            $saveImage['web_image'] = '';
            $saveImage['mobile_image'] = '';

        }

        return view('coming-soon')->with(['deals_off' => false, 'weekend_images' => $saveImage]);
    }

    public function readSearchData($file) {
        $destinationPath=public_path()."/logs/searches/weekend_image/";
        return $file = File::get($destinationPath.$file);
    }

    public function barcode(Request $request) {
        return view('barcode')->with(['no' => $request->no]);
    }

    public function landing(Request $request) {

        $isILS = false;
       // $this->getAirport();

        $referral = $request->referral;
        // if(Auth::user()) {
        //     $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();
        //     if(isset($agent) && !empty($agent)) {
        //         $referral = $agent['referal_code'];
        //     }
        // }

        if (isset($request->show)) {
            $active_tab = $request->show;
            Session::put('active_tab', $active_tab);
        } else {
            Session::put('active_tab', 'hotels');
        }

        $country_list = array();
        
        $uscities = Cities::select('CityId', 'CityName', 'Country', 'CountryCode')
                ->where('CountryCode','US')
                ->orderBy('CityName', 'ASC')
                ->get();
        
   
        //get country from IP
        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {
            $location = \Location::get($this->end_user_ip);
        }

        $this->domainData = User::where('domain', $this->sub_domain)->first();
        $this->hotelCode = $this->domainData['hotel_code'];
        
        Session::put('domainData', $this->domainData);
        $country = $this->domainData['country'];
        Session::put('currency', $this->domainData['currency']);
        Session::put('country', $country);
        Session::put('selectedGuests', 'Rooms & Guests');
        
        $hotelDetails = HotelInfos::where('sub_domain', $this->sub_domain)->first();
        $hotelDetails['slider_images'] = unserialize($hotelDetails['slider_images']);
       
        $tags = MetaTags::where('sub_domain', $this->sub_domain)->first();
        $static_data = StaticDataHotels::where(['hotel_code' => $this->domainData['hotel_code']])->first();

        $location = \Location::get($this->end_user_ip);
        //get user currency
        $currencyCode = Currencies::where('code', $location->countryCode)->first();
       
        if(isset($static_data)) {
                
          $hotel['h_rating'] = ($static_data['start_rating'] != null) ? (int) $static_data['start_rating'] : 0;

          if(isset($static_data['hotel_images']) && !empty($static_data['hotel_images'])) {
            $static_data['hotel_images'] = json_decode($static_data['hotel_images']);
          }

          if(isset($static_data['hotel_facilities']) && !empty($static_data['hotel_facilities'])) {
            $static_data['hotel_facilities'] = json_decode($static_data['hotel_facilities']);
          }

          if(isset($static_data['attractions']) && !empty($static_data['attractions'])) {
            $static_data['attractions'] = json_decode($static_data['attractions']);
          }

          if(isset($static_data['hotel_description']) && !empty($static_data['hotel_description'])) {
            $static_data['hotel_description'] = json_decode($static_data['hotel_description']);
          }

          if(isset($static_data['hotel_location']) && !empty($static_data['hotel_location'])) {
            $static_data['hotel_location'] = json_decode($static_data['hotel_location']);
          }

          if(isset($static_data['hotel_address']) && !empty($static_data['hotel_address'])) {
            $static_data['hotel_address'] = json_decode($static_data['hotel_address']);
          }

          if(isset($static_data['hotel_contact']) && !empty($static_data['hotel_contact'])) {
            $static_data['hotel_contact'] = json_decode($static_data['hotel_contact']);
          }

          if(isset($static_data['hotel_time']) && !empty($static_data['hotel_time'])) {
            $static_data['hotel_time'] = json_decode($static_data['hotel_time']);
          }

          if(isset($static_data['hotel_type']) && !empty($static_data['hotel_type'])) {
            $static_data['hotel_type'] = json_decode($static_data['hotel_type'], true);
          }

        }

        $countryDetails = Cities::where('CityId', $static_data['city_id'])->first();
        if (isset($location) && isset($location->countryCode)) {

            if ($location->countryCode == 'IL') {
                $isILS = true;
                date_default_timezone_set("Israel");
                $date = date('Y-m-d H:i:s');

                $search_id = 'weekend_images';
                $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);

                if(isset($search_contents['banner_time'])) {
                    $search_id = 'weekend_images';
                    $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
                    $now = date('Y-m-d H:i:s', strtotime($search_contents['banner_time']));
                } else {
                    $now = date('Y-m-d H:i:s', strtotime('2021-04-04 21:30:00'));
                }

                if ($date > $now || $search_contents['coming_soon_mode'] == '0') {

                    return view('landing')->with(['referral' => $referral, 'cities' => '', 'countries' => $country_list, 'isILS' => $isILS, 'static_data' => $static_data, 'countryDetails' => $countryDetails]);

                } else {

                    

                    $destinationPath=public_path()."/logs/searches/weekend_image/" . $search_id . '.json';

                    if (file_exists($destinationPath)){

                        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);

                        if(isset($search_contents['web_image'])){
                                
                            $saveImage['web_image'] = $search_contents['web_image'];

                        }else{

                            $saveImage['web_image'] = '';
                        }

                        if(isset($search_contents['mobile_image'])){
                            
                            $saveImage['mobile_image'] = $search_contents['mobile_image'];

                        }else{

                            $saveImage['mobile_image'] = '';
                        }
                    }else{

                        $saveImage['mobile_image'] = '';
                        $saveImage['web_image'] = '';

                    }

                    return view('coming-soon')->with(['deals_off' => true, 'weekend_images' => $saveImage, 'static_data' => $static_data, 'countryDetails' => $countryDetails]);
                }
            } else {
                return view('landing')->with(['referral' => $referral, 'cities' => '','uscities'=>$uscities, 'countries' => $country_list, 'isILS' => $isILS, 'static_data' => $static_data, 'countryDetails' => $countryDetails]);
            }
        } else {
            return view('landing')->with(['referral' => $referral, 'cities' => '','uscities'=>$uscities,  'countries' => $country_list, 'isILS' => $isILS, 'static_data' => $static_data, 'countryDetails' => $countryDetails]);
        }
    }

    public function affiliateLanding(Request $request) {

        $isILS = false;
       // $this->getAirport();

        $referral = $request->referral;
        if(Auth::user()) {
            $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();
            if(isset($agent) && !empty($agent)) {
                $referral = $agent['referal_code'];
            }
        }

        if (isset($request->show)) {
            $active_tab = $request->show;
            Session::put('active_tab', $active_tab);
        } else {
            Session::put('active_tab', 'hotels');
        }

        $countries = Cities::select('CityId', 'CityName', 'Country', 'CountryCode', 'image')
                ->where('isFeatured', '1')
                ->where('image', '<>', null)
                ->orderBy('CityName', 'ASC')
                ->get(); //DB::select("SELECT DISTINCT `Country`, `CountryCode` , `image` FROM `cities` WHERE isFeatured = '1' ");
        $country_list = array();
        foreach ($countries as $key => $country) {
            $found = false;
            foreach ($country_list as $c_key => $c_value) {
                if ($c_value['CountryCode'] == $country->CountryCode) {
                    $found = true;
                }
            }

            if (!$found) {
                array_push($country_list, array('Country' => $country->Country,
                    'CountryCode' => $country->CountryCode, 'image' => $country->image));
            }
        }
        
        $uscities = Cities::select('CityId', 'CityName', 'Country', 'CountryCode')
                ->where('CountryCode','US')
                ->orderBy('CityName', 'ASC')
                ->get();
        
   
        //get country from IP
        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {
            $location = \Location::get($this->end_user_ip);
        }



        return view('landing')->with(['referral' => $referral, 'cities' => '','uscities'=>$uscities, 'countries' => $country_list, 'isILS' => $isILS]);
    }

    public function hotelByCity(Request $request) {


       // $this->getAirport();

        $referral = $request->referral;
        if(Auth::user()) {
            $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();
            if(isset($agent) && !empty($agent)) {
                $referral = $agent['referal_code'];
            }
        }

        if (isset($request->show)) {
            $active_tab = $request->show;
            Session::put('active_tab', $active_tab);
        } else {
            Session::put('active_tab', 'hotels');
        }

        $countries = Cities::select('CityId', 'CityName', 'Country', 'CountryCode', 'image')
                ->where('isFeatured', '1')
                ->where('image', '<>', null)
                ->orderBy('CityName', 'ASC')
                ->get(); //DB::select("SELECT DISTINCT `Country`, `CountryCode` , `image` FROM `cities` WHERE isFeatured = '1' ");
        $country_list = array();
        foreach ($countries as $key => $country) {
            $found = false;
            foreach ($country_list as $c_key => $c_value) {
                if ($c_value['CountryCode'] == $country->CountryCode) {
                    $found = true;
                }
            }

            if (!$found) {
                array_push($country_list, array('Country' => $country->Country,
                    'CountryCode' => $country->CountryCode, 'image' => $country->image));
            }
        }

        //Paris = 131408, Dubai = 115936, Amsterdam = 109558, LA = 125908, Prague = 131864, Tel Aviv = 139939
        $cities = Cities::select('CityId', 'CityName', 'Country', 'CountryCode')
                ->whereIn('CityId', ['131408', '115936', '109558', '125908', '131864', '139939', '116760', '119805', '113162', '133186'])
                ->orderBy('CityName', 'ASC')
                ->get();
        
        
        //get country from IP
        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {
            $location = \Location::get($this->end_user_ip);
        }

        if (isset($location) && isset($location->countryCode)) {

            if ($location->countryCode == 'IL') {
                date_default_timezone_set("Israel");
                $date = date('Y-m-d H:i:s');
                $now = date('Y-m-d H:i:s', strtotime('2021-04-04 21:30:00'));
                if ($date > $now) {
                    return view('search.hotels.search-by-city')->with(['referral' => $referral, 'cities' => $cities, 'countries' => $country_list]);
                } else {
                    return view('coming-soon')->with(['deals_off' => true]);
                }
            } else {
                return view('search.hotels.search-by-city')->with(['referral' => $referral, 'cities' => $cities, 'countries' => $country_list]);
            }
        } else {
            return view('search.hotels.search-by-city')->with(['referral' => $referral, 'cities' => $cities, 'countries' => $country_list]);
        }
    }

    
    public function home(Request $request) {


        $referral = $request->referral;
        if (isset($request->show)) {
            $active_tab = $request->show;
            Session::put('active_tab', $active_tab);
        } else {
            Session::put('active_tab', 'flights');
        }
        //Paris = 131408, Dubai = 115936, Amsterdam = 109558, LA = 125908, Prague = 131864, Tel Aviv = 139939
        $cities = Cities::select('CityId', 'CityName', 'Country', 'CountryCode')
                ->whereIn('CityId', ['131408', '115936', '109558', '125908', '131864', '139939', '139939', '116760', '119805', '113162', '133186'])
                ->orderBy('CityName', 'ASC')
                ->get();
        return view('home')->with(['referral' => $referral, 'cities' => $cities]);
    }

    public function lottery() {

//        $user = User::first();
//        echo"<pre>";
//        foreach($user->transactions as $row){
//          print_r($row);
//        }
//        die;

        $lottery = Lottery::where(['lotteryStatus' => 'active'])->first();

        $isRegistered = false;
        if (!Auth::guest()) {
            $check = LotteryUsers::where(['lotteryID' => $lottery['lotteryID'], 'userID' => Auth::user()->id])->first();
            if ($check) {
                $isRegistered = true;
                Session::flash('success', 'You are successfully enrolled for lottery system.');
            }
        }


        return view('lottery', ['entryFees' => $lottery->entryFees, 'feeCurrency' => $lottery->feeCurrency, 'lotteryID' => $lottery->lotteryID, 'isRegistered' => $isRegistered]);
    }

    public function joinLottery(Request $request) {

        $inputs = $request->all();

        $result = LotteryUsers::create(['paidAmount' => $inputs['lottery_fee'], 'paymentSignature' => $inputs['razorpay_signature'], 'paymentID' => $inputs['razorpay_payment_id'], 'paymentStatus' => 'success', 'userID' => Auth::user()->id, 'lotteryID' => $inputs['lottery_id']]);
        if (!$result) {
            return redirect()->back()->with('error', 'Something went wrong , please try again!');
        } else {
            $user = User::first();
            $user->deposit($inputs['lottery_fee'], ["description" => 'Lottery Participation Fees']);
            return redirect()->back()->with('success', 'You are successfully registered for lottery participant.');
        }
    }

    public function privacyPolicy() {

        //if(Session::get('locale') == 'heb'){
         //   return view('public-pages.privacy-policy-heb');
        //}else{
            return view('public-pages.privacy-policy');
       // }
    }

    public function refundPolicy() {
        return view('public-pages.refund-policy');
    }

    public function termsConditions() {

        //if(Session::get('locale') == 'heb'){
        //    return view('public-pages.terms-conditions-heb');
        //}else{
            return view('public-pages.terms-conditions');
       // }
    }

    public function aboutUs() {
        return view('public-pages.about');
    }

    public function contactUs() {
        return view('public-pages.contact');
    }

    public function getAirCities(Request $request) {

        $city_list = array();
        //get city air ports
        $transfer_cities = TransferCities::orWhere('airport_code', 'like', '%' . $request->term . '%')
                ->orWhere('city_code', 'like', '%' . $request->term . '%')
                ->orWhere('country_name', 'like', '%' . $request->term . '%')
                ->where('type', '1')
                ->get(array('type', 'city_name', 'city_code', 'country_code', 'airport_name'));

        foreach ($transfer_cities as $key => $t_city) {
            if ($t_city['type'] == "1") {

                array_push($city_list, array('label' => $t_city['city_name'],
                    'text' => $t_city['airport_name'] . ' (' . $t_city['city_code'] . ') ' . $t_city['country_code'],
                    'id' => $t_city['city_code'],
                    'value' => $t_city['airport_name'] . ' (' . $t_city['city_code'] . ') ' . $t_city['country_code'],
                    'country' => $t_city['country_code']
                    )
                );
            }
        }

        $cities = AirCities::distinct()->orWhere('cityName', 'like', '%' . $request->term . '%')
                ->get(array('cityName', 'cityCode', 'countryCode'));

        foreach ($cities as $key => $city) {
            array_push($city_list, array('label' => $city['cityName'],
                'text' => $city['cityName'] . ' (' . $city['cityCode'] . ') ' . $city['countryCode'],
                'id' => $city['cityCode'],
                'value' => $city['cityName'] . ' (' . $city['cityCode'] . ') ' . $city['countryCode'],
                'country' => $city['countryCode']
            )
            );
        }
        return response()->json($city_list);
    }

    public function halalCities(Request $request) {


        //now check hotels with same name
        $halals = StaticDataHotels::distinct()->orWhere('hotel_name', 'like', '%' . $request->term . '%')->where('isHalal', 'yes')
                ->select('static_data_hotels.city_id')
                ->get();

        $hIds = [];
        foreach ($halals as $key => $h) {
            $hIds[] = $h['city_id'];
        }

        $cities = Cities::distinct()->orWhere('CityName', 'like', '%' . $request->term . '%')
                ->get(array('CityName', 'CityId', 'Country', 'CountryCode'));

        $city_list = array();
        foreach ($cities as $key => $city) {
            array_push($city_list, array('label' => $city['CityName'] . ' (' . $city['Country'] . ')',
                'text' => $city['CityName'] . ' (' . $city['Country'] . ')',
                'countryCode' => $city['CountryCode'],
                'id' => $city['CityId'],
                'type' => 'city',
                'cityName' => $city['CityName'],
                'value' => $city['CityName'] . ' (' . $city['Country'] . ')'));
        }

        //now check hotels with same name
        $hotels = StaticDataHotels::distinct()->orWhere('hotel_name', 'like', '%' . $request->term . '%')
                ->whereIn('cities.CityId', $hIds)
                ->join('cities', 'cities.CityId', '=', 'static_data_hotels.city_id')
                ->select('static_data_hotels.hotel_name', 'static_data_hotels.hotel_code', 'cities.CityName', 'cities.CityId', 'cities.Country', 'cities.CountryCode')
                ->get();


        foreach ($hotels as $key => $h) {
            array_push($city_list, array('label' => $h['hotel_name'] . ' (' . $h['CityName'] . ')',
                'text' => $h['hotel_name'] . ' (' . $h['CityName'] . ')',
                'countryCode' => $h['CountryCode'],
                'id' => $h['CityId'],
                'hotelCode' => $h['hotel_code'],
                'type' => 'hotel',
                'cityName' => $h['CityName'],
                'value' => $h['hotel_name'] . ' (' . $h['CityName'] . ')'));
        }
        return response()->json($city_list);
    }

    public function halalCitiesFH(Request $request) {


        //now check hotels with same name
        $halals = StaticDataHotels::distinct()->orWhere('hotel_name', 'like', '%' . $request->term . '%')->where('isHalal', 'yes')
                ->select('static_data_hotels.city_id')
                ->get();

        $hIds = [];
        foreach ($halals as $key => $h) {
            $hIds[] = $h['city_id'];
        }

        $cities = Cities::distinct()->where(['CityName' =>  $request->term, 'CountryCode' => $request->country ])
                ->get(array('CityName', 'CityId', 'Country', 'CountryCode'));

        $city_list = array();
        foreach ($cities as $key => $city) {
            array_push($city_list, array('label' => $city['CityName'] . ' (' . $city['Country'] . ')',
                'text' => $city['CityName'] . ' (' . $city['Country'] . ')',
                'countryCode' => $city['CountryCode'],
                'id' => $city['CityId'],
                'type' => 'city',
                'cityName' => $city['CityName'],
                'value' => $city['CityName'] . ' (' . $city['Country'] . ')'));
        }

        //now check hotels with same name
        $hotels = StaticDataHotels::distinct()->orWhere('hotel_name', 'like', '%' . $request->term . '%')
                ->whereIn('cities.CityId', $hIds)
                ->join('cities', 'cities.CityId', '=', 'static_data_hotels.city_id')
                ->select('static_data_hotels.hotel_name', 'static_data_hotels.hotel_code', 'cities.CityName', 'cities.CityId', 'cities.Country', 'cities.CountryCode')
                ->get();


        foreach ($hotels as $key => $h) {
            array_push($city_list, array('label' => $h['hotel_name'] . ' (' . $h['CityName'] . ')',
                'text' => $h['hotel_name'] . ' (' . $h['CityName'] . ')',
                'countryCode' => $h['CountryCode'],
                'id' => $h['CityId'],
                'hotelCode' => $h['hotel_code'],
                'type' => 'hotel',
                'cityName' => $h['CityName'],
                'value' => $h['hotel_name'] . ' (' . $h['CityName'] . ')'));
        }
        return response()->json($city_list);
    }

    public function getCities(Request $request) {

        $cities = Cities::distinct()->orWhere('CityName', 'like', '%' . $request->term . '%')
                ->get(array('CityName', 'CityId', 'Country', 'CountryCode'));

        $city_list = array();
        foreach ($cities as $key => $city) {
            array_push($city_list, array('label' => $city['CityName'] . ' (' . $city['Country'] . ')',
                'text' => $city['CityName'] . ' (' . $city['Country'] . ')',
                'countryCode' => $city['CountryCode'],
                'id' => $city['CityId'],
                'type' => 'city',
                'cityName' => $city['CityName'],
                'value' => $city['CityName'] . ' (' . $city['Country'] . ')'));
        }


        // if (strpos($_SERVER['HTTP_HOST'], 'tripheist.com') !== false || strpos($_SERVER['HTTP_HOST'], 'staging.tripheist.com') !== false) {

        //     $hotels = StaticDataHotels::distinct()->where('hotel_name', 'ilike', '%' . $request->term . '%')
        //             ->select('static_data_hotels.hotel_name', 'static_data_hotels.hotel_code','static_data_hotels.city_id')
        //             ->get();
        // }else{

        //     $hotels = StaticDataHotels::distinct()->where('hotel_name', 'like', '%' . $request->term . '%')
        //             ->select('static_data_hotels.hotel_name', 'static_data_hotels.hotel_code','static_data_hotels.city_id')
        //             ->get();
        // }



        // foreach ($hotels as $key => $h) {

        //     $cities = Cities::where(['CityId' => $h['city_id']])
        //                       ->get(array('CityName', 'CityId', 'Country', 'CountryCode'))
        //                       ->first();

        //     array_push($city_list, array('label' => $h['hotel_name'] . ' (' . $cities['CityName'] . ')',
        //         'text' => $h['hotel_name'] . ' (' . $cities['CityName'] . ')',
        //         'countryCode' => $cities['CountryCode'],
        //         'id' => $cities['CityId'],
        //         'hotelCode' => $h['hotel_code'],
        //         'type' => 'hotel',
        //         'cityName' => $cities['CityName'],
        //         'value' => $h['hotel_name'] . ' (' . $cities['CityName'] . ')'));
        // }

        return response()->json($city_list);
    }

    public function getUSCities(Request $request) {
        $cities = Cities::distinct()->orWhere('CityName', 'like', '%' . $request->term . '%')
                ->get(array('CityName', 'CityId', 'Country', 'CountryCode'));

        $city_list = array();
        foreach ($cities as $key => $city) {
            //if($city['CountryCode'] == 'US') {
                $matched = false;
                foreach ($city_list as $key => $c) {
                    if($c['id'] == $city['CityId']) {
                        $matched = true;
                    }
                }
                if(!$matched) {
                    array_push($city_list, array('label' => $city['CityName'] . ' (' . $city['Country'] . ')',
                        'text' => $city['CityName'] . ' (' . $city['Country'] . ')',
                        'countryCode' => $city['CountryCode'],
                        'id' => $city['CityId'],
                        'type' => 'city',
                        'cityName' => $city['CityName'],
                        'value' => $city['CityName'] . ' (' . $city['Country'] . ')'));
                }
            //}
        }
        
        return response()->json($city_list);
    }

     public function getCitiesFH(Request $request) {
        $cities = Cities::distinct()->where(['CityName' =>  $request->term, 'CountryCode' => $request->country ])
                ->get(array('CityName', 'CityId', 'Country', 'CountryCode'));

        $city_list = array();
        foreach ($cities as $key => $city) {
            array_push($city_list, array('label' => $city['CityName'] . ' (' . $city['Country'] . ')',
                'text' => $city['CityName'] . ' (' . $city['Country'] . ')',
                'countryCode' => $city['CountryCode'],
                'id' => $city['CityId'],
                'type' => 'city',
                'cityName' => $city['CityName'],
                'value' => $city['CityName'] . ' (' . $city['Country'] . ')'));
        }

        return response()->json($city_list);
    }

    public function getCountries(Request $request) {
        $cities = Currencies::distinct()->orWhere('name', 'like', '%' . $request->term . '%')
                ->get(array('name', 'code', 'dial_code', 'currency_code'));

        $city_list = array();
        foreach ($cities as $key => $city) {
            array_push($city_list, array('label' => $city['name'],
                'text' => $city['name'],
                'id' => $city['code'],
                'currency_code' => $city['currency_code'],
                'value' => '(+' . $city['dial_code'] . ') ' . $city['name'] . ' (' . $city['code'] . ') '));
        }

        return response()->json($city_list);
    }

    public function getCabCities(Request $request) {
        $cities = TransferCities::distinct()->orWhere('city_name', 'like', '%' . $request->term . '%')
                ->get(array('city_id', 'city_name', 'city_code', 'station_id', 'station_name', 'station_name', 'country_code', 'country_name', 'port_name', 'port_name', 'port_name', 'airport_code', 'airport_name'));

        $city_list = array();
        foreach ($cities as $key => $city) {
            $found = false;
            foreach ($city_list as $c_key => $c_value) {
                if ($c_value['id'] == $city['city_id']) {
                    $found = true;
                }
            }

            if (!$found) {
                array_push($city_list, array('label' => $city['city_name'],
                    'text' => $city['city_name'] . ' (' . $city['country_code'] . ') ',
                    'id' => $city['city_id'],
                    'currency_code' => $city['country_code'],
                    'value' => $city['city_name'] . ' (' . $city['city_code'] . ' - ' . $city['country_code'] . ') '));
            }
        }

        return response()->json($city_list);
    }

    public function getActivityCities(Request $request) {
        $cities = Cities::distinct()->orWhere('CityName', 'like', '%' . $request->term . '%')
                ->get(array('CityName', 'CityId', 'Country', 'CountryCode'));

        $city_list = array();
        foreach ($cities as $key => $city) {
            array_push($city_list, array('label' => $city['CityName'] . ' (' . $city['Country'] . ')',
                'text' => $city['CityName'] . ' (' . $city['Country'] . ')',
                'countryCode' => $city['CountryCode'],
                'id' => $city['CityId'],
                'currency_code' => $city['CountryCode'],
                'cityName' => $city['CityName'],
                'value' => $city['CityName'] . ' (' . $city['Country'] . ')'));
        }
        return response()->json($city_list);
    }

    public function getCabLocations(Request $request) {
        $type = $request->type;
        if ($type == '2') {

            $cities = TransferCities::distinct()->where(['city_id' => $request->cityId, 'type' => 2])
                    ->get(array('city_id', 'city_name', 'city_code', 'station_id', 'station_name', 'station_name', 'country_code', 'country_name', 'port_name', 'port_name', 'port_name', 'airport_code', 'airport_name'));


            $city_list = array();
            foreach ($cities as $key => $city) {
                array_push($city_list, array('label' => $city['station_name'],
                    'text' => $city['station_name'] . ' (' . $city['country_code'] . ') ',
                    'id' => $city['station_id'],
                    'currency_code' => $city['country_code'],
                    'value' => $city['station_name'] . ' (' . $city['city_code'] . ' - ' . $city['country_code'] . ') '));
            }
        } else if ($type == '3') {
            $cities = TransferCities::distinct()->where(['city_id' => $request->cityId])
                    ->get(array('city_id', 'city_name', 'city_code', 'station_id', 'station_name', 'station_name', 'country_code', 'country_name', 'port_name', 'port_id', 'port_name', 'airport_code', 'airport_name'));


            $city_list = array();
            foreach ($cities as $key => $city) {
                if ($city['port_name'] != '' && $city['port_name'] != null) {
                    array_push($city_list, array('label' => $city['port_name'],
                        'text' => $city['port_name'] . ' (' . $city['country_code'] . ') ',
                        'id' => $city['port_id'],
                        'currency_code' => $city['country_code'],
                        'value' => $city['port_name'] . ' (' . $city['city_code'] . ' - ' . $city['country_code'] . ') '));
                }
            }
        } else if ($type == '1') {
            $cities = TransferCities::distinct()->where(['city_id' => $request->cityId, 'type' => 1])
                    ->get(array('city_id', 'city_name', 'city_code', 'station_id', 'station_name', 'station_name', 'country_code', 'country_name', 'port_name', 'port_name', 'port_name', 'airport_code', 'airport_name'));


            $city_list = array();
            foreach ($cities as $key => $city) {
                array_push($city_list, array('label' => $city['airport_name'],
                    'text' => $city['airport_name'] . ' (' . $city['country_code'] . ') ',
                    'id' => $city['airport_code'],
                    'currency_code' => $city['country_code'],
                    'value' => $city['airport_name'] . ' (' . $city['city_code'] . ' - ' . $city['country_code'] . ') '));
            }
        } else if ($type == '0') {

            if (isset($request->term) && $request->term != '') {

                $cities = StaticDataHotels::distinct()->where(['city_id' => $request->cityId])->where('hotel_name', 'ilike', '%' . $request->term . '%')
                        ->get(array('city_id', 'hotel_code', 'hotel_name'));
            } else {
                $cities = StaticDataHotels::where(['city_id' => $request->cityId])->get(array('city_id', 'hotel_code', 'hotel_name'));
            }


            $city_list = array();
            foreach ($cities as $key => $city) {
                array_push($city_list, array(
                    'label' => $city['hotel_name'],
                    'text' => $city['hotel_name'],
                    'id' => $city['hotel_code'],
                    'value' => $city['hotel_name']));
            }
        }

        return response()->json($city_list);
    }

    public function getCountryCode(Request $request) {
        $code = $request->code;
        $countryCode = Currencies::distinct()->where(['currency_code' => $code])->get(array('code'));

        return $countryCode;
    }

    public function testEmail(Request $request) {
        try {
            Mail::to("randhirsinghpaul@gmail.com")->send(new TestEmail('Randhir', 'Working Password'));
            echo "email send";
            die();
        } catch (Exception $e) {
            echo $e->getMessage();
            die("mail not sent");
        }
    }

    public function notFound() {
        return view('404');
    }

    public function cronJobSetCity(Request $request) {
        $number = $request->number;
        $city_id = $request->city_id;

        ini_set('max_execution_time', -1);
        $api = new TBOHotelAPI();
        $data = $api->getCityData($city_id);

        $destinationPath = public_path() . "/logs/static-data/";
        $file = "city-number-" . $number . ".txt";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        File::put($destinationPath . $file, $city_id);

        // $destinationPath=public_path()."/logs/static-data/";
        // $cityId = $request->CityId;

        $path = $destinationPath . "city-" . $city_id . "_logs.xml";

        // Read entire file into string
        $xmlfile = file_get_contents($path);
        $xmlfile = str_replace("=\\", "=", $xmlfile);
        $xmlfile = str_replace('"<?xml', "<?xml", $xmlfile);
        $xmlfile = str_replace('ArrayOfBasicPropertyInfo>"', "ArrayOfBasicPropertyInfo>", $xmlfile);
        $xmlfile = str_replace('\"', '"', $xmlfile);
        $xmlfile = str_replace('\/', '/', $xmlfile);
        $xmlfile = str_replace('\r\n ', '', $xmlfile);
        $xmlfile = str_replace('utf-16', 'utf-8', $xmlfile);
        $xmlfile = str_replace('>\r\n<', '><', $xmlfile);

        $newXml = simplexml_load_string($xmlfile, 'SimpleXMLElement', LIBXML_NOCDATA);

        // Convert into json
        $arrayData = $this->xmlToArray($newXml);
        // echo "<pre>";

        foreach ($arrayData['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'] as $key => $hotel) {

            if (isset($hotel['@TBOHotelCode'])) {
                //check if exits
                $check = StaticDataHotels::where(['city_id' => $city_id, 'hotel_code' => $hotel['@TBOHotelCode']])->first();
                if (isset($check)) {

                    StaticDataHotels::where(['city_id' => $city_id, 'hotel_code' => $hotel['@TBOHotelCode']])
                            ->update(['data_updated' => 0, 'start_rating' => $hotel['@BrandCode']]);
                } else {

                    StaticDataHotels::create(['hotel_name' => isset($hotel['@HotelName']) ? $hotel['@HotelName'] : '',
                        'hotel_code' => $hotel['@TBOHotelCode'],
                        'city_id' => $city_id,
                        'start_rating' => $hotel['@BrandCode'],
                        'data_updated' => 0]);
                }
            }
        }

        return 'done';
    }

    public function cronJobHotelStaticData1(Request $request) {

        //get the city id
        $destinationPath = public_path() . "/logs/static-data/city-number-1.txt";
        $city = file_get_contents($destinationPath);
        //echo $city;
        ini_set('max_execution_time', -1);
        $api = new TBOHotelAPI();
        $cityId = $city;

        $hotels = DB::select("SELECT `hotel_code` FROM `static_data_hotels` WHERE `city_id` = '" . $cityId . "' AND `data_updated` = '0'  LIMIT 50");
        try {

            foreach ($hotels as $key => $hotel) {

                try {
                    $hotelData = $api->getHotelStaticData($cityId, $hotel->hotel_code);
                    $utfReplaces = str_replace('utf-16', 'utf-8', $hotelData['HotelData']);
                    $hotelXml = simplexml_load_string($utfReplaces, 'SimpleXMLElement', LIBXML_NOCDATA);
                    $hotelDataArray = $this->xmlToArray($hotelXml);
                    if (isset($hotelDataArray['ArrayOfBasicPropertyInfo']) && isset($hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'])) {
                        $hotelDataFinal = $hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'];

                        $hotel_code = $hotelDataFinal['@TBOHotelCode'];
                        $hotel_name = $hotelDataFinal['@HotelName'];
                        $start_rating = $hotelDataFinal['@BrandCode'];

                        $hotel_facilities = array();
                        $attractions = array();
                        $hotel_description = array();
                        $hotel_images = array();
                        $room_images = array();

                        $facilities = (isset($hotelDataFinal['VendorMessages']) && isset($hotelDataFinal['VendorMessages']['VendorMessage'])) ? $hotelDataFinal['VendorMessages']['VendorMessage'] : array();
                        $hotel_location = isset($hotelDataFinal['Position']) ? $hotelDataFinal['Position'] : array();
                        $hotel_address = isset($hotelDataFinal['Address']) ? $hotelDataFinal['Address'] : array();
                        $hotel_contact = isset($hotelDataFinal['ContactNumbers']) ? $hotelDataFinal['ContactNumbers'] : array();
                        $hotel_time = isset($hotelDataFinal['Policy']) ? $hotelDataFinal['Policy'] : array();
                        $hotel_type = (isset($hotelDataFinal['HotelThemes']) && isset($hotelDataFinal['HotelThemes']['HotelTheme'])) ? $hotelDataFinal['HotelThemes']['HotelTheme'] : array();

                        $room_images = array();

                        if (isset($facilities)) {
                            foreach ($facilities as $key => $facility) {

                                if (isset($facility['@Title'])) {
                                    if ($facility['@Title'] == 'Facilities') {

                                        foreach ($facility['SubSection'] as $key_fac => $hotel_fac) {
                                            if (isset($hotel_fac) && isset($hotel_fac['Paragraph'])) {
                                                array_push($hotel_facilities, str_replace("'", " ", $hotel_fac['Paragraph']['Text']['$']));
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Attractions') {
                                        foreach ($facility['SubSection'] as $key_attrac => $hotel_attrac) {
                                            if (isset($hotel_attrac) && isset($hotel_attrac['Text']) && isset($hotel_attrac['Text']['$'])) {
                                                array_push($attractions, str_replace("'", " ", $hotel_attrac['Text']['$']));
                                            } else {
                                                if (isset($hotel_attrac['Paragraph']) && isset($hotel_attrac['Paragraph']['Text']) && isset($hotel_attrac['Paragraph']['Text']['$'])) {
                                                    array_push($attractions, str_replace("'", " ", $hotel_attrac['Paragraph']['Text']['$']));
                                                }
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Hotel Description') {
                                        if (isset($facility['SubSection']) && isset($facility['SubSection']['Paragraph']) && isset($facility['SubSection']['Paragraph']['Text']) && isset($facility['SubSection']['Paragraph']['Text']['$'])) {
                                            array_push($hotel_description, str_replace("'", " ", $facility['SubSection']['Paragraph']['Text']['$']));
                                        } else {
                                            foreach ($facility['SubSection'] as $d) {
                                                if (isset($d['Paragraph']) && isset($d['Paragraph']['Text']) && isset($d['Paragraph']['Text']['$'])) {
                                                    array_push($hotel_description, str_replace("'", " ", $d['Paragraph']['Text']['$']));
                                                }
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Hotel Pictures') {
                                        foreach ($facility['SubSection'] as $key_hpic => $hotel_pic) {
                                            if (isset($hotel_pic['Paragraph']) && isset($hotel_pic['Paragraph'][1])) {
                                                if (isset($hotel_pic['Paragraph'][1]['URL']) && $hotel_pic['Paragraph'][1]['@Type'] == 'FullImage') {
                                                    if ($key_hpic < 40) {
                                                        array_push($hotel_images, $hotel_pic['Paragraph'][1]['URL']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($hotelDataFinal['Rooms']) && isset($hotelDataFinal['Rooms']['Room'])) {
                            //check if added
                            foreach ($hotelDataFinal['Rooms']['Room'] as $room_key => $hotel_room) {
                                if (isset($hotel_room['RoomTypeName'])) {
                                    $type = preg_replace('/\s*/', '', $hotel_room['RoomTypeName']);
                                    $image = RoomImages::where(['type' => $type, 'sub_domain' => $hotel_code])->first();
                                    if (isset($image) && isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                        $temp_img = array();
                                        foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                            if (isset($room_image['ImageUrl'])) {
                                                array_push($temp_img, $room_image['ImageUrl']);
                                            }
                                        }
                                        RoomImages::where(['type' => $type, 'sub_domain' => $hotel_code])
                                                ->update(['images' => serialize($temp_img)]);
                                    } else {
                                        //create
                                        $temp_img = array();
                                        if (isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                            foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                                if (isset($room_image['ImageUrl'])) {
                                                    array_push($temp_img, $room_image['ImageUrl']);
                                                }
                                            }
                                            RoomImages::create(['type' => $type, 'sub_domain' => $hotel_code, 'name' => $hotel_room['RoomTypeName'], 'images' => serialize($temp_img)]);
                                        }
                                    }
                                }
                            }
                        }

                        StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $cityId])
                                ->update(['hotel_name' => $hotel_name,
                                    'start_rating' => $start_rating,
                                    'hotel_facilities' => json_encode($hotel_facilities),
                                    'hotel_contact' => json_encode($hotel_contact),
                                    'attractions' => json_encode($attractions),
                                    'hotel_description' => json_encode($hotel_description),
                                    'hotel_images' => json_encode($hotel_images),
                                    'hotel_location' => json_encode($hotel_location),
                                    'hotel_address' => json_encode($hotel_address),
                                    'hotel_time' => json_encode($hotel_time),
                                    'hotel_type' => json_encode($hotel_type),
                                    'data_updated' => 1,
                                    'updated_at' => date('Y-m-d h:i:s')]);
                    }
                } catch (Exception $e) {

                    $data = $e->getMessage();
                    $file = $city . ' - cron_logs1.txt';
                    $destinationPath = public_path() . "/logs/static-data/";
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    File::put($destinationPath . $file, $data);
                }
            }
        } catch (Exception $e) {
            $data = $e->getMessage();
            $file = $city . ' - cron_logs1.txt';
            $destinationPath = public_path() . "/logs/static-data/";
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $file, $data);
            return 'done';
        }

        return 'done';
    }

    public function cronJobHotelStaticData5(Request $request) {

        //get the city id
        $destinationPath = public_path() . "/logs/static-data/city-number-5.txt";
        $city = file_get_contents($destinationPath);
        //echo $city;
        ini_set('max_execution_time', -1);
        $api = new TBOHotelAPI();
        $cityId = $city;

        $hotels = DB::select("SELECT `hotel_code` FROM `static_data_hotels` WHERE `city_id` = '" . $cityId . "' AND `data_updated` = '0'  LIMIT 50");
        try {

            foreach ($hotels as $key => $hotel) {

                try {
                    $hotelData = $api->getHotelStaticData($cityId, $hotel->hotel_code);
                    $utfReplaces = str_replace('utf-16', 'utf-8', $hotelData['HotelData']);
                    $hotelXml = simplexml_load_string($utfReplaces, 'SimpleXMLElement', LIBXML_NOCDATA);
                    $hotelDataArray = $this->xmlToArray($hotelXml);
                    if (isset($hotelDataArray['ArrayOfBasicPropertyInfo']) && isset($hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'])) {
                        $hotelDataFinal = $hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'];

                        $hotel_code = $hotelDataFinal['@TBOHotelCode'];
                        $hotel_name = $hotelDataFinal['@HotelName'];
                        $start_rating = $hotelDataFinal['@BrandCode'];

                        $hotel_facilities = array();
                        $attractions = array();
                        $hotel_description = array();
                        $hotel_images = array();
                        $room_images = array();

                        $facilities = (isset($hotelDataFinal['VendorMessages']) && isset($hotelDataFinal['VendorMessages']['VendorMessage'])) ? $hotelDataFinal['VendorMessages']['VendorMessage'] : array();
                        $hotel_location = isset($hotelDataFinal['Position']) ? $hotelDataFinal['Position'] : array();
                        $hotel_address = isset($hotelDataFinal['Address']) ? $hotelDataFinal['Address'] : array();
                        $hotel_contact = isset($hotelDataFinal['ContactNumbers']) ? $hotelDataFinal['ContactNumbers'] : array();
                        $hotel_time = isset($hotelDataFinal['Policy']) ? $hotelDataFinal['Policy'] : array();
                        $hotel_type = (isset($hotelDataFinal['HotelThemes']) && isset($hotelDataFinal['HotelThemes']['HotelTheme'])) ? $hotelDataFinal['HotelThemes']['HotelTheme'] : array();

                        $room_images = array();

                        if (isset($facilities)) {
                            foreach ($facilities as $key => $facility) {

                                if (isset($facility['@Title'])) {
                                    if ($facility['@Title'] == 'Facilities') {

                                        foreach ($facility['SubSection'] as $key_fac => $hotel_fac) {
                                            if (isset($hotel_fac) && isset($hotel_fac['Paragraph'])) {
                                                array_push($hotel_facilities, str_replace("'", " ", $hotel_fac['Paragraph']['Text']['$']));
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Attractions') {
                                        foreach ($facility['SubSection'] as $key_attrac => $hotel_attrac) {
                                            if (isset($hotel_attrac) && isset($hotel_attrac['Text']) && isset($hotel_attrac['Text']['$'])) {
                                                array_push($attractions, str_replace("'", " ", $hotel_attrac['Text']['$']));
                                            } else {
                                                if (isset($hotel_attrac['Paragraph']) && isset($hotel_attrac['Paragraph']['Text']) && isset($hotel_attrac['Paragraph']['Text']['$'])) {
                                                    array_push($attractions, str_replace("'", " ", $hotel_attrac['Paragraph']['Text']['$']));
                                                }
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Hotel Description') {
                                        if (isset($facility['SubSection']) && isset($facility['SubSection']['Paragraph']) && isset($facility['SubSection']['Paragraph']['Text']) && isset($facility['SubSection']['Paragraph']['Text']['$'])) {
                                            array_push($hotel_description, str_replace("'", " ", $facility['SubSection']['Paragraph']['Text']['$']));
                                        } else {
                                            foreach ($facility['SubSection'] as $d) {
                                                if (isset($d['Paragraph']) && isset($d['Paragraph']['Text']) && isset($d['Paragraph']['Text']['$'])) {
                                                    array_push($hotel_description, str_replace("'", " ", $d['Paragraph']['Text']['$']));
                                                }
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Hotel Pictures') {
                                        foreach ($facility['SubSection'] as $key_hpic => $hotel_pic) {
                                            if (isset($hotel_pic['Paragraph']) && isset($hotel_pic['Paragraph'][1])) {
                                                if (isset($hotel_pic['Paragraph'][1]['URL']) && $hotel_pic['Paragraph'][1]['@Type'] == 'FullImage') {
                                                    if ($key_hpic < 40) {
                                                        array_push($hotel_images, $hotel_pic['Paragraph'][1]['URL']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($hotelDataFinal['Rooms']) && isset($hotelDataFinal['Rooms']['Room'])) {
                            //check if added
                            foreach ($hotelDataFinal['Rooms']['Room'] as $room_key => $hotel_room) {
                                if (isset($hotel_room['RoomTypeName'])) {
                                    $type = preg_replace('/\s*/', '', $hotel_room['RoomTypeName']);
                                    $image = RoomImages::where(['type' => $type, 'sub_domain' => $hotel_code])->first();
                                    if (isset($image) && isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                        $temp_img = array();
                                        foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                            if (isset($room_image['ImageUrl'])) {
                                                array_push($temp_img, $room_image['ImageUrl']);
                                            }
                                        }
                                        RoomImages::where(['type' => $type, 'sub_domain' => $hotel_code])
                                                ->update(['images' => serialize($temp_img)]);
                                    } else {
                                        //create
                                        $temp_img = array();
                                        if (isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                            foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                                if (isset($room_image['ImageUrl'])) {
                                                    array_push($temp_img, $room_image['ImageUrl']);
                                                }
                                            }
                                            RoomImages::create(['type' => $type, 'sub_domain' => $hotel_code, 'name' => $hotel_room['RoomTypeName'], 'images' => serialize($temp_img)]);
                                        }
                                    }
                                }
                            }
                        }

                        StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $cityId])
                                ->update(['hotel_name' => $hotel_name,
                                    'start_rating' => $start_rating,
                                    'hotel_facilities' => json_encode($hotel_facilities),
                                    'hotel_contact' => json_encode($hotel_contact),
                                    'attractions' => json_encode($attractions),
                                    'hotel_description' => json_encode($hotel_description),
                                    'hotel_images' => json_encode($hotel_images),
                                    'hotel_location' => json_encode($hotel_location),
                                    'hotel_address' => json_encode($hotel_address),
                                    'hotel_time' => json_encode($hotel_time),
                                    'hotel_type' => json_encode($hotel_type),
                                    'data_updated' => 1,
                                    'updated_at' => date('Y-m-d h:i:s')]);
                    }
                } catch (Exception $e) {

                    $data = $e->getMessage();
                    $file = $city . ' - cron_logs5.txt';
                    $destinationPath = public_path() . "/logs/static-data/";
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    File::put($destinationPath . $file, $data);
                }
            }
        } catch (Exception $e) {
            $data = $e->getMessage();
            $file = $city . ' - cron_logs5.txt';
            $destinationPath = public_path() . "/logs/static-data/";
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $file, $data);
            return 'done';
        }

        return 'done';
    }

    public function cronJobHotelStaticData4(Request $request) {

        //get the city id
        $destinationPath = public_path() . "/logs/static-data/city-number-4.txt";
        $city = file_get_contents($destinationPath);
        //echo $city;
        ini_set('max_execution_time', -1);
        $api = new TBOHotelAPI();
        $cityId = $city;

        $hotels = DB::select("SELECT `hotel_code` FROM `static_data_hotels` WHERE `city_id` = '" . $cityId . "' AND `data_updated` = '0'  LIMIT 50");
        try {

            foreach ($hotels as $key => $hotel) {

                try {
                    $hotelData = $api->getHotelStaticData($cityId, $hotel->hotel_code);
                    $utfReplaces = str_replace('utf-16', 'utf-8', $hotelData['HotelData']);
                    $hotelXml = simplexml_load_string($utfReplaces, 'SimpleXMLElement', LIBXML_NOCDATA);
                    $hotelDataArray = $this->xmlToArray($hotelXml);
                    if (isset($hotelDataArray['ArrayOfBasicPropertyInfo']) && isset($hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'])) {
                        $hotelDataFinal = $hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'];

                        $hotel_code = $hotelDataFinal['@TBOHotelCode'];
                        $hotel_name = $hotelDataFinal['@HotelName'];
                        $start_rating = $hotelDataFinal['@BrandCode'];

                        $hotel_facilities = array();
                        $attractions = array();
                        $hotel_description = array();
                        $hotel_images = array();
                        $room_images = array();

                        $facilities = (isset($hotelDataFinal['VendorMessages']) && isset($hotelDataFinal['VendorMessages']['VendorMessage'])) ? $hotelDataFinal['VendorMessages']['VendorMessage'] : array();
                        $hotel_location = isset($hotelDataFinal['Position']) ? $hotelDataFinal['Position'] : array();
                        $hotel_address = isset($hotelDataFinal['Address']) ? $hotelDataFinal['Address'] : array();
                        $hotel_contact = isset($hotelDataFinal['ContactNumbers']) ? $hotelDataFinal['ContactNumbers'] : array();
                        $hotel_time = isset($hotelDataFinal['Policy']) ? $hotelDataFinal['Policy'] : array();
                        $hotel_type = (isset($hotelDataFinal['HotelThemes']) && isset($hotelDataFinal['HotelThemes']['HotelTheme'])) ? $hotelDataFinal['HotelThemes']['HotelTheme'] : array();

                        $room_images = array();

                        if (isset($facilities)) {
                            foreach ($facilities as $key => $facility) {

                                if (isset($facility['@Title'])) {
                                    if ($facility['@Title'] == 'Facilities') {

                                        foreach ($facility['SubSection'] as $key_fac => $hotel_fac) {
                                            if (isset($hotel_fac) && isset($hotel_fac['Paragraph'])) {
                                                array_push($hotel_facilities, str_replace("'", " ", $hotel_fac['Paragraph']['Text']['$']));
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Attractions') {
                                        foreach ($facility['SubSection'] as $key_attrac => $hotel_attrac) {
                                            if (isset($hotel_attrac) && isset($hotel_attrac['Text']) && isset($hotel_attrac['Text']['$'])) {
                                                array_push($attractions, str_replace("'", " ", $hotel_attrac['Text']['$']));
                                            } else {
                                                if (isset($hotel_attrac['Paragraph']) && isset($hotel_attrac['Paragraph']['Text']) && isset($hotel_attrac['Paragraph']['Text']['$'])) {
                                                    array_push($attractions, str_replace("'", " ", $hotel_attrac['Paragraph']['Text']['$']));
                                                }
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Hotel Description') {
                                        if (isset($facility['SubSection']) && isset($facility['SubSection']['Paragraph']) && isset($facility['SubSection']['Paragraph']['Text']) && isset($facility['SubSection']['Paragraph']['Text']['$'])) {
                                            array_push($hotel_description, str_replace("'", " ", $facility['SubSection']['Paragraph']['Text']['$']));
                                        } else {
                                            foreach ($facility['SubSection'] as $d) {
                                                if (isset($d['Paragraph']) && isset($d['Paragraph']['Text']) && isset($d['Paragraph']['Text']['$'])) {
                                                    array_push($hotel_description, str_replace("'", " ", $d['Paragraph']['Text']['$']));
                                                }
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Hotel Pictures') {
                                        foreach ($facility['SubSection'] as $key_hpic => $hotel_pic) {
                                            if (isset($hotel_pic['Paragraph']) && isset($hotel_pic['Paragraph'][1])) {
                                                if (isset($hotel_pic['Paragraph'][1]['URL']) && $hotel_pic['Paragraph'][1]['@Type'] == 'FullImage') {
                                                    if ($key_hpic < 40) {
                                                        array_push($hotel_images, $hotel_pic['Paragraph'][1]['URL']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($hotelDataFinal['Rooms']) && isset($hotelDataFinal['Rooms']['Room'])) {
                            //check if added
                            foreach ($hotelDataFinal['Rooms']['Room'] as $room_key => $hotel_room) {
                                if (isset($hotel_room['RoomTypeName'])) {
                                    $type = preg_replace('/\s*/', '', $hotel_room['RoomTypeName']);
                                    $image = RoomImages::where(['type' => $type, 'sub_domain' => $hotel_code])->first();
                                    if (isset($image) && isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                        $temp_img = array();
                                        foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                            if (isset($room_image['ImageUrl'])) {
                                                array_push($temp_img, $room_image['ImageUrl']);
                                            }
                                        }
                                        RoomImages::where(['type' => $type, 'sub_domain' => $hotel_code])
                                                ->update(['images' => serialize($temp_img)]);
                                    } else {
                                        //create
                                        $temp_img = array();
                                        if (isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                            foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                                if (isset($room_image['ImageUrl'])) {
                                                    array_push($temp_img, $room_image['ImageUrl']);
                                                }
                                            }
                                            RoomImages::create(['type' => $type, 'sub_domain' => $hotel_code, 'name' => $hotel_room['RoomTypeName'], 'images' => serialize($temp_img)]);
                                        }
                                    }
                                }
                            }
                        }

                        StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $cityId])
                                ->update(['hotel_name' => $hotel_name,
                                    'start_rating' => $start_rating,
                                    'hotel_facilities' => json_encode($hotel_facilities),
                                    'hotel_contact' => json_encode($hotel_contact),
                                    'attractions' => json_encode($attractions),
                                    'hotel_description' => json_encode($hotel_description),
                                    'hotel_images' => json_encode($hotel_images),
                                    'hotel_location' => json_encode($hotel_location),
                                    'hotel_address' => json_encode($hotel_address),
                                    'hotel_time' => json_encode($hotel_time),
                                    'hotel_type' => json_encode($hotel_type),
                                    'data_updated' => 1,
                                    'updated_at' => date('Y-m-d h:i:s')]);
                    }
                } catch (Exception $e) {

                    $data = $e->getMessage();
                    $file = $city . ' - cron_logs4.txt';
                    $destinationPath = public_path() . "/logs/static-data/";
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    File::put($destinationPath . $file, $data);
                }
            }
        } catch (Exception $e) {
            $data = $e->getMessage();
            $file = $city . ' - cron_logs4.txt';
            $destinationPath = public_path() . "/logs/static-data/";
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $file, $data);
            return 'done';
        }

        return 'done';
    }

    public function cronJobHotelStaticData2(Request $request) {

        //get the city id
        $destinationPath = public_path() . "/logs/static-data/city-number-2.txt";
        $city = file_get_contents($destinationPath);
        //echo $city;
        ini_set('max_execution_time', -1);
        $api = new TBOHotelAPI();
        $cityId = $city;

        $hotels = DB::select("SELECT `hotel_code` FROM `static_data_hotels` WHERE `city_id` = '" . $cityId . "' AND `data_updated` = '0'  LIMIT 50");
        try {

            foreach ($hotels as $key => $hotel) {

                try {
                    $hotelData = $api->getHotelStaticData($cityId, $hotel->hotel_code);
                    $utfReplaces = str_replace('utf-16', 'utf-8', $hotelData['HotelData']);
                    $hotelXml = simplexml_load_string($utfReplaces, 'SimpleXMLElement', LIBXML_NOCDATA);
                    $hotelDataArray = $this->xmlToArray($hotelXml);
                    if (isset($hotelDataArray['ArrayOfBasicPropertyInfo']) && isset($hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'])) {
                        $hotelDataFinal = $hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'];

                        $hotel_code = $hotelDataFinal['@TBOHotelCode'];
                        $hotel_name = $hotelDataFinal['@HotelName'];
                        $start_rating = $hotelDataFinal['@BrandCode'];

                        $hotel_facilities = array();
                        $attractions = array();
                        $hotel_description = array();
                        $hotel_images = array();
                        $room_images = array();

                        $facilities = (isset($hotelDataFinal['VendorMessages']) && isset($hotelDataFinal['VendorMessages']['VendorMessage'])) ? $hotelDataFinal['VendorMessages']['VendorMessage'] : array();
                        $hotel_location = isset($hotelDataFinal['Position']) ? $hotelDataFinal['Position'] : array();
                        $hotel_address = isset($hotelDataFinal['Address']) ? $hotelDataFinal['Address'] : array();
                        $hotel_contact = isset($hotelDataFinal['ContactNumbers']) ? $hotelDataFinal['ContactNumbers'] : array();
                        $hotel_time = isset($hotelDataFinal['Policy']) ? $hotelDataFinal['Policy'] : array();
                        $hotel_type = (isset($hotelDataFinal['HotelThemes']) && isset($hotelDataFinal['HotelThemes']['HotelTheme'])) ? $hotelDataFinal['HotelThemes']['HotelTheme'] : array();

                        $room_images = array();

                        if (isset($facilities)) {
                            foreach ($facilities as $key => $facility) {

                                if (isset($facility['@Title'])) {
                                    if ($facility['@Title'] == 'Facilities') {

                                        foreach ($facility['SubSection'] as $key_fac => $hotel_fac) {
                                            if (isset($hotel_fac) && isset($hotel_fac['Paragraph'])) {
                                                array_push($hotel_facilities, str_replace("'", " ", $hotel_fac['Paragraph']['Text']['$']));
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Attractions') {
                                        foreach ($facility['SubSection'] as $key_attrac => $hotel_attrac) {
                                            if (isset($hotel_attrac) && isset($hotel_attrac['Text']) && isset($hotel_attrac['Text']['$'])) {
                                                array_push($attractions, str_replace("'", " ", $hotel_attrac['Text']['$']));
                                            } else {
                                                if (isset($hotel_attrac['Paragraph']) && isset($hotel_attrac['Paragraph']['Text']) && isset($hotel_attrac['Paragraph']['Text']['$'])) {
                                                    array_push($attractions, str_replace("'", " ", $hotel_attrac['Paragraph']['Text']['$']));
                                                }
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Hotel Description') {
                                        if (isset($facility['SubSection']) && isset($facility['SubSection']['Paragraph']) && isset($facility['SubSection']['Paragraph']['Text']) && isset($facility['SubSection']['Paragraph']['Text']['$'])) {
                                            array_push($hotel_description, str_replace("'", " ", $facility['SubSection']['Paragraph']['Text']['$']));
                                        } else {
                                            foreach ($facility['SubSection'] as $d) {
                                                if (isset($d['Paragraph']) && isset($d['Paragraph']['Text']) && isset($d['Paragraph']['Text']['$'])) {
                                                    array_push($hotel_description, str_replace("'", " ", $d['Paragraph']['Text']['$']));
                                                }
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Hotel Pictures') {
                                        foreach ($facility['SubSection'] as $key_hpic => $hotel_pic) {
                                            if (isset($hotel_pic['Paragraph']) && isset($hotel_pic['Paragraph'][1])) {
                                                if (isset($hotel_pic['Paragraph'][1]['URL']) && $hotel_pic['Paragraph'][1]['@Type'] == 'FullImage') {
                                                    if ($key_hpic < 40) {
                                                        array_push($hotel_images, $hotel_pic['Paragraph'][1]['URL']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($hotelDataFinal['Rooms']) && isset($hotelDataFinal['Rooms']['Room'])) {
                            //check if added
                            foreach ($hotelDataFinal['Rooms']['Room'] as $room_key => $hotel_room) {
                                if (isset($hotel_room['RoomTypeName'])) {
                                    $type = preg_replace('/\s*/', '', $hotel_room['RoomTypeName']);
                                    $image = RoomImages::where(['type' => $type, 'sub_domain' => $hotel_code])->first();
                                    if (isset($image) && isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                        $temp_img = array();
                                        foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                            if (isset($room_image['ImageUrl'])) {
                                                array_push($temp_img, $room_image['ImageUrl']);
                                            }
                                        }
                                        RoomImages::where(['type' => $type, 'sub_domain' => $hotel_code])
                                                ->update(['images' => serialize($temp_img)]);
                                    } else {
                                        //create
                                        $temp_img = array();
                                        if (isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                            foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                                if (isset($room_image['ImageUrl'])) {
                                                    array_push($temp_img, $room_image['ImageUrl']);
                                                }
                                            }
                                            RoomImages::create(['type' => $type, 'sub_domain' => $hotel_code, 'name' => $hotel_room['RoomTypeName'], 'images' => serialize($temp_img)]);
                                        }
                                    }
                                }
                            }
                        }

                        StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $cityId])
                                ->update(['hotel_name' => $hotel_name,
                                    'start_rating' => $start_rating,
                                    'hotel_facilities' => json_encode($hotel_facilities),
                                    'hotel_contact' => json_encode($hotel_contact),
                                    'attractions' => json_encode($attractions),
                                    'hotel_description' => json_encode($hotel_description),
                                    'hotel_images' => json_encode($hotel_images),
                                    'hotel_location' => json_encode($hotel_location),
                                    'hotel_address' => json_encode($hotel_address),
                                    'hotel_time' => json_encode($hotel_time),
                                    'hotel_type' => json_encode($hotel_type),
                                    'data_updated' => 1,
                                    'updated_at' => date('Y-m-d h:i:s')]);
                    }
                } catch (Exception $e) {

                    $data = $e->getMessage();
                    $file = $city . ' - cron_logs2.txt';
                    $destinationPath = public_path() . "/logs/static-data/";
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    File::put($destinationPath . $file, $data);
                }
            }
        } catch (Exception $e) {
            $data = $e->getMessage();
            $file = $city . ' - cron_logs2.txt';
            $destinationPath = public_path() . "/logs/static-data/";
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $file, $data);
            return 'done';
        }

        return 'done';
    }

    public function cronJobHotelStaticData3(Request $request) {

        //get the city id
        $destinationPath = public_path() . "/logs/static-data/city-number-3.txt";
        $city = file_get_contents($destinationPath);
        //echo $city;
        ini_set('max_execution_time', -1);
        $api = new TBOHotelAPI();
        $cityId = $city;

        $hotels = DB::select("SELECT `hotel_code` FROM `static_data_hotels` WHERE `city_id` = '" . $cityId . "' AND `data_updated` = '0'  LIMIT 50");
        try {

            foreach ($hotels as $key => $hotel) {

                try {
                    $hotelData = $api->getHotelStaticData($cityId, $hotel->hotel_code);
                    $utfReplaces = str_replace('utf-16', 'utf-8', $hotelData['HotelData']);
                    $hotelXml = simplexml_load_string($utfReplaces, 'SimpleXMLElement', LIBXML_NOCDATA);
                    $hotelDataArray = $this->xmlToArray($hotelXml);
                    if (isset($hotelDataArray['ArrayOfBasicPropertyInfo']) && isset($hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'])) {
                        $hotelDataFinal = $hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'];

                        $hotel_code = $hotelDataFinal['@TBOHotelCode'];
                        $hotel_name = $hotelDataFinal['@HotelName'];
                        $start_rating = $hotelDataFinal['@BrandCode'];

                        $hotel_facilities = array();
                        $attractions = array();
                        $hotel_description = array();
                        $hotel_images = array();
                        $room_images = array();

                        $facilities = (isset($hotelDataFinal['VendorMessages']) && isset($hotelDataFinal['VendorMessages']['VendorMessage'])) ? $hotelDataFinal['VendorMessages']['VendorMessage'] : array();
                        $hotel_location = isset($hotelDataFinal['Position']) ? $hotelDataFinal['Position'] : array();
                        $hotel_address = isset($hotelDataFinal['Address']) ? $hotelDataFinal['Address'] : array();
                        $hotel_contact = isset($hotelDataFinal['ContactNumbers']) ? $hotelDataFinal['ContactNumbers'] : array();
                        $hotel_time = isset($hotelDataFinal['Policy']) ? $hotelDataFinal['Policy'] : array();
                        $hotel_type = (isset($hotelDataFinal['HotelThemes']) && isset($hotelDataFinal['HotelThemes']['HotelTheme'])) ? $hotelDataFinal['HotelThemes']['HotelTheme'] : array();

                        $room_images = array();

                        if (isset($facilities)) {
                            foreach ($facilities as $key => $facility) {

                                if (isset($facility['@Title'])) {
                                    if ($facility['@Title'] == 'Facilities') {

                                        foreach ($facility['SubSection'] as $key_fac => $hotel_fac) {
                                            if (isset($hotel_fac) && isset($hotel_fac['Paragraph'])) {
                                                array_push($hotel_facilities, str_replace("'", " ", $hotel_fac['Paragraph']['Text']['$']));
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Attractions') {
                                        foreach ($facility['SubSection'] as $key_attrac => $hotel_attrac) {
                                            if (isset($hotel_attrac) && isset($hotel_attrac['Text']) && isset($hotel_attrac['Text']['$'])) {
                                                array_push($attractions, str_replace("'", " ", $hotel_attrac['Text']['$']));
                                            } else {
                                                if (isset($hotel_attrac['Paragraph']) && isset($hotel_attrac['Paragraph']['Text']) && isset($hotel_attrac['Paragraph']['Text']['$'])) {
                                                    array_push($attractions, str_replace("'", " ", $hotel_attrac['Paragraph']['Text']['$']));
                                                }
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Hotel Description') {
                                        if (isset($facility['SubSection']) && isset($facility['SubSection']['Paragraph']) && isset($facility['SubSection']['Paragraph']['Text']) && isset($facility['SubSection']['Paragraph']['Text']['$'])) {
                                            array_push($hotel_description, str_replace("'", " ", $facility['SubSection']['Paragraph']['Text']['$']));
                                        } else {
                                            foreach ($facility['SubSection'] as $d) {
                                                if (isset($d['Paragraph']) && isset($d['Paragraph']['Text']) && isset($d['Paragraph']['Text']['$'])) {
                                                    array_push($hotel_description, str_replace("'", " ", $d['Paragraph']['Text']['$']));
                                                }
                                            }
                                        }
                                    }

                                    if ($facility['@Title'] == 'Hotel Pictures') {
                                        foreach ($facility['SubSection'] as $key_hpic => $hotel_pic) {
                                            if (isset($hotel_pic['Paragraph']) && isset($hotel_pic['Paragraph'][1])) {
                                                if (isset($hotel_pic['Paragraph'][1]['URL']) && $hotel_pic['Paragraph'][1]['@Type'] == 'FullImage') {
                                                    if ($key_hpic < 40) {
                                                        array_push($hotel_images, $hotel_pic['Paragraph'][1]['URL']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($hotelDataFinal['Rooms']) && isset($hotelDataFinal['Rooms']['Room'])) {
                            //check if added
                            foreach ($hotelDataFinal['Rooms']['Room'] as $room_key => $hotel_room) {
                                if (isset($hotel_room['RoomTypeName'])) {
                                    $type = preg_replace('/\s*/', '', $hotel_room['RoomTypeName']);
                                    $image = RoomImages::where(['type' => $type, 'sub_domain' => $hotel_code])->first();
                                    if (isset($image) && isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                        $temp_img = array();
                                        foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                            if (isset($room_image['ImageUrl'])) {
                                                array_push($temp_img, $room_image['ImageUrl']);
                                            }
                                        }
                                        RoomImages::where(['type' => $type, 'sub_domain' => $hotel_code])
                                                ->update(['images' => serialize($temp_img)]);
                                    } else {
                                        //create
                                        $temp_img = array();
                                        if (isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                            foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                                if (isset($room_image['ImageUrl'])) {
                                                    array_push($temp_img, $room_image['ImageUrl']);
                                                }
                                            }
                                            RoomImages::create(['type' => $type, 'sub_domain' => $hotel_code, 'name' => $hotel_room['RoomTypeName'], 'images' => serialize($temp_img)]);
                                        }
                                    }
                                }
                            }
                        }

                        StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $cityId])
                                ->update(['hotel_name' => $hotel_name,
                                    'start_rating' => $start_rating,
                                    'hotel_facilities' => json_encode($hotel_facilities),
                                    'hotel_contact' => json_encode($hotel_contact),
                                    'attractions' => json_encode($attractions),
                                    'hotel_description' => json_encode($hotel_description),
                                    'hotel_images' => json_encode($hotel_images),
                                    'hotel_location' => json_encode($hotel_location),
                                    'hotel_address' => json_encode($hotel_address),
                                    'hotel_time' => json_encode($hotel_time),
                                    'hotel_type' => json_encode($hotel_type),
                                    'data_updated' => 1,
                                    'updated_at' => date('Y-m-d h:i:s')]);
                    }
                } catch (Exception $e) {

                    $data = $e->getMessage();
                    $file = $city . ' - cron_logs3.txt';
                    $destinationPath = public_path() . "/logs/static-data/";
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    File::put($destinationPath . $file, $data);
                }
            }
        } catch (Exception $e) {
            $data = $e->getMessage();
            $file = $city . ' - cron_logs3.txt';
            $destinationPath = public_path() . "/logs/static-data/";
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $file, $data);
            return 'done';
        }

        return 'done';
    }

    public function cronJobHotelStaticDataProgress(Request $request) {
        ini_set('max_execution_time', -1);
        $number = $request->number;
        $destinationPath = public_path() . "/logs/static-data/city-number-" . $number . ".txt";
        $city = file_get_contents($destinationPath);

        $updated_hotels = DB::select("SELECT COUNT(id) AS `total_count` FROM `static_data_hotels` WHERE `city_id` = '" . $city . "' AND `data_updated` = '1'");
        $pending_hotels = DB::select("SELECT COUNT(id) AS `total_count` FROM `static_data_hotels` WHERE `city_id` = '" . $city . "' AND `data_updated` = '0'");

        echo " Total " . $updated_hotels[0]->total_count . " are updated and " . $pending_hotels[0]->total_count . "  are still pending.";
    }

    public function xmlToArray($xml, $options = array()) {
        $defaults = array(
            'namespaceSeparator' => ':', //you may want this to be something other than a colon
            'attributePrefix' => '@', //to distinguish between attributes and nodes with the same name
            'alwaysArray' => array(), //array of xml tag names which should always become arrays
            'autoArray' => true, //only create arrays for tags which appear more than once
            'textContent' => '$', //key used for the text content of elements
            'autoText' => true, //skip textContent key if node has no attributes or child nodes
            'keySearch' => false, //optional search and replace on tag and attribute names
            'keyReplace' => false       //replace values for above search values (as passed to str_replace())
        );
        $options = array_merge($defaults, $options);
        $namespaces = $xml->getDocNamespaces();
        $namespaces[''] = null; //add base (empty) namespace
        //get attributes from all namespaces
        $attributesArray = array();
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
                //replace characters in attribute name
                if ($options['keySearch'])
                    $attributeName = str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
                $attributeKey = $options['attributePrefix']
                        . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                        . $attributeName;
                $attributesArray[$attributeKey] = (string) $attribute;
            }
        }

        //get child nodes from all namespaces
        $tagsArray = array();
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->children($namespace) as $childXml) {
                //recurse into child nodes
                $childArray = $this->xmlToArray($childXml, $options);
                foreach ($childArray as $key => $value) {
                    // list($childTagName, $childProperties);
                    $childTagName = $key;
                    $childProperties = $value;
                }

                //replace characters in tag name
                if ($options['keySearch'])
                    $childTagName = str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
                //add namespace prefix, if any
                if ($prefix)
                    $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;

                if (!isset($tagsArray[$childTagName])) {
                    //only entry with this key
                    //test if tags of this type should always be arrays, no matter the element count
                    $tagsArray[$childTagName] = in_array($childTagName, $options['alwaysArray']) || !$options['autoArray'] ? array($childProperties) : $childProperties;
                } elseif (
                        is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName]) === range(0, count($tagsArray[$childTagName]) - 1)
                ) {
                    //key already exists and is integer indexed array
                    $tagsArray[$childTagName][] = $childProperties;
                } else {
                    //key exists so convert to integer indexed array with previous value in position 0
                    $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
                }
            }
        }

        //get text content of node
        $textContentArray = array();
        $plainText = trim((string) $xml);
        if ($plainText !== '')
            $textContentArray[$options['textContent']] = $plainText;

        //stick it all together
        $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '') ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;

        //return node as array
        return array(
            $xml->getName() => $propertiesArray
        );
    }

    public function setCookie(Request $request) {
        return response('success')->cookie('accept-cookie', true, env('COOKIE_ACCEPT_LIFETIME'));
    }

    public function setCookie2($name, $value, $time) {
        setcookie($name, $value, time() + (60 * $time), "/");
        return true;
    }

    public function getCookie($name) {
        return $_COOKIE[$name];
    }

}