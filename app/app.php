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

$app->register( new Silex\Provider\DoctrineServiceProvider(), array(
	'dbs.options' => array(
		'db' => $dbParams
	)
) );

$app->register( new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/../templates'
) );

$app->get( '/admin/check', function() use ( $app ) {
	return 'check';
} );

$app['userprovider'] = $app->share( function () use ( $app ) {
	return new \Descriptionator\User\UserProvider( $app );
});

$app->register( new Silex\Provider\SecurityServiceProvider(), array(
	'security.firewalls' => array(
		'admin' => array(
			'pattern' => '^/(admin)',
			'form' => array(
				'login_path' => '/login',
				'check_path' => '/admin/check'
			),
			'users' => $app['userprovider']
		),
	)
) );

$app['security.encoder.digest'] = $app->share( function( $app ) {
	return new Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder( 15 );
});


$app['mwuser'] = $app->share( function() use ( $mwuser ) {
	return $mwuser;
});

$app['watchlist'] = $app->share( function() {
	return new Descriptionator\MediaWiki\Watchlist;
});

$app['oauth'] = $app->share( function( $app ) use ( $config ) {
	return new WikiClient\OAuth\OAuth( $config, $app );
});

$app['oauth.request'] = $app->share( function() use ( $config ) {
	return new WikiClient\OAuth\OAuthRequest( $config );
});

require_once __DIR__ . '/wiki.php';
require_once __DIR__ . '/routes.php';

$app['debug'] = true;
