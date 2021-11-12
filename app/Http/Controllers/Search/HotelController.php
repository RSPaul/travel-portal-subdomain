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
use App\Models\Currencies;
use App\Models\Cities;
use App\Models\Posts;
use App\Models\Reviews;
use App\Models\StaticDataHotels;
use App\Models\RoomImages;
use App\Services\TBOHotelAPI;
use App\Mail\HotelBookingEmail;
use App\Mail\FailedPaymentEmail;
use App\Mail\NewUserRegister;
use App\Models\AffiliateUsers;
use App\Models\NotificationAgents;
use Stripe\Stripe;
use PDF;
use App\Models\Lottery;
use App\Models\LotteryUsers;
use App\Mail\LotteryBookingEmail;
use App\Mail\LotteryWonEmail;
use App\Mail\HotelsEmail;
use Currency;
use Log;
use Config;
use App\Models\Receipts;
use App\Mail\MultiCardPayment;
use App\Mail\NoResultsEmail;
use App\Mail\PaymentFailed;
use File;
use DateTime;
use App\Models\Token;
//use App\Models\HotelView;


class HotelController extends Controller {

    public $hotels_list;
    public $temp;
    public $end_user_ip;

    public function __construct(Request $request) {
        ini_set('max_execution_time', 1000);
        ini_set('memory_limit', '1024M');
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? \Request::ip() ?? '3.64.135.96';
        if ($ip == '127.0.0.1') {
            $ip = '132.154.175.244'; //'93.173.228.94'; US = 52.186.25.21 IN = 132.154.175.244
        }
        if (count(explode(', ', $ip)) > 1) {
            $this->end_user_ip = explode(', ', $ip)[0];
        } else {
            $this->end_user_ip = $ip;
        }

        if (!Session::has('CurrencyCode')) {
            $location = \Location::get($this->end_user_ip);
            $countryInfo = Currencies::where('code', $location->countryCode)->first();

            $ourCurrency = Config::get('ourcurrency');

            if (in_array($countryInfo['currency_code'], $ourCurrency)) {
                Session::put('CurrencyCode', $countryInfo['currency_code']);
            } else {
                Session::put('CurrencyCode', 'USD');
            }
        }

        if (Session::has('CurrencyCode')) {
            $lAmount = Currency::convert('USD', Session::get('CurrencyCode'), env('LOTTERY_ELIGIBILITY'));
            if(isset($lAmount['convertedAmount'])){
              $LotteryLimit = round($lAmount['convertedAmount']);
              Session::put('lotteryLimit', $LotteryLimit);
            }else{
                 Session::put('lotteryLimit', env('LOTTERY_ELIGIBILITY'));
            }
        } else {
            Session::put('lotteryLimit', env('LOTTERY_ELIGIBILITY'));
        }

    }

    public function recordPayment(Request $request) {


        try {

            Log::info($request->all());

            /*
             * First check if customer exists in the DB
             */
            $user = User::where('email', $request->email)->first();
            Log::info(["check user:" => $user]);
            if (isset($user) && $user->id) {

                Auth::login($user);
            } else {
                $password = $this->generateRandomString();
                $user = User::create(['name' => $request->name, 'email' => $request->email, 'phone' => $request->contact, 'address' => ' ', 'role' => 'user', 'password' => Hash::make($password), 'password_changed' => 1]);
                Log::info(["new user:" => $user]);
                Mail::to($request->email)->send(new NewUserRegister($user, $password));
                Auth::login($user);
            }

            $total = round($request->bookprice, 2);

            if (Session::has('paid')) {
                $paid = Session::get('paid') + $request->paidAmt;
                Session::put('paid', $paid);
            } else {
                Session::put('paid', $request->paidAmt);
            }
            $ccrcy = Session::get('CurrencyCode');
            $walletuser = \Auth::user();
            $pAmount = Currency::convert($ccrcy, 'USD', $request->paidAmt);
            //$walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(' . $ccrcy . ') -' . $request->paidAmt]);


            $paid = Session::get('paid');
            $leftAmnt = (float) $request->bookprice - (float) $paid;
            $due = round($leftAmnt, 2);


            //$slip = Receipts::create(['total_paid' => $paid, 'currencyCode' => $request->currency, 'booking_amount' => $total, 'txn_id' => $request->payres['razorpay_payment_id'], 'paid_amount' => $request->paidAmt, 'user_id' => Auth::user()->id]);

           // Mail::to($request->email)->send(new MultiCardPayment($user, $slip));

            return response()->json(array(
                        'message' => "Payment Success",
                       // 'payment' => $slip,
                        'user' => $user,
                        'total' => $total,
                        'paid' => Session::get('paid'),
                        'due' => $due,
                        'error' => 0
            ));
        } catch (Exception $e) {
            return response()->json(array(
                        'message' => "Payment Failed",
                        'total' => $request->bookprice,
                        'paid' => $request->paidAmt,
                        'error' => $e->getMessage()
            ));
        }
    }


     public function recordPaymentILS(Request $request) {


        try {

            Log::info($request->all());

            /*
             * First check if customer exists in the DB
             */
            $user = User::where('email', $request->email)->first();
            Log::info(["check user:" => $user]);
            if (isset($user) && $user->id) {

                Auth::login($user);
            } else {
                $password = $this->generateRandomString();
                $user = User::create(['name' => $request->name, 'email' => $request->email, 'phone' => $request->contact, 'address' => ' ', 'role' => 'user', 'password' => Hash::make($password), 'password_changed' => 1]);
                Log::info(["new user:" => $user]);
                Mail::to($request->email)->send(new NewUserRegister($user, $password));
                Auth::login($user);
            }

            $total = round($request->bookprice, 2);

            if (Session::has('paid')) {
                $paid = Session::get('paid') + $request->paidAmt;
                Session::put('paid', $paid);
            } else {
                Session::put('paid', $request->paidAmt);
            }
            $ccrcy = Session::get('CurrencyCode');
            $walletuser = \Auth::user();
            $pAmount = Currency::convert($ccrcy, 'USD', $request->paidAmt);
            $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(' . $ccrcy . ') -' . $request->paidAmt]);


            $paid = Session::get('paid');
            $leftAmnt = (float) $request->bookprice - (float) $paid;
            $due = round($leftAmnt, 2);


            $slip = Receipts::create(['total_paid' => $paid, 'currencyCode' => $request->currency, 'booking_amount' => $total, 'paid_amount' => $request->paidAmt, 'user_id' => Auth::user()->id]);

            Mail::to($request->email)->send(new MultiCardPayment($user, $slip));

            return response()->json(array(
                        'message' => "Payment Success",
                        'payment' => $slip,
                        'user' => $user,
                        'total' => $total,
                        'paid' => Session::get('paid'),
                        'due' => $due,
                        'error' => 0
            ));
        } catch (Exception $e) {
            return response()->json(array(
                        'message' => "Payment Failed",
                        'total' => $request->bookprice,
                        'paid' => $request->paidAmt,
                        'error' => $e->getMessage()
            ));
        }
    }

    public function lookup(Request $request) {

        $hotels = array();
        $this->temp = 'set value ';
        $roomGuests = array();
        Session::put('active_tab', 'hotels');
        $queryValues = $request->query();
        $input_data = $request->all();
        $queryVals = '';
        $input = array();
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

        if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

            $location = json_decode($this->getCookie('th_country'));
        } else {

            $location = \Location::get($this->end_user_ip);
        }

        $country = $location->countryCode;

        $countryInfo = Currencies::select('currency_code', 'name')->where('code', $location->countryCode)->first();

        $ourCurrency = Config::get('ourcurrency');

        if (in_array($countryInfo['currency_code'], $ourCurrency)) {
            $input['currency'] = $countryInfo['currency_code'];
        } else {
            $input['currency'] = 'USD';
        }


        $searchCountry = Cities::where('CityId', $input['city_id'])->first();


        if (isset($input_data['countryCode']) && !empty($input_data['countryCode'])) {
            $input['countryCode'] = $input_data['countryCode'];
            $input['city_name'] = $input_data['city_name'];
        } else {
            if(isset($input['city_name']) && !empty($input['city_name'])) {
                $searchCountry = Cities::where('CityName', 'like', '%' . $input['city_name'] . '%')->first();
                $input['countryCode'] = $searchCountry['CountryCode'];
                $input['city_name'] = $searchCountry['CityName'];
            }
        }

        if(empty($searchCountry) && !empty($input['city_name'])) {
            $searchCountry = Cities::where('CityName', 'like', '%' . $input['city_name'] . '%')->first();
        }

