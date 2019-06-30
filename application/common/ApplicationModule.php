<?php

namespace Karts\Application;

use \Phalcon\Mvc\ModuleDefinitionInterface;

use \Karts\Application\RoutedModule;

/**
 * Abstract application module base class
 */
abstract class ApplicationModule implements ModuleDefinitionInterface, RoutedModule
{

}
