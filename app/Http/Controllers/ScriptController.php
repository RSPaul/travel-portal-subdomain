<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\AirCities;
use App\Models\Currencies;
use App\Models\Cities;
use App\Models\RoomImages;
use App\Models\StaticDataHotels;
use App\Models\TransferCities;
use DB;
use Session;
use App\Services\TBOHotelAPI;
use App\Mail\TestEmail;
use DateTime;
use File;
use AWS;

class ScriptController extends Controller {

	public function halalHotels() {
		ini_set('max_execution_time', -1);
        ini_set('memory_limit', '1024M');
		$filePath = base_path() . "/rawData/halal-hotel.csv";
		$file = fopen($filePath, "r");
		$all_data = array();
		$total_hotel = 0;
		$found_hotel = 0;
		$resul_array = array();
		while ( ($data = fgetcsv($file, 200, ",")) !==FALSE ) {
			$total_hotel++;

		    $hotel = StaticDataHotels::where('hotel_name', $data[0])->select('hotel_code', 'hotel_name')->first();
		    if(isset($hotel)) {

			    $found_hotel++;
			   	StaticDataHotels::where('hotel_name', $data[0])
			   					->update(['ishalal' => 'yes']);
			   //array_push($resul_array, array($hotel['hotel_name'], $hotel['hotel_code']));
			} //else {
				//array_push($resul_array, array($data[0], ''));
			//}
		}


		// $csv = "Hotel Name,Hotel Code \n";//Column headers
		// foreach ($resul_array as $result){
		//     $csv.= $result[0].','.$result[1]."\n"; //Append data to csv
	 //    }

	 //    $destnationPath = base_path() . "/rawData/halal-hotel-codes.csv";
		// $csv_handler = fopen ($destnationPath,'w');
		// fwrite ($csv_handler,$csv);
		// fclose ($csv_handler);


		echo "Total hotels are " . $total_hotel . " and found hotels are " . $found_hotel;
	}

