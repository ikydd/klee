<div id="navmenu">
<?php

$links = array(
	'Portfolio' => '/portfolio',
	'About' => '/about' );
$admin = array(
	'Pictures' => '/admin/pictures',
	'Collections' => '/admin/collections',
	'Profile' => '/admin/profile',
	'Logout' => '/logout' );
if (auth::isLoggedIn()){
	$links = array_merge($links, $admin);
}

$page = tools::retrieve('page', tools::REMOVE);
foreach ($links as $k => $v){
	echo "<a href='{$v}'";
	if ($page == strtolower($k)) echo " class='navselected'";
	echo ">{$k}</a>";
}

?>
</div>
