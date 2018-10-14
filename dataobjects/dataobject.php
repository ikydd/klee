<?php

// References: database (and mysql)
// construct with assoc array normally, number and string get put to default key
// References: database (and mysql)
// construct with assoc array normally, number and string get put to default key

class DataObjectException extends Exception {}
class SaveDataObjectException extends Exception 
{
	function __construct($object, $message = null, $code = 0, Exception $previous = null)
	{
		$this->dataobject = $object;
		// do the usual stuff
        parent::__construct($message, $code, $previous);
	}
}

abstract class dataobject
{
	protected $data;
	
	// constructor
	public function __construct($conditions = NULL)
	{	
		if (!empty($conditions)) {
			if (is_array($conditions)) {
				$where = $conditions;
			}
			else if (is_numeric($conditions) || is_string($conditions)) {
				$where = array($this->defaultKey => $conditions);
			}
			else {
				throw new DataObjectException("Invalid type of qualifier given for datachunk: {$conditions}");
			}
			$this->prepare($where);
		}
	}
	// magic setter
	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}
	// magic getter
	public function __get($name)
	{
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}
		else {
			return null;
		}
	}
	// this grabs the data from the datasource
	protected function prepare($where)
	{
		// get an instance of the datastore
		$db = database::factory($this->database);
		
		$results = $db->retrieve($this->table, $where);
		
		if (!isset($results[0])) {
			$results[0] = array();
		}
		foreach ($results[0] as $name=>$value) {
			$this->$name = $value;
		}
	}
	// decides between adding and updating
	public function save()
	{
		if (!$this->data[$this->defaultKey]) {
			$this->store();
		}
		else {
			$this->update();
		}
	}
	// store
	protected function store()
	{
		$db = database::factory($this->database);

		// check data is there
		if (empty($this->data) || !is_array($this->data)) {
			throw new SaveDataObjectException($this, 'Cannot save. Data invalid type or empty.');
		}

		// declare the data for clarity
		$location = $this->table;
		$values = $this->data;
		
		$id = $db->create($location, $values);
		
		$this->data[$this->defaultKey] = $id;
	}
	// update
	protected function update()
	{
		$db = database::factory($this->database);
		
		// check input
		if (empty($this->data[$this->defaultKey]) || !is_array($this->data)) {
			throw new SaveDataObjectException($this, "Cannot update. 'Where' was empty.");
		}
		
		// declare data for clarity
		$location = $this->table;
		$conditions = array($this->defaultKey=>$this->data[$this->defaultKey]);
		$values = $this->data;
		
		$db->update($location, $conditions, $values);
	}
	// allows input of a visitor while also checking it's the right type
	public function visitation($visitor)
	{
		// check it's legit
		if ($visitor instanceof visitorinterface) {
			$visitor->visit($this);
		}
		else {
			throw new DataObjectException('Invalid visitor applied on some data');
		}
	}
}
