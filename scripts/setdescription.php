<?php

use \WikiClient\MediaWiki\ApiClient;
use \WikiClient\MediaWiki\WikiFactory;

require __DIR__ . '/bootstrap.php';

$wiki = WikiFactory::newWiki( $app['wikis'], 'testrepo' );

$params = array(
	'action' => 'wbsetdescription',
	'id' => 'Q4',
	'language' => 'en',
	'value' => 'Romo'
);
/*
$client = new ApiClient( $wiki, '/tmp' );
$results = $client->doEdit( $params );
$data = json_decode( $results, true );

var_export( $data );
*/
$params['value']['rom'];

$results = $app['oauth.request']->edit( $wiki, $params );
$data = json_decode( $results, true );

var_export( $data );
