<?php
session_start();
require_once 'backend.php';
$errors = [];
$success = [];
$crud = new Crud();

if (!isset($_SESSION['reset_email'])) {
    header('Location: forgot-password.php');
    exit();
}

$email = $_SESSION['reset_email'];
$username = $_SESSION['reset_name'] ?? '';

date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userRows = $crud->GetData('users', "email='$email'");
    if (empty($userRows)) {
        $errors[] = "User not found.";
    } else {
        $user = $userRows[0];
        $currentOtp = $user['otp'];
        $currentOtpExpiration = $user['otp_expiration'];
        $currentTime = date('Y-m-d H:i:s');
        if (!empty($_POST['otp'])) {
            $enteredOtp = implode('', $_POST['otp'] ?? []);
            $userRows = $crud->GetData('users', "email='$email'");
            $user = $userRows[0];
            $currentOtp = $user['otp'];
            $currentOtpExpiration = $user['otp_expiration'];

            if ($enteredOtp === $currentOtp) {
                if ($currentTime <= $currentOtpExpiration) {
                    $_SESSION['otp_verified'] = true;
                    header('Location: update-password.php');
                    exit();
                } else {
                    $errors[] = "OTP expired. Please resend OTP.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Enter OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <style>
        .otp-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            background-color: #fff;
            padding: 30px;
            width: 400px;
            border-radius: 20px;
            text-align: center;
        }

        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .otp-inputs input {
            width: 60px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            border-radius: 10px;
            border: 1.5px solid #727672;
            transition: border 0.2s ease-in-out;
        }

        .otp-inputs input:focus {
            outline: none;
            border-color: #2d79f3;
            box-shadow: 0 0 5px rgba(45, 121, 243, 0.5);
        }

        .btn-submit-otp {
            font-size: 16px;
            font-weight: 500;
            height: 50px;
            border-radius: 10px;
            border: none;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <form class="p-4 bg-white shadow rounded text-center" method="POST" id="otpForm">
        <h4>Hey <strong><?= htmlspecialchars($username) ?></strong> 👋</h4>
        <p>We’ve sent a 4-digit code to <b><?= htmlspecialchars($email) ?></b></p>

        <?php foreach ($success as $msg): ?>
            <div class="alert alert-success"><?= $msg ?></div>
        <?php endforeach; ?>
        <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger"><?= $err ?></div>
        <?php endforeach; ?>

        <div class="otp-inputs mb-3">
            <input type="text" name="otp[]" maxlength="1" required>
            <input type="text" name="otp[]" maxlength="1" required>
            <input type="text" name="otp[]" maxlength="1" required>
            <input type="text" name="otp[]" maxlength="1" required>
        </div>
        <button type="submit" class="btn btn-dark btn-submit-otp w-100">Verify OTP</button>
    </form>

    <script>
        document.querySelectorAll('.otp-inputs input').forEach((input, index, inputs) => {
            input.addEventListener('input', () => {
                if (input.value && index < inputs.length - 1) inputs[index + 1].focus();
            });
        });
    </script>
</body>

</html>