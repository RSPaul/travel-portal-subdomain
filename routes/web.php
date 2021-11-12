<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
//Route::get('/test-email', [App\Http\Controllers\HomeController::class, 'testEmail']);
Route::get('/', [App\Http\Controllers\HomeController::class, 'landing']);
Route::get('/affiliate', [App\Http\Controllers\HomeController::class, 'affiliateLanding']);
Route::get('/hotel-by-city', [App\Http\Controllers\HomeController::class, 'hotelByCity']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/landing', [App\Http\Controllers\HomeController::class, 'landing']);
Route::get('/coming_soon', [App\Http\Controllers\HomeController::class, 'comingSoon']);

Route::get('/privacy-policy', [App\Http\Controllers\HomeController::class, 'privacyPolicy']);
Route::get('/refund-policy', [App\Http\Controllers\HomeController::class, 'refundPolicy']);
Route::get('/terms-conditions', [App\Http\Controllers\HomeController::class, 'termsConditions']);
Route::get('/contact-us', [App\Http\Controllers\HomeController::class, 'contactUs']);
Route::get('/about-us', [App\Http\Controllers\HomeController::class, 'aboutUs']);

Route::get('/register/affiliate', [App\Http\Controllers\User\UserController::class, 'registerAgent']);
Route::post('/register/affiliate', [App\Http\Controllers\User\UserController::class, 'registerAgent']);

Route::get('/referral/{referral}', [App\Http\Controllers\HomeController::class, 'landing']);
Route::get('/barcode/{no}', [App\Http\Controllers\HomeController::class, 'barcode']);

Route::get('/hotels', [App\Http\Controllers\Search\HotelController::class, 'search'])->name('search_hotels');
Route::get('/hotels-raw', [App\Http\Controllers\Search\HotelController::class, 'searchHotelsRaw'])->name('search_hotels_raw');


/*
* Hotel Wihtout price
*/
Route::get('/discover/country/{country}', [App\Http\Controllers\Search\HotelStaticController::class, 'discoverCountry']);
Route::get('/discover/more-countries', [App\Http\Controllers\Search\HotelStaticController::class, 'discoverMoreCountry']);
Route::get('/hotels/{country}/{city}/{city_id}', [App\Http\Controllers\Search\HotelStaticController::class, 'searchHotels'])->name('search_hotels_no_price');
Route::get('/hotel/{country}/{city}/{hotel_name}/{hotel_code}/{referral}', [App\Http\Controllers\Search\HotelStaticController::class, 'viewHotel']);

Route::get('/findhotels', [App\Http\Controllers\Search\HotelController::class, 'lookup'])->name('search_rooms');
Route::post('/recordpayment', [App\Http\Controllers\Search\HotelController::class, 'recordPayment']);

Route::get('/hotel/{country}/{city}/{hotel_name}/{traceId}/{code}/{checkIn}/{rooms}/{city_id}/{nights}/{referral}', [App\Http\Controllers\Search\HotelController::class, 'viewHotel2']);
// Route::get('/hotel/{traceId}/{hotelCode}/{hotelIndex}/{supplierIds}/{checkIn}/{checkOut}/{guests}/{city_name}/{rating}', [App\Http\Controllers\Search\HotelController::class, 'viewHotel'])->name('view_hotel');
// Route::get('/hotel/{traceId}/{hotelCode}/{hotelIndex}/{supplierIds}/{checkIn}/{checkOut}/{guests}/{city_name}/{rating}/{referral}', [App\Http\Controllers\Search\HotelController::class, 'viewHotel'])->name('view_hotel');

//Route::get('/room/{index}/{traceId}/{checkInDate}/{checkOutDate}/{category}/{combination}/{roomIndexes}', [App\Http\Controllers\Search\HotelController::class,'viewRoom'])->name('viewRoom');
//Route::get('/room/{index}/{traceId}/{checkInDate}/{checkOutDate}/{category}/{combination}/{referral}/{roomIndexes}', [App\Http\Controllers\Search\HotelController::class,'viewRoom'])->name('viewRoom');

Route::get('/room/{traceId}/{referral}/{search_id}/{code}/{checkIn}/{rooms}/{nights}', [App\Http\Controllers\Search\HotelController::class,'viewRoomGet'])->name('viewRoomGet');

Route::post('/room/{traceId}/{referral}/{search_id}/{code}/{checkIn}/{rooms}/{nights}', [App\Http\Controllers\Search\HotelController::class,'viewRoom'])->name('viewRoom');
Route::post('/bookRoom', [App\Http\Controllers\Search\HotelController::class,'bookRoom'])->name('bookRoom');


/* Hotels + Flights Routes */

Route::get('/findflighthotels', [App\Http\Controllers\Search\FlightsHotelController::class, 'lookupF'])->name('search_flight_rooms');

Route::get('/flight-hotel/{traceId}/{code}/{checkIn}/{rooms}/{city_id}/{nights}/{referral}/{origin}/{from}/{destination}/{to}/{flightTraceId}/{searchId}/{flightID}/{rflightID}', [App\Http\Controllers\Search\FlightsHotelController::class, 'viewFlightHotel2']);

Route::post('/flight-room/{traceId}/{referral}/{search_id}/{flightTraceId}/{searchId}/{flightID}/{rflightID}', [App\Http\Controllers\Search\FlightsHotelController::class,'viewFlightRoom'])->name('viewFlightRoom');
Route::get('/flight-room/{traceId}/{referral}/{search_id}/{flightTraceId}/{searchId}/{flightID}/{rflightID}', [App\Http\Controllers\Search\FlightsHotelController::class,'viewFlightRoomGet'])->name('viewFlightRoomGet');

Route::post('/bookFlightRoom', [App\Http\Controllers\Search\FlightsHotelController::class,'bookFlight'])->name('bookFlightRoom');



/* Flight Routes */
Route::get('/flights', [App\Http\Controllers\Search\FlightController::class, 'search'])->name('search_flights');

Route::get('/share-flight/{JourneyType}/{origin}/{from}/{destination}/{to}/{departDate}/{returnDate}/{travellersClass}/{referral}/{adultsF}/{childsF}', [App\Http\Controllers\Search\FlightController::class, 'search_social_flights'])->name('search_social_flights');


Route::get('/flight/{traceId}/{OBindex}/{IBindex}/{isLcc}', [App\Http\Controllers\Search\FlightController::class, 'viewFlight'])->name('viewFlight');
Route::get('/flight/{traceId}/{OBindex}/{IBindex}/{isLcc}/{referral}/{searchId}', [App\Http\Controllers\Search\FlightController::class, 'viewFlight'])->name('viewFlight');
Route::post('/bookFlight', [App\Http\Controllers\Search\FlightController::class, 'bookFlight'])->name('bookFlight');
Route::post('/cancelFlightBooking', [App\Http\Controllers\Search\FlightController::class,'cancelFlightBooking'])->name('cancelFlightBooking');
Route::post('/user/view-refund-status/flight', [App\Http\Controllers\Search\FlightController::class, 'cancelBookingStatus'])->name('cancel_booking_flight');


Route::get('/cabs', [App\Http\Controllers\Search\CabController::class, 'search'])->name('search_cabs');
Route::get('/cab/{category}/{index}/{traceId}/{referral}/{currency_code}/{search_id}', [App\Http\Controllers\Search\CabController::class, 'cabDetails'])->name('book_cab');
Route::post('/bookCab', [App\Http\Controllers\Search\CabController::class,'bookCab'])->name('bookCab');

Auth::routes();
Route::post('/cancelCabBooking', [App\Http\Controllers\Search\CabController::class,'cancelCabBooking'])->name('cancelCabBooking');
Route::post('/user/view-refund-status/cab', [App\Http\Controllers\Search\CabController::class, 'cancelBookingStatus'])->name('cancel_booking_cab');


Route::get('/activities', [App\Http\Controllers\Search\ActivityController::class, 'search'])->name('search_activities');
Route::get('/activitiy/{category}/{index}/{traceId}/{referral}/{currency_code}', [App\Http\Controllers\Search\ActivityController::class, 'activityDetails'])->name('book_activity');
Route::post('/bookActivity', [App\Http\Controllers\Search\ActivityController::class,'bookActivity'])->name('bookActivity');

Route::get('/view-activity/{search_id}/{tour_index}/{result_index}/{referral}/{traceId}', [App\Http\Controllers\Search\ActivityController::class,'blockActivity'])->name('block_activity');
Route::post('/cancelActBooking', [App\Http\Controllers\Search\ActivityController::class,'cancelActBooking'])->name('cancelActBooking');
Route::post('/user/view-refund-status/act', [App\Http\Controllers\Search\ActivityController::class, 'cancelBookingStatus'])->name('cancel_booking_act');

Auth::routes();
Route::post('/cancelActivityBooking', [App\Http\Controllers\Search\ActivityController::class,'cancelActivityBooking'])->name('cancelActivityBooking');
Route::post('/user/view-refund-status/activity', [App\Http\Controllers\Search\ActivityController::class, 'cancelBookingStatus'])->name('cancel_booking_activity');


/*
* Admin Routes
*/
Route::get('/admin/login', [App\Http\Controllers\Admin\AdminController::class, 'login']);
Route::post('/admin/login', [App\Http\Controllers\Admin\AdminController::class, 'login'])->name('admin_login');
Auth::routes();
Route::get('/admin', [App\Http\Controllers\Admin\AdminController::class, 'dashboard']);
// Route::get('/admin/activities', [App\Http\Controllers\Admin\AdminController::class, 'activities'])->name('admin_activities');
// Route::get('/admin/activity/add', [App\Http\Controllers\Admin\AdminController::class, 'addActivity'])->name('admin_add_activities');
// Route::post('/admin/activity/add', [App\Http\Controllers\Admin\AdminController::class, 'addActivity'])->name('admin_add_activities');
// Route::get('/admin/activity/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editActivity'])->name('admin_edit_activity');
// Route::post('/admin/activity/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editActivity'])->name('admin_edit_activity');
// Route::post('/admin/activity/delete/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteActivity'])->name('admin_delete_activity');

Route::get('/admin/cruises', [App\Http\Controllers\Admin\AdminController::class, 'cruises'])->name('admin_cruises');
Route::get('/admin/cruise/add', [App\Http\Controllers\Admin\AdminController::class, 'addCruis'])->name('admin_add_cruises');
Route::post('/admin/cruise/add', [App\Http\Controllers\Admin\AdminController::class, 'addCruis'])->name('admin_add_cruises');
Route::get('/admin/cruise/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editCruis'])->name('admin_edit_cruis');
Route::post('/admin/cruise/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editCruis'])->name('admin_edit_cruis');
Route::post('/admin/cruise/delete/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteCruis'])->name('admin_delete_cruise');


