<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\BankDetails;
use App\Models\Payments;
use App\Models\FlightPayments;
use App\Models\NotificationAgents;
use App\Models\WalletPaymentRequests;

use Mail;
use App\Mail\Admin\AccountApproved;
use App\Mail\Admin\PaymentApproved;
use App\Mail\Admin\WalletPaymentApproved;

class PaymentsController extends Controller
{

	public function __construct(Request $request) {
        $this->middleware(['auth', 'isAdmin']);        
    }

	public function bankAccounts(Request $request) {

		$accounts = BankDetails::orderBy('created_at', 'DESC')->get();
		return view('admin.payments.accounts')->with(['accounts' => $accounts]);
	}  

	public function approveAccount(Request $request) {
		try {

			$accounts = BankDetails::where('id', $request->id)->update(['verified' => 'yes']);
			$returnData = array('success' => true , 'message' => 'This account has been aprroved.');

			/* Send notification to agent */
			$notifications = NotificationAgents::create(['agent_id' => $request->user,
                              'type' => 'bank',
                              'description' => 'Bank Account has been approved.',
                              'price' => '0',
                              'status' => 0
                          ]);

			$user = User::where('id', $request->user)->first();
			Mail::to($user->email)->send(new AccountApproved($user));

		} catch (Exception $e) {

			$returnData = array('success' => false , 'message' => $e->getMessage());
		}
		return response()->json($returnData);
	}

	public function withdrawls(Request $request) {

		$agents = User::where('role', 'agent')->get();
		$results = array();
		foreach($agents as $agent) {
			$payment = Payments::where(['agent_id' => $agent->id, 'withdraw_status' => 'requested'])->sum('commission');
			$flight_payment = FlightPayments::where(['agent_id' => $agent->id, 'withdraw_status' => 'requested'])->sum('commission');

			if(!empty($payment) || !empty($flight_payment)) {
				array_push($results, array('user' => $agent, 'amount' => ($payment + $flight_payment)));
			}
		}		

		return view('admin.payments.requests')->with('results', $results);
	}

	public function approveWithdrawl(Request $request) {

		try {

			$payment = Payments::where(['agent_id' => $request->user, 'withdraw_status' => 'requested'])
						->update(['withdraw_status' => 'paid']);
			$flight_payment = FlightPayments::where(['agent_id' => $request->user, 'withdraw_status' => 'requested'])
						->update(['withdraw_status' => 'paid']);
			$returnData = array('success' => true , 'message' => 'The payment has been aprroved.');

			/* Send notification to agent */
			$notifications = NotificationAgents::create(['agent_id' => $request->user,
                              'type' => 'payment',
                              'description' => 'Payment withdrawl request has been approved.',
                              'price' => $request->amount,
                              'status' => 0
                          ]);

			$user = User::where('id', $request->user)->first();
			Mail::to($user->email)->send(new PaymentApproved($user, $request->amount));
					
		} catch (Exception $e) {

			$returnData = array('success' => false , 'message' => $e->getMessage());
		}
		return response()->json($returnData);
	}

	public function payments(Request $request) {

		$requests = WalletPaymentRequests::join('users', 'users.id', '=', 'wallet_payment_requests.user_id')
					->select('wallet_payment_requests.*', 'users.name', 'users.email')
					->orderBy('wallet_payment_requests.created_at', 'DESC')
					->get();
        return view('admin.payments.wallet-requests')->with(['requests' => $requests]);
	}

	public function approveWalletPayment(Request $request) {

		try {

			$payment = WalletPaymentRequests::where(['user_id' => $request->user, 'id' => $request->id])
						->update(['status' => 'approved']);

			$returnData = array('success' => true , 'message' => 'The payment has been added to the affiliate wallet.');

			$notifications = NotificationAgents::create(['agent_id' => $request->user,
                              'type' => 'payment',
                              'description' => 'Payment has been added to the wallet.',
                              'price' => $request->amount,
                              'status' => 0
                          ]);

			$user = User::where('id', $request->user)->first();

			$walletuser = $user;
            $walletuser->deposit(intval(round($request->amount,2)), ["description" => 'Wallet payment approved by admin.']);

			Mail::to($user->email)->send(new WalletPaymentApproved($user, $request->amount));
					
		} catch (Exception $e) {

			$returnData = array('success' => false , 'message' => $e->getMessage());
		}
		return response()->json($returnData);
	}
}