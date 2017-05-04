<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = true;

    protected $guarded = [];

    protected $hidden = ['created_by'];

    protected $casts = [];

    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function application()
    {
        return $this->belongsTo('App\Application');
    }

    public function notification_deliveries()
    {
        return $this->hasMany('App\NotificationDelivery');
    }
}
