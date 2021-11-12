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
use App\Models\AffiliateUsers;
use App\Services\TBOFlightAPI;
use App\Models\Packages;
use App\Models\HotelInfos;
use App\Models\RoomImages;
use App\Models\StaticDataHotels;
use App\Models\TransferCities;
use App\Models\WebVideos;
use App\Models\FlightBookings;
use App\Models\FlightPayments;
use App\Mail\FlightBookingEmail;
use PDF;

use App\Services\TBOHotelAPI;
use Carbon\Carbon;

use App\Mail\AgentRegisterEmail;
use App\Mail\Admin\AgentAccountStatusEmail;

class AdminController extends Controller
{   
    private $sub_domain;
    private $domainData;
    //
    public function __construct(Request $request) {
        $this->middleware(['auth', 'isAdmin'], array('except' => array('login')));
        
    }

    public function login(Request $request) {

        if($request->isMethod('post')) {
            $input = $request->all();
            $user = User::where(['email' => $input['email']])->first();
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

    /* Agents Menu */

    public function agents(){
        $users = DB::table('users')
            ->join('affiliate_users', 'users.id', '=', 'affiliate_users.user_id')
            ->select('users.*', 'affiliate_users.referal_code','affiliate_users.commission')
            ->get();
        return view('admin.agents.agents')->with(['users' => $users]);    

    } 

    public function addAgent(Request $request) {
        
        if($request->isMethod('post')) {

            $input = $request->all();
            $temp_password = $this->generateRandomString();
            $randomString = $this->generateRandomString();
            $checkUser = User::where(['email' => $input['email']])->first();
            if(isset($checkUser)){
                Session::flash('error', 'Email already exists, please use another email.');
            }else{

                $input['password'] = Hash::make($temp_password);
                $addAgent = User::create($input);

                $aff['user_id'] = $addAgent['id'];
                $aff['referal_code'] = $input['referal_code'];
                $aff['commission'] = $input['commission'];
                $checkrefferal = AffiliateUsers::where(['referal_code' => $aff['referal_code']])->first();

                if(empty($checkrefferal)) {

                    AffiliateUsers::create($aff);

                }else{

                    
                    $affili['user_id'] = $addAgent['id'];
                    $affili['referal_code'] = $randomString;
                    $affili['commission'] = $input['commission'];
                    AffiliateUsers::create($affili); 

                }              

                $link = env('APP_URL') . '/login';
                //send email
                try {
                    
                    $file_path = public_path('AffiliateProgramAgreement.pdf');
                    Mail::to($input['email'])->send(new AgentRegisterEmail($addAgent, $temp_password, $link, $file_path));
                    Session::flash('success', 'Agent added Successfully.');
                    return redirect('/admin/agents');//->with('success', 'Agent added Successfully.');

                } catch(Exception $e) {
                    Session::flash('error', $e->getMessage());
                   // return redirect('/admin/agent/add');
                }
            }            
            
        }

        $randomString = $this->generateRandomString();

        return view('admin.agents.add-agent')->with(['code' => $randomString]);

    }

    public function editAgent(Request $request){

        $id = $request->id;
         
        if($request->isMethod('post')) {

            $user_data = $request->all();
            $input = $request->only('commission');
            
            AffiliateUsers::where(['user_id' => $request->id])
                                ->update($input);

            User::where(['id' => $request->id])
                                ->update(['status' => $user_data['status']]);

            //return redirect('/admin/agents');
            $link = env('APP_URL') . '/login';
            if($user_data['status'] == '1') {
                $status = 'Activated';
                Session::flash('success', 'Agent account is activated.');
            } else {
                $status = 'Deactivated';
                Session::flash('success', 'Agent account is deactivateds.');
            }
            //send email to agent     
            Mail::to($user_data['email'])->send(new AgentAccountStatusEmail($user_data,$link,$status));
        }

        $users = DB::table('users')
            ->join('affiliate_users', 'users.id', '=', 'affiliate_users.user_id')
            ->select('users.*', 'affiliate_users.referal_code','affiliate_users.commission')
            ->where(['users.id' => $id])
            ->first();
        
        return view('admin.agents.edit-agent')->with(['users' => $users]);   
    }   

    public function deleteAgent(Request $request) {

        if(Auth::check() && Auth::user()->role == 'admin') {
            User::where(['id' => $request->id])
                            ->delete();  
            AffiliateUsers::where(['user_id' => $request->id])
                            ->delete();   
            return redirect('/admin/agents')->with('success', 'Agent deleted Successfully.');

        }
        return view('admin.agents.agents');
    }

    public function saveWeekendImage(Request $request){



        if($request->isMethod('post')) {

            $search_id = 'weekend_images';
            $search_contents = json_decode($this->readSearchData($search_id.'.json'), true);

            //$saveImage['web_image']    = $search_contents['weekend_image'];

            if(isset($search_contents['web_image'])){
                
                $saveImage['web_image'] = $search_contents['web_image'];

            }else{

                $saveImage['web_image'] = '';
            }

            if(isset($search_contents['mobile_image'])){
                
                $saveImage['mobile_image'] = $search_contents['mobile_image'];

            }else{

                $saveImage['mobile_image'] = '';
            }

            $input = $request->only('weekend_image','weekend_image_mobile', 'coming_soon_mode', 'banner_time');
            $saveImage['coming_soon_mode']   = $input['coming_soon_mode'];
            $saveImage['banner_time']   = $input['banner_time'];

            $destinationPath = public_path('/uploads/weekend_images');

            if(isset($request->weekend_image)) {

                $image = $request->file('weekend_image');
                $logo = time().'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath, $logo);
                $input['weekend_image'] = $logo;

                $saveImage['web_image']   = $input['weekend_image'];
                $saveImage['coming_soon_mode']   = $input['coming_soon_mode'];

                $fileName = 'weekend_images';

                $fileContents = json_encode($saveImage);

                $this->saveSearchData($fileName . '.json', $fileContents);
            }

            if(isset($request->weekend_image_mobile)) {

                $image = $request->file('weekend_image_mobile');
                $logo = time().'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath, $logo);
                $input['weekend_image_mobile'] = $logo;

                $saveImage['mobile_image']  = $input['weekend_image_mobile'];
                $saveImage['coming_soon_mode']   = $input['coming_soon_mode'];

                $fileName = 'weekend_images';

                $fileContents = json_encode($saveImage);

                $this->saveSearchData($fileName . '.json', $fileContents);


            }

            $fileName = 'weekend_images';
            $fileContents = json_encode($saveImage);
            $this->saveSearchData($fileName . '.json', $fileContents);

            return redirect('/admin/profile')->with('success', 'Image Uploaded Successfully.');

        }

    }

    public function saveSearchData($file, $content) {
        $destinationPath=public_path()."/logs/searches/weekend_image/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$content);
    }

    public function readSearchData($file) {
        $destinationPath=public_path()."/logs/searches/weekend_image/";
        if(file_exists($destinationPath)) {
            return $file = File::get($destinationPath.$file);
        } else {
            return null;
        }
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
                                        'country' => $input['country'],
                                        'phone' => $input['phone'],
                                        'address' => $input['address']]);

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
                                'country' => $input['country'],
                                'phone' => $input['phone'],
                                'address' => $input['address']]);
            }

            return redirect('/admin/profile')->with('success', 'Password details updated successfully.');
        }

        $search_id = 'weekend_images';
        $weekend_images = json_decode($this->readSearchData($search_id.'.json'), true);

        return view('admin.profile')->with(['user' => $user, 'currencies' => $currencies, 'weekend_images' => $weekend_images]);   
    }

    /* Get Flight Bookings */

    public function flightBooking (Request $request) {
        $currencies = Currencies::all();
        $user = User::where('id', Auth::user()->id)->first();

        if($request->isMethod('post')) {

            $input = $request->all();

            $this->flightapi = new TBOFlightAPI();


            $booking_id = $input['booking_id'];

            $booking = FlightBookings::where('booking_id', $booking_id)->get();
            $booking = $booking[0];

            

            $payments = FlightPayments::where('booking_id', $booking->id)->get();

            if(isset($payments[0])){

                $payments = $payments[0];

            }else{
                $payments = array();
            }
            
           // print_r($payments);die;


            $bookFlightArr = array('BookingId' => $booking->booking_id,
                "EndUserIp" => $this->flightapi->userIP,
                "TokenId" => $booking->token_id,
                "PNR" => $booking->pnr
            );


            $booking->request_data = json_decode($booking->request_data, true);

            $emailUser = $booking->request_data['bookingData']['Passengers'][0]['Email'];

            if(empty($emailUser)  || $emailUser == '' ){
                $user_id = $booking->user_id;
                $user = User::where(['id' => $user_id])->first();

                $emailUser = $user['email'];
            }

            $this->bookingResultNOLCC = $this->flightapi->GetBookingDetails($bookFlightArr);

            if (isset($this->bookingResultNOLCC['Response']) && isset($this->bookingResultNOLCC['Response']['ResponseStatus']) && $this->bookingResultNOLCC['Response']['ResponseStatus'] == 1) {

                $bookingDetailsNOLCC = $this->bookingResultNOLCC['Response'];

                //echo "<pre>";print_r($bookingDetailsNOLCC);die;

                $segments = $bookingDetailsNOLCC['FlightItinerary']['Segments'];
                $farerules = $bookingDetailsNOLCC['FlightItinerary']['FareRules'];

                $pdf = PDF::loadView('emails.pdf.flight-booking', compact('booking', 'payments', 'segments', 'farerules'));
                $pdf->save(public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf'));
                $path = public_path('e-tickets/flight/e-Ticket-' . $booking->booking_id . '.pdf');
                $file_name = 'e-Ticket-' . $booking->booking_id . '.pdf';

                Mail::to($emailUser)->send(new FlightBookingEmail($booking, $payments, $segments, $farerules, $path, $file_name));


                return redirect('/admin/flight-booking')->with('success', 'Details sent successfully.');
            }else{
                return redirect('/admin/flight-booking')->with('error', 'Error Sending Ticket.');
            }


        }

        return view('admin.flight-booking')->with(['user' => $user, 'currencies' => $currencies]);  
    } 

    public function webVideos(Request $request) {
        $videos = WebVideos::orderBy('created_at', 'DESC')->get();
        return view('admin.web-videos.index')->with('videos', $videos);
    }

    public function addVideo(Request $request) {

        if($request->isMethod('post')) {

            $input = $request->only('title', 'description', 'media_link', 'status');
            WebVideos::create($input);
            return redirect('/admin/web-videos')->with('success', 'Video added successfully.');
        }
        return view('admin.web-videos.add');
    }

    public function editVideo(Request $request) {

        if($request->isMethod('post')) {

            $input = $request->only('title', 'description', 'media_link', 'status');
            WebVideos::where('id', $request->id)
                        ->update($input);
            return redirect('/admin/web-videos')->with('success', 'Video added successfully.');
        }

        $video = WebVideos::where('id', $request->id)->first();
        return view('admin.web-videos.edit')->with('video',$video);
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // public function activities(Request $request) {
    // 	$activities = Activities::all();
    // 	return view('admin.activities.activities')->with(['activities' => $activities]);
    // }

    // public function addActivity(Request $request) {
    	
    // 	if($request->isMethod('post')) {
    // 		$input = $request->only('sub_domain', 'name', 'short_description', 'description', 'currency', 'price', 'main_image', 'images');
    //         $destinationPath = public_path('/uploads/activities');
    //         //upload main image
    //         $image = $request->file('main_image');
    //         $main_image = time().'.'.$image->getClientOriginalExtension();
    //         $image->move($destinationPath, $main_image);

    //         $input['main_image'] = $main_image;

    //         $slider_images = array();
    //         //slider images            
    //         for($i = 0; $i < sizeof($request->images); $i++) {
    //             $image_name = '';
    //             $media = $request->images[$i];
    //             $image_name = $i . time().'.'.$media->getClientOriginalExtension();
    //             $media->move($destinationPath, $image_name);

    //             array_push($slider_images, $image_name);
    //         }       	

    //         try {

    //             $input['sub_domain'] = 'main';
    //             $input['images'] = serialize($slider_images);
    //         	Activities::create($input);	

    //         	return redirect('/admin/activities')->with('success', 'Activity added Successfully.');

    //         } catch(Exception $e) {

    //         	return redirect('/admin/activity/add')->with('error', $e->getMessage());
    //         }
            
    //     }
    // 	return view('admin.activities.add-activity');
    // }

    // public function editActivity(Request $request) {
    	
    //     $activity = Activities::where(['id' => $request->id])->first();	
    //     $activity['images'] = unserialize($activity['images']);
        
    //     if($request->isMethod('post')) {

    //        $input = $request->only('sub_domain', 'name', 'short_description', 'description', 'currency', 'price', 'main_image', 'images');
    // 		$destinationPath = public_path('/uploads/activities');
    // 		//upload images
    // 		if(isset($request->main_image)) {
    //             $image = $request->file('main_image');
    //             $main_image = time().'.'.$image->getClientOriginalExtension();
    //             $image->move($destinationPath, $main_image);

    //             $input['main_image'] = $main_image;
    //         } else {
    //         	$input['main_image'] = $request->hidden_main_image;
    //         }	

    //         $slider_images = array();
    //         //slider images            
    //         $slider_image_count = (isset($request->slider_image_count) && $request->slider_image_count > 0) ? $request->slider_image_count : sizeof($request->images);
    //         for($i = 0; $i < $slider_image_count; $i++) {
    //             $image_name = '';
    //             if(isset($request->images[$i])) {

    //                 $media = $request->images[$i];
    //                 $image_name = $i . time().'.'.$media->getClientOriginalExtension();
    //                 $media->move($destinationPath, $image_name);

    //             } else {

    //                 $image_name = $request->hidden_images[$i];
    //             }

    //             array_push($slider_images, $image_name);
    //         }
    //         try {

    //             $input['sub_domain'] = 'main';
    //             $input['images'] = serialize($slider_images);
    //         	Activities::where(['id' => $request->id])
    //         				->update($input);	

    //         	return redirect('/admin/activities')->with('success', 'Activity updated Successfully.');

    //         } catch(Exception $e) {

    //         	return redirect('/admin/activity/edit/' . $request->id)->with('error', $e->getMessage());
    //         }
    //     }
    // 	return view('admin.activities.edit-activity')->with(['activity' => $activity]);
    // }

    // public function deleteActivity(Request $request) {

    //     if(Auth::check() && Auth::user()->role == 'admin') {
    //         Activities::where(['id' => $request->id])
    //                         ->delete();   
    //         return redirect('/admin/activities')->with('success', 'Activity deleted Successfully.');

    //     }
    // 	return view('admin.activities.activities');
    // }


    // public function cruises() {
    // 	$cruises = Cruises::all();
    // 	return view('admin.cruises.cruises')->with(['cruises' => $cruises]);
    // }

    // public function addCruis(Request $request) {
    	
    // 	if($request->isMethod('post')) {
    // 		$input = $request->only('sub_domain', 'name', 'description', 'currency', 'price', 'main_image', 'images');
    //         $destinationPath = public_path('/uploads/cruises');
    //         //upload main image
    //         $image = $request->file('main_image');
    //         $main_image = time().'.'.$image->getClientOriginalExtension();
    //         $image->move($destinationPath, $main_image);

    //         $input['main_image'] = $main_image;

    //         $slider_images = array();
    //         //slider images            
    //         for($i = 0; $i < sizeof($request->images); $i++) {
    //             $image_name = '';
    //             $media = $request->images[$i];
    //             $image_name = $i . time().'.'.$media->getClientOriginalExtension();
    //             $media->move($destinationPath, $image_name);

    //             array_push($slider_images, $image_name);
    //         }      	

    //         try {

    //             $input['sub_domain'] = 'main';
    //             $input['images'] = serialize($slider_images);
    //         	Cruises::create($input);	

    //         	return redirect('/admin/cruises')->with('success', 'Cruise added Successfully.');

    //         } catch(Exception $e) {

    //         	return redirect('/admin/cruise/add')->with('error', $e->getMessage());
    //         }
            
    //     }
    // 	return view('admin.cruises.add-cruise');
    // }

    // public function editCruis(Request $request) {
    	
    // 	$cruise = Cruises::where(['id' => $request->id])->first();	
    //     $cruise['images'] = unserialize($cruise['images']);

    // 	if($request->isMethod('post')) {
    		
    // 		$input = $request->only('sub_domain', 'name', 'description', 'currency', 'price', 'main_image', 'images');
    //         $destinationPath = public_path('/uploads/cruises');
    //         //upload images
    //         if(isset($request->main_image)) {
    //             $image = $request->file('main_image');
    //             $main_image = time().'.'.$image->getClientOriginalExtension();
    //             $image->move($destinationPath, $main_image);

    //             $input['main_image'] = $main_image;
    //         } else {
    //             $input['main_image'] = $request->hidden_main_image;
    //         }   

    //         $slider_images = array();
    //         //slider images            
    //         $slider_image_count = (isset($request->slider_image_count) && $request->slider_image_count > 0) ? $request->slider_image_count : sizeof($request->images);
    //         for($i = 0; $i < $slider_image_count; $i++) {
    //             $image_name = '';
    //             if(isset($request->images[$i])) {

    //                 $media = $request->images[$i];
    //                 $image_name = $i . time().'.'.$media->getClientOriginalExtension();
    //                 $media->move($destinationPath, $image_name);

    //             } else {

    //                 $image_name = $request->hidden_images[$i];
    //             }

    //             array_push($slider_images, $image_name);
    //         }

    //         try {
    //             $input['sub_domain'] = 'main';
    //             $input['images'] = serialize($slider_images);
    //         	Cruises::where(['id' => $request->id])
    //         				->update($input);	

    //         	return redirect('/admin/cruises')->with('success', 'Cruise updated Successfully.');

    //         } catch(Exception $e) {

    //         	return redirect('/admin/cruise/edit/' . $request->id)->with('error', $e->getMessage());
    //         }
    //     }
    // 	return view('admin.cruises.edit-cruise')->with(['cruise' => $cruise]);
    // }

    // public function deleteCruis(Request $request) {

    // 	if(Auth::check() && Auth::user()->role == 'admin') {
    //         Cruises::where(['id' => $request->id])
    //                         ->delete();   
    //         return redirect('/admin/cruises')->with('success', 'Cruise deleted Successfully.');

    //     }
    //     return view('admin.cruises.cruises');
    // }



    // public function packages() {
    //     $packages = Packages::where('sub_domain', $this->sub_domain)->get();
    //     return view('admin.packages.packages')->with(['packages' => $packages]);
    // }

    // public function addPackage(Request $request) {
    //     $sub_domains = Cities::whereNotNull('HotelName')->get();
        
    //     if($request->isMethod('post')) {
    //         $input = $request->only('sub_domain', 'name', 'description', 'currency', 'price', 'main_image', 'images');
    //         $destinationPath = public_path('/uploads/packages');
    //         //upload main image
    //         $image = $request->file('main_image');
    //         $main_image = time().'.'.$image->getClientOriginalExtension();
    //         $image->move($destinationPath, $main_image);

    //         $input['main_image'] = $main_image;

    //         $slider_images = array();
    //         //slider images            
    //         for($i = 0; $i < sizeof($request->images); $i++) {
    //             $image_name = '';
    //             $media = $request->images[$i];
    //             $image_name = $i . time().'.'.$media->getClientOriginalExtension();
    //             $media->move($destinationPath, $image_name);

    //             array_push($slider_images, $image_name);
    //         }     

    //         try {
    //             $input['sub_domain'] = $this->sub_domain;
    //             $input['images'] = serialize($slider_images);
    //             Packages::create($input);    

    //             return redirect('/admin/packages')->with('success', 'Package added Successfully.');

    //         } catch(Exception $e) {

    //             return redirect('/admin/package/add')->with('error', $e->getMessage());
    //         }
            
    //     }
    //     return view('admin.packages.add-package');
    // }

    // public function editPackage(Request $request) {
       
    //     $package = Packages::where(['id' => $request->id])->first();  
    //     $package['images'] = unserialize($package['images']);

    //     if($request->isMethod('post')) {
            
    //         $input = $request->only('sub_domain', 'name', 'description', 'currency', 'price', 'main_image', 'images');
    //         $destinationPath = public_path('/uploads/cruises');
    //         //upload images
    //         if(isset($request->main_image)) {
    //             $image = $request->file('main_image');
    //             $main_image = time().'.'.$image->getClientOriginalExtension();
    //             $image->move($destinationPath, $main_image);

    //             $input['main_image'] = $main_image;
    //         } else {
    //             $input['main_image'] = $request->hidden_main_image;
    //         }   

    //         $slider_images = array();
    //         //slider images            
    //         $slider_image_count = (isset($request->slider_image_count) && $request->slider_image_count > 0) ? $request->slider_image_count : sizeof($request->images);
    //         for($i = 0; $i < $slider_image_count; $i++) {
    //             $image_name = '';
    //             if(isset($request->images[$i])) {

    //                 $media = $request->images[$i];
    //                 $image_name = $i . time().'.'.$media->getClientOriginalExtension();
    //                 $media->move($destinationPath, $image_name);

    //             } else {

    //                 $image_name = $request->hidden_images[$i];
    //             }

    //             array_push($slider_images, $image_name);
    //         }  

    //         try {

    //             $input['sub_domain'] = 'main';
    //             $input['images'] = serialize($slider_images);
    //             Packages::where(['id' => $request->id])
    //                         ->update($input);   

    //             return redirect('/admin/packages')->with('success', 'Package updated Successfully.');

    //         } catch(Exception $e) {

    //             return redirect('/admin/package/edit/' . $request->id)->with('error', $e->getMessage());
    //         }
    //     }
    //     return view('admin.packages.edit-package')->with(['package' => $package]);
    // }

    // public function deletePackage(Request $request) {

    //     if(Auth::check() && Auth::user()->role == 'admin') {
    //         Packages::where(['id' => $request->id])
    //                         ->delete();   
    //         return redirect('/admin/cruises')->with('success', 'Package deleted Successfully.');

    //     }
    //     return view('admin.packages.packages');
    // }



    // public function cabs(Request $request) {
    //     $cabs = Cabs::where('sub_domain', $this->sub_domain)->get();
    //     return view('admin.cabs.cabs')->with(['cabs' => $cabs]);
    // }

    // public function addCab(Request $request) {
        
    //     if($request->isMethod('post')) {
    //         $input = $request->only('sub_domain', 'name', 'short_description', 'description', 'currency', 'price', 'main_image', 'images');
    //         $destinationPath = public_path('/uploads/cabs');
    //         //upload main image
    //         $image = $request->file('main_image');
    //         $main_image = time().'.'.$image->getClientOriginalExtension();
    //         $image->move($destinationPath, $main_image);

    //         $input['main_image'] = $main_image;

    //         $slider_images = array();
    //         //slider images            
    //         for($i = 0; $i < sizeof($request->images); $i++) {
    //             $image_name = '';
    //             $media = $request->images[$i];
    //             $image_name = $i . time().'.'.$media->getClientOriginalExtension();
    //             $media->move($destinationPath, $image_name);

    //             array_push($slider_images, $image_name);
    //         }           

    //         try {

    //             $input['sub_domain'] = 'main';
    //             $input['images'] = serialize($slider_images);
    //             Cabs::create($input); 

    //             return redirect('/admin/cabs')->with('success', 'Cab added Successfully.');

    //         } catch(Exception $e) {

    //             return redirect('/admin/cab/add')->with('error', $e->getMessage());
    //         }
            
    //     }
    //     return view('admin.cabs.add-cab');
    // }

    // public function editCab(Request $request) {
        
    //     $cab = Cabs::where(['id' => $request->id])->first(); 
    //     $cab['images'] = unserialize($cab['images']);
        
    //     if($request->isMethod('post')) {

    //        $input = $request->only('sub_domain', 'name', 'short_description', 'description', 'currency', 'price', 'main_image', 'images');
    //         $destinationPath = public_path('/uploads/cabs');
    //         //upload images
    //         if(isset($request->main_image)) {
    //             $image = $request->file('main_image');
    //             $main_image = time().'.'.$image->getClientOriginalExtension();
    //             $image->move($destinationPath, $main_image);

    //             $input['main_image'] = $main_image;
    //         } else {
    //             $input['main_image'] = $request->hidden_main_image;
    //         }   

    //         $slider_images = array();
    //         //slider images            
    //         $slider_image_count = (isset($request->slider_image_count) && $request->slider_image_count > 0) ? $request->slider_image_count : sizeof($request->images);
    //         for($i = 0; $i < $slider_image_count; $i++) {
    //             $image_name = '';
    //             if(isset($request->images[$i])) {

    //                 $media = $request->images[$i];
    //                 $image_name = $i . time().'.'.$media->getClientOriginalExtension();
    //                 $media->move($destinationPath, $image_name);

    //             } else {

    //                 $image_name = $request->hidden_images[$i];
    //             }

    //             array_push($slider_images, $image_name);
    //         }
    //         try {

    //             $input['sub_domain'] = 'main';
    //             $input['images'] = serialize($slider_images);
    //             Cabs::where(['id' => $request->id])
    //                         ->update($input);   

    //             return redirect('/admin/cabs')->with('success', 'Cab updated Successfully.');

    //         } catch(Exception $e) {

    //             return redirect('/admin/cab/edit/' . $request->id)->with('error', $e->getMessage());
    //         }
    //     }
    //     return view('admin.cabs.edit-cab')->with(['cab' => $cab]);
    // }

    // public function deleteCab(Request $request) {

    //     if(Auth::check() && Auth::user()->role == 'admin') {
    //         Cabs::where(['id' => $request->id])
    //                         ->delete();   
    //         return redirect('/admin/cabs')->with('success', 'Cab deleted Successfully.');

    //     }
    //     return view('admin.cabs.cabs');
    // }

    
    public function staticData(Request $request) {
      ini_set('max_execution_time', -1);
      $message = '';
      if($request->isMethod('post')) {        

        $api = new TBOHotelAPI();
        $data = $api->getCityData($request->CityId);  
        $destinationPath=public_path()."/logs/static-data/";
        $cityId = $request->CityId;

        $path = $destinationPath . "city-". $cityId ."_logs.xml"; 
      
        // Read entire file into string 
        $xmlfile = file_get_contents($path); 
        $xmlfile = str_replace("=\\", "=", $xmlfile);
        $xmlfile = str_replace('"<?xml', "<?xml", $xmlfile);
        $xmlfile = str_replace('ArrayOfBasicPropertyInfo>"', "ArrayOfBasicPropertyInfo>", $xmlfile);
        $xmlfile = str_replace('\"', '"', $xmlfile);
        $xmlfile = str_replace('\/', '/', $xmlfile);
        $xmlfile = str_replace('\r\n ', '', $xmlfile);
        $xmlfile = str_replace('utf-16', 'utf-8', $xmlfile);
        $xmlfile = str_replace('>\r\n<', '><', $xmlfile);

        $newXml = simplexml_load_string($xmlfile, 'SimpleXMLElement', LIBXML_NOCDATA); 
            
        // Convert into json 
        $arrayData = $this->xmlToArray($newXml);
        // echo "<pre>";
        
        foreach ($arrayData['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'] as $key => $hotel) {
            
            if(isset($hotel['@TBOHotelCode'])) {
                //check if exits
                $check = StaticDataHotels::where(['city_id' => $cityId, 'hotel_code' => $hotel['@TBOHotelCode']])->first();
                if(isset($check)) {
                    
                    StaticDataHotels::where(['city_id' => $cityId, 'hotel_code' => $hotel['@TBOHotelCode']])
                            ->update(['data_updated' => 0, 'start_rating' => $hotel['@BrandCode']]);
                } else {

                    StaticDataHotels::create(['hotel_name' => isset($hotel['@HotelName']) ? $hotel['@HotelName'] : '',
                           'hotel_code' => $hotel['@TBOHotelCode'],
                            'city_id' => $cityId,
                            'start_rating' => $hotel['@BrandCode'],
                            'data_updated' => 0]);
                }
            }
        }
        //die('dead');
       // return redirect('/admin/get-static-data')->with('success', );
        $cities = Cities::all();
        $message = 'City data imported successfully. You can proceed for hotel data now.';
        return view('admin.static-data.cities')->with(['cities' => $cities, 'message' => $message]);
      }
      $cities = Cities::all();
      return view('admin.static-data.cities')->with(['cities' => $cities, 'message' => $message]);
      
    }

    public function showHotels(Request $request) {

        if($request->isMethod('post')) {

            $pattern = "/" . $request->name . "/i";

            $hotels = DB::table('static_data_hotels as s_h')
                    ->join('cities as c', 'c.CityId', '=', 's_h.city_id')
                    ->select('s_h.hotel_name', 's_h.hotel_code', 'c.CityName', 'c.Country')
                    ->where('hotel_name', 'like', '%' . $request->name . '%')
                    ->get();

            return response()->json($hotels);
        }
        return view('admin.hotels');
    }

    public function searchHotel(Request $request) {

        
        //echo $pattern = "/" . $request->name . "/i";

        // $hotels = StaticDataHotels::where(['city_id' => $request->city])
                            // ->where('hotel_name', 'like', '%' . $pattern . '%');
        $pattern = "/" . $request->name . "/i";

        $hotels = DB::table('static_data_hotels as s_h')
                ->join('cities as c', 'c.CityId', '=', 's_h.city_id')
                ->select('s_h.hotel_name', 's_h.hotel_code', 'c.CityName', 'c.Country')
                ->where('hotel_name', 'like', '%' . $request->name . '%')
                ->where('c.CityId', $request->city)
                ->get();

        $hotel_list = array();
        foreach ($hotels as $key => $hotel) {
           array_push($hotel_list, array('value' => $hotel->hotel_name . ' (' . $hotel->CityName . ', ' . $hotel->Country . ')' ,
                                        'id' => $hotel->hotel_code));
        }
        return response()->json($hotel_list);
    }

    public function roomImages(Request $request) {
        $images = DB::table('room_images')
            ->join('static_data_hotels as s_h', 's_h.hotel_code', '=', 'room_images.sub_domain')
            ->select('room_images.*', 's_h.hotel_name')
            ->where('s_h.hotel_code', $request->hotel_code)
            ->get();

       // $hotels = StaticDataHotels::select('hotel_name','hotel_code')->orderBy('hotel_name', 'ASC')->get();
        return view('admin.room-images')->with(['images' => $images, 'hotel_code' => $request->hotel_code]);
    }

    public function addRoomImages(Request $request) {

        if($request->isMethod('post')) {
            $input = $request->all();

            $path = public_path('/uploads/rooms/' . $request->sub_domain . '/');
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
            // echo $images_size;
            for($i = 0; $i < $images_size; $i++) {
                $image_name = '';
                if(isset($request->room_images[$i])) {

                    $media = $request->room_images[$i];
                    $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                    $media->move($destinationPath, $image_name);

                }
                array_push($room_images, $image_name);
                
            }

            if(sizeof($room_images) > 0) {
                $images_to_save = serialize($room_images);
            } else {
                $images_to_save = '';
            }

            $type = preg_replace('/\s*/', '', $input['name']);

            RoomImages::create(['images' => $images_to_save,
                                'name' => $input['name'],
                                'r_type' => strtolower($type),
                                'sub_domain' => $request->sub_domain]);

            return redirect('/admin/hotel-room-images')->with('success', 'Details updated Successfully.');

        }

        $hotels = StaticDataHotels::select('hotel_name','hotel_code')->where('hotel_code', $request->hotel_code)->orderBy('hotel_name', 'ASC')->get();
        return view('admin.add-images')->with(['hotels' => $hotels]);
    }

    public function editRoomImages(Request $request) {
        $image = RoomImages::where('id', $request->id)->first();
        $image['images'] = unserialize($image['images']);

        $rooms = RoomImages::where('sub_domain', $image['sub_domain'])->get();
        if($request->isMethod('post')) {
            $input = $request->all();

            $path = public_path('/uploads/rooms/' . $image['sub_domain'] . '/');

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

                    if (strpos($request->room_images[$i], 'http') !== false) {

                        $image_name = $request->room_images[$i];

                    } else {

                        $media = $request->room_images[$i];
                        $image_name = $i . time().'.'.$media->getClientOriginalExtension();
                        $media->move($destinationPath, $image_name);
                    }

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

        $hotel_name = StaticDataHotels::select('hotel_name')->where('hotel_code', $image['sub_domain'])->first();
        return view('admin.edit-images')->with(['image' => $image, 'rooms' => $rooms, 'hotel_name' => $hotel_name]);
    }

    public function importStaticData(Request $request) {
        if($request->isMethod('post')) {
            ini_set('max_execution_time', -1);
            $api = new TBOHotelAPI();
            $cityId = $request->CityId;
            Session::put('static_city', $cityId);
            //echo $cityId . " ";
            
            //echo $request->prefredHotel; die();
            //$hotels = StaticDataHotels::where(['city_id' => $cityId, 'data_updated' => 0])->take(50)->get();
            
            if(isset($request->prefredHotel) && $request->prefredHotel !='') {
                $hotels = DB::select("SELECT `hotel_code` FROM `static_data_hotels` WHERE `hotel_code` = '". $request->prefredHotel ."' ");    
            } else {
                $hotels = DB::select("SELECT `hotel_code` FROM `static_data_hotels` WHERE `city_id` = '". $cityId ."' AND `data_updated` = '0' AND (`start_rating` = '4' OR `start_rating` = '5') LIMIT 50");
            }
            /*StaticDataHotels::where(['city_id' =>  $cityId, 'data_updated' => 0])
                                ->orWhere('start_rating', '=', '4')
                                ->orWhere('start_rating', '=', '5')
                                ->count();*/
            // echo "<pre>";
            // print_r($hotels); die();
            try{

                foreach ($hotels as $key => $hotel) {           

                    try{
                        $hotelData = $api->getHotelStaticData($cityId, $hotel->hotel_code);  
                        if(isset($hotelData['HotelData']) && !empty($hotelData['HotelData'])) {
                            $utfReplaces = str_replace('utf-16', 'utf-8', $hotelData['HotelData']);
                            $hotelXml = simplexml_load_string($utfReplaces, 'SimpleXMLElement', LIBXML_NOCDATA); 
                            $hotelDataArray = $this->xmlToArray($hotelXml);
                            
                            
                            if(isset($hotelDataArray['ArrayOfBasicPropertyInfo']) && isset($hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'])) {
                                $hotelDataFinal  = $hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'];

                                //echo "<pre>";
                                //print_r($hotelDataFinal['VendorMessages']['VendorMessage'][4]);

                                $hotel_code = $hotelDataFinal['@TBOHotelCode'];
                                $hotel_name = $hotelDataFinal['@HotelName'];
                                $start_rating = $hotelDataFinal['@BrandCode'];
                                $hotel_rooms = $hotelDataFinal['@NoOfRooms'];
                                $hotel_floors = $hotelDataFinal['@NoOfFloors'];
                                $build_year = $hotelDataFinal['@BuiltYear'];
                               
                                $hotel_facilities = array();
                                $attractions = array();
                                $hotel_description = array();
                                $hotel_images = array();
                                $room_images = array();
                                $covid_info_array = array();
                                $room_images = array();
                                $hotel_category_images = array();

                                $facilities = (isset($hotelDataFinal['VendorMessages']) && isset($hotelDataFinal['VendorMessages']['VendorMessage'])) ? $hotelDataFinal['VendorMessages']['VendorMessage'] : array();
                                $hotel_location = isset($hotelDataFinal['Position']) ? $hotelDataFinal['Position'] : array();
                                $hotel_address = isset($hotelDataFinal['Address']) ? $hotelDataFinal['Address'] : array();
                                $hotel_contact = isset($hotelDataFinal['ContactNumbers']) ? $hotelDataFinal['ContactNumbers'] : array();
                                $hotel_time = isset($hotelDataFinal['Policy']) ? $hotelDataFinal['Policy'] : array();
                                $hotel_type = (isset($hotelDataFinal['HotelThemes']) && isset($hotelDataFinal['HotelThemes']['HotelTheme'])) ? $hotelDataFinal['HotelThemes']['HotelTheme'] : array();
                                $covid_info = (isset($hotelDataFinal['Attributes'])) ? $hotelDataFinal['Attributes'] : array();
                                $review_url = (isset($hotelDataFinal['Award'])) ? $hotelDataFinal['Award']['@ReviewURL'] : '';
                                $tp_ratings = (isset($hotelDataFinal['Award'])) ? $hotelDataFinal['Award']['@Rating'] : '';

                                if(isset($facilities)) {
                                  foreach ($facilities as $key => $facility) {

                                     

                                    if(isset($facility['@Title'])) {
                                      if($facility['@Title'] == 'Facilities') {

                                        foreach ($facility['SubSection'] as $key_fac => $hotel_fac) {
                                          if(isset($hotel_fac) && isset($hotel_fac['Paragraph'])) {
                                            array_push($hotel_facilities, str_replace("'", " ", $hotel_fac['Paragraph']['Text']['$']));
                                          }
                                        }
                                      }

                                      if($facility['@Title'] == 'Attractions') {
                                        foreach ($facility['SubSection'] as $key_attrac => $hotel_attrac) {
                                          if(isset($hotel_attrac) && isset($hotel_attrac['Text']) && isset($hotel_attrac['Text']['$'])) {
                                            array_push($attractions, str_replace("'", " ", $hotel_attrac['Text']['$']));
                                          }else {
                                            if(isset($hotel_attrac['Paragraph']) && isset($hotel_attrac['Paragraph']['Text']) && isset($hotel_attrac['Paragraph']['Text']['$'])) {
                                              array_push($attractions, str_replace("'", " ", $hotel_attrac['Paragraph']['Text']['$']));
                                            }
                                          }
                                        }
                                      }

                                      if($facility['@Title'] == 'Hotel Description') {
                                        if(isset($facility['SubSection']) && isset($facility['SubSection']['Paragraph']) && isset( $facility['SubSection']['Paragraph']['Text']) && isset( $facility['SubSection']['Paragraph']['Text']['$'])) {
                                          array_push($hotel_description, str_replace("'", " ", $facility['SubSection']['Paragraph']['Text']['$']));
                                        } else {
                                          foreach($facility['SubSection'] as $d) {
                                            if(isset($d['Paragraph']) && isset($d['Paragraph']['Text']) && isset($d['Paragraph']['Text']['$'])) {
                                                array_push($hotel_description, str_replace("'", " ", $d['Paragraph']['Text']['$']));
                                            }
                                          }
                                        }
                                      }

                                      if($facility['@Title'] == 'Hotel Pictures') {

                                        

                                        foreach ($facility['SubSection'] as $key_hpic => $hotel_pic) {
                                          if(isset($hotel_pic['Paragraph']) && isset($hotel_pic['Paragraph'][1])) {
                                            
                                            if(isset($hotel_pic['Paragraph'][1]['URL']) && $hotel_pic['Paragraph'][1]['@Type'] == 'FullImage') {
                                              if($key_hpic < 40) {
                                                array_push($hotel_images, $hotel_pic['Paragraph'][1]['URL']);
                                              }
                                              
                                              unset($hotel_pic['Paragraph'][0]);
                                              array_push($hotel_category_images, $hotel_pic);

                                            }

                                          }
                                        }
                                      }

                                    }
                                  }
                                }

                                if(isset($hotelDataFinal['Rooms']) && isset($hotelDataFinal['Rooms']['Room'])) {
                                  //check if added
                                    $room_ameneties = array();
                                    $bed_types = array();
                                  foreach($hotelDataFinal['Rooms']['Room'] as $room_key => $hotel_room) {
                                    if(isset($hotel_room['RoomTypeName'])) {
                                       
                                        if(isset($hotel_room['Faciltities']) && isset($hotel_room['Faciltities']['RoomFacility'])) {
                                            foreach ($hotel_room['Faciltities']['RoomFacility'] as $key => $r_fac) {
                                               
                                                if(isset($r_fac['FacilityName']) && !empty($r_fac['FacilityName'])) {
                                                    array_push($room_ameneties, $r_fac['FacilityName']);
                                                }
                                            }
                                        }

                                        if(isset($hotel_room['BedTypes']) && isset($hotel_room['BedTypes']['BedType'])) {
                                            $bed_types['beds'] = $hotel_room['BedTypes']['BedType'];
                                        }
                                        
                                        $bed_types['room_size'] = array('sf' => $hotel_room['RoomSizeFeet'], 'sm' => $hotel_room['RoomSizeMeter'], 'eb' => $hotel_room['AllowExtraBed']);

                                        $type = preg_replace('/\s*/', '', $hotel_room['RoomTypeName']);
                                        $image = RoomImages::where(['r_type' => $type, 'sub_domain' => $hotel_code])->first();
                                        if(isset($image) && isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                          $temp_img = array();
                                          foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                            if(isset($room_image['ImageUrl'])) {
                                                array_push($temp_img, $room_image['ImageUrl']);
                                            }
                                          }
                                          //$room_ameneties = array();
                                          RoomImages::where(['r_type' => $type, 'sub_domain' => $hotel_code])
                                                    ->update(['images' => serialize($temp_img), 'ameneties' => $room_ameneties, 'bed_type' => $bed_types]);
                                        } else {
                                          //create
                                          $temp_img = array();
                                          if(isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                            foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                                if(isset($room_image['ImageUrl'])) {
                                                    array_push($temp_img, $room_image['ImageUrl']);
                                                }
                                            }
                                            RoomImages::create(['r_type' => $type, 'sub_domain' => $hotel_code, 'name' => $hotel_room['RoomTypeName'], 'images' => serialize($temp_img), 'ameneties' => $room_ameneties, 'bed_type' => $bed_types]);
                                          }
                                        }
                                    }
                                  }

                                }
                                
                                if(isset($covid_info) && !empty($covid_info)) {
                                    foreach ($covid_info['Attribute'] as $c_key => $c_info) {
                                        if(isset($c_info['@AttributeType']) && $c_info['@AttributeType'] == 'Covid Info') {
                                            array_push($covid_info_array, $c_info['@AttributeName']);
                                        }
                                    }
                                }

                                StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $cityId])
                                  ->update(['hotel_name' => $hotel_name,
                                            'start_rating' => $start_rating,
                                            'hotel_rooms' => $hotel_rooms,
                                            'hotel_floors' => $hotel_floors,
                                            'build_year' => $build_year,
                                            'hotel_facilities' => json_encode($hotel_facilities),
                                            'hotel_contact' => json_encode($hotel_contact),
                                            'attractions' => json_encode($attractions),
                                            'hotel_description' => json_encode($hotel_description),
                                            'hotel_images' => json_encode($hotel_images),
                                            'category_image' => json_encode($hotel_category_images),
                                            'hotel_location' => json_encode($hotel_location),
                                            'hotel_address' => json_encode($hotel_address),
                                            'hotel_time' => json_encode($hotel_time),
                                            'hotel_type' => json_encode($hotel_type),
                                            'data_updated' => 1,
                                            'tp_ratings' => $tp_ratings,
                                            'hotel_award' => $review_url,
                                            'hotel_info' => json_encode($covid_info_array),
                                            'room_amenities' => json_encode($room_ameneties),
                                            'updated_at' => date('Y-m-d h:i:s')]);
                            }
                        }
                    } catch (Exception $e) {

                        return redirect('/admin/import-static-data')->with('error', $e->getMessage());
                        break;
                    }
                }    
                // die();
                return redirect('/admin/import-static-data');

            } catch (Exception $e) {

                return redirect('/admin/import-static-data')->with('error', $e->getMessage());
            }      
        }

        if(!empty(Session::get('static_city')) && Session::get('static_city') != null && Session::get('static_city') != '') {

            $updated_hotels = DB::select("SELECT COUNT(*) AS `total_count` FROM `static_data_hotels` WHERE `city_id` = '". Session::get('static_city') ."' AND `data_updated` = '1' AND (`start_rating` = '4' OR `start_rating` = '5')");
            $pending_hotels = DB::select("SELECT COUNT(*) AS `total_count` FROM `static_data_hotels` WHERE `city_id` = '". Session::get('static_city') ."' AND `data_updated` = '0' AND (`start_rating` = '4' OR `start_rating` = '5')");

            $updated_hotels = $updated_hotels[0]->total_count;
            $pending_hotels = $pending_hotels[0]->total_count;
            // $updated_hotels = StaticDataHotels::where(['city_id' => Session::get('static_city'), 'data_updated' => 1])
            //                     //->orWhere('b', '=', 1);
            //                     ->count();
            // $pending_hotels = StaticDataHotels::where(['city_id' => Session::get('static_city'), 'data_updated' => 0])
                                //->count();
        } else {
            $updated_hotels = 0;
            $pending_hotels = 0;

        }

        $cities = Cities::all();
        return view('admin.static-data.hotels')->with(['cities' => $cities, 'updated_hotels' => $updated_hotels , 'pending_hotels' => $pending_hotels]);
      
    }

    public function importTransferData(Request $request) {
      ini_set('max_execution_time', -1);
      $message = '';
      // if($request->isMethod('post')) {        

        // $api = new TBOHotelAPI();
        // $data = $api->getCityData($request->CityId);  
        $destinationPath=base_path()."/rawData/" . $request->file;
        // $cityId = $request->CityId;
        //die();
        // $path = $destinationPath . "city-". $cityId ."_logs.xml"; 
      
        // // Read entire file into string 
        $xmlfile = file_get_contents($destinationPath); 
        // $xmlfile = str_replace("=\\", "=", $xmlfile);
        // $xmlfile = str_replace('"<?xml', "<?xml", $xmlfile);
        // $xmlfile = str_replace('ArrayOfBasicPropertyInfo>"', "ArrayOfBasicPropertyInfo>", $xmlfile);
        // $xmlfile = str_replace('\"', '"', $xmlfile);
        // $xmlfile = str_replace('\/', '/', $xmlfile);
        // $xmlfile = str_replace('\r\n ', '', $xmlfile);
        $xmlfile = str_replace('utf-16', 'utf-8', $xmlfile);
        // $xmlfile = str_replace('>\r\n<', '><', $xmlfile);

        $newXml = simplexml_load_string($xmlfile, 'SimpleXMLElement', LIBXML_NOCDATA); 
            
        // Convert into json 
        $arrayData = $this->xmlToArray($newXml);
        // echo "<pre>";
        // print_r($arrayData['ArrayOfBasicPortPropertyInfo']);
        // die();
        $create = 0;
        $update = 0;

        if($request->file == 'StationData') {

            foreach ($arrayData['ArrayOfBasicStationPropertyInfo']['BasicStationPropertyInfo'] as $key => $value) {    
                
                TransferCities::create(['city_id' => $value['@TBOCityId'],
                        'city_name' => $value['@CityName'],
                        'city_code' => $value['@CityCode'],
                        'country_code' => $value['@CountryCode'],
                        'station_id' => $value['@StationId'],
                        'station_name' => $value['@StationName'],
                        'type' => '2' ]);
                    $create++;
            }        
        }

        if($request->file == 'PortData') {

            foreach ($arrayData['ArrayOfBasicPortPropertyInfo']['BasicPortPropertyInfo'] as $key => $value) {

                    //first find city name
                    $city = TransferCities::where('city_id' , $value['@TBOCityID'])->first();

                    if(isset($city)) {
                        TransferCities::create(['city_id' => $value['@TBOCityID'],
                            'country_code' => $value['@CountryCode'],
                            'city_name' => $city['city_name'],
                            'city_code' => $city['city_code'],
                            'port_id' => $value['@PortId'],
                            'port_destination' => $value['@Destination'],
                            'port_name' => $value['@PortName'],
                            'type' => '3' ]);
                    } else {
                        TransferCities::create(['city_id' => $value['@TBOCityID'],
                            'country_code' => $value['@CountryCode'],
                            'city_name' => " ",
                            'city_code' => " ",
                            'port_id' => $value['@PortId'],
                            'port_destination' => $value['@Destination'],
                            'port_name' => $value['@PortName'],
                            'type' => '3' ]);
                    }
                    $create++;


            }
        }

        if($request->file == 'AirportData') {
            foreach ($arrayData['ArrayOfBasicAirportPropertyInfo']['BasicAirportPropertyInfo'] as $key => $value) {

                    TransferCities::create(['city_id' => $value['@TBOCityId'],
                        'city_name' => $value['@cityName'],
                        'city_code' => $value['@CityCode'],
                        'country_code' => $value['@CountryCode'],
                        'country_name' => "",
                        'airport_code' => $value['@AirportCode'],
                        'airport_name' => $value['@AirportName'],
                        'type' => '1' ]);
                    $create++;
            }
        }

        

        
        echo "Import Complete, total " . $create . " records created and total " . $update . " records updated.";
        // print_r($arrayData['ArrayOfBasicStationPropertyInfo']['BasicStationPropertyInfo']);
        die();
      
    }

    

    public function xmlToArray($xml, $options = array()) {
      $defaults = array(
          'namespaceSeparator' => ':',//you may want this to be something other than a colon
          'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
          'alwaysArray' => array(),   //array of xml tag names which should always become arrays
          'autoArray' => true,        //only create arrays for tags which appear more than once
          'textContent' => '$',       //key used for the text content of elements
          'autoText' => true,         //skip textContent key if node has no attributes or child nodes
          'keySearch' => false,       //optional search and replace on tag and attribute names
          'keyReplace' => false       //replace values for above search values (as passed to str_replace())
      );
      $options = array_merge($defaults, $options);
      $namespaces = $xml->getDocNamespaces();
      $namespaces[''] = null; //add base (empty) namespace
   
      //get attributes from all namespaces
      $attributesArray = array();
      foreach ($namespaces as $prefix => $namespace) {
          foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
              //replace characters in attribute name
              if ($options['keySearch']) $attributeName =
                      str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
              $attributeKey = $options['attributePrefix']
                      . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                      . $attributeName;
              $attributesArray[$attributeKey] = (string)$attribute;
          }
      }
   
      //get child nodes from all namespaces
      $tagsArray = array();
      foreach ($namespaces as $prefix => $namespace) {
          foreach ($xml->children($namespace) as $childXml) {
              //recurse into child nodes
              $childArray = $this->xmlToArray($childXml, $options);
              foreach($childArray as $key => $value) {
                // list($childTagName, $childProperties);
                $childTagName = $key;
                $childProperties = $value;
              }
   
              //replace characters in tag name
              if ($options['keySearch']) $childTagName =
                      str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
              //add namespace prefix, if any
              if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
   
              if (!isset($tagsArray[$childTagName])) {
                  //only entry with this key
                  //test if tags of this type should always be arrays, no matter the element count
                  $tagsArray[$childTagName] =
                          in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
                          ? array($childProperties) : $childProperties;
              } elseif (
                  is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                  === range(0, count($tagsArray[$childTagName]) - 1)
              ) {
                  //key already exists and is integer indexed array
                  $tagsArray[$childTagName][] = $childProperties;
              } else {
                  //key exists so convert to integer indexed array with previous value in position 0
                  $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
              }
          }
      }
   
      //get text content of node
      $textContentArray = array();
      $plainText = trim((string)$xml);
      if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
   
      //stick it all together
      $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
              ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
   
      //return node as array
      return array(
          $xml->getName() => $propertiesArray
      );
    }
}
