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

/*
 * We are putting header here
 */
require_once ($CNF->uidir . $CNF->DS . "header.php");
/*
 * We are putting contianer here
 */

//loading the class automatically
\devlib\General::classAutoload();

$isloogedin = $f3->get('SESSION.user.uid');



if (!$isloogedin) {
	// if user is not login then load the login page
	$error_msg = '';
	//require_once ($CNF->moduledir . $CNF->DS . 'users' . $CNF->DS . 'controller' . $CNF->DS . 'user.php');
	//require_once ($CNF->moduledir . $CNF->DS . 'users' . $CNF->DS . 'service' . $CNF->DS . 'user.php');
	$param = $f3->get ( 'POST' );
	if (! empty ( $param )) {
		$userobj = new \module\users\controller\User ( $f3 );
		$param = $f3->get ( 'POST' );

		$error_msg = $userobj->dologin ( $param );
	}

	require_once ($CNF->moduledir . $CNF->DS . 'users' . $CNF->DS . 'ui' . $CNF->DS . 'user' . $CNF->DS . 'login.php');
} else {
    // set user object
    global $USER ;
    $uid = $f3->get('SESSION.user.uid');
    if(!$uid){
        // if we do not find uid in session
        session_destroy();
        $f3->reroute($CNF->wwwroot);
    }
    $user = new \module\users\lib\UserFactory($uid);
    $USER = $user->userobj;
    
	// redirect user according to path
	// check and include files and class
	$params = $f3->get ( 'PARAMS' );
	$module = isset ( $params ['module'] ) ? $params ['module'] : '';
	$controller = isset ( $params ['controller'] ) ? $params ['controller'] : '';

	$action = isset ( $params ['action'] ) ? $params ['action'] : '';
	// TODO: pass external parameter to relative function
	// check if module and controller both are set

    require_once ($CNF->moduledir . $CNF->DS . "upper_menu.php");
    echo'  <div id="page-wrapper">';
    // echoing session message

    if($f3->get('SESSION','ecm_msg')){

        echo $f3->get('SESSION.ecm_msg');
        $f3->set('SESSION.ecm_msg','');
    }

	if ($module == '' && $controller == '') {
		$module ='users';
		$controller ='dashboard';
		$action ='mainDashboard';
		
	}
		// now check file existance
		// getting first alphanumeric with_ string
		preg_match('/\w*/', $controller, $matches);
		$controller = $matches[0];
		// it is case sensitive
		if (file_exists ( $CNF->basedir . $CNF->DS . $CNF->moduledir . $CNF->DS . $module . $CNF->DS . 'controller' . $CNF->DS . $controller . '.php' )) {
		// loading vendor classes
		require_once( $CNF->vendorlibdir . $CNF->DS .'zebraform'. $CNF->DS . 'Zebra_Form.php');

		// loading the clas automatically but for controller manually
		//require_once($CNF->moduledir . $CNF->DS . $module . $CNF->DS . 'controller' . $CNF->DS . $controller . '.php' );

		// classname
			$className = ucfirst ( $controller );
			$className = '\\module\\'.$module.'\\controller\\'.$className;

			// including controller harcoded
			if (class_exists ( $className )) {
				// construct the code string and evaluate it.
				$cntrlobj = new  $className();
				$action =  $action==''?$cntrlobj->basemethod:$action;
				// getting first alphanumeric with_ string
				preg_match('/\w*/', $action, $matches);
				$action = $matches[0];
					if (method_exists ( $className, $action )) {
						// checking permission based on user_permission

                       if(!empty($USER)){
						$user_permission = $USER['user_permission'];
						$access = false;
						if(!empty($user_permission)){
								foreach($user_permission as $permissions){
									if($permissions['module']== $module
											&& $permissions['controller'] ==$controller ){
										$access = true;
										break;
									}

								}
						}else{
							throw new \devlib\Exception('User Without Permission');
						}
                        }
						// calling method
						// exception method 
						
							if(in_array($action, $cntrlobj->allowedAction)){
								$access = true;
							}
						
						// DONE: remove after development
						//$access = true;
						if($access ){

							$cntrlobj->$action();
							// including set ui
							if(is_array($cntrlobj->view) ){
								 foreach ($cntrlobj->view as $viewname){
								        require_once($CNF->basedir . $CNF->DS . $CNF->moduledir . $CNF->DS . $module . $CNF->DS . 'ui' . $CNF->DS . $controller . $CNF->DS .  $viewname );
								 }


								}else if($cntrlobj->view!=''){
								    require_once($CNF->basedir . $CNF->DS . $CNF->moduledir . $CNF->DS . $module . $CNF->DS . 'ui' . $CNF->DS . $controller . $CNF->DS .  $cntrlobj->view );
							}
						}else{
							throw new \devlib\Exception('No Access Rights!');
						}

					} else {
						throw new \devlib\Exception('Wrong Action!');
					}

				// $cntrlobj
			}else{
				//Controller Not Found
				throw new \devlib\Exception('Wrong Controller!');
			}

		} else {

			throw new \devlib\Exception('Wrong Url!');
		}
	
}
/*
 * We are putting footer here
 */
echo'  </div>';
require_once ($CNF->uidir . $CNF->DS . 'footer.php');

?>