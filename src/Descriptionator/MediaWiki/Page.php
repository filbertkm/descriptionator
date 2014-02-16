<?php

namespace Descriptionator\MediaWiki;

interface Page {

	/**
	 * @param string
	 */
	public function __construct( $name );

	/**
	 * @return string
	 */
	public function getName();

}
