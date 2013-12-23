<?php

namespace Descriptionator\Wikidata;

use Descriptionator\MediaWiki\Wiki;

interface ItemLookup {

	public function getByItemId( $itemId );

}
