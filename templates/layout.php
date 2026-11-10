<?php
defined('APP_ROOT') || exit;
/** @var array $c @var string $path @var string $title @var string $description @var string $body */

$canonical = site_url($path);
$ogImage = site_url('/og-image.png');
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Physician',
    'name' => 'Dr. Malek Gaashan',
    'honorificSuffix' => 'MD, MRCS, FRCS (Urol)',
    'url' => site_url('/'),
    'sameAs' => array_values(array_filter([safe_url($c['contact']['linkedin'] ?? '')])),
    'medicalSpecialty' => 'Urologic',
    'description' => DEFAULT_DESCRIPTION,
    'jobTitle' => 'Consultant Urological Surgeon',
    'worksFor' => ['@type' => 'Hospital', 'name' => 'University Hospitals of Leicester NHS Trust'],
    'alumniOf' => [
        ['@type' => 'CollegeOrUniversity', 'name' => 'Mutah University'],
        ['@type' => 'EducationalOrganization', 'name' => 'Royal College of Surgeons in Ireland'],
    ],
    'knowsAbout' => [
        'Robotic radical cystectomy',
        'Bladder cancer',
        'Intracorporeal urinary diversion',
        'Ileal conduit',
        'Orthotopic neobladder',
        'Uro-oncology',
    ],
];
?>
<!doctype html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
  // Enables scroll animations; if site.js fails to load, content is shown anyway.
  document.documentElement.classList.add('js');
  setTimeout(function () { if (!window.siteReady) document.documentElement.classList.remove('js'); }, 3000);
</script>
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($description) ?>">
<meta name="keywords" content="Dr Malek Gaashan, Robotic Urology Surgeon, Robotic Cystectomy, Bladder Cancer Surgeon, Intracorporeal Urinary Diversion, Consultant Urological Surgeon UK, Leicester urologist">
<meta name="author" content="Dr. Malek Gaashan">
<meta name="theme-color" content="#101D2C">
<link rel="canonical" href="<?= e($canonical) ?>">
<meta property="og:type" content="website">
<meta property="og:locale" content="en_GB">
<meta property="og:site_name" content="Dr. Malek Gaashan">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($description) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Dr. Malek Gaashan — Consultant Robotic Urological Surgeon">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($title) ?>">
<meta name="twitter:description" content="<?= e($description) ?>">
<meta name="twitter:image" content="<?= e($ogImage) ?>">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="preload" href="/assets/fonts/manrope-normal-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/fonts/cormorant-garamond-normal-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset('css/site.css')) ?>">
<script src="<?= e(asset('js/site.js')) ?>" defer></script>
<script type="application/ld+json"><?= json_for_script($jsonLd) ?></script>
</head>
<body>
<?= render('partials/header', ['path' => $path, 'expertise' => $c['expertise']]) ?>
<main id="main">
<?= $body ?>
</main>
<?= render('partials/footer', ['c' => $c]) ?>
</body>
</html>
