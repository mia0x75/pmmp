<?php

namespace Karts\Backend\Controllers;

use \Karts\Backend\Controllers\ModuleController;

/**
 * Concrete implementation of Backend module controller
 *
 * @RoutePrefix("/backend")
 */
class IndexController extends ModuleController
{
	/**
     * @Route("/index", paths={module="backend"}, methods={"GET"}, name="backend-index-index")
     */
    public function indexAction()
    {

    }
}
