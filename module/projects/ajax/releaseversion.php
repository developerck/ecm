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
 * @controller  releaseversion
 */
namespace module\projects\ajax;

class ReleaseVersion extends \module\projects\ProjectsController
{

   	protected $_serv;
	public function __construct() {

		parent::__construct ();

		$this->_serv = new \module\projects\service\ReleaseVersion();

		$this->baseurl = $this->baseurl . '/releaseversion/';
			$this->basemethod = 'base';
	}


	public function base() {
		throw new \devlib\Exception('Ajax Without Action On Project Controller!');
	}

	public function getreleaseversion(){
		global $CNF, $USER;
		//$projectid= \devlib\AppController::getKeyValueRequired ( 'projectid' );
		$param = $this->f3->get ( 'POST' );

		$param = json_decode($param['postdata'],true);

		if(!isset($param['project_id'])){
			throw  new \devlib\Exception('Parameter Not Found!','AJAX');
		}
		$procond= 'where  islocked =0 and project_id = ' . $param['project_id'];

		$data=  $this->_serv->getReleaseVersionArr($procond);

		return $data;

	}


	public function getRVInfo(){
		// TODO: Implementaion method for detail modal box

	}
}