Route::get('/admin/packages', [App\Http\Controllers\Admin\AdminController::class, 'packages'])->name('admin_packages');
Route::get('/admin/package/add', [App\Http\Controllers\Admin\AdminController::class, 'addPackage'])->name('admin_add_packages');
Route::post('/admin/package/add', [App\Http\Controllers\Admin\AdminController::class, 'addPackage'])->name('admin_add_packages');
Route::get('/admin/package/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editPackage'])->name('admin_edit_package');
Route::post('/admin/package/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editPackage'])->name('admin_edit_package');
Route::post('/admin/package/delete/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deletePackage'])->name('admin_delete_packagee');

Route::get('/admin/discover', [App\Http\Controllers\Admin\DiscoverController::class, 'index'])->name('discover_cities');
Route::get('/admin/discover/cities/{country_code}', [App\Http\Controllers\Admin\DiscoverController::class, 'showCities'])->name('show_cities');
Route::post('/admin/discover/cities/{country_code}', [App\Http\Controllers\Admin\DiscoverController::class, 'showCities'])->name('show_cities');

// Route::get('/admin/cabs', [App\Http\Controllers\Admin\AdminController::class, 'cabs'])->name('admin_cabs');
// Route::get('/admin/cab/add', [App\Http\Controllers\Admin\AdminController::class, 'addCab'])->name('admin_add_cabs');
// Route::post('/admin/cab/add', [App\Http\Controllers\Admin\AdminController::class, 'addCab'])->name('admin_add_cabs');
// Route::get('/admin/cab/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editCab'])->name('admin_edit_cab');
// Route::post('/admin/cab/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editCab'])->name('admin_edit_cab');
// Route::post('/admin/cab/delete/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteCab'])->name('admin_delete_cab');

