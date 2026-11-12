<?php
defined('APP_ROOT') || exit;

// /admin — content management, enquiries inbox and account settings.

// Every editable section: key (top-level key in app/content.php) => [title, one-line description].
const ADMIN_SECTIONS = [
    'profile' => ['Profile & Biography', 'Name, headline, introduction, portrait and biography used across the site.'],
    'credentials' => ['Credentials Strip', 'The scrolling line of credentials under the home-page header.'],
    'stats' => ['Key Figures', 'The animated numbers. Use verified figures only.'],
    'timeline' => ['Career Timeline', 'Hospitals and roles shown on the About page, newest first.'],
    'education' => ['Education & Qualifications', 'Degrees, fellowships and diplomas.'],
    'registrations' => ['Registrations', 'GMC and other professional registrations.'],
    'awards' => ['Awards', 'Awards and distinctions.'],
    'expertise' => ['Surgical Expertise', 'Each area of expertise: summary, full text, key points and photograph.'],
    'achievements' => ['Experience & Milestones', 'The 50+ milestone, reflections, tailored approaches and case imagery.'],
    'publications' => ['Publications', 'Published papers with journal, DOI and link.'],
    'academicContributions' => ['Forthcoming Work', 'Upcoming publications and academic contributions, one per line.'],
    'conferences' => ['Conferences & Talks', 'Presentations and speaking engagements. Tick “Featured” for the main one.'],
    'patientArticles' => ['Patient Articles', 'The educational articles on the Patient Information page.'],
    'faqs' => ['FAQs', 'Frequently asked questions and answers.'],
    'contact' => ['Contact Details', 'Institution, address, email, phone, LinkedIn and GMC number.'],
];
// Sidebar grouping: [label, icon, public page, sections].
const ADMIN_GROUPS = [
    ['Home & Profile', 'user', '/', ['profile', 'credentials', 'stats']],
    ['About', 'book-open', '/about', ['timeline', 'education', 'registrations', 'awards']],
    ['Expertise', 'stethoscope', '/expertise', ['expertise']],
    ['Experience', 'award', '/experience', ['achievements']],
    ['Research', 'file-text', '/research', ['publications', 'academicContributions']],
    ['Conferences', 'mic', '/conferences', ['conferences']],
    ['Patient Information', 'heart-pulse', '/patient-information', ['patientArticles', 'faqs']],
    ['Contact', 'mail', '/contact', ['contact']],
];
const LOGIN_MAX_FAILURES = 5;          // per IP address…
const LOGIN_WINDOW_MINUTES = 15;       // …within this many minutes
const SESSION_IDLE_SECONDS = 4 * 3600; // signed out after 4 hours of inactivity
const PASSWORD_MIN_LENGTH = 10;
const USERNAME_MAX_LENGTH = 190;
const UPLOAD_MAX_BYTES = 8 * 1024 * 1024;
const UPLOAD_TYPES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];

function admin_dispatch(string $path, string $method): never
{
    header('X-Robots-Tag: noindex, nofollow');
    header('Cache-Control: no-store');

    if (!db_configured()) {
        admin_notice(
            'Database not configured',
            'Copy <code>config.example.php</code> to <code>config.php</code>, fill in the <code>db</code> settings, then reload. See README → “Content management”.'
        );
    }
    try {
        db();
    } catch (PDOException $e) {
        error_log('Admin DB connection failed: ' . $e->getMessage());
        admin_notice('Cannot connect to the database', 'Check the <code>db</code> settings in <code>config.php</code> and that the MySQL server is running.');
    }

    admin_session_start();

    $needsSetup = !admin_exists();
    if ($path === '/admin/setup') {
        $needsSetup ? admin_setup($method) : redirect('/admin');
    }
    if ($needsSetup) {
        redirect('/admin/setup');
    }

    if ($path === '/admin/login' && $method === 'POST') {
        admin_login();
    }

    $admin = current_admin();
    if (!$admin) {
        if ($method !== 'GET') {
            wants_json()
                ? json_response(['error' => 'Your session has expired. Please sign in again in a new tab, then save.'], 401)
                : redirect('/admin');
        }
        admin_page('login', ['error' => '', 'username' => ''], 'Sign in');
    }

    match ([$method, $path]) {
        ['GET', '/admin'] => isset($_GET['section']) ? admin_editor() : admin_dashboard(),
        ['POST', '/admin/save'] => admin_save(),
        ['POST', '/admin/reset'] => admin_reset(),
        ['POST', '/admin/upload'] => admin_upload(),
        ['GET', '/admin/enquiries'] => admin_enquiries(),
        ['POST', '/admin/enquiries/delete'] => admin_enquiry_delete(),
        ['GET', '/admin/account'], ['POST', '/admin/account'] => admin_account($admin, $method),
        ['POST', '/admin/logout'] => admin_logout(),
        default => admin_notice('Not found', 'This admin page does not exist. <a href="/admin" class="underline">Back to content</a>.', 404),
    };
}

