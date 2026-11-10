<?php
defined('APP_ROOT') || exit;
/** @var string $error @var string $email */
?>
<form method="post" action="/admin/setup" class="mx-auto mt-20 max-w-md space-y-5 px-6">
  <h1 class="text-4xl text-midnight">Welcome</h1>
  <p class="text-sm leading-relaxed text-charcoal/70">
    Create the administrator account. This also creates the database tables. This page is only available until the first account exists.
  </p>
  <?= csrf_field() ?>
  <input name="email" type="email" required placeholder="Email" autocomplete="username" value="<?= e($email) ?>" class="w-full border border-midnight/20 bg-white px-4 py-3">
  <input name="password" type="password" required minlength="<?= PASSWORD_MIN_LENGTH ?>" placeholder="Password (at least <?= PASSWORD_MIN_LENGTH ?> characters)" autocomplete="new-password" class="w-full border border-midnight/20 bg-white px-4 py-3">
  <input name="confirm" type="password" required minlength="<?= PASSWORD_MIN_LENGTH ?>" placeholder="Confirm password" autocomplete="new-password" class="w-full border border-midnight/20 bg-white px-4 py-3">
  <?php if ($error): ?><p role="alert" class="text-sm text-red-800"><?= e($error) ?></p><?php endif ?>
  <button class="w-full bg-midnight py-3 text-xs font-semibold uppercase tracking-[0.2em] text-ivory">Create account</button>
</form>
