<?php

// Be sure to check what error situation you have. You can set the type of error, and also the error redirect location.
// All methods are static so you shouldn't need to instantiate the object.

class tools
{
	const STACK = TRUE;
	const REMOVE = TRUE;
	
	// stores variable in SESSION
	public static function store($name, $value, $array = null)
	{
		if ($array) {
			if (!is_array($_SESSION[$name])) {
				$_SESSION[$name] = array();
			}
			$_SESSION[$name][] = $value;
		}
		else {
			$_SESSION[$name] = $value;
		}
	}
	// retrieves variable from SESSION without removing
	public static function retrieve($name, $remove = null)
	{
		if (isset($_SESSION[$name])) {
			$value = $_SESSION[$name];
			if ($remove) unset($_SESSION[$name]);
		}
		return $value;
	}
	// grab a load of shizz from the POST
	public static function harvest()
	{
		$yield = array();
		foreach ($_POST as $k=>$v){
			$yield[$k] = $v;
		}
		
		return $yield;
	}
	// stores errors in SESSION
	public static function setError($error, $type = 'error')
	{
		self::store($type, $error, self::STACK);
	}
	// redirects user
	public static function sendTo($url = '/')
	{
		if (!defined('SITEPATH')){
			$site = new sitepath();
		}
		die(header('Location: ' . SITEPATH . $url));	
	}
}