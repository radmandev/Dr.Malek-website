<?php
defined('APP_ROOT') || exit;
/** @var string $heading @var string $html  Trusted markup. */
?>
<div class="admin-notice mx-auto max-w-xl px-6 py-16 lg:py-24">
  <h1 class="text-3xl text-midnight"><?= e($heading) ?></h1>
  <p class="mt-4 text-sm leading-relaxed text-charcoal/75 [&_a]:text-bronze [&_a]:underline [&_code]:rounded [&_code]:bg-sand [&_code]:px-1.5 [&_code]:py-0.5"><?= $html ?></p>
</div>
