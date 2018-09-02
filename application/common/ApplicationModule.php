<?php

namespace Karts\Application;

use \Phalcon\Mvc\ModuleDefinitionInterface;
use \Phalcon\Mvc\User\Module as UserModule;

use \Karts\Application\RoutedModule;

/**
 * Abstract application module base class
 */
abstract class ApplicationModule extends UserModule implements ModuleDefinitionInterface, RoutedModule
{

}
