<?php
defined('APP_ROOT') || exit;
/** @var string $heading @var string $html  Trusted markup. */
?>
<div class="mx-auto mt-20 max-w-xl border-l border-champagne px-6">
  <h1 class="text-3xl text-midnight"><?= e($heading) ?></h1>
  <p class="mt-4 text-sm leading-relaxed text-charcoal/70 [&_code]:bg-stone/60 [&_code]:px-1"><?= $html ?></p>
</div>
