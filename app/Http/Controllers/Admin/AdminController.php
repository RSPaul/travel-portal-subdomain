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
use File;

use DB;
use Mail;
use App\Models\Activities;
use App\Models\Cities;
use App\Models\Cruises;
use App\Models\Cabs;
use App\Models\User;
use App\Models\Currencies;
use App\Models\Packages;
use App\Models\HotelInfos;
use App\Models\RoomImages;
use App\Models\MetaTags;
use App\Models\MainBookings;

class AdminController extends Controller
{   
    private $sub_domain;
    private $domainData;
    private $hotel_code;
    //
    public function __construct(Request $request) {
        $this->middleware(['auth', 'isAdmin'], array('except' => array('login')));
        list($subdomain) = explode('.', $request->getHost(), 2);  

        if($subdomain == 'www') {
            $subdomain = explode('.', $request->getHost())[1];
        }        
        
        $this->sub_domain = $subdomain;
        $this->domainData = User::where('domain', $this->sub_domain)->first();
        $this->hotel_code = $this->domainData['hotel_code'];
        Session::put('domainData', $this->domainData);
        
    }

    public function login(Request $request) {

        // list($subdomain) = explode('.', $request->getHost(), 2);       
        
        // $this->sub_domain = $subdomain;
        $this->domainData = User::where('domain', $this->sub_domain)->first();
        Session::put('domainData', $this->domainData);
        
        if($request->isMethod('post')) {
            $input = $request->all();
            $user = User::where(['email' => $input['email'], 'domain' => $this->sub_domain])->first();
            if(empty($user)) {
                Session::flash('error', 'Invalid login credentials.');
                return view('admin.login')->with('error', 'Invalid login credentials.');
            }
            if(Hash::check($input['password'], $user->password)) {
                Auth::login($user);
                return redirect('/admin');
            } else {
                Session::flash('error', 'Invalid login credentials.');
                return view('admin.login')->with('error', 'Invalid login credentials.');
            }
        }
        return view('admin.login');
    }

    public function dashboard(Request $request) {

    	return view('admin.index');
    }

    public function activities(Request $request) {
    	$activities = Activities::where('sub_domain', $this->sub_domain)->get();
    	//$sub_domains = Cities::whereNotNull('HotelName')->get();	
    	return view('admin.activities')->with(['activities' => $activities]);
    }

    public function addActivity(Request $request) {
    	//$sub_domains = Cities::whereNotNull('HotelName')->get();
    	
    	if($request->isMethod('post')) {
    		$input = $request->only('sub_domain', 'name', 'short_description', 'description', 'currency', 'price', 'main_image', 'images');
            $destinationPath = public_path('/uploads/activities');
            //upload main image
            $image = $request->file('main_image');
            $main_image = time().'.'.$image->getClientOriginalExtension();
            $image->move($destinationPath, $main_image);

            $input['main_image'] = $main_image;

            $slider_images = array();
            //slider images            
            for($i = 0; $i < sizeof($request->images); $i++) {
                $image_name = '';
                $media = $request->images[$i];
                $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                $media->move($destinationPath, $image_name);

                array_push($slider_images, $image_name);
            }       	

            try {

                $input['sub_domain'] = $this->sub_domain;
                $input['images'] = serialize($slider_images);
            	Activities::create($input);	

            	return redirect('/admin/activities')->with('success', 'Activity added Successfully.');

            } catch(Exception $e) {

            	return redirect('/admin/activity/add')->with('error', $e->getMessage());
            }
            
        }
    	return view('admin.add-activity');
    }

    public function editActivity(Request $request) {
    	
        $activity = Activities::where(['id' => $request->id])->first();	
        $activity['images'] = unserialize($activity['images']);
        
        if($request->isMethod('post')) {

           $input = $request->only('sub_domain', 'name', 'short_description', 'description', 'currency', 'price', 'main_image', 'images');
    		$destinationPath = public_path('/uploads/activities');
    		//upload images
    		if(isset($request->main_image)) {
                $image = $request->file('main_image');
                $main_image = time().'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath, $main_image);

                $input['main_image'] = $main_image;
            } else {
            	$input['main_image'] = $request->hidden_main_image;
            }	

            $slider_images = array();
            //slider images            
            $slider_image_count = (isset($request->slider_image_count) && $request->slider_image_count > 0) ? $request->slider_image_count : sizeof($request->images);
            for($i = 0; $i < $slider_image_count; $i++) {
                $image_name = '';
                if(isset($request->images[$i])) {

                    $media = $request->images[$i];
                    $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                    $media->move($destinationPath, $image_name);

                } else {

                    $image_name = $request->hidden_images[$i];
                }

                array_push($slider_images, $image_name);
            }
            try {

                $input['sub_domain'] = $this->sub_domain;
                $input['images'] = serialize($slider_images);
            	Activities::where(['id' => $request->id])
            				->update($input);	

            	return redirect('/admin/activities')->with('success', 'Activity updated Successfully.');

            } catch(Exception $e) {

            	return redirect('/admin/activity/edit/' . $request->id)->with('error', $e->getMessage());
            }
        }
    	return view('admin.edit-activity')->with(['activity' => $activity]);
    }