/* ---------- Rendering ---------- */

function admin_page(string $template, array $vars, string $title, int $status = 200): never
{
    http_response_code($status);
    echo render('admin/layout', [
        'title' => $title,
        'admin' => $admin = isset($_SESSION['admin_id']) ? current_admin() : null,
        'current' => $template,
        'section' => $vars['section'] ?? null,
        'enquiryCount' => $admin ? (int) db()->query('SELECT COUNT(*) FROM enquiries')->fetchColumn() : 0,
        'body' => render('admin/' . $template, $vars),
    ]);
    exit;
}

/** A simple message page. $html is trusted markup. */
function admin_notice(string $title, string $html, int $status = 200): never
{
    admin_page('notice', ['heading' => $title, 'html' => $html], $title, $status);
}

/* ---------- Session, CSRF, auth ---------- */

function admin_session_start(): void
{
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_name('drmalek_admin');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/admin',
        'secure' => is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();

    $now = time();
    if (isset($_SESSION['admin_id']) && $now - (int) ($_SESSION['last_seen'] ?? 0) > SESSION_IDLE_SECONDS) {
        $_SESSION = [];
        session_regenerate_id(true);
    }
    $_SESSION['last_seen'] = $now;
}

function csrf_token(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (is_string($token) && hash_equals(csrf_token(), $token)) {
        return;
    }
    if (wants_json()) {
        json_response(['error' => 'This page has expired. Reload it and try again.'], 419);
    }
    admin_notice('Page expired', 'Your session changed since this page was loaded. <a href="/admin" class="underline">Reload and try again</a>.', 419);
}

function admin_exists(): bool
{
    try {
        return (bool) db()->query('SELECT 1 FROM admins LIMIT 1')->fetchColumn();
    } catch (PDOException) {
        return false; // tables not created yet
    }
}

function current_admin(): ?array
{
    static $admin = false;
    if ($admin === false) {
        $admin = null;
        if (isset($_SESSION['admin_id'])) {
            $stmt = db()->prepare('SELECT id, email, password_hash FROM admins WHERE id = ?');
            $stmt->execute([(int) $_SESSION['admin_id']]);
            $admin = $stmt->fetch() ?: null;
        }
    }
    return $admin;
}

function admin_sign_in(int $id): void
{
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $id;
    unset($_SESSION['csrf']);
    db()->prepare('UPDATE admins SET last_login_at = NOW() WHERE id = ?')->execute([$id]);
}

function admin_login(): never
{
    csrf_check();
    $username = normalise_username($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $ip = client_ip();

    $fails = db()->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND attempted_at > NOW() - INTERVAL ' . LOGIN_WINDOW_MINUTES . ' MINUTE');
    $fails->execute([$ip]);
    if ((int) $fails->fetchColumn() >= LOGIN_MAX_FAILURES) {
        admin_page('login', [
            'error' => 'Too many failed sign-in attempts. Please wait ' . LOGIN_WINDOW_MINUTES . ' minutes and try again.',
            'username' => $username,
        ], 'Sign in', 429);
    }

    $stmt = db()->prepare('SELECT id, password_hash FROM admins WHERE email = ?'); // `email` holds the sign-in name
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    // Verify against a throwaway hash when the username is unknown, so response time doesn't reveal which usernames exist.
    $valid = password_verify($password, $row['password_hash'] ?? password_hash('timing-equaliser', PASSWORD_DEFAULT));

    if (!$row || !$valid) {
        db()->prepare('INSERT INTO login_attempts (ip) VALUES (?)')->execute([$ip]);
        db()->exec('DELETE FROM login_attempts WHERE attempted_at < NOW() - INTERVAL 1 DAY');
        admin_page('login', ['error' => 'Incorrect username or password.', 'username' => $username], 'Sign in', 401);
    }

    db()->prepare('DELETE FROM login_attempts WHERE ip = ?')->execute([$ip]);
    if (password_needs_rehash($row['password_hash'], PASSWORD_DEFAULT)) {
        db()->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $row['id']]);
    }
    admin_sign_in((int) $row['id']);
    redirect('/admin');
}

