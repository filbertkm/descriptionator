<?php

namespace Descriptionator\Controller;

use Descriptionator\Form\ItemType;
use Descriptionator\MediaWiki\Wiki;
use Descriptionator\MediaWiki\WikitextPage;
use Descriptionator\MediaWiki\WikitextPageStore;
use Descriptionator\Wikidata\ItemApiLookup;
use Descriptionator\Wikidata\ItemDeserializer;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use WikiClient\MediaWiki\WikiFactory;

class ItemController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->get( '/{id}/edit/', array( $this, 'edit' ) );
		$controller->get( '/{id}/', array( $this, 'view' ) );

		return $controller;
	}

	public function edit( Application $app, $id ) {
		$itemData = $this->getItemData( $app, $id );

		$data = array(
			'_description' => ''
		);

		// type, data, options
		$form = $app['form.factory']->create( new ItemType(), $data, array() );
		$params = array_merge(
			array( 'form' => $form->createView() ),
			$itemData
		);

		return $app['twig']->render( 'item_form.twig', $params );
	}

	public function view( Application $app, $id ) {
		$itemData = $this->getItemData( $app, $id );

		return $app['twig']->render(
			'item.twig',
			$itemData
		);
	}

	private function getItemData( Application $app, $id ) {
		$repo = WikiFactory::newWiki( $app['wikis'], 'wikidatawiki' );
		$wiki = WikiFactory::newWiki( $app['wikis'], 'enwiki' );

		$itemLookup = new ItemApiLookup( $repo );
		$item = $itemLookup->getItem( $id );

		$siteLink = $item->getSiteLink( 'enwiki' );
		$siteLinkPage = $siteLink->getPageName();

		$pageStore = new WikitextPageStore();
		$page = new WikitextPage( $siteLinkPage );

		$itemData = array(
			'id' => $item->getId()->getSerialization(),
			'label' => $item->getLabel( 'en' ),
			'description' => $item->getDescription( 'en' ),
			'page' => $siteLinkPage,
			'snippet' => $pageStore->getSnippet( $page, $wiki )
		);

		return $itemData;
	}

}