Route::get('/admin/agents', [App\Http\Controllers\Admin\AdminController::class, 'agents'])->name('admin_agents');
Route::get('/admin/agent/add', [App\Http\Controllers\Admin\AdminController::class, 'addAgent'])->name('admin_add_agents');
Route::post('/admin/agent/add', [App\Http\Controllers\Admin\AdminController::class, 'addAgent'])->name('admin_add_agents');
Route::get('/admin/agent/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editAgent'])->name('admin_edit_agent');
Route::post('/admin/agent/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editAgent'])->name('admin_edit_agent');
Route::post('/admin/agent/delete/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteAgent'])->name('admin_delete_agent');


Route::get('/admin/hotel/details', [App\Http\Controllers\Admin\AdminController::class, 'hotelDetails'])->name('admin_hotel_details');
Route::post('/admin/hotel/details', [App\Http\Controllers\Admin\AdminController::class, 'hotelDetails'])->name('admin_hotel_details');

/*
* Reports
*/
Route::get('/admin/reports/visitors', [App\Http\Controllers\Admin\ReportsController::class, 'visitorsReports'])->name('visitors_Reports');
Route::get('/admin/reports/sales', [App\Http\Controllers\Admin\ReportsController::class, 'salesReports'])->name('sales_Reports');
Route::get('/admin/reports/earnings', [App\Http\Controllers\Admin\ReportsController::class, 'earningReports'])->name('earning_Reports');