function admin_logout(): never
{
    csrf_check();
    $_SESSION = [];
    $params = session_get_cookie_params();
    setcookie(session_name(), '', ['expires' => time() - 3600] + array_intersect_key($params, array_flip(['path', 'domain', 'secure', 'httponly', 'samesite'])));
    session_destroy();
    redirect('/admin');
}

/** Sign-in names are case-insensitive and ignore extra spaces: “ Malek  Gaashan ” → “malek gaashan”. */
function normalise_username(mixed $value): string
{
    return mb_strtolower(trim((string) preg_replace('/\s+/u', ' ', (string) $value)));
}

/** Returns an error message, or '' if the username is acceptable. */
function username_problem(string $username): string
{
    if (mb_strlen($username) < 3) {
        return 'Please choose a username of at least 3 characters.';
    }
    if (mb_strlen($username) > USERNAME_MAX_LENGTH || preg_match('/[\p{C}]/u', $username)) {
        return 'Please choose a shorter username, using letters, numbers, spaces or symbols.';
    }
    return '';
}

/** Returns an error message, or '' if the new password is acceptable. */
function password_problem(string $password, string $confirm): string
{
    if (mb_strlen($password) < PASSWORD_MIN_LENGTH) {
        return 'Use a password of at least ' . PASSWORD_MIN_LENGTH . ' characters.';
    }
    if (!hash_equals($password, $confirm)) {
        return 'The two passwords do not match.';
    }
    return '';
}

/** First run: creates the tables and the first administrator. Only reachable while no administrator exists. */
function admin_setup(string $method): never
{
    $error = '';
    $username = '';
    if ($method === 'POST') {
        csrf_check();
        $username = normalise_username($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $error = username_problem($username) ?: password_problem($password, (string) ($_POST['confirm'] ?? ''));

        if ($error === '') {
            try {
                db_install();
                if (admin_exists()) {
                    redirect('/admin');
                }
                db()->prepare('INSERT INTO admins (email, password_hash) VALUES (?, ?)')
                    ->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
                admin_sign_in((int) db()->lastInsertId());
                redirect('/admin');
            } catch (PDOException $e) {
                error_log('Admin setup failed: ' . $e->getMessage());
                $error = 'Could not create the database tables. Check that the database user can CREATE tables, or import database/schema.sql manually.';
            }
        }
    }
    admin_page('setup', ['error' => $error, 'username' => $username], 'Set up');
}

/* ---------- Content editor ---------- */

/** Section key => when it was last saved (sections never saved use the bundled default). */
function admin_saved_sections(): array
{
    return db()->query('SELECT section, updated_at FROM site_content')->fetchAll(PDO::FETCH_KEY_PAIR);
}

function admin_group_of(string $section): array
{
    foreach (ADMIN_GROUPS as $group) {
        if (in_array($section, $group[3], true)) {
            return $group;
        }
    }
    return ADMIN_GROUPS[0];
}

function admin_dashboard(): never
{
    $latest = db()->query('SELECT name, subject, created_at FROM enquiries ORDER BY id DESC LIMIT 3')->fetchAll();
    admin_page('dashboard', ['saved' => admin_saved_sections(), 'latest' => $latest], 'Overview');
}

function admin_editor(): never
{
    $defaults = default_content();
    $section = (string) ($_GET['section'] ?? 'profile');
    if (!isset(ADMIN_SECTIONS[$section]) || !array_key_exists($section, $defaults)) {
        $section = 'profile';
    }
    $saved = admin_saved_sections();
    admin_page('editor', [
        'section' => $section,
        'group' => admin_group_of($section),
        'savedAt' => $saved[$section] ?? null,
        'data' => [
            'section' => $section,
            'value' => content()[$section],
            'template' => $defaults[$section],
            'customised' => isset($saved[$section]),
            'csrf' => csrf_token(),
        ],
    ], ADMIN_SECTIONS[$section][0]);
}

/** Discards the saved version of a section so the site shows the bundled original again. */
function admin_reset(): never
{
    csrf_check();
    $input = json_decode((string) file_get_contents('php://input'), true);
    $section = $input['section'] ?? null;
    if (!is_string($section) || !isset(ADMIN_SECTIONS[$section])) {
        json_response(['error' => 'Unknown section.'], 400);
    }
    db()->prepare('DELETE FROM site_content WHERE section = ?')->execute([$section]);
    json_response(['ok' => true, 'value' => default_content()[$section]]);
}

function admin_save(): never
{
    csrf_check();
    $input = json_decode((string) file_get_contents('php://input'), true);
    $section = $input['section'] ?? null;
    $value = $input['value'] ?? null;
    $defaults = default_content();

    if (!is_string($section) || !isset(ADMIN_SECTIONS[$section]) || !array_key_exists($section, $defaults)) {
        json_response(['error' => 'Unknown section.'], 400);
    }
    // Lists must stay lists and groups of fields must stay groups, so the public pages can render them.
    $expectList = array_is_list($defaults[$section]);
    if (!is_array($value) || ($expectList ? !array_is_list($value) : ($value === [] || array_is_list($value)))) {
        json_response(['error' => 'That content is not in the expected format.'], 400);
    }
    $json = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false || strlen($json) > 2 * 1024 * 1024) {
        json_response(['error' => 'That content is too large to save.'], 400);
    }

    db()->prepare('INSERT INTO site_content (section, data) VALUES (?, ?) ON DUPLICATE KEY UPDATE data = ?')
        ->execute([$section, $json, $json]);
    json_response(['ok' => true]);
}

