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
    $api->group(['prefix'=>'users','middleware' => 'cors'],function($api){
        $api->post('/',['as'=>'users.store','uses'=>'UserController@store']);
    });

    $api->get('cities', ['as' => 'cities.all', 'uses' => 'CitiesController@all']);
    $api->post('cities/zipcodes', ['as' => 'cities.zipcodes', 'uses' => 'CitiesController@zipcode']);

    $api->group(['middleware'=>'api.auth'],function($api){
        $api->get('/me',['as'=>'users.me','uses'=>'UserController@me']);

        $api->group(['prefix'=>'applications'], function($api) {
            $api->get('/', ['as' => 'applications.index', 'uses' => 'ApplicationController@index']);
            $api->get('{application}', ['as' => 'applications.show', 'uses' => 'ApplicationController@show']);
            $api->post('/', ['as' => 'applications.create', 'uses' => 'ApplicationController@create']);
            $api->put('{application}', ['as' => 'applications.update', 'uses' => 'ApplicationController@update']);
            $api->delete('{application}', ['as' => 'applications.delete', 'uses' => 'ApplicationController@delete']);
            $api->group(['prefix' => '{application}'], function($api) {
                $api->post('apns', ['as' => 'applications.update_apns', 'uses' => 'ApplicationController@updateApns']);
                $api->post('gcm', ['as' => 'applications.update_gcm', 'uses' => 'ApplicationController@updateGcm']);
                $api->group(['prefix' => 'notifications'], function($api) {
                    $api->get('/', ['as' => 'notifications.index', 'uses' => 'NotificationController@index']);
                    $api->post('/', ['as' => 'notifications.create', 'uses' => 'NotificationController@create']);
                    $api->group(['prefix' => '{notification}'], function($api) {
                        $api->get('{notification}', ['as' => 'notifications.show', 'uses' => 'NotificationController@show']);
                        $api->put('{notification}', ['as' => 'notifications.update', 'uses' => 'NotificationController@update']);
                        $api->delete('{notification}', ['as' => 'notifications.delete', 'uses' => 'NotificationController@delete']);
                        $api->get('deliveries', ['as' => 'notifications.deliveries', 'uses' => 'NotificationController@deliveries']);
                    });
                });
                $api->group(['prefix' => 'devices'], function($api) {
                    $api->get('/', ['as' => 'devices.index', 'uses' => 'DeviceController@index']);
                    $api->post('/', ['as' => 'devices.create', 'uses' => 'DeviceController@create']);
                    $api->group(['prefix' => '{device}'], function($api) {
                        $api->get('{device}', ['as' => 'devices.show', 'uses' => 'DeviceController@show']);
                        $api->put('{device}', ['as' => 'devices.update', 'uses' => 'DeviceController@update']);
                        $api->delete('{device}', ['as' => 'devices.delete', 'uses' => 'DeviceController@delete']);
                        $api->get('deliveries', ['as' => 'devices.deliveries', 'uses' => 'DeviceController@deliveries']);
                    });
                });
            });
        });
    });


});