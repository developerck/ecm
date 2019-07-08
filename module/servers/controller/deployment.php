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
 * @controller  deployment
 */
namespace module\servers\controller;

class Deployment extends \module\servers\ServersController {
	protected $_serv;
	protected $_serv_project;
	protected $_serv_rv;
	protected $_serv_server;
	protected $_serv_dps;
	public $form;
	public $table_list_data;
	public function __construct() {
		parent::__construct ();

		$this->_serv = new \module\servers\service\Deployment ();
		$this->_serv_project = new \module\projects\service\Project ();
		$this->_serv_rv = new \module\projects\service\ReleaseVersion ();
		$this->_serv_dps = new \module\servers\service\DeploymentSteps ();
		$this->_serv_server = new \module\servers\service\Server ();
		$this->baseurl = $this->baseurl . '/deployment/';
		$this->basemethod = 'deploy';
	}
	public function deploy() {
		$definedstepno = array (
				1,
				2,
				3
		);
		$step = \devlib\AppController::getKeyValue ( 'step' );
		if (! $step || ! in_array ( $step, $definedstepno )) {
			$step = 1;
		}

		// project id not selected go to step 1
		if ($step == 1) {
			// if we go to step 1 then emty selected changelog
			$this->f3->set ( 'SESSION.search.deployment.changelog', '' );
			$this->f3->set ( 'SESSION.search.deployment.exportsel', '' );
		} else if ($step == 2) {
		}
		if (! $this->f3->get ( 'SESSION.search.deployment.changelog.project_id' )) {
			$step = 1;
		} else if (! $this->f3->get ( 'SESSION.search.deployment.exportsel' )) {
			// changelog not selected go to step 2
			$step = 2;
		}
		$this->form ['step'] = $step;
		$this->view [] = 'stepheader.php';
		$stepfun = "step" . $step;
		$this->$stepfun ();
		$this->view [] = 'stepfooter.php';
	}
	public function step1() {
		$this->view [] = 'step1.php';
		global $CNF, $USER;

		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$procond = 'where p.isactive=1 group by p.id';
		} else {
			$procond = 'where p.isactive=1 and pu.user_id = ' . $USER ['uid'];
		}
		$this->form ['data'] ['projectarr'] = $this->_serv_project->getProjectsArray ( $procond );
		$this->form ['data'] ['selprojectid'] = $this->f3->get ( 'SESSION.search.deployment.changelog.project_id' );
		$this->form ['data'] ['selserverid'] = $this->f3->get ( 'SESSION.search.deployment.changelog.server_id' );
		// $assignprojectarr = $this->_serv_project->getProjectsArray($procond);
		if ($this->f3->get ( 'SESSION.search.deployment.changelog.project_id' )) {
			$this->form ['data'] ['serverarr'] = $this->_serv->getServerByProjectID ( $this->f3->get ( 'SESSION.search.deployment.changelog.project_id' ) );
		} else {
			$this->form ['data'] ['serverarr'] = array ();
		}

		if ($this->form ['form']->validate ()) {
			$param = $this->f3->get ( 'POST' );
			$searcharr = array ();
			$searcharr ['project_id'] = $param ['project_id'];
			$searcharr ['releaseversion_id'] = '';
			$searcharr ['server_id'] = $param ['server_id'];
			$searcharr ['issueid'] = '';
			$searcharr ['labelname'] = '';

			if ($this->f3->set ( 'SESSION.search.deployment.changelog', $searcharr )) {
				// reintialize step 2 selected changelog array
				$_SESSION ['search'] ['deployment'] ['exportsel'] = array ();
				$this->setSessionMessage ( 'Project And Server Selected ! Now Select Changelog!', array (
						"viewname" => 'step1.php'
				) );
				$url = \devlib\AppController::generateGetLink ( array (
						"step" => 2
				), $this->baseurl . "deploy" );

				$this->f3->reroute ( $url );
			} else {
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}
	public function step2($paging = true) {
		$this->view [] = 'step2.php';
		global $CNF, $USER;
		$param = $this->f3->get ( 'POST' );

		$proid = $this->f3->get ( 'SESSION.search.deployment.changelog.project_id' );
		$prodata = $this->_serv_project->getProjectById ( $proid );
		$proname = $prodata ['name'];
		$srvid = $this->f3->get ( 'SESSION.search.deployment.changelog.server_id' );
		$srvdata = $this->_serv_server->getServerById ( $srvid );
		$srvname = $srvdata ['name'];
		$this->form ['data'] ['projectname'] = $proname;
		$this->form ['data'] ['servername'] = $srvname;

		// checking if user proceed for next step
		if (isset ( $param ['btnnext'] )) {
			// it is true user is going for next step :)
			if (! $this->f3->get ( 'SESSION.search.deployment.exportsel' )) {
				$this->setSessionMessage ( 'Please Select  Changelogs Before Deployment!', array (
						"viewname" => ''
				) );
				return;
			}
			$this->setSessionMessage ( 'Changelog Selected ! Now Follow deployment Checklist!', array (
					"viewname" => ''
			) );
			$url = \devlib\AppController::generateGetLink ( array (
					"step" => 3
			), $this->baseurl . "deploy" );

			$this->f3->reroute ( $url );
		}

		$searchcond = '';

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
				$searcharr ['project_id'] = $this->f3->get ( 'SESSION.search.deployment.changelog.project_id' );
				$searcharr ['server_id'] = $this->f3->get ( 'SESSION.search.deployment.changelog.server_id' );
				$searcharr ['releaseversion_id'] = isset ( $param ['releaseversion_id'] ) ? $param ['releaseversion_id'] : '';

				$searcharr ['issueid'] = $param ['issueid'];
				$searcharr ['labelname'] = $param ['labelname'];
				$this->f3->set ( 'SESSION.search.deployment.changelog', $searcharr );
			} else if (isset ( $param ['btnreset'] )) {
				$searcharr = array ();

				$this->f3->set ( 'SESSION.search.deployment.changelog', $searcharr );
			}
		}
		$sessionsearch = $this->f3->get ( 'SESSION.search.deployment.changelog' );

