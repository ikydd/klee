<?php
// Visitor: updates various login data on successfull login
// Regenerates session id also

class loginpassvisitor implements visitorinterface
{
	public function visit($obj)
	{
		// reset token
		$obj->token = md5(uniqid(rand(), TRUE));
		// regen session on privilege elevation
		session_regenerate_id();
		$obj->save();
	}
}