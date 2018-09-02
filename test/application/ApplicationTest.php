<?php

namespace Karts\Test;

use \Phalcon\DI,
	\Phalcon\Loader,
	Karts\Test\Helper\UnitTestCase;

/**
 * Test class for Karts Application class
 */
class ApplicationTest extends UnitTestCase
{
	/**
	 * Test application instance matches the app service
	 *
	 * @covers \Karts\Application\Application::__construct
	 */
	public function testInternalApplicationService()
	{
		$this->assertEquals($this->application, $this->application->di->get('app'));
	}

	/**
	 * Test service registration
	 *
	 * @covers \Karts\Application\Application::_registerServices
	 */
	public function testServiceRegistration()
	{
		$this->assertInstanceOf('\Phalcon\Mvc\Router', $this->application->di->get('router'));
		$this->assertInstanceOf('\Phalcon\Session\Adapter', $this->application->di->get('session'));
		$this->assertInstanceOf('\Phalcon\Mvc\Model\MetaData', $this->application->di->get('modelsMetadata'));
		$this->assertInstanceOf('\Phalcon\Annotations\Adapter', $this->application->di->get('annotations'));
		$this->assertInstanceOf('\Phalcon\Events\Manager', $this->application->getEventsManager());
	}

	/**
	 * Simple test for registerModules method
	 *
	 * @covers \Karts\Application\Application::registerModules
	 */
	public function testModuleIsRegistered()
	{
		$this->assertArrayHasKey('frontend', $this->application->getModules());
	}

	/**
	 * Test applicaton HMVC request
	 *
	 * @covers \Karts\Application\Application::request
	 */
	public function testHMVCApplicationRequest()
	{
		$controllerName = 'index';
		$indexCntrl = $this->getController($controllerName);

        $this->assertInstanceOf(
        	'\Phalcon\Mvc\Controller',
        	$indexCntrl,
        	sprintf('Make sure the %sController matches the internal HMVC request.', ucfirst($controllerName))
        );

		$this->assertEquals(
			$indexCntrl->indexAction(),
			$this->application->request([
				'namespace' => 'Karts\Frontend\Controllers\API',
				'module' => 'frontend',
				'controller' => $controllerName,
				'action' => 'index'
			]),
			sprintf(
				'Assert that calling the %s action of the %sController matches the internal HMVC request.',
				$controllerName,
				ucfirst($controllerName)
			)
		);
	}

	/**
	 * Helper to load the a controller
	 *
	 * @coversNothing
	 */
	public function getController($name)
	{
		$loader = new Loader();
		$loader->registerClasses([
			'\Karts\Frontend\Controllers\API\\' . ucfirst($name) . 'Controller' => ROOT_PATH . 'modules/frontend/controller/api/'
		])->register();

		$indexCntrl = new \Karts\Frontend\Controllers\API\IndexController();
		$this->assertNotNull($indexCntrl, 'Make sure the index controller could be loaded');

		return $indexCntrl;
	}
}
