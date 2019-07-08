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
 * @service		changelog
 */
namespace module\projects\service;

class Changelog extends \module\projects\ProjectsService {
	public $table = 'changelogs';
	public $tablecol = '';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->table = $CNF->tbl_prefix . $this->table;
		$this->tablecol = $this->getColumnFromTable ( $this->table );
	}
	// save and update
	public function save($data) {
		// saving user and role
		global $CNF;

		try {

		    return $this->DBH->insert($this->table,$data);

		} catch ( \PDOException $e ) {
		    // passing pdo exception object to log

		    $this->logServiceException ( $e );
		}
	}
	public function update($data, $id) {
		global $CNF;

		try {

		    return $this->DBH->update($this->table,$data,'where id = '.$id);

		} catch ( \PDOException $e ) {
		    // passing pdo exception object to log

		    $this->logServiceException ( $e );
		}
	}
	// get list
	public function getChangelogs($cond = '', $orderby = '', $limit = '') {
		global $CNF;
		try {
			// $cond = $cond==''?$this->role_condition:($this->role_condition!=''?$this->role_condition.' and '.$cond:$cond);
			$data = array ();
			$totalrows = 0;
			$sql = "select SQL_CALC_FOUND_ROWS c.*,concat(rv.rvname,' (', IFNULL(rv.rcname,''),')')as rvname ,p.name as projectname from $this->table c LEFT JOIN " . $CNF->tbl_prefix . "project_releaseversion rv ON rv.id= c.releaseversion_id  LEFT JOIN " . $CNF->tbl_prefix . "projects p ON p.id = c.project_id $cond $orderby $limit";

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
	public function getChangelogById($id) {
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
	public function isChangelogExist( $cond = '') {
		try {

			$sth = $this->DBH->prepare ( "select  count(*)  from $this->table" . $cond );

			$sth->execute ();
			if ($sth->fetchColumn () > 0) {
				return true;
			} else {
				return false;
			}
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}
	public function isLocked($id) {
		try {

			$sth = $this->DBH->prepare ( "select id, islocked  from $this->table  where id = ? " );
			$sth->execute ( array (
					$id
			) );
			$data = $sth->fetch ( \PDO::FETCH_ASSOC );
			if ($data ['islocked']) {
				return true;
			} else {
				return false;
			}
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function checkChangelogExistsByRV($rvid){
		try {

		    $sth = $this->DBH->prepare ( "select  count(*)  from $this->table where releaseversion_id =? ");

		    $sth->execute (array($rvid));
		    if ($sth->fetchColumn () > 0) {
		        return true;
		    } else {
		        return false;
		    }
		} catch ( \PDOException $e ) {
		    $this->logServiceException ( $e );
		}
	}


	public function noOfChangelogByProjectID($proid,$cond =''){
		try {

			$sth = $this->DBH->prepare ( "select  count(*)  from $this->table where project_id =? ".$cond);

			$sth->execute (array($proid));
			return $sth->fetchColumn ();
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function getChangelogLabelArrByProjectId($pid){
		global $CNF;
		try {


		    $data = array ();
		    $totalrows = 0;
		    $sql = "select * from $this->table where project_id =? and labelname IS NOT NULL";
		    $sth = $this->DBH->prepare ( $sql );
		    $sth->execute (array($pid));
		    $data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
		    $returnarr = array ();
		    foreach ( $data as $value ) {
		        $returnarr [$value ['labelname']] = $value ['labelname'];
		    }
		    return $returnarr;
		} catch ( \PDOException $e ) {
		    $this->logServiceException ( $e );
		}
	}

	public function delete($id){
		global $CNF;
		try {
			$where = " where id = ?";

			return $this->DBH->delete($this->table, $where ,$id);
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function getFullDetailByChnagelog($cid){
		global $CNF;
		if(!is_array($cid)){
			$cid = array($cid=>$cid);
		}
		$cidstr = implode(",",$cid );
		try {
		    // $cond = $cond==''?$this->role_condition:($this->role_condition!=''?$this->role_condition.' and '.$cond:$cond);
		    $data = array ();
		    $totalrows = 0;
		    $sql = "select SQL_CALC_FOUND_ROWS c.*,concat(rv.rvname,' (', IFNULL(rv.rcname,''),')')as rvname ,p.name as projectname from $this->table c LEFT JOIN " . $CNF->tbl_prefix . "project_releaseversion rv ON rv.id= c.releaseversion_id  LEFT JOIN " . $CNF->tbl_prefix . "projects p ON p.id = c.project_id where c.id IN ($cidstr)";

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

	public function lockIssueById($id){
		$data = array('islocked'=>1,'lockedtime'=>time());
		return $this->update($data, $id);
	}
}


