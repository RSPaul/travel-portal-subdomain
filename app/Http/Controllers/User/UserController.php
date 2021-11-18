<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Reponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use DB;
use Mail;
use App\Models\User;
use App\Models\Activities;
use App\Models\Packages;
use App\Models\Cruises;
use App\Models\Cabs;
use App\Models\Bookings;
use App\Models\Payments;
use App\Models\FlightPayments;
use App\Models\FlightBookings;
use App\Models\AffiliateUsers;
use App\Models\Currencies;
use App\Models\StaticDataHotels;
use App\Services\TBAAPI;
use Stripe\Stripe;
use App\Mail\AgentRegisterEmail;
use App\Models\Lottery;
use App\Models\LotteryUsers;
use App\Mail\NewUserRegister;
use PDF;
use Config;

use App\Models\TransferCities;
use Currency;

class UserController extends Controller
{
    public $end_user_ip;

    public function __construct()
    {
        //$this->middleware(['auth', 'isAdmin'], array('except' => array('login')));
    	$this->middleware(['auth', 'isUser'], array('except' => array('changePassword', 'checkEMail', 'login', 'registerAgent', 'fbLogin', 'gLogin', 'downloadInvoice', 'createUserLogin')));

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? \Request::ip();
        if ($ip == '127.0.0.1') {

            $ip = '132.154.175.244';//'185.191.207.36'; //'93.173.228.94';

        }
        $this->end_user_ip = $ip;

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? \Request::ip();
        if ($ip == '127.0.0.1') {
            $ip = '103.40.200.110';
        }
        // $this->end_user_ip = $ip;


        // //if (!Session::has('CurrencyCode')) {
        //     $location = \Location::get($this->end_user_ip);
        //     //get user currency
        //     $countryInfo = Currencies::where('code', $location->countryCode)->first();
        //     Session::put('CurrencyCode', $countryInfo['currency_code']);

        //if (!Session::has('CurrencyCode')) {
            $location = \Location::get($this->end_user_ip);
            //get user currency
            if($location){
              $countryInfo = Currencies::where('code', $location->countryCode)->first();
            }else{
                $countryInfo = Currencies::where('code',"US")->first();
            }
            Session::put('CurrencyCode', $countryInfo['currency_code']);

        //}

        // $lottery_Limit = env('LOTTERY_ELIGIBILITY');
        // $myCurrency = Session::get('CurrencyCode');
        // $lAmount= Currency::convert('USD', $myCurrency, $lottery_Limit);
        // $LotteryLimit = round($lAmount['convertedAmount']);
        // Session::put('lotteryLimit', $LotteryLimit);

    }

    public function login(Request $request) {
        if(Auth::user()) {
            return redirect(Auth::user()->role . '/profile');
        }
        if ($request->isMethod('post')) {
            $input = $request->all();
            $user = User::where(['email' => $input['email']])->first();
            $remember = (isset($input['remember']) && $input['remember'] == 'on') ? true : false;
            if (empty($user)) {
                Session::flash('error', 'Invalid login credentials.');
                return view('auth.login')->with('error', 'Invalid login credentials.');
            }
            if (Hash::check($input['password'], $user->password)) {
                Auth::login($user, $remember);
                if (Auth::user()->role == "agent") {
                    $ourPartners = Config::get('ourpartners');
                    if (in_array(Auth::user()->email, $ourPartners)) {
                        $partnerName = strtolower(str_replace(' ', '-', Auth::user()->name));
                        return redirect('/agent/' . $partnerName);
                    } else {
                        return redirect('/agent/dashboard');
                    }
                 } else {
                    return redirect('/user/bookings');
                }
            } else {
                Session::flash('error', 'Invalid login credentials.');
                return view('auth.login')->with('error', 'Invalid login credentials.');
            }
        }
        return view('auth.login');
    }

