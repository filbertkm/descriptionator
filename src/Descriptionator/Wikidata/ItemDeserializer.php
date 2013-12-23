<?php

namespace Descriptionator\Wikidata;

use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\SiteLink;

class ItemDeserializer {

	public function deserialize( array $data ) {
		$item = Item::newEmpty();

		if ( array_key_exists( 'id', $data ) ) {
			$itemId = new ItemId( $data['id'] );
			$item->setId( $itemId );
		}

		if ( array_key_exists( 'labels', $data ) ) {
			foreach( $data['labels'] as $lang => $label ) {
				$item->setLabel( $lang, $label['value'] );
			}
		}

		if ( array_key_exists( 'descriptions', $data ) ) {
			foreach( $data['descriptions'] as $lang => $label ) {
				$item->setDescription( $lang, $label['value'] );
			}
		}

		if ( array_key_exists( 'sitelinks', $data ) ) {
			foreach( $data['sitelinks'] as $siteId => $siteLink ) {
				$item->addSiteLink( new SiteLink( $siteId, $siteLink['title'] ) );
			}
		}

		return $item;
	}

}
