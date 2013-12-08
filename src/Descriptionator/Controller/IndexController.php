<?php

namespace Descriptionator\Controller;

use Descriptionator\MediaWiki\CategoryMemberApiLookup;
use Descriptionator\MediaWiki\Wiki;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
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

			return $this->showCategory( $data['category'], $app );
		}

		return $app['twig']->render( 'index_form.twig', array( 'form' => $form->createView() ) );
	}

	public function showCategory( $catname, $app ) {
		$categoryMemberLookup = new CategoryMemberApiLookup( $app['mwuser'] );
		$wiki = new Wiki( 'enwiki', 'http://en.wikipedia.org/w/api.php' );
		$pages = $categoryMemberLookup->find( $catname, $wiki );

		return $app['twig']->render( 'category_list.twig', array( 'pages' => $pages ) );
	}

}
