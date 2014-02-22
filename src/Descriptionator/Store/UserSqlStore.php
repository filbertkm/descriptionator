<?php

namespace Descriptionator\Store;

use Descriptionator\User\User;

class UserSqlStore implements UserStore {

	protected $app;

	public function __construct( $app ) {
		$this->app = $app;
	}

	public function getUser( $username ) {
		$sql = "SELECT * FROM users where username = ?";
		$result = $this->app['db']->fetchAssoc( $sql, array( $username ) );

		if ( $result ) {
			$user = new User(
				$result['username'],
				$result['password'],
				$result['salt'],
				array( 'ROLE_USER' ),
				$result['email']
			);

			$user->setApiPassword( $result['apipassword'] );
			$user->setOAuthToken( $result['oauthtoken'] );
			$user->setOAuthSecret( $result['oauthsecret'] );

			return $user;
		}

		return null;
	}

	public function getUserById( $id ) {

	}

	public function addUser( User $user ) {
		$sql = "INSERT INTO users (username, password, salt, email, apipassword, oauthtoken, oauthsecret) VALUES(?, ?, ?, ?, ?, ?, ?)";
		$params = array(
			$user->getUsername(),
			$user->getPassword(),
			$user->getSalt(),
			$user->getEmail(),
			$user->getApiPassword(),
			$user->getOAuthToken(),
			$user->getOAuthSecret()
		);

		$result = $this->app['db']->executeUpdate( $sql, $params );

		if ( $result !== 1 ) {
			throw new \Exception( 'Failed to create user' );
		}

		return true;
	}

}
