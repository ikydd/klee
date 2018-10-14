<?php

class pictures extends module
{
	public function __construct()
	{
		if (!auth::isLoggedIn()) tools::sendTo('/login');
		tools::store('page', 'pictures');
	}
	protected $itemsOnPage = 10;
	protected $picture_dir = "public/portfolio/";
	protected $maxX = 920;
	protected $maxY = 700;
	
	public function defaultaction()
	{
		$this->show();
	}
	public function show($params = null)
	{
		// acquire media data
		$fullmedia = new picturestack();
		$fullmedia->getData();
		$fullmedia->sort('title');
		
		$pagecount = ceil($fullmedia->count()/$this->itemsOnPage);
		// initialise data array
		$media = array();
		// swap iterator into an array so array chunk works
		foreach($fullmedia as $picture) {
			$media[] = $picture;
		}
		// break array into chunks for each page
		$media = array_chunk($media, $this->itemsOnPage);
		
		// make page from parameter
		$pagenumber = is_null($params[0]) ? 0 : (int) $params[0] - 1;
		$pagecount = count($media);
		$pictures = $media[$pagenumber];
		$pagetype = 'admin/pictures/show';
		
		include view::show('pictures/show');
	}
	
	public function edit($params = array())
	{
		// check id is valid
		if (empty($params[0])) {
			tools::sendTo('/pictures');
		}
		// create new media item
		$picture = new picture($params[0]);
		if ($picture->picture_id) {
			// check exists properly before showing.
			include view::show('pictures/edit');
		}
		else {
			tools::setError('The page you are looking for does not exist');
			tools::sendTo('/error');
		}
		
	}
	public function upload()
	{
		$title = $_POST['title'];
		$caption = $_POST['caption'];
		$clean['title'] = inspector::isText($title);
		$clean['caption'] = inspector::isText($caption);
		
		// check is uploaded
		if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
			// check file type
			if (($_FILES['uploadfile']["type"] != "image/gif")
				&& ($_FILES['uploadfile']["type"] != "image/jpeg")
				&& ($_FILES['uploadfile']["type"] != "image/pjpeg")) {
				// kick if not valid type
				tools::setError('Invalid file type for your upload - jpg and gif only are allowed');
				tools::sendTo('/admin/pictures');
			}
			// check for errors
			if ($_FILES['upload']["error"] > 0) {
				tools::setError('There was an error uploading your file.');
				tools::sendTo('/admin/pictures');
			}
			// explode path in order to pop end segment
			$filename = basename($_FILES['uploadfile']['name']);
			$filename = strtolower($filename);
			//$filename = str_replace(" ", "_", $filename);
			// create path names for program
			$path = $this->picture_dir . $filename;
			$thumbpath = $this->picture_dir . 'thumbs/' . $filename;
			$screenpath = $this->picture_dir . 'screens/' . $filename;
			// check file doesn't exist already
			if (file_exists($path)) {
				tools::setError('File already previously uploaded.');
				tools::sendTo('/admin/pictures');
			}
			$parts = pathinfo($filename);
			$temp = $this->picture_dir . 'temp.' . $parts['extension'];
			// move file to temp position
			move_uploaded_file($_FILES['uploadfile']['tmp_name'], $temp);
			
			// check size for resizing if necessary
			$resize = new checkandresize($temp);
			
			// resize if bigger than max sizes
			if ($resize->check($this->maxX, $this->maxY)) {
				$resize->resizeImage($this->maxX, $this->maxY, 'auto');
			}
			else {
				$resize->resizeImage($this->maxX, $this->maxY, 'none');
			}
			// save
			$resize->saveImage($path, 100);
			// thumbnail
			$thumb = new checkandresize($temp);
			$thumb->resizeImage(100, 80, 'auto');
			$thumb->saveImage($thumbpath, 60);
			// screenshot
			$screen = new checkandresize($temp);
			$screen->resizeImage(525, 525, 'auto');
			$screen->saveImage($screenpath, 60);
			
			if (!file_exists($path) || !file_exists($thumbpath) || !file_exists($screenpath)) {
				unlink($path);
				unlink($thumbpath);
				unlink($screenpath);
				tools::setError('There was a problem uploading your file.');
				tools::sendTo('/admin/pictures');
			}
			
			unlink($temp);
			
			// create datachunk and fill in
			$picture = new picture();
			$picture->filename = $filename;
			$picture->caption = $caption;
			$picture->title = $title;
			$picture->save();
			
			tools::store('uploaded', $picture);
			tools::store('caption', null);
			tools::store('title', null);
			tools::setError('Shiny new picture uploaded.');
			tools::sendTo('/admin/pictures');
		}
		else {
			tools::store('caption', $caption);
			tools::store('title', $title);
			tools::setError('There was a problem with your upload, please try again.');
			tools::sendTo('/admin/pictures');
		}
	}
	
	public function processedit()
	{
		// grab details
		$picture_id = $_POST['picture_id'];
		$title = $_POST['title'];
		$caption = $_POST['picturecaption'];
		
		$clean['picture_id'] = inspector::isNumberInt($picture_id);
		$clean['title'] = inspector::isText($title);
		$clean['caption'] = inspector::isText($caption);

		try {
			// check for bodytext
			if (empty($clean['picture_id'])) {
				tools::setError('An error occurred.' . $picture_id);
				throw new Exception();
			}
			// regenerate event
			$picture = new picture($clean['picture_id']);

			// stick in the data
			$picture->title = $clean['title'];
			$picture->caption = htmlentities($clean['caption']);
			
			$picture->save();

			tools::store('title', null); // don't use clean as it may be null
			tools::store('caption', null);
			tools::setError('Picture details edited.');
			tools::sendTo('/admin/pictures');
			
		} catch (Exception $e) {
			tools::store('title', $title); // don't use clean as it may be null
			tools::store('caption', $caption);
			tools::sendTo('/admin/pictures/edit/' . $picture_id);
		}
	}
	public function delete($params = array())
	{
		// check parameter
		if (!empty($params[0])) {
		
			$picture = new picture($params[0]);
			if ($picture->filename){
			
				$file = $this->picture_dir . $picture->filename;
				unlink($file);
				$thumb = $this->picture_dir . 'thumbs/' . $picture->filename;
				unlink($thumb);
				$screen = $this->picture_dir . 'screens/' . $picture->filename;
				unlink($screen);
				
				$collections = new collectionstack();
				$collections->getData();
				
				foreach ($collections as $collection){
					$pics = $collection->pictures ? explode(":", $collection->pictures) : array();
					$key = array_search($picture->picture_id, $pics);
					if ($key !== false){
						array_splice($pics, $key, 1);
						$collection->pictures = implode(":", $pics);
						$collection->save();
					}
				}
				
				$db = database::factory($picture->database);
				$db->delete($picture->table, array('picture_id' => $picture->picture_id));
			}
		}
		
		tools::setError('Got rid of that picture for you.');
		tools::sendTo('/admin/pictures');
	}
}