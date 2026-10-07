<?php
// Shared helpers for the Login / Registration laboratory exercise.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

const USERS_FILE = __DIR__ . '/data/users.json';

function ensure_users_file(): void
{
    $dir = dirname(USERS_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    if (!file_exists(USERS_FILE)) {
        file_put_contents(USERS_FILE, "[]\n", LOCK_EX);
    }
}

function load_users(): array
{
    ensure_users_file();
    $json = file_get_contents(USERS_FILE);
    $users = json_decode($json ?: '[]', true);
    return is_array($users) ? $users : [];
}

function save_users(array $users): bool
{
    ensure_users_file();
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    return file_put_contents(USERS_FILE, $json . PHP_EOL, LOCK_EX) !== false;
}

function find_user_by_email(string $email): ?array
{
    $email = strtolower(trim($email));
    foreach (load_users() as $user) {
        if (isset($user['email']) && strtolower((string) $user['email']) === $email) {
            return $user;
        }
    }
    return null;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function valid_csrf(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
