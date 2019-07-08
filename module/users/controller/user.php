<?php

/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      user
 * @controller  login
 */
namespace module\users\controller;

class User extends \module\users\UsersController {
	protected $_serv;
	protected $_roleserv;
	protected $_userroleserv;
	public $form;
	public $user_list_data;
	public function __construct() {
		parent::__construct ();

		$this->_serv = new \module\users\service\User ();
		$this->_roleserv = new \module\users\service\Role ();
		$this->_userroleserv = new \module\users\service\UserRole ();
		$this->baseurl = $this->baseurl . '/user/';
		$this->basemethod = 'userlist';
	}

	/**
	 * doLogin
	 *
	 * Manage User Login
	 *
	 * @param array $param
	 *
	 */
	public function dologin($param) {
		global $CNF;
		session_destroy ();
		$validatearr = $this->validate ( $param );
		// this is different process from all our controller and action
		if ($validatearr ['flag']) {

			// DONE: check and set cookie

			// check emailid exists in users and get password
			$data = $this->_serv->getPasswordByEmailId ( $param ['emailid'] );
			if ($data ['emailid'] == $param ['emailid']) {
				if (! $this->_serv->isActive ( $data ['id'] )) {
					return 'Your account is set to inactive.Please contact to your Admin';
				}
				$uid = $data ['id'];
				$password = $data ['password'];
				$passwordsalt = $data ['passwordsalt'];
				$givenpwd = $this->_generatePassword ( $param ['password'], $passwordsalt );
				if ($givenpwd == $password) {
					// check and set cookie

					if (isset ( $param ['remember'] )) {
						$year = time () + 31536000;
						$cookieval = generalEncrypt ( $_POST ['emailid'] );
						setcookie ( 'ecm', $cookieval, $year );
					} elseif (! isset ( $param ['remember'] )) {
						if (isset ( $_COOKIE ['ecm'] )) {
							$past = time () - 100;
							setcookie ( 'ecm', '', $past );
						}
					}

					// now generate userobject after login

					$this->f3->set ( 'SESSION.user', array (
							"uid" => $uid
					) );

					$referrer = isset ( $_SERVER ['HTTP_REFERER'] ) ? $_SERVER ['HTTP_REFERER'] : '';

					if ($referrer) {
						$this->f3->reroute ( $referrer );
					} else {
						$this->f3->reroute ( $CNF->wwwroot );
					}
				} else {
					return 'Password does not match!';
				}
			} else {
				return 'Provide Emailid is not correct!';
			}
		} else {
			return $validatearr ['msg'];
		}
	}
	/*
	 * Validate login credential
	 */
	protected function validate($param) {
		$returnarr = array (
				'flag' => true,
				'msg' => ''
		);
		if (! is_array ( $param )) {
			$returnarr ['flag'] = false;
			$returnarr ['msg'] = 'Parameter Not set.';
			return $returnarr;
		}
		if (! array_key_exists ( 'emailid', $param ) || ! array_key_exists ( 'password', $param ) || ! trim ( $param ['emailid'] ) || ! trim ( $param ['password'] )) {
			$returnarr ['flag'] = false;
			$returnarr ['msg'] = 'Emailid/Password is not set!';
			return $returnarr;
		}
		$username = trim ( $param ['emailid'] );
		$passsword = trim ( $param ['password'] );

		// TODO:DB Match
		return $returnarr;
	}

