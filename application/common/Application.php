<?php

namespace Karts\Application;

use \Phalcon\Mvc\Url as UrlResolver;
use \Phalcon\DiInterface;
use \Phalcon\Mvc\View;
use \Phalcon\Loader;
use \Phalcon\Http\ResponseInterface;
use \Phalcon\Events\Manager as EventsManager;
use \Phalcon\Session\Adapter\Files as SessionAdapter;

use \Karts\Application\Router\ApplicationRouter;

/**
 * Application class for multi module applications
 * including HMVC internal requests.
 */
class Application extends \Phalcon\Mvc\Application
{
	/**
	 * Application Constructor
	 *
	 * @param \Phalcon\DiInterface $di
	 */
	public function __construct(DiInterface $di)
	{
		/**
		 * Sets the parent DI and register the app itself as a service,
		 * necessary for redirecting HMVC requests
		 */
		parent::setDI($di);
		$di->set('app', $this);

		/**
		 * Register application wide accessible services
		 */
		$this->_registerServices();

		/**
		 * Register the installed/configured modules
		 */
		$this->registerModules(require __DIR__ . '/../settings/modules.php');
	}

	/**
	 * Register the services here to make them general or register in the
	 * ModuleDefinition to make them module-specific
	 */
	protected function _registerServices()
	{
		/**
		 * The application wide configuration
		 */
		$config = include __DIR__ . '/../settings/config.php';
		$this->di->set('config', $config);

		/**
		 * Setup an events manager with priorities enabled
		 */
		$eventsManager = new EventsManager();
		$eventsManager->enablePriorities(true);
		$this->setEventsManager($eventsManager);

		/**
		 * Register namespaces for application classes
		 */
		$loader = new Loader();
		$loader->registerNamespaces([
				'Karts\Application'             => __DIR__,
				'Karts\Application\Controllers' => __DIR__ . '/controllers/',
				'Karts\Application\Models'      => __DIR__ . '/models/',
				'Karts\Application\Router'      => __DIR__ . '/routers/'
			], true)
			->register();

		/**
		 * Start the session the first time some component request the session service
		 */
		$this->di->set('session', function () {
			$session = new SessionAdapter();
			$session->start();
			return $session;
		});

		/**
		 * Registering the application wide router with the standard routes set
		 */
		$this->di->set('router', new ApplicationRouter());

		/**
		 * Specify the use of metadata adapter
		 */
		$this->di->set('modelsMetadata', '\Phalcon\Mvc\Model\Metadata\\' . $config->application->models->metadata->adapter);

		/**
		 * Specify the annotations cache adapter
		 */
		$this->di->set('annotations', '\Phalcon\Annotations\Adapter\\' . $config->application->annotations->adapter);
	}

	/**
	 * Register the given modules in the parent and prepare to load
	 * the module routes by triggering the init routes method
	 */
	public function registerModules(array $modules, bool $merge = false): \Phalcon\Application
	{
		parent::registerModules($modules, $merge);

		$loader = new Loader();
		$modules = $this->getModules();

		/**
		 * Iterate the application modules and register the routes
		 * by calling the initRoutes method of the Module class.
		 * We need to auto load the class 
		 */
		foreach ($modules as $module) {
			$className = $module['className'];

			if (!class_exists($className, false)) {
				$loader->registerClasses([ $className => $module['path'] ], true)->register()->autoLoad($className);
			}

			/** @var \Karts\Application\ApplicationModule $className */
			$className::initRoutes($this->di);
		}

		return $this;
	}

	/**
	 * Handles the request and echoes its content to the output stream.
	 */
	public function main()
	{
		echo $this->handle($_SERVER["REQUEST_URI"])->getContent();
	}

	 /**
	  * Does a HMVC request inside the application
	  *
	  * Inside a controller we might do
	  * <code>
	  * $this->app->request([ 'controller' => 'do', 'action' => 'something' ], 'param');
	  * </code>
	  *
	  * @param array $location Array with the route information: 'namespace', 'module', 'controller', 'action', 'params'
	  * @return mixed
	  */
	public function request(array $location)
	{
		/** @var \Phalcon\Mvc\Dispatcher $dispatcher */
		$dispatcher = clone $this->di->get('dispatcher');

		if (isset($location['module'])) {
			$dispatcher->setModuleName($location['module']);
		}

		if (isset($location['namespace'])) {
			$dispatcher->setNamespaceName($location['namespace']);
		}

		if (!isset($location['controller'])) {
			$location['controller'] = 'index';
		}

		if (!isset($location['action'])) {
			$location['action'] = 'index';
		}

		if (!isset($location['params'])) {
			$location['params'] = [];
		}

		$dispatcher->setControllerName($location['controller']);        	
		$dispatcher->setActionName($location['action']);
		$dispatcher->setParams((array) $location['params']);
		$dispatcher->dispatch();

		$response = $dispatcher->getReturnedValue();

		if ($response instanceof ResponseInterface) {
			return $response->getContent();
		}

		return $response;
	}
}
