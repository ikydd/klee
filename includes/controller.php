<?php

// References tools, this is a bit specific
// but it chops up something that is in the from "/some/data"
// relies on the parent of module for shell decision

class ControllerException extends Exception {}

class controller
{
	protected $parts;
	protected $params;
	
	public function __construct($url)
	{
		// convert to all lower case
		$parts = self::translate($url);
		// if first part is empty, fill as index
		if (empty($parts[0])) {
			$parts[0] = 'portfolio';
		}
		// get parent class, then check the grandparent is class 'module'
		try {
			if (class_exists($parts[0]) && get_parent_class($parts[0]) == 'module') {

				// dirty dirty botch for portfolios
				if ($parts[0] == 'portfolio'){
					$parts[2] = $parts[1];
					$parts[1] = null;
				}
				// check there's an action present otherwise change it to 'defaultaction'
				if (empty($parts[1])) {
					$parts[1] = 'defaultaction';
				}
				// check method exists
				else if (!method_exists($parts[0], $parts[1])) {
					tools::setError('method does not exist');
					tools::sendTo('/' . $parts[0]);
				}
				
				$this->parts = $parts;
				array_shift($parts);
				array_shift($parts);
				$this->params = $parts;
			}
			
		} catch (Exception $e){
			tools::setError('Requested page "' . $url . '" does not exist');
			tools::sendTo('/error');
		}
	}
	
	public static function translate($url)
	{
		// convert to all lower case
		$url = strtolower($url);
		
		// chop off trailing slash if present
		if (substr($url, -1, 1) == '/') {
			$url = substr($url, 0, strlen($url) - 1);
		}
		// separate the parts of the array
		return explode('/', $url);
	}
	
	public function render()
	{
		$called = call_user_func_array(array(new $this->parts[0],
			$this->parts[1]), array($this->params));
		
		// if it didn't work at all
		if ($called === FALSE) {
			throw new ControllerException("{$this->parts[1]} of section " .
				"{$this->parts[0]} failed to execute properly.");
		}
	}
}