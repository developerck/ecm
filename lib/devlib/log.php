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

class Log {
	public $logdir;
	public function __construct() {
		global $CNF;
		$this->logdir = $CNF->logdir;
	}
	private function _checkPrerequisite() {
		if (! is_dir ( $this->logdir )) {
			throw new Exception ( 'Logging Directory Not Present' );
		}
		if (! is_writable ( $this->logdir )) {
			throw new Exception ( 'Logging Directory Not Writable' );
		}
	}
	public function LogMe($message, $type = "general") {
		try {
			$this->_checkPrerequisite ( $type );
			$logfile = $this->logdir . $type . ".log";
			$logmsg = time ();
			$logmsg .= "     ";
			$logmsg .= date ( "d/m/Y H:i:s" );
			$logmsg .= "     ";
			$logmsg .= $message;
			$logmsg .= PHP_EOL;
			return file_put_contents ( $logfile, $logmsg, FILE_APPEND );
		} catch ( Exception $e ) {
			throw new Exception ( $e->getMessage () );
		}
	}
}