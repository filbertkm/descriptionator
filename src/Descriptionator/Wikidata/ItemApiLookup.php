<?php

namespace Descriptionator\Wikidata;

use Descriptionator\MediaWiki\Wiki;
use WikiClient\MediaWiki\ApiClient;
use WikiClient\MediaWiki\User;

class ItemApiLookup implements ItemLookup {

	protected $user;

	protected $wiki;

	public function __construct( User $user, Wiki $wiki ) {
		$this->client = new ApiClient( '/tmp/', $wiki->getBaseUrl(), $user );
	}

	public function getByItemId( $itemId ) {
		$params = $this->client->buildParams(
			array(
				'action' => 'wbgetentities',
				'ids' => $itemId,
				'format' => 'json'
			)
		);

		$data = json_decode( $this->client->get( $params ), true );
		$item = Item::newFromArray( $data );

		return $item;
	}

	public function getItemsBySiteLinks( $pages, $site ) {
		$items = array();
		$pageSet = array();

		$i = 1;

		while( $i === 1 ) {
			$pageSet = count( $pages ) <= 50 ? $pages : array_slice( $pages, 0, 50, true );

			$params = $this->client->buildParams(
				array(
					'action' => 'wbgetentities',
					'sites' => $site,
					'titles' => implode( '|', $pageSet ),
					'props' => 'labels|descriptions|sitelinks',
					'languages' => 'en'
				)
			);

			$result = $this->client->get( $params );
			$data = json_decode( $result, true );

			$items = array_merge( $items, $data['entities'] );

			if ( count( $pages ) <= 50 ) {
				$i = 0;
			}

			$pages = array_slice( $pages, 50 );
		}

		return $items;
	}

}
