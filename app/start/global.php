<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

\App::after(function($request, $response)
{
	$csp = "default-src 'self'; script-src 'unsafe-eval' 'unsafe-inline' 'self' https://*.googleapis.com https://cdnjs.cloudflare.com"
		. " http://code.jquery.com https://code.jquery.com https://*.gstatic.com; object-src 'self'; style-src 'self' 'unsafe-inline'"
		. " https://*.googleapis.com https://*.gstatic.com; img-src *; media-src *; frame-src 'self';"
		. " font-src 'self' https://*.googleapis.com https://*.gstatic.com; connect-src *";

	if (\Request::server("HTTP_HOST") === 's5.studentrnd.org') {
		$response->headers->set('Strict-Transport-Security', '2,592,000');
	}

	$response->headers->set('X-Frame-Options', 'deny');
	$response->headers->set('Frame-Options', 'deny');
	$response->headers->set('X-XSS-Protection', '1; mode=block');
	$response->headers->set('X-Content-Type-Options', 'nosniff');
	$response->headers->set('Content-Security-Policy', $csp);
	$response->headers->set('X-Content-Security-Policy', $csp);
	$response->headers->set('X-WebKit-CSP', $csp);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';
require app_path().'/events.php';