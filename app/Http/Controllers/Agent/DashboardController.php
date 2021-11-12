<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Reponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Mail;
use DB;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Bookings;
use App\Models\Payments;
use App\Models\FlightPayments;
use App\Models\FlightBookings;
use App\Models\AffiliateUsers;
use App\Models\Currencies;
use App\Models\Posts;
use App\Models\PostLikes;
use App\Models\PostComments;
use App\Models\BankDetails;
use App\Models\WebVideos;
use App\Models\NotificationAgents;
use App\Models\WalletPaymentRequests;

class DashboardController extends Controller
{

	public $end_user_ip;

    public function __construct()
    {
        $this->middleware(['auth', 'isAgent'], array('except' => array('viewPost')));

    }

    public function dashboard(Request $request) {

    	$user = User::find(Auth::user()->id);
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();

    	$dateS = Carbon::now()->startOfMonth()->subMonth(3);
		$dateE = Carbon::now()->startOfMonth(); 


    	$bookings = Payments::where('agent_id', Auth::user()->id)->count();
    	$flight_bookings = FlightPayments::where('agent_id', Auth::user()->id)->count();

    	$bookings_paid = Payments::where('agent_id', Auth::user()->id)->sum('price_convered');
    	$flight_bookings_paid = FlightPayments::where('agent_id', Auth::user()->id)->sum('price_convered');

    	$bookings_paid_earning = Payments::where('agent_id', Auth::user()->id)->sum('commission');
    	$flight_bookings_paid_earning = FlightPayments::where('agent_id', Auth::user()->id)->sum('commission');

    	$customers = DB::table('payments')->where('agent_id', Auth::user()->id)->distinct('user_id')->count('user_id');
    	$flight_customers = DB::table('flight_payments')->where('agent_id', Auth::user()->id)->distinct('user_id')->count('user_id');

	 	$bookings_paid_earning_month = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$dateS->format('Y-m-d H:i:s'),$dateE->format('Y-m-d H:i:s')])
    	 								->get();

    	$flight_bookings_paid_earning_month = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$dateS->format('Y-m-d H:i:s'),$dateE->format('Y-m-d H:i:s')])
    	 								->get();

    	$total_sales = $bookings_paid + $flight_bookings_paid;
    	$total_customers = $customers + $flight_customers;
    	$total_earnings = $bookings_paid_earning + $flight_bookings_paid_earning;
    	$total_bookings = $bookings + $flight_bookings;
    	
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

    	$b_p_m = 0;
    	$b_p_f_m = 0;
    	foreach ($bookings_paid_earning_month as $key => $value) {
    		if($value['agent_id'] == Auth::user()->id) {
    			$b_p_m = $b_p_m + $value['commission'];
    		}
		}		



		foreach ($flight_bookings_paid_earning_month as $key => $value) {
			if($value['agent_id'] == Auth::user()->id) {
				$b_p_f_m = $b_p_f_m + $value['commission'];
			}
		}

		$total_earnings_month = $b_p_m + $b_p_f_m;

    	$data = array('total_sales' => $total_sales,
    					'total_customers' => $total_customers,
    					'total_bookings' => $total_bookings,
    					'total_earnings' => $total_earnings,
    					'total_earnings_month' => $total_earnings_month,
    					'hotel_bookings' => $hotel_bookings,
    					'flight_bookings' => $flight_bookings,
    					'cab_bookings' => $cab_bookings,
    					'act_bookings' => $act_bookings);

    	return view('agent.dashboard')->with(['user' => $user, 'notifications' => $notifications,  'data' => $data]);
    }

    public function htmlWidget(Request $request) {

    	$user = User::find(Auth::user()->id);
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();
        $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();

    	return view('agent.widget')->with(['user' => $user, 'notifications' => $notifications, 'agent' => $agent]);
    }

    public function settings(Request $request) {

    	$user = User::find(Auth::user()->id);    
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();
    	$agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();  

        if($agent['services'] != null && $agent['services'] != '') {
            $agent['services']  = json_decode($agent['services'], true);
        } else {
            $agent['services'] = array();
        }
        
        
    	return view('agent.settings')->with(['user' => $user, 'notifications' => $notifications, 'agent' => $agent]);
    }

    public function chartsData(Request $request) {

        $startDate = Carbon::now(); //returns current day
        $week_1_day_1 = $startDate->firstOfMonth()->format('Y-m-d H:i:s');  
        $week_1_day_7 = $startDate->addDays(7)->format('Y-m-d H:i:s');

        $week_2_day_1 = $startDate->addDays(8)->format('Y-m-d H:i:s');  
        $week_2_day_7 = $startDate->addDays(14)->format('Y-m-d H:i:s');

        $week_3_day_1 = $startDate->addDays(15)->format('Y-m-d H:i:s');  
        $week_3_day_7 = $startDate->addDays(21)->format('Y-m-d H:i:s');

        $week_4_day_1 = $startDate->addDays(22)->format('Y-m-d H:i:s');  
        $week_4_day_7 = $startDate->addDays(28)->format('Y-m-d H:i:s');

        $week_1_bookings = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_1_day_1,$week_1_day_7])
                                        ->count();
        $week_1_bookings_flight = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_1_day_1,$week_1_day_7])
                                        ->count();

        $week_2_bookings = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_2_day_1,$week_2_day_7])
                                        ->count();
        $week_2_bookings_flight = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_2_day_1,$week_2_day_7])
                                        ->count();

        $week_3_bookings = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_3_day_1,$week_3_day_7])
                                        ->count();
        $week_3_bookings_flight = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_3_day_1,$week_3_day_7])
                                        ->count();

        $week_4_bookings = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_4_day_1,$week_4_day_7])
                                        ->count();
        $week_4_bookings_flight = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_4_day_1,$week_4_day_7])
                                        ->count();

        $week_1_profit = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_1_day_1,$week_1_day_7])
                                        ->sum('commission');
        $week_1_profit_flight = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_1_day_1,$week_1_day_7])
                                        ->sum('commission');

        $week_2_profit = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_2_day_1,$week_2_day_7])
                                        ->sum('commission');
        $week_2_profit_flight = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_2_day_1,$week_2_day_7])
                                        ->sum('commission');

        $week_3_profit = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_3_day_1,$week_3_day_7])
                                        ->sum('commission');
        $week_3_profit_flight = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_3_day_1,$week_3_day_7])
                                        ->sum('commission');

        $week_4_profit = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_4_day_1,$week_4_day_7])
                                        ->sum('commission');
        $week_4_profit_flight = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_4_day_1,$week_4_day_7])
                                        ->sum('commission');

        //get sales/earning per month
        $month = date('m');
        $year = date('Y');
        $reveneue_report = array('months' => array(), 'sales' => array(), 'earnings' => array());

        for($i = $month; $i > 1; $i--) {

            $startMonth = Carbon::now()->month($i)->day(1)->format("Y-m-d");

            $endMonth = Carbon::now()->month($i)->day(Carbon::create($year, $i)->daysInMonth)->format("Y-m-d");

            $month_bookings = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$startMonth,$endMonth])
                                        ->sum('price_convered');
            $month_bookings_flight = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_3_day_1,$week_3_day_7])
                                        ->sum('price_convered');

            $month_bookings_earnings = Payments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$startMonth,$endMonth])
                                        ->sum('commission');
            $month_bookings_earnings_flight = FlightPayments::where('agent_id', Auth::user()->id)->whereBetween('created_at',[$week_3_day_1,$week_3_day_7])
                                        ->sum('commission');

            array_push($reveneue_report['sales'], round($month_bookings +  $month_bookings_flight));
            array_push($reveneue_report['earnings'], round($month_bookings_earnings + $month_bookings_earnings_flight));

            $monthName = date('F', mktime(0, 0, 0, $i, 10));
            array_push($reveneue_report['months'], $monthName);
        }


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
                ->select(DB::raw('payments.*'))
                ->where('bookings.type', 'activity')
                ->get();

        $startMonth = Carbon::now()->month($month)->day(1)->format("Y-m-d");

        $endMonth = Carbon::now()->month($month)->day(Carbon::create($year, $month)->daysInMonth)->format("Y-m-d");

        $hotel_earnings = 0;
        $flight_earnings = 0;
        $cab_earnings = 0;
        $activity_earnings = 0;

        foreach($hotel_bookings as $v) {
            if($v['created_at'] >= $startMonth && $v['created_at'] <= $endMonth) {
                $hotel_earnings = $hotel_earnings + $v['commission'];
            }
        }


        foreach($flight_bookings as $v) {
            if($v['created_at'] >= $startMonth && $v['created_at'] <= $endMonth) {
                $flight_earnings = $flight_earnings + $v['commission'];
            }
        }



        foreach($cab_bookings as $v) {
            if($v['created_at'] >= $startMonth && $v['created_at'] <= $endMonth) {
                $cab_earnings = $cab_earnings + $v['commission'];
            }
        }


        foreach($act_bookings as $v) {
            if($v['created_at'] >= $startMonth && $v['created_at'] <= $endMonth) {
                $activity_earnings = $activity_earnings + $v['commission'];
            }
        }
        $week_1 = $week_1_bookings + $week_1_bookings_flight;
        $week_2 = $week_2_bookings + $week_2_bookings_flight;
        $week_3 = $week_3_bookings + $week_3_bookings_flight;
        $week_4 = $week_4_bookings + $week_4_bookings_flight;

        $week_1_profit = $week_1_profit + $week_1_profit_flight;
        $week_2_profit = $week_2_profit + $week_2_profit_flight;
        $week_3_profit = $week_3_profit + $week_3_profit_flight;
        $week_4_profit = $week_4_profit + $week_4_profit_flight;

        $data = array('week_orders' => array($week_1, $week_2, $week_3, $week_4), 
                      'week_profit' => array($week_1_profit, $week_2_profit, $week_3_profit, $week_4_profit),
                      'reveneue_report' => $reveneue_report,
                      'earnings_by_category' => array(round($hotel_earnings), round($flight_earnings), round($cab_earnings), round($activity_earnings)));

        return response()->json($data);
    }

    public function achievements(Request $request) {
        $user = User::find(Auth::user()->id);    
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();
        $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();    
        return view('agent.achievements')->with(['user' => $user, 'notifications' => $notifications, 'agent' => $agent]);
    }

    public function viewPost(Request $request) {

        $post = Posts::where('id', $request->id)->first();
        if(isset($post) && !empty($post)) {

            $user = User::where('id', $post['user_id'])->first();
            $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();
            $agent = AffiliateUsers::where('user_id', $post['user_id'])->first();
            $other_posts = Posts::where('user_id', $post['user_id'])->get();

            $meta_image = 'https://tripheist.com/images/logo.png';
            if($post['post_type'] == 'article_image') {
                if(strpos($post['post_media'], 'http') !== false) {
                    $meta_image = $post['post_media'];
                } else {
                    $meta_image = env('APP_URL') . '/uploads/posts/'. $post['post_media'];
                }
            }

            $likes = PostLikes::where('post_id', $post['id'])->count();
            $comments = PostComments::where('post_id', $post['id'])
                        ->join('users', 'users.id', '=', 'post_comments.user_id')
                        ->select('post_comments.comment', 'post_comments.created_at', 'users.name', 'users.picture', 'users.id as userId')
                        ->orderBy('created_at', 'DESC')
                        ->get();

            return view('agent.post-details')->with(['user' => $user, 'notifications' => $notifications,'post' => $post, 'other_posts' => $other_posts, 'meta_image' => $meta_image, 'likes' => $likes, 'comments' => $comments, 'user' => $user, 'agent' => $agent, 'post_id' => $request->id]);

        } else {
            return view('agent.error')->with(['message' => '']);
        }
    }

    public function bankDetails(Request $request) {
        
        $user = User::find(Auth::user()->id);    
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();
        $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();    
        $bank_details = BankDetails::where('user_id', Auth::user()->id)->first();    
        if(empty($bank_details)) {
            $bank_details = new BankDetails();
        }
        return view('agent.bank-details')->with(['user' => $user, 'notifications' => $notifications, 'agent' => $agent, 'bank_details' => $bank_details]);
    }

    public function withDrawPayment(Request $request) {

        $user = User::find(Auth::user()->id);  
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();  
        $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();    
        
        $hotel_bookings = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                    ->where('bookings.type', 'hotel')
                    ->where('payments.withdraw_status', '!=', 'paid')
                    ->orderBy('payments.created_at', 'DESC')
                    ->get();

        $flight_bookings = FlightPayments::where('agent_id', Auth::user()->id)
                    ->join('flight_bookings', 'flight_payments.booking_id', '=', 'flight_bookings.id')
                    ->select(DB::raw('flight_bookings.flight_booking_status as booking_status, flight_bookings.invoice_number as c_number, flight_bookings.request_data as r_data, flight_payments.*'))
                    ->where('flight_payments.withdraw_status', '!=', 'paid')
                    ->orderBy('flight_payments.created_at', 'DESC')
                    ->get();

        $cab_bookings = Payments::where('agent_id', Auth::user()->id)
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                ->where('bookings.type', 'cab')
                ->where('payments.withdraw_status', '!=', 'paid')
                ->orderBy('payments.created_at', 'DESC')
                ->get();

        $act_bookings = Payments::where('agent_id', Auth::user()->id)
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                ->where('bookings.type', 'activity')
                ->where('payments.withdraw_status', '!=', 'paid')
                ->orderBy('payments.created_at', 'DESC')
                ->get();


        $hotel_earning = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->where('bookings.type', 'hotel')
                    ->where('payments.withdraw_status', 'pending')
                    ->sum('payments.commission');

        $hotel_earning_markup = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->where('bookings.type', 'hotel')
                    ->where('payments.withdraw_status', 'pending')
                    ->sum('payments.agent_markup');

        $flight_earning = FlightPayments::where('agent_id', Auth::user()->id)
                    ->join('flight_bookings', 'flight_payments.booking_id', '=', 'flight_bookings.id')
                    ->where('flight_payments.withdraw_status', 'pending')
                    ->sum('flight_payments.commission');

        $flight_earning_markup = FlightPayments::where('agent_id', Auth::user()->id)
                    ->join('flight_bookings', 'flight_payments.booking_id', '=', 'flight_bookings.id')
                    ->where('flight_payments.withdraw_status', 'pending')
                    ->sum('flight_payments.agent_markup');

        $cab_earning = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->where('bookings.type', 'cab')
                    ->where('payments.withdraw_status', 'pending')
                    ->sum('payments.commission');

        $activity_earning = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->where('bookings.type', 'activity')
                    ->where('payments.withdraw_status', 'pending')
                    ->sum('payments.commission');
        
        $bank_details = BankDetails::where('user_id', Auth::user()->id)->first();    
        if(empty($bank_details)) {
            $bank_details = new BankDetails();
        }
        return view('agent.withdraw')->with(['user' => $user, 'notifications' => $notifications,'agent' => $agent, 'earnings_data' => array('hotel_earning' => $hotel_earning + $hotel_earning_markup, 'flight_earning' => $flight_earning + $flight_earning_markup, 'cab_earning' => $cab_earning, 'activity_earning' => $activity_earning), 'bank_details' => $bank_details, 'bookings_data' => array('hotel_bookings' => $hotel_bookings, 'flight_bookings' => $flight_bookings, 'cab_bookings' => $cab_bookings, 'act_bookings' => $act_bookings)]);

    }

    public function videos(Request $request) {
        $user = User::find(Auth::user()->id);
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();    
        $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();

        $videos = WebVideos::where('status', 'Active')->orderBy('created_at', 'DESC')->get();
        return view('agent.web-videos')->with(['user' => $user, 'agent' => $agent, 'videos' => $videos,'notifications' => $notifications]);
    }

    public function Notifications(Request $request){

        $user = User::find(Auth::user()->id); 
        $read_notifications = NotificationAgents::where('agent_id', Auth::user()->id)
                        ->update(['status' => '1']);
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();
        $all_notifications = NotificationAgents::where('agent_id', Auth::user()->id)->get();
        return view('agent.notifications')->with(['user' => $user,'notifications' => $notifications, 'all_notifications' => $all_notifications]);

    }


    public function earnings(){

        $user = User::find(Auth::user()->id);
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();

        $hotelpayments = 0;
        $flightpayments = 0;
        $cabpayments = 0;
        $activitypayments = 0;
        $hotel_bookings = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number,bookings.booking_id as bookID,payments.*'))
                    ->where('bookings.type', 'hotel')
                    ->orderBy('bookings.created_at', 'DESC')
                    ->get();

        foreach ($hotel_bookings as $key => $hvalue) {
            # code...
            $hotel_bookings[$key]['r_data'] = json_decode($hvalue['r_data'], true);
            $hotelpayments = $hotelpayments + $hvalue['commission'] + $hvalue['agent_markup'];
        }


        $flight_bookings = FlightPayments::where('agent_id', Auth::user()->id)
                    ->join('flight_bookings', 'flight_payments.booking_id', '=', 'flight_bookings.id')
                    ->select(DB::raw('flight_bookings.flight_booking_status as booking_status,flight_bookings.booking_id as bookID, flight_bookings.invoice_number as c_number, flight_bookings.request_data as r_data, flight_payments.*'))
                    ->orderBy('flight_bookings.created_at', 'DESC')
                    ->get();

        foreach ($flight_bookings as $key => $fvalue) {
            # code...
            $flight_bookings[$key]['f_data'] = json_decode($fvalue['r_data'], true);
            $flightpayments = $flightpayments + $fvalue['commission']  + $fvalue['agent_markup'];
        }  
                

        $cab_bookings = Payments::where('agent_id', Auth::user()->id)
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->select(DB::raw('bookings.hotel_booking_status as booking_status,bookings.booking_id as bookID, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                ->where('bookings.type', 'cab')
                ->orderBy('bookings.created_at', 'DESC')
                ->get();

        foreach ($cab_bookings as $key => $cvalue) {
            # code...
            $cab_bookings[$key]['c_data'] = json_decode($cvalue['r_data'], true);
            $cabpayments = $cabpayments + $cvalue['commission'];
        }


        $act_bookings = Payments::where('agent_id', Auth::user()->id)
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number,bookings.booking_id as bookID, payments.*'))
                ->where('bookings.type', 'activity')
                ->orderBy('bookings.created_at', 'DESC')
                ->get();

        foreach ($act_bookings as $key => $avalue) {
            # code...
            $act_bookings[$key]['a_data'] = json_decode($avalue['r_data'], true);
            $activitypayments = $activitypayments + $avalue['commission'];
        }        

        return view('agent.earnings')->with(['user' => $user, 'notifications' => $notifications, 'hotel_bookings' => $hotel_bookings, 'hotel_payments' => $hotelpayments,'flight_payments' => $flightpayments,'cab_payments' => $cabpayments,'activity_payments' => $activitypayments,'flight_bookings' => $flight_bookings,'cab_bookings' => $cab_bookings,'act_bookings' => $act_bookings,'type' => 'hotel']);
    }

    public function partnerDashboard(){

        $user = User::find(Auth::user()->id);
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();

        $cabpayments = 0;
        $activitypayments = 0;

                

        $cab_bookings = Payments::where('partners_commision', '!=', null)
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->select(DB::raw('bookings.hotel_booking_status as booking_status,bookings.booking_id as bookID, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                ->where('bookings.type', 'cab')
                ->orderBy('bookings.created_at', 'DESC')
                ->get();

        foreach ($cab_bookings as $key => $cvalue) {
            # code...
            $cab_bookings[$key]['c_data'] = json_decode($cvalue['r_data'], true);
            $cabpayments = $cabpayments + $cvalue['partners_commision'];
        }


        $act_bookings = Payments::where('partners_commision', '!=', null)
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number,bookings.booking_id as bookID, payments.*'))
                ->where('bookings.type', 'activity')
                ->orderBy('bookings.created_at', 'DESC')
                ->get();

        // echo "<pre>"; print_r($cab_bookings); die();
        foreach ($act_bookings as $key => $avalue) {
            # code...
            $act_bookings[$key]['a_data'] = json_decode($avalue['r_data'], true);
            $activitypayments = $activitypayments + $avalue['partners_commision'];
        }        

        $partnerName = strtolower(str_replace(' ', '-', Auth::user()->name));
        return view('agent.partner-earnings')->with(['user' => $user, 'notifications' => $notifications, 'cab_payments' => $cabpayments,'activity_payments' => $activitypayments, 'cab_bookings' => $cab_bookings,'act_bookings' => $act_bookings,'type' => 'activity', 'is_partner' => true, 'partnerName' => $partnerName]);
    }

    public function withDrawPaymentPartner(Request $request) {

        $user = User::find(Auth::user()->id);  
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();  
        $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();    

        $cab_bookings = Payments::where('partners_commision', '!=', null)
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                ->where('bookings.type', 'cab')
                ->where('payments.withdraw_status', '!=', 'paid')
                ->orderBy('payments.created_at', 'DESC')
                ->get();

        $act_bookings = Payments::where('partners_commision', '!=', null)
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                ->where('bookings.type', 'activity')
                ->where('payments.withdraw_status', '!=', 'paid')
                ->orderBy('payments.created_at', 'DESC')
                ->get();


        $cab_earning = Payments::where('partners_commision', '!=', null)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->where('bookings.type', 'cab')
                    ->where('payments.withdraw_status', 'pending')
                    ->sum('payments.partners_commision');

        $activity_earning = Payments::where('partners_commision', '!=', null)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->where('bookings.type', 'activity')
                    ->where('payments.withdraw_status', 'pending')
                    ->sum('payments.partners_commision');
        
        $bank_details = BankDetails::where('user_id', Auth::user()->id)->first();    
        if(empty($bank_details)) {
            $bank_details = new BankDetails();
        }

        $partnerName = strtolower(str_replace(' ', '-', Auth::user()->name));
        return view('agent.withdraw-partner')->with(['user' => $user, 'notifications' => $notifications,'agent' => $agent, 'earnings_data' => array('cab_earning' => $cab_earning, 'activity_earning' => $activity_earning), 'bank_details' => $bank_details, 'bookings_data' => array('cab_bookings' => $cab_bookings, 'act_bookings' => $act_bookings), 'is_partner' => true, 'partnerName' => $partnerName]);

    }


    public function wallet(Request $request) {
        $user = User::find(Auth::user()->id); 
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();
        $requests = WalletPaymentRequests::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
        return view('agent.wallet')->with(['user' => $user,'notifications' => $notifications, 'requests' => $requests]);
    }

}