<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Currencies;

if(!function_exists('wallet_blance')) {

	function wallet_blance() {
		$myCurrency = Session::get('CurrencyCode');
	    $wallet_bal = \Auth::user()->balance;
	    if (!$myCurrency) {
	        if ($wallet_bal > 0) {
	        	if (!Session::has('walletAmount')) {
	            	$usercurrency = Currency::convert('USD', $myCurrency, $wallet_bal);
	            	if(isset($usercurrency['convertedAmount'])) {
		            	$walletAmount = round($usercurrency['convertedAmount']);
		            } else {
		            	$walletAmount = $wallet_bal;
		            }
	            	Session::put('walletAmount', $walletAmount);
	            } else {
	            	$usercurrency = Currency::convert('USD', $myCurrency, $wallet_bal);
	            	if(isset($usercurrency['convertedAmount'])) {
		            	$walletAmount = round($usercurrency['convertedAmount']);
		            } else {
		            	$walletAmount = $wallet_bal;
		            }
	            	Session::put('walletAmount', $walletAmount);
	            }
	        } else {
	            $walletAmount = 0;
	        	Session::put('walletAmount', $walletAmount);
	        }
	    } else {
	    	if(isset($_COOKIE['th_country']) && $_COOKIE['th_country'] !='') {

	    		if (!Session::has('walletAmount')) {

		    		$location = json_decode($_COOKIE['th_country']);
		    		$countryInfo = Currencies::where('code', $location->countryCode)->first();            
	            	$myCurrency = $countryInfo['currency_code'];
	            	Session::put('CurrencyCode', $myCurrency);

	            	$usercurrency = Currency::convert('USD', $myCurrency, $wallet_bal);

	            	if(isset($usercurrency['convertedAmount'])) {
		            	$walletAmount = round($usercurrency['convertedAmount']);
		            } else {
		            	$walletAmount = $wallet_bal;
		            }
	        	 	Session::put('walletAmount', $walletAmount);

	        	} else {

	        		$usercurrency = Currency::convert('USD', $myCurrency, $wallet_bal);
	            	if(isset($usercurrency['convertedAmount'])) {
		            	$walletAmount = round($usercurrency['convertedAmount']);
		            } else {
		            	$walletAmount = $wallet_bal;
		            }
	            	Session::put('walletAmount', $walletAmount);
	        	}

	    	} else {

		        $walletAmount = Session::get('walletAmount');
	    	}
	    }

	    return array('currency' => $myCurrency, "amount" => $walletAmount);
	}
}

?>