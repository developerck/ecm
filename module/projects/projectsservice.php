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
 */
namespace module\projects;
// all the class will use this namespace

class ProjectsService extends \devlib\AppService{

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