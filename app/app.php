<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

$app = new Silex\Application();

$app->register( new Silex\Provider\FormServiceProvider() );
$app->register( new Silex\Provider\SessionServiceProvider() );
$app->register( new Silex\Provider\ServiceControllerServiceProvider() );
$app->register( new Silex\Provider\TranslationServiceProvider(), array(
	'translator.messages' => array(),
) );

$app->register( new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/../templates'
) );

$app['mwuser'] = $app->share( function() use ( $mwuser ) {
	return $mwuser;
});

$app['watchlist'] = $app->share( function() {
	return new Descriptionator\MediaWiki\Watchlist;
});

$oauth = new WikiClient\OAuth\OAuth( $config, $app );
$oauthRequest = new WikiClient\OAuth\OAuthRequest( $config );

require_once __DIR__ . '/routes.php';

$app['debug'] = true;

$app->run();
