<?php

/**
 *
 *
 * @server 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      servers
 * @controller  deploymentsteps
 */
namespace module\servers\ajax;

class DeploymentSteps extends \module\servers\ServersController {
	protected $_serv;
	protected $_serv_server;
	public function __construct() {
		parent::__construct ();
		
		$this->_serv = new \module\servers\service\DeploymentSteps ();
		$this->_serv_server = new \module\servers\service\Server ();
		$this->baseurl = $this->baseurl . '/deploymentsteps/';
		$this->basemethod = 'base';
	}
	public function base() {
		throw new \devlib\Exception ( 'Ajax Without Action On server Controller!', 'AJAX' );
	}
	public function getStepsByServer() {
		global $CNF, $USER;
		
		$param = $this->f3->get ( 'POST' );
		
		$param = json_decode ( $param ['postdata'], true );
		
		if (! isset ( $param ['server_id'] )) {
			throw new \devlib\Exception ( 'Parameter Not Found!', 'AJAX' );
		}
		
		$data = $this->_serv->getStepsByServer ( $param ['server_id'] );
		$this->f3->set ( 'SESSION.servers.customize.server_id', $param ['server_id'] );
		if (empty ( $data )) {
			return $data;
		} else {
			$this->form ['data'] ['steps'] = $data;
			$this->view = 'deploymentsteps.php';
		}
	}
	public function addstepview() {
		global $CNF, $USER;
		require_once ($CNF->vendorlibdir . $CNF->DS . 'zebraform' . $CNF->DS . 'Zebra_Form.php');
		
		$this->view = 'addstep.php';
		$param = $this->f3->get ( 'POST' );
		
		$param = $param ['postdata'];
		
		$this->filterInputData ( $param );
		if (! isset ( $param ['server_id'] )) {
			throw new \devlib\Exception ( 'Server Id is required!', 'AJAX' );
		}
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'stepform' );
		if (! isset ( $param ['stepid'] )) {
			$this->form ['data'] ['tablecol'] = $this->_serv->tablecol;
		} else {
			$this->form ['data'] ['tablecol'] = $this->_serv->getServerStepDetail ( $param ['server_id'], $param ['stepid'] );
		}
		$this->form ['data'] ['serverdetail'] = $this->_serv_server->getServerById ( $param ['server_id'] );
	}
	public function savestep() {
		global $CNF, $USER;
		$returnarr = array ();
		
		$param = $this->f3->get ( 'POST' );
		// decode json
		$param = json_decode ( $param ['postdata'], true );
		$param = json_decode ( $param, true );
		
		// now make new params array
		$params = array ();
		foreach ( $param as $record ) {
			
			$params [$record ['name']] = $record ['value'];
		}
		$this->filterInputData ( $params );
		
		if (! isset ( $params ['server_id'] )) {
			return array (
					"status" => false,
					'msg' => 'Server Id Not Found!' 
			);
		}
		$stepsforserver = $this->_serv->getStepsByServer ( $params ['server_id'] );
		
		$savearr = array ();
		if (! $params ['id']) {
			// save condition
			$stepid = 1;
			$sequence = 1;
			if (count ( $stepsforserver ) != 0) {
				// not first step
				$stepidarr = array ();
				$stepsequencearr = array ();
				foreach ( $stepsforserver as $sterecord ) {
					$stepidarr [] = ( int ) $sterecord ['stepid'];
					$stepsequencearr [] = ( int ) $sterecord ['stepsequence'];
				}
				
				$stepid = max ( $stepidarr );
				$stepid = $stepid + 1;
				$sequence = max ( $stepsequencearr );
				$sequence = $sequence + 1;
			}
			
			$savearr ['server_id'] = $params ['server_id'];
			$savearr ['stepid'] = $stepid;
			$savearr ['stepinputname'] = "step_" . $stepid . "_" . $params ['server_id'];
			$savearr ['steplabel'] = $params ['steplabel'];
			$savearr ['stepsequence'] = $sequence;
			$savearr ['stepinputtype'] = $params ['stepinputtype'] == 'none' ? '' : $params ['stepinputtype'];
			$savearr ['steprequired'] = $params ['steprequired'];
			$savearr ['stepcomment'] = $params ['stepcomment'];
			$savearr ['creationtime'] = time ();
			$savearr ['createdby'] = $USER ['uid'];
			if ($this->_serv->saveStep ( $savearr )) {
				$this->setSessionMessage ( 'Step has added succesfully!', array (
						"viewname" => 'customizesteps.php' 
				) );
				
				return array (
						"status" => true,
						'msg' => '' 
				);
			} else {
				return array (
						"status" => false,
						'msg' => 'Information could not save!' 
				);
			}
		} elseif ($params ['id']) {
			// edit condition
			
			$savearr ['steplabel'] = $params ['steplabel'];
			
			$savearr ['stepinputtype'] = $params ['stepinputtype'] == 'none' ? '' : $params ['stepinputtype'];
			$savearr ['steprequired'] = $params ['steprequired'];
			$savearr ['stepcomment'] = $params ['stepcomment'];
			$savearr ['updationtime'] = time ();
			$savearr ['updatedby'] = $USER ['uid'];
			if ($this->_serv->updateStep ( $savearr, $params ['id'] )) {
				$this->setSessionMessage ( 'Step has updated succesfully!', array (
						"viewname" => 'customizesteps.php' 
				) );
				
				return array (
						"status" => true,
						'msg' => '' 
				);
			} else {
				return array (
						"status" => false,
						'msg' => 'Information could not save!' 
				);
			}
		}
	}
	
	
	public function deletestep() {
		global $CNF, $USER;
		$returnarr = array ();
	
		$param = $this->f3->get ( 'POST' );
		$param = $param ['postdata'];
		$param = json_decode($param,true);
		$this->filterInputData ( $param );
		if (! isset ( $param ['id'] )) {
			throw new \devlib\Exception ( ' Id is required!', 'AJAX' );
		}
	
		
			if ($this->_serv->deleteStep ( $param ['id']  )) {
				$this->setSessionMessage ( 'Step has deleted succesfully!', array (
						"viewname" => 'customizesteps.php'
				) );
	
				return array (
						"status" => true,
						'msg' => ''
				);
			}  else {
				return array (
						"status" => false,
						'msg' => 'Step could Not delete!' 
				);
			}
	}
	
	
	public function savestepsequence() {
		global $CNF, $USER;
		$returnarr = array ();
	
		$param = $this->f3->get ( 'POST' );
		$param = $param ['postdata'];
		$param = json_decode($param,true);
		
		$this->filterInputData ( $param );
		if (! isset ( $param ['server_id'] )) {
			throw new \devlib\Exception ( 'Server Id is required!', 'AJAX' );
		}
		if(!isset($param['steparr']) || empty($param['steparr'])){
			throw new \devlib\Exception ( 'Empty Step Array!', 'AJAX' );
		}
	
		$count =1;
		$flag= true;
		foreach($param['steparr'] as $step_table_id){
			$savearr = array();
			$savearr['stepsequence']=$count;
			if(!$this->_serv->updateStep ( $savearr,$step_table_id  )){
				$flag = false;
				break;
			}
			$count++;
		}
	
		if ($flag) {
			$this->setSessionMessage ( 'Step Sequence changed succesfully!', array (
					"viewname" => 'customizesteps.php'
			) );
	
			return array (
					"status" => true,
					'msg' => ''
			);
		}  else {
			return array (
					"status" => false,
					'msg' => 'Step could Not Save!'
			);
		}
	}
}
