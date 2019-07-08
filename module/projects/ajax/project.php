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
 * @controller  project
 */
namespace module\projects\ajax;

class Project extends \module\projects\ProjectsController
{

   	protected $_serv;
	public function __construct() {

		parent::__construct ();

		$this->_serv = new \module\projects\service\Project();

		$this->baseurl = $this->baseurl . '/project/';
		$this->basemethod = 'base';
	}


	public function base() {
		throw new \devlib\Exception('Ajax Without Action On Project Controller!');
	}

	public function getAssignedUserOnProject(){
		global $CNF, $USER;
			//$projectid= \devlib\AppController::getKeyValueRequired ( 'projectid' );
			$param = $this->f3->get ( 'POST' );

			$param = json_decode($param['postdata'],true);

			if(!isset($param['projectid'])){
				throw  new \devlib\Exception('Parameter Not Found!','AJAX');
			}
			$procond= 'where  project_id = ' . $param['projectid'];

			$data=  $this->_serv->getAssignUserOnProject($procond);
			$retarr = array();
			foreach ($data as $value){
				$retarr[$value['user_id']]=$value['user_id'];
			}
			return $retarr;

	}

	public function getProjectInfoById(){
	    global $CNF, $USER;
	    //$serverid= \devlib\AppController::getKeyValueRequired ( 'serverid' );
	    $param = $this->f3->get ( 'POST' );
	    $this->view = 'prodetail.php';

	    $param = json_decode($param['postdata'],true);

	    if(!isset($param['id'])){
	        throw  new \devlib\Exception('Parameter Not Found!','AJAX');
	    }


	    $pro = new \module\projects\lib\projectFactory($param['id']);
	    $this->form ['data'] =$pro->proobj;


	}

}
