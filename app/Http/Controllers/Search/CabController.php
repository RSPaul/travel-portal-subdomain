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
use App\Models\Cabs;
use App\Models\Bookings;
use App\Models\Payments;
use App\Models\Currencies;
use App\Models\Cities;
use App\Models\Reviews;
use App\Models\HotelInfos;
use App\Models\AffiliateUsers;
use App\Models\Posts;
use App\Models\NotificationAgents;
use App\Services\TBOCabAPI;
use Log;
use Stripe\Stripe;

use App\Mail\NewUserRegister;
use App\Mail\CabBookingEmail;
use App\Mail\CabsEmail;
use App\Mail\FailedPaymentEmail;
use Currency;
use Config;
use File;

class CabController extends Controller
{
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

    public function __construct(Request $request)
    {
        ini_set('max_execution_time', 240);
        $this->api = new TBOCabAPI();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? \Request::ip() ?? '3.64.135.96';
        if ($ip == '127.0.0.1') {
            $ip = '157.39.225.231';
        }
        if (count(explode(', ', $ip)) > 1) {
              $this->end_user_ip = explode(', ', $ip)[0];
        } else {
              $this->end_user_ip = $ip;
        }

        //if (!Session::has('CurrencyCode')) {
            // $location = \Location::get($this->end_user_ip);
            // //get user currency
            // $countryInfo = Currencies::where('code', $location->countryCode)->first();
            // //Session::put('CurrencyCode', $countryInfo['currency_code']);
            
            // $ourCurrency = Config::get('ourcurrency');

            // if (in_array($countryInfo['currency_code'], $ourCurrency)) {
            //     Session::put('CurrencyCode', $countryInfo['currency_code']);
            // } else {
            //     Session::put('CurrencyCode', 'USD');
            // }
            
        //}

    }

    public function search(Request $request){
      $cabs = array();
      $this->temp = 'set value ';
      $input = $request->all();

      $now = date('Y-m-d');


      if(isset($input['transferdate'])) {
        $date = date('Y-m-d', strtotime($input['transferdate']));

        if ($date < $now) {
            $new_date = date('Y-m-d', strtotime('+5 days'));
            $input['transferdate'] = $new_date;
        }
      }

      $isAgent = false;
        if(Auth::user()) {
          $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();
          if(isset($agent) && !empty($agent)) {
              $isAgent = true;
        }
      }

      
      
      return view('search.cabs.cabs')->with(['cabs' => $cabs, 'input' => $input, 'referral' => $_GET['referral'], 'isAgent' => $isAgent]);
    }


    public function searchCabs(Request $request) {


      //$input = $request->all();
      Session::put('active_tab', 'cabs');
      $postJson = file_get_contents('php://input');
      $postArray = json_decode($postJson, true);
      $input = array();

      foreach($postArray as $post) {
        foreach($post as $key => $p) {
          $input[$key] = $p;
        }
      }

      $this->api = new TBOCabAPI();

      $input['transferdate'] = date('Y-m-d', strtotime($input['transferdate']));
      Session::put('currency', $input['preffered_currency']);
      Session::put('CountryCode', $input['preffered_currency']);


      $childAges = array();
      for($i = 1; $i <= $input['childsFC']; $i++) {

        if($input['childsFC'] > 0) {
          array_push($childAges, $input['childAge'.$i]);

        } else {
          $childAges = null;
        }

      }

      $now = date('Y-m-d');
      $date = date('Y-m-d', strtotime($input['transferdate']));

      if ($date < $now) {
          $new_date = date('Y-m-d', strtotime('+5 days'));
          $input['transferdate'] = $new_date;
      }

      $postData = [
            "CountryCode" => $input['country'],
            "CityId" => $input['city_cab_id'],
            "PickUpCode" => $input['pick_up'],
            "DropOffCode" => $input['drop_off'],
            "PickUpPointCode" => ($input['pick_up'] == '0') ? $input['pick_up_point_acc'] : $input['pick_up_point'],
            "DropOffPointCode" => ($input['drop_off'] == '0') ? $input['drop_off_point_acc']  : $input['drop_off_point'],
            "TransferDate" => $input['transferdate'],
            "TransferTime" =>  $input['time'],
            "AdultCount" => $input['adultsFC'],
            "ChildCount" => $input['childsFC'],
            "ChildAge" => $childAges,
            "PreferredLanguage" =>  $input['preffered_language'],
            "AlternateLanguage" =>  $input['alternate_language'],
            "PreferredCurrency" => $input['preffered_currency'],
            "BookingMode" => 5,
            //"ClientId" => "ApiIntegrationNew",
            "EndUserIp" => $this->api->userIP,
            "TokenId" => $this->api->tokenId,
        ];


      $this->cabs = $this->api->cabSearch($postData);

       $input['ages_child'] = $childAges;
      // Session::put('CabsSearchData',$input);

      if ($input['referral'] != '')
        {


          $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();

          //$commisioninis = $checkrefferal['commission'];
          if (isset($checkrefferal))
          {

            $agent_id = $checkrefferal['user_id'];
            $commission = env('INIS_VAL_CAB');
          }
          else
          {
              $agent_id = '';
              $commission = env('INIS_VAL_CAB');
          }

      }
      else
      {

          $agent_id = '';
          $commission = env('INIS_VAL_CAB');

      }

      $conversion = env('CONVERSION_VAL_CAB');
      if($this->cabs['TransferSearchResult']['ResponseStatus'] == 1) {

          $this->setCookie('cab_session', time() + (60 * 13), 20);
          $fileName = time() + (60 * 13);

          foreach ($this->cabs['TransferSearchResult']['TransferSearchResults'] as $key => $cab) {
            // echo "<pre>";
            // print_r($cab['Vehicles'][0]['TransferPrice']);
            $tdsVal = ((env('INIS_TDS') / 100) * ( $cab['Vehicles'][0]['TransferPrice']['OfferedPriceRoundedOff'] ));
            $inis_markup = (($commission / 100) * ( $cab['Vehicles'][0]['TransferPrice']['OfferedPriceRoundedOff'] + $tdsVal ));
            $price_with_markup = $inis_markup + $cab['Vehicles'][0]['TransferPrice']['OfferedPriceRoundedOff'] + $tdsVal;

            if($input['preffered_currency'] == 'ILS'){

                $inis_markup = ((env('INIS_VAL_PAYME') / 100) * ($cab['Vehicles'][0]['TransferPrice']['OfferedPriceRoundedOff'] + $tdsVal ));
                $price_with_markup = $inis_markup + $cab['Vehicles'][0]['TransferPrice']['OfferedPriceRoundedOff'] + $tdsVal;

                $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;

                $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                $price_with_markup = $price_with_markup + env('PAYME_FIX_FEES') + $vat + $taxes;

            }else{

                $taxes = ($conversion / 100) * $price_with_markup;
                $price_with_markup = $price_with_markup + $taxes;
            }

            $this->cabs['TransferSearchResult']['TransferSearchResults'][$key]['FinalPrice'] = $price_with_markup;

          }
         // die();
          $fileContents = json_encode(array('request' => $input, 'response' => $this->cabs['TransferSearchResult']['TransferSearchResults']));
          $this->saveSearchData($fileName . '.json', $fileContents);

          return response()->json(array('cabs' => $this->cabs['TransferSearchResult']['TransferSearchResults'], 'traceId' => $this->cabs['TransferSearchResult']['TraceId'],'input_data' => $input,'status' => true, 'commission_inis' => $commission, 'conversion' => $conversion, 'search_id' => $fileName));

      } else {

          return response()->json(array('cabs' => array(), 'message' => $this->cabs['TransferSearchResult']['Error']['ErrorMessage'],'input_data' => $input, 'status' => false));
      }

    }


