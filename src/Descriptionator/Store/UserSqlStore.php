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

		return $result;
	}

	public function getUserById( $id ) {

	}

	public function addUser( User $user ) {
		$sql = "INSERT INTO users (username, password, email) VALUES(?, ?, ?)";
		$params = array(
			$user->getUsername(),
			$user->getPassword(),
			$user->getEmail()
		);

		$result = $this->app['db']->executeUpdate( $sql, $params );

		if ( $result !== 1 ) {
			throw new \Exception( 'Failed to create user' );
		}

		return true;
	}

}
