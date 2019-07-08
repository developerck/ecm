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
 * @controller  changelog
 */
namespace module\projects\ajax;

class Changelog extends \module\projects\ProjectsController
{

   	protected $_serv;
   	protected $_serv_rv;
	public function __construct() {

		parent::__construct ();

		$this->_serv = new \module\projects\service\Changelog();
		$this->_serv_rv = new \module\projects\service\ReleaseVersion();
		$this->baseurl = $this->baseurl . '/changelog/';
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
	    $rvdata=  $this->_serv_rv->getReleaseVersionArr($procond);

	    return $rvdata;

	}

	public function getallreleaseversion(){
	    global $CNF, $USER;
	    //$projectid= \devlib\AppController::getKeyValueRequired ( 'projectid' );
	    $param = $this->f3->get ( 'POST' );

	    $param = json_decode($param['postdata'],true);

	    if(!isset($param['project_id'])){
	        throw  new \devlib\Exception('Parameter Not Found!','AJAX');
	    }
	    $procond= 'where  project_id = ' . $param['project_id'];
	    $rvdata=  $this->_serv_rv->getReleaseVersionArr($procond);

	    return $rvdata;

	}

	public function getRVAndLabel(){
		global $CNF, $USER;
		//$projectid= \devlib\AppController::getKeyValueRequired ( 'projectid' );
		$param = $this->f3->get ( 'POST' );

		$param = json_decode($param['postdata'],true);

		if(!isset($param['project_id'])){
			throw  new \devlib\Exception('Parameter Not Found!','AJAX');
		}
		$procond= 'where  islocked =0 and project_id = ' . $param['project_id'];

		$rvdata=  $this->_serv_rv->getReleaseVersionArr($procond);
		$labeldata=  $this->_serv->getChangelogLabelArrByProjectId($param['project_id']);
		return array("rv"=>$rvdata,"label"=>$labeldata);

	}

	public function exportchangelogs(){
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

                $_SESSION['search']['changelog']['exportsel'][$id] = $name;
            }else{
              if(array_key_exists($id,$_SESSION['search']['changelog']['exportsel'])){
                    unset($_SESSION['search']['changelog']['exportsel'][$id]);
                }
            }
            $response['flag']= true;

        }
   	}
   	$response['selected'] =$_SESSION['search']['changelog']['exportsel'];
    return json_encode($response);

	}


	public function getChangelogInfoById(){
	    global $CNF, $USER;
	    //$serverid= \devlib\AppController::getKeyValueRequired ( 'serverid' );
	    $param = $this->f3->get ( 'POST' );
	    $this->view = 'changelogdetail.php';

	    $param = json_decode($param['postdata'],true);

	    if(!isset($param['id'])){
	        throw  new \devlib\Exception('Parameter Not Found!','AJAX');
	    }


	    $data = $this->_serv->getChangelogById($param['id']);
	    $this->form ['data'] =$data;


	}

	/*
	 * export Single Changelog
	 *
	 */
	public function exportSingle(){
	    global $CNF, $USER;
	    $filename = pDate(time(),"d_m_Y_H_i_s")."_changelog.txt";
	    $filepath = $CNF->tmpdir. $CNF->DS.$filename;
	    $param = $this->f3->get ( 'POST' );

	    $param = json_decode($param['postdata'],true);

	    if(!isset($param['issueid'])){
	        throw  new \devlib\Exception('Parameter Not Found!','AJAX');
	    }
		$obj = new \module\projects\lib\ChangelogLib();
		$data = $obj -> exportTxtChangelog($param['issueid']);
		if(!is_dir($CNF->tmpdir)){
			throw new \devlib\Exception('tmp dir not found!','AJAX');
		}
		if(!is_writable($CNF->tmpdir)){
		    throw new \devlib\Exception('tmp dir not writable!','AJAX');
		}
		if(file_exists($filepath)){
			// removing existing file
			unlink($filepath);
		}
		if(file_put_contents ( $filepath, $data, FILE_APPEND ) !== false){
			return array("path"=>$CNF->wwwroot.'uploaddata/tmp/'. $filename);
		}else{
			return false;
		}

	}

	/*
	 * export Multiple Changelog
	*
	*/
	public function exportMultiple(){
	    global $CNF, $USER;
	    $foldername = p(time(),"d_m_Y_H_i_s")."_changelog";
	    $filepath = $CNF->tmpdir. $CNF->DS.$filename;
	    $param = $this->f3->get ( 'POST' );

	    $param = json_decode($param['postdata'],true);

	    if(!isset($param['issueid'])){
	        throw  new \devlib\Exception('Parameter Not Found!','AJAX');
	    }
	    $obj = new \module\projects\lib\ChangelogLib();
	    $data = $obj -> exportTxtChangelog($param['issueid']);
	    if(!is_dir($CNF->tmpdir)){
	        throw new \devlib\Exception('tmp dir not found!','AJAX');
	    }
	    if(!is_writable($CNF->tmpdir)){
	        throw new \devlib\Exception('tmp dir not writable!','AJAX');
	    }
	    if(file_exists($filepath)){
	        // removing existing file
	        unlink($filepath);
	    }
	    if(file_put_contents ( $filepath, $data, FILE_APPEND ) !== false){
	        return $filepath;
	    }else{
	        return false;
	    }

	}

}