	public function downloadHotelData() {
		//get the city name
		$city = Cities::where('data_updated',0)->select('CityId')->orderBy('CityName', 'asc')->first();
		
    	$city_id = $city['CityId'];
		ini_set('max_execution_time', -1);
		$api = new TBOHotelAPI();
		
		$destinationPath = public_path() . "/logs/static-data/" . "city-" . $city_id . "_logs.xml";
		if(file_exists($destinationPath)) {
			//echo "exit";
			
		    $hotels = DB::select("SELECT `hotel_code` FROM `static_data_hotels` WHERE `city_id` = '" . $city_id . "' AND `data_updated` = '0'  LIMIT 1");
            try {

		    	foreach ($hotels as $key => $hotel) {
		    		try {
		    			$hotelData = $api->getHotelStaticData($city_id, $hotel->hotel_code);
	                    $utfReplaces = str_replace('utf-16', 'utf-8', $hotelData['HotelData']);
	                    $hotelXml = simplexml_load_string($utfReplaces, 'SimpleXMLElement', LIBXML_NOCDATA);
	                    $hotelDataArray = $this->xmlToArray($hotelXml);
	                    
	                    // echo "<pre>"; print_r($hotelDataArray); die();
	                    if (isset($hotelDataArray['ArrayOfBasicPropertyInfo']) && isset($hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo']) && !empty($hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'])) {
	                        $hotelDataFinal = $hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'];

	                        $hotel_code = $hotelDataFinal['@TBOHotelCode'];
	                        $hotel_name = $hotelDataFinal['@HotelName'];
	                        $start_rating = $hotelDataFinal['@BrandCode'];

	                        $hotel_facilities = array();
	                        $attractions = array();
	                        $hotel_description = array();
	                        $hotel_images = array();
	                        $room_images = array();

	                        $facilities = (isset($hotelDataFinal['VendorMessages']) && isset($hotelDataFinal['VendorMessages']['VendorMessage'])) ? $hotelDataFinal['VendorMessages']['VendorMessage'] : array();
	                        $hotel_location = isset($hotelDataFinal['Position']) ? $hotelDataFinal['Position'] : array();
	                        $hotel_address = isset($hotelDataFinal['Address']) ? $hotelDataFinal['Address'] : array();
	                        $hotel_contact = isset($hotelDataFinal['ContactNumbers']) ? $hotelDataFinal['ContactNumbers'] : array();
	                        $hotel_time = isset($hotelDataFinal['Policy']) ? $hotelDataFinal['Policy'] : array();
	                        $hotel_type = (isset($hotelDataFinal['HotelThemes']) && isset($hotelDataFinal['HotelThemes']['HotelTheme'])) ? $hotelDataFinal['HotelThemes']['HotelTheme'] : array();

	                        $room_images = array();

	                        if (isset($facilities)) {
	                            foreach ($facilities as $key => $facility) {

	                                if (isset($facility['@Title'])) {
	                                    if ($facility['@Title'] == 'Facilities') {

	                                        foreach ($facility['SubSection'] as $key_fac => $hotel_fac) {
	                                            if (isset($hotel_fac) && isset($hotel_fac['Paragraph'])) {
	                                                array_push($hotel_facilities, str_replace("'", " ", $hotel_fac['Paragraph']['Text']['$']));
	                                            }
	                                        }
	                                    }

	                                    if ($facility['@Title'] == 'Attractions') {
	                                        foreach ($facility['SubSection'] as $key_attrac => $hotel_attrac) {
	                                            if (isset($hotel_attrac) && isset($hotel_attrac['Text']) && isset($hotel_attrac['Text']['$'])) {
	                                                array_push($attractions, str_replace("'", " ", $hotel_attrac['Text']['$']));
	                                            } else {
	                                                if (isset($hotel_attrac['Paragraph']) && isset($hotel_attrac['Paragraph']['Text']) && isset($hotel_attrac['Paragraph']['Text']['$'])) {
	                                                    array_push($attractions, str_replace("'", " ", $hotel_attrac['Paragraph']['Text']['$']));
	                                                }
	                                            }
	                                        }
	                                    }

	                                    if ($facility['@Title'] == 'Hotel Description') {
	                                        if (isset($facility['SubSection']) && isset($facility['SubSection']['Paragraph']) && isset($facility['SubSection']['Paragraph']['Text']) && isset($facility['SubSection']['Paragraph']['Text']['$'])) {
	                                            array_push($hotel_description, str_replace("'", " ", $facility['SubSection']['Paragraph']['Text']['$']));
	                                        } else {
	                                            foreach ($facility['SubSection'] as $d) {
	                                                if (isset($d['Paragraph']) && isset($d['Paragraph']['Text']) && isset($d['Paragraph']['Text']['$'])) {
	                                                    array_push($hotel_description, str_replace("'", " ", $d['Paragraph']['Text']['$']));
	                                                }
	                                            }
	                                        }
	                                    }

	                                    if ($facility['@Title'] == 'Hotel Pictures') {

                                        	$s3 = AWS::createClient('s3');
	                                        $time = time();
	                                        foreach ($facility['SubSection'] as $key_hpic => $hotel_pic) {
	                                            if (isset($hotel_pic['Paragraph']) && isset($hotel_pic['Paragraph'][1])) {
	                                                if (isset($hotel_pic['Paragraph'][1]['URL']) && $hotel_pic['Paragraph'][1]['@Type'] == 'FullImage') {
	                                                    if ($key_hpic < 40) {

                                                    		$url = $hotel_pic['Paragraph'][1]['URL'];
	                                                    	//get file ext from url
	                                                    	$ext = 'jpg';
	                                                    	if (strpos($url, 'png') !== false) {
	                                                    		$ext = 'png';
	                                                    	} else if (strpos($url, 'gif') !== false) {
	                                                    		$ext = 'gif';
	                                                    	}

	                                                    	if(strrpos($url, "_z") === false) {
	                                                    		//donwload the fiel to local
	                                                    		$fileName =  time() . '.' . $ext;
														        $img = public_path('uploads') . '/hotel-images/' . $fileName;

													        	try{

													        		$content = @file_get_contents($url);
													        		if($content !== FALSE) {
													        			$fn = public_path('uploads') . '/hotel-images';
																		chmod($fn, 0777);
														        		$downloaded = file_get_contents($url);
																        file_put_contents($img, $downloaded);
				                                                    	
				                                                    	$s3->putObject(array(
																		    'Bucket'     => env('AWS_BUCKET'),
																		    'Key'        => $fileName ,
																		    'SourceFile' => $img,
																		    'ACL'        => 'public-read',
																		));

																		array_push($hotel_images, $fileName);
																		unlink($img);
																		
																	}
													        	} catch (Exception $e) {
													        	}
													        }
	                                                    }
	                                                }
	                                            }
	                                        }
	                                    }
	                                }
	                            }
	                        }

	                        if (isset($hotelDataFinal['Rooms']) && isset($hotelDataFinal['Rooms']['Room'])) {
	                            //check if added
	                            foreach ($hotelDataFinal['Rooms']['Room'] as $room_key => $hotel_room) {
	                                if (isset($hotel_room['RoomTypeName'])) {
	                                    $type = preg_replace('/\s*/', '', $hotel_room['RoomTypeName']);
	                                    $image = RoomImages::where(['r_type' => $type, 'sub_domain' => $hotel_code])->first();
	                                    if (isset($image) && isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
	                                        $temp_img = array();
	                                        foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
	                                            if (isset($room_image['ImageUrl'])) {
	                                                $url = $room_image['ImageUrl'];
                                                	//get file ext from url
                                                	$ext = 'jpg';
                                                	if (strpos($url, 'png') !== false) {
                                                		$ext = 'png';
                                                	} else if (strpos($url, 'gif') !== false) {
                                                		$ext = 'gif';
                                                	}

                                                	//check if its not thum
                                                	if(strrpos($url, "_t") === false) {
	                                            		//donwload the fiel to local
	                                            		$fileName =  time() . '.' . $ext;
												        $img = public_path('uploads') . '/hotel-images/' . $fileName;

											        	try{

											        		$content = @file_get_contents($url);
											        		if($content !== FALSE) {
											        			$fn = public_path('uploads') . '/hotel-images/';
																chmod($fn, 0777);
												        		$downloaded = file_get_contents($url);
														        file_put_contents($img, $downloaded);
		                                                    	
		                                                    	$s3->putObject(array(
																    'Bucket'     => env('AWS_BUCKET'),
																    'Key'        => $fileName ,
																    'SourceFile' => $img,
																    'ACL'        => 'public-read',
																));

																array_push($temp_img, $fileName);
																unlink($img);
																
															}
											        	} catch (Exception $e) {
											        	}
											        }
	                                            }
	                                        }
	                                        RoomImages::where(['r_type' => $type, 'sub_domain' => $hotel_code])
	                                                ->update(['images' => serialize($temp_img)]);
	                                    } else {
	                                        //create
	                                        $temp_img = array();
	                                        if (isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
	                                            foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
	                                                if (isset($room_image['ImageUrl'])) {

	                                                	$url = $room_image['ImageUrl'];
                                                    	//get file ext from url
                                                    	$ext = 'jpg';
                                                    	if (strpos($url, 'png') !== false) {
                                                    		$ext = 'png';
                                                    	} else if (strpos($url, 'gif') !== false) {
                                                    		$ext = 'gif';
                                                    	}

                                                		//check if its not thum
	                                                	if(strrpos($url, "_t") === false) {
		                                            		//donwload the fiel to local
		                                            		$fileName =  time() . '.' . $ext;
													        $img = public_path('uploads') . '/hotel-images/' . $fileName;

												        	try{

												        		$content = @file_get_contents($url);
												        		if($content !== FALSE) {
												        			$fn = public_path('uploads') . '/hotel-images/';
																	chmod($fn, 0777);
													        		$downloaded = file_get_contents($url);
															        file_put_contents($img, $downloaded);
			                                                    	
			                                                    	$s3->putObject(array(
																	    'Bucket'     => env('AWS_BUCKET'),
																	    'Key'        => $fileName ,
																	    'SourceFile' => $img,
																	    'ACL'        => 'public-read',
																	));

																	array_push($temp_img, $fileName);
																	unlink($img);
																	
																}
												        	} catch (Exception $e) {
												        	}
												        }
	                                                }
	                                            }
	                                            RoomImages::create(['r_type' => $type, 'sub_domain' => $hotel_code, 'name' => $hotel_room['RoomTypeName'], 'images' => serialize($temp_img)]);
	                                        }
	                                    }
	                                }
	                            }
	                        }

	                        // echo "<pre>"; print_r($hotel_images); die();
	                        StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $city_id])
	                                ->update(['hotel_name' => $hotel_name,
	                                    'start_rating' => $start_rating,
	                                    'hotel_facilities' => json_encode($hotel_facilities),
	                                    'hotel_contact' => json_encode($hotel_contact),
	                                    'attractions' => json_encode($attractions),
	                                    'hotel_description' => json_encode($hotel_description),
	                                    'hotel_images' => json_encode($hotel_images),
	                                    'hotel_location' => json_encode($hotel_location),
	                                    'lat' => (isset($hotelDataFinal['Position']) && isset($hotelDataFinal['Position']['@Latitude'])) ? $hotelDataFinal['Position']['@Latitude'] : '',
	                                    'lng' => (isset($hotelDataFinal['Position']) && isset($hotelDataFinal['Position']['@Longitude'])) ? $hotelDataFinal['Position']['@Longitude'] : '',
	                                    'hotel_address' => json_encode($hotel_address),
	                                    'hotel_time' => json_encode($hotel_time),
	                                    'hotel_type' => json_encode($hotel_type),
	                                    'data_updated' => 1,
	                                    'updated_at' => date('Y-m-d h:i:s')]);
	                    } else {
	                        StaticDataHotels::where(['hotel_code' => $hotel->hotel_code, 'city_id' => $city_id])->update(['data_updated' => 1]);
	                    }
		    		} catch (Exception $e) {
		    			File::append(public_path() . "/logs/static-data.log", $errorMessage);
		    			StaticDataHotels::where(['hotel_code' => $hotel->hotel_code, 'city_id' => $city_id])->update(['data_updated' => 1]);
		    		}
		    	}

		    	//check if all hotels are updated then update city flag
		    	$updated_hotels = DB::select("SELECT COUNT(id) AS `total_count` FROM `static_data_hotels` WHERE `city_id` = '" . $city_id . "' AND `data_updated` = '0'");
		    	if($updated_hotels[0]->total_count == 0) {
		    		Cities::where('CityId', $city_id)->update(['data_updated' => 1]);
		    	}

		    } catch (Exception $e) {
		    	echo $errorMessage = "\n" . $e->getMessage();
		    	File::append(public_path() . "/logs/static-data.log", $errorMessage);
		    }
		    die('Done');
		} else {
			//echo "not";
			try {
				$data = $api->getCityData($city_id);
				// Read entire file into string 
		        $xmlfile = file_get_contents($destinationPath);
		        $xmlfile = str_replace("=\\", "=", $xmlfile);
		        $xmlfile = str_replace('"<?xml', "<?xml", $xmlfile);
		        $xmlfile = str_replace('ArrayOfBasicPropertyInfo>"', "ArrayOfBasicPropertyInfo>", $xmlfile);
		        $xmlfile = str_replace('\"', '"', $xmlfile);
		        $xmlfile = str_replace('\/', '/', $xmlfile);
		        $xmlfile = str_replace('\r\n ', '', $xmlfile);
		        $xmlfile = str_replace('utf-16', 'utf-8', $xmlfile);
		        $xmlfile = str_replace('>\r\n<', '><', $xmlfile);

		        try {

		        	$newXml = simplexml_load_string($xmlfile, 'SimpleXMLElement', LIBXML_NOCDATA);

		        } catch (Exception $e) {
		        	$errorMessage = "\n" . $e->getMessage();
	 				file_put_contents(public_path() . "/logs/static-data.log", $errorMessage, FILE_APPEND);
	 				//update city flag
	 				Cities::where('CityId', $city_id)->update(['data_updated' => 1]);
	 				echo 'done';
	 				return;
		        }

		        // Convert into json 
		        $arrayData = $this->xmlToArray($newXml);
		        // echo "<pre>";

		        foreach ($arrayData['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'] as $key => $hotel) {

		            if (isset($hotel['@TBOHotelCode'])) {
		                //check if exits
		                $check = StaticDataHotels::where(['city_id' => $city_id, 'hotel_code' => $hotel['@TBOHotelCode']])->first();
		                if (isset($check)) {

		                    StaticDataHotels::where(['city_id' => $city_id, 'hotel_code' => $hotel['@TBOHotelCode']])
		                            ->update(['data_updated' => 0, 'start_rating' => $hotel['@BrandCode']]);
		                } else {

		                    StaticDataHotels::create(['hotel_name' => isset($hotel['@HotelName']) ? $hotel['@HotelName'] : '',
		                        'hotel_code' => $hotel['@TBOHotelCode'],
		                        'city_id' => $city_id,
		                        'start_rating' => $hotel['@BrandCode'],
		                        'data_updated' => 0]);
		                }
		            }
		        }
		    } catch (Exception $e) {
		    	$errorMessage = "\n" . $e->getMessage();
 				File::append(public_path() . "/logs/static-data.log", $errorMessage);
 				//update city flag
 				Cities::where('CityId', $city_id)->update(['data_updated' => 1]);
		    }

		    //now download hotel static data for that city
		    $hotels = DB::select("SELECT `hotel_code` FROM `static_data_hotels` WHERE `city_id` = '" . $city_id . "' AND `data_updated` = '0'  LIMIT 1");

		    
		    try {

		    	foreach ($hotels as $key => $hotel) {
		    		try {
		    			$hotelData = $api->getHotelStaticData($city_id, $hotel->hotel_code);
	                    $utfReplaces = str_replace('utf-16', 'utf-8', $hotelData['HotelData']);
	                    $hotelXml = simplexml_load_string($utfReplaces, 'SimpleXMLElement', LIBXML_NOCDATA);
	                    $hotelDataArray = $this->xmlToArray($hotelXml);
	                    if (isset($hotelDataArray['ArrayOfBasicPropertyInfo']) && isset($hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'])) {
	                        $hotelDataFinal = $hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'];

	                        $hotel_code = $hotelDataFinal['@TBOHotelCode'];
	                        $hotel_name = $hotelDataFinal['@HotelName'];
	                        $start_rating = $hotelDataFinal['@BrandCode'];

	                        $hotel_facilities = array();
	                        $attractions = array();
	                        $hotel_description = array();
	                        $hotel_images = array();
	                        $room_images = array();

	                        $facilities = (isset($hotelDataFinal['VendorMessages']) && isset($hotelDataFinal['VendorMessages']['VendorMessage'])) ? $hotelDataFinal['VendorMessages']['VendorMessage'] : array();
	                        $hotel_location = isset($hotelDataFinal['Position']) ? $hotelDataFinal['Position'] : array();
	                        $hotel_address = isset($hotelDataFinal['Address']) ? $hotelDataFinal['Address'] : array();
	                        $hotel_contact = isset($hotelDataFinal['ContactNumbers']) ? $hotelDataFinal['ContactNumbers'] : array();
	                        $hotel_time = isset($hotelDataFinal['Policy']) ? $hotelDataFinal['Policy'] : array();
	                        $hotel_type = (isset($hotelDataFinal['HotelThemes']) && isset($hotelDataFinal['HotelThemes']['HotelTheme'])) ? $hotelDataFinal['HotelThemes']['HotelTheme'] : array();

	                        $room_images = array();

	                        if (isset($facilities)) {
	                            foreach ($facilities as $key => $facility) {

	                                if (isset($facility['@Title'])) {
	                                    if ($facility['@Title'] == 'Facilities') {

	                                        foreach ($facility['SubSection'] as $key_fac => $hotel_fac) {
	                                            if (isset($hotel_fac) && isset($hotel_fac['Paragraph'])) {
	                                                array_push($hotel_facilities, str_replace("'", " ", $hotel_fac['Paragraph']['Text']['$']));
	                                            }
	                                        }
	                                    }

	                                    if ($facility['@Title'] == 'Attractions') {
	                                        foreach ($facility['SubSection'] as $key_attrac => $hotel_attrac) {
	                                            if (isset($hotel_attrac) && isset($hotel_attrac['Text']) && isset($hotel_attrac['Text']['$'])) {
	                                                array_push($attractions, str_replace("'", " ", $hotel_attrac['Text']['$']));
	                                            } else {
	                                                if (isset($hotel_attrac['Paragraph']) && isset($hotel_attrac['Paragraph']['Text']) && isset($hotel_attrac['Paragraph']['Text']['$'])) {
	                                                    array_push($attractions, str_replace("'", " ", $hotel_attrac['Paragraph']['Text']['$']));
	                                                }
	                                            }
	                                        }
	                                    }

	                                    if ($facility['@Title'] == 'Hotel Description') {
	                                        if (isset($facility['SubSection']) && isset($facility['SubSection']['Paragraph']) && isset($facility['SubSection']['Paragraph']['Text']) && isset($facility['SubSection']['Paragraph']['Text']['$'])) {
	                                            array_push($hotel_description, str_replace("'", " ", $facility['SubSection']['Paragraph']['Text']['$']));
	                                        } else {
	                                            foreach ($facility['SubSection'] as $d) {
	                                                if (isset($d['Paragraph']) && isset($d['Paragraph']['Text']) && isset($d['Paragraph']['Text']['$'])) {
	                                                    array_push($hotel_description, str_replace("'", " ", $d['Paragraph']['Text']['$']));
	                                                }
	                                            }
	                                        }
	                                    }

	                                    if ($facility['@Title'] == 'Hotel Pictures') {
	                                        foreach ($facility['SubSection'] as $key_hpic => $hotel_pic) {
	                                            if (isset($hotel_pic['Paragraph']) && isset($hotel_pic['Paragraph'][1])) {
	                                                if (isset($hotel_pic['Paragraph'][1]['URL']) && $hotel_pic['Paragraph'][1]['@Type'] == 'FullImage') {
	                                                    if ($key_hpic < 40) {
	                                                        array_push($hotel_images, $hotel_pic['Paragraph'][1]['URL']);
	                                                    }
	                                                }
	                                            }
	                                        }
	                                    }
	                                }
	                            }
	                        }

	                        if (isset($hotelDataFinal['Rooms']) && isset($hotelDataFinal['Rooms']['Room'])) {
	                            //check if added
	                            foreach ($hotelDataFinal['Rooms']['Room'] as $room_key => $hotel_room) {
	                                if (isset($hotel_room['RoomTypeName'])) {
	                                    $type = preg_replace('/\s*/', '', $hotel_room['RoomTypeName']);
	                                    $image = RoomImages::where(['r_type' => $type, 'sub_domain' => $hotel_code])->first();
	                                    if (isset($image) && isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
	                                        $temp_img = array();
	                                        foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
	                                            if (isset($room_image['ImageUrl'])) {
	                                                array_push($temp_img, $room_image['ImageUrl']);
	                                            }
	                                        }
	                                        RoomImages::where(['r_type' => $type, 'sub_domain' => $hotel_code])
	                                                ->update(['images' => serialize($temp_img)]);
	                                    } else {
	                                        //create
	                                        $temp_img = array();
	                                        if (isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
	                                            foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
	                                                if (isset($room_image['ImageUrl'])) {
	                                                    array_push($temp_img, $room_image['ImageUrl']);
	                                                }
	                                            }
	                                            RoomImages::create(['r_type' => $type, 'sub_domain' => $hotel_code, 'name' => $hotel_room['RoomTypeName'], 'images' => serialize($temp_img)]);
	                                        }
	                                    }
	                                }
	                            }
	                        }


	                        StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $city_id])
	                                ->update(['hotel_name' => $hotel_name,
	                                    'start_rating' => $start_rating,
	                                    'hotel_facilities' => json_encode($hotel_facilities),
	                                    'hotel_contact' => json_encode($hotel_contact),
	                                    'attractions' => json_encode($attractions),
	                                    'hotel_description' => json_encode($hotel_description),
	                                    'hotel_images' => json_encode($hotel_images),
	                                    'hotel_location' => json_encode($hotel_location),
	                                    'hotel_address' => json_encode($hotel_address),
	                                    'hotel_time' => json_encode($hotel_time),
	                                    'hotel_type' => json_encode($hotel_type),
	                                    'data_updated' => 1,
	                                    'updated_at' => date('Y-m-d h:i:s')]);
	                    }
		    		} catch (Exception $e) {
		    			$errorMessage = "\n" . $e->getMessage();
		    			File::append(public_path() . "/logs/static-data.log", $errorMessage);
		    			StaticDataHotels::where(['hotel_code' => $hotel->hotel_code, 'city_id' => $city_id])->update(['data_updated' => 1]);
		    		}
		    	}

		    	//check if all hotels are updated then update city flag
		    	$updated_hotels = DB::select("SELECT COUNT(id) AS `total_count` FROM `static_data_hotels` WHERE `city_id` = '" . $city_id . "' AND `data_updated` = '0'");
		    	if($updated_hotels[0]->total_count == 0) {
		    		Cities::where('CityId', $city_id)->update(['data_updated' => 1]);
		    	}

		    } catch (Exception $e) {
		    	$errorMessage = "\n" . $e->getMessage();
		    	File::append(public_path() . "/logs/static-data.log", $errorMessage);
		    }
		}

		echo 'done';
		die();
	}

	public function downloadHotelDataProgress() {
		//$hotel = StaticDataHotels::select('city_id')->orderBy('updated_at', 'desc')->first();		
		$city = Cities::where('data_updated',0)->select('CityName', 'CityId', 'Country')->orderBy('CityName', 'asc')->first();
		$updated_hotels = DB::select("SELECT COUNT(id) AS `total_count` FROM `static_data_hotels` WHERE `city_id` = '" . $city['CityId'] . "' AND `data_updated` = '1'");
        $pending_hotels = DB::select("SELECT COUNT(id) AS `total_count` FROM `static_data_hotels` WHERE `city_id` = '" . $city['CityId'] . "' AND `data_updated` = '0'");

        echo "Downloading data for city <b>" . $city['CityName'] . "</b> country name <b>" . $city['Country'] . "</b>";
        echo "\n" . "Total " . $updated_hotels[0]->total_count . " are updated and " . $pending_hotels[0]->total_count . "  are still pending.";
	}

	public function handle() {

	    //Log::debug(env('API_MODE_HOTEL', 'liv12e'));
	    //Log::Info(['data' => "hello world from handle"]);
//        die;
		ini_set('max_execution_time', 1000);
        ini_set('memory_limit', '1024M');
        $api = new TBOHotelAPI();
        $hotel_code = '1279415';//$this->hotel_code;
        $city_id = '115936';//$this->city_id;
        $ip = '127.0.0.1';

        try {
            $hotelData = $api->fetchHotelStaticData($city_id, $hotel_code, $ip);

            if(!empty($hotelData) && array_key_exists("HotelData",$hotelData)) {
                $utfReplaces = str_replace('utf-16', 'utf-8', $hotelData['HotelData']);
                //Log::Info(['fetchHotelStaticData:']);
		//
                //Log::Info(['fetchHotelStaticData:'=>$hotelData['HotelData']]);
                if (!strpos($hotelData['HotelData'], "TBOHotelCode")) {
                    StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $city_id])->update(['data_updated' => 2]);
                    return 1;
                }


                $hotelXml = simplexml_load_string($utfReplaces, 'SimpleXMLElement', LIBXML_NOCDATA);
                $hotelDataArray = $this->xmlToArray($hotelXml);
                //Log::Error(['fetchHotelStaticData:']);
                if (isset($hotelDataArray['ArrayOfBasicPropertyInfo']) && isset($hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'])) {
                    $hotelDataFinal = $hotelDataArray['ArrayOfBasicPropertyInfo']['BasicPropertyInfo'];

                    $hotel_code = $hotelDataFinal['@TBOHotelCode'];
                    $hotel_name = $hotelDataFinal['@HotelName'];
                    $start_rating = $hotelDataFinal['@BrandCode'];
                    $hotel_rooms = isset($hotelDataFinal['@NoOfRooms']) ? $hotelDataFinal['@NoOfRooms'] : null;
                    $hotel_floors = isset($hotelDataFinal['@NoOfFloors']) ? $hotelDataFinal['@NoOfFloors'] : null;
                    $build_year = isset($hotelDataFinal['@BuiltYear']) ? $hotelDataFinal['@BuiltYear'] : null;

                    $hotel_facilities = array();
                    $attractions = array();
                    $hotel_description = array();
                    $hotel_images = array();
                    $room_images = array();
                    $covid_info_array = array();
                    $room_amenities = array();
                    $hotel_category_images = array();
                    
                    $facilities = (isset($hotelDataFinal['VendorMessages']) && isset($hotelDataFinal['VendorMessages']['VendorMessage'])) ? $hotelDataFinal['VendorMessages']['VendorMessage'] : array();
                    $hotel_location = isset($hotelDataFinal['Position']) ? $hotelDataFinal['Position'] : array();
                    $hotel_address = isset($hotelDataFinal['Address']) ? $hotelDataFinal['Address'] : array();
                    $hotel_contact = isset($hotelDataFinal['ContactNumbers']) ? $hotelDataFinal['ContactNumbers'] : array();
                    $hotel_time = isset($hotelDataFinal['Policy']) ? $hotelDataFinal['Policy'] : array();
                    $hotel_type = (isset($hotelDataFinal['HotelThemes']) && isset($hotelDataFinal['HotelThemes']['HotelTheme'])) ? $hotelDataFinal['HotelThemes']['HotelTheme'] : array();
                    // $hotel_award = isset($hotelDataFinal['Award']) ? $hotelDataFinal['Award'] : [];
                    // $hotel_info = isset($hotelDataFinal['Attributes'])  ? $hotelDataFinal['Attributes'] : [];

                    $covid_info = (isset($hotelDataFinal['Attributes'])) ? $hotelDataFinal['Attributes'] : '';
                    $review_url = (isset($hotelDataFinal['Award'])) ? $hotelDataFinal['Award']['@ReviewURL'] : '';
                    $tp_ratings = (isset($hotelDataFinal['Award'])) ? $hotelDataFinal['Award']['@Rating'] : '';
                    //Log::Info($hotelDataFinal);
                    if (isset($facilities)) {
                        foreach ($facilities as $key => $facility) {

                            if (isset($facility['@Title'])) {
                                // if ($facility['@Title'] == 'Facilities') {

                                //     if(array_key_exists("SubSection",$facility) && isset($facility) && !empty($facility) && isset($facility['SubSection'])) {
                                //         foreach ($facility['SubSection'] as $key_fac => $hotel_fac) {
                                //             if (isset($hotel_fac) && isset($hotel_fac['Paragraph'])) {
                                //                 array_push($hotel_facilities, str_replace("'", " ", $hotel_fac['Paragraph']['Text']['$']));
                                //             }
                                //         }
                                //     }
                                // }

                                // if ($facility['@Title'] == 'Attractions') {
                                //     foreach ($facility['SubSection'] as $key_attrac => $hotel_attrac) {
                                //         if (isset($hotel_attrac) && isset($hotel_attrac['Text']) && isset($hotel_attrac['Text']['$'])) {
                                //             array_push($attractions, str_replace("'", " ", $hotel_attrac['Text']['$']));
                                //         } else {
                                //             if (isset($hotel_attrac['Paragraph']) && isset($hotel_attrac['Paragraph']['Text']) && isset($hotel_attrac['Paragraph']['Text']['$'])) {
                                //                 array_push($attractions, str_replace("'", " ", $hotel_attrac['Paragraph']['Text']['$']));
                                //             }
                                //         }
                                //     }
                                // }

                                // if ($facility['@Title'] == 'Hotel Description') {
                                //     if (isset($facility['SubSection']) && isset($facility['SubSection']['Paragraph']) && isset($facility['SubSection']['Paragraph']['Text']) && isset($facility['SubSection']['Paragraph']['Text']['$'])) {
                                //         array_push($hotel_description, str_replace("'", " ", $facility['SubSection']['Paragraph']['Text']['$']));
                                //     } else {
                                //         foreach ($facility['SubSection'] as $d) {
                                //             if (isset($d['Paragraph']) && isset($d['Paragraph']['Text']) && isset($d['Paragraph']['Text']['$'])) {
                                //                 array_push($hotel_description, str_replace("'", " ", $d['Paragraph']['Text']['$']));
                                //             }
                                //         }
                                //     }
                                // }

                                if ($facility['@Title'] == 'Hotel Pictures') {
                                   // $s3 = AWS::createClient('s3');
                                    $time = time();
                                    foreach ($facility['SubSection'] as $key_hpic => $hotel_pic) {
                                        if (isset($hotel_pic['Paragraph']) && isset($hotel_pic['Paragraph'][1])) {
                                            if (isset($hotel_pic['Paragraph'][1]['URL']) && $hotel_pic['Paragraph'][1]['@Type'] == 'FullImage') {
                                                if ($key_hpic < 40) {

                                                    $url = $hotel_pic['Paragraph'][1]['URL'];
                                                    //get file ext from url
                                                    $ext = 'jpg';
                                                    if (strpos($url, 'png') !== false) {
                                                        $ext = 'png';
                                                    } else if (strpos($url, 'gif') !== false) {
                                                        $ext = 'gif';
                                                    }

                                                    if(strrpos($url, "_z") === false) {
                                                        // array_push($hotel_images, $url);
                                                        //donwload the fiel to local
                                                        $fileName =  time() . '.' . $ext;
                                                        $img = public_path('s3') . '/' . $fileName;

                                                        try{

                                                            $content = @file_get_contents($url);
                                                            if($content !== FALSE) {
                                                                //$fn = public_path('s3') . '/';
                                                                //chmod($fn, 0777);
                                                                $downloaded = file_get_contents($url);
                                                                file_put_contents($img, $downloaded);
                                                                
                                                                /*$s3->putObject(array(
                                                                    'Bucket'     => env('AWS_BUCKET'),
                                                                    'Key'        => $fileName ,
                                                                    'SourceFile' => $img,
                                                                    'ACL'        => 'public-read',
                                                                ));*/

                                                                array_push($hotel_images, $fileName);
                                                                //unlink($img);

                                                            }
                                                        } catch (Exception $e) {
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (isset($hotelDataFinal['Rooms']) && isset($hotelDataFinal['Rooms']['Room'])) {
                        //check if added
                        $room_ameneties = array();
                        $bed_types = array();
                        foreach ($hotelDataFinal['Rooms']['Room'] as $room_key => $hotel_room) {
                            if (isset($hotel_room['RoomTypeName'])) {

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
                                if (isset($image) && isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                    $temp_img = array();
                                    foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                        if (isset($room_image['ImageUrl'])) {
                                            $url = $room_image['ImageUrl'];
                                            //get file ext from url
                                            $ext = 'jpg';
                                            if (strpos($url, 'png') !== false) {
                                                $ext = 'png';
                                            } else if (strpos($url, 'gif') !== false) {
                                                $ext = 'gif';
                                            }

                                            //check if its not thum
                                            if(strrpos($url, "_t") === false) {
                                                // array_push($temp_img, $url);
                                                //donwload the fiel to local
                                                $fileName =  time() . '.' . $ext;
                                                $img = public_path('s3') . '/' . $fileName;

                                                try{

                                                    $content = @file_get_contents($url);
                                                    if($content !== FALSE) {
                                                        $fn = public_path('s3') . '/';
                                                        // chmod($fn, 0777);
                                                        $downloaded = file_get_contents($url);
                                                        file_put_contents($img, $downloaded);

                                                        array_push($temp_img, $fileName);
                                                        
                                                    }
                                                } catch (Exception $e) {
                                                }
                                            }
                                        }
                                    }

                                     RoomImages::where(['r_type' => $type, 'sub_domain' => $hotel_code])
                                                    ->update(['images' => serialize($temp_img), 'ameneties' => json_encode($room_ameneties), 'bed_type' => json_encode($bed_types)]);
                                    // RoomImages::where(['r_type' => $type, 'sub_domain' => $hotel_code])
                                    //         ->update(['images' => serialize($temp_img)]);
                                } else {
                                    //create
                                    $temp_img = array();
                                    if (isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                        foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                            if (isset($room_image['ImageUrl'])) {

                                                $url = $room_image['ImageUrl'];
                                                //get file ext from url
                                                $ext = 'jpg';
                                                if (strpos($url, 'png') !== false) {
                                                    $ext = 'png';
                                                } else if (strpos($url, 'gif') !== false) {
                                                    $ext = 'gif';
                                                }

                                                //check if its not thum
                                                if(strrpos($url, "_t") === false) {
                                                // array_push($temp_img, $url);
                                                    //donwload the fiel to local
                                                    $fileName =  time() . '.' . $ext;
                                                    $img = public_path('s3') . '/' . $fileName;

                                                    try{

                                                        $content = @file_get_contents($url);
                                                        if($content !== FALSE) {
                                                            $fn = public_path('s3') . '/';
                                                            chmod($fn, 0777);
                                                            $downloaded = file_get_contents($url);
                                                            file_put_contents($img, $downloaded);

                                                            array_push($temp_img, $fileName);
                                                            
                                                        }
                                                    } catch (Exception $e) {
                                                    }
                                                }
                                            }
                                        }

                                        // RoomImages::create(['r_type' => is_array($type)?$type:'', 'sub_domain' => $hotel_code, 'name' => is_array($hotel_room['RoomTypeName'])?$hotel_room['RoomTypeName']:'', 'images' => serialize($temp_img)]);
                                        RoomImages::create(['r_type' => $type, 'sub_domain' => $hotel_code, 'name' => $hotel_room['RoomTypeName'], 'images' => serialize($temp_img), 'ameneties' => json_encode($room_ameneties), 'bed_type' => json_encode($bed_types)]);
                                    }
                                }

                                // if(isset($hotel_room['Faciltities']) && isset($hotel_room['Faciltities']['RoomFacility']) && !empty($hotel_room['Faciltities']['RoomFacility'])) {
                                        
                                //     foreach ($hotel_room['Faciltities']['RoomFacility'] as $key => $r_fac) {
                                //         if(isset($r_fac['FacilityName']) && $r_fac['FacilityName'] != '') {
                                //             array_push($room_amenities, $r_fac['FacilityName']);
                                //         }
                                //     }
                                // }
                            }
                        }
                    }

                    // if(isset($covid_info) && !empty($covid_info)) {
                    //     foreach ($covid_info['Attribute'] as $c_key => $c_info) {
                    //         if(isset($c_info['@AttributeType']) && $c_info['@AttributeType'] == 'Covid Info') {
                    //             array_push($covid_info_array, $c_info['@AttributeName']);
                    //         }
                    //     }
                    // }


                    StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $city_id])
                            ->update(['hotel_name' => $hotel_name,
                                //'start_rating' => $start_rating,
                                //'hotel_rooms' => $hotel_rooms,
                                //'hotel_floors' => $hotel_floors,
                                //'build_year' => $build_year,
                                //'hotel_facilities' => json_encode($hotel_facilities),
                                //'hotel_contact' => json_encode($hotel_contact),
                                //'attractions' => json_encode($attractions),
                                //'hotel_description' => json_encode($hotel_description),
                                'hotel_images' => json_encode($hotel_images),
                                //'category_image' => json_encode($hotel_category_images),
                                //'hotel_location' => '',
                                'lat' => (isset($hotelDataFinal['Position']) && isset($hotelDataFinal['Position']['@Latitude'])) ? $hotelDataFinal['Position']['@Latitude'] : '',
                                'lng' => (isset($hotelDataFinal['Position']) && isset($hotelDataFinal['Position']['@Longitude'])) ? $hotelDataFinal['Position']['@Longitude'] : '',
                                //'hotel_address' => json_encode($hotel_address),
                                //'hotel_time' => json_encode($hotel_time),
                                //'hotel_type' => json_encode($hotel_type),
                                'data_updated' => 1,
                                //'tp_ratings' => $tp_ratings,
                                //'hotel_award' => $review_url,
                                //'hotel_info' => json_encode($covid_info_array),
                                //'room_amenities' => json_encode($room_amenities),
                                'updated_at' => date('Y-m-d h:i:s')]);
                            echo "updated hotel is " . $hotel_code; die();
                }
            } else {
                StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $city_id])->update(['data_updated' => 2,'updated_at' => date('Y-m-d h:i:s')]);
            }
        } catch (Exception $e) {
            $errorMessage = "\n" . $e->getMessage();
            Log::info(['fetchHotelStaticData:' => $errorMessage]);
            //File::append(public_path() . "/logs/static-data.log", $errorMessage);
            StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $city_id])->update(['data_updated' => 2,'updated_at' => date('Y-m-d h:i:s')]);
        }
    }

	public function xmlToArray($xml, $options = array()) {
        $defaults = array(
            'namespaceSeparator' => ':', //you may want this to be something other than a colon
            'attributePrefix' => '@', //to distinguish between attributes and nodes with the same name
            'alwaysArray' => array(), //array of xml tag names which should always become arrays
            'autoArray' => true, //only create arrays for tags which appear more than once
            'textContent' => '$', //key used for the text content of elements
            'autoText' => true, //skip textContent key if node has no attributes or child nodes
            'keySearch' => false, //optional search and replace on tag and attribute names
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
                if ($options['keySearch'])
                    $attributeName = str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
                $attributeKey = $options['attributePrefix']
                        . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                        . $attributeName;
                $attributesArray[$attributeKey] = (string) $attribute;
            }
        }

        //get child nodes from all namespaces
        $tagsArray = array();
        foreach ($namespaces as $prefix => $namespace) {
            foreach ($xml->children($namespace) as $childXml) {
                //recurse into child nodes
                $childArray = $this->xmlToArray($childXml, $options);
                foreach ($childArray as $key => $value) {
                    // list($childTagName, $childProperties);
                    $childTagName = $key;
                    $childProperties = $value;
                }

                //replace characters in tag name
                if ($options['keySearch'])
                    $childTagName = str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
                //add namespace prefix, if any
                if ($prefix)
                    $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;

                if (!isset($tagsArray[$childTagName])) {
                    //only entry with this key
                    //test if tags of this type should always be arrays, no matter the element count
                    $tagsArray[$childTagName] = in_array($childTagName, $options['alwaysArray']) || !$options['autoArray'] ? array($childProperties) : $childProperties;
                } elseif (
                        is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName]) === range(0, count($tagsArray[$childTagName]) - 1)
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
        $plainText = trim((string) $xml);
        if ($plainText !== '')
            $textContentArray[$options['textContent']] = $plainText;

        //stick it all together
        $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '') ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;

        //return node as array
        return array(
            $xml->getName() => $propertiesArray
        );
    }
}