<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

    <div class="dashboard shadow">
        <h2 class="mb-2">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h2>
        <p class="text-muted mb-4">Email: <?= htmlspecialchars($_SESSION['user_email']) ?></p>
        <a href="logout.php" class="btn btn-dark w-100 text-center">Logout</a>
    </div>

</body>

</html>