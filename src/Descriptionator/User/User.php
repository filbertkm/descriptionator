<?php

namespace Descriptionator\User;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface {

	protected $roles;

	protected $password;

	protected $salt;

	protected $username;

	protected $email;

	public function __construct( $username, $password, $salt, array $roles, $email ) {
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->salt = $salt;
		$this->roles = $roles;
	}

	public function getRoles() {
		return $this->roles;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getSalt() {
		return $this->salt;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getEmail() {
		return $this->email;
	}

	public function eraseCredentials() {

	}

	public function isEqualTo( UserInterface $user ) {
		// todo
	}

}
