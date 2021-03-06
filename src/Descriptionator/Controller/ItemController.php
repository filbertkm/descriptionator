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
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Wikibot\Wikibase\ItemLookup;
use WikiClient\MediaWiki\WikiFactory;

class ItemController implements ControllerProviderInterface {

	public function connect( Application $app ) {
		$controller = $app['controllers_factory'];

		$controller->get( '/{id}/edit/', array( $this, 'edit' ) );
		$controller->post( '/{id}/edit/', array( $this, 'edit' ) );
		$controller->get( '/{id}/', array( $this, 'view' ) );

		return $controller;
	}

	public function edit( Application $app, $id ) {
		$itemData = $this->getItemData( $app, $id );

		$data = array(
			'_description' => $itemData['description']
		);

		$form = $app['form.factory']->create( new ItemType(), $data, array() );

		$form->handleRequest( $app['request'] );

		if ( $form->isValid() ) {
			$result = $this->processForm( $app, $form, $id );

//			return $app->redirect( "/item/$id" );
		}

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

	private function processForm( Application $app, Form $form, $id ) {
		$data = $form->getData();

		$itemStore = $app['wikidata.itemstore'];
		$itemStore->saveDescription( $id, $data['_description'] );
	}

	private function getItemData( Application $app, $itemId ) {
		$client = $app['apiclient'];
		$repo = $client( 'wikidatawiki' );
		$wiki = $client( 'enwiki' );

		$itemLookup = new ItemLookup( $repo, $app['entity-deserializer'] );
		$item = $itemLookup->getItem( $itemId );

		$siteLink = $item->getSiteLink( 'enwiki' );
		$siteLinkPage = $siteLink->getPageName();

		$pageStore = new WikitextPageStore( $wiki );
		$page = new WikitextPage( $siteLinkPage );

		$itemData = array(
			'id' => $item->getId()->getSerialization(),
			'label' => $item->getLabel( 'en' ),
			'description' => $item->getDescription( 'en' ),
			'page' => $siteLinkPage,
			'snippet' => $pageStore->getSnippet( $page, 'enwiki' )
		);

		return $itemData;
	}

}
