# Push Notification Service

Simple and functional API service to store device tokens of your app and dispatch notifications to them.

If you want to get it running, follow the instructions here. It will take half an hour, at least it is free.

## Installing

* You can clone this repo, or [download it here](https://github.com/gabfr/pns/archive/master.zip) (development version - master branch).
* After getting the files, you should configure your `.env` file following the example in `.env.example`
* Finally you run `composer install`
* And then create the database with the tables running `php artisan migrate`
* With the database created, you should run `php artisan db:seed`, so you will get the default records.

# Configuring the applications

There is no UI to manage the applications yet, so you have to do it yourself through the database or you can use something like a Postman (which I think it's easier - but this is totally up to you).

# Configuring the applications - using Postman

## Authenticating

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

## Configuring applications

With the token, it is simple to create an application on the system, you just send a `POST` to `/applications`:
```
Content-Type: application/json
Authorization: Bearer PASTE_HERE_THE_TOKEN_YOU_GOT

{
	"name": "My Fancy App Name",
	"slug": "my-fancy-app-name"
}
```

### Configuring applications - notification service certificates (Android and Apple)

With your apps created you should now configure the credentials/certificates of the notifications APIs:

### Android - Google Cloud Messaging/Firebase

`POST /applications/*your-app-slug*/gcm`

Field | Description | Type | Mandatory | Default value
----- | ----------- | ---- | --------- | -------------
`gcm_api_key` | The private key to send notifications on the Google Cloud Messaging. You can get it on the [Firebase Control Panel](https://console.firebase.google.com) (Select Project > Go to Settings > Click on the Cloud Messaging Tab) | `string` | [X] | `null`
`gcm_mode` | The mode you want to operate on the GCM network. `sandbox` or `production` | `string` | [X] | `sandbox`

### Apple Push Notification Service

`POST /applications/*your-app-slug*/apns`

**Important:** When sending the files, you should select the **`form-data`** option on the body tab of the postman. Which will result in a content-type header like this one:
`Content-Type: multipart/form-data; boundary=...`

Field | Description | Type | Mandatory | Default value
----- | ----------- | ---- | --------- | -------------
`apns_certificate_sandbox` | The private key to send notifications on the Apple Push Notification Service, the sandbox one. The format of the certificate file is **`*.pem`**, check out how to generate it in the next section. | `file` | [ ] | `null`
`apns_certificate_production` | The production key to send notifications on the Apple Push Notification Service, the production one. | `file` | [X] | `null`
`apns_mode` | The mode you want to operate on the APNS network. `sandbox` or `production` | `string` | [X] | `sandbox`

### Apple Push Notification Service - Preparing the certificate files

This guide will help you to convert the certificates you already generated on the Apple Developer Center to the format that the API will work with.

First of all, make sure you have the `*.cer` file, before you start, follow these steps below:
* You have to go to the **Keychain Access** tool, selected the category **My Certificates**
* Drag and drop the `*.cer` file on the certificate list
* Localize your certificate that should be called something like `Apple Development IOS Push Services: com.mycoolapp.code` or `Apple Push Services: com.mycoolapp.code`
* Toggle the marker right before the name, and you will see a private key registry, click with the right button of your magic mouse or trackpad (whatever), and **Export** it.
* Save it on the same folder of your `*.cer` file. Make sure it is on the format `*.p12` (Personal Information Exchange)

I recommend that you use the same name for your certificates, i.e., if you generate the `*.p12` file of your `push_certificate_sandbox.cer`, you should name your `*.p12` file as: `push_certificate_sandbox.p12`. That way, we will not get lost in the middle of the many files we will generate.

Now you just have to run the bash script below to generate the correct `*.pem` file to the API.

**Important:** Remember to check the files in bold below and change it to the correct name that you want.

```shell
#!/bin/sh

cd **folder/of/your/certificates**
openssl x509 -in **aps_production.cer** -inform der -out aps_production.pem

openssl pkcs12 -nocerts -out Certificates_production.pem -in **Certificates_production.p12**
openssl rsa -in Certificates_production.pem -out Certificates_production_NOPWD.pem

cat aps_production.pem Certificates_production_NOPWD.pem > apns_production.pem
```

In this case, the file that you will use on the endpoint above is the **apns_production.pem**.

## Ready? Nice!

After you are ready, you can start sendind notifications with our [simple web app, that was made with love and needs attention](https://github.com/gabfr/pns-app).

## License

This software is made on top of the Laravel framework that is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
