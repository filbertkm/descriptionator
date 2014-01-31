<?php

require __DIR__ . '/bootstrap.php';

use Descriptionator\Store\SchemaInstaller;

$schema = $app['db']->getSchemaManager();

$installer = new SchemaInstaller();
$installer->install( $schema );

echo "done\n";
