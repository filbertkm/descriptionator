<?php

namespace Descriptionator\User;

use Descriptionator\Store\UserStore;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRegistrationHandler {

	private $userStore;

	private $encoderFactory;

	private $securityContext;

	public function __construct( UserStore $userStore, $encoderFactory, $securityContext ) {
		$this->userStore = $userStore;
		$this->encoderFactory = $encoderFactory;
		$this->securityContext = $securityContext;
	}

	public function handle( array $data ) {
		$salt = '1234567890abcdefghijkl'; // needs to be length 22
		$user = new User( $data['username'], '', $salt, array(), $data['email'] );

		$encoder = $this->encoderFactory->getEncoder( $user );
		$user->setPassword( $encoder->encodePassword( $data['password'], $salt ) );
		$user->setApiPassword( $data['password'] );

		$this->userStore->addUser( $user );
		$this->authenticateUser( $user );

		return $user;
	}

	private function authenticateUser( UserInterface $user ) {
		$token = new UsernamePasswordToken( $user, null, 'admin', $user->getRoles() );

		$this->securityContext->setToken( $token );
	}

}
