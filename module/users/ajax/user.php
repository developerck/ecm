<?php
/**
 *
 *
 * @server 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      servers
 * @controller  server
 */
namespace module\users\ajax;

class User extends \module\users\UsersController
{

   	protected $_serv;
	public function __construct() {

		parent::__construct ();

		$this->_serv = new \module\users\service\User();

		$this->baseurl = $this->baseurl . '/users/';
		$this->basemethod = 'base';
	}


	public function base() {
		throw new \devlib\Exception('Ajax Without Action On user Controller!');
	}
	
	public function getUserInfoById(){
		global $CNF, $USER;
			//$serverid= \devlib\AppController::getKeyValueRequired ( 'serverid' );
			$param = $this->f3->get ( 'POST' );
			$this->view = 'userprofile.php';
			
			$param = json_decode($param['postdata'],true);
			
			if(!isset($param['id'])){
				throw  new \devlib\Exception('Parameter Not Found!','AJAX');
			}
			
			
			  $user = new \module\users\lib\UserFactory($param['id']);
			  $this->form ['data'] =$user->userobj; 
			
			
	} 
	
}
