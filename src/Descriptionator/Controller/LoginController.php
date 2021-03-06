<?php

namespace Descriptionator\Controller;

use Descriptionator\Form\LoginType;
use Silex\Application;
use Silex\ControllerProviderInterface;

class LoginController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->match( '/', array( $this, 'form' ) );

		return $controller;
	}

	public function form( Application $app ) {
		$data = array(
			'_username' => '',
			'_password' => ''
		);

		// type, data, options
		$form = $app['form.factory']->create( new LoginType(), $data, array() );

		return $app['twig']->render(
			'login_form.twig',
			array(
				'form' => $form->createView(),
				'error' => $app['security.last_error']( $app['request'] )
			)
		);
	}

}
