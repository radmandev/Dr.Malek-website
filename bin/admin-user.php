<?php
// Creates an administrator, or resets an existing administrator's password.
// Usage (from the project folder):  php bin/admin-user.php "username"   (quote it if it contains spaces)
// Useful when you have SSH access, or if the password has been forgotten (there is no email reset).

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit;
}

require dirname(__DIR__) . '/app/bootstrap.php';

$username = mb_strtolower(trim((string) preg_replace('/\s+/u', ' ', $argv[1] ?? '')));
if (mb_strlen($username) < 3 || mb_strlen($username) > 190) {
    fwrite(STDERR, "Usage: php bin/admin-user.php \"username\"  (3–190 characters; quote it if it contains spaces)\n");
    exit(1);
}
if (!db_configured()) {
    fwrite(STDERR, "No database configured. Fill in the db settings in config.php first.\n");
    exit(1);
}

/** Reads a line without echoing it (falls back to visible input where stty is unavailable). */
function prompt_secret(string $label): string
{
    echo $label;
    $code = 1;
    if (DIRECTORY_SEPARATOR === '/' && function_exists('exec')) {
        @exec('stty -echo 2>/dev/null', $output, $code);
    }
    $value = rtrim((string) fgets(STDIN), "\r\n");
    if ($code === 0) {
        @exec('stty echo 2>/dev/null');
        echo "\n";
    }
    return $value;
}

$password = prompt_secret('New password (at least 10 characters): ');
if (mb_strlen($password) < 10) {
    fwrite(STDERR, "Password too short.\n");
    exit(1);
}
if (!hash_equals($password, prompt_secret('Confirm password: '))) {
    fwrite(STDERR, "Passwords do not match.\n");
    exit(1);
}

db_install();
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = db()->prepare('SELECT id FROM admins WHERE email = ?'); // `email` holds the sign-in name
$stmt->execute([$username]);
if ($id = $stmt->fetchColumn()) {
    db()->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')->execute([$hash, $id]);
    echo "Password updated for $username.\n";
} else {
    db()->prepare('INSERT INTO admins (email, password_hash) VALUES (?, ?)')->execute([$username, $hash]);
    echo "Administrator $username created.\n";
}
db()->exec('DELETE FROM login_attempts');