Route::get('/admin/profile',  [App\Http\Controllers\Admin\AdminController::class, 'profile']);
Route::post('/admin/profile',  [App\Http\Controllers\Admin\AdminController::class, 'profile']);

Route::post('/admin/saveWeekendImage',  [App\Http\Controllers\Admin\AdminController::class, 'saveWeekendImage']);


/* Get Flight Booking */

Route::get('/admin/flight-booking',  [App\Http\Controllers\Admin\AdminController::class, 'flightBooking']);
Route::post('/admin/flight-booking',  [App\Http\Controllers\Admin\AdminController::class, 'flightBooking']);

/*
* Static Data
*/
Route::get('/admin/get-static-data', [App\Http\Controllers\Admin\AdminController::class, 'staticData']);
Route::post('/admin/get-static-data', [App\Http\Controllers\Admin\AdminController::class, 'staticData']);

Route::get('/admin/import-static-data', [App\Http\Controllers\Admin\AdminController::class, 'importStaticData']);
Route::post('/admin/import-static-data', [App\Http\Controllers\Admin\AdminController::class, 'importStaticData']);


/*
* Static Data Import
*/
Route::get('/admin/import-transfer-data/{file}', [App\Http\Controllers\Admin\AdminController::class, 'importTransferData']);

/*
* Hotel Room Images
*/
Route::get('/admin/hotels', [App\Http\Controllers\Admin\AdminController::class, 'showHotels'])->name('admin_room_images');
Route::get('/admin/hotel-room-images/{hotel_code}', [App\Http\Controllers\Admin\AdminController::class, 'roomImages'])->name('admin_room_images');
Route::get('/admin/hotel-room-images/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editRoomImages'])->name('admin_edit_room_images');
Route::get('/admin/hotel-room-images/add/{hotel_code}', [App\Http\Controllers\Admin\AdminController::class, 'addRoomImages'])->name('admin_add_room_images');
Route::post('/admin/hotel-room-images/add/{hotel_code}', [App\Http\Controllers\Admin\AdminController::class, 'addRoomImages'])->name('admin_add_room_images');
Route::post('/admin/hotel-room-images/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editRoomImages'])->name('admin_edit_room_images');
Route::delete('/admin/hotel-room-images/delete/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteRoomImages'])->name('admin_delete_room_images');

/*
* Lottery Routes
*/
Route::get('/admin/lottery', [App\Http\Controllers\Admin\lotteryController::class, 'index'])->name('lottery_list');
Route::get('/admin/lottery/add', [App\Http\Controllers\Admin\lotteryController::class, 'add'])->name('lottery_add');
Route::post('/admin/lottery/add', [App\Http\Controllers\Admin\lotteryController::class, 'add'])->name('lottery_add');
Route::get('/admin/lottery/edit/{id}', [App\Http\Controllers\Admin\lotteryController::class, 'edit'])->name('lottery_edit');
Route::post('/admin/lottery/edit/{id}', [App\Http\Controllers\Admin\lotteryController::class, 'edit'])->name('lottery_edit');
Route::post('/admin/lottery/delete/{id}', [App\Http\Controllers\Admin\lotteryController::class, 'delete'])->name('lottery_edit');