    public function createUserLogin(Request $request) {
        try {
            if(Auth::user()) {
                return json_encode(array(
                    'success' => true,
                    'message' => 'logged in'
                ));
            }
            $request_data = $request->all();
            $name = $request_data['fname'] . ' ' . $request_data['lname'];
            $customername = $name;
            $password = $this->generateRandomString();//$request_data['password'];

            $user = User::where(['email' => $request_data['email']])->first();
            if(isset($user) && $user['email'] !='') {
                Auth::login($user);
            } else {

                //$password = $request_data['password'];
                $user = User::create(['name' => $name, 'email' => $request_data['email'], 'country' => '', 'state' => '', 'city' => '', 'pin' => '', 'role' => 'user', 'password' => Hash::make($password), 'password_changed' => 0]);
                Mail::to($request_data['email'])->send(new NewUserRegister($user, $password));
                Auth::login($user);
            }

            $output = array(
                'success' => true,
                'message' => 'logged in'
            );

            

        } catch (Exception $e) {

            $output = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }

        echo json_encode($output);
    }

    public function checkEMail(Request $request) {
        $check = User::where('email', $request->email)->first();


        if (isset($check) && isset($check['id'])) {
            $hasTicket=false;
            $lottery = Lottery::where(['lotteryStatus' => 'active'])->first();
            if(isset($lottery)) {
                $hasLottery = LotteryUsers::where(['lotteryID' => $lottery['lotteryID'], 'userID' => $check['id']])->first();
                if ($hasLottery) {
                    $hasTicket=true;
                }
            } else {
                $hasTicket=false;
            }

            $output = array(
                'success' => false,
                'hasTicket'=>$hasTicket
            );
            echo "";//json_encode($output);
        } else {
            $output = array(
                'success' => true
            );

            echo json_encode($output);
        }
    }

    public function changePassword(Request $request) {
        $user = User::find(Auth::user()->id);
        if ($request->isMethod('post')) {
            $request_data = $request->all();

            //check current password
            $current_password = $request_data['current_password'];
            if (Hash::check($current_password, $user->password)) {
                //check password and confirm password
                if ($request_data['password'] == $request_data['confirm_password']) {

                    User::where('id', Auth::user()->id)
                            ->update(['password' => Hash::make($request_data['password']), 'password_changed' => 1]);

                    return redirect('/user/profile');
                } else {
                    return view('user.change-password')->with(['message' => 'New password and confirm password should be same.', 'password_changed' => $user['password_changed']]);
                }
            } else {
                return view('user.change-password')->with(['message' => 'Your current password is not correct.', 'password_changed' => $user['password_changed']]);
            }
        }
        return view('user.change-password')->with(['message' => '', 'password_changed' => $user['password_changed']]);
    }

