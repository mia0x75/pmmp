<?php

namespace Karts\Merchants;

use \Phalcon\Mvc\Router\Group;

/**
 * This class defines routes for the Merchants\Merchants module
 * which will be prefixed with '/merchants'
 */
class Routes extends Group
{
	/**
	 * Initialize the router group for the Merchants module
	 */
	public function initialize()
	{
		/**
		 * In the URI this module is prefixed by '/merchants'
		 */
		$this->setPrefix('/merchants');
		
		/**
		 * Configure the instance
		 */
		$this->setPaths([
			'module'     => 'merchants',
			'namespace'  => 'Karts\Merchants\Controllers\\',
			'controller' => 'index',
			'action'     => 'index'
		]);
		
		/**
		 * Default route: 'merchants-root'
		 */
		$this->addGet('', [])
			->setName('merchants-root');
		
		/**
		 * Controller route: 'merchants-controller'
		 */
		$this->addGet('/:controller', ['controller' => 1])
			->setName('merchants-controller');
		
		/**
		 * Action route: 'merchants-action'
		 */
		$this->addGet('/:controller/:action/:params', [
				'controller' => 1,
				'action'     => 2,
				'params'     => 3
			])
			->setName('merchants-action');
		
		/**
		 * Add all Merchants\Merchants specific routes here
		 */
	}
}
