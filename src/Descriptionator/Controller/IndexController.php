<?php

namespace Descriptionator\Controller;

use Descriptionator\MediaWiki\CategoryMemberApiLookup;
use Descriptionator\MediaWiki\Wiki;
use Descriptionator\Wikidata\ItemApiLookup;
use Descriptionator\Wikidata\ItemDeserializer;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use WikiClient\MediaWiki\User;

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

		return $app['twig']->render( 'index_form.twig', array( 'form' => $form->createView() ) );
	}

}
