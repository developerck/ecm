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
 * @service  releseversion
 */
namespace module\projects\service;

class ReleaseVersion extends \module\projects\ProjectsService {
	public $table = 'project_releaseversion';
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

	public function update($data,$id) {
		global $CNF;

		try {

            return $this->DBH->update($this->table,$data,'where id = '.$id);

		} catch ( \PDOException $e ) {
			// passing pdo exception object to log

			$this->logServiceException ( $e );
		}
	}
	// get list
	public function getRV($cond = '', $orderby = '', $limit = '') {
		global $CNF;
		try {
            $cond = $cond==''?$this->role_condition:($this->role_condition!=''?$this->role_condition.' and '.$cond:$cond);
			$data = array ();
			$totalrows = 0;
	     	$sql = "select SQL_CALC_FOUND_ROWS rv.*,p.name as project_name from $this->table rv LEFT JOIN {$CNF->tbl_prefix}projects p on p.id = rv.project_id  $cond $orderby $limit";

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

    public function delete($id){
        global $CNF;
		try {
		      $where = " where id = ?";
              
		      return $this->DBH->delete($this->table, $where ,$id);
		  	} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
    } 
	// get list
	public function getRVById($id) {
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
	public function isRVExist($project,$rvname,$rcname, $cond='') {
		try {

			$sth = $this->DBH->prepare ( "select  count(*)  from $this->table  where project_id = ? and lower(rvname) = ? and lower(rcname) = ? ".$cond );

			$sth->execute (array($project,strtolower ($rvname),strtolower ($rcname)));
			if ($sth->fetchColumn () > 0) {
				return true;
			} else {
				return false;
			}
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	// validate project name
	public function noOfRVByProjectID($project) {
		try {
	
			$sth = $this->DBH->prepare ( "select  count(*)  from $this->table  where project_id = ? " );
	
			$sth->execute (array($project));
			return $sth->fetchColumn ();
			
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function isLocked($id) {
		try {

			$sth = $this->DBH->prepare ( "select id, islocked  from $this->table  where id = ? " );
			$sth->execute (array($id));
			$data = $sth->fetch ( \PDO::FETCH_ASSOC );
            if($data['islocked']){
				return true;
			} else {
				return false;
			}
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function getReleaseVersionArr($cond = '', $orderby = '', $limit = '') {
		global $CNF;
		try {
			
			
			$data = array ();
			$totalrows = 0;
			 $sql = "select * from $this->table  $cond $orderby $limit";
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ();
			$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
			$returnarr = array ();
			foreach ( $data as $value ) {
				$returnarr [$value ['id']] = $value ['rvname'].($value ['rcname']?"(".$value ['rcname'].")":'');
			}
			return $returnarr;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function lockAllChangeLogByRVID($rvid){
		global $CNF;
		
		try {
			$data= array();
			$data['islocked'] =1;
			$data['lockedtime'] =time();
			return $this->DBH->update($CNF->tbl_prefix."changelogs",$data,'where releaseversion_id = '.$rvid);
		
		} catch ( \PDOException $e ) {
			// passing pdo exception object to log
		
			$this->logServiceException ( $e );
		}
	}
}


