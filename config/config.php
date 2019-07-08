<?php
/**
 * please do not change this file or any configuration it may
 * lead to fatal error.
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */

if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');
global $CNF;
$CNF = new stdClass();
// need to set this path when things changes on setup


//version


//Developement
$CNF->debug = 0; //0|1
$CNF->maintainmode = 0; //0|1

//Path
$CNF->DS =PHP_OS == "Windows" || PHP_OS == "WINNT" ?  "\\" :  "/";
$CNF->basedir = dirname(dirname(realpath(__file__)));
$CNF->uploaddata   = $CNF->basedir.$CNF->DS."uploaddata";
$CNF->tmpdir   = $CNF->uploaddata.$CNF->DS."tmp";
$CNF->logdir   = $CNF->uploaddata.$CNF->DS."logs".$CNF->DS;
$CNF->libdir = $CNF->basedir.$CNF->DS."lib";
$CNF->baselibdir = $CNF->libdir .$CNF->DS."base";
$CNF->customlibdir = $CNF->libdir.$CNF->DS."devlib";
$CNF->vendorlibdir = $CNF->libdir.$CNF->DS."vendor";
$CNF->uidir =  "ui";
$CNF->moduledir =  "module";
$CNF->plugindir =  "plugin";
$CNF->textdir   = $CNF->basedir.$CNF->DS."lang".$CNF->DS.'en'.$CNF->DS;

// settings
$CNF->encryptionkey  = "devlib_ecm";

//table prefix
$CNF->tbl_prefix  = "ecm_";

// global namespace
$CNF->global_nampspace = 'ecm';

// setup lib

require_once($CNF->customlibdir.$CNF->DS.'setup.php');

