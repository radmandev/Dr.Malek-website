<?php
defined('APP_ROOT') || exit;
/** @var array $enquiries @var int $total @var int $page @var int $pages @var bool $deleted */
?>
<main class="mx-auto max-w-4xl p-6 md:p-10">
  <div class="flex flex-wrap items-baseline justify-between gap-4 border-b border-midnight/10 pb-6">
    <h1 class="text-4xl text-midnight">Enquiries</h1>
    <p class="text-sm text-charcoal/60"><?= $total ?> total<?= $pages > 1 ? " · page $page of $pages" : '' ?></p>
  </div>
  <?php if ($deleted): ?><p role="status" class="mt-6 text-sm text-charcoal/70">Enquiry deleted.</p><?php endif ?>

  <?php if (!$enquiries): ?>
  <p class="mt-10 text-charcoal/70">No enquiries yet. Messages sent through the contact form will appear here<?= config('mail.to') ? ' and are also emailed to ' . e(config('mail.to')) : '' ?>.</p>
  <?php endif ?>

  <ol class="mt-8 space-y-6">
    <?php foreach ($enquiries as $q): ?>
    <li class="border border-midnight/10 bg-white p-6">
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
          <p class="font-serif text-2xl text-midnight"><?= e($q['subject']) ?></p>
          <p class="mt-1 text-sm text-charcoal/70">
            <?= e($q['name']) ?> · <a href="mailto:<?= e($q['email']) ?>?subject=<?= e(rawurlencode('Re: ' . $q['subject'])) ?>" class="underline decoration-champagne underline-offset-4 hover:text-midnight"><?= e($q['email']) ?></a>
          </p>
        </div>
        <time datetime="<?= e($q['created_at']) ?>" class="text-xs uppercase tracking-[0.16em] text-charcoal/50"><?= e(date('j M Y, H:i', strtotime($q['created_at']))) ?></time>
      </div>
      <p class="mt-5 whitespace-pre-line leading-relaxed text-charcoal/85"><?= e($q['message']) ?></p>
      <form method="post" action="/admin/enquiries/delete" class="mt-5 text-right" onsubmit="return confirm('Delete this enquiry permanently?')">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $q['id'] ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
        <button class="text-xs uppercase tracking-[0.16em] text-charcoal/50 hover:text-red-700">Delete</button>
      </form>
    </li>
    <?php endforeach ?>
  </ol>

  <?php if ($pages > 1): ?>
  <nav aria-label="Pages" class="mt-10 flex justify-between text-xs uppercase tracking-[0.2em]">
    <?php if ($page > 1): ?><a href="/admin/enquiries?page=<?= $page - 1 ?>" class="hover:text-bronze">← Newer</a><?php else: ?><span></span><?php endif ?>
    <?php if ($page < $pages): ?><a href="/admin/enquiries?page=<?= $page + 1 ?>" class="hover:text-bronze">Older →</a><?php endif ?>
  </nav>
  <?php endif ?>
</main>
