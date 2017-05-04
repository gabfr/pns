<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public $timestamps = true;

    protected $guarded = [];

    protected $hidden = [];

    protected $casts = [];

    public function application()
    {
        return $this->belongsTo('App\Application');
    }

    public function notification_deliveries()
    {
        return $this->hasMany('App\NotificationDelivery');
    }
}
