<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Exception;
use Session;
use File;
use App\Models\Token;

use GuzzleHttp\Client;

class TBOFlightAPI
{

  //
    public $tokenId;
    public $userIP;
    public $traceId;
    public function __construct()
    {

        ini_set('serialize_precision', 14);
        $token = Token::where(['mode' => env('API_MODE_FLIGHT')])->first();
        //$now = date('yy-m-d h:i:s');
        date_default_timezone_set('Asia/Kolkata');
        $now = date("Y-m-d");
        $token['updated_at'] = date("Y-m-d",strtotime($token['updated_at']));
        //$hourdiff = round((strtotime($now) - strtotime($token['updated_at']))/3600, 1);
        if($now > $token['updated_at']) {
          //get new token
          $url = env('API_FLIGHT_HOST') . env('API_AUTH_URL_FLIGHT');
          
          $response = Http::post($url, [
              'ClientId' => env('ClientId_Flight'),
              'UserName' => env('API_UserName_Flight'),
              'Password' => env('API_Password_Flight'),
              'EndUserIp' => $this->getIp(),
          ]);
          $tokenResponse =  $response->json();
          $this->tokenId = $tokenResponse['TokenId'];

          if($this->tokenId && $this->tokenId !='') {
            if($this->tokenId != $token['token']) {
              Token::where(['mode' => env('API_MODE_FLIGHT')])
                ->update(['token' => $this->tokenId]);
            }
          }
        } else {
          $this->tokenId = $token['token'];
        }

       
        $this->userIP = $this->getIp();
    }