    public function cabs(Request $request) {

      Session::put('selectedTab','cab');

      return view('search.cabs.cabs')->with(['cabs' => "", 'currencies' => "", 'hotelDetails' => ""]);

    }

    public function cabDetails (Request $request) {

      $destinationPath=public_path()."/logs/searches/cabs/" . $request->search_id . ".json";
      if (file_exists($destinationPath)){
        $search_contents = json_decode($this->readSearchData($request->search_id.'.json'), true);
        $cabs = $search_contents['response'];

        foreach ($cabs as $key => $cab) {
          # code...

          if($cab['CategoryId'] == $request->category && $cab['ResultIndex'] == $request->index) {
            $selected_cab = $cab;
          }
        }

        /* Get Cancellation Policy */

        $postData = [
              "ResultIndex" => $selected_cab['ResultIndex'],
              "TransferCode" => $selected_cab['TransferCode'],
              "VehicleIndex" => array( $selected_cab['Vehicles'][0]['VehicleIndex'] ),
              'AgencyId' => $this->api->agencyId,
              "BookingMode" =>  5,
              //"ClientId" => "ApiIntegrationNew",
              'TraceId' => $request->traceId,
              "EndUserIp" => $this->api->userIP,
              "TokenId" => $this->api->tokenId,
          ];

        $cancellationData = $this->api->cabCancellationPlcy($postData);

        if ($request->referral != '' && $request->referral != '0')
          {

            $checkrefferal = AffiliateUsers::where(['referal_code' => $request->referral])->first();
            //$commisioninis = $checkrefferal['commission'];
            if (isset($checkrefferal))
            {

              $agent_id = $checkrefferal['user_id'];
              $commission = env('INIS_VAL_CAB');
            }
            else
            {
                $agent_id = '';
                $commission = env('INIS_VAL_CAB');
            }

        }
        else
        {

            $agent_id = '';
            $commission = env('INIS_VAL_CAB');

        }

        $cabsearchData =  $search_contents['request'];//Session::get('CabsSearchData');

        // echo "<pre>"; print_r($postData); print_r($cancellationData);die;
        if($cancellationData['GetCancellationPolicyResult']['ResponseStatus'] == 1) {
          $getcancellationData = $cancellationData['GetCancellationPolicyResult']['TransferCancellationPolicies']['TransferCancellationPolicy'];
           $lastcdate = $cancellationData['GetCancellationPolicyResult']['TransferCancellationPolicies']['LastCancellationDate'];
        } else {

          $amt = Session::get('multiplePayments');

          if(isset($amt) && $amt > 0 ){

              $walletuser = \Auth::user();
              $ccrcy = Session::get('CurrencyCode');

              $walletAmount = \Auth::user()->balance;

              $amt =  $amt;

              $pAmount = Currency::convert($ccrcy, 'USD', round($amt));

              $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);

              Session::forget('multiplePayments');
          }

          $getcancellationData = array();
          return view('500')->with(['error' => $cancellationData['GetCancellationPolicyResult']['ErrorMessage']]);
        }

        $queryValues = $request->query();
        $paymentVal = false;

        $search_id = $request->search_id;
        $paidAmtILS = 0;

        if(isset($queryValues['payme_sale_id']) && $queryValues['payme_sale_id'] != ''){

          $saleID = $queryValues['payme_sale_id'];

          $paymentDetails = $this
                  ->api
                  ->checkPaymePayment(env('PAYME_KEY'), $saleID);
          $payMEDetails = $paymentDetails['items'];

          if(!empty($payMEDetails) && $payMEDetails[0]['sale_status'] == 'completed'){
            $paymentVal = true;
            $destinationPath=$search_id . '_payme_form_cab.json';
            $ilsPay = json_decode($this->readSearchDataILS($destinationPath), true);///Session::get('BookRoomDetails');
            $ilsPayDetails = $ilsPay['request'];

            //$paidAmtILS = ($ilsPayDetails['paidAmtILS'] == 0) ? ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'];

            // Add money to wallet on cab 

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

            $pendingAmount = $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME'] - $paidAmtILS;


             if($ilsPayDetails['walletDebit'] > 0){

                  $walletuser = \Auth::user();
                  $ccrcy = Session::get('CurrencyCode');
                  $pAmount = Currency::convert($ccrcy, 'USD', $ilsPayDetails['walletDebit']);

                  $walletuser->withdraw(round($pAmount['convertedAmount']), ['description' => "Card payment " . $ilsPayDetails['walletDebit'] . "(" . $ccrcy . ") received from card for Multiple Payment transaction_id :- " . $payMEDetails[0]['transaction_id']]);

             }

            if($pendingAmount <= 0) {

              $cabId = $this->bookCabILS($ilsPayDetails);

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
                  $walletuser->withdraw($debitAmnt, ['description' => 'Payment withdraw from account for single cab booking.']);
                  
                  $walletAmount = \Auth::user()->balance;
                  Session::forget('walletAmount');
              }


              if(isset($cabId['success']) && $cabId['success']){

                  return redirect('/thankyou/cab/' . $cabId['booking_id'] . '/true');

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

          } else {
                $paymentVal = false;

                $destinationPath=$search_id . '_payme_form_cab.json';
                
                $ilsPay = json_decode($this->readSearchDataILS($destinationPath), true);///Session::get('BookRoomDetails');
                $ilsPayDetails = $ilsPay['request'];

                $this->writePaymeLogs($ilsPayDetails, $payMEDetails);

                Mail::to(env('NO_RESULT_EMAIL'))->send(new FailedPaymentEmail(json_encode($ilsPayDetails), json_encode($payMEDetails) ));

                return view('500')->with(['error' => 'Payment Failed']);
          }
        }

        return view('search.cabs.view-cab')->with(['cab' => $selected_cab,'traceId' => $request->traceId, 'cancellation_data' => $getcancellationData, 'last_cancel_date' => $lastcdate, 'currency_code' => $request->currency_code, 'commission' => $commission, 'referral' => $request->referral, 'input' => $cabsearchData, 'search_id' => $request->search_id,'payme_pay' => $paymentVal, 'paidAmtILS' => $paidAmtILS]);
      } else {
        // Send Money back to Wallet for Single Payment
            $queryValues = $request->query();
            $saleID = $queryValues['payme_sale_id'];

            $paymentDetails = $this
                   ->api
                   ->checkPaymePayment(env('PAYME_KEY'), $saleID);
            $payMEDetails = $paymentDetails['items'];

            if(!empty($payMEDetails) && $payMEDetails[0]['sale_status'] == 'completed'){


                $destinationPath=$request->search_id . '_payme_form_cab.json';
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

    public function bookCab(Request $request) {


      if(Auth::check()) {

        $user = User::where('id', Auth::user()->id)->first();
      } else {
        $user = array('name' => '', 'email' => '', 'phone' => '', 'address' => '');
      }


      if($request->isMethod('post')) {
        $input = $request->all();

        // $cabs = Session::get('Cabs');
        $search_contents = json_decode($this->readSearchData($input['search_id'].'.json'), true);
        $cabs = $search_contents['response'];
        $cab_name = '';
        // echo "<pre>";
        // print_r($cabs);
        foreach ($cabs as $key => $cab) {
          if($cab['ResultIndex'] == $input['result_index']) {
            foreach ($cab['Vehicles']  as $key => $c) {
              if($c['VehicleIndex'] == $input['vehicle_index']) {
                    $selected_cab = $c;
                    unset($selected_cab['PickUp']);
                    unset($selected_cab['IsPANMandatory']);
                    unset($selected_cab['LastCancellationDate']);
                    unset($selected_cab['TransferCancellationPolicy']);
                    unset($selected_cab['TransferPrice']['GST']);
                    $cab_name = $cab['TransferName'];
                    $input['currency_code'] = $selected_cab['TransferPrice']['CurrencyCode'];
                    $input['base_price'] = $selected_cab['TransferPrice']['BasePrice'];
                    $input['offered_price'] = $selected_cab['TransferPrice']['OfferedPriceRoundedOff'];
              }
            }
          }
        }


       $activityPassengerArr = array();

       $cabsearchData =  $search_contents['request'];//Session::get('CabsSearchData');

       $Paxcount = $cabsearchData['adultsFC'] +  $cabsearchData['childsFC'];


        for ($ch=1; $ch <= $cabsearchData['adultsFC'] ; $ch++) {

            array_push($activityPassengerArr, array(
                                                 'PaxId' => 0,
                                                 'Title' => 'Mr.',
                                                 'FirstName' => $input['passenger_first_name'],
                                                 'LastName' => $input['passenger_last_name'],
                                                 'PaxType' => 0,
                                                 'Age' => 0,
                                                 'ContactNumber' => $input['phone'],
                                                 'PAN' => ''
                                              )
                                      );
        }

        for ($ch=1; $ch <= $cabsearchData['childsFC'] ; $ch++) {

            array_push($activityPassengerArr, array(
                                                 'PaxId' => 1,
                                                 'Title' => 'Mr.',
                                                 'FirstName' => $input['passenger_first_name'],
                                                 'LastName' => $input['passenger_last_name'],
                                                 'PaxType' => 1,
                                                 'Age' => $cabsearchData['ages_child'][$ch-1],
                                                 'ContactNumber' => $input['phone'],
                                                 'PAN' => ''
                                              )
                                      );
        }


        $book_request = array(
                              'IsVoucherBooking' => true,
                              'NumOfPax' => $Paxcount,
                              'PassengerInfo' => null,
                              'PaxInfo' => $activityPassengerArr,
                              'PickUp' => array('PickUpDetailName' => $input['pickup_detailname'],
                                                'PickUpDetailCode' => $input['pickup_detail_code'],
                                                'Description' => $input['pickup_description'],
                                                'Remarks' => null,
                                                'Time' => $input['pickup_time'],
                                                "PickUpDate" => $input['pickup_date'],
                                                "AddressLine1" => null,
                                                "City" => null,
                                                "Country" => null,
                                                "ZipCode" => null,
                                                "AddressLine2" => null),
                              'DropOff' => array('DropOffDetailName' => $input['dropoff_detailname'],
                                                'DropOffDetailCode' => $input['dropoff_detail_code'],
                                                'Description' => $input['dropoff_description'],
                                                'Remarks' => null,
                                                'Time' => $input['dropoff_time'],
                                                "PickUpDate" => null,
                                                "AddressLine1" => null,
                                                "City" => null,
                                                "Country" => null,
                                                "ZipCode" => null,
                                                "AddressLine2" => null),
                              'Vehicles' => [$selected_cab],
                              'ResultIndex' => $input['result_index'],
                              'TransferCode' => $input['transfer_code'],
                              'VehicleIndex' => array( $input['vehicle_index'] ),
                              "BookingMode" => 5,
                              'OccupiedPax' => [array('AdultCount' => $cabsearchData['adultsFC'],
                                                    'ChildCount' => $cabsearchData['childsFC'],
                                                    'ChildAge' => $cabsearchData['ages_child'])],
                              'EndUserIp' => $this->api->userIP,
                              'TokenId' => $this->api->tokenId,
                              'TraceId' => $input['trace_id'],
                              //"ClientId" =>  "ApiIntegrationNew",
                              'AgencyId' => $this->api->agencyId);

        try{

            /*
            * First check if customer exists in the DB
            */

            if(Auth::user()) {
                $user = Auth::user();
            } else {
                $user = User::where('email', $input['passenger_email'])->first();
            }

            if(isset($user) && $user->id) {
              $customer_id = $user->customer_id;
              Auth::login($user);
            } else {
              //create new user
              $password = $this->generateRandomString();
              $user = User::create([
                        'name' => $input['passenger_first_name'] .' '. $input['passenger_last_name'],
                        'email' => $input['passenger_email'],
                        'phone' => $input['phone'],
                        'address' => ' ',
                        'role' => 'user',
                        'password' => Hash::make($password),
                        'password_changed' => 1,
                    ]);
              Auth::login($user);
              Mail::to($input['passenger_email'])->send(new NewUserRegister($user, $password));
            }


            if ($request->referral != '' && $request->referral != '0')
              {

                $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request->referral])->first();
                //$commisioninis = $checkrefferal['commission'];
                if (isset($checkrefferal))
                {

                  $agent_id = $checkrefferal['user_id'];
                  $commission = env('INIS_AGENT_VAL_CAB');
                  $agentemail = User::select('email')->where(['id' => $agent_id])->first();
                  $agentemail = $agentemail['email']; 
                }
                else
                {
                    $agent_id = '';
                    $agentemail = '';
                    $commission = env('INIS_AGENT_VAL_CAB');
                }

            }
            else
            {

                $agent_id = '';
                $agentemail = '';
                $commission = env('INIS_VAL_CAB');

            }

            $total_commision = env('INIS_VAL_CAB');

            $amount = $selected_cab['TransferPrice']['OfferedPriceRoundedOff'];
           // $amount = $amount + $selected_cab['TransferPrice']['OfferedPriceRoundedOff'];
            $commission_price = ($commission / 100 * $amount);


            $partners_commision = env('PARTNER_COMMISION_ACTIVITY');

            if ($request->referral != '' && $request->referral != '0'){

                $rest_commision =(env('INIS_VAL_CAB') / 100 * $amount) - (env('INIS_AGENT_VAL_CAB') / 100 * $amount);

                $partners_commision = ( ($partners_commision / 100) * $rest_commision);

                //$partners_commision = 

            } else {

                $partners_commision = ( ( $partners_commision / 100 ) * $commission_price);
            }


            $amount = $amount + ($total_commision / 100 * $amount);
            $conversion_payment = 0;
            if(Session::get('currency') == 'ILS') {
                $conversion = env('CONVERSION_VAL_CAB');
            } else {
                $conversion = env('CONVERSION_VAL_CAB');
            }
            $conversion_payment = ( $conversion / 100 * $amount );
            $amount = $amount + $conversion_payment;
            // * 100;
            //echo $amount; die();

            $pAmount = Currency::convert('ILS', 'USD', round($input['ORIGINAL_BOOKING_PRICE_PME']));
            if (isset($input['walletPay']) && $input['walletPay'] == 'no') {
              $walletuser = \Auth::user();
              $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $input['ORIGINAL_BOOKING_PRICE_PME']]);
            }

            $bookRCabData = $this->api->bookCab($book_request);

            if(isset($bookRCabData['BookResult']) && isset($bookRCabData['BookResult']['ResponseStatus']) && $bookRCabData['BookResult']['ResponseStatus'] == 1){



                if (isset($input['walletPay']) && $input['walletPay'] == 'yes') {

                    $pAmount = Currency::convert('ILS', 'USD', round($input['walletDebit']));

                    $walletuser = \Auth::user();
                    $walletuser->withdraw($pAmount['convertedAmount'], ['BookingID' => $bookRCabData['BookResult']['BookingId']]);

                }else{
                    $pAmount = Currency::convert('ILS', 'USD', round($input['ORIGINAL_BOOKING_PRICE_PME']));
                    $walletuser = \Auth::user();
                    $walletuser->withdraw($pAmount['convertedAmount'], ['BookingID' => $bookRCabData['BookResult']['BookingId']]);
                } 

                $walletAmount = \Auth::user()->balance;
                Session::put('walletAmount', $walletAmount);

                $postData = [
                    "BookingId" => $bookRCabData['BookResult']['BookingId'],
                    "AgencyId" => $this->api->agencyId,
                    "EndUserIp" => $this->api->userIP,
                    "TokenId" => $this->api->tokenId,
                ];

                //$bookRDetailCabData = $this->api->bookDetailCab($book_request);
                // $request_array = array_merge($book_request, $input);
                $input['amount'] = $amount;
                $input['commission_price'] = $commission_price;
                $input['conversion_payment'] = $conversion_payment;
                $input['selected_cab'] = $selected_cab;

                $lAmount= Currency::convert(Session::get('currency'), 'USD', $commission_price);
                $commission_price = $lAmount['convertedAmount'];

               // $input['amount'] = $amount / 100;
                $booking = Bookings::create(['booking_id' => $bookRCabData['BookResult']['BookingId'],
                                    'type' => 'cab',
                                    'trace_id' => $bookRCabData['BookResult']['TraceId'],
                                    'user_id' => Auth::user()->id,
                                    'token_id' => $this->api->tokenId,
                                    'status' => 'Booked',
                                    'hotel_booking_status' => $bookRCabData['BookResult']['BookingStatus'],
                                    'invoice_number' =>  '',
                                    'confirmation_number' => $bookRCabData['BookResult']['ConfirmationNo'],
                                    'booking_ref' => $bookRCabData['BookResult']['BookingRefNo'],
                                    'price_changed' => 0,
                                    'cancellation_policy' => $input['cancellation_policy'],
                                    'last_cancellation_date' =>  $input['last_cancellation_date'],
                                    'request_data' => json_encode($input) ]);

                $mAmount = Currency::convert(Session::get('currency'), 'USD', $amount);
                $mAmountC = $mAmount['convertedAmount'];
                //create entry to payments table

                $convertPrtnr = Currency::convert(Session::get('currency'), 'USD', $partners_commision );
                $convertPrtnrAmount = $convertPrtnr['convertedAmount'];
   


                $payments = Payments::create(['booking_id' => $booking->id,
                                    'user_id' => Auth::user()->id,
                                    'agent_id' => $agent_id,
                                    'commission' => $commission_price,
                                    'price' => $amount,
                                    'price_convered' => $mAmountC,
                                    'agent_markup' => '',
                                    'partners_commision' => $convertPrtnrAmount,
                                    'partners_commision_rest' => $convertPrtnrAmount,
                                    'customer_id' => '',
                                    'sub_domain' => '']);
                

                $booking->request_data = json_decode($booking->request_data, true);

                if(strpos($input['selected_cab']['Vehicle'], 'Car') !== false) {
                  $post_image = env('APP_URL') . '/images/Car.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'Minibus') !== false) {
                  $post_image = env('APP_URL') . '/images/Minibus.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'Sedan') !== false) {
                  $post_image = env('APP_URL') . '/images/Sedan.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'Minivan') !== false) {
                  $post_image = env('APP_URL') . '/images/Minivan.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'SUV') !== false) {
                  $post_image = env('APP_URL') . '/images/SUV.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'Adapted') !== false) {
                  $post_image = env('APP_URL') . '/images/Adapted.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'Bus') !== false) {
                  $post_image = env('APP_URL') . '/images/Bus.png';
                }
                else  {
                  $post_image = env('APP_URL') . '/images/Car.png';
                }   

                if(isset($agent_id) && $agent_id != ''){ 
                  
                  $post_content = "New cab booking for <b>" . $input['selected_cab']['Vehicle'] . "</b> pickup on <b>". date('l, F jS, Y', strtotime(str_replace('/' , '-', $input['pickup_date']) )) . " " . date('h:i:s', strtotime(str_replace('/' , '-', $input['pickup_time']) )) ."</b>.<br>Pickup from <b>". $input['pickup_detailname'] ."</b> and drop off to <b>". $input['dropoff_detailname'] ."</b><br> Total paid <b>" . $input['selected_cab']['TransferPrice']['CurrencyCode'] . ' ' . number_format($payments->price,2) . '</b>';
                      //create story for profile page
                  Posts::create(['post_type' => 'article_image',
                            'post_content' => $post_content,
                            'post_media' => $post_image,
                            'user_id' => Auth::user()->id]);

                  $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                                'type' => 'cab',
                                'description' => $post_content,
                                'price' => ' USD ' . number_format($commission_price,2),
                                'status' => 0
                            ]);
                }

                Mail::to($input['passenger_email'])->send(new CabBookingEmail($booking, '', $payments, '', ''));

                if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                    Mail::to($agentemail)->send(new CabBookingEmail($booking, '', $payments, '', ''));
                      
                }


