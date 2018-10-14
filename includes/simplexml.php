<?php

// Location of details is relative to general requesting script
// If you need different connections to different servers/dbs then create new objects for it
// XML database allows for nested elements

class XMLConnectionException extends Exception {}
class XMLQueryException extends Exception {}

class simplexml extends crud_database implements singleton
{
	// for singleton
	protected static $instance = null;
	protected $folder = 'private/xml';
	protected $connection;
	
	// protected
	protected function __construct()
	{		
		// try connection throw error if fail, but only display error
		if (!is_dir($this->folder)) {
			throw new XMLConnectionException('XML database connection failed: folder does not exist');
		}
	}
	// singleton method
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	// coverall escaping function
	public function escape($data)
	{
		if (is_array($data)) {
			// could do an array_map here, but need to maintain the assoc keys
			$escaped = array();
			foreach ($data as $key=>$value) {
				$key = $key;
				// get rid of these in case
				$value = stripslashes($value);
				// convert all characters to what the text should look like, then convert XML entities back
				$escaped[$key] = htmlspecialchars(html_entity_decode($value, ENT_QUOTES, 'UTF-8'), ENT_NOQUOTES, 'UTF-8');
			}
			return $escaped;
		}
		else {
			// get rid of these just in case
			$value = stripslashes($value);
			// convert all characters to what the text should look like, then convert XML entities back
			return htmlspecialchars(html_entity_decode($data, ENT_QUOTES, 'UTF-8'), ENT_NOQUOTES, 'UTF-8');
		}
		// note that simpleXML will do some encoding too, but it unconverts on the way out also
	}
	protected function entities($data)
	{
		$entities = array(
			//'"' => '&quot;',
			'\'' => '&apos;' );
		$search = array_keys($entities);
		$replace = array_values($entities);
		return str_replace($search, $replace, $data);
	}
	// opens XML file and return SimpleXMLObject
	protected function open($xml)
	{
		$path = $this->folder . '/' . $xml . '.xml';
		$contents = simplexml_load_file($path);
		
		if ($contents instanceof SimpleXMLElement){
			return $contents;
		}
		else {
			throw new XMLConnectionException('The requested XML had errors in it and could not be loaded.');
		}
	}
	// writes to the current XML file in use
	protected function write($xml)
	{
		$filename = $xml->getName();
		$path = $this->folder . '/' . $filename . '.xml';
		
		return $xml->asXML($path);
	}
	// test conditions against an element
	protected function where($element, $conditions = array())
	{
		if (empty($conditions)) return true; // return true automatically if no conditions set
	
		$where = false;
		foreach ($conditions as $k=>$v){
			if ($v == (string) $element->$k){
				$where = true;
			}
			else {
				$where = false;
				break;
			}
		}
		return $where;
	}
	// recursive that converts elements to variables (reading)
	protected function xmlObjectToArray($element)
	{
		$row = array();
	
		foreach ($element->children() as $child){
			$row[$child->getName()] = $this->getElement($child);
		}
		return $row;
	}
	protected function getElement($element)
	{
		// if node handed has some children
		if (count($element->children()) > 0) {
			// node will be made as an object to return
			$obj = new stdClass();
			// iterate over each of the child nodes
			foreach ($element->children() as $child){
				// get the name of the sub-node
				$n = $child->getName();
				// get an array of the all the similarly named subnodes in the main element
				$nodes = $element->xpath($n);
				// if there's more than one we need to stack them in an array
				if (count($nodes) > 1){
					// initialise array so we can push into it
					$obj->$n = array();
					// iterate of each of the similarly named subnodes
					foreach ($nodes as $kid){
						// add their data into the array. If they contain more submodes, add them in too
						array_push($obj->$n, $this->getElement($kid));
					}
				}
				// if there's only one of the name subnode, no array, just a value
				else {
					$obj->$n = $this->getElement($child);
				}
			}
			return $obj;
		}
		else {
			return (string) $element;
		}
	}
	// recursive for appending elements (saving)
	protected function arrayToXmlObject($element, $array)
	{
		unset($element->$k);
		foreach ($array as $k => $v){
			if (is_object($v)){
				$node = $element->addChild($k);
				// can use __METHOD__ here, but that's PHP 5.3 onwards only it seems
				call_user_func_array(array($this, __FUNCTION__), array($node, $v));
			}
			elseif (is_array($v)){
				foreach ($v as $value){
					if (is_object($value)) {
						$node = $v->addChild($k);
						// can use __METHOD__ here, but that's PHP 5.3 onwards only it seems
						call_user_func_array(array($this, __FUNCTION__), array($node, $value));
					}
					else {
						$element->addChild($k, $value);
					}
				}
			}
			else {
				$element->$k = $v;
			}
		}
		// no need for a return as directly edits reference to node
	}
	// insert has additional return of id
	public function create($location, $values = array())
	{
		// escape data
		$escaped = array();
		$escaped['location'] = $this->escape($location);
		$escaped['values'] = $this->escape($values);
		
		// open the xml file
		$xml = $this->open($escaped['location']);
		
		// get the current id and then increment it
		$id = (int) $xml['id_count'];
		$xml['id_count'] = ++$id;
		
		// create xml element
		$entry = $xml->addChild((string) $xml['object_type']);
		// insert id element
		$entry->addChild((string) $xml['auto_increment'], $id);
		// iterate over data and enter values
		$this->arrayToXmlObject($entry, $escaped['values']);
		// check file is actually written
		if ($this->write($xml)){
			return $id;
		} 
		else {
			throw new XMLConnectionException('Failed to write entry');
		}
	}
	// returns data
	public function retrieve($location, $conditions = array())
	{
		// escape data
		$escaped = array();
		$escaped['location'] = $this->escape($location);
		$escaped['conditions'] = $this->escape($conditions);
		
		// open xml file
		$xml = $this->open($escaped['location']);
		
		// initialise results
		$results = array();
		// find relevant element
		foreach ($xml->xpath('//'.(string) $xml['object_type']) as $element){
			if ($this->where($element, $escaped['conditions'])){
				// add to the results
				$results[] = $this->xmlObjectToArray($element);
			}
		}
		return $results;
	}
	// updates record
	public function update ($location, $conditions = array(), $values = array())
	{
		// check some conditions are in fact set
		if (empty($conditions) || !is_array($conditions)){
			throw new XMLQueryException('Delete conditions were empty. Continuing would delete everything');
		}
		
		// escape data
		$escaped = array();
		$escaped['location'] = $this->escape($location);
		$escaped['conditions'] = $this->escape($conditions);
		$escaped['values'] = $this->escape($values);
		
		// open xml file
		$xml = $this->open($escaped['location']);
		
		// find the relevant element
		foreach ($xml->xpath('//'. (string) $xml['object_type']) as $element){
			if ($this->where($element, $escaped['conditions'])){
				// this essentially erases the node and starts from fresh so as to avoid duplicates etc.
				// $element = new SimpleXMLElement('<'.$xml['object_type'].'></'.$xml['object_type'].'>');
				// iterate over it with the values
				$this->arrayToXmlObject($element, $escaped['values']);
			}
		}
		if (!$this->write($xml)){ // finish writing it
			throw new XMLConnectionException('Failed to write entry');
		}
	}
	// deletes record
	public function delete ($location, $conditions = array())
	{
		// check some conditions are in fact set
		if (empty($conditions) || !is_array($conditions)){
			throw new XMLQueryException('Delete conditions were empty. Continuing would delete everything');
		}
		
		// escape data
		$escaped = array();
		$escaped['location'] = $this->escape($location);
		$escaped['conditions'] = $this->escape($conditions);
		
		// open xml file
		$xml = $this->open($escaped['location']);
		
		// find the relevant element
		foreach ($xml->xpath('//'. (string) $xml['object_type']) as $element){
			if ($this->where($element, $escaped['conditions'])){
				// delete the element
				$dom = dom_import_simplexml($element);
				$dom->parentNode->removeChild($dom);
			}
		}
		// check it actually writes
		if (!$this->write($xml)){
			throw new XMLConnectionException('Failed to write entry');
		}
	}
}