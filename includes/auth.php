<?php //v0.1

class auth
{
	public static function isLoggedIn()
	{
		if (!is_null(tools::retrieve('user')->user_id)) {
			return true;
		}
		else {
			return cookiemaster::check();
		}
	}
	
	public static function authenticate($type, user $user, $param)
	{
		$authenticator = self::factory($type);
		return $authenticator->authenticate($user, $param);
	}
	
	protected static function factory($type)
	{
		try {
			$class = "auth{$type}";
			if (class_exists($class)) {
				return new $class;
			}
			throw new ClassException("Auth type {$type} does not exist.");
		} catch (Exception $e) {
			tools::sendTo('/error');
		}
	}
}
