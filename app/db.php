<?php
defined('APP_ROOT') || exit;

function db_configured(): bool
{
    return (string) config('db.name', '') !== '';
}

/** Shared PDO connection, or null when no database is configured. Throws PDOException if it can't connect. */
function db(): ?PDO
{
    static $pdo;
    if ($pdo === null && db_configured()) {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            config('db.host', '127.0.0.1'),
            (int) config('db.port', 3306),
            config('db.name')
        );
        $pdo = new PDO($dsn, (string) config('db.user', ''), (string) config('db.pass', ''), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

/** Creates any missing tables from database/schema.sql. */
function db_install(): void
{
    $sql = (string) file_get_contents(APP_ROOT . '/database/schema.sql');
    $sql = preg_replace('/^\s*--.*$/m', '', $sql);
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        db()->exec($statement);
    }
}

function default_content(): array
{
    static $content;
    return $content ??= require APP_ROOT . '/app/content.php';
}

/**
 * Site content: the bundled defaults, with any sections saved in /admin layered on top.
 * Falls back to the defaults if the database is missing or unreachable.
 */
function content(): array
{
    static $content;
    if ($content !== null) {
        return $content;
    }
    $content = default_content();
    if (!db_configured()) {
        return $content;
    }
    try {
        foreach (db()->query('SELECT section, data FROM site_content') as $row) {
            $value = json_decode($row['data'], true);
            if (array_key_exists($row['section'], $content) && is_array($value)) {
                $content[$row['section']] = $value;
            }
        }
    } catch (Throwable $e) {
        error_log('Content load failed, using defaults: ' . $e->getMessage());
    }
    return $content;
}
