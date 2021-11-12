<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MetaTags extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sub_domain', 'title', 'description', 'keywords', 'author', 'viewport', 'robots', 'canonical', 'view_id', 'google_site_verification', 'google_analytics_code'
    ];

}
