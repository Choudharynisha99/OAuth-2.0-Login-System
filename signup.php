<?php
require_once 'config.php';
require_once 'backend.php';
$loginUrl = $client->createAuthUrl();
$signup = new Crud();
$errors = [];
$success = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];
    $userData = $signup->GetData('users', "email='$email'");
    if ($userData) {
        $success[] = "User Already Exist !";
    } else {
        if ($pass === $cpass) {
            $data = $signup->InsertData(
                'users',
                [
                    "name" => $_POST['name'],
                    "email" => $_POST['email'],
                    "password" => $_POST['password']
                ]
            );
            if ($data) {
                $success[] = "Successfully Registered! Redirecting to login page";
                echo '<script>
                    setTimeout(function(){
                        window.location.href="index.php";
                    }, 2000);
                  </script>';
            } else {
                $errors[] = "Something Went Wrong!";
            }
        } else {
            $errors[] = "Password Doesn't Match!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <body class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <form class="form shadow" action="" method="POST">
            <?php
            if (count($success) > 0) {
                foreach ($success as $showsuccess) {
                    echo "<div class='alert alert-success'>$showsuccess</div>";
                }
            }
            if (count($errors) > 0) {
                foreach ($errors as $showerrors) {
                    echo "<div class='alert alert-danger'>$showerrors</div>";
                }
            }
            ?>
            <div class="mb-2 flex-column">
                <label for="email" class="form-label">Name</label>
                <div class="inputForm d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <input id="email" class="form-control input" type="text" placeholder="Enter your Name" name="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                </div>
            </div>
            <div class="mb-2 flex-column">
                <label for="email" class="form-label">Email</label>
                <div class="inputForm d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 32 32" height="20">
                        <g data-name="Layer 3" id="Layer_3">
                            <path d="m30.853 13.87a15 15 0 0 0 -29.729 4.082 15.1 15.1 0 0 0 12.876 12.918 15.6 15.6 0 0 0 2.016.13 14.85 14.85 0 0 0 7.715-2.145 1 1 0 1 0 -1.031-1.711 13.007 13.007 0 1 1 5.458-6.529 2.149 2.149 0 0 1 -4.158-.759v-10.856a1 1 0 0 0 -2 0v1.726a8 8 0 1 0 .2 10.325 4.135 4.135 0 0 0 7.83.274 15.2 15.2 0 0 0 .823-7.455zm-14.853 8.13a6 6 0 1 1 6-6 6.006 6.006 0 0 1 -6 6z"></path>
                        </g>
                    </svg>
                    <input id="email" class="form-control input" type="email" placeholder="Enter your Email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
            </div>

            <div class="mb-2 flex-column">
                <label for="password" class="form-label">Password</label>
                <div class="inputForm d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="-64 0 512 512" height="20">
                        <path d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"></path>
                        <path d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"></path>
                    </svg>
                    <input id="password" class="form-control input" type="password" placeholder="Enter your Password" name="password">
                </div>
            </div>
            <div class="mb-3 flex-column">
                <label for="password" class="form-label">Confirm Password</label>
                <div class="inputForm d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="-64 0 512 512" height="20">
                        <path d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"></path>
                        <path d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"></path>
                    </svg>
                    <input id="password" class="form-control input" type="password" placeholder="Confirm your Password" name="cpassword">
                </div>
            </div>


            <button type="submit" class="btn btn-dark w-100 mb-2 button-submit">Sign Up</button>

            <p class="text-center">Have an account? <a href="index.php" class="span text-decoration-none">Sign In</a></p>

            <div class="d-flex flex-column gap-2 mt-0">
                <a href="<?= htmlspecialchars($loginUrl) ?>" class="btn google d-flex justify-content-center align-items-center">
                    <svg xml:space="preserve" style="enable-background:new 0 0 512 512;" viewBox="0 0 512 512" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" id="Layer_1" width="20" version="1.1">
                        <path d="M113.47,309.408L95.648,375.94l-65.139,1.378C11.042,341.211,0,299.9,0,256
                        c0-42.451,10.324-82.483,28.624-117.732h0.014l57.992,10.632l25.404,57.644c-5.317,15.501-8.215,32.141-8.215,49.456
                        C103.821,274.792,107.225,292.797,113.47,309.408z" style="fill:#FBBB00;"></path>
                        <path d="M507.527,208.176C510.467,223.662,512,239.655,512,256c0,18.328-1.927,36.206-5.598,53.451
                        c-12.462,58.683-45.025,109.925-90.134,146.187l-0.014-0.014l-73.044-3.727l-10.338-64.535
                        c29.932-17.554,53.324-45.025,65.646-77.911h-136.89V208.176h138.887L507.527,208.176L507.527,208.176z" style="fill:#518EF8;"></path>
                        <path d="M416.253,455.624l0.014,0.014C372.396,490.901,316.666,512,256,512
                        c-97.491,0-182.252-54.491-225.491-134.681l82.961-67.91c21.619,57.698,77.278,98.771,142.53,98.771
                        c28.047,0,54.323-7.582,76.87-20.818L416.253,455.624z" style="fill:#28B446;"></path>
                        <path d="M419.404,58.936l-82.933,67.896c-23.335-14.586-50.919-23.012-80.471-23.012
                        c-66.729,0-123.429,42.957-143.965,102.724l-83.397-68.276h-0.014C71.23,56.123,157.06,0,256,0
                        C318.115,0,375.068,22.126,419.404,58.936z" style="fill:#F14336;"></path>

                    </svg>
                    Login With Google
                </a>
            </div>
        </form>

    </body>
</body>

</html>