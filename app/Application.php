<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public $timestamps = true;

    protected $guarded = [];

    protected $hidden = ['apns_certificate_sandbox', 'apns_certificate_production', 'apns_root_certificate', 'apns_certificate_password', 'gcm_api_key'];

    protected $casts = [];

    public function devices()
    {
        return $this->hasMany('App\Device');
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }
}
