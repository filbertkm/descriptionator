<?php

namespace Descriptionator\MediaWiki;

use WikiClient\MediaWiki\ApiClient;
use WikiClient\MediaWiki\Wiki;

class WikitextPageStore {

	public function getSnippet( WikitextPage $page, Wiki $wiki ) {
		$client = new ApiClient( $wiki, '/tmp/' );

		$params = $client->buildParams(
			array(
				'action' => 'query',
				'prop' => 'extracts',
				'exchars' => 300,
				'titles' => $page->getName()
			)
		);

		$data = json_decode( $client->get( $params ), true );

		foreach( $data['query']['pages'] as $pageItem ) {
			$sections = explode( '<h2>', $pageItem['extract'] );

			return $sections[0];
		}

		return '';
	}

}
