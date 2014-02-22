<?php

namespace Descriptionator\User;

use Descriptionator\Store\UserSqlStore;
use Silex\Application;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface {

	private $app;

	public function __construct( Application $app ) {
		$this->app = $app;
	}

	public function loadUserByUsername( $username ) {
		$userStore = new UserSqlStore( $this->app );
		$user = $userStore->getUser( $username );

		if ( !$user ) {
			throw new UsernameNotFoundException( sprintf( 'Username %s not found', $username ) );
		}

		return $user;
	}

	public function refreshUser( UserInterface $user ) {
		return $this->loadUserByUsername( $user->getUsername() );
	}

	public function supportsClass( $class ) {
		//returns bool
		return true;
	}

}
