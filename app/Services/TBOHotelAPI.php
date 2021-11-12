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

class TBOHotelAPI
{

  //
    public $tokenId;
    public $userIP;
    public $traceId;
    public function __construct()
    {

        ini_set('serialize_precision', 14);
        $token = Token::where(['mode' => env('API_MODE_HOTEL')])->first();
        
        date_default_timezone_set('Asia/Kolkata');
        $now = date("Y-m-d");
        $token['updated_at'] = date("Y-m-d",strtotime($token['updated_at']));
       
        if($now > $token['updated_at']) {
          //get new token
          $url = env('API_HOST') . env('API_AUTH_URL');
          $postData  = [
              'ClientId' => env('ClientId'),
              'UserName' => env('API_UserName'),
              'Password' => env('API_Password'),
              'EndUserIp' => $this->getIp(),
          ];
          $this->writeLogs($postData, 'auth-request');
          $response = Http::post($url, $postData);
          $tokenResponse =  $response->json();
          $this->writeLogs($tokenResponse, 'auth-results');
          $this->tokenId = $tokenResponse['TokenId'];

          if($this->tokenId && $this->tokenId !='') {
            if($this->tokenId != $token['token']) {
              Token::where(['mode' => env('API_MODE_HOTEL')])
                ->update(['token' => $this->tokenId]);
            }
          }
        } else {
          $this->tokenId = $token['token'];
        }

       
        /* Static Token */
       // $this->tokenId = "d44993f7-6715-44a5-a62b-2ce1c8eb0976";
        $this->userIP = $this->getIp();
    }


