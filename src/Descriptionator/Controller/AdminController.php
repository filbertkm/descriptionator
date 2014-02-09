<?php

namespace Descriptionator\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class AdminController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->match( '/', array( $this, 'index' ) );

		return $controller;
	}

	public function index( Application $app ) {
		return $app['twig']->render( 'admin_index.twig' );
	}

}
