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
class RolePermission extends \module\users\UsersService{

    public $table = 'role_perm';
	public $tablecol = '';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->table = $CNF->tbl_prefix . $this->table;
		$this->tablecol = $this->getColumnFromTable ( $this->table );


	}


	// TODO: make frront-end to make permission
    public function getPermissionByRoleId($role_id){
    	global $CNF;
    	try {

    	$data = array();
    	$sql = "SELECT t2.* FROM ".$CNF->tbl_prefix."role_perm as t1
                JOIN ".$CNF->tbl_prefix."permissions as t2 ON t1.permissions_id = t2.id
                WHERE t1.roles_id = :role_id";
        $sth = $this->DBH->prepare($sql);
        $sth->execute(array(":role_id" => $role_id));
        $data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
       	return $data;

    	} catch ( \PDOException $e ) {
    		$this->logServiceException ( $e );
    	}
    }
    
    
    // TODO: make frront-end to make permission
    public function getRolesByPermission($module='',$controller='',$action=''){
    	global $CNF;
    	try {
    		$cond = array();
    		$cond[] = $module;
    		$cond[] = $controller ;
    		$cond[] = $action;
    		if($module){
    			$cond[] =  ' p.module = '.$module;
    		}
    		if($controller){
    			$cond =  ' p.controller = '.$controller;
    		}
    		if($action){
    			$cond =  ' p.action = '.$action;
    		}
    		
    		$cond = implode(" and ", $cond);
    		if($cond !=''){
    			$cond = " Where ".$cond;
    		}
    		
    		$data = array();
    		$sql = "SELECT rp.* FROM ".$this->table." as rp
    		LEFT JOIN ".$CNF->tbl_prefix."permissions as p ON rp.permissions_id = p.id
    		$cond";
    		$sth = $this->DBH->prepare($sql);
    		$sth->execute();
    		$data = $sth->fetchAll ( \PDO::FETCH_ASSOC );
    		return $data;
    
    	} catch ( \PDOException $e ) {
    		$this->logServiceException ( $e );
    	}
    }
    
}



?>