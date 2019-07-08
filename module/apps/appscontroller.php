<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      apps
 */

namespace apps;

// all the class will use this namespace

class AppsController extends \devlib\AppController{


     public function __construct(){
        parent::__construct();
        $this->module = 'apps';
        $this->baseurl =$this->baseurl.$this->module;


    }

     //TODO: defination needed
    protected function beforeController($data=array()){

    }

       //TODO: defination needed
    protected function afterController($data=array()){

    }
}