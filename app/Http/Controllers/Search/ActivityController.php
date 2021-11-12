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
use Log;
use App\Models\User;
use App\Models\Activities;
use App\Models\Packages;
use App\Models\Cruises;
use App\Models\Cabs;
use App\Models\Bookings;
use App\Models\Payments;
use App\Models\Currencies;
use App\Models\Cities;
use App\Models\Reviews;
use App\Models\HotelInfos;
use App\Models\NotificationAgents;
use App\Services\TBOActivityAPI;
//use App\Services\TBAAPI;
use Stripe\Stripe;
use App\Mail\NewUserRegister;
use App\Mail\ActivitiesEmail;
use App\Mail\ActivityBookingEmail;
use App\Mail\FailedPaymentEmail;
use App\Models\AffiliateUsers;
use App\Models\Posts;
use Currency;
use Config;
use File;

class ActivityController extends Controller {

    private $api;
    private $hotelId;
    private $hotelName;
    private $hoteIndex;
    private $traceId;
    private $hotelDetails;
    private $hotelRooms;
    private $hotelRoomsCombination;
    private $allHotels;
    private $checkInDate;
    private $checkOutDate;
    private $sub_domain;
    private $domainData;
    public $end_user_ip;

    public function __construct(Request $request) {
        ini_set('max_execution_time', 240);
        $this->api = new TBOActivityAPI();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? \Request::ip() ?? '3.64.135.96';
        if ($ip == '127.0.0.1') {
            $ip = '52.186.25.21'; //'93.173.228.94'; US = 52.186.25.21 IN = 132.154.175.244
        }
        if (count(explode(', ', $ip)) > 1) {
              $this->end_user_ip = explode(', ', $ip)[0];
        } else {
              $this->end_user_ip = $ip;
        }

    }

    public function search(Request $request) {
        $activities = array();
        $this->temp = 'set value ';
        $input = $request->all();

        if(isset($input['travelstartdate'])) {
            $now = date('Y-m-d');
            $date = date('Y-m-d', strtotime($input['travelstartdate']));

            if ($date < $now) {
                $new_date = date('Y-m-d', strtotime('+5 days'));
                $input['travelstartdate'] = $new_date;
            }
        }

        $isAgent = false;
        if(Auth::user()) {
            $agent = AffiliateUsers::select('id')->where('user_id', Auth::user()->id)->first();
            if(isset($agent) && !empty($agent)) {
                $isAgent = true;
            }
        }

        return view('search.activities.activities')->with(['activities' => $activities, 'input' => $input, 'referral' => $_GET['referral'], 'isAgent' => $isAgent]);
    }

