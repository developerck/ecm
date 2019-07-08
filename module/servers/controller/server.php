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
 * @controller  server
 */
namespace module\servers\controller;

class Server extends \module\servers\ServersController {
	protected $_serv;
	protected $_serv_srvdb;
	protected $_serv_srvftp;
	protected $_serv_pro;
	public $form;
	public $table_list_data;
	public function __construct() {
		parent::__construct ();

		$this->_serv = new \module\servers\service\Server ();
		$this->_serv_pro = new \module\projects\service\Project ();
		$this->_serv_srvdb = new \module\servers\service\ServerDBDetail ();
		$this->_serv_srvftp = new \module\servers\service\ServerFTPDetail ();
		$this->baseurl = $this->baseurl . '/server/';
		$this->basemethod = 'serverlist';
	}
	public function addserver() {
		global $CNF, $USER;

		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$procond = 'where isactive = 1 ';
		} else {
			$procond = 'where p.isactive=1 and pu.user_id = ' . $USER ['uid'];
		}
		$param = $this->f3->get ( 'POST' );

		$this->view = 'addserver.php';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$this->form ['data'] ['srvcol'] = $this->_serv->tablecol;
		$this->form ['data'] ['ftpcol'] = $this->_serv_srvftp->tablecol;
		$this->form ['data'] ['ftptypearr'] = $this->_serv_srvftp->getFTPTypeArr ();
		$this->form ['data'] ['dbcol'] = $this->_serv_srvdb->tablecol;
		$this->form ['data'] ['dbtypearr'] = $this->_serv_srvdb->getDBTypeArr ();
		$this->form ['data'] ['proarr'] = $this->_serv_pro->getProjectsArray ( $procond );
		if (isset ( $param ['btnsubmit'] )) {
			// remainining form filles incase there is an error
			assocArrayLeftMerge ( $this->form ['data'] ['srvcol'], $param );
			assocArrayLeftMerge ( $this->form ['data'] ['ftpcol'], $param );
			assocArrayLeftMerge ( $this->form ['data'] ['dbcol'], $param );
		}

