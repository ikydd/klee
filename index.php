<?php

require 'includes/autoloader.php';
session_start();

$site = new sitepath();

// get the url and fill if blank
$controller = new controller($_GET['url']);

ob_start();

try {
// get content
	$controller->render();
} catch (Exception $e) {
	echo $e->getMessage();
}

$content = ob_get_contents();

ob_clean();

// html header block
include view::show('standard/header');

// display content
echo "<div id='content'>{$content}</div>";

// html footer block
include view::show('standard/footer');

$content = ob_get_contents();

$content = urlfixer::prepend($content, SITEPATH);

ob_end_clean();

echo $content;
