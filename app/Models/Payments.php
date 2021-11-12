<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Payments extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id', 'user_id', 'agent_id' , 'commission', 'price', 'price_convered', 'partners_commision','partners_commision_rest','customer_id', 'sub_domain', 'withdraw_status', 'agent_markup'
    ];

}
