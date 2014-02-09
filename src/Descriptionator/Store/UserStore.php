<?php

namespace Descriptionator\Store;

use Descriptionator\User\User;

interface UserStore {

	public function getUser( $username );

	public function getUserById( $id );

	public function addUser( User $user );

}