		if (isset ( $sessionsearch ) && ! empty ( $sessionsearch )) {
			$searcharr = $sessionsearch;
			$searchcond = array ();
			if ($searcharr ['project_id'] != '') {
				$searchcond [] = "( c.project_id =" . $searcharr ['project_id'] . ")";
			}
			// case of multiple
			if (! empty ( $searcharr ['releaseversion_id'] ) && is_array ( $searcharr ['releaseversion_id'] )) {
				$searchcond [] = " ( c.releaseversion_id  IN (" . implode ( ",", $searcharr ['releaseversion_id'] ) . ") )";
			}

			if ($searcharr ['issueid']) {
				$searchcond [] = " ( c.issueid like '%" . $searcharr ['issueid'] . "%' ) ";
			}
			if ($searcharr ['labelname']) {
				$searchcond [] = " ( c.labelname like '%" . $searcharr ['labelname'] . "%' )";
			}
			if (! empty ( $searchcond )) {
				$searchcond = implode ( " AND ", $searchcond );
				$searchcond = ' AND ' . $searchcond;
			}
		} else {
			$searchcond = '';
			$searcharr = array ();
			$searcharr ['project_id'] = '';
			$searcharr ['server_id'] = '';
			$searcharr ['releaseversion_id'] = '';
			$searcharr ['issueid'] = '';
			$searcharr ['labelname'] = '';
		}