	/*
	 * Manage Logout
	 */
	public function logout() {
		global $CNF;
		session_destroy ();
		$this->f3->reroute ( $CNF->wwwroot );
	}
	/*
	 * Add User
	 */
	public function add() {
		global $CNF, $USER;
		$this->view = 'add.php';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$param = $this->f3->get ( 'POST' );

		$this->form ['data'] ['usercol'] = $this->_serv->tablecol;
		// adding roleid extra
		$this->form ['data'] ['rolecol'] = $this->_userroleserv->tablecol;
		// TODO : do not add user untill ther is no role

		if (isset ( $param ['btnsubmit'] )) {
			// remainining form filles incase there is an error
			assocArrayLeftMerge ( $this->form ['data'] ['usercol'], $param );
			assocArrayLeftMerge ( $this->form ['data'] ['rolecol'], $param );
		}
		if (isset ( $param ['btnreset'] )) {
			$this->f3->reroute ( $CNF->wwwroot . 'users/user/userlist' );
		}

		$this->form ['data'] ['rolearr'] = $this->_roleserv->getRolesOptionsArr ();

		if ($this->form ['form']->validate ()) {
			$param['emailid'] = strtolower($param['emailid']);
			$this->filterInputData ( $param );

			if ($this->checkUserEmail ( $param ['emailid'] )) {
				return $this->form ['form']->add_error ( 'error', 'Provided Emailid already Exists!' );
			}
			// also saving role of user

			$user = array ();
			$passwordsalt = rand ( 1, 99999 );
			$encyptpassword = $this->_generatePassword ( $param ['password'], $passwordsalt );
			$user ['firstname'] = $param ['firstname'];
			$user ['lastname'] = $param ['lastname'];
			$user ['emailid'] = $param ['emailid'];
			$user ['password'] = $encyptpassword;
			$user ['passwordsalt'] = $passwordsalt;
			$user ['displayname'] = $param ['displayname'];
			$user ['signature'] = $param ['signature'];
			$user ['isactive'] = isset ( $param ['isactive'] ) ? $param ['isactive'] : 0;
			$user ['creationtime'] = time ();
			$user ['createdby'] = $USER ['uid'];
			$role ['role_id'] = $param ['role_id'];
			$savearr ['user'] = $user;
			$savearr ['role'] = $role;

			if ($this->_serv->saveUser ( $savearr )) {
				$this->setSessionMessage ( 'User has added succesfully!', array (
						"viewname" => 'list.php'
				) );

				$this->f3->reroute ( $CNF->wwwroot . 'users/user/userlist' );
			} else {
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}
	/*
	 * Update User Profile
	 */
	public function edit() {
		$editid = \devlib\AppController::getKeyValueRequired ( 'edit' );

		global $CNF, $USER;
		$this->view = 'add.php';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );
		$param = $this->f3->get ( 'POST' );

		$this->form ['data'] ['usercol'] = $this->_serv->getUserById ( $editid );
		// adding roleid extra
		$this->form ['data'] ['rolecol'] = $this->_userroleserv->getRoleByUserId ( $editid );
		// TODO : do not add user untill ther is no role
		$this->form ['data'] ['rolearr'] = $this->_roleserv->getRolesOptionsArr ();

		if (isset ( $param ['btnsubmit'] )) {
			// remainining form filles incase there is an error
			assocArrayLeftMerge ( $this->form ['data'] ['usercol'], $param );
			assocArrayLeftMerge ( $this->form ['data'] ['rolecol'], $param );
		}
		if (isset ( $param ['btnreset'] )) {
			$this->f3->reroute ( $CNF->wwwroot . 'users/user/userlist' );
		}
		if ($this->form ['form']->validate ()) {
			$param['emailid'] = strtolower($param['emailid']);
			$this->filterInputData ( $param );
			$cond = " and id NOT IN (" . $param ['id'] . ")";
			if ($this->checkUserEmail ( $param ['emailid'], $cond )) {
				return $this->form ['form']->add_error ( 'error', 'Provided Emailid already Exists!' );
			}
			// also saving role of user

			$user = array ();
			if ($param ['password'] != '') {
				$passwordsalt = rand ( 1, 99999 );
				$encyptpassword = $this->_generatePassword ( $param ['password'], $passwordsalt );
				$user ['password'] = $encyptpassword;
				$user ['passwordsalt'] = $passwordsalt;
			}
			// getting id from hidden element
			$user ['id'] = $param ['id'];
			$user ['firstname'] = $param ['firstname'];
			$user ['lastname'] = $param ['lastname'];
			$user ['emailid'] = $param ['emailid'];
			$user ['displayname'] = $param ['displayname'];
			$user ['signature'] = $param ['signature'];
			$user ['isactive'] = isset ( $param ['isactive'] ) ? $param ['isactive'] : 0;
			$user ['updationtime'] = time ();
			$user ['updatedby'] = $USER ['uid'];
			$role ['role_id'] = $param ['role_id'];
			$role ['user_id'] = $param ['id'];
			$savearr ['user'] = $user;
			$savearr ['role'] = $role;

			// checkingif last admin is setting inactive to itself
			if (! $user ['isactive']) {

				$nour = $this->_userroleserv->getNoOfUserByRole ( $role ['role_id'] );
				if ($nour ['shortname'] == 'ADMIN' && $nour ['totaluser'] == 1) {
					return $this->form ['form']->add_error ( 'error', 'You are the only Admin and setting you self to inactive will lead to system without admin ' );
				}
			}
			if ($this->_serv->updateUser ( $savearr, $param ['id'] )) {
				$this->setSessionMessage ( 'User has updated succesfully!', array (
						"viewname" => 'list.php'
				) );
				$this->f3->reroute ( $CNF->wwwroot . 'users/user/userlist' );
			} else {
				return $this->form ['form']->add_error ( 'error', 'Information could not save!' );
			}
		}
	}

	/*
	 * check if useremail exist
	 */
	public function checkUserEmail($emailid, $cond = '') {
		return $this->_serv->isEmailIdExist ( $emailid, $cond );
	}

	/*
	 * browser user list
	 */
	public function userlist($paging = true) {
		$this->view = 'userlist.php';
		$cond = '';
		// instantiate a Zebra_Form object
		$this->form ['form'] = new \Zebra_Form ( 'form' );

		if ($this->form ['form']->validate ()) {
			$param = $this->f3->get ( 'POST' );
			if (isset ( $param ['btnsubmit'] )) {
				$searcharr = array ();
				$searcharr ['searchname'] = $param ['searchname'];
				$searcharr ['searchemail'] = $param ['searchemail'];
				$searcharr ['isactive'] = isset ( $param ['isactive'] ) ? $param ['isactive'] : 0;
				;
				$searcharr ['role_id'] = $param ['role_id'];

				$this->f3->set ( 'SESSION.search.user', $searcharr );
			} else if (isset ( $param ['btnreset'] )) {
				$searcharr = array ();
				$this->f3->set ( 'SESSION.search.user', $searcharr );
			}
		}
		$sessionsearch = $this->f3->get ( 'SESSION.search.user' );

		if (isset ( $sessionsearch ) && ! empty ( $sessionsearch )) {
			$searcharr = $sessionsearch;
			$searchcond = array ();
			if ($searcharr ['searchname'] != '') {
				$searchcond [] = " (u.firstname like  '%" . $searcharr ['searchname'] . "%' or u.lastname like  '%" . $searcharr ['searchname'] . "%' )";
			}
			if ($searcharr ['searchemail'] != '') {
				$searchcond [] = " (u.emailid like  '%" . $searcharr ['searchemail'] . "%' )";
			}
			if ($searcharr ['isactive']) {
				$searchcond [] = " (u.isactive = " . $searcharr ['isactive']." ) ";
			}
			if ($searcharr ['role_id']) {
				$searchcond [] = "(ur.role_id = " . $searcharr ['role_id']." )";
			}
			if (! empty ( $searchcond )) {
				$cond = implode ( " AND ", $searchcond );
				$cond = 'where ' . $cond;
			}
		} else {
			$searcharr = array ();
			$searcharr ['searchname'] = '';
			$searcharr ['searchemail'] = '';
			$searcharr ['isactive'] = '';
			$searcharr ['role_id'] = '';
		}

		$this->form ['data'] ['searcharr'] = $searcharr;
		$this->form ['data'] ['rolearr'] = $this->_roleserv->getRolesOptionsArr ();

		$project_list = array ();
		$serial_offset = 0;
		// this key name should match keyname of data record
		$header = array (
				'id' => '', // s that it would not come with header
				'name' => 'Name',
				'emailid' => 'Email-Id',
				'rolename' => 'Role',
				'isactive' => 'Is Active'
		);

		// parsing url and gettignpageno.
		$orderby = 'order by isactive DESC, id DESC ';
		if ($paging) {

			if ($pageno = \devlib\AppController::getKeyValue ( 'page' )) {
			} else {
				$pageno = 1;
			}
			$paging_obj = new \devlib\Pagination ( $pageno );
			$serial_offset = ( int ) $paging_obj->getOffset ( $pageno );

			$limit = ' Limit ' . $serial_offset . ', ' . ( int ) $paging_obj->getPerPage ();
			$data = $this->_serv->getUsers ( $cond, $orderby, $limit );

			$this->user_list_data ['paging'] = $paging_obj->doPaging ( $data ['rowcount'] );
		} else {
			$data = $this->_serv->getUsers ( $cond, $orderby );
		}

		$listformatter = new \devlib\ListGenerator ( $data ['data'], $header, array_keys ( $header ) );
		$this->user_list_data ['data'] = $listformatter->setTableArray ( $serial = true, $serial_offset );
	}

	/*
	 * Profile display
	 */
	public function profile() {
		$this->view = 'profile.php';
	}

	/*
	 * generating password
	 */
	private function _generatePassword($password, $crypt) {
		return crypt ( $password, $crypt );
	}
}
