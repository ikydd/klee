<?php

class inspector
{
	// Test for real true
	public static function isTrue($input)
	{
		if($input === 1 || $input === '1' || $input === true || $input === 'true')
			return true;
		else
			return false;
	}
	// Test for real false -- NOTE: this returns true if input is genuinely false
	public static function isFalse($input)
	{
		if($input === 0 || $input === '0' || $input === false || $input === 'false')
			return true;
		else
			return false;
	}
	public static function isZeroOne($input)
	{
		if ($input == 0 || $input == 1)
			return $input;
		else
			return null;
	}
	// Letters and numbers only
	public static function isAlphaNum($input)
	{
		if(ctype_alnum($input))
			return $input;
		else
			return null;
	}
	// Letters only
	public static function isAlpha($input)
	{
		if(ctype_alpha($input))
			return $input;
		else
			return null;
	}
	// Numbers only i.e. an interger
	public static function isNumberInt($input)
	{
		if(ctype_digit($input))
			return $input;
		else
			return null;
	}
	// Floating point number
	public static function isNumberFloat($input)
	{
		if(filter_var($input, FILTER_VALIDATE_FLOAT))
			return $input;
		else
			return null;
	}
	// Any kind of number (float or int)
	public static function isNumberAny($input)
	{
		if($this->numberInt($input) || $this->numberFloat($input))
			return $input;
		else
			return null;
	}
	// Use regex to specify allowed characters 
	public static function isUsername($input)
	{
		if(preg_match('/^([a-z0-9_-])+$/i', $input))
			return $input;
		else
			return null;
	}
	// Use regex to specify allowed characters 
	public static function isFoldername($input)
	{
		if(preg_match('/^([a-z0-9_-])+$/i', $input))
			return $input;
		else
			return null;
	}
	// Use regex to specify allowed characters 
	public static function isFilename($input)
	{
		if(preg_match('/^([a-z0-9_\.-])+$/i', $input))
			return $input;
		else
			return null;
	}
	// Valid email address
	public static function isEmail($input)
	{
		if(filter_var($input, FILTER_VALIDATE_EMAIL))
			return $input;
		else
			return null;
	}
	// Valid text
	public static function isText($input)
	{
		if(is_string($input))
			return $input;
		else
			return null;
	}
	// valid time
	public static function isTime($input)
	{
		$test = strtotime($input);
		if ($test == -1)
			return null;
		else
			return $input;
	}
	public static function isAnything($input)
	{
		return $input;
	}
}