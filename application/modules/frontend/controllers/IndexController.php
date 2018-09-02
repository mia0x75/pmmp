<?php

namespace Karts\Frontend\Controllers;

use \Karts\Frontend\Controllers\ModuleController;

/**
 * Concrete implementation of Frontend module controller
 *
 * @RoutePrefix("/frontend")
 */
class IndexController extends ModuleController
{
	/**
     * @Route("/index", paths={module="frontend"}, methods={"GET"}, name="frontend-index-index")
     */
    public function indexAction()
    {

    }
}
