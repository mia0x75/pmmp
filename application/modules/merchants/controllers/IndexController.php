<?php

namespace Karts\Merchants\Controllers;

use \Karts\Merchants\Controllers;

/**
 * Concrete implementation of Merchants module controller
 *
 * @RoutePrefix("/merchants")
 */
class IndexController extends ModuleController
{
	/**
	 * @Route("/index", paths={module="merchants"}, methods={"GET"}, name="merchants-index-index")
	 */
	public function indexAction()
	{
	}
}
