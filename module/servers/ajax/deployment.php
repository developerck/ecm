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
 * @controller  deployment
 */
namespace module\servers\ajax;

class Deployment extends \module\servers\ServersController
{

  	protected $_serv;
   	protected $_serv_rv;
   	protected $_serv_dps;
	public function __construct() {

		parent::__construct ();

		$this->_serv = new \module\servers\service\Deployment();
		$this->_serv_dps = new \module\servers\service\DeploymentSteps();
		$this->_serv_rv = new \module\projects\service\ReleaseVersion();

		$this->baseurl = $this->baseurl . '/deployment/';
		$this->basemethod = 'base';
	}


	public function base() {
		throw new \devlib\Exception('Ajax Without Action On Deployment Controller!');
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

		$data=  $this->_serv_rv->getReleaseVersionArr($procond);

		return $data;

	}

	public function deploychangelogs(){
		global $CNF, $USER;
		//$projectid= \devlib\AppController::getKeyValueRequired ( 'projectid' );
		$param = $this->f3->get ( 'POST' );

		$param = json_decode($param['postdata'],true);
		$noOfChk = count($param);

		foreach($param as $val){
			$id =$val['id'];
			$name= $val['name'];
			if($id != ''){

				if($val['data']){

					$_SESSION['search']['deployment']['exportsel'][$id] = $name;
				}else{
					if(array_key_exists($id,$_SESSION['search']['deployment']['exportsel'])){
						unset($_SESSION['search']['deployment']['exportsel'][$id]);
					}
				}
				$response['flag']= true;

			}
		}
		$response['selected'] =$_SESSION['search']['deployment']['exportsel'];
		return json_encode($response);

	}

	public function getStepsByServer() {
		global $CNF, $USER;

		$param = $this->f3->get ( 'POST' );

		$param = json_decode ( $param ['postdata'], true );

		if (! isset ( $param ['server_id'] )) {
			throw new \devlib\Exception ( 'Parameter Not Found!', 'AJAX' );
		}

		$data = $this->_serv_dps->getStepsByServer ( $param ['server_id'] );
		$this->f3->set ( 'SESSION.servers.deployment.server_id', $param ['server_id'] );
		if (empty ( $data )) {
			return $data;
		} else {
			$this->form ['data'] ['steps'] = $data;
			$this->view = 'deploymentsteps.php';
		}
	}

	public function getProjectServer(){
		global $CNF, $USER;

		//$projectid= \devlib\AppController::getKeyValueRequired ( 'projectid' );
		$param = $this->f3->get ( 'POST' );

		$param = json_decode($param['postdata'],true);

		if(!isset($param['project_id'])){
			throw  new \devlib\Exception('Parameter Not Found!','AJAX');
		}
		//TODO:Improve This
		if ($USER ['user_role'] ['shortname'] == 'ADMIN') {
		    $srvcond = " LEFT JOIN ".$this->_serv->tbl_su ." su ON su.server_id=s.id where s.isactive=1 and s.project_id=".$param['project_id'].' group by s.id';
		} else {
		    $srvcond = " RIGHT JOIN ".$this->_serv->tbl_su ." su ON su.server_id=s.id where s.isactive=1 and s.project_id=".$param['project_id'].' and su.user_id = ' . $USER ['uid'];
		}

		$data=  $this->_serv->getAssignServerByCond($srvcond);

		return $data;

	}

	public function deployedPreview(){
		global $CNF, $USER;
		//$serverid= \devlib\AppController::getKeyValueRequired ( 'serverid' );
		$param = $this->f3->get ( 'POST' );
		$this->view = 'deployedpreview.php';

		$param = json_decode($param['postdata'],true);

		if(!isset($param['id'])){
			throw  new \devlib\Exception('Parameter Not Found!','AJAX');
		}

		$data = $this->_serv->getDeployedStepsByDPID($param['id']);
		$dpdata = $this->_serv->getDeployedRecordDPID($param['id']);
		$this->form ['data']['steps'] =$data;
		$this->form ['data']['comment'] =$dpdata ['comment'];


	}
}
