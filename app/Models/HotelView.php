<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class HotelView extends Authenticatable
{
    use Notifiable;

    protected $connection = 'pgsql';
    public $table = 'static_data_hotels_tbl_il';
    //protected $connection = null;
    //protected $table = null;
    
    
    public function __construct() {

        parent::__construct();

    }
    
    public function scopeFromTable($query, $tableName) 
    {
        $query->from('static_data_hotels_tbl_il');
    }
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hotel_name', 'hotel_code', 'hotel_rooms', 'hotel_floors', 'build_year', 'category_image', 'city_id', 'hotel_facilities', 'attractions', 'hotel_description', 'hotel_images', 'hotel_location', 'hotel_address', 'hotel_contact', 'hotel_time', 'hotel_type', 'data_updated' , 'tp_ratings', 'hotel_award', 'hotel_info'
    ];

}
