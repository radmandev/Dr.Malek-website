<?php
// Copy this file to config.php and fill it in. config.php is git-ignored: never commit real credentials.

return [
    // Public site URL without a trailing slash (canonical URLs, sitemap, Open Graph).
    // Leave '' to derive it from the incoming request (fine locally; set it in production).
    'site_url' => '',

    // MySQL / MariaDB. Leave 'name' empty to run without a database: the site then renders the
    // bundled content in app/content.php, /admin is unavailable, and enquiries are only emailed.
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => '',
        'user' => '',
        'pass' => '',
    ],

    // Enquiry notifications, sent with PHP mail().
    //   to   — where enquiries are emailed. '' = don't email (enquiries are still stored in the database).
    //   from — an address on this site's own domain; many hosts reject mail from domains they don't serve.
    'mail' => [
        'to' => '',
        'from' => '',
    ],

    // Show PHP errors in the browser. Keep false in production.
    'debug' => false,
];
