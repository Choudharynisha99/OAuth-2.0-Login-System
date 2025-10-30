<?php

require 'vendor/autoload.php';

session_start();
$client = new Google_Client();
$client->setClientId('419152802361-9davv9412cbd4u7p6hm3pu0m0osu2rt9.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/google_auth/callback.php');
$client->addScope('email');
$client->addScope('profile');
$client->setPrompt('select_account');