    public function deleteActivity(Request $request) {

        if(Auth::check() && Auth::user()->role == 'admin') {
            Activities::where(['id' => $request->id])
                            ->delete();   
            return redirect('/admin/activities')->with('success', 'Activity deleted Successfully.');

        }
    	return view('admin.activities');
    }


    public function cruises() {
    	$cruises = Cruises::where('sub_domain', $this->sub_domain)->get();
    	return view('admin.cruises')->with(['cruises' => $cruises]);
    }

    public function addCruis(Request $request) {
    	
    	if($request->isMethod('post')) {
    		$input = $request->only('sub_domain', 'name', 'description', 'currency', 'price', 'main_image', 'images');
            $destinationPath = public_path('/uploads/cruises');
            //upload main image
            $image = $request->file('main_image');
            $main_image = time().'.'.$image->getClientOriginalExtension();
            $image->move($destinationPath, $main_image);

            $input['main_image'] = $main_image;

            $slider_images = array();
            //slider images            
            for($i = 0; $i < sizeof($request->images); $i++) {
                $image_name = '';
                $media = $request->images[$i];
                $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                $media->move($destinationPath, $image_name);

                array_push($slider_images, $image_name);
            }      	

            try {

                $input['sub_domain'] = $this->sub_domain;
                $input['images'] = serialize($slider_images);
            	Cruises::create($input);	

            	return redirect('/admin/cruises')->with('success', 'Cruise added Successfully.');

            } catch(Exception $e) {

            	return redirect('/admin/cruise/add')->with('error', $e->getMessage());
            }
            
        }
    	return view('admin.add-cruise');
    }

    public function editCruis(Request $request) {
    	
    	$cruise = Cruises::where(['id' => $request->id])->first();	
        $cruise['images'] = unserialize($cruise['images']);

    	if($request->isMethod('post')) {
    		
    		$input = $request->only('sub_domain', 'name', 'description', 'currency', 'price', 'main_image', 'images');
            $destinationPath = public_path('/uploads/cruises');
            //upload images
            if(isset($request->main_image)) {
                $image = $request->file('main_image');
                $main_image = time().'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath, $main_image);

                $input['main_image'] = $main_image;
            } else {
                $input['main_image'] = $request->hidden_main_image;
            }   

            $slider_images = array();
            //slider images            
            $slider_image_count = (isset($request->slider_image_count) && $request->slider_image_count > 0) ? $request->slider_image_count : sizeof($request->images);
            for($i = 0; $i < $slider_image_count; $i++) {
                $image_name = '';
                if(isset($request->images[$i])) {

                    $media = $request->images[$i];
                    $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                    $media->move($destinationPath, $image_name);

                } else {

                    $image_name = $request->hidden_images[$i];
                }

                array_push($slider_images, $image_name);
            }

            try {
                $input['sub_domain'] = $this->sub_domain;
                $input['images'] = serialize($slider_images);
            	Cruises::where(['id' => $request->id])
            				->update($input);	

            	return redirect('/admin/cruises')->with('success', 'Cruise updated Successfully.');

            } catch(Exception $e) {

            	return redirect('/admin/cruise/edit/' . $request->id)->with('error', $e->getMessage());
            }
        }
    	return view('admin.edit-cruise')->with(['cruise' => $cruise]);
    }

    public function deleteCruis(Request $request) {

    	if(Auth::check() && Auth::user()->role == 'admin') {
            Cruises::where(['id' => $request->id])
                            ->delete();   
            return redirect('/admin/cruises')->with('success', 'Cruise deleted Successfully.');

        }
        return view('admin.cruises');
    }


    public function packages() {
        $packages = Packages::where('sub_domain', $this->sub_domain)->get();
        return view('admin.packages')->with(['packages' => $packages]);
    }

