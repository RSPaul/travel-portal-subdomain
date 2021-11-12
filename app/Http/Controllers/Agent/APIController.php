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
use Illuminate\Support\Facades\Hash;
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
use App\Models\PostComments;
use App\Models\PostLikes;
use App\Models\BankDetails;
use App\Models\WalletPaymentRequests;
use App\Models\Messages;
use App\Models\NotificationAgents;

use App\Mail\Agent\BankDetailsEmail;
use App\Mail\Agent\WithDrawRequest;
use App\Mail\Agent\WalletPaymentRequestEmail;
//use Currency;

class APIController extends Controller
{

	public function __construct()
    {
        $this->middleware(['auth', 'isAgent']);        
    }

    public function updateSettings(Request $request) {

    	$postData = $request->all();

    	if($postData['action'] == 'settings') {
    	
    		User::where('id', Auth::user()->id)
    			->update(['name' => $postData['name']]);

    		AffiliateUsers::where('user_id', Auth::user()->id)
    			->update(['company_name' => $postData['company_name'],
    					 'website_url' => $postData['website_url'],
    					 'years_in_business' => $postData['years_in_business']]);

    		if(isset($postData['picture']) && $postData['picture'] != '') {

			    $files = $postData['picture'];
                list($type, $files) = explode(';', $files);
                list(, $files)      = explode(',', $files);
                $file_date = base64_decode($files);
                $file_type = explode("/", $type);
                
                $file_name = time().'.' . $file_type[1];
                $path = public_path() . "/uploads/profiles/" . $file_name;
                file_put_contents($path, $file_date);

                User::where('id', Auth::user()->id)
					->update(['picture' => $file_name]);
    		}

            $returnData = array('success' => true , 'message' => 'Settings updated.');

		} else if($postData['action'] == 'information') {

			User::where('id', Auth::user()->id)
    			->update(['country' => $postData['country']]);

			AffiliateUsers::where('user_id', Auth::user()->id)
    			->update(['company_name' => $postData['company_name'],
    					 'other_services' => $postData['other_services'],
    					 'monthly_deals' => $postData['monthly_deals'],
    					 'bussiness_phone' => $postData['bussiness_phone'],
    					 'services' => json_encode($postData['services'])]);

            $returnData = array('success' => true , 'message' => 'Settings updated.');

		} else if($postData['action'] == 'password') {

            $user = User::where('id', Auth::user()->id)->first();

            $current_password = $postData['current_password'];
            if (Hash::check($current_password, $user->password)) {
                if ($postData['password'] == $postData['confirm_password']) {

                    User::where('id', Auth::user()->id)
                            ->update(['password' => Hash::make($postData['password']), 'password_changed' => 1]);

                    $returnData = array('success' => true , 'message' => 'Password changed.');

                } else {
                    $returnData = array('success' => false , 'message' => 'Password and confirm password is not same.');
                }

            } else {

                $returnData = array('success' => false , 'message' => 'Provided current passwors is incorrect.');
            }
        }

		
		return response()->json($returnData);
    }

    public function createPost(Request $request) {

    	$postData = $request->all();

    	try {
	    	if($postData['post_type'] == 'article_image') {

	    		$files = $postData['post_media_hidden'];
	            list($type, $files) = explode(';', $files);
	            list(, $files)      = explode(',', $files);
	            $file_date = base64_decode($files);
	            $file_type = explode("/", $type);
	            
	            $file_name = time().'.' . $file_type[1];
	            $path = public_path() . "/uploads/posts/" . $file_name;
	            file_put_contents($path, $file_date);

	            Posts::create(['post_type' => $postData['post_type'],
	        				  'post_content' => $postData['post_content'],
	        				  'post_media' => $file_name,
	        				  'user_id' => Auth::user()->id]);

	    	} else if($postData['post_type'] == 'article_video') {

	    		Posts::create(['post_type' => $postData['post_type'],
	        				  'post_content' => $postData['post_content'],
	        				  'post_media' => $postData['post_media_link'],
	        				  'user_id' => Auth::user()->id]);

	    	} else {

	    		Posts::create(['post_type' => $postData['post_type'],
	        				  'post_content' => $postData['post_content'],
	        				  'user_id' => Auth::user()->id]);
	    	}

	    	$returnData = array('success' => true , 'message' => 'Your post has been published.');

	    } catch(Exception $e) {

	    	$returnData = array('success' => false , 'message' => $e->getMessage());
	    }


		return response()->json($returnData);
    }

