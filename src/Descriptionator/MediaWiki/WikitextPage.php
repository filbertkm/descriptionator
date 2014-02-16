<?php

namespace Descriptionator\MediaWiki;

class WikitextPage implements Page {

	protected $name;

	public function __construct( $name ) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

}
