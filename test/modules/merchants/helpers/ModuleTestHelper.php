<?php
use Phalcon\DI,
    Phalcon\DI\FactoryDefault;

ini_set('display_errors',1);
error_reporting(E_ALL);

define('ROOT_PATH', __DIR__ . '/../../../../');
define('PATH_LIBRARY', __DIR__ . '/common/lib/application/');

set_include_path(
    ROOT_PATH . PATH_SEPARATOR . get_include_path()
);

/**
 * Use the application autoloader to autoload the required
 * bootstrap and test helper classes
 */
$loader = new \Phalcon\Loader();
$loader->registerNamespaces([
    'Phalcon\Test' => ROOT_PATH . 'test/phalcon/',
    'Merchants\Application' => ROOT_PATH . 'common/lib/application/',
    'Merchants\Application\Controllers' => ROOT_PATH . 'common/lib/application/controllers/',
    'Merchants\Test\Helper' => ROOT_PATH . 'test/helpers/',
    'Merchants\Merchants\Controllers\API' => ROOT_PATH . 'modules/merchants/controllers/api/',
    'Merchants\Merchants\Controllers' => ROOT_PATH . 'modules/merchants/controllers/',
    'Merchants\Merchants\Test\Helper' => ROOT_PATH . 'test/modules/merchants/helpers/',
    'Merchants\Merchants' => ROOT_PATH . 'modules/merchants/'
])->register();

$di = new FactoryDefault();
DI::reset();

// add any needed services to the DI here

DI::setDefault($di);
