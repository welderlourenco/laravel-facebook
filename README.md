# Laravel Facebook #

The cleaner and more organized bridge from you to facebook platform.

# Introduction #

*Laravel Facebook* is the most simple solution for developers that need fast, autonomous and secure integration with the **Facebook platform**. Using the facebook php sdk v4 from April 28, 2014, Laravel Facebook establishes the cleaner and more organized intermediation between you and the platform.

'*It just builds a bridge. You will have to pass for it.*' -- me

# Needed knowledge #

Facebook Platform utilizes their [Graph API](https://developers.facebook.com/docs/graph-api/quickstart/v2.0) as a primary way to get data in and out of Facebook's social graph.
In order to get to the bottom at this package, you'll need to have the basick knowledge in access tokens and facebook permissions, but don't worry, it's really not heard at all.

# Instalation #

## Required steps ##

In the ***require*** key of ***composer.json*** file add the following


```
"welderlourenco/laravel-facebook"	: "dev-master"
```

Run the Composer update comand


```
composer update
```

Once this operation completes, the final step is to add the ***provider*** and the ***alias*** in the ***app/config/app.php*** config file.


```
return array(
  // ...
  'providers' => array(
    // At the end of this array, push Laravel Facebook provider:
    'WelderLourenco\Facebook\Providers\FacebookServiceProvider'
  ),
  'aliases' => array(
    // At the end of this array, push Laravel Facebook facade:
    'Facebook'				=> 'WelderLourenco\Facebook\Facades\Facebook'
  )
)
```

## Configuration ##

Run the Config Publish command


```

php artisan config:publish welderlourenco/laravel-facebook
```

Go to the generated config file in your application


```
return array(	
    /*
	|--------------------------------------------------------------------------
	| facebook-php-sdk-v4
	|--------------------------------------------------------------------------
	|
	| Essential data provided for the facebook-php-sdk-v4. The app-id and 
	| app-secret won't even be touched by welderlourenco/laravel-facebook's 
	| package.
	|
	*/
	'appId'			=> '', // Id of your facebook app.
	'appSecret'		=> '', // Secret of your facebook app.
	'redirectUrl'	=> '' // Where to process the facebook answer.
);
```

# Available Methods / Usage #

In any page, use the connect() method without passing any arguments to get a instance of the [FacebookRedirectLoginHelper](https://developers.facebook.com/docs/php/FacebookRedirectLoginHelper/4.0.0) object, allowing you to call its native methods.

**Example: Get the login url.**

```
$FacebookRedirectLoginHelper = Facebook::connect();
echo $loginUrl = $FacebookRedirectLoginHelper->getLoginUrl();
```

Laravel Facebook allows you to chain these methods, looking way more pretty.


```
echo Facebook::connect()->getLoginUrl();
```

You can pass an array to the getLoginUrl method to define the scope.

```
echo Facebook::connect()->getLoginUrl(array('email'));
// public_profile (default scope) and email
```

In any page, use the connect() method again passing a accessToken as argument to get a instance of [FacebookSession](https://developers.facebook.com/docs/php/FacebookSession/4.0.0) object, allowing you to call its native methods.

**Example: Get session info.**

```
$accessToken = 'example-of-access-token';
dd(Facebook::connect($accessToken)->getSessionInfo());
```

In the redirect page, call the process() method to process the facebook answer and get a instance of the [FacebookSession](https://developers.facebook.com/docs/php/FacebookSession/4.0.0) object, allowing you to call its native methods.

**Example: Process the facebook redirect, transform it to long-lived access token and get the access token.**

```
$accessToken = Facebook::process()->getLongLivedSession()->getToken();
// Now that you have the access token, do whatever you want with it, store in database or in a cookie, it is you call.
```

In any page use the api() method passing 3 arguments to get the [GraphObject](https://developers.facebook.com/docs/php/GraphObject/4.0.0) object, allowing you to call its native methods.

**Example: Process the facebook redirect, transform it to long-lived access token and get the access token and the user personal info.**


```
// FacebookSession, you'll need this to make any api calls.
$session = Facebook::process()->getLongLivedSession();
// Access Token
$accessToken = $session->getToken();
// User email
$email = Facebook::api($session, 'GET', '/me');
```

In any page, before calling the connect() or process() method use the change() passing 2 required method and 2 optional to change the app before connecting.

**Example: Get the login url from another app.**

```
#!php
<?php
echo Facebook::change($newAppId, $newAppSecret, $optionalNewAppRedirectUrl)->connect()->getLoginUrl();
```

# Thanks #

Thank God for the knowledge to write all this.