function admin_upload(): never
{
    csrf_check();
    $file = $_FILES['file'] ?? null;
    $code = is_array($file) ? ($file['error'] ?? UPLOAD_ERR_NO_FILE) : UPLOAD_ERR_NO_FILE;
    if ($code === UPLOAD_ERR_INI_SIZE || $code === UPLOAD_ERR_FORM_SIZE || ($code === UPLOAD_ERR_OK && $file['size'] > UPLOAD_MAX_BYTES)) {
        json_response(['error' => 'That image is too large. The limit is ' . (UPLOAD_MAX_BYTES >> 20) . ' MB (and your server’s upload_max_filesize).'], 400);
    }
    if ($code !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        json_response(['error' => 'The upload did not complete. Please try again.'], 400);
    }

    $info = @getimagesize($file['tmp_name']);
    $ext = UPLOAD_TYPES[$info['mime'] ?? ''] ?? null;
    if (!$ext) {
        json_response(['error' => 'Please upload a JPEG, PNG, WebP or GIF image.'], 400);
    }

    $base = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower(pathinfo((string) $file['name'], PATHINFO_FILENAME))), '-');
    $name = date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '-' . (substr($base, 0, 60) ?: 'image') . '.' . $ext;
    $dir = APP_ROOT . '/public/uploads';
    if ((!is_dir($dir) && !@mkdir($dir, 0755, true)) || !move_uploaded_file($file['tmp_name'], "$dir/$name")) {
        json_response(['error' => 'The image could not be saved. Check that public/uploads is writable by the web server.'], 500);
    }
    @chmod("$dir/$name", 0644);
    json_response(['url' => '/uploads/' . $name]);
}

/* ---------- Enquiries ---------- */

function admin_enquiries(): never
{
    $perPage = 25;
    $total = (int) db()->query('SELECT COUNT(*) FROM enquiries')->fetchColumn();
    $pages = max(1, (int) ceil($total / $perPage));
    $page = min($pages, max(1, (int) ($_GET['page'] ?? 1)));

    $stmt = db()->prepare('SELECT id, name, email, subject, message, created_at FROM enquiries ORDER BY id DESC LIMIT ? OFFSET ?');
    $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
    $stmt->bindValue(2, ($page - 1) * $perPage, PDO::PARAM_INT);
    $stmt->execute();

    admin_page('enquiries', [
        'enquiries' => $stmt->fetchAll(),
        'total' => $total,
        'page' => $page,
        'pages' => $pages,
        'deleted' => isset($_GET['deleted']),
    ], 'Enquiries');
}

function admin_enquiry_delete(): never
{
    csrf_check();
    db()->prepare('DELETE FROM enquiries WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
    redirect('/admin/enquiries?deleted=1&page=' . max(1, (int) ($_POST['page'] ?? 1)));
}

/* ---------- Account ---------- */

function admin_account(array $admin, string $method): never
{
    $error = '';
    $saved = false;
    if ($method === 'POST') {
        csrf_check();
        $new = (string) ($_POST['password'] ?? '');
        $error = !password_verify((string) ($_POST['current'] ?? ''), $admin['password_hash'])
            ? 'Your current password is incorrect.'
            : password_problem($new, (string) ($_POST['confirm'] ?? ''));
        if ($error === '') {
            db()->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')
                ->execute([password_hash($new, PASSWORD_DEFAULT), $admin['id']]);
            session_regenerate_id(true);
            $saved = true;
        }
    }
    admin_page('account', ['username' => $admin['email'], 'error' => $error, 'saved' => $saved], 'Account', $error ? 400 : 200);
}
