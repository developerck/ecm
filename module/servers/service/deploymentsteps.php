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
 * @service		deploymentsteps
 */
namespace module\servers\service;

class DeploymentSteps extends \module\servers\ServersService {
	public $table = 'server_deploymentsteps';
    public $tablecol = '';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->table = $CNF->tbl_prefix . $this->table;
        $this->tablecol = $this->getColumnFromTable ( $this->table );

	}
	
	public function getStepsByServer($id) {
		try {
				
			$data = array ();
			$totalrows = 0;
			$sql = "select * from $this->table where server_id=? order  by stepsequence ASC" ;
				
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ( array (
					$id
			) );
			$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
			return $data;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}
	
	
	public function getServerStepDetail($serverid, $stepid){
		try {
		
			$data = array ();
			$totalrows = 0;
			$sql = "select * from $this->table where server_id=? and stepid =? order  by stepsequence ASC" ;
		
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ( array (
					$serverid, $stepid
			) );
			$data = $sth->fetch( \PDO::FETCH_ASSOC );
			
			return $data;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
		
	}
	
	public function getStepDetailById($id){
		try {
	// $id => table id
			$data = array ();
			$totalrows = 0;
			$sql = "select * from $this->table where id=? " ;
	
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ( array (
					 $id
			) );
			$data = $sth->fetch( \PDO::FETCH_ASSOC );
				
			return $data;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	
	}
	
	public function saveStep($data){
		// saving user and role
		global $CNF;
		
		try {
		
			return $this->DBH->insert($this->table,$data);
		
		} catch ( \PDOException $e ) {
			// passing pdo exception object to log
		
			$this->logServiceException ( $e );
		}
		
	}
	
	public function updateStep($data,$id) {
		global $CNF;
	
		try {
	
			$this->DBH->update($this->table,$data,'where id = '.$id);
			return true;
	
		} catch ( \PDOException $e ) {
			// passing pdo exception object to log
	
			$this->logServiceException ( $e );
		}
	}

	public function deleteStep($id){
		global $CNF;
		try {
			$where = " where id = ?";
	
			return $this->DBH->delete($this->table, $where ,$id);
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}
	
	
}


