<?php
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
        if (!empty($_POST['resend_otp']) && $_POST['resend_otp'] == '1') {
            if ($currentOtpExpiration && $currentTime < $currentOtpExpiration) {
                $errors[] = "You can resend OTP only after current OTP expires!";
            } else {
                $newOtp = rand(1000, 9999);
                $newExpiration = date('Y-m-d H:i:s', strtotime('+1 minute'));
                $crud->UpdateData('users', [
                    'otp' => $newOtp,
                    'otp_expiration' => $newExpiration
                ], "email='$email'");

                $success[] = "A new OTP has been sent!";
            }
        }
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
        <span id="resendOtp" style="color:blue; cursor:pointer; text-decoration:underline;">
            Resend OTP?
        </span>
        <input type="hidden" name="resend_otp" id="resend_otp_input" value="0">
        <input type="hidden" name="verify_otp" value="1">
        <!-- <input type="hidden" name="resend_otp" id="resend_otp_input" value="0"> -->
        <button type="submit" class="btn btn-dark btn-submit-otp w-100">Verify OTP</button>
    </form>

    <script>
        document.querySelectorAll('.otp-inputs input').forEach((input, index, inputs) => {
            input.addEventListener('input', () => {
                if (input.value && index < inputs.length - 1) inputs[index + 1].focus();
            });
        });

        document.getElementById('resendOtp').addEventListener('click', () => {
            document.getElementById('resend_otp_input').value = '1';
            document.getElementById('otpForm').submit();
        });
    </script>
</body>

</html>