<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use DB;
use Session;
use Mail;
use App\User;
use App\Models\Activities;
use App\Models\Packages;
use App\Models\Cruises;
use App\Models\Cabs;
use App\Models\Bookings;
use App\Models\Payments;
use App\Models\Currencies;
use App\Models\Cities;
use App\Models\Reviews;
use App\Models\HotelInfos;

//use App\Services\TBAAPI;

use Stripe\Stripe;


class CruiseController extends Controller
{
    private $api;
    private $hotelId;
    private $hotelName;
    private $hoteIndex;
    private $traceId;
    private $hotelDetails;
    private $hotelRooms;
    private $hotelRoomsCombination;
    private $allHotels;
    private $checkInDate;
    private $checkOutDate;
    private $sub_domain;
    private $domainData;

    public function __construct(Request $request)
    {
      ini_set('max_execution_time', 240);
      //$this->api = new TBAAPI();

      Stripe::setApiKey(env('STRIPE_SECRET'));

      
      list($subdomain) = explode('.', $request->getHost(), 2); 

      if($subdomain == 'www') {
        $subdomain = explode('.', $request->getHost())[1];
      }      
        
      $this->sub_domain = $subdomain;
      $this->domainData = User::where('domain', $this->sub_domain)->first();
      Session::put('domainData', $this->domainData);     

    }

    public function cruises(Request $request) {
      
      $dbHotel = Cities::where(['HotelName' => $this->sub_domain])->first();
      $this->hotelCode = $dbHotel['HotelCode'];
      $this->domainData = User::where('domain', $this->sub_domain)->first();
      Session::put('domainData', $this->domainData);
      $country = $this->domainData['country'];
      
      $hotelDetails = HotelInfos::where('sub_domain', $this->sub_domain)->first();
      $hotelDetails['slider_images'] = unserialize($hotelDetails['slider_images']);
      $currencies = Currencies::all();

      Session::put('selectedTab','cruise');

      $cruises = Cruises::where(['sub_domain' => $this->sub_domain])->get();
      return view('search.cruises.cruises')->with(['cruises' => $cruises, 'currencies' => $currencies, 'hotelDetails' => $hotelDetails]);

    }

    public function cruiseDetails (Request $request) {
      
      $cruise = Cruises::where(['sub_domain' => $this->sub_domain,'id' => $request->id])->first();
      $cruise->images = unserialize($cruise->images);
      $cruises = Cruises::where(['sub_domain' => $this->sub_domain])->get();

      $dbHotel = Cities::where(['HotelName' => $this->sub_domain])->first();
      $this->hotelCode = $dbHotel['HotelCode'];
      $this->domainData = User::where('domain', $this->sub_domain)->first();
      Session::put('domainData', $this->domainData);
      $country = $this->domainData['country'];
      
      $hotelDetails = HotelInfos::where('sub_domain', $this->sub_domain)->first();
      $hotelDetails['slider_images'] = unserialize($hotelDetails['slider_images']);
      $currencies = Currencies::all();

      return view('search.cruises.cruise')->with(['cruise' => $cruise, 'cruises' => $cruises, 'selectItem' => $request->id, 'currencies' => $currencies, 'hotelDetails' => $hotelDetails]);
    }

    public function bookCruise(Request $request) {

      if(Auth::check()) {
        $user = User::where('id', Auth::user()->id)->first();  
      } else {
        $user = array('name' => '', 'email' => '', 'phone' => '', 'address' => '');
      }
      
      $cruise = Cruises::where('id', $request->id)->first();
      
      $dbHotel = Cities::where(['HotelName' => $this->sub_domain])->first();
      $this->hotelCode = $dbHotel['HotelCode'];
      $this->domainData = User::where('domain', $this->sub_domain)->first();
      Session::put('domainData', $this->domainData);
      $country = $this->domainData['country'];
      
      $hotelDetails = HotelInfos::where('sub_domain', $this->sub_domain)->first();
      $hotelDetails['slider_images'] = unserialize($hotelDetails['slider_images']);
      $currencies = Currencies::all();

      if($request->isMethod('post')) {
        $input = $request->all();
        try{

            /*
            * First check if customer exists in the DB
            */
            $user = User::where('email', $input['email'])->first();
            $amount = $cruise['price'] * 100;

            if(isset($user) && $user->id) {
              $customer_id = $user->customer_id;
              Auth::login($user);
            } else {
              //create new user
              $password = Hash::make($this->generateRandomString());
              $user = User::create([
                        'name' => $input['first_name'] .' '. $input['last_name'],
                        'email' => $input['email'],
                        'phone' => $input['phone'],
                        'address' => $input['address'],
                        'role' => 'user',
                        'password' => $password,
                    ]);
              Auth::login($user);
              //create customer on stripe
              $customer = \Stripe\Customer::create(
                            [
                                'name' => $input['first_name'] .' '. $input['last_name'],
                                'source' => $input['token'],
                                'email' =>  $input['email'],
                                "address" => ["city" => $input['city'],
                                             "country" => $input['country'], 
                                             "line1" => $input['address'], 
                                             "line2" => "", 
                                             "postal_code" => $input['zip_code'], 
                                             "state" => $input['state']],
                                'description' => 'Booking for cruise'. $cruise['name'],
                            ]
                        );
              $customer_id = $customer['id'];
              User::where(['id' => $user->id ])
                    ->update(["customer_id" => $customer_id]);   
            }
           

            $charge = \Stripe\Charge::create([
                      'amount' => ceil($amount),
                      'currency' => $input['currency'],
                      'customer' => $customer_id,
                      'description' => 'Booking for Cruise'. $cruise['name']
                    ]);
            

            $booking = Bookings::create(['booking_id' => 'cruise',
                                'trace_id' => '',
                                'user_id' => Auth::user()->id,
                                'token_id' => $cruise['id'],
                                'status' => 'Booked',
                                'hotel_booking_status' => '',
                                'invoice_number' => '',
                                'confirmation_number' => '',
                                'booking_ref' => '',
                                'price_changed' => 0,
                                'cancellation_policy' => 0,
                                'request_data' => json_encode($input) ]);

            //create entry to payments table
            Payments::create(['booking_id' => $booking->id,
                                'user_id' => Auth::user()->id,
                                'price' => $amount / 100,
                                'customer_id' => $customer_id,
                                'sub_domain' => '']);         

            return redirect('/user/bookings');

          } catch (\Stripe\Error\RateLimit $e) {
            $success = false;
            $message = $e->getMessage();
          } catch (\Stripe\Error\InvalidRequest $e) {
            $success = false;
            $message = $e->getMessage();
          } catch (\Stripe\Error\Authentication $e) {
            $success = false;
            $message = $e->getMessage();
          } catch (\Stripe\Error\ApiConnection $e) {
            $success = false;
            $message = $e->getMessage();
          } catch (\Stripe\Error\Base $e) {
            $success = false;
            $message = $e->getMessage();
          } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
          }

          Session::flash('error', $message);
          return view('search.cruises.book-cruise')->with(['user' => $user, 'cruise' => $cruise, 'currencies' => $currencies, 'hotelDetails' => $hotelDetails]);
      }

      return view('search.cruises.book-cruise')->with(['user' => $user, 'cruise' => $cruise, 'currencies' => $currencies, 'hotelDetails' => $hotelDetails]);
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
}