<?php

namespace Descriptionator\Store;

use Descriptionator\User\User;
use WikiClient\MediaWiki\ApiClient;
use WikiClient\MediaWiki\Wiki;

class ItemStore {

	protected $client;

	public function __construct( Wiki $wiki ) {
		$this->client = new ApiClient( $wiki, '/tmp/' );
	}

	public function saveDescription( $itemId, $description ) {
		$params = $this->client->buildParams(
			array(
				'action' => 'wbsetdescription',
				'id' => $itemId,
				'summary' => 'setting description to "' . $description . '"',
				'language' => 'en',
				'value' => $description,
			)
		);

		$result = $this->client->doEdit( $params );
	}

}