    public function postComment(Request $request) {

    	$postData = $request->all();

    	try {

    		PostComments::create(['user_id' => Auth::user()->id,
    							  'comment' => $postData['comment'],
    							  'post_id' => $postData['post_id']]);

    		$returnData = array('success' => true , 'message' => 'Your comment has been published.');

    	} catch(Exception $e) {

	    	$returnData = array('success' => false , 'message' => $e->getMessage());
	    }

	    return response()->json($returnData);

    }

    public function postLike(Request $request) {

    	$postData = $request->all();

    	try {

    		if($postData['action'] == 'like') {
   				
   				PostLikes::create(['user_id' => Auth::user()->id,
								   'post_id' => $postData['post_id']]);
   				$action = 'liked';

			} else {

				PostLikes::where(['user_id' => Auth::user()->id,
								   'post_id' => $postData['post_id']])
				                ->delete();
				$action = 'unliked';
			}

    		$returnData = array('success' => true , 'message' => 'You '. $action .' this post.');

    	} catch(Exception $e) {

	    	$returnData = array('success' => false , 'message' => $e->getMessage());
	    }

	    return response()->json($returnData);

    }

    public function saveBankDetails(Request $request) {

        $postData = $request->all();
        try {

            if(isset($postData['user_id']) && $postData['user_id'] != '') {
                $postData['verified'] = 'no';
                BankDetails::where('user_id', Auth::user()->id)
                                ->update($postData);

            } else {

                $postData['user_id'] = Auth::user()->id;
                BankDetails::create($postData);   
                Mail::to(env('SALES_EMAIL'))->send(new BankDetailsEmail(Auth::user(), $postData));
            }
            
            $returnData = array('success' => true , 'message' => 'Bank details has been updated.');

        } catch (Exception $e) {

            $returnData = array('success' => false , 'message' => $e->getMessage());
        }

        return response()->json($returnData);
    }

    public function withdrawPayment(Request $request) {
        $postData = $request->all();
        try {

            if(isset($postData['flight'])) {
                foreach($postData['flight'] as $id) {
                    FlightPayments::where('id', $id)
                                ->update(['withdraw_status' => 'requested']);
                }
            }

            if(isset($postData['others'])) {
                foreach($postData['others'] as $id) {
                    Payments::where('id', $id)
                                ->update(['withdraw_status' => 'requested']);
                }
            }
            
            Mail::to(env('SALES_EMAIL'))->send(new WithDrawRequest(Auth::user(), $postData['amount']));
            $returnData = array('success' => true , 'message' => 'Payment withdraw request has been sent to admin.');

        } catch (Exception $e) {

            $returnData = array('success' => false , 'message' => $e->getMessage());
        }

        return response()->json($returnData);
    }

    public function uploadCoverImage(Request $request) {

        if(isset($request->source) && $request->source != '') {

            try {

                $files = $request->source;
                list($type, $files) = explode(';', $files);
                list(, $files)      = explode(',', $files);
                $file_date = base64_decode($files);
                $file_type = explode("/", $type);
                
                $file_name = time().'.' . $file_type[1];
                $path = public_path() . "/uploads/profiles/" . $file_name;
                file_put_contents($path, $file_date);

                AffiliateUsers::where('user_id', Auth::user()->id)
                    ->update(['cover_pic' => $file_name]);

                $returnData = array('success' => true , 'message' => 'Cover picture has been updated.');

            } catch (Exception $e) {

                $returnData = array('success' => false , 'message' => $e->getMessage());
            }

            return response()->json($returnData);
        }
    }

    public function addPayment(Request $request) {

        $postData = $request->all();

        try {

            $files = $postData['pay_receipt'];
            list($type, $files) = explode(';', $files);
            list(, $files)      = explode(',', $files);
            $file_date = base64_decode($files);
            $file_type = explode("/", $type);
            
            $file_name = time().'.' . $file_type[1];
            $path = public_path() . "/uploads/pay-receipts/" . $file_name;
            file_put_contents($path, $file_date);

            WalletPaymentRequests::create(['user_id' => Auth::user()->id,
                                           'amount' => $postData['amount'],
                                           'pay_receipt' => $file_name,
                                           'comments' => $postData['comments']]);

            $path = public_path('uploads/pay-receipts/' . $file_name);
            Mail::to(env('SALES_EMAIL'))->send(new WalletPaymentRequestEmail(Auth::user(), $postData['amount'], $path, $file_name));
            $returnData = array('success' => true , 'message' => 'Payment request has been sent to admin.');

        } catch (Exception $e) {

            $returnData = array('success' => false , 'message' => $e->getMessage());
        }

        return response()->json($returnData);
    }

