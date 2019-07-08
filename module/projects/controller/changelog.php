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
 * @controller  changelog
 */
namespace module\projects\controller;

class Changelog extends \module\projects\ProjectsController {
	protected $_serv;
	protected $_serv_project;
	protected $_serv_rv;
	public $form;
	public $table_list_data;
	public function __construct() {
		parent::__construct ();

		$this->_serv = new \module\projects\service\Changelog ();
		$this->_serv_project = new \module\projects\service\Project ();
		$this->_serv_rv = new \module\projects\service\ReleaseVersion ();
		$this->baseurl = $this->baseurl . '/changelog/';
		$this->basemethod = 'browse';
	}
	public function add() {
		global $CNF, $USER;

		$this->view = 'add.php';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$param = $this->f3->get ( 'POST' );
		$this->form ['data'] ['tablecol'] = $this->_serv->tablecol;

		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$cond = 'where isactive = 1 ';
		} else {
			$cond = 'where p.isactive=1 and pu.user_id = ' . $USER ['uid'];
		}
		$this->form ['data'] ['projectarr'] = $this->_serv_project->getProjectsArray ( $cond );
		$this->form ['data'] ['rvarr'] = array ();
		
		$this->form ['data'] ['labelarr'] = array ();
		if (isset ( $param ['btnsubmit'] )) {
			// remainining form filles incase there is an error
			assocArrayLeftMerge ( $this->form ['data'] ['tablecol'], $param );
		}
		if($this->form ['data'] ['tablecol'] ['project_id']){
			$rvcond = " where project_id = " . $this->form ['data'] ['tablecol'] ['project_id'];
			$this->form ['data'] ['rvarr'] = $this->_serv_rv->getReleaseVersionArr ( $rvcond );
			$this->form ['data'] ['labelarr'] = $this->_serv->getChangelogLabelArrByProjectId ( $this->form ['data'] ['tablecol'] ['project_id'] );
		}
		if ($this->form ['form']->validate ()) {
			$param = $this->f3->get ( 'POST' );

			$this->filterInputData ( $param );
			$cond = " where issueid = '" . $param ['issueid'] . "' and releaseversion_id=" . $param ['releaseversion_id'] . " and project_id = " . $param ['project_id'];
			if ($this->checkIssueID ( $cond )) {
				return $this->form ['form']->add_error ( 'error', 'Provided Issue Id Already Exist For This Release Version !' );
			}
			// also saving role of user

			$changelog = array ();
			$changelog ['project_id'] = $param ['project_id'];
			$changelog ['releaseversion_id'] = $param ['releaseversion_id'];
			$changelog ['issueid'] = $param ['issueid'];
			$changelog ['islocked'] = isset ( $param ['islocked'] ) ? $param ['islocked'] : 0;
			if ($changelog ['islocked']) {
				$changelog ['lockedtime'] = time ();
			}

			$changelog ['filelog'] = $param ['filelog'];
			$changelog ['scriptlog'] = $param ['scriptlog'];
			$changelog ['settings'] = $param ['settings'];
			$changelog ['comment'] = $param ['comment'];
			$changelog ['labelname'] = $param ['labelname'];
			$changelog ['creationtime'] = time ();
			$changelog ['createdby'] = $USER ['uid'];

			if ($this->_serv->save ( $changelog )) {
				$this->setSessionMessage ( 'Changelog has added succesfully!', array (
						"viewname" => 'browse.php'
				) );

				$this->f3->reroute ( $CNF->wwwroot . 'projects/changelog/browse' );
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
		$this->view = 'add.php';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$param = $this->f3->get ( 'POST' );

		$this->form ['data'] ['tablecol'] = $this->_serv->getChangelogById ( $editid );
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$procond = ' where isactive = 1 ';
		} else {
			$procond = ' where p.isactive=1 and pu.user_id = ' . $USER ['uid'];
		}
		$this->form ['data'] ['projectarr'] = $this->_serv_project->getProjectsArray ( $procond );
		$rvcond = " where project_id = " . $this->form ['data'] ['tablecol'] ['project_id'];
		$this->form ['data'] ['rvarr'] = $this->_serv_rv->getReleaseVersionArr ( $rvcond );
		$this->form ['data'] ['labelarr'] = $this->_serv->getChangelogLabelArrByProjectId ( $this->form ['data'] ['tablecol'] ['project_id'] );
		if (isset ( $param ['btnsubmit'] )) {
			// remainining form filles incase there is an error
			assocArrayLeftMerge ( $this->form ['data'] ['tablecol'], $param );
		}

		if ($this->form ['form']->validate ()) {
			$changelog = array ();

			$this->filterInputData ( $param );
			if (! $this->form ['data'] ['tablecol'] ['islocked']) {

				$cond = " where issueid = '" . $param ['issueid'] . "' and releaseversion_id=" . $param ['releaseversion_id'] . " and project_id = " . $param ['project_id'] . " and id NOT IN (" . $param ['id'] . ")";
				if ($this->checkIssueID ( $cond )) {
					$this->form ['data'] ['tablecol']['islocked'] =0;
					return $this->form ['form']->add_error ( 'error', 'Provided Issue Id Already Exist For This Release Version !' );
				}

				$changelog ['project_id'] = $param ['project_id'];
				$changelog ['releaseversion_id'] = $param ['releaseversion_id'];
				$changelog ['issueid'] = $param ['issueid'];
				$changelog ['islocked'] = isset ( $param ['islocked'] ) ? $param ['islocked'] : 0;
				if ($changelog ['islocked']) {
					$changelog ['lockedtime'] = time ();
				}

				$changelog ['filelog'] = $param ['filelog'];
				$changelog ['scriptlog'] = $param ['scriptlog'];
				$changelog ['settings'] = $param ['settings'];
				$changelog ['labelname'] = $param ['labelname'];
			}
			$changelog ['comment'] = $param ['comment'];
			$changelog ['updationtime'] = time ();
			$changelog ['updatedby'] = $USER ['uid'];

			if ($this->_serv->update ( $changelog, $param ['id'] )) {
				$this->setSessionMessage ( 'Changelog has updated succesfully!', array (
						"viewname" => 'browse.php'
				) );

				$this->f3->reroute ( $CNF->wwwroot . 'projects/changelog/browse' );
			} else {
				$this->form ['data'] ['tablecol']['islocked'] =0;
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}

	/*
	 * check if useremail exist
	 */
	public function checkIssueID($cond) {
		return $this->_serv->isChangelogExist ( $cond );
	}

	/*
	 * browser user list
	 */
	public function browse($paging = true) {
		global $CNF, $USER;
		$searchcond = '';
		$this->view = 'browse.php';
		$cond = '';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$sort = \devlib\AppController::getKeyValue ( 'sort' );
		$dir = \devlib\AppController::getKeyValue ( 'dir' );
		if ($sort != 'id') {
			$sort = 'id';
		}
		if ($dir != 'asc' && $dir != 'desc') {
			$dir = 'desc';
		}
		if ($dir == 'asc') {
			$adir = 'desc';
		} else {
			$adir = 'asc';
		}
		$sortarr = array ();
		$sortarr ['sort'] = $sort;
		$sortarr ['dir'] = $dir;
		$sortarr ['adir'] = $adir;
		$this->table_list_data ['sortarr'] = $sortarr;
		$orderby = 'ORDER BY ' . $sort . " " . $dir;
		if ($this->form ['form']->validate ()) {
			$param = $this->f3->get ( 'POST' );

			if (isset ( $param ['btnsubmit'] )) {
				$searcharr = array ();
				$searcharr ['project_id'] = $param ['project_id'];
				$searcharr ['releaseversion_id'] =  isset($param ['releaseversion_id'])?$param ['releaseversion_id']:'';
				$searcharr ['islocked'] = isset ( $param ['islocked'] ) ? $param ['islocked'] : 0;

				$searcharr ['issueid'] = $param ['issueid'];
				$searcharr ['labelname'] = $param ['labelname'];

				$this->f3->set ( 'SESSION.search.changelog.browse', $searcharr );
			} else if (isset ( $param ['btnreset'] )) {
				$searcharr = array ();
				$this->f3->set ( 'SESSION.search.changelog.browse', $searcharr );
			}
		}
		$sessionsearch = $this->f3->get ( 'SESSION.search.changelog.browse' );

		if (isset ( $sessionsearch ) && ! empty ( $sessionsearch )) {
			$searcharr = $sessionsearch;
			$searchcond = array ();
			if ($searcharr ['project_id'] != '') {
				$searchcond [] = "c.project_id =" . $searcharr ['project_id'] . "";
			}
			// case of multiple
			if (! empty ( $searcharr ['releaseversion_id'] ) && is_array ( $searcharr ['releaseversion_id'] )) {
				$searchcond [] = " (c.releaseversion_id  IN (" . implode ( ",", $searcharr ['releaseversion_id'] ) . ") ) ";
			}
			if ($searcharr ['islocked']) {
				$searchcond [] = " (c.islocked = " . $searcharr ['islocked']." )";
			}
			if ($searcharr ['issueid']) {
				$searchcond [] = " (c.issueid like '%" . $searcharr ['issueid'] . "%' )";
			}
			if ($searcharr ['labelname']) {
				$searchcond [] = "( c.labelname like '%" . $searcharr ['labelname'] . "%' )";
			}
			if (! empty ( $searchcond )) {
				$searchcond = implode ( " AND ", $searchcond );
				$searchcond = ' AND ' . $searchcond;
			}
		} else {
			$searchcond = '';
			$searcharr = array ();
			$searcharr ['project_id'] = '';
			$searcharr ['releaseversion_id'] = '';
			$searcharr ['issueid'] = '';
			$searcharr ['islocked'] = '';
			$searcharr ['labelname'] = '';
		}

		$this->form ['data'] ['searcharr'] = $searcharr;
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$procond = '';
		} else {
			$procond = 'where pu.user_id = ' . $USER ['uid'];
		}
		$this->form ['data'] ['projectarr'] = $this->_serv_project->getProjectsArray ( $procond );
		if ($searcharr ['project_id']) {
			$rvcond = " where project_id = " . $searcharr ['project_id'];
			$this->form ['data'] ['rvarr'] = $this->_serv_rv->getReleaseVersionArr ( $rvcond );
		} else {
			$this->form ['data'] ['rvarr'] = array ();
		}

		$assignprojectarr = $this->_serv_project->getProjectsArray ( $procond );
		$assignprojectarrstr = implode ( ",", array_keys ( $assignprojectarr ) );
		$serial_offset = 0;
		// this key name should match keyname of data record
		$header = array (
				'id' => '', // s that it would not come with header
				'projectname' => 'Project Name',
				'rvname' => 'ReleaseVersion',
				'issueid' => 'Issue ID',
				'labelname' => 'Label',
				'islocked' => 'Is Locked',
				'lockedtime' => '',
				'creationtime' => 'Created On'
		);

		// parsing url and gettignpageno.
		if (! empty ( $assignprojectarr )) {
			$cond = ' where c.project_id IN (' . $assignprojectarrstr . ')' . $searchcond;
			$this->table_list_data ['paging'] = '';
			if ($paging) {

				if ($pageno = \devlib\AppController::getKeyValue ( 'page' )) {
				} else {
					$pageno = 1;
				}
				$paging_obj = new \devlib\Pagination ( $pageno );
				$serial_offset = ( int ) $paging_obj->getOffset ( $pageno );

				$limit = ' Limit ' . $serial_offset . ', ' . ( int ) $paging_obj->getPerPage ();
				$data = $this->_serv->getChangelogs ( $cond, $orderby, $limit );

				$this->table_list_data ['paging'] = $paging_obj->doPaging ( $data ['rowcount'] );
			} else {
				$data = $this->_serv->getChangelogs ( $cond, $orderby );
			}
		} else {
			$data ['data'] = array ();
		}
		$listformatter = new \devlib\ListGenerator ( $data ['data'], $header, array_keys ( $header ) );
		$this->table_list_data ['data'] = $listformatter->setTableArray ( $serial = true, $serial_offset );
	}
	public function export($paging = true) {
		global $CNF, $USER;
		$searchcond = '';
		$this->view = 'export.php';
		$cond = '';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$sort = \devlib\AppController::getKeyValue ( 'sort' );
		$dir = \devlib\AppController::getKeyValue ( 'dir' );
		if ($sort != 'id') {
			$sort = 'id';
		}
		if ($dir != 'asc' && $dir != 'desc') {
			$dir = 'asc';
		}
		if ($dir == 'asc') {
			$adir = 'desc';
		} else {
			$adir = 'asc';
		}
		$sortarr = array ();
		$sortarr ['sort'] = $sort;
		$sortarr ['dir'] = $dir;
		$sortarr ['adir'] = $adir;
		$this->table_list_data ['sortarr'] = $sortarr;
		$orderby = 'ORDER BY ' . $sort . " " . $dir;
		if ($this->form ['form']->validate ()) {
			$param = $this->f3->get ( 'POST' );

			if (isset ( $param ['btnsubmit'] )) {
				$searcharr = array ();
				$searcharr ['project_id'] = $param ['project_id'];
				$searcharr ['releaseversion_id'] =  isset($param ['releaseversion_id'])?$param ['releaseversion_id']:'';
				$searcharr ['labelname'] = $param ['labelname'];
				$searcharr ['issueid'] = $param ['issueid'];

				$this->f3->set ( 'SESSION.search.changelog.export', $searcharr );
			} else if (isset ( $param ['btnreset'] )) {
				$searcharr = array ();
				$this->f3->set ( 'SESSION.search.changelog.export', $searcharr );
			}
		}
		$sessionsearch = $this->f3->get ( 'SESSION.search.changelog.export' );

		if (isset ( $sessionsearch ) && ! empty ( $sessionsearch )) {
			$searcharr = $sessionsearch;
			$searchcond = array ();
			if ($searcharr ['project_id'] != '') {
				$searchcond [] = " (c.project_id =" . $searcharr ['project_id'] . ")";
			}
			// case of multiple
			if (! empty ( $searcharr ['releaseversion_id'] ) && is_array ( $searcharr ['releaseversion_id'] )) {
				$searchcond [] = "( c.releaseversion_id  IN (" . implode ( ",", $searcharr ['releaseversion_id'] ) . ") )";
			}

			if ($searcharr ['issueid']) {
				$searchcond [] = " (c.issueid like '%" . $searcharr ['issueid'] . "%' )";
			}
			if ($searcharr ['labelname']) {
				$searchcond [] = "( c.labelname like '%" . $searcharr ['labelname'] . "%' )";
			}
			if (! empty ( $searchcond )) {
				$searchcond = implode ( " AND ", $searchcond );
				$searchcond = ' AND ' . $searchcond;
			}
		} else {
			$searchcond = '';
			$searcharr = array ();
			$searcharr ['project_id'] = '';
			$searcharr ['releaseversion_id'] = '';
			$searcharr ['issueid'] = '';
			$searcharr ['labelname'] = '';
		}

		$this->form ['data'] ['searcharr'] = $searcharr;
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$procond = '';
		} else {
			$procond = 'where pu.user_id = ' . $USER ['uid'];
		}
		$this->form ['data'] ['projectarr'] = $this->_serv_project->getProjectsArray ( $procond );
		if ($searcharr ['project_id']) {
			$rvcond = " where project_id = " . $searcharr ['project_id'];
			$this->form ['data'] ['rvarr'] = $this->_serv_rv->getReleaseVersionArr ( $rvcond );
		} else {
			$this->form ['data'] ['rvarr'] = array ();
		}

		$assignprojectarr = $this->_serv_project->getProjectsArray ( $procond );
		$assignprojectarrstr = implode ( ",", array_keys ( $assignprojectarr ) );
		$serial_offset = 0;
		// this key name should match keyname of data record
		$header = array (
				'id' => '', // s that it would not come with header
				'projectname' => 'Project Name',
				'rvname' => 'ReleaseVersion',
				'issueid' => 'Issue ID',
				'labelname' => 'Label',
				'islocked' => 'Is Locked',
				'lockedtime' => '',
				'creationtime' => 'Created On'
		);

		// parsing url and gettignpageno.
		if (! empty ( $assignprojectarr )) {
			$cond = ' where c.islocked=1 and  c.project_id IN (' . $assignprojectarrstr . ')' . $searchcond;
			$this->table_list_data ['paging'] = '';
			if ($paging) {

				if ($pageno = \devlib\AppController::getKeyValue ( 'page' )) {
				} else {
					$pageno = 1;
				}
				$paging_obj = new \devlib\Pagination ( $pageno);
				$serial_offset = ( int ) $paging_obj->getOffset ( $pageno );

				$limit = ' Limit ' . $serial_offset . ', ' . ( int ) $paging_obj->getPerPage ();
				$data = $this->_serv->getChangelogs ( $cond, $orderby, $limit );

				$this->table_list_data ['paging'] = $paging_obj->doPaging ( $data ['rowcount'] );
			} else {
				$data = $this->_serv->getChangelogs ( $cond, $orderby );
			}
		} else {
			$data ['data'] = array ();
		}
		$listformatter = new \devlib\ListGenerator ( $data ['data'], $header, array_keys ( $header ) );
		$this->table_list_data ['data'] = $listformatter->setTableArray ( $serial = true, $serial_offset );
	}
	public function delete() {
		$delid = \devlib\AppController::getKeyValueRequired ( 'delete' );

		global $CNF, $USER;
		if (! $this->_serv->isLocked ( $delid )) {

			if ($this->_serv->delete ( $delid )) {
				$this->setSessionMessage ( 'Changelog has deleted succesfully!', array (
						"viewname" => 'browse.php'
				), 'warning' );
				$this->f3->reroute ( $CNF->wwwroot . 'projects/changelog/browse' );
			} else {
				$this->setSessionMessage ( 'Changelog could not deleted !', array (
						"viewname" => 'browse.php'
				), 'danger' );
				return;
			}
		} else {
			$this->setSessionMessage ( 'It can not be delete because changelog is locked!', array (
					"viewname" => 'browse.php'
			), 'danger' );
			return;
		}
	}

