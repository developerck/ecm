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
 */
namespace module\servers;
// all the class will use this namespace

class ServersService extends \devlib\AppService{

    public function __construct(){
        parent::__construct();
        $this->role_condition = $this->role_condition;

    }

    //TODO: defination needed
    protected function beforeService($data=array()){

    }

       //TODO: defination needed
    protected function afterService($data=array()){

    }
}