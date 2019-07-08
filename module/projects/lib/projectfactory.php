<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      projects
 *
 */
namespace module\projects\lib;


class ProjectFactory{
	private $pid;
	public $proobj;

	public function __construct($pid){
		if(!$pid){
			throw new \devlib\Exception('Project ID is should be present for Project Factory!');
		}
		$this->pid = $pid;
		$this->_initProjectObject();

	}

	/*
	 * Return Project Object Wiht Complete Detail
	 *
	 *
	 */
	public function _initProjectObject(){
		// get project detail
		global $CNF, $USER;
		$project = new \module\projects\service\Project();


        $this->proobj['pid'] = $this->pid;
        $prodata = $project->getProjectByID($this->pid );
        $this->proobj['project_basic'] =$prodata;
        $this->proobj['project_assign']= $project->getAssignUserByProjectId($this->pid);
        //TODO: extends for servers and scm and db detail


	}
}