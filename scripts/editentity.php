<?php

use \WikiClient\MediaWiki\ApiClient;
use \WikiClient\MediaWiki\WikiFactory;

require __DIR__ . '/bootstrap.php';

$wiki = WikiFactory::newWiki( $app['wikis'], 'testrepo' );

$params = array(
	'action' => 'wbeditentity',
	'new' => 'item',
	'data' => '{}'
);

$results = $app['oauth.request']->edit( $wiki, $params );
$data = json_decode( $results, true );

var_export( $data );
