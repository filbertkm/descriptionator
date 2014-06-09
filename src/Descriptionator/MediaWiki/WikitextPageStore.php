<?php

namespace Descriptionator\MediaWiki;

use WikiClient\MediaWiki\ApiClient;
use WikiClient\MediaWiki\Wiki;

class WikitextPageStore {

	/**
	 * @var ApiClient
	 */
	private $client;

	/**
	 * @param ApiClient $client
	 */
	public function __construct( ApiClient $client ) {
		$this->client = $client;
	}

	public function getSnippet( WikitextPage $page  ) {
		$params = array(
			'action' => 'query',
			'prop' => 'extracts',
			'exchars' => 300,
			'titles' => $page->getName()
		);

		$data = json_decode( $this->client->get( $params ), true );

		foreach( $data['query']['pages'] as $pageItem ) {
			$sections = explode( '<h2>', $pageItem['extract'] );

			return $sections[0];
		}

		return '';
	}

}
