<?php

namespace Descriptionator\MediaWiki;

use WikiClient\MediaWiki\ApiClient;
use WikiClient\MediaWiki\Wiki;

class CategoryMemberApiLookup implements CategoryMemberLookup {

	public function find( $cat, Wiki $wiki ) {
		$client = new ApiClient( $wiki );

		$params = $client->buildParams(
			array(
				'action' => 'query',
				'list' => 'categorymembers',
				'cmlimit' => '100',
				'cmnamespace' => 0,
				'cmprop' => 'title',
				'cmtitle' => "Category:$cat"
			)
		);

		$result = $client->get( $params );
		$data = json_decode( $result, true );

		$pages = array();

		foreach( $data['query']['categorymembers'] as $member ) {
			$pages[] = $member['title'];
		}

		return $pages;
	}

}
