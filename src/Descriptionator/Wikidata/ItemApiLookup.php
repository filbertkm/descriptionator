<?php

namespace Descriptionator\Wikidata;

use Descriptionator\MediaWiki\Wiki;
use WikiClient\MediaWiki\ApiClient;
use WikiClient\MediaWiki\User;

class ItemApiLookup implements ItemLookup {

	protected $user;

	public function __construct( User $user ) {
		echo 'lookup';
		$this->user = $user;
	}

	public function find( $id, Wiki $wiki ) {
		$client = new ApiClient( '/tmp/', $wiki->getBaseUrl(), $this->user );

		$params = $client->buildParams(
			array(
				'action' => 'wbgetentities',
				'ids' => $id,
				'format' => 'json'
			)
		);

		$data = json_decode( $client->get( $params ), true );
		$item = Item::newFromArray( $data );

		return $item;
	}

}
