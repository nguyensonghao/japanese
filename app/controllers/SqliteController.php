<?php 

class SqliteController extends BaseController {

	protected $db;

	public function openDatabase () {
		$filename = public_path() . '/word.db';
		$this->db = new SQLite3($filename);
	}

	public function queryAllofTable ($tableName) {
		$results = $this->db->query('select * from ' . $tableName);
		$result = [];

		while ($row = $results->fetchArray()) {
			array_push($result, $row);
		}

		return $result;
	}

}

?>