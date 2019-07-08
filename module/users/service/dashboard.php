<?php

/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      user
 *
 */
namespace module\users\service;

class Dashboard extends \module\users\UsersService {
	public $tbl_p = 'projects';
	public $tbl_pu = 'project_user';
	public $tbl_s = 'servers';
	public $tbl_su = 'server_user';
	public $tbl_c = 'changelogs';
	public $serv_c;
	public $serv_rv;
	public $serv_p;
	public $serv_dp;
	public $serv_s;
	public function __construct() {
		global $CNF;
		$this->tbl_p =$CNF->tbl_prefix.$this->tbl_p;
		$this->tbl_pu =$CNF->tbl_prefix.$this->tbl_pu;
		$this->tbl_s =$CNF->tbl_prefix.$this->tbl_s;
		$this->tbl_su =$CNF->tbl_prefix.$this->tbl_su;
		$this->tbl_c =$CNF->tbl_prefix.$this->tbl_c;
		$this->serv_c = new \module\projects\service\Changelog();
		$this->serv_rv = new \module\projects\service\ReleaseVersion();
		$this->serv_p = new \module\projects\service\Project();
		$this->serv_dp = new \module\servers\service\Deployment();
		$this->serv_s= new \module\servers\service\Server();
		parent::__construct ();


	}

// calling all methods here to get the detail about project
	public function getProjectDashboard($cond,$orderby=' oreder by  isactive DESC ', $limit=''){
		// five details
		//1:- assignproject
		// 2:-No of release version
		// 3:- Total No of changelogs
		// 4:- Total No. of locked Issues
		// 5: - Total Assigned User
		global $CNF;
		try {
			
			$data = array ();
			$totalrows = 0;
			$sql = "select SQL_CALC_FOUND_ROWS p.*
			 from $this->tbl_p p 
			LEFT JOIN $this->tbl_pu pu ON p.id= pu.project_id $cond $orderby $limit";
		
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ();
			$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
			$totalrows = $this->DBH->query ( 'SELECT FOUND_ROWS();' )->fetch ( \PDO::FETCH_COLUMN );
			
			foreach($data as $key=>$record){
			$retarr = array();
			$retarr['id'] = $record['id'];
			$retarr['name'] = $record['name'];
			$retarr['isactive'] = $record['isactive'];
			$retarr['creationtime'] = $record['creationtime'];
			$retarr['norv'] = $this->serv_rv->noOfRVByProjectID($record['id']);
			$retarr['noc'] = $this->serv_c->noOfChangelogByProjectID($record['id']);
			$retarr['nolc'] = $this->serv_c->noOfChangelogByProjectID($record['id'],' and islocked = 1');
			$retarr['noau'] = count($this->serv_p->getAssignUserArrByProjectId($record['id']));
			$data[$key]= $retarr;
			}
			return array (
					"rowcount" => $totalrows,
					'data' => $data
			);
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}

	}

	// calling all methods here to get the detail about server
	public function getServerDashboard($cond,$orderby=' oreder by  isactive DESC ', $limit=''){
	    // five details
	    //1:- assignserver
	    // 2:-No. Of Deployment
	    // 3:- Total No of deployed Changelog
	    // 4:- Latest Deployment at
	    // 5: - Total Assigned User
	    // 6:-Your assigned Data
		global $CNF;
		try {
			
			$data = array ();
			$totalrows = 0;
			 $sql = "select SQL_CALC_FOUND_ROWS s.*
			from $this->tbl_s s
			LEFT JOIN $this->tbl_su su ON s.id= su.server_id $cond $orderby $limit";
		
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ();
			$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
			$totalrows = $this->DBH->query ( 'SELECT FOUND_ROWS();' )->fetch ( \PDO::FETCH_COLUMN );
				
			foreach($data as $key=>$record){
				$retarr = array();
				$retarr['id'] = $record['id'];
				$retarr['name'] = $record['name'];
				$retarr['isactive'] = $record['isactive'];
				$retarr['creationtime'] = $record['creationtime'];
				$retarr['nod'] = $this->serv_dp->noOfDeploymentByServerID($record['id']);
				$retarr['nodc'] = $this->serv_dp->noOfDeployedChangelogByServerId($record['id']);
				$retarr['nold'] = $this->serv_dp->getLatestDeploymentDateByServerId($record['id']);
				$retarr['noau'] = count($this->serv_s->getAssignUserArrByServerId($record['id']));
				$data[$key]= $retarr;
			}
			return array (
					"rowcount" => $totalrows,
					'data' => $data
			);
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}
}