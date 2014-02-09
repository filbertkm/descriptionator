<?php

namespace Descriptionator\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->setAction( '/admin/check' )
			->add( '_username', 'text' )
			->add( '_password', 'password' )
			->add( '_csrf_token', 'hidden' );
	}

	public function getName() {
		return '';
	}

}
