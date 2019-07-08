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
 * @controller  releaseversion
 */
namespace module\projects\controller;

class ReleaseVersion extends \module\projects\ProjectsController
{

   	protected $_serv;
   	protected $_serv_project;
	public $form;
	public $table_list_data;
	public function __construct() {

		parent::__construct ();

		$this->_serv = new \module\projects\service\ReleaseVersion();
		$this->_serv_project = new \module\projects\service\Project();
		$this->baseurl = $this->baseurl . '/releaseversion/';
		$this->basemethod = 'rvlist';
	}


	public function add() {
		global $CNF, $USER;

		$this->view = 'add.php';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$param = $this->f3->get ( 'POST' );
		$this->form ['data']['tablecol'] = $this->_serv->tablecol;
		if (isset ( $param ['btnsubmit'] )) {
		    // remainining form filles incase there is an error
		    assocArrayLeftMerge ( $this->form ['data'] ['tablecol'], $param );

		}
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$cond = 'where isactive = 1 ';
		} else {
			$cond = 'where p.isactive=1 and pu.user_id = ' . $USER ['uid'];
		}
		$this->form ['data']['projectarr'] = $this->_serv_project->getProjectsArray($cond);
		if ($this->form ['form']->validate ()) {


			$this->filterInputData ( $param );

			if ($this->checkRVName( $param ['project_id'],$param ['rvname'],$param ['rcname'] )) {
				$this->form ['data'] ['tablecol']['islocked'] =0;
				return $this->form ['form']->add_error ( 'error', 'Provided Release Version Already Exists with this project!' );
			}
			// also saving role of user

			$savearr = array ();
			$savearr ['project_id'] = $param ['project_id'];
			$savearr ['rvname'] = $param ['rvname'];
			$savearr ['rcname'] = $param ['rcname'];
			$savearr ['rvname'] = $param ['rvname'];
			$savearr ['description'] = $param ['description'];
			$savearr ['creationtime'] = time ();
			$savearr ['createdby'] = $USER['uid'];


			if ($this->_serv->save( $savearr )) {
				$this->setSessionMessage ( 'Release Version has added succesfully!', array (
						"viewname" => 'rvlist.php'
				) );

				$this->f3->reroute ( $CNF->wwwroot . 'projects/releaseversion/rvlist' );
			} else {
				$this->form ['data'] ['tablecol']['islocked'] =0;
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}
	/*
	 * Update
	 *
	 */
	public function edit() {
		$editid = \devlib\AppController::getKeyValueRequired ( 'edit' );

		global $CNF,$USER;
		$this->view = 'add.php';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$param = $this->f3->get ( 'POST' );
		$this->form ['data'] ['tablecol'] = $this->_serv->getRVById ( $editid );
		if (isset ( $param ['btnsubmit'] )) {
		    // remainining form filles incase there is an error
		    assocArrayLeftMerge ( $this->form ['data'] ['tablecol'], $param );
		}
		$this->form ['data']['projectarr'] = $this->_serv_project->getProjectsArray();
		if ($this->form ['form']->validate ()) {
			

			$this->filterInputData ( $param );
			$updatearr = array ();
			if(!$this->form ['data'] ['tablecol']['islocked']){
			$cond = " and id NOT IN (" . $param ['id'] . ")";
			if ($this->checkRVName ( $param ['project_id'],$param ['rvname'],$param ['rcname'] , $cond )) {
				$this->form ['data'] ['tablecol']['islocked'] =0;
				return $this->form ['form']->add_error ( 'error', 'Provided Project Name already Exists!' );
			}
			// also saving role of user

			
			$updatearr ['project_id'] = $param ['project_id'];
			$updatearr ['rvname'] = $param ['rvname'];
			$updatearr ['rcname'] = $param ['rcname'];
			$updatearr ['rvname'] = $param ['rvname'];
			}
			$updatearr ['description'] = $param ['description'];
			$updatearr ['updationtime'] = time ();
			$updatearr ['updatedby'] = $USER['uid'];
			$updatearr['islocked'] = isset ( $param ['islocked'] ) ? $param ['islocked'] : 0;
			if($updatearr['islocked']){
				$this->_serv->lockAllChangeLogByRVID($param['id']);
				$updatearr['lockedtime'] = time();
			}

			if ($this->_serv->update( $updatearr, $param ['id'] )) {
				$this->setSessionMessage ( 'Release Version has updated succesfully!', array (
						"viewname" => 'rvlist.php'
				) );
				$this->f3->reroute ( $CNF->wwwroot . 'projects/releaseversion/rvlist' );
			} else {
				$this->form ['data'] ['tablecol']['islocked'] =0;
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}


	public function delete() {
		$delid = \devlib\AppController::getKeyValueRequired ( 'delete' );

		global $CNF,$USER;
        	$changelog = new \module\projects\service\Changelog();
            if(!$changelog->checkChangelogExistsByRV($delid) 
            	&& !$this->_serv->isLocked($delid)
				){

			if ($this->_serv->delete( $delid)) {
				$this->setSessionMessage ( 'Release Version has deleted succesfully!', array (
						"viewname" => 'rvlist.php'
				),'warning' );
				$this->f3->reroute ( $CNF->wwwroot . 'projects/releaseversion/rvlist' );
			} else {
					$this->setSessionMessage ( 'Release Version could not deleted !', array (
						"viewname" => 'rvlist.php'
				),'danger' );
                return;
			}
		}else{
		  	$this->setSessionMessage ( 'It can not be delete because either changelog exists or release version is locked!', array (
						"viewname" => 'rvlist.php'
				) ,'danger');
                return;
		}
	}
	/*
	 * check if useremail exist
	 *
	 */
	public function checkRVName($project,$rvname,$rcname, $cond = '') {
		return $this->_serv->isRVExist ( $project,$rvname,$rcname, $cond );
	}

	/*
	 *  browser  list
	 *
	 */
	public function rvlist($paging = true) {
		global $CNF, $USER;
		$searchcond ='';
		$this->view = 'rvlist.php';
		$cond= '';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
	 	 $sort = \devlib\AppController::getKeyValue ( 'sort' );
         $dir = \devlib\AppController::getKeyValue ( 'dir' );
         	if($sort !='id'){
             	$sort= 'id';
         	}
         	if($dir !='asc' && $dir!='desc'){
         		$dir= 'asc';
         	}
         	if($dir=='asc'){
         		$adir ='desc';
         	}else{
         		$adir ='asc';
         	}
         	$sortarr = array();
         	$sortarr['sort'] = $sort;
         	$sortarr['dir'] = $dir;
         	$sortarr['adir'] = $adir;
         	$this->table_list_data['sortarr'] = $sortarr;
         	$orderby = 'ORDER BY '.$sort." ".$dir;
		if ($this->form ['form']->validate ()) {
		    $param = $this->f3->get ( 'POST' );

		    if (isset ( $param ['btnsubmit'] )) {
		        $searcharr = array ();
		        $searcharr ['project_id'] = $param ['project_id'];
		        $searcharr ['rvname'] = $param ['rvname'];

		        $this->f3->set ( 'SESSION.search.rv.rvlist', $searcharr );
		    } else if (isset ( $param ['btnreset'] )) {
		        $searcharr = array ();
		        $this->f3->set ( 'SESSION.search.rv.rvlist', $searcharr );
		    }
		}
		$sessionsearch = $this->f3->get ( 'SESSION.search.rv.rvlist' );

		if (isset ( $sessionsearch ) && !empty($sessionsearch)) {
		    $searcharr = $sessionsearch;
		    $searchcond = array ();
		    if ($searcharr ['project_id'] != '') {
		        $searchcond [] = " (project_id =" . $searcharr ['project_id'] . ")";
		    }
		    if ($searcharr ['rvname']) {
		        $searchcond [] = " (rvname like '%" . $searcharr ['rvname']."%' or rcname like '%" . $searcharr ['rvname']."%' )";
		    }
		    if (! empty ( $searchcond )) {
		        $searchcond  = implode ( " AND ", $searchcond );
		        $searchcond  = ' AND '.$searchcond;

		    }
		} else {
			$searchcond='';
		    $searcharr = array ();
		    $searcharr ['project_id'] = '';
		    $searcharr ['rvname'] = '';

		}

		$this->form ['data'] ['searcharr'] = $searcharr;
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
		    $procond = '';
		} else {
		    $procond = 'where pu.user_id = ' . $USER ['uid'];
		}
		$this->form ['data']['projectarr'] = $this->_serv_project->getProjectsArray($procond);
		$assignprojectarr = $this->_serv_project->getProjectsArray($procond);
		$assignprojectarrstr = implode(",",array_keys($assignprojectarr));



		$serial_offset = 0;
		// this key name should match keyname of data record
		$header = array (
				'id' => '', // s that it would not come with header
				'project_name' => 'Project',
				'rvname' => 'Release Version',
				'rcname' => 'RC Name',
				'description' => 'Description',
				'islocked' => 'Is Locked',
				'lockedtime' => 'Locked On',
                'creationtime' => 'Created On',
		)
		;
        $orderby = 'order by creationtime DESC ';
		// parsing url and gettignpageno.

		if ($paging) {
			$cond = ' where project_id IN ('.$assignprojectarrstr.')'.$searchcond ;
			if ($pageno = \devlib\AppController::getKeyValue ( 'page' )) {
			} else {
				$pageno = 1;
			}
			$paging_obj = new \devlib\Pagination ( $pageno);
			$serial_offset = ( int ) $paging_obj->getOffset ( $pageno );

			$limit = ' Limit ' . $serial_offset . ', ' . ( int ) $paging_obj->getPerPage ();
			$data = $this->_serv->getRV ( $cond, $orderby, $limit );

			$this->table_list_data ['paging'] = $paging_obj->doPaging ( $data ['rowcount'] );
		} else {
			$data = $this->_serv->getRV( $cond, $orderby  );
		}

		$listformatter = new \devlib\ListGenerator ( $data ['data'], $header, array_keys ( $header ) );
		$this->table_list_data ['data'] = $listformatter->setTableArray ( $serial = true, $serial_offset );
	}


}
