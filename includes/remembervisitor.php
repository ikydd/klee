<?php
// Visitor: grabs cookie data, validates it, sets timeout and
// Delivers cookies

class remembervisitor implements visitorinterface
{
	public function visit($obj)
	{
		// validate cookie info
		$clean['ident'] = inspector::isAlphaNum($obj->ident);
		$clean['token'] = inspector::isAlphaNum($obj->token);
		
		// set timeout and save
		$obj->timeout = cookiemaster::duration();
		$obj->save();
		
		// escape data for output (bit redundant here, but fuck it)
		$cookie['ident'] = htmlentities($clean['ident'], ENT_QUOTES, 'UTF-8');
		$cookie['token'] = htmlentities($clean['token'], ENT_QUOTES, 'UTF-8');
		
		// set cookies
		setcookie('ident', $cookie['ident'], $obj->timeout, '/');
		setcookie('token', $cookie['token'], $obj->timeout, '/');
	}
}
