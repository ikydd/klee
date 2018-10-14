<?php

// Generates a database object for all your DBing needs

class DatabaseFailureException extends Exception {}

abstract class database
{
	// call this to get instance of datastore connection
	public static function factory($type = null)
	{
		// check class exists otherwise throw exception
		if (class_exists($type)) {
			// dynamic call of static variable ($class::$static_method only works in later PHP)
			return call_user_func(array($type, 'getInstance'));
		}
		throw new DatabaseFailureException("Database type '{$type}' does not exist.");
	}
}