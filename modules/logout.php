<?php

class logout extends module
{
	public function defaultaction()
	{
		cookiemaster::delete();
		tools::store('user', null);
		
		if(tools::retrieve('out', REMOVE)){
			include view::show('logout/main');
		}
		else{		
			tools::store('out', true);
			tools::sendTo('/logout');
		}
	}
}