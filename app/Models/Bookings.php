<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Bookings extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id', 'type', 'user_id', 'trace_id', 'token_id', 'status','hotel_booking_status','invoice_number','confirmation_number', 'booking_ref', 'price_changed', 'cancellation_policy','last_cancellation_date', 'sub_domain', 'request_data'
    ];

}
