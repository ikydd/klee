<?php

class sitepath
{
	public function __construct()
	{
		// get current script path then chop off script name
		$site = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
		// set SITEPATH constant
		define('SITEPATH', $site);
	}
}