    public function registerAgent(Request $request) {
        if(Auth::user()) {
            return redirect(Auth::user()->role . '/profile');
        }

        if ($request->isMethod('post')) {
            $request_data = $request->all();

            //echo "<pre>";print_r($request_data);echo "</pre>";die;

            try {
                $name = $request_data['first_name'] . ' ' . $request_data['last_name'];
                $customername = $name;
                $password = $this->generateRandomString();//$request_data['password'];
                $password = $request_data['password'];
                $user = User::create(['name' => $name, 'email' => $request_data['email'], 'country' => $request_data['country'], 'state' => $request_data['state'], 'city' => $request_data['city'], 'pin' => $request_data['pin'], 'role' => 'agent', 'password' => Hash::make($password), 'password_changed' => 0]);

                $agent = array();
                $agent['user_id'] = $user['id'];

                $agent['bussiness_phone'] = $request_data['bussiness_phone'];
                $agent['years_in_business'] = $request_data['years_in_business'];
                $agent['monthly_deals'] = $request_data['monthly_deals'];
                $agent['specific_destinations'] = $request_data['specific_destinations'];
                $agent['services'] = isset($request_data['services']) ? implode(',', $request_data['services']) : '';
                $agent['other_services'] = $request_data['other_services'];
                $agent['comments'] = $request_data['comments'];

                $agent['company_name'] = $request_data['company_name'];
                $agent['company_reg_num'] = isset($request_data['company_reg_num']) ? $request_data['company_reg_num'] : '';
                $agent['company_emp'] = $request_data['company_emp'];
                $agent['company_yr_rev'] = $request_data['company_yr_rev'];
                $agent['company_loc'] = $request_data['company_loc'];
                $agent['company_hear'] = $request_data['company_hear'];

                $agent['referal_code'] = $this->generateRandomString();
                $agent['commission'] = 0;
                AffiliateUsers::create($agent);


                if ($request->hasFile('company_cert')) {
                    if ($request->file('company_cert')->isValid()) {
                        $extension = $request->company_cert->extension();

                        $name = $request->company_cert->getClientOriginalName();
                        ;
                        try {

                            $image_name_id_comp = 'company_cert_' . time() . '.' . $request->company_cert->getClientOriginalExtension();
                            $destinationPath = public_path('uploads/id_documents');
                            $request->company_cert->move($destinationPath, $image_name_id_comp);
                            AffiliateUsers::where('user_id', $user['id'])
                                    ->update(['company_cert' => $image_name_id_comp]);
                        } catch (Exception $e) {
                            Session::flash('error', $e->getMessage());
                        }
                    }
                }


                if ($request->hasFile('id')) {
                    if ($request->file('id')->isValid()) {
                        $extension = $request->id->extension();

                        $name = $request->id->getClientOriginalName();
                        ;
                        try {

                            $image_name_id = 'id_' . time() . '.' . $request->id->getClientOriginalExtension();
                            $destinationPath = public_path('uploads/id_documents');
                            $request->id->move($destinationPath, $image_name_id);
                            User::where('id', $user['id'])
                                    ->update(['id_proof' => $image_name_id]);
                        } catch (Exception $e) {
                            Session::flash('error', $e->getMessage());
                        }
                    }
                }

                if ($request->hasFile('picture')) {
                    if ($request->file('picture')->isValid()) {
                        $extension = $request->picture->extension();

                        $name = $request->picture->getClientOriginalName();
                        ;
                        try {

                            $profile_picture = 'picture_' . time() . '.' . $request->picture->getClientOriginalExtension();
                            $destinationPath = public_path('uploads/profiles');
                            $request->picture->move($destinationPath, $profile_picture);
                            User::where('id', $user['id'])
                                    ->update(['picture' => $profile_picture]);
                        } catch (Exception $e) {
                            Session::flash('error', $e->getMessage());
                        }
                    }
                }

                Session::flash('success', 'Congratulations '.$customername.', Your application has been received and someone from our Customer Success team will reach out you with further information');
                $file_path = public_path('AffiliateProgramAgreement.pdf');
                $link = env('APP_URL') . '/login';
                try {
                    Mail::to($request_data['email'])->send(new AgentRegisterEmail($user, $password, $link, $file_path));
                } catch (Exception $e) {
                    Session::flash('error', $e->getMessage());
                }
            } catch (Exception $e) {

                Session::flash('error', $e->getMessage());
            }
        }

        $location = \Location::get($this->end_user_ip);
        $countryName = $location->countryName;
        $countryCode = $location->countryCode;

        return view('auth.register-agent')->with(['countryName' => $countryName, 'countryCode' => $countryCode]);
    }

