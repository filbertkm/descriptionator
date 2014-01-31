<?php

namespace Descriptionator\Store;

use Descriptionator\Model\User;

interface UserStore {

	public function getUser( $id );

	public function addUser( User $user );

}
