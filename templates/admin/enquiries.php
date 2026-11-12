<?php
defined('APP_ROOT') || exit;
/** @var array $enquiries @var int $total @var int $page @var int $pages @var bool $deleted */

$initials = fn (string $name) => strtoupper(implode('', array_map(fn ($w) => mb_substr($w, 0, 1), array_slice(preg_split('/\s+/', trim($name)) ?: [], 0, 2))));
?>
<main class="mx-auto max-w-4xl px-5 pb-20 pt-8 sm:px-8 lg:px-12 lg:pt-12">
  <header class="flex flex-wrap items-end justify-between gap-4 border-b border-midnight/10 pb-8">
    <div>
      <p class="eyebrow text-bronze">Inbox</p>
      <h1 class="mt-3 text-4xl text-midnight md:text-[2.75rem]">Enquiries</h1>
      <p class="mt-2 text-charcoal/75">Messages sent through the website’s contact form, newest first.</p>
    </div>
    <p class="rounded-full bg-white px-3 py-1 text-xs text-charcoal/70 ring-1 ring-midnight/10"><?= $total ?> total<?= $pages > 1 ? " · page $page of $pages" : '' ?></p>
  </header>

  <?php if ($deleted): ?>
  <p role="status" class="mt-6 flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm text-charcoal/80 ring-1 ring-midnight/10"><?= icon('check', 'size-4 text-bronze') ?> Enquiry deleted.</p>
  <?php endif ?>

  <?php if (!$enquiries): ?>
  <div class="mt-10 flex flex-col items-center rounded-xl border border-dashed border-midnight/15 px-6 py-16 text-center">
    <span class="flex size-12 items-center justify-center rounded-full bg-sand text-bronze"><?= icon('inbox', 'size-5', 1.75) ?></span>
    <p class="mt-4 font-serif text-2xl text-midnight">No enquiries yet</p>
    <p class="mt-1 max-w-sm text-sm text-charcoal/70">Messages sent through the contact form will appear here<?= config('mail.to') ? ' and are also emailed to ' . e(config('mail.to')) : '' ?>.</p>
  </div>
  <?php endif ?>

  <ol class="mt-8 space-y-4">
    <?php foreach ($enquiries as $q): ?>
    <li class="rounded-xl border border-midnight/10 bg-white p-6">
      <div class="flex flex-wrap items-start gap-4">
        <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-sand font-semibold text-bronze"><?= e($initials($q['name'])) ?></span>
        <div class="min-w-0 flex-1">
          <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
            <p class="font-serif text-2xl leading-tight text-midnight"><?= e($q['subject']) ?></p>
            <time datetime="<?= e($q['created_at']) ?>" class="text-xs text-charcoal/60"><?= e(date('j M Y, H:i', strtotime($q['created_at']))) ?></time>
          </div>
          <p class="mt-1 text-sm text-charcoal/75"><?= e($q['name']) ?> · <?= e($q['email']) ?></p>
          <p class="mt-4 whitespace-pre-line leading-relaxed text-charcoal/90"><?= e($q['message']) ?></p>
          <div class="mt-5 flex flex-wrap items-center gap-2">
            <a href="mailto:<?= e($q['email']) ?>?subject=<?= e(rawurlencode('Re: ' . $q['subject'])) ?>" class="admin-btn-primary"><?= icon('mail', 'size-4', 1.75) ?> Reply by email</a>
            <form method="post" action="/admin/enquiries/delete" onsubmit="return confirm('Delete this enquiry permanently?')">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $q['id'] ?>">
              <input type="hidden" name="page" value="<?= $page ?>">
              <button class="admin-btn-ghost hover:bg-red-50 hover:text-red-800">Delete</button>
            </form>
          </div>
        </div>
      </div>
    </li>
    <?php endforeach ?>
  </ol>

  <?php if ($pages > 1): ?>
  <nav aria-label="Pages" class="mt-10 flex justify-between">
    <?php if ($page > 1): ?><a href="/admin/enquiries?page=<?= $page - 1 ?>" class="admin-btn-secondary">← Newer</a><?php else: ?><span></span><?php endif ?>
    <?php if ($page < $pages): ?><a href="/admin/enquiries?page=<?= $page + 1 ?>" class="admin-btn-secondary">Older →</a><?php endif ?>
  </nav>
  <?php endif ?>
</main>
