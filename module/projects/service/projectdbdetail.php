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
 * @service		projectdbdetail
 */
namespace module\projects\service;

class ProjectDBDetail extends \module\projects\ProjectsService {
	public $table = 'project_dbdetail';
    public $tablecol = '';
	public function __construct() {
		global $CNF;
		parent::__construct ();
		$this->table = $CNF->tbl_prefix . $this->table;
        $this->tablecol = $this->getColumnFromTable ( $this->table );

	}

	// save and update
	public function save($data) {
		// saving user and role
		global $CNF;

		try {

            return $this->DBH->insert($this->table,$data);

		} catch ( \PDOException $e ) {
			// passing pdo exception object to log

			$this->logServiceException ( $e );
		}
	}

	public function update($data,$id) {
		global $CNF;

		try {

            return $this->DBH->update($this->table,$data,'where id = '.$id);

		} catch ( \PDOException $e ) {
			// passing pdo exception object to log

			$this->logServiceException ( $e );
		}
	}


	// get list
	public function getDBDetailByProjectId($id) {
		try {

			$data = array ();
			$totalrows = 0;
			$sql = "select * from $this->table where project_id=?";

			$sth = $this->DBH->prepare ( $sql );
			$sth->execute ( array (
					$id
			) );
			$data = $sth->fetch ( \PDO::FETCH_ASSOC );
			return $data;
		} catch ( \PDOException $e ) {
			$this->logServiceException ( $e );
		}
	}


	public function getDBTypeArr(){
		return array('MYSQL'=>'MYSQL');
	}


}


