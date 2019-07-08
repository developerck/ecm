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
class UserRole extends \module\users\UsersService{

    public $table = 'user_role';
	public $tablecol = '';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->table = $CNF->tbl_prefix . $this->table;
		$this->tablecol = $this->getColumnFromTable ( $this->table );


	}

	// get list
	public function getRoleByUserId($id) {
	   global $CNF;
		try {
			$data = array ();
			$totalrows = 0;
			$sql = "select role_id,user_id,ur.shortname,ur.rolename from $this->table LEFT JOIN ".$CNF->tbl_prefix."roles  ur on $this->table.role_id=ur.id where user_id=?";

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
    
     public function getNoOfUserByRole($roleid =''){
        try {global $CNF;
			$data = array ();
			$totalrows = 0;
            if($roleid){
            $sql = "select role_id,count(user_id) totaluser,ur.shortname from $this->table LEFT JOIN ".$CNF->tbl_prefix."roles  ur on $this->table.role_id=ur.id GROUP BY role_id having role_id = ?";
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute (array($roleid));
			$data = $sth->fetch( \PDO::FETCH_ASSOC );
			    
            }else{
            $sql = "select role_id,ur.shortname,count(user_id) totaluser from $this->table LEFT JOIN ".$CNF->tbl_prefix."roles  ur on $this->table.role_id=ur.id GROUP BY role_id";
			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ();
			$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
			    
            }
			return $data;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
    }

}



?>