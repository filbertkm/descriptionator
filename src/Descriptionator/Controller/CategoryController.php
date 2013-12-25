<?php

namespace Descriptionator\Controller;

use Descriptionator\MediaWiki\CategoryMemberApiLookup;
use Descriptionator\MediaWiki\Wiki;
use Descriptionator\Wikidata\ItemApiLookup;
use Descriptionator\Wikidata\ItemDeserializer;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use WikiClient\MediaWiki\User;

class CategoryController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->get( '/{catname}', array( $this, 'members' ) );

		return $controller;
	}

	public function members( Application $app, $catname ) {
		$categoryMemberLookup = new CategoryMemberApiLookup( $app['mwuser'] );
		$wiki = new Wiki( 'enwiki', 'http://en.wikipedia.org/w/api.php' );
		$pages = $categoryMemberLookup->find( $catname, $wiki );

		$repo = new Wiki( 'wikidatawiki', 'https://www.wikidata.org/w/api.php' );
		$itemLookup = new ItemApiLookup( $app['mwuser'], $repo );
		$items = $itemLookup->getItemsBySiteLinks( $pages, 'enwiki' );

		$itemList = array();
		$missing = array();

		$deserializer = new ItemDeserializer();

		foreach( $items as $item ) {
			if ( array_key_exists( 'missing', $item ) ) {
				$missing[] = $item;
			} else {
				$itemList[] = $deserializer->deserialize( $item );
			}
		}

		$formattedItems = array();

		foreach( $itemList as $item ) {
			$formattedItems[] = array(
				'id' => $item->getId()->getSerialization(),
				'page' => $item->getSiteLink( 'enwiki' )->getPageName(),
				'label' => $item->getLabel( 'en' ),
				'description' => $item->getDescription( 'en' )
			);
		}

		return $app['twig']->render(
			'category_list.twig',
			array(
				'pages' => array(),
				'items' => $formattedItems
			)
		);
	}

}
