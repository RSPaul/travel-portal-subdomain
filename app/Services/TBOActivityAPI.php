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

class TBOActivityAPI
{

  //
    public $tokenId;
    public $userIP;
    public $traceId;
    public $agencyId;
    public function __construct()
    {

        ini_set('serialize_precision', 14);
        $token = Token::where(['mode' => env('API_MODE_ACTIVITY')])->first();
        //$now = date('yy-m-d h:i:s');
        date_default_timezone_set('Asia/Kolkata');
        $now = date("Y-m-d");
        $token['updated_at'] = date("Y-m-d",strtotime($token['updated_at']));
        //$hourdiff = round((strtotime($now) - strtotime($token['updated_at']))/3600, 1);
        if($now > $token['updated_at']) {
          //get new token
          $url = env('API_ACTIVITY_HOST') . env('API_AUTH_URL_ACTIVITY');
          
          $response = Http::post($url, [
              'ClientId' => env('ClientId_Activity'),
              'UserName' => env('API_UserName_Activity'),
              'Password' => env('API_Password_Activity'),
              'EndUserIp' => $this->getIp(),
          ]);
          $tokenResponse =  $response->json();
          $this->tokenId = $tokenResponse['TokenId'];
          $this->agencyId = env('AgencyId');

          if($this->tokenId && $this->tokenId !='') {
            if($this->tokenId != $token['token']) {
              Token::where(['mode' => env('API_MODE_ACTIVITY')])
                ->update(['token' => $this->tokenId]);
            }
          }
        } else {
          $this->tokenId = $token['token'];
          $this->agencyId = env('AgencyId');
        }

       
        $this->userIP = $this->getIp();
    }

    public function activitySearch($postData) {
        $url = env('API_ACTIVITY_LIVE') . 'Search';
        //echo $url;
        $response = Http::post($url, $postData);
        //echo "<pre>";print_r($response);
        $this->writeLogs($postData, 'activity-search-request');
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'activity-search-response');
        return $results;
    }

    public function activityGetAvailability($postData) {
        $url = env('API_ACTIVITY_LIVE') . 'GetAvailability';
        //echo $url;
        $response = Http::post($url, $postData);
        //echo "<pre>";print_r($response);
        $this->writeLogs($postData, 'activity-get-availability-request');
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'activity-get-availability-response');
        return $results;
    }

    public function blockActivity($postData) {
        $url = env('API_ACTIVITY_LIVE') . 'Block';
        //echo $url;
        $response = Http::post($url, $postData);
        // echo "<pre>";print_r($response);
        $this->writeLogs($postData, 'activity-block-request');
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'activity-block-response');
        return $results;
    }

    public function bookActivity($postData) {
        $url = env('API_ACTIVITY_LIVE') . 'Book';
        //echo $url;
        $response = Http::post($url, $postData);
        // echo "<pre>";print_r($response);
        $this->writeLogs($postData, 'activity-book-request');
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'activity-book-response');
        return $results;
    }

    public function bookDetailActivity($postData) {
        $url = env('API_ACTIVITY_LIVE') . 'GetBookingDetail';
        //echo $url;
        $response = Http::post($url, $postData);
        // echo "<pre>";print_r($response);
        $this->writeLogs($postData, 'activity-book-detail-request');
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'activity-book-detail-response');
        return $results;
    }


    public function sendChangeRequest($postData) {
        $url = env('API_ACTIVITY_LIVE') . 'SendChangeRequest';
        //echo $url;
        $response = Http::post($url, $postData);
        // echo "<pre>";print_r($response);
        $this->writeLogs($postData, 'activity-send-change-request');
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'activity-send-change-response');
        return $results;
    }

    public function getChangeRequestStatus($postData) {
        $url = env('API_ACTIVITY_LIVE') . 'GetChangeRequestStatus';
        //echo $url;
        $response = Http::post($url, $postData);
        // echo "<pre>";print_r($response);
        $this->writeLogs($postData, 'activity-get-change-request');
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'activity-get-change-response');
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
      $destinationPath=public_path()."/logs/activities/";
      if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
      File::put($destinationPath.$file,$data);
  }
}