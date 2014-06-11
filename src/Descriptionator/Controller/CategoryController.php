<?php

namespace Descriptionator\Controller;

use Descriptionator\MediaWiki\CategoryMemberApiLookup;
use Descriptionator\Wikidata\ItemDeserializer;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Wikibot\CategoryMemberFinder;
use Wikibot\Wikibase\ItemLookup;
use Wikibot\Wikibase\ItemsByCategoryFinder;
use Wikibot\Wikibase\ItemsWithoutDescriptionFinder;

class CategoryController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->get( '/{catname}/', array( $this, 'members' ) );
		$controller->get( '/{catname}/nodesc/', array( $this, 'membersWithNoDescription' ) );

		return $controller;
	}

	public function members( Application $app, $catname ) {
		$items = $this->getItemsForCategory( $app, $catname );

		return $this->buildRendering( $app, $items );
	}

	public function membersWithNoDescription( Application $app, $catname ) {
		$items = $this->getItemsWithNoDescription( $app, $catname );

		return $this->buildRendering( $app, $items );
	}

	private function buildRendering( $app, array $items ) {
		$itemData = $this->buildItemData( $items );

		return $app['twig']->render(
			'category_list.twig',
			array(
				'pages' => array(),
				'items' => $itemData
			)
		);
	}

	private function getItemsWithNoDescription( $app, $catname ) {
		$client = $app['apiclient'];

		$itemLookup = new ItemLookup( $client( 'wikidatawiki' ), $app['entity-deserializer'] );
		$itemFinder = new ItemsWithoutDescriptionFinder( $client( 'enwiki' ), $itemLookup );

		$items = $itemFinder->getItems( $catname, 'enwiki', 'en' );

		return $items;
	}

	private function getItemsForCategory( $app, $catname ) {
		$client = $app['apiclient'];

		$catMemberFinder = new CategoryMemberFinder( $client( 'enwiki' ) );
		$itemLookup = new ItemLookup( $client( 'wikidatawiki' ), $app['entity-deserializer'] );

		$itemsByCatFinder = new ItemsByCategoryFinder( $catMemberFinder, $itemLookup );
		$items = $itemsByCatFinder->getItemsForCategory( $catname );

		return $items;
	}

	private function buildItemData( array $items ) {
		$itemData = array();

		foreach( $items as $item ) {
			$itemData[] = array(
				'id' => $item->getId()->getSerialization(),
				'page' => $item->getSiteLink( 'enwiki' )->getPageName(),
				'label' => $item->getLabel( 'en' ),
				'description' => $item->getDescription( 'en' )
			);
		}

		return $itemData;
	}

}
