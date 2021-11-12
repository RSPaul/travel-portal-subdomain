<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\Getaway;
use App\Models\Country;

class HomeController extends Controller {

    public function index() {

        try {

            $data['destinations'] = Destination::all();
            $data['experiences'] = Experience::all();
            
             $glist= Getaway::all();
             $getways=[];
             foreach($glist as $row){
                 $ikey=$row->category;
                 $ckey= strtolower(str_replace(" ", "", $ikey));
                 $getways[$ckey][]=$row;
             }
             
             
            $data['getaways']=$getways; 
            $data['countries'] = Country::all();

            return response()->json([
                        'status' => 'success',
                        'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                        'status' =>'error',
                        'message' => $e->getMessage(),
            ]);
        }
    }

}