	/*
	 * export Single Changelog
	 */
	public function exportSingle() {
		global $CNF, $USER;

		$logid = \devlib\AppController::getKeyValueRequired ( 'logid' );
		$cdata = $this->_serv->getChangelogById ( $logid );
		$obj = new \module\projects\lib\ChangelogLib ();
		$data = $obj->exportTxtChangelog ( $logid );
		$filename = $cdata ['issueid'] . "_changelog.txt";
		ob_end_clean ();
		ob_start ();
		header ( "Content-Type: application/download\n" );
		header ( "Content-Disposition: attachment; filename=$filename" );
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate,post-check=0,pre-check=0" );
		header ( "Pragma: public" );
		echo $data;

		ob_end_flush ();
		die ();
	}

	/*
	 * export Combined
	 */
	public function exportCombined() {
		global $CNF, $USER;
		$filename = pDate ( time (), "d_m_Y_H_i_s" ) . "_changelog.txt";
		// logids from session
		if (isset ( $_SESSION ['search'] ['changelog'] ['exportsel'] )) {
			$logids = $_SESSION ['search'] ['changelog'] ['exportsel'];
			$logids = array_keys($logids);
		} else {
			$this->setSessionMessage ( 'Select Some Changelog First!', array (
					"viewname" => 'export.php'
			), 'danger' );
			$this->f3->reroute (\devlib\AppController::generateGetLink ('', $cntrlobj->baseurl . "export" ));
			return;
		}

		$obj = new \module\projects\lib\ChangelogLib ();
		echo $data = $obj->exportTxtChangelog ( $logids );

		ob_end_clean ();
		ob_start ();
		header ( "Content-Type: application/download\n" );
		header ( "Content-Disposition: attachment; filename=$filename" );
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate,post-check=0,pre-check=0" );
		header ( "Pragma: public" );
		echo $data;

		ob_end_flush ();
		die ();
	}

