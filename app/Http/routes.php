<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1',['namespace'=>'App\Http\Controllers\Api\V1','middleware' => 'cors'],function($api){

    $api->get('/',function(){
        return 'Push Notification Service';
    });

    // Create a new access token (basic auth)
    $api->post('access-token',['as'=>'auth.access-token','uses'=>'AccessTokenController@request']);
    $api->post('remember-password', ['as'=>'password.remember', 'uses'=>'PasswordController@remember']);
    $api->post('reset-password', ['as'=>'password.reset', 'uses'=>'PasswordController@reset']);

    $api->get('social-auth/user/{driver}',['as'=>'auth.social','uses'=>'SocialAuthentication@getUserFromToken','middleware'=>'web']);
    $api->get('social-auth/{driver}',['as'=>'auth.social','uses'=>'SocialAuthentication@requestUrl','middleware'=>'web']);
    $api->get('social-auth/{driver}/callback',['as'=>'auth.social.callback','uses'=>'SocialAuthentication@callback','middleware'=>'web']);

    // User Routes

    $api->get('cities', ['as' => 'cities.all', 'uses' => 'CitiesController@all']);
    $api->post('cities/zipcodes', ['as' => 'cities.zipcodes', 'uses' => 'CitiesController@zipcode']);

    $api->post('applications/{application}/devices', ['as' => 'devices.create', 'uses' => 'DeviceController@create']);

    $api->group(['prefix' => 'fake-pages'], function($api) {
        $api->get('/', ['as' => 'fakePages.index', 'uses' => 'FakePagesController@index']);
        $api->group(['middleware' => 'api.auth'], function($api) {
            $api->post('/', ['as' => 'fakePages.store', 'uses' => 'FakePagesController@store']);
            $api->get('{fakePage}', ['as' => 'fakePages.show', 'uses' => 'FakePagesController@show']);
            $api->put('{fakePage}', ['as' => 'fakePages.update', 'uses' => 'FakePagesController@update']);
            $api->delete('{fakePage}', ['as' => 'fakePages.delete', 'uses' => 'FakePagesController@delete']);
        });
    });

    $api->group(['prefix' => 'cloud-objects'], function($api) {
        $api->get('/', ['as' => 'cloudObjects.index', 'uses' => 'CloudObjectsController@index']);
        $api->get('{cloudObject}', ['as' => 'cloudObjects.show', 'uses' => 'CloudObjectsController@show']);
        $api->get('{cloudObject}/download/{filename}', ['as' => 'cloudObjects.download', 'uses' => 'CloudObjectsController@download']);
        $api->group(['middleware' => 'api.auth'], function($api) {
            $api->post('/', ['as' => 'cloudObjects.store', 'uses' => 'CloudObjectsController@store']);
            $api->put('{cloudObject}', ['as' => 'cloudObjects.update', 'uses' => 'CloudObjectsController@update']);
            $api->delete('{cloudObject}', ['as' => 'cloudObjects.delete', 'uses' => 'CloudObjectsController@delete']);
        });
    });

    $api->group(['middleware'=>'api.auth'],function($api){
        $api->get('/me',['as'=>'users.me','uses'=>'UserController@me']);
        $api->group(['prefix' => 'users', 'middleware' => 'staff'], function($api) {
            $api->get('/', ['as' => 'users.index', 'uses' => 'UserController@index']);
            $api->post('/', ['as' => 'users.store', 'uses' => 'UserController@store']);
            $api->get('{user}', ['as' => 'users.show', 'uses' => 'UserController@show']);
            $api->delete('{user}', ['as' => 'users.delete', 'uses' => 'UserController@delete']);
            $api->put('{user}', ['as' => 'users.update', 'uses' => 'UserController@update']);
        });

        $api->group(['prefix'=>'applications'], function($api) {
            $api->get('/', ['as' => 'applications.index', 'uses' => 'ApplicationController@index']);
            $api->post('/', ['as' => 'applications.create', 'uses' => 'ApplicationController@create']);

            $api->group(['prefix' => '{application}'], function($api) {
                $api->get('/', ['as' => 'applications.show', 'uses' => 'ApplicationController@show']);
                $api->put('/', ['as' => 'applications.update', 'uses' => 'ApplicationController@update']);
                $api->delete('/', ['as' => 'applications.delete', 'uses' => 'ApplicationController@delete']);

                $api->post('apns', ['as' => 'applications.update_apns', 'uses' => 'ApplicationController@updateApns']);
                $api->post('gcm', ['as' => 'applications.update_gcm', 'uses' => 'ApplicationController@updateGcm']);

                $api->group(['prefix' => 'notifications'], function($api) {
                    $api->get('/', ['as' => 'notifications.index', 'uses' => 'NotificationController@index']);
                    $api->post('/', ['as' => 'notifications.create', 'uses' => 'NotificationController@create']);

                    $api->group(['prefix' => '{notification}'], function($api) {
                        $api->get('/', ['as' => 'notifications.show', 'uses' => 'NotificationController@show']);
                        $api->put('/', ['as' => 'notifications.update', 'uses' => 'NotificationController@update']);
                        $api->delete('/', ['as' => 'notifications.delete', 'uses' => 'NotificationController@delete']);
                        $api->get('deliveries', ['as' => 'notifications.deliveries', 'uses' => 'NotificationController@deliveries']);
                        $api->post('send', ['as' => 'notifications.send', 'uses' => 'NotificationController@send']);
                    });

                });

                $api->group(['prefix' => 'devices'], function($api) {
                    $api->get('/', ['as' => 'devices.index', 'uses' => 'DeviceController@index']);
                    //$api->post('/', ['as' => 'devices.create', 'uses' => 'DeviceController@create']);
                    // => This endpoint should not require authenticated access to the API

                    $api->group(['prefix' => '{device}'], function($api) {
                        $api->get('/', ['as' => 'devices.show', 'uses' => 'DeviceController@show']);
                        $api->put('/', ['as' => 'devices.update', 'uses' => 'DeviceController@update']);
                        $api->delete('/', ['as' => 'devices.delete', 'uses' => 'DeviceController@delete']);
                        $api->get('deliveries', ['as' => 'devices.deliveries', 'uses' => 'DeviceController@deliveries']);
                    });

                });

            });

        });

    });


});