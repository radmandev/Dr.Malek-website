<?php
defined('APP_ROOT') || exit;
/** @var string $title @var array|null $admin @var string $current @var string $body */

$tabs = [['/admin', 'Content', 'editor'], ['/admin/enquiries', 'Enquiries', 'enquiries'], ['/admin/account', 'Account', 'account']];
?>
<!doctype html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title) ?> — Content Admin</title>
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="<?= e(asset('css/site.css')) ?>">
</head>
<body>
<div class="min-h-screen bg-ivory text-charcoal">
  <header class="flex flex-wrap items-center justify-between gap-4 bg-midnight px-6 py-4 text-ivory">
    <p class="font-serif text-2xl">Malek <span class="italic text-champagne-light">Gaashan</span> <span class="text-ivory/40">· Content</span></p>
    <?php if ($admin): ?>
    <nav aria-label="Admin" class="flex flex-wrap items-center gap-6 text-xs uppercase tracking-[0.2em]">
      <?php foreach ($tabs as [$href, $label, $key]): ?>
      <a href="<?= $href ?>"<?= $current === $key ? ' aria-current="page"' : '' ?> class="<?= $current === $key ? 'text-champagne-light' : 'text-ivory/70 hover:text-ivory' ?>"><?= $label ?></a>
      <?php endforeach ?>
      <a href="/" target="_blank" rel="noopener" class="text-ivory/70 hover:text-ivory">View site ↗</a>
      <form method="post" action="/admin/logout">
        <?= csrf_field() ?>
        <button class="uppercase tracking-[0.2em] text-ivory/70 hover:text-ivory">Sign out</button>
      </form>
    </nav>
    <?php endif ?>
  </header>
  <?= $body ?>
</div>
</body>
</html>
