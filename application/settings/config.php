<?php

/**
 * Simple application configuration
 */
return new \Phalcon\Config([
	
	'database' => [
		'adapter'    => 'Mysql',
		'host'       => '127.0.0.1',
		'username'   => 'root',
		'password'   => '1DggfVtmx3keljqR',
		'dbname'     => 'carts',
		'persistent' => true,
		'charset'    => 'utf8'
	],

	'application' => [
		'baseUri'     => '/',
		'annotations' => ['adapter' => 'Apcu'],
		'models'      => [
			'metadata' => ['adapter' => 'Apcu']
		]
	]
]);
