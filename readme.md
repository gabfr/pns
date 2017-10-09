# Push Notification Service

Simple and functional API service to store device tokens of your app and dispatch notifications to them.

## Installing

* You can clone this repo, or [download it here](https://github.com/gabfr/pns/archive/master.zip) (development version - master branch).
* After getting the files, you should configure your `.env` file following the example in `.env.example`
* Finally you run `composer install`
* And then create the database with the tables running `php artisan migrate`
* With the database created, you should run `php artisan db:seed`, so you will get the default records.

## Managing the applications

There is no UI to manage the applications yet, so you have to do it yourself through the database or you can use something like a Postman (which I think it's easier - but this is totally up to you).

### Managing the application - via Postman

#### Authenticating

First of all, you should get a access token so you can POST/GET/DELETE/PUT critical resources such as applications registries.
You do it in the route `/access-token` as the following request:
```
POST /access-token HTTP/1.1
Content-Type: application/json
Cache-Control: no-cache

{
	"email": "eu@gabrielf.com",
	"password": "secret"
}
```

When you got the token successfully, you have to add in the headers of all your further requests:
```
Content-Type: application/json
Authorization: Bearer PASTE_HERE_THE_TOKEN_YOU_GOT
```

#### Configuring applications

With the token, it is simple to create an application on the system, you just send a `POST` to `/applications`:
```
Content-Type: application/json
Authorization: Bearer PASTE_HERE_THE_TOKEN_YOU_GOT

{
	"name": "My Fancy App Name",
	"slug": "my-fancy-app-name"
}
```

#### Configuring applications - notification service certificates (Android and Apple)

With your apps created you should now configure the credentials/certificates of the notifications APIs:

##### Android - Google Cloud Messaging/Firebase

`POST /applications/*your-app-slug*/gcm`

Field | Description | Type | Mandatory | Default value
----- | ----------- | ---- | --------- | -------------
`gcm_api_key` | The private key to send notifications on the Google Cloud Messaging. You can get it on the [Firebase Control Panel](https://console.firebase.google.com) (Select Project > Go to Settings > Click on the Cloud Messaging Tab) | `string` | [X] | `null`
`gcm_mode` | The mode you want to operate on the GCM network. `sandbox` or `production` | `string` | [X] | `sandbox`

##### Apple Push Notification Service

`POST /applications/*your-app-slug*/apns`

*Important:* When sending the files, you should select the `form-data` option on the body tab of the postman. Which will result in a content-type header like this one:
`Content-Type: multipart/form-data; boundary=...`

Field | Description | Type | Mandatory | Default value
----- | ----------- | ---- | --------- | -------------
`apns_certificate_sandbox` | The private key to send notifications on the Apple Push Notification Service, the sandbox one. The format of the certificate file is specified in the next section. | `file` | [ ] | `null`
`apns_certificate_production` | The production key to send notifications on the Apple Push Notification Service, the production one. | `file` | [X] | `null`
`apns_mode` | The mode you want to operate on the APNS network. `sandbox` or `production` | `string` | [X] | `sandbox`

## License

This software is made on top of the Laravel framework that is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
