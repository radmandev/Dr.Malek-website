<?php
defined('APP_ROOT') || exit;
/** @var string $section @var array $data */
?>
<div class="grid min-h-[calc(100vh-64px)] md:grid-cols-[16rem_1fr]">
  <nav aria-label="Sections" class="border-r border-midnight/10 bg-stone/30 p-4">
    <ul class="space-y-1">
      <?php foreach (ADMIN_SECTIONS as $key => $label): ?>
      <li>
        <a href="/admin?section=<?= e($key) ?>" data-section-link<?= $key === $section ? ' aria-current="page"' : '' ?> class="block w-full px-3 py-2 text-left text-sm <?= $key === $section ? 'bg-midnight text-ivory' : 'hover:bg-stone/60' ?>"><?= e($label) ?></a>
      </li>
      <?php endforeach ?>
    </ul>
  </nav>
  <main class="p-6 md:p-10">
    <div class="sticky top-0 z-10 -mx-6 mb-8 flex flex-wrap items-center justify-between gap-4 border-b border-midnight/10 bg-ivory/95 px-6 py-4 backdrop-blur md:-mx-10 md:px-10">
      <h1 class="text-3xl text-midnight"><?= e(ADMIN_SECTIONS[$section]) ?></h1>
      <div class="flex items-center gap-4">
        <span data-status role="status" class="text-xs text-charcoal/60"></span>
        <button type="button" data-save disabled class="bg-champagne px-6 py-2.5 text-xs font-semibold uppercase tracking-[0.2em] text-midnight disabled:opacity-40">Save</button>
      </div>
    </div>
    <div id="editor"><noscript>The content editor needs JavaScript enabled.</noscript></div>
  </main>
</div>
<script type="application/json" id="editor-data"><?= json_for_script($data) ?></script>
<script src="<?= e(asset('js/admin.js')) ?>"></script>
