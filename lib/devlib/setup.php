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
// TODO : setup file and check pre condition to run ECM

class Setup {
	public $rootdir;
	public $configiniarr=array();
	// TODO: check if these role exist in table
	public $reqrole_shortname = array (
			"ADMIN",
			"PM",
			"DEV"
	);
	public function __construct() {
		$this->rootdir = dirname ( dirname ( dirname ( __file__ ) ) );
		// check if installed
		if (! $this->isInstalled ()) {
			$this->installECM ();
		}
		// intialize all thing if installed
		$this->init ();
		if (! $this->isUpdated ()) {
			$this->updateECM ();
		}
	}

	/*
	 * check if already installed
	 */
	public function isInstalled() {
		// TODO:check more things before returing like, db, and content of ini if exist
		$configfilepath = $this->rootdir . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.ini';
		return file_exists ( $configfilepath );
	}
	public function installECM() {
		die ( '<h3>Please Install first!</h3><p> Go to  "/plugins/install/" </p>' );
	}

	/**
	 * parse config.ini file
	 *
	 *
	 */

	public function parseConfigIni(){
		global $CNF;
		// setup CNF properties
		$this->setupCNF();
		// file existance is checked in installed function
		if(file_exists( $CNF->basedir . $CNF->DS . 'config' . $CNF->DS . 'config.ini')){
		$conifgarr = parse_ini_file ( $CNF->basedir . $CNF->DS . 'config' . $CNF->DS . 'config.ini', true );
		if(is_array($conifgarr)){
			$this->configiniarr = $conifgarr;

			// check if these property exist
			$neededproperty = array("globals"=>array("wwwroot","conn"=>array("host","port","dbname","username","password","port")));
			//TODO: do it loop bases
			if(array_key_exists ( 'globals', $this->configiniarr )){
				$globalarr = $this->configiniarr ['globals'];
				if (array_key_exists ( 'wwwroot', $globalarr )) {
						$CNF->wwwroot = $globalarr['wwwroot'];
						// checking and appending / in last of wwwroot
						$CNF->wwwroot = rtrim($CNF->wwwroot,"/")."/";
				}else{
					die('required parameter "wwwroot" not found in config.ini under global section!');
				}
				// database
				if (array_key_exists ( 'conn', $globalarr )) {
					$connarr = $globalarr ['conn'];
					if (array_key_exists ( 'host', $connarr)) {
							$CNF->conn['host'] = $connarr['host'];

					}else{
						die('required parameter "host" not found in config.ini under global>conn section!');
					}
					if (array_key_exists ( 'port', $connarr)) {
						$CNF->conn['port'] = $connarr['port'];

					}else{
						die('required parameter "port" not found in config.ini under global>conn section!');
					}
					if (array_key_exists ( 'dbname', $connarr)) {
						$CNF->conn['dbname'] = $connarr['dbname'];

					}else{
						die('required parameter "dbname" not found in config.ini under global>conn section!');
					}
					if (array_key_exists ( 'username', $connarr)) {
						$CNF->conn['username'] = $connarr['username'];

					}else{
						die('required parameter "username" not found in config.ini under global>conn section!');
					}
					if (array_key_exists ( 'password', $connarr)) {
						$CNF->conn['password'] = $connarr['password'];

					}else{
						die('required parameter "password" not found in config.ini under global>conn section!');
					}
					if (array_key_exists ( 'dsn', $connarr)) {
						$CNF->conn['dsn'] = $connarr['dsn'];

					}else{
						die('required parameter "dsn" not found in config.ini under global>conn section!');
					}

				}else{
					die('required parameter "conn" not found in config.ini under global section!');
				}
			}else{
				die('Config.ini is present but required globals key is missing!please check config.dist.ini for more!');
			}
		}else{
			die('Somehow, your config.ini is corrupted! please check!');
		}

		}else{
			die('Config.ini does not exist!');
		}
	}

