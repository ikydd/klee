<?php

class passwordhash
{
	// PROPERTIES
	protected static $publicSalt = 'private/salt.php';
	protected static $saltLength = 8;

	//METHODS
	/**
	 * Just generates a random string of hexa-digits of up 32 characters
	 */
	protected static function randomGen()
	{
		return substr(md5(uniqid(rand(), TRUE)), 0, self::$saltLength);
	}
	/**
	 * Pass this a password and the stored hash. It will handle the
	 * business of extracting the salt and then pass it on to the
	 * hashing function.
	 */
	public static function check($password, $hash)
	{
		$salt = substr($hash, 0, self::$saltLength);
		if(self::hash($password, $salt) == $hash)
			return true;
		else
			return false;
	}
	/**
	 * Pass this function the salt if you want to check a password
	 * otherwise it will generate a fresh one instead. The hash is as follows:
	 * 1. pass + public salt => hashed
	 * 2. personal salt + previous hash => hashed
	 * 3. return salt + previous hash
	 */
	public static function hash($password, $salt = null)
	{
		require self::$publicSalt;
		
		if(is_null($salt))
			$salt = self::randomGen();
			
		return $salt . sha1($salt . sha1($password . GLOBAL_SALT));
	}
}