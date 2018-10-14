<?php

class login extends module
{
	public function __construct()
	{
	}
	public function defaultaction()
	{
		include view::show('login/main');
	}
	public function process()
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$remember = $_POST['remember'];
		
		if ($username) {
			tools::store('username', $username);
		}
	
		// clean name and check it
		$clean = array();
		$clean['username'] = inspector::isUsername($username);

		if (empty($clean['username']) || empty($password)) {
			tools::setError('Nice try, but no dice. Have another go.');
			tools::sendTo('/login');
		}
		
		// set up user
		$user = new user($clean);
		// check whether user exists
		if ($user->user_id) {
			// check the user authenticates with password
			if (auth::authenticate('standard', $user, $password)) {
				$user->visitation(new loginpassvisitor());
				if ($remember) {
					$user->visitation(new remembervisitor());
				}
				tools::store('user', $user);
				tools::sendTo('/admin/pictures');
			}
		}
		tools::setError('Nice try, but no dice. Have another go.');
		tools::sendTo('/login');
	}
}