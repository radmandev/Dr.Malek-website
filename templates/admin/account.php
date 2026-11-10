<?php
defined('APP_ROOT') || exit;
/** @var string $email @var string $error @var bool $saved */
?>
<form method="post" action="/admin/account" class="mx-auto mt-16 max-w-md space-y-5 px-6">
  <h1 class="text-4xl text-midnight">Account</h1>
  <p class="text-sm text-charcoal/70">Signed in as <strong class="font-semibold text-midnight"><?= e($email) ?></strong>.</p>
  <h2 class="pt-4 text-2xl text-midnight">Change password</h2>
  <?= csrf_field() ?>
  <input name="current" type="password" required placeholder="Current password" autocomplete="current-password" class="w-full border border-midnight/20 bg-white px-4 py-3">
  <input name="password" type="password" required minlength="<?= PASSWORD_MIN_LENGTH ?>" placeholder="New password (at least <?= PASSWORD_MIN_LENGTH ?> characters)" autocomplete="new-password" class="w-full border border-midnight/20 bg-white px-4 py-3">
  <input name="confirm" type="password" required minlength="<?= PASSWORD_MIN_LENGTH ?>" placeholder="Confirm new password" autocomplete="new-password" class="w-full border border-midnight/20 bg-white px-4 py-3">
  <?php if ($error): ?><p role="alert" class="text-sm text-red-800"><?= e($error) ?></p><?php endif ?>
  <?php if ($saved): ?><p role="status" class="text-sm text-charcoal/80">Password changed.</p><?php endif ?>
  <button class="w-full bg-midnight py-3 text-xs font-semibold uppercase tracking-[0.2em] text-ivory">Update password</button>
</form>
