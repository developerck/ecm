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

class Exception extends \Exception {
	public function __construct($msg = null, $type = "THROW") {
		parent::__construct ( $msg );
		if ($type == 'THROW') {
			set_exception_handler ( array (
					$this,
					'handleException'
			) );
		} else if ($type == 'AJAX') {
			set_exception_handler ( array (
					$this,
					'handleAJAXException'
			) );
		} else if ($type == 'CONTROLLER') {
			set_exception_handler ( array (
					$this,
					'handleControllerException'
			) );
		} else if ($type == 'SERVICE') {
			set_exception_handler ( array (
					$this,
					'handleServiceException'
			) );
		} else {
			set_exception_handler ( array (
					$this,
					'handleException'
			) );
		}
	}
	public function handleException($exception = null) {
		global $CNF;
		// checking if request was from ajax
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->handleAJAXException($exception);
		}else{
			echo '<div class="alert alert-danger">';
			print_R ( $exception->getMessage () );
			echo '</div>';
			echo $this->detailedException ( $exception );
			require_once ($CNF->uidir . $CNF->DS . 'footer.php');
		}
	}
	public function handleAJAXException($exception = null) {
		$responsearr = array();
		$responsearr['_ecmajaxexception'] =  $exception->getMessage () .$this->detailedException ( $exception );
		echo json_encode($responsearr);

	}
	public function handleControllerException($exception = null) {

		// just call the handle exception method no need to extra work
		$this->handleException ( $exception );
	}
	public function handleServiceException($exception = null) {
		// log exception also
		$this->logException ( $exception, 'service' );
		// just call the handle exception method no need to extra work
		$this->handleException ( $exception );
	}
	public function detailedException($exception, $overwrite = false) {
		global $CNF;
		$debuglevel = property_exists ( $CNF, 'debug' ) ? $CNF->debug : 0;
		$str = '';
		if ($debuglevel || $overwrite) {
			// TODO: just need to set debug level

			if ($exception instanceof Exception) {
				$str .= "<div class=\"well\">" . PHP_EOL;
				$str .= "<code>";
				$str .= "Code: " . $exception->getCode () . "<br/>";
				$str .= "</code>" . PHP_EOL;
				$str .= "<code>";
				$str .= "File: " . $exception->getFile () . "<br/>";
				$str .= "</code>" . PHP_EOL;
				$str .= "<code>";
				$str .= "Line: " . $exception->getLine () . "<br/>";
				$str .= "</code>" . PHP_EOL;
				$str .= "<code>";
				$str .= "Trace: \n</code>" . $exception->getTraceAsString () . "<br/>";
				$str .= "" . PHP_EOL;
				$str .= "</div>" . PHP_EOL;
			} else {
				return false;
			}
		}
		return $str;
	}
	/*
	 * logException
	 */
	public function logException($exception, $type = 'general') {
		global $CNF;
		$message = $this->detailedException($exception, true);
		$type = $type =='' ? "general" : $type;
		require_once $CNF->customlibdir . $CNF->DS . 'log.php';
		$logger = new \devlib\Log ();
		$logger->logMe ( $message, $type );
	}
}
?>
