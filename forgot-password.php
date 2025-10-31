<?php
session_start();
require_once 'backend.php';
$errors = [];
$success = [];
$crud = new Crud();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $userRows = $crud->GetData('users', "email='$email'");
    if (!empty($userRows) && isset($userRows[0])) {
        $user = $userRows[0];
        $username = $user['name'];
        $otp = rand(1000, 9999);
        date_default_timezone_set('Asia/Kolkata');
        $otp_expiration = date('Y-m-d H:i:s', strtotime('+1 minutes'));
        $update = $crud->UpdateData('users', [
            'otp' => $otp,
            'otp_expiration' => $otp_expiration
        ], "email='$email'");
        if ($update) {
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_name'] = $username;
            // (optional) send OTP by email in future
            // mail($email, "Your OTP Code", "Your OTP is: $otp");
            header('Location: otp.php');
            exit();
        } else {
            $errors[] = "Could not send OTP. Try again.";
        }
    } else {
        $errors[] = "Email does not exist in our records.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <body class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <form class="form shadow" action="" method="POST">
            <?php
            if (count($errors) > 0) {
                foreach ($errors as $showerrors) {
                    echo "<div class='alert alert-danger'>$showerrors</div>";
                }
            }
            ?>
            <div class="mb-3 flex-column">
                <label for="email" class="form-label">Email</label>
                <div class="inputForm d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 32 32" height="20">
                        <g data-name="Layer 3" id="Layer_3">
                            <path d="m30.853 13.87a15 15 0 0 0 -29.729 4.082 15.1 15.1 0 0 0 12.876 12.918 15.6 15.6 0 0 0 2.016.13 14.85 14.85 0 0 0 7.715-2.145 1 1 0 1 0 -1.031-1.711 13.007 13.007 0 1 1 5.458-6.529 2.149 2.149 0 0 1 -4.158-.759v-10.856a1 1 0 0 0 -2 0v1.726a8 8 0 1 0 .2 10.325 4.135 4.135 0 0 0 7.83.274 15.2 15.2 0 0 0 .823-7.455zm-14.853 8.13a6 6 0 1 1 6-6 6.006 6.006 0 0 1 -6 6z"></path>
                        </g>
                    </svg>
                    <input id="email" class="form-control input" type="text" placeholder="Enter your Email" name="email">
                </div>
            </div>
            <button type="submit" class="btn btn-dark w-100 mb-3 button-submit">Get Code</button>
        </form>
    </body>
</body>

</html>