<?php

// this probably works? who knows...
require '../includes/autoloader.php';

$hashme = 'some-password';

$user = new user();
$user->token = md5(uniqid(rand(), TRUE));
$user->ident = md5(uniqid(rand(), TRUE));
$user->timeout = 0;
$user->password = passwordhash::hash($hashme);

$user->save();

echo 'Admin user created';