/*
* Admin Bank Accounts
*/
Route::get('/admin/bank-accounts', [App\Http\Controllers\Admin\PaymentsController::class, 'bankAccounts'])->name('bank_accounts');
Route::post('/admin/approve/bank-account', [App\Http\Controllers\Admin\PaymentsController::class, 'approveAccount'])->name('approve_accounts');
Route::get('/admin/web-videos', [App\Http\Controllers\Admin\AdminController::class, 'webVideos'])->name('web_videos');
Route::get('/admin/web-video/add', [App\Http\Controllers\Admin\AdminController::class, 'addVideo'])->name('add_video');
Route::post('/admin/web-video/add', [App\Http\Controllers\Admin\AdminController::class, 'addVideo'])->name('add_video');
Route::get('/admin/web-video/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editVideo'])->name('edit_video');
Route::post('/admin/web-video/edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'editVideo'])->name('edit_video');

/*
* Admin Payments
*/
Route::get('/admin/withdrawls', [App\Http\Controllers\Admin\PaymentsController::class, 'withdrawls'])->name('withdrawls');
Route::post('/admin/approve/payment', [App\Http\Controllers\Admin\PaymentsController::class, 'approveWithdrawl'])->name('approve_withdrawl');
Route::get('/admin/payments', [App\Http\Controllers\Admin\PaymentsController::class, 'payments'])->name('payments');
Route::post('/admin/approve/wallet-payment', [App\Http\Controllers\Admin\PaymentsController::class, 'approveWalletPayment'])->name('approve_wallet_payment');

/*
* User Routes
*/

Route::get('/login', [App\Http\Controllers\User\UserController::class, 'login'])->name('user_login');
Route::post('/login', [App\Http\Controllers\User\UserController::class, 'login'])->name('user_login');
Route::get('/{role}/password/change', [App\Http\Controllers\User\UserController::class, 'changePassword'])->name('change_password');
Route::post('/{role}/password/change', [App\Http\Controllers\User\UserController::class, 'changePassword'])->name('change_password');
Route::get('/user/bookings', [App\Http\Controllers\User\UserController::class, 'bookings'])->name('my_booking');
//Route::get('/user/booking/{id}', [App\Http\Controllers\User\UserController::class, 'viewBooking'])->name('view_booking');
Route::get('/user/profile', [App\Http\Controllers\User\UserController::class, 'profile'])->name('user_profile');
Route::post('/user/profile', [App\Http\Controllers\User\UserController::class, 'profile'])->name('user_profile');

// Route::get('/agent/profile', [App\Http\Controllers\User\UserController::class, 'profile'])->name('user_profile');
// Route::post('/agent/profile', [App\Http\Controllers\User\UserController::class, 'profile'])->name('user_profile');


Route::post('/user/cancel_booking/{type}', [App\Http\Controllers\Search\HotelController::class, 'cancelBooking'])->name('cancel_booking_hotel');
Route::post('/user/view-refund-status/hotel', [App\Http\Controllers\Search\HotelController::class, 'cancelBookingStatus'])->name('cancel_booking_hotel_status');
Route::get('/user/e-ticket/{type}/{booking_id}', [App\Http\Controllers\User\UserController::class, 'downloadEPDF'])->name('download_ticket');
Route::post('/user/generate_voucher/{type}', [App\Http\Controllers\Search\HotelController::class, 'generateVoucher'])->name('voucher_booking_hotel');



Route::get('/user/booking/{type}/{id}/{message}', [App\Http\Controllers\User\UserController::class, 'viewBooking'])->name('view_booking');
Route::get('/thankyou/{type}/{id}/{message}', [App\Http\Controllers\User\UserController::class, 'viewBooking'])->name('view_booking');

Route::get('/invoice/{type}/{id}', [App\Http\Controllers\User\UserController::class, 'downloadInvoice'])->name('downloadInvoice');

Route::get('/not-found', [App\Http\Controllers\HomeController::class, 'notFound'])->name('not-found');

Route::get('/show-location', function () {

	echo "Your IP address is => " .$ip = \Request::ip();
    $data = \Location::get($ip);
    echo "<br>";
    echo "You country is <b>" . $data->countryName . '</b> country code is  <b>' . $data->countryCode . '</b> and region is <b>' . $data->regionName . '</b> and city is <b>' . $data->cityName . "</b>." ;
    echo "<pre>";
    print_r($data);
    die();
   
});

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});


/*
* Cron Job
*/
Route::get('/cron-static-set-city/{number}/{city_id}', [App\Http\Controllers\HomeController::class, 'cronJobSetCity'])->name('cron_job_set_city');