	/*
	 * export Seperate
	 */
	public function exportSeperate() {
		global $CNF, $USER;
		$foldername = pDate ( time (), "d_m_Y_H_i_s" ) . "_changelog";

		if (isset ( $_SESSION ['search'] ['changelog'] ['exportsel'] )) {
			$logids = $_SESSION ['search'] ['changelog'] ['exportsel'];
			$logids = array_keys($logids);
		} else {
			$this->setSessionMessage ( 'Select Some Changelog First!', array (
					"viewname" => 'export.php'
			), 'danger' );
			$this->f3->reroute (\devlib\AppController::generateGetLink ('', $this->baseurl . "export" ));
			return;
		}

		if (! is_dir ( $CNF->tmpdir )) {
			throw new \devlib\Exception ( 'tmp dir not found!' );
		}
		if (! is_writable ( $CNF->tmpdir )) {
			throw new \devlib\Exception ( 'tmp dir not writable!' );
		}
		// now make a folder
		if (! mkdir ( $CNF->tmpdir . $CNF->DS . $foldername )) {
			throw new \devlib\Exception ( 'Unable to Create!' );
		}
		foreach ( $logids as $logid ) {
			$obj = new \module\projects\lib\ChangelogLib ();
			$data = $obj->exportTxtChangelog ( $logid );
			$cdata = $this->_serv->getChangelogById ( $logid );
			if ($cdata ['issueid'] != '') {
				$filepath = $CNF->tmpdir . $CNF->DS . $foldername . $CNF->DS . $cdata ['issueid'] . "_changelog.txt";
			} else {
				$filepath = $CNF->tmpdir . $CNF->DS . $foldername . $CNF->DS . rand ( 1, 1000 ) . "_changelog.txt";
			}
			file_put_contents ( $filepath, $data, FILE_APPEND );
		}

		// now make a zip of that folder
		$zip = new \ZipArchive ();
		$zipname =  $foldername . ".zip";
		$zippath = $CNF->tmpdir . $CNF->DS . $zipname;
		if ($zip->open ( $zippath, \ZIPARCHIVE::CREATE ) !== true) {
			$this->setSessionMessage ( 'Zip could not create!', array (
					"viewname" => 'export.php'
			), 'danger' );
			$this->f3->reroute (\devlib\AppController::generateGetLink ('', $this->baseurl . "export" ));
			return;
		}
		// add the files
		$options = array (
				'add_path' =>$foldername . $CNF->DS,
				'remove_all_path' => TRUE
		);
		$zip->addGlob ( $CNF->tmpdir . $CNF->DS . $foldername . $CNF->DS.'*.{txt}', GLOB_BRACE, $options );
		$zip->close ();

		ob_end_clean ();
		ob_start ();
		header ( "Content-Type: application/zip\n" );
		header ( "Content-Disposition: attachment; filename=$zipname" );
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate,post-check=0,pre-check=0" );
		header ( "Pragma: public" );
		header("Content-Length: " . filesize($zippath ));
		readfile($zippath );
		ob_end_flush ();
		unlink($zippath);

		ob_end_flush ();
		die ();
	}

