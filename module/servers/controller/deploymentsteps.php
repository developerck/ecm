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
 * @controller  deploymentsteps
 */
namespace module\servers\controller;

class DeploymentSteps extends \module\servers\ServersController
{

   	protected $_serv;
	protected $_serv_server;
	public $form;
	public $table_list_data;
	public function __construct() {

		parent::__construct ();

		$this->_serv = new \module\servers\service\Deployment();
		$this->_serv_server = new \module\servers\service\Server();

		$this->baseurl = $this->baseurl . '/deploymentsteps/';
		$this->basemethod = 'base';
	}


	public function base() {
		throw new \devlib\Exception('No Base Action is Defined for this controller!');
	}

	public function customizesteps(){
		
		global $CNF, $USER;
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$srvcond = 'where s.isactive = 1 ';
		} else {
			$srvcond= 'where s.isactive=1 and su.user_id = ' . $USER ['uid'];
		}
		$this->view = 'customizesteps.php';
		$this->form ['form'] = new \Zebra_Form ( 'form');
		$this->form ['data'] ['serverarr'] = $this->_serv_server->getServersArray ($srvcond);
				
	}
	
	


}
