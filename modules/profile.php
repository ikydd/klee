<?php

class profile extends module
{
	public function __construct()
	{
		if (!auth::isLoggedIn()) tools::sendTo('/login');
		tools::store('page', 'profile');
	}
	public function defaultaction()
	{
		$user = tools::retrieve('user');
		
		include view::show('profile/show');
	}
	public function edit($params = array())
	{
		tools::setError('Editing the profile has been disabled');
		tools::sendTo('/admin/profile');
		
		$user = tools::retrieve('user');
		
		include view::show('profile/edit');
	}
	
	public function processedit()
	{
		// grab details
		$username = $_POST['username'];
		$old_password = $_POST['old_password'];
		$new_password = $_POST['new_password'];
		$conf_password = $_POST['conf_password'];
		
		// clean as necessary
		$clean['username'] = inspector::isUsername($username);
		
		try {
		
			// check for username
			if (empty($clean['username'])) {
				tools::setError('That username is not valid I\'m afraid.');
				throw new Exception();
			}
			// check for all passwords
			if (empty($old_password)) {
				tools::setError('Password verification needed for this stuff pal.');
				throw new Exception();
			}
			// check that new passwords match
			if ($new_password != $conf_password) {
				tools::setError('Your new passwords do not match.');
				throw new Exception();
			}
			// regenerate user
			$user = tools::retrieve('user');
			
			// check user is still there
			if (!$user) {
				tools::setError('Something went a bit wrong. My bad.');
				throw new Exception();
			}
			// check old password
			if (!auth::authenticate('standard', $user, $old_password)){
				tools::setError('Password was incorrect I\'m afraid. No-go.');
				throw new Exception();
			}
			
			// stick in the data
			$user->username = $clean['username'];
			$user->password = $new_password ? passwordhash::hash($new_password) : $user->password;
			
			// restore stored user if has been edited
			if (tools::retrieve('user')->user_id == $obj->user_id){
				tools::store('user', $obj);
			}
			// clean house
			tools::store('username', null);
			tools::store('password', null);
			
			$user->save();

			// send to default module page after saving
			tools::setError('User details succesfully changed.');
			tools::sendTo('/admin/profile');
		
		} catch (Exception $e) {
			tools::sendTo('/admin/profile/edit');
		}
	}
}