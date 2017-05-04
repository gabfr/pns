<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationDelivery extends Model
{
    const AGUARDANDO = "AGUARDANDO";
    const ENVIADO = "ENVIADO";
    const ERRO = "ERRO";
    const CAPTURADO = "CAPTURADO";
    
    public $timestamps = true;

    protected $guarded = [];

    protected $hidden = [];

    protected $casts = [];

    public function application()
    {
        return $this->belongsTo('App\Application');
    }

    public function device()
    {
        return $this->belongsTo('App\Device');
    }
}
