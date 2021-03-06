<?php

namespace Descriptionator\Wikidata;

use WikiClient\MediaWiki\ApiClient;
use WikiClient\MediaWiki\User;
use WikiClient\MediaWiki\Wiki;

class ItemApiLookup implements ItemLookup {

	protected $client;

	public function __construct( Wiki $wiki ) {
		$this->client = new ApiClient( $wiki, '/tmp/' );
	}

	public function getItem( $itemId ) {
		$params = $this->client->buildParams(
			array(
				'action' => 'wbgetentities',
				'ids' => $itemId,
				'format' => 'json'
			)
		);

		$data = json_decode( $this->client->get( $params ), true );
		$deserializer = new ItemDeserializer();

		foreach( $data['entities'] as $item ) {
			return $deserializer->deserialize( $item );
		}

		return array();
	}

	public function getItemsBySiteLinks( $pages, $site ) {
		$items = array();
		$pageSet = array();

		$i = 1;

		while( $i === 1 ) {
			$pageSet = count( $pages ) <= 50 ? $pages : array_slice( $pages, 0, 50, true );

			if ( count( $pageSet ) === 0 ) {
				// no pages
				return array();
			}

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

	public function getSiteLink( $id, $lang ) {
		$data = $this->getItem( $id );
	}

}
