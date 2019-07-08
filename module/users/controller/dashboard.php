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
 * @controller  dashboard
 */
namespace module\users\controller;

class Dashboard extends \module\users\UsersController {
	protected $_serv;
	public $form;
	public $data;
	
	public $table_project_data;
	public $table_server_data;
	public function __construct() {
		parent::__construct ();

		$this->_serv = new \module\users\service\Dashboard();
		$this->baseurl = $this->baseurl . '/user/';
		$this->basemethod = 'mainDashboard';
	}

	public function mainDashboard(){
	global $CNF,$USER;
		$this->projectDashboard();
		$this->serverDashboard();
	}

	
	public function projectDashboard() {
		global $USER;
	
		$this->view[] = 'projectdashboard.php';
		
	
		$serial_offset = 0;
		// this key name should match keyname of data record
		$header = array (
				'id' => '', // s that it would not come with header
				'name' => 'Name',
				'norv' => 'No. Of RV',
				'noc' => 'Changelogs',
				'nolc' => 'Locked Changelogs',
				'noau' => 'Assigned Users',
			
		);
		$orderby = 'order by id DESC ';
		// parsing url and gettignpageno.
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$cond = ' group by p.id ';
		} else {
			$cond = 'where pu.user_id = ' . $USER ['uid'];
		}
	
			if ($pageno = \devlib\AppController::getKeyValue ( 'project' )) {
			} else {
				$pageno = 1;
			}
			$paging_obj = new \devlib\Pagination ( $pageno, '','project' );
			$serial_offset = ( int ) $paging_obj->getOffset ( $pageno );
	
			$limit = ' Limit ' . $serial_offset . ', ' . ( int ) $paging_obj->getPerPage ();
			$data = $this->_serv->getProjectDashboard ( $cond, $orderby, $limit );
	
			$this->table_project_data ['paging'] = $paging_obj->doPaging ( $data ['rowcount'] );
		
	
		$listformatter = new \devlib\ListGenerator ( $data ['data'], $header, array_keys ( $header ) );
		$this->table_project_data['data'] = $listformatter->setTableArray ( $serial = true, $serial_offset );
	}
	
	public function serverDashboard() {
		global $USER;
	
		$this->view[] = 'serverdashboard.php';
	
	
		$serial_offset = 0;
		// this key name should match keyname of data record
		$header = array (
				'id' => '', // s that it would not come with header
				'name' => 'Name',
				'nod' => 'Deployment',
				'nodc' => 'Deployed Chnagelog',
				'nold' => 'Latest Deployment At',
				'noau' => 'Assigned Users',
		);
		$orderby = 'order by id DESC ';
		// parsing url and gettignpageno.
	
		// parsing url and gettignpageno.
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
			$cond = ' group by s.id ';
		} else {
			$cond = 'where su.user_id = ' . $USER ['uid'];
		}
		if ($pageno = \devlib\AppController::getKeyValue ( 'server' )) {
		} else {
			$pageno = 1;
		}
		$paging_obj = new \devlib\Pagination ( $pageno, '','server' );
		$serial_offset = ( int ) $paging_obj->getOffset ( $pageno );
	
		$limit = ' Limit ' . $serial_offset . ', ' . ( int ) $paging_obj->getPerPage ();
		$data = $this->_serv->getServerDashboard ( $cond, $orderby, $limit );
	
		$this->table_server_data ['paging'] = $paging_obj->doPaging ( $data ['rowcount'] );
	
	
		$listformatter = new \devlib\ListGenerator ( $data ['data'], $header, array_keys ( $header ) );
		$this->table_server_data ['data'] = $listformatter->setTableArray ( $serial = true, $serial_offset );
	}
	
}