<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CloudObject extends Model
{
    protected $guarded = [];

    protected $appends = ['download_url','info_url'];

    protected $casts = ['is_active' => 'boolean'];

    public function getInfoUrlAttribute()
    {
        $url = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('cloudObjects.show', ['cloudObject' => $this->getKey()]);
        return $url;
    }

    public function getDownloadUrlAttribute()
    {
        $url = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('cloudObjects.download', ['cloudObject' => $this->getKey(), 'filename' => $this->filename]);
        return $url;
    }
}
