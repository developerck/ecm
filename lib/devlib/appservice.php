<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */
namespace devlib;

abstract class AppService implements ServiceInterface{
    public $DBH;
    public $role_condition;

    public function __construct(){
       global $CNF;
        $this->DBH = $CNF->DB;

    }

    abstract protected function beforeService();

    abstract protected function afterService();



    protected function logServiceException($ex,$type='SERVICE'){
        //TODO: REMOVE AFTER COMPLETTION
			global $CNF;
			if($CNF->debug){
             throw new \devlib\Exception($ex,$type);
			}else{
				throw new \devlib\Exception('Service Exception!',$type);
			}
    }

    protected function getColumnFromTable($tablename){
    	try {

	    	$sth = $this->DBH->prepare("DESCRIBE ".$tablename);
	    	$sth->execute();
	    	$table_fields = $sth->fetchAll(\PDO::FETCH_COLUMN);
			// intialized with blank data

	    	$returnarr = array();
	    	foreach($table_fields as $val){
	    		$returnarr[$val] = '';
	    	}
			return $returnarr;

    	} catch ( \PDOException $e ) {
    		// passing pdo exception object to log
    		$this->logServiceException ( $e );
    	}
    }



}

interface ServiceInterface{

}