<?php

class urlfixer
{
	public static function prepend($html, $prefix = '')
	{
		if (empty($prefix)) { return $html; }
		
		$original = array(
					'/action="\//i', 
					"/action='\//i",
					'/src="\//i',
					"/src='\//i",
					'/href="\//i',
					"/href='\//i");
		$fixed = array(
					'action="'.$prefix.'/', 
					"action='".$prefix."/",
					'src="'.$prefix.'/', 
					"src='".$prefix."/",
					'href="'.$prefix.'/', 
					"href='".$prefix."/");
		
		return preg_replace($original, $fixed, $html);
		
	}
}