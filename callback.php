<?php
require_once 'config.php';
require_once 'backend.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        // $google_service = new Google_Service_Oauth2($client);
        $google_service =  new Google\Service\Oauth2($client);
        $google_user = $google_service->userinfo->get();

        $email = $google_user->email;
        $name = $google_user->name;
        $google_id = $google_user->id;

        $crud = new Crud();

        $existingUser = $crud->GetData("users", "email='$email'");
        if (empty($existingUser)) {
            $crud->InsertData("users", [
                "name" => $name,
                "email" => $email,
                "google_id" => $google_id
            ]);
        }

        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;

        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error retrieving token.";
    }
} else {
    echo "No code parameter.";
}
