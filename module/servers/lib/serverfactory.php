<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      servers
 *
 */
namespace module\servers\lib;

class ServerFactory{
	private $sid;
	public $srvobj;

	public function __construct($sid){
		if(!$sid){
			throw new \devlib\Exception('Server ID is should be present for Server Factory!');
		}
		$this->sid = $sid;
		$this->_initServerObject();

	}

	/*
	 * Return Project Object Wiht Complete Detail
	 *
	 *
	 */
	public function _initServerObject(){
		// get project detail
		global $CNF, $USER;
		$server = new \module\servers\service\Server();


        $this->srvobj['sid'] = $this->sid;


        $this->srvobj['server_basic'] =$server->getServerById($this->sid);
        $this->srvobj['server_assign']= $server->getAssignUserByServerId($this->sid);
        $serverftp = new \module\servers\service\ServerFTPDetail();
        $this->srvobj['server_ftp'] =$serverftp->getFTPDetailByServerId($this->sid);
        $serverdb = new \module\servers\service\ServerDBDetail();
        $this->srvobj['server_db'] =$serverdb->getDBDetailByServerId($this->sid);;



	}
}