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
namespace module\servers\ajax;

class Server extends \module\servers\ServersController
{

   	protected $_serv;
	public function __construct() {

		parent::__construct ();

		$this->_serv = new \module\servers\service\Server();

		$this->baseurl = $this->baseurl . '/server/';
		$this->basemethod = 'base';
	}


	public function base() {
		throw new \devlib\Exception('Ajax Without Action On server Controller!');
	}

	public function getAssignedUserOnServer(){
		global $CNF, $USER;
			//$serverid= \devlib\AppController::getKeyValueRequired ( 'serverid' );
			$param = $this->f3->get ( 'POST' );

			$param = json_decode($param['postdata'],true);

			if(!isset($param['serverid'])){
				throw  new \devlib\Exception('Parameter Not Found!','AJAX');
			}
			$procond= 'where  server_id = ' . $param['serverid'];

			$data=  $this->_serv->getAssignUserOnserver($procond);
			$retarr = array();
			foreach ($data as $value){
				$retarr[$value['user_id']]=$value['user_id'];
			}
			return $retarr;

	}

	public function getServerInfoById(){
	    global $CNF, $USER;
	    //$serverid= \devlib\AppController::getKeyValueRequired ( 'serverid' );
	    $param = $this->f3->get ( 'POST' );
	    $this->view = 'serverdetail.php';

	    $param = json_decode($param['postdata'],true);

	    if(!isset($param['id'])){
	        throw  new \devlib\Exception('Parameter Not Found!','AJAX');
	    }


	    $srv = new \module\servers\lib\serverFactory($param['id']);
	    $this->form ['data'] =$srv->srvobj;


	}

}
