<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StaticDataHotels;
use App\Models\Cities;
use App\Services\TBOHotelAPI;
use Log;

class FetchStaticDataCmd extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staticdatacmd:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add or Update Static data from Hotels';
    protected $ip;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    public function handle() {

         Log::info("Called staticdatacmd");
        
        $this->ip = $this->getIp();

        $hotels = StaticDataHotels::where(function ($q) {
                    $currDate = date("Y-m-d", strtotime("-15 days"));
                    $q->where('data_updated', '0')->orWhereDate('updated_at', '<', $currDate);
                })->select('hotel_code', 'city_id')->take(1000)->get();

        if ($hotels) {
            try {
                foreach ($hotels as $key => $hotel) {

                    $this->getStaticData($hotel->hotel_code, $hotel->city_id);
                    //Log::info("Called staticdatacmd");
                }
            } catch (Exception $e) {
                dd($e->getMessage());
                $errorMessage = "\n" . $e->getMessage();
                Log::debug(['HotelStaticCmd:' => $errorMessage]);
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getStaticData($hotel_code ,$city_id) {

        $api = new TBOHotelAPI();
        $ip = $this->ip;

        try {
            $hotelData = $api->fetchHotelStaticData($city_id, $hotel_code, $ip);

            if (!empty($hotelData) && array_key_exists("HotelData", $hotelData)) {
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
                    $hotel_rooms = $hotelDataFinal['@NoOfRooms'];
                    $hotel_floors = $hotelDataFinal['@NoOfFloors'];
                    $build_year = $hotelDataFinal['@BuiltYear'];

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
                                if ($facility['@Title'] == 'Facilities') {

                                    if (array_key_exists("SubSection", $facility) && isset($facility) && !empty($facility) && isset($facility['SubSection'])) {
                                        foreach ($facility['SubSection'] as $key_fac => $hotel_fac) {
                                            if (isset($hotel_fac) && isset($hotel_fac['Paragraph'])) {
                                                array_push($hotel_facilities, str_replace("'", " ", $hotel_fac['Paragraph']['Text']['$']));
                                            }
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

                                                unset($hotel_pic['Paragraph'][0]);
                                                array_push($hotel_category_images, $hotel_pic);
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

                                if (isset($hotel_room['Faciltities']) && isset($hotel_room['Faciltities']['RoomFacility'])) {
                                    foreach ($hotel_room['Faciltities']['RoomFacility'] as $key => $r_fac) {

                                        if (isset($r_fac['FacilityName']) && !empty($r_fac['FacilityName'])) {
                                            array_push($room_ameneties, $r_fac['FacilityName']);
                                        }
                                    }
                                }

                                if (isset($hotel_room['BedTypes']) && isset($hotel_room['BedTypes']['BedType'])) {
                                    $bed_types['beds'] = $hotel_room['BedTypes']['BedType'];
                                }

                                $bed_types['room_size'] = array('sf' => $hotel_room['RoomSizeFeet'], 'sm' => $hotel_room['RoomSizeMeter'], 'eb' => $hotel_room['AllowExtraBed']);


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
                                            ->update(['images' => serialize($temp_img), 'ameneties' => $room_ameneties, 'bed_type' => $bed_types]);
                                    // RoomImages::where(['r_type' => $type, 'sub_domain' => $hotel_code])
                                    //         ->update(['images' => serialize($temp_img)]);
                                } else {
                                    //create
                                    $temp_img = array();
                                    if (isset($hotel_room['RoomImages']) && isset($hotel_room['RoomImages']['RoomImage'])) {
                                        foreach ($hotel_room['RoomImages']['RoomImage'] as $key => $room_image) {
                                            if (isset($room_image['ImageUrl'])) {
                                                array_push($temp_img, $room_image['ImageUrl']);
                                            }
                                        }

                                        // RoomImages::create(['r_type' => is_array($type)?$type:'', 'sub_domain' => $hotel_code, 'name' => is_array($hotel_room['RoomTypeName'])?$hotel_room['RoomTypeName']:'', 'images' => serialize($temp_img)]);
                                        RoomImages::create(['r_type' => $type, 'sub_domain' => $hotel_code, 'name' => $hotel_room['RoomTypeName'], 'images' => serialize($temp_img), 'ameneties' => $room_ameneties, 'bed_type' => $bed_types]);
                                    }
                                }

                                if (isset($hotel_room['Faciltities']) && isset($hotel_room['Faciltities']['RoomFacility']) && !empty($hotel_room['Faciltities']['RoomFacility'])) {

                                    foreach ($hotel_room['Faciltities']['RoomFacility'] as $key => $r_fac) {
                                        if (isset($r_fac['FacilityName']) && $r_fac['FacilityName'] != '') {
                                            array_push($room_amenities, $r_fac['FacilityName']);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (isset($covid_info) && !empty($covid_info)) {
                        foreach ($covid_info['Attribute'] as $c_key => $c_info) {
                            if (isset($c_info['@AttributeType']) && $c_info['@AttributeType'] == 'Covid Info') {
                                array_push($covid_info_array, $c_info['@AttributeName']);
                            }
                        }
                    }


                    StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $city_id])
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
                                'room_amenities' => json_encode($room_amenities),
                                'updated_at' => date('Y-m-d h:i:s')]);
                }
            } else {
                StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $city_id])->update(['data_updated' => 2, 'updated_at' => date('Y-m-d h:i:s')]);
            }
        } catch (Exception $e) {
            $errorMessage = "\n" . $e->getMessage();
            Log::info(['fetchHotelStaticData:' => $errorMessage]);
            //File::append(public_path() . "/logs/static-data.log", $errorMessage);
            StaticDataHotels::where(['hotel_code' => $hotel_code, 'city_id' => $city_id])->update(['data_updated' => 2, 'updated_at' => date('Y-m-d h:i:s')]);
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
