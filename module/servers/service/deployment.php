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
 * @service		deployment
 */
namespace module\servers\service;

class Deployment extends \module\servers\ServersService {
	public $tablecol = '';
	public $tbl_dpdcl = 'deployed_changelog';
    public $tbl_dp = 'deployment';
    public $tbl_dps = 'deployedsteps';
    public $tbl_cl = 'changelogs';
    public $tbl_dpsteps = 'server_deploymentsteps';
    public $tbl_p = 'projects';
    public $tbl_s = 'servers';
    public $tbl_pr = 'project_releaseversion';
    public $tbl_u = 'users';
    public $tbl_su = 'server_user';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->tbl_dpdcl = $CNF->tbl_prefix . $this->tbl_dpdcl;
		$this->tbl_dp = $CNF->tbl_prefix . $this->tbl_dp;
		$this->tbl_dps = $CNF->tbl_prefix . $this->tbl_dps;
		$this->tbl_cl = $CNF->tbl_prefix . $this->tbl_cl;
		$this->tbl_dpsteps  = $CNF->tbl_prefix . $this->tbl_dpsteps ;
		$this->tbl_p  = $CNF->tbl_prefix . $this->tbl_p ;
		$this->tbl_pr  = $CNF->tbl_prefix . $this->tbl_pr ;
		$this->tbl_s  = $CNF->tbl_prefix . $this->tbl_s ;
		$this->tbl_u  = $CNF->tbl_prefix . $this->tbl_u ;
		$this->tbl_su  = $CNF->tbl_prefix . $this->tbl_su ;


	}

	// get list
	public function getChangelogsToDeploy($cond = '', $orderby = '', $limit = '') {
		global $CNF;
		try {
			// $cond = $cond==''?$this->role_condition:($this->role_condition!=''?$this->role_condition.' and '.$cond:$cond);
			$data = array ();
			$totalrows = 0;
			 $sql = "select SQL_CALC_FOUND_ROWS
			 c.*,concat(rv.rvname,' (', IFNULL(rv.rcname,''),')')as rvname ,p.name as projectname
			 from $this->tbl_cl c
			 LEFT JOIN $this->tbl_pr rv ON rv.id= c.releaseversion_id
			 LEFT JOIN $this->tbl_p p ON p.id = c.project_id
			 $cond $orderby $limit";



			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ();
			$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
			$totalrows = $this->DBH->query ( 'SELECT FOUND_ROWS();' )->fetch ( \PDO::FETCH_COLUMN );

			return array (
					"rowcount" => $totalrows,
					'data' => $data
			);
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function getServerByProjectID($pid){

		global $CNF;
		try {


			$data = array ();
			$totalrows = 0;
			$sql = "select * from $this->tbl_s where project_id = ?";
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute (array($pid));
			$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
			$returnarr = array ();
			foreach ( $data as $value ) {
				$returnarr [$value ['id']] = $value ['name'];
			}
			return $returnarr;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function getAssignServerByCond($cond){

	    global $CNF;
	    try {


	        $data = array ();
	        $totalrows = 0;
	        $sql = "select s.id,s.name from $this->tbl_s s
".$cond;
	        
	        $sth = $this->DBH->prepare ( $sql );
	        $sth->execute ();
	        $data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
	        $returnarr = array ();
	        foreach ( $data as $value ) {
	            $returnarr [$value ['id']] = $value ['name'];
	        }
	        return $returnarr;
	    } catch ( \PDOException $e ) {
	        $this->logServiceException ( $e );
	    }
	}

	public function saveDeployment($data){
		// saving
		global $CNF;

		try {

			// step 1: save in deployment table
			// step 2: save in deployedchangelog table
			// step 3: save in deployedsteps table
			$this->DBH->beginTransaction();
			// step 1:
			$deploymentarr = array();
			$deploymentarr['server_id'] = $data['server_id'];
			$deploymentarr['project_id'] = $data['project_id'];
			$deploymentarr['comment'] = $data['comment'];
			$deploymentarr['deploymentby'] = $data['uid'];
			$deploymentarr['deploymenttime'] = $data['time'];
			$deploymentarr['changelogid'] = implode(",",array_keys($data['exportsel']));

			$colname = array_keys($deploymentarr);
			$colnamestr = implode(", ", $colname);
			foreach ($colname  as &$value){
				$value = ':'.$value;
			}
			$bindvaluestr = implode(", ", $colname);
			$sth = $this->DBH->prepare ( "INSERT INTO $this->tbl_dp  ($colnamestr) values ($bindvaluestr)" );
			$deploymentid= 0;
			if(	$sth->execute ($deploymentarr)){


				$deploymentid =  $this->DBH->lastInsertId ();
				// step2
				foreach($data['exportsel'] as $cid=>$cval){
					//$cid => changelogid
					$deployedchangelog = array();
					$deployedchangelog['deployment_id'] = $deploymentid;
					$deployedchangelog['changelog_id'] = $cid;
					$deployedchangelog['server_id'] = $data['server_id'];
					$deployedchangelog['project_id'] = $data['project_id'];
					$deployedchangelog['deployed'] = 1;
					$deployedchangelog['deployedby'] = $data['uid'];
					$deployedchangelog['deployedtime'] = $data['time'];

						$colname = array_keys($deployedchangelog);
						$colnamestr = implode(", ", $colname);
						foreach ($colname  as &$value){
							$value = ':'.$value;
						}
						$bindvaluestr = implode(", ", $colname);

						$sth = $this->DBH->prepare ( "INSERT INTO  $this->tbl_dpdcl ($colnamestr) values ($bindvaluestr)" );
						$sth->execute ($deployedchangelog);
					}

					//step 3:-
					if(!empty($data['stepchk'])){
						$srv_dps = new \module\servers\service\DeploymentSteps();

					foreach($data['stepchk'] as $stepid=>$stepidval){
						//$stepid => deploymentstep table id
						$steparr = array();
						$steparr = $srv_dps->getStepDetailById($stepid);
						$dpsarr = array();
						$dpsarr['server_id'] = $data['server_id'];
						$dpsarr['deployment_id'] = $deploymentid;
						$dpsarr['steplabel'] = $steparr['steplabel'];
						$dpsarr['stepid'] = $steparr['stepid'];
						$dpsarr['stepinputtype'] = $steparr['stepinputtype'];
						$dpsarr['stepinputname'] = $steparr['stepcomment'];
						$dpsarr['stepcomment'] = $steparr['stepcomment'];
						$dpsarr['steprequired'] = $steparr['steprequired'];
						$dpsarr['stepsequence'] = $steparr['stepsequence'];
						$dpsarr['createdby'] = $data['uid'];
						$dpsarr['creationtime'] = $data['time'];
						if(is_array($data['stepchk'])){
								$dpsarr['stepinputvalue'] = isset($data['steptxt'][$stepid])?$data['steptxt'][$stepid]:'';
							}


						$colname = array_keys($dpsarr);
						$colnamestr = implode(", ", $colname);
						foreach ($colname  as &$value){
							$value = ':'.$value;
						}
						$bindvaluestr = implode(", ", $colname);

						$sth = $this->DBH->prepare ( "INSERT INTO  $this->tbl_dps  ($colnamestr) values ($bindvaluestr)" );
						$sth->execute ($dpsarr);
					}

					}

				}

				$this->DBH->commit();
				return $deploymentid;



		} catch ( \PDOException $e ) {
			// passing pdo exception object to log
			$this->DBH->rollBack();
			$this->logServiceException ( $e );
		}

	}

	public function getDeploymentRecordByServer($serverid,$orderby = '', $limit = ''){
			global $CNF;
		    try {

		        $data = array ();
		        $totalrows = 0;
		        $sql = "SELECT SQL_CALC_FOUND_ROWS dp.*, s.name AS servername,p.name AS projectname, CONCAT(u.firstname,' ', IFNULL(u.lastname,'')) AS deployedby
	FROM  $this->tbl_dp dp
	LEFT JOIN $this->tbl_s s ON dp.server_id= s.id
	LEFT JOIN $this->tbl_p p ON dp.project_id= p.id
	LEFT JOIN $this->tbl_u u ON dp.deploymentby= u.id
	WHERE server_id = ? $orderby $limit";

		        $sth = $this->DBH->prepare ( $sql );
		        $sth->execute (array($serverid));
		        $data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
		        $totalrows = $this->DBH->query ( 'SELECT FOUND_ROWS();' )->fetch( \PDO::FETCH_COLUMN );


				foreach($data as $key=>$record){
					$retdataarr = array();
					$retdataarr = $record;
					// now get the changelog
					if($record['changelogid'] !=''){
					$changelogsql = "select c.id,c.issueid,concat(rv.rvname,' (', IFNULL(rv.rcname,''),')')as rvname ,p.name as projectname from $this->tbl_cl c LEFT JOIN $this->tbl_pr rv ON rv.id= c.releaseversion_id  LEFT JOIN $this->tbl_p p ON p.id = c.project_id where c.id IN(".$record['changelogid'].")";

					$sth = $this->DBH->prepare ( $changelogsql );
					$sth->execute ();
					$retdataarr['changelogs_detail'] = $sth->fetchAll ( \PDO::FETCH_ASSOC );
					}else{
						$retdataarr['changelogs_detail'] = array();
					}
					$data[$key] = $retdataarr;
				}
		        return array (
		                "rowcount" => $totalrows,
		                'data' => $data
		        );
		    } catch ( \PDOException $e ) {
		        $this->logServiceException ( $e );
		    }
		}


		public function getDeployedStepsByDPID($dpid) {
			try {

				$data = array ();
				$totalrows = 0;
				$sql = "select * from $this->tbl_dps where deployment_id = ? order  by stepsequence ASC" ;

				$sth = $this->DBH->prepare ( $sql );
				$sth->execute ( array (
						$dpid
				) );
				$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
				return $data;
			} catch ( \PDOException $e ) {
				$this->logServiceException ( $e );
			}
		}

		public function getDeployedRecordDPID($dpid) {
			try {

				$data = array ();
				$totalrows = 0;
				$sql = "select * from $this->tbl_dp where id = ? " ;

				$sth = $this->DBH->prepare ( $sql );
				$sth->execute ( array (
						$dpid
				) );
				$data = $sth->fetch ( \PDO::FETCH_ASSOC );
				return $data;
			} catch ( \PDOException $e ) {
				$this->logServiceException ( $e );
			}
		}

		public function noOfDeploymentByServerID($sid){
			try {

				$sth = $this->DBH->prepare ( "select  count(*)  from $this->tbl_dp where server_id =? ");

				$sth->execute (array($sid));
				return $sth->fetchColumn ();
			} catch ( \PDOException $e ) {
				$this->logServiceException ( $e );
			}
		}

		public function noOfDeployedChangelogByServerId($sid){
			try {

				$sth = $this->DBH->prepare ( "select  count(*)  from $this->tbl_dpdcl where server_id =? ");

				$sth->execute (array($sid));
				return $sth->fetchColumn ();
			} catch ( \PDOException $e ) {
				$this->logServiceException ( $e );
			}
		}

		public function getLatestDeploymentDateByServerId($sid){
			try {

				$sth = $this->DBH->prepare ( "select  max(deploymenttime)  from $this->tbl_dp where server_id =? ");

				$sth->execute (array($sid));
				return $sth->fetchColumn ();
			} catch ( \PDOException $e ) {
				$this->logServiceException ( $e );
			}
		}
}