	public function setupCNF(){
		global $CNF;

		$name = "69 67 77 91 69 97 115 121 32 67 104 97 110 103 101 108 111 103 32 77 97 110 97 103 101 114 93";
		$bby = '100 101 118 101 108 111 112 101 114 99 107';
		$cus = '111 115 46 100 101 118 101 108 111 112 101 114 99 107 64 103 109 97 105 108 46 99 111 109';
		$asign = '101 99 109 64 50 48 49 52';
		$name = explode(" ", $name);
		$name = \devlib\General::asToS($name);
		$bby = explode(" ", $bby);
		$bby = \devlib\General::asToS($bby);
		$cus = explode(" ", $cus);
		$cus = \devlib\General::asToS($cus);
		$asign = explode(" ", $asign);
		$asign = \devlib\General::asToS($asign);
		$CNF->ecm['a'.'p'.'p'.'n'.'a'.'m'.'e'] = $name;
		$CNF->ecm['b'.'r'.'o'.'u'.'g'.'h'.'t'.'b'.'y'] = $bby;
		$CNF->ecm['c'.'o'.'n'.'t'.'a'.'c'.'t'.'u'.'s'] = $cus;
		$CNF->ecm['a'.'p'.'p'.'s'.'i'.'g'.'n'] = 	$asign;
		// creating directory structure under uploaddata
		if(property_exists($CNF, 'tmpdir')){
			if(!is_dir($CNF->tmpdir)){
				if(is_writable($CNF->uploaddata)){
					if(!mkdir($CNF->tmpdir,0777)){
						die("tmp dir could not create under uploaddata directory!");
					}
				}

			}
		}
		if(property_exists($CNF, 'logdir')){
			if(!is_dir($CNF->logdir)){
				if(is_writable($CNF->uploaddata)){
					if(!mkdir($CNF->logdir,0777)){
						die("log dir could not create under uploaddata directory!");
					}
				}

			}
		}



		return ;

	}

	/**
	 * check updates and version
	 */
	public function isUpdated() {
		global $CNF;
		$dbversion = 0;
		$versionfile = $CNF->basedir . $CNF->DS . 'version.xml';
		$link = new \mysqli  ( $CNF->conn ['host'] , $CNF->conn['username'], $CNF->conn['password'],$CNF->conn ['dbname'] , $CNF->conn['port']);
			if ($link->connect_errno >0 ) {
				die ( "Could not connect to mysql with config parameter.Please check config.ini." );
			}
					// Select database

						$sql = "select * from " . $CNF->tbl_prefix . "config where propertyname='version'";
						if ($result = $link->query ( $sql )) {

							while ( $row = $result->fetch_assoc() ) {
								$dbversion = $row ['value'];
							}
						} else {
							die ( 'config table error while checking version!' );
						}



		// check db version
		if (! $dbversion) {
			// we do not have version in table so install this
			$this->installECM ();
		}
		// now parse version.xml

		if (file_exists ( $versionfile )) {
			$parsexml = simplexml_load_file ( $versionfile );
			if ($parsexml) {

				// check if all required tags are available after that check checksum

				if (md5 ( ( string ) $parsexml->product->current_version ) == ( string ) $parsexml->product->current_version_checksum) {
					// if checksum valid
					// check if this update is not done in system
					// TODO: get the last version in system
					$cv = ( int ) $parsexml->product->current_version;
					$rv = ( int ) $parsexml->product->required_version;

					if ($cv != $dbversion) {
						if ($rv) {
							if (md5 ( ( string ) $parsexml->product->required_version ) == ( string ) $parsexml->product->required_version_checksum) {
								if ($dbversion < $rv) {
									die ( 'Your installed version is less then required version to upgrade.' );
								}
							}
						} else {
							return false;
						}
					} else {
						return true;
					}
				} else {
					die ( 'Version File checksum doesnot match!' );
				}
			} else {
				die ( 'Version File is not in proper xml format!' );
			}
		} else {
			die ( 'Version File Does not exist!' );
		}
	}
	public function updateECM() {
		die ( '<h3>Version File changed. So Please update first!</h3><p> Follow the instruction that is with update package! </p>' );
	}

	/*
	 * initialize App and Set various handler
	 */
	function init() {
		global $CNF;
		$this->devlibLoader();
		$this->parseConfigIni();
		$exceptionHandler = new \devlib\Exception ();
		$errorHandler = new \devlib\Error ();

		// intialize shutdown handler
		$this->shutdown();
	}

	/*
	 * devlibLoader
	 *
	 */

	public function devlibLoader(){
			//loading    devlib autoload
			spl_autoload_register(function ($class) {
				// lib either namespace
				global $CNF;
				$parts = explode('\\', $class);
				$classname = array_pop($parts);
				$filename = implode($CNF->DS,$parts);
				// lower case file name and name space is also lowercase to match with foldername
				$filename = $CNF->libdir.$CNF->DS.$filename.$CNF->DS .strtolower($classname).'.php';
				if(file_exists($filename)){
					include_once $filename ;
				}
			});

	}

	/**
	 *  shutdown
	 *  shutdown handler
	 *
	 */
	public function shutdown(){
		// we will not write it
		// so that it would not overwrite f3 shutdown handler

	}
}

$_setup = new \devlib\Setup ();
