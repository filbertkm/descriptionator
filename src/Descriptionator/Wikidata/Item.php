<?php

namespace Descriptionator\Wikidata;

class Item {

	protected $data;

	public function __construct( array $data ) {
		$this->data = $data;
	}

	public static function newFromArray( $data ) {
		return new self( $data );
	}

	public function getLabel( $lang ) {
		$entities = $this->data['entities'];

		$ids = array_keys( $entities );
		$id = $ids[0];

		$label = array_key_exists( $lang, $this->data['entities'][$id]['labels'] )
			? $this->data['entities'][$id]['labels'][$lang]['value']
			: 'not set';

		return $label;
	}

	public function getDescription( $lang ) {
		return 'description';
	}

}
