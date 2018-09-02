<?php

namespace Karts\Frontend\Models;

use Karts\Application\Models\ApplicationModel;

/**
 * Base class of Frontend model
 */
class ModuleModel extends ApplicationModel
{
	public function getSource()
	{
		$di = \Phalcon\DI::getDefault();
		//$config = $di->get('config');
		return 'bs_' . strtolower(parent::getSource());
	}
	
	public function initialize()
	{
		$this->setConnectionService('db');
		$this->setSchema('db');
	}
}
