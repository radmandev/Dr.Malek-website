<?php
defined('APP_ROOT') || exit;
/** @var array $saved section => updated_at @var array $latest */

$hour = (int) date('G');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
?>
<main class="mx-auto max-w-6xl px-5 py-10 sm:px-8 lg:px-12 lg:py-14">
  <header class="flex flex-wrap items-end justify-between gap-6">
    <div>
      <p class="eyebrow text-bronze">Overview</p>
      <h1 class="mt-3 text-4xl text-midnight md:text-5xl"><?= e($greeting) ?>.</h1>
      <p class="mt-2 max-w-xl text-charcoal/75">Choose what you’d like to update. Each card matches a page of the website.</p>
    </div>
    <a href="/" target="_blank" rel="noopener" class="admin-btn-secondary"><?= icon('external-link', 'size-4', 1.75) ?> View website</a>
  </header>

  <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
    <?php foreach (ADMIN_GROUPS as [$label, $groupIcon, $page, $keys]): ?>
    <section class="flex flex-col rounded-xl border border-midnight/10 bg-white p-6 transition-shadow duration-300 hover:shadow-[0_24px_50px_-30px_rgba(16,29,44,0.35)]">
      <div class="flex items-start justify-between gap-4">
        <div class="flex items-center gap-3">
          <span class="flex size-10 items-center justify-center rounded-full bg-sand text-bronze"><?= icon($groupIcon, 'size-[1.1rem]', 1.75) ?></span>
          <h2 class="font-serif text-2xl text-midnight"><?= e($label) ?></h2>
        </div>
        <a href="<?= e($page) ?>" target="_blank" rel="noopener" class="rounded-md p-1.5 text-charcoal/50 hover:bg-ivory hover:text-bronze" aria-label="View the <?= e($label) ?> page" title="View page"><?= icon('external-link', 'size-4', 1.75) ?></a>
      </div>
      <ul class="mt-5 flex-1 divide-y divide-midnight/[0.07] border-t border-midnight/[0.07]">
        <?php foreach ($keys as $key): ?>
        <li>
          <a href="/admin?section=<?= e($key) ?>" class="group flex items-center gap-3 py-3">
            <span class="min-w-0 flex-1">
              <span class="block text-[0.92rem] font-semibold text-midnight group-hover:text-bronze"><?= e(ADMIN_SECTIONS[$key][0]) ?></span>
              <span class="mt-0.5 block text-xs text-charcoal/60">
                <?= isset($saved[$key]) ? 'Edited ' . e(date('j M Y', strtotime($saved[$key]))) : 'Original content' ?>
              </span>
            </span>
            <?= icon('chevron-right', 'size-4 shrink-0 text-charcoal/35 transition-transform group-hover:translate-x-0.5 group-hover:text-bronze', 1.75) ?>
          </a>
        </li>
        <?php endforeach ?>
      </ul>
    </section>
    <?php endforeach ?>

    <section class="flex flex-col rounded-xl border border-midnight/10 bg-white p-6">
      <div class="flex items-center gap-3">
        <span class="flex size-10 items-center justify-center rounded-full bg-midnight text-ivory"><?= icon('inbox', 'size-[1.1rem]', 1.75) ?></span>
        <h2 class="font-serif text-2xl text-midnight">Latest enquiries</h2>
      </div>
      <?php if ($latest): ?>
      <ul class="mt-5 flex-1 divide-y divide-midnight/[0.07] border-t border-midnight/[0.07]">
        <?php foreach ($latest as $q): ?>
        <li class="py-3">
          <p class="truncate text-[0.92rem] font-semibold text-midnight"><?= e($q['subject']) ?></p>
          <p class="mt-0.5 truncate text-xs text-charcoal/60"><?= e($q['name']) ?> · <?= e(date('j M', strtotime($q['created_at']))) ?></p>
        </li>
        <?php endforeach ?>
      </ul>
      <a href="/admin/enquiries" class="mt-4 text-sm font-semibold text-bronze hover:text-midnight">See all enquiries →</a>
      <?php else: ?>
      <p class="mt-5 flex-1 text-sm leading-relaxed text-charcoal/70">No enquiries yet. Messages sent through the website’s contact form will appear here.</p>
      <?php endif ?>
    </section>
  </div>

  <section class="mt-10 grid gap-6 rounded-xl bg-sand/70 p-6 text-sm leading-relaxed text-charcoal/80 md:grid-cols-3 md:p-8">
    <div>
      <p class="font-serif text-xl text-midnight">1 · Choose</p>
      <p class="mt-1">Open a section from a card above or from the menu.</p>
    </div>
    <div>
      <p class="font-serif text-xl text-midnight">2 · Edit</p>
      <p class="mt-1">Change text, reorder or add items, and upload photographs.</p>
    </div>
    <div>
      <p class="font-serif text-xl text-midnight">3 · Save</p>
      <p class="mt-1">Press <strong>Save changes</strong> (or <kbd class="rounded border border-midnight/15 bg-white px-1.5 text-xs">Ctrl/⌘ S</kbd>). The website updates immediately.</p>
    </div>
  </section>
</main>
