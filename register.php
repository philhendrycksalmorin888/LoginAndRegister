<?php
require_once __DIR__ . '/config.php';

if (isset($_SESSION['user_email'])) {
    header('Location: dashboard.php');
    exit;
}

$email = '';
$fieldErrors = [];
$generalError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if (!valid_csrf($_POST['csrf_token'] ?? null)) {
        $generalError = 'Your session expired. Please refresh the page and try again.';
    }

    if ($email === '') {
        $fieldErrors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] = 'Enter a valid email address.';
    } elseif (find_user_by_email($email)) {
        $fieldErrors['email'] = 'An account with this email already exists.';
    }

    if ($password === '') {
        $fieldErrors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $fieldErrors['password'] = 'Use at least 8 characters.';
    } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
        $fieldErrors['password'] = 'Include at least one letter and one number.';
    }

    if ($confirmPassword === '') {
        $fieldErrors['confirm_password'] = 'Please confirm your password.';
    } elseif ($password !== $confirmPassword) {
        $fieldErrors['confirm_password'] = 'Passwords do not match.';
    }

    if (!$fieldErrors && $generalError === '') {
        $users = load_users();
        $users[] = [
            'email' => strtolower($email),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date(DATE_ATOM),
        ];

        if (save_users($users)) {
            header('Location: login.php?registered=1');
            exit;
        }

        $generalError = 'Registration could not be saved. Please try again.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | Student Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="auth-shell register-layout">
    <section class="form-panel">
        <div class="form-wrap">
            <p class="eyebrow">NEW ACCOUNT</p>
            <div class="auth-card">
                <div class="card-heading">
                    <h1>Create account</h1>
                    <p>Register with your email and a secure password.</p>
                </div>

                <?php if ($generalError): ?>
                    <div class="message error" role="alert">
                        <span class="message-icon">!</span>
                        <span><?= e($generalError) ?></span>
                    </div>
                <?php endif; ?>

                <form method="post" action="register.php" novalidate>
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
                            <input class="<?= isset($fieldErrors['password']) ? 'invalid' : '' ?>" id="password" name="password" type="password" autocomplete="new-password" placeholder="Create a password" required minlength="8">
                            <button class="toggle-password" type="button" data-target="password" aria-label="Show password">Show</button>
                        </div>
                        <?php if (isset($fieldErrors['password'])): ?>
                            <small class="field-error"><?= e($fieldErrors['password']) ?></small>
                        <?php else: ?>
                            <small class="field-hint">At least 8 characters with a letter and a number.</small>
                        <?php endif; ?>
                    </div>

                    <div class="field-group">
                        <label for="confirm_password">Confirm password</label>
                        <div class="password-field">
                            <input class="<?= isset($fieldErrors['confirm_password']) ? 'invalid' : '' ?>" id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" placeholder="Repeat your password" required minlength="8">
                            <button class="toggle-password" type="button" data-target="confirm_password" aria-label="Show password">Show</button>
                        </div>
                        <?php if (isset($fieldErrors['confirm_password'])): ?>
                            <small class="field-error"><?= e($fieldErrors['confirm_password']) ?></small>
                        <?php endif; ?>
                    </div>

                    <button class="primary-button" type="submit">Create account</button>
                </form>

                <div class="divider"><span>or</span></div>
                <p class="switch-link">Already registered? <a href="login.php">Back to login</a></p>
            </div>
        </div>
    </section>

    <section class="visual-panel register-visual" aria-label="Registration illustrations">
        <div class="visual-content register-copy-wrap">
            <span class="brand-pill">STUDENT PORTAL</span>
            <div class="art-stack">
                <img class="red-art" src="assets/red_scene.png" alt="Red moon silhouette illustration">
                <img class="earth-art" src="assets/earth_scene.png" alt="Colorful illustrated landscape">
            </div>
            <div class="side-copy">
                <p class="eyebrow">START HERE</p>
                <h2>Simple, secure, and easy to use.</h2>
                <p>Create your account in just a few seconds.</p>
            </div>
        </div>
    </section>
</main>
<script src="script.js"></script>
</body>
</html>