    public function sendMessage(Request $request) {

        $postData = $request->all();
        try {

            Messages::create([
                'sender_id' => Auth::user()->id,
                'reciever_id' => $postData['sender_id'],
                'message' => $postData['message']
            ]);

            //create notification
            NotificationAgents::create(['agent_id' => $postData['sender_id'],
                'type' => 'message',
                'description' => Auth::user()->name . ' has sent you a new message.',
                'price' => '',
                'status' => 0
            ]);
            $returnData = array('success' => true , 'message' => 'Message sent.');

        } catch(Exception $e) {
            $returnData = array('success' => false , 'message' => $e->getMessage());
        }
        return response()->json($returnData);
    }

    public function readMessage(Request $request) {
        $postData = $request->all();
        try {

            DB::select("UPDATE `messages` SET `status` = 'read'  WHERE (`sender_id` = '" . $postData['sender_id'] . "' AND `reciever_id` = '". Auth::user()->id ."') OR  (`reciever_id` = '" . $postData['sender_id'] . "' AND `sender_id` = '". Auth::user()->id ."')");

            $chats = DB::select("SELECT * FROM `messages` WHERE (`sender_id` = '" . $postData['sender_id'] . "' AND `reciever_id` = '". Auth::user()->id ."') OR  (`reciever_id` = '" . $postData['sender_id'] . "' AND `sender_id` = '". Auth::user()->id ."') ORDER BY `created_at` ASC");

            
            $returnArray = array();

            foreach ($chats as $key => $chat) {
                

                if(Auth::user()->id != $chat->sender_id) {
                    $sender_id = $chat->sender_id;
                } else {
                    $sender_id = $chat->reciever_id;
                }

                $sender = User::where('id', $sender_id)->first();
                if(empty($returnArray)) {

                    array_push($returnArray, array('date' => date('l, F jS, Y', strtotime($chat->created_at)), 'chat' => array()));
                    array_push($returnArray[0]['chat'] , array('sender_pic' => $sender->picture, 'message' => $chat->message, 'sender_id' => $chat->sender_id ));

                } else {

                    $key = $this->searchForValue( date('l, F jS, Y', strtotime($chat->created_at)), $returnArray);
                    
                    if($key !== 'empty') {
                    
                        array_push($returnArray[$key]['chat'], array('sender_pic' => $sender->picture, 'message' => $chat->message, 'sender_id' => $chat->sender_id ));
                    } else {

                        array_push($returnArray, array('date' => date('l, F jS, Y', strtotime($chat->created_at)), 'chat' => array()));
                        array_push($returnArray[sizeof($returnArray) - 1]['chat'] , array('sender_pic' => $sender->picture, 'message' => $chat->message, 'sender_id' => $chat->sender_id ));
                    }
                }
            }

            $returnData = array('success' => true , 'messages' => $returnArray, 'current_user' => Auth::user()->id, 'user_pic' => Auth::user()->picture);

        } catch(Exception $e) {
            $returnData = array('success' => false , 'message' => $e->getMessage());
        }
        return response()->json($returnData);
    }

    public function searchForValue($value, $array) {
        foreach ($array as $key => $val) {
            if ($val['date'] === $value) {
                return $key;
            }
        }
        return 'empty';
    }

    /* Get earnings filter by type */