    public function addPackage(Request $request) {
        $sub_domains = Cities::whereNotNull('HotelName')->get();
        
        if($request->isMethod('post')) {
            $input = $request->only('sub_domain', 'name', 'description', 'currency', 'price', 'main_image', 'images');
            $destinationPath = public_path('/uploads/packages');
            //upload main image
            $image = $request->file('main_image');
            $main_image = time().'.'.$image->getClientOriginalExtension();
            $image->move($destinationPath, $main_image);

            $input['main_image'] = $main_image;

            $slider_images = array();
            //slider images            
            for($i = 0; $i < sizeof($request->images); $i++) {
                $image_name = '';
                $media = $request->images[$i];
                $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                $media->move($destinationPath, $image_name);

                array_push($slider_images, $image_name);
            }     

            try {
                $input['sub_domain'] = $this->sub_domain;
                $input['images'] = serialize($slider_images);
                Packages::create($input);    

                return redirect('/admin/packages')->with('success', 'Package added Successfully.');

            } catch(Exception $e) {

                return redirect('/admin/package/add')->with('error', $e->getMessage());
            }
            
        }
        return view('admin.add-package');
    }

    public function editPackage(Request $request) {
       
        $package = Packages::where(['id' => $request->id])->first();  
        $package['images'] = unserialize($package['images']);

        if($request->isMethod('post')) {
            
            $input = $request->only('sub_domain', 'name', 'description', 'currency', 'price', 'main_image', 'images');
            $destinationPath = public_path('/uploads/cruises');
            //upload images
            if(isset($request->main_image)) {
                $image = $request->file('main_image');
                $main_image = time().'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath, $main_image);

                $input['main_image'] = $main_image;
            } else {
                $input['main_image'] = $request->hidden_main_image;
            }   

            $slider_images = array();
            //slider images            
            $slider_image_count = (isset($request->slider_image_count) && $request->slider_image_count > 0) ? $request->slider_image_count : sizeof($request->images);
            for($i = 0; $i < $slider_image_count; $i++) {
                $image_name = '';
                if(isset($request->images[$i])) {

                    $media = $request->images[$i];
                    $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                    $media->move($destinationPath, $image_name);

                } else {

                    $image_name = $request->hidden_images[$i];
                }

                array_push($slider_images, $image_name);
            }  

            try {

                $input['sub_domain'] = $this->sub_domain;
                $input['images'] = serialize($slider_images);
                Packages::where(['id' => $request->id])
                            ->update($input);   

                return redirect('/admin/packages')->with('success', 'Package updated Successfully.');

            } catch(Exception $e) {

                return redirect('/admin/package/edit/' . $request->id)->with('error', $e->getMessage());
            }
        }
        return view('admin.edit-package')->with(['package' => $package]);
    }

    public function deletePackage(Request $request) {

        if(Auth::check() && Auth::user()->role == 'admin') {
            Packages::where(['id' => $request->id])
                            ->delete();   
            return redirect('/admin/cruises')->with('success', 'Package deleted Successfully.');

        }
        return view('admin.packages');
    }

    public function profile (Request $request) {
        $currencies = Currencies::all();
        $user = User::where('id', Auth::user()->id)->first();
        if($request->isMethod('post')) {
            
            $input = $request->only('name', 'phone', 'currency', 'country', 'logo', 'password', 'confirm_password', 'address', 'fb_link', 'twitter_link', 'insta_link', 'you_link');
            $destinationPath = public_path('/uploads/website_logo');
            //check password
            if($input['password'] != '') {
                
                if($input['password'] == $input['confirm_password']) {

                    User::where('id', Auth::user()->id)
                        ->update(['password' => Hash::make($input['password'])]);

                        if(isset($request->logo)) {
                            $image = $request->file('logo');
                            $logo = time().'.'.$image->getClientOriginalExtension();
                            $image->move($destinationPath, $logo);
                            $input['logo'] = $logo;
                        } else {
                            $input['logo'] = $request->hidden_logo;
                        }

                        User::where('id', Auth::user()->id)
                                ->update(['name' => $input['name'],
                                        'currency' => $input['currency'],
                                        'country' => $input['country'],
                                        'phone' => $input['phone'],
                                        'logo' => $input['logo'],
                                        'address' => $input['address'],
                                        'fb_link' => $input['fb_link'],
                                        'twitter_link' => $input['twitter_link'],
                                        'insta_link' => $input['insta_link'],
                                        'you_link' => $input['you_link']]);

                } else {
                    //Session::flash('error', 'Password and confirm password not matched.');
                    return redirect('/admin/profile')->with('error', 'Password and confirm password not matched.');
                }
            } else {

                if(isset($request->logo)) {
                    $image = $request->file('logo');
                    $logo = time().'.'.$image->getClientOriginalExtension();
                    $image->move($destinationPath, $logo);
                    $input['logo'] = $logo;
                } else {
                    $input['logo'] = $request->hidden_logo;
                }

                User::where('id', Auth::user()->id)
                        ->update(['name' => $input['name'],
                                'currency' => $input['currency'],
                                'country' => $input['country'],
                                'phone' => $input['phone'],
                                'logo' => $input['logo'],
                                'address' => $input['address'],
                                'fb_link' => $input['fb_link'],
                                'twitter_link' => $input['twitter_link'],
                                'insta_link' => $input['insta_link'],
                                'you_link' => $input['you_link']]);
            }

            return redirect('/admin/profile')->with('success', 'Password details updated successfully.');
        }
        return view('admin.profile')->with(['user' => $user, 'currencies' => $currencies]);   
    }

