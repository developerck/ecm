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

class User extends \module\users\UsersService {
	public $table = 'users';
	public $tablecol = '';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->table = $CNF->tbl_prefix . $this->table;
		$this->tablecol = $this->getColumnFromTable ( $this->table );


	}



	// save and update
	public function saveUser($data) {
		// saving user and role
		global $CNF;



		// TODO: TO implement functionality if user want to make user wihtout roles


		try {
			$this->DBH->beginTransaction();
			// insert user
			$valarr = $data['user'];
			$colname = array_keys($valarr);
			$colnamestr = implode(", ", $colname);
			foreach ($colname  as &$value){
				$value = ':'.$value;
			}
			$bindvaluestr = implode(", ", $colname);
			$sth = $this->DBH->prepare ( "INSERT INTO $this->table  ($colnamestr) values ($bindvaluestr)" );

			if(	$sth->execute ($valarr)){


			$userid =  $this->DBH->lastInsertId ();
			// insert user role
			$valarr = $data['role'];
			if(!empty($valarr)){
				// saving user_id with role_id
				$valarr['user_id'] = $userid;
				$colname = array_keys($valarr);
				$colnamestr = implode(", ", $colname);
				foreach ($colname  as &$value){
					$value = ':'.$value;
				}
				$bindvaluestr = implode(", ", $colname);

				$sth = $this->DBH->prepare ( "INSERT INTO  ".$CNF->tbl_prefix."user_role ($colnamestr) values ($bindvaluestr)" );
				$sth->execute ($valarr);
			}
			$this->DBH->commit();
			return $userid;
			}


		} catch ( \PDOException $e ) {
			// passing pdo exception object to log
			$this->DBH->rollBack();
			$this->logServiceException ( $e );
		}
	}

	public function updateUser($data) {
		// saving user and role
		global $CNF;


		// TODO: TO implement functionality if user want to make user wihtout roles


		try {
			$this->DBH->beginTransaction();
			// insert user
			$valarr = $data['user'];
			$colname = array_keys($valarr);
			foreach ($colname  as &$value){
				$value = $value.'= :'.$value;
			}
			$bindvaluestr = implode(", ", $colname);

			$sth = $this->DBH->prepare ( "update $this->table SET $bindvaluestr where id=".$valarr['id'] );

			if(	$sth->execute ($valarr)){



				// insert user role
				$valarr = $data['role'];
				if(!empty($valarr)){
					// saving user_id with role_id
						$colname = array_keys($valarr);
						foreach ($colname  as &$value){
							$value = $value.'= :'.$value;
						}
						$bindvaluestr = implode(", ", $colname);
						$sth = $this->DBH->prepare ( "update ".$CNF->tbl_prefix."user_role  SET $bindvaluestr where user_id=".$valarr['user_id']  );
						$sth->execute ($valarr);
				}
				$this->DBH->commit();
				return true;
			}


		} catch ( \PDOException $e ) {
			// passing pdo exception object to log

			$this->DBH->rollBack();
			$this->logServiceException ( $e );
		}
	}
	// get list
	public function getUsers($cond = '', $orderby = '', $limit = '') {
		global $CNF;
		try {

			$data = array ();
			$totalrows = 0;
	       	$sql = "select SQL_CALC_FOUND_ROWS u.*, concat(u.firstname,' ', IFNULL(u.lastname,''))as name,r.rolename from $this->table u LEFT JOIN ".$CNF->tbl_prefix."user_role ur
			on u.id = ur.user_id LEFT JOIN ".$CNF->tbl_prefix."roles r on ur.role_id = r.id   $cond $orderby $limit";

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
	public function getUserById($id) {
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
	public function isEmailIdExist($emailid, $cond='') {
		try {

			$sth = $this->DBH->prepare ( "select  count(*)  from $this->table  where lower(emailid) = ? ".$cond );

			$sth->execute (array(strtolower ( $emailid )));
			if ($sth->fetchColumn () > 0) {
				return true;
			} else {
				return false;
			}
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}

    	// isactive
	public function isActive($id) {
		try {

			$sth = $this->DBH->prepare ( "select id, isactive  from $this->table  where id = ? " );
			$sth->execute (array($id));
			$data = $sth->fetch ( \PDO::FETCH_ASSOC );
            if($data['isactive']){
				return true;
			} else {
				return false;
			}
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}


	// return password by Emailid
	public function getPasswordByEmailId($emailid) {
		try {

			$sth = $this->DBH->prepare ( "select  id,emailid,password,passwordsalt  from $this->table  where lower(emailid) = ?" );
			$sth->execute (array(strtolower ( $emailid )));
			$data = $sth->fetch ( \PDO::FETCH_ASSOC );
			return $data;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}


   public function noOfAdmin(){
    try {

			$sth = $this->DBH->prepare ( "select  id,emailid,password,passwordsalt  from $this->table  where lower(emailid) = ?" );
			$sth->execute (array(strtolower ( $emailid )));
			$data = $sth->fetch ( \PDO::FETCH_ASSOC );
			return $data;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}

   }

   public function getUsersArrayWithRole($cond='',$orderby='',$limit=''){

   	global $CNF;
   	try {
   		$cond = $cond == ''?' where u.isactive = 1 and r.shortname != "ADMIN" ':$cond;
   		$cond = $cond==''?$this->role_condition:($this->role_condition!=''?$this->role_condition.' and '.$cond:$cond);
   		$data = array ();

   		$sql = "select u.id,concat(u.firstname,' ', IFNULL(u.lastname,''))as name, ur.role_id, r.rolename from $this->table u LEFT JOIN ".$CNF->tbl_prefix."user_role ur
			on u.id = ur.user_id LEFT JOIN ".$CNF->tbl_prefix."roles r on ur.role_id = r.id   $cond $orderby $limit";
   		$sth = $this->DBH->prepare ( $sql );
   		$sth->execute ();
   		$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );

   		return $data ;


   	} catch ( \PDOException $e ) {
   		$this->logServiceException ( $e );
   	}
   }
}



