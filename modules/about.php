<?php

class about extends module
{
	public function __construct()
	{
		tools::store('page', 'about');
	}
	public function defaultaction()
	{
		include view::show('about');
	}
}