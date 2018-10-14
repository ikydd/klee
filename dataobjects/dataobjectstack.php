<?php

class DatastackFailureException extends Exception {}

abstract class dataobjectstack implements Iterator
{
	protected $position = 0;
	protected $storage = array();
	protected $dataobject = null;
	
	public function __construct()
	{
		if(!is_null($this->type)){
			$this->dataobject = $this->factory($this->type);
		}
	}
	// to see whether it's empty or not
	public function count()
	{
		return count($this->storage);
	}
	public function sort($sortby)
	{
		$sorter = array();
		foreach ($this->storage as $array){
			$sorter[] = strtolower($array->$sortby);
		}
		array_multisort($sorter, $this->storage);
	}
	protected function factory($dataobject)
	{
		// check class exists before executing
		if (class_exists($dataobject)){
			return new $dataobject;
		}
		// kick out if broken
		throw new DatastackFailureException();
	}
	public function getData($conditions = null)
	{
		if (!is_null($this->dataobject)){
			// instantiate database
			$db = database::factory($this->dataobject->database);
			// set location
			$location = $this->dataobject->table;
			// get data from source
			$results = $db->retrieve($location, $conditions);
			// stack in data
			$this->prepare($results, get_class($this->dataobject));
		}
	}
	protected function prepare($array, $dataobject)
	{
		// $array is full table data
		foreach ($array as $item) {
			// new object for each row
			$object = new $dataobject;
			// fill out the object
			foreach ($item as $key => $value) {
				$object->$key = $value;
			}
			// store it
			$this->storage[] = $object;
		}
	}
	
	public function saveAll()
    {
        foreach ($this as $item) {
            $item->save();
        }
    }
    
    public function current()
    {
        return $this->storage[$this->position];
    }
    
    public function key()
    {
        return $this->position;
    }
    
    public function next()
    {
        $this->position++;
    }
    
    public function rewind()
    {
        $this->position = 0;        
    }
    
    public function valid()
    {
        return isset($this->storage[$this->position]);
    }
}