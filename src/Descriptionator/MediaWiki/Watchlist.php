<?php

namespace Descriptionator\MediaWiki;

class Watchlist {

	public function get( $oauthRequest ) {
		$apiParams = array(
			'action' => 'query',
			'format' => 'json',
			'list' => 'watchlistraw',
			'wrnamespace' => 0
		);

		return $oauthRequest->doRequest( $apiParams );
	}

}
