<?php

namespace Descriptionator\Controller;

use Descriptionator\Store\UserSqlStore;
use Descriptionator\User\User;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;

class RegisterController implements ControllerProviderInterface {

	private $regHandler;

	public function __construct( $regHandler ) {
		$this->regHandler = $regHandler;
	}

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
		try {
			$data = $form->getData();
			$user = $this->regHandler->handle( $data );
		} catch ( \Exception $ex ) {
			$form->addError( new FormError( $ex->getMessage() ) );

			return false;
		}

		return $user;
	}

}
