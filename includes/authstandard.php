<?php

class authstandard implements authenticatorinterface
{
	public function authenticate(user $user, $match)
	{
		// check for stored password
		if (!$user->password) {
			return false;
		}
		// check hash against password
		return passwordhash::check($match, $user->password);
	}
}
