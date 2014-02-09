<?php

namespace Descriptionator\Controller;

use Descriptionator\Store\UserSqlStore;
use Descriptionator\User\User;
use Silex\Application;
use Silex\ControllerProviderInterface;

class RegisterController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->match( '/', array( $this, 'form' ) );

		return $controller;
	}

	public function form( Application $app ) {
		$data = array(
			'username' => '',
			'password' => ''
		);

		$form = $app['form.factory']->createBuilder( 'form', $data )
			->add( 'username', 'text' )
			->add( 'password', 'password' )
			->add( 'email', 'email' )
			->getForm();

		$form->handleRequest( $app['request'] );

		if ( $form->isValid() ) {
			$data = $form->getData();
			$userStore = new UserSqlStore( $app );
			$user = new User( $data['username'], $data['password'], '', array(), $data['email'] );
			$result = $userStore->addUser( $user );

			var_export( $result );

//			return $app->redirect( '/category/' . $data['category'] );
		}

		return $app['twig']->render( 'register_form.twig', array( 'form' => $form->createView() ) );
	}

}
