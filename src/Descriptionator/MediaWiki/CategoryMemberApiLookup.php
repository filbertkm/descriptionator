<?php

namespace Descriptionator\MediaWiki;

use WikiClient\MediaWiki\ApiClient;
use WikiClient\MediaWiki\User;

class CategoryMemberApiLookup implements CategoryMemberLookup {

	protected $user;

	public function __construct( User $user ) {
		$this->user = $user;
	}

	public function find( $cat, Wiki $wiki ) {
		$client = new ApiClient( '/tmp/', $wiki->getBaseUrl(), $this->user );

		$params = $client->buildParams(
			array(
				'action' => 'query',
				'list' => 'categorymembers',
				'cmlimit' => '100',
				'cmnamespace' => 0,
				'cmprop' => 'title',
				'cmtitle' => $cat
			)
		);

		$data = json_decode( $client->get( $params ), true );
		$pages = array();

		foreach( $data['query']['categorymembers'] as $member ) {
			$pages[] = $member['title'];
		}

		return $pages;
	}

}
