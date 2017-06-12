<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FakePage extends Model
{
	public $timestamps = false;

    protected $guarded = [];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeOnlyActive()
    {
    	return $this->where('is_active', true);
    }
}
