<?php

class portfolio extends module
{
	public function __construct()
	{
		tools::store('page', 'portfolio');
	}
	public function defaultaction($params = array())
	{
		// get param data
		$selection = $params[0];
		
		$collections = new collectionstack();
		$collections->getData();
		$collections->sort('position');

		foreach ($collections as $collection){
			include view::show('portfolio/menu');
		}
		// init display
		$display = '';
		// iterate over collection to find right one
		foreach ($collections as $collection){
			if ($collection->slug == $selection) $display = $collection;
		}
		// check result is right type
		if ($display instanceof collection) {
			
			// get pic IDs
			$pics = explode(":", $display->pictures);
			
			$pictures = array();
			// iterate over IDs and create pictures from it
			foreach ($pics as $p){
				if (empty($p)) continue;
				$pictures[] = new picture($p);
			}
			// show pics
			include view::show('portfolio/images');
			
		}
		else if ($selection && $selection != 'defaultaction') {
			tools::setError('Sorry, but that collection is nowhere to found.');

			include view::show('standard/errors');
		}
	}
}