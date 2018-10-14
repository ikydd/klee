<?php

// Be careful of which file is calling this. 
// This file works assuming the file calling it is the same each time and its folder addresses 
// will be based on the assumption of a certain location.
// It files doesn't exist it will throw a hard error

// Doesn't seem to like files in the same directory (.)

class AutoloadException extends Exception {}

function __autoload($class)
{
	// list of all possible folders
	$folders = array(	'includes',
						'dataobjects',
						'modules'	);
	
	// loops through each folder to check for file
	foreach ($folders as $folder) {
		$file = $folder . '/' . $class . '.php';
		if (is_readable($file)) {
			require_once $file;
			return;
		}
	}
	throw new AutoloadException("Requested Class '{$class}' Does Not Exist");
}
