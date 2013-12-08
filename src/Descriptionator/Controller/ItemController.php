<?php

namespace Descriptionator\Controller;

use Descriptionator\MediaWiki\Wiki;
use Descriptionator\Wikidata\ItemApiLookup;
use Silex\Application;
use Silex\ControllerProviderInterface;

class ItemController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->get( '/{id}', array( $this, 'view' ) );
		$controller->get( '/', array( $this, 'index' ) );

		return $controller;
	}

	public function index( Application $app ) {
		return $app['twig']->render( 'item_index.twig', array() );
	}

	public function view( Application $app, $id ) {
		$itemLookup = new ItemApiLookup( $app['mwuser'] );
		$wiki = new Wiki( 'wikidatawiki', 'https://www.wikidata.org/w/api.php' );
		$item = $itemLookup->find( $id, $wiki );

		return $app['twig']->render( 'item.twig', array(
			'id' => $id,
			'label' => $item->getLabel( 'en' ),
			'description' => $item->getDescription( 'en' )
		) );
	}
}