                $this->emptySession($input['search_id']);

                return redirect('/thankyou/cab/' . $booking->id . '/true');//redirect('/user/bookings');

            } else {
              // return view('search.cabs.view-cab')->with(['message' => $message]);
              return view('500')->with(['error' => $bookRCabData['BookResult']['Error']['ErrorMessage']]);
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

          if(isset($message) && $message != ''){
            // Session::flash('error', $message);
            return view('500')->with(['error' => $message]);
          }
         // print_r($bookRCabData); die();
          return view('search.cabs.view-cab')->with(['message' => $message]);
      }

      return redirect('/user/bookings');
    }

    public function bookCabILS($data) {


      if(Auth::check()) {

        $user = User::where('id', Auth::user()->id)->first();
      } else {
        $user = array('name' => '', 'email' => '', 'phone' => '', 'address' => '');
      }


    //  if($request->isMethod('post')) {
        $input = $data;

        // $cabs = Session::get('Cabs');
        $search_contents = json_decode($this->readSearchData($input['search_id'].'.json'), true);
        $cabs = $search_contents['response'];
        $cab_name = '';
        $selected_cab = array();
        
        foreach ($cabs as $key => $cab) {
          # code...
          if($cab['ResultIndex'] == $input['result_index']) {
            foreach ($cab['Vehicles']  as $key => $c) {
          
            //   # code...
              if($c['VehicleIndex'] == $input['vehicle_index']) {
                    $selected_cab = $c;
                    unset($selected_cab['PickUp']);
                    unset($selected_cab['IsPANMandatory']);
                    unset($selected_cab['LastCancellationDate']);
                    unset($selected_cab['TransferCancellationPolicy']);
                    unset($selected_cab['TransferPrice']['GST']);
                    $cab_name = $cab['TransferName'];
                    $input['currency_code'] = $selected_cab['TransferPrice']['CurrencyCode'];
                    $input['base_price'] = $selected_cab['TransferPrice']['BasePrice'];
                    $input['offered_price'] = $selected_cab['TransferPrice']['OfferedPriceRoundedOff'];
              }
            }
          }
        }

     
       $activityPassengerArr = array();

       $cabsearchData =  $search_contents['request'];//Session::get('CabsSearchData');

       $Paxcount = $cabsearchData['adultsFC'] +  $cabsearchData['childsFC'];


        for ($ch=1; $ch <= $cabsearchData['adultsFC'] ; $ch++) {

            array_push($activityPassengerArr, array(
                                                 'PaxId' => 0,
                                                 'Title' => 'Mr.',
                                                 'FirstName' => $input['passenger_first_name'],
                                                 'LastName' => $input['passenger_last_name'],
                                                 'PaxType' => 0,
                                                 'Age' => 0,
                                                 'ContactNumber' => $input['phone'],
                                                 'PAN' => ''
                                              )
                                      );
        }

        for ($ch=1; $ch <= $cabsearchData['childsFC'] ; $ch++) {

            array_push($activityPassengerArr, array(
                                                 'PaxId' => 1,
                                                 'Title' => 'Mr.',
                                                 'FirstName' => $input['passenger_first_name'],
                                                 'LastName' => $input['passenger_last_name'],
                                                 'PaxType' => 1,
                                                 'Age' => $cabsearchData['ages_child'][$ch-1],
                                                 'ContactNumber' => $input['phone'],
                                                 'PAN' => ''
                                              )
                                      );
        }


        $book_request = array(
                              'IsVoucherBooking' => true,
                              'NumOfPax' => $Paxcount,
                              'PassengerInfo' => null,
                              'PaxInfo' => $activityPassengerArr,
                              'PickUp' => array('PickUpDetailName' => $input['pickup_detailname'],
                                                'PickUpDetailCode' => $input['pickup_detail_code'],
                                                'Description' => $input['pickup_description'],
                                                'Remarks' => null,
                                                'Time' => $input['pickup_time'],
                                                "PickUpDate" => $input['pickup_date'],
                                                "AddressLine1" => null,
                                                "City" => null,
                                                "Country" => null,
                                                "ZipCode" => null,
                                                "AddressLine2" => null),
                              'DropOff' => array('DropOffDetailName' => $input['dropoff_detailname'],
                                                'DropOffDetailCode' => $input['dropoff_detail_code'],
                                                'Description' => $input['dropoff_description'],
                                                'Remarks' => null,
                                                'Time' => $input['dropoff_time'],
                                                "PickUpDate" => null,
                                                "AddressLine1" => null,
                                                "City" => null,
                                                "Country" => null,
                                                "ZipCode" => null,
                                                "AddressLine2" => null),
                              'Vehicles' => [$selected_cab],
                              'ResultIndex' => $input['result_index'],
                              'TransferCode' => $input['transfer_code'],
                              'VehicleIndex' => array( $input['vehicle_index'] ),
                              "BookingMode" => 5,
                              'OccupiedPax' => [array('AdultCount' => $cabsearchData['adultsFC'],
                                                    'ChildCount' => $cabsearchData['childsFC'],
                                                    'ChildAge' => $cabsearchData['ages_child'])],
                              'EndUserIp' => $this->api->userIP,
                              'TokenId' => $this->api->tokenId,
                              'TraceId' => $input['trace_id'],
                              //"ClientId" =>  "ApiIntegrationNew",
                              'AgencyId' => $this->api->agencyId);

        try{

            /*
            * First check if customer exists in the DB
            */

            if(Auth::user()) {
                $user = Auth::user();
            } else {
                $user = User::where('email', $input['passenger_email'])->first();
            }

            if(isset($user) && $user->id) {
              $customer_id = $user->customer_id;
              Auth::login($user);
            } else {
              //create new user
              $password = $this->generateRandomString();
              $user = User::create([
                        'name' => $input['passenger_first_name'] .' '. $input['passenger_last_name'],
                        'email' => $input['passenger_email'],
                        'phone' => $input['phone'],
                        'address' => ' ',
                        'role' => 'user',
                        'password' => Hash::make($password),
                        'password_changed' => 1,
                    ]);
              Auth::login($user);
              Mail::to($input['passenger_email'])->send(new NewUserRegister($user, $password));
            }


            if ($input['referral'] != '' && $input['referral'] != '0')
              {

                $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();
                //$commisioninis = $checkrefferal['commission'];
                if (isset($checkrefferal))
                {

                  $agent_id = $checkrefferal['user_id'];
                  $commission = env('INIS_AGENT_VAL_CAB');
                  $agentemail = User::select('email')->where(['id' => $agent_id])->first();
                  $agentemail = $agentemail['email']; 
                }
                else
                {
                    $agent_id = '';
                    $agentemail = '';
                    $commission = env('INIS_AGENT_VAL_CAB');
                }

            }
            else
            {

                $agent_id = '';
                $agentemail = '';
                $commission = env('INIS_VAL_CAB');

            }

            $total_commision = env('INIS_VAL_CAB');

            $amount = $selected_cab['TransferPrice']['OfferedPriceRoundedOff'];
           // $amount = $amount + $selected_cab['TransferPrice']['OfferedPriceRoundedOff'];
            $commission_price = ($commission / 100 * $amount);


            $partners_commision = env('PARTNER_COMMISION_ACTIVITY');

            if ($input['referral'] != '' && $input['referral'] != '0'){

                $rest_commision =(env('INIS_VAL_CAB') / 100 * $amount) - (env('INIS_AGENT_VAL_CAB') / 100 * $amount);

                $partners_commision = ( ($partners_commision / 100) * $rest_commision);

                //$partners_commision = 

            } else {

                $partners_commision = ( ( $partners_commision / 100 ) * $commission_price);
            }


            $amount = $amount + ($total_commision / 100 * $amount);
            $conversion_payment = 0;
            if(Session::get('currency') == 'ILS') {
                $conversion = env('CONVERSION_VAL_CAB');
            } else {
                $conversion = env('CONVERSION_VAL_CAB');
            }
            $conversion_payment = ( $conversion / 100 * $amount );
            $amount = $amount + $conversion_payment;
            // * 100;
            //echo $amount; die();


            $tdsVal = ((env('INIS_TDS') / 100) * ( $selected_cab['TransferPrice']['OfferedPriceRoundedOff'] ));
            $inis_markup = (($commission / 100) * ( $selected_cab['TransferPrice']['OfferedPriceRoundedOff'] + $tdsVal ));
            $price_with_markup = $inis_markup + $selected_cab['TransferPrice']['OfferedPriceRoundedOff'] + $tdsVal;

            if($input['preffered_currency'] == 'ILS'){

                $inis_markup = ((env('INIS_VAL_PAYME') / 100) * ($selected_cab['TransferPrice']['OfferedPriceRoundedOff'] + $tdsVal ));
                $price_with_markup = $inis_markup + $selected_cab['TransferPrice']['OfferedPriceRoundedOff'] + $tdsVal;

                $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;

                $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                $price_with_markup = $price_with_markup + env('PAYME_FIX_FEES') + $vat + $taxes;


                $input['amount'] = $input['BOOKING_PRICE'];
                $input['commission_price'] = $inis_markup;
                $input['conversion_payment'] = $taxes;
              //  $input['selected_cab'] = $selected_cab;

            } else {

               $input['amount'] = $amount;
               $input['commission_price'] = $commission_price;
               $input['conversion_payment'] = $conversion_payment;
            }
           
            $input['selected_cab'] = $selected_cab;


            $bookRCabData = $this->api->bookCab($book_request);

            // if (isset($input['walletPay']) && $input['walletPay'] == 'no') {
            //   $walletuser = \Auth::user();
            //   $pAmount = Currency::convert('ILS', 'USD', round($input['BOOKING_PRICE']));
            //   $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $input['BOOKING_PRICE']]);
            // }

            if(isset($bookRCabData['BookResult']) && isset($bookRCabData['BookResult']['ResponseStatus']) && $bookRCabData['BookResult']['ResponseStatus'] == 1){



                //if (isset($input['walletPay']) && $input['walletPay'] == 'yes' && $input['walletDebit'] > 0) {

                    // $myCurrency = Session::get('currency');
                    // $usercurrency = Currency::convert($myCurrency, 'USD', $input['BOOKING_PRICE']);
                    // $debitAmnt = round($usercurrency['convertedAmount']);

                    // $walletuser = \Auth::user();
                    // $walletuser->withdraw($pAmount['convertedAmount'], ['BookingID' => $bookRCabData['BookResult']['BookingId']]);
                    
                                        
                    // $walletAmount = \Auth::user()->balance;
                    // Session::put('walletAmount', $walletAmount);

               // }


                $postData = [
                    "BookingId" => $bookRCabData['BookResult']['BookingId'],
                    "AgencyId" => $this->api->agencyId,
                    "EndUserIp" => $this->api->userIP,
                    "TokenId" => $this->api->tokenId,
                ];

                //$bookRDetailCabData = $this->api->bookDetailCab($book_request);
                // $request_array = array_merge($book_request, $input);
               

                $lAmount= Currency::convert(Session::get('currency'), 'USD', $commission_price);
                $commission_price = $lAmount['convertedAmount'];

               // $input['amount'] = $amount / 100;
                $booking = Bookings::create(['booking_id' => $bookRCabData['BookResult']['BookingId'],
                                    'type' => 'cab',
                                    'trace_id' => $bookRCabData['BookResult']['TraceId'],
                                    'user_id' => Auth::user()->id,
                                    'token_id' => $this->api->tokenId,
                                    'status' => 'Booked',
                                    'hotel_booking_status' => $bookRCabData['BookResult']['BookingStatus'],
                                    'invoice_number' =>  '',
                                    'confirmation_number' => $bookRCabData['BookResult']['ConfirmationNo'],
                                    'booking_ref' => $bookRCabData['BookResult']['BookingRefNo'],
                                    'price_changed' => 0,
                                    'cancellation_policy' => $input['cancellation_policy'],
                                    'last_cancellation_date' =>  $input['last_cancellation_date'],
                                    'request_data' => json_encode($input) ]);

                $mAmount = Currency::convert(Session::get('currency'), 'USD', $input['amount']);
                $mAmountC = $mAmount['convertedAmount'];
                //create entry to payments table

                $convertPrtnr = Currency::convert(Session::get('currency'), 'USD', $partners_commision );
                $convertPrtnrAmount = $convertPrtnr['convertedAmount'];
   


                $payments = Payments::create(['booking_id' => $booking->id,
                                    'user_id' => Auth::user()->id,
                                    'agent_id' => $agent_id,
                                    'commission' => $commission_price,
                                    'price' => $input['amount'],
                                    'price_convered' => $mAmountC,
                                    'agent_markup' => '',
                                    'partners_commision' => $convertPrtnrAmount,
                                    'partners_commision_rest' => $convertPrtnrAmount,
                                    'customer_id' => '',
                                    'sub_domain' => '']);
                

                $booking->request_data = json_decode($booking->request_data, true);

                if(strpos($input['selected_cab']['Vehicle'], 'Car') !== false) {
                  $post_image = env('APP_URL') . '/images/Car.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'Minibus') !== false) {
                  $post_image = env('APP_URL') . '/images/Minibus.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'Sedan') !== false) {
                  $post_image = env('APP_URL') . '/images/Sedan.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'Minivan') !== false) {
                  $post_image = env('APP_URL') . '/images/Minivan.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'SUV') !== false) {
                  $post_image = env('APP_URL') . '/images/SUV.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'Adapted') !== false) {
                  $post_image = env('APP_URL') . '/images/Adapted.png';
                }
                elseif(strpos($input['selected_cab']['Vehicle'], 'Bus') !== false) {
                  $post_image = env('APP_URL') . '/images/Bus.png';
                }
                else  {
                  $post_image = env('APP_URL') . '/images/Car.png';
                }   

                if(isset($agent_id) && $agent_id != ''){ 
                  
                  $post_content = "New cab booking for <b>" . $input['selected_cab']['Vehicle'] . "</b> pickup on <b>". date('l, F jS, Y', strtotime(str_replace('/' , '-', $input['pickup_date']) )) . " " . date('h:i:s', strtotime(str_replace('/' , '-', $input['pickup_time']) )) ."</b>.<br>Pickup from <b>". $input['pickup_detailname'] ."</b> and drop off to <b>". $input['dropoff_detailname'] ."</b><br> Total paid <b>" . $input['selected_cab']['TransferPrice']['CurrencyCode'] . ' ' . number_format($payments->price,2) . '</b>';
                      //create story for profile page
                  Posts::create(['post_type' => 'article_image',
                            'post_content' => $post_content,
                            'post_media' => $post_image,
                            'user_id' => Auth::user()->id]);

                  $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                                'type' => 'cab',
                                'description' => $post_content,
                                'price' => ' USD ' . number_format($commission_price,2),
                                'status' => 0
                            ]);
                }

                Mail::to($input['passenger_email'])->send(new CabBookingEmail($booking, '', $payments, '', ''));

                if(isset($agent_id) && $agent_id != '' && Auth::user()->email != $agentemail){

                    Mail::to($agentemail)->send(new CabBookingEmail($booking, '', $payments, '', ''));
                      
                }


                $this->emptySession($input['search_id']);
                return array('success' => true, 'booking_id' => $booking->id);
                // return redirect('/thankyou/cab/' . $booking->id . '/true');//redirect('/user/bookings');

            } else {
              // return view('search.cabs.view-cab')->with(['message' => $message]);
              // return view('500')->with(['error' => $bookRCabData['BookResult']['Error']['ErrorMessage']]);
              return array('success' => false, 'message' => $bookRCabData['BookResult']['Error']['ErrorMessage']);
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

          if(isset($message) && $message != ''){
            // Session::flash('error', $message);
           return array('success' => false, 'booking_id' => $message);
          }
         // print_r($bookRCabData); die();
          // return view('search.cabs.view-cab')->with(['message' => $message]);
      //}

      //return redirect('/user/bookings');
    }

    public function cancelCabBooking(Request $request){

      if($request->isMethod('post')) {

        $message = '';

        $input = $request->all();

         $postData = [
            "RequestType" => 4,
            "Remarks" => $input['remarks'],
            'BookingId' => $input['booking_id'],
            "EndUserIp" => $this->api->userIP,
            "TokenId" => $input['token_id'],
        ];

        $sendChangeRequest = $this->api->sendChangeRequest($postData);

        if(isset($sendChangeRequest['TransferChangeRequestResult']) && isset($sendChangeRequest['TransferChangeRequestResult']['ResponseStatus']) && $sendChangeRequest['TransferChangeRequestResult']['ResponseStatus'] == 1){

           $postData = [
            "ChangeRequestId" => $sendChangeRequest['TransferChangeRequestResult']['ChangeRequestId'],
            "EndUserIp" => $this->api->userIP,
            "TokenId" => $input['token_id'],
          ];

          $getChangeRequestStatus = $this->api->getChangeRequestStatus($postData);

          if(isset($getChangeRequestStatus['TransferChangeRequestStatusResult']) && isset($getChangeRequestStatus['TransferChangeRequestStatusResult']['ResponseStatus']) && $getChangeRequestStatus['TransferChangeRequestStatusResult']['ResponseStatus'] == 1){

            $status = $getChangeRequestStatus['TransferChangeRequestStatusResult']['Status'];

            $change_request_id = $getChangeRequestStatus['TransferChangeRequestStatusResult']['ChangeRequestId'];
            if(isset($getChangeRequestStatus['TransferChangeRequestStatusResult']['RefundedAmount'])){

              $refunded_amount = $getChangeRequestStatus['TransferChangeRequestStatusResult']['RefundedAmount'];
            }else{
              $refunded_amount = '';
            }
            if(isset($getChangeRequestStatus['TransferChangeRequestStatusResult']['CancellationCharge'])){

              $cancellation_charge = $getChangeRequestStatus['TransferChangeRequestStatusResult']['CancellationCharge'];
            }else{
              $cancellation_charge = '';
            }

            if($status == 0){
              $statusText = 'NotSet';
              $message = 'Booking Status: NotSet';
            }else if($status == 1){
              $statusText = 'Pending';
              $message = 'Booking Status: Pending';
            }else if($status == 2){
              $statusText = 'InProgress';
              $message = 'Booking Status: InProgress';
            }else if($status == 3){
              $statusText = 'Processed';
              $message = 'Booking Status: Processed';
            }else if($status == 4){
              $statusText = 'Rejected';
              $message = 'Booking Status: Rejected';
            }else{
              $statusText = 'NotSet';
              $message = 'Booking Status: NotSet';
            }

            if($refunded_amount > 0) {
                //conver refund amount to USD
                $lAmount = Currency::convert('ILS', 'USD', $refunded_amount);
                $refunded_amount = $lAmount['convertedAmount'];
            }

            Bookings::where(['booking_id' => $input['booking_id'] ])
                    ->update(["status" => $statusText , 'refunded_amount' => $refunded_amount, 'change_request_id' => $change_request_id, 'cancellation_charge' => $cancellation_charge]);

            return redirect('/user/bookings')->with('success', $message);
          }


        }


      }

    }

    public function cancelBookingStatus(Request $request) {
      if ($request->isMethod('post'))
      {
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
              ->getChangeRequestStatus($postData);
              
          if(isset($getChangeRequest['TransferChangeRequestStatusResult']) && $getChangeRequest['TransferChangeRequestStatusResult']['ResponseStatus'] == 1)
            {
                $message = '';
                $bookingStatus = 'Confirmed';
                if($getChangeRequest['TransferChangeRequestStatusResult']['ChangeRequestStatus'] == 0)
                {
                  $message = 'Invalid request values, please try again.';
                  $bookingStatus = 'Confirmed';
                }
                else if($getChangeRequest['TransferChangeRequestStatusResult']['ChangeRequestStatus'] == 1)
                {
                  $message = 'Booking cancellation request is accepted but pending for processing.';
                  $bookingStatus = 'Processing';
                }
                else if($getChangeRequest['TransferChangeRequestStatusResult']['ChangeRequestStatus'] == 2)
                {
                  $message = 'Booking cancellation request is in progress.';
                  $bookingStatus = 'In Progress';
                }
                else if($getChangeRequest['TransferChangeRequestStatusResult']['ChangeRequestStatus'] == 3)
                {
                  $message = 'Booking cancellation request is completed.';
                  $bookingStatus = 'Cancelled';
                }
                else if($getChangeRequest['TransferChangeRequestStatusResult']['ChangeRequestStatus'] == 4)
                {
                  $message = 'Booking cancellation request is rejected.';
                  $bookingStatus = 'Rejected';
                }

                /*
                * Update Booking Status
                */
                $refunded_amount = (isset($getChangeRequest['TransferChangeRequestStatusResult']['RefundedAmount'])) ? $getChangeRequest['TransferChangeRequestStatusResult']['RefundedAmount'] : 0;

                if($refunded_amount > 0) {
                    //conver refund amount to USD
                    $lAmount = Currency::convert('ILS', 'USD', $refunded_amount);
                    $refunded_amount = $lAmount['convertedAmount'];
                }

                Bookings::where(['user_id' => Auth::user()->id, 'change_request_id' => $input['ChangeRequestId']])
                        ->update(['status' => $bookingStatus, 'refunded_amount' => $refunded_amount]);
            }
            else
            {
              $message = $getChangeRequest['TransferChangeRequestStatusResult']['Error']['ErrorMessage'];
            }

            return response()->json(array(
                  'message' => $message,
                  'bookingStatus' => $bookingStatus,
                  'refunded_amount' => $refunded_amount
              ));
        }
        }
    }

    public function sendCabEmail(Request $request) {
       
        // $searchData = Session::get('CabsSearchData');
        $search_id = $request->search_id;
        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
        $searchData = $search_contents['request'];

        $cabs = array();
        $selected_cabs = $request->data;

        $cab_list = $search_contents['response'];//Session::get('Cabs');
        $cab_found = array();
        
        foreach($selected_cabs as $s_cab) {

            foreach ($cab_list as $key => $cab) {
                if($cab['ResultIndex'] == $s_cab) {
                    $cab_found = $cab;
                }
            }

            array_push($cabs, $cab_found);
        }
        $searchData['commisioninis'] = env('INIS_VAL_CAB');
        $searchData['conversion'] = env('CONVERSION_VAL_CAB');
        
        // echo "<pre>";
        
        // print_r($searchData);
        // die();
        $agent = AffiliateUsers::select('referal_code')->where('user_id', Auth::user()->id)->first();
        Mail::to($request->email)->send(new CabsEmail($searchData, $cabs, $agent));

         return response()->json(array(
                        'message' => "Email sent",
                        'success' => true
            ));

    }

    public function setCookie($name, $value, $time) {
        setcookie($name, $value, time() + (60 * $time), "/");
        return true;
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

    public function emptySession($search_id) {

      Session::forget('Cabs');
      Session::forget('CabsSearchData');

      $search_file = $destinationPath=public_path()."/logs/searches/cabs/" . $search_id . '.json';
      File::delete($search_file);

    }

    public function saveSearchData($file, $content) {
        $destinationPath=public_path()."/logs/searches/cabs/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function readSearchData($file) {
        $destinationPath=public_path()."/logs/searches/cabs/";
        return $file = File::get($destinationPath.$file);
    }

    public function readSearchDataILS($file) {
        $destinationPath=public_path()."/logs/searches/hotels/";
        return $file = File::get($destinationPath.$file);
    }

    public function saveBlockData($file, $content) {
        $destinationPath=public_path()."/logs/searches/cabs/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function getBlockData($file) {
        $destinationPath=public_path()."/logs/searches/cabs/";
        return $file = File::get($destinationPath.$file);
    }

}
