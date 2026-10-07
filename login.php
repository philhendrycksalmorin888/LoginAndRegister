<?php
require_once __DIR__ . '/config.php';

if (isset($_SESSION['user_email'])) {
    header('Location: dashboard.php');
    exit;
}

$email = '';
$fieldErrors = [];
$generalError = '';
$success = isset($_GET['registered']) ? 'Registration successful. You can now sign in.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if (!valid_csrf($_POST['csrf_token'] ?? null)) {
        $generalError = 'Your session expired. Please refresh the page and try again.';
    }

    if ($email === '') {
        $fieldErrors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] = 'Enter a valid email address.';
    }

    if ($password === '') {
        $fieldErrors['password'] = 'Password is required.';
    }

    if (!$fieldErrors && $generalError === '') {
        $user = find_user_by_email($email);
        if (!$user || !isset($user['password']) || !password_verify($password, (string) $user['password'])) {
            $generalError = 'The email or password you entered is incorrect.';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_email'] = $user['email'];
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Student Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="auth-shell login-layout">
    <section class="visual-panel" aria-label="Welcome illustration">
        <div class="visual-content">
            <span class="brand-pill">STUDENT PORTAL</span>
            <div class="welcome-card">
                <img src="assets/earth_scene.png" alt="Colorful illustrated landscape">
                <div class="welcome-copy">
                    <p class="eyebrow">WELCOME BACK</p>
                    <h2>Good to see you again.</h2>
                    <p>Sign in to continue to your account.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="form-panel">
        <div class="form-wrap">
            <p class="eyebrow">ACCOUNT ACCESS</p>
            <div class="auth-card">
                <div class="card-heading">
                    <h1>Login</h1>
                    <p>Enter your account details below.</p>
                </div>

                <?php if ($success): ?>
                    <div class="message success" role="status">
                        <span class="message-icon">✓</span>
                        <span><?= e($success) ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($generalError): ?>
                    <div class="message error" role="alert">
                        <span class="message-icon">!</span>
                        <span><?= e($generalError) ?></span>
                    </div>
                <?php endif; ?>

                <form method="post" action="login.php" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                    <div class="field-group">
                        <label for="email">Email address</label>
                        <input class="<?= isset($fieldErrors['email']) ? 'invalid' : '' ?>" id="email" name="email" type="email" autocomplete="email" placeholder="name@example.com" value="<?= e($email) ?>" required>
                        <?php if (isset($fieldErrors['email'])): ?>
                            <small class="field-error"><?= e($fieldErrors['email']) ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="field-group">
                        <label for="password">Password</label>
                        <div class="password-field">
                            <input class="<?= isset($fieldErrors['password']) ? 'invalid' : '' ?>" id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required>
                            <button class="toggle-password" type="button" data-target="password" aria-label="Show password">Show</button>
                        </div>
                        <?php if (isset($fieldErrors['password'])): ?>
                            <small class="field-error"><?= e($fieldErrors['password']) ?></small>
                        <?php endif; ?>
                    </div>

                    <button class="primary-button" type="submit">Login</button>
                </form>

                <div class="divider"><span>or</span></div>
                <p class="switch-link">Don’t have an account? <a href="register.php">Create one</a></p>
            </div>
        </div>
    </section>
</main>
<script src="script.js"></script>
</body>
</html>