    public function cabs(Request $request) {
        $cabs = Cabs::where('sub_domain', $this->sub_domain)->get();
        return view('admin.cabs')->with(['cabs' => $cabs]);
    }

    public function addCab(Request $request) {
        
        if($request->isMethod('post')) {
            $input = $request->only('sub_domain', 'name', 'short_description', 'description', 'currency', 'price', 'main_image', 'images');
            $destinationPath = public_path('/uploads/cabs');
            //upload main image
            $image = $request->file('main_image');
            $main_image = time().'.'.$image->getClientOriginalExtension();
            $image->move($destinationPath, $main_image);

            $input['main_image'] = $main_image;

            $slider_images = array();
            //slider images            
            for($i = 0; $i < sizeof($request->images); $i++) {
                $image_name = '';
                $media = $request->images[$i];
                $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                $media->move($destinationPath, $image_name);

                array_push($slider_images, $image_name);
            }           

            try {

                $input['sub_domain'] = $this->sub_domain;
                $input['images'] = serialize($slider_images);
                Cabs::create($input); 

                return redirect('/admin/cabs')->with('success', 'Cab added Successfully.');

            } catch(Exception $e) {

                return redirect('/admin/cab/add')->with('error', $e->getMessage());
            }
            
        }
        return view('admin.add-cab');
    }

    public function editCab(Request $request) {
        
        $cab = Cabs::where(['id' => $request->id])->first(); 
        $cab['images'] = unserialize($cab['images']);
        
        if($request->isMethod('post')) {

           $input = $request->only('sub_domain', 'name', 'short_description', 'description', 'currency', 'price', 'main_image', 'images');
            $destinationPath = public_path('/uploads/cabs');
            //upload images
            if(isset($request->main_image)) {
                $image = $request->file('main_image');
                $main_image = time().'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath, $main_image);

                $input['main_image'] = $main_image;
            } else {
                $input['main_image'] = $request->hidden_main_image;
            }   

            $slider_images = array();
            //slider images            
            $slider_image_count = (isset($request->slider_image_count) && $request->slider_image_count > 0) ? $request->slider_image_count : sizeof($request->images);
            for($i = 0; $i < $slider_image_count; $i++) {
                $image_name = '';
                if(isset($request->images[$i])) {

                    $media = $request->images[$i];
                    $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                    $media->move($destinationPath, $image_name);

                } else {

                    $image_name = $request->hidden_images[$i];
                }

                array_push($slider_images, $image_name);
            }
            try {

                $input['sub_domain'] = $this->sub_domain;
                $input['images'] = serialize($slider_images);
                Cabs::where(['id' => $request->id])
                            ->update($input);   

                return redirect('/admin/cabs')->with('success', 'Cab updated Successfully.');

            } catch(Exception $e) {

                return redirect('/admin/cab/edit/' . $request->id)->with('error', $e->getMessage());
            }
        }
        return view('admin.edit-cab')->with(['cab' => $cab]);
    }

    public function deleteCab(Request $request) {

        if(Auth::check() && Auth::user()->role == 'admin') {
            Cabs::where(['id' => $request->id])
                            ->delete();   
            return redirect('/admin/cabs')->with('success', 'Cab deleted Successfully.');

        }
        return view('admin.cabs');
    }

