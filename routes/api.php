<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/set-country', 'App\Http\Controllers\HomeController@setUserCurrency');
Route::get('/cities/{term}', 'App\Http\Controllers\HomeController@getCities');
Route::get('/us-cities/{term}', 'App\Http\Controllers\HomeController@getUSCities');
Route::get('/hcities/{term}', 'App\Http\Controllers\HomeController@halalCities');

/*  */
Route::get('/cities-FH/{term}/{country}', 'App\Http\Controllers\HomeController@getCitiesFH');
Route::get('/hcities-FH/{term}/{country}', 'App\Http\Controllers\HomeController@halalCitiesFH');

Route::get('/cities-air/{term}', 'App\Http\Controllers\HomeController@getAirCities');
Route::get('/countries/{term}', 'App\Http\Controllers\HomeController@getCountries');

Route::post('/hotels', [App\Http\Controllers\Search\HotelController::class, 'searchHotels']);
Route::post('/saveHotelFormData', [App\Http\Controllers\Search\HotelController::class, 'saveHotelFormData']);
Route::get('/getFormData', [App\Http\Controllers\Search\HotelController::class, 'getFormData']);
Route::post('/hotels-send', [App\Http\Controllers\Search\HotelController::class, 'sendHotelsEmail']);
Route::post('/flights-hotels-send', [App\Http\Controllers\Search\FlightsHotelController::class, 'sendFlightsHotelsEmail']);

Route::post('/flight-hotels', [App\Http\Controllers\Search\FlightsHotelController::class, 'searchFlightHotels']);

Route::get('/hotels/all', [App\Http\Controllers\Search\HotelController::class, 'allHotels']);

Route::get('/hotels/more/{size}/{city_id}/{price}/{distance}', [App\Http\Controllers\Search\HotelController::class, 'loadMoreHotels']);
Route::post('/hotel/rooms', [App\Http\Controllers\Search\HotelController::class, 'hotelRooms'])->name('view_hotel_rooms');
Route::post('/flight-hotel/rooms', [App\Http\Controllers\Search\FlightsHotelController::class, 'hotelRooms'])->name('view_hotel_rooms');

Route::post('/change-flight-hotel/{flightID}/{flightTraceId}/{searchId}', [App\Http\Controllers\Search\FlightsHotelController::class, 'changeFlight'])->name('changeFlight');

Route::post('/change-flight-hotel-return/{flightID}/{flightTraceId}/{searchId}', [App\Http\Controllers\Search\FlightsHotelController::class, 'changeFlightReturn'])->name('changeFlightReturn');

Route::get('/hotel/search/{name}/{city_id}', [App\Http\Controllers\Search\HotelController::class, 'searchHotel']);

Route::post('/send-wallet-single', [App\Http\Controllers\Search\HotelController::class, 'saveWalletSingle']);

Route::post('/flights', [App\Http\Controllers\Search\FlightController::class, 'searchFlights']);
Route::get('/flights/more/{size}', [App\Http\Controllers\Search\FlightController::class, 'loadMoreFlights']);
Route::post('/flight-send', [App\Http\Controllers\Search\FlightController::class, 'sendFlightEmail']);

Route::post('/cabs', [App\Http\Controllers\Search\CabController::class, 'searchCabs']);
Route::post('/activities', [App\Http\Controllers\Search\ActivityController::class, 'searchActivities']);
Route::post('/checkAvailability', [App\Http\Controllers\Search\ActivityController::class, 'checkAvailability']);
Route::post('/activity-send', [App\Http\Controllers\Search\ActivityController::class, 'sendActivitiesEmail']);

Route::get('/cabs-cities/{term}', 'App\Http\Controllers\HomeController@getCabCities');
Route::get('/act-cities/{term}', 'App\Http\Controllers\HomeController@getActivityCities');
Route::get('/get-country-code/{code}', 'App\Http\Controllers\HomeController@getCountryCode');
Route::post('/cab-send', [App\Http\Controllers\Search\CabController::class, 'sendCabEmail']);

Route::get('/cabs-cities-pickup/{cityId}/{type}', 'App\Http\Controllers\HomeController@getCabLocations');

Route::get('/cabs-cities-pickup/{cityId}/{type}/{term}', 'App\Http\Controllers\HomeController@getCabLocations');

Route::post('/admin/hotel/search/{name}', [App\Http\Controllers\Admin\AdminController::class, 'showHotels']);

Route::post('/check-email', [App\Http\Controllers\User\UserController::class, 'checkEMail']);

Route::get('/admin/hotels/{city}/{name}', [App\Http\Controllers\Admin\AdminController::class, 'searchHotel']);

Route::post('/fb-login', [App\Http\Controllers\User\UserController::class, 'fbLogin']);
Route::post('/g-login', [App\Http\Controllers\User\UserController::class, 'gLogin']);
Route::post('/create-user', [App\Http\Controllers\User\UserController::class, 'createUserLogin']);

/*
* Extra Script Data Routes
*/
Route::get('/halal-hotels', [App\Http\Controllers\ScriptController::class, 'halalHotels']);
Route::get('/download-hotel-data', [App\Http\Controllers\ScriptController::class, 'downloadHotelData']);
Route::get('/download-hotel-data-progress', [App\Http\Controllers\ScriptController::class, 'downloadHotelDataProgress']);

Route::get('/getcitiesdata',  [App\Http\Controllers\JobController::class, 'getCitiesData']);
Route::get('/gethotelsdata',  [App\Http\Controllers\JobController::class, 'getHotelsData']);

Route::get('/cron-hotel', [App\Http\Controllers\ScriptController::class, 'handle']);


Route::post('/hotels-raw', [App\Http\Controllers\Search\HotelController::class, 'searchHotelsRaw']);

/*
* get home page sections data in angular app
*/
Route::get('/get-sections',  [App\Http\Controllers\Api\HomeController::class, 'index']);

/*
* new agent dasboard
*/
Route::post('/agent/chart-data', [App\Http\Controllers\Agent\DashboardController::class, 'chartsData']);

Route::post('/agent/settings', [App\Http\Controllers\Agent\APIController::class, 'updateSettings']);
Route::post('/agent/post', [App\Http\Controllers\Agent\APIController::class, 'createPost']);
Route::post('/agent/post/comment', [App\Http\Controllers\Agent\APIController::class, 'postComment']);
Route::post('/agent/post/like', [App\Http\Controllers\Agent\APIController::class, 'postLike']);
Route::post('/agent/kyc', [App\Http\Controllers\Agent\APIController::class, 'saveBankDetails']);
Route::post('/agent/withdraw', [App\Http\Controllers\Agent\APIController::class, 'withdrawPayment']);
Route::post('/agent/upload/cover', [App\Http\Controllers\Agent\APIController::class, 'uploadCoverImage']);
Route::post('/agent/add-pay', [App\Http\Controllers\Agent\APIController::class, 'addPayment']);
Route::post('/agent/message', [App\Http\Controllers\Agent\APIController::class, 'sendMessage']);
Route::post('/agent/message/read', [App\Http\Controllers\Agent\APIController::class, 'readMessage']);

Route::post('/agent/earnings/{type}', [App\Http\Controllers\Agent\APIController::class, 'getEarningsType']);


/* Payment API */
Route::post('/payment-failed', [App\Http\Controllers\Search\HotelController::class, 'paymentFailedEmail']);