    public function search($postData) {
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'Search';
        $response = Http::post($url, $postData);
        $this->writeLogs($postData, 'flight-search-request');
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'flight-search-response');
        return $results;
    }

    public function fareRule($traceID, $resultIndex) {
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'FareRule';
        //$response = Http::post($url, $postData);
        $response = Http::post($url, [
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
        ]);
        $this->writeLogs([
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp()], 'flight-farerule-request');
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'flight-farerule-response');
        return $results;
    }

    public function fareRuleIB($traceID, $resultIndex) {
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'FareRule';
        //$response = Http::post($url, $postData);
        $response = Http::post($url, [
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
        ]);
        $this->writeLogs([
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp()], 'flight-farerule-IB-request');
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'flight-farerule-IB-response');
        return $results;
    }

    public function fareQuote($traceID, $resultIndex) {
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'FareQuote';
        //$response = Http::post($url, $postData);
        $response = Http::post($url, [
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
        ]);
        $results = $response->json();
        $this->writeLogs([
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp()], 'flight-fareQuote-request');
        $this->checkError($results);
        $this->writeLogs($results, 'flight-fareQuote-response');
        return $results;
    }

    public function fareQuoteIB($traceID, $resultIndex) {
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'FareQuote';
        //$response = Http::post($url, $postData);
        $response = Http::post($url, [
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
        ]);
        $results = $response->json();
        $this->writeLogs([
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp()], 'flight-fareQuote-IB-request');
        $this->checkError($results);
        $this->writeLogs($results, 'flight-fareQuote-IB-response');
        return $results;
    }

    public function SSR($traceID, $resultIndex) {
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'SSR';
        //$response = Http::post($url, $postData);
        $response = Http::post($url, [
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
        ]);
        $results = $response->json();
        $this->writeLogs([
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp()], 'flight-SSR-request');
        $this->checkError($results);
        $this->writeLogs($results, 'flight-SSR-response');
        return $results;
    }

    public function SSRIB($traceID, $resultIndex) {
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'SSR';
        //$response = Http::post($url, $postData);
        $response = Http::post($url, [
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
        ]);
        $results = $response->json();
        $this->writeLogs([
              'ResultIndex' => $resultIndex,
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp()], 'flight-SSR-IB-request');
        $this->checkError($results);
        $this->writeLogs($results, 'flight-SSR-IB-response');
        return $results;
    }

    public function ticket($resultarr){
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'Ticket';
        $response = Http::post($url, $resultarr);
        $results = $response->json();
        $this->writeLogs($resultarr, 'flight-ticket-request');
        $this->checkError($results);
        $this->writeLogs($results, 'flight-ticket-response');
        return $results;
    }

    public function ticketIB($resultarr){
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'Ticket';
        $response = Http::post($url, $resultarr);
        $results = $response->json();
        $this->writeLogs($resultarr, 'flight-ticket-IB-request');
        $this->checkError($results);
        $this->writeLogs($results, 'flight-ticket-IB-response');
        return $results;
    }

    public function book($resultarr){
        unset($resultarr['PreferredCurrency']);
        unset($resultarr['IsBaseCurrencyRequired']);
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'Book';
        $this->writeLogs($resultarr, 'flight-book-request');
        $response = Http::post($url, $resultarr);
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'flight-book-response');
        return $results;
    }

    public function bookIB($resultarr){
        unset($resultarr['PreferredCurrency']);
        unset($resultarr['IsBaseCurrencyRequired']);
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'Book';
        $this->writeLogs($resultarr, 'flight-book-IB-request');
        $response = Http::post($url, $resultarr);
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'flight-book-IB-response');
        return $results;
    }

    public function getNoLCCTicket($traceID, $bookingId, $pnr){
       $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'Ticket';
        //$response = Http::post($url, $postData);
        $response = Http::post($url, [
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
              'PNR' => $pnr,
              'BookingId' => $bookingId,
        ]);
        $this->writeLogs([
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
              'PNR' => $pnr,
              'BookingId' => $bookingId], 'flight-non-lcc-ticket-request');
        $results = $response->json();
        $this->writeLogs($results, 'flight-non-lcc-ticket-response');
        $this->checkError($results);
        return $results;
    }

    public function getNoLCCTicketIB($traceID, $bookingId, $pnr){
       $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'Ticket';
        //$response = Http::post($url, $postData);
        $response = Http::post($url, [
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
              'PNR' => $pnr,
              'BookingId' => $bookingId,
        ]);
        $this->writeLogs([
              'TraceId' => $traceID,
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
              'PNR' => $pnr,
              'BookingId' => $bookingId], 'flight-non-lcc-ib-ticket-request');
        $results = $response->json();
        $this->writeLogs($results, 'flight-non-lcc-ib-ticket-response');
        $this->checkError($results);
        return $results;
    }

    /* GEt Booking Details */

    public function GetBookingDetails($resultarr){
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'GetBookingDetails';
        $response = Http::post($url, $resultarr);
        $results = $response->json();
        $this->writeLogs($resultarr, 'flight-get-booking-details');
        $this->checkError($results);
        $this->writeLogs($results, 'flight-send-booking-details');
        return $results;
    }


    public function SendChangeRequest($resultarr){
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'SendChangeRequest';
        $response = Http::post($url, $resultarr);
        $results = $response->json();
        $this->writeLogs($resultarr, 'flight-send-change-cancel-request');
        $this->checkError($results);
        $this->writeLogs($results, 'flight-send-change-cancel-response');
        return $results;
    }

    public function GetChangeRequest($resultarr){
        $url = env('API_FLIGHT_HOST') . env('API_FLIGHT_SEARCH_URL') . 'GetChangeRequestStatus';
        $response = Http::post($url, $resultarr);
        $results = $response->json();
        $this->writeLogs($resultarr, 'flight-get-change-cancel-request');
        $this->checkError($results);
        $this->writeLogs($results, 'flight-get-change-cancel-response');
        return $results;
    }

     public function checkPaymePayment($key, $saleID) {
        $url = env('PAYME_URL') . '/api/get-sales/';

        $postData = [
              'seller_payme_id' => $key,
              'sale_payme_id' => $saleID
        ];

        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->writeLogs($results, 'payme-get-sales-response');
        return $results;
    }

    public function getIp() {
      $ipaddress = '';
      if (isset($_SERVER['HTTP_CLIENT_IP']))
          $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
      else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
      else if(isset($_SERVER['HTTP_X_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
      else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
      else if(isset($_SERVER['HTTP_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_FORWARDED'];
      else if(isset($_SERVER['REMOTE_ADDR']))
          $ipaddress = $_SERVER['REMOTE_ADDR'];
      else
          $ipaddress = 'UNKNOWN';
      return $ipaddress;
    }

    public function checkError($data) {
        if(isset($data['Response'])) {
            if($data['Response']['ResponseStatus'] != 1) {
              //throw new Exception($data['Response']['Error']['ErrorMessage']);
              Session::flash('error', $data['Response']['Error']['ErrorMessage']); 
            }
        } 
    }

  public function writeLogs($data, $type) {
      $data = json_encode($data);
      $file = $type. '_logs.json';
      $destinationPath=public_path()."/logs/flights/";
      if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
      File::put($destinationPath.$file,$data);
  }
}