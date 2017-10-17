<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Device;

class Application extends Model
{
    public $timestamps = true;

    protected $guarded = [];

    protected $hidden = ['apns_certificate_sandbox', 'apns_certificate_production', 'apns_root_certificate', 'apns_certificate_password', 'gcm_api_key'];

    protected $casts = [];

    protected $appends = [
        'devices_count',
        'notifications_count'
    ];

    public function devices()
    {
        return $this->hasMany('App\Device');
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function fake_pages()
    {
        return $this->hasMany('App\FakePage');
    }

    public function getDevicesCountAttribute()
    {
        return Device::onlyActive()->where('application_id', $this->getKey())->count();
    }

    public function getNotificationsCountAttribute()
    {
        return $this->notifications()->count();
    }
}
