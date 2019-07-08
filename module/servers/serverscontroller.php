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

class ServersController extends \devlib\AppController{


     public function __construct(){
        parent::__construct();
        $this->module = 'servers';
        $this->baseurl =$this->baseurl.$this->module;



    }
    //TODO: defination needed
    protected function beforeController($data=array()){

    }

       //TODO: defination needed
    protected function afterController($data=array()){

    }


}