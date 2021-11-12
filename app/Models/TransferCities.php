<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TransferCities extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id', 'city_name', 'city_code', 'station_id', 'station_name', 'country_name', 'country_code', 'port_name', 'port_id', 'port_destination', 'airport_code', 'airport_name', 'type'
    ];

}
