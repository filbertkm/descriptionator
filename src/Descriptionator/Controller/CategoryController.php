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
use WikiClient\MediaWiki\WikiFactory;

class CategoryController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->get( '/{catname}', array( $this, 'members' ) );

		return $controller;
	}

	public function members( Application $app, $catname ) {
		$categoryMemberLookup = new CategoryMemberApiLookup();
		$wiki = WikiFactory::newWiki( $app['wikis'], 'enwiki' );
		$pages = $categoryMemberLookup->find( $catname, $wiki );

		$repo = WikiFactory::newWiki( $app['wikis'], 'wikidatawiki' );
		$itemLookup = new ItemApiLookup( $repo );
		$items = $itemLookup->getItemsBySiteLinks( $pages, 'enwiki' );

		$itemList = $this->buildItemList( $items );
		$itemData = $this->buildItemData( $itemList );

		return $app['twig']->render(
			'category_list.twig',
			array(
				'pages' => array(),
				'items' => $itemData
			)
		);
	}

	private function buildItemList( $items ) {
		$itemList = array();

		$deserializer = new ItemDeserializer();

		foreach( $items as $item ) {
			if ( !array_key_exists( 'missing', $item ) ) {
				$itemList[] = $deserializer->deserialize( $item );
			}
		}

		return $itemList;
	}

	private function buildMissingList( $items ) {
		$missing = array();

		foreach( $items as $item ) {
			if ( array_key_exists( 'missing', $item ) ) {
				$missing[] = $item;
			}
		}

		return $missing;
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
