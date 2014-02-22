<?php

namespace Descriptionator\Controller;

use Descriptionator\Store\UserSqlStore;
use Descriptionator\User\User;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;

class RegisterController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->match( '/', array( $this, 'registerForm' ) );

		return $controller;
	}

	public function registerForm( Application $app ) {
		$form = $this->buildForm( $app );
		$form->handleRequest( $app['request'] );

		if ( $form->isValid() ) {
			$user = $this->processForm( $app, $form );

			if ( $user ) {
				return $app->redirect( '/user/' . $user->getUsername() );
			}
		}

		return $app['twig']->render( 'register_form.twig', array( 'form' => $form->createView() ) );
	}

	private function buildForm( Application $app ) {
		$data = array(
			'username' => '',
			'password' => '',
			'email' => ''
		);

		return $app['form.factory']->createBuilder( 'form', $data )
			->add( 'username', 'text' )
			->add( 'password', 'password' )
			->add( 'email', 'email' )
			->getForm();
	}

	private function processForm( Application $app, Form $form ) {
		$data = $form->getData();
		$userStore = new UserSqlStore( $app );

		$salt = '1234567890abc';
		$user = new User( $data['username'], '', $salt, array(), $data['email'] );

		$encoder = $app['security.encoder_factory']->getEncoder( $user );
		$user->setPassword( $encoder->encodePassword( $data['password'], $salt ) );

		try {
			$userStore->addUser( $user );
		} catch ( \Exception $ex ) {
			$form->addError( new FormError( $ex->getMessage() ) );

			return false;
		}

		return $user;
	}

}
