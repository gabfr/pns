<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\NormalizeTrait as Normalizer;
use Log;

class User extends Authenticatable
{
    use Normalizer;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected static $normalize = [
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_super' => 'boolean'
    ];

    protected $fillable = [
        'name', 'email', 'password'
    ];

    public static function boot()
    {
        parent::boot();

        // Encrypt password
        static::creating(function($model){
            $model->password = bcrypt(trim($model->password));
        });
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function socialIntegrations()
    {
        return $this->hasMany('App\UserIntegration');
    }

    public function scopeOnlySuper($query)
    {
        return $query->where('is_super', 1);
    }

    public function checkPassword($password)
    {
        return password_verify($password, $this->password);
    }


}