	/*
	 * export Seperate
	 */
	public function exportByLogType() {
		global $CNF, $USER;
		$foldername = pDate ( time (), "d_m_Y_H_i_s" ) . "_changelog";

		if (isset ( $_SESSION ['search'] ['changelog'] ['exportsel'] )) {
		    $logids = $_SESSION ['search'] ['changelog'] ['exportsel'];
		    $logids = array_keys($logids);
		} else {
		    $this->setSessionMessage ( 'Select Some Changelog First!', array (
		            "viewname" => 'export.php'
		    ), 'danger' );
		    $this->f3->reroute (\devlib\AppController::generateGetLink ('', $this->baseurl . "export" ));
		    return;
		}

		if (! is_dir ( $CNF->tmpdir )) {
		    throw new \devlib\Exception ( 'tmp dir not found!' );
		}
		if (! is_writable ( $CNF->tmpdir )) {
		    throw new \devlib\Exception ( 'tmp dir not writable!' );
		}
		// now make a folder
		if (! mkdir ( $CNF->tmpdir . $CNF->DS . $foldername )) {
		    throw new \devlib\Exception ( 'Unable to Create!' );
		}


		$obj = new \module\projects\lib\ChangelogLib ();
		$data = $obj->exportTxtChangelogByLogType ( $logids );
		foreach ( $data as $type=>$value ) {
			$filepath =$filepath = $CNF->tmpdir . $CNF->DS . $foldername . $CNF->DS . $type . "_changelog.txt";
		    file_put_contents ($filepath , $value, FILE_APPEND );
		}


		// now make a zip of that folder
		$zip = new \ZipArchive ();
		$zipname =  $foldername . ".zip";
		$zippath = $CNF->tmpdir . $CNF->DS . $zipname;
		if ($zip->open ($zippath , \ZIPARCHIVE::CREATE ) !== true) {
			$this->setSessionMessage ( 'Zip could not create!', array (
					"viewname" => 'export.php'
			), 'danger' );
			$this->f3->reroute (\devlib\AppController::generateGetLink ('', $this->baseurl . "export" ));
			return;
		}
		// add the files
		$options = array (
				'add_path' => $foldername . $CNF->DS,
				'remove_all_path' => TRUE
		);
		$zip->addGlob ( $CNF->tmpdir . $CNF->DS .$foldername . $CNF->DS. '*.{txt}', GLOB_BRACE, $options );
		$zip->close ();

		ob_end_clean ();
		ob_start ();
		header ( "Content-Type: application/zip\n" );
		header ( "Content-Disposition: attachment; filename=$zipname" );
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate,post-check=0,pre-check=0" );
		header ( "Pragma: public" );
		header("Content-Length: " . filesize($zippath ));
		readfile($zippath );
		ob_end_flush ();
		unlink($zippath);
		die ();
	}
	
	
	public function lock(){
		$lock= \devlib\AppController::getKeyValueRequired ( 'lock' );
		
		global $CNF, $USER;
		if($this->_serv->lockIssueById($lock)){
			$this->setSessionMessage ( 'Issue Locked Succesfully!', array (
					"viewname" => 'browse.php'
			) );
			$this->f3->reroute (\devlib\AppController::generateGetLink ('', $this->baseurl . "browse" ));
			return;
		}else{
			$this->setSessionMessage ( 'Issue could not lock!!', array (
					"viewname" => 'browse.php'
			), 'danger' );
			$this->f3->reroute (\devlib\AppController::generateGetLink ('', $this->baseurl . "browse" ));
			return;
		}
		
	}
}
