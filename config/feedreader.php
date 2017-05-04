<?php

return [


    'engine' => EduardoStuart\FeedReader\Engines\SimplePie\SimplePieEngine::class,

    /**
     * Cache settings
     *
     * Here you can enable or disable cache feature and set the cache location
     */
   'cache' => [

        // enable or disable cache
        'is_enabled' => true,

        // cache location
        'location'   => storage_path('cache/feeds'),
   ],


];