<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Log;
use Illuminate\Routing\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $router->model('user','App\User');

        $router->bind('application', function($idOrSlug, $route) {
            return \App\Application::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
        });

        $router->bind('notification', function($idOrSlug, $route) {
            $application = $route->parameter('application');
            if ($application) {
                return $application->notifications()->where('id', $idOrSlug)->first();
            }
            return null;
        });

        $router->bind('device', function($idOrSlug, $route) {
            $application = $route->parameter('application');
            if ($application) {
                return $application->devices()->where('id', $idOrSlug)->first();
            }
            return null;
        });
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapWebRoutes($router);

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
