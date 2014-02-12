<?php

namespace Descriptionator\MediaWiki;

use WikiClient\MediaWiki\Wiki;

interface CategoryMemberLookup {

	public function find( $cat, Wiki $wiki );

}