    public function profile(Request $request) {
        $user = User::find(Auth::user()->id);
        $cities = Currencies::distinct()->get(array('id', 'name', 'code', 'dial_code', 'currency_code'));

        $country_name = array('code' => '', 'name' => '');
        if ($user->country != '') {
            $country_name = Currencies::where('code', $user->country)->first();
        }

        $query = $request->query();
        // print_r($query);die();
        if(isset($query['email']) && $query['email'] == 'true') {
            $showEmailMessage = true;
        } else {
            $showEmailMessage = false;
        }
        //if agent
        $agent = false;//AffiliateUsers::where('user_id', Auth::user()->id)->first();
        if ($request->isMethod('post')) {
            //check if email already exists
            $email = User::where('email', $request->email)
                    ->first();

            if(isset($email) && $email->id != Auth::user()->id) {
                Session::flash('error', 'This email already used for another account, please use different email.');
            } else {
                User::where('id', Auth::user()->id)
                    ->update(['name' => $request->name, 'country' => $request->country, 'email' => $request->email]);
            }

            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    //die('inside validat');

                    $extension = $request->image->extension();

                    $name = $request->image->getClientOriginalName();
                    ;
                    try {

                        //$file_name = $name;
                        $image_name = time() . '.' . $request->image->getClientOriginalExtension();
                        $destinationPath = public_path('uploads/profiles');
                        $request->image->move($destinationPath, $image_name);
                        // $data['product_image'] = $image_name;
                        // Storage::disk('profiles')->put($file_name, $request->image);
                        User::where('id', Auth::user()->id)
                                ->update(['picture' => $image_name]);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                        die();
                    }
                }
            }
            Session::flash('success', 'Profile updated.');
            return redirect('/'. Auth::user()->role .'/profile');
        }

        $bookings = array();
        if ($agent) {
            $hotel_bookings = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                    ->where('bookings.type', 'hotel')
                    ->get();

            $flight_bookings = FlightPayments::where('agent_id', Auth::user()->id)
                    ->join('flight_bookings', 'flight_payments.booking_id', '=', 'flight_bookings.id')
                    ->select(DB::raw('flight_bookings.flight_booking_status as booking_status, flight_bookings.invoice_number as c_number, flight_bookings.request_data as r_data, flight_payments.*'))
                    ->get();

            $cab_bookings = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                    ->where('bookings.type', 'cab')
                    ->get();

            $act_bookings = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                    ->where('bookings.type', 'activity')
                    ->get();

            array_push($bookings, array('hotels' => $hotel_bookings, 'flights' => $flight_bookings, 'cabs' => $cab_bookings, 'activities' => $act_bookings));
        }
        // // $r = json_decode($cab_bookings[0]['r_data'],true);
        // echo "<pre>";
        // print_r($user->name); die();
        return view('user.profile')->with(['user' => $user, 'agent' => $agent, 'bookings' => $bookings, 'cities' => $cities, 'country_name' => $country_name, 'showEmailMessage' => $showEmailMessage]);
    }

    public function viewBooking(Request $request) {

        $hotel_data = array();
        if ($request->type == 'hotel' || $request->type == 'cab' || $request->type == 'activity') {
            $booking = Bookings::where(['id' => $request->id, 'user_id' => Auth::user()->id])->first();

            if (isset($booking)) {
                $booking['request_data'] = json_decode($booking['request_data'], true);
                $payment = Payments::where(['booking_id' => $booking['id'], 'user_id' => Auth::user()->id])->first();

                if ($request->type == 'hotel') {
                    $hotel_data = StaticDataHotels::where('hotel_code', $booking['request_data']['hotelCode'])->first();

                    if (isset($hotel_data)) {
                        if (isset($hotel_data['hotel_images'])) {
                            $hotel_data['hotel_images'] = json_decode($hotel_data['hotel_images'], true);
                        }
                        if (isset($hotel_data['hotel_address'])) {
                            $hotel_data['hotel_address'] = json_decode($hotel_data['hotel_address'], true);
                        }
                        if (isset($hotel_data['hotel_address'])) {
                            $hotel_data['hotel_location'] = json_decode($hotel_data['hotel_location'], true);
                        }
                    }
                } else {
                    // echo $booking['request_data']['cancellation_policy'];
                    // print_r(json_decode($booking['request_data']['cancellation_policy'], true));
                    // die();
                    // $booking['request_data']['cancellation_policy'] = json_decode($booking['request_data']['cancellation_policy'], true);
                }
            } else {
                return redirect('/not-found');
            }
        } else if ($request->type == 'flight') {

            $booking = FlightBookings::where(['id' => $request->id, 'user_id' => Auth::user()->id])->first();

            if (isset($booking)) {
                $booking['request_data'] = json_decode($booking['request_data'], true);
                $payment = FlightPayments::where(['booking_id' => $booking['id'], 'user_id' => Auth::user()->id])->first();
            } else {
                return redirect('/not-found');
            }
        }

        if($request->type == 'flight-hotel') {

            $f_booking = FlightBookings::where(['id' => $request->message, 'user_id' => Auth::user()->id])->first();
            if (isset($f_booking)) {
                $f_booking['request_data'] = json_decode($f_booking['request_data'], true);
                $f_payment = FlightPayments::where(['booking_id' => $f_booking['id'], 'user_id' => Auth::user()->id])->first();
            }

            $h_booking = Bookings::where(['id' => $request->id, 'user_id' => Auth::user()->id])->first();

            if (isset($h_booking)) {
                $h_booking['request_data'] = json_decode($h_booking['request_data'], true);
                $h_payment = Payments::where(['booking_id' => $h_booking['id'], 'user_id' => Auth::user()->id])->first();


                $hotel_data = StaticDataHotels::where('hotel_code', $h_booking['request_data']['hotelCode'])->first();

                if (isset($hotel_data)) {
                    if (isset($hotel_data['hotel_images'])) {
                        $hotel_data['hotel_images'] = json_decode($hotel_data['hotel_images'], true);
                    }
                    if (isset($hotel_data['hotel_address'])) {
                        $hotel_data['hotel_address'] = json_decode($hotel_data['hotel_address'], true);
                    }
                    if (isset($hotel_data['hotel_address'])) {
                        $hotel_data['hotel_location'] = json_decode($hotel_data['hotel_location'], true);
                    }
                }
            }
            if(isset($f_payment)){

            }else{
                $f_payment = array();
            }

            return view('thank-you-fh')->with(['f_booking' => $f_booking, 'f_payment' => $f_payment, 'hotel_data' => $hotel_data, 'h_booking' => $h_booking, 'h_payment' => $h_payment, 'message' => $request->message]);

        } else {

            if(isset($payment)){

            }else{
                $payment = array();
            }

            return view('thank-you')->with(['booking' => $booking, 'payment' => $payment, 'hotel_data' => $hotel_data, 'message' => $request->message, 'booking_type' => $request->type]);
        }

        // $booking['request_data'] = json_decode($booking['request_data']);
        // echo "<pre>";
        // print_r($booking);
        // print_r($payment);
        // //  print_r($hotel_data);
        // die();
        
    }

    public function bookings(Request $request) {

        $hotel_bookings = Bookings::where(['user_id' => Auth::user()->id, 'type' => 'hotel'])->orderBy('created_at', 'DESC')->get();
        $cab_bookings = Bookings::where(['user_id' => Auth::user()->id, 'type' => 'cab'])->orderBy('created_at', 'DESC')->get();
        $activity_bookings = Bookings::where(['user_id' => Auth::user()->id, 'type' => 'activity'])->orderBy('created_at', 'DESC')->get();
        $flightbookings = FlightBookings::where(['user_id' => Auth::user()->id])->orderBy('created_at', 'DESC')->get();
        foreach ($hotel_bookings as $key => $booking) {
            $booking->request_data = json_decode($booking->request_data, true);
            if ($booking->booking_id == 'activity') {
                $booking->activity_data = Activities::where('id', $booking->token_id)->first();
            } else if ($booking->booking_id == 'cruise') {
                $booking->activity_data = Cruises::where('id', $booking->token_id)->first();
            } else if ($booking->booking_id == 'package') {
                $booking->activity_data = Packages::where('id', $booking->token_id)->first();
                // }else if($booking->booking_id == 'cab'){
                //    $booking->activity_data = Cabs::where('id', $booking->token_id)->first();
                // }
            } else {

            }
        }


        foreach ($cab_bookings as $key => $cbooking) {
            $cbooking->request_data = json_decode($cbooking->request_data, true);
            $cbooking->cancellation_policy = json_decode($cbooking->cancellation_policy, true);

            $payment = Payments::where(['booking_id' => $cbooking->id, 'user_id' => Auth::user()->id])->first();

            $cbooking['total_price'] = $payment['price'];

        }

        foreach ($activity_bookings as $key => $abooking) {
            $abooking->request_data = json_decode($abooking->request_data, true);
            //$cbooking->cancellation_policy = json_decode($cbooking->cancellation_policy, true);
        }


        foreach ($flightbookings as $key => $fbooking) {
            $fbooking->request_data = json_decode($fbooking->request_data, true);
        }
        //echo "<pre>";print_r($flightbookings);
        return view('user.bookings')->with(['hotel_bookings' => $hotel_bookings, 'flight_bookings' => $flightbookings, 'cab_bookings' => $cab_bookings, 'activity_bookings' => $activity_bookings]);
    }

    function getAirport() {

        $location = \Location::get($this->end_user_ip);

        $myCity = TransferCities::where('country_code', $location->countryCode)->where('city_name', 'like', '%' . $location->cityName . '%')->first();
        if (!$myCity) {
            $myCity = TransferCities::where('country_code', $location->countryCode)->first();
        }

        $user_city = ['countryName' => $location->countryName, 'cityName' => $myCity['city_name'], 'cityCode' => $myCity['city_code'], 'countryCode' => $myCity['country_code']];


        Session::put('user_city', $user_city);
    }


    public function fbLogin(Request $request){

        if($request->isMethod('post')) {

            $user_data = $request->all();
            //first check if user already exists
            $check  = User::where('social_id', $user_data['data']['id'])->first();

            if((isset($user_data['data']['email']))) {
                $email = $user_data['data']['email'];
                $force_email_change = false;
                //check email exist
                $duplicate  = User::where('email', $user_data['data']['email'])->first();
                if(isset($check) && !empty($check) && ($duplicate['social_id'] == '' || $duplicate['social_id'] == null)) {
                    $output = array(
                        'success' => false,
                        'force_email_change'=>$force_email_change,
                        'message' => 'Account with email ' . $user_data['data']['email'] . ' already exists.'
                    );

                    return response()->json($output);
                }
            } else {
                $email = $user_data['data']['id'];
                $force_email_change = true;
            }

            if(isset($check) && !empty($check)) {

                $user  = User::where('social_id', $user_data['data']['id'])->first();

                User::where('social_id', $user_data['data']['id'])
                                ->update(['name' => $user_data['data']['name'],
                                     'social_id' => $user_data['data']['id'],
                                     'password' => rand(),
                                     'role' => 'user',
                                     'email' => $email]);
                Auth::login($user);
                $output = array(
                    'success' => true,
                    'force_email_change'=>$force_email_change,
                    'message' => ''
                );

                return response()->json($output);

            } else {

                //create new user
                $user = User::create(['name' => $user_data['data']['name'],
                                     'social_id' => $user_data['data']['id'],
                                     'password' => Hash::make(rand()),
                                     'role' => 'user',
                                     'password_changed' => 1,
                                     'status' => '1',
                                     'email' => $email]);
                Auth::login($user);
                $output = array(
                    'success' => true,
                    'force_email_change'=>$force_email_change,
                    'message' => ''
                );

                return response()->json($output);
            }


        }
    }

    public function gLogin(Request $request){

        if($request->isMethod('post')) {

            $user_data = $request->all();
            
            //first check if user already exists
            $check  = User::where('social_id', $user_data['data']['id'])->first();

            if((isset($user_data['data']['email']))) {
                $email = $user_data['data']['email'];
                $force_email_change = false;
                //check email exist
                $duplicate  = User::where('email', $user_data['data']['email'])->first();
                if(isset($check) && !empty($check) && ($duplicate['social_id'] == '' || $duplicate['social_id'] == null)) {
                    $output = array(
                        'success' => false,
                        'force_email_change'=>$force_email_change,
                        'message' => 'Account with email ' . $user_data['data']['email'] . ' already exists.'
                    );

                    return response()->json($output);
                }
            } else {
                $email = $user_data['data']['id'];
                $force_email_change = true;
            }

            if(isset($check) && !empty($check)) {

                $user  = User::where('social_id', $user_data['data']['id'])->first();

                User::where('social_id', $user_data['data']['id'])
                                ->update(['name' => $user_data['data']['name'],
                                     'social_id' => $user_data['data']['id'],
                                     'password' => rand(),
                                     'role' => 'user',
                                     'picture' => $user_data['data']['picture'],
                                     'email' => $email]);
                Auth::login($user);
                $output = array(
                    'success' => true,
                    'force_email_change'=>$force_email_change,
                    'message' => ''
                );

                return response()->json($output);

            } else {

                //create new user
                $user = User::create(['name' => $user_data['data']['name'],
                                     'social_id' => $user_data['data']['id'],
                                     'password' => Hash::make(rand()),
                                     'role' => 'user',
                                     'password_changed' => 1,
                                     'status' => '1',
                                     'picture' => $user_data['data']['picture'],
                                     'email' => $email]);
                Auth::login($user);
                $output = array(
                    'success' => true,
                    'force_email_change'=>$force_email_change,
                    'message' => ''
                );

                return response()->json($output);
            }


        }
    }

    public function downloadInvoice(Request $request) {

        if($request->type == 'flights'){
            if(Auth::check()) {
              $booking = FlightBookings::where(['id' => $request->id, 'user_id' => Auth::user()->id])->first();
            } else {
                $booking = FlightBookings::where(['id' => $request->id])->first();
            }
            $booking['request_data'] = json_decode($booking['request_data'], true);

        }else{

            if(Auth::check()) {
                $booking = Bookings::where(['id' => $request->id, 'user_id' => Auth::user()->id])->first();
            } else {
                $booking = Bookings::where(['id' => $request->id])->first();
            }
            $booking['request_data'] = json_decode($booking['request_data'], true);
           
            if($request->type == 'cabs'){

                $payment = Payments::where(['booking_id' => $booking->id, 'user_id' => Auth::user()->id])->first();

                $booking['total_price'] = $payment['price'];
            }
        }



        $pdf = PDF::loadView('emails.invoice.'.$request->type.'-booking', compact('booking'));

        $pdf->save(public_path('invoices/'.$request->type.'/invoice-' . $booking->booking_id . '.pdf'));

        $file = public_path('invoices/'.$request->type.'/invoice-' . $booking->booking_id . '.pdf');

        $headers = array('Content-Type: application/pdf',);

        return response()->download($file, "invoice-" . $booking->booking_id . ".pdf", $headers);


    }

    public function downloadEPDF(Request $request) {

        $file = public_path('e-tickets/' . $request->type . '/e-Ticket-' . $request->booking_id . '.pdf');
        $headers = array('Content-Type: application/pdf',);
        // return Response::download($file, "e-Ticket-". $booking_id .".pdf",$headers);
        return response()->download($file, "e-Ticket-" . $request->booking_id . ".pdf", $headers);
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

    public function wallet() {

        $user = Auth::user();
        $trans = $user->transactions()->orderBy('created_at', 'desc')->get();

        return view('wallet', ['trans' => $trans]);
    }

}