Route::get('/cron-static-hotel-data1', [App\Http\Controllers\HomeController::class, 'cronJobHotelStaticData1'])->name('cron_job1');
Route::get('/cron-static-hotel-data2', [App\Http\Controllers\HomeController::class, 'cronJobHotelStaticData2'])->name('cron_job2');
Route::get('/cron-static-hotel-data3', [App\Http\Controllers\HomeController::class, 'cronJobHotelStaticData3'])->name('cron_job3');
Route::get('/cron-static-hotel-data4', [App\Http\Controllers\HomeController::class, 'cronJobHotelStaticData4'])->name('cron_job4');
Route::get('/cron-static-hotel-data5', [App\Http\Controllers\HomeController::class, 'cronJobHotelStaticData5'])->name('cron_job5');
Route::get('/cron/delete/searches', [App\Http\Controllers\JobController::class, 'deleteSearches'])->name('delete_searches');

//Route::get('/cron-progress/{number}', [App\Http\Controllers\HomeController::class, 'cronJobHotelStaticDataProgress'])->name('cron_progress');
Route::get('/check-cron-progress/{number}', [App\Http\Controllers\HomeController::class, 'cronJobHotelStaticDataProgress'])->name('cron_progress');

//Route::get('/lottery', [App\Http\Controllers\HomeController::class, 'lottery']);
//Route::post('/join-lottery', [App\Http\Controllers\HomeController::class, 'joinLottery'])->name('join_lottary');
Route::get('/user/wallet', [App\Http\Controllers\User\UserController::class, 'wallet']);

Route::post('/cookie', [App\Http\Controllers\HomeController::class, 'setCookie']);


/*
* New Agent Dashboard
*/
Route::get('/agent/dashboard', [App\Http\Controllers\Agent\DashboardController::class, 'dashboard']);
Route::get('/agent/widget', [App\Http\Controllers\Agent\DashboardController::class, 'htmlWidget']);
Route::get('/agent/settings', [App\Http\Controllers\Agent\DashboardController::class, 'settings']);
Route::get('/agent/achievements', [App\Http\Controllers\Agent\DashboardController::class, 'achievements']);
Route::get('/agent/kyc', [App\Http\Controllers\Agent\DashboardController::class, 'bankDetails']);
Route::get('/agent/withdraw', [App\Http\Controllers\Agent\DashboardController::class, 'withDrawPayment']);
Route::get('/agent/notifications', [App\Http\Controllers\Agent\DashboardController::class, 'Notifications']);
Route::get('/agent/earnings', [App\Http\Controllers\Agent\DashboardController::class, 'earnings']);
Route::get('/agent/videos', [App\Http\Controllers\Agent\DashboardController::class, 'videos']);
Route::get('/agent/profile', [App\Http\Controllers\Agent\ProfileController::class, 'viewProfile']);
Route::get('/agent/profile/{name}/{id}', [App\Http\Controllers\Agent\ProfileController::class, 'viewProfile']);
Route::get('/agent/wallet', [App\Http\Controllers\Agent\DashboardController::class, 'wallet']);
Route::get('/agent/chat', [App\Http\Controllers\Agent\ChatController::class, 'chat']);
Route::get('/agent/{partnername}', [App\Http\Controllers\Agent\DashboardController::class, 'partnerDashboard']);
Route::get('/agent/withdraw-payment/{partnername}', [App\Http\Controllers\Agent\DashboardController::class, 'withDrawPaymentPartner']);

/*
* Agent Profile
*/
Route::get('/agent/profile', [App\Http\Controllers\Agent\ProfileController::class, 'index']);
Route::get('/post/{id}', [App\Http\Controllers\Agent\DashboardController::class, 'viewPost']);


/* Top locations */
Route::resource('admin/destinations', App\Http\Controllers\Admin\DestinationController::class);
/* Local Experiences */
Route::resource('admin/experiences', App\Http\Controllers\Admin\ExperienceController::class);
/* Getways */
Route::resource('admin/getaways', App\Http\Controllers\Admin\GetawayController::class);
/* explore countreis */
Route::resource('admin/countries', App\Http\Controllers\Admin\CountryController::class);

/*create views */
/* dont' uncomment route untill its required to recreate views */
//Route::get('/createview', [App\Http\Controllers\HomeController::class, 'createView']);
//Route::get('/createtbl', [App\Http\Controllers\HomeController::class, 'createTable']);


