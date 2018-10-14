<?php

class cookiemaster
{
	public static function check()
	{				
		// check for cookies
		$ident = $_COOKIE['ident'];
		$token = $_COOKIE['token'];
			
		$clean = array();
		
		if (empty($ident) || empty($token)) {
			return false;
		}
			
		// clean the cookies
		$clean['ident'] = inspector::isAlphaNum($ident);
		$clean['token'] = inspector::isAlphaNum($token);
		$user = new user(array('ident'=>$clean['ident']));
		
		// auth match check
		if (auth::authenticate('persistent', $user, $clean['token'])) {
			$user->visitation(new loginpassvisitor());
			$user->visitation(new remembervisitor());
			tools::store('user', $user);
			return true;
		}
	}
	
	public static function duration()
	{
		// return time plus seven days
		return time() + 60*60*24*90;
	}
	
	public static function delete()
	{
		// delete cookies  time()-60*60*24*30
		setcookie('ident', 'DELETED', time()+30, '/');
		setcookie('token', 'DELETED', time()+30, '/');
	}
}