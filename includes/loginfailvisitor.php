<?php
// Visitor: updates login fail values and check for lockout

class loginfailvisitor implements visitorinterface
{
	public function visit($obj)
	{
		// check if last fail was more than twenty minutes ago
		// reset fail count if it was
		if (($obj->last_fail + 60*60*20) < time()) {
			$obj->failcount = 0;
		}
		$obj->failcount++;
		$obj->last_fail = time();
		// check failcount + time and lockout if necessary
		if ($obj->failcount > 3) { // abstract me please
			// set lockout time and cancel failcount for when lockout expires
			$obj->lockout = time();
			$obj->failcount = 0;
		}
		
		$obj->save();
	}
}
