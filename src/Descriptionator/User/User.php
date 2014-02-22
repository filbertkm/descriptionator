<?php

namespace Descriptionator\User;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface {

	protected $roles;

	protected $password;

	protected $salt;

	protected $username;

	protected $email;

	protected $apiPassword;

	protected $oauthSecret;

	protected $oauthToken;

	public function __construct( $username, $password, $salt, array $roles, $email ) {
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->salt = '1234567890abcdefghijkl'; // needs to be length 22
		$this->roles = $roles;
	}

	public function getRoles() {
		return $this->roles;
	}

	public function setPassword( $password ) {
		$this->password = $password;
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

	public function setApiPassword( $password ) {
		$this->apiPassword = $password;
	}

	public function getApiPassword() {
		return isset( $this->apiPassword ) ? $this->apiPassword : '';
	}

	public function setOAuthSecret( $secret ) {
		$this->oauthSecret = $secret;
	}

	public function getOAuthSecret() {
		return isset( $this->oauthSecret ) ? $this->oauthSecret : '';
	}

	public function setOAuthToken( $token ) {
		$this->oauthToken = $token;
	}

	public function getOAuthToken() {
		return isset( $this->oauthToken ) ? $this->oauthToken : '';
	}

	public function eraseCredentials() {

	}

	public function isEqualTo( UserInterface $user ) {
		// todo
	}

}
