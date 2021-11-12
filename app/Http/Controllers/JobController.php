<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Jobs\JobA;
use App\Jobs\JobB;
use App\Jobs\JobC;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use App\Jobs\HotelWorker;
use App\Jobs\CityWorker;
use App\Models\Cities;
use Log;
use App\Models\StaticDataHotels;

class JobController extends Controller {

    public function getIp() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function getCitiesData() {

        if ((config('queue.default') == 'redis') &&
            \Illuminate\Support\Facades\Redis::llen('queues:' . config('queue.connections.redis.queue')) > 1000
        ) {
            return false;
        }
        $currDate=date("Y-m-d", strtotime("-7 days"));
        //$cities =Cities::where('CityId', $city_id)->update(['data_updated' => 2]);
        $cities=Cities::whereDate('updated_at','<',$currDate)->select('CityId')->take(500)->get();

        //$cities = DB::select("SELECT `CityId` FROM `cities` WHERE `data_updated` = '0'  LIMIT 30");
        foreach ($cities as $city) {

            $city_id = $city->CityId;
            $ip = $this->getIp();
            //$qNo = rand(1, 10);
            //$queueName = "JobQueue999";

            try {
                $this->dispatch((new CityWorker($city_id, $ip)));
            } catch (Exception $e) {
                $errorMessage = "\n" . $e->getMessage();
                Log::info(['CityWorker:' => $errorMessage]);
                //File::append(public_path() . "/logs/static-data.log", $errorMessage);
            }
        }
        echo"Fetching top 10 cities data";
    }

    public function getHotelsData() {

        if ((config('queue.default') == 'redis') &&
            \Illuminate\Support\Facades\Redis::llen('queues:' . config('queue.connections.redis.queue')) > 1000
        ) {
            return false;
        }

        
        //now download hotel static data for that city
        //DB::enableQueryLog();
        $hotels=StaticDataHotels::where(function ($q) {
            $currDate=date("Y-m-d", strtotime("-7 days"));
            $q->where('data_updated','0')->orWhereDate('updated_at','<',$currDate);
        //})//->where(function ($q) {
          //  $q->where('hotel_images', '[]')->orWhere('hotel_images',NULL);
        })->select('hotel_code', 'city_id')->take(1000)->get();
        
        //dd(DB::getQueryLog());die;
        if ($hotels) {
            try {
                foreach ($hotels as $key => $hotel) {
                    $city_id = $hotel->city_id;
                    $ip = $this->getIp();
                    //$qNo = rand(1, 10);
                    //$queueName = "JobQueue".$qNo;
                    $this->dispatch((new HotelWorker($hotel->hotel_code, $city_id, $ip)));
                    //die("stop");
                }
            } catch (Exception $e) {
                dd($e->getMessage());
                $errorMessage = "\n" . $e->getMessage();
                Log::info(['HotelWorker:' => $errorMessage]);
                //File::append(public_path() . "/logs/static-data.log", $errorMessage);
            }
        }

        echo"Fetching top 10 hotels data";
    }

    /**
     * Store a new podcast.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index() {
        $this->dispatch(new JobA());
        $this->dispatch(new JobB());
        $this->dispatch(new JobC());
        $this->dispatch(new JobA());
        $this->dispatch(new JobB());
        $this->dispatch(new JobC());
        $this->dispatch(new JobA());
        $this->dispatch(new JobB());
        $this->dispatch(new JobC());
        $this->dispatch(new JobA());
        $this->dispatch(new JobB());
        $this->dispatch(new JobC());
        $this->dispatch(new JobA());
        $this->dispatch(new JobB());
        $this->dispatch(new JobC());
        $this->dispatch(new JobA());
        $this->dispatch(new JobB());
        $this->dispatch(new JobC());
        $this->dispatch(new JobA());
        $this->dispatch(new JobB());
        $this->dispatch(new JobC());
        $this->dispatch(new JobA());
        $this->dispatch(new JobB());
        $this->dispatch(new JobC());
        $this->dispatch(new JobA());
        $this->dispatch(new JobB());
        $this->dispatch(new JobC());

        die("stop");
    }

    public function deleteSearches() {

        $h_files = glob(public_path() . '/logs/searches/hotels/*'); // get all file names
        
        foreach($h_files as $file){
          if(is_file($file)) {
            $f_name = explode("/hotels/", $file);
            $file_name = $f_name[1];
            if (strpos($file_name, '_') !== false) {
                $time_stamp = explode("_",$file_name);
            } else {
                $time_stamp = explode(".",$file_name);
            }
            $time = $time_stamp[0];

            if ((time() > $time)){            
              unlink($file);
            }
          }
        }

        $c_files = glob(public_path() . '/logs/searches/cabs/*'); // get all file names
        
        foreach($c_files as $file){
          if(is_file($file)) {
            $f_name = explode("/cabs/", $file);
            $file_name = $f_name[1];
            if (strpos($file_name, '_') !== false) {
                $time_stamp = explode("_",$file_name);
            } else {
                $time_stamp = explode(".",$file_name);
            }
            $time = $time_stamp[0];

            if ((time() > $time)){            
              unlink($file);
            }
          }
        }

        $f_files = glob(public_path() . '/logs/searches/flights/*'); // get all file names
        
        foreach($f_files as $file){
          if(is_file($file)) {
            $f_name = explode("/flights/", $file);
            $file_name = $f_name[1];
            if (strpos($file_name, '_') !== false) {
                $time_stamp = explode("_",$file_name);
            } else {
                $time_stamp = explode(".",$file_name);
            }
            $time = $time_stamp[0];

            if ((time() > $time)){            
              unlink($file);
            }
          }
        }

        $a_files = glob(public_path() . '/logs/searches/activity/*'); // get all file names
        
        foreach($a_files as $file){
          if(is_file($file)) {
            $f_name = explode("/activity/", $file);
            $file_name = $f_name[1];
            if (strpos($file_name, '_') !== false) {
                $time_stamp = explode("_",$file_name);
            } else {
                $time_stamp = explode(".",$file_name);
            }
            $time = $time_stamp[0];

            if ((time() > $time)){            
              unlink($file);
            }
          }
        }

        $c_files = glob(public_path() . '/logs/searches/cabs/*'); // get all file names
        
        foreach($c_files as $file){
          if(is_file($file)) {
            $f_name = explode("/cabs/", $file);
            $file_name = $f_name[1];
            if (strpos($file_name, '_') !== false) {
                $time_stamp = explode("_",$file_name);
            } else {
                $time_stamp = explode(".",$file_name);
            }
            $time = $time_stamp[0];

            if ((time() > $time)){            
              unlink($file);
            }
          }
        }

        return 'done';
    }

}
