<?php

namespace Descriptionator\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ItemType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( '_description', 'text' )
			->add( '_csrf_token', 'hidden' );
	}

	public function getName() {
		return '';
	}

}
