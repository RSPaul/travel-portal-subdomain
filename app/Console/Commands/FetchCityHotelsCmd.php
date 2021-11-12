<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StaticDataHotels;
use App\Models\Cities;
use App\Services\TBOHotelAPI;
use Log;

class FetchCityHotelsCmd extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelcitycmd:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all new hotels from City';
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
        
        Log::info("Called hotelcitycmd");
        
        $this->ip = $this->getIp();
        
        $currDate = date("Y-m-d", strtotime("-15 days"));
        
        $cities = Cities::whereDate('updated_at', '<', $currDate)->select('CityId')->take(500)->get();

        foreach ($cities as $city) {

            $city_id = $city->CityId;

            try {
                $this->getHotel($city_id);
                //Log::info("Called hotelcitycmd");
            } catch (Exception $e) {
                $errorMessage = "\n" . $e->getMessage();
                Log::debug(['CityCmd Error:' => $errorMessage]);
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getHotel($city_id) {

        $api = new TBOHotelAPI();
        $ip = $this->ip;
        $todayD = date('Y-m-d');
        try {
            $data = $api->fetchCityData($city_id, $ip);

            if (!strpos($data['HotelData'], 'TBOHotelCode')) {

                Cities::where('CityId', $city_id)->update(['data_updated' => 2, 'updated_at' => $todayD]);
                return 1;
            }

            try {

                $utfReplaces = str_replace('utf-16', 'utf-8', $data['HotelData']);
                $newXml = simplexml_load_string($utfReplaces, 'SimpleXMLElement', LIBXML_NOCDATA);
            } catch (Exception $e) {
                $errorMessage = "\n" . $e->getMessage();
                Log::info(['fetchCityData:' => $errorMessage]);
                //update city flag
                Cities::where('CityId', $city_id)->update(['data_updated' => 2, 'updated_at' => $todayD]);
                return 1;
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
                                ->update(['data_updated' => 1, 'start_rating' => $hotel['@BrandCode']]);
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
            Log::info(['fetchCityData:' => $errorMessage]);
            Cities::where('CityId', $city_id)->update(['data_updated' => 2, 'updated_at' => $todayD]);
            //update city flag
            return 0;
        }
        Cities::where('CityId', $city_id)->update(['data_updated' => 1, 'updated_at' => $todayD]);
        return 1;
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
