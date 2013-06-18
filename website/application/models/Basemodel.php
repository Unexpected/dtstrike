<?php 
Class Basemodel extends CI_Model {

	function __construct() {
		// Call the Model constructor
		parent::__construct();
	}
	
	function getTableName() {
		// Implement this
	}

	function getAll($limit = -1) {
		if ($limit > 0) {
			$this->db->limit($limit);
		}
		
		$query = $this->db->get($this->getTableName());
		if ($query->num_rows())  {
			return $query->result();
		}
		return array();
	}
	
	function insert() {
        $this->db->insert($this->getTableName(), $this);
	}
	
	function update($idField, $idValue) {
        $this->db->update($this->getTableName(), $this, array($idField => $idValue));
	}

	function delete($idField, $idValue) {
		$this->db->delete($this->getTableName(), $this, array($idField => $idValue));
	}
}

