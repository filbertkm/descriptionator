<?php

namespace Descriptionator\Controller;

use Descriptionator\Form\LoginType;
use Silex\Application;
use Silex\ControllerProviderInterface;

class UserController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->match( '/authenticate/', array( $this, 'authenticate' ) );
		$controller->match( '/logout/', array( $this, 'logout' ) );
		$controller->match( '/', array( $this, 'index' ) );

		return $controller;
	}

	public function index( Application $app ) {
		return $app['twig']->render( 'user_index.twig' );
	}

	public function authenticate( Application $app ) {
		return 'authenticate';
	}

	public function logout( Application $app ) {
		return 'logout';
	}

}
