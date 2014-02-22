<?php

namespace Descriptionator\Store;

use Descriptionator\User\User;
use Silex\Application;
use WikiClient\MediaWiki\ApiClient;
use WikiClient\MediaWiki\Wiki;

class ItemStore {

	protected $app;

	protected $wiki;

	public function __construct( Application $app, Wiki $wiki ) {
		$this->app = $app;
		$this->wiki = $wiki;
	}

	public function saveDescription( $itemId, $description ) {
		$oauthRequest = $this->app['oauth.request'];

		$params = array(
			'action' => 'wbsetdescription',
			'id' => $itemId,
			'summary' => 'settingdescription', // to "' . $description . '"',
			'language' => 'en',
			'value' => $description,
		);

		$result = $oauthRequest->edit(
			$this->wiki,
			$params,
			$this->app['session']->get( 'oauth_token' ),
			$this->app['session']->get( 'oauth_secret' )
		);

		var_export( $result );

		return $result;
	}

}
