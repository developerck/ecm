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

class Role extends \module\users\UsersService{

	public $table = 'roles';
	public $tablecol = '';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->table = $CNF->tbl_prefix . $this->table;
		$this->tablecol = $this->getColumnFromTable ( $this->table );


	}

	// TODO: make frront-end to make roles
    public function getRoles($cond = '',$orderby='', $limit=''){
    	try {

    		$data = array();
    		$totalrows =0;
    		$sql ="select SQL_CALC_FOUND_ROWS * from $this->table $cond $orderby $limit" ;

    		$sth = $this->DBH->prepare ( $sql);
    		$sth->execute ();
    		$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
    		$totalrows = $this->DBH->query('SELECT FOUND_ROWS();')->fetch(\PDO::FETCH_COLUMN);
    		return array("rowcount"=>$totalrows,'data'=>$data);

    	} catch ( \PDOException $e ) {
    		$this->logServiceException ( $e );
    	}
    }

    // TODO: make frront-end to make roles
    public function getRolesOptionsArr($cond = '',$orderby='', $limit=''){
    	try {

    		$data = array();
    		$totalrows =0;
    		$sql ="select * from $this->table $cond $orderby $limit" ;
    		$sth = $this->DBH->prepare ( $sql);
    		$sth->execute ();
    		$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
    		$returnarr = array();
    		foreach($data as $value){
				$returnarr[$value['id']] = $value['rolename'];
    		}
			return $returnarr;

    	} catch ( \PDOException $e ) {
    		$this->logServiceException ( $e );
    	}
    }
    
    	// TODO: make frront-end to make roles
    public function getRoleIdByShortName($shortname){
    	try {

    		$data = array();
    		$totalrows =0;
    		$sql ="select id, shortname from $this->table where shortname = ?" ;

    		$sth = $this->DBH->prepare ( $sql);
    		$sth->execute (array($shortname));
    		$data = $sth->fetch ( \PDO::FETCH_ASSOC );
    		return $data;

    	} catch ( \PDOException $e ) {
    		$this->logServiceException ( $e );
    	}
    }
   
}



?>