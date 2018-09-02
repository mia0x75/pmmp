<?php

namespace Karts\Frontend;

use \Phalcon\Loader;
use \Phalcon\DI;
use \Phalcon\Mvc\View;
use \Phalcon\Mvc\Dispatcher;
use \Phalcon\Config;
use \Phalcon\DiInterface;
use \Phalcon\Mvc\Url as UrlResolver;
use \Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

use \Karts\Application\ApplicationModule;

/**
 * Application module definition for multi module application
 * Defining the Frontend module 
 */
class Module extends ApplicationModule
{
	/**
	 * Mount the module specific routes before the module is loaded.
	 * Add ModuleRoutes Group and annotated controllers for parsing their routing information.
	 *
	 * @param \Phalcon\DiInterface  $di
	 */
	public static function initRoutes(DiInterface $di)
	{
		$loader = new Loader();
		$loader->registerNamespaces([
			'Karts\Frontend'                 => __DIR__,
			'Karts\Frontend\Controllers'     => __DIR__ . '/controllers/',
		], true)
		->register();
		
		/**
		 * Add ModuleRoutes Group and annotated controllers for parsing their routing information.
		 * Be aware that the parsing will only be triggered if the request URI matches the third
		 * parameter of addModuleResource.
		 */
		$router = $di->getRouter();
		$router->mount(new Routes());
		
	 	/**
	 	 * Read names of annotated controllers from the module config and add them to the router
	 	 */
		$moduleConfig = include __DIR__ . '/settings/config.php';
		if ( isset($moduleConfig['controllers']['annotationRouted']) ) {
			foreach ($moduleConfig['controllers']['annotationRouted'] as $ctrl) {
				$router->addModuleResource('frontend', $ctrl, '/frontend');
			}
		}
	}
	
	/**
	 * Registers the module auto-loader
	 */
	public function registerAutoloaders(DiInterface $di = NULL)
	{
		$loader = new Loader();
		$loader->registerNamespaces([
			'Karts\Frontend'                 => __DIR__,
			'Karts\Frontend\Controllers'     => __DIR__ . '/controllers/',
			'Karts\Frontend\Controllers\API' => __DIR__ . '/controllers/api/',
			'Karts\Frontend\Models'          => __DIR__ . '/models/',
			'Karts\Frontend\Library'         => __DIR__ . '/libraries/',
		], true)
		->register();
	}
	
	/**
	 * Registers the module-only services
	 *
	 * @param \Phalcon\DiInterface $di
	 */
	public function registerServices(DiInterface $di)
	{
		/**
		 * Read application wide and module only configurations
		 */
		$appConfig = $di->get('config');
		$moduleConfig = include __DIR__ . '/settings/config.php';
		
		$di->set('moduleConfig', $moduleConfig);
		
		/**
		 * Setting up the view component
		 */
		$di->set('view', function() {
			$view = new View();
			
			$view->setViewsDir(__DIR__ . '/views/');
			$view->setLayoutsDir(__DIR__ . '/layouts/');
			$view->setPartialsDir(__DIR__ . '/partials/');
			$view->setTemplateAfter('main');
			$view->registerEngines(['.volt' => 'Phalcon\Mvc\View\Engine\Volt']);
			
			return $view;
		});
		
		$di->set('volt', function($view, $di) {
			$volt = new Phalcon\Mvc\View\Engine\Volt($view, $di);
			$volt->setOptions([
				'compiledPath' => '/cache/frontend/'
			]);
			
			$compiler = $volt->getCompiler();
			/**
			 * PHP native functions injection
			 */
			$compiler->addFunction('is_a', 'is_a');
			$compiler->addFunction('strtotime', 'strtotime');
			$compiler->addFunction('substr_replace', 'substr_replace');
			$compiler->addFunction('rand', 'rand');
			
			return $volt;
		}, true);

		/**
		 * The URL component is used to generate all kind of urls in the application
		 */
		$di->set('url', function () use ($appConfig) {
			$url = new UrlResolver();
			
			$url->setBaseUri($appConfig->application->baseUri);
			
			return $url;
		});

		/**
		 * Module specific dispatcher
		 */
		$di->set('dispatcher', function () use ($di) {
			$dispatcher = new Dispatcher();
			
			$dispatcher->setEventsManager($di->getShared('eventsManager'));
			$dispatcher->setDefaultNamespace('Karts\Frontend\\');
			
			return $dispatcher;
		});
		
		/**
		 *
		 */
		$di->set('profiler', function() {
			return new \Phalcon\Db\Profiler();
		}, true);
		
		/**
		 * Module specific database connection
		 */
		$di->set('db', function() use ($moduleConfig) {
			$eventsManager = new \Phalcon\Events\Manager();
			$profiler = $this->getProfiler();
			
			$eventsManager->attach('db', function($event, $connection) use ($profiler) {
				if ($event->getType() == 'beforeQuery') {
					$profiler->startProfile($connection->getSQLStatement());
				}
				if ($event->getType() == 'afterQuery') {
					$profiler->stopProfile();
				}
			});
			
			$db = new DbAdapter([
				'host'     => $moduleConfig->database->host,
				'port'     => $moduleConfig->database->port,
				'username' => $moduleConfig->database->username,
				'password' => $moduleConfig->database->password,
				'dbname'   => $moduleConfig->database->dbname
			]);
			
			$db->setEventsManager($eventsManager);
			
			return $db;
		});
	}
}
