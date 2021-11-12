<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AffiliateUsers extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'referal_code','commission' , 'company_name', 'company_reg_num' , 'company_cert' , 'company_emp' , 'company_yr_rev' , 'company_loc' , 'company_hear','bussiness_phone','services','other_services','years_in_business','monthly_deals','specific_destinations','comments'
    ];

}