        if(empty($searchCountry)) {
            if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

                $location = json_decode($this->getCookie('th_country'));
                $searchCountry = Cities::where('CountryCode', $location->countryCode)->first();
            } else {
                $searchCountry = Cities::where('CountryCode', 'US')->first();
            }
        }

        $currency = $input['currency'];
        $input['countryName'] = $input_data['countryName']; //(isset($location->countryName) && $location->countryName != '') ? $location->countryName : $countryInfo['name'];
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
                    $queryVals = '&' . $queryVals . 'a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i] . '&';
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

                        $queryVals = $queryVals . '&a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i] . '&';
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
                    $queryVals = $queryVals . '&a' . $i . '=' . $queryValues['a' . $i] . '&c' . $i . '=' . $queryValues['c' . $i];
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

        $input['Country'] = isset($searchCountry['Country']) ? $searchCountry['Country'] : $queryValues['countryName'];

        $isAgent = false;
        if (Auth::user()) {

            $agent = AffiliateUsers::select('user_id')->where('user_id', Auth::user()->id)->first();

            if (isset($agent) && !empty($agent)) {
                $isAgent = true;
            }
        }
        if (isset($input['preffered_hotel']) && $input['preffered_hotel'] != '') {


            Session::put('active_tab', 'hotels');

            Session::put('checkInDate', $input['departdate']);
            Session::put('checkOutDate', $input['returndate']);

            $noOfRooms = $input['roomCount'];
            Session::put('noofrooms', $noOfRooms);

            $startdate = $input['departdate'];
            $returndate = $input['returndate'];

            $currency = $input['currency'];

            if (!empty($input_data['Location'])) {
                $input['city_name'] = (isset($input_data['Location'])) ? $input_data['Location'] : $queryValues['Location'];
                $input['Location'] = (isset($input_data['Location'])) ? $input_data['Location'] : $queryValues['Location'];
            } else {
                $input['city_name'] = (isset($queryValues['Location'])) ? $queryValues['Location'] : $searchCountry['CityName'];
                $input['Location'] = (isset($queryValues['Location'])) ? $queryValues['Location'] : $searchCountry['CityName'];
            }

            $city = StaticDataHotels::where('hotel_code', $input["preffered_hotel"])->select('city_id')->first();
            $this->api = new TBOHotelAPI();


            $postData = [
                    "CheckInDate" => date('d/m/Y', strtotime($input['departdate'])),
                    "NoOfNights" => $noOfNights,
                    "CountryCode" => $input['countryCode'],
                    "CityId" => $city['city_id'],
                    "ResultCount" => null,
                    "PreferredCurrency" => $input['currency'],
                    "GuestNationality" => $country,
                    "NoOfRooms" => $noOfRooms,
                    "RoomGuests" => $roomGuests,
                    "MaxRating" => 5,
                    "MinRating" => 3,
                    "ReviewScore" => null,
                    "IsTBOMapped" => true,
                    "IsNearBySearchAllowed" => false,
                    "EndUserIp" => $this->api->userIP,
                    "TokenId" => $this->api->tokenId,
                    "HotelCode" => $input["preffered_hotel"]
                ];
            
            try {
                $hotels = $this->api->hotelSearch($postData);
                if (isset($hotels['HotelSearchResult']) && isset($hotels['HotelSearchResult']['ResponseStatus']) && $hotels['HotelSearchResult']['ResponseStatus'] == 1) {

                    if (sizeof($hotels['HotelSearchResult']['HotelResults']) > 0) {

                        $this->setCookie('hotel_session', time() + (60 * 28), 360);


                        $results_hotels = array();
                        $hotel = $hotels['HotelSearchResult']['HotelResults'][0];
                        $traceId = $hotels['HotelSearchResult']['TraceId'];
                        Session::put('traceId', $traceId);

                        Session::put('currency', $currency);
                        if (isset($hotel['IsTBOMapped']) && $hotel['IsTBOMapped'] == true) {
                            $static_data = StaticDataHotels::select('hotel_location', 'hotel_images', 'start_rating', 'hotel_facilities', 'hotel_address', 'city_id', 'hotel_name', 'id', 'ishalal')->where(['hotel_code' => $hotel['HotelCode']])->first();


                            if (isset($static_data)) {

                                $hotel['h_rating'] = ($static_data['start_rating'] != null) ? (int) $static_data['start_rating'] : 0;

                                if (isset($static_data['hotel_images']) && !empty($static_data['hotel_images'])) {
                                    $static_data['hotel_images'] = json_decode($static_data['hotel_images']);
                                }

                                if (isset($static_data['hotel_facilities']) && !empty($static_data['hotel_facilities'])) {
                                    $static_data['hotel_facilities'] = json_decode($static_data['hotel_facilities']);
                                }

                                if (isset($static_data['hotel_address']) && !empty($static_data['hotel_address'])) {
                                    $static_data['hotel_address'] = json_decode($static_data['hotel_address']);
                                }

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
                                //unset($hotel['Price']['TDS']);
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
                                //unset($hotel['Price']['TDS']);
                                unset($hotel['Price']['ServiceCharge']);
                                unset($hotel['Price']['TotalGSTAmount']);
                                array_push($results_hotels, array(
                                    'TBO_data' => $hotel,
                                    'static_data' => $static_data
                                ));
                            }
                        }

                        $fileName = time() + (60 * 28);
                        $fileContents = json_encode(array('request' => $input, 'response' => $results_hotels));
                        $this->saveSearchData($fileName . '.json', $fileContents);

                        if (isset($hotel['SupplierHotelCodes']) && sizeof($hotel['SupplierHotelCodes']) > 0) {

                            return redirect('/hotel/' . strtolower(str_replace(' ', '-', $input['Country'])) . '/' . strtolower(str_replace(' ', '-', $input['city_name'])) . '/' . strtolower(str_replace(' ', '-', $static_data['hotel_name'])) . '/' . $traceId . '/' . $hotel['HotelCode'] . '/' . str_replace("/", "-", $input['departdate']) . '/' . $noOfRooms . '/' . $fileName . '/' . $noOfNights . '/' . $input['referral'] . '?' . $queryVals);
                        } else {
                            $this->emptySession('');
                            return view('500')->with(['error' => 'No rooms availble for the selected hotel.']);
                        }
                    } else {
                        $this->emptySession('');
                        return view('500')->with(['error' => 'You results found for your search, please try again.']);
                    }
                } else {
                    $this->emptySession('');
                    return view('500')->with(['error' => $hotels['HotelSearchResult']['Error']['ErrorMessage']]);
                    Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode($postData), json_encode($hotels) ));
                }
            } catch (Exception $e) {
                $this->emptySession('');
                return view('500')->with(['error' => $e->getMessage()]);
            }
        } else {

            if (isset($input['countryCode']) && !empty($input['countryCode'])) {
                $city = Cities::select('CityId')->where(['CityName' => $input['city_name'], 'CountryCode' => $input['countryCode']])
                    ->select('CityId')
                    ->first();
            }
            if (empty($city)) {
                if (isset($input['countryCode']) && !empty($input['countryCode'])) {
                    $city = Cities::select('CityId')->where('CityName', 'like', '%' . $input['city_name'] . '%')
                        ->where('CountryCode', $input['countryCode'])
                        ->select('CityId')
                        ->first();
                }
            }
            if (empty($city)) {
                if(isset($input['CountryCode']) && !empty($input['CountryCode'])) {
                    $city = Cities::select('CityId')->where('CountryCode', $input['countryCode'])
                        ->select('CityId')
                        ->first();
                } else {
                    if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

                        $location = json_decode($this->getCookie('th_country'));
                        $city = Cities::where('CountryCode', $location->countryCode)->first();
                    } else {
                        $city = Cities::where('CountryCode', 'US')->first();
                    }
                }
            }

            if(empty($city)) {
                $city = Cities::select('CityId')
                            ->first();
            }


            $hotels = StaticDataHotels::where(['city_id' => $city['CityId'], 'start_rating' => '5'])->limit(15)->get();

            // $hotels = DB::select("SELECT *, ( 3959 * acos( cos( radians(".$input['Latitude'].") ) * cos( radians( lat ) ) 
            //             * cos( radians( lng ) - radians(".$input['Longitude'].") ) + sin( radians(".$input['Latitude'].") ) * sin(radians(lat)) ) ) AS distance 
            //         FROM static_data_hotels
            //         HAVING distance < 50
            //         ORDER BY distance LIMIT 15");

            // $hotels = array_map(function ($value) {
            //     return (array)$value;
            // }, $hotels);

            if(empty($hotels)){
                $hotels = StaticDataHotels::where(['city_id' => $city['CityId'], 'start_rating' => '5'])->limit(15)->get();
            }

            $input['city_id'] = (isset($city['CityId'])) ? $city['CityId'] : $input['city_id'];
            $input['departdate'] = date('d-m-Y', strtotime($input['departdate']));
            $input['returndate'] = date('d-m-Y', strtotime($input['returndate']));

            $title = ' Top Hotel In ' . $input['city_name'] . ' 2021';

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

            return view('search.hotels.results')->with(['hotels' => $hotels, 'input' => $input, 'referral' => $_GET['referral'], 'title' => $title, 'isAgent' => $isAgent, 'input_data' => $input, 'isILS' => $isILS]);
        }
    }

    public function searchHotels(Request $request) {

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
            Session::put('active_tab', 'hotels');
        }


        $startdate = $input['departdate'];
        $returndate = $input['returndate'];

        $date1 = date('Y-m-d');
        $now1 = date('Y-m-d', strtotime($startdate));
        
        if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

            $location = json_decode($this->getCookie('th_country'));
        } else {
            $debugTimeline[] = ["Before Location api called:" => date('H:i:s')];
            $location = \Location::get($this->end_user_ip);
            $debugTimeline[] = ["After Location api called:" => date('H:i:s')];
        }
        if ($location) {
            $country = $location->countryCode;
        } else {
            $country = 'US';
        }



        $debugTimeline[] = ["Before Currencies api called:" => date('H:i:s')];
        $countryInfo = Currencies::select('currency_code', 'name')->where('code', $location->countryCode)->first();
        $debugTimeline[] = ["After Currencies api called:" => date('H:i:s')];

        $ourCurrency = Config::get('ourcurrency');

        if (in_array($countryInfo['currency_code'], $ourCurrency)) {
            $currency = $countryInfo['currency_code'];
        } else {
            $currency = 'USD';
        }


        $input['user_country'] = (isset($location->countryName) && $location->countryName != '') ? $location->countryName : $countryInfo['name'];

        $selectedGuests = $input['roomsGuests'];


        if (isset($input['countryCode']) && !empty($input['countryCode'])) {
            $input['countryCode'] = $input['countryCode'];
            $input['city_name'] = $input['city_name'];
        } else {
            $searchCountry = Cities::select('CountryCode', 'CityName')->where('CityName', 'like', '%' . $input['city_name'] . '%')->first();
            $input['countryCode'] = (isset($searchCountry['CountryCode'])) ? $searchCountry['CountryCode'] : 'CN';
            $input['city_name'] = (isset($searchCountry['CityName'])) ? $searchCountry['CityName'] : 'country';
        }

        Session::put('CountryCode', strtolower($input['countryCode']));

        if ($date1 >= $now1) {
            $new_date = date('d-m-Y', strtotime('+5 days'));
            $startdate = $new_date;
            $returndate = date('d-m-Y', strtotime($startdate. ' + 2 days'));//date($startdate, strtotime('+2 days'));

        }

        $date = Carbon::createFromDate($startdate);
        $now = Carbon::createFromDate($returndate);

        $noOfNights = $date->diffInDays($now);

        $roomguests = array();

        $input['NoOfNights'] = $noOfNights;

        $input['currency'] = $currency;

        if ($date1 >= $now1) {
            $input['CheckInDate'] = date('d/m/Y', strtotime('+5 days'));
        } else {
            $input['CheckInDate'] = str_replace("-", "/", $input['departdate']);
        }

        $noOfRooms = $input['roomCount'];

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

        if(Session::get('locale') == 'heb') {
            $input['roomsGuests'] = 'חדרים' .' ' . $input['roomCount'] . 'אורחים' .' '.$total_guests;
        } else {

            $input['roomsGuests'] = $input['roomCount'] . ' Rooms ' . $total_guests . ' Guests';
        }

        $this->api = new TBOHotelAPI();

        $date = date('d/m/Y');
        $now = date('d/m/Y', strtotime($input['CheckInDate']));


        $splitDate = explode('/', $input['CheckInDate']);

        if(isset($splitDate[0]) ) {
            if(strlen($splitDate[0]) == 4) {
                $input['CheckInDate'] = date('d/m/Y', strtotime($input['CheckInDate']));
            }
        }

        $debugTimeline[] = ["Before TBO api called:" => date('H:i:s')];

        // $hotels = DB::connection('pgsql')->select("SELECT *, ( 3959 * acos( cos( radians(".$input['Latitude'].") ) * cos( radians( lat ) ) 
        //                 * cos( radians( lng ) - radians(".$input['Longitude'].") ) + sin( radians(".$input['Latitude'].") ) * sin(radians(lat)) ) ) AS distance 
        //             FROM static_data_hotels
        //             HAVING distance < 50
        //             ORDER BY distance LIMIT 15");
        // echo "<pre>"; print_r($hotels); die();
        if (isset($input['Latitude']) && !empty($input['Latitude'])) {

                if($input['countryCode'] == 'IL') {

                    $input['Radius'] = '50';
                }
            
                $postData = [
                    "CheckInDate" => $input['CheckInDate'],
                    "Latitude" => $input['Latitude'],
                    "Longitude" => $input['Longitude'],
                    "Radius" => $input['Radius'],
                    "NoOfNights" => $input['NoOfNights'],
                    "CountryCode" => $input['countryCode'],
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
            $input['Latitude'] = '';
        }

        $hotels_list = $this->api->hotelSearch($postData);

        $debugTimeline[] = ["After TBO api called:" => date('H:i:s')];


        $input['countryName'] = str_replace('+', ' ', $input['countryName']);
        $input['roomsGuests'] = str_replace('+', ' ', $input['roomsGuests']);
        $traceId = '';

        $commisioninis_currency = env('INR_FEES');

        if (isset($hotels_list['HotelSearchResult']) && isset($hotels_list['HotelSearchResult']['ResponseStatus']) && $hotels_list['HotelSearchResult']['ResponseStatus'] == 1) {

            $this->setCookie('hotel_session', time() + (60 * 28), 360);
            $this->setCookie('hotel_city', $input['city_id'], 20);

            $hotels = $hotels_list['HotelSearchResult']['HotelResults'];
            $traceId = $hotels_list['HotelSearchResult']['TraceId'];
            Session::put('traceId', $traceId);
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
            $hotel_in_api = false;
            $h_lat = $input['Latitude'];

            foreach ($hotels as $h_key => $hotel) {
                array_push($hotel_in_array, $hotel['HotelCode']);
            }
            $debugTimeline[] = ["Before PGSQL db called:" => date('H:i:s')];
            $db_hotels = StaticDataHotels::select('hotel_location', 'hotel_code', 'hotel_images', 'start_rating', 'hotel_facilities', 'hotel_address', 'city_id', 'hotel_name', 'id', 'ishalal', 'tp_ratings', 'room_amenities', 'lat', 'lng')->whereIn('hotel_code', $hotel_in_array)->get();
            $debugTimeline[] = ["After PGSQL db called:" => date('H:i:s')];

            $debugTimeline[] = ["After static data collected:" => date('H:i:s')];

            $highstPrice = 0;
            foreach ($db_hotels as $key => $d_hotel) {

                foreach ($hotels as $h_key => $hotel) {

                    if ($d_hotel['hotel_code'] == $hotel['HotelCode']) {
                        if (isset($hotel['IsTBOMapped']) && $hotel['IsTBOMapped'] == true) {

                            if (isset($input['referral']) && $input['referral'] != '') {

                                $checkrefferal = AffiliateUsers::where(['referal_code' => $request
                                            ->referral])
                                        ->first();
                                if (isset($checkrefferal)) {

                                    $commisioninis = env('INIS_VAL');
                                    $paymeComm = env('INIS_VAL_PAYME');
                                } else {
                                    $commisioninis = env('INIS_VAL');
                                    $paymeComm = env('INIS_VAL_PAYME');
                                }

                                $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $hotel['HotelCode'] . "'");

                                if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                                    $commisioninis = 10;
                                    $paymeComm = env('INIS_VAL_PAYME_AGENT');
                                }
                            } else {

                                $commisioninis = env('INIS_VAL');
                                $paymeComm = env('INIS_VAL_PAYME');
                            }

                            $tdsVal = ((env('INIS_TDS') / 100) * ( $hotel['Price']['OfferedPriceRoundedOff'] ));

                            $inis_markup = (($commisioninis / 100) * ( $hotel['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                            $price_with_markup = $inis_markup + $hotel['Price']['OfferedPriceRoundedOff'] + $tdsVal;
                            //$taxes = ($commisioninis_currency / 100) * $price_with_markup;

                            if($currency == 'ILS'){

                                $inis_markup = (($paymeComm / 100) * ( $hotel['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                                $price_with_markup = $inis_markup + $hotel['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                                $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;
                                //$taxes = $taxes + env('PAYME_FIX_FEES');

                            }else{

                                $taxes = ($commisioninis_currency / 100) * $price_with_markup;
                            }

                            $hotel['FinalPrice'] = $inis_markup + $taxes + $hotel['Price']['OfferedPriceRoundedOff'] + $tdsVal;
                            $hotel['discount'] = rand(0,25);


                            if($currency == 'ILS'){

                              $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                              $hotel['FinalPrice'] = $hotel['FinalPrice'] + env('PAYME_FIX_FEES') + $vat;

                            }

                            if($hotel['FinalPrice'] > $highstPrice) {
                                $highstPrice = $hotel['FinalPrice'];
                            }

                            if (isset($input['Latitude']) && !empty($input['Latitude'])) {
                                $hfilter['hotel_code'] = $hotel['HotelCode'];
                            } else {
                                $hfilter['hotel_code'] = $hotel['HotelCode'];
                            }
                            $static_data = $d_hotel;

                            if($d_hotel['hotel_location'] == '' || empty($d_hotel['hotel_location'])) {
                                $loc = array();
                                $loc['@Latitude'] = $d_hotel['lat'];
                                $loc['@Longitude'] = $d_hotel['lng'];
                            }
                            if (isset($d_hotel)) {

                                if (isset($d_hotel['hotel_location'])) {

                                    $loc = json_decode($d_hotel['hotel_location'], true);

                                    if($loc == '' || empty($loc)) {
                                        $loc = array();
                                        $loc['@Latitude'] = $d_hotel['lat'];
                                        $loc['@Longitude'] = $d_hotel['lng'];
                                    }

                                    if (isset($input['Longitude']) && !empty($input['Longitude']) && isset($input['Latitude']) && !empty($input['Latitude']) && isset($loc['@Latitude']) && !empty($loc['@Latitude']) && isset($loc['@Longitude']) && !empty($loc['@Longitude'])) {

                                        $d_hotel['distance'] = $this->getDistance($input['Latitude'], $input['Longitude'], $loc['@Latitude'], $loc['@Longitude'], 'K');

                                        // if (isset($input['Latitude']) && !empty($input['Latitude'])) {
                                        //     // extract location
                                        //     $e_lat = explode('.', $input['Latitude']);
                                        //     $e_lan = explode('.', $input['Longitude']);

                                        //     $e_lat = $e_lat[0] . '.' . substr($e_lat[1], 0, 3); 

                                        //     $e_lan = $e_lan[0] . '.' . substr($e_lan[1], 0, 3); 

                                        //     $h_lat = explode('.', $loc['@Latitude']);
                                        //     $h_lan = explode('.', $loc['@Longitude']);

                                        //     $h_lat = $h_lat[0] . '.' . substr($h_lat[1], 0, 3); 

                                        //     $h_lan = $h_lan[0] . '.' . substr($h_lan[1], 0, 3); 
                                            

                                        //     if($e_lat == $h_lat && $e_lan == $h_lan) {
                                        //         $hotel_in_api = true;
                                        //     }
                                        // }
                                    }
                                }

                                $hotel['h_rating'] = ($d_hotel['start_rating'] != null) ? (int) $d_hotel['start_rating'] : 0;

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

                                if (isset($d_hotel['hotel_facilities']) && !empty($d_hotel['hotel_facilities'])) {
                                    $d_hotel['hotel_facilities'] = json_decode($d_hotel['hotel_facilities']);

                                    if (isset($d_hotel['hotel_facilities']) && !empty($d_hotel['hotel_facilities'])) {
                                        $tmp_arr = array();
                                        foreach ($d_hotel['hotel_facilities'] as $key => $fac) {
                                            if ($key <= 4) {
                                                array_push($tmp_arr, $fac);
                                                if (!in_array($fac, $ameneties_array)) {
                                                    array_push($ameneties_array, $fac);
                                                }
                                            }
                                        }

                                        $d_hotel['hotel_facilities'] = $tmp_arr;
                                    }
                                }

                                if (isset($d_hotel['room_amenities']) && !empty($d_hotel['room_amenities'])) {
                                    $d_hotel['room_amenities'] = json_decode($d_hotel['room_amenities']);

                                    if (isset($d_hotel['room_amenities']) && !empty($d_hotel['room_amenities'])) {
                                        $tmp_arr = array();
                                        foreach ($d_hotel['room_amenities'] as $key => $r_amn) {
                                            if ($key <= 4) {
                                                if (!in_array($r_amn, $room_ameneties_array)) {
                                                    array_push($room_ameneties_array, $r_amn);
                                                }
                                            }
                                        }

                                    }
                                }


                                if (isset($d_hotel['hotel_location']) && !empty($d_hotel['hotel_location'])) {
                                    $d_hotel['hotel_location'] = json_decode($d_hotel['hotel_location']);
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
                                //unset($hotel['Price']['TDS']);
                                unset($hotel['Price']['ServiceCharge']);
                                unset($hotel['Price']['TotalGSTAmount']);

                                $add_hotel = true;
                                if (sizeof($results_hotels) == 0) {
                                    $add_hotel = true;
                                } else {

                                    foreach ($results_hotels as $key => $r_hotel) {

                                        if ($r_hotel['TBO_data']['HotelCode'] == $hotel['HotelCode']) {
                                            $add_hotel = false;
                                            continue;
                                        }
                                    }
                                }

                                if ($add_hotel) {
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

                                    if ($static_data['tp_ratings'] == '0.0') {
                                        $unrated_t++;
                                    }
                                    if ($static_data['tp_ratings'] == '1.0') {
                                        $tp_one++;
                                    }
                                    if ($static_data['tp_ratings'] == '1.5') {
                                        $tp_one_h++;
                                    }
                                    if ($static_data['tp_ratings'] == '2.0') {
                                        $tp_two++;
                                    }
                                    if ($static_data['tp_ratings'] == '2.5') {
                                        $tp_two_h++;
                                    }
                                    if ($static_data['tp_ratings'] == '3.0') {
                                        $tp_three++;
                                    }
                                    if ($static_data['tp_ratings'] == '3.5') {
                                        $tp_three_h++;
                                    }
                                    if ($static_data['tp_ratings'] == '4.0') {
                                        $tp_four++;
                                    }
                                    if ($static_data['tp_ratings'] == '4.5') {
                                        $tp_four_h++;
                                    }
                                    if ($static_data['tp_ratings'] == '5.0') {
                                        $tp_five++;
                                    }

                                    if (isset($static_data['hotel_address']) && !empty($static_data['hotel_address'])) {
                                        $static_data['hotel_address'] = json_decode($static_data['hotel_address'], true);


                                        if (isset($static_data['hotel_address']['CityName']) && $static_data['hotel_address']['CityName'] != '') {

                                            if (sizeof($locations) > 0) {
                                                $check_loc = false;
                                                foreach ($locations as $key => $loc) {
                                                    if (strtolower(str_replace("-", " ", $loc['name'])) == strtolower(str_replace("-", " ", $static_data['hotel_address']['CityName']))) {
                                                        $locations[$key]['hotels'] = $locations[$key]['hotels'] + 1;
                                                        $check_loc = true;
                                                    }
                                                }

                                                if (!$check_loc) {
                                                    array_push($locations, array('name' => $static_data['hotel_address']['CityName'], 'hotels' => 1));
                                                }
                                            } else {
                                                array_push($locations, array('name' => $static_data['hotel_address']['CityName'], 'hotels' => 1));
                                            }
                                        }
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

            foreach ($results_hotels as $h_key => $hotel) {
                array_push($array_send, $hotel);

                if ($counter >= 2000) {
                    break;
                }

                $counter++;
            }

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


            $debugTimeline[] = ["After code processing done:" => date('H:i:s')];

            $fileName = time() + (60 * 28);
            $fileContents = json_encode(array('request' => $input, 'response' => $results_hotels));
            $this->saveSearchData($fileName . '.json', $fileContents);

            $sold_out_hotel = array();
           //  //if(!$hotel_in_api) {
           //      echo $h_lat . "<br>"; 
           //      $sold_out_hotel = StaticDataHotels::where('lat', 'ilike', '%' . $h_lat . '%')->where('lng', 'ilike', '%' . $h_lng . '%')->first();
           //      echo "<pre>";
           //      print_r($sold_out_hotel);
           //      die();
           // // }
           //  echo $hotel_in_api;

            return response()->json(array(
                        'highstPrice' => round($highstPrice),
                        'hotels' => $array_send,
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
                        'timeline' => $debugTimeline,
                        'search_id' => $fileName,
                        'lottery_limit' => Session::get('lotteryLimit')
            ));
            
        } else {


            $debugTimeline[] = ["After code processing done:" => date('H:i:s')];

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
            Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode($postData), json_encode($hotels_list) ));
            return response()->json(array(
                        'hotels' => $hotels_list,
                        'input_data' => $input,
                        'status' => false,
                        'hotel_count' => 0,
                        'traceId' => $traceId,
                        'timeline' => $debugTimeline
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
        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
        // echo "<pre>"; print_r($search_contents); die();
        $hotels = $search_contents['response'];//unserialize(Session::get('hotels_list_' . $s_city));
        $hotels_counter = $request->size; //Session::get('hotels_counter');
        $size = $hotels_counter + 1000;
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

        foreach ($hotels as $h_key => $hotel) {

            if ($request->has('htypes')) {
                $htltypes = (array) $request->htypes;

                if (in_array("halal", $htltypes) && $hotel['static_data']['ishalal'] != 'yes') {
                    continue;
                }
            }

            if ($counter_temp <= $hotels_counter) {
                $counter_temp++;
                continue;
            }


            if (intval($price_filter) < 500000) {
                if ($hotel['TBO_data']['Price']['OfferedPriceRoundedOff'] < $price_filter) {

                    if (isset($array_ratings)) {

                        if (in_array($hotel['TBO_data']['h_rating'], $array_ratings)) {

                            $counter_temp++;
                            if ($hotels_counter == 0 || $hotels_counter > 10) {
                                array_push($results_hotels, $hotel);
                            }
                        }
                    } else if (isset($array_tp_ratings)) {
                        if (in_array($hotel['static_data']['tp_ratings'], $array_tp_ratings)) {

                            $counter_temp++;
                            if ($hotels_counter == 0 || $hotels_counter > 10) {
                                array_push($results_hotels, $hotel);
                            }
                        }
                    } else if (isset($array_h_amns)) {

                        if (isset($hotel['static_data']['hotel_facilities']) && !empty($hotel['static_data']['hotel_facilities'])) {

                            $hotel_matched = false;
                            foreach ($hotel['static_data']['hotel_facilities'] as $h_fac) {
                                if (in_array($h_fac, $array_h_amns)) {
                                    $hotel_matched = true;
                                    continue;
                                }
                            }

                            if ($hotel_matched) {
                                $counter_temp++;
                                if ($hotels_counter == 0 || $hotels_counter > 10) {
                                    array_push($results_hotels, $hotel);
                                }
                            }
                        }
                    } else if (isset($array_h_loc)) {

                        if (isset($hotel['static_data']['hotel_address']) && !empty($hotel['static_data']['hotel_address'])) {

                            if (in_array(strtolower(str_replace("-", " ", $hotel['static_data']['hotel_address']['CityName'])), $array_h_loc)) {

                                $counter_temp++;
                                if ($hotels_counter == 0 || $hotels_counter > 10) {
                                    array_push($results_hotels, $hotel);
                                }
                            }
                        }
                    } else if (floatval($hotel['static_data']['distance']) < $distance_filter) {
                        $counter_temp++;
                        array_push($results_hotels, $hotel);
                    }
                }
            } else {

                if (isset($array_ratings) && sizeof($array_ratings) > 0) {
                    if (in_array($hotel['TBO_data']['h_rating'], $array_ratings)) {

                        $counter_temp++;
                        if ($hotels_counter == 0 || $hotels_counter > 10) {
                            array_push($results_hotels, $hotel);
                        }
                    }
                } else if (isset($array_tp_ratings) && sizeof($array_tp_ratings) > 0) {
                    if (in_array($hotel['static_data']['tp_ratings'], $array_tp_ratings)) {

                        $counter_temp++;
                        if ($hotels_counter == 0 || $hotels_counter > 10) {
                            array_push($results_hotels, $hotel);
                        }
                    }
                } else if (isset($array_h_amns) && sizeof($array_h_amns) > 0) {

                    if (isset($hotel['static_data']['hotel_facilities']) && !empty($hotel['static_data']['hotel_facilities'])) {

                        $hotel_matched = false;
                        foreach ($hotel['static_data']['hotel_facilities'] as $h_fac) {
                            if (in_array($h_fac, $array_h_amns)) {
                                $hotel_matched = true;
                                continue;
                            }
                        }

                        if ($hotel_matched) {
                            $counter_temp++;
                            if ($hotels_counter == 0 || $hotels_counter > 10) {
                                array_push($results_hotels, $hotel);
                            }
                        }
                    }
                } else if (isset($array_h_loc) && sizeof($array_h_loc) > 0) {

                    if (isset($hotel['static_data']['hotel_address']) && isset($hotel['static_data']['hotel_address']['CityName'])) {
                        if (in_array(strtolower(str_replace("-", " ", $hotel['static_data']['hotel_address']['CityName'])), $array_h_loc)) {

                            $counter_temp++;
                            if ($hotels_counter == 0 || $hotels_counter > 10) {
                                array_push($results_hotels, $hotel);
                            }
                        }
                    }
                } else if (floatval($hotel['static_data']['distance']) < $distance_filter) {

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

    public function viewHotel2(Request $request) {

        $hotel_code = $request->code;
        $checkin_date = $request->checkIn;
        $rooms_count = $request->rooms;
        $city_id = $request->city_id;
        $total_nights = $request->nights;
        $referral = $request->referral;
        $traceId = $request->traceId;

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

            $location = $_COOKIE['th_country'];
            if (!empty(trim($myCurrency))) {
                if (!empty(trim($myCurrency)) && (empty(Session::get('lotteryLimit')) || Session::get('lotteryLimit') !='' || !Session::get('lotteryLimit'))) {
                   $lAmount = Currency::convert('USD', $myCurrency, $lottery_Limit);
                   if (isset($lAmount) && isset($lAmount['convertedAmount'])) {

                       $LotteryLimit = round($lAmount['convertedAmount']);
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

            $lottery_Limit = env('LOTTERY_ELIGIBILITY');

            if (!empty(trim($myCurrency))) {
                if (!empty(trim($myCurrency)) && (empty(Session::get('lotteryLimit')) || Session::get('lotteryLimit') !='' || !Session::get('lotteryLimit'))) {
                    $lAmount = Currency::convert('USD', $myCurrency, $lottery_Limit);
                    if (isset($lAmount) && isset($lAmount['convertedAmount'])) {

                        $LotteryLimit = round($lAmount['convertedAmount']);
                        Session::put('lotteryLimit', $LotteryLimit);
                    } else {
                        Session::put('lotteryLimit', $lottery_Limit);
                    }
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

            $hotels = json_decode($this->readSearchData($city_id.'.json'), true);
            
            $selected_hotel = array();
            $supplierIds = '';


            foreach ($hotels['response'] as $key => $hotel) {

                if ($hotel['TBO_data']['HotelCode'] == $hotel_code) {
                    $selected_hotel = $hotel;


                    if (empty($hotel['static_data']['hotel_name'])) {

                        $hotel['static_data'] = StaticDataHotels::where(['hotel_code' => $selected_hotel['TBO_data']['HotelCode']])->first();
                    }

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
                $this->emptySession('');
                return view('500')->with(['error' => 'No rooms availblefor selected hotel, please try with different booking dates.']);
            }


            $static_data = StaticDataHotels::select('hotel_code','hotel_location', 'attractions', 'hotel_images', 'start_rating', 'hotel_facilities', 'hotel_description', 'hotel_name', 'id', 'ishalal', 'hotel_info', 'hotel_time', 'hotel_type', 'lat', 'lng')->where(['hotel_code' => $selected_hotel['TBO_data']['HotelCode']])->first();


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
                
                if (isset($static_data['hotel_location']) && !empty($static_data['hotel_location'])) {
                    $static_data['hotel_location'] = json_decode($static_data['hotel_location'], true);
                    if(isset($static_data['hotel_location']) && isset($static_data['hotel_location']['@Latitude'])) {
                        $static_data['lat'] = $static_data['hotel_location']['@Latitude'];
                        $static_data['lng'] = $static_data['hotel_location']['@Longitude'];
                    }
                }

                if (isset($static_data['hotel_description']) && !empty($static_data['hotel_description'])) {
                    $static_data['hotel_description'] = json_decode($static_data['hotel_description'], true);
                }


                if (isset($static_data['hotel_type']) && !empty($static_data['hotel_type'])) {
                    $static_data['hotel_type'] = json_decode($static_data['hotel_type'], true);
                }

                if (isset($static_data['hotel_info']) && !empty($static_data['hotel_info'])) {
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
            $title = $selected_hotel['static_data']['hotel_name'] . ' - ' .$request_input['city_name'] . ' - ' .$request_input['countryName'];
            $meta_image = (isset($selected_hotel['static_data']['hotel_images']) && !empty($selected_hotel['static_data']['hotel_images'])) ? $selected_hotel['static_data']['hotel_images'][0] : 'https://tripheist.com/images/logo.png';

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

            // echo "<pre>"; print_r($request_input); die();
            return view('search.hotels.view-hotel2')
                            ->with(['hotel' => $selected_hotel, 'total_nights' => $total_nights, 'roomImages' => $roomImages, 'referral' => $referral, 'supplierIds' => $supplierIds, 'hotel_code' => $hotel_code, 'image_count' => 0, 'traceId' => $traceId, 'static_data' => $selected_hotel['static_data'], 'queryVals' => $queryVals, 'title' => $title, 'meta_image' => $meta_image, 's_city' => $city_id, 's_name' => $s_name, 'search_id' => $city_id, 'input_data' => $request_input, 'isILS' => $isILS]);
        } else {

            $city_data = StaticDataHotels::where('hotel_code', $hotel_code)->select('city_id')->first();

            $city_id = $city_data['city_id'];
            
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

            if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

                $location = json_decode($this->getCookie('th_country'));
            } else {

                $location = \Location::get($this->end_user_ip);
            }

            $country = $location->countryCode;

            $countryInfo = Currencies::select('currency_code', 'name')->where('code', $location->countryCode)->first();
            $ourCurrency = Config::get('ourcurrency');

            if (in_array($countryInfo['currency_code'], $ourCurrency)) {
                $input['currency'] = $countryInfo['currency_code'];
            } else {
                $input['currency'] = 'USD';
            }


            $searchCountry = Cities::select('CountryCode', 'CityName', 'Country')->where('CityId', $city_id)->first();
            $input['countryCode'] = $searchCountry['CountryCode'];

            Session::put('CountryCode', $input['countryCode']);

            $input['city_name'] = $searchCountry['CityName'];
            $input['Location'] = $searchCountry['CityName'];
            $input['Country'] = $searchCountry['Country'];
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

                        $total_guests = $total_guests + intval($queryValues['a' . $i]) + intval($queryValues['c' . $i]);
                    } else {
                        array_push($roomGuests, array(
                            'NoOfAdults' => $queryValues['a' . $i],
                            'NoOfChild' => $queryValues['c' . $i],
                            'ChildAge' => $childAges
                        ));
                        $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                        $input['childCountRoom' . $i] = $queryValues['c' . $i];

                        $total_guests = $total_guests + intval($queryValues['a' . $i]) + intval($queryValues['c' . $i]);
                    }
                } else {
                    array_push($roomGuests, array(
                        'NoOfAdults' => (isset($queryValues['c' . $i])) ? $queryValues['a' . $i] : 0,
                        'NoOfChild' => 0,
                        'ChildAge' => null
                    ));
                    $input['adultCountRoom' . $i] = $queryValues['a' . $i];
                    $input['childCountRoom' . $i] = $queryValues['c' . $i];

                    $total_guests = $total_guests + intval($queryValues['a' . $i]) + intval($queryValues['c' . $i]);
                }
            }

            if(Session::get('locale') == 'heb') {
                $input['roomsGuests'] = 'חדרים' .' ' . $input['roomCount'] . 'אורחים' .' '.$total_guests;
            } else {

                $input['roomsGuests'] = $input['roomCount'] . ' Rooms ' . $total_guests . ' Guests';
            }
            $input['Latitude'] = '';
            $input['Longitude'] = '';
            // Session::put('hotelSearchInput', $input);

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

                        $this->setCookie('hotel_session', time() + (60 * 28), 360);

                        $results_hotels = array();
                        $hotel = $hotels['HotelSearchResult']['HotelResults'][0];
                        $traceId = $hotels['HotelSearchResult']['TraceId'];
                        Session::put('traceId', $traceId);

                        if (isset($hotel['IsTBOMapped']) && $hotel['IsTBOMapped'] == true) {

                            $static_data = StaticDataHotels::select('hotel_location', 'hotel_code', 'hotel_images', 'start_rating', 'hotel_facilities', 'hotel_address', 'hotel_name', 'id', 'ishalal', 'tp_ratings', 'room_amenities')->where(['hotel_code' => $hotel['HotelCode']])->first();



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

                        $fileName = time() + (60 * 28);
                        $fileContents = json_encode(array('request' => $input, 'response' => $results_hotels));
                        $this->saveSearchData($fileName . '.json', $fileContents);

                        return redirect('/hotel/' . strtolower(str_replace(' ', '-', $input['Country'])) . '/' . strtolower(str_replace(' ', '-', $input['city_name'])) . '/' . strtolower(str_replace(' ', '-', $static_data['hotel_name'])) . '/' . $traceId . '/' . $hotel_code . '/' . $checkin_date . '/' . $rooms_count . '/' . $fileName . '/' . $total_nights . '/' . $referral . '?' . $queryVals);
                    } else {

                        $this->writeLogs($postData, $hotels['HotelSearchResult']);
                        Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode($postData), json_encode($hotels) ));
                        $this->emptySession('');
                        return view('500')->with(['error' => 'No results found for your search.']);
                    }
                } else {

                    $this->emptySession('');
                    return view('500')->with(['error' => $hotels['HotelSearchResult']['Error']['ErrorMessage']]);
                }
            } catch (Exception $e) {

                $this->emptySession('');
                return view('500')->with(['error' => $e->getMessage()]);
            }
        }
    }

    public function hotelRooms(Request $request) {

        $supplierCategories = array();

        $hotel_code = $request->hotelCode;
        $search_id = $request->city_id;

        $destinationPath=public_path()."/logs/searches/hotels/" . $search_id . '.json';
        if (file_exists($destinationPath)){
            
            $hotels = json_decode($this->readSearchData($search_id.'.json'), true);

        } else {

            return response()->json(array(
                        'rooms' => 'Your session has expired, please refresh the page to get rooms list.',
                        'status' => false
            ));
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

        Session::put('CurrencyCode', $myCurrency);

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

        $rooms_array = array();
        $roomArr = array();
        $this->api = new TBOHotelAPI();
        $rooms = $this
                ->api
                ->hotelRooms($request->hotelCode, $selected_hotel['TBO_data']['ResultIndex'], $request->traceId, $supplierCategories);

        $combination_type = "";

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


        $imagesAll = RoomImages::select('r_type', 'images', 'bed_type', 'ameneties')->where('sub_domain', $request->hotelCode)->get();

        $roomStaticData = array();
        foreach ($imagesAll as $key => $value) {
            $imagesAll[$key]['images'] = unserialize($value['images']);
            if($value['r_type']) {
                $roomStaticData[$value['r_type']] = array('ameneties' => $value['ameneties'], 'bed_type' => $value['bed_type']);
            }
        }

        // echo "<pre>"; print_r($roomStaticData); die();
        $room_images_size = sizeof($imagesAll);
        $image_counter = 0;

        if ($request->referral != '' && $request->referral != '0') {


            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request
                        ->referral])
                    ->first();
            if (isset($checkrefferal)) {

                $commisioninis = env('INIS_VAL');
                $paymeComm = env('INIS_VAL_PAYME');
            } else {
                $commisioninis = env('INIS_VAL');
                $paymeComm = env('INIS_VAL_PAYME');
            }
        } else {

            $commisioninis = env('INIS_VAL');
            $paymeComm = env('INIS_VAL_PAYME');
        }

        if ($request->referral && $request->referral != '' && $request->referral != '0') {
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $request->hotelCode . "'");
        }

        if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
            $commisioninis = 10;
            $paymeComm = env('INIS_VAL_PAYME_AGENT');
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

                    $type = preg_replace('/\s*/', '', $room['RoomTypeName']);
                    if(isset($roomStaticData[$type])) {
                        $room['staticData'] = $roomStaticData[$type];
                        if(isset($room['staticData']['ameneties'])) {
                            $room['staticData']['ameneties'] = json_decode($room['staticData']['ameneties'], true);
                            foreach ($room['staticData']['ameneties'] as $key => $am) {
                                array_push($room['Amenity'], $am);
                            }
                            $room['Amenity'] = array_unique($room['Amenity']);
                        }
                        if(isset($room['staticData']['bed_type'])) {
                            $room['staticData']['bed_type'] = json_decode($room['staticData']['bed_type'], true);
                        }
                    } else {
                        $room['staticData'] = array();
                    }
                    // $room_details = RoomImages::select('bed_type')->where('name' , $room['RoomTypeName'])->first();
                    // echo "<pre>"; print_r($room_details);

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
                    $inis_markup = (($commisioninis / 100) * ( $room['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                    $price_with_markup = $inis_markup + $room['Price']['OfferedPriceRoundedOff'] + $tdsVal;
                    //$taxes = ($commisioninis_currency / 100) * $price_with_markup;
                    //$room['FinalPrice'] = $inis_markup + $taxes + $room['Price']['OfferedPriceRoundedOff'];


                    if(Session::get('CurrencyCode') == 'ILS'){

                        $inis_markup = (($paymeComm / 100) *  ( $room['Price']['OfferedPriceRoundedOff'] + $tdsVal ));

                        $price_with_markup = $inis_markup + $room['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                        $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;
                        //$taxes = $taxes + env('PAYME_FIX_FEES');

                    }else{

                        $taxes = ($commisioninis_currency / 100) * $price_with_markup;
                    }

                    $room['FinalPrice'] = $inis_markup + $taxes + $room['Price']['OfferedPriceRoundedOff'] + $tdsVal;


                    if(Session::get('CurrencyCode') == 'ILS'){

                      $vat = ( env('INIS_VAL_VAT') / 100 )  * ( $taxes + env('PAYME_FIX_FEES') );

                      $room['FinalPrice'] = $room['FinalPrice'] + env('PAYME_FIX_FEES') + $vat;
                      
                    }


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

                            if (isset($room['Inclusion']) && isset($room['Inclusion'][0])) {

                                $currentType = $room['Inclusion'][0];
                                $add_room = true;
                                foreach ($rooms_array[$id]['sub_rooms'] as $key => $s_room) {

                                    if (isset($s_room['Inclusion']) && isset($s_room['Inclusion'][0]) && strtolower(str_replace(",", "", $room['Inclusion'][0])) == strtolower(str_replace(",", "", $s_room['Inclusion'][0]))) {

                                        $add_room = false;
                                    }
                                }

                                if ($add_room) {
                                    array_push($rooms_array[$id]['sub_rooms'], $room);
                                }
                            } else {
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

                                    $type = preg_replace('/\s*/', '', $roomC['RoomTypeName']);
                                    if(isset($roomStaticData[$type])) {
                                        $roomC['staticData'] = $roomStaticData[$type];
                                        if(isset($roomC['staticData']['ameneties'])) {
                                            $roomC['staticData']['ameneties'] = json_decode($roomC['staticData']['ameneties'], true);
                                            foreach ($roomC['staticData']['ameneties'] as $key => $am) {
                                                array_push($roomC['Amenity'], $am);
                                            }
                                            $roomC['Amenity'] = array_unique($roomC['Amenity']);
                                        }
                                        if(isset($roomC['staticData']['bed_type'])) {
                                            $roomC['staticData']['bed_type'] = json_decode($roomC['staticData']['bed_type'], true);
                                        }
                                    } else {
                                        $roomC['staticData'] = array();
                                    }

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
                                    unset($roomC['DayRates']);
                                    unset($roomC['IsPerStay']);
                                    unset($roomC['SupplierPrice']);
                                    unset($roomC['RoomPromotion']);
                                    unset($roomC['HotelSupplements']);

                                    $tdsVal = ((env('INIS_TDS') / 100) * ( $roomC['Price']['OfferedPriceRoundedOff'] ));
                                    $inis_markup = (($commisioninis / 100) * ( $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                                    $price_with_markup = $inis_markup + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal;
                                    //$taxes = ($commisioninis_currency / 100) * $price_with_markup;
                                    //$roomC['FinalPrice'] = $inis_markup + $taxes + $roomC['Price']['OfferedPriceRoundedOff'];


                                    if(Session::get('CurrencyCode') == 'ILS'){

                                        $inis_markup = (($paymeComm / 100) * ( $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                                        $price_with_markup = $inis_markup + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                                        $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;
                                        //$taxes = $taxes + env('PAYME_FIX_FEES');

                                    }else{

                                        $taxes = ($commisioninis_currency / 100) * $price_with_markup;
                                    }

                                    $roomC['FinalPrice'] = $inis_markup + $taxes + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal;

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
                    } else {
                        foreach ($room_combination['RoomCombination'] as $k => $combinations) {
                            for ($r = 0; $r < sizeof($combinations['RoomIndex']); $r++) {
                                foreach ($rooms['GetHotelRoomResult']['HotelRoomsDetails'] as $key => $roomC) {

                                    $type = preg_replace('/\s*/', '', $roomC['RoomTypeName']);
                                    if(isset($roomStaticData[$type])) {
                                        $roomC['staticData'] = $roomStaticData[$type];
                                        if(isset($roomC['staticData']['ameneties'])) {
                                            $roomC['staticData']['ameneties'] = json_decode($roomC['staticData']['ameneties'], true);
                                            foreach ($roomC['staticData']['ameneties'] as $key => $am) {
                                                array_push($roomC['Amenity'], $am);
                                            }
                                            $roomC['Amenity'] = array_unique($roomC['Amenity']);
                                        }
                                        if(isset($roomC['staticData']['bed_type'])) {
                                            $roomC['staticData']['bed_type'] = json_decode($roomC['staticData']['bed_type'], true);
                                        }
                                    } else {
                                        $roomC['staticData'] = array();
                                    }

                                    unset($roomC['ChildCount']);
                                    unset($roomC['RequireAllPaxDetails']);
                                    unset($roomC['RoomId']);
                                    unset($roomC['RoomStatus']);
                                    unset($roomC['RatePlan']);
                                    unset($roomC['DayRates']);
                                    unset($roomC['IsPerStay']);
                                    unset($roomC['SupplierPrice']);
                                    unset($roomC['RoomPromotion']);
                                    unset($roomC['HotelSupplements']);

                                    $tdsVal = ((env('INIS_TDS') / 100) * ( $roomC['Price']['OfferedPriceRoundedOff'] ));

                                    $inis_markup = (($commisioninis / 100) * ( $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                                    $price_with_markup = $inis_markup + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal;
                                    //$taxes = ($commisioninis_currency / 100) * $price_with_markup;
                                    //$roomC['FinalPrice'] = $inis_markup + $taxes + $roomC['Price']['OfferedPriceRoundedOff'];

                                    if(Session::get('CurrencyCode') == 'ILS'){

                                        $inis_markup = (($paymeComm / 100) * ( $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal ));
                                        $price_with_markup = $inis_markup + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal;

                                        $taxes = (env('PAYME_FEES') / 100) * $price_with_markup;
                                        //$taxes = $taxes + env('PAYME_FIX_FEES');

                                    }else{

                                        $taxes = ($commisioninis_currency / 100) * $price_with_markup;
                                    }

                                    $roomC['FinalPrice'] = $inis_markup + $taxes + $roomC['Price']['OfferedPriceRoundedOff'] + $tdsVal;

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
                                        if (isset($rooms_temp_array['rooms_' . $r]) && sizeof($rooms_temp_array['rooms_' . $r]) > 0) {
                                            if (isset($rooms_temp_array['rooms_' . $r][0]) && isset($rooms_temp_array['rooms_' . $r][0]['InfoSource']) && $rooms_temp_array['rooms_' . $r][0]['InfoSource'] != 'OpenCombination') {

                                                array_push($rooms_temp_array['rooms_' . $r], $roomC);
                                            }
                                        } else {

                                            if(!is_array($rooms_temp_array['rooms_' . $r])) {
                                                $rooms_temp_array['rooms_' . $r] = array();
                                            }
                                            array_push($rooms_temp_array['rooms_' . $r], $roomC);
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
                    }
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
                                    'hotelSearchInput' => $hotelSearchInput,
                                    'commisioninis_currency' => $commisioninis_currency,
                                    'search_id' => $search_id,
                                    'lottery_limit' => $lottery_Limit));
            } else {
                if (isset($rooms_temp_array) && isset($rooms_temp_array['rooms_0']) && isset($rooms_temp_array['rooms_0'][0]) && isset($rooms_temp_array['rooms_0'][0]['InfoSource'])) {

                    if ($rooms_temp_array['rooms_0'][0]['InfoSource'] == 'OpenCombination') {
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
                            'hotelSearchInput' => $hotelSearchInput,
                            'commisioninis_currency' => $commisioninis_currency,
                            'search_id' => $search_id,
                            'lottery_limit' => $lottery_Limit));
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

    public function viewRoom(Request $request) {


        if (Session::has('paid')) {
            Session::forget('paid');
        }


        $input = $request->all();

        $roomCategory = $request->category; //str_replace('-', '#', $request->category);
        $this->hotelName = $input['hotelName'];
        $this->hotelCode = $input['hotelCode'];
        $this->hoteIndex = $input['hotelIndex']; //Session::get('resultIndex');
        $this->traceId = $request->traceId;

        $search_id = $request->search_id;
        $destinationPath=public_path()."/logs/searches/hotels/" . $search_id . '.json';
        if (file_exists($destinationPath)){
            
            $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);

        } else {
            //redirect to view hotel page
            //'/hotel/{country}/{city}/{hotel_name}/{traceId}/{code}/{checkIn}/{rooms}/{city_id}/{nights}/{referral}'
            $queryValues = $request->query();
            //check if shared from social media
            if(!isset($queryValues['a1'])) {
                $queryValues = array();
                for($i = 1; $i <= $rooms_count; $i++) {
                    $queryValues['a' . $i] = '2';
                    $queryValues['c' . $i] = '0';
                }
            }
            $hotel_data = StaticDataHotels::select('city_id','hotel_name')->where('hotel_code', $request->code)->first();
            $city_data = Cities::where('CityId', $hotel_data['city_id'])->first();

            $queryVals = '';
            foreach ($queryValues as $q_k => $q) {
                $queryVals = $queryVals . $q_k . '=' . $q . '&';
            }
            return redirect('/hotel/' . strtolower(str_replace(' ', '-', $city_data['Country'])) . '/' . strtolower(str_replace(' ', '-', $city_data['CityName'])) . '/' . strtolower(str_replace(' ', '-', $hotel_data['hotel_name'])) . '/' . $request->traceId . '/' . $request->code . '/' . $request->checkIn . '/' . $request->rooms . '/' . $request->search_id . '/' . $request->nights . '/' . $request->referral . '?' . $queryVals);
        }

        $this->checkInDate = $search_contents['request']['departdate'];
        $this->checkOutDate = $search_contents['request']['returndate'];
        $this->noOfRooms = $search_contents['request']['roomCount'];

        $roomGuest = $search_contents['request']['roomsGuests'];

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
            foreach ($input['room'] as $key => $r) {
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

        if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

            $getlocation = json_decode($this->getCookie('th_country'));
        } else {

            $getlocation = \Location::get($this->end_user_ip);
        }


        $guestNationality = $getlocation->countryCode;

        $CategoryId = $roomCategory;


        if ($request->referral != '' && $request->referral != '0') {

            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request->referral])->first();
            if (isset($checkrefferal)) {

                $commisioninis = env('INIS_VAL');
                $paymeComm = env('INIS_VAL_PAYME');
            } else {
                $commisioninis = env('INIS_VAL');
                $paymeComm = env('INIS_VAL_PAYME');
            }   
        } else {

            $commisioninis = env('INIS_VAL');
            $paymeComm = env('INIS_VAL_PAYME');
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
            $paymeComm = env('INIS_VAL_PAYME_AGENT');
        }

        $commisioninis_currency = env('INR_FEES');


        $this->api = new TBOHotelAPI();
        $roomDetails = $this
                ->api
                ->hotelBlockRoom($this->hotelCode, $this->hoteIndex, $this->traceId, $this->hotelName, $selectedRooms, $this->noOfRooms, $guestNationality, $CategoryId, $isVoucherBooking);

        $blockRequest = array('hotelCode' => $this->hotelCode,
                              'hoteIndex' => $this->hoteIndex,
                              'traceId' => $this->traceId,
                              'hotelName' => $this->hotelName,
                              'selectedRooms' => $selectedRooms,
                              'noOfRooms' => $this->noOfRooms,
                              'guestNationality' => $guestNationality,
                              'CategoryId' => $CategoryId,
                              'roomPhoto' => $roomPhoto);
        
        $fileName = $search_id . '_block_request.json';
        $blockRequest['isVoucherBooking'] = $isVoucherBooking;
        $blockRequest['lastCancellationDate'] = $lastCancellationDate;
        $this->saveBlockRoomData($fileName, json_encode($blockRequest));

        if ($roomDetails['BlockRoomResult']['ResponseStatus'] != 1) {
            $this->writeLogs(array('HotelCode' => $this->hotelCode, 'HotelIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'selectedRooms' => $selectedRooms), $roomDetails['BlockRoomResult']);

            Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode(array('HotelCode' => $this->hotelCode, 'HotelIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'selectedRooms' => $selectedRooms)), json_encode($roomDetails) ));

            return view('500')->with(['error' => 'This room is not available, Kindly select another room for your stay']);
        }

        $blockRoom = $roomDetails['BlockRoomResult']['HotelRoomsDetails'];

        $fileName = $search_id . '_block.json';
        
        $this->saveBlockRoomData($fileName, json_encode($roomDetails));
        // Session::put('BookRoomDetails', $blockRoom);
        $isPackageFare = $roomDetails['BlockRoomResult']['IsPackageFare'];
        $isPackageDetailsMandatory = $roomDetails['BlockRoomResult']['IsPackageDetailsMandatory'];

        // Session::put('IsPackageFare', $isPackageFare);
        // Session::put('IsPackageDetailsMandatory', $isPackageDetailsMandatory);


        $hotel = StaticDataHotels::select('hotel_images')->where('hotel_code', $this->hotelCode)->first();


        StaticDataHotels::where('hotel_code', $this->hotelCode)->update(['hotel_policy' => $roomDetails['BlockRoomResult']['HotelPolicyDetail']]);

        if (isset($hotel['hotel_images']) && !empty($hotel['hotel_images'])) {
            $hotel['hotel_images'] = json_decode($hotel['hotel_images']);
        }


        if ($request->referral != '' && $request->referral != '0') {


            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request
                        ->referral])
                    ->first();
            if (isset($checkrefferal)) {

                $commisioninis = env('INIS_VAL');
                $paymeComm = env('INIS_VAL_PAYME');
            } else {
                $commisioninis = env('INIS_VAL');
                $paymeComm = env('INIS_VAL_PAYME');
            }
        } else {

            $commisioninis = env('INIS_VAL');
            $paymeComm = env('INIS_VAL_PAYME');
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
                $paymeComm = env('INIS_VAL_PAYME_AGENT');
            }
        }

        $type = preg_replace('/\s*/', '', $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['RoomTypeName']);
        $images = RoomImages::select('images')->where(['sub_domain' => $this->hotelCode])->first();

        $show_markup = false;
        if (Auth::user()) {
            // $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();

            // if (isset($agent) && $agent['referal_code'] && $agent['referal_code'] == $referral) {
            //     $show_markup = true;
            // }
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

        return view('search.hotels.view-room')->with(['rphoto' => $roomPhoto, 'roomDetails' => $roomDetails, 'checkInDate' => $this->checkInDate, 'checkOutDate' => $this->checkOutDate, 'ResultIndex' => $this->hoteIndex, 'roomGuests' => $roomGuest, 'noOfRooms' => $this->noOfRooms, 'sub_domain' => $this->hotelCode, 'CategoryId' => $CategoryId, 'hotel_img' => (isset($hotel['hotel_images'][0])) ? $hotel['hotel_images'] : [], 'commission' => $commisioninis, 'referral' => $referral, 'room_images' => $images, 'guestNationality' => $guestNationality, 'hotel_code' => $this->hotelCode, 'traceId' => $this->traceId, 'show_markup' => $show_markup, 'search_id' => $search_id, 'input_data' => $search_contents['request'], 'payme_pay' => false, 'paidAmtILS' => $paidAmtILS, 'paymeComm' => $paymeComm , 'agentMarkup' => $agentMarkup, 'isILS' => $isILS]);
    }

    public function viewRoomGet(Request $request) {

        $search_id = $request->search_id;
        $destinationPath=public_path()."/logs/searches/hotels/" . $search_id . '_block_request.json';
        $paymentVal = false;
        $queryValues = $request->query();

        if (file_exists($destinationPath)){


            $this->api = new TBOHotelAPI();

            //echo "<pre>";print_r($queryValues);die;


            $blockRequest = json_decode($this->readSearchData($search_id . '_block_request.json'), true);
            if (isset($blockRequest['roomPhoto']) && $blockRequest['roomPhoto'] != '' && !empty($blockRequest['roomPhoto'])) {
                $roomPhoto = $blockRequest['roomPhoto'];
            } else {
                $roomPhoto = 'https://b2b.tektravels.com/Images/HotelNA.jpg';
            }

            $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
            $this->checkInDate = $search_contents['request']['departdate'];
            $this->checkOutDate = $search_contents['request']['returndate'];
            $this->noOfRooms = $search_contents['request']['roomCount'];
            
            $roomGuest = $search_contents['request']['roomsGuests'];

            $roomDetails = $this
                ->api
                ->hotelBlockRoom($blockRequest['hotelCode'], $blockRequest['hoteIndex'], $blockRequest['traceId'], $blockRequest['hotelName'], $blockRequest['selectedRooms'], $blockRequest['noOfRooms'], $blockRequest['guestNationality'], $blockRequest['CategoryId'], $blockRequest['isVoucherBooking']);

            if ($roomDetails['BlockRoomResult']['ResponseStatus'] != 1) {
                
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

                $this->writeLogs(array('HotelCode' => $blockRequest['hotelCode'], 'HotelIndex' => $blockRequest['hoteIndex'], 'TraceId' => $blockRequest['traceId'], 'HotelName' => $blockRequest['hotelName'], 'selectedRooms' => $blockRequest['selectedRooms']), $roomDetails['BlockRoomResult']);

                Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode(array('HotelCode' => $blockRequest['hotelCode'], 'HotelIndex' => $blockRequest['hoteIndex'], 'TraceId' => $blockRequest['traceId'], 'HotelName' => $blockRequest['hotelName'], 'selectedRooms' => $blockRequest['selectedRooms'])), json_encode($roomDetails) ));

                // return paid amount back to wallet


                 return view('500')->with(['error' => 'This room is not available, Kindly select another room for your stay']);
            }

            $blockRoom = $roomDetails['BlockRoomResult']['HotelRoomsDetails'];

            $fileName = $search_id . '_block.json';
            $this->saveBlockRoomData($fileName, json_encode($roomDetails));

            $isPackageFare = $roomDetails['BlockRoomResult']['IsPackageFare'];
            $isPackageDetailsMandatory = $roomDetails['BlockRoomResult']['IsPackageDetailsMandatory'];

            $hotel = StaticDataHotels::select('hotel_images')->where('hotel_code', $blockRequest['hotelCode'])->first();


            StaticDataHotels::where('hotel_code', $blockRequest['hotelCode'])->update(['hotel_policy' => $roomDetails['BlockRoomResult']['HotelPolicyDetail']]);

            if (isset($hotel['hotel_images']) && !empty($hotel['hotel_images'])) {
                $hotel['hotel_images'] = json_decode($hotel['hotel_images']);
            }


            if ($request->referral != '' && $request->referral != '0') {


                $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $request
                            ->referral])
                        ->first();
                if (isset($checkrefferal)) {

                    $commisioninis = env('INIS_VAL');
                    $paymeComm = env('INIS_VAL_PAYME');
                } else {
                    $commisioninis = env('INIS_VAL');
                    $paymeComm = env('INIS_VAL_PAYME');
                }
            } else {

                $commisioninis = env('INIS_VAL');
                $paymeComm = env('INIS_VAL_PAYME');
            }

            if ($request->referral != '') {
                $referral = $request->referral;
            } else {
                $referral = '0';
            }


            if ($request->referral && $request->referral != '' && $request->referral != '0') {
                $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $blockRequest['hotelCode'] . "'");

                if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                    $commisioninis = 10;
                    $paymeComm = env('INIS_VAL_PAYME_AGENT');
                }
            }

            $type = preg_replace('/\s*/', '', $roomDetails['BlockRoomResult']['HotelRoomsDetails'][0]['RoomTypeName']);
            $images = RoomImages::select('images')->where(['sub_domain' => $blockRequest['hotelCode']])->first();

            $show_markup = false;
            if (Auth::user()) {
                $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();

                if (isset($agent) && $agent['referal_code'] && $agent['referal_code'] == $referral) {
                    $show_markup = true;
                }
            }


            $paidAmtILS = 0;
            $agentMarkup = 0;
            $ilsPayDetails = array();
            if(isset($queryValues['payme_sale_id']) && $queryValues['payme_sale_id'] != ''){

                $saleID = $queryValues['payme_sale_id'];

                $paymentDetails = $this
                       ->api
                       ->checkPaymePayment(env('PAYME_KEY'), $saleID);
                $payMEDetails = $paymentDetails['items'];
                
                if(!empty($payMEDetails) && $payMEDetails[0]['transaction_id'] != '' && $payMEDetails[0]['sale_status'] == 'completed'){

                   $paymentVal = true;
                   $destinationPath=$search_id . '_payme_form_hotel.json';
                   $ilsPay = json_decode($this->readSearchData($destinationPath), true); ///Session::get('BookRoomDetails');
                   $ilsPayDetails = $ilsPay['request'];
                
                  if($ilsPayDetails['paymentMode'] == 'multiple'){

                    $paidAmtILS = ($ilsPayDetails['paidAmtILS'] == 0) ? ($ilsPayDetails['partAmountILS']) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($ilsPayDetails['partAmountILS']) + $ilsPayDetails['walletDebit'];

                    $paidAmtILSMultiple = ($ilsPayDetails['paidAmtILS'] == 0) ? ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'];

                    Session::put('multiplePayments', $paidAmtILSMultiple);

                  }else{
                    
                    $paidAmtILS = ($ilsPayDetails['paidAmtILS'] == 0) ? ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'] : $ilsPayDetails['paidAmtILS'] + ($payMEDetails[0]['sale_price'] / 100) + $ilsPayDetails['walletDebit'];

                  }

                   $pendingAmount = $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME'] - $paidAmtILS;
                   
                   // Deposit Paid Amount to User Account

                    if(Auth::user() && $ilsPayDetails['paymentMode'] == 'single') {

                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');
                        $pAmount = Currency::convert($ccrcy, 'USD', round($paidAmtILS));

                        $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $pAmount['convertedAmount']]);
                    }

                   //$agentMarkup = 0;

                   if(isset($ilsPayDetails['agent_makrup']) && $ilsPayDetails['agent_makrup'] != ''){

                        $agentMarkup = $ilsPayDetails['agent_makrup'];

                   }

                   if($ilsPayDetails['walletDebit'] > 0){

                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');
                        $pAmount = Currency::convert($ccrcy, 'USD', $ilsPayDetails['walletDebit']);

                        $walletuser->withdraw(round($pAmount['convertedAmount']), ['description' => "Card payment " . $ilsPayDetails['walletDebit'] . "(" . $ccrcy . ") received from card for Multiple Payment transaction_id :- " . $payMEDetails[0]['transaction_id']]);

                   }


                   if($pendingAmount <= 0) {


                        $hotelId = $this->bookRoomILS($ilsPayDetails);

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
                            $walletuser->withdraw($debitAmnt, ['BookingID' => $hotelId]);
                            
                            $walletAmount = \Auth::user()->balance;
                            Session::forget('walletAmount');
                        }


                 
                        if(isset($hotelId['success']) && $hotelId['success']){

                            return redirect('/thankyou/hotel/' . $hotelId['booking_id'] . '/true');

                        }else{

                            //add oney to wallet

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

                            // $walletuser = \Auth::user();
                            // $pAmount = Currency::convert('ILS', 'USD', $ilsPayDetails['ORIGINAL_BOOKING_PRICE_PME']);
                            // $walletuser->deposit($pAmount['convertedAmount'], ["description" => 'Multicard partial payment(ILS) -' . $input['amount']]);

                            return view('500')->with(['error' => $hotelId['message']]);  
                        }

                   }


                }else{

                    $paymentVal = false;

                    $destinationPath=$search_id . '_payme_form_hotel.json';
                    $ilsPay = json_decode($this->readSearchData($destinationPath), true); ///Session::get('BookRoomDetails');
                    $ilsPayDetails = $ilsPay['request'];

                    //echo "<pre>";print_r($ilsPayDetails);die;

                    $this->writePaymeLogs($ilsPayDetails, $payMEDetails);

                    Mail::to(env('NO_RESULT_EMAIL'))->send(new FailedPaymentEmail(json_encode($ilsPayDetails), json_encode($payMEDetails) ));

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


            return view('search.hotels.view-room')->with(['rphoto' => $roomPhoto, 'roomDetails' => $roomDetails, 'checkInDate' => $this->checkInDate, 'checkOutDate' => $this->checkOutDate, 'ResultIndex' => $blockRequest['hoteIndex'], 'roomGuests' => $roomGuest, 'noOfRooms' => $blockRequest['noOfRooms'], 'sub_domain' => $blockRequest['hotelCode'], 'CategoryId' => $blockRequest['CategoryId'], 'hotel_img' => (isset($hotel['hotel_images'][0])) ? $hotel['hotel_images'] : [], 'commission' => $commisioninis, 'referral' => $referral, 'room_images' => $images, 'guestNationality' => $blockRequest['guestNationality'], 'hotel_code' => $blockRequest['hotelCode'], 'traceId' => $blockRequest['traceId'], 'show_markup' => $show_markup, 'search_id' => $search_id, 'input_data' => $search_contents['request'] ,'payme_pay' => $paymentVal, 'paidAmtILS' => $paidAmtILS, 'paymeComm' => $paymeComm, 'agentMarkup' => $agentMarkup, 'isILS' => $isILS ]);

        } else {

            // Send Money back to Wallet for Single Payment

            $saleID = $queryValues['payme_sale_id'];

            $this->api = new TBOHotelAPI();

            $paymentDetails = $this
                   ->api
                   ->checkPaymePayment(env('PAYME_KEY'), $saleID);
            $payMEDetails = $paymentDetails['items'];

            if(!empty($payMEDetails) && $payMEDetails[0]['sale_status'] == 'completed'){


                $destinationPath=$search_id . '_payme_form_hotel.json';
                $ilsPay = json_decode($this->readSearchData($destinationPath), true); ///Session::get('BookRoomDetails');
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

        //redirect to view hotel page
        $queryValues = $request->query();
        //check if shared from social media
        if(!isset($queryValues['a1'])) {
            $queryValues = array();
            for($i = 1; $i <= $rooms_count; $i++) {
                $queryValues['a' . $i] = '2';
                $queryValues['c' . $i] = '0';
            }
        }
        $hotel_data = StaticDataHotels::select('city_id','hotel_name')->where('hotel_code', $request->code)->first();
        $city_data = Cities::where('CityId', $hotel_data['city_id'])->first();

        $queryVals = '';
        foreach ($queryValues as $q_k => $q) {
            $queryVals = $queryVals . $q_k . '=' . $q . '&';
        }
        return redirect('/hotel/' . strtolower(str_replace(' ', '-', $city_data['Country'])) . '/' . strtolower(str_replace(' ', '-', $city_data['CityName'])) . '/' . strtolower(str_replace(' ', '-', $hotel_data['hotel_name'])) . '/' . $request->traceId . '/' . $request->code . '/' . $request->checkIn . '/' . $request->rooms . '/' . $request->search_id . '/' . $request->nights . '/' . $request->referral . '?' . $queryVals);
        }
    }


    public function saveWalletSingle(Request $request){

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

        return response()->json(array(
                    'message' => "Wallet added",
                    'success' => true
        ));

    }

    public function bookRoom(Request $request) {

        $input = $request->all();

        $roomIndex = $request->index;

        $roomPassengers = $input['roomPassengers'];
        $category = $request->category;
        $combination = $request->combination;
        $roomIndexes = $request->roomIndexes;
        $search_id = $request->search_id;

        $hotelPassengers = array();
        $roomGuest = $input['roomPassengers']; //Session::get('roomGuests');

        $destinationPath=$search_id . '_block.json';
        $roomDetails = json_decode($this->getBlockRoomData($destinationPath), true);///Session::get('BookRoomDetails');
        $BookRoomDetails = $roomDetails['BlockRoomResult']['HotelRoomsDetails'];

        if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

            $getlocation = json_decode($this->getCookie('th_country'));
        } else {

            $getlocation = \Location::get($this->end_user_ip);
        }

        $guestNationality = $getlocation->countryCode;
        $roomCount = 1;
        $userEmail = '';
        $userFirstName = '';
        $userLastName = '';
        $userPhone = '';

        if ($input['referral'] != '' && $input['referral'] != '0') {

            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();

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

        $this->hotelCode = $request->hotelCode;//Session::get('hotelCode');

        if ($input['referral'] != '' && $input['referral'] != '0') {
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

            if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                $commission = 10;
            }
        }

        foreach ($roomGuest as $room => $guest) {
            $NoOfAdults = sizeof($guest['adult']);
            if (isset($guest['child'])) {

                $NoOfChild = $guest['child'];
            }
            $hotelPassengers[$room] = array();

            for ($a = 1; $a <= $NoOfAdults; $a++) {
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

                for ($c = 1; $c <= sizeof($NoOfChild); $c++) {

                    array_push($hotelPassengers[$room], array(
                        'Title' => 'Mr',
                        'FirstName' => $guest['child'][$c]['first_name'],
                        'Middlename' => null,
                        'LastName' => $guest['child'][$c]['last_name'],
                        'PAN' => isset($guest['child'][$c]['panNo']) ? $guest['child'][$c]['panNo'] : null,
                        'PaxType' => 2,
                        'Age' => $guest['child'][$c]['age']
                    ));
                }
            }

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
        $amount = $amount + $commission_price;
        $commission_agent = $commission_price;
        $add_to_subdomain = false;

        $commission_sub_doamin = 0;
        /*
        *split comission if booking from sub somain hotel
        */
        if ($input['referral'] && $input['referral'] != '' && $input['referral'] != '0') {
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

            if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                $commisioninis = 10;

                $commission_agent = (5 / 100 * $amount_temp);
                $commission_sub_doamin = $commission_agent;

                $add_to_subdomain = true;
            }
        } else {
        /*
        *check if only sub domain is there
        */
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

            if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                $commisioninis = 5;

                $commission_agent = 0;
                $commission_sub_doamin = (5 / 100 * $amount_temp);

                $add_to_subdomain = true;
            }
        }

        if ($commission_agent > 0) {
            // if (isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
            //     $commission_agent = $commission_agent + ($input['agent_makrup'] * 0.60);
            // }

            $lAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $commission_agent);
            $commission_agent = $lAmount['convertedAmount'];
        }

        
        if ($commission_sub_doamin > 0) {
            $lAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $commission_sub_doamin);
            $commission_sub_doamin = $lAmount['convertedAmount'];
        }


        $conversion_payment = 0;
        
        if (Session::get('CurrencyCode') == 'INR') {

            $conversion = env('INR_FEES');

        } else {

            $conversion = env('INT_FEES');

        }

        $conversion_payment = ( $conversion / 100 * $amount );


        $amount = $amount + $conversion_payment;

        $amount = round($amount * 100, 2);

        $this->hotelName = $input['hotelName'];
        $this->hotelCode = $input['hotelCode'];
        $this->hoteIndex = $input['hotelIndex']; //Session::get('resultIndex');
        $this->traceId = $input['traceId'];
        $this->checkInDate = $input['checkInDate'];
        $this->checkOutDate = $input['checkOutDate'];

        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);

        $this->noOfRooms = $search_contents['request']['roomCount'];

        $date = Carbon::createFromDate($this->checkInDate);
        $now = Carbon::createFromDate($this->checkOutDate);

        $noOfNights = $date->diffInDays($now);

        $this->IsPackageFare = $roomDetails['BlockRoomResult']['IsPackageFare'];//Session::get('IsPackageFare');
        $this->IsPackageDetailsMandatory = $roomDetails['BlockRoomResult']['IsPackageDetailsMandatory'];//Session::get('IsPackageDetailsMandatory');

        unset($BookRoomDetails['Price']['GST']);
        unset($BookRoomDetails['Price']['ServiceCharge']);
        unset($BookRoomDetails['Price']['TotalGSTAmount']);

        $BookRoomDetails['HotelPassenger'] = $hotelPassengers;

        $hotel_details = StaticDataHotels::where('hotel_code', $this->hotelCode)->first();

        try {

            /*
             * First check if customer exists in the DB
             */
            if (Auth::user()) {
                $user = Auth::user();
            } else {
                $user = User::where('email', $userEmail)->first();
            }

            if (isset($user) && $user->id) {
                $customer_id = $user->customer_id;
                if ($user->customer_id == '') {
                    
                }
                Auth::login($user);
            } else {

                $password = $this->generateRandomString();
                $user = User::create(['name' => $userFirstName . ' ' . $userLastName, 'email' => $userEmail, 'phone' => $userPhone, 'address' => ' ', 'role' => 'user', 'password' => Hash::make($password), 'password_changed' => 1]);
                Mail::to($userEmail)->send(new NewUserRegister($user, $password));
                Auth::login($user);
            }

            $blockRequest = json_decode($this->readSearchData($search_id . '_block_request.json'), true);

            $this->api = new TBOHotelAPI();
            $bookRoomData = $this
                    ->api
                    ->hotelBookRoom($this->checkInDate, $this->hotelCode, $this->hoteIndex, $this->traceId, $this->hotelName, $HotelRoomsDetails, $this->noOfRooms, $this->IsPackageFare, $this->IsPackageDetailsMandatory, $guestNationality, $input['CategoryId'], $blockRequest['isVoucherBooking']);
                    
            if (isset($bookRoomData['BookResult']) && isset($bookRoomData['BookResult']['ResponseStatus']) && $bookRoomData['BookResult']['ResponseStatus'] == 1) {
                $bookingDetails = $bookRoomData['BookResult'];

                /* check if lottery reach its entry limit */

               // if ($request->paymentMode == 'multiple') {

                    if (Session::has('paid')) {

                        $paid = Session::get('paid');
                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');
                        $pAmount = Currency::convert($ccrcy, 'USD', $paid);

                        $walletuser->withdraw(round($pAmount['convertedAmount']), ['description' => "Card payment " . $paid . "(" . $ccrcy . ") received from card for booking ID :- " . $bookingDetails['BookingId']]);
                        Session::forget('paid');
                    }
               // }

                /* get active lottery */
                $lottery = Lottery::where(['lotteryStatus' => 'active'])->first();

                if (isset($lottery)) {
                    /* find all enrolled user count */
                    $lotryNos = LotteryUsers::select(['id'])->where(['lotteryID' => $lottery->id])->get()->toArray();
                }

                /* check if entry limit reach the quota to announce winner */
                if (isset($lottery) && $lottery->entryLimit <= count($lotryNos)) {

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

                if (isset($input['walletPay']) && $input['walletPay'] == 'yes' && $input['walletDebit'] > 0) {

                    $myCurrency = Session::get('CurrencyCode');
                    $usercurrency = Currency::convert($myCurrency, 'USD', $input['walletDebit']);
                    $debitAmnt = round($usercurrency['convertedAmount']);


                    $walletuser = \Auth::user();
                    $walletuser->withdraw($debitAmnt, ['BookingID' => $bookingDetails['BookingId']]);

                    $walletAmount = \Auth::user()->balance;
                    Session::put('walletAmount', $walletAmount);
                }


                $amount = ($amount / 100);
                $extraMarkup = 0;
                if (isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
                    
                    $amount = round($amount + $input['agent_makrup'], 2);

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $amount);
                    $mAmountC = $mAmount['convertedAmount'];

                    $extraMarkup = Currency::convert(Session::get('CurrencyCode'), 'USD', round($input['agent_makrup']  * 0.60));
                    $extraMarkup = $extraMarkup['convertedAmount'];

                    $partners_markup = env('PARTNER_COMMISION_MARKUP');

                    $partners_markup_rest = env('PARTNER_COMMISION_REST_MARKUP');

                    $markupPartner = round($input['agent_makrup']  * 0.40);

                    $markupPartnerCommission = ( ( $partners_markup / 100 ) * $markupPartner);

                    $markupPartnerRestCommission = ( ( $partners_markup_rest / 100 ) * $markupPartner);

                    $convertPrtnr = Currency::convert(Session::get('CurrencyCode'), 'USD', $markupPartnerCommission );
                    $convertPrtnrAmount = $convertPrtnr['convertedAmount'];

                    $convertPrtnrest = Currency::convert(Session::get('CurrencyCode'), 'USD', $markupPartnerRestCommission );
                    $convertPrtnrestAmount = $convertPrtnrest['convertedAmount'];

                } else {
                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $amount);
                    $mAmountC = $mAmount['convertedAmount'];

                    $convertPrtnrAmount = 0;
                    $convertPrtnrestAmount = 0;
                }

                // Get Partner's commision




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
                                'base_price' => round($base_price, 2),
                                'tax_price' => round($tax_price, 2),
                                'amount_tbo' => round($oldAmount, 2),
                                'amount' => round($amount, 2),
                                'isVoucherBooking' => $blockRequest['isVoucherBooking'],
                                'lastCancellationDate' => $blockRequest['lastCancellationDate'],
                                'razorpay_payment_id' => $input['razorpay_payment_id'],
                                'razorpay_signature' => $input['razorpay_signature'],
                ))]);

                $booking->request_data = json_decode($booking->request_data, true);
                /*
                *create entry to payments table
                */

                $payments = Payments::create(['booking_id' => $booking->id, 'user_id' => Auth::user()->id, 'agent_id' => $agent_id, 'commission' => $commission_agent, 'price' => $amount, 'price_convered' => $mAmountC,'partners_commision' => $convertPrtnrAmount,'partners_commision_rest' => $convertPrtnrestAmount, 'customer_id' => Auth::user()->id, 'sub_domain' => '', 'agent_markup' => $extraMarkup]);



                if ($add_to_subdomain) {

                    $check_hotel_subdomain = DB::connection('mysql2')->select("INSERT INTO `main_bookings` (`booking_id`, `hotel_code`, `currency_code`, `user_id`, `agent_id`, `total_paid`, `comission_earned`, `booking_type`, `created_at`) VALUES ('" . $booking['id'] . "', '" . $this->hotelCode . "', '" . Session::get('CurrencyCode') . "', '" . Auth::user()->id . "', '" . $agent_id . "', '" . $mAmountC . "', '" . $commission_sub_doamin . "', 'hotel', '" . date('Y-m-d h:i:s') . "')");
                }

                $currency = Session::get('CurrencyCode');

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


                if (isset($agent_id) && $agent_id != '') {

                    $post_content = 'Booking for <b>' . $this->hotelName . '</b> city <b>' . $search_contents['request']['city_name'] . '</b> checkin <b>' . date('l, F d Y', strtotime($this->checkInDate)) . '</b> checkout <b>' . date('l, F d Y', strtotime($this->checkOutDate)) . '</b> for ' . $search_contents['request']['roomsGuests'] . '<br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($amount, 2) . '</b>';
                    //create story for profile page
                    Posts::create(['post_type' => 'article_image',
                        'post_content' => $post_content,
                        'post_media' => $hotel_data['hotel_image'],
                        'user_id' => Auth::user()->id]);


                    $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                                'type' => 'hotel',
                                'description' => $post_content,
                                'price' => 'USD ' . round($commission_agent, 2),
                                'status' => 0
                    ]);
                }


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


                $this->emptySession($search_id);

                return redirect('/thankyou/hotel/' . $booking->id . '/true');
            } else {
                $this->writeLogs(array('checkInDate' => $this->checkInDate, 'hotelCode' => $this->hotelCode, 'hoteIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'HotelRoomsDetails' => $HotelRoomsDetails), $bookRoomData['BookResult']);

                Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode(array('checkInDate' => $this->checkInDate, 'hotelCode' => $this->hotelCode, 'hoteIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'HotelRoomsDetails' => $HotelRoomsDetails)), json_encode($bookRoomData) ));
                
                $message = $bookRoomData['BookResult']['Error']['ErrorMessage'];
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

        return view('500')->with(['error' => $message]);
    }

    public function bookRoomILS($dataVal) {

        $input = $dataVal;
        
        $installments = $input['installments'];

        $roomPassengers = $input['roomPassengers'];
        $search_id = $input['search_id'];
        $walletDebit = $input['walletDebit'];

        $hotelPassengers = array();
        $roomGuest = $input['roomPassengers']; //Session::get('roomGuests');

        $destinationPath=$search_id . '_block.json';
        $roomDetails = json_decode($this->getBlockRoomData($destinationPath), true);///Session::get('BookRoomDetails');
        $BookRoomDetails = $roomDetails['BlockRoomResult']['HotelRoomsDetails'];

        if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

            $getlocation = json_decode($this->getCookie('th_country'));
        } else {

            $getlocation = \Location::get($this->end_user_ip);
        }

        $guestNationality = $getlocation->countryCode;
        $roomCount = 1;
        $userEmail = '';
        $userFirstName = '';
        $userLastName = '';
        $userPhone = '';

        if ($input['referral'] != '' && $input['referral'] != '0') {

            $checkrefferal = AffiliateUsers::select('user_id')->where(['referal_code' => $input['referral']])->first();

            if (isset($checkrefferal)) {

                $agent_id = $checkrefferal['user_id'];
                $agentemail = User::select('email')->where(['id' => $agent_id])->first();
                $agentemail = $agentemail['email'];
                $commission = env('INIS_VAL');
                $paymeComm = env('INIS_VAL_PAYME');
            } else {
                $agentemail = '';
                $agent_id = '';
                $commission = env('INIS_VAL');
                $paymeComm = env('INIS_VAL_PAYME');
            }
        } else {

            $agentemail = '';
            $agent_id = '';
            $commission = env('INIS_VAL');
            $paymeComm = env('INIS_VAL_PAYME');
        }

        $this->hotelCode = $input['hotelCode'];//Session::get('hotelCode');

        if ($input['referral'] != '' && $input['referral'] != '0') {
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

            if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                $commission = 10;
                $paymeComm = env('INIS_VAL_PAYME_AGENT');
            }
        }

        foreach ($roomGuest as $room => $guest) {
            $NoOfAdults = sizeof($guest['adult']);
            if (isset($guest['child'])) {

                $NoOfChild = $guest['child'];
            }
            $hotelPassengers[$room] = array();

            for ($a = 1; $a <= $NoOfAdults; $a++) {
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

                for ($c = 1; $c <= sizeof($NoOfChild); $c++) {

                    array_push($hotelPassengers[$room], array(
                        'Title' => 'Mr',
                        'FirstName' => $guest['child'][$c]['first_name'],
                        'Middlename' => null,
                        'LastName' => $guest['child'][$c]['last_name'],
                        'PAN' => isset($guest['child'][$c]['panNo']) ? $guest['child'][$c]['panNo'] : null,
                        'PaxType' => 2,
                        'Age' => $guest['child'][$c]['age']
                    ));
                }
            }

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

            $tdsVal = ((env('INIS_TDS') / 100) * ( $price['OfferedPriceRoundedOff'] ));

            $amount = $amount + $price['OfferedPriceRoundedOff'] + $tdsVal;
            $oldAmount = $oldAmount + $price['OfferedPriceRoundedOff'] + $tdsVal;
            $amount_temp = $amount_temp + $price['OfferedPriceRoundedOff'] + $tdsVal;


            $roomCountDetails++;
        }

        $commission_price = ($commission / 100 * $amount_temp);

        if (Session::get('CurrencyCode') == 'ILS') {

            $commission_price = ($paymeComm / 100 * $amount_temp);
        }

        $amount = $amount + $commission_price;
        $commission_agent = $commission_price;
        $add_to_subdomain = false;

        $commission_sub_doamin = 0;
        /*
        *split comission if booking from sub somain hotel
        */
        if ($input['referral'] && $input['referral'] != '' && $input['referral'] != '0') {
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

            if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                $commisioninis = $paymeComm;

                $commission_agent = (5 / 100 * $amount_temp);
                $commission_sub_doamin = $commission_agent;

                $add_to_subdomain = true;
            }
        } else {
        /*
        *check if only sub domain is there
        */
            $check_hotel_subdomain = DB::connection('mysql2')->select("SELECT * FROM `users` WHERE  `hotel_code` = '" . $this->hotelCode . "'");

            if (isset($check_hotel_subdomain) && !empty($check_hotel_subdomain)) {
                $commisioninis = 5;

                $commission_agent = 0;
                $commission_sub_doamin = (5 / 100 * $amount_temp);

                $add_to_subdomain = true;
            }
        }

        if ($commission_agent > 0) {
            // if (isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
            //     $commission_agent = $commission_agent + ($input['agent_makrup'] * 0.60);
            // }

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

        }else if (Session::get('CurrencyCode') == 'INR') {

            $conversion = env('INR_FEES');

        } else {

            $conversion = env('INT_FEES');
        }

        $ils_conversion = 0;

        $ils_install = 0;

        $vat = 0;

        $partAmountILS = 0;
        $paidAmtILS = 0;

        if (isset($input['partAmountILS']) && $input['partAmountILS'] != '') {
            $partAmountILS = $input['partAmountILS'];
        }

        if (isset($input['paidAmtILS']) && $input['paidAmtILS'] != '') {

            $paidAmtILS = $input['paidAmtILS'];

        }

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

        $amount = $amount + $conversion_payment + $vat;

        $amount = round($amount * 100, 2);

        $this->hotelName = $input['hotelName'];
        $this->hotelCode = $input['hotelCode'];
        $this->hoteIndex = $input['hotelIndex']; //Session::get('resultIndex');
        $this->traceId = $input['traceId'];
        $this->checkInDate = $input['checkInDate'];
        $this->checkOutDate = $input['checkOutDate'];

        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);

        $this->noOfRooms = $search_contents['request']['roomCount'];

        $date = Carbon::createFromDate($this->checkInDate);
        $now = Carbon::createFromDate($this->checkOutDate);

        $noOfNights = $date->diffInDays($now);

        $this->IsPackageFare = $roomDetails['BlockRoomResult']['IsPackageFare'];//Session::get('IsPackageFare');
        $this->IsPackageDetailsMandatory = $roomDetails['BlockRoomResult']['IsPackageDetailsMandatory'];//Session::get('IsPackageDetailsMandatory');

        unset($BookRoomDetails['Price']['GST']);
        unset($BookRoomDetails['Price']['ServiceCharge']);
        unset($BookRoomDetails['Price']['TotalGSTAmount']);

        $BookRoomDetails['HotelPassenger'] = $hotelPassengers;

        $hotel_details = StaticDataHotels::where('hotel_code', $this->hotelCode)->first();

        try {

            /*
             * First check if customer exists in the DB
             */
            if (Auth::user()) {
                $user = Auth::user();
            } else {
                $user = User::where('email', $userEmail)->first();
            }

            if (isset($user) && $user->id) {
                $customer_id = $user->customer_id;
                if ($user->customer_id == '') {
                    
                }
                Auth::login($user);
            } else {

                $password = $this->generateRandomString();
                $user = User::create(['name' => $userFirstName . ' ' . $userLastName, 'email' => $userEmail, 'phone' => $userPhone, 'address' => ' ', 'role' => 'user', 'password' => Hash::make($password), 'password_changed' => 1]);
                Mail::to($userEmail)->send(new NewUserRegister($user, $password));
                Auth::login($user);
            }

            $blockRequest = json_decode($this->readSearchData($search_id . '_block_request.json'), true);

            $this->api = new TBOHotelAPI();
            $bookRoomData = $this
                    ->api
                    ->hotelBookRoom($this->checkInDate, $this->hotelCode, $this->hoteIndex, $this->traceId, $this->hotelName, $HotelRoomsDetails, $this->noOfRooms, $this->IsPackageFare, $this->IsPackageDetailsMandatory, $guestNationality, $input['CategoryId'], $blockRequest['isVoucherBooking']);
                    
            if (isset($bookRoomData['BookResult']) && isset($bookRoomData['BookResult']['ResponseStatus']) && $bookRoomData['BookResult']['ResponseStatus'] == 1) {
                $bookingDetails = $bookRoomData['BookResult'];

                /* check if lottery reach its entry limit */

               // if ($request->paymentMode == 'multiple') {

                    if (Session::has('paid')) {

                        $paid = Session::get('paid');
                        $walletuser = \Auth::user();
                        $ccrcy = Session::get('CurrencyCode');
                        $pAmount = Currency::convert($ccrcy, 'USD', $paid);

                        $walletuser->withdraw(round($pAmount['convertedAmount']), ['description' => "Card payment " . $paid . "(" . $ccrcy . ") received from card for booking ID :- " . $bookingDetails['BookingId']]);
                        Session::forget('paid');
                    }
               // }

                /* get active lottery */
                $lottery = Lottery::where(['lotteryStatus' => 'active'])->first();

                if (isset($lottery)) {
                    /* find all enrolled user count */
                    $lotryNos = LotteryUsers::select(['id'])->where(['lotteryID' => $lottery->id])->get()->toArray();
                }

                /* check if entry limit reach the quota to announce winner */
                if (isset($lottery) && $lottery->entryLimit <= count($lotryNos)) {

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

                //     $myCurrency = Session::get('CurrencyCode');
                //     $usercurrency = Currency::convert($myCurrency, 'USD', $input['walletDebit']);
                //     $debitAmnt = round($usercurrency['convertedAmount']);


                //     $walletuser = \Auth::user();
                //     $walletuser->withdraw($debitAmnt, ['BookingID' => $bookingDetails['BookingId']]);

                //     $walletAmount = \Auth::user()->balance;
                //     Session::put('walletAmount', $walletAmount);
                // }


                $amount = ($amount / 100);
                $extraMarkup = 0;
                if (isset($input['agent_makrup']) && $input['agent_makrup'] > 0) {
                    
                    $amount = round($amount + $input['agent_makrup'], 2);

                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $amount);
                    $mAmountC = $mAmount['convertedAmount'];

                    $extraMarkup = Currency::convert(Session::get('CurrencyCode'), 'USD', ($input['agent_makrup']  * 0.60));
                    $extraMarkup = $extraMarkup['convertedAmount'];

                    $partners_markup = env('PARTNER_COMMISION_MARKUP');

                    $partners_markup_rest = env('PARTNER_COMMISION_REST_MARKUP');

                    $markupPartner = ($input['agent_makrup']  * 0.40);

                    $markupPartnerCommission = ( ( $partners_markup / 100 ) * $markupPartner);

                    $markupPartnerRestCommission = ( ( $partners_markup_rest / 100 ) * $markupPartner);

                    $convertPrtnr = Currency::convert(Session::get('CurrencyCode'), 'USD', $markupPartnerCommission );
                    $convertPrtnrAmount = (isset($convertPrtnr['convertedAmount'])) ? $convertPrtnr['convertedAmount'] : 0 ;

                    $convertPrtnrest = Currency::convert(Session::get('CurrencyCode'), 'USD', $markupPartnerRestCommission );
                    $convertPrtnrestAmount = (isset($convertPrtnrest['convertedAmount'])) ? $convertPrtnrest['convertedAmount'] : 0;

                } else {
                    $mAmount = Currency::convert(Session::get('CurrencyCode'), 'USD', $amount);
                    $mAmountC = $mAmount['convertedAmount'];

                    $convertPrtnrAmount = 0;
                    $convertPrtnrestAmount = 0;
                }

                // Get Partner's commision




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
                                'base_price' => round($base_price, 2),
                                'tax_price' => round($tax_price, 2),
                                'amount_tbo' => round($oldAmount, 2),
                                'installment_price' => round($ils_install, 2),
                                'installments' => $installments,
                                'ils_fees' => round($ils_conversion, 2),
                                'amount' => round($amount, 2),
                                'vat' => round($vat, 2),
                                'isVoucherBooking' => $blockRequest['isVoucherBooking'],
                                'lastCancellationDate' => $blockRequest['lastCancellationDate'],
                                'partAmountILS' => round($partAmountILS, 2),
                                'paidAmtILS' => round($paidAmtILS, 2),
                                'walletDebit' => $walletDebit,
                                'razorpay_payment_id' => $input['razorpay_payment_id'],
                                'razorpay_signature' => $input['razorpay_signature'],
                ))]);

                $booking->request_data = json_decode($booking->request_data, true);
                /*
                *create entry to payments table
                */

                $payments = Payments::create(['booking_id' => $booking->id, 'user_id' => Auth::user()->id, 'agent_id' => $agent_id, 'commission' => $commission_agent, 'price' => $amount, 'price_convered' => $mAmountC,'partners_commision' => $convertPrtnrAmount,'partners_commision_rest' => $convertPrtnrestAmount, 'customer_id' => Auth::user()->id, 'sub_domain' => '', 'agent_markup' => $extraMarkup]);



                if ($add_to_subdomain) {

                    $check_hotel_subdomain = DB::connection('mysql2')->select("INSERT INTO `main_bookings` (`booking_id`, `hotel_code`, `currency_code`, `user_id`, `agent_id`, `total_paid`, `comission_earned`, `booking_type`, `created_at`) VALUES ('" . $booking['id'] . "', '" . $this->hotelCode . "', '" . Session::get('CurrencyCode') . "', '" . Auth::user()->id . "', '" . $agent_id . "', '" . $mAmountC . "', '" . $commission_sub_doamin . "', 'hotel', '" . date('Y-m-d h:i:s') . "')");
                }

                $currency = Session::get('CurrencyCode');

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


                if (isset($agent_id) && $agent_id != '') {

                    $post_content = 'Booking for <b>' . $this->hotelName . '</b> city <b>' . $search_contents['request']['city_name'] . '</b> checkin <b>' . date('l, F d Y', strtotime($this->checkInDate)) . '</b> checkout <b>' . date('l, F d Y', strtotime($this->checkOutDate)) . '</b> for ' . $search_contents['request']['roomsGuests'] . '<br> Total paid amount <b>' . Session::get('CurrencyCode') . ' ' . round($amount, 2) . '</b>';
                    //create story for profile page
                    Posts::create(['post_type' => 'article_image',
                        'post_content' => $post_content,
                        'post_media' => $hotel_data['hotel_image'],
                        'user_id' => Auth::user()->id]);


                    $notifications = NotificationAgents::create(['agent_id' => $agent_id,
                                'type' => 'hotel',
                                'description' => $post_content,
                                'price' => 'USD ' . round($commission_agent, 2),
                                'status' => 0
                    ]);
                }


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


                $this->emptySession($search_id);

                //return redirect('/thankyou/hotel/' . $booking->id . '/true');
                return array('success' => true, 'booking_id' => $booking->id);


            } else {
                $this->writeLogs(array('checkInDate' => $this->checkInDate, 'hotelCode' => $this->hotelCode, 'hoteIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'HotelRoomsDetails' => $HotelRoomsDetails), $bookRoomData['BookResult']);

                Mail::to(env('NO_RESULT_EMAIL'))->send(new NoResultsEmail(json_encode(array('checkInDate' => $this->checkInDate, 'hotelCode' => $this->hotelCode, 'hoteIndex' => $this->hoteIndex, 'TraceId' => $this->traceId, 'HotelName' => $this->hotelName, 'HotelRoomsDetails' => $HotelRoomsDetails)), json_encode($bookRoomData) ));
                
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

        return view('500')->with(['error' => $message]);
    }

    public function cancelBooking(Request $request) {
        if ($request->isMethod('post')) {
            $input = $request->all();
            $this->api = new TBOHotelAPI();
            $input['RequestType'] = 4;
            $input['EndUserIp'] = $this->api->userIP;
            unset($input['_token']);
            unset($input['submit']);
            try {

                $sendChangeRequest = $this
                        ->api
                        ->sendChangeRequest($input);

                if (isset($sendChangeRequest['HotelChangeRequestResult']) && $sendChangeRequest['HotelChangeRequestResult']['ResponseStatus'] == 1) {
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
                        if ($refunded_amount > 0) {
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

        }
    }

    public function cancelBookingStatus(Request $request) {
        if ($request->isMethod('post')) {
            $this->api = new TBOHotelAPI();
            $input = $request->all();
            $postData = array('TokenId' => $input['TokenId'], 'EndUserIp' => $this->api->userIP, 'ChangeRequestId' => $input['ChangeRequestId']);

            $bookingStatus = '';
            $refunded_amount = 0;

            $checkRefund = Bookings::where(['user_id' => Auth::user()->id, 'change_request_id' => $input['ChangeRequestId']])->first();

            if (isset($checkRefund) && $checkRefund['refunded_amount'] > 0) {

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

                    if ($refunded_amount > 0) {
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

    public function generateVoucher(Request $request) {
        if ($request->isMethod('post')) {
            $this->api = new TBOHotelAPI();
            $input = $request->all();
            $postData = array('TokenId' => $input['TokenId'], 'EndUserIp' => $this->api->userIP, 'BookingId' => $input['BookingId']);

            unset($input['_token']);
            unset($input['submit']);
            try {

                $generateVoucher = $this
                        ->api
                        ->generateVoucher($postData);

                if(isset($generateVoucher['GenerateVoucherResult']) && isset($generateVoucher['GenerateVoucherResult']['ResponseStatus']) && $generateVoucher['GenerateVoucherResult']['ResponseStatus'] == 1) {

                    $booking = Bookings::where(['user_id' => Auth::user()->id, 'booking_id' => $input['BookingId']])->first();
                    $booking['request_data'] = json_decode($booking['request_data'], true);

                    $request_data = $booking['request_data'];
                    
                    $request_data['isVoucherBooking'] = true;
                    

                    Bookings::where(['user_id' => Auth::user()->id, 'booking_id' => $input['BookingId']])
                            ->update(['hotel_booking_status' => $generateVoucher['GenerateVoucherResult']['HotelBookingStatus'], 
                                    'refunded_amount' => $refunded_amount,
                                    'invoice_number' => $generateVoucher['GenerateVoucherResult']['InvoiceNumber'],
                                    'request_data' => json_encode($request_data)]);

                    Session::flash('success', "Your booking has been confirmed.");
                    return redirect("/user/bookings")->with('success', "Your booking has been confirmed.");

               } else {
                   Session::flash('error', $generateVoucher['GenerateVoucherResult']['Error']['ErrorMessage']);
                   return redirect("/user/bookings")->with('error', $generateVoucher['GenerateVoucherResult']['Error']['ErrorMessage']);
                }


            } catch (Exception $e) {

                return view('500')->with(['error' => $e->getMessage()]);
            }
        }
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

    public function emptySession($search_id) {

        $search_file = $destinationPath=public_path()."/logs/searches/hotels/" . $search_id . '.json';
        $block_file = $destinationPath=public_path()."/logs/searches/hotels/" . $search_id . '_block.json';
        $block_file_request = $destinationPath=public_path()."/logs/searches/hotels/" . $search_id . '_block_request.json';
        File::delete($search_file, $block_file, $block_file_request);
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

    public function searchHotelsRaw(Request $request) {

        $input = $request->query();

        if (isset($input['ishalal']) && $input['ishalal'] == 1) {
            Session::put('active_tab', 'halal');
        } else {
            Session::put('active_tab', 'hotels');
        }


        $startdate = $input['departdate'];
        $returndate = $input['returndate'];

        if (isset($_COOKIE['th_country']) && $_COOKIE['th_country'] != '') {

            $location = json_decode($this->getCookie('th_country'));
        } else {

            $location = \Location::get($this->end_user_ip);
        }

        $country = $location->countryCode;

        $countryInfo = Currencies::where('code', $location->countryCode)->first();

        $currencyCode = $location->countryCode;
        $ourCurrency = Config::get('ourcurrency');

        if (in_array($countryInfo['currency_code'], $ourCurrency)) {
            $currency = $countryInfo['currency_code'];
        } else {
            $currency = 'USD';
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

        if (isset($input['Latitude']) && !empty($input['Latitude'])) {

                $postData = [
                    "CheckInDate" => $input['CheckInDate'],
                    "NoOfNights" => $input['NoOfNights'],
                    "CountryCode" => $input['countryCode'],
                    "CityId" => $input['city_id'],
                    "ResultCount" => 500,
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
                "ResultCount" => 500,
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


        if (isset($hotels_list['HotelSearchResult']) && isset($hotels_list['HotelSearchResult']['ResponseStatus']) && $hotels_list['HotelSearchResult']['ResponseStatus'] == 1) {

            return response()->json(array(
                        'success' => true,
                        'hotels' => $hotels_list['HotelSearchResult']['HotelResults'],
            ));
        } else {


            return response()->json(array(
                        'success' => false,
                        'hotels' => $hotels_list['HotelSearchResult']['Error']['ErrorMessage'],
            ));
        }
    }

    public function sendHotelsEmail(Request $request) {

        $search_id = $request->search_id;
        $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);
        $searchData = $search_contents['request'];
        $hotels = array();
        $selected_hotels = $request->hotel;

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
        Mail::to($request->email)->send(new HotelsEmail($searchData, $hotels, $agent));

        return response()->json(array(
                    'message' => "Email sent",
                    'success' => true
        ));
    }

    public function saveHotelFormData(Request $request){

        $data = $request->all();

        $fileName = $data['search_id'].'_payme_form_' . $data['file_name'];

        $destinationPath=public_path()."/logs/searches/hotels/" . $fileName . '.json';

        //login user
        if (file_exists($destinationPath)){

          $ilsPay = json_decode($this->readSearchData($fileName.'.json'), true);///Session::get('BookRoomDetails');
          $ilsPayDetails = $ilsPay['request'];
          $ilsPayDetails['partAmountILS'] = isset($data['partAmountILS']) ? $data['partAmountILS'] : 0;
          $ilsPayDetails['paidAmtILS'] = isset($data['paidAmtILS']) ? $data['paidAmtILS'] : 0;
          $ilsPayDetails['walletDebit'] = isset($data['walletDebit']) ? $data['walletDebit'] : 0;
          $ilsPayDetails['walletPay'] = isset($data['walletPay']) ? $data['walletPay'] : 0;
          $ilsPayDetails['paymentMode'] = isset($data['paymentMode']) ? $data['paymentMode'] : 0;

          $fileContents = json_encode(array('request' => $ilsPayDetails));
          $this->saveSearchData($fileName . '.json', $fileContents);

          $ip = $this->end_user_ip;
          $this->saveIPFrom($data['file_name'] . '_'. $ip .'.json', json_encode($data));

        }else{

            $ip = $this->end_user_ip;
            $this->saveIPFrom($data['file_name'] . '_'. $ip .'.json', json_encode($data));

            $fileContents = json_encode(array('request' => $data));
            $this->saveSearchData($fileName . '.json', $fileContents);
        }

        return response()->json(array(
                    'fileName' => $fileName,
                    'success' => true
        ));

    }

    public function getFormData(Request $request){

        $ip = $this->end_user_ip;

        $destinationPath=public_path()."/logs/ip-searches/f_h_". $ip .'.json';
        // $fileName = $data['file_name'] . '_'. $ip .'.json';
        if (file_exists($destinationPath)){
            $data = json_decode($this->getIPFrom($destinationPath), true);
            return response()->json(array(
                    'data' => $data,
                    'success' => true
            ));
        } else {
            return response()->json(array(
                    'data' => '',
                    'success' => false
            ));
        }
    }

    public function paymentFailedEmail(Request $request) {

        $paymentData = array();
        $postData = $request->all();
        $paymentData['name'] = $postData['name'];
        $paymentData['amount'] = $postData['amount'];
        $paymentData['currency'] = $postData['currency'];
        $paymentData['IP'] = $this->end_user_ip;

        $location = \Location::get($this->end_user_ip);
        $paymentData['countryCode'] = $location->countryCode;
        $paymentData['countryName'] = $location->countryName;

        Mail::to(env('NO_RESULT_EMAIL'))->send(new PaymentFailed($paymentData));
    }

    public function validateDate($date, $format = 'dd/mm/Y') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function saveSearchData($file, $content) {
        $destinationPath=public_path()."/logs/searches/hotels/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function readSearchData($file) {
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

    public function saveIPFrom($file, $content) {
        $destinationPath=public_path()."/logs/ip-searches/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function getIPFrom($file) {
        return File::get($file);
    }
}

?>