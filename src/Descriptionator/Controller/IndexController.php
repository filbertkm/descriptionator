<?php

namespace Descriptionator\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class IndexController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->match( '/', array( $this, 'form' ) );

		return $controller;
	}

	public function form( Application $app ) {
		$data = array(
			'category' => ''
		);

		$form = $app['form.factory']->createBuilder( 'form', $data )
			->add( 'category' )
			->getForm();

		$form->handleRequest( $app['request'] );

		if ( $form->isValid() ) {
			$data = $form->getData();
			return $app->redirect( '/category/' . $data['category'] );
		}

		return $app['twig']->render(
			'index_form.twig',
			array(
				'form' => $form->createView()
			)
		);
	}

}
