<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

$dataValueMap = array(
	'globecoordinate' => 'DataValues\GlobeCoordinateValue',
	'monolingualtext' => 'DataValues\MonolingualTextValue',
	'multilingualtext' => 'DataValues\MultilingualTextValue',
	'quantity' => 'DataValues\QuantityValue',
	'time' => 'DataValues\TimeValue',
	'wikibase-entityid' => 'Wikibase\DataModel\Entity\EntityIdValue',
	'string' => 'DataValues\StringValue'
);

$app = new Silex\Application();

$app->register( new Silex\Provider\FormServiceProvider() );
$app->register( new Silex\Provider\SessionServiceProvider() );
$app->register( new Silex\Provider\ServiceControllerServiceProvider() );
$app->register( new Silex\Provider\TranslationServiceProvider(), array(
	'translator.messages' => array(),
) );

$app['wikiprovider'] = function( $app ) use( $wikis ) {
	return new WikiClient\MediaWiki\WikiFactory( $wikis );
};

$app['apiclient'] = function( $app ) use ( $wikis ) {
	return function( $siteId ) use( $app, $wikis ) {
		$wiki = $app['wikiprovider']->newWiki( $siteId );

		$userInfo = $wikis[$siteId]['user'];
		$user = new WikiClient\MediaWiki\User(
			$userInfo['username'],
			$userInfo['password'],
			$wikis[$siteId]
		);

		return new WikiClient\MediaWiki\ApiClient( $wiki, $user );
	};
};

$app->register( new Silex\Provider\DoctrineServiceProvider(), array(
	'dbs.options' => $dbOptions
) );

$app->register( new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/../templates'
) );

$app->get( '/user/authenticate', function() use ( $app ) {
	return 'check';
} );

$app->get( '/user/logout', function() use ( $app ) {
	return 'authenticate';
} );

$app['userprovider'] = $app->share( function () use ( $app ) {
	return new \Descriptionator\User\UserProvider( $app );
});

$app->register( new Silex\Provider\SecurityServiceProvider(), array(
	'security.firewalls' => array(
		'admin' => array(
			'pattern' => '^/',
			'form' => array(
				'login_path' => '/login',
				'check_path' => '/user/authenticate'
			),
			'logout' => array(
				'logout_path' => '/user/logout'
			),
			'users' => $app['userprovider'],
			'anonymous' => true
		)
	),
	'security.access_rules' => array(
		array( '^/user', 'ROLE_USER' )
//		array( '^/item/Q([\d]+)/edit', 'ROLE_USER' )
	)
) );

$app['security.encoder.digest'] = $app->share( function( $app ) {
	return new Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder( 15 );
});

$app['watchlist'] = $app->share( function() {
	return new Descriptionator\MediaWiki\Watchlist;
});

$app['oauth'] = $app->share( function( $app ) use ( $oauthConfig ) {
	return new WikiClient\OAuth\OAuth( $oauthConfig, $app );
});

$app['oauth.request'] = $app->share( function() use ( $oauthConfig ) {
	return new WikiClient\OAuth\OAuthRequest( $oauthConfig );
});

require_once __DIR__ . '/routes.php';

$app['oauth.user'] = $app->share( function( $app ) {
	$token = $app['session']->get( 'oauth_token' );
	$userStore = new Descriptionator\Store\UserSqlStore( $app );

	$user = $userStore->getUserByToken( $token );

	return $user;
});

$app['wikidata.itemstore'] = $app->share( function( $app ) {
	$token = $app['session']->get( 'oauth_token' );
	$userStore = new Descriptionator\Store\UserSqlStore( $app );
	$user = $userStore->getUserByToken( $token );

	$repo = WikiClient\MediaWiki\WikiFactory::newWiki( $app['wikis'], 'testrepo', $user );
	return new Descriptionator\Store\ItemStore( $app, $repo );
});

$app['deserializer-factory'] = function() use ( $dataValueMap ) {
	return new Wikibase\DataModel\DeserializerFactory(
		new DataValues\Deserializers\DataValueDeserializer( $dataValueMap ),
		new Wikibase\DataModel\Entity\BasicEntityIdParser()
	);
};

$app['entity-deserializer'] = function( $app ) {
	return $app['deserializer-factory']->newEntityDeserializer();
};

$app['debug'] = true;
