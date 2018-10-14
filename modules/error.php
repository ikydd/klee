<?php

class error extends module
{
	public function defaultaction()
	{
		include view::show('standard/errors');
	}
}