<?php

namespace Descriptionator\MediaWiki;

interface CategoryMemberLookup {

	public function find( $name, Wiki $wiki );

}
