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
// TODO: make it singleton class
class DBWrapper extends \PDO {
	private $sql;
	private $bind;
	public function __construct($dsn, $username, $password, $attributes = array()) {
		try {
			parent::__construct ( $dsn, $username, $password, $attributes );
		} catch ( \PDOException $e ) {
			throw new Exception ('Database connection could not established ! please check dsn configuration in config.ini!' , 'SERVICE' );
		}
	}
	public function delete($table, $where, $bind = "") {
		$sql = "DELETE FROM " . $table . $where . ";";
		return $this->run ( $sql, $bind );
	}
	private function cleanup($bind) {
		if (! is_array ( $bind )) {
			if (! empty ( $bind ))
				$bind = array (
						$bind
				);
			else
				$bind = array ();
		}
		return $bind;
	}
	public function insert($table, $data) {
		$colname = array_keys ( $data );
		$colnamestr = implode ( ", ", $colname );
		foreach ( $colname as &$value ) {
			$value = ':' . $value;
		}
		$bindvaluestr = implode ( ", ", $colname );
		$sql = "INSERT INTO $table ($colnamestr) values ($bindvaluestr)";
		return $this->run ( $sql, $data );
	}
	public function run($sql, $bind = "") {
		$this->sql = trim ( $sql );
		$this->bind = $this->cleanup ( $bind );

		try {
			$pdostmt = $this->prepare ( $this->sql );
			if ($pdostmt->execute ( $this->bind ) !== false) {
				if (preg_match ( "/^(" . implode ( "|", array (
						"select",
						"describe",
						"pragma"
				) ) . ") /i", $this->sql ))
					return $pdostmt->fetchAll ( \PDO::FETCH_ASSOC );
				elseif (preg_match ( "/^(" . implode ( "|", array (
						"delete",
						"insert",
						"update"
				) ) . ") /i", $this->sql ))
					return $pdostmt->rowCount ();
			}
		} catch ( \PDOException $e ) {
			throw new Exception ( $e, 'SERVICE' );
			return false;
		}
	}
	public function select($table, $where = "", $bind = "", $fields = "*") {
		$sql = "SELECT " . $fields . " FROM " . $table;
		if (! empty ( $where ))
			$sql .= " WHERE " . $where;
		$sql .= ";";
		return $this->run ( $sql, $bind );
	}
	public function update($table, $valarr, $where) {
		$colname = array_keys ( $valarr );
		foreach ( $colname as &$value ) {
			$value = $value . '= :' . $value;
		}
		$bindvaluestr = implode ( ", ", $colname );
		$sql = "update $table SET $bindvaluestr " . $where;
		$bind = $this->cleanup ( $valarr );
		return $this->run ( $sql, $bind );
	}
	public function num_rows($query) {
		// create a prepared statement
		$stmt = parent::prepare ( $query );

		if ($stmt) {
			// execute query
			$stmt->execute ();

			return $stmt->rowCount ();
		} else {
			return self::get_error ();
		}
	}

	// isplay error
	public function get_error() {
		$this->connection->errorInfo ();
	}

	// closes the database connection when object is destroyed.
	public function __destruct() {
		$this->connection = null;
	}
}
?>
