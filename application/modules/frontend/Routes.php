<?php

namespace Karts\Frontend;

use \Phalcon\Mvc\Router\Group;

/**
 * This class defines routes for the Karts\Frontend module
 * which will be prefixed with '/frontend'
 */
class Routes extends Group
{
	/**
	 * Initialize the router group for the Frontend module
	 */
	public function initialize()
	{
		/**
		 * In the URI this module is prefixed by '/frontend'
		 */
		$this->setPrefix('/frontend');

		/**
		 * Configure the instance
		 */
		$this->setPaths([
			'module'     => 'frontend',
			'namespace'  => 'Karts\Frontend\Controllers\\',
			'controller' => 'index',
			'action'     => 'index'
		]);

		/**
		 * Default route: 'frontend-root'
		 */
		$this->addGet('', [])
			->setName('frontend-root');

		/**
		 * Controller route: 'frontend-controller'
		 */
		$this->addGet('/:controller', ['controller' => 1])
			->setName('frontend-controller');

		/**
		 * Action route: 'frontend-action'
		 */
		$this->addGet('/:controller/:action/:params', [
				'controller' => 1,
				'action'     => 2,
				'params'     => 3
			])
			->setName('frontend-action');

		/**
		 * Add all Karts\Frontend specific routes here
		 */
	}
}
