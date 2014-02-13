<?php

$app->get( '/watchlist/', function() use( $app ) {
	$oauthRequest = $app['oauth.request'];
	return $app['watchlist']->get( $app, $oauthRequest );
});

$app->get( '/wiki/', function() use ( $app ) {
	$oauth = $app['oauth'];

	$token = $app['request']->query->get( 'oauth_token' );
	$verifier = $app['request']->query->get( 'oauth_verifier' );

	if ( $token && $verifier ) {
		return $oauth->verify( $token, $verifier );
	}

	return $oauth->authorize();
});

$app->mount( '/category', new Descriptionator\Controller\CategoryController() );
$app->mount( '/login', new Descriptionator\Controller\LoginController() );
$app->mount( '/register', new Descriptionator\Controller\RegisterController() );
$app->mount( '/admin', new Descriptionator\Controller\AdminController() );
$app->mount( '/', new Descriptionator\Controller\IndexController() );
