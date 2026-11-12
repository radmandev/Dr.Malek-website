<?php
defined('APP_ROOT') || exit;
/** @var string $section @var array $group [label, icon, page, sections] @var ?string $savedAt @var array $data */

[$title, $description] = ADMIN_SECTIONS[$section];
[$groupLabel, $groupIcon, $groupPage] = $group;
?>
<main class="mx-auto max-w-4xl px-5 pb-40 pt-8 sm:px-8 lg:px-12 lg:pt-12">
  <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-xs text-charcoal/60">
    <a href="/admin" class="hover:text-bronze">Overview</a>
    <?= icon('chevron-right', 'size-3.5', 1.75) ?>
    <span><?= e($groupLabel) ?></span>
  </nav>

  <header class="mt-4 flex flex-wrap items-start justify-between gap-5 border-b border-midnight/10 pb-8">
    <div class="min-w-0">
      <h1 class="text-4xl text-midnight md:text-[2.75rem]"><?= e($title) ?></h1>
      <p class="mt-2 max-w-xl text-charcoal/75"><?= e($description) ?></p>
      <p data-saved-at class="mt-3 inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-xs text-charcoal/70 ring-1 ring-midnight/10">
        <span class="size-1.5 rounded-full <?= $savedAt ? 'bg-champagne' : 'bg-charcoal/30' ?>"></span>
        <?= $savedAt ? 'Last edited ' . e(date('j M Y, H:i', strtotime($savedAt))) : 'Showing the original content' ?>
      </p>
    </div>
    <a href="<?= e($groupPage) ?>" target="_blank" rel="noopener" class="admin-btn-secondary shrink-0">
      <?= icon('external-link', 'size-4', 1.75) ?> View on website
    </a>
  </header>

  <div id="editor" class="mt-8 space-y-6"><noscript>The editor needs JavaScript enabled in your browser.</noscript></div>

  <div data-reset-wrap class="mt-14 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-dashed border-midnight/15 px-5 py-4 text-sm text-charcoal/70"<?= $data['customised'] ? '' : ' hidden' ?>>
    <span>Want to undo all edits to this section?</span>
    <button type="button" data-reset class="admin-btn-ghost text-red-800 hover:bg-red-50 hover:text-red-900">Restore original content</button>
  </div>
</main>

<!-- Floating save bar: slides up when there are unsaved changes -->
<div data-savebar aria-hidden="true" class="pointer-events-none fixed inset-x-0 bottom-0 z-30 translate-y-[120%] px-4 pb-4 transition-transform duration-300 ease-editorial data-show:pointer-events-auto data-show:translate-y-0 lg:left-[17.5rem]">
  <div class="mx-auto flex max-w-3xl flex-wrap items-center justify-between gap-3 rounded-xl bg-midnight px-5 py-3.5 text-ivory shadow-[0_24px_60px_-20px_rgba(16,29,44,0.6)]">
    <p data-status class="flex items-center gap-2.5 text-sm"><span class="size-2 animate-pulse rounded-full bg-champagne"></span> Unsaved changes</p>
    <div class="flex items-center gap-2">
      <button type="button" data-discard class="rounded-md px-4 py-2 text-sm text-ivory/75 transition-colors hover:bg-white/10 hover:text-ivory">Discard</button>
      <button type="button" data-save class="admin-btn-gold">Save changes</button>
    </div>
  </div>
</div>

<div data-toast role="status" aria-live="polite" class="pointer-events-none fixed right-4 top-4 z-[60] flex translate-y-[-150%] items-center gap-3 rounded-xl bg-white px-4 py-3 text-sm text-midnight opacity-0 shadow-[0_20px_50px_-20px_rgba(16,29,44,0.45)] ring-1 ring-midnight/10 transition-all duration-300 ease-editorial data-show:translate-y-0 data-show:opacity-100"></div>

<script type="application/json" id="editor-data"><?= json_for_script($data) ?></script>
