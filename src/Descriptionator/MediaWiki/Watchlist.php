<?php

namespace Descriptionator\MediaWiki;

use WikiClient\MediaWiki\WikiFactory;

class Watchlist {

	public function get( $app, $oauthRequest ) {
		$wiki = WikiFactory::newWiki( $app['wikis'], 'testrepo' );

		$apiParams = array(
			'action' => 'query',
			'format' => 'json',
			'list' => 'watchlistraw',
			'wrnamespace' => 0
		);

		return $oauthRequest->get( $wiki, $apiParams );
	}

}
