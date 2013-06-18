<?php 
Class Basemodel extends Model {

	function Basemodel() {
		// Call the Model constructor
		parent::Model();
	}
	
	function getTableName() {
		// Need to be implemented on all implementations
	}

	function getAll() {
		$query = $this->db->query($this->getTableName());
		if ($query->num_rows())  {
			return $query->result();
		}
	}
	
	function insert() {
        $this->db->insert($this->getTableName(), $this);
	}
	
	function update($idField, $idValue) {
        $this->db->update($this->getTableName(), $this, array($idField => $idValue));
	}

}

