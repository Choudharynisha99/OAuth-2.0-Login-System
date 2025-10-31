<?php
session_start();
require_once 'backend.php';
$errors = [];
$success = [];
$crud = new Crud();
if (!isset($_SESSION['reset_email']) || !isset($_SESSION['otp_verified'])) {
    header('Location: forgot-password.php');
    exit();
}
$email = $_SESSION['reset_email'];
$username = $_SESSION['reset_name'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['npassword'] ?? '');
    $cpassword = trim($_POST['cpassword'] ?? '');
    if ($password === '' || $cpassword === '') {
        $errors[] = "Please fill in all fields.";
    } elseif ($password !== $cpassword) {
        $errors[] = "Passwords do not match.";
    } else {
        $update = $crud->UpdateData('users', [
            'password' => $password,
            'otp' => NULL,
            'otp_expiration' => NULL
        ], "email='$email'");

        if ($update) {
            unset($_SESSION['reset_email'], $_SESSION['otp_verified'], $_SESSION['reset_name']);
            $success[] = "Password updated successfully! <a href='index.php' class='alert-link'>Login here</a>.";
        } else {
            $errors[] = "Something went wrong. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <form class="form shadow" action="" method="POST">
            <h4 class="text-center mb-3">Hello, <strong><?= htmlspecialchars($username) ?></strong> 👋</h4>
            <p class="text-center text-muted">You're updating your password for <b><?= htmlspecialchars($email) ?></b></p>

            <?php foreach ($success as $msg): ?>
                <div class="alert alert-success"><?= $msg ?></div>
            <?php endforeach; ?>
            <?php foreach ($errors as $err): ?>
                <div class="alert alert-danger"><?= $err ?></div>
            <?php endforeach; ?>
            <div class="mb-3 flex-column"> <label for="password" class="form-label">New Password</label>
                <div class="inputForm d-flex align-items-center"> <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="-64 0 512 512" height="20">
                        <path d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"></path>
                        <path d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"></path>
                    </svg> <input id="password" class="form-control input" type="password" placeholder="Enter your Password" name="npassword"> </div>
            </div>
            <div class="mb-3 flex-column"> <label for="password" class="form-label">Confirm Password</label>
                <div class="inputForm d-flex align-items-center"> <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="-64 0 512 512" height="20">
                        <path d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"></path>
                        <path d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"></path>
                    </svg> <input id="password" class="form-control input" type="password" placeholder="Enter your Password" name="cpassword"> </div>
            </div> <button type="submit" class="btn btn-dark w-100 mb-3 button-submit">Update Password</button>
        </form>
    </div>
</body>

</html>