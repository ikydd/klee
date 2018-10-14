<?php

interface authenticatorinterface
{
	public function authenticate(user $user, $match);
}