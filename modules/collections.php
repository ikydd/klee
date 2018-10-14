<?php

class collections extends module
{
	public function __construct()
	{
		if (!auth::isLoggedIn()) tools::sendTo('/login');
		tools::store('page', 'collections');
	}
	public function defaultaction()
	{
		$this->show();
	}
	public function show()
	{
		// acquire data
		$collections = new collectionstack();
		$collections->getData();
		$collections->sort('position');
		
		include view::show('collections/show');
	}
	public function create()
	{
		// scoop and clean
		$title = $_POST['title'];
		$slug = $_POST['slug'];
		$description = $_POST['description'];
		$clean['title'] = inspector::isText($title);
		$clean['slug'] = inspector::isUsername($slug);
		$clean['description'] = inspector::isText($description);
		
		$clean['slug'] = strtolower($clean['slug']);
		
		try {
			// check for title
			if (!$clean['title']){
				tools::setError('Please fill in a title.');
				throw new Exception();
			}
			// check for slug
			if (!$clean['slug']){
				tools::setError('Please fill in a valid web address slug.');
				throw new Exception();
			}
			// check slug has not been used before
			$test = new collection(array('slug' => $clean['slug']));
			if ($test->collection_id) {
				tools::setError('That url slug is already in use.');
				throw new Exception();
			}
			
			// input details
			$collection = new collection();
			$collection->title = $clean['title'];
			$collection->slug = $clean['slug'];
			$collection->description = $clean['description'];
			// stick at the back of the queue
			$collections = new collectionstack();
			$collections->getData();
			$number = $collections->count();
			$collection->position = $number;
			// save it all
			$collection->save();
			
			// wipe and reset for next round
			tools::store('title', null);
			tools::store('slug', null);
			tools::store('description', null);
			tools::setError('New collection created, time to add some pictures.');
			tools::sendTo('/admin/collections/edit/' . $collection->collection_id);
			
		} catch (Exception $e) {
			tools::store('title', $title);
			tools::store('slug', $slug);
			tools::store('description', $description);
			tools::sendTo('/admin/collections');
		}
	}

