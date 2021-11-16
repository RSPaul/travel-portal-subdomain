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
use App\Models\Payments;
use App\Models\FlightPayments;
use App\Models\Currencies;
use App\Models\Cities;
use App\Models\Reviews;
use App\Models\FlightBookings;
use App\Services\TBOFlightAPI;
use App\Models\AffiliateUsers;
use App\Models\NotificationAgents;
use App\Models\Posts;
use Stripe\Stripe;
use App\Mail\NewUserRegister;
use App\Mail\FlightBookingEmail;
use App\Mail\FlightsEmail;
use App\Mail\FailedPaymentEmail;
use PDF;
use Log;
use File;
use Currency;
use Config;

class FlightController extends Controller {

    public $end_user_ip;

    public function __construct(Request $request) {
        ini_set('max_execution_time', 240);
        $this->api = new TBOFlightAPI();

        Stripe::setApiKey(env('STRIPE_SECRET'));
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

    public function search_social_flights(Request $request){


        //print_r($request);die;
        if($request->returnDate == '0'){
            $request->returnDate = '';
        }

        return redirect('/flights?_token=eOLr2gOmGFC4aglC5ijMQ4FC8ajXJqgM0lLeNXYi&JourneyType='. $request->JourneyType .'&origin='. $request->origin .'&from='. $request->from .'&destination='. $request->destination .'&to='. $request->to .'&departDate='. $request->departDate .'&returnDate='. $request->returnDate .'&travellersClass='. $request->travellersClass .'&referral='. $request->referral .'&adultsF='. $request->adultsF .'&childsF='. $request->childsF .'&infants=0&FlightCabinClass=1&DirectFlight=false&OneStopFlight=false&results=true');
    }

    public function search(Request $request) {
        $flights = array();
        $this->temp = 'set value ';

        $isAgent = false;
        // if(Auth::user()) {

        //     $agent = AffiliateUsers::select('user_id')->where('user_id', Auth::user()->id)->first();

        //     if(isset($agent) && !empty($agent)) {
        //         $isAgent = true;
        //     }
        // }
        return view('search.flights.flights')->with(['flights' => $flights, 'input' => $request->all(), 'referral' => $_GET['referral'], 'isAgent' => $isAgent]);
    }

    public function searchFlights(Request $request) {


        //$input = $request->all();
        $postJson = file_get_contents('php://input');
        $postArray = json_decode($postJson, true);
        $input = array();
        foreach ($postArray as $post) {
            foreach ($post as $key => $p) {
                $input[$key] = urldecode($p);
            }
        }

        $Segments = array();
        Session::put('active_tab', 'flights');
        Session::put('flightSearhData', $input);


        //$location = \Location::get($this->end_user_ip);
        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {

            $location = \Location::get($this->end_user_ip);
        }

        $country = $location->countryCode;

        //get user currency
        $currencyCode = Currencies::select('currency_code', 'name')->where('code', $country)->first();

        $ourCurrency = Config::get('ourcurrency');

        if (in_array($currencyCode['currency_code'], $ourCurrency)) {
            $input['currency'] = $currencyCode['currency_code'];
        } else {
            $input['currency'] = 'USD';
        }

        Session::put('currency', $input['currency']);


        if ($input['JourneyType'] == '1') {

            $Segments = [
                "Origin" => $input['origin'],
                "Destination" => $input['destination'],
                "FlightCabinClass" => $input['FlightCabinClass'],
                "PreferredDepartureTime" => date('Y-m-d', strtotime($input['departDate'])) . "T00:00:00",
                "PreferredArrivalTime" => date('Y-m-d', strtotime($input['departDate'] . ' +1 day')) . "T00:00:00"
            ];
        } else {

            array_push($Segments, array(
                "Origin" => $input['origin'],
                "Destination" => $input['destination'],
                "FlightCabinClass" => $input['FlightCabinClass'],
                "PreferredDepartureTime" => date('Y-m-d', strtotime($input['departDate'])) . "T00:00:00",
                "PreferredArrivalTime" => date('Y-m-d', strtotime($input['departDate'] . ' +1 day')) . "T00:00:00"
            ));
            array_push($Segments, array(
                "Origin" => $input['destination'],
                "Destination" => $input['origin'],
                "FlightCabinClass" => $input['FlightCabinClass'],
                "PreferredDepartureTime" => date('Y-m-d', strtotime($input['returnDate'])) . "T00:00:00",
                "PreferredArrivalTime" => date('Y-m-d', strtotime($input['returnDate'] . ' +1 day')) . "T00:00:00"
            ));
        }


        if ($input['JourneyType'] == '1') {
            $postData = [
                "EndUserIp" => $this->api->userIP,
                "TokenId" => $this->api->tokenId,
                "AdultCount" => $input['adultsF'],
                "ChildCount" => (isset($input['childsF']) && $input['childsF'] != null) ? $input['childsF'] : "0",
                "InfantCount" => (isset($input['infantsF']) && $input['infantsF'] != null) ? $input['infantsF'] : "0",
                "DirectFlight" => $input['DirectFlight'],
                "OneStopFlight" => $input['OneStopFlight'],
                "PreferredCurrency" => $input['currency'],
                "JourneyType" => $input['JourneyType'],
                "PreferredAirlines" => null,
                "Segments" => [$Segments],
                "Sources" => null
            ];
        } else {
            $postData = [
                "EndUserIp" => $this->api->userIP,
                "TokenId" => $this->api->tokenId,
                "AdultCount" => $input['adultsF'],
                "ChildCount" => (isset($input['childsF']) && $input['childsF'] != null) ? $input['childsF'] : "0",
                "InfantCount" => (isset($input['infantsF']) && $input['infantsF'] != null) ? $input['infantsF'] : "0",
                "DirectFlight" => $input['DirectFlight'],
                "OneStopFlight" => $input['OneStopFlight'],
                "PreferredCurrency" => $input['currency'],
                "JourneyType" => $input['JourneyType'],
                "PreferredAirlines" => null,
                "Segments" => $Segments,
                "Sources" => null
            ];
        }

        // echo "<pre>";print_r($postData);echo "</pre>";die;


        $flights = $this->api->search($postData);

        if (isset($flights['Response']) && isset($flights['Response']['ResponseStatus']) && $flights['Response']['ResponseStatus'] == 1) {

            $fileName = time() + (60 * 13);
            $postData['input'] = $input;
            $fileContents = json_encode(array('request' => $postData));
            $this->saveSearchData($fileName . '.json', $fileContents);


            $this->setCookie('flight_session', time() + (60 * 13), 20);

            if ($input['referral'] != '' && $input['referral'] != '0') {

                $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();

                $commisioninis = env('INIS_VAL_FLIGHT');
            } else {

                $commisioninis = env('INIS_VAL_FLIGHT');
            }

            $conversion = env('INR_FEES');
            $int_conversion = env('INT_FEES');

            $ils_commission = env('INIS_VAL_PAYME');
            $ils_fees = env('PAYME_FEES');
            $ils_vat = env('INIS_VAL_VAT');
            $ils_fixfees = env('PAYME_FIX_FEES');


            foreach ($flights['Response']['Results'][0] as $key => $value) {
                # code...

                $inis_markup_flight = (($commisioninis / 100) * $value['Fare']['OfferedFare']);
                $price_with_markup_flight = $inis_markup_flight + $value['Fare']['OfferedFare'];

                //$taxes_flight = ($commisioninis_currency / 100) * $price_with_markup_flight;
                //$flightPrice = $inis_markup_flight + $taxes_flight + $fprice;

                if($input['currency'] == 'ILS'){

                    $inis_markup_flight = ((env('INIS_VAL_PAYME') / 100) * $value['Fare']['OfferedFare']);
                    $price_with_markup_flight = $inis_markup_flight + $value['Fare']['OfferedFare'];

                    $taxes_flight = (env('PAYME_FEES') / 100) * $price_with_markup_flight;
                    $flightPrice = $inis_markup_flight + $taxes_flight + $value['Fare']['OfferedFare'];
                    //$taxes = $taxes + env('PAYME_FIX_FEES');
                    $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes_flight + env('PAYME_FIX_FEES') );

                    $flights['Response']['Results'][0][$key]['Fare']['FinalPrice'] = $flightPrice + env('PAYME_FIX_FEES') + $vat;

                }else{

                    $taxes_flight = ($conversion / 100) * $price_with_markup_flight;
                    $flights['Response']['Results'][0][$key]['Fare']['FinalPrice'] = $inis_markup_flight + $taxes_flight + $value['Fare']['OfferedFare'];
                }

            }

            // Return Price calculate final Price 

            if(isset($flights['Response']['Results'][1])){


                foreach ($flights['Response']['Results'][1] as $key => $value) {
                    # code...

                    $inis_markup_flight = (($commisioninis / 100) * $value['Fare']['OfferedFare']);
                    $price_with_markup_flight = $inis_markup_flight + $value['Fare']['OfferedFare'];

                    //$taxes_flight = ($commisioninis_currency / 100) * $price_with_markup_flight;
                    //$flightPrice = $inis_markup_flight + $taxes_flight + $fprice;

                    if($input['currency'] == 'ILS'){

                        $inis_markup_flight = ((env('INIS_VAL_PAYME') / 100) * $value['Fare']['OfferedFare']);
                        $price_with_markup_flight = $inis_markup_flight + $value['Fare']['OfferedFare'];

                        $taxes_flight = (env('PAYME_FEES') / 100) * $price_with_markup_flight;
                        $flightPrice = $inis_markup_flight + $taxes_flight + $value['Fare']['OfferedFare'];
                        //$taxes = $taxes + env('PAYME_FIX_FEES');
                        $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes_flight + env('PAYME_FIX_FEES') );

                        $flights['Response']['Results'][1][$key]['Fare']['FinalPrice'] = $flightPrice + $vat;

                    }else{

                        $taxes_flight = ($conversion / 100) * $price_with_markup_flight;
                        $flights['Response']['Results'][1][$key]['Fare']['FinalPrice'] = $inis_markup_flight + $taxes_flight + $value['Fare']['OfferedFare'];
                    }

                }

            }
           
            return response()->json(array('flights' => $flights['Response'], 'input_data' => $input, 'status' => true, 'commission_inis' => $commisioninis, 'conversion' => $conversion, 'search_id' => $fileName, 'currency' => $input['currency'] , 'ils_commission' => $ils_commission, 'ils_fees' => $ils_fees, 'ils_vat' => $ils_vat, 'ils_fixfees' => $ils_fixfees));
        } else {
            $message = $flights['Response']['Error']['ErrorMessage'];
            // return view('500')->with(['error' => $message]);
            return response()->json(array('flights' => $message, 'input_data' => $input, 'status' => false, 'commission_inis' => '', 'conversion' => '' ,'currency' => '' , 'ils_commission' => '', 'ils_fees' => '', 'ils_vat' => '', 'ils_fixfees' => '' ));
        }
    }

    public function viewFlight(Request $request) {

        $traceID = $request->traceId;
        $resultOBIndex = $request->OBindex;
        $referral = $request->referral;
        $search_id = $request->searchId;

        // $location = \Location::get($this->end_user_ip);
        if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {
            
            $location = json_decode($this->getCookie('th_country'));

        } else {
            $location = \Location::get($this->end_user_ip);
        }

        //$country = $location->countryCode;

        if ($request->IBindex && $request->IBindex != '') {
            $resultIBIndex = $request->IBindex;
        } else {
            $resultIBIndex = '';
        }

        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
        $flightSearch = $search_contents['request']['input'];
        //$flightSearch = Session::get('flightSearhData');
        //print_r($flightSearch);die;
        $flightSearch['travellersClass'] = str_replace("+", " ", $flightSearch['travellersClass']);
        $adultCount = ($flightSearch['adultsF'] != null) ? $flightSearch['adultsF'] : "1";
        $ChildCount = ($flightSearch['childsF'] != null) ? $flightSearch['childsF'] : "0";
        $InfantCount = ($flightSearch['infants'] != null) ? $flightSearch['infants'] : "0";

        $queryValues = $request->query();
        $paymentVal = false;
        $walletuser = \Auth::user();

        if(isset($queryValues['payme_sale_id']) && $queryValues['payme_sale_id'] != ''){
            //die('Herre');
            $search_id = $request->searchId;

            $saleID = $queryValues['payme_sale_id'];

            $paymentDetails = $this
                    ->api
                    ->checkPaymePayment(env('PAYME_KEY'), $saleID);
            $payMEDetails = $paymentDetails['items'];

            if(!empty($payMEDetails) && $payMEDetails[0]['transaction_id'] != '' || $payMEDetails[0]['sale_status'] == 'completed'){
              $paymentVal = true;
              $destinationPath=$search_id . '_payme_form_flight.json';
              $ilsPay = json_decode($this->readSearchDataILS($destinationPath), true);///Session::get('BookRoomDetails');
              $ilsPayDetails = $ilsPay['request'];

              //echo "<pre>";print_r($$ilsPayDetails);die;
              $flightId = $this->bookFlightILS($ilsPayDetails);

              if(isset($flightId['success']) && $flightId['success']){

                  return redirect('/thankyou/flight/' . $flightId['booking_id'] . '/true');
              }else{

                $deductamount = $payMEDetails[0]['sale_price'] / 100;

                if($ilsPayDetails['walletDebit'] > 0){

                    $deductamount = $deductamount + $ilsPayDetails['walletDebit'];
                }

                $myCurrency = Session::get('CurrencyCode');
                $usercurrency = Currency::convert($myCurrency, 'USD', ($deductamount));
                $debitAmnt = round($usercurrency['convertedAmount']);

                 $walletuser->deposit($debitAmnt, ["description" => 'Flight Single Payment -' . $usercurrency['convertedAmount']]);

                  return view('500')->with(['error' => $flightId['message']]);  
              }

            } else {

                $paymentVal = false;
                
                $destinationPath=$search_id . '_payme_form_flight.json';
                $ilsPay = json_decode($this->readSearchDataILS($destinationPath), true);///Session::get('BookRoomDetails');
                $ilsPayDetails = $ilsPay['request'];

                $this->writePaymeLogs($ilsPayDetails, $payMEDetails);

                Mail::to(env('NO_RESULT_EMAIL'))->send(new FailedPaymentEmail(json_encode($ilsPayDetails), json_encode($payMEDetails) ));

                return view('500')->with(['error' => 'Payment Failed']);
            }
        }


        $this->fareRuleOB = $this->api->fareRule($traceID, $resultOBIndex);
        $this->fareQuoteOB = $this->api->fareQuote($traceID, $resultOBIndex);

        /* Select Seat and Meal */    
        $this->SSR = $this->api->SSR($traceID, $resultOBIndex);

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

        /* Redirect to 500 when FareIB Quote doesnt work */
        if (isset($this->fareRuleOB['Response']) && isset($this->fareRuleOB['Response']['ResponseStatus']) && $this->fareRuleOB['Response']['ResponseStatus'] != 1) {

            $message = $this->fareRuleOB['Response']['Error']['ErrorMessage'];
            return view('500')->with(['error' => $message]);
        }

        if (isset($this->fareQuoteOB['Response']) && isset($this->fareQuoteOB['Response']['ResponseStatus']) && $this->fareQuoteOB['Response']['ResponseStatus'] != 1) {

            $message = $this->fareQuoteOB['Response']['Error']['ErrorMessage'];
            return view('500')->with(['error' => $message]);
        }
        /* Ends here */

        if ($resultIBIndex && $resultIBIndex != '') {
            $this->fareRuleIB = $this->api->fareRuleIB($traceID, $resultIBIndex);
            $this->fareQuoteIB = $this->api->fareQuoteIB($traceID, $resultIBIndex);
            $this->SSRIB = $this->api->SSRIB($traceID, $resultIBIndex);
        } else {
            $this->fareRuleIB = [];
            $this->fareQuoteIB = [];
            $this->fareRuleIB['Response'] = [];
            $this->fareQuoteIB['Response'] = [];
        }

        $baggagearrayreturnib = array();
        $mealarrayreturnib = array();

        if (isset($this->SSRIB['Response']) && isset($this->SSRIB['Response']['Baggage']) && $this->fareQuoteIB['Response']['ResponseStatus'] == 1) {
            
            $baggagearrayreturnib = $this->SSRIB['Response']['Baggage'][0];
        
        }

        if (isset($this->SSRIB['Response']) && isset($this->SSRIB['Response']['MealDynamic']) && $this->fareQuoteIB['Response']['ResponseStatus'] == 1) {
            
            $mealarrayreturnib = $this->SSRIB['Response']['MealDynamic'][0];
        
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


        if ($request->referral != '' && $request->referral != '0') {

            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request->referral])->first();


            if (isset($checkrefferal)) {

                $commisioninis = env('INIS_VAL_FLIGHT');
                $commisioninisagent = env('INIS_AGENT_VAL');
            } else {
                $agent_id = '';
                $commisioninis = env('INIS_VAL_FLIGHT');
                $commisioninisagent = env('INIS_AGENT_VAL');
            }
        } else {

            $commisioninis = env('INIS_VAL_FLIGHT');
            $commisioninisagent = env('INIS_AGENT_VAL');
        }

        $conversion = env('INR_FEES');
        $int_conversion = env('INT_FEES');


        return view('search.flights.view-flight')->with(['fareRuleOB' => $this->fareRuleOB['Response'], 'fareRuleIB' => $this->fareRuleIB['Response'], 'fareQuoteOB' => $this->fareQuoteOB['Response'], 'fareQuoteIB' => $this->fareQuoteIB['Response'], 'adultCount' => $adultCount, 'childCount' => $ChildCount, 'infantCount' => $InfantCount, 'traceID' => $traceID, 'resultOBIndex' => $resultOBIndex, 'resultIBIndex' => $resultIBIndex, 'input' => $flightSearch, 'commission' => $commisioninis, 'commisioninisagent' => $commisioninisagent, 'referral' => $referral, 'location' => $location, 'conversion' => $conversion, 'int_conversion' => $int_conversion, 'meal' => $mealarray, 'seat' => $seatarray, 'mealLCC' => $mealLCCarray, 'mealLCCreturn' => $mealLCCarrayreturn, 'baggage' => $baggagearray , 'baggagereturn' => $baggagearrayreturn, 'mealreturnib' => $mealarrayreturnib, 'baggagereturnib' => $baggagearrayreturnib,'search_id' => $search_id ]);
    }

    public function bookFlightILS($data) {

        //$input = $request->all();
         $input = $data;

        $search_contents = json_decode($this->readSearchData($input['search_id'].'.json'), true);
        $flightSearch = $search_contents['request']['input'];

        //$flightSearch = Session::get('flightSearhData');
        $flightSearch['travellersClass'] = str_replace("+", " ", $flightSearch['travellersClass']);
        if ($input['referral'] != '' && $input['referral'] != '0') {


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

        $adultCount = $flightSearch['adultsF'];
        $ChildCount = ($flightSearch['childsF'] != null) ? $flightSearch['childsF'] : "0";
        $InfantCount = ($flightSearch['infants'] != null) ? $flightSearch['infants'] : "0";

        $departDate = $flightSearch['departDate'];

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
        $travelArr['amount'] = $input['fullAmount_Install'];
        $travelArr['installment_price'] = $input['installment_price'];
        $travelArr['installments_number'] = $input['installments_number'];
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


        if ($input['referral'] && $input['referral'] != '' && $input['referral'] != '0') {

            $commission_agent = $commission_agent;
        } else {
            $commission_agent = $commission;
        }


        // $location = \Location::get($this->end_user_ip);
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

        for ($ad = 1; $ad <= $adultCount; $ad++) {

            array_push($flightPassengerArr, array(
                "Title" => $input['adult_title_' . $ad],
                "FirstName" => $input['adult_first_name_' . $ad],
                "LastName" => $input['adult_last_name_' . $ad],
                "PaxType" => 1,
                "DateOfBirth" => isset($input['adult_dob_' . $ad]) ? $input['adult_dob_' . $ad] : "1985-01-30T00:00:00",
                "Gender" => ($input['adult_title_' . $ad] == 'Mr') ? 1 : 2,
                "AddressLine1" => $input['address_' . $ad],
                "AddressLine2" => "",
                "City" => $input['address_' . $ad],
                "CountryCode" => $location->countryCode,
                "CountryName" => $location->countryName,
                "PassportNo" => isset($input['adult_passport_no_' . $ad]) ? $input['adult_passport_no_' . $ad] : "KJHHJKHKJH",
                "PassportExpiry" => isset($input['adult_pass_expiry_date_' . $ad]) ? $input['adult_pass_expiry_date_' . $ad] : "2022-08-30",
                "ContactNo" => $input['adult_phone_' . $ad],
                "Email" => $input['adult_email_' . $ad],
                "IsLeadPax" => ($ad == 1) ? true : false,
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

            if(isset($input['meal_' . $ad]) && $input['meal_' . $ad] != ''){
                $input['meal_' . $ad] = json_decode($input['meal_' . $ad], true);
                $flightPassengerArr[$ad-1]['Meal'] = $input['meal_' . $ad];
            }

            if(isset($input['seat_' . $ad]) && $input['seat_' . $ad] != ''){
                $input['seat_' . $ad] = json_decode($input['seat_' . $ad], true);
                $flightPassengerArr[$ad-1]['Seat'] = $input['seat_' . $ad];
            }

            if(isset($input['baggage_' . $ad]) && $input['baggage_' . $ad] != ''){
                $input['baggage_' . $ad] = json_decode($input['baggage_' . $ad], true);
                $flightPassengerArr[$ad-1]['Baggage'] = array();
                array_push($flightPassengerArr[$ad-1]['Baggage'], $input['baggage_' . $ad]);
            }

            if(isset($input['meallcc_' . $ad]) && $input['meallcc_' . $ad] != ''){
                $input['meallcc_' . $ad] = json_decode($input['meallcc_' . $ad], true);
                $flightPassengerArr[$ad-1]['MealDynamic'] = array();
                array_push($flightPassengerArr[$ad-1]['MealDynamic'], $input['meallcc_' . $ad]);
            }

            if(isset($input['baggage_return' . $ad]) && $input['baggage_return' . $ad] != ''){
                $input['baggage_return' . $ad] = json_decode($input['baggage_return' . $ad], true);
                //$flightPassengerArr[$ad-1]['Baggage'] = array();
                if(!isset($input['baggage_' . $ad]) && $input['baggage_' . $ad] == ''){
                 $flightPassengerArr[$ad-1]['Baggage'] = array();
                }

                array_push($flightPassengerArr[$ad-1]['Baggage'], $input['baggage_return' . $ad]);
            }

            if(isset($input['meallcc_return' . $ad]) && $input['meallcc_return' . $ad] != ''){
                $input['meallcc_return' . $ad] = json_decode($input['meallcc_return' . $ad], true);
                //$flightPassengerArr[$ad-1]['MealDynamic'] = array();
                if(!isset($input['meallcc_' . $ad]) && $input['meallcc_' . $ad] == ''){
                 $flightPassengerArr[$ad-1]['MealDynamic'] = array();
                }

                array_push($flightPassengerArr[$ad-1]['MealDynamic'], $input['meallcc_return' . $ad]);
            }

        }


        /* Child Array */

        for ($ch = 1; $ch <= $ChildCount; $ch++) {
            //$counterchild = $adultCount + 1;
            array_push($flightPassengerArr, array(
                "Title" => $input['child_title_' . $ch],
                "FirstName" => $input['child_first_name_' . $ch],
                "LastName" => $input['child_last_name_' . $ch],
                "PaxType" => 2,
                "DateOfBirth" => date('Y-m-d', strtotime($input['child_dob_' . $ch])) . "T00:00:00",
                "Gender" => ($input['child_title_' . $ch] == 'Mr') ? 1 : 2,
                "AddressLine1" => $input['address_1'],
                "AddressLine2" => "",
                "City" => $input['address_1'],
                "CountryCode" => $location->countryCode,
                "CountryName" => $location->countryName,
                "PassportNo" => isset($input['child_passport_no_' . $ch]) ? $input['child_passport_no_' . $ch] : "KJHHJKHKJH",
                "PassportExpiry" => isset($input['child_pass_expiry_date_' . $ch]) ? $input['child_pass_expiry_date_' . $ch]: "2022-08-30",
                "ContactNo" => $input['child_phone_' . $ch],
                "Email" => $input['child_email_' . $ch],
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


            if(isset($input['child_meal_' . $ch]) && $input['child_meal_' . $ch] != ''){
                $input['child_meal_' . $ch] = json_decode($input['child_meal_' . $ch], true);
                $flightPassengerArr[$counterchild]['Meal'] = $input['child_meal_' . $ch];
            }

            if(isset($input['child_seat_' . $ch]) && $input['child_seat_' . $ch] != ''){
                $input['child_seat_' . $ch] = json_decode($input['child_seat_' . $ch], true);
                $flightPassengerArr[$counterchild]['Seat'] = $input['child_seat_' . $ch];
            }

            if(isset($input['child_baggage_' . $ch]) && $input['child_baggage_' . $ch] != ''){
                $input['child_baggage_' . $ch] = json_decode($input['child_baggage_' . $ch], true);
                $flightPassengerArr[$counterchild]['Baggage'] = array();
                array_push($flightPassengerArr[$counterchild]['Baggage'], $input['child_baggage_' . $ch]);
            }

            if(isset($input['child_meallcc_' . $ch]) && $input['child_meallcc_' . $ch] != ''){
                $input['child_meallcc_' . $ch] = json_decode($input['child_meallcc_' . $ch], true);
                $flightPassengerArr[$counterchild]['MealDynamic'] = array();
                array_push($flightPassengerArr[$counterchild]['MealDynamic'], $input['child_meallcc_' . $ch]);
            }

            if(isset($input['child_baggage_return' . $ch]) && $input['child_baggage_return' . $ch] != ''){
                $input['child_baggage_return' . $ch] = json_decode($input['child_baggage_return' . $ch], true);

                if(!isset($input['child_baggage_' . $ch]) && $input['child_baggage_' . $ch] == ''){
                  $flightPassengerArr[$counterchild]['Baggage'] = array();
                }

                array_push($flightPassengerArr[$counterchild]['Baggage'], $input['child_baggage_return' . $ch]);
            }

            if(isset($input['child_meallcc_return' . $ch]) && $input['child_meallcc_return' . $ch] != ''){
                $input['child_meallcc_return' . $ch] = json_decode($input['child_meallcc_return' . $ch], true);
                
                if(!isset($input['child_meallcc_' . $ch]) && $input['child_meallcc_' . $ch] == ''){
                  $flightPassengerArr[$counterchild]['MealDynamic'] = array();
                }

                array_push($flightPassengerArr[$counterchild]['MealDynamic'], $input['child_meallcc_return' . $ch]);
            }

            $counterchild++;

        }

        /* End Object */

        /* Infant Arr */

        for ($inf = 1; $inf <= $InfantCount; $inf++) {

            array_push($flightPassengerArr, array(
                "Title" => $input['infant_title_' . $inf],
                "FirstName" => $input['infant_first_name_' . $inf],
                "LastName" => $input['infant_last_name_' . $inf],
                "PaxType" => 3,
                "DateOfBirth" => date('Y-m-d', strtotime($input['infant_dob_' . $inf])) . "T00:00:00",
                "Gender" => ( $input['infant_title_' . $inf] == 'Mr') ? 1 : 2,
                "AddressLine1" => $input['address_1'],
                "AddressLine2" => "",
                "City" => $input['address_1'],
                "CountryCode" => $location->countryCode,
                "CountryName" => $location->countryName,
                "ContactNo" => $input['infant_phone_' . $inf],
                "Email" => $input['infant_email_' . $inf],
                "IsLeadPax" => false,
                "FFAirline" => "",
                "FFNumber" => "",
                "Nationality" => "",
                "Fare" => array(
                    "BaseFare" => $fareQuoteOB[2]['BaseFare'] / $fareQuoteOB[2]['PassengerCount'],
                    "Tax" => $fareQuoteOB[2]['Tax'] / $fareQuoteOB[2]['PassengerCount'],
                    "YQTax" => $fareQuoteOB[2]['YQTax'] / $fareQuoteOB[2]['PassengerCount'],
                    "AdditionalTxnFeeOfrd" => $fareQuoteOB[2]['AdditionalTxnFeeOfrd'] / $fareQuoteOB[2]['PassengerCount'],
                    "AdditionalTxnFeePub" => $fareQuoteOB[2]['AdditionalTxnFeePub'] / $fareQuoteOB[2]['PassengerCount'],
                    "PGCharge" => $fareQuoteOB[2]['PGCharge'] / $fareQuoteOB[2]['PassengerCount']
                )
            ));

            if(isset($input['infant_meal_' . $inf]) && $input['infant_meal_' . $inf] != ''){
                $input['infant_meal_' . $inf] = json_decode($input['infant_meal_' . $inf], true);
                $flightPassengerArr[$counterinfant-1]['Meal'] = $input['infant_meal_' . $inf];
            }

            if(isset($input['infant_seat_' . $inf]) && $input['infant_seat_' . $inf] != ''){
                $input['infant_seat_' . $inf] = json_decode($input['infant_seat_' . $inf], true);
                $flightPassengerArr[$counterinfant-1]['Seat'] = $input['infant_seat_' . $inf];
            }

            $counterinfant++;

        }

        /* End Object */

        $currency = Session::get('currency');
        $bookFlightArr = array('PreferredCurrency' => $currency,
            "IsBaseCurrencyRequired" => "true",
            "EndUserIp" => $this->api->userIP,
            "TokenId" => $this->api->tokenId,
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
            if(Auth::user()) {
                $user = Auth::user();
            } else {
                $user = User::where('email', $input['adult_email_1'])->first();
            }

            if (isset($user) && $user->id) {
                Auth::login($user);
            } else {
                //create new user
                $password = $this->generateRandomString();
                $user = User::create([
                            'name' => $input['adult_first_name_1'] . ' ' . $input['adult_last_name_1'],
                            'email' => $input['adult_email_1'],
                            'phone' => $input['adult_phone_1'],
                            'address' => 'Bhopal',
                            'role' => 'user',
                            'password' => Hash::make($password),
                            'password_changed' => 1
                ]);
                //send email for create user
                Mail::to($input['adult_email_1'])->send(new NewUserRegister($user, $password));
                Auth::login($user);
            }


            /* Getting Ticket For OB */

            if ($input['is_ob_lcc'] && $input['is_ob_lcc'] != '') {

                $this->bookingResult = $this->api->ticket($bookFlightArr);

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
                                'token_id' => $this->api->tokenId,
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

                    //$input['amount'] = $input['amount'] + $input['extra_baggage_meal_price'];
                    $input['amount'] = $input['fullAmount_Install'];
                    //create entry to FlightPayments table

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $input['amount']);
                    $mAmountC = $mAmount['convertedAmount'];

                    $payments = FlightPayments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'agent_id' => $agent_id,
                                'commission' => $commission_agent,
                                'price' => $input['amount'],
                                'price_convered' => $mAmountC,
                                'partners_commision' => '',
                                'partners_commision_rest' => '',
                                'customer_id' => '',
                                'sub_domain' => '']);


                    

                    $segments = $bookingDetails['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetails['Response']['FlightItinerary']['FareRules'];
                  

                    if(isset($agent_id) && $agent_id != ''){
                   
                        // echo "<pre>"; print_r($segments[0]); die();
                        $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $flightSearch['travellersClass'] . '</b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
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
                    
                    Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));

                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));

                    }


                    if (isset($input['ibindex']) && $input['ibindex'] != '') {
                        
                    } else {

                        $this->emptySession();
                        //send to thank you page

                        return array('success' => true, 'booking_id' => $booking->id);

                        //return redirect('/thankyou/flight/' . $booking->id . '/true');
                    }
                } else {
                    $message = $this->bookingResult['Response']['Error']['ErrorMessage'];
                    //echo "<pre>";print_r($message);echo"</pre>";
                    //return view('500')->with(['error' => $message]);
                    return array('success' => false, 'message' => $message);
                }
            } else {

                $this->bookingResultNOLCC = $this->api->book($bookFlightArr);

                $bookingDetailsNOLCC = $this->bookingResultNOLCC['Response'];

                //echo "<pre>";print_r($bookingDetailsNOLCC);echo "</pre>";

                if ($bookingDetailsNOLCC['ResponseStatus'] != 1) {
                    $this->emptySession();
                    // return view('error')->with(['error' => $bookingDetailsNOLCC['Error']['ErrorMessage']]);
                    return view('500')->with(['error' => $bookingDetailsNOLCC['Error']['ErrorMessage']]);
                }

                $this->TicketResult = $this->api->getNoLCCTicket($input['trace_id'], $bookingDetailsNOLCC['Response']['BookingId'], $bookingDetailsNOLCC['Response']['PNR']);

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
                                'token_id' => $this->api->tokenId,
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
                    //$input['amount'] = $input['amount'] + $input['extra_baggage_meal_price'];
                    $input['amount'] = $input['fullAmount_Install'];

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $input['amount']);
                    $mAmountC = $mAmount['convertedAmount'];

                    $payments = FlightPayments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'agent_id' => $agent_id,
                                'commission' => $commission_agent,
                                'price' => $input['amount'],
                                'price_convered' => $mAmountC,
                                'partners_commision' => '',
                                'partners_commision_rest' => '',
                                'customer_id' => '',
                                'sub_domain' => '']);




                    $segments = $bookingDetailsNOLCC['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetailsNOLCC['Response']['FlightItinerary']['FareRules'];


                    if(isset($agent_id) && $agent_id != ''){   

                        $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $flightSearch['travellersClass'] . '</b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                       
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

                    Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));

                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                          
                    }


                    if (isset($input['ibindex']) && $input['ibindex'] != '') {
                        
                    } else {

                        $this->emptySession();
                        //send to thank you page
                        //return redirect('/thankyou/flight/' . $booking->id . '/true');
                        return array('success' => true, 'booking_id' => $booking->id);
                    }
                } else {
                    $message = $this->TicketResult['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    //return view('500')->with(['error' => $message]);
                    return array('success' => false, 'message' => $message);
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
        //$this->bookingResult = $this->api->ticket($bookFlightArr);

        /* Getting Ticket For IB */

        if (isset($input['ibindex']) && $input['ibindex'] != '') {

            $flightPassengerArrIB = array();
            $fareQuoteIB = json_decode($input['farebreakDownIB'], true);

            /* Adult Array */

            $counterchildIB = $adultCount;

            for ($adIB = 1; $adIB <= $adultCount; $adIB++) {

                array_push($flightPassengerArrIB, array(
                    "Title" => $input['adult_title_' . $adIB],
                    "FirstName" => $input['adult_first_name_' . $adIB],
                    "LastName" => $input['adult_last_name_' . $adIB],
                    "PaxType" => 1,
                    "DateOfBirth" => "1985-01-30T00:00:00",
                    "Gender" => ($input['adult_title_' . $adIB] == 'Mr') ? 1 : 2,
                    "AddressLine1" => $input['address_' . $adIB],
                    "AddressLine2" => "",
                    "City" => $input['address_' . $adIB],
                    "CountryCode" => $location->countryCode,
                    "CountryName" => $location->countryName,
                    "PassportNo" => isset($input['adult_passport_no_' . $adIB]) ? $input['adult_passport_no_' . $adIB] : "KJHHJKHKJH",
                    "PassportExpiry" => isset($input['adult_pass_expiry_date_' . $adIB]) ? $input['adult_pass_expiry_date_' . $adIB] : "2022-08-30",
                    "ContactNo" => $input['adult_phone_' . $adIB],
                    "Email" => $input['adult_email_' . $adIB],
                    "IsLeadPax" => ($adIB == 1) ? true : false,
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

                if(isset($input['baggage_return_ib' . $adIB]) && $input['baggage_return_ib' . $adIB] != ''){
                    $input['baggage_return_ib' . $adIB] = json_decode($input['baggage_return_ib' . $adIB], true);
                    $flightPassengerArrIB[$adIB-1]['Baggage'] = array();
                    array_push($flightPassengerArrIB[$adIB-1]['Baggage'], $input['baggage_return_ib' . $adIB]);
                }

                if(isset($input['meallcc_return_ib' . $adIB]) && $input['meallcc_return_ib' . $adIB] != ''){
                    $input['meallcc_return_ib' . $adIB] = json_decode($input['meallcc_return_ib' . $adIB], true);
                    $flightPassengerArrIB[$adIB-1]['MealDynamic'] = array();
                    array_push($flightPassengerArrIB[$adIB-1]['MealDynamic'], $input['meallcc_return_ib' . $adIB]);
                }

            }

            /* Child Array */

            for ($chIB = 1; $chIB <= $ChildCount; $chIB++) {

                array_push($flightPassengerArrIB, array(
                    "Title" => $input['child_title_' . $chIB],
                    "FirstName" => $input['child_first_name_' . $chIB],
                    "LastName" => $input['child_last_name_' . $chIB],
                    "PaxType" => 2,
                    "DateOfBirth" => "2014-01-30T00:00:00",
                    "Gender" => ($input['child_title_' . $chIB] == 'Mr') ? 1 : 2,
                    "AddressLine1" => $input['address_1'],
                    "AddressLine2" => "",
                    "City" => $input['address_1'],
                    "CountryCode" => $location->countryCode,
                    "CountryName" => $location->countryName,
                    "PassportNo" => isset($input['child_passport_no_' . $chIB]) ? $input['child_passport_no_' . $chIB] : "KJHHJKHKJH",
                    "PassportExpiry" => isset($input['child_pass_expiry_date_' . $chIB]) ? $input['child_pass_expiry_date_' . $chIB] : "2022-08-30",
                    "ContactNo" => $input['child_phone_' . $chIB],
                    "Email" => $input['child_email_' . $chIB],
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

             if(isset($input['child_baggage_return_ib' . $chIB]) && $input['child_baggage_return_ib' . $chIB] != ''){

                $input['child_baggage_return_ib' . $chIB] = json_decode($input['child_baggage_return_ib' . $chIB], true);
                $flightPassengerArrIB[$counterchildIB]['Baggage'] = array();

                array_push($flightPassengerArrIB[$counterchildIB]['Baggage'], $input['child_baggage_return_ib' . $chIB]);

            }

            if(isset($input['child_meallcc_return_ib' . $chIB]) && $input['child_meallcc_return_ib' . $chIB] != ''){

                $input['child_meallcc_return_ib' . $chIB] = json_decode($input['child_meallcc_return_ib' . $chIB], true);
                $flightPassengerArrIB[$counterchildIB]['MealDynamic'] = array();

                array_push($flightPassengerArrIB[$counterchildIB]['MealDynamic'], $input['child_meallcc_return_ib' . $chIB]);

            }

                $counterchildIB++;
            }

            /* End Object */

            /* Infant Arr */

            for ($infIB = 1; $infIB <= $InfantCount; $infIB++) {

                array_push($flightPassengerArrIB, array(
                    "Title" => $input['infant_title_' . $infIB],
                    "FirstName" => $input['infant_first_name_' . $infIB],
                    "LastName" => $input['infant_last_name_' . $infIB],
                    "PaxType" => 3,
                    "DateOfBirth" => "2019-01-30T00:00:00",
                    "Gender" => ( $input['infant_title_' . $infIB] == 'Mr') ? 1 : 2,
                    "AddressLine1" => $input['address_1'],
                    "AddressLine2" => "",
                    "City" => $input['address_1'],
                    "CountryCode" => $location->countryCode,
                    "CountryName" => $location->countryName,
                    "ContactNo" => $input['infant_phone_' . $infIB],
                    "Email" => $input['infant_email_' . $infIB],
                    "IsLeadPax" => false,
                    "FFAirline" => "",
                    "FFNumber" => "",
                    "Nationality" => "",
                    "Fare" => array(
                        "BaseFare" => $fareQuoteIB[2]['BaseFare'] / $fareQuoteIB[2]['PassengerCount'],
                        "Tax" => $fareQuoteIB[2]['Tax'] / $fareQuoteIB[2]['PassengerCount'],
                        "YQTax" => $fareQuoteIB[2]['YQTax'] / $fareQuoteIB[2]['PassengerCount'],
                        "AdditionalTxnFeeOfrd" => $fareQuoteIB[2]['AdditionalTxnFeeOfrd'] / $fareQuoteIB[2]['PassengerCount'],
                        "AdditionalTxnFeePub" => $fareQuoteIB[2]['AdditionalTxnFeePub'] / $fareQuoteIB[2]['PassengerCount'],
                        "PGCharge" => $fareQuoteIB[2]['PGCharge'] / $fareQuoteIB[2]['PassengerCount']
                    )
                ));
            }

            /* End Object */
            $currency = Session::get('currency');
            $bookFlightArrIB = array('PreferredCurrency' => $currency,
                "IsBaseCurrencyRequired" => "true",
                "EndUserIp" => $this->api->userIP,
                "TokenId" => $this->api->tokenId,
                "TraceId" => $input['trace_id'],
                "ResultIndex" => $input['ibindex'],
                "Passengers" => $flightPassengerArrIB
            );

            if ($input['is_ib_lcc'] && $input['is_ib_lcc'] != '') {

                $this->bookingResultIB = $this->api->ticketIB($bookFlightArrIB);

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
                                'token_id' => $this->api->tokenId,
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

                    if(isset($agent_id) && $agent_id != ''){ 

                        $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $flightSearch['travellersClass'] . '</b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);
  

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => 'Booking for Flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] . '<br> Total paid amount <b>'. Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>',
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }

                    $segments = $bookingDetails['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetails['Response']['FlightItinerary']['FareRules'];

                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));

                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                          
                    }

                    //Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $bookingDetails['Response']['FlightItinerary']['Segments'], $bookingDetails['Response']['FlightItinerary']['FareRules']));

                    $this->emptySession();
                    //send to thank you page
                    //return redirect('/thankyou/flight/' . $booking->id . '/true');
                     return array('success' => true, 'booking_id' => $booking->id);
                } else {
                    $message = $this->bookingResultIB['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    //return view('500')->with(['error' => $message]);
                    return array('success' => false, 'message' => $message);
                }
            } else {

                $this->bookingResultIBNOLCC = $this->api->bookIB($bookFlightArrIB);

                $bookingDetailsIBNOLCC = $this->bookingResultIBNOLCC['Response'];

                //echo "<pre>";print_r($bookingDetailsNOLCC);echo "</pre>";
                if ($bookingDetailsIBNOLCC['ResponseStatus'] != 1) {
                    $this->emptySession();
                    // return view('error')->with(['error' => $bookingDetailsIBNOLCC['Error']['ErrorMessage']]);
                    return view('500')->with(['error' => $bookingDetailsIBNOLCC['Error']['ErrorMessage']]);
                }
                $this->TicketResultIB = $this->api->getNoLCCTicketIB($input['trace_id'], $bookingDetailsIBNOLCC['Response']['BookingId'], $bookingDetailsIBNOLCC['Response']['PNR']);

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
                                'token_id' => $this->api->tokenId,
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
                    
                    if(isset($agent_id) && $agent_id != ''){   

                        $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $flightSearch['travellersClass'] . '</b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);


                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => 'Booking for Flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] . '<br> Total paid amount <b>'. Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>',
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }    

                    $segments = $bookingDetailsIBNOLCC['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetailsIBNOLCC['Response']['FlightItinerary']['FareRules'];

                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));

                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                          
                    }

                    //Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $ticketDetailsIBNOLCC['Response']['FlightItinerary']['Segments'], $ticketDetailsIBNOLCC['Response']['FlightItinerary']['FareRules']));
                    //Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $ticketDetailsIBNOLCC['Response']['FlightItinerary']['Segments'], $ticketDetailsIBNOLCC['Response']['FlightItinerary']['FareRules']));

                    $this->emptySession();
                    //send to thank you page
                    //return redirect('/thankyou/flight/' . $booking->id . '/true');
                     return array('success' => true, 'booking_id' => $booking->id);
                } else {
                    $message = $this->TicketResultIB['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    //return view('500')->with(['error' => $message]);
                    return array('success' => false, 'message' => $message);
                }
            }
        }


        //echo "<pre>";print_r($this->bookingResult);echo "</pre>"; die;
        //echo "<pre>";print_r($input);die;
    }

    public function bookFlight(Request $request) {

        $input = $request->all();

        $search_contents = json_decode($this->readSearchData($input['search_id'].'.json'), true);
        $flightSearch = $search_contents['request']['input'];

        //$flightSearch = Session::get('flightSearhData');
        $flightSearch['travellersClass'] = str_replace("+", " ", $flightSearch['travellersClass']);
        if ($input['referral'] != '' && $input['referral'] != '0') {


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

        $adultCount = $flightSearch['adultsF'];
        $ChildCount = ($flightSearch['childsF'] != null) ? $flightSearch['childsF'] : "0";
        $InfantCount = ($flightSearch['infants'] != null) ? $flightSearch['infants'] : "0";

        $departDate = $flightSearch['departDate'];

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


        if ($input['referral'] && $input['referral'] != '' && $input['referral'] != '0') {

            $commission_agent = $commission_agent;
        } else {
            $commission_agent = $commission;
        }


        // $location = \Location::get($this->end_user_ip);
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

        for ($ad = 1; $ad <= $adultCount; $ad++) {

            array_push($flightPassengerArr, array(
                "Title" => $input['adult_title_' . $ad],
                "FirstName" => $input['adult_first_name_' . $ad],
                "LastName" => $input['adult_last_name_' . $ad],
                "PaxType" => 1,
                "DateOfBirth" => isset($input['adult_dob_' . $ad]) ? $input['adult_dob_' . $ad] : "1985-01-30T00:00:00",
                "Gender" => ($input['adult_title_' . $ad] == 'Mr') ? 1 : 2,
                "AddressLine1" => $input['address_' . $ad],
                "AddressLine2" => "",
                "City" => $input['address_' . $ad],
                "CountryCode" => $location->countryCode,
                "CountryName" => $location->countryName,
                "PassportNo" => isset($input['adult_passport_no_' . $ad]) ? $input['adult_passport_no_' . $ad] : "KJHHJKHKJH",
                "PassportExpiry" => isset($input['adult_pass_expiry_date_' . $ad]) ? $input['adult_pass_expiry_date_' . $ad] : "2022-08-30",
                "ContactNo" => $input['adult_phone_' . $ad],
                "Email" => $input['adult_email_' . $ad],
                "IsLeadPax" => ($ad == 1) ? true : false,
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

            if(isset($input['meal_' . $ad]) && $input['meal_' . $ad] != ''){
                $input['meal_' . $ad] = json_decode($input['meal_' . $ad], true);
                $flightPassengerArr[$ad-1]['Meal'] = $input['meal_' . $ad];
            }

            if(isset($input['seat_' . $ad]) && $input['seat_' . $ad] != ''){
                $input['seat_' . $ad] = json_decode($input['seat_' . $ad], true);
                $flightPassengerArr[$ad-1]['Seat'] = $input['seat_' . $ad];
            }

            if(isset($input['baggage_' . $ad]) && $input['baggage_' . $ad] != ''){
                $input['baggage_' . $ad] = json_decode($input['baggage_' . $ad], true);
                $flightPassengerArr[$ad-1]['Baggage'] = array();
                array_push($flightPassengerArr[$ad-1]['Baggage'], $input['baggage_' . $ad]);
            }

            if(isset($input['meallcc_' . $ad]) && $input['meallcc_' . $ad] != ''){
                $input['meallcc_' . $ad] = json_decode($input['meallcc_' . $ad], true);
                $flightPassengerArr[$ad-1]['MealDynamic'] = array();
                array_push($flightPassengerArr[$ad-1]['MealDynamic'], $input['meallcc_' . $ad]);
            }

            if(isset($input['baggage_return' . $ad]) && $input['baggage_return' . $ad] != ''){
                $input['baggage_return' . $ad] = json_decode($input['baggage_return' . $ad], true);
                //$flightPassengerArr[$ad-1]['Baggage'] = array();
                if(!isset($input['baggage_' . $ad]) && $input['baggage_' . $ad] == ''){
                 $flightPassengerArr[$ad-1]['Baggage'] = array();
                }

                array_push($flightPassengerArr[$ad-1]['Baggage'], $input['baggage_return' . $ad]);
            }

            if(isset($input['meallcc_return' . $ad]) && $input['meallcc_return' . $ad] != ''){
                $input['meallcc_return' . $ad] = json_decode($input['meallcc_return' . $ad], true);
                //$flightPassengerArr[$ad-1]['MealDynamic'] = array();
                if(!isset($input['meallcc_' . $ad]) && $input['meallcc_' . $ad] == ''){
                 $flightPassengerArr[$ad-1]['MealDynamic'] = array();
                }

                array_push($flightPassengerArr[$ad-1]['MealDynamic'], $input['meallcc_return' . $ad]);
            }

        }


        /* Child Array */

        for ($ch = 1; $ch <= $ChildCount; $ch++) {
            //$counterchild = $adultCount + 1;
            array_push($flightPassengerArr, array(
                "Title" => $input['child_title_' . $ch],
                "FirstName" => $input['child_first_name_' . $ch],
                "LastName" => $input['child_last_name_' . $ch],
                "PaxType" => 2,
                "DateOfBirth" => date('Y-m-d', strtotime($input['child_dob_' . $ch])) . "T00:00:00",
                "Gender" => ($input['child_title_' . $ch] == 'Mr') ? 1 : 2,
                "AddressLine1" => $input['address_1'],
                "AddressLine2" => "",
                "City" => $input['address_1'],
                "CountryCode" => $location->countryCode,
                "CountryName" => $location->countryName,
                "PassportNo" => isset($input['child_passport_no_' . $ch]) ? $input['child_passport_no_' . $ch] : "KJHHJKHKJH",
                "PassportExpiry" => isset($input['child_pass_expiry_date_' . $ch]) ? $input['child_pass_expiry_date_' . $ch]: "2022-08-30",
                "ContactNo" => $input['child_phone_' . $ch],
                "Email" => $input['child_email_' . $ch],
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


            if(isset($input['child_meal_' . $ch]) && $input['child_meal_' . $ch] != ''){
                $input['child_meal_' . $ch] = json_decode($input['child_meal_' . $ch], true);
                $flightPassengerArr[$counterchild]['Meal'] = $input['child_meal_' . $ch];
            }

            if(isset($input['child_seat_' . $ch]) && $input['child_seat_' . $ch] != ''){
                $input['child_seat_' . $ch] = json_decode($input['child_seat_' . $ch], true);
                $flightPassengerArr[$counterchild]['Seat'] = $input['child_seat_' . $ch];
            }

            if(isset($input['child_baggage_' . $ch]) && $input['child_baggage_' . $ch] != ''){
                $input['child_baggage_' . $ch] = json_decode($input['child_baggage_' . $ch], true);
                $flightPassengerArr[$counterchild]['Baggage'] = array();
                array_push($flightPassengerArr[$counterchild]['Baggage'], $input['child_baggage_' . $ch]);
            }

            if(isset($input['child_meallcc_' . $ch]) && $input['child_meallcc_' . $ch] != ''){
                $input['child_meallcc_' . $ch] = json_decode($input['child_meallcc_' . $ch], true);
                $flightPassengerArr[$counterchild]['MealDynamic'] = array();
                array_push($flightPassengerArr[$counterchild]['MealDynamic'], $input['child_meallcc_' . $ch]);
            }

            if(isset($input['child_baggage_return' . $ch]) && $input['child_baggage_return' . $ch] != ''){
                $input['child_baggage_return' . $ch] = json_decode($input['child_baggage_return' . $ch], true);

                if(!isset($input['child_baggage_' . $ch]) && $input['child_baggage_' . $ch] == ''){
                  $flightPassengerArr[$counterchild]['Baggage'] = array();
                }

                array_push($flightPassengerArr[$counterchild]['Baggage'], $input['child_baggage_return' . $ch]);
            }

            if(isset($input['child_meallcc_return' . $ch]) && $input['child_meallcc_return' . $ch] != ''){
                $input['child_meallcc_return' . $ch] = json_decode($input['child_meallcc_return' . $ch], true);
                
                if(!isset($input['child_meallcc_' . $ch]) && $input['child_meallcc_' . $ch] == ''){
                  $flightPassengerArr[$counterchild]['MealDynamic'] = array();
                }

                array_push($flightPassengerArr[$counterchild]['MealDynamic'], $input['child_meallcc_return' . $ch]);
            }

            $counterchild++;

        }

        /* End Object */

        /* Infant Arr */

        for ($inf = 1; $inf <= $InfantCount; $inf++) {

            array_push($flightPassengerArr, array(
                "Title" => $input['infant_title_' . $inf],
                "FirstName" => $input['infant_first_name_' . $inf],
                "LastName" => $input['infant_last_name_' . $inf],
                "PaxType" => 3,
                "DateOfBirth" => date('Y-m-d', strtotime($input['infant_dob_' . $inf])) . "T00:00:00",
                "Gender" => ( $input['infant_title_' . $inf] == 'Mr') ? 1 : 2,
                "AddressLine1" => $input['address_1'],
                "AddressLine2" => "",
                "City" => $input['address_1'],
                "CountryCode" => $location->countryCode,
                "CountryName" => $location->countryName,
                "ContactNo" => $input['infant_phone_' . $inf],
                "Email" => $input['infant_email_' . $inf],
                "IsLeadPax" => false,
                "FFAirline" => "",
                "FFNumber" => "",
                "Nationality" => "",
                "Fare" => array(
                    "BaseFare" => $fareQuoteOB[2]['BaseFare'] / $fareQuoteOB[2]['PassengerCount'],
                    "Tax" => $fareQuoteOB[2]['Tax'] / $fareQuoteOB[2]['PassengerCount'],
                    "YQTax" => $fareQuoteOB[2]['YQTax'] / $fareQuoteOB[2]['PassengerCount'],
                    "AdditionalTxnFeeOfrd" => $fareQuoteOB[2]['AdditionalTxnFeeOfrd'] / $fareQuoteOB[2]['PassengerCount'],
                    "AdditionalTxnFeePub" => $fareQuoteOB[2]['AdditionalTxnFeePub'] / $fareQuoteOB[2]['PassengerCount'],
                    "PGCharge" => $fareQuoteOB[2]['PGCharge'] / $fareQuoteOB[2]['PassengerCount']
                )
            ));

            if(isset($input['infant_meal_' . $inf]) && $input['infant_meal_' . $inf] != ''){
                $input['infant_meal_' . $inf] = json_decode($input['infant_meal_' . $inf], true);
                $flightPassengerArr[$counterinfant-1]['Meal'] = $input['infant_meal_' . $inf];
            }

            if(isset($input['infant_seat_' . $inf]) && $input['infant_seat_' . $inf] != ''){
                $input['infant_seat_' . $inf] = json_decode($input['infant_seat_' . $inf], true);
                $flightPassengerArr[$counterinfant-1]['Seat'] = $input['infant_seat_' . $inf];
            }

            $counterinfant++;

        }

        /* End Object */

        $currency = Session::get('currency');
        $bookFlightArr = array('PreferredCurrency' => $currency,
            "IsBaseCurrencyRequired" => "true",
            "EndUserIp" => $this->api->userIP,
            "TokenId" => $this->api->tokenId,
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
            if(Auth::user()) {
                $user = Auth::user();
            } else {
                $user = User::where('email', $input['adult_email_1'])->first();
            }

            if (isset($user) && $user->id) {
                Auth::login($user);
            } else {
                //create new user
                $password = $this->generateRandomString();
                $user = User::create([
                            'name' => $input['adult_first_name_1'] . ' ' . $input['adult_last_name_1'],
                            'email' => $input['adult_email_1'],
                            'phone' => $input['adult_phone_1'],
                            'address' => 'Bhopal',
                            'role' => 'user',
                            'password' => Hash::make($password),
                            'password_changed' => 1
                ]);
                //send email for create user
                Mail::to($input['adult_email_1'])->send(new NewUserRegister($user, $password));
                Auth::login($user);
            }


            /* Getting Ticket For OB */

            if ($input['is_ob_lcc'] && $input['is_ob_lcc'] != '') {

                $this->bookingResult = $this->api->ticket($bookFlightArr);

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
                                'token_id' => $this->api->tokenId,
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
                    //create entry to FlightPayments table

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $input['amount']);
                    $mAmountC = $mAmount['convertedAmount'];

                    $payments = FlightPayments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'agent_id' => $agent_id,
                                'commission' => $commission_agent,
                                'price' => $input['amount'],
                                'price_convered' => $mAmountC,
                                'partners_commision' => '',
                                'partners_commision_rest' => '',
                                'customer_id' => '',
                                'sub_domain' => '']);


                    

                    $segments = $bookingDetails['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetails['Response']['FlightItinerary']['FareRules'];
                  

                    if(isset($agent_id) && $agent_id != ''){
                   
                        // echo "<pre>"; print_r($segments[0]); die();
                        $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $flightSearch['travellersClass'] . '</b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
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
                    
                    Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));

                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));

                    }


                    if (isset($input['ibindex']) && $input['ibindex'] != '') {
                        
                    } else {

                        $this->emptySession();
                        //send to thank you page
                        return redirect('/thankyou/flight/' . $booking->id . '/true');
                    }
                } else {
                    $message = $this->bookingResult['Response']['Error']['ErrorMessage'];
                    //echo "<pre>";print_r($message);echo"</pre>";
                    return view('500')->with(['error' => $message]);
                }
            } else {

                $this->bookingResultNOLCC = $this->api->book($bookFlightArr);

                $bookingDetailsNOLCC = $this->bookingResultNOLCC['Response'];

                //echo "<pre>";print_r($bookingDetailsNOLCC);echo "</pre>";

                if ($bookingDetailsNOLCC['ResponseStatus'] != 1) {
                    $this->emptySession();
                    // return view('error')->with(['error' => $bookingDetailsNOLCC['Error']['ErrorMessage']]);
                    return view('500')->with(['error' => $bookingDetailsNOLCC['Error']['ErrorMessage']]);
                }

                $this->TicketResult = $this->api->getNoLCCTicket($input['trace_id'], $bookingDetailsNOLCC['Response']['BookingId'], $bookingDetailsNOLCC['Response']['PNR']);

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
                                'token_id' => $this->api->tokenId,
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

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $input['amount']);
                    $mAmountC = $mAmount['convertedAmount'];

                    $payments = FlightPayments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'agent_id' => $agent_id,
                                'commission' => $commission_agent,
                                'price' => $input['amount'],
                                'price_convered' => $mAmountC,
                                'partners_commision' => '',
                                'partners_commision_rest' => '',
                                'customer_id' => '',
                                'sub_domain' => '']);




                    $segments = $bookingDetailsNOLCC['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetailsNOLCC['Response']['FlightItinerary']['FareRules'];


                    if(isset($agent_id) && $agent_id != ''){   

                        $post_content = 'Booking for flight from <b>' . $flightSearch['from'] . '</b> to <b>'.  $flightSearch['to'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $flightSearch['travellersClass'] . '</b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                       
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

                    Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));

                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                          
                    }


                    if (isset($input['ibindex']) && $input['ibindex'] != '') {
                        
                    } else {

                        $this->emptySession();
                        //send to thank you page
                        return redirect('/thankyou/flight/' . $booking->id . '/true');
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
        //$this->bookingResult = $this->api->ticket($bookFlightArr);

        /* Getting Ticket For IB */

        if (isset($input['ibindex']) && $input['ibindex'] != '') {

            $flightPassengerArrIB = array();
            $fareQuoteIB = json_decode($input['farebreakDownIB'], true);

            /* Adult Array */

            $counterchildIB = $adultCount;

            for ($adIB = 1; $adIB <= $adultCount; $adIB++) {

                array_push($flightPassengerArrIB, array(
                    "Title" => $input['adult_title_' . $adIB],
                    "FirstName" => $input['adult_first_name_' . $adIB],
                    "LastName" => $input['adult_last_name_' . $adIB],
                    "PaxType" => 1,
                    "DateOfBirth" => "1985-01-30T00:00:00",
                    "Gender" => ($input['adult_title_' . $adIB] == 'Mr') ? 1 : 2,
                    "AddressLine1" => $input['address_' . $adIB],
                    "AddressLine2" => "",
                    "City" => $input['address_' . $adIB],
                    "CountryCode" => $location->countryCode,
                    "CountryName" => $location->countryName,
                    "PassportNo" => isset($input['adult_passport_no_' . $adIB]) ? $input['adult_passport_no_' . $adIB] : "KJHHJKHKJH",
                    "PassportExpiry" => isset($input['adult_pass_expiry_date_' . $adIB]) ? $input['adult_pass_expiry_date_' . $adIB] : "2022-08-30",
                    "ContactNo" => $input['adult_phone_' . $adIB],
                    "Email" => $input['adult_email_' . $adIB],
                    "IsLeadPax" => ($adIB == 1) ? true : false,
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

                if(isset($input['baggage_return_ib' . $adIB]) && $input['baggage_return_ib' . $adIB] != ''){
                    $input['baggage_return_ib' . $adIB] = json_decode($input['baggage_return_ib' . $adIB], true);
                    $flightPassengerArrIB[$adIB-1]['Baggage'] = array();
                    array_push($flightPassengerArrIB[$adIB-1]['Baggage'], $input['baggage_return_ib' . $adIB]);
                }

                if(isset($input['meallcc_return_ib' . $adIB]) && $input['meallcc_return_ib' . $adIB] != ''){
                    $input['meallcc_return_ib' . $adIB] = json_decode($input['meallcc_return_ib' . $adIB], true);
                    $flightPassengerArrIB[$adIB-1]['MealDynamic'] = array();
                    array_push($flightPassengerArrIB[$adIB-1]['MealDynamic'], $input['meallcc_return_ib' . $adIB]);
                }

            }

            /* Child Array */

            for ($chIB = 1; $chIB <= $ChildCount; $chIB++) {

                array_push($flightPassengerArrIB, array(
                    "Title" => $input['child_title_' . $chIB],
                    "FirstName" => $input['child_first_name_' . $chIB],
                    "LastName" => $input['child_last_name_' . $chIB],
                    "PaxType" => 2,
                    "DateOfBirth" => "2014-01-30T00:00:00",
                    "Gender" => ($input['child_title_' . $chIB] == 'Mr') ? 1 : 2,
                    "AddressLine1" => $input['address_1'],
                    "AddressLine2" => "",
                    "City" => $input['address_1'],
                    "CountryCode" => $location->countryCode,
                    "CountryName" => $location->countryName,
                    "PassportNo" => isset($input['child_passport_no_' . $chIB]) ? $input['child_passport_no_' . $chIB] : "KJHHJKHKJH",
                    "PassportExpiry" => isset($input['child_pass_expiry_date_' . $chIB]) ? $input['child_pass_expiry_date_' . $chIB] : "2022-08-30",
                    "ContactNo" => $input['child_phone_' . $chIB],
                    "Email" => $input['child_email_' . $chIB],
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

             if(isset($input['child_baggage_return_ib' . $chIB]) && $input['child_baggage_return_ib' . $chIB] != ''){

                $input['child_baggage_return_ib' . $chIB] = json_decode($input['child_baggage_return_ib' . $chIB], true);
                $flightPassengerArrIB[$counterchildIB]['Baggage'] = array();

                array_push($flightPassengerArrIB[$counterchildIB]['Baggage'], $input['child_baggage_return_ib' . $chIB]);

            }

            if(isset($input['child_meallcc_return_ib' . $chIB]) && $input['child_meallcc_return_ib' . $chIB] != ''){

                $input['child_meallcc_return_ib' . $chIB] = json_decode($input['child_meallcc_return_ib' . $chIB], true);
                $flightPassengerArrIB[$counterchildIB]['MealDynamic'] = array();

                array_push($flightPassengerArrIB[$counterchildIB]['MealDynamic'], $input['child_meallcc_return_ib' . $chIB]);

            }

                $counterchildIB++;
            }

            /* End Object */

            /* Infant Arr */

            for ($infIB = 1; $infIB <= $InfantCount; $infIB++) {

                array_push($flightPassengerArrIB, array(
                    "Title" => $input['infant_title_' . $infIB],
                    "FirstName" => $input['infant_first_name_' . $infIB],
                    "LastName" => $input['infant_last_name_' . $infIB],
                    "PaxType" => 3,
                    "DateOfBirth" => "2019-01-30T00:00:00",
                    "Gender" => ( $input['infant_title_' . $infIB] == 'Mr') ? 1 : 2,
                    "AddressLine1" => $input['address_1'],
                    "AddressLine2" => "",
                    "City" => $input['address_1'],
                    "CountryCode" => $location->countryCode,
                    "CountryName" => $location->countryName,
                    "ContactNo" => $input['infant_phone_' . $infIB],
                    "Email" => $input['infant_email_' . $infIB],
                    "IsLeadPax" => false,
                    "FFAirline" => "",
                    "FFNumber" => "",
                    "Nationality" => "",
                    "Fare" => array(
                        "BaseFare" => $fareQuoteIB[2]['BaseFare'] / $fareQuoteIB[2]['PassengerCount'],
                        "Tax" => $fareQuoteIB[2]['Tax'] / $fareQuoteIB[2]['PassengerCount'],
                        "YQTax" => $fareQuoteIB[2]['YQTax'] / $fareQuoteIB[2]['PassengerCount'],
                        "AdditionalTxnFeeOfrd" => $fareQuoteIB[2]['AdditionalTxnFeeOfrd'] / $fareQuoteIB[2]['PassengerCount'],
                        "AdditionalTxnFeePub" => $fareQuoteIB[2]['AdditionalTxnFeePub'] / $fareQuoteIB[2]['PassengerCount'],
                        "PGCharge" => $fareQuoteIB[2]['PGCharge'] / $fareQuoteIB[2]['PassengerCount']
                    )
                ));
            }

            /* End Object */
            $currency = Session::get('currency');
            $bookFlightArrIB = array('PreferredCurrency' => $currency,
                "IsBaseCurrencyRequired" => "true",
                "EndUserIp" => $this->api->userIP,
                "TokenId" => $this->api->tokenId,
                "TraceId" => $input['trace_id'],
                "ResultIndex" => $input['ibindex'],
                "Passengers" => $flightPassengerArrIB
            );

            if ($input['is_ib_lcc'] && $input['is_ib_lcc'] != '') {

                $this->bookingResultIB = $this->api->ticketIB($bookFlightArrIB);

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
                                'token_id' => $this->api->tokenId,
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

                    if(isset($agent_id) && $agent_id != ''){ 

                        $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $flightSearch['travellersClass'] . '</b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);
  

                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => 'Booking for Flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] . '<br> Total paid amount <b>'. Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>',
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }

                    $segments = $bookingDetails['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetails['Response']['FlightItinerary']['FareRules'];

                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));

                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                          
                    }

                    //Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $bookingDetails['Response']['FlightItinerary']['Segments'], $bookingDetails['Response']['FlightItinerary']['FareRules']));

                    $this->emptySession();
                    //send to thank you page
                    return redirect('/thankyou/flight/' . $booking->id . '/true');
                } else {
                    $message = $this->bookingResultIB['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    return view('500')->with(['error' => $message]);
                }
            } else {

                $this->bookingResultIBNOLCC = $this->api->bookIB($bookFlightArrIB);

                $bookingDetailsIBNOLCC = $this->bookingResultIBNOLCC['Response'];

                //echo "<pre>";print_r($bookingDetailsNOLCC);echo "</pre>";
                if ($bookingDetailsIBNOLCC['ResponseStatus'] != 1) {
                    $this->emptySession();
                    // return view('error')->with(['error' => $bookingDetailsIBNOLCC['Error']['ErrorMessage']]);
                    return view('500')->with(['error' => $bookingDetailsIBNOLCC['Error']['ErrorMessage']]);
                }
                $this->TicketResultIB = $this->api->getNoLCCTicketIB($input['trace_id'], $bookingDetailsIBNOLCC['Response']['BookingId'], $bookingDetailsIBNOLCC['Response']['PNR']);

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
                                'token_id' => $this->api->tokenId,
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
                    
                    if(isset($agent_id) && $agent_id != ''){   

                        $post_content = 'Booking for flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] .'</b> depart date <b>' . date('l, F jS, Y', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> depart time <b>' . date('H:i', strtotime(str_replace('/' , '-', $travelArr['departure_date_arr']) )) . '</b> for <b>' . $flightSearch['travellersClass'] . '</b><br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>';
                        //create story for profile page
                        Posts::create(['post_type' => 'article_image',
                                  'post_content' => $post_content,
                                  'post_media' => "https://daisycon.io/images/airline/?width=600&height=500&color=ffffff&iata=" . $segments[0]['Airline']['AirlineCode'],
                                  'user_id' => Auth::user()->id]);


                        $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                              'type' => 'flight',
                              'description' => 'Booking for Flight from <b>' . $travelArr['main_start'] . '</b> to <b>'.  $travelArr['to_start'] . '<br> Total paid amount <b>'. Session::get('CurrencyCode') . ' ' . round($input['amount'],2) . '</b>',
                              'price' => 'USD ' . round($commission_agent,2),
                              'status' => 0
                        ]);
                    }    

                    $segments = $bookingDetailsIBNOLCC['Response']['FlightItinerary']['Segments'];
                    $farerules = $bookingDetailsIBNOLCC['Response']['FlightItinerary']['FareRules'];

                    $pdf = PDF::loadView('emails.invoice.flights-booking', compact('booking', 'payments', 'segments', 'farerules'));
                    $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                    $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                    $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                    Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));

                    if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                        Mail::to($agentemail)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));
                          
                    }

                    //Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $ticketDetailsIBNOLCC['Response']['FlightItinerary']['Segments'], $ticketDetailsIBNOLCC['Response']['FlightItinerary']['FareRules']));
                    //Mail::to($input['adult_email_1'])->send(new FlightBookingEmail($booking, $payments, $ticketDetailsIBNOLCC['Response']['FlightItinerary']['Segments'], $ticketDetailsIBNOLCC['Response']['FlightItinerary']['FareRules']));

                    $this->emptySession();
                    //send to thank you page
                    return redirect('/thankyou/flight/' . $booking->id . '/true');
                } else {
                    $message = $this->TicketResultIB['Response']['Error']['ErrorMessage'];
                    // echo "<pre>";print_r($message);echo"</pre>";
                    return view('500')->with(['error' => $message]);
                }
            }
        }

    }

    public function cancelFlightBooking(Request $request) {

        if ($request->isMethod('post')) {

            $input = $request->all();
            $input['TicketId'] = unserialize($input['TicketId']);

            $Sectors = [
                "Origin" => $input['Origin'],
                "Destination" => $input['Destination'],
            ];

            $postData = [
                "EndUserIp" => $this->api->userIP,
                "TokenId" => $this->api->tokenId,
                "BookingId" => $input['BookingId'],
                "RequestType" => 1,
                "CancellationType" => 3,
                //"Sectors" =>  [$Sectors],
                //"TicketId" =>  $input['TicketId'],
                "RequestType" => 1,
                "Remarks" => $input['remarks']
            ];

            $cancelflights = $this->api->SendChangeRequest($postData);

            if (isset($cancelflights['Response']) && isset($cancelflights['Response']['ResponseStatus']) && $cancelflights['Response']['ResponseStatus'] == 1) {

                $requestInfo = $cancelflights['Response']['TicketCRInfo'];
                $change_request_id = $requestInfo[0]['ChangeRequestId'];

                $newpostData = [
                    "EndUserIp" => $this->api->userIP,
                    "TokenId" => $this->api->tokenId,
                    "ChangeRequestId" => $change_request_id
                ];

                $getChangeRequestData = $this->api->GetChangeRequest($newpostData);
                //print_r($getChangeRequestData);die;
                if (isset($getChangeRequestData['Response']['ResponseStatus'])) {

                    if (isset($getChangeRequestData['Response']['RefundedAmount'])) {
                        $refund_amount = $getChangeRequestData['Response']['RefundedAmount'];
                    } else {
                        $refund_amount = 0;
                    }
                    if (isset($getChangeRequestData['Response']['CancellationCharge'])) {
                        $cancellation_charge = $getChangeRequestData['Response']['CancellationCharge'];
                    } else {
                        $cancellation_charge = 0;
                    }
                }


                //echo "<pre>"; print_r($requestInfo);die;

                if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 0) {
                    $statusText = 'NotSet';
                    $message = 'Booking Status: NotSet';
                } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 1) {
                    $statusText = 'Successfull';
                    $message = 'Booking Status: Successfull';
                } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 2) {
                    $statusText = 'Failed';
                    $message = 'Booking Status: Failed';
                } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 3) {
                    $statusText = 'InValidRequest';
                    $message = 'Booking Status: InValidRequest';
                } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 4) {
                    $statusText = 'InValidSession';
                    $message = 'Booking Status: InValidSession';
                } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 5) {
                    $statusText = 'InValidCredentials';
                    $message = 'Booking Status: InValidCredentials';
                } else {
                    $statusText = 'NotSet';
                    $message = 'Booking Status: NotSet';
                }

                if($refund_amount > 0) {
                    //conver refund amount to USD
                    $lAmount = Currency::convert('INR', 'USD', $refund_amount);
                    $refund_amount = $lAmount['convertedAmount'];
                }
                FlightBookings::where(['booking_id' => $input['BookingId']])
                        ->update(["refund_status" => $statusText, 'refund_amount' => $refund_amount, 'cancellation_charge' => $cancellation_charge, 'change_request_id' => $change_request_id]);

                return redirect('/user/bookings')->with('success', $message);
            }
        }
    }

    public function cancelBookingStatus(Request $request) {
        if ($request->isMethod('post')) {
            $input = $request->all();
            $postData = array('TokenId' => $input['TokenId'], 'EndUserIp' => $this->api->userIP, 'ChangeRequestId' => $input['ChangeRequestId']);
            $getChangeRequestData = $this
                    ->api
                    ->GetChangeRequest($postData);
            $bookingStatus = '';
            $refunded_amount = 0;
            
            //check if refund is already procssed
            $checkRefund = FlightBookings::where(['user_id' => Auth::user()->id, 'change_request_id' => $input['ChangeRequestId']])->first();

            if(isset($checkRefund) && $checkRefund['refunded_amount'] > 0) {

                return response()->json(array(
                            'message' => "Your refund request has been completed",
                            'bookingStatus' => "completed",
                            'refunded_amount' => $checkRefund['refunded_amount']
                ));

            } else {
                
                if (isset($getChangeRequestData['Response']['ResponseStatus'])) {
                    $message = '';
                    $bookingStatus = 'Confirmed';
                    if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 0) {
                        $message = 'Invalid request values, please try again.';
                        $bookingStatus = 'NotSet';
                    } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 1) {
                        $message = 'Booking cancellation request is unassigned.';
                        $bookingStatus = 'Unassigned';
                    } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 2) {
                        $message = 'Booking cancellation request is in progress.';
                        $bookingStatus = 'Assigned';
                    } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 3) {
                        $message = 'Booking cancellation request is acknowledged.';
                        $bookingStatus = 'Acknowledged';
                    } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 4) {
                        $message = 'Booking cancellation request is completed.';
                        $bookingStatus = 'Completed';
                    } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 5) {
                        $message = 'Booking cancellation request is rejected.';
                        $bookingStatus = 'Rejected';
                    } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 6) {
                        $message = 'Booking cancellation request is closed.';
                        $bookingStatus = 'Closed';
                    } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 7) {
                        $message = 'Booking cancellation request is pending.';
                        $bookingStatus = 'Pending';
                    } else if ($getChangeRequestData['Response']['ChangeRequestStatus'] == 6) {
                        $message = 'Invalid request values, please try again.';
                        $bookingStatus = 'Other';
                    }

                    /*
                     * Update Booking Status
                     */
                    $refunded_amount = (isset($getChangeRequestData['Response']['RefundedAmount'])) ? $getChangeRequestData['Response']['RefundedAmount'] : 0;

                    $cancellation_charge = (isset($getChangeRequestData['Response']['CancellationCharge'])) ? $getChangeRequestData['Response']['CancellationCharge'] : 0;

                    if($refund_amount > 0) {
                        //conver refund amount to USD
                        $lAmount = Currency::convert('INR', 'USD', $refund_amount);
                        $refund_amount = $lAmount['convertedAmount'];
                    }

                    FlightBookings::where(['user_id' => Auth::user()->id, 'change_request_id' => $input['ChangeRequestId']])
                            ->update(['refund_status' => $bookingStatus, 'refund_amount' => $refunded_amount, 'cancellation_charge' => $cancellation_charge]);
                } else {
                    $message = 'Unable to Cancel Booking at this time.';
                }

                return response()->json(array(
                            'message' => $message,
                            'bookingStatus' => $bookingStatus,
                            'refunded_amount' => $refunded_amount
                ));
            }
        }
    }

    public function sendFlightEmail(Request $request) {
       
        //$searchData = Session::get('flightSearhData');
        $search_id = $request->searchId;
        $flights = $request->data;
        
        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
        $searchData = $search_contents['request']['input'];

        $searchData['currency'] = Session::get('currency');     
        $searchData['iniscomm'] = env('INIS_VAL_FLIGHT');
        $searchData['conversion'] = env('INR_FEES');

        //  echo "<pre>";
        // print_r($searchData);
        
        // die();


        Mail::to($request->email)->send(new FlightsEmail($searchData, $flights));

         return response()->json(array(
                        'message' => "Email sent",
                        'success' => true
            ));

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

    public function emptySession() {

        Session::forget('flightSearhData');
    }

     public function saveSearchData($file, $content) {
        $destinationPath=public_path()."/logs/searches/flights/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function readSearchData($file) {
        $destinationPath=public_path()."/logs/searches/flights/";
        return $file = File::get($destinationPath.$file);
    }

    public function readSearchDataILS($file) {
        $destinationPath=public_path()."/logs/searches/hotels/";
        return $file = File::get($destinationPath.$file);
    }


}
