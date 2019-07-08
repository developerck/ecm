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
 * @service		project
 */
namespace module\projects\service;

class Project extends \module\projects\ProjectsService {
	public $table = 'projects';
	public $tablecol = '';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->table = $CNF->tbl_prefix . $this->table;
		$this->tablecol = $this->getColumnFromTable ( $this->table );
	}
	// save and update
	public function saveProject($data) {
		// saving user and role
		global $CNF,$USER;
		try {
			$this->DBH->beginTransaction ();
			// insert user
			$valarr = $data ['pro'];
			$colname = array_keys ( $valarr );
			$colnamestr = implode ( ", ", $colname );
			foreach ( $colname as &$value ) {
				$value = ':' . $value;
			}
			$bindvaluestr = implode ( ", ", $colname );
			$sth = $this->DBH->prepare ( "INSERT INTO $this->table  ($colnamestr) values ($bindvaluestr)" );

			if ($sth->execute ( $valarr )) {

				$proid = $this->DBH->lastInsertId ();
				// insert scm detail
				$valarr = $data ['scm'];

				// saving user_id with role_id
				$valarr ['project_id'] = $proid;
				$colname = array_keys ( $valarr );
				$colnamestr = implode ( ", ", $colname );
				foreach ( $colname as &$value ) {
					$value = ':' . $value;
				}
				$bindvaluestr = implode ( ", ", $colname );

				$sth = $this->DBH->prepare ( "INSERT INTO  " . $CNF->tbl_prefix . "project_scmdetail ($colnamestr) values ($bindvaluestr)" );
				$sth->execute ( $valarr );

				// insert db detail
				$valarr = $data ['db'];

				// saving user_id with role_id
				$valarr ['project_id'] = $proid;
				$colname = array_keys ( $valarr );
				$colnamestr = implode ( ", ", $colname );
				foreach ( $colname as &$value ) {
					$value = ':' . $value;
				}
				$bindvaluestr = implode ( ", ", $colname );

				$sth = $this->DBH->prepare ( "INSERT INTO  " . $CNF->tbl_prefix . "project_dbdetail ($colnamestr) values ($bindvaluestr)" );
				$sth->execute ( $valarr );

				// now assign
				// not for admin, for other pm
				if ($USER ['user_role'] ['shortname'] != 'ADMIN') {
					$valarr = array ();
					$valarr ['project_id'] = $proid;
					$valarr ['user_id'] = $data ['pro'] ['createdby'];
					$valarr ['assignedtime'] = time ();
					$valarr ['assignedby'] = $data ['pro'] ['createdby'];
					$colname = array_keys ( $valarr );
					$colnamestr = implode ( ", ", $colname );
					foreach ( $colname as &$value ) {
						$value = ':' . $value;
					}
					$bindvaluestr = implode ( ", ", $colname );

					$sth = $this->DBH->prepare ( "INSERT INTO  " . $CNF->tbl_prefix . "project_user ($colnamestr) values ($bindvaluestr)" );
					$sth->execute ( $valarr );
				}
				$this->DBH->commit ();
				return $proid;
			}
		} catch ( \PDOException $e ) {
			// passing pdo exception object to log
			$this->DBH->rollBack ();
			$this->logServiceException ( $e );
		}
	}
	public function updateProject($data, $id) {
		global $CNF;

		try {
			$this->DBH->beginTransaction ();
			// insert user
			$valarr = $data ['pro'];
			$colname = array_keys ( $valarr );
			foreach ( $colname as &$value ) {
				$value = $value . '= :' . $value;
			}
			$bindvaluestr = implode ( ", ", $colname );

			$sth = $this->DBH->prepare ( "update $this->table SET $bindvaluestr where id=" . $id );

			if ($sth->execute ( $valarr )) {


				$valarr = $data ['scm'];

				$colname = array_keys ( $valarr );
				foreach ( $colname as &$value ) {
					$value = $value . '= :' . $value;
				}
				$bindvaluestr = implode ( ", ", $colname );
				$sth = $this->DBH->prepare ( "update " . $CNF->tbl_prefix . "project_scmdetail SET $bindvaluestr where project_id=" . $id );
				$sth->execute ( $valarr );

				// insert db detail
				$valarr = $data ['db'];

				$colname = array_keys ( $valarr );
				foreach ( $colname as &$value ) {
					$value = $value . '= :' . $value;
				}
				$bindvaluestr = implode ( ", ", $colname );
				$sth = $this->DBH->prepare ( "update " . $CNF->tbl_prefix . "project_dbdetail  SET $bindvaluestr where project_id=" . $id );
				$sth->execute ( $valarr );

				$this->DBH->commit ();
				return true;
			}
		} catch ( \PDOException $e ) {
			// passing pdo exception object to log
			$this->DBH->rollBack ();
			$this->logServiceException ( $e );
		}
	}
	// get list
	public function getProjects($cond = '', $orderby = '', $limit = '') {
		global $CNF;
		try {
			// $cond = $cond==''?$this->role_condition:($this->role_condition!=''?$this->role_condition.' and '.$cond:$cond);
			$data = array ();
			$totalrows = 0;
			$sql = "select SQL_CALC_FOUND_ROWS p.* from $this->table p LEFT JOIN " . $CNF->tbl_prefix . "project_user pu ON p.id= pu.project_id $cond $orderby $limit";

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

	// get list
	public function getProjectById($id) {
		try {

			$data = array ();
			$totalrows = 0;
			$sql = "select * from $this->table where id=?";

			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ( array (
					$id
			) );
			$data = $sth->fetch ( \PDO::FETCH_ASSOC );
			return $data;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	// validate project name
	public function isProjectExist($project, $cond = '') {
		try {

			$sth = $this->DBH->prepare ( "select  count(*)  from $this->table  where lower(name) = ? " . $cond );

			$sth->execute ( array (
					strtolower ( $project )
			) );
			if ($sth->fetchColumn () > 0) {
				return true;
			} else {
				return false;
			}
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}
	public function isActive($id) {
		try {

			$sth = $this->DBH->prepare ( "select id, isactive  from $this->table  where id = ? " );
			$sth->execute ( array (
					$id
			) );
			$data = $sth->fetch ( \PDO::FETCH_ASSOC );
			if ($data ['isactive']) {
				return true;
			} else {
				return false;
			}
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}
	public function getProjectsArray($cond = '', $orderby = '', $limit = '') {
		global $CNF;
		try {


			$data = array ();
			$totalrows = 0;
			$sql = "select p.* from $this->table p LEFT JOIN " . $CNF->tbl_prefix . "project_user pu ON p.id= pu.project_id   $cond $orderby $limit";
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

	public function isAssigned($projectid, $userid) {
		global $CNF;
		try {
			$cond = 'where pu.project_id =  ? and pu.user_id= ?';
			$sql = "select SQL_CALC_FOUND_ROWS p.* from $this->table p LEFT JOIN " . $CNF->tbl_prefix . "project_user pu ON p.id= pu.project_id $cond ";

			$sth = $this->DBH->prepare ( $sql);
			$sth->execute ( array (
					$projectid,$userid
			) );
			return $sth->rowCount();
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function getAssignUserOnProject($cond = '') {
		global $CNF;
		try {


			$data = array ();
			$totalrows = 0;
			$sql = "select * from ". $CNF->tbl_prefix . "project_user $cond ";
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ();
			$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );

			return $data ;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function getAssignUserByProjectId($id) {
	    global $CNF;
	    try {


	        $data = array ();
	        $totalrows = 0;
	        $sql = "select user_id,project_id, assignedtime from ". $CNF->tbl_prefix . "project_user where project_id= ?";
	        $sth = $this->DBH->prepare ( $sql );
	        $sth->execute (array($id));
	        $data = $sth->fetchAll ( \PDO::FETCH_ASSOC );

	        return $data ;
	    } catch ( \PDOException $e ) {
	        $this->logServiceException ( $e );
	    }
	}

	public function getAssignUserArrByProjectId($id) {
	    global $CNF;
	    $userarr = array();
	    $data =  $this->getAssignUserByProjectId($id);
	    foreach($data as $record){
	        $userarr[$record['user_id']]=$record['user_id'];
	    }
	    return $userarr;
	}

	public function assignProject($data){

			// saving user and role
			global $CNF;
			try {
				$this->DBH->beginTransaction ();


					//DONE: assign by inser,update,delete
				$projectid = $data['project_id'];
//				$sth = $this->DBH->prepare ( "DELETE FROM  " . $CNF->tbl_prefix . "project_user where project_id=?" );
//				$sth->execute ( array($projectid));
				$assigneduserarr = $this-> getAssignUserArrByProjectId($projectid);

				foreach($data['users_id'] as $userid){
					if(!in_array($userid, $assigneduserarr)){

					$valarr = array ();
					$valarr['project_id'] = $projectid;
					$valarr['user_id'] = $userid;
					$valarr ['assignedtime'] = time ();
					$valarr ['assignedby'] = $data['assignedby'];
					$colname = array_keys ( $valarr );
					$colnamestr = implode ( ", ", $colname );
					foreach ( $colname as &$value ) {
						$value = ':' . $value;
					}
					$bindvaluestr = implode ( ", ", $colname );

					$sth = $this->DBH->prepare ( "INSERT INTO  " . $CNF->tbl_prefix . "project_user ($colnamestr) values ($bindvaluestr)" );
					$sth->execute ( $valarr );
					}else{
					    // user already assinged then do nothing
					    unset ($assigneduserarr[$userid]);
					}
				}
				// now we have users that are not assigned now
				if(!empty($assigneduserarr)){
				    // some user are unassigned
				    $sth = $this->DBH->prepare ( "DELETE FROM  " . $CNF->tbl_prefix . "project_user where user_id IN (?)" );
				    $assigneduserstr = implode(",",$assigneduserarr);
				    $sth->execute ( array($assigneduserstr));
				}

					$this->DBH->commit ();
					return true;
				}
			 catch ( \PDOException $e ) {
				// passing pdo exception object to log
				$this->DBH->rollBack ();
				$this->logServiceException ( $e );
			}
	}
}


