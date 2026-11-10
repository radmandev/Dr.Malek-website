<?php
defined('APP_ROOT') || exit;

/** Reads config.php with dot notation, e.g. config('db.name'). */
function config(string $key, mixed $default = null): mixed
{
    static $config;
    if ($config === null) {
        $file = APP_ROOT . '/config.php';
        $config = is_file($file) ? (array) require $file : [];
    }
    $value = $config;
    foreach (explode('.', $key) as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return $default;
        }
        $value = $value[$part];
    }
    return $value;
}

/** Escapes text for HTML output. */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'
        || (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
}

/** Absolute URL for a site path. Uses config('site_url'), falling back to the request host. */
function site_url(string $path = ''): string
{
    $base = rtrim((string) config('site_url', ''), '/');
    if ($base === '') {
        $base = (is_https() ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    return $base . ($path === '/' ? '' : $path);
}

/** The request path without query string or trailing slash, e.g. "/about". */
function request_path(): string
{
    $path = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    return $path === '/' ? '/' : rtrim($path, '/');
}

/**
 * Allows only safe link/image targets from editable content: site-relative paths and
 * http(s), mailto and tel URLs. Anything else (e.g. javascript:) becomes ''.
 */
function safe_url(?string $url): string
{
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }
    if ($url[0] === '/' && !str_starts_with($url, '//')) {
        return $url;
    }
    return preg_match('~^(https?://|mailto:|tel:)~i', $url) ? $url : '';
}

/** Cache-busted URL for a file in public/assets. */
function asset(string $path): string
{
    $file = APP_ROOT . '/public/assets/' . $path;
    return '/assets/' . $path . (is_file($file) ? '?v=' . filemtime($file) : '');
}

/** JSON safe to embed inside a <script> element. */
function json_for_script(mixed $data): string
{
    return json_encode(
        $data,
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}

/** "1" -> "01". */
function pad2(int $n): string
{
    return str_pad((string) $n, 2, '0', STR_PAD_LEFT);
}

/** Renders templates/{name}.php with the given variables and returns the HTML. */
function render(string $template, array $vars = []): string
{
    extract($vars, EXTR_SKIP);
    ob_start();
    require APP_ROOT . '/templates/' . $template . '.php';
    return (string) ob_get_clean();
}

function wants_json(): bool
{
    return str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
}

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function redirect(string $to, int $status = 303): never
{
    header('Location: ' . $to, true, $status);
    exit;
}

function client_ip(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '');
}
