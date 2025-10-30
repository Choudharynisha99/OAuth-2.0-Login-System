<?php
// session_start();
// session_unset();
// session_destroy();
// Optional: log out from Google completely
// $googleLogout = 'https://accounts.google.com/Logout';
// header('Location: ' . $googleLogout);
// exit;
// Or if you just want to go back to index.php
// header('Location: index.php');
// exit();
require_once 'config.php';
require_once 'backend.php';

$crud = new Crud();

if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];

    // Update logout time in database
    $crud->UpdateData("users", ["last_logout" => date("Y-m-d H:i:s")], "email='$email'");
}

// Destroy session
session_unset();
session_destroy();

// Redirect to index
header('Location: index.php');
exit();
