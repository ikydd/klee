<?php 
$errors = tools::retrieve('error', tools::REMOVE);

if (is_array($errors)) {
	echo '<div id="errors">';
	echo '<ul><li><span class="error">' . implode('</span></li><li><span>', $errors) . '</span></li></ul>';
	echo '</div>';
}
?>