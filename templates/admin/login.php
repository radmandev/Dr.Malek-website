<?php
defined('APP_ROOT') || exit;
/** @var string $error @var string $email */
?>
<form method="post" action="/admin/login" class="mx-auto mt-24 max-w-sm space-y-5 px-6">
  <h1 class="text-4xl text-midnight">Sign in</h1>
  <?= csrf_field() ?>
  <input name="email" type="email" required placeholder="Email" autocomplete="username" value="<?= e($email) ?>" class="w-full border border-midnight/20 bg-white px-4 py-3">
  <input name="password" type="password" required placeholder="Password" autocomplete="current-password" class="w-full border border-midnight/20 bg-white px-4 py-3">
  <?php if ($error): ?><p role="alert" class="text-sm text-red-800"><?= e($error) ?></p><?php endif ?>
  <button class="w-full bg-midnight py-3 text-xs font-semibold uppercase tracking-[0.2em] text-ivory">Sign in</button>
</form>
