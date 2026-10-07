<?php
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Student Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">
    <div class="dashboard-card">
        <div class="dashboard-badge">✓</div>
        <p class="eyebrow">LOGIN SUCCESSFUL</p>
        <h1>Welcome!</h1>
        <p>You are signed in as <strong><?= e((string) $_SESSION['user_email']) ?></strong>.</p>
        <a class="button-link" href="logout.php">Logout</a>
    </div>
</body>
</html>