    public function hotelDetails(Request $request) {
        if(Auth::check() && Auth::user()->role == 'admin') {
            $details = HotelInfos::where('sub_domain', $this->sub_domain)->first();
            $id = isset($details['id']) ? $details['id'] : '';
            if(empty($details)) {
                $details = new HotelInfos();
            } else {
                $details['slider_images']  = unserialize($details['slider_images']);
            }

            if($request->isMethod('post')) {

               $input = $request->only('id', 'sub_domain', 'name', 'tag_line', 'description', 'main_image', 'slider_images', 'tag_line2', 'description2', 'tag_line3', 'description3', 'lat', 'lng');
                $destinationPath = public_path('/uploads/hotel');
                //upload images
                if(isset($request->main_image)) {
                    $image = $request->file('main_image');
                    $main_image = time().'.'.$image->getClientOriginalExtension();
                    $image->move($destinationPath, $main_image);

                    $input['main_image'] = $main_image;
                } else {
                    $input['main_image'] = $request->hidden_main_image;
                }   

                $slider_images = array();
                //slider images            
                $slider_image_count = (isset($request->slider_image_count) && $request->slider_image_count > 0) ? $request->slider_image_count : sizeof($request->slider_images);
                for($i = 0; $i < $slider_image_count; $i++) {
                    $image_name = '';
                    if(isset($request->slider_images[$i])) {

                        $media = $request->slider_images[$i];
                        $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                        $media->move($destinationPath, $image_name);

                    } else {

                        $image_name = $request->hidden_images[$i];
                    }

                    array_push($slider_images, $image_name);
                }

                try {

                    $input['sub_domain'] = $this->sub_domain;
                    $input['slider_images'] = serialize($slider_images);
                    
                    if(isset($input['id']) && $input['id'] !='' ) {
                        $hotel_id = $input['id'];
                        unset($input['id']);
                        unset($input['updated_at']);
                        HotelInfos::where(['id' => $hotel_id])
                                    ->update($input);   
                    } else {                
                        HotelInfos::create($input);        
                    }

                    return redirect('/admin/hotel/details')->with('success', 'Details updated Successfully.');

                } catch(Exception $e) {

                    return redirect('/admin/hotel/details')->with('error', $e->getMessage());
                }
            }
            return view('admin.hotel-details')->with(['details' => $details, 'id' => $id]);
        }
    }

    public function roomImages() {
        $images = RoomImages::where('sub_domain', $this->sub_domain)->get();
        return view('admin.room-images')->with(['images' => $images]);
    }

    public function editRoomImages(Request $request) {
        $image = RoomImages::where('id', $request->id)->first();
        $image['images'] = unserialize($image['images']);
        list($subdomain) = explode('.', $request->getHost(), 2); 
        $rooms = RoomImages::where('sub_domain', $this->sub_domain)->get();
        if($request->isMethod('post')) {
            $input = $request->all();

            $path = public_path('/uploads/rooms/' . $subdomain . '/');

            if(!File::isDirectory($path)){

                File::makeDirectory($path, 0777, true, true);

            }

            $destinationPath = $path;
            
            $room_images = array();
            //slider images            
            if(isset($request->room_images)) {
               $images_size = sizeof($request->room_images);
            } else {
                $images_size = 0;
            }

            $room_image_count = (isset($request->room_image_count) && $request->room_image_count > 0) ? $request->room_image_count : $images_size;

            for($i = 0; $i < $room_image_count; $i++) {
                $image_name = '';
                if(isset($request->room_images[$i])) {

                    $media = $request->room_images[$i];
                    $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                    $media->move($destinationPath, $image_name);

                } else {

                    $image_name = $request->hidden_images[$i];
                }

                array_push($room_images, $image_name);
            }
            if(sizeof($room_images) > 0) {
                $images_to_save = serialize($room_images);
            } else {
                $images_to_save = '';
            }
            RoomImages::where('id', $request->id)
                        ->update(['images' => $images_to_save,
                                'parent_room_id' => $input['parent_room_id']]);

            return redirect('/admin/hotel-room-images')->with('success', 'Details updated Successfully.');
        }
        return view('admin.edit-images')->with(['image' => $image, 'subdomain' => $subdomain, 'rooms' => $rooms]);
    }

    public function metaTags(Request $request) {

        list($subdomain) = explode('.', $request->getHost(), 2); 
        $tags = MetaTags::where('sub_domain', $this->sub_domain)->first();
        $input = $request->only('sub_domain', 'title', 'description', 'keywords', 'author', 'viewport', 'robots', 'canonical', 'view_id', 'google_site_verification', 'google_analytics_code');
        if(empty($tags)) {
            $tags = new MetaTags();
        }
        if($request->isMethod('post')) {

            if(isset($tags) && isset($tags['id'])) {
                MetaTags::where('sub_domain', $this->sub_domain)
                        ->update($input);
            } else {
                $input['sub_domain'] = $this->sub_domain;
                MetaTags::create($input);
            }
            return redirect('/admin/meta')->with('success', 'Details updated Successfully.');
        }
        return view('admin.meta')->with(['tags' => $tags]);
    }

    public function bookings(Request $request) {

        $bookings = MainBookings::where('hotel_code', $this->hotel_code)->get();
        return view('admin.bookings')->with(['bookings' => $bookings]);
    }
}
