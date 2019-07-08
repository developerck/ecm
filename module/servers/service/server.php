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
 * @service		server
 */
namespace module\servers\service;

class Server extends \module\servers\ServersService {
	public $table = 'servers';
	public $tablecol = '';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->table = $CNF->tbl_prefix . $this->table;
		$this->tablecol = $this->getColumnFromTable ( $this->table );
	}
	// save and update
	public function saveServer($data) {
		// saving user and role
		global $CNF,$USER;
		try {
			$this->DBH->beginTransaction ();
			// insert user
			$valarr = $data ['srvcol'];
			$colname = array_keys ( $valarr );
			$colnamestr = implode ( ", ", $colname );
			foreach ( $colname as &$value ) {
				$value = ':' . $value;
			}
			$bindvaluestr = implode ( ", ", $colname );
			$sth = $this->DBH->prepare ( "INSERT INTO $this->table  ($colnamestr) values ($bindvaluestr)" );

			if ($sth->execute ( $valarr )) {

				$srvid = $this->DBH->lastInsertId ();
				// insert ftp detail
				$valarr = $data ['ftpcol'];


				$valarr ['server_id'] = $srvid;
				$colname = array_keys ( $valarr );
				$colnamestr = implode ( ", ", $colname );
				foreach ( $colname as &$value ) {
					$value = ':' . $value;
				}
				$bindvaluestr = implode ( ", ", $colname );

				$sth = $this->DBH->prepare ( "INSERT INTO  " . $CNF->tbl_prefix . "server_ftpdetail ($colnamestr) values ($bindvaluestr)" );
				$sth->execute ( $valarr );

				// insert db detail
				$valarr = $data ['dbcol'];

				// saving user_id with role_id
				$valarr ['server_id'] = $srvid;
				$colname = array_keys ( $valarr );
				$colnamestr = implode ( ", ", $colname );
				foreach ( $colname as &$value ) {
					$value = ':' . $value;
				}
				$bindvaluestr = implode ( ", ", $colname );

				$sth = $this->DBH->prepare ( "INSERT INTO  " . $CNF->tbl_prefix . "server_dbdetail ($colnamestr) values ($bindvaluestr)" );
				$sth->execute ( $valarr );

				// now assign
				// not for admin, for other pm
				if ($USER ['user_role'] ['shortname'] != 'ADMIN') {
					$valarr = array ();
					$valarr ['server_id'] = $srvid;
					$valarr ['user_id'] = $data ['srvcol'] ['createdby'];
					$valarr ['assignedtime'] = time ();
					$valarr ['assignedby'] = $data ['srvcol'] ['createdby'];
					$colname = array_keys ( $valarr );
					$colnamestr = implode ( ", ", $colname );
					foreach ( $colname as &$value ) {
						$value = ':' . $value;
					}
					$bindvaluestr = implode ( ", ", $colname );

					$sth = $this->DBH->prepare ( "INSERT INTO  " . $CNF->tbl_prefix . "server_user ($colnamestr) values ($bindvaluestr)" );
					$sth->execute ( $valarr );
				}
				$this->DBH->commit ();
				return $srvid;
			}
		} catch ( \PDOException $e ) {
			// passing pdo exception object to log
			$this->DBH->rollBack ();
			$this->logServiceException ( $e );
		}
	}
	public function updateServer($data, $id) {
		global $CNF, $USER;

		try {
			$this->DBH->beginTransaction ();
			// insert user
			$valarr = $data ['srvcol'];
			$colname = array_keys ( $valarr );
			foreach ( $colname as &$value ) {
				$value = $value . '= :' . $value;
			}
			$bindvaluestr = implode ( ", ", $colname );

			$sth = $this->DBH->prepare ( "update $this->table SET $bindvaluestr where id=" . $id );

			if ($sth->execute ( $valarr )) {


				$valarr = $data ['ftpcol'];

				$colname = array_keys ( $valarr );
				foreach ( $colname as &$value ) {
					$value = $value . '= :' . $value;
				}
				$bindvaluestr = implode ( ", ", $colname );
				$sth = $this->DBH->prepare ( "update " . $CNF->tbl_prefix . "server_ftpdetail SET $bindvaluestr where server_id=" . $id );
				$sth->execute ( $valarr );

				// insert db detail
				$valarr = $data ['dbcol'];

				$colname = array_keys ( $valarr );
				foreach ( $colname as &$value ) {
					$value = $value . '= :' . $value;
				}
				$bindvaluestr = implode ( ", ", $colname );
				$sth = $this->DBH->prepare ( "update " . $CNF->tbl_prefix . "server_dbdetail  SET $bindvaluestr where server_id=" . $id );
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
	public function getServers($cond = '', $orderby = '', $limit = '') {
		global $CNF;
		try {
			// $cond = $cond==''?$this->role_condition:($this->role_condition!=''?$this->role_condition.' and '.$cond:$cond);
			$data = array ();
			$totalrows = 0;
			$sql = "select SQL_CALC_FOUND_ROWS s.*,p.name as projectname from $this->table s LEFT JOIN " . $CNF->tbl_prefix . "server_user su ON s.id= su.server_id LEFT JOIN ". $CNF->tbl_prefix . "projects p ON s.project_id=p.id $cond $orderby $limit";

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
	public function getServerById($id) {
		global $CNF;
		try {

			$data = array ();
			$totalrows = 0;
			$sql = "select s.*,p.name as projectname from $this->table s LEFT JOIN " . $CNF->tbl_prefix . "server_user su ON s.id= su.server_id LEFT JOIN ". $CNF->tbl_prefix . "projects p ON s.project_id=p.id where s.id=?";

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

	// validate server name
	public function isServerExist($server, $cond = '') {
		try {

			$sth = $this->DBH->prepare ( "select  count(*)  from $this->table  where lower(name) = ? " . $cond );

			$sth->execute ( array (
					strtolower ( $server )
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
	public function getServersArray($cond = '', $orderby = '', $limit = '') {
		global $CNF;
		try {


			$data = array ();
			$totalrows = 0;
			$sql = "select s.*,p.name as projectname from $this->table s LEFT JOIN " . $CNF->tbl_prefix . "server_user su ON s.id= su.server_id LEFT JOIN ". $CNF->tbl_prefix . "projects p ON s.project_id=p.id   $cond $orderby $limit";
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ();
			$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
			$returnarr = array ();
			foreach ( $data as $value ) {
				$returnarr [$value ['id']] = $value ['name']. " (".$value ['projectname'].")";
			}
			return $returnarr;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function isAssigned($serverid, $userid) {
		global $CNF;
		try {
			$cond = 'where su.server_id =  ? and su.user_id= ?';
			$sql = "select SQL_CALC_FOUND_ROWS s.* from $this->table s LEFT JOIN " . $CNF->tbl_prefix . "server_user su ON s.id= su.server_id $cond ";

			$sth = $this->DBH->prepare ( $sql);
			$sth->execute ( array (
					$serverid,$userid
			) );
			return $sth->rowCount();
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function getAssignUserOnServer($cond = '') {
		global $CNF;
		try {


			$data = array ();
			$totalrows = 0;
			$sql = "select * from ". $CNF->tbl_prefix . "server_user $cond ";
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ();
			$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );

			return $data ;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

	public function getAssignServerArrByUserId($uid) {
	    global $CNF;
	    try {


	        $data = array ();
	        $totalrows = 0;
	        $sql = "select id, server_id from ". $CNF->tbl_prefix . "server_user where user_id =? ";
	        $sth = $this->DBH->prepare ( $sql );
	        $sth->execute (array($uid));
	        $data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
	        $serverarr = array();
	        foreach($data as $record){
	            $serverarr[$record['id']]=$record['server_id'];
	        }
	        return $serverarr;

	    } catch ( \PDOException $e ) {
	        $this->logServiceException ( $e );
	    }
	}


	public function getAssignUserByServerId($srvid) {
	    global $CNF;
	    try {


	        $data = array ();
	        $totalrows = 0;
	        $sql = "select * from ". $CNF->tbl_prefix . "server_user where server_id =?";
	        $sth = $this->DBH->prepare ( $sql );
	        $sth->execute (array($srvid));
	        $data = $sth->fetchAll ( \PDO::FETCH_ASSOC );

	        return $data ;
	    } catch ( \PDOException $e ) {
	        $this->logServiceException ( $e );
	    }
	}

	public function getAssignUserArrByServerId($srvid) {
	    global $CNF;
	    	$userarr = array();
			$data =  $this->getAssignUserByServerId($srvid);
			foreach($data as $record){
				$userarr[$record['user_id']]=$record['user_id'];
			}
			return $userarr;
	}


	public function assignServer($data){

			// saving user and role
			global $CNF;
			try {
				$this->DBH->beginTransaction ();


					//DONE: assign by inser,update,delete
				$serverid = $data['server_id'];
//				$sth = $this->DBH->prepare ( "DELETE FROM  " . $CNF->tbl_prefix . "server_user where server_id=?" );
//				$sth->execute ( array($serverid));
				$assigneduserarr = $this-> getAssignUserArrByServerId($serverid);

				foreach($data['users_id'] as $userid){
				if(!in_array($userid, $assigneduserarr)){

				// insert
				$valarr = array ();
					$valarr['server_id'] = $serverid;
					$valarr['user_id'] = $userid;
					$valarr ['assignedtime'] = time ();
					$valarr ['assignedby'] = $data['assignedby'];
					$colname = array_keys ( $valarr );
					$colnamestr = implode ( ", ", $colname );
					foreach ( $colname as &$value ) {
						$value = ':' . $value;
					}
					$bindvaluestr = implode ( ", ", $colname );

					$sth = $this->DBH->prepare ( "INSERT INTO  " . $CNF->tbl_prefix . "server_user ($colnamestr) values ($bindvaluestr)" );
					$sth->execute ( $valarr );
				}else{
						// user already assinged then do nothing
					unset ($assigneduserarr[$userid]);
				}

			}
					// now we have users that are not assigned now
					if(!empty($assigneduserarr)){
						// some user are unassigned
						$sth = $this->DBH->prepare ( "DELETE FROM  " . $CNF->tbl_prefix . "server_user where user_id IN (?)" );
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


