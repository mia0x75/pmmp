<?php
/**
 * Copyrights © 2013-2017 Biztalk Mobility Ltd.
 */
namespace Karts\Frontend\Controllers;

use \Karts\Frontend\Models;
use Phalcon\Db\Column as Column;

class MembersController extends ModuleController
{
	private $links = [
	];
	
	public function initialize()
	{
		//TODO:
	}
	
	public function indexAction()
	{
		$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
		
		$where  = '1 = 1 and member_id = :id:';
		$types  = ['id' => Column::BIND_PARAM_INT];
		$params = ['id' => 184189374219419648];
		$member = \Karts\Frontend\Models\Members::findFirst([$where, 'bind' => $params, 'bindTypes' => $types]);
		$result = $member->toArray();
		unset($result['uuid']); //移除二进制字段，不便打印
		print_r($result);
		$md = new \Phalcon\Mvc\Model\MetaData\Apc(['prefix' => 'mta_', 'lifetime' => 86400]);
		
		/*
		$attributes = $md->getAttributes($member);
		print_r($attributes);
		*/
		
		//获取所有的profiler记录结果，这是一个数组，每条记录对应一个sql语句
		$profiles = $this->di->get('profiler')->getProfiles();
		
		//遍历输出
		echo str_repeat('-', 80), "\n";
		foreach ($profiles as $profile) {
			echo "查询语句: ", $profile->getSQLStatement(), "\n";
			echo "开始时间: ", $profile->getInitialTime(), "\n";
			echo "结束时间: ", $profile->getFinalTime(), "\n";
			echo "消耗时间: ", $profile->getTotalElapsedSeconds(), "\n";
			echo str_repeat('-', 80), "\n";
		}
	}
	
	/**
	 * @api {get} /user 获得当前登录用户信息
	 * @apiUse header
	 *
	 * @apiName getUser
	 * @apiGroup User
	 * @apiVersion 1.0.0
	 *
	 * @apiSuccess {Array} user 该用户的信息
	 *     HTTP/1.1 200 OK
	 *       {
	 *           "user": {
	 *               "username": "Admin",
	 *               "name": "Edvard",
	 *               "organization": null,
	 *               "title": "Software Enginner",
	 *               "email": "edvard_hua@live.com"
	 *           }
	 *       }
	 *
	 * @apiUse errorExample
	 */
	public function getUser()
	{
		$token = $this->session->get('token');

		$user = User::findFirst(array(
			'id='.$token->user_id,
			'columns' => 'username, name, organization, title, email'
		));

		return parent::success(array(
			'user' => $user
		));
	}
	
	/**
	 * @api {put} /user 更新当前登录用户信息
	 * @apiUse header
	 *
	 * @apiName updateUser
	 * @apiGroup User
	 * @apiVersion 1.0.0
	 *
	 * @apiParam {String} username 该子会议的ID
	 * @apiParam {String} name 该子会议名称 必选
	 * @apiParam {String} organization 子会议的开始时间
	 * @apiParam {Integer} title 子会议的结束时间
	 * @apiParam {String} email 子会议举行场地
	 * @apiParam {String} password 该子会议可接纳的人数
	 *
	 * @apiSuccess {Array} empty_array 空数组
	 */
	public function updateUser()
	{
		$token = $this->session->get('token');

		// username name organization title email password
		$data = $this->request->get();
		$dbUser = User::findFirst('id='.$token->user_id);
		if(!empty($data['password'])){
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		}
		$dbUser = $dbUser->toArray();

		$userModel = new User();
		if (false == $userModel->save(array_merge($dbUser,$data)))  // 使用修改的数据覆盖原始的数据来达到部分更新效果
			return parent::resWithErrMsg($userModel->getMessages());

		return parent::success();
	}
}
