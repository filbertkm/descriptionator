<?php

namespace Descriptionator\MediaWiki;

class Wiki {

	protected $siteId;

	protected $baseUrl;

	public function __construct( $siteId, $baseUrl ) {
		$this->siteId = $siteId;
		$this->baseUrl = $baseUrl;
	}

	public function getSiteId() {
		return $this->siteId;
	}

	public function getBaseUrl() {
		return $this->baseUrl;
	}

}
