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
require_once ($CNF->customlibdir . $CNF->DS . 'global_function.php');
// creating view that will include file under ui folder

// loading the class automatically
\devlib\General::classAutoload ();
global $USER;
$uid = $f3->get ( 'SESSION.user' );

if (empty ( $uid )) {
	// TODO: Login required in ajax
	throw new \devlib\Exception ( 'Login Required!' ,'AJAX');
} else {
	global $USER;
	$uid = $f3->get('SESSION.user.uid');
	if(!$uid){
	    // if we do not find uid in session
	    session_destroy();
	    $f3->reroute($CNF->wwwroot);
	}
	$user = new \module\users\lib\UserFactory($uid);
	$USER = $user->userobj;

	$params = $f3->get ( 'PARAMS' );
	$module = isset ( $params ['module'] ) ? $params ['module'] : '';
	$controller = isset ( $params ['controller'] ) ? $params ['controller'] : '';
	$action = isset ( $params ['action'] ) ? $params ['action'] : '';

	if ($module != '' && $controller != '') {
		// now check file existance
		// getting first alphanumeric with_ string
		preg_match ( '/\w*/', $controller, $matches );
		$controller = $matches [0];
		if (file_exists ( $CNF->basedir . $CNF->DS . $CNF->moduledir . $CNF->DS . $module . $CNF->DS . 'ajax' . $CNF->DS . $controller . '.php' )) {
			$className = ucfirst ( $controller );
			$className = '\\module\\' . $module . '\\ajax\\' . $className;

			// including controller harcoded
			if (class_exists ( $className )) {
				// construct the code string and evaluate it.
				$cntrlobj = new $className ();

				$action = $action == '' ? $cntrlobj->basemethod : $action;
				// getting first alphanumeric with_ string
				preg_match ( '/\w*/', $action, $matches );
				$action = $matches [0];
				if (method_exists ( $className, $action )) {



						$returnvalue = $cntrlobj->$action ();
						// including set ui
						if (is_array ( $cntrlobj->view )) {
							foreach ( $cntrlobj->view as $viewname ) {
								require_once ($CNF->basedir . $CNF->DS . $CNF->moduledir . $CNF->DS . $module . $CNF->DS . 'ui' . $CNF->DS . $controller . $CNF->DS . $viewname);
							}
						} else if ($cntrlobj->view != '') {
							require_once ($CNF->basedir . $CNF->DS . $CNF->moduledir . $CNF->DS . $module . $CNF->DS . 'ui' . $CNF->DS . $controller . $CNF->DS . $cntrlobj->view);
						}else{

							echo sendResponse($returnvalue);
						}





				} else {
					throw new \devlib\Exception ( 'Wrong Action!', "AJAX" );
				}

				// $cntrlobj
			} else {
				// Controller Not Found
				throw new \devlib\Exception ( 'Wrong Controller!', "AJAX" );
			}
		} else {

			throw new \devlib\Exception ( 'Wrong Url!', "AJAX" );
		}
	} else {

		throw new \devlib\Exception ( 'No Module and No Controller defined!', "AJAX" );
	}
}