    public function hotelSearch($postData) {
        $url = env('API_HOST') . env('API_BOOKING_URL') . 'GetHotelResult/';
        $this->writeLogs($postData, 'hotel-search-request');
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'hotel-search-response');
        $this->traceId = (isset($results['HotelSearchResult']) && $results['HotelSearchResult']['TraceId'] != null) ? $results['HotelSearchResult']['TraceId'] : '';
        return $results;
    }

    public function hotelInfo($hotelCode, $resultIndex, $traceId, $CategoryId) {
        $url = env('API_HOST') . env('API_BOOKING_URL'). 'GetHotelInfo';
        $postData = ['EndUserIp' => $this->getIp(),
                    'TokenId' => $this->tokenId,
                    'TraceId' => $traceId,
                    'ResultIndex' => $resultIndex,
                    'HotelCode' => $hotelCode,
                    'CategoryId' => $CategoryId];
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->checkError($results);
        return $results;
    }

    public function hotelRooms($itemId, $resultIndex, $traceId, $supplierCategories) {
        $url = env('API_HOST') . env('API_BOOKING_URL') . 'GetHotelRoom';
        $postData = ['EndUserIp' => $this->getIp(),
                    'TokenId' => $this->tokenId,
                    'TraceId' => $traceId,
                    'ResultIndex' => $resultIndex,
                    'HotelCode' => $itemId,
                    'CategoryIndexes' => $supplierCategories];
        $this->writeLogs($postData, 'hotel-rooms-request');
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'hotel-rooms-response');
        return $results;
    }

    public function hotelBlockRoom($hotelCode, $resultIndex, $traceId, $hotelName, $hotelRoomsarr,$noofRooms,$guestNationality, $CategoryId, $isVoucherBooking) {
        $url = env('API_HOST') . env('API_BOOKING_URL') . 'BlockRoom/';

         $postData = [
                "ResultIndex" => $resultIndex,
                "HotelCode" => $hotelCode,
                "HotelName" => $hotelName,
                "GuestNationality" => $guestNationality,
                "NoOfRooms" => $noofRooms,
                "ClientReferenceNo" => "0",
                "IsVoucherBooking" => $isVoucherBooking,
                "CategoryId" => $CategoryId,
                //"HotelRoomsDetails" => [$hotelRoomsarr],
                "HotelRoomsDetails" => $hotelRoomsarr,
                "EndUserIp" => $this->getIp(),
                "TokenId" => $this->tokenId,
                "TraceId" => $traceId,
          ];
        $this->writeLogs($postData, 'hotel-block-request');
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'hotel-block-response');
        // echo "<pre>";print_r($postData); print_r($response);die();
        return $results;
    }

    public function hotelBookRoom($checkIndate, $hotelCode, $resultIndex, $traceId, $hotelName, $HotelRoomsDetails, $noOfRooms, $IsPackageFare, $IsPackageDetailsMandatory,$guestNationality, $CategoryId, $isVoucherBooking) {
        $url = env('API_BOOK_ROOM_URL') . 'Book/';
        //echo "<pre>";print_r($bookRoomArr);echo "</pre>";die;
        if(isset($IsPackageDetailsMandatory) && $IsPackageDetailsMandatory == 1 && isset($IsPackageFare) && $IsPackageFare == 1){

            $postData = [
                  "ResultIndex" => $resultIndex,
                  "HotelCode" => $hotelCode,
                  "CategoryId" => $CategoryId,
                  "HotelName" => $hotelName,
                  "GuestNationality" => $guestNationality,
                  "NoOfRooms" => $noOfRooms,
                  "ClientReferenceNo" => "0",
                  "IsVoucherBooking" => $isVoucherBooking,
                  "HotelRoomsDetails" => $HotelRoomsDetails,
                  "IsPackageFare" => true,
                  "ArrivalTransport" => [
                    "ArrivalTransportType" => 0,
                    "TransportInfoId" => "Ab 777",
                    "Time" => date( 'Y-m-d\TH:i:s', strtotime($checkIndate) )
                  ],
                  "IsPackageDetailsMandatory" => true,
                  "EndUserIp" => $this->getIp(),
                  "TokenId" => $this->tokenId,
                  "TraceId" => $traceId,
            ];
        }else{
            $postData = [
                  "ResultIndex" => $resultIndex,
                  "HotelCode" => $hotelCode,
                  "CategoryId" => $CategoryId,
                  "HotelName" => $hotelName,
                  "GuestNationality" => $guestNationality,
                  "NoOfRooms" => $noOfRooms,
                  "ClientReferenceNo" => "0",
                  "IsVoucherBooking" => $isVoucherBooking,
                  "HotelRoomsDetails" => $HotelRoomsDetails,
                  "IsPackageFare" => ($IsPackageFare == 1) ? true : false,
                  "IsPackageDetailsMandatory" => ($IsPackageDetailsMandatory == 1) ? true : false,
                  "EndUserIp" => $this->getIp(),
                  "TokenId" => $this->tokenId,
                  "TraceId" => $traceId,
            ];  
        }
        $this->writeLogs($postData, 'hotel-book-request');
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->checkError($results);
        $this->writeLogs($results, 'hotel-book-response');
        return $results;
    }

    public function hotelBookingDetail($bookingId){
        $url = env('API_BOOK_ROOM_URL') . 'GetBookingDetail/';
        $postData = [
              "EndUserIp" => $this->getIp(),
              "TokenId" => $this->tokenId,
              "BookingId" => $bookingId,
        ];
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->checkError($results);
        return $results;
    }

    public function getCityDataCron($cityId) {
       $url = env('API_HOST_STATIC') . '/StaticData.svc/rest/GetHotelStaticData';
        $postData = [
              'CityId' => $cityId,
              'ClientId' => env('ClientId'),
              'UserName' => env('API_UserName'),
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
        ];
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->writeLogsData($results['HotelData'], 'static-data/city-' . $cityId);

        $data = $cityId;
        $file = 'city_static.txt';
        $destinationPath=public_path()."/logs/static-data/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$data);
        


        // $this->checkError($results);
        return $results;
    }

    public function fetchCityData($cityId,$ip) {
       $url = env('API_HOST_STATIC') . '/StaticData.svc/rest/GetHotelStaticData';
        $postData = [
              'CityId' => $cityId,
              'ClientId' => env('ClientId'),
              'UserName' => env('API_UserName'),
              'TokenId' => $this->tokenId,
              'EndUserIp' => $ip,
        ];
        $response = Http::post($url, $postData);
        $results = $response->json();
        // $this->writeLogsData($results['HotelData'], 'static-data/city-' . $cityId);

        // $data = $cityId;
        // $file = 'city_static.txt';
        // $destinationPath=public_path()."/logs/static-data/";
        // if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        // File::put($destinationPath.$file,$data);
        


        $this->checkError($results);
        return $results;
    }
    
    public function getCityData($cityId) {
       $url = env('API_HOST_STATIC') . '/StaticData.svc/rest/GetHotelStaticData';
        $postData = [
              'CityId' => $cityId,
              'ClientId' => env('ClientId'),
              'UserName' => env('API_UserName'),
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
        ];
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->writeLogsData($results['HotelData'], 'static-data/city-' . $cityId);

        $data = $cityId;
        $file = 'city_static.txt';
        $destinationPath=public_path()."/logs/static-data/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$data);
        


        $this->checkError($results);
        return $results;
    }

    public function fetchHotelStaticData($cityId, $hotelCode,$ip) {
        $url = env('API_HOST_STATIC') . '/StaticData.svc/rest/GetHotelStaticData';
        $postData = [
              'CityId' => $cityId,
              'HotelId' => $hotelCode,
              'ClientId' => env('ClientId'),
              'UserName' => env('API_UserName'),
              'TokenId' => $this->tokenId,
              'EndUserIp' => $ip,
        ];
        $response = Http::post($url, $postData);
        // if($hotelCode == '1605716' || $hotelCode == '1605716') {
        //   $this->writeLogs($response['HotelData'], 'static-data/hotel-' . $hotelCode);
        // }
        $results = $response->json();
        return $results;
    }
    
    public function getHotelStaticData($cityId, $hotelCode) {
        $url = env('API_HOST_STATIC') . '/StaticData.svc/rest/GetHotelStaticData';
        $postData = [
              'CityId' => $cityId,
              'HotelId' => $hotelCode,
              'ClientId' => env('ClientId'),
              'UserName' => env('API_UserName'),
              'TokenId' => $this->tokenId,
              'EndUserIp' => $this->getIp(),
        ];
        $response = Http::post($url, $postData);
        // if($hotelCode == '1605716' || $hotelCode == '1605716') {
        //   $this->writeLogs($response['HotelData'], 'static-data/hotel-' . $hotelCode);
        // }
        $results = $response->json();
        return $results;
    }

    public function getHotelStaticDataLive($cityId, $hotelCode) {
        $url = env('Static_Data_URL_Live');
        $postData = [
              'CityId' => $cityId,
              'HotelId' => $hotelCode,
              'ClientId' => env('ClientId_Live'),
              'UserName' => env('API_UserName_Live'),
              'TokenId' => env('API_Token_Live'),
              'EndUserIp' => "3.64.135.96",
        ];
        $response = Http::post($url, $postData);
        // if($hotelCode == '1605716' || $hotelCode == '1605716') {
        //   $this->writeLogs($response['HotelData'], 'static-data/hotel-' . $hotelCode);
        // }
        $results = $response->json();
        return $results;
    }

    public function sendChangeRequest($postData) {
        $url = env('API_HOST') . env('API_BOOKING_URL') . '/SendChangeRequest';
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->writeLogs($results, 'hotel-send-change-response');
        return $results;
    }

    public function getChangeRequest($postData) {
        $url = env('API_HOST') . env('API_BOOKING_URL') . '/GetChangeRequestStatus/';
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->writeLogs($results, 'hotel-get-change-response');
        return $results;
    }


    public function generateVoucher($postData) {
        $url = env('API_HOST') . env('API_BOOKING_URL') . '/GenerateVoucher/';
        $response = Http::post($url, $postData);
        $results = $response->json();
        $this->writeLogs($results, 'hotel-generate-voucher');
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

  public function writeLogs($data, $type) {
      $data = json_encode($data);
      $file = $type. '_logs.json';
      $destinationPath=public_path()."/logs/hotels/";
      if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
      File::put($destinationPath.$file,$data);
  }

  public function writeLogsData($data, $type) {
      $data = json_encode($data);
      $file = $type. '_logs.xml';
      $destinationPath=public_path()."/logs/";
      if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
      File::put($destinationPath.$file,$data);
  }


    public function checkError($data) {
        if(isset($data['HotelSearchResult'])) {
            if($data['HotelSearchResult']['ResponseStatus'] != 1) {
              //throw new Exception($data['HotelSearchResult']['Error']['ErrorMessage']);
              Session::flash('error', $data['HotelSearchResult']['Error']['ErrorMessage']); 
            }
        } else if(isset($data['GetHotelRoomResult'])) {
            if($data['GetHotelRoomResult']['ResponseStatus'] != 1) {
                Session::flash('error', $data['GetHotelRoomResult']['Error']['ErrorMessage']); 
               //throw new Exception($data['GetHotelRoomResult']['Error']['ErrorMessage']); 
            }
        } else if(isset($data['HotelInfoResult'])) {
            if($data['HotelInfoResult']['ResponseStatus'] != 1) {
                Session::flash('error', $data['HotelInfoResult']['Error']['ErrorMessage']); 
               //throw new Exception($data['HotelInfoResult']['Error']['ErrorMessage']); 
            }
        } else if(isset($data['BlockRoomResult'])) {
            if($data['BlockRoomResult']['ResponseStatus'] != 1) {
                Session::flash('error', $data['BlockRoomResult']['Error']['ErrorMessage']); 
              //throw new Exception($data['BlockRoomResult']['Error']['ErrorMessage']);
                //Session::flash('error', $data['BlockRoomResult']['Error']['ErrorMessage']); 
            }
        } else if(isset($data['BookResult'])) {
            if($data['BookResult']['ResponseStatus'] != 1) {
                Session::flash('error', $data['BookResult']['Error']['ErrorMessage']); 
                //Session::flash('error', $data['BookResult']['Error']['ErrorMessage']); 
                //throw new Exception($data['BookResult']['Error']['ErrorMessage']);                
            }
        }
    }
}