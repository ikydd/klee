<?php

class view
{
	public static function show($view)
	{
		$location = 'views/' . $view . '.php';
		if (is_readable($location)) {
			return $location;
		}
		else {
			return 'views/blank.php';
		}
	}
}