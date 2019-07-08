<?php

/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */
namespace devlib;
// For SVN Related work if
// work in case if svn extension is loaded
class SVNClient{
	private $svnurl = '';
	private $username = '';
	private $password = '';
	public $reposls = array();

	public function __construct($url, $username='', $password=''){
		// check if extension loaded
		if(!extension_loaded ( 'svn' )){
			throw new \devlib\Exception('SVN Extension not installed in system');
		}

		$this->svnurl = rtrim($url,"/")."/";
		$this->username = $username;
		$this->password = $password;
		$this->init();

	}

	public function init(){

		if($this->username !=''){
			svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_USERNAME, $this->username);
		}
		if($this->password !=''){
		    svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_PASSWORD, $this->password);
		}

		/*
		if($this->reposls = svn_ls($this->svnurl)){
			throw new \devlib\Exception('Could not get the directory Structure');
		}
		*/
	}

	public function checkout ( $targetpath){
		if($targetpath ==''){
			throw new \devlib\Exception('Target path is blank!');
		}
		if(!is_writable($targetpath)){
		    throw new \devlib\Exception('Target path not writable!');
		}
		if(!svn_checkout (  $this->svnurl , $targetpath )){
			throw new \devlib\Exception('Could not checkout!');
		}

	}
}