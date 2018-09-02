<?php
/**
 * Copyrights © 2013 Biztalk Mobility Ltd.
 */
namespace Karts\Frontend\Models;

use Karts\Application\Models\ApplicationModel;

class Members extends ModuleModel
{
	public $member_id;
	public $mobile;
	public $password;
	public $disabled;
	public $creation_date;
	public $uuid;
	
	public function beforeValidationOnCreate()
	{
		if (!$this->disabled) {
			$this->disabled = new \Phalcon\Db\RawValue('default');
		}
		if (!$this->creation_date) {
			$this->creation_date = new \Phalcon\Db\RawValue('default');
		}
	}
	
	public function initialize()
	{
		/*
		$this->hasMany('mid', 'Balances', 'mid');
		$this->hasMany('mid', 'Xchgs', 'mid');
		$this->hasMany('mid', 'Trans', 'mid');
		$this->hasMany('mid', 'Speedtests', 'mid');
		$this->hasMany('mid', 'Hotspots', 'mid');
		$this->hasMany('mid', 'Rewards', 'mid');
		$this->hasMany('mid', 'Reviews', 'mid');
		$this->hasMany('mid', 'Trails', 'mid');
		$this->hasOne('mid', 'Dcis', 'mid');
		 */
	}
}

