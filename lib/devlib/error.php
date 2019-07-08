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

class Error {
	protected $error_codes;
	protected $warning_codes;
	protected $error_names;
	protected $error_numbers;
	protected $errno;
	protected $errstr;
	protected $errfile;
	protected $errline;
	protected $errcontext;

	/*
	 * load required file
	 */
	public function __construct() {
		global $CNF;
		if($CNF->debug){
		    error_reporting(E_ALL);
		}else{
		    error_reporting(E_ERROR | E_WARNING | E_PARSE);
		}
		$this->error_codes = E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR;
		$this->warning_codes = E_WARNING | E_CORE_WARNING | E_COMPILE_WARNING | E_USER_WARNING;

		// associate error codes with errno...
		$this->error_names = array (
				'E_ERROR',
				'E_WARNING',
				'E_PARSE',
				'E_NOTICE',
				'E_CORE_ERROR',
				'E_CORE_WARNING',
				'E_COMPILE_ERROR',
				'E_COMPILE_WARNING',
				'E_USER_ERROR',
				'E_USER_WARNING',
				'E_USER_NOTICE',
				'E_STRICT',
				'E_RECOVERABLE_ERROR'
		);

		for($i = 0, $j = 1, $num = count ( $this->error_names ); $i < $num; $i ++, $j = $j * 2) {
			$this->error_numbers [$j] = $this->error_names [$i];
		}
		// intiate error handler
		set_error_handler ( array (
				$this,
				"loadErrorHandler"
		) );
	}

	/*
	 * loading error handler based
	 */
	public function loadErrorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
		global $CNF;

		// Load the library
		$this->errno = $errno;
		$this->errstr = $errstr;
		$this->errfile = $errfile;
		$this->errline = $errline;
		$this->errcontext = $errcontext;
		//TODO: do according to error level
		// TODO: set to work on debug level and settings
		echo '<div class="alert alert-danger">';
		if ($CNF->debug) {

			echo $this->error_msg_detailed ((int)$CNF->debug);

			// For More Detail Error
			//echo $this->detailedError();
		} else {
			echo 'Some Internal Error Ouccred!';
			// logging error incase dubuggin disabled
			$str = $this->error_msg_detailed ();
			$this->logError ( $str );
		}
		echo '</div>';
		require_once ($CNF->uidir . $CNF->DS . 'footer.php');
		if($CNF->debug){
		    //return true;
		    die();
		}else{
		    die();
		}
		/* Don't execute PHP internal error handler */

	}

	/*
	 * loggging Error
	 */
	public function logError($str, $type = "error") {
		global $CNF;
		$type = $type=='' ? "error" : $type;
		require_once $CNF->customlibdir . $CNF->DS . 'log.php';
		$logger = new \devlib\Log ();
		$logger->logMe ( $str, $type );
	}

	/*
	 * custom Error
	 */
	public function triggerCustomError($str) {
		// trigggering custom error
		trigger_error ( $str, E_USER_ERROR );
	}
	function error_msg_detailed($debuglevel=1) {

		$color = "RED";

		$message = '';
		$message .= "<pre style='color:$color;'>\n\n" . PHP_EOL;
		$message .= "file: " . print_r ( $this->errfile, true ) . "\n" . PHP_EOL;
		$message .= "line: " . print_r ( $this->errline, true ) . "\n\n" . PHP_EOL;
		$message .= "code: " . print_r ( $this->error_numbers [$this->errno], true ) . "\n" . PHP_EOL;
		$message .= "message: " . print_r ( $this->errstr, true ) . "\n\n" . PHP_EOL;
		if($debuglevel >=2){
		$message .= "context: " . print_r ( $this->errcontext, true ) . "\n\n" . PHP_EOL;
		}
		if($debuglevel >=3){
			$message .= "backtrace: " . print_r ( debug_backtrace (), true ) . "\n\n" . PHP_EOL;
		}
		$message .= "</pre>\n" . PHP_EOL;

		return $message;
	}

}
