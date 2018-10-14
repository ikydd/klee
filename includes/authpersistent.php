<?php

class authpersistent implements authenticatorinterface
{
	public function authenticate(user $user, $match)
	{
		// check for stored token
		if (!$user->token) {
			return false;
		}
		// check for timeout
		if ($user->timeout < time()) {
			return false;
		}
		// check for match
		if ($match == $user->token) {
			return true;
		}
		return false;
	}
}
