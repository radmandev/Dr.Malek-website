<?php
defined('APP_ROOT') || exit;
/** @var string $title @var array|null $admin @var string $current @var ?string $section @var int $enquiryCount @var string $body */

$link = fn (bool $active) => 'group flex items-center gap-3 rounded-md px-3 py-2 text-[0.84rem] transition-colors duration-200 '
    . ($active ? 'bg-sand font-semibold text-midnight' : 'text-charcoal/75 hover:bg-ivory hover:text-midnight');
?>
<!doctype html>
<html lang="en-GB" class="js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title) ?> — Website Editor</title>
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="preload" href="/assets/fonts/dm-sans-normal-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset('css/site.css')) ?>">
</head>
<body class="bg-ivory text-charcoal">
<?php if ($admin): ?>
<div class="min-h-screen lg:grid lg:grid-cols-[17.5rem_1fr]">
  <!-- Sidebar (drawer on small screens) -->
  <div data-nav-backdrop hidden class="fixed inset-0 z-40 bg-midnight/30 backdrop-blur-[2px] lg:hidden"></div>
  <aside data-nav class="fixed inset-y-0 left-0 z-50 flex w-[17.5rem] -translate-x-full flex-col border-r border-midnight/10 bg-white transition-transform duration-300 ease-editorial data-open:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
    <div class="flex items-center justify-between border-b border-midnight/10 px-6 py-5">
      <a href="/admin" class="leading-none">
        <span class="block font-serif text-[1.45rem] text-midnight">Dr. Malek <span class="italic text-bronze">Gaashan</span></span>
        <span class="mt-1.5 block text-[0.6rem] font-semibold uppercase tracking-[0.2em] text-charcoal/55">Website editor</span>
      </a>
      <button type="button" data-nav-close class="rounded-md p-1.5 text-charcoal/60 hover:bg-ivory lg:hidden" aria-label="Close menu"><?= icon('x', 'size-5', 1.5) ?></button>
    </div>

    <nav aria-label="Editor" class="flex-1 overflow-y-auto px-3 py-4">
      <a href="/admin"<?= $current === 'dashboard' ? ' aria-current="page"' : '' ?> class="<?= $link($current === 'dashboard') ?>">
        <?= icon('layout-grid', 'size-4 shrink-0 text-bronze', 1.75) ?> Overview
      </a>
      <?php foreach (ADMIN_GROUPS as [$label, $groupIcon, $page, $keys]): ?>
      <p class="mb-1 mt-5 flex items-center gap-2 px-3 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-charcoal/50">
        <?= icon($groupIcon, 'size-3.5', 1.75) ?> <?= e($label) ?>
      </p>
      <?php foreach ($keys as $key): $active = $current === 'editor' && $section === $key; ?>
      <a href="/admin?section=<?= e($key) ?>" data-section-link<?= $active ? ' aria-current="page"' : '' ?> class="<?= $link($active) ?> pl-9">
        <?= e(ADMIN_SECTIONS[$key][0]) ?>
      </a>
      <?php endforeach ?>
      <?php endforeach ?>
    </nav>

    <div class="space-y-0.5 border-t border-midnight/10 px-3 py-3">
      <a href="/admin/enquiries"<?= $current === 'enquiries' ? ' aria-current="page"' : '' ?> class="<?= $link($current === 'enquiries') ?>">
        <?= icon('inbox', 'size-4 shrink-0 text-bronze', 1.75) ?> Enquiries
        <?php if ($enquiryCount): ?><span class="ml-auto rounded-full bg-midnight px-2 py-0.5 text-[0.65rem] font-semibold text-ivory"><?= $enquiryCount ?></span><?php endif ?>
      </a>
      <a href="/admin/account"<?= $current === 'account' ? ' aria-current="page"' : '' ?> class="<?= $link($current === 'account') ?>">
        <?= icon('lock', 'size-4 shrink-0 text-bronze', 1.75) ?> Password
      </a>
      <a href="/" target="_blank" rel="noopener" class="<?= $link(false) ?>">
        <?= icon('external-link', 'size-4 shrink-0 text-bronze', 1.75) ?> View website
      </a>
      <form method="post" action="/admin/logout">
        <?= csrf_field() ?>
        <button class="<?= $link(false) ?> w-full">
          <?= icon('log-out', 'size-4 shrink-0 text-bronze', 1.75) ?> Sign out
        </button>
      </form>
      <p class="truncate px-3 pt-2 text-[0.7rem] text-charcoal/50" title="<?= e($admin['email']) ?>"><?= e($admin['email']) ?></p>
    </div>
  </aside>

  <div class="min-w-0">
    <!-- Top bar on small screens -->
    <div class="sticky top-0 z-30 flex items-center justify-between border-b border-midnight/10 bg-white/90 px-4 py-3 backdrop-blur lg:hidden">
      <button type="button" data-nav-open class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm font-semibold text-midnight hover:bg-ivory" aria-label="Open menu">
        <?= icon('menu', 'size-5', 1.5) ?> Menu
      </button>
      <span class="font-serif text-lg text-midnight">Dr. Malek <span class="italic text-bronze">Gaashan</span></span>
      <a href="/" target="_blank" rel="noopener" class="rounded-md p-2 text-charcoal/70 hover:bg-ivory" aria-label="View website"><?= icon('external-link', 'size-4', 1.75) ?></a>
    </div>
    <?= $body ?>
  </div>
</div>
<?php else: ?>
<div class="flex min-h-screen items-center justify-center bg-[radial-gradient(70%_60%_at_50%_0%,#efe9df_0%,#f7f5f0_70%)] px-5 py-16">
  <div class="w-full max-w-[26rem]">
    <p class="text-center font-serif text-3xl text-midnight">Dr. Malek <span class="italic text-bronze">Gaashan</span></p>
    <p class="mt-2 text-center text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-charcoal/55">Website editor</p>
    <div class="admin-card mt-8 rounded-xl border border-midnight/10 bg-white p-8 shadow-[0_30px_60px_-35px_rgba(16,29,44,0.35)] sm:p-10">
      <?= $body ?>
    </div>
    <p class="mt-6 text-center text-xs text-charcoal/50"><a href="/" class="hover:text-bronze">← Back to the website</a></p>
  </div>
</div>
<?php endif ?>
<script src="<?= e(asset('js/admin.js')) ?>"></script>
</body>
</html>
