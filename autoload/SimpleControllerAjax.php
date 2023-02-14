<?php
// Class that provides methods for working with the data at 'artCollection'.


class SimpleControllerAjax {
	private $mapper;
	
	public function __construct() {
		global $f3;						// needed for $f3->get() 
		$this->mapper = new DB\SQL\Mapper($f3->get('DB'),"artCollection");	// create DB query mapper object for 'artCollection' table
	}

	public function getData() {
		$list = $this->mapper->find();
		return $list;
	}
		
	public function search($field, $term) {
		$list = $this->mapper->find([$field . " LIKE ?", "%" . $term . "%"]);  // load DB record matching the given field
		return $list;
	}

	// Used for searching an art collection based on the MBTI and scoreBand of user
	public function artSearching($field1, $term1, $field2, $term2) {
		$list = $this->mapper->find([$field1 . " LIKE ? AND " . $field2 . " LIKE ?", "%" . $term1 . "%", "%" . $term2 . "%"]);
		return $list;
	}




}

?>
