<?php
declare(strict_types=1);

// Front controller: every request that isn't an existing file in public/ arrives here.

// PHP's built-in server (local development): serve existing static files in public/ directly.
if (PHP_SAPI === 'cli-server') {
    $file = realpath(__DIR__ . rawurldecode((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    if ($file !== false && $file !== __FILE__ && str_starts_with($file, __DIR__ . DIRECTORY_SEPARATOR) && is_file($file)) {
        return false;
    }
}

require dirname(__DIR__) . '/app/bootstrap.php';

const SITE_NAME = 'Dr. Malek Gaashan';
const DEFAULT_TITLE = 'Dr. Malek Gaashan — Consultant Robotic Urological Surgeon';
const DEFAULT_DESCRIPTION = 'Dr. Malek Gaashan, MD, MRCS, FRCS (Urol) — Consultant Urological Surgeon specialising in robotic radical cystectomy, bladder cancer surgery and intracorporeal urinary diversion at University Hospitals of Leicester.';

// path => [template, title, meta description]. A null title/description uses the site defaults.
const PAGES = [
    '/' => ['home', null, null],
    '/about' => ['about', 'About', 'Biography, career, qualifications and awards of Dr. Malek Gaashan, Consultant Urological Surgeon at University Hospitals of Leicester NHS Trust.'],
    '/expertise' => ['expertise', 'Surgical Expertise — Robotic Cystectomy & Intracorporeal Urinary Diversion', 'Robotic radical cystectomy, intracorporeal ileal conduit and neobladder reconstruction, complex bladder reconstruction, uro-oncology and organ-sparing approaches.'],
    '/experience' => ['experience', 'Experience & Achievements — 50+ Robotic Cystectomies', "Dr. Malek Gaashan's robotic cystectomy milestones, including 50+ robotic cystectomies with intracorporeal urinary diversion at University Hospitals of Leicester."],
    '/research' => ['research', 'Research & Publications', 'Peer-reviewed publications and academic contributions of Dr. Malek Gaashan, Consultant Urological Surgeon.'],
    '/conferences' => ['conferences', 'Conferences & Education', 'Speaking engagements and surgical education by Dr. Malek Gaashan, including an oral presentation on robotic cystectomy and intracorporeal reconstruction at AAU 2026, Jeddah.'],
    '/patient-information' => ['patient-information', 'Patient Information — Bladder Cancer, Robotic Cystectomy & Urinary Diversion', 'Clear, reassuring information about bladder cancer, robotic surgery, robotic cystectomy, ileal conduits and orthotopic neobladders, with answers to common questions.'],
    '/contact' => ['contact', 'Professional Enquiries & Contact', 'Professional, academic and collaboration enquiries for Dr. Malek Gaashan, Consultant Urological Surgeon.'],
];

function render_page(string $path, array $vars = []): never
{
    [$template, $title, $description] = PAGES[$path];
    $c = content();
    $body = render('pages/' . $template, ['c' => $c] + $vars);
    echo render('layout', [
        'c' => $c,
        'path' => $path,
        'title' => $title === null ? DEFAULT_TITLE : $title . ' — ' . SITE_NAME,
        'description' => $description ?? DEFAULT_DESCRIPTION,
        'body' => $body,
    ]);
    exit;
}

function not_found(): never
{
    http_response_code(404);
    echo render('404');
    exit;
}

$path = request_path();
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$isGet = $method === 'GET' || $method === 'HEAD';

// Canonicalise trailing slashes: /about/ -> /about
$rawPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if ($isGet && $rawPath !== '/' && str_ends_with($rawPath, '/')) {
    $query = $_SERVER['QUERY_STRING'] ?? '';
    redirect($path . ($query !== '' ? '?' . $query : ''), 301);
}

if ($path === '/admin' || str_starts_with($path, '/admin/')) {
    require APP_ROOT . '/app/admin.php';
    admin_dispatch($path, $method);
    exit;
}

if ($path === '/contact' && $method === 'POST') {
    require APP_ROOT . '/app/contact.php';
    $result = submit_enquiry($_POST);
    if (wants_json()) {
        json_response($result['ok'] ? ['ok' => true] : ['error' => $result['error']], $result['ok'] ? 200 : $result['status']);
    }
    // No-JavaScript fallback: re-render the page with the outcome.
    if (!$result['ok']) {
        http_response_code($result['status']);
    }
    render_page('/contact', ['form' => $result + ['old' => $result['ok'] ? [] : $_POST]]);
}

if ($isGet && $path === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-Agent: *\nAllow: /\nDisallow: /admin\n\nSitemap: " . site_url('/sitemap.xml') . "\n";
    exit;
}

if ($isGet && $path === '/sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach (array_keys(PAGES) as $page) {
        printf(
            "<url>\n<loc>%s</loc>\n<changefreq>monthly</changefreq>\n<priority>%s</priority>\n</url>\n",
            e(site_url($page)),
            $page === '/' ? '1' : '0.8'
        );
    }
    echo '</urlset>' . "\n";
    exit;
}

if ($isGet && isset(PAGES[$path])) {
    render_page($path);
}

not_found();