		if ($this->form ['form']->validate ()) {

			$this->filterInputData ( $param );

			if ($this->checkServerName ( $param ['name'] )) {
				return $this->form ['form']->add_error ( 'error', 'Provided Server Name Already Exists!' );
			}
			// also saving role of user

			$server = array ();
			$server ['srvcol'] ['name'] = $param ['name'];
			$server ['srvcol'] ['project_id'] = $param ['project_id'];
			$server ['srvcol'] ['description'] = $param ['description'];
			$server ['srvcol'] ['isactive'] = isset ( $param ['isactive'] ) ? $param ['isactive'] : 0;
			$server ['srvcol'] ['creationtime'] = time ();
			$server ['srvcol'] ['createdby'] = $USER ['uid'];

			$server ['ftpcol'] ['ftptype'] = $param ['ftptype'];
			$server ['ftpcol'] ['ftpservername'] = $param ['ftpservername'];
			$server ['ftpcol'] ['ftpserverurl'] = $param ['ftpserverurl'];
			$server ['ftpcol'] ['ftpusername'] = $param ['ftpusername'];
			$server ['ftpcol'] ['ftppassword'] = $param ['ftppassword'];
			$server ['ftpcol'] ['ftpotherdetail'] = $param ['ftpotherdetail'];
			$server ['ftpcol'] ['creationtime'] = time ();
			$server ['ftpcol'] ['createdby'] = $USER ['uid'];

			$server ['dbcol'] ['dbtype'] = $param ['dbtype'];
			$server ['dbcol'] ['dbservername'] = $param ['dbservername'];
			$server ['dbcol'] ['dbserverurl'] = $param ['dbserverurl'];
			$server ['dbcol'] ['dbusername'] = $param ['dbusername'];
			$server ['dbcol'] ['dbpassword'] = $param ['dbpassword'];
			$server ['dbcol'] ['dbotherdetail'] = $param ['dbotherdetail'];
			$server ['dbcol'] ['creationtime'] = time ();
			$server ['dbcol'] ['createdby'] = $USER ['uid'];

			if ($this->_serv->saveServer ( $server )) {
				$this->setSessionMessage ( 'Server has added succesfully!', array (
						"viewname" => 'serverlist.php'
				) );

				$this->f3->reroute ( $CNF->wwwroot . 'servers/server/serverlist' );
			} else {
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}
	/*
	 * Update
	 */
	public function edit() {
		$editid = \devlib\AppController::getKeyValueRequired ( 'edit' );

		global $CNF, $USER;
		$this->view = 'addserver.php';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$param = $this->f3->get ( 'POST' );
		// cehck if assigned
		if (! $this->_serv->isAssigned ( $editid, $USER ['uid'] ) && $USER ['user_role'] ['shortname'] != 'ADMIN') {
			throw new \devlib\Exception ( 'Passed Server Id is not associated with you!' );
		}

		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$procond = 'where isactive = 1 ';
		} else {
			$procond = 'where p.isactive=1 and pu.user_id = ' . $USER ['uid'];
		}
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$this->form ['data'] ['srvcol'] = $this->_serv->getServerById ( $editid );
		$this->form ['data'] ['ftpcol'] = $this->_serv_srvftp->getFTPDetailByServerId ( $editid );
		$this->form ['data'] ['ftptypearr'] = $this->_serv_srvftp->getFTPTypeArr ();
		$this->form ['data'] ['dbcol'] = $this->_serv_srvdb->getDBDetailByServerId ( $editid );
		$this->form ['data'] ['dbtypearr'] = $this->_serv_srvdb->getDBTypeArr ();
		$this->form ['data'] ['proarr'] = $this->_serv_pro->getProjectsArray ( $procond );

		if (isset ( $param ['btnsubmit'] )) {
			// remainining form filles incase there is an error
			assocArrayLeftMerge ( $this->form ['data'] ['srvcol'], $param );
			assocArrayLeftMerge ( $this->form ['data'] ['ftpcol'], $param );
			assocArrayLeftMerge ( $this->form ['data'] ['dbcol'], $param );
		}
		if ($this->form ['form']->validate ()) {

			$this->filterInputData ( $param );
			$cond = " and id NOT IN (" . $param ['id'] . ")";
			if ($this->checkServerName ( $param ['name'], $cond )) {
				return $this->form ['form']->add_error ( 'error', 'Provided Server Name already Exists!' );
			}
			// also saving role of user

			$server = array ();
			$server ['srvcol'] ['name'] = $param ['name'];
			$server ['srvcol'] ['project_id'] = $param ['project_id'];
			$server ['srvcol'] ['description'] = $param ['description'];
			$server ['srvcol'] ['isactive'] = isset ( $param ['isactive'] ) ? $param ['isactive'] : 0;
			$server ['srvcol'] ['updationtime'] = time ();
			$server ['srvcol'] ['updatedby'] = $USER ['uid'];

			$server ['ftpcol'] ['ftptype'] = $param ['ftptype'];
			$server ['ftpcol'] ['ftpservername'] = $param ['ftpservername'];
			$server ['ftpcol'] ['ftpserverurl'] = $param ['ftpserverurl'];
			$server ['ftpcol'] ['ftpusername'] = $param ['ftpusername'];
			$server ['ftpcol'] ['ftppassword'] = $param ['ftppassword'];
			$server ['ftpcol'] ['ftpotherdetail'] = $param ['ftpotherdetail'];
			$server ['ftpcol'] ['updationtime'] = time ();
			$server ['ftpcol'] ['updatedby'] = $USER ['uid'];

			$server ['dbcol'] ['dbtype'] = $param ['dbtype'];
			$server ['dbcol'] ['dbservername'] = $param ['dbservername'];
			$server ['dbcol'] ['dbserverurl'] = $param ['dbserverurl'];
			$server ['dbcol'] ['dbusername'] = $param ['dbusername'];
			$server ['dbcol'] ['dbpassword'] = $param ['dbpassword'];
			$server ['dbcol'] ['dbotherdetail'] = $param ['dbotherdetail'];
			$server ['dbcol'] ['updationtime'] = time ();
			$server ['dbcol'] ['updatedby'] = $USER ['uid'];

			if ($this->_serv->updateServer ( $server, $param ['id'] )) {
				$this->setSessionMessage ( 'Server has updated succesfully!', array (
						"viewname" => 'serverlist.php'
				) );
				$this->f3->reroute ( $CNF->wwwroot . 'servers/server/serverlist' );
			} else {
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}

	/*
	 * check if useremail exist
	 */
	public function checkServerName($server, $cond = '') {
		return $this->_serv->isServerExist ( $server, $cond );
	}

	/*
	 * browser user list
	 */
	public function serverlist($paging = true) {
		global $USER;

		$this->view = 'serverlist.php';
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$cond = ' group by s.id ';
		} else {
			$cond = 'where su.user_id = ' . $USER ['uid'];
		}

		$serial_offset = 0;
		// this key name should match keyname of data record
		$header = array (
				'id' => '', // s that it would not come with header
				'projectname' => 'Project Name',
				'name' => 'Server Name',
				'description' => 'Description',
				'isactive' => 'Is Active',
				'creationtime' => 'Created On'
		);
		$orderby = ' order by isactive DESC, id DESC ';
		// parsing url and gettignpageno.

		if ($paging) {

			if ($pageno = \devlib\AppController::getKeyValue ( 'page' )) {
			} else {
				$pageno = 1;
			}
			$paging_obj = new \devlib\Pagination ( $pageno );
			$serial_offset = ( int ) $paging_obj->getOffset ( $pageno );

			$limit = ' Limit ' . $serial_offset . ', ' . ( int ) $paging_obj->getPerPage ();
			$data = $this->_serv->getServers ( $cond, $orderby, $limit );

			$this->table_list_data ['paging'] = $paging_obj->doPaging ( $data ['rowcount'] );
		} else {
			$data = $this->_serv->getServers ( $cond, $orderby );
		}

		$listformatter = new \devlib\ListGenerator ( $data ['data'], $header, array_keys ( $header ) );
		$this->table_list_data ['data'] = $listformatter->setTableArray ( $serial = true, $serial_offset );
	}
	public function assign() {
		global $CNF, $USER;
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$srvcond = 'where s.isactive = 1 ';
		} else {
			$srvcond = 'where s.isactive=1 and su.user_id = ' . $USER ['uid'];
		}
		$this->view = 'assign.php';
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$this->form ['data'] ['serverarr'] = $this->_serv->getServersArray ( $srvcond );
		$userobj = new \module\users\service\User ();
		$this->form ['data'] ['userarr'] = $userobj->getUsersArrayWithRole ();
		// $this->form ['data'] ['proarr'] = $this->_serv_pro->getProjectsArray ($procond);

		if ($this->form ['form']->validate ()) {
			$param = $this->f3->get ( 'POST' );
			$assign = array ();
			$assign ['server_id'] = $param ['server_id'];
			$assign ['users_id'] = $param ['users_id'];
			$assign ['assignedtime'] = time ();
			$assign ['assignedby'] = $USER ['uid'];

			$this->filterInputData ( $param );

			if ($this->_serv->assignServer ( $assign )) {
				$this->setSessionMessage ( 'Server has been assigned succesfully!', array (
						"viewname" => 'serverlist.php'
				) );

				$this->f3->reroute ( $CNF->wwwroot . 'servers/server/serverlist' );
			} else {
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}

	public function deploymenthistory() {
		global $CNF, $USER;
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$srvcond = 'where s.isactive = 1 ';
		} else {
			$srvcond = 'where s.isactive=1 and su.user_id = ' . $USER ['uid'];
		}
		$this->view = 'deploymenthistory.php';
		
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$this->form ['data'] ['serverarr'] = $this->_serv->getServersArray ( $srvcond );
		
		$sid = \devlib\AppController::getKeyValue ( 'id' );
		$this->form ['data'] ['server_id'] = $sid;
		if($sid !=''){
			if ($USER ['user_role'] ['shortname'] != 'ADMIN') {
				if(!$this->_serv->isAssigned($sid,$USER['uid'])){
					$this->setSessionMessage ( 'Server is not assigned to you!', array (
							"viewname" => 'deploymenthistory.php'
					) );
				
					$this->f3->reroute ( $CNF->wwwroot . 'servers/server/deploymenthistory' );
				}
			}
			// get history
			
			// this key name should match keyname of data record
		$header = array (
				'id' => '', // s that it would not come with header
				'servername' => 'Server Name',
				'projectname' => 'Project Name',
				'changelogid' => 'Issues',
				'deployedby' => 'Deployed By',
				'deploymenttime' => 'Deployed On',
				'changelogs_detail' => '',
				'project_id' => '',
				'server_id' => '',
			
		);
		$orderby = 'order by id DESC ';
		// parsing url and gettignpageno.

			$dpobj = new \module\servers\service\Deployment();

			if ($pageno = \devlib\AppController::getKeyValue ( 'page' )) {
			} else {
				$pageno = 1;
			}
			$paging_obj = new \devlib\Pagination ( $pageno );
			$serial_offset = ( int ) $paging_obj->getOffset ( $pageno );

			$limit = ' Limit ' . $serial_offset . ', ' . ( int ) $paging_obj->getPerPage ();
			$data = $dpobj->getDeploymentRecordByServer ( $sid, $orderby, $limit );

			$this->table_list_data ['paging'] = $paging_obj->doPaging ( $data ['rowcount'] );
			$listformatter = new \devlib\ListGenerator ( $data ['data'], $header, array_keys ( $header ) );
			$this->table_list_data ['data'] = $listformatter->setTableArray ( $serial = true, $serial_offset );
			
		
		}

	}

	
}
