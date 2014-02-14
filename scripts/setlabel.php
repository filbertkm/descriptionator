<?php

use \WikiClient\MediaWiki\ApiClient;
use \WikiClient\MediaWiki\WikiFactory;

require __DIR__ . '/bootstrap.php';

$wiki = WikiFactory::newWiki( $app['wikis'], 'testrepo' );

$params = array(
	'action' => 'wbsetlabel',
	'id' => 'Q4',
	'language' => 'en',
	'value' => 'Roma'
);

$client = new ApiClient( $wiki, '/tmp' );
$results = $client->doEdit( $params );
$data = json_decode( $results, true );

var_export( $data );

/*
$results = $app['oauth.request']->edit( $wiki, $params );
$data = json_decode( $results, true );

var_export( $data );
*/