	public function edit($params = array())
	{
		$clean['collection'] = inspector::isNumberInt($params[0]);
		// check id is valid
		if (is_null($clean['collection'])) {
			tools::sendTo('/admin/collections');
		}
		// create new media item
		$collection = new collection($params[0]);
		if ($collection->collection_id) {
			// check exists properly before showing
			
			$pictures = new picturestack();
			$pictures->getData();
			$pictures->sort('title');
			$collection_pics = $collection->pictures ? explode(":", $collection->pictures) : array();
			
			include view::show('collections/edit');
		}
		else {
			tools::setError('The page you are looking for does not exist');
			tools::sendTo('/error');
		}
	}
	public function processedit()
	{
		// grab details
		$collection_id = $_POST['collection_id'];
		$title = $_POST['title'];
		$description = $_POST['description'];
		$slug = $_POST['slug'];
		
		$clean['collection_id'] = inspector::isNumberInt($collection_id);
		$clean['title'] = inspector::isText($title);
		$clean['description'] = inspector::isText($description);
		$clean['slug'] = inspector::isUserName($slug);
		
		$clean['slug'] = strtolower($clean['slug']);

		try {
			// check for bodytext
			if (is_null($clean['collection_id'])) {
				tools::setError('An error occurred.');
				throw new Exception();
			}
			// regenerate event
			$collection = new collection($clean['collection_id']);

			// stick in the data
			$collection->title = $clean['title'];
			$collection->slug = $clean['slug'];
			$collection->description = $clean['description'];
			
			$collection->save();
			
			tools::store('title', null); // don't use clean as it may be null
			tools::store('collection', null);
			tools::store('slug', null);
			tools::setError('Collection details edited quick-sharp.');
			tools::sendTo('/admin/collections');
			
		} catch (Exception $e) {
			tools::store('title', $title); // don't use clean as it may be null
			tools::store('collection', $collection);
			tools::store('slug', $slug);
			tools::sendTo('/admin/collections/edit/' . $collection_id);
		}
	}
	public function processadd()
	{
		$collection_id = $_POST['collection_id'];
		$picture_id = $_POST['picture_id'];
		$insert_id = $_POST['insert_id'];
		
		$clean['collection_id'] = inspector::isNumberInt($collection_id);
		$clean['picture_id'] = inspector::isNumberInt($picture_id);
		$clean['insert_id'] = inspector::isNumberInt($insert_id);
		
		try {
			if (is_null($clean['collection_id'])){
				tools::setError('Erm, something when wrong with the collection.');
				throw new Exception();
			}
			if (is_null($clean['picture_id'])){
				tools::setError('No picture was selected.');
				throw new Exception();
			}
			$collection = new collection($clean['collection_id']);
			if (!$collection->slug) {
				tools::setError('The collection has not come out right. Please try again.');
				throw new Exception();
			}			
			$picture = new picture($clean['picture_id']);
			if (!$picture->filename){
				tools::setError('Nope can\'t find that picture anywhere. Try again.');
				throw new Exception();
			}
			// must use this longer process as an empty pictures field results in array(0=>)
			$pics = $collection->pictures ? explode(":", $collection->pictures) : array();
			// initialise clean array
			$final = array();
			$insert = 0;
			$ins = false;
			foreach ($pics as $pic){
				if ($insert == $clean['insert_id'] && !is_null($clean['insert_id'])){
					$final[] = $picture->picture_id;
					$ins = true;
				}
				$final[] = $pic;
				$insert++;
			}
			// if null do it at end.
			if (is_null($clean['insert_id'])){$final[] = $picture->picture_id;}
			
			$collection->pictures = implode(":", $final);
			$collection->save();
			
			tools::setError('Picture added to collection.');
			tools::sendTo('/admin/collections/edit/' . $collection_id);
			
		} catch (Exception $e) {
			tools::sendTo('/admin/collections/edit/' . $collection_id);
		}
	}
	public function reorder()
	{
		$collections = new collectionstack();
		$collections->getData();
		$collections->sort('position');
		
		$amount = $collections->count();
		
		include view::show('collections/reorder');
	}
	public function processreorder()
	{
		// intialise array
		$colls = array();
		// iterate over all of POST to find relevant data
		foreach ($_POST as $k => $v) {
			// search for 'coll_'
			if (strpos($k, 'coll_') !== false) {
				// replace 'coll_' with ''
				$coll = str_replace('coll_', '', $k);
				// stick date in array
				$colls[$coll] = $v;
			}
		}
		
		if (empty($colls)) {
			tools::setError('There was an oopsie with the collections, couldn\'t re-order them for you.');
			tools::sendTo('/admin/collections/reorder');
		}
		
		// iterate over valid results
		foreach ($colls as $k => $v) {
			$collection = new collection($k);
			// if valid collection
			if ($collection->collection_id) {
				// update and save
				$collection->position = $v;
				$collection->save();
			}
		}
		
		tools::setError('Collections re-jiggled alright.');
		tools::sendTo('/admin/collections/reorder');
		
	}
	public function remove($params = array())
	{
		$clean['collection'] = inspector::isNumberInt($params[0]);
		$clean['picture'] = inspector::isNumberInt($params[1]);
		
		try {
			if (is_null($clean['collection'])){
				tools::setError('Something broke when trying to remove a picture from the list.');
				throw new Exception();
			}
			$collection = new collection($clean['collection']);
			if (!$collection->pictures){
				tools::setError("Something broke when trying to remove a picture from the list.");
				throw new Exception();
			}
			if (is_null($clean['picture'])){
				tools::setError("Something broke when trying to remove a picture from the list");
				throw new Exception();
			}
			
			// must use this longer process as an empty pictures field results in array(0=>)
			$pics = $collection->pictures ? explode(":", $collection->pictures) : array();
			// splice returns the removed parts and actually splices the array
			array_splice($pics, $clean['picture'], 1);
			$collection->pictures = implode(":", $pics);
			$collection->save();
			
			tools::setError('Picture tossed from the collection.');
			tools::sendTo('/admin/collections/edit/' . $collection->collection_id);
			
		} catch (Exception $e) {
			tools::sendTo('/admin/collections/edit/' . $collection->collection_id);
		}
	}
	public function delete($params = array())
	{
		// check parameter
		if (!empty($params[0])) {
		
			$collection = new collection($params[0]);
			if ($collection->collection_id){

				$db = database::factory($collection->database);
				$db->delete($collection->table, array('collection_id' => $collection->collection_id));
				
			}
		}
		
		tools::setError('Collection taken out back and shot for good.');
		tools::sendTo('/admin/collections');
	}
}