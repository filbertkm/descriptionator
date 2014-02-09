<?php

namespace Descriptionator\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface {

	public function loadUserByUsername( string $username ) {
		return new User( $username, $password, $salt, $roles, $email );
	}

	public function refreshUser( UserInterface $user ) {

	}

	public function supportsClass(string $class ) {
		//returns bool
	}

}
