<?php

namespace Descriptionator\Store;

use Doctrine\DBAL\Schema\Table;

class SchemaInstaller {

	public function install( $schema ) {
		if ( !$schema->tablesExist( 'users' ) ) {
			$this->addUserTable( $schema );
		}
	}

	private function addUserTable( $schema ) {
		$users = new Table( 'users' );

		$users->addColumn( 'id', 'integer', array( 'unsigned' => true, 'autoincrement' => true ) );
		$users->setPrimaryKey( array( 'id' ) );

		$users->addColumn( 'username', 'string', array( 'length' => 32 ) );
		$users->addUniqueIndex( array( 'username' ) );

		$users->addColumn( 'password', 'string', array( 'length' => 255 ) );

		$users->addColumn( 'salt', 'string', array( 'length' => 255 ) );

		$users->addColumn( 'email', 'string', array( 'length' => 255 ) );

		$users->addColumn( 'apipassword', 'string', array( 'length' => 255 ) );

		$users->addColumn( 'oauthsecret', 'string', array( 'length' => 255 ) );
		$users->addColumn( 'oauthtoken', 'string', array( 'length' => 255 ) );

		$schema->createTable( $users );

		echo "added users table\n";
	}

}
