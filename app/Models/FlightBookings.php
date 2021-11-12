<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class FlightBookings extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id', 'user_id', 'trace_id', 'token_id', 'is_lcc','flight_booking_status','invoice_number','pnr', 'booking_ref', 'price_changed', 'cancellation_policy', 'sub_domain', 'request_data'
    ];

}