    public function searchActivities(Request $request) {


        //$input = $request->all();
        Session::put('active_tab', 'activities');
        $postJson = file_get_contents('php://input');
        $postArray = json_decode($postJson, true);
        $input = array();

        foreach ($postArray as $post) {
            foreach ($post as $key => $p) {
                $input[$key] = $p;
            }
        }

        $this->api = new TBOActivityAPI();

        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));        

        } else {
        
            $location = \Location::get($this->end_user_ip);            
        }

        $country = $location->countryCode;

        //get user currency
        $currencyCode = Currencies::select('name', 'currency_code')->where('code', $country)->first();


        //$input['currency'] = $currencyCode['currency_code'];
        $ourCurrency = Config::get('ourcurrency');

        if (in_array($currencyCode['currency_code'], $ourCurrency)) {
            $input['currency']=$currencyCode['currency_code'];
        } else {
             $input['currency']= 'USD';
        }
        Session::put('currency', $input['currency']);

        $input['travelstartdate'] = date('Y-m-d', strtotime($input['travelstartdate']));
        //$input['SSstartDate']     = date('Y-m-d', strtotime($input['SSstartDate']));

        $childAges = array();
        for ($i = 1; $i <= $input['childsCCA']; $i++) {

            if ($input['childsCCA'] > 0) {
                array_push($childAges, $input['childAge' . $i]);
            } else {
                $childAges = null;
            }
        }

        $date = date('Y-m-d');
        $now = date('Y-m-d', strtotime($input['travelstartdate']));

        if ($date >= $now) {
            $new_date = date('Y-m-d', strtotime('+5 days'));
            $input['travelstartdate'] = $new_date;
        }

        $postData = [
            "CountryCode" => $input['currency_code_act'],
            "CityId" => $input['city_act_id'],
            //"TravelStartDate" => $input['travelstartdate'],
            "FromDate" => date('Y-m-d', strtotime($input['travelstartdate'])) . "T00:00:00",
            "AdultCount" => $input['adultsCCA'],
            "ChildCount" => $input['childsCCA'],
            "PreferredCurrency" => $input['currency'],
            "ChildAge" => $childAges,
            "EndUserIp" => $this->api->userIP,
            "TokenId" => $this->api->tokenId,
        ];


        $input['childAge'] = $childAges;


        try {
            $this->activities = $this->api->activitySearch($postData);

            $commisioninis = env('INIS_VAL_ACTIVITY');

            $conversion = env('CONVERSION_VAL_ACTIVITY');

            if ($this->activities['Response']['ResponseStatus'] == 1) {

                $this->setCookie('activity_session', time() + (60 * 13), 20);
                $fileName = time() + (60 * 13);

                foreach ($this->activities['Response']['SightseeingSearchResults'] as $key => $activity) {
                
                    $tdsVal = ((env('INIS_TDS') / 100) * ( $activity['Price']['OfferedPriceRoundedOff'] ));
                    $inis_markup = (($commisioninis / 100) * ( $activity['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                    $price_with_markup = $inis_markup + $activity['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                    if($input['currency'] == 'ILS'){

                        $inis_markup = ((env('INIS_VAL_PAYME') / 100) * ($activity['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                        $price_with_markup = $inis_markup + $activity['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                        $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;

                        $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                        $price_with_markup = $price_with_markup + env('PAYME_FIX_FEES') + $vat + $taxes;

                    }else{

                        $taxes = ($conversion / 100) * $price_with_markup;
                        $price_with_markup = $price_with_markup + $taxes;
                    }

                    $this->activities['Response']['SightseeingSearchResults'][$key]['FinalPrice'] = $price_with_markup;

                }

                $fileContents = json_encode(array('request' => $input, 'response' => $this->activities['Response']['SightseeingSearchResults']));
                $this->saveSearchData($fileName . '.json', $fileContents);

                return response()->json(array('activities' => $this->activities['Response']['SightseeingSearchResults'], 'traceId' => $this->activities['Response']['TraceId'], 'input_data' => $input, 'status' => true, 'commission_inis' => $commisioninis, 'conversion' => $conversion, 'search_id' => $fileName));
            } else {

                return response()->json(array('activities' => array(), 'message' => $this->activities['Response']['Error']['ErrorMessage'], 'input_data' => $input, 'status' => false, 'commission_inis' => $commisioninis, 'conversion' => $conversion));
            }
        } catch (Exception $e)  {
            return response()->json(array('activities' => array(), 'message' => $e->getMessage(), 'input_data' => $input, 'status' => false, 'commission_inis' => $commisioninis, 'conversion' => $conversion));
        }
    }

    public function activities(Request $request) {

        Session::put('selectedTab', 'activities');

        return view('search.activities.activities')->with(['activities' => "", 'currencies' => "", 'hotelDetails' => ""]);
    }

    public function checkAvailability(Request $request) {

        $postJson = file_get_contents('php://input');
        $postArray = json_decode($postJson, true);

        $this->api = new TBOActivityAPI();

        $postData = [
            "ResultIndex" => $postArray['ResultIndex'],
            "TraceId" => $postArray['TraceId'],
            "EndUserIp" => $this->api->userIP,
            "TokenId" => $this->api->tokenId,
        ];


        $this->activities = $this->api->activityGetAvailability($postData);

        if ($this->activities['Response']['ResponseStatus'] == 1) {


            $fileName = "avail_" . $postArray['search_id'] . '_' . $postArray['ResultIndex'];
            $fileContents = json_encode($this->activities['Response']['SightseeingSearchResult']);
            $this->saveSearchData($fileName . '.json', $fileContents);

            return response()->json(array('activities' => $this->activities['Response']['SightseeingSearchResult'], 'traceId' => $this->activities['Response']['TraceId'], 'input_data' => $postArray, 'price_arr' => $this->activities['Response']['SightseeingSearchResult']['TourPlan'][0]['Price'], 'status' => true));

        } else {

            return response()->json(array('activities' => array(), 'message' => $this->activities['Response']['Error']['ErrorMessage'], 'input_data' => $postArray, 'status' => false));
        }
    }

    public function blockActivity(Request $request) {


        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));        

        } else {
        
            $location = \Location::get($this->end_user_ip);            
        }

        //if ($request->isMethod('post')) {

            $input = $request->all();

            //echo "<pre>"; print_r($input);die;

            $referral = $request->referral;
            
            $search_id = $request->search_id;
            

        $destinationPath=public_path()."/logs/searches/activity/" . $search_id . ".json";
        if (file_exists($destinationPath)){

            $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
            $input = $search_contents['request'];
            
            $fileNameTours = "avail_" . $search_id . '_' . $request->result_index;
            $search_contents_tours = json_decode($this->readSearchData($fileNameTours.'.json'), true);

          
            $tourImage = $search_contents_tours['ImageList'][0];
            foreach($search_contents_tours['TourPlan'] as $tour) {
                if($tour['TourIndex'] == $request->tour_index) {
                    $price_arr = $tour['Price'];
                }
            }

            foreach($search_contents['response'] as $act) {
                if($act['ResultIndex'] == $request->result_index) {
                    $isPan = $act['IsPANMandatory'];
                }
            }

            $childAges = array();
            for ($i = 1; $i <= $input['childsCCA']; $i++) {

                if ($input['childsCCA'] > 0) {
                    array_push($childAges, $input['childAge'][$i - 1]);
                } else {
                    $childAges = null;
                }
            }
            $activityPassengerArr = array();

            array_push($activityPassengerArr, array(
                'AgeBandIndex' => 1,
                'BandDescription' => 'Adult',
                'BandQuantity' => $input['adultsCCA'],
                'IsAgeRequired' => false,
                'MaximumCount' => $input['adultsCCA'],
                'MinimumCount' => 0
                    )
            );
            if ($input['childsCCA'] > 0) {

                array_push($activityPassengerArr, array(
                    'AgeBandIndex' => 2,
                    'BandDescription' => 'Child(Age 0-12)',
                    'BandQuantity' => $input['childsCCA'],
                    'IsAgeRequired' => true,
                    'MinAge' => 0,
                    'Age' => $childAges,
                    'MaxAge' => 12,
                    'MaximumCount' => $input['childsCCA'],
                    'MinimumCount' => 0
                        )
                );
            }



            $this->api = new TBOActivityAPI();

            if ($input['childsCCA'] > 0) {

                $postData = [
                    "ResultIndex" => $request->result_index,
                    "TourIndex" => $request->tour_index,
                    "Price" => $price_arr,
                    "AgeBands" => $activityPassengerArr,
                    'TraceId' => $request->traceId,
                    "EndUserIp" => $this->api->userIP,
                    "TokenId" => $this->api->tokenId,
                ];
            } else {

                $postData = [
                    "ResultIndex" => $request->result_index,
                    "TourIndex" => $request->tour_index,
                    "Price" => $price_arr,
                    "AgeBands" => [array('AgeBandIndex' => 1,
                    'BandDescription' => 'Adult',
                    'BandQuantity' => $input['adultsCCA'],
                    'IsAgeRequired' => false,
                    'MaximumCount' => $input['adultsCCA'],
                    'MinimumCount' => 0)],
                    'TraceId' => $request->traceId,
                    "EndUserIp" => $this->api->userIP,
                    "TokenId" => $this->api->tokenId,
                ];
            }

            $blockActivity = $this->api->blockActivity($postData);

            $commisioninis = env('INIS_VAL_ACTIVITY');

            $conversion = env('CONVERSION_VAL_ACTIVITY');

            $commisioninisagent = env('INIS_AGENT_VAL_ACTIVITY');

            $queryValues = $request->query();
            $paidAmtILS = 0;

            if(isset($queryValues['payme_sale_id']) && $queryValues['payme_sale_id'] != ''){

                $saleID = $queryValues['payme_sale_id'];

                $paymentDetails = $this
                        ->api
                        ->checkPaymePayment(env('PAYME_KEY'), $saleID);
                $payMEDetails = $paymentDetails['items'];

                if(!empty($payMEDetails)  && $payMEDetails[0]['sale_status'] == 'completed'){

                  $paymentVal = true;

                  $destinationPath=$search_id . '_payme_form_activity.json';

                  $ilsPay = json_decode($this->readSearchDataILS($destinationPath), true);///Session::get('BookRoomDetails');

                  $ilsPayDetails = $ilsPay['request'];

                  //$paidAmtILS = ($ilsPayDetails['paidAmtILS'] == 0) ? ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'];

                  // Add money to wallet on activity 

                  if($ilsPayDetails['paymentMode'] == 'multiple'){

                    $paidAmtILS = ($ilsPayDetails['paidAmtILS'] == 0) ? ($ilsPayDetails['partAmountILS']) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($ilsPayDetails['partAmountILS']) + $ilsPayDetails['walletDebit'];

                    $paidAmtILSMultiple = ($ilsPayDetails['paidAmtILS'] == 0) ? ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'];

                    Session::put('multiplePayments', $paidAmtILSMultiple);

                  }else{
                    
                    $paidAmtILS = ($ilsPayDetails['paidAmtILS'] == 0) ? ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'];

                  }

                    if(Auth::user() && $ilsPayDetails['paymentMode'] == 'single') {

                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');
                        $pAmount = Currency::convert($ccrcy, 'USD', round($paidAmtILS));

                        $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);
                    }



                  //echo "<pre>";print_r($ilsPayDetails);die;

                  //echo $paidAmtILS;die;

                  $pendingAmount = $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME'] - $paidAmtILS;

                  if($ilsPayDetails['walletDebit'] > 0){

                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');
                        $pAmount = Currency::convert($ccrcy, 'USD', $ilsPayDetails['walletDebit']);

                        $walletuser->withdraw(round($pAmount['convertedAmount']), ['description' => "Card payment " . $ilsPayDetails['walletDebit'] . "(" . $ccrcy . ") received from card for Multiple Payment transaction_id :- " . $payMEDetails[0]['transaction_id']]);

                   }

                  if($pendingAmount <= 0) {

                        $cabId = $this->bookActivityILS($ilsPayDetails);

                        if($ilsPayDetails['paymentMode'] == 'single'){

                            if($ilsPayDetails['installments'] != '1'){

                                $installmentsValue =  0.5 * $ilsPayDetails['installments'];

                                $deductamount = $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME'] + ( ( $installmentsValue / 100 )  * $ilsPayDetails['ORIGINAL_BOOKING_PRICE']);

                            }else{
                                 $deductamount = $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME'];
                            }
                           
                            $myCurrency = Session::get('CurrencyCode');
                            $usercurrency = Currency::convert($myCurrency, 'USD', ($deductamount));
                            $debitAmnt = round($usercurrency['convertedAmount']);

                            $walletAmount = \Auth::user()->balance;

                            if($debitAmnt > $walletAmount){

                                $debitAmnt = $walletAmount;
                            }

                            $walletuser = \Auth::user();
                            $walletuser->withdraw($debitAmnt, ['description' => 'Payment withdraw from account for single activity booking.']);
                            
                            $walletAmount = \Auth::user()->balance;
                            Session::forget('walletAmount');
                        }



                        if(isset($cabId['success']) && $cabId['success']){

                          return redirect('/thankyou/activity/' . $cabId['booking_id'] . '/true');

                        }else{

                            //add oney to wallet
                            // $walletuser = \Auth::user();
                            // $pAmount = Currency::convert('ILS', 'USD', $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME']);
                            // $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME']]);

                            $amt = Session::get('multiplePayments');

                            if(isset($amt) && $amt > 0){

                                $walletuser = \Auth::user();
                                $ccrcy = Session::get('CurrencyCode');

                                $walletAmount = \Auth::user()->balance;

                                $amt = $amt;

                                $pAmount = Currency::convert($ccrcy, 'USD', round($amt));

                                $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);

                                Session::forget('multiplePayments');
                            }

                            return view('500')->with(['error' => $cabId['message']]);  
                        }
                  }


                }else{

                    $paymentVal = false;

                    $destinationPath=$search_id . '_payme_form_activity.json';

                    $ilsPay = json_decode($this->readSearchDataILS($destinationPath), true);///Session::get('BookRoomDetails');

                    $ilsPayDetails = $ilsPay['request'];

                    $this->writePaymeLogs($ilsPayDetails, $payMEDetails);

                    Mail::to(env('NO_RESULT_EMAIL'))->send(new FailedPaymentEmail(json_encode($ilsPayDetails), json_encode($payMEDetails) ));

                    return view('500')->with(['error' => 'Payment Failed']);
                }
            }

            if ($blockActivity['Response']['ResponseStatus'] == 1) {

                

                $blockResultsPrice = $blockActivity['Response']['SightseeingBlockResult']['TourPlan']['Price'];

                $tdsVal = ((env('INIS_TDS') / 100) * ( $blockResultsPrice['OfferedPriceRoundedOff'] ));
                $inis_markup = (($commisioninis / 100) * ( $blockResultsPrice['OfferedPriceRoundedOff'] + $tdsVal ));
                
                $inis_markup_agent = $inis_markup + $blockResultsPrice['OfferedPriceRoundedOff'] + $tdsVal;

                if($referral !='' && $referral !='0') {
                    $inis_markup_agent = (($commisioninisagent / 100) * ( $blockResultsPrice['OfferedPriceRoundedOff'] + $tdsVal ));
                    $inis_markup_agent = $inis_markup_agent + $blockResultsPrice['OfferedPriceRoundedOff'] + $tdsVal;
                }

                $price_with_markup = $inis_markup + $blockResultsPrice['OfferedPriceRoundedOff'] + $tdsVal;

                if($input['currency'] == 'ILS'){

                    $inis_markup = ((env('INIS_VAL_PAYME') / 100) * ($blockResultsPrice['OfferedPriceRoundedOff'] + $tdsVal ));
                    $price_with_markup = $inis_markup + $blockResultsPrice['OfferedPriceRoundedOff'] + $tdsVal;

                    $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;

                    $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                    $price_with_markup = $price_with_markup + env('PAYME_FIX_FEES') + $vat + $taxes;

                    $inis_markup_agent = $inis_markup + $blockResultsPrice['OfferedPriceRoundedOff'] + $tdsVal;

                }else{

                    $taxes = ($conversion / 100) * $price_with_markup;
                    $price_with_markup = $price_with_markup + $taxes;
                }

                $inis_markup_view = $inis_markup + $blockResultsPrice['OfferedPriceRoundedOff'] + $tdsVal;


                return view('search.activities.view-activity')->with(['activities' => $blockActivity['Response']['SightseeingBlockResult'], 'tourImage' => $tourImage, 'traceId' => $request->traceId, "TourIndex" => $request->tour_index, 'ResultIndex' => $request->result_index, 'currency_code' => "INR", 'adultCount' => $input['adultsCCA'], 'ChildCount' => $input['childsCCA'], 'ispan' => $isPan, 'location' => $location, 'referral' => $referral, 'commission_inis' => $commisioninis, 'conversion' => $conversion, 'commisioninisagent' => $commisioninisagent, 'price_changed' => $blockActivity['Response']['IsPriceChanged'] ,'input' => $input, 'search_id' => $request->search_id, 'FinalPrice' => $price_with_markup, 'inis_markup_view' => $inis_markup_view, 'inis_markup_agent' => $inis_markup_agent, 'paidAmtILS' => $paidAmtILS]);
            } else {

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


                return view('500')->with(['error' => $blockActivity['Response']['Error']['ErrorMessage']]);
            }

        } else {
            // Send Money back to Wallet for Single Payment
            $queryValues = $request->query();
            $saleID = $queryValues['payme_sale_id'];

            $this->api = new TBOActivityAPI();

            $paymentDetails = $this
                   ->api
                   ->checkPaymePayment(env('PAYME_KEY'), $saleID);
            $payMEDetails = $paymentDetails['items'];

            if(!empty($payMEDetails) && $payMEDetails[0]['sale_status'] == 'completed'){


                $destinationPath=$search_id . '_payme_form_activity.json';
                $ilsPay = json_decode($this->readSearchDataILS($destinationPath), true); ///Session::get('BookRoomDetails');
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

            return view('500')->with(['error' => 'Session Expired.']);
        }
    }


    public function bookActivityILS($data) {

        if (Auth::check()) {

            $user = User::where('id', Auth::user()->id)->first();
        } else {
            $user = array('name' => '', 'email' => '', 'phone' => '', 'address' => '');
        }

        $actPassengerArr = array();

        //if ($request->isMethod('post')) {

            $input = $data;

            //$cabs = Session::get('Cabs');
            //$cab_name = '';
            $adultCount = $input['adultsCCA'];
            $childCount = $input['childsCCA'];

            if ($input['price_block_null'] == 'no') {

                $priceArray = null;
            } else {

                $priceArray = json_decode($input['price_block'], true);
            }


            if ($input['referral'] != '0') {

                $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();
                if (isset($checkrefferal)) {

                    //$commisioninis = $checkrefferal['commission'];
                    $agent_id = $checkrefferal['user_id'];
                    $agentemail = User::select('email')->where(['id' => $agent_id])->first();
                    $agentemail = $agentemail['email'];
                } else {
                    $agentemail = '';
                    $agentemail = '';
                    $agent_id = '';
                    // $commisioninis = env('INIS_VAL');
                }
            } else {

                $agentemail = '';
                $agentemail = '';
                $agent_id = '';
            }


            $search_id = $input['search_id'];
            $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
            $actsearchDataB = $search_contents['request'];

            //$actsearchDataB = Session::get('ActivitiesSearchData');

            $childAges = array();
            for ($i = 1; $i <= $input['childsCCA']; $i++) {

                if ($input['childsCCA'] > 0) {
                    array_push($childAges, $actsearchDataB['childAge'][$i - 1]);
                } else {
                    $childAges = null;
                }
            }




        for ($ad=1; $ad <= $adultCount ; $ad++) {

            array_push($actPassengerArr, array(
                                                    "Title" =>  $input['adult_title_'.$ad],
                                                    "FirstName" => $input['adult_passenger_first_name_'.$ad],
                                                    "LastName" => $input['adult_passenger_last_name_'.$ad],
                                                    "PaxType" => 1,
                                                    "Age" => 28,
                                                    "LeadPassenger" =>  ($ad == 1) ? true : false,
                                                    "Phoneno" => $input['adult_phone_'.$ad],
                                                    "PassportNo" => $input['adult_passport_no_'.$ad],
                                                    "Email" => $input['adult_passenger_email_'.$ad],
                                                    "AgeBandIndex" => 1,
                                                    "PAN" => ($input['adult_pan_no_'.$ad]) ? $input['adult_pan_no_'.$ad] : "",
                                                    "PaxId" => 0,
                                                    "DateOfBirth" => ""
                                      ));
        }

        for ($ch=1; $ch <= $childCount ; $ch++) {

            array_push($actPassengerArr, array(
                                                    "Title" =>  $input['child_title_'.$ch],
                                                    "FirstName" => $input['child_passenger_first_name_'.$ch],
                                                    "LastName" => $input['child_passenger_last_name_'.$ch],
                                                    "PaxType" => 2,
                                                    "Age" => $childAges[$ch-1],
                                                    "LeadPassenger" =>  false,
                                                    "Phoneno" => $input['child_phone_'.$ch],
                                                    "PassportNo" => "",
                                                    "Email" => $input['child_passenger_email_'.$ch],
                                                    "AgeBandIndex" => 2,
                                                    "PAN" => "",
                                                    "PaxId" => 1,
                                                    "DateOfBirth" => date('Y-m-d', strtotime($input['child_dob_' . $ch])) . "T00:00:00"
                                      ));
        }



            $book_request = array(
                'SightseeingCode' => $input['SightseeingCode'],
                //'TourLanguages' => "",
                //'PassengerInfo' => null,
                'Passenger' => $actPassengerArr,
                'GuestNationality' => "IN",
                'IsVoucherBooking' => true,
                'Price' => $priceArray,
                //'IsBaseCurrencyRequired' => false,
                // 'IsCorporate' => false,
                "ResultIndex" => $input['ResultIndex'],
                "TourIndex" => $input['TourIndex'],
                'EndUserIp' => $this->api->userIP,
                'TokenId' => $this->api->tokenId,
                'TraceId' => $input['trace_id']);

            $partners_commision = env('PARTNER_COMMISION_ACTIVITY');

            if ($input['referral'] && $input['referral'] != '' && $input['referral'] != '0') {

                $commission_agent = $input['amount_commission_agent'] - $input['amount_tbo'];

                $rest_commision = ( $input['amount_commission'] - $input['amount_tbo'] ) - ( $input['amount_commission_agent'] - $input['amount_tbo'] );

                $partners_commision = ( ($partners_commision / 100) * $rest_commision);

                //$partners_commision = 

            } else {
                $commission_agent = $input['amount_commission'] - $input['amount_tbo'];

                $partners_commision = ( ( $partners_commision / 100 ) * $commission_agent);
            }


            try {

                /*
                 * First check if customer exists in the DB
                 */
                // $user = User::where('email', $input['adult_passenger_email_1'])->first();
                if(Auth::user()) {
                    $user = Auth::user();
                } else {
                    $user = User::where('email', $input['adult_passenger_email_1'])->first();
                }
                $amount = $input['amount'] * 100;

                if (isset($user) && $user->id) {

                    Auth::login($user);
                } else {
                    //create new user
                    $password = $this->generateRandomString();
                    $user = User::create([
                                'name' => $input['adult_passenger_first_name_1'] . ' ' . $input['adult_passenger_last_name_1'],
                                'email' => $input['adult_passenger_email_1'],
                                'phone' => $input['adult_phone_1'],
                                'address' => ' ',
                                'role' => 'user',
                                'password' => Hash::make($password),
                                'password_changed' => 1,
                    ]);
                    Auth::login($user);
                    Mail::to($input['adult_passenger_email_1'])->send(new NewUserRegister($user, $password));
                }

                $pAmount = Currency::convert('ILS', 'USD', round($input['ORIGINAL_BOOKING_PRICE_PME']));

                // if (isset($input['walletPay']) && $input['walletPay'] == 'no') {
                //   $walletuser = \Auth::user();
                //   $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $input['ORIGINAL_BOOKING_PRICE_PME']]);
                // }


                $bookRActivityData = $this->api->bookActivity($book_request);

                if (isset($bookRActivityData['Response']) && isset($bookRActivityData['Response']['ResponseStatus']) && $bookRActivityData['Response']['ResponseStatus'] == 1) {


                    //echo "<pre>";print_r($walletuser);die;
                    // if (isset($input['walletPay']) && $input['walletPay'] == 'yes') {

                    //     $pAmount = Currency::convert('ILS', 'USD', round($input['walletDebit']));

                    //     $walletuser = \Auth::user();
                    //     $walletuser->withdraw($pAmount['convertedAmount'], ['BookingID' => $bookRActivityData['Response']['Itinerary']['BookingId']]);

                    // }else{
                    //     $pAmount = Currency::convert('ILS', 'USD', round($input['ORIGINAL_BOOKING_PRICE_PME']));
                    //     $walletuser = \Auth::user();
                    //     $walletuser->withdraw($pAmount['convertedAmount'], ['BookingID' => $bookRActivityData['Response']['Itinerary']['BookingId']]);
                    // } 
                    
                                        
                    // $walletAmount = \Auth::user()->balance;
                    // Session::put('walletAmount', $walletAmount);



                    $postData = [
                        "BookingId" => $bookRActivityData['Response']['Itinerary']['BookingId'],
                        "AgencyId" => $this->api->agencyId,
                        "EndUserIp" => $this->api->userIP,
                        "TokenId" => $this->api->tokenId,
                    ];

                    //$bookRDetailCabData = $this->api->bookDetailCab($book_request);
                    $request_array = array_merge($book_request, $input);


                    $lAmount= Currency::convert('ILS', 'USD', $commission_agent);
                    $commission_agent = $lAmount['convertedAmount'];

                    // $input['amount'] = $amount / 100;
                    $booking = Bookings::create(['booking_id' => $bookRActivityData['Response']['Itinerary']['BookingId'],
                                'type' => 'activity',
                                'trace_id' => $bookRActivityData['Response']['TraceId'],
                                'user_id' => Auth::user()->id,
                                'token_id' => $this->api->tokenId,
                                'status' => 'Booked',
                                'hotel_booking_status' => $bookRActivityData['Response']['Itinerary']['BookingStatus'],
                                'invoice_number' => '',
                                'confirmation_number' => $bookRActivityData['Response']['Itinerary']['ConfirmationNo'],
                                'booking_ref' => $bookRActivityData['Response']['Itinerary']['BookingRefNo'],
                                'price_changed' => 0,
                                'cancellation_policy' => $bookRActivityData['Response']['Itinerary']['CancellationPolicy'],
                                'last_cancellation_date' => $bookRActivityData['Response']['Itinerary']['LastCancellationDate'],
                                'request_data' => json_encode($request_array)]);

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', ($amount / 100));
                    $mAmountC = $mAmount['convertedAmount'];

                    if(Session::get('CurrencyCode') == 'USD'){

                        $convertPrtnrAmount = $partners_commision;

                    }else{

                        $convertPrtnr = Currency::convert(Session::get('CurrencyCode'), 'USD', $partners_commision);
                        $convertPrtnrAmount = $convertPrtnr['convertedAmount'];
                    }

                    //create entry to payments table
                    $payments = Payments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'agent_id' => $agent_id,
                                'commission' => $commission_agent,
                                'price' => $amount / 100,
                                'price_convered' => $mAmountC,
                                'agent_markup' => '',
                              //  'partners_commision' => $convertPrtnrAmount,
                               // 'partners_commision_rest' => $convertPrtnrAmount,
                                'customer_id' => '',
                                'sub_domain' => '']);

                    if(isset($agent_id) && $agent_id != ''){ 

                        $post_content = "New activity booking for <b>" . $request_array['SightseeingName'] . "</b> from <b>". date('l, F jS, Y', strtotime(str_replace('/' , '-', $request_array['from_date']) )) . "</b>.<br> Total paid <b>" . $request_array['currency'] . ' ' . number_format($payments->price,2) . '</b>';

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                                  'type' => 'activity',
                                  'description' => $post_content,
                                  'price' => ' USD ' . number_format($commission_agent,2),
                                  'status' => 0
                              ]);
                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => $input['TourImage'],
                                  'user_id' => Auth::user()->id]);
                    }

                    $booking->request_data = json_decode($booking->request_data, true);


                    Mail::to($input['adult_passenger_email_1'])->send(new ActivityBookingEmail($booking, '', $payments, '', ''));

                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                        Mail::to($agentemail)->send(new ActivityBookingEmail($booking, '', $payments, '', ''));
                    }

                    // return redirect('/thankyou/activity/' . $booking->id . '/true'); //redirect('/user/bookings');
                    return array('success' => true, 'booking_id' => $booking->id);
                } else {
                    // return view('search.cabs.view-cab')->with(['message' => $message]);
                    // return view('500')->with(['error' => $bookRActivityData['Response']['Error']['ErrorMessage']]);
                    return array('success' => false, 'message' => $bookRActivityData['Response']['Error']['ErrorMessage']);
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

            if (isset($message) && $message != '') {
                // Session::flash('error', $message);
                //return view('500')->with(['error' => $message]);
                return array('success' => false, 'booking_id' => $message);
            }
            // print_r($bookRCabData); die();
            //return view('search.cabs.view-cab')->with(['message' => $message]);
       // }

        //return redirect('/user/bookings');
    }

    public function bookActivity(Request $request) {

        if (Auth::check()) {

            $user = User::where('id', Auth::user()->id)->first();
        } else {
            $user = array('name' => '', 'email' => '', 'phone' => '', 'address' => '');
        }

        $actPassengerArr = array();

        if ($request->isMethod('post')) {

            $input = $request->all();

            //$cabs = Session::get('Cabs');
            //$cab_name = '';
            $adultCount = $input['adultsCCA'];
            $childCount = $input['childsCCA'];

            if ($input['price_block_null'] == 'no') {

                $priceArray = null;
            } else {

                $priceArray = json_decode($input['price_block'], true);
            }


            if ($input['referral'] != '0') {

                $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();
                if (isset($checkrefferal)) {

                    //$commisioninis = $checkrefferal['commission'];
                    $agent_id = $checkrefferal['user_id'];
                    $agentemail = User::select('email')->where(['id' => $agent_id])->first();
                    $agentemail = $agentemail['email'];
                } else {
                    $agentemail = '';
                    $agentemail = '';
                    $agent_id = '';
                    // $commisioninis = env('INIS_VAL');
                }
            } else {

                $agentemail = '';
                $agentemail = '';
                $agent_id = '';
            }


            $search_id = $input['search_id'];
            $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
            $actsearchDataB = $search_contents['request'];

            //$actsearchDataB = Session::get('ActivitiesSearchData');

            $childAges = array();
            for ($i = 1; $i <= $input['childsCCA']; $i++) {

                if ($input['childsCCA'] > 0) {
                    array_push($childAges, $actsearchDataB['childAge'][$i - 1]);
                } else {
                    $childAges = null;
                }
            }




        for ($ad=1; $ad <= $adultCount ; $ad++) {

            array_push($actPassengerArr, array(
                                                    "Title" =>  $input['adult_title_'.$ad],
                                                    "FirstName" => $input['adult_passenger_first_name_'.$ad],
                                                    "LastName" => $input['adult_passenger_last_name_'.$ad],
                                                    "PaxType" => 1,
                                                    "Age" => 28,
                                                    "LeadPassenger" =>  ($ad == 1) ? true : false,
                                                    "Phoneno" => $input['adult_phone_'.$ad],
                                                    "PassportNo" => $input['adult_passport_no_'.$ad],
                                                    "Email" => $input['adult_passenger_email_'.$ad],
                                                    "AgeBandIndex" => 1,
                                                    "PAN" => ($input['adult_pan_no_'.$ad]) ? $input['adult_pan_no_'.$ad] : "",
                                                    "PaxId" => 0,
                                                    "DateOfBirth" => ""
                                      ));
        }

       for ($ch=1; $ch <= $childCount ; $ch++) {

            array_push($actPassengerArr, array(
                                                    "Title" =>  $input['child_title_'.$ch],
                                                    "FirstName" => $input['child_passenger_first_name_'.$ch],
                                                    "LastName" => $input['child_passenger_last_name_'.$ch],
                                                    "PaxType" => 2,
                                                    "Age" => $childAges[$ch-1],
                                                    "LeadPassenger" =>  false,
                                                    "Phoneno" => $input['child_phone_'.$ch],
                                                    "PassportNo" => "",
                                                    "Email" => $input['child_passenger_email_'.$ch],
                                                    "AgeBandIndex" => 2,
                                                    "PAN" => "",
                                                    "PaxId" => 1,
                                                    "DateOfBirth" => date('Y-m-d', strtotime($input['child_dob_' . $ch])) . "T00:00:00"
                                      ));
        }



            $book_request = array(
                'SightseeingCode' => $input['SightseeingCode'],
                //'TourLanguages' => "",
                //'PassengerInfo' => null,
                'Passenger' => $actPassengerArr,
                'GuestNationality' => "IN",
                'IsVoucherBooking' => true,
                'Price' => $priceArray,
                //'IsBaseCurrencyRequired' => false,
                // 'IsCorporate' => false,
                "ResultIndex" => $input['ResultIndex'],
                "TourIndex" => $input['TourIndex'],
                'EndUserIp' => $this->api->userIP,
                'TokenId' => $this->api->tokenId,
                'TraceId' => $input['trace_id']);

            $partners_commision = env('PARTNER_COMMISION_ACTIVITY');

            if ($input['referral'] && $input['referral'] != '' && $input['referral'] != '0') {

                $commission_agent = $input['amount_commission_agent'] - $input['amount_tbo'];

                $rest_commision = ( $input['amount_commission'] - $input['amount_tbo'] ) - ( $input['amount_commission_agent'] - $input['amount_tbo'] );

                $partners_commision = ( ($partners_commision / 100) * $rest_commision);

                //$partners_commision = 

            } else {
                $commission_agent = $input['amount_commission'] - $input['amount_tbo'];

                $partners_commision = ( ( $partners_commision / 100 ) * $commission_agent);
            }


            try {

                /*
                 * First check if customer exists in the DB
                 */
                // $user = User::where('email', $input['adult_passenger_email_1'])->first();
                if(Auth::user()) {
                    $user = Auth::user();
                } else {
                    $user = User::where('email', $input['adult_passenger_email_1'])->first();
                }
                $amount = $input['amount'] * 100;

                if (isset($user) && $user->id) {

                    Auth::login($user);
                } else {
                    //create new user
                    $password = $this->generateRandomString();
                    $user = User::create([
                                'name' => $input['adult_passenger_first_name_1'] . ' ' . $input['adult_passenger_last_name_1'],
                                'email' => $input['adult_passenger_email_1'],
                                'phone' => $input['adult_phone_1'],
                                'address' => ' ',
                                'role' => 'user',
                                'password' => Hash::make($password),
                                'password_changed' => 1,
                    ]);
                    Auth::login($user);
                    Mail::to($input['adult_passenger_email_1'])->send(new NewUserRegister($user, $password));
                }

                $bookRActivityData = $this->api->bookActivity($book_request);

                if (isset($bookRActivityData['Response']) && isset($bookRActivityData['Response']['ResponseStatus']) && $bookRActivityData['Response']['ResponseStatus'] == 1) {


                    if (isset($input['walletPay']) && $input['walletPay'] == 'yes' && $input['walletDebit'] > 0) {

                        $myCurrency = Session::get('CurrencyCode');
                        $usercurrency = Currency::convert($myCurrency, 'USD', $input['walletDebit']);
                        $debitAmnt = round($usercurrency['convertedAmount']);

                        $walletuser = \Auth::user();
                        $walletuser->withdraw(round($debitAmnt), ['BookingID' => $bookRActivityData['Response']['Itinerary']['BookingId']]);
                    
                        $walletAmount = \Auth::user()->balance;
                        Session::put('walletAmount', $walletAmount);
                        
                    }



                    $postData = [
                        "BookingId" => $bookRActivityData['Response']['Itinerary']['BookingId'],
                        "AgencyId" => $this->api->agencyId,
                        "EndUserIp" => $this->api->userIP,
                        "TokenId" => $this->api->tokenId,
                    ];

                    //$bookRDetailCabData = $this->api->bookDetailCab($book_request);
                    $request_array = array_merge($book_request, $input);


                    $lAmount= Currency::convert(Session::get('CurrencyCode'), 'USD', $commission_agent);
                    $commission_agent = round($lAmount['convertedAmount']);

                    // $input['amount'] = $amount / 100;
                    $booking = Bookings::create(['booking_id' => $bookRActivityData['Response']['Itinerary']['BookingId'],
                                'type' => 'activity',
                                'trace_id' => $bookRActivityData['Response']['TraceId'],
                                'user_id' => Auth::user()->id,
                                'token_id' => $this->api->tokenId,
                                'status' => 'Booked',
                                'hotel_booking_status' => $bookRActivityData['Response']['Itinerary']['BookingStatus'],
                                'invoice_number' => '',
                                'confirmation_number' => $bookRActivityData['Response']['Itinerary']['ConfirmationNo'],
                                'booking_ref' => $bookRActivityData['Response']['Itinerary']['BookingRefNo'],
                                'price_changed' => 0,
                                'cancellation_policy' => $bookRActivityData['Response']['Itinerary']['CancellationPolicy'],
                                'last_cancellation_date' => $bookRActivityData['Response']['Itinerary']['LastCancellationDate'],
                                'request_data' => json_encode($request_array)]);

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', ($amount / 100));
                    $mAmountC = round($mAmount['convertedAmount']);

                    if(Session::get('CurrencyCode') == 'USD'){

                        $convertPrtnrAmount = $partners_commision;

                    }else{

                        $convertPrtnr = Currency::convert(Session::get('CurrencyCode'), 'USD', $partners_commision);
                        $convertPrtnrAmount = $convertPrtnr['convertedAmount'];
                    }

                    //create entry to payments table
                    $payments = Payments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'agent_id' => $agent_id,
                                'commission' => $commission_agent,
                                'price' => $amount / 100,
                                'price_convered' => $mAmountC,
                                'agent_markup' => '',
                              //  'partners_commision' => $convertPrtnrAmount,
                               // 'partners_commision_rest' => $convertPrtnrAmount,
                                'customer_id' => '',
                                'sub_domain' => '']);

                    if(isset($agent_id) && $agent_id != ''){ 

                        $post_content = "New activity booking for <b>" . $request_array['SightseeingName'] . "</b> from <b>". date('l, F jS, Y', strtotime(str_replace('/' , '-', $request_array['from_date']) )) . "</b>.<br> Total paid <b>" . $request_array['currency'] . ' ' . number_format($payments->price,2) . '</b>';

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                                  'type' => 'activity',
                                  'description' => $post_content,
                                  'price' => ' USD ' . number_format($commission_agent,2),
                                  'status' => 0
                              ]);
                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => $input['TourImage'],
                                  'user_id' => Auth::user()->id]);
                    }

                    $booking->request_data = json_decode($booking->request_data, true);


                    Mail::to($input['adult_passenger_email_1'])->send(new ActivityBookingEmail($booking, '', $payments, '', ''));

                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                        Mail::to($agentemail)->send(new ActivityBookingEmail($booking, '', $payments, '', ''));
                    }

                    return redirect('/thankyou/activity/' . $booking->id . '/true'); //redirect('/user/bookings');
                } else {
                    //add oney to wallet
                    $walletuser = \Auth::user();
                    $pAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', ($amount / 100));
                    $walletuser->deposit(round($pAmount['convertedAmount']), ["description" => 'Multicard partial payment(ILS) -' . $input['amount']]);
                    // return view('search.cabs.view-cab')->with(['message' => $message]);
                    return view('500')->with(['error' => $bookRActivityData['Response']['Error']['ErrorMessage']]);
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

            if (isset($message) && $message != '') {
                // Session::flash('error', $message);
                return view('500')->with(['error' => $message]);
            }
            // print_r($bookRCabData); die();
            return view('search.cabs.view-cab')->with(['message' => $message]);
        }

        return redirect('/user/bookings');
    }

    public function cancelActBooking(Request $request) {

        if ($request->isMethod('post')) {

            $message = '';

            $input = $request->all();

            $postData = [
                "RequestType" => 6,
                "Remarks" => $input['remarks'],
                'BookingId' => $input['booking_id'],
                "EndUserIp" => $this->api->userIP,
                "TokenId" => $input['token_id'],
                "TraceId" => $input['trace_id'],
            ];

            $sendChangeRequest = $this->api->sendChangeRequest($postData);

            if (isset($sendChangeRequest['Response']) && isset($sendChangeRequest['Response']['ResponseStatus']) && $sendChangeRequest['Response']['ResponseStatus'] == 1) {

                $postData = [
                    "ChangeRequestId" => $sendChangeRequest['Response']['ChangeRequestId'],
                    "EndUserIp" => $this->api->userIP,
                    "TokenId" => $input['token_id'],
                ];

                $getChangeRequestStatus = $this->api->getChangeRequestStatus($postData);

                if (isset($getChangeRequestStatus['Response']) && isset($getChangeRequestStatus['Response']['ResponseStatus']) && $getChangeRequestStatus['Response']['ResponseStatus'] == 1) {

                    $status = $getChangeRequestStatus['Response']['Status'];

                    $change_request_id = $getChangeRequestStatus['Response']['ChangeRequestId'];
                    $refunded_amount = $getChangeRequestStatus['Response']['RefundedAmount'];
                    $cancellation_charge = $getChangeRequestStatus['Response']['CancellationCharge'];

                    if($refunded_amount > 0) {
                        //conver refund amount to USD
                        $lAmount = Currency::convert('INR', 'USD', $refunded_amount);
                        $refunded_amount = $lAmount['convertedAmount'];
                    }

                    $statusText = Bookings::where(['booking_id' => $input['booking_id']])
                            ->update(['refunded_amount' => $refunded_amount, 'change_request_id' => $change_request_id, 'cancellation_charge' => $cancellation_charge]);

                    return redirect('/user/bookings')->with('success', $message);
                } else {
                    // echo "<pre>"; print_r($getChangeRequestStatus);die();
                    return view('500')->with(['error' => $getChangeRequestStatus['Response']['Error']['ErrorMessage']]);
                }
            }
        }
    }

    public function cancelBookingStatus(Request $request) {
        if ($request->isMethod('post')) {
            $input = $request->all();
            $postData = array('TokenId' => $input['TokenId'], 'EndUserIp' => $this->api->userIP, 'ChangeRequestId' => $input['ChangeRequestId']);
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
                    ->getChangeRequestStatus($postData);
                $bookingStatus = '';
                $refunded_amount = 0;
                
                if (isset($getChangeRequest['Response']) && $getChangeRequest['Response']['ResponseStatus'] == 1) {
                    $message = '';
                    $bookingStatus = 'Confirmed';
                    if ($getChangeRequest['Response']['ChangeRequestStatus'] == 0) {
                        $message = 'Invalid request values, please try again.';
                        $bookingStatus = 'Confirmed';
                    } else if ($getChangeRequest['Response']['ChangeRequestStatus'] == 1) {
                        $message = 'Booking cancellation request is accepted but pending for processing.';
                        $bookingStatus = 'Processing';
                    } else if ($getChangeRequest['Response']['ChangeRequestStatus'] == 2) {
                        $message = 'Booking cancellation request is in progress.';
                        $bookingStatus = 'In Progress';
                    } else if ($getChangeRequest['Response']['ChangeRequestStatus'] == 3) {
                        $message = 'Booking cancellation request is completed.';
                        $bookingStatus = 'Cancelled';
                    } else if ($getChangeRequest['Response']['ChangeRequestStatus'] == 4) {
                        $message = 'Booking cancellation request is rejected.';
                        $bookingStatus = 'Rejected';
                    }

                    /*
                     * Update Booking Status
                     */
                    $refunded_amount = (isset($getChangeRequest['Response']['RefundedAmount'])) ? $getChangeRequest['Response']['RefundedAmount'] : 0;

                    if($refunded_amount > 0) {
                        //conver refund amount to USD
                        $lAmount = Currency::convert('INR', 'USD', $refunded_amount);
                        $refunded_amount = $lAmount['convertedAmount'];
                    }
                    Bookings::where(['user_id' => Auth::user()->id, 'change_request_id' => $input['ChangeRequestId']])
                            ->update(['status' => $bookingStatus, 'refunded_amount' => $refunded_amount]);
                } else {
                    $message = $getChangeRequest['Response']['Error']['ErrorMessage'];
                }

                return response()->json(array(
                            'message' => $message,
                            'bookingStatus' => $bookingStatus,
                            'refunded_amount' => $refunded_amount
                ));
            }
        }
    }

    public function sendActivitiesEmail(Request $request) {
       
        // $searchData = Session::get('ActivitiesSearchData');
        $search_id = $request->search_id;
        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
        $searchData = $search_contents['request'];

        $activities = array();
        $selected_acts = $request->data;

        $act_list = $search_contents['response'];
        $act_found = array();
        
        foreach($selected_acts as $act) {

            foreach ($act_list as $key => $activity) {
                if($activity['ResultIndex'] == $act) {
                    $act_found = $activity;
                }
            }

            array_push($activities, $act_found);
        }
        $searchData['commisioninis'] = env('INIS_VAL_ACTIVITY');
        $searchData['conversion'] = env('CONVERSION_VAL_ACTIVITY');
       
        $agent = AffiliateUsers::select('referal_code')->where('user_id', Auth::user()->id)->first();
        Mail::to($request->email)->send(new ActivitiesEmail($searchData, $activities, $agent));

         return response()->json(array(
                        'message' => "Email sent",
                        'success' => true
            ));

    }

    public function setCookie($name, $value, $time) {
        setcookie($name, $value, time() + (60 * $time), "/");
        return true;
    }
    
    public function getCookie($name) {
        return $_COOKIE[$name];
    }

    public function writePaymeLogs($requestData,$responseData) {
        Log::info(['HotelError:' => date('Y-m-d h:i:s'), 'requestData' => $requestData,'responseData' => $responseData]);
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

    public function saveSearchData($file, $content) {
        $destinationPath=public_path()."/logs/searches/activity/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function readSearchData($file) {
        $destinationPath=public_path()."/logs/searches/activity/";
        return $file = File::get($destinationPath.$file);
    }

    public function saveBlockData($file, $content) {
        $destinationPath=public_path()."/logs/searches/activity/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function getBlockData($file) {
        $destinationPath=public_path()."/logs/searches/activity/";
        return $file = File::get($destinationPath.$file);
    }

    public function readSearchDataILS($file) {
        $destinationPath=public_path()."/logs/searches/hotels/";
        return $file = File::get($destinationPath.$file);
    }

}
