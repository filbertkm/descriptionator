<?php

namespace Descriptionator\Controller;

use Descriptionator\Store\UserSqlStore;
use Descriptionator\User\User;
use Silex\Application;
use Silex\ControllerProviderInterface;
use WikiClient\HttpClient;
use WikiClient\MediaWiki\ApiClient;
use WikiClient\MediaWiki\WikiFactory;

class OAuthController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->match( '/', array( $this, 'authorize' ) );

		return $controller;
	}

	public function authorize( Application $app ) {
		$oauth = $app['oauth'];

		$token = $app['request']->query->get( 'oauth_token' );
		$verifier = $app['request']->query->get( 'oauth_verifier' );

		$wiki = WikiFactory::newWiki( $app['wikis'], 'testrepo' );

		if ( $token && $verifier ) {
			$url = $oauth->getAuthUrl( $token, $verifier );
			$client = new HttpClient( '/tmp', '' );
			$json = $client->get( $url );

			$result = json_decode( $json, true );

			var_export( $result );

			$user = new User( '', '', '', array(), '' );
			$user->setOAuthSecret( $result['secret'] );
			$user->setOAuthToken( $result['key'] );

			var_export( $user );

			$apiParams = array(
				'action' => 'query',
				'format' => 'json',
				'meta' => 'userinfo',
				'uiprop' => 'email'
			);

			$app['session']->set( 'oauth_token', $result['key'] );
			$app['session']->set( 'oauth_secret', $result['secret'] );

			$userJson = $app['oauth.request']->request( $wiki, $apiParams, 'post', $result['key'], $result['secret'] );
			$userData = json_decode( $userJson, true );

			$username = $userData['query']['userinfo']['name'];
			$user->setUsername( $username );

			$userStore = new UserSqlStore( $app );
			$userStore->addUser( $user );

			return 'connected';
		}

		return $oauth->authorize();
	}

}
