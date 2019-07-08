<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      users
 *
 */
namespace module\users\lib;
//TODO: defination of class

// TODO: will make admin and user class seperate that's why making it factory

class UserFactory{
	private $uid;
	public $userobj;

	public function __construct($uid){
		if(!$uid){
			throw new \devlib\Exception('Uid should be set for userfactory!');
		}
		$this->uid = $uid;
		$this->_initUserObject();

	}

	/*
	 * Make global user object
	 * or return user object on demand
	 *
	 *
	 */
	public function _initUserObject(){
		// get user detail

		$userobj = new \module\users\service\User();
        $this->userobj['uid'] = $this->uid;
		$this->userobj['user_detail'] = $userobj->getUserById($this->uid);
		$urobj = new \module\users\service\UserRole();
		$roledata = $urobj->getRoleByUserId($this->uid);
		$this->userobj['user_role']= $roledata;
		$rpobj = new \module\users\service\RolePermission();
		$this->userobj['user_permission'] = $rpobj->getPermissionByRoleId($roledata['role_id']);
		//TODO : This should be projects lib object in future 
		$project = new \module\projects\service\Project();
		if ($this->userobj['user_role']['shortname'] == 'ADMIN') {
			$procond = 'where isactive = 1 ';
		} else {
			$procond = 'where p.isactive=1 and pu.user_id = ' . $this->uid;
		}
		$prodata = $project->getProjects($procond );
		$this->userobj['user_projects'] = $prodata['data'];
		//TODO : This should be servers lib object in future
		$server = new \module\servers\service\Server();
		
		if ($this->userobj['user_role'] ['shortname'] == 'ADMIN') {
			$servcond = '';
		} else {
			$servcond = 'where s.isactive = 1 and su.user_id = ' . $this->uid;
		}
		$servdata = $server->getServers($servcond);
		$this->userobj['user_servers'] =$servdata['data'];
		//TODO:  extend it



	}
}