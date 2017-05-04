<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = ['ibge_code'];

    protected $casts = [
        'lat' => 'double',
        'lng' => 'double'
    ];

    public function state()
    {
        return $this->belongsTo('App\State','uf');
    }
}
