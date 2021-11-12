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
use App\Models\Cities;

use Carbon\Carbon;
use DB;

class DiscoverController extends Controller
{   

	public function __construct(Request $request) {
        $this->middleware(['auth', 'isAdmin']);        
    }

    public function index(Request $request) {
        $countries = DB::select("SELECT DISTINCT `Country`, `CountryCode` FROM `cities`");
        $country_list = array();
        foreach ($countries as $key => $country) {
            $found = false;
            foreach ($country_list as $c_key => $c_value) {
                if ($c_value['CountryCode'] == $country->CountryCode) {
                    $found = true;
                }
            }

            if (!$found) {
                array_push($country_list, array('Country' => $country->Country,
                    'CountryCode' => $country->CountryCode));
            }
        }
        return view('admin.discover.index')->with(['countries' => $country_list]);
    }

    public function showCities(Request $request) {
    	$country_code = $request->country_code;
    	$country = Cities::where('CountryCode', $country_code)->first();
    	$cities = Cities::where('CountryCode', $country_code)
    			->orderBy('CityName')
                ->get(array('id', 'CityName', 'CityId', 'Country', 'CountryCode', 'image', 'isFeatured'));

        if($request->isMethod('post')) {
        	$request_data = $request->all();
        	Cities::where('CountryCode', $request_data['country_code'])
        			->update(['isFeatured' => '0']);

        	$destinationPath = public_path('/uploads/featured_cities/');
            
        	foreach ($request_data['city'] as $key => $value) {
        		
        		if(isset($request_data['image'][$key])) {
	        		$image_name = '';
	                $media = $request_data['image'][$key];
	                $image_name = $key . time().'.'.$media->getClientOriginalExtension();
	                $media->move($destinationPath, $image_name);
            		
                    Cities::where('id', $key)
            			->update(['isFeatured' => '1', 'image' => $image_name]);
	            } else {
                    Cities::where('id', $key)
                        ->update(['isFeatured' => '1']);
                }
        	}
        	
        	return redirect('/admin/discover')->with('success', 'Data updated Successfully.');
        }
        return view('admin.discover.cities')->with(['cities' => $cities, 'country' => $country]);
    }
}