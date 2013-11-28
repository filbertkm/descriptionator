<?php

$app->get( '/watchlist/', function() use( $app, $oauthRequest ) {
	return $app['watchlist']->get( $oauthRequest );
});

$app->get( '/wiki/', function() use ( $app, $oauth ) {
	$token = $app['request']->query->get( 'oauth_token' );
	$verifier = $app['request']->query->get( 'oauth_verifier' );

	if ( $token && $verifier ) {
		return $oauth->verify( $token, $verifier );
	}

	return $oauth->authorize();
});

$app->mount( '/category', new Descriptionator\Controller\CategoryController() );
$app->mount( '/item', new Descriptionator\Controller\ItemController() );
$app->mount( '/', new Descriptionator\Controller\IndexController() );
