<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    protected $binders = [
        'UserRepositoryContract' => 'UserRepository',
        'CityRepositoryContract' => 'CityRepository'
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach( $this->binders as $interface => $class ){
            $this->app->bind(
                "App\\Repositories\\Contracts\\{$interface}",
                "App\\Repositories\\{$class}"
            );
        }
    }
}