		$this->form ['data'] ['searcharr'] = $searcharr;
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$procond = ' group by p.id';
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
			$cond = ' where
					c.id NOT IN (SELECT dpd.changelog_id FROM ecm_deployed_changelog dpd WHERE  dpd.project_id='.$searcharr ['project_id'].')
					AND  c.islocked=1 ' . $searchcond;
			$this->table_list_data ['paging'] = '';
			if ($paging) {

				if ($pageno = \devlib\AppController::getKeyValue ( 'page' )) {
				} else {
					$pageno = 1;
				}
				$paging_obj = new \devlib\Pagination ( $pageno, 20 );
				$serial_offset = ( int ) $paging_obj->getOffset ( $pageno );

				$limit = ' Limit ' . $serial_offset . ', ' . ( int ) $paging_obj->getPerPage ();
				$data = $this->_serv->getChangelogsToDeploy ( $cond, $orderby, $limit );

				$this->table_list_data ['paging'] = $paging_obj->doPaging ( $data ['rowcount'] );
			} else {
				$data = $this->_serv->getChangelogsToDeploy ( $cond, $orderby );
			}
		} else {
			$data ['data'] = array ();
		}
		$listformatter = new \devlib\ListGenerator ( $data ['data'], $header, array_keys ( $header ) );
		$this->table_list_data ['data'] = $listformatter->setTableArray ( $serial = true, $serial_offset );
	}
	public function step3() {
		global $CNF, $USER;

		$this->view [] = 'step3.php';
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$proid = $this->f3->get ( 'SESSION.search.deployment.changelog.project_id' );
		$prodata = $this->_serv_project->getProjectById ( $proid );
		$proname = $prodata ['name'];
		$srvid = $this->f3->get ( 'SESSION.search.deployment.changelog.server_id' );
		$srvdata = $this->_serv_server->getServerById ( $srvid );
		$srvname = $srvdata ['name'];
		$selcchangearr = $this->f3->get ( 'SESSION.search.deployment.exportsel' );
		$this->form ['data'] ['project_id'] = $proid;
		$this->form ['data'] ['projectname'] = $proname;
		$this->form ['data'] ['server_id'] = $srvid;
		$this->form ['data'] ['servername'] = $srvname;
		$this->form ['data'] ['selectedchangelog'] = $selcchangearr;
		$data = $this->_serv_dps->getStepsByServer ( $srvid );
		$this->form ['data'] ['steps'] = $data;

		if ($this->form ['form']->validate ()) {
			$param = $this->f3->get ( 'POST' );
			$savearr = array ();
			$savearr ['project_id'] = $_SESSION ['search'] ['deployment'] ['changelog'] ['project_id'];
			$savearr ['server_id'] = $_SESSION ['search'] ['deployment'] ['changelog'] ['server_id'];
			$savearr ['exportsel'] = $_SESSION ['search'] ['deployment'] ['exportsel'];
			$savearr ['stepchk'] = isset ( $param ['stepchk'] ) ? $param ['stepchk'] : '';
			$savearr ['steptxt'] = isset ( $param ['steptxt'] ) ? $param ['steptxt'] : '';
			$savearr ['comment'] = $param ['comment'];
			$savearr ['uid'] = $USER ['uid'];
			$savearr ['time'] = time ();
			if ($this->_serv->saveDeployment ( $savearr )) {
				// reintialize step 2 selected changelog array

				$this->setSessionMessage ( 'Deployment Done!', array (
						"viewname" => 'step1.php'
				) );
				$url = \devlib\AppController::generateGetLink ( array (
						"step" => 1
				), $this->baseurl . "deploy" );
				$this->f3->set ( 'SESSION.search.deployment', '' );
				$this->f3->reroute ( $url );
			} else {
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}
	/*
	 * export Single Changelog
	 */
	public function exportSingle() {
		global $CNF, $USER;

		$logid = \devlib\AppController::getKeyValueRequired ( 'logid' );
		$cobj = new \module\projects\service\Changelog ();
		$cdata = $cobj->getChangelogById ( $logid );
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

		if (isset ( $_SESSION ['search'] ['deployment'] ['exportsel'] ) && ! empty ( $_SESSION ['search'] ['deployment'] ['exportsel'] )) {
			$logids = $_SESSION ['search'] ['deployment'] ['exportsel'];
			$logids = array_keys ( $logids );
		} else {
			$this->setSessionMessage ( 'Select Some Changelog First!', array (
					"viewname" => 'export.php'
			), 'danger' );
			$this->f3->reroute ( \devlib\AppController::generateGetLink ( array (
					"step" => 2
			), $this->baseurl . "deploy" ) );
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

		if (isset ( $_SESSION ['search'] ['deployment'] ['exportsel'] ) && ! empty ( $_SESSION ['search'] ['deployment'] ['exportsel'] )) {
			$logids = $_SESSION ['search'] ['deployment'] ['exportsel'];
			$logids = array_keys ( $logids );
		} else {
			$this->setSessionMessage ( 'Select Some Changelog First!', array (
					"viewname" => 'step2.php'
			), 'danger' );
			$this->f3->reroute ( \devlib\AppController::generateGetLink ( array (
					"step" => 2
			), $this->baseurl . "deploy" ) );
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
			$cobj = new \module\projects\service\Changelog ();
			$cdata = $cobj->getChangelogById ( $logid );
			if ($cdata ['issueid'] != '') {
				$filepath = $CNF->tmpdir . $CNF->DS . $foldername . $CNF->DS . $cdata ['issueid'] . "_changelog.txt";
			} else {
				$filepath = $CNF->tmpdir . $CNF->DS . $foldername . $CNF->DS . rand ( 1, 1000 ) . "_changelog.txt";
			}
			file_put_contents ( $filepath, $data, FILE_APPEND );
		}

		// now make a zip of that folder
		$zip = new \ZipArchive ();
		$zipname = $foldername . ".zip";
		$zippath = $CNF->tmpdir . $CNF->DS . $zipname;
		if ($zip->open ( $zippath, \ZIPARCHIVE::CREATE ) !== true) {
			$this->setSessionMessage ( 'Zip could not create!', array (
					"viewname" => 'step2.php'
			), 'danger' );
			$this->f3->reroute ( \devlib\AppController::generateGetLink ( array (
					"step" => 2
			), $this->baseurl . "deploy" ) );
			return;
		}
		// add the files
		$options = array (
				'add_path' => $foldername . $CNF->DS,
				'remove_all_path' => TRUE
		);
		$zip->addGlob ( $CNF->tmpdir . $CNF->DS . $foldername . $CNF->DS . '*.{txt}', GLOB_BRACE, $options );
		$zip->close ();

		ob_end_clean ();
		ob_start ();
		header ( "Content-Type: application/zip\n" );
		header ( "Content-Disposition: attachment; filename=$zipname" );
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate,post-check=0,pre-check=0" );
		header ( "Pragma: public" );
		header ( "Content-Length: " . filesize ( $zippath ) );
		readfile ( $zippath );
		ob_end_flush ();
		unlink ( $zippath );

		ob_end_flush ();
		die ();
	}

	/*
	 * export Seperate
	 */
	public function exportByLogType() {
		global $CNF, $USER;
		$foldername = pDate ( time (), "d_m_Y_H_i_s" ) . "_changelog";

		if (isset ( $_SESSION ['search'] ['deployment'] ['exportsel'] )) {
			$logids = $_SESSION ['search'] ['deployment'] ['exportsel'];
			$logids = array_keys ( $logids );
		} else {
			$this->setSessionMessage ( 'Select Some Changelog First!', array (
					"viewname" => 'step2.php'
			), 'danger' );
			$this->f3->reroute ( \devlib\AppController::generateGetLink ( array (
					"step" => 2
			), $this->baseurl . "deploy" ) );
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
		foreach ( $data as $type => $value ) {
			$filepath = $filepath = $CNF->tmpdir . $CNF->DS . $foldername . $CNF->DS . $type . "_changelog.txt";
			file_put_contents ( $filepath, $value, FILE_APPEND );
		}

		// now make a zip of that folder
		$zip = new \ZipArchive ();
		$zipname = $foldername . ".zip";
		$zippath = $CNF->tmpdir . $CNF->DS . $zipname;
		if ($zip->open ( $zippath, \ZIPARCHIVE::CREATE ) !== true) {
			$this->setSessionMessage ( 'Zip could not create!', array (
					"viewname" => 'step2.php'
			), 'danger' );
			$this->f3->reroute ( \devlib\AppController::generateGetLink ( array (
					"step" => 2
			), $cntrlobj->baseurl . "deploy" ) );
			return;
		}
		// add the files
		$options = array (
				'add_path' => $foldername . $CNF->DS,
				'remove_all_path' => TRUE
		);
		$zip->addGlob ( $CNF->tmpdir . $CNF->DS . $foldername . $CNF->DS . '*.{txt}', GLOB_BRACE, $options );
		$zip->close ();

		ob_end_clean ();
		ob_start ();
		header ( "Content-Type: application/zip\n" );
		header ( "Content-Disposition: attachment; filename=$zipname" );
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate,post-check=0,pre-check=0" );
		header ( "Pragma: public" );
		header ( "Content-Length: " . filesize ( $zippath ) );
		readfile ( $zippath );
		ob_end_flush ();
		unlink ( $zippath );
		die ();
	}
	public function changelogPatch() {
		$this->view [] = 'changelogpatch.php';
		$patchfilerror = array ();
		global $CNF, $USER;
		$param = $this->f3->get ( 'POST' );
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$data = array (
				"svnurl" => '',
				"username" => '',
				"password" => '',
				'checkout' => 0
		)
		;
		$this->form['data']['patchpath'] = '';
		$this->form['data']['patchfileerror'] = '';
		if (isset ( $param ['btnsubmit'] )) {
			// remainining form filles incase there is an error
			assocArrayLeftMerge ( $this->form ['data']['field'] , $param );
		}

		$this->form ['data']['field'] = $data;
		if ($this->form ['form']->validate () || $this->f3->get ( 'SESSION.search.deployment.svnfoldername' )) {
			// now perform to make changelog patch
			if (! $this->f3->get ( 'SESSION.search.deployment.svnfoldername' )) {
				$svnobj = new \devlib\SVNClient ( $param ['svnurl'], $param ['username'], $param ['password'] );
				$svnfoldername = date ( "d_m_Y_H_i_s" ) . '_svncheckout';
				$svnfolderpath = $CNF->tmpdir . $CNF->DS . $svnfoldername . $CNF->DS;
				if (mkdir ( $svnfolderpath )) {
					$svnobj->checkout ( $svnfolderpath );
					// now check out done
					$this->form ['data'] ['checkout'] = 1;
					$this->f3->set ( 'SESSION.search.deployment.svnfoldername', $svnfoldername );
				} else {
					return $this->form ['form']->add_error ( 'error', 'Could not create directory for checkout!' );
				}
			}
			if ($this->f3->get ( 'SESSION.search.deployment.svnfoldername' )) {
				// now get all the selected changelog
				$exportsel = $this->f3->get ( 'SESSION.search.deployment.exportsel' );
				if (! empty ( $exportsel )) {
					$svnfoldername = $this->f3->get ( 'SESSION.search.deployment.svnfoldername' );
					$svnfolderpath = $CNF->tmpdir . $CNF->DS . $svnfoldername;
					$patchfoldername = date ( "d_m_Y_H_i_s" ) . '_patch';
					$patchfolderpath = $CNF->tmpdir . $CNF->DS . $patchfoldername . $CNF->DS;
					$patchfolderfilespath = $patchfolderpath . 'Files';
					if (! mkdir ( $patchfolderpath )) {
						return $this->form ['form']->add_error ( 'error', 'Could not create directory for patch!' );
					}
					if (! mkdir ( $patchfolderfilespath )) {
						return $this->form ['form']->add_error ( 'error', 'Could not create directory for patch!' );
					}

					$cobj = new \module\projects\service\Changelog ();
					foreach ( $exportsel as $cid => $cval ) {

						$cdata = $cobj->getChangelogById ( $cid );
						$filelog = $cdata ['filelog'];
						$filelog = preg_replace ( '/\n/', ' ', $filelog );
						$filelogarr = explode ( " ", $filelog );
						if (! empty ( $filelogarr )) {
							// copy all changelog from checkout directory to patch directory
							foreach ( $filelogarr as $filepath ) {
								$filepath = trim ( $filepath );
								if ($filepath != '') {
									if (substr ( $filepath, 0, 1 ) !== $CNF->DS) {
										$filepath = $CNF->DS . $filepath;
									}
									$frompath = $svnfolderpath . $filepath;

									$topath = $patchfolderfilespath . $filepath;
									if (! is_dir ( $topath )) {
										// we will make dir strucutre
										$parentdir = dirname ( $topath );
										if (! is_dir ( $parentdir )) {
											mkdir ( $parentdir, 0777, true );
										}
									}
									if (! rcopy ( $frompath, $topath )) {
										$patchfilerror [$cid] [] = $filepath;
									}
								}
							}
						}
					}

					// now make all the changelogs
					$obj = new \module\projects\lib\ChangelogLib ();
					$logids = array_keys ( $exportsel );
					$data = $obj->exportTxtChangelogByLogType ( $logids );
					foreach ( $data as $type => $value ) {
						$filepath = $filepath = $patchfolderpath . $type . "_changelog.txt";
						file_put_contents ( $filepath, $value, FILE_APPEND );
					}
					$zipname = $patchfoldername . ".zip";
					$zippath = $CNF->tmpdir . $CNF->DS . $zipname;

					// now make a zip of that folder
					/*$zip = new \ZipArchive ();
					if ($zip->open ( $zippath, \ZIPARCHIVE::CREATE ) !== true) {

						return $this->form ['form']->add_error ( 'error', 'zip could not create!' );
					}
					// add the files
					$options = array (
							'add_path' => $patchfoldername . $CNF->DS,
							'remove_all_path' => TRUE
					);
					$zip->addGlob ( $patchfolderpath .'*', GLOB_BRACE, $options );
					$zip->close ();
					*/
					makeZip($patchfolderpath,$zippath);
					$this->form['data']['patchpath'] = $CNF->wwwroot."uploaddata".$CNF->DS.'tmp'.$CNF->DS.$zipname;
					$this->form['data']['patchfileerror'] = $patchfilerror;
					/*
					ob_end_clean ();
					ob_start ();
					header ( "Content-Type: application/zip\n" );
					header ( "Content-Disposition: attachment; filename=$zipname" );
					header ( "Expires: 0" );
					header ( "Cache-Control: must-revalidate,post-check=0,pre-check=0" );
					header ( "Pragma: public" );
					header ( "Content-Length: " . filesize ( $zippath ) );
					readfile ( $zippath );
					ob_end_flush ();

					die ();
						*/
				} else {
					return $this->form ['form']->add_error ( 'error', 'No Changelog to make a patch!' );
				}
			} else {
				return $this->form ['form']->add_error ( 'error', 'svncheckout folder not found!' );
			}
		}
	}


}
