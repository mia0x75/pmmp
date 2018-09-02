<?php

return new \Phalcon\Config([
	'database' => [
		'adapter'    => 'Mysql',
		'host'       => '127.0.0.1',
		'port'       => 3306,
		'username'   => 'root',
		'password'   => '1DggfVtmx3keljqR',
		'dbname'     => 'carts',
		'persistent' => true,
		'charset'    => 'utf8'
	],

	'controllers' => [
		'annotationRouted' => [
			'\Karts\Frontend\Controllers\Index',
		]
	]
]);
