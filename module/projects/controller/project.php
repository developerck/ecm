<?php

/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      projects
 * @controller  project
 */
namespace module\projects\controller;

class Project extends \module\projects\ProjectsController {
	protected $_serv;
	protected $_serv_prodb;
	protected $_serv_proscm;
	public $form;
	public $table_list_data;
	public function __construct() {
		parent::__construct ();

		$this->_serv = new \module\projects\service\Project ();
		$this->_serv_prodb = new \module\projects\service\ProjectDBDetail ();
		$this->_serv_proscm = new \module\projects\service\ProjectSCMDetail ();
		$this->baseurl = $this->baseurl . '/project/';
		$this->basemethod = 'projectlist';
	}
	public function addproject() {
		global $CNF, $USER;

		$this->view = 'addproject.php';
		$param = $this->f3->get ( 'POST' );

		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$this->form ['data'] ['procol'] = $this->_serv->tablecol;
		$this->form ['data'] ['scmcol'] = $this->_serv_proscm->tablecol;
		$this->form ['data'] ['scmtypearr'] = $this->_serv_proscm->getSCMTypeArr ();
		$this->form ['data'] ['dbcol'] = $this->_serv_prodb->tablecol;
		$this->form ['data'] ['dbtypearr'] = $this->_serv_prodb->getDBTypeArr ();

		if (isset ( $param ['btnsubmit'] )) {
		    // remainining form filles incase there is an error
		    assocArrayLeftMerge ( $this->form ['data'] ['procol'], $param );
		    assocArrayLeftMerge ( $this->form ['data'] ['scmcol'], $param );
		    assocArrayLeftMerge ( $this->form ['data'] ['dbcol'], $param );
		}
		if ($this->form ['form']->validate ()) {

			$this->filterInputData ( $param );

			if ($this->checkProjectName ( $param ['name'] )) {
				return $this->form ['form']->add_error ( 'error', 'Provided Project Name Already Exists!' );
			}
			// also saving role of user

			$project = array ();
			$project['pro']['name'] = $param ['name'];
			$project['pro'] ['description'] = $param ['description'];
			$project['pro'] ['isactive'] = isset ( $param ['isactive'] ) ? $param ['isactive'] : 0;
			$project['pro'] ['creationtime'] = time ();
			$project['pro'] ['createdby'] = $USER ['uid'];

			$project['scm']['scmtype'] = $param ['scmtype'];
			$project['scm'] ['secmervername'] = $param ['secmervername'];
			$project['scm'] ['secmerverurl'] = $param ['secmerverurl'];
			$project['scm'] ['scmusername'] = $param ['scmusername'];
			$project['scm'] ['scmpassword'] = $param ['scmpassword'];
			$project['scm'] ['scmotherdetail'] = $param ['scmotherdetail'];
			$project['scm'] ['creationtime'] = time ();
			$project['scm'] ['createdby'] = $USER ['uid'];

			$project['db']['dbtype'] = $param ['dbtype'];
			$project['db'] ['dbservername'] = $param ['dbservername'];
			$project['db'] ['dbserverurl'] = $param ['dbserverurl'];
			$project['db'] ['dbusername'] = $param ['dbusername'];
			$project['db'] ['dbpassword'] = $param ['dbpassword'];
			$project['db'] ['dbotherdetail'] = $param ['dbotherdetail'];
			$project['db'] ['creationtime'] = time ();
			$project['db'] ['createdby'] = $USER ['uid'];

			if ($this->_serv->saveProject ( $project )) {
				$this->setSessionMessage ( 'Project has added succesfully!', array (
						"viewname" => 'projectlist.php'
				) );

				$this->f3->reroute ( $CNF->wwwroot . 'projects/project/projectlist' );
			} else {
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}
	/*
	 * Update User Profile
	 */
	public function edit() {
		$editid = \devlib\AppController::getKeyValueRequired ( 'edit' );

		global $CNF, $USER;
		$this->view = 'addproject.php';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		//cehck if assigned
		if(!$this->_serv->isAssigned($editid,$USER['uid']) && $USER['user_role']['shortname'] !='ADMIN'){
			throw new \devlib\Exception('Passed Project Id is not associated with you!');
		}

		$param = $this->f3->get ( 'POST' );

		$this->form ['data'] ['procol'] = $this->_serv->getProjectById($editid);
		$this->form ['data'] ['scmcol'] = $this->_serv_proscm->getSCMDetailByProjectId($editid);
		$this->form ['data'] ['scmtypearr'] = $this->_serv_proscm->getSCMTypeArr ();
		$this->form ['data'] ['dbcol'] = $this->_serv_prodb->getDBDetailByProjectId($editid);
		$this->form ['data'] ['dbtypearr'] = $this->_serv_prodb->getDBTypeArr ();

		if (isset ( $param ['btnsubmit'] )) {
		    // remainining form filles incase there is an error
		    assocArrayLeftMerge ( $this->form ['data'] ['procol'], $param );
		    assocArrayLeftMerge ( $this->form ['data'] ['scmcol'], $param );
		    assocArrayLeftMerge ( $this->form ['data'] ['dbcol'], $param );
		}
		if ($this->form ['form']->validate ()) {
			$param = $this->f3->get ( 'POST' );

			$this->filterInputData ( $param );
			$cond = " and id NOT IN (" . $param ['id'] . ")";
			if ($this->checkProjectName ( $param ['name'], $cond )) {
				return $this->form ['form']->add_error ( 'error', 'Provided Project Name already Exists!' );
			}
			// also saving role of user

			$project = array ();
			$project = array ();
			$project['pro']['name'] = $param ['name'];
			$project['pro'] ['description'] = $param ['description'];
			$project['pro'] ['isactive'] = isset ( $param ['isactive'] ) ? $param ['isactive'] : 0;
			$project ['pro'] ['updationtime'] = time ();
			$project ['pro'] ['updatedby'] = $USER ['uid'];

			$project['scm']['scmtype'] = $param ['scmtype'];
			$project['scm'] ['secmervername'] = $param ['secmervername'];
			$project['scm'] ['secmerverurl'] = $param ['secmerverurl'];
			$project['scm'] ['scmusername'] = $param ['scmusername'];
			$project['scm'] ['scmpassword'] = $param ['scmpassword'];
			$project['scm'] ['scmotherdetail'] = $param ['scmotherdetail'];
			$project['scm'] ['updationtime'] = time ();
			$project['scm'] ['updatedby'] = $USER ['uid'];

			$project['db']['dbtype'] = $param ['dbtype'];
			$project['db'] ['dbservername'] = $param ['dbservername'];
			$project['db'] ['dbserverurl'] = $param ['dbserverurl'];
			$project['db'] ['dbusername'] = $param ['dbusername'];
			$project['db'] ['dbpassword'] = $param ['dbpassword'];
			$project['db'] ['dbotherdetail'] = $param ['dbotherdetail'];
			$project['db']  ['updationtime'] = time ();
			$project ['db'] ['updatedby'] = $USER ['uid'];


			if ($this->_serv->updateProject ( $project, $param ['id'] )) {
				$this->setSessionMessage ( 'Project has updated succesfully!', array (
						"viewname" => 'projectlist.php'
				) );
				$this->f3->reroute ( $CNF->wwwroot . 'projects/project/projectlist' );
			} else {
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}

	/*
	 * check if useremail exist
	 */
	public function checkProjectName($project, $cond = '') {
		return $this->_serv->isProjectExist ( $project, $cond );
	}

	/*
	 * browser user list
	 */
	public function projectlist($paging = true) {
		global $USER;

		$this->view = 'projectlist.php';
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$cond = ' group by p.id ';
		} else {
			$cond = 'where pu.user_id = ' . $USER ['uid'];
		}

		$serial_offset = 0;
		// this key name should match keyname of data record
		$header = array (
				'id' => '', // s that it would not come with header
				'name' => 'Name',
				'description' => 'Description',
				'isactive' => 'Is Active',
				'creationtime' => 'Created On'
		);
		$orderby = 'order by isactive DESC,id DESC ';
		// parsing url and gettignpageno.

		if ($paging) {

			if ($pageno = \devlib\AppController::getKeyValue ( 'page' )) {
			} else {
				$pageno = 1;
			}
			$paging_obj = new \devlib\Pagination ( $pageno);
			$serial_offset = ( int ) $paging_obj->getOffset ( $pageno );

			$limit = ' Limit ' . $serial_offset . ', ' . ( int ) $paging_obj->getPerPage ();
			$data = $this->_serv->getProjects ( $cond, $orderby, $limit );

			$this->table_list_data ['paging'] = $paging_obj->doPaging ( $data ['rowcount'] );
		} else {
			$data = $this->_serv->getProjects ( $cond, $orderby );
		}

		$listformatter = new \devlib\ListGenerator ( $data ['data'], $header, array_keys ( $header ) );
		$this->table_list_data ['data'] = $listformatter->setTableArray ( $serial = true, $serial_offset );
	}

	public function assign() {
		global $CNF, $USER;
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$procond = 'where p.isactive = 1 ';
		} else {
			$procond= 'where p.isactive=1 and pu.user_id = ' . $USER ['uid'];
		}
		$this->view = 'assign.php';
		$this->form ['form'] = new \Zebra_Form ( 'form');
		$this->form ['data'] ['projectarr'] = $this->_serv->getProjectsArray ($procond);
		$userobj = new \module\users\service\User ();
		$this->form ['data'] ['userarr'] = $userobj->getUsersArrayWithRole ();


		if ($this->form ['form']->validate ()) {
			$param = $this->f3->get ( 'POST' );
			$assign = array();
			$assign['project_id'] =$param ['project_id'];
			$assign['users_id'] =$param ['users_id'];
			$assign ['assignedtime'] = time ();
			$assign['assignedby'] = $USER['uid'];

			$this->filterInputData ( $param );


			if ($this->_serv->assignProject ( $assign )) {
				$this->setSessionMessage ( 'Project has been assigned succesfully!', array (
						"viewname" => 'projectlist.php'
				) );

				$this->f3->reroute ( $CNF->wwwroot . 'projects/project/projectlist' );
			} else {
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}
}