    public function getEarningsType(Request $request){

        $postData = $request->all();

        $user = User::find(Auth::user()->id);
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();

        $hotelpayments = 0;
        $flightpayments = 0;
        $cabpayments = 0;
        $activitypayments = 0;

        if($request->type == 'hotel' && ( isset($postData['month_filter']) || isset($postData['to_date']) ) ){

            if($postData['month_filter'] != ''){
                
                $monthVal = $postData['month_filter'];

                $from = date("Y-m-d", strtotime("-".$monthVal." months"));
                $to = date('Y-m-d');

            }else{

                $from = $postData['from_date'];
                $to = $postData['to_date'];
            }

                $hotel_bookings = Payments::where('agent_id', Auth::user()->id)
                            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                            ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number,bookings.booking_id as bookID,payments.*'))
                            ->where('bookings.type', 'hotel')
                            ->whereBetween('bookings.created_at', [$from, $to])
                            ->get();

        }else{

            $hotel_bookings = Payments::where('agent_id', Auth::user()->id)
                        ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                        ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number,bookings.booking_id as bookID,payments.*'))
                        ->where('bookings.type', 'hotel')
                        ->get();
        }


        foreach ($hotel_bookings as $key => $hvalue) {
            # code...
            $hotel_bookings[$key]['r_data'] = json_decode($hvalue['r_data'], true);
            $hotelpayments = $hotelpayments + $hvalue['commission'];
        }

        //echo "<pre>";print_r($hotel_bookings);die;

        if($request->type == 'flight' && ( isset($postData['month_filter_flight']) || isset($postData['to_date_flight']) ) ){

            if($postData['month_filter_flight'] != ''){
                
                $monthVal = $postData['month_filter_flight'];

                $from = date("Y-m-d", strtotime("-".$monthVal." months"));
                $to = date('Y-m-d');

            }else{

                $from = $postData['from_date_flight'];
                $to = $postData['to_date_flight'];
            }

            $flight_bookings = FlightPayments::where('agent_id', Auth::user()->id)
                        ->join('flight_bookings', 'flight_payments.booking_id', '=', 'flight_bookings.id')
                        ->select(DB::raw('flight_bookings.flight_booking_status as booking_status,flight_bookings.booking_id as bookID, flight_bookings.invoice_number as c_number, flight_bookings.request_data as r_data, flight_payments.*'))
                        ->whereBetween('flight_bookings.created_at', [$from, $to])
                        ->get();


        }else{

            $flight_bookings = FlightPayments::where('agent_id', Auth::user()->id)
                        ->join('flight_bookings', 'flight_payments.booking_id', '=', 'flight_bookings.id')
                        ->select(DB::raw('flight_bookings.flight_booking_status as booking_status,flight_bookings.booking_id as bookID, flight_bookings.invoice_number as c_number, flight_bookings.request_data as r_data, flight_payments.*'))
                        ->get();
        }


        foreach ($flight_bookings as $key => $fvalue) {
            # code...
            $flight_bookings[$key]['f_data'] = json_decode($fvalue['r_data'], true);
            $flightpayments = $flightpayments + $fvalue['commission'];
        }  
                

        if($request->type == 'cab' && ( isset($postData['month_filter_cab']) || isset($postData['to_date_cab']) ) ){

            if($postData['month_filter_cab'] != ''){
                
                $monthVal = $postData['month_filter_cab'];

                $from = date("Y-m-d", strtotime("-".$monthVal." months"));
                $to = date('Y-m-d');

            }else{

                $from = $postData['from_date_cab'];
                $to = $postData['to_date_cab'];
            }

            $cab_bookings = Payments::where('agent_id', Auth::user()->id)
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->select(DB::raw('bookings.hotel_booking_status as booking_status,bookings.booking_id as bookID, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                ->where('bookings.type', 'cab')
                ->whereBetween('bookings.created_at', [$from, $to])
                ->get();

        }else{


            $cab_bookings = Payments::where('agent_id', Auth::user()->id)
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->select(DB::raw('bookings.hotel_booking_status as booking_status,bookings.booking_id as bookID, bookings.request_data as r_data, bookings.confirmation_number as c_number, payments.*'))
                ->where('bookings.type', 'cab')
                ->get();
        }

        foreach ($cab_bookings as $key => $cvalue) {
            # code...
            $cab_bookings[$key]['c_data'] = json_decode($cvalue['r_data'], true);
            $cabpayments = $cabpayments + $cvalue['commission'];
        }

        if($request->type == 'activity' && ( isset($postData['month_filter_activity']) || isset($postData['to_date_activity']) ) ){

            if($postData['month_filter_activity'] != ''){
                
                $monthVal = $postData['month_filter_activity'];

                $from = date("Y-m-d", strtotime("-".$monthVal." months"));
                $to = date('Y-m-d');

            }else{

                $from = $postData['from_date_activity'];
                $to = $postData['to_date_activity'];
            }

            $act_bookings = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number,bookings.booking_id as bookID, payments.*'))
                    ->where('bookings.type', 'activity')
                    ->whereBetween('bookings.created_at', [$from, $to])
                    ->get();
        }else{

            $act_bookings = Payments::where('agent_id', Auth::user()->id)
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->select(DB::raw('bookings.hotel_booking_status as booking_status, bookings.request_data as r_data, bookings.confirmation_number as c_number,bookings.booking_id as bookID, payments.*'))
                    ->where('bookings.type', 'activity')
                    ->get();
        }


        foreach ($act_bookings as $key => $avalue) {
            # code...
            $act_bookings[$key]['a_data'] = json_decode($avalue['r_data'], true);
            $activitypayments = $activitypayments + $avalue['commission'];
        }        

        //echo "<pre>";print_r($act_bookings);die;

        return view('agent.earnings')->with(['user' => $user, 'notifications' => $notifications, 'hotel_bookings' => $hotel_bookings, 'hotel_payments' => $hotelpayments,'flight_payments' => $flightpayments,'cab_payments' => $cabpayments,'activity_payments' => $activitypayments,'flight_bookings' => $flight_bookings,'cab_bookings' => $cab_bookings,'act_bookings' => $act_bookings, 'type' => $request->type]);

    }

}