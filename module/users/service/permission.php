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
class Permission extends \module\users\UsersService{

    public $table = 'permission';
	public $tablecol = '';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->table = $CNF->tbl_prefix . $this->table;
		$this->tablecol = $this->getColumnFromTable ( $this->table );


	}

	// TODO: make frront-end to make permission
    public function getPermissions($cond = '',$orderby='', $limit=''){
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
}



?>