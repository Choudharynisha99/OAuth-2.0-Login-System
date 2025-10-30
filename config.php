<?php

require 'vendor/autoload.php';

session_start();
$client = new Google_Client();
$client->setClientId('xyz');
$client->setClientSecret('abc');
$client->setRedirectUri('http://localhost/google_auth/callback.php');
$client->addScope('email');
$client->addScope('profile');
$client->setPrompt('select_account');
